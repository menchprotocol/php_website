<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);

        //Example: /usr/bin/php /home/ubuntu/mench-web-app/index.php cron student_reminder_complete_application
	}

	function ping(){
	    echo_json(array('status'=>'success'));
    }

    function algolia_sync($obj='b',$obj_id=0){
        echo_json($this->Db_model->algolia_sync($obj,$obj_id));
    }

    /* ******************************
     * Classes
     ****************************** */

    function class_kickstart(){

        //Cron Settings: 0 * * * 0,1
        //This function is solely responsible to get the class started and dispatch its very first Task messages IF it does start
        //Searches for any class that might be starting and kick starts its messages:
        $classes = $this->Db_model->r_fetch(array(
            'r_status' => 1,
            'r_start_date <=' => date("Y-m-d"),
        ));

        //Generate stats for this cron run:
        $stats = array();

        foreach($classes as $key=>$class){

            //Fetch full Bootcamp/Class data for this
            //See if we have a copy of this Action Plan:
            //This ensures we do not make another Copy if the instructor has already cached a copy before Class start time
            //It's a feature that is not publicly available, and would likely not happen
            $bs = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bs[0]['this_class'];


            //Append stats array for cron reporting:
            $stats[$class['r_id']] = array(
                'b_id' => $class['r_b_id'],
                'initial_status' => $class['r_status'],
                'new_status' => 0, //To be updated
                'students' => array(
                    'rejected_incomplete' => 0, //Rejected because their application was incomplete by the time the class started
                    'accepted_started' => 0, //The students who got started with this Class
                ),
            );

            //Auto withdraw all incomplete admission requests:
            $incomplete_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 0,
            ));
            if(count($incomplete_admissions)>0){

                //Update counter:
                $stats[$class['r_id']]['students']['rejected_incomplete'] = count($incomplete_admissions);

                foreach($incomplete_admissions as $admission){

                    //Auto reject:
                    $this->Db_model->ru_update( $admission['ru_id'] , array('ru_status' => -1));

                    //Inform the student of auto rejection because they missed deadline:
                    $this->Comm_model->foundation_message(array(
                        'e_inbound_u_id' => 0,
                        'e_outbound_u_id' => $admission['u_id'],
                        'e_outbound_u_id' => 3016,
                        'depth' => 0,
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));
                }
            }


            //lets prep for checking the conditions to get started
            //Lets see how many admitted students we have?
            $accepted_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 4, //Admitted students
            ));


            //Did we get any students?
            if(count($accepted_admissions)==0){

                //Expire this class as it does not have enough students admitted:
                $stats[$class['r_id']]['new_status'] = -2; //Expired
                $this->Db_model->r_update( $class['r_id'] , array('r_status' => $stats[$class['r_id']]['new_status']));

                //Log Class Cancellation engagement & Notify Admin/Instructor:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => 0, //System
                    'e_text_value' => 'Class did not start because no students applied/got-admitted into this Class.',
                    'e_json' => array(
                        'admitted' => $accepted_admissions,
                    ),
                    'e_inbound_c_id' => 56, //Class Cancelled
                    'e_b_id' => $class['r_b_id'],
                    'e_r_id' => $class['r_id'],
                ));

            } else {

                //The class is ready to get started!
                //Change the status to running:
                $stats[$class['r_id']]['new_status'] = 2; //Class Running
                $this->Db_model->r_update( $class['r_id'] , array(
                    'r_status' => $stats[$class['r_id']]['new_status'],
                ));

                //Take snapshot of Action Plan ONLY IF not already taken for this class:
                if(!$bs[0]['is_copy']){
                    //Save Action Plan only if not already done so:
                    $this->Db_model->snapshot_action_plan($bs[0]['b_id'],$class['r_id']);
                }

                $stats[$class['r_id']]['students']['accepted_started'] = count($accepted_admissions);


                //Dispatch appropriate Message to Students
                $classroom_students = 0;
                foreach($accepted_admissions as $admission){

                    if($admission['ru_p2_price']>0){
                        $classroom_students++;
                    }

                    //Send message letting them know that their Bootcamp has started:
                    $this->Comm_model->foundation_message(array(
                        'e_outbound_u_id' => $admission['u_id'],
                        'e_outbound_u_id' => 5441, //Bootcamp/Class Started
                        'depth' => 0,
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));

                    if(!intval($admission['ru_fp_psid']) || !intval($admission['ru_fp_id'])){

                        //No Messenger activated yet! Remind them again:
                        $this->Comm_model->foundation_message(array(
                            'e_inbound_u_id' => 0,
                            'e_outbound_u_id' => $admission['u_id'],
                            'e_outbound_u_id' => 3120,
                            'depth' => 0,
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));

                    } else {

                        //Override this as the user's primary Chatline as this Class is their default:
                        $this->Db_model->u_update( $admission['u_id'] , array(
                            'u_cache__fp_id' => $admission['ru_fp_id'],
                            'u_cache__fp_psid' => $admission['ru_fp_psid'],
                        ));

                    }
                }


                //Log Class Kick-start engagement & Notify Admin/Instructor:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => 0, //System
                    'e_text_value' => 'Class started successfully with ['.count($accepted_admissions).'] total students and ['.$classroom_students.'] Classroom students.'.( $classroom_students ? ' You should now schedule an hour long group call with your students and communicate the time to them so they can join on the group Call.' : ''),
                    'e_json' => array(
                        'classroom_count' => $classroom_students,
                        'admitted' => $accepted_admissions,
                    ),
                    'e_inbound_c_id' => 60, //Class kick-started
                    'e_b_id' => $class['r_b_id'],
                    'e_r_id' => $class['r_id'],
                ));

            }
        }

        //Echo Summary:
        echo_json($stats);
    }

    function class_complete(){

        //Cron Settings: 0 * * * 0,1 (Sunday & Monday Every hour)

        //Completed a Class

        $running_classes = $this->Db_model->r_fetch(array(
            'r_status' => 2, //Only running classes
        ));

        //Cron stats file so we know what happened in each run...
        $stats = array();

        foreach($running_classes as $class){

            //Fetch full Bootcamp/Class data for this:
            $bs = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bs[0]['this_class'];

            //Has the class ended yet?
            if($class['r__class_end_time']<=time()){

                //Yes, Class has ended

                //Fetch all the class students
                $accepted_admissions = $this->Db_model->ru_fetch(array(
                    'ru.ru_r_id'	    => $class['r_id'],
                    'ru.ru_status >='	=> 4, //Admitted students
                ));

                if(count($accepted_admissions)==0){

                    //Ooops, this is an error that should not happen, log engagemeng:
                    $this->Db_model->e_create(array(
                        'e_text_value' => 'ERROR: Class ended with 0 admitted students',
                        'e_json' => $bs[0],
                        'e_inbound_c_id' => 8, //Platform Error
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));

                    //Show in Cron stats:
                    $stats[$class['r_id']] = 'Class ended without any admitted students';
                    $r_cache__completion_rate = 0;

                } else {

                    //Fetch lead Instructor:
                    $lead_instructors = $this->Db_model->ba_fetch(array(
                        'ba.ba_b_id'            => $class['r_b_id'],
                        'ba.ba_status >='       => 3, //Lead Instructor
                        'u.u_status >='         => 1, //Must be a user level 1 or higher
                    ));

                    //Construct the review message and button:
                    $review_message = 'Your final step is to rate & review your experience with ​​​​'.$lead_instructors[0]['u_fname'].' '.$lead_instructors[0]['u_lname'].' and help improve future Classes:';
                    $review_button = '📣 Review '.$lead_instructors[0]['u_fname']; //Will show a button to rate/review Lead Instructor


                    //Do a count for stat reporting:
                    $completion_stats = array(
                        'completed' => 0,
                        'incomplete' => 0,
                    );

                    //Loop through students and make adjustments:
                    foreach($accepted_admissions as $admission){

                        //See where the student is at, did they finish the previous Task?
                        if($admission['ru_cache__current_task']>$class['r__total_tasks']){
                            //Completed all Tasks:
                            $completion_stats['completed']++;
                            $ru_status = 7; //Graduate
                            $e_inbound_c_id = 64; //Student Graduated
                            $i_message​​ = '{first_name} your class just ended. Congratulations for completing all Tasks on-time 🎉​';
                        } else {
                            //Did not complete:
                            $completion_stats['incomplete']++;
                            $ru_status = 6; //Incomplete
                            $e_inbound_c_id = 71; //Student Incomplete Class
                            $i_message​​ = '{first_name} your class just ended. You can no longer submit Steps but you will have life-time access to all Tasks and Steps which are now unlocked.​';
                        }

                        //Adjust status in admissions table:
                        $this->Db_model->ru_update( $admission['ru_id'] , array(
                            'ru_status' => $ru_status,
                        ));

                        //Log Engagement:
                        $this->Db_model->e_create(array(
                            'e_inbound_u_id' => 0, //System
                            'e_outbound_u_id' => $admission['u_id'],
                            'e_json' => array(
                                'admission' => $admission,
                                'messages' => $this->Comm_model->send_message(array(
                                    array(
                                        'i_media_type' => 'text',
                                        'i_message' => $i_message​​,
                                        'e_inbound_u_id' => 0,
                                        'e_outbound_u_id' => $admission['u_id'],
                                        'e_b_id' => $class['r_b_id'],
                                        'e_r_id' => $class['r_id'],
                                    ),
                                    //Ask to review message:
                                    array(
                                        'i_media_type' => 'text',
                                        'i_message' => $review_message,
                                        'i_button' => $review_button,
                                        'i_url' => 'https://mench.com/my/review/'.$admission['ru_id'].'/'.substr(md5($admission['ru_id'].'r3vi3wS@lt'),0,6),
                                        'e_inbound_u_id' => 0,
                                        'e_outbound_u_id' => $admission['u_id'],
                                        'e_b_id' => $class['r_b_id'],
                                        'e_r_id' => $class['r_id'],
                                    ),
                                )),
                            ),
                            'e_inbound_c_id' => $e_inbound_c_id,
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));
                    }

                    //How did the class do overall?
                    $r_cache__completion_rate = number_format(($completion_stats['completed'] / count($accepted_admissions)),3);


                    //Log Engagement:
                    $industry_completion = 0.10; //Like Udemy, etc...
                    $completion_message = 'Your ['.$bs[0]['c_outcome'].'] Class of ['.time_format($class['r_start_date'],2).'] has ended with a ['.round($r_cache__completion_rate*100).'%] completion rate. From the total students of ['.count($accepted_admissions).'], you helped ['.$completion_stats['completed'].'] of them graduate by completing all Tasks on-time.'.( $r_cache__completion_rate>$industry_completion ? ' Great job on exceeding the e-learning industry average completion rate of '.(round($industry_completion*100)).'% 🎉🎉🎉​' : '' );

                    //Log Engagement for Class Completion:
                    $this->Db_model->e_create(array(
                        'e_inbound_u_id' => 0, //System
                        'e_text_value' => $completion_message,
                        'e_inbound_c_id' => 69, //Class Completed, sends message to instructor team...
                        'e_json' => array(
                            'stats' => $completion_stats,
                            'admissions' => $accepted_admissions,
                        ),
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));

                    //Show in Cron stats:
                    $stats[$class['r_id']] = $completion_message;

                }

                //Update Class:
                $this->Db_model->r_update( $class['r_id'] , array(
                    'r_status' => 3, //Completed
                    'r_cache__completion_rate' => $r_cache__completion_rate,
                ));

            }
        }

        //Show cron summary:
        echo_json($stats);
    }

    function class_create($b_id=0){

        if($b_id>0){
            $filter = array(
                'b_id' => $b_id,
            );
        } else {
            $filter = array(
                'b_status >=' => 2,
                'b_old_format' => 0,
            );
        }
        $bs = $this->Db_model->b_fetch($filter);

        $stats = array();
        foreach($bs as $b){
            $stats[$b['b_id']] = $this->Db_model->r_sync($b['b_id']);
        }

        echo_json($stats);
    }

    /* ******************************
     * Messaging
     ****************************** */

    function message_drip(){

        //Cron Settings: */5 * * * *

        //Fetch pending drips
        $e_pending = $this->Db_model->e_fetch(array(
            'e_status' => 0, //Pending
            'e_inbound_c_id' => 52, //Scheduled Drip e_inbound_c_id=52
            'e_timestamp <=' => date("Y-m-d H:i:s" ), //Message is due
            //Some standard checks to make sure, these should all be true:
            'e_r_id >' => 0,
            'e_outbound_u_id >' => 0,
            'e_b_id >' => 0,
            'e_outbound_u_id >' => 0,
        ), 200, array('ej'));


        //Lock item so other Cron jobs don't pick this up:
        lock_cron_for_processing($e_pending);


        $drip_sent = 0;
        foreach($e_pending as $e_text_value){

            //Fetch user data:
            $matching_admissions = $this->Db_model->ru_fetch(array(
                'ru_u_id' => $e_text_value['e_outbound_u_id'],
                'ru_r_id' => $e_text_value['e_r_id'],
                'ru_status >=' => 4, //Active student
                'r_status' => 2, //Running Class
            ));

            if(count($matching_admissions)>0){

                //Prepare variables:
                $json_data = unserialize($e_text_value['ej_e_blob']);

                //Send this message:
                $this->Comm_model->send_message(array(
                    array_merge($json_data['i'], array(
                        'e_inbound_u_id' => 0,
                        'e_outbound_u_id' => $matching_admissions[0]['u_id'],
                        'i_inbound_c_id' => $json_data['i']['i_inbound_c_id'],
                        'e_b_id' => $e_text_value['e_b_id'],
                        'e_r_id' => $e_text_value['e_r_id'],
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
            'e_inbound_c_id >=' => 6, //Messages only
            'e_inbound_c_id <=' => 7, //Messages only
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

        //Cron Settings: * * * * *

        /*
         * This cron job looks for all requests to sync Message attachments
         * with Facebook, gets them done and marks the engagement as done
         *
         */

        $success_count = 0; //Track success
        $max_per_batch = 55; //Max number of syncs per cron run
        $e_json = array();

        $e_pending = $this->Db_model->e_fetch(array(
            'e_status' => 0, //Pending Sync
            'e_inbound_c_id' => 83, //Message Facebook Sync e_inbound_c_id=83
        ), $max_per_batch, array('i','fp'));


        //Lock item so other Cron jobs don't pick this up:
        lock_cron_for_processing($e_pending);


        if(count($e_pending)>0){
            foreach($e_pending as $ep){

                //Does this meet the basic tests? It should...
                if($ep['fp_id']>0 && $ep['i_id']>0 && strlen($ep['i_url'])>0 && filter_var($ep['i_url'], FILTER_VALIDATE_URL) && in_array($ep['i_media_type'],array('video','image','audio','file'))){

                    //First make sure we don't already have this saved in v5_message_fb_sync already
                    $synced_messages = $this->Db_model->sy_fetch(array(
                        'sy_i_id' => $ep['i_id'],
                        'sy_fp_id' => $ep['fp_id'],
                    ));

                    if(count($synced_messages)==0){

                        $payload = array(
                            'message' => array(
                                'attachment' => array(
                                    'type' => $ep['i_media_type'],
                                    'payload' => array(
                                        'is_reusable' => true,
                                        'url' => $ep['i_url'],
                                    ),
                                ),
                            )
                        );

                        //Attempt to save this:
                        $result = $this->Comm_model->fb_graph($ep['fp_id'], 'POST', '/me/message_attachments', $payload);
                        $db_result = false;

                        if($result['status'] && isset($result['e_json']['result']['attachment_id'])){
                            //Save attachment to DB:
                            $db_result = $this->Db_model->sy_create(array(
                                'sy_i_id' => $ep['i_id'],
                                'sy_fp_id' => $ep['fp_id'],
                                'sy_fb_att_id' => $result['e_json']['result']['attachment_id'],
                            ));
                        }

                        //Did it go well?
                        if(is_array($db_result) && count($db_result)>0){
                            $success_count++;
                        } else {
                            //Log error:
                            $this->Db_model->e_create(array(
                                'e_text_value' => 'message_fb_sync_attachments() Failed to sync attachment using Facebook API',
                                'e_json' => array(
                                    'payload' => $payload,
                                    'result' => $result,
                                    'ep' => $ep,
                                ),
                                'e_inbound_c_id' => 8, //Platform Error
                            ));
                        }


                        //Update engagement:
                        $this->Db_model->e_update( $ep['e_id'], array(
                            'e_status' => 1, //Completed
                        ));


                        //Save stats either way:
                        array_push($e_json,array(
                            'payload' => $payload,
                            'fb_result' => $result,
                            'db_result' => $db_result,
                        ));

                    }
                }
            }
        }

        //Echo message for cron job:
        echo_json(array(
            'status' => ( $success_count==count($e_pending) && $success_count>0 ? 1 : 0 ),
            'message' => $success_count.'/'.count($e_pending).' Message'.show_s(count($e_pending)).' successfully synced their attachment with Facebook',
            'e_json' => $e_json,
        ));

    }

    /* ******************************
	 * Instructor
	 ****************************** */

    function instructor_notify_student_activity(){

        //Cron Settings: 0 */2 * * * *
        //Runs every hour and informs instructors/admins of new messages received recently
        //Define settings:
        $seconds_ago = 7200; //Defines how much to go back, should be equal to cron job frequency

        //Create query:
        $after_time = date("Y-m-d H:i:s",(time()-$seconds_ago));


        //Fetch student inbound messages that have not yet been replied to:
        $q = $this->db->query('SELECT u_fname, u_lname, e_inbound_u_id, COUNT(e_id) as received_messages FROM v5_engagements e JOIN v5_entities u ON (e.e_inbound_u_id = u.u_id) WHERE e_inbound_c_id=6 AND e_timestamp > \''.$after_time.'\' AND e_inbound_u_id>0 AND u_status<=1 GROUP BY e_inbound_u_id, u_status, u_fname, u_lname');
        $new_messages = $q->result_array();
        $notify_messages = array();
        foreach($new_messages as $key=>$nm){

            //Lets see if their inbound messages has been responded by the instructor:
            $messages = $this->Db_model->e_fetch(array(
                'e_inbound_c_id IN (6,7)' => null,
                'e_timestamp >' => $after_time,
                '(e_inbound_u_id='.$nm['e_inbound_u_id'].' OR e_outbound_u_id='.$nm['e_inbound_u_id'].')' => null,
            ));

            if(count($messages)>$nm['received_messages']){
                //We also sent some messages, see who sent them, and if we need to notify the admin:
                $last_message = $messages[0]; //This is the latest message
                $new_messages[$key]['notify'] = ( $last_message['e_inbound_c_id']==7 && $last_message['e_inbound_u_id']>0 ? 0 : 1 );
            } else {
                //No responses, we must notify:
                $new_messages[$key]['notify'] = 1;
            }


            if($new_messages[$key]['notify']){

                //Lets see who is responsible for this student:
                //Checks to see who is responsible for this user, likely to receive update messages or something...
                $admissions = $this->Db_model->remix_admissions(array(
                    'ru_u_id'	     => $nm['e_inbound_u_id'],
                    'ru_status >='	 => 0,
                ));
                $active_admission = detect_active_admission($admissions); //We'd need to see which admission to load now

                if($active_admission && $active_admission['ru_p2_price']>0 /* Premium Students Only */){

                    unset($notify_fb_ids);
                    $notify_fb_ids = array();
                    $b_data = array(
                        'b_id' => $active_admission['b_id'],
                        'c_outcome' => $active_admission['c_outcome'],
                    );
                    //Fetch the admins for this admission:
                    foreach($active_admission['b__admins'] as $admin){
                        //We can handle either email or messenger connection:
                        array_push( $notify_fb_ids , array(
                            'u_fname' => $admin['u_fname'],
                            'u_lname' => $admin['u_lname'],
                            'u_id' => $admin['u_id'],
                        ));
                    }

                    if(count($notify_fb_ids)>0){

                        //Group these messages based on their receivers:
                        $md5_key = substr(md5(print_r($b_data,true)),0,8).substr(md5(print_r($notify_fb_ids,true)),0,8);
                        if(!isset($notify_messages[$md5_key])){
                            $notify_messages[$md5_key] = array(
                                'notify_admins' => $notify_fb_ids,
                                'b_data' => $b_data,
                                'message_threads' => array(),
                            );
                        }

                        array_push($notify_messages[$md5_key]['message_threads'] , $new_messages[$key]);

                    }
                }
            }
        }


        //Now see if we need to notify any admin:
        if(count($notify_messages)>0){
            foreach($notify_messages as $key=>$msg){

                //Prepare the message Body:
                $message = null;
                if(count($msg['b_data'])>0){
                    $message .= '🎯 '.$msg['b_data']['c_outcome']."\n";
                }
                $message .= '💡 Premium Support Student activity in the past '.round($seconds_ago/3600).' hours:'."\n";
                foreach($msg['message_threads'] as $thread){
                    $message .= "\n".$thread['received_messages'].' message'.show_s($thread['received_messages']).' from '.$thread['u_fname'].' '.$thread['u_lname'];
                }
                if(count($msg['b_data'])>0 && strlen($message)<580){
                    $message .= "\n\n".'https://mench.com/console/'.$msg['b_data']['b_id'];
                }

                $notify_messages[$key]['admin_message'] = $message;

                //Send message to all admins:
                foreach($msg['notify_admins'] as $admin){

                    $this->Comm_model->send_message(array(
                        array(
                            'i_media_type' => 'text',
                            'i_message' => substr($message,0,620), //Make sure this is not too long!
                            'e_inbound_u_id' => 0, //System
                            'e_outbound_u_id' => $admin['u_id'],
                            'e_b_id' => ( isset($msg['b_data']['b_id']) ? $msg['b_data']['b_id'] : 0),
                        ),
                    ));

                }
            }
        }

        echo_json($notify_messages);
    }

    /* ******************************
	 * Students
	 ****************************** */

    function student_reminder_complete_application(){

        //Cron Settings: 10 * * * *

        //Fetch current incomplete applications:
        $incomplete_applications = $this->Db_model->ru_fetch(array(
            'r.r_status'	=> 1, //Open For Admission
            'ru.ru_status'  => 0,
        ));


        $stats = array();
        foreach($incomplete_applications as $admission){

            //Fetch existing reminders sent to this student:
            $reminders_sent = $this->Db_model->e_fetch(array(
                'e_inbound_c_id IN (7,28)' => null, //Email/Message sent
                'e_outbound_u_id' => $admission['u_id'],
                'e_r_id' => $admission['r_id'],
                'e_outbound_u_id IN (3140,3127)' => null, //The ID of the 5 email reminders https://mench.com/console/53/actionplan
            ));

            $admission_end_time = strtotime($admission['r_start_date']) - 60; //11:59PM the night before start date
            $admission_time = strtotime($admission['ru_timestamp']);


            //Send them a reminder to complete 24 hours after they start, only IF they started their application more than 6 days before the Class start:
            $reminder_c_id = 0;
            if(($admission_time+(3*24*3600))<$admission_end_time && ($admission_time+(24*3600))<time() && !filter($reminders_sent,'e_outbound_u_id',3127)){
                //Sent 24 hours after initiating admission IF registered more than 3 days before Class starts
                $reminder_c_id = 3127;
            } elseif(($admission_time+(26*3600))<$admission_end_time && (time()+(24*3600))>$admission_end_time && !filter($reminders_sent,'e_outbound_u_id',3140)){
                //Sent 24 hours before class starts IF registered more than 26 hours before Class starts
                $reminder_c_id = 3140;
            }

            if($reminder_c_id){
                //Send reminder:
                $this->Comm_model->foundation_message(array(
                    'e_inbound_u_id' => 0,
                    'e_outbound_u_id' => $admission['u_id'],
                    'e_outbound_u_id' => $reminder_c_id,
                    'depth' => 0,
                    'e_b_id' => $admission['ru_b_id'],
                    'e_r_id' => $admission['r_id'],
                ));

                //Push stats:
                array_push($stats, array(
                    'email' => $reminder_c_id,
                    'ru_id' => $admission['ru_id'],
                    'r_id' => $admission['r_id'],
                    'u_id' => $admission['u_id'],
                    'ru_timestamp' => $admission['ru_timestamp'],
                    'r_start_date' => $admission['r_start_date'],
                    'reminders' => $reminders_sent,
                ));
            }
        }

        echo_json($stats);
    }

    function student_reminder_complete_task(){

        //Cron Settings: 45 * * * *
        //Send reminders to students to complete their Steps:

        $admissions = $this->Db_model->ru_fetch(array(
            'r.r_status'	    => 2, //Running Class
            'ru.ru_status'      => 4, //Admitted Students
        ));

        //Define the logic of these reminders
        $reminder_index = array(
            array(
                'time_elapsed'   => 0.90,
                'progress_below' => 1.00,
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
                'time_elapsed'   => 0.25,
                'progress_below' => 0.10,
                'reminder_c_id'  => 3136,
            ),
            array(
                'time_elapsed'   => 0.10,
                'progress_below' => 0.01,
                'reminder_c_id'  => 3358,
            ),
        );

        $stats = array();
        foreach($admissions as $admission){

            //Fetch full Bootcamp/Class data for this:
            $bs = fetch_action_plan_copy($admission['ru_b_id'], $admission['r_id']);
            $class = $bs[0]['this_class'];

            //See what % of the class time has elapsed?
            $elapsed_class_percentage = round((time()-$class['r__class_start_time'])/($class['r__class_end_time']-$class['r__class_start_time']),5);

            foreach ($reminder_index as $logic){
                if($elapsed_class_percentage>=$logic['time_elapsed']){

                    if($admission['ru_cache__completion_rate']<$logic['progress_below']){

                        //See if we have reminded them already about this:
                        $reminders_sent = $this->Db_model->e_fetch(array(
                            'e_inbound_c_id IN (7,28)' => null, //Email or Message sent
                            'e_outbound_u_id' => $admission['u_id'],
                            'e_r_id' => $admission['r_id'],
                            'e_outbound_u_id' => $logic['reminder_c_id'],
                        ));

                        if(count($reminders_sent)==0){

                            //Nope, send this message out:
                            $this->Comm_model->foundation_message(array(
                                'e_inbound_u_id' => 0, //System
                                'e_outbound_u_id' => $admission['u_id'],
                                'e_outbound_u_id' => $logic['reminder_c_id'],
                                'depth' => 0,
                                'e_b_id' => $admission['ru_b_id'],
                                'e_r_id' => $admission['r_id'],
                            ));

                            //Show in stats:
                            array_push($stats,$admission['u_fname'].' '.$admission['u_lname'].' done '.round($admission['ru_cache__completion_rate']*100).'% (less than target '.round($logic['progress_below']*100).'%) where class is '.round($elapsed_class_percentage*100).'% complete and got reminded via c_id '.$logic['reminder_c_id']);
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