<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Custom extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->output->enable_profiler(FALSE);
        $udata = $this->session->userdata('user');
	}


    function error(){
        $this->load->view('custom/shared/f_header', array(
            'title' => 'Page Not Found',
        ));
        $this->load->view('custom/error');
        $this->load->view('custom/shared/f_footer');
    }


    function jobs(){
        $this->load->view('custom/shared/f_header' , array(
            'title' => 'Work at Mench',
        ));
        $this->load->view('custom/mench-co-jobs');
        $this->load->view('custom/shared/f_footer');
    }


    function index(){
        $udata = $this->session->userdata('user');

        if(isset($udata['u__inbounds']) && array_any_key_exists(array(1308,1281),$udata['u__inbounds'])){

            //Lead coach and above, go to console:
            redirect_message('/intents/'.$this->config->item('primary_c'));

        } elseif(( isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='mench.co' )) {

            $this->load->view('custom/shared/f_header' , array(
                'title' => 'Online Education. Transformed.',
            ));
            $this->load->view('custom/mench-co-intro');
            $this->load->view('custom/shared/f_footer');

        } else {

            //Go to default landing page:
            return redirect_message('/'.$this->config->item('primary_c'));

            //Show index page:
            $this->load->view('custom/shared/f_header' , array(
                'title' => 'Terms & Privacy Policy',
            ));
            $this->load->view('custom/featured_intentions');
            $this->load->view('custom/shared/f_footer');

        }
    }


	function login(){
	    //Check to see if they are already logged in?
	    $udata = $this->session->userdata('user');
	    if(isset($udata['u__inbounds']) && array_any_key_exists(array(1308,1281),$udata['u__inbounds'])){
	        //Lead coach and above, go to console:
	        redirect_message('/intents/'.$this->config->item('primary_c'));
	    }
	    
		$this->load->view('custom/shared/f_header' , array(
		    'title' => 'Login',
		));
		$this->load->view('custom/login');
		$this->load->view('custom/shared/f_footer');
	}


	function terms(){
		$this->load->view('custom/shared/f_header' , array(
		    'title' => 'Terms & Privacy Policy',
		));
		$this->load->view('custom/terms');
		$this->load->view('custom/shared/f_footer');
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
            'landing_page' => 'custom/splash/coaches_why',
        );
	    $this->load->view('custom/shared/f_header' , $data);
	    $this->load->view('custom/train' , $data);
	    $this->load->view('custom/shared/f_footer');
	}


}
