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
        $tr_metadata = json_decode(file_get_contents('php://input'), true);

        //This is for local testing only:
        //$tr_metadata = fn___objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1505007977668,"messaging":[{"sender":{"id":"1443101719058431"},"recipient":{"id":"381488558920384"},"timestamp":1505007977521,"message":{"mid":"mid.$cAAFa9hmVoehkmryMMVeaXdGIY9x5","seq":19898,"text":"Yes"}}]}]}'));


        //Do some basic checks:
        if (!isset($tr_metadata['object']) || !isset($tr_metadata['entry'])) {
            //Likely loaded the URL in browser:
            return false;
        } elseif (!$tr_metadata['object'] == 'page') {
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'tr_metadata' => $tr_metadata,
                'tr_type_entity_id' => 4246, //Platform Error
                'tr_miner_entity_id' => 1, //Shervin/Developer
            ));
            return false;
        }


        //Loop through entries:
        foreach ($tr_metadata['entry'] as $entry) {

            //check the page ID:
            if (!isset($entry['id']) || !($entry['id'] == $fb_settings['page_id'])) {
                //This can happen for the older webhook that we offered to other FB pages:
                continue;
            } elseif (!isset($entry['messaging'])) {
                $this->Database_model->fn___tr_create(array(
                    'tr_content' => 'facebook_webhook() call missing messaging Array().',
                    'tr_metadata' => $tr_metadata,
                    'tr_type_entity_id' => 4246, //Platform Error
                    'tr_miner_entity_id' => 1, //Shervin/Developer
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
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_timestamp >=' => date("Y-m-d H:i:s", (time() - (180))), //Transactions logged less than 3 minutes ago
                    ), array(), 1);

                    if(count($last_trs_logged) == 0){
                        //We had no recent transactions of this kind, so go ahead and log:
                        $this->Database_model->fn___tr_create(array(
                            'tr_metadata' => $tr_metadata,
                            'tr_type_entity_id' => $tr_type_entity_id,
                            'tr_miner_entity_id' => $en['en_id'],
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

                    //Log primary transaction:
                    $this->Database_model->fn___tr_create(array(
                        'tr_type_entity_id' => $tr_type_entity_id,
                        'tr_metadata' => $tr_metadata,
                        'tr_content' => $quick_reply_payload,
                        'tr_miner_entity_id' => $en['en_id'],
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
                        'tr_metadata' => $tr_metadata,
                        'tr_type_entity_id' => 4266, //Messenger Optin
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_timestamp' => fn___echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->Matrix_model->fn___en_student_messenger_authenticate($im['sender']['id']);

                    //Log transaction:
                    $this->Database_model->fn___tr_create(array(
                        'tr_metadata' => $tr_metadata,
                        'tr_type_entity_id' => 4577, //Message Request Accepted
                        'tr_miner_entity_id' => $en['en_id'],
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

                    $tr_data = array(
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_timestamp' => ($sent_by_mench ? null : fn___echo_time_milliseconds($im['timestamp']) ), //Facebook time if received from Student
                        'tr_metadata' => $tr_metadata, //Entire JSON object received by Facebook API
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

                            $tr_data['tr_type_entity_id'] = 4547; //Text Message Received

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
                                $tr_data['tr_status'] = 0; //drafting, since URL needs to be uploaded to Mench CDN via cron__save_chat_media()

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
                    if(isset($tr_data['tr_type_entity_id']) && isset($tr_data['tr_miner_entity_id'])){

                        //We're all good, log this message:
                        $this->Database_model->fn___tr_create($tr_data);

                    } else {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->Database_model->fn___tr_create(array(
                            'tr_type_entity_id' => 4246, //Platform Error
                            'tr_miner_entity_id' => 1, //Shervin/Developer
                            'tr_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details',
                            'tr_metadata' => $tr_metadata,
                        ));

                    }

                } else {

                    //This should really not happen!
                    $this->Database_model->fn___tr_create(array(
                        'tr_content' => 'facebook_webhook() received unrecognized webhook call',
                        'tr_metadata' => $tr_metadata,
                        'tr_type_entity_id' => 4246, //Platform Error
                        'tr_miner_entity_id' => 1, //Shervin/Developer
                    ));

                }
            }
        }
    }

    function fetch_profile($en_id)
    {

        //Only moderators can do this at this time:
        $session_en = fn___en_auth(array(1281));
        $current_us = $this->Database_model->fn___en_fetch(array(
            'en_id' => $en_id,
        ));

        if (!$session_en) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Sign In as a moderator and Try again.',
            ));
        } elseif (count($current_us) == 0) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'User not found!',
            ));
        } elseif (strlen($current_us[0]['en_psid']) < 10) {
            return fn___echo_json(array(
                'status' => 0,
                'message' => 'User does not seem to be connected to Mench, so profile data cannot be fetched',
            ));
        } else {

            //Fetch results and show:
            return fn___echo_json(array(
                'fb_profile' => $this->Chat_model->fn___facebook_graph('GET', '/'.$current_us[0]['en_psid'], array()),
                'en' => $current_us[0],
            ));

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





    function actionplan($actionplan_tr_id = 0, $in_id = 0)
    {

        $this->load->view('view_shared/messenger_header', array(
            'title' => '🚩 Action Plan',
        ));
        //include main body:
        $this->load->view('view_ledger/tr_actionplan_messenger_frame', array(
            'in_id' => $in_id,
            'actionplan_tr_id' => $actionplan_tr_id,
        ));
        $this->load->view('view_shared/messenger_footer');
    }

    function display_actionplan($psid, $actionplan_tr_id = 0, $in_id = 0)
    {

        //Get session data in case user is doing a browser login:
        $session_en = $this->session->userdata('user');
        $empty_session = (!isset($session_en['en__actionplans']) || count($session_en['en__actionplans']) < 1);
        $is_miner = fn___filter_array($session_en['en__parents'], 'en_id', 1308);

        //Authenticate user:
        if (!$psid && $empty_session && !$is_miner) {
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif ($empty_session && !fn___is_dev() && isset($_GET['sr']) && !fn___parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
        }

        if($empty_session && $psid > 0){
            //Authenticate this user:
            $session_en = $this->Matrix_model->fn___en_student_messenger_authenticate($psid);
        }

        //Set Action Plan filters:
        $filters = array();

        //Do we have a use session?
        if ($actionplan_tr_id > 0 && $in_id > 0) {
            //Yes! It seems to be a desktop login:
            $filters['tr_type_entity_id'] = 4559; //Action Plan Step
            $filters['tr_parent_transaction_id'] = $actionplan_tr_id;
            $filters['tr_child_intent_id'] = $in_id;
        } elseif (!$empty_session) {
            //Yes! It seems to be a desktop login (versus Facebook Messenger)
            $filters['tr_type_entity_id'] = 4235; //Action Plan Intent
            $filters['tr_miner_entity_id'] = $session_en['en_id'];
            $filters['tr_status >='] = 0;
        }

        //Try finding them:
        $trs = $this->Database_model->fn___tr_fetch($filters, array('in_child'));

        if (count($trs) < 1) {

            //No Action Plans found:
            die('<div class="alert alert-danger" role="alert">You have no active Action Plans yet.</div>');

        } elseif (count($trs) > 1) {

            //Determine Action Plan IDs if not provided:
            if(!$actionplan_tr_id || !$in_id){
                $actionplan_tr_id = ( $trs[0]['tr_parent_transaction_id'] == 0 ? $trs[0]['tr_id'] : $trs[0]['tr_parent_transaction_id'] );
                $in_id = $trs[0]['tr_child_intent_id'];
            }

            //Log action plan view transaction:
            $this->Database_model->fn___tr_create(array(
                'tr_type_entity_id' => 4283,
                'tr_miner_entity_id' => $trs[0]['tr_parent_entity_id'],
                'tr_parent_transaction_id' => $actionplan_tr_id,
                'tr_child_intent_id' => $in_id,
            ));

            if(count($trs) > 1) {

                //Student has multiple Action Plans, so list all Action Plans to enable Student to choose:
                echo '<h3 class="master-h3 primary-title">My Action Plan</h3>';
                echo '<div class="list-group" style="margin-top: 10px;">';
                foreach ($trs as $tr) {
                    //Prepare metadata:
                    $metadata = unserialize($tr['in_metadata']);
                    //Display row:
                    echo '<a href="/my/actionplan/' . $tr['tr_id'] . '/' . $tr['tr_child_intent_id'] . '" class="list-group-item">';
                    echo '<span class="pull-right">';
                    echo '<span class="badge badge-primary"><i class="fas fa-angle-right"></i></span>';
                    echo '</span>';
                    echo fn___echo_fixed_fields('tr_status', $tr['tr_status'], 1, 'right');
                    echo ' ' . $tr['in_outcome'];
                    echo ' ' . $metadata['in__tree_in_active_count'];
                    echo ' &nbsp;<i class="fal fa-clock"></i> ' . fn___echo_time_range($tr, true);
                    echo '</a>';
                }
                echo '</div>';

            } elseif(count($trs)==1){

                //We have a single Action Plan Intent to load:
                //Now we need to load the action plan:
                $actionplan_parents = $this->Database_model->fn___tr_fetch(array(
                    'tr_type_entity_id' => 4559, //Action Plan Step
                    'tr_parent_transaction_id' => $actionplan_tr_id,
                    'in_status' => 2, //Published Intents
                    'tr_child_intent_id' => $in_id,
                ), array('in_parent'));

                $actionplan_children = $this->Database_model->fn___tr_fetch(array(
                    'tr_type_entity_id' => 4559, //Action Plan Step
                    'tr_parent_transaction_id' => $actionplan_tr_id,
                    'in_status' => 2, //Published Intents
                    'tr_parent_intent_id' => $in_id,
                ), array('in_child'));


                $ins = $this->Database_model->fn___in_fetch(array(
                    'in_status' => 2,
                    'in_id' => $in_id,
                ));

                if (count($ins) < 1 || (!count($actionplan_parents) && !count($actionplan_children))) {

                    //Ooops, we had issues finding th is intent! Should not happen, report:
                    $this->Database_model->fn___tr_create(array(
                        'tr_miner_entity_id' => $trs[0]['en_id'],
                        'tr_metadata' => $trs,
                        'tr_content' => 'Unable to load a specific intent for the master Action Plan! Should not happen...',
                        'tr_type_entity_id' => 4246, //Platform Error
                        'tr_parent_transaction_id' => $actionplan_tr_id,
                        'tr_child_intent_id' => $in_id,
                    ));

                    die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
                }

                //All good, Load UI:
                $this->load->view('view_ledger/tr_actionplan_messenger_ui.php', array(
                    'actionplan' => $trs[0], //We must have 1 by now!
                    'in' => $ins[0],
                    'actionplan_parents' => $actionplan_parents,
                    'actionplan_children' => $actionplan_children,
                ));

            }
        }
    }






    function skip_tree($tr_id, $in_id, $tr_id2)
    {
        //Start skipping:
        $total_skipped = count($this->Matrix_model->actionplan_skip_recursive_down($tr_id));

        //Draft message:
        $message = '<div class="alert alert-success" role="alert">' . $total_skipped . ' key idea' . fn___echo__s($total_skipped) . ' successfully skipped.</div>';

        //Find the next item to navigate them to:
        $next_ins = $this->Matrix_model->fn___actionplan_next_in($tr_id);
        if ($next_ins) {
            return fn___redirect_message('/my/actionplan/' . $next_ins[0]['tr_parent_transaction_id'] . '/' . $next_ins[0]['in_id'], $message);
        } else {
            return fn___redirect_message('/my/actionplan', $message);
        }
    }

    function choose_or_path($actionplan_tr_id, $origin_in_id, $in_answer_id, $w_key)
    {
        if (md5($actionplan_tr_id . $this->config->item('actionplan_salt') . $in_answer_id . $origin_in_id) == $w_key) {
            if ($this->Matrix_model->fn___actionplan_choose_or($origin_in_id, $in_answer_id, $actionplan_tr_id)) {
                return fn___redirect_message('/my/actionplan/' . $actionplan_tr_id . '/' . $in_answer_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');
            } else {
                //We had some sort of an error:
                return fn___redirect_message('/my/actionplan/' . $actionplan_tr_id . '/' . $origin_in_id, '<div class="alert alert-danger" role="alert">There was an error saving your answer.</div>');
            }
        }
    }

    function update_k_save()
    {

        //Validate integrity of request:
        if (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1 || !isset($_POST['tr_content'])) {
            return fn___redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: Missing Core Data.</div>');
        }

        //Fetch student name and details:
        $session_en = $this->session->userdata('user');
        $trs = $this->Database_model->fn___tr_fetch(array(
            'tr_id' => $_POST['tr_id'],
        ), array('w', 'cr', 'cr_c_child'));

        if (!(count($trs) == 1)) {
            return fn___redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: Invalid submission ID.</div>');
        }
        $k_url = '/my/actionplan/' . $trs[0]['tr_parent_transaction_id'] . '/' . $trs[0]['in_id'];


        //Fetch completion requirements:
        $in_requirement_entity_id = 0; //TODO Update

        if($in_requirement_entity_id>0){

            //Yes, it does have requirements! let's check them one by one:

            //Intent Completion Requirements:
            $en_all_4331 = $this->config->item('en_all_4331');

            //Extract references to see what we have:
            $msg_references = fn___extract_message_references($_POST['tr_content']);

            //TODO we later need to check URL type to enable other requirements live video/audio URLs if they are to be added as an option.

            $requirement_notes = array();
            $did_meet_requirements = false; //Assume false unless proven otherwise

            //Check to see if Student meets ANY of the requirements:
            //Check requirements:
            if($tr['tr_parent_entity_id']==4255 && strlen($_POST['tr_content']) > 0){
                $did_meet_requirements = true;
            } elseif($tr['tr_parent_entity_id']==4256 && count($msg_references['ref_urls']) > 0){
                $did_meet_requirements = true;
            }

            if($did_meet_requirements){
                //We only need to meet a single requirement:
            } else {
                //Add this to list of what is needed to mark as complete so we can inform Student:
                array_push($requirement_notes, $en_all_4331[$tr['tr_parent_entity_id']]['m_name']);
            }

            if(!$did_meet_requirements){
                return fn___redirect_message($k_url, '<div class="alert alert-danger" role="alert">Error: You are required to submit any of ['.join(', ', $requirement_notes).'] to mark [' . $trs[0]['in_outcome'] . '] as complete.</div>');
            }
        }


        //Did anything change?
        $status_changed = ($trs[0]['tr_status'] <= 1);
        $notes_changed = !($trs[0]['tr_content'] == trim($_POST['tr_content']));
        if (!$notes_changed && !$status_changed) {
            //Nothing seemed to change! Let them know:
            return fn___redirect_message($k_url, '<div class="alert alert-info" role="alert">Note: Nothing saved because nothing was changed.</div>');
        }

        //Has anything changed?
        if ($notes_changed) {


            $detected_tr_type = fn___detect_tr_type_entity_id($_POST['tr_content']);
            if(!$detected_tr_type['status']){
                return fn___redirect_message('/my/actionplan', '<div class="alert alert-danger" role="alert">Error: '.$detected_tr_type['message'].'</div>');
            }

            //Updates k notes:
            $this->Database_model->fn___tr_update($trs[0]['tr_id'], array(
                'tr_content' => trim($_POST['tr_content']),
                'tr_type_entity_id' => $detected_tr_type['tr_type_entity_id'],
            ), (isset($session_en['en_id']) ? $session_en['en_id'] : $trs[0]['k_children_en_id']));
        }

        if ($status_changed) {
            //Also update tr_status, determine what it should be:
            $this->Matrix_model->actionplan_complete_recursive_up($trs[0], $trs[0]);
        }


        //Redirect back to page with success message:
        if (isset($_POST['fn___actionplan_next_in'])) {
            //Go to next item:
            $next_ins = $this->Matrix_model->fn___actionplan_next_in($trs[0]['tr_id']);
            if ($next_ins) {
                //Override original item:
                $k_url = '/my/actionplan/' . $next_ins[0]['tr_parent_transaction_id'] . '/' . $next_ins[0]['in_id'];
            }
        }

        return fn___redirect_message($k_url, '<div class="alert alert-success" role="alert"><i class="fal fa-check-circle"></i> Successfully Saved</div>');
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
        $tr_metadata = array();


        //Let's fetch all Media files without a Facebook attachment ID:
        $tr_pending = $this->Database_model->fn___tr_fetch(array(
            'tr_type_entity_id IN (' . join(',',array_keys($fb_convert_4537)) . ')' => null,
            'tr_status' => 2, //Publish
            'tr_metadata' => null, //Missing Facebook Attachment ID [NOTE: Must make sure tr_metadata is not used for anything else for these transaction types]
        ), array(), 10, 0 , array('tr_id' => 'ASC')); //Sort by oldest added first


        //Put something in the tr_metadata so other cron jobs do not pick  up on it:
        foreach ($tr_pending as $tr) {
            $this->Matrix_model->fn___metadata_update('tr', $tr['tr_id'], array(
                'fb_att_id' => 0,
            ));
        }

        foreach ($tr_pending as $tr) {

            //To be set to true soon (hopefully):
            $db_result = false;

            //Payload to save attachment:
            $payload = array(
                'message' => array(
                    'attachment' => array(
                        'type' => $fb_convert_4537[$tr['tr_type_entity_id']],
                        'payload' => array(
                            'is_reusable' => true,
                            'url' => $tr['tr_content'], //The URL to the media file
                        ),
                    ),
                )
            );

            //Attempt to sync Media to Facebook:
            $result = $this->Chat_model->fn___facebook_graph('POST', '/me/message_attachments', $payload);

            if (isset($result['tr_metadata']['result']['attachment_id']) && $result['status']) {

                //Save Facebook Attachment ID to DB:
                $db_result = $this->Matrix_model->fn___metadata_update('tr', $tr['tr_id'], array(
                    'fb_att_id' => intval($result['tr_metadata']['result']['attachment_id']),
                ));

            }

            //Did it go well?
            if ($db_result) {

                $success_count++;

            } else {

                //Log error:
                $this->Database_model->fn___tr_create(array(
                    'tr_type_entity_id' => 4246, //Platform Error
                    'tr_miner_entity_id' => 1, //Shervin/Developer
                    'tr_parent_transaction_id' => $tr['tr_id'],
                    'tr_content' => 'cron__sync_attachments() Failed to sync attachment to Facebook API: '.( isset($result['tr_metadata']['result']['error']['message']) ? $result['tr_metadata']['result']['error']['message'] : 'Unknown Error' ),
                    'tr_metadata' => array(
                        'payload' => $payload,
                        'result' => $result,
                        'tr' => $tr,
                    ),
                ));

            }

            //Save stats:
            array_push($tr_metadata, array(
                'payload' => $payload,
                'fb_result' => $result,
            ));

        }

        //Echo message:
        fn___echo_json(array(
            'status' => ($success_count == count($tr_pending) && $success_count > 0 ? 1 : 0),
            'message' => $success_count . '/' . count($tr_pending) . ' synced using Facebook Attachment API',
            'tr_metadata' => $tr_metadata,
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

        $tr_pending = $this->Database_model->fn___tr_fetch(array(
            'tr_status' => 0, //New
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_6102')) . ')' => null, //Student Sent/Received Media Transactions
        ), array(), 20);

        //Set transaction statuses to drafting so other Cron jobs don't pick them up:
        $this->Matrix_model->fn___trs_set_drafting($tr_pending);

        $counter = 0;
        foreach($tr_pending as $tr){

            //Store to CDN:
            $new_file_url = fn___upload_to_cdn($tr['tr_content'], $tr);

            if($new_file_url && filter_var($new_file_url, FILTER_VALIDATE_URL)){

                //Update transaction:
                $this->Database_model->fn___tr_update($tr['tr_id'], array(
                    'tr_content' => $new_file_url,
                    'tr_status' => 2,
                ));

                //Increase counter:
                $counter++;

            } else {

                //Log error:
                $this->Database_model->fn___tr_create(array(
                    'tr_type_entity_id' => 4246, //Platform Error
                    'tr_miner_entity_id' => 1, //Shervin/Developer
                    'tr_parent_transaction_id' => $tr['tr_id'],
                    'tr_content' => 'cron__save_chat_media() Failed to save media from Messenger',
                    'tr_metadata' => array(
                        'new_file_url' => $new_file_url,
                        'tr' => $tr,
                    ),
                ));

            }
        }

        //Echo message for cron job:
        echo $counter.' message media files saved to Mench CDN';

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
         * Transaction Type which this cron job grabs and
         * uploads to Mench CDN.
         *
         * Runs every minute with the cron job.
         *
         * */

        $tr_pending = $this->Database_model->fn___tr_fetch(array(
            'tr_status' => 0, //New
            'tr_type_entity_id' => 4299, //Requested Photo Storage
        ), array('en_miner'), 20); //Max number of scans per run


        //Set transaction statuses to drafting so other Cron jobs don't pick them up:
        $this->Matrix_model->fn___trs_set_drafting($tr_pending);

        //Now go through and upload to CDN:
        foreach ($tr_pending as $tr) {

            //Save photo to S3 if content is URL
            $new_file_url = (filter_var($tr['tr_content'], FILTER_VALIDATE_URL) ? fn___upload_to_cdn($tr['tr_content'], $tr) : false);

            if(!$new_file_url){

                //Ooopsi, there was an error:
                $this->Database_model->fn___tr_create(array(
                    'tr_content' => 'cron__save_profile_photo() failed to store file in CDN',
                    'tr_type_entity_id' => 4246, //Platform Error
                    'tr_miner_entity_id' => 1, //Shervin/Developer
                    'tr_parent_transaction_id' => $tr['tr_id'],
                ));

                continue;
            }

            //Update entity icon only if not already set:
            $tr_child_entity_id = 0;
            if (strlen($tr['en_icon'])<1) {

                //Update Cover ID:
                $this->Database_model->fn___en_update($tr['en_id'], array(
                    'en_icon' => '<img src="' . $new_file_url . '">',
                ), true, $tr['en_id']);

                //Link transaction to entity:
                $tr_child_entity_id = $tr['en_id'];

            }

            //Update transaction:
            $this->Database_model->fn___tr_update($tr['tr_id'], array(
                'tr_status' => 2, //Publish
                'tr_content' => null, //Remove URL from content to indicate its done
                'tr_child_entity_id' => $tr_child_entity_id,
                'tr_metadata' => array(
                    'original_url' => $tr['tr_content'],
                    'cdn_url' => $new_file_url,
                ),
            ));

        }

        fn___echo_json($tr_pending);
    }

}