<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_model extends CI_Model {
	
	var $page_access_token;
	
	function __construct() {
		parent::__construct();
		//Fetch the primary bot's access token:
		$website = $this->config->item('website');
		$this->page_access_token = $website['access_token'];
	}
	
	
	function fetch_profile($user_id){
		$ch = curl_init('https://graph.facebook.com/v2.6/'.$user_id.'?access_token='.$this->page_access_token);
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_RETURNTRANSFER => TRUE,
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}
	
	
	function send_message($payload , $from_log_error=false){

		//Make the call for add/update
		$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$this->page_access_token);
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json; charset=utf-8'
				),
				CURLOPT_POSTFIELDS => json_encode($payload)
		));
		
		// Send the request
		$response = curl_exec($ch);
		
		// Check for CURL errors
		if($response === FALSE){
			log_error('CURL Failed in sending message via Messenger.',$payload);
		}
		
		$res_array = objectToArray(json_decode($response));
		
		//This prevents the function getting into an endless loop
		if(!$from_log_error){
			//Do we have any errors here?
			if(isset($res_array['error'])){
				log_error($res_array['error']['message'], array(
						'input_payload' => $payload,
						'error_content' => $res_array,
				));
			}
		}
		
		return $res_array;
	}
	
	
}