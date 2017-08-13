<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model {
	
	//This model handles all DB calls from our local database.
	
	function __construct() {
		parent::__construct();
	}
	
	
	/* ******************************
	 * User Functions
	 ****************************** */
	
	function users_fetch($match_columns){
		//Fetch the target gems:
		
		$this->db->select('*');
		$this->db->from('v5_users');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		return $q->result_array();
	}
	
	function user_create($insert_columns){
		
		//Missing anything?
		if(!isset($insert_columns['timestamp'])){
			$insert_columns['timestamp'] = date("Y-m-d H:i:s");
		}
		if(!isset($insert_columns['status'])){
			$insert_columns['status'] = 1;
		}
		
		//Lets now add:
		$this->db->insert('v5_users', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	function user_update($user_id,$update_columns){
		//Update first
		$this->db->where('id', $user_id);
		$this->db->update('v5_users', $update_columns);
		//Return new row:
		$users = $this->users_fetch(array(
				'id' => $user_id
		));
		return $users[0];
	}
	
	
	
	/* ******************************
	 * Challenge Functions
	 ****************************** */
	
	
}
