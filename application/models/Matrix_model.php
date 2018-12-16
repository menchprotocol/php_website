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


    function in_next_actionplan($actionplan_tr_id)
    {

        /*
         *
         * Attempts to find the next item in the Action Plan
         * And if not, it would mark the Action Plan as complete!
         *
         * */

        //Let's first check if we have an OR Intent that is working On, which means it's children have not been answered!
        $first_pending_or_intent = $this->Database_model->tr_fetch(array(
            'tr_tr_parent_id' => $actionplan_tr_id, //This action Plan
            'in_status >=' => 2, //Published+
            'in_is_any' => 1, //OR Branch
            'tr_status' => 1, //Working On, which means OR branch has not been answered yet
        ), array('in_child'), 1, 0, array('tr_order' => 'ASC'));

        if (count($first_pending_or_intent) > 0) {
            return $first_pending_or_intent;
        }


        //Now check the next AND intent that has not been started:
        $next_new_intent = $this->Database_model->tr_fetch(array(
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
        $next_working_on_intent = $this->Database_model->tr_fetch(array(
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

        $actionplans = $this->Database_model->tr_fetch(array(
            'tr_id' => $actionplan_tr_id,
        ), array('in_child'));

        if(count($actionplans)>0 && in_array($actionplans[0]['tr_status'], $this->config->item('tr_status_incomplete'))){

            //Inform user that they are now complete with all tasks:
            $this->Chat_model->dispatch_message(array(
                array(
                    'tr_en_child_id' => $actionplans[0]['tr_en_parent_id'],
                    'tr_in_child_id' => $actionplans[0]['tr_in_child_id'],
                    'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                    'tr_content' => 'Congratulations for completing your Action Plan 🎉 Over time I will keep sharing new concepts (based on my new training data) that will help you to ' . $actionplans[0]['in_outcome'] . ' 🙌 You can, at any time, stop updates on your Action Plans by saying "unsubscribe".',
                ),
                array(
                    'tr_en_child_id' => $actionplans[0]['tr_en_parent_id'],
                    'tr_in_child_id' => $actionplans[0]['tr_in_child_id'],
                    'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                    'tr_content' => 'How else can I help you ' . $this->config->item('in_primary_name') . '? ' . echo_pa_lets(),
                ),
            ));

            //The entire Action Plan is now complete!
            $this->Database_model->tr_update($actionplan_tr_id, array(
                'tr_status' => 2, //Completed
            ), $actionplans[0]['tr_en_parent_id']);

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
        foreach ($this->Database_model->tr_fetch(array(
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
                $this->Database_model->tr_update($tr['tr_id'], array(
                    'tr_status' => ($set_en_child_id > 0 ? -2 /* Being Updated */ : -1 /* Being Removed */), //Updated or Removed
                ));
            }

        }


        //Make sure $set_en_child_id belongs to parent if set (Could be null which means remove all)
        if (!$already_assigned) {
            //Let's go ahead and add desired entity as parent:
            $this->Database_model->tr_create(array(
                'tr_en_credit_id' => $tr_en_credit_id,
                'tr_en_child_id' => $en_master_id,
                'tr_en_parent_id' => $set_en_child_id,
                'tr_en_type_id' => 4230, //Naked link
                'tr_tr_parent_id' => $updated_tr_id,
            ));
        }

    }


    function compose_messages($tr, $skip_messages = false)
    {

        /*
         *
         * Construct a message from Intent Messages for a given Intent Tree
         * This function considers the logic behind the Intent/Entity Trees in constructing messages
         * The goal is to have all messages sent using this function which
         * means everything is stored on the tree (Rather than in the code base using the dispatch_messages() function)
         * Related to: https://github.com/askmench/mench-web-app/issues/2078
         *
         * Required inputs in $tr are:
         *
         * - tr_in_child_id
         * - tr_en_child_id (OR IF tr_en_child_id=0 AND tr_en_parent_id>0 THEN tr_en_child_id=tr_en_parent_id AND tr_en_parent_id=0)
         * - IF Action Plan intent we need these inputs: tr_status & tr_in_parent_id & tr_tr_parent_id & tr_tr_id & tr_en_type_id==4235
         *
         * */

        //Start input validation:
        $message_error = null;

        if (count($tr) < 1) {

            //Make sure we got an input:
            $message_error = 'Missing input settings';

        } elseif (!isset($tr['tr_in_child_id']) || $tr['tr_in_child_id'] < 1) {

            //Make sure we got the intent to build this message from
            $message_error = 'Missing Intent ID';

        } elseif (!isset($tr['tr_en_child_id']) || $tr['tr_en_child_id'] < 1) {

            //Maybe the parent entity is set? If so flip it:
            if(isset($tr['tr_en_parent_id']) && intval($tr['tr_en_parent_id'])>0){

                //Pass this on to the child:
                $tr['tr_en_child_id'] = $tr['tr_en_parent_id'];

                //Remove from parent:
                $tr['tr_en_parent_id'] = 0;

            } else {

                //Error! Make sure we've got the entity ID to send this message to:
                $message_error = 'Missing Master entity ID';

            }

        }


        //If no errors so far, let's do some more validation:
        if (!$message_error) {

            //Fetch intent and its messages:
            $ins = $this->Database_model->in_fetch(array(
                'in_id' => $tr['tr_in_child_id'],
            ));

            //Check to see if we have any other errors:
            if (!isset($ins[0])) {

                $message_error = 'Invalid Intent ID [' . $tr['tr_in_child_id'] . ']';

            } else {

                //Also fetch relevant intent messages:
                $in_messages = array(

                    //If we have rotating we'd need to pick one and send randomly:
                    'messages_rotating' => $this->Database_model->tr_fetch(array(
                        'tr_status >=' => 2, //Published+
                        'tr_en_type_id' => 4234, //Rotating
                        'tr_in_child_id' => $tr['tr_in_child_id'],
                    ), array(), 0, 0, array('tr_order' => 'ASC')),

                    //These messages all need to be sent first:
                    'messages_on_start' => $this->Database_model->tr_fetch(array(
                        'tr_status >=' => 2, //Published+
                        'tr_en_type_id' => 4231, //On-Start Messages
                        'tr_in_child_id' => $tr['tr_in_child_id'],
                    ), array(), 0, 0, array('tr_order' => 'ASC')),

                    //If we have learn more we'd need to give the option to learn more...
                    'messages_learn_more' => $this->Database_model->tr_fetch(array(
                        'tr_status >=' => 2, //Published+
                        'tr_en_type_id' => 4232, //Learn More Messages
                        'tr_in_child_id' => $tr['tr_in_child_id'],
                    ), array(), 1, 0, array('tr_order' => 'ASC')), //Notice how we only fetch 1 item since we need to know if we have any!

                );

            }

        }

        //Did we catch any errors?
        if ($message_error) {

            //Log error transaction:
            $this->Database_model->tr_create(array(
                'tr_content' => 'compose_messages() error: ' . $message_error,
                'tr_en_type_id' => 4246, //Platform Error
                'tr_metadata' => $tr,
                'tr_en_child_id' => @$tr['tr_en_child_id'],
                'tr_in_child_id' => @$tr['tr_in_child_id'],
                'tr_en_credit_id' => @$tr['tr_en_credit_id'],
            ));

            //Return error:
            return array(
                'status' => 0,
                'message' => $message_error,
            );

            //EXIT!

        }



        //Ok we've had no errors so far...

        //Add-up intent messages IF not skipped:
        $messages = array();
        if (!$skip_messages) {

            //Append intent messages, if any

            //Do we have any rotating messages?
            //Here we are deciding to show rotating first... It's just the way it is unless we feel we need to flip it with the one below
            if (count($in_messages['messages_rotating']) > 0) {
                //yes, pick 1 random one and echo:
                $random_pick = $in_messages['messages_rotating'][rand(0, (count($in_messages['messages_rotating']) - 1))];

                array_push($messages, array(
                    'tr_id' => $random_pick['tr_id'],
                    'tr_content' => $random_pick['tr_content'], //Message itself
                    'tr_en_parent_id' => $random_pick['tr_en_parent_id'], //Reference in the message
                    'tr_en_child_id' => $tr['tr_en_parent_id'], //Send message to this entity
                    'tr_tr_parent_id' => $tr['tr_id'],
                ));
            }

            //We have messages for the very first level!
            //Append only if not the top-level item of the Action Plan (Since we've already communicated them)
            foreach ($in_messages['messages_on_start'] as $message_tr) {

                array_push($messages, array(
                    'tr_id' => $message_tr['tr_id'],
                    'tr_content' => $message_tr['tr_content'], //Message itself
                    'tr_en_parent_id' => $message_tr['tr_en_parent_id'], //Reference in the message
                    'tr_en_child_id' => $tr['tr_en_parent_id'], //Send message to this entity
                    'tr_tr_parent_id' => $tr['tr_id'],
                ));

            }

        }


        //Is $tr an Action Plan intent? It must meet all these conditions to be one:
        if (isset($tr['tr_status']) && isset($tr['tr_in_parent_id']) && isset($tr['tr_tr_parent_id']) && isset($tr['tr_tr_id']) && isset($tr['tr_en_type_id']) && $tr['tr_en_type_id'] == 4235) {


            /*
             * Yes, this is an Action Plan Intent.
             *
             * We can now append more messages to give Masters
             * more understanding on what to do to move forward
             *
             * */


            //Now see whether it's the top level intent or not:
            $actionplan_tr_id = ($tr['tr_in_parent_id'] == 0 ? $tr['tr_tr_id'] /* IS top-level */ : $tr['tr_tr_parent_id'] /* NOT top-level */);


            //Check Intent completion Requirements ONLY IF action plan intent is not completed yet:
            if (in_array($tr['tr_status'], $this->config->item('tr_status_incomplete'))) {

                //Check the required notes as we'll use this later:
                $message_in_requirements = $this->Matrix_model->in_completion_requirements($ins[0], true);

                //Do we have a Action Plan, if so, we need to add a next step message:
                if ($message_in_requirements) {

                    //Let the user know what they need to do:
                    array_push($messages, array(
                        'tr_id' => $message_tr['tr_id'],
                        'tr_content' => $message_in_requirements, //Message itself
                        'tr_en_parent_id' => $message_tr['tr_en_parent_id'], //Reference in the message
                        'tr_en_child_id' => $tr['tr_en_parent_id'], //Send message to this entity
                        'tr_tr_parent_id' => $tr['tr_id'],
                    ));


                    array_push($messages, array_merge($tr, array(
                        'tr_content' => $message_in_requirements,
                    )));

                    //Completing this requirement is the next step, return results:
                    return $this->Chat_model->dispatch_message($messages);

                    //EXIT!

                }

            }


            /*
             *
             * Still here? It either does not have requirements or
             * the requirements have been completed by the Master
             *
             * Let's attempt to give direction on what's next...
             *
             * */


            //To be populated soon:
            $next_step_message = null;
            $quick_replies = array();


            //Lets see how many incomplete child intents there are in Master's Action Plan:
            $actionplan_child_ins = $this->Database_model->tr_fetch(array(
                'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //Incomplete
                'tr_en_type_id' => 4235, //Action Plan Intent
                'tr_tr_parent_id' => $tr['tr_id'], //Intents belonging to this Action Plan
                'tr_in_parent_id' => $tr['tr_in_child_id'], //Intents that are direct children of $tr
                //We are fetching with any tr_status just to see what is available/possible from here
            ), array('en_child'));


            //How many children do we have for this intent?
            if (count($actionplan_child_ins) == 0) {

                //No children! So there is a single path forward, the next intent in line...
                //let's see what the next intent:
                $next_ins = $this->Matrix_model->in_next_actionplan($actionplan_tr_id);

                //Did we find the next intent in line in case we had zero?
                if (count($next_ins) > 0) {

                    //Give option to move on:
                    $next_step_message .= 'The next step to ' . $ins[0]['in_outcome'] . ' is to ' . $next_ins[0]['in_outcome'] . '.';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Ok Continue ▶️',
                        'payload' => 'MARKCOMPLETE_' . $next_ins[0]['tr_id'], //Here we are using MARKCOMPLETE_ also for OR branches with a single option... Maybe we need to change this later?! For now it feels ok to do so...
                    ));

                }

            } elseif (count($actionplan_child_ins) == 1) {

                //We 1 child intents, which means again that we have a single path forward...
                $next_step_message .= 'The next step to ' . $ins[0]['in_outcome'] . ' is to ' . $actionplan_child_ins[0]['in_outcome'] . '.';
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Ok Continue ▶️',
                    'payload' => 'MARKCOMPLETE_' . $actionplan_child_ins[0]['tr_id'], //Here we are using MARKCOMPLETE_ also for OR branches with a single option... Maybe we need to change this later?! For now it feels ok to do so...
                ));

            } else {


                //Re-affirm the outcome of the input Intent before listing children:
                array_push($messages, array(
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_in_child_id' => $tr['tr_in_child_id'],
                    'tr_tr_parent_id' => $tr['tr_tr_parent_id'],
                    'tr_content' => 'Let’s ' . $ins[0]['in_outcome'] . '.',
                ));

                //We have multiple immediate children that need to be marked as complete...
                //Let's see if the intent is ALL or ANY to know how to present these children:
                if (intval($ins[0]['in_is_any'])) {

                    //Note that ANY nodes cannot require a written response or a URL
                    //User needs to choose one of the following:
                    $next_step_message .= 'Choose one of these ' . count($actionplan_child_ins) . ' options to ' . $ins[0]['in_outcome'] . ':';
                    foreach ($actionplan_child_ins as $counter => $or_child_in) {
                        if ($counter == 10) {

                            //Log error transaction:
                            $this->Database_model->tr_create(array(
                                'tr_en_credit_id' => 1, //Shervin Enayati - 13 Dec 2018
                                'tr_content' => 'compose_messages() encountered intent with too many children to be listed as OR Intent options! Trim and iterate that intent tree.',
                                'tr_en_type_id' => 4246, //Platform Error
                                'tr_tr_parent_id' => $actionplan_tr_id, //The action plan
                                'tr_in_parent_id' => $tr['tr_in_child_id'],
                                'tr_in_child_id' => $or_child_in['in_id'],
                            ));

                            //Quick reply accepts 11 options max:
                            break;

                        }
                        $next_step_message .= "\n\n" . ($counter + 1) . '/ ' . $or_child_in['in_outcome'];
                        array_push($quick_replies, array(
                            'content_type' => 'text',
                            'title' => '/' . ($counter + 1),
                            'payload' => 'CHOOSEOR_' . $actionplan_tr_id . '_' . $tr['tr_in_child_id'] . '_' . $or_child_in['in_id'] . '_' . $or_child_in['tr_order'],
                        ));
                    }

                } else {

                    //User needs to complete all children, and we'd recommend the first item as their next step:
                    $next_step_message .= 'There are ' . count($actionplan_child_ins) . ' steps to ' . $ins[0]['in_outcome'] . ':';

                    foreach ($actionplan_child_ins as $counter => $and_child_in) {

                        if ($counter == 0) {

                            array_push($quick_replies, array(
                                'content_type' => 'text',
                                'title' => 'Start Step 1 ▶️',
                                'payload' => 'MARKCOMPLETE_' . $and_child_in['tr_id'],
                            ));

                        }

                        //We know that the $next_step_message length cannot surpass the limit defined by fb_max_message variable!
                        //make sure message is within range:
                        if (strlen($next_step_message) < ($this->config->item('fb_max_message') - 200 /* Cushion for appendix messages */)) {

                            //Add message:
                            $next_step_message .= "\n\n" . 'Step ' . ($counter + 1) . ': ' . $and_child_in['in_outcome'];

                        } else {

                            //We cannot add any more, indicate truncating:
                            $remainder = count($actionplan_child_ins) - $counter;
                            $next_step_message .= "\n\n" . 'And ' . $remainder . ' more step' . echo__s($remainder) . '!';
                            break;

                        }
                    }

                }


                //As long as $tr['tr_in_child_id'] is NOT equal to tr_in_child_id, then we will have a k_out relation so we can give the option to skip:
                $k_ins = $this->Database_model->tr_fetch(array(
                    'tr_id' => $tr['tr_tr_parent_id'],
                    'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
                    'tr_in_child_id' => $tr['tr_in_child_id'],
                ), array('w', 'cr', 'cr_c_child'));


                if (count($k_ins) > 0) {
                    //Give option to skip if NOT the top-level intent:
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Skip',
                        'payload' => 'AP-SKIP-INITIATE_1_' . $k_ins[0]['tr_id'],
                    ));
                }
            }

            //Did we have any learn more messages? In that case give the option to learn more:
            if (count($in_messages['messages_learn_more']) > 0) {

                //Yes! Give as last option:
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Learn More',
                    'payload' => 'LEARNMORE_' . $actionplan_tr_id . '_' . $tr['tr_id'] . '_' . $ins[0]['in_id'], //TODO Implement LEARNMORE_
                ));

            }

            //Append next-step message:
            array_push($messages, array(
                'tr_en_child_id' => $tr['tr_en_child_id'],
                'tr_in_child_id' => $tr['tr_in_child_id'],
                'tr_tr_parent_id' => $tr['tr_tr_parent_id'],
                'tr_content' => $next_step_message,
                'quick_replies' => $quick_replies,
            ));

        }


        //Did we compile any messages to be dispatched?
        if (count($messages) > 0) {

            //All good, Dispatch all messages:
            return $messages;
            return $this->Chat_model->dispatch_message($messages);

        } else {

            //Nothing to be sent:
            return array(
                'status' => 0,
                'message' => 'No messages to be sent',
            );

        }

    }




    function fn___create_en_from_url($input_url, $tr_en_credit_id = 0)
    {

        /*
         *
         * The function that would add/validate new URLs
         * which would also check for duplicates, etc...
         * And create a new entity under config variable en_url_bucket
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
        $dup_urls = $this->Old_model->tr_fetch(array(
            'tr_en_type_id IN ('.join(',', $this->config->item('en_ids_4537')).')' => null, //Entity URL Links
            'tr_content LIKE \'' . $input_url . '\'' => null,
        ), array('en_child'));

        if (count($dup_urls) > 0) {

            //Yes, we already have this URL added to an entity, return the entity:
            //Not removed?
            if ($dup_urls[0]['tr_status'] < 0) {

                $status_index = $this->config->item('object_statuses');

                //This URL was removed, which is an issue:
                return array(
                    'status' => 0,
                    'message' => 'This URL has had been added before but then removed with status ['.$status_index['en_status'][$dup_urls[0]['en_status']]['s_name'].']',
                );

            } else {

                //Return the object as this is expected:
                return array(
                    'status' => 1,
                    'is_existing' => 1,
                    'message' => 'Entity already existed',
                    'en_by_url' => $dup_urls[0],
                );

            }
        }


        //This is a new URL that has never been added before...
        //Call URL to validate it further:
        $curl = curl_html($input_url, true);

        if (!$curl) {
            return array(
                'status' => 0,
                'message' => 'Invalid URL',
            );
        }

        //We need to create a new entity and add this URL to it...
        //Was curl_html() able to fetch the URL's <title> tag?
        if (strlen($curl['page_title']) > 0) {

            //Make sure this is not a duplicate name:
            $dup_name_us = $this->Database_model->en_fetch(array(
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

            //curl_html() did not find a <title> tag:
            //Use URL Type as its name:
            $en_all_4537 = $this->config->item('en_all_4537');
            $en_name = $en_all_4537[$curl['tr_en_type_id']]['en_name'] . ' ' . substr(md5($input_url), 0, 8); //Append a unique identifier

        }


        //Create a new entity:
        $en = $this->Database_model->en_create(array(
            'en_name' => $en_name,
        ), true, $tr_en_credit_id);

        //Place this new entity in the default URL bucket:
        $entity_tr = $this->Database_model->tr_create(array(
            'tr_en_credit_id' => $tr_en_credit_id,
            'tr_en_type_id' => $curl['tr_en_type_id'],
            'tr_en_parent_id' => $this->config->item('en_url_bucket'),
            'tr_en_child_id' => $en['en_id'],
            'tr_content' => $input_url,
        ));

        //Return entity object:
        return array(
            'status' => 1,
            'is_existing' => 0,
            'message' => 'New Entity created from URL',
            'en_by_url' => array_merge($en, $entity_tr),
        );

    }



    function in_completion_requirements($in, $include_instructions = false)
    {

        /*
         *
         * Sometimes to mark an intent as complete the Masters might
         * need to meet certain requirements in what they submit to do so.
         * This function fetches those requirements from the Matrix and
         * Provides an easy to understand message to communicate
         * these requirements to Master.
         *
         * Will return NULL if it detects no requirements...
         *
         * */

        //Fetch all possible Intent Response Limiters to see what is required:
        $response_options = $this->Database_model->tr_fetch(array(
            'tr_status >=' => 2, //Published
            'tr_en_type_id' => 4331, //Intent Response Limiters
            'tr_en_child_id IN (' . join(',', $this->config->item('en_ids_4331')) . ')' => null, //All Intent Response Limiters
            'tr_in_child_id' => $in['in_id'], //For this intent
        ));

        //Construct the message accoringly:
        if (count($response_options) > 0) {

            //Fetch latest cache tree:
            $en_all_4331 = $this->config->item('en_all_4331'); //Intent Response Limiters

            //How many do we have?
            if (count($response_options) == 1) {

                //Single option:
                $message = 'Marking as complete requires ' . $en_all_4331[$response_options[0]['tr_en_child_id']]['en_name'];

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
                    $message .= $en_all_4331[$en['tr_en_child_id']]['en_name'];

                }

            }

            //Return Master-friendly message for completion requirements:
            return $message . ($include_instructions ? ', which you can submit using your Action Plan. /open_actionplan' : '');

        } else {

            //Did not find any requirements:
            return null;

        }

    }


    function fn___authenticate_messenger_user($psid)
    {

        /*
         *
         * Detects the Master entity ID based on the
         * PSID provided by the Facebook Webhook Call.
         * This function returns the Master's entity object $en
         *
         */


        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->Database_model->tr_create(array(
                'tr_content' => 'fn___authenticate_messenger_user() got called without a valid Facebook $psid variable',
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }


        //Try matching Facebook PSID to existing Masters:
        $masters_found = $this->Database_model->tr_fetch(array(
            'tr_status >=' => 2, //Published
            'tr_en_parent_id' => 4451, //Mench Personal Assistant on Messenger
            'tr_en_child_id >' => 0, //Looking for this ID to determine Master Entity ID
            'tr_content_int' => intval($psid), //Since the PSID is a full integer, it is cached in tr_content_int for faster indexing
        ), array('en_child'));


        if (count($masters_found) > 0) {

            //Master found:
            return $masters_found[0];

        } else {

            //Master not found, create new Master:
            return $this->Matrix_model->fn___add_messenger_user($psid);

        }

    }


    function in_actionplan_complete_up($cr, $w, $force_tr_status = null)
    {

        //Check if parent of this item is not started, because if not, we need to mark that as Working On:
        $parent_ks = $this->Database_model->tr_fetch(array(
            'tr_tr_parent_id' => $w['tr_id'],
            'tr_status' => 0, //skip intents that are not stared or working on...
            'tr_in_child_id' => $cr['tr_in_parent_id'],
        ), array('cr'));
        if (count($parent_ks) == 1) {
            //Update status (It might not work if it was working on AND new tr_status=1)
            $this->Database_model->tr_status_update($parent_ks[0]['tr_id'], 1);
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
            $none_chosen_paths = $this->Database_model->tr_fetch(array(
                'tr_tr_parent_id' => $w['tr_id'],
                'tr_in_parent_id' => $cr['tr_in_parent_id'], //Fetch children of parent intent which are the siblings of current intent
                'tr_in_child_id !=' => $cr['tr_in_child_id'], //NOT The answer (we need its siblings)
                'in_status >=' => 2,
                'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
            ), array('w', 'cr', 'cr_c_child'));

            //This is the none chosen answers, if any:
            foreach ($none_chosen_paths as $k) {
                //Skip this intent:
                $total_skipped += count($this->Database_model->k_skip_recursive_down($k['tr_id']));
            }
        }


        if (!$force_tr_status) {
            //Regardless of Branch type, we need all children to be complete if we are to mark this as complete...
            //If not, we will mark is as working on...
            //So lets fetch the down tree and see Whatssup:
            $dwn_tree = $this->Database_model->k_recursive_fetch($w['tr_id'], $cr['tr_in_child_id'], true);

            //Does it have OUTs?
            if (count($dwn_tree['k_flat']) > 0) {
                //We do have down, let's check their status:
                $dwn_incomplete_ks = $this->Database_model->tr_fetch(array(
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
        $this->Database_model->tr_status_update($cr['tr_id'], $new_tr_status);


        //We are done with this branch if the status is any of the following:
        if (!in_array($new_tr_status, $this->config->item('tr_status_incomplete'))) {

            //Since down tree is now complete, see if up tree needs completion as well:
            //Fetch all parents:
            $up_tree = $this->Database_model->k_recursive_fetch($w['tr_id'], $cr['tr_in_child_id'], false);

            //Now loop through each level and see whatssup:
            foreach ($up_tree['k_flat'] as $parent_tr_id) {

                //Fetch details to see whatssup:
                $parent_ks = $this->Database_model->tr_fetch(array(
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
                        $complete_child_cs = $this->Database_model->tr_fetch(array(
                            'tr_tr_parent_id' => $w['tr_id'],
                            'tr_status NOT IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //complete
                            'tr_in_parent_id' => $parent_ks[0]['tr_in_child_id'],
                        ), array('cr'));
                        if (count($complete_child_cs) == 0) {
                            $is_complete = false;
                        }
                    } else {
                        //We need all immediate children to be complete (i.e. No incomplete)
                        $incomplete_child_cs = $this->Database_model->tr_fetch(array(
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
                        $this->Database_model->tr_status_update($parent_ks[0]['tr_id'], (!is_null($force_tr_status) ? $force_tr_status : 2));

                    } elseif ($parent_ks[0]['tr_status'] == 0) {

                        //Status is not started, let's set to started:
                        $this->Database_model->tr_status_update($parent_ks[0]['tr_id'], 1); //Working On

                    }
                }
            }
        }
    }



    function fn___add_messenger_user($psid)
    {

        /*
         *
         * This function will attempt to create a new Master Entity
         * Using the PSID provided by Facebook Graph API
         *
         * */

        if ($psid < 1) {
            //Ooops, this should never happen:
            $this->Database_model->tr_create(array(
                'tr_content' => 'fn___add_messenger_user() got called without a valid Facebook $psid variable',
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
            $en = $this->Database_model->en_create(array(
                'en_name' => 'Candidate ' . rand(100000000, 999999999),
            ), true);

            //Inform the master:
            $this->Chat_model->dispatch_message(array(
                array(
                    'tr_en_child_id' => $en['en_id'],
                    'tr_content' => 'Hi stranger! Let\'s get started by completing your profile information by opening the My Account tab in the menu below. /open_myaccount',
                ),
            ));

        } else {

            //We did find the profile, move ahead:
            $fb_profile = $graph_fetch['tr_metadata']['result'];

            //Create Master with their Facebook Graph name:
            $en = $this->Database_model->en_create(array(
                'en_name' => $fb_profile['first_name'] . ' ' . $fb_profile['last_name'],
            ), true);

            //Split locale variable into language and country like "EN_GB" for English in England
            $locale = explode('_', $fb_profile['locale'], 2);

            //Try to match Facebook profile data to internal entities and create links for the ones we find:
            foreach (array(
                         $this->Database_model->en_search_match(3289, $fb_profile['timezone']), //Timezone
                         $this->Database_model->en_search_match(3290, strtolower(substr($fb_profile['gender'], 0, 1))), //Gender either m/f
                         $this->Database_model->en_search_match(3287, strtolower($locale[0])), //Language
                         $this->Database_model->en_search_match(3089, strtolower($locale[1])), //Country
                     ) as $tr_en_parent_id) {
                //Did we find a relation? Create the transaction:
                if ($tr_en_parent_id > 0) {

                    //Create new transaction:
                    $this->Database_model->tr_create(array(
                        'tr_en_type_id' => 4230, //Naked link
                        'tr_en_credit_id' => $en['en_id'], //Master gets credit as they added themselves
                        'tr_en_parent_id' => $tr_en_parent_id,
                        'tr_en_child_id' => $en['en_id'],
                    ));

                }
            }

            //Create transaction to save profile picture:
            $this->Database_model->tr_create(array(
                'tr_status' => 0, //Pending processing via cron job...
                'tr_en_type_id' => 4299, //Save media file to Mench cloud
                'tr_en_credit_id' => $en['en_id'],
                'tr_content' => $fb_profile['profile_pic'], //Image to be saved
            ));

        }

        //Note that new entity engagement is already logged via en_create()
        //Now create more relevant transactions:

        //Log new Master transaction:
        $this->Database_model->tr_create(array(
            'tr_en_type_id' => 4265, //User Joined
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_child_id' => $en['en_id'],
            'tr_metadata' => $en,
        ));

        //Store Master's Messenger PSID:
        $this->Database_model->tr_create(array(
            'tr_en_type_id' => 4319, //Number Link
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_parent_id' => 4451, //Mench Personal Assistant on Messenger
            'tr_en_child_id' => $en['en_id'],
            'tr_content' => $psid, //Used later-on to match Messenger user to entity. $psid is cached in tr_content_int since its an integer
        ));

        //Add default Action Plan Level:
        $this->Database_model->tr_create(array(
            'tr_en_type_id' => 4230, //Naked link
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_parent_id' => 4456, //Receive Regular Notifications (Master can change later on...)
            'tr_en_child_id' => $en['en_id'],
        ));

        //Add them to Masters group:
        $this->Database_model->tr_create(array(
            'tr_en_type_id' => 4230, //Naked link
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_parent_id' => 4430, //Mench Master
            'tr_en_child_id' => $en['en_id'],
        ));

        //Add them to People group:
        $this->Database_model->tr_create(array(
            'tr_en_type_id' => 4230, //Naked link
            'tr_en_credit_id' => $en['en_id'],
            'tr_en_parent_id' => 1278, //People
            'tr_en_child_id' => $en['en_id'],
        ));

        //Return entity object:
        return $en;

    }

}
