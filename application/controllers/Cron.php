<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}


    function jason($u_id){
        echo_json(tree_message(890, 0, '381488558920384', $u_id, 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, 67, 103));
    }

    function s(){
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


    function profile(){

        $admitted = $this->Db_model->ru_fetch(array(
            'ru.ru_r_id'	    => 103,
            'ru.ru_status >='	=> 4, //Anyone who is admitted
            'u.u_fb_id >'	    => 0, //Activated MenchBot
        ));
        $updated = 0;
        $profiles = array();
        foreach($admitted as $u){

            //Fetch profile:
            $profile = $this->Facebook_model->fetch_profile('381488558920384',$u['u_fb_id']);

            if(strlen($profile['first_name'])>0 && strlen($profile['last_name'])>0 && ( !($profile['first_name']==$u['u_fname']) || !($profile['last_name']==$u['u_lname']) )){
                //Update local:
                $this->Db_model->u_update( $u['u_id'] , array(
                    'u_fname' => $profile['first_name'],
                    'u_lname' => $profile['last_name'],
                ));
                $updated++;
                array_push($profiles,$profile);
            }
        }

        echo_json(array(
            'updated' => $updated,
            'profiles' => $profiles,
        ));
    }

    //Update FB:



	function class_kickstart(){

	    //Searches for any class that might be starting and kick starts its messages:
        $classes = $this->Db_model->r_fetch(array(
            'r_status' => 1,
            'r_start_date' => date("Y-m-d"),
        ));



        foreach($classes as $class){
            //See if they have students and they are more than the minimum:
            if($class['r_id']==103){

                //Fetch Bootcamp Data:
                $bootcamps = $this->Db_model->c_full_fetch(array(
                    'b.b_id' => $class['r_b_id'],
                ));

                //$class =
                //if($class['r__confirmed_admissions']>0 && $class['r__confirmed_admissions']>=$class['r_min_students']){}

                if(count($bootcamps)==1 && $bootcamps[0]['b_status']>=2){

                    //Found a published Bootcamp!
                    //Find first due milestone:

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