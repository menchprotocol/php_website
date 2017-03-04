<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Us_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	
	

	
	function fetch_top_users(){
		$res = array();
		//TODO: Expand to other elements and include in users table.
		$this->db->select('	
				u.username,
				COUNT(DISTINCT g.id) as count_patterns,
				COUNT(DISTINCT l.id) as count_links,
				
				((COUNT(DISTINCT g.id))*5 + (COUNT(DISTINCT l.id))*2 ) as points,
		');
		$this->db->from('users u');
		$this->db->join('links l' , 'l.creator_id = u.id' , 'left outer');
		$this->db->join('patterns g' , 'g.creator_id = u.id' , 'left outer');
		$this->db->where('u.status >' , 0);
		$this->db->where('u.email IS NOT NULL');
		$this->db->where('(g.status IS NULL OR g.status>0)');
		$this->db->where('(l.status IS NULL OR l.status>0)');
		$this->db->group_by('u.username');
		$this->db->order_by('points DESC');
		$q = $this->db->get();
		$res = $q->result_array();
		//Return everything:
		return $res;
	}

	
	
	function fetch_pattern($hashtag){
		
		if(!$hashtag){
			//Top of tree:
			$patterns = array(
				'p_id' => 0,
				'p_name' => 'Patterns',
				'p_status' => 1,
				'p_hashtag' => 'patterns',
				'p_description' => 'What we pursue in life.',
				'p_creator' => 'shervin',
			);
		} else {
			$patterns = array();
			$this->db->select('
				g.id AS p_id,
				g.name AS p_name,
				g.status as p_status,
				g.hashtag as p_hashtag,
				g.description as p_description,
				u.username AS p_creator
			');
			$this->db->from('patterns g');
			$this->db->join('users u' , 'u.id = g.creator_id');
			$this->db->where('g.hashtag' , $hashtag);
			$q = $this->db->get();
			$patterns = $q->row_array();
		}
		
		
		//Parent:
		$this->db->select('
			g.id AS p_id,
			g.name AS p_name,
			g.status as p_status,
			g.hashtag as p_hashtag,
			g.description as p_description,

			l.id AS link_id,
			l.status AS link_status,
			l.reference_notes AS link_reference_notes
		');
		$this->db->from('links l');
		$this->db->join('patterns g' , 'g.id = l.parent_id');
		$this->db->where('l.child_id' , $patterns['p_id']);
		$q = $this->db->get();
		$patterns['parents'] = $q->result_array();
		
		//Child:
		$this->db->select('
			g.id AS p_id,
			g.name AS p_name,
			g.status as p_status,
			g.hashtag as p_hashtag,
			g.description as p_description,

			u.username AS link_creator,

			l.id AS link_id,
			l.status AS link_status,
			l.reference_notes AS link_reference_notes
		');
		$this->db->from('links l');
		$this->db->join('patterns g' , 'g.id = l.child_id');
		$this->db->join('users u' , 'u.id = l.creator_id');
		$this->db->where('l.parent_id' , $patterns['p_id']);
		$q = $this->db->get();
		$patterns['children'] = $q->result_array();
		
		
		
		//Return everything:
		return $patterns;
	}

}