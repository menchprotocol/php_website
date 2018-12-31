<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends CI_Controller
{

    //This controller is usually accessed via the /my/ URL prefix via the Messenger Bot

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }

    function index()
    {
        //Nothing here:
        header('Location: /');
    }

    function fb_profile($en_id)
    {

        $udata = fn___en_auth(array(1308));
        $current_us = $this->Database_model->fn___en_fetch(array(
            'en_id' => $en_id,
        ));

        if (!$udata) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and Try again.',
            ));
        } elseif (count($current_us) == 0) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'User not found!',
            ));
        } elseif (strlen($current_us[0]['en_psid']) < 10) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'User does not seem to be connected to Mench, so profile data cannot be fetched',
            ));
        } else {

            //Fetch results and show:
            return fn___echo_json(array(
                'fb_profile' => $this->Chat_model->fn___facebook_graph('GET', '/'.$current_us[0]['en_psid'], array()),
                'en' => $current_us[0],
            ));

        }

    }


    function actionplan($actionplan_tr_id = 0, $in_id = 0)
    {

        $this->load->view('view_shared/messenger_header', array(
            'title' => '🚩 Action Plan',
        ));
        //include main body:
        $this->load->view('view_ledger/tr_actionplan_messenger_frame', array(
            'in_id' => $in_id,
            'actionplan_tr_id' => $actionplan_tr_id,
        ));
        $this->load->view('view_shared/messenger_footer');
    }

    function fn___display_actionplan($psid, $actionplan_tr_id = 0, $in_id = 0)
    {

        //Get session data in case user is doing a browser login:
        $udata = $this->session->userdata('user');
        $empty_session = (!isset($udata['en__actionplans']) || count($udata['en__actionplans']) < 1);
        $is_miner = fn___filter_array($udata['en__parents'], 'en_id', 1308);

        //Authenticate user:
        if (!$psid && $empty_session && !$is_miner) {
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif ($empty_session && !fn___is_dev() && isset($_GET['sr']) && !fn___parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
        }

        if($empty_session && $psid > 0){
            //Authenticate this user:
            $udata = $this->Matrix_model->fn___en_messenger_authenticate($psid);
        }

        //Set Action Plan filters:
        $filters = array();

        //Do we have a use session?
        if ($actionplan_tr_id > 0 && $in_id > 0) {
            //Yes! It seems to be a desktop login:
            $filters['tr_en_type_id'] = 4559; //Action Plan Intent
            $filters['tr_tr_parent_id'] = $actionplan_tr_id;
            $filters['tr_in_child_id'] = $in_id;
        } elseif (!$empty_session) {
            //Yes! It seems to be a desktop login (versus Facebook Messenger)
            $filters['tr_en_type_id'] = 4235; //Action Plan
            $filters['tr_en_parent_id'] = $udata['en_id'];
            $filters['tr_status >='] = 0;
        }

        //Try finding them:
        $trs = $this->Database_model->fn___tr_fetch($filters, array('in_child'));

        if (count($trs) < 1) {

            //No Action Plans found:
            die('<div class="alert alert-danger" role="alert">You have no active Action Plans yet.</div>');

        } elseif (count($trs) > 1) {

            //Determine Action Plan IDs if not provided:
            if(!$actionplan_tr_id || !$in_id){
                $actionplan_tr_id = ( $trs[0]['tr_tr_parent_id'] == 0 ? $trs[0]['tr_id'] : $trs[0]['tr_tr_parent_id'] );
                $in_id = $trs[0]['tr_in_child_id'];
            }

            //Log action plan view transaction:
            $this->Database_model->fn___tr_create(array(
                'tr_en_type_id' => 4283,
                'tr_en_credit_id' => $trs[0]['tr_en_parent_id'],
                'tr_en_parent_id' => $trs[0]['tr_en_parent_id'],
                'tr_tr_parent_id' => $actionplan_tr_id,
                'tr_in_child_id' => $in_id,
            ));

            if(count($trs) > 1) {

                //Master has multiple Action Plans, so list all Action Plans to enable Master to choose:
                echo '<h3 class="master-h3 primary-title">My Action Plan</h3>';
                echo '<div class="list-group" style="margin-top: 10px;">';
                foreach ($trs as $tr) {
                    //Prepare metadata:
                    $metadata = unserialize($tr['in_metadata']);
                    //Display row:
                    echo '<a href="/my/actionplan/' . $tr['tr_id'] . '/' . $tr['tr_in_child_id'] . '" class="list-group-item">';
                    echo '<span class="pull-right">';
                    echo '<span class="badge badge-primary"><i class="fas fa-angle-right"></i></span>';
                    echo '</span>';
                    echo fn___echo_status('tr_status', $tr['tr_status'], 1, 'right');
                    echo ' ' . $tr['in_outcome'];
                    echo ' ' . $metadata['in__tree_in_active_count'];
                    echo ' &nbsp;<i class="fas fa-clock"></i> ' . fn___echo_time_range($tr, true);
                    echo '</a>';
                }
                echo '</div>';

            } elseif(count($trs)==1){

                //We have a single Action Plan Intent to load:
                //Now we need to load the action plan:
                $actionplan_parents = $this->Database_model->fn___tr_fetch(array(
                    'tr_en_type_id' => 4559, //Action Plan Intents
                    'tr_tr_parent_id' => $actionplan_tr_id,
                    'in_status >=' => 2, //Published+ Intents
                    'tr_in_child_id' => $in_id,
                ), array('in_parent'));

                $actionplan_children = $this->Database_model->fn___tr_fetch(array(
                    'tr_en_type_id' => 4559, //Action Plan Intents
                    'tr_tr_parent_id' => $actionplan_tr_id,
                    'in_status >=' => 2, //Published+ Intents
                    'tr_in_parent_id' => $in_id,
                ), array('in_child'));


                $ins = $this->Database_model->fn___in_fetch(array(
                    'in_status >=' => 2,
                    'in_id' => $in_id,
                ));

                if (count($ins) < 1 || (!count($actionplan_parents) && !count($actionplan_children))) {

                    //Ooops, we had issues finding th is intent! Should not happen, report:
                    $this->Database_model->fn___tr_create(array(
                        'tr_en_credit_id' => $trs[0]['en_id'],
                        'tr_metadata' => $trs,
                        'tr_content' => 'Unable to load a specific intent for the master Action Plan! Should not happen...',
                        'tr_en_type_id' => 4246, //Platform Error
                        'tr_tr_parent_id' => $actionplan_tr_id,
                        'tr_in_child_id' => $in_id,
                    ));

                    die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
                }

                //All good, Load UI:
                $this->load->view('view_ledger/tr_actionplan_messenger_ui.php', array(
                    'actionplan' => $trs[0], //We must have 1 by now!
                    'in' => $ins[0],
                    'actionplan_parents' => $actionplan_parents,
                    'actionplan_children' => $actionplan_children,
                ));

            }
        }
    }


    function load_w_actionplan()
    {

        //Auth user and check required variables:
        $udata = fn___en_auth(array(1308)); //miners

        if (!$udata) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Action Plan ID',
            ));
        }

        //Fetch Action Plan
        $actionplans = $this->Database_model->w_fetch(array(
            'tr_id' => $_POST['tr_id'], //Other than this one...
        ));
        if (!(count($actionplans) == 1)) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Action Plan ID',
            ));
        }
        $w = $actionplans[0];

        //Load Action Plan iFrame:
        return fn___echo_json(array(
            'status' => 1,
            'url' => '/my/actionplan/' . $w['tr_id'] . '/' . $w['tr_in_child_id'],
        ));

    }


    function load_u_trs($en_id)
    {

        //Auth user and check required variables:
        $udata = fn___en_auth(array(1308)); //miners

        if (!$udata) {
            die('<div class="alert alert-danger" role="alert">Session Expired</div>');
        } elseif (intval($en_id) < 1) {
            die('<div class="alert alert-danger" role="alert">Missing User ID</div>');
        }

        //Load view for this iFrame:
        $this->load->view('view_shared/messenger_header', array(
            'title' => 'User Transactions',
        ));
        $this->load->view('view_ledger/tr_entity_history', array(
            'en_id' => $en_id,
        ));
        $this->load->view('view_shared/messenger_footer');
    }

    function skip_tree($tr_id, $in_id, $tr_id2)
    {
        //Start skipping:
        $total_skipped = count($this->Matrix_model->k_skip_recursive_down($tr_id));

        //Draft message:
        $message = '<div class="alert alert-success" role="alert">' . $total_skipped . ' key idea' . fn___echo__s($total_skipped) . ' successfully skipped.</div>';

        //Find the next item to navigate them to:
        $next_ins = $this->Matrix_model->fn___actionplan_next_in($tr_id);
        if ($next_ins) {
            return fn___redirect_message('/my/actionplan/' . $next_ins[0]['tr_tr_parent_id'] . '/' . $next_ins[0]['in_id'], $message);
        } else {
            return fn___redirect_message('/my/actionplan', $message);
        }
    }

    function choose_any_path($actionplan_tr_id, $tr_in_parent_id, $in_id, $w_key)
    {
        if (md5($actionplan_tr_id . 'kjaghksjha*(^' . $in_id . $tr_in_parent_id) == $w_key) {
            if ($this->Matrix_model->fn___actionplan_choose_or($actionplan_tr_id, $tr_in_parent_id, $in_id)) {
                return fn___redirect_message('/my/actionplan/' . $actionplan_tr_id . '/' . $in_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');
            } else {
                //We had some sort of an error:
                return fn___redirect_message('/my/actionplan/' . $actionplan_tr_id . '/' . $tr_in_parent_id, '<div class="alert alert-danger" role="alert">There was an error saving your answer.</div>');
            }
        }
    }

    function update_k_save()
    {

        //Validate integrity of request:
        if (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1 || !isset($_POST['tr_content'])) {
            return fn___redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: Missing Core Data.</div>');
        }

        //Fetch master name and details:
        $udata = $this->session->userdata('user');
        $trs = $this->Database_model->fn___tr_fetch(array(
            'tr_id' => $_POST['tr_id'],
        ), array('w', 'cr', 'cr_c_child'));

        if (!(count($trs) == 1)) {
            return fn___redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: Invalid submission ID.</div>');
        }
        $k_url = '/my/actionplan/' . $trs[0]['tr_tr_parent_id'] . '/' . $trs[0]['in_id'];


        //Fetch completion requirements:
        $completion_requirements = $this->Database_model->fn___tr_fetch(array(
            'tr_en_type_id' => 4331, //Intent Response Limiters
            'tr_in_child_id' => $trs[0]['tr_in_child_id'], //For this intent
            'tr_status >=' => 2, //Published+
            'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4331')) . ')' => null, //The Requirement
        ));

        if(count($completion_requirements)>0){

            //Yes, it does have requirements! let's check them one by one:

            //Intent Response Limiters:
            $en_all_4331 = $this->config->item('en_all_4331');

            //Extract references to see what we have:
            $msg_references = fn___extract_message_references($_POST['tr_content']);

            //TODO we later need to check URL type to enable other requirements live video/audio URLs if they are to be added as an option.

            $requirement_notes = array();
            $did_meet_requirements = false; //Assume false unless proven otherwise

            //Check to see if Master meets ANY of the requirements:
            foreach($completion_requirements as $tr){

                //Check requirements:
                if($tr['tr_en_parent_id']==4255 && strlen($_POST['tr_content']) > 0){
                    $did_meet_requirements = true;
                } elseif($tr['tr_en_parent_id']==4256 && count($msg_references['ref_urls']) > 0){
                    $did_meet_requirements = true;
                }

                if($did_meet_requirements){
                    //We only need to meet a single requirement:
                    break;
                } else {
                    //Add this to list of what is needed to mark as complete so we can inform Master:
                    array_push($requirement_notes, $en_all_4331[$tr['tr_en_parent_id']]['m_name']);
                }
            }

            if(!$did_meet_requirements){
                return fn___redirect_message($k_url, '<div class="alert alert-danger" role="alert">Error: You are required to submit any of ['.join(', ', $requirement_notes).'] to mark [' . $trs[0]['in_outcome'] . '] as complete.</div>');
            }
        }


        //Did anything change?
        $status_changed = ($trs[0]['tr_status'] <= 1);
        $notes_changed = !($trs[0]['tr_content'] == trim($_POST['tr_content']));
        if (!$notes_changed && !$status_changed) {
            //Nothing seemed to change! Let them know:
            return fn___redirect_message($k_url, '<div class="alert alert-info" role="alert">Note: Nothing saved because nothing was changed.</div>');
        }

        //Has anything changed?
        if ($notes_changed) {
            //Updates k notes:
            $this->Database_model->fn___tr_update($trs[0]['tr_id'], array(
                'tr_content' => trim($_POST['tr_content']),
                'tr_en_type_id' => fn___detect_tr_en_type_id($_POST['tr_content']),
            ), (isset($udata['en_id']) ? $udata['en_id'] : $trs[0]['k_children_en_id']));
        }

        if ($status_changed) {
            //Also update tr_status, determine what it should be:
            $this->Matrix_model->in_actionplan_complete_up($trs[0], $trs[0]);
        }


        //Redirect back to page with success message:
        if (isset($_POST['fn___actionplan_next_in'])) {
            //Go to next item:
            $next_ins = $this->Matrix_model->fn___actionplan_next_in($trs[0]['tr_id']);
            if ($next_ins) {
                //Override original item:
                $k_url = '/my/actionplan/' . $next_ins[0]['tr_tr_parent_id'] . '/' . $next_ins[0]['in_id'];
            }
        }

        return fn___redirect_message($k_url, '<div class="alert alert-success" role="alert"><i class="fal fa-check-circle"></i> Successfully Saved</div>');
    }


    function reset_pass()
    {
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('view_shared/messenger_header', $data);
        $this->load->view('view_entities/en_pass_reset_ui');
        $this->load->view('view_shared/messenger_footer');
    }

}
