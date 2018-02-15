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

    function blob($e_id){
        $udata = auth(3,1);
        //Fetch blob of engagement and display it on screen:
        $blobs = $this->Db_model->ej_fetch(array(
            'ej_e_id' => $e_id,
        ));
        if(count($blobs)==1){
            echo_json(unserialize($blobs[0]['ej_e_blob']));
        } else {
            echo_json(array('error'=>'Not Found'));
        }
    }

    function config(){
        if($_GET['token']!='1f8e38384ddc45d0d19c706e21950643'){
            echo_json(array(
                'status' => 0,
                'messsage' => 'Invalid Token',
            ));
            return false;
        }

        //Fetch all config:
        $config = $this->config->config;
        $display_config = array();
        //Strip the secret ones:
        foreach($config['show_in_api'] as $key){
            $display_config[$key] = $config[$key];
        }

        echo_json(array(
            'status' => 0,
            'messsage' => 'Success',
            'config' => $display_config,
        ));
    }

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
	            'e_json' => $i,
	            'e_type_id' => 40, //Got It
	            'e_c_id' => intval($_POST['intent_id']),
	            'e_i_id' => $i['i_id'],
	        ));
	    }
	    
	    //Build UI friendly Message:
	    $help_content = null;
	    foreach($messages as $i){
	        $help_content .= echo_i(array_merge($i,array('e_recipient_u_id'=>$udata['u_id'])),$udata['u_fname']);
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

	function update_review(){
        if(!isset($_POST['ru_id']) || !isset($_POST['ru_key']) || intval($_POST['ru_id'])<1 || !($_POST['ru_key']==substr(md5($_POST['ru_id'].'r3vi3wS@lt'),0,6))){
            die('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error: Invalid Admission Data.</div>');
        } elseif(!isset($_POST['ru_review_score']) || intval($_POST['ru_review_score'])<1 || intval($_POST['ru_review_score'])>10){
            die('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error: Review Score must be between 1-10.</div>');
        }

        //Validate Admission:
        $admissions = $this->Db_model->ru_fetch(array(
            'ru_id' => intval($_POST['ru_id']),
        ));
        if(count($admissions)<1){
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => 0, //System
                'e_message' => 'Validated review submission call failed to fetch admission data',
                'e_type_id' => 8, //System Error
            ));
            die('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error: Unable to locate your Admission.</div>');
        }

        //Is this a new review, or updating an existing one?
        $new_review = ( intval($admissions[0]['ru_review_score'])<0 );
        $has_text = ( strlen($_POST['ru_review_public_note'])>0 || strlen($_POST['ru_review_private_note'])>0 );
        $update_data = array(
            'ru_review_time' => date("Y-m-d H:i:s"),
            'ru_review_score' => $_POST['ru_review_score'],
            'ru_review_public_note' => $_POST['ru_review_public_note'],
            'ru_review_private_note' => $_POST['ru_review_private_note'],
        );

        //Save Engagement that is visible to instructor:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $admissions[0]['u_id'],
            'e_message' => ( $new_review ? 'Student rated your Class ' : 'Student updated their rating for your Class to ' ).intval($_POST['ru_review_score']).'/10 with the following review: '.( strlen($_POST['ru_review_public_note'])>0 ? $_POST['ru_review_public_note'] : 'No Review' ),
            'e_json' => $update_data,
            'e_type_id' => 72, //Student Reviewed Class
            'e_b_id' => $admissions[0]['r_b_id'],
            'e_r_id' => $admissions[0]['r_id'],
        ));

        //Do they have a Private Feedback? Log a need attention Engagement to Mench team reads instantly:
        if(strlen($_POST['ru_review_private_note'])>0){
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $admissions[0]['u_id'],
                'e_message' => 'Received the following private/anonymous feedback: '.$_POST['ru_review_private_note'],
                'e_json' => $update_data,
                'e_type_id' => 9, //Support Needing Graceful Errors
                'e_b_id' => $admissions[0]['r_b_id'],
                'e_r_id' => $admissions[0]['r_id'],
            ));
        }

        //Update data:
        $this->Db_model->ru_update($admissions[0]['ru_id'], $update_data);

        //Show success and thank student:
        echo '<div class="alert alert-success">Review '.($new_review?'submitted':'updated').' 👌'.( $has_text ? ' We read every single review and use your feedback to continuously improve 🙌​' : '' ).'</div>';

        //TODO Encourage sharing IF reviewed highly...

    }

    function request_password_reset(){
        //We need an email input:
        if(!isset($_POST['email'])){
            die('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error: Missing Email.</div>');
        } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            die('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error: Invalid Email.</div>');
        }

        //Attempt to fetch this user:
        $matching_users = $this->Db_model->u_fetch(array(
            'u_email' => strtolower($_POST['email']),
        ));
        if(count($matching_users)>0){
            $this->load->model('Email_model');
            //Dispatch the password reset node:
            $this->Email_model->email_intent(0,3030,$matching_users[0]);
        }

        //Show message:
        echo '<div class="alert alert-success">Password reset accepted. You will receive an email only if you have a registered Mench account.</div>';
        echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';

    }

    function update_new_password(){
        //This function updates the user's new password as requested via a password reset:
        if(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0 || !isset($_POST['timestamp']) || intval($_POST['timestamp'])<=0 || !isset($_POST['p_hash']) || strlen($_POST['p_hash'])<10){
            echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error: Missing Core Variables.</div>';
        } elseif(!($_POST['p_hash']==md5($_POST['u_id'] . 'p@ssWordR3s3t' . $_POST['timestamp']))){
            echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error: Invalid hash key.</div>';
        } elseif(!isset($_POST['new_pass']) || strlen($_POST['new_pass'])<6){
            echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Error: New password must be longer than 6 characters. Try again.</div>';
        } else {
            //All seems good, lets update their account:
            $this->Db_model->u_update( intval($_POST['u_id']) , array(
                'u_password' => md5($_POST['new_pass']),
            ));

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => intval($_POST['u_id']),
                'e_type_id' => 59, //Password reset
            ));

            //Log all sessions out:
            $this->session->sess_destroy();

            //Show message:
            echo '<div class="alert alert-success">Passsword reset successful. You can <a href="/login"><u>login here</u></a>.</div>';
            echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';
        }
    }
	
	function funnel_progress(){
	    
	    $this->load->helper('cookie');
        $this->load->model('Email_model');
        $application_status_salt = $this->config->item('application_status_salt');

	    if(!isset($_POST['r_id']) || intval($_POST['r_id'])<1 || !isset($_POST['u_fname']) || !isset($_POST['u_email'])){
	        die(echo_json(array(
	            'status' => 0,
	            'error_message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Missing Core Data</div>',
	        )));
	    }
	    
	    //Fetch inputs:
	    $classes = $this->Db_model->r_fetch(array(
	        'r.r_id' => intval($_POST['r_id']),
	        'r.r_status' => 1,
	    ));

	    if(count($classes)==1){
            $bootcamps = $this->Db_model->remix_bootcamps(array(
                'b.b_id' => $classes[0]['r_b_id'],
            ));
            $bootcamp = $bootcamps[0];
            $focus_class = filter_class($bootcamp['c__classes'],intval($_POST['r_id']));
        }
	    
	    //Display results:
	    if(!isset($bootcamp) || !isset($classes[0]) || $bootcamp['b_id']<1 || !($focus_class['r_id']==intval($_POST['r_id']))) {

            die(echo_json(array(
                'status' => 0,
                'error_message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Invalid Class ID</div>',
            )));

        } elseif(!isset($_POST['u_email']) || strlen($_POST['u_email'])<1 || !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){

            die(echo_json(array(
                'status' => 0,
                'error_message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Invalid email, try again.</div>',
            )));

        }

        //Fetch user data to see if already registered:
        $users = $this->Db_model->u_fetch(array(
            'u_email' => strtolower($_POST['u_email']),
        ));

        if(count($users)==0){

            if(!isset($_POST['u_fname']) || strlen($_POST['u_fname'])<2){
                //Invalid First name,
                die(echo_json(array(
                    'status' => 0,
                    'error_message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Invalid first name, try again.</div>',
                )));
            } else {

                //Create new user:
                $udata = $this->Db_model->u_create(array(
                    'u_status' 			=> 0, //Since nothing is yet validated
                    'u_language' 		=> 'en', //Since they answered initial questions in English
                    'u_email' 			=> trim(strtolower($_POST['u_email'])),
                    'u_fname' 			=> trim($_POST['u_fname']),
                ));

                //Log Engagement for registration:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
                    'e_json' => array(
                        'input' => $_POST,
                        'udata' => $udata,
                    ),
                    'e_type_id' => 27, //New Student Lead
                    'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
                    'e_r_id' => $focus_class['r_id'],
                ));
            }

        } else {

            //This is a registered user!
            $udata = $users[0];

            //Make sure they have not enrolled in this Class before:
            $duplicate_registries = $this->Db_model->ru_fetch(array(
                'ru.ru_u_id'	   => $udata['u_id'],
                'ru.ru_r_id'	   => $focus_class['r_id'],
            ));
            if(count($duplicate_registries)>0){

                //Send the email to their admission page:
                $this->Email_model->email_intent($duplicate_registries[0]['r_b_id'],2697,$udata);

                if($duplicate_registries[0]['ru_status']==0){

                    $u_key = md5($udata['u_id'] . $application_status_salt);

                    //They still need to complete their application for this Class, redirect them to the next step:
                    //Logic is inspired from my_applications.php file
                    if(strlen($duplicate_registries[0]['ru_application_survey'])==0){
                        //Need to complete the Application:
                        die(echo_json(array(
                            'status' => 1,
                            'hard_redirect' => '/my/class_application/'.$duplicate_registries[0]['ru_id'].'?u_key='.$u_key.'&u_id='.$udata['u_id'],
                        )));
                    } elseif($duplicate_registries[0]['r_usd_price']>0){
                        //This must be the case if they have already completed the Application:
                        die(echo_json(array(
                            'status' => 0,
                            'error_message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> You have already submitted your Application Questionnaire for this class. To complete your application, we emailed you a link to pay your tuition online. Check your email to continue.</div>',
                        )));
                    } else {
                        //This should not happen! Log Error:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => $udata['u_id'],
                            'e_message' => 'Student admission was incomplete, but they had already submitted their Questionnaire and the Class is Free!',
                            'e_json' => $duplicate_registries,
                            'e_type_id' => 8, //System Error
                            'e_b_id' => $bootcamp['b_id'],
                            'e_r_id' => $focus_class['r_id'],
                        ));
                    }

                } else {
                    //Show them an error:
                    die(echo_json(array(
                        'status' => 0,
                        'error_message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> You have already enrolled in this class. Your current status is ['.trim(strip_tags(status_bible('ru',$duplicate_registries[0]['ru_status']))).']. '.($duplicate_registries[0]['ru_status']==-2 ? '<a href="/contact"><u>Contact us</u></a> if you like to restart your application for this class.' : 'We emailed you a link to manage your admissions. Check your email to continue.').'</div>',
                    )));
                }

            }


            //Check their current application status(es):
            $admissions = $this->Db_model->remix_admissions(array(
                'ru.ru_u_id'	   => $udata['u_id'],
                'ru.ru_r_id !='	   => $focus_class['r_id'],
                'r.r_status >='	   => 1, //Open for admission
                'r.r_status <='	   => 2, //Running
                'ru.ru_status >='  => 0, //Initiated or higher
                'ru.ru_status <='  => 4, //Active Student
            ));

            if(count($admissions)>0){

                //They are enrolled in another Class, let's see if the dates overlap:
                foreach($admissions as $admission){

                    if(($focus_class['r__class_start_time']>=$admission['r__class_start_time'] && $focus_class['r__class_start_time']<$admission['r__class_end_time']) || ($focus_class['r__class_end_time']>=$admission['r__class_start_time'] && $focus_class['r__class_end_time']<$admission['r__class_end_time'])){

                        //Send the email to their admission page:
                        $this->Email_model->email_intent($admission['r_b_id'],2697,$udata);

                        //Either start time or end time falls within this class!
                        $message = 'Admission blocked because you can join a maximum of 1 concurrent Bootcamps. You are already enrolled in ['.$admission['c_objective'].'] that runs between ['.time_format($admission['r__class_start_time'],1).' - '.time_format($admission['r__class_end_time'],1).'].'."\n\n".'This overlaps with your request to join this Bootcamp ['.$bootcamp['c_objective'].'] that runs between ['.time_format($focus_class['r__class_start_time'],1).' - '.time_format($focus_class['r__class_end_time'],1).'].'."\n\n".'We emailed you a link to manage your current admissions.'.( $admission['ru_status']==0 ? ' You can choose to withdraw your application from ['.$admission['c_objective'].'] because your current application status is ['.trim(strip_tags(status_bible('ru',$admission['ru_status']))).'].' : '' );

                        //Log engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => $udata['u_id'],
                            'e_message' => $message,
                            'e_json' => $_POST,
                            'e_type_id' => 9, //Support Needing Graceful Errors
                            'e_b_id' => $bootcamp['b_id'],
                            'e_r_id' => $focus_class['r_id'],
                        ));

                        //show the error:
                        die(echo_json(array(
                            'status' => 0,
                            'error_message' => '<div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> '.nl2br($message).'</div>',
                        )));

                    }
                }
            }
        }


        //At this point we have a user, and they are clear to be admitted into this Class
        $admission_application = array(
            'ru_r_id' 	        => $focus_class['r_id'],
            'ru_u_id' 	        => $udata['u_id'],
            'ru_affiliate_u_id' => 0, //TBD below
        );

        //Lets see if they have an affiliate Cookie:
        $aff_cookie = get_cookie('menchref');
        if(isset($aff_cookie) && $aff_cookie && strlen($aff_cookie)>10){
            //Seems to be something here, lets see:
            $cookie_parts = explode('-',$aff_cookie);
            if(intval($cookie_parts[0]) && $cookie_parts[1]==md5( intval($cookie_parts[0]) . 'c00ki3' . $cookie_parts[2] . intval($cookie_parts[3]) )){

                //Yes, this is a match!
                $admission_application['ru_affiliate_u_id'] = intval($cookie_parts[0]);

                //Log Affiliate Engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $udata['u_id'], //The User ID
                    'e_message' => 'User initiated admission. Pending payment and lead instructor approval.',
                    'e_json' => array(
                        'input' => $_POST,
                        'udata' => $udata,
                        'rudata' => $admissions[0],
                        'cookie' => $aff_cookie,
                        'initial_e_id' => intval($cookie_parts[3]),
                    ),
                    'e_type_id' => 46, //Affiliate User Initiated Admission
                    'e_b_id' => $bootcamp['b_id'],
                    'e_r_id' => $focus_class['r_id'],
                    'e_recipient_u_id' => $admission_application['ru_affiliate_u_id'], //The affiliate ID
                ));
            }
        }

        //Lets start their admission application:
        $admissions[0] = $this->Db_model->ru_create($admission_application);

        //Log engagement for Application Started:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $udata['u_id'],
            'e_json' => array(
                'input' => $_POST,
                'udata' => $udata,
                'rudata' => $admissions[0],
            ),
            'e_type_id' => 29, //Application Started
            'e_b_id' => $bootcamp['b_id'],
            'e_r_id' => $focus_class['r_id'],
        ));

        //Send the email to their admission page:
        $this->Email_model->email_intent($bootcamp['b_id'],2697,$udata);


        //Redirect to application:
        die(echo_json(array(
            'status' => 1,
            'hard_redirect' => '/my/class_application/'.$admissions[0]['ru_id'].'?u_key='.md5($udata['u_id'] . $application_status_salt).'&u_id='.$udata['u_id'],
        )));

	}
	
	function submit_application(){

        //When they submit the Application Questionnaire in step 2 of their admission:
        $application_status_salt = $this->config->item('application_status_salt');
	    if(intval($_POST['ru_id'])<1 || !isset($_POST['u_key']) || !isset($_POST['answers']) || !isset($_POST['u_id']) || intval($_POST['u_id'])<1 || !(md5($_POST['u_id'].$application_status_salt)==$_POST['u_key'])){
	        
	        //Log engagement:
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => ( isset($_POST['u_id']) ? intval($_POST['u_id']) : 0 ),
	            'e_message' => 'submit_application() Missing Core Inputs.',
	            'e_json' => $_POST,
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
	            'e_json' => $_POST,
	            'e_type_id' => 8, //Platform Error
	        ));
	        
	        //Error:
	        die('<span style="color:#FF0000;">Error: Failed to fetch admission data. Report Logged for Admin to review.</span>');
	    }

	    //Attach timestamp:
	    $_POST['answers']['pst_timestamp'] = date("Y-m-d H:i:s");

        //Log Engagement:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $_POST['u_id'],
            'e_json' => $_POST,
            'e_type_id' => 26, //Application submitted
            'e_b_id' => $admissions[0]['b_id'], //Share with bootcamp team
            'e_r_id' => $admissions[0]['r_id'],
        ));

	    //Set updating data:
        $update_data = array(
            'ru_application_survey' => json_encode($_POST['answers']),
        );

        //Is this a free Bootcamp? If so, we can fast forward the payment step...
        if(doubleval($admissions[0]['r_usd_price'])==0){

            //Yes, change the status to Pending Review:
            $update_data['ru_status'] = 2;

            //Log Engagement that this is now ready
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $_POST['u_id'],
                'e_json' => $_POST,
                'e_type_id' => 30, //Free Application Completed
                'e_b_id' => $admissions[0]['b_id'], //Share with bootcamp team
                'e_r_id' => $admissions[0]['r_id'],
            ));

            //Notify student:
            $this->load->model('Email_model');
            if(!$admissions[0]['u_fb_id']){
                //They should activate their MenchBot IF not already done so:
                $this->Email_model->email_intent($admissions[0]['b_id'],2805,$admissions[0]);
            } else {
                //They will get notified that we're reviewing their application
                $this->Email_model->email_intent($admissions[0]['b_id'],2807,$admissions[0]);
            }
        }

	    //Save answers:
	    $this->Db_model->ru_update( intval($_POST['ru_id']) , $update_data);

	    
	    //We're good now, lets redirect to application status page and MAYBE send them to paypal asap:
	    //The "pay_r_id" variable makes the next page redirect to paypal automatically for PAID classes
	    //Show message & redirect:
	    echo '<script> setTimeout(function() { window.location = "/my/applications?pay_r_id='.$admissions[0]['r_id'].'&u_key='.$_POST['u_key'].'&u_id='.$_POST['u_id'].'" }, 1000); </script>';
	    echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span><div>Successfully Submitted 🙌​</div>';
	}

	function withdraw_application(){
        //Validate inputs:
        $application_status_salt = $this->config->item('application_status_salt');
        if(!isset($_POST['u_key']) || !isset($_POST['u_id']) || intval($_POST['u_id'])<1 || !isset($_POST['ru_id']) || intval($_POST['ru_id'])<1 || !(md5($_POST['u_id'].$application_status_salt)==$_POST['u_key'])){
            //Log this error:
            echo_json(array(
                'status' => 0,
                'message' => 'Error: Invalid Inputs',
            ));
        } else {
            //Attempt to withdraw user:
            $admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_status'  => 0, //Initiated or higher as long as bootcamp is running!
                'ru.ru_u_id'	=> $_POST['u_id'],
                'ru.ru_id'	    => $_POST['ru_id'],
            ));

            if(count($admissions)==1){

                //All good, withdraw:
                $this->Db_model->ru_update( $_POST['ru_id'] , array(
                    'ru_status' => -2, //For now this is the default since we don't accept partial payments
                ));

                //Log Engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $admissions[0]['u_id'], //System
                    'e_type_id' => 66, //Application Withdraw
                    'e_b_id' => $admissions[0]['r_b_id'],
                    'e_r_id' => $admissions[0]['r_id'],
                ));

                //Inform User:
                echo_json(array(
                    'status' => 1,
                    'message' => status_bible('ru',-2,0,'top'),
                ));

            } else {

                //Error, Inform User:
                echo_json(array(
                    'status' => 0,
                    'message' => 'Error: Withdraw no longer possible as your application status has changed.',
                ));

            }
        }


    }

	function login(){
	    
	    //Setting for admin logins:
	    $master_password = 'pi980ollmaster';
        $website = $this->config->item('website');
	    
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

        //See if they have any active admissions:
        $admissions = array();
	    if(count($users)==1){
            $admissions = $this->Db_model->remix_admissions(array(
                'ru_u_id' => $users[0]['u_id'],
                'ru_status >=' => 0, //We would drill into this further...
            ));
        }
	    
	    if(count($users)==0){

	        //Not found!
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: '.$_POST['u_email'].' not found.</div>');
	        return false;

	    } elseif($users[0]['u_status']<0){

	        //Inactive account
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $users[0]['u_id'],
	            'e_message' => 'login() denied because account is not active.',
	            'e_json' => $_POST,
	            'e_type_id' => 9, //Support Needing Graceful Errors
	        ));
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Your account has been de-activated. Contact us to re-active your account.</div>');
	        return false;

	    } elseif(!($_POST['u_password']==$master_password) && !($users[0]['u_password']==md5($_POST['u_password']))){

	        //Bad password
	        redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Incorrect password for '.$_POST['u_email'].'.</div>');
	        return false;

	    }

        $co_instructors = array();
        if($users[0]['u_status']==1){
            //Regular user, see if they are assigned to any Bootcamp as co-instructor
            $co_instructors = $this->Db_model->u_bootcamps(array(
                'ba.ba_u_id' => $users[0]['u_id'],
                'ba.ba_status >=' => 1,
                'b.b_status >=' => 0,
            ));
        }

        $session_data = array();
	    //Are they admin?
	    if($users[0]['u_status']>=2 /* || count($co_instructors)>0 */){
	        //They have admin rights:
            $session_data['user'] = $users[0];
        }
        //Are they an active student?
        if(count($admissions)>0){
            //They have admin rights:
            $session_data['uadmission'] = $admissions[0];
        }

        //Applicable for instructors only:
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS')!==false);
        $is_instructor = (isset($session_data['user']) && count($session_data['user'])>0);
        $is_student = (isset($session_data['uadmission']) && count($session_data['uadmission'])>0);

        if($is_instructor && !$is_chrome){
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Login Denied. Mench Console v'.$website['version'].' support <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> only.<br />Wanna know why? <a href="https://support.mench.co/hc/en-us/articles/115003469471"><u>Continue Reading</u> &raquo;</a></div>');
            return false;
        } elseif($is_student && !$is_instructor && !$session_data['uadmission']['u_fb_id']){
            //Make sure Messenger is already activated:
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: You must activate your Facebook Messenger before logging in. We have already sent you an email with the Activation URL.</div>');
            return false;
        } elseif(!$is_instructor && !$is_student){
            //We assume this is a student request:
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: You have not been admitted to any Bootcamps yet. You can only login as a student after you have been approved by your instructor.</div>');
            return false;
        }


        //Log engagement
        if(!($_POST['u_password']==$master_password)){
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $users[0]['u_id'],
                'e_json' => $users[0],
                'e_type_id' => 10, //login
            ));
        }

        
        //All good to go!
        //Load session and redirect:
        $this->session->set_userdata($session_data);
        
        //Append user IP and agent information
        if(isset($_POST['u_password'])){
            unset($_POST['u_password']); //Sensitive information to be removed and NOT logged
        }
        $users[0]['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $users[0]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $users[0]['input_post_data'] = $_POST;

            
        
        if(isset($_POST['url']) && strlen($_POST['url'])>0){
            header( 'Location: '.$_POST['url'] );
        } else {
            //Default:
            if($is_instructor){
                //Instructor default:
                header( 'Location: /console' );
            } else {
                //Student default:
                header( 'Location: /my/actionplan' );
            }
        }
	}
	
	function logout(){
	    //Log engagement:
	    $udata = $this->session->userdata('user');
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => ( isset($udata['u_id']) && $udata['u_id']>0 ? $udata['u_id'] : 0 ),
	        'e_json' => $udata,
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
        $message_max = $this->config->item('message_max');
	    
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
        } elseif(strlen($_POST['u_paypal_email'])>0 && !filter_var($_POST['u_paypal_email'], FILTER_VALIDATE_EMAIL)){
            die('<span style="color:#FF0000;">Error: Invalid Paypal Email. Try again.</span>');
	    } elseif(strlen($_POST['u_image_url'])>0 && (!filter_var($_POST['u_image_url'], FILTER_VALIDATE_URL) || substr($_POST['u_image_url'],0,8)!=='https://')){
	        die('<span style="color:#FF0000;">Error: Invalid HTTPS profile picture url. Try again.</span>');
	    } elseif(strlen($_POST['u_bio'])>$message_max){
	        die('<span style="color:#FF0000;">Error: Introductory Message should be less than '.$message_max.' characters. Try again.</span>');
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
	        'u_email' => trim(strtolower($_POST['u_email'])),
	        'u_phone' => $_POST['u_phone'],
	        'u_image_url' => $_POST['u_image_url'],
	        'u_gender' => $_POST['u_gender'],
	        'u_country_code' => $_POST['u_country_code'],
	        'u_current_city' => $_POST['u_current_city'],
	        'u_timezone' => $_POST['u_timezone'],
	        'u_language' => join(',',$_POST['u_language']),
	        'u_bio' => trim($_POST['u_bio']),
            'u_skype_username' => trim($_POST['u_skype_username']),
            'u_paypal_email' => ( isset($_POST['u_paypal_email']) ? trim(strtolower($_POST['u_paypal_email'])) : null ),
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
	            if(filter_var($_POST['u_website_url'], FILTER_VALIDATE_URL)){
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
	                //$full_url = $sa_value['sa_prefix'].trim($_POST[$sa_key]).$sa_value['sa_postfix'];
	                $u_update[$sa_key] = trim($_POST[$sa_key]);
	            } else {
	                $u_update[$sa_key] = '';
	            }
	        }
	    }
	    
	    //Now update the DB:
	    $this->Db_model->u_update(intval($_POST['u_id']) , $u_update);


	    //Refetch some DB (to keep consistency with login session format) & update the Session:
        $users = $this->Db_model->u_fetch(array(
            'u_id' => intval($_POST['u_id']),
        ));
        if(isset($users[0])){
            $this->session->set_userdata(array('user' => $users[0]));
        }


	    //Remove sensitive data before logging:
	    unset($_POST['u_password_new']);
	    unset($_POST['u_password_current']);
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	        'e_message' => readable_updates($u_current[0],$u_update,'u_'),
	        'e_json' => array(
	            'input' => $_POST,
	            'before' => $u_current[0],
	            'after' => $u_update,
	        ),
	        'e_type_id' => 12, //Account Update
	        'e_recipient_u_id' => intval($_POST['u_id']), //The user that their account was updated
	    ));
	    
	    //TODO update algolia
	    
	    //Show result:
	    echo ( $warning ? '<span style="color:#FF8C00;">Saved all except: '.$warning.'</span>' : '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
	}

    function completion_report(){

        if(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0
            || !isset($_POST['b_id']) || intval($_POST['b_id'])<=0
            || !isset($_POST['r_id']) || intval($_POST['r_id'])<=0
            || !isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
            die('<span style="color:#FF0000;">Error: Invalid Inputs ID.</span>');
        } elseif(!isset($_POST['next_c_id']) || !isset($_POST['next_level'])){
            die('<span style="color:#FF0000;">Error: Missing next task information.</span>');
        } elseif(!isset($_POST['page_loaded']) || (time()-intval($_POST['page_loaded']))>1800){
            die('<span style="color:#FF0000;">Error: Page was idle for more than 30 minutes. Refresh the page and try again.</span>');

            //Submission settings:
        } elseif($_POST['require_url'] && count(extract_urls($_POST['us_notes']))<1){
            die('<span style="color:#FF0000;">Error: URL Required. <a href=""><b><u>Refresh this page</u></b></a> and try again.</span>');
        } elseif($_POST['require_notes'] && strlen($_POST['us_notes'])<1){
            die('<span style="color:#FF0000;">Error: Notes Required. <a href=""><b><u>Refresh this page</u></b></a> and try again.</span>');
        }

        //Fetch student name and details:
        $matching_admissions = $this->Db_model->ru_fetch(array(
            'ru_u_id' => intval($_POST['u_id']),
            'ru_r_id' => intval($_POST['r_id']),
            'ru_status' => 4, //Active students can submit tasks
        ));

        if(!(count($matching_admissions)==1)){
            die('<span style="color:#FF0000;">Error: Admission not found</span>');
        }

        //Fetch intent, an easy test to pass:
        $original_intents = $this->Db_model->c_fetch(array(
            'c.c_id' => intval($_POST['c_id']),
        ));
        if(count($original_intents)<=0){
            die('<span style="color:#FF0000;">Error: Invalid Task ID</span>');
        }


        //Fetch full Bootcamp/Class data for this:
        $bootcamps = fetch_action_plan_copy(intval($_POST['b_id']),intval($_POST['r_id']));
        $focus_class = $bootcamps[0]['this_class'];


        if(!$focus_class){
            die('<span style="color:#FF0000;">Error: Invalid Class ID!</span>');
        } elseif($focus_class['r__current_milestone']<0 || $focus_class['r_status']>2){
            die('<span style="color:#FF0000;">Error: Class has ended so you can no longer mark tasks as complete.</span>');
        }

        //Now make sure this student has not submitted this task before:
        $us_data = $this->Db_model->us_fetch(array(
            'us_student_id' => intval($_POST['u_id']),
            'us_r_id' => intval($_POST['r_id']),
            'us_c_id' => intval($_POST['c_id']),
            'us_status' => 1,
        ));

        if(count($us_data)>0){
            die('<span style="color:#FF0000;">Error: You have already marked this task as complete. You cannot re-submit it, but you can share updates with your instructor on MenchBot.</span>');
        }

        //Fetch next intent:
        $next_level = intval($_POST['next_level']);
        $next_c_id = intval($_POST['next_c_id']);
        $next_intents = $this->Db_model->c_fetch(array(
            'c.c_id' => $next_c_id,
        ));

        //Now update the DB:
        $us_data = $this->Db_model->us_create(array(
            'us_student_id' => $matching_admissions[0]['u_id'],
            'us_b_id' => intval($_POST['b_id']),
            'us_r_id' => intval($_POST['r_id']),
            'us_c_id' => intval($_POST['c_id']),
            'us_time_estimate' => $original_intents[0]['c_time_estimate'], //A snapshot of its time-estimate upon completion
            'us_student_notes' => trim($_POST['us_notes']),
            'us_status' => 1, //Submitted
        ));


        //Do we need to send any notifications?
        if(strlen(trim($_POST['us_notes']))>0){

            //Send email to all instructors of this Bootcamp:
            $bootcamp_instructors = $this->Db_model->ba_fetch(array(
                'ba.ba_b_id' => intval($_POST['b_id']),
                'ba.ba_status >=' => 2, //co-instructors & lead instructor
                'u.u_status >=' => 1, //Must be a user level 1 or higher
            ));

            $student_name = ( isset($matching_admissions[0]['u_fname']) && strlen($matching_admissions[0]['u_fname'])>0 ? $matching_admissions[0]['u_fname'].' '.$matching_admissions[0]['u_lname'] : 'System' );
            $subject = '⚠️ Review Task Completion '.( strlen(trim($_POST['us_notes']))>0 ? 'Comment' : '(Without Comment)' ).' by '.$student_name;
            $bootcamp_chat_url = 'https://mench.co/console/'.intval($_POST['b_id']).'/students';
            $div_style = ' style="padding:5px 0; font-family: Lato, Helvetica, sans-serif; font-size:16px;"';
            $this->load->model('Email_model');
            //Send notifications to current instructor
            foreach($bootcamp_instructors as $bi){
                //Make sure this instructor has an email on file
                if(strlen($bi['u_email'])>0){
                    //Task Completion Email:
                    //Draft HTML message for this:
                    $html_message  = '<div'.$div_style.'>Hi '.$bi['u_fname'].' 👋​</div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>A new Task Completion report is ready for your review:</div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>Bootcamp: '.$bootcamps[0]['c_objective'].'</div>';
                    $html_message .= '<div'.$div_style.'>Class: '.time_format($focus_class['r_start_date'],2).'</div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>Student: '.$student_name.'</div>';
                    $html_message .= '<div'.$div_style.'>Task: '.$original_intents[0]['c_objective'].'</div>';
                    $html_message .= '<div'.$div_style.'>Estimated Time: '.echo_time($original_intents[0]['c_time_estimate'],0).'</div>';
                    $html_message .= '<div'.$div_style.'>Completion Notes: '.( strlen(trim($_POST['us_notes']))>0 ? nl2br(trim($_POST['us_notes'])) : 'None' ).'</div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>You can chat with this student here: <a href="'.$bootcamp_chat_url.'" target="_blank">'.$bootcamp_chat_url.'</a></div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>Cheers,</div>';
                    $html_message .= '<div'.$div_style.'>Team Mench</div>';
                    $html_message .= '<div><img src="https://s3foundation.s3-us-west-2.amazonaws.com/c65a5ea7c0dd911074518921e3320439.png" /></div>';
                    //Send Email:
                    $this->Email_model->send_single_email(array($bi['u_email']),$subject,$html_message);
                }
            }
        }



        //See if we need to dispatch any messages:
        $on_complete_messages = array();
        $drip_messages = array();

        //Dispatch messages for this Task:
        $task_messages = extract_level($bootcamps[0],$_POST['c_id']);

        foreach($task_messages['intent']['c__messages'] as $i){
            if($i['i_status']==2){
                //Add a reference button to Drip messages:
                $i['i_message'] = $i['i_message'].' {button}';
                array_push($drip_messages , $i);
            } elseif($i['i_status']==3){
                array_push( $on_complete_messages , echo_i(array_merge( $i , array(
                    'e_initiator_u_id' => 0,
                    'e_recipient_u_id' => $matching_admissions[0]['u_id'],
                    'i_c_id' => $i['i_c_id'],
                    'e_b_id' => intval($_POST['b_id']),
                    'e_r_id' => intval($_POST['r_id']),
                )), $matching_admissions[0]['u_fname'],true ) );
            }
        }

        //Is a Milestone Completed? Dispatch potential messages:
        if($next_level<=2){

            //The Milestone does seem complete:
            foreach($task_messages['task_milestone']['c__messages'] as $i){
                if($i['i_status']==2){

                    //Add a reference button to Drip messages:
                    $i['i_message'] = $i['i_message'].' {button}';
                    array_push($drip_messages, $i);

                } elseif($i['i_status']==3){

                    array_push( $on_complete_messages , echo_i(array_merge( $i , array(
                        'e_initiator_u_id' => 0,
                        'e_recipient_u_id' => $matching_admissions[0]['u_id'],
                        'i_c_id' => $i['i_c_id'],
                        'e_b_id' => intval($_POST['b_id']),
                        'e_r_id' => intval($_POST['r_id']),
                    )), $matching_admissions[0]['u_fname'],true ));

                }
            }

            //Is the Bootcamp Complete?
            if($next_level==1){
                //Seems so!
                foreach($bootcamps[0]['c__messages'] as $i){
                    //Bootcamps only could have ON-COMPLETE messages:
                    if($i['i_status']==3){
                        array_push( $on_complete_messages , echo_i(array_merge( $i , array(
                            'e_initiator_u_id' => 0,
                            'e_recipient_u_id' => $matching_admissions[0]['u_id'],
                            'i_c_id' => $i['i_c_id'],
                            'e_b_id' => intval($_POST['b_id']),
                            'e_r_id' => intval($_POST['r_id']),
                        )), $matching_admissions[0]['u_fname'],true ));
                    }
                }
            }
        }

        //Anything to be sent instantly?
        if(count($on_complete_messages)>0 && $matching_admissions[0]['u_fb_id']>0){
            //Dispatch all Instant Messages, their engagements have already been logged:
            $this->Facebook_model->batch_messages('381488558920384', $matching_admissions[0]['u_fb_id'], $on_complete_messages);
        }

        //Any Drip Messages? Schedule them if we have some:
        if(count($drip_messages)>0){

            $start_time = time();
            $drip_intervals = ($focus_class['r__class_end_time']-$start_time) / (count($drip_messages)+1);
            $drip_time = $start_time;

            foreach($drip_messages as $i){

                $drip_time += $drip_intervals;
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => 0, //System
                    'e_recipient_u_id' => $matching_admissions[0]['u_id'],
                    'e_timestamp' => date("Y-m-d H:i:s" , $drip_time ), //Used by Cron Job to fetch this Drip when due
                    'e_json' => array(
                        'created_time' => date("Y-m-d H:i:s" , $start_time ),
                        'drip_time' => date("Y-m-d H:i:s" , $drip_time ),
                        'i_drip_count' => count($drip_messages),
                        'i' => $i, //The actual message that would be sent
                    ),
                    'e_type_id' => 52, //Pending Drip
                    'e_cron_job' => 0, //Pending for the Drip Cron
                    'e_i_id' => $i['i_id'],
                    'e_c_id' => $i['i_c_id'],
                    'e_b_id' => intval($_POST['b_id']),
                    'e_r_id' => intval($_POST['r_id']),
                ));

            }
        }



        //Show result to student:
        echo_us($us_data);


        //Log Engagement for new completion:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $matching_admissions[0]['u_id'],
            'e_message' => $us_data['us_student_notes'],
            'e_json' => array(
                'input' => $_POST,
                'scheduled_drip' => count($drip_messages),
                'sent_oncomplete' => count($on_complete_messages),
                'next_level' => $next_level,
                'next_c' => ( isset($next_intents[0]) ? $next_intents[0] : array() ),
            ),
            'e_type_id' => 33, //Marked as Done Report
            'e_b_id' => $us_data['us_b_id'], //Share with bootcamp team
            'e_r_id' => $us_data['us_r_id'],
            'e_c_id' => $us_data['us_c_id'],
        ));


        //See what the next level is...
        if($next_level==1 && isset($next_intents[0])){

            //The next level is the Bootcamp, which means this was the last Task:
            $this->Db_model->ru_update( $matching_admissions[0]['ru_id'] , array(
                'ru_current_milestone' => ($focus_class['r__total_milestones']+1), //Go 1 milestone after the total milestones to indicate completion
                'ru_current_task' => 1, //They would be at the First task of the next Milestone
            ));

            //Send graduation message:
            $this->Facebook_model->batch_messages( '381488558920384', $matching_admissions[0]['u_fb_id'], array(echo_i(array(
                'i_media_type' => 'text',
                'i_message' => 'Congratulations {first_name} for completing your Action Plan 🎉',
                'e_initiator_u_id' => 0, //System/MenchBot
                'e_recipient_u_id' => $matching_admissions[0]['u_id'],
                'e_b_id' => intval($_POST['b_id']),
                'e_r_id' => intval($_POST['r_id']),
            ), $matching_admissions[0]['u_fname'], true )));


        } elseif($next_level==2 && isset($next_intents[0])){

            //We have a next milestone:
            //We also need to change ru_current_milestone to reflect this advancement
            //Fetch the rank of the next milestone:
            foreach($bootcamps[0]['c__child_intents'] as $milestone){
                if($milestone['c_id']==$next_intents[0]['c_id']){
                    //This is the next milestone, update the student positioning here...
                    $this->Db_model->ru_update( $matching_admissions[0]['ru_id'] , array(
                        'ru_current_milestone' => $milestone['cr_outbound_rank'],
                        'ru_current_task' => 1, //They would be at the First task of the next Milestone
                    ));
                    break;
                }
            }

            //Attempt to dispatch some messages:
            $message_result = tree_message($next_intents[0]['c_id'], 0, '381488558920384', intval($_POST['u_id']), 'REGULAR', intval($_POST['b_id']), intval($_POST['r_id']));

        } elseif($next_level==3 && isset($next_intents[0])){

            //Find the Task:
            foreach($bootcamps[0]['c__child_intents'] as $milestone){
                if($milestone['c_status']>=1){
                    foreach($milestone['c__child_intents'] as $task){
                        if($task['c_id']==$next_intents[0]['c_id']){
                            //This is the next milestone, update the student positioning here...
                            $this->Db_model->ru_update( $matching_admissions[0]['ru_id'] , array(
                                'ru_current_task' => $task['cr_outbound_rank'],
                            ));
                            break;
                        }
                    }
                }
            }

            //Show button for next task:
            echo '<div style="font-size:1.2em;"><a href="/my/actionplan/'.$us_data['us_b_id'].'/'.$next_c_id.'" class="btn btn-black">Next <i class="fa fa-arrow-right"></i></a></div>';

        } else {

            //This should not happen!

        }
    }

    /* ******************************
     * r Classes
     ****************************** */
	
	function mark_read(){
	    //Log a Messenger Read engagement to remove the notifications
	    $udata = auth(1);
	    if(isset($udata['u_id']) && $udata['u_id']>0 && strlen($_POST['botkey'])>4){
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $udata['u_id'],
	            'e_json' => array('original_request'=>'console_chat'),
	            'e_type_id' => 1, //Message Read
	        ));
	        echo 'success'; //This will suce the notification
	    }   
	}

    function sync_action_plan(){
        $udata = auth(2,0,$_POST['b_id']);
        if(!$udata){
            echo '<span style="color:#FF0000;">Error: Session Expired.</span>';
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo '<span style="color:#FF0000;">Error: Missing Bootcamp ID.</span>';
        } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){
            echo '<span style="color:#FF0000;">Error: Missing Class ID.</span>';
        } else {
            //All Good, start updating:
            $saved = $this->Db_model->snapshot_action_plan($_POST['b_id'],$_POST['r_id']);
            //Show Message:
            if($saved){
                echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>';
            } else {
                echo '<span style="color:#FF0000;">Unknown Error while trying to save the Action Plan</span>';
            }
        }
    }

	function load_iphone(){
        $udata = auth(1);
        if(!$udata){
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Login again to continue.</span>');
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID</span>');
        } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
            die('<span style="color:#FF0000;">Error: Invalid Intent id.</span>');
        } elseif(!isset($_POST['level']) || intval($_POST['level'])<=0){
            die('<span style="color:#FF0000;">Error: invalid level ID.</span>');
        } else {
            //Load the phone:
            $this->load->view('console/frames/messages' , $_POST);
        }
    }

    function load_classmates(){

	    //Function called form /MY/classmates (student Menchbot) and /Console/Student tab for Instructors!
        if(isset($_POST['psid'])){

            //Fetch all their admissions:
            $admissions = $this->Db_model->remix_admissions(array(
                'u.u_fb_id' => $_POST['psid'],
                'ru.ru_status >=' => 4, //Actively enrolled in or Completed
                'r.r_status >=' => 1, //Open for Admission or Higher
            ));
            $active_admission = filter_active_admission($admissions); //We'd need to see which admission to load

            if(!$active_admission){

                //Ooops, they dont have anything!
                die('<div class="alert alert-danger" role="alert">You are not a student of any Bootcamp</div>');

            } else {

                //Log Engagement for opening the classmates:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $active_admission['u_id'],
                    'e_type_id' => 54, //classmates Opened
                    'e_b_id' => $active_admission['b_id'],
                    'e_r_id' => $active_admission['r_id'],
                ));

                //Set core variables so we can load as we progress:
                $_POST['b_id'] = $active_admission['b_id'];
                $_POST['r_id'] = $active_admission['r_id'];

            }
        }

        //Resume loading:
        if(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){

            die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID</span>');

        } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){

            die('<span style="color:#FF0000;">Error: Invalid Class ID</span>');

        } else {

            //Was this an instructor-side request?
            $is_instructor = (isset($_POST['is_instructor']) && $_POST['is_instructor']);
            if($is_instructor){
                //Authenticate to ensure it's an instructor:
                $udata = auth(1,1,$_POST['b_id']);
            }


            //Fetch full Bootcamp/Class data for this:
            $bootcamps = fetch_action_plan_copy(intval($_POST['b_id']),intval($_POST['r_id']));
            $class = $bootcamps[0]['this_class'];
            $bootcamp = $bootcamps[0];


            //Was it all good? Should be!
            if(count($bootcamps[0]['c__child_intents'])<1){
                die('<span style="color:#FF0000;">Error: No Milestones Yet</span>');
            } elseif(!$class){
                die('<span style="color:#FF0000;">Error: Class Not Found</span>');
            }

            //Set some settings:
            $loadboard_students = $this->Db_model->fetch_classmates($class['r_id']);
            $countries_all = $this->config->item('countries_all');
            $show_top = 0.2; //The rest are not ranked based on points on the student side, instructors will still see entire ranking
            $show_ranking_top = ceil(count($loadboard_students) * $show_top );


            //Are we started? If so, we can calculate the total point:
            $points_awarded = ($class['r__current_milestone']<0 || $class['r__current_milestone']>=1);
            $possible_points = 0;
            if($points_awarded){
                //Calculate how many total points was possible for this completed or mid-way class:
                foreach($bootcamp['c__child_intents'] as $milestone) {
                    if($milestone['c_status']>=1){
                        //Now go through all the tasks:
                        foreach($milestone['c__child_intents'] as $task) {
                            if($task['c_status']>=1 && !($task['c_complete_is_bonus_task']=='t')){
                                $possible_points += $task['c_time_estimate'];
                            }
                        }
                    }
                }
            }

            echo '<table class="table table-condensed table-striped" style="background-color:#E0E0E0; font-size:18px; '.( $is_instructor ? 'max-width:100%; margin-bottom:12px;' : 'max-width:420px; margin:0 auto;' ).'">';

            //First generate classmates's top message:
            echo '<tr style="font-weight:bold; ">';
            echo '<td colspan="7" style="border:1px solid #999; font-size:1em; padding:10px 0; border-bottom:none; text-align:center;">';
                echo '<i class="fa fa-calendar" aria-hidden="true"></i> ';
                //Do some time calculations for the point system:
                if($class['r__current_milestone']==0){
                    //Not started yet!
                    //TODO maybe have a count down timer to make it nicer?
                    echo 'Class not yet started.';
                } elseif($class['r__current_milestone']<0){
                    //Ended!
                    echo 'Class ended '.strtolower(time_diff($class['r__class_end_time'])).' ago';
                } else {
                    //During the class:
                    echo 'Currently @ '.ucwords($bootcamp['b_sprint_unit']).' '.$class['r__current_milestone'].' of '.$class['r__total_milestones'];
                    //echo ' Due '. time_format($class['r__milestones_due'][$class['r__current_milestone']],0).' PST'
                }
            echo '</td>';
            echo '</tr>';

            //Now its header:
            echo '<tr style="font-weight:bold; font-size:0.8em;">';
            echo '<td style="border:1px solid #999; border-right:none; width:38px;">Rank</td>';
            echo '<td style="border:1px solid #999; border-left:none; border-right:none; text-align:left; padding-left:30px;">Student</td>';
            echo '<td style="border:1px solid #999; border-left:none; border-right:none; text-align:left; width:90px;">Progress</td>';
            if($is_instructor){
                echo '<td style="border:1px solid #999; border-left:none; border-right:none; text-align:left; width:40px;">'.ucwords($bootcamp['b_sprint_unit']).'</td>';
                echo '<td style="border:1px solid #999; border-left:none; border-right:none; text-align:left; width:40px;">Task</td>';
            }
            echo '<td style="border:1px solid #999; border-left:none; border-right:1px solid #999; width:25px;">&nbsp;</td>';
            echo '</tr>';

            //Now list all students in order:
            if(count($loadboard_students)>0){

                //List students:
                $rank = 1; //Keeps track of student rankings, which is equal if points are equal
                $counter = 0; //Keeps track of student counts
                $bborder = '';

                foreach($loadboard_students as $key=>$ls){

                    if($show_ranking_top==$counter){
                        echo '<tr>';
                        echo '<td colspan="7" style="background-color:#999; border-right:1px solid #999; color:#FFF; text-align:center;"><span data-toggle="tooltip" title="While only the top '.($show_top*100).'% are ranked, any student who completes all tasks by the end of the class will win the completion awards.">Ranking for top '.($show_top*100).'% only</span></td>';
                        echo '</tr>';
                    }

                    $counter++;
                    if($key>0 && ($ls['points']<$loadboard_students[($key-1)]['points'] || $ls['points']==0)){
                        $rank++;
                    }

                    //Should we show this ranking?
                    $ranking_visible = ($is_instructor || (isset($_POST['psid']) && isset($active_admission) && $active_admission['u_id']==$ls['u_id']) || $counter<=$show_ranking_top);

                    if(!isset($loadboard_students[($key+1)])){
                        //This is the last item, add a botton border:
                        $bborder = 'border-bottom:1px solid #999;';
                    }

                    echo '<tr>';
                    echo '<td valign="top" style="'.$bborder.'border-left:1px solid #999; text-align:center; vertical-align:top;" title="Student #'.$counter.'">'.( $ranking_visible ? echo_rank($rank) : '' ).'</td>';
                    echo '<td valign="top" style="'.$bborder.'text-align:left; vertical-align:top;">';
                    $student_name = '<img src="'.( strlen($ls['u_image_url'])>0 ? $ls['u_image_url'] : '/img/fb_user.jpg' ).'" class="mini-image"> '.$ls['u_fname'].' '.$ls['u_lname'];


                    if(!$is_instructor) {

                        //Show basic list for students:
                        echo $student_name;

                    } else {

                        echo '<a href="javascript:view_el('.$ls['u_id'].','.$bootcamp['c_id'].')" class="plain">';
                        echo '<i class="pointer fa fa-caret-right" id="pointer_'.$ls['u_id'].'_'.$bootcamp['c_id'].'" aria-hidden="true"></i> ';
                        echo $student_name;
                        echo '</a>';

                        echo '<div style="margin-left:5px; border-left:1px solid #999; padding-left:5px;" id="c_el_'.$ls['u_id'].'_'.$bootcamp['c_id'].'" class="hidden">';

                        //Fetch student submissions so far:
                        $us_data = $this->Db_model->us_fetch(array(
                            'us_r_id' => $class['r_id'],
                            'us_student_id' => $ls['u_id'],
                        ));

                        //Go through all the milestones that are due up to now:
                        $open_task_shown = false;

                        foreach($bootcamp['c__child_intents'] as $milestone) {
                            if($milestone['c_status']>=1){

                                $class_has_ended = ($class['r__current_milestone']<0);
                                $milestone_started = ($milestone['cr_outbound_rank']<=$class['r__current_milestone'] || $class_has_ended);
                                $required_tasks = 0;
                                $completed_tasks = 0;
                                $bonus_tasks = 0; //TODO implement later...
                                $pending_revisions = 0; //TODO Implement later...

                                $task_details = null; //To show details when clicked
                                //Calculate the task completion rate and points for this
                                foreach($milestone['c__child_intents'] as $task) {
                                    if($task['c_status']>=1 && !($task['c_complete_is_bonus_task']=='t')){

                                        $required_tasks++;

                                        //What is the status of this task?
                                        if(isset($us_data[$task['c_id']])){

                                            //This student has made a submission:
                                            $us_task_status = $us_data[$task['c_id']]['us_status'];
                                            $completed_tasks += ( $us_task_status>=1 ? 1 : 0 );

                                        } elseif(!$milestone_started || $open_task_shown) {

                                            //Locked:
                                            $us_task_status = -2;

                                        } else {

                                            //Not submitted yet:
                                            $us_task_status = 0;
                                            //Future tasks should be locked:
                                            $open_task_shown = true;

                                        }

                                        $task_details .= '<div>';


                                        $task_details .= '</div>';

                                        //Now show the task submission details:
                                        $task_details .= '<a href="javascript:view_el('.$ls['u_id'].','.$task['c_id'].')" class="plain">';
                                        $task_details .= '<i class="pointer fa fa-caret-right" id="pointer_'.$ls['u_id'].'_'.$task['c_id'].'" aria-hidden="true"></i> ';
                                        $task_details .= status_bible('us',$us_task_status,1,'right');
                                        $task_details .= ' <span data-toggle="tooltip" title="'.str_replace('"', "", str_replace("'", "", $task['c_objective'])).'">Task '.$task['cr_outbound_rank'].'</span>';

                                        $task_details .= ( isset($us_data[$task['c_id']]) ? ' ' . ( strlen($us_data[$task['c_id']]['us_student_notes'])>0 ? ' <i class="fa fa-file-text" aria-hidden="true" data-toggle="tooltip" title="Submission has notes"></i>' : '' ) : '' );
                                        $task_details .= '</a>';

                                        $task_details .= '<div id="c_el_'.$ls['u_id'].'_'.$task['c_id'].'" class="hidden" style="margin-left:5px;">';

                                        if(isset($us_data[$task['c_id']])){
                                            $task_details .= '<div style="width:280px; overflow:hidden; font-size:0.9em; padding:5px; border:1px solid #999;">'.( strlen($us_data[$task['c_id']]['us_student_notes'])>0 ? make_links_clickable($us_data[$task['c_id']]['us_student_notes']) : 'Notes not added.' ).'</div>';
                                        } else {
                                            $task_details .= '<p>Nothing submitted yet.</p>';
                                        }
                                        $task_details .= '</div>';
                                    }
                                }



                                //What is the milestone status based on its tasks?
                                if($pending_revisions>0){
                                    //Some of its tasks are pending revision:
                                    $us_milestone_status = -1;
                                } elseif($completed_tasks>=$required_tasks){
                                    //Completed all tasks:
                                    $us_milestone_status = 1;
                                } elseif(!$milestone_started){
                                    //Not yet started, still locked:
                                    $us_milestone_status = -2;
                                } else {
                                    //Pending completion:
                                    $us_milestone_status = 0;
                                }


                                //Now its content:
                                echo '<div>';
                                echo '<a href="javascript:view_el('.$ls['u_id'].','.$milestone['c_id'].')" class="plain">';
                                echo '<i class="pointer fa fa-caret-right" id="pointer_'.$ls['u_id'].'_'.$milestone['c_id'].'" aria-hidden="true"></i> ';
                                echo '<span data-toggle="tooltip" title="'.str_replace('"', "", str_replace("'", "", $milestone['c_objective'])).'">'.status_bible('us',$us_milestone_status,1,'right').' '.ucwords($bootcamp['b_sprint_unit']).' '.$milestone['cr_outbound_rank'].( $milestone['c_duration_multiplier']>1 ? '-'.($milestone['cr_outbound_rank']+$milestone['c_duration_multiplier']-1) : '' ).'</span>';
                                echo '</a>';

                                if($milestone['cr_outbound_rank']==$class['r__current_milestone']){
                                    echo ' <span class="badge badge-current"><i class="fa fa-hand-o-left" aria-hidden="true"></i> CLASS IS HERE</span>';
                                }

                                echo '</div>';

                                echo '<div id="c_el_'.$ls['u_id'].'_'.$milestone['c_id'].'" style="margin-left:5px; border-left:1px solid #999; padding-left:5px;" class="hidden">';
                                echo $task_details;
                                echo '</div>';

                            }
                        }

                        echo '</div>';
                    }
                    echo '</td>';


                    //Progress, Milestone & Tasks:
                    if($ls['ru_current_milestone']>$class['r__total_milestones']){
                        //They have completed it all, show them as winners!
                        echo '<td valign="top" colspan="'.($is_instructor?'3':'1').'" style="'.$bborder.'text-align:left; vertical-align:top;">';
                        echo '<i class="fa fa-trophy" aria-hidden="true"></i><span style="font-size: 0.8em; padding-left:2px;">WINNER</span>';
                        echo '</td>';
                    } else {
                        //Progress:
                        echo '<td valign="top" style="'.$bborder.'text-align:left; vertical-align:top;">';
                        if($ranking_visible){
                            if($possible_points>0){
                                echo '<span title="'.$ls['points'].'/'.$possible_points.'">'.round( $ls['points']/$possible_points*100 ).'%</span>';
                            } else {
                                echo '---';
                            }
                        }
                        echo '</td>';

                        if($is_instructor){
                            //Milestone:
                            echo '<td valign="top" style="'.$bborder.'text-align:left; vertical-align:top;">';
                            if($ranking_visible){
                                echo $ls['ru_current_milestone'];
                            }
                            echo '</td>';


                            //Task:
                            echo '<td valign="top" style="'.$bborder.'text-align:left; vertical-align:top;">';
                            if($ranking_visible){
                                echo $ls['ru_current_task'];
                            }
                            echo '</td>';
                        }
                    }



                    echo '<td valign="top" style="'.$bborder.'text-align:left; vertical-align:top; border-right:1px solid #999;">'.( isset($countries_all[strtoupper($ls['u_country_code'])]) ? '<img data-toggle="tooltip" data-placement="left" title="'.$countries_all[strtoupper($ls['u_country_code'])].'" src="/img/flags/'.strtolower($ls['u_country_code']).'.png" class="flag" style="margin-top:-3px;" />' : '' ).'</td>';

                    echo '</tr>';

                }

            } else {
                //No students admitted yet:
                echo '<tr style="font-weight:bold; ">';
                echo '<td colspan="7" style="border:1px solid #999; font-size:1.2em; padding:15px 0; text-align:center;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>  No Students Admitted Yet</td>';
                echo '</tr>';
            }

            echo '</table>';

        }

    }

	function tuition_calculator(){
	    //Displays the class timeline based on some inputs:
	    $udata = auth(1);
	    if(!isset($_POST['r_id']) || !isset($_POST['b_id']) || !isset($_POST['r_response_time_hours']) || !isset($_POST['r_meeting_frequency']) || !isset($_POST['r_meeting_duration']) || !isset($_POST['b_sprint_unit']) || !isset($_POST['c__milestone_units']) || !isset($_POST['c__estimated_hours'])){
	        die('<span style="color:#FF0000;">Missing core data: '.print_r($_POST,ture).'</span>');
	    }	    
	    
	    //Set standards for Tuition Calculator:
	    $calculator_logic = array(
	        'base_usd_price' => 11400, //Standard price in the coding bootcamp industry
	        'target_savings' => 0.33, //How much Mench plans to be cheaper because we're fully online
	        'pricing_factors' => array(
	            'handson_work' => array(
	                'weight' => 0.40, //The percentage of importance for this factor relative to other pricing_factors
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
                'weekly_office_hours' => array(
                    'weight' => 0.10, //The percentage of importance for this factor relative to other pricing_factors
                    'name' => 'Weekly Group Sessions',
                    'desc' => 'The total number of weekly hours spent supporting students as a group',
                    'industry_is' => 40,
                    'mench_is' => 0, //To be calculated
                    'mench_what_if' => ( isset($_POST['whatif_weekly_office_hours']) && intval($_POST['whatif_weekly_office_hours'])>0 ? intval($_POST['whatif_weekly_office_hours']) : null ),
                ),
	            'respond_under' => array(
	                'weight' => 0.20, //The percentage of importance for this factor relative to other pricing_factors
	                'name' => 'Response Time',
	                'desc' => 'The average response time of the bootcamp team to student inquiries',
	                'industry_is' => 3, //How fast (in hours) are they committing to respond
	                'mench_is' => 0, //To be calculated
	                'mench_what_if' => ( isset($_POST['whatif_respond_under']) && intval($_POST['whatif_respond_under'])>0 ? intval($_POST['whatif_respond_under']) : null ),
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
	    if($_POST['c__milestone_units']<=0){
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
	        
	        //Log view:
	        $this->Db_model->e_create(array(
	            'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	            'e_type_id' => 48, //View
	            'e_message' => 'Tuition Calculator',
	            'e_b_id' => $focus_class['r_b_id'],
	            'e_r_id' => $focus_class['r_id'],
	            'e_c_id' => 0,
	            'e_recipient_u_id' => 0,
	        ));
	        
	        //Calculate remaining elements:
	        $c__estimated_hours = intval($_POST['c__estimated_hours']);
	        $whatif_selected = ( isset($_POST['whatif_selection']) && intval($_POST['whatif_selection'])>0 ? intval($_POST['whatif_selection']) : null );
	        $calculator_logic['pricing_factors']['personalized_mentorship']['mench_is'] = gross_mentorship($_POST['r_meeting_frequency'],$_POST['r_meeting_duration'],$_POST['b_sprint_unit'],$_POST['c__milestone_units'],false);
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
        	        echo '<td style="text-align:left;"><span style="width:220px; display: inline-block;" data-toggle="tooltip" title="The amount of time it takes students to accomplish the Bootcamp Outcome" data-placement="top"><i class="fa fa-info-circle" aria-hidden="true"></i> Bootcamp Duration</span></td>';
        	        echo '<td style="text-align:right;">24 Weeks</td>';
        	        echo '<td style="text-align:right;">'.$_POST['c__milestone_units'].' '.ucwords($_POST['b_sprint_unit']).($_POST['c__milestone_units']==1?'':'s').'</td>';
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
	    }
    	    
	    
	}

	function simulate_milestone(){
	    //Dispatch Messages:
        $results = tree_message($_POST['c_id'], $_POST['depth'], '381488558920384', $_POST['u_id'], 'REGULAR', $_POST['b_id'], 0);

        if($results['status']){
            echo '<i class="fa fa-check-circle" style="color:#3C4858;" title="SUCCESS: '.$results['message'].'" aria-hidden="true"></i>';
        } else {
            echo '<i class="fa fa-exclamation-triangle" style="color:#FF0000;" title="ERROR: '.$results['message'].'" aria-hidden="true"></i>';
        }
    }

	function class_timeline(){

	    //Displays the class timeline based on some inputs:
	    if(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
	        die('<span style="color:#000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pick a start date to see class timeline.</span>');
	    } elseif(!isset($_POST['r_start_time_mins'])){
	        die('<span style="color:#000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Pick a start time to see class timeline.</span>');
	    } elseif(!isset($_POST['c__milestone_units']) || intval($_POST['c__milestone_units'])<=0){
	        die('<span style="color:#000;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Add some Milestones to your Action Plan to see class timeline.</span>');
	    } elseif(!isset($_POST['b_sprint_unit'])){
	        die('<span style="color:#FF0000;">Error: Missing Milestone Duration.</span>');
	    } elseif(!isset($_POST['b_id'])){
	        die('<span style="color:#FF0000;">Error: Missing Bootcamp ID.</span>');
	    } elseif(!isset($_POST['b_status'])){
	        die('<span style="color:#FF0000;">Error: Missing Bootcamp Status.</span>');
	    }
	    
	    $_POST['c__milestone_units'] = intval($_POST['c__milestone_units']);
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
	        echo '<li>Bootcamp Duration: <b>'.$_POST['c__milestone_units'].' '.ucwords($_POST['b_sprint_unit']).($_POST['c__milestone_units']==1?'':'s').' <a href="/console/'.$_POST['b_id'].'/actionplan"><i class="fa fa-list-ol" aria-hidden="true"></i> Action Plan</a></b></li>';
	        echo '<li>Class Ends: <b>'.time_format($_POST['r_start_date'],2,(calculate_duration(array('b_sprint_unit'=>$_POST['b_sprint_unit']),$_POST['c__milestone_units']))).' '.$start_times[$_POST['r_start_time_mins']].' PST</b></li>';
    	    echo '<li>Performance Payout by: <b>'.time_format($_POST['r_start_date'],2,(calculate_duration(array('b_sprint_unit'=>$_POST['b_sprint_unit']),$_POST['c__milestone_units'])+13)).' 6:00pm PST</b> <a href="https://support.mench.co/hc/en-us/articles/115002473111" title="Learn more about Mench Payouts" target="_blank"><i class="fa fa-info-circle" aria-hidden="true"></i></a></li>';
    	    echo '</ul>';
	}

	function r_create(){
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
        } elseif(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
            die('<span style="color:#FF0000;">Error: Enter valid start date.</span>');
        } elseif((strtotime($_POST['r_start_date'])+($_POST['r_start_time_mins']*60))<time()){
            die('<span style="color:#FF0000;">Error: Cannot have a start date in the past.</span>');
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
	            die('<span style="color:#FF0000;">Error: Cannot have two classes starting on the same day.</span>');
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

	                //Remove all additional appended data and certain non-replicatable data fields like r_id:
                    foreach($class_data as $key=>$value){
                        if(substr_count($key,'r__')>0 || in_array($key,array('r_id'))){
                            //This is an appended data field:
                            unset($class_data[$key]);
                        }
                    }
	            }
	        }
	        
	        
	        //Did we build it?
	        if(!$class_data){
	            
	            //Generate core data:
	            $class_data = array(
	                'r_b_id' => intval($_POST['r_b_id']),
	                'r_start_date' => date("Y-m-d",strtotime($_POST['r_start_date'])),
	                'r_start_time_mins' => intval($_POST['r_start_time_mins']),
	                'r_status' => intval($_POST['r_status']),
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
	            'e_json' => array(
	                'input' => $_POST,
	                'before' => array(),
	                'after' => $class,
	            ),
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
	            'e_json' => array(
	                'input' => $_POST,
	                'before' => @unserialize($classes[0]['r_live_office_hours']),
	                'after' => @unserialize($r_update['r_live_office_hours']),
	            ),
	            'e_type_id' => 13, //Class Updated
	            'e_b_id' => $classes[0]['r_b_id'], //Share with bootcamp team
	            'e_r_id' => intval($_POST['r_id']),
	        ));
	    }
	    
	    //Show result:
	    die('<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>');
	}

	function class_edit(){

        $message_max = $this->config->item('message_max');
	    $udata = auth(2);
	    if(!$udata){
	        //Display error:
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['r_start_date']) || !strtotime($_POST['r_start_date'])){
	        //TODO make sure its monday
	        die('<span style="color:#FF0000;">Error: Enter valid start date.</span>');
        //} elseif((strtotime($_POST['r_start_date'])+($_POST['r_start_time_mins']*60))){
            //TODO Put back in place
            //die('<span style="color:#FF0000;">Error: Cannot have a start date in the past.</span>');
	    } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Class ID.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['r_status'])){
	        die('<span style="color:#FF0000;">Error: Missing Class Status.</span>');
	    } elseif(strlen($_POST['r_office_hour_instructions'])>$message_max){
	        die('<span style="color:#FF0000;">Error: Contact Instructions Message must be less than '.$message_max.' characters long.</span>');
	    } elseif(strlen($_POST['r_closed_dates'])>$message_max){
	        die('<span style="color:#FF0000;">Error: Close Dates Message must be less than '.$message_max.' characters long.</span>');
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
	        die('<span style="color:#FF0000;">Error: Cannot have two classes starting on the same day.</span>');
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
	    );
	    
	    if(isset($_POST['r_live_office_hours_check']) && !intval($_POST['r_live_office_hours_check'])){
	        //User the office schedule off, lets disable it:
	        $r_update['r_live_office_hours'] = null;
	    }	    
	    
	    //Save
	    $this->Db_model->r_update( intval($_POST['r_id']) , $r_update);


	    //Determine what type of engagement is this?
        if($r_update['r_status']==-3 && $r_update['r_status']!=$classes[0]['r_status']){
            //Abandoned:
            $e_type_id = 57;
        } elseif($r_update['r_status']==-1 && $r_update['r_status']!=$classes[0]['r_status']){
            //Archived:
            $e_type_id = 16;
        } else {
            //Simply updated:
            $e_type_id = 13;
        }
	    
	    //Log engagement:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'], //The user
	        'e_message' => ( $e_type_id==13 ? readable_updates($classes[0],$r_update,'r_') : null ),
	        'e_json' => array(
	            'input' => $_POST,
	            'before' => $classes[0],
	            'after' => $r_update,
	        ),
	        'e_type_id' => $e_type_id,
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
	        die('<span style="color:#FF0000;">Error: Outcome must be 2 characters or longer.</span>');
	    } elseif(!isset($_POST['b_sprint_unit']) || !in_array($_POST['b_sprint_unit'],array('week','day'))){
	        die('<span style="color:#FF0000;">Error: Invalid Milestone Duration.</span>');
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
                'e_json' => $_POST,
                'e_type_id' => 8, //Platform Error
            ));
            //Display error:
            die('<span style="color:#FF0000;">Error: Unkown error while trying to create intent.</span>');
        }
        
        
        //Generaye URL Key:
        //Cleans text:
        $generated_key = generate_hashtag($_POST['c_objective']);
        
        
        //Check for duplicates:
        $bootcamps = $this->Db_model->b_fetch(array(
            'LOWER(b.b_url_key)' => strtolower($generated_key),
        ));
        if(count($bootcamps)>0){
            //Ooops, we have a duplicate:
            $generated_key = $generated_key.'-'.rand(0,99999);
        }

        //Fetch default list values:
        $default_class_questions = $this->config->item('default_class_questions');
        $default_class_prerequisites = $this->config->item('default_class_prerequisites');
        $default_class_prizes = $this->config->item('default_class_prizes');

        //Create new bootcamp:
        $bootcamp = $this->Db_model->b_create(array(
            'b_creator_id' => $udata['u_id'],
            'b_url_key' => $generated_key,
            'b_sprint_unit' => $_POST['b_sprint_unit'],
            'b_c_id' => $intent['c_id'],
            'b_prerequisites' => json_encode($default_class_prerequisites),
            'b_application_questions' => json_encode($default_class_questions),
            'b_completion_prizes' => json_encode($default_class_prizes),
        ));

        if(intval($bootcamp['b_id'])<=0){
            //Log this error:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $udata['u_id'],
                'e_message' => 'bootcamp_create() Function failed to create bootcamp for intent #'.$intent['c_id'],
                'e_json' => $_POST,
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
                'e_json' => $_POST,
                'e_type_id' => 8, //Platform Error
            ));
            die('<span style="color:#FF0000;">Error: Unkown error while trying to set bootcamp leader.</span>');
        }

        
        //Log Engagement for Intent Created:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $udata['u_id'],
            'e_message' => '['.$intent['c_objective'].'] created as a new intent',
            'e_json' => array(
                'input' => $_POST,
                'before' => null,
                'after' => $intent,
            ),
            'e_type_id' => 20, //Intent Created
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
            'e_c_id' => $intent['c_id'],
        ));
        
        
        //Log Engagement for Bootcamp Created:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $udata['u_id'],
            'e_message' => 'Bootcamp #'.$bootcamp['b_id'].' created for ['.$intent['c_objective'].'] intent',
            'e_json' => array(
                'input' => $_POST,
                'before' => null,
                'after' => $bootcamp,
            ),
            'e_type_id' => 15, //Bootcamp Created
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
        ));
        
        
        //Log Engagement for Permission Granted:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $udata['u_id'],
            'e_message' => $udata['u_fname'].' '.$udata['u_lname'].' assigned as Bootcamp Leader',
            'e_json' => array(
                'input' => $_POST,
                'before' => null,
                'after' => $admin_status,
            ),
            'e_type_id' => 25, //Permission Granted
            'e_b_id' => $bootcamp['b_id'], //Share with bootcamp team
        ));
        
        
        //Show message & redirect:
        //For fancy UI to give impression of hard work:
        sleep(2);
        echo '<script> setTimeout(function() { window.location = "/console/'.$bootcamp['b_id'].'" }, 1000); </script>';
        echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span><div>Going to Bootcamp Dashboard...</div>';
	}

    function save_b_list(){
        //Auth user and Load object:
        $udata = auth(2);
        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
        } elseif(!isset($_POST['group_id']) || !in_array($_POST['group_id'],array('b_target_audience','b_prerequisites','b_application_questions','b_transformations','b_completion_prizes'))){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Group ID',
            ));
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
        } else {

            //Updatye bootcamp:
            $this->Db_model->b_update( intval($_POST['b_id']) , array(
                $_POST['group_id'] => ( isset($_POST['new_sort']) && is_array($_POST['new_sort']) && count($_POST['new_sort'])>0 ? json_encode($_POST['new_sort']) : null ),
            ));

            //Log Engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $udata['u_id'],
                'e_json' => $_POST,
                'e_type_id' => 53, //Bootcamp List Modified
                'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
            ));

            //Display message:
            echo_json(array(
                'status' => 1,
                'message' => '<i class="fa fa-check" aria-hidden="true"></i> Saved',
            ));
        }
    }

    function save_modify(){

        //Auth user and check required variables:
        $udata = auth(2);

        //Validate Bootcamp ID:
        $bootcamps = $this->Db_model->b_fetch(array(
            'b.b_id' => intval($_POST['b_id']),
        ));

        //Validate Original intent:
        $original_intents = $this->Db_model->c_fetch(array(
            'c.c_id' => intval($_POST['pid']),
        ) , 0 );

        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
            return false;
        } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
            return false;
        } elseif(!isset($_POST['level']) || intval($_POST['level'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing level',
            ));
            return false;
        } elseif(!isset($_POST['c_objective']) || strlen($_POST['c_objective'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Outcome',
            ));
            return false;
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
            return false;
        } elseif($_POST['level']==1 && !isset($_POST['b_sprint_unit'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Milestone Duration',
            ));
            return false;
        } elseif($_POST['level']==1 && !isset($_POST['b_url_key'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp URL',
            ));
            return false;
        } elseif($_POST['level']==1 && !isset($_POST['b_status'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp Status',
            ));
            return false;
        } elseif($_POST['level']==2 && !isset($_POST['c_duration_multiplier'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Milestone Duration',
            ));
            return false;
        } elseif($_POST['level']==2 && !isset($_POST['c_status'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Milestone Status',
            ));
            return false;
        } elseif($_POST['level']>=3 && !isset($_POST['c_status'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Task Status',
            ));
            return false;
        } elseif($_POST['level']>=3 && !isset($_POST['c_time_estimate'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Time Estimate',
            ));
            return false;
        } elseif($_POST['level']>=3 && (!isset($_POST['c_complete_url_required']) || !isset($_POST['c_complete_notes_required']) || !isset($_POST['c_complete_is_bonus_task']))){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Completion Settings',
            ));
            return false;
        } elseif(count($bootcamps)<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp ID',
            ));
            return false;
        } elseif(count($original_intents)<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Invalid PID',
            ));
            return false;
        }


        //Process data based on level:
        if($_POST['level']==1){

            //Did the Bootcamp's Intent Outcome change?
            if(!(trim($_POST['c_objective'])==$original_intents[0]['c_objective'])){
                //Generate Update Array
                $c_update = array(
                    'c_objective' => trim($_POST['c_objective']),
                );
            }

            //Did any of the Bootcamp fields change?
            if(!($bootcamps[0]['b_sprint_unit']==$_POST['b_sprint_unit']) || !($bootcamps[0]['b_status']==$_POST['b_status']) || !($bootcamps[0]['b_url_key']==$_POST['b_url_key'])){

                //Fetch reserved terms:
                $reserved_hashtags = $this->config->item('reserved_hashtags');

                //Validate URL Key to be unique:
                $duplicate_bootcamps = $this->Db_model->b_fetch(array(
                    'LOWER(b.b_url_key)' => strtolower($_POST['b_url_key']),
                    'b.b_id !=' => intval($_POST['b_id']),
                ));

                //Check URL Key:
                if(in_array(strtolower($_POST['b_url_key']),$reserved_hashtags)){
                    echo_json(array(
                        'status' => 0,
                        'message' => '"'.$_POST['b_url_key'].'" cannot be used as its reserved.',
                    ));
                    return false;
                } elseif(strlen($_POST['b_url_key'])>30){
                    echo_json(array(
                        'status' => 0,
                        'message' => 'URL Key should be less than 30 characters',
                    ));
                    return false;
                } elseif(strlen($_POST['b_url_key'])<5){
                    echo_json(array(
                        'status' => 0,
                        'message' => 'URL Key should be at least 5 characters long',
                    ));
                    return false;
                } elseif(ctype_digit($_POST['b_url_key'])){
                    echo_json(array(
                        'status' => 0,
                        'message' => 'URL Key should have at-least 1 letter.',
                    ));
                    return false;
                } elseif(!(strtolower(generate_hashtag($_POST['b_url_key']))==strtolower($_POST['b_url_key']))){
                    echo_json(array(
                        'status' => 0,
                        'message' => 'URL Key can only include letters a-z and numbers 0-9',
                    ));
                    return false;
                } elseif(count($duplicate_bootcamps)>0){
                    echo_json(array(
                        'status' => 0,
                        'message' => 'URL Key <a href="/'.$_POST['b_url_key'].'" target="_blank">'.$_POST['b_url_key'].'</a> already taken.',
                    ));
                    return false;

                }

                //Updatye bootcamp:
                $b_update = array(
                    'b_status' => $_POST['b_status'],
                    'b_sprint_unit' => $_POST['b_sprint_unit'],
                    'b_url_key' => $_POST['b_url_key'],
                );
                $this->Db_model->b_update( intval($_POST['b_id']) , $b_update );


                //Bootcamp Edit is the default engagement:
                $engagement_type_id = 18;

                //Did the status change? Log Engagement for this:
                if(!(intval($_POST['b_status'])==intval($bootcamps[0]['b_status']))){
                    if(intval($_POST['b_status'])<0){
                        //Archived:
                        $engagement_type_id = 17;
                    } elseif(intval($_POST['b_status'])==1) {
                        //Request to publish
                        $engagement_type_id = 37;
                    } elseif(intval($_POST['b_status'])==2) {
                        //Published Privately
                        $engagement_type_id = 67;
                    } elseif(intval($_POST['b_status'])==3) {
                        //Published to Marketplace
                        $engagement_type_id = 68;
                    }
                }

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $udata['u_id'],
                    'e_message' => ( $engagement_type_id==18 ? readable_updates($bootcamps[0],$b_update,'b_') : null ),
                    'e_json' => array(
                        'input' => $_POST,
                        'before' => $bootcamps[0],
                        'after' => $b_update,
                    ),
                    'e_type_id' => $engagement_type_id,
                    'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
                ));
            }

        } elseif($_POST['level']==2){

            $c_update = array(
                'c_objective' => trim($_POST['c_objective']),
                'c_status' => intval($_POST['c_status']),
                'c_duration_multiplier' => $_POST['c_duration_multiplier'],
            );

        } elseif($_POST['level']==3){

            $c_update = array(
                'c_objective' => trim($_POST['c_objective']),
                'c_status' => intval($_POST['c_status']),
                'c_time_estimate' => floatval($_POST['c_time_estimate']),
                'c_complete_url_required' => ( intval($_POST['c_complete_url_required']) ? 't' : 'f' ),
                'c_complete_notes_required' => ( intval($_POST['c_complete_notes_required']) ? 't' : 'f' ),
                'c_complete_is_bonus_task' => ( intval($_POST['c_complete_is_bonus_task']) ? 't' : 'f' ),
            );

        }



        //Did we have any intent updating to do?
        if(isset($c_update) && count($c_update)>0){

            //Now update the DB:
            $this->Db_model->c_update( intval($_POST['pid']) , $c_update);

            //Update Algolia:
            //$this->Db_model->sync_algolia(intval($_POST['pid']));

            //Log Engagement for New Intent Link:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $udata['u_id'],
                'e_message' => readable_updates($original_intents[0],$c_update,'c_'),
                'e_json' => array(
                    'input' => $_POST,
                    'before' => $original_intents[0],
                    'after' => $c_update,
                ),
                'e_type_id' => ( $_POST['level']>=2 && isset($c_update['c_status']) && $c_update['c_status']<0 ? 21 : 19 ), //Intent Deleted OR Updated
                'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
                'e_c_id' => intval($_POST['pid']),
            ));

        }

        //Show success:
        echo_json(array(
            'status' => 1,
            'message' => '<span><i class="fa fa-check" aria-hidden="true"></i> Saved</span>',
        ));

	}

	/* ******************************
	 * c Intents
	 ****************************** */
	
	function intent_create(){
	    
	    $udata = auth(2);
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
	    } elseif(!isset($_POST['c_objective']) || strlen($_POST['c_objective'])<=0){
	        die('<span style="color:#FF0000;">Error: Missing Intent Outcome.</span>');
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
            'c_time_estimate' => ( $_POST['next_level']>=3 ? '0.05' : '0' ), //3 min default task
	    ));
	    
	    //Log Engagement for New Intent:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => 'Intent ['.$new_intent['c_objective'].'] created',
	        'e_json' => array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $new_intent,
	        ),
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
                'cr_status >=' => 1,
                'c_status >=' => 1,
	            'cr_inbound_id' => intval($_POST['pid']),
	        )),
	    ));
	    
	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => 'Linked intent ['.$new_intent['c_objective'].'] as outbound of intent ['.$inbound_intents[0]['c_objective'].']',
	        'e_json' => array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $relation,
	        ),
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
        echo_json(array(
            'status' => 1,
            'c_id' => $new_intent['c_id'],
            'html' => echo_cr($_POST['b_id'],$relations[0],'outbound',$_POST['next_level'],$bootcamps[0]['b_sprint_unit'],intval($_POST['pid'])),
        ));
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
	            'cr_status >=' => 1,
                'c_status >=' => 1,
	            'cr_inbound_id' => intval($_POST['pid']),
	        )),
	    ));
	    
	    
	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'],
	        'e_message' => 'Linked intent ['.$outbound_intents[0]['c_objective'].'] as outbound of intent ['.$inbound_intents[0]['c_objective'].']',
	        'e_json' => array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $relation,
	        ),
	        'e_type_id' => 23, //New Intent Link
	        'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
	        'e_cr_id' => $relation['cr_id'],
	    ));
	    
	    
	    //Fetch full OUTBOUND link package:
	    $relations = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_id' => $relation['cr_id'],
	    ));
	    
	    
	    //Return result:
	    echo echo_cr($_POST['b_id'],$relations[0],'outbound',$_POST['next_level'],$bootcamps[0]['b_sprint_unit'],intval($_POST['pid']));
	}

	function migrate_task(){

        //Auth user and Load object:
        $udata = auth(2);
        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
        } elseif(!isset($_POST['cr_id']) || intval($_POST['cr_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid cr_id',
            ));
        } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid c_id',
            ));
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid b_id',
            ));
        } elseif(!isset($_POST['from_c_id']) || intval($_POST['from_c_id'])<=0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing from_c_id',
            ));
        } elseif(!isset($_POST['to_c_id']) || intval($_POST['to_c_id'])<=0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing to_c_id',
            ));
        } else {

            //Fetch all three intents to ensure they are all valid and use them for engagement logging:
            $subject = $this->Db_model->c_fetch(array(
                'c.c_id' => intval($_POST['c_id']),
            ));
            $from = $this->Db_model->c_fetch(array(
                'c.c_id' => intval($_POST['from_c_id']),
            ));
            $to = $this->Db_model->c_fetch(array(
                'c.c_id' => intval($_POST['to_c_id']),
            ));

            if(!isset($subject[0]) || !isset($from[0]) || !isset($to[0])){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid intent IDs',
                ));
            } else {
                //Make the move:
                $this->Db_model->cr_update( intval($_POST['cr_id']) , array(
                    'cr_creator_id' => $udata['u_id'],
                    'cr_timestamp' => date("Y-m-d H:i:s"),
                    'cr_inbound_id' => intval($_POST['to_c_id']),
                    //No need to update sorting here as a separate JS function would call that within half a second after the move...
                ));

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $udata['u_id'],
                    'e_json' => array(
                        'post' => $_POST,
                    ),
                    'e_message' => '['.$subject[0]['c_objective'].'] was migrated from ['.$from[0]['c_objective'].'] to ['.$to[0]['c_objective'].']', //Message migrated
                    'e_type_id' => 50, //Message migrated
                    'e_c_id' => intval($_POST['c_id']),
                    'e_cr_id' => intval($_POST['cr_id']),
                    'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
                ));

                //Return success
                echo_json(array(
                    'status' => 1,
                    'message' => 'Move completed',
                ));
            }
        }
    }

	function intents_sort(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid PID',
            ));
	    } elseif(!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
        } else {

            //Validate Parent intent:
            $inbound_intents = $this->Db_model->c_fetch(array(
                'c.c_id' => intval($_POST['pid']),
            ));
            if(count($inbound_intents)<=0){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid PID',
                ));
            } else {

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
                        'cr_outbound_rank' => intval($rank), //Might have decimal for DRAFTING milestones/tasks
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
                    'e_json' => array(
                        'input' => $_POST,
                        'before' => $outbounds_before,
                        'after' => $outbounds_after,
                    ),
                    'e_type_id' => 22, //Links Sorted
                    'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
                    'e_c_id' => intval($_POST['pid']),
                ));

                //Display message:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fa fa-check" aria-hidden="true"></i> Sorted',
                ));
            }
        }
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
	    }
	}

    function dispatch_message(){

        //Auth user and check required variables:
        $udata = auth(2);

        if(!$udata){

            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
            return false;

        } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0) {

            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
            return false;

        } elseif(!isset($_POST['depth']) || intval($_POST['depth'])<0) {

            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Depth',
            ));
            return false;

        } elseif(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0) {

            echo_json(array(
                'status' => 0,
                'message' => 'Missing User ID',
            ));
            return false;

        } else {

            //All seems good, attempt dispatch:
            echo_json(tree_message(intval($_POST['pid']), intval($_POST['depth']), '381488558920384', intval($_POST['u_id']), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 0, 0));

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
	        $new_file_url = trim(save_file( $temp_local , $_FILES[$_POST['upload_type']] , true ));
	        
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
	            $message = '/attach '.$i_media_type.':'.$new_file_url;

                if(in_array($i_media_type,array('image','audio','video','file'))){
                    //Save file via Facebook Attachments:
                    $fb_save = $this->Facebook_model->save_attachment('381488558920384',$i_media_type,$new_file_url);
                }
	            
	            //Create message:
	            $i = $this->Db_model->i_create(array(
	                'i_creator_id' => $udata['u_id'],
	                'i_c_id' => intval($_POST['pid']),
	                'i_media_type' => $i_media_type,
	                'i_message' => $message,
	                'i_url' => $new_file_url,
                    'i_status' => $_POST['i_status'],
                    'i_fb_att_id' => ( isset($fb_save['attachment_id']) ? $fb_save['attachment_id'] : 0 ),
	                'i_rank' => 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
	                    'i_status' => $_POST['i_status'],
	                    'i_c_id' => $_POST['pid'],
	                )),
	            ));
	            
	            //Fetch full message:
	            $new_messages = $this->Db_model->i_fetch(array(
	                'i_id' => $i['i_id'],
	            ));
	            
	            //Log engagement:
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => $udata['u_id'],
	                'e_json' => array(
	                    'post' => $_POST,
	                    'file' => $_FILES,
	                    'after' => $new_messages[0],
	                ),
	                'e_type_id' => 34, //Message added
	                'e_i_id' => intval($new_messages[0]['i_id']),
	                'e_c_id' => intval($new_messages[0]['i_c_id']),
	                'e_b_id' => intval($_POST['b_id']),
	            ));
	            
	            //Echo message:
	            echo_json(array(
	                'status' => 1,
	                'message' => echo_message(array_merge($new_messages[0],array(
	                    'e_b_id'=>intval($_POST['b_id']),
                        'e_recipient_u_id'=>$udata['u_id'],
                    )),$_POST['level']),
	            ));
	        }
	    }
	}

	function message_create(){

	    $udata = auth(2);
	    if(!$udata){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Session Expired. Login and Try again.',
	        ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Task',
	        ));
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Bootcamp',
	        ));
	    } else {

	        //Make sure message is all good:
            $validation = message_validation($_POST['i_status'],$_POST['i_message']);

            if(!$validation['status']){

                //There was some sort of an error:
                echo_json($validation);

            } else {

                //Detect file type:
                if(count($validation['urls'])==1 && trim($validation['urls'][0])==trim($_POST['i_message'])){

                    //This message is a URL only, perform raw URL to file conversion
                    //This feature only available for newly created message, NOT in editing mode!
                    $mime = remote_mime($validation['urls'][0]);
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
                    'i_media_type' => $i_media_type,
                    'i_message' => trim($_POST['i_message']),
                    'i_url' => ( count($validation['urls'])==1 ? $validation['urls'][0] : null ),
                    'i_status' => $_POST['i_status'],
                    'i_rank' => 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
                        'i_status' => $_POST['i_status'],
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
                    'e_json' => array(
                        'input' => $_POST,
                        'after' => $new_messages[0],
                    ),
                    'e_type_id' => 34, //Message added
                    'e_i_id' => intval($new_messages[0]['i_id']),
                    'e_c_id' => intval($_POST['pid']),
                    'e_b_id' => intval($_POST['b_id']), //Share with bootcamp team
                ));

                //Print the challenge:
                echo_json(array(
                    'status' => 1,
                    'message' => echo_message(array_merge($new_messages[0],array(
                        'e_b_id'=>intval($_POST['b_id']),
                        'e_recipient_u_id'=>$udata['u_id'],
                    )),$_POST['level']),
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
	            'message' => 'Invalid Session. Refresh.',
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
	    } else {

            //Fetch Message:
            $messages = $this->Db_model->i_fetch(array(
                'i_id' => intval($_POST['i_id']),
                'i_status >=' => 0,
            ));

            //Make sure message is all good:
            $validation = message_validation($_POST['i_status'],( isset($_POST['i_message']) ? $_POST['i_message'] : null ),$_POST['i_media_type']);

            if(!isset($messages[0])){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Message Not Found',
                ));
            } elseif(!$validation['status']){

                //There was some sort of an error:
                echo_json($validation);

            } else {

                //All good, lets move on:
                //Define what needs to be updated:
                $to_update = array(
                    'i_creator_id' => $udata['u_id'],
                    'i_timestamp' => date("Y-m-d H:i:s"),
                );

                //Is this a text message?
                if($_POST['i_media_type']=='text'){
                    $to_update['i_message'] = trim($_POST['i_message']);
                    $to_update['i_url'] = ( isset($validation['urls'][0]) ? $validation['urls'][0] : null );
                }

                if(!($_POST['initial_i_status']==$_POST['i_status'])){
                    //Change the status:
                    $to_update['i_status'] = $_POST['i_status'];
                    //Put it at the end of the new list:
                    $to_update['i_rank'] = 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
                        'i_status' => $_POST['i_status'],
                        'i_c_id' => intval($_POST['pid']),
                    ));
                }

                //Now update the DB:
                $this->Db_model->i_update( intval($_POST['i_id']) , $to_update );

                //Re-fetch the message for display purposes:
                $new_messages = $this->Db_model->i_fetch(array(
                    'i_id' => intval($_POST['i_id']),
                ));

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $udata['u_id'],
                    'e_json' => array(
                        'input' => $_POST,
                        'before' => $messages[0],
                        'after' => $new_messages[0],
                    ),
                    'e_type_id' => 36, //Message edited
                    'e_i_id' => $messages[0]['i_id'],
                    'e_c_id' => intval($_POST['pid']),
                ));

                //Print the challenge:
                echo_json(array(
                    'status' => 1,
                    'message' => echo_i(array_merge($new_messages[0],array('e_recipient_u_id'=>$udata['u_id'])),$udata['u_fname']),
                    'new_status' => status_bible('i',$new_messages[0]['i_status'],1,'right'),
                    'success_icon' => '<span><i class="fa fa-check" aria-hidden="true"></i> Saved</span>',
                    'new_uploader' => echo_uploader($new_messages[0]), //If there is a person change...
                ));
            }
	    }
	}
	
	function message_delete(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    
	    if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
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
	    } else {

            //Fetch Message:
            $messages = $this->Db_model->i_fetch(array(
                'i_id' => intval($_POST['i_id']),
                'i_status >=' => 0, //Not deleted
            ));
            if(!isset($messages[0])){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Message Not Found',
                ));
            } else {
                //Now update the DB:
                $this->Db_model->i_update( intval($_POST['i_id']) , array(
                    'i_creator_id' => $udata['u_id'],
                    'i_timestamp' => date("Y-m-d H:i:s"),
                    'i_status' => -1, //Deleted by instructor
                ));

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $udata['u_id'],
                    'e_json' => array(
                        'input' => $_POST,
                        'before' => $messages[0],
                    ),
                    'e_type_id' => 35, //Message deleted
                    'e_i_id' => intval($messages[0]['i_id']),
                    'e_c_id' => intval($_POST['pid']),
                ));

                echo_json(array(
                    'status' => 1,
                    'message' => '<span style="color:#222;"><i class="fa fa-trash" aria-hidden="true"></i> Deleted</span>',
                ));
            }
        }
	}

	function messages_sort(){
	    //Auth user and Load object:
	    $udata = auth(2);
	    if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));
	    } elseif(!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort'])<=0){
            echo_json(array(
                'status' => 1, //Do not treat this as error as it could happen in moving Messages between types
                'message' => 'There was nothing to sort',
            ));
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
	    } else {

            //Update them all:
            $sort_count = 0;
            foreach($_POST['new_sort'] as $i_rank=>$i_id){
                if(intval($i_id)>0){
                    $sort_count++;
                    $this->Db_model->i_update( $i_id , array(
                        'i_rank' => intval($i_rank),
                    ));
                }
            }

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $udata['u_id'],
                'e_json' => $_POST,
                'e_type_id' => 39, //Messages sorted
                'e_c_id' => intval($_POST['pid']),
                'e_b_id' => intval($_POST['b_id']),
            ));

            echo_json(array(
                'status' => 1,
                'message' => $sort_count.' Sorted', //Does not matter as its currently not displayed in UI
            ));
        }
	}
	
}