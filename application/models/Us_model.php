<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Us_model extends CI_Model {

	var $success_deletes;
	function __construct() {
		parent::__construct();
		$this->success_deletes = 0; //Used for recursive_node_delete() count tracker.
	}
	
	
	function insert_link($link_data){
		$this->db->insert('v3_data', $link_data);
		return $this->db->insert_id();
	}
	
	function update_link($link_id,$link_data){
		$this->db->where('id', $link_id);
		$this->db->update('v3_data', $link_data);
		return $this->db->affected_rows();
	}
	
	function delete_link($link_id,$action_type){
		//This would delete a single link:
		
		//Define key variables:
		$user_data = $this->session->userdata('user');
		$link = $this->fetch_link($link_id);
		
		//Insert new row to log delete history:
		$update_id = $this->Us_model->insert_link(array(
			'us_id' => $user_data['node_id'],
			'timestamp' => date("Y-m-d H:i:s"),
			'status' => -2, //Deleted
			'node_id' => $link['node_id'],
			'grandpa_id' => $link['grandpa_id'],
			'parent_id' => $link['parent_id'],
			'value' => $link['value'],
			'update_id' => $link_id, //This would be deleted as well
			'ui_rank' => $link['ui_rank'],
			'correlation' => $link['correlation'],
			'action_type' => $action_type,
		));
		
		if(!$update_id){
			//Ooops, some unknown error:
			return false;
		}
		
		//Also delete main link:
		$affected_rows = $this->Us_model->update_link($link_id, array(
			'update_id' => $update_id,
			'status' => -2,
		));
		
		return intval($affected_rows); //1 or 0
	}
	
	function delete_node($node_id,$action_type){
		//This would delete all links within this node:
		$node = $this->fetch_node($node_id);
		$links_deleted = 0;
		foreach ($node as $key=>$value){
			$links_deleted += $this->delete_link($value['id'],$action_type);
		}
		return $links_deleted;
	}
	
	
	function move_child_nodes($node_id,$new_parent_id,$action_type){
		
		//Move all child nodes to a new parent:
		$user_data  = $this->session->userdata('user');
		$child_data = $this->fetch_node($node_id, 'fetch_children');
		$new_parent = $this->fetch_node($new_parent_id, 'fetch_top_plain');
		
		$success_moves = 0;
		foreach ($child_data as $link){
			//Insert new row:
			$update_id = $this->Us_model->insert_link(array(
					'us_id' => $user_data['node_id'],
					'timestamp' => date("Y-m-d H:i:s"),
					'status' => $link['status'],
					'node_id' => $link['node_id'],
					'grandpa_id' => $new_parent['grandpa_id'],
					'parent_id' => $new_parent['node_id'],
					'value' => $link['value'],
					'update_id' => $link['id'], //This would be deleted as well
					'ui_rank' => 999, //Position this at the end of the children of new parent
					'correlation' => $link['correlation'],
					'action_type' => $action_type,
			));
			
			if($update_id){
				//Also update original link:
				$affected_rows = $this->Us_model->update_link($link['id'], array(
						'update_id' => $update_id,
						'status' => -2, //Currently moving can happen only through deletion. TODO: Enable moving as a standalone function.
				));
				
				$success_moves += $affected_rows;
			}
		}
		//Return number of nodes moved!
		return $success_moves;
	}
	
	
	function recursive_node_delete($node_id,$action_type){
		
		//NUCLEAR! Find all children/grandchildren and delete!
		$user_data  = $this->session->userdata('user');
		$child_data = $this->fetch_node($node_id, 'fetch_children');
		
		foreach ($child_data as $link){
			
			//Fetch child nodes first:
			if($link['node_id']!==$node_id){
				//Go to next level, if any:
				$this->recursive_node_delete($link['node_id'], $action_type);
			}
			
			//Main delete:
			$update_id = $this->Us_model->insert_link(array(
				'us_id' => $user_data['node_id'],
				'timestamp' => date("Y-m-d H:i:s"),
				'status' => -2, //Deleted
				'node_id' => $link['node_id'],
				'grandpa_id' => $link['grandpa_id'],
				'parent_id' => $link['parent_id'],
				'value' => $link['value'],
				'update_id' => $link['id'],
				'ui_rank' => $link['ui_rank'],
				'correlation' => $link['correlation'],
				'action_type' => $action_type,
			));
			
			if($update_id){
				//Also update original link:
				$affected_rows = $this->Us_model->update_link($link['id'], array(
					'update_id' => $update_id,
					'status' => -2, //Deleted!
				));
				
				$this->success_deletes += $affected_rows;
			}
		}
		
		//Return number of nodes delete:
		return $this->success_deletes;
	}
	
	function largest_node_id(){
		$this->db->select('MAX(node_id) as largest_node');
		$this->db->from('v3_data d');
		$q = $this->db->get();
		$stats = $q->row_array();
		return $stats['largest_node'];
	}
	
	function count_links($node_id){
		//Count the number of child nodes:
		$this->db->select('COUNT(id) as link_count');
		$this->db->from('v3_data d');
		$this->db->where('(d.node_id='.$node_id.' OR d.parent_id='.$node_id.')');
		$this->db->where('d.status >' , 0);
		$this->db->where('d.ui_rank >' , 0);
		$q = $this->db->get();
		$stats = $q->row_array();
		return $stats['link_count'];
	}
	
	function search_node($value_string, $parent_id=null){
		//Return the node_id of a link that matches the value and parent ID
		//TODO Maybe move to Agolia search engine for faster search.
		//Fetch all node links
		$this->db->select('*');
		$this->db->from('v3_data d');
		if($parent_id){
			$this->db->where('d.parent_id' , $parent_id);
		}		
		$this->db->where('d.value', $value_string);
		$this->db->where('d.status >' , 0);
		$this->db->order_by('d.status' , 'ASC'); //status=1 always comes before status=2
		$q = $this->db->get();
		return $q->result_array();
	}
	
	
	
	function fetch_node_ids(){
		$this->db->distinct();
		$this->db->select('node_id');
		$this->db->from('v3_data d');
		$this->db->where('d.status >' , 0);
		$this->db->order_by('node_id' , 'ASC');
		$q = $this->db->get();
		$nodes = $q->result_array();
		$return = array();
		foreach($nodes as $node){
			array_push($return,$node['node_id']);
		}
		return $return;
	}
	
	
	
	function fetch_link($link_id){
		$this->db->select('*');
		$this->db->from('v3_data d');
		$this->db->where('d.id' , $link_id);
		$q = $this->db->get();
		return $q->row_array();
	}
	
	
	function fetch_full_node($node_id){
		//The new function that would use the old one to fetch the complete node:
		$parent = $this->Us_model->fetch_node($node_id, 'fetch_parents');
		$child 	= $this->Us_model->fetch_node($node_id, 'fetch_children');
		return array_merge($parent,$child);
	}
	
	function fetch_grandpas_child($node_id){
		//Iteratively loop until parent_id = any grandpa_id
		$grandpas = parents();
		$looking = 1;
		while($looking){
			$fetch_node = $this->fetch_node($node_id,'fetch_top_plain');
			if(array_key_exists($fetch_node['parent_id'],$grandpas)){
				//The parent of this is a grandpa!
				return $fetch_node['node_id'];
			} else {
				//Continue our search:
				$node_id = $fetch_node['parent_id'];
			}
		}
		return 0;
	}
	
	function fetch_node($node_id , $action='fetch_parents', $setting=array()){
		
		if(intval($node_id)<1 || !in_array($action,array('fetch_parents','fetch_children','fetch_top_plain'))){
			//No a valid node id or action
			return false;
		}
		
		//Fetch all node links
		$this->db->select('*');
		$this->db->from('v3_data d');
		$this->db->where('d.status >' , 0);
		
		if($action=='fetch_parents' || $action=='fetch_top_plain'){
			
			$this->db->where('d.node_id' , $node_id);
			
		} elseif($action=='fetch_children'){
			
			$this->db->where('d.parent_id' , $node_id);
			$this->db->where('d.node_id !=', $node_id);
			$this->db->where('d.ui_rank >' , 0); //Below 0 is hidden from the UI
			$this->db->order_by('d.ui_rank' , 'ASC'); //status=2 is ranked based on ur_rank ASC
			
		}
		
		//Default sorts:
		$this->db->order_by('d.status' , 'ASC'); //status=1 always comes before status=2
		$this->db->order_by('d.grandpa_id' , 'ASC'); //To group parents
		$this->db->order_by('d.parent_id' , 'ASC'); //To group parents
		$this->db->order_by('d.id' , 'DSC'); //To group parents
		$q = $this->db->get();
		$links = $q->result_array();
		
		
		
		if($action=='fetch_top_plain'){
			//Quick return:
			return $links[0];
		}
		
		
		
		//Lets curate/enhance the data a bit:
		$parents = parents(); //Everything at level 1
		//Caching mechanism for usernames and counts
		$cache = array(
			'contributors' => array(),
			'link_count' => array(),
		);
		
		foreach($links as $i=>$link){
			
			//Append Sign, always:
			$links[$i]['sign'] = $parents[$link['grandpa_id']]['sign'];
			
			//Some elements are for the first level only, to make queries faster:
			if(!isset($setting['recursive_level'])){
				//Do we have this user ID in the cache variable?
				if(!isset($cache['contributors'][$link['us_id']])){
					//Fetch user name:
					$person_link = $this->fetch_node($link['us_id'], 'fetch_top_plain');
					$cache['contributors'][$link['us_id']] = $person_link['value'];
				}
				
				//Determine what are we counting based on parent/child position:
				$count_column = ( ($node_id==$link['node_id']) ? $link['parent_id'] : $link['node_id']);
				if(!isset($cache['link_count'][$count_column])){
					//Fetch link counts:
					$cache['link_count'][$count_column] = $this->count_links($count_column);
				}
				
				//Append uploader name:
				//TODO: Maybe for some $action s only?
				$links[$i]['us_name'] = $cache['contributors'][$link['us_id']];
				
				//Count node links:
				$links[$i]['link_count'] = $cache['link_count'][$count_column];
			}
			
			
			//We fetch the parents of parent !MetaData nodes for settings:
			//TODO Maybe we only need to do this when grandpa_id=43 (MetaData). Time would tell...
			if( !isset($setting['recursive_level']) || $setting['recursive_level']<1){
				//Go fetch:
				$links[$i]['parents'] = $this->fetch_node( ( $action=='fetch_parents' ? $link['parent_id'] : $link['node_id'] ) , 'fetch_parents', array('recursive_level'=>(!isset($setting['recursive_level'])?1:(1 + $setting['recursive_level']))));
			}
			
			/*
			if($action=='fetch_parents'){
				if(strlen($link['value'])<1){
					//This has no value, meaning the node_id needs to be invoked for data:
					$invoke_node = $this->fetch_node($link['parent_id'], 'fetch_top_plain');
					$links[$i]['title'] = $parents[$invoke_node['grandpa_id']]['sign'].clean($invoke_node['value']);
					//$links[$i]['parent_name'] = '<a href="/'.$link['parent_id'].'">'.$links[$i]['title'].'</a>';
					$links[$i]['parent_name'] = $links[$i]['title'];
					$links[$i]['index'] = 1; //For debugging
				} else {
					//Create custom node title based on primary data set:
					$links[$i]['title'] = $parents[$link['grandpa_id']]['sign'].clean($link['value']);
					
					//Fetch parent name:
					$parent_top_link = $this->fetch_node($link['parent_id'], 'fetch_top_plain');
					$parent_sign = $parents[$link['grandpa_id']]['sign'];
					$links[$i]['parent_name'] = $parent_sign.clean($parent_top_link['value']);
					$links[$i]['index'] = 3; //For debugging
				}
				
			} elseif($action=='fetch_children'){
				
				if(strlen($link['value'])<1){
					//This has no value, meaning the node_id needs to be invoked for data:
					$invoke_node = $this->fetch_node($link['node_id'], 'fetch_top_plain');
					$links[$i]['title'] = $parents[$invoke_node['grandpa_id']]['sign'].clean($invoke_node['value']);
					
					$parent_top_link = $this->fetch_node($invoke_node['parent_id'], 'fetch_top_plain');
					$links[$i]['parent_name'] = $parents[$parent_top_link['grandpa_id']]['sign'].clean($parent_top_link['value']);
					$links[$i]['index'] = 2; //For debugging
				} else {
					//Create custom node title based on primary data set:
					$links[$i]['title'] = $parents[$link['grandpa_id']]['sign'].clean($link['value']);
					
					//Fetch parent name:
					$parent_top_link = $this->fetch_node($link['node_id'], 'fetch_top_plain');
					$parent_sign = $parents[$parent_top_link['grandpa_id']]['sign'];
					$links[$i]['parent_name'] = $parent_sign.clean($parent_top_link['value']);
					$links[$i]['index'] = 4; //For debugging
				}
			}
			*/
			
		}
		
		return $links;
	}
}