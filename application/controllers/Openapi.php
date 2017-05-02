<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Openapi extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function bcall(){
		echo '<script> chrome.runtime.sendMessage("mgdoadincellakcmdpobfleifadheffk", { status: "logged in" }); </script>';
	}
	
	function fetchUserSession(){
		header("Access-Control-Allow-Origin: *");		
		if(auth(1)){
			$user_data = $this->session->userdata('user');
			header('Content-Type: application/json');
			echo json_encode($user_data);
		} else {
			echo 0;
		}
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
}