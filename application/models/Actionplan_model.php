<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Actionplan_model extends CI_Model
{

    /*
     *
     * Action Plan related functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function actionplan_step_next_find($en_id, $in){

        /*
         *
         * Searches within a user Action Plan to find
         * first incomplete step.
         *
         * */

        $in_metadata = unserialize($in['in_metadata']);
        foreach(array_flatten($in_metadata['in__metadata_common_steps']) as $common_step_in_id){

            //Is this an expansion step?
            $is_expansion = isset($in_metadata['in__metadata_expansion_steps'][$common_step_in_id]);
            $is_condition = isset($in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]);

            //Is this completed?
            $completed_steps = $this->Links_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',' , $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Steps
                'ln_miner_entity_id' => $en_id, //Belongs to this User
                'ln_parent_intent_id' => $common_step_in_id,
                'ln_status_entity_id' => 6176, //Link Published
            ), ( $is_expansion ? array('in_child') : array() ));

            //Have they completed this?
            if(count($completed_steps) == 0){

                //Not completed yet, this is the next step:
                return $common_step_in_id;

            } elseif($is_expansion){

                //Completed step that has OR expansions, check recursively to see if next step within here:
                $found_in_id = $this->Actionplan_model->actionplan_step_next_find($en_id, $completed_steps[0]);

                if($found_in_id > 0){
                    return $found_in_id;
                }

            } elseif($is_condition){

                //See which path they got unlocked, if any:
                $unlocked_conditions = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id' => 6140, //Action Plan Conditional Step Unlocked
                    'ln_miner_entity_id' => $en_id, //Belongs to this User
                    'ln_parent_intent_id' => $common_step_in_id,
                    'ln_child_intent_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]) . ')' => null,
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                ), array('in_child'));

                if(count($unlocked_conditions) < 1){
                    continue;
                }

                //Completed step that has OR expansions, check recursively to see if next step within here:
                $found_in_id = $this->Actionplan_model->actionplan_step_next_find($en_id, $unlocked_conditions[0]);

                if($found_in_id > 0){
                    return $found_in_id;
                }
            }
        }


        //Nothing found!
        return 0;

    }

    function actionplan_step_next_go($en_id, $advance_step, $send_title_message = false)
    {

        /*
         *
         * Searches for the next Action Plan step
         * and advance it IF $advance_step = TRUE
         *
         * */

        $user_intents = $this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

        if(count($user_intents) == 0){

            if($advance_step){

                $this->Communication_model->dispatch_message(
                    'You have no intentions added to your Action Plan yet.',
                    array('en_id' => $en_id),
                    true
                );

                //List Recommended Intents and let them choose:
                $this->Communication_model->recommend_intents($en_id);

            }

            //No Action Plans found!
            return 0;

        }


        //Looop through Action Plan intentions and see what's next:
        foreach($user_intents as $user_intent){

            //Find first incomplete step for this Action Plan intention:
            $next_in_id = $this->Actionplan_model->actionplan_step_next_find($en_id, $user_intent);

            if($next_in_id > 0){
                //We found the next incomplete step, return:
                break;
            }
        }

        if($advance_step){

            //Did we find a next step?
            if($next_in_id > 0){

                if($send_title_message){

                    //Fetch and append the title to be more informative:

                    //Yes, we do have a next step, fetch it and give user more details:
                    $next_step_ins = $this->Intents_model->in_fetch(array(
                        'in_id' => $next_in_id,
                    ));

                    //Inform of intent title only if its a clean title:
                    if(in_is_clean_outcome($next_step_ins[0])){
                        $this->Communication_model->dispatch_message(
                            'Let\'s '. $next_step_ins[0]['in_outcome'],
                            array('en_id' => $en_id),
                            true
                        );
                    }

                }

                //Yes, communicate it:
                $this->Actionplan_model->actionplan_step_next_echo($en_id, $next_in_id);

            } else {

                //Inform user that they are now complete with all steps:
                $this->Communication_model->dispatch_message(
                    'You have no pending steps in your Action Plan 🙌 I will keep you updated on new steps as they become available. You may also stop receiving updates by saying "stop".',
                    array('en_id' => $en_id),
                    true
                );

                //List Recommended Intents and let them choose:
                $this->Communication_model->recommend_intents($en_id);

            }
        }

        //Return next step intent or false:
        return intval($next_in_id);

    }

    function actionplan_step_skip_initiate($en_id, $in_id, $fb_messenger_format = true){

        //Fetch this intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ));
        if(count($ins) < 1){
            $this->Links_model->ln_create(array(
                'ln_child_entity_id' => $en_id,
                'ln_child_intent_id' => $in_id,
                'ln_content' => 'actionplan_step_skip_initiate() did not locate the published intent',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return false;
        }

        $skip_message = 'Are you sure you want to skip the '.echo_step_range($ins[0], true).' to '.echo_in_outcome($ins[0]['in_outcome'], $fb_messenger_format, true).'?';

        if(!$fb_messenger_format){

            //Just return the message for HTML format:
            return $skip_message;

        } else {

            //Send over messenger:
            $this->Communication_model->dispatch_message(
                $skip_message,
                array('en_id' => $en_id),
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Skip 🚫',
                        'payload' => 'SKIP-ACTIONPLAN_skip-confirm_'.$in_id, //Confirm and skip
                    ),
                    array(
                        'content_type' => 'text',
                        'title' => 'Continue ▶️',
                        'payload' => 'SKIP-ACTIONPLAN_skip-cancel_'.$in_id, //Cancel skipping
                    ),
                ),
                array(
                    'ln_parent_intent_id' => $in_id,
                )
            );

        }
    }

    function actionplan_step_skip_apply($en_id, $in_id)
    {

        //Fetch intent common steps:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ));
        if(count($ins) < 1){
            $this->Links_model->ln_create(array(
                'ln_content' => 'actionplan_step_skip_apply() failed to locate published intent',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
            ));
            return 0;
        }


        $in_metadata = unserialize( $ins[0]['in_metadata'] );

        if(!isset($in_metadata['in__metadata_common_steps'])){
            $this->Links_model->ln_create(array(
                'ln_content' => 'actionplan_step_skip_apply() failed to locate metadata common steps',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
            ));
            return 0;
        }

        //Fetch common base and expansion paths from intent metadata:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Add Action Plan Skipped Step Progression Links:
        foreach($flat_common_steps as $common_in_id){

            //Fetch current progression links, if any:
            $current_progression_links = $this->Links_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $common_in_id,
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ));

            //Add skip link:
            $new_progression_link = $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 6143, //Action Plan Skipped Step
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $common_in_id,
                'ln_status_entity_id' => 6176, //Link Published
            ));

            //Archive current progression links:
            foreach($current_progression_links as $ln){
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_parent_link_id' => $new_progression_link['ln_id'],
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $en_id);
            }

        }

        //Process on-complete automations:
        $this->Actionplan_model->actionplan_completion_checks($en_id, $ins[0], true, false);

        //Return number of skipped steps:
        return count($flat_common_steps);

    }

    function actionplan_intention_focus($en_id){

        /*
         *
         * A function that goes through the Action Plan
         * and finds the top-priority that the user
         * is currently working on.
         *
         * */

        $top_priority_in = false;
        foreach($this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC')) as $actionplan_in){

            //See progress rate so far:
            $completion_rate = $this->Actionplan_model->actionplan_completion_progress($en_id, $actionplan_in);

            if($completion_rate['completion_percentage'] < 100){
                //This is the top priority now:
                $top_priority_in = $actionplan_in;
                break;
            }

        }

        if(!$top_priority_in){
            return false;
        }

        //Return what's found:
        return array(
            'in' => $top_priority_in,
            'completion_rate' => $completion_rate,
        );

    }

    function actionplan_intention_add($en_id, $in_id){

        //Validate Intent ID:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
        ));

        if (count($ins) != 1) {
            return false;
        }


        //Make sure intent is public:
        $public_in = $this->Intents_model->in_is_public($ins[0], true);

        //Did we have any issues?
        if(!$public_in['status']){

            //Log error:
            $this->Links_model->ln_create(array(
                'ln_child_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
                'ln_content' => 'actionplan_intention_add() was about to add an intention that was not public',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));

            return false;
        }


        //Make sure not already added to this User's Action Plan:
        if(count($this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
                'ln_type_entity_id' => 4235, //Action Plan Set Intention
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            ))) > 0){

            //Oooops this already exists in the Action Plan:
            $this->Links_model->ln_create(array(
                'ln_child_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
                'ln_content' => 'actionplan_intention_add() blocked the addition of a duplicate intention to the Action Plan',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));

            return false;

        }

        //Add intent to User's Action Plan:
        $actionplan = $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status_entity_id' => 6175, //Link Drafting
            'ln_miner_entity_id' => $en_id, //Belongs to this User
            'ln_parent_intent_id' => $ins[0]['in_id'], //The Intent they are adding
            'ln_order' => 1 + $this->Links_model->ln_max_order(array( //Place this intent at the end of all intents the User is drafting...
                    'ln_type_entity_id' => 4235, //Action Plan Set Intention
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
                    'ln_miner_entity_id' => $en_id, //Belongs to this User
                )),
        ));

        //Confirm with them that we're now ready:
        $this->Communication_model->dispatch_message(
            'I have successfully added the intention to ' . $ins[0]['in_outcome'] . ' to your Action Plan 🙌 /link:Open 🚩Action Plan:https://mench.com/actionplan/' . $ins[0]['in_id'],
            array('en_id' => $en_id),
            true,
            array(),
            array(
                'ln_parent_intent_id' => $ins[0]['in_id'],
            )
        );

        /*
         *
         * Not the immediate priority, so let them
         * know that we will get to this when we
         * get to it, unless they want to re-sort
         * their Action Plan.
         *
         * */

        //Fetch top intention that being workined on now:
        $top_priority = $this->Actionplan_model->actionplan_intention_focus($en_id);

        if($top_priority){
            if($top_priority['in']['in_id']==$ins[0]['in_id']){

                //The newly added intent is the top priority, so let's initiate first message for action plan tree:
                $this->Actionplan_model->actionplan_step_next_echo($en_id, $ins[0]['in_id']);

            } else {

                //A previously added intent is top-priority, so let them know:
                $this->Communication_model->dispatch_message(
                    'But we will work on this intention later because based on your Action Plan\'s priorities, your current focus is to '.$top_priority['in']['in_outcome'].' which you have made '.$top_priority['completion_rate']['completion_percentage'].'% progress so far. Alternatively, you can sort your Action Plan\'s priorities. /link:Sort 🚩Action Plan:https://mench.com/actionplan',
                    array('en_id' => $en_id),
                    true
                );

            }
        } else {

            //It seems the user already have this intention as completed:
            $this->Communication_model->dispatch_message(
                'You seem to have completed this intention before, so there is nothing else to do now.',
                array('en_id' => $en_id),
                true
            );

            //List Recommended Intents and let them choose:
            $this->Communication_model->recommend_intents($en_id);

        }

        return true;

    }

    function actionplan_completion_auto_apply($en_id, $in){

        /*
         *
         * A function that marks an intent as complete IF
         * the intent has nothing of substance to be
         * further communicated/done by the user.
         *
         * */


        if(in_array($in['in_type_entity_id'], $this->config->item('en_ids_6794'))){
            //Requires Manual Response:
            return false;
        }

        //Count children:
        $child_count = count($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Fixed intent links only
            'ln_parent_intent_id' => $in['in_id'],
        ), array('in_child')));


        if(in_is_or($in['in_type_entity_id']) && $child_count > 0){
            //OR Branch:
            return false;
        } elseif($child_count > 1){
            //AND with children:
            return false;
        }

        if(count($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6345')) . ')' => null, //Deliverable Intent Notes
            'ln_child_intent_id' => $in['in_id'],
        ))) > 0){
            //Has deliverable messages:
            return false;
        }





        //Ok, now it should be auto completed:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 6158, //Action Plan Auto Complete
            'ln_miner_entity_id' => $en_id,
            'ln_parent_intent_id' => $in['in_id'],
            'ln_status_entity_id' => 6176, //Link Published
        ));

        //Process on-complete automations:
        $this->Actionplan_model->actionplan_completion_checks($en_id, $in, true, true);

        //All good:
        return true;
    }





    function actionplan_completion_recursive_up($en_id, $in, $is_bottom_level = true){

        /*
         *
         * Function Entity:
         *
         * https://mench.com/entities/6410
         *
         * */


        //Let's see how many steps get unlocked:
        $unlock_steps_messages = array();


        //First let's make sure this entire intent tree completed by the user:
        $completion_rate = $this->Actionplan_model->actionplan_completion_progress($en_id, $in);
        if($completion_rate['completion_percentage'] < 100){
            //Not completed, so can't go further up:
            return array();
        }


        //Look at Conditional Steps ONLY at this level:
        $in_metadata = unserialize($in['in_metadata']);
        if(isset($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) && count($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) > 0){


            //Make sure previous step expansion has NOT happened before:
            $existing_expansions = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 6140, //Action Plan Conditional Step Unlocked
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $in['in_id'],
                'ln_child_intent_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ));
            if(count($existing_expansions) > 0){

                //Oh we do have an expansion that already happened! So skip this:
                /*
                 * This was being triggered but I am not sure if its normal or not!
                 * For now will comment out so no errors are logged
                 * TODO: See if you can make sense of this section. The question is
                 * if we would ever try to process a conditional step twice? If it
                 * happens, is it an error or not, and should simply be ignored?
                 *
                $this->Links_model->ln_create(array(
                    'ln_child_entity_id' => $en_id,
                    'ln_parent_intent_id' => $in['in_id'],
                    'ln_child_intent_id' => $existing_expansions[0]['ln_child_intent_id'],
                    'ln_content' => 'actionplan_completion_recursive_up() detected duplicate Label Expansion entries',
                    'ln_type_entity_id' => 4246, //Platform Bug Reports
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                ));
                */

                return array();

            }


            //Yes, Let's calculate user's score for this tree:
            $user_marks = $this->Actionplan_model->actionplan_completion_marks($en_id, $in);



            //Detect potential conditional steps to be Unlocked:
            $found_match = 0;
            $condition_ranges = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'ln_type_entity_id' => 4229,
                'ln_parent_intent_id' => $in['in_id'],
                'ln_child_intent_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ), array('in_child'), 0, 0);


            foreach ($condition_ranges as $conditional_step) {

                //See if it unlocks any of these ranges defined in the metadata:
                $ln_metadata = unserialize($conditional_step['ln_metadata']);

                //Defines ranges:
                if(!isset($ln_metadata['tr__conditional_score_min'])){
                    $ln_metadata['tr__conditional_score_min'] = 0;
                }
                if(!isset($ln_metadata['tr__conditional_score_max'])){
                    $ln_metadata['tr__conditional_score_max'] = 0;
                }


                if($user_marks['steps_answered_score']>=$ln_metadata['tr__conditional_score_min'] && $user_marks['steps_answered_score']<=$ln_metadata['tr__conditional_score_max']){

                    //Found a match:
                    $found_match++;

                    //It did match here! Log and notify user!
                    $message = 'You completed the step to '.echo_in_outcome($in['in_outcome'], true, true).'. ';

                    //Append based on title type:
                    if(in_is_clean_outcome($conditional_step)){
                        $message .= 'This unlocked a new step to '.echo_in_outcome($conditional_step['in_outcome'], true);
                    } else {
                        $message .= 'The result:' . "\n\n" . echo_in_outcome($conditional_step['in_outcome'], true);
                    }

                    //Give reference in Action Plan
                    $message .= ' /link:Open 🚩Action Plan:https://mench.com/actionplan/'.$conditional_step['in_id'];


                    //Communicate message to user:
                    array_push($unlock_steps_messages, array(
                        'ln_content' => $message,
                    ));

                    //Unlock Action Plan:
                    $this->Links_model->ln_create(array(
                        'ln_status_entity_id' => 6176, //Link Published
                        'ln_type_entity_id' => 6140, //Action Plan Conditional Step Unlocked
                        'ln_miner_entity_id' => $en_id,
                        'ln_parent_intent_id' => $in['in_id'],
                        'ln_child_intent_id' => $conditional_step['in_id'],
                        'ln_content' => $message,
                        'ln_metadata' => array(
                            'completion_rate' => $completion_rate,
                            'user_marks' => $user_marks,
                            'condition_ranges' => $condition_ranges,
                        ),
                    ));

                    //See if we also need to mark the child as complete:
                    $this->Actionplan_model->actionplan_completion_auto_apply($en_id, $conditional_step);

                }
            }

            //We must have exactly 1 match by now:
            if($found_match != 1){
                $this->Links_model->ln_create(array(
                    'ln_content' => 'actionplan_completion_recursive_up() found ['.$found_match.'] routing logic matches!',
                    'ln_type_entity_id' => 4246, //Platform Bug Reports
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                    'ln_child_entity_id' => $en_id,
                    'ln_parent_intent_id' => $in['in_id'],
                    'ln_metadata' => array(
                        'completion_rate' => $completion_rate,
                        'user_marks' => $user_marks,
                        'conditional_ranges' => $condition_ranges,
                    ),
                ));
            }

        }


        //Now go up since we know there are more levels...
        if($is_bottom_level){

            //Fetch user intentions:
            $user_intentions_ids = $this->Actionplan_model->actionplan_intention_ids($en_id);

            //Fetch all parents trees for this intent
            $parents_trees = $this->Intents_model->in_fetch_recursive_public_parents($in['in_id']);

            //Prevent duplicate processes even if on multiple parent trees:
            $parents_checked = array();

            //Go through parents trees and detect intersects with user intentions. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
            foreach ($parents_trees as $parent_in_id => $grand_parent_ids) {

                //Does this parent and its grandparents have an intersection with the user intentions?
                if(array_intersect($grand_parent_ids, $user_intentions_ids)){

                    //Yes, let's go through until we hit their intersection
                    foreach($grand_parent_ids as $p_id){

                        //Make sure not duplicated:
                        if(in_array($p_id , $parents_checked)){
                            continue;
                        }

                        //Fetch parent intent:
                        $parent_ins = $this->Intents_model->in_fetch(array(
                            'in_id' => $p_id,
                            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                        ));

                        //Now see if this child completion resulted in a full parent completion:
                        if(count($parent_ins) > 0){
                            $unlock_steps_messages_recursive = $this->Actionplan_model->actionplan_completion_recursive_up($en_id, $parent_ins[0], false);
                            $unlock_steps_messages = array_merge($unlock_steps_messages, $unlock_steps_messages_recursive);
                        }

                        //Terminate if we reached the Action Plan intention level:
                        if(in_array($p_id , $user_intentions_ids)){
                            break;
                        }
                    }
                }
            }
        }


        return $unlock_steps_messages;
    }

    function actionplan_completion_checks($en_id, $in, $send_message, $step_progress_made){


        /*
         *
         * There are certain processes we need to run and messages
         * we need to compile every time an Action Plan step/intent
         * is marked as complete. This function handles that workflow
         * with the following inputs:
         *
         * - $in_id The intent that was marked as complete
         * - $en_id The entity who marked it as complete
         * - $send_message IF TRUE would send messages to $en_id and IF FASLE would return raw messages
         * - $step_progress_made WILL also trigger on-complete messages if the user actually completed
         *
         * */


        //Start with on-complete tips if any:
        if($step_progress_made){

            $on_complete_messages = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 6242, //On-Complete Tips
                'ln_child_intent_id' => $in['in_id'],
            ), array(), 0, 0, array('ln_order' => 'ASC'));

        } else {

            $on_complete_messages = array();

        }


        //Try to unlock steps:
        $unlock_steps_messages = $this->Actionplan_model->actionplan_completion_recursive_up($en_id, $in);


        //Merge the two, if any:
        $on_complete_messages = array_merge($on_complete_messages, $unlock_steps_messages);


        //Return all the messages:
        if($send_message){

            //Send message to user:
            foreach($on_complete_messages as $on_complete_message){
                $this->Communication_model->dispatch_message(
                    $on_complete_message['ln_content'],
                    array('en_id' => $en_id),
                    true
                );
            }

            //Return the number of messages sent:
            return count($on_complete_messages);

        } else {

            //Return messages array:
            return $on_complete_messages;

        }
    }

    function actionplan_step_next_echo($en_id, $in_id, $fb_messenger_format = true)
    {

        /*
         *
         * Advance the user action plan by 1 step
         *
         * - $in_id:            The next step intent to be completed now
         *
         * - $en_id:            The recipient who will receive the messages via
         *                      Facebook Messenger. Note that this function does
         *                      not support an HTML format, only Messenger.
         *
         * */

        //Basic input validation:
        if ($in_id < 1) {

            return array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            );

        } elseif ($en_id < 1) {

            return array(
                'status' => 0,
                'message' => 'Missing recipient entity ID',
            );

        }

        //Fetch/Validate intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
        ));

        if (count($ins) < 1) {

            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'actionplan_step_next_echo() called invalid intent',
                'ln_child_entity_id' => $en_id,
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

            return array(
                'status' => 0,
                'message' => 'Invalid Intent #' . $ins[0]['in_id'],
            );

        } elseif (!in_array($ins[0]['in_status_entity_id'], $this->config->item('en_ids_7355') /* Intent Statuses Public */)) {

            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'actionplan_step_next_echo() called intent that is not yet public',
                'ln_child_entity_id' => $en_id,
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

            return array(
                'status' => 0,
                'message' => 'Invalid #' . $ins[0]['in_id'].' is not yet public',
            );

        } elseif (!$fb_messenger_format && in_array($ins[0]['in_type_entity_id'], $this->config->item('en_ids_7366') /* Private Intents */)) {

            return array(
                'status' => 0,
                'message' => 'Invalid #' . $ins[0]['in_id'].' can only be completed using chat.',
            );

        }


        /*
         *
         * There are different ways to complete an intent
         * as listed under User Steps Completed:
         *
         * https://mench.com/entities/6146
         *
         * We'll start by assuming the most basic form of
         * completion (Action Plan Auto Complete) and
         * build-up to more advance forms of completion
         * as we gather more data through-out this function.
         *
         * Also note that some progression types follow a
         * 2-step completion method where users are
         * required to submit their response in order
         * to move to the next step as defined by
         * Action Plan 2-Step Link Types:
         *
         * https://mench.com/entities/6244
         *
         * */


        //Fetch submission requirements, messages, children and current progressions (if any):
        $completion_req_note = $this->Intents_model->in_manual_response_note($ins[0], $fb_messenger_format); //See if we have intent requirements
        $in__messages = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $ins[0]['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));
        $in__children = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Fixed intent links only
            'ln_parent_intent_id' => $ins[0]['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        $current_progression_links = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
            'ln_miner_entity_id' => $en_id,
            'ln_parent_intent_id' => $ins[0]['in_id'],
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ));


        //Define communication variables:
        $next_step_message = '';
        $next_step_quick_replies = array();

        //Define step variables:
        $has_children = (count($in__children) > 0);
        $in_is_or = in_is_or($ins[0]['in_type_entity_id']);


        //Let's figure out the progression method:
        if(!$in_is_or && $completion_req_note){
            $progression_type_entity_id = 6144; //Action Plan Requirement Submitted
        } elseif($in_is_or && $has_children){
            $progression_type_entity_id = 6157; //Action Plan Question Answered
        } elseif(count($in__messages) > 0){
            $progression_type_entity_id = 4559; //Action Plan Messages Read
        } else {
            $progression_type_entity_id = 6158; //Action Plan Auto Complete
        }


        //Let's learn more about the nature of this progression link:
        $is_two_step                = in_array($progression_type_entity_id, $this->config->item('en_ids_6244')); //If TRUE, initial progression link will be logged as WORKING ON since we need user response
        $step_progress_made    = ( !$is_two_step && in_array($progression_type_entity_id, $this->config->item('en_ids_6255'))); //Action Plan Steps Progressed
        $nothing_more_to_do         = ( !$is_two_step && !$has_children && in_array($progression_type_entity_id, $this->config->item('en_ids_6274')) ); //If TRUE, we will auto move on to the next item
        $recommend_recommend        = false; //Assume FALSE unless $nothing_more_to_do=TRUE and we do not have any next steps which means user has finished their Action Plan




        /*
         *
         * Before getting started with communications,
         * Let's see if we need to log a new progress
         * link for this step of the Action Plan.
         *
         * */
        $made_published_progress = false; //Assume FALSE, search and see...
        foreach($current_progression_links as $current_progression_link){
            if(in_array($current_progression_link['ln_status_entity_id'], $this->config->item('en_ids_7359')/* Link Statuses Public */)){
                $made_published_progress = true;
                break;
            }
        }

        if(count($current_progression_links)<1 || ( !$is_two_step && !$made_published_progress )){

            //Log new link:
            $new_progression_link = $this->Links_model->ln_create(array(
                'ln_type_entity_id' => $progression_type_entity_id,
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $ins[0]['in_id'],
                'ln_status_entity_id' => ( $is_two_step ? 6175 /* Link Drafting */ : 6176 /* Link Published */ ),
            ));

            //Since we logged a new progression, let's remove the old ones if any:
            if(!$is_two_step && count($current_progression_links) > 0){
                //Archive previous progression links since new one was logged:
                foreach($current_progression_links as $key=>$ln){

                    $this->Links_model->ln_update($ln['ln_id'], array(
                        'ln_parent_link_id' => $new_progression_link['ln_id'],
                        'ln_status_entity_id' => 6173, //Link Removed
                    ), $en_id);

                    //Remove from array:
                    unset($current_progression_links[$key]);
                }
            }

            if(!$is_two_step){
                $made_published_progress = true;
            }

            //Add new progression link:
            array_push($current_progression_links, $new_progression_link);

        }



        //Let's analyse the progress made so far to better understand how to deal with this step:
        $user_can_skip = ( $is_two_step || $has_children ); //Assume TRUE unless proven otherwise...
        if($user_can_skip){
            foreach($current_progression_links as $current_progression_link){
                //Also make sure this was NOT an automated progression because there is no point in skipping those:
                if(!$has_children && in_array($current_progression_link['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */) && !in_array($current_progression_link['ln_type_entity_id'], $this->config->item('en_ids_6274'))){
                    $user_can_skip = false;
                    break;
                }
            }
        }









        /*
         *
         * Check Conditional Steps in HTML
         * Action Plan Webview only
         *
         * */
        if(!$fb_messenger_format){

            $unlocked_steps = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'ln_type_entity_id' => 6140, //Action Plan Conditional Step Unlocked
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ), array('in_child'), 0);

            //Did we have any steps unlocked?
            if(count($unlocked_steps) > 0){
                //Yes! Show them:
                $next_step_message .= '<div class="list-group" style="margin:0 0 0 0;">';
                foreach($unlocked_steps as $unlocked_step){
                    //Add HTML step to UI:
                    $next_step_message .= echo_actionplan_step_child($en_id, $unlocked_step, $unlocked_step['ln_status_entity_id'], true);
                }
                $next_step_message .= '</div>';
            }

        }











        /*
         *
         * Now let's see the intent type (AND or OR)
         * and also count its children to see how
         * we would need to advance the user.
         *
         * */

        //Do we have any requirements?
        if ($completion_req_note && !$made_published_progress) {

            //They still need to complete:
            $next_step_message .= $completion_req_note;

        } elseif($in_is_or && $has_children /* Otherwise who cares */){


            //Prep variables:
            $too_many_children = ( $fb_messenger_format && count($in__children) > 10);


            if($fb_messenger_format){

                /*
                 *
                 * Let's see if we need to cleanup the OR answer
                 * index by merging the answer response quick replies
                 * (See Github Issue 2234 for more details)
                 *
                 * */

                $answer_referencing = array(); //Start with nothing...
                foreach ($in__messages as $message_ln) {
                    //Let's see if we can find a reference:
                    for ($num = 1; $num <= 10; $num++) {
                        if(substr_count($message_ln['ln_content'] , $num.'. ')==1 || substr_count($message_ln['ln_content'] , $num.".\n")==1){
                            //Make sure we have have the previous number:
                            if($num==1 || in_array(($num-1),$answer_referencing)){
                                array_push($answer_referencing, $num);
                            }
                        }
                    }
                }

            } else {

                $next_step_message .= '<div class="list-group" style="margin-top:10px;">';

            }

            //List OR child answers:
            foreach ($in__children as $key => $child_in) {


                //Is this selected?
                $was_selected       = ( $made_published_progress && $current_progression_links[0]['ln_child_intent_id']==$child_in['in_id'] );

                //Fetch history if selected:
                if($was_selected){
                    $child_progression_steps = $this->Links_model->ln_fetch(array(
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
                        'ln_miner_entity_id' => $en_id,
                        'ln_parent_intent_id' => $current_progression_links[0]['ln_child_intent_id'],
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    ));
                }


                if($fb_messenger_format){

                    if(!in_array(($key+1), $answer_referencing)){
                        $next_step_message .= "\n\n" . ($key+1).'. '.echo_in_outcome($child_in['in_outcome'], true);
                    }

                    //Add answer options to Quick Reply:
                    if(!$too_many_children){
                        array_push($next_step_quick_replies, array(
                            'content_type' => 'text',
                            'title' => ($key+1),
                            'payload' => 'ANSWERQUESTION_' . $ins[0]['in_id'] . '_' . $child_in['in_id'],
                        ));
                    }

                } else {

                    if(!$made_published_progress){

                        //Need to select answer:
                        $next_step_message .= '<a href="/messenger/actionplan_answer_question/' . $en_id . '/' . $ins[0]['in_id'] . '/' . $child_in['in_id'] . '/' . md5($this->config->item('actionplan_salt') . $child_in['in_id'] . $ins[0]['in_id'] . $en_id) . '" class="list-group-item">';

                    } elseif($was_selected){

                        //This was selected:
                        $next_step_message .= '<a href="/actionplan/'.$child_in['in_id'] . '" class="list-group-item">';

                    } else {

                        //This was NOT selected and nothing else has been selected yet:
                        $next_step_message .= '<span class="list-group-item" style="text-decoration: line-through;">';

                    }


                    if($was_selected){

                        //Selected Icon:
                        $next_step_message .= '<i class="fas fa-check-circle"></i> ';

                    } else {

                        //Not selected icon:
                        $next_step_message .= '<i class="far fa-circle"></i> ';

                    }

                    //Add to answer list:
                    $next_step_message .= echo_in_outcome($child_in['in_outcome']);

                }


                //HTML?
                if(!$fb_messenger_format){

                    if($was_selected) {
                        //Status Icon:
                        $next_step_message .= '&nbsp;' . echo_en_cache('en_all_6186' /* Link Statuses */, (count($child_progression_steps) > 0 ? $child_progression_steps[0]['ln_status_entity_id'] : 6174 /* Link New */), false, null);
                    }

                    //Close tags:
                    if(!$made_published_progress || $was_selected){

                        //Simple right icon
                        $next_step_message .= '<span class="pull-right" style="margin-top: -6px;">';
                        $next_step_message .= '<span class="badge badge-primary"><i class="fas fa-angle-right"></i>&nbsp;</span>';
                        $next_step_message .= '</span>';

                        $next_step_message .= '</a>';

                    } else {

                        $next_step_message .= '</span>';

                    }
                }
            }

            if(!$fb_messenger_format){
                $next_step_message .= '</div>';
            } else {
                if($too_many_children) {
                    //Give instructions on how to select path:
                    $next_step_message .= "\n\n" . 'I recommend you to skip';
                }
            }

        } elseif($has_children && !$in_is_or /* AND Children */){

            //Do we have 2 or more children?
            $max_and_list = 7;
            $has_multiple_children = (count($in__children) > 1);

            //Give more context for Messenger only:
            if($fb_messenger_format && $has_multiple_children){
                //Multiple next steps:
                $next_step_message .= 'There are ' . count($in__children) . ' steps to ' . echo_in_outcome($ins[0]['in_outcome'], true, true);
            }


            //List AND children:
            if($has_multiple_children || !$fb_messenger_format){
                $key = 0;
                foreach ($in__children as $child_in) {

                    if($key==0){
                        if($fb_messenger_format){
                            $next_step_message .= ':';
                        } else {
                            $next_step_message .= '<div class="list-group" style="margin-top:10px;">';
                        }
                    }

                    //We know that the $next_step_message length cannot surpass the limit defined by fb_max_message variable!
                    //make sure message is within range:
                    if ($fb_messenger_format && ($key >= $max_and_list || strlen($next_step_message) > ($this->config->item('fb_max_message') - 150))) {
                        //We cannot add any more, indicate truncating:
                        $remainder = count($in__children) - $max_and_list;
                        $next_step_message .= "\n\n" . '... plus ' . $remainder . ' more step' . echo__s($remainder) . '.';
                        break;
                    }


                    //Fetch progression data:
                    $child_progression_steps = $this->Links_model->ln_fetch(array(
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
                        'ln_miner_entity_id' => $en_id,
                        'ln_parent_intent_id' => $child_in['in_id'],
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    ));


                    if(!$fb_messenger_format){

                        //Add HTML step to UI:
                        $next_step_message .= echo_actionplan_step_child($en_id, $child_in, (count($child_progression_steps) > 0 ? $child_progression_steps[0]['ln_status_entity_id'] : 6174 /* Link New */ ));

                    } else {

                        //Add simple message:
                        $next_step_message .= "\n\n" . ($key + 1) . '. ' . echo_in_outcome($child_in['in_outcome'], $fb_messenger_format);

                    }

                    $key++;
                }

                if(!$fb_messenger_format && $key > 0){
                    //Close the HTML tag we opened:
                    $next_step_message .= '</div>';
                }
            }
        }






        /*
         *
         * Perform on-complete checks if we have
         * JUST made progress on this step
         *
         * */
        if(isset($new_progression_link['ln_status_entity_id']) && in_array($new_progression_link['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)){

            //Process on-complete automations:
            $on_complete_messages = $this->Actionplan_model->actionplan_completion_checks($en_id, $ins[0], false, $step_progress_made);

            if($step_progress_made && count($on_complete_messages) > 0){
                //Add on-complete messages (if any) to the current messages:
                $in__messages = array_merge($in__messages, $on_complete_messages);
            }
        }








        /*
         *
         * Call to Action
         *
         * We've did everything we had to do for the current
         * step, now let's see what's next and how should
         * we move forward...
         *
         * */

        //NEXT? Only possible if NOT a 2-step progress OR if progress has been made:
        if(!$is_two_step || $made_published_progress){

            $next_in_id = 0;
            if(!$has_children){
                //Let's see if we have a next step:
                $next_in_id = $this->Actionplan_model->actionplan_step_next_go($en_id, false);
            }

            if($has_children || $next_in_id>0){
                //Option to go next:
                if($fb_messenger_format){

                    array_push($next_step_quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'GONEXT',
                    ));

                } else {

                    $next_step_message .= '<div style="margin: 15px 0 0;"><a href="/messenger/actionplan/next" class="btn btn-md btn-primary">Next Step <i class="fas fa-angle-right"></i></a></div>';

                }
            } else {
                //No next step found! This must be it...
                $next_step_message = 'This was your final step 🎉🎉🎉';
                $recommend_recommend = true;
            }
        }

        //SKIP?
        if($user_can_skip) {
            //Give option to skip:
            if($fb_messenger_format){

                //Give option to skip User Intent:
                array_push($next_step_quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Skip',
                    'payload' => 'SKIP-ACTIONPLAN_skip-initiate_' . $ins[0]['in_id'],
                ));

            } else {

                $next_step_message .= '<div style="font-size: 0.7em; margin-top: 10px;">Or <a href="javascript:void(0);" onclick="actionplan_skip_steps(' . $en_id . ', ' . $ins[0]['in_id'] . ')"><u>Skip</u></a>.</div>';

            }
        }










        /*
         *
         * Let's start dispatch Messenger messages
         *
         * */
        $compile_html_message = null; //Will be useful only IF $fb_messenger_format=FALSE
        $last_message_accepts_quick_replies = false; //Assume FALSE unless proven otherwise...
        foreach ($in__messages as $count => $message_ln) {

            //Since we can only append quick replies to text messages, let's see what is happening here:
            $is_last_message = ( $count == (count($in__messages)-1) );
            //No entity reference and no /link command in order to accept quick replies...
            if($is_last_message && ( !isset($message_ln['ln_parent_entity_id']) || $message_ln['ln_parent_entity_id']==0 ) && substr_count($message_ln['ln_content'], '/link')==0){
                //Since there is no entity reference we can append our message here:
                $last_message_accepts_quick_replies = true;
            }

            $compile_html_message .= $this->Communication_model->dispatch_message(
                $message_ln['ln_content'],
                array('en_id' => $en_id),
                $fb_messenger_format,
                //This is when we have messages and need to append the "Next" Quick Reply to the last message:
                ( $last_message_accepts_quick_replies && count($next_step_quick_replies) > 0 ? $next_step_quick_replies : array() ),
                array(
                    'ln_parent_intent_id' => $ins[0]['in_id'],
                )
            );
        }
        if($fb_messenger_format) {

            if(strlen($next_step_message) > 0 || (!$last_message_accepts_quick_replies && count($next_step_quick_replies) > 0)){
                //Send messages over Messenger IF we have a message
                $this->Communication_model->dispatch_message(
                    ( strlen($next_step_message) > 0 ? $next_step_message : echo_random_message('goto_next') ),
                    array('en_id' => $en_id),
                    true,
                    $next_step_quick_replies,
                    array(
                        'ln_parent_intent_id' => $ins[0]['in_id'], //Focus Intent
                    )
                );
            }

            if($recommend_recommend){
                //List Recommended Intents and let them choose:
                $this->Communication_model->recommend_intents($en_id);
            }

        }







        //Return data:
        return array(
            'status' => 1,
            'message' => 'Success',

            //Do we need to return the HTML UI?
            'html_messages' => ( $fb_messenger_format ? null : $compile_html_message . '<div class="msg" style="margin-top: 15px;">'.nl2br($next_step_message).'</div>' ),
            'progression_links' => ( $fb_messenger_format ? null : $current_progression_links ),
        );

    }

    function actionplan_completion_marks($en_id, $in, $top_level = true)
    {

        //Fetch/validate Action Plan Common Steps:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){

            //Should not happen, log error:
            $this->Links_model->ln_create(array(
                'ln_content' => 'actionplan_completion_marks() Detected user Action Plan without in__metadata_common_steps value!',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $en_id,
                'ln_parent_intent_id' => $in['in_id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            //Generic assessment marks stats:
            'steps_question_count' => 0, //The parent intent
            'steps_marks_min' => 0,
            'steps_marks_max' => 0,

            //User answer stats:
            'steps_answered_count' => 0, //How many they have answered so far
            'steps_answered_marks' => 0, //Indicates completion score

            //Calculated at the end:
            'steps_answered_score' => 0, //Used to determine which label to be unlocked...
        );


        //Fetch expansion steps recursively, if any:
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0){

            //We need expansion steps (OR Intents) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_in_ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($in_metadata['in__metadata_expansion_steps'] as $question_in_id => $answers_in_ids ){

                //Calculate local min/max marks:
                array_push($question_in_ids, $question_in_id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->Links_model->ln_fetch(array(
                    'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_entity_id' => 4228, //Intent Link Fixed Steps
                    'ln_parent_intent_id' => $question_in_id,
                    'ln_child_intent_id IN (' . join(',', $answers_in_ids) . ')' => null, //Limit to cached answers
                ), array('in_child')) as $in_answer){

                    //Extract Link Metadata:
                    $possible_answer_metadata = unserialize($in_answer['ln_metadata']);

                    //Assign to this question:
                    $answer_marks_index[$in_answer['in_id']] = ( isset($possible_answer_metadata['tr__assessment_points']) ? intval($possible_answer_metadata['tr__assessment_points']) : 0 );

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$in_answer['in_id']] < $local_min){
                        $local_min = $answer_marks_index[$in_answer['in_id']];
                    }
                    if(is_null($local_max) || $answer_marks_index[$in_answer['in_id']] > $local_max){
                        $local_max = $answer_marks_index[$in_answer['in_id']];
                    }
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }
                if(!is_null($local_max)){
                    $metadata_this['steps_marks_max'] += $local_max;
                }
            }



            //Now let's check user answers to see what they have done:
            foreach($this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $en_id, //Belongs to this User
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
                'ln_parent_intent_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )) as $expansion_in) {

                //Addup data for this intent:
                $metadata_this['steps_answered_count'] += 1;

                //Calculate if answered:
                if($expansion_in['ln_type_entity_id']==6157 /* Action Plan Question Answered */){

                    //Fetch intent data:
                    $ins = $this->Intents_model->in_fetch(array(
                        'in_id' => $expansion_in['ln_child_intent_id'],
                        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                    ));

                    if(count($ins) > 0){
                        //Fetch recursive:
                        $recursive_stats = $this->Actionplan_model->actionplan_completion_marks($en_id, array_merge($expansion_in, $ins[0]), false);
                        $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];

                        $this_answer_marks = $answer_marks_index[$expansion_in['ln_child_intent_id']];
                        $metadata_this['steps_answered_marks'] += $this_answer_marks + $recursive_stats['steps_answered_marks'];
                    }
                }
            }
        }


        if($top_level && $metadata_this['steps_answered_count'] > 0){
            //See assessment summary:
            $metadata_this['steps_answered_score'] = floor( ($metadata_this['steps_answered_marks'] - $metadata_this['steps_marks_min']) / ( $metadata_this['steps_marks_max'] - $metadata_this['steps_marks_min'] ) * 100 );
        }


        //Return results:
        return $metadata_this;

    }

    function actionplan_completion_progress($en_id, $in, $top_level = true)
    {

        //Fetch/validate Action Plan Common Steps:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){
            //Since it's not there yet we assume the intent it self only!
            $in_metadata['in__metadata_common_steps'] = array($in['in_id']);
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Count totals:
        $common_totals = $this->Intents_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array(), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_completion_seconds) as total_seconds');

        //Count completed for user:
        $common_completed = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
            'ln_miner_entity_id' => $en_id, //Belongs to this User
            'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_parent'), 0, 0, array(), 'COUNT(in_id) as completed_steps, SUM(in_completion_seconds) as completed_seconds');

        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_steps']),
            'steps_completed' => intval($common_completed[0]['completed_steps']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );


        //Expansion Steps Recursive
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0){

            //Now let's check user answers to see what they have done:
            foreach($this->Links_model->ln_fetch(array(
                'ln_type_entity_id' => 6157, //Action Plan Question Answered
                'ln_miner_entity_id' => $en_id, //Belongs to this User
                'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_child_intent_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_steps'])) . ')' => null,
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            ), array('in_child')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->Actionplan_model->actionplan_completion_progress($en_id, $expansion_in, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];
            }
        }


        //Expansion steps Recursive
        if(isset($in_metadata['in__metadata_expansion_conditional']) && count($in_metadata['in__metadata_expansion_conditional']) > 0){

            //Now let's check if user has unlocked any Miletones:
            foreach($this->Links_model->ln_fetch(array(
                'ln_type_entity_id' => 6140, //Action Plan Conditional Step Unlocked
                'ln_miner_entity_id' => $en_id, //Belongs to this User
                'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_child_intent_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_conditional'])) . ')' => null,
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            ), array('in_child')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->Actionplan_model->actionplan_completion_progress($en_id, $expansion_in, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];

            }
        }


        if($top_level){

            /*
             *
             * Completing an Action Plan depends on two factors:
             *
             * 1) number of steps (some may have 0 time estimate)
             * 2) estimated seconds (usual ly accurate)
             *
             * To increase the accurate of our completion % function,
             * We would also assign a default time to the average step
             * so we can calculate more accurately even if none of the
             * steps have an estimated time.
             *
             * */

            //Set default seconds per step:
            $step_default_seconds = 60;

            //Calculate completion rate based on estimated time cost:
            $metadata_this['completion_percentage'] = floor( ($metadata_this['seconds_completed']+($step_default_seconds*$metadata_this['steps_completed'])) / ($metadata_this['seconds_total']+($step_default_seconds*$metadata_this['steps_total'])) * 100 );

        }


        //Return results:
        return $metadata_this;

    }


    function actionplan_intention_ids($en_id){
        //Simply returns all the intention IDs for a user's Action Plan:
        $user_intentions_ids = array();
        foreach($this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_parent'), 0) as $user_in){
            array_push($user_intentions_ids, intval($user_in['in_id']));
        }
        return $user_intentions_ids;
    }

}