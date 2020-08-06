<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class I extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }


    function i_create(){

        $e___6201 = $this->config->item('e___6201'); //Idea Table
        $user_e = superpower_assigned(10939);
        if (!$user_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['newIdeaTitle'])) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return view_json(array(
                'status' => 0,
                'message' => 'Missing '.$e___6201[4736]['m_title'],
            ));

        }

        //Validate Title:
        $i__title_validation = i__title_validate($_POST['newIdeaTitle']);
        if(!$i__title_validation['status']){
            //We had an error, return it:
            return view_json($i__title_validation);
        }


        //Create Idea:
        $i = $this->I_model->create_or_link($i__title_validation['i_clean_title'], $user_e['e__id']);


        //Add additional source if different than user:
        if($user_e['e__id']!=$_POST['e_focus_id']){
            $this->X_model->create(array(
                'x__type' => 4983, //IDEA SOURCES
                'x__source' => $user_e['e__id'],
                'x__up' => $_POST['e_focus_id'],
                'x__message' => '@'.$_POST['e_focus_id'],
                'x__right' => $i['new_i__id'],
            ));
        }

        //Move Existing Bookmarks by one:
        $x__sort = 2;
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 10573, //MY IDEAS
            'x__up' => $user_e['e__id'], //For this user
        ), array(), 0, 0, array('x__sort' => 'ASC')) as $u_i){
            $this->X_model->update($u_i['x__id'], array(
                'x__sort' => $x__sort,
            ), $user_e['e__id']);
            $x__sort++;
        }

        //Add to top of my ideas:
        $this->X_model->create(array(
            'x__type' => 10573, //MY IDEAS
            'x__source' => $user_e['e__id'],
            'x__right' => $i['new_i__id'],
            'x__up' => $user_e['e__id'],
            'x__message' => '@'.$user_e['e__id'],
            'x__sort' => 1, //Top of the list
        ), true);

        return view_json(array(
            'status' => 1,
            'message' => '<span class="icon-block"><i class="fas fa-check-circle idea"></i></span>Success! Redirecting now...',
            'i__id' => $i['new_i__id'],
        ));

    }


    function i_go($i__id){
        /*
         *
         * The next section is very important as it
         * manages the entire search traffic that
         * comes through /iID
         *
         * */
        $e_of_i = e_of_i($i__id);
        return redirect_message(( $e_of_i ? '/~' : '/' ) . $i__id . ( $e_of_i && isset($_GET['filter__e']) ? '?filter__e='.$_GET['filter__e'] : '' ) );
    }


    function i_coin($i__id){

        //Validate/fetch Idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));
        if ( count($is) < 1) {
            return redirect_message(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>IDEA #' . $i__id . ' Not Found</div>');
        }


        $user_e = superpower_assigned(10939); //Idea Pen?
        $is_public = in_array($is[0]['i__status'], $this->config->item('n___7355'));

        if(!$user_e){
            if($is_public){
                return redirect_message('/'.$i__id);
            } else {
                return redirect_message(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>IDEA #' . $i__id . ' is not published yet.</div>');
            }
        }


        //Mass List Editing?
        if (superpower_active(13403, true) && isset($_POST['mass_action_e__id']) && isset($_POST['mass_value1_'.$_POST['mass_action_e__id']]) && isset($_POST['mass_value2_'.$_POST['mass_action_e__id']])) {

            //Process mass action:
            $process_mass_action = $this->I_model->mass_update($i__id, intval($_POST['mass_action_e__id']), $_POST['mass_value1_'.$_POST['mass_action_e__id']], $_POST['mass_value2_'.$_POST['mass_action_e__id']], $user_e['e__id']);

            //Pass-on results to UI:
            $message = '<div class="alert '.( $process_mass_action['status'] ? 'alert-warning' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.$process_mass_action['message'].'</div>';

        } else {

            //Just Viewing:
            $message = null;
            $new_order = ( $this->session->userdata('session_page_count') + 1 );
            $this->session->set_userdata('session_page_count', $new_order);
            $this->X_model->create(array(
                'x__source' => $user_e['e__id'],
                'x__type' => 4993, //User Opened Idea
                'x__right' => $i__id,
                'x__sort' => $new_order,
            ));

        }



        //Load views:
        $this->load->view('header', array(
            'title' => $is[0]['i__title'],
            'i_focus' => $is[0],
            'flash_message' => $message, //Possible mass-action message for UI:
        ));
        $this->load->view('i/layout', array(
            'i_focus' => $is[0],
            'user_e' => $user_e,
        ));
        $this->load->view('footer');

    }


    function i_e_request($i__id){

        //Make sure it's a logged in user:
        $user_e = superpower_assigned(null, true);

        if(count($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 12450,
            'x__source' => $user_e['e__id'],
            'x__right' => $i__id,
        )))){
            return redirect_message('/~'.$i__id, '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>You have previously requested to join this idea. No further action is necessary.</div>');

        }

        //Inform moderators:
        $this->X_model->create(array(
            'x__type' => 12450,
            'x__source' => $user_e['e__id'],
            'x__right' => $i__id,
        ));

        //Go back to idea:
        return redirect_message('/~'.$i__id, '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-check-circle"></i></span>Successfully submitted your request to become a source for this idea. You will receive an update once your request has been reviewed.</div>');

    }

    function i_e_add($i__id){

        //Make sure it's a logged in user:
        $user_e = superpower_assigned(10984, true);

        //Idea Source:
        $this->X_model->create(array(
            'x__type' => 4983, //IDEA SOURCES
            'x__source' => $user_e['e__id'],
            'x__up' => $user_e['e__id'],
            'x__message' => '@'.$user_e['e__id'],
            'x__right' => $i__id,
        ));

        //Go back to idea:
        return redirect_message('/~'.$i__id, '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-check-circle"></i></span>SUCCESSFULLY JOINED</div>');

    }


    function i_navigate($previous_i__id, $current_i__id, $action){

        $trigger_next = false;
        $track_previous = 0;

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $previous_i__id,
        ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $i){
            if($action=='next'){
                if($trigger_next){
                    return redirect_message('/~' . $i['i__id'] );
                }
                if($i['i__id']==$current_i__id){
                    $trigger_next = true;
                }
            } elseif($action=='previous'){
                if($i['i__id']==$current_i__id){
                    if($track_previous > 0){
                        return redirect_message('/~' . $track_previous );
                    } else {
                        //First item:
                        break;
                    }
                } else {
                    $track_previous = $i['i__id'];
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
        $var_index = var_index();
        $deletion_redirect = null;
        $delete_element = null;

        //Authenticate User:
        $user_e = superpower_assigned();
        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Target Idea ID',
            ));
        } elseif (!isset($_POST['focus_i__id']) || intval($_POST['focus_i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Loaded Idea ID',
            ));
        } elseif (!isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Transaction ID',
            ));
        } elseif (!isset($_POST['element_id']) || intval($_POST['element_id']) < 1 || !array_key_exists($_POST['element_id'], $var_index) || !count($this->config->item('n___'.$_POST['element_id']))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Variable ID ['.$_POST['element_id'].']',
            ));
        } elseif (!isset($_POST['new_e__id']) || intval($_POST['new_e__id']) < 1 || !in_array($_POST['new_e__id'], $this->config->item('n___'.$_POST['element_id']))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            ));
        }

        if($_POST['x__id'] > 0){

            //Validate the transaction update Type ID:
            $e___4527 = $this->config->item('e___4527');
            if(!is_array($e___4527[$_POST['element_id']]['m_profile']) || !count($e___4527[$_POST['element_id']]['m_profile'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing @'.$_POST['element_id'].' in @4527',
                ));
            }

            //Find the single discover type in parent transactions:
            $x_update_types = array_intersect($this->config->item('n___4593'), $e___4527[$_POST['element_id']]['m_profile']);
            if(count($x_update_types)!=1){
                return view_json(array(
                    'status' => 0,
                    'message' => '@'.$_POST['element_id'].' has '.count($x_update_types).' parents that belog to @4593 [Should be exactly 1]',
                ));
            }

            //All good, Update Transaction:
            $this->X_model->update($_POST['x__id'], array(
                $var_index[$_POST['element_id']] => $_POST['new_e__id'],
            ), $user_e['e__id'], end($x_update_types));

        } else {


            //See if Idea is being deleted:
            if($_POST['element_id']==4737){

                //Delete all idea transactions?
                if(!in_array($_POST['new_e__id'], $this->config->item('n___7356'))){

                    //Determine what to do after deleted:
                    if($_POST['i__id'] == $_POST['focus_i__id']){

                        //Since we're removing the FOCUS IDEA we need to move to the first parent idea:
                        foreach($this->I_model->recursive_parents($_POST['i__id'], true, false) as $grand_parent_ids) {
                            foreach($grand_parent_ids as $previous_i__id) {
                                $deletion_redirect = '/~'.$previous_i__id; //First parent in first branch of parents
                                break;
                            }
                        }

                        //Go to main page if no parent found:
                        if(!$deletion_redirect){
                            $deletion_redirect = ( intval($this->session->userdata('session_time_7260')) ? '/e/plugin/7260' : home_url() );
                        }

                    } else {

                        if(!$delete_element){

                            //Just delete from UI using JS:
                            $delete_element = '.i_line_' . $_POST['i__id'];

                        }

                    }

                    //Delete all transactions:
                    $this->I_model->remove($_POST['i__id'] , $user_e['e__id']);

                //Notify moderators of Feature request? Only if they don't have the powers themselves:
                } elseif(in_array($_POST['new_e__id'], $this->config->item('n___12138')) && !superpower_assigned(10984) && !count($this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type' => 12453, //Idea Feature Request
                        'x__source' => $user_e['e__id'],
                        'x__right' => $_POST['i__id'],
                    )))){

                    $this->X_model->create(array(
                        'x__type' => 12453, //Idea Feature Request
                        'x__source' => $user_e['e__id'],
                        'x__right' => $_POST['i__id'],
                    ));

                }
            }

            //Update Idea:
            $this->I_model->update($_POST['i__id'], array(
                $var_index[$_POST['element_id']] => $_POST['new_e__id'],
            ), true, $user_e['e__id']);

        }


        return view_json(array(
            'status' => 1,
            'deletion_redirect' => $deletion_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function i_remove(){

        //Authenticate User:
        $user_e = superpower_assigned();
        if (!$user_e) {
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
                'message' => 'Missing Transaction ID',
            ));
        }

        //Delete this transaction:
        $this->X_model->update($_POST['x__id'], array(
            'x__status' => 6173, //Transaction Removed
        ), $user_e['e__id'], 10686 /* Idea Transaction Unpublished */);

        return view_json(array(
            'status' => 1,
            'message' => 'Success',
        ));

    }


    function i_add()
    {

        /*
         *
         * Either creates a IDEA transaction between i_x_id & i_x_child_id
         * OR will create a new idea with outcome i__title and then transaction it
         * to i_x_id (In this case i_x_child_id=0)
         *
         * */

        //Authenticate User:
        $user_e = superpower_assigned(10939);
        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['i_x_id']) || intval($_POST['i_x_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Parent Idea ID',
            ));
        } elseif (!isset($_POST['is_parent']) || !in_array(intval($_POST['is_parent']), array(0,1))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Is Parent setting',
            ));
        } elseif (!isset($_POST['i__title']) || !isset($_POST['i_x_child_id']) || ( strlen($_POST['i__title']) < 1 && intval($_POST['i_x_child_id']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Child Idea ID',
            ));
        } elseif (strlen($_POST['i__title']) > config_var(4736)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea outcome cannot be longer than '.config_var(4736).' characters',
            ));
        } elseif($_POST['i_x_child_id'] >= 2147483647){
            return view_json(array(
                'status' => 0,
                'message' => 'Value must be less than 2147483647',
            ));
        }


        $new_i_type = 6677; //Idea Read & Next
        $x_i = array();

        if($_POST['i_x_child_id'] > 0){

            //Fetch transaction idea to determine idea type:
            $x_i = $this->I_model->fetch(array(
                'i__id' => intval($_POST['i_x_child_id']),
                'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));

            if(count($x_i)==0){
                //validate Idea:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea #'.$_POST['i_x_child_id'].' is not active',
                ));
            }

            if(!intval($_POST['is_parent']) && in_array($x_i[0]['i__type'], $this->config->item('n___7712'))){
                $new_i_type = 6914; //Require All
            }
        }

        //All seems good, go ahead and try creating the Idea:
        return view_json($this->I_model->create_or_link(trim($_POST['i__title']), $user_e['e__id'], $_POST['i_x_id'], intval($_POST['is_parent']), 6184, $new_i_type, $_POST['i_x_child_id']));

    }

    function i_sort_save()
    {

        //Authenticate User:
        $user_e = superpower_assigned(10939);
        if (!$user_e) {
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
            $previous_i = $this->I_model->fetch(array(
                'i__id' => intval($_POST['i__id']),
            ));
            if (count($previous_i) < 1) {
                view_json(array(
                    'status' => 0,
                    'message' => 'Invalid i__id',
                ));
            } else {

                //Update them all:
                foreach($_POST['new_x__sorts'] as $rank => $x__id) {
                    $this->X_model->update(intval($x__id), array(
                        'x__sort' => intval($rank),
                    ), $user_e['e__id'], 10675 /* Ideas Ordered by User */);
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

        //Authenticate User:
        $user_e = superpower_assigned();

        if (!$user_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));

        } elseif (!isset($_POST['note_type_id']) || !in_array($_POST['note_type_id'], $this->config->item('n___4485'))) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }


        //Fetch/Validate the idea:
        $is = $this->I_model->fetch(array(
            'i__id' => intval($_POST['i__id']),
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if(count($is)<1){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->X_model->message_compile($_POST['x__message'], $user_e, $_POST['note_type_id'], $_POST['i__id']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return view_json($msg_validation);
        }

        //Create Message:
        $x = $this->X_model->create(array(
            'x__source' => $user_e['e__id'],
            'x__sort' => 1 + $this->X_model->max_sort(array(
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
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
            'message' => view_13574(array_merge($user_e, $x, array(
                'x__down' => $user_e['e__id'],
            )), true),
        ));
    }


    function i_note_file()
    {

        //TODO: MERGE WITH FUNCTION x_upload()

        //Authenticate User:
        $user_e = superpower_assigned();
        if (!$user_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing IDEA',
            ));

        } elseif (!isset($_POST['note_type_id']) || !in_array($_POST['note_type_id'], $this->config->item('n___12359'))) {

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

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(13572) * 1024 * 1024)) {

            return view_json(array(
                'status' => 0,
                'message' => 'File is larger than the maximum allowed file size of ' . config_var(13572) . ' MB.',
            ));

        }

        //Validate Idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
        ));
        if(count($is)<1){
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

        $cdn_status = upload_to_cdn($temp_local, $user_e['e__id'], $_FILES[$_POST['upload_type']], true);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return view_json($cdn_status);
        }


        //Create message:
        $x = $this->X_model->create(array(
            'x__source' => $user_e['e__id'],
            'x__type' => $_POST['note_type_id'],
            'x__up' => $cdn_status['cdn_e']['e__id'],
            'x__right' => intval($_POST['i__id']),
            'x__message' => '@' . $cdn_status['cdn_e']['e__id'],
            'x__sort' => 1 + $this->X_model->max_sort(array(
                    'x__type' => $_POST['note_type_id'],
                    'x__right' => $_POST['i__id'],
                )),
        ));



        //Fetch full message for proper UI display:
        $new_messages = $this->X_model->fetch(array(
            'x__id' => $x['x__id'],
        ));

        //Echo message:
        view_json(array(
            'status' => 1,
            'message' => view_13574(array_merge($user_e, $new_messages[0], array(
                'x__down' => $user_e['e__id'],
            )), true),
        ));

    }




    function i_note_sort()
    {

        //Authenticate User:
        $user_e = superpower_assigned(10939);
        if (!$user_e) {

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

        //Update all transaction orders:
        $sort_count = 0;
        foreach($_POST['new_x__sorts'] as $x__sort => $x__id) {
            if (intval($x__id) > 0) {
                $sort_count++;
                //Log update and give credit to the session User:
                $this->X_model->update($x__id, array(
                    'x__sort' => intval($x__sort),
                ), $user_e['e__id'], 10676 /* IDEA NOTES Ordered */);
            }
        }

        //Return success:
        return view_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }

    function save_13574()
    {

        //Authenticate User:
        $user_e = superpower_assigned();
        if (!$user_e) {
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
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
        ));
        if (count($is) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea Not Found',
            ));
        }

        //Validate Message:
        $messages = $this->X_model->fetch(array(
            'x__id' => intval($_POST['x__id']),
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ));
        if (count($messages) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }

        //Validate new message:
        $msg_validation = $this->X_model->message_compile($_POST['x__message'], $user_e, $messages[0]['x__type'], $_POST['i__id']);
        if (!$msg_validation['status']) {

            //There was some sort of an error:
            return view_json($msg_validation);

        } elseif($messages[0]['x__message'] != $msg_validation['input_message']) {

            //Now update the DB:
            $this->X_model->update(intval($_POST['x__id']), array(

                'x__message' => $msg_validation['input_message'],

                //Source References:
                'x__up' => $msg_validation['x__up'],
                'x__down' => $msg_validation['x__down'],

            ), $user_e['e__id'], 10679 /* IDEA NOTES updated Content */, update_description($messages[0]['x__message'], $msg_validation['input_message']));

        }


        //Did the message status change?
        if($messages[0]['x__status'] != $_POST['message_x__status']){

            //Are we deleting this message?
            if(in_array($_POST['message_x__status'], $this->config->item('n___7360') /* ACTIVE */)){

                //If making the transaction public, all referenced sources must also be public...
                if(in_array($_POST['message_x__status'], $this->config->item('n___7359') /* PUBLIC */)){

                    //We're publishing, make sure potential source references are also published:
                    $string_references = extract_e_references($_POST['x__message']);

                    if (count($string_references['ref_e']) > 0) {

                        //We do have an source reference, what's its status?
                        $ref_e = $this->E_model->fetch(array(
                            'e__id' => $string_references['ref_e'][0],
                        ));

                        if(count($ref_e)>0 && !in_array($ref_e[0]['e__status'], $this->config->item('n___7357') /* PUBLIC */)){
                            return view_json(array(
                                'status' => 0,
                                'message' => 'You cannot published this message because its referenced source is not yet public',
                            ));
                        }
                    }
                }

                //yes, do so and return results:
                $affected_rows = $this->X_model->update(intval($_POST['x__id']), array(
                    'x__status' => $_POST['message_x__status'],
                ), $user_e['e__id'], 10677 /* IDEA NOTES updated Status */);

            } else {

                //New status is no longer active, so delete the IDEA NOTES:
                $affected_rows = $this->X_model->update(intval($_POST['x__id']), array(
                    'x__status' => $_POST['message_x__status'],
                ), $user_e['e__id'], 10678 /* IDEA NOTES Unpublished */);

                //Return success:
                if($affected_rows > 0){
                    return view_json(array(
                        'status' => 1,
                        'delete_from_ui' => 1,
                        'message' => view_12687(12695),
                    ));
                } else {
                    return view_json(array(
                        'status' => 0,
                        'message' => 'Error trying to delete message',
                    ));
                }
            }
        }


        $e___6186 = $this->config->item('e___6186');

        //Print the challenge:
        return view_json(array(
            'status' => 1,
            'delete_from_ui' => 0,
            'message' => $this->X_model->message_send($msg_validation['input_message'], $user_e, $_POST['i__id']),
            'message_new_status_icon' => '<span title="' . $e___6186[$_POST['message_x__status']]['m_title'] . ': ' . $e___6186[$_POST['message_x__status']]['m_message'] . '" data-toggle="tooltip" data-placement="top">' . $e___6186[$_POST['message_x__status']]['m_icon'] . '</span>', //This might have changed
        ));

    }



}