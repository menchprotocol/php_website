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



    function in()
    {

        fn___boost_power();

        //These intents will have their messages converted to rotational from on-start:
        $in_onstart_to_rotational = array(8332,8333,8334);
        $eng_converter = $this->config->item('eng_converter');
        $message_status_converter = array(
            1 => 4231,
            2 => 4232,
            3 => 4233,
        );
        $stats = array(
            'intents' => 0,
            'intents_skipped' => 0,
            'intents_links' => 0,
            'messages' => 0,
            'total_links' => 0,
            'metadata_cron_url' => 'https://mench.co/cron/fn___in_metadata_update',
        );

        foreach ($this->Old_model->c_fetch(array(
            'c_status >=' => 1, //working on or more
        ), 0, array(), array('c_id' => 'ASC')) as $c) {

            //Do not migrate the old transaction intents as they have now been moved to entities:
            if(array_key_exists($c['c_id'], $eng_converter)){
                $stats['intents_skipped']++;
                continue;
            }

            //Create new intent:
            $stats['intents']++;
            $this->Database_model->fn___in_create(array(
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
            $this->Database_model->fn___tr_create(array(
                'tr_timestamp' => $c['c_timestamp'],
                'tr_en_type_id' => 4250, //Intent created
                'tr_en_credit_id' => $c['c_parent_u_id'],
                'tr_in_child_id' => $c['c_id'],
            ));


            if ($c['c_require_notes_to_complete']) {
                $stats['total_links']++;
                $this->Database_model->fn___tr_create(array(
                    'tr_timestamp' => $c['c_timestamp'],
                    'tr_en_type_id' => 4331, //Intent Completion Requirements
                    'tr_en_credit_id' => $c['c_parent_u_id'],
                    'tr_in_child_id' => $c['c_id'],
                    'tr_en_parent_id' => 4255, // Link Content Type = Text Link
                ));
            }

            //convert active children:
            $children = $this->Old_model->cr_child_fetch(array(
                'cr_parent_c_id' => $c['c_id'],
                'cr_status' => 1,
                'c_status >=' => 1,
            ));
            foreach ($children as $cr) {
                $stats['intents_links']++;
                $stats['total_links']++;
                //Create new link
                $this->Database_model->fn___tr_create(array(
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
                $this->Database_model->fn___tr_create(array(
                    'tr_timestamp' => $i['i_timestamp'],
                    'tr_content' => $i['i_message'],
                    'tr_en_type_id' => ( in_array($c['c_id'],$in_onstart_to_rotational) ? 4234 /* Rotating Message */ : $message_status_converter[$i['i_status']] ), //Message status migrating into new link type entity reference
                    'tr_en_credit_id' => $i['i_parent_u_id'],
                    'tr_en_parent_id' => $i['i_u_id'],
                    'tr_in_child_id' => $i['i_c_id'],
                    'tr_order' => $i['i_rank'],
                ));
            }
        }

        //Update the table sequence:
        $this->db->query("SELECT setval('table_intents_in_id_seq', ".$c['c_id'].", true);");


        fn___echo_json($stats);
    }

    function en()
    {

        fn___boost_power();

        $u_status_conv = array(
            -2 => 3, //Unsubscribe
            -1 => -1, //Archived
            0 => 0,//New
            1 => 0,//Working on
            2 => 2,//Published
        );
        $x_type_conv = array(
            0 => 4256, //Generic URL
            1 => 4257, //Embed
            2 => 4258, //Video
            3 => 4259, //Audio
            4 => 4260, //Image
            5 => 4261, //File
        );
        $stats = array(
            'entities' => 0,
            'set_icons' => 0,
            'entity_links' => 0,
            'entity_urls' => 0,
            'entity_urls_matched' => 0,
            'action_plan_intent' => 0,
            'total_links' => 0,
            'php_cache_cron_url' => 'https://mench.co/cron/fn___matrix_cache',
        );

        $matching_patterns = $this->Old_model->ur_child_fetch(array(
            'ur_parent_u_id' => 3307, //Entity Matching Patterns
            'ur_status >=' => 0, //Pending or Active
            'u_status >=' => 0, //Pending or Active
        ));


        foreach ($this->Old_model->u_fetch(array(
            'u_status >=' => 0, //new+
        ), array('skip_en__parents'), 0, 0, array('u_id' => 'ASC')) as $u) {

            //Does this entity have a cover photo?
            if (strlen($u['u_icon']) > 0) {
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
            $this->Database_model->fn___en_create(array(
                'en_id' => $u['u_id'],
                'en_status' => ($u['u_fb_psid'] > 0 ? 3 /* Claimed */ : $u_status_conv[$u['u_status']]),
                'en_icon' => $en_icon,
                'en_name' => $u['u_full_name'],
                'en_trust_score' => $u['u__e_score'],
                'en_psid' => ( $u['u_fb_psid'] > 0 ? $u['u_fb_psid'] : null ),
                'en_metadata' => array(
                    'en__algolia_id' => intval($u['u_algolia_id']),
                ),
            ));

            //Create new entity creation link:
            $stats['total_links']++;
            $this->Database_model->fn___tr_create(array(
                'tr_timestamp' => $u['u_timestamp'],
                'tr_en_type_id' => 4251, //Entity created
                'tr_en_credit_id' => 1, //Shervin
                'tr_en_child_id' => $u['u_id'],
            ));


            //Messenger Subscription?
            if ($u['u_fb_psid'] > 0) {

                //Add them to masters group:
                $stats['total_links']++;
                $this->Database_model->fn___tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => 4430, //Mench Master
                    'tr_en_child_id' => $u['u_id'],
                ));

                //Subscription Level:
                $stats['total_links']++;
                $this->Database_model->fn___tr_create(array(
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
                $this->Database_model->fn___tr_create(array(
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
                $this->Database_model->fn___tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => $this->Old_model->en_match_metadata('en_timezones', $u['u_timezone']),
                    'tr_en_child_id' => $u['u_id'],
                ));
            }
            if (strlen($u['u_country_code']) == 2 && $this->Old_model->en_match_metadata('en_countries', $u['u_country_code'])) {
                $stats['total_links']++;
                $this->Database_model->fn___tr_create(array(
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
                        $this->Database_model->fn___tr_create(array(
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
                $this->Database_model->fn___tr_create(array(
                    'tr_timestamp' => $u['u_timestamp'],
                    'tr_en_type_id' => 4230, //Naked link
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => $this->Old_model->en_match_metadata('en_gender', $u['u_gender']),
                    'tr_en_child_id' => $u['u_id'],
                ));
            }


            //convert active children:
            $children = $this->Old_model->ur_child_fetch(array(
                'ur_parent_u_id' => $u['u_id'],
                'ur_status >=' => 0,
                'u_status >=' => 0,
            ));
            foreach ($children as $ur) {
                $stats['entity_links']++;
                $stats['total_links']++;
                //Create new link
                $this->Database_model->fn___tr_create(array(
                    'tr_timestamp' => $ur['ur_timestamp'],
                    'tr_en_type_id' => fn___detect_tr_en_type_id($ur['ur_notes']), //Depends on content
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
                $duplicates = $this->Database_model->fn___tr_fetch(array(
                    'tr_status >=' => 0, //Active in any way
                    'tr_content' => $x['x_url'],
                    'tr_en_type_id IN ('.join(',', $this->config->item('en_ids_4537')).')' => null, //Entity URL Links
                ));
                if (count($duplicates) > 0) {
                    //Ooops, already there:
                    continue;
                }

                //Fetch the appropriate parent using current patterns:
                $tr_en_parent_id = $this->config->item('en_default_parent_id'); // URL Reference
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
                $this->Database_model->fn___tr_create(array(
                    'tr_timestamp' => $x['x_timestamp'],
                    'tr_en_credit_id' => $x['x_parent_u_id'],
                    'tr_en_type_id' => $x_type_conv[$x['x_type']], //Depends on content
                    'tr_en_parent_id' => $tr_en_parent_id,
                    'tr_en_child_id' => $x['x_u_id'],
                    'tr_content' => $x['x_url'],
                    'tr_metadata' => ( $x['x_fb_att_id'] > 0 ? array( 'fb_att_id' => $x['x_fb_att_id'] ) : null ),
                ));

            }

            /*

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
                $actionplan_tr = $this->Database_model->fn___tr_create(array(
                    'tr_timestamp' => $w['w_timestamp'],
                    'tr_status' => $w['w_status'], //Same status meaning for all 5 levels
                    'tr_en_type_id' => 4235, //Action Plan
                    'tr_en_credit_id' => $u['u_id'],
                    'tr_en_parent_id' => $u['u_id'], //Belongs to this Master
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
                    $this->Database_model->fn___tr_create(array(
                        'tr_timestamp' => $k['k_timestamp'],
                        'tr_status' => $k['k_status'], //Same status meaning for all 5 levels
                        'tr_en_type_id' => 4559, //Action Plan Intent
                        'tr_tr_parent_id' => $actionplan_tr['tr_id'], //Indicates parent Action Plan ID
                        'tr_en_credit_id' => $u['u_id'],
                        'tr_en_parent_id' => $u['u_id'], //Belongs to this Master
                        'tr_in_parent_id' => $k['cr_parent_c_id'], //This indicates that this is a top-level intent in the Action Plan
                        'tr_in_child_id' => $k['cr_child_c_id'],
                        'tr_order' => $k['k_rank'],
                        'tr_content' => $k['k_notes'],
                    ));

                }


            }

            */

        }

        //Update the table sequence after a mass update:
        $this->db->query("SELECT setval('table_entities_en_id_seq', ".$u['u_id'].", true);");

        fn___echo_json($stats);
    }

}