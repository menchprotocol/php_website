<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends CI_Controller {
    
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
	
	
	function fetch(){
	    echo_json($this->Db_model->c_fb_fetch('1443101719058431'));
	}
	
	
	function leaderboard(){
	    $data = array(
	        'title' => '🏆 Leaderboard',
	    );
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/my_leaderboard' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	function account(){
	    //Load apply page:
	    $data = array(
	        'title' => '😀 My Account',
	    );
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/my_account' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	
	function actionplan(){
	    //Load apply page:
	    $data = array(
	        'title' => '☑️ Action Plan',
	    );
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/my_actionplan' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	function fetch_actionplan($c_id=null){
	    
	    //Fetch bootcamps for this user:
	    $fb_psid = $_POST['psid'];
	    if(strlen($fb_psid)<10){
	        //There is an issue here!
	        die('<div class="alert alert-danger" role="alert">Invalid user ID.</div>');
	    }
	    
	    
	    $assignments = null;
	    
	    if(!$c_id){
	        
	        //Fetch all cohorts for this user:
	        $assignments = $this->Db_model->c_fb_fetch($fb_psid);
	        
	        //Does this student belong to any cohorts?
	        if(count($assignments)==0){
	            //Add notice to session:
	            $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">You\'re not enrolled in a bootcamp. Join a bootcamp below to get started.</div>');
	            //Nothing found for this user!
	            die('<script> window.location = "/bootcamps"; </script>');
	        }
	        
	        //This is the home page of assignments!
	        if(count($assignments)==1){
	            //Lets set the CID to this cohort:
	            $this->fetch_assignments($assignments[0]['c_id']);
	            //End function:
	            return false;
	        }
	    }
	    
	    //By now we have a specific $c_id to load!
	    if(!$assignments){
	        $assignments = $this->Db_model->c_newwww($c_id);
	    }
	    
	    //Would not include this in the general_helper since this is the only instance of this UI:
	    //List their cohorts so they get to choose which c_id
	    echo '<div class="list-group">';
	    foreach($assignments as $c){
	        echo '<a href="/my/assignments/'.$c['c_id'].'" class="list-group-item"><span class="pull-right"><span class="label label-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
	        echo $c['c_objective'].' ';
	        echo '<i class="fa fa-calendar" aria-hidden="true"></i> '.time_format($cohort['r_start_date'],1).' &nbsp; ';
	        echo '<i class="fa fa-usd" aria-hidden="true"></i> '.number_format($cohort['r_usd_price']);
	        
	        //Other settings:
	        if(strlen($intent['c_todo_overview'])>0){
	            echo '<i class="fa fa-binoculars title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Overview"></i>';
	        }
	        if(strlen($intent['c_todo_bible'])>0){
	            echo '<i class="fa fa-book title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Action Plan"></i>';
	            
	            if($level==2 && isset($intent['c__estimated_hours'])){
	                echo echo_time($intent['c__estimated_hours'],0);
	            } elseif($level==3 && isset($intent['c_time_estimate'])){
	                echo echo_time($intent['c_time_estimate'],0);
	            }
	        }
	        
	        
	        
	        if($level==2 && isset($intent['c__child_intents']) && count($intent['c__child_intents'])>0){
	            //This sprint has action plan:
	            $ui .= '<span class="title-sub" data-toggle="tooltip" title="Number of Sub-Goals"><i class="fa fa-check-square" aria-hidden="true"></i>'.count($intent['c__child_intents']).'</span>';
	        }
	        
	        
	        
	        echo '</a>';
	    }
	    echo '</div>';
	    
	    
	    if(0){
	        //A single bootcamp, load inner view:
	    } elseif(count($assignments)>1){
	        //List all their bootcamps so they choose which one to see:
	        
	    } 
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
	    
	    
	    //Fetch all their addmissions:
	    $admissions = $this->Db_model->remix_admissions(array(
	        'ru.ru_u_id'	=> $udata['u_id'],
	    ));
	    //Did we find at-least one?
	    if(count($admissions)<=0){
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
	        'admissions' => $admissions,
	        'hm' => ( isset($_GET['status']) && isset($_GET['message']) ? '<div class="alert alert-'.( intval($_GET['status']) ? 'success' : 'danger').'" role="alert">'.( intval($_GET['status']) ? 'Success' : 'Error').': '.$_GET['message'].'</div>' : '' ),
	    );
	    
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/my_applications' , $data);
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
	    
	    //To give the typeform webhook some time to update the DB status:
	    sleep(2);
	    
	    
	    //Fetch all their admissions:
	    $admissions = $this->Db_model->remix_admissions(array(
	        'ru.ru_r_id'	=> $_GET['r_id'],
	        'ru.ru_u_id'	=> $udata['u_id'],
	    ));
	    //Make sure we got all this data:
	    if(!(count($admissions)==1) || !isset($admissions[0]['cohort']['r_id']) || !isset($admissions[0]['bootcamp']['b_id'])){
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