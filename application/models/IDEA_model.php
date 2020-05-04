<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class IDEA_model extends CI_Model
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


    function in_create($insert_columns, $ln_creator_source_id = 0)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($insert_columns, array('in_title', 'in_type_source_id', 'in_status_source_id'), $ln_creator_source_id)) {
            return false;
        }

        if(!isset($insert_columns['in_time_seconds']) || $insert_columns['in_time_seconds'] < config_var(12427)){
            $insert_columns['in_time_seconds'] = config_var(12176);
        }

        //Lets now add:
        $this->db->insert('mench_idea', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['in_id'])) {
            $insert_columns['in_id'] = $this->db->insert_id();
        }

        if ($insert_columns['in_id'] > 0) {

            if ($ln_creator_source_id > 0) {

                //Log link new Idea:
                $this->LEDGER_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_next_idea_id' => $insert_columns['in_id'],
                    'ln_content' => $insert_columns['in_title'],
                    'ln_type_source_id' => 4250, //New Idea Created
                ));

                //Also add as source:
                $this->LEDGER_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_profile_source_id' => $ln_creator_source_id,
                    'ln_type_source_id' => 4983, //IDEA COIN
                    'ln_content' => '@'.$ln_creator_source_id,
                    'ln_next_idea_id' => $insert_columns['in_id'],
                ), true);

                //Fetch to return the complete source data:
                $ins = $this->IDEA_model->in_fetch(array(
                    'in_id' => $insert_columns['in_id'],
                ));

                //Update Algolia:
                update_algolia('in', $insert_columns['in_id']);

                return $ins[0];

            } else {

                //Return provided inputs plus the new source ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->LEDGER_model->ln_create(array(
                'ln_content' => 'in_create() failed to create a new idea',
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function in_fetch($match_columns = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Ideas
        $this->db->select($select);
        $this->db->from('mench_idea');

        foreach($match_columns as $key => $value) {
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
        return $q->result_array();
    }

    function in_update($id, $update_columns, $external_sync = false, $ln_creator_source_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current Idea filed values so we can compare later on after we've updated it:
        if($ln_creator_source_id > 0){
            $before_data = $this->IDEA_model->in_fetch(array('in_id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['in_metadata']) && is_array($update_columns['in_metadata'])) {
            $update_columns['in_metadata'] = serialize($update_columns['in_metadata']);
        }

        //Update:
        $this->db->where('in_id', $id);
        $this->db->update('mench_idea', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $ln_creator_source_id > 0) {

            //Unlike source modification, we require a player source ID to log the modification link:
            //Log modification link for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //Assume no SOURCE LINKS unless specifically defined:
                $ln_portfolio_source_id = 0;
                $ln_profile_source_id = 0;


                if($key=='in_title') {

                    $ln_type_source_id = 10644; //Idea updated Outcome
                    $ln_content = update_description($before_data[0][$key], $value);

                } elseif($key=='in_status_source_id'){

                    if(in_array($value, $this->config->item('en_ids_7356') /* ACTIVE */)){
                        $ln_type_source_id = 10648; //Idea updated Status
                    } else {
                        $ln_type_source_id = 6182; //Idea Deleted
                    }
                    $en_all_4737 = $this->config->item('en_all_4737'); //Idea Status
                    $ln_content = echo_db_field($key) . ' updated from [' . $en_all_4737[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_4737[$value]['m_name'] . ']';
                    $ln_profile_source_id = $value;
                    $ln_portfolio_source_id = $before_data[0][$key];

                } elseif($key=='in_type_source_id'){

                    $ln_type_source_id = 10651; //Idea updated Subtype
                    $en_all_7585 = $this->config->item('en_all_7585'); //Idea Subtypes
                    $ln_content = echo_db_field($key) . ' updated from [' . $en_all_7585[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_7585[$value]['m_name'] . ']';
                    $ln_profile_source_id = $value;
                    $ln_portfolio_source_id = $before_data[0][$key];

                } elseif($key=='in_time_seconds') {

                    $ln_type_source_id = 10650; //Idea updated Completion Time
                    $ln_content = echo_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }


                //Value has changed, log link:
                $this->LEDGER_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_type_source_id' => $ln_type_source_id,
                    'ln_next_idea_id' => $id,
                    'ln_portfolio_source_id' => $ln_portfolio_source_id,
                    'ln_profile_source_id' => $ln_profile_source_id,
                    'ln_content' => $ln_content,
                    'ln_metadata' => array(
                        'in_id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

            if($external_sync){
                //Sync algolia:
                update_algolia('in', $id);
            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->LEDGER_model->ln_create(array(
                'ln_next_idea_id' => $id,
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_content' => 'in_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function in_unlink($in_id, $ln_creator_source_id = 0){

        //REMOVE IDEA LINKS
        $links_deleted = 0;
        foreach($this->LEDGER_model->ln_fetch(array( //Idea Links
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            '(ln_next_idea_id = '.$in_id.' OR ln_previous_idea_id = '.$in_id.')' => null,
        ), array(), 0) as $ln){
            //Delete this link:
            $links_deleted += $this->LEDGER_model->ln_update($ln['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Deleted
            ), $ln_creator_source_id, 10686 /* Idea Link Unlinked */);
        }


        //REMOVE NOTES:
        $in_notes = $this->LEDGER_model->ln_fetch(array( //Idea Links
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //IDEA NOTES
            'ln_next_idea_id' => $in_id,
        ), array(), 0);
        foreach($in_notes as $in_note){
            //Delete this link:
            $links_deleted += $this->LEDGER_model->ln_update($in_note['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Deleted
            ), $ln_creator_source_id, 10686 /* Idea Link Unlinked */);
        }


        //Return links deleted:
        return $links_deleted;
    }

    function in_match_ln_status($ln_creator_source_id, $query = array()){

        //STATS
        $stats = array(
            'ln_type_source_id' => 4250, //Idea Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        $status_converter = array(
            12137 => 12399, //IDEA FEATURE  => TRANSACTION FEATURE
            6184 => 6176,   //IDEA PUBLISH  => TRANSACTION PUBLISH
            6183 => 6175,   //IDEA DRAFT    => TRANSACTION DRAFT
            6182 => 6173,   //IDEA DELETE   => TRANSACTION DELETE
        );


        foreach($this->IDEA_model->in_fetch($query) as $in){

            $stats['scanned']++;

            //Find creation read:
            $reads = $this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id' => $stats['ln_type_source_id'],
                'ln_next_idea_id' => $in['in_id'],
            ));

            if(!count($reads)){

                $stats['missing_creation_fix']++;

                $this->LEDGER_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_next_idea_id' => $in['in_id'],
                    'ln_content' => $in['in_title'],
                    'ln_type_source_id' => $stats['ln_type_source_id'],
                    'ln_status_source_id' => $status_converter[$in['in_status_source_id']],
                ));

            } elseif($reads[0]['ln_status_source_id'] != $status_converter[$in['in_status_source_id']]){

                $stats['status_sync']++;
                $this->LEDGER_model->ln_update($reads[0]['ln_id'], array(
                    'ln_status_source_id' => $status_converter[$in['in_status_source_id']],
                ));

            }

        }

        return $stats;
    }

    function in_link_or_create($in_title, $ln_creator_source_id, $link_to_in_id = 0, $is_parent = false, $new_in_status = 6184, $in_type_source_id = 6677 /* Idea Read-Only */, $link_in_id = 0)
    {

        /*
         *
         * The main idea creation function that would create
         * appropriate links and return the idea view.
         *
         * Either creates an IDEA link between $link_to_in_id & $link_in_id
         * (IF $link_in_id>0) OR will create a new idea with outcome $in_title
         * and link it to $link_to_in_id (In this case $link_in_id will be 0)
         *
         * p.s. Inputs have previously been validated via ideas/in_link_or_create() function
         *
         * */

        //Validate Original idea:
        if($link_to_in_id > 0){
            $linked_ins = $this->IDEA_model->in_fetch(array(
                'in_id' => intval($link_to_in_id),
            ));

            if (count($linked_ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID',
                );
            } elseif (!in_array($linked_ins[0]['in_status_source_id'], $this->config->item('en_ids_7356')) /* ACTIVE */) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active ideas. This idea is not active.',
                );
            }
        }


        if ($link_in_id > 0) {

            //We are linking to $link_in_id, We are NOT creating any new ideas...

            //Fetch more details on the child idea we're about to link:
            $ins = $this->IDEA_model->in_fetch(array(
                'in_id' => $link_in_id,
            ));

            //Determine which is parent Idea, and which is child
            if($is_parent){

                $parent_in = $ins[0];
                $child_in = $linked_ins[0];

                /*
                //Prevent child duplicates:
                $recursive_children = $this->IDEA_model->in_recursive_child_ids($child_in['in_id'], false);
                if (in_array($parent_in['in_id'], $recursive_children)) {
                    return array(
                        'status' => 0,
                        'message' => 'Idea previously set as child, so it cannot be added as parent',
                    );
                }
                */

            } else {

                $parent_in = $linked_ins[0];
                $child_in = $ins[0];

                //Prevent parent duplicate:
                $recursive_parents = $this->IDEA_model->in_recursive_parents($parent_in['in_id']);
                foreach($recursive_parents as $grand_parent_ids) {
                    if (in_array($child_in['in_id'], $grand_parent_ids)) {
                        return array(
                            'status' => 0,
                            'message' => 'Idea previously set as parent, so it cannot be added as child',
                        );
                    }
                }
            }


            if (count($ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Idea ID',
                );
            } elseif (!in_array($ins[0]['in_status_source_id'], $this->config->item('en_ids_7356') /* ACTIVE */)) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active ideas. This idea is not active.',
                );
            }

            //All good so far, continue with linking:
            $in_new = $ins[0];

            //Make sure this is not a duplicate Idea for its parent:
            $dup_links = $this->LEDGER_model->ln_fetch(array(
                'ln_previous_idea_id' => $parent_in['in_id'],
                'ln_next_idea_id' => $child_in['in_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            ));

            //Check for issues:
            if (count($dup_links) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $in_new['in_title'] . '] is previously linked here.',
                );

            } elseif ($link_to_in_id > 0 && $link_in_id == $link_to_in_id) {

                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $in_new['in_title'] . '" as its own '.( $is_parent ? 'previous' : 'next' ).' idea.',
                );

            }

        } else {

            //We are NOT linking to an existing Idea, but instead, we're creating a new Idea

            //Validate Idea Outcome:
            $in_title_validation = in_title_validate($in_title);
            if(!$in_title_validation['status']){
                //We had an error, return it:
                return $in_title_validation;
            }


            //Create new Idea:
            $in_new = $this->IDEA_model->in_create(array(
                'in_title' => $in_title_validation['in_clean_title'],
                'in_type_source_id' => $in_type_source_id,
                'in_status_source_id' => $new_in_status,
            ), $ln_creator_source_id);

        }


        //Create Idea Link:
        if($link_to_in_id > 0){

            $relation = $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_type_source_id' => 4228, //Idea Link Regular Reads
                ( $is_parent ? 'ln_next_idea_id' : 'ln_previous_idea_id' ) => $link_to_in_id,
                ( $is_parent ? 'ln_previous_idea_id' : 'ln_next_idea_id' ) => $in_new['in_id'],
                'ln_order' => 1 + $this->LEDGER_model->ln_max_order(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
                        'ln_previous_idea_id' => ( $is_parent ? $in_new['in_id'] : $link_to_in_id ),
                    )),
            ), true);

            //Fetch and return full data to be properly shown on the UI using the echo_in() function
            $new_ins = $this->LEDGER_model->ln_fetch(array(
                ( $is_parent ? 'ln_next_idea_id' : 'ln_previous_idea_id' ) => $link_to_in_id,
                ( $is_parent ? 'ln_previous_idea_id' : 'ln_next_idea_id' ) => $in_new['in_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
            ), array(($is_parent ? 'in_previous' : 'in_next')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


            $child_in_html = echo_in($new_ins[0], $link_to_in_id, $is_parent, true /* Since they added it! */);

        } else {

            $child_in_html = null;

        }

        //Return result:
        return array(
            'status' => 1,
            'new_in_id' => $in_new['in_id'],
            'in_child_html' => $child_in_html,
        );

    }


    function in_recursive_parents($in_id, $first_level = true, $public_only = true)
    {

        $grand_parents = array();

        //Fetch parents:
        foreach($this->LEDGER_model->ln_fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item(($public_only ? 'en_ids_7355' : 'en_ids_7356'))) . ')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item(($public_only ? 'en_ids_7359' : 'en_ids_7360'))) . ')' => null,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_next_idea_id' => $in_id,
        ), array('in_previous')) as $in_parent) {

            //Prep ID:
            $p_id = intval($in_parent['in_id']);

            //Add to appropriate array:
            if (!$first_level) {
                array_push($grand_parents, $p_id);
            }


            //Fetch parents of parents:
            $recursive_parents = $this->IDEA_model->in_recursive_parents($p_id, false);

            if (count($recursive_parents) > 0) {
                if ($first_level) {
                    array_push($grand_parents, array_merge(array($p_id), $recursive_parents));
                } else {
                    $grand_parents = array_merge($grand_parents, $recursive_parents);
                }
            } elseif ($first_level) {
                array_push($grand_parents, array($p_id));
            }

        }


        if ($first_level) {

            //Now we must break down the array:
            $recursive_parents = array();
            $start_in_id = config_var(12156);
            $index = 0;
            foreach($grand_parents as $grand_parent_ids) {
                foreach($grand_parent_ids as $grand_parent_id) {
                    if (!isset($recursive_parents[$index])) {
                        $recursive_parents[$index] = array();
                    }
                    array_push($recursive_parents[$index], intval($grand_parent_id));
                    if ($grand_parent_id == $start_in_id) {
                        $index++;
                    }
                }
            }

            return $recursive_parents;

        } else {
            return $grand_parents;
        }
    }


    function in_recursive_child_ids($in_id, $first_level = true){

        $child_ids = array();

        //Fetch parents:
        foreach($this->LEDGER_model->ln_fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            'ln_previous_idea_id' => $in_id,
        ), array('in_next')) as $child_in){

            array_push($child_ids, intval($child_in['in_id']));

            //Fetch parents of parents:
            $recursive_children = $this->IDEA_model->in_recursive_child_ids($child_in['in_id'], false);

            //Add to current array if we found anything:
            if(count($recursive_children) > 0){
                $child_ids = array_merge($child_ids, $recursive_children);
            }
        }

        if($first_level){
            return array_unique($child_ids);
        } else {
            return $child_ids;
        }
    }



    function in_metadata_common_base($focus_in){

        //Set variables:
        $is_first_in = ( !isset($focus_in['ln_id']) ); //First idea does not have a link, just the idea
        $select_one = in_array($focus_in['in_type_source_id'] , $this->config->item('en_ids_12883')); //IDEA TYPE SELECT ONE
        $select_some = in_array($focus_in['in_type_source_id'] , $this->config->item('en_ids_12884')); //IDEA TYPE SELECT SOME
        $select_one_children = array(); //To be populated only if $focus_in is select one
        $select_some_children = array(); //To be populated only if $focus_in is select some
        $conditional_steps = array(); //To be populated only for Conditional Ideas
        $metadata_this = array(
            '__in__metadata_common_steps' => array(), //The idea structure that would be shared with all users regardless of their quick replies (OR Idea Answers)
            '__in__metadata_expansion_steps' => array(), //Ideas that may exist as a link to expand an Bookshelf idea by answering OR ideas
            '__in__metadata_expansion_some' => array(), //Ideas that allows players to select one or more
            '__in__metadata_expansion_conditional' => array(), //Ideas that may exist as a link to expand an Bookshelf idea via Conditional Idea links
        );

        //Fetch children:
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            'ln_previous_idea_id' => $focus_in['in_id'],
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $child_in){

            //Determine action based on parent idea type:
            if(in_array($child_in['ln_type_source_id'], $this->config->item('en_ids_12842'))){

                //Conditional Idea Link:
                array_push($conditional_steps, intval($child_in['in_id']));

            } elseif($select_one){

                //OR parent Idea with Fixed Idea Link:
                array_push($select_one_children, intval($child_in['in_id']));

            } elseif($select_some){

                //OR parent Idea with Fixed Idea Link:
                array_push($select_some_children, intval($child_in['in_id']));

            } else {

                //AND parent Idea with Fixed Idea Link:
                array_push($metadata_this['__in__metadata_common_steps'], intval($child_in['in_id']));

                //Go recursively down:
                $child_recursion = $this->IDEA_model->in_metadata_common_base($child_in);


                //Aggregate recursion data:
                if(count($child_recursion['__in__metadata_common_steps']) > 0){
                    array_push($metadata_this['__in__metadata_common_steps'], $child_recursion['__in__metadata_common_steps']);
                }

                //Merge expansion steps:
                if(count($child_recursion['__in__metadata_expansion_steps']) > 0){
                    foreach($child_recursion['__in__metadata_expansion_steps'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['__in__metadata_expansion_steps'])){
                            $metadata_this['__in__metadata_expansion_steps'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['__in__metadata_expansion_some']) > 0){
                    foreach($child_recursion['__in__metadata_expansion_some'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['__in__metadata_expansion_some'])){
                            $metadata_this['__in__metadata_expansion_some'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['__in__metadata_expansion_conditional']) > 0){
                    foreach($child_recursion['__in__metadata_expansion_conditional'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['__in__metadata_expansion_conditional'])){
                            $metadata_this['__in__metadata_expansion_conditional'][$key] = $value;
                        }
                    }
                }
            }
        }


        //Was this an OR branch that needs it's children added to the array?
        if($select_one && count($select_one_children) > 0){
            $metadata_this['__in__metadata_expansion_steps'][$focus_in['in_id']] = $select_one_children;
        }
        if($select_some && count($select_some_children) > 0){
            $metadata_this['__in__metadata_expansion_some'][$focus_in['in_id']] = $select_some_children;
        }
        if(count($conditional_steps) > 0){
            $metadata_this['__in__metadata_expansion_conditional'][$focus_in['in_id']] = $conditional_steps;
        }


        //Save common base:
        if($is_first_in){

            //Make sure to add main idea to common idea:
            if(count($metadata_this['__in__metadata_common_steps']) > 0){
                $metadata_this['__in__metadata_common_steps'] = array_merge( array(intval($focus_in['in_id'])) , array($metadata_this['__in__metadata_common_steps']));
            } else {
                $metadata_this['__in__metadata_common_steps'] = array(intval($focus_in['in_id']));
            }

            update_metadata('in', $focus_in['in_id'], array(
                'in__metadata_common_steps' => $metadata_this['__in__metadata_common_steps'],
                'in__metadata_expansion_steps' => $metadata_this['__in__metadata_expansion_steps'],
                'in__metadata_expansion_some' => $metadata_this['__in__metadata_expansion_some'],
                'in__metadata_expansion_conditional' => $metadata_this['__in__metadata_expansion_conditional'],
            ));

        }

        //Return results:
        return $metadata_this;

    }

    function in_mass_update($in_id, $action_en_id, $action_command1, $action_command2, $ln_creator_source_id)
    {

        //Alert: Has a twin function called en_mass_update()

        boost_power();

        if(!in_array($action_en_id, $this->config->item('en_ids_12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif(!is_valid_en_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Name',
            );

        }



        //Basic input validation done, let's continue...


        //Fetch all children:
        $applied_success = 0; //To be populated...

        $in__next = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_previous_idea_id' => $in_id,
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC'));


        //Process request:
        foreach($in__next as $in) {

            //Logic here must match items in en_mass_actions config variable

            if(in_array($action_en_id , array(12591, 12592))){

                //Check if it hs this item:
                $en__profile_id = intval(one_two_explode('@',' ',$action_command1));
                $in_has_sources = $this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
                    'ln_next_idea_id' => $in['in_id'],
                    'ln_profile_source_id' => $en__profile_id,
                ));

                if($action_en_id==12591 && !count($in_has_sources)){

                    //Missing & Must be Added:
                    $this->LEDGER_model->ln_create(array(
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_profile_source_id' => $en__profile_id,
                        'ln_type_source_id' => 4983, //IDEA COIN
                        'ln_content' => '@'.$en__profile_id,
                        'ln_next_idea_id' => $in['in_id'],
                    ), true);

                    $applied_success++;

                } elseif($action_en_id==12592 && count($in_has_sources)){

                    //Has and must be deleted:
                    $this->LEDGER_model->ln_update($in_has_sources[0]['ln_id'], array(
                        'ln_status_source_id' => 6173,
                    ), $ln_creator_source_id, 10678 /* IDEA NOTES Unlinked */);

                    $applied_success++;

                }

            } elseif(in_array($action_en_id , array(12611, 12612))){

                //TODO here

            }

        }


        //Log mass source edit link:
        $this->LEDGER_model->ln_create(array(
            'ln_creator_source_id' => $ln_creator_source_id,
            'ln_type_source_id' => $action_en_id,
            'ln_next_idea_id' => $in_id,
            'ln_metadata' => array(
                'payload' => $_POST,
                'ideas_total' => count($in__next),
                'ideas_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . '/' . count($in__next) . ' ideas updated',
        );

    }


    function in_weight($in_id)
    {

        /*
         *
         * Addup weights recursively
         *
         * */


        $total_child_weights = 0;

        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_previous_idea_id' => $in_id,
        ), array('in_next'), 0, 0, array(), 'in_id, in_weight') as $in_child){
            $total_child_weights += $in_child['in_weight'] + $this->IDEA_model->in_weight($in_child['in_id']);
        }

        //Update This Level:
        if($total_child_weights > 0){
            $this->db->query("UPDATE mench_idea SET in_weight=in_weight+".$total_child_weights." WHERE in_id=".$in_id.";");
        }

        //Return data:
        return $total_child_weights;

    }



    function in_metadata_extra_insights($in)
    {

        /*
         *
         * Generates Idea Tree Insights like
         * min/max ideas, time & referenced
         * expert sources/channels.
         *
         * */

        $metadata_this = array(
            '__in__metadata_min_steps' => 1,
            '__in__metadata_max_steps' => 1,
            '__in__metadata_min_seconds' => $in['in_time_seconds'],
            '__in__metadata_max_seconds' => $in['in_time_seconds'],
            '__in__metadata_experts' => array(),
            '__in__metadata_sources' => array(),
        );


        //AGGREGATE IDEA SOURCES
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_profile_source_id >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
            'ln_next_idea_id' => $in['in_id'],
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12273')).')' => null, //IDEA COIN
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
        ), array('en_profile'), 0) as $en) {

            $en_metadat_experts = $this->SOURCE_model->en_metadat_experts($en);

            //CONTENT CHANNELS
            foreach($en_metadat_experts['__in__metadata_sources'] as $en_id => $source_en) {
                if (!isset($metadata_this['__in__metadata_sources'][$en_id])) {
                    $metadata_this['__in__metadata_sources'][$en_id] = $source_en;
                }
            }

            //EXPERT PEOPLE/ORGANIZATIONS
            foreach($en_metadat_experts['__in__metadata_experts'] as $en_id => $expert_en) {
                //Is this a new expert?
                if (!isset($metadata_this['__in__metadata_experts'][$en_id])) {
                    //Yes, add them to the list:
                    $metadata_this['__in__metadata_experts'][$en_id] = $expert_en;
                }
            }
        }


        $metadata_local = array(
            'local__in__metadata_min_steps'=> null,
            'local__in__metadata_max_steps'=> null,
            'local__in__metadata_min_seconds'=> null,
            'local__in__metadata_max_seconds'=> null,
        );

        //NEXT IDEAS
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            'ln_previous_idea_id' => $in['in_id'],
        ), array('in_next'), 0) as $in__next){

            //RECURSION
            $metadata_recursion = $this->IDEA_model->in_metadata_extra_insights($in__next);
            if(!$metadata_recursion){
                continue;
            }

            //MERGE (3 SCENARIOS)
            if(in_array($in__next['ln_type_source_id'], $this->config->item('en_ids_12842')) || in_array($in['in_type_source_id'], $this->config->item('en_ids_12883'))){

                //ONE

                //MIN
                if(is_null($metadata_local['local__in__metadata_min_steps']) || $metadata_recursion['__in__metadata_min_steps'] < $metadata_local['local__in__metadata_min_steps']){
                    $metadata_local['local__in__metadata_min_steps'] = $metadata_recursion['__in__metadata_min_steps'];
                }
                if(is_null($metadata_local['local__in__metadata_min_seconds']) || $metadata_recursion['__in__metadata_min_seconds'] < $metadata_local['local__in__metadata_min_seconds']){
                    $metadata_local['local__in__metadata_min_seconds'] = $metadata_recursion['__in__metadata_min_seconds'];
                }

                //MAX
                if(is_null($metadata_local['local__in__metadata_max_steps']) || $metadata_recursion['__in__metadata_max_steps'] > $metadata_local['local__in__metadata_max_steps']){
                    $metadata_local['local__in__metadata_max_steps'] = $metadata_recursion['__in__metadata_max_steps'];
                }
                if(is_null($metadata_local['local__in__metadata_max_seconds']) || $metadata_recursion['__in__metadata_max_seconds'] > $metadata_local['local__in__metadata_max_seconds']){
                    $metadata_local['local__in__metadata_max_seconds'] = $metadata_recursion['__in__metadata_max_seconds'];
                }

            } elseif(in_array($in['in_type_source_id'], $this->config->item('en_ids_12884'))){

                //SOME

                //MIN
                if(is_null($metadata_local['local__in__metadata_min_steps']) || $metadata_recursion['__in__metadata_min_steps'] < $metadata_local['local__in__metadata_min_steps']){
                    $metadata_local['local__in__metadata_min_steps'] = $metadata_recursion['__in__metadata_min_steps'];
                }
                if(is_null($metadata_local['local__in__metadata_min_seconds']) || $metadata_recursion['__in__metadata_min_seconds'] < $metadata_local['local__in__metadata_min_seconds']){
                    $metadata_local['local__in__metadata_min_seconds'] = $metadata_recursion['__in__metadata_min_seconds'];
                }

                //MAX
                $metadata_this['__in__metadata_max_steps'] += intval($metadata_recursion['__in__metadata_max_steps']);
                $metadata_this['__in__metadata_max_seconds'] += intval($metadata_recursion['__in__metadata_max_seconds']);

            } else {

                //ALL

                //MIN
                $metadata_this['__in__metadata_min_steps'] += intval($metadata_recursion['__in__metadata_min_steps']);
                $metadata_this['__in__metadata_min_seconds'] += intval($metadata_recursion['__in__metadata_min_seconds']);

                //MAX
                $metadata_this['__in__metadata_max_steps'] += intval($metadata_recursion['__in__metadata_max_steps']);
                $metadata_this['__in__metadata_max_seconds'] += intval($metadata_recursion['__in__metadata_max_seconds']);

            }


            //EXPERT CONTENT
            foreach($metadata_recursion['__in__metadata_sources'] as $en_id => $source_en) {
                if (!isset($metadata_this['__in__metadata_sources'][$en_id])) {
                    $metadata_this['__in__metadata_sources'][$en_id] = $source_en;
                }
            }

            //EXPERT PEOPLE/ORGANIZATIONS
            foreach($metadata_recursion['__in__metadata_experts'] as $en_id => $expert_en) {
                if (!isset($metadata_this['__in__metadata_experts'][$en_id])) {
                    $metadata_this['__in__metadata_experts'][$en_id] = $expert_en;
                }
            }
        }


        //ADD LOCAL MIN/MAX
        if(!is_null($metadata_local['local__in__metadata_min_steps'])){
            $metadata_this['__in__metadata_min_steps'] += intval($metadata_local['local__in__metadata_min_steps']);
        }
        if(!is_null($metadata_local['local__in__metadata_max_steps'])){
            $metadata_this['__in__metadata_max_steps'] += intval($metadata_local['local__in__metadata_max_steps']);
        }
        if(!is_null($metadata_local['local__in__metadata_min_seconds'])){
            $metadata_this['__in__metadata_min_seconds'] += intval($metadata_local['local__in__metadata_min_seconds']);
        }
        if(!is_null($metadata_local['local__in__metadata_max_seconds'])){
            $metadata_this['__in__metadata_max_seconds'] += intval($metadata_local['local__in__metadata_max_seconds']);
        }

        //Sort Expert Content
        usort($metadata_this['__in__metadata_experts'], 'sortByWeight');
        usort($metadata_this['__in__metadata_sources'], 'sortByWeight');

        //Save to DB
        update_metadata('in', $in['in_id'], array(
            'in__metadata_min_steps' => intval($metadata_this['__in__metadata_min_steps']),
            'in__metadata_max_steps' => intval($metadata_this['__in__metadata_max_steps']),
            'in__metadata_min_seconds' => intval($metadata_this['__in__metadata_min_seconds']),
            'in__metadata_max_seconds' => intval($metadata_this['__in__metadata_max_seconds']),
            'in__metadata_experts' => $metadata_this['__in__metadata_experts'],
            'in__metadata_sources' => $metadata_this['__in__metadata_sources'],
        ));

        //Return data:
        return $metadata_this;

    }



    function in_unlock_paths($in)
    {
        /*
         *
         * Finds the pathways, if any, on how to unlock $in
         *
         * */


        //Validate this locked idea:
        if(!in_is_unlockable($in)){
            return array();
        }

        $child_unlock_paths = array();


        //Reads 1: Is there an OR parent that we can simply answer and unlock?
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_next_idea_id' => $in['in_id'],
            'in_type_source_id IN (' . join(',', $this->config->item('en_ids_7712')) . ')' => null,
        ), array('in_previous'), 0) as $in_or_parent){
            if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $in_or_parent['in_id'])) {
                array_push($child_unlock_paths, $in_or_parent);
            }
        }


        //Reads 2: Are there any locked link parents that the user might be able to unlock?
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12842')) . ')' => null, //IDEA LINKS ONE-WAY
            'ln_next_idea_id' => $in['in_id'],
        ), array('in_previous'), 0) as $in_locked_parent){
            if(in_is_unlockable($in_locked_parent)){
                //Need to check recursively:
                foreach($this->IDEA_model->in_unlock_paths($in_locked_parent) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $locked_path['in_id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }
            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $in_locked_parent['in_id'])) {
                array_push($child_unlock_paths, $in_locked_parent);
            }
        }


        //Return if we have options for step 1 OR step 2:
        if(count($child_unlock_paths) > 0){
            //Return OR parents for unlocking this idea:
            return $child_unlock_paths;
        }


        //Reads 3: We don't have any OR parents, let's see how we can complete all children to meet the requirements:
        $in__next = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_previous_idea_id' => $in['in_id'],
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC'));
        if(count($in__next) < 1){
            //No children, no path:
            return array();
        }

        //Go through children to see if any/all can be completed:
        foreach($in__next as $child_in){
            if(in_is_unlockable($child_in)){

                //Need to check recursively:
                foreach($this->IDEA_model->in_unlock_paths($child_in) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $locked_path['in_id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }

            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $child_in['in_id'])) {

                //Not locked, so this can be completed:
                array_push($child_unlock_paths, $child_in);

            }
        }
        return $child_unlock_paths;

    }

}