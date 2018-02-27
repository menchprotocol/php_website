<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_model extends CI_Model {
		
	function __construct() {
		parent::__construct();
	}






    function fb_graph($fp_id,$action,$url,$payload=array(),$fp=null){

        if(!$fp){
            //Fetch from DB:
            $pages = $this->Db_model->fp_fetch(array(
                'fp_id' => $fp_id,
                'fp_status >=' => 0, //Available or Connected
                'fs_status' => 1, //Authorized Access
            ));
            if(!isset($pages[0]['fs_access_token']) || strlen($pages[0]['fs_access_token'])<1){
                return array(
                    'status' => 0,
                    'message' => 'invalid fp_id ['.$fp_id.']',
                );
            } else {
                $fp = $pages[0];
            }
        }

        if(!in_array($action,array('GET','POST','DELETE'))){
            return array(
                'status' => 0,
                'message' => '$action ['.$action.'] is invalid',
            );
        }

        //Make the graph call:
        $ch = curl_init('https://graph.facebook.com/v2.6/'.$url.( substr_count($url,'?')>0 ? '&' : '?' ).'access_token='.$fp['fs_access_token']);

        //Base setting:
        $ch_setting = array(
            CURLOPT_CUSTOMREQUEST => $action,
            CURLOPT_RETURNTRANSFER => TRUE,
        );

        if(count($payload)>0){
            $ch_setting[CURLOPT_HTTPHEADER] = array('Content-Type: application/json; charset=utf-8');
            $ch_setting[CURLOPT_POSTFIELDS] = json_encode($payload);
        }

        //Apply settings:
        curl_setopt_array($ch, $ch_setting);

        //Process results and produce e_json
        $result = objectToArray(json_decode(curl_exec($ch)));
        $e_json = array(
            'fp' => $fp,
            'action' => $action,
            'payload' => $payload,
            'url' => $url,
            'result' => $result,
        );

        //Did we have any issues?
        if(!$result){

            //Failed to fetch this profile:
            $error_message = 'Facebook_model->fb_graph() failed to '.$action.' '.$url;
            $this->Db_model->e_create(array(
                'e_message' => $error_message,
                'e_type_id' => 8, //Platform Error
                'e_json' => $e_json,
                'e_fp_id' => $fp_id,
            ));

            //There was an issue accessing this on FB
            return array(
                'status' => 0,
                'message' => $error_message,
                'e_json' => $e_json,
            );

        } else {

            //All seems good, return:
            return array(
                'status' => 1,
                'message' => 'Success',
                'e_json' => $e_json,
            );

        }
    }

    function fb_index_pages($u_id,$access_token,$b_id){

        if($u_id<1 || strlen($access_token)<1){
            return false;
        }

        //Load Facebook PHP SDK:
        require_once( 'application/libraries/Facebook/autoload.php' );

        $fb = new \Facebook\Facebook([
            'app_id' => '1782431902047009',
            'app_secret' => '05aea76d11b062951b40a5bee4251620',
            'default_graph_version' => 'v2.10',
            'default_access_token' => $access_token,
        ]);

        try {

            //Fetch all Pages:
            $response = $fb->get('/me/accounts');

        } catch(FacebookExceptionsFacebookResponseException $e) {

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $u_id,
                'e_type_id' => 9, //Support needs attention
                'e_b_id' => $b_id,
                'e_message' => 'fb_index_pages() Graph call returned error: ' . $e->getMessage(),
            ));

            return false;

        } catch(FacebookExceptionsFacebookSDKException $e) {

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $u_id,
                'e_type_id' => 9, //Support needs attention
                'e_b_id' => $b_id,
                'e_message' => 'fb_index_pages() Facebook SDK call returned error: ' . $e->getMessage(),
            ));

            return false;

        }

        //All seems good:
        $facebookPages = $response->getGraphEdge();
        $authorized_fp_ids = array();

        //Now lets loop through their pages
        if(count($facebookPages)>0){

            foreach ($facebookPages as $fb_page) {

                //Check if we have this page in the database:
                $pages = $this->Db_model->fp_fetch(array(
                    'fp_fb_id' => $fb_page['id'],
                ));

                if(count($pages)==0){

                    //This is a new page, insert it:
                    $fp = $this->Db_model->fp_create(array(
                        'fp_fb_id' => $fb_page['id'],
                        'fp_name' => $fb_page['name'],
                        'fp_status' => 0, //Available... fb_page_connect() would change this to 1 later on when connected to a Bootcamp
                    ));

                    //Log Engagement:
                    $this->Db_model->e_create(array(
                        'e_initiator_u_id' => $u_id,
                        'e_fp_id' => $fp['fp_id'],
                        'e_b_id' => $b_id,
                        'e_type_id' => 76, //Facebook Page Added
                    ));

                    //Give admin the authorization to access it:
                    $fs = $this->Db_model->fs_create(array(
                        'fs_fp_id' => $fp['fp_id'],
                        'fs_u_id' => $u_id,
                        'fs_status' => 1, //Authorized
                        'fs_access_token' => $fb_page['access_token'],
                    ));

                    //Log Engagement:
                    $this->Db_model->e_create(array(
                        'e_initiator_u_id' => $u_id,
                        'e_fp_id' => $fp['fp_id'],
                        'e_b_id' => $b_id,
                        'e_type_id' => 82, //Facebook Page Access Authorized
                    ));

                } else {

                    //Page exists:
                    $fp = $pages[0];

                    //How many Bootcamps are using this page now?
                    $b_connected_count = count($this->Db_model->b_fetch(array(
                        'b_fp_id' => $fp['fp_id'],
                    )));
                    $target_fp_status = ( $b_connected_count>0 ? 1 : 0 ); //Either connected or Available

                    //Does this page need updating?
                    if(!($fb_page['name']==$fp['fp_name']) || !($fp['fp_status']==$target_fp_status)){

                        //Either name or Access token has changed, update:
                        $update_data = array(
                            'fp_name' => $fb_page['name'],
                            'fp_timestamp' => date("Y-m-d H:i:s"), //The most recent updated time
                            'fp_status' => $target_fp_status, //Available or Connected
                        );
                        $this->Db_model->fp_update( $fp['fp_id'], $update_data);

                        //Log engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => $u_id,
                            'e_json' => $update_data,
                            'e_fp_id' => $fp['fp_id'],
                            'e_b_id' => $b_id,
                            'e_type_id' => 77, //Facebook Page Update
                        ));

                    }

                    //Is instructor assigned as its admin?
                    $admin_pages = $this->Db_model->fp_fetch(array(
                        'fs_u_id' => $u_id,
                        'fs_fp_id' => $fp['fp_id'],
                    ));

                    if(count($admin_pages)==0){

                        //Give admin the authorization to access it:
                        $fs = $this->Db_model->fs_create(array(
                            'fs_fp_id' => $fp['fp_id'],
                            'fs_u_id' => $u_id,
                            'fs_status' => 1, //Authorized
                            'fs_access_token' => $fb_page['access_token'],
                        ));

                        //Log Engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => $u_id,
                            'e_fp_id' => $fp['fp_id'],
                            'e_b_id' => $b_id,
                            'e_type_id' => 82, //Facebook Page Access Authorized
                        ));

                    } else {

                        //yes, instructor is assigned as its admin
                        $fs = $admin_pages[0];

                        //Does it need updating?
                        if(!($fs['fs_access_token']==$fb_page['access_token']) || !($fs['fs_status']==1)){

                            //Update instructor admin status, as this happens frequently...
                            $this->Db_model->fs_update( $fs['fs_id'] , array(
                                'fs_access_token' => $fb_page['access_token'],
                                'fs_timestamp' => date("Y-m-d H:i:s"), //The most recent updated time
                                'fs_status' => 1, //Authorized
                            ));

                        }

                    }
                }

                //Now add this page to $authorized_fp_ids
                array_push($authorized_fp_ids,$fp['fp_id']);

            }
        }

        //Return the authorized pages:
        return $authorized_fp_ids;

    }

    function fb_revoke_access($u_id,$authorized_fp_ids,$b_id){

	    if(!is_array($authorized_fp_ids)){
	        //This should not happen
            return false;
        }

        //Checks to see if $u_id has any Facebook Pages that are not part of their most recent $authorized_fp_ids, meaning they had access before but not anymore
        $filters = array(
            'fs_u_id' => $u_id,
            'fp_status >=' => 0, //Available or Connected Page
            'fs_status' => 1, //Authorized Access
        );
	    if(count($authorized_fp_ids)>0){
            $filters['fs_fp_id NOT IN ('.join(',',$authorized_fp_ids).')'] = null;
        }
        $admin_lost_pages = $this->Db_model->fp_fetch($filters);

        if(count($admin_lost_pages)>0){

            //Oho, we seem to have some pages that this instructor no longer has access to:
            foreach($admin_lost_pages as $fp){

                //Update the status of this admin:
                $this->Db_model->fs_update( $fp['fs_id'] , array(
                    'fs_timestamp' => date("Y-m-d H:i:s"), //The most recent updated time
                    'fs_access_token' => null, //No more access token
                    'fs_status' => -1, //Revoked access for this Instructor
                ));

                //Log Engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $u_id,
                    'e_fp_id' => $fp['fp_id'],
                    'e_type_id' => 78, //Facebook Page Access Revoked
                    'e_b_id' => $b_id,
                ));

                //Does any other Mench instructor have access to this page? If not, make the page Unavailable:
                $admin_access_points = $this->Db_model->fp_fetch(array(
                    'fs_u_id !=' => $u_id, //Not this instructor
                    'fs_fp_id' => $fp['fp_id'],
                    'fs_status' => 1, //Authorized Access
                ));

                if(count($admin_access_points)==0){

                    //Make page Unavailable as no one else seems to be authorized to access it:
                    $this->Db_model->fp_update( $fp['fp_id'] , array(
                        'fp_timestamp' => date("Y-m-d H:i:s"), //The most recent updated time
                        'fp_status' => -1, //Page Unavailable
                    ));

                    //Log Engagement:
                    $this->Db_model->e_create(array(
                        'e_initiator_u_id' => $u_id,
                        'e_fp_id' => $fp['fp_id'],
                        'e_type_id' => 81, //Page Removed
                        'e_b_id' => $b_id,
                    ));

                    //Is this page connected to any Bootcamps? If so, this is an issue!
                    $b_connected = $this->Db_model->b_fetch(array(
                        'b_fp_id' => $fp['fp_id'],
                    ));

                    if(count($b_connected)>0){
                        //Remove the access to these pages from all Bootcamps and notify admin to look into this:
                        foreach($b_connected as $b){

                            //Disconnect Page:
                            $this->Db_model->b_update( $b['b_id'] , array(
                                'b_fp_id' => 0,
                            ));

                            //Log Engagement for Mench support team to review:
                            $this->Db_model->e_create(array(
                                'e_initiator_u_id' => $u_id,
                                'e_fp_id' => $fp['fp_id'],
                                'e_type_id' => 9, //Support team look into this
                                'e_b_id' => $b['b_id'],
                                'e_message' => 'Bootcamp was connected to this Facebook Page, but lost its connection as Instructor permissions were revoked in the most recent graph call. Review this case to ensure this disconnection would not impact the current students of any possible Action Classes for this Bootcamp.',
                            ));

                        }
                    }
                }
            }
        }

        //Return the pages that their access has been revoked:
        return $admin_lost_pages;
    }

    function fb_page_connect($u_id,$fp_id,$b_id){

        //Connects $fp_id to $b_id as requested by $u_id, and does the integration if necessary...

        //Validate the Page:
        $fp_pages = $this->Db_model->fp_fetch(array(
            'fp_id' => $fp_id,
        ));

        //Does this bootcamp have any current pages connected to it? If so, we'd need to disconnect them first:
        $bootcamps = $this->Db_model->b_fetch(array(
            'b_id' => $b_id,
        ));

        if(count($fp_pages)<1){
            //This should not happen!
            return false;
        } elseif(count($bootcamps)<1){
            //This should not happen!
            return false;
        } elseif($bootcamps[0]['b_fp_id']>0){
            //This Bootcamp is already connected to another page, disconnect it so it can be connected to this new page:
            $this->Facebook_model->fb_page_disconnect($u_id,$bootcamps[0]['b_fp_id'],$b_id);
        }

        //Ok, now we're ready to connect this new page to the Bootcamp
        //Is this page already integrated?
        if($fp_pages[0]['fp_status']==0){

            //This page is not integrated, let's do the integration before connecting to a Bootcamp:
            $e_json = array();

            //Subscribe to App so we get the messages funneled form them:
            $e_json['subscribed_apps'] = $this->Facebook_model->fb_graph($fp_pages[0]['fp_id'], 'POST', $fp_pages[0]['fp_fb_id'].'/subscribed_apps', array(), $fp_pages[0]);

            //APP SETTING
            //First white-label our domain so we can later set the persistent menu:
            $payload = array(
                'get_started' => array(
                    'payload' => 'GET_STARTED',
                ),
                'whitelisted_domains' => array(
                    'http://local.mench.co',
                    'https://mench.co',
                    'https://mench.com',
                ),
            );


            if(strlen($fp_pages[0]['fp_greeting'])>0){
                $payload['greeting'] = array(
                    'locale' => 'default',
                    'text' => $fp_pages[0]['fp_greeting'], //If any
                );
            }

            //Update:
            $e_json['messenger_profile_base'] = $this->Facebook_model->fb_graph($fp_pages[0]['fp_id'], 'POST', 'me/messenger_profile', $payload, $fp_pages[0]);

            //Wait until Facebook propagates changes of our whitelisted_domains setting:
            sleep(2);

            //Now with the right permission, update persistent_menu:
            $e_json['messenger_profile_persistent_menu'] = $this->Facebook_model->fb_graph($fp_pages[0]['fp_id'], 'POST', 'me/messenger_profile', array(
                'persistent_menu' => array(
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
                ),
            ), $fp_pages[0]);


            //Update Page status:
            $this->Db_model->fp_update( $fp_id , array(
                'fp_timestamp' => date("Y-m-d H:i:s"), //The most recent updated time
                'fp_status' => 1, //Connected & Integrated:
            ));

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $u_id,
                'e_type_id' => 79, //Page Integrated with Mench
                'e_b_id' => $b_id,
                'e_json' => $e_json,
                'e_fp_id' => $fp_id,
            ));

        }


        //Page should be integrated by now
        //Let's connect it to this Bootcamp:
        $this->Db_model->b_update( $b_id , array(
            'b_fp_id' => $fp_id,
        ));



        //Fetch all attachment messages of this Bootcamp and Sync to its Facebook Page:
        $media_messages = $this->Db_model->i_fetch(array(
            'i_status >' => 0, //Published in any form
            'i_media_type IN (\'video\',\'audio\',\'image\',\'file\')' => null, //Attachments only
            'i_c_id IN ('.join(',',$this->Db_model->fetch_c_tree($bootcamps[0]['b_c_id'])).')' => null, //Entire Bootcamp Action Plan
        ));
        foreach($media_messages as $i){
            //Craete a request to sync attachment:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $u_id,
                'e_type_id' => 83, //Message Facebook Sync e_type_id=83
                'e_i_id' => $i['i_id'],
                'e_c_id' => $i['i_c_id'],
                'e_b_id' => $b_id,
                'e_fp_id' => $fp_id,
                'e_cron_job' => 0, //Job pending
            ));
        }


        //Log Engagement
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $u_id,
            'e_fp_id' => $fp_id,
            'e_type_id' => 73, //Page Connected to Bootcamp
            'e_json' => array(
                'fb_sync_messages' => count($media_messages),
            ),
            'e_b_id' => $b_id,
        ));

        //All good:
        return true;
    }

    function fb_page_disconnect($u_id,$fp_id,$b_id){

        //Disconnects $fp_id from $b_id as requested by $u_id, and removes page integration if no longer connected to any Mench Bootcamp...

        //Validate both $fp_id & $b_id
        $fp_pages = $this->Db_model->fp_fetch(array(
            'fp_id' => $fp_id,
        ));

        //Does this bootcamp have any current pages connected to it? If so, we'd need to disconnect them first:
        $bootcamps = $this->Db_model->b_fetch(array(
            'b_id' => $b_id,
        ));

        if(count($fp_pages)<1){
            //This should not happen!
            return false;
        } elseif(count($bootcamps)<1){
            //This should not happen!
            return false;
        } elseif(!($bootcamps[0]['b_fp_id']==$fp_id)){
            //This Bootcamp is not connected to this page at this moment, so it cannot be removed:
            return false;
        }



        //Disconnect page:
        $this->Db_model->b_update( $b_id , array(
            'b_fp_id' => 0,
        ));

        //Now is this page connected to any other Mench Bootcamp?
        $b_connected_count = count($this->Db_model->b_fetch(array(
            'b_fp_id' => $fp_id,
            'b_id !=' => $b_id, //Not needed, just to be safe...
        )));
        $target_fp_status = ( $b_connected_count>0 ? 1 : 0 ); //Either connected or Available

        //Do we need to update the page status?
        if(!($fp_pages[0]['fp_status']==$target_fp_status)){
            //yes, page needs uodating:
            $this->Db_model->fp_update( $fp_id , array(
                'fp_timestamp' => date("Y-m-d H:i:s"), //The most recent updated time
                'fp_status' => $target_fp_status, //Available or Connected
            ));
        }

        //Log Engagement:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $u_id,
            'e_fp_id' => $fp_id,
            'e_type_id' => 74, //Page Disconnected
            'e_b_id' => $b_id,
        ));



        //Remove Page integration? (Don't do it for Mench)
        if($b_connected_count==0 && !($fp_pages[0]['fp_fb_id']=='381488558920384')){

            //Yup, not connected to any more Mench Bootcamps:
            $e_json = array();
            $e_json['messenger_profile'] = $this->Facebook_model->fb_graph($fp_id,'DELETE','me/messenger_profile' , array(
                //Define the settings to delete:
                'fields' => array(
                    'whitelisted_domains',
                    'persistent_menu',
                ),
                //We do NOT remove [get_started] because its a good feature for their bot to have
                //We do NOT remove [greeting] because it was set by them
            ));

            //Remove app subscription:
            $e_json['subscribed_apps'] = $this->Facebook_model->fb_graph($fp_id,'DELETE',$fp_pages[0]['fp_fb_id'].'/subscribed_apps');

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $u_id,
                'e_type_id' => 80, //Page Integration Removed
                'e_b_id' => $b_id,
                'e_json' => $e_json,
                'e_fp_id' => $fp_id,
            ));

        }


        //All good:
        return true;
    }




    //Sends out batch messages in an easier way:
    function fb_message($fp_id, $fp_psid, $messages, $notification_type='REGULAR'){

        if(!in_array(strtoupper($notification_type),array('REGULAR','SILENT_PUSH','NO_PUSH'))){
            return array(
                'status' => 0,
                'message' => 'Invalid notification type ['.$notification_type.']',
            );
        } elseif(count($messages)<1){
            return array(
                'status' => 0,
                'message' => 'No messages set',
            );
        }

        $e_json = array();
        $failed_count = 0;
        foreach($messages as $message){

            $process = $this->Facebook_model->fb_graph( $fp_id ,'GET','me/messages', array(
                'recipient' => array(
                    'id' => $u_fb_user_id,
                ),
                'message' => $message,
                'notification_type' => $notification_type,
            ));

            if(!$process['status']){
                $failed_count++;
            }

            array_push( $e_json , $process );
        }

        if($failed_count>0){

            return array(
                'status' => 0,
                'message' => 'Failed to send '.$failed_count.'/'.count($messages).' message'.show_s(count($messages)).'.',
                'e_json' => $e_json,
            );

        } else {

            return array(
                'status' => 1,
                'message' => 'Successfully sent '.count($messages).' message'.show_s(count($messages)),
                'e_json' => $e_json,
            );

        }
    }






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

}