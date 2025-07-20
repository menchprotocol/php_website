<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hashtags extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainhandlecreator = 14068 /* GUEST */)
    {

        if(!isset($add_fields['hashtagvalue'])){
            return false;
        }

        $nextchainid = nextchainid();
        $creation_data = array(
            'chainhandletype' => 12273,
            'chainhandlecreator' => $chainhandlecreator,
            'chainhandleinput' => $chainhandlecreator,
            'chainhashtagoutput' => $nextchainid,
            'chainvalue' => $add_fields['hashtagvalue'],
        );

        if (isset($add_fields['hashtagid']) && !count($this->Chains->read(array('chainid' => $add_fields['hashtagid'])))) {
            //Set the chain ID since its not in the ledger:
            $creation_data['chainid'] = $add_fields['hashtagid'];
        }

        //Add if not added as the author:
        $new_x = $this->Chains->create($creation_data);

        if (!$new_x['chainid']) {
            return false;
        } elseif($nextchainid!=$new_x['chainid']) {
            //Something went wrong, update:
            $this->Chains->update($new_x['chainid'], array(
                'chainhashtagoutput' => $new_x['chainid'],
            ));
        }

        //Save hashtag
        if (!isset($add_fields['hashtaghashtag'])) {
            $add_fields['hashtaghashtag'] = random_string(8);
        }

        //Save Hashtag
        $add_fields['hashtagid'] = $new_x['chainid'];
        //$add_fields['hashtagvalue'] = '#'.$add_fields['hashtaghashtag']."\n".$add_fields['hashtagvalue'];
        $add_fields['hashtagread'] = hashtagread($add_fields['hashtagid'], $add_fields['hashtagvalue']);
        if (!count($this->Hashtags->read(array('hashtagid' => $add_fields['hashtagid'])))) {
            $this->db->insert('ideachainhashtags', $add_fields);
        }


        //Update Search Index:
        update_algolia(12273, $add_fields['hashtagid']);

        //Additional Handles to be added? Start with creator
        $handle_appended = array($chainhandlecreator);
        $pinned_followers = $this->Chains->read(array(
            'chainhandleinput' => $chainhandlecreator,
            'chainhandletype' => 41011, //PINNED FOLLOWER
        ), array('chainhandleoutput'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC'));

        //Also append all pinned followers:
        $chainkey = 0;
        foreach ($pinned_followers as $x_pinned) {
            if (!in_array($x_pinned['handleid'], $handle_appended) && !count($this->Chains->read(array(
                    'chainhandletype' => 4983, //Hashtag Created
                    'chainhandleinput' => $x_pinned['handleid'],
                    'chainhashtagoutput' => $add_fields['hashtagid'],
                )))) {
                $this->Chains->create(array(
                    'chainhandletype' => 4983, //Hashtag Created
                    'chainhandleinput' => $x_pinned['handleid'],
                    'chainhashtagoutput' => $add_fields['hashtagid'],
                    'chainhandlecreator' => $chainhandlecreator,
                    'chainkey' => $chainkey,
                ));
                array_push($handle_appended, $x_pinned['handleid']);
                $chainkey++;
            }
        }

        //Fetch to return the complete Hashtag
        $is = $this->Hashtags->read(array(
            'hashtagid' => $add_fields['hashtagid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'hashtag_create' => $is[0],
        );

    }


    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Hashtags
        $this->db->select($select);
        $this->db->from('ideachainhashtags');

        foreach ($query_filters as $key => $value) {
            $this->db->where($key, $value);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }
        if (count($order_columns) > 0) {
            foreach ($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        $results = $q->result_array();

        //Make sure user has access to each item:
        if ($select == '*' && 0) {
            foreach ($results as $key => $value) {
                if (!hashtag_access($value['hashtaghashtag'], 0, $value)) {
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

        $hashtags_found = $this->Hashtags->read(array('hashtagid' => $chainid));
        if (!count($hashtags_found)) {
            log_error('Hashtag #' . $chainid . ' not found in Hashtags table');
            return false;
        } elseif (!count($this->Chains->read(array('chainid' => $chainid)))) {
            log_error('Hashtag #' . $chainid . ' not found in Chains table');
            return false;
        }

        $affected_rows = 0;
        foreach ($hashtags_found as $hashtag_current) {

            $must_sync_found = false;
            $skip_sync_ledger = array('hashtagread', 'hashtagexternal', 'hashtagkey', 'hashtagtype');
            $must_sync_ledger = array(
                'hashtagvalue' => 4736, //Hashtag Text
                'hashtaghashtag' => 32337,
            );

            //See what is being updated:
            foreach ($update_columns as $key => $value) {
                if (array_key_exists($key, $must_sync_ledger)) {
                    //Update if anything changed:
                    if ($value != $hashtag_current[$key]) {
                        $this->Chains->create(array(
                            'chainhandletype' => 42275, //Hashtag Trigger
                            'chainhandleinput' => $must_sync_ledger[$key],
                            'chainhandlecreator' => $chainhandlecreator,
                            'chainhashtagoutput' => $chainid,
                            'chainvalue' => $value,
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

            if (isset($update_columns['hashtagvalue']) && !isset($update_columns['hashtagread'])) {
                //Update Hashtag Text:
                $update_columns['hashtagread'] = hashtagread($chainid, $update_columns['hashtagvalue']);
            }

            if (!count($update_columns)) {
                return false;
            }

            //Update:
            $this->db->where('hashtagid', $chainid);
            $this->db->update('ideachainhashtags', $update_columns);
            $affected_rows = $this->db->affected_rows();

            if ($must_sync_found) {
                //Sync algolia:
                update_algolia(12273, $chainid);
            }

        }

        return $affected_rows;

    }

    function delete($hashtagid, $chainhandlecreator = 0, $migrateid = 0)
    {

        if (!count($this->Hashtags->read(array('hashtagid' => $hashtagid)))) {
            return array(
                'status' => 0,
                'message' => $hashtagid . ' is not a valid ID',
            );
        } elseif ($migrateid > 0 && !count($this->Hashtags->read(array('hashtagid' => $migrateid)))) {
            return array(
                'status' => 0,
                'message' => $migrateid . ' is not a valid ID',
            );
        }

        $x_adjusted = 0;
        foreach ($this->Chains->read(array(
            '(chainid = ' . $hashtagid . ' OR chainhashtagoutput = ' . $hashtagid . ' OR chainhashtaginput = ' . $hashtagid . ')' => null,
        ), array(), 0) as $migrate) {

            if ($migrateid && $migrate['chainid'] != $hashtagid) {
                $new_array = array(
                    'chainhashtaginput' => ($migrate['chainhashtaginput'] == $hashtagid ? $migrateid : $migrate['chainhashtaginput']),
                    'chainhashtagoutput' => ($migrate['chainhashtagoutput'] == $hashtagid ? $migrateid : $migrate['chainhashtagoutput']),
                    'chainhandlecreator' => $migrate['chainhandlecreator'],
                    'chainhandleoutput' => $migrate['chainhandleoutput'],
                    'chainhandleinput' => $migrate['chainhandleinput'],
                    'chainhandletype' => $migrate['chainhandletype'],
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
            $this->db->query("DELETE FROM ideachainhashtags WHERE hashtagid = " . $hashtagid . ";");

            //Update Search Index?
            update_algolia(12273, $hashtagid);
        } else {
            //Failed to remove
            log_error('hashtags->delete() Failed to remove #' . $hashtagid . ' Chain ID', array(
                'chainhandlecreator' => $chainhandlecreator,
                'chainhashtagoutput' => $hashtagid,
                'chainhashtaginput' => $migrateid,
            ));
        }

        //Return Chains deleted:
        return $x_adjusted;
    }


    function command($hashtagid, $action_handleid, $action_command1, $action_command2, $chainhandlecreator)
    {


        boost_power();

        if (!in_array($action_handleid, $this->config->item('handleids___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif (in_array($action_handleid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && !view_valid_handle_handle($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Handle. Format must be: @HandleHandle',
            );

        } elseif (in_array($action_handleid, array(12611, 12612, 27240, 28801)) && !view_valid_handle_hashtag($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Hashtag. Format must be: #HashtagHashtag',
            );

        }


        //Basic input validation done, let's continue


        //Fetch all followers:
        $applied_success = 0; //To be populated

        $is_next = $this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
            'chainhashtaginput' => $hashtagid,
        ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC'));


        //Process request:
        foreach ($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if (in_array($action_handleid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && view_valid_handle_handle($action_command1)) {

                //Check if it has this item:
                foreach ($this->Handles->read(array(
                    'LOWER(handlehandle)' => strtolower(view_valid_handle_handle($action_command1)),
                )) as $e) {

                    $hashtag_has_e = $this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                        'chainhashtagoutput' => $next_i['hashtagid'],
                        'chainhandleinput' => $e['handleid'],
                    ));

                    if (in_array($action_handleid, array(12591, 27080, 27985, 27082, 27084, 27086)) && !count($hashtag_has_e)) {

                        $handle_mapper = array(
                            12591 => 4983,  //Co-Author
                            27985 => 27984, //IF Follows Any
                            27082 => 26600, //IF Not Follows All
                            27084 => 7545,  //Following Add
                            27086 => 26599, //Following Remove
                        );

                        //Missing & Must be Added:
                        $this->Chains->create(array(
                            'chainhandlecreator' => $chainhandlecreator,
                            'chainhandleinput' => $e['handleid'],
                            'chainhandletype' => $handle_mapper[$action_handleid],
                            'chainhashtagoutput' => $next_i['hashtagid'],
                            'chainvalue' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif (in_array($action_handleid, array(12592, 27081, 27986, 27083, 27085, 27087)) && count($hashtag_has_e)) {

                        //Has and must be deleted:
                        $this->Chains->delete($hashtag_has_e[0]['chainid'], $chainhandlecreator);

                        $applied_success++;

                    }
                }

            } elseif (in_array($action_handleid, array(12611, 12612, 27240, 28801)) && view_valid_handle_hashtag($action_command1)) {

                foreach ($this->Hashtags->read(array(
                    'LOWER(hashtaghashtag)' => strtolower(view_valid_handle_hashtag($action_command1)),
                )) as $i) {

                    if ($action_handleid == 27240) {

                        //Copy
                        $result = $this->Hashtags->copy(intval($_POST['hashtagid']), 0, $action_handleid);
                        if ($result['status']) {
                            //Increment Handle since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->Chains->read(array(
                            'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
                            'chainhashtaginput' => $i['hashtagid'],
                            'chainhashtagoutput' => $next_i['hashtagid'],
                        ), array(), 0);


                        //See how to adjust:
                        if (in_array($action_handleid, array(12611, 28801)) && !count($is_previous)) {

                            //Chain
                            $status = $this->Hashtags->chain($i, 4228, $next_i, $chainhandlecreator);

                            if ($status['status']) {

                                if ($action_handleid == 28801) {
                                    //Also remove old chain:
                                    $this->Chains->delete($next_i['chainid'], $chainhandlecreator);
                                }

                                //Increment Handle since not there:
                                $applied_success++;
                            }
                        }


                        if ($action_handleid == 12612 && count($is_previous)) {
                            //Unchain
                            $this->Chains->delete($is_previous[0]['chainid'], $chainhandlecreator);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass Handle edit transaction:
        $this->Chains->create(array(
            'chainhandletype' => 44179, //Triggered
            'chainhandleinput' => $action_handleid,
            'chainhandleoutput' => $chainhandlecreator,
            'chainhandlecreator' => $chainhandlecreator,
            'chainhashtagoutput' => $hashtagid,
            'chainvalue' => array(
                'payload' => $_POST,
                'hashtag_total' => count($is_next),
                'hashtag_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($is_next) . ' hashtags updated',
        );

    }


    function chain($i, $chainhandletype, $next_i, $chainhandlecreator)
    {

        //Chains hashtags with the causality chain ensuring not a duplicate:
        if (0 && $chainhandletype == 4228 && count($this->Chains->previoushashtag(0, $next_i['hashtaghashtag'], $i['hashtagid']))) {
            return array(
                'status' => 0,
                'message' => 'Hashtag already added in the inverse direction, so it cannot be added here',
            );
        } elseif (count($this->Chains->read(array(
            'chainhashtaginput' => $i['hashtagid'],
            'chainhandletype' => $chainhandletype,
            'chainhashtagoutput' => $next_i['hashtagid'],
        )))) {
            //Make sure not a duplicate chain:
            return array(
                'status' => 0,
                'message' => 'Hashtag is already chained here',
            );
        }

        //Adding PREVIOUS or NEXT Hashtag from Hashtag
        $this->Chains->create(array(
            'chainhandlecreator' => $chainhandlecreator,
            'chainhashtaginput' => $i['hashtagid'],
            'chainhandletype' => $chainhandletype,
            'chainhashtagoutput' => $next_i['hashtagid'],
        ), true);

        //Return result:
        return array(
            'status' => 1,
        );
    }


    function ids($i, $scope, $loop_breaker_ids = array())
    {

        if (!($scope == 'ALL' /* includes both AND and OR hashtags */ || $scope == 'AND' /* AND hashtags only */ || $scope == 'OR' /* OR hashtags only */)) {
            return false;
        }

        if (count($loop_breaker_ids) > 0 && in_array($i['hashtagid'], $loop_breaker_ids)) {
            return array();
        }

        $input__selection = in_array($i['hashtagtype'], $this->config->item('handleids___7712'));
        if ($scope == 'AND' && $input__selection) {
            //OR HASHTAG:
            return array();
        }

        $recursive_hashtag_ids = array();
        array_push($loop_breaker_ids, intval($i['hashtagid']));

        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___42345')) . ')' => null, //Active Sequence
            'chainhashtaginput' => $i['hashtagid'],
        ), array('chainhashtagoutput'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {

            if (!in_array(intval($next_i['hashtagid']), $recursive_hashtag_ids)) {
                if (!($scope == 'OR' && !$input__selection)) {
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_hashtag_ids, intval($next_i['hashtagid']));
                }
            }

            //Add to current array if we found anything:
            $copy = $this->Hashtags->ids($next_i, $scope, $loop_breaker_ids);
            if (isset($copy['recursive_hashtag_ids'])) {
                foreach ($copy['recursive_hashtag_ids'] as $recursive_hashtag_id) {
                    if (!in_array($recursive_hashtag_id, $recursive_hashtag_ids)) {
                        array_push($recursive_hashtag_ids, $recursive_hashtag_id);
                    }
                }
            }


        }

        return array(
            'recursive_hashtag_ids' => array_unique($recursive_hashtag_ids),
        );

    }

    function copy($hashtagid, $do_recursive, $chainhandlecreator, $previous_i = null, $clone_title = null)
    {

        //Create Clone -or- Chain & move-on?
        //Validate Hashtag:
        $this_i = $this->Hashtags->read(array(
            'hashtagid' => $hashtagid,
        ));
        if (count($this_i) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid hashtag ID',
                'hashtag_createid' => 0,
                'hashtag_createhashtag' => '',
            );
        }

        $hashtag_new = $this->Hashtags->create(array(
            'hashtagvalue' => ($clone_title ? $clone_title : "Copy Of " . $this_i[0]['hashtagvalue']),
            'hashtagtype' => $this_i[0]['hashtagtype'],
        ), $chainhandlecreator);

        //Always Chain Handles:
        $filters = array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___41302')) . ')' => null, //Clone Hashtag Handle Chains
            'chainhashtagoutput' => $hashtagid,
        );

        foreach ($this->Chains->read($filters, array(), 0) as $x) {
            $this->Chains->create(array(
                'chainhandlecreator' => $chainhandlecreator,
                'chainhandletype' => $x['chainhandletype'],
                'chainhashtagoutput' => $hashtag_new['hashtag_create']['hashtagid'],
                'chainhandleinput' => $x['chainhandleinput'],
                'chainhandleoutput' => $x['chainhandleoutput'],
                'chainhashtaginput' => $x['chainhashtaginput'],
                'chainvalue' => $x['chainvalue'],
                'chainkey' => $x['chainkey'],
            ));
        }


        //Always Chain Followings:
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___41301')) . ')' => null, //Duplicate Chains
            'chainhashtagoutput' => $hashtagid,
        ), array(), 0) as $x) {
            $this->Chains->create(array(
                'chainhandlecreator' => $chainhandlecreator,
                'chainhandletype' => $x['chainhandletype'],
                'chainhashtagoutput' => $hashtag_new['hashtag_create']['hashtagid'],
                'chainhashtaginput' => $x['chainhashtaginput'],
                'chainvalue' => $x['chainvalue'],
                'chainkey' => $x['chainkey'],
            ));
        }


        //Fetch followers:
        foreach ($this->Chains->read(array(
            'chainhandletype IN (' . join(',', $this->config->item('handleids___41301')) . ')' => null, //Duplicate Chains
            'chainhashtaginput' => $hashtagid,
        ), array('chainhashtagoutput'), 0) as $x) {

            if ($do_recursive && !count($this->Chains->read(array(
                    'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                    'chainhashtagoutput' => $hashtagid,
                    'chainhandleinput' => 42208, //No-Clone Hashtag
                )))) {
                //Clone Followers Recursively:
                $this->Hashtags->copy($x['hashtagid'], $do_recursive, $chainhandlecreator, $this_i[0]);
            } else {
                //Chain Followers:
                $this->Chains->create(array(
                    'chainhandlecreator' => $chainhandlecreator,
                    'chainhandletype' => $x['chainhandletype'],
                    'chainhashtaginput' => $hashtag_new['hashtag_create']['hashtagid'],
                    'chainhashtagoutput' => $x['hashtagid'],
                    'chainvalue' => $x['chainvalue'],
                    'chainkey' => $x['chainkey'],
                ));
            }
        }

        return array(
            'status' => 1,
            'hashtag_createid' => $hashtag_new['hashtag_create']['hashtagid'],
            'hashtag_createhashtag' => $hashtag_new['hashtag_create']['hashtaghashtag'],
        );

    }


}