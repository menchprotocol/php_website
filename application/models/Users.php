<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainusercreator = 0)
    {

        if (!isset($add_fields['username'])) {
            return false;
        }

        //Validate Title
        $validate_username = validate_username($add_fields['username']);
        if (!$validate_username['status']) {
            return $validate_username;
        }

        //Determine ID
        $nextchainid = nextchainid();
        $user_session = user_session();
        $chainusercreator = ($chainusercreator > 0 ? $chainusercreator : ($user_session ? $user_session['userid'] : 14068));

        //Set defaults if missing:
        if (!isset($add_fields['userhandle'])) {
            $add_fields['userhandle'] = generate_user(12274, $validate_username['username_clean']);
        }
        if (!isset($add_fields['usercover'])) {
            $add_fields['usercover'] = 'far fa-at';
        }
        if (!isset($add_fields['userbio'])) {
            $add_fields['userbio'] = null;
        }

        //Add to Chain:
        $new_array = array(
            'chainusertype' => 12274,
            'chainusercreator' => $chainusercreator,
            'chainuserinput' => $nextchainid,
            'chainvalue' => "@" . $add_fields['userhandle']
                . "\n" . $validate_username['username_clean']
                . "\n" . $add_fields['usercover']
                . "\n" . $add_fields['userbio']
        );
        $new_x = $this->Chains->create($new_array);

        if (!$new_x['chainid']) {
            return log_error('create() failed to create a new User', $new_array);
        } elseif ($nextchainid > 0 && $nextchainid != $new_x['chainid']) {
            //Update new ID:
            $this->db->query("UPDATE ideachains SET chainuserinput = " . $new_x['chainid'] . " WHERE chainid = " . $new_x['chainid'] . ";");
        }

        //Add to cache:
        $this->db->insert('users', array(
            'userid' => $new_x['chainid'],
            'usercreator' => $new_x['chainusercreator'],
            'usertime' => $new_x['chaintime'],
            'userhandle' => $add_fields['userhandle'],
            'username' => $validate_username['username_clean'],
            'usercover' => $add_fields['usercover'],
            'userbio' => $add_fields['userbio'],
        ));

        //Update Search Index:
        update_algolia(12274, $new_x['chainid']);

        //Fetch to return the complete User data:
        $es = $this->Users->read(array(
            'userid' => $new_x['chainid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'user_create' => $es[0],
        );

    }

    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array('userid' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target Users:
        $this->db->select($select);
        $this->db->from('users');

        $void_found = false;
        foreach ($query_filters as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
            if (substr_count($key, 'uservoid')) {
                $void_found = true;
            }
        }
        if (!$void_found) {
            //Auto add:
            $this->db->where('uservoid', 0); //Not Void
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
                if (!user_access(null, $value['userid'], $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }

        return $results;

    }


    function update($userid, $update_columns, $chainusercreator = 0)
    {

        //Find existing chain to update:
        foreach ($this->Chains->read(array(
            'chainusertype' => 12274,
            'chainuserinput' => $userid,
        ), array(), 1) as $chain) {

            //Now fetch existing data from cache table:
            foreach ($this->Users->read(array(
                'userid' => $userid,
                'uservoid >=' => 0,
            ), 1) as $cache) {

                //Validate that something has changed:
                $must_update_chain = 0;

                foreach ($update_columns as $key => $value) {
                    if ($cache[$key] === $value) {

                        //its the same so remove it:
                        unset($update_columns[$key]);

                    } elseif (in_array($key, array('userhandle', 'username', 'usercover', 'userbio'))) {

                        $must_update_chain = 1;

                        if ($key == 'username') {

                            //Validate title:
                            $validate_username = validate_username($value);
                            if (!$validate_username['status']) {
                                return 0;
                            } else {
                                $update_columns[$key] = $validate_username['username_clean'];
                            }

                        } elseif ($key == 'userhandle') {

                            //Update term on all references
                            foreach ($this->Chains->read(array(
                                'chainusertype IN (' . join(',', $this->config->item('userids___13550')) . ')' => null, //Mentions
                                'chainuserinput' => $userid,
                            ), array('chainpostoutput'), 0) as $ref) {

                                //Update the post index:
                                post_index($ref['posttext'], $ref['postid'], $chainusercreator, $ref['posthashtag'], $value);

                            }
                        }
                    }
                }
                if (!count($update_columns)) {
                    //Nothing to update:
                    return 0;
                }

                //Update Chain only if needed:
                if ($must_update_chain) {

                    $update_columns['usertime'] = date("Y-m-d H:i:s"); //Update timestamp

                    $this->Chains->update($chain['chainid'], array(
                        'chainvalue' => "@" . (isset($update_columns['userhandle']) ? $update_columns['userhandle'] : $cache['userhandle'])
                            . "\n" . (isset($update_columns['username']) ? $update_columns['username'] : $cache['username'])
                            . "\n" . (isset($update_columns['usercover']) ? $update_columns['usercover'] : $cache['usercover'])
                            . "\n" . (isset($update_columns['userbio']) ? $update_columns['userbio'] : $cache['userbio'])
                    ));

                    //Sync algolia:
                    update_algolia(12274, intval($userid));

                }

                //Update Cache:
                $this->db->where('userid', $userid);
                $this->db->update('users', $update_columns);
                return $this->db->affected_rows();

            }



            log_error('Active User @' . $userid . ' not found on cache with query: '.$this->db->last_query(), $update_columns);
            return 0;

        }

        log_error('Active User @' . $userid . ' not found on chain or cache', $update_columns);
        return 0;

    }


    function delete($userid, $chainusercreator = 0, $migrateid = 0)
    {

        //Find all chains to delete/migrate:
        $x_adjusted = 0;
        foreach ($this->Chains->read(array(
            '(chainid=' . $userid . ' OR chainuserinput=' . $userid . ' OR chainuseroutput=' . $userid . ' OR chainusercreator=' . $userid . ' OR chainusertype=' . $userid . ')' => null,
        ), array(), 0) as $migrate) {

            if ($migrateid) {

                $new_array = array(
                    'chainusercreator' => ($migrate['chainusercreator'] == $userid ? $migrateid : ($chainusercreator > 0 ? $chainusercreator : $migrate['chainusercreator'])),
                    'chainusertype' => ($migrate['chainusertype'] == $userid ? $migrateid : $migrate['chainusertype']),
                    'chainuserinput' => ($migrate['chainuserinput'] == $userid ? $migrateid : $migrate['chainuserinput']),
                    'chainuseroutput' => ($migrate['chainuseroutput'] == $userid ? $migrateid : $migrate['chainuseroutput']),
                    'chainpostinput' => $migrate['chainpostinput'],
                    'chainpostoutput' => $migrate['chainpostoutput'],
                );

                //Update if this new one is unique:
                if (!count($this->Chains->read($new_array))) {
                    $x_adjusted += $this->Chains->update($migrate['chainid'], $new_array);
                    continue;
                }
            }

            //Just remove it:
            $x_adjusted += $this->Chains->delete($migrate['chainid'], $chainusercreator);

        }

        if ($x_adjusted) {

            //Remove from Table:
            $this->db->where('userid', $userid);
            $this->db->update('users', array(
                'usertime' => date("Y-m-d H:i:s"),
                'uservoid' => 1,
            ));

        } else {
            //Failed to remove
            log_error('users->delete() Failed to remove @' . $userid . ' Chain ID', array(
                'chainusercreator' => $chainusercreator,
                'chainpostup' => $userid,
                'chainpostdown' => $migrateid,
            ));
        }


        //Return Chains deleted:
        return $x_adjusted;

    }


    function command($userid, $action_userid, $action_command1, $action_command2, $chainusercreator)
    {

        //Alert: Has a twin function called i_command()

        boost_power();

        $action_command1 = trim($action_command1);
        $action_command2 = trim($action_command2);


        if (!in_array($action_userid, $this->config->item('userids___4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif (in_array($action_userid, array(5981, 5982, 11956, 13441)) && !view_valid_user_user($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown User. Format must be: @UserUser',
            );

        } elseif (in_array($action_userid, array(11956)) && !view_valid_user_user($action_command2)) {

            return array(
                'status' => 0,
                'message' => 'Unknown User. Format must be: @UserUser',
            );

        }


        //Basic input validation done, let's continue
        $applied_success = 0; //To be populated

        //Fetch all followers:
        $followers = $this->Chains->read(array(
            'chainuserinput' => $userid,
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array('chainuseroutput'), 0);


        //Process request:
        foreach ($followers as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_userid == 4998) { //Add Prefix String

                $this->Users->update($x['userid'], array(
                    'username' => $action_command1 . $x['username'],
                ), $chainusercreator);

                $applied_success++;

            } elseif ($action_userid == 4999) { //Add Postfix String

                $this->Users->update($x['userid'], array(
                    'username' => $x['username'] . $action_command1,
                ), $chainusercreator);

                $applied_success++;

            } elseif (in_array($action_userid, array(5981, 5982, 11956, 13441)) && view_valid_user_user($action_command1)) { //Add/Delete/Migrate followings User

                //What member searched for:
                foreach ($this->Users->read(array(
                    'LOWER(userhandle)' => strtolower(view_valid_user_user($action_command1)),
                )) as $e) {

                    //See if follower User has searched followings User:
                    $down_up_e = $this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuseroutput' => $x['userid'], //This follower User
                        'chainuserinput' => $e['userid'],
                    ));

                    if ((in_array($action_userid, array(5981, 13441)) && count($down_up_e) == 0)) {

                        $add_fields = array(
                            'chainusercreator' => $chainusercreator,
                            'chainusertype' => 4230,
                            'chainuseroutput' => $x['userid'], //This follower User
                            'chainuserinput' => $e['userid'],
                        );

                        if ($action_userid == 13441) {
                            //Copy message only if moving:
                            $add_fields['chainvalue'] = $x['chainvalue'];
                        }

                        //Following Member Addition
                        $this->Chains->create($add_fields);

                        $applied_success++;

                        if ($action_userid == 13441) {
                            //Since we're migrating we should remove from here:
                            $this->Chains->delete($x['chainid'], $chainusercreator);
                        }

                    } elseif (in_array($action_userid, array(5982, 11956)) && count($down_up_e) > 0) {

                        if ($action_userid == 5982) {

                            //Following Member Removal
                            foreach ($down_up_e as $delete_tr) {
                                $this->Chains->delete($delete_tr['chainid'], $chainusercreator);
                                $applied_success++;
                            }

                        } elseif ($action_userid == 11956 && view_valid_user_user($action_command2)) {

                            foreach ($this->Users->read(array(
                                'LOWER(userhandle)' => strtolower(view_valid_user_user($action_command2)),
                            )) as $e) {
                                //Add as a followings because it meets the condition
                                $this->Chains->create(array(
                                    'chainusercreator' => $chainusercreator,
                                    'chainusertype' => 4230,
                                    'chainuseroutput' => $x['userid'], //This follower User
                                    'chainuserinput' => $e['userid'],
                                ));
                                $applied_success++;
                            }
                        }
                    }
                }

            } elseif ($action_userid == 5943) { //Member Mass Update Member Cover

                $this->Users->update($x['userid'], array(
                    'usercover' => $action_command1,
                ), $chainusercreator);

                $applied_success++;

            } elseif ($action_userid == 12318 && !strlen($x['usercover'])) { //Member Mass Update Member Cover

                $this->Users->update($x['userid'], array(
                    'usercover' => $action_command1,
                ), $chainusercreator);

                $applied_success++;

            } elseif ($action_userid == 5000 && substr_count(strtolower($x['username']), strtolower($action_command1)) > 0) { //Replace Member Matching Name

                $this->Users->update($x['userid'], array(
                    'username' => str_ireplace($action_command1, $action_command2, $x['username']),
                ), $chainusercreator);

                $applied_success++;

            } elseif ($action_userid == 10625 && substr_count($x['usercover'], $action_command1) > 0) { //Replace Member Matching Cover

                $this->Users->update($x['userid'], array(
                    'usercover' => str_replace($action_command1, $action_command2, $x['usercover']),
                ), $chainusercreator);

                $applied_success++;

            } elseif ($action_userid == 5001 && substr_count($x['chainvalue'], $action_command1) > 0) { //Replace Chain Matching String

                $new_message = str_replace($action_command1, $action_command2, $x['chainvalue']);

                $this->Chains->update($x['chainid'], array(
                    'chainvalue' => $new_message,
                    'chainusercreator' => $chainusercreator,
                ));

                $applied_success++;

            } elseif ($action_userid == 26093) { //Replace Chain Matching String

                $this->Chains->update($x['chainid'], array(
                    'chainvalue' => $action_command1,
                    'chainusercreator' => $chainusercreator,
                ));

                $applied_success++;

            } elseif ($action_userid == 42804 && ($action_command1 == '*' || $x['chainusertype'] == $action_command1) && in_array($action_command2, $this->config->item('userids___13548') /* User Chain Types */)) { //Update Matching Interaction Type

                $this->Chains->update($x['chainid'], array(
                    'chainusertype' => $action_command2,
                    'chainusercreator' => $chainusercreator,
                ));
                $applied_success++;

            }
        }

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($followers) . ' Users updated',
        );

    }


    function activate($e, $update_session = false, $is_cookie = false)
    {

        //PROFILE
        $session_data = array(
            'session_user' => $e,
            'session_superpowers_unlocked' => array(),
        );

        $websiteuserid = website_setting(0);

        //Make sure they also belong to this website's members:
        if ($websiteuserid != 4341 && !count($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                'chainuserinput' => $websiteuserid,
                'chainuseroutput' => $e['userid'],
            )))) {
            $this->Chains->create(array(
                'chainusercreator' => $e['userid'],
                'chainusertype' => 4230,
                'chainuserinput' => $websiteuserid,
                'chainuseroutput' => $e['userid'],
            ));
        }


        //Check & Adjust their subscription, IF needed:
        $resubscribed = 0;
        foreach ($this->Chains->read(array(
            'chainuserinput IN (' . join(',', $this->config->item('userids___29648')) . ')' => null, //Unsubscribers
            'chainuseroutput' => $e['userid'],
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        )) as $unsubscribe) {
            $resubscribed += $this->Chains->delete($unsubscribe['chainid'], $e['userid']);
        }
        if ($resubscribed > 0) {
            //Add Back to Subscribers:
            $this->Chains->create(array(
                'chainusertype' => 4230,
                'chainuserinput' => 4430, //Active Member
                'chainusercreator' => $e['userid'],
                'chainuseroutput' => $e['userid'],
            ));
        }


        if (!$update_session && !$is_cookie) {
            //Create Cookie:
            $cookie_time = time();
            $cookie_val = $e['userid'] . 'ABCEFG' . $cookie_time . 'ABCEFG' . view_hash($e['userid'] . $cookie_time);
            setcookie('auth_cookie', $cookie_val, ($cookie_time + (86400 * view_memory(6404, 14031))), "/");
        }


        //Fetch Platform Defaults:
        $platform_theme = array();
        foreach ($this->Chains->read(array(
            'chainuserinput IN (' . join(',', $this->config->item('userids___14926')) . ')' => null, //Website Theme Items
            'chainuseroutput' => 6404, //Platform Default
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0) as $x) {
            array_push($platform_theme, intval($x['chainuserinput']));
        }

        //Fetch Website Defaults:
        $website_theme = array();
        foreach ($this->Chains->read(array(
            'chainuserinput IN (' . join(',', $this->config->item('userids___14926')) . ')' => null, //Website Theme Items
            'chainuseroutput' => website_setting(0), //Website ID
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array(), 0) as $x) {
            array_push($website_theme, intval($x['chainuserinput']));
        }


        //Fetch User Defaults:
        $user_theme = array();
        foreach ($this->Chains->read(array(
            'chainuseroutput' => $e['userid'], //This follower User
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array('chainuserinput'), 0) as $user_up) {

            //Website Theme Items?
            if (in_array($user_up['userid'], $this->config->item('userids___14926'))) {
                array_push($user_theme, intval($user_up['userid']));
            }

            //Superpower?
            if (in_array($user_up['userid'], $this->config->item('userids___10957'))) {

                //It's unlocked!
                array_push($session_data['session_superpowers_unlocked'], intval($user_up['userid']));
            }
        }


        //Determine Defaults if missing any of the CUSTOM UI
        foreach ($this->config->item('users___13890') as $userid => $m) {

            //Set Default:
            $session_data['session_custom_ui_' . $userid] = 0;

            //First try to find User Theme, if any:
            if (!$session_data['session_custom_ui_' . $userid]) {
                foreach ($this->config->item('users___' . $userid) as $userid2 => $m2) {
                    if (in_array($userid2, $user_theme)) {
                        $session_data['session_custom_ui_' . $userid] = $userid2;
                        break;
                    }
                }
            }

            //Then try to find Website Theme, if any:
            if (!$session_data['session_custom_ui_' . $userid]) {
                foreach ($this->config->item('users___' . $userid) as $userid2 => $m2) {
                    if (in_array($userid2, $website_theme)) {
                        $session_data['session_custom_ui_' . $userid] = $userid2;
                        break;
                    }
                }
            }


            //Finally try Platform Theme:
            if (!$session_data['session_custom_ui_' . $userid]) {
                //First try to find Website Default, if any:
                foreach ($this->config->item('users___' . $userid) as $userid2 => $m2) {
                    if (in_array($userid2, $platform_theme)) {
                        $session_data['session_custom_ui_' . $userid] = $userid2;
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
            'chainuserinput IN (' . join(',', $this->config->item('userids___31057')) . ')' => null, //Permanently Unsubscribed
            'chainuseroutput' => $e['userid'], //This follower User
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ), array(), 0) as $unsubscribed){
            $unsubscribed_time = $unsubscribed['chaintime'];
            $this->Chains->delete($unsubscribed['chainid'], $e['userid']); //Resubscribe
        }
        if($unsubscribed_time){
            //Add to subscribed again:
            $this->Chains->create(array(
                'chainusertype' => 4230,
                'chainuserinput' => 4430, //Active Member
                'chainusercreator' => $e['userid'],
                'chainuseroutput' => $e['userid'],
            ));
            $this->session->set_flashdata('flash_message', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-user-check"></i></span>Welcome Back! You Have Been Re-Subscribed :)</div>');
        }
        */

        return $session_data;

    }


    function scissor($chainuserinput, $sub_id)
    {

        $all_results = $this->Chains->read(array(
            'chainuserinput' => $chainuserinput,
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
        ), array('chainuseroutput'), 0, 0, user_sort());

        //Remove if not in the secondary group:
        foreach ($all_results as $key => $primary_list) {
            if (!count($this->Chains->read(array(
                'chainuserinput' => $sub_id,
                'chainuseroutput' => $primary_list['userid'],
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ), array(), 0))) {
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }

    function join($full_name, $email = null, $phone_number = null, $image_url = null, $chainuserdomain = 0)
    {

        //Set website if not set:
        if (!$chainuserdomain) {
            $chainuserdomain = website_setting(0);
        }

        //All good, create new User:
        $new_private_users = in_array($chainuserdomain, $this->config->item('userids___44011'));
        $added_e = $this->Users->create(array(
            'username' => $full_name,
            'usercover' => ($image_url ? $image_url : usercover_generator(12279)),
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
                'chainusertype' => 4230,
                'chainvalue' => trim(strtolower($email)),
                'chainuserinput' => 3288, //Email
                'chainusercreator' => $added_e['user_create']['userid'],
                'chainuseroutput' => $added_e['user_create']['userid'],
                'chainuserdomain' => $chainuserdomain,
            ));
        }

        //Add Number?
        if ($phone_number) {
            $this->Chains->create(array(
                'chainuserinput' => 4783, //Phone
                'chainusertype' => 4230,
                'chainvalue' => $phone_number,
                'chainusercreator' => $added_e['user_create']['userid'],
                'chainuseroutput' => $added_e['user_create']['userid'],
                'chainuserdomain' => $chainuserdomain,
            ));
        }

        if ($email || $phone_number) {

            //Remove from Anonymous:
            foreach ($this->Chains->read(array(
                'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                'chainuserinput IN (' . join(',', $this->config->item('userids___32540')) . ')' => null, //Unsubscribers
                'chainuseroutput' => $added_e['user_create']['userid'],
            )) as $unsubscriber_x) {
                $this->Chains->delete($unsubscriber_x['chainid'], $added_e['user_create']['userid']);
            }

            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

            //Add to Subscriber:
            $this->Chains->create(array(
                'chainuserinput' => 4430, //Subscriber
                'chainusertype' => 4230,
                'chainusercreator' => $added_e['user_create']['userid'],
                'chainuseroutput' => $added_e['user_create']['userid'],
                'chainuserdomain' => $chainuserdomain,
            ));

        } else {

            //Add to anonymous:
            $this->Chains->create(array(
                'chainuserinput' => 14938, //Guest Login
                'chainusertype' => 4230,
                'chainusercreator' => $added_e['user_create']['userid'],
                'chainuseroutput' => $added_e['user_create']['userid'],
                'chainuserdomain' => $chainuserdomain,
            ));

            //Assign session key:
            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

        }

        //Add if chain not already there:
        if (!count($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
            'chainuserinput' => $chainuserdomain,
            'chainuseroutput' => $added_e['user_create']['userid'],
        )))) {
            $this->Chains->create(array(
                'chainusercreator' => $added_e['user_create']['userid'], //Belongs to this Member
                'chainusertype' => 4230,
                'chainuserinput' => $chainuserdomain,
                'chainuseroutput' => $added_e['user_create']['userid'],
            ));
        }

        //Send Welcome Email if any:
        if ($email) {
            foreach ($this->Chains->read(array(
                'chainusertype' => 31835, //Mention
                'chainuserinput' => 14929, //Website Welcome Email Templates
            ), array('chainpostoutput'), 0) as $i) {
                if (count($this->Chains->read(array(
                    'chainusertype' => 31835, //Mention
                    'chainuserinput' => $chainuserdomain, //for Current website
                    'chainpostoutput' => $i['postid'], //Is this the template?
                )))) {
                    //Found the email template to send:
                    $total_sent = $this->Chains->broadcast(array($added_e['user_create']), $i, $chainuserdomain);
                    break; //Just the first template match
                }
            }
        }

        //Update Search Index:
        update_algolia(12274, $added_e['user_create']['userid']);

        //Assign session & log login Chain:
        $this->Users->activate($added_e['user_create']);


        //Return Member:
        return array(
            'status' => 1,
            'e' => $added_e['user_create'],
        );

    }

    function tree($chainusertype, $userid, $include_any_e = array(), $exclude_all_e = array(), $hard_level = 3, $hard_limit = 100, $s__level = 0)
    {

        $flat_items = array();
        $s__level++;

        if ($chainusertype == 42279) {

            //Up/Following User Chain Groups:
            $order_columns = user_sort();
            $joins_objects = array('chainuserinput');
            $query_filters = array(
                'chainuseroutput' => $userid,
                'chainusertype IN (' . join(',', $this->config->item('userids___' . $chainusertype)) . ')' => null, //USER CHAINS
            );

        } elseif ($chainusertype == 42373) {

            //Down/Followers User Chain Groups:
            $order_columns = user_sort();
            $joins_objects = array('chainuseroutput');
            $query_filters = array(
                'chainuserinput' => $userid,
                'chainusertype IN (' . join(',', $this->config->item('userids___' . $chainusertype)) . ')' => null, //USER CHAINS
            );

        } else {

            return false;

        }


        foreach ($this->Chains->read($query_filters, $joins_objects, 0, 0, $order_columns) as $user_down) {

            //Filter Users, if needed:
            $qualified_e = true;
            if (count($include_any_e) && !count($this->Chains->read(array(
                    'chainuserinput IN (' . join(',', $include_any_e) . ')' => null,
                    'chainuseroutput' => $user_down['userid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                )))) {
                //Must include all Users, skip:
                $qualified_e = false;
            }
            if (count($exclude_all_e) && count($this->Chains->read(array(
                    'chainuserinput IN (' . join(',', $exclude_all_e) . ')' => null,
                    'chainuseroutput' => $user_down['userid'],
                    'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null, //USER CHAINS
                )))) {
                //Must IF Not Follows All Users, skip:
                $qualified_e = false;
            }


            //Is this a new matching User?
            if ($qualified_e && !isset($flat_items[$user_down['userid']])) {
                $user_down['s__level'] = $s__level;
                $user_down['s__count'] = count($flat_items) + 1;
                $flat_items[$user_down['userid']] = $user_down;
            }

            //Do we have more followers?
            if ($s__level >= $hard_level || count($flat_items) >= $hard_limit) {
                break;
            }

            foreach ($this->Users->tree($chainusertype, $user_down['userid'], $include_any_e, $exclude_all_e, $hard_level, $hard_limit, $s__level) as $user_recursive_down) {
                if (!isset($flat_items[$user_recursive_down['userid']])) {
                    $user_recursive_down['s__count'] = count($flat_items) + 1;
                    $flat_items[$user_recursive_down['userid']] = $user_recursive_down;
                }
            }
        }

        return $flat_items;
    }


}