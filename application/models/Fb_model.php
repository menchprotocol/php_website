<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Fb_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function fetch_fb_profile($fp_id,$u_id,$psid_sender_id,$fp_access_token=null){

        if(!$fp_access_token){
            //Fetch from DB:
            $pages = $this->Db_model->fp_fetch(array(
                'fp_id' => $fp_id,
            ));
            if(isset($pages[0]['fp_access_token']) && strlen($pages[0]['fp_access_token'])>0){
                $fp_access_token = $pages[0]['fp_access_token'];
            } else {
                return false; //Should not happen!
            }
        }

        $ch = curl_init('https://graph.facebook.com/v2.6/'.$psid_sender_id.'?access_token='.$fp_access_token);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => TRUE,
        ));

        $fb_profile = objectToArray(json_decode(curl_exec($ch)));

        if(!isset($fb_profile['first_name'])){

            //Failed to fetch this profile:
            $this->Db_model->e_create(array(
                'e_recipient_u_id' => $u_id,
                'e_message' => 'fetch_fb_profile() failed to fetch user profile for Facebook PSID ['.$psid_sender_id.']',
                'e_json' => $fb_profile,
                'e_type_id' => 8, //Platform Error
                'e_fp_id' => $fp_id,
            ));

            //There was an issue accessing this on FB
            return false;

        } else {
            return $fb_profile;
        }
    }



    function fetch_fb_settings($fp_id){

        //Fetch from DB:
        $pages = $this->Db_model->fp_fetch(array(
            'fp_id' => $fp_id,
        ));
        if(isset($pages[0]['fp_access_token']) && strlen($pages[0]['fp_access_token'])>0){
            $fp_access_token = $pages[0]['fp_access_token'];
        } else {
            return false; //Should not happen!
        }

        $ch = curl_init('https://graph.facebook.com/v2.6/me/messenger_profile?fields=persistent_menu,get_started,greeting,whitelisted_domains&access_token='.$fp_access_token);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => TRUE,
        ));

        $fb_settings = objectToArray(json_decode(curl_exec($ch)));

        if(!$fb_settings){

            //Failed to fetch this profile:
            $this->Db_model->e_create(array(
                'e_message' => 'fetch_fb_settings() failed to fetch page settings',
                'e_type_id' => 8, //Platform Error
                'e_fp_id' => $fp_id,
            ));

            //There was an issue accessing this on FB
            return false;

        } else {
            return $fb_settings;
        }
    }




    function set_fb_settings($fp_id,$remove_settings=false){

        //Fetch from DB:
        $pages = $this->Db_model->fp_fetch(array(
            'fp_id' => $fp_id,
        ));
        if(isset($pages[0]['fp_access_token']) && strlen($pages[0]['fp_access_token'])>0){
            $fp_access_token = $pages[0]['fp_access_token'];
        } else {
            return false; //Should not happen!
        }

        //The basic setting array:
        $setting = array(
            'get_started' => array(
                'payload' => 'GET_STARTED',
            ),
        );

        if(!$remove_settings){

            //Whitelist mench:
            $setting['whitelisted_domains'] = array(
                'http://local.mench.co',
                'https://mench.co',
                //'https://mench.com', //To be added soon
            );

            //Add persistent menu:
            /*
            $setting['persistent_menu'] = array(
                array(
                    'locale' => 'default',
                    'composer_input_disabled' => false,
                    'call_to_actions' => array(
                        array(
                            'title' => '🚩 Action Plan',
                            'type' => 'web_url',
                            'url' => 'https://mench.co/my/actionplan',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                        array(
                            'title' => '👥 Classmates',
                            'type' => 'web_url',
                            'url' => 'https://mench.co/my/classmates',
                            'webview_height_ratio' => 'tall',
                            'webview_share_button' => 'hide',
                            'messenger_extensions' => true,
                        ),
                    ),
                ),
            );

            //Do we have a custom greeting?
            if(strlen($pages[0]['fp_greeting'])>0){
                $setting['greeting'] = array(
                    array(
                        'locale' => 'default',
                        'text' => $pages[0]['fp_greeting'], //If any
                    ),
                );
            }
            */

        }

        //Make the call for add/update
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messenger_profile?access_token='.$fp_access_token);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
            //Set our standard menu:
            CURLOPT_POSTFIELDS => json_encode($setting)
        ));

        // Send the request
        $response = curl_exec($ch);

        // Check for CURL errors
        if($response === FALSE){

            $this->Db_model->e_create(array(
                'e_message' => 'set_fb_settings() failed to update the settings',
                'e_json' => $setting,
                'e_type_id' => 8, //Platform Error
                'e_fp_id' => $fp_id,
            ));

            return false;

        } else {

            return objectToArray(json_decode($response));

        }
    }



    //TODO Adjust the 3 functions below with new system


    //This is a fancier way to send messages that feels more human:
    function batch_fb_messages( $botkey , $u_fb_id , $messages , $notification_type='REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/){

        $mench_bots = $this->config->item('mench_bots');
        if(!array_key_exists($botkey,$mench_bots)){
            die('Invalid Bot Key');
        } elseif(!in_array(strtoupper($notification_type),array('REGULAR','SILENT_PUSH','NO_PUSH'))){
            die('Invalid notification type');
        }

        foreach($messages as $count=>$message){

            /*
            $this->Facebook_model->send_message( $botkey , array(
                'recipient' => array(
                    'id' => $u_fb_id,
                ),
                'sender_action' => 'typing_on'
            ));

            //To have them see the typing...
            sleep(rand(0,3)); //This is risky as it delays processors and can cause issues if the user types in something twice for example...
            */

            //Send the real message:
            $this->Facebook_model->send_message( $botkey , array(
                'recipient' => array(
                    'id' => $u_fb_id,
                ),
                'message' => $message,
                'notification_type' => $notification_type,
            ));
        }
    }


    function save_fb_attachment($botkey,$type,$url){

        $mench_bots = $this->config->item('mench_bots');
        if(!array_key_exists($botkey,$mench_bots)){
            die('Invalid Bot Key');
        }

        $payload = array(
            'message' => array(
                'attachment' => array(
                    'type' => $type,
                    'payload' => array(
                        'is_reusable' => true,
                        'url' => $url,
                    ),
                ),
            )
        );

        //Make the call for add/update
        $ch = curl_init('https://graph.facebook.com/v2.6/me/message_attachments?access_token='.$mench_bots[$botkey]['access_token']);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
            CURLOPT_POSTFIELDS => json_encode($payload),
        ));

        // Send the request
        $response = curl_exec($ch);

        // Check for CURL errors
        if($response === FALSE){
            $this->Db_model->e_create(array(
                'e_message' => 'save_attachment() CURL Failed in sending message via ['.$botkey.'] Messenger.',
                'e_json' => $payload,
                'e_type_id' => 8, //Platform Error
            ));
        }

        return objectToArray(json_decode($response));
    }


    function send_fb_message($botkey,$payload){

        $mench_bots = $this->config->item('mench_bots');
        if(!array_key_exists($botkey,$mench_bots)){
            die('Invalid Bot Key');
        }

        //Make the call for add/update
        $ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token='.$mench_bots[$botkey]['access_token']);
        curl_setopt_array($ch, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=utf-8'
            ),
            CURLOPT_POSTFIELDS => json_encode($payload)
        ));

        // Send the request
        $response = curl_exec($ch);

        // Check for CURL errors
        if($response === FALSE){
            $this->Db_model->e_create(array(
                'e_message' => 'send_message() CURL Failed in sending message via ['.$botkey.'] Messenger.',
                'e_json' => $payload,
                'e_type_id' => 8, //Platform Error
            ));
        }

        return objectToArray(json_decode($response));
    }


}