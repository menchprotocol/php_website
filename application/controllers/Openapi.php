<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Openapi extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	


	function reverse_delete($start_id, $end_id){
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
	
	function addYoutubeVideo($video_id,$parent_id,$user_node){
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		$this->load->helper('node/65');
		echo json_encode(add_youtube_video($video_id,0,0,$parent_id));
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
			array_push($result,add_youtube_video($url,0,0,$parent_id));
		}
		
		print_r($result);
		
	}
	
	
	
	function sliceYouTubePost(){
		
		$this->load->helper('node/65');
		
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		
		//Search through the people:
		/*
		$_POST['people_string'] = 'Elon Musk, Kevin Spacey';
		$public_figures = explode(',' , $_POST['people_string']);
		foreach($public_figures as $fullName){
			//Search among public figures /33
			$current = $this->Us_model->search_node($fullName, 33 , array('compare_lowercase'=>1));
			if(!isset($current[0]['node_id'])){
				//Lets index this person:
				
			}
		}
		*/
		
		//See if we need to create a new #hashtag:
		/*
		 * 
		 * var params = "youtube_id="+input_data['youtube_id']
		+"&start_time="+input_data['start_time']
		+"&end_time="+input_data['end_time']
		+"&selected_id="+input_data['selected_id']
		+"&new_node_text="+input_data['new_node_text']
		+"&description="+input_data['description'];
		 * 
		 * */
		
		if(intval($_POST['selected_id'])>0){
			//Insert the node into the pending bucket:
			$child_hashtag = intval($_POST['selected_id']);
		} elseif(intval($_POST['selected_id'])<1 && strlen($_POST['new_node_text'])>0){
			//Insert the node into the pending bucket:
			$new_hashtag = $this->Us_model->insert_link(array(
					'us_id' => intval($_POST['user_node_id']),
					'parent_id' => 298, //Newly added bucket
					'grandpa_id' => 3, //Always #Intent
					'value' => trim($_POST['new_node_text']),
					'ui_rank' => 999, //Place last on the list
					'action_type' => 1, //For adding
			));
			if(!$new_hashtag){
				echo json_encode($new_hashtag);
				return false;
			} else {
				$child_hashtag = $new_hashtag['node_id'];
			}
		} else {
			$child_hashtag = 0;
		}
		
		$result = add_youtube_video($_POST['youtube_id'],$_POST['start_time'],$_POST['end_time'],$child_hashtag,$_POST['description'],intval($_POST['user_node_id']));
		
		echo_html(1,'Added Successfully');
	}
	
	
	function bcall(){
		//echo '<script> chrome.runtime.sendMessage("mgdoadincellakcmdpobfleifadheffk", { status: "logged in" }); </script>';
	}
	
	function fetchUserSession(){
		
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		$ses = $this->session->all_userdata();
		if(count($ses)>1){
			echo json_encode($ses);
		} else {
			$this->session->set_userdata(array(
				'hello' => 1,
					'hi' => 2,
					'hi' => '333',
			));
			$ses = $this->session->all_userdata();
			echo json_encode($ses);
			echo json_encode($_POST);
		}
		
		
		/*
		header("Access-Control-Allow-Origin: *");
		if(auth(1)){
			$user_data = $this->session->userdata('user');
			header('Content-Type: application/json');
			echo json_encode($user_data);
		} else {
			echo 0;
		}*/
	}
	
	function search2steps($parent_start,$parent_end,$search_value){
		//Searches for a value/parent match, and then grab finds node connected to $parent_end
		$matches = $this->Us_model->search_node($search_value,$parent_start);
		//Display results:
		header("Access-Control-Allow-Origin: *");
		if(count($matches)>0){
			//Fetch the top nodes for what we found:
			$return_array = array();
			foreach($matches as $m){
				$top_parent = $this->Us_model->fetch_node($m['node_id'],'fetch_top_plain');
				if($top_parent['parent_id']==$parent_end){
					//Qualifies as part of the search result:
					array_push($return_array,$top_parent);
				}
			}
			if(count($return_array)>0){
				header('Content-Type: application/json');
				echo json_encode($return_array);
				return true;
			}
		}
		
		//Return this if nothing found:
		echo 0;
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