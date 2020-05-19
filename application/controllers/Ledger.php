<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }



    function index(){

        /*
         *
         * List all Links on reverse chronological order
         * and Display Status for ideas, sources and
         * links.
         *
         * */

        //Load header:
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

        $this->load->view('header', array(
            'title' => $en_all_11035[4341]['m_name'],
        ));
        $this->load->view('read/read_ledger');
        $this->load->view('footer');

    }


    function ledger_load(){

        /*
         * Loads the list of links based on the
         * filters passed on.
         *
         * */

        $filters = unserialize($_POST['link_filters']);
        $join_by = unserialize($_POST['link_join_by']);
        $page_num = ( isset($_POST['page_num']) && intval($_POST['page_num'])>=2 ? intval($_POST['page_num']) : 1 );
        $next_page = ($page_num+1);
        $query_offset = (($page_num-1)*config_var(11064));
        $session_en = superpower_assigned();

        $message = '';

        //Fetch links and total link counts:
        $lns = $this->READ_model->fetch($filters, $join_by, config_var(11064), $query_offset);
        $lns_count = $this->READ_model->fetch($filters, $join_by, 0, 0, array(), 'COUNT(ln_id) as total_count');
        $total_items_loaded = ($query_offset+count($lns));
        $has_more_links = ($lns_count[0]['total_count'] > 0 && $total_items_loaded < $lns_count[0]['total_count']);


        //Display filter:
        if($total_items_loaded > 0){
            $message .= '<div class="montserrat ledger-info"><span class="icon-block"><i class="fas fa-file-search"></i></span>'.( $has_more_links && $query_offset==0  ? 'FIRST ' : ($query_offset+1).' - ' ) . ( $total_items_loaded >= ($query_offset+1) ?  $total_items_loaded . ' OF ' : '' ) . number_format($lns_count[0]['total_count'] , 0) .' TRANSACTIONS:</div>';
        }


        if(count($lns)>0){

            $message .= '<div class="list-group list-grey">';
            foreach($lns as $ln) {

                $message .= echo_ln($ln);

                if($session_en && strlen($ln['ln_content'])>0 && strlen($_POST['ln_content_search'])>0 && strlen($_POST['ln_content_replace'])>0 && substr_count($ln['ln_content'], $_POST['ln_content_search'])>0){

                    $new_content = str_replace($_POST['ln_content_search'],trim($_POST['ln_content_replace']),$ln['ln_content']);

                    $this->READ_model->update($ln['ln_id'], array(
                        'ln_content' => $new_content,
                    ), $session_en['en_id'], 12360, update_description($ln['ln_content'], $new_content));

                    $message .= '<div class="alert alert-info" role="alert"><i class="fas fa-check-circle"></i> Replaced ['.$_POST['ln_content_search'].'] with ['.trim($_POST['ln_content_replace']).']</div>';

                }

            }
            $message .= '</div>';

            //Do we have more to show?
            if($has_more_links){
                $message .= '<div id="link_page_'.$next_page.'"><a href="javascript:void(0);" style="margin:10px 0 72px 0;" class="btn btn-read" onclick="ledger_load(link_filters, link_join_by, '.$next_page.');"><span class="icon-block"><i class="fas fa-plus-circle"></i></span>Page '.$next_page.'</a></div>';
                $message .= '';
            } else {
                $message .= '<div style="margin:10px 0 72px 0;"><span class="icon-block"><i class="far fa-check-circle"></i></span>All '.$lns_count[0]['total_count'].' link'.echo__s($lns_count[0]['total_count']).' have been loaded</div>';

            }

        } else {

            //Show no link warning:
            $message .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>No Links found with the selected filters. Modify filters and try again.</div>';

        }


        return echo_json(array(
            'status' => 1,
            'message' => $message,
        ));


    }






    function echo_input_text_update(){

        //Authenticate Player:
        $session_en = superpower_assigned();
        $en_all_12112 = $this->config->item('en_all_12112');

        if (!$session_en) {

            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(),
                'original_val' => '',
            ));

        } elseif(!isset($_POST['object_id']) || !isset($_POST['cache_en_id']) || !isset($_POST['field_value'])){

            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core variables',
                'original_val' => '',
            ));

        } elseif($_POST['cache_en_id']==4736 /* IDEA TITLE */){

            $ins = $this->IDEA_model->fetch(array(
                'in_id' => $_POST['object_id'],
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
            ));
            if(!count($ins)){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID.',
                    'original_val' => '',
                ));
            }

            //Validate Idea Outcome:
            $in_title_validation = in_title_validate($_POST['field_value']);
            if(!$in_title_validation['status']){
                //We had an error, return it:
                return echo_json(array_merge($in_title_validation, array(
                    'original_val' => $ins[0]['in_title'],
                )));
            }


            //All good, go ahead and update:
            $this->IDEA_model->update($_POST['object_id'], array(
                'in_title' => trim($_POST['field_value']),
            ), true, $session_en['en_id']);

            return echo_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_en_id']==6197 /* SOURCE FULL NAME */){

            $ens = $this->SOURCE_model->fetch(array(
                'en_id' => $_POST['object_id'],
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //ACTIVE
            ));
            if(!count($ens)){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Source ID.',
                    'original_val' => '',
                ));
            }


            $en_name_validate = en_name_validate($_POST['field_value']);
            if(!$en_name_validate['status']){
                return echo_json(array_merge($en_name_validate, array(
                    'original_val' => $ens[0]['en_name'],
                )));
            }

            //All good, go ahead and update:
            $this->SOURCE_model->update($ens[0]['en_id'], array(
                'en_name' => $en_name_validate['en_clean_name'],
            ), true, $session_en['en_id']);

            //Reset user session data if this data belongs to the logged-in user:
            if ($ens[0]['en_id'] == $session_en['en_id']) {
                //Re-activate Session with new data:
                $ens[0]['en_name'] = $en_name_validate['en_clean_name'];
                $this->SOURCE_model->activate_session($ens[0], true);
            }

            return echo_json(array(
                'status' => 1,
            ));

        } elseif($_POST['cache_en_id']==4356 /* READ TIME */){

            $ins = $this->IDEA_model->fetch(array(
                'in_id' => $_POST['object_id'],
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
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
                    'original_val' => $ins[0]['in_time_seconds'],
                ));

            } elseif($_POST['field_value'] > config_var(4356)){

                $hours = rtrim(number_format((config_var(4356)/3600), 1), '.0');
                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' should be less than '.$hours.' Hour'.echo__s($hours).', or '.config_var(4356).' Seconds long. You can break down your idea into smaller ideas.',
                    'original_val' => $ins[0]['in_time_seconds'],
                ));

            } elseif($_POST['field_value'] < config_var(12427)){

                return echo_json(array(
                    'status' => 0,
                    'message' => $en_all_12112[$_POST['cache_en_id']]['m_name'].' should be at-least '.config_var(12427).' Seconds long. It takes time to read ideas ;)',
                    'original_val' => $ins[0]['in_time_seconds'],
                ));

            } else {

                //All good, go ahead and update:
                $this->IDEA_model->update($_POST['object_id'], array(
                    'in_time_seconds' => $_POST['field_value'],
                ), true, $session_en['en_id']);

                return echo_json(array(
                    'status' => 1,
                ));

            }

        } elseif($_POST['cache_en_id']==4358 /* READ MARKS */){

            //Fetch/Validate Link:
            $lns = $this->READ_model->fetch(array(
                'ln_id' => $_POST['object_id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            ));
            $ln_metadata = unserialize($lns[0]['ln_metadata']);
            if(!$ln_metadata){
                $ln_metadata = array();
            }

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
                $this->READ_model->update($_POST['object_id'], array(
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
            $lns = $this->READ_model->fetch(array(
                'ln_id' => $_POST['object_id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
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
                $this->READ_model->update($_POST['object_id'], array(
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
                'message' => 'Unknown Update Type ['.$_POST['cache_en_id'].']',
                'original_val' => '',
            ));

        }
    }



}
