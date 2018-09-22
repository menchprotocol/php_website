<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Front extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->output->enable_profiler(FALSE);
        $udata = $this->session->userdata('user');
	}


    function error(){
            $this->load->view('front/shared/f_header', array(
                'title' => 'Page Not Found',
            ));
            $this->load->view('front/error');
            $this->load->view('front/shared/f_footer');
    }

    function index($c_id=0){
        //Load home page:
        $data = array(
            'title' => 'Land a Fabulous Programming Job',
            'c_id' => $c_id,
        );
        $this->load->view('front/shared/f_header' , $data);
        $this->load->view('front/home', $data);
        $this->load->view('front/shared/f_footer');
    }

	function login(){
	    //Check to see if they are already logged in?
	    $udata = $this->session->userdata('user');
	    if(isset($udata['u_inbound_u_id']) && in_array($udata['u_inbound_u_id'], array(1280,1308,1281))){
	        //Lead coach and above, go to console:
	        redirect_message('/intents');
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

    function ses(){
        echo_json($this->session->all_userdata());
    }

    function info(){
        echo phpinfo();
    }
	
	
	/* ******************************
	 * Pitch Pages
	 ****************************** */


	function train(){
        $data = array(
            'title' => 'Train Mench to become the best Personal Assistant',
            'landing_page' => 'front/splash/coaches_why',
        );
	    $this->load->view('front/shared/f_header' , $data);
	    $this->load->view('front/train' , $data);
	    $this->load->view('front/shared/f_footer');
	}


}
