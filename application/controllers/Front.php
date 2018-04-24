<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->output->enable_profiler(FALSE);

        $udata = $this->session->userdata('user');
        if(is_old() && !isset($_GET['skip']) && !isset($udata['u_id'])){
            //Always redirect to newer version:
            redirect_message('https://mench.com'.$_SERVER['REQUEST_URI']);
        }
	}

    function ping(){
        echo_json(array('status'=>'success'));
    }
	
	function index($c_id=0){
		//Load home page:
        $data = array(
            'title' => 'Land Your Dream Tech Job',
            'c_id' => $c_id,
        );
		$this->load->view('front/shared/f_header' , $data);
		$this->load->view('front/bootcamp/marketplace', $data);
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
	
	
	function landing_page($b_url_key){
	    
	    //Fetch data:
	    $udata = $this->session->userdata('user');
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
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp not connected to a Facebook Page.</div>');
        } elseif(!(strcmp($bs[0]['b_url_key'], $b_url_key)==0)){
            //URL Case sensitivity redirect:
            redirect_message('/'.$bs[0]['b_url_key']);
        }

	    //Load home page:
	    $this->load->view('front/shared/f_header' , array(
            'title' => $bs[0]['c_objective'],
            'b_id' => $bs[0]['b_id'],
	        'b_fb_pixel_id' => $bs[0]['b_fb_pixel_id'], //Will insert pixel code in header
            'canonical' => 'https://mench.com/'.$bs[0]['b_url_key'], //Would set this in the <head> for SEO purposes
	    ));
	    $this->load->view('front/bootcamp/landing_page' , array(
	        'b' => $bs[0],
	    ));
	    $this->load->view('front/shared/f_footer');
	}

}
