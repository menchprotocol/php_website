<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}

    function lazaro(){
        echo_json(tree_message(896, 0, '381488558920384', 422, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 68, 104));
        echo_json(tree_message(896, 0, '381488558920384', 416, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 68, 104));
    }

    function profile(){
        echo_json($this->Facebook_model->fetch_profile('381488558920384','1670125439677259'));
    }

    function drip(){

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
                'active_students' => array(), //To be populated if drip detected
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
                            $drip_stats['active_students'] = $this->Db_model->ru_fetch(array(
                                'ru.ru_r_id'	    => $class['r_id'],
                                'ru.ru_status'	    => 4, //Bootcamp students
                                'u.u_status >'	    => 0, //Active user
                                'u.u_fb_id >'	    => 0, //Activated MenchBot
                            ));
                            $drip_stats['d_bootcamp']['drips_sent'] = count($drip_stats['active_students']);

                            //Did we find any?
                            if($drip_stats['d_bootcamp']['drips_sent']>0){

                                //Send drip message to all students:
                                foreach($drip_stats['active_students'] as $u){
                                    //Send this message & log sent engagement using the echo_i() function:
                                    $this->Facebook_model->batch_messages('381488558920384', $u['u_fb_id'], array(echo_i(array_merge( $i , array(
                                        'e_initiator_u_id' => 0, //System
                                        'e_recipient_u_id' => $u['u_id'],
                                        'e_b_id' => $class['r_b_id'],
                                        'e_r_id' => $class['r_id'],
                                    )), $u['u_fname'], true )));
                                }

                                //Log engagement for the entire Drip batch:
                                $this->Db_model->e_create(array(
                                    'e_initiator_u_id' => 0, //System
                                    'e_recipient_u_id' => 0, //No particular person, likely a group of students
                                    'e_message' => 'Bootcamp-level drip message sent to '.count($drip_stats['active_students']).' students',
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

                            //Fetch active students only if not already fetched at the Bootcamp-level:
                            if(count($drip_stats['active_students'])==0){
                                $drip_stats['active_students'] = $this->Db_model->ru_fetch(array(
                                    'ru.ru_r_id'	    => $class['r_id'],
                                    'ru.ru_status'	    => 4, //Bootcamp students
                                    'u.u_status >'	    => 0, //Active user
                                    'u.u_fb_id >'	    => 0, //Activated MenchBot
                                ));
                            }

                            $drip_stats['d_milestone']['drips_sent'] = count($drip_stats['active_students']);

                            //Did we find any?
                            if($drip_stats['d_milestone']['drips_sent']>0){

                                //Send drip message to all students:
                                foreach($drip_stats['active_students'] as $u){
                                    //Send this message & log sent engagement using the echo_i() function:
                                    $this->Facebook_model->batch_messages('381488558920384', $u['u_fb_id'], array(echo_i(array_merge( $i , array(
                                        'e_initiator_u_id' => 0, //System
                                        'e_recipient_u_id' => $u['u_id'],
                                        'e_b_id' => $class['r_b_id'],
                                        'e_r_id' => $class['r_id'],
                                    )), $u['u_fname'], true )));
                                }

                                //Log engagement for the entire Drip batch:
                                $this->Db_model->e_create(array(
                                    'e_initiator_u_id' => 0, //System
                                    'e_recipient_u_id' => 0, //No particular person, likely a group of students
                                    'e_message' => 'Milestone-level drip message sent to '.count($drip_stats['active_students']).' students',
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


    function unread_new_messages(){

        //Runs every hour and informs instructors/admins of new messages received recently
        //Define settings:
	    $seconds_ago = 7200; //Defines how much to go back, should be equal to cron job frequency
        $cs_team = array(1,2); //ID of Mench admins who receive unassigned message notifications

        //Create query:
	    $after_time = date("Y-m-d H:i:s",(time()-$seconds_ago));
        $q = $this->db->query('SELECT u_status, u_fname, e_initiator_u_id, COUNT(e_id) as received_messages FROM v5_engagements e JOIN v5_users u ON (e.e_initiator_u_id = u.u_id) WHERE e_type_id=6 AND e_timestamp > \''.$after_time.'\' AND e_initiator_u_id>0 AND u_status<=1 GROUP BY e_initiator_u_id, u_status, u_fname');
        $new_messages = $q->result_array();
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
                $responsible_fb_ids = $this->Db_model->fetch_responsible_users();
            }

            $new_messages[$key]['all'] = count($messages);
        }

        echo_json($new_messages);
    }


    function next_milestone(){

        $completed = array(258,271,314,317,336,354,358,369,370,371,372,374,389,393,404);
        $incomplete = array(1);//324


        $accepted_admissions = $this->Db_model->ru_fetch(array(
            'ru.ru_r_id'	    => 103,
            'ru.ru_status'	    => 4,
            'u.u_fb_id >'	    => 0, //Activated
        ));

        foreach($completed as $u_id){
            //tree_message(946, 0, '381488558920384', $u_id, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 67, 103);
        }

        $counter = 0;
        foreach($accepted_admissions as $u){

            if(in_array($u['u_id'],$completed)){
                continue;
            }

            $counter++;
            echo $counter.') '.$u['u_fname'].'<br />';
            continue;

            //Ooopsy, this milestone is not started yet! Let the user know that they are up to date:
            unset($instant_messages);
            $instant_messages = array();
            array_push( $instant_messages , echo_i( array(
                'i_media_type' => 'text',
                'i_message' => 'Hi {first_name}, we just moved on to our next Milestone which would become available to you once you finish all your week 1 tasks. Let me know what\'s preventing you from completing your week 1 tasks, and I\'d be happy to assist you in catching-up to the rest of the class who now moved on to week 2.',
                'e_initiator_u_id' => 0,
                'e_recipient_u_id' => $u['u_id'],
                'e_r_id' => 103,
                'e_b_id' => 67,
            ), $u['u_fname'], true ));

            //Send message:
            $this->Facebook_model->batch_messages('381488558920384', $u['u_fb_id'], $instant_messages);
        }



        exit;

	    //Starts the next milestone and notifies students who are ready to move on:
        $classes = $this->Db_model->r_fetch(array(
            'r_id' => 103, //For start...
            'r_status' => 2,
        ));

        foreach($classes as $key=>$class){

            //Fetch full Bootcamp/Class data for this:
            $bootcamps = $this->Db_model->c_full_fetch(array(
                'b.b_id' => $class['r_b_id'],
            ));

            //Now override $class with the more complete version:
            $class = filter($bootcamps[0]['c__classes'],'r_id',$class['r_id']);


            //Fetch all active students for this class:
            $stats = array(
                'total' => 0,
                'pending' => 0,
                'completed' => 0,
            );
            $accepted_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 4,
            ));

            foreach($accepted_admissions as $u){
                $stats['total']++;

                //Count their previous submission:


                if(1){
                    $stats['pending']++;
                } else {
                    $stats['completed']++;

                    //Inform Students on First Milestone:
                    tree_message(946, 0, '381488558920384', $u['u_id'], 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, $class['r_b_id'], $class['r_id']);
                }
            }

            //Now lets send them a message based on their status:


            //echo_json($accepted_admissions);
        }

        //print_r($classes);
    }

	function class_kickstart(){

	    //This function is solely responsible to get the class started and dispatch its very first milestone messages IF it does start
	    //Searches for any class that might be starting and kick starts its messages:
        $classes = $this->Db_model->r_fetch(array(
            'r_status' => 1,
            'r_start_date <=' => date("Y-m-d"),
        ));

        foreach($classes as $key=>$class){

            //Make sure the start time has already passed:
            if( time() < ( strtotime($class['r_start_date']) + ($class['r_start_time_mins']*60) )){
                //Not started yet!
                continue;
            }

            //Include email model as we might need some communications:
            $this->load->model('Email_model');

            //Fetch full Bootcamp/Class data for this:
            $bootcamps = $this->Db_model->c_full_fetch(array(
                'b.b_id' => $class['r_b_id'],
            ));

            //Now override $class with the more complete version:
            $class = filter($bootcamps[0]['c__classes'],'r_id',$class['r_id']);

            //Uncomment for For QA:
            $classes[$key] = $class; $classes[$key]['bootcamp'] = $bootcamps[0]; continue;

            //Auto withdraw all incomplete admission requests:
            $incomplete_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 0,
            ));
            if(count($incomplete_admissions)>0){
                foreach($incomplete_admissions as $admission){

                    //Auto withdraw:
                    $this->Db_model->ru_update( $admission['ru_id'] , array(
                        'ru_status' => -2,
                    ));

                    //Inform the student of auto withdrawal:
                    $this->Email_model->email_intent($admission['b_id'],3016,$admission);
                }
            }


            //Auto reject all applications that were not yet accepted:
            $pending_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 2,
            ));
            if(count($pending_admissions)>0){
                foreach($pending_admissions as $admission){

                    //Auto reject:
                    $this->Db_model->ru_update( $admission['ru_id'] , array(
                        'ru_status' => -1,
                    ));

                    //Inform the student of rejection:
                    $this->Email_model->email_intent($admission['b_id'],2799,$admission);

                    //Was this a paid class? Let admin know to manually process refunds
                    //TODO automate refunds through Paypal API later on...
                    if($class['r_usd_price']>0){
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_recipient_u_id' => $admission['u_id'],
                            'e_message' => 'Need to manually refund $['.$class['r_usd_price'].'] to ['.$admission['u_fname'].' '.$admission['u_lname'].'] as their pending application was auto-rejected upon class kick-start',
                            'e_type_id' => 58, //Class Manual Refund
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));
                    }
                }
            }


            //What should happen to the class it self?
            //Lets see how many admitted students we have?
            $accepted_admissions = $this->Db_model->ru_fetch(array(
                'ru.ru_r_id'	    => $class['r_id'],
                'ru.ru_status'	    => 4,
            ));
            if(count($accepted_admissions)<$class['r_min_students']){

                //Cancel this class as it does not have enough students admitted:
                $this->Db_model->r_update( $class['r_id'] , array(
                    'r_status' => -2, //Class was cancelled
                ));

                //Change the status of all students that had been accepted, if any, and notify admin for refunds:
                //Note that this process is kind-of similar to Api_chat_v1/update_admission_status() but not fully as it also includes ru_status=0
                if(count($accepted_admissions)>0){
                    foreach($accepted_admissions as $admission){
                        //Reject their admission:

                    }

                    //Was this a paid class? Let admin know to manually process refunds
                    //TODO automate refunds through Paypal API later on...
                    if($class['r_usd_price']>0){
                        $this->Db_model->e_create(array(
                            'e_initiator_u_id' => 0, //System
                            'e_message' => 'Need to manually refund the '.$class['r__current_admissions'].' students who paid for this class via Paypal',
                            'e_type_id' => 58, //Class Manual Refund
                            'e_b_id' => $class['r_b_id'],
                            'e_r_id' => $class['r_id'],
                        ));
                    }
                }

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $udata['u_id'], //The user
                    'e_message' => readable_updates($classes[0],$r_update,'r_'),
                    'e_json' => json_encode(array(
                        'input' => $_POST,
                        'before' => $classes[0],
                        'after' => $r_update,
                    )),
                    'e_type_id' => 56, //Class Cancelled
                    'e_b_id' => $classes[0]['r_b_id'], //Share with bootcamp team
                    'e_r_id' => intval($_POST['r_id']),
                ));

            } else {

                //The class is ready to get started!
                //Change the status to running:
                $this->Db_model->r_update( $class['r_id'] , array(
                    'r_status' => 2, //Class Running
                ));

                //Find first due milestone to dispatch its messages to all students:
                $first_milestone_c_id = 0;
                foreach($bootcamps[0]['c__child_intents'] as $milestone){
                    if($milestone['c_status']>=1){
                        $first_milestone_c_id = $milestone['c_id'];
                        break;
                    }
                }

                if($first_milestone_c_id || 1){
                    //We found this milestone!

                    //Change the status:
                    //$this->Db_model->r_update( $class['r_id'] , array('r_status' => 2));

                    //Fetch all admitted & activated students:
                    $admitted = $this->Db_model->ru_fetch(array(
                        'ru.ru_r_id'	    => $class['r_id'],
                        'ru.ru_status >='	=> 4, //Anyone who is admitted
                        'u.u_fb_id >'	    => 0, //Activated MenchBot
                    ));

                    foreach($admitted as $u){
                        //Inform Students on First Milestone:
                        tree_message(890, 0, '381488558920384', $u['u_id'], 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, $class['r_b_id'], $class['r_id']);
                    }
                }

            }

            echo_json(array(
                'admitted' => $admitted,
                'class' => $class,
            ));

        }
    }
	
	function bot_save_files(){
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
	
	
	
	
	
}