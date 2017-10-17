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
	            'b.b_status' => 3,
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
	        'message' => ( $bootcamp['b_status']<=0 ? '<div class="alert alert-danger" role="alert"><span><i class="fa fa-eye-slash" aria-hidden="true"></i> ADMIN VIEW ONLY:</span>You can view this bootcamp only because you are logged-in as a mentor. This bootcamp is hidden from the public until published live.</div>' : null ),
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
	        'title' => 'Apply to '.$bootcamp['c_objective'].' Bootcamp Starting '.time_format($next_cohort['r_start_date'],1),
	        'next_cohort' => $next_cohort,
	    );
	    
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/bootcamp/apply' , $data);
	    $this->load->view('front/shared/p_footer');
	}	
	
	
	
	function application_status(){
	    
	    $application_status_salt = $this->config->item('application_status_salt');
	    if(!isset($_GET['u_key']) || !isset($_GET['u_id']) || intval($_GET['u_id'])<1 || !(md5($_GET['u_id'].$application_status_salt)==$_GET['u_key'])){
	        //Log this error:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Invalid Application Key. Choose your bootcamp and re-apply to receive an email with your application status url.</div>');
	        exit;
	    }
	    
	    //Search for cohort using form ID:
	    $users = $this->Db_model->u_fetch(array(
	        'u_id' => intval($_GET['u_id']),
	    ));
	    $udata = @$users[0];
	    //Fetch all 
	    $enrollments = $this->Db_model->ru_fetch(array(
	        'ru.ru_u_id'	=> $udata['u_id'],
	    ));
	    
	    //Fetch more data for each enrollment:
	    foreach($enrollments as $key=>$enrollment){
	        $cohorts = $this->Db_model->r_fetch(array(
	            'r.r_id' => $enrollment['ru_r_id'],
	        ));
	        //Assume this was fetched:
	        $enrollments[$key]['cohort'] = $cohorts[0];
	        //Fetch bootcamp:
	        $bootcamps = $this->Db_model->c_full_fetch(array(
	            'b.b_id' => $cohorts[0]['r_b_id'],
	        ));
	        //Assume this was fetched:
	        $enrollments[$key]['bootcamp'] = $bootcamps[0];
	    }
	    
	    //Did we find at-least one enrollment?
	    if(count($enrollments)<=0){
	        //Log this error:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">No Active Applications.</div>');
	        exit;
	    }
	    
	    
	    //Validate Cohort ID that it's still the latest:
	    $data = array(
	        'title' => 'My Application Status',
	        'u_id' => $_GET['u_id'],
	        'u_key' => $_GET['u_key'],
	        'enrollments' => $enrollments,
	        'hm' => ( isset($_GET['status']) && isset($_GET['message']) ? '<div class="alert alert-'.( intval($_GET['status']) ? 'success' : 'danger').'" role="alert">'.( intval($_GET['status']) ? 'Success' : 'Error').': '.$_GET['message'].'</div>' : '' ),
	    );
	    
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/bootcamp/application_status' , $data);
	    $this->load->view('front/shared/p_footer');
	}	
}