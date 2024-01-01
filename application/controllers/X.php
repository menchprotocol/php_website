<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class X extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        auto_login();

    }

    function x_create(){
        return view_json($this->X_model->create($_POST));
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

        } elseif(!isset($_POST['s__id']) || !isset($_POST['cache_e__id']) || !isset($_POST['new_i__message'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
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


            $validate_e__title = validate_e__title($_POST['new_i__message']);
            if(!$validate_e__title['status']){
                return view_json(array_merge($validate_e__title, array(
                    'original_val' => $es[0]['e__title'],
                )));
            }

            //All good, go ahead and update:
            $this->E_model->update($es[0]['e__id'], array(
                'e__title' => $validate_e__title['e__title_clean'],
            ), true, $member_e['e__id']);

            //Reset member session data if this data belongs to the logged-in member:
            if ($es[0]['e__id']==$member_e['e__id']) {
                //Re-activate Session with new data:
                $es[0]['e__title'] = $validate_e__title['e__title_clean'];
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
            'x__down' => ( $_POST['apply_id']==4997 ? $_POST['s__id'] : 0 ),
            'x__right' => ( $_POST['apply_id']==12589 ? $_POST['s__id'] : 0 ),
        ));

        if(!isset($_POST['apply_id']) || !isset($_POST['s__id'])){
            echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing Core Data</div>';
        } else {
            if($_POST['apply_id']==4997){

                //Source list:
                $counter = view_e_covers(12274, $_POST['s__id'], 0, false);
                if(!$counter){
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Sources yet...</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' source'.view__s($counter).':</div>';
                    echo '<div class="row justify-content">';
                    $ids = array();
                    foreach(view_e_covers(12274, $_POST['s__id'], 1, true) as $e) {
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
                    'x__left' => $_POST['s__id'],
                ), array('x__right'), 0, 0, array('x__weight' => 'ASC'));
                $counter = count($is_next);

                if(!$counter){
                    echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Ideas yet...</div>';
                } else {
                    echo '<div class="alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' idea'.view__s($counter).':</div>';
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
                echo '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Unknown Apply ID</div>';
            }
        }
    }



    function x_start($focus_i__hashtag){

        //Adds Idea to the Members read

        $member_e = superpower_unlocked();
        $e___11035 = $this->config->item('e___11035'); //NAVIGATION

        //valid idea?
        $is = $this->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($focus_i__hashtag),
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if(!count($is)){
            return redirect_message('/', '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Idea #'.$focus_i__hashtag.' is not active</div>');
        }

        //Check to see if added to read for logged-in members:
        if(!$member_e){
            return redirect_message(view_app_link(4269).'?i__hashtag='.$focus_i__hashtag);
        }

        //Add this Idea to their read If not there:
        $next_i__hashtag = $focus_i__hashtag;

        if(!in_array($is[0]['i__id'], $this->X_model->started_ids($member_e['e__id']))){

            //is available?
            $i_is_available = i_is_available($is[0]['i__id'], true);
            if(!$i_is_available['status']){
                return redirect_message('/'.$i_is_available['return_i__hashtag'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i_is_available['message'].'</div>');
            }

            //Is startable?
            if(!count($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                'x__right' => $is[0]['i__id'],
                'x__up' => 4235,
            )))){
                return redirect_message('/'.$focus_i__hashtag, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This idea is not startable.</div>');
            }

            //Add Starting Point:
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 4235, //Get started
                'x__right' => $is[0]['i__id'],
                'x__left' => $is[0]['i__id'],
            ));

            //Mark as complete:
            $this->X_model->read_only_complete($member_e['e__id'], $is[0]['i__id'], $is[0]);

            //Now return next idea:
            $next_i__hashtag = $this->X_model->find_next($member_e['e__id'], $is[0]['i__hashtag'], $is[0]);
            if(!$next_i__hashtag){
                //Failed to add to read:
                return redirect_message(home_url());
            }
        }

        //Go to this newly added idea:
        return redirect_message('/'.$focus_i__hashtag.'/'.$next_i__hashtag);

    }


    function x_next($top_i__hashtag, $focus_i__hashtag){

        $member_e = superpower_unlocked();
        $is = $this->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($focus_i__hashtag),
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));

        if(!$member_e){
            return redirect_message(view_app_link(4269).'?i__hashtag='.$top_i__hashtag);
        } elseif(!$this->X_model->started_ids($member_e['e__id'], $top_i__hashtag)) {
            return redirect_message('/'.$top_i__hashtag);
        } elseif(!count($is)) {
            return redirect_message('/'.$top_i__hashtag, '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>Idea #'.$focus_i__hashtag.' is not currently active.</div>');
        }

        $i_is_available = i_is_available($is[0]['i__id'], true, false);
        if(!$i_is_available['status']){
            return redirect_message('/'.$top_i__hashtag.'/'.$i_is_available['return_i__hashtag'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>'.$i_is_available['message'].'</div>');
        }

        //Go to Next Idea:
        $next_i__hashtag = $this->X_model->find_next($member_e['e__id'], $is[0]['i__hashtag'], $is[0]);
        if($next_i__hashtag){

            return redirect_message('/'.$top_i__hashtag.'/'.$next_i__hashtag );

        } else {

            //Mark as Complete
            $this->X_model->create(array(
                'x__creator' => $member_e['e__id'],
                'x__type' => 14730, //COMPLETED 100%
                'x__right' => $is[0]['i__id'],
                //TODO Maybe log additional details like total ideas, time, etc...
            ));

            return redirect_message('/'.$top_i__hashtag);

            //TODO Go to Rating or Checkout App since the entire tree is discovered...

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
                if (in_array($_POST['x__type'], $this->config->item('n___11028'))) {
                    echo view_card_e($_POST['x__type'], $s);
                } else if ($_POST['x__type']==6255 || in_array($_POST['x__type'], $this->config->item('n___11020'))) {
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
                if (in_array($_POST['x__type'], $this->config->item('n___11020'))) {
                    echo view_card_i($_POST['x__type'], 0, $previous_i, $s, $focus_e);
                } else if ($_POST['x__type']==6255 || in_array($_POST['x__type'], $this->config->item('n___11028'))) {
                    echo view_card_e($_POST['x__type'], $s);
                }
            }
        }

    }





    function x_layout($top_i__hashtag=null, $focus_i__hashtag)
    {

        /*
         *
         * Enables a Member to DISCOVER an IDEA
         * on the public web
         *
         * */

        $flash_message = null;
        $member_e = superpower_unlocked();
        $focus_es = array();

        if($top_i__hashtag && $top_i__hashtag==$focus_i__hashtag){
            //Cleaner URL:
            return redirect_message('/'.$focus_i__hashtag);
        }

        if(isset($_GET['e__handle'])){
            $focus_es = $this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            ));
            if(!count($focus_es)){
                return redirect_message( home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid User Handler</div>');
            }
        } elseif($member_e){
            $focus_es[0] = $member_e;
        }

        //Validate Top Idea:
        $top_is = array();
        if($top_i__hashtag && count($focus_es)){
            $top_is = $this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower($top_i__hashtag),
            ));
            if ( !count($top_is) ) {
                return redirect_message(home_url(), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Top Idea #' . $top_i__hashtag . ' not found</div>');
            }
        }


        //Validate Focus Idea:
        $focus_is = $this->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($focus_i__hashtag),
        ));
        if ( !count($focus_is) ) {
            return redirect_message( ( $top_i__hashtag ? '/'.$top_i__hashtag : home_url() ), '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Idea #' . $focus_i__hashtag . ' is not active right now</div>');
        }


        //Log Link Click discovery if authenticated:
        if(
            isset($_GET['e__hash'])
            && count($focus_es) //We have a user
        ){

            //Validate Hash:
            if($_GET['e__hash'] == view_e__hash($focus_es[0]['e__handle'])){

                $this->X_model->mark_complete(29393, $focus_es[0]['e__id'], ( count($top_is) ? $top_is[0]['i__id'] : 0 ), $focus_is[0]);

                //Inform user of changes:
                $flash_message = '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>You have discovered this idea</div>';

                //If not logged in, log them in:
                if(!$member_e){
                    $this->E_model->activate_session($focus_es[0], true);
                }

            } else {

                $flash_message = '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>Invalid Hash: Idea could not be discovered at this time.</div>';

            }
        }



        //Has the user discovered this?
        $is_startable = count($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__right' => $focus_is[0]['i__id'],
            'x__up' => 4235,
        )));

        $x_completes = array();
        if(count($focus_es)) {

            //Fetch discovery
            $x_completes = $this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__creator' => $focus_es[0]['e__id'],
                'x__left' => $focus_is[0]['i__id'],
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ), array('x__right'));

            //Missing focus Idea?
            if(!$top_i__hashtag) {

                //Do we have a direct discovery?
                $this_discovery = null;
                foreach($x_completes as $x){
                    $this_discovery = $x['i__hashtag'];
                    break;
                }

                if($this_discovery){
                    //We have a discovery here, make sure its not the same as the starting point:
                    if($this_discovery!=$focus_i__hashtag){
                        return redirect_message('/'.$this_discovery.'/'.$focus_i__hashtag);
                    }
                } else {
                    //No discovery here, let's see if we can find any above:
                    $top_x_i__hashtag = $this->X_model->find_previous_discovered($focus_is[0]['i__id'], $focus_es[0]['e__id']);
                    if($top_x_i__hashtag){
                        return redirect_message('/'.$top_x_i__hashtag.'/'.$focus_i__hashtag);
                    }
                }
            }
        }


        //VIEW DISCOVERY
        $this->X_model->create(array(
            'x__creator' => ( count($focus_es) ? $focus_es[0]['e__id'] : 14068 ), //Guest Member
            'x__type' => 7610, //MEMBER VIEWED DISCOVERY
            'x__left' => ( count($top_is) ? $top_is[0]['i__id'] : 0 ),
            'x__right' => $focus_is[0]['i__id'],
        ));

        $this->load->view('header', array(
            'title' => view_i_title($focus_is[0], true).( count($top_is) ? ' > '.view_i_title($top_is[0],  true) : '' ),
            'flash_message' => $flash_message,
        ));


        $this->load->view('x_layout', array(
            'focus_i' => $focus_is[0],
            'top_i' => ( count($top_is) ? $top_is[0] : ( !$top_i__hashtag && $is_startable && count($x_completes) ? $focus_is[0] : array() ) ),
            'member_e' => ( count($focus_es) ? $focus_es[0] : array() ),
            'x_completes' => $x_completes,
            'is_startable' => $is_startable,
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
            ), array('x__right'), 0, 0, array('i__message' => 'ASC')) as $x) {
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

        $cdn_status = upload_to_cdn($temp_local, $member_e['e__id'], $_FILES[$_POST['upload_type']], true, view_i_title($is[0]).' BY '.$member_e['e__title']);
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
        $this->X_model->mark_complete(12117, $member_e['e__id'], $_POST['top_i__id'], $is[0], array(
            'x__message' => $cdn_status['cdn_url'],
        ));

        //All good:
        $e___11035 = $this->config->item('e___11035'); //NAVIGATION
        return view_json(array(
            'status' => 1,
            'message' => view_headline(13977, null, $e___11035[13977], $cdn_status['cdn_url'], true),
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

        } elseif (!isset($_POST['save_e__id'])) {

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
                'x__down' => $_POST['save_e__id'],
                'x__message' => $cdn_status['cdn_url'],
            ));

            //Return CDN URL:
            return view_json(array(
                'status' => 1,
                'cdn_url' => $cdn_status['cdn_url'],
            ));

        }
    }

    function read_only_complete(){

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
        if(count($is) < 1){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea not published.',
            ));
        }


        //Mark as complete?
        if($this->X_model->read_only_complete($member_e['e__id'], $_POST['top_i__id'], $is[0])){
            //All good:
            return view_json(array(
                'status' => 1,
                'message' => 'Saved & Next...',
            ));
        } else {
            return view_json(array(
                'status' => 0,
                'message' => 'Cannot complete this idea',
            ));
        }


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

        //Log Skipped:
        $this->X_model->mark_complete(31022, $member_e['e__id'], intval($_POST['top_i__id']), $is[0]);

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
            //Ticket Issued:
            $this->X_model->mark_complete(26595, $member_e['e__id'], $_POST['top_i__id'], $is[0], array(
                'x__weight' => $_POST['paypal_quantity'],
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
                $this->X_model->mark_complete(31022, $member_e['e__id'], intval($_POST['top_i__id']), $is[0], array(
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
                'x__up' => 42203, //Time Equal or Great Than
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
                'x__up' => 30198, //Full Name
            )))){
                $this->X_model->create(array(
                    'x__creator' => 14068, //Guest Member
                    'x__type' => 7545, //Following Add
                    'x__up' => 30198, //Full Name
                    'x__right' => $is[0]['i__id'],
                ));
            }

            //Update Legal name with this name:
            $return = e_link_message(30198, $member_e['e__id'], $_POST['x_write']);

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
        $this->X_model->mark_complete($x__type, $member_e['e__id'], intval($_POST['top_i__id']), $is[0], array(
            'x__message' => $_POST['x_write'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Saved & Next...',
        ));

    }



    function load_stats_33292(){

        $miscstats = '';

        //See if we have any idea or source targets to limit our stats:
        if(isset($_POST['e__handle']) && strlen($_POST['e__handle'])){

            //See stats for this source:
            $es = $this->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_POST['e__handle']),
            ));
            if(!count($es)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Handle',
                ));
            }

        } elseif(isset($_POST['i__hashtag']) && strlen($_POST['i__hashtag'])){

            //See stats for this idea:
            $is = $this->I_model->fetch(array(
                'LOWER(i__hashtag)' => strtolower($_POST['i__hashtag']),
            ));
            if(!count($is)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Hashtag',
                ));
            }

            $recursive_down_ids = $this->I_model->recursive_down_ids($is[0], 'ALL');

            //List stats:
            $miscstats .= '<div>Tree Ideas: '.number_format(count($recursive_down_ids['recursive_i_ids']), 0).'</div>';

        }


        //Count transactions:
        $return_array = array();
        foreach($this->config->item('e___33292') as $x__type1 => $m1) {
            $level1_total = 0;
            foreach($this->config->item('e___'.$x__type1) as $x__type2 => $m2) {
                $level2_total = 0;
                foreach($this->config->item('e___'.map_primary_links($x__type2)) as $x__type3 => $m3) {

                    if($x__type2==12273){

                        if(strlen($_POST['e__handle'])){

                            $sub_counter = $this->X_model->fetch(array(
                                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                                'x__up' => $es[0]['e__id'],
                                'i__type' => $x__type3,
                                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                            ), array('x__right'), 0, 0, array(), 'COUNT(x__id) as totals');

                        } elseif(strlen($_POST['i__hashtag'])){

                            //See stats for this idea:
                            $sub_counter = $this->I_model->fetch(array(
                                'i__type' => $x__type3,
                                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                                'i__id IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ')' => null,
                            ), 0, 0, array(), 'COUNT(i__id) as totals');

                        } else {

                            $sub_counter = $this->I_model->fetch(array(
                                'i__type' => $x__type3,
                                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
                            ), 0, 0, array(), 'COUNT(i__id) as totals');

                        }

                    } elseif($x__type2==12274){

                        if(strlen($_POST['e__handle'])){

                            $sub_counter = $this->X_model->fetch(array(
                                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'x__up' => $es[0]['e__id'],
                                'e__access' => $x__type3,
                            ), array('x__down'), 0, 0, array(), 'COUNT(x__id) as totals');

                        } elseif(strlen($_POST['i__hashtag'])){

                            //See stats for this idea:
                            $sub_counter = $this->X_model->fetch(array(
                                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                                'x__right IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ')' => null,
                                'e__access' => $x__type3,
                            ), array('x__up'), 0, 0, array(), 'COUNT(x__id) as totals');

                        } else {

                            $sub_counter = $this->E_model->fetch(array(
                                'e__access' => $x__type3,
                            ), 0, 0, array(), 'COUNT(e__id) as totals');

                        }

                    } else {

                        if(strlen($_POST['e__handle'])){

                            $sub_counter = $this->X_model->fetch(array(
                                'x__type' => $x__type3,
                                '( x__down = ' . $es[0]['e__id'] . ' OR x__up = ' . $es[0]['e__id'] . ' OR x__creator = ' . $es[0]['e__id'] . ' )' => null,
                                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

                        } elseif(strlen($_POST['i__hashtag'])){

                            $sub_counter = $this->X_model->fetch(array(
                                'x__type' => $x__type3,
                                '( x__left IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . ') OR x__right IN (' . join(',', $recursive_down_ids['recursive_i_ids']) . '))' => null,
                                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

                        } else {

                            $sub_counter = $this->X_model->fetch(array(
                                'x__type' => $x__type3,
                                'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

                        }

                    }

                    $level2_total += $sub_counter[0]['totals'];
                    array_push($return_array , array(
                        'sub_id' => $x__type3,
                        'sub_counter' => number_format($sub_counter[0]['totals'], 0),
                    ));

                }

                $level1_total += $level2_total;
                array_push($return_array , array(
                    'sub_id' => $x__type2,
                    'sub_counter' => number_format($level2_total, 0),
                ));

            }

            array_push($return_array , array(
                'sub_id' => $x__type1,
                'sub_counter' => number_format($level1_total, 0),
            ));

        }
        return view_json(array(
            'status' => 1,
            'return_array' => $return_array,
            'miscstats' => $miscstats,
        ));
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
        foreach($this->E_model->fetch(array('e__id' => $e__id)) as $e){
            return redirect_message('/@'.$e['e__handle'], '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');
        }

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





    function sort_i_load()
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