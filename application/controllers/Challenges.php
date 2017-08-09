<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenges extends CI_Controller {
	
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function index(){
		//Load views
		$this->load->view('challenges/z_header' , array(
				'title' => 'Hi',
		));
		$this->load->view('challenges/home_page');
		$this->load->view('challenges/z_footer');
	}
	
	
	
	
	
	
}