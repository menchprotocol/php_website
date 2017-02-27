<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goals extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	function index(){
		$this->load_goal();
		$top_goals = $this->Us_model->fetch_top_goals();
		$this->load->view('shared/header' , array( 'title' => 'Goals' ));
		$this->load->view('goals/all_goals' , array( 'top_goals' => $top_goals));
		$this->load->view('shared/footer');
	}
	
	function load_goal($goal_hashtag=null){
		//Load this specific goal:
		$goal = $this->Us_model->fetch_goal($goal_hashtag);
		$this->load->view('shared/header' , array( 'title' => $goal['goal_name'] ));
		$this->load->view('goals/goal_view' , array( 'goal' => $goal));
		$this->load->view('shared/footer');
	}
	
}
