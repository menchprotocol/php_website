<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hashtags extends CIdea_cache
{

    function __construct()
    {
        parent::__construct();
    }

    function create($add_fields, $chainhandlecreator = 14068 /* GUEST */)
    {

        if(!isset($add_fields['hashtagtext'])){
            return false;
        }

        //hashtag term
        if (!isset($add_fields['hashtagterm']) || strlen($add_fields['hashtagterm'])<3) {
            $add_fields['hashtagterm'] = random_string(8);
            //Make sure not existant:
            while (count($this->Hashtags->read(array('hashtagterm' => $add_fields['hashtagterm'])))) {
                $add_fields['hashtagterm'] = random_string(8);
            }
        }


        $nextchainid = 0;
        if(!isset($add_fields['hashtagid'])){
            //Add to ledger:
            $nextchainid = nextchainid();
            $new_x = $this->Chains->create(array(
                'chainhandletype' => 12273,
                'chainhandlecreator' => $chainhandlecreator,
                'chainhandleinput' => $chainhandlecreator,
                'chainhashtagoutput' => $nextchainid,
                'chainvalue' => "#".$add_fields['hashtagterm']."\n".$add_fields['hashtagtext'],
            ));
        } else {
            $new_x['chainid'] = $add_fields['hashtagid'];
        }
        if (!$new_x['chainid']) {
            log_error('Failed to create in ledger', $add_fields);
            return false;
        } elseif($nextchainid>0 && $nextchainid!=$new_x['chainid']) {
            //Something went wrong, update:
            $this->Chains->update($new_x['chainid'], array(
                'chainhashtagoutput' => $new_x['chainid'],
            ));
        }


        //Save Hashtag
        $add_fields['hashtagid'] = $new_x['chainid'];
        $hashtag_cache = hashtag_cache($add_fields['hashtagid'], $add_fields['hashtagtext'], $chainhandlecreator);
        $add_fields['hashtagtext'] = $hashtag_cache['hashtagtext'];
        $add_fields['hashtagdiscover'] = $hashtag_cache['hashtagdiscover'];
        $add_fields['hashtagedit'] = $hashtag_cache['hashtagedit'];
        if (!count($this->Hashtags->read(array('hashtagid' => $add_fields['hashtagid'])))) {
            $this->db->insert('ideachainhashtags', $add_fields);
        } else {
            $this->Hashtags->update($add_fields['hashtagid'], $add_fields);
        }

        //Update Search Index:
        update_algolia(12273, $add_fields['hashtagid']);

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
                if (!hashtag_access($value['hashtagterm'], 0, $value)) {
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
        } elseif (!count($this->Chains->read(array(
            'chainid' => $chainid,
            'chainvoid >=' => 0, //Any chain
        )))) {
            log_error('Hashtag #' . $chainid . ' not found in Chains table');
            return false;
        }

        $affected_rows = 0;
        foreach ($hashtags_found as $hashtag_current) {

            if (isset($update_columns['hashtagtext']) || isset($update_columns['hashtagterm'])) {
                //Update Hashtag Text:
                $hashtag_cache = hashtag_cache($chainid, $update_columns['hashtagtext'], $chainhandlecreator);
                $update_columns['hashtagtext'] = $hashtag_cache['hashtagtext']; //May be updated
                $update_columns['hashtagdiscover'] = $hashtag_cache['hashtagdiscover'];
                $update_columns['hashtagedit'] = $hashtag_cache['hashtagedit'];
            }

            if (!count($update_columns)) {
                return false;
            }

            //Update:
            $this->db->where('hashtagid', $chainid);
            $this->db->update('ideachainhashtags', $update_columns);
            $affected_rows = $this->db->affected_rows();

            //Chain data changed?
            if((isset($update_columns['hashtagtext']) && $hashtag_cache['hashtagtext']!=$hashtag_current['hashtagtext']) || (isset($update_columns['hashtagterm']) && $update_columns['hashtagterm']!=$hashtag_current['hashtagterm'])){
                //Fetch latest chain:
                foreach($this->Chains->read(array(
                    'chainhandletype' => 12273,
                    '(chainid='.$chainid.' OR chainhashtaginput='.$chainid.')' => null, //Either original or updates
                ), array(), 0) as $chain_i){
                    $this->Chains->update($chain_i['chainid'], array(
                        'chainhashtaginput' => $chainid,
                        'chainhandlecreator' => $chainhandlecreator,
                        'chainvalue' => '#'.( isset($update_columns['hashtagterm']) ? $update_columns['hashtagterm'] : $hashtag_current['hashtagterm'] )."\n".$hashtag_cache['hashtagtext'],
                    ));
                }
            }

            if(isset($update_columns['hashtagtext']) && $hashtag_cache['hashtagtext']!=$hashtag_current['hashtagtext']){
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
                    'LOWER(handleterm)' => strtolower(view_valid_handle_handle($action_command1)),
                )) as $e) {

                    $hashtag_has_e = $this->Chains->read(array(
                        'chainhandletype IN (' . join(',', $this->config->item('handleids___33602')) . ')' => null, //Hashtag/Handle Chains Active
                        'chainhashtagoutput' => $next_i['hashtagid'],
                        'chainhandleinput' => $e['handleid'],
                    ));

                    if (in_array($action_handleid, array(12591, 27080, 27985, 27082, 27084, 27086)) && !count($hashtag_has_e)) {

                        $handle_mapper = array(
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
                    'LOWER(hashtagterm)' => strtolower(view_valid_handle_hashtag($action_command1)),
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
            'chainhandletype' => 44176, //Handle View
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
        if (0 && $chainhandletype == 4228 && count($this->Chains->previoushashtag(0, $next_i['hashtagterm'], $i['hashtagid']))) {
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
            'hashtagtext' => ($clone_title ? $clone_title : "Copy Of " . $this_i[0]['hashtagtext']),
            'hashtagtype' => $this_i[0]['hashtagtype'],
        ), $chainhandlecreator);

        return array(
            'status' => 1,
            'hashtag_createid' => $hashtag_new['hashtag_create']['hashtagid'],
            'hashtag_createhashtag' => $hashtag_new['hashtag_create']['hashtagterm'],
        );

    }


}