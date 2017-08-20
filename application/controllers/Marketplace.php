<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	function index(){
		die('');
	}
	
	/* ******************************
	 * Admin Guides
	 ****************************** */
	
	function status_bible(){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(1,1);
		
		//Load views
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Guides | Status Bible',
		));
		$this->load->view('marketplace/guides/status_bible');
		$this->load->view('marketplace/shared/d_footer');
	}
	
	function showdown_markup(){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(1,1);
		
		//Load views
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Guides | Showdown Markup Syntax',
		));
		$this->load->view('marketplace/guides/showdown_markup');
		$this->load->view('marketplace/shared/d_footer');
	}
	
	
	
	/* ******************************
	 * Users & Authentication
	 ****************************** */

	function login(){
		//Called via AJAX to validate the user's Facebook login status and assign session variable:
		if(isset($_POST['response']['authResponse']) && $_POST['response']['status']=='connected'){
			
			//User is logged in on Facebook. Let's check their local profile:
			$res = $_POST['response']['authResponse'];
			$users = $this->Db_model->users_fetch(array(
					'u_fb_id' => $res['userID']
			));
			
			//Were they already registered?
			if(count($users)==0){
				
				//Fetch user profile from Facebook:
				$profile = $this->Facebook_model->fetch_profile($res['userID']);
				
				//This is a new user! Create their account:
				$name = explode(' ',$profile['name'],2);
				$udata = $this->Db_model->user_create(array(
						'u_fb_id' => $res['userID'],
						'u_fb_token' => $res['accessToken'],
						'u_fname' => $name[0],
						'u_lname' => $name[1],
						'u_url_key' => preg_replace("/[^a-z0-9]/", '', strtolower($profile['name'])),
				));
				
			} elseif(count($users)==1){
				
				//Found this user:
				$udata = $users[0];
				
				//Check to see if access token has been updated:
				if(!($res['accessToken']==$udata['u_fb_token']) || !($res['userID']==$udata['u_fb_id'])){
					//Let's update:
					$udata = $this->Db_model->user_update( $udata['u_id'] , array(
							'u_fb_id' => $res['userID'],
							'u_fb_token' => $res['accessToken'],
					));
				}
				
			} else {
				
				//Ooops, this should never happen!
				ping_admin('Found multiple users with the same Facebook ID ['.$res['userID'].']');
				
			}
			
			//Assign session and login:
			if(isset($udata)){
				//Set session:
				$this->session->set_userdata(array(
						'user' => $udata, //This has the user's top link data
						'challenges' => $this->Db_model->c_ses_fetch($udata),
				));
				
				//Display user data:
				header('Content-Type: application/json');
				echo json_encode($udata);
			}
			
		} else {
			//Ooops, they do not seem to be logged in!
			die('Missing parameters for login');
		}
		//print_r($_POST['response']);
	}
	
	function logout(){
		//Called via AJAX to destroy user session:
		$this->session->sess_destroy();
	}
	
	function user_view($c_id,$user_id){
		//The activity stream of a challenge
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'My Profile',
		));
		$this->load->view('marketplace/users/edit_account');
		$this->load->view('marketplace/shared/d_footer');
	}
	
	function user_edit($url_key){
		//Loads a single user profile for everyone to see.
		$udata = array();
		
		//This lists all users based on the permissions of the user
		$this->load->view('shared/z_header' , array(
				'title' => '',
		));
		$this->load->view('users/public_account' , array(
				'udata' => $udata,
		));
		$this->load->view('shared/z_footer');
	}
	
	
	
	/* ******************************
	 * Challenges
	 ****************************** */
	
	function challenge_marketplace(){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Challenge Marketplace',
		));
		$this->load->view('marketplace/challenge/challenge_marketplace');
		$this->load->view('marketplace/shared/d_footer');
	}
	
	function challenge_overview($c_url_key){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//Fetch Challenge:
		$fetch_challenge = $this->Db_model->c_fetch(array(
				'c.c_url_key' => $c_url_key,
		));
		
		//Valid challenge key?
		if(count($fetch_challenge)<1){
			redirect_message('/marketplace','<div class="alert alert-danger" role="alert">Invalid Challege Key ['.$c_url_key.']. Select another challenge to continue.</div>');
			return false;
		}
		
		//This should always be one because url_key is unique:
		$challenge = $fetch_challenge[0];
		
		//Append more data:
		$challenge['runs'] = $this->Db_model->r_fetch(array(
				'r.r_c_id' => $challenge['c_id'],
				'r.r_status >=' => 1,
				'r.r_status <=' => 3,
		));
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => $challenge['c_objective'],
				'challenge' => $challenge,
		));
		$this->load->view('marketplace/challenge/challenge_overview' , array(
				'challenge' => $challenge,
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	function challenge_create(){
		//New Challenge
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'New Challenge',
		));
		$this->load->view('marketplace/challenges/challenge_setting');
		$this->load->view('marketplace/shared/d_footer');
	}
	
	function challenge_settings($c_url_key){
		//The dashboard of a given challenge
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'New Challenge',
		));
		$this->load->view('marketplace/challenges/challenge_setting' , array(
				'challenge' => array(), //TODO Fetch
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	function challenge_library($c_url_key,$pid=null){
		$c = $c_url_key;
		if(!$pid){
			$pid = $c['id'];
		}
		//The dashboard of a given challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/marketplace');
		$this->load->view('shared/z_footer');
	}
	
	
	
	/* ******************************
	 * Runs
	 ****************************** */
	
	function run_dashboard($c_url_key,$r_version){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//Fetch & Validate Challenge:
		$fetch_challenge = $this->Db_model->c_fetch(array(
				'c.c_url_key' => $c_url_key,
		));
		if(count($fetch_challenge)<1){
			redirect_message('/marketplace','<div class="alert alert-danger" role="alert">Invalid Challege Key ['.$c_url_key.']. Select another challenge to continue.</div>');
			return false;
		}
		
		//This should always be one because url_key is unique:
		$challenge = $fetch_challenge[0];
		
		//Append more data:
		$challenge['runs'] = $this->Db_model->r_fetch(array(
				'r.r_c_id' => $challenge['c_id'],
				'r.r_status >=' => 1,
				'r.r_status <=' => 3,
		));
		
		//Fetch & Validate Run:
		$run = filter($challenge['runs'],'r_version',$r_version);
		if(!$run){
			redirect_message('/marketplace/'.$c_url_key,'<div class="alert alert-danger" role="alert">Invalid Challege Key ['.$c_url_key.']. Select another challenge to continue.</div>');
			return false;
		}
		
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Run #'.$r_version.' | '.$challenge['c_objective'],
				'challenge' => $challenge,
				'run' => $run,
		));
		$this->load->view('marketplace/run/run_dashboard' , array(
				'challenge' => $challenge,
				'run' => $run,
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	
	function run_leaderboard($c_url_key,$r_version){
		//The activity stream of a challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Challenge Activity History | ',
		));
		$this->load->view('challenges/sellers/activity');
		$this->load->view('shared/z_footer');
	}
	
	function run_timeline($c_url_key,$r_version){
		//The notifications stream of all activities done in a challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Challenge Activity History | ',
		));
		$this->load->view('challenges/sellers/activity');
		$this->load->view('shared/z_footer');
	}
	
	//challenge modification wizard:
	function run_settings($c_url_key,$r_version){
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