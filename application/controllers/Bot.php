<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
	}
	
	function fetch_intent($apiai_id){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->fetch_intent($apiai_id));
	}
	
	
	function apiai_webhook(){
		//This function receives incoming requests from api.ai, logs and processes them:
		$new = $this->Engagement_model->log_engagement(array(
				'us_id' => 651, //api.ai API User
				'node_type_id' => 653, //Bot API Webhook Log
				'blob' => json_encode(objectToArray($_POST)), //Dump all incoming variables
		));
		
		//Return success message to bot:
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		echo json_encode(array(
				'speech' => 'Barack Hussein Obama II is the 44th and current President of the United States.',
				'displayText' => "Barack Hussein Obama II is the 44th and current President of the United States, and the first African American to hold the office. Born in Honolulu, Hawaii, Obama is a graduate of Columbia University   and Harvard Law School, where ",
				'data' => array(
						'usBotTalk' => array()
				),
				'contextOut' => array(),
				
				'source' => "DuckDuckGo",
				
				//This makes the system ignores "speech", "displayText", and "data":
				// https://docs.api.ai/docs/concept-events#invoking-event-from-webhook
				//TODO Implement for unknown:
				'followupEvent' => array(),
		));
	}
}
