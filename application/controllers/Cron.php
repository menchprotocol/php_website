<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}

	function ping(){
	    echo_json(array('status'=>'success'));
    }

    /* ******************************
     * System
     * /usr/bin/php /var/www/us/index.php cron student_reminder_complete_application
     ****************************** */

    function class_kickstart(){

        //Cron Settings: 0,30 * * * *
        //This function is solely responsible to get the class started and dispatch its very first Task messages IF it does start
        //Searches for any class that might be starting and kick starts its messages:
        $classes = $this->Db_model->r_fetch(array(
            'r_status' => 1,
            'r_start_date <=' => date("Y-m-d"),
        ));

        //Generate stats for this cron run:
        $stats = array();

        foreach($classes as $key=>$class){

            //Fetch full Project/Class data for this
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
                    'rejected_pending' => 0, //Rejected because their application was not approved by instructor by the time the class started
                    'rejected_class_cancelled' => 0, //Rejected because the class did not start (see reasons below)
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
                        'e_initiator_u_id' => 0,
                        'e_recipient_u_id' => $admission['u_id'],
                        'e_c_id' => 3016,
                        'depth' => 0,
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));
                }
            }


            //Auto reject all applications that were not yet accepted:
            //TODO Maybe turn this into Auto accept?
            $pending_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 2,
            ));

            if(count($pending_admissions)>0){

                //Update counter:
                $stats[$class['r_id']]['students']['rejected_pending'] = count($pending_admissions);

                foreach($pending_admissions as $admission){

                    //Auto reject:
                    $this->Db_model->ru_update($admission['ru_id'] , array('ru_status' => -1));

                    //Inform the student of rejection:
                    $this->Comm_model->foundation_message(array(
                        'e_initiator_u_id' => 0,
                        'e_recipient_u_id' => $admission['u_id'],
                        'e_c_id' => 2799,
                        'depth' => 0,
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));

                    //Was this a paid class? Let admin know to manually process refunds
                    if($class['r_usd_price']>0){
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_message' => 'Investigation needed. May need to manually refund $['.$class['r_usd_price'].'] to ['.$admission['u_fname'].' '.$admission['u_lname'].'] as their pending application was auto-rejected upon class kick-start because the instructor did not approve them on time.',
                            'e_type_id' => 58, //Class Manual Refund
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));
                    }
                }
            }



            //lets prep for checking the conditions to get started
            //Lets see how many admitted students we have?
            $accepted_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 4, //Admitted students
            ));


            //Find first due Task to dispatch its messages to all students:
            $first_task_c_id = 0;
            foreach($bs[0]['c__child_intents'] as $task){
                if($task['c_status']>=1){
                    $first_task_c_id = $task['c_id'];
                    break;
                }
            }


            //Now make sure class meets are requirements to get started with the following conditions:
            $cancellation_reason = null; //If remains Null we're good to get started
            if($bs[0]['b_status']<2){
                $cancellation_reason = 'Project was not published';
            } elseif($first_task_c_id==0) {
                $cancellation_reason = 'Project did not have any published Tasks';
            } elseif(count($accepted_admissions)==0) {
                $cancellation_reason = 'no students applied/got-admitted into this Class';
            }

            if($cancellation_reason){

                //Cancel this class as it does not have enough students admitted:
                $stats[$class['r_id']]['new_status'] = -2; //Class was cancelled
                $this->Db_model->r_update( $class['r_id'] , array('r_status' => $stats[$class['r_id']]['new_status']));


                //Change the status of all students that had been accepted, if any, and notify admin for refunds:
                //Note that this process is kind-of similar to Api_chat_v1/update_admission_status() but not fully as it also includes ru_status=0
                if(count($accepted_admissions)>0){

                    //Update counter:
                    $stats[$class['r_id']]['students']['rejected_class_cancelled'] = count($accepted_admissions);

                    foreach($accepted_admissions as $admission){
                        //Auto reject:
                        $this->Db_model->ru_update( $admission['ru_id'] , array('ru_status' => -1));

                        //Inform the student of rejection:
                        $this->Comm_model->foundation_message(array(
                            'e_initiator_u_id' => 0,
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_c_id' => 3017,
                            'depth' => 0,
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));

                        //Was this a paid class? Let admin know to manually process refunds
                        //TODO automate refunds through Paypal API later on...
                        if($class['r_usd_price']>0){
                            $this->Db_model->e_create(array(
                                'e_initiator_u_id' => 0, //System
                                'e_recipient_u_id' => $admission['u_id'],
                                'e_message' => 'Investigation needed. May need to manually refund $['.$class['r_usd_price'].'] to ['.$admission['u_fname'].' '.$admission['u_lname'].'] as their pending application was auto-rejected upon class kick-start '.$cancellation_reason.'.',
                                'e_type_id' => 58, //Class Manual Refund
                                'e_b_id' => $class['r_b_id'],
                                'e_r_id' => $class['r_id'],
                            ));
                        }
                    }
                }

                //Log Class Cancellation engagement & Notify Admin/Instructor:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => 0, //System
                    'e_message' => 'Class did not start because '.$cancellation_reason.'.',
                    'e_json' => array(
                        'admitted' => $accepted_admissions,
                    ),
                    'e_type_id' => 56, //Class Cancelled
                    'e_b_id' => $class['r_b_id'],
                    'e_r_id' => $class['r_id'],
                ));

            } else {

                //The class is ready to get started!
                //Change the status to running:
                $stats[$class['r_id']]['new_status'] = 2; //Class Running
                $this->Db_model->r_update( $class['r_id'] , array(
                    'r_status' => $stats[$class['r_id']]['new_status'],
                    'r_cache__current_milestone' => 1, //First Task is getting started, note it here
                ));


                //Take snapshot of Action Plan ONLY IF not already taken for this class:
                if(!$bs[0]['is_copy']){
                    //Save Action Plan only if not already done so:
                    $this->Db_model->snapshot_action_plan($bs[0]['b_id'],$class['r_id']);
                }

                $stats[$class['r_id']]['students']['accepted_started'] = count($accepted_admissions);


                //Dispatch appropriate Message to Students
                foreach($accepted_admissions as $admission){

                    if(!($admission['ru_fp_psid']>0) || !($admission['ru_fp_id']>0)){
                        //No Messenger activated yet! Remind them again:
                        $this->Comm_model->foundation_message(array(
                            'e_initiator_u_id' => 0,
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_c_id' => 3120,
                            'depth' => 0,
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));
                    } else {
                        //Override this as the user's primary Chatline:
                        $this->Db_model->u_update( $admission['u_id'] , array(
                            'ru_fp_id' => $admission['ru_fp_id'],
                            'ru_fp_psid' => $admission['ru_fp_psid'],
                        ));
                    }

                    //Send message for their first Class:
                    $this->Comm_model->foundation_message(array(
                        'e_recipient_u_id' => $admission['u_id'],
                        'e_c_id' => $first_task_c_id, //First Task of this Class
                        'depth' => 0,
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));
                }


                //Log Class Kick-start engagement & Notify Admin/Instructor:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => 0, //System
                    'e_message' => 'Class started successfully with ['.count($accepted_admissions).'] admitted students.',
                    'e_json' => array(
                        'admitted' => $accepted_admissions,
                    ),
                    'e_type_id' => 60, //Class kick-started
                    'e_b_id' => $class['r_b_id'],
                    'e_r_id' => $class['r_id'],
                ));

            }
        }

        //Echo Summary:
        echo_json($stats);
    }

    function end_project(){

        //Cron Settings: 0,30 * * * *
        //Moves the class from one Task to another, Only applicable for the 2nd milesone or higher
        //Also closes a Class if all Tasks are done...
        //The first Task is triggered with the class_kickstart() cron function above

        $running_classes = $this->Db_model->r_fetch(array(
            'r_status' => 2, //Only running classes
        ));

        //Cron stats file so we know what happened in each run...
        $stats = array();

        foreach($running_classes as $class){

            //Fetch full Project/Class data for this:
            $bs = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bs[0]['this_class'];

            $has_ended = 0; //TODO Determine

            //Where is the class at now?
            if($has_ended){

                //Class ended
                //Fetch all the class students, and see which ones advance to this new Task:
                $accepted_admissions = $this->Db_model->ru_fetch(array(
                    'ru.ru_r_id'	    => $class['r_id'],
                    'ru.ru_status >='	=> 4, //Admitted students
                ));

                if(count($accepted_admissions)==0){

                    //Ooops, this is an error that should not happen, log engagemeng:
                    $this->Db_model->e_create(array(
                        'e_message' => 'ERROR: Class ended with 0 admitted students',
                        'e_json' => $bs[0],
                        'e_type_id' => 8, //Platform Error
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));

                    //Show in Cron stats:
                    $stats[$class['r_id']] = 'Class ended without any admitted students';

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
                            $e_type_id = 64; //Student Graduated
                            $i_message​​ = '{first_name} your class just ended. Congratulations for completing all Tasks on-time 🎉​';
                        } else {
                            //Did not complete:
                            $completion_stats['incomplete']++;
                            $ru_status = 6; //Incomplete
                            $e_type_id = 71; //Student Incomplete Class
                            $i_message​​ = '{first_name} your class just ended. You can no longer submit Steps but you will have life-time access to all Tasks and Steps which are now unlocked.​';
                        }

                        //Adjust status in admissions table:
                        $this->Db_model->ru_update( $admission['ru_id'] , array(
                            'ru_status' => $ru_status,
                        ));

                        //Log Engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_json' => array(
                                'admission' => $admission,
                                'messages' => $this->Comm_model->send_message(array(
                                    array(
                                        'i_media_type' => 'text',
                                        'i_message' => $i_message​​,
                                        'e_initiator_u_id' => 0,
                                        'e_recipient_u_id' => $admission['u_id'],
                                        'e_b_id' => $class['r_b_id'],
                                        'e_r_id' => $class['r_id'],
                                    ),
                                    //Ask to review message:
                                    array(
                                        'i_media_type' => 'text',
                                        'i_message' => $review_message,
                                        'i_button' => $review_button,
                                        'i_url' => 'https://mench.com/my/review/'.$admission['ru_id'].'/'.substr(md5($admission['ru_id'].'r3vi3wS@lt'),0,6),
                                        'e_initiator_u_id' => 0,
                                        'e_recipient_u_id' => $admission['u_id'],
                                        'e_b_id' => $class['r_b_id'],
                                        'e_r_id' => $class['r_id'],
                                    ),
                                )),
                            ),
                            'e_type_id' => $e_type_id,
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));
                    }

                    //How did the class do overall?
                    if(count($accepted_admissions)<1){
                        $r_cache__completion_rate = 0;
                    } else {
                        $r_cache__completion_rate = number_format(($completion_stats['completed'] / count($accepted_admissions)),3);
                    }

                    //Update Class:
                    $this->Db_model->r_update( $class['r_id'] , array(
                        'r_status' => 3, //Completed
                        'r_cache__current_milestone' => -1, //meaning Class is now complete
                        'r_cache__completion_rate' => $r_cache__completion_rate,
                    ));


                    //Log Engagement:
                    $industry_completion = 0.10; //Like Udemy, etc...
                    $completion_message = 'Your ['.$bs[0]['c_objective'].'] Class of ['.time_format($class['r_start_date'],2).'] has ended with a ['.round($r_cache__completion_rate*100).'%] completion rate. From the total students of ['.count($accepted_admissions).'], you helped ['.$completion_stats['completed'].'] of them graduate by completing all Tasks on-time.'.( $r_cache__completion_rate>$industry_completion ? ' Great job on exceeding the e-learning industry average completion rate of '.(round($industry_completion*100)).'% 🎉🎉🎉​' : '' );

                    //Log Engagement for Class Completion:
                    $this->Db_model->e_create(array(
                        'e_initiator_u_id' => 0, //System
                        'e_message' => $completion_message,
                        'e_type_id' => 69, //Class Completed, sends message to instructor team...
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
            }
        }

        //Show cron summary:
        echo_json($stats);
    }

    function drip(){

        //Cron Settings: */5 * * * *

        //Fetch pending drips
        $e_pending = $this->Db_model->e_fetch(array(
            'e_cron_job' => 0, //Pending
            'e_type_id' => 52, //Scheduled Drip e_type_id=52
            'e_timestamp <=' => date("Y-m-d H:i:s" ), //Message is due
            //Some standard checks to make sure, these should all be true:
            'e_r_id >' => 0,
            'e_c_id >' => 0,
            'e_b_id >' => 0,
            'e_recipient_u_id >' => 0,
        ), 200, array('ej'));


        //Lock item so other Cron jobs don't pick this up:
        lock_cron_for_processing($e_pending);


        $drip_sent = 0;
        foreach($e_pending as $e_message){

            //Fetch user data:
            $matching_admissions = $this->Db_model->ru_fetch(array(
                'ru_u_id' => $e_message['e_recipient_u_id'],
                'ru_r_id' => $e_message['e_r_id'],
                'ru_status >=' => 4, //Active student
                'r_status' => 2, //Running Class
                'ru_fp_psid >' => 0, //Messenger activated
                'ru_fp_id >' => 0, //Messenger activated
            ));

            //Prepare variables:
            $json_data = unserialize($e_message['ej_e_blob']);

            if(count($matching_admissions)<1){
                //Something has changed since this Drip has been scheduled...
                //Log Engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => 0,
                    'e_recipient_u_id' => $e_message['e_recipient_u_id'], //System
                    'e_message' => 'Failed to send scheduled Drip message because could not find student admission',
                    'e_json' => $json_data,
                    'e_type_id' => 8, //System Error
                    'e_b_id' => $e_message['e_b_id'],
                    'e_r_id' => $e_message['e_r_id'],
                    'e_c_id' => $json_data['i']['i_c_id'],
                ));
                continue;
            }

            //Send this message:
            $this->Comm_model->send_message(array(
                array_merge($json_data['i'], array(
                    'e_initiator_u_id' => 0,
                    'e_recipient_u_id' => $matching_admissions[0]['u_id'],
                    'i_c_id' => $json_data['i']['i_c_id'],
                    'e_b_id' => $e_message['e_b_id'],
                    'e_r_id' => $e_message['e_r_id'],
                )),
            ));

            //Update Engagement:
            $this->Db_model->e_update( $e_message['e_id'] , array(
                'e_cron_job' => 1, //Mark as done
            ));

            //Increase counter:
            $drip_sent++;
        }

        //Echo message for cron job:
        echo $drip_sent.' Drip messages sent';

    }

    function bot_save_files(){

        //Cron Settings: * * * * *

        /*
         * This cron job looks for all engagements with Facebook attachments
         * that are pending upload (i.e. e_cron_job=0) and uploads their
         * attachments to amazon S3 and then changes status to e_cron_job=1
         *
         */

        $max_per_batch = 10; //Max number of scans per run

        $e_pending = $this->Db_model->e_fetch(array(
            'e_cron_job' => 0, //Pending file upload to S3
            'e_type_id >=' => 6, //Messages only
            'e_type_id <=' => 7, //Messages only
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
                                            'e_message' => ( strlen($ep['e_message'])>0 ? $ep['e_message']."\n\n" : '' ).'/attach '.$att['type'].':'.$new_file_url, //Makes the file preview available on the message
                                            'e_cron_job' => 1, //Mark as done
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
                    'e_initiator_u_id' => 0, //System
                    'e_message' => 'cron/bot_save_files() fetched ej_e_blob() that was missing its [entry] value',
                    'e_json' => $json_data,
                    'e_type_id' => 8, //System Error
                ));
            }

            if($counter>=$max_per_batch){
                break; //done for now
            }
        }
        //Echo message for cron job:
        echo $counter.' Incoming Messenger file'.($counter==1?'':'s').' saved to Mench cloud.';
    }

    function create_classes(){

        //First determine all dates we'd need:
        $dates_needed = array();
        $start = strtotime('next monday');
        for($i=0;$i<55;$i++){
            $new_timestamp = $start+($i*7*24*3607); //The extra 7 seconds makes sure we don't get into Sundays
            if(date("D",$new_timestamp)=='Mon'){
                array_push($dates_needed,date("Y-m-d",($start+($i*7*24*3607))));
            } else {
                //Log error:
                $this->Db_model->e_create(array(
                    'e_message' => 'r_sync() generated Class date that was Not a Monday',
                    'e_type_id' => 8, //System Error
                ));
            }
        }


        //Now fetch all Active Bootcamps:
        $bs = $this->Db_model->b_fetch(array(
            'b_status >=' => 2,
        ));

        $stats = array();

        foreach($bs as $b){
            $stats[$b['b_id']] = $this->Db_model->r_sync($b['b_id']);
        }

        echo_json($stats);

    }


    function fb_sync_attachments(){

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
            'e_cron_job' => 0, //Pending Sync
            'e_type_id' => 83, //Message Facebook Sync e_type_id=83
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
                        if($db_result){
                            $success_count++;
                        } else {
                            //Log error:
                            $this->Db_model->e_create(array(
                                'e_message' => 'fb_sync_attachments() failed to sync attachment with Facebook',
                                'e_json' => $ep,
                                'e_type_id' => 8, //Platform Error
                            ));
                        }


                        //Update engagement:
                        $this->Db_model->e_update( $ep['e_id'], array(
                            'e_cron_job' => 1, //Completed
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
        $q = $this->db->query('SELECT u_fname, u_lname, e_initiator_u_id, COUNT(e_id) as received_messages FROM v5_engagements e JOIN v5_users u ON (e.e_initiator_u_id = u.u_id) WHERE e_type_id=6 AND e_timestamp > \''.$after_time.'\' AND e_initiator_u_id>0 AND u_status<=1 GROUP BY e_initiator_u_id, u_status, u_fname, u_lname');
        $new_messages = $q->result_array();
        $notify_messages = array();
        foreach($new_messages as $key=>$nm){

            //Lets see if their inbound messages has been responded by the instructor:
            $messages = $this->Db_model->e_fetch(array(
                'e_type_id IN (6,7)' => null,
                'e_timestamp >' => $after_time,
                '(e_initiator_u_id='.$nm['e_initiator_u_id'].' OR e_recipient_u_id='.$nm['e_initiator_u_id'].')' => null,
            ));

            if(count($messages)>$nm['received_messages']){
                //We also sent some messages, see who sent them, and if we need to notify the admin:
                $last_message = $messages[0]; //This is the latest message
                $new_messages[$key]['notify'] = ( $last_message['e_type_id']==7 && $last_message['e_initiator_u_id']>0 ? 0 : 1 );
            } else {
                //No responses, we must notify:
                $new_messages[$key]['notify'] = 1;
            }


            if($new_messages[$key]['notify']){

                //Lets see who is responsible for this student:
                //Checks to see who is responsible for this user, likely to receive update messages or something...
                $admissions = $this->Db_model->remix_admissions(array(
                    'ru_u_id'	     => $nm['e_initiator_u_id'],
                    'ru_status >='	 => 0, //Instructors can send messages to students with ru_status>=0
                ));
                $active_admission = filter_active_admission($admissions); //We'd need to see which admission to load now

                if($active_admission){

                    unset($notify_fb_ids);
                    $notify_fb_ids = array();
                    $b_data = array(
                        'b_id' => $active_admission['b_id'],
                        'c_objective' => $active_admission['c_objective'],
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
                                'project_data' => $b_data,
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
                if(count($msg['project_data'])>0){
                    $message .= '🎯 '.$msg['project_data']['c_objective']."\n";
                }
                $message .= '💡 Student activity in the past '.round($seconds_ago/3600).' hours:'."\n";
                foreach($msg['message_threads'] as $thread){
                    $message .= "\n".$thread['received_messages'].' message'.show_s($thread['received_messages']).' from '.$thread['u_fname'].' '.$thread['u_lname'];
                }
                if(count($msg['project_data'])>0 && strlen($message)<580){
                    $message .= "\n\n".'https://mench.com/console/'.$msg['project_data']['b_id'];
                }

                $notify_messages[$key]['admin_message'] = $message;

                //Send message to all admins:
                foreach($msg['notify_admins'] as $admin){

                    $this->Comm_model->send_message(array(
                        array(
                            'i_media_type' => 'text',
                            'i_message' => substr($message,0,620), //Make sure this is not too long!
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $admin['u_id'],
                            'e_b_id' => ( isset($msg['project_data']['b_id']) ? $msg['project_data']['b_id'] : 0),
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
            'r.r_status'	=> 1, //Class still Open For Admission
            'ru.ru_status'  => 0, //Application Incomplete
        ));


        $stats = array();
        foreach($incomplete_applications as $admission){

            //Fetch existing reminders sent to this student:
            $reminders_sent = $this->Db_model->e_fetch(array(
                'e_type_id' => 28, //Email sent
                'e_recipient_u_id' => $admission['u_id'],
                'e_r_id' => $admission['r_id'],
                'e_c_id IN (3140,3127,3128,3129,3130)' => null, //The ID of the 5 email reminders https://mench.com/console/53/actionplan
            ));

            $admission_end_time = strtotime($admission['r_start_date']) - 60; //11:59PM the night before start date
            $admission_time = strtotime($admission['ru_timestamp']);




            //Send them a reminder to complete 24 hours after they start, only IF they started their application more than 6 days before the Class start:
            $reminder_c_id = 0;
            if(($admission_time+(6*24*3600))<$admission_end_time && ($admission_time+(24*3600))<time() && !filter($reminders_sent,'e_c_id',3140)){
                $reminder_c_id = 3140;
            } elseif((time()+(72*3600))>$admission_end_time && !filter($reminders_sent,'e_c_id',3127)){
                $reminder_c_id = 3127;
            } elseif((time()+(48*3600))>$admission_end_time && !filter($reminders_sent,'e_c_id',3128)){
                $reminder_c_id = 3128;
            } elseif((time()+(24*3600))>$admission_end_time && !filter($reminders_sent,'e_c_id',3129)){
                $reminder_c_id = 3129;
            } elseif((time()+(2*3600))>$admission_end_time && !filter($reminders_sent,'e_c_id',3130)){
                $reminder_c_id = 3130;
            }

            if($reminder_c_id){
                //Send reminder:
                $this->Comm_model->foundation_message(array(
                    'e_initiator_u_id' => 0,
                    'e_recipient_u_id' => $admission['u_id'],
                    'e_c_id' => $reminder_c_id,
                    'depth' => 0,
                    'e_b_id' => $admission['r_b_id'],
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

    function student_reminder_group_call_starting(){

        //Cron Settings: 0,20,30,50 * * * * (ATTENTION: Logic dependant on these exact times!)

        /*
         * Cron timing designed with the assumption that Group Calls start at either 0 Minutes or 30 minutes (based on calendar restrictions)
         * Sends out 2x reminders before the group call:
         *
         * - 2 hours before call to instructor
         * - 1 Hour before call to students
         * - 10 minutes before call to both student and instructor
         *
         */

        //Cron stats file so we know what happened in each run...
        $stats = array();
        $today = date("N")-1;
        $hour = date("G"); //Based on the cron settings
        $minute = date("i"); //Based on the cron settings

        if(!in_array($minute,array(0,20,30,50))){
            //This should not happen, log an error:
            $error = 'student_reminder_group_call_starting() can only run on minutes [0,20,30,50]. Its now minute ['.$minute.']';
            $this->Db_model->e_create(array(
                'e_message' => $error,
                'e_type_id' => 8, //Platform Error
            ));
            die($error);
        }

        //For every cron we're looking for one of these two:
        $tenmin_start = ( in_array($minute,array(20,50)) ? $hour + ( $minute==20 ? 0.5 : 1 ) : null );
        $onehour_start = ( in_array($minute,array(0,30)) ? $hour + ( $minute==30 ? 1.5 : 1 ) : null );
        $twohour_start = ( in_array($minute,array(0,30)) ? $hour + 1 + ( $minute==30 ? 1.5 : 1 ) : null );

        $running_classes = $this->Db_model->r_fetch(array(
            'r_status' => 2, //Only running classes
            'r_live_office_hours IS NOT NULL' => null, //They have active Group Calls
        ));

        foreach($running_classes as $class) {

            $group_call_schedule = unserialize($class['r_live_office_hours']);

            if(!isset($group_call_schedule[$today]['periods']) || count($group_call_schedule[$today]['periods'])==0){
                //Nothing found for today:
                $stats[$class['r_id']] = 'No group calls scheduled for today.';
                continue;
            }

            if(strlen($class['r_office_hour_instructions'])==0){
                //Ooops, this should not happen:
                $this->Db_model->e_create(array(
                    'e_message' => 'Class with group call schedule is missing contact instructions. Inform instructor.',
                    'e_type_id' => 8, //Platform Error
                    'e_b_id' => $class['r_b_id'],
                    'e_r_id' => $class['r_id'],
                ));

                //Skip this:
                $stats[$class['r_id']] = 'Missing group call instructions. Error logged.';
                continue;
            }

            //Fetch full Project/Class data for this:
            $bs = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bs[0]['this_class'];


            //Make sure Class is still running
            if(time()>$class['r__class_end_time']){
                //Class is finished, but still instructor has not submitted final report which is why r_status=2
                $stats[$class['r_id']] = 'Class already finished.';
                continue;
            }

            //We're trying to populate these if a match is found:
            $student_message = null;
            $instructor_message = null;

            //Let's see:
            foreach($group_call_schedule[$today]['periods'] as $key=>$period){

                $start_hour = hourformat($period[0]);
                //Not mentioning meeting duration for now:
                //$end_hour = hourformat($period[1]);
                //$meeting_duration = $end_hour - $start_hour;

                if($twohour_start && $twohour_start==$start_hour){

                    //Trigger 2 hours notice:
                    $instructor_message = '📅 Reminder: your ['.$bs[0]['c_objective'].'] Project group call should start in 2 hours from now. Your students will receive 2 reminders before the call (1 hour before & 10 minutes before) and will receive the following contact method to join the call:'."\n\n=============\n".$class['r_office_hour_instructions']."\n=============\n\n".'If not correct, you have 1 hour and 50 minutes from now to update this contact method before we share it with you Class:'."\n\n".'https://mench.com/console/'.$class['r_b_id'].'/classes/'.$class['r_id'];

                } elseif($onehour_start && $onehour_start==$start_hour){

                    //Trigger 1 hour notice:
                    $student_message = 'Hi {first_name}, just a reminder that our group call will start in 1 hour from now. I will share instructions on how to join the call 10 minutes prior to the call. Stay tuned 🙌​';

                } elseif($tenmin_start && $tenmin_start==$start_hour){

                    //Trigger 10 minute notice:
                    //TODO we can query last messages and if nothing else was sent from the previous reminder, we can further simplify this:
                    $student_message = 'Our group call is starting in 10 minutes. You can join by following these instructions:'."\n\n".$class['r_office_hour_instructions'];
                    $instructor_message = '{first_name} your class group call should start in 10 minutes. All your students have already been notified 🙌';

                }

                if($student_message || $instructor_message){

                    if($student_message){

                        //Fetch all Students in This Class:
                        $class_students = $this->Db_model->ru_fetch(array(
                            'ru.ru_r_id'	    => $class['r_id'],
                            'ru.ru_status'	    => 4, //Project students
                        ));

                        //Inform all students:
                        foreach($class_students as $u){
                            //Send this message & log sent engagement using the echo_i() function:
                            $this->Comm_model->send_message(array(
                                array(
                                    'i_media_type' => 'text',
                                    'i_message' => $student_message,
                                    'e_initiator_u_id' => 0, //System
                                    'e_recipient_u_id' => $u['u_id'],
                                    'e_b_id' => $class['r_b_id'],
                                    'e_r_id' => $class['r_id'],
                                ),
                            ));
                        }
                    }

                    if($instructor_message){
                        //Fetch co-instructors:
                        $b_instructors = $this->Db_model->ba_fetch(array(
                            'ba.ba_b_id'        => $class['r_b_id'],
                            'ba.ba_status >='   => 1, //Must be an actively assigned instructor
                            'u.u_status >='     => 1, //Must be a user level 1 or higher
                        ));

                        //Send drip message to all students:
                        foreach($b_instructors as $u){
                            //Send this message & log sent engagement using the echo_i() function:
                            $this->Comm_model->send_message(array(
                                array(
                                    'i_media_type' => 'text',
                                    'i_message' => $instructor_message,
                                    'e_initiator_u_id' => 0, //System
                                    'e_recipient_u_id' => $u['u_id'],
                                    'e_b_id' => $class['r_b_id'],
                                    'e_r_id' => $class['r_id'],
                                ),
                            ));
                        }
                    }

                    //Save for reporting:
                    $stats[$class['r_id']] = $student_message.' '.$instructor_message;

                    //Nothing more to do here:
                    break;
                }
            }

            if(!$student_message && !$instructor_message){
                //Time not matched:
                $stats[$class['r_id']] = 'Time not matched';
            }
        }

        //Show stats:
        echo_json($stats);
    }

    function student_reminder_complete_task(){
        //Cron Settings: 45 * * * *
        //Send reminders to students to complete their Steps:

        $admissions = $this->Db_model->ru_fetch(array(
            'r.r_status'	    => 2, //Running Class
            'ru.ru_status'      => 4, //Admitted Students
            '(ru.ru_cache__current_task <= r.r_cache__current_milestone)' => null, //Students that are behind
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

            //Fetch full Project/Class data for this:
            $bs = fetch_action_plan_copy($admission['r_b_id'], $admission['r_id']);
            $class = $bs[0]['this_class'];

            //See what % of the class time has elapsed?
            $elapsed_class_percentage = round((time()-$class['r__class_start_time'])/($class['r__class_end_time']-$class['r__class_start_time']),5);

            foreach ($reminder_index as $logic){
                if($elapsed_class_percentage>=$logic['time_elapsed']){
                    if($admission['ru_cache__completion_rate']<$logic['progress_below']){
                        //See if we have reminded them already about this:
                        $reminders_sent = $this->Db_model->e_fetch(array(
                            'e_type_id' => 7, //Message sent
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_r_id' => $admission['r_id'],
                            'e_c_id' => $logic['reminder_c_id'],
                        ));

                        if(count($reminders_sent)==0){

                            //Nope, send this message out:
                            $this->Comm_model->foundation_message(array(
                                'e_initiator_u_id' => 0, //System
                                'e_recipient_u_id' => $admission['u_id'],
                                'e_c_id' => $logic['reminder_c_id'],
                                'depth' => 0,
                                'e_b_id' => $admission['r_b_id'],
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