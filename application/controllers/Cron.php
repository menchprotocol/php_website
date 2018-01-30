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

            //Fetch full Bootcamp/Class data for this:
            $bootcamps = $this->Db_model->c_full_fetch(array(
                'b.b_id' => $class['r_b_id'],
            ));

            //Now override $class with the more complete version:
            $class = filter($bootcamps[0]['c__classes'],'r_id',$class['r_id']);

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
                    //TODO automate refunds through Paypal API later on...
                    if($class['r_usd_price']>0){
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_message' => 'Need to manually refund $['.$class['r_usd_price'].'] to ['.$admission['u_fname'].' '.$admission['u_lname'].'] as their pending application was auto-rejected upon class kick-start because the instructor did not approve them on time.',
                            'e_type_id' => 58, //Class Manual Refund
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));
                    }
                }
            }



            /*
             * Now make sure class meets are requirements to get started:
             *
             * 1. Minimum required studnets have already been admitted
             * 2. Action Plan has at-least 1 Published Milestone
             *
             */

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

            if($first_milestone_c_id==0 || count($accepted_admissions)==0 || count($accepted_admissions)<$class['r_min_students']){

                //Cancel this class as it does not have enough students admitted:
                $stats[$class['r_id']]['new_status'] = -2; //Class was cancelled
                $this->Db_model->r_update( $class['r_id'] , array('r_status' => $stats[$class['r_id']]['new_status']));

                $cancellation_reason = ( $first_milestone_c_id==0 ? 'because there was no published Milestones' : 'because the class had ['.count($accepted_admissions).'] admitted students, which did not meet the minimum required students of ['.$class['r_min_students'].']' );

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
                                'e_message' => 'Need to manually refund $['.$class['r_usd_price'].'] to ['.$admission['u_fname'].' '.$admission['u_lname'].'] as their pending application was auto-rejected upon class kick-start '.$cancellation_reason.'.',
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
                    'e_json' => json_encode(array(
                        'minimum' => $class['r_min_students'],
                        'admitted' => $accepted_admissions,
                    )),
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
                    'r_cache__action_plan' => json_encode($bootcamps[0]), //A Cache copy of the Action Plan & Entire Bootcamp at this point in time
                ));

                //Dispatch appropriate Message to Students
                foreach($accepted_admissions as $admission){
                    if($admission['u_fb_id']>0){

                        //Update counter:
                        $stats[$class['r_id']]['students']['accepted']['menchbot_active']++;

                        //They already have MenchBot activated, send message:
                        $message_result = tree_message($first_milestone_c_id, 0, '381488558920384', $admission['u_id'], 'REGULAR', $class['r_b_id'], $class['r_id']);

                    } else {

                        //Update counter:
                        $stats[$class['r_id']]['students']['accepted']['menchbot_inactive']++;

                        //No MenchBot activated yet! Remind them again:
                        $this->Email_model->email_intent($class['r_b_id'],3120,$admission);

                    }
                }

                //Log Class Cancellation engagement & Notify Admin/Instructor:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => 0, //System
                    'e_message' => 'Class started successfully with ['.count($accepted_admissions).'] admitted students.',
                    'e_json' => json_encode(array(
                        'admitted' => $accepted_admissions,
                    )),
                    'e_type_id' => 60, //Class kick-started
                    'e_b_id' => $class['r_b_id'],
                    'e_r_id' => $class['r_id'],
                ));
            }
        }

        //Echo Summary:
        echo_json($stats);
    }

    //TODO Enable for students that are left behind (Currently only works for students on the most recent milestone)
    function drip(){

        //Cron Settings: 0,30 * * * *
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
            $bootcamps = $this->Db_model->c_full_fetch(array(
                'b.b_id' => $class['r_b_id'],
            ));

            //Now override $class with the more complete version:
            $class = filter($bootcamps[0]['c__classes'],'r_id',$class['r_id']);

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
                                    'e_json' => json_encode(array(
                                        'i' => $i,
                                        'drip_stats' => $drip_stats['d_bootcamp'],
                                    )),
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
                                $fetch_filters['ru.ru_current_milestone >='] = $class['r__current_milestone'];
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
                                    'e_json' => json_encode(array(
                                        'i' => $i,
                                        'drip_stats' => $drip_stats['d_milestone'],
                                    )),
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

    function next_milestone(){

        //Cron Settings: 0,30 * * * *
        //Moves the class from one milestone to another.
        //Only applicable for the 2nd milesone or higher
        //The first milestone is triggered with the class_kickstart() cron function above

        $running_classes = $this->Db_model->r_fetch(array(
            'r_status' => 2, //Only running classes
        ));

        //Cron stats file so we know what happened in each run...
        $stats = array();

        foreach($running_classes as $class){

            //Fetch full Bootcamp/Class data for this:
            $bootcamps = $this->Db_model->c_full_fetch(array(
                'b.b_id' => $class['r_b_id'],
            ));

            //Now override $class with the more complete version:
            $class = filter($bootcamps[0]['c__classes'],'r_id',$class['r_id']);

            //Where is the class at now?
            if($class['r__current_milestone']<=1 || $class['r__current_milestone']<=$class['r_cache__current_milestone']){

                //Should never be 0 (meaning not started) as r_status=2 (means it must have started)
                //Its possible for this to be -1, meaning its over (And the instructor has still not submitted class completion report)
                //ALso if its 1, it means its still at the first milestone, nothing we need to do here...
                $stats[$class['r_id']] = 'Skipped. Current Milestone is ['.$class['r__current_milestone'].'] and DB cache is ['.$class['r_cache__current_milestone'].']';

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
                        'e_json' => json_encode($bootcamps[0]),
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
                        'u.u_fb_id >'	    => 0, //MenchBot Activated
                    ));

                    if(count($accepted_admissions)==0){

                        //Ooops, this is an error that should not happen, log engagemeng:
                        $stats[$class['r_id']] = 'ERROR: Class has 0 admitted students';
                        $this->Db_model->e_create(array(
                            'e_message' => $stats[$class['r_id']],
                            'e_json' => json_encode($bootcamps[0]),
                            'e_type_id' => 8, //Platform Error
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));

                    } else {

                        //Do a count for stat reporting:
                        $ontime = 0;
                        $behind = array();

                        //Loop through students:
                        foreach($accepted_admissions as $admission){
                            //See where the student is at, did they finish the previous milestone?
                            if($admission['ru_current_milestone']>=$class['r__current_milestone']){

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
                                    'i_message' => 'Hi {first_name} 👋​ We just moved to our next 🚩 ​Milestone! '.$ontime.' of your classmate'.show_s($ontime).' progressed to '.$bootcamps[0]['b_sprint_unit'].' '.$class['r__current_milestone'].'. I would love to know what is preventing you from finishing your remaining '.$bootcamps[0]['b_sprint_unit'].' '.$admission['ru_current_milestone'].' tasks and would be happy to assist you in marching forward 🙌 Let\'s do it!​',
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
                        $stats[$class['r_id']] = 'Moved from '.$bootcamps[0]['b_sprint_unit'].' ['.$class['r_cache__current_milestone'].'] to '.$bootcamps[0]['b_sprint_unit'].' ['.$class['r__current_milestone'].'] with ['.$ontime.'] ON-TIME and ['.count($behind).'] BEHIND students';

                        //Log Engagement:
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_message' => $stats[$class['r_id']],
                            'e_json' => json_encode(array(
                                'bootcamp' => $bootcamps[0],
                                'accepted_admissions' => $accepted_admissions,
                            )),
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
        ));

        $counter = 0;
        foreach($e_pending as $ep){

            //Prepare variables:
            $json_data = objectToArray(json_decode($ep['e_json']));

            //Loop through entries:
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
                    'ru_status <='	 => 4,
                    'ru_status >='	 => 0,
                ));
                if(count($admissions)==1){
                    $bootcamp_data = array(
                        'b_id' => $admissions[0]['b_id'],
                        'c_objective' => $admissions[0]['c_objective'],
                    );
                    //Fetch the admins for this admission:
                    foreach($admissions[0]['b__admins'] as $admin){
                        if($admin['u_fb_id']>0){
                            array_push($notify_fb_ids,array(
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
                $message = '💡 Student activity in the past '.round($seconds_ago/3600).' hours:'."\n";
                foreach($msg['message_threads'] as $thread){
                    $message .= "\n".$thread['received_messages'].' message'.show_s($thread['received_messages']).' from '.$thread['u_fname'].' '.$thread['u_lname'];
                }
                if(count($msg['bootcamp_data'])>0 && strlen($message)<580){
                    $message .= "\n\n".'Communicate with your ['.$msg['bootcamp_data']['c_objective'].'] students here:'."\n\n".'https://mench.co/console/'.$msg['bootcamp_data']['b_id'].'/students';
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
        //Cron Settings: 15 * * * *
    }

    function student_reminder_group_call_starting(){
        //Cron Settings: 20,50 * * * *
    }

    function student_reminder_complete_milestone(){
        //Cron Settings: 45 * * * *
        //Send reminders to students to complete their tasks:

    }

}