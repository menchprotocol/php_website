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
	
	function index(){
		$this->load_wiki();
	}
	
	function test(){
		$this->load->view('us/test');
	}
	
	function load_wiki($file_name= 'usoverview'){
		//For quick static pages
		//Also edit config/routes.php to load Wiki!
		$wiki_index = array(
			'login' => 'US Login',
			'join' => 'Join US',
			'usoverview' => 'Us Humans Foundation',
			'collectiveai' => 'Collective AI',
			'patternnetwork' => 'Pattern Network',
		);
		//Load views
		$this->load->view('shared/header' , array( 'title' => $wiki_index[$file_name] ));
		$this->load->view('wiki/'.$file_name);
		$this->load->view('shared/footer');
	}
	
	
	function load_node($node_id){
		//The main function for loading nodes
		//TODO: Design platform to be more open and accessible without login
		auth();
		
		//Build data sets for our views:
		$data_set = array(
			'node' 		 => $this->Us_model->fetch_node($node_id),
			'child_data' => $this->Us_model->fetch_node($node_id, 'fetch_children'),
		);
		//print_r($data_set);exit;
		
		//Load views:
		$this->load->view('shared/header', $data_set);
		$this->load->view('us/load_node', $data_set);
		$this->load->view('shared/footer', $data_set);
	}
	
	
	function logout() {
		//Destroy all sessions:
		$this->session->unset_userdata('user');
		//Redirect:
		redirect_message('/','<div class="alert alert-success" role="alert">Logout successful. See you soon &#128536;</div>');
	}
	
	function login_process() {
		
		if(!isset($_POST['user_email']) || !filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)){
			//Invalid email:
			redirect_message('/login','<div class="alert alert-danger" role="alert">Invalid email address.</div>');
		} elseif(!isset($_POST['user_pass']) || strlen($_POST['user_pass'])<2){
			//Invalid password:
			redirect_message('/login','<div class="alert alert-danger" role="alert">Invalid password.</div>'); 
		} else {
			//Fetch user nodes with this email:
			//TODO We can wire this in Agolia for faster string search!
			$matching_users = $this->Us_model->search_node($_POST['user_email'],24);
			
			if(count($matching_users)<1){
				//We could not find this email linked to the email node
				redirect_message('/login','<div class="alert alert-danger" role="alert">Email "'.$_POST['user_email'].'" not found.</div>');
			}
			
			//Now fetch entire user node:
			$user_node = $this->Us_model->fetch_node($matching_users[0]['node_id']);
			
			if($user_node[0]['grandpa_id']!=1){
				//We could not find this email linked to the email node
				//TODO This should technically never happen!
				redirect_message('/login','<div class="alert alert-danger" role="alert">Email not associated to a valid user.</div>');
			}
			
			//Now lets see if this user has a login password and if it matches the entered password
			$has_password = false;
			foreach($user_node as $link){
				if($link['parent_id']==44){
					//TODO: We should prevent duplicate password relations to be created
					//Yes they have a password link attached to the user node!
					$has_password = true;
					
					//Does it match login form entry?
					$matched_password = ($link['value']==sha1($_POST['user_pass']));
					
					break;
				}
			}
			
			if(!$has_password){
				//We could not find this password linked to anyone!
				redirect_message('/login','<div class="alert alert-danger" role="alert">A login password has not been assigned to your account.</div>');
			} elseif(!$matched_password){
				//Invalid 
				redirect_message('/login','<div class="alert alert-danger" role="alert">Invalid password for "'.$_POST['user_email'].'".</div>');
			} else {
				
				//Good to go!
				//Assign session variables:
				$matching_users[0]['login_timestamp'] = time();
				$this->session->set_userdata(array(
					'user' => $matching_users[0],
				));
				
				//Log Login history
				//TODO: Enable later. Disabled due to required UI adjustmens!
				/*
				$this->Us_model->insert_link(array(
					'us_id' => $matching_users[0]['node_id'],
					'timestamp' => date("Y-m-d H:i:s"),
					'status' => 2,
					'node_id' => $matching_users[0]['node_id'],
					'grandpa_id' => 43, //System
					'parent_id' => 61, //The login history node
				));
				*/
				
				//Redirect to pattern home page:
				if(isset($_POST['login_node_id']) && intval($_POST['login_node_id'])>0){
					//This is page the user was trying to access when their failed authentication:
					header("Location: /".intval($_POST['login_node_id']));
				} else {
					//Send user to default starting node post-login:
					header("Location: /1"); //Us node for now
				}
				exit;
			}
		}
	}
}
