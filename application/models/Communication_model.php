<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Communication_model extends CI_Model
{

    /*
     *
     * This model contains all chat related functions
     * to interact with various chat platforms
     * (currently we only support Facebook)
     * and interpret incoming messages while dispatching
     * outgoing messages via various channels.
     *
     * Think of this as the most external layer
     * input/output processor for our platform.
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function dispatch_message($input_message, $recipient_en = array(), $fb_messenger_format = false, $quick_replies = array(), $ln_append = array(), $message_in_id = 0)
    {

        /*
         *
         * The primary function that constructs messages based on the following inputs:
         *
         *
         * - $input_message:        The message text which may include entity
         *                          references like "@123" or commands like
         *                          "/firstname". This may NOT include direct
         *                          URLs as they must be first turned into an
         *                          entity and then referenced within a message.
         *
         *
         * - $recipient_en:         The entity object that this message is supposed
         *                          to be delivered to. May be an empty array for
         *                          when we want to show these messages to guests,
         *                          and it may contain the full entity object or it
         *                          may only contain the entity ID, which enables this
         *                          function to fetch further information from that
         *                          entity as required based on its other parameters.
         *                          The 3 key columns that this function uses are:
         *
         *                          - $recipient_en['en_id'] - As who to send to
         *                          - $recipient_en['en_name'] - To replace with /firstname
         *                          - $recipient_en['en_psid'] - Needed if $fb_messenger_format = TRUE
         *
         *
         * - $fb_messenger_format:  If True this function will prepare a message to be
         *                          delivered via Facebook Messenger, and if False, it
         *                          would prepare a message for HTML view. The HTML
         *                          format will consider if a Miner is logged in or not,
         *                          which will alter the HTML format.
         *
         *
         * - $quick_replies:        Only supported if $fb_messenger_format = TRUE, and
         *                          will append an array of quick replies that will give
         *                          Students an easy way to tap and select their next step.
         *
         *
         * - $ln_append:            Since this function logs a "message sent" engagement for
         *                          every message it processes, the $ln_append will append
         *                          additional data to capture more context for this message.
         *                          Supported fields only include:
         *
         *                          - $ln_append['ln_parent_intent_id']
         *                          - $ln_append['ln_child_intent_id']
         *                          - $ln_append['ln_parent_link_id']
         *
         *                          Following fields are not allowed, because:
         *
         *                          - $ln_append['ln_metadata']: Reserved for message body IF $fb_messenger_format = TRUE
         *                          - $ln_append['ln_timestamp']: Auto generated to current timestamp
         *                          - $ln_append['ln_status']: Will always equal 2 as a completed message
         *                          - $ln_append['ln_type_entity_id']: Auto calculated based on message content (or error)
         *                          - $ln_append['ln_miner_entity_id']: Mench will always get credit to miner, so this is set to zero
         *                          - $ln_append['ln_parent_entity_id']: This is auto set with an entity reference within $input_message
         *                          - $ln_append['ln_child_entity_id']: This will be equal to $recipient_en['en_id']
         *
         * */

        //Validate message:
        $msg_validation = $this->Communication_model->dispatch_validate_message($input_message, $recipient_en, $fb_messenger_format, $quick_replies, 0, $message_in_id);

        //Prepare data to be appended to success/fail link:
        $allowed_tr_append = array('ln_parent_intent_id', 'ln_child_intent_id', 'ln_parent_link_id');
        $filtered_tr_append = array();
        foreach ($ln_append as $key => $value) {
            if (in_array($key, $allowed_tr_append)) {
                $filtered_tr_append[$key] = $value;
            }
        }


        //Did we have ane error in message validation?
        if (!$msg_validation['status']) {

            //Log Error Link:
            $this->Links_model->ln_create(array_merge(array(
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_content' => 'dispatch_validate_message() returned error [' . $msg_validation['message'] . '] for input message [' . $input_message . ']',
                'ln_child_entity_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                'ln_metadata' => array(
                    'input_message' => $input_message,
                    'recipient_en' => $recipient_en,
                    'fb_messenger_format' => $fb_messenger_format,
                    'quick_replies' => $quick_replies,
                    'ln_append' => $ln_append,
                    'message_in_id' => $message_in_id
                ),
            ), $filtered_tr_append));

            return false;
        }

        //Message validation passed...
        $html_message_body = '';

        //Log message sent link:
        foreach ($msg_validation['output_messages'] as $output_message) {

            //Dispatch message based on format:
            if ($fb_messenger_format) {

                //Attempt to dispatch message via Facebook Graph API:
                $fb_graph_process = $this->Communication_model->facebook_graph('POST', '/me/messages', $output_message['message_body']);

                //Did we have an Error from the Facebook API side?
                if (!$fb_graph_process['status']) {

                    //Ooopsi, we did! Log error Transcation:
                    $this->Links_model->ln_create(array_merge(array(
                        'ln_type_entity_id' => 4246, //Platform Error
                        'ln_miner_entity_id' => 1, //Shervin/Developer
                        'ln_content' => 'dispatch_message() failed to send message via Facebook Graph API. See Metadata log for more details.',
                        'ln_child_entity_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                        'ln_metadata' => array(
                            'input_message' => $input_message,
                            'output_message' => $output_message['message_body'],
                            'fb_graph_process' => $fb_graph_process,
                        ),
                    ), $filtered_tr_append));

                    //Terminate function:
                    return false;

                }

            } else {

                //HTML Format, add to message variable that will be returned at the end:
                $html_message_body .= $output_message['message_body'];

                //NULL placeholder for the Facebook Graph Call since this is an HTML delivery:
                $fb_graph_process = null;

            }

            //Log successful Link for message delivery (Unless Miners viewing HTML):
            if(isset($recipient_en['en_id']) && ($fb_messenger_format || isset($_GET['log_miner_messages']))){
                $this->Links_model->ln_create(array_merge(array(
                    'ln_content' => $msg_validation['input_message'],
                    'ln_type_entity_id' => $output_message['message_type'],
                    'ln_miner_entity_id' => $recipient_en['en_id'],
                    'ln_parent_entity_id' => $msg_validation['ln_parent_entity_id'], //Might be set if message had a referenced entity
                    'ln_metadata' => array(
                        'input_message' => $input_message,
                        'output_message' => $output_message['message_body'],
                        'fb_graph_process' => $fb_graph_process,
                    ),
                ), $filtered_tr_append));
            }

        }

        //If we're here it's all good:
        return ( $fb_messenger_format ? true : $html_message_body );

    }


    function dispatch_validate_message($input_message, $recipient_en = array(), $fb_messenger_format = false, $quick_replies = array(), $message_type_en_id = 0, $message_in_id = 0)
    {

        /*
         *
         * This function is used to validate Intent Notes.
         *
         * See dispatch_message() for more information on input variables.
         *
         * */

        $is_being_modified = ( $message_type_en_id > 0 ); //IF $message_type_en_id > 0 means we're adding/editing and need to do extra checks
        $allowed_length = ( $fb_messenger_format ? $this->config->item('fb_max_message') : $this->config->item('ln_content_max_length')  );

        //Start with basic input validation:
        if (strlen($input_message) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message Content',
            );
        } elseif (strlen($input_message) > $allowed_length) {
            return array(
                'status' => 0,
                'message' => 'Message is longer than the allowed ' . $allowed_length . ' characters',
            );
        } elseif (!preg_match('//u', $input_message)) {
            return array(
                'status' => 0,
                'message' => 'Message must be UTF8',
            );
        } elseif ($fb_messenger_format && !isset($recipient_en['en_id'])) {
            return array(
                'status' => 0,
                'message' => 'Facebook Messenger Format requires a recipient entity ID to construct a message',
            );
        } elseif (count($quick_replies) > 0 && !$fb_messenger_format) {
            return array(
                'status' => 0,
                'message' => 'Quick Replies are only supported for messages Formatted for Facebook Messenger',
            );
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
        $msg_references = extract_message_references($input_message);

        if (count($msg_references['ref_urls']) > 1) {

            return array(
                'status' => 0,
                'message' => 'You can reference a maximum of 1 URL per message',
            );

        } elseif (count($msg_references['ref_entities']) > 1) {

            return array(
                'status' => 0,
                'message' => 'Message can include a maximum of 1 entity reference',
            );

        } elseif (!$fb_messenger_format && count($msg_references['ref_intents']) > 1) {

            return array(
                'status' => 0,
                'message' => 'Message can include a maximum of 1 intent reference',
            );

        } elseif (!$fb_messenger_format && count($msg_references['ref_intents']) > 0 && count($msg_references['ref_entities']) != 1)  {

            return array(
                'status' => 0,
                'message' => 'Intent referencing requires an entity reference',
            );

        } elseif (!$fb_messenger_format && count($msg_references['ref_entities']) > 0 && count($msg_references['ref_urls']) > 0) {

            return array(
                'status' => 0,
                'message' => 'You can either reference 1 entity OR 1 URL (As the URL will be transformed into an entity)',
            );

        } elseif (count($msg_references['ref_commands']) > 0) {

            if(count($msg_references['ref_commands']) != count(array_unique($msg_references['ref_commands']))){

                return array(
                    'status' => 0,
                    'message' => 'Each /command can only be used once per message',
                );

            } elseif(in_array('/link',$msg_references['ref_commands']) && count($quick_replies) > 0){

                return array(
                    'status' => 0,
                    'message' => 'You cannot combine the /link command with quick replies',
                );

            }



        }


        /*
         *
         * $message_type_en_id Validation
         *
         * */
        if($message_type_en_id > 0){

            //See if this message type has specific input requirements:
            $en_all_4485 = $this->config->item('en_all_4485');
            $en_all_4331 = $this->config->item('en_all_4331');
            $en_all_4592 = $this->config->item('en_all_4592');

            //Find the intersection of this message type and intent completion requirements:
            $completion_requirements = array_intersect($en_all_4485[$message_type_en_id]['m_parents'], $this->config->item('en_ids_4331'));

            //See what this is:
            $detected_ln_type = detect_ln_type_entity_id($_POST['ln_content']);

            if (!$detected_ln_type['status']) {

                //return error:
                return echo_json($detected_ln_type);

            } elseif(count($completion_requirements) > 1){

                return array(
                    'status' => 0,
                    'message' => 'Admin Configuration Error: Message type ['.$en_all_4485[$message_type_en_id]['m_name'].'] has multiple completion requirements ['.join(', ',$completion_requirements).'].',
                );

            } elseif(count($completion_requirements) == 1){

                $en_id = array_shift($completion_requirements);
                $is_url_reference = (count($msg_references['ref_entities'])>0 && $en_id==4256); //TODO Also check/require @4986 as parent entity

                if(!($en_id==$detected_ln_type['ln_type_entity_id']) && !$is_url_reference){
                    return array(
                        'status' => 0,
                        'message' => $en_all_4485[$message_type_en_id]['m_name'].' requires a ['.$en_all_4331[$en_id]['m_name'].'] message. You entered a ['.$en_all_4592[$detected_ln_type['ln_type_entity_id']]['m_name'].'] message.',
                    );
                }

            }


            //Now check for intent referencing settings:
            if(in_array(4985 , $en_all_4485[$message_type_en_id]['m_parents'])){

                //Is it missing its required intent reference?
                if(count($msg_references['ref_intents']) < 1){
                    return array(
                        'status' => 0,
                        'message' => $en_all_4485[$message_type_en_id]['m_name'].' require an intent reference.',
                    );
                } elseif($message_in_id < 1){
                    return array(
                        'status' => 0,
                        'message' => 'Message validator function missing required message intent ID.',
                    );
                }

            } elseif(!in_array(4985 , $en_all_4485[$message_type_en_id]['m_parents']) && count($msg_references['ref_intents']) > 0){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' do not support intent referencing.',
                );

            }

            //Now check for entity referencing settings:
            if(!in_array(4986 , $en_all_4485[$message_type_en_id]['m_parents']) && count($msg_references['ref_entities']) > 0){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' do not support entity referencing.',
                );

            }

        }







        /*
         *
         * Fetch more details on recipient entity if needed:
         *
         * - IF $fb_messenger_format = TRUE AND We're missing en_psid
         * - IF /firstname command is used AND en_id is set AND We're missing en_name
         *
         * */

        if (($fb_messenger_format && !isset($recipient_en['en_psid'])) || (isset($recipient_en['en_id']) && in_array('/firstname', $msg_references['ref_commands']) && !isset($recipient_en['en_name']))) {

            //We have partial entity data, but we're missing some needed information...

            //Fetch full entity data:
            $ens = $this->Entities_model->en_fetch(array(
                'en_id' => $recipient_en['en_id'],
                'en_status >=' => 0, //New+
            ), array('skip_en__parents')); //Just need entity info, not its parents...

            if (count($ens) < 1) {
                //Ooops, invalid entity ID provided
                return array(
                    'status' => 0,
                    'message' => 'Invalid Entity ID provided',
                );
            } elseif ($fb_messenger_format && $ens[0]['en_psid'] < 1) {
                //This Student does not have their Messenger connected yet:
                return array(
                    'status' => 0,
                    'message' => 'Student @' . $recipient_en['en_id'] . ' does not have Messenger connected yet',
                );
            } else {
                //Assign data:
                $recipient_en = $ens[0];
            }
        }


        /*
         *
         * Fetch notification level IF $fb_messenger_format = TRUE
         *
         * */

        if ($fb_messenger_format) {

            //Translates our settings to Facebook Notification Settings:
            $fb_convert_4454 = array( //Facebook Messenger Notification Levels - This is a manual converter of our internal entities to Facebook API
                4456 => 'REGULAR',
                4457 => 'SILENT_PUSH',
                4458 => 'NO_PUSH',
                4455 => 'NO_PUSH', //Unsubscribed users only get messages if they messages us first
            );

            //Fetch recipient notification type:
            $lns_comm_level = $this->Links_model->ln_fetch(array(
                'ln_parent_entity_id IN (' . join(',', $this->config->item('en_ids_4454')) . ')' => null,
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_child_entity_id' => $recipient_en['en_id'],
                'ln_status' => 2, //Published
            ));

            //Start validating communication settings we fetched to ensure everything is A-OK:
            if (count($lns_comm_level) < 1) {

                return array(
                    'status' => 0,
                    'message' => 'Student is missing their Notification Level parent entity relation',
                );

            } elseif (count($lns_comm_level) > 1) {

                //This should find exactly one result as it belongs to Student Radio Entity @6137
                return array(
                    'status' => 0,
                    'message' => 'Student has more than 1 Notification Level parent entity relation',
                );

            } elseif (!array_key_exists($lns_comm_level[0]['ln_parent_entity_id'], $fb_convert_4454)) {

                return array(
                    'status' => 0,
                    'message' => 'Fetched unknown Notification Level [' . $lns_comm_level[0]['ln_parent_entity_id'] . ']',
                );

            }

            //All good, Set notification type:
            $notification_type = $fb_convert_4454[$lns_comm_level[0]['ln_parent_entity_id']];

        }


        /*
         *
         * Process Possible URL
         * (turn URL into an entity reference)
         *
         * */
        if (count($msg_references['ref_urls']) > 0) {

            //Fetch session user:
            $session_en = $this->session->userdata('user');

            if(!isset($session_en['en_id'])){
                return array(
                    'status' => 0,
                    'message' => 'Miner must be logged in to convert URL to entity',
                );
            }

            //No entity linked, but we have a URL that we should turn into an entity:
            $url_entity = $this->Entities_model->en_sync_url($msg_references['ref_urls'][0], $session_en['en_id']);

            //Did we have an error?
            if (!$url_entity['status']) {
                return $url_entity;
            }

            //Transform this URL into an entity:
            $msg_references['ref_entities'][0] = $url_entity['en_url']['en_id'];

            //Replace the URL with this new @entity in message.
            //This is the only valid modification we can do to $input_message before storing it in the DB:
            $input_message = str_replace($msg_references['ref_urls'][0], '@' . $msg_references['ref_entities'][0], $input_message);

            //Remove URL:
            unset($msg_references['ref_urls'][0]);

        }


        /*
         *
         * Process Commands
         *
         * */

        //Start building the Output message body based on format:
        $output_body_message = ( $fb_messenger_format ? $input_message : htmlentities($input_message) );

        if (in_array('/firstname', $msg_references['ref_commands'])) {

            //We sometimes may need to set a default recipient entity name IF /firstname command used without any recipient entity passed:
            if (!isset($recipient_en['en_name'])) {
                //This is a guest Student, so use the default:
                $recipient_en['en_name'] = 'Student';
            }

            //Replace name with command:
            $output_body_message = str_replace('/firstname', one_two_explode('', ' ', $recipient_en['en_name']), $output_body_message);

        }


        //Determine if we have a button link:
        $fb_button_title = null;
        $fb_button_url = null;
        if (in_array('/link', $msg_references['ref_commands'])) {

            //Validate /link format:
            $link_anchor = trim(one_two_explode('/link:', ':http', $output_body_message));
            $link_url = 'http' . one_two_explode(':http', ' ', $output_body_message);

            if (strlen($link_anchor) < 1 || !filter_var($link_url, FILTER_VALIDATE_URL)) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid /link command! Proper format is: /link:ANCHOR:URL for example: /link:Open Google:https://google.com',
                );
            }

            //Make adjustments:
            if ($fb_messenger_format) {

                //Update variables to later include in message:
                $fb_button_title = $link_anchor;
                $fb_button_url = $link_url;

                //Remove command from input message:
                $output_body_message = str_replace('/link:' . $link_anchor . ':' . $link_url, '', $output_body_message);

            } else {

                //Replace in HTML message:
                $output_body_message = str_replace('/link:' . $link_anchor . ':' . $link_url, '<a href="' . $link_url . '" target="_blank">' . $link_anchor . '</a>', $output_body_message);

            }

        }


        //Will include the start and end time:
        $slice_times = array();

        //Valid URLs that are considered slicable in-case the /slice command is used:
        $sliceable_urls = array('youtube.com');

        if (in_array('/slice', $msg_references['ref_commands'])) {

            //Validate the format of this command:
            $slice_times = explode(':', one_two_explode('/slice:', ' ', $output_body_message), 2);

            if (intval($slice_times[0]) < 1 || intval($slice_times[1]) < 1 || strlen($slice_times[0]) != strlen(intval($slice_times[0])) || strlen($slice_times[1]) != strlen(intval($slice_times[1]))) {
                //Not valid format!
                return array(
                    'status' => 0,
                    'message' => 'Invalid format for /slice command. For example, to slice first 60 seconds use: /slice:0:60',
                );
            } elseif ((intval($slice_times[0]) + 3) > intval($slice_times[1])) {
                //Not valid format!
                return array(
                    'status' => 0,
                    'message' => 'Sliced clip must be at-least 3 seconds long',
                );
            } elseif (count($msg_references['ref_entities']) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The /slice command requires the message to reference an entity that links to ' . join(' or ', $sliceable_urls),
                );
            }

            //All good, Remove command from input message:
            $output_body_message = str_replace('/slice:' . $slice_times[0] . ':' . $slice_times[1], '', $output_body_message);

            //More processing will happen as we go through referenced entity which is required for the /slice command

        }


        /*
         *
         * Process Possible Referenced Entity
         *
         * */

        //Will contain media from referenced entity:
        $fb_media_attachments = array();

        //This must eventually turn TRUE if the /slice command is used:
        $found_slicable_url = false;

        //We assume this message has text, unless its only content is an entity reference like "@123"
        $has_text = true;

        //Where is this request being made from? Public landing pages will have some restrictions on what they displat:
        $is_landing_page = is_numeric($this->uri->segment(1));
        $is_action_plan = ($this->uri->segment(1)=='messenger');
        $hide_entity_links = ($is_landing_page || $is_action_plan);

        if (count($msg_references['ref_entities']) > 0) {

            //We have a reference within this message, let's fetch it to better understand it:
            $ens = $this->Entities_model->en_fetch(array(
                'en_id' => $msg_references['ref_entities'][0], //Note: We will only have a single reference per message
                'en_status >=' => 0, //New+
            ));

            if (count($ens) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced entity @' . $msg_references['ref_entities'][0] . ' not found',
                );
            }

            //Direct Media URLs supported:
            $fb_convert_4537 = $this->config->item('fb_convert_4537');

            //We send Media in their original format IF $fb_messenger_format = TRUE, which means we need to convert link types:
            if ($fb_messenger_format) {
                //Converts Entity Link Types to their corresponding Student Message Sent Link Types:
                $master_media_sent_conv = array(
                    4258 => 4553, //video
                    4259 => 4554, //audio
                    4260 => 4555, //image
                    4261 => 4556, //file
                );
            }

            //See if this entity has any parent links to be shown in this appendix
            $entity_appendix = null;

            //Determine what type of Media this reference has:
            foreach ($ens[0]['en__parents'] as $parent_en) {

                //Define what type of entity parent link content should be displayed up-front in Messages
                if (!in_array($parent_en['ln_parent_entity_id'], $this->config->item('en_ids_4990')) && !in_array($parent_en['ln_type_entity_id'], $this->config->item('en_ids_4990'))) {
                    continue;
                }

                if (in_array($parent_en['ln_type_entity_id'], $this->config->item('en_ids_4537'))) {

                    //Any Type of URL: Generic, Embed, Video, Audio, Image & File

                    if ($parent_en['ln_type_entity_id'] == 4257) {

                        //Embed URL
                        //Do we have a Slice command AND is this Embed URL Slice-able?
                        if (in_array('/slice', $msg_references['ref_commands']) && includes_any($parent_en['ln_content'], $sliceable_urls)) {

                            //We've found a slice-able URL:
                            $found_slicable_url = true;

                            if ($fb_messenger_format) {
                                //Show custom Start/End URL:
                                $ln_content = 'https://www.youtube.com/embed/' . extract_youtube_id($parent_en['ln_content']) . '?start=' . $slice_times[0] . '&end=' . $slice_times[1] . '&autoplay=1';
                            } else {
                                //Show HTML Embed Code for slice-able:
                                $ln_content = '<div class="entity-appendix">' . echo_url_embed($parent_en['ln_content'], $parent_en['ln_content'], false, $slice_times[0], $slice_times[1]) . '</div>';
                            }

                        } else {

                            if ($fb_messenger_format) {
                                //Show custom Start/End URL:
                                $ln_content = $parent_en['ln_content'];
                            } else {
                                //Show HTML Embed Code:
                                $ln_content = '<div class="entity-appendix">' . echo_url_embed($parent_en['ln_content']) . '</div>';
                            }

                        }


                        if ($fb_messenger_format) {

                            //Generic URL:
                            array_push($fb_media_attachments, array(
                                'ln_type_entity_id' => 4552, //Text Message Sent
                                'ln_content' => $ln_content,
                                'fb_att_id' => 0,
                                'fb_att_type' => null,
                            ));

                        } else {

                            //HTML Format, append content to current output message:
                            $entity_appendix .= $ln_content;

                        }

                    } elseif ($fb_messenger_format && array_key_exists($parent_en['ln_type_entity_id'], $fb_convert_4537)) {

                        //Raw media file: Audio, Video, Image OR File...

                        //Search for Facebook Attachment ID IF $fb_messenger_format = TRUE
                        $fb_att_id = 0;
                        if ($fb_messenger_format && strlen($parent_en['ln_metadata']) > 0) {
                            //We might have a Facebook Attachment ID saved in Metadata, check to see:
                            $metadata = unserialize($parent_en['ln_metadata']);
                            if (isset($metadata['fb_att_id']) && intval($metadata['fb_att_id']) > 0) {
                                //Yes we do, use this for faster media attachments:
                                $fb_att_id = intval($metadata['fb_att_id']);
                            }
                        }

                        //Push raw file to Media Array:
                        array_push($fb_media_attachments, array(
                            'ln_type_entity_id' => $master_media_sent_conv[$parent_en['ln_type_entity_id']],
                            'ln_content' => ($fb_att_id > 0 ? null : $parent_en['ln_content']),
                            'fb_att_id' => $fb_att_id,
                            'fb_att_type' => $fb_convert_4537[$parent_en['ln_type_entity_id']],
                        ));

                    } elseif($fb_messenger_format && $parent_en['ln_type_entity_id'] == 4256){

                        //Generic URL:
                        array_push($fb_media_attachments, array(
                            'ln_type_entity_id' => 4552, //Text Message Sent
                            'ln_content' => $parent_en['ln_content'],
                            'fb_att_id' => 0,
                            'fb_att_type' => null,
                        ));

                    } elseif(!$fb_messenger_format){

                        //HTML Format, append content to current output message:
                        $entity_appendix .= '<div class="entity-appendix"><b>*</b> ' . echo_url_type($parent_en['ln_content'], $parent_en['ln_type_entity_id']) . '</div>';

                    }

                } elseif(!$fb_messenger_format){

                    //HTML Format, append content to current output message:
                    $entity_appendix .= '<div class="entity-appendix"><b>*</b> ' . $parent_en['en_icon'] . ' '. $parent_en['en_name'] . (strlen($parent_en['ln_content']) > 0 ? ': '. $parent_en['ln_content'] : '') . '</div>';

                }

            }


            if($entity_appendix){
                $output_body_message .= $entity_appendix;
            }


            //Determine if we have text:
            $has_text = !(trim($output_body_message) == '@' . $msg_references['ref_entities'][0]);

            //Adjust
            if (!$fb_messenger_format) {

                /*
                 *
                 * HTML Message format, which will
                 * include a link to the Entity for quick access
                 * to more information about that entity:=.
                 *
                 * */

                if($hide_entity_links){

                    //Do not include a link because we don't want to distract the student from the call to Action to get started...
                    $output_body_message = str_replace('@' . $msg_references['ref_entities'][0], '<span class="entity-name">'.$ens[0]['en_name'].'</span>'.( $entity_appendix ? '<b>*</b>' : ''), $output_body_message);

                } else {
                    //Show entity link with status:
                    $fixed_fields = $this->config->item('fixed_fields');
                    $output_body_message = str_replace('@' . $msg_references['ref_entities'][0], $fixed_fields['en_status'][$ens[0]['en_status']]['s_icon'].' <a href="/entities/' . $ens[0]['en_id'] . '" target="_parent">' . $ens[0]['en_name'] . '</a>'.( $entity_appendix ? '<b>*</b>' : ''), $output_body_message);
                }

            } else {

                //Just replace with the entity name, which ensure we're always have a text in our message even if $has_text = FALSE
                $output_body_message = str_replace('@' . $msg_references['ref_entities'][0], $ens[0]['en_name'], $output_body_message);

            }
        }

        //Do we have an intent up-vote?
        if (!$fb_messenger_format && count($msg_references['ref_intents']) > 0 && $message_in_id > 0) {

            //Fetch the referenced intent:
            $upvote_child_ins = $this->Intents_model->in_fetch(array(
                'in_id' => $message_in_id,
                'in_status >=' => 0, //New+
            ));
            if (count($upvote_child_ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced child intent #' . $message_in_id . ' not found',
                );
            }


            $upvote_parent_ins = $this->Intents_model->in_fetch(array(
                'in_id' => $msg_references['ref_intents'][0], //Note: We will only have a single reference per message
                'in_status >=' => 0, //New+
            ));
            if (count($upvote_parent_ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced parent intent #' . $msg_references['ref_intents'][0] . ' not found',
                );
            }


            //Check up-voting restrictions:
            if($is_being_modified){

                //Entity reference must be either the miner themselves or an expert source:
                $session_en = en_auth(array(1308)); //Is miners
                if($msg_references['ref_entities'][0] != $session_en['en_id']){

                    //Reference is not the logged-in miner, let's check to make sure it's an expert source:
                    $is_expert_sources = $this->Links_model->ln_fetch(array(
                        'ln_status >=' => 0,
                        'ln_child_entity_id' => $msg_references['ref_entities'][0],
                        'ln_parent_entity_id IN ('.join(',' , $this->config->item('en_ids_3000')).')' => null, //Intent Supported Verbs
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                    ));

                    if(count($is_expert_sources) < 1){
                        return array(
                            'status' => 0,
                            'message' => 'Voter entity must be either you OR an expert source entity belonging to @3000',
                        );
                    }
                }

            }


            //Note that currently intent references are not displayed on the landing page (Only Messages are) OR messenger format

            //Remove intent reference from anywhere in the message:
            $output_body_message = trim(str_replace('#' . $upvote_parent_ins[0]['in_id'], '', $output_body_message));

            //Add Intent up-vote to beginning:
            $output_body_message = '<div style="margin-bottom:5px; border-bottom: 1px solid #E5E5E5; padding-bottom:10px;">IF you <a href="/intents/' . $upvote_child_ins[0]['in_id'] . '" target="_parent">' . $upvote_child_ins[0]['in_outcome'] . '</a> THEN you will <a href="/intents/' . $upvote_parent_ins[0]['in_id'] . '" target="_parent">' . $upvote_parent_ins[0]['in_outcome'] . '</a></div>' . $output_body_message;

        }


        //Did we meet /slice command requirements (if any) after processing the referenced entity?
        if (in_array('/slice', $msg_references['ref_commands']) && !$found_slicable_url) {
            return array(
                'status' => 0,
                'message' => '/slice command requires the message to reference an entity that links to ' . join(' or ', $sliceable_urls),
            );
        }


        /*
         *
         * Construct Message based on current data
         *
         * $output_messages will determines the type & content of the
         * message(s) that will to be sent. We might need to send
         * multiple messages IF $fb_messenger_format = TRUE and the
         * text message has a referenced entity with a one or more
         * media file (Like video, image, file or audio).
         *
         * The format of this will be array( $ln_child_entity_id => $ln_content )
         * to define both message and it's type.
         *
         * See all sent message types here: https://mench.com/entities/4280
         *
         * */
        $output_messages = array();

        if ($fb_messenger_format) {


            if(count($quick_replies) > 0){
                //TODO Validate $quick_replies content?
            }

            //Do we have a text message?
            if ($has_text || $fb_button_title) {

                if ($fb_button_title) {

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
                                        'url' => $fb_button_url,
                                        'title' => $fb_button_title,
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
                    'message_type' => 4552, //Text Message Sent
                    'message_body' => array(
                        'recipient' => array(
                            'id' => $recipient_en['en_psid'],
                        ),
                        'message' => $fb_message,
                        'notification_type' => $notification_type,
                        'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION',
                    ),
                ));

            }


            if (!$has_text && count($quick_replies) > 0) {

                //We have a quick reply without a text, so append a generix text message as its required to have one:
                array_push($output_messages, array(
                    'message_type' => 4552, //Text Message Sent
                    'message_body' => array(
                        'recipient' => array(
                            'id' => $recipient_en['en_psid'],
                        ),
                        'message' => array(
                            'text' => 'Select an option to continue:', //Generic/fixed message
                            'quick_replies' => $quick_replies,
                            'metadata' => 'system_logged', //Prevents duplicate Link logs
                        ),
                        'notification_type' => $notification_type,
                        'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION',
                    ),
                ));

            }


            if (count($fb_media_attachments) > 0) {

                //We do have additional messages...
                //TODO Maybe add another message to give Student some context on these?

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
                        'message_type' => $fb_media_attachment['ln_type_entity_id'],
                        'message_body' => array(
                            'recipient' => array(
                                'id' => $recipient_en['en_psid'],
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
                'message_type' => 4570, //HTML Message Sent
                'message_body' => '<div class="i_content"><div class="msg">' . nl2br($output_body_message) . '</div></div>',
            ));

        }


        //Return results:
        return array(
            'status' => 1,
            'input_message' => trim($input_message),
            'output_messages' => $output_messages,
            'ln_parent_entity_id' => (count($msg_references['ref_entities']) > 0 ? $msg_references['ref_entities'][0] : 0),
            'ln_parent_intent_id' => (count($msg_references['ref_intents']) > 0 ? $msg_references['ref_intents'][0] : 0),
        );

    }


    function suggest_featured_intents($en_id){


        /*
         *
         * A function that would recommend featured intentions
         * that have not been taken by this student yet.
         *
         * */



        //Fetch student's Action Plan Intents:
        $student_intents = $this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
            'in_status' => 2, //Published
        ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

        $student_ins_ids = array(); //To be populated:
        foreach($student_intents as $student_in){
            array_push($student_ins_ids, $student_in['in_id']);
        }



        //Fetch featured intentions not yet taken by student:
        $featured_filters = array(
            'ln_status' => 2, //Published
            'in_status' => 2, //Published
            'ln_type_entity_id' => 4228, //Fixed Links
            'ln_parent_intent_id' => $this->config->item('in_featured'),
        );
        if(count($student_ins_ids) > 0){
            //Remove as its already added to student Action Plan:
            $featured_filters['ln_child_intent_id NOT IN ('.join(',', $student_ins_ids).')'] = null;
        }
        $featured_intentions = $this->Links_model->ln_fetch($featured_filters, array('in_child'), 0, 0, array('ln_order' => 'ASC'));



        //What did we find?
        if(count($featured_intentions) > 0){
            //Yes, we have something to offer:


            $message = 'Here are my recommended intentions that you can add to your Action Plan:';
            $quick_replies = array();

            foreach($featured_intentions as $count => $in){

                if ($count >= 10) {

                    //We can't have more than 10 intentions listed as Quick Reply supports a total of 11 only (and we need one for "None of the above" option)
                    $this->Links_model->ln_create(array(
                        'ln_miner_entity_id' => 1, //Shervin/Developer
                        'ln_content' => 'actionplan_advance_step() encountered intent with too many children to be listed as OR Intent options! Trim and iterate that intent tree.',
                        'ln_type_entity_id' => 4246, //Platform Error
                        'ln_child_entity_id' => $en_id, //Affected student
                        'ln_parent_intent_id' => $this->config->item('in_featured'), //Featured intentions has an overflow!
                        'ln_child_intent_id' => $in['in_id'],
                    ));

                    //Quick reply accepts 11 options max:
                    break;

                }


                $message .= "\n\n" . ( $count+1 ) . '. ' . echo_in_outcome($in['in_outcome'], true);
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => ( $count+1 ),
                    'payload' => 'SUBSCRIBE-INITIATE_' . $in['in_id'],
                ));

            }

            array_push($quick_replies, array(
                'content_type' => 'text',
                'title' => 'Cancel',
                'payload' => 'NOTINTERESTED',
            ));

            //Inform user that they are now complete with all steps:
            $this->Communication_model->dispatch_message(
                $message,
                array('en_id' => $en_id),
                true,
                $quick_replies,
                array(
                    'ln_parent_intent_id' => $this->config->item('in_featured'),
                )
            );

        } else {

            //Student has already taken all featured intentions and there is nothing else to offer them:
            $this->Communication_model->dispatch_message(
                'You have already added all featured intentions to your Action Plan and I have nothing else to recommend to you at this time.',
                array('en_id' => $en_id),
                true
            );


        }

    }



    function facebook_graph($action, $graph_url, $payload = array())
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
        $fb_credentials = $this->config->item('fb_credentials');
        $fb_settings = $this->config->item('fb_settings');

        $access_token_payload = array(
            'access_token' => $fb_credentials['mench_access_token']
        );

        if ($action == 'GET' && count($payload) > 0) {
            //Add $payload to GET variables:
            $access_token_payload = array_merge($payload, $access_token_payload);
            $payload = array();
        }

        $graph_url = 'https://graph.facebook.com/' . $fb_settings['default_graph_version'] . $graph_url;
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
            $message_error = 'Communication_model->facebook_graph() failed to ' . $action . ' ' . $graph_url;
            $this->Links_model->ln_create(array(
                'ln_content' => $message_error,
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
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


    function digest_quick_reply($en, $quick_reply_payload)
    {

        /*
         *
         * With the assumption that chat platforms like Messenger,
         * Slack and Telegram all offer a mechanism to manage a reference
         * field other than the actual message itself (Facebook calls
         * this the Reference key or Metadata), this function will
         * process that metadata string from incoming messages sent to Mench
         * by its Students and take appropriate action.
         *
         * Inputs:
         *
         * - $en:                   The Student who made the request
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

        } elseif (substr_count($quick_reply_payload, 'APPENDRESPONSE_') == 1) {

            $action_append = one_two_explode('APPENDRESPONSE_', '', $quick_reply_payload);

            if($action_append=='CANCEL'){

                //They selected none of the options:
                $this->Communication_model->dispatch_message(
                    'ok got it 🙌 I will not append this message to any of your Action Plan steps.',
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

            } else {

                //Should be a specific append command:
                $append_link_ids = explode('_',$action_append);
                $message_ln_id = intval($append_link_ids[0]);
                $ap_step_ln_id = intval($append_link_ids[1]);

                if($message_ln_id>0 && $ap_step_ln_id>0){

                    //Validate message:
                    $new_message_links = $this->Links_model->ln_fetch(array(
                        'ln_id' => $message_ln_id,
                    ));

                    //Validate Action Plan step:
                    $pending_in_requirements = $this->Links_model->ln_fetch(array(
                        'ln_id' => $ap_step_ln_id,
                        //Also validate other requirements:
                        'ln_type_entity_id' => 6144, //Action Plan Submit Requirements
                        'ln_miner_entity_id' => $en['en_id'], //for this student
                        'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete
                        'in_status' => 2, //Published
                    ), array('in_parent'));

                }


                if(!isset($pending_in_requirements[0]) || !isset($new_message_links[0])){
                    return array(
                        'status' => 0,
                        'message' => 'Invalid command to mark step as complete',
                    );
                }


                //All good!
                $en_all_4592 = $this->config->item('en_all_4592');

                //Make changes:
                $this->Links_model->ln_update($pending_in_requirements[0]['ln_id'], array(
                    'ln_content' => $new_message_links[0]['ln_content'],
                    'ln_status' => 2,
                    'ln_parent_link_id' => $new_message_links[0]['ln_id'],
                ), $en['en_id']);

                //Confirm with student:
                $this->Communication_model->dispatch_message(
                    echo_random_message('affirm_progress'),
                    $en,
                    true
                );
                $this->Communication_model->dispatch_message(
                    'I added your '.$en_all_4592[$pending_in_requirements[0]['in_requirement_entity_id']]['m_name'].' message to '.$pending_in_requirements[0]['in_outcome'].'. /link:See in 🚩Action Plan:https://mench.com/messenger/actionplan/' . $pending_in_requirements[0]['in_id'],
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

        } elseif (substr_count($quick_reply_payload, 'UNSUBSCRIBE_') == 1) {

            $action_unsubscribe = one_two_explode('UNSUBSCRIBE_', '', $quick_reply_payload);

            if ($action_unsubscribe == 'CANCEL') {

                //Student seems to have changed their mind, confirm with them:
                $this->Communication_model->dispatch_message(
                    'Awesome, I am excited to continue our work together.',
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

            } elseif ($action_unsubscribe == 'ALL') {

                //Student wants to completely unsubscribe from Mench:
                $removed_intents = 0;
                foreach ($this->Links_model->ln_fetch(array(
                    'ln_miner_entity_id' => $en['en_id'],
                    'ln_type_entity_id' => 4235, //Action Plan Set Intention
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                )) as $ln) {
                    $removed_intents++;
                    $this->Links_model->ln_update($ln['ln_id'], array(
                        'ln_status' => -1, //Removed
                    ), $en['en_id']); //Give credit to miner
                }

                //Update Student communication level to Unsubscribe:
                $this->Entities_model->en_radio_set(4454, 4455, $en['en_id'], $en['en_id']);

                //Let them know about these changes:
                $this->Communication_model->dispatch_message(
                    'Confirmed, I removed ' . $removed_intents . ' intention' . echo__s($removed_intents) . ' from your Action Plan. This is the final message you will receive from me unless you message me again. I hope you take good care of yourself 😘',
                    $en,
                    true
                );

            } elseif (is_numeric($action_unsubscribe)) {

                //User wants to Remove a specific Action Plan, validate it:
                $student_intents = $this->Links_model->ln_fetch(array(
                    'ln_miner_entity_id' => $en['en_id'],
                    'ln_type_entity_id' => 4235, //Action Plan Set Intention
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                    'ln_parent_intent_id' => $action_unsubscribe,
                ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

                //All good?
                if (count($student_intents) < 1) {
                    return array(
                        'status' => 0,
                        'message' => 'UNSUBSCRIBE_ Failed to skip an intent from the master Action Plan',
                    );
                }

                //Update status for this single Action Plan:
                $this->Links_model->ln_update($student_intents[0]['ln_id'], array(
                    'ln_status' => -1, //Removed
                ), $en['en_id']);

                //Re-sort remaining Action Plan intentions:
                foreach($this->Links_model->ln_fetch(array(
                    'ln_type_entity_id' => 4235, //Action Plan Set Intention
                    'ln_miner_entity_id' => $en['en_id'], //Belongs to this Student
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                ), array(), 0, 0, array('ln_order' => 'ASC')) as $count => $ln){
                    $this->Links_model->ln_update($ln['ln_id'], array(
                        'ln_order' => ($count+1),
                    ), $en['en_id']);
                }

                //Show success message to user:
                $this->Communication_model->dispatch_message(
                    'I have successfully removed the intention to ' . $student_intents[0]['in_outcome'] . ' from your Action Plan.',
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

        } elseif (substr_count($quick_reply_payload, 'RESUBSCRIBE_') == 1) {

            if ($quick_reply_payload == 'RESUBSCRIBE_YES') {

                //Update User communication level to Receive Silent Push Notifications:
                $this->Entities_model->en_radio_set(4454, 4457, $en['en_id'], $en['en_id']);

                //Inform them:
                $this->Communication_model->dispatch_message(
                    'Sweet, your account is now activated but you don\'t have any intentions added to your Action Plan yet.',
                    $en,
                    true
                );

                //List featured intents and let them choose:
                $this->Communication_model->suggest_featured_intents($en['en_id']);

            } elseif ($quick_reply_payload == 'RESUBSCRIBE_NO') {

                $this->Communication_model->dispatch_message(
                    'Ok, I will keep you unsubscribed 🙏',
                    $en,
                    true
                );

            }

        } elseif ($quick_reply_payload == 'SUBSCRIBE-REJECT') {

            //They rejected the offer... Acknowledge and give response:
            $this->Communication_model->dispatch_message(
                'Ok, so how can I help you land your dream programming job?',
                $en,
                true
            );

            //List featured intents and let them choose:
            $this->Communication_model->suggest_featured_intents($en['en_id']);

        } elseif (is_numeric($quick_reply_payload)) {

            //This is the Intent ID that they are interested to Subscribe to.

            $in_id = intval($quick_reply_payload);

            //Validate Intent:
            $ins = $this->Intents_model->in_fetch(array(
                'in_id' => $in_id,
            ));
            if (count($ins) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'Failed to locate published intent to subscribe to',
                );
            }


            //Give response:
            if ($ins[0]['in_status'] < 2) {

                //Ooopsi Intention is not published:
                $this->Communication_model->dispatch_message(
                    'I cannot subscribe you to ' . $ins[0]['in_outcome'] . ' as its currently not published.',
                    $en,
                    true
                );

            } else {

                //Confirm if they are interested to subscribe to this intention:
                $this->Communication_model->dispatch_message(
                    'Hello hello 👋 are you interested to ' . $ins[0]['in_outcome'] . '?',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Yes, Learn More',
                            'payload' => 'SUBSCRIBE-INITIATE_' . $ins[0]['in_id'],
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'Cancel',
                            'payload' => 'SUBSCRIBE-REJECT',
                        ),
                    ),
                    array(
                        'ln_child_intent_id' => $ins[0]['in_id'],
                    )
                );

            }

        } elseif ($quick_reply_payload=='NOTINTERESTED') {

            //Affirm and educate:
            $this->Communication_model->dispatch_message(
                'Got it. '.echo_random_message('command_me'),
                $en,
                true
            );

        } elseif (substr_count($quick_reply_payload, 'SUBSCRIBE-INITIATE_') == 1) {

            //Student has confirmed their desire to subscribe to an intention:
            $in_id = intval(one_two_explode('SUBSCRIBE-INITIATE_', '', $quick_reply_payload));

            //Initiating an intent Action Plan:
            $ins = $this->Intents_model->in_fetch(array(
                'in_id' => $in_id,
                'in_status' => 2, //Published
            ));

            if (count($ins) != 1) {
                return array(
                    'status' => 0,
                    'message' => 'SUBSCRIBE-INITIATE_ Failed to locate published intent',
                );
            }

            //Make sure intention has not already been added to student Action Plan:
            if (count($this->Links_model->ln_fetch(array(
                    'ln_miner_entity_id' => $en['en_id'],
                    'ln_type_entity_id' => 4235, //Action Plan Set Intention
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                    'ln_parent_intent_id' => $ins[0]['in_id'],
                ))) > 0) {

                //Let Student know that they have already subscribed to this intention:
                $this->Communication_model->dispatch_message(
                    'The intention to ' . $ins[0]['in_outcome'] . ' has already been added to your Action Plan. /link:See in 🚩Action Plan:https://mench.com/messenger/actionplan/' . $ins[0]['in_id'],
                    $en,
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

            } else {

                //Do final confirmation by giving Student more context on this intention before adding to their Action Plan...

                //See if we have an overview:
                $overview_message = '';
                $source_info = echo_tree_references($ins[0], true);
                $step_info = echo_tree_steps($ins[0], true);
                $time_info = echo_tree_time_estimate($ins[0], true);

                if($source_info || $step_info || $time_info){
                    $overview_message = 'Here is an overview:' . "\n\n" . $source_info . $step_info . $time_info . "\n";
                }

                //Send message for final confirmation with the overview of how long/difficult it would be to accomplish this intention:
                $this->Communication_model->dispatch_message(
                    $overview_message . 'Should I add the intention to ' . $ins[0]['in_outcome'] . ' to your Action Plan?',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Add To Action Plan',
                            'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'],
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'Cancel',
                            'payload' => 'SUBSCRIBE-REJECT',
                        ),
                    ),
                    array(
                        'ln_parent_intent_id' => $ins[0]['in_id'],
                    )
                );

            }

        } elseif ($quick_reply_payload == 'GONEXT') {

            //Fetch and communicate next intent:
            $this->Actionplan_model->actionplan_find_next_step($en['en_id'], true, true);

        } elseif (substr_count($quick_reply_payload, 'SUBSCRIBE-CONFIRM_') == 1) {

            //Student has requested to add this intention to their Action Plan:
            $in_id = intval(one_two_explode('SUBSCRIBE-CONFIRM_', '', $quick_reply_payload));

            //Add to Action Plan:
            $this->Actionplan_model->actionplan_add($en['en_id'], $in_id);

        } elseif (substr_count($quick_reply_payload, 'INFORMREQUIREMENT_') == 1) {

            //Educate students on how to submit the intent completion requirement:
            $in_id = intval(one_two_explode('INFORMREQUIREMENT_', '', $quick_reply_payload));

            //Fetch intent details:
            $req_ins = $this->Intents_model->in_fetch(array(
                'in_id' => $in_id,
                'in_status' => 2, //Published
            ));
            if(count($req_ins) < 1){
                return array(
                    'status' => 0,
                    'message' => 'INFORMREQUIREMENT_ failed to locate published intent',
                );
            }

            //Load message types:
            $en_all_4331 = $this->config->item('en_all_4331');

            //Inform Student:
            $this->Communication_model->dispatch_message(
                'Ok! Simply send me a '.$en_all_4331[$req_ins[0]['in_requirement_entity_id']]['m_name'].' message to complete the intention to '.echo_in_outcome($req_ins[0]['in_outcome'], true),
                $en,
                true,
                array(),
                array(
                    'ln_parent_intent_id' => $in_id,
                )
            );

        } elseif (substr_count($quick_reply_payload, 'SKIP-ACTIONPLAN_') == 1) {

            //Extract variables from REF:
            $input_parts = explode('_', one_two_explode('SKIP-ACTIONPLAN_', '', $quick_reply_payload));
            $ln_status = intval($input_parts[0]); //It would be $ln_status=1 initial (drafting) and then would change to either -1 IF skip was cancelled or 2 IF skip was confirmed.
            $in_id = intval($input_parts[1]); //Intention to Skip

            //Validate inputs:
            if ($in_id < 1 || !in_array($ln_status, array(-1, 1, 2))) {
                return array(
                    'status' => 0,
                    'message' => 'SKIP-ACTIONPLAN_ received invalid inputs',
                );
            }


            //Was this initiating?
            if ($ln_status == 1) {

                //User has indicated they want to skip this tree and move on to the next item in-line:
                //Lets confirm the implications of this SKIP to ensure they are aware:
                $this->Actionplan_model->actionplan_skip_initiate($en, $in_id);

            } else {

                //They have either confirmed or cancelled the skip:
                if ($ln_status == -1) {

                    //user changed their mind and does not want to skip anymore
                    $message = 'I\'m glad you changed your mind! Let\'s continue...';

                } elseif ($ln_status == 2) {

                    //Actually skip and see if we've finished this Action Plan:
                    $this->Actionplan_model->actionplan_skip_recursive_down($en['en_id'], $in_id);

                    //Confirm the skip:
                    $message = 'Got it! I successfully skipped all steps';

                }

                //Inform Student of Skip status:
                $this->Communication_model->dispatch_message(
                    $message,
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Next',
                            'payload' => 'GONEXT',
                        )
                    ),
                    array(
                        'ln_parent_intent_id' => $in_id,
                    )
                );

                //Communicate next step:
                $this->Actionplan_model->actionplan_find_next_step($en['en_id'], true, true);

            }

        } elseif (substr_count($quick_reply_payload, 'ANSWERQUESTION_') == 1) {

            /*
             *
             * When the student answers a quick reply question.
             *
             * */

            //Extract variables:
            $quickreply_parts = explode('_', one_two_explode('ANSWERQUESTION_', '', $quick_reply_payload));
            $parent_in_id = intval($quickreply_parts[0]);
            $answer_in_id = intval($quickreply_parts[1]);
            if($parent_in_id < 1 || $answer_in_id < 1){
                return array(
                    'status' => 0,
                    'message' => 'ANSWERQUESTION_ missing core variables ['.$parent_in_id.'] & ['.$answer_in_id.']',
                );
            }

            //Validate Answer Intent:
            $answer_ins = $this->Intents_model->in_fetch(array(
                'in_id' => $answer_in_id,
                'in_status' => 2, //Published
            ));
            if(count($answer_ins) < 1){
                return array(
                    'status' => 0,
                    'message' => 'ANSWERQUESTION_ was unable to locate published answer',
                );
            }

            //We should already have a link for this, so let's find and update it:
            $pending_answer_links = $this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $en['en_id'],
                'ln_type_entity_id' => 6157, //Action Plan Question Answered
                'ln_parent_intent_id' => $parent_in_id,
                'ln_status >=' => 0, //New+
            ));
            if(count($pending_answer_links) < 1){
                return array(
                    'status' => 0,
                    'message' => 'ANSWERQUESTION_ was unable to locate the pending answer link',
                );
            }


            //All good, let's save the answer:
            $published_answer = false;
            foreach($pending_answer_links as $ln){
                if(in_array($ln['ln_status'], $this->config->item('ln_status_incomplete'))){

                    //We just found a pending answer:
                    $published_answer = true;

                    //Mark it as published while saving the answer:
                    $this->Links_model->ln_update($ln['ln_id'], array(
                        'ln_child_intent_id' => $answer_in_id, //Save answer
                        'ln_status' => 2, //Publish answer
                    ), $en['en_id']);

                } elseif($ln['ln_child_intent_id'] != $answer_in_id){

                    //This is a published & different answer!
                    return array(
                        'status' => 0,
                        'message' => 'ANSWERQUESTION_ updated a previously answered question which should not happen!',
                    );

                }
            }

            //Did we succeed?
            if(!$published_answer){
                return array(
                    'status' => 0,
                    'message' => 'ANSWERQUESTION_ failed to publish student answer',
                );
            }


            //See if we also need to mark the child as complete:
            $this->Actionplan_model->actionplan_complete_if_empty($en['en_id'], $answer_ins[0]);

            //Affirm answer received answer:
            $this->Communication_model->dispatch_message(
                echo_random_message('affirm_progress'),
                $en,
                true
            );

            //Find/communicate the next step:
            $this->Actionplan_model->actionplan_find_next_step($en['en_id'], true, true);

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


    function digest_text_message($en, $fb_received_message)
    {

        /*
         *
         * Will process the chat message only in the absence of a chat metadata
         * otherwise the digest_quick_reply() will process the message since we
         * know that the medata would have more precise instructions on what
         * needs to be done for the Student response.
         *
         * This involves string analysis and matching terms to a intents, entities
         * and known commands that will help us understand the Student and
         * hopefully provide them with the information they need, right now.
         *
         * We'd eventually need to migrate the search engine to an NLP platform
         * Like dialogflow.com (By Google) or wit.ai (By Facebook) to improve
         * our ability to detect correlations specifically for intents.
         *
         * */

        if (!$fb_received_message) {
            return false;
        }


        /*
         *
         * Ok, now attempt to understand Student's message intention.
         * We would do a very basic work pattern match to see what
         * we can understand from their message, and we would expand
         * upon this section as we improve our NLP technology.
         *
         *
         * */

        $fb_received_message = trim(strtolower($fb_received_message));

        if (in_array($fb_received_message, array('next', 'continue'))) {

            //Give them the next step of their Action Plan:
            $step = $this->Actionplan_model->actionplan_find_next_step($en['en_id'], true, true);

        } elseif (in_array($fb_received_message, array('yes', 'yeah', 'ya', 'ok', '▶️', 'ok continue', 'go', 'yass', 'yas', 'yea', 'yup', 'yes, learn more'))) {

            $this->Communication_model->dispatch_message(
                'I am not yet trained to accept your affirmation.',
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

        } elseif ($fb_received_message == 'skip') {

            //Find the next intent in the Action Plan to skip:
            $next_in_id = $this->Actionplan_model->actionplan_find_next_step($en['en_id'], false);

            if($next_in_id > 0){
                //Initiate skip request:
                $this->Actionplan_model->actionplan_skip_initiate($en, $next_in_id);
            } else {
                $this->Communication_model->dispatch_message(
                    'I could not find any Action Plan steps to skip.',
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

        } elseif (in_array($fb_received_message, array('help', 'support', 'f1', 'sos'))) {

            //Ask the user if they like to be connected to a human
            //IF yes, create a ATTENTION NEEDED link that would notify admin so admin can start a manual conversation
            $this->Communication_model->dispatch_message(
                'I am not yet trained to provide help.',
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

        } elseif (in_array($fb_received_message, array('no', 'nope', 'nah', 'cancel'))) {

            //Rejecting an offer...
            $this->Communication_model->dispatch_message(
                'I am not yet trained to accept your rejection.',
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

        } elseif (is_numeric($fb_received_message)) {

            //Likely an OR response with a specific number in mind...
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

        } elseif (includes_any($fb_received_message, array('unsubscribe', 'stop'))) {

            //List their Action Plan intentions and let student choose which one to unsubscribe:
            $student_intents = $this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $en['en_id'],
                'ln_type_entity_id' => 4235, //Action Plan Set Intention
                'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                'in_status' => 2, //Published
            ), array('in_parent'), 10 /* Max quick replies allowed */, 0, array('ln_order' => 'ASC'));

            //Do they have anything in their Action Plan?
            if (count($student_intents) > 0) {

                //Give them options to remove specific Action Plans:
                $quick_replies = array();
                $message = 'Choose one of the following options:';
                $increment = 1;

                foreach ($student_intents as $counter => $in) {
                    //Construct unsubscribe confirmation body:
                    $message .= "\n\n" . ($counter + $increment) . '. Stop ' . $in['in_outcome'];
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($counter + $increment),
                        'payload' => 'UNSUBSCRIBE_' . $in['in_id'],
                    ));
                }

                if (count($student_intents) >= 2) {
                    //Give option to skip all and unsubscribe:
                    $increment++;
                    $message .= "\n\n" . ($counter + $increment) . '. Stop all intentions and unsubscribe';
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

                //Send out message and let them confirm:
                $this->Communication_model->dispatch_message(
                    $message,
                    $en,
                    true,
                    $quick_replies
                );

            } else {

                //No intentions found in their Action Plan, so we assume they just want to Unsubscribe and stop all future communications:
                $this->Communication_model->dispatch_message(
                    'Just to confirm, do you want to unsubscribe and stop all future communications with me and unsubscribe?',
                    $en,
                    true,
                    array(
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
                    )
                );

            }

        } elseif (substr(strtolower(trim($fb_received_message)), 0, 9) == 'i want to') {


            //This looks like they are giving us a command:
            $master_command = trim(substr(trim($fb_received_message), 9));
            $result_limit = 6;

            //Make sure algolia is enabled:
            if (!$this->config->item('app_enable_algolia')) {
                $this->Communication_model->dispatch_message(
                    'Currently I cannot search for any intentions. Try again later.',
                    $en,
                    true
                );
                return false;
            }


            $search_index = load_php_algolia('alg_index');
            $res = $search_index->search($master_command, [
                'hitsPerPage' => $result_limit,
                'filters' => 'alg_obj_is_in=1 AND alg_obj_status=2 AND alg_obj_published_children>=7', //Search published intents with more than 7 published children
            ]);
            $search_results = $res['hits'];


            //Log intent search:
            $this->Links_model->ln_create(array(
                'ln_content' => 'Found ' . count($search_results) . ' intent' . echo__s(count($search_results)) . ' matching "' . $master_command . '"',
                'ln_metadata' => array(
                    'app_enable_algolia' => $this->config->item('app_enable_algolia'),
                    'input_data' => $master_command,
                    'output' => $search_results,
                ),
                'ln_miner_entity_id' => $en['en_id'], //user who searched
                'ln_type_entity_id' => 4275, //Search for New Intent Action Plan
            ));


            //Show options for the Student to add to their Action Plan:
            $new_intent_count = 0;
            $quick_replies = array();

            foreach ($search_results as $alg) {

                //Make sure not already in Action Plan:
                if(count($this->Links_model->ln_fetch(array(
                    'ln_miner_entity_id' => $en['en_id'],
                    'ln_type_entity_id' => 4235, //Action Plan Set Intention
                    'ln_status IN (' . join(',', $this->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
                    'ln_parent_intent_id' => $alg['alg_obj_id'],
                ))) > 0){
                    continue;
                }

                $new_intent_count++;

                if($new_intent_count==1){
                    $message = 'I found these intentions:';
                }

                //Fetch metadata:
                $ins = $this->Intents_model->in_fetch(array(
                    'in_id' => $alg['alg_obj_id'],
                ));

                //Show Message:
                $message .= "\n\n" . $new_intent_count . '. ' . $ins[0]['in_outcome'] . ' in ' . strip_tags(echo_time_range($ins[0]));
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => $new_intent_count,
                    'payload' => 'SUBSCRIBE-INITIATE_' . $ins[0]['in_id'],
                ));

            }


            if($new_intent_count > 0){

                //Give them a "None of the above" option:
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Cancel',
                    'payload' => 'SUBSCRIBE-REJECT',
                ));

                //return what we found to the student to decide:
                $this->Communication_model->dispatch_message(
                    $message,
                    $en,
                    true,
                    $quick_replies
                );

            } else {

                //Respond to user:
                $this->Communication_model->dispatch_message(
                    'I did not find any intentions to "' . $master_command . '", but I have made a note of this and will let you know as soon as I am trained on this.',
                    $en,
                    true
                );

                //List featured intents and let them choose:
                $this->Communication_model->suggest_featured_intents($en['en_id']);

            }

        } else {


            /*
             *
             * Ok, if we're here it means we didn't really understand what
             * the Student's intention was within their message.
             * So let's run through a few more options before letting them
             * know that we did not understand them...
             *
             * */

            //First, let's check to see if a Mench admin has not started a manual conversation with them via Facebook Inbox Chat:
            $admin_conversations = $this->Links_model->ln_fetch(array(
                'ln_order' => 1, //A HACK to identify messages sent from us via Facebook Page Inbox
                'ln_miner_entity_id' => $en['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4280')) . ')' => null, //Student/Miner Received Message Links
                'ln_timestamp >=' => date("Y-m-d H:i:s", (time() - (1800))), //Messages sent from us less than 30 minutes ago
            ), array(), 1);
            if (count($admin_conversations) > 0) {

                //Yes, this user is talking to an admin so do not interrupt their conversation:
                return false;

            } else {

                //Inform Student of Mench's one-way communication limitation & that Mench did not understand their message:
                $this->Communication_model->dispatch_message(
                    echo_random_message('one_way_only'),
                    $en,
                    true
                );

                //Log link:
                $this->Links_model->ln_create(array(
                    'ln_miner_entity_id' => $en['en_id'], //User who initiated this message
                    'ln_content' => $fb_received_message,
                    'ln_type_entity_id' => 4287, //Log Unrecognizable Message Received
                ));

                //Call to Action: Does this student have any Action Plans?
                $next_in_id = $this->Actionplan_model->actionplan_find_next_step($en['en_id'], false);

                if($next_in_id > 0){

                    //Inform Student of Mench's one-way communication limitation & that Mench did not understand their message:
                    $this->Communication_model->dispatch_message(
                        'You can continue with your Action Plan by saying "Next"',
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

                } else {

                    //Recommend to join:
                    $this->Communication_model->suggest_featured_intents($en['en_id']);

                }
            }
        }
    }



    function dispatch_email($to_array, $to_en_ids, $subject, $html_message)
    {

        /*
         *
         * Send an email via our Amazon server
         *
         * */

        if (is_dev()) {
            return false; //We cannot send emails on Dev server
        }

        //Loadup amazon SES:
        require_once('application/libraries/aws/aws-autoloader.php');
        $this->CLIENT = new Aws\Ses\SesClient([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => $this->config->item('aws_credentials'),
        ]);

        $result = $this->CLIENT->sendEmail(array(
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

        foreach($to_en_ids as $to_en_id){
            $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 5967, //Email Sent
                'ln_miner_entity_id' => $to_en_id,
                'ln_content' => '<b>SUBJECT: '.$subject.'</b><hr />' . $html_message,
                'ln_metadata' => array(
                    'to_array' => $to_array,
                    'subject' => $subject,
                    'html_message' => $html_message,
                    'result' => $result,
                ),
            ));
        }

        return $result;

    }

}