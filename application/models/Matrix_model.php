<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Matrix_model extends CI_Model
{

    /*
     *
     * This model contains all Database functions that
     * interpret the Matrix from a particular perspective
     * to gain understanding from it and to perform pre-defined
     * operations.
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function fn___actionplan_next_in($actionplan_tr_id)
    {

        /*
         *
         * Attempts to find the next item in the Action Plan
         * And if not, it would mark the Action Plan as complete!
         *
         * */

        //Let's first check if we have an OR Intent that is working On, which means it's children have not been answered!
        $first_pending_or_intent = $this->Database_model->fn___tr_fetch(array(
            'tr_tr_parent_id' => $actionplan_tr_id, //This action Plan
            'in_status >=' => 2, //Published+
            'in_is_any' => 1, //OR Branch
            'tr_status' => 1, //Working On, which means OR branch has not been answered yet
        ), array('in_child'), 1, 0, array('tr_order' => 'ASC'));

        if (count($first_pending_or_intent) > 0) {
            return $first_pending_or_intent;
        }


        //Now check the next AND intent that has not been started:
        $next_new_intent = $this->Database_model->fn___tr_fetch(array(
            'tr_tr_parent_id' => $actionplan_tr_id, //This action Plan
            'in_status >=' => 2, //Published+
            'tr_status' => 0, //New (not started yet) for either AND/OR branches
        ), array('in_child'), 1, 0, array('tr_order' => 'ASC'));

        if (count($next_new_intent) > 0) {
            return $next_new_intent;
        }


        //Now check the next AND intent that is working on:
        //I don't think this situation should ever happen...
        //Because if we don't have any of the previous ones,
        //how can we have this? 🤔 But let's keep it for now...
        $next_working_on_intent = $this->Database_model->fn___tr_fetch(array(
            'tr_tr_parent_id' => $actionplan_tr_id, //This action Plan
            'in_status >=' => 2, //Published+
            'in_is_any' => 0, //AND Branch
            'tr_status' => 1, //Working On
        ), array('in_child'), 1, 0, array('tr_order' => 'ASC'));

        if (count($next_working_on_intent) > 0) {
            return $next_working_on_intent;
        }


        /*
         *
         * The Action Plan seems to be completed as we could not find any pending intent!
         * Nothing else left to do, we must be done with this Action Plan:
         * What is the Action Plan Status?
         *
         * */

        $actionplans = $this->Database_model->fn___tr_fetch(array(
            'tr_id' => $actionplan_tr_id,
        ), array('in_child'));

        if (count($actionplans) > 0 && in_array($actionplans[0]['tr_status'], $this->config->item('tr_status_incomplete'))) {

            //Inform user that they are now complete with all tasks:
            $this->Chat_model->fn___dispatch_message(
                'Congratulations for completing your Action Plan 🎉 Over time I will keep sharing new key ideas (based on my new training data) that will help you to ' . $actionplans[0]['in_outcome'] . ' 🙌 You can, at any time, stop updates on your Action Plans by saying "unsubscribe".',
                array( 'en_id' => $actionplans[0]['tr_en_parent_id'] ),
                true,
                array(),
                array(
                    'tr_in_child_id' => $actionplans[0]['tr_in_child_id'],
                    'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                )
            );

            $this->Chat_model->fn___dispatch_message(
                'How else can I help you ' . $this->config->item('in_strategy_name') . '?',
                array( 'en_id' => $actionplans[0]['tr_en_parent_id'] ),
                true,
                array(),
                array(
                    'tr_in_child_id' => $actionplans[0]['tr_in_child_id'],
                    'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                )
            );

            //The entire Action Plan is now complete!
            $this->Database_model->fn___tr_update($actionplan_tr_id, array(
                'tr_status' => 2, //Completed
            ), $actionplans[0]['tr_en_parent_id']);

            //Inform Student on how to can command Mench:
            $this->Chat_model->fn___compose_message(8332, array('en_id' => $actionplans[0]['tr_en_parent_id']));

        }

        return false;

    }


    function fn___en_radio_set($en_parent_bucket_id, $set_en_child_id = 0, $en_master_id, $tr_en_credit_id = 0)
    {

        /*
         * Treats an entity child group as a drop down menu where:
         *
         *  $en_parent_bucket_id is the parent of the drop down
         *  $en_master_id is the master entity ID that one of the children of $en_parent_bucket_id should be assigned (like a drop down)
         *  $set_en_child_id is the new value to be assigned, which could also be null (meaning just remove all current values)
         *
         * This function is helpful to manage things like Student communication levels
         *
         * */


        //Fetch all the child entities for $en_parent_bucket_id and make sure they match $set_en_child_id
        $children = $this->config->item('en_ids_' . $en_parent_bucket_id);
        if ($en_parent_bucket_id < 1) {
            return false;
        } elseif (!$children) {
            return false;
        } elseif ($set_en_child_id > 0 && !in_array($set_en_child_id, $children)) {
            return false;
        }

        //First remove existing parent/child transactions for this drop down:
        $already_assigned = ($set_en_child_id < 1);
        $updated_tr_id = 0;
        foreach ($this->Database_model->fn___tr_fetch(array(
            'tr_en_child_id' => $en_master_id,
            'tr_en_parent_id IN (' . join(',', $children) . ')' => null, //Current children
            'tr_status >=' => 0,
        ), array(), 200) as $tr) {

            if (!$already_assigned && $tr['tr_en_parent_id'] == $set_en_child_id) {
                $already_assigned = true;
            } else {
                //Remove assignment:
                $updated_tr_id = $tr['tr_id'];

                //Do not log update transaction here as we would log it further below:
                $this->Database_model->fn___tr_update($tr['tr_id'], array(
                    'tr_status' => -1, //Removed
                ));
            }

        }


        //Make sure $set_en_child_id belongs to parent if set (Could be null which means remove all)
        if (!$already_assigned) {
            //Let's go ahead and add desired entity as parent:
            $this->Database_model->fn___tr_create(array(
                'tr_en_credit_id' => $tr_en_credit_id,
                'tr_en_child_id' => $en_master_id,
                'tr_en_parent_id' => $set_en_child_id,
                'tr_en_type_id' => 4230, //Naked link
                'tr_tr_parent_id' => $updated_tr_id,
            ));
        }

    }



    function fn___en_url_add($input_url, $tr_en_credit_id = 0)
    {

        /*
         *
         * The function that would add/validate new URLs
         * which would also check for duplicates, etc...
         * And create a new entity.
         *
         * Note that this function is mainly used in intent message
         * related functions as that's when we need to turn URLs into
         * entities to be stored properly on the matrix
         *
         * */

        if (!filter_var($input_url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        }


        //Check if this URL has already been added:
        //TODO This part can be improved to better detect duplicate URLs as it current does an exact matching, which is OK but not the best...
        $input_url = trim($input_url);
        $dup_urls = $this->Database_model->fn___tr_fetch(array(
            'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4537')) . ')' => null, //Entity URL Links
            'tr_content' => $input_url, //Exact Match!
        ), array('en_child'));

        if (count($dup_urls) > 0) {

            //Yes, we already have this URL added to an entity, return the entity:
            //Not removed?
            if ($dup_urls[0]['tr_status'] < 0) {

                $object_statuses = $this->config->item('object_statuses');

                //This URL was removed, which is an issue:
                return array(
                    'status' => 0,
                    'message' => 'This URL has had been added before but then removed with status [' . $object_statuses['en_status'][$dup_urls[0]['en_status']]['s_name'] . ']',
                );

            } else {

                //Return the object as this is expected:
                return array(
                    'status' => 1,
                    'is_existing' => 1,
                    'message' => 'Entity already existed',
                    'en_from_url' => $dup_urls[0],
                );

            }
        }


        //This is a new URL that has never been added before...
        //Call URL to validate it further:
        $curl = fn___curl_html($input_url, true);

        if (!$curl) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        }

        //We need to create a new entity and add this URL to it...
        //Was fn___curl_html() able to fetch the URL's <title> tag?
        if (strlen($curl['page_title']) > 0) {

            //Make sure this is not a duplicate name:
            $dup_name_us = $this->Database_model->fn___en_fetch(array(
                'en_status >=' => 0, //New+
                'en_name' => $curl['page_title'],
            ));

            if (count($dup_name_us) > 0) {
                //Yes, we did find a duplicate name! Append a unique identifier:
                $en_name = $curl['page_title'] . ' ' . substr(md5($input_url), 0, 8);
            } else {
                //No duplicate detected, we can use page title as is:
                $en_name = $curl['page_title'];
            }

        } else {

            //fn___curl_html() did not find a <title> tag:
            //Use URL Type as its name:
            $en_all_4537 = $this->config->item('en_all_4537');
            $en_name = $en_all_4537[$curl['tr_en_type_id']]['m_name'] . ' ' . substr(md5($input_url), 0, 8); //Append a unique identifier

        }


        //Create a new entity:
        $en = $this->Database_model->fn___en_create(array(
            'en_name' => $en_name,
            'en_status' => 2, //Published
        ), true, $tr_en_credit_id);


        /*
         *
         * Now we need to place this new entity under the right parent entity.
         *
         * See if we can pattern match the URL to a more relevant parent
         * We will start assuming that the parent should be the
         * en_default_parent_id defined in config unless we can match
         * the URL to another entity pattern
         *
         * */

        //Set default parent to start:
        $tr_en_parent_id = $this->config->item('en_default_parent_id');

        //Now see if we can find a better match:
        $matching_patterns = $this->Database_model->fn___tr_fetch(array(
            'tr_en_type_id' => 4255, //Text Link that contains the pattern match
            'tr_en_parent_id' => 3307, //Entity URL Pattern Match
            'tr_status >=' => 2, //Published+
        ));
        foreach ($matching_patterns as $match) {
            if (substr_count($input_url, $match['tr_content']) > 0) {
                //yes we found a pattern match:
                $tr_en_parent_id = $match['tr_en_child_id'];
                break;
            }
        }


        //Place this new entity in the default URL bucket:
        $entity_tr = $this->Database_model->fn___tr_create(array(
            'tr_en_credit_id' => $tr_en_credit_id,
            'tr_en_type_id' => $curl['tr_en_type_id'],
            'tr_en_parent_id' => $tr_en_parent_id,
            'tr_en_child_id' => $en['en_id'],
            'tr_content' => $input_url,
        ));

        //Return entity object:
        return array(
            'status' => 1,
            'is_existing' => 0,
            'message' => 'New Entity created from URL',
            'en_from_url' => array_merge($en, $entity_tr),
        );

    }


    function fn___in_completion_requirements($in_id, $actionplan_tr_id = 0)
    {

        /*
         *
         * Sometimes to mark an intent as complete the Students might
         * need to meet certain requirements in what they submit to do so.
         * This function fetches those requirements from the Matrix and
         * Provides an easy to understand message to communicate
         * these requirements to Student.
         *
         * Will return NULL if it detects no requirements...
         *
         * */

        //Fetch all possible Intent Completion Requirements for this intent to see what is required:
        $response_options = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 2, //Published
            'tr_en_type_id' => 4331, //Intent Completion Requirements
            'tr_en_child_id IN (' . join(',', $this->config->item('en_ids_4331')) . ')' => null, //Technically not needed, but here for extra clarity
            'tr_in_child_id' => $in_id, //For this intent
        ));

        if (count($response_options) < 1) {
            //Did not find any requirements:
            return null;
        }

        //Construct the message accordingly...

        //Fetch latest cache tree:
        $en_all_4331 = $this->config->item('en_all_4331'); //Intent Completion Requirements

        //How many do we have?
        if (count($response_options) == 1) {

            //Single option:
            $message = 'Marking as complete requires ' . $en_all_4331[$response_options[0]['tr_en_child_id']]['m_name'];

        } else {

            //Multiple options:
            $message = 'Marking as complete requires either: ';

            //Loop through all options:
            foreach ($response_options as $count => $en) {

                //Prefix:
                if (($count + 1) == count($response_options)) {
                    //This is the last item:
                    $message .= ' or ';
                } elseif ($count > 0) {
                    $message .= ', ';
                }

                //What is required:
                $message .= $en_all_4331[$en['tr_en_child_id']]['m_name'];

            }

        }

        //Give clear directions to complete if Action Plan ID is provided...
        if($actionplan_tr_id > 0){
            $message .= ', which you can submit using your Action Plan. /link:See in 🚩Action Plan:https://mench.com/master/actionplan/' . $actionplan_tr_id . '/' . $in_id;
        }

        //Return Student-friendly message for completion requirements:
        return $message;

    }


    function fn___en_master_messenger_authenticate($psid)
    {

        /*
         *
         * Detects the Student entity ID based on the
         * PSID provided by the Facebook Webhook Call.
         * This function returns the Student's entity object $en
         *
         */


        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'fn___en_master_messenger_authenticate() got called without a valid Facebook $psid variable',
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }

        //Try matching Facebook PSID to existing Students:
        $ens = $this->Database_model->fn___en_fetch(array(
            'en_status >=' => 0, //New+
            'en_psid' => intval($psid),
        ), array('skip_en__parents'));

        //So, did we find them?
        if (count($ens) > 0) {

            //Student found:
            return $ens[0];

        } else {

            //Student not found, create new Student:
            return $this->Matrix_model->fn___en_messenger_add($psid);

        }

    }


    function fn___actionplan_update($tr_id, $new_tr_status)
    {

        /*
         *
         * Marks an Action Plan as complete
         *
         * */

        //Validate Action Plan:
        $actionplan_ins = $this->Database_model->fn___tr_fetch(array(
            'tr_id' => $tr_id,
        ), array('in_child', 'en_parent'));
        if(count($actionplan_ins) < 1){
            return false;
        }

        //Update status:
        $this->Database_model->fn___tr_update($tr_id, array(
            'tr_status' => $new_tr_status,
        ), $actionplan_ins[0]['tr_en_parent_id']);

        //Take additional action if Action Plan is Complete:
        if ($new_tr_status == 2) {

            //It's complete!

            //Dispatch all on-complete messages if we have any:
            $on_complete_messages = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 2, //Published+
                'tr_en_type_id' => 4233, //On-Complete Messages
                'tr_in_child_id' => $actionplan_ins[0]['tr_in_child_id'],
            ), array('en_parent'), 0, 0, array('tr_order' => 'ASC'));

            foreach ($on_complete_messages as $tr) {
                $this->Chat_model->fn___dispatch_message(
                    $tr['tr_content'], //Message content
                    $actionplan_ins[0], //Includes entity data for Action Plan Student
                    true,
                    array(),
                    array(
                        'tr_tr_parent_id' => $actionplan_ins[0]['tr_tr_parent_id'],
                    )
                );
            }

            //TODO Update Action Plan progress (In tr_metadata) at this point
            //TODO implement drip?
        }
    }





    function fn___metadata_tree_update($obj_type, $focus_obj_id, $metadata_new = array(), $direction_is_downward = 0)
    {

        /*
         *
         * Keeps intent/entity metadata field in-sync when adjustments are made to tree items
         *
         * */

        //Currently only supports intents:
        if (count($metadata_new) == 0) {
            return false;
        }


        if (in_array($obj_type, array('in'))) {

            //Fetch tree that needs adjustment:
            $tree = $this->Matrix_model->fn___in_recursive_fetch($focus_obj_id, $direction_is_downward);

            if (count($tree['in_flat_tree']) == 0) {
                return false;
            }

            //Now fetch them all:
            $objects = $this->Database_model->fn___in_fetch(array(
                'in_id IN (' . join(',', $tree['in_flat_tree']) . ')' => null,
            ));

        } elseif (in_array($obj_type, array('en'))) {

            //TODO add entity support

        }

        //Apply relative changes to all objects:
        $affected_rows = 0;
        foreach ($objects as $obj) {
            //Make a relative adjustment compared to what is currently there:
            $affected_rows += $this->Matrix_model->fn___metadata_update($obj_type, $obj, $metadata_new, false);
        }

        //Return total affected rows:
        return $affected_rows;

    }





    function k_skip_recursive_down($tr_id, $update_db = true)
    {
        //TODO Readjust the removal of $tr_id, $in_id variables
        //User has requested to skip an intent starting from:
        $dwn_tree = $this->Matrix_model->k_recursive_fetch($tr_id, $in_id, true);
        $skip_ks = array_merge(array(intval($tr_id)), $dwn_tree['actionplan_ins_flat']);

        //Now see how many should we actually skip based on current status:
        $skippable_ks = $this->Database_model->fn___tr_fetch(array(
            'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
            'tr_id IN (' . join(',', $skip_ks) . ')' => null,
        ), ($update_db ? array() : array('cr', 'cr_c_child')), 0, 0, array('tr_order' => 'ASC'));

        if ($update_db) {

            //Now start skipping:
            foreach ($skippable_ks as $k) {
                $this->Matrix_model->fn___actionplan_update($k['tr_id'], -1); //skip
            }

        }

        //Returned intents:
        return $skippable_ks;

    }




    function k_recursive_fetch($tr_id, $in_id, $direction_is_downward, $parent_in = array(), $metadata_aggregate = null)
    {

        //Get core data:
        $metadata_this = array(
            'in_flat_tree' => array(),
            'in_flat_unique_published_tree' => array(),
            'in_links_flat_tree' => array(),
            'actionplan_ins_flat' => array(),
        );

        if (!$metadata_aggregate && !isset($parent_in['tr_id'])) {

            //First item:
            $metadata_aggregate = $metadata_this;
            $ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => $in_id,
            ));

        } else {

            //Recursive item:
            $ins = $this->Database_model->fn___tr_fetch(array(
                'tr_tr_parent_id' => $tr_id,
                'tr_id' => $parent_in['tr_id'],
            ), array(($direction_is_downward ? 'in_child' : 'in_parent')));

        }

        //We should have found an item by now:
        if (count($ins) < 1) {
            return false;
        }


        //Add the link relations before we start recursion so we can have the Tree in up-custom order:
        array_push($metadata_this['in_flat_tree'], intval($in_id));

        if($ins[0]['in_status'] >= 2 && !in_array(intval($in_id) , $metadata_this['in_flat_unique_published_tree'])){
            array_push($metadata_this['in_flat_unique_published_tree'], intval($in_id));
        }
        if (isset($ins[0]['tr_id'])) {
            array_push($metadata_this['in_links_flat_tree'], intval($ins[0]['tr_id']));
            array_push($metadata_this['actionplan_ins_flat'], intval($ins[0]['tr_id']));
        }


        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        $next_level_ins = $this->Database_model->fn___tr_fetch(array(
            'tr_tr_parent_id' => $tr_id,
            'in_status >=' => 2, //Published+
            ($direction_is_downward ? 'tr_in_parent_id' : 'tr_in_child_id') => $in_id,
        ), array(($direction_is_downward ? 'in_child' : 'in_parent')));


        if (count($next_level_ins) > 0) {
            foreach ($next_level_ins as $in) {

                //Fetch children for this intent, if any:
                $recursion = $this->Matrix_model->k_recursive_fetch($tr_id, $in['in_id'], $direction_is_downward, $in, $metadata_this);

                //return $recursion;

                if (!$recursion) {
                    //There was an infinity break
                    return false;
                }

                //Addup values:
                array_push($metadata_this['in_links_flat_tree'], $recursion['in_links_flat_tree']);
                array_push($metadata_this['actionplan_ins_flat'], $recursion['actionplan_ins_flat']);
                array_push($metadata_this['in_flat_unique_published_tree'], $recursion['in_flat_unique_published_tree']);
                array_push($metadata_this['in_flat_tree'], $recursion['in_flat_tree']);
            }
        }

        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($metadata_this['in_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['in_flat_tree'] = $result;

        //Flatten intent unique ID array:
        $result = array();
        array_walk_recursive($metadata_this['in_flat_unique_published_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['in_flat_unique_published_tree'] = $result;

        $result = array();
        array_walk_recursive($metadata_this['in_links_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['in_links_flat_tree'] = $result;

        $result = array();
        array_walk_recursive($metadata_this['actionplan_ins_flat'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['actionplan_ins_flat'] = $result;

        //Return data:
        return $metadata_this;
    }



    function fn___actionplan_choose_or($actionplan_tr_id, $in_parent_id, $in_answer_id)
    {

        /*
         *
         * Used when Students choose an OR Intent path in their Action Plan.
         * When a user chooses an answer to an ANY intent, this function
         * would mark that answer as complete while marking all siblings
         * as Removed/Skipped (tr_status = -1)
         *
         * Inputs:
         *
         * $actionplan_tr_id:   Action Plan ID
         *
         * $in_parent_id:       The OR Intent that one of its children need
         *                      to be selected by the Student
         *
         * $in_answer_id:       The selected child intent
         *
         * */

        $chosen_path = $this->Database_model->fn___tr_fetch(array(
            'tr_tr_parent_id' => $actionplan_tr_id,
            'tr_in_parent_id' => $in_parent_id,
            'tr_in_child_id' => $in_answer_id,
        ), array('in_parent'));


        if (count($chosen_path) < 0) {

            //Oooopsi, we could not find it! Log error and return false:
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'Unable to locate OR selection for this Action Plan',
                'tr_en_type_id' => 4246, //Platform Error
                'tr_tr_parent_id' => $actionplan_tr_id,
                'tr_in_parent_id' => $in_parent_id,
                'tr_in_child_id' => $in_answer_id,
            ));

            return false;
        }

        //Inform the user of any completion requirements:
        $message_in_requirements = $this->Matrix_model->fn___in_completion_requirements($in_answer_id, $actionplan_tr_id);

        //Now mark intent as complete (and this will SKIP all siblings) and move on:
        $this->Matrix_model->in_actionplan_complete_up($chosen_path[0], $chosen_path[0], ( $message_in_requirements ? 1 /* Working On */ : null ));

        //Successful:
        return true;
    }

    function fn___in_recursive_fetch($in_id, $direction_is_downward = false, $update_db_table = false, $actionplan = array(), $previous_in = array(), $metadata_aggregate = null)
    {

        /*
         *
         * The nature of the Intent tree is best suited for a recursive function
         * that will travel up/down and fetch all intent branches.
         *
         * Inputs:
         *
         * - $in_id:                    The point to get started in the tree
         *
         * - $direction_is_downward:    Whether to go up or down, sets the direction
         *
         * - $update_db_table:          Whether or not to update a copy of the
         *                              $metadata_this array into in_metadata
         *
         * - $actionplan:               The Action Plan object which if provided, will
         *                              get this function to create a copy of the intents
         *                              of $in_id downwards
         *
         * - $previous_in:              Keeps track of the state of recursion by passing
         *                              down/up the previous intent
         *
         * - $metadata_aggregate:       A variable used to addup the $metadata_this
         *                              values that will eventually be stored in in_metadata
         *
         * */

        
        //Do basic input validation:
        if($in_id < 1){
            //Invalid Intent ID:
            return false;
        } elseif(count($actionplan) > 0 && !$direction_is_downward){
            //Caching Action Plan intents only words in the downward direction:
            return false;
        }

        //Calculate metadata variables:
        $metadata_this = array(

            //Fetch for New+ intents:
            '___tree_active_count' => 0, //A count of all active (in_status >= 0) intents within the tree
            '___messages_count' => 0, //A count of all messages for this intent only
            'in_tree' => array(), //Fetches the intent tree with its full 2-dimensional & hierarchical beauty
            'in_flat_tree' => array(), //Puts all the tree's intent IDs in a flat array, useful for quick processing
            'in_flat_unique_published_tree' => array(), //Unique IDs
            'in_links_flat_tree' => array(), //Puts all the tree's intent transaction (intent link) IDs in a flat array, useful for quick processing

            //Fetched for Published+ Intents:
            '___tree_published_count' => 0, //A count of all published (in_status >= 2) intents within the tree
            '___tree_published_unique_count' => 0, //A count of all published (in_status >= 2) UNIQUE intents
            '___messages_tree_count' => 0, //A count of all messages for all tree intents that are published
            '___tree_min_seconds' => 0, //The minimum number of seconds required to complete tree
            '___tree_max_seconds' => 0, //The maximum number of seconds required to complete tree
            '___tree_min_cost' => 0, //The minimum cost of third-party product purchases recommended to complete tree
            '___tree_max_cost' => 0, //The maximum cost of third-party product purchases recommended to complete tree
            '___tree_experts' => array(), //Expert references across all contributions
            '___tree_miners' => array(), //miner references considering intent messages
            '___tree_contents' => array(), //Content types entity references on messages
            'metadatas_updated' => 0, //Keeps count of database metadata fields that were not in sync with the latest version of the cahced data
        );

        if (!$metadata_aggregate) {
            //First level, no aggregate yet, set $metadata_this as the starting point to then start aggregating:
            $metadata_aggregate = $metadata_this;
        }

        //Are we 1+ levels deep? If so, we'll have $previous_in set
        if (isset($previous_in['tr_id'])) {

            //Yes, so now we can fetch children:

            if ($direction_is_downward) {

                //Fetch children:
                $ins = $this->Database_model->fn___tr_fetch(array(
                    'tr_status >=' => 2, //Published+
                    'in_status >=' => 0, //New+
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_id' => $previous_in['tr_id'],
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC')); //Child intents must be ordered

            } else {

                //Fetch parents:
                $ins = $this->Database_model->fn___tr_fetch(array(
                    'tr_status >=' => 2, //Published+
                    'in_status >=' => 0, //New+
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                    'tr_id' => $previous_in['tr_id'],
                ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

            }

        } else {

            //This is the very first intent, fetch intention itself as we don't have any links yet:
            $ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => $in_id,
            ));

        }


        //We should have found an item by now:
        if (count($ins) < 1) {
            return false;
        }

        //Set the current intent:
        $this_in = $ins[0];


        //Always add intent to the flat intent tree which is part of the metadata:
        array_push($metadata_this['in_flat_tree'], intval($in_id));

        if($this_in['in_status'] >= 2 && !in_array(intval($in_id) , $metadata_this['in_flat_unique_published_tree'])){
            array_push($metadata_this['in_flat_unique_published_tree'], intval($in_id));
        }


        //Add the link relations before we start recursion so we can have the Tree in up-custom order:
        if (isset($this_in['tr_id'])) {

            //Add link to flat intent link tree:
            array_push($metadata_this['in_links_flat_tree'], intval($this_in['tr_id']));

            //Are we caching an Action Plan?
            if (count($actionplan) > 0) {

                //Yes we are, create a cache of this Intent link to be added to their Action Plan:
                $this->Database_model->fn___tr_create(array(
                    'tr_status' => 0, //New
                    'tr_en_type_id' => 4559, //Action Plan Intent
                    'tr_en_credit_id' => $actionplan['tr_en_parent_id'], //Credit goes to Student
                    'tr_en_parent_id' => $actionplan['tr_en_parent_id'], //Belongs to this Student
                    'tr_in_parent_id' => $this_in['tr_in_parent_id'],
                    'tr_in_child_id' => $this_in['tr_in_child_id'],
                    'tr_order' => $this_in['tr_order'],
                    'tr_tr_parent_id' => $actionplan['tr_id'], //Indicates the parent Action Plan Transaction ID
                ));

            }

        }

        //Terminate at OR branches for Action Plan caching
        if (count($actionplan) > 0 && intval($this_in['in_is_any'])) {
            /*
             *
             * We do this as we don't know which OR path will be
             * chosen by Student so no point in adding every branch
             * possible! We will then add a new Action Plan intent
             * every time an OR branch poth is chosen.
             *
             * */
            return false;
        }


        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        if ($direction_is_downward) {

            //Fetch children:
            $next_level_ins = $this->Database_model->fn___tr_fetch(array(
                'tr_in_parent_id' => $in_id,
                'tr_status >=' => 2, //Published+
                'in_status >=' => 0, //New+
                'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
            ), array('in_child'), 0, 0, array('tr_order' => 'ASC')); //Child intents must be ordered

        } else {

            //Fetch parents:
            $next_level_ins = $this->Database_model->fn___tr_fetch(array(
                'tr_in_child_id' => $in_id,
                'tr_status >=' => 2, //Published+
                'in_status >=' => 0, //New+
                'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
            ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

        }


        //Do we have any next level intents (up or down)?
        if (count($next_level_ins) > 0) {

            //$resource_estimates are determined based on the intent's AND/OR type:
            $resource_estimates = array(
                'in___tree_min_seconds' => null,
                'in___tree_max_seconds' => null,
                'in___tree_min_cost' => null,
                'in___tree_max_cost' => null,
            );

            foreach ($next_level_ins as $next_in) {

                if (in_array($next_in['in_id'], $metadata_aggregate['in_flat_tree'])) {

                    //Duplicate intent detected within tree
                    //terminate function to prevent an infinite loop:
                    return false;

                } else {

                    //Recursively fetch the next level (up or down):
                    $recursion = $this->Matrix_model->fn___in_recursive_fetch($next_in['in_id'], $direction_is_downward, $update_db_table, $actionplan, $next_in, $metadata_this);

                    if (!$recursion) {
                        //There was an infinity break
                        return false;
                    }

                    //Addup if any:
                    $metadata_this['___tree_active_count'] += $recursion['___tree_active_count'];
                    array_push($metadata_this['in_links_flat_tree'], $recursion['in_links_flat_tree']);
                    array_push($metadata_this['in_flat_tree'], $recursion['in_flat_tree']);
                    array_push($metadata_this['in_flat_unique_published_tree'], $recursion['in_flat_unique_published_tree']);
                    array_push($metadata_this['in_tree'], $recursion['in_tree']);

                    //Is this published?
                    if($next_in['in_status'] < 2){
                        continue;
                    }


                    $metadata_this['___tree_published_count'] += $recursion['___tree_published_count'];

                    //Do calculations based on intent type (AND or OR)
                    if ($this_in['in_is_any']) {
                        //OR Branch, figure out the logic:
                        if ($recursion['___tree_min_seconds'] < $resource_estimates['in___tree_min_seconds'] || is_null($resource_estimates['in___tree_min_seconds'])) {
                            $resource_estimates['in___tree_min_seconds'] = $recursion['___tree_min_seconds'];
                        }
                        if ($recursion['___tree_max_seconds'] > $resource_estimates['in___tree_max_seconds'] || is_null($resource_estimates['in___tree_max_seconds'])) {
                            $resource_estimates['in___tree_max_seconds'] = $recursion['___tree_max_seconds'];
                        }
                        if ($recursion['___tree_min_cost'] < $resource_estimates['in___tree_min_cost'] || is_null($resource_estimates['in___tree_min_cost'])) {
                            $resource_estimates['in___tree_min_cost'] = $recursion['___tree_min_cost'];
                        }
                        if ($recursion['___tree_max_cost'] > $resource_estimates['in___tree_max_cost'] || is_null($resource_estimates['in___tree_max_cost'])) {
                            $resource_estimates['in___tree_max_cost'] = $recursion['___tree_max_cost'];
                        }
                    } else {
                        //AND Branch, add them all up:
                        $resource_estimates['in___tree_min_seconds'] += intval($recursion['___tree_min_seconds']);
                        $resource_estimates['in___tree_max_seconds'] += intval($recursion['___tree_max_seconds']);
                        $resource_estimates['in___tree_min_cost'] += number_format($recursion['___tree_min_cost'], 2);
                        $resource_estimates['in___tree_max_cost'] += number_format($recursion['___tree_max_cost'], 2);
                    }


                    if ($update_db_table) {

                        //Update DB requested:
                        $metadata_this['___messages_tree_count'] += $recursion['___messages_tree_count'];
                        $metadata_this['metadatas_updated'] += $recursion['metadatas_updated'];

                        //Addup unique experts:
                        foreach ($recursion['___tree_experts'] as $en_id => $tex) {
                            //Is this a new expert?
                            if (!isset($metadata_this['___tree_experts'][$en_id])) {
                                //Yes, add them to the list:
                                $metadata_this['___tree_experts'][$en_id] = $tex;
                            }
                        }

                        //Addup unique miners:
                        foreach ($recursion['___tree_miners'] as $en_id => $tet) {
                            //Is this a new expert?
                            if (!isset($metadata_this['___tree_miners'][$en_id])) {
                                //Yes, add them to the list:
                                $metadata_this['___tree_miners'][$en_id] = $tet;
                            }
                        }

                        //Addup content types:
                        foreach ($recursion['___tree_contents'] as $type_en_id => $current_us) {
                            foreach ($current_us as $en_id => $u_obj) {
                                if (!isset($metadata_this['___tree_contents'][$type_en_id][$en_id])) {
                                    //Yes, add them to the list:
                                    $metadata_this['___tree_contents'][$type_en_id][$en_id] = $u_obj;
                                }
                            }
                        }
                    }
                }
            }

            //Addup the totals from this tree:
            $metadata_this['___tree_min_seconds'] += $resource_estimates['in___tree_min_seconds'];
            $metadata_this['___tree_max_seconds'] += $resource_estimates['in___tree_max_seconds'];
            $metadata_this['___tree_min_cost'] += $resource_estimates['in___tree_min_cost'];
            $metadata_this['___tree_max_cost'] += $resource_estimates['in___tree_max_cost'];
        }


        //Increase metadata counters for this intent:
        $metadata_this['___tree_active_count']++;
        $this_in['___tree_active_count'] = $metadata_this['___tree_active_count'];


        //Is this a published intent?
        if($this_in['in_status'] >= 2){
            $metadata_this['___tree_published_count']++;
        }

        $this_in['___tree_published_count'] = $metadata_this['___tree_published_count'];

        $metadata_this['___tree_min_seconds'] += intval($this_in['in_seconds']);
        $metadata_this['___tree_max_seconds'] += intval($this_in['in_seconds']);
        $metadata_this['___tree_min_cost'] += number_format($this_in['in_usd'], 2);
        $metadata_this['___tree_max_cost'] += number_format($this_in['in_usd'], 2);

        //Set the data for this intent:
        $this_in['___tree_min_seconds'] = $metadata_this['___tree_min_seconds'];
        $this_in['___tree_max_seconds'] = $metadata_this['___tree_max_seconds'];
        $this_in['___tree_min_cost'] = $metadata_this['___tree_min_cost'];
        $this_in['___tree_max_cost'] = $metadata_this['___tree_max_cost'];


        //Count messages only if DB updating:
        if ($update_db_table) {

            $this_in['___tree_experts'] = array();
            $this_in['___tree_miners'] = array();
            $this_in['___tree_contents'] = array();

            //Fetch intent messages to see who is involved:
            $in__messages = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 0, //New+
                'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4485')) . ')' => null, //All Intent messages
                'tr_in_child_id' => $this_in['in_id'],
            ), array('en_credit'), 0, 0, array('tr_order' => 'ASC'));

            $this_in['___messages_count'] = count($in__messages);
            $metadata_this['___messages_tree_count'] += $this_in['___messages_count'];
            $this_in['___messages_tree_count'] = $metadata_this['___messages_tree_count'];


            $parent_ids = array();

            if($this_in['in_status'] >= 2){
                foreach ($in__messages as $tr) {

                    //Who are the Miners of this message?
                    if (!in_array($tr['tr_en_credit_id'], $parent_ids)) {
                        array_push($parent_ids, $tr['tr_en_credit_id']);
                    }

                    //Check the Miners of this message in the miner array:
                    if (!isset($this_in['___tree_miners'][$tr['tr_en_credit_id']])) {
                        //Add the entire message which would also hold the miner details:
                        $this_in['___tree_miners'][$tr['tr_en_credit_id']] = $tr;
                    }
                    //How about the parent of this one?
                    if (!isset($metadata_this['___tree_miners'][$tr['tr_en_credit_id']])) {
                        //Yes, add them to the list:
                        $metadata_this['___tree_miners'][$tr['tr_en_credit_id']] = $tr;
                    }


                    //Does this message have any entity references?
                    if ($tr['tr_en_parent_id'] > 0) {

                        //Add the reference it self:
                        if (!in_array($tr['tr_en_parent_id'], $parent_ids)) {
                            array_push($parent_ids, $tr['tr_en_parent_id']);
                        }

                        //Yes! Let's see if any of the parents/creators are industry experts:
                        $ens = $this->Database_model->fn___en_fetch(array(
                            'en_id' => $tr['tr_en_parent_id'],
                        ), array('en__parents'));

                        if (isset($ens[0]) && count($ens[0]['en__parents']) > 0) {
                            //We found it, let's loop through the parents and aggregate their IDs for a single search:
                            foreach ($ens[0]['en__parents'] as $en) {

                                //We only accept published parent entities:
                                if($en['en_status'] < 2){
                                    //Not yet ready:
                                    continue;
                                }

                                //Is this a particular content type?
                                if (in_array($en['en_id'], $this->config->item('en_ids_3000'))) {
                                    //yes! Add it to the list if it does not already exist:
                                    if (!isset($this_in['___tree_contents'][$en['en_id']][$ens[0]['en_id']])) {
                                        $this_in['___tree_contents'][$en['en_id']][$ens[0]['en_id']] = $ens[0];
                                    }

                                    //How about the parent tree?
                                    if (!isset($metadata_this['___tree_contents'][$en['en_id']][$ens[0]['en_id']])) {
                                        $metadata_this['___tree_contents'][$en['en_id']][$ens[0]['en_id']] = $ens[0];
                                    }
                                }

                                if (!in_array($en['en_id'], $parent_ids)) {
                                    array_push($parent_ids, $en['en_id']);
                                }
                            }
                        }
                    }
                }
            }


            //Who was involved in mining this content?
            if (count($parent_ids) > 0) {

                //Lets make a query search to see how many of those involved are industry experts:
                $expert_ens = $this->Database_model->fn___tr_fetch(array(
                    'tr_en_parent_id' => 3084, //Industry expert entity
                    'tr_en_child_id IN (' . join(',', $parent_ids) . ')' => null,
                    'tr_status >=' => 2, //Published+
                ), array('en_child'));

                //Put unique IDs in array key for faster searching:
                foreach ($expert_ens as $en) {
                    if (!isset($this_in['___tree_experts'][$en['en_id']])) {
                        $this_in['___tree_experts'][$en['en_id']] = $en;
                    }
                }
            }


            //Did we find any new industry experts?
            if (count($this_in['___tree_experts']) > 0) {

                //Yes, lets add them uniquely to the mother array assuming they are not already there:
                foreach ($this_in['___tree_experts'] as $new_ixs) {
                    //Is this a new expert?
                    if (!isset($metadata_this['___tree_experts'][$new_ixs['en_id']])) {
                        //Yes, add them to the list:
                        $metadata_this['___tree_experts'][$new_ixs['en_id']] = $new_ixs;
                    }
                }
            }
        }

        array_push($metadata_this['in_tree'], $this_in);



        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($metadata_this['in_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['in_flat_tree'] = $result;


        $result = array();
        array_walk_recursive($metadata_this['in_flat_unique_published_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['in_flat_unique_published_tree'] = $result;



        $result = array();
        array_walk_recursive($metadata_this['in_links_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $metadata_this['in_links_flat_tree'] = $result;




        if ($update_db_table) {

            //Assign aggregates:
            $this_in['___tree_experts'] = $metadata_this['___tree_experts'];
            $this_in['___tree_miners'] = $metadata_this['___tree_miners'];
            $this_in['___tree_contents'] = $metadata_this['___tree_contents'];

            //Start sorting:
            if (is_array($this_in['___tree_experts']) && count($this_in['___tree_experts']) > 0) {
                usort($this_in['___tree_experts'], 'fn___sortByScore');
            }
            if (is_array($this_in['___tree_miners']) && count($this_in['___tree_miners']) > 0) {
                usort($this_in['___tree_miners'], 'fn___sortByScore');
            }
            foreach ($this_in['___tree_contents'] as $type_en_id => $current_us) {
                if (isset($this_in['___tree_contents'][$type_en_id]) && count($this_in['___tree_contents'][$type_en_id]) > 0) {
                    usort($this_in['___tree_contents'][$type_en_id], 'fn___sortByScore');
                }
            }

            //Update DB only if any of these metadata fields have changed:
            $metadata = unserialize($this_in['in_metadata']);
            if (!(
                intval($this_in['___tree_min_seconds']) == intval(@$metadata['in__tree_min_seconds']) &&
                intval($this_in['___tree_max_seconds']) == intval(@$metadata['in__tree_max_seconds']) &&
                number_format($this_in['___tree_min_cost'], 2) == number_format(@$metadata['in__tree_min_cost'], 2) &&
                number_format($this_in['___tree_max_cost'], 2) == number_format(@$metadata['in__tree_max_cost'], 2) &&
                ((!@$metadata['in__tree_experts'] && count($this_in['___tree_experts']) < 1) || (serialize($this_in['___tree_experts']) == @$metadata['in__tree_experts'])) &&
                ((!@$metadata['in__tree_miners'] && count($this_in['___tree_miners']) < 1) || (serialize($this_in['___tree_miners']) == @$metadata['in__tree_miners'])) &&
                ((!@$metadata['in__tree_contents'] && count($this_in['___tree_contents']) < 1) || (serialize($this_in['___tree_contents']) == @$metadata['in__tree_contents'])) &&
                $this_in['___tree_active_count'] == @$metadata['in__tree_in_active_count'] &&
                $this_in['___tree_published_count'] == @$metadata['in__tree_in_published_count'] &&
                $this_in['___messages_count'] == @$metadata['in__message_count'] &&
                $this_in['___messages_tree_count'] == @$metadata['in__message_tree_count']
            )) {

                //Something was not up to date, let's update:
                if ($this->Matrix_model->fn___metadata_update('in', $this_in, array(

                    'in__tree_min_seconds' => intval($this_in['___tree_min_seconds']),
                    'in__tree_max_seconds' => intval($this_in['___tree_max_seconds']),

                    'in__tree_min_cost' => number_format($this_in['___tree_min_cost'], 2),
                    'in__tree_max_cost' => number_format($this_in['___tree_max_cost'], 2),

                    'in__tree_in_active_count' => $this_in['___tree_active_count'],
                    'in__tree_in_published_count' => $this_in['___tree_published_count'],
                    'in__flat_unique_published_count' => count(array_unique($metadata_this['in_flat_unique_published_tree'])),
                    'in__message_count' => $this_in['___messages_count'],
                    'in__message_tree_count' => $this_in['___messages_tree_count'],
                    'in__tree_experts' => $this_in['___tree_experts'],
                    'in__tree_miners' => $this_in['___tree_miners'],
                    'in__tree_contents' => $this_in['___tree_contents'],
                ))) {
                    //Yes update was successful:
                    $metadata_this['metadatas_updated']++;
                }
            }
        }



        //Return data:
        return $metadata_this;
    }


    function fn___metadata_update($obj_type, $obj, $new_fields, $absolute_adjustment = true)
    {

        /*
         *
         * Enables the easy manipulation of the text metadata field which holds cache data for developers
         *
         * $obj_type:               Either in, en or tr
         *
         * $obj:                    The Entity, Intent or Transaction itself.
         *                          We're looking for the $obj ID and METADATA
         *
         * $new_fields:             The new array of metadata fields to be Set,
         *                          Updated or Removed (If set to null)
         *
         * $absolute_adjustment:    TRUE by default, meaning that values within
         *                          $new_fields will be updated as they are. If
         *                          this is FALSE, then this would be a relative
         *                          adjustment (add or subtract) compared to what
         *                          is already in the metadata field of $obj.
         *
         * */

        if (!array_key_exists($obj_type, $this->config->item('core_objects')) || !isset($obj[$obj_type . '_id']) || count($new_fields) < 1) {
            return false;
        }

        //Do we have a metadata?
        if(!isset($obj[$obj_type . '_metadata'])){
            $obj[$obj_type . '_metadata'] = null;
        }

        //Prepare metadata:
        if(strlen($obj[$obj_type . '_metadata']) > 0){
            $metadata = unserialize($obj[$obj_type . '_metadata']);
        } else {
            $metadata = array();
        }

        //Go through all the new fields and see if they differ from current metadata fields:
        foreach ($new_fields as $metadata_key => $metadata_value) {
            if (!$absolute_adjustment) {
                //We need to do a relative adjustment:
                $metadata[$metadata_key] = (isset($metadata[$metadata_key]) ? $metadata[$metadata_key] : 0) + $metadata_value;
            } else {

                //We are doing an absolute adjustment if needed:
                if (is_null($metadata_value)) {
                    //User asked to remove this value:
                    if(isset($metadata[$metadata_key])){
                        unset($metadata[$metadata_key]);
                    }
                } elseif (!isset($metadata[$metadata_key]) || $metadata[$metadata_key] !== $metadata_value) {
                    //Value has changed, adjust:
                    $metadata[$metadata_key] = $metadata_value;
                }
            }
        }

        //Now update DB without logging any transactions as this is considered a back-end update:
        if ($obj_type == 'in') {

            $affected_rows = $this->Database_model->fn___in_update($obj['in_id'], array(
                'in_metadata' => $metadata,
            ));

        } elseif ($obj_type == 'en') {

            $affected_rows = $this->Database_model->fn___en_update($obj['en_id'], array(
                'en_metadata' => $metadata,
            ));

        } elseif ($obj_type == 'tr') {

            $affected_rows = $this->Database_model->fn___tr_update($obj['tr_id'], array(
                'tr_metadata' => $metadata,
            ));

        }

        //Should be all good:
        return $affected_rows;

    }


    function fn___en_search_match($en_parent_id, $value)
    {

        //Is this a timezone? We need to adjust the timezone according to our limited timezone entities
        if ($en_parent_id == 3289) {
            $valid_halfs = array(-4, -3, 3, 4, 9); //These are timezones with half values so far
            $decimal = fmod(doubleval($value), 1);
            if (!($decimal == 0)) {
                $whole = intval(str_replace('.' . $decimal, '', $value));
                if (in_array(intval($whole), $valid_halfs)) {
                    $value = $whole + ($whole < 0 ? -0.5 : +0.5);
                } else {
                    $value = round(doubleval($value));
                }
            }
        }


        //Search and see if we can find $value in the transaction content:
        $matching_entities = $this->Database_model->fn___tr_fetch(array(
            'tr_en_parent_id' => $en_parent_id,
            'tr_en_child_id > ' => 0,
            'tr_content' => $value,
            'tr_status >=' => 0, //Pending or Active
        ), array(), 0);


        if (count($matching_entities) == 1) {

            //Bingo, return result:
            return intval($matching_entities[0]['tr_en_child_id']);

        } else {

            //Ooooopsi, this value did not exist! Notify the admin so we can look into this:
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'fn___en_search_match() found [' . count($matching_entities) . '] results as the children of en_id=[' . $en_parent_id . '] that had the value of [' . $value . '].',
                'tr_en_type_id' => 4246, //Platform Error
                'tr_en_child_id' => $en_parent_id,
            ));

            return 0;
        }
    }


    function in_actionplan_complete_up($cr, $w, $force_tr_status = null)
    {

        //Check if parent of this item is not started, because if not, we need to mark that as Working On:
        $parent_ks = $this->Database_model->fn___tr_fetch(array(
            'tr_tr_parent_id' => $w['tr_id'],
            'tr_status' => 0, //skip intents that are not stared or working on...
            'tr_in_child_id' => $cr['tr_in_parent_id'],
        ), array('cr'));
        if (count($parent_ks) == 1) {
            //Update status (It might not work if it was working on AND new tr_status=1)
            $this->Matrix_model->fn___actionplan_update($parent_ks[0]['tr_id'], 1);
        }

        //See if current intent children are complete...
        //We'll assume complete unless proven otherwise:
        $down_is_complete = true;
        $total_skipped = 0;
        //Is this an OR branch? Because if it is, we need to skip its siblings:
        if (intval($cr['in_is_any'])) {
            //Skip all eligible siblings, if any:
            //$cr['tr_in_child_id'] is the chosen path that we're trying to find its siblings for the parent $cr['tr_in_parent_id']

            //First search for other options that need to be skipped because of this selection:
            $none_chosen_paths = $this->Database_model->fn___tr_fetch(array(
                'tr_tr_parent_id' => $w['tr_id'],
                'tr_in_parent_id' => $cr['tr_in_parent_id'], //Fetch children of parent intent which are the siblings of current intent
                'tr_in_child_id !=' => $cr['tr_in_child_id'], //NOT The answer (we need its siblings)
                'in_status >=' => 2,
                'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
            ), array('w', 'cr', 'cr_c_child'));

            //This is the none chosen answers, if any:
            foreach ($none_chosen_paths as $k) {
                //Skip this intent:
                $total_skipped += count($this->Matrix_model->k_skip_recursive_down($k['tr_id']));
            }
        }


        if (!$force_tr_status) {
            //Regardless of Branch type, we need all children to be complete if we are to mark this as complete...
            //If not, we will mark is as working on...
            //So lets fetch the down tree and see Whatssup:
            $dwn_tree = $this->Matrix_model->k_recursive_fetch($w['tr_id'], $cr['tr_in_child_id'], true);

            //Does it have OUTs?
            if (count($dwn_tree['actionplan_ins_flat']) > 0) {
                //We do have down, let's check their status:
                $dwn_incomplete_ks = $this->Database_model->fn___tr_fetch(array(
                    'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
                    'tr_id IN (' . join(',', $dwn_tree['actionplan_ins_flat']) . ')' => null, //All OUT links
                ), array('cr'));
                if (count($dwn_incomplete_ks) > 0) {
                    //We do have some incomplete children, so this is not complete:
                    $down_is_complete = false;
                }
            }
        }


        //Ok now define the new status here:
        $new_tr_status = (!is_null($force_tr_status) ? $force_tr_status : ($down_is_complete ? 2 : 1));

        //Update this intent:
        $this->Matrix_model->fn___actionplan_update($cr['tr_id'], $new_tr_status);


        //We are done with this branch if the status is any of the following:
        if (!in_array($new_tr_status, $this->config->item('tr_status_incomplete'))) {

            //Since down tree is now complete, see if up tree needs completion as well:
            //Fetch all parents:
            $up_tree = $this->Matrix_model->k_recursive_fetch($w['tr_id'], $cr['tr_in_child_id'], false);

            //Now loop through each level and see whatssup:
            foreach ($up_tree['actionplan_ins_flat'] as $parent_tr_id) {

                //Fetch details to see whatssup:
                $parent_ks = $this->Database_model->fn___tr_fetch(array(
                    'tr_id' => $parent_tr_id,
                    'tr_tr_parent_id' => $w['tr_id'],
                    'in_status >=' => 2,
                    'tr_status <' => 2, //Not completed in any way
                ), array('cr', 'cr_c_child'));

                if (count($parent_ks) == 1) {

                    //We did find an incomplete parent, let's see if its now completed:
                    //Assume complete unless proven otherwise:
                    $is_complete = true;

                    //Any intents would always be complete since we already marked one of its children as complete!
                    //If it's an ALL intent, we need to check to make sure all children are complete:
                    if (intval($parent_ks[0]['in_is_any'])) {
                        //We need a single immediate child to be complete:
                        $complete_child_cs = $this->Database_model->fn___tr_fetch(array(
                            'tr_tr_parent_id' => $w['tr_id'],
                            'tr_status NOT IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //complete
                            'tr_in_parent_id' => $parent_ks[0]['tr_in_child_id'],
                        ), array('cr'));
                        if (count($complete_child_cs) == 0) {
                            $is_complete = false;
                        }
                    } else {
                        //We need all immediate children to be complete (i.e. No incomplete)
                        $incomplete_child_cs = $this->Database_model->fn___tr_fetch(array(
                            'tr_tr_parent_id' => $w['tr_id'],
                            'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
                            'tr_in_parent_id' => $parent_ks[0]['tr_in_child_id'],
                        ), array('cr'));
                        if (count($incomplete_child_cs) > 0) {
                            $is_complete = false;
                        }
                    }

                    if ($is_complete) {

                        //Update this:
                        $this->Matrix_model->fn___actionplan_update($parent_ks[0]['tr_id'], (!is_null($force_tr_status) ? $force_tr_status : 2));

                    } elseif ($parent_ks[0]['tr_status'] == 0) {

                        //Status is not started, let's set to started:
                        $this->Matrix_model->fn___actionplan_update($parent_ks[0]['tr_id'], 1); //Working On

                    }
                }
            }
        }
    }


    function fn___en_messenger_add($psid)
    {

        /*
         *
         * This function will attempt to create a new Student Entity
         * Using the PSID provided by Facebook Graph API
         *
         * */

        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'fn___en_messenger_add() got called without a valid Facebook $psid variable',
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }


        //Call facebook messenger API and get user graph profile:
        $graph_fetch = $this->Chat_model->fn___facebook_graph('GET', '/' . $psid, array());


        //Did we find the profile from FB?
        if (!$graph_fetch['status'] || !isset($graph_fetch['tr_metadata']['result']['first_name']) || strlen($graph_fetch['tr_metadata']['result']['first_name']) < 1) {

            /*
             *
             * No profile on Facebook! This happens when user has logged
             * into messenger with their phone number or for any reason
             * that Facebook does not provide profile details.
             *
             * */


            //We will create this master with a random & temporary name:
            $en = $this->Database_model->fn___en_create(array(
                'en_name' => 'Student ' . rand(100000000, 999999999),
                'en_psid' => $psid,
                'en_status' => 3, //Claimed
            ), true);

            //Inform the master:
            $this->Chat_model->fn___dispatch_message(
                'Hi stranger! Let\'s get started by completing your profile information by opening the My Account tab in the menu below. /link:Open 👤My Account:https://mench.com/master/account',
                $en,
                true
            );

        } else {

            //We did find the profile, move ahead:
            $fb_profile = $graph_fetch['tr_metadata']['result'];

            //Create Student with their Facebook Graph name:
            $en = $this->Database_model->fn___en_create(array(
                'en_name' => $fb_profile['first_name'] . ' ' . $fb_profile['last_name'],
                'en_psid' => $psid,
                'en_status' => 3, //Claimed
            ), true);

            //Split locale variable into language and country like "EN_GB" for English in England
            $locale = explode('_', $fb_profile['locale'], 2);

            //Try to match Facebook profile data to internal entities and create links for the ones we find:
            foreach (array(
                 $this->Matrix_model->fn___en_search_match(3289, $fb_profile['timezone']), //Timezone
                 $this->Matrix_model->fn___en_search_match(3290, strtolower(substr($fb_profile['gender'], 0, 1))), //Gender either m/f
                 $this->Matrix_model->fn___en_search_match(3287, strtolower($locale[0])), //Language
                 $this->Matrix_model->fn___en_search_match(3089, strtolower($locale[1])), //Country
             ) as $tr_en_parent_id) {

                //Did we find a relation? Create the transaction:
                if ($tr_en_parent_id > 0) {

                    //Create new transaction:
                    $this->Database_model->fn___tr_create(array(
                        'tr_en_type_id' => 4230, //Naked link
                        'tr_en_credit_id' => $en['en_id'], //Student gets credit as they added themselves
                        'tr_en_parent_id' => $tr_en_parent_id,
                        'tr_en_child_id' => $en['en_id'],
                    ));

                }
            }

            //Create transaction to save profile picture:
            $this->Database_model->fn___tr_create(array(
                'tr_status' => 0, //New
                'tr_en_type_id' => 4299, //Save URL to Mench Cloud
                'tr_en_credit_id' => $en['en_id'], //The Student who added this
                'tr_en_parent_id' => 4260, //Indicates URL file Type (Image)
                'tr_content' => $fb_profile['profile_pic'], //Image to be saved
            ));

        }

        //Note that new entity transaction is already logged via fn___en_create()
        //Now create more relevant transactions:

        //Log new Student transaction:
        $this->Database_model->fn___tr_create(array(
            'tr_en_type_id' => 4265, //New Student Joined
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_child_id' => $en['en_id'],
            'tr_metadata' => $en,
        ));

        //Add default Notification Level:
        $this->Database_model->fn___tr_create(array(
            'tr_en_type_id' => 4230, //Naked link
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_parent_id' => 4456, //Receive Regular Notifications (Student can change later on...)
            'tr_en_child_id' => $en['en_id'],
        ));

        //Add them to Students group:
        $this->Database_model->fn___tr_create(array(
            'tr_en_type_id' => 4230, //Naked link
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_parent_id' => 4430, //Mench Student
            'tr_en_child_id' => $en['en_id'],
        ));

        //Add them to People group:
        $this->Database_model->fn___tr_create(array(
            'tr_en_type_id' => 4230, //Naked link
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_parent_id' => 1278, //People
            'tr_en_child_id' => $en['en_id'],
        ));

        //Return entity object:
        return $en;

    }


    function fn___in_link_or_create($in_parent_id, $in_outcome, $in_link_child_id, $next_level, $tr_en_credit_id)
    {

        /*
         *
         * The main intent creation function that would create
         * appropriate links and return the intent view.
         *
         * Either creates an intent link between $in_parent_id & $in_link_child_id
         * (IF $in_link_child_id>0) OR will create a new intent with outcome $in_outcome
         * and link it to $in_parent_id (In this case $in_link_child_id will be 0)
         *
         * p.s. Inputs have already been validated via intents/fn___in_link_or_create() function
         *
         * */
        
        //Validate Original intent:
        $parent_ins = $this->Database_model->fn___in_fetch(array(
            'in_id' => intval($in_parent_id),
        ), array('in__children')); //We'd need either Children (If adding in Level 2) or nothing (If adding in Level 3), so 'in__children' would be enough

        if (count($parent_ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        }

        if (intval($in_link_child_id) > 0) {

            //We are linking to $in_link_child_id, We are NOT creating any new intents...

            //Fetch more details on the child intent we're about to link:
            $ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => $in_link_child_id,
                'in_status >=' => 0,
            ), ($next_level == 2 ? array('in__children') : array()));

            if (count($ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Intent ID',
                );
            }

            //All good so far, continue with linking:
            $child_in = $ins[0];

            //check all parents as this intent cannot be duplicated with any of its parents:
            $parent_tree = $this->Matrix_model->fn___in_recursive_fetch($in_parent_id);
            if (in_array($child_in['in_id'], $parent_tree['in_flat_tree'])) {
                return array(
                    'status' => 0,
                    'message' => 'You cannot link to "' . $child_in['in_outcome'] . '" as it already belongs to the parent/grandparent tree.',
                );
            }

            //Make sure this is not a duplicate intent for its parent:
            $dup_links = $this->Database_model->fn___tr_fetch(array(
                'tr_in_parent_id' => $in_parent_id,
                'tr_in_child_id' => $in_link_child_id,
                'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                'tr_status >=' => 0, //New+
            ));

            //Check for issues:
            if (count($dup_links) > 0) {

                //Ooopsi, this is a duplicate!
                return array(
                    'status' => 0,
                    'message' => '[' . $child_in['in_outcome'] . '] is already linked here.',
                );

            } elseif ($in_link_child_id == $in_parent_id) {

                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $child_in['in_outcome'] . '" as its own child.',
                );

            }

            //Prepare recursive update:
            $metadata = unserialize($child_in['in_metadata']);
            //Fetch and adjust the intent tree based on these values:
            $in_metadata_modify = array(
                'in__tree_in_active_count' => ( isset($metadata['in__tree_in_active_count']) ? intval($metadata['in__tree_in_active_count']) : 0 ),
                'in__tree_max_seconds' => ( isset($metadata['in__tree_max_seconds']) ? intval($metadata['in__tree_max_seconds']) : 0 ),
                'in__message_tree_count' => ( isset($metadata['in__message_tree_count']) ? intval($metadata['in__message_tree_count']) : 0 ),
            );

        } else {

            //We are NOT linking to an existing intent, but instead, we're creating a new intent:

            //Prepare recursive update:
            $in_metadata_modify = array(
                'in__tree_in_active_count' => 1, //We just added 1 new intent to this tree
            );

            //Create child intent:
            $child_in = $this->Database_model->fn___in_create(array(
                'in_status' => 1, //Working On
                'in_outcome' => trim($in_outcome),
                'in_metadata' => $in_metadata_modify,
            ), true, $tr_en_credit_id);

            //Sync the metadata of this new intent:
            $this->Matrix_model->fn___in_recursive_fetch($child_in['in_id'], true, true);
            
        }


        //Create Intent Link:
        $relation = $this->Database_model->fn___tr_create(array(
            'tr_en_credit_id' => $tr_en_credit_id,
            'tr_en_type_id' => 4228,
            'tr_in_parent_id' => $in_parent_id,
            'tr_in_child_id' => $child_in['in_id'],
            'tr_order' => 1 + $this->Database_model->fn___tr_max_order(array(
                'tr_status >=' => 0,
                'tr_en_type_id' => 4228,
                'tr_in_parent_id' => $in_parent_id,
            )),
        ), true);


        //Update Metadata for tree:
        $this->Matrix_model->fn___metadata_tree_update('in', $in_parent_id, $in_metadata_modify);


        //Fetch and return full data to be properly shown on the UI using the fn___echo_in() function
        $new_ins = $this->Database_model->fn___tr_fetch(array(
            'tr_in_parent_id' => $in_parent_id,
            'tr_in_child_id' => $child_in['in_id'],
            'tr_en_type_id IN (' . join(',', $this->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
            'tr_status >=' => 0,
            'in_status >=' => 0,
        ), array('in_child'), 1); //We did a limit to 1, but this should return 1 anyways since it's a specific/unique relation


        //Return result:
        return array(
            'status' => 1,
            'in_child_id' => $child_in['in_id'],
            'in_child_html' => fn___echo_in($new_ins[0], $next_level, $in_parent_id),
            //Also append some tree data for UI modifications via JS functions:
            'in__tree_max_seconds' => ( isset($in_metadata_modify['in__tree_max_seconds']) ? intval($in_metadata_modify['in__tree_max_seconds']) : 0 ), //Seconds added because of this
            'in__tree_in_active_count' => intval($in_metadata_modify['in__tree_in_active_count']), //We must have this (Either if we're linking OR creating) to show new intents in the tree
        );

    }

}