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


    function test()
    {

        echo echo_json($this->Db_model->in_fetch(array(
            ' NOT EXISTS (SELECT 1 FROM table_ledger WHERE in_id=tr_in_child_id AND tr_status>=0) ' => null,
        )));

    }


    function c()
    {

        die('pending final migration');

        boost_power();

        //Delete everything before starting:
        $this->db->query("DELETE FROM table_intents WHERE in_id>0");
        $this->db->query("DELETE FROM table_ledger WHERE tr_en_type_id IN (4231,4232,4233,4250,4228,4331);"); //The link types we could create with this function

        $message_status_converter = array(
            1 => 4231,
            2 => 4232,
            3 => 4233,
        );

        $intents = $this->Old_model->c_fetch(array(
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
                'in_metadata' => array(
                    'in__algolia_id' => intval($c['c_algolia_id']),
                ),
            ));
            //Create new intent creation link:
            $stats['total_links']++;
            $this->Db_model->tr_create(array(
                'tr_timestamp' => $c['c_timestamp'],
                'tr_en_type_id' => 4250, //Intent created
                'tr_en_credit_id' => $c['c_parent_u_id'],
                'tr_in_child_id' => $c['c_id'],
            ));


            if ($c['c_require_notes_to_complete']) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $c['c_timestamp'],
                    'tr_en_type_id' => 4331, //Intent Response Limitor
                    'tr_en_credit_id' => $c['c_parent_u_id'],
                    'tr_in_child_id' => $c['c_id'],
                    'tr_en_child_id' => 4255, //Link Content Type = Text Link
                ));
            }

            if ($c['c_require_url_to_complete']) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $c['c_timestamp'],
                    'tr_en_type_id' => 4331, //Intent Response Limitor
                    'tr_en_credit_id' => $c['c_parent_u_id'],
                    'tr_in_child_id' => $c['c_id'],
                    'tr_en_child_id' => 4256, //Link Content Type = URL Link
                ));
            }

            //convert active children:
            $children = $this->Old_model->cr_children_fetch(array(
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
                    'tr_en_credit_id' => $cr['cr_parent_u_id'],
                    'tr_in_parent_id' => $cr['cr_parent_c_id'],
                    'tr_in_child_id' => $cr['cr_child_c_id'],
                    'tr_order' => $cr['cr_child_rank'],
                ));
            }


            //convert active messages:
            $tr_contents = $this->Old_model->i_fetch(array(
                'i_c_id' => $c['c_id'],
                'i_status >=' => 1, //active
            ));
            foreach ($tr_contents as $i) {
                $stats['messages']++;
                $stats['total_links']++;
                //Create new link
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $i['i_timestamp'],
                    'tr_content' => $i['i_message'],
                    'tr_en_type_id' => $message_status_converter[$i['i_status']], //Message status migrating into new link type entity reference
                    'tr_en_credit_id' => $i['i_parent_u_id'],
                    'tr_en_parent_id' => $i['i_u_id'],
                    'tr_in_child_id' => $i['i_c_id'],
                    'tr_order' => $i['i_rank'],
                ));
            }
        }

        echo_json($stats);
    }

    function u()
    {

        die('pending final migration');

        boost_power();

        //Delete everything before starting:
        if(0){
            $this->db->query("DELETE FROM table_entities WHERE en_id>0");
            $this->db->query("DELETE FROM table_ledger WHERE tr_en_type_id IN (" . join(',', array_merge($this->config->item('en_ids_4537'), $this->config->item('en_ids_4538'), array(4251, 4235, 4299))) . ")"); //The link types we could create with this function
        }

        $u_status_conv = array(
            -2 => 3, //Unsubscribe
            -1 => -1, //Archived
            0 => 0,//New
            1 => 0,//Working on
            2 => 2,//Published
        );
        $x_type_conv = array(
            0 => 4256,
            1 => 4257,
            2 => 4258,
            3 => 4259,
            4 => 4260,
            5 => 4261,
        );
        $stats = array(
            'entities' => 0,
            'set_icons' => 0,
            'entity_links' => 0,
            'entity_urls' => 0,
            'entity_urls_matched' => 0,
            'action_plan_intent' => 0,
            'total_links' => 0,
        );

        $matching_patterns = $this->Old_model->ur_children_fetch(array(
            'ur_parent_u_id' => 3307, //Entity Matching Patterns
            'ur_status >=' => 0, //Pending or Active
            'u_status >=' => 0, //Pending or Active
        ));


        $entities = $this->Old_model->u_fetch(array(
            'u_id >' => 4518,
            'u_status >=' => 0, //new+
        ), array('skip_en__parents'), 0, 0, array('u_id' => 'ASC'));


        foreach ($entities as $u) {

            //Does this entity have a cover photo?
            if ($u['u_icon'] > 0) {
                $stats['set_icons']++;
                $en_icon = $u['u_icon'];
            } elseif ($u['u_cover_x_id'] > 0 && isset($u['x_url'])) {
                //Yes, fetch it:
                $stats['set_icons']++;
                $en_icon = '<img class="profile-icon" src="' . $u['x_url'] . '" />';
            } else {
                $en_icon = null;
            }

            //Create new entity:
            $stats['entities']++;
            $this->Db_model->en_create(array(
                'en_id' => $u['u_id'],
                'en_status' => ($u['u_fb_psid'] > 0 ? 3 /* Claimed */ : $u_status_conv[$u['u_status']]),
                'en_icon' => $en_icon,
                'en_name' => $u['u_full_name'],
                'en_trust_score' => $u['u__e_score'],
                'en_metadata' => array(
                    'en__algolia_id' => intval($u['u_algolia_id']),
                ),
            ));

            //Create new entity creation link:
            $stats['total_links']++;
            $this->Db_model->tr_create(array(
                'tr_timestamp' => $u['u_timestamp'],
                'tr_en_type_id' => 4251, //Entity created
                'tr_en_credit_id' => 1, //Shervin
                'tr_en_child_id' => $u['u_id'],
            ));


            //Messenger Subscription?
            if ($u['u_fb_psid'] > 0) {

                //Add them to masters group:
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => 4430, //Mench Master
                    'tr_en_child_id' => $u['u_id'],
                ));

                //Store their messenger ID:
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4319, //Number Link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => 4451, //Mench Personal Assistant on Messenger
                    'tr_en_child_id' => $u['u_id'],
                    'tr_content' => $u['u_fb_psid'],
                ));

                //Subscription Level:
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => ($u['u_status'] == -2 ? 4455 : 4456), //Either Unsubscribed or normal
                    'tr_en_child_id' => $u['u_id'],
                ));

            }


            //Email:
            if (strlen($u['u_email']) > 0 && filter_var($u['u_email'], FILTER_VALIDATE_EMAIL)) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4255, //Text link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => 3288, //Primary email
                    'tr_en_child_id' => $u['u_id'],
                    'tr_content' => $u['u_email'],
                ));
            }

            //Convert 4x relations:
            if (strlen($u['u_timezone']) > 0 && $this->Old_model->en_match_metadata('en_timezones', $u['u_timezone'])) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => $this->Old_model->en_match_metadata('en_timezones', $u['u_timezone']),
                    'tr_en_child_id' => $u['u_id'],
                ));
            }
            if (strlen($u['u_country_code']) == 2 && $this->Old_model->en_match_metadata('en_countries', $u['u_country_code'])) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => $this->Old_model->en_match_metadata('en_countries', $u['u_country_code']),
                    'tr_en_child_id' => $u['u_id'],
                ));
            }
            if (strlen($u['u_language']) > 0) {
                $parts = explode(',', $u['u_language']);
                foreach ($parts as $part) {
                    if (strlen($part) == 2 && $this->Old_model->en_match_metadata('en_languages', $part)) {
                        $stats['total_links']++;
                        $this->Db_model->tr_create(array(
                            'tr_timestamp' => $u['u_timestamp'],
                            'tr_en_type_id' => 4230, //Naked link
                            'tr_en_credit_id' => $u['u_id'],
                            'tr_en_parent_id' => $this->Old_model->en_match_metadata('en_languages', $part),
                            'tr_en_child_id' => $u['u_id'],
                        ));
                    }
                }
            }
            if (strlen($u['u_gender']) == 1 && $this->Old_model->en_match_metadata('en_gender', $u['u_gender'])) {
                $stats['total_links']++;
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => $this->Old_model->en_match_metadata('en_gender', $u['u_gender']),
                    'tr_en_child_id' => $u['u_id'],
                ));
            }


            //convert active children:
            $children = $this->Old_model->ur_children_fetch(array(
                'ur_parent_u_id' => $u['u_id'],
                'ur_status >=' => 0,
                'u_status >=' => 0,
            ));
            foreach ($children as $ur) {
                $stats['entity_links']++;
                $stats['total_links']++;
                //Create new link
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $ur['ur_timestamp'],
                    'tr_en_type_id' => detect_tr_en_type_id($ur['ur_notes']), //Depends on content
                    'tr_en_credit_id' => 1, //Shervin
                    'tr_en_parent_id' => $ur['ur_parent_u_id'],
                    'tr_en_child_id' => $ur['ur_child_u_id'],
                    'tr_content' => $ur['ur_notes'],
                ));
            }


            //convert active URLs:
            $urls = $this->Old_model->x_fetch(array(
                'x_status >' => -2,
                'x_u_id' => $u['u_id'],
            ));
            foreach ($urls as $x) {

                if ($x['x_id'] == $u['u_cover_x_id']) {
                    //Skip this as we've already done this:
                    continue;
                }

                //Check to make sure URL does not already existed in ledger:
                $duplicates = $this->Db_model->tr_fetch(array(
                    'tr_status >=' => 0, //Active in any way
                    'tr_content' => $x['x_url'],
                    'tr_en_type_id IN ('.join(',', $this->config->item('en_ids_4537')).')' => null, //Entity URL Links
                ));
                if (count($duplicates) > 0) {
                    //Ooops, already there:
                    continue;
                }

                //Fetch the appropriate parent using current patterns:
                $tr_en_parent_id = 1326; //URL Reference
                foreach ($matching_patterns as $match) {
                    if (substr_count($x['x_url'], $match['ur_notes']) > 0) {
                        //yes we found a pattern match:
                        $tr_en_parent_id = $match['u_id'];
                        $stats['entity_urls_matched']++;
                        break;
                    }
                }

                //Insert as URL relation:
                $stats['entity_urls']++;
                $stats['total_links']++;

                //Create new URL Link
                $this->Db_model->tr_create(array(
                    'tr_timestamp' => $x['x_timestamp'],
                    'tr_en_credit_id' => $x['x_parent_u_id'],
                    'tr_en_type_id' => $x_type_conv[$x['x_type']], //Depends on content
                    'tr_en_parent_id' => $tr_en_parent_id,
                    'tr_en_child_id' => $x['x_u_id'],
                    'tr_content' => $x['x_url'],
                ));

                //Do we have an attachment? Save that too:
                if ($x['x_fb_att_id'] > 0) {
                    $stats['total_links']++;
                    $this->Db_model->tr_create(array(
                        'tr_timestamp' => $x['x_timestamp'],
                        'tr_en_credit_id' => 0, //System
                        'tr_en_type_id' => 4319, //Number Link
                        'tr_en_parent_id' => 4505, //Facebook Attachment Upload API
                        'tr_en_child_id' => $x['x_u_id'],
                        'tr_content' => $x['x_fb_att_id'],
                    ));
                }

            }


            //Convert Action Plans for this user:
            $action_plan_rank = 1; //We add items in order...
            $ws = $this->Old_model->w_fetch(array(
                'w_child_u_id' => $u['u_id'],
                'w_status >=' => 1,
                'c_status >=' => 1,
            ), array('in', 'en'), array(
                'w_id' => 'ASC',
            ), 999);

            $counter = 0;
            foreach ($ws as $w) {

                $counter++; //We need to rank top level as well!
                //Insert top of the action plan item that is being added:
                $stats['action_plan_intent']++;
                $stats['total_links']++;
                $actionplan_tr = $this->Db_model->tr_create(array(
                    'tr_timestamp' => $w['w_timestamp'],
                    'tr_status' => $w['w_status'], //Same status meaning for all 5 levels

                    'tr_en_type_id' => 4235, //Action Plan Intent Add
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => $u['u_id'], //Belongs to this user

                    'tr_in_parent_id' => 0, //This indicates that this is a top-level intent in the Action Plan
                    'tr_tr_parent_id' => 0, //Again, indicates the top of the Action Plan
                    'tr_in_child_id' => $w['w_c_id'],
                    'tr_order' => $counter,
                ));

                //Now fetch all intents for this Action Plan:
                $ks = $this->Old_model->k_fetch(array(
                    'k_w_id' => $w['w_id'],
                ), array('cr'), array(
                    'k_rank' => 'ASC',
                ), 9999);

                foreach ($ks as $k) {

                    $stats['action_plan_intent']++;
                    $stats['total_links']++;
                    $this->Db_model->tr_create(array(
                        'tr_timestamp' => $k['k_timestamp'],
                        'tr_status' => $k['k_status'], //Same status meaning for all 5 levels

                        'tr_en_type_id' => 4235, //Action Plan Intent Add
                        'tr_en_credit_id' => $u['u_id'],
                        'tr_en_parent_id' => $u['u_id'], //Belongs to this user

                        'tr_in_parent_id' => $k['cr_parent_c_id'], //This indicates that this is a top-level intent in the Action Plan
                        'tr_in_child_id' => $k['cr_child_c_id'],
                        'tr_order' => $k['k_rank'],
                        'tr_content' => $k['k_notes'],

                        'tr_tr_parent_id' => $actionplan_tr['tr_id'], //Instantly show the top of the intention for that action plan
                    ));

                }


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
            7718 => 4287, //Log unrecognized message

            //Platform Operations Links:
            8 => 4246, //Log system bug
            9 => 4247, //Log user attention request
            72 => 4248, //Log user review
        );


    }


}