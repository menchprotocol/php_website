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
	
	
	
	
	/*
	 *
	 * #Intent Functions
	 *
	 */
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
	
	function sync_entity($node_id){
		
		if(intval($node_id)<1){
			return array(
					'status' => 0,
					'message' => 'Invalid node inpit Id.',
			);
		}
		
		
		//This function creates a single entity on API.AI for $node_id
		$IN_links = $this->Us_model->fetch_node($node_id, 'fetch_parents');
		
		
		if(count($IN_links)<1){
			return array(
					'status' => 0,
					'message' => 'Invalid URL Id.',
			);
		}
		
		//Synonyms are required for entities:
		$synonyms = array();
		$apiai_entity_id = null; //If we find this, we would update instead of add.
		$update_gem_id = 0; //We to update the entity value back into our system
		
		foreach($IN_links as $INs){
			if($INs['parent_id']==595){
				//We found synonyms to enhance the Entity:
				$synonyms = explode("\n",trim($INs['value']));
			} elseif($INs['parent_id']==590){
				//BINGO! Lets see if we have some value here:
				$apiai_entity_id = ( strlen($INs['value'])>0 ? $INs['value'] : null );
				$update_gem_id = $INs['id'];
			}
		}
		
		if(!$update_gem_id){
			//This should never happen, as this function would be called only with an association to /590 is detected
			return array(
					'status' => 0,
					'message' => 'This entity is not linked to !SyncSingleEntity.',
			);
		}
		
		// The data to send to the API
		$ent_name = nodeName($IN_links[0]['value']);
		$postData = array(
				'name' => $ent_name,
				'entries' => array(
						array(
								'value' => $IN_links[0]['value'],
								'synonyms' => $synonyms
						),
						//We could have had more here... If it was not a single sync
				),
		);
		
		
		if($apiai_entity_id){
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
				$curl_url = 'https://api.api.ai/v1/entities/'.$apiai_entity_id.'?v=20150910';
			}
		}
		
		//We might have pivoted here:
		if(!$apiai_entity_id){
			//This is a new entity:
			$curl_url = 'https://api.api.ai/v1/entities?v=20150910';
		}
		
		
		//Make the call for add/update
		$ch = curl_init($curl_url);
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => ( $apiai_entity_id ? 'PUT' : 'POST' ),
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer c617539ff5ba4f01b75180621b9767d3', //Dev key To manage @Entities & #Intents
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
			//200 means all-good
			return array(
					'status' => 1,
					'message' => 'Success',
					'res' => $res,
			);
		} elseif($res['status']['code']==409){
			//Already exists, lets find the ID and pass it on:
			$all_entities = $this->getAllEntity();

			//Attempt to find:
			foreach($all_entities as $e){
				if($e['name']==$ent_name){
					return array(
							'status' => 1,
							'message' => 'Duplicate entity name, but found original ID',
							'res' => array('id'=>$e['id']),
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
			//Some error from api.ai:
			return array(
					'status' => 0,
					'message' => 'Status code error #'.$res['status']['code'].' from api.ai: '.$res['status']['errorDetails'],
					'res' => $res,
			);
		}
	}
}