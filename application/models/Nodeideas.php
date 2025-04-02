<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Nodeideas extends CIdea_cache
{

    /*
     *
     * Idea related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function create($add_fields, $linkcreator = 14068)
    {

        //Add if not added as the author:
        $new_x = $this->Ledger->create(array(
            'linktype' => 4250,
            'linkcreator' => $linkcreator,
            'linktext' => (isset($add_fields['ideatext']) ? $add_fields['ideatext'] : null),
        ));

        if (!$new_x['linkid']) {
            //Ooopsi, something went wrong!
            $this->Ledger->create(array(
                'linktype' => 44179, //Triggered
                'linkup' => 4246, //Platform Bug Reports
                'linkdown' => $linkcreator,
                'linktext' => 'i->create() failed to create a new idea',
                'linkcreator' => $linkcreator,
            ));
            return false;
        }


        //Save hashtag
        if (!isset($add_fields['ideahashtag'])) {
            $add_fields['ideahashtag'] = random_string(13);
        }
        $this->Ledger->create(array(
            'linkcreator' => $linkcreator,
            'linkup' => 32337, //Idea Hashtag
            'linkright' => $new_x['linkid'],
            'linktext' => $add_fields['ideahashtag'],
            'linktype' => 4983, //CO-author
        ));

        //Save Player Cache:
        $add_fields['ideaid'] = $new_x['linkid'];
        $this->db->insert('nodeideas', $add_fields);

        //Sync messages:
        $view_sync_links = view_sync_links($add_fields['ideatext'], true, $add_fields['ideaid']);

        //Fetch to return the complete Player data:
        $is = $this->Nodeideas->fetch(array(
            'ideaid' => $add_fields['ideaid'],
        ));

        //Update Search Index:
        flag_for_search_indexing(12273, $add_fields['ideaid']);

        //Additional Players to be added? Start with creator
        $player_appended = array($linkcreator);
        $pinned_followers = $this->Ledger->fetch(array(
            'linkup' => $linkcreator,
            'linktype' => 41011, //PINNED FOLLOWER
        ), array('linkdown'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC'));


        //Also append all pinned followers:
        $linknumber = 0;
        foreach ($pinned_followers as $x_pinned) {
            if (!in_array($x_pinned['playerid'], $player_appended) && !count($this->Ledger->fetch(array(
                    'linktype' => 4983, //Idea Created
                    'linkup' => $x_pinned['playerid'],
                    'linkright' => $add_fields['ideaid'],
                )))) {
                $this->Ledger->create(array(
                    'linktype' => 4983, //Idea Created
                    'linkup' => $x_pinned['playerid'],
                    'linkright' => $add_fields['ideaid'],
                    'linkcreator' => $linkcreator,
                    'linknumber' => $linknumber,
                ));
                array_push($player_appended, $x_pinned['playerid']);
                $linknumber++;
            }
        }

        return $is[0];

    }

    function fetch($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Ideas
        $this->db->select($select);
        $this->db->from('nodeideas');

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
                if (!access_level_i($value['ideahashtag'], 0, $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }


        return $results;

    }


    function update($id, $update_columns, $external_sync = false)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Update:
        $this->db->where('ideaid', intval($id));
        $this->db->update('nodeideas', $update_columns);
        $affected_rows = $this->db->affected_rows();

        if ($affected_rows && $external_sync) {
            //Sync algolia:
            flag_for_search_indexing(12273, $id);
        }

        return $affected_rows;
    }

    function remove($ideaid, $linkcreator = 0, $migrate_s__id = 0)
    {

        //TODO Needs work
        return false;

        if ($migrate_s__id > 0) {
            $valid_hashtag = $this->Nodeideas->fetch(array(
                'LOWER(ideahashtag)' => $migrate_s__id,
            ));
            if (!count($valid_hashtag)) {
                return array(
                    'status' => 0,
                    'message' => '#' . $migrate_s__handle . ' is an Invalid Idea hashtag!',
                );
            } elseif ($valid_hashtag[0]['ideaid'] == $o__id) {
                return array(
                    'status' => 0,
                    'message' => 'You cannot migrate this idea to itself! Choose a different idea to migrate.',
                );
            }
            $migrate_s__id = $valid_hashtag[0]['ideaid'];
        }

        //Determine what to do after deleted:
        if ($o__id == $focus__id) {

            //Find Published Followings:
            foreach ($this->Ledger->fetch(array(
                'linktype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //IDEA LINKS
                'linkright' => $o__id,
            ), array('linkleft'), 1) as $previous_i) {
                $deletion_redirect = view_memory(42903, 33286) . $previous_i['ideahashtag'];
            }

            //If not found, find active followings:
            if (!$deletion_redirect) {
                foreach ($this->Ledger->fetch(array(
                    'linktype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //IDEA LINKS
                    'linkright' => $o__id,
                ), array('linkleft'), 1) as $previous_i) {
                    $deletion_redirect = view_memory(42903, 33286) . $previous_i['ideahashtag'];
                }
            }

            //If still not found, go to main page if no followings found:
            if (!$deletion_redirect) {
                foreach ($this->Nodeideas->fetch(array(
                    'ideaid' => $o__id,
                )) as $i) {
                    $deletion_redirect = view_memory(42903, 33286) . $i['ideahashtag'];
                }
            }

        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12273_' . $o__id;

        }


        $x_adjusted = 0;
        if ($migrate_s__id && 0) {

            //Migrate Transactions:
            /*
             * $this->db->query("UPDATE menchledger SET linkright=".$migrate_s__id." WHERE linkright=".$ideaid.";");
            $affected_linkright = $this->db->affected_rows();
            $x_adjusted += $affected_linkright;
            $this->db->query("UPDATE menchledger SET linkleft=".$migrate_s__id." WHERE linkleft=".$ideaid.";");
            $affected_linkleft = $this->db->affected_rows();
            $x_adjusted += $affected_linkleft;
             * */

            $player_e = superpower_unlocked();

        } else {

            //REMOVE TRANSACTIONS
            foreach ($this->Ledger->fetch(array( //Idea Transactions
                '(linkright = ' . $ideaid . ' OR linkleft = ' . $ideaid . ')' => null,
            ), array(), 0) as $x) {
                //Delete this transaction:
                $x_adjusted += $this->Ledger->update($x['linkid'], array(), $linkcreator);
            }

        }

        //Delete Idea:
        $this->Nodeideas->update($ideaid, array(), $linkcreator);

        //Update Search Index?
        if (0) {
            flag_for_search_indexing(12273, $o__id);
        }

        //Return transactions deleted:
        return $x_adjusted;
    }


    function duplicate($i, $copy_to__id, $linkcreator)
    {

        $idea_new = $this->Nodeideas->create(array(
            'ideatext' => $i['ideatext'],
            'ideatype' => $i['ideatype'],
        ), $linkcreator);

        //Copy related transactions:
        $links = 0;
        foreach ($this->Ledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___27240')) . ')' => null, //COPY Transactions
            '(linkright=' . $i['ideaid'] . ' OR linkleft=' . $i['ideaid'] . ')' => null,
        ), array(), 0) as $x) {

            //Duplicate transaction, with new idea
            if (!count($this->Ledger->fetch(array(
                'linktype' => $x['linktype'],
                'linktext' => $x['linktext'],
                'linkup' => $x['linkup'],
                'linkdown' => $x['linkdown'],
                'linkleft' => ($i['ideaid'] == $x['linkleft'] ? $idea_new['ideaid'] : $x['linkleft']),
                'linkright' => ($i['ideaid'] == $x['linkright'] ? $idea_new['ideaid'] : $x['linkright']),
            )))) {
                $links++;
                $this->Ledger->create(array(
                    //Copy:
                    'linktype' => $x['linktype'],
                    'linknumber' => $x['linknumber'],
                    'linktext' => $x['linktext'],
                    'linkup' => $x['linkup'],
                    'linkdown' => $x['linkdown'],
                    //Change:
                    'linkcreator' => $linkcreator,
                    'linkleft' => ($i['ideaid'] == $x['linkleft'] ? $idea_new['ideaid'] : $x['linkleft']),
                    'linkright' => ($i['ideaid'] == $x['linkright'] ? $idea_new['ideaid'] : $x['linkright']),
                ));
            }

        }

        return $links;

    }


    function i_link($i, $linktype, $next_i, $linkcreator)
    {

        //Links ideas with the causality link ensuring not a duplicate:
        if (0 && $linktype == 4228 && count($this->Ledger->find_previous(0, $next_i['ideahashtag'], $i['ideaid']))) {
            return array(
                'status' => 0,
                'message' => 'Idea already added in the inverse direction, so it cannot be added here',
            );
        } elseif (count($this->Ledger->fetch(array(
            'linkleft' => $i['ideaid'],
            'linktype' => $linktype,
            'linkright' => $next_i['ideaid'],
        )))) {
            //Make sure not a duplicate link:
            return array(
                'status' => 0,
                'message' => 'Idea is already linked here',
            );
        }

        //Adding PREVIOUS or NEXT Idea from Idea
        $this->Ledger->create(array(
            'linkcreator' => $linkcreator,
            'linkleft' => $i['ideaid'],
            'linktype' => $linktype,
            'linkright' => $next_i['ideaid'],
        ), true);

        //Return result:
        return array(
            'status' => 1,
        );
    }


    function recursive_down_ids($i, $scope, $loop_breaker_ids = array())
    {

        /*
         *
         * $fetch can be either:
         * - ALL includes both AND and OR ideas
         * - AND ideas only
         * - OR ideas only
         * */

        if (!($scope == 'ALL' || $scope == 'AND' || $scope == 'OR')) {
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

        foreach ($this->Ledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'linkleft' => $i['ideaid'],
        ), array('linkright'), 0, 0, array('linknumber' => 'ASC')) as $next_i) {

            if (!in_array(intval($next_i['ideaid']), $recursive_idea_ids)) {
                if (!($scope == 'OR' && !$input__selection)) {
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_idea_ids, intval($next_i['ideaid']));
                }
            }

            //Add to current array if we found anything:
            $recursive_down_ids = $this->Nodeideas->recursive_down_ids($next_i, $scope, $loop_breaker_ids);
            if (isset($recursive_down_ids['recursive_idea_ids'])) {
                foreach ($recursive_down_ids['recursive_idea_ids'] as $recursive_idea_id) {
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

    function recursive_clone($ideaid, $do_recursive, $linkcreator, $previous_i = null, $clone_title = null)
    {

        //Create Clone -or- Link & move-on?
        //Validate Idea:
        $this_i = $this->Nodeideas->fetch(array(
            'ideaid' => $ideaid,
        ));
        if (count($this_i) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
                'new_ideaid' => 0,
                'new_ideahashtag' => '',
            );
        }

        $idea_new = $this->Nodeideas->create(array(
            'ideatext' => ($clone_title ? $clone_title : "Copy Of " . $this_i[0]['ideatext']),
            'ideatype' => $this_i[0]['ideatype'],
        ), $linkcreator);

        //Always Link Players:
        $filters = array(
            'linktype IN (' . join(',', $this->config->item('playerids___41302')) . ')' => null, //Clone Idea Player Links
            'linkright' => $ideaid,
        );

        foreach ($this->Ledger->fetch($filters, array(), 0) as $x) {
            $this->Ledger->create(array(
                'linkcreator' => $linkcreator,
                'linktype' => $x['linktype'],
                'linkright' => $idea_new['ideaid'],
                'linkup' => $x['linkup'],
                'linkdown' => $x['linkdown'],
                'linkleft' => $x['linkleft'],
                'linktext' => $x['linktext'],
                'linknumber' => $x['linknumber'],
            ));
        }


        //Always Link Followings:
        foreach ($this->Ledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___41301')) . ')' => null, //Duplicate Links
            'linkright' => $ideaid,
        ), array('linkleft'), 0) as $x) {
            $this->Ledger->create(array(
                'linkcreator' => $linkcreator,
                'linktype' => $x['linktype'],
                'linkright' => $idea_new['ideaid'],
                'linkleft' => $x['ideaid'],
                'linktext' => $x['linktext'],
                'linknumber' => $x['linknumber'],
            ));
        }


        //Fetch followers:
        foreach ($this->Ledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___41301')) . ')' => null, //Duplicate Links
            'linkleft' => $ideaid,
        ), array('linkright'), 0) as $x) {

            if ($do_recursive && !count($this->Ledger->fetch(array(
                    'linktype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    'linkright' => $ideaid,
                    'linkup' => 42208, //No-Clone Idea
                )))) {
                //Clone Followers Recursively:
                $this->Nodeideas->recursive_clone($x['ideaid'], $do_recursive, $linkcreator, $this_i[0]);
            } else {
                //Link Followers:
                $this->Ledger->create(array(
                    'linkcreator' => $linkcreator,
                    'linktype' => $x['linktype'],
                    'linkleft' => $idea_new['ideaid'],
                    'linkright' => $x['ideaid'],
                    'linktext' => $x['linktext'],
                    'linknumber' => $x['linknumber'],
                ));
            }
        }

        return array(
            'status' => 1,
            'new_ideaid' => $idea_new['ideaid'],
            'new_ideahashtag' => $idea_new['ideahashtag'],
        );

    }


    function mass_update($ideaid, $action_playerid, $action_command1, $action_command2, $linkcreator)
    {

        //Alert: Has a twin function called e_mass_update()

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

        } elseif (in_array($action_playerid, array(12611, 12612, 27240, 28801)) && !view_valid_handle_i($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #IdeaHashtag',
            );

        }


        //Basic input validation done, let's continue


        //Fetch all followers:
        $applied_success = 0; //To be populated

        $is_next = $this->Ledger->fetch(array(
            'linktype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'linkleft' => $ideaid,
        ), array('linkright'), 0, 0, array('linknumber' => 'ASC'));


        //Process request:
        foreach ($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if (in_array($action_playerid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && view_valid_handle_player($action_command1)) {

                //Check if it has this item:
                foreach ($this->Nodeplayers->fetch(array(
                    'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command1)),
                )) as $e) {

                    $idea_has_e = $this->Ledger->fetch(array(
                        'linktype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                        'linkright' => $next_i['ideaid'],
                        'linkup' => $e['playerid'],
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
                        $this->Ledger->create(array(
                            'linkcreator' => $linkcreator,
                            'linkup' => $e['playerid'],
                            'linktype' => $player_mapper[$action_playerid],
                            'linkright' => $next_i['ideaid'],
                            'linktext' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif (in_array($action_playerid, array(12592, 27081, 27986, 27083, 27085, 27087)) && count($idea_has_e)) {

                        //Has and must be deleted:
                        $this->Ledger->update($idea_has_e[0]['linkid'], array(), $linkcreator);

                        $applied_success++;

                    }
                }

            } elseif (in_array($action_playerid, array(12611, 12612, 27240, 28801)) && view_valid_handle_i($action_command1)) {

                foreach ($this->Nodeideas->fetch(array(
                    'LOWER(ideahashtag)' => strtolower(view_valid_handle_i($action_command1)),
                )) as $i) {

                    if ($action_playerid == 27240) {

                        //Copy
                        $link_count = $this->Nodeideas->duplicate($next_i, $i['ideaid'], $linkcreator);

                        if ($link_count > 0) {
                            //Increment Player since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->Ledger->fetch(array(
                            'linktype IN (' . join(',', $this->config->item('playerids___42345')) . ')' => null, //Active Sequence 2-Ways
                            'linkleft' => $i['ideaid'],
                            'linkright' => $next_i['ideaid'],
                        ), array(), 0);


                        //See how to adjust:
                        if (in_array($action_playerid, array(12611, 28801)) && !count($is_previous)) {

                            //Link
                            $status = $this->Nodeideas->i_link($i, 4228, $next_i, $linkcreator);

                            if ($status['status']) {

                                if ($action_playerid == 28801) {
                                    //Also remove old link:
                                    $this->Ledger->update($next_i['linkid'], array(), $linkcreator);
                                }

                                //Increment Player since not there:
                                $applied_success++;
                            }
                        }


                        if ($action_playerid == 12612 && count($is_previous)) {
                            //Unlink
                            $this->Ledger->update($is_previous[0]['linkid'], array(), $linkcreator);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass Player edit transaction:
        $this->Ledger->create(array(
            'linktype' => 44179, //Triggered
            'linkup' => $action_playerid,
            'linkdown' => $linkcreator,
            'linkcreator' => $linkcreator,
            'linkright' => $ideaid,
            'linktext' => array(
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


}