<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intents extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }



    //Loaded as default function of the default controller:
    function index()
    {

        $udata = $this->session->userdata('user');

        if (isset($udata['en__parents'][0]) && fn___filter_array($udata['en__parents'], 'en_id', 1308)) {

            //Lead miner and above, go to matrix:
            fn___redirect_message('/intents/' . $this->config->item('in_tactic_id'));

        } elseif (0 && (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'mench.co')) {

            //Show the Hiring Ad:
            fn___redirect_message('/8327?expand_mode=1');

        } else {

            //How many featured intents do we have?
            $featured_ins = $ins = $this->Database_model->fn___in_fetch(array(
                'in_status' => 3, //Featured Intents
            ));

            if (count($featured_ins) == 0) {

                //Go to default landing page:
                return fn___redirect_message('/' . $this->config->item('in_tactic_id'));

            } elseif (count($featured_ins) == 1) {

                //TO to single feature:
                return fn___redirect_message('/' . $featured_ins[0]['in_id']);

            } else {

                //We have more featured, list them so user can choose:
                //Show index page:
                $this->load->view('view_shared/public_header', array(
                    'title' => 'Advance Your Tech Career',
                ));
                $this->load->view('view_intents/in_home_featured_ui', array(
                    'featured_ins' => $featured_ins,
                ));
                $this->load->view('view_shared/public_footer');

            }
        }
    }


    function fn___in_miner_ui($in_id)
    {

        /*
         *
         * Main intent view that Miners use to manage the
         * intent networks.
         *
         * */

        if($in_id == 0){
            //Set to default:
            $in_id = $this->config->item('in_tactic_id');
        }

        //Authenticate Miner, redirect if failed:
        $udata = fn___en_auth(array(1308), true);

        //Fetch intent with 2 levels of children:
        $ins = $this->Database_model->fn___in_fetch(array(
            'in_id' => $in_id,
            'in_status >=' => 0, //New+ (Since Miners are moderating it)
        ), array('in__parents','in__grandchildren'));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return fn___redirect_message('/intents/' . $this->config->item('in_tactic_id'), '<div class="alert alert-danger" role="alert">Intent ID [' . $in_id . '] not found</div>');
        }

        //Load views:
        $this->load->view('view_shared/matrix_header', array( 'title' => $ins[0]['in_outcome'].' | Intents' ));
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
            'orphan_ins' => $this->Database_model->fn___in_fetch(array(
                'NOT EXISTS (SELECT 1 FROM table_ledger WHERE in_id=tr_in_child_id AND tr_status>=0)' => null,
            )),
        ));
        $this->load->view('view_shared/matrix_footer');

    }


    function fn___in_public_ui($in_id)
    {

        /*
         *
         * Loads public landing page that Students can use
         * to review intents before adding to Action Plan
         *
         * */

        //Fetch data:
        $ins = $this->Database_model->fn___in_fetch(array(
            'in_id' => $in_id,
            'in_status >=' => 2, //Published+ (Since it's a public landing page)
        ), array('in__parents', 'in__grandchildren'));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return fn___redirect_message('/' . $this->config->item('in_tactic_id'), '<div class="alert alert-danger" role="alert">Intent ID [' . $in_id . '] not found</div>');
        }

        //Load home page:
        $this->load->view('view_shared/public_header', array( 'title' => $ins[0]['in_outcome'] ));
        $this->load->view('view_intents/in_public_ui', array( 'in' => $ins[0] ));
        $this->load->view('view_shared/public_footer');

    }


    function fn___in_link_or_create()
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
        return fn___echo_json($this->Matrix_model->fn___in_link_or_create($_POST['in_parent_id'], $_POST['in_outcome'], $_POST['in_link_child_id'], $_POST['next_level'], $udata['en_id']));

    }






    function fn___in_migrate()
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
        $this_in = $this->Database_model->fn___in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status >=' => 0, //New+
        ));
        $from_in = $this->Database_model->fn___in_fetch(array(
            'in_id' => intval($_POST['from_in_id']),
            'in_status >=' => 0, //New+
        ));
        $to_in = $this->Database_model->fn___in_fetch(array(
            'in_id' => intval($_POST['to_in_id']),
            'in_status >=' => 0, //New+
        ));

        if (!isset($this_in[0]) || !isset($from_in[0]) || !isset($to_in[0])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid intent IDs',
            ));
        }

        $this_metadata = unserialize($this_in[0]['in_metadata']);

        //Make sure we have all metadata that is needed:
        if(!isset($this_metadata['in__tree_in_active_count'])){
            $this_metadata['in__tree_in_active_count'] = 1;
        }
        if(!isset($this_metadata['in__tree_max_seconds'])){
            $this_metadata['in__tree_max_seconds'] = 0;
        }
        if(!isset($this_metadata['in__message_tree_count'])){
            $this_metadata['in__message_tree_count'] = 0;
        }


        //Make the move:
        $this->Database_model->fn___tr_update(intval($_POST['tr_id']), array(
            'tr_in_parent_id' => $to_in[0]['in_id'],
        ), $udata['en_id']);


        //Adjust tree metadata on both branches that have been affected:
        $updated_from_recursively = $this->Matrix_model->fn___metadata_tree_update('in', $from_in[0]['in_id'], array(
            'in__tree_in_active_count' => -(intval($this_metadata['in__tree_in_active_count'])),
            'in__tree_max_seconds' => -(intval($this_metadata['in__tree_max_seconds'])),
            'in__message_tree_count' => -(intval($this_metadata['in__message_tree_count'])),
        ));
        $updated_to_recursively = $this->Matrix_model->fn___metadata_tree_update('in', $to_in[0]['in_id'], array(
            'in__tree_in_active_count' => +(intval($this_metadata['in__tree_in_active_count'])),
            'in__tree_max_seconds' => +(intval($this_metadata['in__tree_max_seconds'])),
            'in__message_tree_count' => +(intval($this_metadata['in__message_tree_count'])),
        ));

        //Return success
        fn___echo_json(array(
            'status' => 1,
        ));
    }

    function fn___in_save_settings()
    {

        //Authenticate Miner:
        $udata = fn___en_auth(array(1308));
        $tr_id = intval($_POST['tr_id']);

        //Validate intent:
        $ins = $this->Database_model->fn___in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status >=' => 0, //New+
        ));

        if(!isset($_POST['input_requirements'])){
            $_POST['input_requirements'] = array();
        }

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
        } elseif (!isset($_POST['in_webhook'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Input Webhook variable',
            ));
        } elseif (intval($_POST['level'])==1 && intval($_POST['tr_id'])>0) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Level 1 intent should not have a transaction',
            ));
        } elseif ( strlen($_POST['in_webhook']) > 0 && !filter_var($this->config->item('in_webhook_prefix').$_POST['in_webhook'], FILTER_VALIDATE_URL)) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Input Webhook URL',
            ));
        } elseif (!isset($_POST['tr__conditional_score_min']) || !isset($_POST['tr__conditional_score_max'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Score Min/Max Variables',
            ));
        } elseif (!isset($_POST['level']) || intval($_POST['level']) < 1 || intval($_POST['level']) > 3) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid level',
            ));
        } elseif (!isset($_POST['in_outcome']) || strlen($_POST['in_outcome']) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Outcome',
            ));
        } elseif (!isset($_POST['in_seconds']) || intval($_POST['in_seconds']) < 0) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Time Estimate',
            ));
        } elseif (intval($_POST['in_seconds']) > $this->config->item('in_seconds_max')) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Maximum estimated time is ' . round(($this->config->item('in_seconds_max') / 3600), 2) . ' hours for each intent. If larger, break the intent down into smaller intents.',
            ));
        } elseif (!isset($_POST['apply_recursively'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Recursive setting',
            ));
        } elseif (!isset($_POST['in_usd']) || doubleval($_POST['in_usd']) < 0) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Cost Estimate',
            ));
        } elseif (!isset($_POST['in_status'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent Status',
            ));
        } elseif (!isset($_POST['in_points'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Points',
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
                'message' => 'Intent Not Found',
            ));
        } elseif($tr_id > 0 && intval($_POST['tr_type_en_id']) == 4229){
            //Conditional links, we require range values:
            if(strlen($_POST['tr__conditional_score_min']) < 1 || !is_numeric($_POST['tr__conditional_score_min'])){
                return fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Missing MIN range, enter 0 or greater',
                ));
            } elseif(strlen($_POST['tr__conditional_score_max']) < 1 || !is_numeric($_POST['tr__conditional_score_max'])){
                return fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Missing MAX range, enter 0 or greater',
                ));
            } elseif(doubleval($_POST['tr__conditional_score_min']) > doubleval($_POST['tr__conditional_score_max'])){
                return fn___echo_json(array(
                    'status' => 0,
                    'message' => 'MIN range cannot be larger than MAX',
                ));
            }
        }








        //Fetch current ANY Conditional input limiters:
        $completion_requirements_modified = 0;
        $completion_requirements = $this->Database_model->fn___tr_fetch(array(
            'tr_type_en_id' => 4331, //Intent Completion Requirements
            'tr_in_child_id' => $_POST['in_id'], //For this intent
            'tr_status >=' => 0, //New+
            'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4331')) . ')' => null, //Technically not needed, but here for extra clarity
        ));

        //Save additional settings:
        if(intval($_POST['in_is_any'])){

            //Intent type is ANY AND intent has input requirements, which now need to be removed:
            foreach ($completion_requirements as $tr) {
                //Remove this link:
                $this->Database_model->fn___tr_update($tr['tr_id'], array(
                    'tr_status' => -1, //Removed
                ), $udata['en_id']);
                $completion_requirements_modified++;
            }

        } else {

            //Loop through current input limiters and see if we need to modify:
            $already_selected = array(); //Will build this with selected type IDs

            foreach ($completion_requirements as $tr) {

                //See if any of the existing links have been removed:
                if(!in_array($tr['tr_en_parent_id'], $_POST['input_requirements']) || !in_array($tr['tr_en_parent_id'], $this->config->item('en_ids_4331'))){

                    //Yes its removed OR it does not exist in the input array:
                    $this->Database_model->fn___tr_update($tr['tr_id'], array(
                        'tr_status' => -1, //Removed
                    ), $udata['en_id']);

                    $completion_requirements_modified++;

                } else {
                    //Push into array:
                    array_push($already_selected , $tr['tr_en_parent_id']);
                }
            }

            //Now see if any of the selected ones is missing and needed to be inserted:
            foreach ($_POST['input_requirements'] as $en_id) {

                //Double check:
                if(!in_array($en_id, $this->config->item('en_ids_4331'))){
                    return fn___echo_json(array(
                        'status' => 0,
                        'message' => 'Invalid Input Requirement',
                    ));
                }

                if(!in_array($en_id, $already_selected)){
                    //Need to create a new row for this:
                    $this->Database_model->fn___tr_create(array(
                        'tr_miner_en_id' => $udata['en_id'],
                        'tr_type_en_id' => 4331,
                        'tr_en_parent_id' => $en_id,
                        'tr_in_child_id' => intval($_POST['in_id']),
                    ));
                    $completion_requirements_modified++;
                }
            }
        }









        //Prep new variables:
        $in_update = array(
            'in_status' => intval($_POST['in_status']),
            'in_outcome' => trim($_POST['in_outcome']),
            'in_seconds' => intval($_POST['in_seconds']),
            'in_usd' => doubleval($_POST['in_usd']),
            'in_points' => intval($_POST['in_points']),
            'in_is_any' => intval($_POST['in_is_any']),
            'in_webhook' => trim($_POST['in_webhook']),
        );

        //Prep current intent metadata:
        $in_metadata = unserialize($ins[0]['in_metadata']);

        //Determines if Intent has been removed OR unlinked:
        $remove_from_ui = 0; //Assume not

        //This determines if there are any recursive updates needed on the tree:
        $in_metadata_modify = array();

        //Did anything change?
        $status_update_children = 0;

        //Check to see which variables actually changed:
        foreach ($in_update as $key => $value) {

            //Did this value change?
            if ($value == $ins[0][$key]) {

                //No it did not! Remove it!
                unset($in_update[$key]);

            } else {

                //Does it required a recursive tree update?
                if ($key == 'in_seconds') {

                    $in_metadata_modify['in__tree_min_seconds'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_min_seconds']) ? intval($in_metadata['in__tree_min_seconds']) : 0 );
                    $in_metadata_modify['in__tree_max_seconds'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_max_seconds']) ? intval($in_metadata['in__tree_max_seconds']) : 0 );

                } elseif ($key == 'in_usd') {

                    $in_metadata_modify['in__tree_min_cost'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_min_cost']) ? intval($in_metadata['in__tree_min_cost']) : 0 );
                    $in_metadata_modify['in__tree_max_cost'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_max_cost']) ? intval($in_metadata['in__tree_max_cost']) : 0 );

                } elseif ($key == 'in_points') {

                    $in_metadata_modify['in__tree_min_points'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_min_points']) ? intval($in_metadata['in__tree_min_points']) : 0 );
                    $in_metadata_modify['in__tree_max_points'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_max_points']) ? intval($in_metadata['in__tree_max_points']) : 0 );

                } elseif ($key == 'in_status') {

                    //Has intent been removed?
                    if($value < 0){

                        //Intent has been removed:
                        $remove_from_ui = 1;

                        //Also remove all children/parent links:
                        foreach($this->Database_model->fn___tr_fetch(array(
                            'tr_status >=' => 0, //New+
                            'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                            '(tr_in_child_id = '.$_POST['in_id'].' OR tr_in_parent_id = '.$_POST['in_id'].')' => null,
                        )) as $unlink_tr){

                            $this->Database_model->fn___tr_update($unlink_tr['tr_id'], array(
                                'tr_status' => -1, //Unlink
                            ), $udata['en_id']);

                        }

                        //Treat as if no link (Since it was removed):
                        $tr_id = 0;
                    }

                    if(intval($_POST['apply_recursively'])){
                        //Intent status has changed and there is a recursive update request:
                        //Yes, sync downwards where current statuses match:
                        $children = $this->Matrix_model->fn___in_recursive_fetch(intval($_POST['in_id']), true);

                        //Fetch all intents that match parent intent status:
                        $child_ins = $this->Database_model->fn___in_fetch(array(
                            'in_id IN ('.join(',' , $children['in_flat_tree']).')' => null,
                            'in_status' => intval($ins[0]['in_status']), //Same as status before update
                        ));

                        foreach ($child_ins as $child_in) {
                            //Update this intent as the status did match:
                            $status_update_children += $this->Database_model->fn___in_update($child_in['in_id'], array(
                                'in_status' => $in_update['in_status']
                            ), true, $udata['en_id']);
                        }
                    }
                }

                //This field has been updated, update one field at a time:
                $this->Database_model->fn___in_update($_POST['in_id'], array( $key => $_POST[$key] ), true, $udata['en_id']);

            }
        }

        //Any relative metadata upward recursive updates needed?
        if (count($in_metadata_modify) > 0) {
            $this->Matrix_model->fn___metadata_tree_update('in', $_POST['in_id'], $in_metadata_modify);
        }





        //Assume transaction is not updated:
        $transaction_was_updated = false;

        //Does this request has an intent transaction?
        if($tr_id > 0){


            //Validate Transaction and inputs:
            $trs = $this->Database_model->fn___tr_fetch(array(
                'tr_id' => $tr_id,
                'tr_status >=' => 0, //New+
            ));
            if(count($trs) < 1){
                return fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid link ID',
                ));
            }

            //Prep link Metadata to see if the conditional score variables have changed:
            $tr_update = array(
                'tr_type_en_id'     => intval($_POST['tr_type_en_id']),
                'tr_status'         => intval($_POST['tr_status']),
            );

            //Prep variables:
            $tr_metadata = ( strlen($trs[0]['tr_metadata']) > 0 ? unserialize($trs[0]['tr_metadata']) : array() );

            //Check to see if anything changed in the transaction?
            $transaction_meta_updated = ($tr_update['tr_type_en_id'] == 4229 && (
                    !isset($tr_metadata['tr__conditional_score_min']) ||
                    !isset($tr_metadata['tr__conditional_score_max']) ||
                    !(doubleval($tr_metadata['tr__conditional_score_max'])==doubleval($_POST['tr__conditional_score_max'])) ||
                    !(doubleval($tr_metadata['tr__conditional_score_min'])==doubleval($_POST['tr__conditional_score_min']))
                ));


            foreach ($tr_update as $key => $value) {

                //Did this value change?
                if ($value == $trs[0][$key]) {

                    //No it did not! Remove it!
                    unset($tr_update[$key]);

                } else {

                    if($key=='tr_status' && $value < 0){
                        $remove_from_ui = 1;
                    }

                }

            }

            //Was anything updated?
            if(count($tr_update) > 0 || $transaction_meta_updated){
                $transaction_was_updated = true;
            }



            //Did anything change?
            if( $transaction_was_updated ){

                if($transaction_meta_updated){
                    $tr_update['tr_metadata'] = array_merge( $tr_metadata, array(
                        'tr__conditional_score_min' => doubleval($_POST['tr__conditional_score_min']),
                        'tr__conditional_score_max' => doubleval($_POST['tr__conditional_score_max']),
                    ));
                }

                //Also update the timestamp & new miner:
                $tr_update['tr_timestamp'] = date("Y-m-d H:i:s");
                $tr_update['tr_miner_en_id'] = $udata['en_id'];

                //Update transactions:
                $this->Database_model->fn___tr_update($tr_id, $tr_update, $udata['en_id']);
            }

        }





        //Fetch latest intent update:
        $updated_trs = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'tr_type_en_id IN (4250, 4264)' => null, //Intent Created/Updated
            'tr_in_child_id' => $_POST['in_id'],
        ), array('en_miner'));


        $return_data = array(
            'status' => 1,
            'remove_from_ui' => $remove_from_ui,
            'status_update_children' => $status_update_children,
            'in__tree_in_active_count' => -( isset($in_metadata['in__tree_in_active_count']) ? $in_metadata['in__tree_in_active_count'] : 0 ),
            'in___last_updated' => fn___echo_last_updated('in', $updated_trs[0]),
        );


        //Did we have an intent link update? If so, update the last updated UI:
        if($transaction_was_updated){

            //Fetch last intent Ledger Transaction:
            $trs = $this->Database_model->fn___tr_fetch(array(
                'tr_id' => $tr_id,
            ), array('en_miner'));

            $return_data['tr___last_updated'] = fn___echo_last_updated('tr',$trs[0]);

        }

        //Show success:
        return fn___echo_json($return_data);

    }


    function fn___in_sort_save()
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
            $parent_ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => intval($_POST['in_id']),
            ));
            if (count($parent_ins) < 1) {
                fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid in_id',
                ));
            } else {

                //Fetch for the record:
                $children_before = $this->Database_model->fn___tr_fetch(array(
                    'tr_in_parent_id' => intval($_POST['in_id']),
                    'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_status >=' => 0,
                    'in_status >=' => 0,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

                //Update them all:
                foreach ($_POST['new_tr_orders'] as $rank => $tr_id) {
                    $this->Database_model->fn___tr_update(intval($tr_id), array(
                        'tr_order' => intval($rank),
                    ), $udata['en_id']);
                }

                //Fetch again for the record:
                $children_after = $this->Database_model->fn___tr_fetch(array(
                    'tr_in_parent_id' => intval($_POST['in_id']),
                    'tr_type_en_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_status >=' => 0,
                    'in_status >=' => 0,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

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
        $on_start_messages = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 2, //Published+
            'tr_type_en_id' => 4231, //On-Start Messages
            'tr_in_child_id' => $_POST['in_id'],
        ), array(), 0, 0, array('tr_order' => 'ASC'));

        if (count($on_start_messages) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Intent Missing On-Start Messages',
            ));
        }

        $_GET['log_miner_messages'] = 1; //Will log miner messages which normally do not get logged (so we prevent intent message editing logs)
        $tip_messages = null;
        foreach ($on_start_messages as $tr) {
            //What type of message is this?
            $tip_messages .= $this->Chat_model->fn___dispatch_message($tr['tr_content'], $udata, false, array(), array(
                'tr_in_parent_id' => $_POST['in_id'],
            ));
        }

        //Return results:
        return fn___echo_json(array(
            'status' => 1,
            'tip_messages' => $tip_messages,
        ));
    }


    function fn___in_tr_load($in_id, $tr_id=0, $tr_type_en_id=0)
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
        $this->load->view('view_ledger/tr_intent_history', array(
            'in_id' => $in_id,
            'tr_id' => $tr_id,
            'tr_type_en_id' => $tr_type_en_id,
        ));
        $this->load->view('view_shared/messenger_footer');
    }



    function fn___in_messages_load($in_id)
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
        } elseif (!isset($_POST['in_id']) || !isset($_POST['focus_tr_type_en_id'])) {
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
        $ins = $this->Database_model->fn___in_fetch(array(
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
        $created_url = $this->Matrix_model->fn___en_url_add($new_file_url);

        //Did we have an error?
        if (!$created_url['status']) {
            //Oops something went wrong, return error:
            return $created_url;
        }


        //Create message:
        $tr = $this->Database_model->fn___tr_create(array(
            'tr_miner_en_id' => $udata['en_id'],
            'tr_type_en_id' => $_POST['focus_tr_type_en_id'],
            'tr_en_parent_id' => $created_url['en_from_url']['en_id'],
            'tr_in_child_id' => intval($_POST['in_id']),
            'tr_content' => '@' . $created_url['en_from_url']['en_id'], //Just place the entity reference as the entire message
            'tr_order' => 1 + $this->Database_model->fn___tr_max_order(array(
                'tr_type_en_id' => $_POST['focus_tr_type_en_id'],
                'tr_in_child_id' => $_POST['in_id'],
            )),
        ));


        //Update intent count & tree:
        //Do a relative adjustment for this intent's metadata
        $this->Matrix_model->fn___metadata_update('in', $ins[0], array(
            'in__message_count' => 1, //Add 1 to existing value
        ), false);

        $this->Matrix_model->fn___metadata_tree_update('in', $ins[0]['in_id'], array(
            'in__message_tree_count' => 1,
        ));


        //Fetch full message for proper UI display:
        $new_messages = $this->Database_model->fn___tr_fetch(array(
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


    function fn___in_load_data()
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
        } elseif (!isset($_POST['tr_id'])) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent Link ID',
            ));
        }

        //Fetch Intent:
        $ins = $this->Database_model->fn___in_fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status >=' => 0, //New+
        ));
        if(count($ins) < 1){
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Prep metadata:
        $ins[0]['in_metadata'] = ( strlen($ins[0]['in_metadata']) > 0 ? unserialize($ins[0]['in_metadata']) : array());

        //Fetch last intent update transaction:
        $updated_trs = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'tr_type_en_id IN (4250, 4264)' => null, //Intent Created/Updated
            'tr_in_child_id' => $_POST['in_id'],
        ), array('en_miner'));
        if(count($updated_trs) < 1){
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent Last Updated Data',
            ));
        }

        //Prep last updated:
        $ins[0]['in___last_updated'] = fn___echo_last_updated('in',$updated_trs[0]);


        if(intval($_POST['tr_id'])>0){

            //Fetch intent link:
            $trs = $this->Database_model->fn___tr_fetch(array(
                'tr_id' => $_POST['tr_id'],
                'tr_status >=' => 0, //New+
            ), array('en_miner'));

            if(count($trs) < 1){
                return fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Intent Link ID',
                ));
            }

            //Prep metadata:
            $trs[0]['tr_metadata'] = ( strlen($trs[0]['tr_metadata']) > 0 ? unserialize($trs[0]['tr_metadata']) : array());

            //Prep last updated:
            $trs[0]['tr___last_updated'] = fn___echo_last_updated('tr',$trs[0]);

        }


        //Fetch intent completion requirements (if any):
        $completion_requirements = $this->Database_model->fn___tr_fetch(array(
            'tr_type_en_id' => 4331, //Intent Completion Requirements
            'tr_in_child_id' => $_POST['in_id'], //For this intent
            'tr_status >=' => 0, //New+
            'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4331')) . ')' => null, //Technically not needed, but here for extra clarity
        ));

        $in_req_ens = array();
        foreach($completion_requirements as $tr){
            array_push($in_req_ens, intval($tr['tr_en_parent_id']));
        }

        //Adjust formats:
        $ins[0]['in_usd'] = number_format($ins[0]['in_usd'], 2);

        //Return results:
        return fn___echo_json(array(
            'status' => 1,
            'in_req_ens' => $in_req_ens,
            'in' => $ins[0],
            'tr' => ( isset($trs[0]) ? $trs[0] : array() ),
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
        $ins = $this->Database_model->fn___in_fetch(array(
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
        $messages = $this->Database_model->fn___tr_fetch(array(
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
        $msg_validation = $this->Chat_model->fn___dispatch_validate_message($_POST['tr_content']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return fn___echo_json($msg_validation);
        }


        //All good, lets move on:
        //Define what needs to be updated:
        $to_update = array(
            'tr_content' => $msg_validation['input_message'],
            'tr_en_parent_id' => $msg_validation['tr_en_parent_id'],
        );


        if (!($_POST['initial_tr_type_en_id'] == $_POST['focus_tr_type_en_id'])) {
            //Change the status:
            $to_update['tr_type_en_id'] = $_POST['focus_tr_type_en_id'];
            //Put it at the end of the new list:
            $to_update['tr_order'] = 1 + $this->Database_model->fn___tr_max_order(array(
                'tr_type_en_id' => $_POST['focus_tr_type_en_id'],
                'tr_in_child_id' => intval($_POST['in_id']),
            ));
        }

        //Now update the DB:
        $this->Database_model->fn___tr_update(intval($_POST['tr_id']), $to_update, $udata['en_id']);

        //Re-fetch the message for display purposes:
        $new_messages = $this->Database_model->fn___tr_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
        ));

        $en_all_4485 = $this->config->item('en_all_4485');

        //Print the challenge:
        return fn___echo_json(array(
            'status' => 1,
            'message' => $this->Chat_model->fn___dispatch_message($msg_validation['input_message'], $udata, false),
            'tr_type_en_id' => $en_all_4485[$new_messages[0]['tr_type_en_id']]['m_icon'].' '.$en_all_4485[$new_messages[0]['tr_type_en_id']]['m_name'],
            'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));
    }

}