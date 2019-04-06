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


    function api_webhook()
    {

        /*
         *
         * The master function for all Facebook webhook calls
         * This URL is set as our end to receive Facebook calls:
         *
         * https://developers.facebook.com/apps/1782431902047009/webhooks/
         *
         * */

        //Facebook Webhook Authentication:
        $challenge = (isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : null);
        $verify_token = (isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : null);
        $fb_settings = $this->config->item('fb_settings');

        //We need this only for the first time to authenticate that we own the server:
        if ($verify_token == '722bb4e2bac428aa697cc97a605b2c5a') {
            echo $challenge;
        }

        //Fetch input data:
        $ln_metadata = json_decode(file_get_contents('php://input'), true);

        //This is for local testing only:
        //$ln_metadata = objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1505007977668,"messaging":[{"sender":{"id":"1443101719058431"},"recipient":{"id":"381488558920384"},"timestamp":1505007977521,"message":{"mid":"mid.$cAAFa9hmVoehkmryMMVeaXdGIY9x5","seq":19898,"text":"Yes"}}]}]}'));


        //Do some basic checks:
        if (!isset($ln_metadata['object']) || !isset($ln_metadata['entry'])) {
            //Likely loaded the URL in browser:
            return false;
        } elseif ($ln_metadata['object'] != 'page') {
            $this->Database_model->ln_create(array(
                'ln_content' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'ln_metadata' => $ln_metadata,
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
            return false;
        }


        //Loop through entries:
        foreach ($ln_metadata['entry'] as $entry) {

            //check the page ID:
            if (!isset($entry['id']) || !($entry['id'] == $fb_settings['page_id'])) {
                //This can happen for the older webhook that we offered to other FB pages:
                continue;
            } elseif (!isset($entry['messaging'])) {
                $this->Database_model->ln_create(array(
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
                    $en = $this->Matrix_model->en_student_messenger_authenticate($im['sender']['id']);

                    //Log Link Only IF last delivery link was 3+ minutes ago (Since Facebook sends many of these):
                    $last_trs_logged = $this->Database_model->ln_fetch(array(
                        'ln_type_entity_id' => $ln_type_entity_id,
                        'ln_miner_entity_id' => $en['en_id'],
                        'ln_timestamp >=' => date("Y-m-d H:i:s", (time() - (180))), //Links logged less than 3 minutes ago
                    ), array(), 1);

                    if (count($last_trs_logged) == 0) {
                        //We had no recent links of this kind, so go ahead and log:
                        $this->Database_model->ln_create(array(
                            'ln_metadata' => $ln_metadata,
                            'ln_type_entity_id' => $ln_type_entity_id,
                            'ln_miner_entity_id' => $en['en_id'],
                            'ln_timestamp' => echo_time_milliseconds($im['timestamp']), //The Facebook time
                        ));
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
                    $en = $this->Matrix_model->en_student_messenger_authenticate($im['sender']['id']);

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
                    $this->Database_model->ln_create(array(
                        'ln_type_entity_id' => $ln_type_entity_id,
                        'ln_metadata' => $ln_metadata,
                        'ln_content' => $quick_reply_payload,
                        'ln_miner_entity_id' => $en['en_id'],
                        'ln_timestamp' => echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                    //Digest quick reply Payload if any:
                    if ($quick_reply_payload) {
                        $this->Chat_model->digest_quick_reply($en, $quick_reply_payload);
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

                    $en = $this->Matrix_model->en_student_messenger_authenticate($im['sender']['id']);

                    //Log link:
                    $this->Database_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_entity_id' => 4266, //Messenger Optin
                        'ln_miner_entity_id' => $en['en_id'],
                        'ln_timestamp' => echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->Matrix_model->en_student_messenger_authenticate($im['sender']['id']);

                    //Log link:
                    $this->Database_model->ln_create(array(
                        'ln_metadata' => $ln_metadata,
                        'ln_type_entity_id' => 4577, //Message Request Accepted
                        'ln_miner_entity_id' => $en['en_id'],
                        'ln_timestamp' => echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

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
                        return echo_json(array('is_logged_already' => 1));

                    }


                    //Set variables:
                    unset($ln_data); //Reset everything in case its set from the previous loop!
                    $sent_by_mench = (isset($im['message']['is_echo'])); //Indicates the message sent from the page itself
                    $en = $this->Matrix_model->en_student_messenger_authenticate(($sent_by_mench ? $im['recipient']['id'] : $im['sender']['id']));

                    $ln_data = array(
                        'ln_miner_entity_id' => $en['en_id'],
                        'ln_timestamp' => ($sent_by_mench ? null : echo_time_milliseconds($im['timestamp'])), //Facebook time if received from Student
                        'ln_metadata' => $ln_metadata, //Entire JSON object received by Facebook API
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

                    if (isset($im['message']['quick_reply']['payload'])) {

                        //Quick Reply Answer Received (Cannot be sent as we never send quick replies)
                        $ln_data['ln_type_entity_id'] = 4460;
                        $ln_data['ln_content'] = $im['message']['text']; //Quick reply always has a text

                        //Digest the Quick Reply payload:
                        $this->Chat_model->digest_quick_reply($en, $im['message']['quick_reply']['payload']);

                    } elseif (isset($im['message']['text'])) {

                        //Set message content:
                        $ln_data['ln_content'] = $im['message']['text'];

                        //Who sent this?
                        if ($sent_by_mench) {

                            //Text Message Sent By Mench Admin via Facebook Inbox UI
                            $ln_data['ln_type_entity_id'] = 4552; //Text Message Sent

                        } else {

                            $ln_data['ln_type_entity_id'] = 4547; //Text Message Received

                            //Digest message & try to make sense of it:
                            $this->Chat_model->digest_message($en, $im['message']['text']);

                        }

                    } elseif (isset($im['message']['attachments'])) {

                        //We have some attachments, lets loops through them:
                        foreach ($im['message']['attachments'] as $att) {

                            //Define 4 main Attachment Message Types:
                            $att_media_types = array( //Converts video, audio, image and file messages
                                'video' => array(
                                    'sent' => 4553,     //Link type for when sent to Students via Messenger
                                    'received' => 4548, //Link type for when received from Students via Messenger
                                ),
                                'audio' => array(
                                    'sent' => 4554,
                                    'received' => 4549,
                                ),
                                'image' => array(
                                    'sent' => 4555,
                                    'received' => 4550,
                                ),
                                'file' => array(
                                    'sent' => 4556,
                                    'received' => 4551,
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
                    if (isset($ln_data['ln_type_entity_id']) && isset($ln_data['ln_miner_entity_id'])) {

                        //We're all good, log this message:
                        $this->Database_model->ln_create($ln_data);

                    } else {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->Database_model->ln_create(array(
                            'ln_type_entity_id' => 4246, //Platform Error
                            'ln_miner_entity_id' => 1, //Shervin/Developer
                            'ln_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details',
                            'ln_metadata' => $ln_metadata,
                        ));

                    }

                } else {

                    //This should really not happen!
                    $this->Database_model->ln_create(array(
                        'ln_content' => 'facebook_webhook() received unrecognized webhook call',
                        'ln_metadata' => $ln_metadata,
                        'ln_type_entity_id' => 4246, //Platform Error
                        'ln_miner_entity_id' => 1, //Shervin/Developer
                    ));

                }
            }
        }
    }

    function api_fetch_profile($en_id)
    {

        //Only moderators can do this at this time:
        $session_en = en_auth(array(1281));
        $current_us = $this->Database_model->en_fetch(array(
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
                'fb_profile' => $this->Chat_model->facebook_graph('GET', '/' . $current_us[0]['en_psid'], array()),
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
        array_push($res, $this->Chat_model->facebook_graph('POST', '/me/messenger_profile', array(
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
        array_push($res, $this->Chat_model->facebook_graph('POST', '/me/messenger_profile', array(
            'persistent_menu' => array(
                array(
                    'locale' => 'default',
                    'composer_input_disabled' => false,
                    'disabled_surfaces' => array('CUSTOMER_CHAT_PLUGIN'),
                    'call_to_actions' => array(
                        array(
                            'title' => '🚩 Action Plan',
                            'type' => 'web_url',
                            'url' => 'https://mench.com/messenger/actionplan',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => '👤 My Account',
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
            $session_en = $this->Matrix_model->en_student_messenger_authenticate($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }


        //Load Action Plan UI:
        $this->load->view('view_messenger/myaccount_ui.php', array(
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
        $duplicates = $this->Database_model->en_fetch(array(
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
        $this->Database_model->en_update($_POST['en_id'], array(
            'en_name' => $_POST['en_name'],
        ), true, $_POST['en_id']);

        return echo_json(array(
            'status' => 1,
            'message' => 'Name updated',
        ));
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
            $duplicates = $this->Database_model->ln_fetch(array(
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
                    'message' => 'Email already in-use. Use another email or contact support for assisstance.',
                ));
            }
        }


        //Fetch existing email:
        $student_emails = $this->Database_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4255, //Emails are of type Text
            'ln_parent_entity_id' => 3288, //Email Address
        ));
        if (count($student_emails) > 0) {

            if (strlen($_POST['en_email']) == 0) {

                //Remove email:
                $this->Database_model->ln_update($student_emails[0]['ln_id'], array(
                    'ln_status' => -1,
                ), $_POST['en_id']);

                return echo_json(array(
                    'status' => 1,
                    'message' => 'Email removed',
                ));

            } elseif ($student_emails[0]['ln_content'] != $_POST['en_email']) {

                //Update if not duplicate:
                $this->Database_model->ln_update($student_emails[0]['ln_id'], array(
                    'ln_content' => $_POST['en_email'],
                ), $_POST['en_id']);

                return echo_json(array(
                    'status' => 1,
                    'message' => 'Email updated',
                ));

            }

        } elseif (strlen($_POST['en_email']) > 0) {

            //Create new link:
            $this->Database_model->ln_create(array(
                'ln_status' => 2, //Published
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 4255, //Emails are of type Text
                'ln_parent_entity_id' => 3288, //Email Address
                'ln_content' => $_POST['en_email'],
            ), true);

            return echo_json(array(
                'status' => 1,
                'message' => 'Email added',
            ));

        } else {

            return echo_json(array(
                'status' => 1,
                'message' => 'Nothing changed',
            ));

        }

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
        $student_passwords = $this->Database_model->ln_fetch(array(
            'ln_status' => 2,
            'ln_type_entity_id' => 4255, //Passwords are of type Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['en_password']));


        if (count($student_passwords) > 0) {

            if ($hashed_password == $student_passwords[0]['ln_content']) {
                return echo_json(array(
                    'status' => 1,
                    'message' => 'Nothing Updated',
                ));
            } else {
                //Update password:
                $this->Database_model->ln_update($student_passwords[0]['ln_id'], array(
                    'ln_content' => $hashed_password,
                ), $_POST['en_id']);

                return echo_json(array(
                    'status' => 1,
                    'message' => 'Password updated',
                ));
            }

        } else {

            //Create new link:
            $this->Database_model->ln_create(array(
                'ln_status' => 2,
                'ln_type_entity_id' => 4255, //Passwords are of type Text
                'ln_parent_entity_id' => 3286, //Password
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_content' => $hashed_password,
            ), true);

            return echo_json(array(
                'status' => 1,
                'message' => 'Password added',
            ));

        }

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
                    $domain_entity = $this->Matrix_model->en_sync_domain($social_url);
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
            $social_url_exists = $this->Database_model->ln_fetch(array(
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
                    $duplicates = $this->Database_model->ln_fetch(array(
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
                    $this->Database_model->ln_update($social_url_exists[0]['ln_id'], array(
                        'ln_content' => $social_url,
                    ), $_POST['en_id']);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' updated. ';

                } elseif(!$profile_set) {

                    //Remove profile:
                    $this->Database_model->ln_update($social_url_exists[0]['ln_id'], array(
                        'ln_status' => -1,
                    ), $_POST['en_id']);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' removed. ';

                } else {



                }

            } elseif ($profile_set) {

                //Create new link:
                $this->Database_model->ln_create(array(
                    'ln_status' => 2, //Published
                    'ln_miner_entity_id' => $_POST['en_id'],
                    'ln_child_entity_id' => $_POST['en_id'],
                    'ln_type_entity_id' => 4256, //Generic URL
                    'ln_parent_entity_id' => $social_en_id,
                    'ln_content' => $social_url,
                ), true);

                $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' added. ';

            }
        }


        //All good, return combined success messages:
        return echo_json(array(
            'status' => 1,
            'message' => ( strlen($success_messages) > 0 ? $success_messages : 'Nothing changed.'),
        ));

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

    function actionplan_load($psid, $in_id = 0)
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
            $session_en = $this->Matrix_model->en_student_messenger_authenticate($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }

        //Show appropriate UI:
        if ($in_id < 1) {

            //Log Action Plan View:
            $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 4283, //Opened Action Plan
                'ln_miner_entity_id' => $session_en['en_id'],
            ));

            //List all student intentions:
            $this->load->view('view_messenger/actionplan_all.php', array(
                'session_en' => $session_en,
                'student_intents' => $this->Database_model->ln_fetch(array(
                    'ln_miner_entity_id' => $session_en['en_id'],
                    'ln_type_entity_id' => 4235, //Student Intents
                    'ln_status >=' => 0, //New+
                    'in_status' => 2, //Published
                ), array('in_child'), 0, 0, array('ln_order' => 'ASC')),
            ));

        } else {

            //Fetch/validate selected intent:
            $ins = $this->Database_model->in_fetch(array(
                'in_id' => $in_id,
            ), array('in__parents', 'in__children'));
            if (count($ins) < 1) {
                die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
            } elseif ($ins[0]['in_status'] != 2) {
                die('<div class="alert alert-danger" role="alert">Intent cannot be accessed because it is not published.</div>');
            }

            //Log Action Plan View:
            $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 4283, //Opened Action Plan
                'ln_miner_entity_id' => $session_en['en_id'],
                'ln_child_intent_id' => $in_id,
            ));

            //Load Action Plan UI:
            $this->load->view('view_messenger/actionplan_intent.php', array(
                'session_en' => $session_en,
                'in' => $ins[0],
                'actionplans' => $this->Database_model->ln_fetch(array(
                    'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6107')) . ')' => null, //Student Action Plan
                    'ln_miner_entity_id' => $session_en['en_id'],
                    'ln_child_intent_id' => $in_id,
                    'ln_status >=' => 0, //New+
                ), array('in_child')),
            ));

        }
    }


    function actionplan_update_step()
    {

        /*
         *
         * Main function called when students mark a step
         * as complete via the Messenger Webview OR via
         * the web-based Action Plan.
         *
         * */

        //Validate input variables:
        if (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1 || !isset($_POST['ln_content'])) {
            return redirect_message('/messenger/actionplan', '<div class="alert alert-danger" role="alert">Error: Missing Core Data.</div>');
        }

        //Fetch/validate Completed Step:
        $actionplan_steps = $this->Database_model->ln_fetch(array(
            'ln_id' => $_POST['ln_id'],
            'ln_type_entity_id' => 4559, //Completed Step
        ), array('in_child'));
        if (count($actionplan_steps) < 1) {
            return redirect_message('/messenger/actionplan', '<div class="alert alert-danger" role="alert">Error: Completed Step not found.</div>');
        }

        //Set some variables:
        $session_en = $this->session->userdata('user');
        $step_url = '/messenger/actionplan/' . $actionplan_steps[0]['ln_child_intent_id'];
        $step_is_incomplete = ($actionplan_steps[0]['ln_status'] < 2);
        $completion_notes_added = (strlen($_POST['ln_content']) > 0);
        if (isset($session_en['en_id']) && $session_en['en_id'] != $actionplan_steps[0]['ln_miner_entity_id']) {
            //This seems to be a Mench miner/moderator that is NOT the Action Plan Student:
            return redirect_message($step_url, '<div class="alert alert-info" role="alert">Note: You do not seem to be the Action Plan Student so you cannot submit changes.</div>');
        } elseif (!$completion_notes_added && !$step_is_incomplete) {
            //Nothing to be done, return error:
            return redirect_message($step_url, '<div class="alert alert-info" role="alert">Note: Nothing saved as nothing was changed.</div>');
        }

        //TODO Check to see intent has been un-published since it was added to student Action Plan? See Github issue #2226

        if ($completion_notes_added) {
            //Validate submission:
            $detected_ln_type = detect_ln_type_entity_id($_POST['ln_content']);
            if (!$detected_ln_type['status']) {
                return redirect_message('/messenger/actionplan', '<div class="alert alert-danger" role="alert">Error: ' . $detected_ln_type['message'] . '</div>');
            }
        }

        //Validate intent completion requirement and see if Student meets it:
        $message_in_requirements = $this->Matrix_model->in_req_completion($actionplan_steps[0]);
        if ($message_in_requirements && $actionplan_steps[0]['in_requirement_entity_id'] != $detected_ln_type['ln_type_entity_id']) {
            //Nope, it does not seem that the submission meets the requirements:
            return redirect_message($step_url, '<div class="alert alert-danger" role="alert">Error: ' . $message_in_requirements . '</div>');
        }


        if ($completion_notes_added) {
            //Save notes:
            $this->Database_model->ln_create(array(
                'ln_parent_link_id' => $actionplan_steps[0]['ln_id'], //Submission for Completed Step
                'ln_miner_entity_id' => $actionplan_steps[0]['ln_miner_entity_id'],
                'ln_status' => 2, //Published
                'ln_content' => trim($_POST['ln_content']),
                'ln_type_entity_id' => $detected_ln_type['ln_type_entity_id'],
                'ln_parent_intent_id' => $actionplan_steps[0]['ln_child_intent_id'],
                'ln_order' => 1 + $this->Database_model->ln_max_order(array(
                        'ln_parent_link_id' => $actionplan_steps[0]['ln_id'],
                    )),
            ));
        }

        if ($step_is_incomplete) {
            //Also update ln_status, determine what it should be:
            $this->Matrix_model->actionplan_complete_recursive_up($actionplan_steps[0]);
        }


        //Redirect back to page with success message:
        if (isset($_POST['fetch_next_step'])) {
            //Go to next item:
            $next_ins = $this->Matrix_model->actionplan_fetch_next($actionplan_steps[0]['ln_parent_link_id']);
            if ($next_ins) {
                //Override original item:
                $step_url = '/messenger/actionplan/' . $next_ins[0]['in_id'];
            }
        }

        return redirect_message($step_url, '<div class="alert alert-success" role="alert"><i class="fal fa-check-circle"></i> Successfully Saved</div>');
    }


    function actionplan_skip_step($ln_id, $apply_skip)
    {

        //Fetch/validate Completed Step:
        $actionplan_steps = $this->Database_model->ln_fetch(array(
            'ln_id' => $ln_id,
            'ln_type_entity_id' => 4559, //Completed Step
            'ln_status >=' => 0, //New+
        ));

        if (count($actionplan_steps) < 1) {
            //Ooooopsi, could not find it:
            $this->Database_model->ln_create(array(
                'ln_parent_link_id' => $ln_id,
                'ln_content' => 'actionplan_skip_recursive_down() failed to locate step [Apply: ' . ($apply_skip ? 'YES' : 'NO') . ']',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));

            return false;
        }

        //Run skip function
        $total_skipped = count($this->Matrix_model->actionplan_skip_recursive_down($ln_id, $apply_skip));

        if (!$apply_skip) {

            //Just give count on total steps so student can review/confirm:
            return echo_json(array(
                'step_count' => $total_skipped,
            ));

        } else {

            //We actually skipped, draft message:
            $message = '<div class="alert alert-success" role="alert">Successfully skipped ' . $total_skipped . ' step' . echo__s($total_skipped) . '.</div>';

            //Find the next item to navigate them to:
            $next_ins = $this->Matrix_model->actionplan_fetch_next($actionplan_steps[0]['ln_parent_link_id']);
            if ($next_ins) {
                return redirect_message('/messenger/actionplan/' . $next_ins[0]['in_id'], $message);
            } else {
                return redirect_message('/messenger/actionplan', $message);
            }
        }
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


        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => 'Intents sorted',
        ));
    }

    function actionplan_choose_step($actionplan_ln_id, $actionplan_in_id, $in_append_id, $w_key)
    {

        if ($w_key != md5($this->config->item('actionplan_salt') . $in_append_id . $actionplan_in_id . $actionplan_ln_id)) {
            return redirect_message('/messenger/actionplan/' . $actionplan_in_id, '<div class="alert alert-danger" role="alert">Invalid Secret Key</div>');
        }

        //Validate Action Plan:
        $actionplan_steps = $this->Database_model->ln_fetch(array(
            'ln_parent_link_id' => $actionplan_ln_id,
            'ln_child_intent_id' => $actionplan_in_id,
            'ln_type_entity_id' => 4559, //Completed Step
            'ln_status >=' => 0, //New+
        ));
        if (count($actionplan_steps) < 1) {
            return redirect_message('/messenger/actionplan/' . $actionplan_in_id, '<div class="alert alert-danger" role="alert">Step Not Found</div>');
        }

        if ($this->Matrix_model->actionplan_append_in($in_append_id, $actionplan_steps[0]['ln_miner_entity_id'], $actionplan_in_id)) {
            return redirect_message('/messenger/actionplan/' . $in_append_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');
        } else {
            //We had some sort of an error:
            return redirect_message('/messenger/actionplan/' . $actionplan_in_id, '<div class="alert alert-danger" role="alert">There was an error saving your answer.</div>');
        }
    }


    function test($in_id = 7463, $add_actionplan = 1, $direction_is_downward = 1)
    {

        if ($add_actionplan) {
            $actionplan = $this->Database_model->ln_create(array(
                'ln_type_entity_id' => 4235, //Student Intent
                'ln_status' => 0, //New
                'ln_miner_entity_id' => 1,
                'ln_child_intent_id' => $in_id, //The Intent they are adding
                'ln_order' => 1 + $this->Database_model->ln_max_order(array( //Place this intent at the end of all intents the Student is drafting...
                        'ln_type_entity_id' => 4235, //Student Intent
                        'ln_status >=' => 0, //New+
                        'ln_miner_entity_id' => 1, //Belongs to this Student
                    )),
            ));
        } else {
            $actionplan = array();
        }

        echo_json($this->Matrix_model->in_fetch_recursive($in_id, $direction_is_downward, false, $actionplan));
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
        $ln_pending = $this->Database_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', array_keys($fb_convert_4537)) . ')' => null,
            'ln_status' => 2, //Publish
            'ln_metadata' => null, //Missing Facebook Attachment ID [NOTE: Must make sure ln_metadata is not used for anything else for these link types]
        ), array(), 10, 0, array('ln_id' => 'ASC')); //Sort by oldest added first


        //Put something in the ln_metadata so other cron jobs do not pick  up on it:
        foreach ($ln_pending as $ln) {
            $this->Matrix_model->metadata_single_update('ln', $ln['ln_id'], array(
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
            $result = $this->Chat_model->facebook_graph('POST', '/me/message_attachments', $payload);

            if (isset($result['ln_metadata']['result']['attachment_id']) && $result['status']) {

                //Save Facebook Attachment ID to DB:
                $db_result = $this->Matrix_model->metadata_single_update('ln', $ln['ln_id'], array(
                    'fb_att_id' => intval($result['ln_metadata']['result']['attachment_id']),
                ));

            }

            //Did it go well?
            if ($db_result) {

                $success_count++;

            } else {

                //Log error:
                $this->Database_model->ln_create(array(
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

        $ln_pending = $this->Database_model->ln_fetch(array(
            'ln_status' => 0, //New
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6102')) . ')' => null, //Student Sent/Received Media Links
        ), array(), 20);

        //Set link statuses to drafting so other Cron jobs don't pick them up:
        $this->Matrix_model->trs_set_drafting($ln_pending);

        $counter = 0;
        foreach ($ln_pending as $ln) {

            //Store to CDN:
            $new_file_url = upload_to_cdn($ln['ln_content'], $ln);

            if ($new_file_url && filter_var($new_file_url, FILTER_VALIDATE_URL)) {

                //Update link:
                $this->Database_model->ln_update($ln['ln_id'], array(
                    'ln_content' => $new_file_url,
                    'ln_status' => 2,
                ));

                //Increase counter:
                $counter++;

            } else {

                //Log error:
                $this->Database_model->ln_create(array(
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

        $ln_pending = $this->Database_model->ln_fetch(array(
            'ln_status' => 0, //New
            'ln_type_entity_id' => 4299, //Updated Profile Picture
        ), array('en_miner'), 20); //Max number of scans per run


        //Set link statuses to drafting so other Cron jobs don't pick them up:
        $this->Matrix_model->trs_set_drafting($ln_pending);

        //Now go through and upload to CDN:
        foreach ($ln_pending as $ln) {

            //Save photo to S3 if content is URL
            $new_file_url = (filter_var($ln['ln_content'], FILTER_VALIDATE_URL) ? upload_to_cdn($ln['ln_content'], $ln) : false);

            if (!$new_file_url) {

                //Ooopsi, there was an error:
                $this->Database_model->ln_create(array(
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
                $this->Database_model->en_update($ln['en_id'], array(
                    'en_icon' => '<img src="' . $new_file_url . '">',
                ), true, $ln['en_id']);

                //Link link to entity:
                $ln_child_entity_id = $ln['en_id'];

            }

            //Update link:
            $this->Database_model->ln_update($ln['ln_id'], array(
                'ln_status' => 2, //Publish
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