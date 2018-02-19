<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}

    /* ******************************
     * System
     * /usr/bin/php /var/www/us/index.php cron student_reminder_complete_application
     ****************************** */

    function class_kickstart(){

        //Cron Settings: 0,30 * * * *
        //This function is solely responsible to get the class started and dispatch its very first milestone messages IF it does start
        //Searches for any class that might be starting and kick starts its messages:
        $classes = $this->Db_model->r_fetch(array(
            'r_status' => 1,
            'r_start_date <=' => date("Y-m-d"),
        ));

        //Include email model for certain communications:
        $this->load->model('Email_model');
        $start_times = $this->config->item('start_times');

        //Generate stats for this cron run:
        $stats = array();

        foreach($classes as $key=>$class){

            //Make sure the start time has already passed:
            if( time() < ( strtotime($class['r_start_date']) + (($class['r_start_time_mins']-1)*60) )){

                //Add to report:
                $stats[$class['r_id']] = array(
                    'b_id' => $class['r_b_id'],
                    'initial_status' => $class['r_status'],
                    'message' => 'Class starts at '.$start_times[$class['r_start_time_mins']].' PST (Not yet started).',
                );

                //Not started yet!
                continue;
            }

            //Fetch full Bootcamp/Class data for this
            //See if we have a copy of this Action Plan:
            //This ensures we do not make another Copy if the instructor has already cached a copy before Class start time
            //It's a feature that is not publicly available, and would likely not happen
            $bootcamps = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bootcamps[0]['this_class'];


            //Append stats array for cron reporting:
            $stats[$class['r_id']] = array(
                'b_id' => $class['r_b_id'],
                'initial_status' => $class['r_status'],
                'new_status' => 0, //To be updated
                'students' => array(
                    'rejected_incomplete' => 0, //Rejected because their application was incomplete by the time the class started
                    'rejected_pending' => 0, //Rejected because their application was not approved by instructor by the time the class started
                    'rejected_class_cancelled' => 0, //Rejected because the class did not start (see reasons below)
                    'accepted' => array(
                        'menchbot_active' => 0,
                        'menchbot_inactive' => 0,
                    ),
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
                    $this->Email_model->email_intent($class['r_b_id'],3016,$admission);
                }
            }


            //Auto reject all applications that were not yet accepted:
            $pending_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 2,
            ));

            if(count($pending_admissions)>0){

                //Update counter:
                $stats[$class['r_id']]['students']['rejected_pending'] = count($pending_admissions);

                foreach($pending_admissions as $admission){

                    //Auto reject:
                    $this->Db_model->ru_update( $admission['ru_id'] , array('ru_status' => -1));

                    //Inform the student of rejection:
                    $this->Email_model->email_intent($class['r_b_id'],2799,$admission);

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


            //Find first due milestone to dispatch its messages to all students:
            $first_milestone_c_id = 0;
            foreach($bootcamps[0]['c__child_intents'] as $milestone){
                if($milestone['c_status']>=1){
                    $first_milestone_c_id = $milestone['c_id'];
                    break;
                }
            }


            //Now make sure class meets are requirements to get started with the following conditions:
            $cancellation_reason = null; //If remains Null we're good to get started
            if($bootcamps[0]['b_status']<2){
                $cancellation_reason = 'Bootcamp was not published';
            } elseif($first_milestone_c_id==0) {
                $cancellation_reason = 'Bootcamp did not have any published Milestones';
            } elseif(count($accepted_admissions)==0) {
                $cancellation_reason = 'no students applied/got-admitted into this Class';
            } elseif(count($accepted_admissions)<$class['r_min_students']) {
                $cancellation_reason = 'only ['.count($accepted_admissions).'] students were admitted which was below the minimum requirement of ['.$class['r_min_students'].']';
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
                        $this->Email_model->email_intent($class['r_b_id'],3017,$admission);

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
                        'minimum' => $class['r_min_students'],
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
                    'r_cache__current_milestone' => 1, //First Milestone is getting started, note it here
                ));


                //Take snapshot of Action Plan ONLY IF not already taken for this class:
                if(!$bootcamps[0]['is_copy']){
                    //Save Action Plan only if not already done so:
                    $this->Db_model->snapshot_action_plan($bootcamps[0]['b_id'],$class['r_id']);
                }


                //Dispatch appropriate Message to Students
                foreach($accepted_admissions as $admission){
                    if($admission['u_fb_id']>0){

                        //Update counter:
                        $stats[$class['r_id']]['students']['accepted']['menchbot_active']++;

                        //They already have MenchBot activated, send message:
                        tree_message($first_milestone_c_id, 0, '381488558920384', $admission['u_id'], 'REGULAR', $class['r_b_id'], $class['r_id']);

                    } else {

                        //Update counter:
                        $stats[$class['r_id']]['students']['accepted']['menchbot_inactive']++;

                        //No MenchBot activated yet! Remind them again:
                        $this->Email_model->email_intent($class['r_b_id'],3120,$admission);

                    }
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

    function next_milestone(){

        //Cron Settings: 0,30 * * * *
        //Moves the class from one milestone to another, Only applicable for the 2nd milesone or higher
        //Also closes a Class if all Milestones are done...
        //The first milestone is triggered with the class_kickstart() cron function above

        $running_classes = $this->Db_model->r_fetch(array(
            'r_status' => 2, //Only running classes
        ));

        //Cron stats file so we know what happened in each run...
        $stats = array();

        foreach($running_classes as $class){

            //Fetch full Bootcamp/Class data for this:
            $bootcamps = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bootcamps[0]['this_class'];

            //Where is the class at now?
            if($class['r__current_milestone']<0){

                //Class has ended, take necessary actions here:
                //All good, ove on to dispatch phase...
                //Fetch all the class students, and see which ones advance to this new milestone:
                $accepted_admissions = $this->Db_model->ru_fetch(array(
                    'ru.ru_r_id'	    => $class['r_id'],
                    'ru.ru_status'	    => 4, //Admitted students
                ));

                if(count($accepted_admissions)==0){

                    //Ooops, this is an error that should not happen, log engagemeng:
                    $this->Db_model->e_create(array(
                        'e_message' => 'ERROR: Class ended with 0 admitted students',
                        'e_json' => $bootcamps[0],
                        'e_type_id' => 8, //Platform Error
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));

                    //Show in Cron stats:
                    $stats[$class['r_id']] = 'Class ended without any admitted students';

                } else {

                    //Do a count for stat reporting:
                    $completion_stats = array(
                        'completed' => array(),
                        'incomplete_activated' => array(),
                        'incomplete_inactive' => array(),
                    );

                    //Loop through students to see how did everyone do:
                    foreach($accepted_admissions as $admission){
                        //See where the student is at, did they finish the previous milestone?
                        if($admission['u_fb_id']>0 && $admission['ru_cache__current_milestone']>$class['r__total_milestones']){
                            array_push($completion_stats['completed'],$admission);
                        } elseif($admission['u_fb_id']>0) {
                            array_push($completion_stats['incomplete_activated'],$admission);
                        } else {
                            //Nothing we would do with these students, only for statistics purposes:
                            array_push($completion_stats['incomplete_inactive'],$admission);
                        }
                    }

                    //How did the class do overall?
                    $qualified_students = count($accepted_admissions) - count($completion_stats['incomplete_inactive']);
                    if($qualified_students<=0){
                        //All students did not activate!
                        $r_cache__completion_rate = 0;
                    } else {
                        $r_cache__completion_rate = number_format((count($completion_stats['completed']) / $qualified_students),3);
                    }

                    $this->Facebook_model->batch_messages('381488558920384', '1443101719058431', array());


                    //Fetch all Instructors:
                    $bootcamp_instructors = $this->Db_model->ba_fetch(array(
                        'ba.ba_b_id'        => $class['r_b_id'],
                        'ba.ba_status >='   => 1, //Must be an actively assigned instructor
                        'u.u_status >='     => 1, //Must be a user level 1 or higher
                        'u.u_fb_id >'	    => 0, //Activated MenchBot
                    ));

                    //Construct the review message and button:
                    $review_message = 'Your final step is to rate & review your experience with ​​​​'.$bootcamp_instructors[0]['u_fname'].' '.$bootcamp_instructors[0]['u_lname'].' and help improve future Classes:';
                    $review_button = '📣 Review '.$bootcamp_instructors[0]['u_fname']; //Will show a button to rate/review Lead Instructor


                    //Graduate Students:
                    foreach($completion_stats['completed'] as $admission){

                        //Send message:
                        $this->Facebook_model->batch_messages('381488558920384', $admission['u_fb_id'], array(
                            echo_i(array(
                                'i_media_type' => 'text',
                                'i_message' => '{first_name} your class just ended. Congratulations for completing all Milestones on-time 🎉​​​',
                                'e_initiator_u_id' => 0,
                                'e_recipient_u_id' => $admission['u_id'],
                                'e_b_id' => $class['r_b_id'],
                                'e_r_id' => $class['r_id'],
                            ), $admission['u_fname'], true ),
                            echo_i(array(
                                'i_media_type' => 'text',
                                'i_message' => $review_message,
                                'i_button' => $review_button,
                                'i_url' => 'https://mench.co/my/review/'.$admission['ru_id'].'/'.substr(md5($admission['ru_id'].'r3vi3wS@lt'),0,6),
                                'e_initiator_u_id' => 0,
                                'e_recipient_u_id' => $admission['u_id'],
                                'e_b_id' => $class['r_b_id'],
                                'e_r_id' => $class['r_id'],
                            ), $admission['u_fname'], true ),
                        ));


                        //Adjust status in admissions table:
                        $this->Db_model->ru_update( $admission['ru_id'] , array(
                            'ru_status' => 7, //Graduate
                        ));

                        //Log Engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_json' => $admission,
                            'e_type_id' => 64, //Student Graduated
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));

                    }

                    //Incomplete & Activated Students:
                    foreach($completion_stats['incomplete_activated'] as $admission){

                        //Send message:
                        $this->Facebook_model->batch_messages('381488558920384', $admission['u_fb_id'], array(
                            echo_i(array(
                                'i_media_type' => 'text',
                                'i_message' => '{first_name} your class just ended. You can no longer submit Tasks but you will have life-time access to all Milestones and Tasks which are now unlocked.​',
                                'e_initiator_u_id' => 0,
                                'e_recipient_u_id' => $admission['u_id'],
                                'e_b_id' => $class['r_b_id'],
                                'e_r_id' => $class['r_id'],
                            ), $admission['u_fname'], true ),
                            echo_i(array(
                                'i_media_type' => 'text',
                                'i_message' => $review_message,
                                'i_button' => $review_button,
                                'i_url' => 'https://mench.co/my/review/'.$admission['ru_id'].'/'.substr(md5($admission['ru_id'].'r3vi3wS@lt'),0,6),
                                'e_initiator_u_id' => 0,
                                'e_recipient_u_id' => $admission['u_id'],
                                'e_b_id' => $class['r_b_id'],
                                'e_r_id' => $class['r_id'],
                            ), $admission['u_fname'], true ),
                        ));


                        //Adjust status in admissions table:
                        $this->Db_model->ru_update( $admission['ru_id'] , array(
                            'ru_status' => 6, //Incomplete
                        ));

                        //Log Engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_json' => $admission, //Stores whether or not they have u_fb_id activated at this time
                            'e_type_id' => 71, //Student Incomplete Class
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));

                    }

                    //Incomplete & Inactive Students:
                    foreach($completion_stats['incomplete_inactive'] as $admission){

                        //Adjust status in admissions table:
                        $this->Db_model->ru_update( $admission['ru_id'] , array(
                            'ru_status' => 6, //Incomplete
                        ));

                        //Log Engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_json' => $admission, //Stores whether or not they have u_fb_id activated at this time
                            'e_type_id' => 71, //Student Incomplete Class
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));

                    }


                    //Update Class:
                    $this->Db_model->r_update( $class['r_id'] , array(
                        'r_status' => 3, //Completed
                        'r_cache__current_milestone' => -1, //meaning its now complete
                        'r_cache__completion_rate' => $r_cache__completion_rate,
                    ));


                    //Log Engagement:
                    $industry_completion = 0.10; //Like Udemy, etc...
                    $completion_message = 'Your ['.$bootcamps[0]['c_objective'].'] Class of ['.time_format($class['r_start_date'],2).'] has ended with a ['.round($r_cache__completion_rate*100).'%] completion rate. From the total students of ['.$qualified_students.'], you helped ['.count($completion_stats['completed']).'] of them graduate by completing all Milestones on-time.'.( $r_cache__completion_rate>$industry_completion ? ' Great job on exceeding the e-learning industry average completion rate of '.(round($industry_completion*100)).'% 🎉🎉🎉​' : '' );

                    //Log Engagement for Class Completion:
                    $this->Db_model->e_create(array(
                        'e_initiator_u_id' => 0, //System
                        'e_message' => $completion_message,
                        'e_type_id' => 69, //Class Completed
                        'e_json' => $accepted_admissions,
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));

                    //Send message to instructor team:
                    foreach($bootcamp_instructors as $u){
                        //Send this message & log sent engagement using the echo_i() function:
                        $this->Facebook_model->batch_messages('381488558920384', $u['u_fb_id'], array(echo_i(array(
                            'i_media_type' => 'text',
                            'i_message' => $completion_message,
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $u['u_id'],
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ), $u['u_fname'], true )));
                    }

                    //Show in Cron stats:
                    $stats[$class['r_id']] = $completion_message;

                }

            } elseif($class['r__current_milestone']<=1){

                //Should never be 0 (meaning not started) as r_status=2 (means it must have started)
                //ALso if its 1, it means its still at the first milestone, nothing we need to do here...
                $stats[$class['r_id']] = 'Skipped. Class has not passed its first week yet.';

            } elseif($class['r__current_milestone']<$class['r_cache__current_milestone']){

                //This should not happen as it means the Class has somehow gone backwards, log error:
                $this->Db_model->e_create(array(
                    'e_message' => 'Class seems to be moving backwards as current Milestone is ['.$class['r__current_milestone'].'] and DB cache is ['.$class['r_cache__current_milestone'].']',
                    'e_json' => $bootcamps[0],
                    'e_type_id' => 8, //Platform Error
                    'e_b_id' => $class['r_b_id'],
                    'e_r_id' => $class['r_id'],
                ));

            } elseif($class['r__current_milestone']==$class['r_cache__current_milestone']){

                //Still during the current milestone
                $stats[$class['r_id']] = 'Already up-to-date. Current Milestone is ['.$class['r__current_milestone'].'] and DB cache is ['.$class['r_cache__current_milestone'].']';

            } elseif($class['r__current_milestone']>$class['r_cache__current_milestone']){

                //We have advanced since the DB:
                //r__current_milestone (real) milestone is 2 or higher
                //First the c_id of the milestone to dispatch its messages:
                $new_milestone_c_id = 0;
                foreach($bootcamps[0]['c__child_intents'] as $milestone){
                    if($milestone['c_status']>=1 && $milestone['cr_outbound_rank']==$class['r__current_milestone']){
                        //Gotcha, this is it!
                        $new_milestone_c_id = $milestone['c_id'];
                        break;
                    }
                }

                if(!$new_milestone_c_id){

                    //OOps, this should not happen, notify admin:
                    $stats[$class['r_id']] = 'ERROR: Could not find new Milestone c_id with r__current_milestone = cr_outbound_rank = ['.$class['r__current_milestone'].']';

                    //Log Error engagement:
                    $this->Db_model->e_create(array(
                        'e_message' => $stats[$class['r_id']],
                        'e_json' => $bootcamps[0],
                        'e_type_id' => 8, //Platform Error
                        'e_b_id' => $class['r_b_id'],
                        'e_r_id' => $class['r_id'],
                    ));

                } else {

                    //All good, ove on to dispatch phase...
                    //Fetch all the class students, and see which ones advance to this new milestone:
                    $accepted_admissions = $this->Db_model->ru_fetch(array(
                        'ru.ru_r_id'	    => $class['r_id'],
                        'ru.ru_status'	    => 4, //Admitted students
                        'u.u_fb_id >'	    => 0, //MenchBot Activated ONLY, otherwise they should be activated to join these communications
                    ));

                    if(count($accepted_admissions)>0){

                        //Do a count for stat reporting:
                        $ontime = 0;
                        $behind = array();

                        //Loop through students:
                        foreach($accepted_admissions as $admission){
                            //See where the student is at, did they finish the previous milestone?
                            if($admission['ru_cache__current_milestone']>=$class['r__current_milestone']){

                                $ontime++;

                                //Yes, they are up to date:
                                tree_message($new_milestone_c_id, 0, '381488558920384', $admission['u_id'], 'REGULAR', $class['r_b_id'], $class['r_id']);

                            } else {

                                //We do the message later to include the total number of students who advanced:
                                array_push($behind,$admission);

                            }
                        }

                        if(count($behind)>0){
                            foreach($behind as $admission){
                                //Ooops, they have yet not finished, notify them that class has moved on:
                                $this->Facebook_model->batch_messages('381488558920384', $admission['u_fb_id'], array(echo_i(array(
                                    'i_media_type' => 'text',
                                    'i_message' => 'Hi {first_name} 👋​ We just moved to our next 🚩 ​Milestone!'.( $ontime>0 ? ' '.$ontime.' of your classmate'.show_s($ontime).' progressed to '.$bootcamps[0]['b_sprint_unit'].' '.$class['r__current_milestone'].'.' : '').' I would love to know what is preventing you from finishing your remaining '.$bootcamps[0]['b_sprint_unit'].' '.$admission['ru_cache__current_milestone'].' tasks and would be happy to assist you in marching forward 🙌 Let\'s do it!​',
                                    'e_initiator_u_id' => 0,
                                    'e_recipient_u_id' => $admission['u_id'],
                                    'e_b_id' => $class['r_b_id'],
                                    'e_r_id' => $class['r_id'],
                                ), $admission['u_fname'], true )));
                            }
                        }

                        //Update new status to classes:
                        $this->Db_model->r_update( $class['r_id'] , array('r_cache__current_milestone' => $class['r__current_milestone']));

                        //Append message to stats:
                        $stats[$class['r_id']] = 'Moved from ['.ucwords($bootcamps[0]['b_sprint_unit']).' '.$class['r_cache__current_milestone'].'] to ['.ucwords($bootcamps[0]['b_sprint_unit']).' '.$class['r__current_milestone'].'] with ['.$ontime.' ON-TIME] and ['.count($behind).' BEHIND] students';

                        //Log Engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_message' => $stats[$class['r_id']],
                            'e_json' => array(
                                'bootcamp' => $bootcamps[0],
                                'accepted_admissions' => $accepted_admissions,
                            ),
                            'e_type_id' => 61, //Class Milestone Advanced
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                            'e_c_id' => $new_milestone_c_id,
                        ));

                    }
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
            'e_type_id' => 52, //Scheduled Drip
            'e_timestamp <=' => date("Y-m-d H:i:s" ), //Message is due
            //Some standard checks to make sure, these should all be true:
            'e_r_id >' => 0,
            'e_c_id >' => 0,
            'e_b_id >' => 0,
            'e_recipient_u_id >' => 0,
        ), 200, true);

        $drip_sent = 0;
        foreach($e_pending as $e_message){

            //Fetch user data:
            $matching_admissions = $this->Db_model->ru_fetch(array(
                'ru_u_id' => $e_message['e_recipient_u_id'],
                'ru_r_id' => $e_message['e_r_id'],
                'ru_status >=' => 4, //Active student
                'r_status' => 2, //Running Class
            ));

            if(count($matching_admissions)<1){
                //Something has changed since this Drip has been scheduled...
                return false;
            }

            //Prepare variables:
            $json_data = unserialize($e_message['ej_e_blob']);

            //Increase counter:
            $drip_sent++;

            //Send this message:
            $this->Facebook_model->batch_messages('381488558920384', $matching_admissions[0]['u_fb_id'], array(echo_i(array_merge( $json_data['i'] , array(
                'e_initiator_u_id' => 0,
                'e_recipient_u_id' => $matching_admissions[0]['u_id'],
                'i_c_id' => $json_data['i']['i_c_id'],
                'e_b_id' => $e_message['e_b_id'],
                'e_r_id' => $e_message['e_r_id'],
            )), $matching_admissions[0]['u_fname'],true )));

            //Update Engagement:
            $this->Db_model->e_update( $e_message['e_id'] , array(
                'e_cron_job' => 1, //Mark as done
            ));
        }

        //Echo message for cron job:
        echo $drip_sent.' Drip messages sent';
    }

    function deprecated__drip(){

        /*
         * This was the older cron for Drip messages that is now retired...
         *
         * Keeping it because it has a logic of Intent/Milestone-Duration distribution method
         * which may be useful later on...
         *
         * */
        exit;
        //Cron Settings: 15,45 * * * *
        //Set drip setting variables:
        $drip_settings = array(
            'buffer_bootcamp_start' => 0.1,
            'buffer_bootcamp_end' => 0.1,
            'buffer_class_start' => 0.1,
            'buffer_class_end' => 0.1,
        );

        //Now fetch all active classes:
        $classes = $this->Db_model->r_fetch(array(
            'r_status' => 2, //Running
        ));

        //Loop through the active classes to see whatssup!
        $results = array();
        foreach($classes as $class){

            //Fetch full Bootcamp/Class data for this:
            $bootcamps = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bootcamps[0]['this_class'];


            //Make sure that we are in the middle of an active Milestone:
            if($class['r__current_milestone']<=0){
                //This class has no active milestones!
                //Should not happen as its status was 2!
                continue;
            }

            //Now lets loop through the Bootcamp and Milestone levels to see if we have any drip messages pending
            $drip_stats = array(
                'd_bootcamp' => array(
                    'c_id' => $bootcamps[0]['b_c_id'],
                    'send' => array(),
                    'send_ids' => array(),
                    'sent' => array(),
                    'sent_ids' => array(),
                    'drips_sent' => 0, //Reflects total drip messages sent to students at this level
                ),
                'd_milestone' => array(
                    'current_milestone' => array(), //Will be populated later...
                    'c_id' => 0, //Will be populated later...
                    'send' => array(),
                    'send_ids' => array(),
                    'sent' => array(),
                    'sent_ids' => array(),
                    'drips_sent' => 0, //Reflects total drip messages sent to students at this level
                ),
            );



            /* **********************************
             * **********************************
             * Bootcamp-Level Drip Messages
             * **********************************
             * **********************************
             */

            //Addup total Bootcamp drip messages:
            foreach($bootcamps[0]['c__messages'] as $i){
                if($i['i_status']==2){
                    array_push($drip_stats['d_bootcamp']['send'],$i);
                    array_push($drip_stats['d_bootcamp']['send_ids'],$i['i_id']);
                }
            }

            //Any drip messages at this level?
            if(count($drip_stats['d_bootcamp']['send'])>0){

                //Yes, lets see what has been sent so far from these messages
                $drip_stats['d_bootcamp']['sent'] = $this->Db_model->e_fetch(array(
                    'e_r_id' => $class['r_id'],
                    'e_type_id' => 52, //Drip sent already
                    'e_c_id' => $drip_stats['d_bootcamp']['c_id'],
                    'e_i_id IN ('.join(',',$drip_stats['d_bootcamp']['send_ids']).')' => null, //Only consider the currently active, as the instructor might have deleted some...
                ));
                foreach($drip_stats['d_bootcamp']['sent'] as $i){
                    array_push($drip_stats['d_bootcamp']['sent_ids'],$i['e_i_id']);
                }

                //Calculate timing:
                $drip_stats['d_bootcamp']['start'] = $class['r__class_start_time'];
                $drip_stats['d_bootcamp']['end'] = $class['r__class_end_time'];
                //Whats the total class duration:
                $drip_stats['d_bootcamp']['duration'] = ( $drip_stats['d_bootcamp']['end'] - $drip_stats['d_bootcamp']['start'] );
                //See how much of the start we'd need to exclude:
                $drip_stats['d_bootcamp']['remove_from_start'] = $drip_stats['d_bootcamp']['duration'] * $drip_settings['buffer_bootcamp_start'];
                //And form the end:
                $drip_stats['d_bootcamp']['remove_from_end'] = $drip_stats['d_bootcamp']['duration'] * $drip_settings['buffer_bootcamp_end'];
                //This gives us the effective drip window that within which drip messages would be sent:
                $drip_stats['d_bootcamp']['effective_drip_frame'] = $drip_stats['d_bootcamp']['duration'] - $drip_stats['d_bootcamp']['remove_from_start'] - $drip_stats['d_bootcamp']['remove_from_end'];
                //Lets see how far apart each message should be:
                $drip_stats['d_bootcamp']['drip_sequences'] = round($drip_stats['d_bootcamp']['effective_drip_frame'] / (count($drip_stats['d_bootcamp']['send']) + 1));
                //And calculate the exact timing of each message based on how many has been sent so far...
                $drip_stats['d_bootcamp']['next_drip_due'] = $drip_stats['d_bootcamp']['start'] + $drip_stats['d_bootcamp']['remove_from_start'] + ((count($drip_stats['d_bootcamp']['sent'])+1)*$drip_stats['d_bootcamp']['drip_sequences']);
                $drip_stats['d_bootcamp']['next_drip_due_formatted'] = date("Y-m-d H:i:s", $drip_stats['d_bootcamp']['next_drip_due']);
                //For QA purposes show all drip messages due:
                $drip_stats['d_bootcamp']['all_drips'] = array();
                foreach($drip_stats['d_bootcamp']['send'] as $key=>$i){
                    $drip_stats['d_bootcamp']['all_drips'][($key+1)] = date("Y-m-d H:i:s",($drip_stats['d_bootcamp']['start'] + $drip_stats['d_bootcamp']['remove_from_start'] + (($key+1)*$drip_stats['d_bootcamp']['drip_sequences'])));
                }


                //See if we have any Drip messages left to be sent, and if the time for sending them has arrived:
                if( count($drip_stats['d_bootcamp']['sent'])<count($drip_stats['d_bootcamp']['send']) && time()>$drip_stats['d_bootcamp']['next_drip_due'] ){
                    //We Need to send the next Drip message in line:
                    foreach($drip_stats['d_bootcamp']['send'] as $i){
                        //Make sure this has not been sent before...
                        //Note that if the instructor changes the order of the Drips mid-way, this would still work, kind of...
                        if(!in_array($i['i_id'],$drip_stats['d_bootcamp']['sent_ids'])){

                            //Fetch active students in this class:
                            $class_students = $this->Db_model->ru_fetch(array(
                                'ru.ru_r_id'	    => $class['r_id'],
                                'ru.ru_status'	    => 4, //Bootcamp students
                                'u.u_fb_id >'	    => 0, //Activated MenchBot
                            ));
                            $drip_stats['d_bootcamp']['drips_sent'] = count($class_students);

                            //Did we find any?
                            if($drip_stats['d_bootcamp']['drips_sent']>0){

                                //Send drip message to all students:
                                foreach($class_students as $u){
                                    //Send this message & log sent engagement using the echo_i() function:
                                    $this->Facebook_model->batch_messages('381488558920384', $u['u_fb_id'], array(echo_i(array_merge( $i , array(
                                        'e_initiator_u_id' => 0, //System
                                        'e_recipient_u_id' => $u['u_id'],
                                        'e_b_id' => $class['r_b_id'],
                                        'e_r_id' => $class['r_id'],
                                    )), $u['u_fname'], true )));
                                }

                                //Log engagement for the entire Drip batch:
                                //TODO log per student
                                $this->Db_model->e_create(array(
                                    'e_initiator_u_id' => 0, //System
                                    'e_recipient_u_id' => 0, //No particular person, likely a group of students
                                    'e_message' => 'Bootcamp-level drip message sent to all '.count($class_students).' class students',
                                    'e_json' => array(
                                        'i' => $i,
                                        'drip_stats' => $drip_stats['d_bootcamp'],
                                    ),
                                    'e_type_id' => 52, //Drip sent, Instructors are subscribed and will be notified about this...
                                    'e_b_id' => $class['r_b_id'],
                                    'e_r_id' => $class['r_id'],
                                    'e_i_id' => $i['i_id'],
                                    'e_c_id' => $drip_stats['d_bootcamp']['c_id'],
                                ));
                            }

                            //We always will send 1 drip message at a time, so break here:
                            break;
                        }
                    }
                }
            }


            /* **********************************
             * **********************************
             * Milestone-Level Drip Messages
             * **********************************
             * **********************************
             */

            //Addup total milestone Drip messages for current milestone:
            foreach($bootcamps[0]['c__child_intents'] as $milestone){
                if($milestone['cr_outbound_rank']==$class['r__current_milestone']){

                    //We found the active Milestone for this class:
                    $drip_stats['d_milestone']['current_milestone'] = $milestone;
                    $drip_stats['d_milestone']['c_id'] = $milestone['c_id'];

                    //Lets see if it has any Drip Messages:
                    foreach($milestone['c__messages'] as $i){
                        if($i['i_status']==2){
                            array_push($drip_stats['d_milestone']['send'],$i);
                            array_push($drip_stats['d_milestone']['send_ids'],$i['i_id']);
                        }
                    }

                    //We're done here:
                    break;
                }
            }

            //Any drip messages at this level?
            if(count($drip_stats['d_milestone']['send'])>0){

                //Yes, lets see what has been sent so far from these messages
                $drip_stats['d_milestone']['sent'] = $this->Db_model->e_fetch(array(
                    'e_r_id' => $class['r_id'],
                    'e_type_id' => 52, //Drip sent already
                    'e_c_id' => $drip_stats['d_milestone']['c_id'],
                    'e_i_id IN ('.join(',',$drip_stats['d_milestone']['send_ids']).')' => null, //Only consider the currently active, as the instructor might have deleted some...
                ));
                foreach($drip_stats['d_milestone']['sent'] as $i){
                    array_push($drip_stats['d_milestone']['sent_ids'],$i['e_i_id']);
                }

                //Calculate timing:
                $drip_stats['d_milestone']['end'] = $class['r__milestones_due'][$class['r__current_milestone']];
                $drip_stats['d_milestone']['duration'] = $bootcamps[0]['c__milestone_secs'];
                $drip_stats['d_milestone']['start'] = $drip_stats['d_milestone']['end'] - $drip_stats['d_milestone']['duration'];
                //See how much of the start we'd need to exclude:
                $drip_stats['d_milestone']['remove_from_start'] = $drip_stats['d_milestone']['duration'] * $drip_settings['buffer_class_start'];
                //And form the end:
                $drip_stats['d_milestone']['remove_from_end'] = $drip_stats['d_milestone']['duration'] * $drip_settings['buffer_class_end'];

                //Rest is same as Bootcamp calculation:

                //This gives us the effective drip window that within which drip messages would be sent:
                $drip_stats['d_milestone']['effective_drip_frame'] = $drip_stats['d_milestone']['duration'] - $drip_stats['d_milestone']['remove_from_start'] - $drip_stats['d_milestone']['remove_from_end'];
                //Lets see how far apart each message should be:
                $drip_stats['d_milestone']['drip_sequences'] = round($drip_stats['d_milestone']['effective_drip_frame'] / (count($drip_stats['d_milestone']['send']) + 1));
                //And calculate the exact timing of each message based on how many has been sent so far...
                $drip_stats['d_milestone']['next_drip_due'] = $drip_stats['d_milestone']['start'] + $drip_stats['d_milestone']['remove_from_start'] + ((count($drip_stats['d_milestone']['sent'])+1)*$drip_stats['d_milestone']['drip_sequences']);
                $drip_stats['d_milestone']['next_drip_due_formatted'] = date("Y-m-d H:i:s", $drip_stats['d_milestone']['next_drip_due']);
                //For QA purposes show all drip messages due:
                $drip_stats['d_milestone']['all_drips'] = array();
                foreach($drip_stats['d_milestone']['send'] as $key=>$i){
                    $drip_stats['d_milestone']['all_drips'][($key+1)] = date("Y-m-d H:i:s",($drip_stats['d_milestone']['start'] + $drip_stats['d_milestone']['remove_from_start'] + (($key+1)*$drip_stats['d_milestone']['drip_sequences'])));
                }


                //See if we have any Drip messages left to be sent, and if the time for sending them has arrived:
                if( count($drip_stats['d_milestone']['sent'])<count($drip_stats['d_milestone']['send']) && time()>$drip_stats['d_milestone']['next_drip_due'] ){
                    //We Need to send the next Drip message in line:
                    foreach($drip_stats['d_milestone']['send'] as $i){
                        //Make sure this has not been sent before...
                        //Note that if the instructor changes the order of the Drips mid-way, this would still work, kind of...
                        if(!in_array($i['i_id'],$drip_stats['d_milestone']['sent_ids'])){

                            //Fetch active students who are at the current milestone:
                            $fetch_filters = array(
                                'ru.ru_r_id'	            => $class['r_id'],
                                'ru.ru_status'	            => 4, //Bootcamp students
                                'u.u_fb_id >'	            => 0, //Activated MenchBot
                            );

                            if($class['r__current_milestone']>=2){
                                //Since we're at Week 2, we only need to send these Drip messages to students who have reached this week:
                                $fetch_filters['ru.ru_cache__current_milestone >='] = $class['r__current_milestone'];
                            }

                            $milestone_students = $this->Db_model->ru_fetch($fetch_filters);

                            $drip_stats['d_milestone']['drips_sent'] = count($milestone_students);

                            //Did we find any?
                            if($drip_stats['d_milestone']['drips_sent']>0){

                                //Send drip message to all students:
                                foreach($milestone_students as $u){
                                    //Send this message & log sent engagement using the echo_i() function:
                                    $this->Facebook_model->batch_messages('381488558920384', $u['u_fb_id'], array(echo_i(array_merge( $i , array(
                                        'e_initiator_u_id' => 0, //System
                                        'e_recipient_u_id' => $u['u_id'],
                                        'e_b_id' => $class['r_b_id'],
                                        'e_r_id' => $class['r_id'],
                                    )), $u['u_fname'], true )));
                                }

                                //Log engagement for the entire Drip batch:
                                //TODO change to logging per student
                                $this->Db_model->e_create(array(
                                    'e_initiator_u_id' => 0, //System
                                    'e_recipient_u_id' => 0, //No particular person, likely a group of students
                                    'e_message' => 'Milestone-level drip message sent to '.count($milestone_students).' students at this milestone',
                                    'e_json' => array(
                                        'i' => $i,
                                        'drip_stats' => $drip_stats['d_milestone'],
                                    ),
                                    'e_type_id' => 52, //Drip sent, Instructors are subscribed and will be notified about this...
                                    'e_b_id' => $class['r_b_id'],
                                    'e_r_id' => $class['r_id'],
                                    'e_i_id' => $i['i_id'],
                                    'e_c_id' => $drip_stats['d_milestone']['c_id'],
                                ));
                            }

                            //We always will send 1 drip message at a time, so break here:
                            break;
                        }
                    }
                }
            }

            //Add to results array:
            $results[$class['r_id']] = $drip_stats;
        }

        //Show results:
        echo_json($results);
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
        ), $max_per_batch, true);

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

    /* ******************************
	 * Instructor
	 ****************************** */

    function instructor_notify_student_activity(){

        //Cron Settings: 0 */2 * * * *
        //Runs every hour and informs instructors/admins of new messages received recently
        //Define settings:
        $seconds_ago = 7200; //Defines how much to go back, should be equal to cron job frequency

        //Create query:
        $mench_cs_fb_ids = $this->config->item('mench_cs_fb_ids');
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
                unset($notify_fb_ids);
                $notify_fb_ids = array();
                $bootcamp_data = array();

                //Checks to see who is responsible for this user, likely to receive update messages or something...
                $admissions = $this->Db_model->remix_admissions(array(
                    'ru_u_id'	     => $nm['e_initiator_u_id'],
                    'ru_status >='	 => 0, //Instructors can send messages to students with ru_status>=0
                ));
                $active_admission = filter_active_admission($admissions); //We'd need to see which admission to load now

                if($active_admission){
                    $bootcamp_data = array(
                        'b_id' => $active_admission['b_id'],
                        'c_objective' => $active_admission['c_objective'],
                    );
                    //Fetch the admins for this admission:
                    foreach($active_admission['b__admins'] as $admin){
                        if($admin['u_fb_id']>0){
                            array_push( $notify_fb_ids , array(
                                'u_fname' => $admin['u_fname'],
                                'u_lname' => $admin['u_lname'],
                                'u_id' => $admin['u_id'],
                                'u_fb_id' => $admin['u_fb_id'],
                            ));
                        }
                    }
                }

                //We had some instructors assigned?
                if(count($notify_fb_ids)==0){
                    //Did not find any admin, or no admissions, set mench CS team:
                    $notify_fb_ids = $mench_cs_fb_ids;
                }

                //Group these messages based on their receivers:
                $md5_key = substr(md5(print_r($bootcamp_data,true)),0,8).substr(md5(print_r($notify_fb_ids,true)),0,8);
                if(!isset($notify_messages[$md5_key])){
                    $notify_messages[$md5_key] = array(
                        'notify_admins' => $notify_fb_ids,
                        'bootcamp_data' => $bootcamp_data,
                        'message_threads' => array(),
                    );
                }

                array_push($notify_messages[$md5_key]['message_threads'] , $new_messages[$key]);
            }
        }

        //Fetch student assignment submissions:
        //TODO later, we have an open issue for this
        /*
        $task_submissions = $this->Db_model->us_fetch(array(
            'us_timestamp >=' => $after_time,
            'us_status' => 1, //This is the default when submitted by the student
        ));

        $q = $this->db->query('SELECT u_fname, u_lname, u_id, COUNT(us_id) as tasks_submitted FROM v5_user_submissions us JOIN v5_users u ON (e.e_initiator_u_id = u.u_id) WHERE e_type_id=6 AND e_timestamp > \''.$after_time.'\' AND e_initiator_u_id>0 AND u_status<=1 GROUP BY u_id, u_status, u_fname, u_lname');
        $new_messages = $q->result_array();


        if(count($task_submissions)>0){
            foreach($task_submissions){

            }
        }
        */

        //Now see if we need to notify any admin:
        if(count($notify_messages)>0){
            foreach($notify_messages as $key=>$msg){

                //Prepare the message Body:
                $message = null;
                if(count($msg['bootcamp_data'])>0){
                    $message .= '🎯 '.$msg['bootcamp_data']['c_objective']."\n";
                }
                $message .= '💡 Student activity in the past '.round($seconds_ago/3600).' hours:'."\n";
                foreach($msg['message_threads'] as $thread){
                    $message .= "\n".$thread['received_messages'].' message'.show_s($thread['received_messages']).' from '.$thread['u_fname'].' '.$thread['u_lname'];
                }
                if(count($msg['bootcamp_data'])>0 && strlen($message)<580){
                    $message .= "\n\n".'https://mench.co/console/'.$msg['bootcamp_data']['b_id'].'/students';
                }

                $notify_messages[$key]['admin_message'] = $message;

                //Send message to all admins:
                foreach($msg['notify_admins'] as $admin){
                    $this->Facebook_model->batch_messages('381488558920384', $admin['u_fb_id'], array(echo_i( array(
                        'i_media_type' => 'text',
                        'i_message' => substr($message,0,620), //Make sure this is not too long!
                        'e_initiator_u_id' => 0,
                        'e_recipient_u_id' => $admin['u_id'],
                        'e_b_id' => ( count($msg['bootcamp_data'])>0 ? $msg['bootcamp_data']['b_id'] : 0),
                    ), $admin['u_fname'], true )));
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

        $this->load->model('Email_model');

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
                'e_c_id IN (3140,3127,3128,3129,3130)' => null, //The ID of the 5 email reminders https://mench.co/console/53/actionplan
            ));

            $admission_end_time = strtotime($admission['r_start_date']) - 60; //11:59PM the night before start date
            $admission_time = strtotime($admission['ru_timestamp']);

            //Send them a reminder to complete 24 hours after they start, only IF they started their application more than 6 days before the Class start:
            if(($admission_time+(6*24*3600))<$admission_end_time && ($admission_time+(24*3600))<time() && !filter($reminders_sent,'e_c_id',3140)){
                $this->Email_model->email_intent($admission['r_b_id'],3140,$admission,$admission['r_id']);
                array_push($stats,array(
                    'email' => 3140,
                    'ru_id' => $admission['ru_id'],
                    'r_id' => $admission['r_id'],
                    'u_id' => $admission['u_id'],
                    'ru_timestamp' => $admission['ru_timestamp'],
                    'r_start_date' => $admission['r_start_date'],
                    'reminders' => $reminders_sent,
                ));
            } elseif((time()+(72*3600))>$admission_end_time && !filter($reminders_sent,'e_c_id',3127)){
                $this->Email_model->email_intent($admission['r_b_id'],3127,$admission,$admission['r_id']);
                array_push($stats,array(
                    'email' => 3127,
                    'ru_id' => $admission['ru_id'],
                    'r_id' => $admission['r_id'],
                    'u_id' => $admission['u_id'],
                    'ru_timestamp' => $admission['ru_timestamp'],
                    'r_start_date' => $admission['r_start_date'],
                    'reminders' => $reminders_sent,
                ));
            } elseif((time()+(48*3600))>$admission_end_time && !filter($reminders_sent,'e_c_id',3128)){
                $this->Email_model->email_intent($admission['r_b_id'],3128,$admission,$admission['r_id']);
                array_push($stats,array(
                    'email' => 3128,
                    'ru_id' => $admission['ru_id'],
                    'r_id' => $admission['r_id'],
                    'u_id' => $admission['u_id'],
                    'ru_timestamp' => $admission['ru_timestamp'],
                    'r_start_date' => $admission['r_start_date'],
                    'reminders' => $reminders_sent,
                ));
            } elseif((time()+(24*3600))>$admission_end_time && !filter($reminders_sent,'e_c_id',3129)){
                $this->Email_model->email_intent($admission['r_b_id'],3129,$admission,$admission['r_id']);
                array_push($stats,array(
                    'email' => 3129,
                    'ru_id' => $admission['ru_id'],
                    'r_id' => $admission['r_id'],
                    'u_id' => $admission['u_id'],
                    'ru_timestamp' => $admission['ru_timestamp'],
                    'r_start_date' => $admission['r_start_date'],
                    'reminders' => $reminders_sent,
                ));
            } elseif((time()+(2*3600))>$admission_end_time && !filter($reminders_sent,'e_c_id',3130)){
                $this->Email_model->email_intent($admission['r_b_id'],3130,$admission,$admission['r_id']);
                array_push($stats,array(
                    'email' => 3130,
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

            //Fetch full Bootcamp/Class data for this:
            $bootcamps = fetch_action_plan_copy($class['r_b_id'],$class['r_id']);
            $class = $bootcamps[0]['this_class'];


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
                    $instructor_message = '📅 Reminder: your ['.$bootcamps[0]['c_objective'].'] Bootcamp group call should start in 2 hours from now. Your students will receive 2 reminders before the call (1 hour before & 10 minutes before) and will receive the following contact method to join the call:'."\n\n=============\n".$class['r_office_hour_instructions']."\n=============\n\n".'If not correct, you have 1 hour and 50 minutes from now to update this contact method before we share it with you Class:'."\n\n".'https://mench.co/console/'.$class['r_b_id'].'/classes/'.$class['r_id'];

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
                            'ru.ru_status'	    => 4, //Bootcamp students
                            'u.u_fb_id >'	    => 0, //Activated MenchBot
                        ));

                        //Send drip message to all students:
                        foreach($class_students as $u){
                            //Send this message & log sent engagement using the echo_i() function:
                            $this->Facebook_model->batch_messages('381488558920384', $u['u_fb_id'], array(echo_i(array(
                                'i_media_type' => 'text',
                                'i_message' => $student_message,
                                'e_initiator_u_id' => 0, //System
                                'e_recipient_u_id' => $u['u_id'],
                                'e_b_id' => $class['r_b_id'],
                                'e_r_id' => $class['r_id'],
                            ), $u['u_fname'], true )));
                        }
                    }

                    if($instructor_message){
                        //Fetch co-instructors:
                        $bootcamp_instructors = $this->Db_model->ba_fetch(array(
                            'ba.ba_b_id'        => $class['r_b_id'],
                            'ba.ba_status >='   => 1, //Must be an actively assigned instructor
                            'u.u_status >='     => 1, //Must be a user level 1 or higher
                            'u.u_fb_id >'	    => 0, //Activated MenchBot
                        ));

                        //Send drip message to all students:
                        foreach($bootcamp_instructors as $u){
                            //Send this message & log sent engagement using the echo_i() function:
                            $this->Facebook_model->batch_messages('381488558920384', $u['u_fb_id'], array(echo_i(array(
                                'i_media_type' => 'text',
                                'i_message' => $instructor_message,
                                'e_initiator_u_id' => 0, //System
                                'e_recipient_u_id' => $u['u_id'],
                                'e_b_id' => $class['r_b_id'],
                                'e_r_id' => $class['r_id'],
                            ), $u['u_fname'], true )));
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

    function student_reminder_complete_milestone(){
        //Cron Settings: 45 * * * *
        //Send reminders to students to complete their tasks:

        $admissions = $this->Db_model->ru_fetch(array(
            'r.r_status'	    => 2, //Running Class
            'ru.ru_status'      => 4, //Admitted Students
            'u.u_fb_id >'       => 0, //Activated MenchBot
            'ru.ru_cache__current_milestone <= r.r_cache__current_milestone' => null, //Students that are behind
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
            $bootcamps = fetch_action_plan_copy($admission['r_b_id'],$admission['r_id']);
            $class = $bootcamps[0]['this_class'];

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
                            tree_message($logic['reminder_c_id'], 0, '381488558920384', $admission['u_id'], 'REGULAR', $admission['r_b_id'], $admission['r_id']);

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