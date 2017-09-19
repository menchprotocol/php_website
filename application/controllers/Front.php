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
				'title' => $this->lang->line('headline_primary'),
		));
		$this->load->view('front/index');
		$this->load->view('front/shared/f_footer');
	}
	
	function login(){
		$this->load->view('front/shared/f_header' , array(
				'title' => $this->lang->line('login'),
		));
		$this->load->view('front/partner_login');
		$this->load->view('front/shared/f_footer');
	}
	
	function features(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Features',
		));
		$this->load->view('front/features');
		$this->load->view('front/shared/f_footer');
	}
	
	function aboutus(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'About Us',
		));
		$this->load->view('front/aboutus');
		$this->load->view('front/shared/f_footer');
	}
	
	function terms(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Terms & Privacy Policy',
		));
		$this->load->view('front/terms');
		$this->load->view('front/shared/f_footer');
	}
	
	function contact(){
		$this->load->view('front/shared/f_header' , array(
				'title' => $this->lang->line('contact_us'),
		));
		$this->load->view('front/contact');
		$this->load->view('front/shared/f_footer');
	}
	
	function ses(){
		//For admins
		print_r($this->session->all_userdata());
		
	}
	
	
	/* ******************************
	 * Challenges PUBLIC
	 ****************************** */
	
	function challenge_browse($challenge_key){
		//The public list of challenges:
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Challenges',
		));
		//$this->load->view('front/terms');
		$this->load->view('front/shared/f_footer');
	}
	
	function challenge_landing_page($challenge_key){
		//Challenge Landing Page:
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Challenges',
		));
		//$this->load->view('front/terms');
		$this->load->view('front/shared/f_footer');
	}
	
	function challenge_join($challenge_key){
		//Challenge Signup Page
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Challenges',
		));
		//$this->load->view('front/terms');
		$this->load->view('front/shared/f_footer');
	}
	
}