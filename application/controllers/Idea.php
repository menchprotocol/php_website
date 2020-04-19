<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Idea extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }


    function in_create(){

        $en_all_6201 = $this->config->item('en_all_6201'); //Idea Table
        $session_en = superpower_assigned(10939);
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['newIdeaTitle'])) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing '.$en_all_6201[4736]['m_name'],
            ));

        }

        //Validate Title:
        $in_titlevalidation = $this->IDEA_model->in_titlevalidate($_POST['newIdeaTitle']);
        if(!$in_titlevalidation['status']){
            //We had an error, return it:
            return echo_json($in_titlevalidation);
        }


        //Create Idea:
        $in = $this->IDEA_model->in_link_or_create($in_titlevalidation['in_cleaned_outcome'], $session_en['en_id']);

        //Also add to bookmarks:
        $this->DISCOVER_model->ln_create(array(
            'ln_type_source_id' => 10573, //Idea Bookmarks
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_next_idea_id' => $in['new_in_id'],
            'ln_parent_source_id' => $session_en['en_id'],
            'ln_content' => '@'.$session_en['en_id'],
        ), true);

        return echo_json(array(
            'status' => 1,
            'message' => 'Success. Redirecting now...',
            'in_id' => $in['new_in_id'],
        ));

    }

    function index(){
        //Idea Bookmarks
        $session_en = superpower_assigned(null, true);
        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH
        $this->load->view('header', array(
            'title' => $en_all_2738[4535]['m_name'],
            'session_en' => $session_en,
        ));
        $this->load->view('idea/idea_home');
        $this->load->view('footer');
    }

    function idea_coin($in_id){

        //Validate/fetch Idea:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        if ( count($ins) < 1) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert">IDEA #' . $in_id . ' Not Found</div>');
        }

        //Make sure user is logged in
        $session_en = superpower_assigned(null, (!in_array($ins[0]['in_status_source_id'], $this->config->item('en_ids_7355'))));
        if(!$session_en){
            return redirect_message('/'.$in_id);
        }



        if (superpower_assigned(12702) && isset($_POST['mass_action_en_id']) && isset($_POST['mass_value1_'.$_POST['mass_action_en_id']]) && isset($_POST['mass_value2_'.$_POST['mass_action_en_id']])) {

            //Process mass action:
            $process_mass_action = $this->IDEA_model->in_mass_update($in_id, intval($_POST['mass_action_en_id']), $_POST['mass_value1_'.$_POST['mass_action_en_id']], $_POST['mass_value2_'.$_POST['mass_action_en_id']], $session_en['en_id']);

            //Pass-on results to UI:
            $message = '<div class="alert '.( $process_mass_action['status'] ? 'alert-success' : 'alert-danger' ).'" role="alert">'.$process_mass_action['message'].'</div>';

        } else {

            //No mass action, just viewing...
            $message = null;
            $new_order = ( $this->session->userdata('session_page_count') + 1 );
            $this->session->set_userdata('session_page_count', $new_order);
            $this->DISCOVER_model->ln_create(array(
                'ln_creator_source_id' => $session_en['en_id'],
                'ln_type_source_id' => 4993, //Player Opened Idea
                'ln_next_idea_id' => $in_id,
                'ln_order' => $new_order,
            ));

        }



        //Load views:
        $this->load->view('header', array(
            'title' => $ins[0]['in_title'],
            'in' => $ins[0],
            'flash_message' => $message, //Possible mass-action message for UI:
        ));
        $this->load->view('idea/idea_coin', array(
            'in' => $ins[0],
            'session_en' => $session_en,
        ));
        $this->load->view('footer');

    }


    function in_request_invite($in_id){

        //Make sure it's a logged in player:
        $session_en = superpower_assigned(null, true);

        if(count($this->DISCOVER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id' => 12450,
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_next_idea_id' => $in_id,
        )))){
            return redirect_message('/idea/'.$in_id, '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>You have previously requested to join this idea. No further action is necessary.</div>');

        }

        //Inform moderators:
        $this->DISCOVER_model->ln_create(array(
            'ln_type_source_id' => 12450,
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_next_idea_id' => $in_id,
        ));

        //Go back to idea:
        return redirect_message('/idea/'.$in_id, '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-thumbs-up"></i></span>Successfully submitted your request to become a source for this idea. You will receive a confirmation once your request has been reviewed.</div>');

    }

    function in_become_source($in_id){

        //Make sure it's a logged in player:
        $session_en = superpower_assigned(10984, true);

        //Idea Source:
        $this->DISCOVER_model->ln_create(array(
            'ln_type_source_id' => 4983, //IDEA COIN
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_parent_source_id' => $session_en['en_id'],
            'ln_content' => '@'.$session_en['en_id'],
            'ln_next_idea_id' => $in_id,
        ));

        //Go back to idea:
        return redirect_message('/idea/'.$in_id, '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-thumbs-up"></i></span>SUCCESSFULLY JOINED</div>');

    }


    function in_update_text(){

        //Authenticate Player:
        $session_en = superpower_assigned();
        $en_all_12112 = $this->config->item('en_all_12112');

        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
                'original_val' => '',
            ));

        } elseif(!isset($_POST['in_ln__id']) || !isset($_POST['cache_en_id']) || !isset($_POST['field_value'])){

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_en_id']==4736 /* IDEA TITLE */){

            $ins = $this->IDEA_model->in_fetch(array(
                'in_id' => $_POST['in_ln__id'],
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
            ));
            if(!count($ins)){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));
            }

            //Validate Idea Outcome:
            $in_titlevalidation = $this->IDEA_model->in_titlevalidate($_POST['field_value']);
            if(!$in_titlevalidation['status']){
                //We had an error, return it:
                return echo_json(array_merge($in_titlevalidation, array(
                    'original_val' => $ins[0]['in_title'],
                )));
            } else {

                //All good, go ahead and update:
                $this->IDEA_model->in_update($_POST['in_ln__id'], array(
                    'in_title' => trim($_POST['field_value']),
                ), true, $session_en['en_id']);

                return echo_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4356 /* DISCOVER TIME */){

            $ins = $this->IDEA_model->in_fetch(array(
                'in_id' => $_POST['in_ln__id'],
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
            ));

            if(!count($ins)){

                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || $_POST['field_value'] < 0){

                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' must be a number greater than zero.',
                    'original_val' => $ins[0]['in_discover_time'],
                ));

            } elseif($_POST['field_value'] > config_var(12113)){

                $hours = rtrim(number_format((config_var(12113)/3600), 1), '.0');
                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' should be less than '.$hours.' Hour'.echo__s($hours).', or '.config_var(12113).' Seconds long. You can break down your idea into smaller ideas.',
                    'original_val' => $ins[0]['in_discover_time'],
                ));

            } elseif($_POST['field_value'] < config_var(12427)){

                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' should be at-least '.config_var(12427).' Seconds long. It takes time to discover ideas ;)',
                    'original_val' => $ins[0]['in_discover_time'],
                ));

            } else {

                //All good, go ahead and update:
                $this->IDEA_model->in_update($_POST['in_ln__id'], array(
                    'in_discover_time' => $_POST['field_value'],
                ), true, $session_en['en_id']);

                return echo_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4358 /* DISCOVER MARKS */){

            //Fetch/Validate Link:
            $lns = $this->DISCOVER_model->ln_fetch(array(
                'ln_id' => $_POST['in_ln__id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
            ));
            $ln_metadata = unserialize($lns[0]['ln_metadata']);

            if(!count($lns)){

                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Link ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < config_var(11056) ||  $_POST['field_value'] > config_var(11057)){

                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' must be an integer between '.config_var(11056).' and '.config_var(11057).'.',
                    'original_val' => ( isset($ln_metadata['tr__assessment_points']) ? $ln_metadata['tr__assessment_points'] : 0 ),
                ));

            } else {

                //All good, go ahead and update:
                $this->DISCOVER_model->ln_update($_POST['in_ln__id'], array(
                    'ln_metadata' => array_merge($ln_metadata, array(
                        'tr__assessment_points' => intval($_POST['field_value']),
                    )),
                ), $session_en['en_id'], 10663 /* Idea Link updated Marks */, $en_all_12112[$_POST['cache_en_id']]['m_name'].' updated'.( isset($ln_metadata['tr__assessment_points']) ? ' from [' . $ln_metadata['tr__assessment_points']. ']' : '' ).' to [' . $_POST['field_value']. ']');

                return echo_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4735 /* UNLOCK MIN SCORE */ || $_POST['cache_en_id']==4739 /* UNLOCK MAX SCORE */){

            //Fetch/Validate Link:
            $lns = $this->DISCOVER_model->ln_fetch(array(
                'ln_id' => $_POST['in_ln__id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
            ));
            $ln_metadata = unserialize($lns[0]['ln_metadata']);
            $field_name = ( $_POST['cache_en_id']==4735 ? 'tr__conditional_score_min' : 'tr__conditional_score_max' );

            if(!count($lns)){

                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Link ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || fmod($_POST['field_value'], 1)>0 || $_POST['field_value'] < 0 || $_POST['field_value'] > 100){

                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' must be an integer between 0 and 100.',
                    'original_val' => ( isset($ln_metadata[$field_name]) ? $ln_metadata[$field_name] : '' ),
                ));

            } else {

                //All good, go ahead and update:
                $this->DISCOVER_model->ln_update($_POST['in_ln__id'], array(
                    'ln_metadata' => array_merge($ln_metadata, array(
                        $field_name => intval($_POST['field_value']),
                    )),
                ), $session_en['en_id'], 10664 /* Idea Link updated Score */, $en_all_12112[$_POST['cache_en_id']]['m_name'].' updated'.( isset($ln_metadata[$field_name]) ? ' from [' . $ln_metadata[$field_name].']' : '' ).' to [' . $_POST['field_value'].']');

                return echo_json(array(
                    'status' => 1,
                ));

            }

        } else {

            return echo_json(array(
                'status' => 0,
                'message' => 'Unkown Update Type ['.$_POST['cache_en_id'].']',
                'original_val' => '',
            ));

        }
    }

    function in_update_dropdown(){

        //Maintain a manual index as a hack for the Idea/Source tables for now:
        $en_all_6232 = $this->config->item('en_all_6232'); //PLATFORM VARIABLES
        $deletion_redirect = null;
        $delete_element = null;

        //Authenticate Player:
        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Target Idea ID',
            ));
        } elseif (!isset($_POST['in_loaded_id']) || intval($_POST['in_loaded_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Loaded Idea ID',
            ));
        } elseif (!isset($_POST['ln_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        } elseif (!isset($_POST['element_id']) || intval($_POST['element_id']) < 1 || !array_key_exists($_POST['element_id'], $en_all_6232) || strlen($en_all_6232[$_POST['element_id']]['m_desc'])<5 || !count($this->config->item('en_ids_'.$_POST['element_id']))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Element ID / Missing from @6232',
            ));
        } elseif (!isset($_POST['new_en_id']) || intval($_POST['new_en_id']) < 1 || !in_array($_POST['new_en_id'], $this->config->item('en_ids_'.$_POST['element_id']))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Value ID',
            ));
        }

        if($_POST['ln_id'] > 0){

            //Validate the link update Type ID:
            $en_all_4527 = $this->config->item('en_all_4527');
            if(!is_array($en_all_4527[$_POST['element_id']]['m_parents']) || !count($en_all_4527[$_POST['element_id']]['m_parents'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Missing @'.$_POST['element_id'].' in @4527',
                ));
            }

            //Find the single discover type in parent links:
            $link_update_types = array_intersect($this->config->item('en_ids_4593'), $en_all_4527[$_POST['element_id']]['m_parents']);
            if(count($link_update_types)!=1){
                return echo_json(array(
                    'status' => 0,
                    'message' => '@'.$_POST['element_id'].' has '.count($link_update_types).' parents that belog to @4593 [Should be exactly 1]',
                ));
            }

            //All good, Update Link:
            $this->DISCOVER_model->ln_update($_POST['ln_id'], array(
                $en_all_6232[$_POST['element_id']]['m_desc'] => $_POST['new_en_id'],
            ), $session_en['en_id'], end($link_update_types));

        } else {


            //See if Idea is being deleted:
            if($_POST['element_id']==4737){

                //Delete all idea links?
                if(!in_array($_POST['new_en_id'], $this->config->item('en_ids_7356'))){

                    //Determine what to do after deleted:
                    if($_POST['in_id'] == $_POST['in_loaded_id']){

                        //Since we're removing the FOCUS IDEA we need to move to the first parent idea:
                        foreach ($this->IDEA_model->in_recursive_parents($_POST['in_id'], true, false) as $grand_parent_ids) {
                            foreach ($grand_parent_ids as $parent_in_id) {
                                $deletion_redirect = '/idea/'.$parent_in_id; //First parent in first branch of parents
                                break;
                            }
                        }

                        //Go to main page if no parent found:
                        if(!$deletion_redirect){

                            $deletion_redirect = '/idea';

                        }

                    } else {

                        if(!$delete_element){

                            //Just delete from UI using JS:
                            $delete_element = '.in_line_' . $_POST['in_id'];

                        }

                    }

                    //Delete all links:
                    $this->IDEA_model->in_unlink($_POST['in_id'] , $session_en['en_id']);

                //Notify moderators of Feature request? Only if they don't have the powers themselves:
                } elseif(in_array($_POST['new_en_id'], $this->config->item('en_ids_12138')) && !superpower_assigned(10984) && !count($this->DISCOVER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                        'ln_type_source_id' => 12453, //Idea Feature Request
                        'ln_creator_source_id' => $session_en['en_id'],
                        'ln_next_idea_id' => $_POST['in_id'],
                    )))){

                    $this->DISCOVER_model->ln_create(array(
                        'ln_type_source_id' => 12453, //Idea Feature Request
                        'ln_creator_source_id' => $session_en['en_id'],
                        'ln_next_idea_id' => $_POST['in_id'],
                    ));

                }

            }

            //Update Idea:
            $this->IDEA_model->in_update($_POST['in_id'], array(
                $en_all_6232[$_POST['element_id']]['m_desc'] => $_POST['new_en_id'],
            ), true, $session_en['en_id']);

        }


        return echo_json(array(
            'status' => 1,
            'deletion_redirect' => $deletion_redirect,
            'delete_element' => $delete_element,
        ));

    }

    function in_unlink(){

        //Authenticate Player:
        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Idea ID',
            ));
        } elseif (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        }

        //Delete this link:
        $this->DISCOVER_model->ln_update($_POST['ln_id'], array(
            'ln_status_source_id' => 6173, //Link Deleted
        ), $session_en['en_id'], 10686 /* Idea Link Unlinked */);

        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
        ));

    }


    function in_link_or_create()
    {

        /*
         *
         * Either creates a IDEA link between in_linked_id & in_link_child_id
         * OR will create a new idea with outcome in_title and then link it
         * to in_linked_id (In this case in_link_child_id=0)
         *
         * */

        //Authenticate Player:
        $session_en = superpower_assigned(10939);
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['in_linked_id']) || intval($_POST['in_linked_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Parent Idea ID',
            ));
        } elseif (!isset($_POST['is_parent']) || !in_array(intval($_POST['is_parent']), array(0,1))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Is Parent setting',
            ));
        } elseif (!isset($_POST['in_title']) || !isset($_POST['in_link_child_id']) || ( strlen($_POST['in_title']) < 1 && intval($_POST['in_link_child_id']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing either Idea Outcome OR Child Idea ID',
            ));
        } elseif (strlen($_POST['in_title']) > config_var(11071)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Idea outcome cannot be longer than '.config_var(11071).' characters',
            ));
        } elseif($_POST['in_link_child_id'] >= 2147483647){
            return echo_json(array(
                'status' => 0,
                'message' => 'Value must be less than 2147483647',
            ));
        }


        $new_in_type = 6677; //Idea Read & Next
        $linked_ins = array();

        if($_POST['in_link_child_id'] > 0){

            //Fetch link idea to determine idea type:
            $linked_ins = $this->IDEA_model->in_fetch(array(
                'in_id' => intval($_POST['in_link_child_id']),
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
            ));

            if(count($linked_ins)==0){
                //validate linked Idea:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Idea #'.$_POST['in_link_child_id'].' is not active',
                ));
            }

            if(!intval($_POST['is_parent']) && in_array($linked_ins[0]['in_type_source_id'], $this->config->item('en_ids_7712'))){
                $new_in_type = 6914; //Require All
            }
        }

        //All seems good, go ahead and try creating the Idea:
        return echo_json($this->IDEA_model->in_link_or_create(trim($_POST['in_title']), $session_en['en_id'], $_POST['in_linked_id'], intval($_POST['is_parent']), 6184, $new_in_type, $_POST['in_link_child_id']));

    }

    function in_sort_save()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10939);
        if (!$session_en) {
            echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['new_ln_orders']) || !is_array($_POST['new_ln_orders']) || count($_POST['new_ln_orders']) < 1) {
            echo_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
        } else {

            //Validate Parent Idea:
            $parent_ins = $this->IDEA_model->in_fetch(array(
                'in_id' => intval($_POST['in_id']),
            ));
            if (count($parent_ins) < 1) {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid in_id',
                ));
            } else {

                //Fetch for the record:
                $children_before = $this->DISCOVER_model->ln_fetch(array(
                    'ln_previous_idea_id' => intval($_POST['in_id']),
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

                //Update them all:
                foreach ($_POST['new_ln_orders'] as $rank => $ln_id) {
                    $this->DISCOVER_model->ln_update(intval($ln_id), array(
                        'ln_order' => intval($rank),
                    ), $session_en['en_id'], 10675 /* Ideas Ordered by Player */);
                }

                //Fetch again for the record:
                $children_after = $this->DISCOVER_model->ln_fetch(array(
                    'ln_previous_idea_id' => intval($_POST['in_id']),
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Idea-to-Idea Links
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

                //Display message:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-check"></i> Sorted',
                ));
            }
        }
    }


    function in_notes_create_text()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10939);

        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));

        } elseif (!isset($_POST['focus_ln_type_source_id']) || intval($_POST['focus_ln_type_source_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }


        //Fetch/Validate the idea:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Idea',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->DISCOVER_model->dispatch_validate_message($_POST['ln_content'], $session_en, false, array(), $_POST['focus_ln_type_source_id'], $_POST['in_id']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return echo_json($msg_validation);
        }

        //Create Message:
        $ln = $this->DISCOVER_model->ln_create(array(
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_order' => 1 + $this->DISCOVER_model->ln_max_order(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                    'ln_type_source_id' => intval($_POST['focus_ln_type_source_id']),
                    'ln_next_idea_id' => intval($_POST['in_id']),
                )),
            //Referencing attributes:
            'ln_type_source_id' => intval($_POST['focus_ln_type_source_id']),
            'ln_parent_source_id' => $msg_validation['ln_parent_source_id'],
            'ln_next_idea_id' => intval($_POST['in_id']),
            'ln_content' => $msg_validation['input_message'],
        ), true);

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_in_notes(array_merge($ln, array(
                'ln_child_source_id' => $session_en['en_id'],
            ))),
        ));
    }


    function in_notes_create_upload()
    {

        //TODO: MERGE WITH FUNCTION discover_file_upload()

        //Authenticate Player:
        $session_en = superpower_assigned(10939);
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['in_id'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing IDEA',
            ));

        } elseif (!isset($_POST['focus_ln_type_source_id'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Pads Type',
            ));

        } elseif (!isset($_POST['upload_type']) || !in_array($_POST['upload_type'], array('file', 'drop'))) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown upload type.',
            ));

        } elseif (!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name']) == 0 || intval($_FILES[$_POST['upload_type']]['size']) == 0) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to save file.',
            ));

        } elseif ($_FILES[$_POST['upload_type']]['size'] > (config_var(11063) * 1024 * 1024)) {

            return echo_json(array(
                'status' => 0,
                'message' => 'File is larger than ' . config_var(11063) . ' MB.',
            ));

        }

        //Validate Idea:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //See if this message type has specific input requirements:
        $valid_file_types = array(4258, 4259, 4260, 4261); //This must be a valid file type:  Video, Image, Audio or File

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

        $cdn_status = upload_to_cdn($temp_local, $session_en['en_id'], $_FILES[$_POST['upload_type']], true);
        if (!$cdn_status['status']) {
            //Oops something went wrong:
            return echo_json($cdn_status);
        }


        //Create message:
        $ln = $this->DISCOVER_model->ln_create(array(
            'ln_creator_source_id' => $session_en['en_id'],
            'ln_type_source_id' => $_POST['focus_ln_type_source_id'],
            'ln_parent_source_id' => $cdn_status['cdn_en']['en_id'],
            'ln_next_idea_id' => intval($_POST['in_id']),
            'ln_content' => '@' . $cdn_status['cdn_en']['en_id'],
            'ln_order' => 1 + $this->DISCOVER_model->ln_max_order(array(
                    'ln_type_source_id' => $_POST['focus_ln_type_source_id'],
                    'ln_next_idea_id' => $_POST['in_id'],
                )),
        ));


        //Fetch full message for proper UI display:
        $new_messages = $this->DISCOVER_model->ln_fetch(array(
            'ln_id' => $ln['ln_id'],
        ));

        //Echo message:
        echo_json(array(
            'status' => 1,
            'message' => echo_in_notes(array_merge($new_messages[0], array(
                'ln_child_source_id' => $session_en['en_id'],
            ))),
        ));
    }




    function in_notes_sort()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10939);
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));

        } elseif (!isset($_POST['new_ln_orders']) || !is_array($_POST['new_ln_orders']) || count($_POST['new_ln_orders']) < 1) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return echo_json(array(
                'status' => 1,
                'message' => 'There was nothing to sort',
            ));

        }

        //Update all link orders:
        $sort_count = 0;
        foreach ($_POST['new_ln_orders'] as $ln_order => $ln_id) {
            if (intval($ln_id) > 0) {
                $sort_count++;
                //Log update and give credit to the session Player:
                $this->DISCOVER_model->ln_update($ln_id, array(
                    'ln_order' => intval($ln_order),
                ), $session_en['en_id'], 10676 /* Idea Notes Ordered */);
            }
        }

        //Return success:
        return echo_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }

    function in_notes_modify_save()
    {

        //Authenticate Player:
        $session_en = superpower_assigned(10939);
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(10939),
            ));
        } elseif (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing TRANSACTION ID',
            ));
        } elseif (!isset($_POST['message_ln_status_source_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Status',
            ));
        } elseif (!isset($_POST['ln_content'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Idea ID',
            ));
        }

        //Validate Idea:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Idea Not Found',
            ));
        }

        //Validate Message:
        $messages = $this->DISCOVER_model->ln_fetch(array(
            'ln_id' => intval($_POST['ln_id']),
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
        ));
        if (count($messages) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }

        //Validate new message:
        $msg_validation = $this->DISCOVER_model->dispatch_validate_message($_POST['ln_content'], $session_en, false, array(), $messages[0]['ln_type_source_id'], $_POST['in_id']);
        if (!$msg_validation['status']) {

            //There was some sort of an error:
            return echo_json($msg_validation);

        } elseif($messages[0]['ln_content'] != $msg_validation['input_message']) {

            //Now update the DB:
            $this->DISCOVER_model->ln_update(intval($_POST['ln_id']), array(
                'ln_content' => $msg_validation['input_message'],
                'ln_parent_source_id' => $msg_validation['ln_parent_source_id'],
            ), $session_en['en_id'], 10679 /* Idea Notes updated Content */, update_description($messages[0]['ln_content'], $msg_validation['input_message']));

        }


        //Did the message status change?
        if($messages[0]['ln_status_source_id'] != $_POST['message_ln_status_source_id']){

            //Are we deleting this message?
            if(in_array($_POST['message_ln_status_source_id'], $this->config->item('en_ids_7360') /* Transaction Status Active */)){

                //If making the link public, all referenced sources must also be public...
                if(in_array($_POST['message_ln_status_source_id'], $this->config->item('en_ids_7359') /* Transaction Status Public */)){

                    //We're publishing, make sure potential source references are also published:
                    $string_references = extract_references($_POST['ln_content']);

                    if (count($string_references['ref_sources']) > 0) {

                        //We do have an source reference, what's its status?
                        $ref_ens = $this->SOURCE_model->en_fetch(array(
                            'en_id' => $string_references['ref_sources'][0],
                        ));

                        if(count($ref_ens)>0 && !in_array($ref_ens[0]['en_status_source_id'], $this->config->item('en_ids_7357') /* Source Status Public */)){
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'You cannot published this message because its referenced source is not yet public',
                            ));
                        }
                    }
                }

                //yes, do so and return results:
                $affected_rows = $this->DISCOVER_model->ln_update(intval($_POST['ln_id']), array(
                    'ln_status_source_id' => $_POST['message_ln_status_source_id'],
                ), $session_en['en_id'], 10677 /* Idea Notes updated Status */);

            } else {

                //New status is no longer active, so delete the Idea Notes:
                $affected_rows = $this->DISCOVER_model->ln_update(intval($_POST['ln_id']), array(
                    'ln_status_source_id' => $_POST['message_ln_status_source_id'],
                ), $session_en['en_id'], 10678 /* Idea Notes Unlinked */);

                //Return success:
                if($affected_rows > 0){
                    return echo_json(array(
                        'status' => 1,
                        'delete_from_ui' => 1,
                        'message' => echo_platform_message(12695),
                    ));
                } else {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Error trying to delete message',
                    ));
                }
            }
        }


        $en_all_6186 = $this->config->item('en_all_6186');

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'delete_from_ui' => 0,
            'message' => $this->DISCOVER_model->dispatch_message($msg_validation['input_message'], $session_en, false, array(), $_POST['in_id']),
            'message_new_status_icon' => '<span title="' . $en_all_6186[$_POST['message_ln_status_source_id']]['m_name'] . ': ' . $en_all_6186[$_POST['message_ln_status_source_id']]['m_desc'] . '" data-toggle="tooltip" data-placement="top">' . $en_all_6186[$_POST['message_ln_status_source_id']]['m_icon'] . '</span>', //This might have changed
            'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));

    }



}