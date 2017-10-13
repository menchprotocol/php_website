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
	
	/* ******************************
	 * Users
	 ****************************** */
	
	function login(){
	    
	    if(!isset($_POST['u_email']) || !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
	        return false;
	    } elseif(!isset($_POST['u_password'])){
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
	        return false;
	    }
	    
	    //Fetch user data:
	    $users = $this->Db_model->u_fetch(array(
	        'u_email' => strtolower($_POST['u_email']),
	    ));
	    
	    if(count($users)==0){
	        //Not found!
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: '.$_POST['u_email'].' not found.</div>');
	        return false;
	    } elseif($users[0]['u_status']<=0){
	        //Inactive account
	        $this->Db_model->e_create(array(
	            'e_creator_id' => $users[0]['u_id'],
	            'e_message' => 'login() denied because account is not active.',
	            'e_json' => json_encode($_POST),
	            'e_type_id' => 9, //Platform Expected Error
	        ));
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Your account has been de-activated. Contact us for more details.</div>');
	        return false;
	    } elseif(!($users[0]['u_password']==md5($_POST['u_password']))){
	        //Bad password
	        $this->Db_model->e_create(array(
	            'e_creator_id' => $users[0]['u_id'],
	            'e_message' => 'login() denied because of incorrect password.',
	            'e_json' => json_encode($_POST),
	            'e_type_id' => 9, //Platform Expected Error
	        ));
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Incorrect password for '.$_POST['u_email'].'.</div>');
	        return false;
	    }
	    
        if($users[0]['u_status']==1){
            //Regular user, they are required to be assigned to at-least one bootcamp to be able to login:
            $bootcamps = $this->Db_model->u_bootcamps(array(
                'ba.ba_u_id' => $users[0]['u_id'],
                'ba.ba_status >=' => 1,
                'b.b_status >=' => 0,
            ));
            
            if(count($bootcamps)<=0){
                $this->Db_model->e_create(array(
                    'e_creator_id' => $users[0]['u_id'],
                    'e_message' => 'login() denied because user is not assigned to any bootcamps.',
                    'e_json' => json_encode($_POST),
                    'e_type_id' => 9, //Platform Expected Error
                ));
                redirect_message('/login','<div class="alert alert-danger" role="alert">Error: You are not assigned to any bootcamps.</div>');
                return false;
            }
        }
        
        //All good to go!
        //Load session and redirect:
        $this->session->set_userdata(array(
            'user' => $users[0],
        ));
        
        //Append user IP and agent information
        unset($_POST['u_password']); //Sensitive information to be removed and NOT logged
        $users[0]['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $users[0]['get_browser'] = get_browser(null, true);
        $users[0]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $users[0]['input_post_data'] = $_POST;
        
        //Log engagement
        $this->Db_model->e_create(array(
            'e_json' => json_encode($users[0]),
            'e_type_id' => 10, //Admin login
        ));
        
        if(isset($_POST['url']) && strlen($_POST['url'])>0){
            header( 'Location: '.$_POST['url'] );
        } else {
            //Default:
            header( 'Location: /console' );
        }
	}
	
	function logout(){
	    //Log engagement:
	    $udata = $this->session->userdata('user');
	    $this->Db_model->e_create(array(
	        'e_json' => json_encode($udata),
	        'e_type_id' => 11, //Admin Logout
	    ));
	    
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
	    
	    //Fetch current data:
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
	    
	    //Remove sensitive data before logging:
	    unset($_POST['u_password_new']);
	    unset($_POST['u_password_current']);
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'], //The user that updated the account
	        'e_message' => readable_updates($u_current[0],$u_update,'u_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $u_current[0],
	            'after' => $u_update,
	        )),
	        'e_type_id' => 12, //Account Update
	        'e_object_id' => intval($_POST['u_id']), //The user that their account was updated
	    ));
	    
	    //TODO update algolia
	    
	    //Show result:
	    echo ( $warning ? '<span style="color:#FF8C00;">Saved all except: '.$warning.'</span>' : '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
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
	    } elseif(!isset($_POST['copy_cohort_id']) || intval($_POST['copy_cohort_id'])<0){
	        die('<span style="color:#FF0000;">Error: Missing Cohort Import Settings.</span>');
	    } else {
	        
	        //This is the variable we're building:
	        $cohort_data = null;
	        
	        if(intval($_POST['copy_cohort_id'])>0){
	            
	            //Fetch default questions:
	            $cohorts = $this->Db_model->r_fetch(array(
	                'r.r_id' => intval($_POST['copy_cohort_id']),
	                'r.r_b_id' => intval($_POST['r_b_id']),
	            ));
	            
	            if(count($cohorts)==1){
	                //Port the settings:
	                $cohort_data = $cohorts[0];
	                $eng_message = time_format($_POST['r_start_date'],1).' cohort created by copying '.time_format($cohorts[0]['r_start_date'],1).' cohort settings.';
	                
	                //Make some adjustments
	                $cohort_data['r_start_date'] = date("Y-m-d",strtotime($_POST['r_start_date']));
	                unset($cohort_data['r_status']); //Use the system default for this
	                unset($cohort_data['r_id']); //Remove as it would be assigned for this new cohort
	            }
	        }
	        
	        
	        //Did we build it?
	        if(!$cohort_data){
	            
	            //Fetch default questions:
	            $default_cohort_questions = $this->config->item('default_cohort_questions');
	            
	            //Generate core data:
	            $cohort_data = array(
	                'r_b_id' => intval($_POST['r_b_id']),
	                'r_start_date' => date("Y-m-d",strtotime($_POST['r_start_date'])),
	                'r_application_questions' => '<ol><li>'.join('</li><li>',$default_cohort_questions).'</li></ol>',
	            );
	            
	            //Default message:
	            $eng_message = time_format($_POST['r_start_date'],1).' cohort created form scratch.';
	        }
	        
	        
	        //Create new cohort:
	        $cohort = $this->Db_model->r_create($cohort_data);
	        
	        //Log engagement:
	        $this->Db_model->e_create(array(
	            'e_creator_id' => $udata['u_id'], //The user that updated the account
	            'e_message' => $eng_message,
	            'e_json' => json_encode(array(
	                'input' => $_POST,
	                'before' => array(),
	                'after' => $cohort,
	            )),
	            'e_type_id' => 14, //New Cohort
	            'e_object_id' => $cohort['r_id'],
	            'e_b_id' => intval($_POST['r_b_id']), //Share with bootcamp team
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
	    
	    //Fetch for the record:
	    $cohorts = $this->Db_model->r_fetch(array(
	        'r.r_id' => intval($_POST['r_id']),
	    ));
	    if(count($cohorts)<1){
	        //Ooops, not found!
	        die('<span style="color:#FF0000;">Error: Cohort ID not found.</span>');
	    }
	    
	    
	    $blank_template = 'a:7:{i:0;a:1:{s:3:"day";s:1:"0";}i:1;a:1:{s:3:"day";s:1:"1";}i:2;a:1:{s:3:"day";s:1:"2";}i:3;a:1:{s:3:"day";s:1:"3";}i:4;a:1:{s:3:"day";s:1:"4";}i:5;a:1:{s:3:"day";s:1:"5";}i:6;a:1:{s:3:"day";s:1:"6";}}';
	    $r_update = array(
	        'r_live_office_hours' => ( trim(serialize($_POST['hours']))==$blank_template ? '' : serialize($_POST['hours'])),
	    );
	    $this->Db_model->r_update( intval($_POST['r_id']) , $r_update);
	    
	    //Log engagement ONLY if different:
	    if($r_update['r_live_office_hours']!==$cohorts[0]['r_live_office_hours']){
	        $this->Db_model->e_create(array(
	            'e_creator_id' => $udata['u_id'], //The user
	            'e_message' => readable_updates($cohorts[0],$r_update,'r_'),
	            'e_json' => json_encode(array(
	                'input' => $_POST,
	                'before' => @unserialize($cohorts[0]['r_live_office_hours']),
	                'after' => @unserialize($r_update['r_live_office_hours']),
	            )),
	            'e_type_id' => 24, //Cohort Schedule Update
	            'e_object_id' => intval($_POST['r_id']),
	            'e_b_id' => $cohorts[0]['r_b_id'], //Share with bootcamp team
	        ));
	    }
	    
	    //Show result:
	    die('<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
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
	    
	    
	    //Fetch for the record:
	    $cohorts = $this->Db_model->r_fetch(array(
	        'r.r_id' => intval($_POST['r_id']),
	    ));
	    if(count($cohorts)<1){
	        //Ooops, not found!
	        die('<span style="color:#FF0000;">Error: Cohort ID not found.</span>');
	    }
	    
	    
	    $r_update = array(
	        'r_start_date' => date("Y-m-d",strtotime($_POST['r_start_date'])),
	        'r_response_time_hours' => $_POST['r_response_time_hours'],
	        'r_weekly_1on1s' => $_POST['r_weekly_1on1s'],
	        'r_office_hour_instructions' => $_POST['r_office_hour_instructions'],
	        'r_application_questions' => $_POST['r_application_questions'],
	        'r_closed_dates' => $_POST['r_closed_dates'],
	        'r_status' => intval($_POST['r_status']),
	        'r_usd_price' => floatval($_POST['r_usd_price']),
	        'r_min_students' => intval($_POST['r_min_students']),
	        'r_max_students' => intval($_POST['r_max_students']),
	    );
	    
	    if(isset($_POST['r_live_office_hours_check']) && !intval($_POST['r_live_office_hours_check'])){
	        //User the office schedule off, lets disable it:
	        $r_update['r_live_office_hours'] = '';
	    }	    
	    
	    //Save
	    $this->Db_model->r_update( intval($_POST['r_id']) , $r_update);
	    
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'], //The user
	        'e_message' => readable_updates($cohorts[0],$r_update,'r_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $cohorts[0],
	            'after' => $r_update,
	        )),
	        'e_type_id' => ($r_update['r_status']<0 && $r_update['r_status']!=$cohorts[0]['r_status'] ? 16 : 13), //Cohort Setting Updated/Deleted
	        'e_object_id' => intval($_POST['r_id']),
	        'e_b_id' => $cohorts[0]['r_b_id'], //Share with bootcamp team
	    ));
	    
	    
	    //Show result:
	    die('<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
	}
	
	
	
	
	
	/* ******************************
	 * b Bootcamps
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
            //Log this error:
            $this->Db_model->e_create(array(
                'e_creator_id' => $udata['u_id'],
                'e_message' => 'bootcamp_create() Function failed to create intent ['.$_POST['c_primary_objective'].'].',
                'e_json' => json_encode($_POST),
                'e_type_id' => 8, //Platform Error
            ));
            //Display error:
            die('<span style="color:#FF0000;">Error: Unkown error while trying to create intent.</span>');
        }        
        
        
        //Generaye URL Key:
        //Cleans text:
        $generated_key = clean_urlkey($_POST['c_primary_objective']);
        
        
        //Check for duplicates:
        $bootcamps = $this->Db_model->c_full_fetch(array(
            'b.b_url_key' => $generated_key,
        ));
        if(count($bootcamps)>0){
            //Ooops, we have a duplicate:
            $generated_key = $generated_key.'-'.rand(0,99999);
        }
        
        
        //Create new bootcamp:
        $bootcamp = $this->Db_model->b_create(array(
            'b_creator_id' => $udata['u_id'],
            'b_url_key' => $generated_key,
            'b_c_id' => $intent['c_id'],
        ));
        if(intval($bootcamp['b_id'])<=0){
            //Log this error:
            $this->Db_model->e_create(array(
                'e_creator_id' => $udata['u_id'],
                'e_message' => 'bootcamp_create() Function failed to create bootcamp for intent #'.$intent['c_id'],
                'e_json' => json_encode($_POST),
                'e_type_id' => 8, //Platform Error
            ));
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
            //Log this error:
            $this->Db_model->e_create(array(
                'e_creator_id' => $udata['u_id'],
                'e_message' => 'bootcamp_create() Function failed to grant permission for bootcamp #'.$bootcamp['b_id'],
                'e_json' => json_encode($_POST),
                'e_type_id' => 8, //Platform Error
            ));
            die('<span style="color:#FF0000;">Error: Unkown error while trying to set bootcamp leader.</span>');
        }

        
        //Log Engagement for Intent Created:
        $this->Db_model->e_create(array(
            'e_creator_id' => $udata['u_id'],
            'e_message' => '['.$intent['c_objective'].'] created as a new intent',
            'e_json' => json_encode(array(
                'input' => $_POST,
                'before' => null,
                'after' => $intent,
            )),
            'e_type_id' => 20, //Intent Created
            'e_object_id' => $intent['c_id'],
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
        ));
        
        
        //Log Engagement for Bootcamp Created:
        $this->Db_model->e_create(array(
            'e_creator_id' => $udata['u_id'],
            'e_message' => 'Bootcamp #'.$bootcamp['b_id'].' created for ['.$intent['c_objective'].'] intent',
            'e_json' => json_encode(array(
                'input' => $_POST,
                'before' => null,
                'after' => $bootcamp,
            )),
            'e_type_id' => 15, //Bootcamp Created
            'e_object_id' => $bootcamp['b_id'],
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
        ));
        
        
        //Log Engagement for Permission Granted:
        $this->Db_model->e_create(array(
            'e_creator_id' => $udata['u_id'],
            'e_message' => $udata['u_fname'].' '.$udata['u_lname'].' assigned as Bootcamp Leader',
            'e_json' => json_encode(array(
                'input' => $_POST,
                'before' => null,
                'after' => $admin_status,
            )),
            'e_type_id' => 25, //Permission Granted
            'e_object_id' => $udata['u_id'],
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
        ));
        
        
        //Redirect:
        echo '<script> window.location = "/console/'.$bootcamp['b_id'].'" </script>';
	}
	
	
	function bootcamp_edit(){
	    //Auth user and check required variables:
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['b_status'])){
	        die('<span style="color:#FF0000;">Error: Missing Status.</span>');
	    } elseif(!isset($_POST['b_url_key']) || strlen($_POST['b_url_key'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing URL Key.</span>');
	    }
	    
	    //Validate Bootcamp ID:
	    $bootcamps = $this->Db_model->b_fetch(array(
	        'b.b_id' => intval($_POST['b_id']),
	    ));
	    if(count($bootcamps)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    }
	    
	    //Cleanup the URL key:
	    $_POST['b_url_key'] = clean_urlkey($_POST['b_url_key']);
	    
	    //Validate URL key to be unique:
	    $duplicate_bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_url_key' => $_POST['b_url_key'],
	        'b.b_id !=' => intval($_POST['b_id']),
	    ));
	    if(count($duplicate_bootcamps)>0){
	        //Ooops, we have a duplicate:
	        die('<span style="color:#FF0000;">Error: Duplicate URL Key with <a href="/bootcamps/'.$_POST['b_url_key'].'" target="_blank">another bootcamp</a>.</span>');
	    }
	    
	    //Validate Video & Image URL:
	    if(strlen($_POST['b_video_url'])>0 && !url_exists($_POST['b_video_url'])){
	        die('<span style="color:#FF0000;">Error: <a href="'.$_POST['b_video_url'].'" target="_blank">Video URL</a> could not be verified.</span>');
	    }
	    if(strlen($_POST['b_image_url'])>0 && !url_exists($_POST['b_image_url'])){
	        die('<span style="color:#FF0000;">Error: <a href="'.$_POST['b_image_url'].'" target="_blank">Image URL</a> could not be verified.</span>');
	    }
	    
	    //Generate update array:
	    $b_update = array(
	        'b_status' => intval($_POST['b_status']),
	        'b_url_key' => $_POST['b_url_key'],
	        'b_video_url' => $_POST['b_video_url'],
	        'b_image_url' => $_POST['b_image_url'],
	    );

	    //Updatye bootcamp:
	    $this->Db_model->b_update( intval($_POST['b_id']) , $b_update);
	    
	    
	    //Log Engagement for Bootcamp Edited:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => readable_updates($bootcamps[0],$b_update,'b_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $bootcamps[0],
	            'after' => $b_update,
	        )),
	        'e_type_id' => ( $b_update['b_status']<0 && $b_update['b_status']!=$bootcamps[0]['b_status'] ? 17 : 18 ), //Bootcamp Deleted or Updated
	        'e_object_id' => intval($_POST['b_id']),
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	    ));
	    
	    
	    //Update Href for Landing page buttons:
	    echo '<script> $(".landing_page_url").attr("href", "/bootcamps/'.$_POST['b_url_key'].'"); </script>';
	    //Show result 
	    die('<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
	}
	
	
	
	
	
	
	
	
	
	/* ******************************
	 * c Intents
	 ****************************** */
	
	function intent_create(){
	    
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0 || !is_valid_intent($_POST['c_id'])){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['c_objective']) || strlen($_POST['c_objective'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Intent Objective.</span>');
	    }
	    
	    //Validate Original intent:
	    $inbound_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => intval($_POST['pid']),
	    ));
	    if(count($inbound_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid PID.</span>');
	    }
	    
	    //Validate Bootcamp ID:
	    $bootcamps = $this->Db_model->b_fetch(array(
	        'b.b_id' => intval($_POST['b_id']),
	    ));
	    if(count($bootcamps)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    }
	    
	    //Create intent:
	    $new_intent = $this->Db_model->c_create(array(
	        'c_creator_id' => $udata['u_id'],
	        'c_objective' => trim($_POST['c_objective']),
	    ));
	    
	    //Log Engagement for New Intent:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => 'Intent ['.$new_intent['c_objective'].'] created',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $new_intent,
	        )),
	        'e_type_id' => 20, //New Intent
	        'e_object_id' => $new_intent['c_id'],
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	    ));
	    
	    //Create Link:
	    $relation = $this->Db_model->cr_create(array(
	        'cr_creator_id' => $udata['u_id'],
	        'cr_inbound_id'  => intval($_POST['pid']),
	        'cr_outbound_id' => $new_intent['c_id'],
	        'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
	            'cr_status >=' => 0,
	            'cr_inbound_id' => intval($_POST['pid']),
	        )),
	    ));
	    
	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => 'Linked intent ['.$new_intent['c_objective'].'] as outbound of intent ['.$inbound_intents[0]['c_objective'].']',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $relation,
	        )),
	        'e_type_id' => 23, //New Intent Link
	        'e_object_id' => $relation['cr_id'],
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	    ));
	    
	    //Fetch full link package:
	    $relations = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_id' => $relation['cr_id'],
	    ));
	    
	    //Update Algolia:
	    $this->Db_model->sync_algolia($new_intent['c_id']);
	    
	    //Return result:
	    echo echo_cr($_POST['b_id'],$relations[0],'outbound',$_POST['next_level']);
	}
	
	
	
	function intent_edit(){
	    
	    //Auth user and check required variables:
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['c_objective']) || strlen($_POST['c_objective'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Intent Objective.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Bootcamp ID.</span>');
	    }
	    
	    //Validate Original intent:
	    $original_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => intval($_POST['pid']),
	    ));
	    if(count($original_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid PID.</span>');
	    }
	    
	    //Generate Update Array
	    $c_update = array(
	        'c_objective' => trim($_POST['c_objective']),
	        'c_todo_bible' => $_POST['c_todo_bible'],
	        'c_todo_overview' => $_POST['c_todo_overview'],
	        'c_prerequisites' => $_POST['c_prerequisites'],
	        'c_time_estimate' => floatval($_POST['c_time_estimate']),
	    );
	    
	    //Now update the DB:
	    $this->Db_model->c_update( intval($_POST['pid']) , $c_update);
	    
	    //Update Algolia:
	    $this->Db_model->sync_algolia(intval($_POST['pid']));
	    
	    
	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => readable_updates($original_intents[0],$c_update,'c_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $original_intents[0],
	            'after' => $c_update,
	        )),
	        'e_type_id' => 19, //Intent Updated
	        'e_object_id' => intval($_POST['pid']),
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	    ));
	    
	    //Show result:
	    die('<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
	}
	
	
	
	function intent_link(){
	    
	    $udata = auth(2);
	    
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['target_id']) || intval($_POST['target_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing target_id.</span>');
	    }
	    
	    //Validate outbound link:
	    $outbound_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => intval($_POST['target_id']),
	    ));
	    if(count($outbound_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid target_id.</span>');
	    }
	    
	    
	    //Validate inbound link:
	    $inbound_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => intval($_POST['pid']),
	    ));
	    if(count($inbound_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid PID.</span>');
	    }
	    
	    
	    //Check to make sure not a duplicate link:
	    $current_outbounds = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_inbound_id' => intval($_POST['pid']),
	        'cr.cr_status >=' => 0,
	    ));
	    foreach($current_outbounds as $co){
	        if($co['cr_outbound_id']==intval($_POST['target_id'])){
	            //Oops, we already have this!
	            die('<script> alert("ERROR: Cannot create this link because it already exists."); </script>');
	        }
	    }
	    
	    
	    //Create Link:
	    $relation = $this->Db_model->cr_create(array(
	        'cr_creator_id' => $udata['u_id'],
	        'cr_inbound_id'  => intval($_POST['pid']),
	        'cr_outbound_id' => intval($_POST['target_id']),
	        'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
	            'cr_status >=' => 0,
	            'cr_inbound_id' => intval($_POST['pid']),
	        )),
	    ));
	    
	    
	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => 'Linked intent ['.$outbound_intents[0]['c_objective'].'] as outbound of intent ['.$inbound_intents[0]['c_objective'].']',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $relation,
	        )),
	        'e_type_id' => 23, //New Intent Link
	        'e_object_id' => $relation['cr_id'],
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	    ));
	    
	    
	    //Fetch full OUTBOUND link package:
	    $relations = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_id' => $relation['cr_id'],
	    ));
	    
	    
	    //Return result:
	    echo echo_cr($_POST['b_id'],$relations[0],'outbound',$_POST['next_level']);
	}
	
	
	function intent_unlink(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['cr_id']) || intval($_POST['cr_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Link ID.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Bootcamp ID.</span>');
	    }
	    
	    //Vaidate Link
	    $outbound_intents = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_id' => intval($_POST['cr_id']),
	    ));
	    if(count($outbound_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Link ID.</span>');
	    }
	    
	    //Fetch inbound
	    $inbound_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => $outbound_intents[0]['cr_inbound_id'],
	    ));
	    
	    
	    //Now update the DB:
	    $cr_update = array(
	        'cr_status' => -1, //Deleted by user
	    );
	    $this->Db_model->cr_update( intval($_POST['cr_id']) , $cr_update);
	    
	    
	    //Log Engagement for Deleted Intent Link:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => 'Removed intent ['.$outbound_intents[0]['c_objective'].'] as outbound of intent ['.(isset($inbound_intents[0]['c_objective']) ? $inbound_intents[0]['c_objective'] : 'Unknown!').']',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $outbound_intents[0],
	            'after' => $cr_update,
	        )),
	        'e_type_id' => 21, //Deleted Link
	        'e_object_id' => intval($_POST['cr_id']),
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	    ));
	    
	    //Show result:
	    die('<span style="color:#00CC00;">Removed Link</span>');
	}
	
	
	
	function intents_sort(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid PID.</span>');
	    } elseif(!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort'])<=0){
	        die('<span style="color:#FF0000;">Error: Nothing passed for sorting.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Bootcamp ID.</span>');
	    }
	    
	    //Validate Parent intent:
	    $inbound_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => intval($_POST['pid']),
	    ));
	    if(count($inbound_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid PID.</span>');
	    }
	    
	    //Fetch for the record:
	    $outbounds_before = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_inbound_id' => intval($_POST['pid']),
	        'cr.cr_status >=' => 0,
	    ));
	    
	    //Update them all:
	    foreach($_POST['new_sort'] as $rank=>$cr_id){
	        $this->Db_model->cr_update( intval($cr_id) , array(
	            'cr_creator_id' => $udata['u_id'],
	            'cr_timestamp' => date("Y-m-d H:i:s"),
	            'cr_outbound_rank' => intval($rank),
	        ));
	    }
	    
	    //Fetch for the record:
	    $outbounds_after = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_inbound_id' => intval($_POST['pid']),
	        'cr.cr_status >=' => 0,
	    ));
	    
	    //Log Engagement:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => 'Sorted outbound intents for ['.$inbound_intents[0]['c_objective'].']',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $outbounds_before,
	            'after' => $outbounds_after,
	        )),
	        'e_type_id' => 22, //Links Sorted
	        'e_object_id' => intval($_POST['pid']),
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	    ));	    
        
	    //Display message:
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
	    die('<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
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