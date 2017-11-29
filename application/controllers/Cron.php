<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
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