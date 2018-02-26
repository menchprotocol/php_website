<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_model extends CI_Model {
		
	function __construct() {
		parent::__construct();
	}






	//Entire Model To be DEPRECATED soon in favour of fb_graph() helper function...







    function send_message($botkey,$payload){
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



    function batch_messages( $botkey , $u_fb_id , $messages , $notification_type='REGULAR'){

        $mench_bots = $this->config->item('mench_bots');
        if(!array_key_exists($botkey,$mench_bots)){
            die('Invalid Bot Key');
        } elseif(!in_array(strtoupper($notification_type),array('REGULAR','SILENT_PUSH','NO_PUSH'))){
            die('Invalid notification type');
        }

        $stats = array();
        foreach($messages as $count=>$message){

            //Send the real message:
            $result = $this->Facebook_model->send_message( $botkey , array(
                'recipient' => array(
                    'id' => $u_fb_id,
                ),
                'message' => $message,
                'notification_type' => $notification_type,
            ));

            array_push($stats,$result);
        }

        return $stats;
    }


    function save_attachment($botkey,$type,$url){

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


}