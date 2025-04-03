<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Nodeplayers extends CIdea_cache
{

    /*
     *
     * Member related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function activate_subscription($playerid, $linkplayerdomain = 0)
    {


        //Remove from Anonymous:
        foreach ($this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
            'linkplayerup IN (' . join(',', $this->config->item('playerids___32540')) . ')' => null, //Unsubscribers
            'linkplayerdown' => $playerid,
        )) as $unsubscriber_x) {
            $this->Menchledger->void($unsubscriber_x['linkid'], $playerid);
        }

        $session_data = $this->session->all_userdata();
        $this->session->set_userdata($session_data);


        //Add to Subscriber:
        $this->Menchledger->create(array(
            'linkplayerup' => 4430, //Subscriber
            'linkplayertype' => 4230,
            'linkplayercreator' => $playerid,
            'linkplayerdown' => $playerid,
            'linkplayerdomain' => $linkplayerdomain,
        ));


    }


    function activate_session($e, $update_session = false, $is_cookie = false)
    {

        //PROFILE
        $session_data = array(
            'session_up' => $e,
            'session_up_ids' => array(),
            'session_superpowers_unlocked' => array(),
        );

        //Make sure they also belong to this website's members:
        $this->Nodeplayers->add_regular_player(website_setting(0), $e['playerid']);


        //Check & Adjust their subscription, IF needed:
        //Remove their subscribe:
        $resubscribed = 0;
        foreach ($this->Menchledger->fetch(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___29648')) . ')' => null, //Unsubscribers
            'linkplayerdown' => $e['playerid'],
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        )) as $unsubscribe) {
            $resubscribed += $this->Menchledger->void($unsubscribe['linkid'], $e['playerid']);
        }
        if ($resubscribed > 0) {
            //Add Back to Subscribers:
            $this->Menchledger->create(array(
                'linkplayertype' => 4230,
                'linkplayerup' => 4430, //Active Member
                'linkplayercreator' => $e['playerid'],
                'linkplayerdown' => $e['playerid'],
            ));
        }


        if (!$update_session && !$is_cookie) {
            //Create Cookie:
            $cookie_time = time();
            $cookie_val = $e['playerid'] . 'ABCEFG' . $cookie_time . 'ABCEFG' . view_hash($e['playerid'] . $cookie_time);
            setcookie('auth_cookie', $cookie_val, ($cookie_time + (86400 * view_memory(6404, 14031))), "/");
        }


        //Fetch Platform Defaults:
        $platform_theme = array();
        foreach ($this->Menchledger->fetch(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___14926')) . ')' => null, //Website Theme Items
            'linkplayerdown' => 6404, //Platform Default
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array(), 0) as $x) {
            array_push($platform_theme, intval($x['linkplayerup']));
        }

        //Fetch Website Defaults:
        $website_theme = array();
        foreach ($this->Menchledger->fetch(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___14926')) . ')' => null, //Website Theme Items
            'linkplayerdown' => website_setting(0), //Website ID
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array(), 0) as $x) {
            array_push($website_theme, intval($x['linkplayerup']));
        }


        //Fetch User Defaults:
        $user_theme = array();
        foreach ($this->Menchledger->fetch(array(
            'linkplayerdown' => $e['playerid'], //This follower Player
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array('linkplayerup'), 0) as $player_up) {

            //Push to followings IDs:
            array_push($session_data['session_up_ids'], intval($player_up['playerid']));

            //Website Theme Items?
            if (in_array($player_up['playerid'], $this->config->item('playerids___14926'))) {
                array_push($user_theme, intval($player_up['playerid']));
            }

            //Superpower?
            if (in_array($player_up['playerid'], $this->config->item('playerids___10957'))) {

                //It's unlocked!
                array_push($session_data['session_superpowers_unlocked'], intval($player_up['playerid']));
            }
        }


        //Determine Defaults if missing any of the CUSTOM UI
        foreach ($this->config->item('players___13890') as $playerid => $m) {

            //Set Default:
            $session_data['session_custom_ui_' . $playerid] = 0;

            //First try to find User Theme, if any:
            if (!$session_data['session_custom_ui_' . $playerid]) {
                foreach ($this->config->item('players___' . $playerid) as $playerid2 => $m2) {
                    if (in_array($playerid2, $user_theme)) {
                        $session_data['session_custom_ui_' . $playerid] = $playerid2;
                        break;
                    }
                }
            }

            //Then try to find Website Theme, if any:
            if (!$session_data['session_custom_ui_' . $playerid]) {
                foreach ($this->config->item('players___' . $playerid) as $playerid2 => $m2) {
                    if (in_array($playerid2, $website_theme)) {
                        $session_data['session_custom_ui_' . $playerid] = $playerid2;
                        break;
                    }
                }
            }


            //Finally try Platform Theme:
            if (!$session_data['session_custom_ui_' . $playerid]) {
                //First try to find Website Default, if any:
                foreach ($this->config->item('players___' . $playerid) as $playerid2 => $m2) {
                    if (in_array($playerid2, $platform_theme)) {
                        $session_data['session_custom_ui_' . $playerid] = $playerid2;
                        break;
                    }
                }
            }
        }


        //SESSION
        $this->session->set_userdata($session_data);


        //Resubscribe IF they are Permanently Unsubscribed:
        /*
        $unsubscribed_time = null;
        foreach($this->Menchledger->fetch(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___31057')) . ')' => null, //Permanently Unsubscribed
            'linkplayerdown' => $e['playerid'], //This follower Player
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                ), array(), 0) as $unsubscribed){
            $unsubscribed_time = $unsubscribed['linktime'];
            $this->Menchledger->void($unsubscribed['linkid'], $e['playerid']); //Resubscribe
        }
        if($unsubscribed_time){
            //Add to subscribed again:
            $this->Menchledger->create(array(
                'linkplayertype' => 4230,
                'linkplayerup' => 4430, //Active Member
                'linkplayercreator' => $e['playerid'],
                'linkplayerdown' => $e['playerid'],
            ));
            $this->session->set_flashdata('flash_message', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-user-check"></i></span>Welcome Back! You Have Been Re-Subscribed :)</div>');
        }
        */

        return $session_data;

    }


    function add_regular_player($linkplayerup, $linkplayerdown, $linktext = null)
    {
        //Add if link not already there:
        if (!count($this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
            'linkplayerup' => $linkplayerup,
            'linkplayerdown' => $linkplayerdown,
            'linktext' => $linktext,
        )))) {
            $this->Menchledger->create(array(
                'linkplayercreator' => $linkplayerdown, //Belongs to this Member
                'linkplayertype' => 4230,
                'linktext' => $linktext,
                'linkplayerup' => $linkplayerup,
                'linkplayerdown' => $linkplayerdown,
            ));
        }
    }

    function scissor_player($linkplayerup, $sub_id)
    {

        $all_results = $this->Menchledger->fetch(array(
            'linkplayerup' => $linkplayerup,
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array('linkplayerdown'), 0, 0, sort__player());

        //Remove if not in the secondary group:
        foreach ($all_results as $key => $primary_list) {
            if (!count($this->Menchledger->fetch(array(
                'linkplayerup' => $sub_id,
                'linkplayerdown' => $primary_list['playerid'],
                'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
            ), array(), 0))) {
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }

    function scissor_i($linkplayerup, $sub_id)
    {

        $all_results = $this->Menchledger->fetch(array(
            'linkplayerup' => $linkplayerup,
            'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
        ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC'));

        //Remove if not in the secondary group:
        foreach ($all_results as $key => $primary_list) {
            if (!count($this->Menchledger->fetch(array(
                'linkplayerup' => $sub_id,
                'linkidearight' => $primary_list['ideaid'],
                'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
            )))) {
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }


    function add_member($full_name, $email = null, $phone_number = null, $image_url = null, $linkplayerdomain = 0)
    {

        //Set website if not set:
        if (!$linkplayerdomain) {
            $linkplayerdomain = website_setting(0);
        }

        //All good, create new Player:
        $new_private_users = in_array($linkplayerdomain, $this->config->item('playerids___44011'));
        $added_e = $this->Nodeplayers->create($full_name, 0, ($image_url ? $image_url : random_cover(12279)));
        if (!$added_e['status']) {
            //We had an error, return it:
            return $added_e;
        } elseif ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid Email',
            );
        } elseif ($phone_number && (!intval($phone_number) || strlen($phone_number) < 7)) {
            return array(
                'status' => 0,
                'message' => 'Invalid Phone',
            );
        }

        //Add email?
        if ($email) {
            $this->Menchledger->create(array(
                'linkplayertype' => 4230,
                'linktext' => trim(strtolower($email)),
                'linkplayerup' => 3288, //Email
                'linkplayercreator' => $added_e['new_player']['playerid'],
                'linkplayerdown' => $added_e['new_player']['playerid'],
                'linkplayerdomain' => $linkplayerdomain,
            ));
        }

        //Add Number?
        if ($phone_number) {
            $this->Menchledger->create(array(
                'linkplayerup' => 4783, //Phone
                'linkplayertype' => 4230,
                'linktext' => $phone_number,
                'linkplayercreator' => $added_e['new_player']['playerid'],
                'linkplayerdown' => $added_e['new_player']['playerid'],
                'linkplayerdomain' => $linkplayerdomain,
            ));
        }

        if ($email || $phone_number) {

            $this->Nodeplayers->activate_subscription($added_e['new_player']['playerid'], $linkplayerdomain);

        } else {

            //Add to anonymous:
            $this->Menchledger->create(array(
                'linkplayerup' => 14938, //Guest Login
                'linkplayertype' => 4230,
                'linkplayercreator' => $added_e['new_player']['playerid'],
                'linkplayerdown' => $added_e['new_player']['playerid'],
                'linkplayerdomain' => $linkplayerdomain,
            ));

            //Assign session key:
            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

        }


        //Add member to Domain Member Group(s):
        $this->Nodeplayers->add_regular_player($linkplayerdomain, $added_e['new_player']['playerid']);


        //Send Welcome Email if any:
        if ($email) {
            foreach ($this->Menchledger->fetch(array(
                'linkplayertype' => 33600, //Draft
                'linkplayerup' => 14929, //Website Welcome Email Templates
            ), array('linkidearight'), 0) as $i) {
                if (count($this->Menchledger->fetch(array(
                    'linkplayertype' => 33600, //Draft
                    'linkplayerup' => $linkplayerdomain, //for Current website
                    'linkidearight' => $i['ideaid'], //Is this the template?
                )))) {
                    //Found the email template to send:
                    $total_sent = $this->Menchledger->send_idea_mass_dm(array($added_e['new_player']), $i, $linkplayerdomain);
                    break; //Just the first template match
                }
            }
        }

        //Update Search Index:
        flag_for_search_indexing(12274, $added_e['new_player']['playerid']);

        //Assign session & log login transaction:
        $this->Nodeplayers->activate_session($added_e['new_player']);


        //Return Member:
        return array(
            'status' => 1,
            'e' => $added_e['new_player'],
        );

    }

    function fetch($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array('playerid' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target Players:
        $this->db->select($select);
        $this->db->from('nodeplayers');
        foreach ($query_filters as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if ($group_by) {
            $this->db->group_by($group_by);
        }
        foreach ($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }

        $q = $this->db->get();
        $results = $q->result_array();


        //Make sure user has access to each item:
        if ($select == '*' && 0) {
            foreach ($results as $key => $value) {
                if (!access_level_player(null, $value['playerid'], $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }

        return $results;

    }

    function fetch_recursive($linkplayertype, $playerid, $include_any_e = array(), $exclude_all_e = array(), $hard_level = 3, $hard_limit = 100, $s__level = 0)
    {

        $flat_items = array();
        $s__level++;

        if (in_array($linkplayertype, $this->config->item('playerids___42276'))) {

            //Up Player Link Groups:
            $order_columns = array('linkplayertype = \'41011\' DESC' => null, 'linknumber' => 'ASC', 'linktime' => 'DESC');
            $joins_objects = array('linkplayerup');
            $query_filters = array(
                'linkplayerdown' => $playerid,
                'linkplayertype IN (' . join(',', $this->config->item('playerids___' . $linkplayertype)) . ')' => null, //SOURCE LINKS
            );

        } elseif (in_array($linkplayertype, $this->config->item('playerids___42377'))) {

            //Down Player Link Groups:
            $order_columns = array('linkplayertype = \'41011\' DESC' => null, 'linknumber' => 'ASC', 'linktime' => 'DESC');
            $joins_objects = array('linkplayerdown');
            $query_filters = array(
                'linkplayerup' => $playerid,
                'linkplayertype IN (' . join(',', $this->config->item('playerids___' . $linkplayertype)) . ')' => null, //SOURCE LINKS
            );

        } else {

            return false;

        }


        foreach ($this->Menchledger->fetch($query_filters, $joins_objects, 0, 0, $order_columns) as $player_down) {

            //Filter Players, if needed:
            $qualified_e = true;
            if (count($include_any_e) && !count($this->Menchledger->fetch(array(
                    'linkplayerup IN (' . join(',', $include_any_e) . ')' => null,
                    'linkplayerdown' => $player_down['playerid'],
                    'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                )))) {
                //Must include all Players, skip:
                $qualified_e = false;
            }
            if (count($exclude_all_e) && count($this->Menchledger->fetch(array(
                    'linkplayerup IN (' . join(',', $exclude_all_e) . ')' => null,
                    'linkplayerdown' => $player_down['playerid'],
                    'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                )))) {
                //Must Exclude If Has ALL Players, skip:
                $qualified_e = false;
            }


            //Is this a new matching Player?
            if ($qualified_e && !isset($flat_items[$player_down['playerid']])) {
                $player_down['s__level'] = $s__level;
                $player_down['s__count'] = count($flat_items) + 1;
                $flat_items[$player_down['playerid']] = $player_down;
            }

            //Do we have more followers?
            if ($s__level >= $hard_level || count($flat_items) >= $hard_limit) {
                break;
            }

            foreach ($this->Nodeplayers->fetch_recursive($linkplayertype, $player_down['playerid'], $include_any_e, $exclude_all_e, $hard_level, $hard_limit, $s__level) as $player_recursive_down) {
                if (!isset($flat_items[$player_recursive_down['playerid']])) {
                    $player_recursive_down['s__count'] = count($flat_items) + 1;
                    $flat_items[$player_recursive_down['playerid']] = $player_recursive_down;
                }
            }
        }

        return $flat_items;
    }

    function update($id, $update_columns, $external_sync = false)
    {
        if (count($update_columns) == 0) {
            return false;
        }
        //Update:
        $this->db->where('playerid', intval($id));
        $this->db->update('nodeplayers', $update_columns);
        $affected_rows = $this->db->affected_rows();
        if ($affected_rows && $external_sync) {
            //Sync algolia:
            flag_for_search_indexing(12274, intval($id));
        }
        return $affected_rows;
    }


    function radio_set($player_up_bucket_id, $setplayer_down_id, $linkplayercreator)
    {

        /*
         * Treats an Player follower group as a drop down menu where:
         *
         *  $player_up_bucket_id is the followings of the drop down
         *  $linkplayercreator is the member Player ID that one of the followers of $player_up_bucket_id should be assigned (like a drop down)
         *  $setplayer_down_id is the new value to be assigned, which could also be null (meaning just delete all current values)
         *
         * This function is helpful to manage things like Member communication levels
         *
         * */


        //Fetch all the follower Players for $player_up_bucket_id and make sure they match $setplayer_down_id
        $followers = $this->config->item('playerids___' . $player_up_bucket_id);
        if ($player_up_bucket_id < 1) {
            return false;
        } elseif (!$followers) {
            return false;
        } elseif ($setplayer_down_id > 0 && !in_array($setplayer_down_id, $followers)) {
            return false;
        }

        //First delete existing following/follower transactions for this drop down:
        $previously_assigned = ($setplayer_down_id < 1);
        $x_update_id = 0;
        foreach ($this->Menchledger->fetch(array(
            'linkplayerdown' => $linkplayercreator,
            'linkplayerup IN (' . join(',', $followers) . ')' => null, //Current followers
        ), array(), view_memory(6404, 11064)) as $x) {

            if (!$previously_assigned && $x['linkplayerup'] == $setplayer_down_id) {
                $previously_assigned = true;
            } else {
                //Delete assignment:
                $x_update_id = $x['linkid'];

                //Do not log update transaction here as we would log it further below:
                $this->Menchledger->void($x['linkid'], $linkplayercreator);
            }

        }


        //Make sure $setplayer_down_id belongs to followings if set (Could be null which means delete all)
        if (!$previously_assigned) {
            //Let's go ahead and add desired Player as parent:
            $this->Menchledger->create(array(
                'linkplayercreator' => $linkplayercreator,
                'linkplayerdown' => $linkplayercreator,
                'linkplayerup' => $setplayer_down_id,
                'linkplayertype' => 4230,
            ));
        }

    }

    function remove_duplicate_links($playerid)
    {

        //A function that scans Player followings links and removes duplicates

        $current_up = array();
        $duplicates_removed = 0;

        //Check followings to see if there are duplicates:
        foreach ($this->Menchledger->fetch(array(
            'linkplayerdown' => $playerid,
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array('linkplayerup'), 0, 0, array('linkplayerup' => 'ASC', 'linkid' => 'ASC')) as $x) {

            //Does this match any in the list so far?
            $duplicate_found = false;
            foreach ($current_up as $up) {
                if ($up['linkplayerup'] == $x['linkplayerup'] && $up['linkplayertype'] == $x['linkplayertype'] && $up['linktext'] == $x['linktext']) {
                    $duplicate_found = true;
                    break;
                }
            }

            if ($duplicate_found) {
                //Remove it:
                $duplicates_removed++;
                $this->Menchledger->void($x['linkid'], $x['linkplayercreator']); //Duplicate Link Removed
            } else {
                //Add it to main list:
                array_push($current_up, array(
                    'linkplayerup' => $x['linkplayerup'],
                    'linkplayertype' => $x['linkplayertype'],
                    'linktext' => $x['linktext'],
                ));
            }

        }

        return $duplicates_removed;

    }


    function remove($playerid, $linkplayercreator = 0, $migrate_s__id = 0)
    {

        if ($playerid < 1) {
            return 0;
        }

        //Fetch all SOURCE LINKS:
        $x_adjusted = 0;

        if ($migrate_s__id && 0) {

            //Migrate Transactions:
            /*
            $this->db->query("UPDATE menchledger SET linkplayerup=".$migrate_s__id." WHERE linkplayerup=".$playerid.";");
            $affected_linkplayerup = $this->db->affected_rows();
            $x_adjusted += $affected_linkplayerup;
            $this->db->query("UPDATE menchledger SET linkplayerdown=".$migrate_s__id." WHERE linkplayerdown=".$playerid.";");
            $affected_linkplayerdown = $this->db->affected_rows();
            $x_adjusted += $affected_linkplayerdown;
            $this->db->query("UPDATE menchledger SET linkplayercreator=".$migrate_s__id." WHERE linkplayercreator=".$playerid.";");
            $affected_linkplayercreator = $this->db->affected_rows();
            $x_adjusted += $affected_linkplayercreator;
            $this->db->query("UPDATE menchledger SET linkplayertype=".$migrate_s__id." WHERE linkplayertype=".$playerid.";");
            $affected_linkplayertype = $this->db->affected_rows();
            $x_adjusted += $affected_linkplayertype;
            $this->db->query("UPDATE menchledger SET linkplayerdomain=".$migrate_s__id." WHERE linkplayerdomain=".$playerid.";");
            $affected_linkplayerdomain = $this->db->affected_rows();
            $x_adjusted += $affected_linkplayerdomain;
            */

            //Clean Duplicates:
            $duplicates_removed = $this->Nodeplayers->remove_duplicate_links($migrate_s__id);
            $x_adjusted += $duplicates_removed;

        } else {

            //REMOVE TRANSACTIONS
            foreach ($this->Menchledger->fetch(array(
                '(linkplayerdown = ' . $playerid . ' OR linkplayerup = ' . $playerid . ' OR linkplayercreator = ' . $playerid . ')' => null,
            ), array(), 0) as $adjust_tr) {
                //Delete this transaction:
                $x_adjusted += $this->Menchledger->void($adjust_tr['linkid'], $linkplayercreator);
            }

        }

        return $x_adjusted;
    }


    function mass_update($playerid, $action_playerid, $action_command1, $action_command2, $linkplayercreator)
    {

        //Alert: Has a twin function called i_mass_update()

        boost_power();

        $action_command1 = trim($action_command1);
        $action_command2 = trim($action_command2);


        if (!in_array($action_playerid, $this->config->item('playerids___4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif (in_array($action_playerid, array(5981, 5982, 11956, 13441)) && !view_valid_handle_player($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Player. Format must be: @PlayerHandle',
            );

        } elseif (in_array($action_playerid, array(11956)) && !view_valid_handle_player($action_command2)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Player. Format must be: @PlayerHandle',
            );

        }


        //Basic input validation done, let's continue
        $applied_success = 0; //To be populated

        //Fetch all followers:
        $followers = $this->Menchledger->fetch(array(
            'linkplayerup' => $playerid,
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array('linkplayerdown'), 0);


        //Process request:
        foreach ($followers as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_playerid == 4998) { //Add Prefix String

                $this->Nodeplayers->update($x['playerid'], array(
                    'playertext' => $action_command1 . $x['playertext'],
                ), true, $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 4999) { //Add Postfix String

                $this->Nodeplayers->update($x['playerid'], array(
                    'playertext' => $x['playertext'] . $action_command1,
                ), true, $linkplayercreator);

                $applied_success++;

            } elseif (in_array($action_playerid, array(5981, 5982, 11956, 13441)) && view_valid_handle_player($action_command1)) { //Add/Delete/Migrate followings Player

                //What member searched for:
                foreach ($this->Nodeplayers->fetch(array(
                    'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command1)),
                )) as $e) {

                    //See if follower Player has searched followings Player:
                    $down_up_e = $this->Menchledger->fetch(array(
                        'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                        'linkplayerdown' => $x['playerid'], //This follower Player
                        'linkplayerup' => $e['playerid'],
                    ));

                    if ((in_array($action_playerid, array(5981, 13441)) && count($down_up_e) == 0)) {

                        $add_fields = array(
                            'linkplayercreator' => $linkplayercreator,
                            'linkplayertype' => 4230,
                            'linkplayerdown' => $x['playerid'], //This follower Player
                            'linkplayerup' => $e['playerid'],
                        );

                        if ($action_playerid == 13441) {
                            //Copy message only if moving:
                            $add_fields['linktext'] = $x['linktext'];
                        }

                        //Following Member Addition
                        $this->Menchledger->create($add_fields);

                        $applied_success++;

                        if ($action_playerid == 13441) {
                            //Since we're migrating we should remove from here:
                            $this->Menchledger->void($x['linkid'], $linkplayercreator);
                        }

                    } elseif (in_array($action_playerid, array(5982, 11956)) && count($down_up_e) > 0) {

                        if ($action_playerid == 5982) {

                            //Following Member Removal
                            foreach ($down_up_e as $delete_tr) {
                                $this->Menchledger->void($delete_tr['linkid'], $linkplayercreator);
                                $applied_success++;
                            }

                        } elseif ($action_playerid == 11956 && view_valid_handle_player($action_command2)) {

                            foreach ($this->Nodeplayers->fetch(array(
                                'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command2)),
                            )) as $e) {
                                //Add as a followings because it meets the condition
                                $this->Menchledger->create(array(
                                    'linkplayercreator' => $linkplayercreator,
                                    'linkplayertype' => 4230,
                                    'linkplayerdown' => $x['playerid'], //This follower Player
                                    'linkplayerup' => $e['playerid'],
                                ));
                                $applied_success++;
                            }
                        }
                    }
                }

            } elseif ($action_playerid == 5943) { //Member Mass Update Member Cover

                $this->Nodeplayers->update($x['playerid'], array(
                    'playercover' => $action_command1,
                ), true, $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 12318 && !strlen($x['playercover'])) { //Member Mass Update Member Cover

                $this->Nodeplayers->update($x['playerid'], array(
                    'playercover' => $action_command1,
                ), true, $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 5000 && substr_count(strtolower($x['playertext']), strtolower($action_command1)) > 0) { //Replace Member Matching Name

                $this->Nodeplayers->update($x['playerid'], array(
                    'playertext' => str_ireplace($action_command1, $action_command2, $x['playertext']),
                ), true, $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 10625 && substr_count($x['playercover'], $action_command1) > 0) { //Replace Member Matching Cover

                $this->Nodeplayers->update($x['playerid'], array(
                    'playercover' => str_replace($action_command1, $action_command2, $x['playercover']),
                ), true, $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 5001 && substr_count($x['linktext'], $action_command1) > 0) { //Replace Transaction Matching String

                $new_message = str_replace($action_command1, $action_command2, $x['linktext']);

                $this->Menchledger->update($x['linkid'], array(
                    'linktext' => $new_message,
                ), $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 26093) { //Replace Transaction Matching String

                $this->Menchledger->update($x['linkid'], array(
                    'linktext' => $action_command1,
                ), $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 42804 && ($action_command1 == '*' || $x['linkplayertype'] == $action_command1) && in_array($action_command2, $this->list_player_links_intentional /* Player Link Types */)) { //Update Matching Interaction Type

                $this->Menchledger->update($x['linkid'], array(
                    'linkplayertype' => $action_command2,
                ), $linkplayercreator);
                $applied_success++;

            }
        }

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($followers) . ' Players updated',
        );

    }


    function create($playertext, $linkplayercreator = 0, $playercover = null)
    {

        //Validate Title
        $validate_playertext = validate_playertext($playertext);
        if (!$validate_playertext['status']) {
            return $validate_playertext;
        }

        //Log transaction new Player:
        $player_e = superpower_unlocked();
        $creator = ($linkplayercreator > 0 ? $linkplayercreator : ($player_e ? $player_e['playerid'] : 14068));

        //Create New Player:
        $new_x = $this->Menchledger->create(array(
            'linkplayercreator' => $creator,
            'linkplayertype' => 4251, //New Player Created
            'linktext' => $validate_playertext['playertext_clean'],
        ));

        if (!$new_x['linkid']) {
            //Ooopsi, something went wrong!
            $this->Menchledger->create(array(
                'linkplayertype' => 44179, //Triggered
                'linkplayerup' => 4246, //Platform Bug Reports
                'linkplayerdown' => $creator,
                'linktext' => 'create() failed to create a new Player',
                'linkplayercreator' => $creator,
            ));
            return array(
                'status' => 0,
                'message' => 'Error trying to create Player',
            );
        }

        //Handle Generation
        $new_handle = generate_handle(12274, $validate_playertext['playertext_clean']);
        $this->Menchledger->create(array(
            'linkplayercreator' => $creator,
            'linkplayertype' => 4230, //Follow
            'linkplayerup' => 32338, //Player Handle
            'linktext' => $new_handle,
            'linkplayerdown' => $new_x['linkid'],
        ));

        //Cover saving if any
        if (strlen($playercover)) {
            $this->Menchledger->create(array(
                'linkplayercreator' => $creator,
                'linkplayertype' => 4230, //Follow
                'linkplayerup' => 6198, //Player Cover
                'linktext' => $playercover,
                'linkplayerdown' => $new_x['linkid'],
            ));
        }

        //Add to cache:
        $this->db->insert(' nodeplayers', array(
            'playerid' => $new_x['linkid'],
            'playerhandle' => $new_handle,
            'playercover' => $playercover,
            'playertext' => $validate_playertext['playertext_clean'],
        ));

        //Update Search Index:
        flag_for_search_indexing(12274, $new_x['linkid']);

        //Fetch to return the complete Player data:
        $es = $this->Nodeplayers->fetch(array(
            'playerid' => $new_x['linkid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'new_player' => $es[0],
        );

    }

}