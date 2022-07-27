<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class I extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        cookie_check();

    }




    function i_go($i__id = 0){
        /*
         *
         * The next section is very important as it
         * manages the entire search traffic that
         * comes through /iID
         *
         * */
        if(!$i__id){
            die('missing valid ID');
        }
        $member_e = superpower_unlocked(10939);
        return redirect_message(( $member_e ? '/~' : '/' ) . $i__id . ( $member_e && isset($_GET['load__e']) ? '?load__e='.$_GET['load__e'] : '' ) );
    }


    function i_clone(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'messagCloe' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Source',
            ));
        }


        //Validate Source:
        $fetch_o = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
        ));
        if (count($fetch_o) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid parent idea ID',
            ));
        }


        //Create:
        $i_new = $this->I_model->create(array(
            'i__title' => $fetch_o[0]['e__title']." Clone",
            'i__type' => $fetch_o[0]['i__type'],
            'i__cover' => $fetch_o[0]['i__cover'],
        ), $member_e['e__id']);


        //Fetch children:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $_POST['i__id'],
        ), array(), 0) as $x){
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__left' => $i_new['i__id'],
                'x__right' => $x['x__right'],
                'x__message' => $x['x__message'],
            ));
        }


        //Parents:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__right' => $_POST['i__id'],
        ), array(), 0) as $x){
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__left' => $x['x__left'],
                'x__right' => $i_new['i__id'],
                'x__message' => $x['x__message'],
            ));
        }

        //Sources:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__right' => $_POST['i__id'],
            'x__up > 0' => null,
        ), array(), 0) as $x){
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__right' => $i_new['i__id'],
                'x__up' => $x['x__up'],
                'x__down' => $x['x__down'],
                'x__left' => $x['x__left'],
                'x__message' => $x['x__message'],
            ));
        }


        return view_json(array(
            'status' => 1,
            'new_i__id' => $i_new['i__id'],
        ));


    }


    function i_layout($i__id, $append_e__id = 0){

        //Validate/fetch Idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));
        if ( count($is) < 1) {
            return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>IDEA #' . $i__id . ' Not Found</div>', true);
        }

        $member_e = superpower_unlocked(10939); //Idea Pen?
        if(!$member_e){
            if(in_array($is[0]['i__type'], $this->config->item('n___7355'))){
                return redirect_message('/'.$i__id);
            } else {
                return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>IDEA #' . $i__id . ' is not published yet.</div>');
            }
        }

        //Import Discoveries?
        if($append_e__id>0){

            $completed = 0;
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
                'x__left' => $i__id,
            )) as $x){
                if(!count($this->X_model->fetch(array(
                    'x__up' => $append_e__id,
                    'x__down' => $x['x__source'],
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                    //Add source link:
                    $completed++;
                    $this->X_model->create(array(
                        'x__type' => e_x__type(),
                        'x__source' => ($member_e ? $member_e['e__id'] : $x['x__source']),
                        'x__up' => $append_e__id,
                        'x__down' => $x['x__source'],
                    ));
                }
            }
            echo '<div>'.$completed.' sources who played this idea added to @'.$append_e__id.'</div>';
        }

        //Load views:
        $this->load->view('header', array(
            'title' => $is[0]['i__title'],
            'i_focus' => $is[0],
        ));
        $this->load->view('i_layout', array(
            'i_focus' => $is[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }


    function i_e_add($i__id){

        //Make sure it's a logged in member:
        $member_e = superpower_unlocked(10939, true);
        $success_message = null;


        if(superpower_unlocked(12700)){

            //They can instantly join:
            $this->X_model->create(array(
                'x__type' => 4983, //IDEA SOURCES
                'x__source' => $member_e['e__id'],
                'x__up' => $member_e['e__id'],
                'x__right' => $i__id,
            ));

            $this->X_model->create(array(
                'x__type' => 13933, //JOIN AS SOURCE INSTANTLY
                'x__source' => $member_e['e__id'],
                'x__right' => $i__id,
            ));

            $success_message = '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-check-circle"></i></span>SUCCESSFULLY Joined & Notified relevant members of your intention to contribute.</div>';

        } else {

            //Pending Request
            $this->X_model->create(array(
                'x__type' => 14577, //JOIN AS SOURCE PENDING
                'x__source' => $member_e['e__id'],
                'x__right' => $i__id,
            ));

        }

        //Go back to idea:
        return redirect_message('/~'.$i__id, $success_message);

    }




    function i_navigate($previous_i__id, $current_i__id, $action){

        $trigger_next = false;
        $track_previous = 0;

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $previous_i__id,
        ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $i){
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



    function i__add()
    {

        /*
         *
         * Either creates a IDEA transaction between focus__id & link_i__id
         * OR will create a new idea with outcome i__title and then transaction it
         * to focus__id (In this case link_i__id=0)
         *
         * */

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___14685'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invaid Idea Add Type',
            ));
        } elseif (!isset($_POST['focus__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Parent Idea ID',
            ));
        } elseif (!isset($_POST['i__title']) || !isset($_POST['link_i__id']) || ( strlen($_POST['i__title']) < 1 && intval($_POST['link_i__id']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Child Idea ID',
            ));
        } elseif (strlen($_POST['i__title']) > view_memory(6404,4736)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea outcome cannot be longer than '.view_memory(6404,4736).' characters',
            ));
        }


        $x_i = array();

        if($_POST['link_i__id'] > 0){
            //Fetch transaction idea to determine idea type:
            $x_i = $this->I_model->fetch(array(
                'i__id' => intval($_POST['link_i__id']),
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));
            if(count($x_i)==0){
                //validate Idea:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea #'.$_POST['link_i__id'].' is not active',
                ));
            }
        }

        //All seems good, go ahead and try to create/link the Idea:
        return view_json($this->I_model->create_or_link($_POST['x__type'], trim($_POST['i__title']), $member_e['e__id'], $_POST['focus__id'], $_POST['link_i__id']));

    }

    function i_view_body_i(){
        //Authenticate Member:
        if (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1 || !isset($_POST['counter']) || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo view_body_i($_POST['x__type'], $_POST['counter'], $_POST['i__id']);
        }
    }

    function i_load_coin(){

        if (!isset($_POST['i__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {

            $ui = '';
            $listed_items = 0;
            if($_POST['x__type']==12274 || $_POST['x__type']==6255){

                //SOURCES
                $e___6177 = $this->config->item('e___6177'); //Source Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                $current_e = ( substr($_POST['first_segment'], 0, 1)=='@' ? intval(substr($_POST['first_segment'], 1)) : 0 );
                foreach(view_coins_i($_POST['x__type'], $_POST['i__id'], 1, false, view_memory(6404,13206)) as $source_e) {
                    if(isset($source_e['is_break'])){
                        $ui .= '<hr />';
                    } elseif(isset($source_e['e__id'])){
                        $ui .= view_coin_line('/@'.$source_e['e__id'], $source_e['e__id']==$current_e, $e___4593[$source_e['x__type']]['m__cover'], $e___6177[$source_e['e__type']]['m__cover'], view_cover(12274,$source_e['e__cover']), $source_e['e__title'], view_x__message($source_e['x__message'],$source_e['x__type']));
                        $listed_items++;
                    }
                }

            } elseif($_POST['x__type']==13542 || $_POST['x__type']==11019 || $_POST['x__type']==12273){

                //IDEAS
                $e___4737 = $this->config->item('e___4737'); //Idea Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                $superpower_10939 = superpower_active(10939, true);
                $current_i = ( substr($_POST['first_segment'], 0, 1)=='~' ? intval(substr($_POST['first_segment'], 1)) : 0 );
                foreach(view_coins_i($_POST['x__type'], $_POST['i__id'], 1, false, view_memory(6404,13206)) as $next_i) {
                    if(isset($next_i['is_break'])){
                        $ui .= '<hr />';
                    } elseif(isset($next_i['i__id'])){
                        $ui .= view_coin_line('/~'.$next_i['i__id'], $next_i['i__id']==$current_i, $e___4593[$next_i['x__type']]['m__cover'], $e___4737[$next_i['i__type']]['m__cover'], view_cover(12273,$next_i['i__cover']), view_i_title($next_i), view_x__message($next_i['x__message'],$next_i['x__type']));
                        $listed_items++;
                    }
                }

            }

            if($listed_items < $_POST['counter']){
                //We have more to show:
                $ui .= view_coin_line('/~'.$_POST['i__id'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
            }

            echo $ui;
        }
    }


    function load_message_27963(){

        //Authenticate Member:
        $member_e = superpower_unlocked(10939); //Superpower not required as it may be just a comment

        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));

        }

        $message = '';
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $_POST['i__id'],
        ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $x) {
            $message .= $x['x__message']."\n";
        }


        return view_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }

    function i_note_add_text()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked(); //Superpower not required as it may be just a comment

        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));

        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___4485'))) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }


        //Fetch/Validate the idea:
        $is = $this->I_model->fetch(array(
            'i__id' => intval($_POST['i__id']),
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if(count($is)<1){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->X_model->message_compile($_POST['x__message'], false, $member_e, $_POST['x__type'], $_POST['i__id']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return view_json($msg_validation);
        }

        //Create Message:
        $x = $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__spectrum' => 1 + $this->X_model->max_spectrum(array(
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'x__type' => intval($_POST['x__type']),
                    'x__right' => intval($_POST['i__id']),
                )),
            //Referencing attributes:
            'x__type' => intval($_POST['x__type']),
            'x__right' => intval($_POST['i__id']),
            'x__message' => $msg_validation['clean_message'],
            'x__up' => ( count($msg_validation['note_references']) ? $msg_validation['note_references'][0] : 0 ),
        ), true);


        //Save Extra References if any:
        $this->X_model->save_note_extra_sources($x['x__id'], $member_e['e__id'], $msg_validation['note_references'], intval($_POST['i__id']), false);


        //Print the challenge:
        return view_json(array(
            'status' => 1,
            'message' => view_i_note($_POST['x__type'], false, array_merge($member_e, $x, array(
                'x__down' => $member_e['e__id'],
            )), true),
        ));
    }


    function i_note_add_file()
    {

        //TODO: MERGE WITH FUNCTION x_upload() ?

        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing the IDEA',
            ));

        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___12359'))) {

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

            $error_message = 'This file is not supported. Try another file to continue.';

            $this->X_model->create(array(
                'x__message' => $error_message,
                'x__source' => $member_e['e__id'],
                'x__left' => $_POST['i__id'],
                'x__up' => $_POST['x__type'],
                'x__type' => 4246, //Platform Bug Reports
                'x__metadata' => array(
                    '$_FILES' => $_FILES,
                    '$_POST' => $_POST,
                ),
            ));

            return view_json(array(
                'status' => 0,
                'message' => $error_message,
            ));

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (view_memory(6404,13572) * 1024 * 1024)) {

            return view_json(array(
                'status' => 0,
                'message' => 'File is larger than the maximum allowed file size of ' . view_memory(6404,13572) . ' MB. Try a smaller file to continue.',
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


        //Attempt to store in Cloud on Amazon S3:
        if (isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type']) > 0) {
            $mime = $_FILES[$_POST['upload_type']]['type'];
        } else {
            $mime = mime_content_type($temp_local);
        }

        $cdn_status = upload_to_cdn($temp_local, $member_e['e__id'], $_FILES[$_POST['upload_type']], true);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return view_json($cdn_status);
        }


        //Create message:
        $x = $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__type' => $_POST['x__type'],
            'x__up' => $cdn_status['cdn_e']['e__id'],
            'x__right' => intval($_POST['i__id']),
            'x__message' => '@' . $cdn_status['cdn_e']['e__id'],
            'x__spectrum' => 1 + $this->X_model->max_spectrum(array(
                    'x__type' => $_POST['x__type'],
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
            'new_source' => '@' . $cdn_status['cdn_e']['e__id'],
            'message' => view_i_note($_POST['x__type'], false, array_merge($member_e, $new_messages[0], array(
                'x__down' => $member_e['e__id'],
            )), true),
        ));

    }




    function i_note_sort()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['new_x__spectrums']) || !is_array($_POST['new_x__spectrums']) || count($_POST['new_x__spectrums']) < 1) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return view_json(array(
                'status' => 1,
                'message' => 'There was nothing to sort',
            ));

        }

        //Update all transaction orders:
        $sort_count = 0;
        foreach($_POST['new_x__spectrums'] as $x__spectrum => $x__id) {
            if (intval($x__id) > 0) {
                $sort_count++;
                //Log update and give credit to the session Member:
                $this->X_model->update($x__id, array(
                    'x__spectrum' => intval($x__spectrum),
                ), $member_e['e__id'], 10676 /* IDEA NOTES Ordered */);
            }
        }

        //Return success:
        return view_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }



    function view_load_page_i()
    {

        $superpower_10939 = superpower_active(10939, true); //SUPERPOWER OF IDEAGING
        $items_per_page = view_memory(6404,11064);
        $focus__id = intval($_POST['focus__id']);
        $page = intval($_POST['page']);
        $query_filters = array(
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => $focus__id,
        );

        //Fetch & display next batch of children:
        $extra_items = $this->X_model->fetch($query_filters, array('x__right'), $items_per_page, ($page * $items_per_page), array('i__spectrum' => 'DESC'));

        foreach($extra_items as $item) {
            echo view_i(13550, 0, null, $item);
        }

        //Count total children:
        $child_count = $this->X_model->fetch($query_filters, array('x__right'), 0, 0, array(), 'COUNT(x__id) as totals');

        //Do we need another load more button?
        if ($child_count[0]['totals'] > (($page * $items_per_page) + count($extra_items))) {
            echo view_load_page_i($_POST['x__type'], ($page + 1), $items_per_page, $child_count[0]['totals']);
        }

    }


    function i_note_update_text()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['x__id']) || intval($_POST['x__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing TRANSACTION ID',
            ));
        } elseif (!isset($_POST['x__message'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Message Text',
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
        $msg_validation = $this->X_model->message_compile($_POST['x__message'], false, $member_e, $messages[0]['x__type'], $_POST['i__id']);
        if (!$msg_validation['status']) {

            //There was some sort of an error:
            return view_json($msg_validation);

        } elseif($messages[0]['x__message'] != $msg_validation['clean_message']) {

            //Now update the DB:
            $this->X_model->update(intval($_POST['x__id']), array(
                'x__message' => $msg_validation['clean_message'],
                'x__up' => ( count($msg_validation['note_references']) ? $msg_validation['note_references'][0] : 0 ),
            ), $member_e['e__id'], 10679 /* IDEA NOTES updated Content */, update_description($messages[0]['x__message'], $msg_validation['clean_message']));

            //Update extra references:
            $this->X_model->save_note_extra_sources($x['x__id'], $member_e['e__id'], $msg_validation['note_references'], $messages['x__right'], true);

        }


        $e___6186 = $this->config->item('e___6186');

        //Print the challenge:
        return view_json(array(
            'status' => 1,
            'message' => $this->X_model->message_view($msg_validation['clean_message'], false, $member_e, $_POST['i__id']),
        ));

    }



    function i_remove_note(){

        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['x__id']) || intval($_POST['x__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing TRANSACTION ID',
            ));
        }

        //New status is no longer active, so delete the IDEA NOTES:
        $affected_rows = $this->X_model->update(intval($_POST['x__id']), array(
            'x__status' => 6173,
        ), $member_e['e__id'], 13579);

        //Return success:
        if($affected_rows > 0){
            return view_json(array(
                'status' => 1,
            ));
        } else {
            return view_json(array(
                'status' => 0,
                'message' => 'Error trying to delete this note',
            ));
        }

    }




    function save_message_27963(){

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        $e___12112 = $this->config->item('e___12112');

        if(!isset($_POST['field_value'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing message input',
            ));

        } elseif (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif(!isset($_POST['i__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if(!count($is)){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }


        $errors = false;
        $line_number = 0;
        $msg_validations = array();
        $input_clean = ''; //Generate new clean message
        $textarea_content = '';
        foreach(explode(PHP_EOL, $_POST['field_value']) as $message_input) {

            if(!strlen(trim($message_input))){
                //Ignore empty line:
                continue;
            }

            $line_number++;

            //Validate message:
            $msg_validation = $this->X_model->message_compile($message_input, false, $member_e, 0, $is[0]['i__id']);


            //Did we have ane error in message validation?
            if (!$msg_validation['status']) {
                $errors .= '<br /><span class="inline-block">&nbsp;</span>Line #'.$line_number.': '.$msg_validation['message'];
                $input_clean .= $message_input."\n\n";
            } else {
                array_push($msg_validations, $msg_validation);
                //Add to clean messages:
                $input_clean .= $msg_validation['clean_message']."\n\n";
            }

        }

        //Did we catch any errors?
        if($errors){
            return view_json(array(
                'status' => 0,
                'message' => $errors,
            ));
        }


        //Validation complete! Let's update messages...
        //DELETE all current notes, if any:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 4231,
            'x__right' => $is[0]['i__id'],
        ), array(), 0) as $x) {

            //Remove Note:
            $this->X_model->update($x['x__id'], array(
                'x__status' => 6173,
            ), $member_e['e__id'], 13579);

            //Remove Extra References:
            $this->X_model->save_note_extra_sources($x['x__id'], $member_e['e__id'], array(), $is[0]['i__id'], true);
        }


        foreach($msg_validations as $count => $msg_validation) {

            //SAVE this message:
            $x = $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__spectrum' => ($count + 1),
                'x__type' => 4231,
                'x__right' => $is[0]['i__id'],
                'x__message' => $msg_validation['clean_message'],
                'x__up' => ( count($msg_validation['note_references']) ? $msg_validation['note_references'][0] : 0 ),
            ));

            //Update extra references:
            $this->X_model->save_note_extra_sources($x['x__id'], $member_e['e__id'], $msg_validation['note_references'], $is[0]['i__id'], false);

            //GENERATE New Preview:
            $textarea_content .= $this->X_model->message_view($msg_validation['clean_message'], false, $member_e, $is[0]['i__id']);

        }

        if(!strlen($textarea_content)){
            $textarea_content = '<i class="no-message">Write Message...</i>';
        }

        //Update Search Index:
        update_algolia(12273, $is[0]['i__id']);


        return view_json(array(
            'status' => 1,
            'message' => $textarea_content,
        ));

    }

}