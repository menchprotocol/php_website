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
	
	function bootcamps_browse(){
	    //The public list of challenges:
	    $this->load->view('front/shared/f_header' , array(
	        'title' => 'Browse Bootcamps',
	    ));
	    $this->load->view('front/bootcamp/browse' , array(
	        'bootcamps' => $this->Db_model->c_full_fetch(array(
	            'c.c_status >=' => 1,
	            'c.c_is_grandpa' => true, //Not sub challenges
	        )),
	    ));
	    $this->load->view('front/shared/f_footer');
	}
	
	function bootcamp_load($c_url_key){
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'c.c_url_key' => $c_url_key,
	        'c.c_status >=' => 1,
	        'c.c_is_grandpa' => true, //Not sub challenges
	    ));
	    if(!isset($bootcamps[0])){
	        //Invalid key, redirect back:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Invalid bootcamp URL.</div>');
	    }


	    //Load home page:
	    $this->load->view('front/shared/f_header' , array(
	        'landing_page' => 'front/splash/product_splash',
	        'lp_variables' => array(
	            'c_image_url' => $bootcamps[0]['c_image_url'],
	        ),
	        'title' => $bootcamps[0]['c_objective'],
	    ));
	    $this->load->view('front/bootcamp/bootcamp_landing_page' , array(
	        'c' => $bootcamps[0],
	    ));
	    $this->load->view('front/shared/f_footer');
	}
	
	function start_bootcamp(){
	    //The public list of challenges:
	    $this->load->view('front/shared/f_header' , array(
	        'title' => 'Start A Bootcamp',
	    ));
	    $this->load->view('front/for_mentors');
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