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

        //Fetch intent with 2 levels of children:
        $intents = $this->Database_model->in_fetch(array(
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
            'title' => $intents[0]['in_outcome'],
            'in' => $intents[0],
            'in__active_parents' => $this->Old_model->cr_parents_fetch(array(
                'tr_in_child_id' => $in_id,
                'tr_status' => 1,
            ), array('in__active_children')),
        );

        $this->load->view('shared/matrix_header', $data);
        $this->load->view('intents/intent_manage', $data);
        $this->load->view('shared/matrix_footer');
    }

    function orphan()
    {

        //Authenticate, redirect if not:
        $udata = auth(array(1308), 1);

        //Load view and passon data:
        $this->load->view('shared/matrix_header', array(
            'title' => 'Orphan Intents',
        ));
        $this->load->view('intents/intent_manage', array(
            //Passing this will load the orphans instead of the regular intent tree view:
            'orphan_intents' => $this->Database_model->in_fetch(array(
                ' NOT EXISTS (SELECT 1 FROM table_ledger WHERE in_id=tr_in_child_id AND tr_status>=0) ' => null,
            )),
        ));
        $this->load->view('shared/matrix_footer');
    }


    function intent_public($in_id)
    {

        //Fetch data:
        $intents = $this->Database_model->in_fetch(array(
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
            'title' => $intents[0]['in_outcome'],
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

    function in_combo_create()
    {
        $udata = auth(array(1308));
        if (!$udata) {
            return array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue',
            );
        } elseif (!isset($_POST['in_id']) || !isset($_POST['in_outcome']) || !isset($_POST['in_linkto_id']) || !isset($_POST['next_level'])) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing core inputs',
            ));
        } else {
            echo_json($this->Database_model->in_combo_create($_POST['in_id'], $_POST['in_outcome'], $_POST['in_linkto_id'], $_POST['next_level'], $udata['en_id']));
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
        $subject = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));
        $from = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['from_in_id']),
        ));
        $to = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['to_in_id']),
        ));

        if (!isset($subject[0]) || !isset($from[0]) || !isset($to[0])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid intent IDs',
            ));
        }


        //Make the move:
        $this->Database_model->tr_update(intval($_POST['tr_id']), array(
            'tr_en_parent_id' => $udata['en_id'],
            'tr_in_parent_id' => intval($_POST['to_in_id']),
            //No need to update sorting here as a separate JS function would call that within half a second after the move...
        ), $udata['en_id']);


        //Adjust tree on both branches that have been affected:
        $updated_from_recursively = $this->Database_model->metadata_tree_update('in', $from[0]['in_id'], array(
            'in__tree_in_count' => -($subject[0]['in__tree_in_count']),
            'in__tree_max_seconds' => -(intval($subject[0]['in__tree_max_seconds'])),
            'in__messages_tree_count' => -($subject[0]['in__messages_tree_count']),
        ));
        $updated_to_recursively = $this->Database_model->metadata_tree_update('in', $to[0]['in_id'], array(
            'in__tree_in_count' => +($subject[0]['in__tree_in_count']),
            'in__tree_max_seconds' => +(intval($subject[0]['in__tree_max_seconds'])),
            'in__messages_tree_count' => +($subject[0]['in__messages_tree_count']),
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
        $intents = $this->Database_model->in_fetch(array(
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
        } elseif (!isset($_POST['in_outcome']) || strlen($_POST['in_outcome']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent',
            ));
        } elseif (!isset($_POST['in_seconds'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Time Estimate',
            ));
        } elseif (intval($_POST['in_seconds']) > $this->config->item('in_seconds_max')) {
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
            'in_outcome' => trim($_POST['in_outcome']),
            //These are also in the recursive adjustment array as they affect metadata
            'c_cost_estimate' => doubleval($_POST['c_cost_estimate']),
            'in_seconds' => intval($_POST['in_seconds']),
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
                if ($key == 'in_seconds') {
                    $recursive_query['in__tree_max_seconds'] = intval($_POST[$key]) - intval($intents[0][$key]);
                }

            }
        }


        //Did anything change?
        if (count($c_update) > 0) {

            //YES, update the DB:
            $this->Database_model->in_update($_POST['in_id'], $c_update, $udata['en_id']);

            //Any recursive updates needed?
            if (count($recursive_query) > 0) {
                $updated_recursively = $this->Database_model->metadata_tree_update('in', $_POST['in_id'], $recursive_query);
            }

            //Any recursive down status sync requests?
            $children_updated = 0;
            if (intval($_POST['apply_recurively']) && !(intval($_POST['in_status']) == intval($intents[0]['in_status']))) {

                //Yes, sync downwards where current statuses match:
                $children = $this->Database_model->in_recursive_fetch(intval($_POST['in_id']), true);

                //Fetch all intents that match parent intent status:
                $child_intents = $this->Database_model->in_fetch(array(
                    'in_id IN ('.join(',' , $children['in_flat_tree']).')' => null,
                    'in_status' => intval($intents[0]['in_status']),
                ));

                foreach ($child_intents as $child_in) {
                    //Update this intent as the status did match:
                    $children_updated += $this->Database_model->in_update($child_in['in_id'], array('in_status' => intval($_POST['in_status'])), $udata['en_id']);
                }
            }
        }

        //Show success:
        return echo_json(array(
            'status' => 1,
            'children_updated' => $children_updated,
            'adjusted_c_count' => -($intents[0]['in__tree_in_count']),
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
        $intents = $this->Database_model->in_fetch(array(
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
        $in__active_parents = $this->Old_model->cr_parents_fetch(array(
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
            'in__tree_in_count' => -($intents[0]['in__tree_in_count']),
            'in__tree_max_seconds' => -(intval($intents[0]['in__tree_max_seconds'])),
            'in__messages_tree_count' => -($intents[0]['in__messages_tree_count']),
        );
        $updated_recursively = $this->Database_model->metadata_tree_update('in', $in__active_parents[0]['tr_in_parent_id'], $recursive_query);


        //Remove Transaction:
        $this->Database_model->tr_update($_POST['tr_id'], array(
            'tr_status' => -1, //Removed
        ), $udata['en_id']);

        //Show success:
        echo_json(array(
            'status' => 1,
            'c_parent' => $in__active_parents[0]['tr_in_parent_id'],
            'adjusted_c_count' => -($intents[0]['in__tree_in_count']),
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
            $parent_intents = $this->Database_model->in_fetch(array(
                'in_id' => intval($_POST['in_id']),
            ));
            if (count($parent_intents) <= 0) {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid in_id',
                ));
            } else {

                //Fetch for the record:
                $children_before = $this->Database_model->tr_fetch(array(
                    'tr_in_parent_id' => intval($_POST['in_id']),
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                    'tr_status >=' => 0,
                    'in_status >=' => 0,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

                //Update them all:
                foreach ($_POST['new_sort'] as $rank => $tr_id) {
                    $this->Database_model->tr_update(intval($tr_id), array(
                        'tr_order' => intval($rank),
                    ), $udata['en_id']);
                }

                //Fetch again for the record:
                $children_after = $this->Database_model->tr_fetch(array(
                    'tr_in_parent_id' => intval($_POST['in_id']),
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                    'tr_status >=' => 0,
                    'in_status >=' => 0,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

                //Log transaction:
                $this->Database_model->tr_create(array(
                    'tr_en_credit_id' => $udata['en_id'],
                    'tr_content' => 'Sorted child intents for [' . $parent_intents[0]['in_outcome'] . ']',
                    'tr_metadata' => array(
                        'input_data' => $_POST,
                        'before' => $children_before,
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
        //Used to load all the help messages within the matrix:
        if (!$udata || !isset($_POST['intent_id']) || intval($_POST['intent_id']) < 1) {
            return echo_json(array(
                'success' => 0,
            ));
        }

        //Fetch Messages and the User's Got It Engagement History:
        $messages = $this->Database_model->i_fetch(array(
            'tr_in_child_id' => intval($_POST['intent_id']),
            'tr_status >' => 0, //Published in any form
        ));
        if (count($messages) == 0) {
            return echo_json(array(
                'success' => 0,
            ));
        }

        $help_content = null;
        foreach ($messages as $i) {

            //Log an engagement for all messages
            $this->Database_model->tr_create(array(
                'tr_en_credit_id' => $udata['en_id'],
                'tr_metadata' => $i,
                'tr_en_type_id' => 4273, //Got It
                'tr_in_child_id' => intval($_POST['intent_id']),
                'e_tr_id' => $i['tr_id'],
            ));

            //Build UI friendly HTML Message:
            $help_content .= echo_i(array_merge($i, array('tr_en_child_id' => $udata['en_id'])), $udata['en_name'], false);
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
        $this->load->view('shared/matrix_header', array(
            'title' => 'Intent #' . $in_id . ' Messages',
        ));
        $this->load->view('intents/frame_messages', array(
            'in_id' => $in_id,
        ));
        $this->load->view('shared/matrix_footer');
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
        } elseif (!isset($_POST['in_id']) || !isset($_POST['tr_status'])) {
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


        $url_create = $this->Database_model->x_sync($new_file_url, 1326, 1, 1);

        //Did we have an error?
        if (!$url_create['status']) {
            //Oops something went wrong:
            return echo_json(array(
                'status' => 0,
                'message' => 'Failed to create internal URL from cloud URL',
            ));
        }


        //Create message:
        $i = $this->Database_model->tr_create(array(
            'tr_en_credit_id' => $udata['en_id'],
            'tr_en_type_id' => $_POST['tr_status'], //TODO What type of message is this? find its entity ID
            'tr_en_parent_id' => $url_create['en']['en_id'],
            'tr_in_child_id' => intval($_POST['in_id']),
            'tr_content' => '@' . $url_create['en']['en_id'], //Just place the reference inside the message content
            'tr_order' => 1 + $this->Database_model->tr_max_order(array(
                'tr_status' => $_POST['tr_status'],
                'tr_in_child_id' => $_POST['in_id'],
            )),
        ));


        //Update intent count & tree:
        $this->db->query("UPDATE tb_intents SET in__messages_count=in__messages_count+1 WHERE in_id=" . intval($_POST['in_id']));
        $updated_recursively = $this->Database_model->metadata_tree_update('in', intval($_POST['in_id']), array(
            'in__messages_tree_count' => 1,
        ));


        //Fetch full message:
        $new_messages = $this->Database_model->i_fetch(array(
            'tr_id' => $i['tr_id'],
        ));


        //Echo message:
        echo_json(array(
            'status' => 1,
            'message' => echo_message(array_merge($new_messages[0], array(
                'tr_en_child_id' => $udata['en_id'],
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
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Fetch/Validate the intent:
        $intents = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status >=' => 0,
        ));
        if(count($intents)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent',
            ));
        }

        //Make sure message is all good:
        $validation = message_validation($_POST['tr_content']);
        if (!$validation['status']) {
            //There was some sort of an error:
            return echo_json($validation);
        }

        //Create Message Transaction:
        $tr = $this->Database_model->tr_create(array(
            'tr_en_credit_id' => $udata['en_id'],
            'tr_in_child_id' => intval($_POST['in_id']),
            'tr_order' => 1 + $this->Database_model->tr_max_order(array(
                'tr_status >=' => 0, //New+
                'tr_en_type_id' => 123, //TODO Put message type
                'tr_in_child_id' => intval($_POST['in_id']),
            )),
            //Referencing attributes:
            'tr_content' => $validation['tr_content'],
            'tr_en_type_id' => 123, //TODO Put message type
            'tr_en_parent_id' => $validation['tr_en_parent_id'],
        ), true);

        //Do a relative adjustment for this intent's metadata
        $this->Database_model->metadata_update('in', $intents[0], array(
            'in__messages_count' => 1, //Add one to existing value
        ), false);

        //Update tree as well:
        $updated_recursively = $this->Database_model->metadata_tree_update('in', $intents[0]['in_id'], array(
            'in__messages_tree_count' => 1,
        ));

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_message(array_merge($tr, array(
                'tr_en_child_id' => $udata['en_id'],
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
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Transaction ID',
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
        $messages = $this->Database_model->i_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
            'tr_status >=' => 0,
        ));
        if (count($messages) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }

        //Make sure message is all good:
        $validation = message_validation($_POST['tr_content']);

        if (!$validation['status']) {
            //There was some sort of an error:
            return echo_json($validation);
        }


        //All good, lets move on:
        //Define what needs to be updated:
        $to_update = array(
            'tr_content' => $validation['tr_content'],
            'tr_en_parent_id' => $validation['tr_en_parent_id'],
        );


        if (!($_POST['initial_tr_en_type_id'] == $_POST['tr_status'])) {
            //Change the status:
            $to_update['tr_status'] = $_POST['tr_status'];
            //Put it at the end of the new list:
            $to_update['tr_order'] = 1 + $this->Database_model->tr_max_order(array(
                'tr_status' => $_POST['tr_status'],
                'tr_in_child_id' => intval($_POST['in_id']),
            ));
        }

        //Now update the DB:
        $this->Database_model->tr_update(intval($_POST['tr_id']), $to_update, $udata['en_id']);

        //Re-fetch the message for display purposes:
        $new_messages = $this->Database_model->tr_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
        ));

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_i(array_merge($new_messages[0], array('tr_en_child_id' => $udata['en_id'])), $udata['en_name']),
            'tr_en_type_id' => echo_status('en_all_4485', $new_messages[0]['tr_en_type_id'], 1, 'right'),
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
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) <= 0) {
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
            $messages = $this->Database_model->i_fetch(array(
                'tr_id' => intval($_POST['tr_id']),
                'tr_status >=' => 0, //Not Removed
            ));
            if (!isset($messages[0])) {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Message Not Found',
                ));
            } else {

                //Now update the DB:
                $this->Database_model->tr_update(intval($_POST['tr_id']), array(
                    'tr_status' => -1, //Removed
                ), $udata['en_id']);

                //Update intent count:
                $this->db->query("UPDATE tb_intents SET in__messages_count=in__messages_count-1 WHERE in_id=" . intval($_POST['in_id']));

                //Update tree:
                $updated_recursively = $this->Database_model->metadata_tree_update('in', intval($_POST['in_id']), array(
                    'in__messages_tree_count' => -1,
                ));

                echo_json(array(
                    'status' => 1,
                    'message' => '<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>',
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
            foreach ($_POST['new_sort'] as $tr_order => $tr_id) {
                if (intval($tr_id) > 0) {
                    $sort_count++;
                    $this->Database_model->tr_update($tr_id, array(
                        'tr_order' => intval($tr_order),
                    ), $udata['en_id']);
                }
            }

            echo_json(array(
                'status' => 1,
                'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
            ));
        }
    }

}