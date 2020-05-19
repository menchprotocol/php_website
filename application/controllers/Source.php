<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Source extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }

    function source_404()
    {
        $this->load->view('header', array(
            'title' => 'Page Not Found',
        ));
        $this->load->view('source/source_404');
        $this->load->view('footer');
    }


    function index()
    {
        //Leaderboard:
        $session_en = superpower_assigned(null);

        //Log View:
        if($session_en){
            $this->TRANSACTION_model->create(array(
                'ln_type_source_id' => 12489, //Opened Leaderboard
                'ln_creator_source_id' => $session_en['en_id'],
            ));
        }

        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH
        $this->load->view('header', array(
            'title' => $en_all_2738[4536]['m_name'],
        ));
        $this->load->view('source/source_home', array(
            'session_en' => $session_en,
        ));
        $this->load->view('footer');
    }


    //Lists sources
    function en_coin($en_id)
    {

        //Make sure not a private transaction:
        if(in_array($en_id, $this->config->item('en_ids_4755'))){
            $session_en = superpower_assigned(12701, true);
        } else {
            $session_en = superpower_assigned();
        }

        //Do we have any mass action to process here?
        if (superpower_assigned(12703) && isset($_POST['mass_action_en_id']) && isset($_POST['mass_value1_'.$_POST['mass_action_en_id']]) && isset($_POST['mass_value2_'.$_POST['mass_action_en_id']])) {

            //Process mass action:
            $process_mass_action = $this->SOURCE_model->mass_update($en_id, intval($_POST['mass_action_en_id']), $_POST['mass_value1_'.$_POST['mass_action_en_id']], $_POST['mass_value2_'.$_POST['mass_action_en_id']], $session_en['en_id']);

            //Pass-on results to UI:
            $message = '<div class="alert '.( $process_mass_action['status'] ? 'alert-info' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="fas fa-info-circle"></i></span>'.$process_mass_action['message'].'</div>';

        } else {

            //No mass action, just viewing...
            //Update session count and log link:
            $message = null; //No mass-action message to be appended...

            $new_order = ( $this->session->userdata('session_page_count') + 1 );
            $this->session->set_userdata('session_page_count', $new_order);
            $this->TRANSACTION_model->create(array(
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_type_source_id' => 4994, //Player Opened Player
                'ln_portfolio_source_id' => $en_id,
                'ln_order' => $new_order,
            ));

        }

        //Validate source ID and fetch data:
        $ens = $this->SOURCE_model->fetch(array(
            'en_id' => $en_id,
        ));

        if (count($ens) < 1) {
            return redirect_message('/source');
        }

        //Load views:
        $this->load->view('header', array(
            'title' => $ens[0]['en_name'],
            'flash_message' => $message, //Possible mass-action message for UI:
        ));
        $this->load->view('source/source_coin', array(
            'en' => $ens[0],
            'session_en' => $session_en,
        ));
        $this->load->view('footer');

    }


    function en_sort_reset()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10967);

        //Validate Source:
        $ens = $this->SOURCE_model->fetch(array(
            'en_id' => $_POST['en_id'],
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
        ));

        if (!$session_en) {
            echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10967),
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1 || count($ens) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid en_id',
            ));
        }



        //All good, reset sort value for all children:
        foreach($this->TRANSACTION_model->fetch(array(
            'ln_profile_source_id' => $_POST['en_id'],
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
        ), array('en_portfolio'), 0, 0, array(), 'ln_id') as $ln) {
            $this->TRANSACTION_model->update($ln['ln_id'], array(
                'ln_order' => 0,
            ), $session_en['en_id'], 13007 /* SOURCE SORT RESET */);
        }

        //Display message:
        echo_json(array(
            'status' => 1,
        ));
    }


    function en_sort_save()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10967);
        if (!$session_en) {
            echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10967),
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid en_id',
            ));
        } elseif (!isset($_POST['new_ln_orders']) || !is_array($_POST['new_ln_orders']) || count($_POST['new_ln_orders']) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Source:
            $ens = $this->SOURCE_model->fetch(array(
                'en_id' => $_POST['en_id'],
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            ));

            //Count Portfolio:
            $en__portfolio_count = $this->TRANSACTION_model->fetch(array(
                'ln_profile_source_id' => $_POST['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            ), array('en_portfolio'), 0, 0, array(), 'COUNT(en_id) as totals');

            if (count($ens) < 1) {

                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid en_id',
                ));

            } elseif($en__portfolio_count[0]['totals'] > config_var(13005)){

                echo_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort sources if greater than '.config_var(13005),
                ));

            } else {

                //Update them all:
                foreach($_POST['new_ln_orders'] as $rank => $ln_id) {
                    $this->TRANSACTION_model->update(intval($ln_id), array(
                        'ln_order' => intval($rank),
                    ), $session_en['en_id'], 13006 /* SOURCE SORT MANUAL */);
                }

                //Display message:
                echo_json(array(
                    'status' => 1,
                ));

            }
        }
    }



    function load_leaderboard(){

        //Fetch top sources
        $session_en = superpower_assigned();
        $load_max = config_var(11064);
        $show_max = config_var(11986);
        $start_date = null; //All-Time
        $filters_in = array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
            'ln_profile_source_id >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
        );

        if($session_en){
            //This source is already listed at the top of the page, skip:
            $filters_in['en_id !='] = $session_en['en_id'];
        }

        /*
        if(1){ //Weekly

            //Week always starts on Monday:
            if(date('D') === 'Mon'){
                //Today is Monday:
                $start_date = date("Y-m-d");
            } else {
                $start_date = date("Y-m-d", strtotime('previous monday'));
            }
            $filters_in['ln_timestamp >='] = $start_date.' 00:00:00'; //From beginning of the day
        }
        */

        //Start with top Players:
        echo '<div class="list-group">';

        if($session_en){
            //Make it even for the user row:
            echo '<div class="list-group-item no-height"></div>';
        } else {
            $show_max++;
        }

        foreach($this->TRANSACTION_model->fetch($filters_in, array('en_profile'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(ln_id) as totals, en_id, en_name, en_icon, en_metadata, en_status_source_id, en_weight', 'en_id, en_name, en_icon, en_metadata, en_status_source_id, en_weight') as $count=>$en) {

            if($count==$show_max){

                echo '<div class="list-group-item see_more_who no-side-padding"><a href="javascript:void(0);" onclick="$(\'.see_more_who\').toggleClass(\'hidden\')" class="block"><span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source" style="text-decoration: none !important;">SEE MORE</b></a></div>';

                echo '<div class="list-group-item see_more_who no-height"></div>';

            }

            echo echo_en($en, false, ( $count<$show_max ? '' : 'see_more_who hidden'));

        }
        echo '</div>';

    }


    function en_add_source_paste_url()
    {

        /*
         *
         * Validates the input URL to be added as a new source source
         *
         * */

        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
                'url_source' => array(),
            ));
        }

        //All seems good, fetch URL:
        $url_source = $this->SOURCE_model->url($_POST['input_url']);

        if (!$url_source['status']) {
            //Oooopsi, we had some error:
            return echo_json(array(
                'status' => 0,
                'message' => $url_source['message'],
            ));
        }

        //Return results:
        return echo_json(array(
            'status' => 1,
            'source_domain_ui' => '<span class="en_mini_ui_icon">' . (isset($url_source['en_domain']['en_icon']) && strlen($url_source['en_domain']['en_icon']) > 0 ? $url_source['en_domain']['en_icon'] : detect_fav_icon($url_source['url_clean_domain'], true)) . '</span> ' . (isset($url_source['en_domain']['en_name']) ? $url_source['en_domain']['en_name'] . ' <a href="/source/' . $url_source['en_domain']['en_id'] . '" class="underdot" data-toggle="tooltip" title="Click to open domain source" data-placement="top">@' . $url_source['en_domain']['en_id'] . '</a>' : $url_source['url_domain_name'] . ' [<span data-toggle="tooltip" title="Domain source not yet added" data-placement="top">New</span>]'),
            'js_url_source' => $url_source,
        ));

    }


    function en_ln_type_preview()
    {

        if (!isset($_POST['ln_content']) || !isset($_POST['ln_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing inputs',
            ));
        }

        //Will Contain every possible Player Link Connector:
        $en_all_4592 = $this->config->item('en_all_4592');

        //See what this is:
        $detected_ln_type = ln_detect_type($_POST['ln_content']);

        if(!$_POST['ln_id'] && !in_array($detected_ln_type['ln_type_source_id'], $this->config->item('en_ids_4537'))){

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));

        } elseif (!$detected_ln_type['status'] && isset($detected_ln_type['url_previously_existed']) && $detected_ln_type['url_previously_existed']) {

            //See if this is duplicate to either link:
            $en_lns = $this->TRANSACTION_model->fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Player URL Links
            ));

            //Are they both different?
            if (count($en_lns) < 1 || ($en_lns[0]['ln_profile_source_id'] != $detected_ln_type['en_url']['en_id'] && $en_lns[0]['ln_portfolio_source_id'] != $detected_ln_type['en_url']['en_id'])) {
                //return error:
                return echo_json($detected_ln_type);
            }

        }



        return echo_json(array(
            'status' => 1,
            'html_ui' => '<b class="montserrat doupper '.extract_icon_color($en_all_4592[$detected_ln_type['ln_type_source_id']]['m_icon']).'">' . $en_all_4592[$detected_ln_type['ln_type_source_id']]['m_icon'] . ' ' . $en_all_4592[$detected_ln_type['ln_type_source_id']]['m_name'] . '</b>',
            'en_link_preview' => ( in_array($detected_ln_type['ln_type_source_id'], $this->config->item('en_ids_12524')) ? '<span class="paddingup inline-block">'.echo_ln_content($_POST['ln_content'], $detected_ln_type['ln_type_source_id']).'</span>' : ''),
        ));

    }


    function en_save_file_upload()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10939);
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));
        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown error 3 while trying to save file.',
            ));
        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(11063) * 1024 * 1024)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . config_var(11063) . ' MB.',
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

        //Return the CDN uploader results:
        return echo_json(upload_to_cdn($temp_local, 0, $_FILES[$_POST['upload_type']], true));

    }


    function en_load_next_page()
    {

        $items_per_page = config_var(11064);
        $parent_en_id = intval($_POST['parent_en_id']);
        $en_focus_filter = intval($_POST['en_focus_filter']);
        $is_source = en_is_source($parent_en_id);
        $page = intval($_POST['page']);
        $filters = array(
            'ln_profile_source_id' => $parent_en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'en_status_source_id IN (' . join(',', ( $en_focus_filter<0 /* Remove Filters */ ? $this->config->item('en_ids_7358') /* ACTIVE */ : array($en_focus_filter) /* This specific filter*/ )) . ')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        );

        //Fetch & display next batch of children:
        $child_sources = $this->TRANSACTION_model->fetch($filters, array('en_portfolio'), $items_per_page, ($page * $items_per_page), array(
            'ln_order' => 'ASC',
            'en_name' => 'ASC'
        ));

        foreach($child_sources as $en) {
            echo echo_en($en,false, null, true, $is_source);
        }

        //Count total children:
        $child_sources_count = $this->TRANSACTION_model->fetch($filters, array('en_portfolio'), 0, 0, array(), 'COUNT(ln_id) as totals');

        //Do we need another load more button?
        if ($child_sources_count[0]['totals'] > (($page * $items_per_page) + count($child_sources))) {
            echo echo_en_load_more(($page + 1), $items_per_page, $child_sources_count[0]['totals']);
        }

    }

    function en_source_only_unlink(){

        //Auth user and check required variables:
        $session_en = superpower_assigned(10939);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['ln_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Transaction ID',
            ));
        } elseif (!isset($_POST['in_id']) || !in_is_source($_POST['in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'You are not the author of this source',
            ));
        }

        //Archive Link:
        $this->TRANSACTION_model->update($_POST['ln_id'], array(
            'ln_status_source_id' => 6173,
        ), $session_en['en_id'], 10678 /* IDEA NOTES Unpublished */);

        return echo_json(array(
            'status' => 1,
        ));

    }

    function en_source_only_add()
    {

        //Auth user and check required variables:
        $session_en = superpower_assigned(10939);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        } elseif (!isset($_POST['note_type_id']) || !in_array($_POST['note_type_id'], $this->config->item('en_ids_7551'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Idea Note Type ID',
            ));
        } elseif (!isset($_POST['en_existing_id']) || !isset($_POST['en_new_string']) || (intval($_POST['en_existing_id']) < 1 && strlen($_POST['en_new_string']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }


        //Validate Idea
        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
        ));
        if (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Idea',
            ));
        }


        //Set some variables:
        $_POST['en_existing_id'] = intval($_POST['en_existing_id']);

        //Are we linking to an existing source?
        if ($_POST['en_existing_id'] > 0) {

            //Validate this existing source:
            $ens = $this->SOURCE_model->fetch(array(
                'en_id' => $_POST['en_existing_id'],
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            ));
            if (count($ens) < 1) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid active source',
                ));
            }

            //Make sure not alreads linked:
            if(count($this->TRANSACTION_model->fetch(array(
                'ln_next_idea_id' => $ins[0]['in_id'],
                'ln_profile_source_id' => $_POST['en_existing_id'],
                'ln_type_source_id' => $_POST['note_type_id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            )))){
                $en_all_7551 = $this->config->item('en_all_7551');
                return echo_json(array(
                    'status' => 0,
                    'message' => $ens[0]['en_name'].' is already added as idea '.$en_all_7551[$_POST['note_type_id']]['m_name'],
                ));
            }


            //All good, assign:
            $focus_en = $ens[0];

        } else {

            //Create source:
            $added_en = $this->SOURCE_model->verify_create($_POST['en_new_string'], $session_en['en_id']);
            if(!$added_en['status']){
                //We had an error, return it:
                return echo_json($added_en);
            }

            //Assign new source:
            $focus_en = $added_en['en'];

            //Assign to Player:
            $this->SOURCE_model->assign_session_player($focus_en['en_id']);

            //Update Algolia:
            update_algolia('en', $focus_en['en_id']);

        }

        //Create Note:
        $new_note = $this->TRANSACTION_model->create(array(
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_type_source_id' => $_POST['note_type_id'],
            'ln_next_idea_id' => $ins[0]['in_id'],

            'ln_profile_source_id' => $focus_en['en_id'],
            'ln_content' => '@'.$focus_en['en_id'],
        ));

        //Return newly added or linked source:
        return echo_json(array(
            'status' => 1,
            'en_new_echo' => echo_en(array_merge($focus_en, $new_note), 0, null, true, true),
        ));

    }


    function en_add_or_link()
    {

        //Auth user and check required variables:
        $session_en = superpower_assigned(10939);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Source',
            ));
        } elseif (!isset($_POST['is_parent'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Source Link Direction',
            ));
        } elseif (!isset($_POST['en_existing_id']) || !isset($_POST['en_new_string']) || (intval($_POST['en_existing_id']) < 1 && strlen($_POST['en_new_string']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }

        //Validate parent source:
        $current_en = $this->SOURCE_model->fetch(array(
            'en_id' => $_POST['en_id'],
        ));
        if (count($current_en) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid parent source ID',
            ));
        }


        //Set some variables:
        $_POST['is_parent'] = intval($_POST['is_parent']);
        $_POST['en_existing_id'] = intval($_POST['en_existing_id']);
        $is_url_input = false;

        //Are we linking to an existing source?
        if (intval($_POST['en_existing_id']) > 0) {

            //Validate this existing source:
            $ens = $this->SOURCE_model->fetch(array(
                'en_id' => $_POST['en_existing_id'],
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            ));

            if (count($ens) < 1) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid active source',
                ));
            }

            //All good, assign:
            $focus_en = $ens[0];

        } else {

            //We are creating a new source OR adding a URL...

            //Is this a URL?
            if (filter_var($_POST['en_new_string'], FILTER_VALIDATE_URL)) {

                //Digest URL to see what type it is and if we have any errors:
                $url_source = $this->SOURCE_model->url($_POST['en_new_string']);
                if (!$url_source['status']) {
                    return echo_json($url_source);
                }

                //Is this a root domain? Add to domains if so:
                if($url_source['url_is_root']){

                    //Link to domains parent:
                    $focus_en = array('en_id' => 1326);

                    //Update domain to stay synced:
                    $_POST['en_new_string'] = $url_source['url_clean_domain'];

                } else {

                    //Let's first find/add the domain:
                    $domain_source = $this->SOURCE_model->domain($_POST['en_new_string'], $session_en['en_id']);

                    //Link to this source:
                    $focus_en = $domain_source['en_domain'];
                }

            } else {

                //Create source:
                $added_en = $this->SOURCE_model->verify_create($_POST['en_new_string'], $session_en['en_id']);
                if(!$added_en['status']){
                    //We had an error, return it:
                    return echo_json($added_en);
                } else {
                    //Assign new source:
                    $focus_en = $added_en['en'];
                }

            }

        }


        //We need to check to ensure this is not a duplicate link if linking to an existing source:
        $ur2 = array();

        if (!$is_url_input) {

            //Add links only if not previously added by the URL function:
            if ($_POST['is_parent']) {

                $ln_portfolio_source_id = $current_en[0]['en_id'];
                $ln_profile_source_id = $focus_en['en_id'];

            } else {

                $ln_portfolio_source_id = $focus_en['en_id'];
                $ln_profile_source_id = $current_en[0]['en_id'];

            }


            if (isset($url_source['url_is_root']) && $url_source['url_is_root']) {

                $ln_type_source_id = 4256; //Generic URL (Domains always are generic)
                $ln_content = $url_source['clean_url'];

            } elseif (isset($domain_source['en_domain'])) {

                $ln_type_source_id = $url_source['ln_type_source_id'];
                $ln_content = $url_source['clean_url'];

            } else {

                $ln_type_source_id = 4230; //Raw
                $ln_content = null;

            }

            // Link to new OR existing source:
            $ur2 = $this->TRANSACTION_model->create(array(
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_type_source_id' => $ln_type_source_id,
                'ln_content' => $ln_content,
                'ln_portfolio_source_id' => $ln_portfolio_source_id,
                'ln_profile_source_id' => $ln_profile_source_id,
            ));
        }

        //Fetch latest version:
        $ens_latest = $this->SOURCE_model->fetch(array(
            'en_id' => $focus_en['en_id'],
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
        ));
        if(!count($ens_latest)){
            return echo_json(array(
                'status' => 0,
                'message' => 'Failed to create/fetch new source',
            ));
        }

        //Return newly added or linked source:
        return echo_json(array(
            'status' => 1,
            'en_new_echo' => echo_en(array_merge($ens_latest[0], $ur2), $_POST['is_parent'], null, true, true),
        ));

    }

    function en_count_delete_links()
    {

        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID',
            ));
        }

        //Simply counts the links for a given source:
        $all_en_links = $this->TRANSACTION_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            '(ln_portfolio_source_id = ' . $_POST['en_id'] . ' OR ln_profile_source_id = ' . $_POST['en_id'] . ')' => null,
        ), array(), 0);

        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
            'en_link_count' => count($all_en_links),
        ));

    }



    function account_toggle_superpower($superpower_en_id){

        //Toggles the advance session variable for the player on/off for logged-in players:
        $session_en = superpower_assigned(10939);
        $superpower_en_id = intval($superpower_en_id);
        $en_all_10957 = $this->config->item('en_all_10957');

        if(!$session_en){

            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));

        } elseif(!superpower_assigned($superpower_en_id)){

            //Access not authorized:
            return echo_json(array(
                'status' => 0,
                'message' => 'You have not yet unlocked the superpower of '.$en_all_10957[$superpower_en_id]['m_name'],
            ));

        }

        //Figure out new toggle state:
        $session_data = $this->session->all_userdata();

        if(in_array($superpower_en_id, $session_data['session_superpowers_activated'])){
            //Previously there, turn it off:
            $session_data['session_superpowers_activated'] = array_diff($session_data['session_superpowers_activated'], array($superpower_en_id));
            $toggled_setting = 'DEACTIVATED';
        } else {
            //Not there, turn it on:
            array_push($session_data['session_superpowers_activated'], $superpower_en_id);
            $toggled_setting = 'ACTIVATED';
        }


        //Update Session:
        $this->session->set_userdata($session_data);


        //Log Link:
        $this->TRANSACTION_model->create(array(
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_type_source_id' => 5007, //TOGGLE SUPERPOWER
            'ln_profile_source_id' => $superpower_en_id,
            'ln_content' => 'SUPERPOWER '.$toggled_setting, //To be used when player logs in again
        ));

        //Return to JS function:
        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
        ));
    }




    function en_modify_save()
    {

        //Auth user and check required variables:
        $session_en = superpower_assigned(10939);
        $success_message = 'Saved'; //Default, might change based on what we do...
        $is_valid_icon = is_valid_icon($_POST['en_icon']);

        //Fetch current data:
        $ens = $this->SOURCE_model->fetch(array(
            'en_id' => intval($_POST['en_id']),
        ));


        $en_name_validate = en_name_validate($_POST['en_name']);
        if(!$en_name_validate['status']){
            return echo_json($en_name_validate);
        }

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1 || !(count($ens) == 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid ID',
            ));
        } elseif (!isset($_POST['en_focus_id']) || intval($_POST['en_focus_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Focus ID',
            ));
        } elseif (!isset($_POST['en_status_source_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing status',
            ));
        } elseif (!isset($_POST['ln_id']) || !isset($_POST['ln_content']) || !isset($_POST['ln_status_source_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing source link data',
            ));
        } elseif(!$is_valid_icon['status']){
            //Check if valid icon:
            return echo_json(array(
                'status' => 0,
                'message' => $is_valid_icon['message'],
            ));
        }

        $delete_redirect_url = null;
        $delete_from_ui = 0;
        $js_ln_type_source_id = 0; //Detect link type based on content

        //Prepare data to be updated:
        $en_update = array(
            'en_name' => $en_name_validate['en_clean_name'],
            'en_icon' => trim($_POST['en_icon']),
            'en_status_source_id' => intval($_POST['en_status_source_id']),
        );

        //Is this being deleted?
        if (!in_array($en_update['en_status_source_id'], $this->config->item('en_ids_7358') /* ACTIVE */) && !($en_update['en_status_source_id'] == $ens[0]['en_status_source_id'])) {


            //Make sure source is not referenced in key DB reference fields:
            $en_count_db_references = en_count_db_references($_POST['en_id'], false);
            if(count($en_count_db_references) > 0){

                $en_all_6194 = $this->config->item('en_all_6194');

                //Construct the message:
                $error_message = 'Cannot be deleted because source is referenced as ';
                foreach($en_count_db_references as $en_id=>$en_count){
                    $error_message .= $en_all_6194[$en_id]['m_name'].' '.echo_number($en_count).' times ';
                }

                return echo_json(array(
                    'status' => 0,
                    'message' => $error_message,
                ));

            }



            //Count source references in IDEA NOTES:
            $messages = $this->TRANSACTION_model->fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //IDEA NOTES
                'ln_profile_source_id' => $_POST['en_id'],
            ), array('in_next'), 0, 0, array('ln_order' => 'ASC'));

            //Assume no merge:
            $merged_ens = array();

            //See if we have merger source:
            if (strlen($_POST['en_merge']) > 0) {

                //Yes, validate this source:

                //Validate the input for updating linked Idea:
                $merger_en_id = 0;
                if (substr($_POST['en_merge'], 0, 1) == '@') {
                    $parts = explode(' ', $_POST['en_merge']);
                    $merger_en_id = intval(str_replace('@', '', $parts[0]));
                }

                if ($merger_en_id < 1) {

                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Unrecognized merger source [' . $_POST['en_merge'] . ']',
                    ));

                } elseif ($merger_en_id == $_POST['en_id']) {

                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Cannot merge source into itself',
                    ));

                } else {

                    //Finally validate merger source:
                    $merged_ens = $this->SOURCE_model->fetch(array(
                        'en_id' => $merger_en_id,
                        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
                    ));
                    if (count($merged_ens) == 0) {
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'Could not find source @' . $merger_en_id,
                        ));
                    }

                }

            } elseif(count($messages) > 0){

                //Cannot delete this source until Idea references are deleted:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'You can delete source after removing all its IDEA NOTES references',
                ));

            }

            //Delete/merge SOURCE LINKS:
            if($_POST['en_id'] == $_POST['en_focus_id']){

                //Fetch parents to redirect to:
                $en__profiles = $this->TRANSACTION_model->fetch(array(
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                    'ln_portfolio_source_id' => $_POST['en_id'],
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
                ), array('en_profile'), 1);

            }


            $_POST['ln_id'] = 0; //Do not consider the link as the source is being Deleted
            $delete_from_ui = 1; //Removing source
            $merger_en_id = (count($merged_ens) > 0 ? $merged_ens[0]['en_id'] : 0);
            $links_adjusted = $this->SOURCE_model->unlink($_POST['en_id'], $session_en['en_id'], $merger_en_id);

            //Show appropriate message based on action:
            if ($merger_en_id > 0) {

                if($_POST['en_id'] == $_POST['en_focus_id'] || $merged_ens[0]['en_id'] == $_POST['en_focus_id']){
                    //Player is being Deleted and merged into another source:
                    $delete_redirect_url = '/source/' . $merged_ens[0]['en_id'];
                }

                $success_message = 'Source deleted & merged its ' . $links_adjusted . ' links here';

            } else {

                if($_POST['en_id'] == $_POST['en_focus_id']){
                    $delete_redirect_url = '/source/' . ( count($en__profiles) ? $en__profiles[0]['en_id'] : $session_en['en_id'] );
                }

                //Display proper message:
                $success_message = 'Source deleted & its ' . $links_adjusted . ' links have been Unpublished.';

            }

        }


        if (intval($_POST['ln_id']) > 0) { //DO we have a link to update?

            //Yes, first validate source link:
            $en_lns = $this->TRANSACTION_model->fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            ));
            if (count($en_lns) < 1) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'INVALID TRANSACTION ID',
                ));
            }


            //Status change?
            if($en_lns[0]['ln_status_source_id']!=$_POST['ln_status_source_id']){

                if (in_array($_POST['ln_status_source_id'], $this->config->item('en_ids_7360') /* ACTIVE */)) {
                    $ln_status_source_id = 10656; //Player Link updated Status
                } else {
                    $delete_from_ui = 1;
                    $ln_status_source_id = 10673; //Player Link Unpublished
                }

                $this->TRANSACTION_model->update($_POST['ln_id'], array(
                    'ln_status_source_id' => intval($_POST['ln_status_source_id']),
                ), $session_en['en_id'], $ln_status_source_id);
            }


            //Link content change?
            if ($en_lns[0]['ln_content'] == $_POST['ln_content']) {

                //Link content has not changed:
                $js_ln_type_source_id = $en_lns[0]['ln_type_source_id'];
                $ln_content = $en_lns[0]['ln_content'];

            } else {

                //Link content has changed:
                $detected_ln_type = ln_detect_type($_POST['ln_content']);

                if (!$detected_ln_type['status']) {

                    return echo_json($detected_ln_type);

                } elseif (in_array($detected_ln_type['ln_type_source_id'], $this->config->item('en_ids_4537'))) {

                    //This is a URL, validate modification:

                    if ($detected_ln_type['url_is_root']) {

                        if ($en_lns[0]['ln_profile_source_id'] == 1326) {

                            //Override with the clean domain for consistency:
                            $_POST['ln_content'] = $detected_ln_type['url_clean_domain'];

                        } else {

                            //Domains can only be added to the domain source:
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Domain URLs must link to <b>@1326 Domains</b> as source profile',
                            ));

                        }

                    } else {

                        if ($en_lns[0]['ln_profile_source_id'] == 1326) {

                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Only domain URLs can be linked to Domain source.',
                            ));

                        } elseif ($detected_ln_type['en_domain']) {
                            //We do have the domain mapped! Is this connected to the domain source as its parent?
                            if ($detected_ln_type['en_domain']['en_id'] != $en_lns[0]['ln_profile_source_id']) {
                                return echo_json(array(
                                    'status' => 0,
                                    'message' => 'Must link to <b>@' . $detected_ln_type['en_domain']['en_id'] . ' ' . $detected_ln_type['en_domain']['en_name'] . '</b> as source profile',
                                ));
                            }
                        } else {
                            //We don't have the domain mapped, this is for sure not allowed:
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'Requires a new parent source for <b>' . $detected_ln_type['url_tld'] . '</b>. Add by pasting URL into the [Add @Source] input field.',
                            ));
                        }

                    }

                }

                //Update variables:
                $ln_content = $_POST['ln_content'];
                $js_ln_type_source_id = $detected_ln_type['ln_type_source_id'];


                $this->TRANSACTION_model->update($_POST['ln_id'], array(
                    'ln_content' => $ln_content,
                ), $session_en['en_id'], 10657 /* Player Link updated Content */);


                //Also, did the link type change based on the content change?
                if($js_ln_type_source_id!=$en_lns[0]['ln_type_source_id']){
                    $this->TRANSACTION_model->update($_POST['ln_id'], array(
                        'ln_type_source_id' => $js_ln_type_source_id,
                    ), $session_en['en_id'], 10659 /* Player Link updated Type */);
                }
            }
        }

        //Now update the DB:
        $this->SOURCE_model->update(intval($_POST['en_id']), $en_update, true, $session_en['en_id']);


        //Reset user session data if this data belongs to the logged-in user:
        if ($_POST['en_id'] == $session_en['en_id']) {
            //Re-activate Session with new data:
            $this->SOURCE_model->activate_session($session_en, true);
        }


        if ($delete_redirect_url) {
            //Page will be refresh, set flash message to be shown after restart:
            $this->session->set_flashdata('flash_message', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>' . $success_message . '</div>');
        }

        //Start return array:
        $return_array = array(
            'status' => 1,
            'message' => '<i class="fas fa-check-circle"></i> ' . $success_message,
            'delete_from_ui' => $delete_from_ui,
            'delete_redirect_url' => $delete_redirect_url,
            'js_ln_type_source_id' => intval($js_ln_type_source_id),
        );

        if (intval($_POST['ln_id']) > 0) {

            //Fetch source link:
            $lns = $this->TRANSACTION_model->fetch(array(
                'ln_id' => $_POST['ln_id'],
            ), array('en_creator'));

            //Prep last updated:
            $return_array['ln_content'] = echo_ln_content($ln_content, $js_ln_type_source_id);
            $return_array['ln_content_final'] = $ln_content; //In case content was updated

        }

        //Show success:
        return echo_json($return_array);

    }


    function en_fetch_canonical_url(){

        //Auth user and check required variables:
        $session_en = superpower_assigned();

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['search_url']) || !filter_var($_POST['search_url'], FILTER_VALIDATE_URL)) {
            //This string was incorrectly detected as a URL by JS, return not found:
            return echo_json(array(
                'status' => 1,
                'url_previously_existed' => 0,
            ));
        }

        //Fetch URL:
        $url_source = $this->SOURCE_model->url($_POST['search_url']);

        if($url_source['url_previously_existed']){
            return echo_json(array(
                'status' => 1,
                'url_previously_existed' => 1,
                'algolia_object' => update_algolia('en', $url_source['en_url']['en_id'], 1),
            ));
        } else {
            return echo_json(array(
                'status' => 1,
                'url_previously_existed' => 0,
            ));
        }
    }




    function account_update_radio()
    {
        /*
         *
         * Saves the radio selection of some account fields
         * that are displayed using echo_radio_sources()
         *
         * */

        $session_en = superpower_assigned();

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['parent_en_id']) || intval($_POST['parent_en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing parent source',
            ));
        } elseif (!isset($_POST['selected_en_id']) || intval($_POST['selected_en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing selected source',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_previously_selected'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }


        if(!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']){
            //Since this is not a multi-select we want to delete all existing options...

            //Fetch all possible answers based on parent source:
            $filters = array(
                'ln_profile_source_id' => $_POST['parent_en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
            );

            if($_POST['enable_mulitiselect'] && $_POST['was_previously_selected']){
                //Just delete this single item, not the other ones:
                $filters['ln_portfolio_source_id'] = $_POST['selected_en_id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->TRANSACTION_model->fetch($filters, array('en_portfolio'), 0, 0) as $answer_en){
                array_push($possible_answers, $answer_en['en_id']);
            }

            //Delete selected options for this player:
            foreach($this->TRANSACTION_model->fetch(array(
                'ln_profile_source_id IN (' . join(',', $possible_answers) . ')' => null,
                'ln_portfolio_source_id' => $session_en['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            )) as $delete_en){
                //Should usually delete a single option:
                $this->TRANSACTION_model->update($delete_en['ln_id'], array(
                    'ln_status_source_id' => 6173, //Transaction Deleted
                ), $session_en['en_id'], 6224 /* User Account Updated */);
            }

        }

        //Add new option if not previously there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_previously_selected']){
            $this->TRANSACTION_model->create(array(
                'ln_profile_source_id' => $_POST['selected_en_id'],
                'ln_portfolio_source_id' => $session_en['en_id'],
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_type_source_id' => en_link_type_id(),
            ));
        }


        //Log Account Update link type:
        $_POST['account_update_function'] = 'account_update_radio'; //Add this variable to indicate which My Account function created this link
        $this->TRANSACTION_model->create(array(
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_type_source_id' => 6224, //My Account updated
            'ln_content' => 'My Account '.( $_POST['enable_mulitiselect'] ? 'Multi-Select Radio Field ' : 'Single-Select Radio Field ' ).( $_POST['was_previously_selected'] ? 'Deleted' : 'Added' ),
            'ln_metadata' => $_POST,
            'ln_profile_source_id' => $_POST['parent_en_id'],
            'ln_portfolio_source_id' => $_POST['selected_en_id'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => 'Updated', //Alert: NOT shown in UI
        ));
    }






    function account_update_avatar_icon()
    {

        $session_en = superpower_assigned();

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['type_css'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing type_css',
            ));
        } elseif (!isset($_POST['icon_css'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing icon_css',
            ));
        }

        //Validate:
        $icon_new_css = $_POST['type_css'].' '.$_POST['icon_css'].' source';
        $validated = false;
        foreach($this->config->item('en_all_12279') as $en_id => $m) {
            if(substr_count($m['m_icon'], $icon_new_css) == 1){
                $validated = true;
                break;
            }
        }
        if(!$validated){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid icon',
            ));
        }


        //Update icon:
        $new_avatar = '<i class="'.$icon_new_css.'"></i>';
        $this->SOURCE_model->update($session_en['en_id'], array(
            'en_icon' => $new_avatar,
        ), true, $session_en['en_id']);


        //Update Session:
        $session_en['en_icon'] = $new_avatar;
        $this->SOURCE_model->activate_session($session_en, true);


        return echo_json(array(
            'status' => 1,
            'message' => 'Name updated',
            'new_avatar' => $new_avatar,
        ));
    }



    function account_update_email()
    {

        $session_en = superpower_assigned();

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['en_email']) || !filter_var($_POST['en_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        }


        if (strlen($_POST['en_email']) > 0) {

            //Cleanup:
            $_POST['en_email'] = trim(strtolower($_POST['en_email']));

            //Check to make sure not duplicate:
            $duplicates = $this->TRANSACTION_model->fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                'ln_profile_source_id' => 3288, //Mench Email
                'ln_portfolio_source_id !=' => $session_en['en_id'],
                'LOWER(ln_content)' => $_POST['en_email'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Email previously in-use. Use another email or contact support for assistance.',
                ));
            }
        }


        //Fetch existing email:
        $user_emails = $this->TRANSACTION_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_portfolio_source_id' => $session_en['en_id'],
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_profile_source_id' => 3288, //Mench Email
        ));
        if (count($user_emails) > 0) {

            if (strlen($_POST['en_email']) == 0) {

                //Delete email:
                $this->TRANSACTION_model->update($user_emails[0]['ln_id'], array(
                    'ln_status_source_id' => 6173, //Transaction Deleted
                ), $session_en['en_id'], 6224 /* User Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email deleted',
                );

            } elseif ($user_emails[0]['ln_content'] != $_POST['en_email']) {

                //Update if not duplicate:
                $this->TRANSACTION_model->update($user_emails[0]['ln_id'], array(
                    'ln_content' => $_POST['en_email'],
                ), $session_en['en_id'], 6224 /* User Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Email unchanged',
                );

            }

        } elseif (strlen($_POST['en_email']) > 0) {

            //Create new link:
            $this->TRANSACTION_model->create(array(
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_portfolio_source_id' => $session_en['en_id'],
                'ln_type_source_id' => en_link_type_id($_POST['en_email']),
                'ln_profile_source_id' => 3288, //Mench Email
                'ln_content' => $_POST['en_email'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Email added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Email unchanged',
            );

        }


        if($return['status']){
            //Log Account Update link type:
            $_POST['account_update_function'] = 'account_update_email'; //Add this variable to indicate which My Account function created this link
            $this->TRANSACTION_model->create(array(
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_type_source_id' => 6224, //My Account updated
                'ln_content' => 'My Account '.$return['message']. ( strlen($_POST['en_email']) > 0 ? ': '.$_POST['en_email'] : ''),
                'ln_metadata' => $_POST,
            ));
        }


        //Return results:
        return echo_json($return);


    }


    function account_update_password()
    {

        $session_en = superpower_assigned();

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < config_var(11066)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'New password must be '.config_var(11066).' characters or more',
            ));
        }

        //Fetch existing password:
        $user_passwords = $this->TRANSACTION_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_profile_source_id' => 3286, //Password
            'ln_portfolio_source_id' => $session_en['en_id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $session_en['en_id']));


        if (count($user_passwords) > 0) {

            if ($hashed_password == $user_passwords[0]['ln_content']) {

                $return = array(
                    'status' => 0,
                    'message' => 'Password Unchanged',
                );

            } else {

                //Update password:
                $this->TRANSACTION_model->update($user_passwords[0]['ln_id'], array(
                    'ln_content' => $hashed_password,
                ), $session_en['en_id'], 7578 /* User Updated Password  */);

                $return = array(
                    'status' => 1,
                    'message' => 'Password Updated',
                );

            }

        } else {

            //Create new link:
            $this->TRANSACTION_model->create(array(
                'ln_type_source_id' => en_link_type_id($hashed_password),
                'ln_profile_source_id' => 3286, //Password
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_portfolio_source_id' => $session_en['en_id'],
                'ln_content' => $hashed_password,
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Password Added',
            );

        }


        //Log Account Update link type:
        if($return['status']){
            $_POST['account_update_function'] = 'account_update_password'; //Add this variable to indicate which My Account function created this link
            $this->TRANSACTION_model->create(array(
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_type_source_id' => 6224, //My Account Updated
                'ln_content' => 'My Account '.$return['message'],
                'ln_metadata' => $_POST,
            ));
        }


        //Return results:
        return echo_json($return);

    }











    /*
     *
     * SIGN FUNCTIONS
     *
     *
     * */




    function sign($in_id = 0){

        //Check to see if they are previously logged in?
        $session_en = superpower_assigned();
        if ($session_en) {
            //Lead player and above, go to console:
            if($in_id > 0){
                return redirect_message('/idea/go/' . $in_id);
            } else {
                return redirect_message('/read');
            }
        }

        //Update focus idea session:
        if($in_id > 0){
            //Set in session:
            $this->session->set_userdata(array(
                'sign_in_id' => $in_id,
            ));

            //Redirect to basic login URL (So Facebook OAuth can validate)
            return redirect_message('/source/sign');
        }


        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
        $this->load->view('header', array(
            'hide_header' => 1,
            'title' => $en_all_11035[4269]['m_name'],
        ));
        $this->load->view('source/source_sign');
        $this->load->view('footer');

    }


    function signout()
    {
        //Destroys Session
        $this->session->sess_destroy();
        header('Location: /');
    }



    function sign_create_account(){

        if (!isset($_POST['referrer_in_id']) || !isset($_POST['referrer_url'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['input_name']) || strlen($_POST['input_name'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing name',
                'focus_input_field' => 'input_name',
            ));
        }

        //Prep inputs & validate further:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $_POST['input_name'] = trim($_POST['input_name']);
        $name_parts = explode(' ', trim($_POST['input_name']));
        if (strlen($_POST['input_name']) < config_var(12232)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Name must longer than '.config_var(12232).' characters',
                'focus_input_field' => 'input_name',
            ));
        } elseif (strlen($_POST['input_name']) > config_var(6197)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Name must be less than '.config_var(6197).' characters',
                'focus_input_field' => 'input_name',
            ));

            /*
            } elseif (!isset($name_parts[1])) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'There must be a space between your your first and last name',
                    'focus_input_field' => 'input_name',
                ));
            } elseif (strlen($name_parts[1])<2) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Last name must be 2 characters or longer',
                    'focus_input_field' => 'input_name',
                ));
            } elseif (strlen($name_parts[0])<2) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'First name must be 2 characters or longer',
                    'focus_input_field' => 'input_name',
                ));
            */

        } elseif (!isset($_POST['new_password']) || strlen($_POST['new_password'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing password',
                'focus_input_field' => 'new_password',
            ));
        } elseif (strlen($_POST['new_password']) < config_var(11066)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'New password must be '.config_var(11066).' characters or longer',
                'focus_input_field' => 'new_password',
            ));
        }



        //All good, create new source:
        $user_en = $this->SOURCE_model->verify_create(trim($_POST['input_name']), 0, 6181, random_player_avatar());
        if(!$user_en['status']){
            //We had an error, return it:
            return echo_json($user_en);
        }


        //Add Player:
        $this->TRANSACTION_model->create(array(
            'ln_profile_source_id' => 4430, //MENCH PLAYERS
            'ln_type_source_id' => en_link_type_id(),
            'ln_creator_source_id' => $user_en['en']['en_id'],
            'ln_portfolio_source_id' => $user_en['en']['en_id'],
        ));

        $this->TRANSACTION_model->create(array(
            'ln_type_source_id' => en_link_type_id(trim(strtolower($_POST['input_email']))),
            'ln_content' => trim(strtolower($_POST['input_email'])),
            'ln_profile_source_id' => 3288, //Mench Email
            'ln_creator_source_id' => $user_en['en']['en_id'],
            'ln_portfolio_source_id' => $user_en['en']['en_id'],
        ));
        $hash = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['new_password'] . $user_en['en']['en_id']));
        $this->TRANSACTION_model->create(array(
            'ln_type_source_id' => en_link_type_id($hash),
            'ln_content' => $hash,
            'ln_profile_source_id' => 3286, //Mench Password
            'ln_creator_source_id' => $user_en['en']['en_id'],
            'ln_portfolio_source_id' => $user_en['en']['en_id'],
        ));

        //Now update Algolia:
        update_algolia('en',  $user_en['en']['en_id']);

        //Fetch referral Idea, if any:
        if(intval($_POST['referrer_in_id']) > 0){

            //Fetch the Idea:
            $referrer_ins = $this->IDEA_model->fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                'in_id' => $_POST['referrer_in_id'],
            ));

            if(count($referrer_ins) > 0){
                //Add this Idea to their Reads:
                $this->READ_model->start($user_en['en']['en_id'], $_POST['referrer_in_id']);
            } else {
                //Cannot be added, likely because its not published:
                $_POST['referrer_in_id'] = 0;
            }

        } else {
            $referrer_ins = array();
        }



        ##Email Subject
        $subject = 'Hi, '.$name_parts[0].'! 👋';

        ##Email Body
        $html_message = '<div>Just wanted to welcome you to Mench. You can create your first idea here:</div>';
        $html_message .= '<br /><br />';
        $html_message .= '<div>'.echo_platform_message(12691).'</div><br />';
        $html_message .= '<div>MENCH</div>';

        //Send Welcome Email:
        $email_log = $this->READ_model->send_email(array($_POST['input_email']), $subject, $html_message);


        //Log User Signin Joined Mench
        $invite_link = $this->TRANSACTION_model->create(array(
            'ln_type_source_id' => 7562, //User Signin Joined Mench
            'ln_creator_source_id' => $user_en['en']['en_id'],
            'ln_previous_idea_id' => intval($_POST['referrer_in_id']),
            'ln_metadata' => array(
                'email_log' => $email_log,
            ),
        ));

        //Assign session & log login link:
        $this->SOURCE_model->activate_session($user_en['en']);


        if (strlen($_POST['referrer_url']) > 0) {
            $login_url = urldecode($_POST['referrer_url']);
        } elseif(intval($_POST['referrer_in_id']) > 0) {
            $login_url = '/idea/go/'.$_POST['referrer_in_id'];
        } else {
            //Go to home page and let them continue from there:
            $login_url = '/';
        }

        return echo_json(array(
            'status' => 1,
            'login_url' => $login_url,
        ));



    }




    function search_google($en_id){
        $ens = $this->SOURCE_model->fetch(array(
            'en_id' => $en_id,
        ));
        if(count($ens)){
            return redirect_message('https://www.google.com/search?q='.urlencode($ens[0]['en_name']));
        } else {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID'
            ));
        }
    }

    function search_icon($en_id){
        $ens = $this->SOURCE_model->fetch(array(
            'en_id' => $en_id,
        ));
        if(count($ens)){

            if(( substr_count($ens[0]['en_icon'], 'class="') ?  : null )){

                return redirect_message('/plugin/7267?search_for='.urlencode(one_two_explode('class="','"',$ens[0]['en_icon'])));

            } elseif(strlen($ens[0]['en_icon'])) {

                return redirect_message('/plugin/7267?search_for=' . urlencode($ens[0]['en_icon']));

            } else {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Source Missing Icon'
                ));
            }

        } else {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID'
            ));
        }
    }

    function singin_check_password(){

        if (!isset($_POST['login_en_id']) || intval($_POST['login_en_id'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < config_var(11066)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Password',
            ));
        } elseif (!isset($_POST['referrer_url'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing referrer URL',
            ));
        } elseif (!isset($_POST['referrer_in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing idea referrer',
            ));
        }



        //Validaye user ID
        $ens = $this->SOURCE_model->fetch(array(
            'en_id' => $_POST['login_en_id'],
        ));
        if (!in_array($ens[0]['en_status_source_id'], $this->config->item('en_ids_7357') /* PUBLIC */)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Your account source is not public. Contact us to adjust your account.',
            ));
        }

        //Authenticate password:
        $ens[0]['is_masterpass_login'] = 0;
        $user_passwords = $this->TRANSACTION_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_profile_source_id' => 3286, //Password
            'ln_portfolio_source_id' => $ens[0]['en_id'],
        ));
        if (count($user_passwords) == 0) {
            //They do not have a password assigned yet!
            return echo_json(array(
                'status' => 0,
                'message' => 'An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.',
            ));
        } elseif (!in_array($user_passwords[0]['ln_status_source_id'], $this->config->item('en_ids_7359') /* PUBLIC */)) {
            //They do not have a password assigned yet!
            return echo_json(array(
                'status' => 0,
                'message' => 'Password link is not public. Contact us to adjust your account.',
            ));
        } elseif ($user_passwords[0]['ln_content'] != hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $ens[0]['en_id'])) {

            //Is this the master password?
            if(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password']) == config_var(13014)){

                $ens[0]['is_masterpass_login'] = 1;

            } else {

                //Bad password
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Incorrect password',
                ));

            }
        }


        //Assign session & log link:
        $this->SOURCE_model->activate_session($ens[0]);


        if (intval($_POST['referrer_in_id']) > 0) {

            $login_url = '/read/start/'.$_POST['referrer_in_id'];

        } elseif (isset($_POST['referrer_url']) && strlen($_POST['referrer_url']) > 0) {

            $login_url = urldecode($_POST['referrer_url']);

        } else {
            $login_url = '/';
        }

        return echo_json(array(
            'status' => 1,
            'login_url' => $login_url,
        ));

    }



    function sign_reset_password_apply()
    {

        //This function updates the user's new password as requested via a password reset:
        if (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1 || !isset($_POST['input_email']) || strlen($_POST['input_email']) < 1 || !isset($_POST['input_password'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (strlen($_POST['input_password']) < config_var(11066)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Must be longer than '.config_var(11066).' characters',
            ));
        } else {

            //Validate READ ID and matching email:
            $validate_links = $this->TRANSACTION_model->fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_content' => $_POST['input_email'],
                'ln_type_source_id' => 7563, //User Signin Magic Link Email
            )); //The user making the request
            if(count($validate_links) < 1){
                //Probably previously completed the reset password:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Reset password link not found',
                ));
            }

            //Validate user:
            $ens = $this->SOURCE_model->fetch(array(
                'en_id' => $validate_links[0]['ln_creator_source_id'],
            ));
            if(count($ens) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'User not found',
                ));
            }


            //Generate the password hash:
            $password_hash = hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password']. $ens[0]['en_id'] );


            //Fetch their passwords to authenticate login:
            $user_passwords = $this->TRANSACTION_model->fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                'ln_profile_source_id' => 3286, //Mench Sign In Password
                'ln_portfolio_source_id' => $ens[0]['en_id'],
            ));

            if (count($user_passwords) > 0) {

                $detected_ln_type = ln_detect_type($password_hash);
                if (!$detected_ln_type['status']) {
                    return echo_json($detected_ln_type);
                }

                //Update existing password:
                $this->TRANSACTION_model->update($user_passwords[0]['ln_id'], array(
                    'ln_content' => $password_hash,
                    'ln_type_source_id' => $detected_ln_type['ln_type_source_id'],
                ), $ens[0]['en_id'], 7578 /* User updated Password */);

            } else {

                //Create new password link:
                $this->TRANSACTION_model->create(array(
                    'ln_type_source_id' => en_link_type_id($password_hash),
                    'ln_content' => $password_hash,
                    'ln_profile_source_id' => 3286, //Mench Password
                    'ln_creator_source_id' => $ens[0]['en_id'],
                    'ln_portfolio_source_id' => $ens[0]['en_id'],
                ));

            }


            //Log password reset:
            $this->TRANSACTION_model->create(array(
                'ln_creator_source_id' => $ens[0]['en_id'],
                'ln_type_source_id' => 7578, //User updated Password
                'ln_content' => $password_hash, //A copy of their password set at this time
            ));


            //Log them in:
            $this->SOURCE_model->activate_session($ens[0]);

            //Their next Idea in line:
            return echo_json(array(
                'status' => 1,
                'login_url' => '/read/next',
            ));


        }
    }




    function magicemail(){


        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['referrer_in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Cleanup/validate email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $user_emails = $this->TRANSACTION_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_content' => $_POST['input_email'],
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_profile_source_id' => 3288, //Mench Email
        ), array('en_portfolio'));
        if(count($user_emails) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Email not associated with a registered account',
            ));
        }

        //Log email search attempt:
        $reset_link = $this->TRANSACTION_model->create(array(
            'ln_type_source_id' => 7563, //User Signin Magic Link Email
            'ln_content' => $_POST['input_email'],
            'ln_creator_source_id' => $user_emails[0]['en_id'], //User making request
            'ln_previous_idea_id' => intval($_POST['referrer_in_id']),
        ));

        //This is a new email, send invitation to join:

        ##Email Subject
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION
        $subject = 'MENCH '.$en_all_11035[11068]['m_name'];

        ##Email Body
        $html_message = '<div>Hi '.one_two_explode('',' ',$user_emails[0]['en_name']).' 👋</div><br /><br />';

        $magic_link_expiry_hours = (config_var(11065)/3600);
        $html_message .= '<div>Login within the next '.$magic_link_expiry_hours.' hour'.echo__s($magic_link_expiry_hours).':</div>';
        $magic_url = 'https://mench.com/source/magic/' . $reset_link['ln_id'] . '?email='.$_POST['input_email'];
        $html_message .= '<div><a href="'.$magic_url.'" target="_blank">' . $magic_url . '</a></div>';

        $html_message .= '<br /><br />';
        $html_message .= '<div>'.echo_platform_message(12691).'</div>';
        $html_message .= '<div>MENCH</div>';

        //Send email:
        $this->READ_model->send_email(array($_POST['input_email']), $subject, $html_message);

        //Return success
        return echo_json(array(
            'status' => 1,
        ));
    }

    function magic($ln_id){

        //Validate email:
        if(superpower_assigned()){
            return redirect_message('/');
        } elseif(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
            //Missing email input:
            return redirect_message('/source/sign', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Missing Email</div>');
        }

        //Validate READ ID and matching email:
        $validate_links = $this->TRANSACTION_model->fetch(array(
            'ln_id' => $ln_id,
            'ln_content' => $_GET['email'],
            'ln_type_source_id' => 7563, //User Signin Magic Link Email
        )); //The user making the request
        if(count($validate_links) < 1){
            //Probably previously completed the reset password:
            return redirect_message('/source/sign?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Invalid data source</div>');
        } elseif(strtotime($validate_links[0]['ln_timestamp']) + config_var(11065) < time()){
            //Probably previously completed the reset password:
            return redirect_message('/source/sign?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Magic link has expired. Try again.</div>');
        }

        //Fetch source:
        $ens = $this->SOURCE_model->fetch(array(
            'en_id' => $validate_links[0]['ln_creator_source_id'],
        ));
        if(count($ens) < 1){
            return redirect_message('/source/sign?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>User not found</div>');
        }

        //Log them in:
        $this->SOURCE_model->activate_session($ens[0]);

        //Take them to READ HOME
        return redirect_message( '/read' , '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully signed in.</div>');

    }

    function singin_check_email(){

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['referrer_in_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }


        //Cleanup input email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));


        if(intval($_POST['referrer_in_id']) > 0){
            //Fetch the idea:
            $referrer_ins = $this->IDEA_model->fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                'in_id' => $_POST['referrer_in_id'],
            ));
        } else {
            $referrer_ins = array();
        }


        //Search for email to see if it exists...
        $user_emails = $this->TRANSACTION_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_content' => $_POST['input_email'],
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_profile_source_id' => 3288, //Mench Email
        ), array('en_portfolio'));

        if(count($user_emails) > 0){

            return echo_json(array(
                'status' => 1,
                'email_existed_previously' => 1,
                'login_en_id' => $user_emails[0]['en_id'],
                'clean_input_email' => $_POST['input_email'],
            ));

        } else {

            return echo_json(array(
                'status' => 1,
                'email_existed_previously' => 0,
                'login_en_id' => 0,
                'clean_input_email' => $_POST['input_email'],
            ));

        }
    }


}