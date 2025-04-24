<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Players extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainplayercreator = 0)
    {

        //Validate Title
        $validate_playertext = validate_playertext($add_fields['playertext']);
        if (!$validate_playertext['status']) {
            return $validate_playertext;
        }

        //Log Chain new Player:
        $player_session = player_session();
        $chainplayercreator = ($chainplayercreator > 0 ? $chainplayercreator : ($player_session ? $player_session['playerid'] : 14068));

        $creation_data = array(
            'chainplayercreator' => $chainplayercreator,
            'chainplayertype' => 4251, //New Player Created
            'chaintext' => $validate_playertext['playertext_clean'],
        );
        if (isset($add_fields['playerid']) && !count($this->Chains->read(array('chainid' => $add_fields['playerid'])))) {
            //Set the chain ID since its not in the ledger:
            $creation_data['chainid'] = $add_fields['playerid'];
        }
        $new_x = $this->Chains->create($creation_data);

        if (!$new_x['chainid']) {
            return log_error('create() failed to create a new Player', array(
                'chainplayerdown' => $chainplayercreator,
                'chainplayercreator' => $chainplayercreator,
            ));
        }

        //Handle Generation
        if (!isset($add_fields['playerhandle'])) {
            $add_fields['playerhandle'] = generate_handle(12274, $validate_playertext['playertext_clean']);
        }
        $this->Chains->create(array(
            'chainplayercreator' => $chainplayercreator,
            'chainplayertype' => 44179, //Trigerred
            'chainplayerup' => 32338, //Player Handle
            'chaintext' => $add_fields['playerhandle'],
            'chainplayerdown' => $new_x['chainid'],
        ));

        $update_data = array(
            'playerid' => $new_x['chainid'],
            'playerhandle' => $add_fields['playerhandle'],
            'playertext' => $validate_playertext['playertext_clean'],
        );

        //Cover saving if any
        if (isset($add_fields['playercover'])) {
            $this->Chains->create(array(
                'chainplayercreator' => $chainplayercreator,
                'chainplayertype' => 44179, //Trigerred
                'chainplayerup' => 6198, //Player Cover
                'chaintext' => $add_fields['playercover'],
                'chainplayerdown' => $new_x['chainid'],
            ));
            $update_data['playercover'] = $add_fields['playercover'];
        }

        //Add to cache:
        if (!count($this->Players->read(array('playerid' => $new_x['chainid'])))) {
            $this->db->insert('cacheplayers', $update_data);
        }


        //Update Search Index:
        update_algolia(12274, $new_x['chainid']);

        //Fetch to return the complete Player data:
        $es = $this->Players->read(array(
            'playerid' => $new_x['chainid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'player_create' => $es[0],
        );

    }

    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array('playerid' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target Players:
        $this->db->select($select);
        $this->db->from('cacheplayers');
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
                if (!player_access(null, $value['playerid'], $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }

        return $results;

    }


    function update($chainid, $update_columns, $chainplayercreator = 0)
    {

        if (!count($update_columns)) {
            return false;
        }

        $players_found = $this->Players->read(array('playerid' => $chainid));
        if (!count($players_found)) {
            log_error('Player @' . $chainid . ' not found in Players table');
            return false;
        } elseif (!count($this->Chains->read(array('chainid' => $chainid)))) {
            log_error('Player @' . $chainid . ' not found in Chains table');
            return false;
        }

        $affected_rows = 0;
        foreach ($players_found as $player_current) {

            $must_sync_found = false;
            $skip_sync_ledger = array('playerexternal', 'playernumber');
            $must_sync_ledger = array(
                'playerhandle' => 32338,
                'playercover' => 6198,
                'playertext' => 6197,
            );

            //See what is being updated:
            foreach ($update_columns as $key => $value) {
                if (array_key_exists($key, $must_sync_ledger)) {
                    //Update if anything changed:
                    if ($value != $player_current[$key]) {
                        $this->Chains->create(array(
                            'chainplayercreator' => $chainplayercreator,
                            'chainplayertype' => 44179, //Trigerred
                            'chainplayerup' => $must_sync_ledger[$key], //Idea Hashtag
                            'chaintext' => $value,
                            'chainplayerdown' => $chainid,
                        ));
                        $must_sync_found = true;
                    } else {
                        //Nothing changed:
                        unset($update_columns[$key]);
                    }
                } elseif (in_array($key, $skip_sync_ledger)) {
                    //Nothing we need to do here
                } else {
                    //Unknown not allowed:
                    unset($update_columns[$key]);
                }
            }

            if (!count($update_columns)) {
                return false;
            }

            //Update:
            $this->db->where('playerid', $chainid);
            $this->db->update('cacheplayers', $update_columns);
            $affected_rows = $this->db->affected_rows();

            if ($must_sync_found) {
                //Sync algolia:
                update_algolia(12274, intval($chainid));
            }

        }

        return $affected_rows;

    }


    function delete($playerid, $chainplayercreator = 0, $migrateid = 0)
    {

        if (in_array($playerid, $this->config->item('playerids___4593'))) {
            return array(
                'status' => 0,
                'message' => 'Cannot Delete an active @chainplayertype - Unchain, update @memory and try again',
            );
        } elseif (in_array($playerid, $this->config->item('playerids___14870'))) {
            return array(
                'status' => 0,
                'message' => 'Cannot Delete an active @chainplayerdomain - Unchain, update @memory and try again',
            );
        } elseif (!count($this->Players->read(array('playerid' => $playerid)))) {
            return array(
                'status' => 0,
                'message' => $playerid . ' is not a valid ID',
            );
        } elseif ($migrateid > 0 && !count($this->Players->read(array('playerid' => $migrateid)))) {
            return array(
                'status' => 0,
                'message' => $migrateid . ' is not a valid ID',
            );
        }

        //Find all chains to delete/migrate:
        $x_adjusted = 0;
        foreach ($this->Chains->read(array(
            '(chainid=' . $playerid . ' OR chainplayerup=' . $playerid . ' OR chainplayerdown=' . $playerid . ' OR chainplayercreator=' . $playerid . ' OR chainplayertype=' . $playerid . ' OR chainplayerdomain=' . $playerid . ')' => null,
        ), array(), 0) as $migrate) {

            if ($migrateid) {

                $new_array = array(
                    'chainplayercreator' => ($migrate['chainplayercreator'] == $playerid ? $migrateid : ($chainplayercreator > 0 ? $chainplayercreator : $migrate['chainplayercreator'])),
                    'chainplayertype' => ($migrate['chainplayertype'] == $playerid ? $migrateid : $migrate['chainplayertype']),
                    'chainplayerdomain' => ($migrate['chainplayerdomain'] == $playerid ? $migrateid : $migrate['chainplayerdomain']),
                    'chainplayerup' => ($migrate['chainplayerup'] == $playerid ? $migrateid : $migrate['chainplayerup']),
                    'chainplayerdown' => ($migrate['chainplayerdown'] == $playerid ? $migrateid : $migrate['chainplayerdown']),
                    'chainidealeft' => $migrate['chainidealeft'],
                    'chainidearight' => $migrate['chainidearight'],
                );

                //Update if this new one is unique:
                if (!count($this->Chains->read($new_array))) {
                    $x_adjusted += $this->Chains->update($migrate['chainid'], $new_array);
                    continue;
                }
            }

            //Just remove it:
            $x_adjusted += $this->Chains->delete($migrate['chainid'], $chainplayercreator);

        }

        if ($x_adjusted) {
            //Remove from Table:
            $this->db->query("DELETE FROM cacheplayers WHERE playerid = " . $playerid . ";");

            //Update Search Index?
            update_algolia(12277, $playerid);
        } else {
            //Failed to remove
            log_error('players->delete() Failed to remove @' . $playerid . ' Chain ID', array(
                'chainplayercreator' => $chainplayercreator,
                'chainideaup' => $playerid,
                'chainideadown' => $migrateid,
            ));
        }


        //Return Chains deleted:
        return $x_adjusted;

    }


    function command($playerid, $action_playerid, $action_command1, $action_command2, $chainplayercreator)
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
        $followers = $this->Chains->read(array(
            'chainplayerup' => $playerid,
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainplayerdown'), 0);


        //Process request:
        foreach ($followers as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_playerid == 4998) { //Add Prefix String

                $this->Players->update($x['playerid'], array(
                    'playertext' => $action_command1 . $x['playertext'],
                ), $chainplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 4999) { //Add Postfix String

                $this->Players->update($x['playerid'], array(
                    'playertext' => $x['playertext'] . $action_command1,
                ), $chainplayercreator);

                $applied_success++;

            } elseif (in_array($action_playerid, array(5981, 5982, 11956, 13441)) && view_valid_handle_player($action_command1)) { //Add/Delete/Migrate followings Player

                //What member searched for:
                foreach ($this->Players->read(array(
                    'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command1)),
                )) as $e) {

                    //See if follower Player has searched followings Player:
                    $down_up_e = $this->Chains->read(array(
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                        'chainplayerdown' => $x['playerid'], //This follower Player
                        'chainplayerup' => $e['playerid'],
                    ));

                    if ((in_array($action_playerid, array(5981, 13441)) && count($down_up_e) == 0)) {

                        $add_fields = array(
                            'chainplayercreator' => $chainplayercreator,
                            'chainplayertype' => 4230,
                            'chainplayerdown' => $x['playerid'], //This follower Player
                            'chainplayerup' => $e['playerid'],
                        );

                        if ($action_playerid == 13441) {
                            //Copy message only if moving:
                            $add_fields['chaintext'] = $x['chaintext'];
                        }

                        //Following Member Addition
                        $this->Chains->create($add_fields);

                        $applied_success++;

                        if ($action_playerid == 13441) {
                            //Since we're migrating we should remove from here:
                            $this->Chains->delete($x['chainid'], $chainplayercreator);
                        }

                    } elseif (in_array($action_playerid, array(5982, 11956)) && count($down_up_e) > 0) {

                        if ($action_playerid == 5982) {

                            //Following Member Removal
                            foreach ($down_up_e as $delete_tr) {
                                $this->Chains->delete($delete_tr['chainid'], $chainplayercreator);
                                $applied_success++;
                            }

                        } elseif ($action_playerid == 11956 && view_valid_handle_player($action_command2)) {

                            foreach ($this->Players->read(array(
                                'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command2)),
                            )) as $e) {
                                //Add as a followings because it meets the condition
                                $this->Chains->create(array(
                                    'chainplayercreator' => $chainplayercreator,
                                    'chainplayertype' => 4230,
                                    'chainplayerdown' => $x['playerid'], //This follower Player
                                    'chainplayerup' => $e['playerid'],
                                ));
                                $applied_success++;
                            }
                        }
                    }
                }

            } elseif ($action_playerid == 5943) { //Member Mass Update Member Cover

                $this->Players->update($x['playerid'], array(
                    'playercover' => $action_command1,
                ), $chainplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 12318 && !strlen($x['playercover'])) { //Member Mass Update Member Cover

                $this->Players->update($x['playerid'], array(
                    'playercover' => $action_command1,
                ), $chainplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 5000 && substr_count(strtolower($x['playertext']), strtolower($action_command1)) > 0) { //Replace Member Matching Name

                $this->Players->update($x['playerid'], array(
                    'playertext' => str_ireplace($action_command1, $action_command2, $x['playertext']),
                ), $chainplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 10625 && substr_count($x['playercover'], $action_command1) > 0) { //Replace Member Matching Cover

                $this->Players->update($x['playerid'], array(
                    'playercover' => str_replace($action_command1, $action_command2, $x['playercover']),
                ), $chainplayercreator);

                $applied_success++;

            } elseif ($action_playerid == 5001 && substr_count($x['chaintext'], $action_command1) > 0) { //Replace Chain Matching String

                $new_message = str_replace($action_command1, $action_command2, $x['chaintext']);

                $this->Chains->update($x['chainid'], array(
                    'chaintext' => $new_message,
                    'chainplayercreator' => $chainplayercreator,
                ));

                $applied_success++;

            } elseif ($action_playerid == 26093) { //Replace Chain Matching String

                $this->Chains->update($x['chainid'], array(
                    'chaintext' => $action_command1,
                    'chainplayercreator' => $chainplayercreator,
                ));

                $applied_success++;

            } elseif ($action_playerid == 42804 && ($action_command1 == '*' || $x['chainplayertype'] == $action_command1) && in_array($action_command2, $this->config->item('playerids___13548') /* Player Chain Types */)) { //Update Matching Interaction Type

                $this->Chains->update($x['chainid'], array(
                    'chainplayertype' => $action_command2,
                    'chainplayercreator' => $chainplayercreator,
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
        //Add if chain not already there:
        if (!count($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
            'chainplayerup' => $websiteplayerid,
            'chainplayerdown' => $e['playerid'],
        )))) {
            $this->Chains->create(array(
                'chainplayercreator' => $e['playerid'], //Belongs to this Member
                'chainplayertype' => 4230,
                'chainplayerup' => $websiteplayerid,
                'chainplayerdown' => $e['playerid'],
            ));
        }


        //Check & Adjust their subscription, IF needed:
        //Remove their subscribe:
        $resubscribed = 0;
        foreach ($this->Chains->read(array(
            'chainplayerup IN (' . join(',', $this->config->item('playerids___29648')) . ')' => null, //Unsubscribers
            'chainplayerdown' => $e['playerid'],
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
        )) as $unsubscribe) {
            $resubscribed += $this->Chains->delete($unsubscribe['chainid'], $e['playerid']);
        }
        if ($resubscribed > 0) {
            //Add Back to Subscribers:
            $this->Chains->create(array(
                'chainplayertype' => 4230,
                'chainplayerup' => 4430, //Active Member
                'chainplayercreator' => $e['playerid'],
                'chainplayerdown' => $e['playerid'],
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
        foreach ($this->Chains->read(array(
            'chainplayerup IN (' . join(',', $this->config->item('playerids___14926')) . ')' => null, //Website Theme Items
            'chainplayerdown' => 6404, //Platform Default
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
        ), array(), 0) as $x) {
            array_push($platform_theme, intval($x['chainplayerup']));
        }

        //Fetch Website Defaults:
        $website_theme = array();
        foreach ($this->Chains->read(array(
            'chainplayerup IN (' . join(',', $this->config->item('playerids___14926')) . ')' => null, //Website Theme Items
            'chainplayerdown' => website_setting(0), //Website ID
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
        ), array(), 0) as $x) {
            array_push($website_theme, intval($x['chainplayerup']));
        }


        //Fetch User Defaults:
        $user_theme = array();
        foreach ($this->Chains->read(array(
            'chainplayerdown' => $e['playerid'], //This follower Player
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainplayerup'), 0) as $player_up) {

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


        //TODO Resubscribe IF they are Permanently Unsubscribed:
        /*
        $unsubscribed_time = null;
        foreach($this->Chains->read(array(
            'chainplayerup IN (' . join(',', $this->config->item('playerids___31057')) . ')' => null, //Permanently Unsubscribed
            'chainplayerdown' => $e['playerid'], //This follower Player
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                ), array(), 0) as $unsubscribed){
            $unsubscribed_time = $unsubscribed['chaintime'];
            $this->Chains->delete($unsubscribed['chainid'], $e['playerid']); //Resubscribe
        }
        if($unsubscribed_time){
            //Add to subscribed again:
            $this->Chains->create(array(
                'chainplayertype' => 4230,
                'chainplayerup' => 4430, //Active Member
                'chainplayercreator' => $e['playerid'],
                'chainplayerdown' => $e['playerid'],
            ));
            $this->session->set_flashdata('flash_message', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-user-check"></i></span>Welcome Back! You Have Been Re-Subscribed :)</div>');
        }
        */

        return $session_data;

    }


    function scissor($chainplayerup, $sub_id)
    {

        $all_results = $this->Chains->read(array(
            'chainplayerup' => $chainplayerup,
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainplayerdown'), 0, 0, player_sort());

        //Remove if not in the secondary group:
        foreach ($all_results as $key => $primary_list) {
            if (!count($this->Chains->read(array(
                'chainplayerup' => $sub_id,
                'chainplayerdown' => $primary_list['playerid'],
                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
            ), array(), 0))) {
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }

    function join($full_name, $email = null, $phone_number = null, $image_url = null, $chainplayerdomain = 0)
    {

        //Set website if not set:
        if (!$chainplayerdomain) {
            $chainplayerdomain = website_setting(0);
        }

        //All good, create new Player:
        $new_private_users = in_array($chainplayerdomain, $this->config->item('playerids___44011'));
        $added_e = $this->Players->create(array(
            'playertext' => $full_name,
            'playercover' => ($image_url ? $image_url : playercover_generator(12279)),
        ));
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
            $this->Chains->create(array(
                'chainplayertype' => 4230,
                'chaintext' => trim(strtolower($email)),
                'chainplayerup' => 3288, //Email
                'chainplayercreator' => $added_e['player_create']['playerid'],
                'chainplayerdown' => $added_e['player_create']['playerid'],
                'chainplayerdomain' => $chainplayerdomain,
            ));
        }

        //Add Number?
        if ($phone_number) {
            $this->Chains->create(array(
                'chainplayerup' => 4783, //Phone
                'chainplayertype' => 4230,
                'chaintext' => $phone_number,
                'chainplayercreator' => $added_e['player_create']['playerid'],
                'chainplayerdown' => $added_e['player_create']['playerid'],
                'chainplayerdomain' => $chainplayerdomain,
            ));
        }

        if ($email || $phone_number) {

            //Remove from Anonymous:
            foreach ($this->Chains->read(array(
                'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                'chainplayerup IN (' . join(',', $this->config->item('playerids___32540')) . ')' => null, //Unsubscribers
                'chainplayerdown' => $added_e['player_create']['playerid'],
            )) as $unsubscriber_x) {
                $this->Chains->delete($unsubscriber_x['chainid'], $added_e['player_create']['playerid']);
            }

            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

            //Add to Subscriber:
            $this->Chains->create(array(
                'chainplayerup' => 4430, //Subscriber
                'chainplayertype' => 4230,
                'chainplayercreator' => $added_e['player_create']['playerid'],
                'chainplayerdown' => $added_e['player_create']['playerid'],
                'chainplayerdomain' => $chainplayerdomain,
            ));

        } else {

            //Add to anonymous:
            $this->Chains->create(array(
                'chainplayerup' => 14938, //Guest Login
                'chainplayertype' => 4230,
                'chainplayercreator' => $added_e['player_create']['playerid'],
                'chainplayerdown' => $added_e['player_create']['playerid'],
                'chainplayerdomain' => $chainplayerdomain,
            ));

            //Assign session key:
            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

        }

        //Add if chain not already there:
        if (!count($this->Chains->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
            'chainplayerup' => $chainplayerdomain,
            'chainplayerdown' => $added_e['player_create']['playerid'],
        )))) {
            $this->Chains->create(array(
                'chainplayercreator' => $added_e['player_create']['playerid'], //Belongs to this Member
                'chainplayertype' => 4230,
                'chainplayerup' => $chainplayerdomain,
                'chainplayerdown' => $added_e['player_create']['playerid'],
            ));
        }

        //Send Welcome Email if any:
        if ($email) {
            foreach ($this->Chains->read(array(
                'chainplayertype' => 33600, //Draft
                'chainplayerup' => 14929, //Website Welcome Email Templates
            ), array('chainidearight'), 0) as $i) {
                if (count($this->Chains->read(array(
                    'chainplayertype' => 33600, //Draft
                    'chainplayerup' => $chainplayerdomain, //for Current website
                    'chainidearight' => $i['ideaid'], //Is this the template?
                )))) {
                    //Found the email template to send:
                    $total_sent = $this->Chains->broadcast(array($added_e['player_create']), $i, $chainplayerdomain);
                    break; //Just the first template match
                }
            }
        }

        //Update Search Index:
        update_algolia(12274, $added_e['player_create']['playerid']);

        //Assign session & log login Chain:
        $this->Players->activate($added_e['player_create']);


        //Return Member:
        return array(
            'status' => 1,
            'e' => $added_e['player_create'],
        );

    }

    function tree($chainplayertype, $playerid, $include_any_e = array(), $exclude_all_e = array(), $hard_level = 3, $hard_limit = 100, $s__level = 0)
    {

        $flat_items = array();
        $s__level++;

        if (in_array($chainplayertype, $this->config->item('playerids___42276'))) {

            //Up Player Chain Groups:
            $order_columns = player_sort();
            $joins_objects = array('chainplayerup');
            $query_filters = array(
                'chainplayerdown' => $playerid,
                'chainplayertype IN (' . join(',', $this->config->item('playerids___' . $chainplayertype)) . ')' => null, //SOURCE CHAINS
            );

        } elseif (in_array($chainplayertype, $this->config->item('playerids___42377'))) {

            //Down Player Chain Groups:
            $order_columns = player_sort();
            $joins_objects = array('chainplayerdown');
            $query_filters = array(
                'chainplayerup' => $playerid,
                'chainplayertype IN (' . join(',', $this->config->item('playerids___' . $chainplayertype)) . ')' => null, //SOURCE CHAINS
            );

        } else {

            return false;

        }


        foreach ($this->Chains->read($query_filters, $joins_objects, 0, 0, $order_columns) as $player_down) {

            //Filter Players, if needed:
            $qualified_e = true;
            if (count($include_any_e) && !count($this->Chains->read(array(
                    'chainplayerup IN (' . join(',', $include_any_e) . ')' => null,
                    'chainplayerdown' => $player_down['playerid'],
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
                )))) {
                //Must include all Players, skip:
                $qualified_e = false;
            }
            if (count($exclude_all_e) && count($this->Chains->read(array(
                    'chainplayerup IN (' . join(',', $exclude_all_e) . ')' => null,
                    'chainplayerdown' => $player_down['playerid'],
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___13548')) . ')' => null, //SOURCE CHAINS
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

            foreach ($this->Players->tree($chainplayertype, $player_down['playerid'], $include_any_e, $exclude_all_e, $hard_level, $hard_limit, $s__level) as $player_recursive_down) {
                if (!isset($flat_items[$player_recursive_down['playerid']])) {
                    $player_recursive_down['s__count'] = count($flat_items) + 1;
                    $flat_items[$player_recursive_down['playerid']] = $player_recursive_down;
                }
            }
        }

        return $flat_items;
    }


}