<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Handles extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainhandlecreator = 0)
    {

        //Validate Title
        $validate_handlename = validate_handlename($add_fields['handlename']);
        if (!$validate_handlename['status']) {
            return $validate_handlename;
        }

        //Log Chain new Handle:
        $handle_session = handle_session();
        $chainhandlecreator = ($chainhandlecreator > 0 ? $chainhandlecreator : ($handle_session ? $handle_session['handleid'] : 14068));

        $nextchainid = nextchainid();
        $creation_data = array(
            'chainhandlecreator' => $chainhandlecreator,
            'chainhandleinput' => $chainhandlecreator,
            'chainhandleoutput' => $nextchainid,
            'chainhandletype' => 12274, //New Handle Created
            'chainvalue' => $validate_handlename['handlename_clean'],
        );

        if (isset($add_fields['handleid']) && !count($this->Chains->read(array('chainid' => $add_fields['handleid'])))) {
            //Set the chain ID since its not in the ledger:
            $creation_data['chainid'] = $add_fields['handleid'];
        }
        $new_x = $this->Chains->create($creation_data);

        if (!$new_x['chainid']) {
            return log_error('create() failed to create a new Handle', array(
                'chainhandleoutput' => $chainhandlecreator,
                'chainhandlecreator' => $chainhandlecreator,
            ));
        } elseif($nextchainid!=$new_x['chainid']) {
            //Something went wrong, update:
            $this->Chains->update($new_x['chainid'], array(
                'chainhandleoutput' => $new_x['chainid'],
            ));
        }

        //Handle Generation
        if (!isset($add_fields['handleterm'])) {
            $add_fields['handleterm'] = generate_handle(12274, $validate_handlename['handlename_clean']);
        }
        $this->Chains->create(array(
            'chainhandlecreator' => $chainhandlecreator,
            'chainhandletype' => 44176, //Trigerred
            'chainhandleinput' => 32338, //Handle Handle
            'chainvalue' => $add_fields['handleterm'],
            'chainhandleoutput' => $new_x['chainid'],
        ));

        $update_data = array(
            'handleid' => $new_x['chainid'],
            'handleterm' => $add_fields['handleterm'],
            'handlename' => $validate_handlename['handlename_clean'],
        );

        //Cover saving if any
        if (isset($add_fields['handlecover'])) {
            $this->Chains->create(array(
                'chainhandlecreator' => $chainhandlecreator,
                'chainhandletype' => 44176, //Trigerred
                'chainhandleinput' => 6198, //Handle Cover
                'chainvalue' => $add_fields['handlecover'],
                'chainhandleoutput' => $new_x['chainid'],
            ));
            $update_data['handlecover'] = $add_fields['handlecover'];
        }

        //Add to cache:
        if (!count($this->Handles->read(array('handleid' => $new_x['chainid'])))) {
            $this->db->insert('ideachainhandles', $update_data);
        }


        //Update Search Index:
        update_algolia(12274, $new_x['chainid']);

        //Fetch to return the complete Handle data:
        $es = $this->Handles->read(array(
            'handleid' => $new_x['chainid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'handle_create' => $es[0],
        );

    }

    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array('handleid' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target Handles:
        $this->db->select($select);
        $this->db->from('ideachainhandles');
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
                if (!handle_access(null, $value['handleid'], $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }

        return $results;

    }


    function update($chainid, $update_columns, $chainhandlecreator = 0)
    {

        if (!count($update_columns)) {
            return false;
        }

        $handles_found = $this->Handles->read(array('handleid' => $chainid));
        if (!count($handles_found)) {
            log_error('Handle @' . $chainid . ' not found in Handles table');
            return false;
        } elseif (!count($this->Chains->read(array(
            'chainid' => $chainid,
            'chainvoid >=' => 0, //Any void
        )))) {
            log_error('Handle @' . $chainid . ' not found in Chains table');
            return false;
        }

        $affected_rows = 0;
        foreach ($handles_found as $handle_current) {

            $must_sync_found = false;
            $skip_sync_ledger = array('handleexternal', 'handleweight');
            $must_sync_ledger = array(
                'handleterm' => 32338,
                'handlecover' => 6198,
                'handlename' => 6197,
            );

            //See what is being updated:
            foreach ($update_columns as $key => $value) {
                if (array_key_exists($key, $must_sync_ledger)) {
                    //Update if anything changed:
                    if ($value != $handle_current[$key]) {
                        $this->Chains->create(array(
                            'chainhandlecreator' => $chainhandlecreator,
                            'chainhandletype' => 44176, //Trigerred
                            'chainhandleinput' => $must_sync_ledger[$key], //Hashtag Hashtag
                            'chainvalue' => $value,
                            'chainhandleoutput' => $chainid,
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
            $this->db->where('handleid', $chainid);
            $this->db->update('ideachainhandles', $update_columns);
            $affected_rows = $this->db->affected_rows();

            if ($must_sync_found) {
                //Sync algolia:
                update_algolia(12274, intval($chainid));
            }

        }

        return $affected_rows;

    }


    function delete($handleid, $chainhandlecreator = 0, $migrateid = 0)
    {

        //Find all chains to delete/migrate:
        $x_adjusted = 0;
        foreach ($this->Chains->read(array(
            '(chainid=' . $handleid . ' OR chainhandleinput=' . $handleid . ' OR chainhandleoutput=' . $handleid . ' OR chainhandlecreator=' . $handleid . ' OR chainhandletype=' . $handleid . ' OR chainhandledomain=' . $handleid . ')' => null,
        ), array(), 0) as $migrate) {

            if ($migrateid) {

                $new_array = array(
                    'chainhandlecreator' => ($migrate['chainhandlecreator'] == $handleid ? $migrateid : ($chainhandlecreator > 0 ? $chainhandlecreator : $migrate['chainhandlecreator'])),
                    'chainhandletype' => ($migrate['chainhandletype'] == $handleid ? $migrateid : $migrate['chainhandletype']),
                    'chainhandledomain' => ($migrate['chainhandledomain'] == $handleid ? $migrateid : $migrate['chainhandledomain']),
                    'chainhandleinput' => ($migrate['chainhandleinput'] == $handleid ? $migrateid : $migrate['chainhandleinput']),
                    'chainhandleoutput' => ($migrate['chainhandleoutput'] == $handleid ? $migrateid : $migrate['chainhandleoutput']),
                    'chainhashtaginput' => $migrate['chainhashtaginput'],
                    'chainhashtagoutput' => $migrate['chainhashtagoutput'],
                );

                //Update if this new one is unique:
                if (!count($this->Chains->read($new_array))) {
                    $x_adjusted += $this->Chains->update($migrate['chainid'], $new_array);
                    continue;
                }
            }

            //Just remove it:
            $x_adjusted += $this->Chains->delete($migrate['chainid'], $chainhandlecreator);

        }

        if ($x_adjusted) {
            //Remove from Table:
            $this->db->query("DELETE FROM ideachainhandles WHERE handleid = " . $handleid . ";");

            //Update Search Index?
            update_algolia(12277, $handleid);
        } else {
            //Failed to remove
            log_error('handles->delete() Failed to remove @' . $handleid . ' Chain ID', array(
                'chainhandlecreator' => $chainhandlecreator,
                'chainhashtagup' => $handleid,
                'chainhashtagdown' => $migrateid,
            ));
        }


        //Return Chains deleted:
        return $x_adjusted;

    }


    function command($handleid, $action_handleid, $action_command1, $action_command2, $chainhandlecreator)
    {

        //Alert: Has a twin function called i_command()

        boost_power();

        $action_command1 = trim($action_command1);
        $action_command2 = trim($action_command2);


        if (!in_array($action_handleid, $this->config->item('handleids___4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif (in_array($action_handleid, array(5981, 5982, 11956, 13441)) && !view_valid_handle_handle($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Handle. Format must be: @HandleHandle',
            );

        } elseif (in_array($action_handleid, array(11956)) && !view_valid_handle_handle($action_command2)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Handle. Format must be: @HandleHandle',
            );

        }


        //Basic input validation done, let's continue
        $applied_success = 0; //To be populated

        //Fetch all followers:
        $followers = $this->Chains->read(array(
            'chainhandleinput' => $handleid,
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array('chainhandleoutput'), 0);


        //Process request:
        foreach ($followers as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_handleid == 4998) { //Add Prefix String

                $this->Handles->update($x['handleid'], array(
                    'handlename' => $action_command1 . $x['handlename'],
                ), $chainhandlecreator);

                $applied_success++;

            } elseif ($action_handleid == 4999) { //Add Postfix String

                $this->Handles->update($x['handleid'], array(
                    'handlename' => $x['handlename'] . $action_command1,
                ), $chainhandlecreator);

                $applied_success++;

            } elseif (in_array($action_handleid, array(5981, 5982, 11956, 13441)) && view_valid_handle_handle($action_command1)) { //Add/Delete/Migrate followings Handle

                //What member searched for:
                foreach ($this->Handles->read(array(
                    'LOWER(handleterm)' => strtolower(view_valid_handle_handle($action_command1)),
                )) as $e) {

                    //See if follower Handle has searched followings Handle:
                    $down_up_e = $this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                        'chainhandleoutput' => $x['handleid'], //This follower Handle
                        'chainhandleinput' => $e['handleid'],
                    ));

                    if ((in_array($action_handleid, array(5981, 13441)) && count($down_up_e) == 0)) {

                        $add_fields = array(
                            'chainhandlecreator' => $chainhandlecreator,
                            'chainhandletype' => 4230,
                            'chainhandleoutput' => $x['handleid'], //This follower Handle
                            'chainhandleinput' => $e['handleid'],
                        );

                        if ($action_handleid == 13441) {
                            //Copy message only if moving:
                            $add_fields['chainvalue'] = $x['chainvalue'];
                        }

                        //Following Member Addition
                        $this->Chains->create($add_fields);

                        $applied_success++;

                        if ($action_handleid == 13441) {
                            //Since we're migrating we should remove from here:
                            $this->Chains->delete($x['chainid'], $chainhandlecreator);
                        }

                    } elseif (in_array($action_handleid, array(5982, 11956)) && count($down_up_e) > 0) {

                        if ($action_handleid == 5982) {

                            //Following Member Removal
                            foreach ($down_up_e as $delete_tr) {
                                $this->Chains->delete($delete_tr['chainid'], $chainhandlecreator);
                                $applied_success++;
                            }

                        } elseif ($action_handleid == 11956 && view_valid_handle_handle($action_command2)) {

                            foreach ($this->Handles->read(array(
                                'LOWER(handleterm)' => strtolower(view_valid_handle_handle($action_command2)),
                            )) as $e) {
                                //Add as a followings because it meets the condition
                                $this->Chains->create(array(
                                    'chainhandlecreator' => $chainhandlecreator,
                                    'chainhandletype' => 4230,
                                    'chainhandleoutput' => $x['handleid'], //This follower Handle
                                    'chainhandleinput' => $e['handleid'],
                                ));
                                $applied_success++;
                            }
                        }
                    }
                }

            } elseif ($action_handleid == 5943) { //Member Mass Update Member Cover

                $this->Handles->update($x['handleid'], array(
                    'handlecover' => $action_command1,
                ), $chainhandlecreator);

                $applied_success++;

            } elseif ($action_handleid == 12318 && !strlen($x['handlecover'])) { //Member Mass Update Member Cover

                $this->Handles->update($x['handleid'], array(
                    'handlecover' => $action_command1,
                ), $chainhandlecreator);

                $applied_success++;

            } elseif ($action_handleid == 5000 && substr_count(strtolower($x['handlename']), strtolower($action_command1)) > 0) { //Replace Member Matching Name

                $this->Handles->update($x['handleid'], array(
                    'handlename' => str_ireplace($action_command1, $action_command2, $x['handlename']),
                ), $chainhandlecreator);

                $applied_success++;

            } elseif ($action_handleid == 10625 && substr_count($x['handlecover'], $action_command1) > 0) { //Replace Member Matching Cover

                $this->Handles->update($x['handleid'], array(
                    'handlecover' => str_replace($action_command1, $action_command2, $x['handlecover']),
                ), $chainhandlecreator);

                $applied_success++;

            } elseif ($action_handleid == 5001 && substr_count($x['chainvalue'], $action_command1) > 0) { //Replace Chain Matching String

                $new_message = str_replace($action_command1, $action_command2, $x['chainvalue']);

                $this->Chains->update($x['chainid'], array(
                    'chainvalue' => $new_message,
                    'chainhandlecreator' => $chainhandlecreator,
                ));

                $applied_success++;

            } elseif ($action_handleid == 26093) { //Replace Chain Matching String

                $this->Chains->update($x['chainid'], array(
                    'chainvalue' => $action_command1,
                    'chainhandlecreator' => $chainhandlecreator,
                ));

                $applied_success++;

            } elseif ($action_handleid == 42804 && ($action_command1 == '*' || $x['chainhandletype'] == $action_command1) && in_array($action_command2, $this->config->item('handleids___13548') /* Handle Chain Types */)) { //Update Matching Interaction Type

                $this->Chains->update($x['chainid'], array(
                    'chainhandletype' => $action_command2,
                    'chainhandlecreator' => $chainhandlecreator,
                ));
                $applied_success++;

            }
        }

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($followers) . ' Handles updated',
        );

    }


    function activate($e, $update_session = false, $is_cookie = false)
    {

        //PROFILE
        $session_data = array(
            'session_handle' => $e,
            'session_superpowers_unlocked' => array(),
        );

        $websitehandleid = website_setting(0);

        //Make sure they also belong to this website's members:
        if ($websitehandleid!=4341 && !count($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            'chainhandleinput' => $websitehandleid,
            'chainhandleoutput' => $e['handleid'],
        )))) {
            $this->Chains->create(array(
                'chainhandlecreator' => $e['handleid'],
                'chainhandletype' => 4230,
                'chainhandleinput' => $websitehandleid,
                'chainhandleoutput' => $e['handleid'],
            ));
        }


        //Check & Adjust their subscription, IF needed:
        $resubscribed = 0;
        foreach ($this->Chains->read(array(
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___29648')) . ')' => null, //Unsubscribers
            'chainhandleoutput' => $e['handleid'],
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        )) as $unsubscribe) {
            $resubscribed += $this->Chains->delete($unsubscribe['chainid'], $e['handleid']);
        }
        if ($resubscribed > 0) {
            //Add Back to Subscribers:
            $this->Chains->create(array(
                'chainhandletype' => 4230,
                'chainhandleinput' => 4430, //Active Member
                'chainhandlecreator' => $e['handleid'],
                'chainhandleoutput' => $e['handleid'],
            ));
        }


        if (!$update_session && !$is_cookie) {
            //Create Cookie:
            $cookie_time = time();
            $cookie_val = $e['handleid'] . 'ABCEFG' . $cookie_time . 'ABCEFG' . view_hash($e['handleid'] . $cookie_time);
            setcookie('auth_cookie', $cookie_val, ($cookie_time + (86400 * view_memory(6404, 14031))), "/");
        }


        //Fetch Platform Defaults:
        $platform_theme = array();
        foreach ($this->Chains->read(array(
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___14926')) . ')' => null, //Website Theme Items
            'chainhandleoutput' => 6404, //Platform Default
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array(), 0) as $x) {
            array_push($platform_theme, intval($x['chainhandleinput']));
        }

        //Fetch Website Defaults:
        $website_theme = array();
        foreach ($this->Chains->read(array(
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___14926')) . ')' => null, //Website Theme Items
            'chainhandleoutput' => website_setting(0), //Website ID
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array(), 0) as $x) {
            array_push($website_theme, intval($x['chainhandleinput']));
        }


        //Fetch User Defaults:
        $user_theme = array();
        foreach ($this->Chains->read(array(
            'chainhandleoutput' => $e['handleid'], //This follower Handle
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array('chainhandleinput'), 0) as $handle_up) {

            //Website Theme Items?
            if (in_array($handle_up['handleid'], $this->config->item('handleids___14926'))) {
                array_push($user_theme, intval($handle_up['handleid']));
            }

            //Superpower?
            if (in_array($handle_up['handleid'], $this->config->item('handleids___10957'))) {

                //It's unlocked!
                array_push($session_data['session_superpowers_unlocked'], intval($handle_up['handleid']));
            }
        }


        //Determine Defaults if missing any of the CUSTOM UI
        foreach ($this->config->item('handles___13890') as $handleid => $m) {

            //Set Default:
            $session_data['session_custom_ui_' . $handleid] = 0;

            //First try to find User Theme, if any:
            if (!$session_data['session_custom_ui_' . $handleid]) {
                foreach ($this->config->item('handles___' . $handleid) as $handleid2 => $m2) {
                    if (in_array($handleid2, $user_theme)) {
                        $session_data['session_custom_ui_' . $handleid] = $handleid2;
                        break;
                    }
                }
            }

            //Then try to find Website Theme, if any:
            if (!$session_data['session_custom_ui_' . $handleid]) {
                foreach ($this->config->item('handles___' . $handleid) as $handleid2 => $m2) {
                    if (in_array($handleid2, $website_theme)) {
                        $session_data['session_custom_ui_' . $handleid] = $handleid2;
                        break;
                    }
                }
            }


            //Finally try Platform Theme:
            if (!$session_data['session_custom_ui_' . $handleid]) {
                //First try to find Website Default, if any:
                foreach ($this->config->item('handles___' . $handleid) as $handleid2 => $m2) {
                    if (in_array($handleid2, $platform_theme)) {
                        $session_data['session_custom_ui_' . $handleid] = $handleid2;
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
            'chainhandleinput IN (' . join(',', $this->config->item('handleids___31057')) . ')' => null, //Permanently Unsubscribed
            'chainhandleoutput' => $e['handleid'], //This follower Handle
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                ), array(), 0) as $unsubscribed){
            $unsubscribed_time = $unsubscribed['chaintime'];
            $this->Chains->delete($unsubscribed['chainid'], $e['handleid']); //Resubscribe
        }
        if($unsubscribed_time){
            //Add to subscribed again:
            $this->Chains->create(array(
                'chainhandletype' => 4230,
                'chainhandleinput' => 4430, //Active Member
                'chainhandlecreator' => $e['handleid'],
                'chainhandleoutput' => $e['handleid'],
            ));
            $this->session->set_flashdata('flash_message', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-user-check"></i></span>Welcome Back! You Have Been Re-Subscribed :)</div>');
        }
        */

        return $session_data;

    }


    function scissor($chainhandleinput, $sub_id)
    {

        $all_results = $this->Chains->read(array(
            'chainhandleinput' => $chainhandleinput,
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
        ), array('chainhandleoutput'), 0, 0, handle_sort());

        //Remove if not in the secondary group:
        foreach ($all_results as $key => $primary_list) {
            if (!count($this->Chains->read(array(
                'chainhandleinput' => $sub_id,
                'chainhandleoutput' => $primary_list['handleid'],
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            ), array(), 0))) {
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }

    function join($full_name, $email = null, $phone_number = null, $image_url = null, $chainhandledomain = 0)
    {

        //Set website if not set:
        if (!$chainhandledomain) {
            $chainhandledomain = website_setting(0);
        }

        //All good, create new Handle:
        $new_private_users = in_array($chainhandledomain, $this->config->item('handleids___44011'));
        $added_e = $this->Handles->create(array(
            'handlename' => $full_name,
            'handlecover' => ($image_url ? $image_url : handlecover_generator(12279)),
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
                'chainhandletype' => 4230,
                'chainvalue' => trim(strtolower($email)),
                'chainhandleinput' => 3288, //Email
                'chainhandlecreator' => $added_e['handle_create']['handleid'],
                'chainhandleoutput' => $added_e['handle_create']['handleid'],
                'chainhandledomain' => $chainhandledomain,
            ));
        }

        //Add Number?
        if ($phone_number) {
            $this->Chains->create(array(
                'chainhandleinput' => 4783, //Phone
                'chainhandletype' => 4230,
                'chainvalue' => $phone_number,
                'chainhandlecreator' => $added_e['handle_create']['handleid'],
                'chainhandleoutput' => $added_e['handle_create']['handleid'],
                'chainhandledomain' => $chainhandledomain,
            ));
        }

        if ($email || $phone_number) {

            //Remove from Anonymous:
            foreach ($this->Chains->read(array(
                'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                'chainhandleinput IN (' . join(',', $this->config->item('handleids___32540')) . ')' => null, //Unsubscribers
                'chainhandleoutput' => $added_e['handle_create']['handleid'],
            )) as $unsubscriber_x) {
                $this->Chains->delete($unsubscriber_x['chainid'], $added_e['handle_create']['handleid']);
            }

            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

            //Add to Subscriber:
            $this->Chains->create(array(
                'chainhandleinput' => 4430, //Subscriber
                'chainhandletype' => 4230,
                'chainhandlecreator' => $added_e['handle_create']['handleid'],
                'chainhandleoutput' => $added_e['handle_create']['handleid'],
                'chainhandledomain' => $chainhandledomain,
            ));

        } else {

            //Add to anonymous:
            $this->Chains->create(array(
                'chainhandleinput' => 14938, //Guest Login
                'chainhandletype' => 4230,
                'chainhandlecreator' => $added_e['handle_create']['handleid'],
                'chainhandleoutput' => $added_e['handle_create']['handleid'],
                'chainhandledomain' => $chainhandledomain,
            ));

            //Assign session key:
            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

        }

        //Add if chain not already there:
        if (!count($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
            'chainhandleinput' => $chainhandledomain,
            'chainhandleoutput' => $added_e['handle_create']['handleid'],
        )))) {
            $this->Chains->create(array(
                'chainhandlecreator' => $added_e['handle_create']['handleid'], //Belongs to this Member
                'chainhandletype' => 4230,
                'chainhandleinput' => $chainhandledomain,
                'chainhandleoutput' => $added_e['handle_create']['handleid'],
            ));
        }

        //Send Welcome Email if any:
        if ($email) {
            foreach ($this->Chains->read(array(
                'chainhandletype' => 31835, //Mention
                'chainhandleinput' => 14929, //Website Welcome Email Templates
            ), array('chainhashtagoutput'), 0) as $i) {
                if (count($this->Chains->read(array(
                    'chainhandletype' => 31835, //Mention
                    'chainhandleinput' => $chainhandledomain, //for Current website
                    'chainhashtagoutput' => $i['hashtagid'], //Is this the template?
                )))) {
                    //Found the email template to send:
                    $total_sent = $this->Chains->broadcast(array($added_e['handle_create']), $i, $chainhandledomain);
                    break; //Just the first template match
                }
            }
        }

        //Update Search Index:
        update_algolia(12274, $added_e['handle_create']['handleid']);

        //Assign session & log login Chain:
        $this->Handles->activate($added_e['handle_create']);


        //Return Member:
        return array(
            'status' => 1,
            'e' => $added_e['handle_create'],
        );

    }

    function tree($chainhandletype, $handleid, $include_any_e = array(), $exclude_all_e = array(), $hard_level = 3, $hard_limit = 100, $s__level = 0)
    {

        $flat_items = array();
        $s__level++;

        if ($chainhandletype==42279) {

            //Up/Following Handle Chain Groups:
            $order_columns = handle_sort();
            $joins_objects = array('chainhandleinput');
            $query_filters = array(
                'chainhandleoutput' => $handleid,
                'chainhandletype IN (' . join(',', $this->config->item('handleids___' . $chainhandletype)) . ')' => null, //HANDLE CHAINS
            );

        } elseif ($chainhandletype==42373) {

            //Down/Followers Handle Chain Groups:
            $order_columns = handle_sort();
            $joins_objects = array('chainhandleoutput');
            $query_filters = array(
                'chainhandleinput' => $handleid,
                'chainhandletype IN (' . join(',', $this->config->item('handleids___' . $chainhandletype)) . ')' => null, //HANDLE CHAINS
            );

        } else {

            return false;

        }


        foreach ($this->Chains->read($query_filters, $joins_objects, 0, 0, $order_columns) as $handle_down) {

            //Filter Handles, if needed:
            $qualified_e = true;
            if (count($include_any_e) && !count($this->Chains->read(array(
                    'chainhandleinput IN (' . join(',', $include_any_e) . ')' => null,
                    'chainhandleoutput' => $handle_down['handleid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                )))) {
                //Must include all Handles, skip:
                $qualified_e = false;
            }
            if (count($exclude_all_e) && count($this->Chains->read(array(
                    'chainhandleinput IN (' . join(',', $exclude_all_e) . ')' => null,
                    'chainhandleoutput' => $handle_down['handleid'],
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___13548')) . ')' => null, //HANDLE CHAINS
                )))) {
                //Must IF Not Follows All Handles, skip:
                $qualified_e = false;
            }


            //Is this a new matching Handle?
            if ($qualified_e && !isset($flat_items[$handle_down['handleid']])) {
                $handle_down['s__level'] = $s__level;
                $handle_down['s__count'] = count($flat_items) + 1;
                $flat_items[$handle_down['handleid']] = $handle_down;
            }

            //Do we have more followers?
            if ($s__level >= $hard_level || count($flat_items) >= $hard_limit) {
                break;
            }

            foreach ($this->Handles->tree($chainhandletype, $handle_down['handleid'], $include_any_e, $exclude_all_e, $hard_level, $hard_limit, $s__level) as $handle_recursive_down) {
                if (!isset($flat_items[$handle_recursive_down['handleid']])) {
                    $handle_recursive_down['s__count'] = count($flat_items) + 1;
                    $flat_items[$handle_recursive_down['handleid']] = $handle_recursive_down;
                }
            }
        }

        return $flat_items;
    }


}