<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    //To carry the user object after validation

    function __construct() {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }

    function ff(){
        echo_json($this->Db_model->li_fetch(array(
            'li_en_parent_id' => 4227, //All Entity Link Types
            'li_en_child_id >' => 0, //Must have a child
            'li_en_child_id !=' => 4230, //Not a Naked link as that is already the default option
            'li_status >=' => 0, //Not removed
            'en_status >=' => 2, //Syncing
        ), 100, array('en_child'), array('li_rank' => 'ASC')));
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

        //Delete everything before starting:
        $this->db->query("DELETE FROM table_entities WHERE en_id>0");
        $this->db->query("DELETE FROM table_links WHERE li_en_type_id IN (".join(',',array_merge($this->config->item('en_li_type_ids'),array(4235))).")"); //The link types we could create with this function

        $message_status_converter = array(
            1 => 4231,
            2 => 4232,
            3 => 4233,
        );

        $entities = $this->Db_model->en_fetch(array(
            'u_id' => 2,
            'u_status >=' => 0, //new+
        ), array('skip_u__parents','u__urls','u__ws'));

        echo_json($entities);exit;

        $stats = array(
            'entities' => 0,
            'entity_links' => 0,
            'urls' => 0,
            'set_icons' => 0,
            'action_plans' => 0,
            'total_links' => 0,
        );

        foreach($entities as $u){

            //Create new entity:
            $stats['entities']++;
            $this->Db_model->en_create(array(
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


    function e(){

        boost_power();


        $eng_converter = array(
            //Patternization Links
            20 => 4250, //Log intent creation
            6971 => 4251, //Log entity creation
            21 => 4252, //Log intent archived
            50 => 4254, //Log intent migration
            19 => 4264, //Log intent modification
            //0 => 4253, //Entity Archived (Did not have this!)

            36      => 4242, //Log intent message update
            7727    => 4242, //Log entity link note modification

            12      => 4263, //Log entity modification
            7001    => 4299, //Log pending image upload sync to cloud

            89      => 4241, //Log intent unlinked
            7292    => 4241, //Log entity unlinked
            35      => 4241, //Log intent message archived
            6912    => 4241, //Log entity URL archived

            39      => 4262, //Log intent message sorting
            22      => 4262, //Log intent children sorted


            //Growth links
            27 => 4265, //Log user joined
            5 => 4266, //Log Messenger optin
            4 => 4267, //Log Messenger referral
            3 => 4268, //Log Messenger postback
            10 => 4269, //Log user sign in
            11 => 4270, //Log user sign out
            59 => 4271, //Log user password reset


            //Personal Assistant links
            40 => 4273, //Log console tip read
            //0 => 4235, //Log new Action Plan intent [to be implemented]
            7703 => 4275, //Log subscription intent search
            28 => 4276, //Log user email sent
            6 => 4277, //Log message received
            1 => 4278, //Log message read
            2 => 4279, //Log message delivered
            7 => 4280, //Log message sent
            52 => 4281, //Log message queued
            55 => 4282, //Log my account access
            32 => 4283, //Log action plan access
            33 => 4242, //Log action plan intent completion [Link updated]
            7730 => 4284, //Log Skip Initiation
            7731 => 4285, //Log Skip Cancellation
            7732 => 4286, //Log Skip Confirmation
            7718 => 4287, //Log unrecognized message

            //Platform Operations Links:
            8 => 4246, //Log system bug
            9 => 4247, //Log user attention request
            72 => 4248, //Log user review
        );



    }


}