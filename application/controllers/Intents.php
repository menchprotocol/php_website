<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intents extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    //For trainers to see and manage an intent:
    function intent_manage($in_id)
    {

        //Authenticate, redirect if not:
        $udata = auth(array(1308), 1);

        //Fetch intent with 2 levels of depth:
        $intents = $this->Db_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status >=' => 0,
        ), array('fetch_grandchildren'));

        //Found it?
        if (!isset($intents[0])) {
            die('Error: Intent ID ' . $in_id . ' not found');
        }

        //Do we just wanna look at the raw data blob?
        if (isset($_GET['raw'])) {
            return echo_json($intents);
        }

        //Load view:
        $data = array(
            'title' => $intents[0]['c_outcome'],
            'in' => $intents[0],
            'in__active_parents' => $this->Db_model->in_parents_fetch(array(
                'tr_in_child_id' => $in_id,
                'tr_status' => 1,
            ), array('in__active_children')),
        );

        $this->load->view('shared/console_header', $data);
        $this->load->view('intents/intent_manage', $data);
        $this->load->view('shared/console_footer');
    }

    function orphan()
    {

        //Authenticate, redirect if not:
        $udata = auth(array(1308), 1);

        //Load view and passon data:
        $this->load->view('shared/console_header', array(
            'title' => 'Orphan Intents',
        ));
        $this->load->view('intents/intent_manage', array(
            //Passing this will load the orphans instead of the regular intent tree view:
            'orphan_intents' => $this->Db_model->in_orphans_fetch()
        ));
        $this->load->view('shared/console_footer');
    }


    function intent_public($in_id)
    {

        //Fetch data:
        $intents = $this->Db_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status >=' => 2, //Published or featured
        ), array('fetch_grandchildren', 'in__active_messages', 'in__active_parents'));


        //Validate Intent:
        if (!isset($intents[0])) {
            //Invalid key, redirect back:
            redirect_message('/' . $this->config->item('in_primary_id'), '<div class="alert alert-danger" role="alert">Invalid Intent ID</div>');
        }

        //Load home page:
        $this->load->view('shared/public_header', array(
            'title' => $intents[0]['c_outcome'],
            'in' => $intents[0],
        ));
        $this->load->view('intents/landing_page', array(
            'in' => $intents[0],
        ));
        $this->load->view('shared/public_footer');
    }

    /* ******************************
     * c Intent Processing
     ****************************** */

    function c_new()
    {
        $udata = auth(array(1308));
        if (!$udata) {
            return array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue',
            );
        } elseif (!isset($_POST['in_id']) || !isset($_POST['c_outcome']) || !isset($_POST['in_linkto_id']) || !isset($_POST['next_level'])) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing core inputs',
            ));
        } else {
            echo_json($this->Db_model->c_new($_POST['in_id'], $_POST['c_outcome'], $_POST['in_linkto_id'], $_POST['next_level'], $udata['u_id']));
        }
    }

    function c_move_c()
    {

        //Auth user and Load object:
        $udata = auth(array(1308));
        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid tr_id',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['from_in_id']) || intval($_POST['from_in_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing from_in_id',
            ));
        } elseif (!isset($_POST['to_in_id']) || intval($_POST['to_in_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing to_in_id',
            ));
        }


        //Fetch all three intents to ensure they are all valid and use them for engagement logging:
        $subject = $this->Db_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));
        $from = $this->Db_model->in_fetch(array(
            'in_id' => intval($_POST['from_in_id']),
        ));
        $to = $this->Db_model->in_fetch(array(
            'in_id' => intval($_POST['to_in_id']),
        ));

        if (!isset($subject[0]) || !isset($from[0]) || !isset($to[0])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid intent IDs',
            ));
        }


        //Make the move:
        $this->Db_model->tr_update(intval($_POST['tr_id']), array(
            'tr_en_parent_id' => $udata['u_id'],
            'tr_timestamp' => date("Y-m-d H:i:s"),
            'tr_in_parent_id' => intval($_POST['to_in_id']),
            //No need to update sorting here as a separate JS function would call that within half a second after the move...
        ));


        //Adjust tree on both branches:
        $updated_from_recursively = $this->Db_model->c_update_tree($from[0]['in_id'], array(
            'in__tree_count' => -($subject[0]['in__tree_count']),
            'c__tree_max_hours' => -(intval($subject[0]['c__tree_max_hours'])),
            'c__tree_messages' => -($subject[0]['c__tree_messages']),
        ));
        $updated_to_recursively = $this->Db_model->c_update_tree($to[0]['in_id'], array(
            'in__tree_count' => +($subject[0]['in__tree_count']),
            'c__tree_max_hours' => +(intval($subject[0]['c__tree_max_hours'])),
            'c__tree_messages' => +($subject[0]['c__tree_messages']),
        ));


        //Log transaction:
        $this->Db_model->tr_create(array(
            'tr_en_creator_id' => $udata['u_id'],
            'tr_metadata' => array(
                'post' => $_POST,
                'updated_from_recursively' => $updated_from_recursively,
                'updated_to_recursively' => $updated_to_recursively,
            ),
            'tr_content' => '[' . $subject[0]['c_outcome'] . '] was migrated from [' . $from[0]['c_outcome'] . '] to [' . $to[0]['c_outcome'] . ']', //Message migrated
            'tr_en_type_id' => 4254, //Intent migrated
            'tr_in_child_id' => intval($_POST['in_id']),
            'e_tr_id' => intval($_POST['tr_id']),
        ));


        //Return success
        echo_json(array(
            'status' => 1,
            'message' => 'Move completed',
        ));
    }

    function c_save_settings()
    {

        //Auth user and check required variables:
        $udata = auth(array(1308));

        //Validate Original intent:
        $intents = $this->Db_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));

        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        } elseif (!isset($_POST['level']) || intval($_POST['level']) < 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing level',
            ));
        } elseif (!isset($_POST['c_outcome']) || strlen($_POST['c_outcome']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent',
            ));
        } elseif (!isset($_POST['c_time_estimate'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Time Estimate',
            ));
        } elseif (intval($_POST['c_time_estimate']) > $this->config->item('in_seconds_max')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Maximum estimated time is ' . round(($this->config->item('in_seconds_max') / 3600), 2) . ' hours for each intent. If larger, break the intent down into smaller intents.',
            ));
        } elseif (!isset($_POST['apply_recurively'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Recursive setting',
            ));
        } elseif (!isset($_POST['c_cost_estimate'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Cost Estimate',
            ));
        } elseif (!isset($_POST['in_status'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Status',
            ));
        } elseif (!isset($_POST['c_points'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Points',
            ));
        } elseif (!isset($_POST['c_trigger_statements'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Trigger Statements',
            ));
        } elseif (intval($_POST['c_cost_estimate']) < 0 || intval($_POST['c_cost_estimate']) > 300) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Cost estimate must be $0-5000 USD',
            ));
        } elseif (!isset($_POST['in_is_any']) || !isset($_POST['c_require_url_to_complete']) || !isset($_POST['c_require_notes_to_complete'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Completion Settings',
            ));
        } elseif (count($intents) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Invalid in_id',
            ));
        }

        //Update array:
        $c_update = array(
            'c_outcome' => trim($_POST['c_outcome']),
            //These are also in the recursive adjustment array as they affect metadata
            'c_cost_estimate' => doubleval($_POST['c_cost_estimate']),
            'c_time_estimate' => intval($_POST['c_time_estimate']),
            'in_is_any' => intval($_POST['in_is_any']),
            'in_status' => intval($_POST['in_status']),
            'c_points' => intval($_POST['c_points']),
            'c_trigger_statements' => trim($_POST['c_trigger_statements']),
        );


        //This determines if there are any recursive updates needed on the tree:
        $updated_recursively = 0;
        $recursive_query = array();


        //Check to see which variables actually changed:
        foreach ($c_update as $key => $value) {

            //Did this value change?
            if ($_POST[$key] == $intents[0][$key]) {

                //No it did not! Remove it!
                unset($c_update[$key]);

            } else {

                //Something was updated!
                //Does it required a recursive upward update on the tree?
                if ($key == 'c_time_estimate') {
                    $recursive_query['c__tree_max_hours'] = intval($_POST[$key]) - intval($intents[0][$key]);
                }

            }
        }


        //Did anything change?
        if (count($c_update) > 0) {

            //YES, update the DB:
            $this->Db_model->in_update($_POST['in_id'], $c_update, true);

            //Any recursive updates needed?
            if (count($recursive_query) > 0) {
                $updated_recursively = $this->Db_model->c_update_tree($_POST['in_id'], $recursive_query);
            }

            //Any recursive down status sync requests?
            $children_updated = 0;
            if (intval($_POST['apply_recurively']) && !(intval($_POST['in_status']) == intval($intents[0]['in_status']))) {

                //Yes, sync downwards where current statuses match:
                $children = $this->Db_model->c_recursive_fetch(intval($_POST['in_id']), true);
                foreach ($children['c_flat'] as $child_in_id) {

                    //See what the status of this is, and update only if status matches:
                    $this->db->query("UPDATE tb_intents SET in_status=" . intval($_POST['in_status']) . " WHERE in_status=" . intval($intents[0]['in_status']) . " AND in_id=" . $child_in_id);

                    //Did it work?! Maybe not if the status was different...
                    if ($this->db->affected_rows()) {

                        //Yes it did update!

                        //Update counter:
                        $children_updated++;

                        //Log modify engagement for this intent:
                        $this->Db_model->tr_create(array(
                            'tr_en_creator_id' => $udata['u_id'],
                            'tr_content' => 'Status recursively updated from [' . $intents[0]['in_status'] . '] to [' . $_POST['in_status'] . '] initiated from parent intent #' . $intents[0]['in_id'] . ' [' . $intents[0]['c_outcome'] . ']',
                            'tr_en_type_id' => 4264, //Intent Modification
                            'tr_in_child_id' => $child_in_id,
                        ));

                    }
                }
            }

            //Log transaction for New Intent Link:
            $this->Db_model->tr_create(array(
                'tr_en_creator_id' => $udata['u_id'],
                'tr_content' => echo_changelog($intents[0], $c_update, 'c_'),
                'tr_metadata' => array(
                    'input_data' => $_POST,
                    'initial_data' => $intents[0],
                    'after' => $c_update,
                    'updated_recursively' => $updated_recursively,
                    'children_updated' => $children_updated,
                    'recursive_query' => $recursive_query,
                ),
                'tr_en_type_id' => ($_POST['level'] >= 2 && isset($c_update['in_status']) && $c_update['in_status'] < 0 ? 4252 : 4264), //Intent Archived OR Modification
                'tr_in_child_id' => intval($_POST['in_id']),
            ));

        }

        //Show success:
        return echo_json(array(
            'status' => 1,
            'children_updated' => $children_updated,
            'adjusted_c_count' => -($intents[0]['in__tree_count']),
            'message' => '<span><i class="fas fa-check"></i> Saved' . ($children_updated > 0 ? ' & ' . $children_updated . ' Recursive Updates' : '') . '</span>',
            'status_c_ui' => echo_status('in', $_POST['in_status'], true, 'left'),
            'status_cr_ui' => echo_status('tr_status', $_POST['tr_status'], true, 'left'),
        ));

    }

    function c_unlink()
    {


        //Auth user and check required variables:
        $udata = auth(array(1308));

        if (!$udata) {
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
            return false;
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            ));
            return false;
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) <= 0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent Link ID',
            ));
            return false;
        }

        //Fetch intent to see what kind is it:
        $intents = $this->Db_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status >=' => 0,
        ));
        if (!isset($intents[0])) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
            return false;
        }


        //Fetch parent ID:
        $in__active_parents = $this->Db_model->in_parents_fetch(array(
            'tr_id' => $_POST['tr_id'],
            'tr_status' => 1,
        ));
        if (!isset($in__active_parents[0])) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent Link ID',
            ));
            return false;
        }


        //Update parent tree (and upwards) based on the intent type BEFORE removing the link:
        $recursive_query = array(
            'in__tree_count' => -($intents[0]['in__tree_count']),
            'c__tree_max_hours' => -(intval($intents[0]['c__tree_max_hours'])),
            'c__tree_messages' => -($intents[0]['c__tree_messages']),
        );
        $updated_recursively = $this->Db_model->c_update_tree($in__active_parents[0]['tr_in_parent_id'], $recursive_query);


        //Remove Transaction:
        $this->Db_model->tr_update($_POST['tr_id'], array(
            'tr_en_parent_id' => $udata['u_id'],
            'tr_timestamp' => date("Y-m-d H:i:s"),
            'tr_status' => -1, //Archived
        ), $udata['u_id'], array(
            'recursive_query' => $recursive_query,
            'updated_recursively' => $updated_recursively,
        ));

        //Show success:
        echo_json(array(
            'status' => 1,
            'c_parent' => $in__active_parents[0]['tr_in_parent_id'],
            'adjusted_c_count' => -($intents[0]['in__tree_count']),
        ));

    }


    function in_sort_save()
    {

        //Auth user and Load object:
        $udata = auth(array(1308));
        if (!$udata) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort']) <= 0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Parent intent:
            $parent_intents = $this->Db_model->in_fetch(array(
                'in_id' => intval($_POST['in_id']),
            ));
            if (count($parent_intents) <= 0) {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid in_id',
                ));
            } else {

                //Fetch for the record:
                $children_before = $this->Db_model->cr_children_fetch(array(
                    'tr_in_parent_id' => intval($_POST['in_id']),
                    'tr_status' => 1,
                ));

                //Update them all:
                foreach ($_POST['new_sort'] as $rank => $tr_id) {
                    $this->Db_model->tr_update(intval($tr_id), array(
                        'tr_en_parent_id' => $udata['u_id'],
                        'tr_timestamp' => date("Y-m-d H:i:s"),
                        'tr_order' => intval($rank),
                    ));
                }

                //Fetch for the record:
                $children_after = $this->Db_model->cr_children_fetch(array(
                    'tr_in_parent_id' => intval($_POST['in_id']),
                    'tr_status' => 1,
                ));

                //Log transaction:
                $this->Db_model->tr_create(array(
                    'tr_en_creator_id' => $udata['u_id'],
                    'tr_content' => 'Sorted child intents for [' . $parent_intents[0]['c_outcome'] . ']',
                    'tr_metadata' => array(
                        'input_data' => $_POST,
                        'initial_data' => $children_before,
                        'after' => $children_after,
                    ),
                    'tr_en_type_id' => 4262, //Links Sorted
                    'tr_in_child_id' => intval($_POST['in_id']),
                ));

                //Display message:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-check"></i> Sorted',
                ));
            }
        }
    }

    function c_echo_tip()
    {

        $udata = auth(array(1308));
        //Used to load all the help messages within the Console:
        if (!$udata || !isset($_POST['intent_id']) || intval($_POST['intent_id']) < 1) {
            return echo_json(array(
                'success' => 0,
            ));
        }

        //Fetch Messages and the User's Got It Engagement History:
        $messages = $this->Db_model->i_fetch(array(
            'i_in_id' => intval($_POST['intent_id']),
            'i_status >' => 0, //Published in any form
        ));
        if (count($messages) == 0) {
            return echo_json(array(
                'success' => 0,
            ));
        }

        $help_content = null;
        foreach ($messages as $i) {

            //Log an engagement for all messages
            $this->Db_model->tr_create(array(
                'tr_en_creator_id' => $udata['u_id'],
                'tr_metadata' => $i,
                'tr_en_type_id' => 4273, //Got It
                'tr_in_child_id' => intval($_POST['intent_id']),
                'e_i_id' => $i['i_id'],
            ));

            //Build UI friendly Message:
            $help_content .= echo_i(array_merge($i, array('tr_en_child_id' => $udata['u_id'])), $udata['u_full_name']);
        }

        //Return results:
        echo_json(array(
            'success' => 1,
            'intent_id' => intval($_POST['intent_id']),
            'help_content' => $help_content,
        ));
    }


    function in_actionplans_load($in_id)
    {

        //Auth user and check required variables:
        $udata = auth(array(1308)); //Trainers

        if (!$udata) {
            die('<div class="alert alert-danger" role="alert">Session Expired</div>');
        } elseif (intval($in_id) <= 0) {
            die('<div class="alert alert-danger" role="alert">Missing Intent ID [' . $in_id . ']</div>');
        }

        //Load view for this iFrame:
        $this->load->view('shared/messenger_header', array(
            'title' => 'User Engagements',
        ));
        $this->load->view('engagements/intent_engagements', array(
            'in_id' => $in_id,
        ));
        $this->load->view('shared/messenger_footer');
    }


    function in_tr_load($in_id)
    {

        //Auth user and check required variables:
        $udata = auth(array(1308)); //Trainers

        if (!$udata) {
            die('<div class="alert alert-danger" role="alert">Session Expired</div>');
        } elseif (intval($in_id) <= 0) {
            die('<div class="alert alert-danger" role="alert">Missing Intent ID</div>');
        }

        //Load view for this iFrame:
        $this->load->view('shared/messenger_header', array(
            'title' => 'User Engagements',
        ));
        $this->load->view('engagements/in_tr_load', array(
            'in_id' => $in_id,
        ));
        $this->load->view('shared/messenger_footer');
    }


    /* ******************************
	 * i Messages
	 ****************************** */

    function in_messages_load($in_id)
    {
        $udata = auth();
        if (!$udata) {
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Login again to continue.</span>');
        } elseif (intval($in_id) <= 0) {
            die('<span style="color:#FF0000;">Error: Invalid Intent id.</span>');
        }

        //Don't show the heading here as we're loading inside an iframe:
        $_GET['skip_header'] = true;

        //Load view:
        $this->load->view('shared/console_header', array(
            'title' => 'Intent #' . $in_id . ' Messages',
        ));
        $this->load->view('intents/frame_messages', array(
            'in_id' => $in_id,
        ));
        $this->load->view('shared/console_footer');
    }

    function i_attach()
    {

        $udata = auth(array(1308));
        $file_size_max = $this->config->item('file_size_max');
        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh to Continue',
            ));
        } elseif (!isset($_POST['in_id']) || !isset($_POST['i_status'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing intent data.',
            ));
        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));
        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Unable to save file. Max file size allowed is ' . $file_size_max . ' MB.',
            ));
        } elseif ($_FILES[$_POST['upload_type']]['size'] > ($file_size_max * 1024 * 1024)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . $file_size_max . ' MB.',
            ));
        }


        //Attempt to save file locally:
        $file_parts = explode('.', $_FILES[$_POST['upload_type']]["name"]);
        $temp_local = "application/cache/temp_files/" . md5($file_parts[0] . $_FILES[$_POST['upload_type']]["type"] . $_FILES[$_POST['upload_type']]["size"]) . '.' . $file_parts[(count($file_parts) - 1)];
        move_uploaded_file($_FILES[$_POST['upload_type']]['tmp_name'], $temp_local);


        //Attempt to store in Cloud:
        if (isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type']) > 0) {
            $mime = $_FILES[$_POST['upload_type']]['type'];
        } else {
            $mime = mime_content_type($temp_local);
        }

        //Upload to S3:
        $new_file_url = trim(save_file($temp_local, $_FILES[$_POST['upload_type']], true));

        //What happened?
        if (!$new_file_url) {
            //Oops something went wrong:
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not save to cloud!',
            ));
        }


        $url_create = $this->Db_model->x_sync($new_file_url, 1326, 1, 1);

        //Did we have an error?
        if (!$url_create['status']) {
            //Oops something went wrong:
            return echo_json(array(
                'status' => 0,
                'message' => 'Failed to create internal URL from cloud URL',
            ));
        }


        //Create message:
        $i = $this->Db_model->i_create(array(
            'i_parent_u_id' => $udata['u_id'],
            'i_u_id' => $url_create['en']['u_id'],
            'i_in_id' => intval($_POST['in_id']),
            'tr_content' => '@' . $url_create['en']['u_id'],
            'i_status' => $_POST['i_status'],
            'i_rank' => 1 + $this->Db_model->max_value('tb_intent_messages', 'i_rank', array(
                    'i_status' => $_POST['i_status'],
                    'i_in_id' => $_POST['in_id'],
                )),
        ));


        //Update intent count & tree:
        $this->db->query("UPDATE tb_intents SET c__this_messages=c__this_messages+1 WHERE in_id=" . intval($_POST['in_id']));
        $updated_recursively = $this->Db_model->c_update_tree(intval($_POST['in_id']), array(
            'c__tree_messages' => 1,
        ));


        //Fetch full message:
        $new_messages = $this->Db_model->i_fetch(array(
            'i_id' => $i['i_id'],
        ));


        //Echo message:
        echo_json(array(
            'status' => 1,
            'message' => echo_message(array_merge($new_messages[0], array(
                'tr_en_child_id' => $udata['u_id'],
            ))),
        ));
    }

    function i_create()
    {

        $udata = auth(array(1308));
        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and Try again.',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0 || !is_valid_intent($_POST['in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Step',
            ));
        }

        //Make sure message is all good:
        $validation = message_validation($_POST['i_status'], $_POST['tr_content'], $_POST['in_id']);
        if (!$validation['status']) {
            //There was some sort of an error:
            return echo_json($validation);
        }


        //Create Message:
        $i = $this->Db_model->i_create(array(
            'i_parent_u_id' => $udata['u_id'],
            'i_in_id' => intval($_POST['in_id']),
            'i_status' => $_POST['i_status'],
            'i_rank' => 1 + $this->Db_model->max_value('tb_intent_messages', 'i_rank', array(
                    'i_status' => $_POST['i_status'],
                    'i_in_id' => intval($_POST['in_id']),
                )),
            //Referencing attributes:
            'tr_content' => $validation['tr_content'],
            'i_u_id' => $validation['i_u_id'],
        ));

        //Update intent count:
        $this->db->query("UPDATE tb_intents SET c__this_messages=c__this_messages+1 WHERE in_id=" . intval($_POST['in_id']));

        //Update tree:
        $updated_recursively = $this->Db_model->c_update_tree(intval($_POST['in_id']), array(
            'c__tree_messages' => 1,
        ));


        //Fetch full message:
        $new_messages = $this->Db_model->i_fetch(array(
            'i_id' => $i['i_id'],
        ), 1);

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_message(array_merge($new_messages[0], array(
                'tr_en_child_id' => $udata['u_id'],
            ))),
        ));
    }

    function i_modify()
    {

        //Auth user and Load object:
        $udata = auth(array(1308));
        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh.',
            ));
        } elseif (!isset($_POST['i_id']) || intval($_POST['i_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message ID',
            ));
        } elseif (!isset($_POST['tr_content'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0 || !is_valid_intent($_POST['in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }


        //Fetch Message:
        $messages = $this->Db_model->i_fetch(array(
            'i_id' => intval($_POST['i_id']),
            'i_status >=' => 0,
        ));
        if (count($messages) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }

        //Make sure message is all good:
        $validation = message_validation($_POST['i_status'], $_POST['tr_content'], $_POST['in_id']);

        if (!$validation['status']) {
            //There was some sort of an error:
            return echo_json($validation);
        }


        //All good, lets move on:
        //Define what needs to be updated:
        $to_update = array(
            'i_parent_u_id' => $udata['u_id'],
            'i_timestamp' => date("Y-m-d H:i:s"),
            //Could have been modified:
            'tr_content' => $validation['tr_content'],
            'i_u_id' => $validation['i_u_id'],
        );


        if (!($_POST['initial_i_status'] == $_POST['i_status'])) {
            //Change the status:
            $to_update['i_status'] = $_POST['i_status'];
            //Put it at the end of the new list:
            $to_update['i_rank'] = 1 + $this->Db_model->max_value('tb_intent_messages', 'i_rank', array(
                    'i_status' => $_POST['i_status'],
                    'i_in_id' => intval($_POST['in_id']),
                ));
        }

        //Now update the DB:
        $this->Db_model->tr_update(intval($_POST['i_id']), $to_update, $udata['u_id']);

        //Re-fetch the message for display purposes:
        $new_messages = $this->Db_model->i_fetch(array(
            'i_id' => intval($_POST['i_id']),
        ), 0);

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_i(array_merge($new_messages[0], array('tr_en_child_id' => $udata['u_id'])), $udata['u_full_name']),
            'new_status' => echo_status('i_status', $new_messages[0]['i_status'], 1, 'right'),
            'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));
    }

    function i_archive()
    {
        //Auth user and Load object:
        $udata = auth(array(1308));

        if (!$udata) {
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));
        } elseif (!isset($_POST['i_id']) || intval($_POST['i_id']) <= 0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Message ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0 || !is_valid_intent($_POST['in_id'])) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        } else {

            //Fetch Message:
            $messages = $this->Db_model->i_fetch(array(
                'i_id' => intval($_POST['i_id']),
                'i_status >=' => 0, //Not Archived
            ));
            if (!isset($messages[0])) {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Message Not Found',
                ));
            } else {

                //Now update the DB:
                $this->Db_model->tr_update(intval($_POST['i_id']), array(
                    'i_parent_u_id' => $udata['u_id'],
                    'i_timestamp' => date("Y-m-d H:i:s"),
                    'i_status' => -1, //Archived
                ), $udata['u_id']);

                //Update intent count:
                $this->db->query("UPDATE tb_intents SET c__this_messages=c__this_messages-1 WHERE in_id=" . intval($_POST['in_id']));

                //Update tree:
                $updated_recursively = $this->Db_model->c_update_tree(intval($_POST['in_id']), array(
                    'c__tree_messages' => -1,
                ));

                echo_json(array(
                    'status' => 1,
                    'message' => '<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Archived</span>',
                ));
            }
        }
    }

    function i_sort()
    {

        //Auth user and Load object:
        $udata = auth(array(1308));
        if (!$udata) {
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));
        } elseif (!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort']) <= 0) {
            echo_json(array(
                'status' => 1, //Do not treat this as error as it could happen in moving Messages between types
                'message' => 'There was nothing to sort',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0 || !is_valid_intent($_POST['in_id'])) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        } else {

            //Update them all:
            $sort_count = 0;
            foreach ($_POST['new_sort'] as $i_rank => $i_id) {
                if (intval($i_id) > 0) {
                    $sort_count++;
                    $this->Db_model->tr_update($i_id, array(
                        'i_rank' => intval($i_rank),
                    ));
                }
            }

            //Log transaction:
            $this->Db_model->tr_create(array(
                'tr_en_creator_id' => $udata['u_id'],
                'tr_metadata' => $_POST,
                'tr_en_type_id' => 4262, //Messages sorted
                'tr_in_child_id' => intval($_POST['in_id']),
            ));

            echo_json(array(
                'status' => 1,
                'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
            ));
        }
    }

}