<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Engagement_model extends CI_Model {
	
	var $success_deletes;
	function __construct() {
		parent::__construct();
		$this->success_deletes = 0; //Used for recursive_node_delete() count tracker.
	}
	
	
	function log_engagement($link_data){
		
		if(!isset($link_data['us_id']) || !isset($link_data['node_type_id'])){
			//These are required
			return false;
		}
		
		//Now some improvements to the input in case missing:
		if(!isset($link_data['link_id'])){
			$link_data['link_id'] = 0;
		}
		if(!isset($link_data['timestamp'])){
			$link_data['timestamp'] = date("Y-m-d H:i:s");
		}
		if(!isset($link_data['blob'])){
			$link_data['blob'] = '';
		}
		
		//Lets now add:
		$this->db->insert('v3_engagement', $link_data);
		
		//Fetch inserted id:
		$link_data['id'] = $this->db->insert_id();
		
		//Boya!
		return $link_data;
	}
}
