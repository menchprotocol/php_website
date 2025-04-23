<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ideas extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainplayercreator = 14068 /* GUEST */)
    {

        $creation_data = array(
            'chainplayertype' => 4250,
            'chainplayercreator' => $chainplayercreator,
            'chaintext' => (isset($add_fields['ideatext']) ? $add_fields['ideatext'] : null),
        );
        if (isset($add_fields['ideaid']) && !count($this->Links->read(array('chainid' => $add_fields['ideaid'])))) {
            //Set the link ID since its not in the ledger:
            $creation_data['chainid'] = $add_fields['ideaid'];
        }

        //Add if not added as the author:
        $new_x = $this->Links->create($creation_data);

        if (!$new_x['chainid']) {
            return false;
        }

        //Save hashtag
        if (!isset($add_fields['ideahashtag'])) {
            $add_fields['ideahashtag'] = random_string(8);
        }
        $this->Links->create(array(
            'chainplayertype' => 42275, //Idea Trigger
            'chainplayerup' => 32337, //Idea Hashtag
            'chainplayercreator' => $chainplayercreator,
            'chainidearight' => $new_x['chainid'],
            'chaintext' => $add_fields['ideahashtag'],
        ));

        //Save Idea
        $add_fields['ideaid'] = $new_x['chainid'];
        $add_fields['ideacache'] = ideacache($add_fields['ideaid'], $add_fields['ideatext']);
        if (!count($this->Ideas->read(array('ideaid' => $add_fields['ideaid'])))) {
            $this->db->insert('cacheideas', $add_fields);
        }

        //Update Search Index:
        update_algolia(12273, $add_fields['ideaid']);

        //Additional Players to be added? Start with creator
        $player_appended = array($chainplayercreator);
        $pinned_followers = $this->Links->read(array(
            'chainplayerup' => $chainplayercreator,
            'chainplayertype' => 41011, //PINNED FOLLOWER
        ), array('chainplayerdown'), 0, 0, array('chainnumber' => 'ASC', 'chainid' => 'DESC'));

        //Also append all pinned followers:
        $chainnumber = 0;
        foreach ($pinned_followers as $x_pinned) {
            if (!in_array($x_pinned['playerid'], $player_appended) && !count($this->Links->read(array(
                    'chainplayertype' => 4983, //Idea Created
                    'chainplayerup' => $x_pinned['playerid'],
                    'chainidearight' => $add_fields['ideaid'],
                )))) {
                $this->Links->create(array(
                    'chainplayertype' => 4983, //Idea Created
                    'chainplayerup' => $x_pinned['playerid'],
                    'chainidearight' => $add_fields['ideaid'],
                    'chainplayercreator' => $chainplayercreator,
                    'chainnumber' => $chainnumber,
                ));
                array_push($player_appended, $x_pinned['playerid']);
                $chainnumber++;
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


    function update($chainid, $update_columns, $chainplayercreator = 0)
    {

        if (!count($update_columns)) {
            return false;
        }

        $ideas_found = $this->Ideas->read(array('ideaid' => $chainid));
        if (!count($ideas_found)) {
            log_error('Idea #' . $chainid . ' not found in Ideas table');
            return false;
        } elseif (!count($this->Links->read(array('chainid' => $chainid)))) {
            log_error('Idea #' . $chainid . ' not found in Links table');
            return false;
        }

        $affected_rows = 0;
        foreach ($ideas_found as $idea_current) {

            $must_sync_found = false;
            $skip_sync_ledger = array('ideacache', 'ideaexternal', 'ideanumber', 'ideatype');
            $must_sync_ledger = array(
                'ideatext' => 4736, //Idea Text
                'ideahashtag' => 32337,
            );

            //See what is being updated:
            foreach ($update_columns as $key => $value) {
                if (array_key_exists($key, $must_sync_ledger)) {
                    //Update if anything changed:
                    if ($value != $idea_current[$key]) {
                        $this->Links->create(array(
                            'chainplayertype' => 42275, //Idea Trigger
                            'chainplayerup' => $must_sync_ledger[$key],
                            'chainplayercreator' => $chainplayercreator,
                            'chainidearight' => $chainid,
                            'chaintext' => $value,
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

            if (isset($update_columns['ideatext']) && !isset($update_columns['ideacache'])) {
                //Update Idea Text:
                $update_columns['ideacache'] = ideacache($chainid, $update_columns['ideatext']);
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

    function delete($ideaid, $chainplayercreator = 0, $migrateid = 0)
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
        foreach ($this->Links->read(array(
            '(chainid = ' . $ideaid . ' OR chainidearight = ' . $ideaid . ' OR chainidealeft = ' . $ideaid . ')' => null,
        ), array(), 0) as $migrate) {

            if ($migrateid && $migrate['chainid'] != $ideaid) {
                $new_array = array(
                    'chainidealeft' => ($migrate['chainidealeft'] == $ideaid ? $migrateid : $migrate['chainidealeft']),
                    'chainidearight' => ($migrate['chainidearight'] == $ideaid ? $migrateid : $migrate['chainidearight']),
                    'chainplayercreator' => $migrate['chainplayercreator'],
                    'chainplayerdown' => $migrate['chainplayerdown'],
                    'chainplayerup' => $migrate['chainplayerup'],
                    'chainplayertype' => $migrate['chainplayertype'],
                );

                //Update if this new one is unique:
                if (!count($this->Links->read($new_array))) {
                    $x_adjusted += $this->Links->update($migrate['chainid'], $new_array);
                    continue;
                }

            }

            //Just remove it:
            $x_adjusted += $this->Links->delete($migrate['chainid'], $chainplayercreator);

        }

        if ($x_adjusted) {
            //Remove from Table:
            $this->db->query("DELETE FROM cacheideas WHERE ideaid = " . $ideaid . ";");

            //Update Search Index?
            update_algolia(12273, $ideaid);
        } else {
            //Failed to remove
            log_error('ideas->delete() Failed to remove #' . $ideaid . ' Link ID', array(
                'chainplayercreator' => $chainplayercreator,
                'chainidearight' => $ideaid,
                'chainidealeft' => $migrateid,
            ));
        }

        //Return Links deleted:
        return $x_adjusted;
    }


    function command($ideaid, $action_playerid, $action_command1, $action_command2, $chainplayercreator)
    {


        boost_power();

        if (!in_array($action_playerid, $this->config->item('playerids___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif (in_array($action_playerid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && !view_valid_handle_player($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Player. Format must be: @PlayerHandle',
            );

        } elseif (in_array($action_playerid, array(12611, 12612, 27240, 28801)) && !view_valid_handle_idea($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #IdeaHashtag',
            );

        }


        //Basic input validation done, let's continue


        //Fetch all followers:
        $applied_success = 0; //To be populated

        $is_next = $this->Links->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'chainidealeft' => $ideaid,
        ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC'));


        //Process request:
        foreach ($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if (in_array($action_playerid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && view_valid_handle_player($action_command1)) {

                //Check if it has this item:
                foreach ($this->Players->read(array(
                    'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command1)),
                )) as $e) {

                    $idea_has_e = $this->Links->read(array(
                        'chainplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                        'chainidearight' => $next_i['ideaid'],
                        'chainplayerup' => $e['playerid'],
                    ));

                    if (in_array($action_playerid, array(12591, 27080, 27985, 27082, 27084, 27086)) && !count($idea_has_e)) {

                        $player_mapper = array(
                            12591 => 4983,  //Co-Author
                            27985 => 27984, //Include If Has ANY
                            27082 => 26600, //Exclude If Has ALL
                            27084 => 7545,  //Following Add
                            27086 => 26599, //Following Remove
                        );

                        //Missing & Must be Added:
                        $this->Links->create(array(
                            'chainplayercreator' => $chainplayercreator,
                            'chainplayerup' => $e['playerid'],
                            'chainplayertype' => $player_mapper[$action_playerid],
                            'chainidearight' => $next_i['ideaid'],
                            'chaintext' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif (in_array($action_playerid, array(12592, 27081, 27986, 27083, 27085, 27087)) && count($idea_has_e)) {

                        //Has and must be deleted:
                        $this->Links->delete($idea_has_e[0]['chainid'], $chainplayercreator);

                        $applied_success++;

                    }
                }

            } elseif (in_array($action_playerid, array(12611, 12612, 27240, 28801)) && view_valid_handle_idea($action_command1)) {

                foreach ($this->Ideas->read(array(
                    'LOWER(ideahashtag)' => strtolower(view_valid_handle_idea($action_command1)),
                )) as $i) {

                    if ($action_playerid == 27240) {

                        //Copy
                        $result = $this->Ideas->copy(intval($_POST['ideaid']), 0, $action_playerid);
                        if ($result['status']) {
                            //Increment Player since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->Links->read(array(
                            'chainplayertype IN (' . join(',', $this->config->item('playerids___42345')) . ')' => null, //Active Sequence 2-Ways
                            'chainidealeft' => $i['ideaid'],
                            'chainidearight' => $next_i['ideaid'],
                        ), array(), 0);


                        //See how to adjust:
                        if (in_array($action_playerid, array(12611, 28801)) && !count($is_previous)) {

                            //Link
                            $status = $this->Ideas->link($i, 4228, $next_i, $chainplayercreator);

                            if ($status['status']) {

                                if ($action_playerid == 28801) {
                                    //Also remove old link:
                                    $this->Links->delete($next_i['chainid'], $chainplayercreator);
                                }

                                //Increment Player since not there:
                                $applied_success++;
                            }
                        }


                        if ($action_playerid == 12612 && count($is_previous)) {
                            //Unlink
                            $this->Links->delete($is_previous[0]['chainid'], $chainplayercreator);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass Player edit transaction:
        $this->Links->create(array(
            'chainplayertype' => 44179, //Triggered
            'chainplayerup' => $action_playerid,
            'chainplayerdown' => $chainplayercreator,
            'chainplayercreator' => $chainplayercreator,
            'chainidearight' => $ideaid,
            'chaintext' => array(
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


    function link($i, $chainplayertype, $next_i, $chainplayercreator)
    {

        //Links ideas with the causality link ensuring not a duplicate:
        if (0 && $chainplayertype == 4228 && count($this->Links->previousidea(0, $next_i['ideahashtag'], $i['ideaid']))) {
            return array(
                'status' => 0,
                'message' => 'Idea already added in the inverse direction, so it cannot be added here',
            );
        } elseif (count($this->Links->read(array(
            'chainidealeft' => $i['ideaid'],
            'chainplayertype' => $chainplayertype,
            'chainidearight' => $next_i['ideaid'],
        )))) {
            //Make sure not a duplicate link:
            return array(
                'status' => 0,
                'message' => 'Idea is already linked here',
            );
        }

        //Adding PREVIOUS or NEXT Idea from Idea
        $this->Links->create(array(
            'chainplayercreator' => $chainplayercreator,
            'chainidealeft' => $i['ideaid'],
            'chainplayertype' => $chainplayertype,
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

        $input__selection = in_array($i['ideatype'], $this->config->item('playerids___7712'));
        if ($scope == 'AND' && $input__selection) {
            //OR IDEA:
            return array();
        }

        $recursive_idea_ids = array();
        array_push($loop_breaker_ids, intval($i['ideaid']));

        foreach ($this->Links->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'chainidealeft' => $i['ideaid'],
        ), array('chainidearight'), 0, 0, array('chainnumber' => 'ASC')) as $next_i) {

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

    function copy($ideaid, $do_recursive, $chainplayercreator, $previous_i = null, $clone_title = null)
    {

        //Create Clone -or- Link & move-on?
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
            'ideatext' => ($clone_title ? $clone_title : "Copy Of " . $this_i[0]['ideatext']),
            'ideatype' => $this_i[0]['ideatype'],
        ), $chainplayercreator);

        //Always Link Players:
        $filters = array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___41302')) . ')' => null, //Clone Idea Player Links
            'chainidearight' => $ideaid,
        );

        foreach ($this->Links->read($filters, array(), 0) as $x) {
            $this->Links->create(array(
                'chainplayercreator' => $chainplayercreator,
                'chainplayertype' => $x['chainplayertype'],
                'chainidearight' => $idea_new['idea_create']['ideaid'],
                'chainplayerup' => $x['chainplayerup'],
                'chainplayerdown' => $x['chainplayerdown'],
                'chainidealeft' => $x['chainidealeft'],
                'chaintext' => $x['chaintext'],
                'chainnumber' => $x['chainnumber'],
            ));
        }


        //Always Link Followings:
        foreach ($this->Links->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___41301')) . ')' => null, //Duplicate Links
            'chainidearight' => $ideaid,
        ), array(), 0) as $x) {
            $this->Links->create(array(
                'chainplayercreator' => $chainplayercreator,
                'chainplayertype' => $x['chainplayertype'],
                'chainidearight' => $idea_new['idea_create']['ideaid'],
                'chainidealeft' => $x['chainidealeft'],
                'chaintext' => $x['chaintext'],
                'chainnumber' => $x['chainnumber'],
            ));
        }


        //Fetch followers:
        foreach ($this->Links->read(array(
            'chainplayertype IN (' . join(',', $this->config->item('playerids___41301')) . ')' => null, //Duplicate Links
            'chainidealeft' => $ideaid,
        ), array('chainidearight'), 0) as $x) {

            if ($do_recursive && !count($this->Links->read(array(
                    'chainplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    'chainidearight' => $ideaid,
                    'chainplayerup' => 42208, //No-Clone Idea
                )))) {
                //Clone Followers Recursively:
                $this->Ideas->copy($x['ideaid'], $do_recursive, $chainplayercreator, $this_i[0]);
            } else {
                //Link Followers:
                $this->Links->create(array(
                    'chainplayercreator' => $chainplayercreator,
                    'chainplayertype' => $x['chainplayertype'],
                    'chainidealeft' => $idea_new['idea_create']['ideaid'],
                    'chainidearight' => $x['ideaid'],
                    'chaintext' => $x['chaintext'],
                    'chainnumber' => $x['chainnumber'],
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