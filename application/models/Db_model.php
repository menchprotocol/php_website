<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model {
	
	//This model handles all DB calls from our local database.
	
	function __construct() {
		parent::__construct();
	}
	
	
	/* ******************************
	 * Users
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
		if(!isset($insert_columns['u_timestamp'])){
			$insert_columns['u_timestamp'] = date("Y-m-d H:i:s");
		}
		if(!isset($insert_columns['u_status'])){
			$insert_columns['u_status'] = 1;
		}
		
		//Lets now add:
		$this->db->insert('v5_users', $insert_columns);
		
		//Fetch inserted id:
		$insert_columns['u_id'] = $this->db->insert_id();
		
		return $insert_columns;
	}
	
	function user_update($user_id,$update_columns){
		//Update first
		$this->db->where('u_id', $user_id);
		$this->db->update('v5_users', $update_columns);
		//Return new row:
		$users = $this->users_fetch(array(
				'u_id' => $user_id
		));
		return $users[0];
	}
	
	
	/* ******************************
	 * Runs
	 ****************************** */
	
	function r_fetch($match_columns){
		//Missing anything?
		$this->db->select('r.*');
		$this->db->from('v5_challenge_runs r');
		$this->db->join('v5_challenge_run_users ru', 'ru.ru_r_id = r.r_id', 'left');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->group_by('r.r_id');
		$this->db->order_by('r.r_version','ASC');
		$q = $this->db->get();
		$runs = $q->result_array();
		
		foreach($runs as $key=>$value){
			$runs[$key]['admins'] = $this->Db_model->c_users_fetch(array(
					'r.r_id' 			=> $value['r_id'],
					'ru.ru_status >='	=> 2, //2 & above are admins
					'u.u_status >='		=> 2, //Admin users
			));
		}
		
		return $runs;
	}
	
	
	
	
	/* ******************************
	 * Challenge
	 ****************************** */
	
	function c_ses_fetch($udata=null){
		//Loads all challenges and their runs into the session:
		if(!$udata){
			$udata = $this->session->userdata('user');
		}
		//Prepare filter:
		$match_columns = array(
				'ru.ru_u_id' => $udata['u_id'],
				'ru.ru_status >=' => 2, //Run Leader
		);
		
		$this->db->select('c.*');
		$this->db->from('v5_challenges c');
		$this->db->join('v5_challenge_runs r', 'r.r_c_id = c.c_id', 'left');
		$this->db->join('v5_challenge_run_users ru', 'ru.ru_r_id = r.r_id', 'left');
		$this->db->group_by('c.c_id');
		
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		$challenges = $q->result_array();
		
		foreach($challenges as $key=>$value){
			$challenges[$key]['admins'] = $this->Db_model->c_users_fetch(array(
					'r.r_c_id' 			=> $value['c_id'],
					'r.r_status >='		=> 1, //1 is Drafting
					'r.r_status <='		=> 3, //3 is Live
					'u.u_status >='		=> 1, //Active user
					'ru.ru_status >='	=> 2, //2 & above are admins
			));
			$challenges[$key]['runs'] = $this->r_fetch(array_merge( $match_columns , array(
					'r.r_c_id' => $value['c_id'],
					'r.r_status >=' => 1,
					'r.r_status <=' => 3,
			)));
		}
		
		return $challenges;
	}
	
	function c_users_fetch($match_columns){
		$this->db->select('u.*');
		$this->db->from('v5_challenge_runs r');
		$this->db->join('v5_challenge_run_users ru', 'ru.ru_r_id = r.r_id','left');
		$this->db->join('v5_users u', 'u.u_id = ru.ru_u_id','left');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->group_by('u.u_id');
		$q = $this->db->get();
		return $q->result_array();
	}
	
	
	function c_fetch($match_columns){
		//Missing anything?
		$this->db->select('c.*, COUNT(DISTINCT r.r_id) AS count_runs, COUNT(DISTINCT ru.ru_u_id) AS count_users');
		$this->db->from('v5_challenges c');
		$this->db->join('v5_challenge_runs r', 'r.r_c_id = c.c_id', 'left');
		$this->db->join('v5_challenge_run_users ru', 'ru.ru_r_id = r.r_id', 'left');
		$this->db->group_by('c.c_id');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		return $q->result_array();
	}
	
	
}
