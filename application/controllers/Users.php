<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	function ses(){
		print_r($this->session->all_userdata());
	}
	
	function account(){
		//Load views
		$this->load->view('shared/z_header' , array(
				'title' => 'Manage My Account',
		));
		$this->load->view('users/edit_account');
		$this->load->view('shared/z_footer');
	}
	
	function browse(){
		//This lists all users based on the permissions of the user
		$this->load->view('shared/z_header' , array(
				'title' => 'Browse Users',
		));
		$this->load->view('users/custom_list');
		$this->load->view('shared/z_footer');
	}
	
	function load($url_key){
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
	 * Authentication functions
	 ****************************** */
	
	function logout(){
		//Called via JS, Destroy user session object:
		$this->session->unset_userdata('user');
	}
	
	function login_auth(){
		//Called to validate the user's Facebook login status and assign session variable:
		if(isset($_POST['response']['authResponse']) && $_POST['response']['status']=='connected'){
			
			//User is logged in on Facebook. Let's check their local profile:
			$res = $_POST['response']['authResponse'];
			$users = $this->Db_model->u_fetch(array(
					'fb_id' => $res['userID']
			));
			
			//Were they already registered?
			if(count($users)==0){
				
				//Fetch user profile from Facebook:
				$profile = $this->Facebook_model->fetch_profile($res['userID']);
				
				//This is a new user! Create their account:
				$name = explode(' ',$profile['name'],2);
				$user = $this->Db_model->u_create(array(
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
				    $user = $this->Db_model->u_update( $user['id'] , array(
							'fb_id' => $res['userID'],
							'fb_token' => $res['accessToken'],
					));
				}
				
			} else {
				
				//Ooops, this should never happen!
			    $this->Db_model->e_create(array(
			        'e_message' => 'login_auth() matched multiple users with the same Facebook ID ['.$res['userID'].']',
			        'e_json' => json_encode($_POST),
			        'e_type_id' => 8, //Platform Error
			    ));
				
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
}