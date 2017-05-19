<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Apiai_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	
	
	
	
	
	
	function generateSynonyms(){
		
	}
	
	
	
	function addEntity(){
		//This function creates an entity on API.AI with $group_node_id
		// as the Entity name, and the OUT entities of $group_node_id as the entity values.
		
		// The data to send to the API
		$postData = array(
				'name' => 'Appliances',
				'entries' => array(
						array(
								'value' => 'Coffee Maker',
								'synonyms' => array("coffee maker", "coffee machine",  "coffee")
						),
						array(
								'value' => 'Dish Washer',
								'synonyms' => array("dishes")
						),
						array(
								'value' => 'Couch',
								'synonyms' => array("sofa")
						),
				),
		);
		
		// Setup cURL
		$ch = curl_init('https://api.api.ai/v1/entities?v=20150910');
		curl_setopt_array($ch, array(
				CURLOPT_POST => TRUE,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_HTTPHEADER => array(
						'Authorization: Bearer c617539ff5ba4f01b75180621b9767d3', //To manage @Entities & #Intents
						'Content-Type: application/json; charset=utf-8'
				),
				CURLOPT_POSTFIELDS => json_encode($postData)
		));
		
		// Send the request
		$response = curl_exec($ch);
		
		// Check for errors
		if($response === FALSE){
			die(curl_error($ch));
		}
		
		// Decode the response
		echo $response;
	}
	
}