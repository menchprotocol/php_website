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

    function test($in_id){
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ));

        echo_json(array(
            'next' => $this->Actionplan_model->actionplan_step_next_find(1, $ins[0]),
            'completion' => $this->Actionplan_model->actionplan_completion_progress(1, $ins[0]),
            'marks' => $this->Actionplan_model->actionplan_completion_marks(1, $ins[0]),
            'recursive_parents' => $this->Intents_model->in_fetch_recursive_public_parents($ins[0]['in_id']),
            'common_base' => $this->Intents_model->in_metadata_common_base($ins[0]),

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
            return print_r('missing');
        } elseif ($ln_metadata['object'] != 'page') {
            $this->Links_model->ln_create(array(
                'ln_content' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'ln_metadata' => $ln_metadata,
                'ln_type_entity_id' => 4246, //Platform Bug Reports
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return print_r('unknown page');
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
                    'ln_type_entity_id' => 4246, //Platform Bug Reports
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                ));
                continue;
            }

            //loop though the messages:
            foreach ($entry['messaging'] as $im) {

                if (isset($im['read']) || isset($im['delivery'])) {

                    //Message read OR delivered
                    $ln_type_entity_id = (isset($im['delivery']) ? 4279 /* Message Delivered */ : 4278 /* Message Read */);

                    //Authenticate User:
                    $en = $this->Entities_model->en_psid_check($im['sender']['id']);

                    //Log Link Only IF last delivery link was 3+ minutes ago (Since Facebook sends many of these):
                    $last_trs_logged = $this->Links_model->ln_fetch(array(
                        'ln_type_entity_id' => $ln_type_entity_id,
                        'ln_miner_entity_id' => $en['en_id'],
                        'ln_timestamp >=' => date("Y-m-d H:i:s", (time() - (60))), //Links logged less than 1 minutes ago
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
                        return print_r('already logged');

                    }


                    //Set variables:
                    $sent_by_mench = (isset($im['message']['is_echo'])); //Indicates the message sent from the page itself
                    $en = $this->Entities_model->en_psid_check(($sent_by_mench ? $im['recipient']['id'] : $im['sender']['id']));
                    $is_quick_reply = (isset($im['message']['quick_reply']['payload']));

                    //Check if this User is unsubscribed:
                    if (!$is_quick_reply && count($this->Links_model->ln_fetch(array(
                            'ln_parent_entity_id' => 4455, //Unsubscribed
                            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                            'ln_child_entity_id' => $en['en_id'],
                            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        ))) > 0) {

                        //Yes, this User is Unsubscribed! Give them an option to re-activate their Mench account:
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
                        return print_r('re-subscribe');
                    }


                    //Set more variables:
                    $in_type_entity_id_search = 0; //If >0, we will try to see if this message is to submit a requirement for an intent

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
                     * appropriately based on who sent the message (Mench/User)
                     *
                     * */

                    if ($is_quick_reply) {

                        //Quick Reply Answer Received:
                        $ln_data['ln_type_entity_id'] = 4460;
                        $ln_data['ln_content'] = $im['message']['text']; //Quick reply always has a text

                        //Digest quick reply:
                        $quick_reply_results = $this->Communication_model->digest_message_payload($en, $im['message']['quick_reply']['payload']);
                        if(!$quick_reply_results['status']){
                            //There was an error, inform admin:
                            $this->Links_model->ln_create(array(
                                'ln_content' => 'digest_message_payload() for message returned error ['.$quick_reply_results['message'].']',
                                'ln_metadata' => $ln_metadata,
                                'ln_type_entity_id' => 4246, //Platform Bug Reports
                                'ln_miner_entity_id' => 1, //Shervin/Developer
                                'ln_child_entity_id' => $en['en_id'],
                            ));

                        }

                    } elseif (isset($im['message']['text'])) {

                        //Set message content:
                        $ln_data['ln_content'] = $im['message']['text'];

                        //Who sent this?
                        if ($sent_by_mench) {

                            $ln_data['ln_type_entity_id'] = 4552; //User Received Text Message

                        } else {

                            //Could be either text or URL:
                            $in_type_entity_id_search = ( filter_var($im['message']['text'], FILTER_VALIDATE_URL) ? 6682 /* URL Required */ : 6683 /* Text Required */ );

                            $ln_data['ln_type_entity_id'] = 4547; //User Sent Text Message

                        }

                    } elseif (isset($im['message']['attachments'])) {

                        //We have some attachments, lets loops through them:
                        foreach ($im['message']['attachments'] as $att) {

                            //Define 4 main Attachment Message Types:
                            $att_media_types = array( //Converts video, audio, image and file messages
                                'video' => array(
                                    'sent' => 4553,     //Link type for when sent to Users via Messenger
                                    'received' => 4548, //Link type for when received from Users via Messenger
                                    'requirement' => 6679,
                                ),
                                'audio' => array(
                                    'sent' => 4554,
                                    'received' => 4549,
                                    'requirement' => 6680,
                                ),
                                'image' => array(
                                    'sent' => 4555,
                                    'received' => 4550,
                                    'requirement' => 6678,
                                ),
                                'file' => array(
                                    'sent' => 4556,
                                    'received' => 4551,
                                    'requirement' => 6681,
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
                                $ln_data['ln_status_entity_id'] = 6174; //Link New, since URL needs to be uploaded to Mench CDN via cron__save_chat_media()
                                if(!$sent_by_mench){
                                    $in_type_entity_id_search = $att_media_types[$att['type']]['requirement'];
                                }

                            } elseif ($att['type'] == 'location') {

                                //Location Message Received:
                                $ln_data['ln_type_entity_id'] = 4557;

                                /*
                                 *
                                 * We do not have the ability to send this
                                 * type of message at this time and we will
                                 * only receive it if the User decides to
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

                                $this->Links_model->ln_create(array(
                                    'ln_content' => 'api_webhook() received a message type that is not yet implemented: ['.$att['type'].']',
                                    'ln_type_entity_id' => 4246, //Platform Bug Reports
                                    'ln_miner_entity_id' => 1, //Shervin/Developer
                                    'ln_metadata' => array(
                                        'ln_data' => $ln_data,
                                        'ln_metadata' => $ln_metadata,
                                    ),
                                ));

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

                                $this->Links_model->ln_create(array(
                                    'ln_content' => 'api_webhook() received a message type that is not yet implemented: ['.$att['type'].']',
                                    'ln_type_entity_id' => 4246, //Platform Bug Reports
                                    'ln_miner_entity_id' => 1, //Shervin/Developer
                                    'ln_metadata' => array(
                                        'ln_data' => $ln_data,
                                        'ln_metadata' => $ln_metadata,
                                    ),
                                ));

                            }
                        }
                    }


                    //So did we recognized the
                    if (!isset($ln_data['ln_type_entity_id']) || !isset($ln_data['ln_miner_entity_id'])) {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->Links_model->ln_create(array(
                            'ln_type_entity_id' => 4246, //Platform Bug Reports
                            'ln_miner_entity_id' => 1, //Shervin/Developer
                            'ln_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details',
                            'ln_metadata' => $ln_metadata,
                        ));

                        //Terminate:
                        return print_r('unknown message type');
                    }


                    //We're all good, log this message:
                    $new_message = $this->Links_model->ln_create($ln_data);


                    //Did we have a pending response?
                    if(isset($new_message['ln_id']) && $in_type_entity_id_search > 0){

                        $pending_matches = array();
                        $pending_mismatches = array();

                        //Yes, see if we have a pending requirement submission:
                        foreach($this->Links_model->ln_fetch(array(
                            'ln_type_entity_id' => 6144, //Action Plan Submit Requirements
                            'ln_miner_entity_id' => $ln_data['ln_miner_entity_id'], //for this user
                            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
                            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                        ), array('in_parent'), 0) as $req_sub){
                            if($req_sub['in_type_entity_id']==$in_type_entity_id_search){
                                array_push($pending_matches, $req_sub);
                            } else {
                                array_push($pending_mismatches, $req_sub);
                            }
                        }

                        //Did we find any matching or mismatching requirement submissions?
                        if(count($pending_matches) > 0){

                            //We have some matches, focus on this:
                            $first_chioce = $pending_matches[0];

                            //We only look at first matching case which covers most cases, but here is an error in case not:
                            if(count($pending_matches) >= 2){
                                $this->Links_model->ln_create(array(
                                    'ln_content' => 'api_webhook() found multiple matching submission requirements for the same user! Time to program the view with more options.',
                                    'ln_type_entity_id' => 4246, //Platform Bug Reports
                                    'ln_miner_entity_id' => 1, //Shervin/Developer
                                    'ln_metadata' => array(
                                        'ln_data' => $ln_data,
                                        'pending_matches' => $pending_matches,
                                        'first_chioce' => $first_chioce,
                                    ),
                                ));
                            }

                            //We did find a pending submission requirement, confirm with user:
                            $this->Communication_model->dispatch_message(
                                'Got it! Please confirm your submission:',
                                $en,
                                true,
                                array(
                                    array(
                                        'content_type' => 'text',
                                        'title' => 'Confirm ✅',
                                        'payload' => 'CONFIRMRESPONSE_' . $new_message['ln_id'] . '_' . $first_chioce['ln_id'],
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => 'Try Again',
                                        'payload' => 'TRYANOTHERRESPONSE_' . $first_chioce['in_type_entity_id'],
                                    ),
                                    array(
                                        'content_type' => 'text',
                                        'title' => 'Skip',
                                        'payload' => 'SKIP-ACTIONPLAN_skip-initiate_' . $first_chioce['in_id'],
                                    )
                                )
                            );

                        } elseif(count($pending_mismatches) > 0){

                            //Only focus on the first mismatch, ignore the rest if any!
                            $mismatch_focus = $pending_mismatches[0];

                            $en_all_6794 = $this->config->item('en_all_6794'); //Requirement names

                            //We did not have any matches, but has some mismatches, maybe that's what they meant?
                            $this->Communication_model->dispatch_message(
                                $en_all_6794[$mismatch_focus['in_type_entity_id']]['m_name'].' to complete this step. Please try again.',
                                $en,
                                true
                            );

                        } elseif($ln_data['ln_type_entity_id']==4547){

                            //No requirement submissions for this text message... Digest text message & try to make sense of it:
                            $this->Communication_model->digest_message_text($en, $im['message']['text']);

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

                    //Authenticate User:
                    $en = $this->Entities_model->en_psid_check($im['sender']['id'], $quick_reply_payload);

                    //Log primary link:
                    $this->Links_model->ln_create(array(
                        'ln_type_entity_id' => $ln_type_entity_id,
                        'ln_metadata' => $ln_metadata,
                        'ln_content' => $quick_reply_payload,
                        'ln_miner_entity_id' => $en['en_id'],
                    ));

                    //Digest quick reply Payload if any:
                    if ($quick_reply_payload) {
                        $quick_reply_results = $this->Communication_model->digest_message_payload($en, $quick_reply_payload);
                        if(!$quick_reply_results['status']){
                            //There was an error, inform admin:
                            $this->Links_model->ln_create(array(
                                'ln_content' => 'digest_message_payload() for postback/referral returned error ['.$quick_reply_results['message'].']',
                                'ln_metadata' => $ln_metadata,
                                'ln_type_entity_id' => 4246, //Platform Bug Reports
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

                    $en = $this->Entities_model->en_psid_check($im['sender']['id']);

                    //Log link:
                    $this->Links_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_entity_id' => 4266, //Messenger Optin
                        'ln_miner_entity_id' => $en['en_id'],
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->Entities_model->en_psid_check($im['sender']['id']);

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
                        'ln_type_entity_id' => 4246, //Platform Bug Reports
                        'ln_miner_entity_id' => 1, //Shervin/Developer
                    ));

                }
            }
        }

        return print_r('success');
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

        $en_all_7369 = $this->config->item('en_all_7369');

        //Now let's update the menu:
        array_push($res, $this->Communication_model->facebook_graph('POST', '/me/messenger_profile', array(
            'persistent_menu' => array(
                array(
                    'locale' => 'default',
                    'composer_input_disabled' => false,
                    'disabled_surfaces' => array('CUSTOMER_CHAT_PLUGIN'),
                    'call_to_actions' => array(
                        array(
                            'title' => $en_all_7369[6138]['m_icon'].' '.$en_all_7369[6138]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/actionplan',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => $en_all_7369[6137]['m_icon'].' '.$en_all_7369[6137]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/myaccount',
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
         * Loads user my account "frame" which would
         * then use JS/Facebook API to determine User
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
        } elseif (!is_dev_environment() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Failed to authenticate your origin.</div>');
        } elseif (!isset($session_en['en_id'])) {
            //Messenger Webview, authenticate PSID:
            $session_en = $this->Entities_model->en_psid_check($psid);
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
        $this->load->view('view_messenger/myaccount_manage', array(
            'session_en' => $session_en,
        ));

    }

    function password_reset()
    {
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('view_shared/messenger_header', $data);
        $this->load->view('view_messenger/password_reset');
        $this->load->view('view_shared/messenger_footer');
    }

    function user_login()
    {
        //Check to see if they are already logged in?
        $session_en = $this->session->userdata('user');
        if (isset($session_en['en__parents'][0]) && filter_array($session_en['en__parents'], 'en_id', 1308)) {
            //Lead miner and above, go to console:
            return redirect_message('/dashboard');
        }

        $this->load->view('view_shared/public_header', array(
            'title' => 'Sign In',
        ));
        $this->load->view('view_messenger/user_login');
        $this->load->view('view_shared/public_footer');
    }

    function login_process()
    {

        //Setting for admin Sign Ins:

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
        } elseif (!isset($_POST['en_password'])) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
        }

        //Validate user email:
        $lns = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 3288, //Primary email
            'LOWER(ln_content)' => strtolower($_POST['input_email']),
        ));

        if (count($lns) == 0) {
            //Not found!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: ' . $_POST['input_email'] . ' not found.</div>');
        } elseif (!in_array($lns[0]['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Your email link is not public. Contact us to adjust your account.</div>');
        }


        //Fetch full entity data with their active Action Plans:
        $ens = $this->Entities_model->en_fetch(array(
            'en_id' => $lns[0]['ln_child_entity_id'],
        ));
        if (!in_array($ens[0]['en_status_entity_id'], $this->config->item('en_ids_7357') /* Entity Statuses Public */)) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Your account entity is not public. Contact us to adjust your account.</div>');
        }

        //Authenticate their password:
        $user_passwords = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4255, //Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $ens[0]['en_id'],
        ));
        if (count($user_passwords) == 0) {
            //They do not have a password assigned yet!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.</div>');
        } elseif (!in_array($user_passwords[0]['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)) {
            //They do not have a password assigned yet!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Password link is not public. Contact us to adjust your account.</div>');
        } elseif ($user_passwords[0]['ln_content'] != strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['en_password']))) {
            //Bad password
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Incorrect password for [' . $_POST['input_email'] . ']</div>');
        }

        //Now let's do a few more checks:

        //Make sure User is connected to Mench:
        if (!intval($ens[0]['en_psid'])) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You are not connected to Mench on Messenger, which is required to login to the Platform.</div>');
        }

        //Make sure User is not unsubscribed:
        if (count($this->Links_model->ln_fetch(array(
                'ln_child_entity_id' => $ens[0]['en_id'],
                'ln_parent_entity_id' => 4455, //Unsubscribed
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ))) > 0) {

            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You cannot login to the Platform because you are unsubscribed from Mench. You can re-active your account by sending a message to Mench on Messenger.</div>');

        }


        $session_data = array();
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false);
        $is_miner = filter_array($ens[0]['en__parents'], 'en_id', 1308);


        //Applicable for miners only:
        if (!$is_chrome && $is_miner) {

            //Remove miner privileges as they cannot use the platform with non-chrome Browser:
            foreach($ens[0]['en__parents'] as $key=>$value){
                if(in_array($value['en_id'], array(1308,1281))){
                    unset($ens[0]['en__parents'][$key]);
                }
            }

            //Remove miner status:
            $is_miner = false;

            //Show error:
            $this->session->set_flashdata('flash_message', '<div class="alert alert-danger" role="alert">Mining console requires <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> to properly function. Your mining permissions have been removed but you still have access to your Action Plan and Account.</div>');

        }


        //Assign user details:
        $session_data['user'] = $ens[0];


        //Are they miner? Give them Sign In access:
        if ($is_miner) {

            //Check their advance mode status:
            $last_advance_settings = $this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $ens[0]['en_id'],
                'ln_type_entity_id' => 5007, //Toggled Advance Mode
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(), 1, 0, array('ln_id' => 'DESC'));

            //They have admin rights:
            $session_data['user_session_count'] = 0;
            $session_data['advance_view_enabled'] = ( count($last_advance_settings) > 0 && substr_count($last_advance_settings[0]['ln_content'] , ' ON')==1 ? 1 : 0 );

        }


        //Append user IP and agent information
        if (isset($_POST['en_password'])) {
            unset($_POST['en_password']); //Sensitive information to be removed and NOT logged
        }

        //Log additional information:
        $ens[0]['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $ens[0]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $ens[0]['input_post_data'] = $_POST;


        //Log Sign In Link:
        $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $ens[0]['en_id'],
            'ln_metadata' => $ens[0],
            'ln_type_entity_id' => 4269, //User Login
            'ln_order' => ( isset($session_data['user_session_count']) ? $session_data['user_session_count'] : 0 ), //First Action
        ));

        //All good to go!
        //Load session and redirect:
        $this->session->set_userdata($session_data);


        if (isset($_POST['url']) && strlen($_POST['url']) > 0) {
            header('Location: ' . $_POST['url']);
        } else {
            //Default:
            if ($is_miner) {
                //miner default:
                header('Location: /dashboard');
            } else {
                //User default:
                header('Location: /actionplan');
            }
        }
    }


    function logout()
    {
        //Destroys Session
        $this->session->sess_destroy();
        header('Location: /');
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
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
            'LOWER(en_name)' => strtolower($_POST['en_name']),
        ));
        if (count($duplicates) > 0) {
            //This is a duplicate, disallow:
            return echo_json(array(
                'status' => 0,
                'message' => 'Name already in-use. Add a pre-fix or post-fix to make it unique.',
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
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
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
        $user_phones = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4319, //Phone are of type number
            'ln_parent_entity_id' => 4783, //Phone Number
        ));
        if (count($user_phones) > 0) {

            if (strlen($_POST['en_phone']) == 0) {

                //Remove:
                $this->Links_model->ln_update($user_phones[0]['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone Removed',
                );

            } elseif ($user_phones[0]['ln_content'] != $_POST['en_phone']) {

                //Update if not duplicate:
                $this->Links_model->ln_update($user_phones[0]['ln_id'], array(
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
                'ln_status_entity_id' => 6176, //Link Published
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
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
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
        $user_emails = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4255, //Emails are of type Text
            'ln_parent_entity_id' => 3288, //Email Address
        ));
        if (count($user_emails) > 0) {

            if (strlen($_POST['en_email']) == 0) {

                //Remove email:
                $this->Links_model->ln_update($user_emails[0]['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Email removed',
                );

            } elseif ($user_emails[0]['ln_content'] != $_POST['en_email']) {

                //Update if not duplicate:
                $this->Links_model->ln_update($user_emails[0]['ln_id'], array(
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
                'ln_status_entity_id' => 6176, //Link Published
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
        $user_passwords = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4255, //Passwords are of type Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['en_password']));


        if (count($user_passwords) > 0) {

            if ($hashed_password == $user_passwords[0]['ln_content']) {

                $return = array(
                    'status' => 0,
                    'message' => 'Password Unchanged',
                );

            } else {

                //Update password:
                $this->Links_model->ln_update($user_passwords[0]['ln_id'], array(
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
                'ln_status_entity_id' => 6176, //Link Published
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
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
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
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
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
                        'ln_status_entity_id' => 6173, //Link Removed
                    ), $_POST['en_id']);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Removed. ';

                } else {



                }

            } elseif ($profile_set) {

                //Create new link:
                $this->Links_model->ln_create(array(
                    'ln_status_entity_id' => 6176, //Link Published
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

    function actionplan_intention_add(){

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
        if($this->Actionplan_model->actionplan_intention_add($session_en['en_id'], $_POST['in_id'])){
            //All good:
            $en_all_7369 = $this->config->item('en_all_7369');
            return echo_json(array(
                'status' => 1,
                'message' => '<i class="far fa-check-circle"></i> Successfully added to your <b><a href="/actionplan">'.$en_all_7369[6138]['m_icon'].' '.$en_all_7369[6138]['m_name'].'</a></b>',
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
         * Loads user action plans "frame" which would
         * then use JS/Facebook API to determine User
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


    function actionplan_reset_progress($en_id, $timestamp, $secret_key){

        if($secret_key != md5($en_id . $this->config->item('actionplan_salt') . $timestamp)){
            die('Invalid Secret Key');
        }

        //Fetch their current progress links:
        $progress_links = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6415')) . ')' => null, //Action Plan Clear All Steps
            'ln_miner_entity_id' => $en_id,
        ), array(), 0);

        if(count($progress_links) > 0){

            //Yes they did have some:
            $message = count($progress_links).' Action Plan progression link'.echo__s(count($progress_links)).' removed';

            //Log link:
            $clear_all_link = $this->Links_model->ln_create(array(
                'ln_content' => $message,
                'ln_type_entity_id' => 6415, //Action Plan Clear All Steps
                'ln_miner_entity_id' => $en_id,
            ));

            //Remove all progressions:
            foreach($progress_links as $progress_link){
                $this->Links_model->ln_update($progress_link['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                    'ln_parent_link_id' => $clear_all_link['ln_id'], //To indicate when it was removed
                ), $en_id);
            }

        } else {

            //Nothing to do:
            $message = 'Nothing to delete...';

        }

        //Show basic UI for now:
        echo $message;
        echo '<div><a href="/actionplan" style="font-weight: bold; font-size: 1.4em; margin-top: 10px;">Go Back</a></div>';

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
        } elseif (!is_dev_environment() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Failed to authenticate your origin.</div>');
        } elseif (!isset($session_en['en_id'])) {
            //Messenger Webview, authenticate PSID:
            $session_en = $this->Entities_model->en_psid_check($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }


        //This is a special command to find the next intent:
        if($in_id=='next'){
            //Find the next item to navigate them to:
            $next_in_id = $this->Actionplan_model->actionplan_step_next_go($session_en['en_id'], false);
            $in_id = ( $next_in_id > 0 ? $next_in_id : 0 );
        }


        //Fetch user's intentions as we'd need to know their top-level goals:
        $user_intents = $this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

        //Show appropriate UI:
        if ($in_id < 1) {

            //Log Action Plan View:
            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 4283, //Opened Action Plan
                'ln_miner_entity_id' => $session_en['en_id'],
            ));

            //List all user intentions:
            $this->load->view('view_messenger/actionplan_intentions', array(
                'session_en' => $session_en,
                'user_intents' => $user_intents,
            ));

        } else {

            //Fetch/validate selected intent:
            $ins = $this->Intents_model->in_fetch(array(
                'in_id' => $in_id,
            ));

            if (count($ins) < 1) {
                die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
            } elseif (!in_array($ins[0]['in_status_entity_id'], $this->config->item('en_ids_7355') /* Intent Statuses Public */)) {
                die('<div class="alert alert-danger" role="alert">Intent is not made public yet.</div>');
            }

            //Load Action Plan UI with relevant variables:
            $this->load->view('view_messenger/actionplan_step', array(
                'session_en' => $session_en,
                'user_intents' => $user_intents,
                'advance_step' => $this->Actionplan_model->actionplan_step_next_echo($session_en['en_id'], $in_id, false),
                'in' => $ins[0], //Currently focused intention:
            ));

        }
    }


    function actionplan_stop_save(){

        /*
         *
         * When users indicate they want to stop
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
        $user_intents = $this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'ln_parent_intent_id' => $_POST['in_id'],
        ));
        if(count($user_intents) < 1){
            //Give error:
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not locate Action Plan',
            ));
        }

        //Adjust Action Plan status:
        foreach($user_intents as $ln){
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_status_entity_id' => ( $_POST['stop_method_id']==6154 ? 6176 /* Link Published */ : 6173 /* Link Removed */ ), //This is a nasty HACK!
            ), $_POST['en_miner_id']);
        }

        //Log related link:
        $this->Links_model->ln_create(array(
            'ln_content' => $_POST['stop_feedback'],
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_type_entity_id' => $_POST['stop_method_id'],
            'ln_parent_intent_id' => $_POST['in_id'],
        ));

        //Communicate with user:
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
            'skip_step_preview' => 'WARNING: '.$this->Actionplan_model->actionplan_step_skip_initiate($en_id, $in_id, false).' Are you sure you want to skip?',
        ));

    }

    function actionplan_skip_apply($en_id, $in_id)
    {

        //Actually go ahead and skip
        $this->Actionplan_model->actionplan_step_skip_apply($en_id, $in_id);
        //Assume its all good!

        //We actually skipped, draft message:
        $message = '<div class="alert alert-success" role="alert">I successfully skipped all steps.</div>';

        //Find the next item to navigate them to:
        $next_in_id = $this->Actionplan_model->actionplan_step_next_go($en_id, false);
        if ($next_in_id > 0) {
            return redirect_message('/actionplan/' . $next_in_id, $message);
        } else {
            return redirect_message('/actionplan', $message);
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
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
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
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
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
                    'ln_status_entity_id' => 6173, //Link Removed
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
                'ln_status_entity_id' => 6176, //Link Published
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
         * user preferences.
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
        $top_priority = $this->Actionplan_model->actionplan_intention_focus($_POST['en_miner_id']);
        if($top_priority){
            //Communicate top-priority with user:
            $this->Communication_model->dispatch_message(
                '🚩 Action Plan prioritised: Now our focus is to '.$top_priority['in']['in_outcome'].' ('.$top_priority['completion_rate']['completion_percentage'].'% done)',
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
            return redirect_message('/actionplan/' . $parent_in_id, '<div class="alert alert-danger" role="alert">Invalid Authentication Key</div>');
        }

        //Validate Answer Intent:
        $answer_ins = $this->Intents_model->in_fetch(array(
            'in_id' => $answer_in_id,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ));
        if (count($answer_ins) < 1) {
            return redirect_message('/actionplan/' . $parent_in_id, '<div class="alert alert-danger" role="alert">Invalid Answer</div>');
        }

        //Fetch current progression links, if any:
        $current_progression_links = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
            'ln_miner_entity_id' => $en_id,
            'ln_parent_intent_id' => $parent_in_id,
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ));

        //All good, save chosen OR path
        $new_progression_link = $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => 6157, //Action Plan Question Answered
            'ln_parent_intent_id' => $parent_in_id,
            'ln_child_intent_id' => $answer_in_id,
            'ln_status_entity_id' => 6176, //Link Published
        ));

        //See if we also need to mark the child as complete:
        $this->Actionplan_model->actionplan_completion_auto_apply($en_id, $answer_ins[0]);

        //Archive current progression links:
        foreach($current_progression_links as $ln){
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_parent_link_id' => $new_progression_link['ln_id'],
                'ln_status_entity_id' => 6173, //Link Removed
            ), $en_id);
        }

        return redirect_message('/actionplan/' . $answer_in_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');

    }


    function cron__sync_attachments()
    {

        /*
         *
         * Messenger has a feature that allows us to cache
         * media files in their servers so we can deliver
         * them instantly without a need to re-upload them
         * every time we want to send them to a user.
         *
         */

        $fb_convert_4537 = $this->config->item('fb_convert_4537'); //Supported Media Types
        $success_count = 0; //Track success
        $ln_metadata = array();


        //Let's fetch all Media files without a Facebook attachment ID:
        $ln_pending = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', array_keys($fb_convert_4537)) . ')' => null,
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_metadata' => null, //Missing Facebook Attachment ID [NOTE: Must make sure ln_metadata is not used for anything else for these link types]
        ), array(), 10, 0, array('ln_id' => 'ASC')); //Sort by oldest added first


        //Put something in the ln_metadata so other cron jobs do not pick up on it:
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
                    'ln_type_entity_id' => 4246, //Platform Bug Reports
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
         * 1) Media received from users
         * 2) Media sent from Mench Admins via Facebook Chat Inbox
         *
         * Note: It would not store media that is sent from intent
         * notes since those are already stored.
         *
         * */

        $ln_pending = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id' => 6174, //Link New
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6102')) . ')' => null, //User Sent/Received Media Links
        ), array(), 20);

        //Set link statuses to drafting so other Cron jobs don't pick them up:
        foreach ($ln_pending as $ln) {
            if($ln['ln_status_entity_id'] == 6174 /* Link New */){
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_status_entity_id' => 6175, //Link Drafting
                ));
            }
        }

        $counter = 0;
        foreach ($ln_pending as $ln) {

            //Store to CDN:
            $cdn_status = upload_to_cdn($ln['ln_content'], $ln['ln_miner_entity_id'], $ln);
            if(!$cdn_status['status']){
                continue;
            }

            //Update link:
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_content' => $cdn_status['cdn_url'], //CDN URL
                'ln_child_entity_id' => $cdn_status['cdn_en']['en_id'], //New URL Entity
                'ln_status_entity_id' => 6176, //Link Published
            ));

            //Increase counter:
            $counter++;
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
            'ln_status_entity_id' => 6174, //Link New
            'ln_type_entity_id' => 4299, //Updated Profile Picture
        ), array('en_miner'), 20); //Max number of scans per run


        //Set link statuses to drafting so other Cron jobs don't pick them up:
        foreach ($ln_pending as $ln) {
            if($ln['ln_status_entity_id'] == 6174 /* Link New */){
                $this->Links_model->ln_update($ln['ln_id'], array(
                    'ln_status_entity_id' => 6175, /* Link Drafting */
                ));
            }
        }


        //Now go through and upload to CDN:
        foreach ($ln_pending as $ln) {

            //make sure it's a URL:
            if(!filter_var($ln['ln_content'], FILTER_VALIDATE_URL)){
                continue;
            }

            //Save photo to CDN:
            $cdn_status = upload_to_cdn($ln['ln_content'], $ln['ln_miner_entity_id'], $ln, false, $ln['en_name'].' Profile Photo');
            if (!$cdn_status['status']) {
                continue;
            }

            //Update entity icon only if not already set:
            $ln_child_entity_id = 0;
            if (strlen($ln['en_icon']) < 1) {

                //Update Cover ID:
                $this->Entities_model->en_update($ln['en_id'], array(
                    'en_icon' => '<img src="' . $cdn_status['cdn_url'] . '">',
                ), true, $ln['en_id']);

                //Link link to entity:
                $ln_child_entity_id = $ln['en_id'];

            }

            //Update link:
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_status_entity_id' => 6176, //Link Published
                'ln_content' => null, //Remove URL from content to indicate its done
                'ln_child_entity_id' => $ln_child_entity_id,
                'ln_metadata' => array(
                    'original_url' => $ln['ln_content'],
                    'cdn_status' => $cdn_status,
                ),
            ));

        }

        echo_json($ln_pending);
    }

}