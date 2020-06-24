<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Map extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }


    function i_create(){

        $sources__6201 = $this->config->item('sources__6201'); //Idea Table
        $session_source = superpower_assigned(10939);
        if (!$session_source) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['newIdeaTitle'])) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return view_json(array(
                'status' => 0,
                'message' => 'Missing '.$sources__6201[4736]['m_name'],
            ));

        }

        //Validate Title:
        $i__title_validation = i__title_validate($_POST['newIdeaTitle']);
        if(!$i__title_validation['status']){
            //We had an error, return it:
            return view_json($i__title_validation);
        }


        //Create Idea:
        $idea = $this->MAP_model->link_or_create($i__title_validation['i_clean_title'], $session_source['e__id']);

        //Also add to bookmarks:
        $this->DISCOVER_model->create(array(
            'x__type' => 10573, //MY IDEAS
            'x__player' => $session_source['e__id'],
            'x__right' => $idea['new_i__id'],
            'x__up' => $session_source['e__id'],
            'x__message' => '@'.$session_source['e__id'],
        ), true);

        return view_json(array(
            'status' => 1,
            'message' => '<span class="icon-block"><i class="fas fa-check-circle idea"></i></span>Success! Redirecting now...',
            'i__id' => $idea['new_i__id'],
        ));

    }

    function index(){
        //Idea Bookmarks
        $session_source = superpower_assigned(10939, true);
        $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
        $this->load->view('header', array(
            'title' => $sources__11035[4535]['m_name'],
            'session_source' => $session_source,
        ));
        $this->load->view('map/home');
        $this->load->view('footer');
    }


    function i_go($i__id){
        /*
         *
         * The next section is very important as it
         * manages the entire search traffic that
         * comes through /iID
         *
         * */
        $i_is_source = i_is_source($i__id);
        return redirect_message(( $i_is_source ? '/~' : '/' ) . $i__id . ( $i_is_source && isset($_GET['focus__source']) ? '?focus__source='.$_GET['focus__source'] : '' ) );
    }


    function i_coin($i__id){

        //Validate/fetch Idea:
        $ideas = $this->MAP_model->fetch(array(
            'i__id' => $i__id,
        ));
        if ( count($ideas) < 1) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>IDEA #' . $i__id . ' Not Found</div>');
        }


        $session_source = superpower_assigned(10939); //Idea Pen?
        $is_public = in_array($ideas[0]['i__status'], $this->config->item('sources_id_7355'));

        if(!$session_source){
            if($is_public){
                return redirect_message('/'.$i__id);
            } else {
                return redirect_message('/', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>IDEA #' . $i__id . ' is not published yet.</div>');
            }
        }


        //Mass List Editing?
        if (superpower_active(13403, true) && isset($_POST['mass_action_e__id']) && isset($_POST['mass_value1_'.$_POST['mass_action_e__id']]) && isset($_POST['mass_value2_'.$_POST['mass_action_e__id']])) {

            //Process mass action:
            $process_mass_action = $this->MAP_model->mass_update($i__id, intval($_POST['mass_action_e__id']), $_POST['mass_value1_'.$_POST['mass_action_e__id']], $_POST['mass_value2_'.$_POST['mass_action_e__id']], $session_source['e__id']);

            //Pass-on results to UI:
            $message = '<div class="alert '.( $process_mass_action['status'] ? 'alert-warning' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.$process_mass_action['message'].'</div>';

        } else {

            //Just Viewing:
            $message = null;
            $new_order = ( $this->session->userdata('session_page_count') + 1 );
            $this->session->set_userdata('session_page_count', $new_order);
            $this->DISCOVER_model->create(array(
                'x__player' => $session_source['e__id'],
                'x__type' => 4993, //Player Opened Idea
                'x__right' => $i__id,
                'x__sort' => $new_order,
            ));

        }



        //Load views:
        $this->load->view('header', array(
            'title' => $ideas[0]['i__title'],
            'flash_message' => $message, //Possible mass-action message for UI:
        ));
        $this->load->view('map/coin', array(
            'i_focus' => $ideas[0],
            'session_source' => $session_source,
        ));
        $this->load->view('footer');

    }


    function i_e_request($i__id){

        //Make sure it's a logged in player:
        $session_source = superpower_assigned(null, true);

        if(count($this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'x__type' => 12450,
            'x__player' => $session_source['e__id'],
            'x__right' => $i__id,
        )))){
            return redirect_message('/~'.$i__id, '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>You have previously requested to join this idea. No further action is necessary.</div>');

        }

        //Inform moderators:
        $this->DISCOVER_model->create(array(
            'x__type' => 12450,
            'x__player' => $session_source['e__id'],
            'x__right' => $i__id,
        ));

        //Go back to idea:
        return redirect_message('/~'.$i__id, '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-check-circle"></i></span>Successfully submitted your request to become a source for this idea. You will receive a confirmation once your request has been reviewed.</div>');

    }

    function i_e_add($i__id){

        //Make sure it's a logged in player:
        $session_source = superpower_assigned(10984, true);

        //Idea Source:
        $this->DISCOVER_model->create(array(
            'x__type' => 4983, //IDEA COIN
            'x__player' => $session_source['e__id'],
            'x__up' => $session_source['e__id'],
            'x__message' => '@'.$session_source['e__id'],
            'x__right' => $i__id,
        ));

        //Go back to idea:
        return redirect_message('/~'.$i__id, '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-check-circle"></i></span>SUCCESSFULLY JOINED</div>');

    }


    function i_navigate($previous_i__id, $current_i__id, $action){

        $trigger_next = false;
        $track_previous = 0;

        foreach($this->DISCOVER_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'x__left' => $previous_i__id,
        ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $idea){
            if($action=='next'){
                if($trigger_next){
                    return redirect_message('/~' . $idea['i__id'] );
                }
                if($idea['i__id']==$current_i__id){
                    $trigger_next = true;
                }
            } elseif($action=='previous'){
                if($idea['i__id']==$current_i__id){
                    if($track_previous > 0){
                        return redirect_message('/~' . $track_previous );
                    } else {
                        //First item:
                        break;
                    }
                } else {
                    $track_previous = $idea['i__id'];
                }
            }
        }

        if($previous_i__id > 0){
            return redirect_message('/~' .$previous_i__id );
        } else {
            die('Could not find matching idea');
        }

    }



    function i_set_dropdown(){

        //Maintain a manual index as a hack for the Idea/Source tables for now:
        $sources__6232 = $this->config->item('sources__6232'); //PLATFORM VARIABLES
        $deletion_redirect = null;
        $delete_element = null;

        //Authenticate Player:
        $session_source = superpower_assigned();
        if (!$session_source) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Target Idea ID',
            ));
        } elseif (!isset($_POST['i_loaded_id']) || intval($_POST['i_loaded_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Loaded Idea ID',
            ));
        } elseif (!isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        } elseif (!isset($_POST['element_id']) || intval($_POST['element_id']) < 1 || !array_key_exists($_POST['element_id'], $sources__6232) || strlen($sources__6232[$_POST['element_id']]['m_desc'])<5 || !count($this->config->item('sources_id_'.$_POST['element_id']))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Element ID ['.$_POST['element_id'].'] Missing from @6232',
            ));
        } elseif (!isset($_POST['new_e__id']) || intval($_POST['new_e__id']) < 1 || !in_array($_POST['new_e__id'], $this->config->item('sources_id_'.$_POST['element_id']))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            ));
        }

        if($_POST['x__id'] > 0){

            //Validate the link update Type ID:
            $sources__4527 = $this->config->item('sources__4527');
            if(!is_array($sources__4527[$_POST['element_id']]['m_parents']) || !count($sources__4527[$_POST['element_id']]['m_parents'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing @'.$_POST['element_id'].' in @4527',
                ));
            }

            //Find the single discover type in parent links:
            $link_update_types = array_intersect($this->config->item('sources_id_4593'), $sources__4527[$_POST['element_id']]['m_parents']);
            if(count($link_update_types)!=1){
                return view_json(array(
                    'status' => 0,
                    'message' => '@'.$_POST['element_id'].' has '.count($link_update_types).' parents that belog to @4593 [Should be exactly 1]',
                ));
            }

            //All good, Update Link:
            $this->DISCOVER_model->update($_POST['x__id'], array(
                $sources__6232[$_POST['element_id']]['m_desc'] => $_POST['new_e__id'],
            ), $session_source['e__id'], end($link_update_types));

        } else {


            //See if Idea is being deleted:
            if($_POST['element_id']==4737){

                //Delete all idea links?
                if(!in_array($_POST['new_e__id'], $this->config->item('sources_id_7356'))){

                    //Determine what to do after deleted:
                    if($_POST['i__id'] == $_POST['i_loaded_id']){

                        //Since we're removing the FOCUS IDEA we need to move to the first parent idea:
                        foreach($this->MAP_model->recursive_parents($_POST['i__id'], true, false) as $grand_parent_ids) {
                            foreach($grand_parent_ids as $previous_i__id) {
                                $deletion_redirect = '/~'.$previous_i__id; //First parent in first branch of parents
                                break;
                            }
                        }

                        //Go to main page if no parent found:
                        if(!$deletion_redirect){
                            $deletion_redirect = ( intval($this->session->userdata('session_time_7260')) ? '/source/plugin/7260' : '/~' );
                        }

                    } else {

                        if(!$delete_element){

                            //Just delete from UI using JS:
                            $delete_element = '.i_line_' . $_POST['i__id'];

                        }

                    }

                    //Delete all links:
                    $this->MAP_model->unlink($_POST['i__id'] , $session_source['e__id']);

                //Notify moderators of Feature request? Only if they don't have the powers themselves:
                } elseif(in_array($_POST['new_e__id'], $this->config->item('sources_id_12138')) && !superpower_assigned(10984) && !count($this->DISCOVER_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                        'x__type' => 12453, //Idea Feature Request
                        'x__player' => $session_source['e__id'],
                        'x__right' => $_POST['i__id'],
                    )))){

                    $this->DISCOVER_model->create(array(
                        'x__type' => 12453, //Idea Feature Request
                        'x__player' => $session_source['e__id'],
                        'x__right' => $_POST['i__id'],
                    ));

                }

            }

            //Update Idea:
            $this->MAP_model->update($_POST['i__id'], array(
                $sources__6232[$_POST['element_id']]['m_desc'] => $_POST['new_e__id'],
            ), true, $session_source['e__id']);

        }


        return view_json(array(
            'status' => 1,
            'deletion_redirect' => $deletion_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function i_unlink(){

        //Authenticate Player:
        $session_source = superpower_assigned();
        if (!$session_source) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));
        } elseif (!isset($_POST['x__id']) || intval($_POST['x__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        }

        //Delete this link:
        $this->DISCOVER_model->update($_POST['x__id'], array(
            'x__status' => 6173, //Interaction Removed
        ), $session_source['e__id'], 10686 /* Idea Link Unpublished */);

        return view_json(array(
            'status' => 1,
            'message' => 'Success',
        ));

    }


    function i_add()
    {

        /*
         *
         * Either creates a IDEA link between i_linked_id & i_link_child_id
         * OR will create a new idea with outcome i__title and then link it
         * to i_linked_id (In this case i_link_child_id=0)
         *
         * */

        //Authenticate Player:
        $session_source = superpower_assigned(10939);
        if (!$session_source) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['i_linked_id']) || intval($_POST['i_linked_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Parent Idea ID',
            ));
        } elseif (!isset($_POST['is_parent']) || !in_array(intval($_POST['is_parent']), array(0,1))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Is Parent setting',
            ));
        } elseif (!isset($_POST['i__title']) || !isset($_POST['i_link_child_id']) || ( strlen($_POST['i__title']) < 1 && intval($_POST['i_link_child_id']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Child Idea ID',
            ));
        } elseif (strlen($_POST['i__title']) > config_var(4736)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea outcome cannot be longer than '.config_var(4736).' characters',
            ));
        } elseif($_POST['i_link_child_id'] >= 2147483647){
            return view_json(array(
                'status' => 0,
                'message' => 'Value must be less than 2147483647',
            ));
        }


        $new_i_type = 6677; //Idea Read & Next
        $linked_ideas = array();

        if($_POST['i_link_child_id'] > 0){

            //Fetch link idea to determine idea type:
            $linked_ideas = $this->MAP_model->fetch(array(
                'i__id' => intval($_POST['i_link_child_id']),
                'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
            ));

            if(count($linked_ideas)==0){
                //validate linked Idea:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea #'.$_POST['i_link_child_id'].' is not active',
                ));
            }

            if(!intval($_POST['is_parent']) && in_array($linked_ideas[0]['i__type'], $this->config->item('sources_id_7712'))){
                $new_i_type = 6914; //Require All
            }
        }

        //All seems good, go ahead and try creating the Idea:
        return view_json($this->MAP_model->link_or_create(trim($_POST['i__title']), $session_source['e__id'], $_POST['i_linked_id'], intval($_POST['is_parent']), 6184, $new_i_type, $_POST['i_link_child_id']));

    }

    function i_sort_save()
    {

        //Authenticate Player:
        $session_source = superpower_assigned(10939);
        if (!$session_source) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid i__id',
            ));
        } elseif (!isset($_POST['new_x__sorts']) || !is_array($_POST['new_x__sorts']) || count($_POST['new_x__sorts']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Parent Idea:
            $previous_ideas = $this->MAP_model->fetch(array(
                'i__id' => intval($_POST['i__id']),
            ));
            if (count($previous_ideas) < 1) {
                view_json(array(
                    'status' => 0,
                    'message' => 'Invalid i__id',
                ));
            } else {

                //Update them all:
                foreach($_POST['new_x__sorts'] as $rank => $x__id) {
                    $this->DISCOVER_model->update(intval($x__id), array(
                        'x__sort' => intval($rank),
                    ), $session_source['e__id'], 10675 /* Ideas Ordered by Player */);
                }

                //Display message:
                view_json(array(
                    'status' => 1,
                ));
            }
        }
    }


    function i_note_text()
    {

        //Authenticate Player:
        $session_source = superpower_assigned();

        if (!$session_source) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));

        } elseif (!isset($_POST['note_type_id']) || !in_array($_POST['note_type_id'], $this->config->item('sources_id_4485'))) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }


        //Fetch/Validate the idea:
        $ideas = $this->MAP_model->fetch(array(
            'i__id' => intval($_POST['i__id']),
            'i__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        ));
        if(count($ideas)<1){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->DISCOVER_model->message_compile($_POST['x__message'], $session_source, $_POST['note_type_id'], $_POST['i__id']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return view_json($msg_validation);
        }

        //Create Message:
        $discovery = $this->DISCOVER_model->create(array(
            'x__player' => $session_source['e__id'],
            'x__sort' => 1 + $this->DISCOVER_model->max_order(array(
                    'x__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                    'x__type' => intval($_POST['note_type_id']),
                    'x__right' => intval($_POST['i__id']),
                )),
            //Referencing attributes:
            'x__type' => intval($_POST['note_type_id']),
            'x__right' => intval($_POST['i__id']),
            'x__message' => $msg_validation['input_message'],
            //Source References:
            'x__up' => $msg_validation['x__up'],
            'x__down' => $msg_validation['x__down'],
        ), true);


        //Print the challenge:
        return view_json(array(
            'status' => 1,
            'message' => view_i_notes(array_merge($session_source, $discovery, array(
                'x__down' => $session_source['e__id'],
            )), true),
        ));
    }


    function i_note_file()
    {

        //TODO: MERGE WITH FUNCTION x_upload()

        //Authenticate Player:
        $session_source = superpower_assigned();
        if (!$session_source) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing IDEA',
            ));

        } elseif (!isset($_POST['note_type_id']) || !in_array($_POST['note_type_id'], $this->config->item('sources_id_12359'))) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Note Type',
            ));

        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));

        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown error 1 while trying to save file.'.print_r($_FILES, true),
            ));

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(11063) * 1024 * 1024)) {

            return view_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . config_var(11063) . ' MB.',
            ));

        }

        //Validate Idea:
        $ideas = $this->MAP_model->fetch(array(
            'i__id' => $_POST['i__id'],
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

        $cdn_status = upload_to_cdn($temp_local, $session_source['e__id'], $_FILES[$_POST['upload_type']], true);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return view_json($cdn_status);
        }


        //Create message:
        $discovery = $this->DISCOVER_model->create(array(
            'x__player' => $session_source['e__id'],
            'x__type' => $_POST['note_type_id'],
            'x__up' => $cdn_status['cdn_source']['e__id'],
            'x__right' => intval($_POST['i__id']),
            'x__message' => '@' . $cdn_status['cdn_source']['e__id'],
            'x__sort' => 1 + $this->DISCOVER_model->max_order(array(
                    'x__type' => $_POST['note_type_id'],
                    'x__right' => $_POST['i__id'],
                )),
        ));



        //Fetch full message for proper UI display:
        $new_messages = $this->DISCOVER_model->fetch(array(
            'x__id' => $discovery['x__id'],
        ));

        //Echo message:
        view_json(array(
            'status' => 1,
            'message' => view_i_notes(array_merge($session_source, $new_messages[0], array(
                'x__down' => $session_source['e__id'],
            )), true),
        ));

    }




    function i_note_sort()
    {

        //Authenticate Player:
        $session_source = superpower_assigned(10939);
        if (!$session_source) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['new_x__sorts']) || !is_array($_POST['new_x__sorts']) || count($_POST['new_x__sorts']) < 1) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return view_json(array(
                'status' => 1,
                'message' => 'There was nothing to sort',
            ));

        }

        //Update all link orders:
        $sort_count = 0;
        foreach($_POST['new_x__sorts'] as $x__sort => $x__id) {
            if (intval($x__id) > 0) {
                $sort_count++;
                //Log update and give credit to the session Player:
                $this->DISCOVER_model->update($x__id, array(
                    'x__sort' => intval($x__sort),
                ), $session_source['e__id'], 10676 /* IDEA NOTES Ordered */);
            }
        }

        //Return success:
        return view_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }

    function i_note_edit()
    {

        //Authenticate Player:
        $session_source = superpower_assigned();
        if (!$session_source) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['x__id']) || intval($_POST['x__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing DISCOVER ID',
            ));
        } elseif (!isset($_POST['message_x__status'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Message Status',
            ));
        } elseif (!isset($_POST['x__message'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Message',
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //Validate Idea:
        $ideas = $this->MAP_model->fetch(array(
            'i__id' => $_POST['i__id'],
        ));
        if (count($ideas) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea Not Found',
            ));
        }

        //Validate Message:
        $messages = $this->DISCOVER_model->fetch(array(
            'x__id' => intval($_POST['x__id']),
            'x__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        ));
        if (count($messages) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }

        //Validate new message:
        $msg_validation = $this->DISCOVER_model->message_compile($_POST['x__message'], $session_source, $messages[0]['x__type'], $_POST['i__id']);
        if (!$msg_validation['status']) {

            //There was some sort of an error:
            return view_json($msg_validation);

        } elseif($messages[0]['x__message'] != $msg_validation['input_message']) {

            //Now update the DB:
            $this->DISCOVER_model->update(intval($_POST['x__id']), array(

                'x__message' => $msg_validation['input_message'],

                //Source References:
                'x__up' => $msg_validation['x__up'],
                'x__down' => $msg_validation['x__down'],

            ), $session_source['e__id'], 10679 /* IDEA NOTES updated Content */, update_description($messages[0]['x__message'], $msg_validation['input_message']));

        }


        //Did the message status change?
        if($messages[0]['x__status'] != $_POST['message_x__status']){

            //Are we deleting this message?
            if(in_array($_POST['message_x__status'], $this->config->item('sources_id_7360') /* ACTIVE */)){

                //If making the link public, all referenced sources must also be public...
                if(in_array($_POST['message_x__status'], $this->config->item('sources_id_7359') /* PUBLIC */)){

                    //We're publishing, make sure potential source references are also published:
                    $string_references = extract_e_references($_POST['x__message']);

                    if (count($string_references['ref_sources']) > 0) {

                        //We do have an source reference, what's its status?
                        $ref_sources = $this->SOURCE_model->fetch(array(
                            'e__id' => $string_references['ref_sources'][0],
                        ));

                        if(count($ref_sources)>0 && !in_array($ref_sources[0]['e__status'], $this->config->item('sources_id_7357') /* PUBLIC */)){
                            return view_json(array(
                                'status' => 0,
                                'message' => 'You cannot published this message because its referenced source is not yet public',
                            ));
                        }
                    }
                }

                //yes, do so and return results:
                $affected_rows = $this->DISCOVER_model->update(intval($_POST['x__id']), array(
                    'x__status' => $_POST['message_x__status'],
                ), $session_source['e__id'], 10677 /* IDEA NOTES updated Status */);

            } else {

                //New status is no longer active, so delete the IDEA NOTES:
                $affected_rows = $this->DISCOVER_model->update(intval($_POST['x__id']), array(
                    'x__status' => $_POST['message_x__status'],
                ), $session_source['e__id'], 10678 /* IDEA NOTES Unpublished */);

                //Return success:
                if($affected_rows > 0){
                    return view_json(array(
                        'status' => 1,
                        'delete_from_ui' => 1,
                        'message' => view_platform_message(12695),
                    ));
                } else {
                    return view_json(array(
                        'status' => 0,
                        'message' => 'Error trying to delete message',
                    ));
                }
            }
        }


        $sources__6186 = $this->config->item('sources__6186');

        //Print the challenge:
        return view_json(array(
            'status' => 1,
            'delete_from_ui' => 0,
            'message' => $this->DISCOVER_model->message_send($msg_validation['input_message'], $session_source, $_POST['i__id']),
            'message_new_status_icon' => '<span title="' . $sources__6186[$_POST['message_x__status']]['m_name'] . ': ' . $sources__6186[$_POST['message_x__status']]['m_desc'] . '" data-toggle="tooltip" data-placement="top">' . $sources__6186[$_POST['message_x__status']]['m_icon'] . '</span>', //This might have changed
            'success_icon' => '<span><i class="fas fa-check-circle"></i> Saved</span>',
        ));

    }



}