<?php if ( !defined('BASEPATH')) exit('No direct script access allowed');

class Comm_model extends CI_Model {
		
	function __construct() {
		parent::__construct();
	}
	
    function fb_graph($action,$url,$payload=array()){

	    //Do some initial checks
	    if(!in_array($action, array('GET','POST','DELETE'))){

	        //Only 4 valid types of $action
            return array(
                'status' => 0,
                'message' => '$action ['.$action.'] is invalid',
            );

        }


        //Start building GET URL:
        if(array_key_exists('access_token',$payload)){

            //This this access token:
            $access_token_payload = array(
                'access_token' => $payload['access_token'],
            );
            //Remove it just in case:
            unset($payload['access_token']);

        } else {
            //Apply the Page Access Token:
            $fb_settings = $this->config->item('fb_settings');
            $access_token_payload = array(
                'access_token' => $fb_settings['mench_access_token']
            );
        }

        if($action=='GET' && count($payload)>0){
            //Add $payload to GET variables:
            $access_token_payload = array_merge($access_token_payload,$payload);
            $payload = array();
        }

        $url = 'https://graph.facebook.com/v2.6'.$url;
        $counter = 0;
        foreach($access_token_payload as $key=>$val){
            $url = $url.($counter==0?'?':'&').$key.'='.$val;
            $counter++;
        }

        //Make the graph call:
        $ch = curl_init($url);

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
                'e_text_value' => $error_message,
                'e_inbound_c_id' => 8, //Platform Error
                'e_json' => $e_json,
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

    function fb_activation_url($u_id,$fp_id,$ref_only=false){

        if($fp_id<1 || $u_id<1){
            //Log Error:
            $this->Db_model->e_create(array(
                'e_outbound_u_id' => $u_id,
                'e_fp_id' => $fp_id,
                'e_inbound_c_id' => 8, //Platform error
                'e_text_value' => 'fb_activation_url() failed to generate activation URL as $fp_id=['.$fp_id.'] OR $u_id=['.$u_id.']',
            ));
            //Could not find this page!
            return false;
        }

	    //Fetch the page:
        $fp_pages = $this->Db_model->fp_fetch(array(
            'fp_id' => $fp_id,
            'fp_status' => 1, //Must be connected to Mench
        ), array('fs'));

        if(count($fp_pages)<1){
            //Log Error:
            $this->Db_model->e_create(array(
                'e_outbound_u_id' => $u_id,
                'e_fp_id' => $fp_id,
                'e_inbound_c_id' => 8, //Platform error
                'e_text_value' => 'fb_activation_url() failed to generate activation URL as $fp_id=['.$fp_id.'] did not have fp_status=[1]',
            ));

            //Could not find this page!
            return false;
        }

        //All good, return the activation URL:
        $bot_activation_salt = $this->config->item('bot_activation_salt');
        $ref_key = 'msgact_'.$u_id.'_'.substr(md5($u_id.$bot_activation_salt),0,8);

        if($ref_only){
            return $ref_key;
        } else {
            return 'https://m.me/'.$fp_pages[0]['fp_fb_id'].'?ref='.$ref_key;
        }
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
                'e_text_value' => 'fb_identify_activate() got called with invalid $fp variable with $fp_psid=['.$fp_psid.']',
                'e_inbound_c_id' => 8, //Platform Error
            ));
            return false;
        } elseif($fp_psid<1){
            //Ooops, this is not good:
            $this->Db_model->e_create(array(
                'e_text_value' => 'fb_identify_activate() got called without $fp_psid variable',
                'e_inbound_c_id' => 8, //Platform Error
                'e_fp_id' => $fp['fp_id'],
            ));
            return false;
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




        //Try finding User/Bootcamp/Class ID:
        $u = array();

        //Is this fp_id/fp_psid already registered?
        $fetch_users = $this->Db_model->u_fetch(array(
            'u_cache__fp_id' => $fp['fp_id'],
            'u_cache__fp_psid' => $fp_psid,
        ));

        if(count($fetch_users)>0){

            //Assign user:
            $u = $fetch_users[0];

            //Attempt to search based on u_id:
            $ru_filter = array(
                'ru.ru_outbound_u_id' => $u['u_id'],
            );

        } else {

            //Attempt to search based on ru_fp_id/psid:
            $ru_filter = array(
                'ru_fp_id' => $fp['fp_id'],
                'ru_fp_psid' => $fp_psid,
            );

        }

        //See if we can find it in the enrollment table:
        $enrollments = $this->Db_model->ru_fetch($ru_filter);
        $active_enrollment = detect_active_enrollment($enrollments); //We'd need to see which enrollment to load now
        if($active_enrollment){
            //Override with more complete $u object:
            $u = $active_enrollment;
        }


        if(count($u)>0 && $u['u_id']>0){

            //Returning student located

            //Yes, make sure that there is no referral variable or if there is, its the same as this one:
            if($ref_u_id && !($u['u_id']==$ref_u_id)){

                //See what type of account is this:
                if(strlen($u['u_email'])<1){

                    //Remove this entity to remove them:
                    $this->Db_model->u_update( $u['u_id'] , array(
                        'u_status'   => -1, //Deleted
                        'u_cache__fp_psid' => null, //Remove from this user...
                    ));

                    //Reset user as if we did not find this:
                    $u = array();

                    //Would continue...

                } else {

                    //Ooops, this is a legitimate user which we cannot override

                    //Send notification Messages:
                    $notify_user = $this->Comm_model->foundation_message(array(
                        'e_outbound_u_id' => $u['u_id'],
                        'e_fp_id' => $fp['fp_id'],
                        'e_outbound_c_id' => 923,
                        'depth' => 0,
                        'e_b_id' => ( isset($u['ru_b_id']) ? $u['ru_b_id'] : 0 ),
                        'e_r_id' => ( isset($u['r_id']) ? $u['r_id'] : 0 ),
                    ));

                    //Log engagement:
                    $this->Db_model->e_create(array(
                        'e_inbound_u_id' => $u['u_id'],
                        'e_outbound_u_id' => $ref_u_id,
                        'e_json' => $notify_user,
                        'e_text_value' => 'fb_identify_activate() Failed to activate user because Messenger account is already associated with another user.',
                        'e_inbound_c_id' => 8, //Platform error
                        'e_b_id' => ( isset($u['ru_b_id']) ? $u['ru_b_id'] : 0 ),
                        'e_r_id' => ( isset($u['r_id']) ? $u['r_id'] : 0 ),
                    ));

                    //Return user Object:
                    return $u;

                }

            } else {
                //Return user Object:
                return $u;
            }

        } else {

            //User not found in the database

            //lets see if we have a ref key?

            if($ref_u_id){

                //So now we should have $ref_u_id set
                //Fetch this account and see whatssup:
                $matching_users = $this->Db_model->u_fetch(array(
                    'u_id' => $ref_u_id,
                ));
                if(count($matching_users)<1){

                    //Invalid user ID, should not happen...
                    $this->Db_model->e_create(array(
                        'e_inbound_u_id' => $ref_u_id,
                        'e_text_value' => 'fb_identify_activate() had valid referral key that did not exist in the datavase',
                        'e_inbound_c_id' => 8, //Platform Error
                        'e_fp_id' => $fp['fp_id'],
                        'e_b_id' => ( isset($u['ru_b_id']) ? $u['ru_b_id'] : 0 ),
                        'e_r_id' => ( isset($u['r_id']) ? $u['r_id'] : 0 ),
                    ));

                    return false;
                }



                //Set user object:
                $u = $matching_users[0];

                //See if we can find it in the enrollment table:
                $enrollments = $this->Db_model->ru_fetch(array(
                    'ru.ru_outbound_u_id' => $u['u_id'],
                ));
                $active_enrollment = detect_active_enrollment($enrollments); //We'd need to see which enrollment to load now
                if($active_enrollment){
                    //Override with more complete $u object:
                    $u = $active_enrollment;
                }


                //We are ready to activate!
                /* *************************************
                 * Messenger Activation
                 * *************************************
                 */

                //Fetch their profile from Facebook to update
                $graph_fetch = $this->Comm_model->fb_graph('GET', '/'.$fp_psid, array());


                if(!$graph_fetch['status']){

                    //This error has already been logged inside $this->Comm_model->fb_graph()
                    //We cannot create this user:
                    return false;

                } elseif(!isset($graph_fetch['e_json']['result']['locale'])){

                    //This error has not been logged, and needs more attention:
                    $this->Db_model->e_create(array(
                        'e_inbound_u_id' => $u['u_id'],
                        'e_json' => array(
                            'fp' => $fp,
                            'fp_psid' => $fp_psid,
                            'u' => $u,
                            'graph_fetch' => $graph_fetch,
                        ),
                        'e_text_value' => 'fb_identify_activate() failed to fetch user profile data',
                        'e_inbound_c_id' => 8, //Platform error
                        'e_b_id' => ( isset($u['ru_b_id']) ? $u['ru_b_id'] : 0 ),
                        'e_r_id' => ( isset($u['r_id']) ? $u['r_id'] : 0 ),
                    ));

                    return false;

                }

                //We're cool!
                $fb_profile = $graph_fetch['e_json']['result'];

                //Split locale into language and country
                $locale = explode('_',$fb_profile['locale'],2);

                //Save picture locally:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $u['u_id'],
                    'e_text_value' => $fb_profile['profile_pic'], //Image to be saved
                    'e_status' => 0, //Pending upload
                    'e_inbound_c_id' => 7001, //Cover Photo Save
                ));


                //Do an Update for selected fields as linking:
                $this->Db_model->u_update( $u['u_id'] , array(
                    'u_timezone'       => $fb_profile['timezone'],
                    'u_gender'         => strtolower(substr($fb_profile['gender'],0,1)),
                    'u_language'       => ( $u['u_language']=='en' && !($u['u_language']==$locale[0]) ? $locale[0] : $u['u_language'] ),
                    'u_country_code'   => $locale[1],
                    'u_full_name'      => $fb_profile['first_name'].' '.$fb_profile['last_name'], //Update their original names with FB
                    'u_cache__fp_id'   => $fp['fp_id'],
                    'u_cache__fp_psid' => $fp_psid,
                ));


                //Log Account Update Engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $u['u_id'],
                    'e_outbound_u_id' => $u['u_id'],
                    'e_json' => array(
                        'before' => $u,
                        'after' => $fb_profile,
                    ),
                    'e_inbound_c_id' => 12, //Account Updated
                ));


                //Go through all their enrollments and update their Messenger PSID:
                $enrollments = $this->Db_model->ru_fetch(array(
                    'ru_outbound_u_id' => $u['u_id'],
                    'ru_fp_id' => $fp['fp_id'], //Already set to this
                    'ru_fp_psid' => null, //Not activated yet...
                ));
                foreach($enrollments as $enrollment){
                    $this->Db_model->ru_update($enrollment['ru_id'], array(
                        'ru_fp_psid' => $fp_psid,
                    ));
                }


                //Send activation Message:
                $activation_msg = $this->Comm_model->foundation_message(array(
                    'e_outbound_u_id' => $u['u_id'],
                    'e_fp_id' => $fp['fp_id'],
                    'e_outbound_c_id' => 926, //Student activation, to be removed?
                    'depth' => 0,
                    'e_b_id' => ( isset($u['ru_b_id']) ? $u['ru_b_id'] : 0 ),
                    'e_r_id' => ( isset($u['r_id']) ? $u['r_id'] : 0 ),
                ));

                //Log Activation Engagement
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $u['u_id'],
                    'e_json' => array(
                        'fb_profile' => $fb_profile,
                        'activation_msg' => $activation_msg,
                    ),
                    'e_inbound_c_id' => 31, //Messenger Activated
                    'e_fp_id' => $fp['fp_id'],
                    'e_b_id' => ( isset($u['ru_b_id']) ? $u['ru_b_id'] : 0 ),
                    'e_r_id' => ( isset($u['r_id']) ? $u['r_id'] : 0 ),
                ));



                //Return User Object:
                return $u;

            } else {

                //We do not have a referral code, so its harder to authenticate and map the user.
                //It's also likely that they are new via Messenger, never visited the Mench website
                //This is validating to see if a sender is registered or not:

                //This is a new user that needs to be registered!
                //Call facebook messenger API and get user profile
                $graph_fetch = $this->Comm_model->fb_graph('GET', '/'.$fp_psid, array());

                if(!$graph_fetch['status'] || !isset($graph_fetch['e_json']['result']['first_name']) || strlen($graph_fetch['e_json']['result']['first_name'])<1){

                    $this->Db_model->e_create(array(
                        'e_text_value' => 'fb_identify_activate() failed to fetch user profile from Facebook Graph',
                        'e_json' => array(
                            'fp' => $fp,
                            'fp_psid' => $fp_psid,
                            'fb_ref' => $fb_ref,
                            'graph_fetch' => $graph_fetch,
                        ),
                        'e_inbound_c_id' => 8, //Platform Error
                        'e_fp_id' => $fp['fp_id'],
                        'e_b_id' => ( isset($u['ru_b_id']) ? $u['ru_b_id'] : 0 ),
                        'e_r_id' => ( isset($u['r_id']) ? $u['r_id'] : 0 ),
                    ));

                    //We cannot create this user:
                    return false;
                }

                //We're cool!
                $fb_profile = $graph_fetch['e_json']['result'];

                //Split locale into language and country
                $locale = explode('_',$fb_profile['locale'],2);

                //Create user
                $u = $this->Db_model->u_create(array(
                    'u_full_name' 		=> $fb_profile['first_name'].' '.$fb_profile['last_name'],
                    'u_timezone' 		=> $fb_profile['timezone'],
                    'u_gender'		 	=> strtolower(substr($fb_profile['gender'],0,1)),
                    'u_language' 		=> $locale[0],
                    'u_country_code' 	=> $locale[1],
                    'u_cache__fp_id'    => $fp['fp_id'],
                    'u_cache__fp_psid'  => $fp_psid,
                ));

                //Update Algolia:
                $this->Db_model->algolia_sync('u',$u['u_id']);

                //Save picture locally:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $u['u_id'],
                    'e_text_value' => $fb_profile['profile_pic'], //Image to be saved
                    'e_status' => 0, //Pending upload
                    'e_inbound_c_id' => 7001, //Cover Photo Save
                ));

                //New Student Without Subscription:
                $this->Comm_model->foundation_message(array(
                    'e_outbound_u_id' => $u['u_id'],
                    'e_fp_id' => $fp['fp_id'],
                    'e_outbound_c_id' => 921,
                    'depth' => 0,
                ));

                //Return the newly created user Object:
                return $u;

            }

        }

    }

    function send_message($messages,$force_email=false,$intent_title_subject=false){

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
            if(!isset($message['e_outbound_u_id'])){
                //Log error:
                $this->Db_model->e_create(array(
                    'e_json' => $message,
                    'e_inbound_c_id' => 8, //Platform error
                    'e_text_value' => 'send_message() failed to send message as it was missing e_outbound_u_id',
                ));
                continue;
            }

            //TODO Implement simple caching to remember $dispatch_fp_id & $dispatch_fp_psid && $u IF details such as e_r_id remain the same
            if(1){

                //Fetch user communication preferences:
                $users = array();
                if(!$force_email && isset($message['e_r_id']) && $message['e_r_id']>0){
                    //Fetch enrollment to class:
                    $users = $this->Db_model->ru_fetch(array(
                        'ru_outbound_u_id' => $message['e_outbound_u_id'],
                        'ru_r_id' => $message['e_r_id'],
                    ));
                }

                if(count($users)<1){
                    //Fetch user profile via their account:
                    $users = $this->Db_model->u_fetch(array(
                        'u_id' => $message['e_outbound_u_id'],
                    ));
                }


                if(count($users)<1){

                    //Log error:
                    $failed_count++;
                    $this->Db_model->e_create(array(
                        'e_outbound_u_id' => $message['e_outbound_u_id'],
                        'e_json' => $message,
                        'e_inbound_c_id' => 8, //Platform error
                        'e_text_value' => 'send_message() failed to fetch user details message as it was missing core variables',
                    ));
                    continue;

                } else {

                    //Determine communication method:
                    $dispatch_fp_id = 0;
                    $dispatch_fp_psid = 0;
                    $u = array();

                    if(!$force_email && isset($users[0]['ru_fp_id']) && isset($users[0]['ru_fp_psid']) && $users[0]['ru_fp_id']>0 && $users[0]['ru_fp_psid']>0){
                        //We fetched an enrollment with an active Messenger connection:
                        $dispatch_fp_id = $users[0]['ru_fp_id'];
                        $dispatch_fp_psid = $users[0]['ru_fp_psid'];
                        $u = $users[0];
                    } elseif(!$force_email && $users[0]['u_cache__fp_id']>0 && $users[0]['u_cache__fp_psid']>0){
                        //We fetched an enrollment with an active Messenger connection:
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
                            'e_outbound_u_id' => $message['e_outbound_u_id'],
                            'e_json' => $message,
                            'e_inbound_c_id' => 8, //Platform error
                            'e_text_value' => 'send_message() detected user without an active email/Messenger with $force_email=['.($force_email?'1':'0').']',
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
                        'e_outbound_u_id' => $message['e_outbound_u_id'],
                        'e_fp_id' => $message['e_fp_id'],
                        'e_json' => $message,
                        'e_inbound_c_id' => 8, //Platform error
                        'e_text_value' => 'send_message() failed to send message because user FP ID ['.$dispatch_fp_id.'] was different that e_fp_id ['.$message['e_fp_id'].']',
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
                    'message' => echo_i($message, $u['u_full_name'],true),
                    'messaging_type' => 'NON_PROMOTIONAL_SUBSCRIPTION', //https://developers.facebook.com/docs/messenger-platform/send-messages#messaging_types
                    'notification_type' => $u['u_fb_notification'],
                );

                //Messenger:
                $process = $this->Comm_model->fb_graph('POST','/me/messages', $payload);

                //Log Outbound Message Engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => ( isset($message['e_inbound_u_id']) ? $message['e_inbound_u_id'] : 0 ),
                    'e_outbound_u_id' => ( isset($message['e_outbound_u_id']) ? $message['e_outbound_u_id'] : 0 ),
                    'e_text_value' => $message['i_message'],
                    'e_json' => array(
                        'input_message' => $message,
                        'input_force_email' => ( $force_email ? 1 : 0 ),
                        'input_intent_title_subject' => ( $intent_title_subject ? 1 : 0 ),
                        'payload' => $payload,
                        'results' => $process,
                    ),
                    'e_inbound_c_id' => 7, //Outbound message
                    'e_fp_id' => ( isset($message['e_fp_id'])   ? $message['e_fp_id'] :0), //If set...
                    'e_r_id'  => ( isset($message['e_r_id'])    ? $message['e_r_id']  :0), //If set...
                    'e_b_id'  => ( isset($message['e_b_id'])    ? $message['e_b_id']  :0), //If set...
                    'e_i_id'  => ( isset($message['i_id'])      ? $message['i_id']    :0), //The message that is being dripped
                    'e_outbound_c_id'  => ( isset($message['i_outbound_c_id']) ? $message['i_outbound_c_id'] : 0),
                ));

                if(!$process['status']){
                    $failed_count++;
                }

                array_push( $e_json['messages'] , $process );

            } else {

                //This is an email request, combine the emails per user:
                if(!isset($email_to_send[$u['u_id']])){


                    $subject_line = 'New Message from Mench';

                    /*
                    if($intent_title_subject && isset($message['i_outbound_c_id']) && $message['i_outbound_c_id']>0){
                        $intents = $this->Db_model->c_fetch(array(
                            'c.c_id' => $message['i_outbound_c_id'],
                        ));
                        if(count($intents)>0){
                            $subject_line = $intents[0]['c_outcome'];
                        }
                    }
                    */

                    $email_variables = array(
                        'u_email' => $u['u_email'],
                        'subject_line' => $subject_line,
                        'html_message' => echo_i($message, $u['u_full_name'],false),
                    );

                    //Should we update email?
                    if(isset($message['e_b_id']) && $message['e_b_id']>0){
                        //Fetch Bootcamp Details:
                        $bs = $this->Db_model->b_fetch(array(
                            'b.b_id' => $message['e_b_id'],
                        ));
                    }

                    $e_var_create = array(
                        'e_var_create' => array(
                            'e_inbound_u_id' => ( isset($message['e_inbound_u_id']) ? $message['e_inbound_u_id'] : 0 ), //If set...
                            'e_outbound_u_id' => $u['u_id'],
                            'e_text_value' => $email_variables['subject_line'],
                            'e_json' => $email_variables,
                            'e_inbound_c_id' => 28, //Email message sent
                            'e_r_id'  => ( isset($message['e_r_id']) ? $message['e_r_id'] : 0 ),
                            'e_b_id'  => ( isset($message['e_b_id']) ? $message['e_b_id'] : 0 ),
                            'e_outbound_c_id'  => ( isset($message['i_outbound_c_id']) ? $message['i_outbound_c_id'] : 0 ),
                        ),
                    );

                    $email_to_send[$u['u_id']] = array_merge($email_variables,$e_var_create);

                } else {
                    //Append message to this user:
                    $email_to_send[$u['u_id']]['html_message'] .= '<div style="padding-top:12px;">'.echo_i($message, $u['u_full_name'],false).'</div>';
                }

            }
        }


        //Do we have to send message?
        if(count($email_to_send)>0){
            //Yes, go through these emails and send them:
            foreach($email_to_send as $email){
                $process = $this->Comm_model->send_email(array($email['u_email']), $email['subject_line'], $email['html_message'], $email['e_var_create'], 'support@mench.com' /*Hack! To be replaced with ceo email*/);

                array_push( $e_json['email'] , $process );
            }
        }



        if($failed_count>0){

            return array(
                'status' => 0,
                'message' => 'Failed to send '.$failed_count.'/'.count($messages).' message'.echo__s(count($messages)).'.',
                'e_json' => $e_json,
            );

        } else {

            return array(
                'status' => 1,
                'message' => 'Successfully sent '.count($messages).' message'.echo__s(count($messages)),
                'e_json' => $e_json,
            );

        }
    }

    function foundation_message($message,$force_email=false){

        //Validate key components that are required:
        $error_message = null;
        if(count($message)<1){
            $error_message = 'Missing $message';
        } elseif(!isset($message['e_outbound_c_id']) || $message['e_outbound_c_id']<1){
            $error_message = 'Missing e_outbound_c_id';
        } elseif(!isset($message['e_outbound_u_id']) || $message['e_outbound_u_id']<1) {
            $error_message = 'Missing e_outbound_u_id';
        }

        if(!$error_message){

            $message['depth'] = 0; //Override this for now and only focus on dispatching Steps at 1 level
            $message['e_inbound_u_id'] = 0; //System, prevents any signatures from being appended...

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
            $bs = array();
            $b_data = null;
            $class = null;

            if($message['e_b_id']){
                //Fetch the copy of the Action Plan for the Class:
                $bs = fetch_action_plan_copy($message['e_b_id'],$message['e_r_id']);

                //Fetch intent relative to the Bootcamp by doing an array search:
                $b_data = extract_level($bs[0], $message['e_outbound_c_id']);
                //IF !$b_data it likely means that intent is a generic system notification not part of $message['e_b_id']

                //Do we have a Class?
                if($message['e_r_id'] && $bs[0]['this_class']){
                    $class = $bs[0]['this_class'];
                }
            }


            //Fetch intent and its messages with an appropriate depth
            $fetch_depth = (($message['depth']==1 || ($message['e_b_id'] && $b_data['level']==2)) ? 1 : ( $message['depth']>1 ? $message['depth'] : 0 ));
            $tree = $this->Db_model->c_fetch(array(
                'c.c_id' => $message['e_outbound_c_id'],
            ), $fetch_depth, array('i')); //Supports up to 2 levels deep for now...



            //Check to see if we have any other errors:
            if($message['e_r_id'] && !$message['e_b_id']){
                $error_message = 'Had e_r_id=['.$message['e_r_id'].'] but missing e_b_id';
            } elseif(!isset($tree[0])){
                $error_message = 'Invalid Intent ID ['.$message['e_outbound_c_id'].']';
            } elseif($message['e_b_id'] && count($bs)<1){
                $error_message = 'Failed to find Bootcamp ['.$message['e_b_id'].']';
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
                'e_text_value' => 'foundation_message() error: '.$error_message,
                'e_inbound_c_id' => 8, //Platform Error
                'e_json' => $message,
                'e_outbound_u_id' => $message['e_outbound_u_id'],
                'e_fp_id' => $message['e_fp_id'],
                'e_outbound_c_id' => $message['e_outbound_c_id'],
                'e_inbound_u_id' => $message['e_inbound_u_id'],
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

        //Anything to be sent instantly?
        if(count($instant_messages)<1){
            //Nothing to be sent
            return array(
                'status' => 0,
                'message' => 'No messages to be sent',
            );
        }

        //All good, attempt to Dispatch all messages, their engagements have already been logged:
        return $this->Comm_model->send_message($instant_messages,$force_email,(!$message['e_b_id'] || !$b_data));
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

        //Log engagement once:
        if(count($e_var_create)>0){
            $this->Db_model->e_create($e_var_create);
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