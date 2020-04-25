<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discover extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));

    }


    function index(){

        //My Bookmarks discovery List
        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH
        $session_en = superpower_assigned(null, true);


        //Fetch discovery list:
        $player_discoveries = $this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        ), array('in_previous'), 0, 0, array('ln_order' => 'ASC'));
        if(!count($player_discoveries)){
            //Nothing in their discovery list:
            return redirect_message('/');
        }

        //Log DISCOVER LIST View:
        $this->LEDGER_model->ln_create(array(
            'ln_type_source_id' => 4283, //Opened DISCOVER LIST
            'ln_creator_source_id' => $session_en['en_id'],
        ));


        $this->load->view('header', array(
            'title' => $en_all_2738[6205]['m_name'],
        ));

        $this->load->view('discover/discover_home', array(
            'session_en' => $session_en,
            'player_discoveries' => $player_discoveries,
        ));

        $this->load->view('footer');

    }


    function start($in_id){

        //Adds Idea to the Players Discovery List

        $session_en = superpower_assigned();

        //Check to see if added to DISCOVER LIST for logged-in users:
        if(!$session_en){
            return redirect_message('/source/sign/'.$in_id);
        }

        //Add this Idea to their DISCOVER LIST:
        if(!$this->DISCOVER_model->discover_start($session_en['en_id'], $in_id)){
            //Failed to add to discovery list:
            return redirect_message('/discover', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Failed to add idea to your discovery list.</div>');
        }

        //Go to this newly added idea:
        return redirect_message('/'.$in_id, '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Success! Continue Discovering Below...</div>');

    }

    function next($in_id = 0){

        $session_en = superpower_assigned();
        if(!$session_en){
            return redirect_message('/source/sign');
        }

        if($in_id > 0){

            //Fetch Idea:
            $ins = $this->IDEA_model->in_fetch(array(
                'in_id' => $in_id,
            ));


            //Should we check for auto next redirect if empty? Only if this is a selection:
            $append_url = null;
            if(in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7712'))){

                $append_url = '?check_if_empty=1';

            } elseif($ins[0]['in_type_source_id']==6677){

                //Mark as discover If not previously:
                $discover_completes = $this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
                    'ln_creator_source_id' => $session_en['en_id'],
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ));

                if(!count($discover_completes)){
                    $this->DISCOVER_model->discover_is_complete($ins[0], array(
                        'ln_type_source_id' => 4559, //DISCOVER MESSAGES
                        'ln_creator_source_id' => $session_en['en_id'],
                        'ln_previous_idea_id' => $ins[0]['in_id'],
                    ));
                }

            }


            //Find next Idea based on source's discovery list:
            $next_in_id = $this->DISCOVER_model->discover_next_find($session_en['en_id'], $ins[0]);
            if($next_in_id > 0){
                return redirect_message('/' . $next_in_id.$append_url);
            } else {
                $next_in_id = $this->DISCOVER_model->discover_next_go($session_en['en_id'], false);
                if($next_in_id > 0){
                    return redirect_message('/' . $next_in_id.$append_url);
                } else {
                    return redirect_message('/', '<div class="alert alert-info" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully discovered your entire list.</div></div>');
                }
            }

        } else {

            //Find the next idea in the DISCOVER LIST:
            $next_in_id = $this->DISCOVER_model->discover_next_go($session_en['en_id'], false);
            if($next_in_id > 0){
                return redirect_message('/' . $next_in_id);
            } else {
                return redirect_message('/', '<div class="alert alert-info" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully discovered your entire list.</div></div>');
            }

        }
    }

    function previous($previous_level_id, $in_id){


        return redirect_message('/'.$previous_level_id);


        //Fetch Idea:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
        ));


        //Should we check for auto next redirect if empty? Only if this is a selection:
        $append_url = null;
        if(in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7712'))){

            $append_url = '?check_if_empty=1';

        } elseif($ins[0]['in_type_source_id']==6677){

            //Mark as discover If not previously:
            $discover_completes = $this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_previous_idea_id' => $ins[0]['in_id'],
            ));

            if(!count($discover_completes)){
                $this->DISCOVER_model->discover_is_complete($ins[0], array(
                    'ln_type_source_id' => 4559, //DISCOVER MESSAGES
                    'ln_creator_source_id' => $session_en['en_id'],
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ));
            }

        }


        //Find next Idea based on source's discovery list:
        $next_in_id = $this->DISCOVER_model->discover_next_find($session_en['en_id'], $ins[0]);
        if($next_in_id > 0){
            return redirect_message('/' . $next_in_id.$append_url);
        } else {
            $next_in_id = $this->DISCOVER_model->discover_next_go($session_en['en_id'], false);
            if($next_in_id > 0){
                return redirect_message('/' . $next_in_id.$append_url);
            } else {
                return redirect_message('/', '<div class="alert alert-info" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully discovered your entire list.</div></div>');
            }
        }

    }

    function discover_in_history($tab_group_id, $note_in_id = 0, $owner_en_id = 0, $last_loaded_ln_id = 0){

        return echo_json($this->DISCOVER_model->discover_history_ui($tab_group_id, $note_in_id, $owner_en_id, $last_loaded_ln_id));

    }





    function discover_coin($in_id = 0)
    {

        /*
         *
         * Enables a Player to DISCOVER a IDEA
         * on the public web
         *
         * */

        //Fetch user session:
        $session_en = superpower_assigned();

        if(!$in_id){
            //Load the Starting Idea:
            $in_id = config_var(12156);
        }

        //Fetch data:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
        ));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Idea #' . $in_id . ' not found</div>');
        } elseif(!in_array($ins[0]['in_status_source_id'], $this->config->item('en_ids_7355') /* Idea Status Public */)){

            if(superpower_assigned(10939)){
                //Give them idea access:
                return redirect_message('/idea/' . $in_id);
            } else {
                //Inform them not published:
                return redirect_message('/', '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Cannot discover this idea because it\'s not published yet.</div>');
            }

        }

        $this->load->view('header', array(
            'title' => echo_in_title($ins[0], true),
            'in' => $ins[0],
        ));

        //Load specific view based on Idea Level:
        $this->load->view('discover/discover_coin', array(
            'in' => $ins[0],
            'session_en' => $session_en,
        ));

        $this->load->view('footer');

    }



    function discover_file_upload()
    {

        //TODO: MERGE WITH FUNCTION in_notes_create_upload()

        //Authenticate Player:
        $session_en = superpower_assigned();
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));

        } elseif (!isset($_POST['in_id'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing IDEA',
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

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(11063) * 1024 * 1024)) {

            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . config_var(11063) . ' MB.',
            ));

        }

        //Validate Idea:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }


        //Attempt to save file locally:
        $file_parts = explode('.', $_FILES[$_POST['upload_type']]["name"]);
        $temp_local = "application/cache/" . md5($file_parts[0] . $_FILES[$_POST['upload_type']]["type"] . $_FILES[$_POST['upload_type']]["size"]) . '.' . $file_parts[(count($file_parts) - 1)];
        move_uploaded_file($_FILES[$_POST['upload_type']]['tmp_name'], $temp_local);


        //Attempt to store in Mench Cloud on Amazon S3:
        if (isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type']) > 0) {
            $mime = $_FILES[$_POST['upload_type']]['type'];
        } else {
            $mime = mime_content_type($temp_local);
        }

        $cdn_status = upload_to_cdn($temp_local, $session_en['en_id'], $_FILES[$_POST['upload_type']], true, $ins[0]['in_title']);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return echo_json($cdn_status);
        }


        //Delete previous answer(s):
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //DISCOVER COIN
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
        )) as $discover_progress){
            $this->LEDGER_model->ln_update($discover_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Transaction Deleted
            ), $session_en['en_id'], 12129 /* DISCOVER ANSWER DELETED */);
        }

        //Save new answer:
        $new_message = '@'.$cdn_status['cdn_en']['en_id'];
        $this->DISCOVER_model->discover_is_complete($ins[0], array(
            'ln_type_source_id' => 12117,
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_content' => $new_message,
            'ln_profile_source_id' => $cdn_status['cdn_en']['en_id'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => '<div class="discover-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->COMMUNICATION_model->comm_message_send($new_message).'</div>',
        ));

    }




    function discover_text_answer(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['in_id']) || !intval($_POST['in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        } elseif (!isset($_POST['discover_text_answer']) || !strlen($_POST['discover_text_answer'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing text answer.',
            ));
        }

        //Validate/Fetch idea:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        }

        //Delete previous answer(s):
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //DISCOVER COIN
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
        )) as $discover_progress){
            $this->LEDGER_model->ln_update($discover_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Transaction Deleted
            ), $session_en['en_id'], 12129 /* DISCOVER ANSWER DELETED */);
        }

        //Save new answer:
        $this->DISCOVER_model->discover_is_complete($ins[0], array(
            'ln_type_source_id' => 6144,
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_content' => $_POST['discover_text_answer'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => 'Answer Saved',
        ));

    }


    function discover_answer(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['in_loaded_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing idea id.',
            ));
        } elseif (!isset($_POST['answered_ins']) || !is_array($_POST['answered_ins']) || !count($_POST['answered_ins'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Select an answer',
            ));
        }

        //Save answer:
        return echo_json($this->DISCOVER_model->discover_answer($session_en['en_id'], $_POST['in_loaded_id'], $_POST['answered_ins']));

    }





    function js_ln_create(){

        //Log link from JS source:
        if(isset($_POST['ln_order']) && strlen($_POST['ln_order'])>0 && !is_numeric($_POST['ln_order'])){
            //We have an order set, but its not an integer, which means it's a cookie name that needs to be analyzed:
            $_POST['ln_order'] = fetch_cookie_order($_POST['ln_order']);
        }

        //Log engagement:
        echo_json($this->LEDGER_model->ln_create($_POST));
    }






    function actionplan_reset_progress($en_id, $timestamp, $secret_key){

        if($secret_key != md5($en_id . $this->config->item('cred_password_salt') . $timestamp)){
            die('Invalid Secret Key');
        }

        //Fetch their current progress links:
        $progress_links = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12227')) . ')' => null,
            'ln_creator_source_id' => $en_id,
        ), array(), 0);

        if(count($progress_links) > 0){

            //Yes they did have some:
            $message = 'Removed '.count($progress_links).' idea'.echo__s(count($progress_links)).' from your discovery list.';

            //Log link:
            $clear_all_link = $this->LEDGER_model->ln_create(array(
                'ln_content' => $message,
                'ln_type_source_id' => 6415, //DISCOVER LIST Reset Discoveries
                'ln_creator_source_id' => $en_id,
            ));

            //Delete all progressions:
            foreach($progress_links as $progress_link){
                $this->LEDGER_model->ln_update($progress_link['ln_id'], array(
                    'ln_status_source_id' => 6173, //Transaction Deleted
                    'ln_parent_transaction_id' => $clear_all_link['ln_id'], //To indicate when it was deleted
                ), $en_id, 6415 /* User Cleared DISCOVER LIST */);
            }

        } else {

            //Nothing to do:
            $message = 'Your DISCOVER LIST was empty as there was nothing to delete';

        }

        //Show basic UI for now:
        return redirect_message('/discover', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');

    }


    function actionplan_stop_save(){

        /*
         *
         * When users indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and delete the idea from their
         * DISCOVER LIST.
         *
         * */


        if (!isset($_POST['js_pl_id']) || intval($_POST['js_pl_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid player ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing idea ID',
            ));
        }

        //Call function to delete form DISCOVER LIST:
        $delete_result = $this->DISCOVER_model->discover_delete($_POST['js_pl_id'], $_POST['in_id'], 6155); //REMOVED BOOKMARK

        if(!$delete_result['status']){
            return echo_json($delete_result);
        } else {
            return echo_json(array(
                'status' => 1,
            ));
        }
    }


    function actionplan_sort_save()
    {
        /*
         *
         * Saves the order of DISCOVER LIST ideas based on
         * user preferences.
         *
         * */

        if (!isset($_POST['js_pl_id']) || intval($_POST['js_pl_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid player ID',
            ));
        } elseif (!isset($_POST['new_actionplan_order']) || !is_array($_POST['new_actionplan_order']) || count($_POST['new_actionplan_order']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        }

        //Update the order of their DISCOVER LIST:
        $results = array();
        foreach($_POST['new_actionplan_order'] as $ln_order => $ln_id){
            if(intval($ln_id) > 0 && intval($ln_order) > 0){
                //Update order of this link:
                $results[$ln_order] = $this->LEDGER_model->ln_update(intval($ln_id), array(
                    'ln_order' => $ln_order,
                ), $_POST['js_pl_id'], 6132 /* Ideas Ordered by User */);
            }
        }

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => count($_POST['new_actionplan_order']).' Ideas Sorted',
        ));
    }


}