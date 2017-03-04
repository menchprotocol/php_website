<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patterns extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function load_pattern($hashtag=null){
		//Load this specific pattern:
		$pattern = $this->Us_model->fetch_pattern($hashtag);
		$this->load->view('shared/header' , array( 'title' => $pattern['p_name'] ));
		$this->load->view('patterns/viewp' , array( 'pattern' => $pattern));
		$this->load->view('shared/footer');
	}
	
	function eatcircle(){
		$this->load->view('misc/eatcircle');
	}
	
	function autocomplete(){
		//TODO: Search patterns
		//$data = $this->Us_model->search_node(@$_GET['keyword'],intval(@$_GET['parentScope']));
		//header('Content-Type: application/json');
		//echo json_encode($data);
	}
	
	function new_pattern(){
		//Load this specific goal:
		//$goal = $this->Us_model->fetch_goal($hashtag);
		$this->load->view('shared/header' , array( 'title' => $goal['goal_name'] ));
		$this->load->view('patterns/viewp' , array( 'goal' => $goal));
		$this->load->view('shared/footer');
	}
	
}
