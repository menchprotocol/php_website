<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class I extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        auto_login();

    }




    function i_copy(){

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
                'message' => 'Invalid Following Source',
            ));
        } elseif (!isset($_POST['do_recursive'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing template parameter',
            ));
        }

        return view_json($this->I_model->recursive_clone(intval($_POST['i__id']), intval($_POST['do_recursive']), $member_e['e__id']));

    }


    function i_layout($i__hashtag){

        //Validate/fetch Idea:
        $is = $this->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($i__hashtag),
        ));
        if ( count($is) < 1) {
            return redirect_message(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>IDEA #' . $i__hashtag . ' Not Found</div>');
        }

        $member_e = superpower_unlocked(10939); //Idea Pen?
        if(!$member_e){
            if(in_array($is[0]['i__access'], $this->config->item('n___31871'))){
                return redirect_message('/'.$i__hashtag);
            } else {
                return redirect_message(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>IDEA #' . $i__hashtag . ' is not published yet.</div>');
            }
        }

        //Import Discoveries?
        $flash_message = '';
        if(isset($_GET['e__handle'])){
            foreach($this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $e_append){
                $completed = 0;
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__left' => $is[0]['i__id'],
                ), array(), 0) as $x){
                    if(!count($this->X_model->fetch(array(
                        'x__up' => $e_append['e__id'],
                        'x__down' => $x['x__creator'],
                        'x__message' => $x['x__message'],
                        'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        //Add source link:
                        $completed++;
                        $this->X_model->create(array(
                            'x__creator' => ($member_e ? $member_e['e__id'] : $x['x__creator']),
                            'x__up' => $e_append['e__id'],
                            'x__down' => $x['x__creator'],
                            'x__message' => $x['x__message'],
                            'x__type' => 4230,
                        ));
                    }
                }

                $flash_message = '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> '.$completed.' sources who played this idea added to @'.$e_append['e__handle'].'</div>';
            }
        }

        $e___14874 = $this->config->item('e___14874'); //Mench Cards

        //Load views:
        $this->load->view('header', array(
            'title' => view_i_title($is[0], true).' | '.$e___14874[12273]['m__title'],
            'flash_message' => $flash_message,
        ));
        $this->load->view('i_layout', array(
            'focus_i' => $is[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }




    function i__add()
    {

        /*
         *
         * Either creates a IDEA transaction between focus_id & link_i__id
         * OR will create a new idea with outcome i__message and then transaction it
         * to focus_id (In this case link_i__id=0)
         *
         * */

        //Authenticate Member:
        $member_e = superpower_unlocked(10939);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['x__type']) || !isset($_POST['focus_id']) || !isset($_POST['focus_card'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core Variables',
            ));
        } elseif (!isset($_POST['new_i__message']) || !isset($_POST['link_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Follower Idea ID',
            ));
        }

        $validate_i__message = validate_i__message($_POST['new_i__message']);
        if(!$validate_i__message['status']){
            //We had an error, return it:
            return view_json($validate_i__message);
        }


        if(!$_POST['link_i__id'] && view_valid_handle_i($_POST['new_i__message'])){
            foreach($this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower(view_valid_handle_i($_POST['new_i__message'])),
            )) as $i){
                $_POST['link_i__id'] = $i['i__id'];
            }
        }

        if($_POST['link_i__id'] > 0){
            //Fetch transaction idea to determine idea type:
            $x_i = $this->I_model->fetch(array(
                'i__id' => intval($_POST['link_i__id']),
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ));
            if(!count($x_i)){
                //validate Idea:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea #'.$_POST['link_i__id'].' is not active.',
                ));
            }
        }

        //All seems good, go ahead and try to create/link the Idea:
        return view_json($this->I_model->create_or_link($_POST['focus_card'], $_POST['x__type'], trim($_POST['new_i__message']), $member_e['e__id'], $_POST['focus_id'], $_POST['link_i__id']));

    }

    function view_body_i(){
        //Authenticate Member:
        if (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1 || !isset($_POST['counter']) || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo view_body_i($_POST['x__type'], $_POST['counter'], $_POST['i__id']);
        }
    }

    function i_load_cover(){

        if (!isset($_POST['i__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {

            $ui = '';
            $listed_items = 0;
            if(in_array($_POST['x__type'], $this->config->item('n___42261')) || in_array($_POST['x__type'], $this->config->item('n___42284'))){

                //SOURCES
                $e___6177 = $this->config->item('e___6177'); //Source Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                $current_e__handle = view_valid_handle_e($_POST['first_segment']);
                foreach(view_i_covers($_POST['x__type'], $_POST['i__id'], 1, false) as $e_e) {
                    if(isset($e_e['e__id'])){
                        $ui .= view_card('/@'.$e_e['e__handle'], $current_e__handle && $e_e['e__handle']==$current_e__handle, $e_e['x__type'], $e_e['e__access'], view_cover($e_e['e__cover'], true), $e_e['e__title'], $e_e['x__message']);
                        $listed_items++;
                    }
                }

            } elseif(in_array($_POST['x__type'], $this->config->item('n___11020'))){

                //IDEAS
                $e___4737 = $this->config->item('e___4737'); //Idea Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                $current_i__hashtag = ( substr($_POST['first_segment'], 0, 1)=='~' ? substr($_POST['first_segment'], 1) : false );

                foreach(view_i_covers($_POST['x__type'], $_POST['i__id'], 1, false) as $next_i) {
                    if(isset($next_i['i__id'])){
                        $ui .= view_card('/~'.$next_i['i__hashtag'], $next_i['i__hashtag']==$current_i__hashtag, $next_i['x__type'], null, ( in_array($next_i['i__type'], $this->config->item('n___32172')) ? $e___4737[$next_i['i__type']]['m__cover'] : '' ), view_i_title($next_i), $next_i['x__message']);
                        $listed_items++;
                    }
                }

            }

            if($listed_items < $_POST['counter']){
                //We have more to show:
                foreach($this->I_model->fetch(array(
                    'i__id' => $_POST['i__id'],
                )) as $i){
                    $ui .= view_more('/~'.$i['i__hashtag'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
                }
            }

            echo $ui;
        }
    }


    function editor_load_i()
    {

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || !isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Core IDs',
            ));
        } elseif (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea is no longer active',
            ));
        } elseif (!write_access_i($is[0]['i__hashtag'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to edit this idea',
            ));
        }

        //Fetch dynamic data based on idea type:
        $return_inputs = array();
        $return_radios = '';


        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields
        foreach(array_intersect($this->config->item('n___'.$is[0]['i__type']), $this->config->item('n___42179')) as $dynamic_e__id){

            //Let's first determine the data type:
            $data_types = array_intersect($e___42179[$dynamic_e__id]['m__following'], $this->config->item('n___4592'));

            if(count($data_types)!=1) {
                //This is strange, we are expecting 1 match only... report this:
                $this->X_model->create(array(
                    'x__type' => 4246, //Platform Bug Reports
                    'x__creator' => $member_e['e__id'],
                    'x__up' => 42179, //Dynamic Input Fields
                    'x__down' => $dynamic_e__id,
                    'x__right' => $is[0]['i__id'],
                    'x__reference' => $_POST['x__id'],
                    'x__message' => 'Found ' . count($data_types) . ' Data Types (Expecting exactly 1) for @' . $dynamic_e__id . ': Check @4592 to see what is wrong...',
                ));
                continue; //Go to the next dynamic data type...
            }

            //We found 1 match as expected:
            foreach($data_types as $data_type_this){
                $data_type = $data_type_this;
                break;
            }
            $is_required = in_array($dynamic_e__id, $this->config->item('n___42174')); //Required Settings

            if(in_array($data_type, $this->config->item('n___42188'))){

                //Single or Multiple Choice:
                $return_radios .= view_radio_e($dynamic_e__id, 0, $is[0]['i__id']);

            } else {

                //Fetch the current value:
                $d__value = '';
                foreach($this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
                    'x__right' => $is[0]['i__id'],
                    'x__up' => $dynamic_e__id,
                )) as $curr_val){
                    $d__value = $curr_val['x__message'];
                    break;
                }

                //Add to main array:
                $this_data_type = $this->config->item('e___'.$data_type);

                array_push($return_inputs, array(
                    'd__id' => $dynamic_e__id,
                    'd__title' => '<span class="icon-block-xs">'.$e___42179[$dynamic_e__id]['m__cover'].'</span>'.$e___42179[$dynamic_e__id]['m__title'].( $is_required ? ' <b title="Required Field" style="color:#FF0000;">*</b>' : '' ),
                    'd__value' => $d__value,
                    'd__type' => html_input_type($data_type),
                    'd__placeholder' => $this_data_type[$dynamic_e__id]['m__message'],
                ));

            }

        }

        $return_array = array(
            'status' => 1,
            'return_inputs' => $return_inputs,
            'return_radios' => $return_radios,
        );

        //Log Modal View:
        $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
            'x__type' => 14576, //MODAL VIEWED
            'x__up' => 31911, //Edit Idea
            'x__right' => $is[0]['i__id'],
            'x__reference' => $_POST['x__id'],
            'x__metadata' => $return_array,
        ));

        //Return everything we found:
        return view_json($return_array);

    }




    function editor_save_i(){


        $member_e = superpower_unlocked();
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif(!isset($_POST['save_i__message']) || !strlen($_POST['save_i__message'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea message',
            ));

        } elseif(!isset($_POST['save_i__hashtag']) || !strlen($_POST['save_i__hashtag'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea hashtag',
            ));

        } elseif(!isset($_POST['save_i__id']) || !intval($_POST['save_i__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        } elseif(!isset($_POST['save_x__id']) || !isset($_POST['save_x__message'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Transaction Data',
            ));

        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['save_i__id'],
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if(!count($is)){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea Not Active',
            ));
        }


        //Validate Idea Message:
        $validate_i__message = validate_i__message($_POST['save_i__message']);
        if(!$validate_i__message['status']){
            return view_json(array(
                'status' => 0,
                'message' => $validate_i__message['message'],
            ));
        }


        //Validate Dynamic Inputs:
        $e___42179 = $this->config->item('e___42179'); //Dynamic Input Fields

        //Process dynamic inputs if any:
        for ($p = 1; $p <= view_memory(6404,42206); $p++) {

            if(!isset($_POST['save_dynamic_' . $p])){
                break; //Nothing more to process
            }

            $input_parts = explode('____', $_POST['save_dynamic_' . $p], 2);

            $dynamic_e__id = $input_parts[0];
            $dynamic_value = $input_parts[1];

            //Let's first determine the data type:
            foreach(array_intersect($e___42179[$dynamic_e__id]['m__following'], $this->config->item('n___4592')) as $data_type_this){
                $data_type = $data_type_this;
                break;
            }

            //Required fields must have an input:
            if(in_array($dynamic_e__id, $this->config->item('n___42174')) && !strlen($_POST['save_dynamic_' . $p])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Source Not Active',
                ));
            }

            //Validate input if required or provided:
            if (strlen($_POST['save_dynamic_' . $p])) {
                $valid_data_type = valid_data_type($data_type, $_POST['save_dynamic_' . $p], $e___42179[$dynamic_e__id]['m__title']);
                if (!$valid_data_type['status']) {
                    //We had an error:
                    return view_json($valid_data_type);
                }
            }

            //Yes value is valid!

            //Fetch the current value:
            $values = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___42252')) . ')' => null, //Plain Link
                'x__right' => $is[0]['i__id'],
                'x__up' => $dynamic_e__id,
            ));

            //Update if needed:
            if(count($values) && !strlen($_POST['save_dynamic_'.$p] )){

                //Remove Link:
                $this->X_model->update($values[0]['x__id'], array(
                    'x__access' => 6173, //Transaction Removed
                ), $member_e['e__id'], 42175 /* Dynamic Link Content Removed */);

            } elseif(!count($values)){

                //Create New Link:
                $this->X_model->create(array(
                    'x__creator' => $member_e['e__id'],
                    'x__type' => 4983, //Co-Author
                    'x__up' => $dynamic_e__id,
                    'x__right' => $is[0]['i__id'],
                    'x__message' => $_POST['save_dynamic_'.$p],
                    'x__weight' => number_x__weight($_POST['save_dynamic_' . $p]),
                ));

            } elseif($values[0]['x__message']!=$_POST['save_dynamic_'.$p]){

                //Update Link:
                $this->X_model->update($values[0]['x__id'], array(
                    'x__message' => $_POST['save_dynamic_'.$p],
                    'x__weight' => number_x__weight($_POST['save_dynamic_' . $p]),
                ), $member_e['e__id'], 42176 /* Dynamic Link Content Updated */);

            }
        }



        //Validate Idea Hashtag & save if needed:
        if($is[0]['i__hashtag'] !== trim($_POST['save_i__hashtag'])){

            $validate_handle = validate_handle($_POST['save_i__hashtag'], $is[0]['i__id'], null);
            if(!$validate_handle['status']){
                return view_json(array(
                    'status' => 0,
                    'message' => $validate_handle['message'],
                ));
            }


            //Update Handles everywhere they are referenced:
            foreach ($this->X_model->fetch(array(
                'x__left' => $is[0]['i__id'],
                'x__type' => 31834, //Idea Reference
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            )) as $ref) {
                $this->I_model->update($ref['x__right'], array(
                    'i__message' => preg_replace('/\b#'.$is[0]['i__hashtag'].'\b/', '@'.trim($_POST['save_i__hashtag']), $ref['i__message']),
                ), false, $member_e['e__id']);
            }

            //Save hashtag since changed:
            $is[0]['i__hashtag'] = trim($_POST['save_i__hashtag']);
            $this->I_model->update($is[0]['i__id'], array(
                'i__hashtag' => $is[0]['i__hashtag'],
            ), true, $member_e['e__id']);


        }


        //Do we have a link reference message that need to be saved?
        if($_POST['save_x__id']>0){
            //Fetch transaction:
            foreach($this->X_model->fetch(array(
                'x__id' => $_POST['save_x__id'],
            )) as $this_x){

                $is[0] = array_merge($is[0], $this_x);

                if($this_x['x__message'] != trim($_POST['save_x__message'])){
                    $this->X_model->update($this_x['x__id'], array(
                        'x__message' => trim($_POST['save_x__message']),
                        'x__weight' => number_x__weight(trim($_POST['save_x__message'])),
                    ), $member_e['e__id'], 42171);
                }
            }
        }


        //Update Links based on save_i__message / Sync Idea Synonym & Source References links:
        $view_sync_links = view_sync_links($_POST['save_i__message'], true, $is[0]['i__id']);
        $is[0]['i__message'] = trim($_POST['save_i__message']);
        $is[0]['i__cache'] = $view_sync_links['i__cache'];


        //Update Search Index:
        update_algolia(12273, $is[0]['i__id']);


        return view_json(array(
            'status' => 1,
            'return_i__cache' => $view_sync_links['i__cache'],
            'return_i__cache_links' => view_i_links($is[0]),
            'message' => $view_sync_links['sync_stats']['old_links_removed'].' old links removed, '.$view_sync_links['sync_stats']['old_links_kept'].' old links kept, '.$view_sync_links['sync_stats']['new_links_added'].' new links added.',
        ));

    }

}