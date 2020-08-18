<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }


    function index()
    {
        //source:
        $user_e = superpower_assigned(null);

        //Log View:
        if($user_e){
            $this->X_model->create(array(
                'x__type' => 12489, //Opened Leaderboard
                'x__source' => $user_e['e__id'],
            ));
        }

        $e___11035 = $this->config->item('e___11035');
        $this->load->view('header', array(
            'title' => $e___11035[13207]['m_title'],
        ));
        $this->load->view('e/home', array(
            'user_e' => $user_e,
        ));
        $this->load->view('footer');
    }

    function e_404()
    {
        $this->load->view('header', array(
            'title' => 'Page Not Found',
        ));
        $this->load->view('e/404');
        $this->load->view('footer');
    }

    //Lists sources
    function e_coin($e__id)
    {

        //Make sure not a private discover:
        if(in_array($e__id, $this->config->item('n___4755'))){
            $user_e = superpower_assigned(12701, true);
        } else {
            $user_e = superpower_assigned();
        }

        //Do we have any mass action to process here?
        if (superpower_assigned(12703) && isset($_POST['mass_action_e__id']) && isset($_POST['mass_value1_'.$_POST['mass_action_e__id']]) && isset($_POST['mass_value2_'.$_POST['mass_action_e__id']])) {

            //Process mass action:
            $process_mass_action = $this->E_model->mass_update($e__id, intval($_POST['mass_action_e__id']), $_POST['mass_value1_'.$_POST['mass_action_e__id']], $_POST['mass_value2_'.$_POST['mass_action_e__id']], $user_e['e__id']);

            //Pass-on results to UI:
            $message = '<div class="alert '.( $process_mass_action['status'] ? 'alert-info' : 'alert-danger' ).'" role="alert"><span class="icon-block"><i class="fas fa-info-circle"></i></span>'.$process_mass_action['message'].'</div>';

        } else {

            //No mass action, just viewing...
            //Update session count and log transaction:
            $message = null; //No mass-action message to be appended...

            $new_order = ( $this->session->userdata('session_page_count') + 1 );
            $this->session->set_userdata('session_page_count', $new_order);
            $this->X_model->create(array(
                'x__source' => $user_e['e__id'],
                'x__type' => 4994, //User Viewed Source
                'x__down' => $e__id,
                'x__sort' => $new_order,
            ));

        }

        //Validate source ID and fetch data:
        $es = $this->E_model->fetch(array(
            'e__id' => $e__id,
        ));

        if (count($es) < 1) {
            return redirect_message('/@');
        }

        //Load views:
        $this->load->view('header', array(
            'title' => $es[0]['e__title'],
            'flash_message' => $message, //Possible mass-action message for UI:
        ));
        $this->load->view('e/layout', array(
            'e' => $es[0],
            'user_e' => $user_e,
        ));
        $this->load->view('footer');

    }


    function e_sort_reset()
    {

        //Authenticate User:
        $user_e = superpower_assigned(13422);

        //Validate Source:
        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
            'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));

        if (!$user_e) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(13422),
            ));
        } elseif (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1 || count($es) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid e__id',
            ));
        }



        //All good, reset sort value for all children:
        foreach($this->X_model->fetch(array(
            'x__up' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down'), 0, 0, array(), 'x__id') as $x) {
            $this->X_model->update($x['x__id'], array(
                'x__sort' => 0,
            ), $user_e['e__id'], 13007 /* SOURCE SORT RESET */);
        }

        //Display message:
        view_json(array(
            'status' => 1,
        ));
    }


    function e_sort_save()
    {

        //Authenticate User:
        $user_e = superpower_assigned(10939);
        if (!$user_e) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid e__id',
            ));
        } elseif (!isset($_POST['new_x__sorts']) || !is_array($_POST['new_x__sorts']) || count($_POST['new_x__sorts']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e__id'],
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));

            //Count Portfolio:
            $list_e_count = $this->X_model->fetch(array(
                'x__up' => $_POST['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');

            if (count($es) < 1) {

                view_json(array(
                    'status' => 0,
                    'message' => 'Invalid e__id',
                ));

            } elseif($list_e_count[0]['totals'] > config_var(13005)){

                view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort sources if greater than '.config_var(13005),
                ));

            } else {

                //Update them all:
                foreach($_POST['new_x__sorts'] as $rank => $x__id) {
                    $this->X_model->update($x__id, array(
                        'x__sort' => intval($rank),
                    ), $user_e['e__id'], 13006 /* SOURCE SORT MANUAL */);
                }

                //Display message:
                view_json(array(
                    'status' => 1,
                ));

            }
        }
    }



    function e_upload_file()
    {

        //Authenticate User:
        $user_e = superpower_assigned(10939);
        if (!$user_e) {
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
        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(13572) * 1024 * 1024)) {
            return view_json(array(
                'status' => 0,
                'message' => 'File is larger than the maximum file size of ' . config_var(13572) . ' MB.',
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


    function e_load_page()
    {

        $items_per_page = config_var(11064);
        $parent_e__id = intval($_POST['parent_e__id']);
        $e_focus_filter = intval($_POST['e_focus_filter']);
        $source_of_e = source_of_e($parent_e__id);
        $page = intval($_POST['page']);
        $filters = array(
            'x__up' => $parent_e__id,
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'e__status IN (' . join(',', ( $e_focus_filter<0 /* Remove Filters */ ? $this->config->item('n___7358') /* ACTIVE */ : array($e_focus_filter) /* This specific filter*/ )) . ')' => null,
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        );

        //Fetch & display next batch of children:
        $child_e = $this->X_model->fetch($filters, array('x__down'), $items_per_page, ($page * $items_per_page), array(
            'x__sort' => 'ASC',
            'e__title' => 'ASC'
        ));

        foreach($child_e as $e) {
            echo view_e($e,false, null, true, $source_of_e);
        }

        //Count total children:
        $child_e_count = $this->X_model->fetch($filters, array('x__down'), 0, 0, array(), 'COUNT(x__id) as totals');

        //Do we need another load more button?
        if ($child_e_count[0]['totals'] > (($page * $items_per_page) + count($child_e))) {
            echo view_e_load_more(($page + 1), $items_per_page, $child_e_count[0]['totals']);
        }

    }

    function remove_10673(){

        //Auth user and check required variables:
        $user_e = superpower_assigned(10939);

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Transaction ID',
            ));
        }

        //Archive Transaction:
        $this->X_model->update($_POST['x__id'], array(
            'x__status' => 6173,
        ), $user_e['e__id'], 10673 /* IDEA NOTES Unpublished */);

        return view_json(array(
            'status' => 1,
        ));

    }

    function e_only_add()
    {

        //Auth user and check required variables:
        $user_e = superpower_assigned(10939);

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        } elseif (!isset($_POST['note_type_id']) || !in_array($_POST['note_type_id'], $this->config->item('n___7551'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea Note Type ID',
            ));
        } elseif (!isset($_POST['e_existing_id']) || !isset($_POST['e_new_string']) || (intval($_POST['e_existing_id']) < 1 && strlen($_POST['e_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }


        //Validate Idea
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if (count($is) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea',
            ));
        }


        //Set some variables:
        $_POST['e_existing_id'] = intval($_POST['e_existing_id']);

        //Are we adding an existing source?
        if ($_POST['e_existing_id'] > 0) {

            //Validate this existing source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e_existing_id'],
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid active source',
                ));
            }

            //Make sure not already there:
            if(count($this->X_model->fetch(array(
                'x__right' => $is[0]['i__id'],
                'x__up' => $_POST['e_existing_id'],
                'x__type' => $_POST['note_type_id'],
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))){
                $e___7551 = $this->config->item('e___7551');
                return view_json(array(
                    'status' => 0,
                    'message' => $es[0]['e__title'].' is already added as idea '.$e___7551[$_POST['note_type_id']]['m_title'],
                ));
            }


            //All good, assign:
            $focus_e = $es[0];

        } else {

            //Create source:
            $added_e = $this->E_model->verify_create($_POST['e_new_string'], $user_e['e__id']);
            if(!$added_e['status']){
                //We had an error, return it:
                return view_json($added_e);
            }

            //Assign new source:
            $focus_e = $added_e['new_e'];

            //Assign to User:
            $this->E_model->add_source($focus_e['e__id']);

            //Update Algolia:
            update_algolia(12274, $focus_e['e__id']);

        }

        //Create Note:
        $new_note = $this->X_model->create(array(
            'x__source' => $user_e['e__id'],
            'x__type' => $_POST['note_type_id'],
            'x__right' => $is[0]['i__id'],
            'x__up' => $focus_e['e__id'],
            'x__message' => '@'.$focus_e['e__id'],
        ));

        //Return source:
        return view_json(array(
            'status' => 1,
            'e_new_echo' => view_e(array_merge($focus_e, $new_note), 0, null, true, true),
        ));

    }


    function e__add()
    {

        //Auth user and check required variables:
        $user_e = superpower_assigned(10939);

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Source',
            ));
        } elseif (!isset($_POST['is_parent'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Source Transaction Direction',
            ));
        } elseif (!isset($_POST['e_existing_id']) || !isset($_POST['e_new_string']) || (intval($_POST['e_existing_id']) < 1 && strlen($_POST['e_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }

        //Validate parent source:
        $fetch_e = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
        ));
        if (count($fetch_e) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid parent source ID',
            ));
        }


        //Set some variables:
        $_POST['is_parent'] = intval($_POST['is_parent']);
        $_POST['e_existing_id'] = intval($_POST['e_existing_id']);
        $is_url_input = false;

        //Are we adding an existing source?
        if (intval($_POST['e_existing_id']) > 0) {

            //Validate this existing source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e_existing_id'],
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));

            if (count($es) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid active source',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new source OR adding a URL...

            //Is this a URL?
            if (filter_var($_POST['e_new_string'], FILTER_VALIDATE_URL)) {

                //Digest URL to see what type it is and if we have any errors:
                $url_e = $this->E_model->url($_POST['e_new_string']);
                if (!$url_e['status']) {
                    return view_json($url_e);
                }

                //Is this a root domain? Add to domains if so:
                if($url_e['url_root']){

                    //Domain
                    $focus_e = array('e__id' => 1326);

                    //Update domain to stay synced:
                    $_POST['e_new_string'] = $url_e['url_clean_domain'];

                } else {

                    //Let's first find/add the domain:
                    $url_domain = $this->E_model->domain($_POST['e_new_string'], $user_e['e__id']);

                    //Add this source:
                    $focus_e = $url_domain['e_domain'];
                }

            } else {

                //Create source:
                $added_e = $this->E_model->verify_create($_POST['e_new_string'], $user_e['e__id']);
                if(!$added_e['status']){
                    //We had an error, return it:
                    return view_json($added_e);
                } else {
                    //Assign new source:
                    $focus_e = $added_e['new_e'];
                }

            }

        }


        //We need to check to ensure this is not a duplicate transaction if adding an existing source:
        $ur2 = array();

        if (!$is_url_input) {

            //Add transactions only if not previously added by the URL function:
            if ($_POST['is_parent']) {

                //Profile
                $x__down = $fetch_e[0]['e__id'];
                $x__up = $focus_e['e__id'];
                $x__sort = 0; //Never sort profiles, only sort portfolios

            } else {

                //Portfolio
                $x__up = $fetch_e[0]['e__id'];
                $x__down = $focus_e['e__id'];

                if(sources_currently_sorted($x__up)){

                    $x__sort = 1 + $this->X_model->max_sort(array(
                            'x__up' => $x__up,
                            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        ));

                } else {

                    //Don't sort since currently not sorted:
                    $x__sort = 0;

                }

            }


            if (isset($url_e['url_root']) && $url_e['url_root']) {

                $x__message = $url_e['clean_url'];
                $x__type = e_x__type($x__message);

            } elseif (isset($url_e['e_domain']) && $url_e['e_domain']) {

                $x__message = $url_e['clean_url'];
                $x__type = $url_e['x__type'];

            } else {

                $x__message = null;
                $x__type = e_x__type($x__message);

            }

            //Create transaction:
            $ur2 = $this->X_model->create(array(
                'x__source' => $user_e['e__id'],
                'x__type' => $x__type,
                'x__message' => $x__message,
                'x__down' => $x__down,
                'x__up' => $x__up,
                'x__sort' => $x__sort,
            ));
        }

        //Fetch latest version:
        $es_latest = $this->E_model->fetch(array(
            'e__id' => $focus_e['e__id'],
            'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if(!count($es_latest)){
            return view_json(array(
                'status' => 0,
                'message' => 'Failed to create/fetch new source',
            ));
        }

        //Return source:
        return view_json(array(
            'status' => 1,
            'e_new_echo' => view_e(array_merge($es_latest[0], $ur2), $_POST['is_parent'], null, true, true),
        ));

    }

    function e_count_deletion()
    {

        if (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID',
            ));
        }

        //Simply counts the transactions for a given source:
        $all_e_x = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            '(x__down = ' . $_POST['e__id'] . ' OR x__up = ' . $_POST['e__id'] . ')' => null,
        ), array(), 0);

        return view_json(array(
            'status' => 1,
            'message' => 'Success',
            'e_x_count' => count($all_e_x),
        ));

    }



    function e_toggle_superpower($superpower_e__id){

        //Toggles the advance session variable for the user on/off for logged-in users:
        $user_e = superpower_assigned(10939);
        $superpower_e__id = intval($superpower_e__id);
        $e___10957 = $this->config->item('e___10957');

        if(!$user_e){

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));

        } elseif(!superpower_assigned($superpower_e__id)){

            //Access not authorized:
            return view_json(array(
                'status' => 0,
                'message' => 'You have not yet unlocked the superpower of '.$e___10957[$superpower_e__id]['m_title'],
            ));

        }

        //Figure out new toggle state:
        $session_data = $this->session->all_userdata();

        if(in_array($superpower_e__id, $session_data['session_superpowers_activated'])){
            //Previously there, turn it off:
            $session_data['session_superpowers_activated'] = array_diff($session_data['session_superpowers_activated'], array($superpower_e__id));
            $toggled_setting = 'DEACTIVATED';
        } else {
            //Not there, turn it on:
            array_push($session_data['session_superpowers_activated'], $superpower_e__id);
            $toggled_setting = 'ACTIVATED';
        }


        //Update Session:
        $this->session->set_userdata($session_data);


        //Log Transaction:
        $this->X_model->create(array(
            'x__source' => $user_e['e__id'],
            'x__type' => 5007, //TOGGLE SUPERPOWER
            'x__up' => $superpower_e__id,
            'x__message' => 'SUPERPOWER '.$toggled_setting, //To be used when user logs in again
        ));

        //Return to JS function:
        return view_json(array(
            'status' => 1,
            'message' => 'Success',
        ));
    }




    function save_13571()
    {

        //Auth user and check required variables:
        $user_e = superpower_assigned(10939);
        $success_message = 'Saved'; //Default, might change based on what we do...
        $is_valid_icon = is_valid_icon($_POST['e__icon']);

        //Fetch current data:
        $es = $this->E_model->fetch(array(
            'e__id' => intval($_POST['e__id']),
        ));


        $e__title_validate = e__title_validate($_POST['e__title']);
        if(!$e__title_validate['status']){
            return view_json($e__title_validate);
        }

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1 || !(count($es) == 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid ID',
            ));
        } elseif (!isset($_POST['e_focus_id']) || intval($_POST['e_focus_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Focus ID',
            ));
        } elseif (!isset($_POST['e__status'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing status',
            ));
        } elseif (!isset($_POST['x__id']) || !isset($_POST['x__message']) || !isset($_POST['x__status'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing source transaction data',
            ));
        } elseif(!$is_valid_icon['status']){
            //Check if valid icon:
            return view_json(array(
                'status' => 0,
                'message' => $is_valid_icon['message'],
            ));
        } elseif($_POST['do_13527'] && !superpower_active(13422, true)){
            //Check if valid icon:
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(13422),
            ));
        }


        $delete_redirect_url = null;
        $delete_from_ui = 0;
        $js_x__type = 0; //Detect transaction type based on content

        //Prepare data to be updated:
        $e__update = array(
            'e__title' => $e__title_validate['e__title_clean'],
            'e__icon' => trim($_POST['e__icon']),
            'e__status' => intval($_POST['e__status']),
        );

        //Is this being deleted?
        if (!in_array($e__update['e__status'], $this->config->item('n___7358') /* ACTIVE */) && !($e__update['e__status'] == $es[0]['e__status'])) {


            //Make sure source is not referenced in key DB reference fields:
            $e_count_6194 = e_count_6194($_POST['e__id']);
            if(count($e_count_6194) > 0){

                $e___6194 = $this->config->item('e___6194');

                //Construct the message:
                $error_message = 'Cannot be deleted because source is referenced as ';
                foreach($e_count_6194 as $e__id=>$e_count){
                    $error_message .= $e___6194[$e__id]['m_title'].' '.view_number($e_count).' times ';
                }

                return view_json(array(
                    'status' => 0,
                    'message' => $error_message,
                ));

            }



            //Count source references in IDEA NOTES:
            $i_notes = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4485')) . ')' => null, //IDEA NOTES
                'x__up' => $_POST['e__id'],
            ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));
            if(count($i_notes) && !$_POST['do_13527']){
                //Cannot delete this source until Idea references are deleted:
                return view_json(array(
                    'status' => 0,
                    'message' => 'You can delete source after removing all its IDEA NOTES references',
                ));
            }



            //Delete SOURCE LINKS:
            if($_POST['e__id'] == $_POST['e_focus_id']){

                //Fetch parents to redirect to:
                $e__profiles = $this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__down' => $_POST['e__id'],
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                ), array('x__up'), 1);

            }


            $_POST['x__id'] = 0; //Do not consider the transaction as the source is being Deleted
            $delete_from_ui = 1; //Removing source
            $x_adjusted = $this->E_model->remove($_POST['e__id'], $user_e['e__id']);

            //Show appropriate message based on action:
            if($_POST['e__id'] == $_POST['e_focus_id']){
                if(count($e__profiles)){
                    $delete_redirect_url = '/@' . $e__profiles[0]['e__id'];
                } else {
                    //Is the plugin activated?
                    $delete_redirect_url = ( intval($this->session->userdata('session_time_7269')) ? '/e/plugin/7269' : '/@' );
                }
            }

            //Display proper message:
            $success_message = 'Source deleted & removed ' . $x_adjusted . ' Links.';

        }


        if (intval($_POST['x__id']) > 0) { //DO we have a transaction to update?

            //Yes, first validate source transaction:
            $e_x = $this->X_model->fetch(array(
                'x__id' => $_POST['x__id'],
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ));
            if (count($e_x) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'INVALID DISCOVER ID',
                ));
            }


            //Status change?
            if($e_x[0]['x__status']!=$_POST['x__status']){

                if (in_array($_POST['x__status'], $this->config->item('n___7360') /* ACTIVE */)) {
                    $x__status = 10656; //User Transaction updated Status
                } else {
                    $delete_from_ui = 1;
                    $x__status = 10673; //User Transaction Unpublished
                }

                $this->X_model->update($_POST['x__id'], array(
                    'x__status' => intval($_POST['x__status']),
                ), $user_e['e__id'], $x__status);
            }


            //Transaction content change?
            if ($e_x[0]['x__message'] == $_POST['x__message']) {

                //Transaction content has not changed:
                $js_x__type = $e_x[0]['x__type'];
                $x__message = $e_x[0]['x__message'];

            } else {

                //Transaction content has changed:
                $detected_x_type = x_detect_type($_POST['x__message']);

                if (!$detected_x_type['status']) {

                    return view_json($detected_x_type);

                } elseif (in_array($detected_x_type['x__type'], $this->config->item('n___4537'))) {

                    //This is a URL, validate modification:

                    if ($detected_x_type['url_root']) {

                        if ($e_x[0]['x__up'] == 1326) {

                            //Override with the clean domain for consistency:
                            $_POST['x__message'] = $detected_x_type['url_clean_domain'];

                        } else {

                            //Domains can only be added to the domain source:
                            return view_json(array(
                                'status' => 0,
                                'message' => 'Domain URLs requires <b>@1326 Domains</b> in profile',
                            ));

                        }

                    } else {

                        if ($e_x[0]['x__up'] == 1326) {

                            return view_json(array(
                                'status' => 0,
                                'message' => 'Only domain URLs can be connected to Domain source.',
                            ));

                        } elseif ($detected_x_type['e_domain']) {
                            //We do have the domain saved! Is this connected to the domain source as its parent?
                            if ($detected_x_type['e_domain']['e__id'] != $e_x[0]['x__up']) {
                                return view_json(array(
                                    'status' => 0,
                                    'message' => 'Must have <b>@' . $detected_x_type['e_domain']['e__id'] . ' ' . $detected_x_type['e_domain']['e__title'] . '</b> in profile',
                                ));
                            }
                        } else {
                            //We don't have the domain saved, this is for sure not allowed:
                            return view_json(array(
                                'status' => 0,
                                'message' => 'Requires a new parent source for <b>' . $detected_x_type['url_tld'] . '</b>. Add by pasting URL into the [Add @Source] input field.',
                            ));
                        }

                    }

                }

                //Update variables:
                $x__message = $_POST['x__message'];
                $js_x__type = $detected_x_type['x__type'];


                $this->X_model->update($_POST['x__id'], array(
                    'x__message' => $x__message,
                ), $user_e['e__id'], 10657 /* SOURCE LINK CONTENT UPDATE */);


                //Also, did the transaction type change based on the content change?
                if($js_x__type!=$e_x[0]['x__type']){
                    $this->X_model->update($_POST['x__id'], array(
                        'x__type' => $js_x__type,
                    ), $user_e['e__id'], 10659 /* User Transaction updated Type */);
                }
            }
        }

        //Now update the DB:
        $this->E_model->update(intval($_POST['e__id']), $e__update, true, $user_e['e__id']);


        //Reset user session data if this data belongs to the logged-in user:
        if ($_POST['e__id'] == $user_e['e__id']) {
            //Re-activate Session with new data:
            $this->E_model->activate_session($user_e, true);
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
            'js_x__type' => intval($js_x__type),
        );

        if (intval($_POST['x__id']) > 0) {

            //Fetch source transaction:
            $x = $this->X_model->fetch(array(
                'x__id' => $_POST['x__id'],
            ), array('x__source'));

            //Prep last updated:
            $return_array['x__message'] = view_x__message($x__message, $js_x__type);
            $return_array['x__message_final'] = $x__message; //In case content was updated

        }

        //Show success:
        return view_json($return_array);

    }


    function e_fetch_canonical(){

        //Auth user and check required variables:
        $user_e = superpower_assigned();

        if (!$user_e) {
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
        $url_e = $this->E_model->url($_POST['search_url']);

        if($url_e['url_previously_existed']){
            return view_json(array(
                'status' => 1,
                'url_previously_existed' => 1,
                'algolia_object' => update_algolia(12274, $url_e['e_url']['e__id'], 1),
            ));
        } else {
            return view_json(array(
                'status' => 1,
                'url_previously_existed' => 0,
            ));
        }
    }




    function e_radio()
    {
        /*
         *
         * Saves the radio selection of some account fields
         * that are displayed using view_radio_e()
         *
         * */

        $user_e = superpower_assigned();

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['parent_e__id']) || intval($_POST['parent_e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing parent source',
            ));
        } elseif (!isset($_POST['selected_e__id']) || intval($_POST['selected_e__id']) < 1) {
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
                'x__up' => $_POST['parent_e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
            );

            if($_POST['enable_mulitiselect'] && $_POST['was_previously_selected']){
                //Just delete this single item, not the other ones:
                $filters['x__down'] = $_POST['selected_e__id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->X_model->fetch($filters, array('x__down'), 0, 0) as $answer_e){
                array_push($possible_answers, $answer_e['e__id']);
            }

            //Delete selected options for this user:
            foreach($this->X_model->fetch(array(
                'x__up IN (' . join(',', $possible_answers) . ')' => null,
                'x__down' => $user_e['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )) as $delete){
                //Should usually delete a single option:
                $this->X_model->update($delete['x__id'], array(
                    'x__status' => 6173, //Transaction Removed
                ), $user_e['e__id'], 6224 /* User Account Updated */);
            }

        }

        //Add new option if not previously there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_previously_selected']){
            $this->X_model->create(array(
                'x__up' => $_POST['selected_e__id'],
                'x__down' => $user_e['e__id'],
                'x__source' => $user_e['e__id'],
                'x__type' => e_x__type(),
            ));
        }


        //Log Account Update transaction type:
        $_POST['account_update_function'] = 'e_radio'; //Add this variable to indicate which My Account function created this transaction
        $this->X_model->create(array(
            'x__source' => $user_e['e__id'],
            'x__type' => 6224, //My Account updated
            'x__message' => 'My Account '.( $_POST['enable_mulitiselect'] ? 'Multi-Select Radio Field ' : 'Single-Select Radio Field ' ).( $_POST['was_previously_selected'] ? 'Deleted' : 'Added' ),
            'x__metadata' => $_POST,
            'x__up' => $_POST['parent_e__id'],
            'x__down' => $_POST['selected_e__id'],
        ));


        //HACK: Update Font size in session:
        if($_POST['parent_e__id']==13491){
            $this->session->set_userdata(array('session_var_13491' => intval($_POST['selected_e__id'])));
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated', //NOT shown in UI
        ));
    }






    function e_avatar()
    {

        $user_e = superpower_assigned();

        if (!$user_e) {
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
        foreach($this->config->item('e___12279') as $e__id => $m) {
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
        $this->E_model->update($user_e['e__id'], array(
            'e__icon' => $new_avatar,
        ), true, $user_e['e__id']);


        //Update Session:
        $user_e['e__icon'] = $new_avatar;
        $this->E_model->activate_session($user_e, true);


        return view_json(array(
            'status' => 1,
            'message' => 'Name updated',
            'new_avatar' => $new_avatar,
        ));
    }



    function e_email()
    {

        $user_e = superpower_assigned();

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['e_email']) || !filter_var($_POST['e_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        }


        if (strlen($_POST['e_email']) > 0) {

            //Cleanup:
            $_POST['e_email'] = trim(strtolower($_POST['e_email']));

            //Check to make sure not duplicate:
            $duplicates = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => 3288, //Mench Email
                'x__down !=' => $user_e['e__id'],
                'LOWER(x__message)' => $_POST['e_email'],
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
        $u_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $user_e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Mench Email
        ));
        if (count($u_emails) > 0) {

            if (strlen($_POST['e_email']) == 0) {

                //Delete email:
                $this->X_model->update($u_emails[0]['x__id'], array(
                    'x__status' => 6173, //Transaction Removed
                ), $user_e['e__id'], 6224 /* User Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email deleted',
                );

            } elseif ($u_emails[0]['x__message'] != $_POST['e_email']) {

                //Update if not duplicate:
                $this->X_model->update($u_emails[0]['x__id'], array(
                    'x__message' => $_POST['e_email'],
                ), $user_e['e__id'], 6224 /* User Account Updated */);

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

        } elseif (strlen($_POST['e_email']) > 0) {

            //Create new transaction:
            $this->X_model->create(array(
                'x__source' => $user_e['e__id'],
                'x__down' => $user_e['e__id'],
                'x__type' => e_x__type($_POST['e_email']),
                'x__up' => 3288, //Mench Email
                'x__message' => $_POST['e_email'],
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
            //Log Account Update transaction type:
            $_POST['account_update_function'] = 'e_email'; //Add this variable to indicate which My Account function created this transaction
            $this->X_model->create(array(
                'x__source' => $user_e['e__id'],
                'x__type' => 6224, //My Account updated
                'x__message' => 'My Account '.$return['message']. ( strlen($_POST['e_email']) > 0 ? ': '.$_POST['e_email'] : ''),
                'x__metadata' => $_POST,
            ));
        }


        //Return results:
        return view_json($return);


    }


    function e_password()
    {

        $user_e = superpower_assigned();

        if (!$user_e) {
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
        $u_passwords = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3286, //Password
            'x__down' => $user_e['e__id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $user_e['e__id']));


        if (count($u_passwords) > 0) {

            if ($hashed_password == $u_passwords[0]['x__message']) {

                $return = array(
                    'status' => 0,
                    'message' => 'Password Unchanged',
                );

            } else {

                //Update password:
                $this->X_model->update($u_passwords[0]['x__id'], array(
                    'x__message' => $hashed_password,
                ), $user_e['e__id'], 7578 /* User Updated Password  */);

                $return = array(
                    'status' => 1,
                    'message' => 'Password Updated',
                );

            }

        } else {

            //Create new transaction:
            $this->X_model->create(array(
                'x__type' => e_x__type($hashed_password),
                'x__up' => 3286, //Password
                'x__source' => $user_e['e__id'],
                'x__down' => $user_e['e__id'],
                'x__message' => $hashed_password,
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Password Added',
            );

        }


        //Log Account Update transaction type:
        if($return['status']){
            $_POST['account_update_function'] = 'e_password'; //Add this variable to indicate which My Account function created this transaction
            $this->X_model->create(array(
                'x__source' => $user_e['e__id'],
                'x__type' => 6224, //My Account Updated
                'x__message' => 'My Account '.$return['message'],
                'x__metadata' => $_POST,
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


    function signin($i__id = 0){

        //Check to see if they are previously logged in?
        if(superpower_assigned()) {
            //Lead user and above, go to console:
            if($i__id > 0){
                return redirect_message(( superpower_assigned(10939) ? '/i/i_go/' : home_url() ) . $i__id);
            } else {
                return redirect_message(home_url());
            }
        }

        $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
        $this->load->view('header', array(
            'hide_header' => 1,
            'title' => $e___11035[4269]['m_title'],
        ));
        $this->load->view('e/signin', array(
            'sign_i__id' => $i__id,
        ));
        $this->load->view('footer');

    }


    function signout()
    {
        //Destroys Session
        $this->session->sess_destroy();
        header('Location: /');
    }



    function e_signin_create(){

        if (!isset($_POST['sign_i__id']) || !isset($_POST['referrer_url'])) {
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
        $added_e = $this->E_model->verify_create(trim($_POST['input_name']), 0, 6181, random_avatar());
        if(!$added_e['status']){
            //We had an error, return it:
            return view_json($added_e);
        }


        //Add User:
        $this->X_model->create(array(
            'x__up' => 4430, //MENCH USERS
            'x__type' => e_x__type(),
            'x__source' => $added_e['new_e']['e__id'],
            'x__down' => $added_e['new_e']['e__id'],
        ));

        $this->X_model->create(array(
            'x__type' => e_x__type(trim(strtolower($_POST['input_email']))),
            'x__message' => trim(strtolower($_POST['input_email'])),
            'x__up' => 3288, //Mench Email
            'x__source' => $added_e['new_e']['e__id'],
            'x__down' => $added_e['new_e']['e__id'],
        ));
        $hash = strtolower(hash('sha256', $this->config->item('cred_password_salt') . $_POST['new_password'] . $added_e['new_e']['e__id']));
        $this->X_model->create(array(
            'x__type' => e_x__type($hash),
            'x__message' => $hash,
            'x__up' => 3286, //Mench Password
            'x__source' => $added_e['new_e']['e__id'],
            'x__down' => $added_e['new_e']['e__id'],
        ));

        //Now update Algolia:
        update_algolia(12274,  $added_e['new_e']['e__id']);

        //Fetch referral Idea, if any:
        if(intval($_POST['sign_i__id']) > 0){

            //Fetch the Idea:
            $referrer_i = $this->I_model->fetch(array(
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'i__id' => $_POST['sign_i__id'],
            ));

            if(count($referrer_i) > 0){
                //Add this Idea to their Discoveries:
                $this->X_model->start($added_e['new_e']['e__id'], $_POST['sign_i__id']);
            } else {
                //Cannot be added, likely because its not published:
                $_POST['sign_i__id'] = 0;
            }

        } else {
            $referrer_i = array();
        }



        ##Email Subject
        $subject = 'Welcome to Mench 👐';
        $profile_url = $this->config->item('base_url').'/@'.$added_e['new_e']['e__id'];

        ##Email Body
        $html_message  = '<div>Hi, '.$name_parts[0].'! 👋</div><br />';
        $html_message .= '<div>'.view_12687(7562).'</div><br />';
        $html_message .= '<div>You can manage your profile here: <a href="'.$profile_url.'">'.$profile_url.'</a></div><br />';
        $html_message .= '<div>'.view_12687(12691).'</div><br />';
        $html_message .= '<div>MENCH</div>';

        //Send & Log Email:
        $this->X_model->email_sent(array($_POST['input_email']), $subject, $html_message);
        $invite_x = $this->X_model->create(array(
            'x__type' => 7562, //User Signin Joined Mench
            'x__source' => $added_e['new_e']['e__id'],
            'x__left' => intval($_POST['sign_i__id']),
        ));

        //Assign session & log login transaction:
        $this->E_model->activate_session($added_e['new_e']);


        if (strlen($_POST['referrer_url']) > 0) {
            $sign_url = urldecode($_POST['referrer_url']);
        } elseif(intval($_POST['sign_i__id']) > 0) {
            $sign_url = '/i/i_go/'.$_POST['sign_i__id'];
        } else {
            //Go to home page and let them continue from there:
            $sign_url = home_url();
        }

        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));



    }




    function search_google($e__id){
        $es = $this->E_model->fetch(array(
            'e__id' => $e__id,
        ));
        if(count($es)){
            return redirect_message('https://www.google.com/search?q='.urlencode($es[0]['e__title']));
        } else {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID'
            ));
        }
    }

    function search_icon($e__id){
        $es = $this->E_model->fetch(array(
            'e__id' => $e__id,
        ));
        if(count($es)){

            if(( substr_count($es[0]['e__icon'], 'class="') ?  : null )){

                return redirect_message('/e/plugin/7267?search_for='.urlencode(one_two_explode('class="','"',$es[0]['e__icon'])));

            } elseif(strlen($es[0]['e__icon'])) {

                return redirect_message('/e/plugin/7267?search_for=' . urlencode($es[0]['e__icon']));

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

    function e_signin_password(){

        if (!isset($_POST['sign_e__id']) || intval($_POST['sign_e__id'])<1) {
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
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea referrer',
            ));
        }



        //Validaye user ID
        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['sign_e__id'],
        ));
        if (!in_array($es[0]['e__status'], $this->config->item('n___7357') /* PUBLIC */)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Your account source is not public. Contact us to adjust your account.',
            ));
        }

        //Authenticate password:
        $es[0]['is_masterpass_login'] = 0;
        $u_passwords = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3286, //Password
            'x__down' => $es[0]['e__id'],
        ));
        if (count($u_passwords) == 0) {
            //They do not have a password assigned yet!
            return view_json(array(
                'status' => 0,
                'message' => 'An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.',
            ));
        } elseif (!in_array($u_passwords[0]['x__status'], $this->config->item('n___7359') /* PUBLIC */)) {
            //They do not have a password assigned yet!
            return view_json(array(
                'status' => 0,
                'message' => 'Password transaction is not public. Contact us to adjust your account.',
            ));
        } elseif ($u_passwords[0]['x__message'] != hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password'] . $es[0]['e__id'])) {

            //Is this the master password?
            if(hash('sha256', $this->config->item('cred_password_salt') . $_POST['input_password']) == config_var(13014)){

                $es[0]['is_masterpass_login'] = 1;

            } else {

                //Bad password
                return view_json(array(
                    'status' => 0,
                    'message' => 'Incorrect password',
                ));

            }
        }


        //Assign session & log transaction:
        $this->E_model->activate_session($es[0]);


        if (intval($_POST['sign_i__id']) > 0) {

            $sign_url = '/x/x_start/'.$_POST['sign_i__id'];

        } elseif (isset($_POST['referrer_url']) && strlen($_POST['referrer_url']) > 0) {

            $sign_url = urldecode($_POST['referrer_url']);

        } else {
            $sign_url = home_url();
        }

        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }




    function e_magic_email(){


        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Cleanup/validate email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $u_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__message' => $_POST['input_email'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Mench Email
        ), array('x__down'));
        if(count($u_emails) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Email not associated with a registered account',
            ));
        }

        //Log email search attempt:
        $reset_x = $this->X_model->create(array(
            'x__type' => 7563, //User Signin Magic Email
            'x__message' => $_POST['input_email'],
            'x__source' => $u_emails[0]['e__id'], //User making request
            'x__left' => intval($_POST['sign_i__id']),
        ));

        //This is a new email, send invitation to join:

        ##Email Subject
        $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
        $subject = 'MENCH '.$e___11035[11068]['m_title'];

        ##Email Body
        $html_message = '<div>Hi '.one_two_explode('',' ',$u_emails[0]['e__title']).' 👋</div><br /><br />';

        $magic_x_expiry_hours = (config_var(11065)/3600);
        $html_message .= '<div>Login within the next '.$magic_x_expiry_hours.' hour'.view__s($magic_x_expiry_hours).':</div>';
        $magic_url = $this->config->item('base_url').'/e/e_magic_sign/' . $reset_x['x__id'] . '?email='.$_POST['input_email'];
        $html_message .= '<div><a href="'.$magic_url.'" target="_blank">' . $magic_url . '</a></div>';

        $html_message .= '<br /><br />';
        $html_message .= '<div>'.view_12687(12691).'</div>';
        $html_message .= '<div>MENCH</div>';

        //Send email:
        $this->X_model->email_sent(array($_POST['input_email']), $subject, $html_message);

        //Return success
        return view_json(array(
            'status' => 1,
        ));
    }

    function e_magic_sign($x__id){

        //Validate email:
        if(superpower_assigned()){
            return redirect_message(home_url());
        } elseif(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
            //Missing email input:
            return redirect_message('/e/signin/', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Missing Email</div>');
        }

        //Validate DISCOVER ID and matching email:
        $validate_x = $this->X_model->fetch(array(
            'x__id' => $x__id,
            'x__message' => $_GET['email'],
            'x__type' => 7563, //User Signin Magic Email
        )); //The user making the request
        if(count($validate_x) < 1){
            //Probably previously completed the reset password:
            return redirect_message('/e/signin?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Invalid data source</div>');
        } elseif(strtotime($validate_x[0]['x__time']) + config_var(11065) < time()){
            //Probably previously completed the reset password:
            return redirect_message('/e/signin?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>Magic transaction has expired. Try again.</div>');
        }

        //Fetch source:
        $es = $this->E_model->fetch(array(
            'e__id' => $validate_x[0]['x__source'],
        ));
        if(count($es) < 1){
            return redirect_message('/e/signin?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle discover"></i></span>User not found</div>');
        }

        //Log them in:
        $this->E_model->activate_session($es[0]);

        //Take them to DISCOVER HOME
        return redirect_message( home_url() , '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Successfully signed in.</div>');

    }

    function e_signin_email(){

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Email',
            ));
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }


        //Cleanup input email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));


        if(intval($_POST['sign_i__id']) > 0){
            //Fetch the idea:
            $referrer_i = $this->I_model->fetch(array(
                'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'i__id' => $_POST['sign_i__id'],
            ));
        } else {
            $referrer_i = array();
        }


        //Search for email to see if it exists...
        $u_emails = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__message' => $_POST['input_email'],
            'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Mench Email
        ), array('x__down'));

        if(count($u_emails) > 0){

            return view_json(array(
                'status' => 1,
                'email_existed_previously' => 1,
                'sign_e__id' => $u_emails[0]['e__id'],
                'clean_input_email' => $_POST['input_email'],
            ));

        } else {

            return view_json(array(
                'status' => 1,
                'email_existed_previously' => 0,
                'sign_e__id' => 0,
                'clean_input_email' => $_POST['input_email'],
            ));

        }
    }


    function plugin($plugin_e__id = 0){

        if($plugin_e__id < 1){

            //List Plugins to choose from:
            $e___11035 = $this->config->item('e___11035'); //MENCH NAVIGATION
            $this->load->view('header', array(
                'title' => $e___11035[6287]['m_title'],
            ));
            $this->load->view('e/plugin_home');
            $this->load->view('footer');

        } else {

            //Load a specific plugin:
            //Valud Plugin?
            if(!in_array($plugin_e__id, $this->config->item('n___6287'))){
                die('Invalid Plugin ID');
            }

            //Running from browser? If so, authenticate:
            $is_u_request = isset($_SERVER['SERVER_NAME']);
            if($is_u_request){
                $user_e = superpower_assigned(12699, true);
            } else {
                $user_e = false;
            }

            //Needs extra superpowers?
            boost_power();
            $e___6287 = $this->config->item('e___6287'); //MENCH PLUGIN
            $superpower_actives = array_intersect($this->config->item('n___10957'), $e___6287[$plugin_e__id]['m_profile']);
            if($is_u_request && count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
                die(view_unauthorized_message(end($superpower_actives)));
            }


            //This is also duplicated in plugin_frame to pass-on to plugin file:
            $view_data = array(
                'plugin_e__id' => $plugin_e__id,
                'user_e' => $user_e,
                'is_u_request' => $is_u_request,
            );

            if(in_array($plugin_e__id, $this->config->item('n___12741'))){

                //Raw UI:
                $this->load->view('e/plugin/'.$plugin_e__id.'/index', $view_data);

            } else {

                //Regular UI:
                //Load Plugin:
                $this->load->view('header', array(
                    'title' => strip_tags($e___6287[$plugin_e__id]['m_icon']).$e___6287[$plugin_e__id]['m_title'].' | PLUGIN',
                ));
                $this->load->view('e/plugin_frame', $view_data);
                $this->load->view('footer');

            }
        }
    }


    function load_13571(){

        if (!isset($_POST['e__id']) || !isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core inputs',
            ));
        }

        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
        ));
        if(!count($es)){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source ID',
            ));
        }
        $return_data = array(
            'status' => 1,
            'e__title' => $es[0]['e__title'],
            'e__status' => $es[0]['e__status'],
            'e__icon' => $es[0]['e__icon'],
        );

        if($_POST['x__id'] > 0){

            $fetch_xs = $this->X_model->fetch(array(
                'x__id' => $_POST['x__id'],
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ));
            if(!count($fetch_xs)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Transaction ID',
                ));
            }

            //Append more data:
            $return_data['x__status'] = $fetch_xs[0]['x__status'];
            $return_data['x__message'] = $fetch_xs[0]['x__message'];

        }

        return view_json($return_data);

    }

    function plugin_7264(){

        //Authenticate User:
        $user_e = superpower_assigned(12700);

        if (!$user_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(12700),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
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
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if(count($is) != 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Could not find idea #'.$_POST['i__id'],
            ));
        }


        //Load AND/OR Ideas:
        $e___7585 = $this->config->item('e___7585'); // Idea Subtypes
        $e___4737 = $this->config->item('e___4737'); // Idea Status


        //Return report:
        return view_json(array(
            'status' => 1,
            'message' => '<h3>'.$e___7585[$is[0]['i__type']]['m_icon'].' '.$e___4737[$is[0]['i__status']]['m_icon'].' '.view_i_title($is[0]).'</h3>'.view_i_scores_answer($_POST['i__id'], $_POST['depth_levels'], $_POST['depth_levels'], $is[0]['i__type']),
        ));


    }


}