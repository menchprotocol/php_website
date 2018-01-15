<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}


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

                    if($first_milestone_c_id){
                        //We found this milestone!

                        //Change the status:
                        $this->Db_model->r_update( $class['r_id'] , array('r_status' => 2));

                        //Fetch all admitted & activated students:
                        $admitted = $this->Db_model->ru_fetch(array(
                            'ru.ru_r_id'	    => $class['r_id'],
                            'ru.ru_status >='	=> 4, //Anyone who is admitted
                            'u.u_fb_id >'	    => 0, //Activated MenchBot
                        ));

                        foreach($admitted as $u){
                            //Inform Students on First Milestone:
                            if($u['u_id']==1){
                                tree_message($first_milestone_c_id, 0, '381488558920384', $u['u_id'], 'REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, $class['r_b_id'], $class['r_id']);

                            }
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