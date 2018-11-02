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

    function display_actionplan($u_fb_psid, $w_id=0, $c_id=0){

        //Get session data in case user is doing a browser login:
        $udata = $this->session->userdata('user');

        //Fetch Bootcamps for this user:
        if(!$u_fb_psid && count($udata['u__ws'])<1){
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

        if($u_fb_psid>0){
            //No, we should have a Facebook PSID to try to find them:
            $w_filter['u_fb_psid'] = $u_fb_psid;
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
                $c_id = $subscriptions[0]['c_id']; //TODO set to current/focused intent
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
                'w' => $subscriptions[0], //We must have 1 by now!
                'c' => $cs[0],
                'k_ins' => $k_ins,
                'k_outs' => $k_outs,
            ));

        }
    }

    function choose_any_path($w_id, $c_id, $cr_inbound_c_id, $w_key){
        if(md5($w_id.'kjaghksjha*(^'.$c_id.$cr_inbound_c_id)==$w_key){
            //Validated! Move on:
            if($this->Db_model->k_choose_any_path($w_id, $c_id, $cr_inbound_c_id)){
                //Successful, redirect and show message:
                redirect_message('/my/actionplan/'.$w_id.'/'.$c_id,'<div class="alert alert-success" role="alert" title="'.$siblings_updated.' Siblings updated">Your answer was saved.</div>');
            } else {
                redirect_message('/my/actionplan/'.$w_id.'/'.$cr_inbound_c_id,'<div class="alert alert-danger" role="alert">There was an error saving your answer.</div>');
            }
        }
    }

    function update_k_save(){

        //Validate integrity of request:
        if(!isset($_POST['k_id']) || intval($_POST['k_id'])<=0 || !isset($_POST['k_notes'])){
            return redirect_message('/my/actionplan','<div class="alert alert-danger" role="alert">Error: Missing Core Data.</div>');
        }

        //Fetch student name and details:
        $udata = $this->session->userdata('user');
        $ks = $this->Db_model->k_fetch(array(
            'k_id' => $_POST['k_id'],
        ), array('w','cr','cr_c_out'));

        if(!(count($ks)==1)){
            return redirect_message('/my/actionplan','<div class="alert alert-danger" role="alert">Error: Invalid submission ID.</div>');
        }
        $k_url = '/my/actionplan/'.$ks[0]['k_w_id'].'/'.$ks[0]['c_id'];


        //Do we have what it takes to mark as complete?
        if($ks[0]['c_require_url_to_complete'] && count(extract_urls($_POST['k_notes']))<1){
            return redirect_message($k_url,'<div class="alert alert-danger" role="alert">Error: URL Required to mark ['.$ks[0]['c_outcome'].'] as complete.</div>');
        } elseif($ks[0]['c_require_notes_to_complete'] && strlen($_POST['k_notes'])<1){
            return redirect_message($k_url,'<div class="alert alert-danger" role="alert">Error: Notes Required to mark ['.$ks[0]['c_outcome'].'] as complete.</div>');
        }


        //Did anything change?
        $status_changed = ( $ks[0]['k_status']<=1 );
        $notes_changed = !($ks[0]['k_notes']==trim($_POST['k_notes']));
        if(!$notes_changed && !$status_changed){
            //Nothing seemed to change! Let them know:
            return redirect_message($k_url,'<div class="alert alert-info" role="alert">Note: Nothing saved because nothing was changed.</div>');
        }

        //All good, move forward with the update:
        //Save a copy of the student completion report:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => ( isset($udata['u_id']) ? $udata['u_id'] : $ks[0]['k_outbound_u_id'] ),
            'e_text_value' => ( $notes_changed ? trim($_POST['k_notes']) : '' ),
            'e_inbound_c_id' => 33, //Completion Report
            'e_outbound_c_id' => $ks[0]['c_id'],
            'e_json' => array(
                'input' => $_POST,
                'k' => $ks[0],
            ),
        ));

        if($notes_changed){
            //Updates k notes:
            $this->Db_model->k_update($ks[0]['k_id'], array(
                'k_last_updated' => date("Y-m-d H:i:s"),
                'k_notes' => trim($_POST['k_notes']),
            ));
        }

        if($status_changed){
            //Also update k_status, determine what it should be:
            $this->Db_model->k_complete_recursive_up($ks[0], $ks[0]);
        }

        //Redirect back to page with success message:
        return redirect_message($k_url,'<div class="alert alert-success" role="alert"><i class="fal fa-check-circle"></i> Saved</div>');

        //TODO Update w__progress at this point based on intent data
        //TODO Update tree upwards and dispatch drip/instant message logic as needed!
        /*
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
                    'e_outbound_u_id' => $ks[0]['u_id'],
                    'i_outbound_c_id' => $i['i_outbound_c_id'],
                )));
            }
        }

        //Anything to be sent instantly?
        if(count($on_complete_text_values)>0){
            //Dispatch all Instant Messages, their engagements have already been logged:
            $this->Comm_model->send_message($on_complete_text_values);
        }

        //TODO Wire in drip messages
        if(0 && count($drip_messages)>0){

            $start_time = time();
            //TODO Adjust $drip_intervals = (class_ends($bs[0], $focus_class)-$start_time) / (count($drip_messages)+1);
            $drip_time = $start_time;

            foreach($drip_messages as $i){

                $drip_time += $drip_intervals;
                $this->Db_model->e_create(array(

                    'e_inbound_u_id' => 0, //System
                    'e_outbound_u_id' => $ks[0]['u_id'],
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
        */
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
