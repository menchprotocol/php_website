<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bot extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
    
	function shervin($c_id,$depth){
	    //Message shervin as example:
	    echo_json(tree_message($c_id, $depth, '381488558920384', 1, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 0, 0));
	}
	
	function t(){
	    /*
	    echo_json($this->Db_model->c_fetch(array(
	        'c.c_id' => 917,
	    ) , 2 , array('i') ));
	    */
	}
	function set_settings($botkey){
	    echo_json($this->Facebook_model->set_settings($botkey));
	}
	function fetch_settings($botkey){
	    print_r($this->Facebook_model->fetch_settings($botkey));
	}	
	
	function send_message(){
	    
	    /*
	    if(!isset($_POST) || count($_POST)<1){
	        $_POST['b_id'] = 1;
	        $_POST['sender_u_id'] = 1;
	        $_POST['receiver_u_id'] = 1;
	        $_POST['message_type'] = 'text';
	        $_POST['auth_hash'] = md5( $_POST['sender_u_id'] . $_POST['receiver_u_id'] . $_POST['message_type'] . '7H6hgtgtfii87' );
	        $_POST['text_payload'] = 'Hi This is a Test...';
	    }
	    */
	    
	    //Auth user and check required variables:
	    if(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Bootcamp ID',
	        ));
	    } elseif(!isset($_POST['sender_u_id']) || intval($_POST['sender_u_id'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Sender/Instructor ID',
	        ));
	    } elseif(!isset($_POST['receiver_u_id']) || intval($_POST['receiver_u_id'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Receiver/Student ID',
	        ));
	    } elseif(!isset($_POST['message_type']) || !in_array($_POST['message_type'],array('text','audio','video','image','file'))){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Message Type',
	        ));
	    } elseif(!isset($_POST['auth_hash']) || !(md5( $_POST['sender_u_id'] . $_POST['receiver_u_id'] . $_POST['message_type'] . '7H6hgtgtfii87' ) == $_POST['auth_hash'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Auth Hash',
	        ));
	    } elseif($_POST['message_type']=='text' && (!isset($_POST['text_payload']) || strlen($_POST['text_payload'])<1)){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Text Payload',
	        ));
	    } elseif(in_array($_POST['message_type'],array('audio','video','image','file')) && !isset($_POST['attach_url'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Attachment URL',
	        ));	        
	    } else {
	        
	        //Fetch instructor/Bootcamp:
	        $fetch_instructors = $this->Db_model->ba_fetch(array(
	            'ba.ba_b_id' => intval($_POST['b_id']),
	            'ba.ba_u_id' => intval($_POST['sender_u_id']),
	            'ba.ba_status >=' => 0,
	            'u.u_status >=' => 0,
	        ));
	        
	        //Fetch Student:
	        $admissions = $this->Db_model->remix_admissions(array(
	            'ru.ru_u_id'	=> intval($_POST['receiver_u_id']),
	            'r.r_b_id'	    => intval($_POST['b_id']),
	        ));
	        
	        //Validate Student ID:
	        if(!(count($fetch_instructors)==1)){
	            echo_json(array(
	                'status' => 0,
	                'message' => 'Instructor Not Assigned to Bootcamp',
	            ));
	        } elseif(count($admissions)<=0){
	            echo_json(array(
	                'status' => 0,
	                'message' => 'Student Not Enrolled in Bootcamp',
	            ));
	        } elseif(count($admissions)>=2){
	            echo_json(array(
	                'status' => 0,
	                'message' => 'Student Enrolled On Mutiple Bootcamps',
	            ));
	        } elseif(strlen($admissions[0]['u_fb_id'])<5){
	            echo_json(array(
	                'status' => 0,
	                'message' => 'Student Not Activated Messenger Yet',
	            ));
	        } else {
	            
	            //Proceed to Send Message:
	            if($_POST['message_type']=='text'){
	                //Create Engagement message to be saved:
	                $e_message = $_POST['text_payload'];
	                $fb_message = array(
	                    'text' => $_POST['text_payload'],
	                    'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
	                );
	            } else {
	                //Create Engagement message to be saved:
	                $e_message = '/attach '.$_POST['message_type'].':'.trim($_POST['attach_url']);
	                $fb_message = array(
	                    'attachment' => array(
	                        'type' => $_POST['message_type'],
	                        'payload' => array(
	                            'url' => $_POST['attach_url'],
	                            'is_reusable' => false,
	                        ),
	                    ),
	                    'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
	                );
	            }
	            
	            //Send Message:
	            $this->Facebook_model->batch_messages( '381488558920384', $admissions[0]['u_fb_id'] , array($fb_message), 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/ );
	            
	            //Log Engagement:
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => intval($_POST['sender_u_id']),
	                'e_recipient_u_id' => intval($_POST['receiver_u_id']),
	                'e_message' => $e_message,
	                'e_json' => json_encode($_POST),
	                'e_type_id' => 7, //Outbound Message
	                'e_b_id' => $admissions[0]['b_id'],
	                'e_r_id' => $admissions[0]['r_id'],
	            ));
	            
	            //Show success:
	            echo_json(array(
	                'status' => 1,
	                'message' => 'Message sent',
	            ));
	            
	        }
	    }
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
		$mench_bots = $this->config->item('mench_bots');
		
		
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
		    if(!isset($entry['id']) || !array_key_exists($entry['id'],$mench_bots)){
			    $this->Db_model->e_create(array(
			        'e_message' => 'facebook_webhook() unrecognized page/bot id ['.$entry['id'].'].',
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
				        'e_initiator_u_id' => $this->Db_model->activate_bot($entry['id'], $im['sender']['id'], null),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 1, //Message Read
				    ));
					
				} elseif(isset($im['delivery'])) {
					
					//This callback will occur when a message a page has sent has been delivered.
				    $this->Db_model->e_create(array(
				        'e_initiator_u_id' => $this->Db_model->activate_bot($entry['id'], $im['sender']['id'], null),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 2, //Message Delivered
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
					
					$eng_data = array(
						'e_type_id' => (isset($im['referral']) ? 4 : 3), //Messenger Referral/Postback
						'e_json' => json_encode($json_data),
					    'e_initiator_u_id' => $this->Db_model->activate_bot($entry['id'], $im['sender']['id'], $ref),
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
					
					
					if($eng_data['e_initiator_u_id'] && $entry['id']=='381488558920384'){
					    //See if this student has any admissions:
					    $admissions = $this->Db_model->ru_fetch(array(
					        'r.r_status >='	   => 1, //Open for admission
					        'r.r_status <='	   => 2, //Running
					        'ru.ru_status >='  => 0, //Initiated or higher as long as bootcamp is running!
					        'ru.ru_u_id'	   => $eng_data['e_initiator_u_id'],
					    ));
					    if(count($admissions)>0){
					        //Append Bootcamp & Class ID to engagement:
					        $eng_data['e_b_id'] = $admissions[0]['r_b_id'];
					        $eng_data['e_r_id'] = $admissions[0]['r_id'];
					    }
					}
					
					//Log primary engagement:
					$this->Db_model->e_create($eng_data);
					
				} elseif(isset($im['optin'])) {
					
					//TODO Validate the ref ID and log error if not valid.
					//Decode ref variable intval($im['optin']['ref'])
					
					//Log engagement:
				    $this->Db_model->e_create(array(
				        'e_initiator_u_id' => $this->Db_model->activate_bot($entry['id'], $im['sender']['id'], null),
				        'e_json' => json_encode($json_data),
				        'e_type_id' => 5, //Messenger Optin
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
					$u_id = $this->Db_model->activate_bot($entry['id'], $user_id, null);
					$page_id = ( $sent_from_us ? $im['sender']['id'] : $im['recipient']['id'] );
					$metadata = ( isset($im['message']['metadata']) ? $im['message']['metadata'] : null ); //Send API custom string [metadata field]
					
					
					if($metadata=='system_logged'){
					    //This is already logged! No need to take further action!
					    json_encode(array('complete'=>'yes'));
					    return false;
					    exit; //This should not trigger?! Not sure...
					}
					
					
					//Start data reparation for message inbound OR outbound engagement:
					$eng_data = array(
					    'e_initiator_u_id' => ( $sent_from_us ? 0 /* TODO replaced with chat widget EXCEPT FB admin Inbox */ : $u_id ),
						'e_json' => json_encode($json_data),
					    'e_message' => ( isset($im['message']['text']) ? $im['message']['text'] : null ),
					    'e_type_id' => ( $sent_from_us ? 7 : 6 ), //Message Sent/Received
					    'e_recipient_u_id' => ( $sent_from_us ? $u_id : 0 ),
					    'e_b_id' => 0, //Default, may be updated later...
					    'e_r_id' => 0, //Default, may be updated later...
					);
					
					
					if($u_id){
					    
					    //Fetch for admission to append to this message:
					    $admissions = $this->Db_model->ru_fetch(array(
					        'r.r_status >='	   => 1, //Open for admission
					        'r.r_status <='	   => 2, //Running
					        'ru.ru_status >='  => 0, //Initiated or higher as long as bootcamp is running!
					        'ru.ru_u_id'	   => $u_id,
					    ));
					    
					    if(isset($admissions[0])){
					        
					        //Append to engagement data:
					        $eng_data['e_b_id'] = $admissions[0]['r_b_id'];
					        $eng_data['e_r_id'] = $admissions[0]['r_id'];
					        
					    } else {
					        
					        //Fetch user details to see if they are admin:
					        $matching_users = $this->Db_model->u_fetch(array(
					            'u_id' => $ref_u_id,
					        ));
					        
					        if($matching_users[0]['s_status']>=2){
					            //Is admin
					            //Log engagement to give extra care:
					            $this->Db_model->e_create(array(
					                'e_initiator_u_id' => $u_id,
					                'e_message' => 'Received inbound message from an instructor. You can reply to them on MenchBot Facebook Inbox: https://www.facebook.com/menchbot/inbox/',
					                'e_json' => json_encode($json_data),
					                'e_type_id' => 9, //Support Needing Graceful Errors
					            ));
					        } else {
					            //Non Verified Guest
					            //Log engagement to give extra care:
					            $this->Db_model->e_create(array(
					                'e_initiator_u_id' => $u_id,
					                'e_message' => 'Received inbound message from a student that is not enrolled in a bootcamp. You can reply to them on MenchBot Facebook Inbox: https://www.facebook.com/menchbot/inbox/',
					                'e_json' => json_encode($json_data),
					                'e_type_id' => 9, //Support Needing Graceful Errors
					            ));
					        }   
					    }
					}
					
					//It may also have an attachment
					if(isset($im['message']['attachments'])){
						//We have some attachments, lets loops through them:
						foreach($im['message']['attachments'] as $att){
							
							if(in_array($att['type'],array('image','audio','video','file'))){
								
							    //Indicate that we need to save this file on our servers:
							    $eng_data['e_cron_job'] = 0;
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
	                    'e_initiator_u_id' => $enrollments[0]['ru_u_id'],
	                    'e_message' => 'Received $'.$amount.' USD via PayPal.',
	                    'e_json' => json_encode($_POST),
	                    'e_type_id' => 30, //Paypal Payment
	                    'e_b_id' => $classes[0]['r_b_id'],
	                    'e_r_id' => $classes[0]['r_id'],
	                    'e_t_id' => $transaction['t_id'],
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
                                    [speech] => Here is how it works: You subscribe to a topic (only topic for now is growing tech startup), and we send you relevant idea nuggets in the form of video, audio, text and image. We curated these messages form credible sources so you can learn faster. Interested? Type "start"
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
				
				//Indicate to the user that we're typing...
				//We have a sender ID, see if this is registered using Facebook PSID

				
				
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
				
				//Send message back to user...
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
