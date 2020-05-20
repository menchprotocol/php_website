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
            $this->READ_model->create(array(
                'read__type' => 12489, //Opened Leaderboard
                'read__source' => $session_en['source__id'],
            ));
        }

        $sources__2738 = $this->config->item('sources__2738'); //MENCH
        $this->load->view('header', array(
            'title' => $sources__2738[4536]['m_name'],
        ));
        $this->load->view('source/source_home', array(
            'session_en' => $session_en,
        ));
        $this->load->view('footer');
    }


    //Lists sources
    function en_coin($source__id)
    {

        //Make sure not a private read:
        if(in_array($source__id, $this->config->item('sources_id_4755'))){
            $session_en = superpower_assigned(12701, true);
        } else {
            $session_en = superpower_assigned();
        }

        //Do we have any mass action to process here?
        if (superpower_assigned(12703) && isset($_POST['mass_action_source__id']) && isset($_POST['mass_value1_'.$_POST['mass_action_source__id']]) && isset($_POST['mass_value2_'.$_POST['mass_action_source__id']])) {

            //Process mass action:
            $process_mass_action = $this->SOURCE_model->mass_update($source__id, intval($_POST['mass_action_source__id']), $_POST['mass_value1_'.$_POST['mass_action_source__id']], $_POST['mass_value2_'.$_POST['mass_action_source__id']], $session_en['source__id']);

            //Pass-on results to UI:
            $message = '<div class="alert '.( $process_mass_action['status'] ? 'alert-info' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="fas fa-info-circle"></i></span>'.$process_mass_action['message'].'</div>';

        } else {

            //No mass action, just viewing...
            //Update session count and log link:
            $message = null; //No mass-action message to be appended...

            $new_order = ( $this->session->userdata('session_page_count') + 1 );
            $this->session->set_userdata('session_page_count', $new_order);
            $this->READ_model->create(array(
                'read__source' => $session_en['source__id'],
                'read__type' => 4994, //Player Opened Player
                'read__down' => $source__id,
                'read__sort' => $new_order,
            ));

        }

        //Validate source ID and fetch data:
        $ens = $this->SOURCE_model->fetch(array(
            'source__id' => $source__id,
        ));

        if (count($ens) < 1) {
            return redirect_message('/source');
        }

        //Load views:
        $this->load->view('header', array(
            'title' => $ens[0]['source__title'],
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
            'source__id' => $_POST['source__id'],
            'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
        ));

        if (!$session_en) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10967),
            ));
        } elseif (!isset($_POST['source__id']) || intval($_POST['source__id']) < 1 || count($ens) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid source__id',
            ));
        }



        //All good, reset sort value for all children:
        foreach($this->READ_model->fetch(array(
            'read__up' => $_POST['source__id'],
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
        ), array('source_portfolio'), 0, 0, array(), 'read__id') as $ln) {
            $this->READ_model->update($ln['read__id'], array(
                'read__sort' => 0,
            ), $session_en['source__id'], 13007 /* SOURCE SORT RESET */);
        }

        //Display message:
        view_json(array(
            'status' => 1,
        ));
    }


    function en_sort_save()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10967);
        if (!$session_en) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10967),
            ));
        } elseif (!isset($_POST['source__id']) || intval($_POST['source__id']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid source__id',
            ));
        } elseif (!isset($_POST['new_read__sorts']) || !is_array($_POST['new_read__sorts']) || count($_POST['new_read__sorts']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Source:
            $ens = $this->SOURCE_model->fetch(array(
                'source__id' => $_POST['source__id'],
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
            ));

            //Count Portfolio:
            $source__portfolio_count = $this->READ_model->fetch(array(
                'read__up' => $_POST['source__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
            ), array('source_portfolio'), 0, 0, array(), 'COUNT(source__id) as totals');

            if (count($ens) < 1) {

                view_json(array(
                    'status' => 0,
                    'message' => 'Invalid source__id',
                ));

            } elseif($source__portfolio_count[0]['totals'] > config_var(13005)){

                view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort sources if greater than '.config_var(13005),
                ));

            } else {

                //Update them all:
                foreach($_POST['new_read__sorts'] as $rank => $read__id) {
                    $this->READ_model->update(intval($read__id), array(
                        'read__sort' => intval($rank),
                    ), $session_en['source__id'], 13006 /* SOURCE SORT MANUAL */);
                }

                //Display message:
                view_json(array(
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
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
            'read__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
        );

        if($session_en){
            //This source is already listed at the top of the page, skip:
            $filters_in['source__id !='] = $session_en['source__id'];
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
            $filters_in['read__time >='] = $start_date.' 00:00:00'; //From beginning of the day
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

        foreach($this->READ_model->fetch($filters_in, array('source_profile'), $load_max, 0, array('totals' => 'DESC'), 'COUNT(read__id) as totals, source__id, source__title, source__icon, source__metadata, source__status, source__weight', 'source__id, source__title, source__icon, source__metadata, source__status, source__weight') as $count=>$en) {

            if($count==$show_max){

                echo '<div class="list-group-item see_more_who no-side-padding"><a href="javascript:void(0);" onclick="$(\'.see_more_who\').toggleClass(\'hidden\')" class="block"><span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source" style="text-decoration: none !important;">SEE MORE</b></a></div>';

                echo '<div class="list-group-item see_more_who no-height"></div>';

            }

            echo view_en($en, false, ( $count<$show_max ? '' : 'see_more_who hidden'));

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
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
                'url_source' => array(),
            ));
        }

        //All seems good, fetch URL:
        $url_source = $this->SOURCE_model->url($_POST['input_url']);

        if (!$url_source['status']) {
            //Oooopsi, we had some error:
            return view_json(array(
                'status' => 0,
                'message' => $url_source['message'],
            ));
        }

        //Return results:
        return view_json(array(
            'status' => 1,
            'source_domain_ui' => '<span class="en_mini_ui_icon">' . (isset($url_source['en_domain']['source__icon']) && strlen($url_source['en_domain']['source__icon']) > 0 ? $url_source['en_domain']['source__icon'] : detect_fav_icon($url_source['url_clean_domain'], true)) . '</span> ' . (isset($url_source['en_domain']['source__title']) ? $url_source['en_domain']['source__title'] . ' <a href="/source/' . $url_source['en_domain']['source__id'] . '" class="underdot" data-toggle="tooltip" title="Click to open domain source" data-placement="top">@' . $url_source['en_domain']['source__id'] . '</a>' : $url_source['url_domain_name'] . ' [<span data-toggle="tooltip" title="Domain source not yet added" data-placement="top">New</span>]'),
            'js_url_source' => $url_source,
        ));

    }


    function en_read_type_preview()
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
            $en_lns = $this->READ_model->fetch(array(
                'read__id' => $_POST['read__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_4537')) . ')' => null, //Player URL Links
            ));

            //Are they both different?
            if (count($en_lns) < 1 || ($en_lns[0]['read__up'] != $detected_read_type['en_url']['source__id'] && $en_lns[0]['read__down'] != $detected_read_type['en_url']['source__id'])) {
                //return error:
                return view_json($detected_read_type);
            }

        }



        return view_json(array(
            'status' => 1,
            'html_ui' => '<b class="montserrat doupper '.extract_icon_color($sources__4592[$detected_read_type['read__type']]['m_icon']).'">' . $sources__4592[$detected_read_type['read__type']]['m_icon'] . ' ' . $sources__4592[$detected_read_type['read__type']]['m_name'] . '</b>',
            'en_link_preview' => ( in_array($detected_read_type['read__type'], $this->config->item('sources_id_12524')) ? '<span class="paddingup inline-block">'.view_read__message($_POST['read__message'], $detected_read_type['read__type']).'</span>' : ''),
        ));

    }


    function en_save_file_upload()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10939);
        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));
        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {
            return view_json(array(
                'status' => 0,
                'message' => 'Unknown error 3 while trying to save file.',
            ));
        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(11063) * 1024 * 1024)) {
            return view_json(array(
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
        return view_json(upload_to_cdn($temp_local, 0, $_FILES[$_POST['upload_type']], true));

    }


    function en_load_next_page()
    {

        $items_per_page = config_var(11064);
        $parent_source__id = intval($_POST['parent_source__id']);
        $en_focus_filter = intval($_POST['en_focus_filter']);
        $is_source = en_is_source($parent_source__id);
        $page = intval($_POST['page']);
        $filters = array(
            'read__up' => $parent_source__id,
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'source__status IN (' . join(',', ( $en_focus_filter<0 /* Remove Filters */ ? $this->config->item('sources_id_7358') /* ACTIVE */ : array($en_focus_filter) /* This specific filter*/ )) . ')' => null,
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        );

        //Fetch & display next batch of children:
        $child_sources = $this->READ_model->fetch($filters, array('source_portfolio'), $items_per_page, ($page * $items_per_page), array(
            'read__sort' => 'ASC',
            'source__title' => 'ASC'
        ));

        foreach($child_sources as $en) {
            echo view_en($en,false, null, true, $is_source);
        }

        //Count total children:
        $child_sources_count = $this->READ_model->fetch($filters, array('source_portfolio'), 0, 0, array(), 'COUNT(read__id) as totals');

        //Do we need another load more button?
        if ($child_sources_count[0]['totals'] > (($page * $items_per_page) + count($child_sources))) {
            echo view_en_load_more(($page + 1), $items_per_page, $child_sources_count[0]['totals']);
        }

    }

    function en_source_only_unlink(){

        //Auth user and check required variables:
        $session_en = superpower_assigned(10939);

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['read__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Read ID',
            ));
        } elseif (!isset($_POST['idea__id']) || !in_is_source($_POST['idea__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are not the author of this source',
            ));
        }

        //Archive Link:
        $this->READ_model->update($_POST['read__id'], array(
            'read__status' => 6173,
        ), $session_en['source__id'], 10678 /* IDEA NOTES Unpublished */);

        return view_json(array(
            'status' => 1,
        ));

    }

    function en_source_only_add()
    {

        //Auth user and check required variables:
        $session_en = superpower_assigned(10939);

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['idea__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        } elseif (!isset($_POST['note_type_id']) || !in_array($_POST['note_type_id'], $this->config->item('sources_id_7551'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea Note Type ID',
            ));
        } elseif (!isset($_POST['en_existing_id']) || !isset($_POST['en_new_string']) || (intval($_POST['en_existing_id']) < 1 && strlen($_POST['en_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }


        //Validate Idea
        $ins = $this->IDEA_model->fetch(array(
            'idea__id' => $_POST['idea__id'],
            'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        ));
        if (count($ins) < 1) {
            return view_json(array(
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
                'source__id' => $_POST['en_existing_id'],
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
            ));
            if (count($ens) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid active source',
                ));
            }

            //Make sure not alreads linked:
            if(count($this->READ_model->fetch(array(
                'read__right' => $ins[0]['idea__id'],
                'read__up' => $_POST['en_existing_id'],
                'read__type' => $_POST['note_type_id'],
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            )))){
                $sources__7551 = $this->config->item('sources__7551');
                return view_json(array(
                    'status' => 0,
                    'message' => $ens[0]['source__title'].' is already added as idea '.$sources__7551[$_POST['note_type_id']]['m_name'],
                ));
            }


            //All good, assign:
            $focus_en = $ens[0];

        } else {

            //Create source:
            $added_en = $this->SOURCE_model->verify_create($_POST['en_new_string'], $session_en['source__id']);
            if(!$added_en['status']){
                //We had an error, return it:
                return view_json($added_en);
            }

            //Assign new source:
            $focus_en = $added_en['en'];

            //Assign to Player:
            $this->SOURCE_model->assign_session_player($focus_en['source__id']);

            //Update Algolia:
            update_algolia('en', $focus_en['source__id']);

        }

        //Create Note:
        $new_note = $this->READ_model->create(array(
            'read__source' => $session_en['source__id'],
            'read__type' => $_POST['note_type_id'],
            'read__right' => $ins[0]['idea__id'],

            'read__up' => $focus_en['source__id'],
            'read__message' => '@'.$focus_en['source__id'],
        ));

        //Return newly added or linked source:
        return view_json(array(
            'status' => 1,
            'en_new_echo' => view_en(array_merge($focus_en, $new_note), 0, null, true, true),
        ));

    }


    function en_add_or_link()
    {

        //Auth user and check required variables:
        $session_en = superpower_assigned(10939);

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['source__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Source',
            ));
        } elseif (!isset($_POST['is_parent'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Source Link Direction',
            ));
        } elseif (!isset($_POST['en_existing_id']) || !isset($_POST['en_new_string']) || (intval($_POST['en_existing_id']) < 1 && strlen($_POST['en_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }

        //Validate parent source:
        $current_en = $this->SOURCE_model->fetch(array(
            'source__id' => $_POST['source__id'],
        ));
        if (count($current_en) < 1) {
            return view_json(array(
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
                'source__id' => $_POST['en_existing_id'],
                'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
            ));

            if (count($ens) < 1) {
                return view_json(array(
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
                    return view_json($url_source);
                }

                //Is this a root domain? Add to domains if so:
                if($url_source['url_is_root']){

                    //Link to domains parent:
                    $focus_en = array('source__id' => 1326);

                    //Update domain to stay synced:
                    $_POST['en_new_string'] = $url_source['url_clean_domain'];

                } else {

                    //Let's first find/add the domain:
                    $domain_source = $this->SOURCE_model->domain($_POST['en_new_string'], $session_en['source__id']);

                    //Link to this source:
                    $focus_en = $domain_source['en_domain'];
                }

            } else {

                //Create source:
                $added_en = $this->SOURCE_model->verify_create($_POST['en_new_string'], $session_en['source__id']);
                if(!$added_en['status']){
                    //We had an error, return it:
                    return view_json($added_en);
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

                $read__down = $current_en[0]['source__id'];
                $read__up = $focus_en['source__id'];

            } else {

                $read__down = $focus_en['source__id'];
                $read__up = $current_en[0]['source__id'];

            }


            if (isset($url_source['url_is_root']) && $url_source['url_is_root']) {

                $read__type = 4256; //Generic URL (Domains always are generic)
                $read__message = $url_source['clean_url'];

            } elseif (isset($domain_source['en_domain'])) {

                $read__type = $url_source['read__type'];
                $read__message = $url_source['clean_url'];

            } else {

                $read__type = 4230; //Raw
                $read__message = null;

            }

            // Link to new OR existing source:
            $ur2 = $this->READ_model->create(array(
                'read__source' => $session_en['source__id'],
                'read__type' => $read__type,
                'read__message' => $read__message,
                'read__down' => $read__down,
                'read__up' => $read__up,
            ));
        }

        //Fetch latest version:
        $ens_latest = $this->SOURCE_model->fetch(array(
            'source__id' => $focus_en['source__id'],
            'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
        ));
        if(!count($ens_latest)){
            return view_json(array(
                'status' => 0,
                'message' => 'Failed to create/fetch new source',
            ));
        }

        //Return newly added or linked source:
        return view_json(array(
            'status' => 1,
            'en_new_echo' => view_en(array_merge($ens_latest[0], $ur2), $_POST['is_parent'], null, true, true),
        ));

    }

    function en_count_delete_links()
    {

        if (!isset($_POST['source__id']) || intval($_POST['source__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID',
            ));
        }

        //Simply counts the links for a given source:
        $all_en_links = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            '(read__down = ' . $_POST['source__id'] . ' OR read__up = ' . $_POST['source__id'] . ')' => null,
        ), array(), 0);

        return view_json(array(
            'status' => 1,
            'message' => 'Success',
            'en_link_count' => count($all_en_links),
        ));

    }



    function account_toggle_superpower($superpower_source__id){

        //Toggles the advance session variable for the player on/off for logged-in players:
        $session_en = superpower_assigned(10939);
        $superpower_source__id = intval($superpower_source__id);
        $sources__10957 = $this->config->item('sources__10957');

        if(!$session_en){

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif(!superpower_assigned($superpower_source__id)){

            //Access not authorized:
            return view_json(array(
                'status' => 0,
                'message' => 'You have not yet unlocked the superpower of '.$sources__10957[$superpower_source__id]['m_name'],
            ));

        }

        //Figure out new toggle state:
        $session_data = $this->session->all_userdata();

        if(in_array($superpower_source__id, $session_data['session_superpowers_activated'])){
            //Previously there, turn it off:
            $session_data['session_superpowers_activated'] = array_diff($session_data['session_superpowers_activated'], array($superpower_source__id));
            $toggled_setting = 'DEACTIVATED';
        } else {
            //Not there, turn it on:
            array_push($session_data['session_superpowers_activated'], $superpower_source__id);
            $toggled_setting = 'ACTIVATED';
        }


        //Update Session:
        $this->session->set_userdata($session_data);


        //Log Link:
        $this->READ_model->create(array(
            'read__source' => $session_en['source__id'],
            'read__type' => 5007, //TOGGLE SUPERPOWER
            'read__up' => $superpower_source__id,
            'read__message' => 'SUPERPOWER '.$toggled_setting, //To be used when player logs in again
        ));

        //Return to JS function:
        return view_json(array(
            'status' => 1,
            'message' => 'Success',
        ));
    }




    function en_modify_save()
    {

        //Auth user and check required variables:
        $session_en = superpower_assigned(10939);
        $success_message = 'Saved'; //Default, might change based on what we do...
        $is_valid_icon = is_valid_icon($_POST['source__icon']);

        //Fetch current data:
        $ens = $this->SOURCE_model->fetch(array(
            'source__id' => intval($_POST['source__id']),
        ));


        $source__title_validate = source__title_validate($_POST['source__title']);
        if(!$source__title_validate['status']){
            return view_json($source__title_validate);
        }

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['source__id']) || intval($_POST['source__id']) < 1 || !(count($ens) == 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid ID',
            ));
        } elseif (!isset($_POST['en_focus_id']) || intval($_POST['en_focus_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Focus ID',
            ));
        } elseif (!isset($_POST['source__status'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing status',
            ));
        } elseif (!isset($_POST['read__id']) || !isset($_POST['read__message']) || !isset($_POST['read__status'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing source link data',
            ));
        } elseif(!$is_valid_icon['status']){
            //Check if valid icon:
            return view_json(array(
                'status' => 0,
                'message' => $is_valid_icon['message'],
            ));
        }

        $delete_redirect_url = null;
        $delete_from_ui = 0;
        $js_read__type = 0; //Detect link type based on content

        //Prepare data to be updated:
        $en_update = array(
            'source__title' => $source__title_validate['source__title_clean'],
            'source__icon' => trim($_POST['source__icon']),
            'source__status' => intval($_POST['source__status']),
        );

        //Is this being deleted?
        if (!in_array($en_update['source__status'], $this->config->item('sources_id_7358') /* ACTIVE */) && !($en_update['source__status'] == $ens[0]['source__status'])) {


            //Make sure source is not referenced in key DB reference fields:
            $en_count_db_references = en_count_db_references($_POST['source__id'], false);
            if(count($en_count_db_references) > 0){

                $sources__6194 = $this->config->item('sources__6194');

                //Construct the message:
                $error_message = 'Cannot be deleted because source is referenced as ';
                foreach($en_count_db_references as $source__id=>$en_count){
                    $error_message .= $sources__6194[$source__id]['m_name'].' '.view_number($en_count).' times ';
                }

                return view_json(array(
                    'status' => 0,
                    'message' => $error_message,
                ));

            }



            //Count source references in IDEA NOTES:
            $messages = $this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
                'read__type IN (' . join(',', $this->config->item('sources_id_4485')) . ')' => null, //IDEA NOTES
                'read__up' => $_POST['source__id'],
            ), array('idea_next'), 0, 0, array('read__sort' => 'ASC'));

            //Assume no merge:
            $merged_ens = array();

            //See if we have merger source:
            if (strlen($_POST['en_merge']) > 0) {

                //Yes, validate this source:

                //Validate the input for updating linked Idea:
                $merger_source__id = 0;
                if (substr($_POST['en_merge'], 0, 1) == '@') {
                    $parts = explode(' ', $_POST['en_merge']);
                    $merger_source__id = intval(str_replace('@', '', $parts[0]));
                }

                if ($merger_source__id < 1) {

                    return view_json(array(
                        'status' => 0,
                        'message' => 'Unrecognized merger source [' . $_POST['en_merge'] . ']',
                    ));

                } elseif ($merger_source__id == $_POST['source__id']) {

                    return view_json(array(
                        'status' => 0,
                        'message' => 'Cannot merge source into itself',
                    ));

                } else {

                    //Finally validate merger source:
                    $merged_ens = $this->SOURCE_model->fetch(array(
                        'source__id' => $merger_source__id,
                        'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
                    ));
                    if (count($merged_ens) == 0) {
                        return view_json(array(
                            'status' => 0,
                            'message' => 'Could not find source @' . $merger_source__id,
                        ));
                    }

                }

            } elseif(count($messages) > 0){

                //Cannot delete this source until Idea references are deleted:
                return view_json(array(
                    'status' => 0,
                    'message' => 'You can delete source after removing all its IDEA NOTES references',
                ));

            }

            //Delete/merge SOURCE LINKS:
            if($_POST['source__id'] == $_POST['en_focus_id']){

                //Fetch parents to redirect to:
                $source__profiles = $this->READ_model->fetch(array(
                    'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                    'read__down' => $_POST['source__id'],
                    'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                    'source__status IN (' . join(',', $this->config->item('sources_id_7358')) . ')' => null, //ACTIVE
                ), array('source_profile'), 1);

            }


            $_POST['read__id'] = 0; //Do not consider the link as the source is being Deleted
            $delete_from_ui = 1; //Removing source
            $merger_source__id = (count($merged_ens) > 0 ? $merged_ens[0]['source__id'] : 0);
            $links_adjusted = $this->SOURCE_model->unlink($_POST['source__id'], $session_en['source__id'], $merger_source__id);

            //Show appropriate message based on action:
            if ($merger_source__id > 0) {

                if($_POST['source__id'] == $_POST['en_focus_id'] || $merged_ens[0]['source__id'] == $_POST['en_focus_id']){
                    //Player is being Deleted and merged into another source:
                    $delete_redirect_url = '/source/' . $merged_ens[0]['source__id'];
                }

                $success_message = 'Source deleted & merged its ' . $links_adjusted . ' links here';

            } else {

                if($_POST['source__id'] == $_POST['en_focus_id']){
                    $delete_redirect_url = '/source/' . ( count($source__profiles) ? $source__profiles[0]['source__id'] : $session_en['source__id'] );
                }

                //Display proper message:
                $success_message = 'Source deleted & its ' . $links_adjusted . ' links have been Unpublished.';

            }

        }


        if (intval($_POST['read__id']) > 0) { //DO we have a link to update?

            //Yes, first validate source link:
            $en_lns = $this->READ_model->fetch(array(
                'read__id' => $_POST['read__id'],
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            ));
            if (count($en_lns) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'INVALID READ ID',
                ));
            }


            //Status change?
            if($en_lns[0]['read__status']!=$_POST['read__status']){

                if (in_array($_POST['read__status'], $this->config->item('sources_id_7360') /* ACTIVE */)) {
                    $read__status = 10656; //Player Link updated Status
                } else {
                    $delete_from_ui = 1;
                    $read__status = 10673; //Player Link Unpublished
                }

                $this->READ_model->update($_POST['read__id'], array(
                    'read__status' => intval($_POST['read__status']),
                ), $session_en['source__id'], $read__status);
            }


            //Link content change?
            if ($en_lns[0]['read__message'] == $_POST['read__message']) {

                //Link content has not changed:
                $js_read__type = $en_lns[0]['read__type'];
                $read__message = $en_lns[0]['read__message'];

            } else {

                //Link content has changed:
                $detected_read_type = read_detect_type($_POST['read__message']);

                if (!$detected_read_type['status']) {

                    return view_json($detected_read_type);

                } elseif (in_array($detected_read_type['read__type'], $this->config->item('sources_id_4537'))) {

                    //This is a URL, validate modification:

                    if ($detected_read_type['url_is_root']) {

                        if ($en_lns[0]['read__up'] == 1326) {

                            //Override with the clean domain for consistency:
                            $_POST['read__message'] = $detected_read_type['url_clean_domain'];

                        } else {

                            //Domains can only be added to the domain source:
                            return view_json(array(
                                'status' => 0,
                                'message' => 'Domain URLs must link to <b>@1326 Domains</b> as source profile',
                            ));

                        }

                    } else {

                        if ($en_lns[0]['read__up'] == 1326) {

                            return view_json(array(
                                'status' => 0,
                                'message' => 'Only domain URLs can be linked to Domain source.',
                            ));

                        } elseif ($detected_read_type['en_domain']) {
                            //We do have the domain mapped! Is this connected to the domain source as its parent?
                            if ($detected_read_type['en_domain']['source__id'] != $en_lns[0]['read__up']) {
                                return view_json(array(
                                    'status' => 0,
                                    'message' => 'Must link to <b>@' . $detected_read_type['en_domain']['source__id'] . ' ' . $detected_read_type['en_domain']['source__title'] . '</b> as source profile',
                                ));
                            }
                        } else {
                            //We don't have the domain mapped, this is for sure not allowed:
                            return view_json(array(
                                'status' => 0,
                                'message' => 'Requires a new parent source for <b>' . $detected_read_type['url_tld'] . '</b>. Add by pasting URL into the [Add @Source] input field.',
                            ));
                        }

                    }

                }

                //Update variables:
                $read__message = $_POST['read__message'];
                $js_read__type = $detected_read_type['read__type'];


                $this->READ_model->update($_POST['read__id'], array(
                    'read__message' => $read__message,
                ), $session_en['source__id'], 10657 /* Player Link updated Content */);


                //Also, did the link type change based on the content change?
                if($js_read__type!=$en_lns[0]['read__type']){
                    $this->READ_model->update($_POST['read__id'], array(
                        'read__type' => $js_read__type,
                    ), $session_en['source__id'], 10659 /* Player Link updated Type */);
                }
            }
        }

        //Now update the DB:
        $this->SOURCE_model->update(intval($_POST['source__id']), $en_update, true, $session_en['source__id']);


        //Reset user session data if this data belongs to the logged-in user:
        if ($_POST['source__id'] == $session_en['source__id']) {
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
            'js_read__type' => intval($js_read__type),
        );

        if (intval($_POST['read__id']) > 0) {

            //Fetch source link:
            $lns = $this->READ_model->fetch(array(
                'read__id' => $_POST['read__id'],
            ), array('en_creator'));

            //Prep last updated:
            $return_array['read__message'] = view_read__message($read__message, $js_read__type);
            $return_array['read__message_final'] = $read__message; //In case content was updated

        }

        //Show success:
        return view_json($return_array);

    }


    function en_fetch_canonical_url(){

        //Auth user and check required variables:
        $session_en = superpower_assigned();

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['search_url']) || !filter_var($_POST['search_url'], FILTER_VALIDATE_URL)) {
            //This string was incorrectly detected as a URL by JS, return not found:
            return view_json(array(
                'status' => 1,
                'url_previously_existed' => 0,
            ));
        }

        //Fetch URL:
        $url_source = $this->SOURCE_model->url($_POST['search_url']);

        if($url_source['url_previously_existed']){
            return view_json(array(
                'status' => 1,
                'url_previously_existed' => 1,
                'algolia_object' => update_algolia('en', $url_source['en_url']['source__id'], 1),
            ));
        } else {
            return view_json(array(
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
         * that are displayed using view_radio_sources()
         *
         * */

        $session_en = superpower_assigned();

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['parent_source__id']) || intval($_POST['parent_source__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing parent source',
            ));
        } elseif (!isset($_POST['selected_source__id']) || intval($_POST['selected_source__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected source',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_previously_selected'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }


        if(!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']){
            //Since this is not a multi-select we want to delete all existing options...

            //Fetch all possible answers based on parent source:
            $filters = array(
                'read__up' => $_POST['parent_source__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
            );

            if($_POST['enable_mulitiselect'] && $_POST['was_previously_selected']){
                //Just delete this single item, not the other ones:
                $filters['read__down'] = $_POST['selected_source__id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->READ_model->fetch($filters, array('source_portfolio'), 0, 0) as $answer_en){
                array_push($possible_answers, $answer_en['source__id']);
            }

            //Delete selected options for this player:
            foreach($this->READ_model->fetch(array(
                'read__up IN (' . join(',', $possible_answers) . ')' => null,
                'read__down' => $session_en['source__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            )) as $delete_en){
                //Should usually delete a single option:
                $this->READ_model->update($delete_en['read__id'], array(
                    'read__status' => 6173, //Read Deleted
                ), $session_en['source__id'], 6224 /* User Account Updated */);
            }

        }

        //Add new option if not previously there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_previously_selected']){
            $this->READ_model->create(array(
                'read__up' => $_POST['selected_source__id'],
                'read__down' => $session_en['source__id'],
                'read__source' => $session_en['source__id'],
                'read__type' => en_link_type_id(),
            ));
        }


        //Log Account Update link type:
        $_POST['account_update_function'] = 'account_update_radio'; //Add this variable to indicate which My Account function created this link
        $this->READ_model->create(array(
            'read__source' => $session_en['source__id'],
            'read__type' => 6224, //My Account updated
            'read__message' => 'My Account '.( $_POST['enable_mulitiselect'] ? 'Multi-Select Radio Field ' : 'Single-Select Radio Field ' ).( $_POST['was_previously_selected'] ? 'Deleted' : 'Added' ),
            'read__metadata' => $_POST,
            'read__up' => $_POST['parent_source__id'],
            'read__down' => $_POST['selected_source__id'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated', //Alert: NOT shown in UI
        ));
    }






    function account_update_avatar_icon()
    {

        $session_en = superpower_assigned();

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['type_css'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing type_css',
            ));
        } elseif (!isset($_POST['icon_css'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing icon_css',
            ));
        }

        //Validate:
        $icon_new_css = $_POST['type_css'].' '.$_POST['icon_css'].' source';
        $validated = false;
        foreach($this->config->item('sources__12279') as $source__id => $m) {
            if(substr_count($m['m_icon'], $icon_new_css) == 1){
                $validated = true;
                break;
            }
        }
        if(!$validated){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid icon',
            ));
        }


        //Update icon:
        $new_avatar = '<i class="'.$icon_new_css.'"></i>';
        $this->SOURCE_model->update($session_en['source__id'], array(
            'source__icon' => $new_avatar,
        ), true, $session_en['source__id']);


        //Update Session:
        $session_en['source__icon'] = $new_avatar;
        $this->SOURCE_model->activate_session($session_en, true);


        return view_json(array(
            'status' => 1,
            'message' => 'Name updated',
            'new_avatar' => $new_avatar,
        ));
    }



    function account_update_email()
    {

        $session_en = superpower_assigned();

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['en_email']) || !filter_var($_POST['en_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        }


        if (strlen($_POST['en_email']) > 0) {

            //Cleanup:
            $_POST['en_email'] = trim(strtolower($_POST['en_email']));

            //Check to make sure not duplicate:
            $duplicates = $this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                'read__up' => 3288, //Mench Email
                'read__down !=' => $session_en['source__id'],
                'LOWER(read__message)' => $_POST['en_email'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Email previously in-use. Use another email or contact support for assistance.',
                ));
            }
        }


        //Fetch existing email:
        $user_emails = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__down' => $session_en['source__id'],
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__up' => 3288, //Mench Email
        ));
        if (count($user_emails) > 0) {

            if (strlen($_POST['en_email']) == 0) {

                //Delete email:
                $this->READ_model->update($user_emails[0]['read__id'], array(
                    'read__status' => 6173, //Read Deleted
                ), $session_en['source__id'], 6224 /* User Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email deleted',
                );

            } elseif ($user_emails[0]['read__message'] != $_POST['en_email']) {

                //Update if not duplicate:
                $this->READ_model->update($user_emails[0]['read__id'], array(
                    'read__message' => $_POST['en_email'],
                ), $session_en['source__id'], 6224 /* User Account Updated */);

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
            $this->READ_model->create(array(
                'read__source' => $session_en['source__id'],
                'read__down' => $session_en['source__id'],
                'read__type' => en_link_type_id($_POST['en_email']),
                'read__up' => 3288, //Mench Email
                'read__message' => $_POST['en_email'],
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
            $this->READ_model->create(array(
                'read__source' => $session_en['source__id'],
                'read__type' => 6224, //My Account updated
                'read__message' => 'My Account '.$return['message']. ( strlen($_POST['en_email']) > 0 ? ': '.$_POST['en_email'] : ''),
                'read__metadata' => $_POST,
            ));
        }


        //Return results:
        return view_json($return);


    }


    function account_update_password()
    {

        $session_en = superpower_assigned();

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < config_var(11066)) {
            return view_json(array(
                'status' => 0,
                'message' => 'New password must be '.config_var(11066).' characters or more',
            ));
        }

        //Fetch existing password:
        $user_passwords = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__up' => 3286, //Password
            'read__down' => $session_en['source__id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $session_en['source__id']));


        if (count($user_passwords) > 0) {

            if ($hashed_password == $user_passwords[0]['read__message']) {

                $return = array(
                    'status' => 0,
                    'message' => 'Password Unchanged',
                );

            } else {

                //Update password:
                $this->READ_model->update($user_passwords[0]['read__id'], array(
                    'read__message' => $hashed_password,
                ), $session_en['source__id'], 7578 /* User Updated Password  */);

                $return = array(
                    'status' => 1,
                    'message' => 'Password Updated',
                );

            }

        } else {

            //Create new link:
            $this->READ_model->create(array(
                'read__type' => en_link_type_id($hashed_password),
                'read__up' => 3286, //Password
                'read__source' => $session_en['source__id'],
                'read__down' => $session_en['source__id'],
                'read__message' => $hashed_password,
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Password Added',
            );

        }


        //Log Account Update link type:
        if($return['status']){
            $_POST['account_update_function'] = 'account_update_password'; //Add this variable to indicate which My Account function created this link
            $this->READ_model->create(array(
                'read__source' => $session_en['source__id'],
                'read__type' => 6224, //My Account Updated
                'read__message' => 'My Account '.$return['message'],
                'read__metadata' => $_POST,
            ));
        }


        //Return results:
        return view_json($return);

    }











    /*
     *
     * SIGN FUNCTIONS
     *
     *
     * */




    function sign($idea__id = 0){

        //Check to see if they are previously logged in?
        $session_en = superpower_assigned();
        if ($session_en) {
            //Lead player and above, go to console:
            if($idea__id > 0){
                return redirect_message('/idea/go/' . $idea__id);
            } else {
                return redirect_message('/read');
            }
        }

        //Update focus idea session:
        if($idea__id > 0){
            //Set in session:
            $this->session->set_userdata(array(
                'sign_idea__id' => $idea__id,
            ));

            //Redirect to basic login URL (So Facebook OAuth can validate)
            return redirect_message('/source/sign');
        }


        $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
        $this->load->view('header', array(
            'hide_header' => 1,
            'title' => $sources__11035[4269]['m_name'],
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

        if (!isset($_POST['referrer_idea__id']) || !isset($_POST['referrer_url'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['input_name']) || strlen($_POST['input_name'])<1) {
            return view_json(array(
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
            return view_json(array(
                'status' => 0,
                'message' => 'Name must longer than '.config_var(12232).' characters',
                'focus_input_field' => 'input_name',
            ));
        } elseif (strlen($_POST['input_name']) > config_var(6197)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Name must be less than '.config_var(6197).' characters',
                'focus_input_field' => 'input_name',
            ));

            /*
            } elseif (!isset($name_parts[1])) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'There must be a space between your your first and last name',
                    'focus_input_field' => 'input_name',
                ));
            } elseif (strlen($name_parts[1])<2) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Last name must be 2 characters or longer',
                    'focus_input_field' => 'input_name',
                ));
            } elseif (strlen($name_parts[0])<2) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'First name must be 2 characters or longer',
                    'focus_input_field' => 'input_name',
                ));
            */

        } elseif (!isset($_POST['new_password']) || strlen($_POST['new_password'])<1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing password',
                'focus_input_field' => 'new_password',
            ));
        } elseif (strlen($_POST['new_password']) < config_var(11066)) {
            return view_json(array(
                'status' => 0,
                'message' => 'New password must be '.config_var(11066).' characters or longer',
                'focus_input_field' => 'new_password',
            ));
        }



        //All good, create new source:
        $user_en = $this->SOURCE_model->verify_create(trim($_POST['input_name']), 0, 6181, random_player_avatar());
        if(!$user_en['status']){
            //We had an error, return it:
            return view_json($user_en);
        }


        //Add Player:
        $this->READ_model->create(array(
            'read__up' => 4430, //MENCH PLAYERS
            'read__type' => en_link_type_id(),
            'read__source' => $user_en['en']['source__id'],
            'read__down' => $user_en['en']['source__id'],
        ));

        $this->READ_model->create(array(
            'read__type' => en_link_type_id(trim(strtolower($_POST['input_email']))),
            'read__message' => trim(strtolower($_POST['input_email'])),
            'read__up' => 3288, //Mench Email
            'read__source' => $user_en['en']['source__id'],
            'read__down' => $user_en['en']['source__id'],
        ));
        $hash = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['new_password'] . $user_en['en']['source__id']));
        $this->READ_model->create(array(
            'read__type' => en_link_type_id($hash),
            'read__message' => $hash,
            'read__up' => 3286, //Mench Password
            'read__source' => $user_en['en']['source__id'],
            'read__down' => $user_en['en']['source__id'],
        ));

        //Now update Algolia:
        update_algolia('en',  $user_en['en']['source__id']);

        //Fetch referral Idea, if any:
        if(intval($_POST['referrer_idea__id']) > 0){

            //Fetch the Idea:
            $referrer_ins = $this->IDEA_model->fetch(array(
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'idea__id' => $_POST['referrer_idea__id'],
            ));

            if(count($referrer_ins) > 0){
                //Add this Idea to their Reads:
                $this->READ_model->start($user_en['en']['source__id'], $_POST['referrer_idea__id']);
            } else {
                //Cannot be added, likely because its not published:
                $_POST['referrer_idea__id'] = 0;
            }

        } else {
            $referrer_ins = array();
        }



        ##Email Subject
        $subject = 'Hi, '.$name_parts[0].'! 👋';

        ##Email Body
        $html_message = '<div>Just wanted to welcome you to Mench. You can create your first idea here:</div>';
        $html_message .= '<br /><br />';
        $html_message .= '<div>'.view_platform_message(12691).'</div><br />';
        $html_message .= '<div>MENCH</div>';

        //Send Welcome Email:
        $email_log = $this->READ_model->send_email(array($_POST['input_email']), $subject, $html_message);


        //Log User Signin Joined Mench
        $invite_link = $this->READ_model->create(array(
            'read__type' => 7562, //User Signin Joined Mench
            'read__source' => $user_en['en']['source__id'],
            'read__left' => intval($_POST['referrer_idea__id']),
            'read__metadata' => array(
                'email_log' => $email_log,
            ),
        ));

        //Assign session & log login link:
        $this->SOURCE_model->activate_session($user_en['en']);


        if (strlen($_POST['referrer_url']) > 0) {
            $login_url = urldecode($_POST['referrer_url']);
        } elseif(intval($_POST['referrer_idea__id']) > 0) {
            $login_url = '/idea/go/'.$_POST['referrer_idea__id'];
        } else {
            //Go to home page and let them continue from there:
            $login_url = '/';
        }

        return view_json(array(
            'status' => 1,
            'login_url' => $login_url,
        ));



    }




    function search_google($source__id){
        $ens = $this->SOURCE_model->fetch(array(
            'source__id' => $source__id,
        ));
        if(count($ens)){
            return redirect_message('https://www.google.com/search?q='.urlencode($ens[0]['source__title']));
        } else {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID'
            ));
        }
    }

    function search_icon($source__id){
        $ens = $this->SOURCE_model->fetch(array(
            'source__id' => $source__id,
        ));
        if(count($ens)){

            if(( substr_count($ens[0]['source__icon'], 'class="') ?  : null )){

                return redirect_message('/source/plugin/7267?search_for='.urlencode(one_two_explode('class="','"',$ens[0]['source__icon'])));

            } elseif(strlen($ens[0]['source__icon'])) {

                return redirect_message('/source/plugin/7267?search_for=' . urlencode($ens[0]['source__icon']));

            } else {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Source Missing Icon'
                ));
            }

        } else {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID'
            ));
        }
    }

    function singin_check_password(){

        if (!isset($_POST['login_source__id']) || intval($_POST['login_source__id'])<1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < config_var(11066)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Password',
            ));
        } elseif (!isset($_POST['referrer_url'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing referrer URL',
            ));
        } elseif (!isset($_POST['referrer_idea__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea referrer',
            ));
        }



        //Validaye user ID
        $ens = $this->SOURCE_model->fetch(array(
            'source__id' => $_POST['login_source__id'],
        ));
        if (!in_array($ens[0]['source__status'], $this->config->item('sources_id_7357') /* PUBLIC */)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Your account source is not public. Contact us to adjust your account.',
            ));
        }

        //Authenticate password:
        $ens[0]['is_masterpass_login'] = 0;
        $user_passwords = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__up' => 3286, //Password
            'read__down' => $ens[0]['source__id'],
        ));
        if (count($user_passwords) == 0) {
            //They do not have a password assigned yet!
            return view_json(array(
                'status' => 0,
                'message' => 'An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.',
            ));
        } elseif (!in_array($user_passwords[0]['read__status'], $this->config->item('sources_id_7359') /* PUBLIC */)) {
            //They do not have a password assigned yet!
            return view_json(array(
                'status' => 0,
                'message' => 'Password link is not public. Contact us to adjust your account.',
            ));
        } elseif ($user_passwords[0]['read__message'] != hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $ens[0]['source__id'])) {

            //Is this the master password?
            if(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password']) == config_var(13014)){

                $ens[0]['is_masterpass_login'] = 1;

            } else {

                //Bad password
                return view_json(array(
                    'status' => 0,
                    'message' => 'Incorrect password',
                ));

            }
        }


        //Assign session & log link:
        $this->SOURCE_model->activate_session($ens[0]);


        if (intval($_POST['referrer_idea__id']) > 0) {

            $login_url = '/read/start/'.$_POST['referrer_idea__id'];

        } elseif (isset($_POST['referrer_url']) && strlen($_POST['referrer_url']) > 0) {

            $login_url = urldecode($_POST['referrer_url']);

        } else {
            $login_url = '/';
        }

        return view_json(array(
            'status' => 1,
            'login_url' => $login_url,
        ));

    }



    function sign_reset_password_apply()
    {

        //This function updates the user's new password as requested via a password reset:
        if (!isset($_POST['read__id']) || intval($_POST['read__id']) < 1 || !isset($_POST['input_email']) || strlen($_POST['input_email']) < 1 || !isset($_POST['input_password'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (strlen($_POST['input_password']) < config_var(11066)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Must be longer than '.config_var(11066).' characters',
            ));
        } else {

            //Validate READ ID and matching email:
            $validate_links = $this->READ_model->fetch(array(
                'read__id' => $_POST['read__id'],
                'read__message' => $_POST['input_email'],
                'read__type' => 7563, //User Signin Magic Link Email
            )); //The user making the request
            if(count($validate_links) < 1){
                //Probably previously completed the reset password:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Reset password link not found',
                ));
            }

            //Validate user:
            $ens = $this->SOURCE_model->fetch(array(
                'source__id' => $validate_links[0]['read__source'],
            ));
            if(count($ens) < 1){
                return view_json(array(
                    'status' => 0,
                    'message' => 'User not found',
                ));
            }


            //Generate the password hash:
            $password_hash = hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password']. $ens[0]['source__id'] );


            //Fetch their passwords to authenticate login:
            $user_passwords = $this->READ_model->fetch(array(
                'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                'read__up' => 3286, //Mench Sign In Password
                'read__down' => $ens[0]['source__id'],
            ));

            if (count($user_passwords) > 0) {

                $detected_read_type = read_detect_type($password_hash);
                if (!$detected_read_type['status']) {
                    return view_json($detected_read_type);
                }

                //Update existing password:
                $this->READ_model->update($user_passwords[0]['read__id'], array(
                    'read__message' => $password_hash,
                    'read__type' => $detected_read_type['read__type'],
                ), $ens[0]['source__id'], 7578 /* User updated Password */);

            } else {

                //Create new password link:
                $this->READ_model->create(array(
                    'read__type' => en_link_type_id($password_hash),
                    'read__message' => $password_hash,
                    'read__up' => 3286, //Mench Password
                    'read__source' => $ens[0]['source__id'],
                    'read__down' => $ens[0]['source__id'],
                ));

            }


            //Log password reset:
            $this->READ_model->create(array(
                'read__source' => $ens[0]['source__id'],
                'read__type' => 7578, //User updated Password
                'read__message' => $password_hash, //A copy of their password set at this time
            ));


            //Log them in:
            $this->SOURCE_model->activate_session($ens[0]);

            //Their next Idea in line:
            return view_json(array(
                'status' => 1,
                'login_url' => '/read/next',
            ));


        }
    }




    function magicemail(){


        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['referrer_idea__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Cleanup/validate email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $user_emails = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__message' => $_POST['input_email'],
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__up' => 3288, //Mench Email
        ), array('source_portfolio'));
        if(count($user_emails) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Email not associated with a registered account',
            ));
        }

        //Log email search attempt:
        $reset_link = $this->READ_model->create(array(
            'read__type' => 7563, //User Signin Magic Link Email
            'read__message' => $_POST['input_email'],
            'read__source' => $user_emails[0]['source__id'], //User making request
            'read__left' => intval($_POST['referrer_idea__id']),
        ));

        //This is a new email, send invitation to join:

        ##Email Subject
        $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
        $subject = 'MENCH '.$sources__11035[11068]['m_name'];

        ##Email Body
        $html_message = '<div>Hi '.one_two_explode('',' ',$user_emails[0]['source__title']).' 👋</div><br /><br />';

        $magic_link_expiry_hours = (config_var(11065)/3600);
        $html_message .= '<div>Login within the next '.$magic_link_expiry_hours.' hour'.view__s($magic_link_expiry_hours).':</div>';
        $magic_url = $this->config->item('base_url').'source/magic/' . $reset_link['read__id'] . '?email='.$_POST['input_email'];
        $html_message .= '<div><a href="'.$magic_url.'" target="_blank">' . $magic_url . '</a></div>';

        $html_message .= '<br /><br />';
        $html_message .= '<div>'.view_platform_message(12691).'</div>';
        $html_message .= '<div>MENCH</div>';

        //Send email:
        $this->READ_model->send_email(array($_POST['input_email']), $subject, $html_message);

        //Return success
        return view_json(array(
            'status' => 1,
        ));
    }

    function magic($read__id){

        //Validate email:
        if(superpower_assigned()){
            return redirect_message('/');
        } elseif(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
            //Missing email input:
            return redirect_message('/source/sign', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Missing Email</div>');
        }

        //Validate READ ID and matching email:
        $validate_links = $this->READ_model->fetch(array(
            'read__id' => $read__id,
            'read__message' => $_GET['email'],
            'read__type' => 7563, //User Signin Magic Link Email
        )); //The user making the request
        if(count($validate_links) < 1){
            //Probably previously completed the reset password:
            return redirect_message('/source/sign?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Invalid data source</div>');
        } elseif(strtotime($validate_links[0]['read__time']) + config_var(11065) < time()){
            //Probably previously completed the reset password:
            return redirect_message('/source/sign?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle read"></i></span>Magic link has expired. Try again.</div>');
        }

        //Fetch source:
        $ens = $this->SOURCE_model->fetch(array(
            'source__id' => $validate_links[0]['read__source'],
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
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['referrer_idea__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }


        //Cleanup input email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));


        if(intval($_POST['referrer_idea__id']) > 0){
            //Fetch the idea:
            $referrer_ins = $this->IDEA_model->fetch(array(
                'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
                'idea__id' => $_POST['referrer_idea__id'],
            ));
        } else {
            $referrer_ins = array();
        }


        //Search for email to see if it exists...
        $user_emails = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__message' => $_POST['input_email'],
            'read__type IN (' . join(',', $this->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__up' => 3288, //Mench Email
        ), array('source_portfolio'));

        if(count($user_emails) > 0){

            return view_json(array(
                'status' => 1,
                'email_existed_previously' => 1,
                'login_source__id' => $user_emails[0]['source__id'],
                'clean_input_email' => $_POST['input_email'],
            ));

        } else {

            return view_json(array(
                'status' => 1,
                'email_existed_previously' => 0,
                'login_source__id' => 0,
                'clean_input_email' => $_POST['input_email'],
            ));

        }
    }


    function plugin($plugin_source__id = 0){

        if($plugin_source__id < 1){

            //List Plugins to choose from:
            $sources__11035 = $this->config->item('sources__11035'); //MENCH NAVIGATION
            $this->load->view('header', array(
                'title' => $sources__11035[6287]['m_name'],
            ));
            $this->load->view('source/source_plugin_home');
            $this->load->view('footer');

        } else {

            //Load a specific plugin:
            //Valud Plugin?
            if(!in_array($plugin_source__id, $this->config->item('sources_id_6287'))){
                die('Invalid Plugin ID');
            }

            //Running from browser? If so, authenticate:
            $is_player_request = isset($_SERVER['SERVER_NAME']);
            if($is_player_request){
                $session_en = superpower_assigned(12699, true);
            } else {
                $session_en = false;
            }

            //Needs extra superpowers?
            boost_power();
            $sources__6287 = $this->config->item('sources__6287'); //MENCH PLUGIN
            $superpower_actives = array_intersect($this->config->item('sources_id_10957'), $sources__6287[$plugin_source__id]['m_parents']);
            if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
                die(view_unauthorized_message(end($superpower_actives)));
            }


            //This is also duplicated in source_plugin_frame to pass-on to plugin file:
            $view_data = array(
                'plugin_source__id' => $plugin_source__id,
                'session_en' => $session_en,
                'is_player_request' => $is_player_request,
            );

            if(in_array($plugin_source__id, $this->config->item('sources_id_12741'))){

                //Raw UI:
                $this->load->view('source/plugin/'.$plugin_source__id.'/index', $view_data);

            } else {

                //Regular UI:
                //Load Plugin:
                $this->load->view('header', array(
                    'title' => strip_tags($sources__6287[$plugin_source__id]['m_icon']).$sources__6287[$plugin_source__id]['m_name'].' | PLUGIN',
                ));
                $this->load->view('source/source_plugin_frame', $view_data);
                $this->load->view('footer');

            }
        }
    }

    function plugin_7264(){

        //Authenticate Player:
        $session_en = superpower_assigned(12700);

        if (!$session_en) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(12700),
            ));
        } elseif (!isset($_POST['idea__id']) || intval($_POST['idea__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Starting Idea',
            ));
        } elseif (!isset($_POST['depth_levels']) || intval($_POST['depth_levels']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Depth',
            ));
        }

        //Fetch/Validate idea:
        $ins = $this->IDEA_model->fetch(array(
            'idea__id' => $_POST['idea__id'],
            'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
        ));
        if(count($ins) != 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Could not find idea #'.$_POST['idea__id'],
            ));
        }


        //Load AND/OR Ideas:
        $sources__7585 = $this->config->item('sources__7585'); // Idea Subtypes
        $sources__4737 = $this->config->item('sources__4737'); // Idea Status


        //Return report:
        return view_json(array(
            'status' => 1,
            'message' => '<h3>'.$sources__7585[$ins[0]['idea__type']]['m_icon'].' '.$sources__4737[$ins[0]['idea__status']]['m_icon'].' '.view_idea__title($ins[0]).'</h3>'.view_in_scores_answer($_POST['idea__id'], $_POST['depth_levels'], $_POST['depth_levels'], $ins[0]['idea__type']),
        ));


    }


}