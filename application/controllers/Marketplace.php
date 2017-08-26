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
				$udata['access'] = $this->Db_model->fetch_user_access($udata['u_id']);
				$this->session->set_userdata(array(
						'user' => $udata, //This has the user's top link data
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
		$this->load->view('marketplace/challenge/challenge_marketplace' , array(
				'challenges' => $this->Db_model->c_fetch(array(
						'c.c_status >=' => 0,
						'c.c_is_grandpa' => true, //Not sub challenges
				)),
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	
	function challenge_framework($c_id,$pid=null){
		
		$udata = auth(2,1);
		$challenge = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		$pid = ( isset($pid) && intval($pid)>0 ? $pid : $challenge['c_id'] );
		//Construct data:
		$view_data = array(
				'challenge' => $challenge,
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
		
		//Do we have a run loaded in session?
		if($this->session->userdata('r_focus_version')) {
			$temp = explode('_',$this->session->userdata('r_focus_version'),2);
			if($temp[0]==$challenge['c_id']){
				$view_data['run'] = filter($challenge['runs'],'r_version',$temp[1]);
			}
		}
		
		//Show View
		$this->load->view('marketplace/shared/d_header' , $view_data);
		$this->load->view('marketplace/challenge/challenge_framework' , $view_data);
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
				'i_rank' => 1 + $this->Db_model->max_value('v5_challenge_insights','i_rank', array(
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
		$challenge = $this->Db_model->c_create(array(
				'c_creator_id' => $udata['u_id'],
				'c_objective' => trim($_POST['c_objective']),
		));
		
		//Create Link:
		$relation = $this->Db_model->cr_create(array(
				'cr_creator_id' => $udata['u_id'],
				
				//Linking:
				'cr_inbound_id'  => ( $is_outbound ? $f_challenge['c_id'] : $challenge['c_id'] ),
				'cr_outbound_id' => ( $is_outbound ? $challenge['c_id'] : $f_challenge['c_id'] ),
				
				//Fetch ranks:
				'cr_inbound_rank'  => 1 + $this->Db_model->max_value('v5_challenge_relations','cr_inbound_rank', array(
						'cr_status >=' => 0,
						'cr_outbound_id' => $f_challenge['c_id'],
				)),
				'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_challenge_relations','cr_outbound_rank', array(
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
		$this->Db_model->sync_algolia($challenge['c_id']);
		
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
		$challenge = load_object('c' , array(
				'c.c_id' => $_POST['target_id'],
				'c.c_status >=' => 0,
		));
		
		//TODO Check to make sure not duplicate
		
		//Create Link:
		$relation = $this->Db_model->cr_create(array(
				'cr_creator_id' => $udata['u_id'],
				
				//Linking:
				'cr_inbound_id'  => ( $is_outbound ? $f_challenge['c_id'] : $challenge['c_id'] ),
				'cr_outbound_id' => ( $is_outbound ? $challenge['c_id'] : $f_challenge['c_id'] ),
				
				//Fetch ranks:
				'cr_inbound_rank'  => 1 + $this->Db_model->max_value('v5_challenge_relations','cr_inbound_rank', array(
						'cr_status >=' => 0,
						'cr_outbound_id' => $f_challenge['c_id'],
				)),
				'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_challenge_relations','cr_outbound_rank', array(
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
	
	function challenge_modify(){
		//Auth user and Load object:
		$udata = auth(2);
		
		if(!$udata){
			die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
		} elseif(!isset($_POST['save_c_id']) || intval($_POST['save_c_id'])<=0){
			die('<span style="color:#FF0000;">Error: Invalid ID.</span>');
		} elseif(!isset($_POST['save_c_objective']) || strlen($_POST['save_c_objective'])<=0){
			die('<span style="color:#FF0000;">Error: Objective is Required.</span>');
		} elseif(!isset($_POST['save_c_description'])){
			$_POST['save_c_description'] = ''; //Not required
		}
		
		//Now update the DB:
		$this->Db_model->challenge_update(intval($_POST['save_c_id']) , array(
				'c_creator_id' => $udata['u_id'],
				'c_timestamp' => date("Y-m-d H:i:s"),
				'c_objective' => trim($_POST['save_c_objective']),
				'c_description' => $_POST['save_c_description'],
		));
		
		//Update Algolia:
		$this->Db_model->sync_algolia(intval($_POST['save_c_id']));
		
		//TODO Save change history
		
		//Show result:
		die('<span style="color:#00CC00;">Saved</span>');
	}
	
	function run_save($c_id=null){
		//Auth user and Load object:
		$udata = auth(2,1);
		
		//Are we updating an existing challenge or creating a new one?
		if($c_id){
			//Updating
			$challenge = load_object('c' , array(
					'c.c_id' => $c_id,
					'c.c_is_grandpa' => true,
			));
			
		} else {
			//Creating a new challenge:
			
		}
	}
	
	
	/* ******************************
	 * Runs
	 ****************************** */
	
	function run_dashboard($c_id,$r_version){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		$challenge = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//Fetch & Validate Run:
		$run = filter($challenge['runs'],'r_version',$r_version);
		if(!$run){
			redirect_message('/marketplace/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run number.</div>');
		}
		
		//Load into session:
		$this->session->set_userdata(array(
				'r_focus_version' => $challenge['c_id'].'_'.$r_version, //To presistently keep the loaded Run menu open
		));
		
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
	
	
	function run_leaderboard($c_id,$r_version){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		$challenge = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//Fetch & Validate Run:
		$run = filter($challenge['runs'],'r_version',$r_version);
		if(!$run){
			redirect_message('/marketplace/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run number.</div>');
		}
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Leaderboard | Run #'.$r_version.' | '.$challenge['c_objective'],
				'challenge' => $challenge,
				'run' => $run,
		));
		$this->load->view('marketplace/run/run_leaderboard' , array(
				'challenge' => $challenge,
				'run' => $run,
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	function run_activity($c_id,$r_version){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		$challenge = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//Fetch & Validate Run:
		$run = filter($challenge['runs'],'r_version',$r_version);
		if(!$run){
			redirect_message('/marketplace/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run number.</div>');
		}
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , array(
				'title' => 'Timeline | Run #'.$r_version.' | '.$challenge['c_objective'],
				'challenge' => $challenge,
				'run' => $run,
		));
		$this->load->view('marketplace/run/run_activity' , array(
				'challenge' => $challenge,
				'run' => $run,
		));
		$this->load->view('marketplace/shared/d_footer');
	}
	
	//challenge modification wizard:
	function run_settings($c_id,$r_version=null){
		//Authenticate:
		$udata = auth(2,1);
		
		$challenge = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//This could be a new run, or editing an existing run:
		if($r_version){
			//This is an edit, not a new run. Fetch & Validate Run:
			$run = filter($challenge['runs'],'r_version',$r_version);
			if(!$run){
				redirect_message('/marketplace/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run.</div>');
			}
			
			$view_data = array(
					'title' => 'Run #'.$r_version.' Settings | '.$challenge['c_objective'],
					'challenge' => $challenge,
					'run' => $run,
			);
		} else {
			//Creating a new run:
			$view_data = array(
					'title' => 'Add Run | '.$challenge['c_objective'],
					'challenge' => $challenge,
			);
		}
		
		//Load view
		$this->load->view('marketplace/shared/d_header' , $view_data);
		$this->load->view('marketplace/run/run_settings' , $view_data);
		$this->load->view('marketplace/shared/d_footer');
	}
}