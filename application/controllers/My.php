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

    function fb_profile($u_id)
    {

        $udata = auth(array(1308));
        $current_us = $this->Db_model->en_fetch(array(
            'u_id' => $u_id,
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
                'fb_profile' => $this->Comm_model->fb_graph('GET', '/' . $current_us[0]['u_fb_psid'], array()),
                'en' => $current_us[0],
            ));

        }
    }

    /* ******************************
     * Messenger Persistent Menu
     ****************************** */

    function actionplan($w_id = 0, $in_id = 0)
    {

        $this->load->view('shared/messenger_header', array(
            'title' => '🚩 Action Plan',
        ));
        //include main body:
        $this->load->view('actionplans/actionplan_frame', array(
            'in_id' => $in_id,
            'w_id' => $w_id,
        ));
        $this->load->view('shared/messenger_footer');
    }

    function display_actionplan($u_fb_psid, $w_id = 0, $in_id = 0)
    {

        //Get session data in case user is doing a browser login:
        $udata = $this->session->userdata('user');
        $no_session_w = (!isset($udata['u__ws']) || count($udata['u__ws']) < 1);

        //Fetch Bootcamps for this user:
        if (!$u_fb_psid && $no_session_w && !filter_array($udata['en__parents'], 'en_id', 1308)) {
            //There is an issue here!
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif ($no_session_w && !is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
        }

        //Set subscription filters:
        $w_filter = array();

        //Do we have a use session?
        if ($w_id > 0) {
            //Yes! It seems to be a desktop login:
            $w_filter['w_id'] = $w_id;
        } elseif (count($udata['u__ws']) > 0) {
            //Yes! It seems to be a desktop login:
            $w_filter['tr_en_parent_id'] = $udata['u__ws'][0]['tr_en_parent_id'];
            $w_filter['w_status >='] = 0;
        }

        if ($u_fb_psid > 0) {
            //No, we should have a Facebook PSID to try to find them:
            $w_filter['u_fb_psid'] = $u_fb_psid;
            $w_filter['w_status >='] = 0;
        }

        //Try finding them:
        $ws = $this->Db_model->w_fetch($w_filter, array('in', 'en'));

        if (count($ws) == 0) {

            //No subscriptions found:
            die('<div class="alert alert-danger" role="alert">You have no active subscriptions yet. ' . echo_pa_lets() . '</div>');

        } elseif (count($ws) > 1) {

            //Log action plan view engagement:
            $this->Db_model->tr_create(array(
                'tr_en_type_id' => 4283,
                'tr_en_creator_id' => $ws[0]['u_id'],
            ));

            //Let them choose between subscriptions:
            echo '<h3 class="student-h3 primary-title">My Subscriptions</h3>';
            echo '<div class="list-group" style="margin-top: 10px;">';
            foreach ($ws as $w) {
                echo echo_w_students($w);
            }
            echo '</div>';

        } elseif (count($ws) == 1) {

            //We found a single subscription, load this by default:
            if (!$w_id || !$in_id) {
                //User with a single subscription
                $w_id = $ws[0]['w_id'];
                $in_id = $ws[0]['in_id']; //TODO set to current/focused intent
            }

            //Log action plan view engagement:
            $this->Db_model->tr_create(array(
                'tr_en_type_id' => 4283,
                'tr_en_creator_id' => $ws[0]['u_id'],
                'tr_in_child_id' => $in_id,
                'tr_tr_parent_id' => $w_id,
            ));


            //We have a single item to load:
            //Now we need to load the action plan:
            $k_ins = $this->Db_model->tr_fetch(array(
                'w_id' => $w_id,
                'in_status >=' => 2,
                'tr_in_child_id' => $in_id,
            ), array('w', 'cr', 'cr_c_parent'));

            $k_outs = $this->Db_model->tr_fetch(array(
                'w_id' => $w_id,
                'in_status >=' => 2,
                'tr_in_parent_id' => $in_id,
            ), array('w', 'cr', 'cr_c_child'));


            $intents = $this->Db_model->in_fetch(array(
                'in_status >=' => 2,
                'in_id' => $in_id,
            ));

            if (count($intents) < 1 || (!count($k_ins) && !count($k_outs))) {

                //Ooops, we had issues finding th is intent! Should not happen, report:
                $this->Db_model->tr_create(array(
                    'tr_en_creator_id' => $ws[0]['u_id'],
                    'tr_metadata' => $ws,
                    'tr_content' => 'Unable to load a specific intent for the student Action Plan! Should not happen...',
                    'tr_en_type_id' => 4246,
                    'tr_tr_parent_id' => $w_id,
                    'tr_in_child_id' => $in_id,
                ));

                die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
            }

            //All good, Load UI:
            $this->load->view('actionplans/actionplan_ui.php', array(
                'w' => $ws[0], //We must have 1 by now!
                'in' => $intents[0],
                'k_ins' => $k_ins,
                'k_outs' => $k_outs,
            ));

        }
    }

    function w_delete($w_id)
    {

        //Validate it's an admin:
        if (!auth(array(1281))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired, login to continue...',
            ));
        }

        //Delete subscription and report back:
        $archive_stats = array();

        $this->db->query("DELETE FROM tb_actionplans WHERE w_id=" . $w_id);
        $archive_stats['tb_actionplans'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM tb_actionplan_links WHERE tr_tr_parent_id=" . $w_id);
        $archive_stats['tb_actionplan_links'] = $this->db->affected_rows();

        $this->db->query("DELETE FROM table_ledger WHERE tr_tr_parent_id=" . $w_id);
        $archive_stats['table_ledger'] = $this->db->affected_rows();

        return echo_json(array(
            'status' => 1,
            'w_id' => $w_id,
            'stats' => $archive_stats,
        ));
    }

    function load_w_actionplan()
    {

        //Auth user and check required variables:
        $udata = auth(array(1308)); //Trainers

        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['w_id']) || intval($_POST['w_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Subscriptions ID',
            ));
        }

        //Fetch subscription
        $validate_subscription = $this->Db_model->w_fetch(array(
            'w_id' => $_POST['w_id'], //Other than this one...
        ));
        if (!(count($validate_subscription) == 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Subscriptions ID',
            ));
        }
        $w = $validate_subscription[0];

        //Load Action Plan iFrame:
        return echo_json(array(
            'status' => 1,
            'url' => '/my/actionplan/' . $w['w_id'] . '/' . $w['w_in_id'],
        ));

    }


    function load_u_engagements($u_id)
    {

        //Auth user and check required variables:
        $udata = auth(array(1308)); //Trainers

        if (!$udata) {
            die('<div class="alert alert-danger" role="alert">Session Expired</div>');
        } elseif (intval($u_id) <= 0) {
            die('<div class="alert alert-danger" role="alert">Missing User ID</div>');
        }

        //Load view for this iFrame:
        $this->load->view('shared/messenger_header', array(
            'title' => 'User Engagements',
        ));
        $this->load->view('engagements/engagement_list', array(
            'u_id' => $u_id,
        ));
        $this->load->view('shared/messenger_footer');
    }

    function skip_tree($w_id, $in_id, $tr_id)
    {
        //Start skipping:
        $total_skipped = count($this->Db_model->k_skip_recursive_down($tr_id));

        //Draft message:
        $message = '<div class="alert alert-success" role="alert">' . $total_skipped . ' insight' . echo__s($total_skipped) . ' successfully skipped.</div>';

        //Find the next item to navigate them to:
        $trs_next = $this->Db_model->k_next_fetch($w_id);
        if ($trs_next) {
            redirect_message('/my/actionplan/' . $trs_next[0]['tr_tr_parent_id'] . '/' . $trs_next[0]['in_id'], $message);
        } else {
            redirect_message('/my/actionplan', $message);
        }
    }

    function choose_any_path($w_id, $tr_in_parent_id, $in_id, $w_key)
    {
        if (md5($w_id . 'kjaghksjha*(^' . $in_id . $tr_in_parent_id) == $w_key) {
            if ($this->Db_model->k_choose_or($w_id, $tr_in_parent_id, $in_id)) {
                redirect_message('/my/actionplan/' . $w_id . '/' . $in_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');
            } else {
                //We had some sort of an error:
                redirect_message('/my/actionplan/' . $w_id . '/' . $tr_in_parent_id, '<div class="alert alert-danger" role="alert">There was an error saving your answer.</div>');
            }
        }
    }

    function update_k_save()
    {

        //Validate integrity of request:
        if (!isset($_POST['tr_id']) || intval($_POST['tr_id']) <= 0 || !isset($_POST['tr_content'])) {
            return redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: Missing Core Data.</div>');
        }

        //Fetch student name and details:
        $udata = $this->session->userdata('user');
        $trs = $this->Db_model->tr_fetch(array(
            'tr_id' => $_POST['tr_id'],
        ), array('w', 'cr', 'cr_c_child'));

        if (!(count($trs) == 1)) {
            return redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: Invalid submission ID.</div>');
        }
        $k_url = '/my/actionplan/' . $trs[0]['tr_tr_parent_id'] . '/' . $trs[0]['in_id'];


        //Do we have what it takes to mark as complete?
        if ($trs[0]['c_require_url_to_complete'] && count(extract_urls($_POST['tr_content'])) < 1) {
            return redirect_message($k_url, '<div class="alert alert-danger" role="alert">Error: URL Required to mark [' . $trs[0]['c_outcome'] . '] as complete.</div>');
        } elseif ($trs[0]['c_require_notes_to_complete'] && strlen($_POST['tr_content']) < 1) {
            return redirect_message($k_url, '<div class="alert alert-danger" role="alert">Error: Notes Required to mark [' . $trs[0]['c_outcome'] . '] as complete.</div>');
        }


        //Did anything change?
        $status_changed = ($trs[0]['k_status'] <= 1);
        $notes_changed = !($trs[0]['tr_content'] == trim($_POST['tr_content']));
        if (!$notes_changed && !$status_changed) {
            //Nothing seemed to change! Let them know:
            return redirect_message($k_url, '<div class="alert alert-info" role="alert">Note: Nothing saved because nothing was changed.</div>');
        }

        //Has anything changed?
        if ($notes_changed) {
            //Updates k notes:
            $this->Db_model->tr_update($trs[0]['tr_id'], array(
                'tr_content' => trim($_POST['tr_content']),
                'tr_en_type_id' => detect_tr_en_type_id($_POST['tr_content']),
            ), (isset($udata['u_id']) ? $udata['u_id'] : $trs[0]['k_children_u_id']));
        }

        if ($status_changed) {
            //Also update k_status, determine what it should be:
            $this->Db_model->k_complete_recursive_up($trs[0], $trs[0]);
        }


        //Redirect back to page with success message:
        if (isset($_POST['k_next_redirect']) && intval($_POST['k_next_redirect']) > 0) {
            //Go to next item:
            $trs_next = $this->Db_model->k_next_fetch($trs[0]['w_id'], (intval($_POST['k_next_redirect']) > 1 ? intval($_POST['k_next_redirect']) : 0));
            if ($trs_next) {
                //Override original item:
                $k_url = '/my/actionplan/' . $trs_next[0]['tr_tr_parent_id'] . '/' . $trs_next[0]['in_id'];

                if (intval($_POST['is_from_messenger'])) {
                    //Also send confirmation messages via messenger:
                    $this->Comm_model->compose_messages(array(
                        'tr_en_child_id' => $trs[0]['k_children_u_id'],
                        'tr_in_child_id' => $trs_next[0]['in_id'],
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
