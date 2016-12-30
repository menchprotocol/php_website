<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Us_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	
	function fetch_id($id){
		$this->db->select('*');
		$this->db->from('us');
		$this->db->where('id' , $id);
		$q = $this->db->get();
		return $q->row_array();
	}
	
	function swap_column_values($column1, $column2, $where=null){
		$this->db->select($column1.','.$column2);
		if($where){
			$this->db->where($where);
		}
		$this->db->from('us');
		$q = $this->db->get();
		$res = $q->result_array();
	}
	
	function next_id(){
		$this->db->select('id');
		$this->db->from('us');
		$this->db->order_by('id', 'DESC');
		$q = $this->db->get();
		$res = $q->row_array();
		return $res['id']+1; 
	}
	
	function update_with_id( $id , $data ){
		$this->db->where('id', $id);
		$this->db->update('us', $data);
		return $this->db->affected_rows();
	}
	
	function insert_row($data){
		$this->db->insert('us', $data);
	}
	
	
	function fetch_link_anchor_text($id){
		$this->db->select('value_string');
		$this->db->from('us');
		$this->db->where('node_id='.$id);
		$this->db->where('parent=2');
		$q = $this->db->get();
		$arr = $q->row_array();
		//TODO: Maybe later we can do a combo view of the hashtag that also includes additional text...
		return $arr['value_string']; 
	}
	
	
	
	function search_node($search_keyword='', $node_link_id=0, $search_type='both'){
		$ps = null;
		if($node_link_id>0){
			//We have a limited scope to look for. What are the child IDs of this pattern?
			//TODO: Implement scope later on...
			//$ps = $this->fetch_pattern_from_id(intval($node_link_id));
		}
	
		$this->db->select('id,node_id,parent,value_string');
		$this->db->from('us');
		$this->db->where_in('parent',array(2));
		$this->db->where('status >',0);
		$this->db->like('LOWER(value_string)', strtolower($search_keyword), $search_type);
		if($ps){
			//Now apply limit:
			$this->db->where_in( 'node_id' , aggregate_children($ps,false) );
		}
		$q = $this->db->get();
		$res = $q->result_array();
		return $res;
	}
	
	
	function fetch_node_content($id){
		
		$this->db->select('*');
		$this->db->from('us');
		$this->db->where('(node_id='.$id.') OR ( parent=4 AND value_int='.$id.' )');
		$this->db->order_by('rank', 'ASC');
		$this->db->order_by('parent', 'ASC');
		$this->db->order_by('id', 'ASC');
		$q = $this->db->get();
		$nds = $q->result_array();
		$return_array = array();
		foreach($nds as $nd){
			
			//Assign core node data:
			if(!isset($return_array[$nd['id']])){
				$return_array[$nd['id']] = $nd;
			}
			if(!isset($return_array[$nd['parent']])){
				$return_array[$nd['parent']] = $this->fetch_id($nd['parent']);
			}
			
			//If its a Link, fetch the link's anchor_text
			if($nd['parent']==4){
				//Oh yeah this is it:
				if($nd['node_id']==$id && intval($nd['value_int'])>0){
					$return_array[$nd['id']]['hashtag'] = $this->fetch_link_anchor_text($nd['value_int']);
				} else {
					$return_array[$nd['id']]['hashtag'] = $this->fetch_link_anchor_text($nd['node_id']);
				}
			}
		}
		return $return_array;
	}

}