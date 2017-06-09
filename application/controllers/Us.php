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
		if(auth(1)){
			//Load default search page:
			$this->load_wiki();
		} else {
			//Load home page for visitors:
			$this->load_wiki('usoverview');
		}
	}
	
	function load_wiki($file_name='start'){
		//For quick static pages
		//Also edit config/routes.php to load Wiki!
		$wiki_index = array(
				'start' => 'Start...',
				'login' => 'Foundation Login',
				'terms' => 'Terms of Service',
				'signup' => 'Foundation Signup',
				'usoverview' => 'Us Foundation | Grow, Faster.',
		);
		
		if(in_array($file_name,array('login','join','usoverview')) && auth(1)){
			//Redirect to Us:
			header("Location: /");
		}
				
		//Load views
		$this->load->view('shared/header' , array(
				'title' => $wiki_index[$file_name],
				'view' => $file_name,
		));
		$this->load->view('wiki/'.$file_name);
		$this->load->view('shared/footer');
	}
	
	
	function fetch_full_node($node_id){
		header('Content-Type: application/json');
		echo json_encode($this->Us_model->fetch_full_node($node_id));
	}
	
	
	//The main function for loading nodes:
	function load_node($node_id){
		
		//Require authentication:
		auth();
		
		
		//Build data sets for our views:
		$data_set = array(
				'node' => $this->Us_model->fetch_full_node($node_id),
		);
		
		//Make sure it was valid:	
		if($data_set['node'][0]['id']<1){
			//We did not find this ID:
			redirect_message('/','<div class="alert alert-danger" role="alert"><b>||'.$node_id.'</b> has no active Gems.</div>');
		}
		
		//Log engagement:
		$eng = $this->Us_model->log_engagement(array(
				'gem_id' => $data_set['node'][0]['id'],
				'platform_pid' => 766, //766 Us, 765 FB, 763 api.ai
				'action_pid' => 928, //928 Read, 929 Write, 930 Subscribe, 931 Unsubscribe
				'intent_pid' => $data_set['node'][0]['node_id'],
				'json_blob' => trim(json_encode($data_set['node'])),
		));
		
		//Load custom node functions for possible processing:
		//TODO automate the loading of these
		$this->load->helper('node/25');
		$this->load->helper('node/27');
		
		
		//See if we have a description:
		$meta_data = '<link rel="canonical" href="//us.foundation/'.$node_id.'" />';
		
		//Create header variables:
		$header_data = array(
			'show_grandpas' => 1, //To force show the navigation for guests
			'meta_data' => $meta_data, //SEO optimizations
		);
		
		//Load views:
		$this->load->view('shared/header', array_merge($data_set,$header_data));
		$this->load->view('us/load_node', $data_set);
		$this->load->view('shared/footer', $data_set);
	}
	
	function info(){
		echo session_id()."<br /><hr /><br />";
		print_r($this->session->all_userdata())."<br /><hr /><br />";
		echo phpinfo();
	}
	
	function logout() {
		//Log engagement:
		$eng = $this->Us_model->log_engagement(array(
				'platform_pid' => 766, //766 Us, 765 FB, 763 api.ai
				'action_pid' => 928, //928 Read, 929 Write, 930 Subscribe, 931 Unsubscribe
				'intent_pid' => 843, //Logout intent
		));
		
		//Destroy all sessions:
		$this->session->unset_userdata('user');
		
		//Redirect:
		redirect_message('/','<div class="alert alert-success" role="alert">Logout successful. See you soon &#128536;</div>');
	}
	
	function login_process() {
		$res = user_login($_POST['user_email'],$_POST['user_pass']);
		
		if($res['status']){
			
			$seq = 1; //This is for their login sequence which is always the first one
			$time = time();
			$session_id = md5($time.$seq.print_r($res['link'],true));
			
			//Yes!, Set session and redirect:
			$this->session->set_userdata(array(
					'user' => $res['link'], //This has the user's top link data
					'seq' => $seq,
					'login_time' => $time,
					'ses_id' => $session_id,
			));
			
			//Log engagement:
			$eng = $this->Us_model->log_engagement(array(
					'platform_pid' => 766, //766 Us, 765 FB, 763 api.ai
					'action_pid' => 928, //928 Read, 929 Write, 930 Subscribe, 931 Unsubscribe
					'intent_pid' => 44, //Login password pattern, which indicates login
					'seq' => $seq,
					'session_id' => $session_id,
			));			
			
			//Redirect to pattern home page:
			if(isset($_POST['login_node_id']) && intval($_POST['login_node_id'])>0){
				//This is page the user was trying to access when their failed authentication:
				header("Location: /".intval($_POST['login_node_id']));
			} else {
				//Send user to default starting node post-login:
				header("Location: /"); //Default
			}
			
		} else {
			//Ooops, some sort of error! Let them know:
			redirect_message('/login','<div class="alert alert-danger" role="alert">'.$res['message'].'</div>');
		}
	}
}
