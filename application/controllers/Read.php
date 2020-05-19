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

        //My Reads
        $session_en = superpower_assigned(null, true);
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

        //Log home View:
        $this->READ_model->create(array(
            'ln_type_source_id' => 4283, //Opened Reads
            'ln_creator_source_id' => $session_en['en_id'],
        ));

        $this->load->view('header', array(
            'title' => $en_all_11035[12969]['m_name'],
        ));
        $this->load->view('read/read_home', array(
            'session_en' => $session_en,
        ));
        $this->load->view('footer');

    }


    function ledger(){

        /*
         *
         * List all Links on reverse chronological order
         * and Display Status for ideas, sources and
         * links.
         *
         * */

        //Load header:
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

        $this->load->view('header', array(
            'title' => $en_all_11035[4341]['m_name'],
        ));
        $this->load->view('read/read_ledger');
        $this->load->view('footer');

    }

    function ledger_load(){

        /*
         * Loads the list of links based on the
         * filters passed on.
         *
         * */

        $filters = unserialize($_POST['link_filters']);
        $join_by = unserialize($_POST['link_join_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $query_offset = (($page_num-1)*config_var(11064));
        $session_en = superpower_assigned();

        $message = '';

        //Fetch links and total link counts:
        $lns = $this->READ_model->fetch($filters, $join_by, config_var(11064), $query_offset);
        $lns_count = $this->READ_model->fetch($filters, $join_by, 0, 0, array(), 'COUNT(ln_id) as total_count');
        $total_items_loaded = ($query_offset+count($lns));
        $has_more_links = ($lns_count[0]['total_count'] > 0 && $total_items_loaded < $lns_count[0]['total_count']);


        //Display filter:
        if($total_items_loaded > 0){
            $message .= '<div class="montserrat ledger-info"><span class="icon-block"><i class="fas fa-file-search"></i></span>'.( $has_more_links && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) . number_format($lns_count[0]['total_count'] , 0) .' TRANSACTIONS:</div>';
        }


        if(count($lns)>0){

            $message .= '<div class="list-group list-grey">';
            foreach($lns as $ln) {

                $message .= view_ln($ln);

                if($session_en && strlen($ln['ln_content'])>0 && strlen($_POST['ln_content_search'])>0 && strlen($_POST['ln_content_replace'])>0 && substr_count($ln['ln_content'], $_POST['ln_content_search'])>0){

                    $new_content = str_replace($_POST['ln_content_search'],trim($_POST['ln_content_replace']),$ln['ln_content']);

                    $this->READ_model->update($ln['ln_id'], array(
                        'ln_content' => $new_content,
                    ), $session_en['en_id'], 12360, update_description($ln['ln_content'], $new_content));

                    $message .= '<div class="alert alert-info" role="alert"><i class="fas fa-check-circle"></i> Replaced ['.$_POST['ln_content_search'].'] with ['.trim($_POST['ln_content_replace']).']</div>';

                }

            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_links){
                $message .= '<div id="link_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn btn-read" onclick="ledger_load(link_filters, link_join_by, '.$next_page.');"><span class="icon-block"><i class="fas fa-plus-circle"></i></span>Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><span class="icon-block"><i class="far fa-check-circle"></i></span>All '.$lns_count[0]['total_count'].' link'.view__s($lns_count[0]['total_count']).' have been loaded</div>';

            }

        } else {

            //Show no link warning:
            $message .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No Links found with the selected filters. Modify filters and try again.</div>';

        }


        return view_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }

    function view_input_text_update(){

        //Authenticate Player:
        $session_en = superpower_assigned();
        $en_all_12112 = $this->config->item('en_all_12112');

        if (!$session_en) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
                'original_val' => '',
            ));

        } elseif(!isset($_POST['object_id']) || !isset($_POST['cache_en_id']) || !isset($_POST['field_value'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_en_id']==4736 /* IDEA TITLE */){

            $ins = $this->IDEA_model->fetch(array(
                'in_id' => $_POST['object_id'],
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
            ));
            if(!count($ins)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));
            }

            //Validate Idea Outcome:
            $in_title_validation = in_title_validate($_POST['field_value']);
            if(!$in_title_validation['status']){
                //We had an error, return it:
                return view_json(array_merge($in_title_validation, array(
                    'original_val' => $ins[0]['in_title'],
                )));
            }


            //All good, go ahead and update:
            $this->IDEA_model->update($_POST['object_id'], array(
                'in_title' => trim($_POST['field_value']),
            ), true, $session_en['en_id']);

            return view_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_en_id']==6197 /* SOURCE FULL NAME */){

            $ens = $this->SOURCE_model->fetch(array(
                'en_id' => $_POST['object_id'],
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            ));
            if(!count($ens)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID.',
                    'original_val' => '',
                ));
            }


            $en_name_validate = en_name_validate($_POST['field_value']);
            if(!$en_name_validate['status']){
                return view_json(array_merge($en_name_validate, array(
                    'original_val' => $ens[0]['en_name'],
                )));
            }

            //All good, go ahead and update:
            $this->SOURCE_model->update($ens[0]['en_id'], array(
                'en_name' => $en_name_validate['en_clean_name'],
            ), true, $session_en['en_id']);

            //Reset user session data if this data belongs to the logged-in user:
            if ($ens[0]['en_id'] == $session_en['en_id']) {
                //Re-activate Session with new data:
                $ens[0]['en_name'] = $en_name_validate['en_clean_name'];
                $this->SOURCE_model->activate_session($ens[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_en_id']==4356 /* READ TIME */){

            $ins = $this->IDEA_model->fetch(array(
                'in_id' => $_POST['object_id'],
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
            ));

            if(!count($ins)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || $_POST['field_value'] < 0){

                return view_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' must be a number greater than zero.',
                    'original_val' => $ins[0]['in_time_seconds'],
                ));

            } elseif($_POST['field_value'] > config_var(4356)){

                $hours = rtrim(number_format((config_var(4356)/3600), 1), '.0');
                return view_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' should be less than '.$hours.' Hour'.view__s($hours).', or '.config_var(4356).' Seconds long. You can break down your idea into smaller ideas.',
                    'original_val' => $ins[0]['in_time_seconds'],
                ));

            } elseif($_POST['field_value'] < config_var(12427)){

                return view_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' should be at-least '.config_var(12427).' Seconds long. It takes time to read ideas ;)',
                    'original_val' => $ins[0]['in_time_seconds'],
                ));

            } else {

                //All good, go ahead and update:
                $this->IDEA_model->update($_POST['object_id'], array(
                    'in_time_seconds' => $_POST['field_value'],
                ), true, $session_en['en_id']);

                return view_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4358 /* READ MARKS */){

            //Fetch/Validate Link:
            $lns = $this->READ_model->fetch(array(
                'ln_id' => $_POST['object_id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            ));
            $ln_metadata = unserialize($lns[0]['ln_metadata']);
            if(!$ln_metadata){
                $ln_metadata = array();
            }

            if(!count($lns)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Link ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < config_var(11056) ||  $_POST['field_value'] > config_var(11057)){

                return view_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' must be an integer between '.config_var(11056).' and '.config_var(11057).'.',
                    'original_val' => ( isset($ln_metadata['tr__assessment_points']) ? $ln_metadata['tr__assessment_points'] : 0 ),
                ));

            } else {

                //All good, go ahead and update:
                $this->READ_model->update($_POST['object_id'], array(
                    'ln_metadata' => array_merge($ln_metadata, array(
                        'tr__assessment_points' => intval($_POST['field_value']),
                    )),
                ), $session_en['en_id'], 10663 /* Idea Link updated Marks */, $en_all_12112[$_POST['cache_en_id']]['m_name'].' updated'.( isset($ln_metadata['tr__assessment_points']) ? ' from [' . $ln_metadata['tr__assessment_points']. ']' : '' ).' to [' . $_POST['field_value']. ']');

                return view_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4735 /* UNLOCK MIN SCORE */ || $_POST['cache_en_id']==4739 /* UNLOCK MAX SCORE */){

            //Fetch/Validate Link:
            $lns = $this->READ_model->fetch(array(
                'ln_id' => $_POST['object_id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            ));
            $ln_metadata = unserialize($lns[0]['ln_metadata']);
            $field_name = ( $_POST['cache_en_id']==4735 ? 'tr__conditional_score_min' : 'tr__conditional_score_max' );

            if(!count($lns)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Link ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < 0 || $_POST['field_value'] > 100){

                return view_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' must be an integer between 0 and 100.',
                    'original_val' => ( isset($ln_metadata[$field_name]) ? $ln_metadata[$field_name] : '' ),
                ));

            } else {

                //All good, go ahead and update:
                $this->READ_model->update($_POST['object_id'], array(
                    'ln_metadata' => array_merge($ln_metadata, array(
                        $field_name => intval($_POST['field_value']),
                    )),
                ), $session_en['en_id'], 10664 /* Idea Link updated Score */, $en_all_12112[$_POST['cache_en_id']]['m_name'].' updated'.( isset($ln_metadata[$field_name]) ? ' from [' . $ln_metadata[$field_name].']' : '' ).' to [' . $_POST['field_value'].']');

                return view_json(array(
                    'status' => 1,
                ));

            }

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type ['.$_POST['cache_en_id'].']',
                'original_val' => '',
            ));

        }
    }

    function saved(){

        $session_en = superpower_assigned(null, true);
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
        $this->load->view('header', array(
            'title' => $en_all_11035[12896]['m_name'],
        ));
        $this->load->view('read/read_saved', array(
            'session_en' => $session_en,
        ));
        $this->load->view('footer');

    }


    function start($in_id){

        //Adds Idea to the Players Reads

        $session_en = superpower_assigned();

        //Check to see if added to Reads for logged-in users:
        if(!$session_en){
            return redirect_message('/source/sign/'.$in_id);
        }

        //Add this Idea to their Reads If not already there:
        $success_message = null;
        $read_in_home = $this->READ_model->in_home($in_id, $session_en);
        if(!$read_in_home){
            if($this->READ_model->start($session_en['en_id'], $in_id)){
                $success_message = '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully added to your Reads</div>';
            } else {
                //Failed to add to Reads:
                return redirect_message('/read', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Failed to add idea to your Reads.</div>');
            }
        }

        //Go to this newly added idea:
        return redirect_message('/'.$in_id, $success_message);

    }

    function next($in_id = 0){

        $append_url = '?previous_read='.( isset($_GET['previous_read']) && $_GET['previous_read']>0 ? $_GET['previous_read'] : $in_id );
        $session_en = superpower_assigned();
        if(!$session_en){
            return redirect_message('/source/sign');
        }

        if($in_id > 0){

            //Fetch Idea:
            $ins = $this->IDEA_model->fetch(array(
                'in_id' => $in_id,
            ));


            //Should we check for auto next redirect if empty? Only if this is a selection:
            if($ins[0]['in_type_source_id']==6677){

                //Mark as read If not previously:
                $read_completes = $this->READ_model->fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                    'ln_creator_source_id' => $session_en['en_id'],
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ));

                if(!count($read_completes)){
                    $this->READ_model->is_complete($ins[0], array(
                        'ln_type_source_id' => 4559, //READ MESSAGES
                        'ln_creator_source_id' => $session_en['en_id'],
                        'ln_previous_idea_id' => $ins[0]['in_id'],
                    ));
                }

            }


            //Find next Idea based on source's Reads:
            $next_in_id = $this->READ_model->find_next($session_en['en_id'], $ins[0]);
            if($next_in_id > 0){
                return redirect_message('/'.$next_in_id.$append_url);
            } else {
                $next_in_id = $this->READ_model->find_next_go($session_en['en_id']);
                if($next_in_id > 0){
                    return redirect_message('/'.$next_in_id.$append_url);
                } else {
                    return redirect_message('/', '<div class="alert alert-info" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully readed your entire list.</div></div>');
                }
            }

        } else {

            //Find the next idea in the Reads:
            $next_in_id = $this->READ_model->find_next_go($session_en['en_id']);
            if($next_in_id > 0){
                return redirect_message('/'.$next_in_id.$append_url);
            } else {
                return redirect_message('/', '<div class="alert alert-info" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully readed your entire list.</div></div>');
            }

        }
    }

    function previous($previous_level_id, $in_id){

        $current_in_id = $previous_level_id;

        //Make sure not a select idea:
        if(!count($this->IDEA_model->fetch(array(
            'in_id' => $current_in_id,
            'in_type_source_id IN (' . join(',', $this->config->item('en_ids_7712')) . ')' => null, //SELECT IDEA
        )))){
            //FIND NEXT IDEAS
            foreach($this->READ_model->fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
                'ln_previous_idea_id' => $previous_level_id,
            ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $in_next){
                if($in_next['in_id']==$in_id){
                    break;
                } else {
                    $current_in_id = $in_next['in_id'];
                }
            }
        }

        return redirect_message('/'.$current_in_id);

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
        $primary_in_id = config_var(12156);

        if($in_id > 0 && $in_id==$primary_in_id){
            return redirect_message('/');
        } elseif(!$in_id){
            //Load the Starting Idea:
            $in_id = $primary_in_id;
        }

        //Fetch data:
        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $in_id,
        ));

        //Make sure we found it:
        if ( count($ins) < 1) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Idea #' . $in_id . ' not found</div>');
        } elseif(!in_array($ins[0]['in_status_source_id'], $this->config->item('en_ids_7355') /* PUBLIC */)){

            if(superpower_assigned(10939)){
                //Give them idea access:
                return redirect_message('/idea/' . $in_id);
            } else {
                //Inform them not published:
                return redirect_message('/', '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Cannot read this idea because it\'s not published yet.</div>');
            }

        }

        $this->load->view('header', array(
            'title' => $ins[0]['in_title'],
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

        //TODO: MERGE WITH FUNCTION in_notes_create_upload()

        //Authenticate Player:
        $session_en = superpower_assigned();
        if (!$session_en) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['in_id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing IDEA',
            ));

        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));

        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown error 2 while trying to save file.',
            ));

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(11063) * 1024 * 1024)) {

            return view_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . config_var(11063) . ' MB.',
            ));

        }

        //Validate Idea:
        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        if(count($ins)<1){
            return view_json(array(
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
            return view_json($cdn_status);
        }


        //Delete previous answer(s):
        foreach($this->READ_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
        )) as $read_progress){
            $this->READ_model->update($read_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Transaction Deleted
            ), $session_en['en_id'], 12129 /* READ ANSWER DELETED */);
        }

        //Save new answer:
        $new_message = '@'.$cdn_status['cdn_en']['en_id'];
        $this->READ_model->is_complete($ins[0], array(
            'ln_type_source_id' => 12117,
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_content' => $new_message,
            'ln_profile_source_id' => $cdn_status['cdn_en']['en_id'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => '<div class="read-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->READ_model->send_message($new_message).'</div>',
        ));

    }




    function read_text_answer(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['in_id']) || !intval($_POST['in_id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        } elseif (!isset($_POST['read_text_answer']) || !strlen($_POST['read_text_answer'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing text answer.',
            ));
        }

        //Validate/Fetch idea:
        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        if(count($ins) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        }

        //Delete previous answer(s):
        foreach($this->READ_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
        )) as $read_progress){
            $this->READ_model->update($read_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Transaction Deleted
            ), $session_en['en_id'], 12129 /* READ ANSWER DELETED */);
        }

        //Save new answer:
        $this->READ_model->is_complete($ins[0], array(
            'ln_type_source_id' => 6144,
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_content' => $_POST['read_text_answer'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Answer Saved',
        ));

    }


    function read_answer(){

        $session_en = superpower_assigned();
        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['in_loaded_id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea id.',
            ));
        } elseif (!isset($_POST['answered_ins']) || !is_array($_POST['answered_ins']) || !count($_POST['answered_ins'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Select an answer',
            ));
        }

        //Save answer:
        return view_json($this->READ_model->answer($session_en['en_id'], $_POST['in_loaded_id'], $_POST['answered_ins']));

    }




    function read_coins_remove_all($en_id, $timestamp, $secret_key){

        if($secret_key != md5($en_id . $this->config->item('cred_password_salt') . $timestamp)){
            die('Invalid Secret Key');
        }

        //Fetch their current progress links:
        $progress_links = $this->READ_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12227')) . ')' => null,
            'ln_creator_source_id' => $en_id,
        ), array(), 0);

        if(count($progress_links) > 0){

            //Yes they did have some:
            $message = 'Removed '.count($progress_links).' idea'.view__s(count($progress_links)).' from your Reads.';

            //Log link:
            $clear_all_link = $this->READ_model->create(array(
                'ln_content' => $message,
                'ln_type_source_id' => 6415, //Reads Reset Reads
                'ln_creator_source_id' => $en_id,
            ));

            //Delete all progressions:
            foreach($progress_links as $progress_link){
                $this->READ_model->update($progress_link['ln_id'], array(
                    'ln_status_source_id' => 6173, //Transaction Deleted
                    'ln_parent_transaction_id' => $clear_all_link['ln_id'], //To indicate when it was deleted
                ), $en_id, 6415 /* User Cleared Reads */);
            }

        } else {

            //Nothing to do:
            $message = 'Your Reads was empty as there was nothing to delete';

        }

        //Show basic UI for now:
        return redirect_message('/read', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');

    }


    function read_toggle_saved(){

        //See if we need to add or remove a highlight:
        //Authenticate Player:
        $session_en = superpower_assigned();
        if (!$session_en) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['in_id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        }

        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        if (!count($ins)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //First try to remove:
        $removed = 0;
        foreach($this->READ_model->fetch(array(
            'ln_profile_source_id' => $session_en['en_id'],
            'ln_next_idea_id' => $_POST['in_id'],
            'ln_type_source_id' => 12896, //SAVED
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        )) as $remove_saved){
            $removed++;
            $this->READ_model->update($remove_saved['ln_id'], array(
                'ln_status_source_id' => 6173, //Transaction Deleted
            ), $session_en['en_id'], 12906 /* UNSAVED */);
        }

        //Need to add?
        if(!$removed){
            //Then we must add:
            $this->READ_model->create(array(
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_profile_source_id' => $session_en['en_id'],
                'ln_content' => '@'.$session_en['en_id'],
                'ln_next_idea_id' => $_POST['in_id'],
                'ln_type_source_id' => 12896, //SAVED
            ));
        }

        //All Good:
        return view_json(array(
            'status' => 1,
        ));

    }



    function read_remove_item(){

        /*
         *
         * When users indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and delete the idea from their
         * Reads.
         *
         * */


        if (!isset($_POST['js_pl_id']) || intval($_POST['js_pl_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid player ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID',
            ));
        }

        //Call function to delete form Reads:
        $delete_result = $this->READ_model->delete($_POST['js_pl_id'], $_POST['in_id'], 6155); //REMOVED BOOKMARK

        if(!$delete_result['status']){
            return view_json($delete_result);
        } else {
            return view_json(array(
                'status' => 1,
            ));
        }
    }





    function read_sort_save()
    {
        /*
         *
         * Saves the order of Reads ideas based on
         * user preferences.
         *
         * */

        if (!isset($_POST['js_pl_id']) || intval($_POST['js_pl_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid player ID',
            ));
        } elseif (!isset($_POST['new_read_order']) || !is_array($_POST['new_read_order']) || count($_POST['new_read_order']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        }

        //Update the order of their Reads:
        $results = array();
        foreach($_POST['new_read_order'] as $ln_order => $ln_id){
            if(intval($ln_id) > 0 && intval($ln_order) > 0){
                //Update order of this link:
                $results[$ln_order] = $this->READ_model->update(intval($ln_id), array(
                    'ln_order' => $ln_order,
                ), $_POST['js_pl_id'], 6132 /* Ideas Ordered by User */);
            }
        }

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => count($_POST['new_read_order']).' Ideas Sorted',
        ));
    }


}