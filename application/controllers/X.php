<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class X extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        universal_check();

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

        $e___4592 = $this->config->item('e___4592'); //DATA TYPES

        //See what this is:
        $detect_data_type = detect_data_type($_POST['x__message']);

        if(!$_POST['x__id'] && !in_array($detect_data_type['x__type'], $this->config->item('n___4537'))){

            //NOT SOURCE LINK URLS
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));

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
                    $x__history_preview .= '<a href="javascript:void(0)" onclick="x_message_save(\''.$image.'\');" class="icon-block-lg">'.view_cover($image, true).'</a>';
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
            'x__type_preview' => '<b class="main__title">' . $e___4592[$detect_data_type['x__type']]['m__cover'] . ' ' . $e___4592[$detect_data_type['x__type']]['m__title'] . '</b>',
            'x__message_preview' => ( in_array($detect_data_type['x__type'], $this->config->item('n___12524')) ? '<span class="paddingup">' . preview_x__message($_POST['x__message'], $detect_data_type['x__type'], null, true) . '</span>' : '' ),
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
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
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
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
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
            if ($es[0]['e__id']==$member_e['e__id']) {
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



    function mass_apply_preview()
    {

        //Log Modal View
        $member_e = superpower_unlocked();
        $this->X_model->create(array(
            'x__creator' => ( isset($member_e['e__id']) ? $member_e['e__id'] : 0 ),
            'x__type' => 14576, //MODAL VIEWED
            'x__up' => $_POST['apply_id'],
            'x__down' => ( $_POST['apply_id']==4997 ? $_POST['card__id'] : 0 ),
            'x__right' => ( $_POST['apply_id']==12589 ? $_POST['card__id'] : 0 ),
        ));

        if(!isset($_POST['apply_id']) || !isset($_POST['card__id'])){
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing Core Data</div>';
        } else {
            if($_POST['apply_id']==4997){

                //Source list:
                $counter = view_e_covers(12274, $_POST['card__id'], 0, false);
                if(!$counter){
                    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Sources yet...</div>';
                } else {
                    echo '<div class="msg alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' source'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach(view_e_covers(12274, $_POST['card__id'], 1, true) as $e) {
                        array_push($ids, $e['e__id']);
                        echo view_card_e(12274, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent" title="Total of '.count($ids).'">'.join(', ',$ids).'</div>';
                }

            } elseif($_POST['apply_id']==12589){

                //idea list:
                $is_next = $this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                    'x__left' => $_POST['card__id'],
                ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));
                $counter = count($is_next);

                if(!$counter){
                    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Ideas yet...</div>';
                } else {
                    echo '<div class="msg alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' idea'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach($is_next as $i) {
                        array_push($ids, $i['i__id']);
                        echo view_card_i(12273, 0, null, $i);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent">'.join(',',$ids).'</div>';
                }

            } else {
                echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Unknown Apply ID</div>';
            }
        }
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

            //valid idea?
            $is = $this->I_model->fetch(array(
                'i__id' => $i__id,
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ));
            if(!count($is)){
                return redirect_message('/', '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid idea ID</div>', true);
            }

            //is available?
            $i_is_available = i_is_available($i__id, true);
            if(!$i_is_available['status']){
                return redirect_message('/'.$i_is_available['return_i__id'], '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i_is_available['message'].'</div>');
            }

            //Is startable?
            if(!count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $i__id,
                'x__up' => 4235,
            )))){
                return redirect_message('/'.$i__id, '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This idea is not startable.</div>');
            }

            //Make sure not previously added to this Member's discoveries:
            $xs = $this->X_model->fetch(array(
                'x__creator' => $member_e['e__id'],
                'x__left' => $i__id,
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
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
                    'x__creator' => $member_e['e__id'],
                ));

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
        $i_is_available = i_is_available($i__id, true, false);
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
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


        //Go to Next Idea:
        $next_i__id = $this->X_model->find_next($member_e['e__id'], $top_i__id, $is[0]);
        if($next_i__id > 0){

            return redirect_message('/'.$top_i__id.'/'.$next_i__id );

        } else {

            //Mark as Complete
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
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

        if(!isset($_POST['focus_card'])){
            die('Missing input. Refresh and try again.');
        }

        if($_POST['focus_card']==12274){

            //SOURCE
            $focus_es = $this->E_model->fetch(array(
                'e__id' => $_POST['focus_id'],
            ));
            $focus_e = $focus_es[0];

            foreach(view_e_covers($_POST['x__type'], $_POST['focus_id'], $_POST['current_page']) as $s) {
                if ($_POST['x__type']==12274 || $_POST['x__type']==11030) {
                    echo view_card_e($_POST['x__type'], $s);
                } else if ($_POST['x__type']==6255 || $_POST['x__type']==12273) {
                    echo view_card_i($_POST['x__type'], 0, $previous_i, $s, $focus_e);
                }
            }

        } elseif($_POST['focus_card']==12273) {

            //IDEA
            $previous_is = $this->I_model->fetch(array(
                'i__id' => $_POST['focus_id'],
            ));
            $previous_i = $previous_is[0];

            foreach(view_i_covers($_POST['x__type'], $_POST['focus_id'], $_POST['current_page']) as $s) {
                if ($_POST['x__type']==12273 || $_POST['x__type']==11019) {
                    echo view_card_i($_POST['x__type'], 0, $previous_i, $s, $focus_e);
                } else if ($_POST['x__type']==6255 || $_POST['x__type']==12274) {
                    echo view_card_e($_POST['x__type'], $s);
                }
            }
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
                'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
                'x__left' => $previous_level_id,
            ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $next_i){
                if($next_i['i__id']==$i__id){
                    break;
                } else {
                    $current_i__id = $next_i['i__id'];
                }
            }
        }

        return redirect_message('/'.$current_i__id);

    }





    function x_layout($top_i__id, $i__id, $member__id=0, $discovery_hash=null)
    {

        /*
         *
         * Enables a Member to DISCOVER an IDEA
         * on the public web
         *
         * */

        $flash_message = null;
        $member_e = superpower_unlocked();
        $x__creator = ($member__id > 0 ? $member__id : ($member_e ? $member_e['e__id'] : 0));

        //Fetch data:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));
        if ( !count($is) ) {
            return redirect_message( ( $top_i__id > 0 ? '/'.$top_i__id : home_url() ), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Idea ID ' . $i__id . ' not found</div>');
        }


        //Log Link Click discovery if authenticated:
        if(
            strlen($discovery_hash)
            && $member__id>0
            && ($discovery_hash == view_hash($member__id))
        ){

            $this->X_model->mark_complete($top_i__id, $is[0], array(
                'x__type' => 29393, //Link Click
                'x__creator' => $member__id,
            ));

            //Inform user of changes:
            $flash_message = '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>You have successfully discovered this idea!</div>';

            //If not logged in, log them in:
            if(!$member_e){
                foreach($this->E_model->fetch(array(
                    'e__id' => $member__id,
                )) as $logged_e){
                    $this->E_model->activate_session($logged_e, true);
                    js_reload(3000);
                }
            }

        }


        if($top_i__id > 0){

            $top_is = $this->I_model->fetch(array(
                'i__id' => $top_i__id,
            ));
            if ( !count($top_is) ) {

                return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Top Idea ID ' . $top_i__id . ' not found</div>');

            }

        } elseif($member_e) {

            //Do we have a direct discovery?
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__creator' => $member_e['e__id'],
                'x__left' => $i__id,
                'i__access IN (' . join(',', $this->config->item('n___31870')) . ')' => null, //PUBLIC
            ), array('x__right')) as $x){
                return redirect_message('/'.$x['x__right'].'/'.$i__id);
            }

            //Any of tops been discovered?
            $top_discovery_id = $this->I_model->recursive_up_ids($i__id, $member_e['e__id']);
            if($top_discovery_id > 0){
                return redirect_message('/'.$top_discovery_id.'/'.$i__id);
            }

        }


    //Determine Member:
        /*
        $member_e = false;
        if(isset($_GET['load__e']) && superpower_active(14005, true)){

            //Fetch This Member
            $e_filters = $this->E_model->fetch(array(
                'e__id' => $_GET['load__e'],
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
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
                'x__creator' => $member_e['e__id'],
                'x__type' => 7610, //MEMBER VIEWED DISCOVERY
                'x__left' => $top_i__id,
                'x__right' => $is[0]['i__id'],
                'x__weight' => fetch_cookie_order('7610_' . $is[0]['i__id']),
            ));
        }

        $e___14874 = $this->config->item('e___14874'); //Mench Cards

        $this->load->view('header', array(
            'title' => $is[0]['i__title'].( $top_i__id > 0 ? ' > '.$top_is[0]['i__title'] : '' ),
            'i' => $is[0],
            'flash_message' => $flash_message,
        ));



        $this->load->view('x_layout', array(
            'top_i__id' => $top_i__id,
            'i' => $is[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }



    function sort_alphabetical()
    {

        //Authenticate Member:
        $member_e = superpower_unlocked(13422);

        if (!$member_e) {
            view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(13422),
            ));
        } elseif (!isset($_POST['focus_card']) || !in_array($_POST['focus_card'], $this->config->item('n___28956'))) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid focus_card',
            ));
        } elseif (!isset($_POST['focus_id']) || intval($_POST['focus_id']) < 1) {
            view_json(array(
                'status' => 0,
                'message' => 'Invalid focus_id',
            ));
        }

        if($_POST['focus_card']==12273){
            //Ideas order based on alphabetical order
            $order = 0;
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__left' => $_POST['focus_id'],
            ), array('x__right'), 0, 0, array('i__title' => 'ASC')) as $x) {
                $order++;
                $this->X_model->update($x['x__id'], array(
                    'x__weight' => $order,
                ), $member_e['e__id'], 13007 /* SOURCE SORT RESET */);
            }
        } elseif($_POST['focus_card']==12274){
            //Sources reset order
            foreach($this->X_model->fetch(array(
                'x__up' => $_POST['focus_id'],
                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__access IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__down'), 0, 0, array()) as $x) {
                $this->X_model->update($x['x__id'], array(
                    'x__weight' => 0,
                ), $member_e['e__id'], 13007 /* SOURCE SORT RESET */);
            }
        }


        //Display message:
        view_json(array(
            'status' => 1,
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

            //Log this error!
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__creator' => $member_e['e__id'],
                'x__message' => 'x_upload() Missing POST ERROR',
                'x__metadata' => array(
                    'post' => $_POST,
                ),
            ));

            return view_json(array(
                'status' => 0,
                'post' => $_POST,
                'get' => $_GET,
                'req' => $_REQUEST,
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

        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name'])==0 || intval($_FILES[$_POST['upload_type']]['size'])==0) {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown error (2) while trying to save file.',
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
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
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
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left' => $is[0]['i__id'],
            'x__creator' => $member_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__access' => 6173, //Transaction Removed
            ), $member_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
        }

        //Save new answer:
        $new_message = '@'.$cdn_status['cdn_e']['e__id'];
        $this->X_model->mark_complete($_POST['top_i__id'], $is[0], array(
            'x__type' => 12117,
            'x__creator' => $member_e['e__id'],
            'x__message' => $new_message,
            'x__up' => $cdn_status['cdn_e']['e__id'],
        ));

        //All good:
        $e___11035 = $this->config->item('e___11035'); //NAVIGATION
        return view_json(array(
            'status' => 1,
            'message' => view_headline(13977, null, $e___11035[13977], $this->X_model->message_view($new_message, true, $member_e, $_POST['i__id']), true),
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

        } elseif (!isset($_POST['edit_e__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing source ID',
            ));

        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {

            return view_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));

        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name'])==0 || intval($_FILES[$_POST['upload_type']]['size'])==0) {

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
                'x__creator' => $member_e['e__id'],
                'x__down' => $_POST['edit_e__id'],
                'x__message' => $cdn_status['cdn_url'],
            ));

            //Return CDN URL:
            return view_json(array(
                'status' => 1,
                'cdn_url' => $cdn_status['cdn_url'],
            ));

        }
    }

    function x_read(){

        //Validate/Fetch idea:
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
        } elseif(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        }

        //Valid x Type?
        if($is[0]['i__type']==6677){
            $x__type = 4559;
        } elseif($is[0]['i__type']==30874){
            $x__type = 31810;
        } elseif(!in_array($is[0]['i__type'], $this->config->item('n___34826'))){
            return view_json(array(
                'status' => 0,
                'message' => 'Not a read-only idea type',
            ));
        }

        if(!count($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__creator' => $member_e['e__id'],
            'x__left' => $is[0]['i__id'],
        )))){
            $this->X_model->mark_complete($_POST['top_i__id'], $is[0], array(
                'x__type' => $x__type, //Read Statement
                'x__creator' => $member_e['e__id'],
            ));
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Saved & Next...',
        ));

    }

    function x_skip(){


        //Validate/Fetch idea:
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
        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));

        //Log Skip:
        $this->X_model->mark_complete(intval($_POST['top_i__id']), $is[0], array(
            'x__type' => 31022, //Skipped
            'x__creator' => $member_e['e__id'],
        ));
        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Skipped & Next...',
        ));

    }

    function x_free_ticket(){

        //Validate/Fetch idea:
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
        } elseif (!isset($_POST['paypal_quantity'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Count.',
            ));
        } elseif(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        } elseif($is[0]['i__type']!=26560){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea Type',
            ));
        }


        if(!count($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__creator' => $member_e['e__id'],
            'x__left' => $is[0]['i__id'],
        )))){
            $this->X_model->mark_complete($_POST['top_i__id'], $is[0], array(
                'x__type' => 31809, //FREE Ticket
                'x__weight' => $_POST['paypal_quantity'],
                'x__creator' => $member_e['e__id'],
            ));
        }


        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Saved & Next...',
        ));

    }


    function x_write(){

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
        } elseif (!isset($_POST['x_write'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Response Variable.',
            ));
        }


        //Validate/Fetch idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        } elseif (!in_array($is[0]['i__type'] , $this->config->item('n___34849'))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Idea Type is not writable.',
            ));
        }



        //Any Preg Remove?
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right' => $_POST['i__id'],
            'x__up' => 32103, //Preg Remove
        )) as $preg_query){
            $new_form = preg_replace($preg_query['x__message'], "", $_POST['x_write'] );
            if($new_form != $_POST['x_write']) {
                $_POST['x_write'] = $new_form;
                //Log preg removal
                $this->X_model->create(array(
                    'x__type' => 32103, //Preg Remove
                    'x__creator' => $member_e['e__id'],
                    'x__message' => '['.$_POST['x_write'].'] Transformed to ['.$new_form.']',
                    'x__left' => $_POST['top_i__id'],
                    'x__right' => $_POST['i__id'],
                ));
            }
        }

        $_POST['x_write'] = trim($_POST['x_write']);

        //Trying to Skip?
        if(!strlen($_POST['x_write'])){
            if(count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $_POST['i__id'],
                'x__up' => 28239, //Can Skip
            )))){
                //Log Skip:
                $this->X_model->mark_complete(intval($_POST['top_i__id']), $is[0], array(
                    'x__type' => 31022, //Skipped
                    'x__creator' => $member_e['e__id'],
                    'x__message' => $_POST['x_write'],
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

            $time_starts = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $_POST['i__id'],
                'x__up' => 26556, //Time Starts
            ), array(), 1);
            $time_ends = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $_POST['i__id'],
                'x__up' => 26557, //Time Ends
            ), array(), 1);

            if(!strtotime($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid time.',
                ));
            } elseif(count($time_starts) && strtotime($time_starts[0]['x__message'])<strtotime($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a date after '.$time_starts[0]['x__message'],
                ));
            } elseif(count($time_ends) && strtotime($time_ends[0]['x__message'])>strtotime($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a date before '.$time_ends[0]['x__message'],
                ));
            }

        } elseif ($is[0]['i__type']==31794) {

            $x__type = 31797; //Entered Number

            $min_value = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $_POST['i__id'],
                'x__up' => 31800, //Min Value
            ), array(), 1);
            $max_value = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $_POST['i__id'],
                'x__up' => 31801, //Max Value
            ), array(), 1);

            if(!is_numeric($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid number.',
                ));
            } elseif(count($min_value) && floatval($min_value[0]['x__message'])<floatval($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a number bigger than '.$min_value[0]['x__message'],
                ));
            } elseif(count($max_value) && floatval($max_value[0]['x__message'])>floatval($_POST['x_write'])){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Enter a number smaller than '.$max_value[0]['x__message'],
                ));
            }

        } elseif ($is[0]['i__type']==31795) {

            $x__type = 31799; //Entered URL

            if(!filter_var($_POST['x_write'], FILTER_VALIDATE_URL)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Error: Please enter a valid URL.',
                ));
            }

        } elseif ($is[0]['i__type']==6683) {

            $x__type = 6144; //Text Replied

        } elseif ($is[0]['i__type']==32603) {

            $x__type = 33614; //Agreement Signed

            if(strlen($_POST['x_write'])<5) {
                return view_json(array(
                    'status' => 0,
                    'message' => 'Legal Name is too short',
                ));
            } elseif(!substr_count($_POST['x_write'], ' ')){
                return view_json(array(
                    'status' => 0,
                    'message' => 'You must enter both your first name & last name!',
                ));
            }

            //Make sure full name is added as a Source Link Added (Needed for Sign Agreement):
            if(!count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7545, //Following Add
                'x__right' => $is[0]['i__id'],
                'x__up' => 30198,
            )))){
                $this->X_model->create(array(
                    'x__creator' => 14068, //Guest Member
                    'x__type' => 7545, //Following Add
                    'x__up' => 30198,
                    'x__right' => $is[0]['i__id'],
                ));
            }

            //Update Legal name with this name:
            $return = source_link_message(30198, $member_e['e__id'], $_POST['x_write']);

        } else {

            //Unknown type!
            $this->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__message' => 'x_write() Unknown text reply',
                'x__metadata' => array(
                    'post' => $_POST,
                ),
            ));
            return false;

        }


        //Any Preg Match?
        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right' => $_POST['i__id'],
            'x__up' => 26611, //Preg Match
        )) as $preg_query){
            if(!preg_match($preg_query['x__message'], $_POST['x_write'])) {

                //Do we have a custom message:
                $preg_query_message = $this->X_model->fetch(array(
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $_POST['i__id'],
                    'x__up' => 30998, //Preg Match Error
                ));

                $error_message = ( count($preg_query_message) && strlen($preg_query_message[0]['x__message']) ? $preg_query_message[0]['x__message'] : 'Invalid Input, Please try again...' );

                //Log preg match failure
                $this->X_model->create(array(
                    'x__type' => 30998, //Preg Match Error Message
                    'x__creator' => $member_e['e__id'],
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
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left' => $is[0]['i__id'],
            'x__creator' => $member_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__access' => 6173, //Transaction Removed
            ), $member_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
        }

        //Save new answer:
        $this->X_model->mark_complete(intval($_POST['top_i__id']), $is[0], array(
            'x__type' => $x__type,
            'x__creator' => $member_e['e__id'],
            'x__message' => $_POST['x_write'],
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

        $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
            'x__type' => 14576, //MODAL VIEWED
            'x__up' => 13571,
            'x__reference' => $_POST['x__id'],
        ));

        $fetch_xs = $this->X_model->fetch(array(
            'x__id' => $_POST['x__id'],
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
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
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ));
        if (count($e_x) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'INVALID TRANSACTION ID',
            ));
        }

        //Transaction content change?
        if ($e_x[0]['x__message']==$_POST['x__message']) {

            //Transaction content has not changed:
            $x__message = $e_x[0]['x__message'];

        } else {

            //Change transaction type ONLY if source link:
            if(!in_array($e_x[0]['x__type'], $this->config->item('n___32292'))){

                $x__message = $_POST['x__message'];
                $this->X_model->update($_POST['x__id'], array(
                    'x__message' => $x__message,
                ), $member_e['e__id'], 26191 /* SOURCE CONTENT UPDATE */);

            } else {

                //it is a source link! We should update this:

                //Update variables:
                $x__message = $_POST['x__message'];

                $this->X_model->update($_POST['x__id'], array(
                    'x__message' => $x__message,
                    'x__type' => 4230,
                ), $member_e['e__id'], 10657 /* SOURCE LINK CONTENT UPDATE */);

            }
        }


        //Show success:
        $detect_data_type = detect_data_type($x__message);
        return view_json(array(
            'status' => 1,
            'x__message' => preview_x__message($x__message, $detect_data_type['x__type']),
            'x__message_final' => $x__message, //In case content was updated
        ));

    }

    function load_platform_stats(){
        //Count transactions:
        $already_added = array();
        $return_array = array();
        foreach($this->config->item('e___33292') as $x__type => $m) {
            foreach($this->config->item('e___'.$x__type) as $x__type2 => $m2) {
                if(!in_array($x__type2, $already_added)){
                    array_push($already_added , $x__type2);
                    array_push($return_array , array(
                        'sub_id' => $x__type2,
                        'sub_counter' => number_format(count_interactions($x__type2), 0),
                    ));
                }
            }
        }
        return view_json($return_array);
    }



    function update_dropdown(){

        if(is_array($_POST['o__id'])){
            $mass_result = array();
            foreach($_POST['o__id'] as $o__id){
                array_push($mass_result, $this->X_model->update_dropdown($_POST['focus_id'],$o__id,$_POST['element_id'],$_POST['new_e__id'],$_POST['migrate_s__id'],$_POST['x__id']));
            }
            return view_json($mass_result);
        } else {
            return view_json($this->X_model->update_dropdown($_POST['focus_id'],$_POST['o__id'],$_POST['element_id'],$_POST['new_e__id'],$_POST['migrate_s__id'],$_POST['x__id']));
        }

    }



    function x_select(){
        return view_json($this->X_model->x_select($_POST['top_i__id'], $_POST['focus_id'], ( !isset($_POST['selection_i__id']) || !count($_POST['selection_i__id']) ? array() : $_POST['selection_i__id'] )));
    }



    function e_reset_discoveries($e__id = 0){

        $member_e = superpower_unlocked(null, true);
        $e__id = ( $e__id > 0 || !$member_e ? $e__id : $member_e['e__id'] );

        //Fetch their current progress transactions:
        $progress_x = $this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___31777')) . ')' => null, //EXPANDED DISCOVERIES
            'x__creator' => $e__id,
        ), array(), 0);

        if(count($progress_x) > 0){

            //Yes they did have some:
            $message = 'Deleted all '.count($progress_x).' discoveries';

            //Log transaction:
            $clear_all_x = $this->X_model->create(array(
                'x__message' => $message,
                'x__type' => 6415,
                'x__creator' => $e__id,
            ));

            //Delete all progressions:
            foreach($progress_x as $progress_x){
                $this->X_model->update($progress_x['x__id'], array(
                    'x__access' => 6173, //Transaction Removed
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
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if (!count($is)) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //Save IDEA:
        $x = $this->X_model->create(array(
            'x__creator' => $member_e['e__id'],
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
            'x__access' => 6173, //DELETED
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
        foreach($_POST['new_x_order'] as $x__weight => $x__id){
            if(intval($x__id) > 0 && intval($x__weight) > 0){
                //Update order of this transaction:
                if($this->X_model->update(intval($x__id), array(
                    'x__weight' => $x__weight,
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