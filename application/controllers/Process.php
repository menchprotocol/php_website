<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Process extends CI_Controller {
	
    //Used for all JS processing calls
    
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	function index(){
		die('nothing here...');
	}
	
	/* ******************************
	 * Miscs
	 ****************************** */
	
	function algolia($pid=null){
	    //Used to update local host:
	    print_r($this->Db_model->sync_algolia($pid));
	}
	
	function contact_us(){
	    
	    if(!isset($_POST['your_name']) || strlen($_POST['your_name'])<=1){
	        echo '<span style="color:#FF0000;">Error: Name required.</span>';
	    } elseif(!isset($_POST['your_email']) || !filter_var($_POST['your_email'], FILTER_VALIDATE_EMAIL)){
	        echo '<span style="color:#FF0000;">Error: Valid email required.</span>';
	    } elseif(!isset($_POST['your_message']) || strlen($_POST['your_message'])<=0){
	        echo '<span style="color:#FF0000;">Error: Message required.</span>';
	    } else {
	        
	        $this->load->model("Email_model");
	        if($this->Email_model->contact_us($_POST['your_name'],$_POST['your_email'],$_POST['your_message'])){
	            //Display confirmation:
	            echo '<span style="color:#00CC00;">Message received. We will get back to you shortly.</span>';
	        } else {
	            echo '<span style="color:#FF0000;">Error: Unknown email error. Please contact us directly at support@mench.co</span>';
	        }
	        
	    }
	}
	
	/* ******************************
	 * Users
	 ****************************** */
	
	function login(){
	    
	    if(!isset($_POST['u_email']) || !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
	    } elseif(!isset($_POST['u_password'])){
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
	    }
	    
	    //Fetch user data:
	    $users = $this->Db_model->u_fetch(array(
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
	        
	        //TODO login engagement
	        
	        if(isset($_POST['url']) && strlen($_POST['url'])>0){
	            header( 'Location: '.$_POST['url'] );
	        } else {
	            //Default:
	            header( 'Location: /console' );
	        }
	    }
	}
	
	function logout(){
	    //Called via AJAX to destroy user session:
	    $this->session->sess_destroy();
	    header( 'Location: /' );
	}
	
	function account_update(){
	    
	    //Auth user and check required variables:
	    $udata = auth(2);
	    $countries_all = $this->config->item('countries_all');
	    $timezones = $this->config->item('timezones');
	    
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the page and try again.</span>');
	    } elseif(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid ID. Try again.</span>');
	    } elseif(!isset($_POST['u_fname']) || strlen($_POST['u_fname'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing First Name. Try again.</span>');
	    } elseif(!isset($_POST['u_lname']) || strlen($_POST['u_lname'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing last name. Try again.</span>');
	    } elseif(!isset($_POST['u_email']) || !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){
	        die('<span style="color:#FF0000;">Error: Missing email. Try again.</span>');
	    } elseif(!isset($_POST['u_image_url']) || !filter_var($_POST['u_image_url'], FILTER_VALIDATE_URL) || substr($_POST['u_image_url'],0,8)!=='https://' || !url_exists($_POST['u_image_url'])){
	        die('<span style="color:#FF0000;">Error: Invalid HTTPS profile picture url. Try again.</span>');
	    } elseif(!isset($_POST['u_gender']) || !in_array($_POST['u_gender'],array('m','f'))){
	        die('<span style="color:#FF0000;">Error: Missing gender. Try again.</span>');
	    } elseif(!isset($_POST['u_country_code']) || !array_key_exists($_POST['u_country_code'], $countries_all)){
	        die('<span style="color:#FF0000;">Error: Missing country. Try again.</span>');
	    } elseif(!isset($_POST['u_timezone']) || !array_key_exists($_POST['u_timezone'], $timezones)){
	        die('<span style="color:#FF0000;">Error: Missing timezone.</span>');
	    } elseif(!isset($_POST['u_language']) || count($_POST['u_language'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing language. Try again.</span>');
	    }
	    
	    //validate current password:
	    $u_current = $this->Db_model->u_fetch(array(
	        'u_id' => intval($_POST['u_id']),
	    ));
	    
	    $u_update = array(
	        'u_fname' => trim($_POST['u_fname']),
	        'u_lname' => trim($_POST['u_lname']),
	        'u_email' => $_POST['u_email'],
	        'u_phone' => $_POST['u_phone'],
	        'u_image_url' => $_POST['u_image_url'],
	        'u_gender' => $_POST['u_gender'],
	        'u_country_code' => $_POST['u_country_code'],
	        'u_current_city' => $_POST['u_current_city'],
	        'u_timezone' => $_POST['u_timezone'],
	        'u_language' => join(',',$_POST['u_language']),
	        'u_bio' => trim($_POST['u_bio']),
	        'u_tangible_experience' => trim($_POST['u_tangible_experience']),
	        'u_skype_username' => trim($_POST['u_skype_username']),
	    );
	    
	    //Some more checks:
	    if(!(count($u_current)==1)){
	        die('<span style="color:#FF0000;">Error: Invalid user ID.</span>');
	    } elseif(strlen($_POST['u_password_new'])>0 || strlen($_POST['u_password_current'])>0){
	        //Password update attempt, lets check:
	        if(strlen($_POST['u_password_new'])<=0){
	            die('<span style="color:#FF0000;">Error: Missing new password. Try again.</span>');
	        } elseif(strlen($_POST['u_password_current'])<=0){
	            die('<span style="color:#FF0000;">Error: Missing current password. Try again.</span>');
	        } elseif(!(md5($_POST['u_password_current'])==$u_current[0]['u_password'])){
	            die('<span style="color:#FF0000;">Error: Invalid current password. Try again.</span>');
	        } elseif($_POST['u_password_new']==$_POST['u_password_current']){
	            die('<span style="color:#FF0000;">Error: New and current password cannot be the same. Try again.</span>');
	        } elseif(strlen($_POST['u_password_new'])<6){
	            die('<span style="color:#FF0000;">Error: New password must be longer than 6 characters. Try again.</span>');
	        } else {
	            //Set password for updating:
	            $u_update['u_password'] = md5($_POST['u_password_new']);
	            //Reset both fields:
	            echo "<script>$('#u_password_current').val('');$('#u_password_new').val('');</script>";
	        }
	    }
	    $warning = NULL;
	    
	    //Check social links:
	    if($_POST['u_website_url']!==$u_current[0]['u_website_url']){
	        if(strlen($_POST['u_website_url'])>0){
	            //Validate it:
	            if(filter_var($_POST['u_website_url'], FILTER_VALIDATE_URL) && url_exists($_POST['u_website_url'])){
	                $u_update['u_website_url'] = $_POST['u_website_url'];
	                echo "<script>$('#u_password_current').val('');$('#u_password_new').val('');</script>";
	            } else {
	                $warning .= 'Invalid website URL. ';
	            }
	        } else {
	            $u_update['u_website_url'] = '';
	        }
	    }
	    
	    
	    $u_social_account = $this->config->item('u_social_account');
	    foreach($u_social_account as $sa_key=>$sa_value){
	        if($_POST[$sa_key]!==$u_current[0][$sa_key]){
	            if(strlen($_POST[$sa_key])>0){
	                //User has attempted to update it, lets validate it:
	                $full_url = $sa_value['sa_prefix'].trim($_POST[$sa_key]).$sa_value['sa_postfix'];
	                if(url_exists($full_url)){
	                    $u_update[$sa_key] = trim($_POST[$sa_key]);
	                } else {
	                    $warning .= 'Invalid '.$sa_value['sa_name'].' username. ';
	                }
	            } else {
	                $u_update[$sa_key] = '';
	            }
	        }
	    }
	    
	    //Now update the DB:
	    $this->Db_model->u_update(intval($_POST['u_id']) , $u_update);
	    
	    //TODO Save change history
	    //TODO update algolia?
	    
	    //Show result:
	    echo ( $warning ? '<span style="color:#FF8C00;">Saved all except: '.$warning.'</span>' : '<span style="color:#00CC00;">Saved</span>');
	}
	
	
	/* ******************************
	 * r Cohorts
	 ****************************** */
	
	
	function cohort_create(){
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
	        //TODO make sure its monday
	        die('<span style="color:#FF0000;">Error: Enter valid start date.</span>');
	    } elseif(!isset($_POST['r_b_id']) || intval($_POST['r_b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid bootcamp ID.</span>');
	    } else {
	        	        
	        //Create new cohort:
	        $cohort = $this->Db_model->r_create(array(
	            'r_b_id' => intval($_POST['r_b_id']),
	            'r_status' => 0, //Drafting
	            'r_start_date' => date("Y-m-d",strtotime($_POST['r_start_date'])),
	            //Set some defaults:
	            'r_min_students' => 1,
	            'r_max_students' => 20,
	            'r_usd_price' => 0,
	        ));
	        
	        if($cohort['r_id']>0){
	            //Redirect:
	            echo '<script> window.location = "/console/'.intval($_POST['r_b_id']).'/cohorts/'.$cohort['r_id'].'" </script>';
	        } else {
	            die('<span style="color:#FF0000;">Error: Unkown error while trying to create this bootcamp.</span>');
	        }
	    }
	}
	
	
	function update_schedule(){
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['hours']) || !is_array($_POST['hours'])){
	        //TODO make sure its monday
	        die('<span style="color:#FF0000;">Error: Missing hours.</span>');
	    } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Cohort ID.</span>');
	    }
	    
	    
	    $this->Db_model->r_update( intval($_POST['r_id']) , array(
	        'r_live_office_hours' => serialize($_POST['hours']),
	    ));
	    
	    //TODO Save change history
	    
	    
	    //Show result:
	    die('<span style="color:#00CC00;">Saved</span>');
	    
	}
	
	
	
	function cohort_edit(){
	    
	    //Auth user and check required variables:
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
	        //TODO make sure its monday
	        die('<span style="color:#FF0000;">Error: Enter valid start date.</span>');
	    } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Cohort ID.</span>');
	    } elseif(!isset($_POST['r_status'])){
	        die('<span style="color:#FF0000;">Error: Missing Cohort Status.</span>');
	    }
	    
	    
	    if(!isset($_POST['r_usd_price']) || intval($_POST['r_usd_price'])<0){
	        $_POST['r_usd_price'] = 0;
	    }
	    if(!isset($_POST['r_min_students'])){
	        $_POST['r_min_students'] = 1;
	    }
	    if(!isset($_POST['r_max_students'])){
	        $_POST['r_max_students'] = 20;
	    }
	    if(!isset($_POST['r_closed_dates'])){
	        $_POST['r_closed_dates'] = '';
	    }
	    
	    if(!isset($_POST['r_response_time_hours'])){
	        $_POST['r_response_time_hours'] = 24;
	    }
	    if(!isset($_POST['r_weekly_1on1s'])){
	        $_POST['r_weekly_1on1s'] = 0;
	    }
	        
	    
	    $this->Db_model->r_update( intval($_POST['r_id']) , array(
	        'r_status' => intval($_POST['r_status']),
	        'r_min_students' => intval($_POST['r_min_students']),
	        'r_max_students' => intval($_POST['r_max_students']),
	        'r_start_date' => date("Y-m-d",strtotime($_POST['r_start_date'])),
	        'r_usd_price' => floatval($_POST['r_usd_price']),
	        'r_closed_dates' => $_POST['r_closed_dates'],
	        'r_response_time_hours' => $_POST['r_response_time_hours'],
	        'r_weekly_1on1s' => $_POST['r_weekly_1on1s'],
	    ));
	    
	    //TODO Save change history
	    
	    //Show result:
	    die('<span style="color:#00CC00;">Saved</span>');
	}
	
	
	
	
	
	/* ******************************
	 * c Intents
	 ****************************** */
	
	function bootcamp_create(){
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['c_primary_objective']) || strlen($_POST['c_primary_objective'])<2){
	        die('<span style="color:#FF0000;">Error: Title must be 2 characters or longer.</span>');
	    }
	        
        //Create new intent:
        $intent = $this->Db_model->c_create(array(
            'c_creator_id' => $udata['u_id'],
            'c_objective' => trim($_POST['c_primary_objective']),
        ));
        if(intval($intent['c_id'])<=0){
            die('<span style="color:#FF0000;">Error: Unkown error while trying to create intent.</span>');
        }
        
        //Create new bootcamp:
        $bootcamp = $this->Db_model->b_create(array(
            'b_creator_id' => $udata['u_id'],
            'b_url_key' => url_key(trim($_POST['c_primary_objective'])),
            'b_status' => 0, //Bootcamp on Hold
            'b_c_id' => $intent['c_id'],
        ));
        if(intval($bootcamp['b_id'])<=0){
            die('<span style="color:#FF0000;">Error: Unkown error while trying to create bootcamp.</span>');
        }
        
        //Assign permissions for this user:
        $admin_status = $this->Db_model->ba_create(array(
            'ba_creator_id' => $udata['u_id'],
            'ba_u_id' => $udata['u_id'],
            'ba_status' => 3, //Leader - As this is the first person to create
            'ba_b_id' => $bootcamp['b_id'],
            'ba_team_display' => 't', //Show on landing page
        ));
        if(intval($admin_status['ba_id'])<=0){
            die('<span style="color:#FF0000;">Error: Unkown error while trying to set bootcamp leader.</span>');
        }
        
        //Redirect:
        echo '<script> window.location = "/console/'.$bootcamp['b_id'].'" </script>';
            
	}
	
	
	
	function intent_create(){
	    
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0 || !is_valid_intent($_POST['c_id'])){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['direction']) || !in_array($_POST['direction'],array('outbound','inbound'))){
	        die('<span style="color:#FF0000;">Error: Invalid Linking Direction.</span>');
	    } elseif(!isset($_POST['c_objective']) || strlen($_POST['c_objective'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Intent Objective.</span>');
	    }
	    
	    //Create intent:
	    $new_intent = $this->Db_model->c_create(array(
	        'c_creator_id' => $udata['u_id'],
	        'c_objective' => trim($_POST['c_objective']),
	    ));
	    
	    //Create Link:
	    $relation = $this->Db_model->cr_create(array(
	        'cr_creator_id' => $udata['u_id'],
	        'cr_inbound_id'  => ( $_POST['direction']=='outbound' ? intval($_POST['pid']) : $new_intent['c_id'] ),
	        'cr_outbound_id' => ( $_POST['direction']=='outbound' ? $new_intent['c_id'] : intval($_POST['pid']) ),
	        'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
	            'cr_status >=' => 0,
	            'cr_inbound_id' => intval($_POST['pid']),
	        )),
	    ));
	    
	    //Fetch full link package:
	    if($_POST['direction']=='outbound'){
	        $relations = $this->Db_model->cr_outbound_fetch(array(
	            'cr.cr_id' => $relation['cr_id'],
	        ));
	    } else {
	        $relations = $this->Db_model->cr_inbound_fetch(array(
	            'cr.cr_id' => $relation['cr_id'],
	        ));
	    }
	    
	    //Update Algolia:
	    $this->Db_model->sync_algolia($new_intent['c_id']);
	    
	    //Return result:
	    echo echo_cr($_POST['b_id'],$relations[0],$_POST['direction'],$_POST['next_level']);
	}
	
	function intent_edit(){
	    
	    //Auth user and check required variables:
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['c_objective']) || strlen($_POST['c_objective'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Intent Objective.</span>');
	    }
	    
	    //Not required variables:
	    if(!isset($_POST['c_todo_overview'])){
	        $_POST['c_todo_overview'] = '';
	    }
	    if(!isset($_POST['c_prerequisites'])){
	        $_POST['c_prerequisites'] = '';
	    }
	    if(!isset($_POST['c_todo_bible'])){
	        $_POST['c_todo_bible'] = '';
	    }
	    if(!isset($_POST['c_time_estimate'])){
	        $_POST['c_time_estimate'] = 0;
	    }
	    
	    
	    $save_array = array(
	        'c_objective' => trim($_POST['c_objective']),
	        'c_todo_bible' => $_POST['c_todo_bible'],
	        'c_todo_overview' => $_POST['c_todo_overview'],
	        'c_prerequisites' => $_POST['c_prerequisites'],
	        'c_time_estimate' => floatval($_POST['c_time_estimate']),
	    );
	    
	    //Now update the DB:
	    $this->Db_model->c_update( intval($_POST['pid']) , $save_array );
	    
	    //Update Algolia:
	    $this->Db_model->sync_algolia(intval($_POST['pid']));
	    
	    //TODO Save change history
	    
	    
	    //Show result:
	    die('<span style="color:#00CC00;">Saved</span>');
	}
	
	function intent_link(){
	    
	    $udata = auth(2);
	    
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0 || !is_valid_intent($_POST['c_id'])){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['direction']) || !in_array($_POST['direction'],array('outbound','inbound'))){
	        die('<span style="color:#FF0000;">Error: Invalid Link Direction.</span>');
	    } elseif(!isset($_POST['target_id']) || intval($_POST['target_id'])<=0 || !is_valid_intent($_POST['target_id'])){
	        die('<span style="color:#FF0000;">Error: Missing target_id.</span>');
	    }
	    //TODO Check to make sure not duplicate
	    
	    
	    //Create Link only:
	    $relation = $this->Db_model->cr_create(array(
	        'cr_creator_id' => $udata['u_id'],
	        'cr_inbound_id'  => ( $_POST['direction']=='outbound' ? intval($_POST['pid']) : intval($_POST['target_id']) ),
	        'cr_outbound_id' => ( $_POST['direction']=='outbound' ? intval($_POST['target_id']) : intval($_POST['pid']) ),
	        'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
	            'cr_status >=' => 0,
	            'cr_inbound_id' => intval($_POST['pid']),
	        )),
	    ));
	    
	    //Fetch full link package:
	    if($_POST['direction']=='outbound'){
	        $relations = $this->Db_model->cr_outbound_fetch(array(
	            'cr.cr_id' => $relation['cr_id'],
	        ));
	    } else {
	        $relations = $this->Db_model->cr_inbound_fetch(array(
	            'cr.cr_id' => $relation['cr_id'],
	        ));
	    }
	    
	    //Return result:
	    echo echo_cr($_POST['b_id'],$relations[0],$_POST['direction'],$_POST['next_level']);
	}
	
	
	function intent_unlink(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['cr_id']) || intval($_POST['cr_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Link ID.</span>');
	    }
	    
	    //Now update the DB:
	    $this->Db_model->cr_update( intval($_POST['cr_id']) , array(
	        'cr_status' => -1, //Deleted by user
	    ));
	    
	    //TODO Save to change history
	    
	    //Show result:
	    die('<span style="color:#00CC00;">Removed Link</span>');
	}
	
	
	function intents_sort(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0 || !is_valid_intent($_POST['c_id'])){
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
	
	
	
	/* ******************************
	 * i Media
	 ****************************** */
	
	function media_create(){
	    
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh to Continue.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        die('<span style="color:#FF0000;">Error: Invalid ID.</span>');
	    } elseif(!isset($_POST['i_message']) || strlen($_POST['i_message'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing message.</span>');
	    }
	    
	    //Create Link:
	    $i = $this->Db_model->i_create(array(
	        'i_creator_id' => $udata['u_id'],
	        'i_c_id' => intval($_POST['pid']),
	        'i_message' => trim($_POST['i_message']),
	        'i_rank' => 1 + $this->Db_model->max_value('v5_media','i_rank', array(
	            'i_status >=' => 0,
	            'i_c_id' => intval($_POST['pid']),
	        )),
	    ));
	    
	    //TODO Log engagement
	    
	    
	    //Print the challenge:
	    echo_message($i);
	}
	
	function media_edit(){
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
	
	function media_delete(){
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
	
	function media_sort(){
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
	
	
}