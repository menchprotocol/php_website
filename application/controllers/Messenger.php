<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messenger extends CI_Controller
{


    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));
    }


    function fetch_profile($psid)
    {

        if (!superpower_assigned(10986)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing superpower',
            ));
        }

        //Validate messenger ID:
        $user_messenger = $this->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Source Links
            'ln_parent_source_id' => 6196, //Mench Messenger
            'ln_external_id' => $psid,
        ));
        if (count($user_messenger) == 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'User not connected to Mench Messenger',
            ));
        }

        //Fetch results and show:
        return echo_json($this->READ_model->facebook_graph('GET', '/' . $user_messenger[0]['ln_external_id'], array()));

    }


    function deauthorize(){
        //When a user removes us:
        $this->READ_model->ln_create(array(
            'ln_content' => 'facebook_deauthorize() Just Called',
            'ln_type_source_id' => 4246, //Platform Bug Reports
            'ln_metadata' => array(
                'POST' => $_POST,
                'GET' => $_GET,
            ),
        ));
    }


    function update_menu()
    {

        /*
         * A function that will sync the fixed
         * menu of Mench's Facebook Messenger.
         *
         * */

        //Let's first give permission to our pages to do so:
        $res = array();
        array_push($res, $this->READ_model->facebook_graph('POST', '/me/messenger_profile', array(
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

        //Now let's update the menu:
        $en_all_2738 = $this->config->item('en_all_2738'); //MENCH

        array_push($res, $this->READ_model->facebook_graph('POST', '/me/messenger_profile', array(
            'persistent_menu' => array(
                array(
                    'locale' => 'default',
                    'composer_input_disabled' => false,
                    'disabled_surfaces' => array('CUSTOMER_CHAT_PLUGIN'),
                    'call_to_actions' => array(
                        array(
                            'title' => '🔵 '.$en_all_2738[4536]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/source',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => '🔴 '.$en_all_2738[6205]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/read',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => '🟡 '.$en_all_2738[4535]['m_name'],
                            'type' => 'web_url',
                            'url' => 'https://mench.com/tree',
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




    function webhook($test = 0)
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
            $this->READ_model->ln_create(array(
                'ln_content' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'ln_metadata' => $ln_metadata,
                'ln_type_source_id' => 4246, //Platform Bug Reports
            ));
            return print_r('unknown page');
        }

        //Loop through entries:
        foreach ($ln_metadata['entry'] as $entry) {

            //check the page ID:
            if (!isset($entry['id']) || !($entry['id'] == config_var(11075))) {
                //This can happen for the older webhook that we offered to other FB pages:
                continue;
            } elseif (!isset($entry['messaging'])) {
                $this->READ_model->ln_create(array(
                    'ln_content' => 'facebook_webhook() call missing messaging Array().',
                    'ln_metadata' => $ln_metadata,
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                ));
                continue;
            }

            //loop though the messages:
            foreach ($entry['messaging'] as $im) {

                if (isset($im['read']) || isset($im['delivery'])) {

                    //Message read OR delivered
                    $ln_type_source_id = (isset($im['delivery']) ? 4279 /* Message Delivered */ : 4278 /* Message Read */);

                    //Authenticate User:
                    $en = $this->SOURCE_model->en_messenger_auth($im['sender']['id']);

                    //Log Link Only IF last delivery link was 3+ minutes ago (Since Facebook sends many of these):
                    $last_links_logged = $this->READ_model->ln_fetch(array(
                        'ln_type_source_id' => $ln_type_source_id,
                        'ln_creator_source_id' => $en['en_id'],
                        'ln_timestamp >=' => date("Y-m-d H:i:s", (time() - (60))), //READ logged less than 1 minutes ago
                    ), array(), 1);

                    if (count($last_links_logged) == 0) {
                        //We had no recent links of this kind, so go ahead and log:
                        $this->READ_model->ln_create(array(
                            'ln_metadata' => $ln_metadata,
                            'ln_type_source_id' => $ln_type_source_id,
                            'ln_creator_source_id' => $en['en_id'],
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
                    $en = $this->SOURCE_model->en_messenger_auth(($sent_by_mench ? $im['recipient']['id'] : $im['sender']['id']));
                    $is_quick_reply = (isset($im['message']['quick_reply']['payload']));

                    //Set more variables:
                    $matching_types = array(); //Defines the supported Tree Subtypes

                    unset($ln_data); //Reset everything in case its set from the previous loop!
                    $ln_data = array(
                        'ln_creator_source_id' => $en['en_id'],
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
                        $ln_data['ln_type_source_id'] = 4460;
                        $ln_data['ln_content'] = $im['message']['text']; //Quick reply always has a text

                        //Digest quick reply:
                        $quick_reply_results = $this->READ_model->digest_received_payload($en, $im['message']['quick_reply']['payload']);

                        if(!$quick_reply_results['status']){
                            //There was an error, inform Trainer:
                            $this->READ_model->ln_create(array(
                                'ln_content' => 'digest_received_payload() for message returned error ['.$quick_reply_results['message'].']',
                                'ln_metadata' => $ln_metadata,
                                'ln_type_source_id' => 4246, //Platform Bug Reports
                                'ln_creator_source_id' => $en['en_id'],
                            ));

                        }

                    } elseif (isset($im['message']['text'])) {

                        //Set message content:
                        $ln_data['ln_content'] = $im['message']['text'];

                        //Who sent this?
                        if ($sent_by_mench) {

                            $ln_data['ln_type_source_id'] = 4552; //User Received Text Message

                        } else {

                            //Could be either text or URL:
                            if(filter_var($im['message']['text'], FILTER_VALIDATE_URL)){
                                //The message is a URL:
                                $matching_types = array(
                                    6683 /* Send Text */ ,
                                    6682 /* Send URL */,
                                    6679 /* Send Video */,
                                    6680 /* Send Audio */,
                                    6678 /* Send Image */,
                                    6681 /* Send Document */,
                                    7637 /* ATTACHMENT */
                                );
                            } else {
                                $matching_types = array(
                                    6683 /* Send Text */
                                );
                            }
                            $ln_data['ln_type_source_id'] = 4547; //User Sent Text Message

                        }

                    } elseif (isset($im['message']['attachments'])) {

                        //We have some attachments, lets loops through them:
                        foreach ($im['message']['attachments'] as $att) {

                            //Define 4 main Attachment Message Types:
                            $att_media_types = array( //Converts video, audio, image and file messages
                                'video' => array(
                                    'sent' => 4553,     //Link type for when sent to Users via Messenger
                                    'received' => 4548, //Link type for when received from Users via Messenger
                                    'matching_types' => array(
                                        7637 /* Send Multimedia */ ,
                                        6679 /* Send Video */
                                    ),
                                ),
                                'audio' => array(
                                    'sent' => 4554,
                                    'received' => 4549,
                                    'matching_types' => array(
                                        7637 /* Send Multimedia */ ,
                                        6680 /* Send Audio */
                                    ),
                                ),
                                'image' => array(
                                    'sent' => 4555,
                                    'received' => 4550,
                                    'matching_types' => array(
                                        7637 /* Send Multimedia */ ,
                                        6678 /* Send Image */
                                    ),
                                ),
                                'file' => array(
                                    'sent' => 4556,
                                    'received' => 4551,
                                    'matching_types' => array(
                                        7637 /* Send Multimedia */ ,
                                        6681 /* Send Document */
                                    ),
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
                                 * */

                                $ln_data['ln_type_source_id'] = $att_media_types[$att['type']][($sent_by_mench ? 'sent' : 'received')];
                                $ln_data['ln_content'] = $att['payload']['url']; //Media Attachment Temporary Facebook URL
                                $ln_data['ln_status_source_id'] = 6175; //Link Drafting, since URL needs to be uploaded to Mench CDN via save_chat_media()
                                if(!$sent_by_mench){
                                    $matching_types = $att_media_types[$att['type']]['matching_types'];
                                }

                            } elseif ($att['type'] == 'location') {

                                //Location Message Received:
                                $ln_data['ln_type_source_id'] = 4557;

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

                                $this->READ_model->ln_create(array(
                                    'ln_content' => 'api_webhook() received a message type that is not yet implemented: ['.$att['type'].']',
                                    'ln_type_source_id' => 4246, //Platform Bug Reports
                                    'ln_creator_source_id' => $en['en_id'],
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

                                $this->READ_model->ln_create(array(
                                    'ln_content' => 'api_webhook() received a message type that is not yet implemented: ['.$att['type'].']',
                                    'ln_type_source_id' => 4246, //Platform Bug Reports
                                    'ln_creator_source_id' => $en['en_id'],
                                    'ln_metadata' => array(
                                        'ln_data' => $ln_data,
                                        'ln_metadata' => $ln_metadata,
                                    ),
                                ));

                            }
                        }
                    }


                    //So did we recognized the
                    if (!isset($ln_data['ln_type_source_id']) || !isset($ln_data['ln_creator_source_id'])) {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->READ_model->ln_create(array(
                            'ln_type_source_id' => 4246, //Platform Bug Reports
                            'ln_creator_source_id' => $en['en_id'],
                            'ln_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details',
                            'ln_metadata' => $ln_metadata,
                        ));

                        //Terminate:
                        return print_r('unknown message type');
                    }


                    //We're all good, log this message:
                    $new_message = $this->READ_model->ln_create($ln_data);


                    //Did we have a pending response?
                    if(isset($new_message['ln_id']) && count($matching_types) > 0){

                        $pending_matches = array();
                        $pending_mismatches = array();

                        //TODO Yes, see if we have a pending tree that requires answer 6144:

                        //Did we find any matching or mismatching requirement submissions?
                        if(count($pending_matches) > 0 && 0){

                            //We have some matches, focus on this:
                            $first_chioce = $pending_matches[0];

                            //Accept their answer:

                            //Validate 🔴 READING LIST read:
                            $pending_req_submission = $this->READ_model->ln_fetch(array(
                                'ln_id' => $first_chioce['ln_id'],
                                //Also validate other requirements:
                                'ln_type_source_id' => 6144, //🔴 READING LIST Submit Requirements
                                'ln_creator_source_id' => $en['en_id'], //for this user
                                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Transaction Status Incomplete
                                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Tree Status Public
                            ), array('in_parent'));


                            if(isset($pending_req_submission[0])){

                                //Make changes:

                                //TODO RREMOVE THIS LOGIC AND CREATE A NEW LINK WHEN ADDED

                                $this->READ_model->ln_update($pending_req_submission[0]['ln_id'], array(
                                    'ln_content' => $new_message['ln_content'],
                                    'ln_status_source_id' => 6176, //Link Published
                                    'ln_parent_transaction_id' => $new_message['ln_id'],
                                ));

                                //Process on-complete automations:
                                $this->READ_model->read__completion_recursive_up($en['en_id'], $pending_req_submission[0]);

                            } else {

                                //Opppsi:
                                $this->READ_model->ln_create(array(
                                    'ln_parent_transaction_id' => $first_chioce['ln_id'],
                                    'ln_content' => 'messenger/webhook() failed to validate user response original step',
                                    'ln_type_source_id' => 4246, //Platform Bug Reports
                                    'ln_creator_source_id' => $en['en_id'], //for this user
                                ));

                                //Confirm with user:
                                $this->READ_model->dispatch_message(
                                    'Unable to accept your response. My trainers have already been notified.',
                                    $en,
                                    true
                                );
                            }



                            //Load next option:
                            $this->READ_model->read_next_go($en['en_id'], true, true);


                        } elseif(count($pending_mismatches) > 0){

                            //Only focus on the first mismatch, ignore the rest if any!
                            $mismatch_focus = $pending_mismatches[0];

                            $en_all_7585 = $this->config->item('en_all_7585');

                            //We did not have any matches, but has some mismatches, maybe that's what they meant?
                            $this->READ_model->dispatch_message(
                                'Alert: You should '.$en_all_7585[$mismatch_focus['in_type_source_id']]['m_name'].' to complete this step.',
                                $en,
                                true
                            );

                        } elseif($ln_data['ln_type_source_id']==4547){

                            //No requirement submissions for this text message... Digest text message & try to make sense of it:
                            $this->READ_model->digest_received_text($en, $im['message']['text']);

                        } else {

                            //Let them know that we did not understand them:
                            $this->READ_model->dispatch_message(
                                echo_random_message('one_way_only'),
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
                    $ln_type_source_id = (isset($im['delivery']) ? 4267 /* Messenger Referral */ : 4268 /* Messenger Postback */);

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
                    $en = $this->SOURCE_model->en_messenger_auth($im['sender']['id'], $quick_reply_payload);

                    //Log primary link:
                    $this->READ_model->ln_create(array(
                        'ln_type_source_id' => $ln_type_source_id,
                        'ln_metadata' => $ln_metadata,
                        'ln_content' => $quick_reply_payload,
                        'ln_creator_source_id' => $en['en_id'],
                    ));

                    //Digest quick reply Payload if any:
                    if ($quick_reply_payload) {
                        $quick_reply_results = $this->READ_model->digest_received_payload($en, $quick_reply_payload);
                        if(!$quick_reply_results['status']){
                            //There was an error, inform Trainer:
                            $this->READ_model->ln_create(array(
                                'ln_content' => 'digest_received_payload() for postback/referral returned error ['.$quick_reply_results['message'].']',
                                'ln_metadata' => $ln_metadata,
                                'ln_type_source_id' => 4246, //Platform Bug Reports
                                'ln_creator_source_id' => $en['en_id'],
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

                    $en = $this->SOURCE_model->en_messenger_auth($im['sender']['id']);

                    //Log link:
                    $this->READ_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_source_id' => 4266, //Messenger Optin
                        'ln_creator_source_id' => $en['en_id'],
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->SOURCE_model->en_messenger_auth($im['sender']['id']);

                    //Log link:
                    $this->READ_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_source_id' => 4577, //Message Request Accepted
                        'ln_creator_source_id' => $en['en_id'],
                    ));

                } else {

                    //This should really not happen!
                    $this->READ_model->ln_create(array(
                        'ln_content' => 'facebook_webhook() received unrecognized webhook call',
                        'ln_metadata' => $ln_metadata,
                        'ln_type_source_id' => 4246, //Platform Bug Reports
                    ));

                }
            }
        }

        return print_r('success');
    }




}