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

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid URL',
            ));

        } elseif (!$detected_x_type['status'] && isset($detected_x_type['url_previously_existed']) && $detected_x_type['url_previously_existed']) {

            //See if this is duplicate to either transaction:
            $e_x = $this->X_model->fetch(array(
                'x__id' => $_POST['x__id'],
                'x__type IN (' . join(',', $this->config->item('n___4537')) . ')' => null, //Member URL Transactions
            ));

            //Are they both different?
            if (count($e_x) < 1 || ($e_x[0]['x__up'] != $detected_x_type['e_url']['e__id'] && $e_x[0]['x__down'] != $detected_x_type['e_url']['e__id'])) {
                //return error:
                return view_json($detected_x_type);
            }

        }



        return view_json(array(
            'status' => 1,
            'x__type_preview' => '<b class="css__title">' . $e___4592[$detected_x_type['x__type']]['m__cover'] . ' ' . $e___4592[$detected_x_type['x__type']]['m__title'] . '</b>',
            'x__message_preview' => ( in_array($detected_x_type['x__type'], $this->config->item('n___12524')) ? '<span class="paddingup">'.view_x__message($_POST['x__message'], $detected_x_type['x__type'], null, true).'</span>' : ''),
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
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));
            if(!count($is)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));
            }

            //Validate Idea Outcome:
            $i__title_validation = i__title_validate($_POST['field_value']);
            if(!$i__title_validation['status']){
                //We had an error, return it:
                return view_json(array_merge($i__title_validation, array(
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
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
            ));
            if(!count($es)){
                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID.',
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

        } elseif($_POST['cache_e__id']==4356 /* DISCOVERY TIME */){

            $is = $this->I_model->fetch(array(
                'i__id' => $_POST['s__id'],
                'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ));

            if(!count($is)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || $_POST['field_value'] < 0){

                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m__title'].' must be a number greater than zero.',
                    'original_val' => $is[0]['i__duration'],
                ));

            } elseif($_POST['field_value'] > view_memory(6404,4356)){

                $hours = rtrim(number_format((view_memory(6404,4356)/3600), 1), '.0');
                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m__title'].' should be less than '.$hours.' Hour'.view__s($hours).', or '.view_memory(6404,4356).' Seconds long. You can break down your idea into smaller ideas.',
                    'original_val' => $is[0]['i__duration'],
                ));

            } elseif($_POST['field_value'] < view_memory(6404,12427)){

                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m__title'].' should be at-least '.view_memory(6404,12427).' Seconds long. It takes time to discover ideas ;)',
                    'original_val' => $is[0]['i__duration'],
                ));

            } else {

                //All good, go ahead and update:
                $this->I_model->update($_POST['s__id'], array(
                    'i__duration' => $_POST['field_value'],
                ), true, $member_e['e__id']);

                return view_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_e__id']==4358 /* DISCOVERY MARKS */){

            //Fetch/Validate Transaction:
            $x = $this->X_model->fetch(array(
                'x__id' => $_POST['s__id'],
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            ));
            $x__metadata = unserialize($x[0]['x__metadata']);
            if(!$x__metadata){
                $x__metadata = array();
            }

            if(!count($x)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Transaction ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < view_memory(6404,11056) ||  $_POST['field_value'] > view_memory(6404,11057)){

                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m__title'].' must be an integer between '.view_memory(6404,11056).' and '.view_memory(6404,11057).'.',
                    'original_val' => ( isset($x__metadata['tr__assessment_points']) ? $x__metadata['tr__assessment_points'] : 0 ),
                ));

            } else {

                //All good, go ahead and update:
                $this->X_model->update($_POST['s__id'], array(
                    'x__metadata' => array_merge($x__metadata, array(
                        'tr__assessment_points' => intval($_POST['field_value']),
                    )),
                ), $member_e['e__id'], 10663 /* Idea Transaction updated Marks */, $e___12112[$_POST['cache_e__id']]['m__title'].' updated'.( isset($x__metadata['tr__assessment_points']) ? ' from [' . $x__metadata['tr__assessment_points']. ']' : '' ).' to [' . $_POST['field_value']. ']');

                return view_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_e__id']==4735 /* UNLOCK MIN SCORE */ || $_POST['cache_e__id']==4739 /* UNLOCK MAX SCORE */){

            //Fetch/Validate Transaction:
            $x = $this->X_model->fetch(array(
                'x__id' => $_POST['s__id'],
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            ));
            $x__metadata = ( strlen($x[0]['x__metadata']) && is_array(unserialize($x[0]['x__metadata'])) ? unserialize($x[0]['x__metadata']) : array() );
            $field_name = ( $_POST['cache_e__id']==4735 ? 'tr__conditional_score_min' : 'tr__conditional_score_max' );

            if(!count($x)){

                return view_json(array(
                    'status' => 0,
                    'message' => 'Invalid Transaction ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < 0 || $_POST['field_value'] > 100){

                return view_json(array(
                    'status' => 0,
                    'message' => $e___12112[$_POST['cache_e__id']]['m__title'].' must be an integer between 0 and 100.',
                    'original_val' => ( isset($x__metadata[$field_name]) ? $x__metadata[$field_name] : '' ),
                ));

            } else {

                //All good, go ahead and update:
                $this->X_model->update($_POST['s__id'], array(
                    'x__metadata' => array_merge($x__metadata, array(
                        $field_name => intval($_POST['field_value']),
                    )),
                ), $member_e['e__id'], 10664 /* Idea Transaction updated Score */, $e___12112[$_POST['cache_e__id']]['m__title'].' updated'.( isset($x__metadata[$field_name]) ? ' from [' . $x__metadata[$field_name].']' : '' ).' to [' . $_POST['field_value'].']');

                return view_json(array(
                    'status' => 1,
                ));

            }

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
                    echo '<div class="row justify-content-center">';
                    $ids = array();
                    foreach(view_coins_e(12274, $_POST['coin__id'], 1) as $e) {
                        array_push($ids, $e['e__id']);
                        echo view_e(11029, $e);
                    }
                    echo '</div>';
                    echo '<div class="dotransparent">'.join(',',$ids).'</div>';
                }

            } elseif($_POST['apply_id']==12589){

                //idea list:
                $is_next = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                    'x__left' => $_POST['coin__id'],
                ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
                $counter = count($is_next);

                if(!$counter){
                    echo '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>No Ideas yet...</div>';
                } else {
                    echo '<div class="msg alert" role="alert"><span class="icon-block"><i class="fas fa-list"></i></span>Will apply to '.$counter.' idea'.view__s($counter).':</div>';
                    echo '<div class="row justify-content-center">';
                    $ids = array();
                    foreach($is_next as $i) {
                        array_push($ids, $i['i__id']);
                        echo view_i(13542, 0, null, $i);
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
        if($member_e && in_array($current_is[0]['i__type'], $this->config->item('n___4559')) && !count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE ALREADY?
                'x__source' => $member_e['e__id'],
                'x__left' => $current_is[0]['i__id'],
            )))){
            $this->X_model->mark_complete($top_i__id, $current_is[0], array(
                'x__type' => 4559, //DISCOVERY MESSAGES
                'x__source' => $member_e['e__id'],
            ));
        }

        if($member_e && in_array($next_is[0]['i__type'], $this->config->item('n___4559')) && !count($this->X_model->fetch(array(
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE ALREADY?
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

        if(!in_array($i__id, $this->X_model->ids($member_e['e__id']))){

            //Make sure they can start this:
            $is = $this->I_model->fetch(array(
                'i__id' => $i__id,
            ));
            if(!count($is)){
                return redirect_message('/', '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Invalid idea ID</div>', true);
            }

            if(!i_is_startable($is[0])){

                //Try to find the top registrable idea:
                $top_startable = $this->I_model->top_startable($is[0]);
                if(count($top_startable)){

                    return redirect_message('/'.$top_startable[0]['i__id']);

                } else {

                    //Cannot be started:
                    $this->X_model->create(array(
                        'x__source' => $member_e['e__id'],
                        'x__left' => $i__id,
                        'x__type' => 14604,
                    ));

                    return redirect_message('/'.$i__id, '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This idea is currently not active & cannot be started at this time.</div>');

                }
            }

            //All good, add to start:
            $next_i__id = $this->X_model->start($member_e['e__id'], $i__id);

            if(!$next_i__id){
                //Failed to add to read:
                return redirect_message(home_url());
            }
        }

        //Go to this newly added idea:
        return redirect_message('/'.$i__id.'/'.$next_i__id);

    }

    function paypal_refund(){

        $member_e = superpower_unlocked();
        if (!$member_e) {

            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));

        } elseif (!isset($_POST['x__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing X ID',
            ));

        } elseif (!isset($_POST['refund_total']) || doubleval($_POST['refund_total'])<=0) {

            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Refund Amount ['.$_POST['refund_total'].']',
            ));

        }

        $transactions = $this->X_model->fetch(array(
            'x__id' => $_POST['x__id'],
            'x__type' => 26595,
        ));
        if(!count($transactions)){
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid X ID',
            ));
        }

        $_POST['refund_total'] = doubleval($_POST['refund_total']);
        $x__metadata = unserialize($transactions[0]['x__metadata']);
        $cred_paypal = $this->config->item('cred_paypal');
        $arrays = array(
            'amount' => array(
                'total' => "95.00",
                'currency' => $x__metadata['mc_currency'],
            ),
        );
        http_build_query_for_curl( $arrays, $post );

        $ch=curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode($cred_paypal['client_id'].":".$cred_paypal['secret_key']),
        ));
        curl_setopt($ch, CURLOPT_URL, "https://api.paypal.com/v1/payments/sale/".$x__metadata['txn_id']."/refund");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        $y=json_decode($result,true);


        /*
        $this->X_model->update($transactions[0]['x__id'], array(
            'x__status' => 6173, //Transaction Deleted
            'x__metadata' => $y,
        ), $member_e['e__id'], 27794); //Paypal Refund
        */

        return view_json(array(
            'status' => 1,
            'arrays' => $arrays,
            'post' => $post,
            'y' => $y,
            'message' => $_POST['refund_total'].' Refunded ['.( isset($y->state) ? $y->state : 'Error' ).']',
        ));

    }

    function x_next($top_i__id, $i__id = 0){

        if(!$i__id){
            die('missing valid ID');
        }

        $member_e = superpower_unlocked();
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
            'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
        ));

        if(!$member_e){
            return redirect_message('/-4269?i__id='.$top_i__id);
        } elseif(!$this->X_model->ids($member_e['e__id'], $top_i__id)) {
            return redirect_message('/'.$top_i__id);
        } elseif(!count($is)) {
            return redirect_message('/'.$top_i__id, '<div class="msg alert alert-info" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>This idea is not published yet</div>');
        }


        //Should we check for auto next redirect if empty? Only if this is a selection:
        $next_url = null;
        if(!count($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12229')) . ')' => null, //DISCOVERY COMPLETE
            'x__source' => $member_e['e__id'],
            'x__left' => $is[0]['i__id'],
        )))){
            //Not yet completed, should we complete?
            if(in_array($is[0]['i__type'], $this->config->item('n___4559'))){
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


        //DO we have a hard redirect?
        if($i__id > 0){
            foreach($this->X_model->fetch(array(
                'x__type IN (' . join(',', $this->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
                'x__up' => 27664, //Hard Redirect
                'x__right' => $i__id,
                'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            )) as $hard_redirect){
                if(strlen($hard_redirect['x__message']) > 0){

                    //Save transaction:
                    $this->X_model->create(array(
                        'x__source' => $member_e['e__id'],
                        'x__type' => 27664, //Hard Redirect
                        'x__right' => $i__id,
                        'x__message' => $hard_redirect['x__message'],
                    ));

                    return redirect_message($hard_redirect['x__message']);

                }
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
            return redirect_message('/-14709?i__id='.$top_i__id);

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
        } elseif(!$this->X_model->ids($member_e['e__id'], $top_i__id)) {
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





    function x_layout($top_i__id, $i__id)
    {

        /*
         *
         * Enables a Member to DISCOVER an IDEA
         * on the public web
         *
         * */

        //Fetch data:
        $member_e = superpower_unlocked();
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));

        if($top_i__id > 0){
            $top_is = $this->I_model->fetch(array(
                'i__id' => $top_i__id,
            ));
        } elseif($member_e) {

            //See if this idea belongs to any of this members starting points, if so, redirect:
            $starting_is = $this->X_model->ids($member_e['e__id']);
            if(in_array($i__id, $starting_is)){
                //This is a starting point itself, so go there:
                return redirect_message('/'.$i__id.'/'.$i__id);
            }

            //Move recursively up to see if we cross any starting points:
            $parent_is = $this->I_model->recursive_parent_ids($i__id);
            $crossovers = array_intersect($starting_is, $parent_is);
            if(count($crossovers) > 0){
                //Just go to the first one for now:
                //TODO If more than one option give the user choice on where to start
                return redirect_message('/'.$crossovers[0].'/'.$i__id);
            }

        }

        //Make sure we found it:
        if ( $top_i__id > 0 && !count($top_is) ) {

            return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Top Idea ID ' . $top_i__id . ' not found</div>');

        } elseif ( !count($is) ) {

            return redirect_message( ( $top_i__id > 0 ? '/'.$top_is[0]['i__id'] : home_url() ), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>Idea ID ' . $i__id . ' not found</div>');

        } elseif($top_i__id > 0 && !in_array($top_is[0]['i__type'], $this->config->item('n___7355') /* PUBLIC */)){

            return redirect_message((superpower_unlocked(10939) ? '/~' . $top_i__id : home_url()), '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This top idea is not published yet.</div>');

        } elseif(!in_array($is[0]['i__type'], $this->config->item('n___7355') /* PUBLIC */)){

            return redirect_message((superpower_unlocked(10939) ? '/~' . $i__id : home_url()), '<div class="msg alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>This idea is not published yet.</div>');

        }

        //Determine Member:
        /*
        $member_e = false;
        if(isset($_GET['load__e']) && superpower_active(14005, true)){

            //Fetch This Member
            $e_filters = $this->E_model->fetch(array(
                'e__id' => $_GET['load__e'],
                'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
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
            'i_focus' => $is[0],
        ));
        $this->load->view('x_layout', array(
            'i_top' => ( $top_i__id > 0 ? $top_is[0] : false ),
            'i_focus' => $is[0],
            'member_e' => $member_e,
        ));
        $this->load->view('footer');

    }



    function x_upload()
    {

        //TODO: MERGE WITH FUNCTION i_note_add_file()

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
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
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
        } elseif (!isset($_POST['x_reply']) || !strlen($_POST['x_reply'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Write a response before going next. Write `skip` if you wish not to respond.',
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

        //Delete previous answer(s):
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___6255')) . ')' => null, //DISCOVERY COIN
            'x__left' => $is[0]['i__id'],
            'x__source' => $member_e['e__id'],
        )) as $x_progress){
            $this->X_model->update($x_progress['x__id'], array(
                'x__status' => 6173, //Transaction Removed
            ), $member_e['e__id'], 12129 /* DISCOVERY ANSWER DELETED */);
        }

        //Save new answer:
        $this->X_model->mark_complete(intval($_POST['top_i__id']), $is[0], array(
            'x__type' => 6144,
            'x__source' => $member_e['e__id'],
            'x__message' => $_POST['x_reply'],
        ));

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => 'Answer Saved',
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

                } elseif (in_array($detected_x_type['x__type'], $this->config->item('n___4537')) && isset($detected_x_type['url_root']) /* This prevents issues with /local_urls that start with / */) {

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
        $return_array = array();
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
                'i__cover' => trim($_POST['coin__cover']),
            ), true, $member_e['e__id']);

        } elseif($_POST['coin__type']==12274){

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

        //Maintain a manual index as a hack for the Idea/Source tables for now:
        $e___12079 = $this->config->item('e___12079');

        //Authenticate Member:
        $member_e = superpower_unlocked();
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['o__id']) || intval($_POST['o__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Target ID',
            ));
        } elseif (!isset($_POST['focus__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Loaded ID',
            ));
        } elseif (!isset($_POST['x__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Transaction ID',
            ));
        } elseif (!isset($_POST['migrate_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Migrate Idea ID',
            ));
        } elseif (!isset($_POST['element_id']) || intval($_POST['element_id']) < 1 || !count($this->config->item('n___'.$_POST['element_id'])) || !isset($e___12079[$_POST['element_id']])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Variable ID ['.$_POST['element_id'].']',
            ));
        } elseif (!isset($_POST['new_e__id']) || intval($_POST['new_e__id']) < 1 || !in_array($_POST['new_e__id'], $this->config->item('n___'.$_POST['element_id']))) {
            return view_json(array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            ));
        }


        //See if anything is being deleted:
        $deletion_redirect = null;
        $delete_element = null;
        $status = 0;

        if($_POST['element_id']==4486 && $_POST['x__id'] > 0){

            //IDEA LINK TYPE
            $status = $this->X_model->update($_POST['x__id'], array(
                'x__type' => $_POST['new_e__id'],
            ), $member_e['e__id'], 13962);

        } elseif($_POST['element_id']==6177){

            //SOURCE TYPE

            //Delete?
            if(!in_array($_POST['new_e__id'], $this->config->item('n___7358'))){

                //Determine what to do after deleted:
                if($_POST['o__id'] == $_POST['focus__id']){

                    //Find Published Parents:
                    foreach($this->X_model->fetch(array(
                        'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'e__type IN (' . join(',', $this->config->item('n___7358')) . ')' => null, //ACTIVE
                        'x__down' => $_POST['o__id'],
                    ), array('x__up'), 1, 0, array('e__spectrum' => 'DESC')) as $profile_e) {
                        $deletion_redirect = '/@'.$profile_e['e__id'];
                    }

                    //If still not found, go to main page if no parent found:
                    if(!$deletion_redirect){
                        $deletion_redirect = home_url();
                    }

                } else {

                    //Just delete from UI using JS:
                    $delete_element = '.coin___12274_' . $_POST['o__id'];

                }

                //Delete all transactions:
                $this->E_model->remove($_POST['o__id'], $member_e['e__id']);

            }

            //Update:
            $status = $this->E_model->update($_POST['o__id'], array(
                'e__type' => $_POST['new_e__id'],
            ), true, $member_e['e__id']);

        } elseif($_POST['element_id']==4737){

            //IDEA TYPE

            //Delete?
            if(!in_array($_POST['new_e__id'], $this->config->item('n___7356'))){

                //Determine what to do after deleted:
                if($_POST['o__id'] == $_POST['focus__id']){

                    //Find Published Parents:
                    foreach($this->X_model->fetch(array(
                        'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'i__type IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                        'x__right' => $_POST['o__id'],
                    ), array('x__left'), 1) as $previous_i) {
                        $deletion_redirect = '/~'.$previous_i['i__id'];
                    }

                    //If not found, find active parents:
                    if(!$deletion_redirect){
                        foreach($this->X_model->fetch(array(
                            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                            'x__right' => $_POST['o__id'],
                        ), array('x__left'), 1) as $previous_i) {
                            $deletion_redirect = '/~'.$previous_i['i__id'];
                        }
                    }

                    //If still not found, go to main page if no parent found:
                    if(!$deletion_redirect){
                        $deletion_redirect = home_url();
                    }

                } else {

                    //Just delete from UI using JS:
                    $delete_element = '.coin___12273_' . $_POST['o__id'];

                }

                //Delete all transactions:
                $this->I_model->remove($_POST['o__id'] , $member_e['e__id'], $_POST['migrate_i__id']);

            }


            //Delete only if Migration request is successful:
            if(!intval($_POST['migrate_i__id']) || count($this->I_model->fetch(array(
                    'i__id' => $_POST['migrate_i__id'],
                    'i__type IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
                )))){
                //Update Idea:
                $status = $this->I_model->update($_POST['o__id'], array(
                    'i__type' => $_POST['new_e__id'],
                ), true, $member_e['e__id']);
            }


        }

        return view_json(array(
            'status' => intval($status),
            'deletion_redirect' => $deletion_redirect,
            'delete_element' => $delete_element,
        ));

    }



    function x_select(){

        $member_e = superpower_unlocked();
        $nothing_seected = !isset($_POST['selection_i__id']) || !count($_POST['selection_i__id']);
        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(),
            ));
        } elseif (!isset($_POST['focus__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea id.',
            ));
        } elseif (!isset($_POST['focus_i__type'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing idea type.',
            ));
        } elseif (!isset($_POST['top_i__id'])) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top idea id.',
            ));
        } elseif (in_array($_POST['focus_i__type'], $this->config->item('n___14958')) && $nothing_seected) {
            return view_json(array(
                'status' => 0,
                'message' => 'You must select an answer before going next.',
            ));
        }

        //We have something to save:
        return view_json($this->X_model->x_answer($member_e['e__id'], $_POST['top_i__id'], $_POST['focus__id'], ( $nothing_seected ? array() : $_POST['selection_i__id'] )));

    }




    function e_reset_discoveries($e__id = 0){

        $member_e = superpower_unlocked(null, true);
        $e__id = ( $e__id > 0 || !$member_e ? $e__id : $member_e['e__id'] );

        //Fetch their current progress transactions:
        $progress_x = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12227')) . ')' => null,
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
        return redirect_message(home_url(), '<div class="msg alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-trash-alt"></i></span>'.$message.'</div>');

    }


    function i_reset_discoveries(){

        $member_e = superpower_unlocked(12701);

        if (!$member_e) {
            return view_json(array(
                'status' => 0,
                'message' => view_unauthorized_message(12701),
            ));
        } elseif (!isset($_POST['i__id']) || intval($_POST['i__id']) < 1) {
            return view_json(array(
                'status' => 0,
                'message' => 'Missing Starting Idea',
            ));
        }

        //Fetch their current progress transactions:
        $progress_x = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12227')) . ')' => null,
            'x__left' => $_POST['i__id'],
        ), array(), 0);

        if(!count($progress_x)){
            return view_json(array(
                'status' => 0,
                'message' => 'Nothing found to be removed',
            ));
        }

        $message = 'Removed all '.count($progress_x).' discoveries';

        //Log transaction:
        $clear_all_x = $this->X_model->create(array(
            'x__type' => 26001,
            'x__source' => $member_e['e__id'],
            'x__left' => $_POST['i__id'],
            'x__message' => $message,
        ));

        //Delete all progressions:
        foreach($progress_x as $progress_x){
            $this->X_model->update($progress_x['x__id'], array(
                'x__status' => 6173, //Transaction Removed
                'x__reference' => $clear_all_x['x__id'], //To indicate when it was deleted
            ), $member_e['e__id'], 26001 /* Reset All discoveries */);
        }

        return view_json(array(
            'status' => 1,
            'message' => $message,
        ));

    }

    function x_save(){

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

        } elseif (!isset($_POST['top_i__id'])) {

            return view_json(array(
                'status' => 0,
                'message' => 'Missing Top Idea ID',
            ));

        } elseif(!$this->X_model->ids($member_e['e__id'], $_POST['top_i__id'])){

            return view_json(array(
                'status' => 0,
                'message' => 'Top Idea not in your discoveries',
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
            'x__type' => 12896, //SAVED
        ));

        //All Good:
        return view_json(array(
            'status' => 1,
            'x__id' => $x['x__id'],
        ));

    }


    function x_suggestion(){
        //Save Suggestion:
        $x = $this->X_model->create(array(
            'x__source' => intval($_POST['js_pl_id']),
            'x__type' => 14393,
            'x__down' => intval($_POST['sugg_type']),
            'x__message' => trim($_POST['sugg_note']).' '.$_POST['sugg_url'],
        ));
        return view_json(array(
            'status' => 1,
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
        $delete_result = $this->X_model->delete($_POST['x__id']);


        if(!$delete_result['status']){
            return view_json($delete_result);
        } else {
            return view_json(array(
                'status' => 1,
            ));
        }
    }





    function x_sort_load()
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
        $results = array();
        foreach($_POST['new_x_order'] as $x__spectrum => $x__id){
            if(intval($x__id) > 0 && intval($x__spectrum) > 0){
                //Update order of this transaction:
                $results[$x__spectrum] = $this->X_model->update(intval($x__id), array(
                    'x__spectrum' => $x__spectrum,
                ), $member_e['e__id'], 4603);
                $updated++;
            }
        }

        //All good:
        return view_json(array(
            'status' => 1,
            'message' => $updated.' Ideas Sorted',
        ));
    }


}