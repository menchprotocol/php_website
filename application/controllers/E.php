<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        universal_check();

    }

    function view_body_e(){
        //Authenticate Member:
        if (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1 || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo view_body_e($_POST['x__type'], $_POST['counter'], $_POST['e__id']);
        }
    }



    //Lists sources
    function e_layout($e__id)
    {

        //Validate source ID and fetch data:
        $es = $this->E_model->fetch(array(
            'e__id' => $e__id,
        ));
        if (count($es) < 1) {
            return redirect_message(home_url());
        }

        $member_e = superpower_unlocked();
        //Make sure not a private source:
        if(!in_array($es[0]['e__access'], $this->config->item('n___33240') /* PUBLIC/GUEST Access */) && !e_of_e($e__id)){
            $member_e = superpower_unlocked(13422, true);
        }

        $e___14874 = $this->config->item('e___14874'); //Mench Cards

        //Load views:
        $this->load->view('header', array(
            'title' => $es[0]['e__title'].' | '.$e___14874[12274]['m__title'],
        ));
        $this->load->view('e_layout', array(
            'e' => $es[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }



    function e_load_cover(){

        if (!isset($_POST['e__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';

        } else {

            $ui = '';
            $listed_items = 0;

            if($_POST['x__type']==11030 || $_POST['x__type']==12274){

                //SOURCES
                $current_e = ( substr($_POST['first_segment'], 0, 1)=='@' ? intval(substr($_POST['first_segment'], 1)) : 0 );
                $e___6177 = $this->config->item('e___6177'); //Source Status
                $e___4593 = $this->config->item('e___4593'); //Transaction Types

                foreach(view_e_covers($_POST['x__type'], $_POST['e__id'], 1, false) as $source_e) {
                    if(isset($source_e['e__id'])){
                        $ui .= view_card('/@'.$source_e['e__id'], $source_e['e__id']==$current_e, $e___4593[$source_e['x__type']]['m__cover'], $e___6177[$source_e['e__access']]['m__cover'], view_cover($source_e['e__cover'], true), $source_e['e__title'], preview_x__message($source_e['x__message'],$source_e['x__type']));
                        $listed_items++;
                    }
                }

            } elseif($_POST['x__type']==12273 || $_POST['x__type']==6255){

                //IDEAS
                $current_i = ( substr($_POST['first_segment'], 0, 1)=='~' ? intval(substr($_POST['first_segment'], 1)) : 0 );
                $e___31004 = $this->config->item('e___31004'); //Idea Status
                $e___4737 = $this->config->item('e___4737'); //Idea Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types

                foreach(view_e_covers($_POST['x__type'], $_POST['e__id'], 1, false) as $next_i) {
                    if(isset($next_i['i__id'])){
                        $ui .= view_card('/~'.$next_i['i__id'], $next_i['i__id']==$current_i, $e___4593[$next_i['x__type']]['m__cover'], $e___31004[$next_i['i__access']]['m__cover'], $e___4737[$next_i['i__type']]['m__cover'], view_i_title($next_i), preview_x__message($next_i['x__message'],$next_i['x__type']));
                        $listed_items++;
                    }
                }

            }

            if($listed_items < $_POST['counter']){
                //We have more to show:
                $ui .= view_card('/@'.$_POST['e__id'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
            }

            echo $ui;
        }
    }

    function sort_e_handle_save()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        if (!$member_e) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid e__id',
            ));
        } elseif (!isset($_POST['new_x__weight']) || !is_array($_POST['new_x__weight']) || count($_POST['new_x__weight']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e__id'],
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));

            //Count followers:
            $list_e_count = $this->X_model->fetch(array(
                'x__up' => $_POST['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');

            if (count($es) < 1) {

                view_json(array(
                    'status' => 0,
                    'message' => 'Invalid e__id',
                ));

            } elseif($list_e_count[0]['totals'] > view_memory(6404,11064)){

                view_json(array(
                    'status' => 0,
                    'message' => 'Cannot sort sources if greater than '.view_memory(6404,11064),
                ));

            } else {

                //Update them all:
                foreach($_POST['new_x__weight'] as $rank => $x__id) {
                    $this->X_model->update($x__id, array(
                        'x__weight' => intval($rank),
                    ), $member_e['e__id'], 13006 /* SOURCE SORT MANUAL */);
                }

                //Display message:
                view_json(array(
                    'status' => 1,
                ));

            }
        }
    }





    function e_remove(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
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
            'x__access' => 6173,
        ), $member_e['e__id'], 10673 /* IDEA NOTES Unpublished */);

        return view_json(array(
            'status' => 1,
        ));

    }


    function e_copy(){

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Following Source',
            ));
        }


        //Validate Source:
        $fetch_o = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if (count($fetch_o) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid followings source ID',
            ));
        }



        //Create:
        $added_e = $this->E_model->verify_create($fetch_o[0]['e__title']." Copy", $member_e['e__id'], $fetch_o[0]['e__cover']);
        if(!$added_e['status']){
            //We had an error, return it:
            return view_json($added_e);
        } else {
            //Assign new source:
            $focus_e = $added_e['new_e'];
        }


        //Followers:
        foreach($this->X_model->fetch(array(
            'x__up' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___41303')) . ')' => null, //Clone Source Links
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0) as $x) {
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__up' => $focus_e['e__id'],
                'x__down' => $x['x__down'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__access' => $x['x__access'],
            ));
        }


        //Followings:
        foreach($this->X_model->fetch(array(
            'x__down' => $_POST['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___41303')) . ')' => null, //Clone Source Links
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0) as $x) {
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__up' => $x['x__up'],
                'x__down' => $focus_e['e__id'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__access' => $x['x__access'],
            ));
        }

        //Ideas:
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___41302')) . ')' => null, //Clone Idea Source Links
            'x__up' => $_POST['e__id'],
        ), array(), 0) as $x){
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => $x['x__type'],
                'x__up' => $focus_e['e__id'],
                'x__down' => $x['x__down'],
                'x__left' => $x['x__left'],
                'x__right' => $x['x__right'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__access' => $x['x__access'],
            ));
        }


        return view_json(array(
            'status' => 1,
            'new_e__id' => $focus_e['e__id'],
        ));


    }

    function e__add()
    {

        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (intval($_POST['focus_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Following Source',
            ));
        } elseif (!isset($_POST['x__type'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Creation Type',
            ));
        } elseif (!isset($_POST['e_existing_id']) || !isset($_POST['e_new_string']) || (intval($_POST['e_existing_id']) < 1 && strlen($_POST['e_new_string']) < 1)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Either New Source ID or Source Name',
            ));
        }

        $adding_to_idea = ($_POST['focus_card']==12273);

        if($adding_to_idea){

            //Validate Idea:
            $fetch_o = $this->I_model->fetch(array(
                'i__id' => $_POST['focus_id'],
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings source ID',
                ));
            }

        } else {

            //Validate Source:
            $fetch_o = $this->E_model->fetch(array(
                'e__id' => $_POST['focus_id'],
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if (count($fetch_o) < 1) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid followings source ID',
                ));
            }

        }



        //Set some variables:
        $_POST['x__type'] = intval($_POST['x__type']);
        $is_upwards = in_array($_POST['x__type'], $this->config->item('n___14686'));
        $_POST['e_existing_id'] = intval($_POST['e_existing_id']);
        $url_e = false;
        $adding_to_existing = (intval($_POST['e_existing_id']) > 0);

        //Are we adding an existing source?
        if ($adding_to_existing) {

            //Validate this existing source:
            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['e_existing_id'],
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
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
                $url_e = $this->E_model->parse_url($_POST['e_new_string'], ( $adding_to_idea ? $member_e['e__id'] /* Will Create if Not Found */ : 0 ));
                if (!$url_e['status']) {
                    return view_json($url_e);
                }

                //Add this source:
                $focus_e = $url_e['e_url'];

            } else {

                //Create:
                $added_e = $this->E_model->verify_create($_POST['e_new_string'], $member_e['e__id']);
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
        $e_already_linked = 0;

        if($adding_to_idea) {

            if($_POST['x__type']==6255){

                $e_already_linked = count($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__creator' => $focus_e['e__id'],
                    'x__left' => $fetch_o[0]['i__id'],
                )));

                //Add Reference:
                $ur2 = $this->X_model->mark_complete($fetch_o[0]['i__id'], $fetch_o[0], array(
                    'x__type' => 38000, //Suggested
                    'x__creator' => $focus_e['e__id'],
                    'x__up' => $member_e['e__id'], //TODO replace with x__creator for all discovery transactions
                ));

            } else {

                $e_already_linked = count($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__up' => $focus_e['e__id'],
                    'x__right' => $fetch_o[0]['i__id'],
                    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                )));

                //Add Reference if needed:
                if(!count($this->X_model->fetch(array(
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //Idea/Source Links Active
                    'x__up' => $focus_e['e__id'],
                    'x__right' => $fetch_o[0]['i__id'],
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))) {
                    $ur2 = $this->X_model->create(array(
                        'x__creator' => $member_e['e__id'],
                        'x__type' => 4983, //IDEA SOURCES
                        'x__up' => $focus_e['e__id'],
                        'x__right' => $fetch_o[0]['i__id'],
                    ));
                }

            }

        } else {

            //Add Up/Down Source:

            //Add transactions only if not previously added by the URL function:
            if ($is_upwards) {

                //Following
                $x__down = $fetch_o[0]['e__id'];
                $x__up = $focus_e['e__id'];
                $x__weight = 0; //Never sort following, only sort followers

            } else {

                //Followers
                $x__up = $fetch_o[0]['e__id'];
                $x__down = $focus_e['e__id'];
                $x__weight = 0;

            }


            $x__message = null;

            $e_already_linked = count($this->X_model->fetch(array(
                'x__down' => $x__down,
                'x__up' => $x__up,
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )));

            //Create transaction:
            $ur2 = $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 4230,
                'x__message' => $x__message,
                'x__down' => $x__down,
                'x__up' => $x__up,
                'x__weight' => $x__weight,
            ));

        }


        //Return source:
        return view_json(array(
            'status' => 1,
            'e_new_echo' => view_card_e($_POST['x__type'], array_merge($focus_e, $ur2), null),
            'e_already_linked' => $e_already_linked,
        ));

    }



    function e_toggle_superpower($superpower_e__id){

        //Toggles the advance session variable for the member on/off for logged-in members:
        $member_e = superpower_unlocked();
        $superpower_e__id = intval($superpower_e__id);
        $e___10957 = $this->config->item('e___10957');

        if(!$member_e){

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif(!in_array($superpower_e__id, $this->session->userdata('session_superpowers_unlocked'))){

            //Access not authorized:
            return view_json(array(
                'status' => 0,
                'message' => 'You have not yet unlocked the superpower of '.$e___10957[$superpower_e__id]['m__title'],
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
            'x__creator' => $member_e['e__id'],
            'x__type' => 5007, //TOGGLE SUPERPOWER
            'x__up' => $superpower_e__id,
            'x__message' => 'SUPERPOWER '.$toggled_setting, //To be used when member logs in again
        ));

        //Return to JS function:
        return view_json(array(
            'status' => 1,
            'message' => 'Success',
        ));
    }


    function edit_source()
    {
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['e__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        }


        //Log Modal View:
        $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
            'x__type' => 14576, //MODAL VIEWED
            'x__up' => 31912, //Edit Source
            'x__down' => $_POST['e__id'],
        ));


        //Any suggestions?
        $icon_suggestions = array();


        //Find Past Selected Icons for Source:
        $unique_covers = array();
        foreach($this->X_model->fetch(array(
            'x__down' => $_POST['e__id'],
            'x__type' => 10653, //Source Icon Update
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array('x__id' => 'DESC')) as $x) {
            $x__metadata = unserialize($x['x__metadata']);
            if(strlen($x__metadata['before'])){
                $cover = ( substr_count($x__metadata['before'], 'class="') ? one_two_explode('class="','"',$x__metadata['before']) : $x__metadata['before'] );
                if(strlen($cover) && !in_array($cover, $unique_covers)){
                    array_push($unique_covers, $cover);
                    array_push($icon_suggestions, array(
                        'cover_preview' => $cover,
                        'cover_apply' => $cover,
                        'new_title' => $x['x__time'],
                    ));
                }
            }
        }

        if($member_e['e__id']==$_POST['e__id']){
            //Show animal icons:
            foreach($this->config->item('e___12279') as $e__id => $m) {
                $cover = one_two_explode('class="','"',$m['m__cover']);
                array_push($icon_suggestions, array(
                    'cover_preview' => $cover,
                    'cover_apply' => $cover,
                    'new_title' => $cover.' ('.$m['m__title'].')',
                ));
            }
        }



        //SOURCE
        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if(count($es)){
            return view_json(array(
                'status' => 1,
                'card__title' => $es[0]['e__title'],
                'card__cover' => $es[0]['e__cover'],
                'icon_suggestions' => $icon_suggestions,
            ));
        }


        //Could not find:
        return view_json(array(
            'status' => 0,
            'message' => 'Could not find coin',
        ));
    }




    function source_edit_save()
    {
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['edit_e__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['card__title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Title',
            ));
        } elseif (!isset($_POST['card__cover'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Cover',
            ));
        }

        //Reset member session data if this data belongs to the logged-in member:
        if ($_POST['edit_e__id']==$member_e['e__id']) {

            $es = $this->E_model->fetch(array(
                'e__id' => intval($_POST['edit_e__id']),
            ));
            if(count($es)){
                //Re-activate Session with new data:
                $es[0]['e__title'] = trim($_POST['card__title']);
                $es[0]['e__cover'] = trim($_POST['card__cover']);
                $this->E_model->activate_session($es[0], true);
            }

        }

        //SOURCE
        $this->E_model->update($_POST['edit_e__id'], array(
            'e__title' => trim($_POST['card__title']),
            'e__cover' => trim($_POST['card__cover']),
        ), true, $member_e['e__id']);

        return view_json(array(
            'status' => 1,
        ));

    }





    function e_radio()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        if(isset($_POST['member__id_override']) && intval($_POST['member__id_override']) > 0){
            $member_e['e__id'] = intval($_POST['member__id_override']);
        } else {
            $member_e = superpower_unlocked();
            if (!$member_e) {
                return view_json(array(
                    'status' => 0,
                    'message' => view_unauthorized_message(),
                ));
            }
        }

        if (!isset($_POST['focus_id']) || intval($_POST['focus_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing followings source',
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


        //Dispatch Any Emails Necessary:
        foreach($this->E_model->scissor_e(31065, $_POST['selected_e__id']) as $e_item) {
            $this->X_model->send_dm($member_e['e__id'], $e_item['e__title'], $e_item['x__message'], array(), $e_item['e__id']);
        }

        if($_POST['focus_id']==28904){

            //Add special transaction to monitor unsubscribes:
            if(in_array($_POST['selected_e__id'], $this->config->item('n___29648'))){
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__type' => 29648, //Communication Downgraded
                    'x__up' => $_POST['focus_id'],
                    'x__down' => $_POST['selected_e__id'],
                ));
            }

            //Inform user if they Permanently Unsubscribed:
            if(in_array($_POST['selected_e__id'], $this->config->item('n___31057'))){
                //$e___31065 = $this->config->item('e___31065'); //NAVIGATION
                //$this->X_model->send_dm($member_e['e__id'], $e___31065[31066]['m__title'], $e___31065[31066]['m__message'], array(), 31066);
            }

        }

        if(!$_POST['enable_mulitiselect'] || $_POST['was_previously_selected']){
            //Since this is not a multi-select we want to delete all existing options...

            //Fetch all possible answers based on followings source:
            $query_filters = array(
                'x__up' => $_POST['focus_id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            );

            if($_POST['enable_mulitiselect'] && $_POST['was_previously_selected']){
                //Just delete this single item, not the other ones:
                $query_filters['x__down'] = $_POST['selected_e__id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->X_model->fetch($query_filters, array('x__down'), 0, 0) as $answer_e){
                array_push($possible_answers, $answer_e['e__id']);
            }

            //Delete selected options for this member:
            foreach($this->X_model->fetch(array(
                'x__up IN (' . join(',', $possible_answers) . ')' => null,
                'x__down' => $member_e['e__id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )) as $delete){
                //Should usually delete a single option:
                $this->X_model->update($delete['x__id'], array(
                    'x__access' => 6173, //Transaction Removed
                ), $member_e['e__id'], 6224 /* Member Account Updated */);
            }

        }

        //Add new option if not previously there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_previously_selected']){
            $this->X_model->create(array(
                'x__up' => $_POST['selected_e__id'],
                'x__down' => $member_e['e__id'],
                'x__creator' => $member_e['e__id'],
                'x__type' => 4230,
            ));
        }


        //Log Account Update transaction type:
        $_POST['account_update_function'] = 'e_radio'; //Add this variable to indicate which My Account function created this transaction
        $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
            'x__type' => 6224, //My Account updated
            'x__message' => 'My Account '.( $_POST['enable_mulitiselect'] ? 'Multi-Select Radio Field ' : 'Single-Select Radio Field ' ).( $_POST['was_previously_selected'] ? 'Deleted' : 'Created' ),
            'x__metadata' => $_POST,
            'x__up' => $_POST['focus_id'],
            'x__down' => $_POST['selected_e__id'],
        ));


        //Update Session:
        if(count($member_e) >= 2){
            $this->E_model->activate_session($member_e, true);
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated', //NOT shown in UI
        ));
    }






    function e_avatar()
    {

        $member_e = superpower_unlocked();

        if (!$member_e) {
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
        $icon_new_css = $_POST['type_css'].' '.$_POST['icon_css'];
        $validated = false;
        foreach($this->config->item('e___12279') as $e__id => $m) {
            if(substr_count($m['m__cover'], $icon_new_css)==1){
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
        $this->E_model->update($member_e['e__id'], array(
            'e__cover' => $new_avatar,
        ), true, $member_e['e__id']);


        //Update Session:
        $member_e['e__cover'] = $new_avatar;
        $this->E_model->activate_session($member_e, true);


        return view_json(array(
            'status' => 1,
            'message' => 'Name updated',
            'new_avatar' => $new_avatar,
        ));
    }



    function e_email()
    {

        $member_e = superpower_unlocked();

        if (!$member_e) {
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
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__up' => 3288, //Email
                'x__down !=' => $member_e['e__id'],
                'LOWER(x__message)' => $_POST['e_email'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Email already in-use by another account. Enter another Email or contact support for assistance.',
                ));
            }
        }


        //Fetch existing email:
        $u_accounts = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $member_e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__up' => 3288, //Email
        ));
        if (count($u_accounts) > 0) {

            if (strlen($_POST['e_email'])==0) {

                //Delete email:
                $this->X_model->update($u_accounts[0]['x__id'], array(
                    'x__access' => 6173, //Transaction Removed
                ), $member_e['e__id'], 6224 /* Member Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Email deleted',
                );

            } elseif ($u_accounts[0]['x__message'] != $_POST['e_email']) {

                //Update if not duplicate:
                $this->X_model->update($u_accounts[0]['x__id'], array(
                    'x__message' => $_POST['e_email'],
                ), $member_e['e__id'], 6224 /* Member Account Updated */);

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
                'x__creator' => $member_e['e__id'],
                'x__down' => $member_e['e__id'],
                'x__type' => 4230,
                'x__up' => 3288, //Email
                'x__message' => $_POST['e_email'],
            ), true);

            $this->E_model->activate_subscription($member_e['e__id']);

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
                'x__creator' => $member_e['e__id'],
                'x__type' => 6224, //My Account updated
                'x__message' => 'My Account '.$return['message']. ( strlen($_POST['e_email']) > 0 ? ': '.$_POST['e_email'] : ''),
                'x__metadata' => $_POST,
            ));
        }


        //Return results:
        return view_json($return);


    }






    function e_fullname()
    {

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['e_fullname']) || !strlen($_POST['e_fullname'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Full Name',
            ));
        }


        //Cleanup:
        $return = source_link_message(30198, $member_e['e__id'], trim($_POST['e_fullname']));


        //Return results:
        return view_json($return);


    }


    function e_phone()
    {

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['e_phone']) || (strlen($_POST['e_phone'])>0 && intval(preg_replace("/[^0-9]/", "", $_POST['e_phone'] ))<10000000)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Phone number',
            ));
        }



        if (strlen($_POST['e_phone']) > 0) {

            //Cleanup digits only:
            $_POST['e_phone'] = trim(preg_replace("/[^0-9]/", "", $_POST['e_phone'] ));

            //Check to make sure not duplicate:
            $duplicates = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__up' => 4783, //Phone
                'x__down !=' => $member_e['e__id'],
                'x__message' => $_POST['e_phone'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Phone already in-use by another account. Enter another phone or contact support for assistance.',
                ));
            }

        }


        //Fetch existing phone:
        $u_phones = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $member_e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__up' => 4783, //Phone
        ));
        if (count($u_phones) > 0) {

            if (strlen($_POST['e_phone'])==0) {

                //Delete phone:
                $this->X_model->update($u_phones[0]['x__id'], array(
                    'x__access' => 6173, //Transaction Removed
                ), $member_e['e__id'], 6224 /* Member Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone deleted',
                );

            } elseif ($u_phones[0]['x__message'] != $_POST['e_phone']) {

                //Update if not the same:
                $this->X_model->update($u_phones[0]['x__id'], array(
                    'x__message' => $_POST['e_phone'],
                ), $member_e['e__id'], 6224 /* Member Account Updated */);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Phone unchanged',
                );

            }

        } elseif (strlen($_POST['e_phone']) > 0) {

            //Add Phone:
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__down' => $member_e['e__id'],
                'x__type' => 4230,
                'x__up' => 4783, //Phone
                'x__message' => $_POST['e_phone'],
            ), true);

            $this->E_model->activate_subscription($member_e['e__id']);

            $return = array(
                'status' => 1,
                'message' => 'Phone added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Phone unchanged',
            );

        }


        if($return['status']){
            //Log Account Update transaction type:
            $_POST['account_update_function'] = 'e_phone'; //Add this variable to indicate which My Account function created this transaction
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 6224, //My Account updated
                'x__message' => 'My Account '.$return['message']. ( strlen($_POST['e_phone']) > 0 ? ': '.$_POST['e_phone'] : ''),
                'x__metadata' => $_POST,
            ));
        }


        //Return results:
        return view_json($return);


    }









    function contact_auth(){


        if (!isset($_POST['account_id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_code']) || !intval($_POST['input_code'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid code',
            ));
        } elseif (!isset($_POST['account_email_phone'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing account_email_phone',
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
        } elseif (!isset($_POST['new_username'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing account name',
            ));
        }

        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));

        //Validate member ID
        if($_POST['account_id'] > 0){

            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['account_id'],
            ));
            if(!count($es)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid account ID.',
                ));
            }

        } else {

            if(strlen($_POST['new_username'])<2){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Missing account name.',
                ));
            }


            $_POST['new_account_email'] = trim(strtolower($_POST['new_account_email']));
            if(!filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) && !filter_var($_POST['new_account_email'], FILTER_VALIDATE_EMAIL)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Enter your email to continue',
                ));
            }

        }



        //Auth Code:
        $is_authenticated = false;
        foreach($this->X_model->fetch(array(
            'x__type' => 32078, //Sign In Key
            'x__access' => 6175, //Still Pending
            'x__message' => $_POST['account_email_phone'],
        ), array(), 1, 0, array('x__id' => 'DESC')) as $sent_key){

            $x__metadata = unserialize($sent_key['x__metadata']);
            $session_key = $this->session->userdata('session_key');

            if(strlen($session_key) && $x__metadata['hash_code']==md5($session_key.$_POST['input_code'])){

                //Complete access code:
                $is_authenticated = $this->X_model->update($sent_key['x__id'], array(
                    'x__access' => 6176, //Published
                ), $_POST['account_id'], 32569); //Code Verified

            }

        }
        if(!$is_authenticated){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid code, try again.',
            ));
        }





        //Validate member ID
        if($_POST['account_id'] > 0){

            //Assign session & log transaction:
            $this->E_model->activate_session($es[0]);

        } else {

            //Add new account
            $_POST['account_email_phone'] =  trim(strtolower($_POST['account_email_phone']));
            $is_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);

            //Prep inputs & validate further:
            $member_result = $this->E_model->add_member($_POST['new_username'], ( $is_email ? $_POST['account_email_phone'] : $_POST['new_account_email'] ), ( !$is_email ? $_POST['account_email_phone'] : '' ));
            if (!$member_result['status']) {
                return view_json($member_result);
            }

            $es[0] = $member_result['e'];

        }


        if (intval($_POST['sign_i__id']) > 0) {

            $sign_url = '/x/x_start/'.$_POST['sign_i__id'];

        } elseif (isset($_POST['referrer_url']) && strlen(urldecode($_POST['referrer_url'])) > 1) {

            $sign_url = urldecode($_POST['referrer_url']);

        } else {
            $sign_url = '/@'.$es[0]['e__id'];
        }

        return view_json(array(
            'status' => 1,
            'sign_url' => $sign_url,
        ));

    }



    function e_toggle_e(){

        $member_e = superpower_unlocked(28714);
        if(!$member_e){

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(28714),
            ));

        } elseif(!isset($_POST['x__creator']) || !isset($_POST['e__id']) || !isset($_POST['i__id']) || !isset($_POST['x__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variable',
            ));

        } else {

            $already_added = $this->X_model->fetch(array(
                'x__up' => $_POST['e__id'],
                'x__down' => $_POST['x__creator'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array('x__up'));

            if(count($already_added)){

                if(intval($_POST['input_modal']) && trim($_POST['modal_value'])!=$already_added[0]['x__message']){

                    //Updating current value:
                    $this->X_model->update($already_added[0]['x__id'], array(
                        'x__message' => trim($_POST['modal_value']),
                    ));
                    return view_json(array(
                        'status' => 1,
                        'message' => ( intval($_POST['input_modal']) && strlen($_POST['modal_value']) ? trim($_POST['modal_value']) : $already_added[0]['e__cover'] ),
                    ));

                } else {

                    //Already exists, let's remove:
                    $this->X_model->update($already_added[0]['x__id'], array(
                        'x__access' => 6173, //Transaction Deleted
                    ), $member_e['e__id'], 10673 /* Member Transaction Unpublished */);

                    return view_json(array(
                        'status' => 1,
                        'message' => ' ',
                    ));

                }

            } else {

                foreach($this->E_model->fetch(array(
                    'e__id' => $_POST['e__id'],
                )) as $e){

                    //Does not exist, Add:
                    $this->X_model->create(array(
                        'x__up' => $_POST['e__id'],
                        'x__down' => $_POST['x__creator'],
                        'x__creator' => $member_e['e__id'],
                        'x__message' => ( intval($_POST['input_modal']) && strlen($_POST['modal_value']) ? trim($_POST['modal_value']) : null ),
                        'x__type' => 4230,
                    ));

                    return view_json(array(
                        'status' => 1,
                        'message' => ( intval($_POST['input_modal']) && strlen($_POST['modal_value']) ? trim($_POST['modal_value']) : $e['e__cover'] ),
                    ));

                }



            }

        }

    }


    function contact_search(){

        //Cleanup input email:
        $e___11035 = $this->config->item('e___11035'); //NAVIGATION
        $_POST['account_email_phone'] = trim(strtolower($_POST['account_email_phone']));
        $valid_email = filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL);
        if(!$valid_email && strlen($_POST['account_email_phone'])>=10){
            $_POST['account_email_phone'] = preg_replace('/[^0-9]+/', '', $_POST['account_email_phone']);
        }
        $possible_phone = !$valid_email && strlen($_POST['account_email_phone'])>=10;

        if (!isset($_POST['account_email_phone']) || (!$valid_email && !$possible_phone)) {
            return view_json(array(
                'status' => 0,
                'message' => '['.$_POST['account_email_phone'].'] is an Invalid Email Address or Phone Number',
            ));
        } elseif (!isset($_POST['sign_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing data ID',
            ));
        }


        if(intval($_POST['sign_i__id']) > 0){
            //Fetch the idea:
            $referrer_i = $this->I_model->fetch(array(
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'i__id' => $_POST['sign_i__id'],
            ));
        } else {
            $referrer_i = array();
        }


        //Search for email/phone to see if it exists...
        $u_accounts = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__message' => $_POST['account_email_phone'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__up IN (' . join(',', $this->config->item('n___32078')) . ')' => null, //Phone or Email
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down'));



        $x__creator = ( count($u_accounts) ? $u_accounts[0]['e__id'] : 0 );

        //Send Sign In Key
        $passcode = rand(1000,9999);
        $session_key = generateRandomString(55);

        //Append to session:
        $session_data = $this->session->all_userdata();
        $session_data['session_key'] = $session_key;
        $this->session->set_userdata($session_data);

        $plain_message = $passcode.' is your '.$e___11035[32078]['m__title'].' for '.get_domain('m__title');

        if($valid_email) {

            //Email:
            send_email(array($_POST['account_email_phone']), $plain_message, $plain_message.'.', 0, array(), 0, 0, false);

            //Log new key:
            $this->X_model->create(array(
                'x__creator' => $x__creator, //Member making request
                'x__left' => intval($_POST['sign_i__id']),
                'x__type' => 32078, //Sign In Key
                'x__access' => 6175, //Pending until used (if used)
                'x__message' => $_POST['account_email_phone'],
                'x__metadata' => array(
                    'hash_code' => md5($session_key.$passcode),
                ),
            ));

        } elseif($possible_phone) {

            //SMS:
            send_sms($_POST['account_email_phone'], $plain_message, 0, array(), 0, 0, false);

            //Log new key:
            $this->X_model->create(array(
                'x__creator' => $x__creator, //Member making request
                'x__left' => intval($_POST['sign_i__id']),
                'x__type' => 32078, //Sign In Key
                'x__access' => 6175, //Pending until used (if used)
                'x__message' => $_POST['account_email_phone'],
                'x__metadata' => array(
                    'hash_code' => md5($session_key.$passcode),
                ),
            ));

        }

        return view_json(array(
            'status' => 1,
            'account_id' => $x__creator,
            'valid_email' => ( $valid_email ? 1 : 0 ),
            'account_preview' => ( $x__creator ? '<span class="icon-block">'.view_cover($u_accounts[0]['e__cover'], true). '</span>'.$u_accounts[0]['e__title'] : '' ),
            'clean_contact' => $_POST['account_email_phone'],
        ));

    }



}