<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class READ_model extends CI_Model
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


    function read_next_find($en_id, $in, $first_step = true){

        /*
         *
         * Searches within a user Bookshelf to find
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
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINK
                        'ln_previous_idea_id' => $common_step_in_id,
                        'ln_next_idea_id' => $ln['in_id'],
                        'ln_creator_source_id' => $en_id, //Belongs to this User
                    )))){

                        $found_expansion++;

                        //Yes was answered, see if it's completed:
                        if(!count($this->LEDGER_model->ln_fetch(array(
                            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                            'ln_type_source_id IN (' . join(',' , $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                            'ln_creator_source_id' => $en_id, //Belongs to this User
                            'ln_previous_idea_id' => $ln['in_id'],
                        )))){

                            //Answer is not completed, go there:
                            return $ln['in_id'];

                        } else {

                            //Answer previously completed, see if there is anyting else:
                            $found_in_id = $this->READ_model->read_next_find($en_id, $ln, false);
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
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                    'ln_creator_source_id' => $en_id, //Belongs to this User
                    'ln_previous_idea_id' => $common_step_in_id,
                    'ln_next_idea_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]) . ')' => null,
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                ), array('in_next')) as $unlocked_condition){

                    //Completed step that has OR expansions, check recursively to see if next step within here:
                    $found_in_id = $this->READ_model->read_next_find($en_id, $unlocked_condition, false);

                    if($found_in_id != 0){
                        return $found_in_id;
                    }

                }

            } elseif(!count($this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',' , $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                    'ln_creator_source_id' => $en_id, //Belongs to this User
                    'ln_previous_idea_id' => $common_step_in_id,
                )))){

                //Not completed yet, this is the next step:
                return $common_step_in_id;

            }

        }


        //If not part of the Bookshelf, go to reads idea
        if($first_step){
            $player_read_ids = $this->READ_model->read_ids($en_id);
            if(!in_array($in['in_id'], $player_read_ids)){
                foreach($this->IDEA_model->in_recursive_parents($in['in_id']) as $grand_parent_ids) {
                    if (array_intersect($grand_parent_ids, $player_read_ids)) {
                        foreach($grand_parent_ids as $parent_in_id){
                            $ins = $this->IDEA_model->in_fetch(array(
                                'in_id' => $parent_in_id,
                                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                            ));
                            if(count($ins)){
                                $found_in_id = $this->READ_model->read_next_find($en_id, $ins[0], false);
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

    function read_next_go($en_id)
    {

        /*
         *
         * Searches for the next Bookshelf step
         *
         * */

        $player_reads = $this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Bookshelf Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0, 0, array('ln_order' => 'ASC'));

        if(!count($player_reads)){
            return 0;
        }

        //Loop through Bookshelf Ideas and see what's next:
        foreach($player_reads as $user_in){

            //Find first incomplete step for this Bookshelf Idea:
            $next_in_id = $this->READ_model->read_next_find($en_id, $user_in);

            if($next_in_id < 0){

                //We need to terminate this:
                $this->READ_model->read_delete($en_id, $user_in['in_id'], 7757); //MENCH REMOVED BOOKMARK
                break;

            } elseif($next_in_id > 0){

                //We found the next incomplete step, return:
                break;

            }
        }

        //Return next step Idea or false:
        return intval($next_in_id);

    }


    function read_focus($en_id){

        /*
         *
         * A function that goes through the Bookshelf
         * and finds the top-priority that the user
         * is currently working on.
         *
         * */

        $top_priority_in = false;
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Bookshelf Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0, 0, array('ln_order' => 'ASC')) as $bookshelf_in){

            //See progress rate so far:
            $completion_rate = $this->READ_model->read_completion_progress($en_id, $bookshelf_in);

            if($completion_rate['completion_percentage'] < 100){
                //This is the top priority now:
                $top_priority_in = $bookshelf_in;
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

    function read_delete($en_id, $in_id, $stop_method_id, $stop_feedback = null){


        if(!in_array($stop_method_id, $this->config->item('en_ids_6150') /* Bookshelf Idea Completed */)){
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

        //Go ahead and delete from Bookshelf:
        $player_reads = $this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Bookshelf Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_previous_idea_id' => $in_id,
        ));
        if(count($player_reads) < 1){
            return array(
                'status' => 0,
                'message' => 'Could not locate Bookshelf',
            );
        }

        //Delete Bookmark:
        foreach($player_reads as $ln){
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

    function read_start($en_id, $in_id, $recommender_in_id = 0){

        //Validate Idea ID:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        if (count($ins) != 1) {
            return false;
        }


        //Make sure not previously added to this User's Bookshelf:
        if(!count($this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $in_id,
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Bookshelf Idea Set
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            )))){

            //Not added to their Bookshelf so far, let's go ahead and add it:
            $in_rank = 1;
            $bookshelf = $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => ( $recommender_in_id > 0 ? 7495 /* User Idea Recommended */ : 4235 /* User Idea Set */ ),
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id' => $ins[0]['in_id'], //The Idea they are adding
                'ln_next_idea_id' => $recommender_in_id, //Store the recommended idea
                'ln_order' => $in_rank, //Always place at the top of their Bookshelf
            ));

            //Mark as readed if possible:
            if($ins[0]['in_type_source_id']==6677){
                $this->READ_model->read_is_complete($ins[0], array(
                    'ln_type_source_id' => 4559, //READ MESSAGES
                    'ln_creator_source_id' => $en_id,
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ));
            }

            //Move other ideas down in the Bookshelf:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_id !=' => $bookshelf['ln_id'], //Not the newly added idea
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Bookshelf Idea Set
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




    function read_completion_recursive_up($en_id, $in, $is_bottom_level = true){

        /*
         *
         * Let's see how many steps get unlocked:
         *
         * https://mench.com/source/6410
         *
         * */


        //First let's make sure this entire Idea completed by the user:
        $completion_rate = $this->READ_model->read_completion_progress($en_id, $in);


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
                'ln_type_source_id' => 6140, //READ UNLOCK LINK
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
            $user_marks = $this->READ_model->read_completion_marks($en_id, $in);





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

                    //Unlock Bookshelf:
                    $this->LEDGER_model->ln_create(array(
                        'ln_type_source_id' => 6140, //READ UNLOCK LINK
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
            $player_read_ids = $this->READ_model->read_ids($en_id);

            //Prevent duplicate processes even if on multiple parent ideas:
            $parents_checked = array();

            //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
            foreach($this->IDEA_model->in_recursive_parents($in['in_id']) as $grand_parent_ids) {

                //Does this parent and its grandparents have an intersection with the user ideas?
                if(!array_intersect($grand_parent_ids, $player_read_ids)){
                    //Parent idea is NOT part of their Bookshelf:
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
                        $this->READ_model->read_completion_recursive_up($en_id, $parent_ins[0], false);

                    }

                    //Terminate if we reached the Bookshelf idea level:
                    if(in_array($p_id , $player_read_ids)){
                        break;
                    }
                }
            }
        }


        return true;
    }


    function read_unlock_locked_step($en_id, $in){

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
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
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
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
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


    function read_in_bookshelf($in_id, $recipient_en){

        $read_in_bookshelf = false;

        if($recipient_en['en_id'] > 0){

            //Fetch entire Bookshelf:
            $player_read_ids = $this->READ_model->read_ids($recipient_en['en_id']);
            $read_in_bookshelf = in_array($in_id, $player_read_ids);

            if(!$read_in_bookshelf){
                //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
                foreach($this->IDEA_model->in_recursive_parents($in_id) as $grand_parent_ids) {
                    //Does this parent and its grandparents have an intersection with the user ideas?
                    if (array_intersect($grand_parent_ids, $player_read_ids)) {
                        //Idea is part of their Bookshelf:
                        $read_in_bookshelf = true;
                        break;
                    }
                }
            }
        }

        return $read_in_bookshelf;

    }


    function read_is_complete($in, $insert_columns){

        //Log completion link:
        $new_link = $this->LEDGER_model->ln_create($insert_columns);

        //Process completion automations:
        $this->READ_model->read_completion_recursive_up($insert_columns['ln_creator_source_id'], $in);

        return $new_link;

    }

    function read_completion_marks($en_id, $in, $top_level = true)
    {

        //Fetch/validate Bookshelf Common Ideas:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){

            //Should not happen, log error:
            $this->LEDGER_model->ln_create(array(
                'ln_content' => 'completion_marks() Detected user Bookshelf without in__metadata_common_steps value!',
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
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->READ_model->read_completion_marks($en_id, $answer_in, false);

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
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->READ_model->read_completion_marks($en_id, $answer_in, false);

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



    function read_completion_progress($en_id, $in, $top_level = true)
    {

        if(!isset($in['in_metadata'])){
            return false;
        }

        //Fetch/validate Bookshelf Common Ideas:
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
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
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
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_next_idea_id IN (' . join(',', $answer_array) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->READ_model->read_completion_progress($en_id, $expansion_in, false);

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
                'ln_type_source_id' => 6140, //READ UNLOCK LINK
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_next_idea_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_conditional'])) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->READ_model->read_completion_progress($en_id, $expansion_in, false);

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
             * Completing an Bookshelf depends on two factors:
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
                $metadata_this['completion_percentage'] = intval(ceil( ($metadata_this['seconds_completed']+($step_default_seconds*$metadata_this['steps_completed'])) / ($metadata_this['seconds_total']+($step_default_seconds*$metadata_this['steps_total'])) * 100 ));
            }


            //Hack for now, investigate later:
            if($metadata_this['completion_percentage'] > 100){
                $metadata_this['completion_percentage'] = 100;
            }

        }

        //Return results:
        return $metadata_this;

    }


    function read_ids($en_id){
        //Simply returns all the idea IDs for a user's Bookshelf:
        $player_read_ids = array();
        foreach($this->LEDGER_model->ln_fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Bookshelf Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0) as $user_in){
            array_push($player_read_ids, intval($user_in['in_id']));
        }
        return $player_read_ids;
    }





    function read_history_ui($tab_group_id, $note_in_id = 0, $owner_en_id = 0, $last_loaded_ln_id = 0){

        if (!$note_in_id && !$owner_en_id) {

            return array(
                'status' => 0,
                'message' => 'Require either Idea or Play ID',
            );

        } elseif (!in_array($tab_group_id, $this->config->item('en_ids_12410') /* IDEA & READ COIN */) || !count($this->config->item('en_ids_'.$tab_group_id))) {

            return array(
                'status' => 0,
                'message' => 'Invalid Reads Type Group ID',
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
                $list_class = 'itemread';
                $join_objects = array('in_next');
                $match_columns['ln_profile_source_id'] = $owner_en_id;

            } elseif($tab_group_id == 6255 /* READ COIN */){

                $order_columns = array('in_weight' => 'DESC');
                $list_class = 'itemsource';
                $list_url = '/source/'.$owner_en_id;
                $join_objects = array('in_previous');
                $match_columns['ln_creator_source_id'] = $owner_en_id;

            }

        }

        $in_query = $this->LEDGER_model->ln_fetch($match_columns, $join_objects, config_var(11064), 0, $order_columns);

        //List Reads History:
        $ui = '<div class="list-group dynamic-reads">';
        if($note_in_id > 0){

            foreach($in_query as $count => $in_read){
                $ui .= echo_en($in_read);
            }

        } elseif($owner_en_id > 0){

            $previous_do_hide = true;
            $bold_upto_weight = in_calc_bold_upto_weight($in_query);
            $show_max = config_var(11986);

            foreach($in_query as $count => $in_read){

                $infobar_details = null;
                if(strlen($in_read['ln_content'])){
                    $infobar_details .= '<div class="message_content">';
                    $infobar_details .= $this->COMMUNICATION_model->send_message($in_read['ln_content']);
                    $infobar_details .= '</div>';
                }

                $do_hide = (($bold_upto_weight && $bold_upto_weight>=$in_read['in_weight']) || ($count >= $show_max));

                if(!$previous_do_hide && $do_hide){
                    $ui .= '<div class="list-group-item nonbold_hide no-side-padding montserrat"><span class="icon-block"><i class="far fa-plus-circle idea"></i></span><a href="javascript:void(0);" onclick="$(\'.nonbold_hide\').toggleClass(\'hidden\')"><b style="text-decoration: none !important;">SEE MORE</b></a></div>';
                    $ui .= '<div class="see_more_sources"></div>';
                }

                $ui .= echo_in($in_read, 0, false, false, null, ( $do_hide ? ' nonbold_hide hidden ' : '' ), false);

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



    function read_answer($en_id, $question_in_id, $answer_in_ids){

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
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7704')) . ')' => null, //READ ANSWERED
            'ln_creator_source_id' => $en_id,
            'ln_previous_idea_id' => $ins[0]['in_id'],
        )) as $read_progress){
            $this->LEDGER_model->ln_update($read_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Deleted
            ), $en_id, 12129 /* READ ANSWER DELETED */);
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

        //Issue READ/IDEA COIN:
        $this->READ_model->read_is_complete($ins[0], array(
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