<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My extends CI_Controller {
    
    //This controller is usually accessed via the /my/ URL prefix via the Messenger Bot
    
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	function leaderboard(){
	    //TODO Remove later, for compatibility only:
        header( 'Location: /my/classmates');
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

	function webview_video($i_id){

        if($i_id>0){
            $messages = $this->Db_model->i_fetch(array(
                'i_id' => $i_id,
                'i_status >=' => 1, //Not deleted
            ));
        }

        if(isset($messages[0]) && strlen($messages[0]['i_url'])>0 && in_array($messages[0]['i_media_type'],array('text','video'))){

            if($messages[0]['i_media_type']=='video'){
                //Show video
                echo '<div>'.format_e_message('/attach '.$messages[0]['i_media_type'].':'.$messages[0]['i_url']).'</div>';
            } else {
                //Show embed video:
                echo detect_embed_video($messages[0]['i_url'],$messages[0]['i_url']);
            }

        } else {

            $this->load->view('front/shared/p_header' , array(
                'title' => 'Watch Online Video',
            ));
            $this->load->view('front/error_message' , array(
                'error' => 'Invalid Message ID, likely because message has been deleted.',
            ));
            $this->load->view('front/shared/p_footer');
        }
    }


	function load_url($i_id){

	    //Loads the URL:
	    if($i_id>0){
	        $messages = $this->Db_model->i_fetch(array(
	            'i_id' => $i_id,
	            'i_status >=' => 1, //Not deleted
	        ));
	    }

        if(isset($messages[0]) && $messages[0]['i_media_type']=='text' && strlen($messages[0]['i_url'])>0){

            //Is this an embed video?
            $embed_html = detect_embed_video($messages[0]['i_url'],$messages[0]['i_message']);

            if(!$embed_html){
                //Now redirect:
                header('Location: '.$messages[0]['i_url']);
            } else {
                $this->load->view('front/shared/p_header' , array(
                    'title' => 'Watch Online Video',
                ));
                $this->load->view('front/embed_video' , array(
                    'embed_html' => $embed_html,
                ));
                $this->load->view('front/shared/p_footer');
            }

        } else {

            $this->load->view('front/shared/p_header' , array(
                'title' => 'Watch Online Video',
            ));
            $this->load->view('front/error_message' , array(
                'error' => 'Invalid Message ID, likely because message has been deleted.',
            ));
            $this->load->view('front/shared/p_footer');
        }
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

    function reset_pass(){
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/password_reset');
        $this->load->view('front/shared/p_footer');
    }

    function classmates(){
        //Load apply page:
        $data = array(
            'title' => '👥 Classmates',
        );
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/classmates_frame' , $data);
        $this->load->view('front/shared/p_footer');
    }


    function display_actionplan($u_fb_id,$b_id=0,$c_id=0){

        //Fetch bootcamps for this user:
	    if(strlen($u_fb_id)<=0){
	        //There is an issue here!
	        die('<h3>Action Plan</h3><div class="alert alert-danger" role="alert">Invalid user ID.</div>');
	    } elseif(!is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])){
	        die('<h3>Action Plan</h3><div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
	    }


        //Fetch all their admissions:
        $admission_filters = array(
            'u.u_fb_id' => $u_fb_id,
            'ru.ru_status' => 4, //Actively enrolled in
        );
	    if($b_id>0){
	        //Enhance our search and make it specific to this $b_id:
            $admission_filters['r.r_b_id'] = $b_id;
        }
        $admissions = $this->Db_model->remix_admissions($admission_filters);


        if(count($admissions)<=0){
            //Ooops, they dont have anything! This should not happen...
            $this->Db_model->e_create(array(
                'e_message' => 'Student admission not found for [u_fb_id='.$u_fb_id.']',
                'e_initiator_u_id' => 0, //System
                'e_type_id' => 8, //Platform Error
                'e_b_id' => $b_id,
            ));

            //Redirect User:
            $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">You are not enrolled in a Bootcamp.</div>');
            //Nothing found for this user!
            die('<script> window.location = "/"; </script>');
        }

	    
	    if(!$b_id || !$c_id){
            //Log Engagement for opening the Action Plan, which happens without $b_id & $c_id
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $admissions[0]['u_id'],
                'e_json' => $admissions,
                'e_type_id' => 32, //actionplan Opened
                'e_b_id' => $admissions[0]['b_id'],
                'e_r_id' => $admissions[0]['r_id'],
                'e_c_id' => $admissions[0]['c_id'],
            ));

            //Reload with specific directions:
            $this->display_actionplan($u_fb_id,$admissions[0]['b_id'],$admissions[0]['c_id']);
            return true;
	    }


        if(count($admissions)>1){
            //TODO Inform the student that they have an upcoming class:
        }


        $bootcamps = array();
        if($admissions[0]['r_status']>=2){

            //This is a running class, fetch the copy of the action plan:
            $cache_action_plans = $this->Db_model->e_fetch(array(
                'e_type_id' => 70,
                'e_r_id' => $admissions[0]['r_id'],
            ),1,true);

            if(count($cache_action_plans)>0){

                //Assign this cache to the Bootcamp:
                array_push($bootcamps,unserialize($cache_action_plans[0]['ej_e_blob']));

            } else {
                //Ooops, this should not happen for a running class!
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $admissions[0]['u_id'],
                    'e_message' => 'Missing Action Plan Copy for a Running Class',
                    'e_json' => $admissions,
                    'e_type_id' => 8, //Platform Error
                    'e_b_id' => $b_id,
                    'e_r_id' => $admissions[0]['r_id'],
                    'e_c_id' => $c_id,
                ));
            }
        }

        if(count($bootcamps)==0){

            //This class is not yet started, fetch the live Action Plan:
            $bootcamps = $this->Db_model->c_full_fetch(array(
                'b.b_id' => $b_id,
            ));

            if(count($bootcamps)==0){

                //There is an issue here!
                $this->Db_model->e_create(array(
                    'e_initiator_u_id' => $admissions[0]['u_id'],
                    'e_message' => 'Failed to load Bootcamp data in the student Action Plan',
                    'e_json' => $admissions,
                    'e_type_id' => 8, //Platform Error
                    'e_b_id' => $b_id,
                    'e_r_id' => $admissions[0]['r_id'],
                    'e_c_id' => $c_id,
                ));

                //Redirect User:
                $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">Bootcamp Not Found.</div>');

                //Nothing found for this user!
                die('<script> window.location = "/"; </script>');
            }
        }


        //Fetch intent relative to the bootcamp by doing an array search:
        $view_data = extract_level( $bootcamps[0] , $c_id );
        if($view_data){

            //Append more data:
            $view_data['admission'] = $admissions[0];
            $view_data['us_data'] = $this->Db_model->us_fetch(array(
                'us_r_id' => $admissions[0]['r_id'],
                'us_student_id' => $admissions[0]['u_id'],
            ));

        } else {

            //This should not happen either:
            $this->Db_model->e_create(array(
                'e_initiator_u_id' => $admissions[0]['u_id'],
                'e_message' => 'Failed to load Bootcamp data in the Student Action Plan',
                'e_json' => $admissions,
                'e_type_id' => 8, //Platform Error
                'e_b_id' => $b_id,
                'e_r_id' => $admissions[0]['r_id'],
                'e_c_id' => $c_id,
            ));

            //Ooops, they dont have anything!
            $this->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">Invalid ID.</div>');
            //Nothing found for this user!
            die('<script> window.location = "/my/actionplan"; </script>');

        }

        //All good, Load UI:
        $this->load->view('front/student/actionplan_ui.php' , $view_data);

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
	
	
	
	function class_application($ru_id){
	    
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