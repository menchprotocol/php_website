<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->output->enable_profiler(FALSE);
	}
	
	function index(){		
		//Load home page:
		$this->load->view('front/shared/f_header' , array(
				'landing_page' => 'front/splash/the_online_challenge_framework',
				'title' => 'Online Bootcamps for the Ambitious.',
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
	 * Pitch Pages
	 ****************************** */
	
	function instructors(){
	    $this->load->view('front/shared/f_header' , array(
	        'title' => 'Launch A Bootcamp',
	    ));
	    $this->load->view('front/instructors');
	    $this->load->view('front/shared/f_footer');
	}
	
	
	/* ******************************
	 * Bootcamp PUBLIC
	 ****************************** */
	
	function bootcamps_browse(){
	    //The public list of challenges:
	    $this->load->view('front/shared/f_header' , array(
	        'title' => 'Browse Bootcamps',
	    ));
	    $this->load->view('front/bootcamp/browse' , array(
	        'bootcamps' => $this->Db_model->c_full_fetch(array(
	            'b.b_status >=' => 2,
	        )),
	    ));
	    $this->load->view('front/shared/f_footer');
	}
	
	function bootcamp_load($b_url_key){
	    //Fetch data:
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_url_key' => $b_url_key,
	    ));
	    
	    //Validate bootcamp:
	    if(!isset($bootcamps[0])){
	        //Invalid key, redirect back:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Invalid bootcamp URL.</div>');
	    }
	    //Validate status:
	    $udata = $this->session->userdata('user');
	    $bootcamp = $bootcamps[0];
	    if($bootcamp['b_status']<=0 && (!isset($udata['u_status']) || $udata['u_status']<=1)){
	        //Bootcamp not yet published:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Invalid bootcamp URL.</div>');
	    }
	    
	    //Load home page:
	    $this->load->view('front/shared/f_header' , array(
	        'title' => $bootcamp['c_objective'],
	        'message' => ( $bootcamp['b_status']<=0 ? '<div class="alert alert-danger" role="alert"><span><i class="fa fa-eye-slash" aria-hidden="true"></i> ADMIN VIEW ONLY:</span>You can view this bootcamp only because you are logged-in as an instructor. This bootcamp is hidden from the public until published live.</div>' : null ),
	    ));
	    $this->load->view('front/bootcamp/landing_page' , array(
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('front/shared/f_footer');
	}
	
	
	function bootcamp_apply($b_url_key,$r_id){
	    
	    //Fetch data:
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_url_key' => $b_url_key,
	    ));
	    
	    //Validate bootcamp:
	    if(!isset($bootcamps[0])){
	        //Invalid key, redirect back:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Invalid bootcamp URL.</div>');
	    }
	    
	    //Validate Cohort ID that it's still the latest:
	    $bootcamp = $bootcamps[0];
	    $next_cohort = filter_next_cohort($bootcamp['c__cohorts']);
	    if(!($next_cohort['r_id']==$r_id)){
	        //Invalid cohort ID, redirect back:
	        redirect_message('/bootcamps/'.$b_url_key ,'<div class="alert alert-danger" role="alert">Cohort is no longer active.</div>');
	    }
	    
	    $data = array(
	        'title' => 'Reserve Your Seat in '.$bootcamp['c_objective'].' Bootcamp Starting '.time_format($next_cohort['r_start_date'],4),
	        'next_cohort' => $next_cohort,
	    );
	    
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/bootcamp/apply' , $data);
	    $this->load->view('front/shared/p_footer');
	}
}