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
	
	function load_wiki($file_name= 'usoverview'){
		//For quick static pages
		//Also edit config/routes.php to load Wiki!
		$wiki_index = array(
			'login' => 'US Login',
			'terms' => 'Our Commitments',
			'signup' => 'Signup',
			'usoverview' => 'Us | Intelligence Assistant',
		);
		if(in_array($file_name,array('login','join','usoverview')) && auth(1)){
			//Redirect to Us:
			header("Location: /1");
		}
		//Load views
		$this->load->view('shared/header' , array( 'title' => $wiki_index[$file_name] ));
		$this->load->view('wiki/'.$file_name);
		$this->load->view('shared/footer');
	}
	
	function fetch_full_node($node_id){
		header('Content-Type: application/json');
		echo json_encode($this->Us_model->fetch_full_node($node_id));
	}
	
	
	//The main function for loading nodes:
	function load_node($node_id){
		
		//While we're building:
		auth();
		
		//Load custom node functions for possible processing:
		//TODO automate the loading of these
		$this->load->helper('node/25');
		$this->load->helper('node/27');
		
		
		//Build data sets for our views:
		$data_set = array(
			'node' => $this->Us_model->fetch_full_node($node_id),
		);
		
		//print_r($data_set);exit;
		
		if($data_set['node'][0]['id']<1){
			//We did not find this ID:
			redirect_message('/1','<div class="alert alert-danger" role="alert"><b>||'.$node_id.'</b> has no active Gems.</div>');
		}
		
		//See if we have a description:
		$meta_data = '<link rel="canonical" href="//us.foundation/'.$node_id.'" />';
		foreach($data_set['node'] as $key=>$value){
			if($value['parent_id']==45){
				$meta_data .= "\n\t".'<meta name="description" content="'.$value['value'].'" />';
			}
			if($value['grandpa_id']==1 && !($value['parent_id']==$node_id)){
				$meta_data .= "\n\t".'<meta name="author" content="'.$value['parents'][0]['value'].'" />';
			}
		}
		
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
		echo phpinfo();
	}
	
	function logout() {
		//Destroy all sessions:
		$this->session->unset_userdata('user');
		//Redirect:
		redirect_message('/','<div class="alert alert-success" role="alert">Logout successful. See you soon &#128536;</div>');
	}
	
	function login_process() {
		$res = user_login($_POST['user_email'],$_POST['user_pass']);
		
		if($res['status']){
			//Yes!, Set session and redirect:
			$this->session->set_userdata(array(
				'user' => $res['link'], //This has the user's top link data
			));
			
			//Redirect to pattern home page:
			if(isset($_POST['login_node_id']) && intval($_POST['login_node_id'])>0){
				//This is page the user was trying to access when their failed authentication:
				header("Location: /".intval($_POST['login_node_id']));
			} else {
				//Send user to default starting node post-login:
				header("Location: /1"); //Us node for now
			}
		} else {
			//Ooops, some sort of error! Let them know:
			redirect_message('/login','<div class="alert alert-danger" role="alert">'.$res['message'].'</div>');
		}
	}
}
