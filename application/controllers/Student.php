<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student extends CI_Controller {
    
    //This controller is usually accessed via the /my/ URL prefix via the Messenger Bot
    
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function index(){
	    //Nothing here:
	    header( 'Location: /');
	}
	
	
	function leaderboard(){
	    $data = array(
	        'title' => 'Leaderboard',
	    );
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/leaderboard' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	
	function assignments(){
	    //Load apply page:
	    $data = array(
	        'title' => 'Assignments',
	    );
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/assignments' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	
	function applications(){
	    
	    //List student applications
	    $application_status_salt = $this->config->item('application_status_salt');
	    if(!isset($_GET['u_key']) || !isset($_GET['u_id']) || intval($_GET['u_id'])<1 || !(md5($_GET['u_id'].$application_status_salt)==$_GET['u_key'])){
	        //Log this error:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Invalid Application Key. Choose your bootcamp and re-apply to receive an email with your application status url.</div>');
	        exit;
	    }
	    
	    //Is this a paypal success?
	    if(isset($_GET['status']) && intval($_GET['status'])){
	        //Give the PayPal webhook enough time to update the DB status:
	        sleep(2);
	    }
	    
	    //Search for cohort using form ID:
	    $users = $this->Db_model->u_fetch(array(
	        'u_id' => intval($_GET['u_id']),
	    ));
	    $udata = @$users[0];
	    //Fetch all 
	    $enrollments = $this->Db_model->ru_fetch(array(
	        'ru.ru_u_id'	=> $udata['u_id'],
	    ));
	    
	    //Fetch more data for each enrollment:
	    foreach($enrollments as $key=>$enrollment){
	        $cohorts = $this->Db_model->r_fetch(array(
	            'r.r_id' => $enrollment['ru_r_id'],
	        ));
	        //Assume this was fetched:
	        $enrollments[$key]['cohort'] = $cohorts[0];
	        //Fetch bootcamp:
	        $bootcamps = $this->Db_model->c_full_fetch(array(
	            'b.b_id' => $cohorts[0]['r_b_id'],
	        ));
	        //Assume this was fetched:
	        $enrollments[$key]['bootcamp'] = $bootcamps[0];
	    }
	    
	    //Did we find at-least one enrollment?
	    if(count($enrollments)<=0){
	        //Log this error:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">No Active Applications.</div>');
	        exit;
	    }
	    
	    
	    //Validate Cohort ID that it's still the latest:
	    $data = array(
	        'title' => 'My Application Status',
	        'udata' => $udata,
	        'u_id' => $_GET['u_id'],
	        'u_key' => $_GET['u_key'],
	        'enrollments' => $enrollments,
	        'hm' => ( isset($_GET['status']) && isset($_GET['message']) ? '<div class="alert alert-'.( intval($_GET['status']) ? 'success' : 'danger').'" role="alert">'.( intval($_GET['status']) ? 'Success' : 'Error').': '.$_GET['message'].'</div>' : '' ),
	    );
	    
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/applications' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	
	
	
	
	
	
	
	
	function typeform(){
	    //User is redirected here after they complete their typeform application.
	    //Note that the typeform Webhook would call the Controller Bot/typeform_webhook to update the data
	    
	    $application_status_salt = $this->config->item('application_status_salt');
	    if(!isset($_GET['u_key']) || !isset($_GET['u_id']) || intval($_GET['u_id'])<1 || !(md5($_GET['u_id'].$application_status_salt)==$_GET['u_key']) || !isset($_GET['r_id']) || intval($_GET['r_id'])<1){
	        
	        //Log this error:
	        $this->Db_model->e_create(array(
	            'e_message' => 'STUDENT/typeform() received call that was missing core data.',
	            'e_json' => json_encode($_GET),
	            'e_type_id' => 8, //Platform Error
	        ));
	        
	        //Redirect:
	        redirect_message('/bootcamps','<div class="alert alert-danger" role="alert">Missing Typeform Variables</div>');
	        exit;
	    }
	    
	    
	    //Search for cohort using form ID:
	    $users = $this->Db_model->u_fetch(array(
	        'u_id' => intval($_GET['u_id']),
	    ));
	    $udata = @$users[0];
	    //Fetch all
	    $enrollments = $this->Db_model->ru_fetch(array(
	        'ru.ru_r_id'	=> $_GET['r_id'],
	        'ru.ru_u_id'	=> $udata['u_id'],
	    ));
	    
	    //Fetch more data for the target enrollment:
	    foreach($enrollments as $key=>$enrollment){
	        $cohorts = $this->Db_model->r_fetch(array(
	            'r.r_id' => $enrollment['ru_r_id'],
	        ));
	        //Assume this was fetched:
	        $enrollments[$key]['cohort'] = $cohorts[0];
	        //Fetch bootcamp:
	        $bootcamps = $this->Db_model->c_full_fetch(array(
	            'b.b_id' => $cohorts[0]['r_b_id'],
	        ));
	        //Assume this was fetched:
	        $enrollments[$key]['bootcamp'] = $bootcamps[0];
	    }
	    
	    //To give the typeform webhook enough time to update the DB status:
	    sleep(2);
	    
	    //Make sure we got all this data:
	    if(!(count($enrollments)==1) || !isset($enrollments[0]['cohort']['r_id']) || !isset($enrollments[0]['bootcamp']['b_id'])){
	        //Log this error:
	        $this->Db_model->e_create(array(
	            'e_creator_id' => $_GET['u_id'],
	            'e_message' => 'STUDENT/typeform() failed to fetch bootcamp data.',
	            'e_json' => json_encode($_GET),
	            'e_type_id' => 8, //Platform Error
	        ));
	        
	        //Redirect:
	        redirect_message('/my/applications?u_key='.$_GET['u_key'].'&u_id='.$_GET['u_id'],'<div class="alert alert-danger" role="alert"> Failed to fetch bootcamp data.</div>');
	        exit;
	    }
	    
	    //We're good now, lets redirect to application status page and MAYBE send them to paypal asap:
	    //The "pay_r_id" variable makes the next page redirect to paypal automatically:
	    header( 'Location: /my/applications?pay_r_id='.$_GET['r_id'].'&u_key='.$_GET['u_key'].'&u_id='.$_GET['u_id'] );
	}
}