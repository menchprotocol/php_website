<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }

    function blog_create(){

        $en_all_6201 = $this->config->item('en_all_6201'); //Blog Table
        $session_en = superpower_assigned(10939);
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));

        } elseif (!isset($_POST['newBlogTitle'])) {

            //Do not treat this case as error as it could happen in moving Messages between types:
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing '.$en_all_6201[4736]['m_name'],
            ));

        }

        //Validate Title:
        $in_titlevalidation = $this->BLOG_model->in_titlevalidate($_POST['newBlogTitle']);
        if(!$in_titlevalidation['status']){
            //We had an error, return it:
            return echo_json($in_titlevalidation);
        }


        //Create Blog:
        $in = $this->BLOG_model->in_link_or_create($in_titlevalidation['in_cleaned_outcome'], $session_en['en_id']);

        //Also add to bookmarks:
        $this->READ_model->ln_create(array(
            'ln_type_play_id' => 10573, //Bookmarks
            'ln_player_play_id' => $session_en['en_id'],
            'ln_child_blog_id' => $in['new_in_id'],
            'ln_parent_play_id' => $session_en['en_id'],
            'ln_content' => '@'.$session_en['en_id'],
        ), true);

        return echo_json(array(
            'status' => 1,
            'message' => 'Success. Redirecting now...',
            'in_id' => $in['new_in_id'],
        ));

    }

    function blog_home(){
        $session_en = superpower_assigned(null, true);
        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH
        $this->load->view('header', array(
            'title' => $en_all_2738[4535]['m_name'],
            'session_en' => $session_en,
        ));
        $this->load->view('blog/blog_home');
        $this->load->view('footer');
    }

    function blog_coin($in_id){

        //Validate/fetch Blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        if ( count($ins) < 1) {
            return redirect_message('/', '<div class="alert alert-danger" role="alert">BLOG #' . $in_id . ' Not Found</div>');
        }

        //Make sure user is logged in
        $session_en = superpower_assigned(null, (!in_array($ins[0]['in_status_play_id'], $this->config->item('en_ids_7355'))));
        if(!$session_en){
            return redirect_message('/'.$in_id);
        }



        //Update session count and log link:
        if(isset($session_en['en_id']) && $session_en['en_id'] > 0){
            $new_order = ( $this->session->userdata('session_page_count') + 1 );
            $this->session->set_userdata('session_page_count', $new_order);
            $this->READ_model->ln_create(array(
                'ln_player_play_id' => $session_en['en_id'],
                'ln_type_play_id' => 4993, //Trainer Opened Blog
                'ln_child_blog_id' => $in_id,
                'ln_order' => $new_order,
            ));
        }


        //Load views:
        $this->load->view('header', array(
            'title' => $ins[0]['in_title'].' | BLOG',
            'in' => $ins[0],
        ));
        $this->load->view('blog/blog_coin', array(
            'in' => $ins[0],
            'session_en' => $session_en,
        ));
        $this->load->view('footer');

    }



    //Loaded as default function of the default controller:
    function index()
    {

        $session_en = superpower_assigned();

        if ((isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'mench.co')) {

            //Go to mench.com for now:
            return redirect_message('https://mench.com');

        } else {

            //Go to focus Blog
            return redirect_message('/read/next');

        }
    }


    function in_report_conditional_steps(){

        //Authenticate Trainer:
        $session_en = superpower_assigned(10985);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['starting_in']) || intval($_POST['starting_in']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Starting Blog',
            ));
        } elseif (!isset($_POST['depth_levels']) || intval($_POST['depth_levels']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Depth',
            ));
        }

        //Fetch/Validate blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['starting_in'],
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
        ));
        if(count($ins) != 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not find blog #'.$_POST['starting_in'],
            ));
        }


        //Load AND/OR Blogs:
        $en_all_7585 = $this->config->item('en_all_7585'); // Blog Subtypes
        $en_all_4737 = $this->config->item('en_all_4737'); // Blog Statuses


        //Return report:
        return echo_json(array(
            'status' => 1,
            'message' => '<h3>'.$en_all_7585[$ins[0]['in_type_play_id']]['m_icon'].' '.$en_all_4737[$ins[0]['in_status_play_id']]['m_icon'].' '.echo_in_title($ins[0]['in_title'], false).'</h3>'.echo_in_scores_answer($_POST['starting_in'], $_POST['depth_levels'], $_POST['depth_levels'], $ins[0]['in_type_play_id']),
        ));

    }

    function in_request_invite($in_id){

        //Make sure it's a logged in trainer:
        $session_en = superpower_assigned(null, true);

        if(count($this->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 12450,
            'ln_player_play_id' => $session_en['en_id'],
            'ln_child_blog_id' => $in_id,
        )))){
            return redirect_message('/blog/'.$in_id, '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fad fa-exclamation-triangle"></i></span>You have already requested to join this blog. No further action is necessary.</div>');

        }

        //Inform moderators:
        $this->READ_model->ln_create(array(
            'ln_type_play_id' => 12450,
            'ln_player_play_id' => $session_en['en_id'],
            'ln_child_blog_id' => $in_id,
        ));

        //Go back to blog:
        return redirect_message('/blog/'.$in_id, '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-thumbs-up"></i></span>Successfully submitted your request to join as an author of this blog. You will receive a confirmation once your request has been reviewed.</div>');

    }

    function in_become_author($in_id){

        //Make sure it's a logged in trainer:
        $session_en = superpower_assigned(10985, true);

        //Blog Author:
        $this->READ_model->ln_create(array(
            'ln_type_play_id' => 4983,
            'ln_player_play_id' => $session_en['en_id'],
            'ln_parent_play_id' => $session_en['en_id'],
            'ln_content' => '@'.$session_en['en_id'],
            'ln_child_blog_id' => $in_id,
        ));

        //Go back to blog:
        return redirect_message('/blog/'.$in_id, '<div class="alert alert-success" role="alert"><span class="icon-block"><i class="far fa-thumbs-up"></i></span>SUCCESSFULLY JOINED</div>');

    }


    function in_update_text(){

        //Authenticate Trainer:
        $session_en = superpower_assigned(10984);
        $en_all_12112 = $this->config->item('en_all_12112');

        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
                'original_val' => '',
            ));

        } elseif(!isset($_POST['in_ln__id']) || !isset($_POST['cache_en_id']) || !isset($_POST['field_value'])){

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_en_id']==4736 /* BLOG TITLE */){

            $ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $_POST['in_ln__id'],
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            ));
            if(!count($ins)){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Blog ID.',
                    'original_val' => '',
                ));
            }

            //Validate Blog Outcome:
            $in_titlevalidation = $this->BLOG_model->in_titlevalidate($_POST['field_value']);
            if(!$in_titlevalidation['status']){
                //We had an error, return it:
                return echo_json(array_merge($in_titlevalidation, array(
                    'original_val' => $ins[0]['in_title'],
                )));
            } else {

                //All good, go ahead and update:
                $this->BLOG_model->in_update($_POST['in_ln__id'], array(
                    'in_title' => trim($_POST['field_value']),
                ), true, $session_en['en_id']);

                return echo_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4356 /* READ TIME */){

            $ins = $this->BLOG_model->in_fetch(array(
                'in_id' => $_POST['in_ln__id'],
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            ));

            if(!count($ins)){

                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Blog ID.',
                    'original_val' => '',
                ));

            } elseif(!is_numeric($_POST['field_value']) || $_POST['field_value'] < 0){

                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' must be a number greater than zero.',
                    'original_val' => $ins[0]['in_read_time'],
                ));

            } elseif($_POST['field_value'] > config_var(12113)){

                $hours = rtrim(number_format((config_var(12113)/3600), 1), '.0');
                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' should be less than '.$hours.' Hour'.echo__s($hours).', or '.config_var(12113).' Seconds long. You can break down your blog into smaller blogs.',
                    'original_val' => $ins[0]['in_read_time'],
                ));

            } elseif($_POST['field_value'] < config_var(12427)){

                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' should be at-least '.config_var(12427).' Seconds long. It takes time to read blogs ;)',
                    'original_val' => $ins[0]['in_read_time'],
                ));

            } else {

                //All good, go ahead and update:
                $this->BLOG_model->in_update($_POST['in_ln__id'], array(
                    'in_read_time' => $_POST['field_value'],
                ), true, $session_en['en_id']);

                return echo_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4358 /* READ MARKS */){

            //Fetch/Validate Link:
            $lns = $this->READ_model->ln_fetch(array(
                'ln_id' => $_POST['in_ln__id'],
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
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
                $this->READ_model->ln_update($_POST['in_ln__id'], array(
                    'ln_metadata' => array_merge($ln_metadata, array(
                        'tr__assessment_points' => intval($_POST['field_value']),
                    )),
                ), $session_en['en_id'], 10663 /* Blog Link Iterated Marks */, $en_all_12112[$_POST['cache_en_id']]['m_name'].' iterated'.( isset($ln_metadata['tr__assessment_points']) ? ' from [' . $ln_metadata['tr__assessment_points']. ']' : '' ).' to [' . $_POST['field_value']. ']');

                return echo_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4735 /* UNLOCK MIN SCORE */ || $_POST['cache_en_id']==4739 /* UNLOCK MAX SCORE */){

            //Fetch/Validate Link:
            $lns = $this->READ_model->ln_fetch(array(
                'ln_id' => $_POST['in_ln__id'],
                'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
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
                $this->READ_model->ln_update($_POST['in_ln__id'], array(
                    'ln_metadata' => array_merge($ln_metadata, array(
                        $field_name => intval($_POST['field_value']),
                    )),
                ), $session_en['en_id'], 10664 /* Blog Link Iterated Score */, $en_all_12112[$_POST['cache_en_id']]['m_name'].' iterated'.( isset($ln_metadata[$field_name]) ? ' from [' . $ln_metadata[$field_name].']' : '' ).' to [' . $_POST['field_value'].']');

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

        //Maintain a manual index as a hack for the Blog/Player tables for now:
        $en_all_6232 = $this->config->item('en_all_6232'); //PLATFORM VARIABLES
        $deletion_redirect = null;
        $remove_element = null;

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Target Blog ID',
            ));
        } elseif (!isset($_POST['in_loaded_id']) || intval($_POST['in_loaded_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Loaded Blog ID',
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

            //Find the single read type in parent links:
            $link_update_types = array_intersect($this->config->item('en_ids_4593'), $en_all_4527[$_POST['element_id']]['m_parents']);
            if(count($link_update_types)!=1){
                return echo_json(array(
                    'status' => 0,
                    'message' => '@'.$_POST['element_id'].' has '.count($link_update_types).' parents that belog to @4593 [Should be exactly 1]',
                ));
            }

            //All good, Update Link:
            $this->READ_model->ln_update($_POST['ln_id'], array(
                $en_all_6232[$_POST['element_id']]['m_desc'] => $_POST['new_en_id'],
            ), $session_en['en_id'], end($link_update_types));

        } else {


            //See if Blog is being removed:
            if($_POST['element_id']==4737){

                //Remove all blog links?
                if(!in_array($_POST['new_en_id'], $this->config->item('en_ids_7356'))){

                    //Determine what to do after removed:
                    if($_POST['in_id'] == $_POST['in_loaded_id']){

                        //Since we're removing the FOCUS BLOG we need to move to the first parent blog:
                        foreach ($this->BLOG_model->in_fetch_recursive_parents($_POST['in_id'], true, false) as $grand_parent_ids) {
                            foreach ($grand_parent_ids as $parent_in_id) {
                                $deletion_redirect = '/blog/'.$parent_in_id; //First parent in first branch of parents
                                break;
                            }
                        }

                        //Go to main page if no parent found:
                        if(!$deletion_redirect){

                            $deletion_redirect = '/blog';

                        }

                    } else {

                        if(!$remove_element){

                            //Just remove from UI using JS:
                            $remove_element = '.blog_line_' . $_POST['in_id'];

                        }

                    }

                    //Remove all links:
                    $this->BLOG_model->in_unlink($_POST['in_id'] , $session_en['en_id']);

                //Notify moderators of Feature request? Only if they don't have the powers themselves:
                } elseif(in_array($_POST['new_en_id'], $this->config->item('en_ids_12138')) && !superpower_assigned(10985) && !count($this->READ_model->ln_fetch(array(
                        'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_type_play_id' => 12453, //Blog Feature Request
                        'ln_player_play_id' => $session_en['en_id'],
                        'ln_child_blog_id' => $_POST['in_id'],
                    )))){

                    $this->READ_model->ln_create(array(
                        'ln_type_play_id' => 12453, //Blog Feature Request
                        'ln_player_play_id' => $session_en['en_id'],
                        'ln_child_blog_id' => $_POST['in_id'],
                    ));

                }

            }

            //Update Blog:
            $this->BLOG_model->in_update($_POST['in_id'], array(
                $en_all_6232[$_POST['element_id']]['m_desc'] => $_POST['new_en_id'],
            ), true, $session_en['en_id']);

        }


        return echo_json(array(
            'status' => 1,
            'deletion_redirect' => $deletion_redirect,
            'remove_element' => $remove_element,
        ));

    }

    function in_unlink(){

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Blog ID',
            ));
        } elseif (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Link ID',
            ));
        }

        //Remove this link:
        $this->READ_model->ln_update($_POST['ln_id'], array(
            'ln_status_play_id' => 6173, //Link Removed
        ), $session_en['en_id'], 10686 /* Blog Link Unlinked */);

        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
        ));

    }


    function in_link_or_create()
    {

        /*
         *
         * Either creates a BLOG link between in_linked_id & in_link_child_id
         * OR will create a new blog with outcome in_title and then link it
         * to in_linked_id (In this case in_link_child_id=0)
         *
         * */

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['in_linked_id']) || intval($_POST['in_linked_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Parent Blog ID',
            ));
        } elseif (!isset($_POST['is_parent']) || !in_array(intval($_POST['is_parent']), array(0,1))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Is Parent setting',
            ));
        } elseif (!isset($_POST['in_title']) || !isset($_POST['in_link_child_id']) || ( strlen($_POST['in_title']) < 1 && intval($_POST['in_link_child_id']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing either Blog Outcome OR Child Blog ID',
            ));
        } elseif (strlen($_POST['in_title']) > config_var(11071)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Blog outcome cannot be longer than '.config_var(11071).' characters',
            ));
        } elseif($_POST['in_link_child_id'] >= 2147483647){
            return echo_json(array(
                'status' => 0,
                'message' => 'Value must be less than 2147483647',
            ));
        }


        $new_blog_type = 6677; //Blog Read-Only
        $linked_ins = array();

        if($_POST['in_link_child_id'] > 0){

            //Fetch link blog to determine blog type:
            $linked_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => intval($_POST['in_link_child_id']),
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
            ));

            if(count($linked_ins)==0){
                //validate linked Blog:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Blog #'.$_POST['in_link_child_id'].' is not active',
                ));
            }

            if(!intval($_POST['is_parent']) && in_array($linked_ins[0]['in_type_play_id'], $this->config->item('en_ids_7712'))){
                $new_blog_type = 6914; //Require All
            }
        }

        //All seems good, go ahead and try creating the Blog:
        return echo_json($this->BLOG_model->in_link_or_create(trim($_POST['in_title']), $session_en['en_id'], $_POST['in_linked_id'], intval($_POST['is_parent']), 6184, $new_blog_type, $_POST['in_link_child_id']));

    }


    function in_READ_BOOKMARKS(){

        //Authenticate User:
        $session_en = superpower_assigned();

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['in_loaded_id']) || intval($_POST['in_loaded_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Focus Blog ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Blog ID',
            ));
        }

        //Validate Blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Blog not found',
            ));
        }


        //Fetch READING LIST users:
        $actionplan_users = $this->READ_model->ln_fetch(array(
            'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            'ln_parent_blog_id' => $ins[0]['in_id'],
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_play_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
        ), array('en_owner'), 500);
        if(count($actionplan_users) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => '<i class="fad fa-exclamation-triangle"></i> Nobody has completed this blog yet',
            ));
        }


        //Go through match list:
        $filters_list_counter = 0;
        $regular_list_counter = 0;
        $filters_list_ui = '';
        $regular_list_ui = '';

        foreach($actionplan_users as $apu){

            //Count user READING LIST Progression Completed:
            $count_progression = $this->READ_model->ln_fetch(array(
                'ln_player_play_id' => $apu['en_id'],
                'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');


            $regular_list_counter++;
            $current_count = $regular_list_counter;


            //Create the UI for this user:
            $item_ui = '<tr>';
            $item_ui .= '<td valign="top">'.$current_count.'</td>';
            $item_ui .= '<td style="text-align:left;">';
            $item_ui .= '<span class="icon-block">'.echo_en_icon($apu['en_icon']).'</span> '.$apu['en_name'];
            $item_ui .= ( strlen($apu['ln_content']) > 0 ? '<div class="user-comment">'.$this->READ_model->dispatch_message($apu['ln_content']).'</div>' : '' );
            $item_ui .= '</td>';

            $item_ui .= '<td style="text-align:left;"><a href="/read/view_json/'.$apu['ln_id'].'">'.echo_en_cache('en_all_6255' /* User Reads Progress */, $apu['ln_type_play_id']).'</a></td>';
            $item_ui .= '<td style="text-align:left;">'.echo_number($count_progression[0]['totals']).'</td>';
            $item_ui .= '<td style="text-align:left;">'.echo_time_difference(strtotime($apu['ln_timestamp'])).'</td>';
            $item_ui .= '<td style="text-align:left;">';

            $item_ui .= '<a href="/blog/'.$_POST['in_loaded_id'].'#actionplanusers-'.$_POST['in_id'].'" data-toggle="tooltip" data-placement="top" title="Filter by this user"><i class="far fa-filter"></i></a>';
            $item_ui .= '&nbsp;<a href="/play/'.$apu['en_id'].'" data-toggle="tooltip" data-placement="top" title="User Player"><i class="fas fa-at"></i></a>';

            $item_ui .= '&nbsp;<a href="/ledger?ln_player_play_id='.$apu['en_id'].'" data-toggle="tooltip" data-placement="top" title="Full User History"><i class="fas fa-link"></i></a>';

            $item_ui .= '</td>';
            $item_ui .= '</tr>';

            //Decide which list it should go to:
            $regular_list_ui .= $item_ui;
        }



        //Filtered list if any:
        $ui = '<table class="table table-sm table-striped">';

        //Regular list:
        if($regular_list_ui){
            $ui .= '<tr style="font-weight: bold;">';
            $ui .= '<td style="text-align:left; padding-left:3px;" colspan="2">PLAYERS READ' . echo__s($regular_list_counter).' ['.$regular_list_counter.']</td>';
            $ui .= '<td style="text-align:left;">&nbsp;</td>';
            $ui .= '<td style="text-align:left;"><i class="fas fa-walking" data-toggle="tooltip" data-placement="top" title="User Steps Progressed"></i></td>';
            $ui .= '<td style="text-align:left;"><i class="far fa-clock" data-toggle="tooltip" data-placement="top" title="Completion time"></i></td>';
            $ui .= '<td style="text-align:left;">&nbsp;</td>';
            $ui .= '</tr>';
            $ui .= $regular_list_ui;
            $ui .= '</table>';
        }

        echo_json(array(
            'status' => 1,
            'message' => $ui,
        ));
    }



    function in_review_metadata($in_id){
        //Fetch Blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        if(count($ins) > 0){
            echo_json(unserialize($ins[0]['in_metadata']));
        } else {
            echo 'Blog #'.$in_id.' not found!';
        }
    }

    function in_sort_save()
    {

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {
            echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
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

            //Validate Parent Blog:
            $parent_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => intval($_POST['in_id']),
            ));
            if (count($parent_ins) < 1) {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid in_id',
                ));
            } else {

                //Fetch for the record:
                $children_before = $this->READ_model->ln_fetch(array(
                    'ln_parent_blog_id' => intval($_POST['in_id']),
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

                //Update them all:
                foreach ($_POST['new_ln_orders'] as $rank => $ln_id) {
                    $this->READ_model->ln_update(intval($ln_id), array(
                        'ln_order' => intval($rank),
                    ), $session_en['en_id'], 10675 /* Blogs Ordered by Trainer */);
                }

                //Fetch again for the record:
                $children_after = $this->READ_model->ln_fetch(array(
                    'ln_parent_blog_id' => intval($_POST['in_id']),
                    'ln_type_play_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

                //Display message:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-check"></i> Sorted',
                ));
            }
        }
    }


    function in_note_create_text()
    {

        //Authenticate Trainer:
        $session_en = superpower_assigned();

        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));

        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Blog ID',
            ));

        } elseif (!isset($_POST['focus_ln_type_play_id']) || intval($_POST['focus_ln_type_play_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }


        //Fetch/Validate the blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Blog',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->READ_model->dispatch_validate_message($_POST['ln_content'], $session_en, false, array(), $_POST['focus_ln_type_play_id'], $_POST['in_id']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return echo_json($msg_validation);
        }

        //Create Message:
        $ln = $this->READ_model->ln_create(array(
            'ln_player_play_id' => $session_en['en_id'],
            'ln_order' => 1 + $this->READ_model->ln_max_order(array(
                    'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'ln_type_play_id' => intval($_POST['focus_ln_type_play_id']),
                    'ln_child_blog_id' => intval($_POST['in_id']),
                )),
            //Referencing attributes:
            'ln_type_play_id' => intval($_POST['focus_ln_type_play_id']),
            'ln_parent_play_id' => $msg_validation['ln_parent_play_id'],
            'ln_parent_blog_id' => $msg_validation['ln_parent_blog_id'],
            'ln_child_blog_id' => intval($_POST['in_id']),
            'ln_content' => $msg_validation['input_message'],
        ), true);

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_in_note(array_merge($ln, array(
                'ln_child_play_id' => $session_en['en_id'],
            ))),
        ));
    }


    function in_note_create_upload()
    {

        //TODO: MERGE WITH FUNCTION read_file_upload()

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));

        } elseif (!isset($_POST['in_id'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing BLOG',
            ));

        } elseif (!isset($_POST['focus_ln_type_play_id'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Note Type',
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

        //Validate Blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Blog ID',
            ));
        }

        //See if this message type has specific input requirements:
        $valid_file_types = array(4258, 4259, 4260, 4261); //This must be a valid file type:  Video, Image, Audio or File

        //Attempt to save file locally:
        $file_parts = explode('.', $_FILES[$_POST['upload_type']]["name"]);
        $temp_local = "application/cache/temp_files/" . md5($file_parts[0] . $_FILES[$_POST['upload_type']]["type"] . $_FILES[$_POST['upload_type']]["size"]) . '.' . $file_parts[(count($file_parts) - 1)];
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
        $ln = $this->READ_model->ln_create(array(
            'ln_player_play_id' => $session_en['en_id'],
            'ln_type_play_id' => $_POST['focus_ln_type_play_id'],
            'ln_parent_play_id' => $cdn_status['cdn_en']['en_id'],
            'ln_child_blog_id' => intval($_POST['in_id']),
            'ln_content' => '@' . $cdn_status['cdn_en']['en_id'], //Just place the player reference as the entire message
            'ln_order' => 1 + $this->READ_model->ln_max_order(array(
                    'ln_type_play_id' => $_POST['focus_ln_type_play_id'],
                    'ln_child_blog_id' => $_POST['in_id'],
                )),
        ));


        //Fetch full message for proper UI display:
        $new_messages = $this->READ_model->ln_fetch(array(
            'ln_id' => $ln['ln_id'],
        ));

        //Echo message:
        echo_json(array(
            'status' => 1,
            'message' => echo_in_note(array_merge($new_messages[0], array(
                'ln_child_play_id' => $session_en['en_id'],
            ))),
        ));
    }




    function in_notes_sort()
    {

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
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
                //Log update and give credit to the session Trainer:
                $this->READ_model->ln_update($ln_id, array(
                    'ln_order' => intval($ln_order),
                ), $session_en['en_id'], 10676 /* Blog Notes Ordered */);
            }
        }

        //Return success:
        return echo_json(array(
            'status' => 1,
            'message' => $sort_count . ' Sorted', //Does not matter as its currently not displayed in UI
        ));
    }

    function in_note_modify_save()
    {

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing READ ID',
            ));
        } elseif (!isset($_POST['message_ln_status_play_id'])) {
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
                'message' => 'Invalid Blog ID',
            ));
        }

        //Validate Blog:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Blog Not Found',
            ));
        }

        //Validate Message:
        $messages = $this->READ_model->ln_fetch(array(
            'ln_id' => intval($_POST['ln_id']),
            'ln_status_play_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ));
        if (count($messages) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }

        //Validate new message:
        $msg_validation = $this->READ_model->dispatch_validate_message($_POST['ln_content'], $session_en, false, array(), $messages[0]['ln_type_play_id'], $_POST['in_id']);
        if (!$msg_validation['status']) {

            //There was some sort of an error:
            return echo_json($msg_validation);

        } elseif($messages[0]['ln_content'] != $msg_validation['input_message']) {

            //Now update the DB:
            $this->READ_model->ln_update(intval($_POST['ln_id']), array(
                'ln_content' => $msg_validation['input_message'],
                'ln_parent_play_id' => $msg_validation['ln_parent_play_id'],
                'ln_parent_blog_id' => $msg_validation['ln_parent_blog_id'],
            ), $session_en['en_id'], 10679 /* Blog Notes Iterated Content */, update_description($messages[0]['ln_content'], $msg_validation['input_message']));

        }


        //Did the message status change?
        if($messages[0]['ln_status_play_id'] != $_POST['message_ln_status_play_id']){

            //Are we deleting this message?
            if(in_array($_POST['message_ln_status_play_id'], $this->config->item('en_ids_7360') /* Link Statuses Active */)){

                //If making the link public, all referenced players must also be public...
                if(in_array($_POST['message_ln_status_play_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)){

                    //We're publishing, make sure potential player references are also published:
                    $string_references = extract_references($_POST['ln_content']);

                    if (count($string_references['ref_players']) > 0) {

                        //We do have an player reference, what's its status?
                        $ref_ens = $this->PLAY_model->en_fetch(array(
                            'en_id' => $string_references['ref_players'][0],
                        ));

                        if(count($ref_ens)>0 && !in_array($ref_ens[0]['en_status_play_id'], $this->config->item('en_ids_7357') /* Player Statuses Public */)){
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'You cannot published this message because its referenced player is not yet public',
                            ));
                        }
                    }
                }

                //yes, do so and return results:
                $affected_rows = $this->READ_model->ln_update(intval($_POST['ln_id']), array(
                    'ln_status_play_id' => $_POST['message_ln_status_play_id'],
                ), $session_en['en_id'], 10677 /* Blog Notes Iterated Status */);

            } else {

                //New status is no longer active, so remove the blog note:
                $affected_rows = $this->READ_model->ln_update(intval($_POST['ln_id']), array(
                    'ln_status_play_id' => $_POST['message_ln_status_play_id'],
                ), $session_en['en_id'], 10678 /* Blog Notes Unlinked */);

                //Return success:
                if($affected_rows > 0){
                    return echo_json(array(
                        'status' => 1,
                        'remove_from_ui' => 1,
                        'message' => echo_random_message('saving_notify'),
                    ));
                } else {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Error trying to remove message',
                    ));
                }
            }
        }


        $en_all_6186 = $this->config->item('en_all_6186');

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'remove_from_ui' => 0,
            'message' => $this->READ_model->dispatch_message($msg_validation['input_message'], $session_en, false, array(), $_POST['in_id']),
            'message_new_status_icon' => '<span title="' . $en_all_6186[$_POST['message_ln_status_play_id']]['m_name'] . ': ' . $en_all_6186[$_POST['message_ln_status_play_id']]['m_desc'] . '" data-toggle="tooltip" data-placement="top">' . $en_all_6186[$_POST['message_ln_status_play_id']]['m_icon'] . '</span>', //This might have changed
            'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));

    }




    function cron__sync_common_base($in_id = 0)
    {

        /*
         *
         * Updates common base metadata for published blogs
         *
         * */

        if($in_id < 0){
            //Gateway URL to give option to run...
            die('<a href="/blog/cron__sync_common_base">Click here</a> to start running this function.');
        }

        boost_power();
        $start_time = time();
        $filters = array(
            'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        );
        if($in_id > 0){
            $filters['in_id'] = $in_id;
        }

        $published_ins = $this->BLOG_model->in_fetch($filters);
        foreach($published_ins as $published_in){
            $tree = $this->BLOG_model->in_metadata_common_base($published_in);
        }

        $total_time = time() - $start_time;
        $success_message = 'Common Base Metadata updated for '.count($published_ins).' published blog'.echo__s(count($published_ins)).'.';
        if (isset($_GET['redirect']) && strlen($_GET['redirect']) > 0) {
            //Now redirect;
            $this->session->set_flashdata('flash_message', '<div class="alert alert-success" role="alert">' . $success_message . '</div>');
            header('Location: ' . $_GET['redirect']);
        } else {
            //Show json:
            echo_json(array(
                'message' => $success_message,
                'total_time' => echo_time_minutes($total_time),
                'item_time' => round(($total_time/count($published_ins)),1).' Seconds',
                'last_tree' => $tree,
            ));
        }
    }


    function cron__sync_extra_insights($in_id = 0)
    {

        /*
         *
         * Updates tree insights (like min/max reads, time & cost)
         * based on its common and expansion tree.
         *
         * */


        if($in_id < 0){
            //Gateway URL to give option to run...
            die('<a href="/blog/cron__sync_extra_insights">Click here</a> to start running this function.');
        }

        boost_power();
        $start_time = time();
        $update_count = 0;

        if($in_id > 0){

            //Increment count by 1:
            $update_count++;

            //Start with common base:
            foreach($this->BLOG_model->in_fetch(array('in_id' => $in_id)) as $published_in){
                $this->BLOG_model->in_metadata_common_base($published_in);
            }

            //Update extra insights:
            $tree = $this->BLOG_model->in_metadata_extra_insights($in_id);

        } else {

            //Update all Recommended Blogs and their tree:
            foreach ($this->BLOG_model->in_fetch(array(
                'in_status_play_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            )) as $published_in) {
                $tree = $this->BLOG_model->in_metadata_extra_insights($published_in['in_id']);
                if($tree){
                    $update_count++;
                }
            }

        }



        $end_time = time() - $start_time;
        $success_message = 'Extra Insights Metadata updated for '.$update_count.' blog'.echo__s($update_count).'.';

        //Show json:
        echo_json(array(
            'message' => $success_message,
            'total_time' => echo_time_minutes($end_time),
            'item_time' => round(($end_time/$update_count),1).' Seconds',
            'last_tree' => $tree,
        ));
    }

}