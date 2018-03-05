<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Comm_model extends CI_Model {
		
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
            $error_message = 'Comm_model->fb_graph() failed to '.$action.' '.$url;
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

    function fb_detect_revoked($u_id,$authorized_fp_ids,$b_id){

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

    function fb_page_integration($u_id,$fp,$b_id,$do_integrate){

	    //We're either integrating or removing an integration:
	    if($do_integrate){

            //This page is not integrated, let's do the integration before connecting to a Bootcamp:
            $e_json = array();

            //Subscribe to App so we get the messages funneled form them:
            $e_json['subscribed_apps'] = $this->Comm_model->fb_graph($fp['fp_id'], 'POST', $fp['fp_fb_id'].'/subscribed_apps', array(), $fp);

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


            if(strlen($fp['fp_greeting'])>0){
                $payload['greeting'] = array(
                    'locale' => 'default',
                    'text' => $fp['fp_greeting'], //If any
                );
            }

            //Update:
            $e_json['messenger_profile_base'] = $this->Comm_model->fb_graph($fp['fp_id'], 'POST', 'me/messenger_profile', $payload, $fp);

            //Wait until Facebook pro-pagates changes of our whitelisted_domains setting:
            sleep(2);

            //Now with the right permission, update persistent_menu:
            $e_json['messenger_profile_persistent_menu'] = $this->Comm_model->fb_graph($fp['fp_id'], 'POST', 'me/messenger_profile', array(
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
            ), $fp);


            //Update Page status:
            $this->Db_model->fp_update( $fp['fp_id'] , array(
                'fp_timestamp' => date("Y-m-d H:i:s"), //The most recent updated time
                'fp_status' => 1, //Connected & Integrated:
            ));

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $u_id,
                'e_type_id' => 79, //Page Integrated with Mench
                'e_b_id' => $b_id,
                'e_json' => $e_json,
                'e_fp_id' => $fp['fp_id'],
            ));

        } else {

            $e_json = array();
            $e_json['messenger_profile'] = $this->Comm_model->fb_graph($fp['fp_id'], 'DELETE', 'me/messenger_profile' , array(
                //Define the settings to delete:
                'fields' => array(
                    'whitelisted_domains',
                    'persistent_menu',
                ),
                //We do NOT remove [get_started] because its a good feature for their bot to have
                //We do NOT remove [greeting] because it was set by them
            ));

            //Remove app subscription:
            $e_json['subscribed_apps'] = $this->Comm_model->fb_graph($fp['fp_id'], 'DELETE', $fp['fp_fb_id'].'/subscribed_apps');

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $u_id,
                'e_type_id' => 80, //Page Integration Removed
                'e_b_id' => $b_id,
                'e_json' => $e_json,
                'e_fp_id' => $fp['fp_id'],
            ));

        }

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
            $this->Comm_model->fb_page_disconnect($u_id,$bootcamps[0]['b_fp_id'],$b_id);
        }

        //Ok, now we're ready to connect this new page to the Bootcamp
        //Is this page already integrated?
        if($fp_pages[0]['fp_status']==0){
            //Nope, its not yet integrated with Mench, let's do that:
            $this->Comm_model->fb_page_integration($u_id,$fp_pages[0],$b_id,1);
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
        if($b_connected_count==0){
            //Yup, not connected to any more Mench Bootcamps, remove the Integration:
            $this->Comm_model->fb_page_integration($u_id,$fp_pages[0],$b_id,0);
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

                //See what type of account is this, as it might be an empty shell:
                if($u['u_status']==0 && strlen($u['u_email'])<1){

                    //Update this user to remove them:
                    $this->Db_model->u_update( $u['u_id'] , array(
                        'u_status'   => -2, //Merged
                        'u_cache__fp_psid' => null, //Remove from this user...
                    ));

                    //Reset user as if we did not find this:
                    $u = array();

                    //Would continue...

                } else {
                    //Ooops, this is a legitimate user which we cannot override
                    //Log engagement:
                    $this->Db_model->e_create(array(
                        'e_initiator_u_id' => $u['u_id'],
                        'e_recipient_u_id' => $ref_u_id,
                        'e_json' => $this->Comm_model->foundation_message(array(
                            'e_recipient_u_id' => $u['u_id'],
                            'e_fp_id' => $fp['fp_id'],
                            'e_c_id' => 923,
                            'depth' => 0,
                        )),
                        'e_message' => 'Failed to activate user because Messenger account is already associated with another user.',
                        'e_type_id' => 8, //Platform error
                    ));
                    return intval($u['u_id']);
                }

            } else {
                return intval($u['u_id']);
            }

        }


        //User not yet activated, lets see if we have a ref key?
        if(!$ref_u_id){

            //We do not have a referral code, so its harder to authenticate and map the user.
            //It's also likely that they are new via Messenger, never visited the Mench website
            //This is validating to see if a sender is registered or not:

            //This is a new user that needs to be registered!
            //Call facebook messenger API and get user profile
            $graph_fetch = $this->Comm_model->fb_graph($fp['fp_id'], 'GET', $fp_psid, array(), $fp);

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
                'u_cache__fp_id'    => $fp['fp_id'],
                'u_cache__fp_psid'  => $fp_psid,
                'u_status'          => 0, //For new users via Messenger
            ));

            //Non verified guest students:
            $this->Comm_model->foundation_message(array(
                'e_recipient_u_id' => $u['u_id'],
                'e_fp_id' => $fp['fp_id'],
                'e_c_id' => 921,
                'depth' => 0,
            ));

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
        $graph_fetch = $this->Comm_model->fb_graph($fp['fp_id'], 'GET', $fp_psid, array(), $fp);
        if(!$graph_fetch['status']){
            //This error has already been logged inside $this->Comm_model->fb_graph()
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
                'activation_msg' => $this->Comm_model->foundation_message(array(
                    'e_recipient_u_id' => $u['u_id'],
                    'e_fp_id' => $fp['fp_id'],
                    'e_c_id' => ($u['u_status']==2 ? 918 : 926),
                    'depth' => 0,
                )),
            ),
            'e_type_id' => 31, //Messenger Activated
        ));

        //Return User ID:
        return intval($u['u_id']);

    }

    function send_message($messages,$force_email=false){

        if(count($messages)<1){
            return array(
                'status' => 0,
                'message' => 'No messages set',
            );
        }

        $failed_count = 0;
        $email_to_send = array();
        $e_json = array(
            'messages' => array(),
            'email' => array(),
        );

        foreach($messages as $message){

            //Make sure we have the necessary fields:
            if(!isset($message['e_recipient_u_id'])){
                //Log error:
                $this->Db_model->e_create(array(
                    'e_json' => $message,
                    'e_type_id' => 8, //Platform error
                    'e_message' => 'send_message() failed to send message as it was missing e_recipient_u_id',
                ));
                continue;
            }

            //TODO Implement simple caching to remember $dispatch_fp_id & $dispatch_fp_psid && $u IF details such as e_r_id remain the same
            if(1){

                //Fetch user preferences:
                $users = array();
                if(!$force_email && isset($message['e_r_id']) && $message['e_r_id']>0){
                    //Fetch admission to class:
                    $users = $this->Db_model->ru_fetch(array(
                        'ru_u_id' => $message['e_recipient_u_id'],
                        'ru_r_id' => $message['e_r_id'],
                    ));
                }

                if(count($users)<1){
                    //Fetch user profile via their account:
                    $users = $this->Db_model->u_fetch(array(
                        'u_id' => $message['e_recipient_u_id'],
                    ));
                }


                if(count($users)<1){

                    //Log error:
                    $failed_count++;
                    $this->Db_model->e_create(array(
                        'e_recipient_u_id' => $message['e_recipient_u_id'],
                        'e_json' => $message,
                        'e_type_id' => 8, //Platform error
                        'e_message' => 'send_message() failed to fetch user details message as it was missing core variables',
                    ));
                    continue;

                } else {

                    //Determine communication method:
                    $dispatch_fp_id = 0;
                    $dispatch_fp_psid = 0;
                    $u = array();

                    if(!$force_email && isset($users[0]['ru_fp_id']) && isset($users[0]['ru_fp_psid']) && $users[0]['ru_fp_id']>0 && $users[0]['ru_fp_psid']>0){
                        //We fetched an admission with an active Messenger connection:
                        $dispatch_fp_id = $users[0]['ru_fp_id'];
                        $dispatch_fp_psid = $users[0]['ru_fp_psid'];
                        $u = $users[0];
                    } elseif(!$force_email && $users[0]['u_cache__fp_id']>0 && $users[0]['u_cache__fp_psid']>0){
                        //We fetched an admission with an active Messenger connection:
                        $dispatch_fp_id = $users[0]['u_cache__fp_id'];
                        $dispatch_fp_psid = $users[0]['u_cache__fp_psid'];
                        $u = $users[0];
                    } elseif(strlen($users[0]['u_email'])>0 && filter_var($users[0]['u_email'], FILTER_VALIDATE_EMAIL)){
                        //User has not activated Messenger but has email:
                        $u = $users[0];
                    } else {

                        //This should technically not happen!
                        //Log error:
                        $failed_count++;
                        $this->Db_model->e_create(array(
                            'e_recipient_u_id' => $message['e_recipient_u_id'],
                            'e_fp_id' => $message['e_fp_id'],
                            'e_json' => $message,
                            'e_type_id' => 8, //Platform error
                            'e_message' => 'send_message() detected user without an active email/Messenger with $force_email=['.($force_email?'1':'0').']',
                        ));
                        continue;
                    }
                }
            }



            //Send using email or Messenger?
            if(!$force_email && $dispatch_fp_id && $dispatch_fp_psid){

                //Messenger...
                //Do we have a specific fp_id requested, and if so, does it match the one we found?
                if(isset($message['e_fp_id']) && $message['e_fp_id']>0 && !($message['e_fp_id']==$dispatch_fp_id)){
                    //Ooops, we seem to have an issue here...
                    $failed_count++;
                    $this->Db_model->e_create(array(
                        'e_recipient_u_id' => $message['e_recipient_u_id'],
                        'e_fp_id' => $message['e_fp_id'],
                        'e_json' => $message,
                        'e_type_id' => 8, //Platform error
                        'e_message' => 'send_message() failed to send message because user FP ID ['.$dispatch_fp_id.'] was different that e_fp_id ['.$message['e_fp_id'].']',
                    ));
                    continue;
                } else {
                    //Override this data to the message data:
                    $message['e_fp_id'] = $dispatch_fp_id;
                }

                //Prepare Payload:
                $payload = array(
                    'recipient' => array(
                        'id' => $dispatch_fp_psid,
                    ),
                    'message' => echo_i($message, $u['u_fname'],true),
                    'notification_type' => $u['u_fb_notification'],
                );

                //Messenger:
                $process = $this->Comm_model->fb_graph($dispatch_fp_id ,'POST','me/messages', $payload);

                //Log Outbound Message Engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => ( isset($message['e_initiator_u_id']) ? $message['e_initiator_u_id'] : 0 ),
                    'e_recipient_u_id' => ( isset($message['e_recipient_u_id']) ? $message['e_recipient_u_id'] : 0 ),
                    'e_message' => ( $message['i_media_type']=='text' ? $message['i_message'] : '/attach '.$message['i_media_type'].':'.$message['i_url'] ),
                    'e_json' => array(
                        'i' => $message,
                        'first_name' => $u['u_fname'],
                        'payload' => $payload,
                        'tree' => ( isset($message['tree']) ? $message['tree'] : null ),
                        'depth' => ( isset($message['depth']) ? $message['depth'] : null ),
                    ),
                    'e_type_id' => 7, //Outbound message
                    'e_fp_id' => ( isset($message['e_fp_id'])   ? $message['e_fp_id'] :0), //If set...
                    'e_r_id'  => ( isset($message['e_r_id'])    ? $message['e_r_id']  :0), //If set...
                    'e_b_id'  => ( isset($message['e_b_id'])    ? $message['e_b_id']  :0), //If set...
                    'e_i_id'  => ( isset($message['i_id'])      ? $message['i_id']    :0), //The message that is being dripped
                    'e_c_id'  => ( isset($message['i_c_id'])    ? $message['i_c_id']  :0),
                ));

                if(!$process['status']){
                    $failed_count++;
                }

                array_push( $e_json['messages'] , $process );

            } else {

                //This is an email request, combine the emails per user:
                if(!isset($email_to_send[$u['u_id']])){

                    $email_variables = array(
                        'u_email' => $u['u_email'],
                        'subject_line' => 'New Message from Mench',
                        'html_message' => echo_i($message, $u['u_fname'],false),
                        'r_reply_to_email' => ( isset($u['r_reply_to_email']) && filter_var($u['r_reply_to_email'],FILTER_VALIDATE_EMAIL) ? $u['r_reply_to_email'] : null ),
                    );
                    $e_var_create = array(
                        'e_var_create' => array(
                            'e_initiator_u_id' => ( isset($message['e_initiator_u_id'])    ? $message['e_initiator_u_id']  :0), //If set...
                            'e_recipient_u_id' => $u['u_id'],
                            'e_message' => $email_variables['subject_line'],
                            'e_json' => $email_variables,
                            'e_type_id' => 28, //Email message sent
                            'e_r_id'  => ( isset($message['e_r_id'])    ? $message['e_r_id']  :0), //If set...
                            'e_b_id'  => ( isset($message['e_b_id'])    ? $message['e_b_id']  :0), //If set...
                            'e_c_id'  => ( isset($message['i_c_id'])    ? $message['i_c_id']  :0),
                        ),
                    );

                    $email_to_send[$u['u_id']] = array_merge($email_variables,$e_var_create);

                } else {
                    //Append message to this user:
                    $email_to_send[$u['u_id']]['html_message'] .= echo_i($message, $u['u_fname'],false);
                }

            }
        }


        //Do we have to send message?
        if(count($email_to_send)>0){
            //Yes, go through these emails and send them:
            foreach($email_to_send as $email){
                $process = $this->Comm_model->send_email(array($email['u_email']), $email['subject_line'], $email['html_message'], $email['e_var_create'], $email['r_reply_to_email']);

                array_push( $e_json['email'] , $process );
            }
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

    function foundation_message($message,$force_email=false){

        //Validate key components that are required:
        $error_message = null;
        if(count($message)<1){
            $error_message = 'Missing $message';
        } elseif(!isset($message['e_recipient_u_id']) || $message['e_recipient_u_id']<1){
            $error_message = 'Missing e_recipient_u_id';
        } elseif(!isset($message['e_c_id']) || $message['e_c_id']<1) {
            $error_message = 'Missing e_c_id';
        }

        if(!$error_message){

            $message['depth'] = 0; //Override this for now and only focus on dispatching tasks at 1 level
            $message['e_initiator_u_id'] = 0; //System, prevents any signatures from being appended...

            //Tweak optional variables:
            if(!isset($message['e_b_id']) || $message['e_b_id']<1){
                $message['e_b_id'] = 0;
            }
            if(!isset($message['e_r_id']) || $message['e_r_id']<1){
                $message['e_r_id'] = 0;
            }
            if(!isset($message['e_fp_id']) || $message['e_fp_id']<1){
                $message['e_fp_id'] = 0;
            }

            //Fetch Bootcamp/Class if needed:
            $bootcamps = array();
            $bootcamp_data = null;
            $class = null;

            if($message['e_b_id']){
                //Fetch the copy of the Action Plan for the Class:
                $bootcamps = fetch_action_plan_copy($message['e_b_id'],$message['e_r_id']);

                //Fetch intent relative to the bootcamp by doing an array search:
                $bootcamp_data = extract_level($bootcamps[0], $message['e_c_id']);

                //Do we have a Class?
                if($message['e_r_id'] && $bootcamps[0]['this_class']){
                    $class = $bootcamps[0]['this_class'];
                }
            }


            //Fetch intent and its messages with an appropriate depth
            $fetch_depth = (($message['depth']==1 || ($message['e_b_id'] && $bootcamp_data['level']==2)) ? 1 : ( $message['depth']>1 ? $message['depth'] : 0 ));
            $tree = $this->Db_model->c_fetch(array(
                'c.c_id' => $message['e_c_id'],
            ), $fetch_depth, array('i')); //Supports up to 2 levels deep for now...



            //Check to see if we have any other errors:
            if($message['e_r_id'] && !$message['e_b_id']){
                $error_message = 'Had e_r_id=['.$message['e_r_id'].'] but missing e_b_id';
            } elseif(!isset($tree[0])){
                $error_message = 'Invalid Intent ID ['.$message['e_c_id'].']';
            } elseif($message['e_b_id'] && count($bootcamps)<1){
                $error_message = 'Failed to find Bootcamp ['.$message['e_b_id'].']';
            } elseif($message['e_b_id'] && !$bootcamp_data){
                $error_message = 'Failed to locate intent ['.$message['e_c_id'].'] in Bootcamp ['.$message['e_b_id'].']';
            } elseif($message['e_r_id'] && !$message['e_b_id']){
                $error_message = 'Cannot reference a Class without a Bootcamp';
            } elseif($message['e_r_id'] && !$class){
                $error_message = 'Failed to locate Class ['.$message['e_r_id'].']';
            } elseif($message['depth']<0 || $message['depth']>1){
                $error_message = 'Invalid depth ['.$message['depth'].']';
            }
        }

        //Did we catch any errors?
        if($error_message){
            //Log error:
            $this->Db_model->e_create(array(
                'e_message' => 'foundation_message() error: '.$error_message,
                'e_type_id' => 8, //Platform Error
                'e_json' => $message,
                'e_c_id' => $message['e_c_id'],
                'e_fp_id' => $message['e_fp_id'],
                'e_recipient_u_id' => $message['e_recipient_u_id'],
                'e_initiator_u_id' => $message['e_initiator_u_id'],
                'e_b_id' => $message['e_b_id'],
                'e_r_id' => $message['e_r_id'],
            ));

            //Return error:
            return array(
                'status' => 0,
                'message' => $error_message,
            );
        }


        //Let's start adding-up the instant messages:
        $instant_messages = array();


        //This is the very first message for this milestone!
        if($message['e_b_id'] && $bootcamp_data['level']==2){

            //Add message to instant stream:
            array_push($instant_messages , array_merge($message, array(
                'i_media_type' => 'text',
                'i_message' => '🚩 ​{first_name} welcome to your '.$bootcamps[0]['b_sprint_unit'].' '.$bootcamp_data['sprint_index'].' milestone! The target outcome for this milestone is to '.strtolower($bootcamp_data['intent']['c_objective']).'.',
            )));

        }


        //Append main object messages:
        if(isset($tree[0]['c__messages']) && count($tree[0]['c__messages'])>0){
            //We have messages for the very first level!
            foreach($tree[0]['c__messages'] as $key=>$i){
                if($i['i_status']==1){
                    //Add message to instant stream:
                    array_push($instant_messages , array_merge($message, $i));
                }
            }
        }


        if($message['e_b_id'] && $bootcamp_data['level']==2){

            //How many tasks?
            $active_tasks = 0;
            foreach($tree[0]['c__child_intents'] as $task){
                if($task['c_status']>=1){
                    $active_tasks++;
                }
            }

            if($active_tasks==0){

                //Let students know there are no tasks for this milestone:
                array_push($instant_messages , array_merge($message, array(
                    'i_media_type' => 'text',
                    'i_message' => 'This Milestone has no Tasks!',
                )));

            } else {

                //Let them know how many tasks:
                array_push($instant_messages , array_merge($message, array(
                    'i_media_type' => 'text',
                    'i_message' => 'To complete this Milestone you need to complete its '.$active_tasks.' task'.show_s($active_tasks).' which is estimated to take ' . strtolower(trim(strip_tags(echo_time($bootcamp_data['intent']['c__estimated_hours'], 0)))) . ' in total. {button}', //{button} links to Milestone
                )));

            }
        }


        //This is only for milestones (Not tasks) as its level=1 (level=0 is for tasks...)
        if(0 && $message['depth']==1 && isset($tree[0]['c__child_intents']) && count($tree[0]['c__child_intents'])>0){

            //TODO Optimize before launching...
            /*
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
                                )), $recipients[0]['u_fname'], true ));

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

            */
        }


        //Anything to be sent instantly?
        if(count($instant_messages)<1){
            $this->Db_model->e_create(array(
                'e_message' => 'foundation_message() error: No messages to be sent after compiling everything',
                'e_type_id' => 8, //Platform Error
                'e_json' => $message,
                'e_c_id' => $message['e_c_id'],
                'e_fp_id' => $message['e_fp_id'],
                'e_recipient_u_id' => $message['e_recipient_u_id'],
                'e_initiator_u_id' => $message['e_initiator_u_id'],
                'e_b_id' => $message['e_b_id'],
                'e_r_id' => $message['e_r_id'],
            ));

            //Error:
            return array(
                'status' => 0,
                'message' => 'No messages to be sent',
            );
        }

        //All good, attempt to Dispatch all messages, their engagements have already been logged:
        return $this->Comm_model->send_message($instant_messages,(isset($message['force_email']) && $message['force_email']));
    }

    function send_email($to_array,$subject,$html_message,$e_var_create=array(),$reply_to=null){

        if(is_dev()){
            return true;
        }

        //Loadup amazon SES:
        require_once( 'application/libraries/aws/aws-autoloader.php' );
        $this->CLIENT = new Aws\Ses\SesClient([
            'version' 	    => 'latest',
            'region'  	    => 'us-west-2',
            'credentials'   => $this->config->item('aws_credentials'),
        ]);

        if(!$reply_to){
            //Set default:
            $reply_to = 'support@mench.com';
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

        //Log engagement once:
        if(count($e_var_create)>0){
            $this->Db_model->e_create($e_var_create);
        }

    }

}