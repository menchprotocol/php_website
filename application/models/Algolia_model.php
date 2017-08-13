<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Algolia_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	
	function load_algolia($index_name='nodes'){
		require_once('application/libraries/algoliasearch.php');
		$client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
		return $client->initIndex($index_name);
	}

	
	function generate_algolia_obj($node_id,$algolia_id=0){
		//Used to create a single entity to be uploaded to Algolia
		
		//Fetch parents:
		$node = $this->Us_model->fetch_node($node_id , 'fetch_parents' , array('recursive_level'=>1) );
		
		//Grandpa Signs:
		$grandparents = $this->config->item('grand_parents');
		
		//CLeanup and prep for search indexing:
		$node_search_object = array();
		
		foreach($node as $i=>$link){
			if($i==0){
				//This is the primary link, Lets append some core info:
				$node_search_object = array(
						'node_id' => $link['node_id'],
						'grandpa_id' => $link['grandpa_id'],
						'grandpa_sign' => $grandparents[$link['grandpa_id']]['sign'],
						'parent_id' => $link['parent_id'],
						'value' => $link['value'],
						'links_blob' => '',
				);
				if($algolia_id>0){
					$node_search_object['objectID'] = $algolia_id; //This would update
				}
				
			} elseif(strlen($link['value'])>0){
				//This is a secondary link with a value attached to it
				//Lets add this to the links blob
				$node_search_object['links_blob'] .= strip_tags($link['value']).' ';
			}
		}
		
		return $node_search_object;
	}
	
	
	function sync_all(){
	
		boost_power();
		
		//Buildup this array to save to search index
		$return = array();
		
		//Fetch all nodes:
		$active_node_ids = $this->Us_model->fetch_node_ids();
		foreach($active_node_ids as $node_id){
			//Add to main array
			$alg = $this->Algolia_model->generate_algolia_obj($node_id);
			array_push( $return , $alg );
		}
		
		
		$obj = json_decode(json_encode($return), FALSE);
		
		//Include PHP library:
		$index = $this->load_algolia();
		$index->clearIndex();
		$obj_add_message = $index->addObjects($obj);
		
		//Now update database with the objectIDs:
		if(isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0){
			foreach($obj_add_message['objectIDs'] as $key=>$algolia_id){
				//Fetch top node, as that is all we need to update:
				$link = $this->Us_model->fetch_node($return[$key]['node_id'], 'fetch_top_plain');
				//Update link:
				$update_status = $this->Us_model->update_link($link['id'],array('algolia_id'=>$algolia_id));
			}
		}
		
		return 'SUCCESS: Search index updated for '.count($return).' nodes.';
	}
}