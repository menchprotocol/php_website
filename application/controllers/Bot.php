<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function t(){
		print_r($this->Facebook_model->set_settings());
		//print_r($this->Facebook_model->fetch_settings());
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
			log_error('Facebook webhook call missing either object/entry variables.',$json_data,2);
			return false;
		} elseif(!$json_data['object']=='page'){
			log_error('Facebook webhook call object value is not equal to [page], which is what was expected.',$json_data,2);
			return false;
		}
		
		
		//Loop through entries:
		foreach($json_data['entry'] as $entry){
			
			//check the page ID:
			if(!isset($entry['id']) || $entry['id']!==$website['fb_page_id']){
				log_error('Facebook webhook call with unknown page id ['.$entry['id'].'].',$json_data,2);
				continue;
			} elseif(!isset($entry['messaging'])){
				log_error('Facebook webhook call without the Messaging Array().',$json_data,2);
				continue;
			}

			//loop though the messages:
			foreach($entry['messaging'] as $im){
				
				if(isset($im['read'])){
					
					//This callback will occur when a message a page has sent has been read by the user.
					//https://developers.facebook.com/docs/messenger-platform/webhook-reference/message-read
					//The watermark field is used to determine which messages were read.
					//It represents a timestamp indicating that all messages with a timestamp before watermark were read by the recipient.
					$this->Db_model->log_engagement(array(
							'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
							'e_medium_id' => 2, //Messenger Bot
							'e_medium_action_id' => 1, //read
							'e_json' => json_encode($json_data),
					));
					
				} elseif(isset($im['delivery'])) {
					
					//This callback will occur when a message a page has sent has been delivered.
					//https://developers.facebook.com/docs/messenger-platform/webhook-reference/message-delivered
					$this->Db_model->log_engagement(array(
							'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
							'e_medium_id' => 2, //Messenger Bot
							'e_medium_action_id' => 2, //delivery
							'e_json' => json_encode($json_data),
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
							/*
							 * https://developers.facebook.com/docs/messenger-platform/webhook-reference/postback
							 *
							 * This section is present only if:
							 *
							 * - The user entered the thread via an m.me link with a ref parameter and tapped the Get Started button.
							 * - The user entered the thread by scanning a parametric Messenger Code and tapped the Get Started button.
							 * - This is the first postback after user came from a Messenger Conversation Ad.
							 * - The user entered the thread via Discover tab and tapped the Get Started button. See here for more info.
							 *
							 * The information contained in this section follows that of the referral webhook.
							 *
							 * */
							$referral_array = $im['postback']['referral'];
						} else {
							//Postback without referral!
							$referral_array = null;
						}
						
					} elseif(isset($im['referral'])) {
						
						/*
						 * This callback will occur when the user already has a thread with the
						 * bot and user comes to the thread from:
						 *
						 *  - Following an m.me link with a referral parameter
						 *  - Clicking on a Messenger Conversation Ad
						 *  - Scanning a parametric Messenger Code.
						 *
						 *  Learn more:
						 *  https://developers.facebook.com/docs/messenger-platform/webhook-reference/referral
						 *
						 * */
						
						$referral_array = $im['referral'];
					}
					
					
					
					
					$eng_data = array(
							'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
							'e_medium_id' => 2, //Messenger Bot
							'e_medium_action_id' => (isset($im['referral']) ? 4 : 3), //referral or postback
							'e_json' => json_encode($json_data),
					);
					
					
					if($referral_array && isset($referral_array['ref']) && strlen($referral_array['ref'])>0){
						
						//We have referrer data, see what this is all about!
						//We expect an integer which is the challenge ID
						$ref_source = $referral_array['source'];
						$ref_type = $referral_array['type'];
						$ad_id = ( isset($referral_array['ad_id']) ? $referral_array['ad_id'] : null ); //Only IF user comes from the Ad
						
						//TODO Validate ref ID
						
						if(count($challenges)==1){
							//We found this!
							//Decode ref variable:
						    $eng_data['e_obj_type'] = 'c';
						    $eng_data['e_obj_id'] = $challenges[0]['c_id'];
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
					
					//General variables:
					$this->Db_model->log_engagement($eng_data);
					
					//TODO implement some response?
					
				} elseif(isset($im['optin'])) {
					
					/*
					 * This callback will occur when the Send to Messenger plugin has been tapped, 
					 * or when a user has accepted a message request using Customer Matching.
					 * 
					 * https://developers.facebook.com/docs/messenger-platform/webhook-reference/optins
					 * 
					 * 
					 * */
					
					//This parameter is set by the data-ref field on the "Send to Messenger" plugin.
					//This field can be used by the developer to associate a click event on the plugin with a callback.
					$eng_data = array(
							'e_creator_id' => $this->Db_model->u_fb_search($im['sender']['id']),
							'e_medium_id' => 2, //Messenger Bot
							'e_medium_action_id' => 5, //optin
							'e_json' => json_encode($json_data),
					);
					
					
					//TODO Validate the ref ID and log error if not valid.
					//Decode ref variable intval($im['optin']['ref'])
					
					//Log engagement:
					$new = $this->Db_model->log_engagement($eng_data);
					
				} elseif(isset($im['message'])) {
					
					/*
					 * Triggered for both incoming and outgoing messages on behalf of our team
					 * 
					 * */
					
					//Set variables:
					$sent_from_us = ( isset($im['message']['is_echo']) ); //Indicates the message sent from the page itself
					$user_id = ( $sent_from_us ? $im['recipient']['id'] : $im['sender']['id'] );
					$page_id = ( $sent_from_us ? $im['sender']['id'] : $im['recipient']['id'] );
					
					$eng_data = array(
							'e_creator_id' => ( $sent_from_us ? 0 : $this->Db_model->u_fb_search($im['sender']['id'])),
							'e_medium_id' => 2, //Messenger Bot
							'e_medium_action_id' => ( $sent_from_us ? 7 : 6 ), //Inbound or Outbound Message
							'e_json' => json_encode($json_data),
							'e_message' => ( isset($im['message']['text']) ? $im['message']['text'] : '' ),
					);					
					
					//Some that are not used yet:
					$is_mench = 0; //TODO
					$metadata = ( isset($im['message']['metadata']) ? $im['message']['metadata'] : null ); //Send API custom string [metadata field]
					
					if($metadata=='SKIP_ECHO_LOGGING'){
						//We've been asked to skip this error logging!
						continue;
					}
					
					//Do some checks:
					if(!isset($im['message']['mid'])){
						log_error('Received message without Facebook Message ID!',$json_data,2);
					}
					
					//It may also have an attachment
					//https://developers.facebook.com/docs/messenger-platform/webhook-reference/message
					//https://developers.facebook.com/docs/messenger-platform/webhook-reference/message-echo
					$new_file_url = null; //Would be updated IF message is a file
					if(isset($im['message']['attachments'])){
						//We have some attachments, lets loops through them:
						foreach($im['message']['attachments'] as $att){
							
							if(in_array($att['type'],array('image','audio','video','file'))){
								
								//Store to local DB:
								$new_file_url = save_file($att['payload']['url'],$json_data);
								
								//Message with image attachment
								$eng_data['e_message'] .= (strlen($eng_data['e_message'])>0?"\n\n":'').'/attach '.$att['type'].':'.$new_file_url;
								
								/*
								//Reply:
								$this->Facebook_model->send_message(array(
										'recipient' => array(
												'id' => $user_id
										),
										'sender_action' => 'typing_on'
								));
								
								//Testing for now:
								$this->Facebook_model->send_message(array(
										'recipient' => array(
												'id' => $user_id
										),
										'message' => array(
												'text' => 'Got your messageand will get back to you soon!',
										),
										'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
								));
								*/
								
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
								log_error('Received Facebook message with unknown attachment type ['.$att['type'].']',$json_data,2);
							}
						}
					}
					
					
					
					/*
					$quick_reply_pid = null;
					if(isset($im['message']['quick_reply']) && isset($im['message']['quick_reply']['payload']) && intval($im['message']['quick_reply']['payload'])>0){
						//This message has a quick reply in it:
						$quick_reply_pid = fetch_grandchild( $im['message']['quick_reply']['payload'] , 3 , $json_data);
						
						//Lets expand the scope of $eng_data['intent_pid'] & have it store this PID
						$eng_data['intent_pid'] = $quick_reply_pid;
						
						//TODO Lets see what type of pattern is this?
						//IF grandpa_id=3, then > $eng_data['intent_pid'] = $quick_reply_pid;
						
					}
					
					//Do we need to detect any commands and auto-reply?
					$mench_command_pid = null;
					if($is_mench && !$quick_reply_pid && strlen($eng_data['message'])>0){
						//Attempt detecting possible coach-related commands in chat:
						$mench_command_pid = $this->run_mench_commands($eng_data['us_id'],$user_id,$eng_data['message']);
						
						$eng_data['intent_pid'] = $mench_command_pid;
					}
					*/
					
					//Log incoming engagement:
					$this->Db_model->log_engagement($eng_data);
					
					
					//Should we start talking?!
					if(0 && !$sent_from_us && !isset($im['message']['attachments']) && strlen($eng_data['e_message'])>0){
						
						//TODO disabled for now, build later
						//Incoming text message, attempt to auto detect it:
						//$eng_data['gem_id'] = ''; //If intent was found, the update ID that was served
						
						//Indicate to the user that we're typing:
						
						
						if(isset($unsubscribed_gem['id'])){
							//Oho! This user is unsubscribed, Ask them if they would like to re-join us:
							$response = array(
								'text' => 'You had unsubscribed from Us. Would you like to re-join?',
							);
						} else {
							//TODO Now figure out the response
						}
						 
						//Send message back to user:
						$this->Facebook_model->send_message(array(
								'recipient' => array(
										'id' => $user_id
								),
								'message' => $response,
								'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
						));
						
						
						//TODO Log outgoing message:
						//$eng_data = array();
						//$new_out = $this->Us_model->log_engagement($eng_data);
					}
					
				} else {
					log_error('We received an unrecognized Facebook webhook call.',$json_data,2);
				}
			}
		}
	}
	
	
	
	function run_mench_commands($from_us_id,$from_fb_id,$message){
		
		//Cleanup the message:
		$message = trim(strtolower($message));
		
		//See if we can detect commands from this message:
		if($message=='help' || substr($message,0,5)=='/help'){
			
			//We have a help menu for our coaches:
			quick_message($from_fb_id,'Here are the commands that help you as a coach:
					
/send - Allows you to send messages to your student entrepenuers like: /send last 3 to Miguel Hernandez');
			
			return 1104; //help command PID
			
		} elseif(substr($message,0,6)=='/send '){
			
			/*
			 * Used by Menches when they want to forward 1 or more messages to their clients.
			 *
			 * Format: [send] [last 4] [to] [Soroush Babaeian, Miguel Hernandez]
			 *
			 * [send] Required, activates command
			 * [last 4] Optional, default is [last 1], if detected would search for last X messages sent to AskMench from Coach
			 * [to] Required, de
			 * [Soroush Babaeian] Required, comma separated full name of individuals that the message should be forwarded to!
			 *
			 * */
			
			if(substr_count($message,' to ')==1){
				
				//Awesome, we found the second required element:
				$temp = explode(' to ',$message,2);
				$people = explode(',',$temp[1]);
				
				//Now see if we can find the recipient(s):
				$valid_users = array();
				foreach($people as $full_name){
				    $fb_user_id = $this->Db_model->u_fb_fetch($full_name);
					if(strlen($fb_user_id)>10){
						//Found it! lets add them to the list!
						array_push($valid_users,$fb_user_id);
					} else {
						//Empty all results and return error:
						unset($valid_users);
						$valid_users = array();
						quick_message($from_fb_id,'Error: "'.$full_name.'" is not a valid user with an active Facebook id. Command suspended; you may try again.');
						break;
					}
				}
				
				if(count($valid_users)>0){
					//We're ready to dispatch the last X messages to these users!
					//How many messages are we sending over?
					$last_msgs = 1; //Default
					if(substr_count($temp[0],'last ')==1){
						$temp2 = explode('last ',$temp[0],2);
						$last_msgs = ( intval($temp2[1])>1 ? intval($temp2[1]) : 1 );
					}
					
					//Go through the messages and see what we find:
					$msgs = $this->Us_model->fetch_recent_messages($from_us_id,$last_msgs);
					if(count($msgs)==$last_msgs){
						
						//All seems good, lets move on and send all messages to all users:
						$success_count = 0; 
						foreach($valid_users as $fb_user_id){
							foreach($msgs as $msg){
								//TODO: Don't send using quick_message(), instead send directly with meta_data and log engagement directly!
								if(quick_message($fb_user_id,$msg['message'])){
									$success_count++;
								} else {
									//Ooops, this should not happen
									log_error('FB Message "'.$msg.'" could not be sent to user ID "'.$fb_user_id.'" using the quick_message() function.',null,2);
								}
							}
						}
						
						//Notify Mench about the status of this command:
						quick_message($from_fb_id, 'Success: '.$success_count.' messages sent to '.count($valid_users).' recipient(s).');
						
					} else {
						//Was not able to find the number of sent messages as requested! Abandon...
						quick_message($from_fb_id,'Error: Could not locate your '.$last_msgs.' most recent message(s). Command suspended; you may try again.');
					}
				}
				
			} else {
				//Ooops, this is clearly an issue!
				quick_message($from_fb_id,'Error: /send command requires " to " followed by the full recipient name.');
			}
			
			return 1105; //send command PID
		}
	}
	
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
				$matching_users = $this->Us_model->search_node($fb_user_id,1024);
				
				if(count($matching_users)>0){
					
					//Yes, we found them!
					$eng_data['us_id'] = $matching_users[0]['node_id'];
					
					//TODO Check to see if this user is unsubscribed:
					//$unsubscribed_gem = $this->Us_model->fetch_sandwich_node($eng_data['us_id'],845);
					
					
				} else {
					//This is a new user that needs to be registered!
				    $eng_data['e_creator_id'] = $this->Db_model->u_fb_create($fb_user_id);
					
					if(!$eng_data['us_id']){
						//There was an error fetching the user profile from Facebook:
						$eng_data['us_id'] = 765; //Use FB messenger
						//TODO Log error and look into this
					}
				}
				
				
				//Log incoming engagement:
				$new = $this->Us_model->log_engagement($eng_data);
				
				//Fancy:
				//sleep(1);
				
				if(isset($unsubscribed_gem['id'])){
					//Oho! This user is unsubscribed, Ask them if they would like to re-join us:
					$response = array(
							'text' => 'You had unsubscribed from Us. Would you like to re-join?',
					);
				} else {
					//Now figure out the response:
					$response = $this->Us_model->generate_response($eng_data['intent_pid'],$setting);
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
			//Log engagement:
			$new = $this->Us_model->log_engagement($eng_data);
			
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
