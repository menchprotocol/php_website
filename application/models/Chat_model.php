<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Chat_model extends CI_Model
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


    function fn___dispatch_message($input_message, $recipient_en = array(), $fb_messenger_format = false, $quick_replies = array(), $tr_append = array())
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
         * - $tr_append:            Since this function logs a "message sent" engagement for
         *                          every message it processes, the $tr_append will append
         *                          additional data to capture more context for this message.
         *                          Supported fields only include:
         *
         *                          - $tr_append['tr_in_parent_id']
         *                          - $tr_append['tr_in_child_id']
         *                          - $tr_append['tr_tr_id']
         *
         *                          Following fields are not allowed, because:
         *
         *                          - $tr_append['tr_metadata']: Reserved for message body IF $fb_messenger_format = TRUE
         *                          - $tr_append['tr_timestamp']: Auto generated to current timestamp
         *                          - $tr_append['tr_status']: Will always equal 2 as a completed message
         *                          - $tr_append['tr_type_en_id']: Auto calculated based on message content (or error)
         *                          - $tr_append['tr_miner_en_id']: Mench will always get credit to miner, so this is set to zero
         *                          - $tr_append['tr_en_parent_id']: This is auto set with an entity reference within $input_message
         *                          - $tr_append['tr_en_child_id']: This will be equal to $recipient_en['en_id']
         *
         * */

        //Validate message:
        $msg_validation = $this->Chat_model->fn___dispatch_validate_message($input_message, $recipient_en, $fb_messenger_format, $quick_replies);
        $is_miner = fn___en_auth(array(1308));

        //Prepare data to be appended to success/fail transaction:
        $allowed_tr_append = array('tr_in_parent_id', 'tr_in_child_id', 'tr_tr_id');
        $filtered_tr_append = array();
        foreach ($tr_append as $key => $value) {
            if (in_array($key, $allowed_tr_append)) {
                $filtered_tr_append[$key] = $value;
            }
        }


        //Did we have ane error in message validation?
        if (!$msg_validation['status']) {

            //Log Error Transaction:
            $this->Database_model->fn___tr_create(array_merge(array(
                'tr_type_en_id' => 4246, //Platform Error
                'tr_content' => 'fn___dispatch_validate_message() returned error [' . $msg_validation['message'] . '] for input message [' . $input_message . ']',
                'tr_en_child_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
            ), $filtered_tr_append));

            return false;
        }

        //Message validation passed...
        $html_message_body = '';

        //Log message sent transaction:
        foreach ($msg_validation['output_messages'] as $output_message) {

            //Dispatch message based on format:
            if ($fb_messenger_format) {

                //Attempt to dispatch message via Facebook Graph API:
                $fb_graph_process = $this->Chat_model->fn___facebook_graph('POST', '/me/messages', $output_message['message_body']);

                //Did we have an Error from the Facebook API side?
                if (!$fb_graph_process['status']) {

                    //Ooopsi, we did! Log error Transcation:
                    $this->Database_model->fn___tr_create(array_merge(array(
                        'tr_type_en_id' => 4246, //Platform Error
                        'tr_content' => 'fn___dispatch_message() failed to send message via Facebook Graph API. See Metadata log for more details.',
                        'tr_en_child_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                        'tr_metadata' => array(
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

            //Log successful Transaction for message delivery (Unless Miners viewing HTML):
            if(!($is_miner && !$fb_messenger_format) || isset($_GET['log_miner_messages'])){
                $this->Database_model->fn___tr_create(array_merge(array(
                    'tr_content' => $msg_validation['input_message'],
                    'tr_type_en_id' => $output_message['message_type'],
                    'tr_en_child_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                    'tr_en_parent_id' => $msg_validation['tr_en_parent_id'], //Might be set if message had a referenced entity
                    'tr_metadata' => array(
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


    function fn___dispatch_validate_message($input_message, $recipient_en = array(), $fb_messenger_format = false, $quick_replies = array())
    {

        /*
         *
         * This function is used to validate intent messages.
         *
         * See fn___dispatch_message() for more information on input variables.
         *
         * */


        //Start with basic input validation:
        if (strlen($input_message) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message Content',
            );
        } elseif (strlen($input_message) > $this->config->item('tr_content_max')) {
            return array(
                'status' => 0,
                'message' => 'Message is longer than the allowed ' . $this->config->item('tr_content_max') . ' characters',
            );
        } elseif ($input_message != strip_tags($input_message)) {
            return array(
                'status' => 0,
                'message' => 'HTML Code is not allowed',
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
        }


        /*
         *
         * Analyze & Validate message references
         *
         * */
        $msg_references = fn___extract_message_references($input_message);

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

        } elseif (count($msg_references['ref_entities']) > 0 && count($msg_references['ref_urls']) > 0) {

            return array(
                'status' => 0,
                'message' => 'You can either reference 1 entity OR 1 URL (As the URL will be transformed into an entity)',
            );

        } elseif (count($msg_references['ref_commands']) > 0 && count($msg_references['ref_commands']) !== count(array_unique($msg_references['ref_commands']))) {

            return array(
                'status' => 0,
                'message' => 'Each /command can only be used once per message',
            );

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
            $ens = $this->Database_model->fn___en_fetch(array(
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
            $en_convert_4454 = $this->config->item('en_convert_4454');

            //Fetch recipient notification type:
            $trs_comm_level = $this->Database_model->fn___tr_fetch(array(
                'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4454')) . ')' => null,
                'tr_en_child_id' => $recipient_en['en_id'],
                'tr_status >=' => 2,
            ));

            //Start validating communication settings we fetched to ensure everything is A-OK:
            if (count($trs_comm_level) < 1) {

                return array(
                    'status' => 0,
                    'message' => 'Student is missing their Notification Level parent entity relation',
                );

            } elseif (count($trs_comm_level) > 1) {

                //This should find exactly one result as it belongs to Student Radio Entity @4461
                return array(
                    'status' => 0,
                    'message' => 'Student has more than 1 Notification Level parent entity relation',
                );

            } elseif ($trs_comm_level[0]['tr_en_parent_id'] == 4455) {

                return array(
                    'status' => 0,
                    'message' => 'Student is unsubscribed',
                );

            } elseif (!array_key_exists($trs_comm_level[0]['tr_en_parent_id'], $en_convert_4454)) {

                return array(
                    'status' => 0,
                    'message' => 'Fetched unknown Notification Level [' . $trs_comm_level[0]['tr_en_parent_id'] . ']',
                );

            }

            //All good, Set notification type:
            $notification_type = $en_convert_4454[$trs_comm_level[0]['tr_en_parent_id']];

        }


        /*
         *
         * Process Possible URL
         * (turn URL into an entity reference)
         *
         * */
        if (count($msg_references['ref_urls']) > 0) {

            //No entity linked, but we have a URL that we should turn into an entity:
            $created_url = $this->Matrix_model->fn___en_url_add($msg_references['ref_urls'][0]);

            //Did we have an error?
            if (!$created_url['status']) {
                return $created_url;
            }

            //Transform this URL into an entity:
            $msg_references['ref_entities'][0] = $created_url['en_from_url']['en_id'];

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
        $output_body_message = $input_message;

        if (in_array('/firstname', $msg_references['ref_commands'])) {

            //We sometimes may need to set a default recipient entity name IF /firstname command used without any recipient entity passed:
            if (!isset($recipient_en['en_name'])) {
                //This is a guest Student, so use the default:
                $recipient_en['en_name'] = 'Student';
            }

            //Replace name with command:
            $output_body_message = str_replace('/firstname', fn___one_two_explode('', ' ', $recipient_en['en_name']), $output_body_message);

        }


        //Determine if we have a button link:
        $fb_button_title = null;
        $fb_button_url = null;
        if (in_array('/link', $msg_references['ref_commands'])) {

            //Validate /link format:
            $link_anchor = fn___one_two_explode('/link:', ':http', $output_body_message);
            $link_url = 'http' . fn___one_two_explode(':http', ' ', $output_body_message);

            if (strlen($link_anchor) < 1 || !filter_var($link_url, FILTER_VALIDATE_URL)) {
                return array(
                    'status' => 0,
                    'message' => 'Invalid /link command! Proper format is: /link:ANCHOR:URL for example: /link:Open Google:https://google.com',
                );
            } elseif (strlen($link_anchor) > 20) {
                return array(
                    'status' => 0,
                    'message' => '/link anchor text cannot be longer than 20 characters',
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
            $slice_times = explode(':', fn___one_two_explode('/slice:', ' ', $output_body_message), 2);

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

        //The HTML Format (IF $fb_messenger_format = FALSE) would slightly change if a logged-in Miner is detected:
        $is_miner = fn___en_auth(array(1308));

        //We assume this message has text, unless its only content is an entity reference like "@123"
        $has_text = true;

        if (count($msg_references['ref_entities']) > 0) {

            //We have a reference within this message, let's fetch it to better understand it:
            $ens = $this->Database_model->fn___en_fetch(array(
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
            $en_convert_4537 = $this->config->item('en_convert_4537');

            //We send Media in their original format IF $fb_messenger_format = TRUE, which means we need to convert transaction types:
            if ($fb_messenger_format) {
                //Converts Entity Link Types to their corresponding Student Message Sent Transaction Types:
                $master_media_sent_conv = array(
                    4258 => 4553, //video
                    4259 => 4554, //audio
                    4260 => 4555, //image
                    4261 => 4556, //file
                );
            }


            //Determine what type of Media this reference has:
            foreach ($ens[0]['en__parents'] as $parent_en) {

                if (array_key_exists($parent_en['tr_type_en_id'], $en_convert_4537)) {

                    //Raw media file: Audio, Video, Image OR File...

                    //Search for Facebook Attachment ID IF $fb_messenger_format = TRUE
                    $fb_att_id = 0;
                    if ($fb_messenger_format && strlen($parent_en['tr_metadata']) > 0) {
                        //We might have a Facebook Attachment ID saved in Metadata, check to see:
                        $metadata = unserialize($parent_en['tr_metadata']);
                        if (isset($metadata['fb_att_id']) && intval($metadata['fb_att_id']) > 0) {
                            //Yes we do, use this for faster media attachments:
                            $fb_att_id = intval($metadata['fb_att_id']);
                        }
                    }


                    if ($fb_messenger_format) {

                        //Push raw file to Media Array:
                        array_push($fb_media_attachments, array(
                            'tr_type_en_id' => $master_media_sent_conv[$parent_en['tr_type_en_id']],
                            'tr_content' => ($fb_att_id > 0 ? null : $parent_en['tr_content']),
                            'fb_att_id' => $fb_att_id,
                            'fb_att_type' => $en_convert_4537[$parent_en['tr_type_en_id']],
                        ));

                    } else {

                        //HTML Format, append content to current output message:
                        $output_body_message .= '<div style="margin-top:7px;">' . fn___echo_url_type($parent_en['tr_content'], $parent_en['tr_type_en_id']) . '</div>';

                    }

                } elseif ($parent_en['tr_type_en_id'] == 4256) {

                    if ($fb_messenger_format) {

                        //Generic URL:
                        array_push($fb_media_attachments, array(
                            'tr_type_en_id' => 4552, //Text Message Sent
                            'tr_content' => $parent_en['tr_content'],
                            'fb_att_id' => 0,
                            'fb_att_type' => null,
                        ));

                    } else {

                        //HTML Format, append content to current output message:
                        $output_body_message .= '<div style="margin-top:7px;">' . fn___echo_url_type($parent_en['tr_content'], $parent_en['tr_type_en_id']) . '</div>';

                    }

                } elseif ($parent_en['tr_type_en_id'] == 4257) {

                    //Embed URL
                    //Do we have a Slice command AND is this Embed URL Slice-able?
                    if (in_array('/slice', $msg_references['ref_commands']) && fn___includes_any($parent_en['tr_content'], $sliceable_urls)) {

                        //We've found a slice-able URL:
                        $found_slicable_url = true;

                        if ($fb_messenger_format) {
                            //Show custom Start/End URL:
                            $tr_content = 'https://www.youtube.com/embed/' . fn___echo_youtube_id($parent_en['tr_content']) . '?start=' . $slice_times[0] . '&end=' . $slice_times[1] . '&autoplay=1';
                        } else {
                            //Show HTML Embed Code for slice-able:
                            $tr_content = '<div style="margin-top:7px;">' . fn___echo_url_embed($parent_en['tr_content'], $parent_en['tr_content'], false, $slice_times[0], $slice_times[1]) . '</div>';
                        }

                    } else {

                        if ($fb_messenger_format) {
                            //Show custom Start/End URL:
                            $tr_content = $parent_en['tr_content'];
                        } else {
                            //Show HTML Embed Code:
                            $tr_content = '<div style="margin-top:7px;">' . fn___echo_url_embed($parent_en['tr_content']) . '</div>';
                        }

                    }


                    if ($fb_messenger_format) {

                        //Generic URL:
                        array_push($fb_media_attachments, array(
                            'tr_type_en_id' => 4552, //Text Message Sent
                            'tr_content' => $tr_content,
                            'fb_att_id' => 0,
                            'fb_att_type' => null,
                        ));

                    } else {

                        //HTML Format, append content to current output message:
                        $output_body_message .= $tr_content;

                    }

                } elseif (strlen($parent_en['tr_content']) > 0) {

                    //This is a regular link with some contextual information.
                    //TODO Consider showing this information somehow...

                }

            }


            //Determine if we have text:
            $has_text = !(trim($output_body_message) == '@' . $msg_references['ref_entities'][0]);

            //Adjust
            if ($is_miner && !$fb_messenger_format) {

                /*
                 *
                 * HTML Message format for Miners, which we can
                 * include a link to the Entity for quick access
                 * to more information about that entity:=.
                 *
                 * Note that url_modal() is available in the Footer
                 * and is available on all pages.
                 *
                 * */
                $output_body_message = str_replace('@' . $msg_references['ref_entities'][0], ' <a href="javascript:void(0);" onclick="url_modal(\'/entities/' . $ens[0]['en_id'] . '?skip_header=1\')">' . $ens[0]['en_name'] . '</a>', $output_body_message);

            } else {

                //Just replace with the entity name, which ensure we're always have a text in our message even if $has_text = FALSE
                $output_body_message = str_replace('@' . $msg_references['ref_entities'][0], $ens[0]['en_name'], $output_body_message);

            }
        }


        //Did we meet /slice command requirements (if any) after processing the referenced entity?
        if (in_array('/slice', $msg_references['ref_commands']) && !$found_slicable_url) {
            return array(
                'status' => 0,
                'message' => 'The /slice command requires the message to reference an entity that links to ' . join(' or ', $sliceable_urls),
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
         * The format of this will be array( $tr_en_child_id => $tr_content )
         * to define both message and it's type.
         *
         * See all sent message types here: https://mench.com/entities/4280
         *
         * */
        $output_messages = array();

        if ($fb_messenger_format) {

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
                        'metadata' => 'system_logged', //Prevents duplicate Transaction logs
                    );

                } elseif ($has_text) {

                    //No button, just text:
                    $fb_message = array(
                        'text' => $output_body_message,
                        'metadata' => 'system_logged', //Prevents duplicate Transaction logs
                    );

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


            if (count($quick_replies) > 0) {

                //TODO Validate $quick_replies content?

                //Append quick reply option:
                array_push($output_messages, array(
                    'message_type' => 4552, //Text Message Sent
                    'message_body' => array(
                        'recipient' => array(
                            'id' => $recipient_en['en_psid'],
                        ),
                        'message' => array(
                            'text' => 'Select an option to continue:', //Generic/fixed message
                            'quick_replies' => $quick_replies,
                            'metadata' => 'system_logged', //Prevents duplicate Transaction logs
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
                            'text' => $fb_media_attachment['tr_content'],
                            'metadata' => 'system_logged', //Prevents duplicate Transaction logs
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
                            'metadata' => 'system_logged', //Prevents duplicate Transaction logs
                        );

                    } else {

                        //Attachment that needs to be uploaded via URL which will take a few seconds:
                        $fb_message = array(
                            'attachment' => array(
                                'type' => $fb_media_attachment['fb_att_type'],
                                'payload' => array(
                                    'url' => $fb_media_attachment['tr_content'],
                                    'is_reusable' => true,
                                ),
                            ),
                            'metadata' => 'system_logged', //Prevents duplicate Transaction logs
                        );

                    }

                    //Add to output message:
                    array_push($output_messages, array(
                        'message_type' => $fb_media_attachment['tr_type_en_id'],
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
            'tr_en_parent_id' => (count($msg_references['ref_entities']) > 0 ? $msg_references['ref_entities'][0] : 0),
        );

    }

    function fn___compose_message($in_id, $recipient_en, $actionplan_tr_id = 0)
    {

        /*
         *
         * A wrapper function for fn___compose_validate_message() for the sole purposes
         * of logging any errors that might happen within that function.
         *
         * */

        $msg_validation = $this->Chat_model->fn___compose_validate_message($in_id, $recipient_en, $actionplan_tr_id);

        if(!$msg_validation['status']){

            //Oooopsi, we had an error, log it:
            $this->Database_model->fn___tr_create(array(
                'tr_type_en_id' => 4246, //Platform Error
                'tr_content' => 'fn___compose_validate_message() returned error [' . $msg_validation['message'] . '] for intent #' . $in_id . ' and Action Plan ['.$actionplan_tr_id.']',
                'tr_en_child_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                'tr_in_child_id' => $in_id,
            ));

            return false;

        } else {

            //Success! No need to log this as its already logged using fn___dispatch_message()
            return true;

        }
    }



    function fn___compose_validate_message($in_id, $recipient_en, $actionplan_tr_id)
    {

        /*
         *
         * Construct a series of messages from the Matrix with the following inputs:
         *
         * - $in_id:            The Intent used to construct messages.
         *
         * - $recipient_en:     The recipient who will receive the messages via
         *                      Facebook Messenger. Note that this function does
         *                      not support an HTML format, only Messenger.
         *
         * - $actionplan_tr_id: If set greater than zero, will indicate a specific
         *                      Action Plan belonging to $recipient_en that will be
         *                      used to construct messages.
         *
         * */


        if ($in_id < 1) {

            return array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            );

        } elseif (!isset($recipient_en['en_id'])) {

            return array(
                'status' => 0,
                'message' => 'Missing recipient entity ID',
            );

        }

        //Validate intent:
        $ins = $this->Database_model->fn___in_fetch(array(
            'in_id' => $in_id,
            'in_status >=' => 0, //New+
        ));

        if (count($ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid Intent ID [' . $in_id . ']',
            );
        }


        //This is only true IF $actionplan_tr_id > 0 AND $in_id = Top level action plan intent!
        $is_top_level_actionplan = false;


        //Validate Action Plan if we have one:
        if($actionplan_tr_id > 0){

            $actionplans = $this->Database_model->fn___tr_fetch(array(
                'tr_id' => $actionplan_tr_id,
                'tr_en_parent_id' => $recipient_en['en_id'],
                'tr_type_en_id' => 4235, //Action Plan
                'tr_status >=' => 0, //New+
            ), array('in_child'));

            if(count($actionplans) < 1){

                return array(
                    'status' => 0,
                    'message' => 'Invalid Action Plan ID ['.$actionplan_tr_id.'] for Student [@'.$recipient_en['en_id'].']',
                );

            } elseif (!in_array($actionplans[0]['tr_status'], array(0, 1, 2))) {

                //Action Plan Seems complete, nothing else to do:
                return array(
                    'status' => 0,
                    'message' => 'Action Plan ['.$actionplans[0]['in_outcome'].'] is no longer active',
                );

            }

            //Is this a top-level Action Plan intent?
            $is_top_level_actionplan = ( $actionplans[0]['tr_in_child_id'] == $in_id );

        }




        /*
         *
         * Share intent messages if not the top-level Action Plan Intent.
         * In that case the intent messages have already been distributed
         * and we only need to give Students the next steps.
         *
         * */
        if(!$is_top_level_actionplan){

            //If we have rotating we'd need to pick one and send randomly:
            $messages_rotating = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 2, //Published+
                'tr_type_en_id' => 4234, //Rotating
                'tr_in_child_id' => $in_id,
            ), array(), 0, 0, array('tr_order' => 'ASC'));

            //Do we have any rotating messages?
            if (count($messages_rotating) > 0) {
                //yes, pick 1 random message:
                $random_pick = $messages_rotating[rand(0, (count($messages_rotating) - 1))];

                //Dispatch message:
                $this->Chat_model->fn___dispatch_message(
                    $random_pick['tr_content'],
                    $recipient_en,
                    true,
                    array(),
                    array(
                        'tr_in_parent_id' => $actionplans[0]['in_id'], //Action Plan Intent
                        'tr_in_child_id' => $in_id, //Focus Intent
                        'tr_tr_id' => $random_pick['tr_id'], //This message
                    )
                );
            }

            //These messages all need to be sent first:
            $messages_on_start = $this->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 2, //Published+
                'tr_type_en_id' => 4231, //On-Start Messages
                'tr_in_child_id' => $in_id,
            ), array(), 0, 0, array('tr_order' => 'ASC'));

            //Append only if this is an Action Plan Intent (Since we've already communicated them)
            foreach ($messages_on_start as $message_tr) {
                //Dispatch message:
                $this->Chat_model->fn___dispatch_message(
                    $message_tr['tr_content'],
                    $recipient_en,
                    true,
                    array(),
                    array(
                        'tr_in_parent_id' => $actionplans[0]['in_id'], //Action Plan Intent
                        'tr_in_child_id' => $in_id, //Focus Intent
                        'tr_tr_id' => $message_tr['tr_id'], //This message
                    )
                );
            }

        }




        //If we do not have an Action Plan request, we're done here:
        if ($actionplan_tr_id < 1) {
            return array(
                'status' => 1,
                'message' => 'Success',
            );
        }


        /*
         *
         * Let's append more messages to give Students a better
         * understanding on what to do to move forward.
         *
         * */


        //Check the required notes as we'll use this later:
        $message_in_requirements = $this->Matrix_model->fn___in_req_completion($ins[0]['in_completion_en_id'],$ins[0]['id_id'], $actionplan_tr_id);

        //Do we have a Action Plan, if so, we need to add a next step message:
        if ($message_in_requirements) {

            //Let the user know what they need to do
            //Completing this requirement of this intent is the next step:
            $this->Chat_model->fn___dispatch_message(
                $message_in_requirements,
                $recipient_en,
                true,
                array(),
                array(
                    'tr_in_parent_id' => $actionplans[0]['in_id'], //Action Plan Intent
                    'tr_in_child_id' => $in_id, //Focus Intent
                )
            );

            return array(
                'status' => 1,
                'message' => 'Student must now complete intent requirements',
            );

        }




        /*
         *
         * Still here? It either does not have requirements or
         * the requirements have been completed by the Student
         *
         * Let's attempt to give direction on what's next...
         *
         * */

        //To be populated soon:
        $next_step_message = null;
        $quick_replies = array();


        //Lets fetch incomplete children of $in_id within Action Plan $actionplan_tr_id
        $actionplan_child_ins = $this->Database_model->fn___tr_fetch(array(
            'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
            'tr_type_en_id' => 4559, //Action Plan Intents
            'tr_tr_id' => $actionplan_tr_id,
            'tr_in_parent_id' => $in_id,
        ), array('in_child'));


        if (count($actionplan_child_ins) == 0) {

            //No children! So there is a single path forward, the next intent in line:
            $next_ins = $this->Matrix_model->fn___actionplan_next_in($actionplan_tr_id);

            //Did we find the next intent in line in case we had zero?
            if (count($next_ins) > 0) {

                //Give option to move on:
                $next_step_message .= 'The next step to ' . $ins[0]['in_outcome'] . ' is to ' . $next_ins[0]['in_outcome'] . '.';
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => 'Ok Continue ▶️',
                    'payload' => 'MARKCOMPLETE_' . $next_ins[0]['tr_id'],
                ));

            } else {

                /*
                 *
                 * This likely means that the Action Plan is complete which
                 * will be handled accordingly within fn___actionplan_next_in()
                 *
                 * */

            }

        } elseif (count($actionplan_child_ins) == 1) {

            //We 1 child intents, which means again that we have a single path forward...
            $next_step_message .= 'The next step to ' . $ins[0]['in_outcome'] . ' is to ' . $actionplan_child_ins[0]['in_outcome'] . '.';
            array_push($quick_replies, array(
                'content_type' => 'text',
                'title' => 'Ok Continue ▶️',
                'payload' => 'MARKCOMPLETE_' . $actionplan_child_ins[0]['tr_id'],
            ));

        } else {

            //Re-affirm the outcome of the input Intent before listing children:
            $this->Chat_model->fn___dispatch_message(
                'Let’s ' . $ins[0]['in_outcome'] . '.',
                $recipient_en,
                true,
                array(),
                array(
                    'tr_in_parent_id' => $actionplans[0]['in_id'], //Action Plan Intent
                    'tr_in_child_id' => $in_id, //Focus Intent
                )
            );

            //We have multiple immediate children that need to be marked as complete...
            //Let's see if the intent is ALL or ANY to know how to present these children:
            if (intval($ins[0]['in_is_any'])) {

                //Note that ANY nodes cannot require a written response or a URL
                //User needs to choose one of the following:
                $next_step_message .= 'Choose one of these ' . count($actionplan_child_ins) . ' options to ' . $ins[0]['in_outcome'] . ':';
                foreach ($actionplan_child_ins as $counter => $or_child_in) {
                    if ($counter == 10) {

                        //Log error transaction so we can look into it:
                        $this->Database_model->fn___tr_create(array(
                            'tr_miner_en_id' => 1, //Shervin Enayati - 13 Dec 2018
                            'tr_content' => 'fn___compose_validate_message() encountered intent with too many children to be listed as OR Intent options! Trim and iterate that intent tree.',
                            'tr_type_en_id' => 4246, //Platform Error
                            'tr_tr_id' => $actionplan_tr_id, //The action plan
                            'tr_in_parent_id' => $in_id,
                            'tr_in_child_id' => $or_child_in['in_id'],
                        ));

                        //Quick reply accepts 11 options max:
                        break;

                    }
                    $next_step_message .= "\n\n" . ($counter + 1) . '/ ' . $or_child_in['in_outcome'];
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => '/' . ($counter + 1),
                        'payload' => 'CHOOSEOR_' . $actionplan_tr_id . '_' . $in_id . '_' . $or_child_in['in_id'],
                    ));
                }

            } else {

                //User needs to complete all children, and we'd recommend the first item as their next step:
                $next_step_message .= 'There are ' . count($actionplan_child_ins) . ' steps to ' . $ins[0]['in_outcome'] . ':';

                foreach ($actionplan_child_ins as $counter => $and_child_in) {

                    if ($counter == 0) {

                        array_push($quick_replies, array(
                            'content_type' => 'text',
                            'title' => 'Start Step 1 ▶️',
                            'payload' => 'MARKCOMPLETE_' . $and_child_in['tr_id'],
                        ));

                    }

                    //We know that the $next_step_message length cannot surpass the limit defined by fb_max_message variable!
                    //make sure message is within range:
                    if (strlen($next_step_message) < ($this->config->item('fb_max_message') - 200 /* Cushion for appendix messages */)) {

                        //Add message:
                        $next_step_message .= "\n\n" . 'Step ' . ($counter + 1) . ': ' . $and_child_in['in_outcome'];

                    } else {

                        //We cannot add any more, indicate truncating:
                        $remainder = count($actionplan_child_ins) - $counter;
                        $next_step_message .= "\n\n" . 'And ' . $remainder . ' more step' . fn___echo__s($remainder) . '!';
                        break;

                    }
                }

            }


            if(!$is_top_level_actionplan){

                //Give option to skip:
                $actionplan_parents = $this->Database_model->fn___tr_fetch(array(
                    'tr_tr_id' => $actionplan_tr_id,
                    'tr_type_en_id' => 4559, //Action Plan Intents
                    'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
                    'tr_in_child_id' => $in_id,
                ));

                if (count($actionplan_parents) > 0) {
                    //Give option to skip Action Plan Intent:
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => 'Skip',
                        'payload' => 'SKIP-ACTIONPLAN_1_' . $actionplan_parents[0]['tr_id'].'_0',
                    ));
                }
            }

        }


        /*
         *
         * If we have learn more we'd need to give the
         * option to learn more... We only fetch 1 message
         * since we only need to know if we have any
         * as we're not delivering them now.
         *
         * */
        $messages_learn_more = $this->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 2, //Published+
            'tr_type_en_id' => 4232, //Learn More Messages
            'tr_in_child_id' => $in_id,
        ), array(), 1, 0, array('tr_order' => 'ASC'));

        if (count($messages_learn_more) > 0) {
            //Yes! Give as last option:
            array_push($quick_replies, array(
                'content_type' => 'text',
                'title' => 'Learn More',
                'payload' => 'LEARNMORE_' . $actionplan_tr_id . '_' . $tr['tr_id'] . '_' . $ins[0]['in_id'], //TODO Implement LEARNMORE_
            ));
        }


        //Dispatch instructional message:
        $this->Chat_model->fn___dispatch_message(
            $next_step_message,
            $recipient_en,
            true,
            $quick_replies,
            array(
                'tr_in_parent_id' => $actionplans[0]['in_id'], //Action Plan Intent
                'tr_in_child_id' => $in_id, //Focus Intent
            )
        );

        return array(
            'status' => 1,
            'message' => 'Success',
        );
    }





    function fn___facebook_graph($action, $graph_url, $payload = array())
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
        $fb_settings = $this->config->item('fb_settings');
        $access_token_payload = array(
            'access_token' => $fb_settings['mench_access_token']
        );

        if ($action == 'GET' && count($payload) > 0) {
            //Add $payload to GET variables:
            $access_token_payload = array_merge($payload, $access_token_payload);
            $payload = array();
        }

        $graph_url = 'https://graph.facebook.com/v2.6' . $graph_url;
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

        //Process results and produce tr_metadata
        $result = fn___objectToArray(json_decode(curl_exec($ch)));
        $tr_metadata = array(
            'action' => $action,
            'payload' => $payload,
            'url' => $graph_url,
            'result' => $result,
        );

        //Did we have any issues?
        if (!$result) {

            //Failed to fetch this profile:
            $message_error = 'Chat_model->fn___facebook_graph() failed to ' . $action . ' ' . $graph_url;
            $this->Database_model->fn___tr_create(array(
                'tr_content' => $message_error,
                'tr_type_en_id' => 4246, //Platform Error
                'tr_metadata' => $tr_metadata,
            ));

            //There was an issue accessing this on FB
            return array(
                'status' => 0,
                'message' => $message_error,
                'tr_metadata' => $tr_metadata,
            );

        } else {

            //All seems good, return:
            return array(
                'status' => 1,
                'message' => 'Success',
                'tr_metadata' => $tr_metadata,
            );

        }
    }


    function fn___digest_received_quick_reply($en, $quick_reply_payload)
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
            return false;

        } elseif (substr_count($quick_reply_payload, 'UNSUBSCRIBE_') == 1) {

            $action_unsubscribe = fn___one_two_explode('UNSUBSCRIBE_', '', $quick_reply_payload);

            if ($action_unsubscribe == 'CANCEL') {

                //Student seems to have changed their mind, confirm with them:
                $this->Chat_model->fn___dispatch_message(
                    'Awesome, I am excited to continue helping you to ' . $this->config->item('in_strategy_name') . '.',
                    $en,
                    true
                );

                //Inform Student on how to can command Mench:
                $this->Chat_model->fn___compose_message(8332, $en);

            } elseif ($action_unsubscribe == 'ALL') {

                //Student wants to completely unsubscribe from Mench...

                //Remove all Action Plans:
                $actionplans = $this->Database_model->fn___tr_fetch(array(
                    'tr_type_en_id' => 4235, //Action Plans
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                    'tr_status IN (0,1,2)' => null, //Actively working on (Status 2 is syncing updates, and they want out)
                ));
                foreach ($actionplans as $tr) {
                    $this->Database_model->fn___tr_update($tr['tr_id'], array(
                        'tr_status' => -1, //Removed
                    ), $en['en_id']); //Give credit to miner
                }

                //Update Student communication level to Unsubscribe:
                $this->Matrix_model->fn___en_radio_set(4454, 4455, $en['en_id'], $en['en_id']);

                //Let them know about these changes:
                $this->Chat_model->fn___dispatch_message(
                    'Confirmed, I removed your ' . count($actionplans) . ' Action Plan' . fn___echo__s(count($actionplans)) . '. This is the final message you will receive from me unless you message me first. Take care of your self and I hope to talk to you soon 😘',
                    $en,
                    true
                );

            } elseif (is_numeric($action_unsubscribe)) {

                //User wants to Remove a specific Action Plan, validate it:
                $actionplans = $this->Database_model->fn___tr_fetch(array(
                    'tr_type_en_id' => 4235, //Action Plan
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                    'tr_in_child_id' => intval($action_unsubscribe),
                ), array('en_child'));

                //All good?
                if (count($actionplans) > 0) {

                    //Update status for this single Action Plan:
                    $this->Database_model->fn___tr_update($actionplans[0]['tr_id'], array(
                        'tr_status' => -1, //Removed
                    ), $en['en_id']); //Give credit to miner

                    //Show success message to user:
                    $this->Chat_model->fn___dispatch_message(
                        'I have successfully removed the intention to ' . $actionplans[0]['in_outcome'] . ' from your Action Plan. Say "Unsubscribe" again if you wish to stop all future communications.',
                        $en,
                        true
                    );

                    //Inform Student on how to can command Mench:
                    $this->Chat_model->fn___compose_message(8332, $en);

                } else {

                    //Oooops, this should not happen
                    //let them know we had error:
                    $this->Chat_model->fn___dispatch_message(
                        'I was unable to process your request as I could not find your Action Plan. Please try again.',
                        $en,
                        true
                    );

                    //Log error transaction:
                    $this->Database_model->fn___tr_create(array(
                        'tr_miner_en_id' => $en['en_id'],
                        'tr_en_parent_id' => $en['en_id'],
                        'tr_content' => 'Failed to skip an intent from the master Action Plan',
                        'tr_type_en_id' => 4246, //Platform Error
                        'tr_in_child_id' => intval($action_unsubscribe),
                    ));

                }

            }

        } elseif (substr_count($quick_reply_payload, 'RESUBSCRIBE_') == 1) {

            if ($quick_reply_payload == 'RESUBSCRIBE_YES') {

                //Update User communication level to Receive Silent Push Notifications:
                $this->Matrix_model->fn___en_radio_set(4454, 4457, $en['en_id'], $en['en_id']);

                //Inform them:
                $this->Chat_model->fn___dispatch_message(
                    'Sweet, you account is now activated but you are not subscribed to any intents yet.',
                    $en,
                    true
                );

                //Inform Student on how to can command Mench:
                $this->Chat_model->fn___compose_message(8332, $en);

            } elseif ($quick_reply_payload == 'RESUBSCRIBE_NO') {

                $this->Chat_model->fn___dispatch_message(
                    'Ok, I will keep you unsubscribed 🙏',
                    $en,
                    true
                );

            }

        } elseif ($quick_reply_payload == 'SUBSCRIBE-REJECT') {

            //They rejected the offer... Acknowledge and give response:
            $this->Chat_model->fn___dispatch_message(
                'Ok, so how can I help you ' . $this->config->item('in_strategy_name') . '?',
                $en,
                true
            );

            //Inform Student on how to can command Mench:
            $this->Chat_model->fn___compose_message(8332, $en);

        } elseif (is_numeric($quick_reply_payload)) {

            //This is the Intent ID that they are interested to Subscribe to.

            $in_id = intval($quick_reply_payload);

            //Validate Intent:
            $ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => $in_id,
            ));

            //Any issues?
            if (count($ins) < 1) {

                //Ooops we could not find the intention:
                $this->Chat_model->fn___dispatch_message(
                    'I was unable to locate intent #' . $in_id,
                    $en,
                    true
                );

            } elseif ($ins[0]['in_status'] < 2) {

                //Ooopsi Intention is not published:
                $this->Chat_model->fn___dispatch_message(
                    'I cannot subscribe you to ' . $ins[0]['in_outcome'] . ' as its not published yet.',
                    $en,
                    true
                );

            } else {

                //Confirm if they are interested to subscribe to this intention:
                $this->Chat_model->fn___dispatch_message(
                    'Hello hello 👋 are you interested to ' . $ins[0]['in_outcome'] . '?',
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Yes, Learn More',
                            'payload' => 'CONFIRM_' . $ins[0]['in_id'],
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'No',
                            'payload' => 'SUBSCRIBE-REJECT',
                        ),
                    ),
                    array(
                        'tr_in_child_id' => $ins[0]['in_id'],
                    )
                );

            }

        } elseif (substr_count($quick_reply_payload, 'CONFIRM_') == 1) {

            //Student has confirmed their desire to subscribe to an intention:
            $in_id = intval(fn___one_two_explode('CONFIRM_', '', $quick_reply_payload));

            //Initiating an intent Action Plan:
            $ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => $in_id,
                'status >=' => 2, //Published+
            ));

            if (count($ins) == 1) {

                //Intent seems good...
                //See if this intent belong to ANY of this Student's Action Plans or Action Plan Intents:
                $actionplans = $this->Database_model->fn___tr_fetch(array(
                    'tr_type_en_id IN (4235,4559)' => null, //Action Plans or Action Plan Intents
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                    'tr_in_child_id' => $ins[0]['in_id'],
                ));

                if (count($actionplans) > 0) {

                    //Let Student know that they have already subscribed to this intention:
                    $this->Chat_model->fn___dispatch_message(
                        'The intention to ' . $ins[0]['in_outcome'] . ' has already been added to your Action Plan. We have been working on it together since ' . fn___echo_time_date($actionplans[0]['tr_timestamp'], true) . '. /link:See in 🚩Action Plan:https://mench.com/master/actionplan/' . ( $actionplans[0]['tr_type_en_id']==4235 ? $actionplans[0]['tr_id'] : $actionplans[0]['tr_tr_id'] ) . '/' . $actionplans[0]['tr_in_child_id'],
                        $en,
                        true,
                        array(),
                        array(
                            'tr_in_child_id' => $ins[0]['in_id'],
                        )
                    );

                } else {

                    //Do final confirmation by giving Student more context on this intention before adding to their Action Plan...

                    //Send all on-start messages for this intention so they can review it:
                    $messages_on_start = $this->Database_model->fn___tr_fetch(array(
                        'tr_status >=' => 2, //Published+
                        'tr_type_en_id' => 4231, //On-Start Messages
                        'tr_in_child_id' => $ins[0]['in_id'],
                    ), array(), 0, 0, array('tr_order' => 'ASC'));

                    foreach ($messages_on_start as $tr) {
                        $this->Chat_model->fn___dispatch_message(
                            $tr['tr_content'],
                            $en,
                            true,
                            array(),
                            array(
                                'tr_tr_id' => $tr['tr_id'],
                                'tr_in_child_id' => $ins[0]['in_id'],
                            )
                        );
                    }

                    //Send message for final confirmation with the overview of how long/difficult it would be to accomplish this intention:
                    $this->Chat_model->fn___dispatch_message(
                        'Here is an overview:' . "\n\n" .
                        fn___echo_in_overview($ins[0], true) .
                        fn___echo_in_referenced_content($ins[0], true) .
                        fn___echo_in_experts($ins[0], true) .
                        fn___echo_in_time_estimate($ins[0], true) .
                        fn___echo_in_cost_range($ins[0], true) .
                        "\n" . 'Are you ready to ' . $ins[0]['in_outcome'] . '?',
                        $en,
                        true,
                        array(
                            array(
                                'content_type' => 'text',
                                'title' => 'Yes, Subscribe',
                                'payload' => 'SUBSCRIBE-CONFIRM_' . $ins[0]['in_id'],
                            ),
                            array(
                                'content_type' => 'text',
                                'title' => 'No',
                                'payload' => 'SUBSCRIBE-REJECT',
                            ),
                        ),
                        array(
                            'tr_in_child_id' => $ins[0]['in_id'],
                        )
                    );

                }
            }

        } elseif (substr_count($quick_reply_payload, 'SUBSCRIBE-CONFIRM_') == 1) {

            //Student has requested to add this intention to their Action Plan:
            $in_id = intval(fn___one_two_explode('SUBSCRIBE-CONFIRM_', '', $quick_reply_payload));

            //Validate Intent ID:
            $ins = $this->Database_model->fn___in_fetch(array(
                'in_id' => $in_id,
                'in_status >=' => 2, //Published+
            ));

            if (count($ins) == 1) {

                //Add intent to Student's Action Plan:
                $actionplan = $this->Database_model->fn___tr_create(array(

                    'tr_type_en_id' => 4235, //Action Plan
                    'tr_status' => 0, //New
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student

                    'tr_in_child_id' => $ins[0]['in_id'], //The Intent they are adding

                    'tr_order' => 1 + $this->Database_model->fn___tr_max_order(array( //Place this intent at the end of all intents the Student is working on...
                        'tr_type_en_id' => 4235, //Action Plan
                        'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
                        'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                    )),
                ));


                //Was this added successfully?
                if (isset($actionplan['tr_id']) && $actionplan['tr_id'] > 0) {

                    //Also add all relevant child intents:
                    $this->Matrix_model->fn___in_recursive_fetch($ins[0]['in_id'], true, false, $actionplan);

                    //Confirm with them that we're now ready:
                    $this->Chat_model->fn___dispatch_message(
                        'Success! I added the intention to ' . $ins[0]['in_outcome'] . ' to your Action Plan 🙌 /link:Open 🚩Action Plan:https://mench.com/master/actionplan/' . $actionplan['tr_id'] . '/' . $ins[0]['in_id'],
                        $en,
                        true,
                        array(),
                        array(
                            'tr_in_child_id' => $ins[0]['in_id'],
                            'tr_tr_id' => $actionplan['tr_id'],
                        )
                    );

                    //Initiate first message for action plan tree:
                    $this->Chat_model->fn___compose_message($ins[0]['in_id'], $en, $actionplan['tr_id']);

                } else {

                    //Ooops we could not find the intention:
                    $this->Chat_model->fn___dispatch_message(
                        'I was unable to add the intention to '.$ins[0]['in_outcome'].' to your Action Plan',
                        $en,
                        true
                    );

                }
            }

        } elseif (substr_count($quick_reply_payload, 'SKIP-ACTIONPLAN_') == 1) {

            //Extract variables from REF:
            $input_parts = explode('_', fn___one_two_explode('SKIP-ACTIONPLAN_', '', $quick_reply_payload));
            $tr_status = intval($input_parts[0]); //It would be $tr_status=1 initial (working on) and then would change to either -1 IF skip was cancelled or 2 IF skip was confirmed.
            $tr_id = intval($input_parts[1]); //Action Plan Intent Transaction ID
            $skip_tr_id = intval($input_parts[2]); //Would initially be zero and would then be set to a Transaction ID when Student confirms/cancels skipping


            if($tr_id > 0){
                //Fetch Action Plan Intent:
                $actionplans = $this->Database_model->fn___tr_fetch(array(
                    'tr_id' => $tr_id,
                    'tr_type_en_id' => 4559, //Action Plan Intents
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                ), array('in_child'));
            }

            //Validate inputs:
            if ($tr_id < 1 || !in_array($tr_status, array(-1, 1, 2)) || count($actionplans) < 1) {

                //Log error:
                $this->Database_model->fn___tr_create(array(
                    'tr_content' => 'fn___digest_received_quick_reply() failed to fetch proper data for a skip request with reference value [' . $quick_reply_payload . ']',
                    'tr_type_en_id' => 4246, //Platform Error
                    'tr_tr_id' => $tr_id,
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                ));

                //Inform Student:
                $this->Chat_model->fn___dispatch_message(
                    'I was unable to process your skip request',
                    $en,
                    true
                );

                return false;

            }

            //Set Action Plan ID:
            $actionplan_tr_id = $actionplans[0]['tr_tr_id'];


            //Was this initiating?
            if ($tr_status == 1) {

                //User has indicated they want to skip this tree and move on to the next item in-line:
                //Lets confirm the implications of this SKIP to ensure they are aware:

                //See how many children would be skipped if they decide to do so:
                $would_be_skipped = $this->Matrix_model->k_skip_recursive_down($tr_id, false);
                $would_be_skipped_count = count($would_be_skipped);

                if ($would_be_skipped_count == 0) {

                    //Nothing found to skip! This should not happen, log error:
                    $this->Database_model->fn___tr_create(array(
                        'tr_content' => 'fn___digest_received_quick_reply() did not find anything to skip for [' . $quick_reply_payload . ']',
                        'tr_type_en_id' => 4246, //Platform Error
                        'tr_tr_id' => $tr_id,
                        'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                    ));

                    //Inform user:
                    $this->Chat_model->fn___dispatch_message(
                        'I did not find anything to skip!',
                        $en,
                        true,
                        array(),
                        array(
                            'tr_tr_id' => $tr_id,
                        )
                    );

                    return false;

                }


                //Log transaction for skip request:
                $new_tr = $this->Database_model->fn___tr_create(array(
                    'tr_miner_en_id' => $en['en_id'],
                    'tr_en_parent_id' => $en['en_id'],
                    'tr_type_en_id' => 4284, //Skip Intent
                    'tr_tr_id' => $tr_id, //The Transaction Reference that points to this intent in the Students Action Plan
                    'tr_status' => 1, //Working on... not yet decided to skip or not as they need to see the consequences before making an informed decision. Will be updated to -1 or 2 based on their response...
                    'tr_metadata' => array(
                        'would_be_skipped' => $would_be_skipped,
                        'ref' => $quick_reply_payload,
                    ),
                ));


                //Construct the message to give more details on skipping:
                $message = 'You are about to skip these ' . $would_be_skipped_count . ' key idea' . fn___echo__s($would_be_skipped_count) . ':';
                foreach ($would_be_skipped as $counter => $k_c) {
                    if (strlen($message) < ($this->config->item('fb_max_message') - 200)) {
                        //We have enough room to add more:
                        $message .= "\n\n" . ($counter + 1) . '/ ' . $k_c['in_outcome'];
                    } else {
                        //We cannot add any more, indicate truncating:
                        $remainder = $would_be_skipped_count - $counter;
                        $message .= "\n\n" . 'And ' . $remainder . ' more key idea' . fn___echo__s($remainder) . '!';
                        break;
                    }
                }

                //Recommend against it:
                $message .= "\n\n" . 'I would not recommend skipping unless you feel comfortable learning these key ideas on your own.';

                //Send them the message:
                $this->Chat_model->fn___dispatch_message(
                    $message,
                    $en,
                    true,
                    array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Skip ' . $would_be_skipped_count . ' key idea' . fn___echo__s($would_be_skipped_count) . ' 🚫',
                            'payload' => 'SKIP-ACTIONPLAN_2_'.$tr_id.'_' . $new_tr['tr_id'], //Confirm and skip
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'Continue ▶️',
                            'payload' => 'SKIP-ACTIONPLAN_-1_'.$tr_id.'_' . $new_tr['tr_id'], //Cancel skipping
                        ),
                    ),
                    array(
                        'tr_tr_id' => $tr_id,
                    )
                );

            } else {

                //They have either confirmed or cancelled the skip:
                if ($tr_status == -1) {

                    //user changed their mind and does not want to skip anymore
                    $message = 'I am happy you changed your mind! Let\'s continue...';

                } elseif ($tr_status == 2) {

                    //Actually skip and see if we've finished this Action Plan:
                    $this->Matrix_model->k_skip_recursive_down($tr_id);

                    //Confirm the skip:
                    $message = 'Confirmed, I marked this section as skipped. You can always re-visit these key ideas in your Action Plan and complete them at any time. /link:See in 🚩Action Plan:https://mench.com/master/actionplan/' . $actionplans[0]['tr_tr_id'] . '/' . $actionplans[0]['tr_in_child_id'];

                }

                //Inform Student of Skip status:
                $this->Chat_model->fn___dispatch_message(
                    $message,
                    $en,
                    true,
                    array(),
                    array(
                        'tr_tr_id' => $tr_id,
                    )
                );

                //Update Skip request status accordingly:
                $this->Database_model->fn___tr_update($skip_tr_id, array(
                    'tr_status' => $tr_status,
                ), $en['en_id']);


                //Find the next item to navigate them to:
                $next_ins = $this->Matrix_model->fn___actionplan_next_in($actionplan_tr_id);
                if ($next_ins) {
                    //Now move on to communicate the next step:
                    $this->Chat_model->fn___compose_message($next_ins[0]['in_id'], $en, $actionplan_tr_id);
                }

            }

        } elseif (substr_count($quick_reply_payload, 'MARKCOMPLETE_') == 1) {

            //Student consumed AND tree content, and is ready to move on to next intent...
            $tr_id = intval(fn___one_two_explode('MARKCOMPLETE_', '', $quick_reply_payload));

            if ($tr_id > 0) {
                //Fetch Action Plan Intent with its Student:
                $actionplans = $this->Database_model->fn___tr_fetch(array(
                    'tr_id' => $tr_id,
                    'tr_type_en_id' => 4559, //Action Plan Intents
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                ), array('in_child', 'en_parent'));
            }


            if($tr_id < 0 || count($actionplans) < 1){

                //Invalid Action Plan Intent ID!
                $this->Database_model->fn___tr_create(array(
                    'tr_content' => 'fn___digest_received_quick_reply() failed to fetch proper data for intent completion request with reference value [' . $quick_reply_payload . ']',
                    'tr_type_en_id' => 4246, //Platform Error
                    'tr_tr_id' => $tr_id,
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                ));

                //Inform Student:
                $this->Chat_model->fn___dispatch_message(
                    'I was unable to process your completion request',
                    $en,
                    true
                );

                return false;
            }

            //Set Action Plan ID:
            $actionplan_tr_id = $actionplans[0]['tr_tr_id'];

            //Mark this intent as complete:
            $this->Matrix_model->in_actionplan_complete_up($actionplans[0], $actionplans[0]);

            //Go to next item:
            $next_ins = $this->Matrix_model->fn___actionplan_next_in($actionplan_tr_id);

            if ($next_ins) {
                //Communicate next step:
                $this->Chat_model->fn___compose_message($next_ins[0]['in_id'], $en, $actionplan_tr_id);
            }

        } elseif (substr_count($quick_reply_payload, 'CHOOSEOR_') == 1) {

            //Student has responded to a multiple-choice OR tree
            $input_parts = explode('_', fn___one_two_explode('CHOOSEOR_', '', $quick_reply_payload));
            $actionplan_tr_id = intval($input_parts[0]);
            $tr_in_parent_id = intval($input_parts[1]);
            $in_id = intval($input_parts[2]);

            if ($actionplan_tr_id > 0 && $tr_in_parent_id > 0 && $in_id > 0 && $this->Matrix_model->fn___actionplan_choose_or($actionplan_tr_id, $tr_in_parent_id, $in_id)) {

                //Confirm answer received by acknowledging progress with Student:
                $this->Chat_model->fn___compose_message(8333, $en);

                //Find the next item to navigate them to:
                $next_ins = $this->Matrix_model->fn___actionplan_next_in($actionplan_tr_id);

                if ($next_ins) {
                    //Communicate next step:
                    $this->Chat_model->fn___compose_message($next_ins[0]['in_id'], $en, $actionplan_tr_id);
                }

            } else {

                //Log Unknown error:
                $this->Database_model->fn___tr_create(array(
                    'tr_content' => 'fn___digest_received_quick_reply() failed to save OR answer with reference value [' . $quick_reply_payload . ']',
                    'tr_type_en_id' => 4246, //Platform Error
                    'tr_metadata' => $en,
                    'tr_tr_id' => $actionplan_tr_id,
                    'tr_in_child_id' => $in_id,
                ));

                //Inform Student:
                $this->Chat_model->fn___dispatch_message(
                    'I was unable to save your answer',
                    $en,
                    true
                );

                return false;
            }
        }
    }

    function fn___digest_received_message($en, $fb_received_message)
    {

        /*
         *
         * Will process the chat message only in the absence of a chat metadata
         * otherwise the fn___digest_received_quick_reply() will process the message since we
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


        //First check if this Student is unsubscribed:
        if (count($this->Database_model->fn___tr_fetch(array(
                'tr_en_child_id' => $en['en_id'],
                'tr_en_parent_id' => 4455, //Unsubscribed
                'tr_status >=' => 0,
            ))) > 0) {

            //Yes, this Student is Unsubscribed! Give them an option to re-activate their Mench account:
            $this->Chat_model->fn___dispatch_message(
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


        if (in_array($fb_received_message, array('yes', 'yeah', 'ya', 'ok', 'continue', 'ok continue', 'ok continue ▶️', '▶️', 'ok continue', 'go', 'yass', 'yas', 'yea', 'yup', 'next', 'yes, learn more'))) {

            //TODO Implement...

        } elseif (in_array($fb_received_message, array('skip', 'skip it'))) {

            //TODO Implement...

        } elseif (in_array($fb_received_message, array('help', 'support', 'f1', 'sos'))) {

            //Ask the user if they like to be connected to a human
            //IF yes, create a ATTENTION NEEDED transaction that would notify admin so admin can start a manual conversation
            //TODO Implement...

        } elseif (in_array($fb_received_message, array('learn', 'learn more', 'explain', 'explain more'))) {

            //TODO Implement...

        } elseif (in_array($fb_received_message, array('no', 'nope', 'nah', 'cancel', 'stop'))) {

            //Rejecting an offer...
            //TODO Implement...

        } elseif (substr($fb_received_message, 0, 1) == '/' || is_int($fb_received_message)) {

            //Likely an OR response with a specific number in mind...
            //TODO Implement...

        } elseif (fn___includes_any($fb_received_message, array('unsubscribe', 'stop', 'cancel'))) {

            //They seem to want to unsubscribe
            //List their Action Plans:
            $actionplans = $this->Database_model->fn___tr_fetch(array(
                'tr_type_en_id' => 4235, //Intents added to the action plan
                'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                'tr_status IN (0,1,2)' => null, //Actively working on
            ), array('in_child'), 10 /* Max quick replies allowed */, 0, array('tr_order' => 'ASC'));


            //Do they have anything in their Action Plan?
            if (count($actionplans) > 0) {

                //Give them options to remove specific Action Plans:
                $quick_replies = array();
                $message = 'Choose one of the following options:';
                $increment = 1;

                foreach ($actionplans as $counter => $in) {
                    //Construct unsubscribe confirmation body:
                    $message .= "\n\n" . '/' . ($counter + $increment) . ' Remove ' . $in['in_outcome'];
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => '/' . ($counter + $increment),
                        'payload' => 'UNSUBSCRIBE_' . $in['in_id'],
                    ));
                }

                if (count($actionplans) >= 2) {
                    //Give option to skip all and unsubscribe:
                    $increment++;
                    $message .= "\n\n" . '/' . ($counter + $increment) . ' Remove all intentions and unsubscribe';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => '/' . ($counter + $increment),
                        'payload' => 'UNSUBSCRIBE_ALL',
                    ));
                }

                //Alwyas give none option:
                $increment++;
                $message .= "\n\n" . '/' . ($counter + $increment) . ' Cancel & keep all intentions';
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => '/' . ($counter + $increment),
                    'payload' => 'UNSUBSCRIBE_CANCEL',
                ));

                //Send out message and let them confirm:
                $this->Chat_model->fn___dispatch_message(
                    $message,
                    $en,
                    true,
                    $quick_replies
                );

            } else {

                //They do not have anything in their Action Plan, so we assume they just want to Unsubscribe and stop all future communications:
                $this->Chat_model->fn___dispatch_message(
                    'Got it, just to confirm, you want to unsubscribe and stop all future communications with me?',
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

        } elseif (fn___includes_any($fb_received_message, array('lets ', 'let’s ', 'let\'s ', '?'))) {

            //This looks like they are giving us a command:
            $master_command = null;
            $result_limit = 6;

            if ($fb_received_message) {
                $fb_received_message = trim(strtolower($fb_received_message));
                if (substr_count($fb_received_message, 'lets ') > 0) {
                    $master_command = fn___one_two_explode('lets ', '', $fb_received_message);
                } elseif (substr_count($fb_received_message, 'let’s ') > 0) {
                    $master_command = fn___one_two_explode('let’s ', '', $fb_received_message);
                } elseif (substr_count($fb_received_message, 'let\'s ') > 0) {
                    $master_command = fn___one_two_explode('let\'s ', '', $fb_received_message);
                } elseif (substr_count($fb_received_message, '?') > 0) {
                    //Them seem to be asking a question, lets treat this as a command:
                    $master_command = str_replace('?', '', $fb_received_message);
                }
            }

            //Do a search to see what we find...
            if ($this->config->item('enable_algolia')) {

                $search_index = fn___load_php_algolia('alg_intents');
                $res = $search_index->search($master_command, [
                    'hitsPerPage' => $result_limit,
                    'filters' => 'in_status>=2', //Search published intents
                ]);
                $search_results = $res['hits'];

            } else {

                //Do a regular internal search:
                $search_results = $this->Database_model->fn___in_fetch(array(
                    'in_status >=' => 2, //Search published intents
                    'in_outcome LIKE \'%' . $master_command . '%\'' => null, //Basic string search
                ), array(), $result_limit);

            }


            //Log intent search:
            $this->Database_model->fn___tr_create(array(
                'tr_content' => 'Found ' . count($search_results) . ' intent' . fn___echo__s(count($search_results)) . ' matching "' . $master_command . '"',
                'tr_metadata' => array(
                    'input_data' => $master_command,
                    'output' => $search_results,
                ),
                'tr_miner_en_id' => $en['en_id'], //user who searched
                'tr_type_en_id' => 4275, //Search for New Intent Action Plan
            ));


            if (count($search_results) > 0) {

                //Show options for the Student to add to their Action Plan:
                $quick_replies = array();
                $message = 'I found these intents:';

                foreach ($search_results as $count => $in) {
                    $message .= "\n\n" . ($count + 1) . '/ ' . $in['in_outcome'] . ' in ' . strip_tags(fn___echo_time_range($in));
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($count + 1) . '/',
                        'payload' => 'CONFIRM_' . $in['in_id'],
                    ));
                }

                //Give them a "None of the above" option:
                $message .= "\n\n" . ($count + 2) . '/ None of the above';
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => ($count + 2) . '/',
                    'payload' => 'SUBSCRIBE-REJECT',
                ));

                //return what we found to the master to decide:
                $this->Chat_model->fn___dispatch_message(
                    $message,
                    $en,
                    true,
                    $quick_replies
                );

            } else {

                //Respond to user:
                $this->Chat_model->fn___dispatch_message(
                    'I did not find any intentions to "' . $master_command . '", but I will let you know as soon as I am trained on this. Is there anything else I can help you with right now?',
                    $en,
                    true
                );

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
            $admin_conversations = $this->Database_model->fn___tr_fetch(array(
                'tr_timestamp >=' => date("Y-m-d H:i:s", (time() - (1800))), //Messages sent from us less than 30 minutes ago
                'tr_type_en_id' => 4280, //Messages sent from us
                'tr_miner_en_id' => 4148, //We log Facebook Inbox UI messages sent with this entity ID
            ), array(), 1);
            if (count($admin_conversations) > 0) {
                //Yes, this user is talking to an admin so do not interrupt their conversation:
                return false;
            }


            //Inform Student of Mench's one-way communication limitation & that Mench did not understand their message:
            $this->Chat_model->fn___compose_message(8334, $en);


            //Log transaction:
            $this->Database_model->fn___tr_create(array(
                'tr_miner_en_id' => $en['en_id'], //User who initiated this message
                'tr_content' => $fb_received_message,
                'tr_type_en_id' => 4287, //Log Unrecognizable Message Received
            ));


            //Do they have an Action Plan that they are working on?
            //If so, we can recommend the next step within that Action Plan...
            $actionplans = $this->Database_model->fn___tr_fetch(array(
                'tr_type_en_id' => 4235, //Action Plan
                'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
            ), array('in_child'), 1, 0, array('tr_order' => 'ASC'));

            if (count($actionplans) > 0) {

                //They have an Action Plan that they are working on, Remind user of their next step:
                $next_ins = $this->Matrix_model->fn___actionplan_next_in($actionplans[0]['tr_id']);

                //Do we have a next step? (We should if Action Plan status is incomplete)
                if ($next_ins) {
                    $this->Chat_model->fn___compose_message($next_ins[0]['in_id'], $en, $actionplans[0]['tr_id']);
                }

            } else {

                /*
                 *
                 * Student has no action plan...
                 *
                 * Suggest to subscribe to our default intent
                 * only IF they have not done so already:
                 *
                 * */

                $default_actionplans = $this->Database_model->fn___tr_fetch(array(
                    'tr_type_en_id IN (4235,4559)' => null, //Action Plan or Action Plan Intents
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Student
                    'tr_in_child_id' => $this->config->item('in_tactic_id'),
                ));
                if (count($default_actionplans) == 0) {

                    //They have never taken the default intent, recommend it to them:
                    $this->Chat_model->fn___digest_received_quick_reply($en, $this->config->item('in_tactic_id'));

                }

            }
        }
    }



    function fn___dispatch_email($to_array, $to_en_ids, $subject, $html_message)
    {

        /*
         *
         * Send an email via our Amazon server
         *
         * */

        if (fn___is_dev()) {
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
            $this->Database_model->fn___tr_create(array(
                'tr_type_en_id' => 4276, //Email Message Sent
                'tr_en_child_id' => $to_en_id, //Email Recipient
                'tr_content' => 'Email Sent: ' . $subject,
                'tr_metadata' => array(
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