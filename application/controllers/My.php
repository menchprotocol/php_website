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
	    //echo_json($this->Db_model->c_fb_fetch('1443101719058431'));
	    
	    echo_json($this->Db_model->remix_admissions(array(
	        'u.u_fb_id' => '1443101719058431',
	        'ru.ru_status' => 4, //Actively enrolled in
	    )));
	    /*
	    echo_json($this->Db_model->c_full_fetch(array(
	        'b.b_id' => 1,
	    )));
	    */
	}
	
	
	function load_url($message_id){
	    
	    //Loads the URL:
	    if($message_id>0){
	        $messages = $this->Db_model->i_fetch(array(
	            'i_id' => $message_id,
	            'i_status >=' => 0, //Not deleted
	        ));
	        
	        if(isset($messages[0]) && $messages[0]['i_media_type']=='text' && strlen($messages[0]['i_url'])>0){
	            header('Location: '.$messages[0]['i_url']);
	            return true;
	        }
	    }
	    
	    //Still here?
	    redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Message ID.</div>');    
	}
	
	function leaderboard(){
	    $data = array(
	        'title' => '🏆Leaderboard',
	    );
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/my_leaderboard' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	
	function display_account(){
	    
	    echo '<p class="p_footer"><img src="'.$admissions[0]['u_image_url'].'" class="mini-image" /> '.$admissions[0]['u_fname'].' '.$admissions[0]['u_lname'].'</p>';
	}
	function account(){
	    //Load apply page:
	    $data = array(
	        'title' => '⚙My Account',
	    );
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/my_account' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	
	function actionplan($b_id=null,$c_id=null){
	    //Load apply page:
	    $data = array(
	        'title' => '🚩 Action Plan',
	        'b_id' => $b_id,
	        'c_id' => $c_id,
	    );
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/actionplan_frame' , $data);
	    $this->load->view('front/shared/p_footer');
	}
	
	function display_actionplan($u_fb_id,$b_id=null,$c_id=null){
	    
	    //Fetch bootcamps for this user:
	    if(strlen($u_fb_id)<=0){
	        //There is an issue here!
	        die('<h3>Milestones</h3><div class="alert alert-danger" role="alert">Invalid user ID.</div>');
	    } elseif(!is_dev() && (!isset($_GET['sr']) || !parse_signed_request($_GET['sr']))){
	        die('<h3>Milestones</h3><div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
	    }
	    
	    if(!($b_id && $c_id)){
	        
	        //Fetch all their admissions:
	        $admissions = $this->Db_model->remix_admissions(array(
	            'u.u_fb_id' => $u_fb_id,
	            'ru.ru_status' => 4, //Actively enrolled in
	        ));
	        
	        //How many?
	        if(count($admissions)<=0){
	            //Ooops, they dont have anything!
	            $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">You\'re not enrolled in a bootcamp. Join a bootcamp below to get started.</div>');
	            //Nothing found for this user!
	            die('<script> window.location = "/"; </script>');
	        }
	        
	        //How Many?
	        if(count($admissions)==1){
	            
	            //Log Engagement
	            $this->Db_model->e_create(array(
	                'e_initiator_u_id' => $admissions[0]['u_id'],
	                'e_json' => json_encode($admissions),
	                'e_type_id' => 32, //actionplan Opened
	                'e_b_id' => $admissions[0]['b_id'],
	                'e_r_id' => $admissions[0]['r_id'],
	                'e_c_id' => $admissions[0]['c_id'],
	            ));
	            
	            //Reload with specific directions:
	            $this->display_actionplan($u_fb_id,$admissions[0]['b_id'],$admissions[0]['c_id']);
	            
	        } else {
	            
	            //List bootcamps:
	            echo '<ol class="breadcrumb"><li>My Bootcamps</li></ol>';
	            echo '<div id="list-outbound" class="list-group">';
	            foreach($admissions as $admission){
	                echo echo_c($admission,$admission,0);
	            }
	            echo '</div>';
	            
	        }
	        
	    } else {
	        
	        //Fetch user & all their admissions:
	        $admissions = $this->Db_model->remix_admissions(array(
	            'u.u_fb_id' => $u_fb_id,
	            'u_status >=' => 0,
	            'ru.ru_status' => 4, //Actively enrolled in
	        ));
	        
	        //We have directions on what to load:
	        $bootcamps = $this->Db_model->c_full_fetch(array(
	            'b.b_id' => $b_id,
	        ));
	        
	        
	        
	        if(count($bootcamps)>0 && count($admissions)>0){
	            
	            //Check if this admission matches this bootcamp
	            $admission = null;
	            foreach($admissions as $a_test_case){
	                foreach($bootcamps as $b_test_case){
	                    if($b_test_case['b_id'] == $a_test_case['b_id']){
	                        $admission = $a_test_case;
	                        break;
	                    }
	                }
	                if($admission){
	                    break;
	                }
	            }
	            
	            if($admission){
	                //Fetch intent relative to the bootcamp by doing an array search:
	                $view_data = extract_level( $bootcamps[0] , $c_id );
	                //Append user to data:
	                $view_data['admission'] = $admission;
	                $view_data['us_data'] = $this->Db_model->us_fetch(array(
	                    'us_r_id' => $admission['r_id'],
	                    'us_student_id' => $admission['u_id'],
	                ));
	            }
	        }
	        
	        if(!$admission || !$view_data){
	            //Ooops, they dont have anything!
	            $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">Invalid ID.</div>');
	            //Nothing found for this user!
	            die('<script> window.location = "/my/actionplan"; </script>');
	        }	        
	        
	        //Load UI:
	        $this->load->view('front/student/actionplan_ui.php' , $view_data);
	    }
	}
	
	
	
	
	
	function applications(){
	    
	    //List student applications
	    $application_status_salt = $this->config->item('application_status_salt');
	    if(!isset($_GET['u_key']) || !isset($_GET['u_id']) || intval($_GET['u_id'])<1 || !(md5($_GET['u_id'].$application_status_salt)==$_GET['u_key'])){
	        //Log this error:
	        redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Application Key. Choose your bootcamp and re-apply to receive an email with your application status url.</div>');
	        exit;
	    }
	    
	    //Is this a paypal success?
	    if(isset($_GET['status']) && intval($_GET['status'])){
	        //Give the PayPal webhook enough time to update the DB status:
	        sleep(2);
	    }
	    
	    //Search for class using form ID:
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
	        redirect_message('/','<div class="alert alert-danger" role="alert">You have not applied to join any bootcamp yet.</div>');
	        exit;
	    }
	    
	    
	    //Validate Class ID that it's still the latest:
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
	
	
	
	function apply($ru_id){
	    
	    //List student applications
	    $application_status_salt = $this->config->item('application_status_salt');
	    if(intval($ru_id)<1 || !isset($_GET['u_key']) || !isset($_GET['u_id']) || intval($_GET['u_id'])<1 || !(md5($_GET['u_id'].$application_status_salt)==$_GET['u_key'])){
	        //Log this error:
	        redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Application Key. Choose your bootcamp and re-apply to receive an email with your application status url.</div>');
	        exit;
	    }
	    
	    //Fetch all their addmissions:
	    $admissions = $this->Db_model->remix_admissions(array(
	        'ru.ru_id'	   => $ru_id,
	        'ru.ru_u_id'   => intval($_GET['u_id']),
	    ));
	    //Did we find at-least one?
	    if(count($admissions)<=0){
	        //Log this error:
	        redirect_message('/my/applications?u_key='.$_GET['u_key'].'&u_id='.$_GET['u_id'],'<div class="alert alert-danger" role="alert">No Active Applications.</div>');
	        exit;
	    }
	    
	    //Assemble the data:
	    $data = array(
	        'title' => 'Application to Join '.$admissions[0]['c_objective'].' - Starting '.time_format($admissions[0]['r_start_date'],4),
	        'ru_id' => $ru_id,
	        'u_id' => $_GET['u_id'],
	        'u_key' => $_GET['u_key'],
	        'admission' => $admissions[0],
	        'r_fb_pixel_id' => $admissions[0]['r_fb_pixel_id'], //Will insert pixel code in header
	    );
	    
	    //Load apply page:
	    $this->load->view('front/shared/p_header' , $data);
	    $this->load->view('front/student/class_apply' , $data);
	    $this->load->view('front/shared/p_footer');
	    
	}
	
	
	
}