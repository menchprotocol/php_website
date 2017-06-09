<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Messenger_model extends CI_Model {
	
	var $page_access_token;
	
	function __construct() {
		parent::__construct();
		
		$this->page_access_token = 'EAAXa7dAxGbABANZCQVwRYyCEe5ZBHHAn2IVIcfaqmNRQHCszfSNxU4sdAmKpUiC0oqZBJgBX0aGC4DbmC8vI0Hf15RUkdh9laY9NqMYZCbtQ2S0ts8wFd89R7zhE4ZCSRcvXTXEDPSyiMWEpTc9VN9eXHrwfweBzUvyItBBpaJgZDZD';
		
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
	
	
	function send_message($payload){
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
			return false;
		}
		
		return objectToArray(json_decode($response));
	}
	
	
}