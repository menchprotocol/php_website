<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	
	var $facebook_page_id;
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		//Assign global variables:
		$this->facebook_page_id = '1782774501750818';
		
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
		
		$json_data = objectToArray(json_decode(file_get_contents('php://input'), true));
		
		//$json_data = objectToArray(json_decode('{"originalRequest":{"source":"facebook","data":{"sender":{"id":"1344093838979504"},"recipient":{"id":"1782774501750818"},"message":{"mid":"mid.$cAAZVbKt7ywpiu4rqGlcikWlWLdAX","text":"hi","seq":14953},"timestamp":1496968438298}},"id":"283f4928-2a2f-4e66-84f5-1a8219a85881","timestamp":"2017-06-09T00:33:58.628Z","lang":"en","result":{"source":"agent","resolvedQuery":"hi","speech":"","action":"pid614","actionIncomplete":false,"parameters":[],"contexts":[{"name":"generic","parameters":{"facebook_sender_id":"1344093838979504"},"lifespan":0}],"metadata":{"intentId":"c380b273-08b5-48f5-b01b-c3ba677a6122","webhookUsed":"true","webhookForSlotFillingUsed":"false","intentName":"Introduce Us"},"fulfillment":{"speech":"holllla!","messages":[{"type":0,"platform":"facebook","speech":"holllla!"},{"type":0,"speech":"holllla!"}]},"score":1},"status":{"code":200,"errorType":"success"},"sessionId":"5b7abe9f-6037-4708-9fd6-5e5dce8cc4e8"}'));

		
		//See what we should respond to the user:
		$eng_data = array(
				'gem_id' => 0,
				'us_id' => 0, //Default api.ai API User, IF not with facebok
				'intent_pid' => ( substr_count($json_data['result']['action'],'pid')==1 ? intval(str_replace('pid','',$json_data['result']['action'])) : 0 ),
				'json_blob' => json_encode($json_data), //Dump all incoming variables
				'external_id' => $json_data['id'], //api.ai id
				'message' => $json_data['result']['resolvedQuery'],
				'seq' => 0, //No sequence if from api.ai
				'correlation' => ( isset($json_data['result']['score']) ? $json_data['result']['score'] : 1 ),
				'platform_pid' => 763, //766 Us, 765 FB, 763 api.ai //We assume its from api.ai console
				'is_inbound' => true, //Either true or false
				'session_id' => $json_data['sessionId'], //Always from api.ai
		);
		
		//Is this message coming from Facebook? (Instead of api.ai console)
		if(isset($json_data['originalRequest']['source']) 
		&& $json_data['originalRequest']['source']=='facebook'
		&& $json_data['originalRequest']['data']['recipient']['id']==$this->facebook_page_id){
			
			//This is from Facebook Messenger
			$fb_user_id = $json_data['originalRequest']['data']['sender']['id'];
			
			//Update engagement variables:
			$eng_data['platform_pid'] 	= 765; //It is from Facebook!
			$eng_data['external_id'] 	= $json_data['originalRequest']['data']['message']['mid']; //Facebook message id
			$eng_data['seq'] 			= $json_data['originalRequest']['data']['message']['seq']; //Facebook message sequence
			$eng_data['message'] 		= $json_data['originalRequest']['data']['message']['text']; //Facebook message content
			
			/*
			if(strlen($fb_user_id)>0){
				
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
			*/
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
