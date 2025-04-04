<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ideas extends CIdea_cache
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


    function create($add_fields, $linkplayercreator = 14068 /* GUEST */)
    {

        //Add if not added as the author:
        $new_x = $this->Links->create(array(
            'linkplayertype' => 4250,
            'linkplayercreator' => $linkplayercreator,
            'linktext' => (isset($add_fields['ideatext']) ? $add_fields['ideatext'] : null),
        ));

        if (!$new_x['linkid']) {
            return false;
        }

        //Save hashtag
        if (!isset($add_fields['ideahashtag'])) {
            $add_fields['ideahashtag'] = random_string(13);
        }
        $this->Links->create(array(
            'linkplayertype' => 42275, //Observed
            'linkplayerup' => 32337, //Idea Hashtag
            'linkplayercreator' => $linkplayercreator,
            'linkidearight' => $new_x['linkid'],
            'linktext' => $add_fields['ideahashtag'],
        ));

        //Save Idea
        $add_fields['ideaid'] = $new_x['linkid'];
        $add_fields['ideacache'] = ideacache($add_fields['ideaid'], $add_fields['ideatext']);
        $this->db->insert('nodeideas', $add_fields);

        //Update Search Index:
        update_algolia(12273, $add_fields['ideaid']);

        //Additional Players to be added? Start with creator
        $player_appended = array($linkplayercreator);
        $pinned_followers = $this->Links->read(array(
            'linkplayerup' => $linkplayercreator,
            'linkplayertype' => 41011, //PINNED FOLLOWER
        ), array('linkplayerdown'), 0, 0, array('linknumber' => 'ASC', 'linkid' => 'DESC'));

        //Also append all pinned followers:
        $linknumber = 0;
        foreach ($pinned_followers as $x_pinned) {
            if (!in_array($x_pinned['playerid'], $player_appended) && !count($this->Links->read(array(
                    'linkplayertype' => 4983, //Idea Created
                    'linkplayerup' => $x_pinned['playerid'],
                    'linkidearight' => $add_fields['ideaid'],
                )))) {
                $this->Links->create(array(
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

        //Fetch to return the complete Idea
        $is = $this->Ideas->read(array(
            'ideaid' => $add_fields['ideaid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'new_idea' => $is[0],
        );

    }


    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
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
                if (!idea_access_level($value['ideahashtag'], 0, $value)) {
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
        $skip_sync_ledger = array('ideacache','ideaexternal','ideanumber','ideatype');
        $must_sync_ledger = array(
            'ideamessage' => 32337, //TODO Update later with message
            'ideahashtag' => 32337,
        );

        //See what is being updated:
        foreach($update_columns as $key => $value) {
            if(array_key_exists($key, $must_sync_ledger)){
                $this->Links->create(array(
                    'linkplayertype' => 42275, //Observed
                    'linkplayerup' => $must_sync_ledger[$key], //Idea Hashtag
                    'linkplayercreator' => $linkplayercreator,
                    'linkidearight' => $id,
                    'linktext' => $value,
                ));
                $must_sync_found = true;
            } elseif(in_array($key, $skip_sync_ledger)){
                //Nothing we need to do here
            } else {
                //Remove this as its unknown:
                unset($update_columns[$key]);
            }
        }

        if(isset($update_columns['ideatext']) && !isset($update_columns['ideacache'])){
            //Update Idea Text:
            $update_columns['ideacache'] = ideacache($id, $value);
        }

        //Update:
        $this->db->set($update_columns);
        $this->db->where('ideaid', intval($id));
        $this->db->update('nodeideas');
        $affected_rows = $this->db->affected_rows();

        if($must_sync_found){
            //Sync algolia:
            update_algolia(12273, $id);
        }

        return $affected_rows;
    }

    function delete($ideaid, $linkplayercreator = 0, $migrate_s__id = 0)
    {

        if (!count($this->Ideas->read(array('ideaid' => $ideaid)))) {
            return array(
                'status' => 0,
                'message' => $ideaid . ' is not a valid ID',
            );
        } elseif ($migrate_s__id > 0 && !count($this->Ideas->read(array('ideaid' => $migrate_s__id)))) {
            return array(
                'status' => 0,
                'message' => $migrate_s__id . ' is not a valid ID',
            );
        }

        $x_adjusted = 0;
        foreach ($this->Links->read(array(
            '(linkidearight = ' . $ideaid . ' OR linkidealeft = ' . $ideaid . ')' => null,
        ), array(), 0) as $migrate) {

            if ($migrate_s__id) {
                $new_array = array(
                    'linkidealeft' => ($migrate['linkidealeft'] == $ideaid ? $migrate_s__id : $migrate['linkidealeft']),
                    'linkidearight' => ($migrate['linkidearight'] == $ideaid ? $migrate_s__id : $migrate['linkidearight']),
                    'linkplayercreator' => $migrate['linkplayercreator'],
                    'linkplayerdown' => $migrate['linkplayerdown'],
                    'linkplayerup' => $migrate['linkplayerup'],
                    'linkplayertype' => $migrate['linkplayertype'],
                );

                //Update if this new one is unique:
                if (!count($this->Links->read($new_array))) {
                    $x_adjusted += $this->Links->update($migrate['linkid'], $new_array);
                    continue;
                }
            }

            //Just remove it:
            $x_adjusted += $this->Links->delete($migrate['linkid'], $linkplayercreator);

        }
        
        //Remove From Ledger:
        $removed = $this->Links->delete($ideaid, $linkplayercreator);
        
        if($removed){
            //Remove from Table:
            $this->db->query("DELETE FROM nodeideas WHERE ideaid = " . $ideaid . ";");

            //Update Search Index?
            update_algolia(12273, $ideaid);
        }

        //Return Links deleted:
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

        $is_next = $this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___42267')) . ')' => null, //Active Sequence Down
            'linkidealeft' => $ideaid,
        ), array('linkidearight'), 0, 0, array('linknumber' => 'ASC'));


        //Process request:
        foreach ($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if (in_array($action_playerid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && view_valid_handle_player($action_command1)) {

                //Check if it has this item:
                foreach ($this->Players->read(array(
                    'LOWER(playerhandle)' => strtolower(view_valid_handle_player($action_command1)),
                )) as $e) {

                    $idea_has_e = $this->Links->read(array(
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
                        $this->Links->create(array(
                            'linkplayercreator' => $linkplayercreator,
                            'linkplayerup' => $e['playerid'],
                            'linkplayertype' => $player_mapper[$action_playerid],
                            'linkidearight' => $next_i['ideaid'],
                            'linktext' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif (in_array($action_playerid, array(12592, 27081, 27986, 27083, 27085, 27087)) && count($idea_has_e)) {

                        //Has and must be deleted:
                        $this->Links->delete($idea_has_e[0]['linkid'], $linkplayercreator);

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
                            'linkplayertype IN (' . join(',', $this->config->item('playerids___42345')) . ')' => null, //Active Sequence 2-Ways
                            'linkidealeft' => $i['ideaid'],
                            'linkidearight' => $next_i['ideaid'],
                        ), array(), 0);


                        //See how to adjust:
                        if (in_array($action_playerid, array(12611, 28801)) && !count($is_previous)) {

                            //Link
                            $status = $this->Ideas->link($i, 4228, $next_i, $linkplayercreator);

                            if ($status['status']) {

                                if ($action_playerid == 28801) {
                                    //Also remove old link:
                                    $this->Links->delete($next_i['linkid'], $linkplayercreator);
                                }

                                //Increment Player since not there:
                                $applied_success++;
                            }
                        }


                        if ($action_playerid == 12612 && count($is_previous)) {
                            //Unlink
                            $this->Links->delete($is_previous[0]['linkid'], $linkplayercreator);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass Player edit transaction:
        $this->Links->create(array(
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
        if (0 && $linkplayertype == 4228 && count($this->Links->previousidea(0, $next_i['ideahashtag'], $i['ideaid']))) {
            return array(
                'status' => 0,
                'message' => 'Idea already added in the inverse direction, so it cannot be added here',
            );
        } elseif (count($this->Links->read(array(
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
        $this->Links->create(array(
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

    function copy($ideaid, $do_recursive, $linkplayercreator, $previous_i = null, $clone_title = null)
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
                'new_ideaid' => 0,
                'new_ideahashtag' => '',
            );
        }

        $idea_new = $this->Ideas->create(array(
            'ideatext' => ($clone_title ? $clone_title : "Copy Of " . $this_i[0]['ideatext']),
            'ideatype' => $this_i[0]['ideatype'],
        ), $linkplayercreator);

        //Always Link Players:
        $filters = array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41302')) . ')' => null, //Clone Idea Player Links
            'linkidearight' => $ideaid,
        );

        foreach ($this->Links->read($filters, array(), 0) as $x) {
            if ($x['linkplayerup'] == 32337 && $x['linkplayertype'] == 4983) {
                //Hashtag is a system link that does not to be replicated:
                continue;
            }
            $this->Links->create(array(
                'linkplayercreator' => $linkplayercreator,
                'linkplayertype' => $x['linkplayertype'],
                'linkidearight' => $idea_new['new_idea']['ideaid'],
                'linkplayerup' => $x['linkplayerup'],
                'linkplayerdown' => $x['linkplayerdown'],
                'linkidealeft' => $x['linkidealeft'],
                'linktext' => $x['linktext'],
                'linknumber' => $x['linknumber'],
            ));
        }


        //Always Link Followings:
        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41301')) . ')' => null, //Duplicate Links
            'linkidearight' => $ideaid,
        ), array(), 0) as $x) {
            $this->Links->create(array(
                'linkplayercreator' => $linkplayercreator,
                'linkplayertype' => $x['linkplayertype'],
                'linkidearight' => $idea_new['new_idea']['ideaid'],
                'linkidealeft' => $x['linkidealeft'],
                'linktext' => $x['linktext'],
                'linknumber' => $x['linknumber'],
            ));
        }


        //Fetch followers:
        foreach ($this->Links->read(array(
            'linkplayertype IN (' . join(',', $this->config->item('playerids___41301')) . ')' => null, //Duplicate Links
            'linkidealeft' => $ideaid,
        ), array('linkidearight'), 0) as $x) {

            if ($do_recursive && !count($this->Links->read(array(
                    'linkplayertype IN (' . join(',', $this->config->item('playerids___33602')) . ')' => null, //Idea/Player Links Active
                    'linkidearight' => $ideaid,
                    'linkplayerup' => 42208, //No-Clone Idea
                )))) {
                //Clone Followers Recursively:
                $this->Ideas->copy($x['ideaid'], $do_recursive, $linkplayercreator, $this_i[0]);
            } else {
                //Link Followers:
                $this->Links->create(array(
                    'linkplayercreator' => $linkplayercreator,
                    'linkplayertype' => $x['linkplayertype'],
                    'linkidealeft' => $idea_new['new_idea']['ideaid'],
                    'linkidearight' => $x['ideaid'],
                    'linktext' => $x['linktext'],
                    'linknumber' => $x['linknumber'],
                ));
            }
        }

        return array(
            'status' => 1,
            'new_ideaid' => $idea_new['new_idea']['ideaid'],
            'new_ideahashtag' => $idea_new['new_idea']['ideahashtag'],
        );

    }


}