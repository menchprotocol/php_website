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


    function create($add_fields, $x__miner = 0)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($add_fields, array('i__title', 'i__type', 'i__status'), $x__miner)) {
            return false;
        }

        if(!isset($add_fields['i__duration']) || $add_fields['i__duration'] < config_var(12427)){
            $add_fields['i__duration'] = config_var(12176);
        }

        //Lets now add:
        $this->db->insert('mench__i', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['i__id'])) {
            $add_fields['i__id'] = $this->db->insert_id();
        }

        if ($add_fields['i__id'] > 0) {

            if ($x__miner > 0) {

                //Log transaction new Idea:
                $this->X_model->create(array(
                    'x__miner' => $x__miner,
                    'x__right' => $add_fields['i__id'],
                    'x__message' => $add_fields['i__title'],
                    'x__type' => 4250, //New Idea Created
                ));

                //Also add as source:
                $this->X_model->create(array(
                    'x__miner' => $x__miner,
                    'x__up' => $x__miner,
                    'x__type' => 4983, //IDEA COIN
                    'x__message' => '@'.$x__miner,
                    'x__right' => $add_fields['i__id'],
                ), true);

                //Fetch to return the complete source data:
                $is = $this->I_model->fetch(array(
                    'i__id' => $add_fields['i__id'],
                ));

                //Update Algolia:
                update_algolia(12273, $add_fields['i__id']);

                return $is[0];

            } else {

                //Return provided inputs plus the new source ID:
                return $add_fields;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->X_model->create(array(
                'x__message' => 'i_create() failed to create a new idea',
                'x__type' => 4246, //Platform Bug Reports
                'x__miner' => $x__miner,
                'x__metadata' => $add_fields,
            ));
            return false;

        }
    }

    function fetch($query_filters = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Ideas
        $this->db->select($select);
        $this->db->from('mench__i');

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
        return $q->result_array();
    }

    function update($id, $update_columns, $external_sync = false, $x__miner = 0)
    {

        $id = intval($id);
        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current Idea filed values so we can compare later on after we've updated it:
        if($x__miner > 0){
            $before_data = $this->I_model->fetch(array('i__id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['i__metadata']) && is_array($update_columns['i__metadata'])) {
            $update_columns['i__metadata'] = serialize($update_columns['i__metadata']);
        }

        //Update:
        $this->db->where('i__id', $id);
        $this->db->update('mench__i', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $x__miner > 0) {

            //Unlike source modification, we require a miner source ID to log the modification transaction:
            //Log modification transaction for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //Assume no SOURCE LINKS unless specifically defined:
                $x__down = 0;
                $x__up = 0;


                if($key=='i__title') {

                    $x__type = 10644; //Idea updated Outcome
                    $x__message = update_description($before_data[0][$key], $value);

                } elseif($key=='i__status'){

                    if(in_array($value, $this->config->item('n___7356') /* ACTIVE */)){
                        $x__type = 10648; //Idea updated Status
                    } else {
                        $x__type = 6182; //Idea Deleted
                    }
                    $e___4737 = $this->config->item('e___4737'); //Idea Status
                    $x__message = view_db_field($key) . ' updated from [' . $e___4737[$before_data[0][$key]]['m_name'] . '] to [' . $e___4737[$value]['m_name'] . ']';
                    $x__up = $value;
                    $x__down = $before_data[0][$key];

                } elseif($key=='i__type'){

                    $x__type = 10651; //Idea updated Subtype
                    $e___7585 = $this->config->item('e___7585'); //Idea Subtypes
                    $x__message = view_db_field($key) . ' updated from [' . $e___7585[$before_data[0][$key]]['m_name'] . '] to [' . $e___7585[$value]['m_name'] . ']';
                    $x__up = $value;
                    $x__down = $before_data[0][$key];

                } elseif($key=='i__duration') {

                    $x__type = 10650; //Idea updated Completion Time
                    $x__message = view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }


                //Value has changed, log transaction:
                $this->X_model->create(array(
                    'x__miner' => $x__miner,
                    'x__type' => $x__type,
                    'x__right' => $id,
                    'x__down' => $x__down,
                    'x__up' => $x__up,
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
                update_algolia(12273, $id);
            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->X_model->create(array(
                'x__right' => $id,
                'x__type' => 4246, //Platform Bug Reports
                'x__miner' => $x__miner,
                'x__message' => 'update() Failed to update',
                'x__metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function remove($i__id, $x__miner = 0){

        //REMOVE IDEA LINKS
        $x_deleted = 0;
        foreach($this->X_model->fetch(array( //Idea Transactions
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            '(x__right = '.$i__id.' OR x__left = '.$i__id.')' => null,
        ), array(), 0) as $x){
            //Delete this transaction:
            $x_deleted += $this->X_model->update($x['x__id'], array(
                'x__status' => 6173, //Transaction Deleted
            ), $x__miner, 10686 /* Idea Transaction Unpublished */);
        }


        //REMOVE NOTES:
        $i_notes = $this->X_model->fetch(array( //Idea Transactions
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4485')) . ')' => null, //IDEA NOTES
            'x__right' => $i__id,
        ), array(), 0);
        foreach($i_notes as $i_note){
            //Delete this transaction:
            $x_deleted += $this->X_model->update($i_note['x__id'], array(
                'x__status' => 6173, //Transaction Deleted
            ), $x__miner, 10686 /* Idea Transaction Unpublished */);
        }


        //Return transactions deleted:
        return $x_deleted;
    }

    function match_x_status($x__miner, $query = array()){

        //STATS
        $stats = array(
            'x__type' => 4250, //Idea Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        $status_converter = array(
            12137 => 12399, //IDEA FEATURE  => DISCOVER FEATURE
            6184 => 6176,   //IDEA PUBLISH  => DISCOVER PUBLISH
            6183 => 6175,   //IDEA DRAFT    => DISCOVER DRAFT
            6182 => 6173,   //IDEA DELETE   => DISCOVER DELETE
        );


        foreach($this->I_model->fetch($query) as $i){

            $stats['scanned']++;

            //Find creation discover:
            $x = $this->X_model->fetch(array(
                'x__type' => $stats['x__type'],
                'x__right' => $i['i__id'],
            ));

            if(!count($x)){

                $stats['missing_creation_fix']++;

                $this->X_model->create(array(
                    'x__miner' => $x__miner,
                    'x__right' => $i['i__id'],
                    'x__message' => $i['i__title'],
                    'x__type' => $stats['x__type'],
                    'x__status' => $status_converter[$i['i__status']],
                ));

            } elseif($x[0]['x__status'] != $status_converter[$i['i__status']]){

                $stats['status_sync']++;
                $this->X_model->update($x[0]['x__id'], array(
                    'x__status' => $status_converter[$i['i__status']],
                ));

            }

        }

        return $stats;
    }

    function x_or_create($i__title, $x__miner, $x_to_i__id = 0, $is_parent = false, $new_i_status = 6184, $i__type = 6677, $x_i__id = 0)
    {

        /*
         *
         * The main idea creation function that would create
         * appropriate transactions and return the idea view.
         *
         * Either creates an IDEA transaction between $x_to_i__id & $x_i__id
         * (IF $x_i__id>0) OR will create a new idea with outcome $i__title
         * and transaction it to $x_to_i__id (In this case $x_i__id will be 0)
         *
         * p.s. Inputs have previously been validated via ideas/i_add() function
         *
         * */

        //Validate Original idea:
        if($x_to_i__id > 0){
            $x_is = $this->I_model->fetch(array(
                'i__id' => intval($x_to_i__id),
            ));

            if (count($x_is) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID',
                );
            } elseif (!in_array($x_is[0]['i__status'], $this->config->item('n___7356')) /* ACTIVE */) {
                return array(
                    'status' => 0,
                    'message' => 'You can only transaction to active ideas. This idea is not active.',
                );
            }
        }


        if ($x_i__id > 0) {

            //We are adding $x_i__id, We are NOT creating any new ideas...

            //Fetch more details on the child idea we're about to transaction:
            $is = $this->I_model->fetch(array(
                'i__id' => $x_i__id,
            ));

            //Determine which is parent Idea, and which is child
            if($is_parent){

                $previous_i = $is[0];
                $next_i = $x_is[0];

                /*
                //Prevent child duplicates:
                $recursive_children = $this->I_model->recursive_child_ids($next_i['i__id'], false);
                if (in_array($previous_i['i__id'], $recursive_children)) {
                    return array(
                        'status' => 0,
                        'message' => 'Idea previously set as child, so it cannot be added as parent',
                    );
                }
                */

            } else {

                $previous_i = $x_is[0];
                $next_i = $is[0];

                //Prevent parent duplicate:
                $recursive_parents = $this->I_model->recursive_parents($previous_i['i__id']);
                foreach($recursive_parents as $grand_parent_ids) {
                    if (in_array($next_i['i__id'], $grand_parent_ids)) {
                        return array(
                            'status' => 0,
                            'message' => 'Idea previously set as parent, so it cannot be added as child',
                        );
                    }
                }
            }


            if (count($is) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID',
                );
            } elseif (!in_array($is[0]['i__status'], $this->config->item('n___7356') /* ACTIVE */)) {
                return array(
                    'status' => 0,
                    'message' => 'You can only transaction to active ideas. This idea is not active.',
                );
            }

            //All good so far, continue with adding:
            $i_new = $is[0];

            //Make sure this is not a duplicate Idea for its parent:
            $dup_x = $this->X_model->fetch(array(
                'x__left' => $previous_i['i__id'],
                'x__right' => $next_i['i__id'],
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            ));

            //Check for issues:
            if (count($dup_x) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $i_new['i__title'] . '] is already here.',
                );

            } elseif ($x_to_i__id > 0 && $x_i__id == $x_to_i__id) {

                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $i_new['i__title'] . '" as its own '.( $is_parent ? 'previous' : 'next' ).' idea.',
                );

            }

        } else {

            //We are NOT adding an existing Idea, but instead, we're creating a new Idea

            //Validate Idea Outcome:
            $i__title_validation = i__title_validate($i__title);
            if(!$i__title_validation['status']){
                //We had an error, return it:
                return $i__title_validation;
            }


            //Create new Idea:
            $i_new = $this->I_model->create(array(
                'i__title' => $i__title_validation['i_clean_title'],
                'i__type' => $i__type,
                'i__status' => $new_i_status,
            ), $x__miner);

        }


        //Create Idea Transaction:
        if($x_to_i__id > 0){

            $relation = $this->X_model->create(array(
                'x__miner' => $x__miner,
                'x__type' => 4228, //Idea Transaction Regular Discovery
                ( $is_parent ? 'x__right' : 'x__left' ) => $x_to_i__id,
                ( $is_parent ? 'x__left' : 'x__right' ) => $i_new['i__id'],
                'x__sort' => 1 + $this->X_model->max_sort(array(
                        'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                        'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                        'x__left' => ( $is_parent ? $i_new['i__id'] : $x_to_i__id ),
                    )),
            ), true);

            //Fetch and return full data to be properly shown on the UI using the view_i() function
            $new_is = $this->X_model->fetch(array(
                ( $is_parent ? 'x__right' : 'x__left' ) => $x_to_i__id,
                ( $is_parent ? 'x__left' : 'x__right' ) => $i_new['i__id'],
                'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            ), array(($is_parent ? 'x__left' : 'x__right')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


            $next_i_html = view_i($new_is[0], $x_to_i__id, $is_parent, true /* Since they added it! */);

        } else {

            $next_i_html = null;

        }

        //Return result:
        return array(
            'status' => 1,
            'new_i__id' => $i_new['i__id'],
            'next_i_html' => $next_i_html,
        );

    }

    function delete($e__id, $i__id, $x__type){

        //Validate idea to be deleted:
        $is = $this->I_model->fetch(array(
            'i__id' => $i__id,
        ));
        if (count($is) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea',
            );
        }


        //Go ahead and delete from Discoveries:
        $miner_is = $this->X_model->fetch(array(
            'x__type' => 10573, //MY IDEAS
            'x__up' => $e__id,
            'x__right' => $i__id,
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ));
        if(count($miner_is) < 1){
            return array(
                'status' => 0,
                'message' => 'Could not locate Idea',
            );
        }

        //Delete:
        foreach($miner_is as $x){
            $this->X_model->update($x['x__id'], array(
                'x__status' => 6173, //DELETED
            ), $e__id, $x__type);
        }

        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }

    function recursive_parents($i__id, $first_level = true, $public_only = true)
    {

        $grand_parents = array();

        //Fetch parents:
        foreach($this->X_model->fetch(array(
            'i__status IN (' . join(',', $this->config->item(($public_only ? 'n___7355' : 'n___7356'))) . ')' => null,
            'x__status IN (' . join(',', $this->config->item(($public_only ? 'n___7359' : 'n___7360'))) . ')' => null,
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__right' => $i__id,
        ), array('x__left')) as $i_previous) {

            //Prep ID:
            $p_id = intval($i_previous['i__id']);

            //Add to appropriate array:
            if (!$first_level) {
                array_push($grand_parents, $p_id);
            }


            //Fetch parents of parents:
            $recursive_parents = $this->I_model->recursive_parents($p_id, false);

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
            $start_i__id = config_var(13427);
            $index = 0;
            foreach($grand_parents as $grand_parent_ids) {
                foreach($grand_parent_ids as $grand_parent_id) {
                    if (!isset($recursive_parents[$index])) {
                        $recursive_parents[$index] = array();
                    }
                    array_push($recursive_parents[$index], intval($grand_parent_id));
                    if ($grand_parent_id == $start_i__id) {
                        $index++;
                    }
                }
            }

            return $recursive_parents;

        } else {
            return $grand_parents;
        }

    }


    function recursive_child_ids($i__id, $first_level = true){

        $child_ids = array();

        //Fetch parents:
        foreach($this->X_model->fetch(array(
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i__id,
        ), array('x__right')) as $next_i){

            array_push($child_ids, intval($next_i['i__id']));

            //Fetch parents of parents:
            $recursive_children = $this->I_model->recursive_child_ids($next_i['i__id'], false);

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



    function metadata_common_base($focus_in){

        //Set variables:
        $is_first_in = ( !isset($focus_in['x__id']) ); //First idea does not have a transaction, just the idea
        $select_one = in_array($focus_in['i__type'] , $this->config->item('n___12883')); //IDEA TYPE SELECT ONE
        $select_some = in_array($focus_in['i__type'] , $this->config->item('n___12884')); //IDEA TYPE SELECT SOME
        $select_one_children = array(); //To be populated only if $focus_in is select one
        $select_some_children = array(); //To be populated only if $focus_in is select some
        $conditional_x = array(); //To be populated only for Conditional Ideas
        $metadata_this = array(
            'p___6168' => array(), //The idea structure that would be shared with all miners regardless of their quick replies (OR Idea Answers)
            'p___6228' => array(), //Ideas that may exist as a transaction to expand Discovery by answering OR ideas
            'p___12885' => array(), //Ideas that allows miners to select one or more
            'p___6283' => array(), //Ideas that may exist as a transaction to expand Discovery via Conditional Idea transactions
        );

        //Fetch children:
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $focus_in['i__id'],
        ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $next_i){

            //Determine action based on parent idea type:
            if(in_array($next_i['x__type'], $this->config->item('n___12842'))){

                //Conditional Idea Transaction:
                array_push($conditional_x, intval($next_i['i__id']));

            } elseif($select_one){

                //OR parent Idea with Fixed Idea Transaction:
                array_push($select_one_children, intval($next_i['i__id']));

            } elseif($select_some){

                //OR parent Idea with Fixed Idea Transaction:
                array_push($select_some_children, intval($next_i['i__id']));

            } else {

                //AND parent Idea with Fixed Idea Transaction:
                array_push($metadata_this['p___6168'], intval($next_i['i__id']));

                //Go recursively down:
                $child_recursion = $this->I_model->metadata_common_base($next_i);


                //Aggregate recursion data:
                if(count($child_recursion['p___6168']) > 0){
                    array_push($metadata_this['p___6168'], $child_recursion['p___6168']);
                }

                //Merge expansion steps:
                if(count($child_recursion['p___6228']) > 0){
                    foreach($child_recursion['p___6228'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['p___6228'])){
                            $metadata_this['p___6228'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['p___12885']) > 0){
                    foreach($child_recursion['p___12885'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['p___12885'])){
                            $metadata_this['p___12885'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['p___6283']) > 0){
                    foreach($child_recursion['p___6283'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['p___6283'])){
                            $metadata_this['p___6283'][$key] = $value;
                        }
                    }
                }
            }
        }


        //Was this an OR branch that needs it's children added to the array?
        if($select_one && count($select_one_children) > 0){
            $metadata_this['p___6228'][$focus_in['i__id']] = $select_one_children;
        }
        if($select_some && count($select_some_children) > 0){
            $metadata_this['p___12885'][$focus_in['i__id']] = $select_some_children;
        }
        if(count($conditional_x) > 0){
            $metadata_this['p___6283'][$focus_in['i__id']] = $conditional_x;
        }


        //Save common base:
        if($is_first_in){

            //Make sure to add main idea to common idea:
            if(count($metadata_this['p___6168']) > 0){
                $metadata_this['p___6168'] = array_merge( array(intval($focus_in['i__id'])) , array($metadata_this['p___6168']));
            } else {
                $metadata_this['p___6168'] = array(intval($focus_in['i__id']));
            }

            update_metadata(12273, $focus_in['i__id'], array(
                'i___6168' => $metadata_this['p___6168'],
                'i___6228' => $metadata_this['p___6228'],
                'i___12885' => $metadata_this['p___12885'],
                'i___6283' => $metadata_this['p___6283'],
            ));

        }

        //Return results:
        return $metadata_this;

    }

    function mass_update($i__id, $action_e__id, $action_command1, $action_command2, $x__miner)
    {

        //Alert: Has a twin function called e_mass_update()

        boost_power();

        if(!in_array($action_e__id, $this->config->item('n___12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif(in_array($action_e__id , array(12591, 12592)) && !is_valid_e_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Title',
            );

        } elseif(in_array($action_e__id , array(12611, 12612)) && !is_valid_i_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Idea. Format must be: #123 Idea Title',
            );

        }



        //Basic input validation done, let's continue...


        //Fetch all children:
        $applied_success = 0; //To be populated...

        $is_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__status IN (' . join(',', $this->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i__id,
        ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));


        //Process request:
        foreach($is_next as $next_i) {

            //Logic here must match items in e_mass_actions config variable

            if(in_array($action_e__id , array(12591, 12592))){

                //Check if it hs this item:
                $e__profile_id = intval(one_two_explode('@',' ',$action_command1));
                $i_has_es = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $this->config->item('n___12273')) . ')' => null, //IDEA COIN
                    'x__right' => $next_i['i__id'],
                    '(x__up = '.$e__profile_id.' OR x__down = '.$e__profile_id.')' => null,
                ));

                if($action_e__id==12591 && !count($i_has_es)){

                    //Missing & Must be Added:
                    $this->X_model->create(array(
                        'x__miner' => $x__miner,
                        'x__up' => $e__profile_id,
                        'x__type' => 4983, //IDEA COIN
                        'x__message' => '@'.$e__profile_id,
                        'x__right' => $next_i['i__id'],
                    ), true);

                    $applied_success++;

                } elseif($action_e__id==12592 && count($i_has_es)){

                    //Has and must be deleted:
                    $this->X_model->update($i_has_es[0]['x__id'], array(
                        'x__status' => 6173,
                    ), $x__miner, 10678 /* IDEA NOTES Unpublished */);

                    $applied_success++;

                }

            } elseif(in_array($action_e__id , array(12611, 12612))){

                //Check if it hs this item:
                $adjust_i__id = intval(one_two_explode('#',' ',$action_command1));

                $is_previous = $this->X_model->fetch(array(
                    'x__status IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
                    'x__left' => $adjust_i__id,
                    'x__right' => $next_i['i__id'],
                ), array(), 0);

                //See how to adjust:
                if($action_e__id==12611 && !count($is_previous)){

                    $this->I_model->x_or_create('', $x__miner, $adjust_i__id, false, 6184, 6677, $next_i['i__id']);

                    //Add Source since not there:
                    $applied_success++;

                } elseif($action_e__id==12612 && count($is_previous)){

                    //Remove Source:
                    $this->X_model->update($is_previous[0]['x__id'], array(
                        'x__status' => 6173,
                    ), $x__miner, 10686 /* IDEA NOTES Unpublished */);

                    $applied_success++;

                }

            }

        }


        //Log mass source edit transaction:
        $this->X_model->create(array(
            'x__miner' => $x__miner,
            'x__type' => $action_e__id,
            'x__right' => $i__id,
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
            'message' => $applied_success . '/' . count($is_next) . ' ideas updated',
        );

    }


    function weight($i__id)
    {

        /*
         *
         * Addup weights recursively
         *
         * */


        $total_child_weights = 0;

        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i__id,
        ), array('x__right'), 0, 0, array(), 'i__id, i__weight') as $next_i){
            $total_child_weights += $next_i['i__weight'] + $this->I_model->weight($next_i['i__id']);
        }

        //Update This Level:
        if($total_child_weights > 0){
            $this->db->query("UPDATE mench__i SET i__weight=i__weight+".$total_child_weights." WHERE i__id=".$i__id.";");
        }

        //Return data:
        return $total_child_weights;

    }



    function metadata_e_insights($i)
    {

        /*
         *
         * Generates Idea Tree Insights like
         * min/max ideas, time & referenced
         * expert sources/channels.
         *
         * */

        $metadata_this = array(
            'p___6169' => 1,
            'p___6170' => 1,
            'p___6161' => $i['i__duration'],
            'p___6162' => $i['i__duration'],
            'p___13202' => array(),
            'p___13339' => array(),
            'p___3000' => array(),
            'p___7545' => array(),
            'p___ids' => array($i['i__id']), //Keeps Track of the IDs scanned here
        );


        //AGGREGATE IDEA SOURCES
        foreach($this->X_model->fetch(array(
            //Already for for x__up & x__down
            'x__right' => $i['i__id'],
            'x__type IN (' . join(',', $this->config->item('n___12273')).')' => null, //IDEA COIN
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0) as $fetched_e) {

            //SOURCES?
            foreach(array('x__up','x__down') as $e_ref_field){
                if($fetched_e[$e_ref_field] > 0){

                    $ref_es = $this->E_model->fetch(array(
                        'e__id' => $fetched_e[$e_ref_field],
                    ));

                    $e_metadata_experts = $this->E_model->metadata_experts($ref_es[0]);

                    //CONTENT CHANNELS
                    foreach($e_metadata_experts['p___3000'] as $e__id => $e_content) {
                        if (!isset($metadata_this['p___3000'][$e__id])) {
                            $metadata_this['p___3000'][$e__id] = $e_content;
                        }
                    }

                    //EXPERT PEOPLE/ORGANIZATIONS
                    foreach($e_metadata_experts['p___13339'] as $e__id => $e_expert) {
                        if (!isset($metadata_this['p___13339'][$e__id])) {
                            $metadata_this['p___13339'][$e__id] = $e_expert;
                        }
                    }
                }
            }

            //MINERS:
            if (!isset($metadata_this['p___13202'][$fetched_e['x__miner']])) {
                //Fetch Miner:
                foreach($this->X_model->fetch(array(
                    'x__up' => 4430, //MENCH MINERS
                    'x__down' => $fetched_e['x__miner'],
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array('x__down'), 1) as $miner){
                    $metadata_this['p___13202'][$fetched_e['x__miner']] = $miner;
                }
            }

        }


        //AGGREGATE CERTIFICATES
        foreach($this->X_model->fetch(array(
            'x__right' => $i['i__id'],
            'x__type' => 7545, //CERTIFICATES
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__status IN (' . join(',', $this->config->item('n___7357')) . ')' => null, //PUBLIC
        ), array('x__up'), 0) as $e) {
            if (!isset($metadata_this['p___7545'][$e['e__id']])) {
                $metadata_this['p___7545'][$e['e__id']] = $e;
            }
        }


        $metadata_local = array(
            'localp___6169'=> null,
            'localp___6170'=> null,
            'localp___6161'=> null,
            'localp___6162'=> null,
        );

        //NEXT IDEAS
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i['i__id'],
        ), array('x__right'), 0) as $is_next){

            //Miners
            if (!isset($metadata_this['p___13202'][$is_next['x__miner']])) {
                //Fetch Miner:
                foreach($this->X_model->fetch(array(
                    'x__up' => 4430, //MENCH MINERS
                    'x__down' => $is_next['x__miner'],
                    'x__type IN (' . join(',', $this->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array('x__down'), 1) as $miner){
                    $metadata_this['p___13202'][$is_next['x__miner']] = $miner;
                }
            }

            //RECURSION
            $metadata_recursion = $this->I_model->metadata_e_insights($is_next);


            //MERGE (3 SCENARIOS)
            if(in_array($is_next['x__type'], $this->config->item('n___12842')) || in_array($i['i__type'], $this->config->item('n___12883'))){

                //ONE

                //MIN
                if(is_null($metadata_local['localp___6169']) || $metadata_recursion['p___6169'] < $metadata_local['localp___6169']){
                    $metadata_local['localp___6169'] = $metadata_recursion['p___6169'];
                }
                if(is_null($metadata_local['localp___6161']) || $metadata_recursion['p___6161'] < $metadata_local['localp___6161']){
                    $metadata_local['localp___6161'] = $metadata_recursion['p___6161'];
                }

                //MAX
                if(is_null($metadata_local['localp___6170']) || $metadata_recursion['p___6170'] > $metadata_local['localp___6170']){
                    $metadata_local['localp___6170'] = $metadata_recursion['p___6170'];
                }
                if(is_null($metadata_local['localp___6162']) || $metadata_recursion['p___6162'] > $metadata_local['localp___6162']){
                    $metadata_local['localp___6162'] = $metadata_recursion['p___6162'];
                }

            } elseif(in_array($i['i__type'], $this->config->item('n___12884'))){

                //SOME

                //MIN
                if(is_null($metadata_local['localp___6169']) || $metadata_recursion['p___6169'] < $metadata_local['localp___6169']){
                    $metadata_local['localp___6169'] = $metadata_recursion['p___6169'];
                }
                if(is_null($metadata_local['localp___6161']) || $metadata_recursion['p___6161'] < $metadata_local['localp___6161']){
                    $metadata_local['localp___6161'] = $metadata_recursion['p___6161'];
                }

                //MAX
                $metadata_this['p___6170'] += intval($metadata_recursion['p___6170']);
                $metadata_this['p___6162'] += intval($metadata_recursion['p___6162']);

            } else {

                //ALL

                //MIN
                $metadata_this['p___6169'] += intval($metadata_recursion['p___6169']);
                $metadata_this['p___6161'] += intval($metadata_recursion['p___6161']);

                //MAX
                $metadata_this['p___6170'] += intval($metadata_recursion['p___6170']);
                $metadata_this['p___6162'] += intval($metadata_recursion['p___6162']);

            }


            //MINERS
            foreach($metadata_recursion['p___13202'] as $e__id => $e) {
                if (!isset($metadata_this['p___13202'][$e__id])) {
                    $metadata_this['p___13202'][$e__id] = $e;
                }
            }

            //EXPERT CONTENT
            foreach($metadata_recursion['p___3000'] as $e__id => $e_content) {
                if (!isset($metadata_this['p___3000'][$e__id])) {
                    $metadata_this['p___3000'][$e__id] = $e_content;
                }
            }

            //EXPERT SOURCES
            foreach($metadata_recursion['p___13339'] as $e__id => $e_expert) {
                if (!isset($metadata_this['p___13339'][$e__id])) {
                    $metadata_this['p___13339'][$e__id] = $e_expert;
                }
            }

            //CERTIFICATES
            foreach($metadata_recursion['p___7545'] as $e__id => $e_certificate) {
                if (!isset($metadata_this['p___7545'][$e__id])) {
                    $metadata_this['p___7545'][$e__id] = $e_certificate;
                }
            }

            //AGGREGATE IDS
            foreach($metadata_recursion['p___ids'] as $i__id) {
                if (!in_array(intval($i__id), $metadata_this['p___ids'])) {
                    array_push($metadata_this['p___ids'], intval($i__id));
                }
            }
        }


        //ADD LOCAL MIN/MAX
        if(!is_null($metadata_local['localp___6169'])){
            $metadata_this['p___6169'] += intval($metadata_local['localp___6169']);
        }
        if(!is_null($metadata_local['localp___6170'])){
            $metadata_this['p___6170'] += intval($metadata_local['localp___6170']);
        }
        if(!is_null($metadata_local['localp___6161'])){
            $metadata_this['p___6161'] += intval($metadata_local['localp___6161']);
        }
        if(!is_null($metadata_local['localp___6162'])){
            $metadata_this['p___6162'] += intval($metadata_local['localp___6162']);
        }

        //Save to DB
        update_metadata(12273, $i['i__id'], array(
            'i___6169' => intval($metadata_this['p___6169']),
            'i___6170' => intval($metadata_this['p___6170']),
            'i___6161' => intval($metadata_this['p___6161']),
            'i___6162' => intval($metadata_this['p___6162']),
            'i___13202' => $metadata_this['p___13202'], //Mench Ideators
            'i___13339' => $metadata_this['p___13339'], //Expert Authors
            'i___3000' => $metadata_this['p___3000'], //Expert Content
            'i___7545' => $metadata_this['p___7545'], //Certificates
        ));

        //Return data:
        return $metadata_this;

    }



    function unlock_paths($i)
    {
        /*
         *
         * Finds the pathways, if any, on how to unlock $i
         *
         * */


        //Validate this locked idea:
        if(!i_is_unlockable($i)){
            return array();
        }

        $child_unlock_paths = array();


        //Discovery 1: Is there an OR parent that we can simply answer and unlock?
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__right' => $i['i__id'],
            'i__type IN (' . join(',', $this->config->item('n___7712')) . ')' => null,
        ), array('x__left'), 0) as $i_or_parent){
            if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $i_or_parent['i__id'])) {
                array_push($child_unlock_paths, $i_or_parent);
            }
        }


        //Discovery 2: Are there any locked transaction parents that the miner might be able to unlock?
        foreach($this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12842')) . ')' => null, //IDEA LINKS ONE-WAY
            'x__right' => $i['i__id'],
        ), array('x__left'), 0) as $i_locked_parent){
            if(i_is_unlockable($i_locked_parent)){
                //Need to check recursively:
                foreach($this->I_model->unlock_paths($i_locked_parent) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $locked_path['i__id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }
            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $i_locked_parent['i__id'])) {
                array_push($child_unlock_paths, $i_locked_parent);
            }
        }


        //Return if we have options for step 1 OR step 2:
        if(count($child_unlock_paths) > 0){
            //Return OR parents for unlocking this idea:
            return $child_unlock_paths;
        }


        //Discovery 3: We don't have any OR parents, let's see how we can complete all children to meet the requirements:
        $is_next = $this->X_model->fetch(array(
            'x__status IN (' . join(',', $this->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__status IN (' . join(',', $this->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $this->config->item('n___12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'x__left' => $i['i__id'],
        ), array('x__right'), 0, 0, array('x__sort' => 'ASC'));
        if(count($is_next) < 1){
            //No children, no path:
            return array();
        }

        //Go through children to see if any/all can be completed:
        foreach($is_next as $next_i){
            if(i_is_unlockable($next_i)){

                //Need to check recursively:
                foreach($this->I_model->unlock_paths($next_i) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $locked_path['i__id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }

            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'i__id', $next_i['i__id'])) {

                //Not locked, so this can be completed:
                array_push($child_unlock_paths, $next_i);

            }
        }
        return $child_unlock_paths;

    }

}