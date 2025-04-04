<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Players extends CIdea_cache
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

    function create($playertext, $linkplayercreator = 0, $playercover = null)
    {

        //Validate Title
        $validate_playertext = validate_playertext($playertext);
        if (!$validate_playertext['status']) {
            return $validate_playertext;
        }

        //Log Link new Player:
        $player_e = superpower_unlocked();
        $linkplayercreator = ($linkplayercreator > 0 ? $linkplayercreator : ($player_e ? $player_e['playerid'] : 14068));

        //Create New Player:
        $new_x = $this->Links->create(array(
            'linkplayercreator' => $linkplayercreator,
            'linkplayertype' => 4251, //New Player Created
            'linktext' => $validate_playertext['playertext_clean'],
        ));

        if (!$new_x['linkid']) {
            $this->Links->create(array(
                'linkplayertype' => 44179, //Triggered
                'linkplayerup' => 4246, //Platform Bug Reports
                'linkplayerdown' => $linkplayercreator,
                'linktext' => 'create() failed to create a new Player',
                'linkplayercreator' => $linkplayercreator,
            ));
            return array(
                'status' => 0,
                'message' => 'Error trying to create Player',
            );
        }

        //Handle Generation
        $new_handle = generate_handle(12274, $validate_playertext['playertext_clean']);
        $this->Links->create(array(
            'linkplayercreator' => $linkplayercreator,
            'linkplayertype' => 44176, //Viewed
            'linkplayerup' => 32338, //Player Handle
            'linktext' => $new_handle,
            'linkplayerdown' => $new_x['linkid'],
        ));

        //Cover saving if any
        if (strlen($playercover)) {
            $this->Links->create(array(
                'linkplayercreator' => $linkplayercreator,
                'linkplayertype' => 44176, //Viewed
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
        update_algolia(12274, $new_x['linkid']);

        //Fetch to return the complete Player data:
        $es = $this->Players->read(array(
            'playerid' => $new_x['linkid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'new_player' => $es[0],
        );

    }

    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array('playerid' => 'DESC'), $select = '*', $group_by = null)
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
                if (!player_access_level(null, $value['playerid'], $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }

        return $results;

    }


    function update($id, $update_columns, $linkplayercreator = 0)
    {
        if (count($update_columns) == 0 || !count($this->Links->read(array('linkid' => $id )))) {
            return false;
        }

        $must_sync_found = false;
        $skip_sync_ledger = array('playerexternal','playernumber');
        $must_sync_ledger = array(
            'playerhandle' => 32338,
            'playercover' => 6198,
            'playertext' => 6198, //TODO Update later with message
        );

        //See what is being updated:
        foreach($update_columns as $key => $value) {
            if(array_key_exists($key, $must_sync_ledger)){
                $this->Links->create(array(
                    'linkplayercreator' => $linkplayercreator,
                    'linkplayertype' => 44176, //Viewed
                    'linkplayerup' => $must_sync_ledger[$key], //Idea Hashtag
                    'linktext' => $value,
                    'linkplayerdown' => $id,
                ));
                $must_sync_found = true;
            } elseif(in_array($key, $skip_sync_ledger)){
                //Nothing we need to do here
            } else {
                //Remove this as its unknown:
                unset($update_columns[$key]);
            }
        }

        //Update:
        $this->db->where('playerid', intval($id));
        $this->db->update('nodeplayers', $update_columns);
        $affected_rows = $this->db->affected_rows();

        if($must_sync_found){
            //Sync algolia:
            update_algolia(12274, intval($id));
        }

        return $affected_rows;

    }


    function delete($playerid, $linkplayercreator = 0, $migrate_s__id = 0)
    {

        if (!count($this->Players->read(array( 'plyerid' => $playerid )))) {
            return array(
                'status' => 0,
                'message' => $playerid . ' is not a valid ID',
            );
        } elseif ($migrate_s__id > 0 && !count($this->Players->read(array( 'playerid' => $migrate_s__id )))) {
            return array(
                'status' => 0,
                'message' => $migrate_s__id . ' is not a valid ID',
            );
        }

        //Find all sources to migrate:
        $x_adjusted = 0;
        foreach ($this->Links->read(array(
            '(linkplayerup='.$playerid.' OR linkplayerdown='.$playerid.' OR linkplayercreator='.$playerid.' OR linkplayertype='.$playerid.')' => null,
        ), array(), 0) as $migrate) {

            if ($migrate_s__id) {
                
                $new_array = array(
                    'linkplayercreator' => ( $migrate['linkplayercreator']==$playerid ? $migrate_s__id : ( $linkplayercreator>0 ? $linkplayercreator : $migrate['linkplayercreator'] ) ),
                    'linkplayertype' => ( $migrate['linkplayertype']==$playerid ? $migrate_s__id : $migrate['linkplayertype'] ),
                    'linkplayerup' => ( $migrate['linkplayerup']==$playerid ? $migrate_s__id : $migrate['linkplayerup'] ),
                    'linkplayerdown' => ( $migrate['linkplayerdown']==$playerid ? $migrate_s__id : $migrate['linkplayerdown'] ),
                    'linkidealeft' => $migrate['linkidealeft'],
                    'linkidearight' => $migrate['linkidearight'],
                );

                //Update if this new one is unique:
                if (!count($this->Links->read($new_array))) {
                    $x_adjusted += $this->Links->update($migrate['linkid'],$new_array);
                    continue;
                }
            }

            //Just remove it:
            $x_adjusted += $this->Links->delete($migrate['linkid'], $linkplayercreator);

        }

        //Remove from Table:
        $this->db->query("DELETE FROM nodeplayers WHERE playerid = " . $playerid . ";");

        //Update Search Index?
        update_algolia(12277, $playerid);

        //Return Links deleted:
        return $x_adjusted;

    }



    function command($playerid, $action_playerid, $action_command1, $action_command2, $linkplayercreator)
    {

        //Alert: Has a twin function called i_command()

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
        $followers = $this->Links->read(array(
            'linkplayerup' => $playerid,
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array('linkplayerdown'), 0);


        //Process request:
        foreach ($followers as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_playerid == 4998) { //Add Prefix String

                $this->Players->update($x['playerid'], array(
                    'playertext' => $action_command1 . $x['playertext'],
                ), $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 4999) { //Add Postfix String

                $this->Players->update($x['playerid'], array(
                    'playertext' => $x['playertext'] . $action_command1,
                ), $linkplayercreator);

                $applied_success++;

            } elseif (in_array($action_playerid, array(5981, 5982, 11956, 13441)) && view_valid_handle_player($action_command1)) { //Add/Delete/Migrate followings Player

                //What member searched for:
                foreach ($this->Players->read(array(
                    'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command1)),
                )) as $e) {

                    //See if follower Player has searched followings Player:
                    $down_up_e = $this->Links->read(array(
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
                        $this->Links->create($add_fields);

                        $applied_success++;

                        if ($action_playerid == 13441) {
                            //Since we're migrating we should remove from here:
                            $this->Links->delete($x['linkid'], $linkplayercreator);
                        }

                    } elseif (in_array($action_playerid, array(5982, 11956)) && count($down_up_e) > 0) {

                        if ($action_playerid == 5982) {

                            //Following Member Removal
                            foreach ($down_up_e as $delete_tr) {
                                $this->Links->delete($delete_tr['linkid'], $linkplayercreator);
                                $applied_success++;
                            }

                        } elseif ($action_playerid == 11956 && view_valid_handle_player($action_command2)) {

                            foreach ($this->Players->read(array(
                                'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command2)),
                            )) as $e) {
                                //Add as a followings because it meets the condition
                                $this->Links->create(array(
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

                $this->Players->update($x['playerid'], array(
                    'playercover' => $action_command1,
                ), $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 12318 && !strlen($x['playercover'])) { //Member Mass Update Member Cover

                $this->Players->update($x['playerid'], array(
                    'playercover' => $action_command1,
                ), $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 5000 && substr_count(strtolower($x['playertext']), strtolower($action_command1)) > 0) { //Replace Member Matching Name

                $this->Players->update($x['playerid'], array(
                    'playertext' => str_ireplace($action_command1, $action_command2, $x['playertext']),
                ), $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 10625 && substr_count($x['playercover'], $action_command1) > 0) { //Replace Member Matching Cover

                $this->Players->update($x['playerid'], array(
                    'playercover' => str_replace($action_command1, $action_command2, $x['playercover']),
                ), $linkplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 5001 && substr_count($x['linktext'], $action_command1) > 0) { //Replace Link Matching String

                $new_message = str_replace($action_command1, $action_command2, $x['linktext']);

                $this->Links->update($x['linkid'], array(
                    'linktext' => $new_message,
                    'linkplayercreator' => $linkplayercreator,
                ));

                $applied_success++;

            } elseif ($action_playerid == 26093) { //Replace Link Matching String

                $this->Links->update($x['linkid'], array(
                    'linktext' => $action_command1,
                    'linkplayercreator' => $linkplayercreator,
                ));

                $applied_success++;

            } elseif ($action_playerid == 42804 && ($action_command1 == '*' || $x['linkplayertype'] == $action_command1) && in_array($action_command2, $this->list_player_links_intentional /* Player Link Types */)) { //Update Matching Interaction Type

                $this->Links->update($x['linkid'], array(
                    'linkplayertype' => $action_command2,
                    'linkplayercreator' => $linkplayercreator,
                ));
                $applied_success++;

            }
        }

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($followers) . ' Players updated',
        );

    }




    function activate($e, $update_session = false, $is_cookie = false)
    {

        //PROFILE
        $session_data = array(
            'session_up' => $e,
            'session_up_ids' => array(),
            'session_superpowers_unlocked' => array(),
        );

        $websiteplayerid = website_setting(0);

        //Make sure they also belong to this website's members:
        //Add if link not already there:
        if (!count($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
            'linkplayerup' => $websiteplayerid,
            'linkplayerdown' => $e['playerid'],
        )))) {
            $this->Links->create(array(
                'linkplayercreator' => $e['playerid'], //Belongs to this Member
                'linkplayertype' => 4230,
                'linkplayerup' => $websiteplayerid,
                'linkplayerdown' => $e['playerid'],
            ));
        }


        //Check & Adjust their subscription, IF needed:
        //Remove their subscribe:
        $resubscribed = 0;
        foreach ($this->Links->read(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___29648')) . ')' => null, //Unsubscribers
            'linkplayerdown' => $e['playerid'],
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        )) as $unsubscribe) {
            $resubscribed += $this->Links->delete($unsubscribe['linkid'], $e['playerid']);
        }
        if ($resubscribed > 0) {
            //Add Back to Subscribers:
            $this->Links->create(array(
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
        foreach ($this->Links->read(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___14926')) . ')' => null, //Website Theme Items
            'linkplayerdown' => 6404, //Platform Default
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array(), 0) as $x) {
            array_push($platform_theme, intval($x['linkplayerup']));
        }

        //Fetch Website Defaults:
        $website_theme = array();
        foreach ($this->Links->read(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___14926')) . ')' => null, //Website Theme Items
            'linkplayerdown' => website_setting(0), //Website ID
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array(), 0) as $x) {
            array_push($website_theme, intval($x['linkplayerup']));
        }


        //Fetch User Defaults:
        $user_theme = array();
        foreach ($this->Links->read(array(
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
        foreach($this->Links->read(array(
            'linkplayerup IN (' . join(',', $this->config->item('playerids___31057')) . ')' => null, //Permanently Unsubscribed
            'linkplayerdown' => $e['playerid'], //This follower Player
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                ), array(), 0) as $unsubscribed){
            $unsubscribed_time = $unsubscribed['linktime'];
            $this->Links->delete($unsubscribed['linkid'], $e['playerid']); //Resubscribe
        }
        if($unsubscribed_time){
            //Add to subscribed again:
            $this->Links->create(array(
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


    function scissor($linkplayerup, $sub_id)
    {

        $all_results = $this->Links->read(array(
            'linkplayerup' => $linkplayerup,
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
        ), array('linkplayerdown'), 0, 0, sort__player());

        //Remove if not in the secondary group:
        foreach ($all_results as $key => $primary_list) {
            if (!count($this->Links->read(array(
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

    function join($full_name, $email = null, $phone_number = null, $image_url = null, $linkplayerdomain = 0)
    {

        //Set website if not set:
        if (!$linkplayerdomain) {
            $linkplayerdomain = website_setting(0);
        }

        //All good, create new Player:
        $new_private_users = in_array($linkplayerdomain, $this->config->item('playerids___44011'));
        $added_e = $this->Players->create($full_name, 0, ($image_url ? $image_url : playercover_generator(12279)));
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
            $this->Links->create(array(
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
            $this->Links->create(array(
                'linkplayerup' => 4783, //Phone
                'linkplayertype' => 4230,
                'linktext' => $phone_number,
                'linkplayercreator' => $added_e['new_player']['playerid'],
                'linkplayerdown' => $added_e['new_player']['playerid'],
                'linkplayerdomain' => $linkplayerdomain,
            ));
        }

        if ($email || $phone_number) {

            //Remove from Anonymous:
            foreach ($this->Links->read(array(
                'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                'linkplayerup IN (' . join(',', $this->config->item('playerids___32540')) . ')' => null, //Unsubscribers
                'linkplayerdown' => $added_e['new_player']['playerid'],
            )) as $unsubscriber_x) {
                $this->Links->delete($unsubscriber_x['linkid'], $added_e['new_player']['playerid']);
            }

            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

            //Add to Subscriber:
            $this->Links->create(array(
                'linkplayerup' => 4430, //Subscriber
                'linkplayertype' => 4230,
                'linkplayercreator' => $added_e['new_player']['playerid'],
                'linkplayerdown' => $added_e['new_player']['playerid'],
                'linkplayerdomain' => $linkplayerdomain,
            ));

        } else {

            //Add to anonymous:
            $this->Links->create(array(
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

        //Add if link not already there:
        if (!count($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
            'linkplayerup' => $linkplayerdomain,
            'linkplayerdown' => $added_e['new_player']['playerid'],
        )))) {
            $this->Links->create(array(
                'linkplayercreator' => $added_e['new_player']['playerid'], //Belongs to this Member
                'linkplayertype' => 4230,
                'linkplayerup' => $linkplayerdomain,
                'linkplayerdown' => $added_e['new_player']['playerid'],
            ));
        }

        //Send Welcome Email if any:
        if ($email) {
            foreach ($this->Links->read(array(
                'linkplayertype' => 33600, //Draft
                'linkplayerup' => 14929, //Website Welcome Email Templates
            ), array('linkidearight'), 0) as $i) {
                if (count($this->Links->read(array(
                    'linkplayertype' => 33600, //Draft
                    'linkplayerup' => $linkplayerdomain, //for Current website
                    'linkidearight' => $i['ideaid'], //Is this the template?
                )))) {
                    //Found the email template to send:
                    $total_sent = $this->Links->broadcast(array($added_e['new_player']), $i, $linkplayerdomain);
                    break; //Just the first template match
                }
            }
        }

        //Update Search Index:
        update_algolia(12274, $added_e['new_player']['playerid']);

        //Assign session & log login Link:
        $this->Players->activate($added_e['new_player']);


        //Return Member:
        return array(
            'status' => 1,
            'e' => $added_e['new_player'],
        );

    }

    function tree($linkplayertype, $playerid, $include_any_e = array(), $exclude_all_e = array(), $hard_level = 3, $hard_limit = 100, $s__level = 0)
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


        foreach ($this->Links->read($query_filters, $joins_objects, 0, 0, $order_columns) as $player_down) {

            //Filter Players, if needed:
            $qualified_e = true;
            if (count($include_any_e) && !count($this->Links->read(array(
                    'linkplayerup IN (' . join(',', $include_any_e) . ')' => null,
                    'linkplayerdown' => $player_down['playerid'],
                    'linkplayertype IN (' . join(',', $this->list_player_links_intentional) . ')' => null, //SOURCE LINKS
                )))) {
                //Must include all Players, skip:
                $qualified_e = false;
            }
            if (count($exclude_all_e) && count($this->Links->read(array(
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

            foreach ($this->Players->tree($linkplayertype, $player_down['playerid'], $include_any_e, $exclude_all_e, $hard_level, $hard_limit, $s__level) as $player_recursive_down) {
                if (!isset($flat_items[$player_recursive_down['playerid']])) {
                    $player_recursive_down['s__count'] = count($flat_items) + 1;
                    $flat_items[$player_recursive_down['playerid']] = $player_recursive_down;
                }
            }
        }

        return $flat_items;
    }



}