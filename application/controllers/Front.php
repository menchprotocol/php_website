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
				'title' => 'Run Online Challenges.',
		));
		$this->load->view('front/index');
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
	
	function pricing(){
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Pricing',
		));
		$this->load->view('front/pricing');
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
	
	function missing_access(){
		//Informs the user that they cannot access that page:
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Missing Access'.( isset($_GET['url']) ? ' for '.$_GET['url'] : '' ),
		));
		$this->load->view('front/missing_access');
		$this->load->view('front/shared/f_footer');
	}
	
	function signup_pending(){
		auth(1,1);
		//Inform them that they have a basic account:
		$this->load->view('front/shared/f_header' , array(
				'title' => 'Signup Pending'.( isset($_GET['url']) ? ' for '.$_GET['url'] : '' ),
		));
		$this->load->view('front/signup_pending');
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