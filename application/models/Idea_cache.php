<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Idea_cache extends CIdea_cache
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


    function create($add_fields, $LinkPlayer = 14068)
    {

        if(!isset($add_fields['i__type']) || !in_array($add_fields['i__type'], $this->config->item('n___4737'))){
            //Statement is the default Source Reference:
            $add_fields['i__type'] = 6677;
        }

        //Auto generate a Hashtag if needed:
        if(!isset($add_fields['i__hashtag'])){
            $add_fields['i__hashtag'] = random_string(13);
        }

        //Lets now add:
        $this->db->insert('cache_ideas', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['i__id'])) {
            $add_fields['i__id'] = $this->db->insert_id();
        }

        if (!$add_fields['i__id']) {
            //Ooopsi, something went wrong!
            $this->Mench_ledger->create(array(
                'LinkText' => 'i->create() failed to create a new idea',
                'LinkType' => 4246, //Platform Bug Reports
                'LinkPlayer' => $LinkPlayer,
                'LinkMetadata' => $add_fields,
            ));
            return false;
        }

        //Log transaction new Idea hashtag:
        $this->Mench_ledger->create(array(
            'LinkPlayer' => $LinkPlayer,
            'LinkRight' => $add_fields['i__id'],
            'LinkText' => $add_fields['i__hashtag'],
            'LinkType' => 42168, //Idea Generated Hashtag
        ));

        //Sync messages:
        $view_sync_links = view__sync_links($add_fields['i__message'], true, $add_fields['i__id']);

        //Fetch to return the complete source data:
        $is = $this->Idea_cache->fetch(array(
            'i__id' => $add_fields['i__id'],
        ));

        //Update Search Index:
        flag_for_search_indexing(12273, $add_fields['i__id']);


        //Additional sources to be added? Start with creator
        $e_appended = array($LinkPlayer);
        $pinned_followers = $this->Mench_ledger->fetch(array(
            'LinkUp' => $LinkPlayer,
            'LinkType' => 41011, //PINNED FOLLOWER
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array('LinkDown'), 0, 0, array('LinkNumber' => 'ASC', 'LinkId' => 'DESC'));
        $LinkType = ( count($pinned_followers) ? 4250 /* 4983 */ : 4250 ); //If it has pinned, they would be primary author...

        //Add if not added as the author:
        if(!count($this->Mench_ledger->fetch(array(
            'LinkType' => $LinkType,
            'LinkUp' => $LinkPlayer,
            'LinkRight' => $add_fields['i__id'],
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )))){
            $this->Mench_ledger->create(array(
                'LinkType' => $LinkType,
                'LinkPlayer' => $LinkPlayer,
                'LinkUp' => $LinkPlayer,
                'LinkRight' => $add_fields['i__id'],
            ));
        }

        //Also append all pinned followers:
        $LinkNumber = 0;
        foreach($pinned_followers as $x_pinned) {
            if(!in_array($x_pinned['e__id'], $e_appended) && !count($this->Mench_ledger->fetch(array(
                    'LinkType' => 4250, //Lead Author
                    'LinkUp' => $x_pinned['e__id'],
                    'LinkRight' => $add_fields['i__id'],
                    'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                )))){
                $this->Mench_ledger->create(array(
                    'LinkType' => 4250, //Lead Author
                    'LinkUp' => $x_pinned['e__id'],
                    'LinkRight' => $add_fields['i__id'],
                    'LinkPlayer' => $LinkPlayer,
                    'LinkNumber' => $LinkNumber,
                ));
                array_push($e_appended, $x_pinned['e__id']);
                $LinkNumber++;
            }
        }

        return $is[0];

    }

    function fetch($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Ideas
        $this->db->select($select);
        $this->db->from('cache_ideas');

        foreach($query_filters as $key => $value) {
            $this->db->where($key, $value);
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }
        if (count($order_columns) > 0) {
            foreach($order_columns as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        $results = $q->result_array();

        //Make sure user has access to each item:
        if($select=='*' && 0){
            foreach($results as $key => $value){
                if(!access_level_i($value['i__hashtag'], 0, $value)){
                    unset($results[$key]); //Remove this option
                }
            }
        }


        return $results;


    }


    function update($id, $update_columns, $external_sync = false, $LinkPlayer = 0, $LinkType = 0)
    {

        $id = intval($id);
        if (count($update_columns)==0) {
            return false;
        }

        //Fetch current Idea filed values so we can compare later on after we've updated it:
        if($LinkPlayer > 0){
            $before_data = $this->Idea_cache->fetch(array('i__id' => $id));
        }

        //Update:
        $this->db->where('i__id', $id);
        $this->db->update('cache_ideas', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $LinkPlayer > 0) {

            //Unlike source modification, we require a member source ID to log the modification transaction:
            //Log modification transaction for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key]==$value){
                    //Nothing changed:
                    continue;
                }

                //Assume no SOURCE LINKS unless specifically defined:
                $LinkDown = 0;
                $LinkUp = 0;


                if($LinkType) {

                    $LinkText = update_description($before_data[0][$key], $value);

                } elseif($key=='i__hashtag') {

                    $LinkType = 41982; //Idea updated Handle
                    $LinkText = update_description($before_data[0][$key], $value);

                } elseif($key=='i__message') {

                    $LinkType = 10644; //Idea updated Outcome
                    $LinkText = update_description($before_data[0][$key], $value);

                } elseif($key=='i__privacy'){

                    $LinkType = 41997; //Idea Access Updated
                    $e___31004 = $this->config->item('e___31004'); //Idea Access
                    $LinkText = view__db_field($key) . ' updated from [' . $e___31004[$before_data[0][$key]]['m__title'] . '] to [' . $e___31004[$value]['m__title'] . ']';
                    $LinkUp = $value;
                    $LinkDown = $before_data[0][$key];

                } elseif($key=='i__type'){

                    $LinkType = 10648; //Idea updated Status
                    $e___4737 = $this->config->item('e___4737'); //Source References
                    $LinkText = view__db_field($key) . ' updated from [' . $e___4737[$before_data[0][$key]]['m__title'] . '] to [' . $e___4737[$value]['m__title'] . ']';
                    $LinkUp = $value;
                    $LinkDown = $before_data[0][$key];

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log transaction:
                $this->Mench_ledger->create(array(
                    'LinkPlayer' => $LinkPlayer,
                    'LinkType' => $LinkType,
                    'LinkRight' => $id,
                    'LinkDown' => $LinkDown,
                    'LinkUp' => $LinkUp,
                    'LinkText' => $LinkText,
                    'LinkMetadata' => array(
                        'i__id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

            if($external_sync){
                //Sync algolia:
                flag_for_search_indexing(12273, $id);
            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->Mench_ledger->create(array(
                'LinkRight' => $id,
                'LinkType' => 4246, //Platform Bug Reports
                'LinkPlayer' => $LinkPlayer,
                'LinkText' => 'update() Failed to update',
                'LinkMetadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function remove($i__id, $LinkPlayer = 0, $migrate_s__id = 0){

        $x_adjusted = 0;
        if($migrate_s__id){

            //Migrate Transactions:
            $this->db->query("UPDATE mench_ledger SET LinkRight=".$migrate_s__id." WHERE LinkRight=".$i__id.";");
            $affected_LinkRight = $this->db->affected_rows();
            $x_adjusted += $affected_LinkRight;
            $this->db->query("UPDATE mench_ledger SET LinkLeft=".$migrate_s__id." WHERE LinkLeft=".$i__id.";");
            $affected_LinkLeft = $this->db->affected_rows();
            $x_adjusted += $affected_LinkLeft;

            $player_e = superpower_unlocked();
            $this->Mench_ledger->create(array(
                'LinkPlayer' => ($LinkPlayer > 0 ? $LinkPlayer : $player_e['e__id'] ),
                'LinkType' => 26785, //idea Link Migrated
                'LinkLeft' => $migrate_s__id,
                'LinkMetadata' => array(
                    'migrated_links' => array(
                        'LinkRight' => $affected_LinkRight,
                        'LinkLeft' => $affected_LinkLeft,
                    ),
                    'old_idea_id' => $i__id,
                ),
            ));

        } else {

            //REMOVE TRANSACTIONS
            foreach($this->Mench_ledger->fetch(array( //Idea Transactions
                'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'LinkType !=' => 13579, //Idea Transaction Unpublished
                '(LinkRight = '.$i__id.' OR LinkLeft = '.$i__id.')' => null,
            ), array(), 0) as $x){
                //Delete this transaction:
                $x_adjusted += $this->Mench_ledger->update($x['LinkId'], array(
                    'LinkPrivacy' => 6173, //Transaction Deleted
                ), $LinkPlayer, 13579 /* Idea Transaction Unpublished */);
            }

        }

        //Return transactions deleted:
        return $x_adjusted;
    }






    function duplicate($i, $copy_to__id, $LinkPlayer)
    {

        $i_new = $this->Idea_cache->create(array(
            'i__message' => $i['i__message'],
            'i__type' => $i['i__type'],
        ), $LinkPlayer);

        //Copy related transactions:
        $links = 0;
        foreach($this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'LinkType IN (' . join(',', $this->config->item('n___27240')) . ')' => null, //COPY Transactions
            '(LinkRight='.$i['i__id'].' OR LinkLeft='.$i['i__id'].')' => null,
        ), array(), 0) as $x){

            //Duplicate transaction, with new idea
            if(!count($this->Mench_ledger->fetch(array(
                'LinkType' => $x['LinkType'],
                'LinkMetadata' => $x['LinkMetadata'],
                'LinkText' => $x['LinkText'],
                'LinkUp' => $x['LinkUp'],
                'LinkDown' => $x['LinkDown'],
                'LinkLeft' => ( $i['i__id']==$x['LinkLeft'] ? $i_new['i__id'] : $x['LinkLeft'] ),
                'LinkRight' => ( $i['i__id']==$x['LinkRight'] ? $i_new['i__id'] : $x['LinkRight'] ),
            )))){
                $links++;
                $this->Mench_ledger->create(array(
                    //Copy:
                    'LinkType' => $x['LinkType'],
                    'LinkPrivacy' => $x['LinkPrivacy'],
                    'LinkNumber' => $x['LinkNumber'],
                    'LinkText' => $x['LinkText'],
                    'LinkMetadata' => $x['LinkMetadata'],
                    'LinkUp' => $x['LinkUp'],
                    'LinkDown' => $x['LinkDown'],
                    'LinkReference' => $x['LinkReference'],
                    //Change:
                    'LinkPlayer' => $LinkPlayer,
                    'LinkLeft' => ( $i['i__id']==$x['LinkLeft'] ? $i_new['i__id'] : $x['LinkLeft'] ),
                    'LinkRight' => ( $i['i__id']==$x['LinkRight'] ? $i_new['i__id'] : $x['LinkRight'] ),
                ));
            }

        }

        return $links;

    }


    function i_link($i, $LinkType, $next_i, $LinkPlayer){

        //Links ideas with the causality link ensuring not a duplicate:
        if(0 && $LinkType==4228 && count($this->Mench_ledger->find_previous(0, $next_i['i__hashtag'], $i['i__id']))){
            return array(
                'status' => 0,
                'message' => 'Idea already added in the inverse direction, so it cannot be added here',
            );
        } elseif(count($this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'LinkLeft' => $i['i__id'],
            'LinkType' => $LinkType,
            'LinkRight' => $next_i['i__id'],
        )))){
            //Make sure not a duplicate link:
            return array(
                'status' => 0,
                'message' => 'Idea is already linked here',
            );
        }

        //Adding PREVIOUS or NEXT Idea from Idea
        $this->Mench_ledger->create(array(
            'LinkPlayer' => $LinkPlayer,
            'LinkLeft' => $i['i__id'],
            'LinkType' => $LinkType,
            'LinkRight' => $next_i['i__id'],
        ), true);

        //Return result:
        return array(
            'status' => 1,
        );
    }



    function recursive_down_ids($i, $scope, $loop_breaker_ids = array()){

        /*
         *
         * $fetch can be either:
         * - ALL includes both AND and OR ideas
         * - AND ideas only
         * - OR ideas only
         * */

        if(!($scope=='ALL' || $scope=='AND' || $scope=='OR')){
            return false;
        }

        if(count($loop_breaker_ids)>0 && in_array($i['i__id'], $loop_breaker_ids)){
            return array();
        }

        $input__selection = in_array($i['i__type'], $this->config->item('n___7712'));
        if($scope=='AND' && $input__selection){
            //OR IDEA:
            return array();
        }

        $recursive_i_ids = array();
        array_push($loop_breaker_ids, intval($i['i__id']));

        foreach($this->Mench_ledger->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'LinkType IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'LinkLeft' => $i['i__id'],
        ), array('LinkRight'), 0, 0, array('LinkNumber' => 'ASC')) as $next_i){

            if(!in_array(intval($next_i['i__id']), $recursive_i_ids)){
                if(!($scope=='OR' && !$input__selection)){
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_i_ids, intval($next_i['i__id']));
                }
            }

            //Add to current array if we found anything:
            $recursive_down_ids = $this->Idea_cache->recursive_down_ids($next_i, $scope, $loop_breaker_ids);
            if(isset($recursive_down_ids['recursive_i_ids'])){
                foreach($recursive_down_ids['recursive_i_ids'] as $recursive_i_id){
                    if(!in_array($recursive_i_id, $recursive_i_ids)){
                        array_push($recursive_i_ids, $recursive_i_id);
                    }
                }
            }


        }

        return array(
            'recursive_i_ids' => array_unique($recursive_i_ids),
        );

    }

    function recursive_clone($i__id, $do_recursive, $LinkPlayer, $previous_i = null, $clone_title = null) {

        //Create Clone -or- Link & move-on?
        //Validate Idea:
        $this_i = $this->Idea_cache->fetch(array(
            'i__id' => $i__id,
        ));
        if (count($this_i) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
                'new_i__id' => 0,
                'new_i__hashtag' => '',
            );
        }

        $i_new = $this->Idea_cache->create(array(
            'i__message' => ( $clone_title ? $clone_title : "Copy Of ".$this_i[0]['i__message'] ),
            'i__type' => $this_i[0]['i__type'],
        ), $LinkPlayer);

        //Always Link Sources:
        $filters = array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'LinkType IN (' . join(',', $this->config->item('n___41302')) . ')' => null, //Clone Idea Source Links
            'LinkRight' => $i__id,
        );

        foreach($this->Mench_ledger->fetch($filters, array(), 0) as $x){
            $this->Mench_ledger->create(array(
                'LinkPlayer' => $LinkPlayer,
                'LinkType' => $x['LinkType'],
                'LinkRight' => $i_new['i__id'],
                'LinkUp' => $x['LinkUp'],
                'LinkDown' => $x['LinkDown'],
                'LinkLeft' => $x['LinkLeft'],
                'LinkText' => $x['LinkText'],
                'LinkNumber' => $x['LinkNumber'],
                'LinkReference' => $x['LinkReference'],
                'LinkMetadata' => $x['LinkMetadata'],
                'LinkPrivacy' => $x['LinkPrivacy'],
            ));
        }


        //Always Link Followings:
        foreach($this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'LinkType IN (' . join(',', $this->config->item('n___41301')) . ')' => null, //Duplicate Links
            'LinkRight' => $i__id,
        ), array('LinkLeft'), 0) as $x){
            $this->Mench_ledger->create(array(
                'LinkPlayer' => $LinkPlayer,
                'LinkType' => $x['LinkType'],
                'LinkRight' => $i_new['i__id'],
                'LinkLeft' => $x['i__id'],
                'LinkText' => $x['LinkText'],
                'LinkNumber' => $x['LinkNumber'],
                'LinkReference' => $x['LinkReference'],
                'LinkMetadata' => $x['LinkMetadata'],
                'LinkPrivacy' => $x['LinkPrivacy'],
            ));
        }


        //Fetch followers:
        foreach($this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'LinkType IN (' . join(',', $this->config->item('n___41301')) . ')' => null, //Duplicate Links
            'LinkLeft' => $i__id,
        ), array('LinkRight'), 0) as $x){

            if($do_recursive && !count($this->Mench_ledger->fetch(array(
                    'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'LinkType IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'LinkRight' => $i__id,
                    'LinkUp' => 42208, //No-Clone Idea
                )))){
                //Clone Followers Recursively:
                $this->Idea_cache->recursive_clone($x['i__id'], $do_recursive, $LinkPlayer, $this_i[0]);
            } else {
                //Link Followers:
                $this->Mench_ledger->create(array(
                    'LinkPlayer' => $LinkPlayer,
                    'LinkType' => $x['LinkType'],
                    'LinkLeft' => $i_new['i__id'],
                    'LinkRight' => $x['i__id'],
                    'LinkText' => $x['LinkText'],
                    'LinkNumber' => $x['LinkNumber'],
                    'LinkReference' => $x['LinkReference'],
                    'LinkMetadata' => $x['LinkMetadata'],
                    'LinkPrivacy' => $x['LinkPrivacy'],
                ));
            }
        }

        return array(
            'status' => 1,
            'new_i__id' => $i_new['i__id'],
            'new_i__hashtag' => $i_new['i__hashtag'],
        );

    }




   function mass_update($i__id, $action_e__id, $action_command1, $action_command2, $LinkPlayer)
    {

        //Alert: Has a twin function called e_mass_update()

        boost_power();

        if(!in_array($action_e__id, $this->config->item('n___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087)) && !view__valid_handle_e($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @SourceHandle',
            );

        } elseif(in_array($action_e__id , array(12611,12612,27240,28801)) && !view__valid_handle_i($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #IdeaHashtag',
            );

        }



        //Basic input validation done, let's continue


        //Fetch all followers:
        $applied_success = 0; //To be populated

        $is_next = $this->Mench_ledger->fetch(array(
            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'LinkType IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'LinkLeft' => $i__id,
        ), array('LinkRight'), 0, 0, array('LinkNumber' => 'ASC'));


        //Process request:
        foreach($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087)) && view__valid_handle_e($action_command1)){

                //Check if it has this item:
                foreach($this->Source_cache->fetch(array(
                    'LOWER(e__handle)' => strtolower(view__valid_handle_e($action_command1)),
                )) as $e){

                    $i_has_e = $this->Mench_ledger->fetch(array(
                        'LinkPrivacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'LinkType IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'LinkRight' => $next_i['i__id'],
                        'LinkUp' => $e['e__id'],
                    ));

                    if(in_array($action_e__id , array(12591,27080,27985,27082,27084,27086)) && !count($i_has_e)){

                        $e_mapper = array(
                            12591 => 4983,  //Sources
                            27985 => 27984, //Include If Has ANY
                            27082 => 26600, //Exclude If Has ALL
                            27084 => 7545,  //Following Add
                            27086 => 26599, //Following Remove
                        );

                        //Missing & Must be Added:
                        $this->Mench_ledger->create(array(
                            'LinkPlayer' => $LinkPlayer,
                            'LinkUp' => $e['e__id'],
                            'LinkType' => $e_mapper[$action_e__id],
                            'LinkRight' => $next_i['i__id'],
                            'LinkText' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif(in_array($action_e__id , array(12592,27081,27986,27083,27085,27087)) && count($i_has_e)){

                        //Has and must be deleted:
                        $this->Mench_ledger->update($i_has_e[0]['LinkId'], array(
                            'LinkPrivacy' => 6173,
                        ), $LinkPlayer, 10673 /* IDEA NOTES Unpublished */);

                        $applied_success++;

                    }
                }

            } elseif(in_array($action_e__id , array(12611,12612,27240,28801)) && view__valid_handle_i($action_command1)){

                foreach($this->Idea_cache->fetch(array(
                    'LOWER(i__hashtag)' => strtolower(view__valid_handle_i($action_command1)),
                )) as $i){

                    if($action_e__id==27240){

                        //Copy
                        $link_count = $this->Idea_cache->duplicate($next_i, $i['i__id'], $LinkPlayer);

                        if($link_count > 0){
                            //Increment Source since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->Mench_ledger->fetch(array(
                            'LinkPrivacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            'LinkType IN (' . join(',', $this->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                            'LinkLeft' => $i['i__id'],
                            'LinkRight' => $next_i['i__id'],
                        ), array(), 0);


                        //See how to adjust:
                        if(in_array($action_e__id, array(12611, 28801)) && !count($is_previous)){

                            //Link
                            $status = $this->Idea_cache->i_link($i, 4228, $next_i, $LinkPlayer);

                            if($status['status']){

                                if($action_e__id==28801){
                                    //Also remove old link:
                                    $this->Mench_ledger->update($next_i['LinkId'], array(
                                        'LinkPrivacy' => 6173, //Transaction Deleted
                                    ), $LinkPlayer, 10673 /* Member Transaction Unpublished  */);
                                }

                                //Increment Source since not there:
                                $applied_success++;
                            }
                        }


                        if($action_e__id==12612 && count($is_previous)){
                            //Unlink
                            $this->Mench_ledger->update($is_previous[0]['LinkId'], array(
                                'LinkPrivacy' => 6173,
                            ), $LinkPlayer, 13579 /* IDEA NOTES Unpublished */);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass source edit transaction:
        $this->Mench_ledger->create(array(
            'LinkPlayer' => $LinkPlayer,
            'LinkType' => $action_e__id,
            'LinkRight' => $i__id,
            'LinkMetadata' => array(
                'payload' => $_POST,
                'i_total' => count($is_next),
                'i_updated' => $applied_success,
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