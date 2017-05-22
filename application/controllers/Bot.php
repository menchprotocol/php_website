<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
	}
	
	function apiai_webhook(){
		//This function receives incoming requests from api.ai, logs and processes them:
		$new = $this->Engagement_model->log_engagement(array(
				'us_id' => 651, //api.ai API User
				'node_type_id' => 653, //Bot API Webhook Log
				'blob' => json_encode($_POST), //Dump all incoming variables
		));
		
		//Return success message to bot:
		header('Content-Type: application/json');
		header("HTTP/1.1 ".( $new['id'] ? "200 OK" : "400" ));
		echo json_encode(array(
				'status' 	=> ( $new['id'] ? 1 : 0 ),
				'message' 	=> ( $new['id'] ? 'Webhook #'.$new['id'].' logged.' : 'Error while logging webhook.'),
		));
	}

	function test($node_id){
		print_r($this->Apiai_model->sync_entity($node_id));
	}
	
	function in($node_id){
		print_r($this->Apiai_model->fetch_intent($node_id));
	}
	

}