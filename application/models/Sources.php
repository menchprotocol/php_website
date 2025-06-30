<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sources extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainsourcecreator = 0)
    {

        //Validate Title
        $validate_sourcevalue = validate_sourcevalue($add_fields['sourcevalue']);
        if (!$validate_sourcevalue['status']) {
            return $validate_sourcevalue;
        }

        //Log Chain new Source:
        $source_session = source_session();
        $chainsourcecreator = ($chainsourcecreator > 0 ? $chainsourcecreator : ($source_session ? $source_session['sourceid'] : 14068));

        $nextchainid = nextchainid();
        $creation_data = array(
            'chainsourcecreator' => $chainsourcecreator,
            'chainsourceup' => $chainsourcecreator,
            'chainsourcedown' => $nextchainid,
            'chainsourcetype' => 12274, //New Source Created
            'chainvalue' => $validate_sourcevalue['sourcevalue_clean'],
        );

        if (isset($add_fields['sourceid']) && !count($this->Chains->read(array('chainid' => $add_fields['sourceid'])))) {
            //Set the chain ID since its not in the ledger:
            $creation_data['chainid'] = $add_fields['sourceid'];
        }
        $new_x = $this->Chains->create($creation_data);

        if (!$new_x['chainid']) {
            return log_error('create() failed to create a new Source', array(
                'chainsourcedown' => $chainsourcecreator,
                'chainsourcecreator' => $chainsourcecreator,
            ));
        } elseif($nextchainid!=$new_x['chainid']) {
            //Something went wrong, update:
            $this->Chains->update($new_x['chainid'], array(
                'chainsourcedown' => $new_x['chainid'],
            ));
        }

        //Handle Generation
        if (!isset($add_fields['sourcehandle'])) {
            $add_fields['sourcehandle'] = generate_handle(12274, $validate_sourcevalue['sourcevalue_clean']);
        }
        $this->Chains->create(array(
            'chainsourcecreator' => $chainsourcecreator,
            'chainsourcetype' => 44179, //Trigerred
            'chainsourceup' => 32338, //Source Handle
            'chainvalue' => $add_fields['sourcehandle'],
            'chainsourcedown' => $new_x['chainid'],
        ));

        $update_data = array(
            'sourceid' => $new_x['chainid'],
            'sourcehandle' => $add_fields['sourcehandle'],
            'sourcevalue' => $validate_sourcevalue['sourcevalue_clean'],
        );

        //Cover saving if any
        if (isset($add_fields['sourcecover'])) {
            $this->Chains->create(array(
                'chainsourcecreator' => $chainsourcecreator,
                'chainsourcetype' => 44179, //Trigerred
                'chainsourceup' => 6198, //Source Cover
                'chainvalue' => $add_fields['sourcecover'],
                'chainsourcedown' => $new_x['chainid'],
            ));
            $update_data['sourcecover'] = $add_fields['sourcecover'];
        }

        //Add to cache:
        if (!count($this->Sources->read(array('sourceid' => $new_x['chainid'])))) {
            $this->db->insert('cachesources', $update_data);
        }


        //Update Search Index:
        update_algolia(12274, $new_x['chainid']);

        //Fetch to return the complete Source data:
        $es = $this->Sources->read(array(
            'sourceid' => $new_x['chainid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'source_create' => $es[0],
        );

    }

    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array('sourceid' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target Sources:
        $this->db->select($select);
        $this->db->from('cachesources');
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
                if (!source_access(null, $value['sourceid'], $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }

        return $results;

    }


    function update($chainid, $update_columns, $chainsourcecreator = 0)
    {

        if (!count($update_columns)) {
            return false;
        }

        $sources_found = $this->Sources->read(array('sourceid' => $chainid));
        if (!count($sources_found)) {
            log_error('Source @' . $chainid . ' not found in Sources table');
            return false;
        } elseif (!count($this->Chains->read(array('chainid' => $chainid)))) {
            log_error('Source @' . $chainid . ' not found in Chains table');
            return false;
        }

        $affected_rows = 0;
        foreach ($sources_found as $source_current) {

            $must_sync_found = false;
            $skip_sync_ledger = array('sourceexternal', 'sourcekey');
            $must_sync_ledger = array(
                'sourcehandle' => 32338,
                'sourcecover' => 6198,
                'sourcevalue' => 6197,
            );

            //See what is being updated:
            foreach ($update_columns as $key => $value) {
                if (array_key_exists($key, $must_sync_ledger)) {
                    //Update if anything changed:
                    if ($value != $source_current[$key]) {
                        $this->Chains->create(array(
                            'chainsourcecreator' => $chainsourcecreator,
                            'chainsourcetype' => 44179, //Trigerred
                            'chainsourceup' => $must_sync_ledger[$key], //Idea Hashtag
                            'chainvalue' => $value,
                            'chainsourcedown' => $chainid,
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
            $this->db->where('sourceid', $chainid);
            $this->db->update('cachesources', $update_columns);
            $affected_rows = $this->db->affected_rows();

            if ($must_sync_found) {
                //Sync algolia:
                update_algolia(12274, intval($chainid));
            }

        }

        return $affected_rows;

    }


    function delete($sourceid, $chainsourcecreator = 0, $migrateid = 0)
    {

        //Find all chains to delete/migrate:
        $x_adjusted = 0;
        foreach ($this->Chains->read(array(
            '(chainid=' . $sourceid . ' OR chainsourceup=' . $sourceid . ' OR chainsourcedown=' . $sourceid . ' OR chainsourcecreator=' . $sourceid . ' OR chainsourcetype=' . $sourceid . ' OR chainsourcedomain=' . $sourceid . ')' => null,
        ), array(), 0) as $migrate) {

            if ($migrateid) {

                $new_array = array(
                    'chainsourcecreator' => ($migrate['chainsourcecreator'] == $sourceid ? $migrateid : ($chainsourcecreator > 0 ? $chainsourcecreator : $migrate['chainsourcecreator'])),
                    'chainsourcetype' => ($migrate['chainsourcetype'] == $sourceid ? $migrateid : $migrate['chainsourcetype']),
                    'chainsourcedomain' => ($migrate['chainsourcedomain'] == $sourceid ? $migrateid : $migrate['chainsourcedomain']),
                    'chainsourceup' => ($migrate['chainsourceup'] == $sourceid ? $migrateid : $migrate['chainsourceup']),
                    'chainsourcedown' => ($migrate['chainsourcedown'] == $sourceid ? $migrateid : $migrate['chainsourcedown']),
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
            $x_adjusted += $this->Chains->delete($migrate['chainid'], $chainsourcecreator);

        }

        if ($x_adjusted) {
            //Remove from Table:
            $this->db->query("DELETE FROM cachesources WHERE sourceid = " . $sourceid . ";");

            //Update Search Index?
            update_algolia(12277, $sourceid);
        } else {
            //Failed to remove
            log_error('sources->delete() Failed to remove @' . $sourceid . ' Chain ID', array(
                'chainsourcecreator' => $chainsourcecreator,
                'chainideaup' => $sourceid,
                'chainideadown' => $migrateid,
            ));
        }


        //Return Chains deleted:
        return $x_adjusted;

    }


    function command($sourceid, $action_sourceid, $action_command1, $action_command2, $chainsourcecreator)
    {

        //Alert: Has a twin function called i_command()

        boost_power();

        $action_command1 = trim($action_command1);
        $action_command2 = trim($action_command2);


        if (!in_array($action_sourceid, $this->config->item('sourceids___4997'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif (in_array($action_sourceid, array(5981, 5982, 11956, 13441)) && !view_valid_handle_source($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @SourceHandle',
            );

        } elseif (in_array($action_sourceid, array(11956)) && !view_valid_handle_source($action_command2)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @SourceHandle',
            );

        }


        //Basic input validation done, let's continue
        $applied_success = 0; //To be populated

        //Fetch all followers:
        $followers = $this->Chains->read(array(
            'chainsourceup' => $sourceid,
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainsourcedown'), 0);


        //Process request:
        foreach ($followers as $x) {

            //Logic here must match items in e_mass_actions config variable

            //Take command-specific action:
            if ($action_sourceid == 4998) { //Add Prefix String

                $this->Sources->update($x['sourceid'], array(
                    'sourcevalue' => $action_command1 . $x['sourcevalue'],
                ), $chainsourcecreator);

                $applied_success++;

            } elseif ($action_sourceid == 4999) { //Add Postfix String

                $this->Sources->update($x['sourceid'], array(
                    'sourcevalue' => $x['sourcevalue'] . $action_command1,
                ), $chainsourcecreator);

                $applied_success++;

            } elseif (in_array($action_sourceid, array(5981, 5982, 11956, 13441)) && view_valid_handle_source($action_command1)) { //Add/Delete/Migrate followings Source

                //What member searched for:
                foreach ($this->Sources->read(array(
                    'LOWER(sourcehandle)' => strtolower(view_valid_handle_source($action_command1)),
                )) as $e) {

                    //See if follower Source has searched followings Source:
                    $down_up_e = $this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                        'chainsourcedown' => $x['sourceid'], //This follower Source
                        'chainsourceup' => $e['sourceid'],
                    ));

                    if ((in_array($action_sourceid, array(5981, 13441)) && count($down_up_e) == 0)) {

                        $add_fields = array(
                            'chainsourcecreator' => $chainsourcecreator,
                            'chainsourcetype' => 4230,
                            'chainsourcedown' => $x['sourceid'], //This follower Source
                            'chainsourceup' => $e['sourceid'],
                        );

                        if ($action_sourceid == 13441) {
                            //Copy message only if moving:
                            $add_fields['chainvalue'] = $x['chainvalue'];
                        }

                        //Following Member Addition
                        $this->Chains->create($add_fields);

                        $applied_success++;

                        if ($action_sourceid == 13441) {
                            //Since we're migrating we should remove from here:
                            $this->Chains->delete($x['chainid'], $chainsourcecreator);
                        }

                    } elseif (in_array($action_sourceid, array(5982, 11956)) && count($down_up_e) > 0) {

                        if ($action_sourceid == 5982) {

                            //Following Member Removal
                            foreach ($down_up_e as $delete_tr) {
                                $this->Chains->delete($delete_tr['chainid'], $chainsourcecreator);
                                $applied_success++;
                            }

                        } elseif ($action_sourceid == 11956 && view_valid_handle_source($action_command2)) {

                            foreach ($this->Sources->read(array(
                                'LOWER(sourcehandle)' => strtolower(view_valid_handle_source($action_command2)),
                            )) as $e) {
                                //Add as a followings because it meets the condition
                                $this->Chains->create(array(
                                    'chainsourcecreator' => $chainsourcecreator,
                                    'chainsourcetype' => 4230,
                                    'chainsourcedown' => $x['sourceid'], //This follower Source
                                    'chainsourceup' => $e['sourceid'],
                                ));
                                $applied_success++;
                            }
                        }
                    }
                }

            } elseif ($action_sourceid == 5943) { //Member Mass Update Member Cover

                $this->Sources->update($x['sourceid'], array(
                    'sourcecover' => $action_command1,
                ), $chainsourcecreator);

                $applied_success++;

            } elseif ($action_sourceid == 12318 && !strlen($x['sourcecover'])) { //Member Mass Update Member Cover

                $this->Sources->update($x['sourceid'], array(
                    'sourcecover' => $action_command1,
                ), $chainsourcecreator);

                $applied_success++;

            } elseif ($action_sourceid == 5000 && substr_count(strtolower($x['sourcevalue']), strtolower($action_command1)) > 0) { //Replace Member Matching Name

                $this->Sources->update($x['sourceid'], array(
                    'sourcevalue' => str_ireplace($action_command1, $action_command2, $x['sourcevalue']),
                ), $chainsourcecreator);

                $applied_success++;

            } elseif ($action_sourceid == 10625 && substr_count($x['sourcecover'], $action_command1) > 0) { //Replace Member Matching Cover

                $this->Sources->update($x['sourceid'], array(
                    'sourcecover' => str_replace($action_command1, $action_command2, $x['sourcecover']),
                ), $chainsourcecreator);

                $applied_success++;

            } elseif ($action_sourceid == 5001 && substr_count($x['chainvalue'], $action_command1) > 0) { //Replace Chain Matching String

                $new_message = str_replace($action_command1, $action_command2, $x['chainvalue']);

                $this->Chains->update($x['chainid'], array(
                    'chainvalue' => $new_message,
                    'chainsourcecreator' => $chainsourcecreator,
                ));

                $applied_success++;

            } elseif ($action_sourceid == 26093) { //Replace Chain Matching String

                $this->Chains->update($x['chainid'], array(
                    'chainvalue' => $action_command1,
                    'chainsourcecreator' => $chainsourcecreator,
                ));

                $applied_success++;

            } elseif ($action_sourceid == 42804 && ($action_command1 == '*' || $x['chainsourcetype'] == $action_command1) && in_array($action_command2, $this->config->item('sourceids___13548') /* Source Chain Types */)) { //Update Matching Interaction Type

                $this->Chains->update($x['chainid'], array(
                    'chainsourcetype' => $action_command2,
                    'chainsourcecreator' => $chainsourcecreator,
                ));
                $applied_success++;

            }
        }

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($followers) . ' Sources updated',
        );

    }


    function activate($e, $update_session = false, $is_cookie = false)
    {

        //PROFILE
        $session_data = array(
            'session_source' => $e,
            'session_superpowers_unlocked' => array(),
        );

        $websitesourceid = website_setting(0);

        //Make sure they also belong to this website's members:
        if ($websitesourceid!=4341 && !count($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            'chainsourceup' => $websitesourceid,
            'chainsourcedown' => $e['sourceid'],
        )))) {
            $this->Chains->create(array(
                'chainsourcecreator' => $e['sourceid'],
                'chainsourcetype' => 4230,
                'chainsourceup' => $websitesourceid,
                'chainsourcedown' => $e['sourceid'],
            ));
        }


        //Check & Adjust their subscription, IF needed:
        $resubscribed = 0;
        foreach ($this->Chains->read(array(
            'chainsourceup IN (' . join(',', $this->config->item('sourceids___29648')) . ')' => null, //Unsubscribers
            'chainsourcedown' => $e['sourceid'],
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        )) as $unsubscribe) {
            $resubscribed += $this->Chains->delete($unsubscribe['chainid'], $e['sourceid']);
        }
        if ($resubscribed > 0) {
            //Add Back to Subscribers:
            $this->Chains->create(array(
                'chainsourcetype' => 4230,
                'chainsourceup' => 4430, //Active Member
                'chainsourcecreator' => $e['sourceid'],
                'chainsourcedown' => $e['sourceid'],
            ));
        }


        if (!$update_session && !$is_cookie) {
            //Create Cookie:
            $cookie_time = time();
            $cookie_val = $e['sourceid'] . 'ABCEFG' . $cookie_time . 'ABCEFG' . view_hash($e['sourceid'] . $cookie_time);
            setcookie('auth_cookie', $cookie_val, ($cookie_time + (86400 * view_memory(6404, 14031))), "/");
        }


        //Fetch Platform Defaults:
        $platform_theme = array();
        foreach ($this->Chains->read(array(
            'chainsourceup IN (' . join(',', $this->config->item('sourceids___14926')) . ')' => null, //Website Theme Items
            'chainsourcedown' => 6404, //Platform Default
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        ), array(), 0) as $x) {
            array_push($platform_theme, intval($x['chainsourceup']));
        }

        //Fetch Website Defaults:
        $website_theme = array();
        foreach ($this->Chains->read(array(
            'chainsourceup IN (' . join(',', $this->config->item('sourceids___14926')) . ')' => null, //Website Theme Items
            'chainsourcedown' => website_setting(0), //Website ID
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        ), array(), 0) as $x) {
            array_push($website_theme, intval($x['chainsourceup']));
        }


        //Fetch User Defaults:
        $user_theme = array();
        foreach ($this->Chains->read(array(
            'chainsourcedown' => $e['sourceid'], //This follower Source
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainsourceup'), 0) as $source_up) {

            //Website Theme Items?
            if (in_array($source_up['sourceid'], $this->config->item('sourceids___14926'))) {
                array_push($user_theme, intval($source_up['sourceid']));
            }

            //Superpower?
            if (in_array($source_up['sourceid'], $this->config->item('sourceids___10957'))) {

                //It's unlocked!
                array_push($session_data['session_superpowers_unlocked'], intval($source_up['sourceid']));
            }
        }


        //Determine Defaults if missing any of the CUSTOM UI
        foreach ($this->config->item('sources___13890') as $sourceid => $m) {

            //Set Default:
            $session_data['session_custom_ui_' . $sourceid] = 0;

            //First try to find User Theme, if any:
            if (!$session_data['session_custom_ui_' . $sourceid]) {
                foreach ($this->config->item('sources___' . $sourceid) as $sourceid2 => $m2) {
                    if (in_array($sourceid2, $user_theme)) {
                        $session_data['session_custom_ui_' . $sourceid] = $sourceid2;
                        break;
                    }
                }
            }

            //Then try to find Website Theme, if any:
            if (!$session_data['session_custom_ui_' . $sourceid]) {
                foreach ($this->config->item('sources___' . $sourceid) as $sourceid2 => $m2) {
                    if (in_array($sourceid2, $website_theme)) {
                        $session_data['session_custom_ui_' . $sourceid] = $sourceid2;
                        break;
                    }
                }
            }


            //Finally try Platform Theme:
            if (!$session_data['session_custom_ui_' . $sourceid]) {
                //First try to find Website Default, if any:
                foreach ($this->config->item('sources___' . $sourceid) as $sourceid2 => $m2) {
                    if (in_array($sourceid2, $platform_theme)) {
                        $session_data['session_custom_ui_' . $sourceid] = $sourceid2;
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
            'chainsourceup IN (' . join(',', $this->config->item('sourceids___31057')) . ')' => null, //Permanently Unsubscribed
            'chainsourcedown' => $e['sourceid'], //This follower Source
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                ), array(), 0) as $unsubscribed){
            $unsubscribed_time = $unsubscribed['chaintime'];
            $this->Chains->delete($unsubscribed['chainid'], $e['sourceid']); //Resubscribe
        }
        if($unsubscribed_time){
            //Add to subscribed again:
            $this->Chains->create(array(
                'chainsourcetype' => 4230,
                'chainsourceup' => 4430, //Active Member
                'chainsourcecreator' => $e['sourceid'],
                'chainsourcedown' => $e['sourceid'],
            ));
            $this->session->set_flashdata('flash_message', '<div class="alert alert-info" role="alert"><span class="icon-block"><i class="far fa-user-check"></i></span>Welcome Back! You Have Been Re-Subscribed :)</div>');
        }
        */

        return $session_data;

    }


    function scissor($chainsourceup, $sub_id)
    {

        $all_results = $this->Chains->read(array(
            'chainsourceup' => $chainsourceup,
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
        ), array('chainsourcedown'), 0, 0, source_sort());

        //Remove if not in the secondary group:
        foreach ($all_results as $key => $primary_list) {
            if (!count($this->Chains->read(array(
                'chainsourceup' => $sub_id,
                'chainsourcedown' => $primary_list['sourceid'],
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            ), array(), 0))) {
                unset($all_results[$key]);
            }
        }

        //Return matching results:
        return $all_results;

    }

    function join($full_name, $email = null, $phone_number = null, $image_url = null, $chainsourcedomain = 0)
    {

        //Set website if not set:
        if (!$chainsourcedomain) {
            $chainsourcedomain = website_setting(0);
        }

        //All good, create new Source:
        $new_private_users = in_array($chainsourcedomain, $this->config->item('sourceids___44011'));
        $added_e = $this->Sources->create(array(
            'sourcevalue' => $full_name,
            'sourcecover' => ($image_url ? $image_url : sourcecover_generator(12279)),
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
                'chainsourcetype' => 4230,
                'chainvalue' => trim(strtolower($email)),
                'chainsourceup' => 3288, //Email
                'chainsourcecreator' => $added_e['source_create']['sourceid'],
                'chainsourcedown' => $added_e['source_create']['sourceid'],
                'chainsourcedomain' => $chainsourcedomain,
            ));
        }

        //Add Number?
        if ($phone_number) {
            $this->Chains->create(array(
                'chainsourceup' => 4783, //Phone
                'chainsourcetype' => 4230,
                'chainvalue' => $phone_number,
                'chainsourcecreator' => $added_e['source_create']['sourceid'],
                'chainsourcedown' => $added_e['source_create']['sourceid'],
                'chainsourcedomain' => $chainsourcedomain,
            ));
        }

        if ($email || $phone_number) {

            //Remove from Anonymous:
            foreach ($this->Chains->read(array(
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                'chainsourceup IN (' . join(',', $this->config->item('sourceids___32540')) . ')' => null, //Unsubscribers
                'chainsourcedown' => $added_e['source_create']['sourceid'],
            )) as $unsubscriber_x) {
                $this->Chains->delete($unsubscriber_x['chainid'], $added_e['source_create']['sourceid']);
            }

            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

            //Add to Subscriber:
            $this->Chains->create(array(
                'chainsourceup' => 4430, //Subscriber
                'chainsourcetype' => 4230,
                'chainsourcecreator' => $added_e['source_create']['sourceid'],
                'chainsourcedown' => $added_e['source_create']['sourceid'],
                'chainsourcedomain' => $chainsourcedomain,
            ));

        } else {

            //Add to anonymous:
            $this->Chains->create(array(
                'chainsourceup' => 14938, //Guest Login
                'chainsourcetype' => 4230,
                'chainsourcecreator' => $added_e['source_create']['sourceid'],
                'chainsourcedown' => $added_e['source_create']['sourceid'],
                'chainsourcedomain' => $chainsourcedomain,
            ));

            //Assign session key:
            $session_data = $this->session->all_userdata();
            $this->session->set_userdata($session_data);

        }

        //Add if chain not already there:
        if (!count($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
            'chainsourceup' => $chainsourcedomain,
            'chainsourcedown' => $added_e['source_create']['sourceid'],
        )))) {
            $this->Chains->create(array(
                'chainsourcecreator' => $added_e['source_create']['sourceid'], //Belongs to this Member
                'chainsourcetype' => 4230,
                'chainsourceup' => $chainsourcedomain,
                'chainsourcedown' => $added_e['source_create']['sourceid'],
            ));
        }

        //Send Welcome Email if any:
        if ($email) {
            foreach ($this->Chains->read(array(
                'chainsourcetype' => 33600, //Draft
                'chainsourceup' => 14929, //Website Welcome Email Templates
            ), array('chainidearight'), 0) as $i) {
                if (count($this->Chains->read(array(
                    'chainsourcetype' => 33600, //Draft
                    'chainsourceup' => $chainsourcedomain, //for Current website
                    'chainidearight' => $i['ideaid'], //Is this the template?
                )))) {
                    //Found the email template to send:
                    $total_sent = $this->Chains->broadcast(array($added_e['source_create']), $i, $chainsourcedomain);
                    break; //Just the first template match
                }
            }
        }

        //Update Search Index:
        update_algolia(12274, $added_e['source_create']['sourceid']);

        //Assign session & log login Chain:
        $this->Sources->activate($added_e['source_create']);


        //Return Member:
        return array(
            'status' => 1,
            'e' => $added_e['source_create'],
        );

    }

    function tree($chainsourcetype, $sourceid, $include_any_e = array(), $exclude_all_e = array(), $hard_level = 3, $hard_limit = 100, $s__level = 0)
    {

        $flat_items = array();
        $s__level++;

        if (in_array($chainsourcetype, $this->config->item('sourceids___42276'))) {

            //Up Source Chain Groups:
            $order_columns = source_sort();
            $joins_objects = array('chainsourceup');
            $query_filters = array(
                'chainsourcedown' => $sourceid,
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___' . $chainsourcetype)) . ')' => null, //SOURCE CHAINS
            );

        } elseif (in_array($chainsourcetype, $this->config->item('sourceids___42377'))) {

            //Down Source Chain Groups:
            $order_columns = source_sort();
            $joins_objects = array('chainsourcedown');
            $query_filters = array(
                'chainsourceup' => $sourceid,
                'chainsourcetype IN (' . join(',', $this->config->item('sourceids___' . $chainsourcetype)) . ')' => null, //SOURCE CHAINS
            );

        } else {

            return false;

        }


        foreach ($this->Chains->read($query_filters, $joins_objects, 0, 0, $order_columns) as $source_down) {

            //Filter Sources, if needed:
            $qualified_e = true;
            if (count($include_any_e) && !count($this->Chains->read(array(
                    'chainsourceup IN (' . join(',', $include_any_e) . ')' => null,
                    'chainsourcedown' => $source_down['sourceid'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                )))) {
                //Must include all Sources, skip:
                $qualified_e = false;
            }
            if (count($exclude_all_e) && count($this->Chains->read(array(
                    'chainsourceup IN (' . join(',', $exclude_all_e) . ')' => null,
                    'chainsourcedown' => $source_down['sourceid'],
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___13548')) . ')' => null, //SOURCE CHAINS
                )))) {
                //Must IF Not Follows All Sources, skip:
                $qualified_e = false;
            }


            //Is this a new matching Source?
            if ($qualified_e && !isset($flat_items[$source_down['sourceid']])) {
                $source_down['s__level'] = $s__level;
                $source_down['s__count'] = count($flat_items) + 1;
                $flat_items[$source_down['sourceid']] = $source_down;
            }

            //Do we have more followers?
            if ($s__level >= $hard_level || count($flat_items) >= $hard_limit) {
                break;
            }

            foreach ($this->Sources->tree($chainsourcetype, $source_down['sourceid'], $include_any_e, $exclude_all_e, $hard_level, $hard_limit, $s__level) as $source_recursive_down) {
                if (!isset($flat_items[$source_recursive_down['sourceid']])) {
                    $source_recursive_down['s__count'] = count($flat_items) + 1;
                    $flat_items[$source_recursive_down['sourceid']] = $source_recursive_down;
                }
            }
        }

        return $flat_items;
    }


}