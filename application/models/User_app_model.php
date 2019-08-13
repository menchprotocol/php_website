<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_app_model extends CI_Model
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




    function user_activate_session($en, $is_miner){

        //Fetch parent data if missing:
        if(!isset($en['en__parents']) || count($en['en__parents']) < 1){
            //Fetch full data:
            $ens = $this->Entities_model->en_fetch(array(
                'en_id' => $en['en_id'],
            ));
            $en = $ens[0];
        }

        //Assign user details:
        $session_data['user'] = $en;

        //Are they miner? Give them Sign In access:
        if ($is_miner) {

            //Check their advance mode status:
            $last_advance_settings = $this->Links_model->ln_fetch(array(
                'ln_creator_entity_id' => $en['en_id'],
                'ln_type_entity_id' => 5007, //Toggled Advance Mode
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(), 1, 0, array('ln_id' => 'DESC'));

            //They have admin rights:
            $session_data['user_default_intent'] = $this->config->item('in_focus_id');
            $session_data['user_session_count'] = 0;
            $session_data['advance_view_enabled'] = ( count($last_advance_settings) > 0 && substr_count($last_advance_settings[0]['ln_content'] , ' ON')==1 ? 1 : 0 );

        }

        //Log Sign In Link:
        $this->Links_model->ln_create(array(
            'ln_creator_entity_id' => $en['en_id'],
            'ln_type_entity_id' => 7564, //User Signin on Website Success
        ));

        //All good to go!
        //Load session and redirect:
        return $this->session->set_userdata($session_data);

    }

    function actionplan_completion_auto_complete($en_id, $in, $unlock_link_type_en_id){

        /*
         *
         * A function that marks an intent as complete IF
         * the intent has nothing of substance to be
         * further communicated/done by the user.
         *
         * $unlock_link_type_en_id Indicates the type of Unlocking that is about to happen
         *
         * */


        if(!in_array($in['in_type_entity_id'], $this->config->item('en_ids_7756'))){
            //Not Auto Completable:
            return false;
        } elseif(!in_array($unlock_link_type_en_id, $this->config->item('en_ids_7494'))){
            //Not a valid unlock step type:
            return false;
        }


        //Send messages, if any:
        foreach ($this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $in['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC')) as $message_ln) {
            $this->Communication_model->dispatch_message(
                $message_ln['ln_content'],
                array('en_id' => $en_id),
                true
            );
        }

        //Ok, now we can mark it as complete:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => ( in_is_unlockable($in) ? $unlock_link_type_en_id : 4559 /* User Step Read Messages */ ),
            'ln_creator_entity_id' => $en_id,
            'ln_parent_intent_id' => $in['in_id'],
            'ln_status_entity_id' => 6176, //Link Published
        ));


        //Process on-complete automations:
        $this->User_app_model->actionplan_completion_checks($en_id, $in, true, true);


        //All good:
        return true;
    }


    function actionplan_step_next_find($en_id, $in){

        /*
         *
         * Searches within a user Action Plan to find
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
        if(count($check_termination_answers) > 0 && count($this->Links_model->ln_fetch(array(
                'ln_type_entity_id' => 7741, //User Step Intention Terminated
                'ln_creator_entity_id' => $en_id, //Belongs to this User
                'ln_parent_intent_id IN (' . join(',' , $check_termination_answers) . ')' => null, //All possible answers that might terminate...
                'ln_status_entity_id' => 6176, //Link Published
            ))) > 0){
            return -1;
        }



        foreach(array_flatten($in_metadata['in__metadata_common_steps']) as $common_step_in_id){

            //Is this an expansion step?
            $is_expansion = isset($in_metadata['in__metadata_expansion_steps'][$common_step_in_id]);
            $is_condition = isset($in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]);

            //Is this completed?
            $completed_steps = $this->Links_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',' , $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Steps
                'ln_creator_entity_id' => $en_id, //Belongs to this User
                'ln_parent_intent_id' => $common_step_in_id,
                'ln_status_entity_id' => 6176, //Link Published
            ),  ( $is_expansion ? array('in_child') : array() ));

            //Have they completed this?
            if(count($completed_steps) == 0){

                //Not completed yet, this is the next step:
                return $common_step_in_id;

            } elseif($is_expansion){

                //Completed step that has OR expansions, check recursively to see if next step within here:
                $found_in_id = $this->User_app_model->actionplan_step_next_find($en_id, $completed_steps[0]);

                if($found_in_id != 0){
                    return $found_in_id;
                }

            } elseif($is_condition){

                //See which path they got unlocked, if any:
                $unlocked_conditions = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id' => 6140, //Action Plan Conditional Step Unlocked
                    'ln_creator_entity_id' => $en_id, //Belongs to this User
                    'ln_parent_intent_id' => $common_step_in_id,
                    'ln_child_intent_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]) . ')' => null,
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                ), array('in_child'));

                if(count($unlocked_conditions) > 0){

                    //Completed step that has OR expansions, check recursively to see if next step within here:
                    $found_in_id = $this->User_app_model->actionplan_step_next_find($en_id, $unlocked_conditions[0]);

                    if($found_in_id != 0){
                        return $found_in_id;
                    }
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
            'ln_creator_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
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
                $this->Communication_model->dispatch_recommendations($en_id);

            }

            //No Action Plans found!
            return 0;

        }


        //Looop through Action Plan intentions and see what's next:
        foreach($user_intents as $user_intent){

            //Find first incomplete step for this Action Plan intention:
            $next_in_id = $this->User_app_model->actionplan_step_next_find($en_id, $user_intent);

            if($next_in_id < 0){

                //We need to terminate this:
                $this->User_app_model->actionplan_intention_delete($en_id, $user_intent['in_id'], 7757 /* User Intent Terminated */);
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
                $this->User_app_model->actionplan_step_next_echo($en_id, $next_in_id);

            } else {

                //Inform user that they are now complete with all steps:
                $this->Communication_model->dispatch_message(
                    'You just completed everything in your Action Plan 🙌',
                    array('en_id' => $en_id),
                    true
                );

                //List Recommended Intents and let them choose:
                $this->Communication_model->dispatch_recommendations($en_id);

            }
        }

        //Return next step intent or false:
        return intval($next_in_id);

    }

    function actionplan_step_skip_initiate($en_id, $in_id, $push_message = true){

        //Fetch this intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ));
        if(count($ins) < 1){
            $this->Links_model->ln_create(array(
                'ln_child_intent_id' => $in_id,
                'ln_content' => 'actionplan_step_skip_initiate() did not locate the published intent',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $en_id,
            ));
            return false;
        }

        $skip_message = 'Are you sure you want to skip the '.echo_step_range($ins[0], true).' to '.echo_in_outcome($ins[0]['in_outcome'], $push_message, true).'?';

        if(!$push_message){

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
                        'title' => 'Cancel',
                        'payload' => 'SKIP-ACTIONPLAN_skip-cancel_'.$in_id, //Cancel skipping
                    ),
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
                'ln_creator_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
            ));
            return 0;
        }


        $in_metadata = unserialize( $ins[0]['in_metadata'] );

        if(!isset($in_metadata['in__metadata_common_steps'])){
            $this->Links_model->ln_create(array(
                'ln_content' => 'actionplan_step_skip_apply() failed to locate metadata common steps',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $en_id,
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
                'ln_creator_entity_id' => $en_id,
                'ln_parent_intent_id' => $common_in_id,
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ));

            //Add skip link:
            $new_progression_link = $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 6143, //Action Plan Skipped Step
                'ln_creator_entity_id' => $en_id,
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
        $this->User_app_model->actionplan_completion_checks($en_id, $ins[0], true, false);

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
            'ln_creator_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC')) as $actionplan_in){

            //See progress rate so far:
            $completion_rate = $this->User_app_model->actionplan_completion_progress($en_id, $actionplan_in);

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

    function actionplan_intention_delete($en_id, $in_id, $stop_method_id, $stop_feedback = null){


        if(!in_array($stop_method_id, $this->config->item('en_ids_6150') /* Action Plan Intention Completed */)){
            return array(
                'status' => 0,
                'message' => 'Invalid stop method',
            );
        }

        //Validate intention to be removed:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
        ));
        if (count($ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid intention',
            );
        }

        //Go ahead and remove from Action Plan:
        $user_intents = $this->Links_model->ln_fetch(array(
            'ln_creator_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'ln_parent_intent_id' => $in_id,
        ));
        if(count($user_intents) < 1){
            //Give error:
            return array(
                'status' => 0,
                'message' => 'Could not locate Action Plan',
            );
        }


        //Adjust Action Plan status:
        foreach($user_intents as $ln){
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_status_entity_id' => ( in_array($stop_method_id, $this->config->item('en_ids_7758') /* Action Plan Intention Successful */) ? 6176 /* Link Published */ : 6173 /* Link Removed */ ), //This is a nasty HACK!
            ), $en_id);
        }

        //Log related link:
        $this->Links_model->ln_create(array(
            'ln_content' => $stop_feedback,
            'ln_creator_entity_id' => $en_id,
            'ln_type_entity_id' => $stop_method_id,
            'ln_parent_intent_id' => $in_id,
        ));


        //Communicate with user:
        $this->Communication_model->dispatch_message(
            'I have removed the intention to '.$ins[0]['in_outcome'].' from your Action Plan.',
            array('en_id' => $en_id),
            true,
            array(
                array(
                    'content_type' => 'text',
                    'title' => 'Next',
                    'payload' => 'GONEXT',
                )
            )
        );

        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }

    function actionplan_intention_add($en_id, $in_id, $recommender_in_id = 0, $echo_next_step = true){

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
                'ln_parent_intent_id' => $in_id,
                'ln_content' => 'actionplan_intention_add() was about to add an intention that was not public',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $en_id,
            ));

            return false;
        }


        //Make sure not already added to this User's Action Plan:
        if(count($this->Links_model->ln_fetch(array(
                'ln_creator_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            ))) > 0){

            //Oooops this already exists in the Action Plan:
            $this->Links_model->ln_create(array(
                'ln_parent_intent_id' => $in_id,
                'ln_content' => 'actionplan_intention_add() blocked the addition of a duplicate intention to the Action Plan',
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $en_id,
            ));

            return false;

        }

        $new_intent_order = 1 + ( $recommender_in_id > 0 ? 0 : $this->Links_model->ln_max_order(array( //Place this intent at the end of all intents the User is drafting...
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
                'ln_creator_entity_id' => $en_id, //Belongs to this User
            )));


        //Add intent to User's Action Plan:
        $actionplan = $this->Links_model->ln_create(array(
            'ln_type_entity_id' => ( $recommender_in_id > 0 ? 7495 /* User Intent Recommended */ : 4235 /* User Intent Set */ ),
            'ln_status_entity_id' => 6175, //Link Drafting
            'ln_creator_entity_id' => $en_id, //Belongs to this User
            'ln_parent_intent_id' => $ins[0]['in_id'], //The Intent they are adding
            'ln_child_intent_id' => $recommender_in_id, //Store the recommended intention
            'ln_order' => $new_intent_order,
        ));


        //If the top intention, move all other intentions down by one step:
        if($recommender_in_id > 0){

            foreach($this->Links_model->ln_fetch(array(
                'ln_id !=' => $actionplan['ln_id'], //Not the newly added intention
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
                'ln_status_entity_id' => 6175, //Link Drafting
                'ln_creator_entity_id' => $en_id, //Belongs to this User
            )) as $current_intentions){
                //Update order:
                $this->Links_model->ln_update($current_intentions['ln_id'], array(
                    'ln_order' => ($current_intentions['ln_order'] + 1),
                ));
            }

            if($echo_next_step){
                $this->Communication_model->dispatch_message(
                    'Ok let\'s ' . $ins[0]['in_outcome'],
                    array('en_id' => $en_id),
                    true
                );
            }

        } else {

            if($echo_next_step){
                $this->Communication_model->dispatch_message(
                    'Ok I added this intention to your Action Plan 🙌 /link:Open 🚩Action Plan:https://mench.com/actionplan/' . $ins[0]['in_id'],
                    array('en_id' => $en_id),
                    true
                );
            }

        }




        /*
         *
         * Not the immediate priority, so let them
         * know that we will get to this when we
         * get to it, unless they want to re-sort
         * their Action Plan.
         *
         * */

        //Fetch top intention that being workined on now:
        $top_priority = $this->User_app_model->actionplan_intention_focus($en_id);

        if($top_priority){
            if($recommender_in_id > 0 || $top_priority['in']['in_id']==$ins[0]['in_id']){

                if($echo_next_step){
                    //The newly added intent is the top priority, so let's initiate first message for action plan tree:
                    $this->User_app_model->actionplan_step_next_echo($en_id, $ins[0]['in_id']);
                }

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


            if($echo_next_step){
                //List Recommended Intents and let them choose:
                $this->Communication_model->dispatch_recommendations($en_id);
            }

        }

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
        $completion_rate = $this->User_app_model->actionplan_completion_progress($en_id, $in);


        if($completion_rate['completion_percentage'] < 100){
            //Not completed, so can't go further up:
            return array();
        }


        //Look at Conditional Steps ONLY at this level:
        $in_metadata = unserialize($in['in_metadata']);
        if(isset($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) && count($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) > 0){

            //Make sure previous link unlocks have NOT happened before:
            $existing_expansions = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 6140, //Action Plan Conditional Step Unlocked
                'ln_creator_entity_id' => $en_id,
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
                    'ln_parent_intent_id' => $in['in_id'],
                    'ln_child_intent_id' => $existing_expansions[0]['ln_child_intent_id'],
                    'ln_content' => 'actionplan_completion_recursive_up() detected duplicate Label Expansion entries',
                    'ln_type_entity_id' => 4246, //Platform Bug Reports
                    'ln_creator_entity_id' => $en_id,
                ));
                */

                return array();

            }


            //Yes, Let's calculate user's score for this tree:
            $user_marks = $this->User_app_model->actionplan_completion_marks($en_id, $in);





            //Detect potential conditional steps to be Unlocked:
            $found_match = 0;
            $locked_links = $this->Links_model->ln_fetch(array(
                'in_type_entity_id IN (' . join(',', $this->config->item('en_ids_7309')) . ')' => null, //Action Plan Step Locked
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 4229, //Intent Link Locked Step
                'ln_parent_intent_id' => $in['in_id'],
                'ln_child_intent_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ), array('in_child'), 0, 0);


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

                    //It did match here! Log and notify user!
                    $message = 'You completed the step to '.echo_in_outcome($in['in_outcome'], true, true).'. ';

                    //Append based on title type:
                    if(in_is_clean_outcome($locked_link)){
                        $message .= 'This unlocked a new step to '.echo_in_outcome($locked_link['in_outcome'], true);
                    } else {
                        $message .= 'The result:' . "\n\n" . echo_in_outcome($locked_link['in_outcome'], true);
                    }

                    //Give reference in Action Plan
                    $message .= ' /link:Open 🚩Action Plan:https://mench.com/actionplan/'.$locked_link['in_id'];


                    //Communicate message to user:
                    array_push($unlock_steps_messages, array(
                        'ln_content' => $message,
                    ));

                    //Unlock Action Plan:
                    $this->Links_model->ln_create(array(
                        'ln_status_entity_id' => 6176, //Link Published
                        'ln_type_entity_id' => 6140, //Action Plan Conditional Step Unlocked
                        'ln_creator_entity_id' => $en_id,
                        'ln_parent_intent_id' => $in['in_id'],
                        'ln_child_intent_id' => $locked_link['in_id'],
                        'ln_content' => $message,
                        'ln_metadata' => array(
                            'completion_rate' => $completion_rate,
                            'user_marks' => $user_marks,
                            'condition_ranges' => $locked_links,
                        ),
                    ));

                    //See if we also need to mark the child as complete:
                    $this->User_app_model->actionplan_completion_auto_complete($en_id, $locked_link, 6997 /* User Step Score Unlock */);

                }
            }

            //We must have exactly 1 match by now:
            if($found_match != 1){
                $this->Links_model->ln_create(array(
                    'ln_content' => 'actionplan_completion_recursive_up() found ['.$found_match.'] routing logic matches!',
                    'ln_type_entity_id' => 4246, //Platform Bug Reports
                    'ln_creator_entity_id' => $en_id,
                    'ln_parent_intent_id' => $in['in_id'],
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

            //Fetch user intentions:
            $user_intentions_ids = $this->User_app_model->actionplan_intention_ids($en_id);

            //Fetch all parents trees for this intent
            $recursive_parents = $this->Intents_model->in_fetch_recursive_public_parents($in['in_id']);

            //Prevent duplicate processes even if on multiple parent trees:
            $parents_checked = array();

            //Go through parents trees and detect intersects with user intentions. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
            foreach ($recursive_parents as $grand_parent_ids) {

                //Does this parent and its grandparents have an intersection with the user intentions?
                if(!array_intersect($grand_parent_ids, $user_intentions_ids)){
                    //Parent tree is NOT part of their Action Plan:
                    continue;
                }

                //Let's go through until we hit their intersection
                foreach($grand_parent_ids as $p_id){

                    //Make sure not duplicated:
                    if(in_array($p_id , $parents_checked)){
                        continue;
                    }

                    array_push($parents_checked, $p_id);

                    //Fetch parent intent:
                    $parent_ins = $this->Intents_model->in_fetch(array(
                        'in_id' => $p_id,
                        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                    ));

                    //Now see if this child completion resulted in a full parent completion:
                    if(count($parent_ins) > 0){

                        //Fetch parent completion:
                        $unlock_steps_messages_recursive = $this->User_app_model->actionplan_completion_recursive_up($en_id, $parent_ins[0], false);

                        //What did we find?
                        if(count($unlock_steps_messages_recursive) > 0){
                            $unlock_steps_messages = array_merge($unlock_steps_messages, $unlock_steps_messages_recursive);
                        }
                    }

                    //Terminate if we reached the Action Plan intention level:
                    if(in_array($p_id , $user_intentions_ids)){
                        break;
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
        $unlock_steps_messages = $this->User_app_model->actionplan_completion_recursive_up($en_id, $in);


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


    function actionplan_unlock_recursive_up($in_id, $is_bottom_level = true)
    {
        /*
         *
         * Checks the completed users at each step that's recursively up
         *
         * */

        foreach($this->Links_model->ln_fetch(array(
            'in_type_entity_id IN (' . join(',', $this->config->item('en_ids_7309')) . ')' => null, //Action Plan Step Locked
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public

            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_child_intent_id' => $in_id,
        ), array('in_parent')) as $in_locked_parent){

        }
    }

    function actionplan_unlock_locked_step($en_id, $in){

        /*
         * A function that starts from a locked intent and checks:
         *
         * 1. List users who have completed ALL/ANY (Depending on AND/OR Lock) of its children
         * 2. If > 0, then goes up recursively to see if these completions unlock other completions
         *
         * */

        if(!in_is_unlockable($in)){
            return array(
                'status' => 0,
                'message' => 'Not a valid locked intent type and status',
            );
        }


        $in__children = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        if(count($in__children) < 1){
            return array(
                'status' => 0,
                'message' => 'Intent has no child intents',
            );
        }



        /*
         *
         * Now we need to determine intent completion method.
         *
         * It's one of these two cases:
         *
         * AND Intents are completed when all their children are completed
         *
         * OR Intents are completed when a single child is completed
         *
         * */
        $requires_all_children = ( $in['in_type_entity_id'] == 6914 /*AND Lock, meaning all children are needed */ );

        //Generate list of users who have completed it:
        $qualified_completed_users = array();

        //Go through children and see how many completed:
        foreach($in__children as $count => $child_in){

            //Fetch users who completed this:
            if($count==0){

                //Always add all the first users to the full list:
                $qualified_completed_users = $this->Links_model->ln_fetch(array(
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
                    'ln_parent_intent_id' => $child_in['in_id'],
                ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

                if($requires_all_children && count($qualified_completed_users)==0){
                    //No users found that would meet all children requirements:
                    break;
                }

            } else {

                //2nd iteration onwards, by now we must have a base:
                if($requires_all_children){

                    //Update list of qualified users:
                    $qualified_completed_users = $this->Links_model->ln_fetch(array(
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
                        'ln_parent_intent_id' => $child_in['in_id'],
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


    function actionplan_step_next_echo($en_id, $in_id, $push_message = true)
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
                'ln_creator_entity_id' => $en_id,
                'ln_content' => 'actionplan_step_next_echo() called invalid intent',
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

            return array(
                'status' => 0,
                'message' => 'Invalid Intent #' . $ins[0]['in_id'],
            );

        } elseif (!in_array($ins[0]['in_status_entity_id'], $this->config->item('en_ids_7355') /* Intent Statuses Public */)) {

            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_creator_entity_id' => $en_id,
                'ln_content' => 'actionplan_step_next_echo() called intent that is not yet public',
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

            return array(
                'status' => 0,
                'message' => 'Invalid #' . $ins[0]['in_id'].' is not yet public',
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
        $completion_req_note = $this->Intents_model->in_create_new_content($ins[0], $push_message); //See if we have intent requirements
        $in__messages = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $ins[0]['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));
        $in__children = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            'ln_type_entity_id' => 4228, //Intent Link Regular Step
            'ln_parent_intent_id' => $ins[0]['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        $current_progression_links = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
            'ln_creator_entity_id' => $en_id,
            'ln_parent_intent_id' => $ins[0]['in_id'],
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ));

        $progress_completed = false; //Assume FALSE, search and see...
        foreach($current_progression_links as $current_progression_link){
            if(in_array($current_progression_link['ln_status_entity_id'], $this->config->item('en_ids_7359')/* Link Statuses Public */)){
                $progress_completed = true;
                break;
            }
        }


        //Define communication variables:
        $next_step_message = '';
        $next_step_quick_replies = array();

        //Define step variables:
        $has_children = (count($in__children) > 0);
        $unlock_paths = array();



        //Determine progression type
        //Let's figure out the progression method:
        //TODO Migrate this logic to entities and use something like array_intersect() to detect correlations
        if(in_is_unlockable($ins[0])){

            if($progress_completed){

                $progression_type_entity_id = $current_progression_links[0]['ln_status_entity_id'];

            } else {

                //Find the paths to unlock:
                $unlock_paths = $this->Intents_model->in_unlock_paths($ins[0]);

                //Set completion method:
                if(count($unlock_paths) > 0){

                    //Yes we have a path:
                    $progression_type_entity_id = 7486; //User Step Children Unlock

                } else {

                    //No path found:
                    $progression_type_entity_id = 7492; //User Step Dead End

                }
            }

        } elseif($ins[0]['in_type_entity_id']==7740 /* Intent Terminate */){

            $progression_type_entity_id = 7741; //User Step Intention Terminated

        } elseif($completion_req_note){

            $progression_type_entity_id = 6144; //User Step Requirement Sent

        } elseif($has_children && $ins[0]['in_type_entity_id']==6684 /* Intent Answer Single-Choice */){

            $progression_type_entity_id = 6157; //User Step Single-Answered

        } elseif($has_children && $ins[0]['in_type_entity_id']==6685 /* Intent Answer Single-Choice Timed */){

            $progression_type_entity_id = 7487; //User Step Single-Answered Timely
            $progression_type_entity_id = 6157; //TODO User Step Single-Answered (Remove after integration)

        } elseif($has_children && $ins[0]['in_type_entity_id']==7231 /* Intent Answer Multiple-Choice */){

            $progression_type_entity_id = 7489; //User Step Multi-Answered

        } else {

            $progression_type_entity_id = 4559; //User Step Read Messages

        }


        //Let's learn more about the nature of this progression link:

        //If TRUE, initial progression link will be logged as WORKING ON since we need user response:
        $is_two_step = in_array($progression_type_entity_id, $this->config->item('en_ids_6244'));

        //Action Plan Steps Progressed:
        $step_progress_made = ( !$is_two_step && in_array($progression_type_entity_id, $this->config->item('en_ids_6255')));

        //If TRUE, we will auto move on to the next item:
        $nothing_more_to_do = ( !$is_two_step && !$has_children && in_array($progression_type_entity_id, $this->config->item('en_ids_6274')) );

        //Assume FALSE unless $nothing_more_to_do=TRUE and we do not have any next steps which means user has finished their Action Plan:
        $recommend_recommend = false;



        if(count($current_progression_links)<1 || ( !$is_two_step && !$progress_completed )){

            //Log new link:
            $new_progression_link = $this->Links_model->ln_create(array(
                'ln_type_entity_id' => $progression_type_entity_id,
                'ln_creator_entity_id' => $en_id,
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
                $progress_completed = true;
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
        if(!$push_message){

            $unlocked_steps = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'ln_type_entity_id' => 6140, //User Step Link Unlocked
                'ln_creator_entity_id' => $en_id,
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ), array('in_child'), 0);

            //Did we have any steps unlocked?
            if(count($unlocked_steps) > 0){
                //Yes! Show them:
                $next_step_message .= '<div class="list-group" style="margin:0 0 0 0;">';
                foreach($unlocked_steps as $unlocked_step){
                    //Add HTML step to UI:
                    $next_step_message .= echo_actionplan_step_child($en_id, $unlocked_step, true);
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
        if(count($unlock_paths) > 0){


            if($push_message){
                $next_step_message .= 'Here are the intentions I recommend adding to your Action Plan to move forward:';
            } else {
                $next_step_message .= '<div class="list-group" style="margin-top:10px;">';
            }

            //List Unlock paths:
            foreach ($unlock_paths as $key => $child_in) {

                $child_progression_steps = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
                    'ln_creator_entity_id' => $en_id,
                    'ln_parent_intent_id' => $child_in['in_id'],
                    'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                ));

                $is_completed = ( count($child_progression_steps) > 0 && in_array($child_progression_steps[0]['ln_status_entity_id'], $this->config->item('en_ids_7359')));
                $is_next = ( count($next_step_quick_replies)==0 && !$is_completed );

                if(!$push_message){

                    //Add HTML step to UI:
                    $next_step_message .= echo_actionplan_step_child($en_id, $child_in);

                } else {

                    //Add simple message:
                    $next_step_message .= "\n\n" . ($key + 1) . '. ' . echo_in_outcome($child_in['in_outcome'], $push_message);
                    $next_step_message .= ( $is_completed ? ' [COMPLETED]' : '' );

                }

                //Add Call to Action:
                if($is_next){
                    //This is the next step:
                    array_push($next_step_quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'ADD_RECOMMENDED_' . $ins[0]['in_id']. '_' . $child_in['in_id'],
                    ));
                }

            }

            if(!$push_message){
                $next_step_message .= '</div>';
            }

        } elseif ($completion_req_note && !$progress_completed) {

            //They still need to complete:
            $next_step_message .= $completion_req_note;

        } elseif($has_children && in_array($ins[0]['in_type_entity_id'] , $this->config->item('en_ids_6193') /* OR Intents */ )){


            //Prep variables:
            $too_many_children = ( $push_message && count($in__children) > 10);


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

                //See if we need to move the message inside the HTML:
                $max_html_answers = 30;
                $inline_answers = array();

                for ($num = 1; $num <= $max_html_answers; $num++) {
                    foreach ($in__messages as $index => $message_ln) {

                        $valid_num = null;
                        if(substr_count($message_ln['ln_content'] , $num.'. ')==1){
                            $valid_num = $num.'. ';
                        } elseif(substr_count($message_ln['ln_content'] , $num.".\n")==1){
                            $valid_num = $num.".\n";
                        }

                        if($valid_num){
                            $inline_answers[$num] = one_two_explode($valid_num , "\n", $message_ln['ln_content']);
                            $in__messages[$index]['ln_content'] = trim(str_replace($valid_num.$inline_answers[$num], '', $message_ln['ln_content']));
                            break;
                        }
                    }
                    //Terminate if not found:
                    if(!isset($inline_answers[$num])){
                        break;
                    }
                }

                $next_step_message .= '<div class="list-group" style="margin-top:10px;">';

            }

            //List OR child answers:
            foreach ($in__children as $key => $child_in) {


                //Is this selected?
                $was_selected       = ( $progress_completed && $current_progression_links[0]['ln_child_intent_id']==$child_in['in_id'] );

                //Fetch history if selected:
                if($was_selected){
                    $child_progression_steps = $this->Links_model->ln_fetch(array(
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
                        'ln_creator_entity_id' => $en_id,
                        'ln_parent_intent_id' => $current_progression_links[0]['ln_child_intent_id'],
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                    ));
                }


                if($push_message){

                    if(!in_array(($key+1), $answer_referencing)){
                        $next_step_message .= "\n\n" . ($key+1).'. '.echo_in_outcome($child_in['in_outcome'], true);
                    }

                    //Add answer options to Quick Reply:
                    if(!$too_many_children){
                        array_push($next_step_quick_replies, array(
                            'content_type' => 'text',
                            'title' => ($key+1),
                            'payload' => 'ANSWERQUESTION_' . $progression_type_entity_id . '_' . $ins[0]['in_id'] . '_' . $child_in['in_id'],
                        ));
                    }

                } else {

                    if(!$progress_completed){

                        //Need to select answer:
                        $next_step_message .= '<a href="/user_app/actionplan_answer_question/6157/' . $en_id . '/' . $ins[0]['in_id'] . '/' . $child_in['in_id'] . '/' . md5($this->config->item('actionplan_salt') . $child_in['in_id'] . $ins[0]['in_id'] . $en_id) . '" class="list-group-item lightgreybg">';

                    } elseif($was_selected){

                        //This was selected:
                        $next_step_message .= '<a href="/actionplan/'.$child_in['in_id'] . '" class="list-group-item lightgreybg">';

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
                    $potential_number = trim(str_replace('.','', echo_in_outcome($child_in['in_outcome'], true)));
                    if(is_numeric($potential_number) && intval($potential_number)>0 && intval($potential_number)<=$max_html_answers && isset($inline_answers[intval($potential_number)])){
                        $next_step_message .= $inline_answers[intval($potential_number)];
                    } else {
                        $next_step_message .= echo_in_outcome($child_in['in_outcome']);
                    }

                }


                //HTML?
                if(!$push_message){

                    if($was_selected) {
                        //Status Icon:
                        $next_step_message .= '&nbsp;' . echo_en_cache('en_all_6186' /* Link Statuses */, (count($child_progression_steps) > 0 ? $child_progression_steps[0]['ln_status_entity_id'] : 6175 /* Link Drafting */), false, null);
                    }

                    //Close tags:
                    if(!$progress_completed || $was_selected){

                        $next_step_message .= '</a>';

                    } else {

                        $next_step_message .= '</span>';

                    }
                }
            }

            if(!$push_message){
                $next_step_message .= '</div>';
            } else {
                if($too_many_children) {
                    //Give instructions on how to select path:
                    //$next_step_message .= "\n\n" . 'Choose your answers by replying a number 1-12';
                }
            }

        } elseif($has_children){

            //AND Children
            $max_and_list           = ( $push_message ? 5 : 30 );
            $has_multiple_children  = (count($in__children) > 1); //Do we have 2 or more children?
            $frist_x_all_are_dirty  = true; //Assume all first X intent outcomes are non-clean unless proven otherwise


            //Check to see if we need titles:
            if($has_multiple_children){
                foreach ($in__children as $count => $child_in) {
                    if($count==$max_and_list){
                        break;
                    }
                    if(in_is_clean_outcome($child_in)){
                        $frist_x_all_are_dirty = false;
                        break;
                    }
                }
            }

            //List AND children:
            if($has_multiple_children && !$frist_x_all_are_dirty){

                //Are we still clean?
                $key = 0;
                $common_prefix = common_prefix($in__children, $max_and_list); //Look only up to the max number of listed intents

                foreach ($in__children as $child_in) {

                    if($key==0){
                        if(!$push_message){
                            $next_step_message .= '<div class="list-group" style="margin-top:10px;">';
                        } else {
                            $next_step_message .= "Here are the next steps:"."\n\n";
                        }
                    } else {
                        if($push_message){

                            $next_step_message .= "\n\n";

                            //We know that the $next_step_message length cannot surpass the limit defined by fb_max_message variable!
                            if (($key >= $max_and_list || strlen($next_step_message) > ($this->config->item('fb_max_message') - 150))) {
                                //We cannot add any more, indicate truncating:
                                $remainder = count($in__children) - $max_and_list;
                                $next_step_message .= '... plus ' . $remainder . ' more step' . echo__s($remainder) . '.';
                                break;
                            }
                        }
                    }



                    if(!$push_message){

                        //Add HTML step to UI:
                        $next_step_message .= echo_actionplan_step_child($en_id, $child_in, false, $common_prefix);

                    } else {

                        //Add simple message:
                        $next_step_message .= ($key + 1) . '. ' . echo_in_outcome($child_in['in_outcome'], $push_message, false, false, $common_prefix);

                    }

                    $key++;
                }

                if(!$push_message && $key > 0){
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
            $on_complete_messages = $this->User_app_model->actionplan_completion_checks($en_id, $ins[0], false, $step_progress_made);

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
        if(!$is_two_step || $progress_completed){

            $next_in_id = 0;
            if(!$has_children){
                //Let's see if we have a next step:
                $next_in_id = $this->User_app_model->actionplan_step_next_go($en_id, false);
            }

            if($has_children || $next_in_id>0){
                //Option to go next:
                if($push_message){

                    array_push($next_step_quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'GONEXT',
                    ));

                } else {

                    $next_step_message .= '<div style="margin: 15px 0 0;"><a href="/actionplan/next" class="btn btn-md btn-primary">Next <i class="fas fa-angle-right"></i></a></div>';

                }
            } else {

                //This will happen for all intents viewed via HTML if all Action Plan steps are completed
                //No next step found! Recommend if messenger
                $recommend_recommend = $push_message;

            }
        }

        //SKIP?
        if($user_can_skip) {
            //Give option to skip:
            if($push_message){

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
        $compile_html_message = null; //Will be useful only IF $push_message=FALSE
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
                $push_message,
                //This is when we have messages and need to append the "Next" Quick Reply to the last message:
                ( $last_message_accepts_quick_replies && count($next_step_quick_replies) > 0 ? $next_step_quick_replies : array() )
            );
        }
        if($push_message) {

            if(strlen($next_step_message) > 0 || (!$last_message_accepts_quick_replies && count($next_step_quick_replies) > 0)){
                //Send messages over Messenger IF we have a message
                $this->Communication_model->dispatch_message(
                    ( strlen($next_step_message) > 0 ? $next_step_message : echo_random_message('goto_next') ),
                    array('en_id' => $en_id),
                    true,
                    $next_step_quick_replies
                );
            }

            if($recommend_recommend){
                //List Recommended Intents and let them choose:
                $this->Communication_model->dispatch_recommendations($en_id);
            }

        }







        //Return data:
        return array(
            'status' => 1,
            'message' => 'Success',
            'current_progression_links' => $current_progression_links,

            //Do we need to return the HTML UI?
            'html_messages' => ( $push_message ? null : $compile_html_message . '<div class="msg" style="margin-top: 15px;">'.nl2br($next_step_message).'</div>' ),
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
                'ln_creator_entity_id' => $en_id,
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
                    'ln_type_entity_id' => 4228, //Intent Link Regular Step
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
                'ln_creator_entity_id' => $en_id, //Belongs to this User
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
                'ln_parent_intent_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )) as $expansion_in) {

                //Addup data for this intent:
                $metadata_this['steps_answered_count'] += 1;

                //Calculate if answered:
                if(in_array($expansion_in['ln_type_entity_id'], $this->config->item('en_ids_7704') /* User Step Answered Successfully */)){

                    //Fetch intent data:
                    $ins = $this->Intents_model->in_fetch(array(
                        'in_id' => $expansion_in['ln_child_intent_id'],
                        'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                    ));

                    if(count($ins) > 0){
                        //Fetch recursive:
                        $recursive_stats = $this->User_app_model->actionplan_completion_marks($en_id, array_merge($expansion_in, $ins[0]), false);
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
            'ln_creator_entity_id' => $en_id, //Belongs to this User
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
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7704') ) . ')' => null, //User Step Answered Successfully
                'ln_creator_entity_id' => $en_id, //Belongs to this User
                'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_child_intent_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_steps'])) . ')' => null,
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            ), array('in_child')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->User_app_model->actionplan_completion_progress($en_id, $expansion_in, false);

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
                'ln_creator_entity_id' => $en_id, //Belongs to this User
                'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_child_intent_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_conditional'])) . ')' => null,
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            ), array('in_child')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->User_app_model->actionplan_completion_progress($en_id, $expansion_in, false);

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
            $metadata_this['completion_percentage'] = intval(floor( ($metadata_this['seconds_completed']+($step_default_seconds*$metadata_this['steps_completed'])) / ($metadata_this['seconds_total']+($step_default_seconds*$metadata_this['steps_total'])) * 100 ));

        }


        //Return results:
        return $metadata_this;

    }


    function actionplan_intention_ids($en_id){
        //Simply returns all the intention IDs for a user's Action Plan:
        $user_intentions_ids = array();
        foreach($this->Links_model->ln_fetch(array(
            'ln_creator_entity_id' => $en_id,
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_parent'), 0) as $user_in){
            array_push($user_intentions_ids, intval($user_in['in_id']));
        }
        return $user_intentions_ids;
    }

}