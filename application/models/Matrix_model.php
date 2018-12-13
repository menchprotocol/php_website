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
            $intents = $this->Database_model->in_fetch(array(
                'in_id' => $tr['tr_in_child_id'],
            ), array('in__active_messages')); //Supports up to 2 levels deep for now...

            //Check to see if we have any other errors:
            if (!isset($intents[0])) {
                $message_error = 'Invalid Intent ID [' . $tr['tr_in_child_id'] . ']';
            } else {
                //Check the required notes as we'll use this later:
                $message_in_requirements = $this->Matrix_model->matrix_in_requirements($intents[0], true);
            }
        }

        //Did we catch any errors?
        if ($message_error) {
            //Log error:
            $this->Database_model->tr_create(array(
                'tr_content' => 'compose_messages() error: ' . $message_error,
                'tr_en_type_id' => 4246, //Platform Error
                'tr_metadata' => $tr,
                'tr_en_child_id' => $tr['tr_en_child_id'],
                'tr_in_child_id' => $tr['tr_in_child_id'],
                'tr_en_credit_id' => $tr['tr_en_credit_id'],
            ));

            //Return error:
            return array(
                'status' => 0,
                'message' => $message_error,
            );
        }


        //Let's start adding-up the instant messages:
        $instant_messages = array();

        //Give some context on the current intent:
        if (isset($tr['tr_tr_parent_id']) && $tr['tr_tr_parent_id'] > 0) {

            //Lets see how many child intents there are
            $k_outs = $this->Database_model->tr_fetch(array(
                'tr_id' => $tr['tr_tr_parent_id'],
                'tr_status IN (0,1)' => null, //Active subscriptions only
                'tr_in_parent_id' => $tr['tr_in_child_id'],
                //We are fetching with any tr_status just to see what is available/possible from here
            ), array('w', 'cr', 'cr_c_child'));

            if (count($k_outs) > 0 && !($k_outs[0]['tr_in_child_id'] == $tr['tr_in_child_id'])) {
                //Only confirm the intention if its not the top-level action plan intention:
                array_push($instant_messages, array(
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_in_child_id' => $tr['tr_in_child_id'],
                    'tr_tr_parent_id' => $tr['tr_tr_parent_id'],
                    'tr_content' => 'Let’s ' . $intents[0]['in_outcome'] . '.',
                ));
            }

        }


        //Append main object messages:
        if (!$skip_messages && isset($intents[0]['in__active_messages']) && count($intents[0]['in__active_messages']) > 0) {
            //We have messages for the very first level!
            foreach ($intents[0]['in__active_messages'] as $key => $i) {
                if ($i['tr_status'] == 1) {
                    //Add message to instant stream:
                    array_push($instant_messages, array_merge($tr, $i));
                }
            }
        }


        //Do we have a subscription, if so, we need to add a next step message:
        if ($message_in_requirements) {

            //URL or a written response is required, let them know that they should complete using the Action Plan:
            array_push($instant_messages, array(
                'tr_en_child_id' => $tr['tr_en_child_id'],
                'tr_in_child_id' => $tr['tr_in_child_id'],
                'tr_tr_parent_id' => $tr['tr_tr_parent_id'],
                'tr_content' => $message_in_requirements,
            ));

        } elseif (isset($tr['tr_tr_parent_id']) && $tr['tr_tr_parent_id'] > 0) {

            $tr_content = null;
            $quick_replies = array();

            //Nothing is required to mark as complete, which means we can move forward with this:
            //How many children do we have for this intent?
            if (count($k_outs) <= 1) {

                //We have 0-1 child intents! If zero, let's see what the next step:
                if (count($k_outs) == 0) {
                    //Let's try to find the next item in tree:
                    $k_outs = $this->Database_model->k_next_fetch($tr['tr_tr_parent_id']);
                }

                //Do we have a next intent?
                if (count($k_outs) > 0 && !($k_outs[0]['in_id'] == $intents[0]['in_id'])) {

                    //Give option to move on:
                    $tr_content .= 'The next step to ' . $intents[0]['in_outcome'] . ' is to ' . $k_outs[0]['in_outcome'] . '.';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Ok Continue ▶️',
                        'payload' => 'MARKCOMPLETE_' . $tr['tr_tr_parent_id'] . '_' . $k_outs[0]['tr_id'] . '_' . $k_outs[0]['tr_order'], //Here are are using MARKCOMPLETE_ also for OR branches with a single option... Maybe we need to change this later?! For now it feels ok to do so...
                    ));

                }

            } else {

                //We have multiple children that are pending completion...
                //Is it ALL or ANY?
                if (intval($intents[0]['in_is_any'])) {

                    //Note that ANY nodes cannot require a written response or a URL
                    //User needs to choose one of the following:
                    $tr_content .= 'Choose one of these ' . count($k_outs) . ' options to ' . $intents[0]['in_outcome'] . ':';
                    foreach ($k_outs as $counter => $k) {
                        if ($counter == 10) {
                            break; //Quick reply accepts 11 options max!
                            //We know that the $tr_content length cannot surpass the limit defined by fb_max_message variable!
                        }
                        $tr_content .= "\n\n" . ($counter + 1) . '/ ' . $k['in_outcome'];
                        array_push($quick_replies, array(
                            'content_type' => 'text',
                            'title' => '/' . ($counter + 1),
                            'payload' => 'CHOOSEOR_' . $tr['tr_tr_parent_id'] . '_' . $tr['tr_in_child_id'] . '_' . $k['in_id'] . '_' . $k['tr_order'],
                        ));
                    }

                } else {

                    //User needs to complete all children, and we'd recommend the first item as their next step:
                    $tr_content .= 'There are ' . count($k_outs) . ' steps to ' . $intents[0]['in_outcome'] . ':';
                    foreach ($k_outs as $counter => $k) {

                        if ($counter == 0) {
                            array_push($quick_replies, array(
                                'content_type' => 'text',
                                'title' => 'Start Step 1 ▶️',
                                'payload' => 'MARKCOMPLETE_' . $tr['tr_tr_parent_id'] . '_' . $k['tr_id'] . '_' . $k['tr_order'],
                            ));
                        }

                        //make sure message is within range:
                        if (strlen($tr_content) < ($this->config->item('fb_max_message') - 200)) {
                            //Add message:
                            $tr_content .= "\n\n" . 'Step ' . ($counter + 1) . ': ' . $k['in_outcome'];
                        } else {
                            //We cannot add any more, indicate truncating:
                            $remainder = count($k_outs) - $counter;
                            $tr_content .= "\n\n" . 'And ' . $remainder . ' more step' . echo__s($remainder) . '!';
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

            //Append next-step message:
            array_push($instant_messages, array(
                'tr_en_child_id' => $tr['tr_en_child_id'],
                'tr_in_child_id' => $tr['tr_in_child_id'],
                'tr_tr_parent_id' => $tr['tr_tr_parent_id'],
                'tr_content' => $tr_content,
                'quick_replies' => $quick_replies,
            ));

        }


        //Anything to be sent instantly?
        if (count($instant_messages) < 1) {
            //Nothing to be sent
            return array(
                'status' => 0,
                'message' => 'No messages to be sent',
            );
        }

        //All good, attempt to Dispatch all messages, their engagements have already been logged:
        return $this->Chat_model->dispatch_message($instant_messages);

    }



    function matrix_in_requirements($in, $include_instructions = false)
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

        //Fetch all possible Intent Response Limiters
        $response_options = $this->Database_model->tr_fetch(array(
            'tr_status >=' => 2, //Published
            'tr_en_type_id' => 4331, //Intent Response Limiters
            'tr_en_child_id IN (' . join(',', $this->config->item('en_ids_4331')) . ')' => null, //Intent Response Limiters
            'tr_in_child_id' => $in['in_id'], //For this intent
        ));

        //Construct the message accoringly:
        if(count($response_options) > 0){

            //Fetch latest cache tree:
            $en_all_4331 = $this->config->item('en_all_4331'); //Intent Response Limiters

            //How many do we have?
            if (count($response_options) == 1) {

                //Single option:
                $message = 'Marking as complete requires '.$en_all_4331[$response_options[0]['tr_en_child_id']]['en_name'];

            } else {

                //Multiple options:
                $message = 'Marking as complete requires either: ';

                //Loop through all options:
                foreach ($response_options as $count => $en) {

                    //Prefix:
                    if (($count+1) == count($response_options)) {
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
            return $message . ( $include_instructions ? ', which you can submit using your Action Plan. /open_actionplan' : '' );

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


        //Try finding existing Master PSID:
        $trs_psid = $this->Database_model->tr_fetch(array(
            'tr_status >=' => 2, //Published
            'tr_en_parent_id' => 4451, //Mench Personal Assistant on Messenger
            'tr_en_child_id >' => 0, //Looking for this ID to determine Master Entity ID
            'tr_obj_id' => intval($psid), //Since the PSID is a full integer, it should be cached in tr_obj_id for faster accessing
        ));

        if (count($trs_psid) > 0) {
            //Master found:
            return $trs_psid[0];
        } else {
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
                    $this->Database_model->tr_create(array(
                        'tr_en_type_id' => 4230, //Naked link
                        'tr_en_credit_id' => $en['en_id'], //Master gets credit as they added themselves
                        'tr_en_parent_id' => $tr_en_parent_id,
                        'tr_en_child_id' => $en['en_id'],
                    ));
                }
            }

        }

        //Now create messenger related fields:
        if ($psid > 0) {

            //Store their messenger ID:
            $this->Database_model->tr_create(array(
                'tr_en_type_id' => 4319, //Number Link
                'tr_en_credit_id' => $en['en_id'],
                'tr_en_parent_id' => 4451, //Mench Personal Assistant on Messenger
                'tr_en_child_id' => $en['en_id'],
                'tr_content' => $psid,
            ));

            //Add them to masters group:
            $this->Database_model->tr_create(array(
                'tr_en_type_id' => 4230, //Naked link
                'tr_en_credit_id' => $en['en_id'],
                'tr_en_parent_id' => 4430, //Mench Master
                'tr_en_child_id' => $en['en_id'],
            ));

            //Subscription Level:
            $this->Database_model->tr_create(array(
                'tr_en_type_id' => 4230, //Naked link
                'tr_en_credit_id' => $en['en_id'],
                'tr_en_parent_id' => 4456, //Receive Regular Notifications (This is the starting point that the Master can change later on...)
                'tr_en_child_id' => $en['en_id'],
            ));
        }


        //Assign people group as we know this is who they are:
        $ur1 = $this->Database_model->tr_create(array(
            'tr_en_child_id' => $u['en_id'],
            'tr_en_parent_id' => 1278,
        ));

        //Log new user engagement:
        $this->Database_model->tr_create(array(
            'tr_en_credit_id' => $u['en_id'],
            'tr_en_type_id' => 4265, //User Joined
            'tr_metadata' => $u,
        ));

        //Save picture locally:
        $this->Database_model->tr_create(array(
            'tr_en_credit_id' => $u['en_id'],
            'tr_content' => $fb_profile['profile_pic'], //Image to be saved
            'tr_status' => 0, //Pending upload
            'tr_en_type_id' => 4299, //Save media file to Mench cloud
        ));

        //Return user object:
        return $u;

    }



}