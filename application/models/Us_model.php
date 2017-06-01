<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Us_model extends CI_Model {

	var $success_deletes;
	function __construct() {
		parent::__construct();
		$this->success_deletes = 0; //Used for recursive_node_delete() count tracker.
	}
	
	
	function restore_delete($start_gem_id, $end_gem_id){
		//Fetch the target gems:
		
		$this->db->select('*');
		$this->db->from('v3_data d');
		$this->db->where('d.id>=' , $start_gem_id);
		$this->db->where('d.id<=' , $end_gem_id);
		$this->db->where('d.status' , -2);
		$this->db->order_by('d.id' , 'DESC');
		$q = $this->db->get();
		$res = $q->result_array();
		
		$count = 0;
		foreach($res as $key=>$value){
			//First revert the old link:
			$this->Us_model->update_link( $value['update_id'] , array(
					'update_id' => 0, //Reset
					'status' => 1, //Active again
			));
			
			//Also delete this link:
			$this->db->where('id', $value['id']);
			$this->db->delete('v3_data');
			
			//Counter:
			$count++;
		}

		echo $count.' Restored.';
	}
	
	
	function next_node_id(){
		//Find the current largest node id and increments it by 1:
		$largest_node_id = $this->largest_node_id();
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
		
		$is_update = ( isset($link_data['update_id']) ? intval($link_data['update_id']) : 0 );
		$parent_update = false;
		
		//Fetch node ID and TOP nodes:
		
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
				//This should NOT happen:
				return false;
			}
		}
		
		
		//Now some improvements to the input in case missing:
		if(!isset($link_data['ui_rank'])){
			$link_data['ui_rank'] = ( $is_update ? $link['ui_rank'] : 1 ); //We assume the top of the child list
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
		
		//TODO Make sure new update_id=0
		if($link_data['status']>=0 && isset($link_data['update_id']) && intval($link_data['update_id'])>0){
			//$link_data['update_id'] = 0;
		}
		
		//Lets now add:		
		$this->db->insert('v3_data', $link_data);
		
		//Fetch inserted id:
		$link_data['id'] = $this->db->insert_id();
		
		
		//Remove older links if needed:
		if(in_array($link_data['action_type'],array(2,5)) && $is_update){
			//For updates, remove the old link:
			$this->Us_model->update_link( $link_data['update_id'] , array(
					'update_id' => $link_data['id'],
					'status' => -1, //Updated
			));
		} elseif($link_data['action_type']<0 && $is_update){
			$this->Us_model->update_link( $link_data['update_id'] , array(
					'update_id' => $link_data['id'],
					'status' => -2, //Deleted
			));
		}
		
		
		
		
		
		
		//Perform special/custom functions based on parent nodes.
		if($link_data['ui_parent_rank']==1 || ml_related($link_data['parent_id']) || ml_related($link_data['node_id'])){
			if($link_data['action_type']<0 || in_array($link_data['action_type'],array(2,4))){
				
				//Fetch the parents of this Node:
				$IN_links = $this->Us_model->fetch_node($link_data['node_id'], 'fetch_parents');
				
				
				if($link_data['action_type']<0 && $link_data['parent_id']==590){
									
					//We're deleting the !SyncSingleEntity Meta Data, which requires us to remove from remote:
					if($IN_links[0]['grandpa_id']==1){
						//This is an @Entity
						$delete_status = $this->Apiai_model->delete_entity($link_data['value']);
					} elseif($IN_links[0]['grandpa_id']==3){
						//This is an #Intent:
						$delete_status = $this->Apiai_model->delete_intent($link_data['value']);
					}
					
				} else {
					
					foreach($IN_links as $INs){
						if($INs['parent_id']==590){
							//Found the primary Publish Live Pattern.
							
							//This is for action 2/4 which means we need to add/update
							//Lets attemp to sync and save the results:
							
							//Anything else requires add/updating
							if($IN_links[0]['grandpa_id']==1){
								
								//This is an @Entity that needs syncing:
								$custom_status = $this->Apiai_model->sync_entity( $link_data['node_id'] , array( 'force_update' => 1, 'force_publish' => 1 ) );
								
							} elseif($IN_links[0]['grandpa_id']==3){
								
								//This is an #Intent that needs syncing:
								$custom_status = $this->Apiai_model->sync_intent( $link_data['node_id'] , array( 'force_update' => 1, 'force_publish' => 1 ) );
							}
						}
					}
				}
			}
		}
		
		
		
		
		
		
		
		
		
		//Algolia only works on Production due to Curl certificate requirements
		if(is_production()){
			
			$return = array();
			$index = $this->Algolia_model->load_algolia();
			
			if($link_data['action_type']==1){
				
				//For adding new nodes:
				$alg = $this->Algolia_model->generate_algolia_obj($link_data['node_id']);
				array_push($return , $alg);
				$res = $index->addObjects(arrayToObject($return));
				//Now update database with the objectIDs:
				if(isset($res['objectIDs'][0]) && intval($res['objectIDs'][0])>0){
					$link_data['algolia_id'] = $res['objectIDs'][0];
					$this->Us_model->update_link($link_data['id'],array('algolia_id'=>$link_data['algolia_id']));
				}
				
			} elseif($link_data['action_type']<0){
				
				if($link_data['algolia_id']>0){
					//We're deleting:
					$index->deleteObject($link_data['algolia_id']);
				} else {
					//This is secondary, lets update it:
					$top_node = $this->fetch_node($link_data['node_id'],'fetch_top_plain');
					if($top_node['algolia_id']>0){
						//We had this indexed, lets update it:
						$alg = $this->Algolia_model->generate_algolia_obj($link_data['node_id'],$top_node['algolia_id']);
						array_push($return , $alg);
						$res = $index->saveObjects($return);
					}
				}
				
			} elseif($is_update){
				
				if($link_data['algolia_id']>0){
					
					$alg = $this->Algolia_model->generate_algolia_obj($link_data['node_id'],$link_data['algolia_id']);
					array_push($return , $alg);
					$res = $index->saveObjects($return);
					
				} else {
					$top_node = $this->fetch_node($link_data['node_id'],'fetch_top_plain');
					if($top_node['algolia_id']>0){
						//We had this indexed, lets update it:
						$alg = $this->Algolia_model->generate_algolia_obj($link_data['node_id'],$top_node['algolia_id']);
						array_push($return , $alg);
						$res = $index->saveObjects($return);
					}
				}
				
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
				'update_id' => $link_id, //This would be deleted as well
				'action_type' => $action_type, //Could be single delete, batch delete, etc...
		));
		
		if(!$new_link){
			//Ooops, some unknown error:
			return false;
		}
		
		return 1;
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
		
		//Move DIRECT OUTs to a new parent:
		$child_data = $this->fetch_node($node_id, 'fetch_children');
		$new_parent = $this->fetch_node($new_parent_id, 'fetch_top_plain');
		
		$status = array(
				'moved' => 0,
				'deleted' => 0,
		);
		
		//First delete INs

		foreach ($child_data as $link){
			
			if($link['ui_parent_rank']==1){
				//This is DIRECT OUT:
				$new_link = $this->Us_model->insert_link(array(
						'grandpa_id' => $new_parent['grandpa_id'],
						'parent_id' => $new_parent['node_id'],
						'update_id' => $link['id'], //This would be deleted as well
						'ui_rank' => 999, //Position this at the end of the children of new parent
						'action_type' => $action_type,
				));
				$status['moved']++;
			} else {
				//This is regular OUT, which needs to be removed:
				$this->delete_link($link['id'],$action_type);
				$status['deleted']++;
			}			
		}
		
		//Delete main node:
		$status['deleted'] += $this->delete_node($node_id,$action_type);
		
		
		//Return number of nodes moved!
		return $status;
	}
	
	
	function recursive_node_delete($node_id , $action_type){
		//NUCLEAR! Find all grand-OUTs & delete!
		$OUTs = $this->fetch_node($node_id, 'fetch_children');
		
		//Start with outs:
		foreach ($OUTs as $link){
			//Go into this OUT if DIRECT:
			if($link['ui_parent_rank']==1){
				$this->success_deletes += $this->recursive_node_delete($link['node_id'], $action_type);
			} else {
				//Now delete this single Gem:
				$this->success_deletes += $this->delete_link($link['id'],$action_type);
			}
		}
		
		//Also delete INs:
		$this->success_deletes += $this->delete_node($node_id,$action_type);
		
		//Return number of nodes delete:
		return ($this->success_deletes-1);
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
	
	function count_OUTs($node_id){
		//Count the number of child nodes:
		$this->db->select('COUNT(id) as link_count');
		$this->db->from('v3_data d');
		$this->db->where('d.parent_id' , $node_id);
		$this->db->where('d.node_id != d.grandpa_id');
		$this->db->where('d.status >=' , 0);
		$this->db->where('d.ui_rank >' , 0);
		$q = $this->db->get();
		$stats = $q->row_array();
		return $stats['link_count'];
	}
	function count_direct_OUTs($node_id){
		//Count the number of child nodes:
		$this->db->select('COUNT(id) as link_count');
		$this->db->from('v3_data d');
		$this->db->where('d.parent_id' , $node_id);
		$this->db->where('d.ui_parent_rank' , 1);
		$this->db->where('d.status >=' , 0);
		$q = $this->db->get();
		$stats = $q->row_array();
		$grandparents = grandparents();
		return $stats['link_count']-( array_key_exists($node_id,$grandparents) ? 1 : 0 );
	}
	
	
	
	function fetch_sandwich_node($node_id, $parent_id){
		$this->db->select('*');
		$this->db->from('v3_data d');
		$this->db->where('d.node_id' , $node_id);
		$this->db->where('d.parent_id' , $parent_id);
		$this->db->where('d.status >' , 0);
		$q = $this->db->get();
		return $q->row_array();
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
		//TODO merge into fetch_node() with new action type
		//The new function that would use the old one to fetch the complete node:
		$parent = $this->Us_model->fetch_node($node_id, 'fetch_parents');
		$child 	= $this->Us_model->fetch_node($node_id, 'fetch_children');
		return array_merge($parent,$child);
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
			
			if(isset($setting['parent_id'])){
				$this->db->where('d.parent_id' , $setting['parent_id']);
			}
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
			return ( isset($links[0]) ? $links[0] : false );
		}
		
		//Lets curate/enhance the data a bit:
		$grandparents= grandparents(); //Everything at level 1
		//Caching mechanism for usernames and counts
		$cache = array(
				'contributors' => array(),
				'link_count' => array(),
				'out_count' => array(),
				'direct_out_count' => array(),
		);
		
		foreach($links as $i=>$link){
			
			//Append Sign, always:
			$links[$i]['sign'] = $grandparents[$link['grandpa_id']]['sign'];
			
			//Some elements are for the first level only, to make queries faster:
			if(!isset($setting['recursive_level'])){
				//Do we have this user ID in the cache variable?
				if(!isset($cache['contributors'][$link['us_id']])){
					//Fetch user name:
					$user_node[0] = $this->fetch_node($link['us_id'],'fetch_top_plain');
					$user_node[1] = $this->fetch_node($link['us_id'],'fetch_top_plain' , array('parent_id'=>24)); //Fetch email for this user:
					$cache['contributors'][$link['us_id']] = $user_node;
				}

				//Append uploader name:
				$links[$i]['us_node'] = $cache['contributors'][$link['us_id']];
			}
			
			if($i==0) {
				//This is only for the first parent:
				
				//Determine what are we counting based on parent/child position:
				$count_column = ( $links[0]['node_id']==$link['node_id'] && isset($setting['recursive_level']) ? $link['parent_id'] : $link['node_id']);
				$count_column = $node_id;
				
				if(!isset($cache['link_count'][$count_column])){
					//Fetch link counts:
					$cache['link_count'][$count_column] = $this->count_links($count_column);
					$cache['out_count'][$count_column] = $this->count_OUTs($count_column);
					$cache['direct_out_count'][$count_column] = $this->count_direct_OUTs($count_column);
				}
				
				//Count node links:
				$links[$i]['link_count'] = $cache['link_count'][$count_column];
				$links[$i]['out_count'] = $cache['out_count'][$count_column];
				$links[$i]['direct_out_count'] = $cache['direct_out_count'][$count_column];
			}
			
			
			//We fetch the parents of parent !MetaData nodes for settings:
			if( !isset($setting['recursive_level']) || $setting['recursive_level']<1){
				$links[$i]['parents'] = $this->fetch_node( ( $action=='fetch_parents' ? $link['parent_id'] : $link['node_id'] ) , 'fetch_parents', array('recursive_level'=>(!isset($setting['recursive_level'])?1:(1 + $setting['recursive_level']))));
			}
		}
		
		return $links;
	}
}
