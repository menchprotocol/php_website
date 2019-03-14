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


    function webhook()
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

        //We need this only for the first time to authenticate that we own the server:
        if ($verify_token == '722bb4e2bac428aa697cc97a605b2c5a') {
            echo $challenge;
        }

        //Fetch input data:
        $json_data = json_decode(file_get_contents('php://input'), true);

        //This is for local testing only:
        //$json_data = fn___objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1505007977668,"messaging":[{"sender":{"id":"1443101719058431"},"recipient":{"id":"381488558920384"},"timestamp":1505007977521,"message":{"mid":"mid.$cAAFa9hmVoehkmryMMVeaXdGIY9x5","seq":19898,"text":"Yes"}}]}]}'));


        //Do some basic checks:
        if (!isset($json_data['object']) || !isset($json_data['entry'])) {
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'facebook_webhook() Function missing either [object] or [entry] variable.',
                'tr_metadata' => $json_data,
                'tr_type_entity_id' => 4246, //Platform Error
            ));
            return false;
        } elseif (!$json_data['object'] == 'page') {
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'tr_metadata' => $json_data,
                'tr_type_entity_id' => 4246, //Platform Error
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
                $this->Database_model->fn___tr_create(array(
                    'tr_content' => 'facebook_webhook() call missing messaging Array().',
                    'tr_metadata' => $json_data,
                    'tr_type_entity_id' => 4246, //Platform Error
                ));
                continue;
            }

            //loop though the messages:
            foreach ($entry['messaging'] as $im) {

                if (isset($im['read']) || isset($im['delivery'])) {

                    //Message read OR delivered
                    $tr_type_entity_id = ( isset($im['delivery']) ? 4279 /* Message Delivered */ : 4278 /* Message Read */ );

                    //Authenticate Student:
                    $en = $this->Matrix_model->fn___en_student_messenger_authenticate($im['sender']['id']);

                    //Log Transaction Only IF last delivery transaction was 3+ minutes ago (Since Facebook sends many of these):
                    $last_trs_logged = $this->Database_model->fn___tr_fetch(array(
                        'tr_type_entity_id' => $tr_type_entity_id,
                        'tr_parent_entity_id' => $en['en_id'],
                        'tr_timestamp >=' => date("Y-m-d H:i:s", (time() - (180))), //Transactions logged less than 3 minutes ago
                    ), array(), 1);

                    if(count($last_trs_logged) == 0){
                        //We had no recent transactions of this kind, so go ahead and log:
                        $this->Database_model->fn___tr_create(array(
                            'tr_metadata' => $json_data,
                            'tr_type_entity_id' => $tr_type_entity_id,
                            'tr_miner_entity_id' => $en['en_id'],
                            'tr_parent_entity_id' => $en['en_id'],
                            'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time
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
                    $tr_type_entity_id = ( isset($im['delivery']) ? 4267 /* Messenger Referral */ : 4268 /* Messenger Postback */ );

                    //Authenticate Student:
                    $en = $this->Matrix_model->fn___en_student_messenger_authenticate($im['sender']['id']);

                    //Extract more insights:
                    if (isset($im['postback'])) {

                        //The payload field passed is defined in the above places.
                        $payload = $im['postback']['payload']; //Maybe do something with this later?

                        if (isset($im['postback']['referral']) && count($im['postback']['referral']) > 0) {

                            $array_ref = $im['postback']['referral'];

                        } elseif ($payload == 'GET_STARTED') {

                            //The very first payload, set defaults:
                            $array_ref = array(
                                'ref' => $this->config->item('in_home_page'),
                            );

                        } else {
                            //Postback without referral!
                            $array_ref = null;
                        }

                    } elseif (isset($im['referral'])) {

                        $array_ref = $im['referral'];

                    }

                    //Did we have a ref from Messenger?
                    $quick_reply_payload = ($array_ref && isset($array_ref['ref']) && strlen($array_ref['ref']) > 0 ? $array_ref['ref'] : null);

                    //Log primary transaction:
                    $this->Database_model->fn___tr_create(array(
                        'tr_type_entity_id' => $tr_type_entity_id,
                        'tr_metadata' => $json_data,
                        'tr_content' => $quick_reply_payload,
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_parent_entity_id' => $en['en_id'],
                        'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                    //Digest quick reply Payload if any:
                    if($quick_reply_payload){
                        $this->Chat_model->fn___digest_received_quick_reply($en, $quick_reply_payload);
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

                    $en = $this->Matrix_model->fn___en_student_messenger_authenticate($im['sender']['id']);

                    //Log transaction:
                    $this->Database_model->fn___tr_create(array(
                        'tr_metadata' => $json_data,
                        'tr_type_entity_id' => 4266, //Messenger Optin
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_parent_entity_id' => $en['en_id'],
                        'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->Matrix_model->fn___en_student_messenger_authenticate($im['sender']['id']);

                    //Log transaction:
                    $this->Database_model->fn___tr_create(array(
                        'tr_metadata' => $json_data,
                        'tr_type_entity_id' => 4577, //Message Request Accepted
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_parent_entity_id' => $en['en_id'],
                        'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time
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
                        return fn___echo_json(array('is_logged_already' => 1));

                    }


                    //Set variables:
                    unset($tr_data); //Reset everything in case its set from the previous loop!
                    $sent_by_mench = (isset($im['message']['is_echo'])); //Indicates the message sent from the page itself
                    $en = $this->Matrix_model->fn___en_student_messenger_authenticate(($sent_by_mench ? $im['recipient']['id'] : $im['sender']['id']));
                    $tr_parent_entity_id = ($sent_by_mench ? 4148 /* Mench Admins via Facebook Inbox UI */ : $en['en_id']);
                    
                    $tr_data = array(
                        'tr_miner_entity_id' => $tr_parent_entity_id,
                        'tr_parent_entity_id' => $tr_parent_entity_id,
                        'tr_child_entity_id' => ($sent_by_mench ? $en['en_id'] : 0),
                        'tr_timestamp' => ($sent_by_mench ? null : fn___echo_time_milliseconds($im['timestamp']) ), //Facebook time if received from Student
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
                     * appropriately based on who sent the message (Mench/Student)
                     *
                     * */

                    if(isset($im['message']['quick_reply']['payload'])){

                        //Quick Reply Answer Received (Cannot be sent as we never send quick replies)
                        $tr_data['tr_type_entity_id'] = 4460;
                        $tr_data['tr_content'] = $im['message']['text']; //Quick reply always has a text

                        //Digest the Quick Reply payload:
                        $this->Chat_model->fn___digest_received_quick_reply($en, $im['message']['quick_reply']['payload']);

                    } elseif(isset($im['message']['text'])){

                        //Set message content:
                        $tr_data['tr_content'] = $im['message']['text'];

                        //Who sent this?
                        if ($sent_by_mench) {

                            //Text Message Sent By Mench Admin via Facebook Inbox UI
                            $tr_data['tr_type_entity_id'] = 4552; //Text Message Sent

                        } else {

                            //Text Message Received:
                            $tr_data['tr_type_entity_id'] = 4547;

                            //Digest message & try to make sense of it:
                            $this->Chat_model->fn___digest_received_message($en, $im['message']['text']);

                        }

                    } elseif (isset($im['message']['attachments'])) {

                        //We have some attachments, lets loops through them:
                        foreach ($im['message']['attachments'] as $att) {

                            //Define 4 main Attachment Message Types:
                            $att_media_types = array( //Converts video, audio, image and file messages
                                'video' => array(
                                    'sent' => 4553,     //Transaction type for when sent to Students via Messenger
                                    'received' => 4548, //Transaction type for when received from Students via Messenger
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

                                $tr_data['tr_type_entity_id'] = $att_media_types[$att['type']][( $sent_by_mench ? 'sent' : 'received' )];
                                $tr_data['tr_content'] = $att['payload']['url']; //Media Attachment Temporary Facebook URL
                                $tr_data['tr_status'] = 0; //Working On, since URL needs to be uploaded to Mench CDN via Cron Job

                            } elseif ($att['type'] == 'location') {

                                //Location Message Received:
                                $tr_data['tr_type_entity_id'] = 4557;

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
                                if(isset($att['url']) && strlen($att['url'])>0 ){
                                    //Sometimes Facebook Might provide a full URL:
                                    $tr_data['tr_content'] = $att['url'];
                                } else {
                                    //If not, we can generate our own URL using the Lat/Lng that will always be provided:
                                    $tr_data['tr_content'] = 'https://www.google.com/maps?q='.$att['payload']['coordinates']['lat'].'+'.$att['payload']['coordinates']['long'];
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
                    if(isset($tr_data['tr_type_entity_id'])){

                        //We're all good, log this message:
                        $this->Database_model->fn___tr_create($tr_data);

                    } else {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->Database_model->fn___tr_create(array(
                            'tr_type_entity_id' => 4246, //Platform Error
                            'tr_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details',
                            'tr_metadata' => $json_data,
                            'tr_parent_entity_id' => $en['en_id'],
                        ));

                    }

                } else {

                    //This should really not happen!
                    $this->Database_model->fn___tr_create(array(
                        'tr_content' => 'facebook_webhook() received unrecognized webhook call',
                        'tr_metadata' => $json_data,
                        'tr_type_entity_id' => 4246, //Platform Error
                    ));

                }
            }
        }
    }

    function sync_menu()
    {

        /*
         * A function that will sync the fixed
         * menu of Mench's Facebook Messenger.
         *
         * */

        //Let's first give permission to our pages to do so:
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

        //Now let's update the menu:
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

        //Show results:
        fn___echo_json($res);
    }


}