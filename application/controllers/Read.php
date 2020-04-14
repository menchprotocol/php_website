<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Read extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }


    function index(){

        //My Bookmarks Reading List
        $session_en = superpower_assigned(null, true);
        $player_reads = array();


        if($session_en){
            //Fetch reading list:
            $player_reads = $this->READ_model->ln_fetch(array(
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Idea Set
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));
            if(!count($player_reads)){
                //Nothing in their reading list:
                return redirect_message('/');
            }

            //Log 🔴 READING LIST View:
            $this->READ_model->ln_create(array(
                'ln_type_source_id' => 4283, //Opened 🔴 READING LIST
                'ln_creator_source_id' => $session_en['en_id'],
            ));
        }


        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH
        $this->load->view('header', array(
            'title' => $en_all_2738[6205]['m_name'],
        ));

        $this->load->view('read/read_bookmarks', array(
            'session_en' => $session_en,
            'player_reads' => $player_reads,
        ));

        $this->load->view('footer');

    }


    function start($in_id){

        //Adds a Read to the Players Read Bookmarks List

        $session_en = superpower_assigned();

        //Check to see if added to READING LIST for logged-in users:
        if(!isset($session_en['en_id'])){
            return redirect_message('/source/sign/'.$in_id);
        }

        //Add this Idea to their READING LIST:
        if(!$this->READ_model->read_start($session_en['en_id'], $in_id)){
            //Failed to add to reading list:
            return redirect_message('/read', '<div class="alert alert-danger" role="alert">Failed to add idea to your reading list.</div>');
        }

        //Go to this newly added read:
        return redirect_message('/'.$in_id, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-check-circle read"></i></span>Added to your reading list. Continue below...</div>');

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

                //Mark as read If not already:
                $read_completes = $this->READ_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                    'ln_creator_source_id' => $session_en['en_id'],
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ));

                if(!count($read_completes)){
                    $this->READ_model->read_is_complete($ins[0], array(
                        'ln_type_source_id' => 4559, //READ MESSAGES
                        'ln_creator_source_id' => $session_en['en_id'],
                        'ln_previous_idea_id' => $ins[0]['in_id'],
                    ));
                }

            }


            //Find next Idea based on source's reading list:
            $next_in_id = $this->READ_model->read_next_find($session_en['en_id'], $ins[0]);
            if($next_in_id > 0){
                return redirect_message('/' . $next_in_id.$append_url);
            } else {
                $next_in_id = $this->READ_model->read_next_go($session_en['en_id'], false);
                if($next_in_id > 0){
                    return redirect_message('/' . $next_in_id.$append_url);
                } else {
                    return redirect_message('/', '<div class="alert alert-danger" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully read your entire reading list.</div></div>');
                }
            }

        } else {

            //Find the next idea in the READING LIST to skip:
            $next_in_id = $this->READ_model->read_next_go($session_en['en_id'], false);
            if($next_in_id > 0){
                return redirect_message('/' . $next_in_id);
            } else {
                return redirect_message('/', '<div class="alert alert-danger" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully read your entire reading list.</div></div>');
            }

        }
    }

    function read_in_history($tab_group_id, $pads_in_id = 0, $owner_en_id = 0, $last_loaded_ln_id = 0){

        return echo_json($this->READ_model->read_history_ui($tab_group_id, $pads_in_id, $owner_en_id, $last_loaded_ln_id));

    }





    function read_coin($in_id = 0)
    {

        /*
         *
         * Enables a Player to READ a IDEA
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
            return redirect_message('/', '<div class="alert alert-danger" role="alert">Idea #' . $in_id . ' not found</div>');
        } elseif(!in_array($ins[0]['in_status_source_id'], $this->config->item('en_ids_7355') /* Idea Status Public */)){

            if(superpower_assigned(10939)){
                //Give them idea access:
                return redirect_message('/idea/' . $in_id);
            } else {
                //Inform them not published:
                return redirect_message('/', '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>Cannot read this idea because it\'s not published yet.</div>');
            }

        }

        $this->load->view('header', array(
            'title' => echo_in_title($ins[0], true).' | READ',
            'in' => $ins[0],
        ));

        //Load specific view based on Idea Level:
        $this->load->view('read/read_coin', array(
            'in' => $ins[0],
            'session_en' => $session_en,
        ));

        $this->load->view('footer');

    }



    function read_file_upload()
    {

        //TODO: MERGE WITH FUNCTION in_pads_create_upload()

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
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
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
        )) as $read_progress){
            $this->READ_model->ln_update($read_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Removed
            ), $session_en['en_id'], 12129 /* READ ANSWER ARCHIVED */);
        }

        //Save new answer:
        $new_message = '@'.$cdn_status['cdn_en']['en_id'];
        $this->READ_model->read_is_complete($ins[0], array(
            'ln_type_source_id' => 12117,
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_content' => $new_message,
            'ln_parent_source_id' => $cdn_status['cdn_en']['en_id'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => '<div class="read-topic"><span class="icon-block-sm">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->READ_model->dispatch_message($new_message).'</div>',
        ));

    }




    function read_text_answer(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session, login and try again',
            ));
        } elseif (!isset($_POST['in_id']) || !intval($_POST['in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        } elseif (!isset($_POST['read_text_answer']) || !strlen($_POST['read_text_answer'])) {
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
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
        )) as $read_progress){
            $this->READ_model->ln_update($read_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Removed
            ), $session_en['en_id'], 12129 /* READ ANSWER ARCHIVED */);
        }

        //Save new answer:
        $this->READ_model->read_is_complete($ins[0], array(
            'ln_type_source_id' => 6144,
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_content' => $_POST['read_text_answer'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => 'Answer Saved',
        ));

    }


    function read_answer(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session, login and try again',
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
        return echo_json($this->READ_model->read_answer($session_en['en_id'], $_POST['in_loaded_id'], $_POST['answered_ins']));

    }





    function read_stats(){


        //Ideas

        $en_all_7302 = $this->config->item('en_all_7302'); //Idea Stats


        //Idea Status:
        echo '<table class="table table-sm table-striped stats-table mini-stats-table in_statuses">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7302[4737]['m_name'].echo__s(count($this->config->item('en_all_4737')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_4737') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->IDEA_model->in_fetch(array(
                'in_status_source_id' => $en_id
            ), 0, 0, array(), 'COUNT(in_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/source/'.$en_id.'">' . $m['m_name'] . '</a></td>';

            echo '<td style="text-align: right;">' . '<a href="/ledger?in_status_source_id=' . $en_id . '&ln_type_source_id=4250">' . number_format($objects_count[0]['totals'],0) .'</a></td>';

            echo '</tr>';

        }
        echo '</table>';





        //Count all Idea Subtypes:
        $in_types_counts = $this->IDEA_model->in_fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ), 0, 0, array(), 'COUNT(in_type_source_id) as total_count, en_name, en_icon, en_id', 'en_id, en_name, en_icon');

        //Count totals:
        $addup_count = addup_array($in_types_counts, 'total_count');

        //Link Stages
        echo_2level_stats($en_all_7302[10602]['m_name'], 10602, 7585, $in_types_counts, $addup_count, 'in_type_source_id', 'total_count');





        //Players
        $en_all_7303 = $this->config->item('en_all_7303'); //Platform Dashboard
        $en_all_6177 = $this->config->item('en_all_6177'); //Source Status





        //Source Status
        echo '<table class="table table-sm table-striped stats-table mini-stats-table source_statuses '.superpower_active(10967).'">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7303[6177]['m_name'].echo__s(count($this->config->item('en_all_6177')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_6177') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->SOURCE_model->en_fetch(array(
                'en_status_source_id' => $en_id
            ), 0, 0, array(), 'COUNT(en_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/source/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">' . '<a href="/ledger?en_status_source_id=' . $en_id . '&ln_type_source_id=4251">' . number_format($objects_count[0]['totals'], 0) . '</a>' . '</td>';
            echo '</tr>';

        }
        echo '</table>';






        //Expert Sources
        $expert_sources_unpublished = ''; //Saved the UI for later view...
        $expert_sources_published = ''; //Saved the UI for later view...
        $total_counts = array();
        foreach ($this->config->item('en_all_3000') as $en_id => $m) {

            $expert_source_statuses = '';
            unset($total_counts);
            $total_counts = array();

            //Count totals for each active status:
            foreach($this->config->item('en_all_7358') /* Source Active Status */ as $en_status_source_id => $m_status){

                //Count this type:
                $source_count = $this->SOURCE_model->en_child_count($en_id, array($en_status_source_id)); //Count completed

                //Addup count:
                if(isset($total_counts[$en_status_source_id])){
                    $total_counts[$en_status_source_id] += $source_count;
                } else {
                    $total_counts[$en_status_source_id] = $source_count;
                }


                if(isset($total_counts[$en_status_source_id])){
                    $total_counts[$en_status_source_id] += $source_count;
                } else {
                    $total_counts[$en_status_source_id] = $source_count;
                }

                //Display row:
                $expert_source_statuses .= '<td style="text-align: right;"'.( $en_status_source_id != 6181 /* Player Featured */ ? ' class="' . superpower_active(10967) . '"' : '' ).'><a href="/source/' . $en_id .'#status-'.$en_status_source_id.'">'.number_format($source_count,0).'</a></td>';

            }

            //Echo stats:
            $expert_sources = '<tr class="' .( !$total_counts[6181] ? superpower_active(10967) : '' ) . '">';
            $expert_sources .= '<td style="text-align: left;"><span class="icon-block">'.$m['m_icon'].'</span><a href="/source/'.$en_id.'">'.$m['m_name'].'</a></td>';
            $expert_sources .= $expert_source_statuses;
            $expert_sources .= '</tr>';

            if($total_counts[6181]){
                $expert_sources_published .= $expert_sources;
            } else {
                $expert_sources_unpublished .= $expert_sources;
            }

        }

        echo '<table class="table table-sm table-striped stats-table">';

        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;">'.$en_all_7303[3000]['m_name'].' ['.number_format($total_counts[6181], 0).']</td>';
        foreach($this->config->item('en_all_7358') /* Source Active Status */ as $en_status_source_id => $m_status){
            if($en_status_source_id == 6181 /* Source Published */){
                echo '<td style="text-align:right;"><div class="' . superpower_active(10967) . '">' . $en_all_6177[$en_status_source_id]['m_name'] . '</div></td>';
            } else {
                echo '<td style="text-align:right;" class="' . superpower_active(10967) . '">' . $en_all_6177[$en_status_source_id]['m_name'] . '</td>';
            }
        }
        echo '</tr>';


        echo $expert_sources_published;
        echo $expert_sources_unpublished;


        echo '<tr style="font-weight: bold;" class="'.superpower_active(10967).'">';
        echo '<td style="text-align: left;"><span class="icon-block"><i class="fas fa-asterisk"></i></span>Totals</td>';
        foreach($this->config->item('en_all_7358') /* Source Active Status */ as $en_status_source_id => $m_status){
            echo '<td style="text-align: right;" '.( $en_status_source_id != 6181 /* Player Featured */ ? ' class="' . superpower_active(10967) . '"' : '' ).'>' . number_format($total_counts[$en_status_source_id], 0) . '</td>';
        }
        echo '</tr>';


        echo '</table>';








        //READ


        $en_all_4593 = $this->config->item('en_all_4593'); //Load all link types
        $en_all_7304 = $this->config->item('en_all_7304'); //Link Stats


        //READ Status:
        echo '<table class="table table-sm table-striped stats-table mini-stats-table link_statuses">';
        echo '<tr class="panel-title down-border">';
        echo '<td style="text-align: left;" colspan="2">'.$en_all_7304[6186]['m_name'].echo__s(count($this->config->item('en_all_6186')), true).'</td>';
        echo '</tr>';
        foreach ($this->config->item('en_all_6186') as $en_id => $m) {

            //Count this status:
            $objects_count = $this->READ_model->ln_fetch(array(
                'ln_status_source_id' => $en_id
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

            //Display this status count:
            echo '<tr>';
            echo '<td style="text-align: left;"><span class="icon-block">' . $m['m_icon'] . '</span><a href="/source/'.$en_id.'">' . $m['m_name'] . '</a></td>';
            echo '<td style="text-align: right;">';
            echo '<a href="/ledger?ln_status_source_id=' . $en_id . '">' . number_format($objects_count[0]['totals'],0) . '</a>';
            echo '</td>';
            echo '</tr>';

        }

        echo '</table>';






        //Count all rows:
        $link_types_counts = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        ), array('en_type'), 0, 0, array(), 'COUNT(ln_id) as total_count, en_name, en_icon, en_id', 'en_id, en_name, en_icon');

        //Count totals:
        $addup_count = addup_array($link_types_counts, 'total_count');

        //Link Direction
        echo_2level_stats('Types', 2738, 4593, $link_types_counts, $addup_count, 'ln_type_source_id', 'total_count');


    }



    function js_ln_create(){

        //Log link from JS source:
        if(isset($_POST['ln_order']) && strlen($_POST['ln_order'])>0 && !is_numeric($_POST['ln_order'])){
            //We have an order set, but its not an integer, which means it's a cookie name that needs to be analyzed:
            $_POST['ln_order'] = fetch_cookie_order($_POST['ln_order']);
        }

        //Log engagement:
        echo_json($this->READ_model->ln_create($_POST));
    }






    function actionplan_reset_progress($en_id, $timestamp, $secret_key){

        if($secret_key != md5($en_id . $this->config->item('cred_password_salt') . $timestamp)){
            die('Invalid Secret Key');
        }

        //Fetch their current progress links:
        $progress_links = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12227')) . ')' => null,
            'ln_creator_source_id' => $en_id,
        ), array(), 0);

        if(count($progress_links) > 0){

            //Yes they did have some:
            $message = 'Removed '.count($progress_links).' read'.echo__s(count($progress_links)).' from your list.';

            //Log link:
            $clear_all_link = $this->READ_model->ln_create(array(
                'ln_content' => $message,
                'ln_type_source_id' => 6415, //🔴 READING LIST Reset Reads
                'ln_creator_source_id' => $en_id,
            ));

            //Remove all progressions:
            foreach($progress_links as $progress_link){
                $this->READ_model->ln_update($progress_link['ln_id'], array(
                    'ln_status_source_id' => 6173, //Link Removed
                    'ln_parent_transaction_id' => $clear_all_link['ln_id'], //To indicate when it was removed
                ), $en_id, 6415 /* User Cleared 🔴 READING LIST */);
            }

        } else {

            //Nothing to do:
            $message = 'Your 🔴 READING LIST was already empty as there was nothing to delete';

        }

        //Show basic UI for now:
        return redirect_message('/read', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');

    }


    function actionplan_stop_save(){

        /*
         *
         * When users indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and remove the idea from their
         * 🔴 READING LIST.
         *
         * */


        if (!isset($_POST['js_pl_id']) || intval($_POST['js_pl_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid trainer ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing idea ID',
            ));
        }

        //Call function to remove form 🔴 READING LIST:
        $delete_result = $this->READ_model->read_delete($_POST['js_pl_id'], $_POST['in_id'], 6155); //READER REMOVED BOOKMARK

        if(!$delete_result['status']){
            return echo_json($delete_result);
        } else {
            return echo_json(array(
                'status' => 1,
            ));
        }
    }


    function actionplan_skip_preview($en_id, $in_id)
    {

        //Just give them an overview of what they are about to skip:
        return echo_json(array(
            'skip_step_preview' => 'WARNING: '.$this->READ_model->read_skip_initiate($en_id, $in_id, false).' Are you sure you want to skip?',
        ));

    }

    function actionplan_skip_apply($en_id, $in_id)
    {

        //Actually go ahead and skip
        $this->READ_model->read_skip_apply($en_id, $in_id, false);
        //Assume its all good!

        //We actually skipped, draft message:
        $message = '<div class="alert alert-danger" role="alert">I successfully skipped selected steps.</div>';

        //Find the next item to navigate them to:
        $next_in_id = $this->READ_model->read_next_go($en_id, false);
        if ($next_in_id > 0) {
            return redirect_message('/' . $next_in_id, $message);
        } else {
            return redirect_message('/read', $message);
        }

    }

    function actionplan_sort_save()
    {
        /*
         *
         * Saves the order of 🔴 READING LIST ideas based on
         * user preferences.
         *
         * */

        if (!isset($_POST['js_pl_id']) || intval($_POST['js_pl_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid trainer ID',
            ));
        } elseif (!isset($_POST['new_actionplan_order']) || !is_array($_POST['new_actionplan_order']) || count($_POST['new_actionplan_order']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        }

        //Update the order of their 🔴 READING LIST:
        $results = array();
        foreach($_POST['new_actionplan_order'] as $ln_order => $ln_id){
            if(intval($ln_id) > 0 && intval($ln_order) > 0){
                //Update order of this link:
                $results[$ln_order] = $this->READ_model->ln_update(intval($ln_id), array(
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


    function debug($in_id){

        $session_en = superpower_assigned();
        if(!isset($session_en['en_id'])){
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        }


        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ));

        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Public Idea not found',
            ));
        }

        //List the idea:
        return echo_json(array(
            'in_user' => array(
                'next_in_id' => $this->READ_model->read_next_find($session_en['en_id'], $ins[0]),
                'progress' => $this->READ_model->read__completion_progress($session_en['en_id'], $ins[0]),
                'marks' => $this->READ_model->read__completion_marks($session_en['en_id'], $ins[0]),
            ),
            'in_general' => array(
                'recursive_parents' => $this->IDEA_model->in_fetch_recursive_parents($ins[0]['in_id']),
                'common_base' => $this->IDEA_model->in_metadata_common_base($ins[0]),
            ),
        ));

    }

}