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


    function create($add_fields, $read__source = 0)
    {

        //What is required to create a new Idea?
        if (detect_missing_columns($add_fields, array('idea__title', 'idea__type', 'idea__status'), $read__source)) {
            return false;
        }

        if(!isset($add_fields['idea__duration']) || $add_fields['idea__duration'] < config_var(12427)){
            $add_fields['idea__duration'] = config_var(12176);
        }

        //Lets now add:
        $this->db->insert('mench_idea', $add_fields);

        //Fetch inserted id:
        if (!isset($add_fields['idea__id'])) {
            $add_fields['idea__id'] = $this->db->insert_id();
        }

        if ($add_fields['idea__id'] > 0) {

            if ($read__source > 0) {

                //Log link new Idea:
                $this->READ_model->create(array(
                    'read__source' => $read__source,
                    'read__right' => $add_fields['idea__id'],
                    'read__message' => $add_fields['idea__title'],
                    'read__type' => 4250, //New Idea Created
                ));

                //Also add as source:
                $this->READ_model->create(array(
                    'read__source' => $read__source,
                    'read__up' => $read__source,
                    'read__type' => 4983, //IDEA COIN
                    'read__message' => '@'.$read__source,
                    'read__right' => $add_fields['idea__id'],
                ), true);

                //Fetch to return the complete source data:
                $ideas = $this->IDEA_model->fetch(array(
                    'idea__id' => $add_fields['idea__id'],
                ));

                //Update Algolia:
                update_algolia(4535, $add_fields['idea__id']);

                return $ideas[0];

            } else {

                //Return provided inputs plus the new source ID:
                return $add_fields;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->READ_model->create(array(
                'read__message' => 'idea_create() failed to create a new idea',
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => $read__source,
                'read__metadata' => $add_fields,
            ));
            return false;

        }
    }

    function fetch($match_columns = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
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

    function update($id, $update_columns, $external_sync = false, $read__source = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current Idea filed values so we can compare later on after we've updated it:
        if($read__source > 0){
            $before_data = $this->IDEA_model->fetch(array('idea__id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['idea__metadata']) && is_array($update_columns['idea__metadata'])) {
            $update_columns['idea__metadata'] = serialize($update_columns['idea__metadata']);
        }

        //Update:
        $this->db->where('idea__id', $id);
        $this->db->update('mench_idea', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $read__source > 0) {

            //Unlike source modification, we require a player source ID to log the modification link:
            //Log modification link for every field changed:
            foreach($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //Assume no SOURCE LINKS unless specifically defined:
                $read__down = 0;
                $read__up = 0;


                if($key=='idea__title') {

                    $read__type = 10644; //Idea updated Outcome
                    $read__message = update_description($before_data[0][$key], $value);

                } elseif($key=='idea__status'){

                    if(in_array($value, $this->config->item('sources_id_7356') /* ACTIVE */)){
                        $read__type = 10648; //Idea updated Status
                    } else {
                        $read__type = 6182; //Idea Deleted
                    }
                    $sources__4737 = $this->config->item('sources__4737'); //Idea Status
                    $read__message = view_db_field($key) . ' updated from [' . $sources__4737[$before_data[0][$key]]['m_name'] . '] to [' . $sources__4737[$value]['m_name'] . ']';
                    $read__up = $value;
                    $read__down = $before_data[0][$key];

                } elseif($key=='idea__type'){

                    $read__type = 10651; //Idea updated Subtype
                    $sources__7585 = $this->config->item('sources__7585'); //Idea Subtypes
                    $read__message = view_db_field($key) . ' updated from [' . $sources__7585[$before_data[0][$key]]['m_name'] . '] to [' . $sources__7585[$value]['m_name'] . ']';
                    $read__up = $value;
                    $read__down = $before_data[0][$key];

                } elseif($key=='idea__duration') {

                    $read__type = 10650; //Idea updated Completion Time
                    $read__message = view_db_field($key) . ' updated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }


                //Value has changed, log link:
                $this->READ_model->create(array(
                    'read__source' => $read__source,
                    'read__type' => $read__type,
                    'read__right' => $id,
                    'read__down' => $read__down,
                    'read__up' => $read__up,
                    'read__message' => $read__message,
                    'read__metadata' => array(
                        'idea__id' => $id,
                        'field' => $key,
                        'before' => $before_data[0][$key],
                        'after' => $value,
                    ),
                ));

            }

            if($external_sync){
                //Sync algolia:
                update_algolia(4535, $id);
            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->READ_model->create(array(
                'read__right' => $id,
                'read__type' => 4246, //Platform Bug Reports
                'read__source' => $read__source,
                'read__message' => 'update() Failed to update',
                'read__metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function unlink($idea__id, $read__source = 0){

        //REMOVE IDEA LINKS
        $links_deleted = 0;
        foreach($this->READ_model->fetch(array( //Idea Links
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            '(read__right = '.$idea__id.' OR read__left = '.$idea__id.')' => null,
        ), array(), 0) as $ln){
            //Delete this link:
            $links_deleted += $this->READ_model->update($ln['read__id'], array(
                'read__status' => 6173, //Link Deleted
            ), $read__source, 10686 /* Idea Link Unpublished */);
        }


        //REMOVE NOTES:
        $idea_notes = $this->READ_model->fetch(array( //Idea Links
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type IN (' . join(',', $this->config->item('sources_id_4485')) . ')' => null, //IDEA NOTES
            'read__right' => $idea__id,
        ), array(), 0);
        foreach($idea_notes as $idea_note){
            //Delete this link:
            $links_deleted += $this->READ_model->update($idea_note['read__id'], array(
                'read__status' => 6173, //Link Deleted
            ), $read__source, 10686 /* Idea Link Unpublished */);
        }


        //Return links deleted:
        return $links_deleted;
    }

    function match_read_status($read__source, $query = array()){

        //STATS
        $stats = array(
            'read__type' => 4250, //Idea Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        $status_converter = array(
            12137 => 12399, //IDEA FEATURE  => READ FEATURE
            6184 => 6176,   //IDEA PUBLISH  => READ PUBLISH
            6183 => 6175,   //IDEA DRAFT    => READ DRAFT
            6182 => 6173,   //IDEA DELETE   => READ DELETE
        );


        foreach($this->IDEA_model->fetch($query) as $idea){

            $stats['scanned']++;

            //Find creation read:
            $reads = $this->READ_model->fetch(array(
                'read__type' => $stats['read__type'],
                'read__right' => $idea['idea__id'],
            ));

            if(!count($reads)){

                $stats['missing_creation_fix']++;

                $this->READ_model->create(array(
                    'read__source' => $read__source,
                    'read__right' => $idea['idea__id'],
                    'read__message' => $idea['idea__title'],
                    'read__type' => $stats['read__type'],
                    'read__status' => $status_converter[$idea['idea__status']],
                ));

            } elseif($reads[0]['read__status'] != $status_converter[$idea['idea__status']]){

                $stats['status_sync']++;
                $this->READ_model->update($reads[0]['read__id'], array(
                    'read__status' => $status_converter[$idea['idea__status']],
                ));

            }

        }

        return $stats;
    }

    function link_or_create($idea__title, $read__source, $link_to_idea__id = 0, $is_parent = false, $new_idea_status = 6184, $idea__type = 6677 /* Idea Read-Only */, $link_idea__id = 0)
    {

        /*
         *
         * The main idea creation function that would create
         * appropriate links and return the idea view.
         *
         * Either creates an IDEA link between $link_to_idea__id & $link_idea__id
         * (IF $link_idea__id>0) OR will create a new idea with outcome $idea__title
         * and link it to $link_to_idea__id (In this case $link_idea__id will be 0)
         *
         * p.s. Inputs have previously been validated via ideas/idea_add() function
         *
         * */

        //Validate Original idea:
        if($link_to_idea__id > 0){
            $linked_ideas = $this->IDEA_model->fetch(array(
                'idea__id' => intval($link_to_idea__id),
            ));

            if (count($linked_ideas) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Idea ID',
                );
            } elseif (!in_array($linked_ideas[0]['idea__status'], $this->config->item('sources_id_7356')) /* ACTIVE */) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active ideas. This idea is not active.',
                );
            }
        }


        if ($link_idea__id > 0) {

            //We are linking to $link_idea__id, We are NOT creating any new ideas...

            //Fetch more details on the child idea we're about to link:
            $ideas = $this->IDEA_model->fetch(array(
                'idea__id' => $link_idea__id,
            ));

            //Determine which is parent Idea, and which is child
            if($is_parent){

                $previous_idea = $ideas[0];
                $next_idea = $linked_ideas[0];

                /*
                //Prevent child duplicates:
                $recursive_children = $this->IDEA_model->recursive_child_ids($next_idea['idea__id'], false);
                if (in_array($previous_idea['idea__id'], $recursive_children)) {
                    return array(
                        'status' => 0,
                        'message' => 'Idea previously set as child, so it cannot be added as parent',
                    );
                }
                */

            } else {

                $previous_idea = $linked_ideas[0];
                $next_idea = $ideas[0];

                //Prevent parent duplicate:
                $recursive_parents = $this->IDEA_model->recursive_parents($previous_idea['idea__id']);
                foreach($recursive_parents as $grand_parent_ids) {
                    if (in_array($next_idea['idea__id'], $grand_parent_ids)) {
                        return array(
                            'status' => 0,
                            'message' => 'Idea previously set as parent, so it cannot be added as child',
                        );
                    }
                }
            }


            if (count($ideas) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Idea ID',
                );
            } elseif (!in_array($ideas[0]['idea__status'], $this->config->item('sources_id_7356') /* ACTIVE */)) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active ideas. This idea is not active.',
                );
            }

            //All good so far, continue with linking:
            $idea_new = $ideas[0];

            //Make sure this is not a duplicate Idea for its parent:
            $dup_links = $this->READ_model->fetch(array(
                'read__left' => $previous_idea['idea__id'],
                'read__right' => $next_idea['idea__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            ));

            //Check for issues:
            if (count($dup_links) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $idea_new['idea__title'] . '] is previously linked here.',
                );

            } elseif ($link_to_idea__id > 0 && $link_idea__id == $link_to_idea__id) {

                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $idea_new['idea__title'] . '" as its own '.( $is_parent ? 'previous' : 'next' ).' idea.',
                );

            }

        } else {

            //We are NOT linking to an existing Idea, but instead, we're creating a new Idea

            //Validate Idea Outcome:
            $idea__title_validation = idea__title_validate($idea__title);
            if(!$idea__title_validation['status']){
                //We had an error, return it:
                return $idea__title_validation;
            }


            //Create new Idea:
            $idea_new = $this->IDEA_model->create(array(
                'idea__title' => $idea__title_validation['idea_clean_title'],
                'idea__type' => $idea__type,
                'idea__status' => $new_idea_status,
            ), $read__source);

        }


        //Create Idea Link:
        if($link_to_idea__id > 0){

            $relation = $this->READ_model->create(array(
                'read__source' => $read__source,
                'read__type' => 4228, //Idea Link Regular Reads
                ( $is_parent ? 'read__right' : 'read__left' ) => $link_to_idea__id,
                ( $is_parent ? 'read__left' : 'read__right' ) => $idea_new['idea__id'],
                'read__sort' => 1 + $this->READ_model->max_order(array(
                        'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                        'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
                        'read__left' => ( $is_parent ? $idea_new['idea__id'] : $link_to_idea__id ),
                    )),
            ), true);

            //Fetch and return full data to be properly shown on the UI using the view_idea() function
            $new_ideas = $this->READ_model->fetch(array(
                ( $is_parent ? 'read__right' : 'read__left' ) => $link_to_idea__id,
                ( $is_parent ? 'read__left' : 'read__right' ) => $idea_new['idea__id'],
                'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
                'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
                'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
            ), array(($is_parent ? 'read__left' : 'read__right')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


            $next_idea_html = view_idea($new_ideas[0], $link_to_idea__id, $is_parent, true /* Since they added it! */);

        } else {

            $next_idea_html = null;

        }

        //Return result:
        return array(
            'status' => 1,
            'new_idea__id' => $idea_new['idea__id'],
            'idea_next_html' => $next_idea_html,
        );

    }



    function recursive_parents($idea__id, $first_level = true, $public_only = true)
    {

        $grand_parents = array();

        //Fetch parents:
        foreach($this->READ_model->fetch(array(
            'idea__status IN (' . join(',', $this->config->item(($public_only ? 'sources_id_7355' : 'sources_id_7356'))) . ')' => null,
            'read__status IN (' . join(',', $this->config->item(($public_only ? 'sources_id_7359' : 'sources_id_7360'))) . ')' => null,
            'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__right' => $idea__id,
        ), array('read__left')) as $idea_previous) {

            //Prep ID:
            $p_id = intval($idea_previous['idea__id']);

            //Add to appropriate array:
            if (!$first_level) {
                array_push($grand_parents, $p_id);
            }


            //Fetch parents of parents:
            $recursive_parents = $this->IDEA_model->recursive_parents($p_id, false);

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
            $start_idea__id = config_var(12156);
            $index = 0;
            foreach($grand_parents as $grand_parent_ids) {
                foreach($grand_parent_ids as $grand_parent_id) {
                    if (!isset($recursive_parents[$index])) {
                        $recursive_parents[$index] = array();
                    }
                    array_push($recursive_parents[$index], intval($grand_parent_id));
                    if ($grand_parent_id == $start_idea__id) {
                        $index++;
                    }
                }
            }

            return $recursive_parents;

        } else {
            return $grand_parents;
        }
    }


    function recursive_child_ids($idea__id, $first_level = true){

        $child_ids = array();

        //Fetch parents:
        foreach($this->READ_model->fetch(array(
            'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__left' => $idea__id,
        ), array('read__right')) as $next_idea){

            array_push($child_ids, intval($next_idea['idea__id']));

            //Fetch parents of parents:
            $recursive_children = $this->IDEA_model->recursive_child_ids($next_idea['idea__id'], false);

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
        $is_first_in = ( !isset($focus_in['read__id']) ); //First idea does not have a link, just the idea
        $select_one = in_array($focus_in['idea__type'] , $this->config->item('sources_id_12883')); //IDEA TYPE SELECT ONE
        $select_some = in_array($focus_in['idea__type'] , $this->config->item('sources_id_12884')); //IDEA TYPE SELECT SOME
        $select_one_children = array(); //To be populated only if $focus_in is select one
        $select_some_children = array(); //To be populated only if $focus_in is select some
        $conditional_reads = array(); //To be populated only for Conditional Ideas
        $metadata_this = array(
            '__idea___common_reads' => array(), //The idea structure that would be shared with all users regardless of their quick replies (OR Idea Answers)
            '__idea___expansion_reads' => array(), //Ideas that may exist as a link to expand an Reads idea by answering OR ideas
            '__idea___expansion_some' => array(), //Ideas that allows players to select one or more
            '__idea___expansion_conditional' => array(), //Ideas that may exist as a link to expand an Reads idea via Conditional Idea links
        );

        //Fetch children:
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__left' => $focus_in['idea__id'],
        ), array('read__right'), 0, 0, array('read__sort' => 'ASC')) as $next_idea){

            //Determine action based on parent idea type:
            if(in_array($next_idea['read__type'], $this->config->item('sources_id_12842'))){

                //Conditional Idea Link:
                array_push($conditional_reads, intval($next_idea['idea__id']));

            } elseif($select_one){

                //OR parent Idea with Fixed Idea Link:
                array_push($select_one_children, intval($next_idea['idea__id']));

            } elseif($select_some){

                //OR parent Idea with Fixed Idea Link:
                array_push($select_some_children, intval($next_idea['idea__id']));

            } else {

                //AND parent Idea with Fixed Idea Link:
                array_push($metadata_this['__idea___common_reads'], intval($next_idea['idea__id']));

                //Go recursively down:
                $child_recursion = $this->IDEA_model->metadata_common_base($next_idea);


                //Aggregate recursion data:
                if(count($child_recursion['__idea___common_reads']) > 0){
                    array_push($metadata_this['__idea___common_reads'], $child_recursion['__idea___common_reads']);
                }

                //Merge expansion steps:
                if(count($child_recursion['__idea___expansion_reads']) > 0){
                    foreach($child_recursion['__idea___expansion_reads'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['__idea___expansion_reads'])){
                            $metadata_this['__idea___expansion_reads'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['__idea___expansion_some']) > 0){
                    foreach($child_recursion['__idea___expansion_some'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['__idea___expansion_some'])){
                            $metadata_this['__idea___expansion_some'][$key] = $value;
                        }
                    }
                }
                if(count($child_recursion['__idea___expansion_conditional']) > 0){
                    foreach($child_recursion['__idea___expansion_conditional'] as $key => $value){
                        if(!array_key_exists($key, $metadata_this['__idea___expansion_conditional'])){
                            $metadata_this['__idea___expansion_conditional'][$key] = $value;
                        }
                    }
                }
            }
        }


        //Was this an OR branch that needs it's children added to the array?
        if($select_one && count($select_one_children) > 0){
            $metadata_this['__idea___expansion_reads'][$focus_in['idea__id']] = $select_one_children;
        }
        if($select_some && count($select_some_children) > 0){
            $metadata_this['__idea___expansion_some'][$focus_in['idea__id']] = $select_some_children;
        }
        if(count($conditional_reads) > 0){
            $metadata_this['__idea___expansion_conditional'][$focus_in['idea__id']] = $conditional_reads;
        }


        //Save common base:
        if($is_first_in){

            //Make sure to add main idea to common idea:
            if(count($metadata_this['__idea___common_reads']) > 0){
                $metadata_this['__idea___common_reads'] = array_merge( array(intval($focus_in['idea__id'])) , array($metadata_this['__idea___common_reads']));
            } else {
                $metadata_this['__idea___common_reads'] = array(intval($focus_in['idea__id']));
            }

            update_metadata(4535, $focus_in['idea__id'], array(
                'idea___common_reads' => $metadata_this['__idea___common_reads'],
                'idea___expansion_reads' => $metadata_this['__idea___expansion_reads'],
                'idea___expansion_some' => $metadata_this['__idea___expansion_some'],
                'idea___expansion_conditional' => $metadata_this['__idea___expansion_conditional'],
            ));

        }

        //Return results:
        return $metadata_this;

    }

    function mass_update($idea__id, $action_source__id, $action_command1, $action_command2, $read__source)
    {

        //Alert: Has a twin function called source_mass_update()

        boost_power();

        if(!in_array($action_source__id, $this->config->item('sources_id_12589'))) {

            return array(
                'status' => 0,
                'message' => 'Unknown mass action',
            );

        } elseif(!is_valid_source_string($action_command1)){

            return array(
                'status' => 0,
                'message' => 'Unknown Source. Format must be: @123 Source Name',
            );

        }



        //Basic input validation done, let's continue...


        //Fetch all children:
        $applied_success = 0; //To be populated...

        $ideas_next = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'idea__status IN (' . join(',', $this->config->item('sources_id_7356')) . ')' => null, //ACTIVE
            'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'read__left' => $idea__id,
        ), array('read__right'), 0, 0, array('read__sort' => 'ASC'));


        //Process request:
        foreach($ideas_next as $idea) {

            //Logic here must match items in source_mass_actions config variable

            if(in_array($action_source__id , array(12591, 12592))){

                //Check if it hs this item:
                $source__profile_id = intval(one_two_explode('@',' ',$action_command1));
                $idea_has_sources = $this->READ_model->fetch(array(
                    'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
                    'read__type IN (' . join(',', $this->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
                    'read__right' => $idea['idea__id'],
                    'read__up' => $source__profile_id,
                ));

                if($action_source__id==12591 && !count($idea_has_sources)){

                    //Missing & Must be Added:
                    $this->READ_model->create(array(
                        'read__source' => $read__source,
                        'read__up' => $source__profile_id,
                        'read__type' => 4983, //IDEA COIN
                        'read__message' => '@'.$source__profile_id,
                        'read__right' => $idea['idea__id'],
                    ), true);

                    $applied_success++;

                } elseif($action_source__id==12592 && count($idea_has_sources)){

                    //Has and must be deleted:
                    $this->READ_model->update($idea_has_sources[0]['read__id'], array(
                        'read__status' => 6173,
                    ), $read__source, 10678 /* IDEA NOTES Unpublished */);

                    $applied_success++;

                }

            } elseif(in_array($action_source__id , array(12611, 12612))){

                //TODO here

            }

        }


        //Log mass source edit link:
        $this->READ_model->create(array(
            'read__source' => $read__source,
            'read__type' => $action_source__id,
            'read__right' => $idea__id,
            'read__metadata' => array(
                'payload' => $_POST,
                'ideas_total' => count($ideas_next),
                'ideas_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => $applied_success . '/' . count($ideas_next) . ' ideas updated',
        );

    }


    function weight($idea__id)
    {

        /*
         *
         * Addup weights recursively
         *
         * */


        $total_child_weights = 0;

        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'read__left' => $idea__id,
        ), array('read__right'), 0, 0, array(), 'idea__id, idea__weight') as $idea_next){
            $total_child_weights += $idea_next['idea__weight'] + $this->IDEA_model->weight($idea_next['idea__id']);
        }

        //Update This Level:
        if($total_child_weights > 0){
            $this->db->query("UPDATE mench_idea SET idea__weight=idea__weight+".$total_child_weights." WHERE idea__id=".$idea__id.";");
        }

        //Return data:
        return $total_child_weights;

    }



    function metadata_extra_insights($idea)
    {

        /*
         *
         * Generates Idea Tree Insights like
         * min/max ideas, time & referenced
         * expert sources/channels.
         *
         * */

        $metadata_this = array(
            '__idea___min_reads' => 1,
            '__idea___max_reads' => 1,
            '__idea___min_seconds' => $idea['idea__duration'],
            '__idea___max_seconds' => $idea['idea__duration'],
            '__idea___experts' => array(),
            '__idea___content' => array(),
        );


        //AGGREGATE IDEA SOURCES
        foreach($this->READ_model->fetch(array(
            'read__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
            'read__right' => $idea['idea__id'],
            'read__type IN (' . join(',', $this->config->item('sources_id_12273')).')' => null, //IDEA COIN
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'source__status IN (' . join(',', $this->config->item('sources_id_7357')) . ')' => null, //PUBLIC
        ), array('read__up'), 0) as $source) {

            $source_metadat_experts = $this->SOURCE_model->metadat_experts($source);

            //CONTENT CHANNELS
            foreach($source_metadat_experts['__idea___content'] as $source__id => $source_content) {
                if (!isset($metadata_this['__idea___content'][$source__id])) {
                    $metadata_this['__idea___content'][$source__id] = $source_content;
                }
            }

            //EXPERT PEOPLE/ORGANIZATIONS
            foreach($source_metadat_experts['__idea___experts'] as $source__id => $source_expert) {
                if (!isset($metadata_this['__idea___experts'][$source__id])) {
                    $metadata_this['__idea___experts'][$source__id] = $source_expert;
                }
            }
        }


        $metadata_local = array(
            'local__idea___min_reads'=> null,
            'local__idea___max_reads'=> null,
            'local__idea___min_seconds'=> null,
            'local__idea___max_seconds'=> null,
        );

        //NEXT IDEAS
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__left' => $idea['idea__id'],
        ), array('read__right'), 0) as $ideas_next){

            //RECURSION
            $metadata_recursion = $this->IDEA_model->metadata_extra_insights($ideas_next);
            if(!$metadata_recursion){
                continue;
            }

            //MERGE (3 SCENARIOS)
            if(in_array($ideas_next['read__type'], $this->config->item('sources_id_12842')) || in_array($idea['idea__type'], $this->config->item('sources_id_12883'))){

                //ONE

                //MIN
                if(is_null($metadata_local['local__idea___min_reads']) || $metadata_recursion['__idea___min_reads'] < $metadata_local['local__idea___min_reads']){
                    $metadata_local['local__idea___min_reads'] = $metadata_recursion['__idea___min_reads'];
                }
                if(is_null($metadata_local['local__idea___min_seconds']) || $metadata_recursion['__idea___min_seconds'] < $metadata_local['local__idea___min_seconds']){
                    $metadata_local['local__idea___min_seconds'] = $metadata_recursion['__idea___min_seconds'];
                }

                //MAX
                if(is_null($metadata_local['local__idea___max_reads']) || $metadata_recursion['__idea___max_reads'] > $metadata_local['local__idea___max_reads']){
                    $metadata_local['local__idea___max_reads'] = $metadata_recursion['__idea___max_reads'];
                }
                if(is_null($metadata_local['local__idea___max_seconds']) || $metadata_recursion['__idea___max_seconds'] > $metadata_local['local__idea___max_seconds']){
                    $metadata_local['local__idea___max_seconds'] = $metadata_recursion['__idea___max_seconds'];
                }

            } elseif(in_array($idea['idea__type'], $this->config->item('sources_id_12884'))){

                //SOME

                //MIN
                if(is_null($metadata_local['local__idea___min_reads']) || $metadata_recursion['__idea___min_reads'] < $metadata_local['local__idea___min_reads']){
                    $metadata_local['local__idea___min_reads'] = $metadata_recursion['__idea___min_reads'];
                }
                if(is_null($metadata_local['local__idea___min_seconds']) || $metadata_recursion['__idea___min_seconds'] < $metadata_local['local__idea___min_seconds']){
                    $metadata_local['local__idea___min_seconds'] = $metadata_recursion['__idea___min_seconds'];
                }

                //MAX
                $metadata_this['__idea___max_reads'] += intval($metadata_recursion['__idea___max_reads']);
                $metadata_this['__idea___max_seconds'] += intval($metadata_recursion['__idea___max_seconds']);

            } else {

                //ALL

                //MIN
                $metadata_this['__idea___min_reads'] += intval($metadata_recursion['__idea___min_reads']);
                $metadata_this['__idea___min_seconds'] += intval($metadata_recursion['__idea___min_seconds']);

                //MAX
                $metadata_this['__idea___max_reads'] += intval($metadata_recursion['__idea___max_reads']);
                $metadata_this['__idea___max_seconds'] += intval($metadata_recursion['__idea___max_seconds']);

            }


            //EXPERT CONTENT
            foreach($metadata_recursion['__idea___content'] as $source__id => $source_content) {
                if (!isset($metadata_this['__idea___content'][$source__id])) {
                    $metadata_this['__idea___content'][$source__id] = $source_content;
                }
            }

            //EXPERT PEOPLE/ORGANIZATIONS
            foreach($metadata_recursion['__idea___experts'] as $source__id => $source_expert) {
                if (!isset($metadata_this['__idea___experts'][$source__id])) {
                    $metadata_this['__idea___experts'][$source__id] = $source_expert;
                }
            }
        }


        //ADD LOCAL MIN/MAX
        if(!is_null($metadata_local['local__idea___min_reads'])){
            $metadata_this['__idea___min_reads'] += intval($metadata_local['local__idea___min_reads']);
        }
        if(!is_null($metadata_local['local__idea___max_reads'])){
            $metadata_this['__idea___max_reads'] += intval($metadata_local['local__idea___max_reads']);
        }
        if(!is_null($metadata_local['local__idea___min_seconds'])){
            $metadata_this['__idea___min_seconds'] += intval($metadata_local['local__idea___min_seconds']);
        }
        if(!is_null($metadata_local['local__idea___max_seconds'])){
            $metadata_this['__idea___max_seconds'] += intval($metadata_local['local__idea___max_seconds']);
        }

        //Save to DB
        update_metadata(4535, $idea['idea__id'], array(
            'idea___min_reads' => intval($metadata_this['__idea___min_reads']),
            'idea___max_reads' => intval($metadata_this['__idea___max_reads']),
            'idea___min_seconds' => intval($metadata_this['__idea___min_seconds']),
            'idea___max_seconds' => intval($metadata_this['__idea___max_seconds']),
            'idea___experts' => $metadata_this['__idea___experts'],
            'idea___content' => $metadata_this['__idea___content'],
        ));

        //Return data:
        return $metadata_this;

    }



    function unlock_paths($idea)
    {
        /*
         *
         * Finds the pathways, if any, on how to unlock $idea
         *
         * */


        //Validate this locked idea:
        if(!idea_is_unlockable($idea)){
            return array();
        }

        $child_unlock_paths = array();


        //Reads 1: Is there an OR parent that we can simply answer and unlock?
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'read__right' => $idea['idea__id'],
            'idea__type IN (' . join(',', $this->config->item('sources_id_7712')) . ')' => null,
        ), array('read__left'), 0) as $idea_or_parent){
            if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'idea__id', $idea_or_parent['idea__id'])) {
                array_push($child_unlock_paths, $idea_or_parent);
            }
        }


        //Reads 2: Are there any locked link parents that the user might be able to unlock?
        foreach($this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12842')) . ')' => null, //IDEA LINKS ONE-WAY
            'read__right' => $idea['idea__id'],
        ), array('read__left'), 0) as $idea_locked_parent){
            if(idea_is_unlockable($idea_locked_parent)){
                //Need to check recursively:
                foreach($this->IDEA_model->unlock_paths($idea_locked_parent) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'idea__id', $locked_path['idea__id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }
            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'idea__id', $idea_locked_parent['idea__id'])) {
                array_push($child_unlock_paths, $idea_locked_parent);
            }
        }


        //Return if we have options for step 1 OR step 2:
        if(count($child_unlock_paths) > 0){
            //Return OR parents for unlocking this idea:
            return $child_unlock_paths;
        }


        //Reads 3: We don't have any OR parents, let's see how we can complete all children to meet the requirements:
        $ideas_next = $this->READ_model->fetch(array(
            'read__status IN (' . join(',', $this->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'idea__status IN (' . join(',', $this->config->item('sources_id_7355')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $this->config->item('sources_id_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'read__left' => $idea['idea__id'],
        ), array('read__right'), 0, 0, array('read__sort' => 'ASC'));
        if(count($ideas_next) < 1){
            //No children, no path:
            return array();
        }

        //Go through children to see if any/all can be completed:
        foreach($ideas_next as $next_idea){
            if(idea_is_unlockable($next_idea)){

                //Need to check recursively:
                foreach($this->IDEA_model->unlock_paths($next_idea) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'idea__id', $locked_path['idea__id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }

            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'idea__id', $next_idea['idea__id'])) {

                //Not locked, so this can be completed:
                array_push($child_unlock_paths, $next_idea);

            }
        }
        return $child_unlock_paths;

    }

}