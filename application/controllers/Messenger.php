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
        $tr_metadata = json_decode(file_get_contents('php://input'), true);

        //This is for local testing only:
        //$tr_metadata = objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1505007977668,"messaging":[{"sender":{"id":"1443101719058431"},"recipient":{"id":"381488558920384"},"timestamp":1505007977521,"message":{"mid":"mid.$cAAFa9hmVoehkmryMMVeaXdGIY9x5","seq":19898,"text":"Yes"}}]}]}'));


        //Do some basic checks:
        if (!isset($tr_metadata['object']) || !isset($tr_metadata['entry'])) {
            //Likely loaded the URL in browser:
            return false;
        } elseif (!$tr_metadata['object'] == 'page') {
            $this->Database_model->tr_create(array(
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
                $this->Database_model->tr_create(array(
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
                    $en = $this->Matrix_model->en_student_messenger_authenticate($im['sender']['id']);

                    //Log Transaction Only IF last delivery transaction was 3+ minutes ago (Since Facebook sends many of these):
                    $last_trs_logged = $this->Database_model->tr_fetch(array(
                        'tr_type_entity_id' => $tr_type_entity_id,
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_timestamp >=' => date("Y-m-d H:i:s", (time() - (180))), //Transactions logged less than 3 minutes ago
                    ), array(), 1);

                    if(count($last_trs_logged) == 0){
                        //We had no recent transactions of this kind, so go ahead and log:
                        $this->Database_model->tr_create(array(
                            'tr_metadata' => $tr_metadata,
                            'tr_type_entity_id' => $tr_type_entity_id,
                            'tr_miner_entity_id' => $en['en_id'],
                            'tr_timestamp' => echo_time_milliseconds($im['timestamp']), //The Facebook time
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

                    //Log primary transaction:
                    $this->Database_model->tr_create(array(
                        'tr_type_entity_id' => $tr_type_entity_id,
                        'tr_metadata' => $tr_metadata,
                        'tr_content' => $quick_reply_payload,
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_timestamp' => echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                    //Digest quick reply Payload if any:
                    if($quick_reply_payload){
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

                    //Log transaction:
                    $this->Database_model->tr_create(array(
                        'tr_metadata' => $tr_metadata,
                        'tr_type_entity_id' => 4266, //Messenger Optin
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_timestamp' => echo_time_milliseconds($im['timestamp']), //The Facebook time
                    ));

                } elseif (isset($im['message_request']) && $im['message_request'] == 'accept') {

                    //This is when we message them and they accept to chat because they had Removed Messenger or something...
                    $en = $this->Matrix_model->en_student_messenger_authenticate($im['sender']['id']);

                    //Log transaction:
                    $this->Database_model->tr_create(array(
                        'tr_metadata' => $tr_metadata,
                        'tr_type_entity_id' => 4577, //Message Request Accepted
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_timestamp' => echo_time_milliseconds($im['timestamp']), //The Facebook time
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
                    unset($tr_data); //Reset everything in case its set from the previous loop!
                    $sent_by_mench = (isset($im['message']['is_echo'])); //Indicates the message sent from the page itself
                    $en = $this->Matrix_model->en_student_messenger_authenticate(($sent_by_mench ? $im['recipient']['id'] : $im['sender']['id']));

                    $tr_data = array(
                        'tr_miner_entity_id' => $en['en_id'],
                        'tr_timestamp' => ($sent_by_mench ? null : echo_time_milliseconds($im['timestamp']) ), //Facebook time if received from Student
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
                        $this->Chat_model->digest_quick_reply($en, $im['message']['quick_reply']['payload']);

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
                            $this->Chat_model->digest_message($en, $im['message']['text']);

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
                        $this->Database_model->tr_create($tr_data);

                    } else {

                        //Ooooopsi, this seems to be an unknown message type:
                        $this->Database_model->tr_create(array(
                            'tr_type_entity_id' => 4246, //Platform Error
                            'tr_miner_entity_id' => 1, //Shervin/Developer
                            'tr_content' => 'facebook_webhook() Received unknown message type! Analyze metadata for more details',
                            'tr_metadata' => $tr_metadata,
                        ));

                    }

                } else {

                    //This should really not happen!
                    $this->Database_model->tr_create(array(
                        'tr_content' => 'facebook_webhook() received unrecognized webhook call',
                        'tr_metadata' => $tr_metadata,
                        'tr_type_entity_id' => 4246, //Platform Error
                        'tr_miner_entity_id' => 1, //Shervin/Developer
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
                'fb_profile' => $this->Chat_model->facebook_graph('GET', '/'.$current_us[0]['en_psid'], array()),
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
                    ),
                ),
            ),
        )));

        //Show results:
        echo_json($res);
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

        //Get session data in case user is doing a browser login:
        $session_en = $this->session->userdata('user');
        $empty_session = (!isset($session_en['en__actionplans']) || count($session_en['en__actionplans']) < 1);
        $is_miner = filter_array($session_en['en__parents'], 'en_id', 1308);

        //Authenticate user:
        if (!$psid && $empty_session && !$is_miner) {
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif ($empty_session && !is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
        }

        if($empty_session && $psid > 0){
            //Authenticate this user:
            $session_en = $this->Matrix_model->en_student_messenger_authenticate($psid);
        }


        //Fetch/Validate Action Plan:
        $trs = array();
        $filters = array();

        //Do we have a use session?
        if ($in_id > 0) {
            //Yes, this is either an action plan intent OR step:
            $filters['tr_child_intent_id'] = $in_id;
            $filters['tr_type_entity_id IN ('.join(',',$this->config->item('en_ids_6107')).')'] = null; //Student Action Plan
        } elseif (!$empty_session) {
            //Yes! It seems to be a desktop login (versus Facebook Messenger)
            $filters['tr_type_entity_id'] = 4235; //Action Plan Intent
            $filters['tr_miner_entity_id'] = $session_en['en_id'];
            $filters['tr_status >='] = 0; //New+
        }

        if(count($filters) > 0){
            //Try finding them:
            $trs = $this->Database_model->tr_fetch($filters, array('in_child'));
        }

        if (count($trs) < 1) {

            //No Action Plans found:
            die('<div class="alert alert-danger" role="alert">You have not added any intentions to your Action Plan yet.</div>');

        } else {

            //Determine Action Plan ID:
            $actionplan_tr_id = ( $trs[0]['tr_parent_transaction_id'] == 0 ? $trs[0]['tr_id'] : $trs[0]['tr_parent_transaction_id'] );
            $in_id = $trs[0]['tr_child_intent_id']; //might have been 0 initially, so we'll set it anyways...

            //Log action plan view transaction:
            $this->Database_model->tr_create(array(
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
                    echo '<a href="/messenger/actionplan/' . $tr['tr_child_intent_id'] . '" class="list-group-item">';
                    echo '<span class="pull-right">';
                    echo '<span class="badge badge-primary"><i class="fas fa-angle-right"></i></span>';
                    echo '</span>';
                    echo echo_fixed_fields('tr_status', $tr['tr_status'], 1, 'right');
                    echo ' ' . $tr['in_outcome'];
                    echo ' ' . $metadata['in__tree_in_active_count'];
                    echo ' &nbsp;<i class="fal fa-clock"></i> ' . echo_time_range($tr, true);
                    echo '</a>';
                }
                echo '</div>';

            } elseif(count($trs)==1){

                //We have a single Action Plan Intent to load...

                //Now we need to load the action plan:
                $actionplan_parents = $this->Database_model->tr_fetch(array(
                    'tr_type_entity_id' => 4559, //Action Plan Step
                    'tr_parent_transaction_id' => $actionplan_tr_id,
                    'in_status' => 2, //Published Intents
                    'tr_child_intent_id' => $in_id,
                ), array('in_parent'), 0, 0, array('tr_order' => 'ASC'));

                $actionplan_children = $this->Database_model->tr_fetch(array(
                    'tr_type_entity_id' => 4559, //Action Plan Step
                    'tr_parent_transaction_id' => $actionplan_tr_id,
                    'in_status' => 2, //Published Intents
                    'tr_parent_intent_id' => $in_id,
                ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));


                $ins = $this->Database_model->in_fetch(array(
                    'in_status' => 2,
                    'in_id' => $in_id,
                ));

                if (count($ins) < 1 || (!count($actionplan_parents) && !count($actionplan_children))) {

                    //Ooops, we had issues finding th is intent! Should not happen, report:
                    $this->Database_model->tr_create(array(
                        'tr_miner_entity_id' => $trs[0]['tr_miner_entity_id'],
                        'tr_metadata' => $trs,
                        'tr_content' => 'Unable to load a specific intent for the master Action Plan! Should not happen...',
                        'tr_type_entity_id' => 4246, //Platform Error
                        'tr_parent_transaction_id' => $actionplan_tr_id,
                        'tr_child_intent_id' => $in_id,
                    ));

                    die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
                }

                //All good, Load UI:
                $this->load->view('view_messenger/actionplan_ui.php', array(
                    'actionplan' => $trs[0], //We must have 1 by now!
                    'in' => $ins[0],
                    'actionplan_parents' => $actionplan_parents,
                    'actionplan_children' => $actionplan_children,
                ));

            }

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
        if (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1 || !isset($_POST['tr_content'])) {
            return redirect_message('/messenger/actionplan', '<div class="alert alert-danger" role="alert">Error: Missing Core Data.</div>');
        }

        //Fetch/validate Action Plan Step:
        $actionplan_steps = $this->Database_model->tr_fetch(array(
            'tr_id' => $_POST['tr_id'],
            'tr_type_entity_id' => 4559, //Action Plan Step
        ), array('in_child'));
        if (count($actionplan_steps) < 1) {
            return redirect_message('/messenger/actionplan', '<div class="alert alert-danger" role="alert">Error: Action Plan Step not found.</div>');
        }

        //Set some variables:
        $session_en = $this->session->userdata('user');
        $step_url = '/messenger/actionplan/' . $actionplan_steps[0]['tr_child_intent_id'];
        $step_is_incomplete = ($actionplan_steps[0]['tr_status'] < 2);
        $completion_notes_added = ( strlen($_POST['tr_content']) > 0 );
        if(isset($session_en['en_id']) && $session_en['en_id']!=$actionplan_steps[0]['tr_miner_entity_id']){
            //This seems to be a Mench miner/moderator that is NOT the Action Plan Student:
            return redirect_message($step_url, '<div class="alert alert-info" role="alert">Note: You do not seem to be the Action Plan Student so you cannot submit changes.</div>');
        } elseif (!$completion_notes_added && !$step_is_incomplete) {
            //Nothing to be done, return error:
            return redirect_message($step_url, '<div class="alert alert-info" role="alert">Note: Nothing saved as nothing was changed.</div>');
        }

        //TODO Check to see intent has been un-published since it was added to student Action Plan? See Github issue #2226

        if($completion_notes_added){
            //Validate submission:
            $detected_tr_type = detect_tr_type_entity_id($_POST['tr_content']);
            if(!$detected_tr_type['status']){
                return redirect_message('/messenger/actionplan', '<div class="alert alert-danger" role="alert">Error: '.$detected_tr_type['message'].'</div>');
            }
        }

        //Validate intent completion requirement and see if Student meets it:
        $message_in_requirements = $this->Matrix_model->in_req_completion($actionplan_steps[0]);
        if($message_in_requirements && $actionplan_steps[0]['in_requirement_entity_id'] != $detected_tr_type['tr_type_entity_id']){
            //Nope, it does not seem that the submission meets the requirements:
            return redirect_message($step_url, '<div class="alert alert-danger" role="alert">Error: '.$message_in_requirements.'</div>');
        }


        if ($completion_notes_added) {
            //Save notes:
            $this->Database_model->tr_create(array(
                'tr_parent_transaction_id' => $actionplan_steps[0]['tr_id'], //Submission for Action Plan Step
                'tr_miner_entity_id' => $actionplan_steps[0]['tr_miner_entity_id'],
                'tr_status' => 2, //Published
                'tr_content' => trim($_POST['tr_content']),
                'tr_type_entity_id' => $detected_tr_type['tr_type_entity_id'],
                'tr_parent_intent_id' => $actionplan_steps[0]['tr_child_intent_id'],
                'tr_order' => 1 + $this->Database_model->tr_max_order(array(
                    'tr_parent_transaction_id' => $actionplan_steps[0]['tr_id'],
                )),
            ));
        }

        if ($step_is_incomplete) {
            //Also update tr_status, determine what it should be:
            $this->Matrix_model->actionplan_complete_recursive_up($actionplan_steps[0]);
        }


        //Redirect back to page with success message:
        if (isset($_POST['fetch_next_step'])) {
            //Go to next item:
            $next_ins = $this->Matrix_model->actionplan_fetch_next($actionplan_steps[0]['tr_parent_transaction_id']);
            if ($next_ins) {
                //Override original item:
                $step_url = '/messenger/actionplan/' . $next_ins[0]['in_id'];
            }
        }

        return redirect_message($step_url, '<div class="alert alert-success" role="alert"><i class="fal fa-check-circle"></i> Successfully Saved</div>');
    }





    function actionplan_skip_step($tr_id, $apply_skip)
    {

        //Fetch/validate Action Plan Step:
        $actionplan_steps = $this->Database_model->tr_fetch(array(
            'tr_id' => $tr_id,
            'tr_type_entity_id' => 4559, //Action Plan Step
            'tr_status >=' => 0, //New+
        ));

        if(count($actionplan_steps) < 1){
            //Ooooopsi, could not find it:
            $this->Database_model->tr_create(array(
                'tr_parent_transaction_id' => $tr_id,
                'tr_content' => 'actionplan_skip_recursive_down() failed to locate step [Apply: '.( $apply_skip ? 'YES' : 'NO' ).']',
                'tr_type_entity_id' => 4246, //Platform Error
                'tr_miner_entity_id' => 1, //Shervin/Developer
            ));

            return false;
        }

        //Run skip function
        $total_skipped = count($this->Matrix_model->actionplan_skip_recursive_down($tr_id, $apply_skip));

        if(!$apply_skip){

            //Just give count on total steps so student can review/confirm:
            return echo_json(array(
                'step_count' => $total_skipped,
            ));

        } else {

            //We actually skipped, draft message:
            $message = '<div class="alert alert-success" role="alert">You successfully skipped ' . $total_skipped . ' step' . echo__s($total_skipped) . '.</div>';

            //Find the next item to navigate them to:
            $next_ins = $this->Matrix_model->actionplan_fetch_next($actionplan_steps[0]['tr_parent_transaction_id']);
            if ($next_ins) {
                return redirect_message('/messenger/actionplan/' . $next_ins[0]['in_id'], $message);
            } else {
                return redirect_message('/messenger/actionplan', $message);
            }
        }
    }

    function actionplan_choose_step($actionplan_in_id, $in_append_id, $w_key)
    {

        if($w_key != md5($this->config->item('actionplan_salt') . $in_append_id . $actionplan_in_id)){
            return redirect_message('/messenger/actionplan/' . $actionplan_in_id, '<div class="alert alert-danger" role="alert">Invalid Secret Key</div>');
        }

        //Validate Action Plan:
        $actionplans = $this->Database_model->tr_fetch(array(
            'tr_child_intent_id' => $actionplan_in_id,
            'tr_type_entity_id IN ('.join(',',$this->config->item('en_ids_6107')).')' => null, //Student Action Plan
            'tr_status >=' => 0, //New+
        ));
        if(count($actionplans) < 1){
            return redirect_message('/messenger/actionplan/' . $actionplan_in_id, '<div class="alert alert-danger" role="alert">Action Plan Not Found</div>');
        }

        if ($this->Matrix_model->actionplan_append_in($in_append_id, $actionplans[0]['tr_miner_entity_id'], $actionplan_in_id)) {
            return redirect_message('/messenger/actionplan/' . $in_append_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');
        } else {
            //We had some sort of an error:
            return redirect_message('/messenger/actionplan/' . $actionplan_in_id, '<div class="alert alert-danger" role="alert">There was an error saving your answer.</div>');
        }
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
        $tr_pending = $this->Database_model->tr_fetch(array(
            'tr_type_entity_id IN (' . join(',',array_keys($fb_convert_4537)) . ')' => null,
            'tr_status' => 2, //Publish
            'tr_metadata' => null, //Missing Facebook Attachment ID [NOTE: Must make sure tr_metadata is not used for anything else for these transaction types]
        ), array(), 10, 0 , array('tr_id' => 'ASC')); //Sort by oldest added first


        //Put something in the tr_metadata so other cron jobs do not pick  up on it:
        foreach ($tr_pending as $tr) {
            $this->Matrix_model->metadata_single_update('tr', $tr['tr_id'], array(
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
            $result = $this->Chat_model->facebook_graph('POST', '/me/message_attachments', $payload);

            if (isset($result['tr_metadata']['result']['attachment_id']) && $result['status']) {

                //Save Facebook Attachment ID to DB:
                $db_result = $this->Matrix_model->metadata_single_update('tr', $tr['tr_id'], array(
                    'fb_att_id' => intval($result['tr_metadata']['result']['attachment_id']),
                ));

            }

            //Did it go well?
            if ($db_result) {

                $success_count++;

            } else {

                //Log error:
                $this->Database_model->tr_create(array(
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
        echo_json(array(
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

        $tr_pending = $this->Database_model->tr_fetch(array(
            'tr_status' => 0, //New
            'tr_type_entity_id IN (' . join(',', $this->config->item('en_ids_6102')) . ')' => null, //Student Sent/Received Media Transactions
        ), array(), 20);

        //Set transaction statuses to drafting so other Cron jobs don't pick them up:
        $this->Matrix_model->trs_set_drafting($tr_pending);

        $counter = 0;
        foreach($tr_pending as $tr){

            //Store to CDN:
            $new_file_url = upload_to_cdn($tr['tr_content'], $tr);

            if($new_file_url && filter_var($new_file_url, FILTER_VALIDATE_URL)){

                //Update transaction:
                $this->Database_model->tr_update($tr['tr_id'], array(
                    'tr_content' => $new_file_url,
                    'tr_status' => 2,
                ));

                //Increase counter:
                $counter++;

            } else {

                //Log error:
                $this->Database_model->tr_create(array(
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

        $tr_pending = $this->Database_model->tr_fetch(array(
            'tr_status' => 0, //New
            'tr_type_entity_id' => 4299, //Updated Profile Picture
        ), array('en_miner'), 20); //Max number of scans per run


        //Set transaction statuses to drafting so other Cron jobs don't pick them up:
        $this->Matrix_model->trs_set_drafting($tr_pending);

        //Now go through and upload to CDN:
        foreach ($tr_pending as $tr) {

            //Save photo to S3 if content is URL
            $new_file_url = (filter_var($tr['tr_content'], FILTER_VALIDATE_URL) ? upload_to_cdn($tr['tr_content'], $tr) : false);

            if(!$new_file_url){

                //Ooopsi, there was an error:
                $this->Database_model->tr_create(array(
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
                $this->Database_model->en_update($tr['en_id'], array(
                    'en_icon' => '<img src="' . $new_file_url . '">',
                ), true, $tr['en_id']);

                //Link transaction to entity:
                $tr_child_entity_id = $tr['en_id'];

            }

            //Update transaction:
            $this->Database_model->tr_update($tr['tr_id'], array(
                'tr_status' => 2, //Publish
                'tr_content' => null, //Remove URL from content to indicate its done
                'tr_child_entity_id' => $tr_child_entity_id,
                'tr_metadata' => array(
                    'original_url' => $tr['tr_content'],
                    'cdn_url' => $new_file_url,
                ),
            ));

        }

        echo_json($tr_pending);
    }

}