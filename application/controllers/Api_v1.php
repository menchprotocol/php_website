<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_v1 extends CI_Controller {
	
    /*
     * A hub of external micro apps transmitting data with the Mench server
     * 
     * */
	
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
	
	function load_tip(){
	    $udata = auth(2);
	    //Used to load all the help messages within the Console:
	    if(!$udata || !isset($_POST['intent_id']) || intval($_POST['intent_id'])<1){
	        echo_json(array(
	            'success' => 0,
	        ));
	    }
	    
	    //Fetch Messages and the User's Got It Engagement History:
	    $messages = $this->Db_model->i_fetch(array(
	        'i_c_id' => intval($_POST['intent_id']),
	        'i_status >=' => 0, //Published in any form. This may need more logic
	        'i_status <' => 4, //But not private notes if any
	    ));
	    
	    //Log an engagement for all messages
	    foreach($messages as $i){
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $udata['u_id'],
	            'e_json' => json_encode($i),
	            'e_type_id' => 40, //Got It
	            'e_c_id' => intval($_POST['intent_id']),
	            'e_i_id' => $i['i_id'],
	        ));
	    }
	    
	    //Build UI friendly Message:
	    $help_content = null;
	    foreach($messages as $i){
	        $help_content .= echo_i($i,$udata['u_fname']);
	    }
	    
	    //Return results:
	    echo_json(array(
	        'success' => ( $help_content ?  1 : 0 ), //No Messages perhaps!
	        'intent_id' => intval($_POST['intent_id']),
	        'help_content' => $help_content,
	    ));
	}

	
	/* ******************************
	 * Users
	 ****************************** */
	
	function funnel_progress(){
	    
	    $this->load->helper('cookie');
	    
	    if(!isset($_POST['r_id']) || intval($_POST['r_id'])<1 || !isset($_POST['current_section'])){
	        die(json_encode(array(
	            'goto_section' => 0,
	            'color' => '#FF0000',
	            'message' => '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>ERROR</b>: Invalid Class ID',
	        )));
	    }
	    
	    //Fetch inputs:
	    $current_section = intval($_POST['current_section']);
	    $classes = $this->Db_model->r_fetch(array(
	        'r.r_id' => intval($_POST['r_id']),
	        'r.r_status' => 1,
	    ));
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $classes[0]['r_b_id'],
	    ));
	    $bootcamp = $bootcamps[0];
	    $focus_class = filter_class($bootcamp['c__classes'],intval($_POST['r_id']));
	    
	    
	    //Display results:
	    if(!isset($bootcamp) || !isset($classes[0]) || $bootcamp['b_id']<1 || !($focus_class['r_id']==intval($_POST['r_id']))){
	        die(echo_json(array(
	            'goto_section' => 0,
	            'color' => '#FF0000',
	            'message' => '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>ERROR</b>: Invalid Class ID',
	        )));
	    }	    
	    
	    if($current_section==1){
	        
	        //EMAIL
	        if(!isset($_POST['u_email']) || strlen($_POST['u_email'])<1 || !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){
	            //Invalid
	            die(echo_json(array(
	                'goto_section' => 0,
	                'color' => '#FF0000',
	                'message' => '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>ERROR</b>: Invalid email, try again.',
	            )));
	        }
	        
	        
	        //Fetch user data to see if already registered:
	        $users = $this->Db_model->u_fetch(array(
	            'u_email' => strtolower($_POST['u_email']),
	        ));
	        
	        if(count($users)==0){
	            //Nope, lets continue:
	            die(echo_json(array(
	                'goto_section' => 2,
	            )));
	        }
	        
	        //This is a registered user!
	        $udata = $users[0];

	        
	        //Check their current application status(es):
	        $enrollments = $this->Db_model->ru_fetch(array(
	            'r.r_status >='	   => 1, //Open for admission
	            'r.r_status <='	   => 2, //Running
	            'ru.ru_status >='  => 0, //Initiated or higher as long as bootcamp is running!
	            'ru.ru_u_id'	   => $udata['u_id'],
	        ));
	        
	        
	        if((count($enrollments)==1 && !($enrollments[0]['ru_r_id']==$focus_class['r_id'])) || (count($enrollments)>=2)/* <-- This should never happen! */){
	            
	            //Ooops, registered in another class, must first deal with that
	            //Currently users can only enroll in one class at a time
	            //TODO Make more sophisticated to understand start & end dates and enable multiple if dates do not overlap
	            
	            //Log engagement:
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => $udata['u_id'],
	                'e_message' => 'Student attempted to enroll in a 2nd Bootcamp which is not allowed!',
	                'e_json' => json_encode($_POST),
	                'e_type_id' => 9, //Support Needing Graceful Errors
	            ));
	            
	            //Send the email to their application:
	            if(email_application_url($udata)){
	                
	                $application_status_salt = $this->config->item('application_status_salt');
	                
	                //Log Email Engagement:
	                $this->Db_model->e_create(array(
	                    'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	                    'e_message' => 'Student received email with link to their applications: https://mench.co/my/applications?u_key='.md5($udata['u_id'].$application_status_salt).'&u_id='.$udata['u_id'],
	                    'e_json' => json_encode(array(
	                        'input' => $_POST,
	                        'udata' => $udata,
	                    )),
	                    'e_type_id' => 28, //Email sent
	                ));
	                
	                //show the error:
	                die(echo_json(array(
	                    'goto_section' => 0,
	                    'color' => '#FF0000',
	                    'message' => '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>ERROR</b>: You can take 1 bootcamp at a time. We emailed you a link to manage your current bootcamps. If your other bootcamp has not yet started, you may withdraw your application and apply for this one instead.',
	                )));
	            }
	            
	            
	        } elseif(count($enrollments)==0){
	            
	            //Existing user that is never enrolled here!
	            $admission_application = array(
	                'ru_r_id' 	        => $focus_class['r_id'],
	                'ru_u_id' 	        => $udata['u_id'],
	                'ru_affiliate_u_id' => 0,
	            );
	            
	            //Lets see if they have an affiliate Cookie:
	            $aff_cookie = get_cookie('menchref');
	            if(isset($aff_cookie) && $aff_cookie && strlen($aff_cookie)>10){
	                //Seems to be something here, lets see:
	                $cookie_parts = explode('-',$aff_cookie);
	                if(intval($cookie_parts[0]) && $cookie_parts[1]==md5( intval($cookie_parts[0]) . 'c00ki3' . $cookie_parts[2] . intval($cookie_parts[3]) )){
	                    //Yes, this is a match!
	                    $admission_application['ru_affiliate_u_id'] = intval($cookie_parts[0]);
	                }
	            }
	            
	            //Lets start their admission application:
	            $enrollments[0] = $this->Db_model->ru_create($admission_application);
	            
	            //Did we have an affiliate?
	            if($admission_application['ru_affiliate_u_id']){
	                //Log Affiliate Engagement:
	                $this->Db_model->e_create(array(
	                    'e_initiator_u_id' => $udata['u_id'], //The User ID
	                    'e_message' => 'User initiated admission. Pending payment and lead instructor approval.',
	                    'e_json' => json_encode(array(
	                        'input' => $_POST,
	                        'udata' => $udata,
	                        'rudata' => $enrollments[0],
	                        'cookie' => $aff_cookie,
	                        'initial_e_id' => intval($cookie_parts[3]),
	                    )),
	                    'e_type_id' => 46, //Affiliate User Initiated Admission
	                    'e_b_id' => $bootcamp['b_id'],
	                    'e_r_id' => $focus_class['r_id'],
	                    'e_recipient_u_id' => $admission_application['ru_affiliate_u_id'], //The affiliate ID
	                ));
	            }
	            
	            //Assume all good, Log engagement:
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => $udata['u_id'],
	                'e_json' => json_encode(array(
	                    'input' => $_POST,
	                    'udata' => $udata,
	                    'rudata' => $enrollments[0],
	                )),
	                'e_type_id' => 29, //Joined Class
	                'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
	                'e_r_id' => $focus_class['r_id'],
	            ));
	            
	        }
	        	        
	        
	        if(isset($enrollments[0]['ru_id']) && $enrollments[0]['ru_id']>0){
	            //Yes they are in, lets email:
	            //Send email and log engagement:
	            if(email_application_url($udata)){
	                
	                $application_status_salt = $this->config->item('application_status_salt');
	                $u_key = md5($udata['u_id'].$application_status_salt);
	                
	                //Log Engagement:
	                $this->Db_model->e_create(array(
	                    'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	                    'e_message' => 'https://mench.co/my/applications?u_key='.$u_key.'&u_id='.$udata['u_id'],
                        'e_json' => json_encode(array(
	                        'input' => $_POST,
	                        'udata' => $udata,
	                        'rudata' => $enrollments[0],
	                    )),
	                    'e_type_id' => 28, //Email sent
	                ));
	                
	                //Redirect to application:
	                die(echo_json(array(
	                    'hard_redirect' => '/my/class_application/'.$enrollments[0]['ru_id'].'?u_key='.$u_key.'&u_id='.$udata['u_id'],
	                )));
	            }
	        }
	        
	    } elseif($current_section==2){
	        
	        if(!isset($_POST['u_fname']) || strlen($_POST['u_fname'])<2){
	            //Invalid
	            die(echo_json(array(
	                'goto_section' => 0,
	                'color' => '#FF0000',
	                'message' => '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>ERROR</b>: Invalid first name, try again.',
	            )));
	        } else {
	            //Nope, lets continue:
	            die(echo_json(array(
	                'goto_section' => 3,
	            )));
	        }
	        
	    } elseif($current_section==3){
	        
	        if(!isset($_POST['u_lname']) || strlen($_POST['u_lname'])<2){
	            
	            //Invalid
	            die(echo_json(array(
	                'goto_section' => 0,
	                'color' => '#FF0000',
	                'message' => '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>ERROR</b>: Invalid last name, try again.',
	            )));
	            
	        } else {
	            
	            //Register User:
	            $udata = $this->Db_model->u_create(array(
	                'u_fb_id' 			=> 0,
	                'u_status' 			=> 0, //Since nothing is yet validated
	                'u_language' 		=> 'en', //Since they answered initial questions in English
	                'u_email' 			=> trim($_POST['u_email']),
	                'u_fname' 			=> trim($_POST['u_fname']),
	                'u_lname' 			=> trim($_POST['u_lname']),
	            ));
	            
	            if($udata['u_id']>0){
	                
	                //Log Engagement for registration:
	                $this->Db_model->e_create(array(
	                    'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	                    'e_json' => json_encode(array(
	                        'input' => $_POST,
	                        'udata' => $udata,
	                    )),
	                    'e_type_id' => 27, //New Student Lead
	                    'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
	                    'e_r_id' => $focus_class['r_id'],
	                ));
	                
	                
	                //Existing user that is never enrolled here!
	                $admission_application = array(
	                    'ru_r_id' 	        => $focus_class['r_id'],
	                    'ru_u_id' 	        => $udata['u_id'],
	                    'ru_affiliate_u_id' => 0,
	                );
	                
	                //Lets see if they have an affiliate Cookie:
	                $aff_cookie = get_cookie('menchref');
	                if(isset($aff_cookie) && $aff_cookie && strlen($aff_cookie)>10){
	                    //Seems to be something here, lets see:
	                    $cookie_parts = explode('-',$aff_cookie);
	                    if(intval($cookie_parts[0]) && $cookie_parts[1]==md5( intval($cookie_parts[0]) . 'c00ki3' . $cookie_parts[2] . intval($cookie_parts[3]) )){
	                        //Yes, this is a match!
	                        $admission_application['ru_affiliate_u_id'] = intval($cookie_parts[0]);
	                    }
	                }
	                
	                //Insert Enrollment Status since they are new:
	                $rudata = $this->Db_model->ru_create($admission_application);
	                
	                //Did it work?
	                if($rudata['ru_id']>0){
	                    
	                    //Did we have an affiliate?
	                    if($admission_application['ru_affiliate_u_id']){
	                        //Log Affiliate Engagement:
	                        $this->Db_model->e_create(array(
	                            'e_initiator_u_id' => $udata['u_id'], //The User ID
	                            'e_message' => 'User initiated admission. Pending payment and lead instructor approval. Intial click Tracker ID is #'.intval($cookie_parts[3]),
	                            'e_json' => json_encode(array(
	                                'input' => $_POST,
	                                'udata' => $udata,
	                                'rudata' => $rudata,
	                                'initial_e_id' => intval($cookie_parts[3]),
	                                'cookie' => $aff_cookie,
	                            )),
	                            'e_type_id' => 46, //Affiliate User Initiated Admission
	                            'e_b_id' => $bootcamp['b_id'],
	                            'e_r_id' => $focus_class['r_id'],
	                            'e_recipient_u_id' => $admission_application['ru_affiliate_u_id'], //The affiliate ID
	                        ));
	                    }
	                    
	                    //Log Engagement:
	                    $this->Db_model->e_create(array(
	                        'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	                        'e_json' => json_encode(array(
	                            'input' => $_POST,
	                            'udata' => $udata,
	                            'rudata' => $rudata,
	                        )),
	                        'e_type_id' => 29, //Joined Class
	                        'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
	                        'e_r_id' => $focus_class['r_id'],
	                    ));
	                    
	                   
	                        
	                    //Send email and log engagement:
	                    if(email_application_url($udata)){
	                        //Fetch variables:
	                        $application_status_salt = $this->config->item('application_status_salt');
	                        $udata['u_key'] = md5($udata['u_id'].$application_status_salt);
	                        
	                        //Log Engagement:
	                        $this->Db_model->e_create(array(
	                            'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	                            'e_message' => 'https://mench.co/my/applications?u_key='.$udata['u_key'].'&u_id='.$udata['u_id'],
	                            'e_json' => json_encode(array(
	                                'input' => $_POST,
	                                'udata' => $udata,
	                                'rudata' => $rudata,
	                            )),
	                            'e_type_id' => 28, //Email sent
	                        ));
	                        
	                        //Redirect to application:
	                        die(echo_json(array(
	                            'hard_redirect' => '/my/class_application/'.$rudata['ru_id'].'?u_key='.$udata['u_key'].'&u_id='.$udata['u_id'],
	                        )));
	                    }
	                }
	            }
	        }
	    }
	    
	    
	    //Ooops, what happened?
	    die(echo_json(array(
	        'goto_section' => 0,
	        'color' => '#FF0000',
	        'message' => '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <b>ERROR</b>: Unknown error, try again.',
	    )));
	}
	
	
	//When they submit the application in step 2 of their admission:
	function submit_application(){
	    
	    $application_status_salt = $this->config->item('application_status_salt');
	    if(intval($_POST['ru_id'])<1 || !isset($_POST['u_key']) || !isset($_POST['answers']) || !isset($_POST['u_id']) || intval($_POST['u_id'])<1 || !(md5($_POST['u_id'].$application_status_salt)==$_POST['u_key'])){
	        
	        //Log engagement:
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => ( isset($_POST['u_id']) ? intval($_POST['u_id']) : 0 ),
	            'e_message' => 'submit_application() Missing Core Inputs.',
	            'e_json' => json_encode($_POST),
	            'e_type_id' => 8, //Platform Error
	        ));
	        
	        //Display Error:
	        die('<span style="color:#FF0000;">Error: Missing Core Inputs. Report Logged for Admin to review.</span>');
	    }
	    
	    
	    //Fetch all their admissions:
	    $admissions = $this->Db_model->remix_admissions(array(
	        'ru.ru_id'	=> intval($_POST['ru_id']),
	    ));
	    
	    //Make sure we got all this data:
	    if(!(count($admissions)==1) || !isset($admissions[0]['r_id']) || !isset($admissions[0]['b_id'])){
	        //Log this error:
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $_POST['u_id'],
	            'e_message' => 'submit_application() failed to fetch admission data.',
	            'e_json' => json_encode($_POST),
	            'e_type_id' => 8, //Platform Error
	        ));
	        
	        //Error:
	        die('<span style="color:#FF0000;">Error: Failed to fetch admission data. Report Logged for Admin to review.</span>');
	    }
	    
	    //Attach timestamp:
	    $_POST['answers']['pst_timestamp'] = date("Y-m-d H:i:s");
	    
	    //Save answers:
	    $this->Db_model->ru_update( intval($_POST['ru_id']) , array(
	        'ru_application_survey' => json_encode($_POST['answers']),
	    ));
	    
	    //Log Engagement:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $_POST['u_id'],
	        'e_json' => json_encode($_POST),
	        'e_type_id' => 26, //Application submitted
	        'e_b_id' => $admissions[0]['b_id'], //Share with bootcamp team
	        'e_r_id' => $admissions[0]['r_id'],
	    ));
	    
	    //We're good now, lets redirect to application status page and MAYBE send them to paypal asap:
	    //The "pay_r_id" variable makes the next page redirect to paypal automatically:
	    //Show message & redirect:
	    sleep(1);
	    echo '<script> setTimeout(function() { window.location = "/my/applications?pay_r_id='.$admissions[0]['r_id'].'&u_key='.$_POST['u_key'].'&u_id='.$_POST['u_id'].'" }, 1000); </script>';
	    echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span><div>Successfully Submitted!</div>';
	}
	
	
	function login(){
	    
	    //Setting for admin logins:
	    $master_password = 'ma5terpa55';
	    
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
	            'e_initiator_u_id' => $users[0]['u_id'],
	            'e_message' => 'login() denied because account is not active.',
	            'e_json' => json_encode($_POST),
	            'e_type_id' => 9, //Support Needing Graceful Errors
	        ));
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Your account has been de-activated. Contact us for more details.</div>');
	        return false;
	    } elseif(!($_POST['u_password']==$master_password) && !($users[0]['u_password']==md5($_POST['u_password']))){
	        //Bad password
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
                    'e_initiator_u_id' => $users[0]['u_id'],
                    'e_message' => 'login() denied because user is not assigned to any bootcamps.',
                    'e_json' => json_encode($_POST),
                    'e_type_id' => 9, //Support Needing Graceful Errors
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
        if(!($_POST['u_password']==$master_password)){
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $users[0]['u_id'],
                'e_json' => json_encode($users[0]),
                'e_type_id' => 10, //Admin login
            ));
        }
            
        
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
	        'e_initiator_u_id' => ( isset($udata['u_id']) && $udata['u_id']>0 ? $udata['u_id'] : 0 ),
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
	    } elseif(strlen($_POST['u_image_url'])>0 && (!filter_var($_POST['u_image_url'], FILTER_VALIDATE_URL) || substr($_POST['u_image_url'],0,8)!=='https://' || !url_exists($_POST['u_image_url']))){
	        die('<span style="color:#FF0000;">Error: Invalid HTTPS profile picture url. Try again.</span>');
	    } elseif(strlen($_POST['u_bio'])>420){
	        die('<span style="color:#FF0000;">Error: Introductory Message should be less than 420 characters. Try again.</span>');
	    }
	    
	    if(!isset($_POST['u_language'])){
	        $_POST['u_language'] = array();
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
	    
	    
	    //Did they just agree to the agreement?
	    if(isset($_POST['u_newly_checked']) && intval($_POST['u_newly_checked']) && strlen($u_current[0]['u_terms_agreement_time'])<1){
	        //Yes they did, save the timestamp:
	        $u_update['u_terms_agreement_time'] = date("Y-m-d H:i:s");
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
	        'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	        'e_message' => readable_updates($u_current[0],$u_update,'u_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $u_current[0],
	            'after' => $u_update,
	        )),
	        'e_type_id' => 12, //Account Update
	        'e_recipient_u_id' => intval($_POST['u_id']), //The user that their account was updated
	    ));
	    
	    //TODO update algolia
	    
	    //Show result:
	    echo ( $warning ? '<span style="color:#FF8C00;">Saved all except: '.$warning.'</span>' : '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
	}
	
	
	/* ******************************
	 * r Classes
	 ****************************** */
	
	function tuition_calculator(){
	    //Displays the class timeline based on some inputs:
	    if(!isset($_POST['r_id']) || !isset($_POST['b_id']) || !isset($_POST['r_response_time_hours']) || !isset($_POST['r_meeting_frequency']) || !isset($_POST['r_meeting_duration']) || !isset($_POST['b_sprint_unit']) || !isset($_POST['b_effective_milestones']) || !isset($_POST['c__estimated_hours'])){
	        die('<span style="color:#FF0000;">Missing core data: '.print_r($_POST,ture).'</span>');
	    }
	    
	    
	    //Set standards for Tuition Calculator:
	    $calculator_logic = array(
	        'base_usd_price' => 11400, //Standard price in the coding bootcamp industry
	        'target_savings' => 0.5, //How much Mench plans to be cheaper because we're fully online
	        'pricing_factors' => array(
	            'handson_work' => array(
	                'weight' => 0.35, //The percentage of importance for this factor relative to other pricing_factors
	                'name' => 'Student Assignments',
	                'desc' => 'Total hours students must spend to complete all bootcamp tasks. Note: Different bootcamps have different durations based on their delivery speed of full-time (40h/week) or part-time (10h/week).',
	                'industry_is' => 600, //How much time students need to spend to complete the bootcamp
	                'mench_is' => 0, //To be calculated
	                'mench_what_if' => ( isset($_POST['whatif_handson_work']) && intval($_POST['whatif_handson_work'])>0 ? intval($_POST['whatif_handson_work']) : null ),
	            ),
	            'personalized_mentorship' => array(
	                'weight' => 0.35, //The percentage of importance for this factor relative to other pricing_factors
	                'name' => '1-on-1 Mentorship',
	                'desc' => 'The sum of all 1-on-1 mentorship offered during the entire bootcamp',
	                'industry_is' => 72,
	                'mench_is' => 0, //To be calculated
	                'mench_what_if' => ( isset($_POST['whatif_personalized_mentorship']) && intval($_POST['whatif_personalized_mentorship'])>0 ? intval($_POST['whatif_personalized_mentorship']) : null ),
	            ),
	            'respond_under' => array(
	                'weight' => 0.20, //The percentage of importance for this factor relative to other pricing_factors
	                'name' => 'Response Time',
	                'desc' => 'The average response time of the bootcamp team to student inquiries',
	                'industry_is' => 2, //How fast (in hours) are they committing to respond
	                'mench_is' => 0, //To be calculated
	                'mench_what_if' => ( isset($_POST['whatif_respond_under']) && intval($_POST['whatif_respond_under'])>0 ? intval($_POST['whatif_respond_under']) : null ),
	            ),
	            'weekly_office_hours' => array(
	                'weight' => 0.10, //The percentage of importance for this factor relative to other pricing_factors
	                'name' => 'Weekly Office Hours',
	                'desc' => 'The total number of weekly office hours available that students can ask questions and get instant answers',
	                'industry_is' => 40,
	                'mench_is' => 0, //To be calculated
	                'mench_what_if' => ( isset($_POST['whatif_weekly_office_hours']) && intval($_POST['whatif_weekly_office_hours'])>0 ? intval($_POST['whatif_weekly_office_hours']) : null ),
	            ),
	        ),
	    );
	    
	    
	    //Check to make sure we have enough data to offer a suggestion:
	    $missing_preq = array();
	    if(strlen($_POST['r_response_time_hours'])<=0){
	        array_push($missing_preq,'Set Response Time');
	    }
	    if(strlen($_POST['r_meeting_frequency'])<=0 || strlen($_POST['r_meeting_duration'])<=0){
	        array_push($missing_preq,'Set 1-on-1 Mentorship');
	    }
	    if($_POST['b_effective_milestones']<=0){
	        array_push($missing_preq,'Add some Milestones to your Action Plan');
	    }
	    
	    
	    //Start calculations:
	    if(count($missing_preq)>0){
	        
	        echo '<p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> The calculator can make a suggestion after you:</p>';
	        echo '<ul style="list-style:decimal;">';
	        foreach($missing_preq as $mp){
	            echo '<li>'.$mp.'</li>';
	        }
	        echo '</ul>';
	        
	    } else {
	        
	        //Fetch and calculate office hours:
	        $current_classes = $this->Db_model->r_fetch(array(
	            'r.r_id' => intval($_POST['r_id']),
	        ));
	        if(!(count($current_classes)==1)){
	            die('<span style="color:#FF0000;">Invalid Class ID</span>');
	        }
	        
	        
	        //Calculate total office hours:
	        $focus_class = $current_classes[0];
	        if(strlen($focus_class['r_live_office_hours'])>0 && is_array(unserialize($focus_class['r_live_office_hours']))){
	            foreach (unserialize($focus_class['r_live_office_hours']) as $key=>$oa){
	                if(isset($oa['periods']) && count($oa['periods'])>0){
	                    //Yes we have somehours for this day:
	                    foreach($oa['periods'] as $period){
	                        //Calculate hours for this period:
	                        $calculator_logic['pricing_factors']['weekly_office_hours']['mench_is'] += (hourformat($period[1]) - hourformat($period[0]));
	                    }
	                }
	            }
	        }
	        
	        
	        //Calculate remaining elements:
	        $c__estimated_hours = intval($_POST['c__estimated_hours']);
	        $whatif_selected = ( isset($_POST['whatif_selection']) && intval($_POST['whatif_selection'])>0 ? intval($_POST['whatif_selection']) : null );
	        $calculator_logic['pricing_factors']['personalized_mentorship']['mench_is'] = gross_mentorship($_POST['r_meeting_frequency'],$_POST['r_meeting_duration'],$_POST['b_sprint_unit'],$_POST['b_effective_milestones'],false);
	        $calculator_logic['pricing_factors']['respond_under']['mench_is'] = $_POST['r_response_time_hours'];
	        $calculator_logic['pricing_factors']['handson_work']['mench_is'] = ( $whatif_selected ? $whatif_selected : $c__estimated_hours );
	        
	        
	        $whatif_handson_work = array(25,50,100,150,200,300,400,500,600);
	        if(!in_array($c__estimated_hours,$whatif_handson_work)){
	            array_push($whatif_handson_work,$c__estimated_hours);
	            asort($whatif_handson_work);
	        }
	        
	        
	        //Show details to students:
	        echo '<table class="table table-condensed" style="margin-top:-40px;">';
    	        echo '<tr>';
        	        echo '<td style="border-bottom:1px solid #999;">&nbsp;</td>';
        	        echo '<td style="border-bottom:1px solid #999; width:120px; text-align:right;">Traditional<br />Bootcamps</td>';
        	        echo '<td style="border-bottom:1px solid #999; width:200px; text-align:right;">Your Mench<br />Bootcamp</td>';
    	        echo '</tr>';
    	        
    	        //First row on Duration:
    	        /*
    	        echo '<tr>';
        	        echo '<td style="text-align:left;"><span style="width:220px; display: inline-block;" data-toggle="tooltip" title="The amount of time it takes students to accomplish the Bootcamp Objective" data-placement="top"><i class="fa fa-info-circle" aria-hidden="true"></i> Bootcamp Duration</span></td>';
        	        echo '<td style="text-align:right;">24 Weeks</td>';
        	        echo '<td style="text-align:right;">'.$_POST['b_effective_milestones'].' '.ucwords($_POST['b_sprint_unit']).($_POST['b_effective_milestones']==1?'':'s').'</td>';
        	    echo '</tr>';
    	        */
    	        
    	        //Show each item of the calculation:
    	        $equalized_mench_price = $calculator_logic['target_savings'] * $calculator_logic['base_usd_price'];
    	        $suggested_price = 0;
    	        $suggested_mench_price = 0;
    	        foreach($calculator_logic['pricing_factors'] as $item=>$pf){
    	            if($item=='respond_under'){
    	                //This is calculated a bit differently:
    	                $mench_price = ( $pf['weight'] * $equalized_mench_price * ( ( $pf['mench_is']<=2 ? 1 : ($pf['industry_is']/$pf['mench_is']) ) ) );
    	            } else {
    	                $mench_price = ( $pf['weight'] * $equalized_mench_price * ( $pf['mench_is']/$pf['industry_is'] ) );
    	            }
    	            
    	            $suggested_mench_price += $mench_price;
    	            
    	            echo '<tr>';
    	                echo '<td style="text-align:left;"><span style="width:220px; display: inline-block;" data-toggle="tooltip" title="'.$pf['desc'].'" data-placement="top"><i class="fa fa-info-circle" aria-hidden="true"></i> '.$pf['name'].'</span></td>';
    	                echo '<td style="text-align:right;">'.($item=='respond_under'?'Under ':'').$pf['industry_is'].' Hours</td>';
    	                echo '<td style="text-align:right;">';
    	                if($item=='handson_work'){
    	                    echo '<select id="whatif_selection" style="padding:0 !important; font-size: 18px !important; border-top:0;" data-toggle="tooltip" title="It takes time to build your Action Plan and estimate the completion time of all its tasks. This feature enables you to get a price estimate by forecasting how many hours your Action Plan would be." data-placement="top">';
    	                    foreach($whatif_handson_work as $whw){
    	                        if($whw<$c__estimated_hours){
    	                            continue; //Only encourage them to go higher, not lower!
    	                        }
    	                        echo '<option value="'.$whw.'" '.( $pf['mench_is']==$whw ? 'selected="selected"' : '' ).'>'.( $whw==$c__estimated_hours ? 'Current: ' : 'What If: ' ).$whw.' Hour'.($whw==1?'':'s').'</option>';
    	                    }
    	                    echo '</select>';
    	                } else {
    	                    echo '<a style="text-decoration:none;" href="javascript:switch_to(\'support\')">'.($item=='respond_under'?'Under ':'').$pf['mench_is'].' Hour'.( $pf['mench_is']==1?'':'s' ).' <i class="fa fa-wrench" aria-hidden="true"></i></a></td>';
    	                }
    	            echo '</tr>';
    	        }
    	        
    	        //Show pricing:
    	        $price_range = ( $suggested_mench_price<1000 ? 0.002 : 0.001 );
    	        echo '<tr>';
        	        echo '<td style="border-top:1px solid #999; text-align:left; padding-left:19px;">USD Price Suggestion</td>';
        	        echo '<td style="border-top:1px solid #999; text-align:right;"><span data-toggle="tooltip" title="This is how much an average bootcamp costs based on 2017 statistics generated for the coding industry based on 95 bootcamps and 22k students across the world" data-placement="top"><i class="fa fa-info-circle" aria-hidden="true"></i> $'.number_format($calculator_logic['base_usd_price'],0).'</span></td>';
        	        echo '<td style="border-top:1px solid #999; text-align:right; font-weight:bold;"><span data-toggle="tooltip" title="This is the price range we suggest based on your bootcamp settings. Also consider that we have adjusted this pricing suggestion to '.(($calculator_logic['target_savings'])*100).'% of Traditional Bootcamps because Online Bootcamps save on rent and hydro." data-placement="top"><i class="fa fa-info-circle" aria-hidden="true"></i> $'.number_format(round($suggested_mench_price*(0.01-$price_range))*100,0).'<span style="padding:0 3px;">-</span>$'.number_format(round($suggested_mench_price*(0.01+$price_range))*100,0).'</span></td>';
    	        echo '</tr>';
    	        
	        echo '</table>';
	        
	        //TODO Give macro potential in the future:
	        //echo '<p>Class Earning Potential: <b>22 Seats @ USD $15,000 - $22,000</b></p>';
	    }
    	    
	    
	}
	function class_timeline(){
	    //Displays the class timeline based on some inputs:
	    if(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
	        die('<span style="color:#000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pick a start date to see class timeline.</span>');
	    } elseif(!isset($_POST['r_start_time_mins'])){
	        die('<span style="color:#000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pick a start time to see class timeline.</span>');
	    } elseif(!isset($_POST['c__child_intent_count']) || intval($_POST['c__child_intent_count'])<=0){
	        die('<span style="color:#000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Add some Milestones to your Action Plan to see class timeline.</span>');
	    } elseif(!isset($_POST['b_sprint_unit'])){
	        die('<span style="color:#FF0000;">Error: Missing Milestone Submission Frequency.</span>');
	    } elseif(!isset($_POST['b_id'])){
	        die('<span style="color:#FF0000;">Error: Missing Bootcamp ID.</span>');
	    } elseif(!isset($_POST['b_status'])){
	        die('<span style="color:#FF0000;">Error: Missing Bootcamp Status.</span>');
	    }
	    
	    $_POST['c__child_intent_count'] = intval($_POST['c__child_intent_count']);
	    //Incldue config variables:
        $sprint_units = $this->config->item('sprint_units');
        $start_times = $this->config->item('start_times');
	    
	    //Start calculations:
        echo '<p>Based on this start time, your class timeline is:</p>';
        echo '<ul style="list-style:decimal;">';
            echo '<li>Admissions Starts: <b>'.(intval($_POST['b_status'])>=2?'ASAP':'When Bootcamp is '.status_bible('b',2)).'</b></li>';
	        echo '<li>Admission Ends: <b>'.time_format($_POST['r_start_date'],2,-1).' 11:59pm PST</b></li>';
	        echo '<li>Class Starts: <b>'.time_format($_POST['r_start_date'],2).' '.$start_times[$_POST['r_start_time_mins']].' PST</b></li>';
	        echo '<li>Instant Payout by: <b>'.time_format($_POST['r_start_date'],2).' 6:00pm PST</b> <a href="https://support.mench.co/hc/en-us/articles/115002473111" title="Learn more about Mench Payouts" target="_blank"><i class="fa fa-info-circle" aria-hidden="true"></i></a></li>';
	        echo '<li>Bootcamp Duration: <b>'.$_POST['c__child_intent_count'].' '.ucwords($_POST['b_sprint_unit']).'s <a href="/console/'.$_POST['b_id'].'/actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</a></b></li>';
	        echo '<li>Class Ends: <b>'.time_format($_POST['r_start_date'],2,(calculate_duration(array('b_sprint_unit'=>$_POST['b_sprint_unit']),$_POST['c__child_intent_count']))).' '.$start_times[$_POST['r_start_time_mins']].' PST</b></li>';
    	    echo '<li>Performance Payout by: <b>'.time_format($_POST['r_start_date'],2,(calculate_duration(array('b_sprint_unit'=>$_POST['b_sprint_unit']),$_POST['c__child_intent_count'])+13)).' 6:00pm PST</b> <a href="https://support.mench.co/hc/en-us/articles/115002473111" title="Learn more about Mench Payouts" target="_blank"><i class="fa fa-info-circle" aria-hidden="true"></i></a></li>';
    	    echo '</ul>';
	}
	
	
	function r_create(){
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
	        die('<span style="color:#FF0000;">Error: Enter valid start date.</span>');
	    } elseif(!isset($_POST['r_start_time_mins'])){
	        die('<span style="color:#FF0000;">Error: Enter valid start time.</span>');
	    } elseif(!isset($_POST['r_b_id']) || intval($_POST['r_b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid bootcamp ID.</span>');
	    } elseif(!isset($_POST['r_status'])){
	        die('<span style="color:#FF0000;">Error: Invalid class status.</span>');
	    } elseif(!isset($_POST['copy_r_id']) || intval($_POST['copy_r_id'])<0){
	        die('<span style="color:#FF0000;">Error: Missing Class Import Settings.</span>');
	    } else {
	        
	        //Start with the date:
	        $new_date = date("Y-m-d",strtotime($_POST['r_start_date']));
	        //Check for unique start date:
	        $current_classes = $this->Db_model->r_fetch(array(
	            'r.r_b_id' => intval($_POST['r_b_id']),
	            'r.r_status >=' => 0,
	            'r.r_start_date' => $new_date,
	        ));
	        if(count($current_classes)>0){
	            //Ooops, we cannot have duplicate dates:
	            die('<span style="color:#FF0000;">Error: Cannot have a two classes starting on the same day.</span>');
	        }
	        
	        
	        //This is the variable we're building:
	        $class_data = null;
	        
	        if(intval($_POST['copy_r_id'])>0){
	            
	            //Fetch default questions:
	            $classes = $this->Db_model->r_fetch(array(
	                'r.r_id' => intval($_POST['copy_r_id']),
	                'r.r_b_id' => intval($_POST['r_b_id']),
	            ));
	            
	            if(count($classes)==1){
	                //Port the settings:
	                $class_data = $classes[0];
	                $eng_message = time_format($_POST['r_start_date'],1).' class created by copying '.time_format($class_data['r_start_date'],1).' class settings.';
	                
	                //Make some adjustments
	                $class_data['r_start_date'] = $new_date;
	                $class_data['r_start_time_mins'] = intval($_POST['r_start_time_mins']);
	                $class_data['r_status'] = intval($_POST['r_status']); //Override with input status
	                
	                //The following data are unique and should NOT be copied:
	                unset($class_data['r_id']);
	                unset($class_data['r__current_admissions']); //This is a dummy placeholder
	            }
	        }
	        
	        
	        //Did we build it?
	        if(!$class_data){
	            
	            //Fetch default questions:
	            $default_class_questions = $this->config->item('default_class_questions');
	            $default_class_prerequisites = $this->config->item('default_class_prerequisites');
	            $default_class_prizes = $this->config->item('default_class_prizes');
	            
	            //Generate core data:
	            $class_data = array(
	                'r_b_id' => intval($_POST['r_b_id']),
	                'r_start_date' => date("Y-m-d",strtotime($_POST['r_start_date'])),
	                'r_start_time_mins' => intval($_POST['r_start_time_mins']),
	                'r_status' => intval($_POST['r_status']),
	                'r_prerequisites' => json_encode($default_class_prerequisites),
	                'r_application_questions' => json_encode($default_class_questions),
	                'r_completion_prizes' => json_encode($default_class_prizes),
	            );
	            
	            //Default message:
	            $eng_message = time_format($_POST['r_start_date'],1).' class created form scratch.';
	        }
	        
	        
	        //Create new class:
	        $class = $this->Db_model->r_create($class_data);
	        
	        
	        //Log engagement:
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	            'e_message' => $eng_message,
	            'e_json' => json_encode(array(
	                'input' => $_POST,
	                'before' => array(),
	                'after' => $class,
	            )),
	            'e_type_id' => 14, //New Class
	            'e_b_id' => intval($_POST['r_b_id']), //Share with bootcamp team
	            'e_r_id' => $class['r_id'],
	        ));
	        
	        
	        if($class['r_id']>0){
	            //Redirect:
	            echo '<script> window.location = "/console/'.intval($_POST['r_b_id']).'/classes/'.$class['r_id'].'" </script>';
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
	        die('<span style="color:#FF0000;">Error: Invalid Class ID.</span>');
	    }
	    
	    //Fetch for the record:
	    $classes = $this->Db_model->r_fetch(array(
	        'r.r_id' => intval($_POST['r_id']),
	    ));
	    if(count($classes)<1){
	        //Ooops, not found!
	        die('<span style="color:#FF0000;">Error: Class ID not found.</span>');
	    }
	    
	    
	    $blank_template = 'a:7:{i:0;a:1:{s:3:"day";s:1:"0";}i:1;a:1:{s:3:"day";s:1:"1";}i:2;a:1:{s:3:"day";s:1:"2";}i:3;a:1:{s:3:"day";s:1:"3";}i:4;a:1:{s:3:"day";s:1:"4";}i:5;a:1:{s:3:"day";s:1:"5";}i:6;a:1:{s:3:"day";s:1:"6";}}';
	    $prepped_input = trim(serialize($_POST['hours']));
	    $r_update = array(
	        'r_live_office_hours' => ( strlen($prepped_input)>0 && !($prepped_input==$blank_template) ? $prepped_input : null ),
	    );
	    $this->Db_model->r_update( intval($_POST['r_id']) , $r_update);
	    
	    //Log engagement ONLY if different:
	    if($r_update['r_live_office_hours']!==$classes[0]['r_live_office_hours']){
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $udata['u_id'], //The user
	            'e_message' => readable_updates($classes[0],$r_update,'r_'),
	            'e_json' => json_encode(array(
	                'input' => $_POST,
	                'before' => @unserialize($classes[0]['r_live_office_hours']),
	                'after' => @unserialize($r_update['r_live_office_hours']),
	            )),
	            'e_type_id' => 24, //Class Schedule Update
	            'e_b_id' => $classes[0]['r_b_id'], //Share with bootcamp team
	            'e_r_id' => intval($_POST['r_id']),
	        ));
	    }
	    
	    //Show result:
	    die('<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
	}
	
	
	
	function class_edit(){
	    
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
	        //TODO make sure its monday
	        die('<span style="color:#FF0000;">Error: Enter valid start date.</span>');
	    } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Class ID.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['r_status'])){
	        die('<span style="color:#FF0000;">Error: Missing Class Status.</span>');
	    } elseif(strlen($_POST['r_office_hour_instructions'])>420){
	        die('<span style="color:#FF0000;">Error: Contact Instructions Message must be less than 420 characters long.</span>');
	    } elseif(strlen($_POST['r_closed_dates'])>420){
	        die('<span style="color:#FF0000;">Error: Close Dates Message must be less than 420 characters long.</span>');
	    }
	    
	    //Check Duplicate Date:
	    $new_date = date("Y-m-d",strtotime($_POST['r_start_date']));
	    //Check for unique start date:
	    $current_classes = $this->Db_model->r_fetch(array(
	        'r.r_id !=' => intval($_POST['r_id']),
	        'r.r_b_id' => intval($_POST['b_id']),
	        'r.r_status >=' => 0,
	        'r.r_start_date' => $new_date,
	    ));
	    if(count($current_classes)>0){
	        //Ooops, we cannot have duplicate dates:
	        die('<span style="color:#FF0000;">Error: Cannot have a two classes starting on the same day.</span>');
	    }
	    
	    
	    //Fetch for the record:
	    $classes = $this->Db_model->r_fetch(array(
	        'r.r_id' => intval($_POST['r_id']),
	    ));
	    if(count($classes)<1){
	        //Ooops, not found!
	        die('<span style="color:#FF0000;">Error: Class ID not found.</span>');
	    }
	    
	    
	    //Fetch config variables for checking:
	    $refund_policies = $this->config->item('refund_policies');
	    $r_response_options = $this->config->item('r_response_options');
	    $r_meeting_durations = $this->config->item('r_meeting_duration');
	    $r_meeting_frquencies = $this->config->item('r_meeting_frequency');
	    $start_times = $this->config->item('start_times');	    
	    
	    $r_update = array(
	        'r_start_date' => $new_date,
	        'r_start_time_mins' => ( array_key_exists(intval($_POST['r_start_time_mins']),$start_times) ? intval($_POST['r_start_time_mins']) : null ),
	        'r_status' => intval($_POST['r_status']),
	        'r_response_time_hours' => ( in_array(floatval($_POST['r_response_time_hours']),$r_response_options) ? floatval($_POST['r_response_time_hours']) : null ),
	        'r_meeting_frequency' => ( strlen($_POST['r_meeting_frequency'])>0 && array_key_exists($_POST['r_meeting_frequency'],$r_meeting_frquencies) ? $_POST['r_meeting_frequency'].'' : null ),
	        'r_meeting_duration' => ( strlen($_POST['r_meeting_duration'])>0 && in_array(floatval($_POST['r_meeting_duration']),$r_meeting_durations) ? floatval($_POST['r_meeting_duration']) : null ),
	        'r_office_hour_instructions' => ( strlen($_POST['r_office_hour_instructions'])>0 ? trim($_POST['r_office_hour_instructions']) : null ),
	        'r_cancellation_policy' => ( isset($_POST['r_cancellation_policy']) && array_key_exists($_POST['r_cancellation_policy'],$refund_policies) ? $_POST['r_cancellation_policy'] : null ),
	        'r_closed_dates' => ( strlen($_POST['r_closed_dates'])>0 ? trim($_POST['r_closed_dates']) : null ),
	        'r_fb_pixel_id' => ( strlen($_POST['r_fb_pixel_id'])>0 ? bigintval($_POST['r_fb_pixel_id']) : 0 ),
	        'r_usd_price' => ( strlen($_POST['r_usd_price'])>0 && floatval($_POST['r_usd_price'])>=0 ? floatval($_POST['r_usd_price']) : null ),
	        'r_min_students' => intval($_POST['r_min_students']),
	        'r_max_students' => ( strlen($_POST['r_max_students'])>0 && intval($_POST['r_max_students'])>=0 ? intval($_POST['r_max_students']) : null ),
	        //List items:
	        'r_application_questions' => ( isset($_POST['r_application_questions']) && is_array($_POST['r_application_questions']) && count($_POST['r_application_questions'])>0 ? json_encode($_POST['r_application_questions']) : null ),
	        'r_prerequisites' => ( isset($_POST['r_prerequisites']) && is_array($_POST['r_prerequisites']) && count($_POST['r_prerequisites'])>0 ? json_encode($_POST['r_prerequisites']) : null ),
	        'r_completion_prizes' => ( isset($_POST['r_completion_prizes']) && is_array($_POST['r_completion_prizes']) && count($_POST['r_completion_prizes'])>0 ? json_encode($_POST['r_completion_prizes']) : null ),
	    );
	    
	    if(isset($_POST['r_live_office_hours_check']) && !intval($_POST['r_live_office_hours_check'])){
	        //User the office schedule off, lets disable it:
	        $r_update['r_live_office_hours'] = null;
	    }	    
	    
	    //Save
	    $this->Db_model->r_update( intval($_POST['r_id']) , $r_update);
	    
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'], //The user
	        'e_message' => readable_updates($classes[0],$r_update,'r_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $classes[0],
	            'after' => $r_update,
	        )),
	        'e_type_id' => ($r_update['r_status']<0 && $r_update['r_status']!=$classes[0]['r_status'] ? 16 : 13), //Class Setting Updated/Deleted
	        'e_b_id' => $classes[0]['r_b_id'], //Share with bootcamp team
	        'e_r_id' => intval($_POST['r_id']),
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
	    } elseif(!isset($_POST['c_objective']) || strlen($_POST['c_objective'])<2){
	        die('<span style="color:#FF0000;">Error: Primary objective must be 2 characters or longer.</span>');
	    } elseif(!isset($_POST['b_sprint_unit']) || !in_array($_POST['b_sprint_unit'],array('week','day'))){
	        die('<span style="color:#FF0000;">Error: Invalid milestone submission frequency.</span>');
	    }
	        
        //Create new intent:
        $intent = $this->Db_model->c_create(array(
            'c_creator_id' => $udata['u_id'],
            'c_objective' => trim($_POST['c_objective']),
        ));
        if(intval($intent['c_id'])<=0){
            //Log this error:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $udata['u_id'],
                'e_message' => 'bootcamp_create() Function failed to create intent ['.$_POST['c_objective'].'].',
                'e_json' => json_encode($_POST),
                'e_type_id' => 8, //Platform Error
            ));
            //Display error:
            die('<span style="color:#FF0000;">Error: Unkown error while trying to create intent.</span>');
        }
        
        
        //Generaye URL Key:
        //Cleans text:
        $generated_key = generate_hashtag($_POST['c_objective']);
        
        
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
            'b_sprint_unit' => $_POST['b_sprint_unit'],
            'b_c_id' => $intent['c_id'],
        ));
        if(intval($bootcamp['b_id'])<=0){
            //Log this error:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $udata['u_id'],
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
                'e_initiator_u_id' => $udata['u_id'],
                'e_message' => 'bootcamp_create() Function failed to grant permission for bootcamp #'.$bootcamp['b_id'],
                'e_json' => json_encode($_POST),
                'e_type_id' => 8, //Platform Error
            ));
            die('<span style="color:#FF0000;">Error: Unkown error while trying to set bootcamp leader.</span>');
        }

        
        //Log Engagement for Intent Created:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $udata['u_id'],
            'e_message' => '['.$intent['c_objective'].'] created as a new intent',
            'e_json' => json_encode(array(
                'input' => $_POST,
                'before' => null,
                'after' => $intent,
            )),
            'e_type_id' => 20, //Intent Created
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
            'e_c_id' => $intent['c_id'],
        ));
        
        
        //Log Engagement for Bootcamp Created:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $udata['u_id'],
            'e_message' => 'Bootcamp #'.$bootcamp['b_id'].' created for ['.$intent['c_objective'].'] intent',
            'e_json' => json_encode(array(
                'input' => $_POST,
                'before' => null,
                'after' => $bootcamp,
            )),
            'e_type_id' => 15, //Bootcamp Created
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
        ));
        
        
        //Log Engagement for Permission Granted:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $udata['u_id'],
            'e_message' => $udata['u_fname'].' '.$udata['u_lname'].' assigned as Bootcamp Leader',
            'e_json' => json_encode(array(
                'input' => $_POST,
                'before' => null,
                'after' => $admin_status,
            )),
            'e_type_id' => 25, //Permission Granted
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
        ));
        
        
        //Show message & redirect:
        //For fancy UI to give impression of hard work:
        sleep(2);
        echo '<script> setTimeout(function() { window.location = "/console/'.$bootcamp['b_id'].'" }, 1000); </script>';
        echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span><div>Going to Bootcamp Dashboard...</div>';
	}
	
	
	function bootcamp_edit(){
	    //Auth user and check required variables:
	    $udata = auth(2);
	    $reserved_hashtags = $this->config->item('reserved_hashtags');
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
	    
	    //Check hashtag:
	    if(in_array(strtolower($_POST['b_url_key']),$reserved_hashtags)){
	        die('<span style="color:#FF0000;">Error: You cannot use "'.$_POST['b_url_key'].'" as hashtag.</span>');
	    } elseif(strlen($_POST['b_url_key'])>30){
	        die('<span style="color:#FF0000;">Error: Hashtags should be less than 30 characters long.</span>');
	    } elseif(strlen($_POST['b_url_key'])<5){
	        die('<span style="color:#FF0000;">Error: Hashtags should be at least 5 characters long.</span>');
	    } elseif(!(strtolower(generate_hashtag($_POST['b_url_key']))==strtolower($_POST['b_url_key']))){
	        die('<span style="color:#FF0000;">Error: Hashtags can only include letters a-z and numbers 0-9.</span>');
	    }
	    //Validate Hashtag to be unique:
	    $duplicate_bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_url_key' => $_POST['b_url_key'],
	        'b.b_id !=' => intval($_POST['b_id']),
	    ));
	    if(count($duplicate_bootcamps)>0){
	        //Ooops, we have a duplicate:
	        die('<span style="color:#FF0000;">Error: Hashtag <a href="/'.$_POST['b_url_key'].'" target="_blank">#'.$_POST['b_url_key'].'</a> already taken.</span>');
	    }
	    
	    //Generate update array for the bootcamp:
	    $b_update = array(
	        'b_status' => intval($_POST['b_status']),
	        'b_url_key' => $_POST['b_url_key'],
	        'b_category_id' => intval($_POST['b_category_id']),
	    );

	    //Updatye bootcamp:
	    $this->Db_model->b_update( intval($_POST['b_id']) , $b_update);
	    
	    
	    //Log Engagement for Bootcamp Edited:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => readable_updates($bootcamps[0],$b_update,'b_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $bootcamps[0],
	            'after' => $b_update,
	        )),
	        'e_type_id' => ( $b_update['b_status']<0 && $b_update['b_status']!=$bootcamps[0]['b_status'] ? 17 : 18 ), //Bootcamp Deleted or Updated
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	    ));
	    
	    
	    //Is this a request to publish?
	    if(intval($_POST['b_status'])==1 && !(intval($_POST['b_status'])==intval($bootcamps[0]['b_status']))){
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $udata['u_id'],
	            'e_json' => json_encode(array(
	                'input' => $_POST,
	                'before' => $bootcamps[0],
	                'after' => $b_update,
	            )),
	            'e_type_id' => 37, //Request to publish
	            'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        ));
	    }   
	    
	    //Update Href for Landing page buttons:
	    echo '<script> $(".landing_page_url").attr("href", "/'.$_POST['b_url_key'].'"); </script>';
	    echo '<script> $("#marketplace_b_url").text("https://mench.co/'.$_POST['b_url_key'].'"); </script>'; //Getting a bit lazy here...
	    echo '<script> $("#marketplace_b_url_ui").val("https://mench.co/'.$_POST['b_url_key'].'"); </script>'; //Getting a bit lazy here...
	    
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
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => 'Intent ['.$new_intent['c_objective'].'] created',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $new_intent,
	        )),
	        'e_type_id' => 20, //New Intent
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        'e_c_id' => $new_intent['c_id'],
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
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => 'Linked intent ['.$new_intent['c_objective'].'] as outbound of intent ['.$inbound_intents[0]['c_objective'].']',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $relation,
	        )),
	        'e_type_id' => 23, //New Intent Link
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        'e_cr_id' => $relation['cr_id'],
	    ));
	    
	    //Fetch full link package:
	    $relations = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_id' => $relation['cr_id'],
	    ));
	    
	    //Update Algolia:
	    $this->Db_model->sync_algolia($new_intent['c_id']);
	    
	    //Return result:
	    echo echo_cr($_POST['b_id'],$relations[0],'outbound',$_POST['next_level'],$bootcamps[0]['b_sprint_unit']);
	}
	

	
	function completion_report(){
	    
	    if(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0
	        || !isset($_POST['b_id']) || intval($_POST['b_id'])<=0
	        || !isset($_POST['r_id']) || intval($_POST['r_id'])<=0
	        || !isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
	            die('<span style="color:#FF0000;">Error: Invalid Inputs ID.</span>');
	    } elseif(!isset($_POST['us_on_time_score'])){
	        die('<span style="color:#FF0000;">Error: Missing point score.</span>');
	    } elseif(!isset($_POST['page_loaded']) || (time()-intval($_POST['page_loaded']))>1800){
	        die('<span style="color:#FF0000;">Error: Page was idle for more than 30 minutes. Refresh the page and try again.</span>');
	    }
	    
	    //Fetch intent:
	    $original_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => intval($_POST['c_id']),
	    ));
	    if(count($original_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid task ID.</span>');
	    }
	    
	    
	    //Now update the DB:
	    $us_data = $this->Db_model->us_create(array(
	        'us_b_id' => intval($_POST['b_id']),
	        'us_r_id' => intval($_POST['r_id']),
	        'us_c_id' => intval($_POST['c_id']),
	        'us_on_time_score' => floatval($_POST['us_on_time_score']),
	        'us_time_estimate' => $original_intents[0]['c_time_estimate'], //A snapshot of its time-estimate upon completion
	        'us_student_id' => intval($_POST['u_id']),
	        'us_student_notes' => trim($_POST['us_notes']),
	        'us_status' => 1, //Submitted
	    ));
	    
	    
	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $us_data['us_student_id'],
	        'e_message' => $us_data['us_student_notes'],
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'us_data' => $us_data,
	        )),
	        'e_type_id' => 33, //Marked as Done Report
	        'e_b_id' => $us_data['us_b_id'], //Share with bootcamp team
	        'e_r_id' => $us_data['us_r_id'],
	        'e_c_id' => $us_data['us_c_id'],
	    ));
	    
	    
	    //Show result:
	    echo_us($us_data);
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
	    } elseif(!isset($_POST['c_status'])){
	        die('<span style="color:#FF0000;">Error: Missing Task Status.</span>');
	    } elseif(!isset($_POST['b_sprint_unit'])){
	        die('<span style="color:#FF0000;">Error: Missing Milestone Submission Frequency.</span>');
	    }
	    
	    //Validate Bootcamp ID:
	    $bootcamps = $this->Db_model->b_fetch(array(
	        'b.b_id' => intval($_POST['b_id']),
	    ));
	    if(count($bootcamps)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    }
	    
	    
	    //Validate Original intent:
	    $original_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => intval($_POST['pid']),
	    ) , true /*Fetch Outbound*/ );
	    if(count($original_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid PID.</span>');
	    } elseif(isset($_POST['c_is_last']) && $_POST['c_is_last']=='t' && count($original_intents[0]['c__child_intents'])>0){
	        die('<span style="color:#FF0000;">Error: Break Milestones cannot have any Tasks. Either delete all Tasks or create a new Milestone.</span>');
	    }
	    
	    
	    //Generate Update Array
	    $c_update = array(
	        'c_objective' => trim($_POST['c_objective']),
	        'c_status' => intval($_POST['c_status']),
	        'c_time_estimate' => floatval($_POST['c_time_estimate']),
	        'c_is_last' => $_POST['c_is_last'],
	    );
	    
	    //Now update the DB:
	    $this->Db_model->c_update( intval($_POST['pid']) , $c_update);
	    
	    //Update Algolia:
	    $this->Db_model->sync_algolia(intval($_POST['pid']));
	    
	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => readable_updates($original_intents[0],$c_update,'c_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $original_intents[0],
	            'after' => $c_update,
	        )),
	        'e_type_id' => 19, //Intent Updated
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        'e_c_id' => intval($_POST['pid']),
	    ));
	    
	    
	    if(!($bootcamps[0]['b_sprint_unit']==$_POST['b_sprint_unit'])){
	        //Updatye bootcamp:
	        $b_update = array(
	            'b_sprint_unit' => $_POST['b_sprint_unit'],
	        );
	        $this->Db_model->b_update( intval($_POST['b_id']) , $b_update );
	        
	        //Log Engagement for Bootcamp Edited:
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $udata['u_id'],
	            'e_message' => readable_updates($bootcamps[0],$b_update,'b_'),
	            'e_json' => json_encode(array(
	                'input' => $_POST,
	                'before' => $bootcamps[0],
	                'after' => $b_update,
	            )),
	            'e_type_id' => 18, //Bootcamp Setting Updated
	            'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        ));
	        
	        //Do a Hard Redirect:
	        echo '<script> setTimeout(function() { window.location = "/console/'.$_POST['b_id'].'/actionplan" }, 500); </script>';
	    }
	    
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
	    
	    //Validate Bootcamp ID:
	    $bootcamps = $this->Db_model->b_fetch(array(
	        'b.b_id' => intval($_POST['b_id']),
	    ));
	    if(count($bootcamps)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
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
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => 'Linked intent ['.$outbound_intents[0]['c_objective'].'] as outbound of intent ['.$inbound_intents[0]['c_objective'].']',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $relation,
	        )),
	        'e_type_id' => 23, //New Intent Link
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        'e_cr_id' => $relation['cr_id'],
	    ));
	    
	    
	    //Fetch full OUTBOUND link package:
	    $relations = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_id' => $relation['cr_id'],
	    ));
	    
	    
	    //Return result:
	    echo echo_cr($_POST['b_id'],$relations[0],'outbound',$_POST['next_level'],$bootcamps[0]['b_sprint_unit']);
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
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => 'Removed intent ['.$outbound_intents[0]['c_objective'].'] as outbound of intent ['.(isset($inbound_intents[0]['c_objective']) ? $inbound_intents[0]['c_objective'] : 'Unknown!').']',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $outbound_intents[0],
	            'after' => $cr_update,
	        )),
	        'e_type_id' => 21, //Deleted Link
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        'e_cr_id' => intval($_POST['cr_id']),
	    ));
	    
	    //Show result:
	    die('<span style="color:#222;"><i class="fa fa-trash" aria-hidden="true"></i> Deleted</span>');
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
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => 'Sorted outbound intents for ['.$inbound_intents[0]['c_objective'].']',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $outbounds_before,
	            'after' => $outbounds_after,
	        )),
	        'e_type_id' => 22, //Links Sorted
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        'e_c_id' => intval($_POST['pid']),
	    ));	    
        
	    //Display message:
	    echo '<span style="color:#00CC00;">'.(count($_POST['new_sort'])-1).' sorted</span>';
	}
	
	
	
	
	
	
	
	
	/* ******************************
	 * i Messages
	 ****************************** */
	
	function detect_url(){
	    $urls = extract_urls($_POST['text']);
	    if(count($urls)>0){
	        //Fetch more details for the FIRST URL only and append to page:
	        $flash_url = $this->session->flashdata('first_url');
	        if(!$flash_url || !($flash_url==$urls[0])){
	            echo $urls[0].time();
	        }
	        //Set new flash data either way!
	        $this->session->set_flashdata('first_url', $urls[0]);
	    } else {
	        //Special command detected by JS to clear the URL preview:
	        echo 'clear_url_preview';
	    }
	}
	
	function message_attachment(){
	    
	    $udata = auth(2);
	    $file_limit_mb = $this->config->item('file_limit_mb');
	    if(!$udata){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Session. Refresh to Continue',
	        ));
	    } elseif(!isset($_POST['pid']) || !isset($_POST['b_id']) || !isset($_POST['i_status'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing intent data.',
	        ));
	    } elseif(!isset($_POST['upload_type']) || !in_array($_POST['upload_type'],array('file','drop'))){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Unknown upload type.',
	        ));
	    } elseif(!isset($_FILES[$_POST['upload_type']]['tmp_name']) || !isset($_FILES[$_POST['upload_type']]['type']) || !isset($_FILES[$_POST['upload_type']]['size'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing file.',
	        ));
	    } elseif($_FILES[$_POST['upload_type']]['size']>($file_limit_mb*1024*1024)){
	        
	        echo_json(array(
	            'status' => 0,
	            'message' => 'File is larger than '.$file_limit_mb.' MB.',
	        ));
	        
	    } else {
	        
	        //First save file locally:
	        $temp_local = "application/cache/temp_files/".$_FILES[$_POST['upload_type']]["name"];
	        move_uploaded_file( $_FILES[$_POST['upload_type']]['tmp_name'] , $temp_local );
	        
	        //Attempt to store in Cloud:
	        if(isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type'])>0){
	            $mime = $_FILES[$_POST['upload_type']]['type'];
	        } else {
	            $mime = mime_content_type($temp_local);
	        }
	        
	        //Upload to S3:
	        $new_file_url = save_file( $temp_local , $_FILES[$_POST['upload_type']] , true );
	        
	        //What happened?
	        if(!$new_file_url){
	            
	            //Oops something went wrong:
	            echo_json(array(
	                'status' => 0,
	                'message' => 'Could not save to cloud!',
	            ));
	            
	        } else {
	            
	            //Detect file type:
	            $i_media_type = mime_type($mime);
	            
	            //Create Message:
	            $message = '/attach '.$i_media_type.':'.trim($new_file_url);
	            
	            //Create message:
	            $i = $this->Db_model->i_create(array(
	                'i_creator_id' => $udata['u_id'],
	                'i_c_id' => intval($_POST['pid']),
	                'i_b_id' => intval($_POST['b_id']),
	                'i_media_type' => $i_media_type,
	                'i_message' => $message,
	                'i_url' => $new_file_url,
	                'i_status' => intval($_POST['i_status']),
	                'i_rank' => 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
	                    'i_status >=' => 0,
	                    'i_status <' => 4, //But not private notes if any
	                    'i_c_id' => intval($_POST['pid']),
	                )),
	            ));
	            
	            //Fetch full message:
	            $new_messages = $this->Db_model->i_fetch(array(
	                'i_id' => $i['i_id'],
	            ));
	            
	            //Log engagement:
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => $udata['u_id'],
	                'e_json' => json_encode(array(
	                    'post' => $_POST,
	                    'file' => $_FILES,
	                    'after' => $new_messages[0],
	                )),
	                'e_type_id' => 34, //Message added
	                'e_i_id' => intval($new_messages[0]['i_id']),
	                'e_c_id' => intval($new_messages[0]['i_c_id']),
	                'e_b_id' => $new_messages[0]['i_b_id'], //Share with bootcamp team
	            ));
	            
	            //Echo message:
	            echo_json(array(
	                'status' => 1,
	                'message' => echo_message($new_messages[0],$_POST['level']),
	            ));
	        }
	    }
	}
	
	
	
	function message_create(){
	    
	    $udata = auth(2);
	    if(!$udata){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Session. Refresh to Continue.',
	        ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Task ID.',
	        ));
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Bootcamp ID.',
	        ));
	    } elseif(!isset($_POST['i_status'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Status.',
	        ));
	    } elseif(!isset($_POST['i_message']) || strlen($_POST['i_message'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing message.',
	        ));
	    } else {
	        
	        //Detect potential URL:
	        $urls = extract_urls($_POST['i_message']);
	        if(count($urls)>1){
	            echo_json(array(
	                'status' => 0,
	                'message' => 'You can only add 1 URL per message.',
	            ));
	        } else {
	            
	            //Detect file type:
	            if(count($urls)==1 && trim($urls[0])==trim($_POST['i_message'])){
	                
	                //This message is a URL only, perform raw URL to file conversion
	                //This feature only available for newly created message, NOT in editing mode!
	                $mime = remote_mime($urls[0]);
	                $i_media_type = mime_type($mime);
	                if($i_media_type=='file'){
	                    $i_media_type = 'text';
	                }
	                
	            } else {
	                //This channel is all text:
	                $i_media_type = 'text'; //Possible: text,image,video,audio,file
	            }
	            
	            //Create Message:
	            $i = $this->Db_model->i_create(array(
	                'i_creator_id' => $udata['u_id'],
	                'i_c_id' => intval($_POST['pid']),
	                'i_b_id' => intval($_POST['b_id']),
	                'i_media_type' => $i_media_type,
	                'i_message' => trim($_POST['i_message']),
	                'i_url' => ( count($urls)==1 ? $urls[0] : null ),
	                'i_status' => intval($_POST['i_status']),
	                'i_rank' => 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
	                    'i_status >=' => 0,
	                    'i_status <' => 4, //But not private notes if any
	                    'i_c_id' => intval($_POST['pid']),
	                )),
	            ));
	            
	            //Fetch full message:
	            $new_messages = $this->Db_model->i_fetch(array(
	                'i_id' => $i['i_id'],
	            ));
	            
	            //Log engagement:
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => $udata['u_id'],
	                'e_json' => json_encode(array(
	                    'input' => $_POST,
	                    'after' => $new_messages[0],
	                )),
	                'e_type_id' => 34, //Message added
	                'e_i_id' => intval($new_messages[0]['i_id']),
	                'e_c_id' => intval($_POST['pid']),
	                'e_b_id' => $new_messages[0]['i_b_id'], //Share with bootcamp team
	            ));
	            
	            //Print the challenge:
	            echo_json(array(
	                'status' => 1,
	                'message' => echo_message($new_messages[0],$_POST['level']),
	            ));
	        }    
	    }   
	}
	
	function message_update(){
	    
	    //Auth user and Load object:
	    $udata = auth(2);
	    if(!$udata){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Session. Refresh & try Again',
	        ));
	    } elseif(!isset($_POST['i_media_type'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Type',
	        ));
	    } elseif(!isset($_POST['i_id']) || intval($_POST['i_id'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Message ID',
	        ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Intent ID',
	        ));
	    } elseif(($_POST['i_media_type']=='text') && (!isset($_POST['i_message']) || strlen($_POST['i_message'])<=0)){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Text Message',
	        ));
	    } elseif(!isset($_POST['i_status'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Status',
	        ));
	    } else {
	        //Fetch Message:
	        $messages = $this->Db_model->i_fetch(array(
	            'i_id' => intval($_POST['i_id']),
	            'i_status >=' => 0,
	        ));
	        
	        //Fetch URLs for Text:
	        if($_POST['i_media_type']=='text'){
	            $urls = extract_urls($_POST['i_message']);
	        }
	        
	        if($_POST['i_media_type']=='text' && count($urls)>1){
	            echo_json(array(
	                'status' => 0,
	                'message' => 'You can only add 1 URL per message.',
	            ));
	        } elseif(!isset($messages[0])){
	            echo_json(array(
	                'status' => 0,
	                'message' => 'Message Not Found',
	            ));
	        } else {
	            
	            //Define what needs to be updated:
	            $to_update = array(
	                'i_creator_id' => $udata['u_id'],
	                'i_timestamp' => date("Y-m-d H:i:s"),
	                'i_status' => intval($_POST['i_status']),
	            );
	            
	            //Is this a text message?
	            if($_POST['i_media_type']=='text'){
	                $to_update['i_message'] = trim($_POST['i_message']);
	                $to_update['i_url'] = ( count($urls)==1 ? $urls[0] : null );
	            }
	            
	            //Now update the DB:
	            $this->Db_model->i_update( intval($_POST['i_id']) , $to_update );
	            
	            //Refetch the message for display purposes:
	            $new_messages = $this->Db_model->i_fetch(array(
	                'i_id' => intval($_POST['i_id']),
	            ));
	            
	            //Log engagement:
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => $udata['u_id'],
	                'e_json' => json_encode(array(
	                    'input' => $_POST,
	                    'before' => $messages[0],
	                    'after' => $new_messages[0],
	                )),
	                'e_type_id' => 36, //Message edited
	                'e_i_id' => $messages[0]['i_id'],
	                'e_c_id' => intval($_POST['pid']),
	                'e_b_id' => $messages[0]['i_b_id'], //Share with bootcamp team
	            ));
	            
	            //Print the challenge:
	            echo_json(array(
	                'status' => 1,
	                'message' => echo_i($new_messages[0]),
	                'new_status' => status_bible('i',$new_messages[0]['i_status'],1,'right'),
	                'success_icon' => '<img src="/img/round_done.gif?time='.time().'" class="loader" />',
	                'new_uploader' => echo_uploader($new_messages[0]), //If there is a person change...
	            ));
	        }
	    }
	}
	
	function message_delete(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['i_id']) || intval($_POST['i_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Message id.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        die('<span style="color:#FF0000;" class="i_error">Error: Invalid Intent ID.</span>');
	    }
	    
	    //Fetch Message:
	    $messages = $this->Db_model->i_fetch(array(
	        'i_id' => intval($_POST['i_id']),
	        'i_status >=' => 0, //Not deleted
	    ));
	    if(!isset($messages[0])){
	        die('<span style="color:#FF0000;">Error: Invalid Message id.</span>');
	    }
	    
	    //Now update the DB:
	    $this->Db_model->i_update( intval($_POST['i_id']) , array(
	        'i_creator_id' => $udata['u_id'],
	        'i_timestamp' => date("Y-m-d H:i:s"),
	        'i_status' => -1, //Deleted by instructor
	    ));
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $messages[0],
	        )),
	        'e_type_id' => 35, //Message deleted
	        'e_i_id' => intval($messages[0]['i_id']),
	        'e_c_id' => intval($_POST['pid']),
	        'e_b_id' => $messages[0]['i_b_id'], //Share with bootcamp team
	    ));
	    
	    //Show result:
	    die('<span style="color:#222;"><i class="fa fa-trash" aria-hidden="true"></i> Deleted</span>');
	}
	
	
	function messages_sort(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort'])<=0){
	        die('<span style="color:#FF0000;">Error: Nothing to sort.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Bootcamp ID.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        die('<span style="color:#FF0000;" class="i_error">Error: Invalid Intent ID.</span>');
	    }
	    
	    //Update them all:
	    foreach($_POST['new_sort'] as $i_rank=>$i_id){
	        $this->Db_model->i_update( intval($i_id) , array(
	            'i_rank' => intval($i_rank),
	        ));
	    }
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
    	    'e_initiator_u_id' => $udata['u_id'],
    	    'e_json' => json_encode($_POST),
    	    'e_type_id' => 39, //Messages sorted
    	    'e_c_id' => intval($_POST['pid']),
    	    'e_b_id' => intval($_POST['b_id']),
	    ));
	    
	    //Show message:
	    echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>';
	}
	
}