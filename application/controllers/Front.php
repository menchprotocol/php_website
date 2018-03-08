<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->output->enable_profiler(FALSE);
	}

    function ping(){
        echo_json(array('status'=>'success'));
    }
	
	function index(){		
		//Load home page:
		$this->load->view('front/shared/f_header' , array(
				'landing_page' => 'front/splash/the_online_challenge_framework',
				'title' => 'Online Projects for the Ambitious.',
		));
		$this->load->view('front/index');
		$this->load->view('front/shared/f_footer');
	}
	
	function login(){
	    //Check to see if they are already logged in?
	    $udata = $this->session->userdata('user');
	    if(isset($udata['u_id']) && $udata['u_status']>=2){
	        //Lead instructor and above, go to console:
	        redirect_message('/console');
	    }
	    
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Login',
		));
		$this->load->view('front/login');
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
	
	function affiliate_click($b_id,$u_id,$goto_apply){
	    //Log this click under this user:
	    $projects = $this->Db_model->b_fetch(array(
	        'b.b_id' => $b_id,
	    ));
	    if(count($projects)<=0){
	        redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Project ID.</div>');
	    }
	    
	    //Validate the user:
	    $users = $this->Db_model->u_fetch(array(
	        'u_id' => $u_id,
	        'u_status >' => 0, //All active users
	    ));
	    if(count($users)<=0){
	        
	        //Invalid user, just redirect:
	        header( 'Location: /'.$projects[0]['b_url_key'].( $goto_apply ? '/apply' : '' ) );
	        
	    } else {
	        
	        //Everything matches, lets log engagement:	        
	        $e_new = $this->Db_model->e_create(array(
	            'e_initiator_u_id' => 0, //At this point the user does not have an account as they just clicked on the link
	            'e_message' => 'New visitor arrived from '.( isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : 'an unknown referrer' ).' with IP address of '.( $_SERVER['REMOTE_ADDR'] ? $_SERVER['REMOTE_ADDR'] : 'unknown'),
	            'e_json' => array(
	                'ip' => $_SERVER['REMOTE_ADDR'],
	                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
	                'referer_url' => (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null ),
	            ),
	            'e_type_id' => 45, //Affiliate Link Clicked
	            'e_b_id' => $b_id, //The Project ID they referred to
	            'e_recipient_u_id' => $u_id, //The affiliate ID
	        ));
	        
	        //Lets create Cookie:
	        $created_time = time();
	        $this->load->helper('cookie');
	        set_cookie(
	            'menchref',
	            $u_id.'-'.md5($u_id.'c00ki3'.$created_time.$e_new['e_id']).'-'.$created_time.'-'.$e_new['e_id'], //The referrer ID
	            (60*24*3600), //60 Days
	            '.mench.co'
	        );
	        
	        //Lets redirect to Page:
	        header( 'Location: /'.$projects[0]['b_url_key'].( $goto_apply ? '/apply' : '' ) );
	    }
	}


	function launch(){
	    $this->load->view('front/shared/f_header' , array(
	        'title' => 'Launch A Project',
	    ));
	    $this->load->view('front/launch');
	    $this->load->view('front/shared/f_footer');
	}
	
	
	/* ******************************
	 * Project PUBLIC
	 ****************************** */
	
	function projects_browse(){
	    
	    //Require login for now:
	    if(!auth(1)){
	        $hm = $this->session->flashdata('hm');
	        if($hm){
	            //Set again and redirect:
	            $this->session->set_flashdata('hm', $hm);
	        }
	        header( 'Location: /' );
	    }
	    
	    //The public list of Projects:
	    $this->load->view('front/shared/f_header' , array(
	        'title' => 'Browse Projects',
	    ));
	    $this->load->view('front/project/browse' , array(
	        'projects' => $this->Db_model->remix_projects(array(
	            'b.b_status >=' => 2,
	        )),
	    ));
	    $this->load->view('front/shared/f_footer');
	}
	
	
	
	
	function project_load($b_url_key,$r_id=null){
	    
	    //Fetch data:
	    $udata = $this->session->userdata('user');
	    $projects = $this->Db_model->remix_projects(array(
	        'LOWER(b.b_url_key)' => strtolower($b_url_key),
	    ));

        //Validate Project:
        if(!isset($projects[0])){
            //Invalid key, redirect back:
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Project URL.</div>');
        } elseif($projects[0]['b_status']<2 && (!isset($udata['u_status']) || $udata['u_status']<2)){
            redirect_message('/','<div class="alert alert-danger" role="alert">Project is not published yet.</div>');
        } elseif($projects[0]['b_fp_id']<=0){
            redirect_message('/','<div class="alert alert-danger" role="alert">Project not connected to a Facebook Page yet.</div>');
        }


	    //Validate Class:
	    $project = $projects[0];
	    $focus_class = filter_class($project['c__classes'],$r_id);
	    if(!$focus_class){
	        if(isset($udata['u_status']) && $udata['u_status']>=2){
	            //This is an admin, get them to the editing page:
                redirect_message('/','<div class="alert alert-danger" role="alert">Error: '.( $r_id ? 'Class is expired.' : 'You must <a href="/console/'.$project['b_id'].'/classes"><b><u>Create A Published Class</u></b></a> before loading the landing page.' ).'</div>');
            } else {
	            //This is a user, give them a standard error:
                redirect_message('/','<div class="alert alert-danger" role="alert">Error: '.( $r_id ? 'Class is expired.' : 'Did not find an active class for this Project.' ).'</div>');
            }
	    }

	    if($project['c__milestone_units']<=0){
	        //No active Tasks:
            redirect_message('/','<div class="alert alert-danger" role="alert">Error: You must <a href="/console/'.$project['b_id'].'/actionplan"><b><u>Create Some Tasks</u></b></a> before loading the landing page.</div>');
        }

	    //Load home page:
	    $this->load->view('front/shared/f_header' , array(
	        'title' => $project['c_objective'].' - Starting '.time_format($focus_class['r_start_date'],4),
	        'message' => ( $project['b_status']<2 ? '<div class="alert alert-danger" role="alert"><span><i class="fa fa-eye-slash" aria-hidden="true"></i> INSTRUCTOR VIEW ONLY:</span>You can view this Project only because you are logged-in as a Mench instructor.<br />This Project is hidden from the public until published live.</div>' : null ),
	        'r_fb_pixel_id' => $focus_class['r_fb_pixel_id'], //Will insert pixel code in header
	    ));
	    $this->load->view('front/project/landing_page' , array(
	        'project' => $project,
	        'focus_class' => $focus_class,
	    ));
	    $this->load->view('front/shared/f_footer');
	}
	
	
	function project_apply($b_url_key,$r_id=null){
	    //The start of the funnel for email, first name & last name

        //Fetch data:
        $udata = $this->session->userdata('user');
        $projects = $this->Db_model->remix_projects(array(
            'LOWER(b.b_url_key)' => strtolower($b_url_key),
        ));

        //Validate Project:
        if(!isset($projects[0])){
            //Invalid key, redirect back:
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Project URL.</div>');
        } elseif($projects[0]['b_status']<2){
            //Here we don't even let instructors move forward to apply!
            redirect_message('/','<div class="alert alert-danger" role="alert">Admission starts after Project is published live.</div>');
        } elseif($projects[0]['b_fp_id']<=0){
            redirect_message('/','<div class="alert alert-danger" role="alert">Project not connected to a Facebook Page yet.</div>');
        }
	    
	    //Validate Class ID that it's still the latest:
	    $project = $projects[0];
	    
	    //Lets figure out how many active classes there are!
	    $active_classes = array();
	    foreach($project['c__classes'] as $class){
	        if(filter_class(array($class),$class['r_id'])){
	            array_push($active_classes,$class);
	        }
	    }
	    
	    if(count($active_classes)<1){
	        
	        //Ooops, no active classes!
	        redirect_message('/'.$b_url_key ,'<div class="alert alert-danger" role="alert">No active classes found for this Project.</div>');
	        
	    } elseif(!$r_id && count($active_classes)>1){
	        
	        //Let the students choose which class they like to join:
	        $data = array(
	            'project' => $project,
	            'active_classes' => $active_classes,
	            'title' => 'Join '.$project['c_objective'],
	        );

	        //Load apply page:
	        $this->load->view('front/shared/p_header' , $data);
	        $this->load->view('front/project/choose_class' , $data); //TODO Build this
	        $this->load->view('front/shared/p_footer');
	        
	    } else {
	        
	        //Match the class and move on:
	        $focus_class = filter_class($project['c__classes'],$r_id);
	        if(!$focus_class){
	            //Invalid class ID, redirect back:
	            redirect_message('/'.$b_url_key ,'<div class="alert alert-danger" role="alert">Class is no longer active.</div>');
	        }

	        $data = array(
	            'title' => 'Join '.$project['c_objective'].' - Starting '.time_format($focus_class['r_start_date'],4),
	            'focus_class' => $focus_class,
	            'r_fb_pixel_id' => $focus_class['r_fb_pixel_id'], //Will insert pixel code in header
	        );
	        
	        //Load apply page:
	        $this->load->view('front/shared/p_header' , $data);
	        $this->load->view('front/project/apply' , $data);
	        $this->load->view('front/shared/p_footer');

	    }
	}
}
