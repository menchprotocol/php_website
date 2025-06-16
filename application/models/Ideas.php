<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ideas extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainsourcecreator = 14068 /* GUEST */)
    {

        $nextchainid = nextchainid();
        $creation_data = array(
            'chainsourcetype' => 12273,
            'chainsourcecreator' => $chainsourcecreator,
            'chainsourceup' => $chainsourcecreator,
            'chainidearight' => $nextchainid,
            'chainvalue' => (isset($add_fields['ideavalue']) ? $add_fields['ideavalue'] : null),
        );

        if (isset($add_fields['ideaid']) && !count($this->Chains->read(array('chainid' => $add_fields['ideaid'])))) {
            //Set the chain ID since its not in the ledger:
            $creation_data['chainid'] = $add_fields['ideaid'];
        }

        //Add if not added as the author:
        $new_x = $this->Chains->create($creation_data);

        if (!$new_x['chainid']) {
            return false;
        } elseif($nextchainid!=$new_x['chainid']) {
            //Something went wrong, update:
            $this->Chains->update($new_x['chainid'], array(
                'chainidearight' => $new_x['chainid'],
            ));
        }

        //Save hashtag
        if (!isset($add_fields['ideahashtag'])) {
            $add_fields['ideahashtag'] = random_string(8);
        }
        $this->Chains->create(array(
            'chainsourcetype' => 42275, //Idea Trigger
            'chainsourceup' => 32337, //Idea Hashtag
            'chainsourcecreator' => $chainsourcecreator,
            'chainidearight' => $new_x['chainid'],
            'chainvalue' => $add_fields['ideahashtag'],
        ));

        //Save Idea
        $add_fields['ideaid'] = $new_x['chainid'];
        $add_fields['ideacache'] = ideacache($add_fields['ideaid'], $add_fields['ideavalue']);
        if (!count($this->Ideas->read(array('ideaid' => $add_fields['ideaid'])))) {
            $this->db->insert('cacheideas', $add_fields);
        }

        //Update Search Index:
        update_algolia(12273, $add_fields['ideaid']);

        //Additional Sources to be added? Start with creator
        $source_appended = array($chainsourcecreator);
        $pinned_followers = $this->Chains->read(array(
            'chainsourceup' => $chainsourcecreator,
            'chainsourcetype' => 41011, //PINNED FOLLOWER
        ), array('chainsourcedown'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC'));

        //Also append all pinned followers:
        $chainkey = 0;
        foreach ($pinned_followers as $x_pinned) {
            if (!in_array($x_pinned['sourceid'], $source_appended) && !count($this->Chains->read(array(
                    'chainsourcetype' => 4983, //Idea Created
                    'chainsourceup' => $x_pinned['sourceid'],
                    'chainidearight' => $add_fields['ideaid'],
                )))) {
                $this->Chains->create(array(
                    'chainsourcetype' => 4983, //Idea Created
                    'chainsourceup' => $x_pinned['sourceid'],
                    'chainidearight' => $add_fields['ideaid'],
                    'chainsourcecreator' => $chainsourcecreator,
                    'chainkey' => $chainkey,
                ));
                array_push($source_appended, $x_pinned['sourceid']);
                $chainkey++;
            }
        }

        //Fetch to return the complete Idea
        $is = $this->Ideas->read(array(
            'ideaid' => $add_fields['ideaid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'idea_create' => $is[0],
        );

    }


    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Ideas
        $this->db->select($select);
        $this->db->from('cacheideas');

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
                if (!idea_access($value['ideahashtag'], 0, $value)) {
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

        $ideas_found = $this->Ideas->read(array('ideaid' => $chainid));
        if (!count($ideas_found)) {
            log_error('Idea #' . $chainid . ' not found in Ideas table');
            return false;
        } elseif (!count($this->Chains->read(array('chainid' => $chainid)))) {
            log_error('Idea #' . $chainid . ' not found in Chains table');
            return false;
        }

        $affected_rows = 0;
        foreach ($ideas_found as $idea_current) {

            $must_sync_found = false;
            $skip_sync_ledger = array('ideacache', 'ideaexternal', 'ideakey', 'ideatype');
            $must_sync_ledger = array(
                'ideavalue' => 4736, //Idea Text
                'ideahashtag' => 32337,
            );

            //See what is being updated:
            foreach ($update_columns as $key => $value) {
                if (array_key_exists($key, $must_sync_ledger)) {
                    //Update if anything changed:
                    if ($value != $idea_current[$key]) {
                        $this->Chains->create(array(
                            'chainsourcetype' => 42275, //Idea Trigger
                            'chainsourceup' => $must_sync_ledger[$key],
                            'chainsourcecreator' => $chainsourcecreator,
                            'chainidearight' => $chainid,
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

            if (isset($update_columns['ideavalue']) && !isset($update_columns['ideacache'])) {
                //Update Idea Text:
                $update_columns['ideacache'] = ideacache($chainid, $update_columns['ideavalue']);
            }

            if (!count($update_columns)) {
                return false;
            }

            //Update:
            $this->db->where('ideaid', $chainid);
            $this->db->update('cacheideas', $update_columns);
            $affected_rows = $this->db->affected_rows();

            if ($must_sync_found) {
                //Sync algolia:
                update_algolia(12273, $chainid);
            }

        }

        return $affected_rows;

    }

    function delete($ideaid, $chainsourcecreator = 0, $migrateid = 0)
    {

        if (!count($this->Ideas->read(array('ideaid' => $ideaid)))) {
            return array(
                'status' => 0,
                'message' => $ideaid . ' is not a valid ID',
            );
        } elseif ($migrateid > 0 && !count($this->Ideas->read(array('ideaid' => $migrateid)))) {
            return array(
                'status' => 0,
                'message' => $migrateid . ' is not a valid ID',
            );
        }

        $x_adjusted = 0;
        foreach ($this->Chains->read(array(
            '(chainid = ' . $ideaid . ' OR chainidearight = ' . $ideaid . ' OR chainidealeft = ' . $ideaid . ')' => null,
        ), array(), 0) as $migrate) {

            if ($migrateid && $migrate['chainid'] != $ideaid) {
                $new_array = array(
                    'chainidealeft' => ($migrate['chainidealeft'] == $ideaid ? $migrateid : $migrate['chainidealeft']),
                    'chainidearight' => ($migrate['chainidearight'] == $ideaid ? $migrateid : $migrate['chainidearight']),
                    'chainsourcecreator' => $migrate['chainsourcecreator'],
                    'chainsourcedown' => $migrate['chainsourcedown'],
                    'chainsourceup' => $migrate['chainsourceup'],
                    'chainsourcetype' => $migrate['chainsourcetype'],
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
            $this->db->query("DELETE FROM cacheideas WHERE ideaid = " . $ideaid . ";");

            //Update Search Index?
            update_algolia(12273, $ideaid);
        } else {
            //Failed to remove
            log_error('ideas->delete() Failed to remove #' . $ideaid . ' Chain ID', array(
                'chainsourcecreator' => $chainsourcecreator,
                'chainidearight' => $ideaid,
                'chainidealeft' => $migrateid,
            ));
        }

        //Return Chains deleted:
        return $x_adjusted;
    }


    function command($ideaid, $action_sourceid, $action_command1, $action_command2, $chainsourcecreator)
    {


        boost_power();

        if (!in_array($action_sourceid, $this->config->item('sourceids___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif (in_array($action_sourceid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && !view_valid_handle_source($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @SourceHandle',
            );

        } elseif (in_array($action_sourceid, array(12611, 12612, 27240, 28801)) && !view_valid_handle_idea($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #IdeaHashtag',
            );

        }


        //Basic input validation done, let's continue


        //Fetch all followers:
        $applied_success = 0; //To be populated

        $is_next = $this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //Active Sequence Down
            'chainidealeft' => $ideaid,
        ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC'));


        //Process request:
        foreach ($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if (in_array($action_sourceid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && view_valid_handle_source($action_command1)) {

                //Check if it has this item:
                foreach ($this->Sources->read(array(
                    'LOWER(sourcehandle)' => strtolower(view_valid_handle_source($action_command1)),
                )) as $e) {

                    $idea_has_e = $this->Chains->read(array(
                        'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33602')) . ')' => null, //Idea/Source Chains Active
                        'chainidearight' => $next_i['ideaid'],
                        'chainsourceup' => $e['sourceid'],
                    ));

                    if (in_array($action_sourceid, array(12591, 27080, 27985, 27082, 27084, 27086)) && !count($idea_has_e)) {

                        $source_mapper = array(
                            12591 => 4983,  //Co-Author
                            27985 => 27984, //IF Follows Any
                            27082 => 26600, //IF Not Follows All
                            27084 => 7545,  //Following Add
                            27086 => 26599, //Following Remove
                        );

                        //Missing & Must be Added:
                        $this->Chains->create(array(
                            'chainsourcecreator' => $chainsourcecreator,
                            'chainsourceup' => $e['sourceid'],
                            'chainsourcetype' => $source_mapper[$action_sourceid],
                            'chainidearight' => $next_i['ideaid'],
                            'chainvalue' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif (in_array($action_sourceid, array(12592, 27081, 27986, 27083, 27085, 27087)) && count($idea_has_e)) {

                        //Has and must be deleted:
                        $this->Chains->delete($idea_has_e[0]['chainid'], $chainsourcecreator);

                        $applied_success++;

                    }
                }

            } elseif (in_array($action_sourceid, array(12611, 12612, 27240, 28801)) && view_valid_handle_idea($action_command1)) {

                foreach ($this->Ideas->read(array(
                    'LOWER(ideahashtag)' => strtolower(view_valid_handle_idea($action_command1)),
                )) as $i) {

                    if ($action_sourceid == 27240) {

                        //Copy
                        $result = $this->Ideas->copy(intval($_POST['ideaid']), 0, $action_sourceid);
                        if ($result['status']) {
                            //Increment Source since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->Chains->read(array(
                            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42345')) . ')' => null, //Active Sequence 2-Ways
                            'chainidealeft' => $i['ideaid'],
                            'chainidearight' => $next_i['ideaid'],
                        ), array(), 0);


                        //See how to adjust:
                        if (in_array($action_sourceid, array(12611, 28801)) && !count($is_previous)) {

                            //Chain
                            $status = $this->Ideas->chain($i, 4228, $next_i, $chainsourcecreator);

                            if ($status['status']) {

                                if ($action_sourceid == 28801) {
                                    //Also remove old chain:
                                    $this->Chains->delete($next_i['chainid'], $chainsourcecreator);
                                }

                                //Increment Source since not there:
                                $applied_success++;
                            }
                        }


                        if ($action_sourceid == 12612 && count($is_previous)) {
                            //Unchain
                            $this->Chains->delete($is_previous[0]['chainid'], $chainsourcecreator);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass Source edit transaction:
        $this->Chains->create(array(
            'chainsourcetype' => 44179, //Triggered
            'chainsourceup' => $action_sourceid,
            'chainsourcedown' => $chainsourcecreator,
            'chainsourcecreator' => $chainsourcecreator,
            'chainidearight' => $ideaid,
            'chainvalue' => array(
                'payload' => $_POST,
                'idea_total' => count($is_next),
                'idea_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($is_next) . ' ideas updated',
        );

    }


    function chain($i, $chainsourcetype, $next_i, $chainsourcecreator)
    {

        //Chains ideas with the causality chain ensuring not a duplicate:
        if (0 && $chainsourcetype == 4228 && count($this->Chains->previousidea(0, $next_i['ideahashtag'], $i['ideaid']))) {
            return array(
                'status' => 0,
                'message' => 'Idea already added in the inverse direction, so it cannot be added here',
            );
        } elseif (count($this->Chains->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainsourcetype' => $chainsourcetype,
            'chainidearight' => $next_i['ideaid'],
        )))) {
            //Make sure not a duplicate chain:
            return array(
                'status' => 0,
                'message' => 'Idea is already chained here',
            );
        }

        //Adding PREVIOUS or NEXT Idea from Idea
        $this->Chains->create(array(
            'chainsourcecreator' => $chainsourcecreator,
            'chainidealeft' => $i['ideaid'],
            'chainsourcetype' => $chainsourcetype,
            'chainidearight' => $next_i['ideaid'],
        ), true);

        //Return result:
        return array(
            'status' => 1,
        );
    }


    function ids($i, $scope, $loop_breaker_ids = array())
    {

        if (!($scope == 'ALL' /* includes both AND and OR ideas */ || $scope == 'AND' /* AND ideas only */ || $scope == 'OR' /* OR ideas only */)) {
            return false;
        }

        if (count($loop_breaker_ids) > 0 && in_array($i['ideaid'], $loop_breaker_ids)) {
            return array();
        }

        $input__selection = in_array($i['ideatype'], $this->config->item('sourceids___7712'));
        if ($scope == 'AND' && $input__selection) {
            //OR IDEA:
            return array();
        }

        $recursive_idea_ids = array();
        array_push($loop_breaker_ids, intval($i['ideaid']));

        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___42267')) . ')' => null, //Active Sequence Down
            'chainidealeft' => $i['ideaid'],
        ), array('chainidearight'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {

            if (!in_array(intval($next_i['ideaid']), $recursive_idea_ids)) {
                if (!($scope == 'OR' && !$input__selection)) {
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_idea_ids, intval($next_i['ideaid']));
                }
            }

            //Add to current array if we found anything:
            $copy = $this->Ideas->ids($next_i, $scope, $loop_breaker_ids);
            if (isset($copy['recursive_idea_ids'])) {
                foreach ($copy['recursive_idea_ids'] as $recursive_idea_id) {
                    if (!in_array($recursive_idea_id, $recursive_idea_ids)) {
                        array_push($recursive_idea_ids, $recursive_idea_id);
                    }
                }
            }


        }

        return array(
            'recursive_idea_ids' => array_unique($recursive_idea_ids),
        );

    }

    function copy($ideaid, $do_recursive, $chainsourcecreator, $previous_i = null, $clone_title = null)
    {

        //Create Clone -or- Chain & move-on?
        //Validate Idea:
        $this_i = $this->Ideas->read(array(
            'ideaid' => $ideaid,
        ));
        if (count($this_i) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
                'idea_createid' => 0,
                'idea_createhashtag' => '',
            );
        }

        $idea_new = $this->Ideas->create(array(
            'ideavalue' => ($clone_title ? $clone_title : "Copy Of " . $this_i[0]['ideavalue']),
            'ideatype' => $this_i[0]['ideatype'],
        ), $chainsourcecreator);

        //Always Chain Sources:
        $filters = array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___41302')) . ')' => null, //Clone Idea Source Chains
            'chainidearight' => $ideaid,
        );

        foreach ($this->Chains->read($filters, array(), 0) as $x) {
            $this->Chains->create(array(
                'chainsourcecreator' => $chainsourcecreator,
                'chainsourcetype' => $x['chainsourcetype'],
                'chainidearight' => $idea_new['idea_create']['ideaid'],
                'chainsourceup' => $x['chainsourceup'],
                'chainsourcedown' => $x['chainsourcedown'],
                'chainidealeft' => $x['chainidealeft'],
                'chainvalue' => $x['chainvalue'],
                'chainkey' => $x['chainkey'],
            ));
        }


        //Always Chain Followings:
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___41301')) . ')' => null, //Duplicate Chains
            'chainidearight' => $ideaid,
        ), array(), 0) as $x) {
            $this->Chains->create(array(
                'chainsourcecreator' => $chainsourcecreator,
                'chainsourcetype' => $x['chainsourcetype'],
                'chainidearight' => $idea_new['idea_create']['ideaid'],
                'chainidealeft' => $x['chainidealeft'],
                'chainvalue' => $x['chainvalue'],
                'chainkey' => $x['chainkey'],
            ));
        }


        //Fetch followers:
        foreach ($this->Chains->read(array(
            'chainsourcetype IN (' . join(',', $this->config->item('sourceids___41301')) . ')' => null, //Duplicate Chains
            'chainidealeft' => $ideaid,
        ), array('chainidearight'), 0) as $x) {

            if ($do_recursive && !count($this->Chains->read(array(
                    'chainsourcetype IN (' . join(',', $this->config->item('sourceids___33602')) . ')' => null, //Idea/Source Chains Active
                    'chainidearight' => $ideaid,
                    'chainsourceup' => 42208, //No-Clone Idea
                )))) {
                //Clone Followers Recursively:
                $this->Ideas->copy($x['ideaid'], $do_recursive, $chainsourcecreator, $this_i[0]);
            } else {
                //Chain Followers:
                $this->Chains->create(array(
                    'chainsourcecreator' => $chainsourcecreator,
                    'chainsourcetype' => $x['chainsourcetype'],
                    'chainidealeft' => $idea_new['idea_create']['ideaid'],
                    'chainidearight' => $x['ideaid'],
                    'chainvalue' => $x['chainvalue'],
                    'chainkey' => $x['chainkey'],
                ));
            }
        }

        return array(
            'status' => 1,
            'idea_createid' => $idea_new['idea_create']['ideaid'],
            'idea_createhashtag' => $idea_new['idea_create']['ideahashtag'],
        );

    }


}