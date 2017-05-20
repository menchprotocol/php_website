<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		if(!auth(1)){
			//Check for session for all functions here!
			echo_html(0,'Invalid session! Login and try again...');
			exit;
		}
	}

	function test($node_id){
		print_r($this->Apiai_model->syncSingleEntity($node_id));
	}
	
	function del($id){
		print_r($this->Apiai_model->deleteSingleEntity($id));
	}
}