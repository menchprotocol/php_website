<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Apiai_model extends CI_Model {
	
	var $DEV_KEY;
	var $CLI_KEY;
	
	function __construct() {
		parent::__construct();
		
		//api.ai API Keys:
		$this->CLI_KEY = 'fd6bbb1f5e7b44d7a74ec1c472c598a0'; //Client key used to make query callss
		$this->DEV_KEY = 'c617539ff5ba4f01b75180621b9767d3'; //Dev key To manage @Entities & #Intents
	}
	

	/*
	 * 
	 * @Entity Functions
	 * 
	 */
	function fetch_entity($apiai_obj_id){
		$ch = curl_init('https://api.api.ai/v1/entities/'.$apiai_obj_id.'?v=20150910');
		curl_setopt_array($ch, array(
				CURLOPT_POST => FALSE,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer '.$this->DEV_KEY,
				),
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}
	
	function delete_entity($apiai_obj_id){
		$ch = curl_init('https://api.api.ai/v1/entities/'.$apiai_obj_id.'?v=20150910');
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'DELETE',
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer '.$this->DEV_KEY,
				),
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}
	function getAllEntity(){
		$ch = curl_init('https://api.api.ai/v1/entities?v=20150910');
		curl_setopt_array($ch, array(
				CURLOPT_POST => FALSE,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer '.$this->DEV_KEY,
				),
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}
	
	
	
	
	
	//This function creates a single @Entity on API.AI
	function sync_entity($pid,$setting=array()){
		
		if(intval($pid)<1){
			return array(
					'status' => 0,
					'message' => 'Invalid Pattern ID '.$pid.' input.',
			);
		}
		
		
		//Fetch INs
		$IN_links = $this->Us_model->fetch_node(intval($pid) , 'fetch_parents');
		
		
		//Is this a valid entity?
		if(count($IN_links)<1 || !($IN_links[0]['grandpa_id']==1)){
			return array(
					'status' => 0,
					'message' => 'Pattern ID '.$pid.' is not an @Entity.',
			);
		}
		
		
		// The data to send to the API:
		$apiai_entity_id = null; //If we find this, we would update instead of add.
		$published_gem_id = 0; //We to update the entity value back into our system
		$entity_name = nodeName($IN_links[0]['value']);
		$postData = array(
				'name' => $entity_name,
				'entries' => array(
						array(
								'value' => $IN_links[0]['value'],
								'synonyms' => array(), //emtpy for now
						),
						//We could have had more here... If it was not a single sync
				),
		);
		
		
		//Start our loop through:
		foreach($IN_links as $INs){
			if($INs['parent_id']==590){
				
				//BINGO! Lets see if we have some value here:
				$apiai_entity_id = ( strlen($INs['value'])>10 ? $INs['value'] : null );
				$published_gem_id = $INs['id'];
				
			} elseif($INs['parent_id']==595){
				
				//We found synonyms to enhance the @Entity:
				$postData['entries'][0]['synonyms'] = explode("\n",trim($INs['value']));
				
			}
		}
		
		
		
		
		
		
		if(!$published_gem_id && !isset($setting['force_publish'])){
			//This entity is not
			return array(
					'status' => 0,
					'message' => 'This entity is not connected to Pattern ||590.',
			);
		}
		
		if($apiai_entity_id){
			
			if(isset($setting['force_update'])){
				
				//This is an update request:
				//Try fetching this entity from API.AI:
				$remote_entity = $this->fetch_entity($apiai_entity_id);
				
				if(!isset($remote_entity['entries']) || $remote_entity['id']!==$apiai_entity_id){
					//Ooops, this was deleted from api.ai?!
					$apiai_entity_id = null;
				} else {
					//Append ID to the request:
					$postData['id'] = $apiai_entity_id;
					$name_changed = ( $remote_entity['entries'][0]['value'] == $IN_links[0]['value'] );
				}
				
			} else {
				
				//This looks lik the remote ID, and something we already have synced:
				return array(
						'status' => 1,
						'message' => 'Already Synced',
						'api_ai_blob' => array(
								'text' => $IN_links[0]['value'],
								'alias' => $entity_name,
								'meta' => '@'.$entity_name,
								'userDefined' => false,
						),
				);
			}
		}
		

		//Make the call for add/update
		$ch = curl_init('https://api.api.ai/v1/entities'.( $apiai_entity_id ? '/'.$apiai_entity_id : '' ).'?v=20150910');
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => ( $apiai_entity_id ? 'PUT' : 'POST' ),
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer '.$this->DEV_KEY,
						'Content-Type: application/json; charset=utf-8'
				),
				CURLOPT_POSTFIELDS => json_encode($postData)
		));
		
		// Send the request
		$response = curl_exec($ch);
		
		// Check for CURL errors
		if($response === FALSE){
			return array(
					'status' => 0,
					'message' => 'Curl Processing Error: '.$response,
			);
		}
		
		$res = objectToArray(json_decode($response));
		
		if($res['status']['code']==200){
			
			if(isset($setting['force_publish'])){
				
				//Lets save link:
				$new_data = array(
						'status' => 1,
						'node_id' => $pid,
						'parent_id' => 590,
						'value' => ( isset($res['id']) ? $res['id'] : $apiai_entity_id),
						'action_type' => 5, //For system updates
				);
				
				if($published_gem_id){
					$new_data['update_id'] = $published_gem_id;
				}
				
				$publish_gem = $this->Us_model->insert_link($new_data);
				
			}
			
			//200 means all-good
			return array(
					'status' => 1,
					'message' => 'Success',
					'res' => $res,
					'api_ai_blob' => array(
							'text' => $IN_links[0]['value'],
							'alias' => $entity_name,
							'meta' => '@'.$entity_name,
							'userDefined' => false,
					),
			);
			
		} elseif($res['status']['code']==409){
			
			//Already exists, lets find the ID and pass it on:
			$all_entities = $this->getAllEntity();
			
			//Attempt to find:
			foreach($all_entities as $e){
				if($e['name']==$entity_name){
					
					if(isset($setting['force_publish'])){
						
						//Lets save link:
						$new_data = array(
								'status' => 1,
								'node_id' => $pid,
								'parent_id' => 590,
								'value' => ( isset($e['id']) ? $e['id'] : $apiai_entity_id),
								'action_type' => 5, //For system updates
						);
						
						if($published_gem_id){
							$new_data['update_id'] = $published_gem_id;
						}
						
						$publish_gem = $this->Us_model->insert_link($new_data);
						
					}
					
					return array(
							'status' => 1,
							'message' => 'Duplicate entity name, but found original ID',
							'res' => array('id'=>$e['id']),
							'api_ai_blob' => array(
									'text' => $IN_links[0]['value'],
									'alias' => $entity_name,
									'meta' => '@'.$entity_name,
									'userDefined' => false,
							),
					);
				}
			}
			
			//If still here, we failed to find ID, which should never happen:
			return array(
					'status' => 0,
					'message' => 'Duplicate Entity, but failed to find ID',
					'res' => $res,
			);
			
		} else {
			
			if(isset($setting['force_publish'])){
				
				//Lets save link:
				$new_data = array(
						'status' => 0, //Requires admin to check this
						'node_id' => $pid,
						'parent_id' => 590,
						'value' => 'Error trying to sync @'.$pid.'! Here are more details from api.ai: '.print_r($res,true),
						'action_type' => 5, //For system updates
				);
				
				if($published_gem_id){
					$new_data['update_id'] = $published_gem_id;
				}
				
				$publish_gem = $this->Us_model->insert_link($new_data);
				
			}
			
			
			//Some error from api.ai:
			return array(
					'status' => 0,
					'message' => 'Status code error #'.$res['status']['code'].' from api.ai: '.$res['status']['errorDetails'],
					'res' => $res,
			);
		}
	}
	
	
	
	
	
	/*
	 *
	 * #Intent Functions
	 *
	 */
	function prep_intent($GEMs){
		
		if(!isset($GEMs[0]['id'])){
			//Invalid ID
			return false;
		}
		
		//Find User Says statements, and their associative @Entities, if any
		$intent = array(
				'name' => $GEMs[0]['value'],
				'priority' => 500000,
				'auto' => true, //ML is on
				'contexts' => array(),
				'templates' => array(), //api.ai will auto generate this field...
				'userSays' => array(
						/*
						array(
								'data' => array(
										array(
												'text' => 'Alpha3'
										),
								),
								'isTemplate' => false,
								'count' => 0,
						),
						array(
								'data' => array(
										array(
												'text' => 'Order from '
										),
										array(
												'text' => 'Lazymeal',
												'alias' => 'FoodCompanies',
												'meta' => '@FoodCompanies',
												'userDefined' => false,
										),
								),
								'isTemplate' => false,
								'count' => 0,
						),
						array(
								'data' => array(
										array(
												'text' => 'Get me food '
										),
										array(
												'text' => 'Foodora',
												'alias' => 'FoodCompanies',
												'meta' => '@FoodCompanies',
												'userDefined' => false,
										),
								),
								'isTemplate' => false,
								'count' => 0,
						),
						*/
				),
				'responses' => array(
						array(
								'resetContexts' => false,
								'action' => '',
								'affectedContexts' => array(),
								'parameters' => array(), //api.ai will auto generate this field...
								'messages' => array( //Order matters, first ones go out first...
										/*
										array(
												'type' => 0, //text
												'platform' => 'facebook', //Could be NULL
												'speech' => array(
														'Hiiiiii 222 Var 1',
														'Hiiiiii 222 Var 2',
												),
										),
										array(
												'type' => 0, //text
												'platform' => 'facebook',
												'speech' => array(
														"Text response 1",
														"Text response 2",
														"Text response 3, final variant",
														"Text response 4 :D",
												),
										),
										array(
												'type' => 3, //Image
												'platform' => 'facebook',
												'imageUrl' => 'http://www.geekfill.com/wp-content/uploads/2013/02/burning-man-beauty.jpg',
										),
										array(
												'type' => 4, //Custom payload
												'platform' => 'facebook',
												'payload' => array(
														'facebook' => array(
																'attachment' => array(
																		'type' => 'video',
																		'payload' => array(
																				'url' => 'https://s3-us-west-2.amazonaws.com/us-videos/20170509_090827.mp4',
																		),
																),
														),
												),
										),
										array(
												'type' => 2, //Quick reply
												'platform' => 'facebook',
												'title' => 'Do you love me?',
												'replies' => array(
														"Yes 😀",
														"No 💩"
												),
										),
										*/
								),
						),
				),
				
				//Default options:
				'webhookUsed' => true,
				'webhookForSlotFilling' => false,
				'fallbackIntent' => false, //This is NOT a fall-back intent
				'events' => array(), //Not used for now
		);
		
		//Now populate User Says statements and responses:
		foreach($GEMs as $key=>$gem){
			if($gem['parent_id']==672){
				
				//Conversation OUT-put Stopper:
				break;
				
			} elseif($gem['parent_id']==561 && strlen($gem['value'])>0){
				
				
				$examples = explode("\n",$gem['value']);
				
				foreach($examples as $sentence){
					
					$base = array(
							'data' => array(
									/*
									array(
											'text' => 'Hey, I\'m Hungry '
									),
									array(
											'text' => 'JustEat',
											'alias' => 'FoodCompanies',
											'meta' => '@FoodCompanies',
											'userDefined' => false,
									),
									array(
											'text' => ' Fast!'
									),
									*/
							),
							'isTemplate' => false,
							'count' => 0,
					);
					
					//Any Pattern references?
					$temp = explode('||',$sentence);
					foreach($temp as $key=>$section){
						if($key==0){
							
							//The first one has to be only text, as its before ||
							if(strlen($section)>0){
								array_push( $base['data'] , array(
										'text' => $section.' '
								));
							}
							
						} else {
							
							//Reset variables:
							unset($followup_sentence);
							$ref_pid = 0;
							
							
							//Look for PID
							if(substr_count($section,' ')>0){
								$followup_sentence = explode(' ',$section,2);
								$ref_pid = intval($followup_sentence[0]);
							} elseif(intval(substr($section,0,1))>0){
								$ref_pid = intval($section);
							}
							
							
							
							//Append reference assuming we found one:
							if( $ref_pid>0 ){
								
								//Fetch INs to construct reference:
								$INs = $this->Us_model->fetch_node($ref_pid);
								
								//Make sure its of type @Entity, otherwise not used here.
								if($INs[0]['grandpa_id']==1){
									//Yes, we're cool!
									//We need to make sure that this reference is synced with @Entities
									$sync_res = $this->sync_entity( $ref_pid , array( 'force_publish' => 1 ) );
									
									if($sync_res['status']){
										
										if(isset($sync_res['api_ai_blob'])){
											//Sync is all good, lets continue:
											array_push( $base['data'] , $sync_res['api_ai_blob'] );

										} else {
											return array(
													'status' => 0,
													'message' => 'No api_ai_blob returned.',
											);
										}
										
									} else {
										//There was some sort of error here:
										return array(
												'status' => 0,
												'message' => '@Entity Sync error: '.$sync_res['message'],
										);
									}
								}
							}
								
							//Append followup sentence, if any:
							if(isset($followup_sentence[1]) && strlen($followup_sentence[1])>0){
								array_push( $base['data'] , array(
										'text' => ' '.$followup_sentence[1].' '
								));
							}
						}
					}
					
					//Now add to main array:
					array_push($intent['userSays'],$base);
					
				}
					
			} elseif(strlen($gem['value'])>0 && (in_array($gem['node_id'],array(567,575,576,577,578)) || in_array($gem['parent_id'],array(567,575,576,577,578)))){
				
				//These are the responses we would give instantly, assuming they are before the conversation stopper:
				if(in_array(567,array($gem['parent_id'],$gem['node_id']))){
					//Text Message
					array_push( $intent['responses'][0]['messages'] , array(
							'type' => 0, //text
							'platform' => 'facebook', //Could be NULL
							'speech' => explode("\n",$gem['value']),
					));
					
				} elseif(in_array(575,array($gem['parent_id'],$gem['node_id']))){
					//Image URL
					array_push( $intent['responses'][0]['messages'] , array(
							'type' => 3, //Image
							'platform' => 'facebook',
							'imageUrl' => $gem['value'],
					));
					
				} elseif(in_array(576,array($gem['parent_id'],$gem['node_id']))){
					//Video MP4 URL
					array_push( $intent['responses'][0]['messages'] , array(
							'type' => 4, //Custom payload
							'platform' => 'facebook',
							'payload' => array(
									'facebook' => array(
											'attachment' => array(
													'type' => 'video',
													'payload' => array(
															'url' => $gem['value'],
													),
											),
									),
							),
					));
					
				} elseif(in_array(577,array($gem['parent_id'],$gem['node_id']))){
					//File URL
					array_push( $intent['responses'][0]['messages'] , array(
							'type' => 4, //Custom payload
							'platform' => 'facebook',
							'payload' => array(
									'facebook' => array(
											'attachment' => array(
													'type' => 'file',
													'payload' => array(
															'url' => $gem['value'],
													),
											),
									),
							),
					));
					
				} elseif(in_array(578,array($gem['parent_id'],$gem['node_id']))){
					//Audio File URL
					array_push( $intent['responses'][0]['messages'] , array(
							'type' => 4, //Custom payload
							'platform' => 'facebook',
							'payload' => array(
									'facebook' => array(
											'attachment' => array(
													'type' => 'audio',
													'payload' => array(
															'url' => $gem['value'],
													),
											),
									),
							),
					));
					
				}
			} elseif(strlen($gem['value'])>0 && in_array($gem['node_id'],array(567,575,576,577,578)) && in_array($gem['parent_id'],array(567,575,576,577,578))){
				
				//TODO Wire in next call to actions based on these objects:
				//This section is with Quick replies to help user jump to next topic from here
				
			}
		}
		
		return $intent;
	}
	
	
	
	
	
	function sync_intent($pid,$setting=array()){
		
		//This functions takes a pattern ID, and prepares the JSON data for api.ai
		$GEMs = $this->Us_model->fetch_full_node(intval($pid));
		$intent = $this->prep_intent($GEMs);
		
		if(!$intent){
			return array(
					'status' => 0,
					'message' => 'Invalid PID.',
			);
		}
		
		//Make sure this is valid and live, or make live:
		$published_gem_id = null; //We to update the entity value back into our system
		
		foreach($GEMs as $gem){
			if($gem['parent_id']==590){				
				$published_gem_id = $gem['id'];
				if(strlen($gem['value'])>10){
					$intent['id'] = $gem['value'];
				}
			}
		}
		
		
		if(!$published_gem_id && !isset($setting['force_publish'])){
			
			return array(
					'status' => 0,
					'message' => 'This #Intent is not associated with ||590. You can also force publish using the $setting[force_publish].',
			);
			
		}
		
		
		//Make the call for add/update
		$ch = curl_init('https://api.api.ai/v1/intents'.( isset($intent['id']) ? '/'.$intent['id'] : '' ).'?v=20150910');
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => ( isset($intent['id']) ? 'PUT' : 'POST' ),
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer '.$this->DEV_KEY, //Dev key To manage @Entities & #Intents
						'Content-Type: application/json; charset=utf-8'
				),
				CURLOPT_POSTFIELDS => json_encode($intent)
		));
		
		// Send the request
		$response = curl_exec($ch);
		
		// Check for CURL errors
		if($response === FALSE){
			return array(
					'status' => 0,
					'intent' => $intent,
					'message' => 'Curl Processing Error: '.$response,
			);
		}
		
		$res = objectToArray(json_decode($response));
		
		if($res['status']['code']==200){
			
			if(isset($setting['force_publish'])){
				
				//Now lets link the publication Gem this #intent with the api.ai ID
				$update_data = array(
						'status' => 1,
						'node_id' => $pid,
						'parent_id' => 590,
						'value' => ( isset($res['id']) ? $res['id'] : ( isset($intent['id']) ? $intent['id'] : '' )),
						'action_type' => 5, //System update
				);
				
				if($published_gem_id){
					$update_data['update_id'] = $published_gem_id;
				}
				
				$new_link = $this->Us_model->insert_link($update_data);
				
			}
			
			//200 means all-good
			return array(
					'status' => 1,
					'message' => $res['status']['errorType'], //Should say 'Success'
					'intent' => $intent,
					'res' => $res,
			);
			
		} else {
			
			if(isset($setting['force_publish'])){
				
				$update_data = array(
						'status' => 0,
						'node_id' => $pid,
						'parent_id' => 590,
						'value' => 'Error trying to sync #'.$pid.'! Here are more details from api.ai: '.print_r($res,true),
						'action_type' => 5, //System update
				);
				
				if($published_gem_id){
					$update_data['update_id'] = $published_gem_id;
				}
				
				$new_link = $this->Us_model->insert_link($update_data);
				
			}
			
			return array(
					'status' => 0,
					'message' => $res['status']['errorType'],
					'intent' => $intent,
					'res' => $res,
			);
			
		}
	}
	
	
	
	
	
	
	
	
	
	function fetch_intent($apiai_obj_id){
		$ch = curl_init('https://api.api.ai/v1/intents/'.$apiai_obj_id.'?v=20150910');
		curl_setopt_array($ch, array(
				CURLOPT_POST => FALSE,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer '.$this->DEV_KEY,
				),
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}
	
	function delete_intent($apiai_obj_id){
		$ch = curl_init('https://api.api.ai/v1/intents/'.$apiai_obj_id.'?v=20150910');
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'DELETE',
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer '.$this->DEV_KEY,
				),
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}
	
	
	
}