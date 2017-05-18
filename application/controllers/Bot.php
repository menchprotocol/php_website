<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	function test_message(){
		
	}
	
	function test(){
		//Fetch all node links
		header("Access-Control-Allow-Origin: *");
		header('Content-Type: application/json');
		echo json_encode(array('status'=>'yeaaaaaaaaa'));
	}
	
	
}