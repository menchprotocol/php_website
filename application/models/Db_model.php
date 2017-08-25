<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model {
	
	//This model handles all DB calls from our local database.
	
	function __construct() {
		parent::__construct();
	}
	
	
	/* ******************************
	 * Users
	 ****************************** */
	
	//Called upon user login to save their Challenges/Runs into the session:
	function fetch_user_access($u_id){
		
		if(intval($u_id)<=0){
			return false;
		}
		
		//The framework:
		$user_access = array(
				'c' => array(), //Challenges
				'r' => array(), //Runs
		);
		
		//Fetch Runs:
		$this->db->select('ru.ru_r_id');
		$this->db->from('v5_challenge_run_users ru');
		$this->db->where('ru.ru_u_id',$u_id);
		$this->db->where('ru.ru_status >=',2); //Leader or Admin
		$q = $this->db->get();
		$run_users = $q->result_array();
		foreach($run_users as $ru){
			if(!in_array($ru['ru_r_id'], $user_access['r'], true)){
				array_push($user_access['r'] , $ru['ru_r_id']);
			}
		}
		
		if(count($user_access['r'])>0){
			//Now fetch unique challenges:
			$this->db->select('r.r_c_id');
			$this->db->from('v5_challenge_runs r');
			$this->db->where_in('r.r_id',$user_access['r']);
			$this->db->or_where('r.r_creator_id',$u_id);
			$q = $this->db->get();
			$runs = $q->result_array();
			foreach($runs as $r){
				if(!in_array($r['r_c_id'], $user_access['c'], true)){
					array_push($user_access['c'] , $r['r_c_id']);
				}
			}
		}
		
		//Any challenges they directly created?
		$this->db->select('c.c_id');
		$this->db->from('v5_challenges c');
		$this->db->where('c.c_creator_id',$u_id); //Challenges they created them selves
		$this->db->where('c.c_status >=',-1); //Deleted by user, but not removed by moderator.
		$this->db->where('c.c_is_grandpa',true); //necessary here since we have many none-grandpa challenges
		$q = $this->db->get();
		$challenges = $q->result_array();
		foreach($challenges as $c){
			if(!in_array($c['c_id'], $user_access['c'], true)){
				array_push($user_access['c'] , $c['c_id']);
			}
		}
		
		return $user_access;
	}
	
	function users_fetch($match_columns){
		//Fetch the target gems:
		
		$this->db->select('*');
		$this->db->from('v5_users u');
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
	
	
	function fetch_user_($user_id,$update_columns){
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
	
	function c_plain_fetch($match_columns){
		//Missing anything?
		$this->db->select('c.*');
		$this->db->from('v5_challenges c');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$q = $this->db->get();
		return $q->row_array();
	}
	
	
	function cr_outbound_fetch($match_columns){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('v5_challenges c');
		$this->db->join('v5_challenge_relations cr', 'cr.cr_outbound_id = c.c_id');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->order_by('cr.cr_outbound_rank','ASC');
		$q = $this->db->get();
		return $q->result_array();
	}
	
	function cr_inbound_fetch($match_columns){
		//Missing anything?
		$this->db->select('*');
		$this->db->from('v5_challenges c');
		$this->db->join('v5_challenge_relations cr', 'cr.cr_inbound_id = c.c_id');
		foreach($match_columns as $key=>$value){
			$this->db->where($key,$value);
		}
		$this->db->order_by('cr.cr_inbound_rank','ASC');
		$q = $this->db->get();
		return $q->result_array();
	}
	
	
	function challenge_update($c_id,$update_columns){
		$this->db->where('c_id', $c_id);
		$this->db->update('v5_challenges', $update_columns);
	}
	
	function cr_update($cr_id,$update_columns,$column='cr_id'){
		$this->db->where($column, $cr_id);
		$this->db->update('v5_challenge_relations', $update_columns);
		return $this->db->affected_rows();
	}
	
	function c_update($c_id,$update_columns){
		$this->db->where('c_id', $c_id);
		$this->db->update('v5_challenges', $update_columns);
		return $this->db->affected_rows();
	}
	
	
	
	
	function sync_algolia($c_id=null){
		
		boost_power();
		
		if(is_dev()){
			return file_get_contents("http://mench.co/marketplace/algolia/".$c_id);
		}
		
		//Include PHP library:
		require_once('application/libraries/algoliasearch.php');
		$client = new \AlgoliaSearch\Client("49OCX1ZXLJ", "84a8df1fecf21978299e31c5b535ebeb");
		$index = $client->initIndex('challenges');
		
		//Fetch all nodes:
		$limits = array(
				'c.c_status >=' => 0,
		);
		if($c_id){
			$limits['c_id'] = $c_id;
		} else {
			$index->clearIndex();
		}
		$challenges = $this->c_fetch($limits);
		
		//Buildup this array to save to search index
		$return = array();
		foreach($challenges as $challenge){
			//Adjust Algolia ID:
			if(intval($challenge['c_algolia_id'])>0){
				$challenge['objectID'] = intval($challenge['c_algolia_id']);
			}
			unset($challenge['c_algolia_id']);
			
			//Add to main array
			array_push( $return , $challenge);
		}
		
		//$obj = json_decode(json_encode($return), FALSE);
		//print_r($return);
		
		if($c_id){
			
			if($challenge['c_status']>=0){
				$obj_add_message = $index->saveObjects($return);
			} else {
				//Delete object:
				$index->deleteObject($challenge['c_algolia_id']);
			}
			
		} else {
			$obj_add_message = $index->addObjects($return);
			
			//Now update database with the objectIDs:
			if(isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs'])>0){
				foreach($obj_add_message['objectIDs'] as $key=>$algolia_id){
					$this->Db_model->c_update( $return[$key]['c_id'] , array(
							'c_algolia_id' => $algolia_id,
					));
				}
			}
		}			
		
		return array(
				'c_id' => $c_id,
				'challenges' => $challenges,
				'output' => $obj_add_message['objectIDs'],
		);
	}
	
	
}
