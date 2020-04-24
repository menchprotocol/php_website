<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class COMMUNICATION_model extends CI_Model
{

    /*
     *
     * Functions to send & receive messages
     * through Email & Messenger
     *
     * */

    function __construct()
    {
        parent::__construct();
    }

    function comm_digest_payload($en, $quick_reply_payload)
    {

        /*
         *
         * With the assumption that chat platforms like Messenger,
         * Slack and Telegram all offer a mechanism to manage a reference
         * field other than the actual message itself (Facebook calls
         * this the Reference key or Metadata), this function will
         * process that metadata string from incoming messages sent to Mench
         * by its Users and take appropriate action.
         *
         * Inputs:
         *
         * - $en:                   The User who made the request
         *
         * - $quick_reply_payload:  The payload string attached to the chat message
         *
         *
         * */


        if (strlen($quick_reply_payload) < 1) {

            //Should never happen!
            return array(
                'status' => 0,
                'message' => 'Missing quick reply payload',
            );

        } elseif (substr_count($quick_reply_payload, 'UNSUBSCRIBE_') == 1) {

            $action_unsubscribe = one_two_explode('UNSUBSCRIBE_', '', $quick_reply_payload);

            if ($action_unsubscribe == 'CANCEL') {

                //User seems to have changed their mind, confirm with them:
                $this->COMMUNICATION_model->comm_message_send(
                    'Awesome, I am excited to continue our work together.',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            } elseif ($action_unsubscribe == 'ALL') {

                //User wants to completely unsubscribe from Mench:
                $deleted_ins = 0;
                foreach ($this->LEDGER_model->ln_fetch(array(
                    'ln_creator_source_id' => $en['en_id'],
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                )) as $ln) {
                    $deleted_ins++;
                    $this->LEDGER_model->ln_update($ln['ln_id'], array(
                        'ln_status_source_id' => 6173, //Link Deleted
                    ), $en['en_id'], 6155 /* User Idea Cancelled */);
                }

                //TODO DELETE THEIR ACCOUNT HERE

                //Let them know about these changes:
                $this->COMMUNICATION_model->comm_message_send(
                    'Confirmed, I removed ' . $deleted_ins . ' idea' . echo__s($deleted_ins) . ' from your list. This is the final message you will receive from me unless you message me again. I hope you take good care of yourself 😘',
                    $en,
                    true
                );

            } elseif (is_numeric($action_unsubscribe)) {

                //User wants to Delete a specific DISCOVER LIST, validate it:
                $player_discoveries = $this->LEDGER_model->ln_fetch(array(
                    'ln_creator_source_id' => $en['en_id'],
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_previous_idea_id' => $action_unsubscribe,
                ), array('in_previous'), 0, 0, array('ln_order' => 'ASC'));

                //All good?
                if (count($player_discoveries) < 1) {
                    return array(
                        'status' => 0,
                        'message' => 'UNSUBSCRIBE_ Failed to delete IDEA from DISCOVER LIST',
                    );
                }

                //Update status for this single DISCOVER LIST:
                $this->LEDGER_model->ln_update($player_discoveries[0]['ln_id'], array(
                    'ln_status_source_id' => 6173, //Link Deleted
                ), $en['en_id'], 6155 /* User Idea Cancelled */);

                //Re-sort remaining DISCOVER LIST ideas:
                foreach($this->LEDGER_model->ln_fetch(array(
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                    'ln_creator_source_id' => $en['en_id'], //Belongs to this User
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                ), array(), 0, 0, array('ln_order' => 'ASC')) as $count => $ln){
                    $this->LEDGER_model->ln_update($ln['ln_id'], array(
                        'ln_order' => ($count+1),
                    ), $en['en_id'], 10681 /* Ideas Ordered Automatically */);
                }

                //Show success message to user:
                $this->COMMUNICATION_model->comm_message_send(
                    'I have successfully removed [' . $player_discoveries[0]['in_title'] . '] from your list.',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            }

        } elseif ($quick_reply_payload == 'SUBSCRIBE-REJECT') {

            //They rejected the offer... Acknowledge and give response:
            $this->COMMUNICATION_model->comm_message_send(
                'Ok, so how can I help you move forward?',
                $en,
                true
            );

            //DISCOVER RECOMMENDATIONS
            $this->COMMUNICATION_model->comm_message_send(
                echo_platform_message(12697),
                $en,
                true
            );

        } elseif (is_numeric($quick_reply_payload)) {

            //Validate Idea:
            $in_id = intval($quick_reply_payload);
            $ins = $this->IDEA_model->in_fetch(array(
                'in_id' => $in_id,
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            ));
            if (count($ins) < 1) {

                //Confirm if they are interested to subscribe to this idea:
                $this->COMMUNICATION_model->comm_message_send(
                    '❌ I cannot add this idea to your DISCOVER LIST because its not yet published.',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        ),
                    )
                );

                return array(
                    'status' => 0,
                    'message' => 'Failed to validate starting-point idea',
                );
            }

            //Confirm if they are interested to subscribe to this idea:
            $this->COMMUNICATION_model->comm_message_send(
                'Hi 👋 are you interested to ' . $ins[0]['in_title'] . '?',
                $en,
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Yes', //Yes, Learn More
                        'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'], //'SUBSCRIBE-INITIATE_' . $ins[0]['in_id']
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

        } elseif ($quick_reply_payload=='NOTINTERESTED') {

            //Affirm and educate:
            $this->COMMUNICATION_model->comm_message_send(
                echo_platform_message(12697),
                $en,
                true
            //Do not give next option and listen for their idea command...
            );

        } elseif (substr_count($quick_reply_payload, 'SUBSCRIBE-INITIATE_') == 1) {

            //User has confirmed their desire to subscribe to an IDEA:
            $in_id = intval(one_two_explode('SUBSCRIBE-INITIATE_', '', $quick_reply_payload));

            //Initiating an IDEA DISCOVER LIST:
            $ins = $this->IDEA_model->in_fetch(array(
                'in_id' => $in_id,
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            ));

            if (count($ins) != 1) {
                return array(
                    'status' => 0,
                    'message' => 'SUBSCRIBE-INITIATE_ Failed to locate published idea',
                );
            }

            //Make sure idea has not previously been added to user DISCOVER LIST:
            if (count($this->LEDGER_model->ln_fetch(array(
                    'ln_creator_source_id' => $en['en_id'],
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ))) > 0) {

                //Let User know that they have previously subscribed to this idea:
                $this->COMMUNICATION_model->comm_message_send(
                    'The idea [' . $ins[0]['in_title'] . '] has previously been added to your DISCOVER LIST.',
                    $en,
                    true
                );

                //Give them option to go next:
                $this->COMMUNICATION_model->comm_message_send(
                    'Say "Next" to continue...',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            } else {

                //Do final confirmation by giving User more context on this idea before adding to their DISCOVER LIST...

                //See if we have an overview:
                $overview_message = 'Should I add this idea to your DISCOVER LIST?';

                //Send message for final confirmation with the overview of how long/difficult it would be to accomplish this idea:
                $this->COMMUNICATION_model->comm_message_send(
                    $overview_message,
                    $en,
                    true,
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
                    )
                );

                //Log as DISCOVER LIST Considered:
                $this->LEDGER_model->ln_create(array(
                    'ln_creator_source_id' => $en['en_id'],
                    'ln_type_source_id' => 6149, //DISCOVER LIST Idea Considered
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                    'ln_content' => $overview_message, //A copy of their message
                ));

            }

        } elseif (substr_count($quick_reply_payload, 'GONEXT_') == 1) {

            $next_in_id = 0;
            $in_id = intval(one_two_explode('GONEXT_', '', $quick_reply_payload));

            if($in_id > 0){
                $ins = $this->IDEA_model->in_fetch(array(
                    'in_id' => $in_id,
                ));
                $next_in_id = $this->DISCOVER_model->discover_next_find($en['en_id'], $ins[0]);
            }

            if($next_in_id > 0){
                //Yes, communicate it:
                $this->DISCOVER_model->discover_echo($next_in_id, $en, true);
            } else {
                //Fetch and communicate next idea:
                $this->DISCOVER_model->discover_next_go($en['en_id'], true, true);
            }

        } elseif (substr_count($quick_reply_payload, 'ADD_RECOMMENDED_') == 1) {

            $in_ids = explode('_', one_two_explode('ADD_RECOMMENDED_', '', $quick_reply_payload));
            $recommender_in_id = $in_ids[0];
            $recommended_in_id = $in_ids[1];

            //Add this item to the tio of the DISCOVER LIST:
            $this->DISCOVER_model->discover_start($en['en_id'], $recommended_in_id, $recommender_in_id);

        } elseif (substr_count($quick_reply_payload, 'SUBSCRIBE-CONFIRM_') == 1) {

            //User has requested to add this idea to their DISCOVER LIST:
            $in_id = intval(one_two_explode('SUBSCRIBE-CONFIRM_', '', $quick_reply_payload));

            //Add to DISCOVER LIST:
            $this->DISCOVER_model->discover_start($en['en_id'], $in_id);

        } elseif (substr_count($quick_reply_payload, 'ANSWERQUESTION_') == 1) {

            /*
             *
             * When the user answers a quick reply question.
             *
             * */

            //Extract variables:
            $quickreply_parts = explode('_', one_two_explode('ANSWERQUESTION_', '', $quick_reply_payload));

            //Save the answer:
            return $this->DISCOVER_model->discover_answer($en['en_id'], $quickreply_parts[1], array($quickreply_parts[2]));

        } else {

            //Unknown quick reply!
            return array(
                'status' => 0,
                'message' => 'Unknown quick reply command!',
            );

        }

        //If here it was all good, return success:
        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }

    function comm_digest_text($en, $fb_received_message)
    {

        /*
         *
         * Will process the chat message only in the absence of a chat metadata
         * otherwise the comm_digest_payload() will process the message since we
         * know that the medata would have more precise instructions on what
         * needs to be done for the User response.
         *
         * This involves string analysis and matching terms to a ideas, sources
         * and known commands that will help us understand the User and
         * hopefully provide them with the information they need, right now.
         *
         * We'd eventually need to migrate the search engine to an NLP platform
         * Like dialogflow.com (By Google) or wit.ai (By Facebook) to improve
         * our ability to detect correlations specifically for ideas.
         *
         * */

        if (!$fb_received_message) {
            return false;
        }


        /*
         *
         * Ok, now attempt to understand User's message idea.
         * We would do a very basic work pattern match to see what
         * we can understand from their message, and we would expand
         * upon this section as we improve our NLP technology.
         *
         *
         * */

        $fb_received_message = trim(strtolower($fb_received_message));

        if (in_array($fb_received_message, array('next', 'continue', 'go'))) {

            //Give them the next step of their DISCOVER LIST:
            $next_in_id = $this->DISCOVER_model->discover_next_go($en['en_id'], true, true);

            //Log command trigger:
            $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => $en['en_id'],
                'ln_type_source_id' => 6559, //User Commanded Next
                'ln_previous_idea_id' => $next_in_id,
            ));

        } elseif (includes_any($fb_received_message, array('unsubscribe', 'stop', 'quit', 'resign', 'exit', 'cancel', 'abort'))) {

            //List their DISCOVER LIST ideas and let user choose which one to unsubscribe:
            $player_discoveries = $this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en['en_id'],
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
            ), array('in_previous'), 10 /* Max quick replies allowed */, 0, array('ln_order' => 'ASC'));


            //Do they have anything in their DISCOVER LIST?
            if (count($player_discoveries) > 0) {

                //Give them options to delete specific DISCOVER LISTs:
                $quick_replies = array();
                $message = 'Choose one of the following options:';
                $increment = 1;

                foreach ($player_discoveries as $counter => $in) {
                    //Construct unsubscribe confirmation body:
                    $message .= "\n\n" . ($counter + $increment) . '. Stop ' . $in['in_title'];
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($counter + $increment),
                        'payload' => 'UNSUBSCRIBE_' . $in['in_id'],
                    ));
                }

                if (count($player_discoveries) >= 2) {
                    //Give option to skip all and unsubscribe:
                    $increment++;
                    $message .= "\n\n" . ($counter + $increment) . '. Delete all ideas and unsubscribe';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($counter + $increment),
                        'payload' => 'UNSUBSCRIBE_ALL',
                    ));
                }

                //Alwyas give cancel option:
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Cancel',
                    'payload' => 'UNSUBSCRIBE_CANCEL',
                ));

            } else {

                $message = 'Just to confirm, do you want to unsubscribe and stop all future communications with me and unsubscribe?';
                $quick_replies = array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Yes, Unsubscribe',
                        'payload' => 'UNSUBSCRIBE_ALL',
                    ),
                    array(
                        'content_type' => 'text',
                        'title' => 'No, Stay Friends',
                        'payload' => 'UNSUBSCRIBE_CANCEL',
                    ),
                );

            }

            //Send out message and let them confirm:
            $this->COMMUNICATION_model->comm_message_send(
                $message,
                $en,
                true,
                $quick_replies
            );

            //Log command trigger:
            $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => $en['en_id'],
                'ln_type_source_id' => 6578, //User Text Commanded Stop
                'ln_content' => $message,
                'ln_metadata' => $quick_replies,
            ));

        } elseif (substr($fb_received_message, 0, 11) == 'Discover ') {

            $master_command = ltrim($fb_received_message, 'Discover ');
            $new_idea_count = 0;
            $quick_replies = array();


            if(intval(config_var(12678))){

                $search_index = load_algolia('alg_index');
                $res = $search_index->search($master_command, [
                    'hitsPerPage' => 6, //Max results
                    'filters' => 'alg_obj_type_id=4535 AND _tags:is_featured',
                ]);
                $search_results = $res['hits'];


                //Show options for the User to add to their DISCOVER LIST:

                foreach ($search_results as $alg) {

                    //Fetch metadata:
                    $ins = $this->IDEA_model->in_fetch(array(
                        'in_id' => $alg['alg_obj_id'],
                        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Idea Status Public
                    ));
                    if(count($ins) < 1){
                        continue;
                    }

                    //Make sure not previously in DISCOVER LIST:
                    if(count($this->LEDGER_model->ln_fetch(array(
                            'ln_creator_source_id' => $en['en_id'],
                            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //DISCOVER LIST Idea Set
                            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                            'ln_previous_idea_id' => $alg['alg_obj_id'],
                        ))) > 0){
                        continue;
                    }

                    $new_idea_count++;

                    if($new_idea_count==1){
                        $message = 'I found these ideas for "'.$master_command.'":';
                    }

                    //List Idea:
                    $message .= "\n\n" . $new_idea_count . '. ' . $ins[0]['in_title'];
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => $new_idea_count,
                        'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'], //'SUBSCRIBE-INITIATE_' . $ins[0]['in_id']
                    ));
                }


                //Log idea search:
                $this->LEDGER_model->ln_create(array(
                    'ln_content' => ( $new_idea_count > 0 ? $message : 'Found ' . $new_idea_count . ' idea' . echo__s($new_idea_count) . ' matching [' . $master_command . ']' ),
                    'ln_metadata' => array(
                        'new_idea_count' => $new_idea_count,
                        'input_data' => $master_command,
                        'output' => $search_results,
                    ),
                    'ln_creator_source_id' => $en['en_id'], //user who searched
                    'ln_type_source_id' => 4275, //User Text Command Discover
                ));

            }


            if($new_idea_count > 0){

                //Give them a "None of the above" option:
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Cancel',
                    'payload' => 'SUBSCRIBE-REJECT',
                ));

                //return what we found to the user to decide:
                $this->COMMUNICATION_model->comm_message_send(
                    $message,
                    $en,
                    true,
                    $quick_replies
                );

            } else {

                //Respond to user:
                $this->COMMUNICATION_model->comm_message_send(
                    'I did not find any ideas to "' . $master_command . '", but I have made a idea of this and will let you know as soon as I am trained on this.',
                    $en,
                    true
                );

                //DISCOVER RECOMMENDATIONS
                $this->COMMUNICATION_model->comm_message_send(
                    echo_platform_message(12697),
                    $en,
                    true
                );

            }

        } else {


            /*
             *
             * Ok, if we're here it means we didn't really understand what
             * the User's idea was within their message.
             * So let's run through a few more options before letting them
             * know that we did not understand them...
             *
             * */


            //Quick Reply Manual Response...
            //We could not match the user command to any other command...
            //Now try to fetch the last quick reply that the user received from us:
            $last_quick_replies = $this->LEDGER_model->ln_fetch(array(
                'ln_creator_source_id' => $en['en_id'],
                'ln_type_source_id' => 6563, //User Received Quick Reply
            ), array(), 1);

            if(count($last_quick_replies) > 0){

                //We did find a recent quick reply!
                $ln_metadata = unserialize($last_quick_replies[0]['ln_metadata']);

                if(isset($ln_metadata['output_message']['message_body']['message']['quick_replies'])){

                    //Go through them:
                    foreach($ln_metadata['output_message']['message_body']['message']['quick_replies'] as $quick_reply){

                        //let's see if their text matches any of the quick reply options:
                        if(substr($fb_received_message, 0, strlen($quick_reply['title'])) == strtolower($quick_reply['title'])){

                            //Yes! We found a match, trigger the payload:
                            $quick_reply_results = $this->COMMUNICATION_model->comm_digest_payload($en, $quick_reply['payload']);

                            if(!$quick_reply_results['status']){

                                //There was an error, inform Player:
                                $this->LEDGER_model->ln_create(array(
                                    'ln_content' => 'comm_digest_payload() for custom response ['.$fb_received_message.'] returned error ['.$quick_reply_results['message'].']',
                                    'ln_metadata' => $ln_metadata,
                                    'ln_type_source_id' => 4246, //Platform Bug Reports
                                    'ln_creator_source_id' => $en['en_id'],
                                    'ln_parent_transaction_id' => $last_quick_replies[0]['ln_id'],
                                ));

                            } else {

                                //All good, log link:
                                $this->LEDGER_model->ln_create(array(
                                    'ln_creator_source_id' => $en['en_id'],
                                    'ln_type_source_id' => 4460, //User Sent Answer
                                    'ln_parent_transaction_id' => $last_quick_replies[0]['ln_id'],
                                    'ln_content' => $fb_received_message,
                                ));

                                //We resolved it:
                                return true;

                            }
                        }
                    }
                }
            }




            //Let's check to see if a Mench Player has not started a manual conversation with them via Facebook Inbox Chat:
            if (count($this->LEDGER_model->ln_fetch(array(
                    'ln_order' => 1, //A HACK to identify messages sent from us via Facebook Page Inbox
                    'ln_creator_source_id' => $en['en_id'],
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4280')) . ')' => null, //User Received Messages with Messenger
                    'ln_timestamp >=' => date("Y-m-d H:i:s", (time() - (1800))), //Messages sent from us less than 30 minutes ago
                ), array(), 1)) > 0) {

                //Yes, this user is talking to an Player so do not interrupt their conversation:
                return false;

            }


            //We don't know what they are talking about!


            //Inform User of Mench's one-way communication limitation & that Mench did not understand their message:
            $this->COMMUNICATION_model->comm_message_send(
                echo_platform_message(12693),
                $en,
                true
            );

            //Log link:
            $this->LEDGER_model->ln_create(array(
                'ln_creator_source_id' => $en['en_id'], //User who initiated this message
                'ln_content' => $fb_received_message,
                'ln_type_source_id' => 4287, //Log Unrecognizable Message Received
            ));

            //Call to Action: Does this user have any DISCOVER LISTs?
            $next_in_id = $this->DISCOVER_model->discover_next_go($en['en_id'], false);

            if($next_in_id > 0){

                //Inform User of Mench's one-way communication limitation & that Mench did not understand their message:
                $this->COMMUNICATION_model->comm_message_send(
                    'You can continue with your DISCOVER LIST by saying "Next"',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT_',
                        )
                    )
                );

            } else {

                //DISCOVER RECOMMENDATIONS
                $this->COMMUNICATION_model->comm_message_send(
                    echo_platform_message(12697),
                    $en,
                    true
                );

            }
        }
    }

    function comm_email_send($to_array, $subject, $html_message)
    {

        /*
         *
         * Send an email via our Amazon server
         *
         * */

        if (is_dev_environment()) {
            return false; //We cannot send emails on Dev server
        }

        //Loadup amazon SES:
        require_once('application/libraries/aws/aws-autoloader.php');
        $this->CLIENT = new Aws\Ses\SesClient([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => $this->config->item('cred_aws'),
        ]);

        return $this->CLIENT->sendEmail(array(
            // Source is required
            'Source' => 'support@mench.com',
            // Destination is required
            'Destination' => array(
                'ToAddresses' => $to_array,
                'CcAddresses' => array(),
                'BccAddresses' => array(),
            ),
            // Message is required
            'Message' => array(
                // Subject is required
                'Subject' => array(
                    // Data is required
                    'Data' => $subject,
                    'Charset' => 'UTF-8',
                ),
                // Body is required
                'Body' => array(
                    'Text' => array(
                        // Data is required
                        'Data' => strip_tags($html_message),
                        'Charset' => 'UTF-8',
                    ),
                    'Html' => array(
                        // Data is required
                        'Data' => $html_message,
                        'Charset' => 'UTF-8',
                    ),
                ),
            ),
            'ReplyToAddresses' => array('support@mench.com'),
            'ReturnPath' => 'support@mench.com',
        ));
    }

    function comm_facebook_graph($action, $graph_url, $payload = array())
    {

        //Do some initial checks
        if (!in_array($action, array('GET', 'POST', 'DELETE'))) {

            //Only 4 valid types of $action
            return array(
                'status' => 0,
                'message' => '$action [' . $action . '] is invalid',
            );

        }

        //Fetch access token and settings:
        $cred_facebook = $this->config->item('cred_facebook');

        $access_token_payload = array(
            'access_token' => $cred_facebook['mench_access_token']
        );

        if ($action == 'GET' && count($payload) > 0) {
            //Add $payload to GET variables:
            $access_token_payload = array_merge($payload, $access_token_payload);
            $payload = array();
        }

        $graph_url = 'https://graph.facebook.com/' . config_var(11077) . $graph_url;
        $counter = 0;
        foreach ($access_token_payload as $key => $val) {
            $graph_url = $graph_url . ($counter == 0 ? '?' : '&') . $key . '=' . $val;
            $counter++;
        }

        //Make the graph call:
        $ch = curl_init($graph_url);

        //Base setting:
        $ch_setting = array(
            CURLOPT_CUSTOMREQUEST => $action,
            CURLOPT_RETURNTRANSFER => TRUE,
        );

        if (count($payload) > 0) {
            $ch_setting[CURLOPT_HTTPHEADER] = array('Content-Type: application/json; charset=utf-8');
            $ch_setting[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        //Apply settings:
        curl_setopt_array($ch, $ch_setting);

        //Process results and produce ln_metadata
        $result = objectToArray(json_decode(curl_exec($ch)));
        $ln_metadata = array(
            'action' => $action,
            'payload' => $payload,
            'url' => $graph_url,
            'result' => $result,
        );

        //Did we have any issues?
        if (!$result) {

            //Failed to fetch this profile:
            $message_error = 'COMMUNICATION_model->comm_facebook_graph() failed to ' . $action . ' ' . $graph_url;
            $this->LEDGER_model->ln_create(array(
                'ln_content' => $message_error,
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_metadata' => $ln_metadata,
            ));

            //There was an issue accessing this on FB
            return array(
                'status' => 0,
                'message' => $message_error,
                'ln_metadata' => $ln_metadata,
            );

        } else {

            //All seems good, return:
            return array(
                'status' => 1,
                'message' => 'Success',
                'ln_metadata' => $ln_metadata,
            );

        }
    }

    function comm_message_send($input_message, $recipient_en = array(), $push_message = false, $quick_replies = array(), $message_in_id = 0)
    {

        /*
         *
         * The primary function that constructs messages based on the following inputs:
         *
         *
         * - $input_message:        The message text which may include source
         *                          references like "@123". This may NOT include
         *                          URLs as they must be first turned into an
         *                          source and then referenced within a message.
         *
         *
         * - $recipient_en:         The source object that this message is supposed
         *                          to be delivered to. May be an empty array for
         *                          when we want to show these messages to guests,
         *                          and it may contain the full source object or it
         *                          may only contain the source ID, which enables this
         *                          function to fetch further information from that
         *                          source as required based on its other parameters.
         *
         *
         * - $push_message:         If TRUE this function will prepare a message to be
         *                          delivered to use using either Messenger or Chrome. If FALSE, it
         *                          would prepare a message for immediate HTML view. The HTML
         *                          format will consider if a Player is logged in or not,
         *                          which will alter the HTML format.
         *
         *
         * - $quick_replies:        Only supported if $push_message = TRUE, and
         *                          will append an array of quick replies that will give
         *                          Users an easy way to tap and select their next step.
         *
         * */

        //This could happen with random messages
        if(strlen($input_message) < 1){
            return false;
        }

        //Validate message:
        $msg_validation = $this->COMMUNICATION_model->comm_message_construct($input_message, $recipient_en, $push_message, $quick_replies, 0, $message_in_id, false);


        //Did we have ane error in message validation?
        if (!$msg_validation['status'] || !isset($msg_validation['output_messages'])) {

            //Log Error Link:
            $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                'ln_content' => 'comm_message_construct() returned error [' . $msg_validation['message'] . '] for input message [' . $input_message . ']',
                'ln_metadata' => array(
                    'input_message' => $input_message,
                    'recipient_en' => $recipient_en,
                    'push_message' => $push_message,
                    'quick_replies' => $quick_replies,
                    'message_in_id' => $message_in_id
                ),
            ));

            return false;
        }

        //Message validation passed...
        $html_message_body = '';

        //Log message sent link:
        foreach ($msg_validation['output_messages'] as $output_message) {

            //Dispatch message based on format:
            if ($push_message) {

                if($msg_validation['user_chat_channel']==6196 /* Mench on Messenger */){

                    //Attempt to dispatch message via Facebook Graph API:
                    $fb_graph_process = $this->COMMUNICATION_model->comm_facebook_graph('POST', '/me/messages', $output_message['message_body']);

                    //Did we have an Error from the Facebook API side?
                    if (!$fb_graph_process['status']) {

                        //Ooopsi, we did! Log error Transcation:
                        $this->LEDGER_model->ln_create(array(
                            'ln_type_source_id' => 4246, //Platform Bug Reports
                            'ln_creator_source_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                            'ln_content' => 'comm_message_send() failed to send message via Facebook Graph API. See Metadata log for more details.',
                            'ln_metadata' => array(
                                'input_message' => $input_message,
                                'output_message' => $output_message['message_body'],
                                'fb_graph_process' => $fb_graph_process,
                            ),
                        ));

                        //Terminate function:
                        return false;

                    }

                } else {

                    $fb_graph_process = null; //No Facebook call made

                }

            } else {

                //HTML Format, add to message variable that will be returned at the end:
                $html_message_body .= $output_message['message_body'];

                $fb_graph_process = null; //No Facebook call made

            }

            //Log successful Link for message delivery:
            if(isset($recipient_en['en_id']) && $push_message){
                $this->LEDGER_model->ln_create(array(
                    'ln_content' => $msg_validation['input_message'],
                    'ln_type_source_id' => $output_message['message_type_en_id'],
                    'ln_creator_source_id' => $recipient_en['en_id'],
                    'ln_profile_source_id' => $msg_validation['ln_profile_source_id'], //Might be set if message had a referenced source
                    'ln_metadata' => array(
                        'input_message' => $input_message,
                        'output_message' => $output_message,
                        'fb_graph_process' => $fb_graph_process,
                    ),
                ));
            }

        }

        //If we're here it's all good:
        return ( $push_message ? true : $html_message_body );

    }

    function comm_message_construct($input_message, $recipient_en = array(), $push_message = false, $quick_replies = array(), $message_type_en_id = 0, $message_in_id = 0, $strict_validation = true)
    {

        /*
         *
         * This function is used to validate Idea Notes.
         *
         * See comm_message_send() for more information on input variables.
         *
         * */


        //Try to fetch session if recipient not provided:
        if(!isset($recipient_en['en_id'])){
            $recipient_en = superpower_assigned();
        }

        $is_being_modified = ( $message_type_en_id > 0 ); //IF $message_type_en_id > 0 means we're adding/editing and need to do extra checks

        //Cleanup:
        $input_message = trim($input_message);
        $input_message = str_replace('’','\'',$input_message);

        //Start with basic input validation:
        if (strlen($input_message) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message Content',
            );
        } elseif ($strict_validation && strlen($input_message) > config_var(4485)) {
            return array(
                'status' => 0,
                'message' => 'Message is '.strlen($input_message).' characters long which is more than the allowed ' . config_var(4485) . ' characters',
            );
        } elseif (!preg_match('//u', $input_message)) {
            return array(
                'status' => 0,
                'message' => 'Message must be UTF8',
            );
        } elseif ($push_message && !isset($recipient_en['en_id'])) {
            return array(
                'status' => 0,
                'message' => 'Facebook Messenger Format requires a recipient source ID to construct a message',
            );
        } elseif (count($quick_replies) > 0 && !$push_message) {
            /*
             * TODO Enable later on...
            return array(
                'status' => 0,
                'message' => 'Quick Replies are only supported for PUSH messages',
            );
            */
        } elseif ($message_type_en_id > 0 && !in_array($message_type_en_id, $this->config->item('en_ids_4485'))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Message type ID',
            );
        }


        /*
         *
         * Let's do a generic message reference validation
         * that does not consider $message_type_en_id if passed
         *
         * */
        $string_references = extract_source_references($input_message);

        if($strict_validation){
            //Check only in strict mode:
            if (count($string_references['ref_urls']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'You can reference a maximum of 1 URL per message',
                );

            } elseif (count($string_references['ref_sources']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'Message can include a maximum of 1 source reference',
                );

            } elseif (!$push_message && count($string_references['ref_sources']) > 0 && count($string_references['ref_urls']) > 0) {

                return array(
                    'status' => 0,
                    'message' => 'You can either reference an source OR a URL, as URLs are transformed to sources',
                );

            }
        }



        /*
         *
         * $message_type_en_id Validation
         * only in strict mode!
         *
         * */
        if($strict_validation && $message_type_en_id > 0){

            //See if this message type has specific input requirements:
            $en_all_4485 = $this->config->item('en_all_4485');

            //Now check for source referencing settings:
            if(!in_array(4986 , $en_all_4485[$message_type_en_id]['m_parents']) && !in_array(7551 , $en_all_4485[$message_type_en_id]['m_parents']) && count($string_references['ref_sources']) > 0){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' do not support source referencing.',
                );

            } elseif(in_array(7551 , $en_all_4485[$message_type_en_id]['m_parents']) && count($string_references['ref_sources']) != 1 && count($string_references['ref_urls']) != 1){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' require an source reference.',
                );

            }

        }







        /*
         *
         * Fetch more details on recipient source if needed
         *
         * */




        //See if we have a valid way to connect to them if push:
        if ($push_message) {

            $user_messenger = $this->LEDGER_model->ln_fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_profile_source_id' => 6196, //Mench Messenger
                'ln_portfolio_source_id' => $recipient_en['en_id'],
                'ln_external_id >' => 0,
            ));

            //Messenger has a higher priority than email, is the user connected?
            if(count($user_messenger) > 0) {

                $user_chat_channel = 6196; //Mench on Messenger

            } else {

                //See if they have an email:
                $user_emails = $this->LEDGER_model->ln_fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_portfolio_source_id' => $recipient_en['en_id'],
                    'ln_type_source_id' => 4255, //Linked Players Text (Email is text)
                    'ln_profile_source_id' => 3288, //Mench Email
                ));

                if(count($user_emails) > 0){

                    $user_chat_channel = 12103; //Web+Email

                } else {

                    //No way to communicate with user:
                    return array(
                        'status' => 0,
                        'message' => 'Player @' . $recipient_en['en_id'] . ' has not joined yet',
                    );

                }

            }

        } else {

            //No communication channel since the message is NOT being pushed:
            $user_chat_channel = 0;

        }


        /*
         *
         * Fetch notification level IF $push_message = TRUE
         *
         * */

        if ($push_message && $user_chat_channel==6196 /* Mench on Messenger */) {


            $en_all_11058 = $this->config->item('en_all_11058');

            //Fetch recipient notification type:
            $lns_comm_level = $this->LEDGER_model->ln_fetch(array(
                'ln_profile_source_id IN (' . join(',', $this->config->item('en_ids_4454')) . ')' => null,
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_portfolio_source_id' => $recipient_en['en_id'],
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            ));

            //Start validating communication settings we fetched to ensure everything is A-OK:
            if (count($lns_comm_level) < 1) {

                return array(
                    'status' => 0,
                    'message' => 'User is missing their Messenger Notification Level',
                );

            } elseif (count($lns_comm_level) > 1) {

                //This should find exactly one result
                return array(
                    'status' => 0,
                    'message' => 'User has more than 1 Notification Level parent source relation',
                );

            } elseif (!array_key_exists($lns_comm_level[0]['ln_profile_source_id'], $en_all_11058)) {

                return array(
                    'status' => 0,
                    'message' => 'Fetched unknown Notification Level [' . $lns_comm_level[0]['ln_profile_source_id'] . ']',
                );

            } else {

                //All good, Set notification type:
                $notification_type = $en_all_11058[$lns_comm_level[0]['ln_profile_source_id']]['m_desc'];

            }
        }


        /*
         *
         * Transform URLs into Player + Links
         *
         * */
        if ($strict_validation && count($string_references['ref_urls']) > 0) {

            //No source linked, but we have a URL that we should turn into an source if not previously:
            $url_source = $this->SOURCE_model->en_url($string_references['ref_urls'][0], ( isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0 ), ( isset($recipient_en['en_id']) ? array($recipient_en['en_id']) : array() ));

            //Did we have an error?
            if (!$url_source['status'] || !isset($url_source['en_url']['en_id']) || intval($url_source['en_url']['en_id']) < 1) {
                return $url_source;
            }

            //Transform this URL into an source IF it was found/created:
            if(intval($url_source['en_url']['en_id']) > 0){

                $string_references['ref_sources'][0] = intval($url_source['en_url']['en_id']);

                //Replace the URL with this new @source in message.
                //This is the only valid modification we can do to $input_message before storing it in the DB:
                $input_message = str_replace($string_references['ref_urls'][0], '@' . $string_references['ref_sources'][0], $input_message);

                //Delete URL:
                unset($string_references['ref_urls'][0]);

            }

        }


        /*
         *
         * Process Commands
         *
         * */

        //Start building the Output message body based on format:
        $output_body_message = ( $push_message ? $input_message : htmlentities($input_message) );



        /*
         *
         * Referenced Player
         *
         * */

        //Will contain media from referenced source:
        $fb_media_attachments = array();

        //We assume this message has text, unless its only content is an source reference like "@123"
        $has_text = true;

        if (count($string_references['ref_sources']) > 0) {

            //We have a reference within this message, let's fetch it to better understand it:
            $ens = $this->SOURCE_model->en_fetch(array(
                'en_id' => $string_references['ref_sources'][0], //Alert: We will only have a single reference per message
            ));

            if (count($ens) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced source @' . $string_references['ref_sources'][0] . ' not found',
                );
            }

            //Direct Media URLs supported:
            $en_all_11059 = $this->config->item('en_all_11059');
            $en_all_6177 = $this->config->item('en_all_6177');

            //We send Media in their original format IF $push_message = TRUE, which means we need to convert link types:
            if ($push_message) {
                //Converts Player Link Types to their corresponding User Message Sent Link Types:
                $master_media_sent_conv = array(
                    4258 => 4553, //video
                    4259 => 4554, //audio
                    4260 => 4555, //image
                    4261 => 4556, //file
                );
            }

            //See if this source has any parent links to be shown in this appendix
            $valid_url = array();
            $message_visual_media = 0;
            $source_appendix = null;
            $current_mench = current_mench();

            //Determine what type of Media this reference has:
            if(!($current_mench['x_name']=='source' && $this->uri->segment(2)==$string_references['ref_sources'][0])){

                //Source Profile
                foreach ($this->LEDGER_model->ln_fetch(array(
                    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Source Status Public
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12822')) . ')' => null, //SOURCE LINK MESSAGE DISPLAY
                    'ln_portfolio_source_id' => $string_references['ref_sources'][0],
                ), array('en_profile'), 0, 0, array('en_id' => 'ASC' /* Hack to get Text first */)) as $parent_en) {

                    if (in_array($parent_en['ln_type_source_id'], $this->config->item('en_ids_12524'))) {

                        //Raw media file: Audio, Video, Image OR File...
                        $message_visual_media++;

                    } elseif($parent_en['ln_type_source_id'] == 4256 /* URL */){

                        array_push($valid_url, $parent_en['ln_content']);

                    } elseif($parent_en['ln_type_source_id'] == 4255 /* TEXT */){

                        if($push_message){

                        } else {
                            //Also append text:
                            $source_appendix .= '<div class="source-appendix padding-top-down">' . $parent_en['ln_content'] . '</div>';
                        }
                        continue;

                    } else {

                        //Not supported for now:
                        continue;

                    }

                    if($push_message){

                        //Messenger templates:
                        if (in_array($parent_en['ln_type_source_id'], $this->config->item('en_ids_11059'))) {

                            //Search for Facebook Attachment ID IF $push_message = TRUE
                            $fb_att_id = 0;
                            if (strlen($parent_en['ln_metadata']) > 0) {
                                //We might have a Facebook Attachment ID saved in Metadata, check to see:
                                $metadata = unserialize($parent_en['ln_metadata']);
                                if (isset($metadata['fb_att_id']) && intval($metadata['fb_att_id']) > 0) {
                                    //Yes we do, use this for faster media attachments:
                                    $fb_att_id = intval($metadata['fb_att_id']);
                                }
                            }

                            //Push raw file to Media Array:
                            array_push($fb_media_attachments, array(
                                'ln_type_source_id' => $master_media_sent_conv[$parent_en['ln_type_source_id']],
                                'ln_content' => ($fb_att_id > 0 ? null : $parent_en['ln_content']),
                                'fb_att_id' => $fb_att_id,
                                'fb_att_type' => $en_all_11059[$parent_en['ln_type_source_id']]['m_desc'],
                            ));

                        } else {

                            //Generic URL:
                            array_push($fb_media_attachments, array(
                                'ln_type_source_id' => 4552, //Text Message Sent
                                'ln_content' => $parent_en['ln_content'],
                                'fb_att_id' => 0,
                                'fb_att_type' => null,
                            ));

                        }

                    } else {

                        $source_appendix .= '<div class="source-appendix padding-top-down">' . echo_url_types($parent_en['ln_content'], $parent_en['ln_type_source_id']) . '</div>';

                    }
                }
            }

            //Determine if we have text:
            $has_text = !(trim($output_body_message) == '@' . $string_references['ref_sources'][0]);


            //Append any appendix generated:
            $output_body_message .= $source_appendix;



            //Adjust
            if (!$push_message) {

                /*
                 *
                 * HTML Message format, which will
                 * include a link to the Player for quick access
                 * to more information about that source:=.
                 *
                 * */

                $output_body_message = str_replace('@' . $string_references['ref_sources'][0], '<span class="inline-block '.( $message_visual_media > 0 && $current_mench['x_name']=='discover' ? superpower_active(10939) : '' ).'">'.( !in_array($ens[0]['en_status_source_id'], $this->config->item('en_ids_7357')) ? '<span class="img-block">'.$en_all_6177[$ens[0]['en_status_source_id']]['m_icon'].'</span> ' : '' ).'<a class="montserrat doupper '.extract_icon_color($ens[0]['en_icon']).'" href="/source/' . $ens[0]['en_id'] . '"><span class="img-block">'.echo_en_icon($ens[0]['en_icon']).'</span>&nbsp;' . $ens[0]['en_name']  . '</a></span>', $output_body_message);

            } else {

                //Just replace with the source name, which ensure we're always have a text in our message even if $has_text = FALSE
                $source_replace_name = ( $has_text ? $ens[0]['en_name'] : '' );
                $output_body_message = str_replace('@' . $string_references['ref_sources'][0], $source_replace_name, $output_body_message);

            }
        }




        /*
         *
         * Construct Message based on current data
         *
         * $output_messages will determines the type & content of the
         * message(s) that will to be sent. We might need to send
         * multiple messages IF $push_message = TRUE and the
         * text message has a referenced source with a one or more
         * media file (Like video, image, file or audio).
         *
         * The format of this will be array( $ln_portfolio_source_id => $ln_content )
         * to define both message and it's type.
         *
         * See all sent message types here: https://mench.com/source/4280
         *
         * */
        $output_messages = array();

        if ($push_message && $user_chat_channel==6196 /* Mench on Messenger */) {


            //Do we have a text message?
            if ($has_text) {

                //Have Link?
                if (0) {

                    //We have a fixed button to append to this message:
                    $fb_message = array(
                        'attachment' => array(
                            'type' => 'template',
                            'payload' => array(
                                'template_type' => 'button',
                                'text' => $output_body_message,
                                'buttons' => array(
                                    array(
                                        'type' => 'web_url',
                                        'url' => 'URL',
                                        'title' => 'TITLE',
                                        'webview_height_ratio' => 'tall',
                                        'webview_share_button' => 'hide',
                                        'messenger_extensions' => true,
                                    ),
                                ),
                            ),
                        ),
                        'metadata' => 'system_logged', //Prevents duplicate Link logs
                    );

                } elseif ($has_text) {

                    //No button, just text:
                    $fb_message = array(
                        'text' => $output_body_message,
                        'metadata' => 'system_logged', //Prevents duplicate Link logs
                    );

                    if(count($quick_replies) > 0){
                        $fb_message['quick_replies'] = $quick_replies;
                    }

                }

                //Add to output message:
                array_push($output_messages, array(
                    'message_type_en_id' => ( isset($fb_message['quick_replies']) && count($fb_message['quick_replies']) > 0 ? 6563 : 4552 ), //Text OR Quick Reply Message Sent
                    'message_body' => array(
                        'recipient' => array(
                            'id' => $user_messenger[0]['ln_external_id'],
                        ),
                        'message' => $fb_message,
                        'notification_type' => $notification_type,
                        'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION',
                    ),
                ));

            }


            if (!$has_text && count($quick_replies) > 0) {

                //This is an error:
                $this->LEDGER_model->ln_create(array(
                    'ln_content' => 'comm_message_construct() was given quick replies without a text message',
                    'ln_metadata' => array(
                        'input_message' => $input_message,
                        'push_message' => $push_message,
                        'quick_replies' => $quick_replies,
                    ),
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_creator_source_id' => $recipient_en['en_id'],
                    'ln_profile_source_id' => $message_type_en_id,
                    'ln_next_idea_id' => $message_in_id,
                ));

            }


            if (count($fb_media_attachments) > 0) {

                //We do have additional messages...
                //TODO Maybe add another message to give User some context on these?

                //Append messages:
                foreach ($fb_media_attachments as $fb_media_attachment) {

                    //See what type of attachment (if any) this is:
                    if (!$fb_media_attachment['fb_att_type']) {

                        //This is a text message, not an attachment:
                        $fb_message = array(
                            'text' => $fb_media_attachment['ln_content'],
                            'metadata' => 'system_logged', //Prevents duplicate Link logs
                        );

                    } elseif ($fb_media_attachment['fb_att_id'] > 0) {

                        //Saved Attachment that can be served instantly:
                        $fb_message = array(
                            'attachment' => array(
                                'type' => $fb_media_attachment['fb_att_type'],
                                'payload' => array(
                                    'attachment_id' => $fb_media_attachment['fb_att_id'],
                                ),
                            ),
                            'metadata' => 'system_logged', //Prevents duplicate Link logs
                        );

                    } else {

                        //Attachment that needs to be uploaded via URL which will take a few seconds:
                        $fb_message = array(
                            'attachment' => array(
                                'type' => $fb_media_attachment['fb_att_type'],
                                'payload' => array(
                                    'url' => $fb_media_attachment['ln_content'],
                                    'is_reusable' => true,
                                ),
                            ),
                            'metadata' => 'system_logged', //Prevents duplicate Link logs
                        );

                    }

                    //Add to output message:
                    array_push($output_messages, array(
                        'message_type_en_id' => $fb_media_attachment['ln_type_source_id'],
                        'message_body' => array(
                            'recipient' => array(
                                'id' => $user_messenger[0]['ln_external_id'],
                            ),
                            'message' => $fb_message,
                            'notification_type' => $notification_type,
                            'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION',
                        ),
                    ));

                }
            }

        } else {


            //Always returns a single (sometimes long) HTML message:
            array_push($output_messages, array(
                'message_type_en_id' => 4570, //User Received Email Message
                'message_body' => '<div class="i_content padded"><div class="msg">' . nl2br($output_body_message) . '</div></div>',
            ));

        }


        //Return results:
        return array(
            'status' => 1,
            'input_message' => trim($input_message),
            'output_messages' => $output_messages,
            'user_chat_channel' => $user_chat_channel,
            'ln_profile_source_id' => (count($string_references['ref_sources']) > 0 ? $string_references['ref_sources'][0] : 0),
        );

    }

}

?>