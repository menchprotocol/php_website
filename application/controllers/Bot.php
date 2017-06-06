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
		$us_id = 763; //Default api.ai API User, IF not with facebok
		if(isset($json_data['originalRequest']['source']) 
				&& $json_data['originalRequest']['source']=='facebook'
				&& $json_data['originalRequest']['source']['recipient']['id']==$this->facebook_page_id){
					//This is from Messenger, which we currently support!
					// $json_data['originalRequest']['source']['sender']['id']
					//$us_id = ''; //TODO: Find/Register user and assign ID
		}
		
		//Log engagement with api.ai Bot:
		$new = $this->Engagement_model->log_engagement(array(
				'entity_pid' => $us_id,
				'intent_pid' => 653, //Bot API Webhook Log
				'json_blob' => $json_data['result']['action'].' <<<>>> '.json_encode($json_data), //Dump all incoming variables
				'external_id' => $json_data['result']['action'].' <<<>>> '.json_encode($json_data), //Dump all incoming variables
		));
		
		//Log engagement with Facebook Bot/Page:
		
		
		//Log engagement with Foundation Web App:
		
		
		//Return success message to bot:
		header('Content-Type: application/json');
		echo json_encode(array(
				'speech' => 'Barack Hussein Obama II is the 44th and current President of the United States. '.time(),
				'displayText' => "Barack Hussein Obama II Harvard Law School, where.",
				'data' => array(
						'google' => array(
								'expect_user_response' => false,
								'final_response' => array(
										'speech_response' => array(
												'text_to_speech' => 'Hiiiiiiiiiiiiiii',
										),
								),
						),
				),
				'contextOut' => array(),
				'source' => "webhook",
				//This makes the system ignores "speech", "displayText", and "data":
				// https://docs.api.ai/docs/concept-events#invoking-event-from-webhook
				//TODO Implement for unknown:
				//'followupEvent' => array(),
		));
	}
}
