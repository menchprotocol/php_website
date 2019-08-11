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


    function in_create($insert_columns, $external_sync = false, $ln_creator_entity_id = 0)
    {

        //What is required to create a new intent?
        if (detect_missing_columns($insert_columns, array('in_outcome', 'in_type_entity_id', 'in_status_entity_id', 'in_verb_entity_id'), $ln_creator_entity_id)) {
            return false;
        }

        //Lets now add:
        $this->db->insert('table_intents', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['in_id'])) {
            $insert_columns['in_id'] = $this->db->insert_id();
        }

        if ($insert_columns['in_id'] > 0) {

            if ($ln_creator_entity_id > 0) {

                if($external_sync){
                    //Update Algolia:
                    $algolia_sync = update_algolia('in', $insert_columns['in_id']);
                }

                //Log link new entity:
                $this->Links_model->ln_create(array(
                    'ln_creator_entity_id' => $ln_creator_entity_id,
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
                'ln_content' => 'in_create() failed to create a new intent',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $ln_creator_entity_id,
                'ln_metadata' => $insert_columns,
            ));
            return false;

        }
    }

    function in_fetch($match_columns = array(), $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
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
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                    'ln_child_intent_id' => $value['in_id'],
                ), array(), 0, 0, array('ln_order' => 'ASC'));
            }

            //Should we fetch all parent intentions?
            if (in_array('in__parents', $join_objects)) {

                $ins[$key]['in__parents'] = $this->Links_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_child_intent_id' => $value['in_id'],
                ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

            }

            //Have we been asked to append any children/granchildren to this query?
            if (in_array('in__children', $join_objects) || in_array('in__grandchildren', $join_objects)) {

                //Fetch immediate children:
                $ins[$key]['in__children'] = $this->Links_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_parent_intent_id' => $value['in_id'],
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered


                if (in_array('in__grandchildren', $join_objects) && count($ins[$key]['in__children']) > 0) {
                    //Fetch second-level grandchildren intents:
                    foreach ($ins[$key]['in__children'] as $key2 => $value2) {

                        $ins[$key]['in__children'][$key2]['in__grandchildren'] = $this->Links_model->ln_fetch(array(
                            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
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

    function in_update($id, $update_columns, $external_sync = false, $ln_creator_entity_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        //Fetch current intent filed values so we can compare later on after we've updated it:
        if($ln_creator_entity_id > 0){
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
        if ($affected_rows > 0 && $ln_creator_entity_id > 0) {

            //Note that unlike entity modification, we require a miner entity ID to log the modification link:
            //Log modification link for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('in_metadata'))) {

                    $en_all_4737 = $this->config->item('en_all_4737'); // Intent Statuses

                    //Value has changed, log link:
                    $this->Links_model->ln_create(array(
                        'ln_creator_entity_id' => $ln_creator_entity_id,
                        'ln_type_entity_id' => 4264, //Intent Attribute Modified
                        'ln_child_intent_id' => $id,
                        'ln_content' => echo_clean_db_name($key) . ' changed from "' . ( in_array($key , array('in_status_entity_id')) ? $en_all_4737[$before_data[0][$key]]['m_name']  : $before_data[0][$key] ) . '" to "' . ( in_array($key , array('in_status_entity_id')) ? $en_all_4737[$value]['m_name'] : $value ) . '"',
                        'ln_metadata' => array(
                            'in_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));

                }

            }

            if($external_sync){
                //Sync algolia:
                update_algolia('in', $id);
            }

        } elseif($affected_rows < 1){

            //This should not happen:
            $this->Links_model->ln_create(array(
                'ln_child_intent_id' => $id,
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $ln_creator_entity_id,
                'ln_content' => 'in_update() Failed to update',
                'ln_metadata' => array(
                    'input' => $update_columns,
                ),
            ));

        }

        return $affected_rows;
    }

    function in_unlink($in_id, $ln_creator_entity_id = 0){

        //Remove intent relations:
        $intent_remove_links = array_merge(
            $this->Links_model->ln_fetch(array( //Intent Links
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                '(ln_child_intent_id = '.$in_id.' OR ln_parent_intent_id = '.$in_id.')' => null,
            ), array(), 0),
            $this->Links_model->ln_fetch(array( //Intent Notes
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
                '(ln_child_intent_id = '.$in_id.' OR ln_parent_intent_id = '.$in_id.')' => null,
            ), array(), 0)
        );

        $links_removed = 0;
        foreach($intent_remove_links as $ln){
            //Remove this link:
            $links_removed += $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_status_entity_id' => 6173, //Link Removed
            ), $ln_creator_entity_id);
        }

        //Return links removed:
        return $links_removed;
    }

    function in_link_or_create($in_linked_id, $is_parent, $in_outcome, $ln_creator_entity_id, $new_in_status = 6183 /* Intent New */, $in_type_entity_id = 6677 /* AND Got It */, $link_in_id = 0, $next_level = 0)
    {

        /*
         *
         * The main intent creation function that would create
         * appropriate links and return the intent view.
         *
         * Either creates an intent link between $in_linked_id & $link_in_id
         * (IF $link_in_id>0) OR will create a new intent with outcome $in_outcome
         * and link it to $in_linked_id (In this case $link_in_id will be 0)
         *
         * p.s. Inputs have already been validated via intents/in_link_or_create() function
         *
         * */

        //Validate Original intent:
        $linking_to_existing = (intval($link_in_id) > 0);
        $linked_ins = $this->Intents_model->in_fetch(array(
            'in_id' => intval($in_linked_id),
        ));

        if (count($linked_ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        } elseif (!in_array($linked_ins[0]['in_status_entity_id'], $this->config->item('en_ids_7356')) /* Intent Statuses Active */) {
            return array(
                'status' => 0,
                'message' => 'You can only link to active intents. This intent is not active.',
            );
        }

        if ($linking_to_existing) {

            //We are linking to $link_in_id, We are NOT creating any new intents...

            //Fetch more details on the child intent we're about to link:
            $ins = $this->Intents_model->in_fetch(array(
                'in_id' => $link_in_id,
            ));

            //Determine which is parent intent, and which is child
            $parent_in = ( $is_parent ? $ins[0] : $linked_ins[0] );
            $child_in = ( !$is_parent ? $ins[0] : $linked_ins[0] );

            if (count($ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Intent ID',
                );
            } elseif (!in_array($ins[0]['in_status_entity_id'], $this->config->item('en_ids_7356') /* Intent Statuses Active */)) {
                return array(
                    'status' => 0,
                    'message' => 'You can only link to active intents. This intent is not active.',
                );
            }

            //All good so far, continue with linking:
            $intent_new = $ins[0];

            //Make sure this is not a duplicate intent for its parent:
            $dup_links = $this->Links_model->ln_fetch(array(
                'ln_parent_intent_id' => $parent_in['in_id'],
                'ln_child_intent_id' => $child_in['in_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ));

            //Check for issues:
            if (count($dup_links) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $intent_new['in_outcome'] . '] is already linked here.',
                );

            } elseif ($link_in_id == $in_linked_id) {

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
                $parent_in_outcome_words = explode(' ', $linked_ins[0]['in_outcome']);
                $in_outcome = $parent_in_outcome_words[0].' #'.$linked_ins[0]['in_id'].' :: '.trim(substr($in_outcome, 2));
            }


            //Validate Intent Outcome:
            $in_outcome_validation = $this->Intents_model->in_validate_outcome($in_outcome, $ln_creator_entity_id);
            if(!$in_outcome_validation['status']){
                //We had an error, return it:
                return $in_outcome_validation;
            }


            //Create new intent:
            $intent_new = $this->Intents_model->in_create(array(
                'in_outcome' => $in_outcome_validation['in_cleaned_outcome'],
                'in_verb_entity_id' => $in_outcome_validation['detected_verb_entity_id'],
                'in_type_entity_id' => $in_type_entity_id,
                'in_status_entity_id' => $new_in_status,
            ), true, $ln_creator_entity_id);

        }


        //Create Intent Link:
        $relation = $this->Links_model->ln_create(array(
            'ln_creator_entity_id' => $ln_creator_entity_id,
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $in_linked_id,
            ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
            'ln_order' => 1 + $this->Links_model->ln_max_order(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                    'ln_parent_intent_id' => ( $is_parent ? $intent_new['in_id'] : $in_linked_id ),
                )),
        ), true);


        //See if parent intent is locked:
        if($linking_to_existing && in_is_unlockable($parent_in)){
            //Yes, we need to check to see if this change triggers new completions:

        }

        //Add Up-Vote if not yet added for this miner:
        if($ln_creator_entity_id > 0){

            $ln_miner_upvotes = $this->Links_model->ln_fetch(array(
                ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $in_linked_id,
                ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
                'ln_parent_entity_id' => $ln_creator_entity_id,
                'ln_type_entity_id' => 4983, //Up-votes
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ));

            if(count($ln_miner_upvotes) == 0){
                //Add new up-vote
                //No need to sync external sources via ln_create()
                $up_vote = $this->Links_model->ln_create(array(
                    'ln_creator_entity_id' => $ln_creator_entity_id,
                    'ln_parent_entity_id' => $ln_creator_entity_id,
                    'ln_type_entity_id' => 4983, //Up-votes
                    'ln_content' => '@'.$ln_creator_entity_id.' #'.( $is_parent ? $intent_new['in_id'] : $in_linked_id ), //Message content
                    ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $in_linked_id,
                    ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
                ));
            }

        }


        //Fetch and return full data to be properly shown on the UI using the echo_in() function
        $new_ins = $this->Links_model->ln_fetch(array(
            ( $is_parent ? 'ln_child_intent_id' : 'ln_parent_intent_id' ) => $in_linked_id,
            ( $is_parent ? 'ln_parent_intent_id' : 'ln_child_intent_id' ) => $intent_new['in_id'],
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ), array(($is_parent ? 'in_parent' : 'in_child')), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


        //Return result:
        return array(
            'status' => 1,
            'new_in_id' => $intent_new['in_id'],
            'in_child_html' => ( in_array($next_level, array(2,3)) ? echo_in($new_ins[0], $next_level, $in_linked_id, $is_parent) : null ),
        );

    }

    function in_manual_response_note($in, $push_message = false)
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
        $ui = '';

        if($push_message){
            $ui .= 'Send me a '. strtolower($en_all_6794[$in['in_type_entity_id']]['m_name']) .' to complete this step.';
        } else {
            $ui .= '<p>Submit a '.$en_all_6794[$in['in_type_entity_id']]['m_name'] .' to continue:</p>';
            $ui .= '<textarea id="stop_feedback" class="border" placeholder="" style="height:66px; width: 100%; padding: 5px;"></textarea>';
        }

        //Return User-friendly message for Requires Manual Response:
        return $ui;

    }


    function in_fetch_recursive_public_parents($in_id, $first_level = true){

        $grand_parents = array();

        //Fetch parents:
        foreach($this->Links_model->ln_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
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
            $recursive_parents = $this->Intents_model->in_fetch_recursive_public_parents($p_id, false);
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
        foreach($this->Links_model->ln_fetch(array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
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



    function in_is_public($in, $force_starting_step = false){

        //Find reasons for it not being available...

        if ( !in_array($in['in_status_entity_id'], $this->config->item('en_ids_7355') /* Intent Statuses Public */) ) {

            //Don't show the intent name yet as its not published:
            return array(
                'status' => 0,
                'message' => 'Intent #' . $in['in_id'] . ' status is not yet public',
            );

        } elseif ( in_array($in['in_type_entity_id'], $this->config->item('en_ids_7366')) ) {

            //Intent type is private by nature:
            return array(
                'status' => 0,
                'message' => 'Intent #' . $in['in_id'] . ' type is private',
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
        $has_or_parent = in_array($focus_in['in_type_entity_id'] , $this->config->item('en_ids_6193') /* OR Intents */ );
        $or_children = array(); //To be populated only if $focus_in is an OR intent
        $conditional_steps = array(); //To be populated only for Conditional Steps
        $metadata_this = array(
            '__in__metadata_common_steps' => array(), //The tree structure that would be shared with all users regardless of their quick replies (OR Intent Answers)
            '__in__metadata_expansion_steps' => array(), //Intents that may exist as a link to expand an Action Plan tree by answering OR intents
            '__in__metadata_expansion_conditional' => array(), //Intents that may exist as a link to expand an Action Plan tree via Conditional Step links
            '__in__metadata_terminations_7740' => array(), //Defines intents that are Terminate Intention in the tree @7742
        );

        //Fetch children:
        foreach($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
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


                if($in_child['in_type_entity_id']==7740 /* Cancel Intention */){
                    array_push($metadata_this['__in__metadata_terminations_7740'], intval($in_child['in_id']));
                }

                //Go recursively down:
                $child_recursion = $this->Intents_model->in_metadata_common_base($in_child);


                //Aggregate recursion data:
                if(count($child_recursion['__in__metadata_common_steps']) > 0){
                    array_push($metadata_this['__in__metadata_common_steps'], $child_recursion['__in__metadata_common_steps']);
                }

                if(count($child_recursion['__in__metadata_terminations_7740']) > 0){
                    array_push($metadata_this['__in__metadata_terminations_7740'], $child_recursion['__in__metadata_terminations_7740']);
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
                'in__metadata_terminations_7740' => $metadata_this['__in__metadata_terminations_7740'],
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
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
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
        $locked_steps = ( isset($in_metadata['in__metadata_expansion_conditional']) && count($in_metadata['in__metadata_expansion_conditional']) > 0 ? $in_metadata['in__metadata_expansion_conditional'] : array() );

        //Fetch totals for published common step intents:
        $common_totals = $this->Intents_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
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
            'ln_parent_entity_id >' => 0, //Intent Notes that reference an entity
            '(ln_child_intent_id='.$in_id.( count($flat_common_steps) > 0 ? ' OR ln_child_intent_id IN ('.join(',',$flat_common_steps).')' : '' ).')' => null,
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
        ), array('en_parent'), 0) as $note_en) {

            //Referenced entity in intent notes... Fetch parents:
            foreach($this->Links_model->ln_fetch(array(
                'ln_child_entity_id' => $note_en['ln_parent_entity_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')).')' => null, //Entity Link Connectors
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
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
                        'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
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
            usort($metadata_this['__in__metadata_experts'], 'en_trust_score_sort');
            foreach ($metadata_this['__in__metadata_sources'] as $type_en_id => $current_us) {
                usort($metadata_this['__in__metadata_sources'][$type_en_id], 'en_trust_score_sort');
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


    function in_unlock_paths($in)
    {
        /*
         *
         * Finds the pathways, if any, on how to unlock $in
         *
         * */


        //Validate this locked intent:
        if(!in_is_unlockable($in)){
            return array();
        }

        $child_unlock_paths = array();


        //Step 1: Is there an OR parent that we can simply answer and unlock?
        foreach($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_child_intent_id' => $in['in_id'],
            'in_type_entity_id IN (' . join(',', $this->config->item('en_ids_7712')) . ')' => null, //Intent Answer Types
        ), array('in_parent'), 0) as $in_or_parent){
            if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $in_or_parent['in_id'])) {
                array_push($child_unlock_paths, $in_or_parent);
            }
        }


        //Step 2: Are there any locked link parents that the user might be able to unlock?
        foreach($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4229, //Intent Link Locked Step
            'ln_child_intent_id' => $in['in_id'],
        ), array('in_parent'), 0) as $in_locked_parent){
            if(in_is_unlockable($in_locked_parent)){
                //Need to check recursively:
                foreach($this->Intents_model->in_unlock_paths($in_locked_parent) as $locked_path){
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
            //Return OR parents for unlocking this intent:
            return $child_unlock_paths;
        }


        //Step 3: We don't have any OR parents, let's see how we can complete all children to meet the requirements:
        $in__children = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        if(count($in__children) < 1){
            //No children, no path:
            return array();
        }

        //Go through children to see if any/all can be completed:
        foreach($in__children as $in_child){
            if(in_is_unlockable($in_child)){

                //Need to check recursively:
                foreach($this->Intents_model->in_unlock_paths($in_child) as $locked_path){
                    if(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $locked_path['in_id'])) {
                        array_push($child_unlock_paths, $locked_path);
                    }
                }

            } elseif(count($child_unlock_paths)==0 || !filter_array($child_unlock_paths, 'in_id', $in_child['in_id'])) {

                //Not locked, so this can be completed:
                array_push($child_unlock_paths, $in_child);

            }
        }
        return $child_unlock_paths;

    }

    function in_force_verb_creation($in_outcome, $ln_creator_entity_id = 0){

        //Fetch related variables:
        $outcome_words = explode(' ', $in_outcome);
        $starting_verb = trim($outcome_words[0]);
        $in_verb_entity_id = in_outcome_verb_id($in_outcome);

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
            $added_en = $this->Entities_model->en_verify_create(ucwords(strtolower($starting_verb)), $ln_creator_entity_id, false, 6181);

            //Link to supported verbs:
            $this->Links_model->ln_create(array(
                'ln_creator_entity_id' => $ln_creator_entity_id,
                'ln_status_entity_id' => 6176, //Link Published
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


    function in_validate_outcome($in_outcome, $ln_creator_entity_id = 0, $skip_in_id = 0){

        //Assign verb variables:
        $in_verb_entity_id = in_outcome_verb_id($in_outcome);

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
            $force_outcome = $this->Intents_model->in_force_verb_creation($in_outcome, $ln_creator_entity_id);

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
                'message' => 'Intent must start with a supporting verb',
            );

        }

        //Check length now that potential /force command is removed:
        if (strlen($in_outcome) > $this->config->item('in_outcome_max')) {
            return array(
                'status' => 0,
                'message' => 'Intent outcome cannot be longer than '.$this->config->item('in_outcome_max').' characters',
            );
        }


        //Does the outcome have a parent intent reference?
        $string_references = extract_references($in_outcome);


        if(count($string_references['ref_forbidden']) > 0){

            return array(
                'status' => 0,
                'message' => 'Intent outcome must be focused, micro and singular, so you cannot use "'.join('" or "',$string_references['ref_forbidden']).'". Think of the core outcome and just put that.',
            );

        } elseif(substr_count($in_outcome , '::') ==1){

            /*
            if(count($string_references['ref_intents']) != 1){
                return array(
                    'status' => 0,
                    'message' => 'Double colon required an intent reference (like #1234) in the outcome',
                );
            } else
            */

            if(count($string_references['ref_intents'])>0 && strpos($in_outcome,'#') > strpos($in_outcome,'::')){

                return array(
                    'status' => 0,
                    'message' => 'Intent reference must appear before double colon',
                );

            } else if(count($string_references['ref_intents'])>1){

                return array(
                    'status' => 0,
                    'message' => 'Intent outcome can reference a maximum of 1 intent',
                );

            }
        }

        //Check to make sure it's not a duplicate outcome:
        $in_dup_search_filters = array(
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            'LOWER(in_outcome)' => strtolower(trim($in_outcome)),
        );
        if($skip_in_id > 0){
            $in_dup_search_filters['in_id !='] = $skip_in_id;
        }
        foreach($this->Intents_model->in_fetch($in_dup_search_filters) as $dup_in){
            //This is a duplicate, disallow:
            $en_all_4737 = $this->config->item('en_all_4737'); // Intent Statuses
            return array(
                'status' => 0,
                'message' => 'Outcome ['.$in_outcome.'] already in-use by intent #'.$dup_in['in_id'].' with status ['.$en_all_4737[$dup_in['in_status_entity_id']]['m_name'].']',
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