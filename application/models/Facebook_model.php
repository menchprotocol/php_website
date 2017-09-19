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
	
	
	function fetch_settings(){
		$ch = curl_init('https://graph.facebook.com/v2.6/me/messenger_profile?fields=persistent_menu,get_started,greeting,whitelisted_domains&access_token='.$this->page_access_token);
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_RETURNTRANSFER => TRUE,
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}
	
	function set_settings(){
		
		$payload = array(
				'get_started' => array(
						'payload' => 'GET_STARTED',
				),
				'greeting' => array(
						array(
								'locale' => 'default',
								'text' => 'Hi {{user_first_name}}, Welcome to Mench!',
						),
				),
				'whitelisted_domains' => array(
						'http://local.mench.co',
						'http://mench.co',
						'https://mench.co',
						'https://us.foundation',
						'http://us.foundation',
				),
				'persistent_menu' => array(
						array(
								'locale' => 'default',
								'composer_input_disabled' => false,
								'call_to_actions' => array(
										array(
												'title' => 'My Account',
												'type' => 'nested',
												'call_to_actions' => array(
														array(
																'title' => 'Progress Report',
																'type' => 'postback',
																'payload' => 'HISTORY_PAYLOAD',
														),
														array(
																'title' => 'My Profile',
																'type' => 'web_url',
																'url' => 'http://mench.co/bootcamps',
																'webview_share_button' => 'hide',
																'webview_height_ratio' => 'tall',
														),
												),
										),
										array(
												'title' => 'Marketplace',
												'type' => 'nested',
												'call_to_actions' => array(
														array(
																'title' => 'Bootcamps',
																'type' => 'web_url',
																'url' => 'http://mench.co/bootcamps',
																'webview_height_ratio' => 'tall',
														),
														array(
																'title' => 'Mentors',
																'type' => 'web_url',
																'url' => 'http://mench.co/mentors',
																'webview_height_ratio' => 'tall',
														),
														/*
														array(
																'title' => 'Another Nest',
																'type' => 'nested',
																'call_to_actions' => array(
																		array(
																				'title' => 'Pay Bill',
																				'type' => 'postback',
																				'payload' => 'PAYBILL_PAYLOAD',
																		),
																		array(
																				'title' => 'History',
																				'type' => 'postback',
																				'payload' => 'HISTORY_PAYLOAD',
																		),
																),
														),
														*/
												),
										),
										
										/*
										array(
												'title' => 'Browse',
												'type' => 'web_url',
												'url' => 'http://mench.co',
												'webview_share_button' => 'hide',
												'webview_height_ratio' => 'tall',
										),
										*/
								),
						),
				),
		);
		
		//Make the call for add/update
		$ch = curl_init('https://graph.facebook.com/v2.6/me/messenger_profile?access_token='.$this->page_access_token);
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
			log_error('Facebook set_settings() failed to update.',$payload,2);
		}
				
		return objectToArray(json_decode($response));
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
			log_error('CURL Failed in sending message via Messenger.',$payload,2);
		}
		
		return objectToArray(json_decode($response));
	}
	
	
}