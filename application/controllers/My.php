<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends CI_Controller {
    
    //This controller is usually accessed via the /my/ URL prefix via the Messenger Bot
    
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}

    function index(){
        //Nothing here:
        header( 'Location: /');
    }



    function applications(){

        //List student applications
        $application_status_salt = $this->config->item('application_status_salt');
        if(!isset($_GET['u_key']) || !isset($_GET['u_id']) || intval($_GET['u_id'])<1 || !(md5($_GET['u_id'].$application_status_salt)==$_GET['u_key'])){
            //Log this error:
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid URL. Choose your Bootcamp and re-apply to receive an email with your application status url.</div>');
        }


        //Search for class using form ID:
        $users = $this->Db_model->u_fetch(array(
            'u_id' => intval($_GET['u_id']),
        ));

        //Fetch all their addmissions:
        $enrollments = $this->Db_model->ru_fetch(array(
            'ru_outbound_u_id'	=> @$users[0]['u_id'],
        ),array(
            'ru.ru_timestamp' => 'DESC',
        ));

        if(count($users)==1 && count($enrollments)>0){
            $udata = $users[0];
        } else {
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid URL. Choose your Bootcamp and re-apply to receive an email with your application status url.</div>');
        }



        //Is this a paypal success?
        if(isset($_GET['status']) && intval($_GET['status'])==1){
            //Give the PayPal webhook enough time to update the DB status:
            sleep(2);

            //Capture Facebook Conversion:
            //TODO This would capture again upon refresh, fix later...
            $purchase_value = doubleval($_GET['purchase_value']);

        } else {
            //Set defaults:
            $purchase_value = 0;
        }

        //Validate Class ID that it's still the latest:
        $data = array(
            'title' => 'My Bootcamps',
            'udata' => $udata,
            'u_id' => $_GET['u_id'],
            'u_key' => $_GET['u_key'],
            'b_enrollment_redirect_url' => null,
            'purchase_value' => $purchase_value, //Capture via Facebook Pixel
            'enrollments' => $enrollments,
            'hm' => ( isset($_GET['status']) && isset($_GET['message']) ? '<div class="alert alert-'.( intval($_GET['status']) ? 'success' : 'danger').'" role="alert">'.( intval($_GET['status']) ? 'Success' : 'Error').': '.$_GET['message'].'</div>' : '' ),
        );

        //Load apply page:
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/my_applications' , $data);
        $this->load->view('front/shared/p_footer');

    }



    /* ******************************
     * Messenger Persistent Menu
     ****************************** */

    function actionplan($b_id=null,$c_id=null){
        //Load apply page:
        $data = array(
            'title' => '🚩 Action Plan',
            'b_id' => $b_id,
            'c_id' => $c_id,
        );
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/actionplan_frame' , $data);
        $this->load->view('front/shared/p_footer');
    }
    function display_actionplan($ru_fp_psid,$b_id=0,$c_id=0){

        $uenrollment = array();
        if(!$ru_fp_psid){
            $uenrollment = $this->session->userdata('uenrollment');
        }

        //Fetch Bootcamps for this user:
        if(!$ru_fp_psid && count($uenrollment)<1){
            //There is an issue here!
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif(count($uenrollment)<1 && !is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])){
            die('<div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
        }

        //Set enrollment filters:
        $ru_filter = array(
            'ru.ru_status >=' => 4, //Enrolled
            'r.r_status >=' => 1, //Open for Subscription or Higher
        );

        //Define user identifier based on origin (Desktop login vs Messenger Webview):
        if(count($uenrollment)>0 && $uenrollment['u_id']>0){
            $ru_filter['u.u_id'] = $uenrollment['u_id'];
        } else {
            $ru_filter['(ru.ru_fp_psid = '.$ru_fp_psid.' OR u.u_cache__fp_psid = '.$ru_fp_psid.')'] = null;
        }

        //Fetch all their enrollments:
        if($b_id>0){
            //Enhance our search and make it specific to this $b_id:
            $ru_filter['ru.ru_b_id'] = $b_id;
        }

        $enrollments = $this->Db_model->remix_enrollments($ru_filter);

        if(count($enrollments)==1){

            //Only have a single option:
            $focus_enrollment = $enrollments[0];

        } elseif(count($enrollments)>1){

            //We'd need to see which enrollment to load now as the Student has not specified:
            $focus_enrollment = detect_active_enrollment($enrollments);

        } else {

            //No enrollments found:
            die('<div class="alert alert-danger" role="alert">You have not joined any Bootcamps yet</div>');

        }


        if(!$b_id || !$c_id){

            //Log Engagement for opening the Action Plan, which happens without $b_id & $c_id
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $focus_enrollment['u_id'],
                'e_json' => $enrollments,
                'e_inbound_c_id' => 32, //actionplan Opened
                'e_outbound_c_id' => $focus_enrollment['c_id'],
            ));

            //Reload with specific directions:
            $this->display_actionplan($ru_fp_psid,$focus_enrollment['b_id'],$focus_enrollment['c_id']);

            //Reload this function, this time with specific instructions on what to load:
            return true;
        }


        //Fetch full Bootcamp/Class data for this:
        $bs = fetch_action_plan_copy($b_id,$focus_enrollment['r_id']);
        $class = $bs[0]['this_class'];


        //Fetch intent relative to the Bootcamp by doing an array search:
        $view_data = extract_level( $bs[0] , $c_id );

        if($view_data){

            //Append more data:
            $view_data['class'] = $class;
            $view_data['enrollments'] = $enrollments;
            $view_data['enrollment'] = $focus_enrollment;
            $view_data['us_data'] = $this->Db_model->e_fetch(array(
                'e_inbound_c_id' => 33, //Completion Report
                'e_inbound_u_id' => $focus_enrollment['u_id'], //by this Student
            ), 1000, array(), 'e_outbound_c_id');

        } else {

            //Reload with specific directions:
            $this->display_actionplan($ru_fp_psid);

            //Reload this function, this time with specific instructions on what to load:
            return true;

            //Ooops, they dont have anything!
            //die('<div class="alert alert-info" role="alert">Click on the Action Plan button on Messenger</div>');

        }

        //All good, Load UI:
        $this->load->view('front/student/actionplan_ui.php' , $view_data);

    }
    function all_enrollments(){

        //Validate their origin:
        $application_status_salt = $this->config->item('application_status_salt');
        if(!isset($_POST['current_r_id']) || !isset($_POST['u_key']) || !isset($_POST['u_id']) || intval($_POST['u_id'])<1 || !(md5($_POST['u_id'].$application_status_salt)==$_POST['u_key'])){
            //Log this error:
            die('<div class="alert alert-danger" role="alert">Invalid ID</div>');
        }

        //Fetch all their enrollments:
        $enrollments = $this->Db_model->remix_enrollments(array(
            'ru.ru_outbound_u_id' => $_POST['u_id'],
            'ru.ru_status >=' => 4, //Enrolled
            'r.r_status >=' => 1, //Open for Subscription or Higher
        ));


        if(count($enrollments)<=1){

            //No other enrollments found:
            die('<div class="alert alert-info" role="alert"><i class="fas fa-exclamation-triangle"></i> Error: You must be part of at-least 2 Bootcamps to be able to switch between them.<div style="margin-top: 15px;"><a href="/">Browse Bootcamps &raquo;</a></div></div>');

        } else {

            //Student is in multiple Bootcamps, give them option to switch:
            echo '<div class="list-group maxout">';

            foreach($enrollments as $other_enrollment){

                $is_current = ($_POST['current_r_id']==$other_enrollment['r_id']);

                if($is_current){

                    //This is the one that is loaded:
                    echo '<li class="list-group-item grey">';
                    //echo '<span class="pull-right"><span class="label label-default grey" style="color:#2f2739;">CURRENTLY VIEWING</span></span>';

                } else {

                    echo '<a href="/my/actionplan/'.$other_enrollment['b_id'].'/'.$other_enrollment['b_outbound_c_id'].'" class="list-group-item">';
                    echo '<span class="pull-right"><span class="badge badge-primary" style="margin-top: -7px;"><i class="fas fa-chevron-right"></i></span></span>';

                }

                echo '<i class="fas fa-cube"></i> <b>'.$other_enrollment['c_outcome'].'</b>';
                echo ' <span style="display:inline-block;"><i class="fas fa-calendar"></i> '.echo_time($other_enrollment['r_start_date'],2).'</span>';

                    echo ' <span class="badge badge-primary grey" style="padding: 2px 9px;">RUNNING?</span>';


                echo ( $is_current ? '</li>' : '</a>' );
            }

            echo '</div>';

        }
    }


    function account(){
        //Load apply page:
        $data = array(
            'title' => '👤 My Account',
        );
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/my_account' , $data);
        $this->load->view('front/shared/p_footer');
    }
    function display_account(){
        //TODO later...
    }


    function us_save(){

        //Validate integrity of request:
        if(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0
            || !isset($_POST['page_load_time']) || !intval($_POST['page_load_time'])
            || !isset($_POST['s_key']) || !($_POST['s_key']==md5($_POST['c_id'].$_POST['page_load_time'].'pag3l0aDSla7'.$_POST['u_id']))
            || !isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
            die('<span style="color:#FF0000;">Error: Missing Core Data</span>');
        }

        //Fetch student name and details:
        $matching_enrollments = $this->Db_model->ru_fetch(array(
            'ru_outbound_u_id' => intval($_POST['u_id']),
            'ru_r_id' => intval($_POST['r_id']),
            'ru_status >=' => 4, //Only Active students can submit Steps
        ));

        if(!(count($matching_enrollments)==1)){
            die('<span style="color:#FF0000;">Error: You are no longer an active Student of this Bootcamp</span>');
        }

        //Fetch full Bootcamp/Class/Intent data from Action Plan copy:
        $bs = fetch_action_plan_copy(intval($_POST['b_id']),intval($_POST['r_id']));
        $focus_class = $bs[0]['this_class'];
        $intent_data = extract_level( $bs[0] , intval($_POST['c_id']) );


        if(!$focus_class){
            die('<span style="color:#FF0000;">Error: Invalid Class ID!</span>');
        } elseif(!isset($intent_data['intent']) || !is_array($intent_data['intent'])){
            die('<span style="color:#FF0000;">Error: Invalid Task ID</span>');
            //Submission settings:
        } elseif($intent_data['intent']['c_require_url_to_complete'] && count(extract_urls($_POST['us_notes']))<1){
            die('<span style="color:#FF0000;">Error: URL Required to mark as complete. <a href=""><b><u>Refresh this page</u></b></a> and try again.</span>');
        } elseif($intent_data['intent']['c_require_notes_to_complete'] && strlen($_POST['us_notes'])<1){
            die('<span style="color:#FF0000;">Error: Notes Required to mark as complete. <a href=""><b><u>Refresh this page</u></b></a> and try again.</span>');
        }


        //Make sure student has not submitted this Intent before:
        $already_submitted = $this->Db_model->e_fetch(array(
            'e_inbound_c_id' => 33, //Completion Report
            'e_outbound_c_id' => intval($_POST['c_id']), //For this item
            'e_inbound_u_id' => $matching_enrollments[0]['u_id'], //by this Student
            'e_status !=' => -3, //Should not be rejected
        ));

        if(count($already_submitted)>0){
            die('<span style="color:#FF0000;">Error: You have already marked this item as complete, You cannot re-submit it.</span>');
        }


        //See if we need to dispatch any messages:
        $on_complete_text_values = array();
        $drip_messages = array();

        //Dispatch messages for this Step:
        $step_messages = extract_level($bs[0],$_POST['c_id']);

        foreach($step_messages['intent']['c__messages'] as $i){
            if($i['i_status']==2){
                array_push($drip_messages , $i);
            } elseif($i['i_status']==3){
                array_push($on_complete_text_values, array_merge($i , array(
                    'e_inbound_u_id' => 0,
                    'e_outbound_u_id' => $matching_enrollments[0]['u_id'],
                    'i_outbound_c_id' => $i['i_outbound_c_id'],
                )));
            }
        }

        //Is the Bootcamp Complete?
        if($intent_data['next_level']==1){
            //Seems so!
            foreach($bs[0]['c__messages'] as $i){
                //Bootcamps only could have ON-COMPLETE messages:
                if($i['i_status']==3){
                    array_push($on_complete_text_values, array_merge($i , array(
                        'e_inbound_u_id' => 0,
                        'e_outbound_u_id' => $matching_enrollments[0]['u_id'],
                        'i_outbound_c_id' => $i['i_outbound_c_id'],
                    )));
                }
            }
        }

        //Anything to be sent instantly?
        if(count($on_complete_text_values)>0){
            //Dispatch all Instant Messages, their engagements have already been logged:
            $this->Comm_model->send_message($on_complete_text_values);
        }

        //Any Drip Messages? Set triggers:
        if(count($drip_messages)>0){

            $start_time = time();
            //TODO Adjust $drip_intervals = (class_ends($bs[0], $focus_class)-$start_time) / (count($drip_messages)+1);
            $drip_time = $start_time;

            foreach($drip_messages as $i){

                $drip_time += $drip_intervals;
                $this->Db_model->e_create(array(

                    'e_inbound_u_id' => 0, //System
                    'e_outbound_u_id' => $matching_enrollments[0]['u_id'],
                    'e_timestamp' => date("Y-m-d H:i:s" , $drip_time ), //Used by Cron Job to fetch this Drip when due
                    'e_json' => array(
                        'created_time' => date("Y-m-d H:i:s" , $start_time ),
                        'drip_time' => date("Y-m-d H:i:s" , $drip_time ),
                        'i_drip_count' => count($drip_messages),
                        'i' => $i, //The actual message that would be sent
                    ),
                    'e_inbound_c_id' => 52, //Pending Drip e_inbound_c_id=52
                    'e_status' => 0, //Pending for the Drip Cron
                    'e_i_id' => $i['i_id'],
                    'e_outbound_c_id' => $i['i_outbound_c_id'],

                ));
            }
        }


        //Save student completion report:
        $us_eng = $this->Db_model->e_create(array(
            'e_inbound_u_id' => $matching_enrollments[0]['u_id'],
            'e_status' => -1, //Auto approved
            'e_text_value' => trim($_POST['us_notes']),
            'e_time_estimate' => $intent_data['intent']['c_time_estimate'], //Estimate time spent on this item
            'e_inbound_c_id' => 33, //Completion Report
            'e_outbound_c_id' => intval($_POST['c_id']),
            'e_json' => array(
                'input' => $_POST,
                'scheduled_drip' => count($drip_messages),
                'sent_oncomplete' => count($on_complete_text_values),
                'next_level' => $intent_data['next_level'],
                'next_c' => ( isset($intent_data['next_intent']) ? $intent_data['next_intent'] : array() ),
            ),
        ));


        //Show result to student:
        echo_completion_report($us_eng);


        //Take action based on what the next level is...
        if($intent_data['next_level']==1){

            //The next level is the Bootcamp, which means this was the last Step:
            $this->Db_model->ru_update( $matching_enrollments[0]['ru_id'] , array(
                'ru_cache__completion_rate' => 1, //Student is 100% complete
                'ru_cache__current_task' => ($focus_class['r__total_tasks']+1), //Go 1 Task after the total Tasks to indicate completion
            ));

            //Send graduation message:
            $this->Comm_model->foundation_message(array(
                'e_outbound_u_id' => intval($_POST['u_id']),
                'e_outbound_c_id' => 2691, //As soon as Graduated message
                'depth' => 0,
            ));

        } elseif($intent_data['next_level']==2){

            //We have a next Task:
            //We also need to change ru_cache__current_task to reflect this advancement:
            $this->Db_model->ru_update( $matching_enrollments[0]['ru_id'] , array(
                'ru_cache__completion_rate' => number_format( ( $matching_enrollments[0]['ru_cache__completion_rate'] + ($intent_data['intent']['c_time_estimate']/$bs[0]['c__estimated_hours']) ),8),
                'ru_cache__current_task' => $intent_data['next_intent']['cr_outbound_rank'],
            ));

            //Show button for next Task:
            echo '<div style="font-size:1.2em;"><a href="/my/actionplan/'.$_POST['b_id'].'/'.$intent_data['next_intent']['c_id'].'" class="btn btn-black">Next <i class="fas fa-arrow-right"></i></a></div>';


        } else {

            //This should not happen!
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $_POST['u_id'],
                'e_text_value' => 'us_save() experienced fatal error where $intent_data/next_level was not 1,2 or 3',
                'e_json' => array(
                    'POST' => $_POST,
                    'intent_data' => $intent_data,
                ),
                'e_inbound_c_id' => 8, //Platform Error
                'e_outbound_c_id' => $_POST['c_id'],
            ));

        }
    }



    function withdraw_enrollment(){
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
            $enrollments = $this->Db_model->ru_fetch(array(
                'ru.ru_status <='  => 4, //Initiated or higher as long as Bootcamp is running!
                'ru.ru_outbound_u_id'	=> $_POST['u_id'],
                'ru.ru_id'	    => $_POST['ru_id'],
            ));

            if(count($enrollments)==1){

                //All good, withdraw:
                $this->Db_model->ru_update( $_POST['ru_id'] , array(
                    'ru_status' => -2,
                ));

                //Log Engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $enrollments[0]['u_id'], //System
                    'e_inbound_c_id' => 66, //Application Withdraw
                ));

                //Inform User:
                echo_json(array(
                    'status' => 1,
                    'message' => echo_status('ru',-2,0,'top'),
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


    function reset_pass(){
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/password_reset');
        $this->load->view('front/shared/p_footer');
    }
	
}
