<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	function index(){
		
		//Redirect for our secondary domains:
		if(in_array($_SERVER['HTTP_HOST'],array('us.foundation','brainplugins.com','askmench.com','mench.ai'))){
			header("Location: http://mench.co");
		}
		
		//Load home page:
		$this->load->view('front/shared/f_header' , array(
				'landing_page' => 'front/splash/the_online_challenge_framework',
				'title' => 'Mench | The Online Challenge Framework.',
		));
		$this->load->view('front/index');
		$this->load->view('front/shared/f_footer');
	}
	
	function features(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Mench | Features',
		));
		$this->load->view('front/features');
		$this->load->view('front/shared/f_footer');
	}
	
	function aboutus(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Mench | About Us',
		));
		$this->load->view('front/aboutus');
		$this->load->view('front/shared/f_footer');
	}
	
	function pricing(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Mench | Pricing',
		));
		$this->load->view('front/pricing');
		$this->load->view('front/shared/f_footer');
	}
	
	function terms(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Mench | Terms & Privacy Policy',
		));
		$this->load->view('front/terms');
		$this->load->view('front/shared/f_footer');
	}
	
	function contact(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Mench | Contact Us',
		));
		$this->load->view('front/contact');
		$this->load->view('front/shared/f_footer');
	}
	
	function ses(){
		//For admins
		print_r($this->session->all_userdata());
	}
	
}