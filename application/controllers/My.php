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




    /* ******************************
     * Messenger Persistent Menu
     ****************************** */

    function actionplan($w_id=0, $c_id=0){
        $this->load->view('front/shared/p_header' , array(
            'title' => '🚩 Action Plan',
        ));
        $this->load->view('front/student/actionplan_frame' , array(
            'c_id' => $c_id,
            'w_id' => $w_id,
        ));
        $this->load->view('front/shared/p_footer');
    }

    function display_actionplan($ru_fp_psid, $w_id=0, $c_id=0){

        //Get session data in case user is doing a browser login:
        $udata = $this->session->userdata('user');

        //Fetch Bootcamps for this user:
        if(!$ru_fp_psid && count($udata['u__ws'])<1){
            //There is an issue here!
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif(count($udata['u__ws'])<1 && !is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])){
            die('<div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
        }

        //Set subscription filters:
        $w_filter = array();

        //Do we have a use session?
        if($w_id>0){
            //Yes! It seems to be a desktop login:
            $w_filter['w_id'] = $w_id;
        } elseif(count($udata['u__ws'])>0){
            //Yes! It seems to be a desktop login:
            $w_filter['w_outbound_u_id'] = $udata['u__ws'][0]['w_outbound_u_id'];
            $w_filter['w_status >='] = 0;
        }

        if($ru_fp_psid>0){
            //No, we should have a Facebook PSID to try to find them:
            $w_filter['u_cache__fp_psid'] = $ru_fp_psid;
            $w_filter['w_status >='] = 0;
        }

        //Try finding them:
        $subscriptions = $this->Db_model->w_fetch($w_filter, array('c','u'));

        if(count($subscriptions)==0){

            //No subscriptions found:
            die('<div class="alert alert-danger" role="alert">You have no active subscriptions yet. '.$this->lang->line('bot_lets_intro').'</div>');

        } elseif(count($subscriptions)>1){

            //Let them choose between subscriptions:
            echo '<div class="list-group" style="margin-top: 10px;">';
            foreach($subscriptions as $w){
                echo echo_w($w);
            }
            echo '</div>';

        } elseif(count($subscriptions)==1) {

            //We found a single subscription, load this by default:
            if(!$w_id || !$c_id){
                //User with a single subscription
                $w_id = $subscriptions[0]['w_id'];
                $c_id = $subscriptions[0]['c_id'];
            }

            //We have a single item to load:
            //Now we need to load the action plan:
            $k_ins = $this->Db_model->k_fetch(array(
                'w_id' => $w_id,
                'cr_status >=' => 1,
                'c_status >=' => 1,
                'cr_outbound_c_id' => $c_id,
            ), array('w','cr','cr_c_in'));

            $k_outs = $this->Db_model->k_fetch(array(
                'w_id' => $w_id,
                'cr_status >=' => 1,
                'c_status >=' => 1,
                'cr_inbound_c_id' => $c_id,
            ), array('w','cr','cr_c_out'));

            $cs = $this->Db_model->c_fetch(array(
                'c_status >=' => 1,
                'c_id' => $c_id,
            ));

            if(count($cs)<1 || (count($k_ins)<1 && count($k_outs)<1)){

                //Ooops, we had issues finding th is intent! Should not happen, report:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $subscriptions[0]['u_id'],
                    'e_json' => $subscriptions,
                    'e_text_value' => 'Unable to load a specific intent for the student Action Plan! Should not happen...',
                    'e_inbound_c_id' => 8,
                    'e_outbound_c_id' => $c_id,
                ));

                die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
            }

            //All good, Load UI:
            $this->load->view('front/student/actionplan_ui.php' , array(
                'subscriptions' => $subscriptions,
                'c' => $cs[0],
                'k_ins' => $k_ins,
                'k_outs' => $k_outs,
            ));

        }
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
        $matching_subscriptions = $this->Db_model->w_fetch(array(


            'ru_outbound_u_id' => intval($_POST['u_id']),
            'ru_r_id' => intval($_POST['r_id']),
            'ru_status >=' => 4, //Only Active students can submit Steps
        ));

        if(!(count($matching_subscriptions)==1)){
            die('<span style="color:#FF0000;">Error: You are no longer an active Student of this Bootcamp</span>');
        }

        //Fetch full Bootcamp/Class/Intent data from Action Plan copy:
        //$bs = fetch_action_plan_copy(intval($_POST['b_id']),intval($_POST['r_id']));
        //$focus_class = $bs[0]['this_class'];
        //$intent_data = extract_level( $bs[0] , intval($_POST['c_id']) );


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
            'e_inbound_u_id' => $matching_subscriptions[0]['u_id'], //by this Student
            'e_status !=' => -3, //Should not be rejected
        ));

        if(count($already_submitted)>0){
            die('<span style="color:#FF0000;">Error: You have already marked this concept as complete, You cannot re-submit it.</span>');
        }


        //See if we need to dispatch any messages:
        $on_complete_text_values = array();
        $drip_messages = array();

        //Dispatch messages for this Step:
        //$step_messages = extract_level($bs[0],$_POST['c_id']);

        foreach($step_messages['intent']['c__messages'] as $i){
            if($i['i_status']==2){
                array_push($drip_messages , $i);
            } elseif($i['i_status']==3){
                array_push($on_complete_text_values, array_merge($i , array(
                    'e_inbound_u_id' => 0,
                    'e_outbound_u_id' => $matching_subscriptions[0]['u_id'],
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
                        'e_outbound_u_id' => $matching_subscriptions[0]['u_id'],
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
                    'e_outbound_u_id' => $matching_subscriptions[0]['u_id'],
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
            'e_inbound_u_id' => $matching_subscriptions[0]['u_id'],
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
        echo_k($us_eng);


        //Take action based on what the next level is...
        if($intent_data['next_level']==1){

            //Last item completed!
            //TODO Update status in subscription

            //Send graduation message:
            $this->Comm_model->foundation_message(array(
                'e_outbound_u_id' => intval($_POST['u_id']),
                'e_outbound_c_id' => 2691, //As soon as Graduated message
                'depth' => 0,
            ));

        } elseif($intent_data['next_level']==2){

            //We have a next Task:
            //TODO Update progress on subscription
            //'ru_cache__completion_rate' => number_format( ( $matching_subscriptions[0]['ru_cache__completion_rate'] + ($intent_data['intent']['c_time_estimate']/$bs[0]['c__estimated_hours']) ),8),
            //'ru_cache__current_task' => $intent_data['next_intent']['cr_outbound_rank'],

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


    function reset_pass(){
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/password_reset');
        $this->load->view('front/shared/p_footer');
    }
	
}
