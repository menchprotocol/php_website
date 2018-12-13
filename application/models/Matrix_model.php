<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Matrix_model extends CI_Model
{

    /*
     *
     * This model contains all Database functions that
     * interpret the Matrix from a particular perspective
     * to gain insights from it and to perform pre-defined
     * operations.
     *
     * */

    function __construct()
    {
        parent::__construct();
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
         * */

        //Start input validation:
        $message_error = null;

        if (count($tr) < 1) {

            //Make sure we got an input:
            $message_error = 'Missing input settings';

        } elseif (!isset($tr['tr_in_child_id']) || $tr['tr_in_child_id'] < 1) {

            //Make sure we got the intent to build this message from
            $message_error = 'Missing intent ID';

        } elseif (!isset($tr['tr_en_child_id']) || $tr['tr_en_child_id'] < 1) {

            //Make sure we've got the entity ID to send this message to:
            $message_error = 'Missing Master entity ID';

        }


        //If no errors so far, let's do some more validation:
        if (!$message_error) {

            //Fetch intent and its messages:
            $ins = $this->Database_model->in_fetch(array(
                'in_id' => $tr['tr_in_child_id'],
            ));


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

            //Check to see if we have any other errors:
            if (!isset($ins[0])) {
                $message_error = 'Invalid Intent ID [' . $tr['tr_in_child_id'] . ']';
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
                array_push($messages, array_merge($random_pick, $tr));
            }

            //We have messages for the very first level!
            //Append only if not the top-level item of the Action Plan (Since we've already communicated them)
            foreach ($in_messages['messages_on_start'] as $message_tr) {
                array_push($messages, array_merge($message_tr, $tr));
            }

        }


        //Is $tr an Action Plan intent? It must meet all these conditions to be one:
        if (isset($tr['tr_status']) && isset($tr['tr_in_parent_id']) && isset($tr['tr_tr_parent_id']) && isset($tr['tr_tr_id']) && isset($tr['tr_en_type_id']) && $tr['tr_en_type_id'] == 4235) {


            /*
             * Yes, this is an Action Plan Intent.
             *
             * We can now append more messages to give Masters
             * more insight on what to do to move forward
             *
             * */


            //Now see whether it's the top level intent or not:
            $actionplan_tr_id = ($tr['tr_in_parent_id'] == 0 ? $tr['tr_tr_id'] /* IS top-level */ : $tr['tr_tr_parent_id'] /* NOT top-level */ ) ;


            //Check Intent completion Requirements ONLY IF action plan intent is not completed yet:
            if(in_array($tr['tr_status'], $this->config->item('tr_status_incomplete'))){

                //Check the required notes as we'll use this later:
                $message_in_requirements = $this->Matrix_model->in_completion_requirements($ins[0], true);

                //Do we have a subscription, if so, we need to add a next step message:
                if ($message_in_requirements) {

                    //URL or a written response is required:
                    array_push($messages, array_merge($tr, array(
                        'tr_content' => $message_in_requirements,
                    )));

                    //This is what the Master has to do next, return results:
                    return $this->Chat_model->dispatch_message($messages);

                    //EXIT!

                }

            }



            /*
             *
             * Still here? It either does not have requirements or
             * the requirements have been submitted by the Master
             *
             * */


            //Let's attempt to give direction on what's next...
            $next_step_message = null; //To be populated soon...
            $quick_replies = array(); //In case we needed to give quick reply options

            //Lets see how many incomplete child intents there are in Master's Action Plan:
            $actionplan_child_ins = $this->Database_model->tr_fetch(array(
                'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //Incomplete
                'tr_en_type_id' => 4235, //Action Plan Intent
                'tr_tr_parent_id' => $tr['tr_id'], //Intents belonging to this Action Plan
                'tr_in_parent_id' => $tr['tr_in_child_id'], //Intents that are direct children of $tr
                //We are fetching with any tr_status just to see what is available/possible from here
            ), array('en_child'));

            //Nothing is required to mark as complete, which means we can move forward with this:
            //How many children do we have for this intent?
            if (count($actionplan_child_ins) <= 1) {

                //We have 0-1 child intents! This means we have a single path forward...
                //If zero, let's see what the next step:
                if (count($actionplan_child_ins) == 0) {

                    //This intent does not have any children, attempt to find the next intent in line to determine next step:
                    $actionplan_child_ins = $this->Database_model->k_next_fetch($actionplan_tr_id);

                }

                //Did we find the next intent in line in case we had zero?
                if (count($actionplan_child_ins) > 0) {

                    //Give option to move on:
                    $next_step_message .= 'The next step to ' . $ins[0]['in_outcome'] . ' is to ' . $actionplan_child_ins[0]['in_outcome'] . '.';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Ok Continue ▶️',
                        'payload' => 'MARKCOMPLETE_' . $actionplan_tr_id . '_' . $actionplan_child_ins[0]['tr_id'] . '_' . $actionplan_child_ins[0]['tr_order'], //Here we are using MARKCOMPLETE_ also for OR branches with a single option... Maybe we need to change this later?! For now it feels ok to do so...
                    ));

                }

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

                            //TODO Log Transaction to notify admin to improve this branch

                            break; //Quick reply accepts 11 options max!
                            //We know that the $next_step_message length cannot surpass the limit defined by fb_max_message variable!
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
                                'payload' => 'MARKCOMPLETE_' . $actionplan_tr_id . '_' . $and_child_in['tr_id'] . '_' . $and_child_in['tr_order'],
                            ));
                        }

                        //make sure message is within range:
                        if (strlen($next_step_message) < ($this->config->item('fb_max_message') - 200)) {
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
                    'tr_status IN (0,1)' => null, //Active subscriptions only
                    'tr_in_child_id' => $tr['tr_in_child_id'],
                ), array('w', 'cr', 'cr_c_child'));


                if (count($k_ins) > 0) {
                    //Give option to skip if NOT the top-level intent:
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Skip',
                        'payload' => 'SKIPREQUEST_1_' . $k_ins[0]['tr_id'],
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
        if (count($messages) < 1) {

            //All good, Dispatch all messages:
            return $this->Chat_model->dispatch_message($messages);

        } else {

            //Nothing to be sent:
            return array(
                'status' => 0,
                'message' => 'No messages to be sent',
            );

        }

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


    function authenticate_messenger_user($psid)
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
                'tr_content' => 'authenticate_messenger_user() got called without a valid Facebook $psid variable',
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }


        //Try matching Facebook PSID to existing Masters:
        $masters_found = $this->Database_model->tr_fetch(array(
            'tr_status >=' => 2, //Published
            'tr_en_parent_id' => 4451, //Mench Personal Assistant on Messenger
            'tr_en_child_id >' => 0, //Looking for this ID to determine Master Entity ID
            'tr_obj_id' => intval($psid), //Since the PSID is a full integer, it is cached in tr_obj_id for faster indexing
        ), array('en_child'));


        if (count($masters_found) > 0) {

            //Master found:
            return $masters_found[0];

        } else {

            //Master not found, create new Master:
            return $this->Matrix_model->add_messenger_user($psid);

        }

    }


    function add_messenger_user($psid)
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
                'tr_content' => 'add_messenger_user() got called without a valid Facebook $psid variable',
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }


        //Call facebook messenger API and get user graph profile:
        $graph_fetch = $this->Chat_model->facebook_graph('GET', '/' . $psid, array());


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
            'tr_content' => $psid, //Used later-on to match Messenger user to entity. $psid is cached in tr_obj_id since its an integer
        ));

        //Add default Subscription Level:
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
