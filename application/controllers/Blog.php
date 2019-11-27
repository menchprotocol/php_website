<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }

    function blog_overview(){
        $this->load->view('header', array(
            'title' => 'BLOG',
        ));
        $this->load->view('view_blog/blog_overview');
        $this->load->view('footer');
    }


    function demo(){
        $this->load->view('header', array(
            'title' => 'DEMO',
        ));
        $this->load->view('view_blog/demo');
        $this->load->view('footer');
    }

    function blog_modify($in_id){

        //Make sure user is logged in
        $session_en = superpower_assigned(null, true);

        //Validate/fetch BLOG:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
        ), array('in__parents'));
        if ( count($ins) < 1) {
            return redirect_message('/blog', '<div class="alert alert-danger" role="alert">BLOG #' . $in_id . ' not found</div>');
        }

        //Update session count and log link:
        $new_order = ( $this->session->userdata('player_page_count') + 1 );
        $this->session->set_userdata('player_page_count', $new_order);
        $this->READ_model->ln_create(array(
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => 4993, //Trainer Opened Intent
            'ln_child_intent_id' => $in_id,
            'ln_order' => $new_order,
        ));

        //Load views:
        $this->load->view('header', array(
            'title' => $ins[0]['in_outcome'].' | BLOG'
        ));
        $this->load->view('view_blog/blog_modify', array(
            'in' => $ins[0],
            'session_en' => $session_en,
        ));
        $this->load->view('footer');
    }




    function fix__completion_marks($in_id){

        die('adjust variables to begin');

        boost_power();

        foreach($this->READ_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $in_id,
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $rank => $assessment_in){

            echo '<br /><b>'.($rank+1). ') '. $assessment_in['in_outcome'].'</b><br />';

            //Assessments:
            foreach($this->READ_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'ln_type_entity_id' => 4228, //Intent Link Regular Step
                'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_6193')) . ')' => null, //OR Intents
                'ln_parent_intent_id' => $assessment_in['in_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $rank2 => $assessment2_in){
                echo '&nbsp;&nbsp;&nbsp;&nbsp;'.($rank+1).'.'.($rank2+1). ') '. $assessment2_in['in_outcome'].'<br />';

                //Questions:
                foreach($this->READ_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                    'ln_type_entity_id' => 4228, //Intent Link Regular Step
                    'ln_parent_intent_id' => $assessment2_in['in_id'],
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $rank3 => $assessment3_in){

                    //prep metadata:
                    $ln_metadata = unserialize($assessment3_in['ln_metadata']);

                    if(is_numeric($ln_metadata['tr__assessment_points']) && intval($ln_metadata['tr__assessment_points']) > 0){
                        $new_value = 5;
                    } else {
                        $new_value = -2;
                    }

                    if($new_value != intval($ln_metadata['tr__assessment_points'])){
                        update_metadata('ln', $assessment3_in['ln_id'], array(
                            'tr__assessment_points' => $new_value,
                        ), 1);
                        echo '[UPDATED]';
                    }


                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($rank+1).'.'.($rank2+1). '.'.($rank3+1). ') '. htmlentities($assessment3_in['in_outcome']).' ['.$ln_metadata['tr__assessment_points'].']<br />';
                }

            }
        }
    }

    //Loaded as default function of the default controller:
    function index()
    {

        $session_en = superpower_assigned();

        if ((isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'mench.co')) {

            //Go to mench.com for now:
            return redirect_message('https://mench.com');

        } else {

            //Go to focus intent
            return redirect_message('/read/next');

        }
    }


    function in_report_conditional_steps(){

        //Authenticate Trainer:
        $session_en = superpower_assigned(10984 /* RUDOLPH */);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['starting_in']) || intval($_POST['starting_in']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Starting Intent',
            ));
        } elseif (!isset($_POST['depth_levels']) || intval($_POST['depth_levels']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Depth',
            ));
        }

        //Fetch/Validate intent:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['starting_in'],
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ));
        if(count($ins) != 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not find intent #'.$_POST['starting_in'],
            ));
        }


        //Load AND/OR Intents:
        $en_all_7585 = $this->config->item('en_all_7585'); // Intent Subtypes
        $en_all_4737 = $this->config->item('en_all_4737'); // Intent Statuses


        //Return report:
        return echo_json(array(
            'status' => 1,
            'message' => '<h3>'.$en_all_7585[$ins[0]['in_completion_method_entity_id']]['m_icon'].' '.$en_all_4737[$ins[0]['in_status_entity_id']]['m_icon'].' '.echo_in_outcome($ins[0]['in_outcome'], false).'</h3>'.echo_in_answer_scores($_POST['starting_in'], $_POST['depth_levels'], $_POST['depth_levels'], $ins[0]['in_completion_method_entity_id']),
        ));

    }



    function in_submit_upvote($in_id){

        //Make sure it's a logged in trainer:
        $session_en = superpower_assigned(null, true);

        //Log up-vote:
        $this->READ_model->ln_create(array(
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_parent_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => 4983, //Intent Note Up-Votes
            'ln_content' => '@'.$session_en['en_id'],
            'ln_child_intent_id' => $in_id,
        ));

        //Go back to intention:
        return redirect_message('/blog/'.$in_id, '<div class="alert alert-success" role="alert"><i class="far fa-thumbs-up"></i> SUCCESSFULLY JOINED</div>');

    }


    function in_link_or_create()
    {

        /*
         *
         * Either creates a BLOG link between in_linked_id & in_link_child_id
         * OR will create a new intent with outcome in_outcome and then link it
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
                'message' => 'Missing Parent Intent ID',
            ));
        } elseif (!isset($_POST['is_parent']) || !in_array(intval($_POST['is_parent']), array(0,1))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Is Parent setting',
            ));
        } elseif (!isset($_POST['in_outcome']) || !isset($_POST['in_link_child_id']) || ( strlen($_POST['in_outcome']) < 1 && intval($_POST['in_link_child_id']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing either Intent Outcome OR Child Intent ID',
            ));
        } elseif (strlen($_POST['in_outcome']) > config_var(11071)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent outcome cannot be longer than '.config_var(11071).' characters',
            ));
        } elseif($_POST['in_link_child_id'] >= 2147483647){
            return echo_json(array(
                'status' => 0,
                'message' => 'Value must be less than 2147483647',
            ));
        }


        $new_intent_type = 6677; //Intent Read-Only
        $linked_ins = array();

        if($_POST['in_link_child_id'] > 0){

            //Fetch link intent to determine intent type:
            $linked_ins = $this->BLOG_model->in_fetch(array(
                'in_id' => intval($_POST['in_link_child_id']),
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            ));

            if(count($linked_ins)==0){
                //validate linked intent:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Intent #'.$_POST['in_link_child_id'].' is not active',
                ));
            }

            if(!intval($_POST['is_parent']) && in_array($linked_ins[0]['in_completion_method_entity_id'], $this->config->item('en_ids_7712'))){
                $new_intent_type = 6914; //Require All
            }
        }

        //All seems good, go ahead and try creating the intent:
        return echo_json($this->BLOG_model->in_link_or_create($_POST['in_linked_id'], intval($_POST['is_parent']), trim($_POST['in_outcome']), $session_en['en_id'], 6183 /* Intent New */, $new_intent_type, $_POST['in_link_child_id']));

    }



    function in_completion_rates(){
        $this->load->view('header', array(
            'title' => 'Completion Rates',
        ));
        $this->load->view('view_blog/in_completion_rates');
        $this->load->view('footer');
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
                'message' => 'Invalid Focus Intent ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Validate intent:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent not found',
            ));
        }


        //Fetch Action Plan users:
        $actionplan_users = $this->READ_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
            'ln_parent_intent_id' => $ins[0]['in_id'],
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
        ), array('ln_creator'), 500);
        if(count($actionplan_users) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => '<i class="fas fa-exclamation-triangle"></i> Nobody has completed this intention yet',
            ));
        }


        //Go through match list:
        $filters_list_counter = 0;
        $regular_list_counter = 0;
        $filters_list_ui = '';
        $regular_list_ui = '';

        foreach($actionplan_users as $apu){

            //Count user Action Plan Progression Completed:
            $count_progression = $this->READ_model->ln_fetch(array(
                'ln_creator_entity_id' => $apu['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //User Steps Progress
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');


            $regular_list_counter++;
            $current_count = $regular_list_counter;


            //Create the UI for this user:
            $item_ui = '<tr>';
            $item_ui .= '<td valign="top">'.$current_count.'</td>';
            $item_ui .= '<td style="text-align:left;">';
            $item_ui .= '<span class="icon-block en-icon">'.echo_en_icon($apu['en_icon']).'</span> '.$apu['en_name'];
            $item_ui .= ( strlen($apu['ln_content']) > 0 ? '<div class="user-comment">'.$this->READ_model->dispatch_message($apu['ln_content']).'</div>' : '' );
            $item_ui .= '</td>';

            $item_ui .= '<td style="text-align:left;"><a href="/read/view_json/'.$apu['ln_id'].'" target="_blank">'.echo_en_cache('en_all_6255' /* User Steps Progress */, $apu['ln_type_entity_id']).'</a></td>';
            $item_ui .= '<td style="text-align:left;">'.echo_number($count_progression[0]['totals']).'</td>';
            $item_ui .= '<td style="text-align:left;">'.echo_time_difference(strtotime($apu['ln_timestamp'])).'</td>';
            $item_ui .= '<td style="text-align:left;">';

            $item_ui .= '<a href="/blog/'.$_POST['in_loaded_id'].'#actionplanusers-'.$_POST['in_id'].'" data-toggle="tooltip" data-placement="top" title="Filter by this user"><i class="far fa-filter"></i></a>';
            $item_ui .= '&nbsp;<a href="/play/'.$apu['en_id'].'" data-toggle="tooltip" data-placement="top" title="User Entity"><i class="fas fa-at"></i></a>';

            $item_ui .= '&nbsp;<a href="/read/ledger?ln_creator_entity_id='.$apu['en_id'].'" data-toggle="tooltip" data-placement="top" title="Full User History"><i class="fas fa-link"></i></a>';

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


    function in_modify_save()
    {

        $en_all_6103 = $this->config->item('en_all_6103'); //Link Metadata

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        $ln_id = intval($_POST['ln_id']);

        //Validate intent:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
        ), array('in__parents'));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_id',
            ));
        } elseif (!isset($_POST['tr__conditional_score_min']) || !isset($_POST['tr__conditional_score_max'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing tr__conditional_score_min or tr__conditional_score_max',
            ));
        } elseif (!isset($_POST['tr__assessment_points'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing tr__assessment_points',
            ));
        } elseif (intval($_POST['tr__assessment_points'])!=$_POST['tr__assessment_points'] || $_POST['tr__assessment_points']<config_var(11056) || $_POST['tr__assessment_points']>config_var(11057)) {
            return echo_json(array(
                'status' => 0,
                'message' => $en_all_6103[4358]['m_name'].' must be an integer between '.config_var(11056).' - '.config_var(11057),
            ));
        } elseif (!isset($_POST['in_completion_method_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_completion_method_entity_id',
            ));
        } elseif (!isset($_POST['in_outcome'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing in_outcome',
            ));
        } elseif (!isset($_POST['in_completion_seconds']) || intval($_POST['in_completion_seconds']) < 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing in_completion_seconds',
            ));
        } elseif (!isset($_POST['in_status_entity_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing in_status_entity_id',
            ));
        } elseif (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent Not Found',
            ));
        } elseif($ln_id > 0 && intval($_POST['ln_type_entity_id']) == 4229){
            //Conditional Step Links, we require range values:
            if(strlen($_POST['tr__conditional_score_min']) < 1 || !is_numeric($_POST['tr__conditional_score_min'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Missing MIN range, enter 0 or greater',
                ));
            } elseif(strlen($_POST['tr__conditional_score_max']) < 1 || !is_numeric($_POST['tr__conditional_score_max'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Missing MAX range, enter 0 or greater',
                ));
            } elseif(doubleval($_POST['tr__conditional_score_min']) > doubleval($_POST['tr__conditional_score_max'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'MIN range cannot be larger than MAX',
                ));
            }
        } elseif (!in_array($_POST['in_completion_method_entity_id'], $this->config->item('en_ids_7585'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_completion_method_entity_id',
            ));
        } elseif (!in_array($_POST['in_status_entity_id'], $this->config->item('en_ids_4737'))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid in_status_entity_id',
            ));
        }


        //Validate Intent Outcome:
        $in_outcome_validation = $this->BLOG_model->in_outcome_validate($_POST['in_outcome']);
        if(!$in_outcome_validation['status']){
            //We had an error, return it:
            return echo_json($in_outcome_validation);
        }

        //Transform intent type into standard DB field:
        $in_current = $ins[0];

        //So we consistently have all variables in POST:
        $_POST['in_outcome'] = $in_outcome_validation['in_cleaned_outcome'];


        //Prep new variables:
        $in_update = array(
            'in_completion_method_entity_id' => $_POST['in_completion_method_entity_id'],
            'in_status_entity_id' => $_POST['in_status_entity_id'],
            'in_completion_seconds' => intval($_POST['in_completion_seconds']),
            'in_outcome' => $_POST['in_outcome'],
        );


        //Prep current intent metadata:
        $in_metadata = unserialize($in_current['in_metadata']);

        //Determines if Intent has been removed OR unlinked:
        $remove_from_ui = 0; //Assume not

        //Did anything change?
        $recursive_update_count = 0;

        //Check to see which variables actually changed:
        foreach ($in_update as $key => $value) {

            //Did this value change?
            if ($value == $in_current[$key]) {

                //No it did not! Remove it!
                unset($in_update[$key]);

            } else {

                if ($key == 'in_status_entity_id') {

                    $links_removed = 0;

                    //Has intent been removed?
                    if(!in_array($value, $this->config->item('en_ids_7356') /* Intent Statuses Active */)){

                        //Intent has been removed:
                        $remove_from_ui = 1;

                        //Unlink intent links:
                        $links_removed += $this->BLOG_model->in_unlink($_POST['in_id'] , $session_en['en_id']);

                        //Will be removed soon:
                        $recursive_update_count++;

                        //Treat as if no link (Since it was removed):
                        $ln_id = 0;

                    }
                }

                //This field has been updated, update one field at a time:
                $this->BLOG_model->in_update($_POST['in_id'], array(
                    $key => $_POST[$key],
                ), true, $session_en['en_id']);

            }
        }




        //Assume link is not updated:
        $link_was_updated = false;


        //Does this request has a BLOG link?
        if($ln_id > 0){

            //Validate Link and inputs:
            $lns = $this->READ_model->ln_fetch(array(
                'ln_id' => $ln_id,
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(( $_POST['is_parent'] ? 'in_child' : 'in_parent')));
            if(count($lns) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid READ ID',
                ));
            }


            if($_POST['ln_status_entity_id'] != $lns[0]['ln_status_entity_id']){

                if(!in_array($_POST['ln_status_entity_id'], $this->config->item('en_ids_7360'))){
                    //No longer active:
                    $remove_from_ui = 1;
                    $ln_type_entity_id = 10686; //Intent Link Unlinked
                } else {
                    $ln_type_entity_id = 10661; //Intent Link Iterated Status
                }

                $this->READ_model->ln_update($ln_id, array(
                    'ln_status_entity_id' => $_POST['ln_status_entity_id'],
                ), $session_en['en_id'], $ln_type_entity_id);
            }


            if(in_array($_POST['ln_status_entity_id'], $this->config->item('en_ids_7360') /* Link Statuses Active */)){

                if($_POST['ln_type_entity_id'] != $lns[0]['ln_type_entity_id']){
                    $this->READ_model->ln_update($ln_id, array(
                        'ln_type_entity_id' => $_POST['ln_type_entity_id'],
                    ), $session_en['en_id'], 10662 /* Intent Link Iterated Type */);
                }

                //Prep Metadata:
                $ln_metadata = ( strlen($lns[0]['ln_metadata']) > 0 ? unserialize($lns[0]['ln_metadata']) : array() );

                if($_POST['ln_type_entity_id'] == 4228 && (
                        (!isset($ln_metadata['tr__assessment_points']) && intval($_POST['tr__assessment_points'])!=0) ||
                        (isset($ln_metadata['tr__assessment_points']) && intval($ln_metadata['tr__assessment_points'])!=intval($_POST['tr__assessment_points']))
                    )){

                    $this->READ_model->ln_update($ln_id, array(
                        'ln_metadata' => array_merge( $ln_metadata, array(
                            'tr__assessment_points' => intval($_POST['tr__assessment_points']),
                        )),
                    ), $session_en['en_id'], 10663 /* Intent Link Iterated Marks */, 'Marks iterated'.( isset($ln_metadata['tr__assessment_points']) ? ' from [' . $ln_metadata['tr__assessment_points']. ']' : '' ).' to [' . $_POST['tr__assessment_points']. ']');
                }

                if($_POST['ln_type_entity_id'] == 4229 && (
                        (!isset($ln_metadata['tr__conditional_score_max']) && intval($_POST['tr__conditional_score_max'])!=0) ||
                        (!isset($ln_metadata['tr__conditional_score_min']) && intval($_POST['tr__conditional_score_min'])!=0) ||
                        (isset($ln_metadata['tr__conditional_score_max']) && doubleval($ln_metadata['tr__conditional_score_max'])!=doubleval($_POST['tr__conditional_score_max'])) ||
                        (isset($ln_metadata['tr__conditional_score_min']) && doubleval($ln_metadata['tr__conditional_score_min'])!=doubleval($_POST['tr__conditional_score_min']))
                    )){
                    $this->READ_model->ln_update($ln_id, array(
                        'ln_metadata' => array_merge( $ln_metadata, array(
                            'tr__conditional_score_min' => doubleval($_POST['tr__conditional_score_min']),
                            'tr__conditional_score_max' => doubleval($_POST['tr__conditional_score_max']),
                        )),
                    ), $session_en['en_id'], 10664 /* Intent Link Iterated Score */, 'Score Range iterated'.( isset($ln_metadata['tr__conditional_score_min']) && isset($ln_metadata['tr__conditional_score_max']) ? ' from [' . $ln_metadata['tr__conditional_score_min'].'% - '.$ln_metadata['tr__conditional_score_max']. '%]' : '' ).' to [' . $_POST['tr__conditional_score_min'].'% - '.$_POST['tr__conditional_score_max']. '%]');
                }
            }
        }




        //Let's see how many intents, if any, have unlocked completions:
        //See if we should check for unlocking this intent:
        //Keep track of stats for reporting:
        $ins_unlocked_completions_count = 0;
        $steps_unlocked_completions_count = 0;

        //Should we check for new unlocks?
        if(!in_is_unlockable($in_current) /* Old Settings */ && in_is_unlockable($_POST) /* New Settings */){

            //First see if this locked intent is completed for any users:
            $step_completed_users = array();

        }

        //Show success:
        return echo_json(array(
            'status' => 1,
            'message' => '<i class="fas fa-check"></i> Saved',
            'remove_from_ui' => $remove_from_ui,
            'formatted_in_outcome' => ( isset($in_update['in_outcome']) ? echo_in_outcome($in_update['in_outcome'], false) : null ),
            'recursive_update_count' => $recursive_update_count,
            'in__metadata_max_steps' => -( isset($in_metadata['in__metadata_max_steps']) ? $in_metadata['in__metadata_max_steps'] : 0 ),

            //Passon unlock data, if any:
            'ins_unlocked_completions_count' => $ins_unlocked_completions_count,
            'steps_unlocked_completions_count' => $steps_unlocked_completions_count,
        ));

    }

    function in_review_metadata($in_id){
        //Fetch Intent:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        if(count($ins) > 0){
            echo_json(unserialize($ins[0]['in_metadata']));
        } else {
            echo 'Intent #'.$in_id.' not found!';
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

            //Validate Parent intent:
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
                    'ln_parent_intent_id' => intval($_POST['in_id']),
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

                //Update them all:
                foreach ($_POST['new_ln_orders'] as $rank => $ln_id) {
                    $this->READ_model->ln_update(intval($ln_id), array(
                        'ln_order' => intval($rank),
                    ), $session_en['en_id'], 10675 /* Intents Ordered by Trainer */);
                }

                //Fetch again for the record:
                $children_after = $this->READ_model->ln_fetch(array(
                    'ln_parent_intent_id' => intval($_POST['in_id']),
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

                //Display message:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-check"></i> Sorted',
                ));
            }
        }
    }


    function in_new_message_from_text()
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
                'message' => 'Invalid Intent ID',
            ));

        } elseif (!isset($_POST['focus_ln_type_entity_id']) || intval($_POST['focus_ln_type_entity_id']) < 1) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Message Type',
            ));

        }


        //Fetch/Validate the intent:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => intval($_POST['in_id']),
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent',
            ));
        }

        //Make sure message is all good:
        $msg_validation = $this->READ_model->dispatch_validate_message($_POST['ln_content'], $session_en, false, array(), $_POST['focus_ln_type_entity_id'], $_POST['in_id']);

        if (!$msg_validation['status']) {
            //There was some sort of an error:
            return echo_json($msg_validation);
        }

        //Create Message:
        $ln = $this->READ_model->ln_create(array(
            'ln_status_entity_id' => 6176, //Link Published
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_order' => 1 + $this->READ_model->ln_max_order(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'ln_type_entity_id' => intval($_POST['focus_ln_type_entity_id']),
                    'ln_child_intent_id' => intval($_POST['in_id']),
                )),
            //Referencing attributes:
            'ln_type_entity_id' => intval($_POST['focus_ln_type_entity_id']),
            'ln_parent_entity_id' => $msg_validation['ln_parent_entity_id'],
            'ln_parent_intent_id' => $msg_validation['ln_parent_intent_id'],
            'ln_child_intent_id' => intval($_POST['in_id']),
            'ln_content' => $msg_validation['input_message'],
        ), true);

        //Print the challenge:
        return echo_json(array(
            'status' => 1,
            'message' => echo_in_note(array_merge($ln, array(
                'ln_child_entity_id' => $session_en['en_id'],
            ))),
        ));
    }


    function in_message_from_attachment()
    {

        //Authenticate Trainer:
        $session_en = superpower_assigned();
        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));

        } elseif (!isset($_POST['in_id']) || !isset($_POST['focus_ln_type_entity_id'])) {

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing intent data.',
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

        //Validate Intent:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if(count($ins)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
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
            'ln_status_entity_id' => 6176, //Link Published
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => $_POST['focus_ln_type_entity_id'],
            'ln_parent_entity_id' => $cdn_status['cdn_en']['en_id'],
            'ln_child_intent_id' => intval($_POST['in_id']),
            'ln_content' => '@' . $cdn_status['cdn_en']['en_id'], //Just place the entity reference as the entire message
            'ln_order' => 1 + $this->READ_model->ln_max_order(array(
                    'ln_type_entity_id' => $_POST['focus_ln_type_entity_id'],
                    'ln_child_intent_id' => $_POST['in_id'],
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
                'ln_child_entity_id' => $session_en['en_id'],
            ))),
        ));
    }


    function in_load_data()
    {

        /*
         *
         * An AJAX function that is triggered every time a Trainer
         * selects to modify a BLOG. It will check the
         * Requires Manual Response of a BLOG so it can
         * check proper boxes to help Trainer modify the intent.
         *
         * */

        $session_en = superpower_assigned();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Expired Session or Missing Superpower',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            ));
        } elseif (!isset($_POST['ln_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent READ ID',
            ));
        }

        //Fetch Intent:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
        }

        //Prep metadata:
        $ins[0]['in_metadata'] = ( strlen($ins[0]['in_metadata']) > 0 ? unserialize($ins[0]['in_metadata']) : array());


        if(intval($_POST['ln_id'])>0){

            //Fetch intent link:
            $lns = $this->READ_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(( $_POST['is_parent'] ? 'in_child' : 'in_parent' )));

            if(count($lns) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Intent READ ID',
                ));
            }

            //Add link connector:
            $lns[0]['ln_metadata'] = ( strlen($lns[0]['ln_metadata']) > 0 ? unserialize($lns[0]['ln_metadata']) : array());

            //Make sure marks are set:
            if(!isset($lns[0]['ln_metadata']['tr__assessment_points'])){
                $lns[0]['ln_metadata']['tr__assessment_points'] = 0;
            }

        }

        $actionplan_users = $this->READ_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
            'ln_parent_intent_id' => $_POST['in_id'],
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_steps');

        //Return results:
        return echo_json(array(
            'status' => 1,
            'in' => $ins[0],
            'in_action_plan_count' => $actionplan_users[0]['total_steps'],
            'ln' => ( isset($lns[0]) ? $lns[0] : array() ),
        ));

    }



    function in_message_sort()
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
                ), $session_en['en_id'], 10676 /* Intent Notes Ordered */);
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
        } elseif (!isset($_POST['message_ln_status_entity_id'])) {
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
                'message' => 'Invalid Intent ID',
            ));
        }

        //Validate Intent:
        $ins = $this->BLOG_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Intent Not Found',
            ));
        }

        //Validate Message:
        $messages = $this->READ_model->ln_fetch(array(
            'ln_id' => intval($_POST['ln_id']),
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ));
        if (count($messages) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Message Not Found',
            ));
        }

        //Validate new message:
        $msg_validation = $this->READ_model->dispatch_validate_message($_POST['ln_content'], $session_en, false, array(), $messages[0]['ln_type_entity_id'], $_POST['in_id']);
        if (!$msg_validation['status']) {

            //There was some sort of an error:
            return echo_json($msg_validation);

        } elseif($messages[0]['ln_content'] != $msg_validation['input_message']) {

            //Now update the DB:
            $this->READ_model->ln_update(intval($_POST['ln_id']), array(
                'ln_content' => $msg_validation['input_message'],
                'ln_parent_entity_id' => $msg_validation['ln_parent_entity_id'],
                'ln_parent_intent_id' => $msg_validation['ln_parent_intent_id'],
            ), $session_en['en_id'], 10679 /* Intent Notes Iterated Content */, word_change_calculator($messages[0]['ln_content'], $msg_validation['input_message']));

        }


        //Did the message status change?
        if($messages[0]['ln_status_entity_id'] != $_POST['message_ln_status_entity_id']){

            //Are we deleting this message?
            if(in_array($_POST['message_ln_status_entity_id'], $this->config->item('en_ids_7360') /* Link Statuses Active */)){

                //If making the link public, all referenced entities must also be public...
                if(in_array($_POST['message_ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)){

                    //We're publishing, make sure potential entity references are also published:
                    $string_references = extract_references($_POST['ln_content']);

                    if (count($string_references['ref_entities']) > 0) {

                        //We do have an entity reference, what's its status?
                        $ref_ens = $this->PLAY_model->en_fetch(array(
                            'en_id' => $string_references['ref_entities'][0],
                        ));

                        if(count($ref_ens)>0 && !in_array($ref_ens[0]['en_status_entity_id'], $this->config->item('en_ids_7357') /* Entity Statuses Public */)){
                            return echo_json(array(
                                'status' => 0,
                                'message' => 'You cannot published this message because its referenced entity is not yet public',
                            ));
                        }
                    }
                }

                //yes, do so and return results:
                $affected_rows = $this->READ_model->ln_update(intval($_POST['ln_id']), array(
                    'ln_status_entity_id' => $_POST['message_ln_status_entity_id'],
                ), $session_en['en_id'], 10677 /* Intent Notes Iterated Status */);

            } else {

                //New status is no longer active, so remove the intent note:
                $affected_rows = $this->READ_model->ln_update(intval($_POST['ln_id']), array(
                    'ln_status_entity_id' => $_POST['message_ln_status_entity_id'],
                ), $session_en['en_id'], 10678 /* Intent Notes Unlinked */);

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
            'message_new_status_icon' => '<span title="' . $en_all_6186[$_POST['message_ln_status_entity_id']]['m_name'] . ': ' . $en_all_6186[$_POST['message_ln_status_entity_id']]['m_desc'] . '" data-toggle="tooltip" data-placement="top">' . $en_all_6186[$_POST['message_ln_status_entity_id']]['m_icon'] . '</span>', //This might have changed
            'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));

    }




    function cron__sync_common_base($in_id = 0)
    {

        /*
         *
         * Updates common base metadata for published intents
         *
         * */

        if($in_id < 0){
            //Gateway URL to give option to run...
            die('<a href="/blog/cron__sync_common_base">Click here</a> to start running this function.');
        }

        boost_power();
        $start_time = time();
        $filters = array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        );
        if($in_id > 0){
            $filters['in_id'] = $in_id;
        }

        $published_ins = $this->BLOG_model->in_fetch($filters);
        foreach($published_ins as $published_in){
            $tree = $this->BLOG_model->in_metadata_common_base($published_in);
        }

        $total_time = time() - $start_time;
        $success_message = 'Common Base Metadata updated for '.count($published_ins).' published intent'.echo__s(count($published_ins)).'.';
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
         * Updates tree insights (like min/max steps, time & cost)
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

            //Update all Recommended Intentions and their tree:
            foreach ($this->BLOG_model->in_fetch(array(
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'in_completion_method_entity_id IN (' . join(',', $this->config->item('en_ids_7582')) . ')' => null, //READ LOGIN REQUIRED
            )) as $published_in) {
                $tree = $this->BLOG_model->in_metadata_extra_insights($published_in['in_id']);
                if($tree){
                    $update_count++;
                }
            }

        }



        $end_time = time() - $start_time;
        $success_message = 'Extra Insights Metadata updated for '.$update_count.' intent'.echo__s($update_count).'.';

        //Show json:
        echo_json(array(
            'message' => $success_message,
            'total_time' => echo_time_minutes($end_time),
            'item_time' => round(($end_time/$update_count),1).' Seconds',
            'last_tree' => $tree,
        ));
    }

}