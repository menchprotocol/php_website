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
                if (!access_level_idea($value['ideahashtag'], 0, $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }


        return $results;

    }


    function create($add_fields, $linkplayercreator = 14068)
    {

        //Add if not added as the author:
        $new_x = $this->Menchledger->create(array(
            'linkplayertype' => 4250,
            'linkplayercreator' => $linkplayercreator,
            'linktext' => (isset($add_fields['ideatext']) ? $add_fields['ideatext'] : null),
        ));

        if (!$new_x['linkid']) {
            //Ooopsi, something went wrong!
            $this->Menchledger->create(array(
                'linkplayertype' => 44179, //Triggered
                'linkplayerup' => 4246, //Platform Bug Reports
                'linkplayerdown' => $linkplayercreator,
                'linktext' => 'i->create() failed to create a new idea',
                'linkplayercreator' => $linkplayercreator,
            ));
            return false;
        }


        //Save hashtag
        if (!isset($add_fields['ideahashtag'])) {
            $add_fields['ideahashtag'] = random_string(13);
        }
        $this->Menchledger->create(array(
            'linkplayercreator' => $linkplayercreator,
            'linkplayerup' => 32337, //Idea Hashtag
            'linkidearight' => $new_x['linkid'],
            'linktext' => $add_fields['ideahashtag'],
            'linkplayertype' => 4983, //CO-author
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
        $player_appended = array($linkplayercreator);
        $pinned_followers = $this->Menchledger->fetch(array(
            'linkplayerup' => $linkplayercreator,
            'linkplayertype' => 41011, //PINNED FOLLOWER
        ), array('linkplayerdown'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC'));


        //Also append all pinned followers:
        $linknumber = 0;
        foreach ($pinned_followers as $x_pinned) {
            if (!in_array($x_pinned['playerid'], $player_appended) && !count($this->Menchledger->fetch(array(
                    'linkplayertype' => 4983, //Idea Created
                    'linkplayerup' => $x_pinned['playerid'],
                    'linkidearight' => $add_fields['ideaid'],
                )))) {
                $this->Menchledger->create(array(
                    'linkplayertype' => 4983, //Idea Created
                    'linkplayerup' => $x_pinned['playerid'],
                    'linkidearight' => $add_fields['ideaid'],
                    'linkplayercreator' => $linkplayercreator,
                    'linknumber' => $linknumber,
                ));
                array_push($player_appended, $x_pinned['playerid']);
                $linknumber++;
            }
        }

        return $is[0];

    }


    function update($id, $update_columns, $external_sync = false)
    {

        if (count($update_columns) == 0) {
            return false;
        }
        
        //Update idea on the ledger:
        foreach($this->Menchledger->fetch(array(
            'linkid' => $id,
        )) as $i){
            
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

    function void($ideaid, $linkplayercreator = 0, $migrate_s__id = 0)
    {

        if ($migrate_s__id > 0) {
            $valid_hashtag = $this->Nodeideas->fetch(array(
                'ideaid' => $migrate_s__id,
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
            foreach ($this->Menchledger->fetch(array(
                'linkplayertype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //IDEA LINKS
                'linkidearight' => $o__id,
            ), array('linkidealeft'), 1) as $previous_i) {
                $deletion_redirect = view_memory(42903, 33286) . $previous_i['ideahashtag'];
            }

            //If not found, find active followings:
            if (!$deletion_redirect) {
                foreach ($this->Menchledger->fetch(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___42268')) . ')' => null, //IDEA LINKS
                    'linkidearight' => $o__id,
                ), array('linkidealeft'), 1) as $previous_i) {
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
             * $this->db->query("UPDATE menchledger SET linkidearight=".$migrate_s__id." WHERE linkidearight=".$ideaid.";");
            $affected_linkidearight = $this->db->affected_rows();
            $x_adjusted += $affected_linkidearight;
            $this->db->query("UPDATE menchledger SET linkidealeft=".$migrate_s__id." WHERE linkidealeft=".$ideaid.";");
            $affected_linkidealeft = $this->db->affected_rows();
            $x_adjusted += $affected_linkidealeft;
             * */

            $player_e = superpower_unlocked();

        } else {

            //REMOVE TRANSACTIONS
            foreach ($this->Menchledger->fetch(array( //Idea Transactions
                '(linkidearight = ' . $ideaid . ' OR linkidealeft = ' . $ideaid . ')' => null,
            ), array(), 0) as $x) {
                //Delete this transaction:
                $x_adjusted += $this->Menchledger->void($x['linkid'], $linkplayercreator);
            }

        }

        //Delete Idea:
        $this->Nodeideas->void($ideaid, $linkplayercreator);

        //Update Search Index?
        if (0) {
            flag_for_search_indexing(12273, $o__id);
        }

        //Return transactions deleted:
        return $x_adjusted;
    }




    function command($ideaid, $action_playerid, $action_command1, $action_command2, $linkplayercreator)
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

        $is_next = $this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'linkidealeft' => $ideaid,
        ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC'));


        //Process request:
        foreach ($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if (in_array($action_playerid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && view_valid_handle_player($action_command1)) {

                //Check if it has this item:
                foreach ($this->Nodeplayers->fetch(array(
                    'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command1)),
                )) as $e) {

                    $idea_has_e = $this->Menchledger->fetch(array(
                        'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                        'linkidearight' => $next_i['ideaid'],
                        'linkplayerup' => $e['playerid'],
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
                        $this->Menchledger->create(array(
                            'linkplayercreator' => $linkplayercreator,
                            'linkplayerup' => $e['playerid'],
                            'linkplayertype' => $player_mapper[$action_playerid],
                            'linkidearight' => $next_i['ideaid'],
                            'linktext' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif (in_array($action_playerid, array(12592, 27081, 27986, 27083, 27085, 27087)) && count($idea_has_e)) {

                        //Has and must be deleted:
                        $this->Menchledger->void($idea_has_e[0]['linkid'], $linkplayercreator);

                        $applied_success++;

                    }
                }

            } elseif (in_array($action_playerid, array(12611, 12612, 27240, 28801)) && view_valid_handle_idea($action_command1)) {

                foreach ($this->Nodeideas->fetch(array(
                    'LOWER(ideahashtag)' => strtolower(view_valid_handle_idea($action_command1)),
                )) as $i) {

                    if ($action_playerid == 27240) {

                        //Copy
                        $result = $this->Nodeideas->copy(intval($_POST['ideaid']), 0, $action_playerid);
                        if ($result['status']) {
                            //Increment Player since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->Menchledger->fetch(array(
                            'linkplayertype IN (' . join(',', $this->config->item('playerids___42345')) . ')' => null, //Active Sequence 2-Ways
                            'linkidealeft' => $i['ideaid'],
                            'linkidearight' => $next_i['ideaid'],
                        ), array(), 0);


                        //See how to adjust:
                        if (in_array($action_playerid, array(12611, 28801)) && !count($is_previous)) {

                            //Link
                            $status = $this->Nodeideas->link($i, 4228, $next_i, $linkplayercreator);

                            if ($status['status']) {

                                if ($action_playerid == 28801) {
                                    //Also remove old link:
                                    $this->Menchledger->void($next_i['linkid'], $linkplayercreator);
                                }

                                //Increment Player since not there:
                                $applied_success++;
                            }
                        }


                        if ($action_playerid == 12612 && count($is_previous)) {
                            //Unlink
                            $this->Menchledger->void($is_previous[0]['linkid'], $linkplayercreator);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass Player edit transaction:
        $this->Menchledger->create(array(
            'linkplayertype' => 44179, //Triggered
            'linkplayerup' => $action_playerid,
            'linkplayerdown' => $linkplayercreator,
            'linkplayercreator' => $linkplayercreator,
            'linkidearight' => $ideaid,
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



    function link($i, $linkplayertype, $next_i, $linkplayercreator)
    {

        //Links ideas with the causality link ensuring not a duplicate:
        if (0 && $linkplayertype == 4228 && count($this->Menchledger->find_previous(0, $next_i['ideahashtag'], $i['ideaid']))) {
            return array(
                'status' => 0,
                'message' => 'Idea already added in the inverse direction, so it cannot be added here',
            );
        } elseif (count($this->Menchledger->fetch(array(
            'linkidealeft' => $i['ideaid'],
            'linkplayertype' => $linkplayertype,
            'linkidearight' => $next_i['ideaid'],
        )))) {
            //Make sure not a duplicate link:
            return array(
                'status' => 0,
                'message' => 'Idea is already linked here',
            );
        }

        //Adding PREVIOUS or NEXT Idea from Idea
        $this->Menchledger->create(array(
            'linkplayercreator' => $linkplayercreator,
            'linkidealeft' => $i['ideaid'],
            'linkplayertype' => $linkplayertype,
            'linkidearight' => $next_i['ideaid'],
        ), true);

        //Return result:
        return array(
            'status' => 1,
        );
    }


    function ids($i, $scope, $loop_breaker_ids = array())
    {

        if (!($scope == 'ALL' /* includes both AND and OR ideas */ || $scope == 'AND' /* AND ideas only */ || $scope == 'OR' /* OR ideas only */ )) {
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

        foreach ($this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'linkidealeft' => $i['ideaid'],
        ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC')) as $next_i) {

            if (!in_array(intval($next_i['ideaid']), $recursive_idea_ids)) {
                if (!($scope == 'OR' && !$input__selection)) {
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_idea_ids, intval($next_i['ideaid']));
                }
            }

            //Add to current array if we found anything:
            $copy = $this->Nodeideas->ids($next_i, $scope, $loop_breaker_ids);
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

    function copy($ideaid, $do_recursive, $linkplayercreator, $previous_i = null, $clone_title = null)
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
        ), $linkplayercreator);

        //Always Link Players:
        $filters = array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41302')) . ')' => null, //Clone Idea Player Links
            'linkidearight' => $ideaid,
        );

        foreach ($this->Menchledger->fetch($filters, array(), 0) as $x) {
            if($x['linkplayerup']==32337 && $x['linkplayertype']==4983){
                //Hashtag is a system link that does not to be replicated:
                continue;
            }
            $this->Menchledger->create(array(
                'linkplayercreator' => $linkplayercreator,
                'linkplayertype' => $x['linkplayertype'],
                'linkidearight' => $idea_new['ideaid'],
                'linkplayerup' => $x['linkplayerup'],
                'linkplayerdown' => $x['linkplayerdown'],
                'linkidealeft' => $x['linkidealeft'],
                'linktext' => $x['linktext'],
                'linknumber' => $x['linknumber'],
            ));
        }


        //Always Link Followings:
        foreach ($this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41301')) . ')' => null, //Duplicate Links
            'linkidearight' => $ideaid,
        ), array(), 0) as $x) {
            $this->Menchledger->create(array(
                'linkplayercreator' => $linkplayercreator,
                'linkplayertype' => $x['linkplayertype'],
                'linkidearight' => $idea_new['ideaid'],
                'linkidealeft' => $x['linkidealeft'],
                'linktext' => $x['linktext'],
                'linknumber' => $x['linknumber'],
            ));
        }


        //Fetch followers:
        foreach ($this->Menchledger->fetch(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41301')) . ')' => null, //Duplicate Links
            'linkidealeft' => $ideaid,
        ), array('linkidearight'), 0) as $x) {

            if ($do_recursive && !count($this->Menchledger->fetch(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    'linkidearight' => $ideaid,
                    'linkplayerup' => 42208, //No-Clone Idea
                )))) {
                //Clone Followers Recursively:
                $this->Nodeideas->copy($x['ideaid'], $do_recursive, $linkplayercreator, $this_i[0]);
            } else {
                //Link Followers:
                $this->Menchledger->create(array(
                    'linkplayercreator' => $linkplayercreator,
                    'linkplayertype' => $x['linkplayertype'],
                    'linkidealeft' => $idea_new['ideaid'],
                    'linkidearight' => $x['ideaid'],
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




}