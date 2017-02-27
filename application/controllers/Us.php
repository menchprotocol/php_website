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

	
	
	function index() {
		$this->load->model('Us_model');
		$top_users = $this->Us_model->fetch_top_users();
		$this->load->view('shared/header' , array( 'title' => 'US' ));
		$this->load->view('us/leaderboard' , array( 'top_users' => $top_users ));
		$this->load->view('shared/footer');
	}
	
	
	function load_profile($username){
		$this->load->model('Us_model');
		$top_users = $this->Us_model->fetch_top_users();
		$this->load->view('shared/header' , array( 'title' => 'US' ));
		$this->load->view('us/leaderboard' , array( 'top_users' => $top_users ));
		$this->load->view('shared/footer');
	}
	
	function signup() {
		$this->load->view('misc/mailchimp_signup');
	}
	
	
	function template(){
		//Only used to see sample code base for UI design
		$this->load->view('misc/template');
	}
	
	
	function add(){
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
	
	function node($hashtag){
		
		//Hashtag is a mask URL that looks prettier than an ID
		//Find the Node ID based on input hashtag:
		$nodes = $this->Us_model->search_node($hashtag,0,'none');
		//print_r($nodes);die();
		if(count($nodes)>0){
			//We found this node!
			//Does it match exactly?
			if($hashtag!==$nodes[0]['value_string']){
				header("Location: /".$nodes[0]['value_string']);
			}
			
			//lets load the node:
			$node_data = $this->Us_model->fetch_node_content($nodes[0]['node_id']);
			$this->load->view('node_v1' , array( 'nds' => $node_data, 'node_id' => $nodes[0]['node_id'] ));
			
		} else {
			//Oops, nothing found, lets give error and redirect to default node:
			$this->session->set_flashdata('html_message', '<div class="editable-error-block"><b>#'.$hashtag.'</b> not found! Welcome to:</div>');
			header("Location: /".default_start());
		}
	}
}
