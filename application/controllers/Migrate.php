<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller
{

    //To carry the user object after validation

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }

    function ff()
    {
        echo_json($this->Db_model->li_fetch(array(
            'tr_en_parent_id' => 4227, //All Entity Link Types
            'tr_en_child_id >' => 0, //Must have a child
            'tr_en_child_id !=' => 4230, //Not a Naked link as that is already the default option
            'tr_status >=' => 0, //Not removed
            'en_status >=' => 2, //Syncing
        ), 100, array('en_child'), array('tr_rank' => 'ASC')));
    }

    function c()
    {

        exit;
        boost_power();

        //Delete everything before starting:
        $this->db->query("DELETE FROM table_intents WHERE in_id>0");
        $this->db->query("DELETE FROM table_ledger WHERE tr_en_type_id IN (4231,4232,4233,4250,4228,4331)"); //The link types we could create with this function


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

        foreach ($intents as $c) {


            //Create new intent:
            $stats['intents']++;
            $this->Db_model->in_create(array(
                'in_id' => $c['c_id'],
                'in_status' => $c['c_status'],
                'in_outcome' => substr($c['c_outcome'], 0, 89),
                'in_alternatives' => $c['c_trigger_statements'],
                'in_seconds' => round($c['c_time_estimate'] * 3600),
                'in_usd' => $c['c_cost_estimate'],
                'in_points' => $c['c_points'],
                'in_is_any' => $c['c_is_any'],
                'in_algolia_id' => $c['c_algolia_id'],
            ));
            //Create new intent creation link:
            $stats['total_links']++;
            $this->Db_model->tr_create(array(
                'tr_timestamp' => $c['c_timestamp'],
                'tr_en_type_id' => 4250, //Intent created
                'tr_en_creator_id' => $c['c_parent_u_id'],
                'tr_in_child_id' => $c['c_id'],
            ));


            if ($c['c_require_notes_to_complete']) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $c['c_timestamp'],
                    'tr_en_type_id' => 4331, //Intent Response Limitor
                    'tr_en_creator_id' => $c['c_parent_u_id'],
                    'tr_in_child_id' => $c['c_id'],
                    'tr_en_child_id' => 4255, //Link Content Type = Text Link
                ));
            }

            if ($c['c_require_url_to_complete']) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $c['c_timestamp'],
                    'tr_en_type_id' => 4331, //Intent Response Limitor
                    'tr_en_creator_id' => $c['c_parent_u_id'],
                    'tr_in_child_id' => $c['c_id'],
                    'tr_en_child_id' => 4256, //Link Content Type = URL Link
                ));
            }


            //convert active children:
            $children = $this->Db_model->cr_children_fetch(array(
                'cr_parent_c_id' => $c['c_id'],
                'cr_status' => 1,
                'c_status >=' => 1,
            ));
            foreach ($children as $cr) {
                $stats['intents_links']++;
                $stats['total_links']++;
                //Create new link
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $cr['cr_timestamp'],
                    'tr_en_type_id' => 4228, //Child intent link
                    'tr_en_creator_id' => $cr['cr_parent_u_id'],
                    'tr_in_parent_id' => $cr['cr_parent_c_id'],
                    'tr_in_child_id' => $cr['cr_child_c_id'],
                    'tr_rank' => $cr['cr_child_rank'],
                ));
            }


            //convert active messages:
            $i_messages = $this->Db_model->i_fetch(array(
                'i_c_id' => $c['c_id'],
                'i_status >=' => 1, //active
            ));
            foreach ($i_messages as $i) {
                $stats['messages']++;
                $stats['total_links']++;
                //Create new link
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $i['i_timestamp'],
                    'tr_content' => $i['i_message'],
                    'tr_en_type_id' => $message_status_converter[$i['i_status']], //Message status migrating into new link type entity reference
                    'tr_en_creator_id' => $i['i_parent_u_id'],
                    'tr_en_parent_id' => $i['i_u_id'],
                    'tr_in_child_id' => $i['i_c_id'],
                    'tr_rank' => $i['i_rank'],
                ));
            }
        }

        echo_json($stats);
    }

    function u()
    {
        boost_power();

        //Delete everything before starting:
        $this->db->query("DELETE FROM table_entities WHERE en_id>0");
        $this->db->query("DELETE FROM table_ledger WHERE tr_en_type_id IN (" . join(',', array_merge($this->config->item('en_child_4227'), array(4235))) . ")"); //The link types we could create with this function

        $u_status_conv = array(
            -2 => 0,
            -1 => -1,
            0 => 0,
            1 => 0,
            2 => 2,
        );

        $entities = $this->Db_model->en_fetch(array(
            'u_id' => 2,
            'u_status >=' => 0, //new+
        ), array('skip_en__parents', 'u__urls', 'u__ws'));

        echo_json($entities);
        exit;

        $stats = array(
            'entities' => 0,
            'entity_links' => 0,
            'urls' => 0,
            'set_icons' => 0,
            'action_plans' => 0,
            'total_links' => 0,
        );

        foreach ($entities as $u) {

            //Create new entity:
            $stats['entities']++;
            $this->Db_model->en_create(array(
                'en_id' => $u['u_id'],
                'en_status' => $u_status_conv[$u['u_status']],
                'en_icon' => $u['u_icon'],
                'en_name' => $u['u_full_name'],
                'en_messenger_psid' => intval($u['u_fb_psid']),
                'en_communication' => ($u['u_fb_psid'] > 0 ? ($u['u_status'] == -2 ? -1 : $u['u_fb_notification']) : 0),
                'en_rating' => $u['u__e_score'],
                'en_is_any' => $u['u_is_any'],
                'en_algolia_id' => $u['u_algolia_id'],
            ));
            //Create new entity creation link:
            $stats['total_links']++;
            $this->Db_model->tr_create(array(
                'tr_timestamp' => $u['u_timestamp'],
                'tr_en_type_id' => 4251, //Entity created
                'tr_en_creator_id' => $u['u_id'],
                'tr_in_child_id' => $u['u_id'],
            ));


            //Email/password?:
            if (strlen($u['u_email']) > 0 && filter_var($u['u_email'], FILTER_VALIDATE_EMAIL)) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4255, //Text link
                    'tr_en_creator_id' => $u['u_id'],
                    'tr_en_parent_id' => 3288, //Primary email
                    'tr_en_child_id' => $u['u_id'],
                    'tr_content' => $u['u_email'],
                ));
            }
            if (strlen($u['u_password']) > 0) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4255, //Text link
                    'tr_en_creator_id' => $u['u_id'],
                    'tr_en_parent_id' => 3288, //Primary email
                    'tr_en_child_id' => $u['u_id'],
                    'tr_content' => $u['u_email'],
                ));
            }

            //Convert 4x relations:
            if (strlen($u['u_timezone']) > 0 && en_match_metadata('en_timezones', $u['u_timezone'])) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_creator_id' => $u['u_id'],
                    'tr_en_parent_id' => en_match_metadata('en_timezones', $u['u_timezone']),
                    'tr_en_child_id' => $u['u_id'],
                ));
            }
            if (strlen($u['u_country_code']) == 2 && en_match_metadata('en_countries', $u['u_country_code'])) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_creator_id' => $u['u_id'],
                    'tr_en_parent_id' => en_match_metadata('en_countries', $u['u_country_code']),
                    'tr_en_child_id' => $u['u_id'],
                ));
            }
            if (strlen($u['u_language']) > 0) {
                $parts = explode(',', $u['u_language']);
                foreach ($parts as $part) {
                    if (strlen($part) == 2 && en_match_metadata('en_languages', $part)) {
                        $stats['total_links']++;
                        $this->Db_model->tr_create(array(
                            'tr_timestamp' => $u['u_timestamp'],
                            'tr_en_type_id' => 4230, //Naked link
                            'tr_en_creator_id' => $u['u_id'],
                            'tr_en_parent_id' => en_match_metadata('en_languages', $part),
                            'tr_en_child_id' => $u['u_id'],
                        ));
                    }
                }
            }
            if (strlen($u['u_gender']) == 1 && en_match_metadata('en_gender', $u['u_gender'])) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_creator_id' => $u['u_id'],
                    'tr_en_parent_id' => en_match_metadata('en_gender', $u['u_gender']),
                    'tr_en_child_id' => $u['u_id'],
                ));
            }


            if ($u['u_require_url_to_complete']) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4331, //Intent Response Limitor
                    'tr_en_creator_id' => $u['u_parent_u_id'],
                    'tr_in_child_id' => $u['u_id'],
                    'tr_en_child_id' => 4256, //Link Content Type = URL Link
                ));
            }


            //convert active children:
            $children = $this->Db_model->cr_children_fetch(array(
                'cr_parent_c_id' => $u['u_id'],
                'cr_status' => 1,
                'c_status >=' => 1,
            ));
            foreach ($children as $cr) {
                $stats['intents_links']++;
                $stats['total_links']++;
                //Create new link
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $cr['cr_timestamp'],
                    'tr_en_type_id' => 4228, //Child intent link
                    'tr_en_creator_id' => $cr['cr_parent_u_id'],
                    'tr_in_parent_id' => $cr['cr_parent_c_id'],
                    'tr_in_child_id' => $cr['cr_child_c_id'],
                    'tr_rank' => $cr['cr_child_rank'],
                ));
            }


            //convert active messages:
            $i_messages = $this->Db_model->i_fetch(array(
                'i_c_id' => $c['c_id'],
                'i_status >=' => 1, //active
            ));
            foreach ($i_messages as $i) {
                $stats['messages']++;
                $stats['total_links']++;
                //Create new link
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $i['i_timestamp'],
                    'tr_content' => $i['i_message'],
                    'tr_en_type_id' => $message_status_converter[$i['i_status']], //Message status migrating into new link type entity reference
                    'tr_en_creator_id' => $i['i_parent_u_id'],
                    'tr_en_parent_id' => $i['i_u_id'],
                    'tr_in_child_id' => $i['i_c_id'],
                    'tr_rank' => $i['i_rank'],
                ));
            }
        }

        echo_json($stats);
    }


    function e()
    {

        boost_power();


        $eng_converter = array(
            //Patternization Links
            20 => 4250, //Log intent creation
            6971 => 4251, //Log entity creation
            21 => 4252, //Log intent archived
            50 => 4254, //Log intent migration
            19 => 4264, //Log intent modification
            //0 => 4253, //Entity Archived (Did not have this!)

            36 => 4242, //Log intent message update
            7727 => 4242, //Log entity link note modification

            12 => 4263, //Log entity modification
            7001 => 4299, //Log pending image upload sync to cloud

            89 => 4241, //Log intent unlinked
            7292 => 4241, //Log entity unlinked
            35 => 4241, //Log intent message archived
            6912 => 4241, //Log entity URL archived

            39 => 4262, //Log intent message sorting
            22 => 4262, //Log intent children sorted


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