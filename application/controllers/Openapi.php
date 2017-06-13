<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Openapi extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	function danger(){
		//Only activate when needed
		//$this->Us_model->restore_delete(5887,6142);
	}
	
	
	function reverse_delete($start_id, $end_id){
		die('disabled for now');
		//Fetch all node links
		$this->db->select('*');
		$this->db->from('v3_data d');
		$this->db->where('d.status < ' , 0); //Must already be deleted
		$this->db->where('d.id >= ' , $start_id);
		$this->db->where('d.id <= ' , $end_id);
		$this->db->where('d.update_id > ' , 0);
		$q = $this->db->get();
		$deleted_links = $q->result_array();
		
		//Loop through and reverse:
		foreach($deleted_links as $value){
			//Bring back old one:
			$this->Us_model->update_link($value['update_id'],array('update_id'=>0,'status'=>2)); //TODO update to status=1
			
			//Delete this new deleted row:
			$this->db->where('id', $value['id']);
			$this->db->delete('v3_data');
		}
	}
	
	
	function validateUser(){
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		echo json_encode(user_login($_GET['user_email'],$_GET['user_pass']));
	}
	
	function indexYouTubeVideos(){
		
		die('inactive for now...');
		$parent_id = 56;
		$videos = explode("\n","https://www.youtube.com/watch?v=-HufDVSkgrI
https://www.youtube.com/watch?v=-HufDVSkgrI");
		
		//Loadup YouTube helper:
		$this->load->helper('node/65');
		$result = array();
		foreach($videos as $url){
			//This does all the heavy liftin:
			array_push($result,add_youtube_video($url,$parent_id));
		}
		
		print_r($result);
	}
	
	
	
	
	
	
	
	
	function addYoutubeVideo(){
		//Create a new YouTube video from Gem Chrome Extension when the user stumbles upon a new video:
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		$this->load->helper('node/65');
		
		//Validate inputs:
		if(!isset($_GET['us_id']) || intval($_GET['us_id'])<=0){
			echo json_encode(array(
					'status' => 0,
					'message' => 'Missing user ID.',
			));
		} elseif(!isset($_GET['video_id']) || strlen($_GET['video_id'])<8){
			echo json_encode(array(
					'status' => 0,
					'message' => 'Missing YouTube video ID',
			));
		} elseif((!isset($_GET['intent_id']) || intval($_GET['intent_id'])<=0) && (!isset($_GET['intent_name']) || strlen($_GET['intent_name'])<1)){
			echo json_encode(array(
					'status' => 0,
					'message' => 'Require either a link to existing intent or a new intent name.',
			));
		} else {
			
			//All seems good, lets define input variables:
			$video_id = trim($_GET['video_id']);
			$us_id = intval($_GET['us_id']);
			$intent_id_ref = intval($_GET['intent_id']);
			$people = trim($_GET['people']);
			$organizations = trim($_GET['organizations']);
			
			
			//Are we referencing existing intent, or creating a new one?
			if(!$intent_id_ref){
				//Creating new #Intent:
				$new_link = $this->Us_model->insert_link(array(
						'us_id' => $us_id,
						'parent_id' => 298, // #NewlyCreatedIntents
						'value' => trim($_GET['intent_name']),
						'action_type' => 1, //For adding
						'ui_parent_rank' => 1, //TOP
				));
				$intent_id_ref = $new_link['node_id']; //A new ID for this new #intent
			}
			
			//Create video:
			$batch_process = add_youtube_video($video_id,$intent_id_ref,$us_id);
			
			//See results:
			if(!$batch_process['status']){
				//O o, some issues in batch processing:
				echo json_encode(array(
						'status' => 0,
						'message' => $batch_process['message'],
				));
			} else {
				//All seems good!
				
				//We do have any people/organization references?
				$entity_refs = array();
				if(strlen($people)>0){
					$temp = explode(',',$people);
					foreach($temp as $obj_name){
						array_push($entity_refs,array(
								'container_pid' => 18, //People
								'container_val' => trim($obj_name),
								'connector_pid' => $batch_process['link'][0]['node_id'],
								'us_id' => $us_id,
						));
					}
				}
				if(strlen($organizations)>0){
					$temp = explode(',',$organizations);
					foreach($temp as $obj_name){
						array_push($entity_refs,array(
								'container_pid' => 21, //Organizations
								'container_val' => trim($obj_name),
								'connector_pid' => $batch_process['link'][0]['node_id'],
								'us_id' => $us_id,
						));
					}
				}
				
				if(count($entity_refs)>0){
					//We do have some!
					$this->Us_model->append_metadata($entity_refs);
				}
				
				//All went well!
				echo json_encode(array(
						'status' => 1,
						'message' => $batch_process['message'],
						'link' => $batch_process['link'][0], //Return only top link in Batch
				));
			}
		}	
	}

	
	
	
	function sliceYouTubeVideo(){
		
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		
		if(!isset($_GET['video_node_id']) || intval($_GET['video_node_id'])<=0){
			echo json_encode(array(
					'status' => 0,
					'message' => 'Missing Indexed video PID.',
			));
		} elseif(!isset($_GET['us_id']) || intval($_GET['us_id'])<=0){
			echo json_encode(array(
					'status' => 0,
					'message' => 'Missing User PID.',
			));
		} elseif((!isset($_GET['intent_id']) || intval($_GET['intent_id'])<=0) && (!isset($_GET['intent_name']) || strlen($_GET['intent_name'])<1)){
			echo json_encode(array(
					'status' => 0,
					'message' => 'Require either a link to existing intent or a new intent name.',
			));
		} elseif(!isset($_GET['start_time']) || intval($_GET['start_time'])<=0 || !isset($_GET['end_time']) || strlen($_GET['end_time'])<1){
			//TODO Improve time check validation and sync with JS logic on contentscript.js
			echo json_encode(array(
					'status' => 0,
					'message' => 'We require both start time and end time to slice this video.',
			));
		} else {
			
			//All seems good, lets define input variables:
			$us_id = intval($_GET['us_id']);
			$intent_id_ref = intval($_GET['intent_id']);
			
			//Are we referencing existing intent, or creating a new one?
			if(!$intent_id_ref){
				//Creating new #Intent:
				$new_link = $this->Us_model->insert_link(array(
						'us_id' => $us_id,
						'parent_id' => 298, // #NewlyCreatedIntents
						'value' => trim($_GET['intent_name']),
						'action_type' => 1, //For adding
						'ui_parent_rank' => 1, //TOP
				));
				$intent_id_ref = $new_link['node_id']; //A new ID for this new #intent
			}
			
			//Insert new Reference
			$new_link2 = $this->Us_model->insert_link(array(
					'us_id' => $us_id,
					'node_id' => $intent_id_ref,
					'parent_id' => intval($_GET['video_node_id']),
					'value' => 'slice/'.intval($_GET['start_time']).':'.intval($_GET['end_time']),
					'action_type' => 4, //For linking
			));
			
			//See results:
			echo json_encode(array(
					'status' => 1,
					'message' => 'Gem #'.$new_link2['id'].' successfully collected.',
			));
			
		}
	}
	
	
	
	function search2steps($parent_start,$parent_end,$search_value){
		//Searches for a value/parent match, and then grab finds node connected to $parent_end
		$matches = $this->Us_model->search_node($search_value,$parent_start);
		$return_array = array();
		if(count($matches)>0){
			//Fetch the top nodes for what we found:
			foreach($matches as $m){
				$top_parent = $this->Us_model->fetch_node($m['node_id'],'fetch_top_plain');
				if($top_parent['parent_id']==$parent_end){
					//Qualifies as part of the search result:
					array_push($return_array,$top_parent);
					break;
				}
			}
		}
		
		
		//Display results:
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		
		if(count($return_array)>0){
			echo json_encode(array(
					'status' => 1,
					'message' => 'Video Found.',
					'link' => $return_array[0], //Only one, since we broke the loop
			));
		} else {
			//Return this if nothing found:
			echo json_encode(array(
					'status' => 0,
					'message' => 'Video Not Found.',
					'link' => array(),
			));
		}	
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
				'is_naked' => array(), //Nodes that have a single link to their parent with NO other IN/OUTs
				
				'update_id_unsync' => array(), //update_id=0 for most recent nodes, and everything before that should reflect previous Gems pointing to the most recent v3_data.id
				'missing_ui_parent_rank' => array(), //ui_parent_rank should be >=1 all the time
				'is_fat' => array(), //patterns with many links
		);
		
		//Start looping through nodes and look for errors:
		foreach($active_nodes as $key=>$node_id){
			//Fetch the top of each node:
			$parents  = $this->Us_model->fetch_node($node_id, 'fetch_parents', array('recursive_level'=>1));
			$children = $this->Us_model->fetch_node($node_id, 'fetch_children', array('recursive_level'=>1));
			
			//Is this naked?
			if(count($parents)<=1 && count($children)<=0 && !in_array($node_id,$err['is_naked'])){
				array_push($err['is_naked'],$node_id);
			} elseif((count($parents)+count($children))>=20 && !in_array($node_id,$err['is_fat'])){
				array_push($err['is_fat'],$node_id);
			}
			
			//Now start checking for various issues:
			if(!$naked_list_only){
				foreach($parents as $k=>$v){
					
					if($k==0){
						if($v['ui_parent_rank']!=1 && !in_array($node_id,$err['missing_top'])){
							array_push($err['missing_top'],$node_id);
						} elseif(intval($v['algolia_id'])<=0 && !in_array($node_id,$err['missing_algolia'])){
							array_push($err['missing_algolia'],$node_id);
						}
					} elseif($k>0 && $v['ui_parent_rank']==1 && !in_array($node_id,$err['multiple_top'])){
						//If there is a second TOP, it would be right after the first one!
						array_push($err['multiple_top'],$node_id);
					}
					
					if($v['ui_parent_rank']<1 && !in_array($node_id,$err['missing_ui_parent_rank'])){
						//If there is a second TOP, it would be right after the first one!
						array_push($err['missing_ui_parent_rank'],$node_id);
					}
					
					
					
					
					
					if(intval($v['update_id'])>0){
						//Ooops, this should always be zero for active links:
						if(!in_array($node_id,$err['update_id_unsync'])){
							array_push($err['update_id_unsync'],$node_id);
						}						
						//Fix:
						$this->Us_model->update_link($v['id'],array('update_id'=>0));
					}
					
					//Is the grandpa up to date?
					//Every parent link's grandpa_id should be up to date:
					if(!isset($parent_trees[$node_id])){
						$parent_trees[$node_id] = $this->Us_model->fetch_parent_tree($v['node_id']);
					}
					
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
			foreach($err['is_naked'] as $n){
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
		echo $this->Algolia_model->sync_all();
	}
}