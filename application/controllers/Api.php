<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		if(!auth_admin(1)){
			//Check for session for all functions here!
			die('<div><span class="danger"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Invalid session! Login and try again...</span></div>');
		}
	}
	
	
	function delete($link_id,$type){
		
	}
	
	function update_sort(){
		if(intval($_REQUEST['node_id'])<1 || !is_array($_REQUEST['new_sort'])){
			//We start with a DIV to prevent errors from being hidden after a few seconds:
			echo '<div><span class="danger"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Invalid Input. Contact admin.</span></div>';
			//TODO: This should not happen! Implement admin-trigger
			return false;
		}
		
		//Awesome Sauce! lets move-on...
		//Fetch child links to update:
		$user_data = $this->session->userdata('user');
		$timestamp = date("Y-m-d H:i:s"); //Make sure all action in this batch have the same time
		$child_links = $this->Us_model->fetch_node(intval($_REQUEST['node_id']), 'fetch_children');
		
		//Inverse key
		$new_sort_index = array();
		foreach($_REQUEST['new_sort'] as $key=>$value){
			$new_sort_index[$value] = $key+1;
		}
		
		
		$success = 0;
		foreach($child_links as $link){
			
			if(!array_key_exists($link['node_id'],$new_sort_index)){
				//Ooops, some unknown error:
				echo '<div><span class="danger"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Unknown sort index.</span></div>';
				//TODO: This should not happen! Implement admin-trigger
				return false;
			}
			
			
			//Valid, insert new row:
			$update_id = $this->Us_model->insert_link(array(
				'us_id' => $user_data['node_id'],
				'timestamp' => $timestamp,
				'status' => $link['status'],
				'node_id' => $link['node_id'],
				'grandpa_id' => $link['grandpa_id'],
				'parent_id' => $link['parent_id'],
				'value' => $link['value'],
				'update_id' => $link['id'],
				'ui_rank' => $new_sort_index[$link['node_id']],
				'correlation' => $link['correlation'],
				'action_type' => 3, //For sorting
			));
			
			if(!$update_id){
				//Ooops, some unknown error:
				echo '<div><span class="danger"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Unknown Error. Contact admin.</span></div>';
				//TODO: This should not happen! Implement admin-trigger
				return false;
			}
			
			//Then remove old one:
			$affected_rows = $this->Us_model->update_link($link['id'], array(
				'update_id' => $update_id,
				'status' => -1, //-1 is for updated items.
			));
			
			if(!$affected_rows){
				//Ooops, some unknown error:
				echo '<div><span class="danger"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Unknown Error. Contact admin.</span></div>';
				//TODO: This should not happen! Implement admin-trigger
				return false;
			} else {
				//All good!
				$success++;
			}
		}
		
		echo '<span class="success"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Saved!</span>';
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

