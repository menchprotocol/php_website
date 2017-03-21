<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function fetch_pattern($pattern_id){
		//TODO: Validate session ID
		$data = $this->Us_model->fetch_pattern($pattern_id);
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	function edit_pattern(){
		print_r($_POST);
		die();
	}
	
	function autocomplete(){
		//TODO: Search patterns
		//$data = $this->Us_model->search_node(@$_GET['keyword'],intval(@$_GET['parentScope']));
		//header('Content-Type: application/json');
		//echo json_encode($data);
	}
	
}

