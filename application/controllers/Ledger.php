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
        echo 'List all transactions';
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
        $udata = auth(array(1308));

        if (!$udata) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));

        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Transaction ID',
            ));

        } elseif (!isset($_POST['tr_status_new'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing New Transaction Status ID',
            ));

        }


        //Fetch Transaction (and its child intents) in case this was a message transaction...
        $trs = $this->Database_model->tr_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
        ), array('in_child'));

        if (!isset($trs[0])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Transaction Not Found',
            ));

        } elseif (intval($trs[0]['tr_status']) == intval($_POST['tr_status_new'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Transaction Status is already set to [' . $_POST['tr_status_new'] . ']',
            ));

        }


        //Now update the DB while giving credit to the Miner:
        $this->Database_model->tr_update($trs[0]['tr_id'], array(
            'tr_status' => intval($_POST['tr_status_new']),
        ), $udata['en_id']);



        /*
         * Custom Adjustments:
         *
         * We might need to do additional adjustments depending
         * on what type of transaction this is...
         * Is this an intent message being deleted?
         *
         * */
        if (in_array($trs[0]['tr_en_type_id'], $this->config->item('en_ids_4485')) && $trs[0]['tr_in_child_id'] > 0) {

            //Yes! We need to adjust the intent cache

            //Do a relative adjustment for this intent's metadata
            $this->Database_model->metadata_update('in', $trs[0], array(
                'in__message_count' => -1, //Remove 1 from existing value
            ), false);

            //Update intent tree:
            $this->Database_model->metadata_tree_update('in', $trs[0]['tr_in_child_id'], array(
                'in__message_tree_count' => -1,
            ));

        }

        //Return success:
        echo_json(array(
            'status' => 1,
            'message' => '<span style="color:#2f2739;"><i class="fas fa-trash-alt"></i> Removed</span>',
        ));

    }


    function fn___tr_order_update()
    {

        //Authenticate Miner:
        $udata = auth(array(1308));
        if (!$udata) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));

        } elseif (!isset($_POST['new_tr_orders']) || !is_array($_POST['new_tr_orders']) || count($_POST['new_tr_orders']) < 1) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return echo_json(array(
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
                $this->Database_model->tr_update($tr_id, array(
                    'tr_order' => intval($tr_order),
                ), $udata['en_id']);
            }
        }

        //Return success:
        return echo_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }



    function fn___tr_create()
    {

        //Authenticate Miner:
        $udata = auth(array(1308));

        if (!$udata) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and Try again.',
            ));

        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));

        }

        //Fetch/Validate the intent:
        $intents = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status >=' => 0, //New+
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
            'in__message_count' => 1, //Add 1 to existing value
        ), false);

        //Update tree as well:
        $this->Database_model->metadata_tree_update('in', $intents[0]['in_id'], array(
            'in__message_tree_count' => 1,
        ));

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => fn___echo_message_matrix(array_merge($tr, array(
                'tr_en_child_id' => $udata['en_id'],
            ))),
        ));
    }

}