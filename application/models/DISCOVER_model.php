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
        if(count($in_metadata['in__metadata_expansion_some']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_some']));
        }
        if(count($in_metadata['in__metadata_expansion_conditional']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_conditional']));
        }
        if(count($check_termination_answers) > 0 && count($this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id' => 7492, //TERMINATE
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',' , $check_termination_answers) . ')' => null, //All possible answers that might terminate...
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            ))) > 0){
            return -1;
        }



        foreach(array_flatten($in_metadata['in__metadata_common_steps']) as $common_step_in_id){

            //Is this an expansion step?
            $is_expansion = isset($in_metadata['in__metadata_expansion_steps'][$common_step_in_id]) || isset($in_metadata['in__metadata_expansion_some'][$common_step_in_id]);
            $is_condition = isset($in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]);

            //Have they completed this?
            if($is_expansion){

                //First fetch all possible answers based on correct order:
                $found_expansion = 0;
                foreach($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'ln_previous_idea_id' => $common_step_in_id,
                ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $ln){

                    //See if this answer was selected:
                    if(count($this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINK
                        'ln_previous_idea_id' => $common_step_in_id,
                        'ln_next_idea_id' => $ln['in_id'],
                        'ln_creator_source_id' => $en_id, //Belongs to this User
                    )))){

                        $found_expansion++;

                        //Yes was answered, see if it's completed:
                        if(!count($this->LEDGER_model->ln_fetch(array(
                            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                ), array('in_next')) as $unlocked_condition){

                    //Completed step that has OR expansions, check recursively to see if next step within here:
                    $found_in_id = $this->DISCOVER_model->discover_next_find($en_id, $unlocked_condition, false);

                    if($found_in_id != 0){
                        return $found_in_id;
                    }

                }

            } elseif(!count($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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
                foreach($this->IDEA_model->in_recursive_parents($in['in_id']) as $grand_parent_ids) {
                    if (array_intersect($grand_parent_ids, $player_discover_ids)) {
                        foreach($grand_parent_ids as $parent_in_id){
                            $ins = $this->IDEA_model->in_fetch(array(
                                'in_id' => $parent_in_id,
                                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
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

    function discover_next_go($en_id)
    {

        /*
         *
         * Searches for the next DISCOVER LIST step
         *
         * */

        $player_discoveries = $this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0, 0, array('ln_order' => 'ASC'));

        if(!count($player_discoveries)){
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
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
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
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        if (count($ins) != 1) {
            return false;
        }


        //Make sure not previously added to this User's DISCOVER LIST:
        if(!count($this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $in_id,
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12842')) . ')' => null, //IDEA LINKS ONE-WAY
                'ln_previous_idea_id' => $in['in_id'],
                'ln_next_idea_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ), array('in_next'), 0, 0);


            foreach($locked_links as $locked_link) {

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
            foreach($this->IDEA_model->in_recursive_parents($in['in_id']) as $grand_parent_ids) {

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
                        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
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
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
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
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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

    function discover_echo($in_id, $recipient_en){

        /*
         * Function to discover na Idea, it's messages,
         * and necessary inputs to complete it.
         *
         */

        if(!isset($recipient_en['en_id']) ){
            $recipient_en['en_id'] = 0;
        }


        //FETCH IDEA
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        if (count($ins) < 1) {
            $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $recipient_en['en_id'],
                'ln_content' => 'step_echo() invalid idea ID',
                'ln_previous_idea_id' => $in_id,
            ));
            return '<div class="alert alert-danger"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span>Invalid Idea ID</div>';
        }



        //MESSAGES
        $in__messages = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id' => 4231, //IDEA NOTES Messages
            'ln_next_idea_id' => $ins[0]['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //NEXT IDEAS
        $in__next = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_previous_idea_id' => $ins[0]['in_id'],
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC'));

        //Log View:
        $this->LEDGER_model->ln_create(array(
            'ln_creator_source_id' => $recipient_en['en_id'],
            'ln_type_source_id' => 7610, //PLAYER VIEWED IDEA
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
                foreach($this->IDEA_model->in_recursive_parents($ins[0]['in_id']) as $grand_parent_ids) {

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

            //IDEA TITLE
            echo '<h1 class="block-one"><span class="icon-block top-icon"><i class="fas fa-circle discover"></i></span><span class="title-block-lg">' . echo_in_title($ins[0]) . '</span></h1>';


            foreach($in__messages as $message_ln) {
                echo $this->COMMUNICATION_model->send_message(
                    $message_ln['ln_content'],
                    $recipient_en
                );
            }

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







            if($all_child_featured){

                if(count($in__next) > 0){
                    //List Children:
                    echo '<div class="list-group">';
                    foreach($in__next as $key => $child_in){
                        echo echo_in_discover($child_in, $is_or, in_calc_common_prefix($in__next, 'in_title'));
                    }
                    echo '</div>';
                }

            } else {

                //IDEA METADATA
                $metadata = unserialize($ins[0]['in_metadata']);
                $has_time = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );
                $has_idea = ( isset($metadata['in__metadata_max_steps']) && $metadata['in__metadata_max_steps']>0 );

                if ($has_time || $has_idea || count($in__next)) {

                    echo '<div class="discover-topic"><a href="javascript:void(0);" onclick="$(\'.contentTabIdeas\').toggleClass(\'hidden\')"><span class="icon-block"><i class="far fa-plus-circle contentTabIdeas"></i><i class="far fa-minus-circle contentTabIdeas hidden"></i></span>'.( $has_idea ? $metadata['in__metadata_max_steps'].' Idea'.echo__s($metadata['in__metadata_max_steps']) : '' ).( $has_time ? ( $has_idea ? ' in ' : '' ).echo_time_hours($metadata['in__metadata_max_seconds']) : '' ).'</a></div>';

                    //BODY
                    echo '<div class="contentTabIdeas hidden" style="padding-bottom:21px;">';
                    if(count($in__next) > 0){
                        //List Children:
                        echo '<div class="list-group">';
                        foreach($in__next as $key => $child_in){
                            echo echo_in_discover($child_in, $is_or, in_calc_common_prefix($in__next, 'in_title'));
                        }
                        echo '</div>';
                    }
                    echo '</div>';

                }


                //Expert References?
                $source_count = count($metadata['in__metadata_experts']);
                foreach($metadata['in__metadata_sources'] as $channel_id => $channel_contents){
                    $source_count += count($channel_contents);
                }
                if ($source_count > 0) {

                    echo '<div class="discover-topic"><a href="javascript:void(0);" onclick="$(\'.contentTabExperts\').toggleClass(\'hidden\')"><span class="icon-block"><i class="far fa-plus-circle contentTabExperts"></i><i class="far fa-minus-circle contentTabExperts hidden"></i></span>'.$source_count.' Expert Source'.echo__s($source_count).'</a></div>';

                    echo '<div class="contentTabExperts hidden" style="padding-bottom:21px;">';
                    echo '<div class="list-group">';

                    foreach($metadata['in__metadata_sources'] as $channel_id => $channel_contents){
                        foreach($channel_contents as $channel_content){
                            echo echo_en_basic($channel_content);
                        }
                    }

                    foreach($metadata['in__metadata_experts'] as $expert){
                        echo echo_en_basic($expert);
                    }

                    echo '</div>';
                    echo '</div>';

                }



                //Redirect to login page:
                echo '<div class="inline-block margin-top-down discover-add pull-right"><a class="btn btn-discover btn-circle" href="/discover/start/'.$ins[0]['in_id'].'"><i class="fas fa-step-forward"></i></a></div>';

            }

            echo '<div class="doclear">&nbsp;</div>';

            return true;
        }



        /*
         * Previously in source's discovery list...
         *
         */





        //Fetch progress history:
        $discover_completes = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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


        // % DONE
        $completion_rate = $this->DISCOVER_model->discover_completion_progress($recipient_en['en_id'], $ins[0]);
        $metadata = unserialize($ins[0]['in_metadata']);
        $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );


        echo '<div class="hideIfEmpty main_discovery_top"></div>';

        //DISCOVER PROGRESS
        if($completion_rate['completion_percentage']>0){
            echo '<div class="progress-bg no-horizonal-margin" title="Discover '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
        } else {
            //Replace with empty space:
            echo '<div class="high3x">&nbsp;</div>';
        }

        //DISCOVER TITLE
        echo '<h1 class="block-one"><span class="icon-block top-icon"><i class="fas fa-circle discover" aria-hidden="true"></i></span><span class="title-block-lg">' . echo_in_title($ins[0]) . '</span></h1>';



        foreach($in__messages as $message_ln) {
            echo $this->COMMUNICATION_model->send_message(
                $message_ln['ln_content'],
                $recipient_en
            );
        }



        if(count($discover_completes) && $qualify_for_autocomplete){
            //Move to the next one as there is nothing to do here:
            echo "<script> $(document).ready(function () { window.location = '/discover/next/' + in_loaded_id + '".( isset($_GET['came_from']) && $_GET['came_from']>0 ? '?came_from='.$_GET['came_from'] : '' )."'; }); </script>";
        }




        //PREVIOUSLY UNLOCKED:
        $unlocked_steps = $this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id' => 6140, //DISCOVER UNLOCK LINK
            'ln_creator_source_id' => $recipient_en['en_id'],
            'ln_previous_idea_id' => $ins[0]['in_id'],
        ), array('in_next'), 0);

        //Did we have any steps unlocked?
        if(count($unlocked_steps) > 0){
            echo_in_list($ins[0], $unlocked_steps, $recipient_en, '<span class="icon-block"><i class="fas fa-lock-open"></i></span>UNLOCKED:', false);
        }




        //LOCKED
        if (in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7309'))) {


            //Requirement lock
            if(!count($discover_completes) && !count($unlocked_connections) && count($unlock_paths)){

                //List Unlock paths:
                echo_in_list($ins[0], $unlock_paths, $recipient_en, '<span class="icon-block"><i class="fas fa-step-forward"></i></span>SUGGESTED IDEAS:');

            }

            //List Children if any:
            echo_in_list($ins[0], $in__next, $recipient_en, null, ( $completion_rate['completion_percentage'] < 100 ));


        } elseif (in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7712'))){

            //SELECT ANSWER

            //Has no children:
            if(!count($in__next)){

                //Mark this as complete since there is no child to choose from:
                if(!count($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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

                echo_in_next_previous($ins[0]['in_id'], $recipient_en);
                return true;

            } else {

                //First fetch answers based on correct order:
                $discover_answers = array();
                foreach($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $ln){
                    //See if this answer was seleted:
                    if(count($this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINK
                        'ln_previous_idea_id' => $ins[0]['in_id'],
                        'ln_next_idea_id' => $ln['in_id'],
                        'ln_creator_source_id' => $recipient_en['en_id'],
                    )))){
                        array_push($discover_answers, $ln);
                    }
                }

                if(count($discover_answers) > 0){
                    //MODIFY ANSWER
                    echo '<div class="edit_select_answer">';

                    //List answers:
                    echo_in_list($ins[0], $discover_answers, $recipient_en, '<span class="icon-block">&nbsp;</span>YOU ANSWERED:', false);

                    echo '<div class="doclear">&nbsp;</div>';

                    echo_in_next_previous($ins[0]['in_id'], $recipient_en);

                    echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-discover btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-pen"></i></a></div>';

                    echo '<div class="doclear">&nbsp;</div>';

                    echo '</div>';
                }


                echo '<div class="edit_select_answer '.( count($discover_answers)>0 ? 'hidden' : '' ).'">';

                //HTML:
                if ($ins[0]['in_type_source_id'] == 6684) {

                    echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>SELECT ONE:</div>';

                } elseif ($ins[0]['in_type_source_id'] == 7231) {

                    echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>SELECT ONE OR MORE:</div>';

                }

                //Open for list to be printed:
                echo '<div class="list-group list-answers" in_type_source_id="'.$ins[0]['in_type_source_id'].'">';




                //Determine Prefix:
                $common_prefix = in_calc_common_prefix($in__next, 'in_title');


                //List children to choose from:
                foreach($in__next as $key => $child_in) {

                    //Has this been previously selected?
                    $previously_selected = count($this->LEDGER_model->ln_fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                        'ln_previous_idea_id' => $ins[0]['in_id'],
                        'ln_next_idea_id' => $child_in['in_id'],
                        'ln_creator_source_id' => $recipient_en['en_id'],
                    )));


                    echo '<a href="javascript:void(0);" onclick="select_answer('.$child_in['in_id'].')" is-selected="'.( $previously_selected ? 1 : 0 ).'" answered_ins="'.$child_in['in_id'].'" class="ln_answer_'.$child_in['in_id'].' answer-item list-group-item itemdiscover no-left-padding">';


                    echo '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
                    echo '<td class="icon-block check-icon" style="padding: 0 !important;"><i class="'.( $previously_selected ? 'fas' : 'far' ).' fa-circle discover"></i></td>';

                    echo '<td style="width: 100%; padding: 0 !important;">';
                    echo '<b class="montserrat idea-url" style="margin-left:0;">'.echo_in_title($child_in, false, $common_prefix).'</b>';
                    echo '</td>';

                    echo '</tr></table>';


                    echo '</a>';
                }


                //Close list:
                echo '</div>';




                echo '<div class="result-update margin-top-down"></div>';

                echo echo_in_previous_discover($in_id, $recipient_en);

                //Button to submit selection:
                if(count($discover_answers)>0){
                    echo '<div class="inline-block margin-top-down pull-left"><a class="btn btn-discover btn-circle" href="javascript:void(0);" onclick="$(\'.edit_select_answer\').toggleClass(\'hidden\');"><i class="fas fa-arrow-left"></i></a></div>';
                }

                echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-discover btn-circle" href="javascript:void(0)" onclick="discover_answer()"><i class="fas fa-step-forward"></i></a></div>';

                echo '</div>';

            }

        } else {

            if ($ins[0]['in_type_source_id'] == 6677) {

                //DISCOVER ONLY

                //Next Ideas:
                echo_in_list($ins[0], $in__next, $recipient_en);

            } elseif ($ins[0]['in_type_source_id'] == 6683) {

                //TEXT RESPONSE

                echo '<textarea class="border i_content padded discover_input" placeholder="Your Answer Here..." id="discover_text_answer">'.( count($discover_completes) ? trim($discover_completes[0]['ln_content']) : '' ).'</textarea>';

                echo '<div class="text_saving_result margin-top-down"></div>';

                //Show Previous Button:
                echo echo_in_previous_discover($ins[0]['in_id'], $recipient_en);

                //Save/Upload & Next:
                echo '<div class="margin-top-down inline-block pull-right"><a class="btn btn-discover btn-circle" href="javascript:void(0);" onclick="discover_text_answer()"><i class="fas fa-step-forward"></i></a></div>';


                if(count($discover_completes)){
                    //Next Ideas:
                    echo_in_list($ins[0], $in__next, $recipient_en, null,false);
                }

                echo '<script> $(document).ready(function () { autosize($(\'#discover_text_answer\')); $(\'#discover_text_answer\').focus(); }); </script>';


            } elseif (in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7751'))) {

                //FILE UPLOAD

                echo '<div class="playerUploader">';
                echo '<form class="box boxUpload" method="post" enctype="multipart/form-data">';

                echo '<input class="inputfile" type="file" name="file" id="fileType'.$ins[0]['in_type_source_id'].'" />';


                if(!count($discover_completes)) {

                    //Show Previous Button:
                    echo '<div class="file_saving_result">';
                    echo echo_in_previous_discover($ins[0]['in_id'], $recipient_en);
                    echo '</div>';

                    //Show next here but keep hidden until file is uploaded:
                    echo '<div class="go_next_upload hidden">';
                    echo_in_next_previous($ins[0]['in_id'], $recipient_en);
                    echo '</div>';

                    echo '<div class="inline-block margin-top-down edit_select_answer pull-right"><label class="btn btn-discover btn-circle inline-block" for="fileType'.$ins[0]['in_type_source_id'].'"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

                } else {

                    echo '<div class="file_saving_result">';

                    echo '<div class="discover-topic"><span class="icon-block">&nbsp;</span>YOUR UPLOAD:</div><div class="previous_answer">'.$this->COMMUNICATION_model->send_message($discover_completes[0]['ln_content']).'</div>';

                    echo '</div>';

                    //Any child ideas?
                    echo_in_list($ins[0], $in__next, $recipient_en, null, true, false);

                    echo '<div class="inline-block margin-top-down pull-right"><label class="btn btn-discover inline-block btn-circle" for="fileType'.$ins[0]['in_type_source_id'].'" style="margin-left:5px;"><i class="fad fa-cloud-upload-alt" style="margin-left: -4px;"></i></label></div>';

                }

                echo '<div class="doclear">&nbsp;</div>';
                echo '</form>';
                echo '</div>';


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


        //Process Answer ONE:
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
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
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
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->DISCOVER_model->discover_completion_marks($en_id, $answer_in, false);

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['in_id']] + $recursive_stats['steps_answered_marks'];

            }
        }


        //Process Answer SOME:
        if(isset($in_metadata['in__metadata_expansion_some']) && count($in_metadata['in__metadata_expansion_some']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_in_ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($in_metadata['in__metadata_expansion_some'] as $question_in_id => $answers_in_ids ){

                //Calculate local min/max marks:
                array_push($question_in_ids, $question_in_id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->LEDGER_model->ln_fetch(array(
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
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
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }

                //Always Add local max:
                $metadata_this['steps_marks_max'] += $answer_marks_index[$in_answer['in_id']];

            }



            //Now let's check user answers to see what they have done:
            $total_completion = $this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
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
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_time_seconds) as total_seconds');


        //Count completed for user:
        $common_completed = $this->LEDGER_model->ln_fetch(array(
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //DISCOVER COMPLETE
            'ln_creator_source_id' => $en_id, //Belongs to this User
            'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0, 0, array(), 'COUNT(in_id) as completed_steps, SUM(in_time_seconds) as completed_seconds');


        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_steps']),
            'steps_completed' => intval($common_completed[0]['completed_steps']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );


        //Expansion Answer ONE
        $answer_array = array();
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($in_metadata['in__metadata_expansion_steps']));
        }
        if(isset($in_metadata['in__metadata_expansion_some']) && count($in_metadata['in__metadata_expansion_some']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($in_metadata['in__metadata_expansion_some']));
        }

        if(count($answer_array)){

            //Now let's check user answers to see what they have done:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //DISCOVER IDEA LINKS
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_next_idea_id IN (' . join(',', $answer_array) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
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
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
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
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
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
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_'.$tab_group_id)) . ')' => null,
        );

        if($note_in_id > 0){

            $match_columns['ln_previous_idea_id'] = $note_in_id;
            $list_url = '/idea/go/'.$note_in_id;
            $list_class = 'itemidea';
            $join_objects = array('en_creator');

        } elseif($owner_en_id > 0){

            if($tab_group_id == 12273 /* IDEA COIN */){

                $order_columns = array('in_weight' => 'DESC');
                $list_class = 'itemdiscover';
                $join_objects = array('in_next');
                $match_columns['ln_profile_source_id'] = $owner_en_id;

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
                    $infobar_details .= $this->COMMUNICATION_model->send_message($in_discover['ln_content']);
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
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        $ens = $this->SOURCE_model->en_fetch(array(
            'en_id' => $en_id,
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
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
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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