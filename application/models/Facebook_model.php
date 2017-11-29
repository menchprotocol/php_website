<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_model extends CI_Model {
		
	function __construct() {
		parent::__construct();
	}
	
	
	function fetch_profile($botkey,$user_id){
	    $mench_bots = $this->config->item('mench_bots');
	    if(!array_key_exists($botkey,$mench_bots)){
	        die('Invalid Bot Key');
	    }
	    $ch = curl_init('https://graph.facebook.com/v2.6/'.$user_id.'?access_token='.$mench_bots[$botkey]['access_token']);
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_RETURNTRANSFER => TRUE,
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}	
	
	function fetch_settings($botkey){
	    $mench_bots = $this->config->item('mench_bots');
	    if(!array_key_exists($botkey,$mench_bots)){
	        die('Invalid Bot Key');
	    }
	    $ch = curl_init('https://graph.facebook.com/v2.6/me/messenger_profile?fields=persistent_menu,get_started,greeting,whitelisted_domains&access_token='.$mench_bots[$botkey]['access_token']);
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_RETURNTRANSFER => TRUE,
		));
		// Send the request
		return objectToArray(json_decode(curl_exec($ch)));
	}
	
	function set_settings($botkey){
		
	    $mench_bots = $this->config->item('mench_bots');
	    if(!array_key_exists($botkey,$mench_bots)){
	        die('Invalid Bot Key');
	    }
		
		//Make the call for add/update
	    $ch = curl_init('https://graph.facebook.com/v2.6/me/messenger_profile?access_token='.$mench_bots[$botkey]['access_token']);
		curl_setopt_array($ch, array(
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json; charset=utf-8'
				),
		    CURLOPT_POSTFIELDS => json_encode($mench_bots[$botkey]['settings'])
		));
		
		// Send the request
		$response = curl_exec($ch);
		
		// Check for CURL errors
		if($response === FALSE){
		    $this->Db_model->e_create(array(
		        'e_message' => 'set_settings() failed to update the settings for ['.$botkey.'] on Facebook.',
		        'e_json' => json_encode(array(
		            'payload' => $mench_bots[$botkey]['settings'],
		        )),
		        'e_type_id' => 8, //Platform Error
		    ));
		}
				
		return objectToArray(json_decode($response));
	}
	
	
	//This is a fancier way to send messages that feels more human:
	function batch_messages( $botkey , $u_fb_id , $messages , $notification_type='REGULAR'){
	    
	    $mench_bots = $this->config->item('mench_bots');
	    if(!array_key_exists($botkey,$mench_bots)){
	        die('Invalid Bot Key');
	    }
	    
	    foreach($messages as $count=>$message){
	        
	        $this->Facebook_model->send_message( $botkey , array(
	            'recipient' => array(
	                'id' => $u_fb_id,
	            ),
	            'sender_action' => 'typing_on'
	        ));
	        
	        sleep(rand(0,3));
	        
	        $this->Facebook_model->send_message( $botkey , array(
	            'recipient' => array(
	                'id' => $u_fb_id,
	            ),
	            'message' => $message,
	            'notification_type' => $notification_type,
	        ));
	    }
	}
	
	
	function send_message($botkey,$payload){
	    
	    $mench_bots = $this->config->item('mench_bots');
	    if(!array_key_exists($botkey,$mench_bots)){
	        die('Invalid Bot Key');
	    }

		//Make the call for add/update
	    $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$mench_bots[$botkey]['access_token']);
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
		    $this->Db_model->e_create(array(
		        'e_message' => 'send_message() CURL Failed in sending message via ['.$botkey.'] Messenger.',
		        'e_json' => json_encode($payload),
		        'e_type_id' => 8, //Platform Error
		    ));
		}
		
		return objectToArray(json_decode($response));
	}
	
	
}