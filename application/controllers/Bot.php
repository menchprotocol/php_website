<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function t(){
	    header('Content-Type: application/json');
	    echo json_encode($this->Facebook_model->set_settings());
		//print_r($this->Facebook_model->fetch_settings());
	}
	
	function fetch(){
	    header('Content-Type: application/json');
	    echo json_encode($this->Db_model->b_fb_fetch('1443101719058431'));
	}
	
	function fetch_entity($apiai_id){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->fetch_entity($apiai_id));
	}
	
	function fetch_bootcamp($apiai_id){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->fetch_bootcamp($apiai_id));
	}
	
	function prep_bootcamp($pid){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->prep_bootcamp($pid));
	}
	
	
	
	
	
	
	function facebook_webhook(){
		
		/*
		 * 
		 * Used for all webhooks from facebook, including user messaging, delivery notifications, etc...
		 * 
		 * */
		
		
		//Facebook Webhook Authentication:
		$challenge = ( isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : null );
		$verify_token = ( isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : null );
		$website = $this->config->item('website');
		
		
		if ($verify_token == '722bb4e2bac428aa697cc97a605b2c5a') {
			echo $challenge;
		}
		
		//Fetch input data:
		$json_data = json_decode(file_get_contents('php://input'), true);
		
		
		
		//This is for local testing only:
		//$json_data = objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1505007977668,"messaging":[{"sender":{"id":"1443101719058431"},"recipient":{"id":"381488558920384"},"timestamp":1505007977521,"message":{"mid":"mid.$cAAFa9hmVoehkmryMMVeaXdGIY9x5","seq":19898,"text":"Yes"}}]}]}'));
		
		//Do some basic checks:
		if(!isset($json_data['object']) || !isset($json_data['entry'])){
		    $this->Db_model->e_create(array(
		        'e_message' => 'facebook_webhook() Function missing either [object] or [entry] variable.',
		        'e_json' => json_encode($json_data),
		        'e_type_id' => 8, //Platform Error
		    ));
			return false;
		} elseif(!$json_data['object']=='page'){
		    $this->Db_model->e_create(array(
		        'e_message' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
		        'e_json' => json_encode($json_data),
		        'e_type_id' => 8, //Platform Error
		    ));
			return false;
		}
		
		
		//Loop through entries:
		foreach($json_data['entry'] as $entry){
			
			//check the page ID:
			if(!isset($entry['id']) || $entry['id']!==$website['fb_page_id']){
			    $this->Db_model->e_create(array(
			        'e_message' => 'facebook_webhook() unrecognized page id ['.$entry['id'].'].',
			        'e_json' => json_encode($json_data),
			        'e_type_id' => 8, //Platform Error
			    ));
				continue;
			} elseif(!isset($entry['messaging'])){
			    $this->Db_model->e_create(array(
			        'e_message' => 'facebook_webhook() call missing messaging Array().',
			        'e_json' => json_encode($json_data),
			        'e_type_id' => 8, //Platform Error
			    ));
				continue;
			}

			//loop though the messages:
			foreach($entry['messaging'] as $im){
				
				if(isset($im['read'])){
					
					//This callback will occur when a message a page has sent has been read by the user.
				    $this->Db_model->e_create(array(
				        'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 1, //Message Read
				    ));
					
				} elseif(isset($im['delivery'])) {
					
					//This callback will occur when a message a page has sent has been delivered.
				    $this->Db_model->e_create(array(
				        'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 2, //Message Delivered
				    ));
					
				} elseif(isset($im['referral']) || isset($im['postback'])) {
					
					if(isset($im['postback'])) {
						
						/*
						 * Postbacks occur when a the following is tapped:
						 *
						 * - Postback button
						 * - Get Started button
						 * - Persistent menu item
						 *
						 * Learn more:
						 * 
						 *
						 * */
						
						//The payload field passed is defined in the above places.
						$payload = $im['postback']['payload']; //Maybe do something with this later?
						
						if(isset($im['postback']['referral'])){
						    
							$referral_array = $im['postback']['referral'];
							
						} else {
							//Postback without referral!
							$referral_array = null;
						}
						
					} elseif(isset($im['referral'])) {
						
						$referral_array = $im['referral'];
						
					}
					
					
					$eng_data = array(
						'e_type_id' => (isset($im['referral']) ? 4 : 3), //Messenger Referral/Postback
						'e_json' => json_encode($json_data),
					);
					
					
					if($referral_array && isset($referral_array['ref']) && strlen($referral_array['ref'])>0){
						
						//We have referrer data, see what this is all about!
						//We expect an integer which is the challenge ID
						$ref_source = $referral_array['source'];
						$ref_type = $referral_array['type'];
						$ad_id = ( isset($referral_array['ad_id']) ? $referral_array['ad_id'] : null ); //Only IF user comes from the Ad
						//Referral key which currently equals the User ID
						$eng_data['e_object_id'] = intval($referral_array['ref']); 
						

						if($eng_data['e_object_id']>0){
						    
						    //Link this account to their existing account:
						    $matching_users = $this->Db_model->u_fetch(array(
						        'u_id' => $eng_data['e_object_id'],
						    ));
						    
						    //Did we find a single user without a linked Facebook account?
						    if(count($matching_users)==1 && strlen($matching_users[0]['u_fb_id'])<5){
						        
						        //Link their profile:
						        $this->Db_model->u_update( $matching_users[0]['u_id'] , array(
						            'u_fb_id' => $im['sender']['id'],
						        ));
						        
						        //Search for possible Bootcamps:
						        $admissions = $this->Db_model->remix_admissions(array(
						            'ru.ru_u_id'	=> $matching_users[0]['u_id'],
						        ));
						        
						        //Log Engagement:
						        $this->Db_model->e_create(array(
						            'e_creator_id' => $matching_users[0]['u_id'],
						            'e_json' => json_encode($json_data),
						            'e_type_id' => 31, //Messenger Activated
						            'e_object_id' => $matching_users[0]['u_id'],
						            'e_b_id' => (count($admissions)==1 ? $admissions[0]['bootcamp']['b_id'] : 0),
						        ));
						        
						        
						        //Communicate the linking process with them ASAP:
						        $this->Facebook_model->send_message(array(
						            'recipient' => array(
						                'id' => $im['sender']['id']
						            ),
						            'sender_action' => 'typing_on'
						        ));
						        $this->Facebook_model->send_message(array(
						            'recipient' => array(
						                'id' => $im['sender']['id'],
						            ),
						            'message' => array(
						                'text' => 'I was able to successfully link Messenger to your Mench account 👍. I noticed you are our '.echo_ordinal($matching_users[0]['u_id']).' member, so welcome onboard! I also noticed you\'re involved with '.count($admissions).' bootcamp'.( count($admissions)==1 ? ': '.$admissions[0]['bootcamp']['c_objective'] : 's' ).'.',
						            ),
						            'notification_type' => 'NO_PUSH' //Can be REGULAR, SILENT_PUSH or NO_PUSH
						        ));
						        $this->Facebook_model->send_message(array(
						            'recipient' => array(
						                'id' => $im['sender']['id'],
						            ),
						            'sender_action' => 'typing_on'
						        ));
						        $this->Facebook_model->send_message(array(
						            'recipient' => array(
						                'id' => $im['sender']['id'],
						            ),
						            'message' => array(
						                'text' => 'I have no more updates for now. Is there anything you would like to know about us? 🤔',
						            ),
						            'notification_type' => 'NO_PUSH' //Can be REGULAR, SILENT_PUSH or NO_PUSH
						        ));
						    }
						}
    						
						
						//Optional actions that may need to be taken on SOURCE:
						if(strtoupper($ref_source)=='ADS' && $ad_id){
							//Ad clicks
							
						} elseif(strtoupper($ref_source)=='SHORTLINK'){
							//Came from m.me short link click
							
						} elseif(strtoupper($ref_source)=='MESSENGER_CODE'){
							//Came from m.me short link click
							
						} elseif(strtoupper($ref_source)=='DISCOVER_TAB'){
							//Came from m.me short link click
							
						}
					}
					
					//Update the user ID now as we might have linked them:
					$eng_data['e_creator_id'] = $this->Db_model->u_fb_search($im['sender']['id']);
					
					//General variables:
					$this->Db_model->e_create($eng_data);
					
					
				} elseif(isset($im['optin'])) {
					
					//TODO Validate the ref ID and log error if not valid.
					//Decode ref variable intval($im['optin']['ref'])
					
					//Log engagement:
				    $this->Db_model->e_create(array(
				        'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 5, //Message Delivered
				    ));
					
				} elseif(isset($im['message'])) {
					
					/*
					 * Triggered for both incoming and outgoing messages on behalf of our team
					 * 
					 * */
					
					//Set variables:
					$sent_from_us = ( isset($im['message']['is_echo']) ); //Indicates the message sent from the page itself
					$user_id = ( $sent_from_us ? $im['recipient']['id'] : $im['sender']['id'] );
					$page_id = ( $sent_from_us ? $im['sender']['id'] : $im['recipient']['id'] );
					
					$eng_data = array(
						'e_creator_id' => ( $sent_from_us ? 0 : $this->Db_model->u_fb_search($im['sender']['id'])),
						'e_json' => json_encode($json_data),
						'e_message' => ( isset($im['message']['text']) ? $im['message']['text'] : '' ),
					    'e_type_id' => ( $sent_from_us ? 7 : 6 ), //Message Sent/Received
					);
					
					//Some that are not used yet:
					$is_mench = 0; //TODO
					$metadata = ( isset($im['message']['metadata']) ? $im['message']['metadata'] : null ); //Send API custom string [metadata field]
					
					if($metadata=='SKIP_ECHO_LOGGING'){
						//We've been asked to skip this error logging!
						continue;
					}
					
					//Do some checks:
					if(!isset($im['message']['mid'])){
					    $this->Db_model->e_create(array(
					        'e_message' => 'facebook_webhook() Received message without Facebook Message ID.',
					        'e_json' => json_encode($json_data),
					        'e_type_id' => 8, //Platform Error
					    ));
					}
					
					//It may also have an attachment
					//https://developers.facebook.com/docs/messenger-platform/webhook-reference/message
					//
					$new_file_url = null; //Would be updated IF message is a file
					if(isset($im['message']['attachments'])){
						//We have some attachments, lets loops through them:
						foreach($im['message']['attachments'] as $att){
							
							if(in_array($att['type'],array('image','audio','video','file'))){
								
								//Store to local DB:
								$new_file_url = save_file($att['payload']['url'],$json_data);
								
								//Message with image attachment
								$eng_data['e_message'] .= (strlen($eng_data['e_message'])>0?"\n\n":'').'/attach '.$att['type'].':'.$new_file_url;
								
								/*
								//Reply:
								$this->Facebook_model->send_message(array(
										'recipient' => array(
												'id' => $user_id
										),
										'sender_action' => 'typing_on'
								));
								
								//Testing for now:
								$this->Facebook_model->send_message(array(
										'recipient' => array(
												'id' => $user_id
										),
										'message' => array(
												'text' => 'Got your messageand will get back to you soon!',
										),
										'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
								));
								*/
								
							} elseif($att['type']=='location'){
								
								//Message with location attachment
								//TODO test to make sure this works!
								$loc_lat = $att['payload']['coordinates']['lat'];
								$loc_long = $att['payload']['coordinates']['long'];
								$eng_data['e_message'] .= (strlen($eng_data['e_message'])>0?"\n\n":'').'/attach location:'.$loc_lat.','.$loc_long;
								
							} elseif($att['type']=='template'){
								
								//Message with template attachment, like a button or something...
								$template_type = $att['payload']['template_type'];
								
							} elseif($att['type']=='fallback'){
								
								//A fallback attachment is any attachment not currently recognized or supported by the Message Echo feature.
								//We can ignore them for now :)
								
							} else {
								//This should really not happen!
							    $this->Db_model->e_create(array(
							        'e_message' => 'facebook_webhook() Received message with unknown attachment type ['.$att['type'].'].',
							        'e_json' => json_encode($json_data),
							        'e_type_id' => 8, //Platform Error
							    ));
							}
						}
					}
					
					
					//Log incoming engagement:
					$this->Db_model->e_create($eng_data);
					
					
					//Should we start talking?!
					/*
					if(0 && !$sent_from_us && !isset($im['message']['attachments']) && strlen($eng_data['e_message'])>0){
						
						//TODO disabled for now, build later
						//Incoming text message, attempt to auto detect it:
						//$eng_data['gem_id'] = ''; //If intent was found, the update ID that was served
						
						//Indicate to the user that we're typing:
						
						
						if(isset($unsubscribed_gem['id'])){
							//Oho! This user is unsubscribed, Ask them if they would like to re-join us:
							$response = array(
								'text' => 'You had unsubscribed from Us. Would you like to re-join?',
							);
						} else {
							//TODO Now figure out the response
						}
						 
						//Send message back to user:
						$this->Facebook_model->send_message(array(
								'recipient' => array(
										'id' => $user_id
								),
								'message' => $response,
								'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
						));
						
						
						//TODO Log outgoing message Engagement
					}
					*/
					
				} else {
				    //This should really not happen!
				    $this->Db_model->e_create(array(
				        'e_message' => 'facebook_webhook() received unrecognized webhook call.',
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 8, //Platform Error
				    ));
				}
			}
		}
	}
	
	
	
	function paypal_webhook(){
	    //Called when the paypal payment is complete:
	    if(isset($_POST) && isset($_POST['payment_status']) && $_POST['payment_status']=='Completed' && isset($_POST['item_number']) && intval($_POST['item_number'])>0){
	        //Seems like a valid Paypal IPN Call:
	        //Fetch Enrollment row:
	        $enrollments = $this->Db_model->ru_fetch(array(
	            'ru.ru_id' => intval($_POST['item_number']),
	        ));
	        
	        if(count($enrollments)==1){
	            //Fetch cohort data to grab bootcamp ID:
	            $cohorts = $this->Db_model->r_fetch(array(
	                'r.r_id' => $enrollments[0]['ru_r_id'],
	            ));
	            
	            if(count($cohorts)==1){
	                
	                //Define numbers:
	                $amount = floatval(( $_POST['payment_gross']>$_POST['mc_gross'] ? $_POST['payment_gross'] : $_POST['mc_gross'] ));
	                $fee = floatval(( $_POST['payment_fee']>$_POST['mc_fee'] ? $_POST['payment_fee'] : $_POST['mc_fee'] ));
	                
	                //Insert transaction:
	                $transaction = $this->Db_model->t_create(array(
	                    't_ru_id' => $enrollments[0]['ru_id'],
	                    't_timestamp' => date("Y-m-d H:i:s"),
	                    't_creator_id' => $enrollments[0]['ru_u_id'],
	                    't_paypal_id' => $_POST['txn_id'],
	                    't_paypal_ipn' => json_encode($_POST),
	                    't_currency' => $_POST['mc_currency'],
	                    't_payment_type' => $_POST['payment_type'],
	                    't_paypal_verified' => ( $_POST['payer_status']=='verified' ? 't' : 'f' ),
	                    't_total' => $amount,
	                    't_fees' => $fee,
	                ));
	                
	                //Update student's payment status:
	                $this->Db_model->ru_update( $enrollments[0]['ru_id'] , array(
	                    'ru_status' => 2, //For now this is the default since we don't accept partial payments
	                    'ru_paid_sofar' => ($enrollments[0]['ru_paid_sofar'] + $amount), //This supports partial payments though!
	                ));
	                
	                //Log Engagement
	                $this->Db_model->e_create(array(
	                    'e_creator_id' => $enrollments[0]['ru_u_id'],
	                    'e_message' => 'Received $'.$amount.' USD via PayPal.',
	                    'e_json' => json_encode($_POST),
	                    'e_type_id' => 30, //Paypal Payment
	                    'e_object_id' => $transaction['t_id'],
	                    'e_b_id' => $cohorts[0]['r_b_id'],
	                ));
	            }
	        }
	    }
	}
	
	function typeform_webhook(){
	    
	    //This function is called when a user submits a typeform to save its data	    
	    $json_data = json_decode(file_get_contents('php://input'), true);
	    $application_status_salt = $this->config->item('application_status_salt');
	    
	    if(!isset($json_data['form_response']['hidden']['u_id']) || !isset($json_data['form_response']['hidden']['u_key']) || !(md5($json_data['form_response']['hidden']['u_id'].$application_status_salt)==$json_data['form_response']['hidden']['u_key'])){
	        //Log this error:
	        $this->Db_model->e_create(array(
	            'e_creator_id' => 0,
	            'e_message' => 'BOT/typeform_webhook() received application with invalid user credentials.',
	            'e_json' => json_encode($json_data),
	            'e_type_id' => 8, //Platform Error
	        ));
	        exit;
	    }
	    
	    
	    //Search for cohort using form ID:
	    $users = $this->Db_model->u_fetch(array(
	        'u_id' => $json_data['form_response']['hidden']['u_id'],
	    ));
	    $udata = @$users[0];
	    $cohorts = $this->Db_model->r_fetch(array(
	        'r.r_typeform_id' => $json_data['form_response']['form_id'],
	        'r.r_status' => 1,
	    ));
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $cohorts[0]['r_b_id'],
	    ));
	    $bootcamp = @$bootcamps[0];
	    $next_cohort = filter_next_cohort($bootcamp['c__cohorts']);
	    $enrollments = $this->Db_model->ru_fetch(array(
	        'ru.ru_r_id'	=> $next_cohort['r_id'],
	        'ru.ru_u_id'	=> $udata['u_id'],
	    ));
	    
	    if(!isset($udata['u_id']) || !isset($bootcamp['b_id']) || !isset($cohorts[0]['r_id']) || !isset($enrollments[0]['ru_id']) || !($next_cohort['r_id']==$cohorts[0]['r_id'])){
	        //Log this error:
	        $this->Db_model->e_create(array(
	            'e_creator_id' => $udata['u_id'],
	            'e_message' => 'BOT/typeform_webhook() received application with missing information.',
	            'e_json' => json_encode($json_data),
	            'e_type_id' => 8, //Platform Error
	        ));
	        exit;
	    }
	    
	    //Update submission:
	    $this->Db_model->ru_update($enrollments[0]['ru_id'],array(
	        'ru_application_survey' => json_encode($json_data),
	    ));
	    
	    //Log Engagement:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_json' => json_encode($json_data),
	        'e_type_id' => 26, //Typeform Application submitted
	        'e_object_id' => $next_cohort['r_id'],
	        'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
	    ));
	    
	    
	}
	
	
	function apiai_webhook(){
		
		//This is being retired in favour of the new design to intake directly from Facebook 
		exit;
		//The main function to receive user message.
		//Facebook Messenger send the data to api.ai, they attempt to detect #intents/@entities.
		//And then they send the results to Us here.
		//Data from api.ai
		
		$json_data = json_decode(file_get_contents('php://input'), true);
		
		//See what we should respond to the user:
		$eng_data = array(
				'gem_id' => 0,
				'us_id' => 0, //Default api.ai API User, IF not with facebok
				'intent_pid' => ( substr_count($json_data['result']['action'],'pid')==1 ? intval(str_replace('pid','',$json_data['result']['action'])) : 0 ),
				'json_blob' => json_encode($json_data), //Dump all incoming variables
				'message' => $json_data['result']['resolvedQuery'],
				'seq' => 0, //No sequence if from api.ai
				'correlation' => ( isset($json_data['result']['score']) ? $json_data['result']['score'] : 1 ),
				'action_pid' => 928, //928 Read, 929 Write, 930 Subscribe, 931 Unsubscribe
		);
		
		//Is this message coming from Facebook? (Instead of api.ai console)
		if(isset($json_data['originalRequest']['source']) 
		&& $json_data['originalRequest']['source']=='facebook'){
			
			//This is from Facebook Messenger
			$fb_user_id = $json_data['originalRequest']['data']['sender']['id'];
			
			//Update engagement variables:
			$eng_data['seq'] 			= $json_data['originalRequest']['data']['message']['seq']; //Facebook message sequence
			$eng_data['message'] 		= $json_data['originalRequest']['data']['message']['text']; //Facebook message content
			
			
			if(strlen($fb_user_id)>0){
				
				//Indicate to the user that we're typing:
				$this->Facebook_model->send_message(array(
						'recipient' => array(
								'id' => $fb_user_id
						),
						'sender_action' => 'typing_on'
				));
				
				//We have a sender ID, see if this is registered using Facebook PSID
				$matching_users = $this->Us_model->search_node($fb_user_id,1024);
				
				if(count($matching_users)>0){
					
					//Yes, we found them!
					$eng_data['us_id'] = $matching_users[0]['node_id'];
					
					//TODO Check to see if this user is unsubscribed:
					//$unsubscribed_gem = $this->Us_model->fetch_sandwich_node($eng_data['us_id'],845);
					
					
				} else {
					//This is a new user that needs to be registered!
				    $eng_data['e_creator_id'] = $this->Db_model->u_fb_create($fb_user_id);
					
					if(!$eng_data['us_id']){
						//There was an error fetching the user profile from Facebook:
						$eng_data['us_id'] = 765; //Use FB messenger
						//TODO Log error and look into this
					}
				}
				
				
				//Log incoming engagement
				
				//Fancy:
				//sleep(1);
				
				if(isset($unsubscribed_gem['id'])){
					//Oho! This user is unsubscribed, Ask them if they would like to re-join us:
					$response = array(
							'text' => 'You had unsubscribed from Us. Would you like to re-join?',
					);
				} else {
					//Now figure out the response:
					$response = $this->Us_model->generate_response($eng_data['intent_pid'],$setting);
				}
				
				//TODO: Log response engagement
				
				//Send message back to user:
				$this->Facebook_model->send_message(array(
						'recipient' => array(
								'id' => $fb_user_id
						),
						'message' => $response,
						'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
				));
			}
			
		} else {
			//TODO Log engagement
			
			//most likely this is the api.ai console.
			header('Content-Type: application/json');
			$chosen_reply = 'Testing intents on api.ai, huh? Currently we programmed to only respond in Facebook messanger directly!';
			echo json_encode(array(
					'speech' => $chosen_reply,
					'displayText' => $chosen_reply,
					'data' => array(), //Its only a text response
					'contextOut' => array(),
					'source' => "webhook",
			));
		}
	}
}
