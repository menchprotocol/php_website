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


    function actionplan_recursive_next_step($en_id, $in){

        /*
         *
         * Searches within a student Action Plan to find
         * first incomplete step.
         *
         * */

        $in_metadata = unserialize($in['in_metadata']);
        foreach(array_flatten($in_metadata['in__metadata_common_steps']) as $common_step_in_id){

            //Is this an expansion step?
            $is_expansion = (isset($in_metadata['in__metadata_expansion_steps'][$common_step_in_id]) || isset($in_metadata['in__metadata_expansion_milestones'][$common_step_in_id]));

            //Is this completed?
            $completed_steps = $this->Links_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',' , $this->config->item('en_ids_6146')) . ')' => null,
                'ln_miner_entity_id' => $en_id, //Belongs to this Student
                'ln_parent_intent_id' => $common_step_in_id,
                'ln_status' => 2, //Published
            ), ( $is_expansion ? array('in_child') : array() ));

            //Have they completed this?
            if(count($completed_steps) == 0){

                //Not completed yet, this is the next step:
                return $common_step_in_id;

            } elseif($is_expansion){

                //Completed step that has OR expansions, check recursively to see if next step within here:
                $found_in_id = $this->Actionplan_model->actionplan_recursive_next_step($en_id, $completed_steps[0]);

                if($found_in_id > 0){
                    return $found_in_id;
                }

            }

        }

        //Nothing found!
        return 0;

    }

    function actionplan_find_next_step($en_id, $advance_step, $send_title_message = false)
    {

        /*
         *
         * Searches for the next Action Plan step
         * and advance it IF $advance_step = TRUE
         *
         * */

        $student_intents = $this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
            'in_status' => 2, //Published
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

        if(count($student_intents) == 0){

            if($advance_step){

                $this->Communication_model->dispatch_message(
                    'You have no intentions added to your Action Plan yet.',
                    array('en_id' => $en_id),
                    true
                );

                //List featured intents and let them choose:
                $this->Communication_model->suggest_featured_intents($en_id);

            }

            //No Action Plans found!
            return false;

        }


        //Looop through Action Plan intentions and see what's next:
        foreach($student_intents as $student_intent){

            //Find first incomplete step for this Action Plan intention:
            $next_in_id = $this->Actionplan_model->actionplan_recursive_next_step($en_id, $student_intent);

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

                    //Yes, we do have a next step, fetch it and give student more details:
                    $next_step_ins = $this->Intents_model->in_fetch(array(
                        'in_id' => $next_in_id,
                    ));

                    //Inform of intent title only if its a clean title:
                    if(is_clean_outcome($next_step_ins[0])){
                        $this->Communication_model->dispatch_message(
                            'Let\'s '. $next_step_ins[0]['in_outcome'],
                            array('en_id' => $en_id),
                            true
                        );
                    }

                }

                //Yes, communicate it:
                $this->Actionplan_model->actionplan_advance_step(array('en_id' => $en_id), $next_in_id);

            } else {

                //Inform user that they are now complete with all steps:
                $this->Communication_model->dispatch_message(
                    'You have no pending steps in your Action Plan 🙌 I will keep you updated on new steps as they become available. You may also stop receiving updates by saying "stop".',
                    array('en_id' => $en_id),
                    true
                );

                //List featured intents and let them choose:
                $this->Communication_model->suggest_featured_intents($en_id);

            }
        }

        //Return next step intent or false:
        return $next_in_id;

    }

    function actionplan_skip_initiate($en, $in_id, $fb_messenger_format = true){

        //Fetch this intent:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status' => 2,
        ));
        if(count($ins) < 1){
            $this->Links_model->ln_create(array(
                'ln_child_entity_id' => $en['en_id'],
                'ln_child_intent_id' => $in_id,
                'ln_content' => 'actionplan_skip_initiate() did not locate the published intent',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return false;
        }

        $skip_message = 'You are about to skip the intention to '.echo_in_outcome($ins[0]['in_outcome'], true, true).' which is ' . echo_step_range($ins[0], true) . '. I encourage you to continue so you have the maximum chance for success 🙏';

        if(!$fb_messenger_format){

            //Just return the message for HTML format:
            return $skip_message;

        } else {

            //Send over messenger:
            $this->Communication_model->dispatch_message(
                $skip_message,
                $en,
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Skip 🚫',
                        'payload' => 'SKIP-ACTIONPLAN_2_'.$in_id, //Confirm and skip
                    ),
                    array(
                        'content_type' => 'text',
                        'title' => 'Continue ▶️',
                        'payload' => 'SKIP-ACTIONPLAN_-1_'.$in_id, //Cancel skipping
                    ),
                ),
                array(
                    'ln_parent_intent_id' => $in_id,
                )
            );

        }
    }

    function actionplan_skip_recursive_down($en_id, $in_id)
    {

        //Fetch intent common steps:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status' => 2, //Published
        ));
        if(count($ins) < 1){
            $this->Links_model->ln_create(array(
                'ln_content' => 'actionplan_skip_recursive_down() failed to locate published intent',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $en_id,
                'ln_parent_intent_id' => $in_id,
            ));
            return 0;
        }


        $in_metadata = unserialize( $ins[0]['in_metadata'] );

        if(!isset($in_metadata['in__metadata_common_steps'])){
            $this->Links_model->ln_create(array(
                'ln_content' => 'actionplan_skip_recursive_down() failed to locate metadata common steps',
                'ln_type_entity_id' => 4246, //Platform Error
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
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $common_in_id,
                'ln_status >=' => 0, //New+
            ));

            //Add skip link:
            $new_progression_link = $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 6143, //Action Plan Skipped Step
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $common_in_id,
                'ln_status' => 2, //Published
            ));

            //Archive current progression links:
            foreach($current_progression_links as $ln){
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_parent_link_id' => $new_progression_link['ln_id'],
                    'ln_status' => -1,
                ), $en_id);
            }

        }

        //Return number of skipped steps:
        return count($flat_common_steps);
    }

    function actionplan_top_priority($en_id){

        /*
         *
         * A function that goes through the Action Plan
         * and finds the top-priority that the student
         * is currently working on.
         *
         * */

        $top_priority_in = false;
        foreach($this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
            'in_status' => 2, //Published
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC')) as $actionplan_in){

            //See progress rate so far:
            $completion_rate = $this->Actionplan_model->actionplan_completion_rate($actionplan_in, $en_id);

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

    function actionplan_add($en_id, $in_id){

        //Validate Intent ID and ensure it's published:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status' => 2, //Published
        ));

        if (count($ins) != 1) {
            return false;
        }

        //Add intent to Student's Action Plan:
        $actionplan = $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status' => 1, //Drafting
            'ln_miner_entity_id' => $en_id, //Belongs to this Student
            'ln_parent_intent_id' => $ins[0]['in_id'], //The Intent they are adding
            'ln_order' => 1 + $this->Links_model->ln_max_order(array( //Place this intent at the end of all intents the Student is drafting...
                    'ln_type_entity_id' => 4235, //Action Plan Set Intention
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                    'ln_miner_entity_id' => $en_id, //Belongs to this Student
                )),
        ));

        //Confirm with them that we're now ready:
        $this->Communication_model->dispatch_message(
            'I have successfully added the intention to ' . $ins[0]['in_outcome'] . ' to your Action Plan 🙌 /link:Open 🚩Action Plan:https://mench.com/messenger/actionplan/' . $ins[0]['in_id'],
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
        $top_priority = $this->Actionplan_model->actionplan_top_priority($en_id);

        if($top_priority){
            if($top_priority['in']['in_id']==$ins[0]['in_id']){

                //The newly added intent is the top priority, so let's initiate first message for action plan tree:
                $this->Actionplan_model->actionplan_advance_step(array('en_id' => $en_id), $ins[0]['in_id']);

            } else {

                //A previously added intent is top-priority, so let them know:
                $this->Communication_model->dispatch_message(
                    'But we will work on this intention later because based on your Action Plan\'s priorities, your current focus is to '.$top_priority['in']['in_outcome'].' which you have made '.$top_priority['completion_rate']['completion_percentage'].'% progress so far. Alternatively, you can sort your Action Plan\'s priorities. /link:Sort 🚩Action Plan:https://mench.com/messenger/actionplan',
                    array('en_id' => $en_id),
                    true
                );

            }
        } else {

            //It seems the student already have this intention as completed:
            $this->Communication_model->dispatch_message(
                'You seem to have completed this intention before, so there is nothing else to do now.',
                array('en_id' => $en_id),
                true
            );

            //List featured intents and let them choose:
            $this->Communication_model->suggest_featured_intents($en_id);

        }

        return true;

    }

    function actionplan_complete_if_empty($en_id, $in){

        /*
         *
         * A function that marks an intent as complete IF
         * the intent has nothing of substance to be
         * further communicated.
         *
         * */

        $no_message = (count($this->Links_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4231, //Intent Note Messages
                'ln_child_intent_id' => $in['in_id'],
            )))==0);

        $no_children = (count($this->Links_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'in_status' => 2, //Published
                'ln_type_entity_id' => 4228, //Fixed intent links only
                'ln_parent_intent_id' => $in['in_id'],
            ), array('in_child')))==0);

        $no_requirements = ($in['in_requirement_entity_id']==6087);


        if($no_message && $no_children && $no_requirements){

            //It should be auto completed:
            $new_progression_link = $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 6158, //Action Plan Outcome Review
                'ln_miner_entity_id' => $en_id,
                'ln_parent_intent_id' => $in['in_id'],
                'ln_status' => 2, //Published
            ));

        }
    }

    function actionplan_advance_step($recipient_en, $in_id, $fb_messenger_format = true)
    {

        /*
         *
         * Advance the student action plan by 1 step
         *
         * - $in_id:            The next step intent to be completed now
         *
         * - $recipient_en:     The recipient who will receive the messages via
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

        } elseif (!isset($recipient_en['en_id'])) {

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
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'actionplan_advance_step() called invalid intent',
                'ln_child_entity_id' => $recipient_en['en_id'],
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

            return array(
                'status' => 0,
                'message' => 'Invalid Intent #' . $ins[0]['in_id'],
            );

        } elseif ($ins[0]['in_status'] != 2) {

            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'actionplan_advance_step() called unpublished intent',
                'ln_child_entity_id' => $recipient_en['en_id'],
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

            return array(
                'status' => 0,
                'message' => 'Invalid #' . $ins[0]['in_id'].' is not yet published',
            );

        }


        /*
         *
         * Make sure we have full student information
         * as it might be needed for the messages
         * we're about to send out.
         *
         * */
        if(!isset($recipient_en['en_name'])){
            //Let's fetch full details:
            $ens = $this->Entities_model->en_fetch(array('en_id' => $recipient_en['en_id']));
            $recipient_en = $ens[0];
        }



        /*
         *
         * There are different ways to complete an intent
         * as listed under Action Plan Progression Link Types:
         *
         * https://mench.com/entities/6146
         *
         * We'll start by assuming the most basic form of
         * completion (Action Plan Outcome Review) and
         * build-up to more advance forms of completion
         * as we gather more data through-out this function.
         *
         * Also note that some progression types follow a
         * 2-step completion method where students are
         * required to submit their response in order
         * to move to the next step as defined by
         * Action Plan 2-Step Link Types:
         *
         * https://mench.com/entities/6244
         *
         * */


        //Fetch submission requirements, messages, children and current progressions (if any):
        $completion_req_note = $this->Intents_model->in_req_completion($ins[0], $fb_messenger_format); //See if we have intent requirements
        $in__messages = $this->Links_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $ins[0]['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));
        $in__children = $this->Links_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'in_status' => 2, //Published
            'ln_type_entity_id' => 4228, //Fixed intent links only
            'ln_parent_intent_id' => $ins[0]['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));
        $current_progression_links = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
            'ln_miner_entity_id' => $recipient_en['en_id'],
            'ln_parent_intent_id' => $ins[0]['in_id'],
            'ln_status >=' => 0, //New+ [Fetch all types of progress)
        ));

        //Define communication variables:
        $next_step_message = '';
        $next_step_quick_replies = array();

        //Define step variables:
        $has_children = (count($in__children) > 0);


        //Let's figure out the progression method:
        if($ins[0]['in_type']==0 /* AND */ && $completion_req_note){
            $progression_type_entity_id = 6144; //Action Plan Requirement Submitted
        } elseif($ins[0]['in_type']==1 /* OR */ && $has_children){
            $progression_type_entity_id = 6157; //Action Plan Question Answered
        } elseif(count($in__messages) > 0){
            $progression_type_entity_id = 4559; //Action Plan Messages Read
        } else {
            $progression_type_entity_id = 6158; //Action Plan Outcome Review
        }


        //Let's learn more about the nature of this progression link:
        $is_two_step        = in_array($progression_type_entity_id, $this->config->item('en_ids_6244')); //If TRUE, initial progression link will be logged as WORKING ON since we need student response
        $trigger_completion = ( !$is_two_step && in_array($progression_type_entity_id, $this->config->item('en_ids_6255'))); //If TRUE AND If !$is_two_step, this would trigger the completion tips
        $nothing_more_to_do = ( !$is_two_step && !$has_children && in_array($progression_type_entity_id, $this->config->item('en_ids_6274')) ); //If TRUE, we will auto move on to the next item
        $recommend_featured = false; //Assume FALSE unless $nothing_more_to_do=TRUE and we do not have any next steps which means student has finished their Action Plan




        /*
         *
         * Before getting started with communications,
         * Let's see if we need to log a new progress
         * link for this step of the Action Plan.
         *
         * */
        $made_published_progress = false; //Assume FALSE, search and see...
        foreach($current_progression_links as $current_progression_link){
            if($current_progression_link['ln_status']==2){
                $made_published_progress = true;
                break;
            }
        }

        if(count($current_progression_links)<1 || ( !$is_two_step && !$made_published_progress )){

            //Log new link:
            $new_progression_link = $this->Links_model->ln_create(array(
                'ln_type_entity_id' => $progression_type_entity_id,
                'ln_miner_entity_id' => $recipient_en['en_id'],
                'ln_parent_intent_id' => $ins[0]['in_id'],
                'ln_status' => ( $is_two_step ? 1 /* Needs more work */ : 2 /* Published */ ),
            ));

            //Since we logged a new progression, let's remove the old ones if any:
            if(!$is_two_step && count($current_progression_links) > 0){
                //Archive previous progression links since new one was logged:
                foreach($current_progression_links as $key=>$ln){

                    $this->Links_model->ln_update($ln['ln_id'], array(
                        'ln_parent_link_id' => $new_progression_link['ln_id'],
                        'ln_status' => -1,
                    ), $recipient_en['en_id']);

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
        $student_can_skip = true; //Assume TRUE unless proven otherwise...
        foreach($current_progression_links as $current_progression_link){
            if($student_can_skip && !$has_children && $current_progression_link['ln_status']==2){
                //Also make sure this was NOT an automated progression because there is no point in skipping those:
                if(in_array($current_progression_link['ln_type_entity_id'], $this->config->item('en_ids_6274'))){
                    $student_can_skip = false;
                    break;
                }
            }
        }







        /*
         *
         * Now let's see the intent type (AND or OR)
         * and also count its children to see how
         * we would need to advance the student.
         *
         * */

        //Do we have any requirements?
        if ($completion_req_note && !$made_published_progress) {

            //They still need to complete:
            $next_step_message .= $completion_req_note;

            //Give option to complete:
            if($fb_messenger_format){
                array_push($next_step_quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'OK',
                    'payload' => 'INFORMREQUIREMENT_' . $ins[0]['in_id'],
                ));
            }

        } elseif($has_children && $ins[0]['in_type']==1 /* OR Children */){


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

                if ($fb_messenger_format && ($key >= 10 || strlen($next_step_message) > ($this->config->item('fb_max_message') - 150))) {
                    //Log error link so we can look into it:
                    $this->Links_model->ln_create(array(
                        'ln_miner_entity_id' => 1, //Shervin/Developer
                        'ln_content' => 'actionplan_advance_step() encountered intent with too many children to be listed as OR Intent options! Trim and iterate that intent tree.',
                        'ln_type_entity_id' => 4246, //Platform Error
                        'ln_parent_intent_id' => $ins[0]['in_id'],
                        'ln_child_intent_id' => $child_in['in_id'],
                        'ln_child_entity_id' => $recipient_en['en_id'],
                    ));

                    //Quick reply accepts 11 options max:
                    break;
                }


                //Is this selected?
                $was_selected = ( $made_published_progress && $current_progression_links[0]['ln_child_intent_id']==$child_in['in_id'] );

                //Fetch history if selected:
                if($was_selected){
                    $child_progression_steps = $this->Links_model->ln_fetch(array(
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
                        'ln_miner_entity_id' => $recipient_en['en_id'],
                        'ln_parent_intent_id' => $current_progression_links[0]['ln_child_intent_id'],
                        'ln_status >=' => 0,
                    ));
                }


                if($fb_messenger_format){

                    if(!in_array(($key+1), $answer_referencing)){
                        $next_step_message .= "\n\n" . ($key+1).'. '.echo_in_outcome($child_in['in_outcome'], true);
                    }

                    //Always add answer options to Quick Reply:
                    array_push($next_step_quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($key+1),
                        'payload' => 'ANSWERQUESTION_' . $ins[0]['in_id'] . '_' . $child_in['in_id'],
                    ));

                } else {

                    if(!$made_published_progress){
                        //Need to select answer:
                        $next_step_message .= '<a href="/messenger/actionplan_answer_question/' . $recipient_en['en_id'] . '/' . $ins[0]['in_id'] . '/' . $child_in['in_id'] . '/' . md5($this->config->item('actionplan_salt') . $child_in['in_id'] . $ins[0]['in_id'] . $recipient_en['en_id']) . '" class="list-group-item">';
                    } elseif($was_selected){
                        //This was selected:
                        $next_step_message .= '<a href="/messenger/actionplan/'.$child_in['in_id'] . '" class="list-group-item">';
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
                    $next_step_message .= echo_in_outcome($child_in['in_outcome'], true);

                }


                //HTML?
                if(!$fb_messenger_format){

                    if($was_selected) {
                        //Status Icon:
                        $next_step_message .= '&nbsp;' . echo_fixed_fields('ln_student_status', (count($child_progression_steps) > 0 ? $child_progression_steps[0]['ln_status'] : 0), false, null);
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
            }



        } elseif($has_children && $ins[0]['in_type']==0 /* AND Children */){

            //Give more context for Messenger only:
            if($fb_messenger_format){
                if(count($in__children) == 1){
                    //A single next step:
                    $next_step_message .= 'There is a single step to ' . echo_in_outcome($ins[0]['in_outcome'], true, true);
                } else {
                    //Multiple next steps:
                    $next_step_message .= 'There are ' . count($in__children) . ' steps to ' . echo_in_outcome($ins[0]['in_outcome'], true, true);
                }
            }

            //List AND children:
            $key = 0;
            foreach ($in__children as $child_in) {

                //We require a clean title for Messenger:
                if($fb_messenger_format && !is_clean_outcome($child_in)){
                    continue;
                }

                if($key==0){
                    if($fb_messenger_format){
                        $next_step_message .= ':';
                    } else {
                        $next_step_message .= '<div class="list-group" style="margin-top:10px;">';
                    }
                }

                //We know that the $next_step_message length cannot surpass the limit defined by fb_max_message variable!
                //make sure message is within range:
                if ($fb_messenger_format && ($key >= 7 || strlen($next_step_message) > ($this->config->item('fb_max_message') - 150))) {
                    //We cannot add any more, indicate truncating:
                    $remainder = count($in__children) - $key;
                    $next_step_message .= "\n\n" . '... plus ' . $remainder . ' more step' . echo__s($remainder) . '.';
                    break;
                }


                //Fetch progression data:
                $child_progression_steps = $this->Links_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
                    'ln_miner_entity_id' => $recipient_en['en_id'],
                    'ln_parent_intent_id' => $child_in['in_id'],
                    'ln_status >=' => 0,
                ));


                if(!$fb_messenger_format){

                    //Completion Percentage so far:
                    $completion_rate = $this->Actionplan_model->actionplan_completion_rate($child_in, $recipient_en['en_id']);

                    //Open list:
                    $next_step_message .= '<a href="/messenger/actionplan/'.$child_in['in_id']. '" class="list-group-item">';

                    //Simple right icon
                    $next_step_message .= '<span class="pull-right" style="margin-top: -6px;">';
                    $next_step_message .= '<span class="badge badge-primary"  data-toggle="tooltip" data-placement="top" title="'.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Steps Completed" style="text-decoration:none;"><span style="font-size:0.7em;">'.$completion_rate['completion_percentage'].'%</span> <i class="fas fa-angle-right"></i>&nbsp;</span>';
                    $next_step_message .= '</span>';

                    //Determine what icon to show:
                    if(count($child_progression_steps) > 0){
                        //We do have a progression link...
                        if($child_progression_steps[0]['ln_status']==2){
                            //Status is complete, show the progression type icon:
                            $en_all_6146 = $this->config->item('en_all_6146');
                            $next_step_message .= $en_all_6146[$child_progression_steps[0]['ln_type_entity_id']]['m_icon'];
                        } else {
                            //Status is not yet complete, so show the status icon:
                            $next_step_message .= echo_fixed_fields('ln_student_status', $child_progression_steps[0]['ln_status'], true, null);
                        }
                    } else {
                        //No progression, so show a new icon:
                        $next_step_message .= echo_fixed_fields('ln_student_status', 0, true, null);
                    }

                    $next_step_message .= '&nbsp;';

                } else {

                    //Add message:
                    $next_step_message .= "\n\n" . ($key + 1) . '. ';

                }


                $next_step_message .= echo_in_outcome($child_in['in_outcome'], true);

                if(!$fb_messenger_format){

                    $next_step_message .= '</a>';

                }

                $key++;
            }

            if(!$fb_messenger_format && $key > 0){
                //Close the HTML tag we opened:
                $next_step_message .= '</div>';
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
                $next_in_id = $this->Actionplan_model->actionplan_find_next_step($recipient_en['en_id'], false);
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
                $recommend_featured = true;
            }
        }

        //SKIP?
        if($student_can_skip) {
            //Give option to skip:
            if($fb_messenger_format){

                //Give option to skip Student Intent:
                array_push($next_step_quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Skip',
                    'payload' => 'SKIP-ACTIONPLAN_1_' . $ins[0]['in_id'],
                ));

            } else {

                $next_step_message .= '<div style="font-size: 0.7em; margin-top: 10px;">Or <a href="javascript:void(0);" onclick="actionplan_skip_steps(' . $recipient_en['en_id'] . ', ' . $ins[0]['in_id'] . ')"><u>Skip</u></a>.</div>';

            }
        }







        /*
         *
         * Let's start dispatch Messenger messages
         *
         * */
        //Check to see if completion notes needs to be dispatched via Messenger?
        if($made_published_progress && $trigger_completion){

            //Add on-complete messages (if any) to the current messages:
            $in__messages = array_merge($in__messages, $this->Links_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 6242, //On-Complete Tips
                'ln_child_intent_id' => $ins[0]['in_id'],
            ), array(), 0, 0, array('ln_order' => 'ASC')));

        }



        $compile_html_message = null; //Will be useful only IF $fb_messenger_format=FALSE
        $last_message_accepts_quick_replies = false; //Assume FALSE unless proven otherwise...
        foreach ($in__messages as $count => $message_ln) {

            //Since we can only append quick replies to text messages, let's see what is happening here:
            $is_last_message = ( $count == (count($in__messages)-1) );
            if($is_last_message && $message_ln['ln_parent_entity_id']==0){
                //Since there is no entity reference we can append our message here:
                $last_message_accepts_quick_replies = true;
            }

            $compile_html_message .= $this->Communication_model->dispatch_message(
                $message_ln['ln_content'],
                $recipient_en,
                $fb_messenger_format,
                //This is when we have messages and need to append the "Next" Quick Reply to the last message:
                ( $last_message_accepts_quick_replies && count($next_step_quick_replies) > 0 ? $next_step_quick_replies : array() ),
                array(
                    'ln_parent_intent_id' => $ins[0]['in_id'],
                    'ln_parent_link_id' => $message_ln['ln_id'], //This message
                )
            );
        }
        if($fb_messenger_format) {

            if(strlen($next_step_message) > 0 || (!$last_message_accepts_quick_replies && count($next_step_quick_replies) > 0)){
                //Send messages over Messenger IF we have a message
                $this->Communication_model->dispatch_message(
                    ( strlen($next_step_message) > 0 ? $next_step_message : echo_random_message('goto_next') ),
                    $recipient_en,
                    true,
                    $next_step_quick_replies,
                    array(
                        'ln_parent_intent_id' => $ins[0]['in_id'], //Focus Intent
                    )
                );
            }

            if($recommend_featured){
                //List featured intents and let them choose:
                $this->Communication_model->suggest_featured_intents($recipient_en['en_id']);
            }

        }





        /*
         *
         * Post-Progression Automated Actions now...
         *
         * */








        //Return data:
        return array(
            'status' => 1,
            'message' => 'Success',

            //Do we need to return the HTML UI?
            'html_messages' => ( $fb_messenger_format ? null : $compile_html_message . '<div class="msg" style="margin-top: 15px;">'.nl2br($next_step_message).'</div>' ),
            'progression_links' => ( $fb_messenger_format ? null : $current_progression_links ),
        );

    }

    function actionplan_completion_rate($in, $miner_en_id, $top_level = true)
    {

        //Fetch/validate Action Plan Common Steps:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){

            //Should not happen, log error:
            $this->Links_model->ln_create(array(
                'ln_content' => 'actionplan_completion_rate() Detected student Action Plan without in__metadata_common_steps value!',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_entity_id' => $miner_en_id,
                'ln_parent_intent_id' => $in['in_id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Count totals:
        $common_totals = $this->Intents_model->in_fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status' => 2, //Published
        ), array(), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_seconds_cost) as total_seconds');

        //Count completed for student:
        $common_completed = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
            'ln_miner_entity_id' => $miner_en_id, //Belongs to this Student
            'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
            'ln_status' => 2, //Published
            'in_status' => 2, //Published
        ), array('in_parent'), 0, 0, array(), 'COUNT(in_id) as completed_steps, SUM(in_seconds_cost) as completed_seconds');

        //Calculate common steps and expansion steps recursively for this student:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_steps']),
            'steps_completed' => intval($common_completed[0]['completed_steps']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );

        //Fetch expansion steps recursively, if any:
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0){

            foreach($this->Links_model->ln_fetch(array(
                'ln_type_entity_id' => 6157, //Action Plan Question Answered
                'ln_miner_entity_id' => $miner_en_id, //Belongs to this Student
                'ln_parent_intent_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_child_intent_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_steps'])) . ')' => null,
                'ln_status' => 2, //Published
                'in_status' => 2, //Published
            ), array('in_child')) as $expansion_in){

                //Fetch recursive:
                $recursive_stats = $this->Actionplan_model->actionplan_completion_rate($expansion_in, $miner_en_id, false);

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
             * 2) estimated seconds (usually accurate)
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


    function deprecate__actionplan_trigger_webhooks($in_id, $en_id, $student_in_ids = null){

        //Search and see if this intent has any Webhooks:
        foreach($this->Links_model->ln_fetch(array(
            'in_status' => 2, //Published
            'ln_status' => 2, //Published
            'ln_type_entity_id' => 0, //TODO REMOVE SOON 4602! Intent Note Webhooks
            'ln_child_intent_id' => $in_id,
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $webhook_entity){

            //Find all the URLs for this Webhook:
            foreach($this->Links_model->ln_fetch(array(
                'ln_type_entity_id' => 4256, //Linked Entities Generic URL
                'ln_child_entity_id' => $webhook_entity['ln_parent_entity_id'], //This child entity
                'ln_status' => 2, //Published
            )) as $webhook_url){

                //Prep filter for search/insert:
                $filter = array(
                    'ln_status' => 2,
                    'ln_miner_entity_id' => $en_id,
                    'ln_type_entity_id' => 0, //TODO REMOVE SOON 6277! Action Plan Progression Trigger Webhook
                    'ln_parent_intent_id' => $in_id,
                    'ln_parent_entity_id' => $webhook_entity['ln_parent_entity_id'],
                    'ln_parent_link_id' => $webhook_entity['ln_id'],
                );

                //Make sure we have not sent this before (they might be completing this again...)
                if( count($this->Links_model->ln_fetch($filter))==0 ) {

                    //Never triggered before, so let's do it now:
                    $trigger_results = webhook_curl_post($webhook_url['ln_content'], $in_id, $en_id);

                    //Did we face any issues?
                    if(!isset($trigger_results['status']) || intval($trigger_results['status'])!=1){
                        //It seemed that the trigger did not work properly, log an error for the admin:
                        $this->Links_model->ln_create(array_merge($filter , array(
                            'ln_type_entity_id' => 4246, //Platform Error
                            'ln_miner_entity_id' => 1, //Shervin/Developer
                            'ln_child_entity_id' => $en_id,
                            'ln_content' => 'deprecate__actionplan_trigger_webhooks() failed when calling webhook URL ['.$webhook_url['ln_content'].']',
                            'ln_metadata' => $trigger_results,
                        )));
                    }

                    //Log a new link while saving the trigger results:
                    $this->Links_model->ln_create(array_merge($filter , array(
                        'ln_metadata' => $trigger_results,
                    )));
                }
            }
        }


        //Go through parents and detect intersects with student intentions. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
        foreach ($this->Intents_model->in_fetch_recursive_parents($in_id, 2) as $parent_in_id => $grand_parent_ids) {

            if(!$student_in_ids){
                //Fetch all student intention IDs:
                $student_in_ids = array();
                foreach($this->Links_model->ln_fetch(array(
                    'ln_miner_entity_id' => $en_id,
                    'ln_type_entity_id' => 4235, //Action Plan Set Intention
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                    'in_status' => 2, //Published
                ), array('in_parent'), 0) as $student_in){
                    array_push($student_in_ids, $student_in['in_id']);
                }
            }

            //Does this parent and its grandparents have an intersection with the student intentions?
            if(array_intersect($grand_parent_ids, $student_in_ids)){

                //Fetch parent intent & show:
                $parent_ins = $this->Intents_model->in_fetch(array(
                    'in_id' => $parent_in_id,
                ));

                //See if this is complete:
                $completion_rate = $this->Actionplan_model->actionplan_completion_rate($parent_ins[0], $en_id);
                if($completion_rate['completion_percentage']==100){

                    //Yes it is! Trigger Webhook recursively:
                    $this->Actionplan_model->deprecate__actionplan_trigger_webhooks($parent_in_id, $en_id, $student_in_ids);

                }
            }
        }
    }


}