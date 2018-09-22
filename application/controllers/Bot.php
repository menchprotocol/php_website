<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {

    function __construct() {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }


    function a(){
        echo_json($this->Db_model->c_recursive_fetch(6898));
    }


    function url($smallest_i_id=0,$limit=1){

        //Show custom input
        echo '<div><form action=""><input type="url" name="url" value="'.@$_GET['url'].'" style="width:400px;"> <input type="submit" value="Go"></form></div>';

        if(isset($_GET['url'])){

            $curl = curl_html($_GET['url'],true);
            foreach($curl as $key=>$value){
                echo '<div style="color:'.( $key=='is_broken_link' && intval($value) ? '#FF0000' : '#000000' ).';">'.$key.': <b>'.$value.'</b></div>';
            }

        } else {

            boost_power();

            $content = $this->Db_model->i_fetch(array(
                'i_id >' => $smallest_i_id, //Published in any form
                'i_status >' => 0, //Published in any form
                'i_media_type' => 'text',
                'LENGTH(i_url)>0' => null, //Entire Bootcamp Action Plan
            ), $limit, array(), array(
                'i_id' => 'ASC',
            ));

            foreach($content as $key=>$i){

                $curl = curl_html($i['i_url'],true);

                echo '<div style="color:'.( $curl['is_broken_link'] ? '#FF0000' : '#000000' ).';">#'.($key+1).' ['.$curl['httpcode'].'] id='.$i['i_id'].' <a href="'.$i['i_url'].'" target="_blank">'.( strlen($curl['page_title'])>0 ? $curl['page_title'] : $i['i_url'] ).'</a>'.( $curl['clean_url'] ? ' ====> <a href="'.$curl['clean_url'].'" target="_blank">'.$curl['clean_url'].'</a>' : '' ).' ['.$curl['last_domain'].']</div>';

            }
        }

    }



    function update_all($fp_id){

        $pages = $this->Db_model->fp_fetch(array(
            'fp_id' => $fp_id,
        ), array('fs'));

        $res = array();
        foreach($pages as $fp){

            array_push($res , $this->Comm_model->fb_graph($fp['fp_id'], 'POST', '/me/messenger_profile', array(
                'get_started' => array(
                    'payload' => 'GET_STARTED',
                ),
                'whitelisted_domains' => array(
                    'http://local.mench.co',
                    'https://mench.co',
                    'https://mench.com',
                ),
            ), $fp));


            //Wait until Facebook pro-pagates changes of our whitelisted_domains setting:
            sleep(2);


            array_push($res , $this->Comm_model->fb_graph($fp['fp_id'], 'POST', '/me/messenger_profile', array(
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
            ), $fp));
        }

        echo_json($res);
    }


    function facebook_webhook(){

        /*
         *
         * The master function for all inbound Facebook calls
         *
         * */

        //Facebook Webhook Authentication:
        $challenge = ( isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : null );
        $verify_token = ( isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : null );

        if ($verify_token == '722bb4e2bac428aa697cc97a605b2c5a') {
            echo $challenge;
        }

        //Fetch input data:
        $json_data = json_decode(file_get_contents('php://input'), true);
        //This is for local testing only:
        //$json_data = objectToArray(json_decode('{"object":"page","entry":[{"id":"381488558920384","time":1505007977668,"messaging":[{"sender":{"id":"1443101719058431"},"recipient":{"id":"381488558920384"},"timestamp":1505007977521,"message":{"mid":"mid.$cAAFa9hmVoehkmryMMVeaXdGIY9x5","seq":19898,"text":"Yes"}}]}]}'));


        //Do some basic checks:
        if(!isset($json_data['object']) || !isset($json_data['entry'])){
            $this->Db_model->e_create(array(
                'e_text_value' => 'facebook_webhook() Function missing either [object] or [entry] variable.',
                'e_json' => $json_data,
                'e_inbound_c_id' => 8, //Platform Error
            ));
            return false;
        } elseif(!$json_data['object']=='page'){
            $this->Db_model->e_create(array(
                'e_text_value' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
                'e_json' => $json_data,
                'e_inbound_c_id' => 8, //Platform Error
            ));
            return false;
        }


        //Loop through entries:
        foreach($json_data['entry'] as $entry){

            //Validate the originating page to ensure its a valid Page connected to Mench
            if(isset($entry['id'])){
                $fp_pages = $this->Db_model->fp_fetch(array(
                    'fp_fb_id' => $entry['id'],
                    'fp_status' => 1, //Must be connected to Mench
                ), array('fs'));
            }

            //check the page ID:
            if(!isset($fp_pages) || count($fp_pages)<1){
                $this->Db_model->e_create(array(
                    'e_text_value' => 'facebook_webhook() received message from unknown Page ID ['.$entry['id'].']',
                    'e_json' => $json_data,
                    'e_inbound_c_id' => 8, //Platform Error
                ));
                continue;
            } elseif(!isset($entry['messaging'])){
                $this->Db_model->e_create(array(
                    'e_text_value' => 'facebook_webhook() call missing messaging Array().',
                    'e_json' => $json_data,
                    'e_inbound_c_id' => 8, //Platform Error
                    'e_fp_id' => $fp_pages[0]['fp_id'],
                ));
                continue;
            }

            //loop though the messages:
            foreach($entry['messaging'] as $im){

                if(isset($im['read'])){

                    //Add delay to prevent concurrent request issues
                    sleep(2);

                    $id_user = $this->Comm_model->fb_identify_activate($fp_pages[0],$im['sender']['id']);

                    //This callback will occur when a message a page has sent has been read by the user.
                    $this->Db_model->e_create(array(
                        'e_json' => $json_data,
                        'e_inbound_c_id' => 1, //Message Read
                        'e_fp_id' => $fp_pages[0]['fp_id'],
                        'e_inbound_u_id' => ( isset($id_user['u_id']) ? $id_user['u_id'] : 0 ),
                        'e_b_id' => ( isset($id_user['r_b_id']) ? $id_user['r_b_id'] : 0 ),
                        'e_r_id' => ( isset($id_user['r_id']) ? $id_user['r_id'] : 0 ),
                    ));

                } elseif(isset($im['delivery'])) {

                    //Add delay to prevent concurrent request issues
                    sleep(2);

                    $id_user = $this->Comm_model->fb_identify_activate($fp_pages[0],$im['sender']['id']);

                    //This callback will occur when a message a page has sent has been delivered.
                    $this->Db_model->e_create(array(
                        'e_json' => $json_data,
                        'e_inbound_c_id' => 2, //Message Delivered
                        'e_fp_id' => $fp_pages[0]['fp_id'],
                        'e_inbound_u_id' => ( isset($id_user['u_id']) ? $id_user['u_id'] : 0 ),
                        'e_b_id' => ( isset($id_user['r_b_id']) ? $id_user['r_b_id'] : 0 ),
                        'e_r_id' => ( isset($id_user['r_id']) ? $id_user['r_id'] : 0 ),
                    ));

                } elseif(isset($im['referral']) || isset($im['postback'])) {

                    /*
                     * Simple difference:
                     *
                     * Handle the messaging_postbacks event for new conversations
                     * Handle the messaging_referrals event for existing conversations
                     *
                     * */

                    if(isset($im['postback'])) {

                        //The payload field passed is defined in the above places.
                        $payload = $im['postback']['payload']; //Maybe do something with this later?

                        if(isset($im['postback']['referral'])){

                            $referral_array = $im['postback']['referral'];

                        } else {
                            //Postback without referral!
                            $referral_array = null;
                        }

                    } elseif(isset($im['referral'])) {

                        $referral_array = $im['referral'];

                    }

                    //Did we have a ref from Messenger?
                    $ref = ( $referral_array && isset($referral_array['ref']) && strlen($referral_array['ref'])>0 ? $referral_array['ref'] : null );
                    $id_user = $this->Comm_model->fb_identify_activate($fp_pages[0],$im['sender']['id'],$ref);

                    $eng_data = array(
                        'e_inbound_c_id' => (isset($im['referral']) ? 4 : 3), //Messenger Referral/Postback
                        'e_json' => $json_data,
                        'e_fp_id' => $fp_pages[0]['fp_id'],
                        'e_inbound_u_id' => ( isset($id_user['u_id']) ? $id_user['u_id'] : 0 ),
                        'e_b_id' => ( isset($id_user['r_b_id']) ? $id_user['r_b_id'] : 0 ),
                        'e_r_id' => ( isset($id_user['r_id']) ? $id_user['r_id'] : 0 ),
                    );

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

                    if($eng_data['e_inbound_u_id']){
                        //See if this student has any enrollments:
                        $enrollments = $this->Db_model->ru_fetch(array(
                            'r.r_status >='	   => 1, //Open for enrollment
                            'r.r_status <='	   => 2, //Running
                            'ru.ru_status >='  => 0, //Initiated or higher as long as Bootcamp is running!
                            'ru.ru_outbound_u_id'	   => $eng_data['e_inbound_u_id'],
                        ));
                        if(count($enrollments)>0){
                            //Append Bootcamp & Class ID to engagement:
                            $eng_data['e_b_id'] = $enrollments[0]['r_b_id'];
                            $eng_data['e_r_id'] = $enrollments[0]['r_id'];
                        }
                    }

                    //Log primary engagement:
                    $this->Db_model->e_create($eng_data);

                } elseif(isset($im['optin'])) {

                    //Add delay to prevent concurrent request issues
                    sleep(2);

                    $id_user = $this->Comm_model->fb_identify_activate($fp_pages[0],$im['sender']['id']);

                    //Note: Never seen this happen yet!
                    //Log engagement:
                    $this->Db_model->e_create(array(
                        'e_json' => $json_data,
                        'e_inbound_c_id' => 5, //Messenger Optin
                        'e_fp_id' => $fp_pages[0]['fp_id'],
                        'e_inbound_u_id' => ( isset($id_user['u_id']) ? $id_user['u_id'] : 0 ),
                        'e_b_id' => ( isset($id_user['r_b_id']) ? $id_user['r_b_id'] : 0 ),
                        'e_r_id' => ( isset($id_user['r_id']) ? $id_user['r_id'] : 0 ),
                    ));

                } elseif(isset($im['message_request']) && $im['message_request']=='accept') {

                    //This is when we message them and they accept to chat because they had deleted Messenger or something...
                    //TODO maybe later log an engagement

                } elseif(isset($im['message'])) {

                    /*
                     * Triggered for both incoming and outgoing messages on behalf of our team
                     *
                     * */


                    //Is this a non loggable inbound message? If so, this has already been logged by Mench:
                    $metadata = ( isset($im['message']['metadata']) ? $im['message']['metadata'] : null ); //Send API custom string [metadata field]
                    if($metadata=='system_logged'){
                        //This is already logged! No need to take further action!
                        echo_json(array('complete'=>'yes'));
                        return false;
                        exit;
                    }


                    //Set variables:
                    $sent_from_us = ( isset($im['message']['is_echo']) ); //Indicates the message sent from the page itself
                    $user_id = ( $sent_from_us ? $im['recipient']['id'] : $im['sender']['id'] );
                    $id_user = $this->Comm_model->fb_identify_activate($fp_pages[0],$user_id);

                    $quick_reply_payload = ( isset($im['message']['quick_reply']['payload']) && strlen($im['message']['quick_reply']['payload'])>0 ? $im['message']['quick_reply']['payload'] : null );

                    $eng_data = array(
                        'e_inbound_u_id' => ( $sent_from_us || !isset($id_user['u_id']) ? 0 : $id_user['u_id'] ),
                        'e_json' => $json_data,
                        'e_text_value' => ( isset($im['message']['text']) ? $im['message']['text'] : null ),
                        'e_inbound_c_id' => ( $sent_from_us ? 7 : 6 ), //Message Sent/Received
                        'e_outbound_u_id' => ( $sent_from_us && isset($id_user['u_id']) ? $id_user['u_id'] : 0 ),
                        'e_b_id' => ( isset($id_user['r_b_id']) ? $id_user['r_b_id'] : 0 ),
                        'e_r_id' => ( isset($id_user['r_id']) ? $id_user['r_id'] : 0 ),
                        'e_fp_id' => $fp_pages[0]['fp_id'],
                    );

                    //It may also have an attachment
                    if(isset($im['message']['attachments'])){
                        //We have some attachments, lets loops through them:
                        foreach($im['message']['attachments'] as $att){

                            if(in_array($att['type'],array('image','audio','video','file'))){

                                //Indicate that we need to save this file on our servers:
                                $eng_data['e_status'] = 0;
                                //We do not save instantly as we need to respond to facebook's webhook call ASAP or else FB resend attachment!

                            } elseif($att['type']=='location'){

                                //Message with location attachment
                                //TODO test to make sure this works!
                                $loc_lat = $att['payload']['coordinates']['lat'];
                                $loc_long = $att['payload']['coordinates']['long'];
                                $eng_data['e_text_value'] .= (strlen($eng_data['e_text_value'])>0?"\n\n":'').'/attach location:'.$loc_lat.','.$loc_long;

                            } elseif($att['type']=='template'){

                                //Message with template attachment, like a button or something...
                                $template_type = $att['payload']['template_type'];

                            } elseif($att['type']=='fallback'){

                                //A fallback attachment is any attachment not currently recognized or supported by the Message Echo feature.
                                //We can ignore them for now :)

                            } else {
                                //This should really not happen!
                                $this->Db_model->e_create(array(
                                    'e_text_value' => 'facebook_webhook() Received message with unknown attachment type ['.$att['type'].'].',
                                    'e_json' => $json_data,
                                    'e_inbound_c_id' => 8, //Platform Error
                                    'e_fp_id' => $fp_pages[0]['fp_id'],
                                    'e_outbound_u_id' => $eng_data['e_outbound_u_id'],
                                    'e_b_id' => $eng_data['e_b_id'],
                                    'e_r_id' => $eng_data['e_r_id'],
                                ));
                            }
                        }
                    }

                    //Log incoming engagement:
                    $this->Db_model->e_create($eng_data);

                } else {

                    //This should really not happen!
                    $this->Db_model->e_create(array(
                        'e_text_value' => 'facebook_webhook() received unrecognized webhook call.',
                        'e_json' => $json_data,
                        'e_inbound_c_id' => 8, //Platform Error
                        'e_fp_id' => $fp_pages[0]['fp_id'],
                    ));

                }
            }
        }
    }

    function paypal_webhook(){

        //Called when the paypal payment is complete:
        if(isset($_POST) && isset($_POST['payment_status']) && $_POST['payment_status']=='Completed' && isset($_POST['item_number']) && intval($_POST['item_number'])>0){

            //Seems like a valid Paypal IPN Call:
            //Fetch Subscription row:
            $enrollments = $this->Db_model->ru_fetch(array(
                'ru.ru_id' => intval($_POST['item_number']),
            ));

            if(count($enrollments)==1){

                $payment_received = doubleval(( $_POST['payment_gross']>$_POST['mc_gross'] ? $_POST['payment_gross'] : $_POST['mc_gross'] ));

                //Save this new transaction:
                $transaction = $this->Db_model->t_create(array(
                    't_ru_id' => $enrollments[0]['ru_id'],
                    't_r_id' => $enrollments[0]['ru_r_id'],
                    't_b_id' => $enrollments[0]['ru_b_id'],
                    't_inbound_u_id' => $enrollments[0]['ru_outbound_u_id'],
                    't_status' => 1, //Payment received from Student
                    't_timestamp' => date("Y-m-d H:i:s"),
                    't_paypal_id' => $_POST['txn_id'],
                    't_paypal_ipn' => json_encode($_POST),
                    't_currency' => $_POST['mc_currency'],
                    't_payment_type' => $_POST['payment_type'],
                    't_total' => $payment_received,
                    't_fees' => doubleval(( $_POST['payment_fee']>$_POST['mc_fee'] ? $_POST['payment_fee'] : $_POST['mc_fee'] )),
                ));

                if($payment_received>=$enrollments[0]['ru_upfront_pay']){

                    //Finalize their Subscription:
                    $this->Db_model->ru_finalize($enrollments[0]['ru_id']);

                } else {

                    //Should not happen, log error:
                    $this->Db_model->e_create(array(
                        'e_text_value' => 'paypal_webhook() received a partial payment form the student which is not allowed.',
                        'e_json' => $_POST,
                        'e_inbound_c_id' => 8,
                    ));

                }

            }
        }
    }

    function deauthorize(){
        //Called when someone de-authorizes our page
        $this->Db_model->e_create(array(
            'e_text_value' => 'deauthorize() was called because coach revoked some/all permission. Look at e_json log file for more information.',
            'e_json' => array(
                'POST' => $_POST,
                'parse_signed_request' => parse_signed_request($_POST['signed_request']),
            ),
            'e_inbound_c_id' => 84, //Facebook Permission Deauthorized
        ));
    }

}