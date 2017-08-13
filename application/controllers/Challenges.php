<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Challenges extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	/* ******************************
	 * Standalone Pages
	 ****************************** */
	
	function index(){
		//Redirect for our secondary domains:
		if(in_array($_SERVER['HTTP_HOST'],array('us.foundation','brainplugins.com','askmench.com','mench.ai'))){
			header("Location: http://mench.co");
		}
		
		//Load home page:
		$this->load->view('shared/z_header' , array(
				'landing_page' => 'standalone/landing_pages/home1',
				'title' => 'Mench | Group Challenges for Entrepreneurs',
		));
		$this->load->view('standalone/home_page');
		$this->load->view('shared/z_footer');
	}
	
	function terms(){
		//Load views
		$this->load->view('shared/z_header' , array(
				'title' => 'Terms of Service & Privacy Policy',
		));
		$this->load->view('standalone/terms');
		$this->load->view('shared/z_footer');
	}
	
	/* ******************************
	 * Buyer Challenge Pages
	 ****************************** */
	function public_list(){
		//Load views
		$this->load->view('shared/z_header' , array(
				'title' => 'Browse Challenges',
		));
		$this->load->view('challenges/public_list');
		$this->load->view('shared/z_footer');
	}
	
	function view(){
		//Fetch challenge from DB:
		$cdata = array(); //TODO replace
		
		//Load views
		$this->load->view('shared/z_header' , array(
				'title' => '', //TODO replace
		));
		$this->load->view('challenges/view' , array(
				'cdata' => $cdata,
		));
		$this->load->view('shared/z_footer');
	}
	
	/* ******************************
	 * Seller Challenge Pages
	 ****************************** */
	
	function dashboard(){
		//The landing page that explains the benefits of challenges to sellers:
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	function launch(){
		//The landing page that explains the benefits of challenges to sellers:
		$this->load->view('shared/z_header' , array(
				'title' => 'Launch a Challenge',
		));
		$this->load->view('challenges/sellers/launch');
		$this->load->view('shared/z_footer');
	}
	
	function activity(){
		//The landing page that explains the benefits of challenges to sellers:
		$this->load->view('shared/z_header' , array(
				'title' => 'Challenge Activity History | ',
		));
		$this->load->view('challenges/sellers/activity');
		$this->load->view('shared/z_footer');
	}
	
	function modify($step,$url_key=null){
		$wizard = array(
				'overview' 	=> 'Start New Challenge', //Do not change key!
				'sprints' 	=> 'Set Sprints',
				'insights' 	=> 'Sprint Insights',
				'pricing' 	=> 'Pricing & Coupons',
				'timeline' 	=> 'timelines',
				'publish' 	=> 'Review & Publish',
		);
		if(!$url_key || !array_key_exists($step,$wizard)){
			$step= 'overview';
		}
		
		$cdata = array();
		if($url_key){
			//Fetch existing data to load into view:
			
		}
		
		//Load views
		$this->load->view('shared/z_header' , array(
				'title' => $wizard[$step],
		));
		$this->load->view('challenges/modify/'.$step , array(
				'cdata' => $cdata,
		));
		$this->load->view('shared/z_footer');
	}
	
	
	
}