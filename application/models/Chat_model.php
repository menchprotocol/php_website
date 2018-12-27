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


    function echo_message($input_message, $recipient_en = array(), $fb_messenger_format = false, $quick_replies = array(), $tr_append = array())
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
         *                          Masters an easy way to tap and select their next step.
         *
         *
         * - $tr_append:            Since this function logs a "message sent" engagement for
         *                          every message it processes, the $tr_append will append
         *                          additional data to capture more context for this message.
         *                          Supported fields only include:
         *
         *                          - $tr_append['tr_in_parent_id']
         *                          - $tr_append['tr_in_child_id']
         *                          - $tr_append['tr_tr_parent_id']
         *
         *                          Following fields are not allowed, because:
         *
         *                          - $tr_append['tr_metadata']: Reserved for message body IF $fb_messenger_format = TRUE
         *                          - $tr_append['tr_timestamp']: Auto generated to current timestamp
         *                          - $tr_append['tr_status']: Will always equal 2 as a completed message
         *                          - $tr_append['tr_en_type_id']: Auto calculated based on message content (or error)
         *                          - $tr_append['tr_en_credit_id']: Mench will always get credit, so this is set to zero
         *                          - $tr_append['tr_en_parent_id']: This is auto set with an entity reference within $input_message
         *                          - $tr_append['tr_en_child_id']: This will be equal to $recipient_en['en_id']
         *
         * */


        //Validate message:
        $msg_validation = $this->Chat_model->validate_message($input_message, $recipient_en, $fb_messenger_format, $quick_replies);


        //Prepare data to be appended to success/fail transaction:
        $allowed_tr_append = array('tr_in_parent_id', 'tr_in_child_id', 'tr_tr_parent_id');
        $filtered_tr_append = array();
        foreach ($tr_append as $key => $value) {
            if (in_array($key, $allowed_tr_append)) {
                $filtered_tr_append[$key] = $value;
            }
        }


        //Did we have ane error in message validation?
        if (!$msg_validation['status']) {

            //Log Error Transaction:
            $this->Database_model->tr_create(array_merge(array(
                'tr_en_type_id' => 4246, //Platform Error
                'tr_content' => 'validate_message() returned error [' . $msg_validation['message'] . '] for input message [' . $input_message . ']',
                'tr_en_child_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
            ), $filtered_tr_append));

            return false;
        }

        //Message validation passed...
        //Log message sent transaction:
        foreach ($msg_validation['output_messages'] as $output_message) {

            //Dispatch message based on format:
            if ($fb_messenger_format) {

                //Attempt to dispatch message via Facebook Graph API:
                $fb_graph_process = $this->Chat_model->fn___facebook_graph('POST', '/me/messages', $output_message['message_body']);

                //Did we have an Error from the Facebook API side?
                if (!$fb_graph_process['status']) {

                    //Ooopsi, we did! Log error Transcation:
                    $this->Database_model->tr_create(array_merge(array(
                        'tr_en_type_id' => 4246, //Platform Error
                        'tr_content' => 'echo_message() failed to send message via Facebook Graph API. See Metadata log for more details.',
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

                //HTML Format, simply echo message on-screen to finalize delivery:
                echo $output_message['message_body'];

                //NULL placeholder for the Facebook Graph Call since this is an HTML delivery:
                $fb_graph_process = null;

            }

            //Log successful Transaction for message delivery:
            $this->Database_model->tr_create(array_merge(array(
                'tr_content' => $msg_validation['input_message'],
                'tr_en_type_id' => $output_message['message_type'],
                'tr_en_child_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                'tr_en_parent_id' => $msg_validation['tr_en_parent_id'], //Might be set if message had a referenced entity
                'tr_metadata' => array(
                    'input_message' => $input_message,
                    'output_message' => $output_message['message_body'],
                    'fb_graph_process' => $fb_graph_process,
                ),
            ), $filtered_tr_append));

        }

        //If we're here it's all good:
        return true;

    }


    function validate_message($input_message, $recipient_en = array(), $fb_messenger_format = false, $quick_replies = array())
    {

        /*
         *
         * This function is used to validate intent messages.
         *
         * See echo_message() for more information on input variables.
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
            $ens = $this->Database_model->en_fetch(array(
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
                //This Master does not have their Messenger connected yet:
                return array(
                    'status' => 0,
                    'message' => 'Master @' . $recipient_en['en_id'] . ' does not have Messenger connected yet',
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
            $trs_comm_level = $this->Database_model->tr_fetch(array(
                'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4454')) . ')' => null,
                'tr_en_child_id' => $recipient_en['en_id'],
                'tr_status >=' => 2,
            ));

            //Start validating communication settings we fetched to ensure everything is A-OK:
            if (count($trs_comm_level) < 1) {

                return array(
                    'status' => 0,
                    'message' => 'Master is missing their Notification Level parent entity relation',
                );

            } elseif (count($trs_comm_level) > 1) {

                //This should find exactly one result as it belongs to Master Radio Entity @4461
                return array(
                    'status' => 0,
                    'message' => 'Master has more than 1 Notification Level parent entity relation',
                );

            } elseif ($trs_comm_level[0]['tr_en_parent_id'] == 4455) {

                return array(
                    'status' => 0,
                    'message' => 'Master is unsubscribed',
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
            $created_url = $this->Matrix_model->fn___create_en_from_url($msg_references['ref_urls'][0]);

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
                //This is a guest Master, so use the default:
                $recipient_en['en_name'] = 'Master';
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
            $ens = $this->Database_model->en_fetch(array(
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
                //Converts Entity Link Types to their corresponding Master Message Sent Transaction Types:
                $master_media_sent_conv = array(
                    4258 => 4553, //video
                    4259 => 4554, //audio
                    4260 => 4555, //image
                    4261 => 4556, //file
                );
            }


            //Determine what type of Media this reference has:
            foreach ($ens[0]['en__parents'] as $parent_en) {

                if (array_key_exists($parent_en['tr_en_type_id'], $en_convert_4537)) {

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
                            'tr_en_type_id' => $master_media_sent_conv[$parent_en['tr_en_type_id']],
                            'tr_content' => ($fb_att_id > 0 ? null : $parent_en['tr_content']),
                            'fb_att_id' => $fb_att_id,
                            'fb_att_type' => $en_convert_4537[$parent_en['tr_en_type_id']],
                        ));

                    } else {

                        //HTML Format, append content to current output message:
                        $output_body_message .= '<div style="margin-top:7px;">' . fn___echo_url_type($parent_en['tr_content'], $parent_en['tr_en_type_id']) . '</div>';

                    }

                } elseif ($parent_en['tr_en_type_id'] == 4256) {

                    if ($fb_messenger_format) {

                        //Generic URL:
                        array_push($fb_media_attachments, array(
                            'tr_en_type_id' => 4552, //Text Message Sent
                            'tr_content' => $parent_en['tr_content'],
                            'fb_att_id' => 0,
                            'fb_att_type' => null,
                        ));

                    } else {

                        //HTML Format, append content to current output message:
                        $output_body_message .= '<div style="margin-top:7px;">' . fn___echo_url_type($parent_en['tr_content'], $parent_en['tr_en_type_id']) . '</div>';

                    }

                } elseif ($parent_en['tr_en_type_id'] == 4257) {

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
                            'tr_en_type_id' => 4552, //Text Message Sent
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
                //TODO Maybe add another message to give Master some context on these?

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
                        'message_type' => $fb_media_attachment['tr_en_type_id'],
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
            $this->Database_model->tr_create(array(
                'tr_content' => $message_error,
                'tr_en_type_id' => 4246, //Platform Error
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


    function digest_incoming_quick_reply($en, $quick_reply_payload)
    {

        /*
         *
         * With the assumption that chat platforms like Messenger,
         * Slack and Telegram all offer a mechanism to manage a reference
         * field other than the actual message itself (Facebook calls
         * this the Reference key or Metadata), this function will
         * process that metadata string from incoming messages sent to Mench
         * by its Masters and take appropriate action.
         *
         * Inputs:
         *
         * - $en - The Master who made the request
         * - $quick_reply_payload - The reference string attached to the chat message
         *
         *
         * */

        if (!$quick_reply_payload || strlen($quick_reply_payload) < 1) {

            return false;

        } elseif (substr_count($quick_reply_payload, 'ACTIONPLAN-SKIP_') == 1) {

            $action_unsubscribe = fn___one_two_explode('ACTIONPLAN-SKIP_', '', $quick_reply_payload);

            if ($action_unsubscribe == 'CANCEL') {

                //Master seems to have changed their mind, confirm with them:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => 'Awesome, I am excited to continue helping you to ' . $this->config->item('in_primary_name') . '.',
                    ),
                ));

                //Inform Master on how to can command Mench:
                $this->Matrix_model->compose_messages(array(
                    'tr_en_child_id' => $en['en_id'],
                    'tr_in_child_id' => 8332, //Train Master to command Mench
                ));

            } elseif ($action_unsubscribe == 'ALL') {

                //Master wants completely out...

                //Remove all Action Plans:
                $actionplans = $this->Database_model->tr_fetch(array(
                    'tr_en_type_id' => 4235, //Action Plans
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Master
                    'tr_status IN (0,1,2)' => null, //Actively working on (Status 2 is syncing updates, and they want out)
                ));
                foreach ($actionplans as $tr) {
                    $this->Database_model->tr_update($tr['tr_id'], array(
                        'tr_status' => -1, //Removed
                    ), $en['en_id']); //Give credit to them
                }

                //Update User communication level to Unsubscribe:
                $this->Matrix_model->fn___en_radio_set(4454, 4455, $en['en_id'], $en['en_id']);

                //Let them know about these changes:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => 'Confirmed, I removed ' . count($actionplans) . ' Action Plan' . fn___echo__s(count($actionplans)) . ' from your account. This is the final message you will receive from me unless you message me. Take care of your self and I hope to talk to you soon 😘',
                    ),
                ));

            } elseif (intval($action_unsubscribe) > 0) {

                //User wants to Remove a specific Action Plan, validate it:
                $actionplans = $this->Database_model->tr_fetch(array(
                    'tr_en_type_id' => 4235, //Action Plan
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Master
                    'tr_in_child_id' => intval($action_unsubscribe),
                ), array('en_child'));

                //All good?
                if (count($actionplans) > 0) {

                    //Update status for this single Action Plan:
                    $this->Database_model->tr_update($actionplans[0]['tr_id'], array(
                        'tr_status' => -1, //Removed
                    ), $en['en_id']); //Give credit to them

                    //Show success message to user:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_content' => 'I have successfully removed the intention to ' . $actionplans[0]['in_outcome'] . ' from your Action Plan. Say "Unsubscribe" if you wish to stop all future communications.',
                        ),
                    ));

                    //Inform Master on how to can command Mench:
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_in_child_id' => 8332, //Train Master to command Mench
                    ));

                } else {

                    //Oooops, this should not happen
                    //let them know we had error:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_content' => 'Unable to process your request as I could not locate your Action Plan. Please try again.',
                        ),
                    ));

                    //Log error transaction:
                    $this->Database_model->tr_create(array(
                        'tr_en_credit_id' => $en['en_id'],
                        'tr_content' => 'Failed to skip an intent from the master Action Plan',
                        'tr_en_type_id' => 4246, //Platform Error
                        'tr_tr_parent_id' => intval($action_unsubscribe),
                    ));

                }

            }

        } elseif (substr_count($quick_reply_payload, 'REACTIVATE_') == 1) {

            if ($quick_reply_payload == 'REACTIVATE_YES') {

                //Update User communication level to Receive Silent Push Notifications:
                $this->Matrix_model->fn___en_radio_set(4454, 4457, $en['en_id'], $en['en_id']);

                //Inform them:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => 'Sweet, you account is now activated but you are not subscribed to any intents yet.',
                    ),
                ));

                //Inform Master on how to can command Mench:
                $this->Matrix_model->compose_messages(array(
                    'tr_en_child_id' => $en['en_id'],
                    'tr_in_child_id' => 8332, //Train Master to command Mench
                ));

            } elseif ($quick_reply_payload == 'REACTIVATE_NO') {

                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => 'Ok, your account will remain unsubscribed.',
                    ),
                ));

            }

        } elseif (is_int($quick_reply_payload)) {

            //Validate this intent:
            $ref_value = intval($quick_reply_payload);

            if ($ref_value == 'REJECT') {

                //They rejected the offer... Acknowledge and give response:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => 'Ok, so how can I help you ' . $this->config->item('in_primary_name') . '?',
                    ),
                ));

                //Inform Master on how to can command Mench:
                $this->Matrix_model->compose_messages(array(
                    'tr_en_child_id' => $en['en_id'],
                    'tr_in_child_id' => 8332, //Train Master to command Mench
                ));

            } else {

                //This reference must be a specific intent ID:
                $in_id = intval($ref_value);

                //They confirmed they want to add this intention to their Action Plan:
                $ins = $this->Database_model->in_fetch(array(
                    'in_id' => $in_id,
                ));

                //Any issues?
                if (count($ins) < 1) {

                    //Ooops we could not find the intention:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_content' => 'I was unable to locate intent #' . $in_id . ' [' . $quick_reply_payload . ']',
                        ),
                    ));

                } elseif ($ins[0]['in_status'] < 2) {

                    //Ooopsi Intention is no longer published:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_content' => 'I was unable to subscribe you to ' . $ins[0]['in_outcome'] . ' as its not published',
                        ),
                    ));

                } else {

                    //Confirm if they are interested for this intention:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_in_child_id' => $ins[0]['in_id'],
                            'tr_content' => 'Hello hello 👋 are you interested to ' . $ins[0]['in_outcome'] . '?',
                            'quick_replies' => array(
                                array(
                                    'content_type' => 'text',
                                    'title' => 'Yes, Learn More',
                                    'payload' => 'ACTIONPLAN-ADD-CONFIRM_' . $ins[0]['in_id'],
                                ),
                                array(
                                    'content_type' => 'text',
                                    'title' => 'No',
                                    'payload' => 'ACTIONPLAN-ADD-INITIATE_REJECT',
                                ),
                            ),
                        ),
                    ));

                }
            }

        } elseif (substr_count($quick_reply_payload, 'ACTIONPLAN-ADD-CONFIRM_') == 1) {

            //Initiating an intent Action Plan:
            $ins = $this->Database_model->in_fetch(array(
                'in_id' => intval(fn___one_two_explode('ACTIONPLAN-ADD-CONFIRM_', '', $quick_reply_payload)),
                'in_status >=' => 2,
            ));
            if (count($ins) == 1) {

                //Intent seems good...
                //See if this intent belong to ANY of this Master's Action Plans or Action Plan Intents:
                $actionplans = $this->Database_model->tr_fetch(array(
                    'tr_en_type_id IN (4235,4559)' => null, //Action Plans or Action Plan Intents
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Master
                    'tr_in_child_id' => $ins[0]['in_id'],
                ));

                if (count($actionplans) > 0) {

                    //Let the user know that this is a duplicate:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_in_child_id' => $ins[0]['in_id'],
                            'tr_tr_parent_id' => ($actionplans[0]['tr_tr_parent_id'] > 0 ? $actionplans[0]['tr_tr_parent_id'] : $actionplans[0]['tr_id']),
                            'tr_content' => 'The intention to ' . $ins[0]['in_outcome'] . ' has already been added to your Action Plan. We have been working on it together since ' . fn___echo_time_date($actionplans[0]['tr_timestamp']) . '. /link:See in 🚩Action Plan:https://mench.com/my/actionplan/' . $actionplans[0]['tr_tr_parent_id'] . '/' . $actionplans[0]['tr_in_child_id']
                        ),
                    ));

                } else {

                    //Confirm if they really want to add this intention to their Action Plan...

                    //Send all on-start messages for this intention so they can review it:
                    $messages_on_start = $this->Database_model->tr_fetch(array(
                        'tr_status >=' => 2, //Published+
                        'tr_en_type_id' => 4231, //On-Start Messages
                        'tr_in_child_id' => $ins[0]['in_id'],
                    ), array(), 0, 0, array('tr_order' => 'ASC'));

                    foreach ($messages_on_start as $tr) {
                        $this->Chat_model->dispatch_message(array(
                            array_merge($tr, array(
                                'tr_en_child_id' => $en['en_id'],
                            )),
                        ));
                    }

                    //Send message for final confirmation with the overview of how long/difficult it would be to accomplish this intention:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_in_child_id' => $ins[0]['in_id'],
                            'tr_content' => 'Here is an overview:' . "\n\n" .
                                fn___echo_in_overview($ins[0], true) .
                                fn___echo_in_referenced_content($ins[0], true) .
                                fn___echo_in_experts($ins[0], true) .
                                fn___echo_in_time_estimate($ins[0], true) .
                                fn___echo_in_cost_range($ins[0], true) .
                                "\n" . 'Are you ready to ' . $ins[0]['in_outcome'] . '?',
                            'quick_replies' => array(
                                array(
                                    'content_type' => 'text',
                                    'title' => 'Yes, Subscribe',
                                    'payload' => 'ACTIONPLAN-ADD-CONFIRMED_' . $ins[0]['in_id'],
                                ),
                                array(
                                    'content_type' => 'text',
                                    'title' => 'No',
                                    'payload' => 'ACTIONPLAN-ADD-INITIATE_REJECT',
                                ),
                                //TODO Maybe Show a "Learn More" if Learn More messages were available
                            ),
                        ),
                    ));

                }
            }

        } elseif (substr_count($quick_reply_payload, 'ACTIONPLAN-ADD-CONFIRMED_') == 1) {

            //Validate Intent ID:
            $ins = $this->Database_model->in_fetch(array(
                'in_id' => intval(fn___one_two_explode('ACTIONPLAN-ADD-CONFIRMED_', '', $quick_reply_payload)),
                'in_status >=' => 2,
            ));

            if (count($ins) == 1) {

                //Add intent to Master's Action Plan:
                $actionplan = $this->Database_model->tr_create(array(
                    'tr_en_type_id' => 4235, //Action Plan
                    'tr_in_child_id' => $ins[0]['in_id'], //The Intent they are adding
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Master
                    'tr_status' => 0, //New
                    'tr_order' => 1 + $this->Database_model->tr_max_order(array( //Place this intent at the end of all intents the Master is working on...
                            'tr_en_type_id' => 4235, //Action Plan
                            'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
                            'tr_en_parent_id' => $en['en_id'], //Belongs to this Master
                        )),
                ));

                //Was this added successfully?
                if (isset($actionplan['tr_id']) && $actionplan['tr_id'] > 0) {

                    //Also add all relevant child intents:
                    $this->Database_model->in_recursive_fetch($ins[0]['in_id'], true, false, $actionplan['tr_id']);

                    //Confirm with them that we're now ready:
                    $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_in_child_id' => $ins[0]['in_id'],
                            'tr_tr_parent_id' => $actionplan['tr_id'],
                            'tr_content' => 'Success! I have added the intention to ' . $ins[0]['in_outcome'] . ' to your Action Plan 🙌 /link:See in 🚩Action Plan:https://mench.com/my/actionplan/' . $actionplan['tr_id'] . '/' . $ins[0]['in_id'],
                        ),
                    ));

                    //Initiate first message for action plan tree:
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_in_child_id' => $ins[0]['in_id'],
                        'tr_tr_parent_id' => $actionplan['tr_id'],
                    ), true);

                }
            }


        } elseif (fn___includes_any($quick_reply_payload, array('ACTIONPLAN-SKIP-CONFIRMED_', 'ACTIONPLAN-SKIP-INITIATE_', 'ACTIONPLAN-SKIP-CANCEL_'))) {

            //See which stage of the skip request they are:
            $handler = fn___includes_any($quick_reply_payload, array('ACTIONPLAN-SKIP-CONFIRMED_', 'ACTIONPLAN-SKIP-INITIATE_', 'ACTIONPLAN-SKIP-CANCEL_'));

            //Extract variables from REF:
            $input_parts = explode('_', fn___one_two_explode($handler, '', $quick_reply_payload));
            $tr_status = intval($input_parts[0]); //It would be $tr_status=1 initial (working on) and then would change to either -1 IF skip was cancelled or 2 IF skip was confirmed.
            $tr_id = intval($input_parts[1]);

            //Fetch this transaction:
            $actionplans = $this->Database_model->tr_fetch(array(
                'tr_id' => $tr_id,
                'tr_en_type_id' => 4559, //Action Plan Intents
            ), array('in_child'));

            //Validate inputs:
            if (!in_array($tr_status, array(-1, 1, 2)) || count($actionplans) < 1) {
                //Log Unknown error:
                return $this->Database_model->tr_create(array(
                    'tr_content' => 'digest_incoming_quick_reply() failed to fetch proper data for a skip request with reference value [' . $quick_reply_payload . ']',
                    'tr_en_type_id' => 4246, //Platform Error
                    'tr_metadata' => $en,
                    'tr_tr_parent_id' => $tr_id,
                ));
            }


            //Was this initiating?
            if ($tr_status == 1) {

                //User has indicated they want to skip this tree and move on to the next item in-line:
                //Lets confirm the implications of this SKIP to ensure they are aware:

                //See how many children would be skipped if they decide to do so:
                $would_be_skipped = $this->Database_model->k_skip_recursive_down($tr_id, false);
                $would_be_skipped_count = count($would_be_skipped);

                if ($would_be_skipped_count == 0) {

                    //Nothing found to skip! This should not happen, log error:
                    $this->Database_model->tr_create(array(
                        'tr_content' => 'digest_incoming_quick_reply() did not find anything to skip for [' . $quick_reply_payload . ']',
                        'tr_en_type_id' => 4246, //Platform Error
                        'tr_tr_parent_id' => $tr_id,
                        'tr_metadata' => $en,
                    ));

                    //Inform user:
                    return $this->Chat_model->dispatch_message(array(
                        array(
                            'tr_en_child_id' => $en['en_id'],
                            'tr_content' => 'I did not find anything to skip!',
                        ),
                    ));

                }


                //Log transaction for skip request:
                $new_tr = $this->Database_model->tr_create(array(
                    'tr_en_credit_id' => $en['en_id'], //user who searched
                    'tr_en_type_id' => 4284, //Skip Intent
                    'tr_tr_parent_id' => $tr_id, //The parent transaction that points to this intent in the Masters Action Plan
                    'tr_status' => 1, //Working on... not yet decided to skip or not as they need to see the consequences before making an informed decision. Will be updated to -1 or 2 based on their response...
                    'tr_metadata' => array(
                        'would_be_skipped' => $would_be_skipped,
                        'ref' => $quick_reply_payload,
                    ),
                ));


                //Construct the message to give more details on skipping:
                $tr_content = 'You are about to skip these ' . $would_be_skipped_count . ' concept' . fn___echo__s($would_be_skipped_count) . ':';
                foreach ($would_be_skipped as $counter => $k_c) {
                    if (strlen($tr_content) < ($this->config->item('fb_max_message') - 200)) {
                        //We have enough room to add more:
                        $tr_content .= "\n\n" . ($counter + 1) . '/ ' . $k_c['in_outcome'];
                    } else {
                        //We cannot add any more, indicate truncating:
                        $remainder = $would_be_skipped_count - $counter;
                        $tr_content .= "\n\n" . 'And ' . $remainder . ' more concept' . fn___echo__s($remainder) . '!';
                        break;
                    }
                }

                //Recommend against it:
                $tr_content .= "\n\n" . 'I would not recommend skipping unless you feel comfortable learning these concepts on your own.';

                //Send them the message:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => $tr_content,
                        'quick_replies' => array(
                            array(
                                'content_type' => 'text',
                                'title' => 'Skip ' . $would_be_skipped_count . ' concept' . fn___echo__s($would_be_skipped_count) . ' 🚫',
                                'payload' => 'ACTIONPLAN-SKIP-INITIATE_2_' . $new_tr['tr_id'], //Confirm and skip
                            ),
                            array(
                                'content_type' => 'text',
                                'title' => 'Continue ▶️',
                                'payload' => 'ACTIONPLAN-SKIP-INITIATE_-1_' . $new_tr['tr_id'], //Cancel skipping
                            ),
                        ),
                    ),
                ));

            } else {


                //They have either confirmed or cancelled the skip:
                if ($tr_status == -1) {

                    //user changed their mind and does not want to skip anymore
                    $tr_content = 'I am happy you changed your mind! Let\'s continue...';

                    //Reset ranking to find the next real item:
                    $tr_order = 0;

                } elseif ($tr_status == 2) {

                    //Actually skip and see if we've finished this Action Plan:
                    $skippable_ks = $this->Database_model->k_skip_recursive_down($tr_id);

                    //Confirm the skip:
                    $tr_content = 'Confirmed, I marked this section as skipped. You can always re-visit these concepts in your Action Plan and complete them at any time. /link:See in 🚩Action Plan:https://mench.com/my/actionplan/' . $actionplans[0]['tr_tr_parent_id'] . '/' . $actionplans[0]['tr_in_child_id'];

                }

                //Send message:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_tr_parent_id' => $tr_id,
                        'tr_content' => $tr_content,
                    ),
                ));

                //Update transaction status accordingly:
                $this->Database_model->tr_update($tr_id, array(
                    'tr_status' => $tr_status,
                ), $en['en_id']);

                //Find the next item to navigate them to:
                $next_ins = $this->Matrix_model->fn___in_next_actionplan($tr_id);
                if ($next_ins) {
                    //Now move on to communicate the next step.
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_in_child_id' => $next_ins[0]['in_id'],
                        'tr_tr_parent_id' => $tr_id,
                    ));
                }

            }

        } elseif (substr_count($quick_reply_payload, 'MARKCOMPLETE_') == 1) {

            //Master consumed AND tree content, and is ready to move on to next intent...
            $tr_id = intval(fn___one_two_explode('MARKCOMPLETE_', '', $quick_reply_payload));

            if ($tr_id > 0) {

                //Fetch Action Plans with its Master:
                $actionplan_ins = $this->Database_model->tr_fetch(array(
                    'tr_id' => $tr_id,
                ), array('en_parent', 'in_parent'));

                //Mark this intent as complete:
                $this->Matrix_model->in_actionplan_complete_up($actionplan_ins[0], $actionplan_ins[0]);

                //Go to next item:
                $next_ins = $this->Matrix_model->fn___in_next_actionplan($tr_id);

                if ($next_ins) {
                    //Now move on to communicate the next step.
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_in_child_id' => $next_ins[0]['in_id'],
                        'tr_id' => $next_ins[0]['tr_id'],
                        'tr_tr_parent_id' => $tr_id,
                    ));
                }

            }

        } elseif (substr_count($quick_reply_payload, 'CHOOSEOR_') == 1) {

            //Master has responded to a multiple-choice OR tree
            $input_parts = explode('_', fn___one_two_explode('CHOOSEOR_', '', $quick_reply_payload));
            $tr_id = intval($input_parts[0]);
            $tr_in_parent_id = intval($input_parts[1]);
            $in_id = intval($input_parts[2]);
            $tr_order = intval($input_parts[3]);

            if (!($tr_id > 0 && $tr_in_parent_id > 0 && $in_id > 0 && $tr_order > 0)) {
                //Log Unknown error:
                $this->Database_model->tr_create(array(
                    'tr_content' => 'digest_incoming_quick_reply() failed to fetch proper data for CHOOSEOR_ request with reference value [' . $quick_reply_payload . ']',
                    'tr_en_type_id' => 4246, //Platform Error
                    'tr_metadata' => $en,
                    'tr_tr_parent_id' => $tr_id,
                    'tr_in_child_id' => $in_id,
                ));
                return false;
            }

            //Confirm answer received:
            $this->Matrix_model->compose_messages(array(
                'tr_en_child_id' => $en['en_id'],
                'tr_in_parent_id' => $in_id,
                'tr_in_child_id' => 8333, //Acknowledge progress with Master
                'tr_tr_parent_id' => $tr_id,
            ));

            //Now save answer:
            if ($this->Database_model->k_choose_or($tr_id, $tr_in_parent_id, $in_id)) {
                //Find the next item to navigate them to:
                $next_ins = $this->Matrix_model->fn___in_next_actionplan($tr_id);
                if ($next_ins) {
                    //Now move on to communicate the next step.
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_in_child_id' => $next_ins[0]['in_id'],
                        'tr_tr_parent_id' => $tr_id,
                    ));
                }
            }

        }
    }

    function fn___digest_incoming_message($en, $fb_received_message)
    {

        /*
         *
         * Will process the chat message only in the absence of a chat metadata
         * otherwise the digest_incoming_quick_reply() will process the message since we
         * know that the medata would have more precise instructions on what
         * needs to be done for the Master response.
         *
         * This involves string analysis and matching terms to a intents, entities
         * and known commands that will help us understand the Master and
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


        //First check if this Master is unsubscribed:
        if (count($this->Database_model->tr_fetch(array(
                'tr_en_child_id' => $en['en_id'],
                'tr_en_parent_id' => 4455, //Unsubscribed
                'tr_status >=' => 0,
            ))) > 0) {

            //Yes, this Master is Unsubscribed! Give them an option to re-activate their Mench account:
            return $this->Chat_model->dispatch_message(array(
                array(
                    'tr_en_child_id' => $en['en_id'],
                    'tr_content' => 'You are currently unsubscribed. Would you like me to re-activate your account?',
                    'quick_replies' => array(
                        array(
                            'content_type' => 'text',
                            'title' => 'Yes, Re-Activate',
                            'payload' => 'REACTIVATE_YES',
                        ),
                        array(
                            'content_type' => 'text',
                            'title' => 'Stay Unsubscribed',
                            'payload' => 'REACTIVATE_NO',
                        ),
                    ),
                ),
            ));
        }


        /*
         *
         * Ok, now attempt to understand Master's message intention.
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
            $actionplans = $this->Database_model->tr_fetch(array(
                'tr_en_type_id' => 4235, //Intents added to the action plan
                'tr_en_parent_id' => $en['en_id'], //Belongs to this Master
                'tr_status IN (0,1,2)' => null, //Actively working on
            ), array('in_child'), 10 /* Max quick replies allowed */, 0, array('tr_order' => 'ASC'));


            //Do they have anything in their Action Plan?
            if (count($actionplans) > 0) {

                //Give them options to remove specific Action Plans:
                $quick_replies = array();
                $tr_content = 'Choose one of the following options:';
                $increment = 1;

                foreach ($actionplans as $counter => $in) {
                    //Construct unsubscribe confirmation body:
                    $tr_content .= "\n\n" . '/' . ($counter + $increment) . ' Remove ' . $in['in_outcome'];
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => '/' . ($counter + $increment),
                        'payload' => 'ACTIONPLAN-SKIP_' . $in['in_id'],
                    ));
                }

                if (count($actionplans) >= 2) {
                    //Give option to skip all and unsubscribe:
                    $increment++;
                    $tr_content .= "\n\n" . '/' . ($counter + $increment) . ' Remove all intentions and unsubscribe';
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => '/' . ($counter + $increment),
                        'payload' => 'ACTIONPLAN-SKIP_ALL',
                    ));
                }

                //Alwyas give none option:
                $increment++;
                $tr_content .= "\n\n" . '/' . ($counter + $increment) . ' Cancel & keep all intentions';
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => '/' . ($counter + $increment),
                    'payload' => 'ACTIONPLAN-SKIP_CANCEL',
                ));

                //Send out message and let them confirm:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => $tr_content,
                        'quick_replies' => $quick_replies,
                    ),
                ));

            } else {

                //They do not have anything in their Action Plan, so we assume they just want to Unsubscribe and stop all future communications:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => 'Got it, just to confirm, you want to unsubscribe and stop all future communications with me?',
                        'quick_replies' => array(
                            array(
                                'content_type' => 'text',
                                'title' => 'Yes, Unsubscribe',
                                'payload' => 'ACTIONPLAN-SKIP_ALL',
                            ),
                            array(
                                'content_type' => 'text',
                                'title' => 'No, Stay Friends',
                                'payload' => 'ACTIONPLAN-SKIP_CANCEL',
                            ),
                        ),
                    ),
                ));

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
                $search_results = $this->Database_model->in_fetch(array(
                    'in_status >=' => 2, //Search published intents
                    'in_outcome LIKE \'%' . $master_command . '%\'' => null, //Basic string search
                ), array(), $result_limit);

            }


            //Log intent search:
            $this->Database_model->tr_create(array(
                'tr_content' => 'Found ' . count($search_results) . ' intent' . fn___echo__s(count($search_results)) . ' matching "' . $master_command . '"',
                'tr_metadata' => array(
                    'input_data' => $master_command,
                    'output' => $search_results,
                ),
                'tr_en_credit_id' => $en['en_id'], //user who searched
                'tr_en_type_id' => 4275, //Search for New Intent Action Plan
            ));


            if (count($search_results) > 0) {

                //Show options for the Master to add to their Action Plan:
                $quick_replies = array();
                $tr_content = 'I found these intents:';

                foreach ($search_results as $count => $in) {
                    $tr_content .= "\n\n" . ($count + 1) . '/ ' . $in['in_outcome'] . ' in ' . strip_tags(fn___echo_time_range($in));
                    array_push($quick_replies, array(
                        'content_type' => 'text',
                        'title' => ($count + 1) . '/',
                        'payload' => 'ACTIONPLAN-ADD-CONFIRM_' . $in['in_id'],
                    ));
                }

                //Give them a "None of the above" option:
                $tr_content .= "\n\n" . ($count + 2) . '/ None of the above';
                array_push($quick_replies, array(
                    'content_type' => 'text',
                    'title' => ($count + 2) . '/',
                    'payload' => 'ACTIONPLAN-ADD-INITIATE_REJECT',
                ));

                //return what we found to the master to decide:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => $tr_content,
                        'quick_replies' => $quick_replies,
                    ),
                ));

            } else {

                //Respond to user:
                $this->Chat_model->dispatch_message(array(
                    array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_content' => 'I did not find any intentions to "' . $master_command . '", but I will let you know as soon as I am trained on this. Is there anything else I can help you with right now?',
                    ),
                ));

            }

        } else {


            /*
             *
             * Ok, if we're here it means we didn't really understand what
             * the Master's intention was within their message.
             * So let's run through a few more options before letting them
             * know that we did not understand them...
             *
             * */

            //First, let's check to see if a Mench admin has not started a manual conversation with them via Facebook Inbox Chat:
            $admin_conversations = $this->Database_model->tr_fetch(array(
                'tr_timestamp >=' => date("Y-m-d H:i:s", (time() - (1800))), //Messages sent from us less than 30 minutes ago
                'tr_en_type_id' => 4280, //Messages sent from us
                'tr_en_credit_id' => 4148, //We log Facebook Inbox UI messages sent with this entity ID
            ), array(), 1);
            if (count($admin_conversations) > 0) {
                //Yes, this user is talking to an admin so do not interrupt their conversation:
                return false;
            }


            //Let them know that we did not understand their message:
            $this->Matrix_model->compose_messages(array(
                'tr_en_child_id' => $en['en_id'],
                'tr_in_child_id' => 8334, //Inform Master of Mench's one-way communication limitation
            ));


            //Log transaction:
            $this->Database_model->tr_create(array(
                'tr_en_credit_id' => $en['en_id'], //User who initiated this message
                'tr_content' => $fb_received_message,
                'tr_en_type_id' => 4287, //Log Unrecognizable Message Received
            ));


            //Do they have an Action Plan that they are working on?
            //If so, we can recommend the next step within that Action Plan...
            $actionplans = $this->Database_model->tr_fetch(array(
                'tr_en_type_id' => 4235, //Action Plan
                'tr_en_parent_id' => $en['en_id'], //Belongs to this Master
                'tr_status IN (' . join(',', $this->config->item('tr_status_incomplete')) . ')' => null, //incomplete
            ), array('in_child'), 1, 0, array('tr_order' => 'ASC'));

            if (count($actionplans) > 0) {

                //They have an Action Plan that they are working on, Remind user of their next step:
                $next_ins = $this->Matrix_model->fn___in_next_actionplan($actionplans[0]['tr_id']);

                if ($next_ins) {
                    $this->Matrix_model->compose_messages(array(
                        'tr_en_child_id' => $en['en_id'],
                        'tr_in_child_id' => $next_ins[0]['in_id'],
                        'tr_tr_parent_id' => $actionplans[0]['tr_id'],
                    ));
                }

            } else {

                /*
                 *
                 * Master has no action plan...
                 *
                 * Suggest to subscribe to our default intent
                 * only IF they have not done so already:
                 *
                 * */

                $default_actionplans = $this->Database_model->tr_fetch(array(
                    'tr_en_type_id IN (4235,4559)' => null, //Action Plan or Action Plan Intents
                    'tr_en_parent_id' => $en['en_id'], //Belongs to this Master
                    'tr_in_child_id' => $this->config->item('in_primary_id'),
                ));
                if (count($default_actionplans) == 0) {

                    //They have never taken the default intent, recommend it to them:
                    $this->Chat_model->digest_incoming_quick_reply($en, $this->config->item('in_primary_id'));

                }

            }
        }
    }


    function dispatch_message($trs)
    {

        /*
         * Will attempt to send a message to the Master using the
         * messaging platform of their choice based on the Entity
         * relations we find for the given master for each message
         * that is passed on in $trs with these fields:
         *
         * - tr_en_child_id
         * - tr_content
         *
         * */

        if (count($trs) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing input messages',
            );
        }

        //Prepare needed variables:
        $failed_count = 0; //Keeps track of failed $trs that we could not send
        $en_convert_4454 = $this->config->item('en_convert_4454'); //Translates our settings to Facebook Notification Settings
        $master_cache = array(); //Simple caching mechanism for communication settings to prevent fetching data multiple times...


        //Let's run through all input messages:
        foreach ($trs as $tr) {

            //Make sure we have the Master ID that we need to send this message to:
            if (!isset($tr['tr_en_child_id']) || !isset($tr['tr_content']) || strlen($tr['tr_content']) < 1) {

                //This should never happen! Log error:
                $failed_count++;

                $this->Database_model->tr_create(array(
                    'tr_metadata' => $tr,
                    'tr_en_type_id' => 4246, //Platform error
                    'tr_content' => 'dispatch_message() failed to send message as it was missing tr_en_child_id/tr_content',
                    'tr_tr_parent_id' => (isset($tr['tr_id']) ? $tr['tr_id'] : 0),
                ));

                continue;

            }


            //Did we already fetch this Master's communication settings in a previous $trs message?
            if (isset($master_cache[$tr['tr_en_child_id']])) {

                //We already have this user in cache, no need to fetch/validate communication settings as it has already been done:
                $trs_fb_psid = $master_cache[$tr['tr_en_child_id']]['trs_fb_psid'];
                $trs_comm_level = $master_cache[$tr['tr_en_child_id']]['trs_comm_level'];

            } else {

                /*
                 *
                 * Now let's grab this user's communication preferences including their Messenger PSID and communication level
                 *
                 * This is a great example of how to learn more about a user with a pre-determined view-point
                 * (In this case to learn more about their communication preferences)
                 *
                 * */

                //Mench Personal Assistant on Messenger:

                //TODO Deprecate in new function...

                $trs_fb_psid = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id' => 4451,
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_status >=' => 2,
                ), array('en_child')); //Also fetch user details as we need their name....


                //Mench Notification Levels:
                $trs_comm_level = $this->Database_model->tr_fetch(array(
                    'tr_en_parent_id IN (' . join(',', $this->config->item('en_ids_4454')) . ')' => null,
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_status >=' => 2,
                ));
                //Note: This should find exactly one result as it belongs to Master Radio Entity @4461


                //Start validating communication settings we fetched to ensure everything is A-OK:
                $message_error = null;

                if (count($trs_fb_psid) < 1) {

                    //Log error, should not happen!
                    $message_error = 'dispatch_message() failed to fetch Master relation to Mench Personal Assistant on Messenger.';

                } elseif (!(count($trs_comm_level) == 1)) {

                    //Log error, should not happen! Since this is part of
                    $message_error = 'dispatch_message() failed to fetch Master relation to any Mench Notification Level.';

                } elseif ($trs_comm_level[0]['tr_en_parent_id'] == 4455) {

                    //This Master is unsubscribed, so we cannot contact them!
                    $message_error = 'dispatch_message() attempted to send a message to an unsubscribed Master which is not allowed.';

                } elseif (intval($trs_fb_psid[0]['tr_content']) < 1) {

                    //The Mench Personal Assistant on Messenger relation is not storing an integer (Facebook PSID) as expected!
                    $message_error = 'dispatch_message() was unable to locate the Messenger PSID for this Master.';

                } elseif (!array_key_exists($trs_comm_level[0]['tr_en_parent_id'], $en_convert_4454)) {

                    //This is an unknown communication level (should never happen!):
                    $message_error = 'dispatch_message() fetched an unknown [' . $trs_comm_level[0]['tr_en_parent_id'] . '] Mench Notification Level!';

                }


                //Did we find an error?
                if ($message_error) {

                    $failed_count++;
                    $this->Database_model->tr_create(array(
                        'tr_en_child_id' => $tr['tr_en_child_id'],
                        'tr_en_type_id' => 4246, //Platform error
                        'tr_metadata' => array(
                            'trs_fb_psid' => $trs_fb_psid,
                            'trs_comm_level' => $trs_comm_level,
                            'tr' => $tr,
                        ),
                        'tr_content' => $message_error,
                        'tr_tr_parent_id' => (isset($tr['tr_id']) ? $tr['tr_id'] : 0),
                    ));
                    continue;

                } else {

                    //Add this to user cache in case this user is repeated multiple times within $trs messages:
                    $master_cache[$tr['tr_en_child_id']] = array(
                        'trs_fb_psid' => $trs_fb_psid,
                        'trs_comm_level' => $trs_comm_level,
                    );

                }

            }


            //Prepare Payload:
            $payload = array(
                'recipient' => array('id' => $trs_fb_psid[0]['tr_content']),
                'message' => echo_body_message($tr, $trs_fb_psid[0]['en_name'], true),
                'notification_type' => $en_convert_4454[$trs_comm_level[0]['tr_en_parent_id']], //Appropriate notification level
                'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION', //We're always educating users without promoting anything! Learn more at: https://developers.facebook.com/docs/messenger-platform/send-messages#messaging_types
            );

            //Send message via Facebook Graph API:
            $fb_graph_process = $this->Chat_model->fn___facebook_graph('POST', '/me/messages', $payload);


            //How did it go?
            if ($fb_graph_process['status']) {

                //Log Successful Transaction:
                $this->Database_model->tr_create(array(
                    'tr_en_type_id' => 4280, //Message Sent
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_content' => $tr['tr_content'],
                    'tr_metadata' => array(
                        'input_message' => $tr,
                        'payload' => $payload,
                        'results' => $fb_graph_process,
                    ),
                    //Store some optional fields if available:
                    'tr_en_credit_id' => (isset($tr['tr_en_credit_id']) ? $tr['tr_en_credit_id'] : 0),
                    'tr_en_parent_id' => (isset($tr['tr_en_parent_id']) ? $tr['tr_en_parent_id'] : 0),
                    'tr_in_parent_id' => (isset($tr['tr_in_parent_id']) ? $tr['tr_in_parent_id'] : 0),
                    'tr_in_child_id' => (isset($tr['tr_in_child_id']) ? $tr['tr_in_child_id'] : 0),
                    'tr_tr_parent_id' => (isset($tr['tr_id']) ? $tr['tr_id'] : 0),
                ));

            } else {

                //Oooopsi, something went wrong from the Facebook side:
                $failed_count++;

                //Log error:
                $this->Database_model->tr_create(array(
                    'tr_en_type_id' => 4246, //Platform error
                    'tr_metadata' => $tr,
                    'tr_content' => 'dispatch_message() encountered a Facebook Graph error when trying to send a message to a Master.',
                    'tr_tr_parent_id' => $tr['tr_id'],
                    'tr_en_child_id' => $tr['tr_en_child_id'],
                    'tr_metadata' => array(
                        'input_message' => $tr,
                        'payload' => $payload,
                        'results' => $fb_graph_process,
                    ),
                ));

            }

        }


        //Return results:
        if ($failed_count > 0) {

            return array(
                'status' => 0,
                'message' => 'Failed to send ' . $failed_count . '/' . count($trs) . ' message' . fn___echo__s(count($trs)),
            );

        } else {

            return array(
                'status' => 1,
                'message' => 'Successfully sent ' . count($trs) . ' message' . fn___echo__s(count($trs)),
            );

        }
    }


    function fn___dispatch_email($to_array, $subject, $html_message, $tr_create = array(), $reply_to = null)
    {

        /*
         *
         * DEPRECATED for now!
         *
         * We used to support sending emails but since Dec 2018 we've
         * focused on Messenger as the only medium of communication.
         *
         * */

        return true;

        if (fn___is_dev()) {
            return true;
        }

        //Loadup amazon SES:
        require_once('application/libraries/aws/aws-autoloader.php');
        $this->CLIENT = new Aws\Ses\SesClient([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => $this->config->item('aws_credentials'),
        ]);

        if (!$reply_to) {
            //Set default:
            $reply_to = 'support@mench.com';
        }

        //Log transaction once:
        if (count($tr_create) > 0) {
            $this->Database_model->tr_create($tr_create);
        }

        return $this->CLIENT->sendEmail(array(
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
            'ReplyToAddresses' => array($reply_to),
            'ReturnPath' => 'support@mench.com',
        ));

    }

}