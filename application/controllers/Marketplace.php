<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marketplace extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	function index(){
		die('nothing here...');
	}
	
	/* ******************************
	 * Crons
	 ****************************** */
	
	function algolia($pid=null){
		//Used to update local host:
		print_r($this->Db_model->sync_algolia($pid));
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
	
	function login_process(){
		
		if(!isset($_POST['u_email']) || !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){
			redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
		} elseif(!isset($_POST['u_password'])){
			redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
		}
		
		//Fetch user data:
		$users = $this->Db_model->users_fetch(array(
				'u_email' => strtolower($_POST['u_email']),
				'u_status >=' => 2,
		));
		
		if(count($users)==0){
			//Not found!
			redirect_message('/login','<div class="alert alert-danger" role="alert">Error: '.$_POST['u_email'].' not registered as a partner.</div>');
		} elseif(!($users[0]['u_password']==md5($_POST['u_password']))){
			//Bad password
			redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Incorrect password for '.$_POST['u_email'].'.</div>');
		} else {
			//All good to go!
			//Load session and redirect:
			$this->session->set_userdata(array(
					'user' => $users[0],
			));
			
			if(isset($_POST['url']) && strlen($_POST['url'])>0){
				header( 'Location: '.$_POST['url'] );
			} else {
				//Default:
				header( 'Location: /marketplace' );
			}
		}
	}
	
	function logout(){
		//Called via AJAX to destroy user session:
		$this->session->sess_destroy();
		header( 'Location: /' );
	}
	
	function user_view($u_url_key){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//Fetch user:
		$view_data['user'] = load_object('u' , array(
				'u.u_url_key' => $u_url_key,
		));
		//Append title:
		$view_data['title'] = $view_data['user']['u_fname'].' '.$view_data['user']['u_lname'];
		
		//This lists all users based on the permissions of the user
		$this->load->view('marketplace/shared/d_header', $view_data);
		$this->load->view('marketplace/user/user_view', $view_data);
		$this->load->view('marketplace/shared/d_footer');
	}
	
	
	function user_edit($u_url_key){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//Fetch user:
		$view_data['user'] = load_object('u' , array(
				'u.u_url_key' => $u_url_key,
				'u.u_status >=' => 1,
		));
		
		//Can this user edit?
		if(!can_modify('u',$view_data['user']['u_id'])){
			redirect_message('/user/'.$u_url_key,'<div class="alert alert-danger" role="alert">You do not have the permission to edit this profile.</div>');
		}
		
		//Append title:
		$view_data['title'] = 'Edit Profile | '.$view_data['user']['u_fname'].' '.$view_data['user']['u_lname'];
		
		//This lists all users based on the permissions of the user
		$this->load->view('marketplace/shared/d_header', $view_data);
		$this->load->view('marketplace/user/user_edit', $view_data);
		$this->load->view('marketplace/shared/d_footer');
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
		$this->load->view('marketplace/bootcamp/bootcamp_marketplace' , array(
				'challenges' => $this->Db_model->c_fetch(array(
				    'c.c_status >=' => 0,
					'c.c_is_grandpa' => true, //Not sub challenges
				)),
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	
	function bootcamp_wiki($c_id,$pid=null){
		
		$udata = auth(2,1);
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		$pid = ( isset($pid) && intval($pid)>0 ? $pid : $bootcamp['c_id'] );
		//Construct data:
		$view_data = array(
				'bootcamp' => $bootcamp,
				'pid' => $pid,
				'cr' => array(
						'c' => $this->Db_model->c_plain_fetch(array(
								'c.c_id' => $pid,
						)),
						'inbound' => $this->Db_model->cr_inbound_fetch(array(
								'cr.cr_outbound_id' => $pid,
								'cr.cr_status >=' => 0,
						)),
						'outbound' => $this->Db_model->cr_outbound_fetch(array(
								'cr.cr_inbound_id' => $pid,
								'cr.cr_status >=' => 0,
						)),
				),
				'i_messages' => $this->Db_model->i_fetch(array(
						'i_status >=' => 0,
						'i_c_id >=' => $pid,
				)),
		);
		
		//Valid challenge key?
		if(!isset($view_data['cr']['c']['c_id'])){
			redirect_message('/marketplace/'.$c_id.'/framework','<div class="alert alert-danger" role="alert">Invalid framework ID. Select another framework to continue.</div>');
		}
		
		//Append Title:
		$view_data['title'] = $view_data['cr']['c']['c_objective'];
		
		//Show View
		$this->load->view('marketplace/shared/d_header' , $view_data);
		$this->load->view('marketplace/bootcamp/bootcamp_wiki' , $view_data);
		$this->load->view('marketplace/shared/d_footer');
	}
	
	/* ******************************
	 * I/O Processing & Forms
	 ****************************** */
	
	function msg_create(){
		
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh to Continue.</span>');
		} elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid ID.</span>');
		} elseif(!isset($_POST['i_message']) || strlen($_POST['i_message'])<=0){
			die('<span style="color:#FF0000;">Error: Missing message.</span>');
		}
		
		//Fetch existing challenge:
		$f_challenge = load_object('c' , array(
				'c.c_id' => $_POST['pid'],
				'c.c_status >=' => 0,
		));
		
		//Create Link:
		$i = $this->Db_model->i_create(array(
				'i_creator_id' => $udata['u_id'],
				'i_c_id' => $f_challenge['c_id'],
				'i_message' => trim($_POST['i_message']),
				'i_rank' => 1 + $this->Db_model->max_value('v5_learning_media','i_rank', array(
						'i_status >=' => 0,
						'i_c_id' => $f_challenge['c_id'],
				)),
		));
		
		//TODO Integrate Message & Update Algolia:
		
		
		//Print the challenge:
		echo_message($i);
	}
	
	
	function challenge_create(){
		
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid c_id.</span>');
		} elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid ID.</span>');
		} elseif(!isset($_POST['direction']) || !in_array($_POST['direction'],array('outbound','inbound'))){
			die('<span style="color:#FF0000;">Error: Invalid direction.</span>');
		} elseif(!isset($_POST['c_objective']) || strlen($_POST['c_objective'])<=0){
			die('<span style="color:#FF0000;">Error: Missing name.</span>');
		}
		
		//Fetch existing challenge:
		$f_challenge = load_object('c' , array(
				'c.c_id' => $_POST['pid'],
				'c.c_status >=' => 0,
		));
		
		//Create challenge:
		$is_outbound = ($_POST['direction']=='outbound');
		$bootcamp = $this->Db_model->c_create(array(
				'c_creator_id' => $udata['u_id'],
				'c_objective' => trim($_POST['c_objective']),
		));
		
		//Create Link:
		$relation = $this->Db_model->cr_create(array(
				'cr_creator_id' => $udata['u_id'],
				
				//Linking:
				'cr_inbound_id'  => ( $is_outbound ? $f_challenge['c_id'] : $bootcamp['c_id'] ),
				'cr_outbound_id' => ( $is_outbound ? $bootcamp['c_id'] : $f_challenge['c_id'] ),
				
				//Fetch ranks:
				'cr_inbound_rank'  => 1 + $this->Db_model->max_value('v5_bootcamp_wiki','cr_inbound_rank', array(
						'cr_status >=' => 0,
						'cr_outbound_id' => $f_challenge['c_id'],
				)),
				'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_bootcamp_wiki','cr_outbound_rank', array(
						'cr_status >=' => 0,
						'cr_inbound_id' => $f_challenge['c_id'],
				)),
		));
		
		//Fetch full link package:
		if($is_outbound){
			$relations = $this->Db_model->cr_outbound_fetch(array(
					'cr.cr_id' => $relation['cr_id'],
			));
		} else {
			$relations = $this->Db_model->cr_inbound_fetch(array(
					'cr.cr_id' => $relation['cr_id'],
			));
		}
		
		//Update Algolia:
		$this->Db_model->sync_algolia($bootcamp['c_id']);
		
		//Return result:
		echo echo_cr($_POST['c_id'],$relations[0],$_POST['direction']);
	}
	
	
	function challenge_link(){
		
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid c_id.</span>');
		} elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid ID.</span>');
		} elseif(!isset($_POST['direction']) || !in_array($_POST['direction'],array('outbound','inbound'))){
			die('<span style="color:#FF0000;">Error: Invalid direction.</span>');
		} elseif(!isset($_POST['target_id']) || intval($_POST['target_id'])<=0){
			die('<span style="color:#FF0000;">Error: Missing target_id.</span>');
		}
		
		//Fetch existing challenge:
		$f_challenge = load_object('c' , array(
				'c.c_id' => $_POST['pid'],
				'c.c_status >=' => 0,
		));
		
		//Create challenge:
		$is_outbound = ($_POST['direction']=='outbound');
		$bootcamp = load_object('c' , array(
				'c.c_id' => $_POST['target_id'],
				'c.c_status >=' => 0,
		));
		
		//TODO Check to make sure not duplicate
		
		//Create Link:
		$relation = $this->Db_model->cr_create(array(
				'cr_creator_id' => $udata['u_id'],
				
				//Linking:
				'cr_inbound_id'  => ( $is_outbound ? $f_challenge['c_id'] : $bootcamp['c_id'] ),
				'cr_outbound_id' => ( $is_outbound ? $bootcamp['c_id'] : $f_challenge['c_id'] ),
				
				//Fetch ranks:
				'cr_inbound_rank'  => 1 + $this->Db_model->max_value('v5_bootcamp_wiki','cr_inbound_rank', array(
						'cr_status >=' => 0,
						'cr_outbound_id' => $f_challenge['c_id'],
				)),
				'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_bootcamp_wiki','cr_outbound_rank', array(
						'cr_status >=' => 0,
						'cr_inbound_id' => $f_challenge['c_id'],
				)),
		));
		
		//Fetch full link package:
		if($is_outbound){
			$relations = $this->Db_model->cr_outbound_fetch(array(
					'cr.cr_id' => $relation['cr_id'],
			));
		} else {
			$relations = $this->Db_model->cr_inbound_fetch(array(
					'cr.cr_id' => $relation['cr_id'],
			));
		}
		
		//Return result:
		echo echo_cr($_POST['c_id'],$relations[0],$_POST['direction']);
	}
	
	function delete_c($grandpa_id,$c_id){
		die('disabled for now');
		$udata = auth(2,1);
		$main_challenge = load_object('c' , array(
				'c.c_id' => $grandpa_id,
				'c.c_status >=' => 0,
		));
		$sub_challenge = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_status >=' => 0,
		));
		
		if(!can_modify('c',$c_id)){
			redirect_message('/marketplace/'.$grandpa_id.'/'.$c_id, '<div class="alert alert-danger" role="alert">You dont have the permission to delete this challenge.</div>');
		}
		
		//Delete links:
		$links_deleted = 0;
		$links_deleted += $this->Db_model->cr_update( intval($cr_id) , array(
				'cr_creator_id' => $udata['u_id'],
				'cr_timestamp' => date("Y-m-d H:i:s"),
				'cr_status' => -1,
		) , 'cr_inbound_id' );
		$links_deleted += $this->Db_model->cr_update( intval($cr_id) , array(
				'cr_creator_id' => $udata['u_id'],
				'cr_timestamp' => date("Y-m-d H:i:s"),
				'cr_status' => -1,
		) , 'cr_outbound_id' );
		
		
		//Delete challenge:
		$this->Db_model->c_update( intval($cr_id) , array(
				'c_creator_id' => $udata['u_id'],
				'c_timestamp' => date("Y-m-d H:i:s"),
				'c_status' => -1,
		));
		
		//Update Algolia:
		$this->Db_model->sync_algolia(intval($cr_id));
		
		//TODO Log activity
		
		//Redirect and show susccess message:
		redirect_message('/marketplace/'.$grandpa_id, '<div class="alert alert-success" role="alert">Challenge <b>'.$sub_challenge['c_objective'].'</b> deleted with '.$links_deleted.' dependencies.</div>');
	}
	
	
	function update_msg_sort(){
		//Auth user and Load object:
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort'])<=0){
			die('<span style="color:#FF0000;">Error: Nothing to sort.</span>');
		}
		
		//Update them all:
		foreach($_POST['new_sort'] as $i_rank=>$i_id){
			$this->Db_model->i_update( intval($i_id) , array(
					'i_creator_id' => $udata['u_id'],
					'i_timestamp' => date("Y-m-d H:i:s"),
					'i_rank' => intval($i_rank),
			));
		}
		
		//TODO Save change history
		echo '<span style="color:#00CC00;">'.(count($_POST['new_sort'])-1).' sorted</span>';
	}
	
	function update_sort(){
		//Auth user and Load object:
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['save_c_id']) || intval($_POST['save_c_id'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid ID.</span>');
		} elseif(!isset($_POST['sort_direction']) || !in_array($_POST['sort_direction'],array('outbound','inbound'))){
			die('<span style="color:#FF0000;">Error: Invalid sort direction.</span>');
		} elseif(!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort'])<=0){
			die('<span style="color:#FF0000;">Error: Nothing passed for sorting.</span>');
		}
		
		//Update them all:
		foreach($_POST['new_sort'] as $rank=>$cr_id){
			$this->Db_model->cr_update( intval($cr_id) , array(
					'cr_creator_id' => $udata['u_id'],
					'cr_timestamp' => date("Y-m-d H:i:s"),
					'cr_'.$_POST['sort_direction'].'_rank' => intval($rank),
			));
		}
		
		//TODO Save change history
		echo '<span style="color:#00CC00;">'.(count($_POST['new_sort'])-1).' sorted</span>';
	}
	
	
	function i_delete(){
		//Auth user and Load object:
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['i_id']) || intval($_POST['i_id'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid i_id.</span>');
		}
		
		//Now update the DB:
		$this->Db_model->i_update( intval($_POST['i_id']) , array(
				'i_creator_id' => $udata['u_id'],
				'i_timestamp' => date("Y-m-d H:i:s"),
				'i_status' => -1, //Deleted by user
		));
		
		//TODO Save change history
		
		
		//Show result:
		die('<span style="color:#00CC00;">Deleted</span>');
	}
	
	function cr_delete(){
		//Auth user and Load object:
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['cr_id']) || intval($_POST['cr_id'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid cr_id.</span>');
		}
		
		//Now update the DB:
		$this->Db_model->cr_update( intval($_POST['cr_id']) , array(
				'cr_creator_id' => $udata['u_id'],
				'cr_timestamp' => date("Y-m-d H:i:s"),
				'cr_status' => -1, //Deleted by user
		));
		
		//TODO Save change history
		
		//Show result:
		die('<span style="color:#00CC00;">Deleted</span>');
	}
	
	function i_edit(){
		//Auth user and Load object:
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['i_id']) || intval($_POST['i_id'])<=0){
			die('<span style="color:#FF0000;">Error: Missing i_id.</span>');
		} elseif(!isset($_POST['i_message']) || strlen($_POST['i_message'])<=0){
			die('<span style="color:#FF0000;">Error: Missing i_message.</span>');
		}
		
		//Now update the DB:
		$this->Db_model->i_update( intval($_POST['i_id']) , array(
				'i_creator_id' => $udata['u_id'],
				'i_timestamp' => date("Y-m-d H:i:s"),
				'i_message' => trim($_POST['i_message']),
		));
		
		//TODO Save change history
		
		//Show result:
		die('<span style="color:#00CC00;">Saved</span>');
	}
	
	function bootcamp_edit_process(){
	    
	    //Auth user and check required variables:
		$udata = auth(2);
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['save_c_id']) || intval($_POST['save_c_id'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid ID.</span>');
		} elseif(!isset($_POST['save_c_objective']) || strlen($_POST['save_c_objective'])<=0){
		    die('<span style="color:#FF0000;">Error: Objective is Required.</span>');
		} elseif(!isset($_POST['save_c_time_estimate']) || floatval($_POST['save_c_time_estimate'])<0){
		    die('<span style="color:#FF0000;">Error: Time estimate is Required.</span>');
		} elseif(!isset($_POST['save_c_is_grandpa'])){
		    die('<span style="color:#FF0000;">Error: Marketplace listed status is required.</span>');
		} elseif(!isset($_POST['save_c_status'])){
		    die('<span style="color:#FF0000;">Error: Bootcamp status is Required.</span>');
		} elseif(!isset($_POST['save_c_url_key'])  || ($_POST['save_c_is_grandpa'] && strlen($_POST['save_c_url_key'])<=0)){
		    die('<span style="color:#FF0000;">Error: URL Key is Required.</span>');
		}
		
		//Not required variables:
		if(!isset($_POST['save_c_additional_goals'])){
		    $_POST['save_c_additional_goals'] = '';
		}
		if(!isset($_POST['save_c_todo_overview'])){
		    $_POST['save_c_todo_overview'] = '';
		}
		if(!isset($_POST['save_c_todo_bible'])){
		    $_POST['save_c_todo_bible'] = '';
		}
		if(!isset($_POST['save_c_prerequisites'])){
		    $_POST['save_c_prerequisites'] = '';
		}
		if(!isset($_POST['save_c_user_says_statements'])){
		    $_POST['save_c_user_says_statements'] = '';
		}
		
		//Now update the DB:
		$this->Db_model->challenge_update(intval($_POST['save_c_id']) , array(
			'c_creator_id' => $udata['u_id'],
			'c_timestamp' => date("Y-m-d H:i:s"),
		    'c_objective' => trim($_POST['save_c_objective']),
		    'c_url_key' => trim(strtolower($_POST['save_c_url_key'])),
		    'c_additional_goals' => $_POST['save_c_additional_goals'],
		    'c_todo_bible' => $_POST['save_c_todo_bible'],
		    'c_todo_overview' => $_POST['save_c_todo_overview'],
		    'c_prerequisites' => $_POST['save_c_prerequisites'],
		    'c_user_says_statements' => $_POST['save_c_user_says_statements'],
		    'c_time_estimate' => floatval($_POST['save_c_time_estimate']),
		    'c_is_grandpa' => ( $_POST['save_c_is_grandpa'] ? 't' : 'f' ),
		    'c_status' => intval($_POST['save_c_status']),
		));
		
		//Update Algolia:
		$this->Db_model->sync_algolia(intval($_POST['save_c_id']));
		
		//TODO Save change history
		
		//Show result:
		die('<span style="color:#00CC00;">Saved</span>');
	}
	
	function cohort_save($c_id=null){
		//Auth user and Load object:
		$udata = auth(2,1);
		
		//Are we updating an existing challenge or creating a new one?
		if($c_id){
			//Updating
			$bootcamp = load_object('c' , array(
					'c.c_id' => $c_id,
					'c.c_is_grandpa' => true,
			));
			
		} else {
			//Creating a new challenge:
			
		}
	}
	
	
	/* ******************************
	 * Cohorts
	 ****************************** */
	
	function cohort_dashboard($c_id,$r_id){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//Fetch & Validate Run:
		$run = filter($bootcamp['runs'],'r_id',$r_id);
		if(!$run){
			redirect_message('/marketplace/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run number.</div>');
		}
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Run #'.$r_id.' | '.$bootcamp['c_objective'],
				'bootcamp' => $bootcamp,
				'run' => $run,
		));
		$this->load->view('marketplace/cohort/cohort_dashboard' , array(
				'bootcamp' => $bootcamp,
				'run' => $run,
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	
	function cohort_leaderboard($c_id,$r_id){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//Fetch & Validate Run:
		$run = filter($bootcamp['runs'],'r_id',$r_id);
		if(!$run){
			redirect_message('/marketplace/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run number.</div>');
		}
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Leaderboard | Run #'.$r_id.' | '.$bootcamp['c_objective'],
				'bootcamp' => $bootcamp,
				'run' => $run,
		));
		$this->load->view('marketplace/cohort/cohort_leaderboard' , array(
				'bootcamp' => $bootcamp,
				'run' => $run,
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	function cohort_activity($c_id,$r_id){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//Fetch & Validate Run:
		$run = filter($bootcamp['runs'],'r_id',$r_id);
		if(!$run){
			redirect_message('/marketplace/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run number.</div>');
		}
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Timeline | Run #'.$r_id.' | '.$bootcamp['c_objective'],
				'bootcamp' => $bootcamp,
				'run' => $run,
		));
		$this->load->view('marketplace/cohort/cohort_activity' , array(
				'bootcamp' => $bootcamp,
				'run' => $run,
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	

	function cohort_settings($c_id,$r_id=null){
		//Authenticate:
		$udata = auth(2,1);
		
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//This could be a new run, or editing an existing run:
		if($r_id){
			//This is an edit, not a new run. Fetch & Validate Run:
			$run = filter($bootcamp['runs'],'r_id',$r_id);
			if(!$run){
				redirect_message('/marketplace/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run.</div>');
			}
			
			$view_data = array(
					'title' => 'Run #'.$r_id.' Settings | '.$bootcamp['c_objective'],
					'bootcamp' => $bootcamp,
					'run' => $run,
			);
		} else {
			//Creating a new run:
			$view_data = array(
					'title' => 'Add Run | '.$bootcamp['c_objective'],
					'bootcamp' => $bootcamp,
			);
		}
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , $view_data);
		$this->load->view('marketplace/cohort/cohort_settings' , $view_data);
		$this->load->view('marketplace/shared/d_footer');
	}
}