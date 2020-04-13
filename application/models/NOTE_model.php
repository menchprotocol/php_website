<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class NOTE_model extends CI_Model
{

    /*
     *
     * Note related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function in_create($insert_columns, $external_sync = false, $ln_creator_source_id = 0)
    {

        //What is required to create a new Note?
        if (detect_missing_columns($insert_columns, array('in_title', 'in_type_source_id', 'in_status_source_id'), $ln_creator_source_id)) {
            return false;
        }

        if(!isset($insert_columns['in_read_time']) || $insert_columns['in_read_time'] < 0){
            $insert_columns['in_read_time'] = config_var(12176);
        }

        //Lets now add:
        $this->db->insert('mench_notes', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['in_id'])) {
            $insert_columns['in_id'] = $this->db->insert_id();
        }

        if ($insert_columns['in_id'] > 0) {

            if($external_sync){
                //Update Algolia:
                $algolia_sync = update_algolia('in', $insert_columns['in_id']);
            }

            if ($ln_creator_source_id > 0) {

                //Log link new Note:
                $this->READ_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_next_note_id' => $insert_columns['in_id'],
                    'ln_content' => $insert_columns['in_title'],
                    'ln_type_source_id' => 4250, //New Note Created
                    'ln_status_source_id' => 6175, //Drafting
                ));

                //Also add as author:
                $this->READ_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_parent_source_id' => $ln_creator_source_id,
                    'ln_type_source_id' => 4983,
                    'ln_content' => '@'.$ln_creator_source_id,
                    'ln_next_note_id' => $insert_columns['in_id'],
                ), $external_sync);

                //Fetch to return the complete source data:
                $ins = $this->NOTE_model->in_fetch(array(
                    'in_id' => $insert_columns['in_id'],
                ));

                return $ins[0];

            } else {

                //Return provided inputs plus the new source ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->READ_model->ln_create(array(
                'ln_content' => 'in_create() failed to create a new note',
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function in_fetch($match_columns = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for Notes
        $this->db->select($select);
        $this->db->from('mench_notes');

        foreach ($match_columns as $key => $value) {
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
        return $q->result_array();
    }

    function in_update($id, $update_columns, $external_sync = false, $ln_creator_source_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current Note filed values so we can compare later on after we've updated it:
        if($ln_creator_source_id > 0){
            $before_data = $this->NOTE_model->in_fetch(array('in_id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['in_metadata']) && is_array($update_columns['in_metadata'])) {
            $update_columns['in_metadata'] = serialize($update_columns['in_metadata']);
        }

        //Update:
        $this->db->where('in_id', $id);
        $this->db->update('mench_notes', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $ln_creator_source_id > 0) {

            //Unlike source modification, we require a trainer source ID to log the modification link:
            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                if ($before_data[0][$key] == $value){
                    //Nothing changed:
                    continue;
                }

                //Assume no source links unless specifically defined:
                $ln_child_source_id = 0;
                $ln_parent_source_id = 0;


                if($key=='in_title') {

                    $ln_type_source_id = 10644; //Note Iterated Outcome
                    $ln_content = update_description($before_data[0][$key], $value);

                } elseif($key=='in_status_source_id'){

                    if(in_array($value, $this->config->item('en_ids_7356') /* Note Status Active */)){
                        $ln_type_source_id = 10648; //Note Iterated Status
                    } else {
                        $ln_type_source_id = 6182; //Note Archived
                    }
                    $en_all_4737 = $this->config->item('en_all_4737'); //Note Status
                    $ln_content = echo_clean_db_name($key) . ' iterated from [' . $en_all_4737[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_4737[$value]['m_name'] . ']';
                    $ln_parent_source_id = $value;
                    $ln_child_source_id = $before_data[0][$key];

                } elseif($key=='in_type_source_id'){

                    $ln_type_source_id = 10651; //Note Iterated Subtype
                    $en_all_7585 = $this->config->item('en_all_7585'); //Note Subtypes
                    $ln_content = echo_clean_db_name($key) . ' iterated from [' . $en_all_7585[$before_data[0][$key]]['m_name'] . '] to [' . $en_all_7585[$value]['m_name'] . ']';
                    $ln_parent_source_id = $value;
                    $ln_child_source_id = $before_data[0][$key];

                } elseif($key=='in_read_time') {

                    $ln_type_source_id = 10650; //Note Iterated Completion Time
                    $ln_content = echo_clean_db_name($key) . ' iterated from [' . $before_data[0][$key] . '] to [' . $value . ']';

                } else {

                    //Should not log updates since not specifically programmed:
                    continue;

                }


                //Value has changed, log link:
                $this->READ_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_type_source_id' => $ln_type_source_id,
                    'ln_next_note_id' => $id,
                    'ln_child_source_id' => $ln_child_source_id,
                    'ln_parent_source_id' => $ln_parent_source_id,
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
            $this->READ_model->ln_create(array(
                'ln_next_note_id' => $id,
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

        //Remove note relations:
        $links_removed = 0;
        foreach($this->READ_model->ln_fetch(array( //Note Links
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Note-to-Note Links
            '(ln_next_note_id = '.$in_id.' OR ln_previous_note_id = '.$in_id.')' => null,
        ), array(), 0) as $ln){
            //Remove this link:
            $links_removed += $this->READ_model->ln_update($ln['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Removed
            ), $ln_creator_source_id, 10686 /* Note Link Unlinked */);
        }

        //Return links removed:
        return $links_removed;
    }

    function in_sync_creation($ln_creator_source_id, $query= array()){

        //STATS
        $stats = array(
            'ln_type_source_id' => 4250, //Note Created
            'scanned' => 0,
            'missing_creation_fix' => 0,
            'status_sync' => 0,
        );

        //NOTES
        $status_converter = array(
            12137 => 12399, //NOTE FEATURE => READ FEATURE
            6184 => 6176, //NOTE PUBLISH => READ PUBLISH
            6183 => 6175, //NOTE DRAFT => READ DRAFT
            6182 => 6173, //NOTE ARCHIVE => READ ARCHIVE
        );
        foreach($this->NOTE_model->in_fetch($query) as $in){

            $stats['scanned']++;

            //Find creation read:
            $reads = $this->READ_model->ln_fetch(array(
                'ln_type_source_id' => $stats['ln_type_source_id'],
                'ln_next_note_id' => $in['in_id'],
            ));

            if(!count($reads)){

                $stats['missing_creation_fix']++;

                $this->READ_model->ln_create(array(
                    'ln_creator_source_id' => $ln_creator_source_id,
                    'ln_next_note_id' => $in['in_id'],
                    'ln_content' => $in['in_title'],
                    'ln_type_source_id' => $stats['ln_type_source_id'],
                    'ln_status_source_id' => $status_converter[$in['in_status_source_id']],
                ));

            } elseif($reads[0]['ln_status_source_id'] != $status_converter[$in['in_status_source_id']]){

                $stats['status_sync']++;
                $this->READ_model->ln_update($reads[0]['ln_id'], array(
                    'ln_status_source_id' => $status_converter[$in['in_status_source_id']],
                ));

            }

        }

        return $stats;
    }

    function in_link_or_create($in_title, $ln_creator_source_id, $link_to_in_id = 0, $is_parent = false, $new_in_status = 6184, $in_type_source_id = 6677 /* Note Read-Only */, $link_in_id = 0)
    {

        /*
         *
         * The main note creation function that would create
         * appropriate links and return the note view.
         *
         * Either creates an NOTE link between $link_to_in_id & $link_in_id
         * (IF $link_in_id>0) OR will create a new note with outcome $in_title
         * and link it to $link_to_in_id (In this case $link_in_id will be 0)
         *
         * p.s. Inputs have already been validated via notes/in_link_or_create() function
         *
         * */

        //Validate Original note:
        if($link_to_in_id > 0){
            $linked_ins = $this->NOTE_model->in_fetch(array(
                'in_id' => intval($link_to_in_id),
            ));

            if (count($linked_ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Note ID',
                );
            } elseif (!in_array($linked_ins[0]['in_status_source_id'], $this->config->item('en_ids_7356')) /* Note Status Active */) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active notes. This note is not active.',
                );
            }
        }


        if ($link_in_id > 0) {

            //We are linking to $link_in_id, We are NOT creating any new notes...

            //Fetch more details on the child note we're about to link:
            $ins = $this->NOTE_model->in_fetch(array(
                'in_id' => $link_in_id,
            ));

            //Determine which is parent Note, and which is child
            if($is_parent){

                $parent_in = $ins[0];
                $child_in = $linked_ins[0];

                /*
                //Prevent child duplicates:
                $recursive_children = $this->NOTE_model->in_recursive_child_ids($child_in['in_id'], false);
                if (in_array($parent_in['in_id'], $recursive_children)) {
                    return array(
                        'status' => 0,
                        'message' => 'Note already set as child, so it cannot be added as parent',
                    );
                }
                */

            } else {

                $parent_in = $linked_ins[0];
                $child_in = $ins[0];

                //Prevent parent duplicate:
                $recursive_parents = $this->NOTE_model->in_fetch_recursive_parents($parent_in['in_id']);
                foreach ($recursive_parents as $grand_parent_ids) {
                    if (in_array($child_in['in_id'], $grand_parent_ids)) {
                        return array(
                            'status' => 0,
                            'message' => 'Note already set as parent, so it cannot be added as child',
                        );
                    }
                }
            }


            if (count($ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Note ID',
                );
            } elseif (!in_array($ins[0]['in_status_source_id'], $this->config->item('en_ids_7356') /* Note Status Active */)) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active notes. This note is not active.',
                );
            }

            //All good so far, continue with linking:
            $in_new = $ins[0];

            //Make sure this is not a duplicate Note for its parent:
            $dup_links = $this->READ_model->ln_fetch(array(
                'ln_previous_note_id' => $parent_in['in_id'],
                'ln_next_note_id' => $child_in['in_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Note-to-Note Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            ));

            //Check for issues:
            if (count($dup_links) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $in_new['in_title'] . '] is already linked here.',
                );

            } elseif ($link_to_in_id > 0 && $link_in_id == $link_to_in_id) {

                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $in_new['in_title'] . '" as its own '.( $is_parent ? 'parent' : 'child' ).'.',
                );

            }

        } else {

            //We are NOT linking to an existing Note, but instead, we're creating a new Note

            //Validate Note Outcome:
            $in_titlevalidation = $this->NOTE_model->in_titlevalidate($in_title);
            if(!$in_titlevalidation['status']){
                //We had an error, return it:
                return $in_titlevalidation;
            }


            //Create new Note:
            $in_new = $this->NOTE_model->in_create(array(
                'in_title' => $in_titlevalidation['in_cleaned_outcome'],
                'in_type_source_id' => $in_type_source_id,
                'in_status_source_id' => $new_in_status,
            ), true, $ln_creator_source_id);

        }


        //Create Note Link:
        if($link_to_in_id > 0){

            $relation = $this->READ_model->ln_create(array(
                'ln_creator_source_id' => $ln_creator_source_id,
                'ln_type_source_id' => 4228, //Note Link Regular Read
                ( $is_parent ? 'ln_next_note_id' : 'ln_previous_note_id' ) => $link_to_in_id,
                ( $is_parent ? 'ln_previous_note_id' : 'ln_next_note_id' ) => $in_new['in_id'],
                'ln_order' => 1 + $this->READ_model->ln_max_order(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Note-to-Note Links
                        'ln_previous_note_id' => ( $is_parent ? $in_new['in_id'] : $link_to_in_id ),
                    )),
            ), true);

            //Fetch and return full data to be properly shown on the UI using the echo_in() function
            $new_ins = $this->READ_model->ln_fetch(array(
                ( $is_parent ? 'ln_next_note_id' : 'ln_previous_note_id' ) => $link_to_in_id,
                ( $is_parent ? 'ln_previous_note_id' : 'ln_next_note_id' ) => $in_new['in_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Note-to-Note Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Note Status Active
            ), array(($is_parent ? 'in_parent' : 'in_child')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


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


    function in_fetch_recursive_parents($in_id, $first_level = true, $public_only = true){

        $grand_parents = array();

        //Fetch parents:
        foreach($this->READ_model->ln_fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item(($public_only ? 'en_ids_7355' : 'en_ids_7356' ))) . ')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item(($public_only ? 'en_ids_7359' : 'en_ids_7360' ))) . ')' => null,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Note-to-Note Links
            'ln_next_note_id' => $in_id,
        ), array('in_parent')) as $in_parent){

            //Prep ID:
            $p_id = intval($in_parent['in_id']);

            //Add to appropriate array:
            if(!$first_level){
                array_push($grand_parents, $p_id);
            }


            //Fetch parents of parents:
            $recursive_parents = $this->NOTE_model->in_fetch_recursive_parents($p_id, false);
            if(count($recursive_parents) > 0){
                if($first_level){
                    array_push($grand_parents, array_merge(array($p_id), $recursive_parents));
                } else {
                    $grand_parents = array_merge($grand_parents, $recursive_parents);
                }
            } elseif($first_level){
                array_push($grand_parents, array($p_id));
            }
        }

        return $grand_parents;
    }



    function in_recursive_child_ids($in_id, $first_level = true){

        $child_ids = array();

        //Fetch parents:
        foreach($this->READ_model->ln_fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Note Status Active
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Note-to-Note Links
            'ln_previous_note_id' => $in_id,
        ), array('in_child')) as $child_in){

            array_push($child_ids, intval($child_in['in_id']));

            //Fetch parents of parents:
            $recursive_children = $this->NOTE_model->in_recursive_child_ids($child_in['in_id'], false);

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
        $is_first_in = ( !isset($focus_in['ln_id']) ); //First note does not have a link, just the note
        $has_or_parent = in_array($focus_in['in_type_source_id'] , $this->config->item('en_ids_6193') /* OR Notes */ );
        $or_children = array(); //To be populated only if $focus_in is an OR note
        $conditional_steps = array(); //To be populated only for Conditional Reads
        $metadata_this = array(
            '__in__metadata_common_steps' => array(), //The tree structure that would be shared with all users regardless of their quick replies (OR Note Answers)
            '__in__metadata_expansion_steps' => array(), //Notes that may exist as a link to expand an 🔴 READING LIST tree by answering OR notes
            '__in__metadata_expansion_conditional' => array(), //Notes that may exist as a link to expand an 🔴 READING LIST tree via Conditional Read links
        );

        //Fetch children:
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Note Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Note-to-Note Links
            'ln_previous_note_id' => $focus_in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $child_in){

            //Determine action based on parent note type:
            if($child_in['ln_type_source_id']==4229){

                //Conditional Read Link:
                array_push($conditional_steps, intval($child_in['in_id']));

            } elseif($has_or_parent){

                //OR parent Note with Fixed Read Link:
                array_push($or_children, intval($child_in['in_id']));

            } else {

                //AND parent Note with Fixed Read Link:
                array_push($metadata_this['__in__metadata_common_steps'], intval($child_in['in_id']));

                //Go recursively down:
                $child_recursion = $this->NOTE_model->in_metadata_common_base($child_in);


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
        if($has_or_parent && count($or_children) > 0){
            $metadata_this['__in__metadata_expansion_steps'][$focus_in['in_id']] = $or_children;
        }

        if(count($conditional_steps) > 0){
            $metadata_this['__in__metadata_expansion_conditional'][$focus_in['in_id']] = $conditional_steps;
        }


        //Save common base:
        if($is_first_in){

            //Make sure to add main note to common tree:
            if(count($metadata_this['__in__metadata_common_steps']) > 0){
                $metadata_this['__in__metadata_common_steps'] = array_merge( array(intval($focus_in['in_id'])) , array($metadata_this['__in__metadata_common_steps']));
            } else {
                $metadata_this['__in__metadata_common_steps'] = array(intval($focus_in['in_id']));
            }

            update_metadata('in', $focus_in['in_id'], array(
                'in__metadata_common_steps' => $metadata_this['__in__metadata_common_steps'],
                'in__metadata_expansion_steps' => $metadata_this['__in__metadata_expansion_steps'],
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

        $in__children = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Note Status Active
            'ln_type_source_id' => 4228, //Note Link Regular Read
            'ln_previous_note_id' => $in_id,
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));


        //Process request:
        foreach ($in__children as $in) {

            //Logic here must match items in en_mass_actions config variable

            if(in_array($action_en_id , array(12591, 12592))){

                //Check if it hs this item:
                $parent_en_id = intval(one_two_explode('@',' ',$action_command1));
                $in_has_sources = $this->READ_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                    'ln_type_source_id' => 4983,
                    'ln_next_note_id' => $in['in_id'],
                    'ln_parent_source_id' => $parent_en_id,
                ));

                if($action_en_id==12591 && !count($in_has_sources)){

                    //Missing & Must be Added:
                    $this->READ_model->ln_create(array(
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_parent_source_id' => $parent_en_id,
                        'ln_type_source_id' => 4983,
                        'ln_content' => '@'.$parent_en_id,
                        'ln_next_note_id' => $in['in_id'],
                    ), true);

                    $applied_success++;

                } elseif($action_en_id==12592 && count($in_has_sources)){

                    //Has and must be removed:
                    $this->READ_model->ln_update($in_has_sources[0]['ln_id'], array(
                        'ln_status_source_id' => 6173,
                    ), $ln_creator_source_id, 10678 /* Note Pads Unlinked */);

                    $applied_success++;

                }

            } elseif(in_array($action_en_id , array(12611, 12612))){

                //Check if it hs this item:
                $parent_in_id = intval(one_two_explode('#',' ',$action_command1));
                $in_has_parents = $this->READ_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
                    'ln_type_source_id' => 4983,
                    'ln_next_note_id' => $in['in_id'],
                    'ln_parent_source_id' => $parent_en_id,
                ));

                if($action_en_id==12591 && !count($in_has_sources)){

                    //Missing & Must be Added:
                    $this->READ_model->ln_create(array(
                        'ln_creator_source_id' => $ln_creator_source_id,
                        'ln_parent_source_id' => $parent_en_id,
                        'ln_type_source_id' => 4983,
                        'ln_content' => '@'.$parent_en_id,
                        'ln_next_note_id' => $in['in_id'],
                    ), true);

                    $applied_success++;

                } elseif($action_en_id==12592 && count($in_has_sources)){

                    //Has and must be removed:
                    $this->READ_model->ln_update($in_has_sources[0]['ln_id'], array(
                        'ln_status_source_id' => 6173,
                    ), $ln_creator_source_id, 10678 /* Note Pads Unlinked */);

                    $applied_success++;

                }

            }

        }


        //Log mass source edit link:
        $this->READ_model->ln_create(array(
            'ln_creator_source_id' => $ln_creator_source_id,
            'ln_type_source_id' => $action_en_id,
            'ln_next_note_id' => $in_id,
            'ln_metadata' => array(
                'payload' => $_POST,
                'notes_total' => count($in__children),
                'notes_updated' => $applied_success,
                'command1' => $action_command1,
                'command2' => $action_command2,
            ),
        ));

        //Return results:
        return array(
            'status' => 1,
            'message' => '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-check-circle"></i></span>'.$applied_success . '/' . count($in__children) . ' notes updated</div>',
        );

    }


    function in_tree_weight($in_id)
    {

        /*
         *
         * Addup weights recursively
         *
         * */


        $total_child_weights = 0;

        foreach($this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Note Status Public
            'ln_type_source_id' => 4228, //Fixed Note Link
            'ln_previous_note_id' => $in_id,
        ), array('in_child'), 0, 0, array(), 'in_id, in_weight') as $in_child){
            $total_child_weights += $in_child['in_weight'] + $this->NOTE_model->in_tree_weight($in_child['in_id']);
        }

        //Update This Level:
        if($total_child_weights > 0){
            $this->db->query("UPDATE mench_notes SET in_weight=in_weight+".$total_child_weights." WHERE in_id=".$in_id.";");
        }

        //Return data:
        return $total_child_weights;

    }

    function in_metadata_extra_insights($in_id, $update_db = true)
    {

        /*
         *
         * Generates additional insights like
         * min/max steps, time, cost and
         * referenced sources in Note Pads.
         *
         * */

        //Fetch this note:
        $ins = $this->NOTE_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Note Status Public
        ));
        if(count($ins) < 1){
            return false;
        }

        $in_metadata = unserialize( $ins[0]['in_metadata'] );
        if(!isset($in_metadata['in__metadata_common_steps'])){
            return false;
        }

        //Fetch common base and expansion paths from note metadata:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);
        $expansion_steps = ( isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0 ? $in_metadata['in__metadata_expansion_steps'] : array() );
        $locked_steps = ( isset($in_metadata['in__metadata_expansion_conditional']) && count($in_metadata['in__metadata_expansion_conditional']) > 0 ? $in_metadata['in__metadata_expansion_conditional'] : array() );

        //Fetch totals for published common step notes:
        $common_totals = $this->NOTE_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Note Status Public
        ), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_read_time) as total_seconds');

        $common_base_resources = array(
            'steps' => $common_totals[0]['total_steps'],
            'seconds' => $common_totals[0]['total_seconds'],
        );

        $metadata_this = array(

            //Required steps/notes range to complete tree:
            '__in__metadata_min_steps' => $common_base_resources['steps'],
            '__in__metadata_max_steps' => $common_base_resources['steps'],

            //Required time range to complete tree:
            '__in__metadata_min_seconds' => $common_base_resources['seconds'],
            '__in__metadata_max_seconds' => $common_base_resources['seconds'],

            //Player references within Note Pads:
            '__in__metadata_experts' => array(),
            '__in__metadata_sources' => array(),

        );



        //Add-up Note Pads References:
        //The sources we need to check and see if they are industry experts:
        foreach ($this->READ_model->ln_fetch(array(
            'ln_parent_source_id >' => 0,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4485')).')' => null, //Note Pads
            '(ln_next_note_id = ' . $in_id . ( count($flat_common_steps) > 0 ? ' OR ln_next_note_id IN ('.join(',',$flat_common_steps).')' : '' ).')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
        ), array('en_parent'), 0) as $pads_en) {

            //Referenced source in Note Pads... Fetch parents:
            foreach($this->READ_model->ln_fetch(array(
                'ln_child_source_id' => $pads_en['ln_parent_source_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            ), array(), 0) as $parent_en){

                if(in_array($parent_en['ln_parent_source_id'], $this->config->item('en_ids_3000'))){

                    //Expert Source:
                    if (!isset($metadata_this['__in__metadata_sources'][$parent_en['ln_parent_source_id']][$pads_en['en_id']])) {
                        //Add since it's not there:
                        $metadata_this['__in__metadata_sources'][$parent_en['ln_parent_source_id']][$pads_en['en_id']] = $pads_en;
                    }

                } elseif($parent_en['ln_parent_source_id']==3084) {

                    //Industry Expert:
                    if (!isset($metadata_this['__in__metadata_experts'][$pads_en['en_id']])) {
                        $metadata_this['__in__metadata_experts'][$pads_en['en_id']] = $pads_en;
                    }

                } else {

                    //Industry Expert?
                    $expert_parents = $this->READ_model->ln_fetch(array(
                        'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Source Links
                        'ln_parent_source_id' => 3084, //Industry Experts
                        'ln_child_source_id' => $parent_en['ln_parent_source_id'],
                    ), array('en_child'), 0);

                    if(count($expert_parents) > 0){

                        //Yes, Industry Expert:
                        if (!isset($metadata_this['__in__metadata_experts'][$parent_en['ln_parent_source_id']])) {
                            $metadata_this['__in__metadata_experts'][$parent_en['ln_parent_source_id']] = $expert_parents[0];
                        }

                    } else {

                        //TODO Maybe this is an expert source that is a child of another expert source? Go another level-up and check parents...
                        //We might want to discourage this via mining principles... Need to think more on this.

                    }
                }
            }
        }


        //Go through expansion paths, if any:
        foreach(array_merge($expansion_steps, $locked_steps) as $expansion_group){

            //Determine OR Answer local min/max:
            $metadata_local = array(
                'local__in__metadata_min_steps'=> null,
                'local__in__metadata_max_steps'=> null,
                'local__in__metadata_min_seconds'=> null,
                'local__in__metadata_max_seconds'=> null,
            );

            foreach($expansion_group as $expansion_in_id){

                $metadata_recursion = $this->NOTE_model->in_metadata_extra_insights($expansion_in_id, false);

                if(!$metadata_recursion){
                    continue;
                }

                //MIN/MAX updates:
                if(is_null($metadata_local['local__in__metadata_min_steps']) || $metadata_recursion['__in__metadata_min_steps'] < $metadata_local['local__in__metadata_min_steps']){
                    $metadata_local['local__in__metadata_min_steps'] = $metadata_recursion['__in__metadata_min_steps'];
                }
                if(is_null($metadata_local['local__in__metadata_max_steps']) || $metadata_recursion['__in__metadata_max_steps'] > $metadata_local['local__in__metadata_max_steps']){
                    $metadata_local['local__in__metadata_max_steps'] = $metadata_recursion['__in__metadata_max_steps'];
                }
                if(is_null($metadata_local['local__in__metadata_min_seconds']) || $metadata_recursion['__in__metadata_min_seconds'] < $metadata_local['local__in__metadata_min_seconds']){
                    $metadata_local['local__in__metadata_min_seconds'] = $metadata_recursion['__in__metadata_min_seconds'];
                }
                if(is_null($metadata_local['local__in__metadata_max_seconds']) || $metadata_recursion['__in__metadata_max_seconds'] > $metadata_local['local__in__metadata_max_seconds']){
                    $metadata_local['local__in__metadata_max_seconds'] = $metadata_recursion['__in__metadata_max_seconds'];
                }


                //Addup Experts:
                foreach ($metadata_recursion['__in__metadata_experts'] as $en_id => $expert_en) {
                    //Is this a new expert?
                    if (!isset($metadata_this['__in__metadata_experts'][$en_id])) {
                        //Yes, add them to the list:
                        $metadata_this['__in__metadata_experts'][$en_id] = $expert_en;
                    }
                }

                //Addup Sources:
                foreach ($metadata_recursion['__in__metadata_sources'] as $type_en_id => $source_ens) {
                    foreach ($source_ens as $en_id => $source_en) {
                        if (!isset($metadata_this['__in__metadata_sources'][$type_en_id][$en_id])) {
                            $metadata_this['__in__metadata_sources'][$type_en_id][$en_id] = $source_en;
                        }
                    }
                }
            }

            //Add to totals if set:
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

        }


        if($update_db){

            /*
             *
             * Save to database
             *
             * */
            update_metadata('in', $in_id, array(
                'in__metadata_min_steps' => intval($metadata_this['__in__metadata_min_steps']),
                'in__metadata_max_steps' => intval($metadata_this['__in__metadata_max_steps']),
                'in__metadata_min_seconds' => intval($metadata_this['__in__metadata_min_seconds']),
                'in__metadata_max_seconds' => intval($metadata_this['__in__metadata_max_seconds']),
                'in__metadata_experts' => $metadata_this['__in__metadata_experts'],
                'in__metadata_sources' => $metadata_this['__in__metadata_sources'],
            ));

        }


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


        //Validate this locked note:
        if(!in_is_unlockable($in)){
            return array();
        }

        $child_unlock_paths = array();


        //Read 1: Is there an OR parent that we can simply answer and unlock?
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Note Status Public
            'ln_type_source_id' => 4228, //Note Link Regular Read
            'ln_next_note_id' => $in['in_id'],
            'in_type_source_id IN (' . join(',', $this->config->item('en_ids_7712')) . ')' => null,
        ), array('in_parent'), 0) as $in_or_parent){
            if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $in_or_parent['in_id'])) {
                array_push($child_unlock_paths, $in_or_parent);
            }
        }


        //Read 2: Are there any locked link parents that the user might be able to unlock?
        foreach($this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Note Status Public
            'ln_type_source_id' => 4229, //Note Link Locked Read
            'ln_next_note_id' => $in['in_id'],
        ), array('in_parent'), 0) as $in_locked_parent){
            if(in_is_unlockable($in_locked_parent)){
                //Need to check recursively:
                foreach($this->NOTE_model->in_unlock_paths($in_locked_parent) as $locked_path){
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
            //Return OR parents for unlocking this note:
            return $child_unlock_paths;
        }


        //Read 3: We don't have any OR parents, let's see how we can complete all children to meet the requirements:
        $in__children = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Note Status Public
            'ln_type_source_id' => 4228, //Note Link Regular Read
            'ln_previous_note_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        if(count($in__children) < 1){
            //No children, no path:
            return array();
        }

        //Go through children to see if any/all can be completed:
        foreach($in__children as $child_in){
            if(in_is_unlockable($child_in)){

                //Need to check recursively:
                foreach($this->NOTE_model->in_unlock_paths($child_in) as $locked_path){
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

    function in_titlevalidate($in_title){

        //Validate:
        if(!strlen(trim($in_title))){

            return array(
                'status' => 0,
                'message' => 'Title missing',
            );

        } elseif(substr_count($in_title , '  ') > 0){

            return array(
                'status' => 0,
                'message' => 'Title cannot include double spaces',
            );

        } elseif (strlen($in_title) > config_var(11071)) {

            return array(
                'status' => 0,
                'message' => 'Title must be '.config_var(11071).' characters or less',
            );

        }

        //All good, return success:
        return array(
            'status' => 1,
            'in_cleaned_outcome' => trim($in_title),
        );

    }


}