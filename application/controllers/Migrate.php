<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    //To carry the user object after validation

    function __construct() {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }

    function c(){

        exit;
        boost_power();

        //Delete everything before starting:
        $this->db->query("DELETE FROM table_intents WHERE in_id>0");
        $this->db->query("DELETE FROM table_links WHERE li_en_type_id IN (4231,4232,4233,4250,4228,4331)"); //The link types we could create with this function


        $message_status_converter = array(
            1 => 4231,
            2 => 4232,
            3 => 4233,
        );

        $intents = $this->Db_model->in_fetch(array(
            'c_status >=' => 1, //working on or more
        ));
        $stats = array(
            'intents' => 0,
            'intents_links' => 0,
            'messages' => 0,
            'total_links' => 0,
        );

        foreach($intents as $c){


            //Create new intent:
            $stats['intents']++;
            $this->Db_model->in_create(array(
                'in_id' => $c['c_id'],
                'in_status' => $c['c_status'],
                'in_outcome' => substr($c['c_outcome'],0,89),
                'in_alternatives' => $c['c_trigger_statements'],
                'in_seconds' => round($c['c_time_estimate']*3600),
                'in_usd' => $c['c_cost_estimate'],
                'in_points' => $c['c_points'],
                'in_is_any' => $c['c_is_any'],
                'in_algolia_id' => $c['c_algolia_id'],
            ));
            //Create new intent creation link:
            $stats['total_links']++;
            $this->Db_model->li_create(array(
                'li_timestamp' => $c['c_timestamp'],
                'li_en_type_id' => 4250, //Intent created
                'li_en_creator_id' => $c['c_parent_u_id'],
                'li_in_child_id' => $c['c_id'],
            ));


            if($c['c_require_notes_to_complete']){
                $stats['total_links']++;
                $this->Db_model->li_create(array(
                    'li_timestamp' => $c['c_timestamp'],
                    'li_en_type_id' => 4331, //Intent Response Limitor
                    'li_en_creator_id' => $c['c_parent_u_id'],
                    'li_in_child_id' => $c['c_id'],
                    'li_en_child_id' => 4255, //Link Content Type = Text Link
                ));
            }

            if($c['c_require_url_to_complete']){
                $stats['total_links']++;
                $this->Db_model->li_create(array(
                    'li_timestamp' => $c['c_timestamp'],
                    'li_en_type_id' => 4331, //Intent Response Limitor
                    'li_en_creator_id' => $c['c_parent_u_id'],
                    'li_in_child_id' => $c['c_id'],
                    'li_en_child_id' => 4256, //Link Content Type = URL Link
                ));
            }




            //convert active children:
            $children = $this->Db_model->cr_children_fetch(array(
                'cr_parent_c_id' => $c['c_id'],
                'cr_status' => 1,
                'c_status >=' => 1,
            ));
            foreach($children as $cr){
                $stats['intents_links']++;
                $stats['total_links']++;
                //Create new link
                $this->Db_model->li_create(array(
                    'li_timestamp' => $cr['cr_timestamp'],
                    'li_en_type_id' => 4228, //Child intent link
                    'li_en_creator_id' => $cr['cr_parent_u_id'],
                    'li_in_parent_id' => $cr['cr_parent_c_id'],
                    'li_in_child_id' => $cr['cr_child_c_id'],
                    'li_rank' => $cr['cr_child_rank'],
                ));
            }



            //convert active messages:
            $i_messages = $this->Db_model->i_fetch(array(
                'i_c_id' => $c['c_id'],
                'i_status >=' => 1, //active
            ));
            foreach($i_messages as $i){
                $stats['messages']++;
                $stats['total_links']++;
                //Create new link
                $this->Db_model->li_create(array(
                    'li_timestamp' => $i['i_timestamp'],
                    'li_content' => $i['i_message'],
                    'li_en_type_id' => $message_status_converter[$i['i_status']], //Message status migrating into new link type entity reference
                    'li_en_creator_id' => $i['i_parent_u_id'],
                    'li_en_parent_id' => $i['i_u_id'],
                    'li_in_child_id' => $i['i_c_id'],
                    'li_rank' => $i['i_rank'],
                ));
            }
        }

        echo_json($stats);
    }

    function u(){
        boost_power();


    }

    function e(){
        boost_power();


    }


}