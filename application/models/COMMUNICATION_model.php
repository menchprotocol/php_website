<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class COMMUNICATION_model extends CI_Model
{

    /*
     *
     * Functions to send & receive messages
     * through Email & Messenger
     *
     * */

    function __construct()
    {
        parent::__construct();
    }


    function send_email($to_array, $subject, $html_message)
    {

        /*
         *
         * Send an email via our Amazon server
         *
         * */

        if (is_dev_environment()) {
            return false; //We cannot send emails on Dev server
        }

        //Loadup amazon SES:
        require_once('application/libraries/aws/aws-autoloader.php');
        $this->CLIENT = new Aws\Ses\SesClient([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => $this->config->item('cred_aws'),
        ]);

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
            'ReplyToAddresses' => array('support@mench.com'),
            'ReturnPath' => 'support@mench.com',
        ));
    }


    function send_message($input_message, $recipient_en = array(), $message_in_id = 0)
    {

        /*
         *
         * The primary function that constructs messages based on the following inputs:
         *
         *
         * - $input_message:        The message text which may include source
         *                          references like "@123". This may NOT include
         *                          URLs as they must be first turned into an
         *                          source and then referenced within a message.
         *
         *
         * - $recipient_en:         The source object that this message is supposed
         *                          to be delivered to. May be an empty array for
         *                          when we want to show these messages to guests,
         *                          and it may contain the full source object or it
         *                          may only contain the source ID, which enables this
         *                          function to fetch further information from that
         *                          source as required based on its other parameters.
         *
         * */

        //This could happen with random messages
        if(strlen($input_message) < 1){
            return false;
        }

        //Validate message:
        $msg_validation = $this->COMMUNICATION_model->build_message($input_message, $recipient_en, 0, $message_in_id, false);


        //Did we have ane error in message validation?
        if (!$msg_validation['status'] || !isset($msg_validation['output_messages'])) {

            //Log Error Link:
            $this->LEDGER_model->ln_create(array(
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                'ln_content' => 'build_message() returned error [' . $msg_validation['message'] . '] for input message [' . $input_message . ']',
                'ln_metadata' => array(
                    'input_message' => $input_message,
                    'recipient_en' => $recipient_en,
                    'message_in_id' => $message_in_id
                ),
            ));

            return false;
        }

        //Message validation passed...
        $html_message_body = '';
        foreach($msg_validation['output_messages'] as $output_message) {
            $html_message_body .= $output_message['message_body'];
        }
        return $html_message_body;

    }

    function build_message($input_message, $recipient_en = array(), $message_type_en_id = 0, $message_in_id = 0, $strict_validation = true)
    {

        /*
         *
         * This function is used to validate IDEA NOTES.
         *
         * See send_message() for more information on input variables.
         *
         * */


        //Try to fetch session if recipient not provided:
        if(!isset($recipient_en['en_id'])){
            $recipient_en = superpower_assigned();
        }

        $is_being_modified = ( $message_type_en_id > 0 ); //IF $message_type_en_id > 0 means we're adding/editing and need to do extra checks

        //Cleanup:
        $input_message = trim($input_message);
        $input_message = str_replace('’','\'',$input_message);

        //Start with basic input validation:
        if (strlen($input_message) < 1) {
            return array(
                'status' => 0,
                'message' => 'Missing Message Content',
            );
        } elseif ($strict_validation && strlen($input_message) > config_var(4485)) {
            return array(
                'status' => 0,
                'message' => 'Message is '.strlen($input_message).' characters long which is more than the allowed ' . config_var(4485) . ' characters',
            );
        } elseif (!preg_match('//u', $input_message)) {
            return array(
                'status' => 0,
                'message' => 'Message must be UTF8',
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
        $string_references = extract_source_references($input_message);

        if($strict_validation){
            //Check only in strict mode:
            if (count($string_references['ref_urls']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'You can reference a maximum of 1 URL per message',
                );

            } elseif (count($string_references['ref_sources']) > 1) {

                return array(
                    'status' => 0,
                    'message' => 'Message can include a maximum of 1 source reference',
                );

            } elseif (count($string_references['ref_sources']) > 0 && count($string_references['ref_urls']) > 0) {

                return array(
                    'status' => 0,
                    'message' => 'You can either reference an source OR a URL, as URLs are transformed to sources',
                );

            }
        }



        /*
         *
         * $message_type_en_id Validation
         * only in strict mode!
         *
         * */
        if($strict_validation && $message_type_en_id > 0){

            //See if this message type has specific input requirements:
            $en_all_4485 = $this->config->item('en_all_4485');

            //Now check for source referencing settings:
            if(!in_array(4986 , $en_all_4485[$message_type_en_id]['m_parents']) && !in_array(7551 , $en_all_4485[$message_type_en_id]['m_parents']) && count($string_references['ref_sources']) > 0){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' do not support source referencing.',
                );

            } elseif(in_array(7551 , $en_all_4485[$message_type_en_id]['m_parents']) && count($string_references['ref_sources']) != 1 && count($string_references['ref_urls']) != 1){

                return array(
                    'status' => 0,
                    'message' => $en_all_4485[$message_type_en_id]['m_name'].' require an source reference.',
                );

            }

        }





        /*
         *
         * Transform URLs into Player + Links
         *
         * */
        if ($strict_validation && count($string_references['ref_urls']) > 0) {

            //No source linked, but we have a URL that we should turn into an source if not previously:
            $url_source = $this->SOURCE_model->en_url($string_references['ref_urls'][0], ( isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0 ));

            //Did we have an error?
            if (!$url_source['status'] || !isset($url_source['en_url']['en_id']) || intval($url_source['en_url']['en_id']) < 1) {
                return $url_source;
            }

            //Transform this URL into an source IF it was found/created:
            if(intval($url_source['en_url']['en_id']) > 0){

                $string_references['ref_sources'][0] = intval($url_source['en_url']['en_id']);

                //Replace the URL with this new @source in message.
                //This is the only valid modification we can do to $input_message before storing it in the DB:
                $input_message = str_replace($string_references['ref_urls'][0], '@' . $string_references['ref_sources'][0], $input_message);

                //Delete URL:
                unset($string_references['ref_urls'][0]);

            }

        }


        /*
         *
         * Process Commands
         *
         * */

        //Start building the Output message body based on format:
        $output_body_message = htmlentities($input_message);



        /*
         *
         * Referenced Player
         *
         * */

        //Will contain media from referenced source:
        $fb_media_attachments = array();

        //We assume this message has text, unless its only content is an source reference like "@123"
        $has_text = true;

        if (count($string_references['ref_sources']) > 0) {

            //We have a reference within this message, let's fetch it to better understand it:
            $ens = $this->SOURCE_model->en_fetch(array(
                'en_id' => $string_references['ref_sources'][0], //Alert: We will only have a single reference per message
            ));

            if (count($ens) < 1) {
                return array(
                    'status' => 0,
                    'message' => 'The referenced source @' . $string_references['ref_sources'][0] . ' not found',
                );
            }

            //Direct Media URLs supported:
            $en_all_6177 = $this->config->item('en_all_6177');


            //See if this source has any parent links to be shown in this appendix
            $valid_url = array();
            $message_visual_media = 0;
            $message_any = 0;
            $source_appendix = null;
            $current_mench = current_mench();
            $has_text = substr_count($input_message, ' ');

            //SOURCE IDENTIFIER
            $string_references = extract_source_references($input_message, true);
            $is_current_source = $current_mench['x_name']=='source' && $this->uri->segment(2)==$string_references['ref_sources'][0];


            //Determine what type of Media this reference has:
            //Source Profile
            if(!$is_current_source || $string_references['ref_time_found']){

                foreach($this->LEDGER_model->ln_fetch(array(
                    'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12822')) . ')' => null, //SOURCE LINK MESSAGE DISPLAY
                    'ln_portfolio_source_id' => $string_references['ref_sources'][0],
                ), array('en_profile'), 0, 0, array('en_id' => 'ASC' /* Hack to get Text first */)) as $parent_en) {

                    $message_any++;

                    if (in_array($parent_en['ln_type_source_id'], $this->config->item('en_ids_12524'))) {

                        //SOURCE LINK VISUAL
                        $message_visual_media++;

                    } elseif($parent_en['ln_type_source_id'] == 4256 /* URL */){

                        array_push($valid_url, $parent_en['ln_content']);

                    } elseif($parent_en['ln_type_source_id'] == 4255 /* TEXT */){

                        $source_appendix .= '<div class="source-appendix paddingup">*' . $parent_en['ln_content'] . '</div>';
                        continue;

                    } else {

                        //Not supported for now:
                        continue;

                    }

                    $source_appendix .= '<div class="source-appendix paddingup">' . echo_ln_content($parent_en['ln_content'], $parent_en['ln_type_source_id'], $input_message) . '</div>';

                }
            }



            //Append any appendix generated:
            $single_word_class = ( !substr_count($ens[0]['en_name'], ' ') ? ' inline-block ' : '' );
            $output_body_message .= $source_appendix;
            if($string_references['ref_time_found']){
                $identifier_string = '@' . $string_references['ref_sources'][0].':'.$string_references['ref_time_start'].':'.$string_references['ref_time_end'];
            } else {
                $identifier_string = '@' . $string_references['ref_sources'][0];
            }

            //PLAYER REFERENCE
            if(($current_mench['x_name']=='read' && !superpower_active(10967, true)) || $is_current_source){

                //NO LINK so we can maintain focus...

                if(!$has_text || ($message_any==1 && $message_visual_media==1)){

                    //HIDE
                    $output_body_message = str_replace($identifier_string, '', $output_body_message);

                } else {

                    //TEXT ONLY
                    $output_body_message = str_replace($identifier_string, '<span class="'.$single_word_class.extract_icon_color($ens[0]['en_icon']).'"><span class="img-block icon-block-xs">'.echo_en_icon($ens[0]['en_icon']).'</span><span class="text__6197_' . $ens[0]['en_id']  . '">' . $ens[0]['en_name']  . '</span></span>', $output_body_message);

                }

            } else {

                //FULL SOURCE LINK
                $output_body_message = str_replace($identifier_string, '<a class="montserrat doupper '.$single_word_class.extract_icon_color($ens[0]['en_icon']).'" href="/source/' . $ens[0]['en_id'] . '">'.( !in_array($ens[0]['en_status_source_id'], $this->config->item('en_ids_7357')) ? '<span class="img-block icon-block-xs">'.$en_all_6177[$ens[0]['en_status_source_id']]['m_icon'].'</span> ' : '' ).'<span class="img-block icon-block-xs">'.echo_en_icon($ens[0]['en_icon']).'</span><span class="text__6197_' . $ens[0]['en_id']  . '">' . $ens[0]['en_name']  . '</span></a>', $output_body_message);

            }
        }


        //Return results:
        return array(
            'status' => 1,
            'input_message' => trim($input_message),
            'output_messages' => array(
                array(
                    'message_type_en_id' => 4570, //User Received Email Message
                    'message_body' => ( strlen($output_body_message) ? '<div class="i_content padded"><div class="msg">' . nl2br($output_body_message) . '</div></div>' : null ),
                ),
            ),
            'ln_profile_source_id' => (count($string_references['ref_sources']) > 0 ? $string_references['ref_sources'][0] : 0),
        );
    }
}

?>