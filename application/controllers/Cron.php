<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);

        //Example: /usr/bin/php /home/ubuntu/mench-web-app/index.php cron save_profile_pic
	}

	function go(){
        echo_json($this->Db_model->w_create(array(
            'w_c_id' => 6623,
            'w_outbound_u_id' => 1,
        )));
    }

    function intent_sync($c_id=7240,$update_c_table=1){
        //Cron Settings: 31 * * * *
	    //Syncs intents with latest caching data:
        echo_json($this->Db_model->c_recursive_fetch($c_id, true, $update_c_table));
    }

    function algolia_sync($obj,$obj_id=0){
        echo_json($this->Db_model->algolia_sync($obj,$obj_id));
    }

    function list_duplicate_cs(){

        $q = $this->db->query('select c1.* from v5_intents c1 where (select count(*) from v5_intents c2 where c2.c_outcome = c1.c_outcome) > 1 ORDER BY c1.c_outcome ASC');
        $duplicates = $q->result_array();


        $prev_title = null;
        foreach($duplicates as $c){
            if($prev_title!=$c['c_outcome']){
                echo '<hr />';
                $prev_title = $c['c_outcome'];
            }

            echo '<a href="/intents/'.$c['c_id'].'">#'.$c['c_id'].'</a> '.$c['c_outcome'].'<br />';
        }
    }

    function list_duplicate_us(){

        $q = $this->db->query('select u1.* from v5_entities u1 where (select count(*) from v5_entities u2 where u2.u_full_name = u1.u_full_name) > 1 ORDER BY u1.u_full_name ASC');
        $duplicates = $q->result_array();


        $prev_title = null;
        foreach($duplicates as $u){
            if($prev_title!=$u['u_full_name']){
                echo '<hr />';
                $prev_title = $u['u_full_name'];
            }

            echo '<a href="/entities/'.$u['u_id'].'">#'.$u['u_id'].'</a> '.$u['u_full_name'].'<br />';
        }
    }


    function e_score_recursive($u=array()){

        //Updates u__e_score based on number/value of connections to other intents/entities
        //Cron Settings: 2 * * * 30

        //Define weights:
        $score_weights = array(
            'u__outbounds' => 0, //Child entities are just containers, no score on the link

            'e_outbound_u_id' => 1, //Engagement initiator
            'e_inbound_u_id' => 1, //Engagement recipient

            'x_inbound_u_id' => 5, //URL Creator
            'x_outbound_u_id' => 8, //URL Referenced to them

            'w_outbound_u_id' => 13, //Subscriptions
            'c_inbound_u_id' => 21, //Active Intents
            't_inbound_u_id' => 55, //Transactions
        );

        //Fetch child entities:
        $entities = $this->Db_model->ur_outbound_fetch(array(
            'ur_inbound_u_id' => ( count($u)>0 ? $u['u_id'] : 2738 /* Parent Entity */ ),
            'ur_status >=' => 0, //Pending or Active
            'u_status >=' => 0, //Pending or Active
        ));

        //Recursively loops through child entities:
        $score = 0;
        foreach($entities as $u_child){
            //Addup all child sores:
            $score += $this->e_score_recursive($u_child);
        }

        //Anything to update?
        if(count($u)>0){

            //Update this row:
            $score += count($entities) * $score_weights['u__outbounds'];

            $score += count($this->Db_model->e_fetch(array(
                    'e_outbound_u_id' => $u['u_id'],
                ), 5000)) * $score_weights['e_outbound_u_id'];
            $score += count($this->Db_model->e_fetch(array(
                    'e_inbound_u_id' => $u['u_id'],
                ), 5000)) * $score_weights['e_inbound_u_id'];

            $score += count($this->Db_model->x_fetch(array(
                    'x_status >' => 0,
                    'x_outbound_u_id' => $u['u_id'],
                ))) * $score_weights['x_outbound_u_id'];
            $score += count($this->Db_model->x_fetch(array(
                    'x_status >' => 0,
                    'x_inbound_u_id' => $u['u_id'],
                ))) * $score_weights['x_inbound_u_id'];

            $score += count($this->Db_model->c_fetch(array(
                    'c_inbound_u_id' => $u['u_id'],
                ))) * $score_weights['c_inbound_u_id'];
            $score += count($this->Db_model->w_fetch(array(
                    'w_outbound_u_id' => $u['u_id'],
                ))) * $score_weights['w_outbound_u_id'];
            $score += count($this->Db_model->t_fetch(array(
                    't_inbound_u_id' => $u['u_id'],
                ))) * $score_weights['t_inbound_u_id'];

            //Update the score:
            $this->Db_model->u_update( $u['u_id'] , array(
                'u__e_score' => $score,
            ));

            //return the score:
            return $score;

        }
    }



    function message_drip(){

        exit; //Logic needs updating

        //Cron Settings: */5 * * * *

        //Fetch pending drips
        $e_pending = $this->Db_model->e_fetch(array(
            'e_status' => 0, //Pending
            'e_inbound_c_id' => 52, //Scheduled Drip e_inbound_c_id=52
            'e_timestamp <=' => date("Y-m-d H:i:s" ), //Message is due
            //Some standard checks to make sure, these should all be true:
            'e_outbound_u_id >' => 0,
            'e_outbound_c_id >' => 0,
        ), 200, array('ej'));


        //Lock item so other Cron jobs don't pick this up:
        lock_cron_for_processing($e_pending);


        $drip_sent = 0;
        foreach($e_pending as $e_text_value){

            //Fetch user data:
            $matching_subscriptions = $this->Db_model->w_fetch(array(
                'ru_outbound_u_id' => $e_text_value['e_outbound_u_id'],
                'ru_status >=' => 4, //Active student
                'r_status' => 2, //Running Class
            ));

            if(count($matching_subscriptions)>0){

                //Prepare variables:
                $json_data = unserialize($e_text_value['ej_e_blob']);

                //Send this message:
                $this->Comm_model->send_message(array(
                    array_merge($json_data['i'], array(
                        'e_inbound_u_id' => 0,
                        'e_outbound_u_id' => $matching_subscriptions[0]['u_id'],
                        'i_outbound_c_id' => $json_data['i']['i_outbound_c_id'],
                    )),
                ));

                //Update Engagement:
                $this->Db_model->e_update( $e_text_value['e_id'] , array(
                    'e_status' => 1, //Mark as done
                ));

                //Increase counter:
                $drip_sent++;
            }
        }

        //Echo message for cron job:
        echo $drip_sent.' Drip messages sent';

    }


    function save_profile_pic(){


        $max_per_batch = 20; //Max number of scans per run

        $e_pending = $this->Db_model->e_fetch(array(
            'e_status' => 0, //Pending
            'e_inbound_c_id' => 7001, //Cover Photo Save
        ), $max_per_batch);


        //Lock item so other Cron jobs don't pick this up:
        lock_cron_for_processing($e_pending);


        $counter = 0;
        foreach($e_pending as $u){

            //Check URL and validate:
            $error_message = null;
            $curl = curl_html($u['e_text_value'],true);

            if(!$curl){
                $error_message = 'Invalid URL (start with http:// or https://)';
            } elseif($curl['url_is_broken']) {
                $error_message = 'URL Seems broken with http code ['.$curl['httpcode'].']';
            } elseif($curl['x_type']!=4) {
                $error_message = 'URL [Type '.$curl['x_type'].'] Does not point to an image';
            }

            if(!$error_message){

                //Save the file to S3
                $new_file_url = save_file($u['e_text_value'],$u);

                if(!$new_file_url){
                    $error_message = 'Failed to upload the file to Mench CDN';
                }

                //Check to make sure this is not a Generic FB URL:
                foreach(array(
                            'ecd274930db69ba4b2d9137949026300',
                            '5bf2d884209d168608b02f3d0850210d',
                            'b3575aa3d0a67fb7d7a076198b442b93',
                            'e35cf96f814f6509d8a202efbda18d3c',
                            '5d2524cb2bdd09422832fa2d25399049',
                            '164c8275278f05c770418258313fb4f4',
                            '',
                        ) as $generic_url){
                    if(substr_count($new_file_url,$generic_url)>0){
                        //This is the hashkey for the Facebook Generic User icon:
                        $error_message = 'This is the user generic icon on Facebook';
                        break;
                    }
                }

                if(!$error_message){

                    //Save URL:
                    $new_x = $this->Db_model->x_create(array(
                        'x_inbound_u_id' => $u['u_id'],
                        'x_outbound_u_id' => $u['u_id'],
                        'x_url' => $new_file_url,
                        'x_clean_url' => $new_file_url,
                        'x_type' => 4, //Image
                    ));

                    //Replace cover photo only if this user has no cover photo set:
                    if(!(intval($u['u_cover_x_id'])>0)){

                        //Update Cover ID:
                        $this->Db_model->u_update( $u['u_id'] , array(
                            'u_cover_x_id' => $new_x['x_id'],
                        ));

                        //Log engagement:
                        $this->Db_model->e_create(array(
                            'e_inbound_u_id' => $u['u_id'],
                            'e_outbound_u_id' => $u['u_id'],
                            'e_inbound_c_id' => 12, //Account Update
                            'e_text_value' => 'Profile cover photo updates from Facebook Image ['.$u['e_text_value'].'] to Mench CDN ['.$new_file_url.']',
                            'e_x_id' => $new_x['x_id'],
                        ));
                    }
                }
            }

            //Update engagement:
            $this->Db_model->e_update( $u['e_id'] , array(
                'e_text_value' => ( $error_message ? 'ERROR: '.$error_message : 'Success' ).' (Original Image URL: '.$u['e_text_value'].')',
                'e_status' => 1, //Done
            ));

        }

        echo_json($e_pending);
    }

    function message_file_save(){

        //Cron Settings: * * * * *

        /*
         * This cron job looks for all engagements with Facebook attachments
         * that are pending upload (i.e. e_status=0) and uploads their
         * attachments to amazon S3 and then changes status to e_status=1
         *
         */

        $max_per_batch = 10; //Max number of scans per run

        $e_pending = $this->Db_model->e_fetch(array(
            'e_status' => 0, //Pending file upload to S3
            'e_inbound_c_id IN (6,7)' => null, //Sent/Received messages
        ), $max_per_batch, array('ej'));


        //Lock item so other Cron jobs don't pick this up:
        lock_cron_for_processing($e_pending);


        $counter = 0;
        foreach($e_pending as $ep){

            //Prepare variables:
            $json_data = unserialize($ep['ej_e_blob']);

            //Loop through entries:
            if(is_array($json_data) && isset($json_data['entry']) && count($json_data['entry'])>0){
                foreach($json_data['entry'] as $entry) {
                    //loop though the messages:
                    foreach($entry['messaging'] as $im){
                        //This should only be a message
                        if(isset($im['message'])) {
                            //This should be here
                            if(isset($im['message']['attachments'])){
                                //We should have attachments:
                                foreach($im['message']['attachments'] as $att){
                                    //This one too! It should be one of these:
                                    if(in_array($att['type'],array('image','audio','video','file'))){

                                        //Store to local DB:
                                        $new_file_url = save_file($att['payload']['url'],$json_data);

                                        //Update engagement data:
                                        $this->Db_model->e_update( $ep['e_id'] , array(
                                            'e_text_value' => ( strlen($ep['e_text_value'])>0 ? $ep['e_text_value']."\n\n" : '' ).'/attach '.$att['type'].':'.$new_file_url, //Makes the file preview available on the message
                                            'e_status' => 1, //Mark as done
                                        ));

                                        //Increase counter:
                                        $counter++;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                //This should not happen, report:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => 0, //System
                    'e_text_value' => 'cron/bot_save_files() fetched ej_e_blob() that was missing its [entry] value',
                    'e_json' => $json_data,
                    'e_inbound_c_id' => 8, //System Error
                ));
            }

            if($counter>=$max_per_batch){
                break; //done for now
            }
        }
        //Echo message for cron job:
        echo $counter.' Incoming Messenger file'.($counter==1?'':'s').' saved to Mench cloud.';
    }

    function message_fb_sync_attachments(){

        exit; //Logic needs updating

        //Cron Settings: * * * * *

        /*
         * This cron job looks for all requests to sync Message attachments
         * with Facebook, gets them done and marks the engagement as done
         *
         */

        $success_count = 0; //Track success
        $max_per_batch = 5; //Max number of syncs per cron run
        $e_json = array();
        $x_types = echo_status('x_type', null);

        $pending_urls = $this->Db_model->x_fetch(array(
            'x_type >=' => 2,
            'x_type <=' => 5, //These are the file URLs that need syncing
            'x_fb_att_id' => 0, //Pending Facebook Sync
        ), array(), array(), $max_per_batch);


        if(count($pending_urls)>0){
            foreach($pending_urls as $x){

                $payload = array(
                    'message' => array(
                        'attachment' => array(
                            'type' => $x_types[$x['x_type']]['s_fb_key'],
                            'payload' => array(
                                'is_reusable' => true,
                                'url' => $x['x_url'],
                            ),
                        ),
                    )
                );

                //Attempt to save this:
                $result = $this->Comm_model->fb_graph('POST', '/me/message_attachments', $payload);
                $db_result = false;

                if($result['status'] && isset($result['e_json']['result']['attachment_id'])){
                    //Save attachment to DB:
                    $db_result = $this->Db_model->x_update( $x['x_id'] , array(
                        'x_fb_att_id' => $result['e_json']['result']['attachment_id'],
                    ));
                }

                //Did it go well?
                if($db_result>0){
                    $success_count++;
                } else {

                    //Log error:
                    $this->Db_model->e_create(array(
                        'e_text_value' => 'message_fb_sync_attachments() Failed to sync attachment using Facebook API',
                        'e_json' => array(
                            'payload' => $payload,
                            'result' => $result,
                        ),
                        'e_inbound_c_id' => 8, //Platform Error
                    ));

                    //Disable future attempts:
                    $this->Db_model->x_update( $x['x_id'] , array(
                        'x_fb_att_id' => -1, //No more checks on this guy
                    ));
                }


                //Save stats either way:
                array_push($e_json, array(
                    'payload' => $payload,
                    'fb_result' => $result,
                ));

            }
        }

        //Echo message for cron job:
        echo_json(array(
            'status' => ( $success_count==count($pending_urls) && $success_count>0 ? 1 : 0 ),
            'message' => $success_count.'/'.count($pending_urls).' Message'.echo__s(count($pending_urls)).' successfully synced their attachment with Facebook',
            'e_json' => $e_json,
        ));

    }


    function student_reminder_complete_task(){

        exit; //Needs optimization

        //Cron Settings: 45 * * * *
        //Send reminders to students to complete their intent:

        $subscriptions = $this->Db_model->w_fetch(array(
            'r.r_status'	    => 2, //Running Class
            'ru.ru_status'      => 4, //Enrolled Students
        ));

        //Define the logic of these reminders
        $reminder_index = array(
            array(
                'time_elapsed'   => 0.90,
                'progress_below' => 0.99,
                'reminder_c_id'  => 3139,
            ),
            array(
                'time_elapsed'   => 0.75,
                'progress_below' => 0.50,
                'reminder_c_id'  => 3138,
            ),
            array(
                'time_elapsed'   => 0.50,
                'progress_below' => 0.25,
                'reminder_c_id'  => 3137,
            ),
            array(
                'time_elapsed'   => 0.20,
                'progress_below' => 0.10,
                'reminder_c_id'  => 3136,
            ),
            array(
                'time_elapsed'   => 0.05,
                'progress_below' => 0.01,
                'reminder_c_id'  => 3358,
            ),
        );

        $stats = array();
        foreach($subscriptions as $subscription){

            //Fetch full Bootcamp/Class data for this:
            //$bs = fetch_action_plan_copy($subscription['ru_b_id'], $subscription['r_id']);
            //$class = $bs[0]['this_class'];

            //See what % of the class time has elapsed?
            //TODO $elapsed_class_percentage = round((time()-strtotime($class['r_start_date']))/(class_ends($bs[0], $class)-strtotime($class['r_start_date'])),5);

            foreach ($reminder_index as $logic){
                if($elapsed_class_percentage>=$logic['time_elapsed']){

                    if($subscription['ru_cache__completion_rate']<$logic['progress_below']){

                        //See if we have reminded them already about this:
                        $reminders_sent = $this->Db_model->e_fetch(array(
                            'e_inbound_c_id IN (7,28)' => null, //Email or Message sent
                            'e_outbound_u_id' => $subscription['u_id'],
                            'e_outbound_c_id' => $logic['reminder_c_id'],
                        ));

                        if(count($reminders_sent)==0){

                            //Nope, send this message out:
                            $this->Comm_model->foundation_message(array(
                                'e_inbound_u_id' => 0, //System
                                'e_outbound_u_id' => $subscription['u_id'],
                                'e_outbound_c_id' => $logic['reminder_c_id'],
                                'depth' => 0,
                            ));

                            //Show in stats:
                            array_push($stats,$subscription['u_full_name'].' done '.round($subscription['ru_cache__completion_rate']*100).'% (less than target '.round($logic['progress_below']*100).'%) where class is '.round($elapsed_class_percentage*100).'% complete and got reminded via c_id '.$logic['reminder_c_id']);
                        }
                    }

                    //Do not go further down the reminder types:
                    break;
                }
            }
        }

        echo_json($stats);
    }

}