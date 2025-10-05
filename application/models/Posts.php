<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Posts extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainusercreator = 0)
    {

        if (!isset($add_fields['posttext'])) {
            return false;
        }

        //Determine ID
        $user_session = user_session();
        $chainusercreator = ($chainusercreator > 0 ? $chainusercreator : ($user_session ? $user_session['userid'] : 14068));

        //Set defaults if missing:
        if (!isset($add_fields['posthashtag'])) {
            $add_fields['posthashtag'] = generate_user(12273, one_two_explode('', "\n", $add_fields['posttext']));
        }

        $post_index = post_index($add_fields['posttext'], 0, 0, $add_fields['posthashtag']);

        //Add to Chain:
        $new_array = array(
            'chainusertype' => 12273,
            'chainusercreator' => $chainusercreator,
        );
        $new_x = $this->Chains->create($new_array);

        if (!$new_x['chainid']) {
            return log_error('create() failed to create a new Post', $new_array);
        }

        //Update other data on chain:
        $post_index = post_index($add_fields['posttext'], $new_x['chainid'], $chainusercreator, $add_fields['posthashtag']);
        //Update new ID:
        $this->db->query("UPDATE ideachains SET chainpostinput = " . $new_x['chainid'] . ", chainvalue='"."#" . $add_fields['posthashtag'] . "\n" . $post_index['chainvalue']."' WHERE chainid = " . $new_x['chainid'] . ";");


        //Add to cache:
        $this->db->insert('posts', array(
            'postid' => $new_x['chainid'],
            'postcreator' => $new_x['chainusercreator'],
            'posttime' => $new_x['chaintime'],
            'posthashtag' => $add_fields['posthashtag'],
            'posttext' => $post_index['posttext'],
            'postdiscover' => $post_index['postdiscover'],
            'postedit' => $post_index['postedit'],
        ));

        //Update Search Index:
        update_algolia(12273, $new_x['chainid']);

        //Fetch to return the complete Post
        $is = $this->Posts->read(array(
            'postid' => $new_x['chainid'],
        ));

        //Return success:
        return array(
            'status' => 1,
            'post_create' => $is[0],
        );

    }


    function read($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Posts
        $this->db->select($select);
        $this->db->from('posts');

        $void_found = false;
        foreach ($query_filters as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
            if (substr_count($key, 'postvoid')) {
                $void_found = true;
            }
        }
        if (!$void_found) {
            //Auto add:
            $this->db->where('postvoid', 0); //Not Void
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
                if (!post_access($value['posthashtag'], 0, $value)) {
                    unset($results[$key]); //Remove this option
                }
            }
        }

        return $results;

    }


    function update($postid, $update_columns, $chainusercreator = 0)
    {

        $core_fields = array('posttext', 'posthashtag');

        //Find existing chain to update:
        foreach ($this->Chains->read(array(
            'chainusertype' => 12273,
            'chainpostinput' => $postid,
        ), array(), 1) as $chain) {

            //Now fetch existing data from cache table:
            foreach ($this->Posts->read(array(
                'postid' => $postid,
                'postvoid >=' => 0,
            ), 1) as $cache) {

                //Validate that something has changed:
                $must_update_chain = 0;
                $must_update_cache = 0;
                $affected_rows = 0;

                foreach ($update_columns as $key => $value) {

                    if ($cache[$key] === $value) {

                        //its the same so remove it:
                        continue;

                    } elseif (in_array($key, $core_fields)) {

                        if($must_update_chain){
                            continue; //Only need to run through this once...
                        }

                        //We only do it once:
                        $must_update_chain = 1;

                        $new_posthashtag = trim( isset($update_columns['posthashtag']) ? $update_columns['posthashtag'] : $cache['posthashtag'] );
                        $new_posttext = trim( isset($update_columns['posttext']) ? $update_columns['posttext'] : $cache['posttext'] );
                        $post_index = post_index($new_posttext, $postid, $chainusercreator, $new_posthashtag);

                        if($new_posttext!=trim($cache['posttext'])){
                            $update_columns['posttext'] = $post_index['posttext'];
                            $update_columns['postdiscover'] = $post_index['postdiscover'];
                            $update_columns['postedit'] = $post_index['postedit'];
                        }

                        $update_columns['posttime'] = date("Y-m-d H:i:s");

                        //Update Cache:
                        $this->db->where('postid', $postid);
                        $this->db->update('posts', $update_columns);
                        $affected_rows = $this->db->affected_rows();

                        //Update Chain:
                        $this->Chains->update($chain['chainid'], array(
                            'chainvalue' => "#" . $new_posthashtag
                                . "\n" . $post_index['chainvalue']
                        ), $chainusercreator);


                        //Update term on all references
                        foreach ($this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___4486')) . ')' => null, //Ideas
                            'chainpostoutput' => $postid,
                        ), array('chainpostinput'), 0) as $ref) {
                            //Update the post index:
                            $post_index = post_index($ref['posttext'], $ref['postid'], $chainusercreator, $ref['posthashtag'], $value);
                        }

                        //Sync algolia:
                        update_algolia(12274, intval($postid));

                    } else {

                        if($must_update_cache){
                            continue;
                        }

                        $must_update_cache = 1;

                        $update_columns_cache = $update_columns;
                        foreach($core_fields as $core_field => $core_value){
                            unset($update_columns_cache[$core_value]);
                        }

                        //Update regular field:
                        $this->db->where('postid', $postid);
                        $this->db->update('posts', $update_columns_cache);
                        $affected_rows = $this->db->affected_rows();

                    }
                }

                return $affected_rows;

            }

            log_error('Post #' . $postid . ' not found on cache', $update_columns);
            return 0;

        }


        log_error('Post #' . $postid . ' not found on chain or cache', $update_columns);
        return 0;


        $affected_rows = 0;
        foreach ($posts_found as $post_current) {

            if (isset($update_columns['posttext']) || isset($update_columns['posthashtag'])) {
                //Update Post Text:
                $post_index = post_index($update_columns['posttext'], $postid, $chainusercreator, (isset($update_columns['posthashtag']) ? $update_columns['posthashtag'] : null));
                $update_columns['posttext'] = $post_index['posttext']; //May be updated
                $update_columns['postdiscover'] = $post_index['postdiscover'];
                $update_columns['postedit'] = $post_index['postedit'];
            }

            if (!count($update_columns)) {
                return false;
            }

            //Update:
            $this->db->where('postid', $postid);
            $this->db->update('posts', $update_columns);
            $affected_rows = $this->db->affected_rows();

            //Chain data changed?
            if ((isset($update_columns['posttext']) && $post_index['posttext'] != $post_current['posttext']) || (isset($update_columns['posthashtag']) && $update_columns['posthashtag'] != $post_current['posthashtag'])) {
                //Fetch latest chain:
                foreach ($this->Chains->read(array(
                    'chainusertype' => 12273,
                    'chainpostinput' => $postid,
                ), array(), 0) as $chain_i) {
                    $this->Chains->update($chain_i['chainid'], array(
                        'chainpostinput' => $postid,
                        'chainusercreator' => $chainusercreator,
                        'chainvalue' => '#' . (isset($update_columns['posthashtag']) ? $update_columns['posthashtag'] : $post_current['posthashtag']) . "\n" . $post_index['posttext'],
                    ));
                }
            }

            if (isset($update_columns['posttext']) && $post_index['posttext'] != $post_current['posttext']) {
                //Sync algolia:
                update_algolia(12273, $postid);
            }

        }


    }

    function delete($postid, $chainusercreator = 0, $migrateid = 0)
    {

        if (!count($this->Posts->read(array('postid' => $postid)))) {
            return array(
                'status' => 0,
                'message' => $postid . ' is not a valid ID',
            );
        } elseif ($migrateid > 0 && !count($this->Posts->read(array('postid' => $migrateid)))) {
            return array(
                'status' => 0,
                'message' => $migrateid . ' is not a valid ID',
            );
        }

        $x_adjusted = 0;
        foreach ($this->Chains->read(array(
            'chainusertype' => 12273,
            'chainpostinput' => $postid,
        ), array(), 1) as $delete) {
            $x_adjusted += $this->Chains->delete($delete['chainid'], $chainusercreator);
        }

        if ($x_adjusted) {

            if ($migrateid) {
                foreach ($this->Chains->read(array(
                    '(chainpostoutput = ' . $postid . ' OR chainpostinput = ' . $postid . ')' => null,
                ), array(), 0) as $migrate) {

                    $new_array = array(
                        'chainpostinput' => ($migrate['chainpostinput'] == $postid ? $migrateid : $migrate['chainpostinput']),
                        'chainpostoutput' => ($migrate['chainpostoutput'] == $postid ? $migrateid : $migrate['chainpostoutput']),
                        'chainusercreator' => $migrate['chainusercreator'],
                        'chainuseroutput' => $migrate['chainuseroutput'],
                        'chainuserinput' => $migrate['chainuserinput'],
                        'chainusertype' => $migrate['chainusertype'],
                    );

                    //Update if this new one is unique:
                    if (!count($this->Chains->read($new_array))) {
                        $this->Chains->update($migrate['chainid'], $new_array);
                    }
                }
            }

            //Remove from Table:
            $this->db->where('postid', $postid);
            $this->db->update('posts', array(
                'posttime' => date("Y-m-d H:i:s"),
                'postvoid' => 1,
            ));

        } else {
            //Failed to remove
            log_error('posts->delete() Failed to remove #' . $postid . ' Chain ID', array(
                'chainusercreator' => $chainusercreator,
                'chainpostoutput' => $postid,
                'chainpostinput' => $migrateid,
            ));
        }

        //Return Chains deleted:
        return $x_adjusted;
    }


    function command($postid, $action_userid, $action_command1, $action_command2, $chainusercreator)
    {


        boost_power();

        if (!in_array($action_userid, $this->config->item('userids___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif (in_array($action_userid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && !view_valid_user_user($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown User. Format must be: @UserUser',
            );

        } elseif (in_array($action_userid, array(12611, 12612, 27240, 28801)) && !view_valid_user_post($action_command1)) {

            return array(
                'status' => 0,
                'message' => 'Unknown Post. Format must be: #PostPost',
            );

        }


        //Basic input validation done, let's continue


        //Fetch all followers:
        $applied_success = 0; //To be populated

        $is_next = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
            'chainpostinput' => $postid,
        ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC'));


        //Process request:
        foreach ($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if (in_array($action_userid, array(12591, 12592, 27080, 27985, 27081, 27986, 27082, 27083, 27084, 27085, 27086, 27087)) && view_valid_user_user($action_command1)) {

                //Check if it has this item:
                foreach ($this->Users->read(array(
                    'LOWER(userhandle)' => strtolower(view_valid_user_user($action_command1)),
                )) as $e) {

                    $post_has_e = $this->Chains->read(array(
                        'chainusertype IN (' . join(',', $this->config->item('userids___33602')) . ')' => null, //Post/User Chains Active
                        'chainpostoutput' => $next_i['postid'],
                        'chainuserinput' => $e['userid'],
                    ));

                    if (in_array($action_userid, array(12591, 27080, 27985, 27082, 27084, 27086)) && !count($post_has_e)) {

                        $user_mapper = array(
                            27985 => 27984, //IF Follows Any
                            27082 => 26600, //IF Not Follows All
                            27084 => 7545,  //Following Add
                            27086 => 26599, //Following Remove
                        );

                        //Missing & Must be Added:
                        $this->Chains->create(array(
                            'chainusercreator' => $chainusercreator,
                            'chainuserinput' => $e['userid'],
                            'chainusertype' => $user_mapper[$action_userid],
                            'chainpostoutput' => $next_i['postid'],
                            'chainvalue' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif (in_array($action_userid, array(12592, 27081, 27986, 27083, 27085, 27087)) && count($post_has_e)) {

                        //Has and must be deleted:
                        $this->Chains->delete($post_has_e[0]['chainid'], $chainusercreator);

                        $applied_success++;

                    }
                }

            } elseif (in_array($action_userid, array(12611, 12612, 27240, 28801)) && view_valid_user_post($action_command1)) {

                foreach ($this->Posts->read(array(
                    'LOWER(posthashtag)' => strtolower(view_valid_user_post($action_command1)),
                )) as $i) {

                    if ($action_userid == 27240) {

                        //Copy
                        $result = $this->Posts->copy(intval($_POST['postid']), 0, $action_userid);
                        if ($result['status']) {
                            //Increment User since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->Chains->read(array(
                            'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
                            'chainpostinput' => $i['postid'],
                            'chainpostoutput' => $next_i['postid'],
                        ), array(), 0);


                        //See how to adjust:
                        if (in_array($action_userid, array(12611, 28801)) && !count($is_previous)) {

                            //Chain
                            $status = $this->Posts->chain($i, 4228, $next_i, $chainusercreator);

                            if ($status['status']) {

                                if ($action_userid == 28801) {
                                    //Also remove old chain:
                                    $this->Chains->delete($next_i['chainid'], $chainusercreator);
                                }

                                //Increment User since not there:
                                $applied_success++;
                            }
                        }


                        if ($action_userid == 12612 && count($is_previous)) {
                            //Unchain
                            $this->Chains->delete($is_previous[0]['chainid'], $chainusercreator);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass User edit transaction:
        $this->Chains->create(array(
            'chainusertype' => 44176, //User View
            'chainuserinput' => $action_userid,
            'chainuseroutput' => $chainusercreator,
            'chainusercreator' => $chainusercreator,
            'chainpostoutput' => $postid,
            'chainvalue' => array(
                'payload' => $_POST,
                'post_total' => count($is_next),
                'post_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . ' of ' . count($is_next) . ' posts updated',
        );

    }


    function chain($i, $chainusertype, $next_i, $chainusercreator)
    {

        //Chains posts with the causality chain ensuring not a duplicate:
        if (0 && $chainusertype == 4228 && count($this->Chains->previouspost(0, $next_i['posthashtag'], $i['postid']))) {
            return array(
                'status' => 0,
                'message' => 'Post already added in the inverse direction, so it cannot be added here',
            );
        } elseif (count($this->Chains->read(array(
            'chainpostinput' => $i['postid'],
            'chainusertype' => $chainusertype,
            'chainpostoutput' => $next_i['postid'],
        )))) {
            //Make sure not a duplicate chain:
            return array(
                'status' => 0,
                'message' => 'Post is already chained here',
            );
        }

        //Adding PREVIOUS or NEXT Post from Post
        $this->Chains->create(array(
            'chainusercreator' => $chainusercreator,
            'chainpostinput' => $i['postid'],
            'chainusertype' => $chainusertype,
            'chainpostoutput' => $next_i['postid'],
        ), true);

        //Return result:
        return array(
            'status' => 1,
        );
    }


    function ids($i, $scope, $loop_breaker_ids = array())
    {

        if (!($scope == 'ALL' /* includes both AND and OR posts */ || $scope == 'AND' /* AND posts only */ || $scope == 'OR' /* OR posts only */)) {
            return false;
        }

        if (count($loop_breaker_ids) > 0 && in_array($i['postid'], $loop_breaker_ids)) {
            return array();
        }

        $input__selection = $this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostoutput' => $i['postid'],
            'chainuserinput IN (' . join(',', $this->config->item('userids___7712')) . ')' => null,
        ));

        if ($scope == 'AND' && $input__selection) {
            //OR POST:
            return array();
        }

        $recursive_post_ids = array();
        array_push($loop_breaker_ids, intval($i['postid']));

        foreach ($this->Chains->read(array(
            'chainusertype IN (' . join(',', $this->config->item('userids___42345')) . ')' => null, //Active Sequence
            'chainpostinput' => $i['postid'],
        ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC')) as $next_i) {

            if (!in_array(intval($next_i['postid']), $recursive_post_ids)) {
                if (!($scope == 'OR' && !$input__selection)) {
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_post_ids, intval($next_i['postid']));
                }
            }

            //Add to current array if we found anything:
            $copy = $this->Posts->ids($next_i, $scope, $loop_breaker_ids);
            if (isset($copy['recursive_post_ids'])) {
                foreach ($copy['recursive_post_ids'] as $recursive_post_id) {
                    if (!in_array($recursive_post_id, $recursive_post_ids)) {
                        array_push($recursive_post_ids, $recursive_post_id);
                    }
                }
            }


        }

        return array(
            'recursive_post_ids' => array_unique($recursive_post_ids),
        );

    }

    function copy($postid, $do_recursive, $chainusercreator, $previous_i = null, $clone_title = null)
    {

        //Create Clone -or- Chain & move-on?
        //Validate Post:
        $this_i = $this->Posts->read(array(
            'postid' => $postid,
        ));
        if (count($this_i) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid post ID',
                'post_createid' => 0,
                'post_createpost' => '',
            );
        }

        $post_new = $this->Posts->create(array(
            'posttext' => ($clone_title ? $clone_title : "Copy Of " . $this_i[0]['posttext']),
        ), $chainusercreator);

        return array(
            'status' => 1,
            'post_createid' => $post_new['post_create']['postid'],
            'post_createpost' => $post_new['post_create']['posthashtag'],
        );

    }


}