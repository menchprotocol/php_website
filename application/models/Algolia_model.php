<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Algolia_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	
	function load_algolia($index_name='challenges'){
		require_once('application/libraries/algoliasearch.php');
		$client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
		return $client->initIndex($index_name);
	}

	
	
	function generate_c_algolia($c_id){
		//Used to create a single entity to be uploaded to Algolia
		$challenge = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_status >=' => 0,
		));
		
		//Adjust Algolia ID:
		if(intval($challenge['c_algolia_id'])>0){
			$challenge['objectID'] = intval($challenge['c_algolia_id']);
		}
		unset($challenge['c_algolia_id']);
		
		//Return:
		return $challenge;
	}
	
	
	function sync_all(){
	
		boost_power();
		
		//Fetch all nodes:
		$challenges = $this->Db_model->c_fetch(array(
				'c.c_status >=' => 0,
		));
		
		//Buildup this array to save to search index
		$return = array();
		foreach($challenges as $challenge){
			//Add to main array
			$alg = $this->generate_c_algolia($challenge['c_id']);
			array_push( $return , $alg );
		}
		
		$obj = json_decode(json_encode($return), FALSE);
		
		//Include PHP library:
		$index = $this->load_algolia();
		$index->clearIndex();
		$obj_add_message = $index->addObjects($obj);
		
		//Now update database with the objectIDs:
		if(isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0){
			print_r($obj_add_message['objectIDs']);
			foreach($obj_add_message['objectIDs'] as $key=>$algolia_id){
				
			}
		}
		
		return 'SUCCESS: Search index updated for '.count($return).' nodes.';
	}
}