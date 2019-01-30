<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }


    function index()
    {

        /*
         *
         * List all Transactions on reverse chronological order
         *
         * */

        $udata = fn___en_auth(null, true); //Just be logged in to browse

        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Ledger Transactions',
        ));
        $this->load->view('view_ledger/ledger_ui');
        $this->load->view('view_shared/matrix_footer');

    }


    function fn___tr_status_update()
    {

        /*
         *
         * A function to adjust a transaction statuses
         * This function will also make additional adjusttments
         * based on transaction type. See the "custom adjustments"
         * section below for more details.
         *
         * */

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));

        if (!$udata) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));

        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Transaction ID',
            ));

        } elseif (!isset($_POST['tr_status_new'])) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing New Transaction Status ID',
            ));

        }


        //Fetch/validate Transaction:
        $trs = $this->Database_model->fn___tr_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
        ));

        if (!isset($trs[0])) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Transaction Not Found',
            ));

        } elseif (intval($trs[0]['tr_status']) == intval($_POST['tr_status_new'])) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Transaction Status is already set to [' . $_POST['tr_status_new'] . ']',
            ));

        }



        /*
         *
         * Custom Adjustments might be needed
         *
         * We might need to do additional adjustments
         * BEFORE adjusting link status depending
         * on what type of transaction this is...
         *
         * */

        if ($_POST['tr_status_new'] < 0 && in_array($trs[0]['tr_type_en_id'], $this->config->item('en_ids_4485'))) {

            //Intent message being deleted..

            //Fetch child intent:
            $ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => $trs[0]['tr_in_child_id'],
            ));

            //Do a relative adjustment for this intent's metadata
            $this->Matrix_model->fn___metadata_update('in', $ins[0], array(
                'in__message_count' => -1, //Remove 1 from existing value
            ), false);

            //Update intent tree:
            $this->Matrix_model->fn___metadata_tree_update('in', $ins[0]['in_id'], array(
                'in__message_tree_count' => -1,
            ));

        } elseif($_POST['tr_status_new'] < 0 && in_array($trs[0]['tr_type_en_id'], $this->config->item('en_ids_4486'))) {

            //Intent link being deleted...

            //Fetch child intent metadata:
            $ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => $trs[0]['tr_in_child_id'],
            ));

            //Prep metadata:
            $metadata = unserialize($trs[0]['in_metadata']);

            //Update parent intent tree (and upwards) to reduce totals based on child intent metadata:
            $this->Matrix_model->fn___metadata_tree_update('in', $trs[0]['tr_in_parent_id'], array(
                'in__tree_in_active_count' => -( isset($metadata['in__tree_in_active_count']) ? $metadata['in__tree_in_active_count'] :0 ),
                'in__tree_max_seconds' => -( isset($metadata['in__tree_max_seconds']) ? $metadata['in__tree_max_seconds'] :0 ),
                'in__message_tree_count' => -( isset($metadata['in__message_tree_count']) ? $metadata['in__message_tree_count'] :0 ),
            ));

        }


        /*
         *
         * Adjust link status and give Miner credit:
         *
         * */
        $this->Database_model->fn___tr_update($trs[0]['tr_id'], array(
            'tr_status' => intval($_POST['tr_status_new']),
        ), $udata['en_id']);




        //Return success:
        fn___echo_json(array(
            'status' => 1,
            'message' => '<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>',
        ));

    }


    function fn___tr_order_update()
    {

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));
        if (!$udata) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));

        } elseif (!isset($_POST['new_tr_orders']) || !is_array($_POST['new_tr_orders']) || count($_POST['new_tr_orders']) < 1) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return fn___echo_json(array(
                'status' => 1,
                'message' => 'There was nothing to sort',
            ));

        }

        //Update all transaction orders:
        $sort_count = 0;
        foreach ($_POST['new_tr_orders'] as $tr_order => $tr_id) {
            if (intval($tr_id) > 0) {
                $sort_count++;
                //Log update and give credit to the session Miner:
                $this->Database_model->fn___tr_update($tr_id, array(
                    'tr_order' => intval($tr_order),
                ), $udata['en_id']);
            }
        }

        //Return success:
        return fn___echo_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }


    function fn___tr_json($tr_id)
    {
        //Authenticate miner access:
        $udata = fn___en_auth(array(1308), true);

        //Fetch transaction metadata and display it:
        $trs = $this->Database_model->fn___tr_fetch(array(
            'tr_id' => $tr_id,
        ));

        //Did we find it?
        if (count($trs) == 1) {
            //unserialize metadata first:
            $trs[0]['tr_metadata'] = unserialize($trs[0]['tr_metadata']);
            //Print on scree:
            fn___echo_json($trs[0]);
        } else {
            //Ooops
            fn___echo_json(array('error' => 'Not Found'));
        }
    }

    function fn___add_message()
    {

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));

        if (!$udata) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and Try again.',
            ));

        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));

        } elseif (!isset($_POST['focus_tr_type_en_id']) || intval($_POST['focus_tr_type_en_id']) < 1) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }



        //Fetch/Validate the intent:
        $ins = $this->Database_model->fn___in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status >=' => 0, //New+
        ));
        if(count($ins)<1){
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->Chat_model->fn___dispatch_validate_message($_POST['tr_content']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return fn___echo_json($msg_validation);
        }

        //Create Message Transaction:
        $tr = $this->Database_model->fn___tr_create(array(
            'tr_miner_en_id' => $udata['en_id'],
            'tr_in_child_id' => intval($_POST['in_id']),
            'tr_order' => 1 + $this->Database_model->fn___tr_max_order(array(
                    'tr_status >=' => 0, //New+
                    'tr_type_en_id' => intval($_POST['focus_tr_type_en_id']),
                    'tr_in_child_id' => intval($_POST['in_id']),
                )),
            //Referencing attributes:
            'tr_content' => $msg_validation['input_message'],
            'tr_type_en_id' => intval($_POST['focus_tr_type_en_id']),
            'tr_en_parent_id' => $msg_validation['tr_en_parent_id'],
        ), true);

        //Do a relative adjustment for this intent's metadata
        $this->Matrix_model->fn___metadata_update('in', $ins[0], array(
            'in__message_count' => 1, //Add 1 to existing value
        ), false);

        //Update tree as well:
        $this->Matrix_model->fn___metadata_tree_update('in', $ins[0]['in_id'], array(
            'in__message_tree_count' => 1,
        ));

        //Print the challenge:
        return fn___echo_json(array(
            'status' => 1,
            'message' => fn___echo_in_message_manage(array_merge($tr, array(
                'tr_en_child_id' => $udata['en_id'],
            ))),
        ));
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
            'url' => '/master/actionplan/' . $w['tr_id'] . '/' . $w['tr_in_child_id'],
        ));

    }

}