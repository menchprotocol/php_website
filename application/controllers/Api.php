<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		if(!auth(1)){
			//Check for session for all functions here!
			echo_html(0,'Session Expired! Login and try again...');
			exit;
		}
	}
	
	
	
	
	
	
	
	
	function create_node(){
		//A one way function that creates a DIRECT LINK from the originating node.
		
		//Make sure all inputs are fine:
		if(intval($_REQUEST['parent_id'])<1 || strlen($_REQUEST['value'])<1){
			return echo_html(0,'Invalid inputs.');
		}
		
		//See how many OUTs this node has:
		$children = $this->Us_model->fetch_node(intval($_REQUEST['parent_id']), 'fetch_children');
		
		//We're good! Insert new link:
		$new_link = $this->Us_model->insert_link(array(
				'parent_id' =>  intval($_REQUEST['parent_id']),
				'grandpa_id' => intval($_REQUEST['grandpa_id']),
				'value' => trim($_REQUEST['value']),
				'ui_parent_rank' => 1, //As this is a DIRECT IN for the newly created Node
				'ui_rank' => fetchMax($children,'ui_rank')+1, //This is always added as the last one
				'action_type' => 1, //For adding
		));
		
		if(!$new_link){
			//Ooops, some unknown error:
			return echo_html(0,'Unknown Error while adding node.');
		}		
		
		//Return results as a new line:
		header('Content-Type: application/json');
		echo json_encode(array(
				'message' => echoFetchNode($new_link['id'],$new_link['parent_id'],$new_link['node_id']),
				'node' => $this->Us_model->fetch_full_node($new_link['parent_id']),
				'link_id' => $new_link['id'],
		));
	}
	
	
	function link_node(){
				
		//Make sure all inputs are find:
		if(intval($_REQUEST['parent_id'])<1 || intval($_REQUEST['child_node_id'])<1){
			return echo_html(0,'Invalid inputs.');
		}
		
		if(!intval($_REQUEST['normal_parenting']) && is_production()){
			//Extra pause for JS to scroll up and user see the loading...
			sleep(1);
		}
		
		//Initial data set:
		$link_data = array(
				'node_id' => intval($_REQUEST['child_node_id']),
				'parent_id' =>  intval($_REQUEST['parent_id']),
				'value' => ( strlen(trim($_REQUEST['value']))>0 ? trim($_REQUEST['value']) : null),
				'action_type' => 4, //For linking
		);
		
		//Fetch current OUTs first:
		$children = $this->Us_model->fetch_node($link_data['parent_id'], 'fetch_children');
		$link_data['ui_rank'] = fetchMax($children,'ui_rank')+1;
		
		//Also append to last item in linked IN:
		$parents  = $this->Us_model->fetch_node($link_data['node_id'], 'fetch_parents');
		$link_data['ui_parent_rank'] = fetchMax($parents,'ui_parent_rank')+1;
		
		//We're good! Insert new link:
		$new_link = $this->Us_model->insert_link($link_data);
		
		if(!$new_link){
			//Ooops, some unknown error:
			return echo_html(0,'Unknown Error while linking nodes.');
		}
		
		//Return results as a new line:
		header('Content-Type: application/json');
		echo json_encode(array(
				'message' => echoFetchNode($new_link['id'],$new_link['parent_id'],$new_link['node_id'],intval($_REQUEST['normal_parenting'])),
				'node' => $this->Us_model->fetch_full_node( ( intval($_REQUEST['normal_parenting']) ? $new_link['parent_id'] : $new_link['node_id']) ),
				'link_id' => $new_link['id'],
		));
	}
	
	
	
	function inverse_link($link_id){
		$link = $this->Us_model->fetch_link($link_id);
		
		
	}
	
	
	function delete(){
		
		$type = intval($_REQUEST['type']);
		
		//Make sure all inputs are find:
		if(!isset($_REQUEST['is_inward']) || !isset($_REQUEST['new_parent_id']) || intval($_REQUEST['id'])<1 || $type<-4 || $type>=0){
			return echo_html(0,'Invalid inputs.');
		}
		
		
		//Start deleting:
		if($type==-1){
			
			//Simple link delete:
			$status = $this->Us_model->delete_link(intval($_REQUEST['id']),$type);
			return echo_html($status,($status ? 'Gem Marked for Removal' : 'Unknown error with status.'));
			
		} else {
			
			$link = $this->Us_model->fetch_link(intval($_REQUEST['id']));
			
			//This is the deletion of the entire node!
			//Set session variable to show confirmation on redirect:
			$del_message = '<b>'.$link['value'].'</b> removed';
			
			if($type==-2){
				
				//Regular node delete:
				$deleted_gems = $this->Us_model->delete_node($link['node_id'],$type);
				$del_message .= ': '.$deleted_gems.' related Gems also removed';
				
			} elseif($type==-3 && intval($_REQUEST['new_parent_id'])>0){
				
				$status = $this->Us_model->move_child_nodes($link['node_id'],intval($_REQUEST['new_parent_id']),$type);
				$del_message .= ': '.$status['moved'].' moved & '.$status['deleted'].' removed';
				
			} elseif($type==-4){
				
				//Recursively delete all children/grandchildren
				$deleted_gems = $this->Us_model->recursive_node_delete($link['node_id'],$type);
				$del_message .= ': '.$deleted_gems.' OUTs recursively removed';
				
				//Reindex search:
				if(!is_production()){
					$this->Algolia_model->sync_all();
				}
				
			} else {
				//Huh?!
				$this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">Invalid Inputs</div>');
				return false;
			}
			
			//Set header message for after redirect:
			$this->session->set_flashdata('hm', '<div class="alert alert-success" role="alert">'.$del_message.'.</div>');
		}
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
		
		if($p_update){
			//Fetch parent details:
			$parent_node = $this->Us_model->fetch_node(intval($_REQUEST['new_parent_id']), 'fetch_top_plain');
			
			//Did we find this?
			if($parent_node['node_id']<1 || $parent_node['node_id']!=intval($_REQUEST['new_parent_id'])){
				return echo_html(0,'Invalid parent.');
			}
		}
		
		//Valid, insert new row:
		$new_link = $this->Us_model->insert_link(array(
				'node_id' => $link['node_id'],
				'grandpa_id' => ( $p_update ? $parent_node['grandpa_id'] : $link['grandpa_id']),
				'parent_id' =>  ( $p_update ? $parent_node['node_id'] : $link['parent_id']),
				'value' => ( strlen($_REQUEST['new_value'])>0 ? $_REQUEST['new_value'] : ''),
				'update_id' => $link['id'],
				'ui_rank' => $link['ui_rank'],
				'ui_parent_rank' => $link['ui_parent_rank'],
				'algolia_id' => $link['algolia_id'],
				'action_type' => 2, //For updating
		));
		
		if(!$new_link){
			//Ooops, some unknown error:
			return echo_html(0,'Unknown Error while saving changes.');
		}
		
		
		//Return results as a new line:
		header('Content-Type: application/json');
		if($p_update){
			//Parent updated, redirect:
			echo json_encode(array(
					'message' => 'Success',
					'link_id' => $new_link['id'],
					'new_parent_id' => intval($_REQUEST['new_parent_id']),
			));
		} else {
			
			//What this a child or parent edit?
			$is_IN = !($_REQUEST['original_node_id']==$new_link['node_id']);
			
			echo json_encode(array(
					'message' => echoFetchNode($new_link['id'],$new_link['parent_id'],$new_link['node_id'],$is_IN,true),
					'node' => $this->Us_model->fetch_full_node( ( $is_IN ? $new_link['parent_id'] : $new_link['node_id']) ),
					'link_id' => $new_link['id'],
					'new_parent_id' => 0,
			));
		}
			
	}
	
	function update_sort(){
		
		if(intval($_REQUEST['node_id'])<1 || !is_array($_REQUEST['new_sort']) || !in_array($_REQUEST['sortType'],array('parent','child'))){
			//We start with a DIV to prevent errors from being hidden after a few seconds:
			return echo_html(0,'Invalid Input.');
		}
		
		//Awesome Sauce! lets move-on...
		//Fetch child links to update:
		$related_links = $this->Us_model->fetch_node(intval($_REQUEST['node_id']), ( $_REQUEST['sortType']=='child' ? 'fetch_children' : 'fetch_parents'));
		
		//print_r($_REQUEST['new_sort']);exit;
		//Inverse key
		$new_sort_index = array();
		foreach($_REQUEST['new_sort'] as $key=>$value){
			$new_sort_index[$value] = $key+2; //Reserve 1 for the top link, which is not affected through sorting as its unsortable.
		}
		
		
		$success = 0;
		foreach($related_links as $link){
			
			if(!array_key_exists($link['id'],$new_sort_index)){
				//Ooops, some unknown error:
				continue;
			}
			
			$update_data = array(
					'grandpa_id' => $link['grandpa_id'],
					'parent_id' => $link['parent_id'],
					'update_id' => $link['id'],
					'action_type' => 3, //For sorting
			);
			
			if($_REQUEST['sortType']=='child'){
				$update_data['ui_rank'] = $new_sort_index[$link['id']];
				$update_data['ui_parent_rank'] = $link['ui_parent_rank'];
			} else {
				$update_data['ui_rank'] = $link['ui_rank'];
				$update_data['ui_parent_rank'] = $new_sort_index[$link['id']];
			}
			
			//print_r($update_data);die();
			
			//Valid, insert new row:
			$new_link = $this->Us_model->insert_link($update_data);
			
			if(!$new_link){
				//Ooops, some unknown error:
				return echo_html(0,'Unknown Error while adding link.');
			}
			
			//Then remove old one:
			$affected_rows = $this->Us_model->update_link($link['id'], array(
				'update_id' => $new_link['id'],
				'status' => -1, //-1 is for updated items.
			));
			
			if(!$affected_rows){
				//Ooops, some unknown error:
				return echo_html(0,'Unknown Error while updating old link.');
			} else {
				//All good!
				$success++;
			}
		}
		
		echo_html(1,'');
	}
	
	
	function fetch_parent_tree($node_id){
		print_r($this->Us_model->fetch_parent_tree($node_id));
	}
}
