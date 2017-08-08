<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Challenges_model extends CI_Model {
	
	function __construct() {
		parent::__construct();
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
	

}
