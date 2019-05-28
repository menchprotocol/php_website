<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Intents_model extends CI_Model
{

    /*
     *
     * Intent related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function in_create($insert_columns, $external_sync = false, $ln_miner_entity_id = 0)
    {

        //What is required to create a new intent?
        if (detect_missing_columns($insert_columns, array('in_outcome', 'in_verb_entity_id'))) {
            return false;
        }

        if (isset($insert_columns['in_metadata']) && count($insert_columns['in_metadata']) > 0) {
            $insert_columns['in_metadata'] = serialize($insert_columns['in_metadata']);
        } else {
            $insert_columns['in_metadata'] = null;
        }

        if (!isset($insert_columns['in_type_entity_id'])) {
            $insert_columns['in_type_entity_id'] = 6677; //AND No Response Required
        }

        if (!isset($insert_columns['in_status'])) {
            $insert_columns['in_status'] = 0; //New Intent
        }

        //Lets now add:
        $this->db->insert('table_intents', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['in_id'])) {
            $insert_columns['in_id'] = $this->db->insert_id();
        }

        if ($insert_columns['in_id'] > 0) {

            if ($external_sync) {

                //Update Algolia:
                $algolia_sync = update_algolia('in', $insert_columns['in_id']);

                //Log link new entity:
                $this->Links_model->ln_create(array(
                    'ln_miner_entity_id' => $ln_miner_entity_id,
                    'ln_child_intent_id' => $insert_columns['in_id'],
                    'ln_type_entity_id' => 4250, //New Intent Created
                ));

                //Fetch to return the complete entity data:
                $ins = $this->Intents_model->in_fetch(array(
                    'in_id' => $insert_columns['in_id'],
                ));

                return $ins[0];

            } else {

                //Return provided inputs plus the new entity ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            $this->Links_model->ln_create(array(
                'ln_parent_entity_id' => $ln_miner_entity_id,
                'ln_content' => 'in_create() failed to create a new intent',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function in_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for intents
        $this->db->select($select);
        $this->db->from('table_intents');

        if (in_array('in_verb_entity_id', $join_objects)) {
            $this->db->join('table_entities', 'in_verb_entity_id=en_id', 'left');
        }

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
        $ins = $q->result_array();

        foreach ($ins as $key => $value) {

            //Should we append Intent Notes?
            if (in_array('in__messages', $join_objects)) {
                $ins[$key]['in__messages'] = $this->Links_model->ln_fetch(array(
                    'ln_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                    'ln_child_intent_id' => $value['in_id'],
                ), array(), 0, 0, array('ln_order' => 'ASC'));
            }

            //Should we fetch all parent intentions?
            if (in_array('in__parents', $join_objects)) {

                $ins[$key]['in__parents'] = $this->Links_model->ln_fetch(array(
                    'ln_status >=' => 0, //New+
                    'in_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_child_intent_id' => $value['in_id'],
                ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

            }

            //Have we been asked to append any children/granchildren to this query?
            if (in_array('in__children', $join_objects) || in_array('in__grandchildren', $join_objects)) {

                //Fetch immediate children:
                $ins[$key]['in__children'] = $this->Links_model->ln_fetch(array(
                    'ln_status >=' => 0, //New+
                    'in_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_parent_intent_id' => $value['in_id'],
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered


                if (in_array('in__grandchildren', $join_objects) && count($ins[$key]['in__children']) > 0) {
                    //Fetch second-level grandchildren intents:
                    foreach ($ins[$key]['in__children'] as $key2 => $value2) {

                        $ins[$key]['in__children'][$key2]['in__grandchildren'] = $this->Links_model->ln_fetch(array(
                            'ln_status >=' => 0, //New+
                            'in_status >=' => 0, //New+
                            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                            'ln_parent_intent_id' => $value2['in_id'],
                        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered

                    }
                }
            }
        }

        //Return everything that was collected:
        return $ins;
    }

    function in_update($id, $update_columns, $external_sync = false, $ln_miner_entity_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current intent filed values so we can compare later on after we've updated it:
        if($external_sync){
            $before_data = $this->Intents_model->in_fetch(array('in_id' => $id));
        }

        //Cleanup metadata if needed:
        if(isset($update_columns['in_metadata']) && is_array($update_columns['in_metadata'])) {
            $update_columns['in_metadata'] = serialize($update_columns['in_metadata']);
        }

        //Update:
        $this->db->where('in_id', $id);
        $this->db->update('table_intents', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows > 0 && $external_sync) {

            $fixed_fields = $this->config->item('fixed_fields');

            //Note that unlike entity modification, we require a miner entity ID to log the modification link:
            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('in_metadata'))) {

                    //Value has changed, log link:
                    $this->Links_model->ln_create(array(
                        'ln_miner_entity_id' => $ln_miner_entity_id,
                        'ln_type_entity_id' => 4264, //Intent Attribute Modified
                        'ln_child_intent_id' => $id,
                        'ln_content' => echo_clean_db_name($key) . ' changed from "' . ( in_array($key , array('in_status')) ? $fixed_fields[$key][$before_data[0][$key]]['s_name']  : $before_data[0][$key] ) . '" to "' . ( in_array($key , array('in_status')) ? $fixed_fields[$key][$value]['s_name'] : $value ) . '"',
                        'ln_metadata' => array(
                            'in_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));

                }

            }

            //Sync algolia:
            update_algolia('in', $id);

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->Links_model->ln_create(array(
                'ln_child_intent_id' => $id,
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'in_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function in_unlink($in_id, $ln_miner_entity_id = 0){

        //Remove intent relations:
        $intent_remove_links = array_merge(
            $this->Links_model->ln_fetch(array( //Intent Links
                'ln_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                '(ln_child_intent_id = '.$in_id.' OR ln_parent_intent_id = '.$in_id.')' => null,
            ), array(), 0),
            $this->Links_model->ln_fetch(array( //Intent Notes
                'ln_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                '(ln_child_intent_id = '.$in_id.' OR ln_parent_intent_id = '.$in_id.')' => null,
            ), array(), 0)
        );

        $links_removed = 0;
        foreach($intent_remove_links as $ln){
            //Remove this link:
            $links_removed += $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_status' => -1, //Removed
            ), $ln_miner_entity_id);
        }

        //Return links removed:
        return $links_removed;
    }

    function in_link_or_create($actionplan_in_id, $is_parent, $in_outcome, $link_in_id, $next_level, $ln_miner_entity_id)
    {

        /*
         *
         * The main intent creation function that would create
         * appropriate links and return the intent view.
         *
         * Either creates an intent link between $actionplan_in_id & $link_in_id
         * (IF $link_in_id>0) OR will create a new intent with outcome $in_outcome
         * and link it to $actionplan_in_id (In this case $link_in_id will be 0)
         *
         * p.s. Inputs have already been validated via intents/in_link_or_create() function
         *
         * */

        //Validate Original intent:
        $parent_ins = $this->Intents_model->in_fetch(array(
            'in_id' => intval($actionplan_in_id),
        ));

        if (count($parent_ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        } elseif (!in_array($next_level, array(2,3))) {
            return array(
                'status' => 0,
                'message' => 'Intent level must be either 2 or 3.',
            );
        } elseif ($parent_ins[0]['in_status'] < 0) {
            return array(
                'status' => 0,
                'message' => 'Cannot link to removed intents',
            );
        }

        if (intval($link_in_id) > 0) {

            //We are linking to $link_in_id, We are NOT creating any new intents...

            //Fetch more details on the child intent we're about to link:
            $ins = $this->Intents_model->in_fetch(array(
                'in_id' => $link_in_id,
            ));

            if (count($ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Intent ID',
                );
            } elseif ($ins[0]['in_status'] < 0) {
                return array(
                    'status' => 0,
                    'message' => 'Cannot link to removed intents',
                );
            }

            //All good so far, continue with linking:
            $intent_new = $ins[0];

            //check all parents as this intent cannot be duplicated with any of its parents as it created an infinity loop:
            if (in_array($intent_new['in_id'], array_flatten($this->Intents_model->in_fetch_recursive_parents($actionplan_in_id, 0)))) {
                return array(
                    'status' => 0,
                    'message' => 'You cannot link to "' . $intent_new['in_outcome'] . '" as it already belongs to the parent/grandparent tree.',
                );
            }

            //Make sure this is not a duplicate intent for its parent:
            $dup_links = $this->Links_model->ln_fetch(array(
                ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
                ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $link_in_id,
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                'ln_status >=' => 0, //New+
            ));

            //Check for issues:
            if (count($dup_links) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $intent_new['in_outcome'] . '] is already linked here.',
                );

            } elseif ($link_in_id == $actionplan_in_id) {

                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $intent_new['in_outcome'] . '" as its own '.( $is_parent ? 'parent' : 'child' ).'.',
                );

            }

        } else {

            //We are NOT linking to an existing intent, but instead, we're creating a new intent:

            //See if we have the double column shortcut:
            if(substr($in_outcome, 0, 2) == '::'){

                //Yes, validate this command:
                if($is_parent){
                    return array(
                        'status' => 0,
                        'message' => 'You can use the double column shortcut for child entities only.',
                    );
                }

                //Apply shortcut and update the intent outcome:
                $parent_in_outcome_words = explode(' ', $parent_ins[0]['in_outcome']);
                $in_outcome = $parent_in_outcome_words[0].' #'.$parent_ins[0]['in_id'].' :: '.trim(substr($in_outcome, 2));

            }

            //Validate Intent Outcome:
            $in_outcome_validation = $this->Intents_model->in_validate_outcome($in_outcome, $ln_miner_entity_id);
            if(!$in_outcome_validation['status']){
                //We had an error, return it:
                return $in_outcome_validation;
            }

            //All good, let's create the intent:
            $intent_new = $this->Intents_model->in_create(array(
                'in_outcome' => $in_outcome_validation['in_cleaned_outcome'],
                'in_verb_entity_id' => $in_outcome_validation['detected_verb_entity_id'],
            ), true, $ln_miner_entity_id);

        }


        //Create Intent Link:
        $relation = $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $ln_miner_entity_id,
            'ln_type_entity_id' => 4228,
            ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
            ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
            'ln_order' => 1 + $this->Links_model->ln_max_order(array(
                    'ln_status >=' => 0, //New+
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_parent_intent_id' => ( $is_parent ? $intent_new['in_id'] : $actionplan_in_id ),
                )),
        ), true);



        //Add Up-Vote if not yet added for this miner:
        if($ln_miner_entity_id > 0){

            $ln_miner_upvotes = $this->Links_model->ln_fetch(array(
                ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
                ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
                'ln_parent_entity_id' => $ln_miner_entity_id,
                'ln_type_entity_id' => 4983, //Up-votes
                'ln_status >=' => 0, //New+
            ));

            if(count($ln_miner_upvotes) == 0){
                //Add new up-vote
                //No need to sync external sources via ln_create()
                $up_vote = $this->Links_model->ln_create(array(
                    'ln_miner_entity_id' => $ln_miner_entity_id,
                    'ln_parent_entity_id' => $ln_miner_entity_id,
                    'ln_type_entity_id' => 4983, //Up-votes
                    'ln_content' => '@'.$ln_miner_entity_id.' #'.( $is_parent ? $intent_new['in_id'] : $actionplan_in_id ), //Message content
                    ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
                    ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
                ));
            }

        }


        //Fetch and return full data to be properly shown on the UI using the echo_in() function
        $new_ins = $this->Links_model->ln_fetch(array(
            ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $actionplan_in_id,
            ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_status >=' => 0,
            'in_status >=' => 0,
        ), array(($is_parent ? 'in_parent' : 'in_child')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


        //Return result:
        return array(
            'status' => 1,
            'in_child_id' => $intent_new['in_id'],
            'in_child_html' => echo_in($new_ins[0], $next_level, $actionplan_in_id, $is_parent),
        );

    }

    function in_manual_response_note($in, $fb_messenger_format = false)
    {

        /*
         *
         * Sometimes to mark an intent as complete the Users might
         * need to meet certain requirements in what they submit to do so.
         * This function fetches those requirements from the Platform and
         * Provides an easy to understand message to communicate
         * these requirements to User.
         *
         * Will return NULL if it detects no requirements...
         *
         * */

        if (!in_array($in['in_type_entity_id'], $this->config->item('en_ids_6794'))) {
            //Does not have any requirements:
            return null;
        }

        //Construct the message accordingly...

        //Fetch latest cache tree:
        $en_all_6794 = $this->config->item('en_all_6794'); //Intent Requires Manual Response

        //Return User-friendly message for Requires Manual Response:
        return $en_all_6794[$in['in_type_entity_id']]['m_name'] .' to complete this step.'.( !$fb_messenger_format ? ' Send it to me via Messenger.' : '' );

    }


    function in_fetch_recursive_parents($in_id, $min_level, $first_level = true){

        $grand_parents = array();

        //Fetch parents:
        foreach($this->Links_model->ln_fetch(array(
            'in_status >=' => $min_level,
            'ln_status >=' => $min_level,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_child_intent_id' => $in_id,
        ), array('in_parent')) as $in_parent){

            //Prep ID:
            $p_id = intval($in_parent['in_id']);

            //Add to appropriate array:
            if(!$first_level){
                array_push($grand_parents, $p_id);
            }

            //Fetch parents of parents:
            $recursive_parents = $this->Intents_model->in_fetch_recursive_parents($p_id, $min_level, false);

            if($first_level){
                array_push($recursive_parents, $p_id);
                $grand_parents[$p_id] = $recursive_parents;
            } elseif(!$first_level && count($recursive_parents) > 0){
                $grand_parents = array_merge($grand_parents, $recursive_parents);
            }
        }

        return $grand_parents;
    }


    function in_check_unlockable(){

    }


    function in_recursive_child_ids($in_id, $first_level = true){

        $child_ids = array();

        //Fetch parents:
        foreach($this->Links_model->ln_fetch(array(
            'in_status >=' => 0,
            'ln_status >=' => 0,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_parent_intent_id' => $in_id,
        ), array('in_child')) as $in_child){

            array_push($child_ids, intval($in_child['in_id']));

            //Fetch parents of parents:
            $recursive_children = $this->Intents_model->in_recursive_child_ids($in_child['in_id'], false);

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



    function in_is_public($in){

        //Status is good?
        if ( $in['in_status'] < 2) {

            //Return error:
            return array(
                'status' => 0,
                'message' => 'Intent #' . $in['in_id'] . ' is not published yet', //Don't show the intent name yet as its not published
            );

        } elseif ( !in_array($in['in_type_entity_id'], $this->config->item('en_ids_6908'))) {

            //Fetch all possible intent types:
            $in_type_group = ( in_array($in['in_type_entity_id'], $this->config->item('en_ids_6192')) ? $this->config->item('en_all_6192') : $this->config->item('en_all_6193') );

            //Return error:
            return array(
                'status' => 0,
                'message' => 'Intent type ['.$in_type_group[$in['in_type_entity_id']]['m_name'].'] is not a starting step @6908',
            );

        }

        //All good:
        return array(
            'status' => 1,
        );

    }

    function in_metadata_common_base($focus_in){

        //Set variables:
        $is_first_intent = ( !isset($focus_in['ln_id']) ); //First intent does not have a link, just the intent
        $has_or_parent = is_or($focus_in['in_type_entity_id']);
        $or_children = array(); //To be populated only if $focus_in is an OR intent
        $conditional_steps = array(); //To be populated only for Conditional Steps
        $metadata_this = array(
            '__in__metadata_common_steps' => array(), //The tree structure that would be shared with all users regardless of their quick replies (OR Intent Answers)
            '__in__metadata_expansion_steps' => array(), //Intents that may exist as a link to expand an Action Plan tree by answering OR intents
            '__in__metadata_expansion_conditional' => array(), //Intents that may exist as a link to expand an Action Plan tree via Conditional Step links
        );


        //Fetch children:
        foreach($this->Links_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'in_status' => 2, //Published
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_parent_intent_id' => $focus_in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $in_child){

            //Determine action based on parent intent type:
            if($in_child['ln_type_entity_id']==4229){

                //Conditional Step Link:
                array_push($conditional_steps, intval($in_child['in_id']));

            } elseif($has_or_parent){

                //OR parent Intent with Fixed Step Link:
                array_push($or_children, intval($in_child['in_id']));

            } else {

                //AND parent Intent with Fixed Step Link:
                array_push($metadata_this['__in__metadata_common_steps'], intval($in_child['in_id']));

                //Go recursively down:
                $child_recursion = $this->Intents_model->in_metadata_common_base($in_child);


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
        if($is_first_intent){

            //Make sure to add main intent to common tree:
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

    function in_metadata_extra_insights($in_id, $update_db = true)
    {

        /*
         *
         * Generates additional insights like
         * min/max steps, time, cost and
         * referenced entities in intent notes.
         *
         * */

        //Fetch this intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status' => 2, //Published
        ));
        if(count($ins) < 1){
            return false;
        }

        $in_metadata = unserialize( $ins[0]['in_metadata'] );
        if(!isset($in_metadata['in__metadata_common_steps'])){
            return false;
        }

        //Fetch common base and expansion paths from intent metadata:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);
        $expansion_steps = ( isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0 ? $in_metadata['in__metadata_expansion_steps'] : array() );
        $expansion_steps = ( isset($in_metadata['in__metadata_expansion_conditional']) && count($in_metadata['in__metadata_expansion_conditional']) > 0 ? $in_metadata['in__metadata_expansion_conditional'] : array() );

        //Fetch totals for published common step intents:
        $common_totals = $this->Intents_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status' => 2, //Published
        ), array(), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_completion_seconds) as total_seconds');

        $common_base_resources = array(
            'steps' => $common_totals[0]['total_steps'],
            'seconds' => $common_totals[0]['total_seconds'],
        );

        $metadata_this = array(

            //Required steps/intents range to complete tree:
            '__in__metadata_min_steps' => $common_base_resources['steps'],
            '__in__metadata_max_steps' => $common_base_resources['steps'],

            //Required time range to complete tree:
            '__in__metadata_min_seconds' => $common_base_resources['seconds'],
            '__in__metadata_max_seconds' => $common_base_resources['seconds'],

            //Entity references within intent notes:
            '__in__metadata_experts' => array(),
            '__in__metadata_sources' => array(),

        );



        //Add-up Intent Note References:
        //The entities we need to check and see if they are industry experts:
        foreach ($this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4986')) . ')' => null, //Intent Notes that could possibly reference an entity
            'ln_parent_entity_id >' => 0, //Intent Notes that actually do reference an entity
            '(ln_child_intent_id='.$in_id.( count($flat_common_steps) > 0 ? ' OR ln_child_intent_id IN ('.join(',',$flat_common_steps).')' : '' ).')' => null,
            'ln_status' => 2, //Published
            'en_status' => 2, //Published
        ), array('en_parent'), 0) as $note_en) {

            //Referenced entity in intent notes... Fetch parents:
            foreach($this->Links_model->ln_fetch(array(
                'ln_child_entity_id' => $note_en['ln_parent_entity_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Entity Link Connectors
                'ln_status' => 2, //Published
            ), array(), 0) as $parent_en){

                if(in_array($parent_en['ln_parent_entity_id'], $this->config->item('en_ids_3000'))){

                    //Expert Source:
                    if (!isset($metadata_this['__in__metadata_sources'][$parent_en['ln_parent_entity_id']][$note_en['en_id']])) {
                        //Add since it's not there:
                        $metadata_this['__in__metadata_sources'][$parent_en['ln_parent_entity_id']][$note_en['en_id']] = $note_en;
                    }

                } elseif($parent_en['ln_parent_entity_id']==3084) {

                    //Industry Expert:
                    if (!isset($metadata_this['__in__metadata_experts'][$note_en['en_id']])) {
                        $metadata_this['__in__metadata_experts'][$note_en['en_id']] = $note_en;
                    }

                } else {

                    //Industry Expert?
                    $expert_parents = $this->Links_model->ln_fetch(array(
                        'en_status' => 2, //Published
                        'ln_status' => 2, //Published
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Entity Link Connectors
                        'ln_parent_entity_id' => 3084, //Industry Experts
                        'ln_child_entity_id' => $parent_en['ln_parent_entity_id'],
                    ), array('en_child'), 0);

                    if(count($expert_parents) > 0){

                        //Yes, Industry Expert:
                        if (!isset($metadata_this['__in__metadata_experts'][$parent_en['ln_parent_entity_id']])) {
                            $metadata_this['__in__metadata_experts'][$parent_en['ln_parent_entity_id']] = $expert_parents[0];
                        }

                    } else {
                        //TODO Maybe this is an expert source that is a slice of another expert source? Go another level-up and check parents...
                    }
                }
            }
        }


        //Go through expansion paths, if any:
        foreach(array_merge($expansion_steps, $expansion_steps) as $expansion_group){

            //Determine OR Answer local min/max:
            $metadata_local = array(
                'local__in__metadata_min_steps'=> null,
                'local__in__metadata_max_steps'=> null,
                'local__in__metadata_min_seconds'=> null,
                'local__in__metadata_max_seconds'=> null,
            );

            foreach($expansion_group as $expansion_in_id){

                $metadata_recursion = $this->Intents_model->in_metadata_extra_insights($expansion_in_id, false);

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
             * Sort Miners, Experts & Sources by trust score
             *
             * */
            usort($metadata_this['__in__metadata_experts'], 'sort_by_en_trust_score');
            foreach ($metadata_this['__in__metadata_sources'] as $type_en_id => $current_us) {
                usort($metadata_this['__in__metadata_sources'][$type_en_id], 'sort_by_en_trust_score');
            }


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

    function in_force_verb_creation($in_outcome, $ln_miner_entity_id = 0){

        //Fetch related variables:
        $outcome_words = explode(' ', $in_outcome);
        $starting_verb = trim($outcome_words[0]);
        $in_verb_entity_id = detect_starting_verb_id($in_outcome);

        //Run some checks on the intent outcome:
        if(count($outcome_words) < 3) {

            //The /force is a word, so Verb is too short:
            return array(
                'status' => 0,
                'message' => 'Outcome must have at-least two words',
            );

        } elseif(!(substr($in_outcome, -7) == ' /force')){

            //not positioned correctly:
            return array(
                'status' => 0,
                'message' => '/force command must be the last word of the outcome',
            );

        } elseif(!en_auth(array(1281))){

            //Not a acceptable Verb:
            return array(
                'status' => 0,
                'message' => '/force command is only available to moderators',
            );

        } elseif(strlen($starting_verb) < 2) {

            //Verb is too short:
            return array(
                'status' => 0,
                'message' => 'Verb must be at-least 2 characters long',
            );

        } elseif(!ctype_alpha($starting_verb)){

            //Not a acceptable Verb:
            return array(
                'status' => 0,
                'message' => 'Verb should only consist of letters A-Z',
            );

        }

        //Create the supporting verb if not already there:
        if(!$in_verb_entity_id){

            //Add and link verb:
            $added_en = $this->Entities_model->en_verify_create(ucwords(strtolower($starting_verb)), $ln_miner_entity_id, true);

            //Link to supported verbs:
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $ln_miner_entity_id,
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4230, //Raw
                'ln_parent_entity_id' => 5008, //Intent Supported Verbs
                'ln_child_entity_id' => $added_en['en']['en_id'],
            ));

            //Assign new verb ID to this intent:
            $in_verb_entity_id = $added_en['en']['en_id'];
        }


        //All good, return results:
        return array(
            'status' => 1,
            'in_cleaned_outcome' => str_replace(' /force' , '', $in_outcome),
            'in_verb_entity_id' => $in_verb_entity_id,
        );

    }

    function in_validate_outcome($in_outcome, $ln_miner_entity_id = 0, $skip_in_id = 0){

        //Assign verb variables:
        $in_verb_entity_id = detect_starting_verb_id($in_outcome);

        //Validate outcome:
        if(strlen($in_outcome) < 5){

            return array(
                'status' => 0,
                'message' => 'Outcome must be at-least 5 characters long',
            );

        } elseif(substr_count($in_outcome , ' ') < 1){

            return array(
                'status' => 0,
                'message' => 'Outcome must have at-least two words',
            );

        } elseif(substr_count($in_outcome , '  ') > 0){

            return array(
                'status' => 0,
                'message' => 'Outcome cannot include double spaces',
            );

        } elseif(substr_count($in_outcome , '::') >= 2){

            return array(
                'status' => 0,
                'message' => 'You can only use the double colon command once',
            );

        } elseif(strlen($in_outcome) < 5){

            return array(
                'status' => 0,
                'message' => 'Outcome must be at-least 5 characters long',
            );

        } elseif(substr_count($in_outcome , '/force') > 0){

            //Force command detected, pass it on to the force function:
            $force_outcome = $this->Intents_model->in_force_verb_creation($in_outcome, $ln_miner_entity_id);

            if(!$force_outcome['status']){
                //We had some errors in outcome structure:
                return $force_outcome['status'];
            }

            //Update forced variables:
            $in_outcome = $force_outcome['in_cleaned_outcome'];

            //Update supporting verb ID if it was not set:
            if(!$in_verb_entity_id){
                $in_verb_entity_id = $force_outcome['in_verb_entity_id'];
            }

        } elseif(!$in_verb_entity_id) {

            //Not a acceptable Verb:
            return array(
                'status' => 0,
                'message' => 'Verb is not yet supported. Manage supported verbs via entity @5008'.( en_auth(array(1281)) ? ' or use the /force command to add this verb to the supported list.' : '' ),
            );

        }

        //Check length now that potential /force command is removed:
        if (strlen($in_outcome) > $this->config->item('in_outcome_max')) {
            return array(
                'status' => 0,
                'message' => 'Intent outcome cannot be longer than '.$this->config->item('in_outcome_max').' characters',
            );
        }

        //Do we have a double colon command? If so, make sure we also have an intent reference:
        if(substr_count($in_outcome , '::') ==1){

            //Does the outcome have a parent intent reference?
            $string_references = extract_references($in_outcome);

            if(count($string_references['ref_intents']) != 1){
                return array(
                    'status' => 0,
                    'message' => 'Double colon required an intent reference (like #1234) in the outcome',
                );
            } elseif(strpos($in_outcome,'#') > strpos($in_outcome,'::')){
                return array(
                    'status' => 0,
                    'message' => 'Intent reference must appear before double colon',
                );
            }
        }

        //Check to make sure it's not a duplicate outcome:
        $in_dup_search_filters = array(
            'in_status >=' => 0, //New+
            'LOWER(in_outcome)' => strtolower(trim($in_outcome)),
        );
        if($skip_in_id > 0){
            $in_dup_search_filters['in_id !='] = $skip_in_id;
        }
        foreach($this->Intents_model->in_fetch($in_dup_search_filters) as $dup_in){
            //This is a duplicate, disallow:
            $fixed_fields = $this->config->item('fixed_fields');
            return array(
                'status' => 0,
                'message' => 'Outcome ['.$in_outcome.'] already in-use by intent #'.$dup_in['in_id'].' with status ['.$fixed_fields['in_status'][$dup_in['in_status']]['s_name'].']',
            );
        }

        //All good, return success:
        return array(
            'status' => 1,
            'in_cleaned_outcome' => trim($in_outcome),
            'detected_verb_entity_id' => $in_verb_entity_id,
        );

    }


}