<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patterns extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		//Require every one to login for all functions in this controller:
		auth();
	}
	
	
	function load_pattern($hashtag=null){
		//Load this specific pattern:
		$pattern = $this->Us_model->fetch_pattern($hashtag);
		$this->load->view('shared/header' , array( 'title' => $pattern['p_hashtag'] ));
		$this->load->view('patterns/viewp' , array( 'pattern' => $pattern));
		$this->load->view('shared/footer');
	}
	
	function new_pattern(){
		//Load this specific goal:
		//$goal = $this->Us_model->fetch_goal($hashtag);
		$this->load->view('shared/header' , array( 'title' => $goal['goal_name'] ));
		$this->load->view('patterns/viewp' , array( 'goal' => $goal));
		$this->load->view('shared/footer');
	}
	
}
