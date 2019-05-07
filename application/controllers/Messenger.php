<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messenger extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }


    function deprecate__actionplan_assessment_webhook(){

        //Validate core input variables from Webhook call:
        if(!isset($_POST['en_id']) || intval($_POST['en_id']) < 1 || !isset($_POST['in_id']) || intval($_POST['in_id']) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'deprecate__actionplan_assessment_webhook() missing core variables',
            ));
        }


        //Validate intent and entity:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status' => 2, //Published
        ));
        $ens = $this->Entities_model->en_fetch(array(
            'en_id' => $_POST['en_id'],
            'en_status' => 2, //Published
        ));
        if(count($ins) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'deprecate__actionplan_assessment_webhook() unable to locate a published intent',
            ));
        } elseif(count($ens) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'deprecate__actionplan_assessment_webhook() unable to locate a published entity',
            ));
        } elseif(count()==0){
            //Failed validation that this miner has completed this intention in their Action Plan:
            return echo_json(array(
                'status' => 0,
                'message' => 'deprecate__actionplan_assessment_webhook() unable to locate a student action plan progression step',
            ));
        }


        /*
         *
         * Define proficiency_levels as a more user
         * friendly way to make sense of their score...
         * Will evolve this over time.
         *
         * */


        //Define how this assessment score calculator should work:
        $app_settings = array(

            //The min students required to have completed this assessment before a relative percentile is calculated:
            'min_student_count' => 20, //If smaller, we won't have a large enough sample size!

            //The transition of the relative percentile score into a human-friendly terminology:
            'proficiency_levels' => array(
                1 => array(
                    'min_relative_percentile' => 0,
                    'max_relative_percentile' => 30,
                    'level_name' => 'Beginner',
                ),
                2 => array(
                    'min_relative_percentile' => 31,
                    'max_relative_percentile' => 60,
                    'level_name' => 'Intermediate',
                ),
                3 => array(
                    'min_relative_percentile' => 61,
                    'max_relative_percentile' => 90,
                    'level_name' => 'Advanced',
                ),
                4 => array(
                    'min_relative_percentile' => 91,
                    'max_relative_percentile' => 100,
                    'level_name' => 'Superstar',
                ),
            ),

        );

        $message = 'Congrats! You completed your intention to Assess your JQuery programming skills. Here are your results: You are a beginner jQuery developer. You score was 35% and you got 14/32 questions right.';


        //Calculate the student's score for this tree based on their Action Plan progress:
        $score_card = array(
            'steps_all_counts' => 21,
            'steps_success_counts' => 17,
            'score_min_marks' => 12,
            'score_max_marks' => 104,
            'score_student_marks' => 91,
            'score_student_all_count' => 12, //All students who have taken this assessment already

            //To be calculated only if we meet the minimum students:
            'score_student_position_count' => 0, //1 = top student, and can be as low as the total number of students taken this assessment (means you're last!)
            'score_relative_percentile' => 0,
            'message_relative_percentile' => '',
            'score_proficiency_level' => 0,
        );

        $score_card['score_absolute_percentage'] = floor(($score_card['score_success_marks']-$score_card['score_min_marks']) / ($score_card['score_max_marks']-$score_card['score_min_marks']) * 100);
        $score_card['message_absolute_percentage'] = 'Your score card is in for your intention to '.$ins[0]['in_outcome'].': '.$score_card['score_absolute_percentage'].'%! You got '.$score_card['steps_success_counts'].'/'.$score_card['steps_all_counts'].' questions right.';

        //Send them message with their absolute percentage score.
        $this->Communication_model->dispatch_message(
            $score_card['message_absolute_percentage'],
            array('en_id' => $ens[0]['en_id']),
            true,
            array(
                array(
                    'content_type' => 'text',
                    'title' => 'Next',
                    'payload' => 'GONEXT',
                )
            ),
            array(
                'ln_parent_intent_id' => $ins[0]['in_id'],
            )
        );



        if($score_card['score_student_all_count'] >= $app_settings['min_student_count']) {

            //Calculate student position among all taken:
            $score_card['score_student_position_count'] = 2;

            //Based on their absolute percentage, now calculate their relative percentile:
            $score_card['score_relative_percentile'] = floor (( ($score_card['score_student_all_count'] - $score_card['score_student_position_count']) / ($score_card['score_student_all_count']-1) ) * 100 );

            $score_card['message_relative_percentile'] = ( $score_card['score_relative_percentile']>=50 ? 'top '.(101 - $score_card['score_relative_percentile']).'%' : 'bottom '.($score_card['score_relative_percentile'] + 1).'%' );


            //We have the minimum number of students taken this assessment, issue the score board immediately:
            $score_card['summary_message'] = 'This puts you in the top 20% of people who took this assessment. Based on this result we believe your level is *beginner*';


            //Set link status to published:
            $link_data['ln_status'] = 2;
            $link_data['ln_metadata'] = array(
                'score_sheet' => $score_card
            );


            //Inform the student of their score:
            $this->Communication_model->dispatch_message(
                'Good news: Enough students have taken this assessment which means I can inform you of where your '.$score_card['score_absolute_percentage'].'% score stands relative to your peers: You are the top 20% which means you are a *'.$app_settings['proficiency_levels'][$score_card['score_proficiency_level']]['level_name'].'* in response to your intention to '.$ins[0]['in_outcome'],
                array('en_id' => $ens[0]['en_id']),
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'GONEXT',
                    )
                ),
                array(
                    'ln_parent_intent_id' => $ins[0]['in_id'],
                )
            );


            //Log link for evaluating the Conditional Milestone Link:
            $conditional_evaluation = $this->Links_model->ln_create(array(
                'ln_status' => 2, //Log as a New link unless we meet the minimum student requirement to publish it instantly...
                'ln_type_entity_id' => 6278, //Assessed Conditional Milestone Link
                'ln_miner_entity_id' => $ens[0]['en_id'],
                'ln_parent_intent_id' => $ins[0]['in_id'],
            ));

        } else {

            //We don't have enough student's yet! Create a pending score card so we get to update it later:

            //Let them know that we can't yet give them a relative percentile:
            $this->Communication_model->dispatch_message(
                'As I issue score cards to more students with the same intention, I will be able to gather a large enough sample size to inform you of your relative percentile and relative proficiency level so you have a better idea of where your score of '.$score_card['score_absolute_percentage'].'% stands relative to your peers.',
                array('en_id' => $ens[0]['en_id']),
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'GONEXT',
                    )
                ),
                array(
                    'ln_parent_intent_id' => $ins[0]['in_id'],
                )
            );

        }


        //Return success:
        return echo_json(array(
            'status' => 1,
            'message' => $score_card['summary_message'],
            'score_link' => $score_link,
        ));
    }



    function api_webhook($test = 0)
    {

        /*
         *
         * The master function for all Facebook webhook calls
         * This URL is set as our end to receive Facebook calls:
         *
         * https://developers.facebook.com/apps/1782431902047009/webhooks/
         *
         * */


        //We need this only for the first time to authenticate that we own the server:
        if (isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token']) && $_GET['hub_verify_token'] == '722bb4e2bac428aa697cc97a605b2c5a') {
            return print_r($_GET['hub_challenge']);
        }

        //Fetch input data:
        $fb_settings = $this->config->item('fb_settings');

        if($test){
            $ln_metadata = objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1557167164354,"messaging":[{"sender":{"id":"1234880879950857"},"recipient":{"id":"381488558920384"},"timestamp":1557128383000,"message":{"quick_reply":{"payload":"ANSWERQUESTION_9295_9298"},"mid":"UcT9GZXJAm9tR1pjIvXUQv2t4AOQjIajAPJbGvHuA9nVaUUam3pCO3YSEoY8Eyh2-L1XIsMtC__mrpSXIUGn2A","seq":82388,"text":"3"}}]}]}'));
        } else {
            //Real webhook data:
            $ln_metadata = json_decode(file_get_contents('php://input'), true);
        }


        //Do some basic checks:
        if (!isset($ln_metadata['object']) || !isset($ln_metadata['entry'])) {
            //Likely loaded the URL in browser:
            return print_r('complete');
        } elseif ($ln_metadata['object'] != 'page') {
            $this->Links_model->ln_create(array(
                'ln_content' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'ln_metadata' => $ln_metadata,
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return print_r('complete');
        }


        //Loop through entries:
        foreach ($ln_metadata['entry'] as $entry) {

            //check the page ID:
            if (!isset($entry['id']) || !($entry['id'] == $fb_settings['page_id'])) {
                //This can happen for the older webhook that we offered to other FB pages:
                continue;
            } elseif (!isset($entry['messaging'])) {
                $this->Links_model->ln_create(array(
                    'ln_content' => 'facebook_webhook() call missing messaging Array().',
                    'ln_metadata' => $ln_metadata,
                    'ln_type_entity_id' => 4246, //Platform Error
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                ));
                continue;
            }

            //loop though the messages:
            foreach ($entry['messaging'] as $im) {

                if (isset($im['read']) || isset($im['delivery'])) {

                    //Message read OR delivered
                    $ln_type_entity_id = (isset($im['delivery']) ? 4279 /* Message Delivered */ : 4278 /* Message Read */);

                    //Authenticate Student:
                    $en = $this->Entities_model->en_authenticate_psid($im['sender']['id']);

                    //Log Link Only IF last delivery link was 3+ minutes ago (Since Facebook sends many of these):
                    $last_trs_logged = $this->Links_model->ln_fetch(array(
                        'ln_type_entity_id' => $ln_type_entity_id,
                        'ln_miner_entity_id' => $en['en_id'],
                        'ln_timestamp >=' => date("Y-m-d H:i:s", (time() - (180))), //Links logged less than 3 minutes ago
                    ), array(), 1);

                    if (count($last_trs_logged) == 0) {
                        //We had no recent links of this kind, so go ahead and log:
                        $this->Links_model->ln_create(array(
                            'ln_metadata' => $ln_metadata,
                            'ln_type_entity_id' => $ln_type_entity_id,
                            'ln_miner_entity_id' => $en['en_id'],
                        ));
                    }

                } elseif (isset($im['message'])) {

                    /*
                     *
                     * Triggered for all incoming messages and also for
                     * outgoing messages sent using the Facebook Inbox UI.
                     *
                     * */

                    //Is this a non loggable message? If so, this has already been logged by Mench:
                    if (isset($im['message']['metadata']) && $im['message']['metadata'] == 'system_logged') {

                        //This is already logged! No need to take further action!
                        return print_r('complete');

                    }


                    //Set variables:
                    $sent_by_mench = (isset($im['message']['is_echo'])); //Indicates the message sent from the page itself
                    $en = $this->Entities_model->en_authenticate_psid(($sent_by_mench ? $im['recipient']['id'] : $im['sender']['id']));
                    $is_quick_reply = (isset($im['message']['quick_reply']['payload']));

                    //Check if this Student is unsubscribed:
                    if (!$is_quick_reply && count($this->Links_model->ln_fetch(array(
                            'ln_parent_entity_id' => 4455, //Unsubscribed
                            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                            'ln_child_entity_id' => $en['en_id'],
                            'ln_status' => 2, //Published
                        ))) > 0) {

                        //Yes, this Student is Unsubscribed! Give them an option to re-activate their Mench account:
                        $this->Communication_model->dispatch_message(
                            'You are currently unsubscribed. Would you like me to re-activate your account?',
                            $en,
                            true,
                            array(
                                array(
                                    'content_type' => 'text',
                                    'title' => 'Yes, Re-Activate',
                                    'payload' => 'RESUBSCRIBE_YES',
                                ),
                                array(
                                    'content_type' => 'text',
                                    'title' => 'Stay Unsubscribed',
                                    'payload' => 'RESUBSCRIBE_NO',
                                ),
                            )
                        );

                        //Terminate:
                        return print_r('complete');
                    }


                    //Set more variables:
                    $in_requirements_search = 0; //If >0, we will try to see if this message is to submit a requirement for an intent

                    unset($ln_data); //Reset everything in case its set from the previous loop!
                    $ln_data = array(
                        'ln_miner_entity_id' => $en['en_id'],
                        'ln_metadata' => $ln_metadata, //Entire JSON object received by Facebook API
                        'ln_order' => ($sent_by_mench ? 1 : 0), //A HACK to identify messages sent from us via Facebook Page Inbox
                    );

                    /*
                     *
                     * Now complete the link data based on message type.
                     * We will generally receive 3 types of Facebook Messages:
                     *
                     * - Quick Replies
                     * - Text Messages
                     * - Attachments
                     *
                     * And we will deal with each group, and their sub-group
                     * appropriately based on who sent the message (Mench/Student)
                     *
                     * */

                    if ($is_quick_reply) {

                        //Quick Reply Answer Received:
                        $ln_data['ln_type_entity_id'] = 4460;
                        $ln_data['ln_content'] = $im['message']['text']; //Quick reply always has a text

                        //Digest quick reply:
                        $quick_reply_results = $this->Communication_model->digest_quick_reply($en, $im['message']['quick_reply']['payload']);
                        if(!$quick_reply_results['status']){
                            //There was an error, inform admin:
                            $this->Links_model->ln_create(array(
                                'ln_content' => 'digest_quick_reply() for message returned error ['.$quick_reply_results['message'].']',
                                'ln_metadata' => $ln_metadata,
                                'ln_type_entity_id' => 4246, //Platform Error
                                'ln_miner_entity_id' => 1, //Shervin/Developer
                                'ln_child_entity_id' => $en['en_id'],
                            ));

                        }

                    } elseif (isset($im['message']['text'])) {

                        //Set message content:
                        $ln_data['ln_content'] = $im['message']['text'];

                        //Who sent this?
                        if ($sent_by_mench) {

                            $ln_data['ln_type_entity_id'] = 4552; //Student Received Text Message

                        } else {

                            //Could be either text or URL:
                            $in_requirements_search = ( filter_var($im['message']['text'], FILTER_VALIDATE_URL) ? 4256 /* URL */ : 4255 /* Text */ );

                            $ln_data['ln_type_entity_id'] = 4547; //Student Sent Text Message

                        }

                    } elseif (isset($im['message']['attachments'])) {

                        //We have some attachments, lets loops through them:
                        foreach ($im['message']['attachments'] as $att) {


                            //Define 4 main Attachment Message Types:
                            $att_media_types = array( //Converts video, audio, image and file messages
                                'video' => array(
                                    'sent' => 4553,     //Link type for when sent to Students via Messenger
                                    'received' => 4548, //Link type for when received from Students via Messenger
                                    'requirement' => 4258,
                                ),
                                'audio' => array(
                                    'sent' => 4554,
                                    'received' => 4549,
                                    'requirement' => 4259,
                                ),
                                'image' => array(
                                    'sent' => 4555,
                                    'received' => 4550,
                                    'requirement' => 4260,
                                ),
                                'file' => array(
                                    'sent' => 4556,
                                    'received' => 4551,
                                    'requirement' => 4261,
                                ),
                            );

                            if (array_key_exists($att['type'], $att_media_types)) {

                                /*
                                 *
                                 * This is a media attachment.
                                 *
                                 * We cannot save this Media on-demand because it takes
                                 * a few seconds depending on the file size which would
                                 * delay our response long-enough that Facebook thinks
                                 * our server is none-responsive which would cause
                                 * Facebook to resent this Attachment!
                                 *
                                 * The solution is to create a @4299 link to save
                                 * this attachment using a cron job later on.
                                 *
                                 * */

                                $ln_data['ln_type_entity_id'] = $att_media_types[$att['type']][($sent_by_mench ? 'sent' : 'received')];
                                $ln_data['ln_content'] = $att['payload']['url']; //Media Attachment Temporary Facebook URL
                                $ln_data['ln_status'] = 0; //drafting, since URL needs to be uploaded to Mench CDN via cron__save_chat_media()
                                if(!$sent_by_mench){
                                    $in_requirements_search = $att_media_types[$att['type']]['requirement'];
                                }

                            } elseif ($att['type'] == 'location') {

                                //Location Message Received:
                                $ln_data['ln_type_entity_id'] = 4557;

                                /*
                                 *
                                 * We do not have the ability to send this
                                 * type of message at this time and we will
                                 * only receive it if the Student decides to
                                 * send us their location for some reason.
                                 *
                                 * Message with location attachment which
                                 * could have up to 4 main elements:
                                 *
                                 * */

                                //Generate a URL from this location data:
                                if (isset($att['url']) && strlen($att['url']) > 0) {
                                    //Sometimes Facebook Might provide a full URL:
                                    $ln_data['ln_content'] = $att['url'];
                                } else {
                                    //If not, we can generate our own URL using the Lat/Lng that will always be provided:
                                    $ln_data['ln_content'] = 'https://www.google.com/maps?q=' . $att['payload']['coordinates']['lat'] . '+' . $att['payload']['coordinates']['long'];
                                }

                            } elseif ($att['type'] == 'template') {

                                /*
                                 *
                                 * Message with template attachment, like a
                                 * button or something...
                                 *
                                 * Will have value $att['payload']['template_type'];
                                 *
                                 * TODO implement later on maybe? Not sure how this is useful...
                                 *
                                 * */

                            } elseif ($att['type'] == 'fallback') {

                                /*
                                 *
                                 * A fallback attachment is any attachment
                                 * not currently recognized or supported
                                 * by the Message Echo feature.
                                 *
                                 * We can ignore them for now :)
                                 * TODO implement later on maybe? Not sure how this is useful...
                                 *
                                 * */

                            }
                        }
                    }


                    //So did we recognized the
                    if (!isset($ln_data['ln_type_entity_id']) || !isset($ln_data['ln_miner_entity_id'])) {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->Links_model->ln_create(array(
                            'ln_type_entity_id' => 4246, //Platform Error
                            'ln_miner_entity_id' => 1, //Shervin/Developer
                            'ln_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details',
                            'ln_metadata' => $ln_metadata,
                        ));

                        //Terminate:
                        return print_r('complete');
                    }


                    //We're all good, log this message:
                    $new_message = $this->Links_model->ln_create($ln_data);


                    //Did we have a potential response?
                    if(isset($new_message['ln_id']) && $in_requirements_search > 0){

                        //Yes, see if we have a pending requirement submission:
                        $pending_in_requirements = $this->Links_model->ln_fetch(array(
                            'ln_type_entity_id' => 6144, //Action Plan Submit Requirements
                            'ln_miner_entity_id' => $ln_data['ln_miner_entity_id'], //for this student
                            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete
                            'in_status' => 2, //Published
                            'in_requirement_entity_id' => $in_requirements_search,
                        ), array('in_parent'), 0);

                        if(count($pending_in_requirements) > 0){

                            //Load requirement names:
                            $en_all_4592 = $this->config->item('en_all_4592');
                            $next_step_quick_replies = array();
                            $next_step_message = 'I can append your '.$en_all_4592[$in_requirements_search]['m_name'].' message to '.( count($pending_in_requirements) > 1 ? ' one of' : '' ).' the following:';

                            //Append all options:
                            foreach($pending_in_requirements as $count => $requirement_in_ln){
                                $next_step_message .= "\n\n" . ($count+1) .'. '.echo_in_outcome($requirement_in_ln['in_outcome'] , true);
                                array_push($next_step_quick_replies, array(
                                    'content_type' => 'text',
                                    'title' => ($count+1),
                                    'payload' => 'APPENDRESPONSE_' . $new_message['ln_id'] . '_' . $requirement_in_ln['ln_id'],
                                ));
                            }

                            //Give option to cancel:
                            array_push($next_step_quick_replies, array(
                                'content_type' => 'text',
                                'title' => 'Cancel',
                                'payload' => 'APPENDRESPONSE_CANCEL',
                            ));

                            //We did find a pending submission requirement, confirm with student:
                            $this->Communication_model->dispatch_message(
                                $next_step_message,
                                $en,
                                true,
                                $next_step_quick_replies
                            );

                        } elseif($ln_data['ln_type_entity_id']==4547){

                            //Digest text message & try to make sense of it:
                            $this->Communication_model->digest_text_message($en, $im['message']['text']);

                        } else {
                            //Let them know that we did not understand them:
                            $this->Communication_model->dispatch_message(
                                echo_random_message('one_way_only'),
                                $en,
                                true,
                                array(
                                    array(
                                        'content_type' => 'text',
                                        'title' => 'Next',
                                        'payload' => 'GONEXT',
                                    )
                                )
                            );
                        }
                    }


                } elseif (isset($im['referral']) || isset($im['postback'])) {

                    /*
                     * Simple difference:
                     *
                     * Handle the messaging_postbacks event for new conversations
                     * Handle the messaging_referrals event for existing conversations
                     *
                     * */

                    //Messenger Referral OR Postback
                    $ln_type_entity_id = (isset($im['delivery']) ? 4267 /* Messenger Referral */ : 4268 /* Messenger Postback */);

                    //Authenticate Student:
                    $en = $this->Entities_model->en_authenticate_psid($im['sender']['id']);

                    //Extract more insights:
                    if (isset($im['postback'])) {

                        //The payload field passed is defined in the above places.
                        $payload = $im['postback']['payload']; //Maybe do something with this later?

                        if (isset($im['postback']['referral']) && count($im['postback']['referral']) > 0) {

                            $array_ref = $im['postback']['referral'];

                        } elseif ($payload == 'GET_STARTED') {

                            //The very first payload, set to null:
                            $array_ref = null;

                        } else {

                            //Postback without referral, again set to null:
                            $array_ref = null;

                        }

                    } elseif (isset($im['referral'])) {

                        $array_ref = $im['referral'];

                    }

                    //Did we have a ref from Messenger?
                    $quick_reply_payload = ($array_ref && isset($array_ref['ref']) && strlen($array_ref['ref']) > 0 ? $array_ref['ref'] : null);

                    //Log primary link:
                    $this->Links_model->ln_create(array(
                        'ln_type_entity_id' => $ln_type_entity_id,
                        'ln_metadata' => $ln_metadata,
                        'ln_content' => $quick_reply_payload,
                        'ln_miner_entity_id' => $en['en_id'],
                    ));

                    //Digest quick reply Payload if any:
                    if ($quick_reply_payload) {
                        $quick_reply_results = $this->Communication_model->digest_quick_reply($en, $quick_reply_payload);
                        if(!$quick_reply_results['status']){
                            //There was an error, inform admin:
                            $this->Links_model->ln_create(array(
                                'ln_content' => 'digest_quick_reply() for postback/referral returned error ['.$quick_reply_results['message'].']',
                                'ln_metadata' => $ln_metadata,
                                'ln_type_entity_id' => 4246, //Platform Error
                                'ln_miner_entity_id' => 1, //Shervin/Developer
                                'ln_child_entity_id' => $en['en_id'],
                            ));

                        }
                    }

                    /*
                     *
                     * We are currently not using any of the following information...
                     *
                    if($quick_reply_payload){
                        //We have referrer data, see what this is all about!
                        //We expect an integer which is the challenge ID
                        $ref_source = $array_ref['source'];
                        $ref_type = $array_ref['type'];
                        $ad_id = ( isset($array_ref['ad_id']) ? $array_ref['ad_id'] : null ); //Only IF user comes from the Ad

                        //Optional actions that may need to be taken on SOURCE:
                        if(strtoupper($ref_source)=='ADS' && $ad_id){
                            //Ad clicks
                        } elseif(strtoupper($ref_source)=='SHORTLINK'){
                            //Came from m.me short link click
                        } elseif(strtoupper($ref_source)=='MESSENGER_CODE'){
                            //Came from m.me short link click
                        } elseif(strtoupper($ref_source)=='DISCOVER_TAB'){
                            //Came from m.me short link click
                        }
                    }
                    */

                } elseif (isset($im['optin'])) {

                    $en = $this->Entities_model->en_authenticate_psid($im['sender']['id']);

                    //Log link:
                    $this->Links_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_entity_id' => 4266, //Messenger Optin
                        'ln_miner_entity_id' => $en['en_id'],
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->Entities_model->en_authenticate_psid($im['sender']['id']);

                    //Log link:
                    $this->Links_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_entity_id' => 4577, //Message Request Accepted
                        'ln_miner_entity_id' => $en['en_id'],
                    ));

                } else {

                    //This should really not happen!
                    $this->Links_model->ln_create(array(
                        'ln_content' => 'facebook_webhook() received unrecognized webhook call',
                        'ln_metadata' => $ln_metadata,
                        'ln_type_entity_id' => 4246, //Platform Error
                        'ln_miner_entity_id' => 1, //Shervin/Developer
                    ));

                }
            }
        }

        return print_r('complete');
    }

    function api_fetch_profile($en_id)
    {

        //Only moderators can do this at this time:
        $session_en = en_auth(array(1281));
        $current_us = $this->Entities_model->en_fetch(array(
            'en_id' => $en_id,
        ));

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Sign In as a moderator and Try again.',
            ));
        } elseif (count($current_us) == 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'User not found!',
            ));
        } elseif (strlen($current_us[0]['en_psid']) < 10) {
            return echo_json(array(
                'status' => 0,
                'message' => 'User does not seem to be connected to Mench, so profile data cannot be fetched',
            ));
        } else {

            //Fetch results and show:
            return echo_json(array(
                'fb_profile' => $this->Communication_model->facebook_graph('GET', '/' . $current_us[0]['en_psid'], array()),
                'en' => $current_us[0],
            ));

        }

    }

    function api_sync_menu()
    {

        /*
         * A function that will sync the fixed
         * menu of Mench's Facebook Messenger.
         *
         * */

        //Let's first give permission to our pages to do so:
        $res = array();
        array_push($res, $this->Communication_model->facebook_graph('POST', '/me/messenger_profile', array(
            'get_started' => array(
                'payload' => 'GET_STARTED',
            ),
            'whitelisted_domains' => array(
                'http://local.mench.co',
                'https://mench.co',
                'https://mench.com',
            ),
        )));

        //Wait until Facebook pro-pagates changes of our whitelisted_domains setting:
        sleep(2);

        $en_all_2738 = $this->config->item('en_all_2738');

        //Now let's update the menu:
        array_push($res, $this->Communication_model->facebook_graph('POST', '/me/messenger_profile', array(
            'persistent_menu' => array(
                array(
                    'locale' => 'default',
                    'composer_input_disabled' => false,
                    'disabled_surfaces' => array('CUSTOMER_CHAT_PLUGIN'),
                    'call_to_actions' => array(
                        array(
                            'title' => $en_all_2738[6138]['m_icon'].' '.$en_all_2738[6138]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/messenger/actionplan',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => $en_all_2738[6137]['m_icon'].' '.$en_all_2738[6137]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/messenger/myaccount',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                    ),
                ),
            ),
        )));

        //Show results:
        echo_json($res);
    }


    function myaccount()
    {
        /*
         *
         * Loads student my account "frame" which would
         * then use JS/Facebook API to determine Student
         * PSID which then loads their Account via
         * myaccount_load() function below.
         *
         * */

        $this->load->view('view_shared/messenger_header', array(
            'title' => '👤 My Account',
        ));
        $this->load->view('view_messenger/myaccount_frame');
        $this->load->view('view_shared/messenger_footer');
    }

    function myaccount_load($psid)
    {

        /*
         *
         * My Account Web UI used for both Messenger
         * Webview and web-browser login
         *
         * */

        //Authenticate user:
        $session_en = $this->session->userdata('user');
        if (!$psid && !isset($session_en['en_id'])) {
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif (!is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Failed to authenticate your origin.</div>');
        } elseif (!isset($session_en['en_id'])) {
            //Messenger Webview, authenticate PSID:
            $session_en = $this->Entities_model->en_authenticate_psid($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }

        //Log My Account View:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4282, //Opened My Account
            'ln_miner_entity_id' => $session_en['en_id'],
        ));

        //Load UI:
        $this->load->view('view_messenger/myaccount_ui', array(
            'session_en' => $session_en,
        ));

    }

    function myaccount_save_full_name()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_name']) || strlen($_POST['en_name']) < 2) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Name must be at-least 2 characters long',
            ));
        }

        //Cleanup:
        $_POST['en_name'] = trim($_POST['en_name']);

        //Check to make sure not duplicate:
        $duplicates = $this->Entities_model->en_fetch(array(
            'en_id !=' => $_POST['en_id'],
            'en_status >=' => 0, //New+
            'LOWER(en_name)' => strtolower($_POST['en_name']),
        ));
        if (count($duplicates) > 0) {
            //This is a duplicate, disallow:
            return echo_json(array(
                'status' => 0,
                'message' => 'Name already in-use. Add a post-fix to make it unique.',
            ));
        }


        //Update name and notify
        $this->Entities_model->en_update($_POST['en_id'], array(
            'en_name' => $_POST['en_name'],
        ), true, $_POST['en_id']);


        //Log Account iteration link type:
        $_POST['account_update_function'] = 'myaccount_save_full_name'; //Add this variable to indicate which My Account function created this link
        $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 6224, //My Account Iterated
            'ln_content' => 'My Account Name Updated:'.$_POST['en_name'],
            'ln_metadata' => $_POST,
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        return echo_json(array(
            'status' => 1,
            'message' => 'Name updated',
        ));
    }


    function myaccount_save_phone(){

        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_phone'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing phone number',
            ));
        } elseif (strlen($_POST['en_phone'])>0 && !is_numeric($_POST['en_phone'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid phone number: numbers only',
            ));
        } elseif (strlen($_POST['en_phone'])>0 && (strlen($_POST['en_phone'])<7 || strlen($_POST['en_phone'])>12)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Phone number must be between 7-12 characters long',
            ));
        }

        if (strlen($_POST['en_phone']) > 0) {

            //Cleanup starting 1:
            if (strlen($_POST['en_phone']) == 11) {
                $_POST['en_phone'] = preg_replace("/^1/", '',$_POST['en_phone']);
            }

            //Check to make sure not duplicate:
            $duplicates = $this->Links_model->ln_fetch(array(
                'ln_status >=' => 0, //New+
                'ln_type_entity_id' => 4319, //Phone are of type number
                'ln_parent_entity_id' => 4783, //Phone Number
                'ln_child_entity_id !=' => $_POST['en_id'],
                'ln_content' => $_POST['en_phone'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Phone already in-use. Use another number or contact support for assistance.',
                ));
            }
        }


        //Fetch existing phone:
        $student_phones = $this->Links_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4319, //Phone are of type number
            'ln_parent_entity_id' => 4783, //Phone Number
        ));
        if (count($student_phones) > 0) {

            if (strlen($_POST['en_phone']) == 0) {

                //Remove:
                $this->Links_model->ln_update($student_phones[0]['ln_id'], array(
                    'ln_status' => -1, //Removed
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone Removed',
                );

            } elseif ($student_phones[0]['ln_content'] != $_POST['en_phone']) {

                //Update if not duplicate:
                $this->Links_model->ln_update($student_phones[0]['ln_id'], array(
                    'ln_content' => $_POST['en_phone'],
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone Updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Phone Unchanged',
                );

            }

        } elseif (strlen($_POST['en_phone']) > 0) {

            //Create new link:
            $this->Links_model->ln_create(array(
                'ln_status' => 2, //Published
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 4319, //Phone are of type number
                'ln_parent_entity_id' => 4783, //Phone Number
                'ln_content' => $_POST['en_phone'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Phone Added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Phone Unchanged',
            );

        }


        //Log Account iteration link type:
        if($return['status']){
            $_POST['account_update_function'] = 'myaccount_save_phone'; //Add this variable to indicate which My Account function created this link
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message']. ( strlen($_POST['en_phone']) > 0 ? ': '.$_POST['en_phone'] : ''),
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }

        return echo_json($return);

    }

    function myaccount_save_email()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_email']) || (strlen($_POST['en_email']) > 0 && !filter_var($_POST['en_email'], FILTER_VALIDATE_EMAIL))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid email address',
            ));
        }


        if (strlen($_POST['en_email']) > 0) {
            //Cleanup:
            $_POST['en_email'] = trim(strtolower($_POST['en_email']));

            //Check to make sure not duplicate:
            $duplicates = $this->Links_model->ln_fetch(array(
                'ln_status >=' => 0, //New+
                'ln_type_entity_id' => 4255, //Emails are of type Text
                'ln_parent_entity_id' => 3288, //Email Address
                'ln_child_entity_id !=' => $_POST['en_id'],
                'LOWER(ln_content)' => $_POST['en_email'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Email already in-use. Use another email or contact support for assistance.',
                ));
            }
        }


        //Fetch existing email:
        $student_emails = $this->Links_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4255, //Emails are of type Text
            'ln_parent_entity_id' => 3288, //Email Address
        ));
        if (count($student_emails) > 0) {

            if (strlen($_POST['en_email']) == 0) {

                //Remove email:
                $this->Links_model->ln_update($student_emails[0]['ln_id'], array(
                    'ln_status' => -1, //Removed
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Email removed',
                );

            } elseif ($student_emails[0]['ln_content'] != $_POST['en_email']) {

                //Update if not duplicate:
                $this->Links_model->ln_update($student_emails[0]['ln_id'], array(
                    'ln_content' => $_POST['en_email'],
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Email updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Email unchanged',
                );

            }

        } elseif (strlen($_POST['en_email']) > 0) {

            //Create new link:
            $this->Links_model->ln_create(array(
                'ln_status' => 2, //Published
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 4255, //Emails are of type Text
                'ln_parent_entity_id' => 3288, //Email Address
                'ln_content' => $_POST['en_email'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Email added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Email unchanged',
            );

        }


        if($return['status']){
            //Log Account iteration link type:
            $_POST['account_update_function'] = 'myaccount_save_email'; //Add this variable to indicate which My Account function created this link
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message']. ( strlen($_POST['en_email']) > 0 ? ': '.$_POST['en_email'] : ''),
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }


        //Return results:
        return echo_json($return);


    }


    function myaccount_save_password()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_password']) || strlen($_POST['en_password']) < 4) {
            return echo_json(array(
                'status' => 0,
                'message' => 'New password must be 4 characters or more',
            ));
        }


        //Fetch existing password:
        $student_passwords = $this->Links_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'ln_type_entity_id' => 4255, //Passwords are of type Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['en_password']));


        if (count($student_passwords) > 0) {

            if ($hashed_password == $student_passwords[0]['ln_content']) {

                $return = array(
                    'status' => 0,
                    'message' => 'Password Unchanged',
                );

            } else {

                //Update password:
                $this->Links_model->ln_update($student_passwords[0]['ln_id'], array(
                    'ln_content' => $hashed_password,
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Password Updated',
                );

            }

        } else {

            //Create new link:
            $this->Links_model->ln_create(array(
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4255, //Passwords are of type Text
                'ln_parent_entity_id' => 3286, //Password
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_content' => $hashed_password,
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Password Added',
            );

        }


        //Log Account iteration link type:
        if($return['status']){
            $_POST['account_update_function'] = 'myaccount_save_password'; //Add this variable to indicate which My Account function created this link
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message'],
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }


        //Return results:
        return echo_json($return);

    }


    function myaccount_save_social_profiles()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['social_profiles']) || !is_array($_POST['social_profiles']) || count($_POST['social_profiles']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing social profiles',
            ));
        }

        $en_all_6123 = $this->config->item('en_all_6123');

        //Loop through and validate social profiles:
        $success_messages = '';
        foreach ($_POST['social_profiles'] as $social_profile) {


            //Validate to make sure either nothing OR URL:
            $social_en_id = intval($social_profile[0]);
            $social_url = trim($social_profile[1]);
            $profile_set = ( strlen($social_url) > 0 ? true : false );


            //This profile already added for this user, are we updating or removing?
            if ($profile_set) {

                //Valiodate URL and make sure it matches:
                $is_valid_url = false;
                if (filter_var($social_url, FILTER_VALIDATE_URL)) {
                    //Check to see if it's from the same domain and not in use:
                    $domain_entity = $this->Entities_model->en_sync_domain($social_url);
                    if ($domain_entity['domain_already_existed'] && isset($domain_entity['en_domain']['en_id']) && $domain_entity['en_domain']['en_id'] == $social_en_id) {
                        //Seems to be a valid domain for this social profile:
                        $is_valid_url = true;
                    }
                }

                if (!$is_valid_url) {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Invalid ' . $en_all_6123[$social_en_id]['m_name'] . ' URL',
                    ));
                }
            }


            //Does this user have a social URL already?
            $social_url_exists = $this->Links_model->ln_fetch(array(
                'ln_status' => 2, //Published
                'ln_type_entity_id' => 4256, //Generic URL
                'ln_parent_entity_id' => $social_en_id,
                'ln_child_entity_id' => $_POST['en_id'],
            ));

            if (count($social_url_exists) > 0) {

                //Make sure not for another entity:
                if ($social_url_exists[0]['ln_child_entity_id'] != $_POST['en_id']) {
                    return echo_json(array(
                        'status' => 0,
                        'message' => $en_all_6123[$social_en_id]['m_name'] . ' URL already taken by another entity.',
                    ));
                }

                //This profile already added for this user, are we updating or removing?
                if ($profile_set && $social_url_exists[0]['ln_content'] != $social_url) {

                    //Check to make sure not duplicate
                    $duplicates = $this->Links_model->ln_fetch(array(
                        'ln_status' => 2, //Published
                        'ln_type_entity_id' => 4256, //Generic URL
                        'ln_parent_entity_id' => $social_en_id,
                        'ln_child_entity_id !=' => $_POST['en_id'],
                        'ln_content' => $social_url,
                    ));
                    if(count($duplicates) > 0){
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'Duplicates',
                        ));
                    }

                    //Update profile since different:
                    $this->Links_model->ln_update($social_url_exists[0]['ln_id'], array(
                        'ln_content' => $social_url,
                    ), $_POST['en_id']);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Updated. ';

                } elseif(!$profile_set) {

                    //Remove profile:
                    $this->Links_model->ln_update($social_url_exists[0]['ln_id'], array(
                        'ln_status' => -1, //Removed
                    ), $_POST['en_id']);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Removed. ';

                } else {



                }

            } elseif ($profile_set) {

                //Create new link:
                $this->Links_model->ln_create(array(
                    'ln_status' => 2, //Published
                    'ln_miner_entity_id' => $_POST['en_id'],
                    'ln_child_entity_id' => $_POST['en_id'],
                    'ln_type_entity_id' => 4256, //Generic URL
                    'ln_parent_entity_id' => $social_en_id,
                    'ln_content' => $social_url,
                ), true);

                $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Added. ';

            }
        }

        if(strlen($success_messages) > 0){

            //Log Account iteration link type:
            $_POST['account_update_function'] = 'myaccount_save_social_profiles'; //Add this variable to indicate which My Account function created this link
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$success_messages,
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));

            //All good, return combined success messages:
            return echo_json(array(
                'status' => 1,
                'message' => $success_messages,
            ));

        } else {

            //All good, return combined success messages:
            return echo_json(array(
                'status' => 0,
                'message' => 'Social Profiles Unchanged',
            ));

        }



    }

    function actionplan_add(){

        /*
         *
         * The Ajax function to add an intention to the Action Plan from the landing page.
         *
         * */

        //Validate input:
        $session_en = en_auth();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            ));
        }

        //Attempt to add intent to Action Plan:
        if($this->Actionplan_model->actionplan_add($session_en['en_id'], $_POST['in_id'])){
            //All good:
            $en_all_2738 = $this->config->item('en_all_2738');
            return echo_json(array(
                'status' => 1,
                'message' => '<i class="far fa-check-circle"></i> Successfully added to your <b><a href="/messenger/actionplan">'.$en_all_2738[6138]['m_icon'].' '.$en_all_2738[6138]['m_name'].'</a></b>',
            ));
        } else {
            //There was some error:
            return echo_json(array(
                'status' => 0,
                'message' => 'Unable to add to Action Plan',
            ));
        }

    }

    function actionplan($in_id = 0)
    {

        /*
         *
         * Loads student action plans "frame" which would
         * then use JS/Facebook API to determine Student
         * PSID which then loads the Action Plan via
         * actionplan_load() function below.
         *
         * */

        $this->load->view('view_shared/messenger_header', array(
            'title' => '🚩 Action Plan',
        ));
        $this->load->view('view_messenger/actionplan_frame', array(
            'in_id' => $in_id,
        ));
        $this->load->view('view_shared/messenger_footer');

    }

    function actionplan_clear_all($en_id, $timestamp, $secret_key){
        if($secret_key != md5($en_id . $this->config->item('actionplan_salt') . $timestamp)){
            die('Invalid Secret Key');
        }

        $this->db->query("DELETE from table_links WHERE ln_miner_entity_id=".$en_id." AND ln_type_entity_id IN (" . join(',', array_merge($this->config->item('en_ids_6146'), $this->config->item('en_ids_6150'))) . ")");
        $affected_rows = $this->db->affected_rows();
        echo 'Removed '.$affected_rows.' Action Plan links.';

    }

    function actionplan_load($psid, $in_id)
    {

        /*
         *
         * Action Plan Web UI used for both Messenger
         * Webview and web-browser login
         *
         * */

        //Authenticate user:
        $session_en = $this->session->userdata('user');
        if (!$psid && !isset($session_en['en_id'])) {
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif (!is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Failed to authenticate your origin.</div>');
        } elseif (!isset($session_en['en_id'])) {
            //Messenger Webview, authenticate PSID:
            $session_en = $this->Entities_model->en_authenticate_psid($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }


        //This is a special command to find the next intent:
        if($in_id=='next'){
            //Find the next item to navigate them to:
            $next_in_id = $this->Actionplan_model->actionplan_find_next_step($session_en['en_id'], false);
            $in_id = ( $next_in_id > 0 ? $next_in_id : 0 );
        }


        //Fetch student's intentions as we'd need to know their top-level goals:
        $student_intents = $this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
            'in_status' => 2, //Published
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

        //Show appropriate UI:
        if ($in_id < 1) {

            //Log Action Plan View:
            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 4283, //Opened Action Plan
                'ln_miner_entity_id' => $session_en['en_id'],
            ));

            //List all student intentions:
            $this->load->view('view_messenger/actionplan_all', array(
                'session_en' => $session_en,
                'student_intents' => $student_intents,
            ));

        } else {

            //Fetch/validate selected intent:
            $ins = $this->Intents_model->in_fetch(array(
                'in_id' => $in_id,
            ));

            if (count($ins) < 1) {
                die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
            } elseif ($ins[0]['in_status'] != 2) {
                die('<div class="alert alert-danger" role="alert">Intent is not yet published.</div>');
            }

            //Load Action Plan UI with relevant variables:
            $this->load->view('view_messenger/actionplan_intent', array(
                'session_en' => $session_en,
                'student_intents' => $student_intents,
                'advance_step' => $this->Actionplan_model->actionplan_advance_step($session_en, $in_id, false),
                'in' => $ins[0], //Currently focused intention:
            ));

        }
    }


    function actionplan_stop_save(){

        /*
         *
         * When students indicate they want to stop
         * an intention this function saves the changes
         * necessary and remove the intention from their
         * Action Plan.
         *
         * */


        if (!isset($_POST['en_miner_id']) || intval($_POST['en_miner_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid miner ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing intent ID',
            ));
        } elseif (!isset($_POST['stop_method_id']) || intval($_POST['stop_method_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing stop method',
            ));
        } elseif (!isset($_POST['stop_feedback'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing feedback input',
            ));
        }

        //Validate intention to be removed:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid intention',
            ));
        }

        //Go ahead and remove from Action Plan:
        $student_intents = $this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
            'ln_parent_intent_id' => $_POST['in_id'],
        ));
        if(count($student_intents) < 1){
            //Give error:
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not locate Action Plan',
            ));
        }

        //Adjust Action Plan status:
        foreach($student_intents as $ln){
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_status' => ( $_POST['stop_method_id']==6154 ? 2 : -1 ), //This is a nasty HACK!
            ), $_POST['en_miner_id']);
        }

        //Log related link:
        $this->Links_model->ln_create(array(
            'ln_content' => $_POST['stop_feedback'],
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_type_entity_id' => $_POST['stop_method_id'],
            'ln_parent_intent_id' => $_POST['in_id'],
        ));

        //Communicate with student:
        $this->Communication_model->dispatch_message(
            'I have successfully removed the intention to '.$ins[0]['in_outcome'].' from your Action Plan. You can add it back to your Action Plan at any time and continue from where you left off.',
            array('en_id' => $_POST['en_miner_id']),
            true,
            array(
                array(
                    'content_type' => 'text',
                    'title' => 'Next',
                    'payload' => 'GONEXT',
                )
            )
        );

        return echo_json(array(
            'status' => 1,
        ));

    }


    function actionplan_skip_preview($en_id, $in_id)
    {

        //Just give them an overview of what they are about to skip:
        return echo_json(array(
            'skip_step_preview' => 'WARNING: '.$this->Actionplan_model->actionplan_skip_initiate(array('en_id' => $en_id), $in_id, false).' Are you sure you want to skip?',
        ));

    }

    function actionplan_skip_apply($en_id, $in_id)
    {

        //Actually go ahead and skip
        $this->Actionplan_model->actionplan_skip_recursive_down($en_id, $in_id);
        //Assume its all good!

        //We actually skipped, draft message:
        $message = '<div class="alert alert-success" role="alert">I successfully skipped all steps.</div>';

        //Find the next item to navigate them to:
        $next_in_id = $this->Actionplan_model->actionplan_find_next_step($en_id, false);
        if ($next_in_id > 0) {
            return redirect_message('/messenger/actionplan/' . $next_in_id, $message);
        } else {
            return redirect_message('/messenger/actionplan', $message);
        }

    }

    function myaccount_radio_update()
    {
        /*
         *
         * Saves the radio selection of some account fields
         * that are displayed using echo_radio_entities()
         *
         * */

        if (!isset($_POST['en_miner_id']) || intval($_POST['en_miner_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid miner ID',
            ));
        } elseif (!isset($_POST['parent_en_id']) || intval($_POST['parent_en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing parent entity',
            ));
        } elseif (!isset($_POST['selected_en_id']) || intval($_POST['selected_en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing selected entity',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_already_selected'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }

        //Used when the user subscribed back to us:
        $greet_them_back = false;

        if(!$_POST['enable_mulitiselect'] || $_POST['was_already_selected']){
            //Since this is not a multi-select we want to remove all existing options...

            //Fetch all possible answers based on parent entity:
            $filters = array(
                'ln_parent_entity_id' => $_POST['parent_en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status' => 2, //Published
                'en_status' => 2, //Published
            );

            if($_POST['enable_mulitiselect'] && $_POST['was_already_selected']){
                //Just remove this single item, not the other ones:
                $filters['ln_child_entity_id'] = $_POST['selected_en_id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->Links_model->ln_fetch($filters, array('en_child'), 0, 0) as $answer_en){
                array_push($possible_answers, $answer_en['en_id']);
            }

            //Remove selected options for this miner:
            foreach($this->Links_model->ln_fetch(array(
                'ln_parent_entity_id IN (' . join(',', $possible_answers) . ')' => null,
                'ln_child_entity_id' => $_POST['en_miner_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status' => 2, //Published
            )) as $remove_en){

                //Does this have to do with changing Subscription Type? We need to confirm with them if so:
                if($_POST['parent_en_id']==4454){
                    if($_POST['selected_en_id']==4455){
                        //They just unsubscribed, send them a message before its too late (changing their status):
                        $this->Communication_model->dispatch_message(
                            'This is a confirmation that you are now unsubscribed from Mench and I will not longer send you any messages. You can resume your subscription later by going to MY ACCOUNT > SUBSCRIPTION TYPE > Set Notification',
                            array('en_id' => $_POST['en_miner_id']),
                            true
                        );
                    } elseif($remove_en['ln_parent_entity_id']==4455){
                        //They used to be ub-subscribed, now they join back, confirm with them AFTER we update their settings:
                        $greet_them_back = true;
                    }
                }

                //Should usually remove a single option:
                $this->Links_model->ln_update($remove_en['ln_id'], array(
                    'ln_status' => -1, //Removed
                ), $_POST['en_miner_id']);
            }

        }

        //Add new option if not already there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_already_selected']){
            $this->Links_model->ln_create(array(
                'ln_parent_entity_id' => $_POST['selected_en_id'],
                'ln_child_entity_id' => $_POST['en_miner_id'],
                'ln_miner_entity_id' => $_POST['en_miner_id'],
                'ln_type_entity_id' => 4230, //Raw
                'ln_status' => 2, //Published
            ));
        }

        if($greet_them_back){
            //Now we can communicate with them again:
            $this->Communication_model->dispatch_message(
                'Welcome back 🎉🎉🎉 This is a confirmation that you are now re-subscribed and I will continue to work with you on your Acion Plan intentions',
                array('en_id' => $_POST['en_miner_id']),
                true
            );
        }


        //Log Account iteration link type:
        $_POST['account_update_function'] = 'myaccount_radio_update'; //Add this variable to indicate which My Account function created this link
        $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_type_entity_id' => 6224, //My Account Iterated
            'ln_content' => 'My Account '.( $_POST['enable_mulitiselect'] ? 'Multi' : 'Single' ).'-Select Radio Field '.( $_POST['was_already_selected'] ? 'Removed' : 'Added' ),
            'ln_metadata' => $_POST,
            'ln_parent_entity_id' => $_POST['parent_en_id'],
            'ln_child_entity_id' => $_POST['selected_en_id'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => 'Updated', //Note: NOT shown in UI
        ));
    }

    function actionplan_sort_save()
    {
        /*
         *
         * Saves the order of Action Plan intents based on
         * student preferences.
         *
         * */

        if (!isset($_POST['en_miner_id']) || intval($_POST['en_miner_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid miner ID',
            ));
        } elseif (!isset($_POST['new_actionplan_order']) || !is_array($_POST['new_actionplan_order']) || count($_POST['new_actionplan_order']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing sorting intents',
            ));
        }


        //Update the order of their Action Plan:
        $results = array();
        foreach($_POST['new_actionplan_order'] as $ln_order => $ln_id){
            if(intval($ln_id) > 0 && intval($ln_order) > 0){
                //Update order of this link:
                $results[$ln_order] = $this->Links_model->ln_update(intval($ln_id), array(
                    'ln_order' => $ln_order,
                ), $_POST['en_miner_id']);
            }
        }


        //Save sorting results:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 6132, //Action Plan Sorted
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_metadata' => array(
                'new_order' => $_POST['new_actionplan_order'],
                'results' => $results,
            ),
        ));


        //Fetch top intention that being workined on now:
        $top_priority = $this->Actionplan_model->actionplan_top_priority($_POST['en_miner_id']);
        if($top_priority){
            //Communicate top-priority with student:
            $this->Communication_model->dispatch_message(
                'I have successfully sorted your Action Plan priorities. Your top active priority is to '.$top_priority['in']['in_outcome'].' which you have made '.$top_priority['completion_rate']['completion_percentage'].'% progress so far.',
                array('en_id' => $_POST['en_miner_id']),
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'GONEXT',
                    )
                )
            );
        }


        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => count($_POST['new_actionplan_order']).' Intents Sorted',
        ));
    }


    function actionplan_answer_question($en_id, $parent_in_id, $answer_in_id, $w_key)
    {

        if ($w_key != md5($this->config->item('actionplan_salt') . $answer_in_id . $parent_in_id . $en_id)) {
            return redirect_message('/messenger/actionplan/' . $parent_in_id, '<div class="alert alert-danger" role="alert">Invalid Authentication Key</div>');
        }

        //Validate Answer Intent:
        $answer_ins = $this->Intents_model->in_fetch(array(
            'in_id' => $answer_in_id,
            'in_status' => 2, //Published
        ));
        if (count($answer_ins) < 1) {
            return redirect_message('/messenger/actionplan/' . $parent_in_id, '<div class="alert alert-danger" role="alert">Invalid Answer</div>');
        }

        //Fetch current progression links, if any:
        $current_progression_links = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
            'ln_miner_entity_id' => $en_id,
            'ln_parent_intent_id' => $parent_in_id,
            'ln_status >=' => 0, //New+
        ));

        //All good, save chosen OR path
        $new_progression_link = $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => 6157, //Action Plan Question Answered
            'ln_parent_intent_id' => $parent_in_id,
            'ln_child_intent_id' => $answer_in_id,
            'ln_status' => 2, //Published since they just answered
        ));

        //See if we also need to mark the child as complete:
        $this->Actionplan_model->actionplan_complete_if_empty($en_id, $answer_ins[0]);

        //Archive current progression links:
        foreach($current_progression_links as $ln){
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_parent_link_id' => $new_progression_link['ln_id'],
                'ln_status' => -1,
            ), $en_id);
        }

        return redirect_message('/messenger/actionplan/' . $answer_in_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');

    }


    function cron__sync_attachments()
    {

        /*
         *
         * Messenger has a feature that allows us to cache
         * media files in their servers so we can deliver
         * them instantly without a need to re-upload them
         * every time we want to send them to a student.
         *
         */

        $fb_convert_4537 = $this->config->item('fb_convert_4537'); //Supported Media Types
        $success_count = 0; //Track success
        $ln_metadata = array();


        //Let's fetch all Media files without a Facebook attachment ID:
        $ln_pending = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', array_keys($fb_convert_4537)) . ')' => null,
            'ln_status' => 2, //Published
            'ln_metadata' => null, //Missing Facebook Attachment ID [NOTE: Must make sure ln_metadata is not used for anything else for these link types]
        ), array(), 10, 0, array('ln_id' => 'ASC')); //Sort by oldest added first


        //Put something in the ln_metadata so other cron jobs do not pick  up on it:
        foreach ($ln_pending as $ln) {
            update_metadata('ln', $ln['ln_id'], array(
                'fb_att_id' => 0,
            ));
        }

        foreach ($ln_pending as $ln) {

            //To be set to true soon (hopefully):
            $db_result = false;

            //Payload to save attachment:
            $payload = array(
                'message' => array(
                    'attachment' => array(
                        'type' => $fb_convert_4537[$ln['ln_type_entity_id']],
                        'payload' => array(
                            'is_reusable' => true,
                            'url' => $ln['ln_content'], //The URL to the media file
                        ),
                    ),
                )
            );

            //Attempt to sync Media to Facebook:
            $result = $this->Communication_model->facebook_graph('POST', '/me/message_attachments', $payload);

            if (isset($result['ln_metadata']['result']['attachment_id']) && $result['status']) {

                //Save Facebook Attachment ID to DB:
                $db_result = update_metadata('ln', $ln['ln_id'], array(
                    'fb_att_id' => intval($result['ln_metadata']['result']['attachment_id']),
                ));

            }

            //Did it go well?
            if ($db_result) {

                $success_count++;

            } else {

                //Log error:
                $this->Links_model->ln_create(array(
                    'ln_type_entity_id' => 4246, //Platform Error
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                    'ln_parent_link_id' => $ln['ln_id'],
                    'ln_content' => 'cron__sync_attachments() Failed to sync attachment to Facebook API: ' . (isset($result['ln_metadata']['result']['error']['message']) ? $result['ln_metadata']['result']['error']['message'] : 'Unknown Error'),
                    'ln_metadata' => array(
                        'payload' => $payload,
                        'result' => $result,
                        'ln' => $ln,
                    ),
                ));

            }

            //Save stats:
            array_push($ln_metadata, array(
                'payload' => $payload,
                'fb_result' => $result,
            ));

        }

        //Echo message:
        echo_json(array(
            'status' => ($success_count == count($ln_pending) && $success_count > 0 ? 1 : 0),
            'message' => $success_count . '/' . count($ln_pending) . ' synced using Facebook Attachment API',
            'ln_metadata' => $ln_metadata,
        ));

    }


    function cron__save_chat_media()
    {

        /*
         *
         * Stores these media in Mench CDN:
         *
         * 1) Media received from students
         * 2) Media sent from Mench Admins via Facebook Chat Inbox
         *
         * Note: It would not store media that is sent from intent
         * notes since those are already stored.
         *
         * */

        $ln_pending = $this->Links_model->ln_fetch(array(
            'ln_status' => 0, //New
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6102')) . ')' => null, //Student Sent/Received Media Links
        ), array(), 20);

        //Set link statuses to drafting so other Cron jobs don't pick them up:
        foreach ($ln_pending as $ln) {
            if($ln['ln_status'] == 0){
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_status' => 1, //Drafting
                ));
            }
        }

        $counter = 0;
        foreach ($ln_pending as $ln) {

            //Store to CDN:
            $new_file_url = upload_to_cdn($ln['ln_content'], $ln);

            if ($new_file_url && filter_var($new_file_url, FILTER_VALIDATE_URL)) {

                //Update link:
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_content' => $new_file_url,
                    'ln_status' => 2, //Published
                ));

                //Increase counter:
                $counter++;

            } else {

                //Log error:
                $this->Links_model->ln_create(array(
                    'ln_type_entity_id' => 4246, //Platform Error
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                    'ln_parent_link_id' => $ln['ln_id'],
                    'ln_content' => 'cron__save_chat_media() Failed to save media from Messenger',
                    'ln_metadata' => array(
                        'new_file_url' => $new_file_url,
                        'ln' => $ln,
                    ),
                ));

            }
        }

        //Echo message for cron job:
        echo $counter . ' message media files saved to Mench CDN';

    }

    function cron__save_profile_photo()
    {

        /*
         *
         * Every time we receive a media file from Facebook
         * we need to upload it to our own CDNs using the
         * short-lived URL provided by Facebook so we can
         * access it indefinitely without restriction.
         * This process is managed by creating a @4299
         * Link Type which this cron job grabs and
         * uploads to Mench CDN.
         *
         * Runs every minute with the cron job.
         *
         * */

        $ln_pending = $this->Links_model->ln_fetch(array(
            'ln_status' => 0, //New
            'ln_type_entity_id' => 4299, //Updated Profile Picture
        ), array('en_miner'), 20); //Max number of scans per run


        //Set link statuses to drafting so other Cron jobs don't pick them up:
        foreach ($ln_pending as $ln) {
            if($ln['ln_status'] == 0){
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_status' => 1, //Drafting
                ));
            }
        }

        //Now go through and upload to CDN:
        foreach ($ln_pending as $ln) {

            //Save photo to S3 if content is URL
            $new_file_url = (filter_var($ln['ln_content'], FILTER_VALIDATE_URL) ? upload_to_cdn($ln['ln_content'], $ln) : false);

            if (!$new_file_url) {

                //Ooopsi, there was an error:
                $this->Links_model->ln_create(array(
                    'ln_content' => 'cron__save_profile_photo() failed to store file in CDN',
                    'ln_type_entity_id' => 4246, //Platform Error
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                    'ln_parent_link_id' => $ln['ln_id'],
                ));

                continue;
            }

            //Update entity icon only if not already set:
            $ln_child_entity_id = 0;
            if (strlen($ln['en_icon']) < 1) {

                //Update Cover ID:
                $this->Entities_model->en_update($ln['en_id'], array(
                    'en_icon' => '<img src="' . $new_file_url . '">',
                ), true, $ln['en_id']);

                //Link link to entity:
                $ln_child_entity_id = $ln['en_id'];

            }

            //Update link:
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_status' => 2, //Published
                'ln_content' => null, //Remove URL from content to indicate its done
                'ln_child_entity_id' => $ln_child_entity_id,
                'ln_metadata' => array(
                    'original_url' => $ln['ln_content'],
                    'cdn_url' => $new_file_url,
                ),
            ));

        }

        echo_json($ln_pending);
    }

}