<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class I_model extends CI_Model
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


    function create($add_fields, $x__player = 14068)
    {

        if(!isset($add_fields['i__type']) || !in_array($add_fields['i__type'], $this->config->item('n___4737'))){
            //Statement is the default idea type:
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
            $this->X_model->create(array(
                'x__message' => 'i->create() failed to create a new idea',
                'x__type' => 4246, //Platform Bug Reports
                'x__player' => $x__player,
                'x__metadata' => $add_fields,
            ));
            return false;
        }

        //Log transaction new Idea hashtag:
        $this->X_model->create(array(
            'x__player' => $x__player,
            'x__next' => $add_fields['i__id'],
            'x__message' => $add_fields['i__hashtag'],
            'x__type' => 42168, //Idea Generated Hashtag
        ));

        //Sync messages:
        $view_sync_links = view_sync_links($add_fields['i__message'], true, $add_fields['i__id']);

        //Fetch to return the complete source data:
        $is = $this->I_model->fetch(array(
            'i__id' => $add_fields['i__id'],
        ));

        //Update Search Index:
        flag_for_search_indexing(12273, $add_fields['i__id']);


        //Additional sources to be added? Start with creator
        $e_appended = array($x__player);
        $pinned_followers = $this->X_model->fetch(array(
            'x__following' => $x__player,
            'x__type' => 41011, //PINNED FOLLOWER
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array('x__follower'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC'));
        $x__type = ( count($pinned_followers) ? 4250 /* 4983 */ : 4250 ); //If it has pinned, they would be primary author...

        //Add if not added as the author:
        if(!count($this->X_model->fetch(array(
            'x__type' => $x__type,
            'x__following' => $x__player,
            'x__next' => $add_fields['i__id'],
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        )))){
            $this->X_model->create(array(
                'x__type' => $x__type,
                'x__player' => $x__player,
                'x__following' => $x__player,
                'x__next' => $add_fields['i__id'],
            ));
        }

        //Also append all pinned followers:
        $x__weight = 0;
        foreach($pinned_followers as $x_pinned) {
            if(!in_array($x_pinned['e__id'], $e_appended) && !count($this->X_model->fetch(array(
                    'x__type' => 4250, //Lead Author
                    'x__following' => $x_pinned['e__id'],
                    'x__next' => $add_fields['i__id'],
                    'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                )))){
                $this->X_model->create(array(
                    'x__type' => 4250, //Lead Author
                    'x__following' => $x_pinned['e__id'],
                    'x__next' => $add_fields['i__id'],
                    'x__player' => $x__player,
                    'x__weight' => $x__weight,
                ));
                array_push($e_appended, $x_pinned['e__id']);
                $x__weight++;
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


    function update($id, $update_columns, $external_sync = false, $x__player = 0, $x__type = 0)
    {

        $id = intval($id);
        if (count($update_columns)==0) {
            return false;
        }

        //Fetch current Idea filed values so we can compare later on after we've updated it:
        if($x__player > 0){
            $before_data = $this->I_model->fetch(array('i__id' => $id));
        }

        //Update:
        $this->db->where('i__id', $id);
        $this->db->update('cache_ideas', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $x__player > 0) {

            //Unlike source modification, we require a member source ID to log the modification transaction:
            //Log modification transaction for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key]==$value){
                    //Nothing changed:
                    continue;
                }

                //Assume no SOURCE LINKS unless specifically defined:
                $x__follower = 0;
                $x__following = 0;


                if($x__type) {

                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='i__hashtag') {

                    $x__type = 41982; //Idea updated Handle
                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='i__message') {

                    $x__type = 10644; //Idea updated Outcome
                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='i__privacy'){

                    $x__type = 41997; //Idea Access Updated
                    $e___31004 = $this->config->item('e___31004'); //Idea Access
                    $x__message = view_db_field($key) . ' updated from [' . $e___31004[$before_data[0][$key]]['m__title'] . '] to [' . $e___31004[$value]['m__title'] . ']';
                    $x__following = $value;
                    $x__follower = $before_data[0][$key];

                } elseif($key=='i__type'){

                    $x__type = 10648; //Idea updated Status
                    $e___4737 = $this->config->item('e___4737'); //Idea Types
                    $x__message = view_db_field($key) . ' updated from [' . $e___4737[$before_data[0][$key]]['m__title'] . '] to [' . $e___4737[$value]['m__title'] . ']';
                    $x__following = $value;
                    $x__follower = $before_data[0][$key];

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }

                //Value has changed, log transaction:
                $this->X_model->create(array(
                    'x__player' => $x__player,
                    'x__type' => $x__type,
                    'x__next' => $id,
                    'x__follower' => $x__follower,
                    'x__following' => $x__following,
                    'x__message' => $x__message,
                    'x__metadata' => array(
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
            $this->X_model->create(array(
                'x__next' => $id,
                'x__type' => 4246, //Platform Bug Reports
                'x__player' => $x__player,
                'x__message' => 'update() Failed to update',
                'x__metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function remove($i__id, $x__player = 0, $migrate_s__id = 0){

        $x_adjusted = 0;
        if($migrate_s__id){

            //Migrate Transactions:
            $this->db->query("UPDATE mench_ledger SET x__next=".$migrate_s__id." WHERE x__next=".$i__id.";");
            $affected_x__next = $this->db->affected_rows();
            $x_adjusted += $affected_x__next;
            $this->db->query("UPDATE mench_ledger SET x__previous=".$migrate_s__id." WHERE x__previous=".$i__id.";");
            $affected_x__previous = $this->db->affected_rows();
            $x_adjusted += $affected_x__previous;

            $player_e = superpower_unlocked();
            $this->X_model->create(array(
                'x__player' => ($x__player > 0 ? $x__player : $player_e['e__id'] ),
                'x__type' => 26785, //idea Link Migrated
                'x__previous' => $migrate_s__id,
                'x__metadata' => array(
                    'migrated_links' => array(
                        'x__next' => $affected_x__next,
                        'x__previous' => $affected_x__previous,
                    ),
                    'old_idea_id' => $i__id,
                ),
            ));

        } else {

            //REMOVE TRANSACTIONS
            foreach($this->X_model->fetch(array( //Idea Transactions
                'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type !=' => 13579, //Idea Transaction Unpublished
                '(x__next = '.$i__id.' OR x__previous = '.$i__id.')' => null,
            ), array(), 0) as $x){
                //Delete this transaction:
                $x_adjusted += $this->X_model->update($x['x__id'], array(
                    'x__privacy' => 6173, //Transaction Deleted
                ), $x__player, 13579 /* Idea Transaction Unpublished */);
            }

        }

        //Return transactions deleted:
        return $x_adjusted;
    }






    function duplicate($i, $copy_to__id, $x__player)
    {

        $i_new = $this->I_model->create(array(
            'i__message' => $i['i__message'],
            'i__type' => $i['i__type'],
        ), $x__player);

        //Copy related transactions:
        $links = 0;
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___27240')) . ')' => null, //COPY Transactions
            '(x__next='.$i['i__id'].' OR x__previous='.$i['i__id'].')' => null,
        ), array(), 0) as $x){

            //Duplicate transaction, with new idea
            if(!count($this->X_model->fetch(array(
                'x__type' => $x['x__type'],
                'x__metadata' => $x['x__metadata'],
                'x__message' => $x['x__message'],
                'x__following' => $x['x__following'],
                'x__follower' => $x['x__follower'],
                'x__previous' => ( $i['i__id']==$x['x__previous'] ? $i_new['i__id'] : $x['x__previous'] ),
                'x__next' => ( $i['i__id']==$x['x__next'] ? $i_new['i__id'] : $x['x__next'] ),
            )))){
                $links++;
                $this->X_model->create(array(
                    //Copy:
                    'x__type' => $x['x__type'],
                    'x__privacy' => $x['x__privacy'],
                    'x__weight' => $x['x__weight'],
                    'x__message' => $x['x__message'],
                    'x__metadata' => $x['x__metadata'],
                    'x__following' => $x['x__following'],
                    'x__follower' => $x['x__follower'],
                    'x__reference' => $x['x__reference'],
                    //Change:
                    'x__player' => $x__player,
                    'x__previous' => ( $i['i__id']==$x['x__previous'] ? $i_new['i__id'] : $x['x__previous'] ),
                    'x__next' => ( $i['i__id']==$x['x__next'] ? $i_new['i__id'] : $x['x__next'] ),
                ));
            }

        }

        return $links;

    }


    function i_link($i, $x__type, $next_i, $x__player){

        //Links ideas with the causality link ensuring not a duplicate:
        if(0 && $x__type==4228 && count($this->X_model->find_previous(0, $next_i['i__hashtag'], $i['i__id']))){
            return array(
                'status' => 0,
                'message' => 'Idea already added in the inverse direction, so it cannot be added here',
            );
        } elseif(count($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__previous' => $i['i__id'],
            'x__type' => $x__type,
            'x__next' => $next_i['i__id'],
        )))){
            //Make sure not a duplicate link:
            return array(
                'status' => 0,
                'message' => 'Idea is already linked here',
            );
        }

        //Adding PREVIOUS or NEXT Idea from Idea
        $this->X_model->create(array(
            'x__player' => $x__player,
            'x__previous' => $i['i__id'],
            'x__type' => $x__type,
            'x__next' => $next_i['i__id'],
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

        foreach($this->X_model->fetch(array(
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'x__previous' => $i['i__id'],
        ), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $next_i){

            if(!in_array(intval($next_i['i__id']), $recursive_i_ids)){
                if(!($scope=='OR' && !$input__selection)){
                    //We add it at all times unless scope is OR and node is not OR
                    array_push($recursive_i_ids, intval($next_i['i__id']));
                }
            }

            //Add to current array if we found anything:
            $recursive_down_ids = $this->I_model->recursive_down_ids($next_i, $scope, $loop_breaker_ids);
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

    function recursive_clone($i__id, $do_recursive, $x__player, $previous_i = null, $clone_title = null) {

        //Create Clone -or- Link & move-on?
        //Validate Idea:
        $this_i = $this->I_model->fetch(array(
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

        $i_new = $this->I_model->create(array(
            'i__message' => ( $clone_title ? $clone_title : "Copy Of ".$this_i[0]['i__message'] ),
            'i__type' => $this_i[0]['i__type'],
        ), $x__player);

        //Always Link Sources:
        $filters = array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___41302')) . ')' => null, //Clone Idea Source Links
            'x__next' => $i__id,
        );

        foreach($this->X_model->fetch($filters, array(), 0) as $x){
            $this->X_model->create(array(
                'x__player' => $x__player,
                'x__type' => $x['x__type'],
                'x__next' => $i_new['i__id'],
                'x__following' => $x['x__following'],
                'x__follower' => $x['x__follower'],
                'x__previous' => $x['x__previous'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__privacy' => $x['x__privacy'],
            ));
        }


        //Always Link Followings:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___41301')) . ')' => null, //Duplicate Links
            'x__next' => $i__id,
        ), array('x__previous'), 0) as $x){
            $this->X_model->create(array(
                'x__player' => $x__player,
                'x__type' => $x['x__type'],
                'x__next' => $i_new['i__id'],
                'x__previous' => $x['i__id'],
                'x__message' => $x['x__message'],
                'x__weight' => $x['x__weight'],
                'x__reference' => $x['x__reference'],
                'x__metadata' => $x['x__metadata'],
                'x__privacy' => $x['x__privacy'],
            ));
        }


        //Fetch followers:
        foreach($this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___41301')) . ')' => null, //Duplicate Links
            'x__previous' => $i__id,
        ), array('x__next'), 0) as $x){

            if($do_recursive && !count($this->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__next' => $i__id,
                    'x__following' => 42208, //No-Clone Idea
                )))){
                //Clone Followers Recursively:
                $this->I_model->recursive_clone($x['i__id'], $do_recursive, $x__player, $this_i[0]);
            } else {
                //Link Followers:
                $this->X_model->create(array(
                    'x__player' => $x__player,
                    'x__type' => $x['x__type'],
                    'x__previous' => $i_new['i__id'],
                    'x__next' => $x['i__id'],
                    'x__message' => $x['x__message'],
                    'x__weight' => $x['x__weight'],
                    'x__reference' => $x['x__reference'],
                    'x__metadata' => $x['x__metadata'],
                    'x__privacy' => $x['x__privacy'],
                ));
            }
        }

        return array(
            'status' => 1,
            'new_i__id' => $i_new['i__id'],
            'new_i__hashtag' => $i_new['i__hashtag'],
        );

    }




   function mass_update($i__id, $action_e__id, $action_command1, $action_command2, $x__player)
    {

        //Alert: Has a twin function called e_mass_update()

        boost_power();

        if(!in_array($action_e__id, $this->config->item('n___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087)) && !view_valid_handle_e($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @SourceHandle',
            );

        } elseif(in_array($action_e__id , array(12611,12612,27240,28801)) && !view_valid_handle_i($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #IdeaHashtag',
            );

        }



        //Basic input validation done, let's continue


        //Fetch all followers:
        $applied_success = 0; //To be populated

        $is_next = $this->X_model->fetch(array(
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $this->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'x__previous' => $i__id,
        ), array('x__next'), 0, 0, array('x__weight' => 'ASC'));


        //Process request:
        foreach($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087)) && view_valid_handle_e($action_command1)){

                //Check if it has this item:
                foreach($this->E_model->fetch(array(
                    'LOWER(e__handle)' => strtolower(view_valid_handle_e($action_command1)),
                )) as $e){

                    $i_has_e = $this->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'x__next' => $next_i['i__id'],
                        'x__following' => $e['e__id'],
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
                        $this->X_model->create(array(
                            'x__player' => $x__player,
                            'x__following' => $e['e__id'],
                            'x__type' => $e_mapper[$action_e__id],
                            'x__next' => $next_i['i__id'],
                            'x__message' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif(in_array($action_e__id , array(12592,27081,27986,27083,27085,27087)) && count($i_has_e)){

                        //Has and must be deleted:
                        $this->X_model->update($i_has_e[0]['x__id'], array(
                            'x__privacy' => 6173,
                        ), $x__player, 10673 /* IDEA NOTES Unpublished */);

                        $applied_success++;

                    }
                }

            } elseif(in_array($action_e__id , array(12611,12612,27240,28801)) && view_valid_handle_i($action_command1)){

                foreach($this->I_model->fetch(array(
                    'LOWER(i__hashtag)' => strtolower(view_valid_handle_i($action_command1)),
                )) as $i){

                    if($action_e__id==27240){

                        //Copy
                        $link_count = $this->I_model->duplicate($next_i, $i['i__id'], $x__player);

                        if($link_count > 0){
                            //Increment Source since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->X_model->fetch(array(
                            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                            'x__type IN (' . join(',', $this->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                            'x__previous' => $i['i__id'],
                            'x__next' => $next_i['i__id'],
                        ), array(), 0);


                        //See how to adjust:
                        if(in_array($action_e__id, array(12611, 28801)) && !count($is_previous)){

                            //Link
                            $status = $this->I_model->i_link($i, 4228, $next_i, $x__player);

                            if($status['status']){

                                if($action_e__id==28801){
                                    //Also remove old link:
                                    $this->X_model->update($next_i['x__id'], array(
                                        'x__privacy' => 6173, //Transaction Deleted
                                    ), $x__player, 10673 /* Member Transaction Unpublished  */);
                                }

                                //Increment Source since not there:
                                $applied_success++;
                            }
                        }


                        if($action_e__id==12612 && count($is_previous)){
                            //Unlink
                            $this->X_model->update($is_previous[0]['x__id'], array(
                                'x__privacy' => 6173,
                            ), $x__player, 13579 /* IDEA NOTES Unpublished */);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass source edit transaction:
        $this->X_model->create(array(
            'x__player' => $x__player,
            'x__type' => $action_e__id,
            'x__next' => $i__id,
            'x__metadata' => array(
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