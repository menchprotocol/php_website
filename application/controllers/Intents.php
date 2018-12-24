<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intents extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    function fn___in_miner_ui($in_id)
    {

        /*
         *
         * Main intent view that Miners use to manage the
         * intent networks.
         *
         * */

        //Authenticate Miner, redirect if failed:
        $udata = fn___en_auth(array(1308), true);

        //Fetch intent with 2 levels of children:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status >=' => 0, //New+ (Since Miners are moderating it)
        ), array('in__parents','in__grandchildren'));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return fn___redirect_message('/intents/' . $this->config->item('in_primary_id'), '<div class="alert alert-danger" role="alert">Intent ID [' . $in_id . '] not found</div>');
        }

        //Load views:
        $this->load->view('view_shared/matrix_header', array( 'title' => $ins[0]['in_outcome'] ));
        $this->load->view('view_intents/in_miner_ui', array( 'in' => $ins[0] ));
        $this->load->view('view_shared/matrix_footer');

    }


    function fn___in_orphans()
    {

        /*
         *
         * Lists all orphan intents (without a parent intent)
         * so Miners can review and organize them accordingly.
         *
         * */

        //Authenticate Miner, redirect if failed:
        $udata = fn___en_auth(array(1308), true);

        //Load views:
        $this->load->view('view_shared/matrix_header', array( 'title' => 'Orphan Intents' ));
        $this->load->view('view_intents/in_miner_ui', array(
            //Passing this will load the orphans instead of the regular intent tree view:
            'orphan_ins' => $this->Database_model->in_fetch(array(
                'NOT EXISTS (SELECT 1 FROM table_ledger WHERE in_id=tr_in_child_id AND tr_status>=0)' => null,
            )),
        ));
        $this->load->view('view_shared/matrix_footer');

    }


    function fn___in_public_ui($in_id)
    {

        /*
         *
         * Loads public landing page that Masters can use
         * to review intents before adding to Action Plan
         *
         * */

        //Fetch data:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status >=' => 2, //Published+ (Since it's a public landing page)
        ), array('in__parents', 'in__grandchildren'));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return fn___redirect_message('/' . $this->config->item('in_primary_id'), '<div class="alert alert-danger" role="alert">Intent ID [' . $in_id . '] not found</div>');
        }

        //Load home page:
        $this->load->view('view_shared/public_header', array( 'title' => $ins[0]['in_outcome'] ));
        $this->load->view('view_intents/in_public_ui', array( 'in' => $ins[0] ));
        $this->load->view('view_shared/public_footer');

    }


    function fn___in_create_or_link()
    {

        /*
         *
         * Either creates an intent link between in_parent_id & in_link_child_id
         * OR will create a new intent with outcome in_outcome and then link it
         * to in_parent_id (In this case in_link_child_id=0)
         *
         * */

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));
        if (!$udata) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue',
            ));
        } elseif (!isset($_POST['in_parent_id']) || intval($_POST['in_parent_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Parent Intent ID',
            ));
        } elseif (!isset($_POST['next_level']) || !in_array(intval($_POST['next_level']), array(2,3))) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent Level',
            ));
        } elseif (!isset($_POST['in_outcome']) || !isset($_POST['in_link_child_id']) || ( strlen($_POST['in_outcome']) < 1 && intval($_POST['in_link_child_id']) < 1)) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing either Intent Outcome OR Child Intent ID',
            ));
        }

        //All seems good, go ahead and try creating the intent:
        return fn___echo_json($this->Matrix_model->fn___in_create_or_link($_POST['in_parent_id'], $_POST['in_outcome'], $_POST['in_link_child_id'], $_POST['next_level'], $udata['en_id']));

    }




    function c_move_c()
    {

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));
        if (!$udata) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid tr_id',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['from_in_id']) || intval($_POST['from_in_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing from_in_id',
            ));
        } elseif (!isset($_POST['to_in_id']) || intval($_POST['to_in_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing to_in_id',
            ));
        }


        //Fetch all three intents to ensure they are all valid and use them for transaction logging:
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
            return fn___echo_json(array(
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
            'in__message_tree_count' => -($subject[0]['in__message_tree_count']),
        ));
        $updated_to_recursively = $this->Database_model->metadata_tree_update('in', $to[0]['in_id'], array(
            'in__tree_in_count' => +($subject[0]['in__tree_in_count']),
            'in__tree_max_seconds' => +(intval($subject[0]['in__tree_max_seconds'])),
            'in__message_tree_count' => +($subject[0]['in__message_tree_count']),
        ));

        //Return success
        fn___echo_json(array(
            'status' => 1,
            'message' => 'Move completed',
        ));
    }

    function c_save_settings()
    {

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));

        //Validate Original intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));

        if (!$udata) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        } elseif (!isset($_POST['level']) || intval($_POST['level']) < 0) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing level',
            ));
        } elseif (!isset($_POST['in_outcome']) || strlen($_POST['in_outcome']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent',
            ));
        } elseif (!isset($_POST['in_seconds'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Time Estimate',
            ));
        } elseif (intval($_POST['in_seconds']) > $this->config->item('in_seconds_max')) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Maximum estimated time is ' . round(($this->config->item('in_seconds_max') / 3600), 2) . ' hours for each intent. If larger, break the intent down into smaller intents.',
            ));
        } elseif (!isset($_POST['apply_recurively'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Recursive setting',
            ));
        } elseif (!isset($_POST['in_usd'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Cost Estimate',
            ));
        } elseif (!isset($_POST['in_status'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Status',
            ));
        } elseif (!isset($_POST['in_points'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Points',
            ));
        } elseif (!isset($_POST['in_alternatives'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Trigger Statements',
            ));
        } elseif (intval($_POST['in_usd']) < 0 || intval($_POST['in_usd']) > 300) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Cost estimate must be $0-5000 USD',
            ));
        } elseif (!isset($_POST['in_is_any'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Completion Settings',
            ));
        } elseif (count($ins) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Invalid in_id',
            ));
        }

        //Update array:
        $c_update = array(
            'in_outcome' => trim($_POST['in_outcome']),
            //These are also in the recursive adjustment array as they affect metadata
            'in_usd' => doubleval($_POST['in_usd']),
            'in_seconds' => intval($_POST['in_seconds']),
            'in_is_any' => intval($_POST['in_is_any']),
            'in_status' => intval($_POST['in_status']),
            'in_points' => intval($_POST['in_points']),
            'in_alternatives' => trim($_POST['in_alternatives']),
        );


        //This determines if there are any recursive updates needed on the tree:
        $in_metadata_modify = array();


        //Check to see which variables actually changed:
        foreach ($c_update as $key => $value) {

            //Did this value change?
            if ($_POST[$key] == $ins[0][$key]) {

                //No it did not! Remove it!
                unset($c_update[$key]);

            } else {

                //Something was updated!
                //Does it required a recursive upward update on the tree?
                if ($key == 'in_seconds') {
                    $in_metadata_modify['in__tree_max_seconds'] = intval($_POST[$key]) - intval($ins[0][$key]);
                }

            }
        }


        //Did anything change?
        if (count($c_update) > 0) {

            //YES, update the DB:
            $this->Database_model->in_update($_POST['in_id'], $c_update, $udata['en_id']);

            //Any recursive updates needed?
            if (count($in_metadata_modify) > 0) {
                $this->Database_model->metadata_tree_update('in', $_POST['in_id'], $in_metadata_modify);
            }

            //Any recursive down status sync requests?
            $children_updated = 0;
            if (intval($_POST['apply_recurively']) && !(intval($_POST['in_status']) == intval($ins[0]['in_status']))) {

                //Yes, sync downwards where current statuses match:
                $children = $this->Database_model->in_recursive_fetch(intval($_POST['in_id']), true);

                //Fetch all intents that match parent intent status:
                $child_ins = $this->Database_model->in_fetch(array(
                    'in_id IN ('.join(',' , $children['in_flat_tree']).')' => null,
                    'in_status' => intval($ins[0]['in_status']),
                ));

                foreach ($child_ins as $child_in) {
                    //Update this intent as the status did match:
                    $children_updated += $this->Database_model->in_update($child_in['in_id'], array('in_status' => intval($_POST['in_status'])), $udata['en_id']);
                }
            }
        }

        //Show success:
        return fn___echo_json(array(
            'status' => 1,
            'children_updated' => $children_updated,
            'in__tree_in_count' => -($ins[0]['in__tree_in_count']),
            'message' => '<span><i class="fas fa-check"></i> Saved' . ($children_updated > 0 ? ' & ' . $children_updated . ' Recursive Updates' : '') . '</span>',
            'status_c_ui' => fn___echo_status('in_status', $_POST['in_status'], true, 'left'),
            'status_cr_ui' => fn___echo_status('tr_status', $_POST['tr_status'], true, 'left'),
        ));

    }


    function in_sort_save()
    {

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));
        if (!$udata) {
            fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['new_tr_orders']) || !is_array($_POST['new_tr_orders']) || count($_POST['new_tr_orders']) < 1) {
            fn___echo_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Parent intent:
            $parent_ins = $this->Database_model->in_fetch(array(
                'in_id' => intval($_POST['in_id']),
            ));
            if (count($parent_ins) < 1) {
                fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid in_id',
                ));
            } else {

                //Fetch for the record:
                $children_before = $this->Database_model->tr_fetch(array(
                    'tr_in_parent_id' => intval($_POST['in_id']),
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_status >=' => 0,
                    'in_status >=' => 0,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

                //Update them all:
                foreach ($_POST['new_tr_orders'] as $rank => $tr_id) {
                    $this->Database_model->tr_update(intval($tr_id), array(
                        'tr_order' => intval($rank),
                    ), $udata['en_id']);
                }

                //Fetch again for the record:
                $children_after = $this->Database_model->tr_fetch(array(
                    'tr_in_parent_id' => intval($_POST['in_id']),
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_status >=' => 0,
                    'in_status >=' => 0,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

                //Log transaction:
                $this->Database_model->tr_create(array(
                    'tr_en_credit_id' => $udata['en_id'],
                    'tr_content' => 'Sorted child intents for [' . $parent_ins[0]['in_outcome'] . ']',
                    'tr_metadata' => array(
                        'input_data' => $_POST,
                        'before' => $children_before,
                        'after' => $children_after,
                    ),
                    'tr_en_type_id' => 4262, //Links Sorted
                    'tr_in_child_id' => intval($_POST['in_id']),
                ));

                //Display message:
                fn___echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-check"></i> Sorted',
                ));
            }
        }
    }

    function fn___in_matrix_tips()
    {

        /*
         *
         * A function to display Matrix Tips to give Miners
         * more information on each field and their use-case.
         *
         * */

        //Validate Miner:
        $udata = fn___en_auth(array(1308));
        if (!$udata) {
            return fn___echo_json(array(
                'success' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return fn___echo_json(array(
                'success' => 0,
                'message' => 'Missing Intent ID',
            ));
        }

        //Fetch On-Start Messages for this intent:
        $on_start_messages = $this->Database_model->tr_fetch(array(
            'tr_status >=' => 2, //Published+
            'tr_en_type_id' => 4231, //On-Start Messages
            'tr_in_child_id' => intval($_POST['in_id']),
        ), array(), 0, 0, array('tr_order' => 'ASC'));

        if (count($on_start_messages) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Intent Missing On-Start Messages',
            ));
        }

        $tip_messages = null;
        foreach ($on_start_messages as $tr) {

            //Log transaction for all messages
            $this->Database_model->tr_create(array(
                'tr_en_credit_id' => $udata['en_id'],
                'tr_en_child_id' => $udata['en_id'],
                'tr_en_type_id' => 4273, //Matrix Tip Read
                'tr_in_child_id' => intval($_POST['in_id']),
                'tr_tr_parent_id' => $tr['tr_id'],
                'tr_metadata' => $tr, //A copy of the message
            ));

            //Build UI friendly HTML Message:
            $tip_messages .= echo_message_body(array_merge($tr, array('tr_en_child_id' => $udata['en_id'])), $udata['en_name'], false);
        }

        //Return results:
        return fn___echo_json(array(
            'status' => 1,
            'in_id' => intval($_POST['in_id']),
            'tip_messages' => $tip_messages,
        ));
    }


    function in_actionplans_load($in_id)
    {

        //Auth user and check required variables:
        $udata = fn___en_auth(array(1308)); //miners

        if (!$udata) {
            die('<div class="alert alert-danger" role="alert">Session Expired</div>');
        } elseif (intval($in_id) < 1) {
            die('<div class="alert alert-danger" role="alert">Missing Intent ID [' . $in_id . ']</div>');
        }

        //Load view for this iFrame:
        $this->load->view('view_shared/messenger_header', array(
            'title' => 'User Transactions',
        ));
        $this->load->view('view_ledger/tr_intent_history', array(
            'in_id' => $in_id,
        ));
        $this->load->view('view_shared/messenger_footer');

    }


    function in_tr_load($in_id)
    {

        //Auth user and check required variables:
        $udata = fn___en_auth(array(1308)); //miners

        if (!$udata) {
            die('<div class="alert alert-danger" role="alert">Session Expired</div>');
        } elseif (intval($in_id) < 1) {
            die('<div class="alert alert-danger" role="alert">Missing Intent ID</div>');
        }

        //Load view for this iFrame:
        $this->load->view('view_shared/messenger_header', array(
            'title' => 'User Transactions',
        ));
        $this->load->view('view_ledger/in_tr_load', array(
            'in_id' => $in_id,
        ));
        $this->load->view('view_shared/messenger_footer');
    }



    function in_messages_load($in_id)
    {

        //Authenticate as a Miner:
        $udata = fn___en_auth(array(1308));
        if (!$udata) {
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Login again to continue.</span>');
        } elseif (intval($in_id) < 1) {
            die('<span style="color:#FF0000;">Error: Invalid Intent id.</span>');
        }

        //Don't show the heading here as we're loading inside an iframe:
        $_GET['skip_header'] = true;

        //Load view:
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Intent #' . $in_id . ' Messages',
        ));
        $this->load->view('view_intents/in_message_iframe_ui', array(
            'in_id' => $in_id,
        ));
        $this->load->view('view_shared/matrix_footer');

    }

    function fn___in_new_message_from_attachment()
    {

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));
        if (!$udata) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh to Continue',
            ));
        } elseif (!isset($_POST['in_id']) || !isset($_POST['focus_tr_en_type_id'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing intent data.',
            ));
        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));
        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to save file.',
            ));
        } elseif ($_FILES[$_POST['upload_type']]['size'] > ($this->config->item('file_size_max') * 1024 * 1024)) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . $this->config->item('file_size_max') . ' MB.',
            ));
        }

        //Validate Intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status >=' => 0,
        ));
        if(count($ins)<1){
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }


        //Attempt to save file locally:
        $file_parts = explode('.', $_FILES[$_POST['upload_type']]["name"]);
        $temp_local = "application/cache/temp_files/" . md5($file_parts[0] . $_FILES[$_POST['upload_type']]["type"] . $_FILES[$_POST['upload_type']]["size"]) . '.' . $file_parts[(count($file_parts) - 1)];
        move_uploaded_file($_FILES[$_POST['upload_type']]['tmp_name'], $temp_local);


        //Attempt to store in Mench Cloud on Amazon S3:
        if (isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type']) > 0) {
            $mime = $_FILES[$_POST['upload_type']]['type'];
        } else {
            $mime = mime_content_type($temp_local);
        }

        $new_file_url = trim(fn___upload_to_cdn($temp_local, $_FILES[$_POST['upload_type']], true));

        //What happened?
        if (!$new_file_url) {
            //Oops something went wrong:
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Failed to save file to Mench cloud',
            ));
        }

        //Now save URL as a new entity:
        $created_url = $this->Matrix_model->fn___create_en_from_url($new_file_url);

        //Did we have an error?
        if (!$created_url['status']) {
            //Oops something went wrong, return error:
            return $created_url;
        }


        //Create message:
        $tr = $this->Database_model->tr_create(array(
            'tr_en_credit_id' => $udata['en_id'],
            'tr_en_type_id' => $_POST['focus_tr_en_type_id'],
            'tr_en_parent_id' => $created_url['en_from_url']['en_id'],
            'tr_in_child_id' => intval($_POST['in_id']),
            'tr_content' => '@' . $created_url['en_from_url']['en_id'], //Just place the entity reference as the entire message
            'tr_order' => 1 + $this->Database_model->tr_max_order(array(
                'tr_en_type_id' => $_POST['focus_tr_en_type_id'],
                'tr_in_child_id' => $_POST['in_id'],
            )),
        ));


        //Update intent count & tree:
        //Do a relative adjustment for this intent's metadata
        $this->Database_model->metadata_update('in', $ins[0], array(
            'in__message_count' => 1, //Add 1 to existing value
        ), false);

        $this->Database_model->metadata_tree_update('in', $ins[0]['in_id'], array(
            'in__message_tree_count' => 1,
        ));


        //Fetch full message for proper UI display:
        $new_messages = $this->Database_model->tr_fetch(array(
            'tr_id' => $tr['tr_id'],
        ));

        //Echo message:
        fn___echo_json(array(
            'status' => 1,
            'message' => fn___echo_in_message_manage(array_merge($new_messages[0], array(
                'tr_en_child_id' => $udata['en_id'],
            ))),
        ));
    }


    function fn___in_check_requirements()
    {

        /*
         *
         * An AJAX function that is triggered every time a Miner
         * selects to modify an intent. It will check the
         * completion requirements of an intent so it can
         * check proper boxes to help Miner modify the intent.
         *
         * */

        $udata = fn___en_auth(array(1308));
        if (!$udata) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh.',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            ));
        }

        //Fetch intent requirements:
        $completion_requirements = $this->Database_model->tr_fetch(array(
            'tr_en_type_id' => 4331, //Intent Response Limiters
            'tr_in_child_id' => $_POST['in_id'], //For this intent
            'tr_status >=' => 2, //Published+
            'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4331')) . ')' => null, //The Requirement
        ));

        $requirement_array = array();
        foreach($completion_requirements as $tr){
            array_push($requirement_array, intval($tr['tr_en_parent_id']));
        }

        //Return results:
        return fn___echo_json(array(
            'status' => 1,
            'requirement_array' => $requirement_array,
        ));

    }


    function fn___in_message_modify()
    {

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));
        if (!$udata) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh.',
            ));
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Transaction ID',
            ));
        } elseif (!isset($_POST['tr_content'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Message',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Validate Intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status >=' => 0, //New+
        ));
        if (count($ins) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Intent Not Found',
            ));
        }

        //Fetch this specific Message:
        $messages = $this->Database_model->tr_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
            'tr_status >=' => 0,
        ));
        if (count($messages) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }

        //Make sure message is all good:
        $validation = fn___validate_message($_POST['tr_content']);

        if (!$validation['status']) {
            //There was some sort of an error:
            return fn___echo_json($validation);
        }


        //All good, lets move on:
        //Define what needs to be updated:
        $to_update = array(
            'tr_content' => $validation['tr_content'],
            'tr_en_parent_id' => $validation['tr_en_parent_id'],
        );


        if (!($_POST['initial_tr_en_type_id'] == $_POST['focus_tr_en_type_id'])) {
            //Change the status:
            $to_update['tr_en_type_id'] = $_POST['focus_tr_en_type_id'];
            //Put it at the end of the new list:
            $to_update['tr_order'] = 1 + $this->Database_model->tr_max_order(array(
                'tr_en_type_id' => $_POST['focus_tr_en_type_id'],
                'tr_in_child_id' => intval($_POST['in_id']),
            ));
        }

        //Now update the DB:
        $this->Database_model->tr_update(intval($_POST['tr_id']), $to_update, $udata['en_id']);

        //Re-fetch the message for display purposes:
        $new_messages = $this->Database_model->tr_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
        ));

        $en_all_4485 = $this->config->item('en_all_4485');

        //Print the challenge:
        return fn___echo_json(array(
            'status' => 1,
            'message' => echo_message_body(array_merge($new_messages[0], array('tr_en_child_id' => $udata['en_id'])), $udata['en_name']),
            'tr_en_type_id' => $en_all_4485[$new_messages[0]['tr_en_type_id']]['en_icon'].' '.$en_all_4485[$new_messages[0]['tr_en_type_id']]['en_name'],
            'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));
    }

}