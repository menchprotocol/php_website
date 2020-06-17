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
        $session_source = superpower_assigned(null, true);
        $sources__2738 = $this->config->item('sources__2738');

        //Log home View:
        $this->READ_model->create(array(
            'read__type' => 4283, //Opened Reads
            'read__player' => $session_source['source__id'],
        ));

        $this->load->view('header', array(
            'title' => $sources__2738[6205]['m_name'],
        ));
        $this->load->view('read/read_home', array(
            'session_source' => $session_source,
        ));
        $this->load->view('footer');

    }


    function interactions(){

        /*
         *
         * List all Links on reverse chronological order
         * and Display Status for ideas, sources and
         * links.
         *
         * */

        //Load header:
        $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

        $this->load->view('header', array(
            'title' => $sources__11035[4341]['m_name'],
        ));
        $this->load->view('read/read_interactions');
        $this->load->view('footer');

    }

    function interactions_load(){

        /*
         * Loads the list of links based on the
         * filters passed on.
         *
         * */

        $filters = unserialize($_POST['link_filters']);
        $joined_by = unserialize($_POST['link_joined_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $query_offset = (($page_num-1)*config_var(11064));
        $session_source = superpower_assigned();

        $message = '';

        //Fetch links and total link counts:
        $reads = $this->READ_model->fetch($filters, $joined_by, config_var(11064), $query_offset);
        $reads_count = $this->READ_model->fetch($filters, $joined_by, 0, 0, array(), 'COUNT(read__id) as total_count');
        $total_items_loaded = ($query_offset+count($reads));
        $has_more_links = ($reads_count[0]['total_count'] > 0 && $total_items_loaded < $reads_count[0]['total_count']);


        //Display filter:
        if($total_items_loaded > 0){
            $message .= '<div class="montserrat read-info"><span class="icon-block"><i class="fas fa-file-search"></i></span>'.( $has_more_links && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) . number_format($reads_count[0]['total_count'] , 0) .' READS:</div>';
        }


        if(count($reads)>0){

            $message .= '<div class="list-group list-grey">';
            foreach($reads as $read) {

                $message .= view_interaction($read);

                if($session_source && strlen($read['read__message'])>0 && strlen($_POST['read__message_search'])>0 && strlen($_POST['read__message_replace'])>0 && substr_count($read['read__message'], $_POST['read__message_search'])>0){

                    $new_content = str_replace($_POST['read__message_search'],trim($_POST['read__message_replace']),$read['read__message']);

                    $this->READ_model->update($read['read__id'], array(
                        'read__message' => $new_content,
                    ), $session_source['source__id'], 12360, update_description($read['read__message'], $new_content));

                    $message .= '<div class="alert alert-info" role="alert"><i class="fas fa-check-circle"></i> Replaced ['.$_POST['read__message_search'].'] with ['.trim($_POST['read__message_replace']).']</div>';

                }

            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_links){
                $message .= '<div id="link_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn btn-read" onclick="read_load(link_filters, link_joined_by, '.$next_page.');"><span class="icon-block"><i class="fas fa-plus-circle"></i></span>Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><span class="icon-block"><i class="far fa-check-circle"></i></span>All '.$reads_count[0]['total_count'].' link'.view__s($reads_count[0]['total_count']).' have been loaded</div>';

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



    function read_preview_type()
    {

        if (!isset($_POST['read__message']) || !isset($_POST['read__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing inputs',
            ));
        }

        //Will Contain every possible Player Link Connector:
        $sources__4592 = $this->config->item('sources__4592');

        //See what this is:
        $detected_read_type = read_detect_type($_POST['read__message']);

        if(!$_POST['read__id'] && !in_array($detected_read_type['read__type'], $this->config->item('sources_id_4537'))){

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));

        } elseif (!$detected_read_type['status'] && isset($detected_read_type['url_previously_existed']) && $detected_read_type['url_previously_existed']) {

            //See if this is duplicate to either link:
            $source_reads = $this->READ_model->fetch(array(
                'read__id' => $_POST['read__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_4537')) . ')' => null, //Player URL Links
            ));

            //Are they both different?
            if (count($source_reads) < 1 || ($source_reads[0]['read__up'] != $detected_read_type['source_url']['source__id'] && $source_reads[0]['read__down'] != $detected_read_type['source_url']['source__id'])) {
                //return error:
                return view_json($detected_read_type);
            }

        }



        return view_json(array(
            'status' => 1,
            'html_ui' => '<b class="montserrat doupper '.extract_icon_color($sources__4592[$detected_read_type['read__type']]['m_icon']).'">' . $sources__4592[$detected_read_type['read__type']]['m_icon'] . ' ' . $sources__4592[$detected_read_type['read__type']]['m_name'] . '</b>',
            'source_link_preview' => ( in_array($detected_read_type['read__type'], $this->config->item('sources_id_12524')) ? '<span class="paddingup inline-block">'.view_read__message($_POST['read__message'], $detected_read_type['read__type']).'</span>' : ''),
        ));

    }




    function view_input_text_update(){

        //Authenticate Player:
        $session_source = superpower_assigned();
        $sources__12112 = $this->config->item('sources__12112');

        if (!$session_source) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
                'original_val' => '',
            ));

        } elseif(!isset($_POST['object__id']) || !isset($_POST['cache_source__id']) || !isset($_POST['field_value'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_source__id']==4736 /* IDEA TITLE */){

            $ideas = $this->IDEA_model->fetch(array(
                'idea__id' => $_POST['object__id'],
                'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
            ));
            if(!count($ideas)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));
            }

            //Validate Idea Outcome:
            $idea__title_validation = idea__title_validate($_POST['field_value']);
            if(!$idea__title_validation['status']){
                //We had an error, return it:
                return view_json(array_merge($idea__title_validation, array(
                    'original_val' => $ideas[0]['idea__title'],
                )));
            }


            //All good, go ahead and update:
            $this->IDEA_model->update($_POST['object__id'], array(
                'idea__title' => trim($_POST['field_value']),
            ), true, $session_source['source__id']);

            return view_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_source__id']==6197 /* SOURCE FULL NAME */){

            $sources = $this->SOURCE_model->fetch(array(
                'source__id' => $_POST['object__id'],
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
            ));
            if(!count($sources)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID.',
                    'original_val' => '',
                ));
            }


            $source__title_validate = source__title_validate($_POST['field_value']);
            if(!$source__title_validate['status']){
                return view_json(array_merge($source__title_validate, array(
                    'original_val' => $sources[0]['source__title'],
                )));
            }

            //All good, go ahead and update:
            $this->SOURCE_model->update($sources[0]['source__id'], array(
                'source__title' => $source__title_validate['source__title_clean'],
            ), true, $session_source['source__id']);

            //Reset user session data if this data belongs to the logged-in user:
            if ($sources[0]['source__id'] == $session_source['source__id']) {
                //Re-activate Session with new data:
                $sources[0]['source__title'] = $source__title_validate['source__title_clean'];
                $this->SOURCE_model->activate_session($sources[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_source__id']==4356 /* READ TIME */){

            $ideas = $this->IDEA_model->fetch(array(
                'idea__id' => $_POST['object__id'],
                'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
            ));

            if(!count($ideas)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || $_POST['field_value'] < 0){

                return view_json(array(
                    'status' => 0,
                    'message' => $sources__12112[$_POST['cache_source__id']]['m_name'].' must be a number greater than zero.',
                    'original_val' => $ideas[0]['idea__duration'],
                ));

            } elseif($_POST['field_value'] > config_var(4356)){

                $hours = rtrim(number_format((config_var(4356)/3600), 1), '.0');
                return view_json(array(
                    'status' => 0,
                    'message' => $sources__12112[$_POST['cache_source__id']]['m_name'].' should be less than '.$hours.' Hour'.view__s($hours).', or '.config_var(4356).' Seconds long. You can break down your idea into smaller ideas.',
                    'original_val' => $ideas[0]['idea__duration'],
                ));

            } elseif($_POST['field_value'] < config_var(12427)){

                return view_json(array(
                    'status' => 0,
                    'message' => $sources__12112[$_POST['cache_source__id']]['m_name'].' should be at-least '.config_var(12427).' Seconds long. It takes time to read ideas ;)',
                    'original_val' => $ideas[0]['idea__duration'],
                ));

            } else {

                //All good, go ahead and update:
                $this->IDEA_model->update($_POST['object__id'], array(
                    'idea__duration' => $_POST['field_value'],
                ), true, $session_source['source__id']);

                return view_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_source__id']==4358 /* READ MARKS */){

            //Fetch/Validate Link:
            $reads = $this->READ_model->fetch(array(
                'read__id' => $_POST['object__id'],
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            ));
            $read__metadata = unserialize($reads[0]['read__metadata']);
            if(!$read__metadata){
                $read__metadata = array();
            }

            if(!count($reads)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Link ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < config_var(11056) ||  $_POST['field_value'] > config_var(11057)){

                return view_json(array(
                    'status' => 0,
                    'message' => $sources__12112[$_POST['cache_source__id']]['m_name'].' must be an integer between '.config_var(11056).' and '.config_var(11057).'.',
                    'original_val' => ( isset($read__metadata['tr__assessment_points']) ? $read__metadata['tr__assessment_points'] : 0 ),
                ));

            } else {

                //All good, go ahead and update:
                $this->READ_model->update($_POST['object__id'], array(
                    'read__metadata' => array_merge($read__metadata, array(
                        'tr__assessment_points' => intval($_POST['field_value']),
                    )),
                ), $session_source['source__id'], 10663 /* Idea Link updated Marks */, $sources__12112[$_POST['cache_source__id']]['m_name'].' updated'.( isset($read__metadata['tr__assessment_points']) ? ' from [' . $read__metadata['tr__assessment_points']. ']' : '' ).' to [' . $_POST['field_value']. ']');

                return view_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_source__id']==4735 /* UNLOCK MIN SCORE */ || $_POST['cache_source__id']==4739 /* UNLOCK MAX SCORE */){

            //Fetch/Validate Link:
            $reads = $this->READ_model->fetch(array(
                'read__id' => $_POST['object__id'],
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            ));
            $read__metadata = unserialize($reads[0]['read__metadata']);
            $field_name = ( $_POST['cache_source__id']==4735 ? 'tr__conditional_score_min' : 'tr__conditional_score_max' );

            if(!count($reads)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Link ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < 0 || $_POST['field_value'] > 100){

                return view_json(array(
                    'status' => 0,
                    'message' => $sources__12112[$_POST['cache_source__id']]['m_name'].' must be an integer between 0 and 100.',
                    'original_val' => ( isset($read__metadata[$field_name]) ? $read__metadata[$field_name] : '' ),
                ));

            } else {

                //All good, go ahead and update:
                $this->READ_model->update($_POST['object__id'], array(
                    'read__metadata' => array_merge($read__metadata, array(
                        $field_name => intval($_POST['field_value']),
                    )),
                ), $session_source['source__id'], 10664 /* Idea Link updated Score */, $sources__12112[$_POST['cache_source__id']]['m_name'].' updated'.( isset($read__metadata[$field_name]) ? ' from [' . $read__metadata[$field_name].']' : '' ).' to [' . $_POST['field_value'].']');

                return view_json(array(
                    'status' => 1,
                ));

            }

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type ['.$_POST['cache_source__id'].']',
                'original_val' => '',
            ));

        }
    }

    function saved(){

        $session_source = superpower_assigned(null, true);
        $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
        $this->load->view('header', array(
            'title' => $sources__11035[12896]['m_name'],
        ));
        $this->load->view('read/read_saved', array(
            'session_source' => $session_source,
        ));
        $this->load->view('footer');

    }


    function start_reading($idea__id){

        //Adds Idea to the Players Reads

        $session_source = superpower_assigned();
        $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION

        //Check to see if added to Reads for logged-in users:
        if(!$session_source){
            return redirect_message('/@s'.$idea__id);
        }

        //Add this Idea to their Reads If not already there:
        $idea__id_added = $idea__id;
        $success_message = null;
        $in_my_reads = $this->READ_model->idea_home($idea__id, $session_source);

        if(!$in_my_reads){
            $idea__id_added = $this->READ_model->start($session_source['source__id'], $idea__id);
            if($idea__id_added){
                $success_message = '<div class="alert alert-info" role="alert"><span class="icon-block">'.$sources__11035[12969]['m_icon'].'</span>Successfully added to your '.$sources__11035[12969]['m_name'].'. Continue below.</div>';
            } else {
                //Failed to add to Reads:
                return redirect_message('/r', '<div class="alert alert-danger" role="alert"><span class="icon-block">'.$sources__11035[12969]['m_icon'].'</span>FAILED to add to your '.$sources__11035[12969]['m_name'].'.</div>');
            }
        }

        //Go to this newly added idea:
        return redirect_message('/'.$idea__id_added, $success_message);

    }

    function next($idea__id = 0){

        $session_source = superpower_assigned();
        if(!$session_source){
            return redirect_message('/@s');
        }

        if($idea__id > 0){

            //Fetch Idea:
            $ideas = $this->IDEA_model->fetch(array(
                'idea__id' => $idea__id,
            ));


            //Should we check for auto next redirect if empty? Only if this is a selection:
            if($ideas[0]['idea__type']==6677){

                //Mark as read If not previously:
                $read_completes = $this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12229')) . ')' => null, //READ COMPLETE
                    'read__player' => $session_source['source__id'],
                    'read__left' => $ideas[0]['idea__id'],
                ));

                if(!count($read_completes)){
                    $this->READ_model->mark_complete($ideas[0], array(
                        'read__type' => 4559, //READ MESSAGES
                        'read__player' => $session_source['source__id'],
                        'read__left' => $ideas[0]['idea__id'],
                    ));
                }
            }
        }

        //Go to Next Idea:
        $next_idea__id = $this->READ_model->find_next($session_source['source__id'], $ideas[0]);
        if($next_idea__id > 0){
            return redirect_message('/'.$next_idea__id.'?previous_read='.( isset($_GET['previous_read']) && $_GET['previous_read']>0 ? $_GET['previous_read'] : $idea__id ));
        } else {
            return redirect_message('/', '<div class="alert alert-info" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully read your entire READ LIST.</div></div>');
        }

    }




    function previous($previous_level_id, $idea__id){

        $current_idea__id = $previous_level_id;

        //Make sure not a select idea:
        if(!count($this->IDEA_model->fetch(array(
            'idea__id' => $current_idea__id,
            'idea__type IN (' . join(',', $this->config->item('sources_id_7712')) . ')' => null, //SELECT IDEA
        )))){
            //FIND NEXT IDEAS
            foreach($this->READ_model->fetch(array(
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
                'read__left' => $previous_level_id,
            ), array('read__right'), 0, 0, array('read__sort' => 'ASC')) as $next_idea){
                if($next_idea['idea__id']==$idea__id){
                    break;
                } else {
                    $current_idea__id = $next_idea['idea__id'];
                }
            }
        }

        return redirect_message('/'.$current_idea__id);

    }



    function read_coin($idea__id = 0)
    {

        /*
         *
         * Enables a Player to READ a IDEA
         * on the public web
         *
         * */

        //Fetch user session:
        $session_source = superpower_assigned();
        $primary_idea__id = $this->config->item('featured_idea__id');

        if($idea__id > 0 && $idea__id==$primary_idea__id){
            return redirect_message('/');
        } elseif(!$idea__id){
            //Load the Starting Idea:
            $idea__id = $primary_idea__id;
        }

        //Fetch data:
        $ideas = $this->IDEA_model->fetch(array(
            'idea__id' => $idea__id,
        ));

        //Make sure we found it:
        if ( count($ideas) < 1) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Idea #' . $idea__id . ' not found</div>');
        } elseif(!in_array($ideas[0]['idea__status'], $this->config->item('sources_id_7355') /* PUBLIC */)){

            if(superpower_assigned(10939)){
                //Give them idea access:
                return redirect_message('/i' . $idea__id);
            } else {
                //Inform them not published:
                return redirect_message('/', '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Cannot read this idea because it\'s not published yet.</div>');
            }

        }

        $this->load->view('header', array(
            'title' => $ideas[0]['idea__title'],
        ));

        //Load specific view based on Idea Level:
        $this->load->view('read/read_coin', array(
            'idea_focus' => $ideas[0],
            'session_source' => $session_source,
        ));

        $this->load->view('footer');

    }



    function read_file_upload()
    {

        //TODO: MERGE WITH FUNCTION idea_note_add_file()

        //Authenticate Player:
        $session_source = superpower_assigned();
        if (!$session_source) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['idea__id'])) {

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
        $ideas = $this->IDEA_model->fetch(array(
            'idea__id' => $_POST['idea__id'],
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ));
        if(count($ideas)<1){
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

        $cdn_status = upload_to_cdn($temp_local, $session_source['source__id'], $_FILES[$_POST['upload_type']], true, $ideas[0]['idea__title']);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return view_json($cdn_status);
        }


        //Delete previous answer(s):
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
            'read__left' => $ideas[0]['idea__id'],
            'read__player' => $session_source['source__id'],
        )) as $read_progress){
            $this->READ_model->update($read_progress['read__id'], array(
                'read__status' => 6173, //Read Deleted
            ), $session_source['source__id'], 12129 /* READ ANSWER DELETED */);
        }

        //Save new answer:
        $new_message = '@'.$cdn_status['cdn_source']['source__id'];
        $this->READ_model->mark_complete($ideas[0], array(
            'read__type' => 12117,
            'read__left' => $ideas[0]['idea__id'],
            'read__player' => $session_source['source__id'],
            'read__message' => $new_message,
            'read__up' => $cdn_status['cdn_source']['source__id'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => '<div class="read-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->READ_model->message_send($new_message).'</div>',
        ));

    }




    function read_text_answer(){

        $session_source = superpower_assigned();
        if (!$session_source) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['idea__id']) || !intval($_POST['idea__id'])) {
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
        $ideas = $this->IDEA_model->fetch(array(
            'idea__id' => $_POST['idea__id'],
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ));
        if(count($ideas) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        }

        //Delete previous answer(s):
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_6255')) . ')' => null, //READ COIN
            'read__left' => $ideas[0]['idea__id'],
            'read__player' => $session_source['source__id'],
        )) as $read_progress){
            $this->READ_model->update($read_progress['read__id'], array(
                'read__status' => 6173, //Read Deleted
            ), $session_source['source__id'], 12129 /* READ ANSWER DELETED */);
        }

        //Save new answer:
        $this->READ_model->mark_complete($ideas[0], array(
            'read__type' => 6144,
            'read__left' => $ideas[0]['idea__id'],
            'read__player' => $session_source['source__id'],
            'read__message' => $_POST['read_text_answer'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Answer Saved',
        ));

    }


    function read_answer(){

        $session_source = superpower_assigned();
        if (!$session_source) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['idea_loaded_id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea id.',
            ));
        } elseif (!isset($_POST['answered_ideas']) || !is_array($_POST['answered_ideas']) || !count($_POST['answered_ideas'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Select an answer',
            ));
        }

        //Save answer:
        return view_json($this->READ_model->answer($session_source['source__id'], $_POST['idea_loaded_id'], $_POST['answered_ideas']));

    }




    function read_coins_remove_all($source__id, $timestamp, $secret_key){

        if($secret_key != md5($source__id . $this->config->item('cred_password_salt') . $timestamp)){
            die('Invalid Secret Key');
        }

        //Fetch their current progress links:
        $progress_links = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type IN (' . join(',', $this->config->item('sources_id_12227')) . ')' => null,
            'read__player' => $source__id,
        ), array(), 0);

        if(count($progress_links) > 0){

            //Yes they did have some:
            $message = 'Removed '.count($progress_links).' idea'.view__s(count($progress_links)).' from your Reads.';

            //Log link:
            $clear_all_link = $this->READ_model->create(array(
                'read__message' => $message,
                'read__type' => 6415, //Reads Reset Reads
                'read__player' => $source__id,
            ));

            //Delete all progressions:
            foreach($progress_links as $progress_link){
                $this->READ_model->update($progress_link['read__id'], array(
                    'read__status' => 6173, //Read Deleted
                    'read__reference' => $clear_all_link['read__id'], //To indicate when it was deleted
                ), $source__id, 6415 /* User Cleared Reads */);
            }

        } else {

            //Nothing to do:
            $message = 'Your Reads was empty as there was nothing to delete';

        }

        //Show basic UI for now:
        return redirect_message('/r', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');

    }


    function read_toggle_saved(){

        //See if we need to add or remove a highlight:
        //Authenticate Player:
        $session_source = superpower_assigned();
        if (!$session_source) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['idea__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        }

        $ideas = $this->IDEA_model->fetch(array(
            'idea__id' => $_POST['idea__id'],
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
        ));
        if (!count($ideas)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //First try to remove:
        $removed = 0;
        foreach($this->READ_model->fetch(array(
            'read__up' => $session_source['source__id'],
            'read__right' => $_POST['idea__id'],
            'read__type' => 12896, //SAVED
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        )) as $remove_saved){
            $removed++;
            $this->READ_model->update($remove_saved['read__id'], array(
                'read__status' => 6173, //Read Deleted
            ), $session_source['source__id'], 12906 /* UNSAVED */);
        }

        //Need to add?
        if(!$removed){
            //Then we must add:
            $this->READ_model->create(array(
                'read__player' => $session_source['source__id'],
                'read__up' => $session_source['source__id'],
                'read__message' => '@'.$session_source['source__id'],
                'read__right' => $_POST['idea__id'],
                'read__type' => 12896, //SAVED
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
        } elseif (!isset($_POST['idea__id']) || intval($_POST['idea__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID',
            ));
        }

        //Call function to delete form Reads:
        $delete_result = $this->READ_model->delete($_POST['js_pl_id'], $_POST['idea__id'], 6155); //REMOVED BOOKMARK

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
        foreach($_POST['new_read_order'] as $read__sort => $read__id){
            if(intval($read__id) > 0 && intval($read__sort) > 0){
                //Update order of this link:
                $results[$read__sort] = $this->READ_model->update(intval($read__id), array(
                    'read__sort' => $read__sort,
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