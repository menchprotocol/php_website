<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }


    function sync_menu()
    {

        $res = array();
        array_push($res, $this->Chat_model->fn___facebook_graph('POST', '/me/messenger_profile', array(
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

        array_push($res, $this->Chat_model->fn___facebook_graph('POST', '/me/messenger_profile', array(
            'persistent_menu' => array(
                array(
                    'locale' => 'default',
                    'composer_input_disabled' => false,
                    'disabled_surfaces' => array('CUSTOMER_CHAT_PLUGIN'),
                    'call_to_actions' => array(
                        array(
                            'title' => '🚩 Action Plan',
                            'type' => 'web_url',
                            'url' => 'https://mench.com/my/actionplan',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                    ),
                ),
            ),
        )));

        fn___echo_json($res);
    }


    function facebook_webhook()
    {

        /*
         *
         * The master function for all Facebook webhook calls
         *
         * */

        //Facebook Webhook Authentication:
        $challenge = (isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : null);
        $verify_token = (isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : null);
        $fb_settings = $this->config->item('fb_settings');

        if ($verify_token == '722bb4e2bac428aa697cc97a605b2c5a') {
            echo $challenge;
        }

        //Fetch input data:
        $json_data = json_decode(file_get_contents('php://input'), true);

        //This is for local testing only:
        //$json_data = fn___objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1505007977668,"messaging":[{"sender":{"id":"1443101719058431"},"recipient":{"id":"381488558920384"},"timestamp":1505007977521,"message":{"mid":"mid.$cAAFa9hmVoehkmryMMVeaXdGIY9x5","seq":19898,"text":"Yes"}}]}]}'));


        //Do some basic checks:
        if (!isset($json_data['object']) || !isset($json_data['entry'])) {
            $this->Database_model->tr_create(array(
                'tr_content' => 'facebook_webhook() Function missing either [object] or [entry] variable.',
                'tr_metadata' => $json_data,
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        } elseif (!$json_data['object'] == 'page') {
            $this->Database_model->tr_create(array(
                'tr_content' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'tr_metadata' => $json_data,
                'tr_en_type_id' => 4246, //Platform Error
            ));
            return false;
        }


        //Loop through entries:
        foreach ($json_data['entry'] as $entry) {

            //check the page ID:
            if (!isset($entry['id']) || !($entry['id'] == $fb_settings['page_id'])) {
                //This can happen for the older webhook that we offered to other FB pages:
                continue;
            } elseif (!isset($entry['messaging'])) {
                $this->Database_model->tr_create(array(
                    'tr_content' => 'facebook_webhook() call missing messaging Array().',
                    'tr_metadata' => $json_data,
                    'tr_en_type_id' => 4246, //Platform Error
                ));
                continue;
            }

            //loop though the messages:
            foreach ($entry['messaging'] as $im) {

                if (isset($im['read'])) {

                    //TODO Only log IF last read engagement was 5+ minutes ago

                    $en = $this->Matrix_model->fn___authenticate_messenger_user($im['sender']['id']);

                    //This callback will occur when a message a page has sent has been read by the user.
                    $this->Database_model->tr_create(array(
                        'tr_metadata' => $json_data,
                        'tr_en_type_id' => 4278, //Message Read
                        'tr_en_credit_id' => (isset($en['en_id']) ? $en['en_id'] : 0),
                        'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time

                    ));

                } elseif (isset($im['delivery'])) {

                    //TODO Only log IF last delivery engagement was 5+ minutes ago

                    $en = $this->Matrix_model->fn___authenticate_messenger_user($im['sender']['id']);

                    //This callback will occur when a message a page has sent has been delivered.
                    $this->Database_model->tr_create(array(
                        'tr_metadata' => $json_data,
                        'tr_en_type_id' => 4279, //Message Delivered
                        'tr_en_credit_id' => (isset($en['en_id']) ? $en['en_id'] : 0),
                        'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                } elseif (isset($im['referral']) || isset($im['postback'])) {

                    /*
                     * Simple difference:
                     *
                     * Handle the messaging_postbacks event for new conversations
                     * Handle the messaging_referrals event for existing conversations
                     *
                     * */

                    if (isset($im['postback'])) {

                        //The payload field passed is defined in the above places.
                        $payload = $im['postback']['payload']; //Maybe do something with this later?

                        if (isset($im['postback']['referral']) && count($im['postback']['referral']) > 0) {

                            $referral_array = $im['postback']['referral'];

                        } elseif ($payload == 'GET_STARTED') {

                            //The very first payload, set defaults:
                            $referral_array = array(
                                'ref' => 'ACTIONPLAN-ADD-INITIATE_' . $this->config->item('in_primary_id'),
                            );

                        } else {
                            //Postback without referral!
                            $referral_array = null;
                        }

                    } elseif (isset($im['referral'])) {

                        $referral_array = $im['referral'];

                    }

                    //Did we have a ref from Messenger?
                    $ref = ($referral_array && isset($referral_array['ref']) && strlen($referral_array['ref']) > 0 ? $referral_array['ref'] : null);

                    $en = $this->Matrix_model->fn___authenticate_messenger_user($im['sender']['id']);

                    /*
                    if($ref){
                        //We have referrer data, see what this is all about!
                        //We expect an integer which is the challenge ID
                        $ref_source = $referral_array['source'];
                        $ref_type = $referral_array['type'];
                        $ad_id = ( isset($referral_array['ad_id']) ? $referral_array['ad_id'] : null ); //Only IF user comes from the Ad

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

                    //Log primary engagement:
                    $this->Database_model->tr_create(array(
                        'tr_en_type_id' => (isset($im['referral']) ? 4267 : 4268), //Messenger Referral/Postback
                        'tr_metadata' => $json_data,
                        'tr_en_credit_id' => (isset($en['en_id']) ? $en['en_id'] : 0),
                        'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));


                    //We might need to respond based on the reference:
                    $this->Chat_model->digest_quick_reply_payload($en, $ref);


                } elseif (isset($im['optin'])) {

                    $en = $this->Matrix_model->fn___authenticate_messenger_user($im['sender']['id']);

                    //Note: Never seen this happen yet!
                    //Log transaction:
                    $this->Database_model->tr_create(array(
                        'tr_metadata' => $json_data,
                        'tr_en_type_id' => 4266, //Messenger Optin
                        'tr_en_credit_id' => (isset($en['en_id']) ? $en['en_id'] : 0),
                        'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->Matrix_model->fn___authenticate_messenger_user($im['sender']['id']);

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
                        return fn___echo_json(array('is_logged_already' => 1));

                    }


                    //Set variables:
                    unset($eng_data); //Reset everything in case its set from the previous loop!
                    $sent_by_mench = (isset($im['message']['is_echo'])); //Indicates the message sent from the page itself
                    $en = $this->Matrix_model->fn___authenticate_messenger_user(($sent_by_mench ? $im['recipient']['id'] : $im['sender']['id']));
                    $eng_data = array(
                        'tr_en_credit_id' => ($sent_by_mench ? 4148 /* Mench Admins via Facebook Inbox UI */ : $en['en_id']),
                        'tr_en_child_id' => ($sent_by_mench ? $en['en_id'] : 0),
                        'tr_timestamp' => ($sent_by_mench ? null : fn___echo_time_milliseconds($im['timestamp']) ), //Facebook time if received from Master
                        'tr_metadata' => $json_data, //Entire JSON object received by Facebook API
                    );

                    /*
                     *
                     * Now complete the transaction data based on message type.
                     * We will generally receive 3 types of Facebook Messages:
                     *
                     * - Quick Replies
                     * - Text Messages
                     * - Attachments
                     *
                     * And we will deal with each group, and their sub-group
                     * appropriately based on who sent the message (Mench/Master)
                     *
                     * */

                    if(isset($im['message']['quick_reply']['payload'])){

                        //Quick Reply Answer Received (Cannot be sent as we never send quick replies)
                        $eng_data['tr_en_type_id'] = 4460;
                        $eng_data['tr_content'] = $im['message']['text']; //Quick reply always has a text

                        //Digest the Quick Reply payload:
                        $this->Chat_model->digest_quick_reply_payload($en, $im['message']['quick_reply']['payload']);

                    } elseif(isset($im['message']['text'])){

                        //Set message content:
                        $eng_data['tr_content'] = $im['message']['text'];

                        //Who sent this?
                        if ($sent_by_mench) {

                            //Text Message Sent (By Mench Admin via Facebook Inbox UI)
                            $eng_data['tr_en_type_id'] = 4552;

                        } else {

                            //Text Message Received:
                            $eng_data['tr_en_type_id'] = 4547;

                            //Digest message & try to make sense of it:
                            $this->Chat_model->fn___digest_message($en, $im['message']['text']);

                        }

                    } elseif (isset($im['message']['attachments'])) {

                        //We have some attachments, lets loops through them:
                        foreach ($im['message']['attachments'] as $att) {

                            //Define 4 main Attachment Message Types:
                            $att_media_types = array(
                                'video' => array(
                                    'sent' => 4553,
                                    'received' => 4548,
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
                                 * The solution is to create a @4299 transaction to save
                                 * this attachment using a cron job later on.
                                 *
                                 * */

                                $eng_data['tr_en_type_id'] = $att_media_types[$att['type']][( $sent_by_mench ? 'sent' : 'received' )];
                                $eng_data['tr_content'] = $att['payload']['url']; //Media Attachment Temporary Facebook URL
                                $eng_data['tr_status'] = 0; //Temporary Facebook URL to be uploaded to Mench CDN via Cron Job

                            } elseif ($att['type'] == 'location') {

                                //Location Message Received:
                                $eng_data['tr_en_type_id'] = 4557;

                                /*
                                 *
                                 * We do not have the ability to send this
                                 * type of message at this time and we will
                                 * only receive it if the Master decides to
                                 * send us their location for some reason.
                                 *
                                 * Message with location attachment which
                                 * could have up to 4 main elements:
                                 *
                                 * */

                                $loc_lat = $att['payload']['coordinates']['lat'];
                                $loc_long = $att['payload']['coordinates']['long'];
                                $loc_title = ( isset($att['title']) && strlen($att['title'])>0 ? $att['title'] : null );
                                $loc_url = ( isset($att['url']) && strlen($att['url'])>0 ? $att['url'] : null );

                                //Construct the body based on these 4 elements:
                                $eng_data['tr_content'] = 'Location Lat/Lng is: ' . $loc_lat . ',' . $loc_long;

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
                    if(isset($eng_data['tr_en_type_id'])){

                        //We're all good, log this message:
                        $this->Database_model->tr_create($eng_data);

                    } else {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->Database_model->tr_create(array(
                            'tr_en_type_id' => 4246, //Platform Error
                            'tr_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details.',
                            'tr_metadata' => $json_data,
                            'tr_en_child_id' => $en['en_id'],
                        ));

                    }


                } else {

                    //This should really not happen!
                    $this->Database_model->tr_create(array(
                        'tr_content' => 'facebook_webhook() received unrecognized webhook call.',
                        'tr_metadata' => $json_data,
                        'tr_en_type_id' => 4246, //Platform Error
                    ));

                }
            }
        }
    }

}