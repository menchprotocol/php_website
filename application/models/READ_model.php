<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class READ_model extends CI_Model
{

    /*
     *
     * Player related database functions
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
        $msg_validation = $this->READ_model->send_message_build($input_message, $recipient_en, 0, $message_in_id, false);


        //Did we have ane error in message validation?
        if (!$msg_validation['status'] || !isset($msg_validation['output_messages'])) {

            //Log Error Link:
            $this->TRANSACTION_model->create(array(
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => (isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0),
                'ln_content' => 'send_message_build() returned error [' . $msg_validation['message'] . '] for input message [' . $input_message . ']',
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


    function send_message_build($input_message, $recipient_en = array(), $message_type_en_id = 0, $message_in_id = 0, $strict_validation = true)
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
            $url_source = $this->SOURCE_model->url($string_references['ref_urls'][0], ( isset($recipient_en['en_id']) ? $recipient_en['en_id'] : 0 ));

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
            $ens = $this->SOURCE_model->fetch(array(
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

                foreach($this->TRANSACTION_model->fetch(array(
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
                    $output_body_message = str_replace($identifier_string, '<span class="'.$single_word_class.'"><span class="img-block">'.echo_en_icon($ens[0]['en_icon']).'</span>&nbsp;<span class="text__6197_' . $ens[0]['en_id']  . '">' . $ens[0]['en_name']  . '</span></span>', $output_body_message);

                }

            } else {

                //FULL SOURCE LINK
                $output_body_message = str_replace($identifier_string, '<a class="montserrat '.$single_word_class.extract_icon_color($ens[0]['en_icon']).'" href="/source/' . $ens[0]['en_id'] . '">'.( !in_array($ens[0]['en_status_source_id'], $this->config->item('en_ids_7357')) ? '<span class="img-block icon-block-xs">'.$en_all_6177[$ens[0]['en_status_source_id']]['m_icon'].'</span> ' : '' ).'<span class="img-block icon-block-xs">'.echo_en_icon($ens[0]['en_icon']).'</span><span class="text__6197_' . $ens[0]['en_id']  . '">' . $ens[0]['en_name']  . '</span></a>', $output_body_message);

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


    function find_previous($en_id, $in_id, $public_only = true)
    {

        if($en_id){
            $player_read_ids = $this->READ_model->ids($en_id);
            if(!count($player_read_ids)){
                return 0;
            }
        } else {
            $grand_parents = array();
        }

        //Fetch parents:
        foreach($this->TRANSACTION_model->fetch(array(
            'in_status_source_id IN (' . join(',', $this->config->item(($public_only ? 'en_ids_7355' : 'en_ids_7356'))) . ')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item(($public_only ? 'en_ids_7359' : 'en_ids_7360'))) . ')' => null,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_next_idea_id' => $in_id,
        ), array('in_previous'), 0, 0, array(), 'in_id') as $in_parent) {

            $recursive_parents = $this->READ_model->find_previous(0, $in_parent['in_id']);

            if($en_id){
                $top_read_ids = array_intersect($player_read_ids, array_flatten($recursive_parents));
                if(count($top_read_ids)){

                    $ins = $this->IDEA_model->fetch(array(
                        'in_id' => end($top_read_ids),
                    ));

                    //Find the next idea from the top read:
                    return $this->READ_model->find_next($en_id, $ins[0], false);

                }
            } else {
                if(count($recursive_parents)){
                    array_push($grand_parents, array_merge(array(intval($in_parent['in_id'])), $recursive_parents));
                } else {
                    array_push($grand_parents, array(intval($in_parent['in_id'])));
                }
            }

        }

        return ( $en_id ? 0 /* We could not find it */ : $grand_parents );
    }

    function find_next($en_id, $in, $first_step = true){

        /*
         *
         * Searches within a user Reads to find
         * first incomplete step.
         *
         * */

        $in_metadata = unserialize($in['in_metadata']);

        //Make sure of no terminations first:
        $check_termination_answers = array();

        if(count($in_metadata['in__metadata_expansion_steps']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_steps']));
        }
        if(count($in_metadata['in__metadata_expansion_some']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_some']));
        }
        if(count($in_metadata['in__metadata_expansion_conditional']) > 0){
            $check_termination_answers = array_merge($check_termination_answers , array_flatten($in_metadata['in__metadata_expansion_conditional']));
        }
        if(count($check_termination_answers) > 0 && count($this->TRANSACTION_model->fetch(array(
                'ln_type_source_id' => 7492, //TERMINATE
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',' , $check_termination_answers) . ')' => null, //All possible answers that might terminate...
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            ))) > 0){
            return -1;
        }



        foreach(array_flatten($in_metadata['in__metadata_common_steps']) as $common_step_in_id){

            //Is this an expansion step?
            $is_expansion = isset($in_metadata['in__metadata_expansion_steps'][$common_step_in_id]) || isset($in_metadata['in__metadata_expansion_some'][$common_step_in_id]);
            $is_condition = isset($in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]);

            //Have they completed this?
            if($is_expansion){

                //First fetch all possible answers based on correct order:
                $found_expansion = 0;
                foreach($this->TRANSACTION_model->fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'ln_previous_idea_id' => $common_step_in_id,
                ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $ln){

                    //See if this answer was selected:
                    if(count($this->TRANSACTION_model->fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINK
                        'ln_previous_idea_id' => $common_step_in_id,
                        'ln_next_idea_id' => $ln['in_id'],
                        'ln_creator_source_id' => $en_id, //Belongs to this User
                    )))){

                        $found_expansion++;

                        //Yes was answered, see if it's completed:
                        if(!count($this->TRANSACTION_model->fetch(array(
                            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                            'ln_type_source_id IN (' . join(',' , $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                            'ln_creator_source_id' => $en_id, //Belongs to this User
                            'ln_previous_idea_id' => $ln['in_id'],
                        )))){

                            //Answer is not completed, go there:
                            return $ln['in_id'];

                        } else {

                            //Answer previously completed, see if there is anyting else:
                            $found_in_id = $this->READ_model->find_next($en_id, $ln, false);
                            if($found_in_id != 0){
                                return $found_in_id;
                            }

                        }
                    }
                }

                if(!$found_expansion){
                    return $common_step_in_id;
                }

            } elseif($is_condition){

                //See which path they got unlocked, if any:
                foreach($this->TRANSACTION_model->fetch(array(
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                    'ln_creator_source_id' => $en_id, //Belongs to this User
                    'ln_previous_idea_id' => $common_step_in_id,
                    'ln_next_idea_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$common_step_in_id]) . ')' => null,
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                ), array('in_next')) as $unlocked_condition){

                    //Completed step that has OR expansions, check recursively to see if next step within here:
                    $found_in_id = $this->READ_model->find_next($en_id, $unlocked_condition, false);

                    if($found_in_id != 0){
                        return $found_in_id;
                    }

                }

            } elseif(!count($this->TRANSACTION_model->fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',' , $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                    'ln_creator_source_id' => $en_id, //Belongs to this User
                    'ln_previous_idea_id' => $common_step_in_id,
                )))){

                //Not completed yet, this is the next step:
                return $common_step_in_id;

            }

        }


        //If not part of the Reads, go to reads idea
        if($first_step){
            return $this->READ_model->find_previous($en_id, $in['in_id']);
        }


        //Really not found:
        return 0;

    }

    function find_next_go($en_id)
    {

        /*
         *
         * Searches for the next Reads step
         *
         * */

        $player_reads = $this->TRANSACTION_model->fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12969')) . ')' => null, //Reads Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0, 0, array('ln_order' => 'ASC'));

        if(!count($player_reads)){
            return 0;
        }

        //Loop through Reads Ideas and see what's next:
        foreach($player_reads as $user_in){

            //Find first incomplete step for this Reads Idea:
            $next_in_id = $this->READ_model->find_next($en_id, $user_in);

            if($next_in_id < 0){

                //We need to terminate this:
                $this->READ_model->delete($en_id, $user_in['in_id'], 7757); //MENCH REMOVED BOOKMARK
                break;

            } elseif($next_in_id > 0){

                //We found the next incomplete step, return:
                break;

            }
        }

        //Return next step Idea or false:
        return intval($next_in_id);

    }


    function focus($en_id){

        /*
         *
         * A function that goes through the Reads
         * and finds the top-priority that the user
         * is currently working on.
         *
         * */

        $top_priority_in = false;
        foreach($this->TRANSACTION_model->fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12969')) . ')' => null, //Reads Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0, 0, array('ln_order' => 'ASC')) as $home_in){

            //See progress rate so far:
            $completion_rate = $this->READ_model->completion_progress($en_id, $home_in);

            if($completion_rate['completion_percentage'] < 100){
                //This is the top priority now:
                $top_priority_in = $home_in;
                break;
            }

        }

        if(!$top_priority_in){
            return false;
        }

        //Return what's found:
        return array(
            'in' => $top_priority_in,
            'completion_rate' => $completion_rate,
        );

    }

    function delete($en_id, $in_id, $stop_method_id, $stop_feedback = null){


        if(!in_array($stop_method_id, $this->config->item('en_ids_6150') /* Reads Idea Completed */)){
            return array(
                'status' => 0,
                'message' => 'Invalid stop method',
            );
        }

        //Validate idea to be deleted:
        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $in_id,
        ));
        if (count($ins) < 1) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea',
            );
        }

        //Go ahead and delete from Reads:
        $player_reads = $this->TRANSACTION_model->fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12969')) . ')' => null, //Reads Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_previous_idea_id' => $in_id,
        ));
        if(count($player_reads) < 1){
            return array(
                'status' => 0,
                'message' => 'Could not locate Reads',
            );
        }

        //Delete Bookmark:
        foreach($player_reads as $ln){
            $this->TRANSACTION_model->update($ln['ln_id'], array(
                'ln_content' => $stop_feedback,
                'ln_status_source_id' => 6173, //DELETED
            ), $en_id, $stop_method_id);
        }

        return array(
            'status' => 1,
            'message' => 'Success',
        );

    }

    function start($en_id, $in_id, $recommender_in_id = 0){

        //Validate Idea ID:
        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        if (count($ins) != 1) {
            return false;
        }


        //Make sure not previously added to this User's Reads:
        if(!count($this->TRANSACTION_model->fetch(array(
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $in_id,
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12969')) . ')' => null, //Reads Idea Set
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            )))){

            //Not added to their Reads so far, let's go ahead and add it:
            $in_rank = 1;
            $home = $this->TRANSACTION_model->create(array(
                'ln_type_source_id' => ( $recommender_in_id > 0 ? 7495 /* User Idea Recommended */ : 4235 /* User Idea Set */ ),
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id' => $ins[0]['in_id'], //The Idea they are adding
                'ln_next_idea_id' => $recommender_in_id, //Store the recommended idea
                'ln_order' => $in_rank, //Always place at the top of their Reads
            ));

            //Mark as readed if possible:
            if($ins[0]['in_type_source_id']==6677){
                $this->READ_model->is_complete($ins[0], array(
                    'ln_type_source_id' => 4559, //READ MESSAGES
                    'ln_creator_source_id' => $en_id,
                    'ln_previous_idea_id' => $ins[0]['in_id'],
                ));
            }

            //Move other ideas down in the Reads:
            foreach($this->TRANSACTION_model->fetch(array(
                'ln_id !=' => $home['ln_id'], //Not the newly added idea
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12969')) . ')' => null, //Reads Idea Set
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_creator_source_id' => $en_id, //Belongs to this User
            ), array(''), 0, 0, array('ln_order' => 'ASC')) as $current_ins){

                //Increase rank:
                $in_rank++;

                //Update order:
                $this->TRANSACTION_model->update($current_ins['ln_id'], array(
                    'ln_order' => $in_rank,
                ), $en_id, 10681 /* Ideas Ordered Automatically  */);
            }

        }

        return true;

    }




    function completion_recursive_up($en_id, $in, $is_bottom_level = true){

        /*
         *
         * Let's see how many steps get unlocked:
         *
         * https://mench.com/source/6410
         *
         * */


        //First let's make sure this entire Idea completed by the user:
        $completion_rate = $this->READ_model->completion_progress($en_id, $in);


        if($completion_rate['completion_percentage'] < 100){
            //Not completed, so can't go further up:
            return array();
        }


        //Look at Conditional Idea Links ONLY at this level:
        $in_metadata = unserialize($in['in_metadata']);
        if(isset($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) && count($in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) > 0){

            //Make sure previous link unlocks have NOT happened before:
            $existing_expansions = $this->TRANSACTION_model->fetch(array(
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_type_source_id' => 6140, //READ UNLOCK LINK
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $in['in_id'],
                'ln_next_idea_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ));
            if(count($existing_expansions) > 0){

                //Oh we do have an expansion that previously happened! So skip this:
                /*
                 * This was being triggered but I am not sure if its normal or not!
                 * For now will comment out so no errors are logged
                 * TODO: See if you can make sense of this section. The question is
                 * if we would ever try to process a conditional step twice? If it
                 * happens, is it an error or not, and should simply be ignored?
                 *
                $this->TRANSACTION_model->create(array(
                    'ln_previous_idea_id' => $in['in_id'],
                    'ln_next_idea_id' => $existing_expansions[0]['ln_next_idea_id'],
                    'ln_content' => 'completion_recursive_up() detected duplicate Label Expansion entries',
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_creator_source_id' => $en_id,
                ));
                */

                return array();

            }


            //Yes, Let's calculate user's score for this idea:
            $user_marks = $this->READ_model->completion_marks($en_id, $in);





            //Detect potential conditional steps to be Unlocked:
            $found_match = 0;
            $locked_links = $this->TRANSACTION_model->fetch(array(
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12842')) . ')' => null, //IDEA LINKS ONE-WAY
                'ln_previous_idea_id' => $in['in_id'],
                'ln_next_idea_id IN (' . join(',', $in_metadata['in__metadata_expansion_conditional'][$in['in_id']]) . ')' => null, //Limit to cached answers
            ), array('in_next'), 0, 0);


            foreach($locked_links as $locked_link) {

                //See if it unlocks any of these ranges defined in the metadata:
                $ln_metadata = unserialize($locked_link['ln_metadata']);

                //Defines ranges:
                if(!isset($ln_metadata['tr__conditional_score_min'])){
                    $ln_metadata['tr__conditional_score_min'] = 0;
                }
                if(!isset($ln_metadata['tr__conditional_score_max'])){
                    $ln_metadata['tr__conditional_score_max'] = 0;
                }


                if($user_marks['steps_answered_score']>=$ln_metadata['tr__conditional_score_min'] && $user_marks['steps_answered_score']<=$ln_metadata['tr__conditional_score_max']){

                    //Found a match:
                    $found_match++;

                    //Unlock Reads:
                    $this->TRANSACTION_model->create(array(
                        'ln_type_source_id' => 6140, //READ UNLOCK LINK
                        'ln_creator_source_id' => $en_id,
                        'ln_previous_idea_id' => $in['in_id'],
                        'ln_next_idea_id' => $locked_link['in_id'],
                        'ln_metadata' => array(
                            'completion_rate' => $completion_rate,
                            'user_marks' => $user_marks,
                            'condition_ranges' => $locked_links,
                        ),
                    ));

                }
            }

            //We must have exactly 1 match by now:
            if($found_match != 1){
                $this->TRANSACTION_model->create(array(
                    'ln_content' => 'completion_recursive_up() found ['.$found_match.'] routing logic matches!',
                    'ln_type_source_id' => 4246, //Platform Bug Reports
                    'ln_creator_source_id' => $en_id,
                    'ln_previous_idea_id' => $in['in_id'],
                    'ln_metadata' => array(
                        'completion_rate' => $completion_rate,
                        'user_marks' => $user_marks,
                        'conditional_ranges' => $locked_links,
                    ),
                ));
            }

        }


        //Now go up since we know there are more levels...
        if($is_bottom_level){

            //Fetch user ideas:
            $player_read_ids = $this->READ_model->ids($en_id);

            //Prevent duplicate processes even if on multiple parent ideas:
            $parents_checked = array();

            //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
            foreach($this->IDEA_model->recursive_parents($in['in_id']) as $grand_parent_ids) {

                //Does this parent and its grandparents have an intersection with the user ideas?
                if(!array_intersect($grand_parent_ids, $player_read_ids)){
                    //Parent idea is NOT part of their Reads:
                    continue;
                }

                //Let's go through until we hit their intersection
                foreach($grand_parent_ids as $p_id){

                    //Make sure not duplicated:
                    if(in_array($p_id , $parents_checked)){
                        continue;
                    }

                    array_push($parents_checked, $p_id);

                    //Fetch parent idea:
                    $parent_ins = $this->IDEA_model->fetch(array(
                        'in_id' => $p_id,
                        'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    ));

                    //Now see if this child completion resulted in a full parent completion:
                    if(count($parent_ins) > 0){

                        //Fetch parent completion:
                        $this->READ_model->completion_recursive_up($en_id, $parent_ins[0], false);

                    }

                    //Terminate if we reached the Reads idea level:
                    if(in_array($p_id , $player_read_ids)){
                        break;
                    }
                }
            }
        }


        return true;
    }


    function unlock_locked_step($en_id, $in){

        /*
         * A function that starts from a locked idea and checks:
         *
         * 1. List users who have completed ALL/ANY (Depending on AND/OR Lock) of its children
         * 2. If > 0, then goes up recursively to see if these completions unlock other completions
         *
         * */

        if(!in_is_unlockable($in)){
            return array(
                'status' => 0,
                'message' => 'Not a valid locked idea type and status',
            );
        }


        $in__next = $this->TRANSACTION_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
            'ln_previous_idea_id' => $in['in_id'],
        ), array('in_next'), 0, 0, array('ln_order' => 'ASC'));
        if(count($in__next) < 1){
            return array(
                'status' => 0,
                'message' => 'Idea has no child ideas',
            );
        }



        /*
         *
         * Now we need to determine idea completion method.
         *
         * It's one of these two cases:
         *
         * AND Ideas are completed when all their children are completed
         *
         * OR Ideas are completed when a single child is completed
         *
         * */
        $requires_all_children = ( $in['in_type_source_id'] == 6914 /* AND Lock, meaning all children are needed */ );

        //Generate list of users who have completed it:
        $qualified_completed_users = array();

        //Go through children and see how many completed:
        foreach($in__next as $count => $child_in){

            //Fetch users who completed this:
            if($count==0){

                //Always add all the first users to the full list:
                $qualified_completed_users = $this->TRANSACTION_model->fetch(array(
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
                    'ln_previous_idea_id' => $child_in['in_id'],
                ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

                if($requires_all_children && count($qualified_completed_users)==0){
                    //No users found that would meet all children requirements:
                    break;
                }

            } else {

                //2nd Update onwards, by now we must have a base:
                if($requires_all_children){

                    //Update list of qualified users:
                    $qualified_completed_users = $this->TRANSACTION_model->fetch(array(
                        'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                        'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_6255')) . ')' => null, //READ COIN
                        'ln_previous_idea_id' => $child_in['in_id'],
                    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

                }

            }
        }

        if(count($qualified_completed_users) > 0){
            return array(
                'status' => 0,
                'message' => 'No users found to have completed',
            );
        }

    }


    function in_home($in_id, $recipient_en){

        $read_in_home = false;

        if($recipient_en['en_id'] > 0){

            //Fetch entire Reads:
            $player_read_ids = $this->READ_model->ids($recipient_en['en_id']);
            $read_in_home = in_array($in_id, $player_read_ids);

            if(!$read_in_home){
                //Go through parents ideas and detect intersects with user ideas. WARNING: Logic duplicated. Search for "ELEPHANT" to see.
                foreach($this->IDEA_model->recursive_parents($in_id) as $grand_parent_ids) {
                    //Does this parent and its grandparents have an intersection with the user ideas?
                    if (array_intersect($grand_parent_ids, $player_read_ids)) {
                        //Idea is part of their Reads:
                        $read_in_home = true;
                        break;
                    }
                }
            }
        }

        return $read_in_home;

    }


    function is_complete($in, $insert_columns){

        //Log completion link:
        $new_link = $this->TRANSACTION_model->create($insert_columns);

        //Process completion automations:
        $this->READ_model->completion_recursive_up($insert_columns['ln_creator_source_id'], $in);

        return $new_link;

    }

    function completion_marks($en_id, $in, $top_level = true)
    {

        //Fetch/validate Reads Common Ideas:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){

            //Should not happen, log error:
            $this->TRANSACTION_model->create(array(
                'ln_content' => 'completion_marks() Detected user Reads without in__metadata_common_steps value!',
                'ln_type_source_id' => 4246, //Platform Bug Reports
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $in['in_id'],
            ));

            return 0;
        }

        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);

        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            //Generic assessment marks stats:
            'steps_question_count' => 0, //The parent idea
            'steps_marks_min' => 0,
            'steps_marks_max' => 0,

            //User answer stats:
            'steps_answered_count' => 0, //How many they have answered so far
            'steps_answered_marks' => 0, //Indicates completion score

            //Calculated at the end:
            'steps_answered_score' => 0, //Used to determine which label to be unlocked...
        );


        //Process Answer ONE:
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_in_ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($in_metadata['in__metadata_expansion_steps'] as $question_in_id => $answers_in_ids ){

                //Calculate local min/max marks:
                array_push($question_in_ids, $question_in_id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->TRANSACTION_model->fetch(array(
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'ln_previous_idea_id' => $question_in_id,
                    'ln_next_idea_id IN (' . join(',', $answers_in_ids) . ')' => null, //Limit to cached answers
                ), array('in_next')) as $in_answer){

                    //Extract Link Metadata:
                    $possible_answer_metadata = unserialize($in_answer['ln_metadata']);

                    //Assign to this question:
                    $answer_marks_index[$in_answer['in_id']] = ( isset($possible_answer_metadata['tr__assessment_points']) ? intval($possible_answer_metadata['tr__assessment_points']) : 0 );

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$in_answer['in_id']] < $local_min){
                        $local_min = $answer_marks_index[$in_answer['in_id']];
                    }
                    if(is_null($local_max) || $answer_marks_index[$in_answer['in_id']] > $local_max){
                        $local_max = $answer_marks_index[$in_answer['in_id']];
                    }
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }
                if(!is_null($local_max)){
                    $metadata_this['steps_marks_max'] += $local_max;
                }
            }



            //Now let's check user answers to see what they have done:
            $total_completion = $this->TRANSACTION_model->fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->TRANSACTION_model->fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->READ_model->completion_marks($en_id, $answer_in, false);

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['in_id']] + $recursive_stats['steps_answered_marks'];

            }
        }


        //Process Answer SOME:
        if(isset($in_metadata['in__metadata_expansion_some']) && count($in_metadata['in__metadata_expansion_some']) > 0){

            //We need expansion steps (OR Ideas) to calculate question/answers:
            //To save all the marks for specific answers:
            $question_in_ids = array();
            $answer_marks_index = array();

            //Go through these expansion steps:
            foreach($in_metadata['in__metadata_expansion_some'] as $question_in_id => $answers_in_ids ){

                //Calculate local min/max marks:
                array_push($question_in_ids, $question_in_id);
                $metadata_this['steps_question_count'] += 1;
                $local_min = null;
                $local_max = null;

                //Calculate min/max points for this based on answers:
                foreach($this->TRANSACTION_model->fetch(array(
                    'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
                    'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                    'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12840')) . ')' => null, //IDEA LINKS TWO-WAY
                    'ln_previous_idea_id' => $question_in_id,
                    'ln_next_idea_id IN (' . join(',', $answers_in_ids) . ')' => null, //Limit to cached answers
                ), array('in_next')) as $in_answer){

                    //Extract Link Metadata:
                    $possible_answer_metadata = unserialize($in_answer['ln_metadata']);

                    //Assign to this question:
                    $answer_marks_index[$in_answer['in_id']] = ( isset($possible_answer_metadata['tr__assessment_points']) ? intval($possible_answer_metadata['tr__assessment_points']) : 0 );

                    //Addup local min/max marks:
                    if(is_null($local_min) || $answer_marks_index[$in_answer['in_id']] < $local_min){
                        $local_min = $answer_marks_index[$in_answer['in_id']];
                    }
                }

                //Did we have any marks for this question?
                if(!is_null($local_min)){
                    $metadata_this['steps_marks_min'] += $local_min;
                }

                //Always Add local max:
                $metadata_this['steps_marks_max'] += $answer_marks_index[$in_answer['in_id']];

            }



            //Now let's check user answers to see what they have done:
            $total_completion = $this->TRANSACTION_model->fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_completions');

            //Add to total answer count:
            $metadata_this['steps_answered_count'] += $total_completion[0]['total_completions'];

            //Go through answers:
            foreach($this->TRANSACTION_model->fetch(array(
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                'ln_previous_idea_id IN (' . join(',', $question_in_ids ) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next'), 500) as $answer_in) {

                //Fetch recursively:
                $recursive_stats = $this->READ_model->completion_marks($en_id, $answer_in, false);

                $metadata_this['steps_answered_count'] += $recursive_stats['steps_answered_count'];
                $metadata_this['steps_answered_marks'] += $answer_marks_index[$answer_in['in_id']] + $recursive_stats['steps_answered_marks'];

            }
        }



        if($top_level && $metadata_this['steps_answered_count'] > 0){

            $divider = ( $metadata_this['steps_marks_max'] - $metadata_this['steps_marks_min'] ) * 100;

            if($divider > 0){
                //See assessment summary:
                $metadata_this['steps_answered_score'] = floor( ($metadata_this['steps_answered_marks'] - $metadata_this['steps_marks_min']) / $divider );
            } else {
                //See assessment summary:
                $metadata_this['steps_answered_score'] = 0;
            }

        }


        //Return results:
        return $metadata_this;

    }



    function completion_progress($en_id, $in, $top_level = true)
    {

        if(!isset($in['in_metadata'])){
            return false;
        }

        //Fetch/validate Reads Common Ideas:
        $in_metadata = unserialize($in['in_metadata']);
        if(!isset($in_metadata['in__metadata_common_steps'])){
            //Since it's not there yet we assume the idea it self only!
            $in_metadata['in__metadata_common_steps'] = array($in['in_id']);
        }


        //Generate flat steps:
        $flat_common_steps = array_flatten($in_metadata['in__metadata_common_steps']);


        //Count totals:
        $common_totals = $this->IDEA_model->fetch(array(
            'in_id IN ('.join(',',$flat_common_steps).')' => null,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), 0, 0, array(), 'COUNT(in_id) as total_steps, SUM(in_time_seconds) as total_seconds');


        //Count completed for user:
        $common_completed = $this->TRANSACTION_model->fetch(array(
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12229')) . ')' => null, //READ COMPLETE
            'ln_creator_source_id' => $en_id, //Belongs to this User
            'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0, 0, array(), 'COUNT(in_id) as completed_steps, SUM(in_time_seconds) as completed_seconds');


        //Calculate common steps and expansion steps recursively for this user:
        $metadata_this = array(
            'steps_total' => intval($common_totals[0]['total_steps']),
            'steps_completed' => intval($common_completed[0]['completed_steps']),
            'seconds_total' => intval($common_totals[0]['total_seconds']),
            'seconds_completed' => intval($common_completed[0]['completed_seconds']),
        );


        //Expansion Answer ONE
        $answer_array = array();
        if(isset($in_metadata['in__metadata_expansion_steps']) && count($in_metadata['in__metadata_expansion_steps']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($in_metadata['in__metadata_expansion_steps']));
        }
        if(isset($in_metadata['in__metadata_expansion_some']) && count($in_metadata['in__metadata_expansion_some']) > 0) {
            $answer_array = array_merge($answer_array , array_flatten($in_metadata['in__metadata_expansion_some']));
        }

        if(count($answer_array)){

            //Now let's check user answers to see what they have done:
            foreach($this->TRANSACTION_model->fetch(array(
                'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12326')) . ')' => null, //READ IDEA LINKS
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_next_idea_id IN (' . join(',', $answer_array) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->READ_model->completion_progress($en_id, $expansion_in, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];
            }
        }


        //Expansion steps Recursive
        if(isset($in_metadata['in__metadata_expansion_conditional']) && count($in_metadata['in__metadata_expansion_conditional']) > 0){

            //Now let's check if user has unlocked any Miletones:
            foreach($this->TRANSACTION_model->fetch(array(
                'ln_type_source_id' => 6140, //READ UNLOCK LINK
                'ln_creator_source_id' => $en_id, //Belongs to this User
                'ln_previous_idea_id IN (' . join(',', $flat_common_steps ) . ')' => null,
                'ln_next_idea_id IN (' . join(',', array_flatten($in_metadata['in__metadata_expansion_conditional'])) . ')' => null,
                'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
                'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
            ), array('in_next')) as $expansion_in) {

                //Fetch recursive:
                $recursive_stats = $this->READ_model->completion_progress($en_id, $expansion_in, false);

                //Addup completion stats for this:
                $metadata_this['steps_total'] += $recursive_stats['steps_total'];
                $metadata_this['steps_completed'] += $recursive_stats['steps_completed'];
                $metadata_this['seconds_total'] += $recursive_stats['seconds_total'];
                $metadata_this['seconds_completed'] += $recursive_stats['seconds_completed'];

            }
        }


        if($top_level){

            /*
             *
             * Completing an Reads depends on two factors:
             *
             * 1) number of steps (some may have 0 time estimate)
             * 2) estimated seconds (usual ly accurate)
             *
             * To increase the accurate of our completion % function,
             * We would also assign a default time to the average step
             * so we can calculate more accurately even if none of the
             * steps have an estimated time.
             *
             * */

            //Set default seconds per step:
            $metadata_this['completion_percentage'] = 0;
            $step_default_seconds = config_var(12176);


            //Calculate completion rate based on estimated time cost:
            if($metadata_this['steps_total'] > 0 || $metadata_this['seconds_total'] > 0){
                $metadata_this['completion_percentage'] = intval(ceil( ($metadata_this['seconds_completed']+($step_default_seconds*$metadata_this['steps_completed'])) / ($metadata_this['seconds_total']+($step_default_seconds*$metadata_this['steps_total'])) * 100 ));
            }


            //Hack for now, investigate later:
            if($metadata_this['completion_percentage'] > 100){
                $metadata_this['completion_percentage'] = 100;
            }

        }

        //Return results:
        return $metadata_this;

    }


    function ids($en_id){
        //Simply returns all the idea IDs for a user's Reads:
        $player_read_ids = array();
        foreach($this->TRANSACTION_model->fetch(array(
            'ln_creator_source_id' => $en_id,
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_12969')) . ')' => null, //Reads Idea Set
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ), array('in_previous'), 0) as $user_in){
            array_push($player_read_ids, intval($user_in['in_id']));
        }
        return $player_read_ids;
    }




    function answer($en_id, $question_in_id, $answer_in_ids){

        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $question_in_id,
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //PUBLIC
        ));
        $ens = $this->SOURCE_model->fetch(array(
            'en_id' => $en_id,
            'en_status_source_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //PUBLIC
        ));
        if (!count($ins)) {
            return array(
                'status' => 0,
                'message' => 'Invalid idea ID',
            );
        } elseif (!count($ens)) {
            return array(
                'status' => 0,
                'message' => 'Invalid source ID',
            );
        } elseif (!in_array($ins[0]['in_type_source_id'], $this->config->item('en_ids_7712'))) {
            return array(
                'status' => 0,
                'message' => 'Invalid Idea type [Must be Answer]',
            );
        } elseif (!count($answer_in_ids)) {
            return array(
                'status' => 0,
                'message' => 'Missing Answer',
            );
        }


        //Define completion links for each answer:
        if($ins[0]['in_type_source_id'] == 6684){

            //ONE ANSWER
            $ln_type_source_id = 6157; //Award Coin
            $in_link_type_id = 12336; //Save Answer

        } elseif($ins[0]['in_type_source_id'] == 7231){

            //SOME ANSWERS
            $ln_type_source_id = 7489; //Award Coin
            $in_link_type_id = 12334; //Save Answer

        }

        //Delete ALL previous answers:
        foreach($this->TRANSACTION_model->fetch(array(
            'ln_status_source_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $this->config->item('en_ids_7704')) . ')' => null, //READ ANSWERED
            'ln_creator_source_id' => $en_id,
            'ln_previous_idea_id' => $ins[0]['in_id'],
        )) as $read_progress){
            $this->TRANSACTION_model->update($read_progress['ln_id'], array(
                'ln_status_source_id' => 6173, //Link Deleted
            ), $en_id, 12129 /* READ ANSWER DELETED */);
        }

        //Add New Answers
        $answers_newly_added = 0;
        foreach($answer_in_ids as $answer_in_id){
            $answers_newly_added++;
            $this->TRANSACTION_model->create(array(
                'ln_type_source_id' => $in_link_type_id,
                'ln_creator_source_id' => $en_id,
                'ln_previous_idea_id' => $ins[0]['in_id'],
                'ln_next_idea_id' => $answer_in_id,
            ));
        }


        //Ensure we logged an answer:
        if(!$answers_newly_added){
            return array(
                'status' => 0,
                'message' => 'No answers saved.',
            );
        }

        //Issue READ/IDEA COIN:
        $this->READ_model->is_complete($ins[0], array(
            'ln_type_source_id' => $ln_type_source_id,
            'ln_creator_source_id' => $en_id,
            'ln_previous_idea_id' => $ins[0]['in_id'],
        ));

        //All good, something happened:
        return array(
            'status' => 1,
            'message' => $answers_newly_added.' Selected. Going Next...',
        );

    }



}