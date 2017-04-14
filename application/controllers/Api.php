<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		if(!auth_admin(1)){
			//Check for session for all functions here!
			echo_html(0,'Invalid session! Login and try again...');
			exit;
		}
	}
	
	
	function delete($link_id,$type){
		
	}
	
	function update_link(){
		if(intval($_REQUEST['id'])<1){
			return echo_html(0,'Invalid link ID.');
		} elseif(!intval($_REQUEST['key']) && strlen($_REQUEST['new_value'])<1){
			return echo_html(0,'You must enter a value for the top link.');
		}
		
		//Fetch link:
		$link = $this->Us_model->fetch_link(intval($_REQUEST['id']));
		$p_update = (intval($_REQUEST['new_parent_id'])>0);
		
		if($link['status']<0){
			return echo_html(0,'You can edit active links only (status>0).');
		} elseif($_REQUEST['new_value']==$link['value'] && !$p_update){
			//Nothing has changed!
			return echo_html(0,'You did not make any changes.');
		}
		
		//We're good! Insert new link:
		$user_data = $this->session->userdata('user');
		$timestamp = date("Y-m-d H:i:s"); //Make sure all action in this batch have the same time
		
		if($p_update){
			//Fetch parent details:
			$parent_node = $this->Us_model->fetch_node(intval($_REQUEST['new_parent_id']), 'fetch_top_plain');
			
			//Did we find this?
			if($parent_node['node_id']<1 || $parent_node['node_id']!=intval($_REQUEST['new_parent_id'])){
				return echo_html(0,'Invalid parent.');
			}
		}
		
		//Valid, insert new row:
		$update_id = $this->Us_model->insert_link(array(
			'us_id' => $user_data['node_id'],
			'timestamp' => $timestamp,
			'status' => ( intval($_REQUEST['key']) ? ( strlen($_REQUEST['new_value'])>0 ? 2 : 3 ) : 1 ), //TODO: Consider "0" for non-admins
			'node_id' => $link['node_id'],
			'grandpa_id' => ( $p_update ? $parent_node['grandpa_id'] : $link['grandpa_id']),
			'parent_id' =>  ( $p_update ? $parent_node['node_id'] : $link['parent_id']),
			'value' => ( strlen($_REQUEST['new_value'])>0 ? $_REQUEST['new_value'] : null),
			'update_id' => $link['id'],
			'ui_rank' => $link['ui_rank'],
			'correlation' => $link['correlation'],
			'action_type' => 2, //For updating
		));
		
		if(!$update_id){
			//Ooops, some unknown error:
			return echo_html(0,'Unknown Error while saving changes.');
		}
		
		//Then remove old one:
		$affected_rows = $this->Us_model->update_link($link['id'], array(
			'update_id' => $update_id,
			'status' => -1, //-1 is for updated items.
		));
		
		
		if(!$affected_rows){
			//Ooops, some unknown error:
			return echo_html(0,'Unknown Error while removing old data.');
		} else {
			//All good!
			echo_html(1,'Saved!');
		}
	}
	
	function update_sort(){
		if(intval($_REQUEST['node_id'])<1 || !is_array($_REQUEST['new_sort'])){
			//We start with a DIV to prevent errors from being hidden after a few seconds:
			return echo_html(0,'Invalid Input.');
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
				return echo_html(0,'Unknown sort index.');
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
				return echo_html(0,'Unknown Error.');
			}
			
			//Then remove old one:
			$affected_rows = $this->Us_model->update_link($link['id'], array(
				'update_id' => $update_id,
				'status' => -1, //-1 is for updated items.
			));
			
			if(!$affected_rows){
				//Ooops, some unknown error:
				return echo_html(0,'Unknown Error.');
			} else {
				//All good!
				$success++;
			}
		}
		
		echo_html(1,'Saved!');
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
		$index = load_algolia();
		$index->clearIndex();
		$index->addObjects($obj);
	}
	
}

