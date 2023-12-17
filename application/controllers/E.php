<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        auto_login();

    }

    function view_body_e(){
        //Authenticate Member:
        if (!isset($_POST['e__id']) || intval($_POST['e__id']) < 1 || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo view_body_e($_POST['x__type'], $_POST['counter'], $_POST['e__id']);
        }
    }



    //Lists sources
    function e_layout($e__handle)
    {

        //Validate source ID and fetch data:
        $es = $this->E_model->fetch(array(
            'LOWER(e__handle)' => strtolower($e__handle),
        ));
        if (count($es) < 1) {

            //See if we need to lookup the ID:
            if(is_numeric($e__handle)){
                //Maybe its an ID?
                foreach ($this->E_model->fetch(array(
                    'e__id' => $e__handle,
                )) as $e_redirect){
                    return redirect_message('/@'.$e_redirect['e__handle']);
                }
            }

            return redirect_message(home_url());
        }

        $member_e = superpower_unlocked();
        //Make sure not a private source:
        if(!in_array($es[0]['e__access'], $this->config->item('n___33240') /* PUBLIC/GUEST Access */) && !write_access_e($es[0]['e__handle'])){
            $member_e = superpower_unlocked(13422, true);
        }

        $e___14874 = $this->config->item('e___14874'); //Mench Cards

        //Load views:
        $this->load->view('header', array(
            'title' => $es[0]['e__title'].' @'.$es[0]['e__handle'].' | '.$e___14874[12274]['m__title'],
        ));
        $this->load->view('e_layout', array(
            'e' => $es[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }



    function e_load_cover(){

        if (!isset($_POST['e__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {

            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';

        } else {

            $ui = '';
            $listed_items = 0;

            if($_POST['x__type']==11030 || $_POST['x__type']==12274){

                //SOURCES
                $current_e__handle = view_valid_handle_e($_POST['first_segment']);
                $e___6177 = $this->config->item('e___6177'); //Source Status
                $e___4593 = $this->config->item('e___4593'); //Transaction Types

                foreach(view_e_covers($_POST['x__type'], $_POST['e__id'], 1, false) as $e_e) {
                    if(isset($e_e['e__id'])){
                        $ui .= view_card('/@'.$e_e['e__handle'], $e_e['e__handle']==$current_e__handle, $e_e['x__type'], $e_e['e__access'], view_cover($e_e['e__cover'], true), $e_e['e__title'], $e_e['x__message']);
                        $listed_items++;
                    }
                }

            } elseif($_POST['x__type']==12273 || $_POST['x__type']==6255){

                //IDEAS
                $current_i__hashtag = ( substr($_POST['first_segment'], 0, 1)=='~' ? substr($_POST['first_segment'], 1) : false );
                $e___31004 = $this->config->item('e___31004'); //Idea Status
                $e___4737 = $this->config->item('e___4737'); //Idea Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types

                foreach(view_e_covers($_POST['x__type'], $_POST['e__id'], 1, false) as $next_i) {
                    if(isset($next_i['i__id'])){
                        $ui .= view_card('/~'.$next_i['i__hashtag'], $next_i['i__hashtag']==$current_i__hashtag, $next_i['x__type'], null, ( in_array($next_i['i__type'], $this->config->item('n___32172')) ? $e___4737[$next_i['i__type']]['m__cover'] : '' ), view_i_title($next_i), $next_i['x__message']);
                        $listed_items++;
                    }
                }

            }

            if($listed_items < $_POST['counter']){
                //We have more to show:
                foreach($this->E_model->fetch(array(
                    'e__id' => $_GET['e__id'],
                )) as $e_this){
                    $ui .= view_more('/@'.$e_this['e__handle'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
                }
            }

            echo $ui;
        }
    }

    function sort_e_save()
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





    function e_delete(){

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
            'new_e__handle' => $focus_e['e__handle'],
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

        $adding_to_i = ($_POST['focus_card']==12273);

        if($adding_to_i){

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

        if(!intval($_POST['e_existing_id']) && view_valid_handle_e($_POST['e_new_string'])){
            foreach($this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower(substr($_POST['e_new_string'], 1)),
            )) as $e){
                $_POST['e_existing_id'] = $e['e__id'];
            }
        }
        $adding_to_existing = ( intval($_POST['e_existing_id']) > 0 );

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
                    'message' => 'Source @'.$_POST['e_existing_id'].' is not active',
                ));
            }

            //All good, assign:
            $focus_e = $es[0];

        } else {

            //We are creating a new source:
            $added_e = $this->E_model->verify_create($_POST['e_new_string'], $member_e['e__id']);
            if(!$added_e['status']){
                //We had an error, return it:
                return view_json($added_e);
            } else {
                //Assign new source:
                $focus_e = $added_e['new_e'];
            }

        }


        //We need to check to ensure this is not a duplicate transaction if adding an existing source:
        $ur2 = array();
        $e_already_linked = 0;

        if($adding_to_i) {

            $e_already_linked = count($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__up' => $focus_e['e__id'],
                'x__right' => $fetch_o[0]['i__id'],
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )));

            //Add Reference:
            $ur2 = $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 4983, //IDEA SOURCES
                'x__up' => $focus_e['e__id'],
                'x__right' => $fetch_o[0]['i__id'],
            ));

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


    function save_load_e()
    {

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['e__id']) || !isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        }

        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['e__id'],
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if (!count($es)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Source is no longer active',
            ));
        } elseif (!write_access_e($es[0]['e__handle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this Source',
            ));
        }

        //Fetch dynamic data based on idea type:
        $return_inputs = array();
        $return_unique_inputs = array();
        $return_radios = '';
        $input_pointer = 0;

        //Fetch Source Templates, if any:
        foreach($this->X_model->fetch(array(
            'x__up IN (' . join(',', $this->config->item('n___42178')) . ')' => null, //SOURCE TEMPLATE GROUPS
            'x__down' => $es[0]['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array('x__up')) as $e_group) {

            //Find template for this group:
            foreach($this->X_model->fetch(array(
                'x__down' => $e_group['e__id'],
                'x__up IN (' . join(',', $this->config->item('n___42145')) . ')' => null, //Dynamic Input Templates
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )) as $e_template) {

                //Load template:
                foreach($this->config->item('e___'.$e_template['x__up']) as $dynamic_e__id => $m) {

                    //Make sure it's a dynamic input field:
                    if(!in_array($dynamic_e__id, $this->config->item('n___42179'))){
                        continue;
                    }

                    //Let's first determine the data type:
                    $data_types = array_intersect($m['m__following'], $this->config->item('n___4592'));

                    if(count($data_types)!=1){
                        //This is strange, we are expecting 1 match only... report this:
                        $this->X_model->create(array(
                            'x__type' => 4246, //Platform Bug Reports
                            'x__creator' => $member_e['e__id'],
                            'x__up' => 31912, //Edit Source
                            'x__down' => $dynamic_e__id,
                            'x__reference' => $_POST['x__id'],
                            'x__message' => 'Found '.count($data_types).' Data Types (@'.$es[0]['e__id'].') (Expecting exactly 1) for @'.$dynamic_e__id.': Check @4592 to see what is wrong...',
                        ));
                        continue; //Go to the next dynamic data type...

                    } elseif ($input_pointer >= view_memory(6404, 42206)) {
                        //Monitor if we ever reach the maximum:
                        $this->X_model->create(array(
                            'x__type' => 4246, //Platform Bug Reports
                            'x__creator' => $member_e['e__id'],
                            'x__up' => 42179, //Dynamic Input Fields
                            'x__down' => $dynamic_e__id,
                            'x__right' => $_POST['e__id'],
                            'x__reference' => $_POST['x__id'],
                            'x__metadata' => $_POST,
                            'x__message' => 'Dynamic Fields Reach their maximum limit of ' . view_memory(6404, 42206) . '  which may require field expansion...',
                        ));
                    }

                    //We found 1 match as expected:
                    $input_pointer++;
                    foreach($data_types as $data_type_this){
                        $data_type = $data_type_this;
                        break;
                    }
                    $is_required = in_array($data_type , $this->config->item('n___42174')); //Required Settings

                    if(in_array($data_type, $this->config->item('n___42188'))){

                        //Single or Multiple Choice:
                        if(!in_array($dynamic_e__id, $return_unique_inputs)){
                            array_push($return_unique_inputs, $dynamic_e__id);
                            $return_radios .= view_radio_e($dynamic_e__id, $es[0]['e__id'], 0);
                        }

                    } else {

                        //Fetch the current value:
                        $d__value = '';
                        foreach($this->X_model->fetch(array(
                            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__down' => $es[0]['e__id'],
                            'x__up' => $dynamic_e__id,
                        )) as $curr_val){
                            $d__value = $curr_val['x__message'];
                            break;
                        }

                        //Add to main array:
                        $this_data_type = $this->config->item('e___'.$data_type);

                        if(!in_array($dynamic_e__id, $return_unique_inputs)){
                            array_push($return_unique_inputs, $dynamic_e__id);
                            array_push($return_inputs, array(
                                'd__id' => $dynamic_e__id,
                                'd__title' => '<span class="icon-block-xs">'.$m['m__cover'].'</span>'.$m['m__title'].( $is_required ? ' <b title="Required Field" style="color:#FF0000;">*</b>' : '' ),
                                'd__value' => $d__value,
                                'd__placeholder' => $this_data_type[$dynamic_e__id]['m__message'],
                            ));
                        }
                    }
                }
            }
        }


        //Find Past Selected Icons for Source:
        $return_covers = array();
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
                    array_push($return_covers, array(
                        'cover_preview' => $cover,
                        'cover_apply' => $cover,
                        'new_title' => $x['x__time'],
                    ));
                }
            }
        }
        if($member_e['e__id']==$_POST['e__id']){
            //Also append animal icons for user cover selection:
            foreach($this->config->item('e___12279') as $e__id => $m) {
                $cover = one_two_explode('class="','"',$m['m__cover']);
                array_push($return_covers, array(
                    'cover_preview' => $cover,
                    'cover_apply' => $cover,
                    'new_title' => $cover.' ('.$m['m__title'].')',
                ));
            }
        }

        $return_array = array(
            'status' => 1,
            'return_inputs' => $return_inputs,
            'return_radios' => $return_radios,
            'return_covers' => $return_covers, //Past covers for quick editing
        );

        //Log Modal View:
        $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
            'x__type' => 14576, //MODAL VIEWED
            'x__up' => 31912, //Edit Source
            'x__down' => $es[0]['e__id'],
            'x__reference' => $_POST['x__id'],
            'x__metadata' => $return_array,
        ));

        //Return everything we found:
        return view_json($return_array);

    }




    function save_e()
    {

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['save_e__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['save_e__title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Title',
            ));
        } elseif (!isset($_POST['save_e__handle'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Handle',
            ));
        } elseif (!isset($_POST['save_e__cover'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Source Cover',
            ));
        } elseif(!isset($_POST['save_x__id']) || !isset($_POST['save_x__message'])){
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Transaction Data',
            ));
        }



        $es = $this->E_model->fetch(array(
            'e__id' => $_POST['save_e__id'],
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if(!count($es)){
            return view_json(array(
                'status' => 0,
                'message' => 'Source Not Active',
            ));
        }



        //Validate Dynamic Inputs:
        $input_pointer = 0;
        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields
        //Fetch Source Templates, if any:
        foreach($this->X_model->fetch(array(
            'x__up IN (' . join(',', $this->config->item('n___42178')) . ')' => null, //SOURCE TEMPLATE GROUPS
            'x__down' => $es[0]['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array('x__up')) as $e_group) {

            //Find template for this group:
            foreach ($this->X_model->fetch(array(
                'x__down' => $e_group['e__id'],
                'x__up IN (' . join(',', $this->config->item('n___42145')) . ')' => null, //Dynamic Input Templates
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )) as $e_template) {

                //Load template:
                foreach ($this->config->item('e___' . $e_template['x__up']) as $dynamic_e__id => $m) {

                    //Make sure it's a dynamic input field:
                    if (!in_array($dynamic_e__id, $this->config->item('n___42179'))) {
                        continue;
                    }

                    //Let's first determine the data type:
                    $data_types = array_intersect($e___42179[$dynamic_e__id]['m__following'], $this->config->item('n___4592'));

                    if (count($data_types) != 1) {
                        //This is strange, we are expecting 1 match only... report this:
                        $this->X_model->create(array(
                            'x__type' => 4246, //Platform Bug Reports
                            'x__creator' => $member_e['e__id'],
                            'x__up' => 42179, //Dynamic Input Fields
                            'x__down' => $dynamic_e__id,
                            'x__right' => $_POST['save_e__id'],
                            'x__reference' => $_POST['save_x__id'],
                            'x__message' => 'Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_e__id . ': Check @4592 to see what is wrong...',
                        ));
                        continue; //Go to the next dynamic data type...
                    }

                    //We found 1 match as expected:
                    $input_pointer++; //Starts at 1
                    foreach($data_types as $data_type_this){
                        $data_type = $data_type_this;
                        break;
                    }
                    $is_required = in_array($data_type, $this->config->item('n___42174')); //Required Settings
                    if(!isset($_POST['save_dynamic_' . $input_pointer])){
                        $_POST['save_dynamic_' . $input_pointer] = '';
                    }

                    //Validate input if required or provided:
                    if ($is_required || strlen($_POST['save_dynamic_' . $input_pointer])) {
                        $valid_data_type = valid_data_type($data_types, $_POST['save_dynamic_' . $input_pointer], $e___42179[$dynamic_e__id]['m__title']);
                        if (!$valid_data_type['status']) {
                            //We had an error:
                            return view_json($valid_data_type);
                        }
                    }

                    //Yes value is valid!

                    //Fetch the current value:
                    $values = $this->X_model->fetch(array(
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__up' => $dynamic_e__id,
                        'x__down' => $es[0]['e__id'],
                    ));

                    //Update if needed:
                    if (count($values) && !strlen($_POST['save_dynamic_' . $input_pointer])) {

                        //Remove Link:
                        $this->X_model->update($values[0]['x__id'], array(
                            'x__access' => 6173, //Transaction Removed
                        ), $member_e['e__id'], 42175 /* Dynamic Link Content Removed */);

                    } elseif (!count($values)) {

                        //Create Link:
                        $this->X_model->create(array(
                            'x__creator' => $member_e['e__id'],
                            'x__type' => 4230,
                            'x__up' => $dynamic_e__id,
                            'x__down' => $es[0]['e__id'],
                            'x__message' => $_POST['save_dynamic_' . $input_pointer],
                            'x__weight' => number_x__weight($_POST['save_dynamic_' . $input_pointer]),
                        ));

                    } elseif ($values[0]['x__message'] != $_POST['save_dynamic_' . $input_pointer]) {

                        //Update Link:
                        $this->X_model->update($values[0]['x__id'], array(
                            'x__message' => $_POST['save_dynamic_' . $input_pointer],
                            'x__weight' => number_x__weight($_POST['save_dynamic_' . $input_pointer]),
                        ), $member_e['e__id'], 42176 /* Dynamic Link Content Updated */);

                    }
                }
            }
        }



        //Validate Source Handle & save if needed:
        if($es[0]['e__handle'] !== trim($_POST['save_e__handle'])){

            $validate_handle = validate_handle(trim($_POST['save_e__handle']), null, $es[0]['e__id']);
            if(!$validate_handle['status']){
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_handle['message'],
                ));
            }

            //Update Handles everywhere they are referenced:
            foreach ($this->X_model->fetch(array(
                'x__up' => $es[0]['e__id'],
                'x__type' => 31835, //Source Mention
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )) as $ref) {
                $this->I_model->update($ref['x__right'], array(
                    'i__message' => preg_replace('/\b@'.$es[0]['e__handle'].'\b/', '@'.trim($_POST['save_e__handle']), $ref['x__message']),
                ), false, $member_e['e__id']);
            }
            $es[0]['e__handle'] = trim($_POST['save_e__handle']);

        }

        //Validate Source Title & save if needed:
        $validate_e__title = validate_e__title($_POST['save_e__title']);
        if($es[0]['e__title'] != trim($_POST['save_e__title'])){
            if(!$validate_e__title['status']){
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_e__title['message'],
                ));
            }
            $es[0]['e__title'] = $validate_e__title['e__title_clean'];
        }

        //Save Source Cover if needed:
        if($es[0]['e__cover'] != trim($_POST['save_e__cover'])){
            //TODO validate e__cover?
            $es[0]['e__cover'] = trim($_POST['save_e__cover']);
        }

        //Update:
        $this->E_model->update($es[0]['e__id'], array(
            'e__title' => $validate_e__title['e__title_clean'],
            'e__cover' => trim($_POST['save_e__cover']),
            'LOWER(e__handle)' => strtolower(trim($_POST['save_e__handle'])),
        ), true, $member_e['e__id']);


        //Do we have a link reference message that need to be saved?
        if($_POST['save_x__id']>0){

            //Fetch transaction:
            foreach($this->X_model->fetch(array(
                'x__id' => $_POST['save_x__id'],
            )) as $this_x){

                $es[0] = array_merge($es[0], $this_x);

                if($this_x['x__message'] != trim($_POST['save_x__message'])){

                    $this->X_model->update($this_x['x__id'], array(
                        'x__message' => trim($_POST['save_x__message']),
                        'x__weight' => number_x__weight(trim($_POST['save_x__message'])),
                    ), $member_e['e__id'], 42171);

                }
            }
        }


        //Reset member session data if this data belongs to the logged-in member:
        if ($_POST['save_e__id']==$member_e['e__id']) {
            $this->E_model->activate_session($es[0], true);
        }


        return view_json(array(
            'status' => 1,
            'message' => 'updated',
        ));


    }





    function e_radio()
    {
        /*
         *
         * Saves the radio selection of some account fields
         *
         * */

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['focus_id']) || intval($_POST['focus_id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing followings source',
            ));
        } elseif (!isset($_POST['selected_e__id']) || intval($_POST['selected_e__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing selected source',
            ));
        } elseif (!isset($_POST['down_e__id']) || !isset($_POST['right_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Down/Right Element',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_previously_selected'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }


        if($_POST['down_e__id'] > 0){

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

            //Delete previously selected options:
            if($_POST['down_e__id']){
                $delete_query = $this->X_model->fetch(array(
                    'x__up IN (' . join(',', $possible_answers) . ')' => null,
                    'x__down' => $_POST['down_e__id'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ));
            } elseif($_POST['right_i__id']){
                $delete_query = $this->X_model->fetch(array(
                    'x__up IN (' . join(',', $possible_answers) . ')' => null,
                    'x__right' => $_POST['right_i__id'],
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ));
            }

            foreach($delete_query as $delete){
                //Should usually delete a single option:
                $this->X_model->update($delete['x__id'], array(
                    'x__access' => 6173, //Transaction Removed
                ), $member_e['e__id'], 6224 /* Member Account Updated */);
            }

        }

        //Add new option if not previously there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_previously_selected']){
            if($_POST['down_e__id']){
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__up' => $_POST['selected_e__id'],
                    'x__type' => 4230,
                    'x__down' => $_POST['down_e__id'],
                ));
            } elseif($_POST['right_i__id']){
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__up' => $_POST['selected_e__id'],
                    'x__type' => 4983, //IDEA SOURCES
                    'x__right' => $_POST['right_i__id'],
                ));
            }
        }


        //Update Session:
        if($_POST['down_e__id'] && count($member_e) >= 2){
            $this->E_model->activate_session($member_e, true);
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Updated',
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


        //Set default sign in URL:
        $sign_url = '/@'.$es[0]['e__handle'];

        //See if we can find a better one:
        if (intval($_POST['sign_i__id']) > 0) {
            foreach($this->I_model->fetch(array(
                'i__id' => $_POST['sign_i__id'],
            )) as $i){
                $sign_url = '/x/x_start/'.$i['i__hashtag'];
            }
        } elseif (isset($_POST['referrer_url']) && strlen(urldecode($_POST['referrer_url'])) > 1) {
            $sign_url = urldecode($_POST['referrer_url']);
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
        $x__creator = 0;
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__message' => $_POST['account_email_phone'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__up' => ( filter_var($_POST['account_email_phone'], FILTER_VALIDATE_EMAIL) ? 3288 : 4783 ), //Email / Phone
            'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__down')) as $map_e){
            $u = $map_e;
            $x__creator = $map_e['e__id'];
            break;
        }

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
            send_email(array($_POST['account_email_phone']), $plain_message, $plain_message.'.', $x__creator, array(), 0, 0, false);


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
            'account_preview' => ( $x__creator ? '<span class="icon-block">'.view_cover($u['e__cover'], true). '</span>'.$u['e__title'] : '' ),
            'clean_contact' => $_POST['account_email_phone'],
        ));

    }



}