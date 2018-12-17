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

        $udata = auth(array(1308));
        $current_us = $this->Database_model->en_fetch(array(
            'en_id' => $en_id,
        ));

        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and Try again.',
            ));
        } elseif (count($current_us) == 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'User not found!',
            ));
        } elseif (strlen($current_us[0]['u_fb_psid']) < 10) {
            return echo_json(array(
                'status' => 0,
                'message' => 'User does not seem to be connected to Mench, so profile data cannot be fetched',
            ));
        } else {

            //Fetch results and show:
            return echo_json(array(
                'fb_profile' => $this->Chat_model->fn___facebook_graph('GET', '/'.$current_us[0]['u_fb_psid'], array()),
                'en' => $current_us[0],
            ));

        }

    }

    /* ******************************
     * Messenger Persistent Menu
     ****************************** */

    function actionplan($actionplan_tr_id = 0, $in_id = 0)
    {

        $this->load->view('shared/messenger_header', array(
            'title' => '🚩 Action Plan',
        ));
        //include main body:
        $this->load->view('actionplans/actionplan_frame', array(
            'in_id' => $in_id,
            'actionplan_tr_id' => $actionplan_tr_id,
        ));
        $this->load->view('shared/messenger_footer');
    }

    function display_actionplan($u_fb_psid, $actionplan_tr_id = 0, $in_id = 0)
    {

        //Get session data in case user is doing a browser login:
        $udata = $this->session->userdata('user');
        $no_session_w = (!isset($udata['en__actionplans']) || count($udata['en__actionplans']) < 1);

        //Fetch Bootcamps for this user:
        if (!$u_fb_psid && $no_session_w && !filter_array($udata['en__parents'], 'en_id', 1308)) {
            //There is an issue here!
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif ($no_session_w && !is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
        }

        //Set Action Plan filters:
        $w_filter = array();

        //Do we have a use session?
        if ($actionplan_tr_id > 0) {
            //Yes! It seems to be a desktop login:
            $w_filter['tr_id'] = $actionplan_tr_id;
        } elseif (count($udata['en__actionplans']) > 0) {
            //Yes! It seems to be a desktop login:
            $w_filter['tr_en_parent_id'] = $udata['en__actionplans'][0]['tr_en_parent_id'];
            $w_filter['tr_status >='] = 0;
        }

        if ($u_fb_psid > 0) {
            //No, we should have a Facebook PSID to try to find them:
            $w_filter['u_fb_psid'] = $u_fb_psid;
            $w_filter['tr_status >='] = 0;
        }

        //Try finding them:
        $trs = $this->Database_model->w_fetch($w_filter, array('in', 'en'));

        if (count($trs) == 0) {

            //No Action Plans found:
            die('<div class="alert alert-danger" role="alert">You have no active Action Plans yet. ' . echo_pa_lets() . '</div>');

        } elseif (count($trs) > 1) {

            //Log action plan view engagement:
            $this->Database_model->tr_create(array(
                'tr_en_type_id' => 4283,
                'tr_en_credit_id' => $trs[0]['en_id'],
            ));

            //Let them choose between Action Plans:
            echo '<h3 class="master-h3 primary-title">My Action Plan</h3>';
            echo '<div class="list-group" style="margin-top: 10px;">';
            foreach ($trs as $w) {
                echo echo_w_masters($w);
            }
            echo '</div>';

        } elseif (count($trs) == 1) {

            //We found a single Action Plan, load this by default:
            if (!$actionplan_tr_id || !$in_id) {
                //User with a single Action Plan
                $actionplan_tr_id = $trs[0]['tr_id'];
                $in_id = $trs[0]['in_id']; //TODO set to current/focused intent
            }

            //Log action plan view engagement:
            $this->Database_model->tr_create(array(
                'tr_en_type_id' => 4283,
                'tr_en_credit_id' => $trs[0]['en_id'],
                'tr_in_child_id' => $in_id,
                'tr_tr_parent_id' => $actionplan_tr_id,
            ));


            //We have a single item to load:
            //Now we need to load the action plan:
            $k_ins = $this->Database_model->tr_fetch(array(
                'tr_id' => $actionplan_tr_id,
                'in_status >=' => 2,
                'tr_in_child_id' => $in_id,
            ), array('w', 'cr', 'cr_c_parent'));

            $k_outs = $this->Database_model->tr_fetch(array(
                'tr_id' => $actionplan_tr_id,
                'in_status >=' => 2,
                'tr_in_parent_id' => $in_id,
            ), array('w', 'cr', 'cr_c_child'));


            $intents = $this->Database_model->in_fetch(array(
                'in_status >=' => 2,
                'in_id' => $in_id,
            ));

            if (count($intents) < 1 || (!count($k_ins) && !count($k_outs))) {

                //Ooops, we had issues finding th is intent! Should not happen, report:
                $this->Database_model->tr_create(array(
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
            $this->load->view('actionplans/actionplan_ui.php', array(
                'w' => $trs[0], //We must have 1 by now!
                'in' => $intents[0],
                'k_ins' => $k_ins,
                'k_outs' => $k_outs,
            ));

        }
    }


    function load_w_actionplan()
    {

        //Auth user and check required variables:
        $udata = auth(array(1308)); //miners

        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Action Plan ID',
            ));
        }

        //Fetch Action Plan
        $actionplans = $this->Database_model->w_fetch(array(
            'tr_id' => $_POST['tr_id'], //Other than this one...
        ));
        if (!(count($actionplans) == 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Action Plan ID',
            ));
        }
        $w = $actionplans[0];

        //Load Action Plan iFrame:
        return echo_json(array(
            'status' => 1,
            'url' => '/my/actionplan/' . $w['tr_id'] . '/' . $w['tr_in_child_id'],
        ));

    }


    function load_u_engagements($en_id)
    {

        //Auth user and check required variables:
        $udata = auth(array(1308)); //miners

        if (!$udata) {
            die('<div class="alert alert-danger" role="alert">Session Expired</div>');
        } elseif (intval($en_id) <= 0) {
            die('<div class="alert alert-danger" role="alert">Missing User ID</div>');
        }

        //Load view for this iFrame:
        $this->load->view('shared/messenger_header', array(
            'title' => 'User Engagements',
        ));
        $this->load->view('ledger/engagement_list', array(
            'en_id' => $en_id,
        ));
        $this->load->view('shared/messenger_footer');
    }

    function skip_tree($tr_id, $in_id, $tr_id)
    {
        //Start skipping:
        $total_skipped = count($this->Database_model->k_skip_recursive_down($tr_id));

        //Draft message:
        $message = '<div class="alert alert-success" role="alert">' . $total_skipped . ' concept' . echo__s($total_skipped) . ' successfully skipped.</div>';

        //Find the next item to navigate them to:
        $next_ins = $this->Matrix_model->fn___in_next_actionplan($tr_id);
        if ($next_ins) {
            redirect_message('/my/actionplan/' . $next_ins[0]['tr_tr_parent_id'] . '/' . $next_ins[0]['in_id'], $message);
        } else {
            redirect_message('/my/actionplan', $message);
        }
    }

    function choose_any_path($tr_id, $tr_in_parent_id, $in_id, $w_key)
    {
        if (md5($tr_id . 'kjaghksjha*(^' . $in_id . $tr_in_parent_id) == $w_key) {
            if ($this->Database_model->k_choose_or($tr_id, $tr_in_parent_id, $in_id)) {
                redirect_message('/my/actionplan/' . $tr_id . '/' . $in_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');
            } else {
                //We had some sort of an error:
                redirect_message('/my/actionplan/' . $tr_id . '/' . $tr_in_parent_id, '<div class="alert alert-danger" role="alert">There was an error saving your answer.</div>');
            }
        }
    }

    function update_k_save()
    {

        //Validate integrity of request:
        if (!isset($_POST['tr_id']) || intval($_POST['tr_id']) <= 0 || !isset($_POST['tr_content'])) {
            return redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: Missing Core Data.</div>');
        }

        //Fetch master name and details:
        $udata = $this->session->userdata('user');
        $trs = $this->Database_model->tr_fetch(array(
            'tr_id' => $_POST['tr_id'],
        ), array('w', 'cr', 'cr_c_child'));

        if (!(count($trs) == 1)) {
            return redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: Invalid submission ID.</div>');
        }
        $k_url = '/my/actionplan/' . $trs[0]['tr_tr_parent_id'] . '/' . $trs[0]['in_id'];


        //Do we have what it takes to mark as complete?
        $obj_breakdown = fn___text_analyze($_POST['tr_content']);

        if ($trs[0]['c_require_url_to_complete'] && count($obj_breakdown['en_urls']) < 1) {
            return redirect_message($k_url, '<div class="alert alert-danger" role="alert">Error: URL Required to mark [' . $trs[0]['in_outcome'] . '] as complete.</div>');
        } elseif ($trs[0]['c_require_notes_to_complete'] && strlen($_POST['tr_content']) < 1) {
            return redirect_message($k_url, '<div class="alert alert-danger" role="alert">Error: Notes Required to mark [' . $trs[0]['in_outcome'] . '] as complete.</div>');
        }


        //Did anything change?
        $status_changed = ($trs[0]['tr_status'] <= 1);
        $notes_changed = !($trs[0]['tr_content'] == trim($_POST['tr_content']));
        if (!$notes_changed && !$status_changed) {
            //Nothing seemed to change! Let them know:
            return redirect_message($k_url, '<div class="alert alert-info" role="alert">Note: Nothing saved because nothing was changed.</div>');
        }

        //Has anything changed?
        if ($notes_changed) {
            //Updates k notes:
            $this->Database_model->tr_update($trs[0]['tr_id'], array(
                'tr_content' => trim($_POST['tr_content']),
                'tr_en_type_id' => detect_tr_en_type_id($_POST['tr_content']),
            ), (isset($udata['en_id']) ? $udata['en_id'] : $trs[0]['k_children_en_id']));
        }

        if ($status_changed) {
            //Also update tr_status, determine what it should be:
            $this->Matrix_model->in_actionplan_complete_up($trs[0], $trs[0]);
        }


        //Redirect back to page with success message:
        if (isset($_POST['fn___in_next_actionplan'])) {
            //Go to next item:
            $next_ins = $this->Matrix_model->fn___in_next_actionplan($trs[0]['tr_id']);
            if ($next_ins) {
                //Override original item:
                $k_url = '/my/actionplan/' . $next_ins[0]['tr_tr_parent_id'] . '/' . $next_ins[0]['in_id'];

                if (intval($_POST['is_from_messenger'])) {
                    //Also send confirmation messages via messenger:
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $trs[0]['k_children_en_id'],
                        'tr_in_child_id' => $next_ins[0]['in_id'],
                        'tr_tr_parent_id' => $trs[0]['tr_tr_parent_id'],
                    ));
                }
            }
        }

        return redirect_message($k_url, '<div class="alert alert-success" role="alert"><i class="fal fa-check-circle"></i> Successfully Saved</div>');
    }


    function reset_pass()
    {
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('shared/messenger_header', $data);
        $this->load->view('entities/password_reset');
        $this->load->view('shared/messenger_footer');
    }

}
