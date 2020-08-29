<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class X extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));

    }

    function index(){

        $user_e = superpower_assigned();
        if($user_e){
            redirect_message('/@'.$user_e['e__id']);
        }

        $is = $this->I_model->fetch(array(
            'i__id' => config_var(12137),
        ));

        //Load header:
        $this->load->view('header', array(
            'title' => $is[0]['i__title'],
        ));
        $this->load->view('x/home', array(
            'i' => $is[0],
        ));
        $this->load->view('footer');

    }


    function x_list(){

        /*
         *
         * List all Transactions on reverse chronological order
         * and Display Status for ideas, sources and
         * transactions.
         *
         * */

        //Load header:
        $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION

        $this->load->view('header', array(
            'title' => $e___11035[4341]['m_title'],
        ));
        $this->load->view('x/ledger');
        $this->load->view('footer');

    }

    function x_load(){

        /*
         * Loads the list of transactions based on the
         * filters passed on.
         *
         * */

        $filters = unserialize($_POST['x_filters']);
        $joined_by = unserialize($_POST['x_joined_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $query_offset = (($page_num-1)*config_var(11064));
        $user_e = superpower_assigned();

        $message = '';

        //Fetch transactions and total transaction counts:
        $x = $this->X_model->fetch($filters, $joined_by, config_var(11064), $query_offset);
        $x_count = $this->X_model->fetch($filters, $joined_by, 0, 0, array(), 'COUNT(x__id) as total_count');
        $total_items_loaded = ($query_offset+count($x));
        $has_more_x = ($x_count[0]['total_count'] > 0 && $total_items_loaded < $x_count[0]['total_count']);


        //Display filter:
        if($total_items_loaded > 0){

            if($page_num==1 && !$filters){

                //First page message:
                $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
                $e___12467 = $this->config->item('e___12467'); //MENCH COINS

                $message .= '<div class="montserrat x-info">'.$e___11035[4341]['m_icon'].' '.$e___11035[4341]['m_title'].' has '.number_format($x_count[0]['total_count'] , 0).' TRANSACTIONS indexing <span class="source inline-block">'.$e___12467[12274]['m_icon'].' '.number_format(count_unique_coins(12274), 0).' '.$e___12467[12274]['m_title'].'</span> & <span class="idea inline-block">'.$e___12467[12273]['m_icon'].' '.number_format(count_unique_coins(12273), 0).' '.$e___12467[12273]['m_title'].'</span> with <span class="discover inline-block">'.$e___12467[6255]['m_icon'].' '.number_format(count_unique_coins(6255), 0).' '.$e___12467[6255]['m_title'].'</span>. Here are the last '.$total_items_loaded.' TRANSACTIONS:</div>';

            } else {
                //Subsequent messages:
                $message .= '<div class="montserrat x-info"><span class="icon-block"><i class="fas fa-file-search"></i></span>'.( $has_more_x && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) . number_format($x_count[0]['total_count'] , 0) .' TRANSACTIONS:</div>';
            }

        }


        if(count($x)>0){

            $message .= '<div class="list-group list-grey">';
            foreach($x as $x) {

                $message .= view_x($x);

                if($user_e && strlen($x['x__message'])>0 && strlen($_POST['x__message_search'])>0 && strlen($_POST['x__message_replace'])>0 && substr_count($x['x__message'], $_POST['x__message_search'])>0){

                    $new_content = str_replace($_POST['x__message_search'],trim($_POST['x__message_replace']),$x['x__message']);

                    $this->X_model->update($x['x__id'], array(
                        'x__message' => $new_content,
                    ), $user_e['e__id'], 12360, update_description($x['x__message'], $new_content));

                    $message .= '<div class="msg alert alert-info" role="alert"><i class="fas fa-check-circle"></i> Replaced ['.$_POST['x__message_search'].'] with ['.trim($_POST['x__message_replace']).']</div>';

                }

            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_x){
                $message .= '<div id="x_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn btn-discover" onclick="x_load(x_filters, x_joined_by, '.$next_page.');"><span class="icon-block"><i class="fas fa-search-plus"></i></span>Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><span class="icon-block"><i class="far fa-check-circle"></i></span>All '.$x_count[0]['total_count'].' transactions have been loaded</div>';

            }

        } else {

            //Show no transaction warning:
            $message .= '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No Transactions found with the selected filters. Modify filters and try again.</div>';

        }


        return view_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }



    function x_type_preview()
    {

        if (!isset($_POST['x__message']) || !isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing inputs',
            ));
        }

        //Will Contain every possible User Transaction Connector:
        $e___4592 = $this->config->item('e___4592');

        //See what this is:
        $detected_x_type = x_detect_type($_POST['x__message']);

        if(!$_POST['x__id'] && !in_array($detected_x_type['x__type'], $this->config->item('n___4537'))){

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));

        } elseif (!$detected_x_type['status'] && isset($detected_x_type['url_previously_existed']) && $detected_x_type['url_previously_existed']) {

            //See if this is duplicate to either transaction:
            $e_x = $this->X_model->fetch(array(
                'x__id' => $_POST['x__id'],
                'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //User URL Transactions
            ));

            //Are they both different?
            if (count($e_x) < 1 || ($e_x[0]['x__up'] != $detected_x_type['e_url']['e__id'] && $e_x[0]['x__down'] != $detected_x_type['e_url']['e__id'])) {
                //return error:
                return view_json($detected_x_type);
            }

        }



        return view_json(array(
            'status' => 1,
            'x__type_preview' => '<b class="montserrat doupper '.extract_icon_color($e___4592[$detected_x_type['x__type']]['m_icon']).'">' . $e___4592[$detected_x_type['x__type']]['m_icon'] . ' ' . $e___4592[$detected_x_type['x__type']]['m_title'] . '</b>',
            'x__message_preview' => ( in_array($detected_x_type['x__type'], $this->config->item('n___12524')) ? '<span class="paddingup">'.view_x__message($_POST['x__message'], $detected_x_type['x__type']).'</span>' : ''),
        ));

    }




    function x_set_text(){

        //Authenticate User:
        $user_e = superpower_assigned();
        $e___12112 = $this->config->item('e___12112');

        if (!$user_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
                'original_val' => '',
            ));

        } elseif(!isset($_POST['object__id']) || !isset($_POST['cache_e__id']) || !isset($_POST['field_value'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_e__id']==4736 /* IDEA TITLE */){

            $is = $this->I_model->fetch(array(
                'i__id' => $_POST['object__id'],
                'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));
            if(!count($is)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));
            }

            //Validate Idea Outcome:
            $i__title_validation = i__title_validate($_POST['field_value']);
            if(!$i__title_validation['status']){
                //We had an error, return it:
                return view_json(array_merge($i__title_validation, array(
                    'original_val' => $is[0]['i__title'],
                )));
            }


            //All good, go ahead and update:
            $this->I_model->update($_POST['object__id'], array(
                'i__title' => trim($_POST['field_value']),
            ), true, $user_e['e__id']);

            return view_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_e__id']==6197 /* SOURCE FULL NAME */){

            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['object__id'],
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if(!count($es)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID.',
                    'original_val' => '',
                ));
            }


            $e__title_validate = e__title_validate($_POST['field_value']);
            if(!$e__title_validate['status']){
                return view_json(array_merge($e__title_validate, array(
                    'original_val' => $es[0]['e__title'],
                )));
            }

            //All good, go ahead and update:
            $this->E_model->update($es[0]['e__id'], array(
                'e__title' => $e__title_validate['e__title_clean'],
            ), true, $user_e['e__id']);

            //Reset user session data if this data belongs to the logged-in user:
            if ($es[0]['e__id'] == $user_e['e__id']) {
                //Re-activate Session with new data:
                $es[0]['e__title'] = $e__title_validate['e__title_clean'];
                $this->E_model->activate_session($es[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_e__id']==4356 /* DISCOVER TIME */){

            $is = $this->I_model->fetch(array(
                'i__id' => $_POST['object__id'],
                'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));

            if(!count($is)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || $_POST['field_value'] < 0){

                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m_title'].' must be a number greater than zero.',
                    'original_val' => $is[0]['i__duration'],
                ));

            } elseif($_POST['field_value'] > config_var(4356)){

                $hours = rtrim(number_format((config_var(4356)/3600), 1), '.0');
                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m_title'].' should be less than '.$hours.' Hour'.view__s($hours).', or '.config_var(4356).' Seconds long. You can break down your idea into smaller ideas.',
                    'original_val' => $is[0]['i__duration'],
                ));

            } elseif($_POST['field_value'] < config_var(12427)){

                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m_title'].' should be at-least '.config_var(12427).' Seconds long. It takes time to discover ideas ;)',
                    'original_val' => $is[0]['i__duration'],
                ));

            } else {

                //All good, go ahead and update:
                $this->I_model->update($_POST['object__id'], array(
                    'i__duration' => $_POST['field_value'],
                ), true, $user_e['e__id']);

                return view_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_e__id']==4358 /* DISCOVER MARKS */){

            //Fetch/Validate Transaction:
            $x = $this->X_model->fetch(array(
                'x__id' => $_POST['object__id'],
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            ));
            $x__metadata = unserialize($x[0]['x__metadata']);
            if(!$x__metadata){
                $x__metadata = array();
            }

            if(!count($x)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Transaction ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < config_var(11056) ||  $_POST['field_value'] > config_var(11057)){

                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m_title'].' must be an integer between '.config_var(11056).' and '.config_var(11057).'.',
                    'original_val' => ( isset($x__metadata['tr__assessment_points']) ? $x__metadata['tr__assessment_points'] : 0 ),
                ));

            } else {

                //All good, go ahead and update:
                $this->X_model->update($_POST['object__id'], array(
                    'x__metadata' => array_merge($x__metadata, array(
                        'tr__assessment_points' => intval($_POST['field_value']),
                    )),
                ), $user_e['e__id'], 10663 /* Idea Transaction updated Marks */, $e___12112[$_POST['cache_e__id']]['m_title'].' updated'.( isset($x__metadata['tr__assessment_points']) ? ' from [' . $x__metadata['tr__assessment_points']. ']' : '' ).' to [' . $_POST['field_value']. ']');

                return view_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_e__id']==4735 /* UNLOCK MIN SCORE */ || $_POST['cache_e__id']==4739 /* UNLOCK MAX SCORE */){

            //Fetch/Validate Transaction:
            $x = $this->X_model->fetch(array(
                'x__id' => $_POST['object__id'],
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            ));
            $x__metadata = unserialize($x[0]['x__metadata']);
            $field_name = ( $_POST['cache_e__id']==4735 ? 'tr__conditional_score_min' : 'tr__conditional_score_max' );

            if(!count($x)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Transaction ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < 0 || $_POST['field_value'] > 100){

                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m_title'].' must be an integer between 0 and 100.',
                    'original_val' => ( isset($x__metadata[$field_name]) ? $x__metadata[$field_name] : '' ),
                ));

            } else {

                //All good, go ahead and update:
                $this->X_model->update($_POST['object__id'], array(
                    'x__metadata' => array_merge($x__metadata, array(
                        $field_name => intval($_POST['field_value']),
                    )),
                ), $user_e['e__id'], 10664 /* Idea Transaction updated Score */, $e___12112[$_POST['cache_e__id']]['m_title'].' updated'.( isset($x__metadata[$field_name]) ? ' from [' . $x__metadata[$field_name].']' : '' ).' to [' . $_POST['field_value'].']');

                return view_json(array(
                    'status' => 1,
                ));

            }

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type ['.$_POST['cache_e__id'].']',
                'original_val' => '',
            ));

        }
    }




    function x_start($i__id){

        //Adds Idea to the Users Discovery

        $user_e = superpower_assigned();
        $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION

        //Check to see if added to Discovery for logged-in users:
        if(!$user_e){
            return redirect_message('/e/signin/'.$i__id);
        }

        //Add this Idea to their Discovery If not there:
        $i__id_added = $i__id;
        $success_message = null;
        $in_my_x = $this->X_model->i_home($i__id, $user_e);

        if(!$in_my_x){
            $i__id_added = $this->X_model->start($user_e['e__id'], $i__id);
            if(!$i__id_added){
                //Failed to add to Discovery:
                return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block">'.$e___11035[12969]['m_icon'].'</span>FAILED to add to '.$e___11035[12969]['m_title'].'.</div>');
            }
        }

        //Go to this newly added idea:
        return redirect_message('/'.$i__id_added, $success_message);

    }

    function x_next($i__id = 0){

        $user_e = superpower_assigned();
        if(!$user_e){
            return redirect_message('/e/signin/');
        }

        if($i__id > 0){

            //Fetch Idea:
            $is = $this->I_model->fetch(array(
                'i__id' => $i__id,
            ));

            //Should we check for auto next redirect if empty? Only if this is a selection:
            if($is[0]['i__type']==6677){

                //Mark as discover If not previously:
                $x_completes = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVER COMPLETE
                    'x__source' => $user_e['e__id'],
                    'x__left' => $is[0]['i__id'],
                ));

                if(!count($x_completes)){
                    $this->X_model->mark_complete($is[0], array(
                        'x__type' => 4559, //DISCOVER MESSAGES
                        'x__source' => $user_e['e__id'],
                    ));
                }
            }
        }

        //Go to Next Idea:
        $next_i__id = $this->X_model->find_next($user_e['e__id'], $is[0]);
        if($next_i__id > 0){
            return redirect_message('/'.$next_i__id.'?previous_x='.( isset($_GET['previous_x']) && $_GET['previous_x']>0 ? $_GET['previous_x'] : $i__id ));
        } else {

            //All completed, find the top idea:
            $top_i__id = $next_i__id; //Starting Assumption
            $u_x_ids = $this->X_model->ids($user_e['e__id']);
            if(!in_array($next_i__id, $u_x_ids)){
                //Search for it:
                $recursive_parents = $this->I_model->recursive_parents($is[0]['i__id'], true, true);
                foreach($recursive_parents as $grand_parent_ids) {
                    foreach(array_intersect($grand_parent_ids, $u_x_ids) as $intersect) {
                        foreach($grand_parent_ids as $count => $previous_i__id) {
                            if(in_array($previous_i__id, $u_x_ids)){
                                //Update It:
                                $top_i__id = $previous_i__id;
                                break;
                            }
                        }
                    }
                }
            }

            return redirect_message('/'.$top_i__id, '<div class="msg alert alert-info" role="alert"><div><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully completed this idea & will be notified of new updates, if any.</div></div>');

        }

    }




    function x_previous($previous_level_id, $i__id){

        $current_i__id = $previous_level_id;

        //Make sure not a select idea:
        if(!count($this->I_model->fetch(array(
            'i__id' => $current_i__id,
            'i__type IN (' . join(',', $this->config->item('n___7712')) . ')' => null, //SELECT IDEA
        )))){
            //FIND NEXT IDEAS
            foreach($this->X_model->fetch(array(
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__left' => $previous_level_id,
            ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $next_i){
                if($next_i['i__id']==$i__id){
                    break;
                } else {
                    $current_i__id = $next_i['i__id'];
                }
            }
        }

        return redirect_message('/'.$current_i__id);

    }





    function x_coin($i__id)
    {

        /*
         *
         * Enables a User to DISCOVER a IDEA
         * on the public web
         *
         * */

        if($i__id==config_var(12137)){
            return redirect_message(home_url());
        }

        //Fetch data:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));

        //Make sure we found it:
        if ( count($is) < 1) {

            return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Idea ID ' . $i__id . ' not found</div>');

        } elseif(!in_array($is[0]['i__status'], $this->config->item('n___7355') /* PUBLIC */)){

            if(superpower_assigned(10939)){

                //Give them idea access:
                return redirect_message('/~' . $i__id);

            } else {

                //Inform them not published:
                return redirect_message(home_url(), '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This idea is not published yet.</div>');

            }

        }

        $this->load->view('header', array(
            'title' => $is[0]['i__title'],
        ));

        //Load specific view based on Idea Level:
        $this->load->view('x/layout', array(
            'i_focus' => $is[0],
        ));

        $this->load->view('footer');

    }



    function x_upload()
    {

        //TODO: MERGE WITH FUNCTION i_note_file()

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

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(13572) * 1024 * 1024)) {

            return view_json(array(
                'status' => 0,
                'message' => 'File is larger than the maximum allowed file size of ' . config_var(13572) . ' MB.',
            ));

        }

        //Validate Idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
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

        $cdn_status = upload_to_cdn($temp_local, $user_e['e__id'], $_FILES[$_POST['upload_type']], true, $is[0]['i__title'].' BY '.$user_e['e__title']);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return view_json($cdn_status);
        }


        //Delete previous answer(s):
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
            'x__left' => $is[0]['i__id'],
            'x__source' => $user_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__status' => 6173, //Transaction Removed
            ), $user_e['e__id'], 12129 /* DISCOVER ANSWER DELETED */);
        }

        //Save new answer:
        $new_message = '@'.$cdn_status['cdn_e']['e__id'];
        $this->X_model->mark_complete($is[0], array(
            'x__type' => 12117,
            'x__source' => $user_e['e__id'],
            'x__message' => $new_message,
            'x__up' => $cdn_status['cdn_e']['e__id'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => '<div class="headline"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->X_model->message_send($new_message).'</div>',
        ));

    }


    function x_reply(){

        $user_e = superpower_assigned();
        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || !intval($_POST['i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        } elseif (!isset($_POST['x_reply']) || !strlen($_POST['x_reply'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Write a text reply before going next.',
            ));
        }

        //Validate/Fetch idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));
        if(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        }

        //Delete previous answer(s):
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVER COIN
            'x__left' => $is[0]['i__id'],
            'x__source' => $user_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__status' => 6173, //Transaction Removed
            ), $user_e['e__id'], 12129 /* DISCOVER ANSWER DELETED */);
        }

        //Save new answer:
        $this->X_model->mark_complete($is[0], array(
            'x__type' => 6144,
            'x__source' => $user_e['e__id'],
            'x__message' => $_POST['x_reply'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Answer Saved',
        ));

    }


    function x_select(){

        $user_e = superpower_assigned();
        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['focus_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea id.',
            ));
        } elseif (!isset($_POST['selection_i__id']) || !is_array($_POST['selection_i__id']) || !count($_POST['selection_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Select an answer before going next.',
            ));
        }

        //Save answer:
        return view_json($this->X_model->answer($user_e['e__id'], $_POST['focus_i__id'], $_POST['selection_i__id']));

    }




    function x_clear_coins(){

        $user_e = superpower_assigned(null, true);

        //Fetch their current progress transactions:
        $progress_x = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12227')) . ')' => null,
            'x__source' => $user_e['e__id'],
        ), array(), 0);

        if(count($progress_x) > 0){

            //Yes they did have some:
            $message = 'Removed '.count($progress_x).' idea'.view__s(count($progress_x));

            //Log transaction:
            $clear_all_x = $this->X_model->create(array(
                'x__message' => $message,
                'x__type' => 6415,
                'x__source' => $user_e['e__id'],
            ));

            //Delete all progressions:
            foreach($progress_x as $progress_x){
                $this->X_model->update($progress_x['x__id'], array(
                    'x__status' => 6173, //Transaction Removed
                    'x__reference' => $clear_all_x['x__id'], //To indicate when it was deleted
                ), $user_e['e__id'], 6415 /* Reset All Discoveries */);
            }

        } else {

            //Nothing to do:
            $message = 'Nothing found to be removed';

        }

        //Show basic UI for now:
        return redirect_message(home_url(), '<div class="msg alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');

    }


    function x_save(){

        //See if we need to add or remove a highlight:
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
                'message' => 'Missing Idea ID',
            ));

        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //First try to remove:
        $removed = 0;
        foreach($this->X_model->fetch(array(
            'x__up' => $user_e['e__id'],
            'x__right' => $_POST['i__id'],
            'x__type' => 12896, //SAVED
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )) as $remove_saved){
            $removed++;
            $this->X_model->update($remove_saved['x__id'], array(
                'x__status' => 6173, //Transaction Removed
            ), $user_e['e__id'], 12906 /* UNSAVED */);
        }

        //Need to add?
        if(!$removed){
            //Then we must add:
            $this->X_model->create(array(
                'x__source' => $user_e['e__id'],
                'x__up' => $user_e['e__id'],
                'x__message' => '@'.$user_e['e__id'],
                'x__right' => $_POST['i__id'],
                'x__type' => 12896, //SAVED
            ));
        }

        //All Good:
        return view_json(array(
            'status' => 1,
        ));

    }



    function x_remove(){

        /*
         *
         * When users indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and delete the idea from their
         * Discoveries.
         *
         * */

        $user_e = superpower_assigned();

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID',
            ));
        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___12467'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Mench Coin ID',
            ));
        }


        //Call function to delete form Discoveries:
        if($_POST['x__type']==6255){
            //Delete Discovery
            $delete_result = $this->X_model->delete($user_e['e__id'], $_POST['i__id'], 6155);
        } elseif($_POST['x__type']==12273){
            //Delete IDEAS
            $delete_result = $this->I_model->delete($user_e['e__id'], $_POST['i__id'], 13415);
        }

        if(!$delete_result['status']){
            return view_json($delete_result);
        } else {
            return view_json(array(
                'status' => 1,
            ));
        }
    }





    function x_sort()
    {
        /*
         *
         * Saves the order of discover ideas based on
         * user preferences.
         *
         * */

        $user_e = superpower_assigned();

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['new_x_order']) || !is_array($_POST['new_x_order']) || count($_POST['new_x_order']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___13413'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Transaction Type',
            ));
        }

        //Update the order of their Discoveries:
        $updated = 0;
        $results = array();
        foreach($_POST['new_x_order'] as $x__sort => $x__id){
            if(intval($x__id) > 0 && intval($x__sort) > 0){
                //Update order of this transaction:
                $results[$x__sort] = $this->X_model->update(intval($x__id), array(
                    'x__sort' => $x__sort,
                ), $user_e['e__id'], intval($_POST['x__type']));
                $updated++;
            }
        }

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => $updated.' Ideas Sorted',
        ));
    }


}