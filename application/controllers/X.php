<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class X extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        cookie_check();

    }

    function x_create(){
        return view_json($this->X_model->create($_POST));
    }

    function x_type_preview()
    {

        if (!isset($_POST['x__message']) || !isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing inputs',
            ));
        }

        //Will Contain every possible Member Transaction Connector:
        $e___4592 = $this->config->item('e___4592');

        //See what this is:
        $detected_x_type = x_detect_type($_POST['x__message']);

        if(!$_POST['x__id'] && !in_array($detected_x_type['x__type'], $this->config->item('n___4537'))){

            //NOT SOURCE LINK URLS
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));

        } elseif (!$detected_x_type['status'] && isset($detected_x_type['url_previously_existed']) && $detected_x_type['url_previously_existed']) {

            //See if this is duplicate to either transaction:
            $e_x = $this->X_model->fetch(array(
                'x__id' => $_POST['x__id'],
                'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //SOURCE LINK URLS
            ));

            //Are they both different?
            if (count($e_x) < 1 || ($e_x[0]['x__up'] != $detected_x_type['e_url']['e__id'] && $e_x[0]['x__down'] != $detected_x_type['e_url']['e__id'])) {
                //return error:
                return view_json($detected_x_type);
            }

        }

        $x__history_preview = '';

        $session_name = 'session_'.date("YmdHms");

        if(!$this->session->userdata($session_name)){

            //Generate history preview, if any:

            if($_POST['x__id']>0){
                //See if this is duplicate to either transaction:
                $xs = $this->X_model->fetch(array(
                    'x__id' => $_POST['x__id'],
                ));
                $array_history = array();
                foreach($this->X_model->fetch(array(
                    'x__up' => $xs[0]['x__up'],
                    'x__down' => $xs[0]['x__down'],
                    'x__type' => 10657, //Past Deleted
                ), array(), 0) as $x_history) {
                    $x__metadata = unserialize($x_history['x__metadata']);
                    if(strlen($x__metadata['fields_changed'][0]['before'])>1 && !in_array($x__metadata['fields_changed'][0]['before'], $array_history)){
                        array_push($array_history, $x__metadata['fields_changed'][0]['before']);
                    }
                    if(strlen($x__metadata['fields_changed'][0]['after'])>1 && !in_array($x__metadata['fields_changed'][0]['after'], $array_history)){
                        array_push($array_history, $x__metadata['fields_changed'][0]['after']);
                    }
                }

                if(count($array_history)){
                    $x__history_preview .= '<div style="margin: 13px 0;">History:</div>';
                }

                foreach($array_history as $image){
                    $x__history_preview .= '<a href="javascript:void(0)" onclick="x_message_save(\''.$image.'\');" class="icon-block-lg">'.view_cover(12273, $image, true).'</a>';
                }
            }

            $this->session->set_userdata($session_name, $x__history_preview);
            $in_history = 0;

        } else {

            $in_history = 1;
            $x__history_preview = $this->session->userdata($session_name);

        }




        return view_json(array(
            'status' => 1,
            'x__type_preview' => '<b class="css__title">' . $e___4592[$detected_x_type['x__type']]['m__cover'] . ' ' . $e___4592[$detected_x_type['x__type']]['m__title'] . '</b>',
            'x__message_preview' => ( in_array($detected_x_type['x__type'], $this->config->item('n___12524')) ? '<span class="paddingup">'.view_x__message($_POST['x__message'], $detected_x_type['x__type'], null, true).'</span>' : ''),
            'in_history' => $in_history,
            'x__history_preview' => $x__history_preview,
        ));

    }




    function x_set_text(){

        //Authenticate Member:
        $member_e = superpower_unlocked();
        $e___12112 = $this->config->item('e___12112');

        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
                'original_val' => '',
            ));

        } elseif(!isset($_POST['s__id']) || !isset($_POST['cache_e__id']) || !isset($_POST['field_value'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_e__id']==4736 /* IDEA TITLE */){

            $is = $this->I_model->fetch(array(
                'i__id' => $_POST['s__id'],
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            ));
            if(!count($is)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));
            }

            //Validate Idea Outcome:
            $i__validate_title = i__validate_title($_POST['field_value']);
            if(!$i__validate_title['status']){
                //We had an error, return it:
                return view_json(array_merge($i__validate_title, array(
                    'original_val' => $is[0]['i__title'],
                )));
            }


            //All good, go ahead and update:
            $this->I_model->update($_POST['s__id'], array(
                'i__title' => trim($_POST['field_value']),
            ), true, $member_e['e__id']);

            return view_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_e__id']==6197 /* SOURCE FULL NAME */){

            $es = $this->E_model->fetch(array(
                'e__id' => $_POST['s__id'],
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if(!count($es)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID #3',
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
            ), true, $member_e['e__id']);

            //Reset member session data if this data belongs to the logged-in member:
            if ($es[0]['e__id'] == $member_e['e__id']) {
                //Re-activate Session with new data:
                $es[0]['e__title'] = $e__title_validate['e__title_clean'];
                $this->E_model->activate_session($es[0], true);
            }

            return view_json(array(
                'status' => 1,
            ));

        } else {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown Update Type ['.$_POST['cache_e__id'].']',
                'original_val' => '',
            ));

        }
    }



    function apply_preview()
    {

        if(!isset($_POST['apply_id']) || !isset($_POST['coin__id'])){
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing Core Data</div>';
        } else {
            if($_POST['apply_id']==4997){

                //Source list:
                $counter = view_coins_e(12274, $_POST['coin__id'], 0, false);
                if(!$counter){
                    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Sources yet...</div>';
                } else {
                    echo '<div class="msg alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' source'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach(view_coins_e(12274, $_POST['coin__id'], 1, true) as $e) {
                        array_push($ids, $e['e__id']);
                        echo view_e_card(12274, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent" title="Total of '.count($ids).'">'.join(', ',$ids).'</div>';
                }

            } elseif($_POST['apply_id']==12589){

                //idea list:
                $is_next = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                    'x__left' => $_POST['coin__id'],
                ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
                $counter = count($is_next);

                if(!$counter){
                    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Ideas yet...</div>';
                } else {
                    echo '<div class="msg alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' idea'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach($is_next as $i) {
                        array_push($ids, $i['i__id']);
                        echo view_i_card(12273, 0, null, $i);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent">'.join(',',$ids).'</div>';
                }

            } else {
                echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Unknown Apply ID</div>';
            }
        }
    }




    function complete_next($top_i__id, $current_i__id, $next_i__id){

        //Marks an idea as complete if the member decides to navigate out of order:

        $member_e = superpower_unlocked();
        $current_is = $this->I_model->fetch(array(
            'i__id' => $current_i__id,
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));

        $next_is = $this->I_model->fetch(array(
            'i__id' => $next_i__id,
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));

        if(!count($current_is) || !count($next_is)){

            //Not public, somehow!
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'complete_next() found non-public ideas for Top ID /'.$top_i__id.'!',
                'x__source' => ( $member_e ? $member_e['e__id'] : 0 ),
                'x__left' => $current_i__id,
                'x__right' => $next_i__id,
            ));

            return false;
        }

        //Mark this as complete since there is no child to choose from:
        if($member_e && in_array($current_is[0]['i__type'], $this->config->item('n___12330')) && !count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__source' => $member_e['e__id'],
                'x__left' => $current_is[0]['i__id'],
            )))){
            $this->X_model->mark_complete($top_i__id, $current_is[0], array(
                'x__type' => 4559, //DISCOVERY MESSAGES
                'x__source' => $member_e['e__id'],
            ));
        }

        if($member_e && in_array($next_is[0]['i__type'], $this->config->item('n___12330')) && !count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__source' => $member_e['e__id'],
                'x__left' => $next_is[0]['i__id'],
            )))){
            $this->X_model->mark_complete($top_i__id, $next_is[0], array(
                'x__type' => 4559, //DISCOVERY MESSAGES
                'x__source' => $member_e['e__id'],
            ));
        }

        return redirect_message('/'.$top_i__id.'/'.$next_i__id);

    }


    function x_start($i__id){

        //Adds Idea to the Members read

        $member_e = superpower_unlocked();
        $e___11035 = $this->config->item('e___11035'); //NAVIGATION

        //Check to see if added to read for logged-in members:
        if(!$member_e){
            return redirect_message('/-4269?i__id='.$i__id);
        }

        //Add this Idea to their read If not there:
        $next_i__id = $i__id;

        if(!in_array($i__id, $this->X_model->started_ids($member_e['e__id']))){

            //Make sure they can start this:
            $is = $this->I_model->fetch(array(
                'i__id' => $i__id,
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            ));
            if(!count($is)){
                return redirect_message('/', '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid idea ID</div>', true);
            }

            //Make sure it's available:
            $i_is_available = i_is_available($i__id, true);
            if(!$i_is_available['status']){
                return redirect_message('/'.$i_is_available['return_i__id'], '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i_is_available['message'].'</div>');
            }


            //Make sure not previously added to this Member's discoveries:
            $xs = $this->X_model->fetch(array(
                'x__source' => $member_e['e__id'],
                'x__left' => $i__id,
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            ));
            if(count($xs)){

                //Already has a starting point:
                $top_i__id =  $xs[0]['x__left'];

            } else {

                //This is the new top ID
                $top_i__id =  $is[0]['i__id'];

                //New Starting Point:
                $this->X_model->mark_complete($top_i__id, $is[0], array(
                    'x__type' => 4235, //Get started
                    'x__source' => $member_e['e__id'],
                ));

                //$one_child_hack: Mark next level as done too? Only if Single show:
                $is_next = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'x__left' => $top_i__id,
                ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
                if(count($is_next)==1){
                    foreach($is_next as $single_child){
                        if(in_array($single_child['i__type'], $this->config->item('n___12330'))){
                            $this->X_model->mark_complete($top_i__id, $single_child, array(
                                'x__type' => 4559, //DISCOVERY MESSAGES
                                'x__source' => $member_e['e__id'],
                            ));
                        }
                    }
                }

            }

            //Now return next idea:
            $next_i__id = $this->X_model->find_next($member_e['e__id'], $top_i__id, $is[0]);


            if(!$next_i__id){
                //Failed to add to read:
                return redirect_message(home_url());
            }
        }

        //Go to this newly added idea:
        return redirect_message('/'.$i__id.'/'.$next_i__id);

    }


    function x_next($top_i__id, $i__id = 0){

        if(!$i__id){
            die('missing valid ID');
        }

        $member_e = superpower_unlocked();
        $i_is_available = i_is_available($i__id, true);
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));

        if(!$member_e){
            return redirect_message('/-4269?i__id='.$top_i__id);
        } elseif(!$this->X_model->started_ids($member_e['e__id'], $top_i__id)) {
            return redirect_message('/'.$top_i__id);
        } elseif(!count($is)) {
            return redirect_message('/'.$top_i__id, '<div class="msg alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>This idea is not published yet</div>');
        } elseif(!$i_is_available['status']){
            return redirect_message('/'.$top_i__id.'/'.$i_is_available['return_i__id'], '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i_is_available['message'].'</div>');
        }


        //Should we check for auto next redirect if empty? Only if this is a selection:
        $next_url = null;
        if(!count($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__source' => $member_e['e__id'],
            'x__left' => $is[0]['i__id'],
        )))){
            //Not yet completed, should we complete?
            if(in_array($is[0]['i__type'], $this->config->item('n___12330'))){
                //Yes we can:
                $this->X_model->mark_complete($top_i__id, $is[0], array(
                    'x__type' => 4559, //DISCOVERY MESSAGES
                    'x__source' => $member_e['e__id'],
                ));
            } else {
                //We can't, so this is the next idea:
                $next_url = '/'.$top_i__id.'/'.$is[0]['i__id'];
            }
        }


        if($next_url){
            return redirect_message($next_url);
        }


        //Go to Next Idea:
        $next_i__id = $this->X_model->find_next($member_e['e__id'], $top_i__id, $is[0]);
        if($next_i__id > 0){

            return redirect_message('/'.$top_i__id.'/'.$next_i__id );

        } else {

            //Mark as Complete
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => 14730, //COMPLETED 100%
                'x__right' => $top_i__id,
                //TODO Maybe log additional details like total ideas, time, etc...
            ));

            //Go to Rating App since it's first completion:
            return redirect_message('/'.$top_i__id.'/'.$top_i__id);

        }
    }

    function view_load_page()
    {

        $focus_e = array();
        $previous_i = array();

        if(!isset($_POST['focus_coin'])){
            die('Missing input. Refresh and try again.');
        }

        if($_POST['focus_coin']==12274){

            //SOURCE
            $focus_es = $this->E_model->fetch(array(
                'e__id' => $_POST['focus_id'],
            ));
            $focus_e = $focus_es[0];

            foreach(view_coins_e($_POST['x__type'], $_POST['focus_id'], $_POST['current_page']) as $s) {
                if ($_POST['x__type'] == 12274 || $_POST['x__type'] == 11030) {
                    echo view_e_card($_POST['x__type'], $s);
                } else if ($_POST['x__type'] == 6255 || $_POST['x__type'] == 12273) {
                    echo view_i_card($_POST['x__type'], 0, $previous_i, $s, $focus_e);
                }
            }

        } elseif($_POST['focus_coin']==12273) {

            //IDEA
            $previous_is = $this->I_model->fetch(array(
                'i__id' => $_POST['focus_id'],
            ));
            $previous_i = $previous_is[0];

            foreach(view_coins_i($_POST['x__type'], $_POST['focus_id'], $_POST['current_page']) as $s) {
                if ($_POST['x__type'] == 12273 || $_POST['x__type'] == 11019) {
                    echo view_i_card($_POST['x__type'], 0, $previous_i, $s, $focus_e);
                } else if ($_POST['x__type'] == 6255 || $_POST['x__type'] == 12274) {
                    echo view_e_card($_POST['x__type'], $s);
                }
            }
        }

    }

    function x_completed_next($top_i__id, $i__id = 0){

        $member_e = superpower_unlocked();
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));

        if(!$member_e){
            return redirect_message('/-4269?i__id='.$top_i__id);
        } elseif(!$this->X_model->started_ids($member_e['e__id'], $top_i__id)) {
            return redirect_message('/'.$top_i__id);
        } elseif(!count($is)) {
            return redirect_message('/'.$top_i__id, '<div class="msg alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>This idea is not published yet</div>');
        }

        //Go to Next Idea:
        $next_i__id = $this->X_model->find_next($member_e['e__id'], $top_i__id, $is[0], 0, true, true);
        if($next_i__id > 0){

            return redirect_message('/'.$top_i__id.'/'.$next_i__id);

        } else {

            //Nothing else to find, go to top:
            return redirect_message('/'.$top_i__id);

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
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__left' => $previous_level_id,
            ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $next_i){
                if($next_i['i__id']==$i__id){
                    break;
                } else {
                    $current_i__id = $next_i['i__id'];
                }
            }
        }

        return redirect_message('/'.$current_i__id);

    }





    function x_layout($top_i__id, $i__id, $tag__id=0, $member__id=0)
    {

        /*
         *
         * Enables a Member to DISCOVER an IDEA
         * on the public web
         *
         * */

        $flash_message = null;
        $member_e = superpower_unlocked();

        //Log link if not there:
        if(
            $tag__id>0
            && $member__id>0
            && count($this->X_model->fetch(array(
                'x__up IN (' . join(',', $this->config->item('n___30820')) . ')' => null, //Active Member
                'x__down' => $member__id,
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))
            && !count($this->X_model->fetch(array(
                'x__up' => $tag__id,
                'x__down' => $member__id,
                'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )))
        ){

            $x__source = ($member__id > 0 ? $member__id : ($member_e ? $member_e['e__id'] : 0));
            $es_tag = $this->E_model->fetch(array(
                'e__id' => $tag__id,
            ));
            if(count($es_tag)){

                //Add source link:
                $this->X_model->create(array(
                    'x__type' => e_x__type(),
                    'x__source' => $x__source,
                    'x__up' => $tag__id,
                    'x__down' => $x__source,
                ));

                //Log Reference:
                $this->X_model->create(array(
                    'x__type' => 29393, //Log Referral
                    'x__source' => $x__source,
                    'x__up' => $tag__id,
                    'x__down' => $x__source,
                    'x__left' => $i__id,
                    'x__right' => $top_i__id,
                ));

                //Inform user of changes:
                $flash_message = '<div class="msg alert alert-warning" role="alert">You\'ve been added to '.view_cover(12274,$es_tag[0]['e__cover'], true).' '.$es_tag[0]['e__title'].'</div>';

            }
        }

        //Fetch data:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));

        if($top_i__id > 0){

            $top_is = $this->I_model->fetch(array(
                'i__id' => $top_i__id,
            ));

        } elseif($member_e) {

            //Fetch parent tree discovery trace to see if we find anything:
            foreach($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__source' => $member_e['e__id'],
                'x__left' => $i__id,
                'x__right > 0' => null,
                'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            ), array('x__right')) as $x){
                return redirect_message('/'.$x['x__right'].'/'.$i__id);
            }

            $recursive_is = $this->I_model->recursive_parent_ids($i__id);
            if(count($recursive_is)){
                //Try top level discoveries:
                foreach($this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                    'x__source' => $member_e['e__id'],
                    'x__left IN (' . join(',', $recursive_is) . ')' => null,
                    'x__right > 0' => null,
                    'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                ), array('x__right')) as $x){
                    return redirect_message('/'.$x['x__right'].'/'.$i__id);
                }
            }

        }

        //Make sure we found it:
        if ( $top_i__id > 0 && !count($top_is) ) {

            return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Top Idea ID ' . $top_i__id . ' not found</div>');

        } elseif ( !count($is) ) {

            return redirect_message( ( $top_i__id > 0 ? '/'.$top_i__id : home_url() ), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Idea ID ' . $i__id . ' not found</div>');

        } elseif(!in_array($is[0]['i__type'], $this->config->item('n___7355') /* PRIVATE */)){

            return redirect_message((superpower_unlocked(10939) ? '/~' . $i__id : home_url()), '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Idea #'.$is[0]['i__id'].' is not published yet.</div>');

        }


        if($top_i__id > 0 && !in_array($top_is[0]['i__type'], $this->config->item('n___7355') /* PRIVATE */)) {
            return redirect_message('/'.$i__id);
        }



    //Determine Member:
        /*
        $member_e = false;
        if(isset($_GET['load__e']) && superpower_active(14005, true)){

            //Fetch This Member
            $e_filters = $this->E_model->fetch(array(
                'e__id' => $_GET['load__e'],
                'e__status IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if(count($e_filters)){
                echo view__load__e($member_e);
                $member_e = $e_filters[0];
            }

        }
        if(!$member_e){
            $member_e = superpower_unlocked();
        }
        */



        if($member_e) {
            //VIEW DISCOVERY
            $this->X_model->create(array(
                'x__source' => $member_e['e__id'],
                'x__type' => 7610, //MEMBER VIEWED DISCOVERY
                'x__left' => ( $top_i__id > 0 ? $top_is[0]['i__id'] : 0 ),
                'x__right' => $is[0]['i__id'],
                'x__spectrum' => fetch_cookie_order('7610_' . $is[0]['i__id']),
            ));
        }

        $this->load->view('header', array(
            'title' => $is[0]['i__title'].( $top_i__id > 0 ? ' | '.$top_is[0]['i__title'] : '' ),
            'i' => $is[0],
            'flash_message' => $flash_message,
        ));
        $this->load->view('x_layout', array(
            'i_top' => ( $top_i__id > 0 ? $top_is[0] : false ),
            'i' => $is[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }


    function x_schedule_delete(){
        $this->X_model->update($_POST['x__id'], array(
            'x__status' => 6173, //Deleted
        ));
    }

    function x_schedule_message(){

        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['message_subject']) || !strlen($_POST['message_subject'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Subject',
            ));

        } elseif (!isset($_POST['message_text']) || !strlen($_POST['message_text'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Message Body',
            ));

        } elseif (!isset($_POST['message_time']) || !strtotime($_POST['message_time'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Message Time',
            ));

        }

        //Log mass message transaction:
        $log_x = $this->X_model->create(array(
            'x__type' => 26582, //Send Instant Message
            'x__status' => 6175, //Drafting until it's sent via Cron Job
            'x__time' => date('Y-m-d H:i:s', strtotime($_POST['message_time'])),
            'x__source' => $member_e['e__id'],
            'x__message' => $_POST['message_subject'],
            'x__metadata' => array(
                'message_subject' => $_POST['message_subject'],
                'message_text' => $_POST['message_text'],
                'message_time' => $_POST['message_time'],
                'i__id' => $_POST['i__id'],
                'e__id' => $_POST['e__id'],
                'exclude_e' => $_POST['exclude_e'],
                'include_e' => $_POST['include_e'],
            ),
        ));

        return view_json(array(
            'status' => ( isset($log_x['x__id']) ? 1 : 0 ),
            'message' => 'Scheduled messages for '.$_POST['message_time'],
        ));

    }

    function x_upload()
    {

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
                'message' => 'Missing IDEA',
            ));

        } elseif (!isset($_POST['top_i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top IDEA',
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

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (view_memory(6404,13572) * 1024 * 1024)) {

            return view_json(array(
                'status' => 0,
                'message' => 'File is larger than the maximum allowed file size of ' . view_memory(6404,13572) . ' MB.',
            ));

        }

        //Validate Idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
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

        $cdn_status = upload_to_cdn($temp_local, $member_e['e__id'], $_FILES[$_POST['upload_type']], true, $is[0]['i__title'].' BY '.$member_e['e__title']);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return view_json($cdn_status);
        }


        //Delete previous answer(s):
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left' => $is[0]['i__id'],
            'x__source' => $member_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__status' => 6173, //Transaction Removed
            ), $member_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
        }

        //Save new answer:
        $new_message = '@'.$cdn_status['cdn_e']['e__id'];
        $this->X_model->mark_complete($_POST['top_i__id'], $is[0], array(
            'x__type' => 12117,
            'x__source' => $member_e['e__id'],
            'x__message' => $new_message,
            'x__up' => $cdn_status['cdn_e']['e__id'],
        ));

        //All good:
        $e___11035 = $this->config->item('e___11035'); //NAVIGATION
        return view_json(array(
            'status' => 1,
            'message' => view_headline(13977, null, $e___11035[13977], $this->X_model->message_view($new_message, true), true),
        ));

    }



    function cover_upload()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['coin__type']) || !isset($_POST['coin__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core info',
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

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (view_memory(6404,13572) * 1024 * 1024)) {

            return view_json(array(
                'status' => 0,
                'message' => 'File is larger than the maximum allowed file size of ' . view_memory(6404,13572) . ' MB.',
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


        //Upload to CDN and return:
        $cdn_status = upload_to_cdn($temp_local, 0 /* To NOT create a Source from URL */, $_FILES[$_POST['upload_type']], true);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return view_json($cdn_status);
        } else {

            //Log Success:
            $invite_x = $this->X_model->create(array(
                'x__type' => 25990,
                'x__source' => $member_e['e__id'],
                'x__down' => ( $_POST['coin__type']==12274 ? $_POST['coin__id'] : 0 ),
                'x__right' => ( $_POST['coin__type']==12273 ? $_POST['coin__id'] : 0 ),
                'x__message' => $cdn_status['cdn_url'],
            ));

            //Return CDN URL:
            return view_json(array(
                'status' => 1,
                'cdn_url' => $cdn_status['cdn_url'],
            ));

        }
    }


    function x_reply(){

        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['i__id']) || !intval($_POST['i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea ID.',
            ));
        } elseif (!isset($_POST['top_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top idea ID.',
            ));
        } elseif (!isset($_POST['x_reply'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Response Variable.',
            ));
        }

        //Validate/Fetch idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));
        if(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        }


        $_POST['x_reply'] = trim($_POST['x_reply']);


        //Trying to Skip?
        if(!strlen($_POST['x_reply'])){
            if(count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $_POST['i__id'],
                'x__up' => 28239, //Can Skip
            )))){
                //Log Skip:
                $this->X_model->mark_complete(intval($_POST['top_i__id']), $is[0], array(
                    'x__type' => 31022, //Skipped
                    'x__source' => $member_e['e__id'],
                    'x__message' => $_POST['x_reply'],
                ));
                //All good:
                return view_json(array(
                    'status' => 1,
                    'message' => 'Skipped & Next...',
                ));
            } else {
                //Cannot Skip:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Write a response before going next.',
                ));
            }
        }




        //Type Specific Requirements?
        if ($is[0]['i__type']==30350) {

            $x__type = 31798; //Set Time

            $min_time = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $_POST['i__id'],
                'x__up' => 26556, //Time Starts
            ), array(), 1);
            $max_time = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $_POST['i__id'],
                'x__up' => 26557, //Time Ends
            ), array(), 1);

            if(!strtotime($_POST['x_reply'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid time.',
                ));
            } elseif(count($min_time) && strtotime($min_time[0]['x__message'])<strtotime($_POST['x_reply'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a date after '.$min_time[0]['x__message'],
                ));
            } elseif(count($max_time) && strtotime($max_time[0]['x__message'])>strtotime($_POST['x_reply'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a date before '.$max_time[0]['x__message'],
                ));
            }

        } elseif ($is[0]['i__type']==31794) {

            $x__type = 31797; //Entered Number

            $min_value = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $_POST['i__id'],
                'x__up' => 31800, //Min Value
            ), array(), 1);
            $max_value = $this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__right' => $_POST['i__id'],
                'x__up' => 31801, //Max Value
            ), array(), 1);

            if(!is_numeric($_POST['x_reply'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid number.',
                ));
            } elseif(count($min_value) && floatval($min_value[0]['x__message'])<floatval($_POST['x_reply'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a number bigger than '.$min_value[0]['x__message'],
                ));
            } elseif(count($max_value) && floatval($max_value[0]['x__message'])>floatval($_POST['x_reply'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a number smaller than '.$max_value[0]['x__message'],
                ));
            }

        } elseif ($is[0]['i__type']==31795) {

            $x__type = 31799; //Entered URL

            if(!filter_var($_POST['x_reply'], FILTER_VALIDATE_URL)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid URL.',
                ));
            }

        } elseif ($is[0]['i__type']==6683) {

            $x__type = 6144; //Text Replied

        } else {

            //Unknown type!
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'x_reply() Unknown text reply',
                'x__metadata' => array(
                    'post' => $_POST,
                ),
            ));
            return false;

        }


        //Any Preg Match?
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__right' => $_POST['i__id'],
            'x__up' => 266111, //Preg Match
        )) as $preg_match){
            if(!preg_match($preg_match['x__message'], $_POST['x_reply'])) {

                //Do we have a custom message:
                $preg_match_message = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                    'x__right' => $_POST['i__id'],
                    'x__up' => 30998, //Preg Match Error
                ));

                $error_message = ( count($preg_match_message) && strlen($preg_match_message[0]['x__message']) ? $preg_match_message[0]['x__message'] : 'Invalid Input, Please try again...' );

                //Log preg match failure
                $this->X_model->create(array(
                    'x__type' => 30998, //Preg Match Error Message
                    'x__source' => $member_e['e__id'],
                    'x__message' => $error_message,
                    'x__left' => $_POST['top_i__id'],
                    'x__right' => $_POST['i__id'],
                ));

                //We have an error, let the user know:
                return view_json(array(
                    'status' => 0,
                    'message' => $error_message,
                ));

            }
        }



        //Delete previous answer(s) if any:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left' => $is[0]['i__id'],
            'x__source' => $member_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__status' => 6173, //Transaction Removed
            ), $member_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
        }

        //Save new answer:
        $this->X_model->mark_complete(intval($_POST['top_i__id']), $is[0], array(
            'x__type' => $x__type,
            'x__source' => $member_e['e__id'],
            'x__message' => $_POST['x_reply'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Saved & Next...',
        ));

    }






    function x_message_load(){

        $member_e = superpower_unlocked(10939);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing core inputs',
            ));
        }

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

        return view_json(array(
            'status' => 1,
            'x__message' => $fetch_xs[0]['x__message'],
        ));

    }





    function x_message_save()
    {


        //Auth member and check required variables:
        $member_e = superpower_unlocked(10939);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['x__id']) || !isset($_POST['x__message']) || !intval($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing source transaction data',
            ));
        }


        //Yes, first validate source transaction:
        $e_x = $this->X_model->fetch(array(
            'x__id' => $_POST['x__id'],
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ));
        if (count($e_x) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'INVALID TRANSACTION ID',
            ));
        }

        //Transaction content change?
        if ($e_x[0]['x__message'] == $_POST['x__message']) {

            //Transaction content has not changed:
            $x__message_type = $e_x[0]['x__type'];
            $x__message = $e_x[0]['x__message'];

        } else {

            //Change transaction type ONLY if source link:
            if(!in_array($e_x[0]['x__type'], $this->config->item('n___4592'))){

                $x__message = $_POST['x__message'];
                $x__message_type = $e_x[0]['x__type'];
                $this->X_model->update($_POST['x__id'], array(
                    'x__message' => $x__message,
                ), $member_e['e__id'], 26191 /* SOURCE CONTENT UPDATE */);

            } else {

                //it is a source link! We should update this:
                //Transaction content has changed:
                $detected_x_type = x_detect_type($_POST['x__message']);
                if (!$detected_x_type['status']) {
                    return view_json($detected_x_type);
                }

                //Update variables:
                $x__message = $_POST['x__message'];
                $x__message_type = $detected_x_type['x__type'];


                $this->X_model->update($_POST['x__id'], array(
                    'x__message' => $x__message,
                    'x__type' => $x__message_type,
                ), $member_e['e__id'], 10657 /* SOURCE LINK CONTENT UPDATE */);

            }
        }


        //Show success:
        return view_json(array(
            'status' => 1,
            'x__message' => view_x__message($x__message, $x__message_type),
            'x__message_final' => $x__message, //In case content was updated
        ));

    }


    function load_coin_count(){
        //Count transactions:
        $query = $this->X_model->fetch(array(), array(), 1, 0, array(), 'COUNT(x__id) as totals');
        $return_array = array(
            'count__x' => number_format($query[0]['totals'], 0),
        );
        foreach($this->config->item('e___14874') as $e__id => $m) {
            $return_array['count__'.$e__id] = number_format(count_unique_coins($e__id), 0);
        }
        return view_json($return_array);
    }

    function coin__save()
    {
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['coin__type']) || !in_array($_POST['coin__type'] , $this->config->item('n___12761'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin Type',
            ));
        } elseif (!isset($_POST['coin__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin ID',
            ));
        } elseif (!isset($_POST['coin__title'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin Title',
            ));
        } elseif (!isset($_POST['coin__cover'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Coin Cover',
            ));
        }

        if($_POST['coin__type']==12273){

            //IDEA
            $this->I_model->update($_POST['coin__id'], array(
                'i__title' => trim($_POST['coin__title']),
            ), true, $member_e['e__id']);

        } elseif($_POST['coin__type']==12274){

            //Reset member session data if this data belongs to the logged-in member:
            if ($_POST['coin__id'] == $member_e['e__id']) {

                $es = $this->E_model->fetch(array(
                    'e__id' => intval($_POST['coin__id']),
                ));
                if(count($es)){
                    //Re-activate Session with new data:
                    $es[0]['e__title'] = trim($_POST['coin__title']);
                    $es[0]['e__cover'] = trim($_POST['coin__cover']);
                    $this->E_model->activate_session($es[0], true);
                }

            }

            //SOURCE
            $this->E_model->update($_POST['coin__id'], array(
                'e__title' => trim($_POST['coin__title']),
                'e__cover' => trim($_POST['coin__cover']),
            ), true, $member_e['e__id']);

        }

        return view_json(array(
            'status' => 1,
        ));

    }



    function update_dropdown(){

        return view_json($this->X_model->update_dropdown($_POST['focus_id'],$_POST['o__id'],$_POST['element_id'],$_POST['new_e__id'],$_POST['migrate_s__id'],$_POST['x__id']));

    }



    function x_select(){
        return view_json($this->X_model->x_select($_POST['top_i__id'], $_POST['focus_id'], ( !isset($_POST['selection_i__id']) || !count($_POST['selection_i__id']) ? array() : $_POST['selection_i__id'] )));
    }



    function e_reset_discoveries($e__id = 0){

        $member_e = superpower_unlocked(null, true);
        $e__id = ( $e__id > 0 || !$member_e ? $e__id : $member_e['e__id'] );

        //Fetch their current progress transactions:
        $progress_x = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //EXPANDED DISCOVERIES
            'x__source' => $e__id,
        ), array(), 0);

        if(count($progress_x) > 0){

            //Yes they did have some:
            $message = 'Deleted all '.count($progress_x).' discoveries';

            //Log transaction:
            $clear_all_x = $this->X_model->create(array(
                'x__message' => $message,
                'x__type' => 6415,
                'x__source' => $e__id,
            ));

            //Delete all progressions:
            foreach($progress_x as $progress_x){
                $this->X_model->update($progress_x['x__id'], array(
                    'x__status' => 6173, //Transaction Removed
                    'x__reference' => $clear_all_x['x__id'], //To indicate when it was deleted
                ), $e__id, 6415 /* Reset All discoveries */);
            }

        } else {

            //Nothing to do:
            $message = 'Nothing found to be removed';

        }

        //Show basic UI for now:
        return redirect_message('/@'.$e__id, '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');

    }


    function x_link_toggle(){

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
                'message' => 'Missing Idea ID',
            ));

        } elseif (!isset($_POST['x__type'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Type',
            ));

        } elseif (!isset($_POST['top_i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top Idea ID',
            ));

        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //Save IDEA:
        $x = $this->X_model->create(array(
            'x__source' => $member_e['e__id'],
            'x__up' => $member_e['e__id'],
            'x__left' => $_POST['top_i__id'],
            'x__right' => $_POST['i__id'],
            'x__type' => $_POST['x__type'],
        ));

        //All Good:
        return view_json(array(
            'status' => 1,
            'x__id' => $x['x__id'],
        ));

    }


    function x_remove(){

        /*
         *
         * When members indicate they want to stop
         * a IDEA this function saves the changes
         * necessary and delete the idea from their
         * discoveries.
         *
         * */

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['x__id']) || intval($_POST['x__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        }

        //Remove Idea
        $this->X_model->update($_POST['x__id'], array(
            'x__status' => 6173, //DELETED
        ), $member_e['e__id'], 10673);

        return view_json(array(
            'status' => 1,
        ));
    }





    function sort_i_handle_load()
    {

        /*
         *
         * Saves the order of read ideas based on
         * member preferences.
         *
         * */

        $member_e = superpower_unlocked();

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['new_x_order']) || !is_array($_POST['new_x_order']) || count($_POST['new_x_order']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing sorting ideas',
            ));
        } elseif (!isset($_POST['x__type']) || !in_array($_POST['x__type'], $this->config->item('n___4603'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Transaction Type',
            ));
        }

        //Update the order of their discoveries:
        $updated = 0;
        foreach($_POST['new_x_order'] as $x__spectrum => $x__id){
            if(intval($x__id) > 0 && intval($x__spectrum) > 0){
                //Update order of this transaction:
                if($this->X_model->update(intval($x__id), array(
                    'x__spectrum' => $x__spectrum,
                ), $member_e['e__id'], 4603)){
                    $updated++;
                }
            }
        }

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => $updated.' Sorted',
        ));
    }


}