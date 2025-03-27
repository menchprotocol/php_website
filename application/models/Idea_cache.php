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


    function create($add_fields, $link_player = 14068)
    {

        return false;
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
                'link_type' => 44179, //Triggered
                'link_up' => 4246, //Platform Bug Reports
                'link_down' => $link_player,
                'link_text' => 'i->create() failed to create a new idea',
                'link_player' => $link_player,
            ));
            return false;
        }

        //Sync messages:
        $view_sync_links = view__sync_links($add_fields['i__message'], true, $add_fields['i__id']);

        //Fetch to return the complete source data:
        $is = $this->Idea_cache->fetch(array(
            'i__id' => $add_fields['i__id'],
        ));

        //Update Search Index:
        flag_for_search_indexing(12273, $add_fields['i__id']);


        //Additional sources to be added? Start with creator
        $e_appended = array($link_player);
        $pinned_followers = $this->Mench_ledger->fetch(array(
            'link_up' => $link_player,
            'link_type' => 41011, //PINNED FOLLOWER
            'link_void' => 0, //Not Void
        ), array('link_down'), 0, 0, array('link_number' => 'ASC', 'link_id' => 'DESC'));

        //Add if not added as the author:
        $this->Mench_ledger->create(array(
            'link_type' => 4250,
            'link_player' => $link_player,
            'link_up' => $link_player,
            'link_right' => $add_fields['i__id'],
        ));

        //Also append all pinned followers:
        $link_number = 0;
        foreach($pinned_followers as $x_pinned) {
            if(!in_array($x_pinned['e__id'], $e_appended) && !count($this->Mench_ledger->fetch(array(
                    'link_type' => 4983, //Idea Created
                    'link_up' => $x_pinned['e__id'],
                    'link_right' => $add_fields['i__id'],
                    'link_void' => 0, //Not Void
                )))){
                $this->Mench_ledger->create(array(
                    'link_type' => 4983, //Idea Created
                    'link_up' => $x_pinned['e__id'],
                    'link_right' => $add_fields['i__id'],
                    'link_player' => $link_player,
                    'link_number' => $link_number,
                ));
                array_push($e_appended, $x_pinned['e__id']);
                $link_number++;
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


    function update($id, $update_columns, $external_sync = false)
    {

        if (count($update_columns)==0) {
            return false;
        }

        //Update:
        $this->db->where('i__id', intval($id));
        $this->db->update('cache_ideas', $update_columns);
        $affected_rows = $this->db->affected_rows();

        if($affected_rows && $external_sync){
            //Sync algolia:
            flag_for_search_indexing(12273, $id);
        }

        return $affected_rows;
    }

    function remove($i__id, $link_player = 0, $migrate_s__id = 0){

        //TODO Needs work
        return false;

        if($migrate_s__id>0){
            $valid_hashtag = $this->Idea_cache->fetch(array(
                'LOWER(i__hashtag)' => $migrate_s__id,
            ));
            if(!count($valid_hashtag)){
                return array(
                    'status' => 0,
                    'message' => '#'.$migrate_s__handle.' is an Invalid Idea hashtag!',
                );
            } elseif($valid_hashtag[0]['i__id']==$o__id){
                return array(
                    'status' => 0,
                    'message' => 'You cannot migrate this idea to itself! Choose a different idea to migrate.',
                );
            }
            $migrate_s__id = $valid_hashtag[0]['i__id'];
        }

        //Determine what to do after deleted:
        if($o__id==$focus__id){

            //Find Published Followings:
            foreach($this->Mench_ledger->fetch(array(
                'link_void' => 0, //Not Void
                'link_type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //IDEA LINKS
                'link_right' => $o__id,
            ), array('link_left'), 1) as $previous_i) {
                $deletion_redirect = view__memory(42903,33286).$previous_i['i__hashtag'];
            }

            //If not found, find active followings:
            if(!$deletion_redirect){
                foreach($this->Mench_ledger->fetch(array(
                    'link_void' => 0, //Not Void
                    'link_type IN (' . join(',', $this->config->item('n___42268')) . ')' => null, //IDEA LINKS
                    'link_right' => $o__id,
                ), array('link_left'), 1) as $previous_i) {
                    $deletion_redirect = view__memory(42903,33286).$previous_i['i__hashtag'];
                }
            }

            //If still not found, go to main page if no followings found:
            if(!$deletion_redirect){
                foreach($this->Idea_cache->fetch(array(
                    'i__id' => $o__id,
                )) as $i){
                    $deletion_redirect = view__memory(42903,33286).$i['i__hashtag'];
                }
            }

        } else {

            //Just delete from UI using JS:
            $delete_element = '.s__12273_' . $o__id;

        }



        $x_adjusted = 0;
        if($migrate_s__id){

            //Migrate Transactions:
            $this->db->query("UPDATE mench_ledger SET link_right=".$migrate_s__id." WHERE link_right=".$i__id.";");
            $affected_link_right = $this->db->affected_rows();
            $x_adjusted += $affected_link_right;
            $this->db->query("UPDATE mench_ledger SET link_left=".$migrate_s__id." WHERE link_left=".$i__id.";");
            $affected_link_left = $this->db->affected_rows();
            $x_adjusted += $affected_link_left;

            $player_e = superpower_unlocked();

        } else {

            //REMOVE TRANSACTIONS
            foreach($this->Mench_ledger->fetch(array( //Idea Transactions
                'link_void' => 0, //Not Void
                '(link_right = '.$i__id.' OR link_left = '.$i__id.')' => null,
            ), array(), 0) as $x){
                //Delete this transaction:
                $x_adjusted += $this->Mench_ledger->update($x['link_id'], array(), $link_player);
            }

        }

        //Delete Idea:
        $this->Idea_cache->update($i__id, array(), $link_player);

        //Update Search Index?
        if(0){
            flag_for_search_indexing(12273,  $o__id);
        }

        //Return transactions deleted:
        return $x_adjusted;
    }






    function duplicate($i, $copy_to__id, $link_player)
    {

        $i_new = $this->Idea_cache->create(array(
            'i__message' => $i['i__message'],
            'i__type' => $i['i__type'],
        ), $link_player);

        //Copy related transactions:
        $links = 0;
        foreach($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___27240')) . ')' => null, //COPY Transactions
            '(link_right='.$i['i__id'].' OR link_left='.$i['i__id'].')' => null,
        ), array(), 0) as $x){

            //Duplicate transaction, with new idea
            if(!count($this->Mench_ledger->fetch(array(
                'link_type' => $x['link_type'],
                'link_text' => $x['link_text'],
                'link_up' => $x['link_up'],
                'link_down' => $x['link_down'],
                'link_left' => ( $i['i__id']==$x['link_left'] ? $i_new['i__id'] : $x['link_left'] ),
                'link_right' => ( $i['i__id']==$x['link_right'] ? $i_new['i__id'] : $x['link_right'] ),
            )))){
                $links++;
                $this->Mench_ledger->create(array(
                    //Copy:
                    'link_type' => $x['link_type'],
                    'link_number' => $x['link_number'],
                    'link_text' => $x['link_text'],
                    'link_up' => $x['link_up'],
                    'link_down' => $x['link_down'],
                    //Change:
                    'link_player' => $link_player,
                    'link_left' => ( $i['i__id']==$x['link_left'] ? $i_new['i__id'] : $x['link_left'] ),
                    'link_right' => ( $i['i__id']==$x['link_right'] ? $i_new['i__id'] : $x['link_right'] ),
                ));
            }

        }

        return $links;

    }


    function i_link($i, $link_type, $next_i, $link_player){

        //Links ideas with the causality link ensuring not a duplicate:
        if(0 && $link_type==4228 && count($this->Mench_ledger->find_previous(0, $next_i['i__hashtag'], $i['i__id']))){
            return array(
                'status' => 0,
                'message' => 'Idea already added in the inverse direction, so it cannot be added here',
            );
        } elseif(count($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_left' => $i['i__id'],
            'link_type' => $link_type,
            'link_right' => $next_i['i__id'],
        )))){
            //Make sure not a duplicate link:
            return array(
                'status' => 0,
                'message' => 'Idea is already linked here',
            );
        }

        //Adding PREVIOUS or NEXT Idea from Idea
        $this->Mench_ledger->create(array(
            'link_player' => $link_player,
            'link_left' => $i['i__id'],
            'link_type' => $link_type,
            'link_right' => $next_i['i__id'],
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
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'link_left' => $i['i__id'],
        ), array('link_right'), 0, 0, array('link_number' => 'ASC')) as $next_i){

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

    function recursive_clone($i__id, $do_recursive, $link_player, $previous_i = null, $clone_title = null) {

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
        ), $link_player);

        //Always Link Sources:
        $filters = array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___41302')) . ')' => null, //Clone Idea Source Links
            'link_right' => $i__id,
        );

        foreach($this->Mench_ledger->fetch($filters, array(), 0) as $x){
            $this->Mench_ledger->create(array(
                'link_player' => $link_player,
                'link_type' => $x['link_type'],
                'link_right' => $i_new['i__id'],
                'link_up' => $x['link_up'],
                'link_down' => $x['link_down'],
                'link_left' => $x['link_left'],
                'link_text' => $x['link_text'],
                'link_number' => $x['link_number'],
            ));
        }


        //Always Link Followings:
        foreach($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___41301')) . ')' => null, //Duplicate Links
            'link_right' => $i__id,
        ), array('link_left'), 0) as $x){
            $this->Mench_ledger->create(array(
                'link_player' => $link_player,
                'link_type' => $x['link_type'],
                'link_right' => $i_new['i__id'],
                'link_left' => $x['i__id'],
                'link_text' => $x['link_text'],
                'link_number' => $x['link_number'],
            ));
        }


        //Fetch followers:
        foreach($this->Mench_ledger->fetch(array(
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___41301')) . ')' => null, //Duplicate Links
            'link_left' => $i__id,
        ), array('link_right'), 0) as $x){

            if($do_recursive && !count($this->Mench_ledger->fetch(array(
                    'link_void' => 0, //Not Void
                    'link_type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'link_right' => $i__id,
                    'link_up' => 42208, //No-Clone Idea
                )))){
                //Clone Followers Recursively:
                $this->Idea_cache->recursive_clone($x['i__id'], $do_recursive, $link_player, $this_i[0]);
            } else {
                //Link Followers:
                $this->Mench_ledger->create(array(
                    'link_player' => $link_player,
                    'link_type' => $x['link_type'],
                    'link_left' => $i_new['i__id'],
                    'link_right' => $x['i__id'],
                    'link_text' => $x['link_text'],
                    'link_number' => $x['link_number'],
                ));
            }
        }

        return array(
            'status' => 1,
            'new_i__id' => $i_new['i__id'],
            'new_i__hashtag' => $i_new['i__hashtag'],
        );

    }




   function mass_update($i__id, $action_e__id, $action_command1, $action_command2, $link_player)
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
            'link_void' => 0, //Not Void
            'link_type IN (' . join(',', $this->config->item('n___42267')) . ')' => null, //Active Sequence Down
            'link_left' => $i__id,
        ), array('link_right'), 0, 0, array('link_number' => 'ASC'));


        //Process request:
        foreach($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if(in_array($action_e__id , array(12591,12592,27080,27985,27081,27986,27082,27083,27084,27085,27086,27087)) && view__valid_handle_e($action_command1)){

                //Check if it has this item:
                foreach($this->Source_cache->fetch(array(
                    'LOWER(e__handle)' => strtolower(view__valid_handle_e($action_command1)),
                )) as $e){

                    $i_has_e = $this->Mench_ledger->fetch(array(
                        'link_void' => 0, //Not Void
                        'link_type IN (' . join(',', $this->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                        'link_right' => $next_i['i__id'],
                        'link_up' => $e['e__id'],
                    ));

                    if(in_array($action_e__id , array(12591,27080,27985,27082,27084,27086)) && !count($i_has_e)){

                        $e_mapper = array(
                            12591 => 4983,  //Co-Author
                            27985 => 27984, //Include If Has ANY
                            27082 => 26600, //Exclude If Has ALL
                            27084 => 7545,  //Following Add
                            27086 => 26599, //Following Remove
                        );

                        //Missing & Must be Added:
                        $this->Mench_ledger->create(array(
                            'link_player' => $link_player,
                            'link_up' => $e['e__id'],
                            'link_type' => $e_mapper[$action_e__id],
                            'link_right' => $next_i['i__id'],
                            'link_text' => trim($action_command2),
                        ), true);

                        $applied_success++;

                    } elseif(in_array($action_e__id , array(12592,27081,27986,27083,27085,27087)) && count($i_has_e)){

                        //Has and must be deleted:
                        $this->Mench_ledger->update($i_has_e[0]['link_id'], array(), $link_player);

                        $applied_success++;

                    }
                }

            } elseif(in_array($action_e__id , array(12611,12612,27240,28801)) && view__valid_handle_i($action_command1)){

                foreach($this->Idea_cache->fetch(array(
                    'LOWER(i__hashtag)' => strtolower(view__valid_handle_i($action_command1)),
                )) as $i){

                    if($action_e__id==27240){

                        //Copy
                        $link_count = $this->Idea_cache->duplicate($next_i, $i['i__id'], $link_player);

                        if($link_count > 0){
                            //Increment Source since not there:
                            $applied_success++;
                        }

                    } else {

                        $is_previous = $this->Mench_ledger->fetch(array(
                            'link_void' => 0, //Not Void
                            'link_type IN (' . join(',', $this->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                            'link_left' => $i['i__id'],
                            'link_right' => $next_i['i__id'],
                        ), array(), 0);


                        //See how to adjust:
                        if(in_array($action_e__id, array(12611, 28801)) && !count($is_previous)){

                            //Link
                            $status = $this->Idea_cache->i_link($i, 4228, $next_i, $link_player);

                            if($status['status']){

                                if($action_e__id==28801){
                                    //Also remove old link:
                                    $this->Mench_ledger->update($next_i['link_id'], array(), $link_player);
                                }

                                //Increment Source since not there:
                                $applied_success++;
                            }
                        }


                        if($action_e__id==12612 && count($is_previous)){
                            //Unlink
                            $this->Mench_ledger->update($is_previous[0]['link_id'], array(), $link_player);

                            $applied_success++;
                        }


                    }
                }

            }
        }


        //Log mass source edit transaction:
        $this->Mench_ledger->create(array(
            'link_type' => 44179, //Triggered
            'link_up' => $action_e__id,
            'link_down' => $link_player,
            'link_player' => $link_player,
            'link_right' => $i__id,
            'link_text' => array(
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