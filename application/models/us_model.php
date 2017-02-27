<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Us_model extends CI_Model {

	function __construct() {
		parent::__construct();
	}

	
	
	
	
	
function fetch_top_sources(){
		
		$res = array();
		
		$this->db->select('*');
		$this->db->from('sources');
		$this->db->where('status >' , 0);
		$q = $this->db->get();
		$res = $q->result_array();
		
		//Also fetch all the authors for this source:
		foreach($res as $k=>$r){
			if(!isset($res[$k]['authors'])){
				$res[$k]['authors'] = array();
			}
			$this->db->select('u.username');
			$this->db->from('authors a');
			$this->db->join('users u', 'a.author_user_id = u.id');
			$this->db->where('u.status >' , 0);
			$this->db->where('a.status >' , 0);
			$this->db->where('a.source_id' , $r['id']);
			$q2 = $this->db->get();
			$authors = $q2->result_array();
			foreach($authors as $auth){
				array_push($res[$k]['authors'] , $auth['username']);
			}
			
			//Count the total references of this source:
			$this->db->select('COUNT(l.id) as reference_count');
			$this->db->from('links l');
			$this->db->where('l.status >' , 0);
			$this->db->where('l.source_id' , $r['id']);
			$q3 = $this->db->get();
			$ref_count = $q3->row_array();
			$res[$k]['reference_count'] = $ref_count['reference_count'];
		}
		
		
		
		
		//Return everything:
		return $res;
	}
	
	
	
function fetch_top_users(){
		$res = array();
		$this->db->select('	
				u.username,
				COUNT(DISTINCT g.id) as count_goals,
				COUNT(DISTINCT l.id) as count_links,
				COUNT(DISTINCT s.id) as count_sources,
				COUNT(DISTINCT a.id) as count_authors,
				
				((COUNT(DISTINCT s.id)+COUNT(DISTINCT g.id))*5 + (COUNT(DISTINCT a.id)+COUNT(DISTINCT l.id))*2 ) as points,
		');
		$this->db->from('users u');
		$this->db->join('authors a' , 'a.creator_id = u.id' , 'left outer');
		$this->db->join('links l' , 'l.creator_id = u.id' , 'left outer');
		$this->db->join('goals g' , 'g.creator_id = u.id' , 'left outer');
		$this->db->join('sources s' , 's.creator_id = u.id' , 'left outer');
		$this->db->where('u.status >' , 0);
		$this->db->where('u.email IS NOT NULL');
		$this->db->where('(g.status IS NULL OR g.status>0)');
		$this->db->where('(l.status IS NULL OR l.status>0)');
		$this->db->where('(s.status IS NULL OR s.status>0)');
		$this->db->where('(a.status IS NULL OR a.status>0)');
		$this->db->group_by('u.username');
		$this->db->order_by('points DESC');
		$q = $this->db->get();
		$res = $q->result_array();
		//Return everything:
		return $res;
	}
	
	
function fetch_top_goals(){
		$res = array();
		$this->db->select('
				g.hashtag,
				COUNT(DISTINCT lp.id) as count_child,
				COUNT(DISTINCT lc.id) as count_parent
		');
		$this->db->from('goals g');
		$this->db->join('links lp' , 'lp.parent_goal_id = g.id' , 'left outer');
		$this->db->join('links lc' , 'lc.child_goal_id = g.id' , 'left outer');
		$this->db->where('g.status >' , 0);
		$this->db->where('(lc.status IS NULL OR lc.status>0)');
		$this->db->where('(lp.status IS NULL OR lp.status>0)');
		$this->db->group_by('g.hashtag');
		$q = $this->db->get();
		$res = $q->result_array();
		//Return everything:
		return $res;
	}
	
	function fetch_related_goals($column , $goal_id){
		
	}
	
	function fetch_goal($goal_hashtag){
		
		
		if(!$goal_hashtag){
			//Top of goal tree:
			$goal = array(
				'goal_id' => 0,
				'goal_name' => 'Goals',
				'goal_status' => 1,
				'goal_hashtag' => 'goals',
				'goal_also_known_as' => 'Mission, Objectives',
				'goal_description' => 'What we pursue in life.',
				'goal_creator' => 'shervin',
			);
		} else {
			$goal = array();
			$this->db->select('
				g.id AS goal_id,
				g.name AS goal_name,
				g.status as goal_status,
				g.hashtag as goal_hashtag,
				g.also_known_as as goal_also_known_as,
				g.description as goal_description,
				u.username AS goal_creator
			');
			$this->db->from('goals g');
			$this->db->join('users u' , 'u.id = g.creator_id');
			$this->db->where('g.hashtag' , $goal_hashtag);
			$q = $this->db->get();
			$goal = $q->row_array();
		}
		
		
		//Parent goals:
		$this->db->select('
			g.id AS goal_id,
			g.name AS goal_name,
			g.status as goal_status,
			g.hashtag as goal_hashtag,
			g.also_known_as as goal_also_known_as,
			g.description as goal_description,
		
			l.id AS link_id,
			l.status AS link_status,
			l.reference_location AS link_reference_location,
			l.reference_notes AS link_reference_notes
		');
		$this->db->from('links l');
		$this->db->join('goals g' , 'g.id = l.parent_goal_id');
		$this->db->where('l.child_goal_id' , $goal['goal_id']);
		$q = $this->db->get();
		$goal['parents'] = $q->result_array();
		
		
		
		//Child goals:
		$this->db->select('
			g.id AS goal_id,
			g.name AS goal_name,
			g.status as goal_status,
			g.hashtag as goal_hashtag,
			g.also_known_as as goal_also_known_as,
			g.description as goal_description,

			u.username AS link_creator,
			s.hashtag AS link_source_hashtag,

			l.id AS link_id,
			l.status AS link_status,
			l.reference_location AS link_reference_location,
			l.reference_notes AS link_reference_notes
		');
		$this->db->from('links l');
		$this->db->join('goals g' , 'g.id = l.child_goal_id');
		$this->db->join('users u' , 'u.id = l.creator_id');
		$this->db->join('sources s' , 's.id = l.source_id');
		$this->db->where('l.parent_goal_id' , $goal['goal_id']);
		$q = $this->db->get();
		$goal['children'] = $q->result_array();
		
		
		
		//Return everything:
		return $goal;
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
	
		$this->db->select('*');
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