<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Links extends CI_Controller
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
         * List all Links on reverse chronological order
         * and Display statuses for intents, entities and
         * links.
         *
         * */

        $session_en = en_auth(array(1308,7512)); //Just be logged in to browse

        //Load header:
        $this->load->view(($session_en ? 'view_miner_app/miner_app_header' : 'view_user_app/user_app_header'), array(
            'title' => 'Mench Links',
        ));

        //Load main:
        $this->load->view('view_miner_app/links_ui');

        //Load footer:
        $this->load->view(($session_en ? 'view_miner_app/miner_app_footer' : 'view_user_app/user_app_footer'));

    }


    function js_ln_create(){

        //Log link from JS source:
        if(isset($_POST['ln_order']) && strlen($_POST['ln_order'])>0 && !is_numeric($_POST['ln_order'])){
            //We have an order set, but its not an integer, which means it's a cookie name that needs to be analyzed:
            $_POST['ln_order'] = fetch_cookie_order($_POST['ln_order']);
        }

        //Log engagement:
        echo_json($this->Links_model->ln_create($_POST));
    }


    function load_link_list(){

        /*
         * Loads the list of links based on the
         * filters passed on.
         *
         * */

        $filters = unserialize($_POST['link_filters']);
        $join_by = unserialize($_POST['link_join_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $item_per_page = (is_dev_environment() ? 20 : $this->config->item('items_per_page'));
        $query_offset = (($page_num-1)*$item_per_page);

        $message = '';

        //Fetch links and total link counts:
        $lns = $this->Links_model->ln_fetch($filters, $join_by, $item_per_page, $query_offset);
        $lns_count = $this->Links_model->ln_fetch($filters, $join_by, 0, 0, array(), 'COUNT(ln_id) as total_count, SUM(ABS(ln_words)) as total_words');
        $total_items_loaded = ($query_offset+count($lns));
        $has_more_links = ($lns_count[0]['total_count'] > 0 && $total_items_loaded < $lns_count[0]['total_count']);


        //Display filter notes:
        if($total_items_loaded > 0){
            $message .= '<p style="margin: 10px 0 0 0;">'.( $has_more_links && $query_offset==0  ? 'First ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' of ' : '' ) . number_format($lns_count[0]['total_count'] , 0) .' Links:</p>';
        }
        // with '.number_format($lns_count[0]['total_words'], 0).' awarded credits


        if(count($lns)>0){

            $message .= '<div class="list-group list-grey">';
            foreach ($lns as $ln) {
                $message .= echo_ln($ln);
            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_links){
                $message .= '<div id="link_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn btn-primary grey" onclick="load_link_list(link_filters, link_join_by, '.$next_page.');"><i class="fas fa-plus-circle"></i> Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><i class="far fa-check-circle"></i> All '.$lns_count[0]['total_count'].' link'.echo__s($lns_count[0]['total_count']).' have been loaded</div>';

            }

        } else {

            //Show no link warning:
            $message .= '<div class="alert alert-warning" role="alert" style="margin-top:20px;"><i class="fas fa-exclamation-triangle"></i> No Links found with the selected filters. Modify filters and try again.</div>';

        }


        return echo_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }


    function add_search_item(){

        //Authenticate Miner:
        $session_en = en_auth(array(1308,7512));

        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Sign In and try again',
            ));

        } elseif (!isset($_POST['raw_string'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));

        }

        //See if intent or entity:
        if(substr($_POST['raw_string'], 0, 1)=='#'){

            $in_outcome = trim(substr($_POST['raw_string'], 1));
            if(strlen($in_outcome)<2){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Intent outcome must be at-least 2 characters long.',
                ));
            }

            //Validate Intent Outcome:
            $in_outcome_validation = $this->Intents_model->in_validate_outcome($in_outcome);
            if(!$in_outcome_validation['status']){
                //We had an error, return it:
                return echo_json($in_outcome_validation);
            }

            //All good, let's create the intent:
            $intent_new = $this->Intents_model->in_create(array(
                'in_outcome' => $in_outcome_validation['in_cleaned_outcome'],
                'in_verb_entity_id' => $in_outcome_validation['detected_in_verb_entity_id'],
                'in_subtype_entity_id' => 6677, //Read-Only
                'in_status_entity_id' => 6183, //Intent New
            ), true, $session_en['en_id']);

            return echo_json(array(
                'status' => 1,
                'new_item_url' => '/intents/' . $intent_new['in_id'],
            ));

        } elseif(substr($_POST['raw_string'], 0, 1)=='@'){

            //Create entity:
            $added_en = $this->Entities_model->en_verify_create(trim(substr($_POST['raw_string'], 1)), $session_en['en_id']);
            if(!$added_en['status']){
                //We had an error, return it:
                return echo_json($added_en);
            } else {
                //Assign new entity:
                return echo_json(array(
                    'status' => 1,
                    'new_item_url' => '/entities/' . $added_en['en']['en_id'],
                ));
            }

        } else {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid string. Must start with either # or @.',
            ));
        }
    }



    function link_json($ln_id)
    {

        //Fetch link metadata and display it:
        $lns = $this->Links_model->ln_fetch(array(
            'ln_id' => $ln_id,
        ));

        if (count($lns) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Link ID',
            ));
        } elseif(!en_auth(array(1308)) /* Viewer NOT a miner */) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Link metadata visible to miners only',
            ));
        } else {

            //unserialize metadata if needed:
            if(strlen($lns[0]['ln_metadata']) > 0){
                $lns[0]['ln_metadata'] = unserialize($lns[0]['ln_metadata']);
            }

            //Print on scree:
            echo_json($lns[0]);

        }
    }




    function cron__sync_algolia($input_obj_type = null, $input_obj_id = null){

        if($input_obj_type < 0){
            //Gateway URL to give option to run...
            die('<a href="/links/cron__sync_algolia">Click here</a> to start running this function.');
        }

        //Call the update function and passon possible values:
        echo_json(update_algolia($input_obj_type, $input_obj_id));
    }

    function load_link_connections(){


        //Authenticate Miner:
        if (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        } elseif (!isset($_POST['load_main'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing loading preference',
            ));
        }

        //Fetch and validate link:
        $lns = $this->Links_model->ln_fetch(array(
            'ln_id' => $_POST['ln_id'],
        ));
        if (count($lns) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Link ID',
            ));
        }

        //Show Links:
        $ln_connections_ui = ( intval($_POST['load_main']) ? '' : echo_ln_connections($lns[0]) );

        //Now show all links for this link:
        foreach ($this->Links_model->ln_fetch(array(
            'ln_parent_link_id' => $_POST['ln_id'],
        ), array(), 0, 0, array('ln_id' => 'DESC')) as $ln_child) {
            $ln_connections_ui .= '<div class="tr-child">' . echo_ln($ln_child, true) . '</div>';
        }

        //Return UI:
        return echo_json(array(
            'status' => 1,
            'ln_connections_ui' => $ln_connections_ui,
        ));

    }

    function cron__sync_gephi($affirmation = null){

        /*
         *
         * Populates the nodes and edges table for
         * Gephi https://gephi.org network visualizer
         *
         * */

        if($affirmation < 0){
            //Gateway URL to give option to run...
            die('<a href="/links/cron__sync_gephi">Click here</a> to start running this function.');
        }

        //Boost processing power:
        boost_power();

        //Empty both tables:
        $this->db->query("TRUNCATE TABLE public.gephi_edges CONTINUE IDENTITY RESTRICT;");
        $this->db->query("TRUNCATE TABLE public.gephi_nodes CONTINUE IDENTITY RESTRICT;");

        //Load Intent-to-Intent Links:
        $en_all_4593 = $this->config->item('en_all_4593');

        //To make sure intent/entity IDs are unique:
        $id_prefix = array(
            'in' => 100,
            'en' => 200,
        );

        //Size of nodes:
        $node_size = array(
            'in' => 3,
            'en' => 2,
            'msg' => 1,
        );

        //Add intents:
        $ins = $this->Intents_model->in_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ));
        foreach($ins as $in){

            //Prep metadata:
            $in_metadata = ( strlen($in['in_metadata']) > 0 ? unserialize($in['in_metadata']) : array());

            //Add intent node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['in'].$in['in_id'],
                'label' => $in['in_outcome'],
                //'size' => ( isset($in_metadata['in__metadata_max_seconds']) ? round(($in_metadata['in__metadata_max_seconds']/3600),0) : 0 ), //Max time
                'size' => $node_size['in'],
                'node_type' => 1, //Intent
                'node_status' => $in['in_status_entity_id'],
            ));

            //Fetch children:
            foreach($this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                'ln_parent_intent_id' => $in['in_id'],
            ), array('in_child'), 0, 0) as $in_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$in_child['ln_parent_intent_id'],
                    'target' => $id_prefix['in'].$in_child['ln_child_intent_id'],
                    'label' => $en_all_4593[$in_child['ln_type_entity_id']]['m_name'], //TODO maybe give visibility to condition here?
                    'weight' => 1, //TODO Maybe update later?
                    'edge_type_en_id' => $in_child['ln_type_entity_id'],
                    'edge_status' => $in_child['ln_status_entity_id'],
                ));

            }
        }


        //Add entities:
        $ens = $this->Entities_model->en_fetch(array(
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ));
        foreach($ens as $en){

            //Add entity node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['en'].$en['en_id'],
                'label' => $en['en_name'],
                'size' => $node_size['en'] ,
                'node_type' => 2, //Entity
                'node_status' => $en['en_status_entity_id'],
            ));

            //Fetch children:
            foreach($this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
                'ln_parent_entity_id' => $en['en_id'],
            ), array('en_child'), 0, 0) as $en_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$en_child['ln_parent_entity_id'],
                    'target' => $id_prefix['en'].$en_child['ln_child_entity_id'],
                    'label' => $en_all_4593[$en_child['ln_type_entity_id']]['m_name'].': '.$en_child['ln_content'],
                    'weight' => 1, //TODO Maybe update later?
                    'edge_type_en_id' => $en_child['ln_type_entity_id'],
                    'edge_status' => $en_child['ln_status_entity_id'],
                ));

            }
        }

        //Add messages:
        $messages = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
        ), array('in_child'), 0, 0);
        foreach($messages as $message) {

            //Add message node:
            $this->db->insert('gephi_nodes', array(
                'id' => $message['ln_id'],
                'label' => $en_all_4593[$message['ln_type_entity_id']]['m_name'] . ': ' . $message['ln_content'],
                'size' => $node_size['msg'],
                'node_type' => $message['ln_type_entity_id'], //Message type
                'node_status' => $message['ln_status_entity_id'],
            ));

            //Add child intent link:
            $this->db->insert('gephi_edges', array(
                'source' => $message['ln_id'],
                'target' => $id_prefix['in'].$message['ln_child_intent_id'],
                'label' => 'Child Intent',
                'weight' => 1, //TODO Maybe update later?
            ));

            //Add parent intent link?
            if ($message['ln_parent_intent_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$message['ln_parent_intent_id'],
                    'target' => $message['ln_id'],
                    'label' => 'Parent Intent',
                    'weight' => 1, //TODO Maybe update later?
                ));
            }

            //Add parent entity link?
            if ($message['ln_parent_entity_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$message['ln_parent_entity_id'],
                    'target' => $message['ln_id'],
                    'label' => 'Parent Entity',
                    'weight' => 1, //TODO Maybe update later?
                ));
            }

        }

        echo count($ins).' intents & '.count($ens).' entities & '.count($messages).' messages synced.';
    }




    function cron__clean_metadatas($affirmation = null){

        /*
         *
         * A function that would run through all
         * object metadata variables and remove
         * all variables that are not indexed
         * as part of Variables Names entity @6232
         *
         * https://mench.com/entities/6232
         *
         *
         * */

        if($affirmation < 0){
            //Gateway URL to give option to run...
            die('<a href="/links/cron__clean_metadatas">Click here</a> to start running this function.');
        }

        boost_power();

        //Fetch all valid variable names:
        $valid_variables = array();
        foreach($this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 6232, //Variables Names
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity-to-Entity Links
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
            'LENGTH(ln_content) > 0' => null,
        ), array('en_child'), 0) as $var_name){
            array_push($valid_variables, $var_name['ln_content']);
        }

        //Now let's start the cleanup process...
        $invalid_variables = array();

        //Intent Metadata
        foreach($this->Intents_model->in_fetch(array()) as $in){

            if(strlen($in['in_metadata']) < 1){
                continue;
            }

            foreach(unserialize($in['in_metadata']) as $key => $value){
                if(!in_array($key, $valid_variables)){
                    //Remove this:
                    update_metadata('in', $in['in_id'], array(
                        $key => null,
                    ));

                    //Add to index:
                    if(!in_array($key, $invalid_variables)){
                        array_push($invalid_variables, $key);
                    }
                }
            }

        }

        //Entity Metadata
        foreach($this->Entities_model->en_fetch(array()) as $en){

            if(strlen($en['en_metadata']) < 1){
                continue;
            }

            foreach(unserialize($en['en_metadata']) as $key => $value){
                if(!in_array($key, $valid_variables)){
                    //Remove this:
                    update_metadata('en', $en['en_id'], array(
                        $key => null,
                    ));

                    //Add to index:
                    if(!in_array($key, $invalid_variables)){
                        array_push($invalid_variables, $key);
                    }
                }
            }

        }

        $ln_metadata = array(
            'invalid' => $invalid_variables,
            'valid' => $valid_variables,
        );

        if(count($invalid_variables) > 0){
            //Did we have anything to remove? Report with system bug:
            $this->Links_model->ln_create(array(
                'ln_content' => 'cron__clean_metadatas() removed '.count($invalid_variables).' unknown variables from intent/entity metadatas. To prevent this from happening, register the variables via Variables Names @6232',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_parent_entity_id' => 6232, //Variables Names
                'ln_metadata' => $ln_metadata,
            ));
        }

        echo_json($ln_metadata);

    }


    function toggle_advance(){

        //Toggles the advance session variable for the miner on/off for logged-in miners:
        $session_en = en_auth(array(1308));

        if($session_en){

            //Figure out new toggle state:
            $toggled_setting = ( $this->session->userdata('advance_view_enabled')==1 ? 0 : 1 );

            //Set session variable:
            $this->session->set_userdata('advance_view_enabled', $toggled_setting);

            //Log Link:
            $this->Links_model->ln_create(array(
                'ln_creator_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => 5007, //Toggled Advance Mode
                'ln_content' => 'Toggled '.( $toggled_setting ? 'ON' : 'OFF' ), //To be used when miner logs in again
            ));

            //Return to JS function:
            return echo_json(array(
                'status' => 1,
                'message' => 'Success',
            ));

        } else {

            //Show error:
            return echo_json(array(
                'status' => 0,
                'message' => 'Session expired. Login and try again.',
            ));

        }
    }


}