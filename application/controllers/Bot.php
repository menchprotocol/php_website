<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	
	var $facebook_page_id;
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		//Assign global variables:
		$this->facebook_page_id = 1782774501750818;
		
	}
	
	function test_response($pid){
		json_response($pid);
	}
	
	function fetch_entity($apiai_id){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->fetch_entity($apiai_id));
	}
	
	function fetch_intent($apiai_id){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->fetch_intent($apiai_id));
	}
	
	function prep_intent($pid){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->prep_intent($pid));
	}	
	
	
	function apiai_webhook(){
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
				'external_id' => $json_data['id'], //api.ai message id
				'message' => $json_data['result']['resolvedQuery'],
				'seq' => 0, //No sequence if from api.ai
				'correlation' => ( isset($json_data['result']['score']) ? $json_data['result']['score'] : 1 ),
				'platform_pid' => 763, //766 Us, 765 FB, 763 api.ai //We assume its from api.ai console
				'is_inbound' => true, //Either true or false
				'session_id' => $json_data['sessionId'], //Always from api.ai
		);
		
		if(isset($json_data['originalRequest']['source']) 
		&& $json_data['originalRequest']['source']=='facebook'
		&& $json_data['originalRequest']['data']['recipient']['id']==$this->facebook_page_id){
			
			//This is from Facebook Messenger
			$fb_user_id = intval($json_data['originalRequest']['data']['sender']['id']);
			
			$eng_data['platform_pid'] = 765; //It is from Facebook!
			$eng_data['external_id'] = $json_data['originalRequest']['data']['message']['mid']; //Facebook message id
			$eng_data['seq'] = $json_data['originalRequest']['data']['message']['seq']; //Fascebook message sequence
			$eng_data['message'] = $json_data['originalRequest']['data']['message']['text']; //Fascebook message content
			
			
			if($fb_user_id>0){
				//We have a sender ID, see if this is registered on Us:
				$matching_users = $this->Us_model->search_node($fb_user_id,765); //Facebook Messenger
				
				if(count($matching_users)>0){
					
					//Yes, we found them!
					$eng_data['us_id'] = $matching_users[0]['node_id'];
					
					//TODO Check to see if this user is unsubscribed:
					//$unsubscribed_gem = $this->Us_model->fetch_sandwich_node($eng_data['us_id'],845);
					
				} else {
					//TODO This is a new user that needs to be registered
					
					//Call facebook messenger API and get user details
				}
			}
		}
			
		
		//Log engagement:
		$new = $this->Us_model->log_engagement($eng_data);
		
		
		//Respond:
		if(isset($unsubscribed_gem['id'])){
			
			//Oho! This user is unsubscribed, Ask them if they would like to re-join us:
			json_response(846,array('skip_history'=>1));
			
		} else {
			
			//This must either be a specific pattern, or the default fallback:
			json_response($eng_data['intent_pid']);
			
		}
	}
}
