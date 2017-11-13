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
	
	function funnel_progress(){
	    
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
	                'e_creator_id' => $udata['u_id'],
	                'e_message' => 'Student attempted to enroll in a 2nd Bootcamp which is currently not allowed!',
	                'e_json' => json_encode($_POST),
	                'e_type_id' => 9, //Support Needing Graceful Errors
	            ));
	            
	            //Send the email to their application:            
	            if(email_application_url($udata)){
	                
	                $application_status_salt = $this->config->item('application_status_salt');
	                
	                //Log Email Engagement:
	                $this->Db_model->e_create(array(
	                    'e_creator_id' => $udata['u_id'], //The user that updated the account
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
	            
	            //Existing user that is never enrolled here:
	            $enrollments[0] = $this->Db_model->ru_create(array(
	                'ru_r_id' 	=> $focus_class['r_id'],
	                'ru_u_id' 	=> $udata['u_id'],
	            ));
	            
	            //Assume all good, Log engagement:
	            $this->Db_model->e_create(array(
	                'e_creator_id' => $udata['u_id'],
	                'e_json' => json_encode(array(
	                    'input' => $_POST,
	                    'udata' => $udata,
	                    'rudata' => $enrollments[0],
	                )),
	                'e_type_id' => 29, //Joined Class
	                'e_object_id' => $focus_class['r_id'],
	                'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
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
	                    'e_creator_id' => $udata['u_id'], //The user that updated the account
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
	                    'hard_redirect' => '/my/apply/'.$enrollments[0]['ru_id'].'?u_key='.$u_key.'&u_id='.$udata['u_id'],
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
	                'u_creator_id' 		=> 0, //They created their own account
	                'u_language' 		=> 'en', //Since they answered initial questions in English
	                'u_email' 			=> trim($_POST['u_email']),
	                'u_fname' 			=> trim($_POST['u_fname']),
	                'u_lname' 			=> trim($_POST['u_lname']),
	            ));
	            
	            if($udata['u_id']>0){
	                
	                //Log Engagement for registration:
	                $this->Db_model->e_create(array(
	                    'e_creator_id' => $udata['u_id'], //The user that updated the account
	                    'e_json' => json_encode(array(
	                        'input' => $_POST,
	                        'udata' => $udata,
	                    )),
	                    'e_type_id' => 27, //New Student Lead
	                    'e_object_id' => $focus_class['r_id'],
	                    'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
	                ));
	                
	                
	                //Insert Enrollment Status since they are new:
	                $rudata = $this->Db_model->ru_create(array(
	                    'ru_r_id' 	=> $focus_class['r_id'],
	                    'ru_u_id' 	=> $udata['u_id'],
	                ));
	                
	                if($rudata['ru_id']>0){
	                    
	                    //Log Engagement:
	                    $this->Db_model->e_create(array(
	                        'e_creator_id' => $udata['u_id'], //The user that updated the account
	                        'e_json' => json_encode(array(
	                            'input' => $_POST,
	                            'udata' => $udata,
	                            'rudata' => $rudata,
	                        )),
	                        'e_type_id' => 29, //Joined Class
	                        'e_object_id' => $focus_class['r_id'],
	                        'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
	                    ));	                        
	                        
	                    //Send email and log engagement:
	                    if(email_application_url($udata)){
	                        //Fetch variables:
	                        $application_status_salt = $this->config->item('application_status_salt');
	                        $udata['u_key'] = md5($udata['u_id'].$application_status_salt);
	                        
	                        //Log Engagement:
	                        $this->Db_model->e_create(array(
	                            'e_creator_id' => $udata['u_id'], //The user that updated the account
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
	                            'hard_redirect' => '/my/apply/'.$rudata['ru_id'].'?u_key='.$udata['u_key'].'&u_id='.$udata['u_id'],
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
	            'e_creator_id' => ( isset($_POST['u_id']) ? intval($_POST['u_id']) : 0 ),
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
	            'e_creator_id' => $_POST['u_id'],
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
	        'e_creator_id' => $_POST['u_id'],
	        'e_json' => json_encode($_POST),
	        'e_type_id' => 26, //Application submitted
	        'e_object_id' => $admissions[0]['r_id'],
	        'e_b_id' => $admissions[0]['b_id'], //Share with bootcamp team
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
	            'e_creator_id' => $users[0]['u_id'],
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
                    'e_creator_id' => $users[0]['u_id'],
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
	 * r Classes
	 ****************************** */
	
	function class_timeline(){
	    //Displays the class timeline based on some inputs:
	    if(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
	        die('<span style="color:#000;">Enter start date to see timeline.</span>');
	    } elseif(!isset($_POST['r_start_time_mins'])){
	        die('<span style="color:#000;">Enter start time to see timeline.</span>');
	    } elseif(!isset($_POST['milestone_count']) || intval($_POST['milestone_count'])<=0){
	        die('<span style="color:#FF0000;">Error: You have not added any Milestones to your Action Plan.</span>');
	    } elseif(!isset($_POST['b_sprint_unit'])){
	        die('<span style="color:#FF0000;">Error: Invalid Milestone Submission Frequency.</span>');
	    }
	    
	    $_POST['milestone_count'] = intval($_POST['milestone_count']);
	    //Incldue config variables:
        $sprint_units = $this->config->item('sprint_units');
        $start_times = $this->config->item('start_times');
	    
	    //Start calculations:
        echo '<p>Based on this start time, your class timeline is:</p>';
        echo '<ul style="list-style:decimal;">';
	        echo '<li>Admission Starts <b>When Bootcamp is Published Live</b></li>';
	        echo '<li>Admission Ends <b>'.time_format($_POST['r_start_date'],2,-1).' 11:59pm PST</b> (Midnight Before Start Day)</li>';
	        echo '<li>Class Starts <b>'.time_format($_POST['r_start_date'],2).' '.$start_times[$_POST['r_start_time_mins']].' PST</b> (Your Selected Time)</li>';
    	    echo '<li>Instant Payout by <b>'.time_format($_POST['r_start_date'],2).' 6:00pm PST</b> (Afternoon of Start Day <a href="https://support.mench.co/hc/en-us/articles/115002473111" title="Learn more about Mench Payouts" target="_blank"><i class="fa fa-info-circle" aria-hidden="true"></i></a>)</li>';
    	    echo '<li>Class Ends <b>'.time_format($_POST['r_start_date'],2,(calculate_duration(array('b_sprint_unit'=>$_POST['b_sprint_unit']),$_POST['milestone_count']))).' '.$start_times[$_POST['r_start_time_mins']].' PST</b> ('.$_POST['milestone_count'] ?> <?= ucwords($_POST['b_sprint_unit']).' Action Plan)</li>';
    	    echo '<li>Performance Payout by <b>'.time_format($_POST['r_start_date'],2,(calculate_duration(array('b_sprint_unit'=>$_POST['b_sprint_unit']),$_POST['milestone_count'])+13)).' 6:00pm PST</b> (2 Weeks Later <a href="https://support.mench.co/hc/en-us/articles/115002473111" title="Learn more about Mench Payouts" target="_blank"><i class="fa fa-info-circle" aria-hidden="true"></i></a>)</li>';
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
	            'e_creator_id' => $udata['u_id'], //The user that updated the account
	            'e_message' => $eng_message,
	            'e_json' => json_encode(array(
	                'input' => $_POST,
	                'before' => array(),
	                'after' => $class,
	            )),
	            'e_type_id' => 14, //New Class
	            'e_object_id' => $class['r_id'],
	            'e_b_id' => intval($_POST['r_b_id']), //Share with bootcamp team
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
	            'e_creator_id' => $udata['u_id'], //The user
	            'e_message' => readable_updates($classes[0],$r_update,'r_'),
	            'e_json' => json_encode(array(
	                'input' => $_POST,
	                'before' => @unserialize($classes[0]['r_live_office_hours']),
	                'after' => @unserialize($r_update['r_live_office_hours']),
	            )),
	            'e_type_id' => 24, //Class Schedule Update
	            'e_object_id' => intval($_POST['r_id']),
	            'e_b_id' => $classes[0]['r_b_id'], //Share with bootcamp team
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
	    $weekly_1on1s_options = $this->config->item('r_weekly_1on1s_options');
	    $start_times = $this->config->item('start_times');
	    
	    
	    $r_update = array(
	        'r_start_date' => $new_date,
	        'r_start_time_mins' => ( array_key_exists(intval($_POST['r_start_time_mins']),$start_times) ? intval($_POST['r_start_time_mins']) : null ),
	        'r_status' => intval($_POST['r_status']),
	        
	        'r_response_time_hours' => ( in_array(floatval($_POST['r_response_time_hours']),$r_response_options) ? floatval($_POST['r_response_time_hours']) : null ),
	        'r_weekly_1on1s' => ( strlen($_POST['r_weekly_1on1s'])>0 && in_array(floatval($_POST['r_weekly_1on1s']),$weekly_1on1s_options) ? floatval($_POST['r_weekly_1on1s']) : null ),
	        'r_office_hour_instructions' => ( strlen($_POST['r_office_hour_instructions'])>0 ? trim($_POST['r_office_hour_instructions']) : null ),
	        'r_cancellation_policy' => ( isset($_POST['r_cancellation_policy']) && array_key_exists($_POST['r_cancellation_policy'],$refund_policies) ? $_POST['r_cancellation_policy'] : null ),
	        'r_closed_dates' => ( strlen($_POST['r_closed_dates'])>0 ? trim($_POST['r_closed_dates']) : null ),
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
	        'e_creator_id' => $udata['u_id'], //The user
	        'e_message' => readable_updates($classes[0],$r_update,'r_'),
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $classes[0],
	            'after' => $r_update,
	        )),
	        'e_type_id' => ($r_update['r_status']<0 && $r_update['r_status']!=$classes[0]['r_status'] ? 16 : 13), //Class Setting Updated/Deleted
	        'e_object_id' => intval($_POST['r_id']),
	        'e_b_id' => $classes[0]['r_b_id'], //Share with bootcamp team
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
                'e_creator_id' => $udata['u_id'],
                'e_message' => 'bootcamp_create() Function failed to create intent ['.$_POST['c_objective'].'].',
                'e_json' => json_encode($_POST),
                'e_type_id' => 8, //Platform Error
            ));
            //Display error:
            die('<span style="color:#FF0000;">Error: Unkown error while trying to create intent.</span>');
        }
        
        
        //Generaye URL Key:
        //Cleans text:
        $generated_key = clean_urlkey($_POST['c_objective']);
        
        
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
        
        
        //Show message & redirect:
        //For fancy UI to give impression of hard work:
        sleep(2);
        echo '<script> setTimeout(function() { window.location = "/console/'.$bootcamp['b_id'].'" }, 1000); </script>';
        echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span><div>Going to Bootcamp Dashboard...</div>';
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
	    } elseif(!isset($_POST['b_sprint_unit'])){
	        die('<span style="color:#FF0000;">Error: Missing Sprint Unit.</span>');
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
	    
	    
	    //Generate update array for the bootcamp:
	    $b_update = array(
	        'b_status' => intval($_POST['b_status']),
	        'b_url_key' => $_POST['b_url_key'],
	        'b_video_url' => $_POST['b_video_url'],
	        'b_sprint_unit' => $_POST['b_sprint_unit'],
	    );
	    
	    //Did they just agree to the agreement?
	    if(isset($_POST['b_newly_checked']) && intval($_POST['b_newly_checked']) && strlen($bootcamps[0]['b_terms_agreement_time'])<1){
	        //Yes they did, save the timestamp:
	        $b_update['b_terms_agreement_time'] = date("Y-m-d H:i:s");
	    }

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
	    
	    //Is this a request to publish?
	    if(intval($_POST['b_status'])==1 && !(intval($_POST['b_status'])==intval($bootcamps[0]['b_status']))){
	        $this->Db_model->e_create(array(
	            'e_creator_id' => $udata['u_id'],
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
	        'us_status' => 1, //No need for review
	    ));
	    
	    
	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $us_data['us_student_id'],
	        'e_message' => $us_data['us_student_notes'],
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'us_data' => $us_data,
	        )),
	        'e_type_id' => 33, //Marked as Done Report
	        'e_object_id' => $us_data['us_c_id'],
	        'e_b_id' => $us_data['us_b_id'], //Share with bootcamp team
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
	        'c_todo_overview' => $_POST['c_todo_overview'],
	        'c_status' => intval($_POST['c_status']),
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
	    
	    $i_media_type_names = $this->config->item('i_media_type_names');
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;" class="i_error">Error: Invalid Session. Refresh to Continue.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        die('<span style="color:#FF0000;" class="i_error">Error: Invalid Task ID.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;" class="i_error">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['i_media_type']) || !array_key_exists($_POST['i_media_type'],$i_media_type_names)){
	        die('<span style="color:#FF0000;" class="i_error">Error: Missing Media Type.</span>');
	    } elseif($_POST['i_media_type']=='text' && (!isset($_POST['i_message']) || strlen($_POST['i_message'])<=0) && (!isset($_POST['i_url']) || strlen($_POST['i_url'])<=0 || !filter_var($_POST['i_url'], FILTER_VALIDATE_URL))){
	        die('<span style="color:#FF0000;" class="i_error">Error: Missing message.</span>');
	    } elseif(!($_POST['i_media_type']=='text') && (!isset($_POST['i_url']) || strlen($_POST['i_url'])<=0 || !filter_var($_POST['i_url'], FILTER_VALIDATE_URL))){
	        die('<span style="color:#FF0000;" class="i_error">Error: Invalid URL.</span>');
	    } elseif(!isset($_POST['i_dispatch_minutes'])){
	        die('<span style="color:#FF0000;" class="i_error">Error: Missing Dispatch Minutes.</span>');
	    }
	    
	    //Create Link:
	    $i = $this->Db_model->i_create(array(
	        'i_creator_id' => $udata['u_id'],
	        'i_c_id' => intval($_POST['pid']),
	        'i_b_id' => intval($_POST['b_id']),
	        'i_media_type' => $_POST['i_media_type'],
	        'i_message' => trim($_POST['i_message']),
	        'i_url' => trim($_POST['i_url']),
	        'i_dispatch_minutes' => intval($_POST['i_dispatch_minutes']),
	        'i_status' => 1,
	    ));
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => ucwords($i['i_media_type']).': '.$i['i_message'].' '.$i['i_url'].' (Dispatch after '.$i['i_dispatch_minutes'].' minutes)',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'after' => $i,
	        )),
	        'e_type_id' => 34, //Insight added
	        'e_object_id' => intval($i['i_id']),
	        'e_b_id' => $i['i_b_id'], //Share with bootcamp team
	    ));
	    
	    //Print the challenge:
	    echo_message($i);
	}
	
	function media_edit(){
	    
	    //TODO update and start using
	    exit;
	    
	    //Auth user and Load object:
	    $udata = auth(2);
	    
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['i_id']) || intval($_POST['i_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Insight id.</span>');
	    } elseif(!isset($_POST['i_message']) || strlen($_POST['i_message'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing i_message.</span>');
	    }
	    
	    //Fetch Insight:
	    $insights = $this->Db_model->i_fetch(array(
	        'i_id' => intval($_POST['i_id']),
	    ));
	    if(!isset($insights[0])){
	        die('<span style="color:#FF0000;">Error: Invalid Insight id.</span>');
	    }
	    
	    //Now update the DB:
	    $this->Db_model->i_update( intval($_POST['i_id']) , array(
	        'i_creator_id' => $udata['u_id'],
	        'i_timestamp' => date("Y-m-d H:i:s"),
	        'i_message' => trim($_POST['i_message']),
	    ));
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        //'e_message' => ucwords($i['i_media_type']).': '.$i['i_message'].' '.$i['i_url'].' (Dispatch after '.$i['i_dispatch_minutes'].' minutes)',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $insights[0],
	        )),
	        'e_type_id' => 36, //Insight edited
	        'e_object_id' => intval($insights[0]['i_id']),
	        'e_b_id' => $insights[0]['i_b_id'], //Share with bootcamp team
	    ));
	    
	    //Show result:
	    die('<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
	}
	
	function media_delete(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['i_id']) || intval($_POST['i_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Insight id.</span>');
	    }
	    
	    //Fetch Insight:
	    $insights = $this->Db_model->i_fetch(array(
	        'i_id' => intval($_POST['i_id']),
	        'i_status >=' => 0, //Not deleted
	    ));
	    if(!isset($insights[0])){
	        die('<span style="color:#FF0000;">Error: Invalid Insight id.</span>');
	    }
	    
	    //Now update the DB:
	    $this->Db_model->i_update( intval($_POST['i_id']) , array(
	        'i_creator_id' => $udata['u_id'],
	        'i_timestamp' => date("Y-m-d H:i:s"),
	        'i_status' => -1, //Deleted by user
	    ));
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_creator_id' => $udata['u_id'],
	        'e_message' => ucwords($insights[0]['i_media_type']).': '.$insights[0]['i_message'].' '.$insights[0]['i_url'].' (Dispatch after '.$insights[0]['i_dispatch_minutes'].' minutes)',
	        'e_json' => json_encode(array(
	            'input' => $_POST,
	            'before' => $insights[0],
	        )),
	        'e_type_id' => 35, //Insight deleted
	        'e_object_id' => intval($insights[0]['i_id']),
	        'e_b_id' => $insights[0]['i_b_id'], //Share with bootcamp team
	    ));
	    
	    //Show result:
	    die('<span style="color:#00CC00;">Deleted</span>');
	}
	
	
}