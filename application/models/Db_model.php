<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Db_model extends CI_Model
{

    //This model handles all DB calls from our local database.

    function __construct()
    {
        parent::__construct();
    }


    function en_radio_set($en_parent_bucket_id, $set_en_child_id = 0, $en_master_id, $tr_en_credit_id = 0)
    {

        /*
         * Treats an entity child group as a drop down menu where:
         *
         *  $en_parent_bucket_id is the parent of the drop down
         *  $en_master_id is the master entity ID that one of the children of $en_parent_bucket_id should be assigned (like a drop down)
         *  $set_en_child_id is the new value to be assigned, which could also be null (meaning just remove all current values)
         *
         * This function is helpful to manage things like Master communication levels
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
        foreach ($this->Db_model->tr_fetch(array(
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
                $this->Db_model->tr_update($tr['tr_id'], array(
                    'tr_status' => ($set_en_child_id > 0 ? -2 /* Being Updated */ : -1 /* Being Removed */), //Updated or Removed
                ));
            }

        }


        //Make sure $set_en_child_id belongs to parent if set (Could be null which means remove all)
        if (!$already_assigned) {
            //Let's go ahead and add desired entity as parent:
            $this->Db_model->tr_create(array(
                'tr_en_credit_id' => $tr_en_credit_id,
                'tr_en_child_id' => $en_master_id,
                'tr_en_parent_id' => $set_en_child_id,
                'tr_en_type_id' => 4230, //Naked link
                'tr_tr_parent_id' => $updated_tr_id,
            ));
        }

    }


    function w_update($id, $update_columns)
    {
        $this->db->where('tr_id', $id);
        $this->db->update('tb_actionplans', $update_columns);
        return $this->db->affected_rows();
    }


    function k_next_fetch($tr_id, $min_k_rank = 0)
    {

        //Two things need to be fetched:
        $last_working_on_any = $this->Db_model->tr_fetch(array(
            'tr_id' => $tr_id,
            'tr_status' => 1, //Active subscriptions
            'in_status >=' => 2,
            'k_rank >' => $min_k_rank,
            //The first case is for OR intents that a child is not yet selected, and the second part is for regular incompleted items:
            '(tr_status IN (1,-2) AND in_is_any=1)' => null, //Not completed or not yet started
        ), array('w', 'cr', 'cr_c_child'), 1, 0, array(
            'k_rank' => 'DESC',
        ));

        //We did not find it? Ok fetch the first one and replace:
        $first_pending_all = $this->Db_model->tr_fetch(array(
            'tr_id' => $tr_id,
            'tr_status' => 1, //Active subscriptions
            'in_status >=' => 2,
            'k_rank >' => $min_k_rank,
            //The first case is for OR intents that a child is not yet selected, and the second part is for regular incompleted items:
            'tr_status IN (0,-2)' => null, //Not completed or not yet started
        ), array('w', 'cr', 'cr_c_child'), 1, 0, array(
            'k_rank' => 'ASC', //Items are cached in order ;)
        ));

        if (isset($first_pending_all[0]) && (!isset($last_working_on_any[0]) || $first_pending_all[0]['k_rank'] < $last_working_on_any[0]['k_rank'])) {
            return $first_pending_all;
        } elseif (isset($last_working_on_any[0])) {
            return $last_working_on_any;
        } else {
            //Neither case was found!
            return false;
        }
    }


    function tr_status_update($tr_id, $new_tr_status)
    {

        //Marks a single subscription intent as complete:
        $this->Db_model->tr_update($tr_id, array(
            'k_last_updated' => date("Y-m-d H:i:s"),
            'tr_status' => $new_tr_status, //Working On...
        ));

        if ($new_tr_status == 2) {

            //It's complete!
            //Fetch full $k object
            $trs = $this->Db_model->tr_fetch(array(
                'tr_id' => $tr_id,
            ), array('w', 'cr'));
            if (count($trs) == 0) {
                return false;
            }

            //Dispatch all on-complete messages of $in_id
            $messages = $this->Db_model->i_fetch(array(
                'tr_in_child_id' => $trs[0]['tr_in_child_id'],
                'tr_status' => 3, //On complete messages
            ));
            if (count($messages) > 0) {
                $send_messages = array();
                foreach ($messages as $i) {
                    array_push($send_messages, array_merge($i, array(
                        'tr_tr_parent_id' => $trs[0]['tr_id'],
                        'tr_en_child_id' => $trs[0]['tr_en_parent_id'],
                        'tr_in_child_id' => $i['tr_in_child_id'],
                    )));
                }
                //Sendout messages:
                $this->Comm_model->send_message($send_messages);
            }

            //TODO Update w__progress at this point based on intent data
            //TODO implement drip 'tr_en_type_id' => 4281, //Pending Drip
        }
    }


    function k_skip_recursive_down($tr_id, $update_db = true)
    {
        //TODO Readjust the removal of $tr_id, $in_id variables
        //User has requested to skip an intent starting from:
        $dwn_tree = $this->Db_model->k_recursive_fetch($tr_id, $in_id, true);
        $skip_ks = array_merge(array(intval($tr_id)), $dwn_tree['k_flat']);

        //Now see how many should we actually skip based on current status:
        $skippable_ks = $this->Db_model->tr_fetch(array(
            'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
            'tr_id IN (' . join(',', $skip_ks) . ')' => null,
        ), ($update_db ? array() : array('cr', 'cr_c_child')), 0, 0, array('k_rank' => 'ASC'));

        if ($update_db) {

            //Now start skipping:
            foreach ($skippable_ks as $k) {
                $this->Db_model->tr_status_update($k['tr_id'], -1); //skip
            }

            //There is a chance that the subscription might be now completed due to this skipping, lets check:
            /*
            $trs = $this->Db_model->tr_fetch(array(
                'tr_id' => $tr_id,
            ), array('w','cr','cr_c_parent'));
            if(count($trs)>0){
                $this->Db_model->k_complete_recursive_up($trs[0],$trs[0],-1);
            }
            */

        }

        //Returned intents:
        return $skippable_ks;

    }


    function k_choose_or($tr_id, $tr_in_parent_id, $in_id)
    {
        //$in_id is the chosen path for the options of $tr_in_parent_id
        //When a user chooses an answer to an ANY intent, this function would mark that answer as complete while marking all siblings as SKIPPED
        $chosen_path = $this->Db_model->tr_fetch(array(
            'tr_tr_parent_id' => $tr_id,
            'tr_in_parent_id' => $tr_in_parent_id, //Fetch children of parent intent which are the siblings of current intent
            'tr_in_child_id' => $in_id, //The answer
            'in_status >=' => 2,
        ), array('w', 'cr', 'cr_c_parent'));

        if (count($chosen_path) == 1) {

            //Also fetch children to see if we requires any notes/url to mark as complete:
            $path_requirements = $this->Db_model->tr_fetch(array(
                'tr_tr_parent_id' => $tr_id,
                'tr_in_parent_id' => $tr_in_parent_id, //Fetch children of parent intent which are the siblings of current intent
                'tr_in_child_id' => $in_id, //The answer
                'in_status >=' => 2,
            ), array('w', 'cr', 'cr_c_child'));

            if (count($path_requirements) == 1) {
                //Determine status:
                $force_working_on = ((intval($path_requirements[0]['c_require_notes_to_complete']) || intval($path_requirements[0]['c_require_url_to_complete'])) ? 1 : null);

                //Now mark intent as complete (and this will SKIP all siblings) and move on:
                $this->Db_model->k_complete_recursive_up($chosen_path[0], $chosen_path[0], $force_working_on);

                //Successful:
                return true;
            } else {
                return false;
            }

        } else {
            //Oooopsi, we could not find it! Log error and return false:
            $this->Db_model->tr_create(array(
                'tr_content' => 'Unable to locate OR selection for this subscription',
                'tr_en_type_id' => 4246, //System error
                'tr_in_child_id' => $in_id,
                'tr_tr_parent_id' => $tr_id,
            ));

            return false;
        }
    }

    function k_complete_recursive_up($cr, $w, $force_tr_status = null)
    {

        //Check if parent of this item is not started, because if not, we need to mark that as Working On:
        $parent_ks = $this->Db_model->tr_fetch(array(
            'tr_tr_parent_id' => $w['tr_id'],
            'tr_status' => 0, //skip intents that are not stared or working on...
            'tr_in_child_id' => $cr['tr_in_parent_id'],
        ), array('cr'));
        if (count($parent_ks) == 1) {
            //Update status (It might not work if it was working on AND new tr_status=1)
            $this->Db_model->tr_status_update($parent_ks[0]['tr_id'], 1);
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
            $none_chosen_paths = $this->Db_model->tr_fetch(array(
                'tr_tr_parent_id' => $w['tr_id'],
                'tr_in_parent_id' => $cr['tr_in_parent_id'], //Fetch children of parent intent which are the siblings of current intent
                'tr_in_child_id !=' => $cr['tr_in_child_id'], //NOT The answer (we need its siblings)
                'in_status >=' => 2,
                'tr_status IN (0,1)' => null,
            ), array('w', 'cr', 'cr_c_child'));

            //This is the none chosen answers, if any:
            foreach ($none_chosen_paths as $k) {
                //Skip this intent:
                $total_skipped += count($this->Db_model->k_skip_recursive_down($k['tr_id']));
            }
        }


        if (!$force_tr_status) {
            //Regardless of Branch type, we need all children to be complete if we are to mark this as complete...
            //If not, we will mark is as working on...
            //So lets fetch the down tree and see Whatssup:
            $dwn_tree = $this->Db_model->k_recursive_fetch($w['tr_id'], $cr['tr_in_child_id'], true);

            //Does it have OUTs?
            if (count($dwn_tree['k_flat']) > 0) {
                //We do have down, let's check their status:
                $dwn_incomplete_ks = $this->Db_model->tr_fetch(array(
                    'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
                    'tr_id IN (' . join(',', $dwn_tree['k_flat']) . ')' => null, //All OUT links
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
        $this->Db_model->tr_status_update($cr['tr_id'], $new_tr_status);


        //We are done with this branch if the status is any of the following:
        if (in_array($new_tr_status, array(3, 2, -1))) {

            //Since down tree is now complete, see if up tree needs completion as well:
            //Fetch all parents:
            $up_tree = $this->Db_model->k_recursive_fetch($w['tr_id'], $cr['tr_in_child_id'], false);

            //Track completion for all top parents, because if they are all complete, the Subscription might be complete:
            $w_might_be_complete = true;

            //Now loop through each level and see whatssup:
            foreach ($up_tree['k_flat'] as $parent_tr_id) {

                //Fetch details to see whatssup:
                $parent_ks = $this->Db_model->tr_fetch(array(
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
                        $complete_child_cs = $this->Db_model->tr_fetch(array(
                            'tr_tr_parent_id' => $w['tr_id'],
                            'tr_status NOT IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //complete
                            'tr_in_parent_id' => $parent_ks[0]['tr_in_child_id'],
                        ), array('cr'));
                        if (count($complete_child_cs) == 0) {
                            $is_complete = false;
                        }
                    } else {
                        //We need all immediate children to be complete (i.e. No incomplete)
                        $incomplete_child_cs = $this->Db_model->tr_fetch(array(
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
                        $this->Db_model->tr_status_update($parent_ks[0]['tr_id'], (!is_null($force_tr_status) ? $force_tr_status : 2));
                    } elseif ($parent_ks[0]['tr_status'] == 0) {
                        //Status is not started, let's set to started:
                        $this->Db_model->tr_status_update($parent_ks[0]['tr_id'], 1); //Started
                        //So subscription cannot be complete:
                        $w_might_be_complete = false;
                    } else {
                        //So subscription cannot be complete:
                        $w_might_be_complete = false;
                    }
                }
            }

            if ($w_might_be_complete) {
                //There is a chance that entire subscription might be complete
                //To determine if the subscription is complete we need to look at the top level siblings...
                //What kind of an intent (AND node or OR node) is this subscription tr_in_child_id?
                $intents = $this->Db_model->w_fetch(array(
                    'tr_id' => $w['tr_id'],
                ), array('in'));

                if (count($intents) == 0) {
                    return false;
                }

                //Assume true unless otherwise:
                $w_is_complete = true;

                if ($intents[0]['in_is_any']) {
                    //We need a single one to be completed:
                    $complete_child_cs = $this->Db_model->tr_fetch(array(
                        'tr_tr_parent_id' => $intents[0]['tr_id'],
                        'tr_in_parent_id' => $intents[0]['tr_in_child_id'],
                        'tr_status NOT IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //complete
                    ), array('cr'));
                    if (count($complete_child_cs) == 0) {
                        $w_is_complete = false;
                    }
                } else {
                    //We need all to be completed:
                    $incomplete_child_cs = $this->Db_model->tr_fetch(array(
                        'tr_tr_parent_id' => $intents[0]['tr_id'],
                        'tr_in_parent_id' => $intents[0]['tr_in_child_id'],
                        'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
                    ), array('cr'));
                    if (count($incomplete_child_cs) > 0) {
                        $w_is_complete = false;
                    }
                }

                if ($w_is_complete) {

                    //We do this check as a hack to a bug that was running this piece of code 10 times!
                    $validate_subscription = $this->Db_model->w_fetch(array(
                        'tr_id' => $intents[0]['tr_id'], //Other than this one...
                        'tr_status <' => 2, //Not Completed subscriptions
                    ));

                    if (count($validate_subscription) == 1) {

                        //What subscription number is this?
                        $completed_ws = $this->Db_model->w_fetch(array(
                            'tr_id !=' => $intents[0]['tr_id'], //Other than this one...
                            'w_parent_en_id' => $intents[0]['tr_en_parent_id'],
                            'tr_status >=' => 2, //Completed subscriptions
                        ));

                        //Inform user that they are now complete with all tasks:
                        $this->Comm_model->send_message(array(
                            array(
                                'tr_en_child_id' => $intents[0]['tr_en_parent_id'],
                                'tr_in_child_id' => $intents[0]['tr_in_child_id'],
                                'tr_tr_parent_id' => $intents[0]['tr_id'],
                                'tr_content' => 'Congratulations for completing your ' . echo_ordinal((count($completed_ws) + 1)) . ' Subscription 🎉 Over time I will keep sharing new insights (based on my new training data) that could help you to ' . $intents[0]['in_outcome'] . ' 🙌 You can, at any time, stop updates on your subscriptions by saying "quit".',
                            ),
                            array(
                                'tr_en_child_id' => $intents[0]['tr_en_parent_id'],
                                'tr_in_child_id' => $intents[0]['tr_in_child_id'],
                                'tr_tr_parent_id' => $intents[0]['tr_id'],
                                'tr_content' => 'How else can I help you ' . $this->config->item('primary_in_name') . '? ' . echo_pa_lets(),
                            ),
                        ));

                        //The entire subscription is now complete!
                        $this->Db_model->w_update($intents[0]['tr_id'], array(
                            'tr_status' => 2, //Subscription is now complete
                            //TODO Maybe change to status 3 directly if the nature of the intent is not verifiable
                        ));

                    }
                }
            }
        }
    }


    function k_create($insert_columns)
    {


        if (!isset($insert_columns['k_timestamp'])) {
            $insert_columns['k_timestamp'] = date("Y-m-d H:i:s");
        }

        if (!isset($insert_columns['k_rank'])) {
            //Determine the highest rank for this subscription:
            $insert_columns['k_rank'] = 1 + $this->Db_model->tr_max_order('tb_actionplan_links', 'k_rank', array(
                    'tr_tr_parent_id' => $insert_columns['tr_tr_parent_id'],
                ));
        }


        if (!isset($insert_columns['tr_order'])) {
            $insert_columns['tr_order'] = 0;
        }


        //Lets now add:
        $this->db->insert('tb_actionplan_links', $insert_columns);

        //Fetch inserted id:
        $insert_columns['tr_id'] = $this->db->insert_id();

        return $insert_columns;
    }

    function in_combo_create($in_id, $in_outcome, $in_linkto_id, $next_level, $tr_en_credit_id)
    {

        if (intval($in_id) <= 0) {
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        } elseif (strlen($in_outcome) <= 0) {
            return array(
                'status' => 0,
                'message' => 'Missing Intent Outcome',
            );
        }

        $in_linkto_id = intval($in_linkto_id);

        //Validate Original intent:
        $parent_intents = $this->Db_model->in_fetch(array(
            'in_id' => intval($in_id),
        ), array('fetch_children'));
        if (count($parent_intents) <= 0) {
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            );
        }

        if (!$in_linkto_id) {

            //We are NOT linking to an existing intent, but instead, we're creating a new intent:
            //Set default new hours:

            $default_new_seconds = 0;

            $recursive_query = array(
                'in__tree_max_seconds' => $default_new_seconds,
                'in__tree_in_count' => 1, //We just added one
            );

            //Create intent:
            $new_intent = $this->Db_model->in_create(array(
                'in_outcome' => trim($in_outcome),
                'in_seconds' => $default_new_seconds,
                'in__tree_in_count' => 1, //We just added one
                'in__tree_max_seconds' => $default_new_seconds,
            ));

            //Log transaction for New Intent:
            $this->Db_model->tr_create(array(
                'tr_en_credit_id' => $tr_en_credit_id,
                'tr_metadata' => array(
                    'input_data' => $_POST,
                    'after' => $new_intent,
                ),
                'tr_en_type_id' => 4250, //New Intent
                'tr_in_child_id' => $new_intent['in_id'],
            ));

        } else {

            //We are linking to $in_linkto_id, lets make sure it exists:
            $new_intents = $this->Db_model->in_fetch(array(
                'in_id' => $in_linkto_id,
                'in_status >=' => 0,
            ), ($next_level == 2 ? array('fetch_children') : array()));

            //, array('fetch_grandchildren')

            if (count($new_intents) <= 0) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid Linked Intent ID',
                );
            }
            $new_intent = $new_intents[0];


            //check for all parents:
            $parent_tree = $this->Db_model->in_recursive_fetch($in_id);
            if (in_array($new_intent['in_id'], $parent_tree['in_flat_tree'])) {
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $new_intent['in_outcome'] . '" as its own child.',
                );
            }


            //Make sure this is not a duplicate intent for its parent:
            //cr_children_fetch
            $dup_links = $this->Db_model->tr_fetch(array(
                'tr_in_parent_id' => intval($in_id),
                'tr_in_child_id' => $new_intent['in_id'],
                'tr_en_type_id IN (' . join(', ', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
                'tr_status >=' => 0,
                'in_status >=' => 0,
            ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));


            if (count($dup_links) > 0) {
                //What is the status? If achived, we can bring back to life!
                if ($dup_links[0]['tr_status'] < 0) {
                    //Yes, we can bring back to life!
                    //TODO update old link here?
                } else {
                    //Ooops, this is a duplicate!
                    return array(
                        'status' => 0,
                        'message' => '[' . $new_intent['in_outcome'] . '] is already linked here.',
                    );
                    //TODO maybe trigger a notice to admin on how to not add duplicates!
                }
            } elseif ($new_intent['in_id'] == $in_id) {
                //Make sure none of the parents are the same:
                return array(
                    'status' => 0,
                    'message' => 'You cannot add "' . $new_intent['in_outcome'] . '" as its own child.',
                );
            }

            //Prepare recursive update:
            $recursive_query = array(
                'in__tree_in_count' => $new_intent['in__tree_in_count'],
                'in__tree_max_seconds' => intval($new_intent['in__tree_max_seconds']),
                'in__messages_tree_count' => $new_intent['in__messages_tree_count'],
            );
        }


        //Create Intent Link:
        $relation = $this->Db_model->tr_create(array(
            'tr_en_credit_id' => $tr_en_credit_id,
            'tr_en_type_id' => 4228,

            'tr_in_parent_id' => intval($in_id),
            'tr_in_child_id' => $new_intent['in_id'],

            'tr_order' => 1 + $this->Db_model->tr_max_order('table_ledger', 'tr_order', array(
                    'tr_status >=' => 0,
                    'in_status >=' => 0,
                    'tr_en_type_id' => 4228,
                    'tr_in_parent_id' => intval($in_id),
                )),
        ), true);

        //Update tree count from parent and above:
        $updated_recursively = $this->Db_model->metadata_tree_update('in', $in_id, $recursive_query);


        $relations = $this->Old_model->cr_children_fetch(array(
            'tr_id' => $relation['tr_id'],
        ));

        $relations = $this->Db_model->tr_fetch(array(
            'tr_in_parent_id' => intval($in_id),
            'tr_in_child_id' => $new_intent['in_id'],
            'tr_en_type_id IN (' . join(', ', $this->config->item('en_ids_4486')) . ')' => null, //Intent-to-Intent Links
            'tr_status >=' => 0,
            'in_status >=' => 0,
        ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

        //Return result:
        return array(
            'status' => 1,
            'in_id' => $new_intent['in_id'],
            'in__tree_max_seconds' => $new_intent['in__tree_max_seconds'],
            'adjusted_c_count' => intval($new_intent['in__tree_in_count']),
            'html' => echo_c(array_merge($new_intent, $relations[0]), $next_level, intval($in_id)),
        );
    }

    function w_create($insert_columns)
    {

        if (detect_missing_columns($insert_columns, array('tr_en_parent_id', 'tr_in_child_id'))) {
            return false;
        }

        if (!isset($insert_columns['w_timestamp'])) {
            $insert_columns['w_timestamp'] = date("Y-m-d H:i:s");
        }
        if (!isset($insert_columns['tr_status'])) {
            $insert_columns['tr_status'] = 1;
        }
        if (!isset($insert_columns['w_parent_en_id'])) {
            $insert_columns['w_parent_en_id'] = 0; //No miner assigned
        }

        if (!isset($insert_columns['w_c_rank'])) {
            //Place this new action plan after the last one the user currently has:
            $insert_columns['w_c_rank'] = 1 + $this->Db_model->tr_max_order('tb_actionplans', 'w_c_rank', array(
                    'tr_status >=' => 1, //Anything they are working on...
                    'tr_en_parent_id' => $insert_columns['tr_en_parent_id'],
                )); //No miner assigned
        }

        //Lets now add:
        $this->db->insert('tb_actionplans', $insert_columns);

        //Fetch inserted id:
        $insert_columns['tr_id'] = $this->db->insert_id();

        if ($insert_columns['tr_id'] > 0) {

            //Now let's create a cache of the Action Plan for this subscription:
            $tree = $this->Db_model->in_recursive_fetch($insert_columns['tr_in_child_id'], true, false, $insert_columns['tr_id']);

            if (count($tree['in_tr_flat_tree']) > 0) {

                $intent = end($tree['in_tree']);

            } else {

                //This would happen if the user subscribes to an intent without any children...
                //This should not happen, inform user and log error:
                $this->Comm_model->send_message(array(
                    array(
                        'tr_en_child_id' => $insert_columns['tr_en_parent_id'],
                        'tr_in_child_id' => $insert_columns['tr_in_child_id'],
                        'tr_content' => 'Subscription failed',
                    ),
                ));

            }

            //Return results:
            return $insert_columns;

        } else {
            return false;
        }
    }


    function en_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array('en_trust_score' => 'DESC'), $select = '*', $group_by = null)
    {

        //Fetch the target entities:
        $this->db->select($select);
        $this->db->from('table_entities');
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        if ($group_by) {
            $this->db->group_by($group_by);
        }
        foreach ($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }
        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }

        $q = $this->db->get();
        $res = $q->result_array();


        //Now fetch parents:
        foreach ($res as $key => $val) {

            if (in_array('in__children_count', $join_objects)) {
                //Fetch the messages for this entity:
                $res[$key]['in__children_count'] = count($this->Old_model->ur_children_fetch(array(
                    'tr_en_parent_id' => $val['en_id'],
                    'tr_status >=' => 0, //Pending or Active
                    'en_status >=' => 0, //Pending or Active
                )));
            }


            if (in_array('u__urls', $join_objects)) {
                //Fetch the messages for this entity:
                $res[$key]['u__urls'] = $this->Old_model->x_fetch(array(
                    'x_status >' => -2,
                    'x_en_id' => $val['en_id'],
                ), array(), array(
                    'x_type' => 'ASC'
                ));
            }

            if (in_array('u__ws', $join_objects)) {
                //Fetch the subscriptions for this entity:
                $res[$key]['u__ws'] = $this->Db_model->w_fetch(array(
                    'tr_en_parent_id' => $val['en_id'],
                    'tr_status IN (1,2)' => null, //Active subscriptions (Passive ones have a more targetted distribution)
                ), array('in'), array(
                    'w_last_heard' => 'ASC'
                ));
            }


            //Always fetch entity parents unless explicitly requested not to:
            if (in_array('skip_en__parents', $join_objects)) {
                $res[$key]['en__parents'] = array();
            } else {
                $res[$key]['en__parents'] = $this->Db_model->tr_parent_fetch(array(
                    'tr_en_child_id' => $val['en_id'],
                    'tr_status >=' => 0, //Pending or Active
                    'en_status >=' => 0, //Pending or Active
                ));
            }
        }

        return $res;
    }


    function en_create($insert_columns, $external_sync = false, $tr_en_credit_id = 0)
    {

        //What is required to create a new intent?
        if (detect_missing_columns($insert_columns, array('en_status', 'en_name'))) {
            return false;
        }

        if (isset($insert_columns['en_metadata'])) {
            $insert_columns['en_metadata'] = serialize($insert_columns['en_metadata']);
        } else {
            $insert_columns['en_metadata'] = null;
        }

        if (!isset($insert_columns['en_trust_score'])) {
            //Will be later calculated via a cron job:
            $insert_columns['en_trust_score'] = 0;
        }

        //Lets now add:
        $this->db->insert('table_entities', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['en_id'])) {
            $insert_columns['en_id'] = $this->db->insert_id();
        }

        if ($insert_columns['en_id'] > 0) {

            if ($external_sync) {

                //Log transaction new entity:
                $this->Db_model->tr_create(array(
                    'tr_en_credit_id' => ($tr_en_credit_id > 0 ? $tr_en_credit_id : $insert_columns['en_id']),
                    'tr_en_child_id' => $insert_columns['en_id'],
                    'tr_en_type_id' => 4251, //New Entity Created
                ));

                //Update Algolia:
                $this->Db_model->algolia_sync('en', $insert_columns['en_id']);

                //Fetch to return the complete entity data:
                $entities = $this->Db_model->en_fetch(array(
                    'en_id' => $insert_columns['en_id'],
                ));

                return $entities[0];

            } else {

                //Return provided inputs plus the new entity ID:
                return $insert_columns;

            }

        } else {

            //Ooopsi, something went wrong!
            //TODO Log Bug report transaction
            return false;

        }
    }

    function tr_status_processing($e_items)
    {
        foreach ($e_items as $e) {
            if ($e['tr_id'] > 0 && $e['tr_status'] == 0) {
                $this->Db_model->tr_update($e['tr_id'], array(
                    'tr_status' => 1, //Working on... (So other cron jobs do not pickup this item again)
                ));
            }
        }
    }


    function i_create($insert_columns)
    {

        //Need either entity or intent:
        if (!isset($insert_columns['tr_in_child_id'])) {
            $this->Db_model->tr_create(array(
                'tr_content' => 'A new message requires either an Entity or Intent to be referenced to',
                'tr_metadata' => $insert_columns,
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }

        //Other required fields:
        if (detect_missing_columns($insert_columns, array('tr_content'))) {
            return false;
        }

        if (!isset($insert_columns['tr_status'])) {
            $insert_columns['tr_status'] = 1;
        }
        if (!isset($insert_columns['tr_order'])) {
            $insert_columns['tr_order'] = 1;
        }

        if (!isset($insert_columns['tr_en_parent_id'])) {
            //Describes an entity:
            $insert_columns['tr_en_parent_id'] = 0;
        }
        if (!isset($insert_columns['tr_in_child_id'])) {
            //Describes an entity:
            $insert_columns['tr_in_child_id'] = 0;
        }


        //Lets now add:
        $this->db->insert('tb_intent_messages', $insert_columns);

        //Fetch inserted id:
        $insert_columns['tr_id'] = $this->db->insert_id();

        return $insert_columns;
    }


    function in_fetch($match_columns, $join_objects = array(), $limit = 0, $limit_offset = 0, $order_columns = array(), $select = '*', $group_by = null)
    {

        //The basic fetcher for intents
        $this->db->select($select);
        $this->db->from('table_intents');

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
        $intents = $q->result_array();

        foreach ($intents as $key => $value) {

            //Should we append intent messages?
            if (in_array('in__active_messages', $join_objects)) {
                $intents[$key]['in__active_messages'] = $this->Db_model->tr_fetch(array(
                    'tr_status >=' => 0, //New+ status which is considered active (not removed)
                    'tr_en_type_id IN (' . join(',', $this->config->item('en_child_4485')) . ')' => null, //All Intent messages
                    'tr_in_child_id' => $value['in_id'],
                ), array(), 200, 0, array('tr_order' => 'ASC'));
            }

            //Should we fetch all parent intentions?
            if (in_array('in__active_parents', $join_objects)) {
                $intents[$key]['in__active_parents'] = $this->Old_model->cr_parents_fetch(array(
                    'tr_in_child_id' => $value['in_id'],
                    'tr_status' => 1,
                ), $join_objects);
            }

            //Have we been asked to append any children/granchildren to this query?
            if (in_array('fetch_children', $join_objects) || in_array('fetch_grandchildren', $join_objects)) {

                //Fetch immediate children:
                $intents[$key]['in__active_children'] = $this->Old_model->cr_children_fetch(array(
                    'tr_in_parent_id' => $value['in_id'],
                    'tr_status' => 1,
                    'in_status >=' => 0,
                ), $join_objects);

                //Fetch second-level granchildren?
                if (in_array('fetch_grandchildren', $join_objects)) {
                    foreach ($intents[$key]['in__active_children'] as $key2 => $value2) {
                        $intents[$key]['in__active_children'][$key2]['in__active_children'] = $this->Old_model->cr_children_fetch(array(
                            'tr_in_parent_id' => $value2['in_id'],
                            'tr_status' => 1,
                            'in_status >=' => 0,
                        ), $join_objects);
                    }
                }
            }
        }

        //Return everything that was collected:
        return $intents;
    }


    function tr_max_order($match_columns)
    {

        //Counts the current highest order value
        $this->db->select('MAX(tr_order) as largest_order');
        $this->db->from('table_ledger');
        foreach ($match_columns as $key => $value) {
            $this->db->where($key, $value);
        }
        $q = $this->db->get();
        $stats = $q->row_array();
        if (count($stats) > 0) {
            return intval($stats['largest_order']);
        } else {
            //Nothing found:
            return 0;
        }
    }


    function x_sync($x_url, $x_en_id, $cad_edit, $accept_existing_url = false)
    {

        //Auth user and check required variables:
        $udata = auth(array(1308));
        $x_url = trim($x_url);

        if (!$udata) {
            return array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            );
        } elseif (!isset($x_en_id)) {
            return array(
                'status' => 0,
                'message' => 'Missing Child Entity ID',
            );
        } elseif (!isset($cad_edit)) {
            return array(
                'status' => 0,
                'message' => 'Missing Editing Permission',
            );
        } elseif (!isset($x_url) || strlen($x_url) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing URL',
            );
        } elseif (!filter_var($x_url, FILTER_VALIDATE_URL)) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        }

        //Validate parent entity:
        $children_us = $this->Db_model->en_fetch(array(
            'en_id' => $x_en_id,
        ));

        //Make sure this URL does not exist:
        $dup_urls = $this->Old_model->x_fetch(array(
            'x_status >' => -2,
            '(x_url LIKE \'' . $x_url . '\' OR x_clean_url LIKE \'' . $x_url . '\')' => null,
        ), array('en'));

        //Call URL to validate it further:
        $curl = curl_html($x_url, true);

        if (!$curl) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        } elseif (count($dup_urls) > 0) {

            if ($accept_existing_url) {
                //Return the object as this is expected:
                return array(
                    'status' => 1,
                    'message' => 'Found existing URL',
                    'is_existing' => 1,
                    'curl' => $curl,
                    'en' => array_merge($children_us[0], $dup_urls[0]),
                );
            } elseif ($dup_urls[0]['en_id'] == $x_en_id) {
                return array(
                    'status' => 0,
                    'message' => 'This URL has already been added!',
                );
            } else {
                return array(
                    'status' => 0,
                    'message' => 'URL is already being used by [' . $dup_urls[0]['en_name'] . ']. URLs cannot belong to multiple entities.',
                );
            }
        } elseif (count($children_us) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid Child Entity ID [' . $x_en_id . ']',
            );
        }


        if ($x_en_id == 1326) { //Content

            //We need to create a new entity and add this URL below it:
            $x_types = echo_status('x_type', null);
            $en_name = null;
            $url_code = substr(md5($x_url), 0, 8);

            if (strlen($curl['page_title']) > 0) {

                //Make sure this is not a duplicate name:
                $dup_name_us = $this->Db_model->en_fetch(array(
                    'en_status >=' => 0,
                    'en_name' => $curl['page_title'],
                ));

                if (count($dup_name_us) > 0) {
                    //Yes, we did find a duplicate name! Change this slightly:
                    $en_name = $curl['page_title'] . ' ' . $url_code;
                } else {
                    //No duplicate detected, all good to go:
                    $en_name = $curl['page_title'];
                }

            } else {
                $en_name = $x_types[$curl['x_type']]['s_name'] . ' ' . $url_code;
            }

            $new_entity = $this->Db_model->en_create(array(
                'en_name' => $en_name,
            ), true, $udata['en_id']);

            //Place this new entity in $x_en_id [Content]
            $ur1 = $this->Db_model->tr_create(array(
                'tr_en_child_id' => $new_entity['en_id'],
                'tr_en_parent_id' => $x_en_id,
            ));

        } else {
            $new_entity = $children_us[0];
            $ur1 = array();
        }


        //All good, Save URL:
        $new_x = $this->Db_model->x_create(array(
            'x_parent_en_id' => $udata['en_id'],
            'x_en_id' => $new_entity['en_id'],
            'x_url' => $x_url,
            'x_clean_url' => ($curl['clean_url'] ? $curl['clean_url'] : $x_url),
            'x_type' => $curl['x_type'],
            'x_status' => ($curl['url_is_broken'] ? -1 : 1), //Either Published or Seems Broken
        ));

        if (!isset($new_x['x_id']) || $new_x['x_id'] < 1) {
            return array(
                'status' => 0,
                'message' => 'There was an issue creating the URL',
            );
        }


        //Is this a image suitable to become the Entity icon? If so, set this as the default:
        $set_cover_x_id = (!$children_us[0]['u_cover_x_id'] && $new_x['x_type'] == 4 /* Image file */ ? $new_x['x_id'] : 0);


        //Update Algolia:
        $this->Db_model->algolia_sync('en', $new_entity['en_id']);


        if ($x_en_id == 1326) {

            //Return entity object:
            return array(
                'status' => 1,
                'message' => 'Success',
                'curl' => $curl,
                'en' => array_merge($new_entity, $ur1),
                'set_cover_x_id' => $set_cover_x_id,
                'new_en' => ($accept_existing_url ? null : echo_u(array_merge($new_entity, $ur1), 2)),
            );

        } else {

            //Return URL object:
            return array(
                'status' => 1,
                'message' => 'Success',
                'curl' => $curl,
                'en' => $children_us[0],
                'set_cover_x_id' => $set_cover_x_id,
                'new_x' => echo_x($children_us[0], $new_x),
            );

        }
    }


    function tr_parent_fetch($match_columns, $join_objects = array())
    {
        //Missing anything?
        $this->db->select('*');
        $this->db->from('tb_entities u');
        $this->db->join('tb_entity_links ur', 'tr_en_parent_id = en_id');
        $this->db->join('tb_entity_urls x', 'x.x_id = u_cover_x_id', 'left'); //Fetch the cover photo if >0
        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }
        $this->db->order_by('en_trust_score', 'DESC');
        $q = $this->db->get();
        return $q->result_array();
    }


    function en_update($id, $update_columns, $external_sync = false, $tr_en_credit_id = 0)
    {

        /*
         *
         * $external_sync helps log a transaction for the new entity that is about to
         * be created but we yet dont have its entity ID to use in $tr_en_credit_id!
         *
         * */

        if (count($update_columns) == 0) {
            return false;
        }

        if ($external_sync) {
            //Fetch current entity filed values so we can compare later on after we've updated it:
            $before_data = $this->Db_model->en_fetch(array('en_id' => $id));

            //Make sure this was a valid id:
            if (!(count($before_data) == 1)) {
                return false;
            }
        }

        //Cleanup metadata if needed:
        $update_columns['en_metadata'] = (isset($update_columns['en_metadata']) ? serialize($update_columns['en_metadata']) : null);

        //Update:
        $this->db->where('en_id', $id);
        $this->db->update('table_entities', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows && $external_sync) {

            //Log modification transaction for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('en_metadata', 'en_trust_score'))) {

                    //Value has changed, log engagement:
                    $this->Db_model->tr_create(array(
                        'tr_en_credit_id' => ($tr_en_credit_id > 0 ? $tr_en_credit_id : $id),
                        'tr_en_type_id' => ($key == 'en_status' && intval($value) < 0 ? 4253 /* Removed */ : 4263 /* Attribute Modified */),
                        'tr_en_child_id' => $id,
                        'tr_content' => 'Entity ' . ucwords(str_replace('_', ' ', str_replace('en_', '', $key))) . ' modified from [' . $before_data[0][$key] . '] to [' . $value . ']',
                        'tr_metadata' => array(
                            'en_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));

                }

            }

            //Sync algolia:
            $this->Db_model->algolia_sync('en', $id);

        }

        return $affected_rows;
    }


    function in_update($id, $update_columns, $tr_en_credit_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        if ($tr_en_credit_id > 0) {
            //Fetch current intent filed values so we can compare later on after we've updated it:
            $before_data = $this->Db_model->in_fetch(array('in_id' => $id));

            //Make sure this was a valid id:
            if (!(count($before_data) == 1)) {
                return false;
            }
        }

        //Cleanup metadata if needed:
        $update_columns['in_metadata'] = (isset($update_columns['in_metadata']) ? serialize($update_columns['in_metadata']) : null);

        //Update:
        $this->db->where('in_id', $id);
        $this->db->update('table_intents', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Do we need to do any additional work?
        if ($affected_rows && $tr_en_credit_id) {

            //Note that unlike entity modification, we require a creditor entity ID to log the modification transaction:
            //Log modification transaction for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (!($before_data[0][$key] == $value) && !in_array($key, array('in_metadata'))) {

                    //Value has changed, log engagement:
                    $this->Db_model->tr_create(array(
                        'tr_en_credit_id' => $tr_en_credit_id,
                        'tr_en_type_id' => ($key == 'in_status' && intval($value) < 0 ? 4252 /* Removed */ : 4264 /* Attribute Modified */),
                        'tr_in_child_id' => $id,
                        'tr_content' => 'Intent ' . ucwords(str_replace('_', ' ', str_replace('in_', '', $key))) . ' modified from [' . $before_data[0][$key] . '] to [' . $value . ']',
                        'tr_metadata' => array(
                            'in_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));

                }

            }

            //Sync algolia:
            $this->Db_model->algolia_sync('in', $id);

        }

        return $affected_rows;
    }


    function tr_update($id, $update_columns, $tr_en_credit_id = 0)
    {

        if (count($update_columns) == 0) {
            return false;
        }

        if ($tr_en_credit_id > 0) {
            //Fetch transaction before updating:
            $before_data = $this->Db_model->tr_fetch(array(
                'tr_id' => $id,
            ));

            //Make sure this was a valid id:
            if (!(count($before_data) == 1)) {
                return false;
            }
        }

        //Cleanup if needed:
        $update_columns['tr_metadata'] = (isset($update_columns['tr_metadata']) ? serialize($update_columns['tr_metadata']) : null);

        //Update:
        $this->db->where('tr_id', $id);
        $this->db->update('table_ledger', $update_columns);
        $affected_rows = $this->db->affected_rows();

        //Log changes if successful:
        if ($affected_rows && $tr_en_credit_id) {

            //Log modification transaction for every field changed:
            foreach ($update_columns as $key => $value) {

                //Has this value changed compared to what we initially had in DB?
                if (in_array($key, array('tr_status', 'tr_content', 'tr_order', 'tr_en_parent_id', 'tr_en_child_id', 'tr_in_parent_id', 'tr_in_child_id')) && !($before_data[0][$key] == $value)) {

                    //Value has changed, log engagement:
                    $this->Db_model->tr_create(array(
                        'tr_tr_parent_id' => $id, //Parent Transaction ID
                        'tr_en_credit_id' => $tr_en_credit_id,
                        'tr_en_type_id' => ($key == 'tr_status' && in_array(intval($value), array(-1, -3)) ? 4241 /* Removed */ : 4242 /* Attribute Modified */),
                        'tr_content' => 'Transaction ' . ucwords(str_replace('_', ' ', str_replace('tr_', '', $key))) . ' modified from [' . $before_data[0][$key] . '] to [' . $value . ']',
                        'tr_metadata' => array(
                            'tr_id' => $id,
                            'field' => $key,
                            'before' => $before_data[0][$key],
                            'after' => $value,
                        ),
                    ));
                }
            }
        }

        return $affected_rows;
    }


    function in_create($insert_columns, $external_sync = false)
    {

        //What is required to create a new intent?
        if (detect_missing_columns($insert_columns, array('in_status', 'in_outcome'))) {
            return false;
        }

        if (isset($insert_columns['in_metadata'])) {
            $insert_columns['in_metadata'] = serialize($insert_columns['in_metadata']);
        } else {
            $insert_columns['in_metadata'] = null;
        }

        //Lets now add:
        $this->db->insert('table_intents', $insert_columns);

        //Fetch inserted id:
        if (!isset($insert_columns['in_id'])) {
            $insert_columns['in_id'] = $this->db->insert_id();
        }

        if ($external_sync) {
            //Update Algolia:
            $this->Db_model->algolia_sync('in', $insert_columns['in_id']);
        }

        return $insert_columns;
    }


    /* ******************************
     * Other
     ****************************** */


    function x_update($id, $update_columns)
    {
        $this->db->where('x_id', $id);
        $this->db->update('tb_entity_urls', $update_columns);
        return $this->db->affected_rows();
    }

    function x_create($insert_columns)
    {

        if (detect_missing_columns($insert_columns, array('x_url', 'x_clean_url', 'x_type', 'x_parent_en_id', 'x_en_id', 'x_status'))) {
            return false;
        } elseif (!filter_var($insert_columns['x_url'], FILTER_VALIDATE_URL)) {
            return false;
        } elseif (!filter_var($insert_columns['x_clean_url'], FILTER_VALIDATE_URL)) {
            return false;
        }

        //Check to see if this URL exists, if so, return that:
        $urls = $this->Old_model->x_fetch(array(
            '(x_url LIKE \'' . $insert_columns['x_url'] . '\' OR x_url LIKE \'' . $insert_columns['x_clean_url'] . '\')' => null,
        ));

        if (count($urls) > 0) {

            if ($insert_columns['x_en_id'] == $urls[0]['x_en_id']) {

                //For same object, we're all good, return this URL:
                return $urls[0];

            } else {

                //Save this engagement as we have an issue here...
                $this->Db_model->tr_create(array(
                    'tr_en_credit_id' => $insert_columns['x_parent_en_id'],
                    'tr_en_child_id' => $insert_columns['x_en_id'],
                    'tr_en_type_id' => 4246, //System error
                    'tr_content' => 'x_create() found a duplicate URL ID [' . $urls[0]['x_id'] . ']',
                    'tr_metadata' => $insert_columns,
                    'e_x_id' => $urls[0]['x_id'],
                ));

                return false;
            }
        }

        if (!isset($insert_columns['x_timestamp'])) {
            $insert_columns['x_timestamp'] = date("Y-m-d H:i:s");
        }

        if (!isset($insert_columns['x_check_timestamp'])) {
            $insert_columns['x_check_timestamp'] = date("Y-m-d H:i:s");
        }

        if (!isset($insert_columns['x_http_code'])) {
            $insert_columns['x_http_code'] = 200; //As the URL was just added
        }


        //Lets now add:
        $this->db->insert('tb_entity_urls', $insert_columns);

        //Fetch inserted id:
        $insert_columns['x_id'] = $this->db->insert_id();

        return $insert_columns;
    }


    function tr_fetch($match_columns = array(), $join_objects = array(), $limit = 100, $limit_offset = 0, $order_columns = array('tr_timestamp' => 'DESC'), $select = '*', $group_by = null)
    {

        $this->db->select($select);
        $this->db->from('table_ledger');

        if (in_array('in_parent', $join_objects)) {
            $this->db->join('table_intents', 'tr_in_parent_id=in_id');
        } elseif (in_array('in_child', $join_objects)) {
            $this->db->join('table_intents', 'tr_in_child_id=in_id');
        }

        if (in_array('en_parent', $join_objects)) {
            $this->db->join('table_entities', 'tr_en_parent_id=en_id');
        } elseif (in_array('en_child', $join_objects)) {
            $this->db->join('table_entities', 'tr_en_child_id=en_id');
        } elseif (in_array('en_type', $join_objects)) {
            $this->db->join('table_entities', 'tr_en_type_id=en_id');
        }

        foreach ($match_columns as $key => $value) {
            if (!is_null($value)) {
                $this->db->where($key, $value);
            } else {
                $this->db->where($key);
            }
        }

        if ($group_by) {
            $this->db->group_by($group_by);
        }

        foreach ($order_columns as $key => $value) {
            $this->db->order_by($key, $value);
        }

        if ($limit > 0) {
            $this->db->limit($limit, $limit_offset);
        }
        $q = $this->db->get();
        return $q->result_array();
    }


    function en_search_match($en_parent_id, $value)
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
        $matching_entities = $this->Db_model->tr_fetch(array(
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
            $this->Db_model->tr_create(array(
                'tr_content' => 'en_search_match() found [' . count($matching_entities) . '] results as the children of en_id=[' . $en_parent_id . '] that had the value of [' . $value . '].',
                'tr_en_type_id' => 4246, //Platform Error
                'tr_en_child_id' => $en_parent_id,
            ));

            return 0;
        }
    }

    function tr_create($insert_columns, $external_sync = false)
    {

        if (detect_missing_columns($insert_columns, array('tr_en_type_id'))) {
            return false;
        }


        //Unset un-allowed columns to be manually added:
        if (isset($insert_columns['tr_obj_id'])) {
            unset($insert_columns['tr_obj_id']);
        }
        if (isset($insert_columns['tr_coins'])) {
            unset($insert_columns['tr_coins']);
        }
        if (isset($insert_columns['tr_metadata'])) {
            $insert_columns['tr_metadata'] = serialize($insert_columns['tr_metadata']);
        } else {
            $insert_columns['tr_metadata'] = null;
        }


        //Try to auto detect user:
        if (!isset($insert_columns['tr_en_credit_id'])) {
            //Attempt to fetch creator ID from session:
            $entity_data = $this->session->userdata('user');
            if (isset($entity_data['en_id']) && intval($entity_data['en_id']) > 0) {
                $insert_columns['tr_en_credit_id'] = $entity_data['en_id'];
            } else {
                //Do not issue credit to anyone:
                $insert_columns['tr_en_credit_id'] = 0;
            }
        }

        //Set some defaults:
        if (!isset($insert_columns['tr_content'])) {
            $insert_columns['tr_content'] = null;
            $insert_columns['tr_obj_id'] = 0;
        } elseif (is_int($insert_columns['tr_content']) && intval($insert_columns['tr_content']) > 0) {
            //Store integer separately for faster query access later on:
            $insert_columns['tr_obj_id'] = intval($insert_columns['tr_content']);
        }

        if (!isset($insert_columns['tr_timestamp'])) {
            //Time with milliseconds:
            $t = microtime(true);
            $micro = sprintf("%06d", ($t - floor($t)) * 1000000);
            $d = new DateTime(date('Y-m-d H:i:s.' . $micro, $t));
            $insert_columns['tr_timestamp'] = $d->format("Y-m-d H:i:s.u");
        }

        if (!isset($insert_columns['tr_status'])) {
            $insert_columns['tr_status'] = 2; //Auto Published
        }

        //Set some zero defaults if not set:
        foreach (array('tr_in_child_id', 'tr_in_parent_id', 'tr_en_child_id', 'tr_en_parent_id', 'tr_tr_parent_id') as $dz) {
            if (!isset($insert_columns[$dz])) {
                $insert_columns[$dz] = 0;
            }
        }


        //Do we need to adjust coins?
        /*
        $award_coins = $this->Db_model->tr_fetch(array(
            'tr_en_type_id' => 4319, //Number Link
            'tr_en_parent_id' => 4374, //Transaction Coins
            'tr_en_child_id' => $insert_columns['tr_en_type_id'], //This type of transaction
            'tr_status >=' => 2, //Must be published+
            'en_status >=' => 2, //Must be published+
        ), array('en_child'), 1);
        if (count($award_coins) > 0) {
            //Yes, we have to issue coins:
            $insert_columns['tr_coins'] = doubleval($award_coins[0]['tr_content']);
        }
        */

        //Lets log:
        $this->db->insert('table_ledger', $insert_columns);

        //Fetch inserted id:
        $insert_columns['tr_id'] = $this->db->insert_id();

        //All good huh?
        if ($insert_columns['tr_id'] < 1) {
            return false;
        }


        //Now we might need to cache if this is a Video, Audio, Image or File URL:
        if (in_array($insert_columns['tr_en_type_id'], array(4258, 4259, 4260, 4261))) {
            $this->Db_model->tr_create(array(
                'tr_status' => 0, //New
                'tr_en_type_id' => 4299, //Media Uploaded
                'tr_tr_parent_id' => $insert_columns['tr_id'],
                //Replicate remaining fields:
                'tr_en_credit_id' => $insert_columns['tr_en_credit_id'],
                'tr_en_parent_id' => $insert_columns['tr_en_parent_id'],
                'tr_en_child_id' => $insert_columns['tr_en_child_id'],
                'tr_in_parent_id' => $insert_columns['tr_in_parent_id'],
                'tr_in_child_id' => $insert_columns['tr_in_child_id'],
                'tr_content' => $insert_columns['tr_content'],
            ));
        }


        //Sync algolia?
        if ($external_sync) {

            if ($insert_columns['tr_en_parent_id'] > 0) {
                $this->Db_model->algolia_sync('en', $insert_columns['tr_en_parent_id']);
            }

            if ($insert_columns['tr_en_child_id'] > 0) {
                $this->Db_model->algolia_sync('en', $insert_columns['tr_en_child_id']);
            }

            if ($insert_columns['tr_in_parent_id'] > 0) {
                $this->Db_model->algolia_sync('in', $insert_columns['tr_in_parent_id']);
            }

            if ($insert_columns['tr_in_child_id'] > 0) {
                $this->Db_model->algolia_sync('in', $insert_columns['tr_in_child_id']);
            }

        }

        //Notify subscribers for this event
        //TODO update to new system
        if (0) {

            foreach ($this->config->item('notify_admins') as $admin_en_id => $subscription) {

                //Do not notify about own actions:
                if (intval($insert_columns['tr_en_credit_id']) == $admin_en_id) {
                    continue;
                }

                if (in_array($insert_columns['tr_en_type_id'], $subscription['admin_notify'])) {

                    //Just do this one:
                    if (!isset($engagements[0])) {
                        //Fetch Engagement Data:
                        $engagements = $this->Db_model->tr_fetch(array(
                            'tr_id' => $insert_columns['tr_id']
                        ));
                    }

                    //Did we find it? We should have:
                    if (isset($engagements[0])) {

                        $subject = 'Notification: ' . trim(strip_tags($engagements[0]['in_outcome'])) . ' - ' . (isset($engagements[0]['en_name']) ? $engagements[0]['en_name'] : 'System');

                        //Compose email:
                        $html_message = null; //Start

                        if (strlen($engagements[0]['tr_content']) > 0) {
                            $html_message .= '<div>' . format_tr_content($engagements[0]['tr_content']) . '</div><br />';
                        }

                        //Lets go through all references to see what is there:
                        foreach ($this->config->item('engagement_references') as $engagement_field => $er) {
                            if (intval($engagements[0][$engagement_field]) > 0) {
                                //Yes we have a value here:
                                $html_message .= '<div>' . $er['name'] . ': ' . echo_object($er['object_code'], $engagements[0][$engagement_field], $engagement_field, null) . '</div>';
                            }
                        }

                        //Append ID:
                        $html_message .= '<div>Engagement ID: <a href="https://mench.com/adminpanel/li_list_blob/' . $engagements[0]['tr_id'] . '">#' . $engagements[0]['tr_id'] . '</a></div>';

                        //Send email:
                        $this->Comm_model->send_email($subscription['admin_emails'], $subject, $html_message);
                    }
                }
            }
        }


        //Return:
        return $insert_columns;
    }


    function metadata_tree_update($obj_type, $obj_id, $metadata_new = array(), $direction_is_downward = 0)
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
            $tree = $this->Db_model->in_recursive_fetch($obj_id, $direction_is_downward);

            if (count($tree['in_flat_tree']) == 0) {
                return false;
            }

            //Now fetch them all:
            $objects = $this->Db_model->in_fetch(array(
                'in_id IN (' . join(',', $tree['in_flat_tree']) . ')' => null,
            ));

        } elseif (in_array($obj_type, array('en'))) {

            //TODO add entity support

        }

        //Apply relative changes to all objects:
        $affected_rows = 0;
        foreach ($objects as $obj) {
            //Make a relative adjustment compared to what is currently there:
            $affected_rows += $this->Db_model->metadata_update($obj_type, $obj, $metadata_new, false);
        }

        //Return total affected rows:
        return $affected_rows;

    }


    function metadata_update($obj_type, $obj, $new_fields, $absolute_adjustment = true)
    {

        /*
         *
         * Enables the easy manipulation of the text metadata field which holds cache data for developers
         *
         *   $obj_type is either in or en
         *   $field is the array key within the metadata
         *
         * */

        if (!in_array($obj_type, array('in', 'en')) || !isset($obj[$obj_type . '_metadata']) || count($new_fields) < 1) {
            return false;
        }

        //Prepare metadata:
        $metadata = unserialize($obj[$obj_type . '_metadata']);

        //Go through all the new fields and see if they differ from current metadata fields:
        foreach ($new_fields as $metadata_key => $metadata_value) {
            if (!$absolute_adjustment) {
                //We need to do a relative adjustment:
                $metadata[$metadata_key] = (isset($metadata[$metadata_key]) ? $metadata[$metadata_key] : 0) + $metadata_value;
            } else {

                //We are doing an absolute adjustment if needed:
                if (is_null($metadata_value)) {
                    //User asked to remove this value:
                    unset($metadata[$metadata_key]);
                } elseif (!isset($metadata[$metadata_key]) || $metadata[$metadata_key] !== $metadata_value) {
                    //Value has changed, adjust:
                    $metadata[$metadata_key] = $metadata_value;
                }

            }
        }

        //Now update DB without logging any transactions as this is considered a back-end update:
        if ($obj_type == 'in') {

            $affected_rows = $this->Db_model->in_update($obj['in_id'], array(
                'in_metadata' => $metadata,
            ));

        } elseif ($obj_type == 'en') {

            $affected_rows = $this->Db_model->en_update($obj['en_id'], array(
                'en_metadata' => $metadata,
            ));

        }

        //Should be all good:
        return $affected_rows;

    }

    function metadata_extract($current_metadata, $field)
    {

        /*
         *
         * Fetches the text metadata field which holds cache data for developers
         *
         *   $object is the original object
         *   $field is the array key within the metadata
         *
         * */


    }

    function in_recursive_fetch($in_id, $direction_is_downward = false, $update_db_table = false, $tr_actionplan_id = 0, $parent_c = array(), $recursive_children = null)
    {

        //Get core data:
        $immediate_children = array(
            '___tree_all_count' => 0,
            '___messages_count' => 0,
            '___messages_tree_count' => 0,

            '___tree_min_seconds' => 0,
            '___tree_max_seconds' => 0,
            '___tree_min_cost' => 0,
            '___tree_max_cost' => 0,

            '___tree_experts' => array(), //Expert references across all contributions
            '___tree_miners' => array(), //Trainer references considering intent messages
            '___tree_contents' => array(), //Content types entity references on messages

            'metadatas_updated' => 0, //Keeps count of database metadata fields that were not in sync with the latest version of the cahced data
            'db_queries' => array(), //Useful for debugging to see what changed at each metadatas_updated request

            'in_tree' => array(), //Fetches the intent tree with its full 2-dimensional & hierarchical beauty
            'in_flat_tree' => array(), //Puts all the tree's intent IDs in a flat array, useful for quick processing
            'in_tr_flat_tree' => array(), //Puts all the tree's intent transaction (intent link) IDs in a flat array, useful for quick processing
        );

        if (!$recursive_children) {
            $recursive_children = $immediate_children;
        }

        //Fetch & add this item itself:
        if (isset($parent_c['tr_id'])) {

            if ($direction_is_downward) {
                $intents = $this->Old_model->cr_children_fetch(array(
                    'tr_id' => $parent_c['tr_id'],
                ), ($update_db_table ? array('in__active_messages') : array()));
            } else {
                $intents = $this->Old_model->cr_parents_fetch(array(
                    'tr_id' => $parent_c['tr_id'],
                ), ($update_db_table ? array('in__active_messages') : array()));
            }

        } else {

            //This is the very first item that
            $intents = $this->Db_model->in_fetch(array(
                'in_id' => $in_id,
            ), ($update_db_table ? array('in__active_messages') : array()));

        }


        //We should have found an item by now:
        if (count($intents) < 1) {
            return false;
        }


        //Always add intent to tree:
        array_push($immediate_children['in_flat_tree'], intval($in_id));


        //Add the link relations before we start recursion so we can have the Tree in up-custom order:
        if (isset($intents[0]['tr_id'])) {

            //Add intent link:
            array_push($immediate_children['in_tr_flat_tree'], intval($intents[0]['tr_id']));

            //Are we caching an Action Plan?
            if ($tr_actionplan_id > 0) {
                //Yes we are, create a cache of this link for this Action Plan:
                $this->Db_model->k_create(array(
                    'tr_tr_parent_id' => $tr_actionplan_id,
                    'k_cr_id' => $intents[0]['tr_id'],
                    'tr_order' => $intents[0]['tr_order'],
                ));
            }

        }

        //Terminate at OR branches for Action Plan caching (when $tr_actionplan_id>0)
        if ($tr_actionplan_id > 0 && $intents[0]['in_is_any']) {
            //return false;
        }

        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        if ($direction_is_downward) {
            $child_cs = $this->Old_model->cr_children_fetch(array(
                'tr_in_parent_id' => $in_id,
                'tr_status' => 1,
                'in_status >=' => 0,
            ));
        } else {
            $child_cs = $this->Old_model->cr_parents_fetch(array(
                'tr_in_child_id' => $in_id,
                'tr_status' => 1,
                'in_status >=' => 0,
            ));
        }


        if (count($child_cs) > 0) {

            //We need to determine this based on the tree AND/OR logic:
            $local_values = array(
                'in___tree_min_seconds' => null,
                'in___tree_max_seconds' => null,
                'in___tree_min_cost' => null,
                'in___tree_max_cost' => null,
            );

            foreach ($child_cs as $c) {

                if (in_array($c['in_id'], $recursive_children['in_flat_tree'])) {

                    //Ooooops, this has an error as it would result in an infinite loop:
                    return false;

                } else {

                    //Fetch children for this intent, if any:
                    $granchildren = $this->Db_model->in_recursive_fetch($c['in_id'], $direction_is_downward, $update_db_table, $tr_actionplan_id, $c, $immediate_children);

                    if (!$granchildren) {
                        //There was an infinity break
                        return false;
                    }

                    //Addup children if any:
                    $immediate_children['___tree_all_count'] += $granchildren['___tree_all_count'];

                    if ($intents[0]['in_is_any']) {
                        //OR Branch, figure out the logic:
                        if ($granchildren['___tree_min_seconds'] < $local_values['in___tree_min_seconds'] || is_null($local_values['in___tree_min_seconds'])) {
                            $local_values['in___tree_min_seconds'] = $granchildren['___tree_min_seconds'];
                        }
                        if ($granchildren['___tree_max_seconds'] > $local_values['in___tree_max_seconds'] || is_null($local_values['in___tree_max_seconds'])) {
                            $local_values['in___tree_max_seconds'] = $granchildren['___tree_max_seconds'];
                        }
                        if ($granchildren['___tree_min_cost'] < $local_values['in___tree_min_cost'] || is_null($local_values['in___tree_min_cost'])) {
                            $local_values['in___tree_min_cost'] = $granchildren['___tree_min_cost'];
                        }
                        if ($granchildren['___tree_max_cost'] > $local_values['in___tree_max_cost'] || is_null($local_values['in___tree_max_cost'])) {
                            $local_values['in___tree_max_cost'] = $granchildren['___tree_max_cost'];
                        }
                    } else {
                        //AND Branch, add them all up:
                        $local_values['in___tree_min_seconds'] += intval($granchildren['___tree_min_seconds']);
                        $local_values['in___tree_max_seconds'] += intval($granchildren['___tree_max_seconds']);
                        $local_values['in___tree_min_cost'] += number_format($granchildren['___tree_min_cost'], 2);
                        $local_values['in___tree_max_cost'] += number_format($granchildren['___tree_max_cost'], 2);
                    }


                    if ($update_db_table) {

                        //Update DB requested:
                        $immediate_children['___messages_tree_count'] += $granchildren['___messages_tree_count'];
                        $immediate_children['metadatas_updated'] += $granchildren['metadatas_updated'];
                        if (!empty($granchildren['db_queries'])) {
                            array_push($immediate_children['db_queries'], $granchildren['db_queries']);
                        }

                        //Addup unique experts:
                        foreach ($granchildren['___tree_experts'] as $en_id => $tex) {
                            //Is this a new expert?
                            if (!isset($immediate_children['___tree_experts'][$en_id])) {
                                //Yes, add them to the list:
                                $immediate_children['___tree_experts'][$en_id] = $tex;
                            }
                        }

                        //Addup unique trainers:
                        foreach ($granchildren['___tree_miners'] as $en_id => $tet) {
                            //Is this a new expert?
                            if (!isset($immediate_children['___tree_miners'][$en_id])) {
                                //Yes, add them to the list:
                                $immediate_children['___tree_miners'][$en_id] = $tet;
                            }
                        }

                        //Addup content types:
                        foreach ($granchildren['___tree_contents'] as $type_en_id => $current_us) {
                            foreach ($current_us as $en_id => $u_obj) {
                                if (!isset($immediate_children['___tree_contents'][$type_en_id][$en_id])) {
                                    //Yes, add them to the list:
                                    $immediate_children['___tree_contents'][$type_en_id][$en_id] = $u_obj;
                                }
                            }
                        }

                    }

                    array_push($immediate_children['in_tr_flat_tree'], $granchildren['in_tr_flat_tree']);
                    array_push($immediate_children['in_flat_tree'], $granchildren['in_flat_tree']);
                    array_push($immediate_children['in_tree'], $granchildren['in_tree']);
                }
            }

            //Addup the totals from this tree:
            $immediate_children['___tree_min_seconds'] += $local_values['in___tree_min_seconds'];
            $immediate_children['___tree_max_seconds'] += $local_values['in___tree_max_seconds'];
            $immediate_children['___tree_min_cost'] += $local_values['in___tree_min_cost'];
            $immediate_children['___tree_max_cost'] += $local_values['in___tree_max_cost'];
        }


        $immediate_children['___tree_all_count']++;
        $immediate_children['___tree_min_seconds'] += intval($intents[0]['in_seconds']);
        $immediate_children['___tree_max_seconds'] += intval($intents[0]['in_seconds']);
        $immediate_children['___tree_min_cost'] += number_format($intents[0]['c_cost_estimate'], 2);
        $immediate_children['___tree_max_cost'] += number_format($intents[0]['c_cost_estimate'], 2);

        //Set the data for this intent:
        $intents[0]['___tree_all_count'] = $immediate_children['___tree_all_count'];
        $intents[0]['___tree_min_seconds'] = $immediate_children['___tree_min_seconds'];
        $intents[0]['___tree_max_seconds'] = $immediate_children['___tree_max_seconds'];
        $intents[0]['___tree_min_cost'] = $immediate_children['___tree_min_cost'];
        $intents[0]['___tree_max_cost'] = $immediate_children['___tree_max_cost'];


        //Count messages only if DB updating:
        if ($update_db_table) {

            $intents[0]['___tree_experts'] = array();
            $intents[0]['___tree_miners'] = array();
            $intents[0]['___tree_contents'] = array();

            //Count messages:
            $intents[0]['___messages_count'] = count($this->Db_model->i_fetch(array(
                'tr_status >=' => 0,
                'tr_in_child_id' => $in_id,
            )));
            $immediate_children['___messages_tree_count'] += $intents[0]['___messages_count'];
            $intents[0]['___messages_tree_count'] = $immediate_children['___messages_tree_count'];


            //See who's involved:
            $parent_ids = array();
            foreach ($intents[0]['in__active_messages'] as $i) {

                //Who are the parent authors of this message?


                if (!in_array($i['tr_en_credit_id'], $parent_ids)) {
                    array_push($parent_ids, $i['tr_en_credit_id']);
                }


                //Check the author of this message (The trainer) in the trainer array:
                if (!isset($intents[0]['___tree_miners'][$i['tr_en_credit_id']])) {
                    //Add the entire message which would also hold the trainer details:
                    $intents[0]['___tree_miners'][$i['tr_en_credit_id']] = u_essentials($i);
                }
                //How about the parent of this one?
                if (!isset($immediate_children['___tree_miners'][$i['tr_en_credit_id']])) {
                    //Yes, add them to the list:
                    $immediate_children['___tree_miners'][$i['tr_en_credit_id']] = u_essentials($i);
                }


                //Does this message have any entity references?
                if ($i['tr_en_parent_id'] > 0) {


                    //Add the reference it self:
                    if (!in_array($i['tr_en_parent_id'], $parent_ids)) {
                        array_push($parent_ids, $i['tr_en_parent_id']);
                    }

                    //Yes! Let's see if any of the parents/creators are industry experts:
                    $us_fetch = $this->Db_model->en_fetch(array(
                        'en_id' => $i['tr_en_parent_id'],
                    ));

                    if (isset($us_fetch[0]) && count($us_fetch[0]['en__parents']) > 0) {
                        //We found it, let's loop through the parents and aggregate their IDs for a single search:
                        foreach ($us_fetch[0]['en__parents'] as $parent_u) {

                            //Is this a particular content type?
                            if (in_array($parent_u['en_id'], $this->config->item('en_ids_3000'))) {
                                //yes! Add it to the list if it does not already exist:
                                if (!isset($intents[0]['___tree_contents'][$parent_u['en_id']][$us_fetch[0]['en_id']])) {
                                    $intents[0]['___tree_contents'][$parent_u['en_id']][$us_fetch[0]['en_id']] = u_essentials($us_fetch[0]);
                                }

                                //How about the parent tree?
                                if (!isset($immediate_children['___tree_contents'][$parent_u['en_id']][$us_fetch[0]['en_id']])) {
                                    $immediate_children['___tree_contents'][$parent_u['en_id']][$us_fetch[0]['en_id']] = u_essentials($us_fetch[0]);
                                }
                            }

                            if (!in_array($parent_u['en_id'], $parent_ids)) {
                                array_push($parent_ids, $parent_u['en_id']);
                            }
                        }
                    }
                }
            }

            //Who was involved in content patternization?
            if (count($parent_ids) > 0) {

                //Lets make a query search to see how many of those involved are industry experts:
                $ixs = $this->Old_model->ur_children_fetch(array(
                    'tr_en_parent_id' => 3084, //Industry expert entity
                    'tr_en_child_id IN (' . join(',', $parent_ids) . ')' => null,
                    'tr_status >=' => 0, //Pending review or higher
                    'en_status >=' => 0, //Pending review or higher
                ), array(), 0, 0, 'en_id, en_name, en_trust_score, x_url');

                //Put unique IDs in array key for faster searching:
                foreach ($ixs as $ixsu) {
                    if (!isset($intents[0]['___tree_experts'][$ixsu['en_id']])) {
                        $intents[0]['___tree_experts'][$ixsu['en_id']] = $ixsu;
                    }
                }
            }


            //Did we find any new industry experts?
            if (count($intents[0]['___tree_experts']) > 0) {

                //Yes, lets add them uniquely to the mother array assuming they are not already there:
                foreach ($intents[0]['___tree_experts'] as $new_ixs) {
                    //Is this a new expert?
                    if (!isset($immediate_children['___tree_experts'][$new_ixs['en_id']])) {
                        //Yes, add them to the list:
                        $immediate_children['___tree_experts'][$new_ixs['en_id']] = $new_ixs;
                    }
                }
            }
        }

        array_push($immediate_children['in_tree'], $intents[0]);


        if ($update_db_table) {

            //Assign aggregates:
            $intents[0]['___tree_experts'] = $immediate_children['___tree_experts'];
            $intents[0]['___tree_miners'] = $immediate_children['___tree_miners'];
            $intents[0]['___tree_contents'] = $immediate_children['___tree_contents'];

            //Start sorting:
            if (is_array($intents[0]['___tree_experts']) && count($intents[0]['___tree_experts']) > 0) {
                usort($intents[0]['___tree_experts'], 'sortByScore');
            }
            if (is_array($intents[0]['___tree_miners']) && count($intents[0]['___tree_miners']) > 0) {
                usort($intents[0]['___tree_miners'], 'sortByScore');
            }
            foreach ($intents[0]['___tree_contents'] as $type_en_id => $current_us) {
                if (isset($intents[0]['___tree_contents'][$type_en_id]) && count($intents[0]['___tree_contents'][$type_en_id]) > 0) {
                    usort($intents[0]['___tree_contents'][$type_en_id], 'sortByScore');
                }
            }

            //Update DB only if any single field is not synced:
            if (!(
                intval($intents[0]['___tree_min_seconds']) == intval($intents[0]['in__tree_min_seconds']) &&
                intval($intents[0]['___tree_max_seconds']) == intval($intents[0]['in__tree_max_seconds']) &&
                number_format($intents[0]['___tree_min_cost'], 2) == number_format($intents[0]['in__tree_min_cost'], 2) &&
                number_format($intents[0]['___tree_max_cost'], 2) == number_format($intents[0]['in__tree_max_cost'], 2) &&
                ((!$intents[0]['in__tree_experts'] && count($intents[0]['___tree_experts']) < 1) || (serialize($intents[0]['___tree_experts']) == $intents[0]['in__tree_experts'])) &&
                ((!$intents[0]['in__tree_miners'] && count($intents[0]['___tree_miners']) < 1) || (serialize($intents[0]['___tree_miners']) == $intents[0]['in__tree_miners'])) &&
                ((!$intents[0]['in__tree_contents'] && count($intents[0]['___tree_contents']) < 1) || (serialize($intents[0]['___tree_contents']) == $intents[0]['in__tree_contents'])) &&
                $intents[0]['___tree_all_count'] == $intents[0]['in__tree_in_count'] &&
                $intents[0]['___messages_count'] == $intents[0]['in__messages_count'] &&
                $intents[0]['___messages_tree_count'] == $intents[0]['in__messages_tree_count']
            )) {

                //Something was not up to date, let's update:
                if ($this->Db_model->metadata_update('in', $intents[0], array(
                    'in__tree_min_seconds' => intval($intents[0]['___tree_min_seconds']),
                    'in__tree_max_seconds' => intval($intents[0]['___tree_max_seconds']),
                    'in__tree_min_cost' => number_format($intents[0]['___tree_min_cost'], 2),
                    'in__tree_max_cost' => number_format($intents[0]['___tree_max_cost'], 2),
                    'in__tree_in_count' => $intents[0]['___tree_all_count'],
                    'in__messages_count' => $intents[0]['___messages_count'],
                    'in__messages_tree_count' => $intents[0]['___messages_tree_count'],
                    'in__tree_experts' => $intents[0]['___tree_experts'],
                    'in__tree_miners' => $intents[0]['___tree_miners'],
                    'in__tree_contents' => $intents[0]['___tree_contents'],
                ))) {
                    //Yes update was successful:
                    $immediate_children['metadatas_updated']++;
                }


                array_push($immediate_children['db_queries'], '[' . $in_id . '] Seconds:' . intval($intents[0]['in__tree_max_seconds']) . '=>' . intval($intents[0]['___tree_max_seconds']) . ' / All Count:' . $intents[0]['in__tree_in_count'] . '=>' . $intents[0]['___tree_all_count'] . ' / Message:' . $intents[0]['in__messages_count'] . '=>' . $intents[0]['___messages_count'] . ' / Tree Message:' . $intents[0]['in__messages_tree_count'] . '=>' . $intents[0]['___messages_tree_count'] . ' (' . $intents[0]['in_outcome'] . ')');

            }
        }


        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($immediate_children['in_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['in_flat_tree'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['in_tr_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['in_tr_flat_tree'] = $result;


        //Return data:
        return $immediate_children;
    }


    function k_recursive_fetch($tr_id, $in_id, $direction_is_downward, $parent_c = array(), $recursive_children = null)
    {

        //Get core data:
        $immediate_children = array(
            'in_flat_tree' => array(),
            'in_tr_flat_tree' => array(),
            'k_flat' => array(),
        );

        if (!$recursive_children && !isset($parent_c['tr_id'])) {
            //First item:
            $recursive_children = $immediate_children;
            $intents = $this->Db_model->in_fetch(array(
                'in_id' => $in_id,
            ));

        } else {
            //Recursive item:
            $intents = $this->Db_model->tr_fetch(array(
                'tr_tr_parent_id' => $tr_id,
                'k_cr_id' => $parent_c['tr_id'],
            ), array('cr', ($direction_is_downward ? 'cr_c_child' : 'cr_c_parent')));
        }

        //We should have found an item by now:
        if (count($intents) < 1) {
            return false;
        }


        //Add the link relations before we start recursion so we can have the Tree in up-custom order:
        array_push($immediate_children['in_flat_tree'], intval($in_id));
        if (isset($intents[0]['tr_id'])) {
            array_push($immediate_children['in_tr_flat_tree'], intval($intents[0]['tr_id']));
            array_push($immediate_children['k_flat'], intval($intents[0]['tr_id']));
        }


        //A recursive function to fetch all Tree for a given intent, either upwards or downwards
        $child_cs = $this->Db_model->tr_fetch(array(
            'tr_tr_parent_id' => $tr_id,
            'in_status >=' => 2,
            ($direction_is_downward ? 'tr_in_parent_id' : 'tr_in_child_id') => $in_id,
        ), array('cr', ($direction_is_downward ? 'cr_c_child' : 'cr_c_parent')));


        if (count($child_cs) > 0) {
            foreach ($child_cs as $c) {

                //Fetch children for this intent, if any:
                $granchildren = $this->Db_model->k_recursive_fetch($tr_id, $c['in_id'], $direction_is_downward, $c, $immediate_children);

                //return $granchildren;

                if (!$granchildren) {
                    //There was an infinity break
                    return false;
                }

                //Addup values:
                array_push($immediate_children['in_tr_flat_tree'], $granchildren['in_tr_flat_tree']);
                array_push($immediate_children['k_flat'], $granchildren['k_flat']);
                array_push($immediate_children['in_flat_tree'], $granchildren['in_flat_tree']);
            }
        }

        //Flatten intent ID array:
        $result = array();
        array_walk_recursive($immediate_children['in_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['in_flat_tree'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['in_tr_flat_tree'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['in_tr_flat_tree'] = $result;

        $result = array();
        array_walk_recursive($immediate_children['k_flat'], function ($v, $k) use (&$result) {
            $result[] = $v;
        });
        $immediate_children['k_flat'] = $result;

        //Return data:
        return $immediate_children;
    }


    function algolia_sync($obj, $obj_id = 0)
    {

        return false; //Algolia is disabled for now

        //Define the support objects indexed on algolia:
        $obj_id = intval($obj_id);

        //Names of Algolia indexes for each data type:
        $alg_indexes = array(
            'in' => 'alg_intents',
            'en' => 'alg_entities',
        );
        //The corresponding local tables for each object:
        $algolia_local_tables = array(
            'in' => 'table_intents',
            'en' => 'table_entities',
        );

        if (!array_key_exists($obj, $alg_indexes)) {
            return array(
                'status' => 0,
                'message' => 'Invalid object [' . $obj . ']',
            );
        }

        boost_power();


        if (is_dev()) {
            //Do a call on live as this does not work on local:
            return json_decode(curl_html("https://mench.com/cron/algolia_sync/" . $obj . "/" . $obj_id));
        }

        //Load algolia
        $search_index = load_php_algolia($alg_indexes[$obj]);

        if (!$obj_id) {
            //Clear this index before re-creating it from scratch:
            $search_index->clearIndex();
        }

        //Prepare universal query limits:
        if ($obj_id) {
            $limits[$obj . '_id'] = $obj_id;
        } else {
            $limits[$obj . '_status >='] = 0; //Intents and Entities that are status New+
        }

        //Fetch item(s) for updates:
        if ($obj == 'in') {
            $items = $this->Db_model->in_fetch($limits);
        } elseif ($obj == 'en') {
            $items = $this->Db_model->en_fetch($limits);
        }

        //Go through selection and update:
        if (count($items) == 0) {
            return array(
                'status' => 0,
                'message' => 'No items found for [' . $obj . ']',
            );
        }

        $return_items = array();
        foreach ($items as $item) {

            unset($new_item);
            $new_item = array();

            //Is this already indexed?
            if ($item[$obj . '_algolia_id'] > 0 && $obj_id) {
                $current_algolia_id = $this->Db_model->metadata_extract($item[$obj . '_metadata'], $obj . '__algolia_id');
                if ($current_algolia_id) {
                    //Update existing object:
                    $new_item['objectID'] = $current_algolia_id;
                }
            }

            if ($obj == 'en') {

                $new_item['en_id'] = intval($item['en_id']); //rquired for all objects
                $new_item['en_id'] = intval($item['en_id']); //rquired for all objects
                $new_item['en_trust_score'] = intval($item['en_trust_score']);
                $new_item['en_status'] = intval($item['en_status']);
                $new_item['en_name'] = '';
                $new_item['_tags'] = array();

                //Tags map parent relation:
                if (count($item['en__parents']) > 0) {
                    //Loop through parent entities:
                    foreach ($item['en__parents'] as $u) {
                        array_push($new_item['_tags'], 'en' . $u['en_id']);
                    }
                } else {
                    //Orphan Entity:
                    array_push($new_item['_tags'], 'isorphan');
                }

                //TODO Fetch parent text/url links to be indexed:
                // $new_item['u_keywords'] .= '';


                //Add primary Entity as tag of Entity itself for search management:
                if ($item['en_id'] == $this->config->item('primary_en_id')) {
                    array_push($new_item['_tags'], 'en' . $this->config->item('primary_en_id'));
                }

                //Append additional information:
                $urls = $this->Old_model->x_fetch(array(
                    'x_status >' => -2,
                    'x_en_id' => $item['en_id'],
                ));
                foreach ($urls as $x) {
                    //Add main URL:
                    $new_item['u_keywords'] .= ' ' . $x['x_url'];

                    //Add Clean URL only if different from main:
                    if (!($x['x_url'] == $x['x_clean_url'])) {
                        $new_item['u_keywords'] .= ' ' . $x['x_clean_url'];
                    }
                }

                //Clean keywords
                $new_item['u_keywords'] = trim(strip_tags($new_item['u_keywords']));

            } elseif ($obj == 'in') {

                $new_item['in_id'] = intval($item['in_id']);
                $new_item['in_outcome'] = $item['in_outcome'];
                $new_item['in_is_any'] = intval($item['in_is_any']);
                $new_item['c_keywords'] = trim($item['c_trigger_statements']);
                $new_item['in_status'] = intval($item['in_status']);

                $new_item['in__tree_max_secs'] = intval($item['in__tree_max_seconds']);
                $new_item['in__tree_min_secs'] = intval($item['in__tree_min_seconds']);
                $new_item['in__tree_in_count'] = intval($item['in__tree_in_count']);
                $new_item['in__messages_tree_count'] = intval($item['in__messages_tree_count']);

                //Append parent intents:
                $new_item['_tags'] = array();

                $child_cs = $this->Old_model->cr_parents_fetch(array(
                    'tr_in_child_id' => $item['in_id'],
                    'tr_status' => 1,
                    'in_status >=' => 0,
                ));

                if (count($child_cs) > 0) {
                    //Loop through the Tags:
                    foreach ($child_cs as $c) {
                        array_push($new_item['_tags'], 'in' . $c['in_id']);
                    }
                } else {
                    //No parents!
                    array_push($new_item['_tags'], 'isorphan');
                }
            }

            //Add to main array
            array_push($return_items, $new_item);

        }


        //Now let's see what to do:
        if ($obj_id) {

            //We should have fetched a single item only, meaning $items[0] is what we care about...

            if ($items[0][$obj . '_status'] >= 0) {

                if (intval($items[0][$obj . '_algolia_id']) > 0) {

                    //Update existing index:
                    $obj_add_message = $search_index->saveObjects($return_items);

                } else {

                    //Create new index:
                    $obj_add_message = $search_index->addObjects($return_items);

                    //Now update local database with the objectIDs:
                    if (isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs']) > 0) {
                        foreach ($obj_add_message['objectIDs'] as $key => $algolia_id) {
                            $this->db->query("UPDATE " . $algolia_local_tables[$obj] . " SET " . $obj . "_algolia_id=" . $algolia_id . " WHERE " . $obj . "_id=" . $return_items[$key][$obj . '_id']);
                        }
                    }

                }

            } elseif (intval($items[0][$obj . '_algolia_id']) > 0) {

                //item has been Removed locally but its still indexed on Algolia

                //Remove from algolia:
                $search_index->deleteObject($items[0][$obj . '_algolia_id']);

                //also set its algolia_id to 0 locally:
                $this->db->query("UPDATE " . $algolia_local_tables[$obj] . " SET " . $obj . "_algolia_id=0 WHERE " . $obj . "_id=" . $obj_id);

                return array(
                    'status' => 1,
                    'message' => 'Item Removed',
                );

            }

        } else {

            //Mass update request
            //All remote items have been Removed from algolia index and local algolia_ids have been set to zero
            //we're ready to create new items and update local:
            $obj_add_message = $search_index->addObjects($return_items);

            //Now update database with the objectIDs:
            if (isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs']) > 0) {
                foreach ($obj_add_message['objectIDs'] as $key => $algolia_id) {

                    $this->db->query("UPDATE " . $algolia_local_tables[$obj] . " SET " . $obj . "_algolia_id=" . $algolia_id . " WHERE " . $obj . "_id=" . $return_items[$key][$obj . '_id']);

                }
            }

        }

        //Return results:
        return array(
            'status' => (isset($obj_add_message['objectIDs']) && count($obj_add_message['objectIDs']) > 0 ? 1 : 0),
            'message' => (isset($obj_add_message['objectIDs']) ? count($obj_add_message['objectIDs']) : 0) . ' items updated',
        );

    }

}
