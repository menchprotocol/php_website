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
	
	function index($c_id=0){
		//Load home page:
		$this->load->view('front/shared/f_header' , array(
		    'title' => 'Weekly Bootcamps from Industry Experts',
		));
		$this->load->view('front/bootcamp/marketplace',array(
		    'c_id' => $c_id,
        ));
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
		//echo exec('whoami');
		//print_r($this->session->all_userdata());
		echo phpinfo();
	}
	
	
	/* ******************************
	 * Pitch Pages
	 ****************************** */


	function launch(){
	    $this->load->view('front/shared/f_header' , array(
            'title' => 'Guide Students to Success',
            'landing_page' => 'front/splash/instructors_why',
	    ));
	    $this->load->view('front/launch');
	    $this->load->view('front/shared/f_footer');
	}
	
	
	/* ******************************
	 * Bootcamp PUBLIC
	 ****************************** */


    function affiliate_click($b_id,$u_id=0,$goto_apply=0){
	    //DEPRECATED: Just keeping for Jason Cannon's Link to His Bootcamp
        $bs = $this->Db_model->b_fetch(array(
            'b.b_id' => $b_id,
        ));
        if(count($bs)>0){
            //Lets redirect to Page:
            redirect_message('/'.$bs[0]['b_url_key'].( $goto_apply ? '/apply' : '' ) );
        } else {
            //Invalid Bootcamp ID
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
        }
    }
	
	
	function landing_page($b_url_key,$r_id=null){
	    
	    //Fetch data:
	    $udata = $this->session->userdata('user');
        $class_settings = $this->config->item('class_settings');
        $bs = $this->Db_model->remix_bs(array(
	        'LOWER(b.b_url_key)' => strtolower($b_url_key),
	    ));

        //Validate Bootcamp:
        if(!isset($bs[0])){
            //Invalid key, redirect back:
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Bootcamp URL.</div>');
        } elseif($bs[0]['b_status']<2){
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp is archived.</div>');
        } elseif($bs[0]['b_fp_id']<=0){
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp not connected to a Facebook Page yet.</div>');
        } elseif(!(strcmp($bs[0]['b_url_key'], $b_url_key)==0)){
            //URL Case sensitivity redirect:
            redirect_message('/'.$bs[0]['b_url_key']);
        }

        $b = $bs[0];
        $focus_class = null; //Let's find this...
        $classes = $this->Db_model->r_fetch(array(
            'r.r_b_id' => $b['b_id'],
            'r.r_status' => 1,
        ), null, 'ASC', $class_settings['students_show_max']);

	    //Validate Class:
        if($r_id){
            $focus_class = filter($classes,'r_id',$r_id);
        } elseif(isset($classes[0])) {
            $focus_class = $classes[0];
        }

	    if(!$focus_class){
            redirect_message('/','<div class="alert alert-danger" role="alert">Error: '.( $r_id ? 'Class is expired.' : 'Did not find an active class for this Bootcamp.' ).'</div>');
	    }

	    //Load home page:
	    $this->load->view('front/shared/f_header' , array(
            'title' => $b['c_objective'].' - Starting '.time_format($focus_class['r_start_date'],2),
            'b_id' => $b['b_id'],
	        'b_fb_pixel_id' => $b['b_fb_pixel_id'], //Will insert pixel code in header
            'canonical' => 'https://mench.com/'.$b['b_url_key'].( $r_id ? '/'.$r_id : '' ), //Would set this in the <head> for SEO purposes
	    ));
	    $this->load->view('front/bootcamp/landing_page' , array(
	        'b' => $b,
            'classes' => $classes,
            'focus_class' => $focus_class,
	    ));
	    $this->load->view('front/shared/f_footer');
	}

}
