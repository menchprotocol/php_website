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

    function fb_activation_url($u_id,$fp_id){

	    //Fetch the page:
        $fp_pages = $this->Db_model->fp_fetch(array(
            'fp_id' => $fp_id,
            'fp_status' => 1, //Must be connected to Mench
        ));

        if(!(count($fp_pages)==1)){
            //Log Error:
            $this->Db_model->e_create(array(
                'e_recipient_u_id' => $u_id,
                'e_fp_id' => $fp_id,
                'e_type_id' => 8, //Platform error
                'e_message' => 'fb_activation_url() failed to generate activation URL as $fp_id=['.$fp_id.'] did not have fp_status=[1]',
            ));

            //Could not find this page!
            return false;
        }

        //All good, return the activation URL:
        $bot_activation_salt = $this->config->item('bot_activation_salt');
        return 'https://m.me/'.$fp_pages[0]['fp_fb_id'].'?ref=msgact_'.$u_id.'_'.substr(md5($u_id.$bot_activation_salt),0,8);
    }

    function fb_identify_activate($fp, $fp_psid, $fb_ref=null){

	    /*
	     *
	     * Function will detect the user identity of all inbound messages
	     *
	     */

        if(!isset($fp['fp_id'])){
            //Ooops, this is not good:
            $this->Db_model->e_create(array(
                'e_message' => 'fb_identify_activate() got called with invalid $fp variable with $fp_psid=['.$fp_psid.']',
                'e_type_id' => 8, //Platform Error
            ));
            return 0;
        } elseif($fp_psid<1){
            //Ooops, this is not good:
            $this->Db_model->e_create(array(
                'e_message' => 'fb_identify_activate() got called without $fp_psid variable',
                'e_type_id' => 8, //Platform Error
                'e_fp_id' => $fp['fp_id'],
            ));
            return 0;
        }


        //Do we have a referral key? This would make life easier:
        $ref_u_id = 0;
        if($fb_ref){
            //We have a ref variable, make sure its valid:
            if(substr_count($fb_ref,'msgact_')==1){
                //Activate specific user
                $parts = explode('_',$fb_ref);
                $bot_activation_salt = $this->config->item('bot_activation_salt');
                if(isset($parts[2]) && $parts[2]==substr(md5($parts[1].$bot_activation_salt),0,8)){
                    $ref_u_id = intval($parts[1]); //This is the matches user id
                }
            }
        }




        //Is this fp_id/fp_psid already registered?
        $u = array(); //Should be replaced with the user object

        //First see if we have this user in the users table:
        $fetch_users = $this->Db_model->u_fetch(array(
            'u_cache__fp_id' => $fp['fp_id'],
            'u_cache__fp_psid' => $fp_psid,
        ));

        if(count($fetch_users)>0){
            $u = $fetch_users[0];
        } else {

            //See if we can find it in the admission table:
            $fetch_users = $this->Db_model->ru_fetch(array(
                'ru_fp_id' => $fp['fp_id'],
                'ru_fp_psid' => $fp_psid,
            ));

            if(count($fetch_users)>0){
                $u = $fetch_users[0];
            }
        }


        if(count($u)>0 && $u['u_id']>0){

            //Yes, make sure that there is no referral variable or if there is, its the same as this one:
            if($ref_u_id && !($u['u_id']==$ref_u_id)){
                //Ooops, a registered user has clicked on a referral URL for another user trying to activate
                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $u['u_id'],
                    'e_recipient_u_id' => $ref_u_id,
                    'e_json' => $this->Facebook_model->fb_foundation_message(923, $u['u_id'], $fp['fp_id']),
                    'e_message' => 'Failed to activate user because Messenger account is already associated with another user.',
                    'e_type_id' => 9, //Support Needing Graceful Errors
                ));
            }

            return intval($u['u_id']);

        }


        //User not yet activated, lets see if we have a ref key?
        if(!$ref_u_id){

            //We do not have a referral code, so its harder to authenticate and map the user.
            //It's also likely that they are new via Messenger, never visited the Mench website
            //This is validating to see if a sender is registered or not:

            //This is a new user that needs to be registered!
            //Call facebook messenger API and get user profile
            $graph_fetch = $this->Facebook_model->fb_graph($fp['fp_id'],'GET',$fp_psid,array(),$fp);

            if(!$graph_fetch['status']){
                //This error has already been logged
                //We cannot create this user:
                return 0;
            }

            //We're cool!
            $fb_profile = $graph_fetch['e_json']['result'];

            //Split locale into language and country
            $locale = explode('_',$fb_profile['locale'],2);

            //Create user
            $u = $this->Db_model->u_create(array(
                'u_fname' 			=> $fb_profile['first_name'],
                'u_lname' 			=> $fb_profile['last_name'],
                'u_url_key' 		=> generate_url_key($fb_profile['first_name'].$fb_profile['last_name']),
                'u_timezone' 		=> $fb_profile['timezone'],
                'u_image_url' 		=> $fb_profile['profile_pic'],
                'u_gender'		 	=> strtolower(substr($fb_profile['gender'],0,1)),
                'u_language' 		=> $locale[0],
                'u_country_code' 	=> $locale[1],
                'u_cache__fp_id'   => $fp['fp_id'],
                'u_cache__fp_psid' => $fp_psid,
            ));

            //Non verified guest students:
            $this->Facebook_model->fb_foundation_message(921, $u['u_id'], $fp['fp_id']);

            //Return the newly created user ID:
            return intval($u['u_id']);
        }




        
        
        

        //Fetch this account and see whatssup:
        $matching_users = $this->Db_model->u_fetch(array(
            'u_id' => $ref_u_id,
        ));
        if(count($matching_users)<1){
            //Invalid user ID, should not happen...
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $ref_u_id,
                'e_message' => 'fb_identify_activate() had valid referral key that did not exist in the datavase',
                'e_type_id' => 8, //Platform Error
                'e_fp_id' => $fp['fp_id'],
            ));
            
            return 0;
        } else {
            $u = $matching_users[0];
        }
        
        


        //We are ready to activate!
        /* *************************************
         * Messenger Activation
         * *************************************
         */

        //Fetch their profile from Facebook to update
        $graph_fetch = $this->Facebook_model->fb_graph(4,'GET',$fp_psid);
        if(!$graph_fetch['status']){
            //This error has already been logged inside $this->Facebook_model->fb_graph()
            //We cannot create this user:
            return 0;
        }

        //We're cool!
        $fb_profile = $graph_fetch['e_json']['result'];

        //Split locale into language and country
        $locale = explode('_',$fb_profile['locale'],2);

        //Do an Update for selected fields as linking:
        $this->Db_model->u_update( $u['u_id'] , array(
            'u_image_url'      => ( strlen($u['u_image_url'])<5 ? $fb_profile['profile_pic'] : $u['u_image_url'] ),
            'u_status'         => ( $u['u_status']==0 ? 1 : $u['u_status'] ), //Activate their profile as well
            'u_timezone'       => $fb_profile['timezone'],
            'u_gender'         => strtolower(substr($fb_profile['gender'],0,1)),
            'u_language'       => ( $u['u_language']=='en' && !($u['u_language']==$locale[0]) ? $locale[0] : $u['u_language'] ),
            'u_country_code'   => $locale[1],
            'u_fname'          => $fb_profile['first_name'], //Update their original names with FB
            'u_lname'          => $fb_profile['last_name'], //Update their original names with FB
            'u_url_key'        => generate_url_key($fb_profile['first_name'].$fb_profile['last_name']),
            'u_cache__fp_id'   => $fp['fp_id'],
            'u_cache__fp_psid' => $fp_psid,
        ));

        //Go through all their admissions and set this:
        $admissions = $this->Db_model->ru_fetch(array(
            'ru_u_id' => $u['u_id'],
            'ru_fp_id' => $fp['fp_id'], //Already set to this
            'ru_fp_psid' => null, //Not activated yet...
        ));
        foreach($admissions as $admission){
            $this->Db_model->ru_update( $admission['ru_id'], array(
                'ru_fp_psid' => $fp_psid,
            ));
        }


        //Log Activation Engagement & send message:
        $this->Db_model->e_create(array(
            'e_initiator_u_id' => $u['u_id'],
            'e_json' => array(
                'fb_profile' => $fb_profile,
                'activation_msg' => $this->Facebook_model->fb_foundation_message(($u['u_status']==2 ? 918 : 926), $u['u_id'], $fp['fp_id']),
            ),
            'e_type_id' => 31, //Messenger Activated
        ));

        //Return User ID:
        return intval($u['u_id']);

    }



    //Sends out batch messages in an easier way:
    function fb_send_messages($fp_id, $fp_psid, $messages, $u_fb_notification){

        if(count($messages)<1){
            return array(
                'status' => 0,
                'message' => 'No messages set',
            );
        }
        if($u_fb_notification && !in_array(strtoupper($u_fb_notification),array('REGULAR','SILENT_PUSH','NO_PUSH'))){
            $u_fb_notification = null;
        }

        $e_json = array();
        $failed_count = 0;
        foreach($messages as $message){

            $process = $this->Facebook_model->fb_graph($fp_id ,'POST','me/messages', array(
                'recipient' => array(
                    'id' => $fp_psid,
                ),
                'message' => $message,
                'notification_type' => $u_fb_notification,
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








    
    function fb_foundation_message($c_id, $u_id, $fp_id=0, $b_id=0, $r_id=0, $depth=0, $override_u_fb_notification=null){

        $depth = 0; //Override this for now and only focus on dispatching tasks at 1 level!


        //Fetch recipient:
        $u_fb_psid = 0;
        $u = array(); //Should be replaced with the user object

        //First see if we have this user in the users table:
        $limits = array(
            'u_id' => $u_id,
            'u_cache__fp_psid > ' => 0,
        );
        if($fp_id>0){
            $limits['u_cache__fp_id'] = $fp_id;
        }
        $fetch_users = $this->Db_model->u_fetch($limits);


        if(count($fetch_users)>0){

            $u = $fetch_users[0];
            $u_fb_psid = $u['u_cache__fp_psid'];
            $fp_id = $u['u_cache__fp_id'];

        } else {

            //See if we can find it in the admission table:
            $limits = array(
                'ru_u_id' => $u_id,
                'ru_fp_psid > ' => 0,
            );
            if($fp_id>0){
                $limits['ru_fp_id'] = $fp_id;
            }
            $fetch_users = $this->Db_model->ru_fetch($limits);

            if(count($fetch_users)>0){

                $u = $fetch_users[0];
                $u_fb_psid = $u['ru_fp_psid'];
                $fp_id = $u['ru_fp_id'];

            }
        }

        //Fetch the page:
        $fp_pages = array();
        if($fp_id>0){
            $fp_pages = $this->Db_model->fp_fetch(array(
                'fp_id' => $fp_id,
                'fp_status' => 1, //Must be connected to Mench otherwise we can't do much!
            ));
        }


        //Do we need to override notification type?
        if(count($u)>0 && $override_u_fb_notification && in_array(strtoupper($override_u_fb_notification),array('REGULAR','SILENT_PUSH','NO_PUSH'))){
            $u['u_fb_notification'] = $override_u_fb_notification;
        }


        //Fetch Bootcamp/Class if needed:
        $bootcamps = array();
        $bootcamp_data = null;
        $class = null;
        if($b_id){

            //Fetch the copy of the Action Plan for the Class:
            $bootcamps = fetch_action_plan_copy($b_id,$r_id);

            //Fetch intent relative to the bootcamp by doing an array search:
            $bootcamp_data = extract_level($bootcamps[0], $c_id);

            //Do we have a Class?
            if($r_id && $bootcamps[0]['this_class']){
                $class = $bootcamps[0]['this_class'];
            }

        }


        //Fetch intent and its messages with an appropriate depth
        $fetch_depth = (($depth==1 || ($b_id && $bootcamp_data['level']==2)) ? 1 : ( $depth>1 ? $depth : 0 ));
        $tree = $this->Db_model->c_fetch(array(
            'c.c_id' => $c_id,
        ), $fetch_depth, array('i')); //Supports up to 2 levels deep for now...



        //Check to see if we have any errors:
        $error_message = null;
        if(count($fp_pages)<1){
            $error_message = 'Facebook Page ['.$fp_id.'] is not active';
        } elseif(!isset($tree[0])){
            $error_message = 'Invalid Intent ID ['.$c_id.']';
        } elseif($b_id && count($bootcamps)<1){
            $error_message = 'Failed to find Bootcamp ['.$b_id.']';
        } elseif($b_id && !$bootcamp_data){
            $error_message = 'Failed to locate intent ['.$c_id.'] in Bootcamp ['.$b_id.']';
        } elseif($r_id && !$b_id){
            $error_message = 'Cannot reference a Class without a Bootcamp';
        } elseif($r_id && !$class){
            $error_message = 'Failed to locate Class ['.$r_id.']';
        } elseif($depth<0 || $depth>1){
            $error_message = 'Invalid depth ['.$depth.']';
        } elseif(intval($u_id)<1){
            $error_message = 'Invalid User ID ['.$u_id.']';
        } elseif(count($u)<1 || $u_fb_psid<1){
            $error_message = 'User ID ['.$u_id.'] did not have a valid PSID connected to Page ['.$fp_id.']';
        } elseif(!isset($u['u_fb_notification']) || !$u['u_fb_notification']){
            $error_message = 'Missing user Messenger notification type';
        }

        if($error_message){
            //Log error:
            $this->Db_model->e_create(array(
                'e_message' => 'fb_foundation_message() failed with error message ['.$error_message.']',
                'e_type_id' => 8, //Platform Error
                'e_c_id' => $c_id,
                'e_fp_id' => $fp_id,
                'e_recipient_u_id' => $u_id,
                'e_b_id' => $b_id,
                'e_r_id' => $r_id,
            ));

            //Return error:
            return array(
                'status' => 0,
                'message' => $error_message,
            );
        }



        //Define key variables:
        $instant_messages = array();
        $current_thread_outbound = 0; //Position of current intent

        //For engagement logging of custom messages (Messages that are not stored in v5_messages) via the echo_i() function
        $custom_message_e_data = array(
            'e_initiator_u_id' => 0, //System, prevents any signatures from being appended...
            'e_recipient_u_id' => $u_id,
            'i_c_id' => $c_id,
            'e_b_id' => $b_id,
            'e_r_id' => $r_id,
        );



        //This is the very first message for this milestone!
        if($b_id && $bootcamp_data['level']==2){

            //Add message to instant stream:
            array_push($instant_messages , echo_i(array_merge($custom_message_e_data, array(
                'i_media_type' => 'text',
                'i_message' => '🚩 ​{first_name} welcome to your '.$bootcamps[0]['b_sprint_unit'].' '.$bootcamp_data['sprint_index'].' milestone! The target outcome for this milestone is to '.strtolower($bootcamp_data['intent']['c_objective']).'.',
            )), $u['u_fname'], true ));

        }


        //Append main object messages:
        if(isset($tree[0]['c__messages']) && count($tree[0]['c__messages'])>0){
            //We have messages for the very first level!
            foreach($tree[0]['c__messages'] as $key=>$i){
                if($i['i_status']==1){
                    //Add message to instant stream:
                    array_push($instant_messages , echo_i(array_merge($i, $custom_message_e_data), $u['u_fname'], true ));
                }
            }
        }


        if($b_id && $bootcamp_data['level']==2){

            //How many tasks?
            $active_tasks = 0;
            foreach($tree[0]['c__child_intents'] as $task){
                if($task['c_status']>=1){
                    $active_tasks++;
                }
            }

            if($active_tasks==0){

                //Let students know there are no tasks for this milestone:
                array_push($instant_messages, echo_i(array_merge($custom_message_e_data, array(
                    'i_media_type' => 'text',
                    'i_message' => 'This Milestone has no Tasks!',
                )), $u['u_fname'], true));

            } else {

                //Let them know how many tasks:
                array_push($instant_messages, echo_i(array_merge($custom_message_e_data, array(
                    'i_media_type' => 'text',
                    'i_message' => 'To complete this Milestone you need to complete its ' . $active_tasks . ' task' .show_s($active_tasks). ' which is estimated to take ' . strtolower(trim(strip_tags(echo_time($bootcamp_data['intent']['c__estimated_hours'], 0)))) . ' in total. {button}', //{button} links to Milestone
                )), $u['u_fname'], true));

            }
        }


        //This is only for milestones (Not tasks) as its level=1 (level=0 is for tasks...)
        //TODO Optimize before launching...
        if(0 && $depth==1 && isset($tree[0]['c__child_intents']) && count($tree[0]['c__child_intents'])>0){

            $active_tasks = 0;
            foreach($tree[0]['c__child_intents'] as $task){
                if($task['c_status']>=1){
                    $active_tasks++;
                }
            }

            //Count how many tasks and let them know:
            if($active_tasks>0){

                foreach($tree[0]['c__child_intents'] as $level1_key=>$level1){

                    if($level1['c_status']<1) {
                        continue;
                    }


                    //Set initial counter:
                    $starting_message_count = count($instant_messages);


                    //Does this intent have messages?
                    if (isset($level1['c__messages']) && count($level1['c__messages']) > 0) {
                        //We do have a mesasage, lets see if they are active/drip:
                        foreach ($level1['c__messages'] as $key => $i) {
                            if ($i['i_status'] == 1) {

                                if($starting_message_count==count($instant_messages)){

                                    //This is the very first message for this Task being added:
                                    array_push( $instant_messages , echo_i( array_merge( array(
                                        'i_media_type' => 'text',
                                        'i_message' => ($active_tasks>1 ? 'Your first' : 'Your').' task is to '.strtolower($level1['c_objective']).'. Your instructor has estimated this task to take about '.strtolower(trim(strip_tags(echo_time($level1['c_time_estimate'],0)))).' to complete.',
                                    ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

                                    if($active_tasks>1){
                                        array_push( $instant_messages , echo_i( array_merge( array(
                                            'i_media_type' => 'text',
                                            'i_message' => 'Once completed I will instantly unlock your next task.',
                                        ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));
                                    } else {
                                        //The only task of this milestone:
                                        array_push( $instant_messages , echo_i( array_merge( array(
                                            'i_media_type' => 'text',
                                            'i_message' => 'Once completed you will complete this milestone as this is its only task.',
                                        ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));
                                    }

                                    array_push( $instant_messages , echo_i( array_merge( array(
                                        'i_media_type' => 'text',
                                        'i_message' => 'Here is how you can go about completing this task:',
                                    ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

                                }

                                //Not needed as we don't have this logic active for now...
                                //Mark this tree as sent for the stepping function that will later pick it up via the cron job:
                                //$tree[0]['c__child_intents'][$level1_key]['c__messages'][$key]['message_sent_time'] = date("Y-m-d H:i:s");

                                //Add message to instant stream:
                                array_push( $instant_messages , echo_i( array_merge( $i , array(
                                    'e_initiator_u_id' => 0, //System, prevents any signatures from being appended...
                                    'e_recipient_u_id' => $u_id,
                                    'i_c_id' => $i['i_c_id'],
                                    'e_r_id' => $r_id,
                                    'tree' => $tree[0],
                                    'depth' => $depth,
                                )), $recipients[0]['u_fname'], true /*Facebook Format*/ ));

                            }
                        }
                    }


                    //Create custom message based on Task Completion Settings:
                    array_push( $instant_messages , echo_i( array_merge( array(
                        'i_media_type' => 'text',
                        //TODO mention the class average response time here:
                        'i_message' => 'Completing this Task is estimated to take '.strip_tags(echo_time($level1['c_time_estimate'],0)).'. If you needed more assistance simply send me a message and I will forward it to your instructor for a timely response. Let\'s do it 🙌​',
                    ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

                    //Level 1 depth only deals with a single intent, so we'll always end here:
                    break;
                }
            }
        }


        //Anything to be sent instantly?
        if(count($instant_messages)<=0){
            //Successful:
            return array(
                'status' => 0,
                'message' => 'No messages to be sent',
            );
        }


        //All good, attempt to Dispatch all messages, their engagements have already been logged:
        return $this->Facebook_model->fb_send_messages($fp_id, $u_fb_psid, $instant_messages, $u['u_fb_notification']);
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

    function batch_messages( $botkey , $u_fb_id , $messages , $u_fb_notification='REGULAR'){

        $mench_bots = $this->config->item('mench_bots');
        if(!array_key_exists($botkey,$mench_bots)){
            die('Invalid Bot Key');
        } elseif(!in_array(strtoupper($u_fb_notification),array('REGULAR','SILENT_PUSH','NO_PUSH'))){
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
                'notification_type' => $u_fb_notification,
            ));

            array_push($stats,$result);
        }

        return $stats;
    }

}