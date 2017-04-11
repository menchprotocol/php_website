<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	//Run this to completely update the "nodes" index
	function update_algolia(){
			
		//Buildup this array to save to search index
		$return = array();
		
		//Fetch all nodes:
		$active_node_ids = $this->Us_model->fetch_node_ids();
		foreach($active_node_ids as $node_id){
			//Fetch node:
			$node = $this->Us_model->fetch_node($node_id);
			//CLeanup and prep for search indexing:
			unset($node_search_object);
			foreach($node as $i=>$link){
				if($i==0){
					//This is the primary link!
					//Lets append some core info:
					$node_search_object = array(
						'node_id' => $link['node_id'],
						'last_edited' => substr($link['timestamp'],0,19),
						'grandpa_id' => $link['grandpa_id'],
						'parent_id' => $link['parent_id'],
						'value' => $link['value'],
						'links_blob' => '',
					);
				} elseif(strlen($link['value'])>0){
					//This is a secondary link with a value attached to it
					//Lets add this to the links blob
					$node_search_object['links_blob'] .= $link['value'].' ';
				}
			}
			//Add to main array
			array_push($return,$node_search_object);
		}
			
		//print_r($return);exit;
		$obj = json_decode(json_encode($return), FALSE);
		//print_r($obj);
		
		//Include PHP library:
		require_once('application/libraries/algoliasearch.php');
		$client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
		$index = $client->initIndex('nodes');
		$index->clearIndex();
		$index->addObjects($obj);
	}
	
}

