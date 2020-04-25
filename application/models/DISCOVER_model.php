<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class DISCOVER_model extends CI_Model
{

    /*
     *
     * Player related database functions
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function discover_next_find($en_id, $in, $first_step = true){

        /*
         *
         * Searches within a user DISCOVER LIST to find
         * first incomplete step.
         *
         * */

        $in_metadata = unserialize($in['in_metadata']);

        //Make sure of no terminations first:
        $check_termination_answers = array();

        if(count($in_metadata['in__metadata_expansion_steps']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_steps']));
        }
        if(count($in_metadata['in__metadata_expansion_conditional']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_conditional']));
        }
        if(count($check_termination_answers) > 0 && count($this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id' => 7492, //TERMINATE
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',' , $check_termination_answers) . ')' => null, //All possible answers that might terminate...
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            ))) > 0){
            return -1;
        }



        foreach(array_flatten($in_metadata['in__metadata_common_steps']) as $common_step_in_id){

            //Is this an expansion step?
            $is_expansion = isset($in_metadata['in__metadata_expansion_steps'][$common_step_in_id]);
            $is_condition = isset($in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]);

            //Have they completed this?
            if($is_expansion){

                //First fetch all possible answers based on correct order:
                $found_expansion = 0;
                foreach ($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                    'ln_type_source_id' => 4228, //Idea Link Regular Discovery
                    'ln_previous_idea_id' => $common_step_in_id,
                ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $ln){

                    //See if this answer was selected:
                    if(count($this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINK
                        'ln_previous_idea_id' => $common_step_in_id,
                        'ln_next_idea_id' => $ln['in_id'],
                        'ln_creator_source_id' => $en_id, //Belongs to this User
                    )))){

                        $found_expansion++;

                        //Yes was answered, see if it's completed:
                        if(!count($this->LEDGER_model->ln_fetch(array(
                            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                            'ln_type_source_id IN (' . join(',' , $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
                            'ln_creator_source_id' => $en_id, //Belongs to this User
                            'ln_previous_idea_id' => $ln['in_id'],
                        )))){

                            //Answer is not completed, go there:
                            return $ln['in_id'];

                        } else {

                            //Answer previously completed, see if there is anyting else:
                            $found_in_id = $this->DISCOVER_model->discover_next_find($en_id, $ln, false);
                            if($found_in_id != 0){
                                return $found_in_id;
                            }

                        }
                    }
                }

                if(!$found_expansion){
                    return $common_step_in_id;
                }

            } elseif($is_condition){

                //See which path they got unlocked, if any:
                foreach($this->LEDGER_model->ln_fetch(array(
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                    'ln_creator_source_id' => $en_id, //Belongs to this User
                    'ln_previous_idea_id' => $common_step_in_id,
                    'ln_next_idea_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]) . ')' => null,
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                ), array('in_next')) as $unlocked_condition){

                    //Completed step that has OR expansions, check recursively to see if next step within here:
                    $found_in_id = $this->DISCOVER_model->discover_next_find($en_id, $unlocked_condition, false);

                    if($found_in_id != 0){
                        return $found_in_id;
                    }

                }

            } elseif(!count($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id IN (' . join(',' , $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
                    'ln_creator_source_id' => $en_id, //Belongs to this User
                    'ln_previous_idea_id' => $common_step_in_id,
                )))){

                //Not completed yet, this is the next step:
                return $common_step_in_id;

            }

        }


        //If not part of the discovery list, go to discovery idea
        if($first_step){
            $player_discover_ids = $this->DISCOVER_model->discover_ids($en_id);
            if(!in_array($in['in_id'], $player_discover_ids)){
                foreach ($this->IDEA_model->in_recursive_parents($in['in_id']) as $grand_parent_ids) {
                    if (array_intersect($grand_parent_ids, $player_discover_ids)) {
                        foreach($grand_parent_ids as $parent_in_id){
                            $ins = $this->IDEA_model->in_fetch(array(
                                'in_id' => $parent_in_id,
                                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                            ));
                            if(count($ins)){
                                $found_in_id = $this->DISCOVER_model->discover_next_find($en_id, $ins[0], false);
                                if($found_in_id != 0){
                                    return $found_in_id;
                                }
                            }
                        }
                    }
                }
            }
        }



        //Really not found:
        return 0;

    }

    function discover_next_go($en_id, $advance_step, $send_title_message = false)
    {

        /*
         *
         * Searches for the next DISCOVER LIST step
         * and advance it IF $advance_step = TRUE
         *
         * */

        $player_discoveries = $this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ), array('in_previous'), 0, 0, array('ln_order' => 'ASC'));

        if(count($player_discoveries) == 0){

            if($advance_step){

                $this->COMMUNICATION_model->comm_message_send(
                    'You have no ideas in your discovery list yet.',
                    array('en_id' => $en_id),
                    true
                );

                //DISCOVER RECOMMENDATIONS
                $this->COMMUNICATION_model->comm_message_send(
                    echo_platform_message(12697),
                    array('en_id' => $en_id),
                    true
                );

            }

            //No DISCOVER LISTs found!
            return 0;

        }


        //Loop through DISCOVER LIST Ideas and see what's next:
        foreach($player_discoveries as $user_in){

            //Find first incomplete step for this DISCOVER LIST Idea:
            $next_in_id = $this->DISCOVER_model->discover_next_find($en_id, $user_in);

            if($next_in_id < 0){

                //We need to terminate this:
                $this->DISCOVER_model->discover_delete($en_id, $user_in['in_id'], 7757); //MENCH REMOVED BOOKMARK
                break;

            } elseif($next_in_id > 0){

                //We found the next incomplete step, return:
                break;

            }
        }

        if($advance_step && $next_in_id >= 0 /* NOT If it was terminated... */){

            //Did we find a next step?
            if($next_in_id > 0){

                if($send_title_message){

                    //Fetch and append the title to be more informative:

                    //Yes, we do have a next step, fetch it and give user more details:
                    $next_step_ins = $this->IDEA_model->in_fetch(array(
                        'in_id' => $next_in_id,
                    ));

                    $this->COMMUNICATION_model->comm_message_send(
                        echo_platform_message(12692) . $next_step_ins[0]['in_title'],
                        array('en_id' => $en_id),
                        true
                    );

                }

                //Yes, communicate it:
                $this->DISCOVER_model->discover_echo($next_in_id, array('en_id' => $en_id), true);

            } else {

                //Inform user that they are now complete with all steps:
                $this->COMMUNICATION_model->comm_message_send(
                    'You completed your entire DISCOVERY LIST',
                    array('en_id' => $en_id),
                    true
                );

                //DISCOVER RECOMMENDATIONS
                $this->COMMUNICATION_model->comm_message_send(
                    echo_platform_message(12697),
                    array('en_id' => $en_id),
                    true
                );

            }
        }

        //Return next step Idea or false:
        return intval($next_in_id);

    }


    function discover_focus($en_id){

        /*
         *
         * A function that goes through the DISCOVER LIST
         * and finds the top-priority that the user
         * is currently working on.
         *
         * */

        $top_priority_in = false;
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ), array('in_previous'), 0, 0, array('ln_order' => 'ASC')) as $actionplan_in){

            //See progress rate so far:
            $completion_rate = $this->DISCOVER_model->discover_completion_progress($en_id, $actionplan_in);

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

    function discover_delete($en_id, $in_id, $stop_method_id, $stop_feedback = null){


        if(!in_array($stop_method_id, $this->config->item('en_ids_6150') /* DISCOVER LIST Idea Completed */)){
            return array(
                'status' => 0,
                'message' => 'Invalid stop method',
            );
        }

        //Validate idea to be deleted:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        if (count($ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea',
            );
        }

        //Go ahead and delete from DISCOVER LIST:
        $player_discoveries = $this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_previous_idea_id' => $in_id,
        ));
        if(count($player_discoveries) < 1){
            return array(
                'status' => 0,
                'message' => 'Could not locate DISCOVER LIST',
            );
        }

        //Delete Bookmark:
        foreach($player_discoveries as $ln){
            $this->LEDGER_model->ln_update($ln['ln_id'], array(
                'ln_content' => $stop_feedback,
                'ln_status_source_id' => 6173, //DELETED
            ), $en_id, $stop_method_id);
        }

        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }

    function discover_start($en_id, $in_id, $recommender_in_id = 0){

        //Validate Idea ID:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ));
        if (count($ins) != 1) {
            return false;
        }


        //Make sure not previously added to this User's DISCOVER LIST:
        if(!count($this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $in_id,
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            )))){

            //Not added to their discovery list so far, let's go ahead and add it:
            $in_rank = 1;
            $actionplan = $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => ( $recommender_in_id > 0 ? 7495 /* User Idea Recommended */ : 4235 /* User Idea Set */ ),
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id' => $ins[0]['in_id'], //The Idea they are adding
                'ln_next_idea_id' => $recommender_in_id, //Store the recommended idea
                'ln_order' => $in_rank, //Always place at the top of their discovery list
            ));

            //Mark as discovered if possible:
            if($ins[0]['in_type_source_id']==6677){
                $this->DISCOVER_model->discover_is_complete($ins[0], array(
                    'ln_type_source_id' => 4559, //DISCOVER MESSAGES
                    'ln_creator_source_id' => $en_id,
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ));
            }

            //Move other ideas down in the discovery list:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_id !=' => $actionplan['ln_id'], //Not the newly added idea
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_creator_source_id' => $en_id, //Belongs to this User
            ), array(''), 0, 0, array('ln_order' => 'ASC')) as $current_ins){

                //Increase rank:
                $in_rank++;

                //Update order:
                $this->LEDGER_model->ln_update($current_ins['ln_id'], array(
                    'ln_order' => $in_rank,
                ), $en_id, 10681 /* Ideas Ordered Automatically  */);
            }

        }

        return true;

    }




    function discover_completion_recursive_up($en_id, $in, $is_bottom_level = true){

        /*
         *
         * Let's see how many steps get unlocked:
         *
         * https://mench.com/source/6410
         *
         * */


        //First let's make sure this entire Idea completed by the user:
        $completion_rate = $this->DISCOVER_model->discover_completion_progress($en_id, $in);


        if($completion_rate['completion_percentage'] < 100){
            //Not completed, so can't go further up:
            return array();
        }


        //Look at Conditional Idea Links ONLY at this level:
        $in_metadata = unserialize($in['in_metadata']);
        if(isset($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) && count($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) > 0){

            //Make sure previous link unlocks have NOT happened before:
            $existing_expansions = $this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id' => 6140, //DISCOVER UNLOCK LINK
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $in['in_id'],
                'ln_next_idea_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ));
            if(count($existing_expansions) > 0){

                //Oh we do have an expansion that previously happened! So skip this:
                /*
                 * This was being triggered but I am not sure if its normal or not!
                 * For now will comment out so no errors are logged
                 * TODO: See if you can make sense of this section. The question is
                 * if we would ever try to process a conditional step twice? If it
                 * happens, is it an error or not, and should simply be ignored?
                 *
                $this->LEDGER_model->ln_create(array(
                    'ln_previous_idea_id' => $in['in_id'],
                    'ln_next_idea_id' => $existing_expansions[0]['ln_next_idea_id'],
                    'ln_content' => 'completion_recursive_up() detected duplicate Label Expansion entries',
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_creator_source_id' => $en_id,
                ));
                */

                return array();

            }


            //Yes, Let's calculate user's score for this idea:
            $user_marks = $this->DISCOVER_model->discover_completion_marks($en_id, $in);





            //Detect potential conditional steps to be Unlocked:
            $found_match = 0;
            $locked_links = $this->LEDGER_model->ln_fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id' => 4229, //Idea Link Locked Discovery
                'ln_previous_idea_id' => $in['in_id'],
                'ln_next_idea_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ), array('in_next'), 0, 0);


            foreach ($locked_links as $locked_link) {

                //See if it unlocks any of these ranges defined in the metadata:
                $ln_metadata = unserialize($locked_link['ln_metadata']);

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

                    //Unlock DISCOVER LIST:
                    $this->LEDGER_model->ln_create(array(
                        'ln_type_source_id' => 6140, //DISCOVER UNLOCK LINK
                        'ln_creator_source_id' => $en_id,
                        'ln_previous_idea_id' => $in['in_id'],
                        'ln_next_idea_id' => $locked_link['in_id'],
                        'ln_metadata' => array(
                            'completion_rate' => $completion_rate,
                            'user_marks' => $user_marks,
                            'condition_ranges' => $locked_links,
                        ),
                    ));

                }
            }

            //We must have exactly 1 match by now:
            if($found_match != 1){
                $this->LEDGER_model->ln_create(array(
                    'ln_content' => 'completion_recursive_up() found ['.$found_match.'] routing logic matches!',
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_creator_source_id' => $en_id,
                    'ln_previous_idea_id' => $in['in_id'],
                    'ln_metadata' => array(
                        'completion_rate' => $completion_rate,
                        'user_marks' => $user_marks,
                        'conditional_ranges' => $locked_links,
                    ),
                ));
            }

        }


        //Now go up since we know there are more levels...
        if($is_bottom_level){

            //Fetch user ideas:
            $player_discover_ids = $this->DISCOVER_model->discover_ids($en_id);

            //Prevent duplicate processes even if on multiple parent ideas:
            $parents_checked = array();

            //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
            foreach ($this->IDEA_model->in_recursive_parents($in['in_id']) as $grand_parent_ids) {

                //Does this parent and its grandparents have an intersection with the user ideas?
                if(!array_intersect($grand_parent_ids, $player_discover_ids)){
                    //Parent idea is NOT part of their DISCOVER LIST:
                    continue;
                }

                //Let's go through until we hit their intersection
                foreach($grand_parent_ids as $p_id){

                    //Make sure not duplicated:
                    if(in_array($p_id , $parents_checked)){
                        continue;
                    }

                    array_push($parents_checked, $p_id);

                    //Fetch parent idea:
                    $parent_ins = $this->IDEA_model->in_fetch(array(
                        'in_id' => $p_id,
                        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                    ));

                    //Now see if this child completion resulted in a full parent completion:
                    if(count($parent_ins) > 0){

                        //Fetch parent completion:
                        $this->DISCOVER_model->discover_completion_recursive_up($en_id, $parent_ins[0], false);

                    }

                    //Terminate if we reached the DISCOVER LIST idea level:
                    if(in_array($p_id , $player_discover_ids)){
                        break;
                    }
                }
            }
        }


        return true;
    }


    function discover_unlock_locked_step($en_id, $in){

        /*
         * A function that starts from a locked idea and checks:
         *
         * 1. List users who have completed ALL/ANY (Depending on AND/OR Lock) of its children
         * 2. If > 0, then goes up recursively to see if these completions unlock other completions
         *
         * */

        if(!in_is_unlockable($in)){
            return array(
                'status' => 0,
                'message' => 'Not a valid locked idea type and status',
            );
        }


        $in__next = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            'ln_type_source_id' => 4228, //Idea Link Regular Discovery
            'ln_previous_idea_id' => $in['in_id'],
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC'));
        if(count($in__next) < 1){
            return array(
                'status' => 0,
                'message' => 'Idea has no child ideas',
            );
        }



        /*
         *
         * Now we need to determine idea completion method.
         *
         * It's one of these two cases:
         *
         * AND Ideas are completed when all their children are completed
         *
         * OR Ideas are completed when a single child is completed
         *
         * */
        $requires_all_children = ( $in['in_type_source_id'] == 6914 /* AND Lock, meaning all children are needed */ );

        //Generate list of users who have completed it:
        $qualified_completed_users = array();

        //Go through children and see how many completed:
        foreach($in__next as $count => $child_in){

            //Fetch users who completed this:
            if($count==0){

                //Always add all the first users to the full list:
                $qualified_completed_users = $this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //DISCOVER COIN
                    'ln_previous_idea_id' => $child_in['in_id'],
                ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

                if($requires_all_children && count($qualified_completed_users)==0){
                    //No users found that would meet all children requirements:
                    break;
                }

            } else {

                //2nd Update onwards, by now we must have a base:
                if($requires_all_children){

                    //Update list of qualified users:
                    $qualified_completed_users = $this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //DISCOVER COIN
                        'ln_previous_idea_id' => $child_in['in_id'],
                    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

                }

            }
        }

        if(count($qualified_completed_users) > 0){
            return array(
                'status' => 0,
                'message' => 'No users found to have completed',
            );
        }

    }



    function discover_is_complete($in, $insert_columns){

        //Log completion link:
        $new_link = $this->LEDGER_model->ln_create($insert_columns);

        //Process completion automations:
        $this->DISCOVER_model->discover_completion_recursive_up($insert_columns['ln_creator_source_id'], $in);

        return $new_link;

    }

    function discover_echo($in_id, $recipient_en, $push_message = false, $next_step_only = false){

        /*
         * Function to discover na Idea, it's messages,
         * and necessary inputs to complete it.
         *
         */


        //Fetch/Validate idea:

        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ));
        if (count($ins) < 1) {
            $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => ( isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0 ),
                'ln_content' => 'step_echo() invalid idea ID',
                'ln_previous_idea_id' => $in_id,
            ));

            if($push_message){
                $this->COMMUNICATION_model->comm_message_send(
                    'Invalid Idea ID',
                    $recipient_en,
                    true
                );
            } else {
                //HTML:

                echo '<div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Invalid Idea ID</div>';
            }
            return false;
        }


        //Validate Recipient, if specified:
        if(!isset($recipient_en['en_id'])){

            if($push_message){

                //We cannot have a guest user on Messenger:
                $this->LEDGER_model->ln_create(array(
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_content' => 'discover_coin() found guest user on Messenger',
                    'ln_previous_idea_id' => $in_id,
                ));
                return false;

            } else {

                //Guest on the web:
                $recipient_en['en_id'] = 0;
                $recipient_en['en_name'] = 'Stranger';

            }

        } elseif(!isset($recipient_en['en_name'])){

            //Fetch name:
            $ens = $this->SOURCE_model->en_fetch(array(
                'en_id' => $recipient_en['en_id'],
                'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Source Status Active
            ));

            if(count($ens)){
                $recipient_en = $ens[0];
            } else {
                $this->LEDGER_model->ln_create(array(
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_content' => 'discover_coin() could not locate source',
                    'ln_previous_idea_id' => $in_id,
                ));
                return false;
            }

        }



        //Fetch Messages
        $in__messages = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id' => 4231, //Idea Notes Messages
            'ln_next_idea_id' => $ins[0]['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //Fetch Children:
        $in__next = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            'ln_type_source_id' => 4228, //Idea Link Regular Discovery
            'ln_previous_idea_id' => $ins[0]['in_id'],
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC'));


        //Log View:
        $this->LEDGER_model->ln_create(array(
            'ln_creator_source_id' => $recipient_en['en_id'],
            'ln_type_source_id' => 7610, //Idea Viewed by User
            'ln_previous_idea_id' => $ins[0]['in_id'],
            'ln_order' => fetch_cookie_order('7610_'.$in_id),
        ));


        $in_discovery_list = false;
        if($recipient_en['en_id'] > 0){

            //Fetch entire discovery list:
            $player_discover_ids = $this->DISCOVER_model->discover_ids($recipient_en['en_id']);

            if(in_array($ins[0]['in_id'], $player_discover_ids)){
                $in_discovery_list = true;
            } else {

                //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
                foreach ($this->IDEA_model->in_recursive_parents($ins[0]['in_id']) as $grand_parent_ids) {

                    //Does this parent and its grandparents have an intersection with the user ideas?
                    if (array_intersect($grand_parent_ids, $player_discover_ids)) {
                        //Idea is part of their DISCOVER LIST:
                        $in_discovery_list = true;
                        break;
                    }
                }
            }
        }


        /*
         *
         * Determine next Discovery
         *
         */
        if(!$in_discovery_list){

            if($push_message){

                $this->COMMUNICATION_model->comm_message_send(
                    'Interested to discover ' . $ins[0]['in_title'] . '?',
                    $recipient_en,
                    $push_message,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Get Started',
                            'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'],
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'Cancel',
                            'payload' => 'SUBSCRIBE-REJECT',
                        ),
                    ),
                    array(
                        'ln_next_idea_id' => $ins[0]['in_id'],
                    )
                );

            } else {

                //IDEA TITLE
                echo '<h1 class="block-one"><span class="icon-block top-icon"><i class="fas fa-circle discover"></i></span><span class="title-block-lg">' . echo_in_title($ins[0]) . '</span></h1>';


                foreach ($in__messages as $message_ln) {
                    echo $this->COMMUNICATION_model->comm_message_send(
                        $message_ln['ln_content'],
                        $recipient_en,
                        $push_message
                    );
                }

                //OVERVIEW STATS
                //echo echo_in_tree_sources($ins[0]);

                $is_or = in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_6193'));

                if($is_or){
                    $all_child_featured = true;
                    foreach($in__next as $key => $child_in){
                        if(!in_array($child_in['in_status_source_id'], $this->config->item('en_ids_12138'))){
                            $all_child_featured = false;
                            break;
                        }
                    }
                } else {
                    $all_child_featured = false;
                }




                //Any Sub Topics?
                if(count($in__next) > 0){

                    //List Children:
                    echo '<div id="discoverScroll no-height">&nbsp;</div>';
                    $common_prefix = in_calc_common_prefix($in__next, 'in_title');

                    echo '<div class="'.( !$all_child_featured ? ' discover_topics hidden ' : '' ).'">';

                    //echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>'.( !$all_child_featured ? 'IDEAS:' : 'SELECT:' ).'</div>';
                    echo '<div class="list-group">';
                    foreach($in__next as $key => $child_in){
                        echo echo_in_discover($child_in, $is_or, $common_prefix);
                    }
                    echo '</div>';
                    echo '</div>';

                }


                if(!$all_child_featured){

                    //Redirect to login page:
                    echo '<div class="inline-block margin-top-down discover-add pull-left"><a class="btn btn-discover" href="/discover/start/'.$ins[0]['in_id'].'">START <i class="fad fa-step-forward"></i></a></div>';

                    //Any Sub Topics?
                    if(count($in__next) > 0){

                        //Give option to review:
                        echo '<div class="inline-block margin-top-down discover-add discover_topics pull-left" style="margin-top:45px;">&nbsp;or&nbsp;<a href="javascript:void();" onclick="toggle_discover()"><i class="fas fa-plus-circle idea discover_topics"></i><i class="fas fa-minus-circle idea discover_topics hidden"></i> <u>Preview '.count($in__next).' Idea'.echo__s(count($in__next)).'</u></a></div>';

                    }

                }

                echo echo_in_contribute_btn($in_id);
                echo '<div class="doclear">&nbsp;</div>';

            }

            return true;
        }



        /*
         * Previously in source's discovery list...
         *
         */





        //Fetch progress history:
        $discover_completes = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
            'ln_creator_source_id' => $recipient_en['en_id'],
            'ln_previous_idea_id' => $ins[0]['in_id'],
        ));

        $qualify_for_autocomplete = ( isset($_GET['check_if_empty']) && !count($in__next) || (count($in__next)==1 && $ins[0]['in_type_source_id'] == 6677)) && !count($in__messages) && !in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_12324'));


        //Is it incomplete & can it be instantly marked as complete?
        if (!count($discover_completes) && in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_12330'))) {

            //We might be able to complete it now:
            //It can, let's process it accordingly for each type within @12330
            if ($ins[0]['in_type_source_id'] == 6677 && $qualify_for_autocomplete) {

                //They should discover and then complete...
                array_push($discover_completes, $this->DISCOVER_model->discover_is_complete($ins[0], array(
                    'ln_type_source_id' => 4559, //DISCOVER MESSAGES
                    'ln_creator_source_id' => $recipient_en['en_id'],
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                )));

            } elseif (in_array($ins[0]['in_type_source_id'], array(6914,6907))) {

                //Reverse check answers to see if they have previously unlocked a path:
                $unlocked_connections = $this->LEDGER_model->ln_fetch(array(
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                    'ln_next_idea_id' => $ins[0]['in_id'],
                    'ln_creator_source_id' => $recipient_en['en_id'],
                ), array('in_previous'), 1);

                if(count($unlocked_connections) > 0){

                    //They previously have unlocked a path here!

                    //Determine DISCOVER COIN type based on it's connection type's parents that will hold the appropriate discover coin.
                    $discover_completion_type_id = 0;
                    foreach($this->config->item('en_all_12327') /* DISCOVER UNLOCKS */ as $en_id => $m){
                        if(in_array($unlocked_connections[0]['ln_type_source_id'], $m['m_parents'])){
                            $discover_completion_type_id = $en_id;
                            break;
                        }
                    }

                    //Could we determine the coin type?
                    if($discover_completion_type_id > 0){

                        //Yes, Issue coin:
                        array_push($discover_completes, $this->DISCOVER_model->discover_is_complete($ins[0], array(
                            'ln_type_source_id' => $discover_completion_type_id,
                            'ln_creator_source_id' => $recipient_en['en_id'],
                            'ln_previous_idea_id' => $ins[0]['in_id'],
                        )));

                    } else {

                        //Oooops, we could not find it, report bug:
                        $this->LEDGER_model->ln_create(array(
                            'ln_type_source_id' => 4246, //Platform Bug Reports
                            'ln_creator_source_id' => $recipient_en['en_id'],
                            'ln_content' => 'discover_coin() found idea connector ['.$unlocked_connections[0]['ln_type_source_id'].'] without a valid unlock method @12327',
                            'ln_previous_idea_id' => $ins[0]['in_id'],
                            'ln_parent_transaction_id' => $unlocked_connections[0]['ln_id'],
                        ));

                    }

                } else {

                    //Try to find paths to unlock:
                    $unlock_paths = $this->IDEA_model->in_unlock_paths($ins[0]);

                    //Set completion method:
                    if(!count($unlock_paths)){

                        //No path found:
                        array_push($discover_completes, $this->DISCOVER_model->discover_is_complete($ins[0], array(
                            'ln_type_source_id' => 7492, //TERMINATE
                            'ln_creator_source_id' => $recipient_en['en_id'],
                            'ln_previous_idea_id' => $ins[0]['in_id'],
                        )));


                    }
                }
            }
        }


        //Define communication variables:
        if(!$next_step_only){

            // % DONE
            $completion_rate = $this->DISCOVER_model->discover_completion_progress($recipient_en['en_id'], $ins[0]);
            $metadata = unserialize($ins[0]['in_metadata']);
            $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );


            if(!$push_message){

                //DISCOVER PREVIOUS (To be moved here using Javascript)
                echo '<div id="previous_final_position"></div>';


                //DISCOVER PROGRESS
                if($completion_rate['completion_percentage']>0){
                    echo '<div class="progress-bg no-horizonal-margin" title="You are '.$completion_rate['completion_percentage'].'% done as you have discover '.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' ideas'.( $has_time_estimate ? ' (Total Estimate '.echo_time_range($ins[0], true).')' : '' ).'"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
                } else {
                    //Replace with empty space:
                    echo '<div class="high5x">&nbsp;</div>';
                }

                //DISCOVER TITLE
                echo '<h1 class="block-one"><span class="icon-block top-icon"><i class="fas fa-circle discover" aria-hidden="true"></i></span><span class="title-block-lg">' . echo_in_title($ins[0]) . '</span></h1>';

            } else {

                $this->COMMUNICATION_model->comm_message_send(
                    'You are discovering: '.$ins[0]['in_title'],
                    $recipient_en,
                    $push_message
                );

            }

            echo '<div class="previous_discoveries">';
            foreach ($in__messages as $message_ln) {
                echo $this->COMMUNICATION_model->comm_message_send(
                    $message_ln['ln_content'],
                    $recipient_en,
                    $push_message
                );
            }
            echo '</div>';

        }



        if(count($discover_completes) && $qualify_for_autocomplete){
            //Move to the next one as there is nothing to do here:
            if($push_message){

                //Code later...

            } else {

                //JS Redirect asap:
                echo "<script> $(document).ready(function () { window.location = '/discover/next/' + in_loaded_id; }); </script>";

            }
        }




        //PREVIOUSLY UNLOCKED:
        if(!$push_message){

            $unlocked_steps = $this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                'ln_type_source_id' => 6140, //DISCOVER UNLOCK LINK
                'ln_creator_source_id' => $recipient_en['en_id'],
                'ln_previous_idea_id' => $ins[0]['in_id'],
            ), array('in_next'), 0);

            //Did we have any steps unlocked?
            if(count($unlocked_steps) > 0){
                echo_in_list($ins[0], $unlocked_steps, $recipient_en, $push_message, '<span class="icon-block"><i class="fas fa-lock-open"></i></span>UNLOCKED:', false);
            }

        }




        //LOCKED
        if (in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7309'))) {


            //Requirement lock
            if(!count($discover_completes) && !count($unlocked_connections) && count($unlock_paths)){

                //List Unlock paths:
                echo_in_list($ins[0], $unlock_paths, $recipient_en, $push_message, '<span class="icon-block"><i class="fad fa-step-forward"></i></span>SUGGESTED IDEAS:');

            }

            //List Children if any:
            echo_in_list($ins[0], $in__next, $recipient_en, $push_message,  null, ( $completion_rate['completion_percentage'] < 100 ));


        } elseif (in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7712'))){

            //SELECT ANSWER

            //Has no children:
            if(!count($in__next)){

                //Mark this as complete since there is no child to choose from:
                if(!count($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id IN (' . join(',' , $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
                    'ln_creator_source_id' => $recipient_en['en_id'],
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                )))){

                    array_push($discover_completes, $this->DISCOVER_model->discover_is_complete($ins[0], array(
                        'ln_type_source_id' => 4559, //DISCOVER MESSAGES
                        'ln_creator_source_id' => $recipient_en['en_id'],
                        'ln_previous_idea_id' => $ins[0]['in_id'],
                    )));

                }

                echo_in_next($ins[0]['in_id'], $recipient_en, $push_message);
                return true;

            } else {

                //First fetch answers based on correct order:
                $discover_answers = array();
                foreach ($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                    'ln_type_source_id' => 4228, //Idea Link Regular Discovery
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $ln){
                    //See if this answer was seleted:
                    if(count($this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINK
                        'ln_previous_idea_id' => $ins[0]['in_id'],
                        'ln_next_idea_id' => $ln['in_id'],
                        'ln_creator_source_id' => $recipient_en['en_id'],
                    )))){
                        array_push($discover_answers, $ln);
                    }
                }

                if(count($discover_answers) > 0){

                    if($push_message){



                    } else {

                        //In HTML Give extra option to change answer:

                        echo '<div class="selected_before">';

                        //List answers:
                        echo_in_list($ins[0], $discover_answers, $recipient_en, $push_message, '<span class="icon-block">&nbsp;</span>YOU ANSWERED:', true, '<div class="inline-block margin-top-down previous_discoveries pull-left">&nbsp;<a class="btn btn-discover" href="javascript:void(0);" onclick="$(\'.selected_before\').toggleClass(\'hidden\');"><i class="fas fa-pen-square"></i></a></div>');

                        echo '</div>';

                    }
                }


                if($push_message){


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
                        for ($num = 1; $num <= config_var(12124); $num++) {
                            if(substr_count($message_ln['ln_content'] , $num.'. ')==1 || substr_count($message_ln['ln_content'] , $num.".\n")==1){
                                //Make sure we have have the previous number:
                                if($num==1 || in_array(($num-1),$answer_referencing)){
                                    array_push($answer_referencing, $num);
                                }
                            }
                        }
                    }

                    $msg_quick_reply = array();

                    if ($ins[0]['in_type_source_id'] == 6684) {

                        //SELECT ONE
                        $quick_replies_allowed = ( count($in__next) <= config_var(12124) );
                        $message_content = 'Select one option to continue:'."\n\n";

                    } elseif ($ins[0]['in_type_source_id'] == 7231) {

                        //SELECT SOME
                        $quick_replies_allowed = ( count($in__next)==1 );
                        $message_content = 'Select one or more options to continue:'."\n\n";

                    }

                } else {

                    echo '<div class="selected_before '.( count($discover_answers)>0 ? 'hidden' : '' ).'">';
                    echo '<div class="previous_discoveries">';

                    //HTML:
                    if ($ins[0]['in_type_source_id'] == 6684) {

                        echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

                    } elseif ($ins[0]['in_type_source_id'] == 7231) {

                        echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE:</div>';

                    }

                    //Open for list to be printed:
                    echo '<div class="list-group list-answers" in_type_source_id="'.$ins[0]['in_type_source_id'].'">';

                }

                //Determine Prefix:
                $common_prefix = in_calc_common_prefix($in__next, 'in_title');


                //List children to choose from:
                foreach ($in__next as $key => $child_in) {

                    //Has this been previously selected?
                    $previously_selected = count($this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                        'ln_previous_idea_id' => $ins[0]['in_id'],
                        'ln_next_idea_id' => $child_in['in_id'],
                        'ln_creator_source_id' => $recipient_en['en_id'],
                    )));

                    if ($push_message) {

                        if(!in_array(($key+1), $answer_referencing)){
                            $message_content .= ($key+1).'. '.echo_in_title($child_in, $push_message, $common_prefix).( $previously_selected ? ' [Previously Selected]' : '' )."\n";
                        }

                        if($quick_replies_allowed){
                            array_push($msg_quick_reply, array(
                                'content_type' => 'text',
                                'title' => 'NEXT',
                                'payload' => 'ANSWERQUESTION_' . $ins[0]['in_id'] . '_' . $child_in['in_id'],
                            ));
                        }

                    } else {

                        echo '<a href="javascript:void(0);" onclick="select_answer('.$child_in['in_id'].')" is-selected="'.( $previously_selected ? 1 : 0 ).'" answered_ins="'.$child_in['in_id'].'" class="ln_answer_'.$child_in['in_id'].' answer-item list-group-item itemdiscover no-left-padding">';


                        echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                        echo '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="'.( $previously_selected ? 'fas' : 'far' ).' fa-circle discover"></i></td>';

                        echo '<td style="width: 100%; padding: 0 !important;">';
                        echo '<b class="montserrat idea-url" style="margin-left:0;">'.echo_in_title($child_in, false, $common_prefix).'</b>';
                        echo '</td>';

                        echo '</tr></table>';


                        echo '</a>';

                    }
                }


                if ($push_message) {

                    if(!$quick_replies_allowed){

                        if ($ins[0]['in_type_source_id'] == 6684) {

                            $message_content .= "\n\n".'Reply with a number between 1 - '.count($in__next).' to continue.';

                        } elseif ($ins[0]['in_type_source_id'] == 7231) {

                            $message_content .= "\n\n".'Reply with one or more numbers between 1 - '.count($in__next).' to continue (add space between). For example, to select the first option reply "1", or to select the first & ';

                            if(count($in__next) >= 3){
                                $message_content .= 'third option reply "1 3"';
                            } else {
                                $message_content .= 'second option reply "1 2"';
                            }
                        }
                    }

                    $this->COMMUNICATION_model->comm_message_send(
                        $message_content,
                        $recipient_en,
                        $push_message,
                        $msg_quick_reply
                    );

                } else {

                    //Close list:
                    echo '</div>';
                    echo '</div>';

                    echo '<div class="result-update margin-top-down"></div>';

                    echo echo_in_previous_discover($in_id, $recipient_en);

                    //Button to submit selection:
                    echo '<div class="inline-block margin-top-down previous_discoveries pull-left">'.( count($discover_answers)>0 ? '<a class="btn btn-discover" href="javascript:void(0);" onclick="$(\'.selected_before\').toggleClass(\'hidden\');"><i class="fas fa-times"></i></a>&nbsp;' : '' ).'<a class="btn btn-discover" href="javascript:void(0)" onclick="discover_answer()">'.( count($discover_answers)>0 ? 'UPDATE' : 'SELECT' ).' & NEXT <i class="fad fa-step-forward"></i></a></div>';

                    echo '</div>';

                }
            }

        } else {

            if ($ins[0]['in_type_source_id'] == 6677) {

                //DISCOVER ONLY

                //Next Ideas:
                echo_in_list($ins[0], $in__next, $recipient_en, $push_message);

            } elseif ($ins[0]['in_type_source_id'] == 6683) {

                //TEXT RESPONSE

                echo '<div class="previous_discoveries"><textarea class="border i_content padded discover_input" placeholder="Your Answer Here..." id="discover_text_answer">'.( count($discover_completes) ? trim($discover_completes[0]['ln_content']) : '' ).'</textarea></div>';

                echo '<div class="text_saving_result margin-top-down previous_discoveries"></div>';

                //Show Previous Button:
                echo echo_in_previous_discover($ins[0]['in_id'], $recipient_en);

                //Save/Upload & Next:
                echo '<div class="margin-top-down inline-block previous_discoveries pull-left"><a class="btn btn-discover" href="javascript:void(0);" onclick="discover_text_answer()">SAVE & NEXT <i class="fad fa-step-forward"></i></a>&nbsp;&nbsp;</div>';


                if(count($discover_completes)){
                    //Next Ideas:
                    echo_in_list($ins[0], $in__next, $recipient_en, $push_message, null,false);
                }

                echo '<script> $(document).ready(function () { autosize($(\'#discover_text_answer\')); $(\'#discover_text_answer\').focus(); }); </script>';


            } elseif (in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7751'))) {

                //FILE UPLOAD

                echo '<div class="playerUploader previous_discoveries">';
                echo '<form class="box boxUpload" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">';

                echo '<div class="file_saving_result">'.( count($discover_completes) ? '<div class="discover-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->COMMUNICATION_model->comm_message_send($discover_completes[0]['ln_content']).'</div>' : '' ).'</div>';

                echo '<input class="inputfile" type="file" name="file" id="fileType'.$ins[0]['in_type_source_id'].'" />';

                echo '</form>';
                echo '</div>';


                if(!count($discover_completes)) {

                    //Show Previous Button:
                    echo echo_in_previous_discover($ins[0]['in_id'], $recipient_en);

                    echo '<label class="btn btn-discover inline-block previous_discoveries pull-left" for="fileType'.$ins[0]['in_type_source_id'].'" data-toggle="tooltip" style="margin-right:10px;" title="Upload files up to ' . config_var(11063) . ' MB" data-placement="top"><i class="fad fa-cloud-upload-alt"></i> UPLOAD</label>';

                    echo '<div class="doclear">&nbsp;</div>';

                    //Show next here but keep hidden until file is uploaded:
                    echo '<div class="go_next_upload hidden inline-block previous_discoveries">';
                    echo_in_next($ins[0]['in_id'], $recipient_en, $push_message);
                    echo '</div>';

                } else {

                    //Next Ideas:
                    echo_in_list($ins[0], $in__next, $recipient_en, $push_message, null, true, true, '<label class="btn btn-discover inline-block previous_discoveries pull-left" for="fileType'.$ins[0]['in_type_source_id'].'" data-toggle="tooltip" style="margin-left:5px;" title="Upload files up to ' . config_var(11063) . ' MB" data-placement="top"><span class="icon-block"><i class="fad fa-cloud-upload-alt"></i></span><span class="show-max">REPLACE</span></label>');

                }

            } else {

                //UNKNOWN IDEA TYPE
                $this->LEDGER_model->ln_create(array(
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_creator_source_id' => $recipient_en['en_id'],
                    'ln_content' => 'step_echo() unknown idea type source ID ['.$ins[0]['in_type_source_id'].'] that could not be rendered',
                    'ln_previous_idea_id' => $in_id,
                ));

            }

        }

    }

    function discover_completion_marks($en_id, $in, $top_level = true)
    {

        //Fetch/validate DISCOVER LIST Common Ideas:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){

            //Should not happen, log error:
            $this->LEDGER_model->ln_create(array(
                'ln_content' => 'completion_marks() Detected user DISCOVER LIST without in__metadata_common_steps value!',
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $in['in_id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            //Generic assessment marks stats:
            'steps_question_count' => 0, //The parent idea
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

            //We need expansion steps (OR Ideas) to calculate question/answers:
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
                foreach($this->LEDGER_model->ln_fetch(array(
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id' => 4228, //Idea Link Regular Discovery
                    'ln_previous_idea_id' => $question_in_id,
                    'ln_next_idea_id IN (' . join(',', $answers_in_ids) . ')' => null, //Limit to cached answers
                ), array('in_next')) as $in_answer){

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
            $total_completion = $this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            ), array('in_next'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->DISCOVER_model->discover_completion_marks($en_id, $answer_in, false);

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['in_id']] + $recursive_stats['steps_answered_marks'];

            }
        }


        if($top_level && $metadata_this['steps_answered_count'] > 0){

            $divider = ( $metadata_this['steps_marks_max'] - $metadata_this['steps_marks_min'] ) * 100;

            if($divider > 0){
                //See assessment summary:
                $metadata_this['steps_answered_score'] = floor( ($metadata_this['steps_answered_marks'] - $metadata_this['steps_marks_min']) / $divider );
            } else {
                //See assessment summary:
                $metadata_this['steps_answered_score'] = 0;
            }

        }


        //Return results:
        return $metadata_this;

    }



    function discover_completion_progress($en_id, $in, $top_level = true)
    {

        if(!isset($in['in_metadata'])){
            return false;
        }

        //Fetch/validate DISCOVER LIST Common Ideas:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){
            //Since it's not there yet we assume the idea it self only!
            $in_metadata['in__metadata_common_steps'] = array($in['in_id']);
        }


        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);


        //Count totals:
        $common_totals = $this->IDEA_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_time_seconds) as total_seconds');


        //Count completed for user:
        $common_completed = $this->LEDGER_model->ln_fetch(array(
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
            'ln_creator_source_id' => $en_id, //Belongs to this User
            'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ), array('in_previous'), 0, 0, array(), 'COUNT(in_id) as completed_steps, SUM(in_time_seconds) as completed_seconds');


        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_steps']),
            'steps_completed' => intval($common_completed[0]['completed_steps']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );


        //Expansion Ideas Recursive
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0){

            //Now let's check user answers to see what they have done:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_next_idea_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_steps'])) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            ), array('in_next')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->DISCOVER_model->discover_completion_progress($en_id, $expansion_in, false);

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
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id' => 6140, //DISCOVER UNLOCK LINK
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_next_idea_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_conditional'])) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            ), array('in_next')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->DISCOVER_model->discover_completion_progress($en_id, $expansion_in, false);

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
             * Completing an DISCOVER LIST depends on two factors:
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
            $metadata_this['completion_percentage'] = 0;
            $step_default_seconds = config_var(12176);


            //Calculate completion rate based on estimated time cost:
            if($metadata_this['steps_total'] > 0 || $metadata_this['seconds_total'] > 0){
                $metadata_this['completion_percentage'] = intval(floor( ($metadata_this['seconds_completed']+($step_default_seconds*$metadata_this['steps_completed'])) / ($metadata_this['seconds_total']+($step_default_seconds*$metadata_this['steps_total'])) * 100 ));
            }


            //Hack for now, investigate later:
            if($metadata_this['completion_percentage'] > 100){
                $metadata_this['completion_percentage'] = 100;
            }

        }

        //Return results:
        return $metadata_this;

    }


    function discover_ids($en_id){
        //Simply returns all the idea IDs for a user's DISCOVER LIST:
        $player_discover_ids = array();
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ), array('in_previous'), 0) as $user_in){
            array_push($player_discover_ids, intval($user_in['in_id']));
        }
        return $player_discover_ids;
    }





    function discover_history_ui($tab_group_id, $note_in_id = 0, $owner_en_id = 0, $last_loaded_ln_id = 0){

        if (!$note_in_id && !$owner_en_id) {

            return array(
                'status' => 0,
                'message' => 'Require either Idea or Play ID',
            );

        } elseif (!in_array($tab_group_id, $this->config->item('en_ids_12410') /* IDEA & DISCOVER COIN */) || !count($this->config->item('en_ids_'.$tab_group_id))) {

            return array(
                'status' => 0,
                'message' => 'Invalid Discovery Type Group ID',
            );

        }


        $order_columns = array('ln_id' => 'DESC');
        $match_columns = array(
            'ln_id >' => $last_loaded_ln_id,
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_'.$tab_group_id)) . ')' => null,
        );

        if($note_in_id > 0){

            $match_columns['ln_previous_idea_id'] = $note_in_id;
            $list_url = '/idea/'.$note_in_id;
            $list_class = 'itemidea';
            $join_objects = array('en_creator');

        } elseif($owner_en_id > 0){

            if($tab_group_id == 12273 /* IDEA COIN */){

                $order_columns = array('in_weight' => 'DESC');
                $list_class = 'itemdiscover';
                $join_objects = array('in_next');
                $match_columns['ln_profile_source_id'] = $owner_en_id;
                $match_columns['in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')'] = null;

            } elseif($tab_group_id == 6255 /* DISCOVER COIN */){

                $order_columns = array('in_weight' => 'DESC');
                $list_class = 'itemsource';
                $list_url = '/source/'.$owner_en_id;
                $join_objects = array('in_previous');
                $match_columns['ln_creator_source_id'] = $owner_en_id;

            }

        }

        $in_query = $this->LEDGER_model->ln_fetch($match_columns, $join_objects, config_var(11064), 0, $order_columns);

        //List Discovery History:
        $ui = '<div class="list-group dynamic-discoveries">';
        if($note_in_id > 0){

            foreach($in_query as $count => $in_discover){
                $ui .= echo_en($in_discover);
            }

        } elseif($owner_en_id > 0){

            $previous_do_hide = true;
            $bold_upto_weight = in_calc_bold_upto_weight($in_query);
            $show_max = config_var(11986);

            foreach($in_query as $count => $in_discover){

                $infobar_details = null;
                if(strlen($in_discover['ln_content'])){
                    $infobar_details .= '<div class="message_content">';
                    $infobar_details .= $this->COMMUNICATION_model->comm_message_send($in_discover['ln_content']);
                    $infobar_details .= '</div>';
                }

                $do_hide = (($bold_upto_weight && $bold_upto_weight>=$in_discover['in_weight']) || ($count >= $show_max));

                if(!$previous_do_hide && $do_hide){
                    $ui .= '<div class="list-group-item nonbold_hide no-side-padding montserrat"><span class="icon-block"><i class="far fa-plus-circle idea"></i></span><a href="javascript:void(0);" onclick="$(\'.nonbold_hide\').toggleClass(\'hidden\')"><b style="text-decoration: none !important;">SEE MORE</b></a></div>';
                    $ui .= '<div class="see_more_sources"></div>';
                }

                $ui .= echo_in($in_discover, 0, false, false, null, ( $do_hide ? ' nonbold_hide hidden ' : '' ), false);

                $previous_do_hide = $do_hide;

            }
        }

        $ui .= '</div>';


        //Return success:
        return array(
            'status' => 1,
            'message' => $ui,
        );

    }



    function discover_answer($en_id, $question_in_id, $answer_in_ids){

        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $question_in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
        ));
        $ens = $this->SOURCE_model->en_fetch(array(
            'en_id' => $en_id,
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
        ));
        if (!count($ins)) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
            );
        } elseif (!count($ens)) {
            return array(
                'status' => 0,
                'message' => 'Invalid source ID',
            );
        } elseif (!in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7712'))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Idea type [Must be Answer]',
            );
        } elseif (!count($answer_in_ids)) {
            return array(
                'status' => 0,
                'message' => 'Missing Answer',
            );
        }


        //Define completion links for each answer:
        if($ins[0]['in_type_source_id'] == 6684){

            //ONE ANSWER
            $ln_type_source_id = 6157; //Award Coin
            $in_link_type_id = 12336; //Save Answer

        } elseif($ins[0]['in_type_source_id'] == 7231){

            //SOME ANSWERS
            $ln_type_source_id = 7489; //Award Coin
            $in_link_type_id = 12334; //Save Answer

        }

        //Delete ALL previous answers:
        foreach ($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7704')) . ')' => null, //DISCOVER ANSWERED
            'ln_creator_source_id' => $en_id,
            'ln_previous_idea_id' => $ins[0]['in_id'],
        )) as $discover_progress){
            $this->LEDGER_model->ln_update($discover_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Deleted
            ), $en_id, 12129 /* DISCOVER ANSWER DELETED */);
        }

        //Add New Answers
        $answers_newly_added = 0;
        foreach($answer_in_ids as $answer_in_id){
            $answers_newly_added++;
            $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => $in_link_type_id,
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $ins[0]['in_id'],
                'ln_next_idea_id' => $answer_in_id,
            ));
        }


        //Ensure we logged an answer:
        if(!$answers_newly_added){
            return array(
                'status' => 0,
                'message' => 'No answers saved.',
            );
        }

        //Issue DISCOVER/IDEA COIN:
        $this->DISCOVER_model->discover_is_complete($ins[0], array(
            'ln_type_source_id' => $ln_type_source_id,
            'ln_creator_source_id' => $en_id,
            'ln_previous_idea_id' => $ins[0]['in_id'],
        ));

        //All good, something happened:
        return array(
            'status' => 1,
            'message' => $answers_newly_added.' Selected. Going Next...',
        );

    }



}