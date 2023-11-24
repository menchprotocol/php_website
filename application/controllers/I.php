<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class I extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        universal_check();

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
        } elseif (!isset($_POST['do_template'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing template parameter',
            ));
        }

        return view_json($this->I_model->recursive_clone(intval($_POST['i__id']), intval($_POST['do_template']), $member_e['e__id']));

    }


    function i_layout($i__id, $append_e__id = 0){

        //Validate/fetch Idea:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));
        if ( count($is) < 1) {
            return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>IDEA #' . $i__id . ' Not Found</div>');
        }

        $member_e = superpower_unlocked(10939); //Idea Pen?
        if(!$member_e){
            if(in_array($is[0]['i__access'], $this->config->item('n___31871'))){
                return redirect_message('/'.$i__id);
            } else {
                return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>IDEA #' . $i__id . ' is not published yet.</div>');
            }
        }

        //Import Discoveries?
        $flash_message = '';
        if($append_e__id>0){

            $completed = 0;
            foreach($this->X_model->fetch(array(
                'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__left' => $i__id,
            ), array(), 0) as $x){
                if(!count($this->X_model->fetch(array(
                    'x__up' => $append_e__id,
                    'x__down' => $x['x__creator'],
                    'x__message' => $x['x__message'],
                    'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__access IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){
                    //Add source link:
                    $completed++;
                    $this->X_model->create(array(
                        'x__creator' => ($member_e ? $member_e['e__id'] : $x['x__creator']),
                        'x__up' => $append_e__id,
                        'x__down' => $x['x__creator'],
                        'x__message' => $x['x__message'],
                        'x__type' => 4230,
                    ));
                }
            }


            $flash_message = '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> '.$completed.' sources who played this idea added to @'.$append_e__id.'</div>';

        }

        $e___14874 = $this->config->item('e___14874'); //Mench Cards

        //Load views:
        $this->load->view('header', array(
            'title' => view_i_title($is[0], true).' | '.$e___14874[12273]['m__title'],
            'i' => $is[0],
            'flash_message' => $flash_message,
        ));
        $this->load->view('i_layout', array(
            'i' => $is[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }



    function i_navigate($previous_i__id, $current_i__id, $action){

        $trigger_next = false;
        $track_previous = 0;

        foreach($this->X_model->fetch(array(
            'x__access IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS
            'x__left' => $previous_i__id,
        ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $i){
            if($action=='next'){
                if($trigger_next){
                    return redirect_message('/~' . $i['i__id'] );
                }
                if($i['i__id']==$current_i__id){
                    $trigger_next = true;
                }
            } elseif($action=='previous'){
                if($i['i__id']==$current_i__id){
                    if($track_previous > 0){
                        return redirect_message('/~' . $track_previous );
                    } else {
                        //First item:
                        break;
                    }
                } else {
                    $track_previous = $i['i__id'];
                }
            }
        }

        if($previous_i__id > 0){
            return redirect_message('/~' .$previous_i__id );
        } else {
            die('Could not find matching idea');
        }

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
        } elseif (!isset($_POST['input__4736']) || !isset($_POST['link_i__id']) || intval($_POST['link_i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Follower Idea ID',
            ));
        }

        $validate_i__message = validate_i__message($_POST['input__4736']);
        if(!$validate_i__message['status']){
            //We had an error, return it:
            return view_json($validate_i__message);
        }


        if(!$_POST['link_i__id'] && !substr_count($_POST['input__4736'], ' ') && substr($_POST['input__4736'], 0, 1)=='#' && intval(substr($_POST['input__4736'],1)) > 0){
            $_POST['link_i__id'] = intval(substr($_POST['input__4736'],1));
        }

        $x_i = array();

        if($_POST['link_i__id'] > 0){
            //Fetch transaction idea to determine idea type:
            $x_i = $this->I_model->fetch(array(
                'i__id' => intval($_POST['link_i__id']),
                'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            ));
            if(count($x_i)==0){
                //validate Idea:
                return view_json(array(
                    'status' => 0,
                    'message' => 'Idea #'.$_POST['link_i__id'].' is not active',
                ));
            }
        }

        //All seems good, go ahead and try to create/link the Idea:
        return view_json($this->I_model->create_or_link($_POST['focus_card'], $_POST['x__type'], trim($_POST['input__4736']), $member_e['e__id'], $_POST['focus_id'], $_POST['link_i__id']));

    }

    function view_body_i(){
        //Authenticate Member:
        if (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1 || !isset($_POST['counter']) || !isset($_POST['x__type']) || intval($_POST['x__type']) < 1) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {
            echo view_body_i($_POST['x__type'], $_POST['counter'], $_POST['i__id']);
        }
    }

    function i_load_cover(){

        if (!isset($_POST['i__id']) || !isset($_POST['x__type']) || !isset($_POST['first_segment']) || !isset($_POST['counter'])) {
            echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Missing core variables</div>';
        } else {

            $ui = '';
            $listed_items = 0;
            if($_POST['x__type']==12274 || $_POST['x__type']==6255){

                //SOURCES
                $e___6177 = $this->config->item('e___6177'); //Source Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                $current_e = ( substr($_POST['first_segment'], 0, 1)=='@' ? intval(substr($_POST['first_segment'], 1)) : 0 );
                foreach(view_i_covers($_POST['x__type'], $_POST['i__id'], 1, false) as $e_e) {
                    if(isset($e_e['e__id'])){
                        $ui .= view_card('/@'.$e_e['e__id'], $e_e['e__id']==$current_e, $e_e['x__type'], $e_e['e__access'], view_cover($e_e['e__cover'], true), $e_e['e__title'], $e_e['x__message']);
                        $listed_items++;
                    }
                }

            } elseif($_POST['x__type']==11019 || $_POST['x__type']==12273){

                //IDEAS
                $e___31004 = $this->config->item('e___31004'); //Idea Status
                $e___4737 = $this->config->item('e___4737'); //Idea Types
                $e___4593 = $this->config->item('e___4593'); //Transaction Types
                $superpower_10939 = superpower_active(10939, true);
                $current_i = ( substr($_POST['first_segment'], 0, 1)=='~' ? intval(substr($_POST['first_segment'], 1)) : 0 );

                foreach(view_i_covers($_POST['x__type'], $_POST['i__id'], 1, false) as $next_i) {
                    if(isset($next_i['i__id'])){
                        $ui .= view_card('/~'.$next_i['i__id'], $next_i['i__id']==$current_i, $next_i['x__type'], null, ( in_array($next_i['i__type'], $this->config->item('n___32172')) ? $e___4737[$next_i['i__type']]['m__cover'] : '' ), view_i_title($next_i), $next_i['x__message']);
                        $listed_items++;
                    }
                }

            }

            if($listed_items < $_POST['counter']){
                //We have more to show:
                $ui .= view_more('/~'.$_POST['i__id'], false, '&nbsp;', '&nbsp;', '&nbsp;', 'View all '.number_format($_POST['counter'], 0));
            }

            echo $ui;
        }
    }



    function i_edit_save(){

        if(!isset($_POST['input__4736'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea message',
            ));

        } elseif(!isset($_POST['input__32337'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea hashtag',
            ));

        } elseif(!isset($_POST['i__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));

        } elseif (!write_access_i($_POST['i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'You are missing permission to write to this idea',
            ));

        }

        $is = $this->I_model->fetch(array(
            'i__id' => $_POST['i__id'],
            'i__access IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
        ));
        if(!count($is)){
            return view_json(array(
                'status' => 0,
                'message' => 'Idea Not Active',
            ));
        }

        //Validate Idea Message:
        $validate_i__message = validate_i__message($_POST['input__4736']);
        if(!$validate_i__message['status']){
            return view_json(array(
                'status' => 0,
                'message' => $validate_i__message['message'],
            ));
        }

        //Validate Idea Hashtag:
        $validate_handler = validate_handler($_POST['input__32337'], $_POST['i__id']);
        if(!$validate_handler['status']){
            return view_json(array(
                'status' => 0,
                'message' => $validate_handler['message'],
            ));
        }

        //All good, Save:
        $this->I_model->update($is[0]['i__id'], array(
            'i__message' => trim($_POST['input__4736']),
            'i__hashtag' => trim($_POST['input__32337']),
        ));

        //Update Search Index:
        update_algolia(12273, $is[0]['i__id']);


        return view_json(array(
            'status' => 1,
            'message_html' => view_text_links(trim($_POST['input__4736'])),
        ));

    }

}