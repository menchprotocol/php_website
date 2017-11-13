<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function t(){
	    header('Content-Type: application/json');
	    echo json_encode($this->Facebook_model->set_settings());
		//print_r($this->Facebook_model->fetch_settings());
	}
	
	function fetch(){
	    header('Content-Type: application/json');
	    echo json_encode($this->Db_model->b_fb_fetch('1443101719058431'));
	}
	
	function fetch_entity($apiai_id){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->fetch_entity($apiai_id));
	}
	
	function fetch_bootcamp($apiai_id){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->fetch_bootcamp($apiai_id));
	}
	
	function prep_bootcamp($pid){
		header('Content-Type: application/json');
		echo json_encode($this->Apiai_model->prep_bootcamp($pid));
	}
	
	
	
	
	
	
	function facebook_webhook(){
		
		/*
		 * 
		 * Used for all webhooks from facebook, including user messaging, delivery notifications, etc...
		 * 
		 * */
		
		
		//Facebook Webhook Authentication:
		$challenge = ( isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : null );
		$verify_token = ( isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : null );
		$website = $this->config->item('website');
		
		
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
		        'e_message' => 'facebook_webhook() Function missing either [object] or [entry] variable.',
		        'e_json' => json_encode($json_data),
		        'e_type_id' => 8, //Platform Error
		    ));
			return false;
		} elseif(!$json_data['object']=='page'){
		    $this->Db_model->e_create(array(
		        'e_message' => 'facebook_webhook() Function call object value is not equal to [page], which is what was expected.',
		        'e_json' => json_encode($json_data),
		        'e_type_id' => 8, //Platform Error
		    ));
			return false;
		}
		
		
		//Loop through entries:
		foreach($json_data['entry'] as $entry){
			
			//check the page ID:
			if(!isset($entry['id']) || $entry['id']!==$website['fb_page_id']){
			    $this->Db_model->e_create(array(
			        'e_message' => 'facebook_webhook() unrecognized page id ['.$entry['id'].'].',
			        'e_json' => json_encode($json_data),
			        'e_type_id' => 8, //Platform Error
			    ));
				continue;
			} elseif(!isset($entry['messaging'])){
			    $this->Db_model->e_create(array(
			        'e_message' => 'facebook_webhook() call missing messaging Array().',
			        'e_json' => json_encode($json_data),
			        'e_type_id' => 8, //Platform Error
			    ));
				continue;
			}

			//loop though the messages:
			foreach($entry['messaging'] as $im){
				
				if(isset($im['read'])){
					
					//This callback will occur when a message a page has sent has been read by the user.
				    $this->Db_model->e_create(array(
				        'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 1, //Message Read
				    ));
					
				} elseif(isset($im['delivery'])) {
					
					//This callback will occur when a message a page has sent has been delivered.
				    $this->Db_model->e_create(array(
				        'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 2, //Message Delivered
				    ));
					
				} elseif(isset($im['referral']) || isset($im['postback'])) {
					
					if(isset($im['postback'])) {
						
						/*
						 * Postbacks occur when a the following is tapped:
						 *
						 * - Postback button
						 * - Get Started button
						 * - Persistent menu item
						 *
						 * Learn more:
						 * 
						 *
						 * */
						
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
					
					
					$eng_data = array(
						'e_type_id' => (isset($im['referral']) ? 4 : 3), //Messenger Referral/Postback
						'e_json' => json_encode($json_data),
					);
					
					
					if($referral_array && isset($referral_array['ref']) && strlen($referral_array['ref'])>0){
						
						//We have referrer data, see what this is all about!
						//We expect an integer which is the challenge ID
						$ref_source = $referral_array['source'];
						$ref_type = $referral_array['type'];
						$ad_id = ( isset($referral_array['ad_id']) ? $referral_array['ad_id'] : null ); //Only IF user comes from the Ad
						//Referral key which currently equals the User ID
						$ref = explode('_',$referral_array['ref'],2);
						$u_key = $ref[1]; //TODO use to validate if authentic origin
						$eng_data['e_object_id'] = intval($ref[0]);
						

						if($eng_data['e_object_id']>0){
						    
						    //See if we can find a valid user with this account:
						    $matching_users = $this->Db_model->u_fetch(array(
						        'u_id' => $eng_data['e_object_id'],
						        'u_status >=' => 0,
						    ));
						    
						    $current_fb_users = $this->Db_model->u_fetch(array(
						        'u_id !=' => $eng_data['e_object_id'],
						        'u_fb_id' => $im['sender']['id'],
						        'u_status >=' => 0,
						    ));
						    
						    //Users can only activate their accounts if/when they are missing their u_fb_id field
						    //Did we find a single user without a linked Facebook account?
						    if(count($current_fb_users)>0){
						        
						        //This FB user is assigned to a different mench account, so we cannot activate them!
						        $this->Facebook_model->batch_messages( $im['sender']['id'] , array(
						            array('text' => 'I could not activate your Messenger account because your Messenger account is already associated with another Mench account. Contact our support team to resolve this issue.'),
						        ), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/);
						        
						        //Log engagement:
						        $this->Db_model->e_create(array(
						            'e_creator_id' => $eng_data['e_object_id'],
						            'e_message' => 'MenchBot failed to activate user because Messenger account is associated with another Mench account.',
						            'e_json' => json_encode($json_data),
						            'e_type_id' => 9, //Support Needing Graceful Errors
						        ));
						        
						    } elseif(count($matching_users)>0 && strlen($matching_users[0]['u_fb_id'])>1){
						        
						        //Found this user but they seem to already be activated.
						        //Lets see who is the Messenger account:
						        
						        if($matching_users[0]['u_fb_id']==$im['sender']['id']){
						            
						            //All good, already activated with same account
						            $this->Facebook_model->batch_messages( $im['sender']['id'] , array(
						                array('text' => 'I have already activated your Messenger account. You are good to go :)'),
						            ), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/);
						            
						        } else {
						            
						            //Ooops, Mench user seems to be activated with a different Messenger account!
						            $this->Facebook_model->batch_messages( $im['sender']['id'] , array(
						                array('text' => 'I could not activate your Messenger account because your Mench account is already activated with another Messenger account. Contact our support team to resolve this issue.'),
						            ), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/);
						            
						            //Log engagement:
						            $this->Db_model->e_create(array(
						                'e_creator_id' => $eng_data['e_object_id'],
						                'e_message' => 'MenchBot failed to activate user because Mench account is already activated with another Messenger account.',
						                'e_json' => json_encode($json_data),
						                'e_type_id' => 9, //Support Needing Graceful Errors
						            ));
						            
						        }
						        
						    } elseif(count($matching_users)>0){
						        
						        
						        /* *************************************
						         * Messenger Activation
						         * *************************************
						         */
						        
						        //Fetch their profile from Facebook:
						        $fb_profile = $this->Facebook_model->fetch_profile($im['sender']['id']);
						        
						        //Split locale into language and country
						        $locale = explode('_',$fb_profile['locale'],2);
						        
						        //Update necessary fields when Activating through Messenger:
						        $update_profile = array(
						            'u_fb_id'         => $im['sender']['id'],
						            'u_fname'         => ( strlen($matching_users[0]['u_fname'])<=0 ? $fb_profile['first_name'] : $matching_users[0]['u_fname'] ),
						            'u_lname'         => ( strlen($matching_users[0]['u_lname'])<=0 ? $fb_profile['last_name'] : $matching_users[0]['u_lname'] ),
						            'u_image_url'     => ( strlen($matching_users[0]['u_image_url'])<5 ? $fb_profile['profile_pic'] : $matching_users[0]['u_image_url'] ),
						            'u_status'        => ( $matching_users[0]['u_status']==0 ? 1 : $matching_users[0]['u_status'] ), //Activate their profile as well
						            'u_timezone'      => $fb_profile['timezone'],
						            'u_gender'        => strtolower(substr($fb_profile['gender'],0,1)),
						            'u_language'      => ( $matching_users[0]['u_language']=='en' && !($matching_users[0]['u_language']==$locale[0]) ? $locale[0] : $matching_users[0]['u_language'] ),
						            'u_country_code'  => $locale[1],
						        );
						        //Update their profile and link accounts:
						        $this->Db_model->u_update( $matching_users[0]['u_id'] , $update_profile );
						        
						        
						        //Search for possible Bootcamps:
						        $admissions = $this->Db_model->remix_admissions(array(
						            'ru.ru_u_id'	=> $matching_users[0]['u_id'],
						        ));
						        
						        
						        //Log Activation Engagement:
						        $this->Db_model->e_create(array(
						            'e_creator_id' => $matching_users[0]['u_id'],
						            'e_json' => json_encode(array(
						                'fb_webhook' => $json_data,
						                'fb_profile' => $fb_profile,
						                'u_update' => $update_profile,
						                'admissions' => $admissions,
						            )),
						            'e_type_id' => 31, //Messenger Activated
						            'e_object_id' => $matching_users[0]['u_id'],
						            'e_b_id' => (count($admissions)==1 ? $admissions[0]['b_id'] : 0),
						        ));
						        
						        
						        //Communicate the linking process with user:
						        $this->Facebook_model->batch_messages( $im['sender']['id'] , array(
						            array('text' => 'Hi '.$update_profile['u_fname'].' 👋'),
						            array('text' => 'My name is MenchBot and I will be your Personal Assistant to help you accomplish your bootcamp objective.'),
						            array('text' => 'As your personal assistant I will send you important updates'.( count($admissions)==1 ? ' on your bootcamp "'.$admissions[0]['c_objective'].'" lead by '.$admissions[0]['b__admins'][0]['u_fname'].' '.$admissions[0]['b__admins'][0]['u_lname'] : ' on your '.count($admissions).' enrolled bootcamps' ).'. I will also forward all your messages to your bootcamp\'s instructor team so they can reply asap'.( count($admissions)==1 ? ', usually within '.$admissions[0]['r_response_time_hours'].' hours ⚡' : '.' )),
						            array('text' => 'That\'s it for now. Click the "️🚩 Action Plan" button in the menu below to get started with your bootcamp.'),
						        ), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/);
						    }
						}
						
						
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
					
					//Update the user ID now as we might have linked them:
					$user_id = $im['sender']['id']; //Their facebook ID
					$u_id = $this->Db_model->u_fb_search($user_id); //Their Mench ID
					$eng_data['e_creator_id'] = $u_id; //Append to engagement data
					
					
					
					
					
					/*
					 * Start of Code Block #001
					 * ***************************
					 * Check User Admission Status
					 *
					 * Note: This Code Block is repeated
					 */
					$admissions = $this->Db_model->ru_fetch(array(
					    'r.r_status >='	   => 1, //Open for admission
					    'r.r_status <='	   => 2, //Running
					    'ru.ru_status >='  => 0, //Initiated or higher as long as bootcamp is running!
					    'ru.ru_u_id'	   => $u_id,
					));
					
					//Check to see which bootcamp, if any, is this student enrolled in:
					if(count($admissions)<=0){
					    
					    //None! Give students directions on how to enroll
					    $this->Facebook_model->batch_messages( $user_id , array(
					        array('text' => 'Hi there! You don\'t seem to be enrolled in a Mench bootcamp yet. You can join a Bootcamp using a private invitation URL. Visit us at https://mench.co to learn more.'),
					    ), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/);
					    
					    //Log engagement:
					    $this->Db_model->e_create(array(
					        'e_creator_id' => $u_id,
					        'e_message' => 'Received inbound message from a user that is not enrolled in a bootcamp. You can reply to them on MenchBot Facebook Inbox: https://www.facebook.com/menchbot/inbox/',
					        'e_json' => json_encode($json_data),
					        'e_type_id' => 9, //Support Needing Graceful Errors
					    ));
					    
					} elseif(count($admissions)>=2){
					    
					    //Ooops, how did they enroll in so many bootcamps?
					    $this->Facebook_model->batch_messages( $user_id , array(
					        array('text' => 'Error: You are somehow enrolled in multiple bootcamps. You can only enroll in 1 bootcamp at a time. I have notified Mench Admins to look into adjusting your application status and get back to you soon.'),
					    ), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/);
					    
					    //Log Engagement:
					    $this->Db_model->e_create(array(
					        'e_creator_id' => $u_id,
					        'e_message' => 'We Received inbound message from student enrolled in *multiple* bootcamps!',
					        'e_json' => json_encode($json_data),
					        'e_type_id' => 8, //Platform Error
					    ));
					    
					} else {
					    
					    //Append Bootcamp ID to engagement:
					    $eng_data['e_b_id'] = $admissions[0]['r_b_id'];
					    
					}
					/*
					 * End of Code Block #001
					 * ***************************
					 */
					
					
					
					
					
					
					//Log primary engagement:
					$this->Db_model->e_create($eng_data);
					
					
					
					
					
					
				} elseif(isset($im['optin'])) {
					
					//TODO Validate the ref ID and log error if not valid.
					//Decode ref variable intval($im['optin']['ref'])
					
					//Log engagement:
				    $this->Db_model->e_create(array(
				        'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 5, //Message Delivered
				    ));
					
				} elseif(isset($im['message_request']) && $im['message_request']=='accept') {
				    
				    //This is when we message them and they accept to chat because they had deleted MenchBot or something...
				    //TODO maybe later log an engagement
				    
				} elseif(isset($im['message'])) {
				    
					/*
					 * Triggered for both incoming and outgoing messages on behalf of our team
					 * 
					 * */
					
					//Set variables:
					$sent_from_us = ( isset($im['message']['is_echo']) ); //Indicates the message sent from the page itself
					$user_id = ( $sent_from_us ? $im['recipient']['id'] : $im['sender']['id'] );
					$u_id = $this->Db_model->u_fb_search($user_id);
					$page_id = ( $sent_from_us ? $im['sender']['id'] : $im['recipient']['id'] );
					
					
					
					//Start data reparation for message inbound OR outbound engagement:
					$eng_data = array(
					    'e_creator_id' => ( $sent_from_us ? 0 /* TODO replaced with chat widget EXCEPT FB admin Inbox */ : $u_id ),
						'e_json' => json_encode($json_data),
					    'e_message' => ( isset($im['message']['text']) ? $im['message']['text'] : null ),
					    'e_type_id' => ( $sent_from_us ? 7 : 6 ), //Message Sent/Received
					    'e_object_id' => ( $sent_from_us ? $u_id : 0 ),
					    'e_b_id' => ( count($admissions)==1 ? $admissions[0]['r_b_id'] : 0 ),
					);
					
					
					/*
					 * Start of Code Block #001
					 * ***************************
					 * Check User Admission Status
					 *
					 * Note: This Code Block is repeated
					 */
					$admissions = $this->Db_model->ru_fetch(array(
					    'r.r_status >='	   => 1, //Open for admission
					    'r.r_status <='	   => 2, //Running
					    'ru.ru_status >='  => 0, //Initiated or higher as long as bootcamp is running!
					    'ru.ru_u_id'	   => $u_id,
					));
					
					//Check to see which bootcamp, if any, is this student enrolled in:
					if(!$sent_from_us && count($admissions)<=0){
					    
					    //None! Give students directions on how to enroll
					    $this->Facebook_model->batch_messages( $user_id , array(
					        array('text' => 'Hi there! You don\'t seem to be enrolled in a Mench bootcamp yet. You can join a Bootcamp using a private invitation URL. Visit us at https://mench.co to learn more.'),
					    ), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/);
					    
					    //Log engagement:
					    $this->Db_model->e_create(array(
					        'e_creator_id' => $u_id,
					        'e_message' => 'Received inbound message from a user that is not enrolled in a bootcamp. You can reply to them on MenchBot Facebook Inbox: https://www.facebook.com/menchbot/inbox/',
					        'e_json' => json_encode($json_data),
					        'e_type_id' => 9, //Support Needing Graceful Errors
					    ));
					    
					} elseif(!$sent_from_us && count($admissions)>=2){
					    
					    //Ooops, how did they enroll in so many bootcamps?
					    $this->Facebook_model->batch_messages( $user_id , array(
					        array('text' => 'Error: You are somehow enrolled in multiple bootcamps. You can only enroll in 1 bootcamp at a time. I have notified Mench Admins to look into adjusting your application status and get back to you soon.'),
					    ), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/);
					    
					    //Log Engagement:
					    $this->Db_model->e_create(array(
					        'e_creator_id' => $u_id,
					        'e_message' => 'We Received inbound message from student enrolled in *multiple* bootcamps!',
					        'e_json' => json_encode($json_data),
					        'e_type_id' => 8, //Platform Error
					    ));
					    
					} else {
					    
					    //Append Bootcamp ID to engagement:
					    $eng_data['e_b_id'] = $admissions[0]['r_b_id'];
					    
					}
					/*
					 * End of Code Block #001
					 * ***************************
					 */
					
					//Some that are not used yet:
					$is_mench = 0; //TODO
					$metadata = ( isset($im['message']['metadata']) ? $im['message']['metadata'] : null ); //Send API custom string [metadata field]
					
					
					//Do some basic checks:
					if(!isset($im['message']['mid'])){
					    $this->Db_model->e_create(array(
					        'e_message' => 'facebook_webhook() Received message without Facebook Message ID.',
					        'e_json' => json_encode($json_data),
					        'e_type_id' => 8, //Platform Error
					    ));
					}
					
					//It may also have an attachment
					//https://developers.facebook.com/docs/messenger-platform/webhook-reference/message
					$new_file_url = null; //Would be updated IF message is a file
					if(isset($im['message']['attachments'])){
						//We have some attachments, lets loops through them:
						foreach($im['message']['attachments'] as $att){
							
							if(in_array($att['type'],array('image','audio','video','file'))){
								
							    //Indicate that we need to save this file on our servers:
							    $eng_data['e_file_save'] = 0;
							    //We do not save instantly as we need to respond to facebook's webhook call ASAP or else FB resend attachment!
								
							} elseif($att['type']=='location'){
								
								//Message with location attachment
								//TODO test to make sure this works!
								$loc_lat = $att['payload']['coordinates']['lat'];
								$loc_long = $att['payload']['coordinates']['long'];
								$eng_data['e_message'] .= (strlen($eng_data['e_message'])>0?"\n\n":'').'/attach location:'.$loc_lat.','.$loc_long;
								
							} elseif($att['type']=='template'){
								
								//Message with template attachment, like a button or something...
								$template_type = $att['payload']['template_type'];
								
							} elseif($att['type']=='fallback'){
								
								//A fallback attachment is any attachment not currently recognized or supported by the Message Echo feature.
								//We can ignore them for now :)
								
							} else {
								//This should really not happen!
							    $this->Db_model->e_create(array(
							        'e_message' => 'facebook_webhook() Received message with unknown attachment type ['.$att['type'].'].',
							        'e_json' => json_encode($json_data),
							        'e_type_id' => 8, //Platform Error
							    ));
							}
						}
					}
					
					//Log incoming engagement:
					$this->Db_model->e_create($eng_data);
					
					//TODO Implement automated responses later on using api.ai or some other NLP engine
					
				} else {
				    //This should really not happen!
				    $this->Db_model->e_create(array(
				        'e_message' => 'facebook_webhook() received unrecognized webhook call.',
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 8, //Platform Error
				    ));
				}
			}
		}
	}
	
	
	
	function paypal_webhook(){
	    //Called when the paypal payment is complete:
	    if(isset($_POST) && isset($_POST['payment_status']) && $_POST['payment_status']=='Completed' && isset($_POST['item_number']) && intval($_POST['item_number'])>0){
	        //Seems like a valid Paypal IPN Call:
	        //Fetch Enrollment row:
	        $enrollments = $this->Db_model->ru_fetch(array(
	            'ru.ru_id' => intval($_POST['item_number']),
	        ));
	        
	        if(count($enrollments)==1){
	            //Fetch class data to grab bootcamp ID:
	            $classes = $this->Db_model->r_fetch(array(
	                'r.r_id' => $enrollments[0]['ru_r_id'],
	            ));
	            
	            if(count($classes)==1){
	                
	                //Define numbers:
	                $amount = floatval(( $_POST['payment_gross']>$_POST['mc_gross'] ? $_POST['payment_gross'] : $_POST['mc_gross'] ));
	                $fee = floatval(( $_POST['payment_fee']>$_POST['mc_fee'] ? $_POST['payment_fee'] : $_POST['mc_fee'] ));
	                
	                //Insert transaction:
	                $transaction = $this->Db_model->t_create(array(
	                    't_ru_id' => $enrollments[0]['ru_id'],
	                    't_timestamp' => date("Y-m-d H:i:s"),
	                    't_creator_id' => $enrollments[0]['ru_u_id'],
	                    't_paypal_id' => $_POST['txn_id'],
	                    't_paypal_ipn' => json_encode($_POST),
	                    't_currency' => $_POST['mc_currency'],
	                    't_payment_type' => $_POST['payment_type'],
	                    't_total' => $amount,
	                    't_fees' => $fee,
	                ));
	                
	                //Update student's payment status:
	                $this->Db_model->ru_update( $enrollments[0]['ru_id'] , array(
	                    'ru_status' => 2, //For now this is the default since we don't accept partial payments
	                ));
	                
	                //Log Engagement
	                $this->Db_model->e_create(array(
	                    'e_creator_id' => $enrollments[0]['ru_u_id'],
	                    'e_message' => 'Received $'.$amount.' USD via PayPal.',
	                    'e_json' => json_encode($_POST),
	                    'e_type_id' => 30, //Paypal Payment
	                    'e_object_id' => $transaction['t_id'],
	                    'e_b_id' => $classes[0]['r_b_id'],
	                ));
	            }
	        }
	    }
	}	

	
/*
 * Sample api.ai Webhook call:

Array
(
    [originalRequest] => Array
        (
            [source] => facebook
            [data] => Array
                (
                    [sender] => Array
                        (
                            [id] => 1344093838979504
                        )

                    [recipient] => Array
                        (
                            [id] => 1782774501750818
                        )

                    [message] => Array
                        (
                            [mid] => mid.$cAAZVbKt7ywpil2wGeFcZibAMlNcz
                            [text] => hi
                            [seq] => 14551
                        )

                    [timestamp] => 1496362434168
                )

        )

    [id] => 7ac3054f-6fb0-4ca7-b6a8-ac7f44c7baf4
    [timestamp] => 2017-06-02T00:13:54.51Z
    [lang] => en
    [result] => Array
        (
            [source] => agent
            [resolvedQuery] => hi
            [speech] => 
            [action] => 
            [actionIncomplete] => 
            [parameters] => Array
                (
                )

            [contexts] => Array
                (
                    [0] => Array
                        (
                            [name] => generic
                            [parameters] => Array
                                (
                                    [facebook_sender_id] => 1344093838979504
                                )

                            [lifespan] => 4
                        )

                )

            [metadata] => Array
                (
                    [intentId] => 087e291c-8476-4782-b9ee-bc02cddea54a
                    [webhookUsed] => true
                    [webhookForSlotFillingUsed] => false
                    [intentName] => Introduce Us Bot
                )

            [fulfillment] => Array
                (
                    [speech] => 
                    [messages] => Array
                        (
                            [0] => Array
                                (
                                    [type] => 0
                                    [platform] => facebook
                                    [speech] => hi 👋
                                )

                            [1] => Array
                                (
                                    [type] => 0
                                    [platform] => facebook
                                    [speech] => My name is Us.
                                )

                            [2] => Array
                                (
                                    [type] => 0
                                    [platform] => facebook
                                    [speech] => I'm called Us, well, because my intelligence is fueled by a group of people that collect idea nuggets from world class entrepreneurs.
                                )

                            [3] => Array
                                (
                                    [type] => 0
                                    [platform] => facebook
                                    [speech] => Here is how it works: You subscribe to a topic (only topic for now is growing tech startup), and we send you relevant idea nuggets in the form of video, audio, text and image. We curated these insights form credible sources so you can learn faster. Interested? Type "start"
                                )
                        )
                )
            [score] => 1
        )

    [status] => Array
        (
            [code] => 200
            [errorType] => success
        )
    [sessionId] => be205d4d-852a-4dd5-9939-af0391c9ce93
)



Direct from api.ai:
Array
(
    [id] => 9183cfa8-cc84-42cc-9f1f-b1ea9900204b
    [timestamp] => 2017-06-02T00:13:03.213Z
    [lang] => en
    [result] => Array
        (
            [source] => agent
            [resolvedQuery] => stat startup
            [speech] => 
            [action] => ||56
            [actionIncomplete] => 
            [parameters] => Array
                (
                )

            [contexts] => Array
                (
                )

            [metadata] => Array
                (
                    [intentId] => 231ec0aa-ddab-4820-9bc3-a6a597fd623c
                    [webhookUsed] => true
                    [webhookForSlotFillingUsed] => false
                    [intentName] => Hypergrow a Startup
                )

            [fulfillment] => Array
                (
                    [speech] => 
                    [messages] => Array
                        (
                            [0] => Array
                                (
                                    [type] => 3
                                    [platform] => facebook
                                    [imageUrl] => http://www.quicksprout.com/images/startup.jpg
                                )

                            [1] => Array
                                (
                                    [type] => 0
                                    [platform] => facebook
                                    [speech] => Welome onboard! How often would you like to receive updates from Us?
                                )

                            [2] => Array
                                (
                                    [type] => 0
                                    [platform] => facebook
                                    [speech] => You can always type in specific topics that interest you the most, and we will auto subscribe you once those topics become available.
                                )

                            [3] => Array
                                (
                                    [type] => 0
                                    [platform] => facebook
                                    [speech] => Before you go, what is currently your biggest challenge in building your technology startup?
                                )
                        )
                )
            [score] => 0.43
        )

    [status] => Array
        (
            [code] => 200
            [errorType] => success
        )
    [sessionId] => c4e9fd9a-b1b9-4db4-bbd0-3e9b33c2697e
)
*/

	
	function apiai_webhook(){
		
		//This is being retired in favour of the new design to intake directly from Facebook 
		exit;
		//The main function to receive user message.
		//Facebook Messenger send the data to api.ai, they attempt to detect #intents/@entities.
		//And then they send the results to Us here.
		//Data from api.ai
		
		$json_data = json_decode(file_get_contents('php://input'), true);
		
		//See what we should respond to the user:
		$eng_data = array(
				'gem_id' => 0,
				'us_id' => 0, //Default api.ai API User, IF not with facebok
				'intent_pid' => ( substr_count($json_data['result']['action'],'pid')==1 ? intval(str_replace('pid','',$json_data['result']['action'])) : 0 ),
				'json_blob' => json_encode($json_data), //Dump all incoming variables
				'message' => $json_data['result']['resolvedQuery'],
				'seq' => 0, //No sequence if from api.ai
				'correlation' => ( isset($json_data['result']['score']) ? $json_data['result']['score'] : 1 ),
				'action_pid' => 928, //928 Read, 929 Write, 930 Subscribe, 931 Unsubscribe
		);
		
		//Is this message coming from Facebook? (Instead of api.ai console)
		if(isset($json_data['originalRequest']['source']) 
		&& $json_data['originalRequest']['source']=='facebook'){
			
			//This is from Facebook Messenger
			$fb_user_id = $json_data['originalRequest']['data']['sender']['id'];
			
			//Update engagement variables:
			$eng_data['seq'] 			= $json_data['originalRequest']['data']['message']['seq']; //Facebook message sequence
			$eng_data['message'] 		= $json_data['originalRequest']['data']['message']['text']; //Facebook message content
			
			
			if(strlen($fb_user_id)>0){
				
				//Indicate to the user that we're typing:
				$this->Facebook_model->send_message(array(
						'recipient' => array(
								'id' => $fb_user_id
						),
						'sender_action' => 'typing_on'
				));
				
				//We have a sender ID, see if this is registered using Facebook PSID

				
				if(count($matching_users)>0){
					
					//Yes, we found them!
					$eng_data['us_id'] = $matching_users[0]['node_id'];
					
					//TODO Check to see if this user is unsubscribed:
					
					
				} else {
					//This is a new user that needs to be registered!
				    $eng_data['e_creator_id'] = $this->Db_model->u_fb_create($fb_user_id);
					
					if(!$eng_data['us_id']){
						//There was an error fetching the user profile from Facebook:
						$eng_data['us_id'] = 765; //Use FB messenger
						//TODO Log error and look into this
					}
				}
				
				
				//Log incoming engagement
				
				//Fancy:
				//sleep(1);
				
				if(isset($unsubscribed_gem['id'])){
					//Oho! This user is unsubscribed, Ask them if they would like to re-join us:
					$response = array(
							'text' => 'You had unsubscribed from Us. Would you like to re-join?',
					);
				} else {
					//Now figure out the response:
				}
				
				//TODO: Log response engagement
				
				//Send message back to user:
				$this->Facebook_model->send_message(array(
						'recipient' => array(
								'id' => $fb_user_id
						),
						'message' => $response,
						'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
				));
			}
			
		} else {
			//TODO Log engagement
			
			//most likely this is the api.ai console.
			header('Content-Type: application/json');
			$chosen_reply = 'Testing intents on api.ai, huh? Currently we programmed to only respond in Facebook messanger directly!';
			echo json_encode(array(
					'speech' => $chosen_reply,
					'displayText' => $chosen_reply,
					'data' => array(), //Its only a text response
					'contextOut' => array(),
					'source' => "webhook",
			));
		}
	}
}
