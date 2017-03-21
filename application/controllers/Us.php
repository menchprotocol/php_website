<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Us extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}

	
	
	function under_construction() {
		//Current default view
		$this->load->view('shared/header' , array( 'title' => 'US' ));
		$this->load->view('landing/under_construction');
		$this->load->view('shared/footer');
	}
	
	function login() {
		$this->load->view('shared/header' , array( 'title' => 'US Login' ));
		$this->load->view('us/login');
		$this->load->view('shared/footer');
	}
	function logout() {
		//Destroy all sessions:
		$this->session->unset_userdata('user');
		
		//Redirect:
		redirect_message('/','<div class="alert alert-success" role="alert">Logout successful.</div>');
	}
	
	
	
	function login_process() {
		if(!isset($_POST['user_email']) || !filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)){
			//Invalid email:
			redirect_message('/us/login','<div class="alert alert-warning" role="alert">Invalid email address.</div>');
		} elseif(!isset($_POST['user_pass']) || strlen($_POST['user_pass'])<2){
			//Invalid password:
			redirect_message('/us/login','<div class="alert alert-warning" role="alert">Invalid password.</div>'); 
		} else {
			//Seems like valid input, lets validate with databases:
			$user_data =  $this->Us_model->validate_user($_POST['user_email'],$_POST['user_pass']);
			if(!isset($user_data['id']) || intval($user_data['id'])<1){
				//Validation failed:
				redirect_message('/us/login','<div class="alert alert-warning" role="alert">Invalid email/password combination.</div>');
			} else {
				//Good to go!
				//Assign session variables:
				$user_data['login_timestamp'] = time();
				$this->session->set_userdata(array(
					'user' => $user_data,
				));
				
				//TODO: Log login pattern
				
				//Redirect to pattern home page:
				header("Location: /patterns");
				exit;
			}
		}
	}
	
	
	function index() {
		auth();
		$top_users = $this->Us_model->fetch_top_users();
		$this->load->view('shared/header' , array( 'title' => 'US' ));
		$this->load->view('us/leaderboard' , array( 'top_users' => $top_users ));
		$this->load->view('shared/footer');
	}
	
	
	function load_profile($username){
		auth();
		$top_users = $this->Us_model->fetch_top_users();
		$this->load->view('shared/header' , array( 'title' => 'US' ));
		$this->load->view('us/leaderboard' , array( 'top_users' => $top_users ));
		$this->load->view('shared/footer');
	}
	
	
	function add(){
		auth();
		if(!isset($_GET['hashtagName']) || strlen($_GET['hashtagName'])<1){
			//No proper hashtag name defined!
			if(isset($_GET['hashtagChildId'])){
				header("Location: /".$_GET['hashtagChildId']);
			} elseif(isset($_GET['hashtagParentId'])) {
				header("Location: /".$_GET['hashtagParentId']);
			} else {
				die('Invalid Data, nothing I can do!');
			}
		}
		
		//Check to make sure this hashtag is not a duplicate:
		
		
		//insert the new hashtag after making sure it's not duplicate:
		
		
		
		//This function would simply create a parent OR child hashtag, and link it to the origin:
		if(isset($_GET['hashtagChildId'])){
			//Create parent hashtag
		} elseif(isset($_GET['hashtagParentId'])) {
			//Create child hashtag
		}
	}
}
