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
				'title' => 'Login as Instructor',
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
				'title' => 'Contact Us',
		));
		$this->load->view('front/contact');
		$this->load->view('front/shared/f_footer');
	}
	
	function ses(){
		//For admins
		echo exec('whoami');
		print_r($this->session->all_userdata());
		echo phpinfo();
		
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
	    //Require login for now:
	    if(!auth(1)){
	        $hm = $this->session->flashdata('hm');
	        if($hm){
	            //Set again and redirect:
	            $this->session->set_flashdata('hm', $hm);
	            header( 'Location: /' );
	        }
	    }
	    
	    //The public list of challenges:
	    $this->load->view('front/shared/f_header' , array(
	        'title' => 'Browse Bootcamps',
	    ));
	    $this->load->view('front/bootcamp/browse' , array(
	        'bootcamps' => $this->Db_model->c_full_fetch(array(
	            'b.b_status >=' => 3,
	        )),
	    ));
	    $this->load->view('front/shared/f_footer');
	}
	
	
	
	
	function bootcamp_load($b_url_key,$r_id=null){
	    
	    
	    //Fetch data:
	    $udata = $this->session->userdata('user');
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_url_key' => $b_url_key,
	    ));
	    
	    //Validate bootcamp:
	    if(!isset($bootcamps[0]) || ($bootcamps[0]['b_status']<=0 && (!isset($udata['u_status']) || $udata['u_status']<=1))){
	        //Invalid key, redirect back:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Invalid bootcamp URL.</div>');
	    }
	    
	    //Validate Class:
	    $bootcamp = $bootcamps[0];
	    $focus_class = filter_class($bootcamp['c__classes'],$r_id);
	    if(!$focus_class){
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">'.( $r_id ? 'This class of '.$bootcamp['c_objective'].' has expired.' : $bootcamp['c_objective'].' Does not have any published classes.' ).'</div>');
	    }
	    
	    //Load home page:
	    $this->load->view('front/shared/f_header' , array(
	        'title' => $bootcamp['c_objective'].' - Starting '.time_format($focus_class['r_start_date'],4),
	        'message' => ( $bootcamp['b_status']<=0 ? '<div class="alert alert-danger" role="alert"><span><i class="fa fa-eye-slash" aria-hidden="true"></i> ADMIN VIEW ONLY:</span>You can view this bootcamp only because you are logged-in as an instructor. This bootcamp is hidden from the public until published live.</div>' : null ),
	    ));
	    $this->load->view('front/bootcamp/landing_page' , array(
	        'bootcamp' => $bootcamp,
	        'focus_class' => $focus_class,
	    ));
	    $this->load->view('front/shared/f_footer');
	}
	
	
	function bootcamp_apply($b_url_key,$r_id){
	    //The start of the funnel for email, first name & last name
	    
	    //Fetch data:
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_url_key' => $b_url_key,
	    ));
	    
	    //Validate bootcamp:
	    if(!isset($bootcamps[0])){
	        //Invalid key, redirect back:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Invalid bootcamp URL.</div>');
	    }
	    
	    //Validate Class ID that it's still the latest:
	    $bootcamp = $bootcamps[0];
	    $focus_class = filter_class($bootcamp['c__classes'],$r_id);
	    if(!($focus_class['r_id']==$r_id)){
	        //Invalid class ID, redirect back:
	        redirect_message('/bootcamps/'.$b_url_key ,'<div class="alert alert-danger" role="alert">Class is no longer active.</div>');
	    }
	    
	    $data = array(
	        'title' => 'Reserve Seat in '.$bootcamp['c_objective'].' - Starting '.time_format($focus_class['r_start_date'],4),
	        'focus_class' => $focus_class,
	    );
	    
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/bootcamp/apply' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	
	
}