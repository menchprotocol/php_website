<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		if(!auth(1) && 0){
			//Check for session for all functions here!
			echo_html(0,'Invalid session! Login and try again...');
			exit;
		}
	}
	
	
	
	
	
	
	
	
	function create_node(){
		//A one way function that creates a DIRECT LINK from the originating node.
		
		//Make sure all inputs are fine:
		if(intval($_REQUEST['parent_id'])<1 || strlen($_REQUEST['value'])<1 || strlen($_REQUEST['ui_rank'])<1){
			return echo_html(0,'Invalid inputs.');
		}
				
		//We're good! Insert new link:
		$new_link = $this->Us_model->insert_link(array(
			'parent_id' =>  intval($_REQUEST['parent_id']),
			'grandpa_id' => intval($_REQUEST['grandpa_id']),
			'value' => trim($_REQUEST['value']),
			'ui_rank' => intval($_REQUEST['ui_rank']),
			'action_type' => 1, //For adding
		));
		
		if(!$new_link){
			//Ooops, some unknown error:
			return echo_html(0,'Unknown Error while adding node.');
		}		
		
		//Return results as a new line:
		header('Content-Type: application/json');
		echo json_encode(array(
			'message' => echoFetchNode($new_link['parent_id'],$new_link['node_id']),
			'node' => $this->Us_model->fetch_full_node($new_link['parent_id']),
		));
	}
	
	
	function link_node(){
		
		//Make sure all inputs are find:
		if(intval($_REQUEST['parent_id'])<1 || intval($_REQUEST['child_node_id'])<1 || strlen($_REQUEST['ui_rank'])<1){
			return echo_html(0,'Invalid inputs.');
		}
		
		//We're good! Insert new link:
		$has_value = (strlen(trim($_REQUEST['value']))>0);
		$new_link = $this->Us_model->insert_link(array(
				'node_id' => intval($_REQUEST['child_node_id']),
				'parent_id' =>  intval($_REQUEST['parent_id']),
				'value' => ( $has_value ? trim($_REQUEST['value']) : null),
				'ui_rank' => intval($_REQUEST['ui_rank']),
				'action_type' => 4, //For linking
		));
		
		
		if(!$new_link){
			//Ooops, some unknown error:
			return echo_html(0,'Unknown Error while linking nodes.');
		}
		
		
		//Do we need to update search index?
		if($has_value){
			//TODO: Update Algolia Search index
			
		}
		
		//Return results as a new line:
		header('Content-Type: application/json');
		echo json_encode(array(
			'message' => echoFetchNode($new_link['parent_id'],$new_link['node_id'],intval($_REQUEST['normal_parenting'])),
			'node' => $this->Us_model->fetch_full_node( ( intval($_REQUEST['normal_parenting']) ? $new_link['parent_id'] : $new_link['node_id']) ),
		));
	}
	
	
	
	function inverse_link($link_id){
		$link = $this->Us_model->fetch_link($link_id);
		
		
	}
	
	
	function delete(){
		
		//Make sure all inputs are find:
		if(intval($_REQUEST['parent_id'])<1 || intval($_REQUEST['node_id'])<1 || intval($_REQUEST['id'])<1 || intval($_REQUEST['type'])<-4 || intval($_REQUEST['type'])>=0){
			return echo_html(0,'Invalid inputs.');
		}
		
		
		
		//TODO: Update Algolia Search index
		
		
		//Start deleting:
		if($_REQUEST['type']==-1){
			
			//Simple link delete:
			$status = $this->Us_model->delete_link(intval($_REQUEST['id']),intval($_REQUEST['type']));
			return echo_html($status,($status ? 'Link deleted.' : 'Unknown error.'));
			
		} else {
			
			//This is the deletion of the entire node!
			//Set session variable to show confirmation on redirect:
			$del_message = '<b>'.$_REQUEST['node_name'].'</b> was deleted';
			
			if($_REQUEST['type']==-3){
				
				//Move these nodes to $_REQUEST['parent_id']
				$moved_children = $this->Us_model->move_child_nodes(intval($_REQUEST['node_id']),intval($_REQUEST['parent_id']),intval($_REQUEST['type']));
				$del_message .= ' and '.$moved_children.' children have been moved here';
				
			} elseif($_REQUEST['type']==-4){
				
				//Recursively delete all children/grandchildren
				$deleted_children = $this->Us_model->recursive_node_delete(intval($_REQUEST['node_id']),intval($_REQUEST['type']));
				$del_message .= ' along with '.$deleted_children.' children/grandchildren';
				
				//Reindex search:
				$this->update_algolia();
				
			}
			
			//Main node delete:
			$status = $this->Us_model->delete_node(intval($_REQUEST['node_id']),intval($_REQUEST['type']));
			
			//Set header message for after redirect:
			$this->session->set_flashdata('hm', '<div class="alert alert-success" role="alert">'.$del_message.'.</div>');
			echo $status;
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
				'value' => ( strlen($_REQUEST['new_value'])>0 ? $_REQUEST['new_value'] : null),
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
		
		//TODO: Update Algolia Search index
		
		
		//Then remove old one:
		$affected_rows = $this->Us_model->update_link($link['id'], array(
			'update_id' => $new_link['id'],
			'status' => -1, //-1 is for updated items.
		));
		
		if(!$affected_rows){
			//Ooops, some unknown error:
			return echo_html(0,'Unknown Error while removing old data.');
		} else {
			//All good!
			echo_html(1,'');
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
				//return echo_html(0,'Unknown sort index.');
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
			
			//Valid, insert new row:
			$new_link = $this->Us_model->insert_link($update_data);
			
			if(!$new_link){
				//Ooops, some unknown error:
				return echo_html(0,'Unknown Error.');
			}
			
			//Then remove old one:
			$affected_rows = $this->Us_model->update_link($link['id'], array(
				'update_id' => $new_link['id'],
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
		
		echo_html(1,'');
	}
	
	
	function fetch_parent_tree($node_id){
		print_r($this->Us_model->fetch_parent_tree($node_id));
	}
	
	
	function health_check($naked_list_only=0){		
		//Would go through all active nodes and look for a series of issues.
		boost_power();
		
		//Fetch all active nodes with status>=0
		$active_nodes = $this->Us_model->fetch_node_ids();
		$parent_trees = array();
		//Prepare error list:
		$err = array(
			'missing_top' => array(), //When a node is missing a TOP link
			'multiple_top' => array(), //When a node has multiple TOP links
			'missing_algolia' => array(), //Top nodes that do not have a valid Algolia ID
			'outdated_grandpa' => array(), //When the grandpa ID does not belong to parent_id due to moving the nodes around
			'naked' => array(), //Nodes that have a single link to their parent with NO other IN/OUTs
		);
		
		//Start looping through nodes and look for errors:
		foreach($active_nodes as $node_id){
			//Fetch the top of each node:
			$parents = $this->Us_model->fetch_node($node_id, 'fetch_parents');
			$children = $this->Us_model->fetch_node($node_id, 'fetch_children');
			
			//Is this naked?
			if(count($parents)<=1 && count($children)<=0){
				array_push($err['naked'],$node_id);
			}
			
			//Now start checking for various issues:
			if(!$naked_list_only){
				foreach($parents as $k=>$v){
					if($k==0){
						if($v['ui_parent_rank']!=1){
							array_push($err['missing_top'],$node_id);
						} elseif(intval($v['algolia_id'])<=0){
							array_push($err['missing_algolia'],$node_id);
						}
					} elseif($k==1 && $v['ui_parent_rank']==1){
						//If there is a second TOP, it would be right after the first one!
						array_push($err['multiple_top'],$node_id);
					}
					
					//Every parent link's grandpa_id should be up to date:
					if(!isset($parent_trees[$node_id])){
						$parent_trees[$node_id] = $this->Us_model->fetch_parent_tree($v['node_id']);
					}
					
					//Is the grandpa up to date?
					if(end($parent_trees[$node_id])!=$v['grandpa_id']){
						if(!in_array($node_id,$err['outdated_grandpa'])){
							array_push($err['outdated_grandpa'],$node_id);
						}
						//Fix:
						$this->Us_model->update_link($v['id'],array('grandpa_id'=>end($parent_trees[$node_id])));
					}
				}
			}
		}
		
		if($naked_list_only){
			foreach($err['naked'] as $n){
				//echo '<a href="/'.$n.'">#'.$n.'</a><br />';
				echo $n.',';
			}
		} else {
			header('Content-Type: application/json');
			echo json_encode($err);
		}
	}
	
	
	
	//Run this to completely update the "nodes" index
	function update_algolia(){
		
		if(!is_production()){ 
			echo 'ERROR: Cannot update cache on local.';
			return false;
		}
		
		boost_power();
		
		//Buildup this array to save to search index
		$return = array();
		
		//Fetch all nodes:
		$active_node_ids = $this->Us_model->fetch_node_ids();
		foreach($active_node_ids as $node_id){
			//Add to main array
			array_push($return,generate_algolia_obj($node_id));
		}
			
		$obj = json_decode(json_encode($return), FALSE);
		//print_r($obj);
		
		//Include PHP library:
		$index = load_algolia();
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
		
		echo 'SUCCESS: Search index updated for '.count($return).' nodes.';
		//echo '$return: '; print_r($return);
		//echo '$obj_add_message: '; print_r($obj_add_message);
	}
}