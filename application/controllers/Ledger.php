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
         * and Display statuses for intents, entities and
         * ledger transactions.
         *
         * */


        $session_en = fn___en_auth(array(1308)); //Just be logged in to browse

        //Load header:
        $this->load->view(($session_en ? 'view_shared/matrix_header' : 'view_shared/public_header'), array(
            'title' => 'Mench Ledger',
        ));

        //Load main:
        $this->load->view('view_ledger/ledger_ui');

        //Load footer:
        $this->load->view(($session_en ? 'view_shared/matrix_footer' : 'view_shared/public_footer'));

    }



    function fn___add_in_en_top_search(){

        //Authenticate Miner:
        $session_en = fn___en_auth(array(1308));

        if (!$session_en) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Sign In and try again',
            ));

        } elseif (!isset($_POST['raw_string'])) {

            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Missing Transaction ID',
            ));

        }

        //See if intent or entity:
        if(substr($_POST['raw_string'], 0, 1)=='#'){

            $in_outcome = trim(substr($_POST['raw_string'], 1));
            if(strlen($in_outcome)<2){
                return fn___echo_json(array(
                    'status' => 0,
                    'message' => 'Intent outcome must be at-least 2 characters long.',
                ));
            }

            //Create Intent:
            $added_in = $this->Matrix_model->fn___in_verify_create($in_outcome, $session_en['en_id']);
            if(!$added_in['status']){
                //We had an error, return it:
                return fn___echo_json($added_in);
            } else {
                return fn___echo_json(array(
                    'status' => 1,
                    'new_item_url' => '/intents/' . $added_in['in']['in_id'],
                ));
            }

        } elseif(substr($_POST['raw_string'], 0, 1)=='@'){

            //Create entity:
            $added_en = $this->Matrix_model->fn___en_verify_create(trim(substr($_POST['raw_string'], 1)), $session_en['en_id']);
            if(!$added_en['status']){
                //We had an error, return it:
                return fn___echo_json($added_en);
            } else {
                //Assign new entity:
                return fn___echo_json(array(
                    'status' => 1,
                    'new_item_url' => '/entities/' . $added_en['en']['en_id'],
                ));
            }

        } else {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid string. Must start with either # or @.',
            ));
        }
    }



    function fn___view_transaction_json($tr_id)
    {

        //Fetch transaction metadata and display it:
        $trs = $this->Database_model->fn___tr_fetch(array(
            'tr_id' => $tr_id,
        ));

        if (count($trs) < 1) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Invalid Transaction ID',
            ));
        } elseif(in_array($trs[0]['tr_type_entity_id'] , $this->config->item('en_ids_4755')) /* Transaction Type is locked */ && !fn___en_auth(array(1281)) /* Viewer NOT a moderator */){
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Transaction content visible to moderators only',
            ));
        } elseif(!fn___en_auth(array(1308)) /* Viewer NOT a miner */) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Transaction metadata visible to miners only',
            ));
        } else {

            //unserialize metadata if needed:
            if(strlen($trs[0]['tr_metadata']) > 0){
                $trs[0]['tr_metadata'] = unserialize($trs[0]['tr_metadata']);
            }

            //Print on scree:
            fn___echo_json($trs[0]);

        }
    }

    function load_w_actionplan()
    {

        //Auth user and check required variables:
        $session_en = fn___en_auth(array(1308)); //miners

        if (!$session_en) {
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
            'url' => '/my/actionplan/' . $w['tr_id'] . '/' . $w['tr_child_intent_id'],
        ));

    }


    function issue_certificate(){
        //TODO to view the student's history and issue a certificate
    }



    function dev_matrix_cache(){
        /*
     *
     * This function prepares a PHP-friendly text to be copied to matrix_cache.php
     * (which is auto loaded) to provide a cache image of some entities in
     * the tree for faster application processing.
     *
     * */

        //First first all entities that have Cache in PHP Config @4527 as their parent:
        $config_ens = $this->Database_model->fn___tr_fetch(array(
            'tr_status' => 2,
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'tr_parent_entity_id' => 4527,
        ), array('en_child'), 0);

        echo '//Generated '.date("Y-m-d H:i:s").' PST<br />';

        foreach($config_ens as $en){

            //Now fetch all its children:
            $children = $this->Database_model->fn___tr_fetch(array(
                'tr_status' => 2, //Published
                'en_status' => 2, //Published
                'tr_parent_entity_id' => $en['tr_child_entity_id'],
                'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            ), array('en_child'), 0, 0, array('tr_order' => 'ASC', 'en_id' => 'ASC'));


            $child_ids = array();
            foreach($children as $child){
                array_push($child_ids , $child['en_id']);
            }

            echo '<br />//'.$en['en_name'].':<br />';
            echo '$config[\'en_ids_'.$en['tr_child_entity_id'].'\'] = array('.join(', ',$child_ids).');<br />';
            echo '$config[\'en_all_'.$en['tr_child_entity_id'].'\'] = array(<br />';
            foreach($children as $child){

                //Do we have an omit command?
                if(substr_count($en['tr_content'], '&var_trimcache=') == 1){
                    $child['en_name'] = trim(str_replace( str_replace('&var_trimcache=','',$en['tr_content']) , '', $child['en_name']));
                }

                //Fetch all parents for this child:
                $child_parent_ids = array(); //To be populated soon
                $child_parents = $this->Database_model->fn___tr_fetch(array(
                    'tr_status' => 2, //Published
                    'en_status' => 2, //Published
                    'tr_child_entity_id' => $child['en_id'],
                    'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                ), array('en_parent'), 0);
                foreach($child_parents as $cp_en){
                    array_push($child_parent_ids, $cp_en['en_id']);
                }

                echo '&nbsp;&nbsp;&nbsp;&nbsp; '.$child['en_id'].' => array(<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_icon\' => \''.htmlentities($child['en_icon']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_name\' => \''.$child['en_name'].'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_desc\' => \''.str_replace('\'','\\\'',$child['tr_content']).'\',<br />';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\'m_parents\' => array('.join(', ',$child_parent_ids).'),<br />';

                echo '&nbsp;&nbsp;&nbsp;&nbsp; ),<br />';

            }
            echo ');<br />';
        }
    }

    function dev_php_info(){
        echo phpinfo();
    }

    function cron__sync_algolia($input_obj_type = null, $input_obj_id = null){
        //Call the update function and passon possible values:
        fn___echo_json($this->Database_model->fn___update_algolia($input_obj_type, $input_obj_id));
    }

    function fn___moderate($action = null, $command1 = null, $command2 = null)
    {

        //Validate moderator:
        $session_en = fn___en_auth(array(1281), true);

        //Load tools:
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Moderation Tools',
        ));
        $this->load->view('view_ledger/moderator_tools' , array(
            'action' => $action,
            'command1' => $command1,
            'command2' => $command2,
            'session_en' => $session_en,
        ));
        $this->load->view('view_shared/matrix_footer');
    }


    function dev_reset_coins(){

        exit; //Maybe use to update all rates if needed?

        //Issue coins for each transaction type:
        $all_engs = $this->Database_model->fn___tr_fetch(array(), array('en_type'), 0, 0, array('trs_count' => 'DESC'), 'COUNT(tr_type_entity_id) as trs_count, en_name, tr_type_entity_id', 'tr_type_entity_id, en_name');

        //return fn___echo_json($all_engs);

        //Give option to select:
        foreach ($all_engs as $tr) {

            //DOes it have a rate?
            $rate_trs = $this->Database_model->fn___tr_fetch(array(
                'tr_status' => 2, //Published
                'en_status' => 2, //Published
                'tr_type_entity_id' => 4319, //Number
                'tr_parent_entity_id' => 4374, //Mench Coins
                'tr_child_entity_id' => $tr['tr_type_entity_id'],
            ), array('en_child'), 1);

            if(count($rate_trs) > 0){
                //Issue coins at this rate:
                $this->db->query("UPDATE table_ledger SET tr_coins = '".$rate_trs[0]['tr_content']."' WHERE tr_type_entity_id = " . $tr['tr_type_entity_id']);
            }

        }

        echo 'done';

    }


    function cron__process_4299()
    {

        /*
         *
         * Every time we receive a media file from Facebook
         * we need to upload it to our own CDNs using the
         * short-lived URL provided by Facebook so we can
         * access it indefinitely without restriction.
         * This process is managed by creating a @4299
         * Transaction Type which this cron job grabs and
         * uploads to Mench CDN.
         *
         * Runs every minute with the cron job.
         *
         * */

        $tr_pending = $this->Database_model->fn___tr_fetch(array(
            'tr_status' => 0, //Pending
            'tr_type_entity_id' => 4299, //Requested Photo Storage
        ), array('en_miner'), 20); //Max number of scans per run


        //Quickly set transaction statuses to drafting so other Cron jobs don't pick them up:
        foreach ($tr_pending as $tr) {
            $this->Database_model->fn___tr_update($tr['tr_id'], array(
                'tr_status' => 1, //Drafting
            ));
        }

        //Now go through and upload to CDN:
        foreach ($tr_pending as $tr) {

            //Save photo to S3 if content is URL
            $new_file_url = (filter_var($tr['tr_content'], FILTER_VALIDATE_URL) ? fn___upload_to_cdn($tr['tr_content'], $tr) : false);

            if(!$new_file_url){

                //Ooopsi, there was an error:
                $this->Database_model->fn___tr_create(array(
                    'tr_content' => 'cron__sync_file_to_cdn() failed to store file in CDN',
                    'tr_type_entity_id' => 4246, //Platform Error
                    'tr_parent_transaction_id' => $tr['tr_id'],
                ));

                //Archive this:
                $this->Database_model->fn___tr_update($tr['tr_id'], array(
                    'tr_status' => -1, //Removed
                ));

                continue;
            }

            //Update entity icon if not already set:
            $tr_child_entity_id = 0;
            if (strlen($tr['en_icon'])<1) {

                //Update Cover ID:
                $this->Database_model->fn___en_update($tr['en_id'], array(
                    'en_icon' => '<img src="' . $new_file_url . '">',
                ), true, $tr['en_id']);

                //Link transaction to entity:
                $tr_child_entity_id = $tr['en_id'];

            }

            //Update transaction:
            $this->Database_model->fn___tr_update($tr['tr_id'], array(
                'tr_status' => 2, //Publish
                'tr_content' => null, //Remove URL from content to indicate its done
                'tr_child_entity_id' => $tr_child_entity_id,
                'tr_metadata' => array(
                    'original_url' => $tr['tr_content'],
                    'cdn_url' => $new_file_url,
                ),
            ));

        }

        fn___echo_json($tr_pending);
    }



    function fn___cron__sync_file_to_messenger()
    {

        /*
         * This cron job looks for all requests to sync
         * Media files with Facebook so we can instantly
         * deliver them over Messenger.
         *
         * Runs every minute with the cron job.
         *
         */

        $max_per_batch = 20; //Max number of syncs per cron run
        $success_count = 0; //Track success
        $fb_convert_4537 = $this->config->item('fb_convert_4537'); //Supported Media Types
        $tr_metadata = array();


        //Let's fetch all Media files without a Facebook attachment ID:
        $pending_urls = $this->Database_model->fn___tr_fetch(array(
            'tr_type_entity_id IN (' . join(',',array_keys($fb_convert_4537)) . ')' => null,
            'tr_metadata' => null, //Missing Facebook Attachment ID
        ), array(), $max_per_batch, 0 , array('tr_id' => 'ASC')); //Sort by oldest added first

        foreach ($pending_urls as $tr) {

            $payload = array(
                'message' => array(
                    'attachment' => array(
                        'type' => $fb_convert_4537[$tr['tr_type_entity_id']],
                        'payload' => array(
                            'is_reusable' => true,
                            'url' => $tr['tr_content'], //The URL to the media file
                        ),
                    ),
                )
            );

            //Attempt to sync Media to Facebook:
            $result = $this->Chat_model->fn___facebook_graph('POST', '/me/message_attachments', $payload);
            $db_result = false;

            if ($result['status'] && isset($result['tr_metadata']['result']['attachment_id'])) {

                //Save Facebook Attachment ID to DB:
                $db_result = $this->Matrix_model->fn___metadata_update('tr', $tr['tr_id'], array(
                    'fb_att_id' => intval($result['tr_metadata']['result']['attachment_id']),
                ));

            }

            //Did it go well?
            if ($db_result) {

                $success_count++;

            } else {

                //Log error:
                $this->Database_model->fn___tr_create(array(
                    'tr_type_entity_id' => 4246, //Platform Error
                    'tr_content' => 'fn___facebook_attachment_sync() Failed to sync attachment using Facebook API',
                    'tr_metadata' => array(
                        'payload' => $payload,
                        'result' => $result,
                    ),
                ));

                //Also disable future attempts for this transaction:
                $db_result = $this->Matrix_model->fn___metadata_update('tr', $tr['tr_id'], array(
                    'fb_att_id_failed' => true,
                ));

            }

            //Save stats:
            array_push($tr_metadata, array(
                'payload' => $payload,
                'fb_result' => $result,
            ));

        }

        //Echo message:
        fn___echo_json(array(
            'status' => ($success_count == count($pending_urls) && $success_count > 0 ? 1 : 0),
            'message' => $success_count . '/' . count($pending_urls) . ' synced using Facebook Attachment API',
            'tr_metadata' => $tr_metadata,
        ));

    }

    function cron__sync_gephi(){

        /*
         *
         * Populates the nodes and edges table for
         * Gephi https://gephi.org network visualizer
         *
         * */


        //Boost processing power:
        fn___boost_power();

        //Empty both tables:
        $this->db->query("TRUNCATE TABLE public.gephi_edges CONTINUE IDENTITY RESTRICT;");
        $this->db->query("TRUNCATE TABLE public.gephi_nodes CONTINUE IDENTITY RESTRICT;");

        //Load intent link types:
        $en_all_4594 = $this->config->item('en_all_4594');

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
        $ins = $this->Database_model->fn___in_fetch(array('in_status >=' => 0));
        foreach($ins as $in){

            //Prep metadata:
            $in_metadata = ( strlen($in['in_metadata']) > 0 ? unserialize($in['in_metadata']) : array());

            //Add intent node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['in'].$in['in_id'],
                'label' => $in['in_outcome'],
                //'size' => ( isset($in_metadata['in__tree_max_seconds']) ? round(($in_metadata['in__tree_max_seconds']/3600),0) : 0 ), //Max time
                'size' => ( $in['in_id']==$this->config->item('in_mission_id') ? 3 * $node_size['in'] : $node_size['in'] ),
                'node_type' => 1, //Intent
                'node_status' => $in['in_status'],
            ));

            //Fetch children:
            foreach($this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 0, //New+
                'in_status >=' => 0, //New+
                'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                'tr_parent_intent_id' => $in['in_id'],
            ), array('in_child'), 0, 0) as $in_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$in_child['tr_parent_intent_id'],
                    'target' => $id_prefix['in'].$in_child['tr_child_intent_id'],
                    'label' => $en_all_4594[$in_child['tr_type_entity_id']]['m_name'], //TODO maybe give visibility to points/condition here?
                    'weight' => 1, //TODO Maybe update later?
                    'edge_type_en_id' => $in_child['tr_type_entity_id'],
                    'edge_status' => $in_child['tr_status'],
                ));

            }
        }


        //Add entities:
        $ens = $this->Database_model->fn___en_fetch(array('en_status >=' => 0));
        foreach($ens as $en){

            //Add entity node:
            $this->db->insert('gephi_nodes', array(
                'id' => $id_prefix['en'].$en['en_id'],
                'label' => $en['en_name'],
                'size' => ( $en['en_id']==$this->config->item('en_top_focus_id') ? 3 * $node_size['en'] : $node_size['en'] ),
                'node_type' => 2, //Entity
                'node_status' => $en['en_status'],
            ));

            //Fetch children:
            foreach($this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 0, //New+
                'en_status >=' => 0, //New+
                'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'tr_parent_entity_id' => $en['en_id'],
            ), array('en_child'), 0, 0) as $en_child){

                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$en_child['tr_parent_entity_id'],
                    'target' => $id_prefix['en'].$en_child['tr_child_entity_id'],
                    'label' => $en_all_4594[$en_child['tr_type_entity_id']]['m_name'].': '.$en_child['tr_content'],
                    'weight' => 1, //TODO Maybe update later?
                    'edge_type_en_id' => $en_child['tr_type_entity_id'],
                    'edge_status' => $en_child['tr_status'],
                ));

            }
        }

        //Add messages:
        $messages = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'in_status >=' => 0, //New+
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
            //'tr_type_entity_id' => 4231, //Intent Messages only
        ), array('in_child'), 0, 0);
        foreach($messages as $message) {

            //Add message node:
            $this->db->insert('gephi_nodes', array(
                'id' => $message['tr_id'],
                'label' => $en_all_4594[$message['tr_type_entity_id']]['m_name'] . ': ' . $message['tr_content'],
                'size' => $node_size['msg'],
                'node_type' => $message['tr_type_entity_id'], //Message type
                'node_status' => $message['tr_status'],
            ));

            //Add child intent link:
            $this->db->insert('gephi_edges', array(
                'source' => $message['tr_id'],
                'target' => $id_prefix['in'].$message['tr_child_intent_id'],
                'label' => 'Child Intent',
                'weight' => 1, //TODO Maybe update later?
            ));

            //Add parent intent link?
            if ($message['tr_parent_intent_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['in'].$message['tr_parent_intent_id'],
                    'target' => $message['tr_id'],
                    'label' => 'Parent Intent',
                    'weight' => 1, //TODO Maybe update later?
                ));
            }

            //Add parent entity link?
            if ($message['tr_parent_entity_id'] > 0) {
                $this->db->insert('gephi_edges', array(
                    'source' => $id_prefix['en'].$message['tr_parent_entity_id'],
                    'target' => $message['tr_id'],
                    'label' => 'Parent Entity',
                    'weight' => 1, //TODO Maybe update later?
                ));
            }

        }

        echo count($ins).' intents & '.count($ens).' entities & '.count($messages).' messages synced.';
    }

}