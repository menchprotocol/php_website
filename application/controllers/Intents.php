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

        $session_en = $this->session->userdata('user');

        if ((isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'mench.co')) {

            //Go to mench.com for now:
            return redirect_message('https://mench.com');

        } elseif (isset($session_en['en__parents'][0]) && filter_array($session_en['en__parents'], 'en_id', 1308)) {

            //Lead miner and above, go to matrix:
            redirect_message('/intents/' . $this->config->item('in_miner_start'));

        } else {

            //Fetch home page intent:
            $home_ins = $this->Database_model->in_fetch(array(
                'in_id' => $this->config->item('in_home_page'),
            ));

            //How many featured intents do we have?
            $featured_ins = $this->Database_model->tr_fetch(array(
                'tr_status' => 2, //Published
                'in_status' => 2, //Published
                'tr_type_entity_id' => 4228, //Fixed Intent Links
                'tr_parent_intent_id' => $this->config->item('in_featured'), //Feature Mench Intentions
            ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

            if(count($home_ins)<1 && count($featured_ins) > 0){

                //Go to the first featured intent:
                redirect_message('/'.$featured_ins[0]['tr_child_intent_id']);

            } elseif(count($home_ins) > 0){

                //Show index page:
                $this->load->view('view_shared/public_header', array(
                    'title' => echo_in_outcome($home_ins[0]['in_outcome'], true),
                ));
                $this->load->view('view_intents/in_home_featured_ui', array(
                    'in' => $home_ins[0],
                    'featured_ins' => $featured_ins,
                ));
                $this->load->view('view_shared/public_footer');

            } else {

                //Oooopsi, unable to load the home page intent

            }
        }
    }


    function in_landing_page($in_id)
    {

        /*
         *
         * Loads public landing page that Students can use
         * to review intents before adding to Action Plan
         *
         * */

        //Fetch data:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_id,
        ));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert">Intent #' . $in_id . ' not found</div>');
        } elseif ( $ins[0]['in_status'] < 2) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert">Intent #' . $in_id . ' is not published yet</div>');
        }

        //Load home page:
        $this->load->view('view_shared/public_header', array(
            'title' => $ins[0]['in_outcome'],
            'in' => $ins[0],
        ));
        $this->load->view('view_intents/in_landing_page', array( 'in' => $ins[0] ));
        $this->load->view('view_shared/public_footer');

    }


    function in_miner_ui($in_id)
    {

        /*
         *
         * Main intent view that Miners use to manage the
         * intent networks.
         *
         * */

        if($in_id == 0){
            //Set to default:
            $in_id = $this->config->item('in_miner_start');
        }

        //Authenticate Miner, redirect if failed:
        $session_en = en_auth(array(1308), true);

        //Fetch intent with 2 levels of children:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $in_id,
        ), array('in__parents','in__grandchildren'));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return redirect_message('/intents/' . $this->config->item('in_miner_start'), '<div class="alert alert-danger" role="alert">Intent #' . $in_id . ' not found</div>');
        }

        //Update session count and log transaction:
        $new_order = ( $this->session->userdata('miner_session_count') + 1 );
        $this->session->set_userdata('miner_session_count', $new_order);
        $this->Database_model->tr_create(array(
            'tr_miner_entity_id' => $session_en['en_id'],
            'tr_type_entity_id' => 4993, //Miner Opened Intent
            'tr_child_intent_id' => $in_id,
            'tr_order' => $new_order,
        ));

        //Load views:
        $this->load->view('view_shared/matrix_header', array( 'title' => $ins[0]['in_outcome'].' | Intents' ));
        $this->load->view('view_intents/in_miner_ui', array( 'in' => $ins[0] ));
        $this->load->view('view_shared/matrix_footer');

    }



    function in_link_or_create()
    {

        /*
         *
         * Either creates an intent link between in_parent_id & in_link_child_id
         * OR will create a new intent with outcome in_outcome and then link it
         * to in_parent_id (In this case in_link_child_id=0)
         *
         * */

        //Authenticate Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue',
            ));
        } elseif (!isset($_POST['in_parent_id']) || intval($_POST['in_parent_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Parent Intent ID',
            ));
        } elseif (!isset($_POST['is_parent']) || !in_array(intval($_POST['is_parent']), array(0,1))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Is Parent setting',
            ));
        } elseif (!isset($_POST['next_level']) || !in_array(intval($_POST['next_level']), array(2,3))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent Level',
            ));
        } elseif (!isset($_POST['in_outcome']) || !isset($_POST['in_link_child_id']) || ( strlen($_POST['in_outcome']) < 1 && intval($_POST['in_link_child_id']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing either Intent Outcome OR Child Intent ID',
            ));
        } elseif (strlen($_POST['in_outcome']) > $this->config->item('in_outcome_max')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent outcome cannot be longer than '.$this->config->item('in_outcome_max').' characters',
            ));
        }

        //All seems good, go ahead and try creating the intent:
        return echo_json($this->Matrix_model->in_link_or_create($_POST['in_parent_id'], intval($_POST['is_parent']), $_POST['in_outcome'], $_POST['in_link_child_id'], $_POST['next_level'], $session_en['en_id']));

    }






    function in_migrate()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Sign In again to Continue.',
            ));
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid tr_id',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['from_in_id']) || intval($_POST['from_in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing from_in_id',
            ));
        } elseif (!isset($_POST['to_in_id']) || intval($_POST['to_in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing to_in_id',
            ));
        }


        //Fetch all three intents to ensure they are all valid and use them for transaction logging:
        $this_in = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));
        $from_in = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['from_in_id']),
        ));
        $to_in = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['to_in_id']),
            'in_status >=' => 0, //New+
        ));

        if (!isset($this_in[0]) || !isset($from_in[0]) || !isset($to_in[0])) {
            return echo_json(array(
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
        $this->Database_model->tr_update(intval($_POST['tr_id']), array(
            'tr_parent_intent_id' => $to_in[0]['in_id'],
        ), $session_en['en_id']);


        //Adjust tree metadata on both branches that have been affected:
        $updated_from_recursively = $this->Matrix_model->metadata_recursive_update('in', $from_in[0]['in_id'], array(
            'in__tree_in_active_count' => -(intval($this_metadata['in__tree_in_active_count'])),
            'in__tree_max_seconds' => -(intval($this_metadata['in__tree_max_seconds'])),
            'in__message_tree_count' => -(intval($this_metadata['in__message_tree_count'])),
        ));
        $updated_to_recursively = $this->Matrix_model->metadata_recursive_update('in', $to_in[0]['in_id'], array(
            'in__tree_in_active_count' => +(intval($this_metadata['in__tree_in_active_count'])),
            'in__tree_max_seconds' => +(intval($this_metadata['in__tree_max_seconds'])),
            'in__message_tree_count' => +(intval($this_metadata['in__message_tree_count'])),
        ));

        //Return success
        echo_json(array(
            'status' => 1,
        ));
    }

    function in_modify_save()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308));
        $tr_id = intval($_POST['tr_id']);
        $tr_in_link_id = 0; //If >0 means linked intent is being updated...

        //Validate intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ), array('in__parents'));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        } elseif (intval($_POST['level'])==1 && intval($_POST['tr_id'])>0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Level 1 intent should not have a transaction',
            ));
        } elseif (!isset($_POST['tr__conditional_score_min']) || !isset($_POST['tr__conditional_score_max'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Score Min/Max Variables',
            ));
        } elseif (!isset($_POST['tr__assessment_points'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing assessment points',
            ));
        } elseif (!isset($_POST['level']) || intval($_POST['level']) < 1 || intval($_POST['level']) > 3) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid level',
            ));
        } elseif (!isset($_POST['in_outcome']) || strlen($_POST['in_outcome']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Outcome',
            ));
        } elseif (strlen($_POST['in_outcome']) > $this->config->item('in_outcome_max')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent outcome cannot be longer than '.$this->config->item('in_outcome_max').' characters',
            ));
        } elseif (!isset($_POST['in_seconds_cost']) || intval($_POST['in_seconds_cost']) < 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Time Estimate',
            ));
        } elseif (intval($_POST['in_seconds_cost']) > $this->config->item('in_seconds_cost_max')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Maximum estimated time is ' . round(($this->config->item('in_seconds_cost_max') / 3600), 2) . ' hours for each intent. If larger, break the intent down into smaller intents.',
            ));
        } elseif (!isset($_POST['apply_recursively'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Recursive setting',
            ));
        } elseif (!isset($_POST['in_requirement_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Completion Entity ID',
            ));
        } elseif (!isset($_POST['in_dollar_cost']) || doubleval($_POST['in_dollar_cost']) < 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Cost Estimate',
            ));
        } elseif (!isset($_POST['in_status'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent Status',
            ));
        } elseif (intval($_POST['in_dollar_cost']) < 0 || doubleval($_POST['in_dollar_cost']) > 300) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Cost estimate must be $0-5000 USD',
            ));
        } elseif (!isset($_POST['in_type'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Completion Settings',
            ));
        } elseif (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent Not Found',
            ));
        } elseif($tr_id > 0 && intval($_POST['tr_type_entity_id']) == 4229){
            //Conditional links, we require range values:
            if(strlen($_POST['tr__conditional_score_min']) < 1 || !is_numeric($_POST['tr__conditional_score_min'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Missing MIN range, enter 0 or greater',
                ));
            } elseif(strlen($_POST['tr__conditional_score_max']) < 1 || !is_numeric($_POST['tr__conditional_score_max'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Missing MAX range, enter 0 or greater',
                ));
            } elseif(doubleval($_POST['tr__conditional_score_min']) > doubleval($_POST['tr__conditional_score_max'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'MIN range cannot be larger than MAX',
                ));
            }
        }




        //calculate Verb ID:
        $_POST['in_verb_entity_id'] = detect_starting_verb_id($_POST['in_outcome']);

        //Prep new variables:
        $in_update = array(
            'in_status' => intval($_POST['in_status']),
            'in_outcome' => trim($_POST['in_outcome']),
            'in_seconds_cost' => intval($_POST['in_seconds_cost']),
            'in_requirement_entity_id' => intval($_POST['in_requirement_entity_id']),
            'in_verb_entity_id' => $_POST['in_verb_entity_id'],
            'in_dollar_cost' => doubleval($_POST['in_dollar_cost']),
            'in_type' => intval($_POST['in_type']),
        );

        //Prep current intent metadata:
        $in_metadata = unserialize($ins[0]['in_metadata']);

        //Determines if Intent has been removed OR unlinked:
        $remove_from_ui = 0; //Assume not
        $remove_redirect_url = null;

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
                if ($key == 'in_seconds_cost') {

                    $in_metadata_modify['in__tree_min_seconds'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_min_seconds']) ? intval($in_metadata['in__tree_min_seconds']) : 0 );
                    $in_metadata_modify['in__tree_max_seconds'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_max_seconds']) ? intval($in_metadata['in__tree_max_seconds']) : 0 );

                } elseif ($key == 'in_dollar_cost') {

                    $in_metadata_modify['in__tree_min_cost'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_min_cost']) ? intval($in_metadata['in__tree_min_cost']) : 0 );
                    $in_metadata_modify['in__tree_max_cost'] = intval($_POST[$key]) - ( isset($in_metadata['in__tree_max_cost']) ? intval($in_metadata['in__tree_max_cost']) : 0 );

                } elseif ($key == 'in_outcome') {

                    //Check to make sure starts with a verb:
                    if($in_update['in_verb_entity_id'] < 1){

                        //Not a acceptable starting word:
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'Starting verb is not yet supported. Manage supported verbs via entity @5008'.( en_auth(array(1281)) ? ' or use the /force command to add this verb to the supported list.' : '' ),
                        ));

                    }

                    //Check to make sure it's not a duplicate outcome:
                    $duplicate_outcome_ins = $this->Database_model->in_fetch(array(
                        'in_id !=' => $ins[0]['in_id'],
                        'in_status >=' => 0, //New+
                        'LOWER(in_outcome)' => strtolower($value),
                    ));

                    if(count($duplicate_outcome_ins) > 0){
                        //This is a duplicate, disallow:
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'Outcome ['.$value.'] already in use by intent #'.$duplicate_outcome_ins[0]['in_id'],
                        ));
                    } else {
                        //Cleanup outcome before saving:
                        $_POST[$key] = trim($_POST[$key]);
                    }

                } elseif ($key == 'in_status') {

                    //Has intent been removed?
                    if($value < 0){

                        //Intent has been removed:
                        $remove_from_ui = 1;

                        //Did we remove the main intent?
                        if($_POST['level']==1){
                            //Yes, redirect to a parent intent if we have any:
                            if(count($ins[0]['in__parents']) > 0){
                                $remove_redirect_url = '/intents/' . $ins[0]['in__parents'][0]['in_id'];
                            } else {
                                //No parents, redirect to default intent:
                                $remove_redirect_url = '/intents/' . $this->config->item('in_miner_start');
                            }
                        }

                        //Unlink intent links:
                        $links_removed = $this->Matrix_model->in_unlink($_POST['in_id'] , $session_en['en_id']);

                        //Prep metadata:
                        $metadata = unserialize($ins[0]['in_metadata']);

                        //Update parent intent tree (and upwards) to reduce totals based on child intent metadata:
                        $this->Matrix_model->metadata_recursive_update('in', $ins[0]['in_id'], array(
                            'in__tree_in_active_count' => -( isset($metadata['in__tree_in_active_count']) ? $metadata['in__tree_in_active_count'] : 0 ),
                            'in__tree_max_seconds' => -( isset($metadata['in__tree_max_seconds']) ? $metadata['in__tree_max_seconds'] : 0 ),
                            'in__message_tree_count' => -( isset($metadata['in__message_tree_count']) ? $metadata['in__message_tree_count'] : 0 ),
                        ));

                        //Treat as if no link (Since it was removed):
                        $tr_id = 0;
                    }

                    if(intval($_POST['apply_recursively'])){
                        //Intent status has changed and there is a recursive update request:
                        //Yes, sync downwards where current statuses match:
                        $children = $this->Matrix_model->in_fetch_recursive(intval($_POST['in_id']), true);

                        //Fetch all intents that match parent intent status:
                        $child_ins = $this->Database_model->in_fetch(array(
                            'in_id IN ('.join(',' , $children['in_flat_tree']).')' => null,
                            'in_status' => intval($ins[0]['in_status']), //Same as status before update
                        ));

                        foreach ($child_ins as $child_in) {
                            //Update this intent as the status did match:
                            $status_update_children += $this->Database_model->in_update($child_in['in_id'], array(
                                'in_status' => $in_update['in_status']
                            ), true, $session_en['en_id']);
                        }
                    }
                }

                //This field has been updated, update one field at a time:
                $this->Database_model->in_update($_POST['in_id'], array( $key => $_POST[$key] ), true, $session_en['en_id']);

            }
        }

        //Any relative metadata upward recursive updates needed?
        if (count($in_metadata_modify) > 0) {
            $this->Matrix_model->metadata_recursive_update('in', $_POST['in_id'], $in_metadata_modify);
        }





        //Assume transaction is not updated:
        $transaction_was_updated = false;

        //Does this request has an intent transaction?
        if($tr_id > 0){

            //Validate Transaction and inputs:
            $trs = $this->Database_model->tr_fetch(array(
                'tr_id' => $tr_id,
                'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                'tr_status >=' => 0, //New+
            ), array(( $_POST['is_parent'] ? 'in_child' : 'in_parent')));
            if(count($trs) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid link ID',
                ));
            }

            //Prep link Metadata to see if the conditional score variables have changed:
            $tr_update = array(
                'tr_type_entity_id'     => intval($_POST['tr_type_entity_id']),
                'tr_status'         => intval($_POST['tr_status']),
            );




            //Validate the input for updating linked intent:
            if(substr($_POST['tr_in_link_update'], 0, 1)=='#'){
                $parts = explode(' ', $_POST['tr_in_link_update']);
                $tr_in_link_id = intval(str_replace('#', '', $parts[0]));
            }
            if($tr_in_link_id > 0){

                //Did we find it?
                if($tr_in_link_id==$trs[0]['tr_parent_intent_id'] || $tr_in_link_id==$trs[0]['tr_child_intent_id']){
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Intent already linked here',
                    ));
                }

                //Validate intent:
                $linked_ins = $this->Database_model->in_fetch(array(
                    'in_id' => $tr_in_link_id,
                ));
                if(count($linked_ins)==0){
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Newly linked intent not found',
                    ));
                }

                //All good, make the move:
                $tr_update[( $_POST['is_parent'] ? 'tr_child_intent_id' : 'tr_parent_intent_id')] = $tr_in_link_id;
                $tr_update['tr_order'] = 9999; //Place at the bottom of this new list
                $remove_from_ui = 1;
                //Did we move it on another intent on the same page? If so reload to show accurate info:
                if(in_array($tr_in_link_id, $_POST['tr_in_focus_ids'])){
                    //Yes, refresh the page:
                    $remove_redirect_url = '/intents/' . $_POST['tr_in_focus_ids'][0]; //First item is the main intent
                }
            } elseif(strlen($_POST['tr_in_link_update']) > 0 && !($_POST['tr_in_link_update']==$trs[0]['in_outcome'])){
                //The value changed in an unknown way...
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Unknown '.( $_POST['is_parent'] ? 'child' : 'parent').' intent. Leave as-is or select intent from search suggestions.',
                ));
            }


            //Prep variables:
            $tr_metadata = ( strlen($trs[0]['tr_metadata']) > 0 ? unserialize($trs[0]['tr_metadata']) : array() );

            //Check to see if anything changed in the transaction?
            $transaction_meta_updated = ( (($tr_update['tr_type_entity_id'] == 4228 && (
                        !isset($tr_metadata['tr__assessment_points']) ||
                        !(intval($tr_metadata['tr__assessment_points'])==intval($_POST['tr__assessment_points']))
                    ))) || (($tr_update['tr_type_entity_id'] == 4229 && (
                        !isset($tr_metadata['tr__conditional_score_min']) ||
                        !isset($tr_metadata['tr__conditional_score_max']) ||
                        !(doubleval($tr_metadata['tr__conditional_score_max'])==doubleval($_POST['tr__conditional_score_max'])) ||
                        !(doubleval($tr_metadata['tr__conditional_score_min'])==doubleval($_POST['tr__conditional_score_min']))
                    ))));



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

                if($transaction_meta_updated && (!isset($tr_update['tr_status']) || $tr_update['tr_status'] >= 0)){
                    $tr_update['tr_metadata'] = array_merge( $tr_metadata, array(
                        'tr__conditional_score_min' => doubleval($_POST['tr__conditional_score_min']),
                        'tr__conditional_score_max' => doubleval($_POST['tr__conditional_score_max']),
                        'tr__assessment_points' => intval($_POST['tr__assessment_points']),
                    ));
                }

                //Also update the timestamp & new miner:
                $tr_update['tr_timestamp'] = date("Y-m-d H:i:s");
                $tr_update['tr_miner_entity_id'] = $session_en['en_id'];

                //Update transactions:
                $this->Database_model->tr_update($tr_id, $tr_update, $session_en['en_id']);
            }

        }



        $return_data = array(
            'status' => 1,
            'message' => '<i class="fas fa-check"></i> Saved',
            'remove_from_ui' => $remove_from_ui,
            'formatted_in_outcome' => ( isset($in_update['in_outcome']) ? echo_in_outcome($in_update['in_outcome']) : null ),
            'remove_redirect_url' => $remove_redirect_url,
            'status_update_children' => $status_update_children,
            'in__tree_in_active_count' => -( isset($in_metadata['in__tree_in_active_count']) ? $in_metadata['in__tree_in_active_count'] : 0 ),
        );


        //Did we have an intent link update? If so, update the last updated UI:
        if($transaction_was_updated){

            //Fetch last intent Ledger Transaction:
            $trs = $this->Database_model->tr_fetch(array(
                'tr_id' => $tr_id,
            ), array('en_miner'));

        }

        //Show success:
        return echo_json($return_data);

    }


    function in_sort_save()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Sign In again to Continue.',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['new_tr_orders']) || !is_array($_POST['new_tr_orders']) || count($_POST['new_tr_orders']) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Parent intent:
            $parent_ins = $this->Database_model->in_fetch(array(
                'in_id' => intval($_POST['in_id']),
            ));
            if (count($parent_ins) < 1) {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid in_id',
                ));
            } else {

                //Fetch for the record:
                $children_before = $this->Database_model->tr_fetch(array(
                    'tr_parent_intent_id' => intval($_POST['in_id']),
                    'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_status >=' => 0,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

                //Update them all:
                foreach ($_POST['new_tr_orders'] as $rank => $tr_id) {
                    $this->Database_model->tr_update(intval($tr_id), array(
                        'tr_order' => intval($rank),
                    ), $session_en['en_id']);
                }

                //Fetch again for the record:
                $children_after = $this->Database_model->tr_fetch(array(
                    'tr_parent_intent_id' => intval($_POST['in_id']),
                    'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_status >=' => 0,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

                //Display message:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-check"></i> Sorted',
                ));
            }
        }
    }

    function in_help_messages()
    {

        /*
         *
         * A function to display Matrix Tips to give Miners
         * more information on each field and their use-case.
         *
         * */

        //Validate Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {
            return echo_json(array(
                'success' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'success' => 0,
                'message' => 'Missing Intent ID',
            ));
        }

        //Fetch Intent Note Messages for this intent:
        $on_start_messages = $this->Database_model->tr_fetch(array(
            'tr_status' => 2, //Published
            'tr_type_entity_id' => 4231, //Intent Note Messages
            'tr_child_intent_id' => $_POST['in_id'],
        ), array(), 0, 0, array('tr_order' => 'ASC'));

        if (count($on_start_messages) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent Missing Intent Note Messages',
            ));
        }

        $_GET['log_miner_messages'] = 1; //Will log miner messages which normally do not get logged (so we prevent Intent Note editing logs)

        $tip_messages = null;
        foreach ($on_start_messages as $tr) {
            //What type of message is this?
            $tip_messages .= $this->Chat_model->dispatch_message($tr['tr_content'], $session_en, false, array(), array(
                'tr_parent_intent_id' => $_POST['in_id'],
            ));
        }

        //Return results:
        return echo_json(array(
            'status' => 1,
            'tip_messages' => $tip_messages,
        ));
    }


    function in_messages_iframe($in_id)
    {

        //Authenticate as a Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Sign In again to continue.</span>');
        } elseif (intval($in_id) < 1) {
            die('<span style="color:#FF0000;">Error: Invalid Intent id.</span>');
        }

        //Don't show the heading here as we're loading inside an iframe:
        $_GET['skip_header'] = 1;

        //Load view:
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Intent #' . $in_id . ' Messages',
        ));
        $this->load->view('view_intents/in_messages_frame', array(
            'in_id' => $in_id,
        ));
        $this->load->view('view_shared/matrix_footer');

    }


    function in_new_message_from_text()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308));

        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Sign In and Try again.',
            ));

        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));

        } elseif (!isset($_POST['focus_tr_type_entity_id']) || intval($_POST['focus_tr_type_entity_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }


        //Fetch/Validate the intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status >=' => 0, //New+
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->Chat_model->dispatch_validate_message($_POST['tr_content'], array(), false, array(), $_POST['focus_tr_type_entity_id'], $_POST['in_id']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return echo_json($msg_validation);
        }

        //Create Message:
        $tr = $this->Database_model->tr_create(array(
            'tr_status' => 0, //New
            'tr_miner_entity_id' => $session_en['en_id'],
            'tr_order' => 1 + $this->Database_model->tr_max_order(array(
                    'tr_status >=' => 0, //New+
                    'tr_type_entity_id' => intval($_POST['focus_tr_type_entity_id']),
                    'tr_child_intent_id' => intval($_POST['in_id']),
                )),
            //Referencing attributes:
            'tr_type_entity_id' => intval($_POST['focus_tr_type_entity_id']),
            'tr_parent_entity_id' => $msg_validation['tr_parent_entity_id'],
            'tr_parent_intent_id' => $msg_validation['tr_parent_intent_id'],
            'tr_child_intent_id' => intval($_POST['in_id']),
            'tr_content' => $msg_validation['input_message'],
        ), true);

        //Do a relative adjustment for this intent's metadata
        $this->Matrix_model->metadata_single_update('in', $ins[0]['in_id'], array(
            'in__metadata_count' => 1, //Add 1 to existing value
        ), false);

        //Update tree as well:
        $this->Matrix_model->metadata_recursive_update('in', $ins[0]['in_id'], array(
            'in__message_tree_count' => 1,
        ));

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_in_message_manage(array_merge($tr, array(
                'tr_child_entity_id' => $session_en['en_id'],
            ))),
        ));
    }


    function in_new_message_from_attachment()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh to Continue',
            ));

        } elseif (!isset($_POST['in_id']) || !isset($_POST['focus_tr_type_entity_id'])) {

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
                'message' => 'Unknown error while trying to save file.',
            ));

        } elseif ($_FILES[$_POST['upload_type']]['size'] > ($this->config->item('en_file_max_size') * 1024 * 1024)) {

            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . $this->config->item('en_file_max_size') . ' MB.',
            ));

        }

        //Validate Intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //See if this message type has specific input requirements:
        $valid_file_types = array(4258, 4259, 4260, 4261); //This must be a valid file type:  Video, Image, Audio or File

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

        $new_file_url = trim(upload_to_cdn($temp_local, $_FILES[$_POST['upload_type']], true));

        //What happened?
        if (!$new_file_url) {
            //Oops something went wrong:
            return echo_json(array(
                'status' => 0,
                'message' => 'Failed to save file to Mench cloud',
            ));
        }


        //Save URL and connect it to the Mench CDN entity:
        $url_entity = $this->Matrix_model->en_sync_url($new_file_url, $session_en['en_id'], 4396 /* Mench CDN Entity */);

        //Did we have an error?
        if (!$url_entity['status']) {
            //Oops something went wrong, return error:
            return $url_entity;
        }


        //Create message:
        $tr = $this->Database_model->tr_create(array(
            'tr_status' => 0, //New
            'tr_miner_entity_id' => $session_en['en_id'],
            'tr_type_entity_id' => $_POST['focus_tr_type_entity_id'],
            'tr_parent_entity_id' => $url_entity['en_url']['en_id'],
            'tr_child_intent_id' => intval($_POST['in_id']),
            'tr_content' => '@' . $url_entity['en_url']['en_id'], //Just place the entity reference as the entire message
            'tr_order' => 1 + $this->Database_model->tr_max_order(array(
                'tr_type_entity_id' => $_POST['focus_tr_type_entity_id'],
                'tr_child_intent_id' => $_POST['in_id'],
            )),
        ));


        //Update intent count & tree:
        //Do a relative adjustment for this intent's metadata
        $this->Matrix_model->metadata_single_update('in', $ins[0]['in_id'], array(
            'in__metadata_count' => 1, //Add 1 to existing value
        ), false);

        $this->Matrix_model->metadata_recursive_update('in', $ins[0]['in_id'], array(
            'in__message_tree_count' => 1,
        ));


        //Fetch full message for proper UI display:
        $new_messages = $this->Database_model->tr_fetch(array(
            'tr_id' => $tr['tr_id'],
        ));

        //Echo message:
        echo_json(array(
            'status' => 1,
            'message' => echo_in_message_manage(array_merge($new_messages[0], array(
                'tr_child_entity_id' => $session_en['en_id'],
            ))),
        ));
    }


    function in_load_data()
    {

        /*
         *
         * An AJAX function that is triggered every time a Miner
         * selects to modify an intent. It will check the
         * completion requirements of an intent so it can
         * check proper boxes to help Miner modify the intent.
         *
         * */

        $session_en = en_auth(array(1308));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh.',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            ));
        } elseif (!isset($_POST['tr_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent Link ID',
            ));
        }

        //Fetch Intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Prep metadata:
        $ins[0]['in_metadata'] = ( strlen($ins[0]['in_metadata']) > 0 ? unserialize($ins[0]['in_metadata']) : array());


        if(intval($_POST['tr_id'])>0){

            //Fetch intent link:
            $trs = $this->Database_model->tr_fetch(array(
                'tr_id' => $_POST['tr_id'],
                'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                'tr_status >=' => 0, //New+
            ), array(( $_POST['is_parent'] ? 'in_child' : 'in_parent' )));

            if(count($trs) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Intent Link ID',
                ));
            }

            //Add link connector:
            $trs[0]['tr_metadata'] = ( strlen($trs[0]['tr_metadata']) > 0 ? unserialize($trs[0]['tr_metadata']) : array());

            //Make sure points are set:
            if(!isset($trs[0]['tr_metadata']['tr__assessment_points'])){
                $trs[0]['tr_metadata']['tr__assessment_points'] = 0;
            }

        }




        //Adjust formats:
        $ins[0]['in_dollar_cost'] = number_format(doubleval($ins[0]['in_dollar_cost']), 2);

        //Return results:
        return echo_json(array(
            'status' => 1,
            'in' => $ins[0],
            'tr' => ( isset($trs[0]) ? $trs[0] : array() ),
        ));

    }



    function in_message_sort()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Sign In and try again',
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
                ), $session_en['en_id']);
            }
        }

        //Return success:
        return echo_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }

    function in_message_modify()
    {

        //Authenticate Miner:
        $session_en = en_auth(array(1308));
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh.',
            ));
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Transaction ID',
            ));
        } elseif (!isset($_POST['new_message_tr_status'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Status',
            ));
        } elseif (!isset($_POST['tr_content'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Validate Intent:
        $ins = $this->Database_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent Not Found',
            ));
        }

        //Validate Message:
        $messages = $this->Database_model->tr_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
            'tr_status >=' => 0,
        ));
        if (count($messages) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }


        //Did the message status change?
        if($messages[0]['tr_status'] != $_POST['new_message_tr_status']){

            //Are we deleting this message?
            if($_POST['new_message_tr_status'] == -1){

                //yes, do so and return results:
                $affected_rows = $this->Database_model->tr_update(intval($_POST['tr_id']), array( 'tr_status' => $_POST['new_message_tr_status'] ), $session_en['en_id']);

                //Return success:
                if($affected_rows > 0){

                    //Do a relative adjustment for this intent's metadata
                    $this->Matrix_model->metadata_single_update('in', $ins[0]['in_id'], array(
                        'in__metadata_count' => -1, //Remove 1 from existing value
                    ), false);

                    //Update intent tree:
                    $this->Matrix_model->metadata_recursive_update('in', $ins[0]['in_id'], array(
                        'in__message_tree_count' => -1,
                    ));

                    return echo_json(array(
                        'status' => 1,
                        'message' => 'Successfully removed',
                    ));
                } else {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Error trying to remove message',
                    ));
                }

            } elseif($_POST['new_message_tr_status'] == 2){

                //We're publishing, make sure potential entity references are also published:
                $msg_references = extract_message_references($_POST['tr_content']);

                if (count($msg_references['ref_entities']) > 0) {

                    //We do have an entity reference, what's its status?
                    $ref_ens = $this->Database_model->en_fetch(array(
                        'en_id' => $msg_references['ref_entities'][0],
                    ));

                    if(count($ref_ens)>0 && $ref_ens[0]['en_status']<2){
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'You cannot published this message because its referenced entity is not yet published',
                        ));
                    }
                }
            }
        }



        //Validate new message:
        $msg_validation = $this->Chat_model->dispatch_validate_message($_POST['tr_content'], array(), false, array(), $messages[0]['tr_type_entity_id'], $_POST['in_id']);
        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return echo_json($msg_validation);
        }


        //All good, lets move on:
        //Define what needs to be updated:
        $to_update = array(
            'tr_status' => $_POST['new_message_tr_status'],
            'tr_content' => $msg_validation['input_message'],
            'tr_parent_entity_id' => $msg_validation['tr_parent_entity_id'],
            'tr_parent_intent_id' => $msg_validation['tr_parent_intent_id'],
        );

        //Now update the DB:
        $this->Database_model->tr_update(intval($_POST['tr_id']), $to_update, $session_en['en_id']);

        //Re-fetch the message for display purposes:
        $new_messages = $this->Database_model->tr_fetch(array(
            'tr_id' => intval($_POST['tr_id']),
        ));

        $fixed_fields = $this->config->item('fixed_fields');

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => $this->Chat_model->dispatch_message($msg_validation['input_message'], $session_en, false, array(), array(), $_POST['in_id']),
            'message_new_status_icon' => '<span title="' . $fixed_fields['tr_status'][$to_update['tr_status']]['s_name'] . ': ' . $fixed_fields['tr_status'][$to_update['tr_status']]['s_desc'] . '" data-toggle="tooltip" data-placement="top">' . $fixed_fields['tr_status'][$to_update['tr_status']]['s_icon'] . '</span>', //This might have changed
            'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));
    }




    function cron__update_metadata($in_id = 0, $update_db = 1)
    {

        /*
         *
         * Updates the metadata cache data for intents starting at $in_id.
         *
         * If $in_id is not provided, it defaults to in_mission_id which
         * is the highest level of intent in the Mench tree.
         *
         * */

        if(!$in_id){
            $in_id = $this->config->item('in_mission_id');
        }
        //Cron Settings: 31 * * * *
        //Syncs intents with latest caching data:

        $sync = $this->Matrix_model->in_fetch_recursive($in_id, true, $update_db);
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            //Now redirect;
            header('Location: ' . $_GET['redirect']);
        } else {
            //Remove the long "in_tree" variable which makes the page load slow:
            unset($sync['in_tree']);

            //Show json:
            echo_json($sync);
        }
    }

}