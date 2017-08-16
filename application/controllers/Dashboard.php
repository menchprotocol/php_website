<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	
	function index(){
		die('Nothing here...');
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
					'fb_id' => $res['userID']
			));
			
			//Were they already registered?
			if(count($users)==0){
				
				//Fetch user profile from Facebook:
				$profile = $this->Facebook_model->fetch_profile($res['userID']);
				
				//This is a new user! Create their account:
				$name = explode(' ',$profile['name'],2);
				$user = $this->Db_model->user_create(array(
						'fb_id' => $res['userID'],
						'fb_token' => $res['accessToken'],
						'first_name' => $name[0],
						'last_name' => $name[1],
						'user_name' => preg_replace("/[^a-z0-9]/", '', strtolower($profile['name'])),
				));
				
			} elseif(count($users)==1){
				
				//Found this user:
				$user = $users[0];
				
				//Check to see if access token has been updated:
				if(!($res['accessToken']==$user['fb_token']) || !($res['userID']==$user['fb_id'])){
					//Let's update:
					$user = $this->Db_model->user_update( $user['id'] , array(
							'fb_id' => $res['userID'],
							'fb_token' => $res['accessToken'],
					));
				}
				
			} else {
				
				//Ooops, this should never happen!
				ping_admin('Found multiple users with the same Facebook ID ['.$res['userID'].']');
				
			}
			
			//Assign session and login:
			if(isset($user)){
				//Set session:
				$this->session->set_userdata(array(
						'user' => $user, //This has the user's top link data
				));
				
				//Display user data:
				header('Content-Type: application/json');
				echo json_encode($user);
			}
			
		} else {
			//Ooops, they do not seem to be logged in!
		}
		//print_r($_POST['response']);
	}
	
	function logout(){
		//Called via AJAX to destroy user session:
		$this->session->unset_userdata('user');
	}
	
	function user_view($challenge_id,$user_id){
		//The activity stream of a challenge
		$this->load->view('dashboard/shared/d_header' , array(
				'title' => 'My Profile',
		));
		$this->load->view('dashboard/users/edit_account');
		$this->load->view('dashboard/shared/d_footer');
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
	 * Challenges PARTNERS
	 ****************************** */
	
	function index(){
		//List all challenges to choose from
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	function challenge_settings($challenge_key){
		//The dashboard of a given challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	function challenge_library($challenge_key,$pid=null){
		$c = $challenge_key;
		if(!$pid){
			$pid = $c['id'];
		}
		//The dashboard of a given challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	
	/* ******************************
	 * Challenges PUBLIC
	 ****************************** */
	
	function challenge_insights_overview($challenge_key){
		//The dashboard of a given challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	function challenge_join($challenge_key){
		//The dashboard of a given challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	function challenge_landing_page($challenge_key){
		//The dashboard of a given challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	
	
	/* ******************************
	 * Runs
	 ****************************** */
	
	function run_list($challenge_key){
		//The dashboard of a given challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	function run_dashboard($challenge_key,$run_num){
		//The dashboard of a given challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Seller Dashboard',
		));
		$this->load->view('challenges/sellers/dashboard');
		$this->load->view('shared/z_footer');
	}
	
	
	function run_leaderboard($challenge_key,$run_num){
		//The activity stream of a challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Challenge Activity History | ',
		));
		$this->load->view('challenges/sellers/activity');
		$this->load->view('shared/z_footer');
	}
	
	function run_stream($challenge_key,$run_num){
		//The notifications stream of all activities done in a challenge
		$this->load->view('shared/z_header' , array(
				'title' => 'Challenge Activity History | ',
		));
		$this->load->view('challenges/sellers/activity');
		$this->load->view('shared/z_footer');
	}
	
	//challenge modification wizard:
	function run_settings($challenge_key,$run_num){
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