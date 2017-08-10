<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenges extends CI_Controller {
	
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function index(){
		if(substr_count($_SERVER['HTTP_HOST'],'mench.co')>0 || !is_production()){
			
			//Load views
			$this->load->view('challenges/shared/z_header' , array(
					'landing_page' => 'home1',
					'title' => 'Mench | Group Challenges for Entrepreneurs',
			));
			$this->load->view('challenges/home_page');
			$this->load->view('challenges/shared/z_footer');
			
		} else {
			//} elseif(substr_count($_SERVER['HTTP_HOST'],'us.foundation')>0 || substr_count($_SERVER['HTTP_HOST'],'brainplugins.com')>0  || substr_count($_SERVER['HTTP_HOST'],'askmench.com')>0){
			//Redirect to main website:
			header("Location: http://mench.co");
		}
	}
	
	
	
	function terms(){
		//Load views
		$this->load->view('challenges/shared/z_header' , array(
				'title' => 'Terms of Service & Privacy Policy',
		));
		$this->load->view('challenges/terms');
		$this->load->view('challenges/shared/z_footer');
	}
	
	function launch(){
		//Load views
		$this->load->view('challenges/shared/z_header' , array(
				'title' => 'Launch Your Challenge',
		));
		$this->load->view('challenges/launch');
		$this->load->view('challenges/shared/z_footer');
	}
	
	function browse(){
		//Load views
		$this->load->view('challenges/shared/z_header' , array(
				'title' => 'Browse Challenges',
		));
		$this->load->view('challenges/browse');
		$this->load->view('challenges/shared/z_footer');
	}
	
	
	
	
	
	
}