<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Us_model extends CI_Model {

	var $success_deletes;
	function __construct() {
		parent::__construct();
		$this->success_deletes = 0; //Used for recursive_node_delete() count tracker.
	}
	
	
	function next_node_id(){
		//Find the current largest node id and increments it by 1:
		$largest_node_id = $this->Us_model->largest_node_id();
		$largest_node_id++;
		return $largest_node_id;
	}
	
	function insert_batch_links($batch_input){
		//Buildup for output:
		$batch_output = array();
		
		foreach($batch_input as $link_data){
			if(!isset($link_data['ui_rank'])){
				//A feature of batch insert in case needed:
				$link_data['ui_rank'] = count($batch_output)+1;
			}
			if(!isset($link_data['us_id']) || intval($link_data['us_id'])<1){
				//Assign to Guest for now
				$user_data = $this->session->userdata('user');
				if(isset($user_data['node_id'])){
					$link_data['us_id'] = $user_data['node_id'];
				} else {
					return false;
				}				
			}
			array_push( $batch_output , $this->insert_link($link_data) );
		}
		
		return $batch_output;
	}
	
	function insert_link($link_data){
		
		$is_update = (isset($link_data['update_id']) && intval($link_data['update_id'])>0);
		$parent_update = false;
		
		if($is_update){
			//This is replacing an older link, lets find related data for that:
			$link = $this->fetch_link(intval($link_data['update_id']));
			
			if(isset($link_data['parent_id'])){
				$parent_update = intval($link_data['parent_id'])!=intval($link['parent_id']);
			} else {
				//We would only auto-fill parent for updating requests
				$link_data['parent_id'] = $link['parent_id'];
			}
		}
		
		
		if(isset($link_data['action_type'])){
			$action_analysis = action_type_descriptions($link_data['action_type']);
			if(!$action_analysis['valid']){
				//This should NOT happen!
				return false;
			}
		} elseif(!isset($link_data['action_type']) || !isset($link_data['parent_id'])){
			//These are required
			return false;
		}
		
		
		if(!isset($link_data['us_id']) || intval($link_data['us_id'])<1){
			//Fetch user session:
			$user_data = $this->session->userdata('user');
			if(isset($user_data['node_id'])){
				$link_data['us_id'] = $user_data['node_id'];
			} else {
				//This should NOT happen!
				return false;
			}
		}
		
		
		//Now some improvements to the input in case missing:
		if(!isset($link_data['ui_rank'])){
			$link_data['ui_rank'] = ( $is_update? $link['ui_rank'] : 1 ); //We assume the top of the child list
		}
		if(!isset($link_data['ui_parent_rank'])){
			$link_data['ui_parent_rank'] = ( $is_update ? $link['ui_parent_rank'] : ( isset($link_data['node_id']) ? 2 : 1 ) );
		}
		if(!isset($link_data['status'])){
			//Solely based on the current user's privileges, for now all approved:
			//TODO Once there is regular users whom need moderation, we need to insert them as 0
			//TODO also keep in mind the GEM slicer conent which is being added without an active session
			$link_data['status'] = 1;
		} else {
			$status_analysis = status_descriptions($link_data['status']);
			if(!$status_analysis['valid']){
				//This should NOT happen!
				return false;
			}
		}
		if(!isset($link_data['grandpa_id'])){
			if($is_update && !$parent_update){
				$link_data['grandpa_id'] = $link['grandpa_id'];
			} else {
				$top_parent = $this->fetch_node(intval($link_data['parent_id']),'fetch_top_plain');
				$link_data['grandpa_id'] = $top_parent['grandpa_id'];
			}
		}
		if(!isset($link_data['timestamp'])){
			$link_data['timestamp'] = date("Y-m-d H:i:s");
		}
		if(!isset($link_data['node_id'])){
			$link_data['node_id'] = ( $is_update ? $link['node_id'] : $this->next_node_id() ); //Generate new one if not updating!
		}
		
		$value_updated = ( $is_update && isset($link_data['value']) && !($link_data['value']==$link['value']) );
		if(!isset($link_data['value'])){
			$link_data['value'] = ( $is_update ? $link['value'] : '' );
		}
		if(!isset($link_data['algolia_id'])){
			$link_data['algolia_id'] = ( $is_update ? intval($link['algolia_id']) : 0 );
		}
		
		
		//Lets now add:		
		$this->db->insert('v3_data', $link_data);
		
		//Fetch inserted id:
		$link_data['id'] = $this->db->insert_id();
		
		
		//Algolia only works on Production due to Curl certificate requirements
		if(is_production()){
			
			$return = array();
			$index = load_algolia();
			
			if($link_data['action_type']==1){
				
				array_push($return , generate_algolia_obj($link_data['node_id']));
				$res = $index->addObjects(json_decode(json_encode($return), FALSE));
				//Now update database with the objectIDs:
				if(isset($res['objectIDs'][0]) && intval($res['objectIDs'][0])>0){
					$link_data['algolia_id'] = $res['objectIDs'][0];
					$this->Us_model->update_link($link_data['id'],array('algolia_id'=>$link_data['algolia_id']));
				}
				
			} elseif($link_data['action_type']==2 || $link_data['action_type']==4){
				
				$top_node = $this->fetch_node($link_data['node_id'],'fetch_top_plain');
				if($top_node['algolia_id']>0){
					//We had this indexed, lets update it:
					array_push($return , generate_algolia_obj($link_data['node_id'],$top_node['algolia_id']));
					$res = $index->saveObjects(json_decode(json_encode($return), FALSE));
				}
				
			} elseif($link_data['action_type']<0 && $link_data['algolia_id']>0){
				
				//We're deleting:
				$index->deleteObject($link_data['algolia_id']);
				
			}
		}
		
		//Boya!
		return $link_data;
	}
	
	function update_link($link_id,$link_data){
		$this->db->where('id', $link_id);
		$this->db->update('v3_data', $link_data);
		return $this->db->affected_rows();
	}
	
	function delete_link($link_id,$action_type){
		//This would delete a single link.
		
		//Define key variables:
		$link = $this->fetch_link($link_id);
		
		//Insert new row to log delete history:
		$new_link = $this->Us_model->insert_link(array(
				'status' => -2, //Deleted
				'node_id' => $link['node_id'],
				'update_id' => $link_id, //This would be deleted as well
				'action_type' => $action_type, //Could be single delete, batch delete, etc...
		));
		
		if(!$new_link){
			//Ooops, some unknown error:
			return false;
		}
		
		//Also delete main link:
		$affected_rows = $this->Us_model->update_link($link_id, array(
			'update_id' => $new_link['id'],
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
		$child_data = $this->fetch_node($node_id, 'fetch_children');
		$new_parent = $this->fetch_node($new_parent_id, 'fetch_top_plain');
		
		$success_moves = 0;
		foreach ($child_data as $link){
			//Insert new row:
			$new_link = $this->Us_model->insert_link(array(
					'grandpa_id' => $new_parent['grandpa_id'],
					'parent_id' => $new_parent['node_id'],
					'update_id' => $link['id'], //This would be deleted as well
					'ui_rank' => 999, //Position this at the end of the children of new parent
					'action_type' => $action_type,
			));
			
			if(isset($new_link['id'])){
				
				//Also update original link:
				$affected_rows = $this->Us_model->update_link($link['id'], array(
						'update_id' => $new_link['id'],
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
		$child_data = $this->fetch_node($node_id, 'fetch_children');
		
		foreach ($child_data as $link){
			
			//Fetch child nodes first:
			if($link['node_id']!==$node_id){
				//Go to next level, if any:
				$this->recursive_node_delete($link['node_id'], $action_type);
			}
			
			//Main delete:
			$new_link = $this->Us_model->insert_link(array(
					'status' => -2, //Deleted
					'update_id' => $link['id'],
					'action_type' => $action_type,
			));
			
			if(isset($new_link['id'])){
				//Also update original link:
				$affected_rows = $this->Us_model->update_link($link['id'], array(
					'update_id' => $new_link['id'],
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
		$this->db->where('d.status >=' , 0);
		$this->db->where('d.ui_rank >' , 0);
		$q = $this->db->get();
		$stats = $q->row_array();
		return $stats['link_count'];
	}
	
	function search_node($value_string, $parent_id=null, $setting=array()){
		//Return the node_id of a link that matches the value and parent ID
		//TODO Maybe move to Agolia search engine for faster search.
		//Fetch all node links
		$this->db->select('*');
		$this->db->from('v3_data d');
		if($parent_id){
			$this->db->where('d.parent_id' , $parent_id);
		}
		
		if(isset($setting['compare_lowercase'])){
			$this->db->where('LOWER(d.value)', strtolower($value_string));
		} else {
			$this->db->where('d.value', $value_string);
		}
		
		$this->db->where('d.status >' , 0);
		$this->db->order_by('d.status' , 'ASC'); //status=1 always comes before status=2
		$q = $this->db->get();
		$res = $q->result_array();
		 
		//This can be expanded to append more things like child parent, etc...
		if(isset($setting['append_node_top'])){
			foreach($res as $key=>$value){
				$res[$key]['node'] = $this->fetch_node($value['node_id'], 'fetch_top_plain');
			}
		}
		
		return $res;
	}
	
	function fetch_parent_tree($node_id){
		//Recursively follows parent nodes to get to a grandparent and returns array:
		$return = array($node_id);
		$grandparents= grandparents();
		$reached_grandpa = false;
		
		$link['parent_id'] = $node_id;
		//Loop through all parents until we hit a grandpa:
		while(!$reached_grandpa){
			$link = $this->fetch_node($link['parent_id'],'fetch_top_plain');			
			array_push($return,$link['parent_id']);
			if(array_key_exists($link['parent_id'],$grandparents)){
				//Reached the top!
				$reached_grandpa = true;
				break;
			}
		}
		
		return $return;
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
		$merge = array_merge($parent,$child);
		
		if($merge[0]['status']==0){
			//TODO This is a hack, need a better solution:
			foreach($merge as $key=>$value){
				if($value['status']==1){
					//This is the main guy:
					$merge[$key] = $merge[0];
					$merge[0] = $value;
					break;
				}
			}
		}
		return $merge;
	}
	
	function fetch_grandpas_child($node_id){
		//Iteratively loop until parent_id = any grandpa_id
		$grandparents = grandparents();
		$looking = 1;
		while($looking){
			$fetch_node = $this->fetch_node($node_id,'fetch_top_plain');
			if(array_key_exists($fetch_node['parent_id'],$grandparents)){
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
		$this->db->where('d.status >=' , 0); //0 is pending approval, and 1+ is live content
		
		if($action=='fetch_parents' || $action=='fetch_top_plain'){
			
			$this->db->where('d.node_id' , $node_id);
			$this->db->order_by('d.ui_parent_rank' , 'ASC');
			
		} elseif($action=='fetch_children'){
			
			$this->db->where('d.parent_id' , $node_id);
			$this->db->where('d.node_id !=', $node_id);
			$this->db->where('d.ui_rank >' , 0); //Below 0 is hidden from the UI
			$this->db->order_by('d.ui_rank' , 'ASC'); //status=2 is ranked based on ur_rank ASC
			
		}
		
		//Default sorts:
		$q = $this->db->get();
		$links = $q->result_array();
		
		if($action=='fetch_top_plain'){
			//Quick return:
			return $links[0];
		}
		
		//Lets curate/enhance the data a bit:
		$grandparents= grandparents(); //Everything at level 1
		//Caching mechanism for usernames and counts
		$cache = array(
			'contributors' => array(),
			'link_count' => array(),
		);
		
		foreach($links as $i=>$link){
			
			//Append Sign, always:
			$links[$i]['sign'] = $grandparents[$link['grandpa_id']]['sign'];
			
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
			if( !isset($setting['recursive_level']) || $setting['recursive_level']<1){
				$links[$i]['parents'] = $this->fetch_node( ( $action=='fetch_parents' ? $link['parent_id'] : $link['node_id'] ) , 'fetch_parents', array('recursive_level'=>(!isset($setting['recursive_level'])?1:(1 + $setting['recursive_level']))));
			}
		}
		
		return $links;
	}
}
