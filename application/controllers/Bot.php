<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {

    function __construct() {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }

    function a(){

        $ids = '7015,
6976,
4174,
6977,
6933,
6934,
6935,
6936,
6899,
6900,
6901,
6903,
6698,
6898,
6681,
3493,
6927,
6686,
6716,
6687,
6688,
6685,
6717,
6701,
6700,
6699,
6689,
6690,
6695,
6696,
7032,
7033,
6672,
6706,
6904,
6715,
6705,
6707,
6708,
6709,
6710,
6712,
6713,
6714,
6702,
6703,
6704,
6673,
6779,
6778,
6780,
6835,
6836,
6837,
6838,
6839,
6840,
6841,
6842,
6843,
6677,
6770,
6769,
6768,
6771,
6772,
6774,
6773,
6775,
6674,
6814,
6908,
6815,
6816,
6817,
6818,
6819,
6820,
6821,
6822,
6823,
6824,
6825,
6826,
6827,
6828,
6829,
6830,
6922,
6813,
6880,
6881,
6902,
6781,
6782,
6783,
6784,
6785,
6786,
6787,
6788,
6789,
6790,
6791,
6792,
6793,
6794,
6795,
6796,
6797,
6798,
6799,
6800,
6801,
6802,
6803,
6804,
6805,
6806,
6807,
6808,
6809,
6810,
6811,
6812,
6676,
6623,
7241,
5892,
6050,
6051,
6052,
6053,
6054,
6055,
6047,
6681,
3493,
6927,
6686,
6716,
6687,
6688,
6685,
6717,
6701,
6700,
6699,
6689,
6690,
6695,
6696,
7032,
7033,
6672,
6880,
6881,
6902,
6781,
6782,
6783,
6784,
6785,
6786,
6787,
6788,
6789,
6790,
6791,
6792,
6793,
6794,
6795,
6796,
6797,
6798,
6799,
6800,
6801,
6802,
6803,
6804,
6805,
6806,
6807,
6808,
6809,
6810,
6811,
6812,
6676,
6814,
6908,
6815,
6816,
6817,
6818,
6819,
6820,
6821,
6822,
6823,
6824,
6825,
6826,
6827,
6828,
6829,
6830,
6922,
6813,
7015,
6976,
4174,
6977,
6933,
6934,
6935,
6936,
6899,
6900,
6901,
6903,
6698,
6898,
7243,
4027,
4026,
2086,
1936,
2093,
2094,
2439,
3026,
3277,
6625,
1935,
926,
918,
921,
923,
920,
3358,
3136,
3137,
3138,
3139,
2691,
5441,
4632,
4630,
3274,
3275,
3273,
2697,
3127,
3140,
3016,
2698,
2800,
2801,
3030,
2696,
2805,
3135,
3120,
3133,
3134,
3132,
5987,
3260,
3261,
3262,
3263,
3264,
3265,
3266,
3259,
6627,
6628,
6629,
6630,
6631,
6626,
590,
46,
45,
47,
25,
17,
15,
53,
67,
68,
18,
16,
57,
69,
70,
14,
56,
88,
24,
60,
87,
86,
76,
81,
82,
73,
74,
80,
79,
78,
77,
84,
75,
40,
21,
20,
89,
23,
50,
22,
19,
35,
85,
34,
2,
41,
1,
6,
52,
7,
39,
83,
36,
31,
5,
3,
4,
30,
29,
66,
71,
64,
32,
54,
55,
65,
72,
33,
8,
28,
27,
10,
11,
9,
48,
59,
12,
6910,
6911,
6909,
6912,
6913,
7001,
6923,
6924,
6966,
6971,
6978,
6997,
7034,
7084,
7093,
7098,
6653,
2273,
2377,
3027,
2583,
2585,
2586,
6024,
1511,
598,
379,
718,
725,
3531,
4868,
4869,
4789,
4867,
615,
4792,
4791,
4790,
617,
627,
629,
6964,
7019,
7097,
434,
624,
607,
606,
7100,
7101,
7102,
592,
2272,
2271,
2555,
610,
435,
2354,
2329,
599,
602,
4997,
2284,
608,
605,
609,
3267,
2982,
7149,
2088,
2587,
2097,
2584,
3342,
2578,
2579,
2577,
6023,
6980,
6776,
6777,
369,
7248,
7240';

        $ids_array = explode();

        echo_json($this->Db_model->c_recursive_fetch(7240,1));
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
                            array(
                                'title' => '👥 Classroom',
                                'type' => 'web_url',
                                'url' => 'https://mench.com/my/classmates',
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

    function s(){
        echo $_SERVER['SERVER_NAME'];
        echo '<br />'.$_SERVER['REQUEST_URI'];
    }

    function error(){
        //This is meant to create an error to rest the log files:
        echo $_GET['none'];
    }

    function json(){
        echo_json(fetch_action_plan_copy(21,196));
    }

    function m1(){
        echo_json($this->Comm_model->foundation_message(array(
            'e_outbound_u_id' => 1,
            'e_outbound_c_id' => 923,
            'depth' => 0,
        )));
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