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




    /* ******************************
     * Signup
     ****************************** */

    function checkout_start($b_url_key){
        //The start of the funnel for email, first name & last name

        //Fetch data:
        $udata = $this->session->userdata('user');
        $bs = $this->Db_model->remix_bs(array(
            'LOWER(b.b_url_key)' => strtolower($b_url_key),
        ));

        //Do we need to auto load a user?
        if(isset($_GET['u_email'])){
            $us = $this->Db_model->u_fetch(array(
                'u_email' => $_GET['u_email'],
            ));
        }

        //Validate Bootcamp:
        if(!isset($bs[0])){
            //Invalid key, redirect back:
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Bootcamp URL.</div>');
        } elseif($bs[0]['b_status']<2){
            //Here we don't even let coaches move forward to apply!
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp is Archived.</div>');
        } elseif($bs[0]['b_fp_id']<=0){
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp not connected to a Facebook Page yet.</div>');
        } elseif(!$bs[0]['b_offers_diy'] && !$bs[0]['b_offers_coaching']){
            redirect_message('/','<div class="alert alert-danger" role="alert">Bootcamp not yet open for enrollment.</div>');
        }

        $data = array(
            'title' => $bs[0]['c_outcome'],
            'u' => ( isset($us[0]) ? $us[0] : ( isset($udata) && count($udata)>0 ? $udata : array() ) ),
            'b' => $bs[0],
            'b_fb_pixel_id' => $bs[0]['b_fb_pixel_id'], //Will insert pixel code in header
        );

        //Load apply page:
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/checkout_start' , $data);
        $this->load->view('front/shared/p_footer');
    }

    function checkout_complete($b_url_key){

        //Validate Bootcamp:
        $bs = $this->Db_model->b_fetch(array(
            'b_url_key' => $b_url_key,
        ));
        if(count($bs)<1){
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Bootcamp URL.</div>');
        }

        //Validate email:
        if(isset($_GET['u_email']) && filter_var($_GET['u_email'], FILTER_VALIDATE_EMAIL)){

            $enrollments = $this->Db_model->remix_enrollments(array(
                'u_email' =>strtolower($_GET['u_email']),
                'ru_b_id' => $bs[0]['b_id'],
            ));

            if(count($enrollments)<1){
                return redirect_message('/'.$b_url_key,'<div class="alert alert-danger" role="alert">Subscription not found.</div>');
            } elseif($bs[0]['b_requires_assessment'] && $enrollments[0]['ru_assessment_result']<1){
                //Send them back to assessment as they have not yet passed that:
                return redirect_message('/'.$b_url_key.'/assessment?u_email='.$_GET['u_email'],'<div class="alert alert-danger" role="alert">You are required to complete the assessment before joining this Bootcamp.</div>');
            }

        } else {
            redirect_message('/'.$b_url_key,'<div class="alert alert-danger" role="alert">Missing email.</div>');
        }

        //Assemble the data:
        $application_status_salt = $this->config->item('application_status_salt');
        $data = array(
            'title' => 'Enroll to '.$enrollments[0]['c_outcome'],
            'ru_id' => $enrollments[0]['ru_id'],
            'u_id' => $enrollments[0]['u_id'],
            'u_key' => md5($enrollments[0]['u_id'].$application_status_salt),
            'enrollment' => $enrollments[0],
            'b_fb_pixel_id' => $bs[0]['b_fb_pixel_id'], //Will insert pixel code in header
        );

        //Load apply page:
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/checkout_complete' , $data);
        $this->load->view('front/shared/p_footer');

    }

    function applications(){

        //List student applications
        $application_status_salt = $this->config->item('application_status_salt');
        if(!isset($_GET['u_key']) || !isset($_GET['u_id']) || intval($_GET['u_id'])<1 || !(md5($_GET['u_id'].$application_status_salt)==$_GET['u_key'])){
            //Log this error:
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid URL. Choose your Bootcamp and re-apply to receive an email with your application status url.</div>');
        }


        //Search for class using form ID:
        $users = $this->Db_model->u_fetch(array(
            'u_id' => intval($_GET['u_id']),
        ));

        //Fetch all their addmissions:
        $enrollments = $this->Db_model->ru_fetch(array(
            'ru_outbound_u_id'	=> @$users[0]['u_id'],
        ),array(
            'ru.ru_timestamp' => 'DESC',
        ));

        if(count($users)==1 && count($enrollments)>0){
            $udata = $users[0];
        } else {
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid URL. Choose your Bootcamp and re-apply to receive an email with your application status url.</div>');
        }

        $bs = $this->Db_model->b_fetch(array(
            'b_id'	=> ( $enrollments[0]['ru_b_id'] ),
        ));


        //Is this a paypal success?
        if(isset($_GET['status']) && intval($_GET['status'])==1){
            //Give the PayPal webhook enough time to update the DB status:
            sleep(2);

            //Capture Facebook Conversion:
            //TODO This would capture again upon refresh, fix later...
            $purchase_value = doubleval($_GET['purchase_value']);

        } else {
            //Set defaults:
            $purchase_value = 0;
        }

        //Validate Class ID that it's still the latest:
        $data = array(
            'title' => 'My Bootcamps',
            'udata' => $udata,
            'u_id' => $_GET['u_id'],
            'u_key' => $_GET['u_key'],
            'b_enrollment_redirect_url' => null,
            'purchase_value' => $purchase_value, //Capture via Facebook Pixel
            'enrollments' => $enrollments,
            'hm' => ( isset($_GET['status']) && isset($_GET['message']) ? '<div class="alert alert-'.( intval($_GET['status']) ? 'success' : 'danger').'" role="alert">'.( intval($_GET['status']) ? 'Success' : 'Error').': '.$_GET['message'].'</div>' : '' ),
        );

        //Load apply page:
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/my_applications' , $data);
        $this->load->view('front/shared/p_footer');

    }



    /* ******************************
     * Messenger Persistent Menu
     ****************************** */

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
    function display_actionplan($ru_fp_psid,$b_id=0,$c_id=0){

        $uenrollment = array();
        if(!$ru_fp_psid){
            $uenrollment = $this->session->userdata('uenrollment');
        }

        //Fetch Bootcamps for this user:
        if(!$ru_fp_psid && count($uenrollment)<1){
            //There is an issue here!
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif(count($uenrollment)<1 && !is_dev() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])){
            die('<div class="alert alert-danger" role="alert">Unable to authenticate your origin.</div>');
        }

        //Set enrollment filters:
        $ru_filter = array(
            'ru.ru_status >=' => 4, //Enrolled
            'r.r_status >=' => 1, //Open for Subscription or Higher
        );

        //Define user identifier based on origin (Desktop login vs Messenger Webview):
        if(count($uenrollment)>0 && $uenrollment['u_id']>0){
            $ru_filter['u.u_id'] = $uenrollment['u_id'];
        } else {
            $ru_filter['(ru.ru_fp_psid = '.$ru_fp_psid.' OR u.u_cache__fp_psid = '.$ru_fp_psid.')'] = null;
        }

        //Fetch all their enrollments:
        if($b_id>0){
            //Enhance our search and make it specific to this $b_id:
            $ru_filter['ru.ru_b_id'] = $b_id;
        }

        $enrollments = $this->Db_model->remix_enrollments($ru_filter);

        if(count($enrollments)==1){

            //Only have a single option:
            $focus_enrollment = $enrollments[0];

        } elseif(count($enrollments)>1){

            //We'd need to see which enrollment to load now as the Student has not specified:
            $focus_enrollment = detect_active_enrollment($enrollments);

        } else {

            //No enrollments found:
            die('<div class="alert alert-danger" role="alert">You have not joined any Bootcamps yet</div>');

        }


        if(!$b_id || !$c_id){

            //Log Engagement for opening the Action Plan, which happens without $b_id & $c_id
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $focus_enrollment['u_id'],
                'e_json' => $enrollments,
                'e_inbound_c_id' => 32, //actionplan Opened
                'e_b_id' => $focus_enrollment['b_id'],
                'e_r_id' => $focus_enrollment['r_id'],
                'e_outbound_c_id' => $focus_enrollment['c_id'],
            ));

            //Reload with specific directions:
            $this->display_actionplan($ru_fp_psid,$focus_enrollment['b_id'],$focus_enrollment['c_id']);

            //Reload this function, this time with specific instructions on what to load:
            return true;
        }


        //Fetch full Bootcamp/Class data for this:
        $bs = fetch_action_plan_copy($b_id,$focus_enrollment['r_id']);
        $class = $bs[0]['this_class'];


        //Fetch intent relative to the Bootcamp by doing an array search:
        $view_data = extract_level( $bs[0] , $c_id );

        if($view_data){

            //Append more data:
            $view_data['class'] = $class;
            $view_data['enrollments'] = $enrollments;
            $view_data['enrollment'] = $focus_enrollment;
            $view_data['us_data'] = $this->Db_model->e_fetch(array(
                'e_inbound_c_id' => 33, //Completion Report
                'e_inbound_u_id' => $focus_enrollment['u_id'], //by this Student
                'e_r_id' => $focus_enrollment['r_id'], //For this Class
                'e_replaced_e_id' => 0, //Data has not been replaced
            ), 1000, array(), 'e_outbound_c_id');

        } else {

            //Reload with specific directions:
            $this->display_actionplan($ru_fp_psid);

            //Reload this function, this time with specific instructions on what to load:
            return true;

            //Ooops, they dont have anything!
            //die('<div class="alert alert-info" role="alert">Click on the Action Plan button on Messenger</div>');

        }

        //All good, Load UI:
        $this->load->view('front/student/actionplan_ui.php' , $view_data);

    }
    function all_enrollments(){

        //Validate their origin:
        $application_status_salt = $this->config->item('application_status_salt');
        if(!isset($_POST['current_r_id']) || !isset($_POST['u_key']) || !isset($_POST['u_id']) || intval($_POST['u_id'])<1 || !(md5($_POST['u_id'].$application_status_salt)==$_POST['u_key'])){
            //Log this error:
            die('<div class="alert alert-danger" role="alert">Invalid ID</div>');
        }

        //Fetch all their enrollments:
        $enrollments = $this->Db_model->remix_enrollments(array(
            'ru.ru_outbound_u_id' => $_POST['u_id'],
            'ru.ru_status >=' => 4, //Enrolled
            'r.r_status >=' => 1, //Open for Subscription or Higher
        ));


        if(count($enrollments)<=1){

            //No other enrollments found:
            die('<div class="alert alert-info" role="alert"><i class="fas fa-exclamation-triangle"></i> Error: You must be part of at-least 2 Bootcamps to be able to switch between them.<div style="margin-top: 15px;"><a href="/">Browse Bootcamps &raquo;</a></div></div>');

        } else {

            //Student is in multiple Bootcamps, give them option to switch:
            echo '<div class="list-group maxout">';

            foreach($enrollments as $other_enrollment){

                $is_current = ($_POST['current_r_id']==$other_enrollment['r_id']);

                if($is_current){

                    //This is the one that is loaded:
                    echo '<li class="list-group-item grey">';
                    //echo '<span class="pull-right"><span class="label label-default grey" style="color:#3C4858;">CURRENTLY VIEWING</span></span>';

                } else {

                    echo '<a href="/my/actionplan/'.$other_enrollment['b_id'].'/'.$other_enrollment['b_outbound_c_id'].'" class="list-group-item">';
                    echo '<span class="pull-right"><span class="badge badge-primary" style="margin-top: -7px;"><i class="fas fa-chevron-right"></i></span></span>';

                }

                echo '<i class="fas fa-cube"></i> <b>'.$other_enrollment['c_outcome'].'</b>';
                echo ' <span style="display:inline-block;"><i class="fas fa-calendar"></i> '.echo_time($other_enrollment['r_start_date'],2).'</span>';

                if(time()>strtotime($other_enrollment['r_start_date']) && time()<class_ends($other_enrollment, $other_enrollment)){
                    echo ' <span class="badge badge-primary grey" style="padding: 2px 9px;">RUNNING</span>';
                }

                echo ( $is_current ? '</li>' : '</a>' );
            }

            echo '</div>';

        }
    }

    function classmates(){
        //Load apply page:
        $data = array(
            'title' => '👥 Classroom',
        );
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/classmates_frame' , $data);
        $this->load->view('front/shared/p_footer');
    }
    function display_classmates(){

        $b_id = 0;
        $r_id = 0;
        $is_coach = 0;

        //Function called form /MY/classmates (student Messenger)
        if(isset($_POST['psid'])){

            $ru_filter = array(
                'ru.ru_status >=' => 4, //Enrolled
                'r.r_status >=' => 1, //Open for Subscription or Higher
            );

            if($_POST['psid']==0){

                //Data is supposed to be in the session:
                $uenrollment = $this->session->userdata('uenrollment');

                if($uenrollment){
                    $focus_enrollment = $uenrollment;
                } else {
                    die('<div class="alert alert-info" role="alert" style="line-height:110%;"><i class="fas fa-exclamation-triangle"></i> To access your Classroom you need to <a href="https://mench.com/login?url='.urlencode($_SERVER['REQUEST_URI']).'" style="font-weight:bold;">Login</a>. Use [Forgot Password] if you never logged in before.</div>');
                }

            } else {

                $ru_filter['(ru.ru_fp_psid = '.$_POST['psid'].' OR u.u_cache__fp_psid = '.$_POST['psid'].')'] = null;

                //Fetch all their enrollments:
                $enrollments = $this->Db_model->remix_enrollments($ru_filter);
                $focus_enrollment = detect_active_enrollment($enrollments); //We'd need to see which enrollment to load

            }


            if(!$focus_enrollment){

                //Ooops, they dont have anything!
                die('<div class="alert alert-danger" role="alert">You have not joined any Bootcamps yet</div>');

            } else {

                //Show Classroom:
                $b_id = $focus_enrollment['b_id'];
                $r_id = $focus_enrollment['r_id'];

                //Log Engagement for opening the classmates:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $focus_enrollment['u_id'],
                    'e_inbound_c_id' => 54, //classmates Opened
                    'e_b_id' => $b_id,
                    'e_r_id' => $r_id,
                ));
            }

        } elseif(isset($_POST['r_id'])){

            //Validate the Class and Coach status:
            $classes = $this->Db_model->r_fetch(array(
                'r.r_id' => $_POST['r_id'],
            ));

            if(count($classes)<1){
                //Ooops, something wrong:
                die('<span style="color:#FF0000;">Error: Missing Core Data</span>');
            }

            $udata = auth(array(1308,1280), 0, $classes[0]['r_b_id']);
            if(!$udata){
                die('<span style="color:#FF0000;">Error: Session Expired.</span>');
            }

            //Show Leaderboard for Coach:
            $b_id = $classes[0]['r_b_id'];
            $r_id = $classes[0]['r_id'];
            $is_coach = 1;

        }


        if(!$b_id || !$r_id){
            //Ooops, something wrong:
            die('<span style="color:#FF0000;">Error: Missing Core Data</span>');
        }

        //Fetch full Bootcamp/Class data for this:
        $bs = fetch_action_plan_copy($b_id,$r_id);
        $class = $bs[0]['this_class'];


        //Was it all good? Should be!
        if($class['r__total_tasks']==0){
            die('<span style="color:#FF0000;">Error: No Bootcamps Yet</span>');
        } elseif(!$class){
            die('<span style="color:#FF0000;">Error: Class Not Found</span>');
        }

        //Set some settings:
        $loadboard_students = $this->Db_model->ru_fetch(array(
            'ru_r_id' => $class['r_id'],
            'ru_status >=' => 4,
        ));
        $countries_all = $this->config->item('countries_all');
        $udata = $this->session->userdata('user');
        $show_top = 0.2; //The rest are not ranked based on points on the student side, coaches will still see entire ranking
        $show_ranking_top = ceil(count($loadboard_students) * $show_top );


        if($is_coach){

            //Show Class Status
            $class_ends = class_ends($bs[0], $class);
            $class_running = (time()>=strtotime($class['r_start_date']) && time()<$class_ends);

            echo '<h3 style="margin:0;" class="maxout">';

                //Title (Dates)
                echo echo_time($class['r_start_date'],2).' - '.echo_time($class_ends,2);

                //Status:
                echo ' ('.( $class_running ? 'Running' : ( time()<strtotime($class['r_start_date']) ? 'Upcoming' : 'Completed' ) ).')';

                //Help Bubble:
                echo ' <span id="hb_2826" class="help_button" intent-id="2826"></span>';


            echo '</h3>';

            echo '<div class="help_body maxout" id="content_2826"></div>';

        }



        echo '<table class="table table-condensed maxout ap_toggle" style="background-color:#E0E0E0; font-size:18px; '.( $is_coach ? 'margin-bottom:12px;' : 'max-width:420px; margin:0 auto;' ).'">';

        //Now its header:
        echo '<tr class="bg-col-h">';
            if($is_coach){
                echo '<td style="width:38px;">#</td>';
                echo '<td style="width:43px;">Rank</td>';
            } else {
                echo '<td style="width:50px;">&nbsp;</td>';
            }

            //Fixed columns for both Coaches/Students:
            echo '<td style="text-align:left; padding-left:30px;">Student</td>';
            echo '<td style="text-align:left; width:'.($is_coach?120:90).'px;" colspan="'.($is_coach?3:2).'">Progress</td>';

        echo '</tr>';



        //Now list all students in order:
        if(count($loadboard_students)>0){

            //List students:
            $rank = 1; //Keeps track of student rankings, which is equal if points are equal
            $counter = 0; //Keeps track of student counts
            $top_ranking_shown = false;

            foreach($loadboard_students as $key=>$enrollment){

                if($show_ranking_top<=$counter && !$top_ranking_shown && $enrollment['ru_cache__current_task']<=$class['r__total_tasks']){
                    echo '<tr class="bg-col-h">';
                    echo '<td colspan="6">';
                    if($show_ranking_top==$counter){
                        echo '<span data-toggle="tooltip" title="While only the top '.($show_top*100).'% are ranked, any student who completes all Steps by the end of the class will win the completion awards.">Ranking for top '.($show_top*100).'% only</span>';
                    } else {
                        echo '<span>Above students have successfully <i class="fas fa-trophy"></i> COMPLETED</span>';
                    }
                    echo '</td>';
                    echo '</tr>';
                    $top_ranking_shown = true;
                }

                $counter++;
                if($key>0 && $enrollment['ru_cache__completion_rate']<$loadboard_students[($key-1)]['ru_cache__completion_rate']){
                    $rank++;
                }

                //Should we show this ranking?
                $ranking_visible = ($is_coach || (isset($_POST['psid']) && isset($focus_enrollment) && $focus_enrollment['u_id']==$enrollment['u_id']) || $counter<=$show_ranking_top || $enrollment['ru_cache__current_task']>$class['r__total_tasks']);


                echo '<tr class="bg-col-'.fmod($counter,2).'">';
                if($is_coach){
                    echo '<td valign="top" style="text-align:center; vertical-align:top;">'.$counter.'</td>';
                    echo '<td valign="top" style="vertical-align:top; text-align:center; vertical-align:top;">'.( $ranking_visible ? echo_rank($rank) : '' ).'</td>';
                } else {
                    echo '<td valign="top" style="text-align:center; vertical-align:top;">'.( $ranking_visible ? echo_rank($rank) : '' ).'</td>';
                }




                echo '<td colspan="'.( $enrollment['ru_cache__completion_rate']<1 && !$ranking_visible ? 2 : 1 ).'" valign="top" style="text-align:left; vertical-align:top;">';
                $student_name = echo_cover($enrollment,'micro-image', true).' '.$enrollment['u_full_name'];


                if($is_coach){

                    echo '<a href="javascript:view_el('.$enrollment['u_id'].','.$bs[0]['c_id'].')" class="plain">';
                    echo '<i class="pointer fas fa-caret-right" id="pointer_'.$enrollment['u_id'].'_'.$bs[0]['c_id'].'"></i> ';
                    echo $student_name;
                    echo '</a>';

                } else {

                    //Show basic list for students:
                    echo $student_name;

                }
                echo '</td>';


                //Progress, Task & Steps:
                if($enrollment['ru_cache__completion_rate']>=1){

                    //They have completed it all, show them as winners!
                    echo '<td valign="top" colspan="'.($is_coach?'2':'1').'" style="text-align:left; vertical-align:top;">';
                    echo '<i class="fas fa-trophy"></i><span style="font-size: 0.8em; padding-left:2px;"></span>';
                    echo '</td>';

                } else {

                    //Progress:
                    if($ranking_visible){
                        echo '<td valign="top" style="text-align:left; vertical-align:top;">';
                        echo '<span>'.round( $enrollment['ru_cache__completion_rate']*100 ).'%</span>';
                        echo '</td>';
                    }

                    if($is_coach){
                        //Task:
                        echo '<td valign="top" style="text-align:left; vertical-align:top;">';
                        if($ranking_visible){
                            echo $enrollment['ru_cache__current_task'];
                        }
                        echo '</td>';
                    }
                }



                echo '<td valign="top" style="text-align:left; vertical-align:top;">'.( isset($countries_all[strtoupper($enrollment['u_country_code'])]) ? '<img data-toggle="tooltip" data-placement="left" title="'.$countries_all[strtoupper($enrollment['u_country_code'])].'" src="/img/flags/'.strtolower($enrollment['u_country_code']).'.png" class="flag" style="margin-top:-3px;" />' : '' ).'</td>';

                echo '</tr>';


                if($is_coach){

                    echo '<tr id="c_el_'.$enrollment['u_id'].'_'.$bs[0]['c_id'].'" class="hidden bg-col-'.fmod($counter,2).'">';
                    echo '<td>&nbsp;</td>';
                    echo '<td>&nbsp;</td>';
                    echo '<td colspan="4" class="us_c_list">';

                        //Fetch student submissions so far:
                        $us_data = $this->Db_model->e_fetch(array(
                            'e_inbound_c_id' => 33, //Completion Report
                            'e_inbound_u_id' => $enrollment['u_id'], //by this Student
                            'e_r_id' => $class['r_id'], //For this Class
                            'e_replaced_e_id' => 0, //Data has not been replaced
                            'e_status !=' => -3, //Should not be rejected
                        ), 1000, array(), 'e_outbound_c_id');

                        //Go through all the Tasks and see which ones are submitted:
                        foreach($bs[0]['c__child_intents'] as $intent) {

                            if($intent['c_status']>0){

                                $intent_submitted = (isset($us_data[$intent['c_id']]));

                                //Title:
                                echo '<div class="us_c_title">';
                                echo '<a href="javascript:view_el('.$enrollment['u_id'].','.$intent['c_id'].')" class="plain">';
                                echo '<i class="pointer fas fa-caret-right" id="pointer_'.$enrollment['u_id'].'_'.$intent['c_id'].'"></i> ';
                                echo echo_status('e_status',( $intent_submitted ? $us_data[$intent['c_id']]['e_status'] : -4 /* Not completed yet */ ),1,'right').'#'.$intent['cr_outbound_rank'].' '.$intent['c_outcome'];
                                echo '</a>';
                                echo '</div>';


                                //Submission Details:
                                echo '<div id="c_el_'.$enrollment['u_id'].'_'.$intent['c_id'].'" class="homework hidden">';
                                if($intent_submitted){
                                    echo '<p>'.( strlen($us_data[$intent['c_id']]['e_text_value'])>0 ? echo_link($us_data[$intent['c_id']]['e_text_value']) : '<i class="fas fa-comment-alt-times"></i> No completion notes by Student' ).'</p>';
                                } else {
                                    echo '<p><i class="fas fa-exclamation-triangle"></i> Nothing submitted Yet</p>';
                                }
                                
                                //TODO Show Steps here in the future

                                echo '</div>';

                            }
                        }
                        
                    echo '</td>';
                    echo '</tr>';
                }

            }

        } else {

            //No students enrolled yet:
            echo '<tr style="font-weight:bold; ">';
            echo '<td colspan="7" style="font-size:1.2em; padding:15px 0; text-align:center;"><i class="fas fa-exclamation-triangle"></i>  No Students Enrolled Yet</td>';
            echo '</tr>';

        }

        echo '</table>';



        //TODO Later add broadcasting and Action Plan UI
        if($is_coach && 0){

            $message_max = $this->config->item('message_max');

            //Add Broadcasting:
            echo '<div class="title" style="margin-top:25px;"><h4><i class="fas fa-comment-dots"></i> Broadcast Message <span id="hb_4997" class="help_button" intent-id="4997"></span> <span id="b_transformations_status" class="list_status">&nbsp;</span></h4></div>';
            echo '<div class="help_body maxout" id="content_4997"></div>';
            echo '<div class="form-group label-floating is-empty">
            <textarea class="form-control text-edit border msg msgin" style="min-height:80px; max-width:420px; padding:3px;" onkeyup="changeBroadcastCount()" id="r_broadcast"></textarea>
            <div style="margin:0 0 0 0; font-size:0.8em;"><span id="BroadcastChar">0</span>/'.$message_max.'</div>
        </div>
        <table width="100%"><tr><td class="save-td"><a href="javascript:send_();" class="btn btn-primary">Send</a></td><td><span class="save_r_results"></span></td></tr></table>';


        }
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
    function display_account(){
        //TODO later...
    }



    /* ******************************
     * User Functions
     ****************************** */

    function update_assessment($b_url_key,$ru_assessment_result){

        //Validate Bootcamp:
        $bs = $this->Db_model->b_fetch(array(
            'b_url_key' => $b_url_key,
        ));
        if(count($bs)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp Key',
            ));
        }


        //Validate & Prepare new status
        $ru_assessment_result = intval($ru_assessment_result);
        if(!in_array($ru_assessment_result,array(0,1))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Status can be either 0 or 1',
            ));
        }
        $new_status = ( $ru_assessment_result ? 'PASSED' : 'FAILED' );


        //Check user email:
        if(isset($_GET['u_email']) && filter_var($_GET['u_email'], FILTER_VALIDATE_EMAIL)){

            $enrollments = $this->Db_model->remix_enrollments(array(
                'u_email' =>strtolower($_GET['u_email']),
                'ru_b_id' => $bs[0]['b_id'],
            ));

            if(count($enrollments)<1){
                //Missing core variables:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Student enrollment not found for this Bootcamp',
                ));
            } elseif($ru_assessment_result==$enrollments[0]['ru_assessment_result']){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Assessment status is already set to ['.$new_status.'].',
                ));
            }

        } else {
            //Missing core variables:
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        }

        //All good, Update their enrollment:
        $this->Db_model->ru_update( $enrollments[0]['ru_id'] , array(
                'ru_assessment_result' => $ru_assessment_result,
        ));

        //Log engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $enrollments[0]['u_id'],
            'e_inbound_c_id' => 7084, //Assessment Status Update API Call
            'e_text_value' => 'Student '.$new_status.' Assessment',
            'e_b_id' => $enrollments[0]['b_id'],
            'e_r_id' => $enrollments[0]['r_id'],
        ));

        //Return result:
        return echo_json(array(
            'status' => 1,
            'message' => 'Update student assessment status to ['.$new_status.']',
        ));
    }

    function checkout_submit(){

        //When students complete the checkout process:
        $application_status_salt = $this->config->item('application_status_salt');
        $_POST['ru_support_package'] = intval($_POST['ru_support_package']);

        if(!isset($_POST['r_id']) || !isset($_POST['ru_network_level']) || !isset($_POST['ru_inbound_u_id']) || intval($_POST['r_id'])<1 || !in_array($_POST['ru_support_package'],array(1,2)) || !isset($_POST['ru_id']) || intval($_POST['ru_id'])<1 || !isset($_POST['u_key']) || !isset($_POST['u_id']) || intval($_POST['u_id'])<1 || !(md5($_POST['u_id'].$application_status_salt)==$_POST['u_key'])){

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => ( isset($_POST['u_id']) ? intval($_POST['u_id']) : 0 ),
                'e_text_value' => 'checkout_submit() Missing Core Inputs.',
                'e_json' => $_POST,
                'e_inbound_c_id' => 8, //Platform Error
            ));

            //Display Error:
            die('<span style="color:#FF0000;">Error: Missing Core Inputs. Report Logged for Admin to review.</span>');
        }

        //Fetch their enrollment:
        $enrollments = $this->Db_model->remix_enrollments(array(
            'ru.ru_id'	=> intval($_POST['ru_id']),
        ));

        //Fetch selected Class:
        $chosen_classes = $this->Db_model->r_fetch(array(
            'r.r_id' => $_POST['r_id'],
            'r.r_status >=' => ( $_POST['ru_support_package']>=2 ? 1 : 0 ),
        ), null, 'DESC', 1, array('b'));


        //Make sure we got all this data:
        if(!(count($enrollments)==1) || !isset($enrollments[0]['b_id'])){

            //Log this error:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $_POST['u_id'],
                'e_text_value' => 'checkout_submit() failed to fetch enrollment data.',
                'e_json' => $_POST,
                'e_inbound_c_id' => 8, //Platform Error
            ));

            //Error:
            die('<span style="color:#FF0000;">Error: Failed to fetch enrollment data. Report Logged for Admin to review.</span>');

        }


        //Log Engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $_POST['u_id'],
            'e_json' => $_POST,
            'e_inbound_c_id' => 26, //Checkout Submitted (Pending Payment)
            'e_b_id' => $enrollments[0]['b_id'],
            'e_r_id' => $_POST['r_id'],
        ));

        //Default next URL is the student application dashboard (This may be over-written with logic below)
        $next_url = '/my/applications?pay_ru_id='.$enrollments[0]['ru_id'].'&u_key='.$_POST['u_key'].'&u_id='.$_POST['u_id'];


        //Save checkout preferences (Still not finalized):
        $this->Db_model->ru_update( intval($_POST['ru_id']) , array(
            'ru_fp_id'          => $enrollments[0]['b_fp_id'],
            'ru_fp_psid'        => ( $enrollments[0]['b_fp_id']==$enrollments[0]['u_cache__fp_id'] ? $enrollments[0]['u_cache__fp_psid'] : 0 ),
            'ru_status'         => 0, //Pending as it might be updated with ru_finalize()

            'ru_network_level'  => $_POST['ru_network_level'],
            'ru_inbound_u_id'   => $_POST['ru_inbound_u_id'],
            'ru_support_package'=> $_POST['ru_support_package'],

            //Class details:
            'ru_r_id'           => $_POST['r_id'],
            'ru_start_time'     => $chosen_classes[0]['r_start_date'].' 00:00:00',
            'ru_end_time'       => date("Y-m-d",(strtotime($chosen_classes[0]['r_start_date'])+($enrollments[0]['b_weeks_count']*7*24*3600)-(12*3600))).' 23:59:59',
            'ru_outcome_time'   => date("Y-m-d",(strtotime($chosen_classes[0]['r_start_date'])+(($enrollments[0]['b_weeks_count']+$enrollments[0]['b_guarantee_weeks'])*7*24*3600)-(12*3600))).' 23:59:59',
        ));


        //Is this a free DIY Bootcamp? If so, we can fast forward the payment step...
        if($_POST['ru_support_package']==1){

            //Update the enrollment as its now paid:
            $this->Db_model->ru_finalize($_POST['ru_id']);

            //Do they have a custom URL?
            if(strlen($enrollments[0]['b_post_enrollment_url_diy'])>0){
                $next_url = $enrollments[0]['b_post_enrollment_url_diy'];
            }

        } elseif($_POST['ru_support_package']==2){

            //They requested to Book their Free Coaching Call:
            $us_coach = $this->Db_model->u_fetch(array(
                'u_id' => $_POST['ru_inbound_u_id'],
            ), array('u_booking_x_id'));
            if(count($us_coach)>0){
                //Booking URL for this coach:
                $next_url = $us_coach[0]['x_url'];

                //Log engagement for this:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $enrollments[0]['ru_outbound_u_id'],
                    'e_outbound_u_id' => $_POST['ru_inbound_u_id'],
                    'e_inbound_c_id' => 7098, //Redirected to Book Free Coaching Call
                    'e_text_value' => 'To book a call with ['.$us_coach[0]['u_full_name'].'] as their coach',
                    'e_b_id' => $enrollments[0]['b_id'],
                    'e_r_id' => $_POST['r_id'],
                    'e_x_id' => $us_coach[0]['x_id'],
                ));
            }
        }

        //We're good now, lets redirect to application status page and MAYBE send them to paypal asap:
        //Show message & redirect:
        echo '<script> setTimeout(function() { window.location = "'.$next_url.'" }, 1000); </script>';
        echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span><div>'.( $_POST['ru_support_package']==2 ? 'Redirecting to Book Call With '.one_two_explode('',' ', $us_coach[0]['u_full_name']) : 'Successfully Enrolled 🙌​').'</div>';

    }

    function withdraw_enrollment(){
        //Validate inputs:
        $application_status_salt = $this->config->item('application_status_salt');
        if(!isset($_POST['u_key']) || !isset($_POST['u_id']) || intval($_POST['u_id'])<1 || !isset($_POST['ru_id']) || intval($_POST['ru_id'])<1 || !(md5($_POST['u_id'].$application_status_salt)==$_POST['u_key'])){
            //Log this error:
            echo_json(array(
                'status' => 0,
                'message' => 'Error: Invalid Inputs',
            ));
        } else {

            //Attempt to withdraw user:
            $enrollments = $this->Db_model->ru_fetch(array(
                'ru.ru_status <='  => 4, //Initiated or higher as long as Bootcamp is running!
                'ru.ru_outbound_u_id'	=> $_POST['u_id'],
                'ru.ru_id'	    => $_POST['ru_id'],
            ));

            if(count($enrollments)==1){

                //All good, withdraw:
                $this->Db_model->ru_update( $_POST['ru_id'] , array(
                    'ru_status' => -2,
                ));

                //Log Engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $enrollments[0]['u_id'], //System
                    'e_inbound_c_id' => 66, //Application Withdraw
                    'e_b_id' => $enrollments[0]['ru_b_id'],
                    'e_r_id' => $enrollments[0]['r_id'],
                ));

                //Inform User:
                echo_json(array(
                    'status' => 1,
                    'message' => echo_status('ru',-2,0,'top'),
                ));

            } else {

                //Error, Inform User:
                echo_json(array(
                    'status' => 0,
                    'message' => 'Error: Withdraw no longer possible as your application status has changed.',
                ));

            }
        }
    }


    //TODO needs to be optimized for account subscriptions
    function subscribe($b_url_key){

        //Validate Bootcamp ID:
        $bs = $this->Db_model->remix_bs(array(
            'b.b_url_key' => $b_url_key,
        ));
        if(count($bs)<1) {
            //Not valid:
            redirect_message('/', '<div class="alert alert-danger" role="alert">Invalid Bootcamp key.</div>');
        }


        //Validate User Email:
        $us = array();
        if(isset($_GET['u_email'])){
            //Fetch this user:
            $us = $this->Db_model->u_fetch(array(
                'u_email' => $_GET['u_email'],
            ));
            //Did we find them?
            if(count($us)<1){
                redirect_message('/'.$b_url_key,'<div class="alert alert-danger" role="alert">User not found.</div>');
            }
        } else {
            redirect_message('/'.$b_url_key,'<div class="alert alert-danger" role="alert">Missing student email.</div>');
        }


        //Do they have an enrollment?
        $enrollments = $this->Db_model->remix_enrollments(array(
            'ru_outbound_u_id' => $us[0]['u_id'],
            'ru_b_id' => $bs[0]['b_id'],
        ));
        if(count($enrollments)<1){
            redirect_message('/'.$b_url_key,'<div class="alert alert-danger" role="alert">Cannot accept your payment as we could not find an application for '.$us[0]['u_full_name'].'</div>');
        }


        //Load views:
        $this->load->view('front/shared/p_header' , array(
            'title' => 'Pay Coaching Tuition to '.$bs[0]['c_outcome'],
        ));
        $this->load->view('front/student/pay_tuition' , array(
            'u' => $us[0],
            'b' => $bs[0],
            'enrollment' => $enrollments[0],
        ));
        $this->load->view('front/shared/p_footer');
    }

    function checkout_assessment($b_url_key='JuniorFullStack' /* Junior FSD Job Placement */){

        //Validate Bootcamp ID:
        $bs = $this->Db_model->remix_bs(array(
            'b.b_url_key' => $b_url_key,
        ));
        if(count($bs)<1){
            //Not valid:
            return redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Bootcamp key.</div>');
        } elseif(!$bs[0]['b_requires_assessment']){
            //Does not need assessment, take them to enrollments:
            return redirect_message('/'.$bs[0]['b_url_key'].'/checkout?u_email='.(@$_GET['u_email']),'<div class="alert alert-info" role="alert">Bootcamp does not require an instant assessment.</div>');
        }


        //Validate User Email:
        $us = array();
        if(isset($_GET['u_email'])){
            //Fetch this user:
            $us = $this->Db_model->u_fetch(array(
                'u_email' => $_GET['u_email'],
            ));

            //Did we find them?
            if(count($us)<1){
                return redirect_message('/','<div class="alert alert-danger" role="alert">User not found.</div>');
            } else {
                //Do they have an enrollment? If not, make one:
                $enrollments = $this->Db_model->remix_enrollments(array(
                    'ru_outbound_u_id' => $us[0]['u_id'],
                ));
                if(count($enrollments)<1){
                    //Admit student:
                    $new_enrollment = $this->Db_model->enroll_student($us[0]['u_id'],$bs[0]);

                    //Fetch full-data:
                    $enrollments = $this->Db_model->remix_enrollments(array(
                        'ru_id' => $new_enrollment['ru_id'],
                    ));
                } elseif($enrollments[0]['ru_assessment_result']==1){
                    //Student has passed this assessment already! Redirect to next step:
                    return redirect_message('/'.$bs[0]['b_url_key'].'/checkout?u_email='.$_GET['u_email'],'<div class="alert alert-info" role="alert">You have passed this assessment. Continue your enrollment below.</div>');
                }
            }
        } else {
            return redirect_message('/','<div class="alert alert-danger" role="alert">Missing student email.</div>');
        }


        //Load views:
        $this->load->view('front/shared/p_header' , array(
            'title' => 'Instant Assessment To '.$bs[0]['c_outcome'],
        ));
        $this->load->view('front/student/technical_assessment' , array(
            'u' => $us[0],
            'b' => $bs[0],
            'enrollment' => $enrollments[0],
            'attempts' => $this->Db_model->e_fetch(array(
                'e_inbound_c_id' => 6997, //Instant Assessment Attempts
                'e_inbound_u_id' => $us[0]['u_id'], //This student
            ), 1000),
        ));
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

    function review($ru_id,$ru_key){
        //Loadup the review system for the student's Class
        if(!($ru_key==substr(md5($ru_id.'r3vi3wS@lt'),0,6))){
            //There is an issue with the key, show error to user:
            redirect_message('/','<div class="alert alert-danger" role="alert">Invalid Review URL.</div>');
            exit;
        }

        //Student is validated, loadup their Reivew portal:
        $enrollments = $this->Db_model->remix_enrollments(array(
            'ru.ru_id'     => $ru_id,
        ));

        //Should never happen:
        if(count($enrollments)<1){

            $this->Db_model->e_create(array(
                'e_inbound_u_id' => 0, //System
                'e_text_value' => 'Validated review URL failed to fetch enrollment data',
                'e_inbound_c_id' => 8, //System Error
            ));

            //There is an issue with the key, show error to user:
            redirect_message('/','<div class="alert alert-danger" role="alert">Subscription not found for placing a review.</div>');
            exit;
        }


        $lead_coach = $enrollments[0]['b__coaches'][0]['u_full_name'];

        //Assemble the data:
        $data = array(
            'title' => 'Review '.$lead_coach.' - '.$enrollments[0]['c_outcome'],
            'lead_coach' => $lead_coach,
            'enrollment' => $enrollments[0],
            'ru_key' => $ru_key,
            'ru_id' => $ru_id,
        );

        if(isset($_GET['raw'])){
            echo_json($enrollments[0]);
            exit;
        }

        //Load apply page:
        $this->load->view('front/shared/p_header' , $data);
        $this->load->view('front/student/review_class' , $data);
        $this->load->view('front/shared/p_footer');
    }

    function webview_video($i_id){

        if($i_id>0){
            $messages = $this->Db_model->i_fetch(array(
                'i_id' => $i_id,
                'i_status >' => 0, //Not deleted
            ));
        }

        if(isset($messages[0]) && strlen($messages[0]['i_url'])>0 && in_array($messages[0]['i_media_type'],array('text','video'))){

            if($messages[0]['i_media_type']=='video'){
                //Show video
                echo '<div>'.format_e_text_value('/attach '.$messages[0]['i_media_type'].':'.$messages[0]['i_url']).'</div>';
            } else {
                //Show embed video:
                echo echo_embed($messages[0]['i_url'],$messages[0]['i_message']);
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
                'i_status >' => 0, //Not deleted
            ));
        }

        if(isset($messages[0]) && $messages[0]['i_media_type']=='text' && strlen($messages[0]['i_url'])>0){

            //Is this an embed video?
            $embed_html = echo_embed($messages[0]['i_url'],$messages[0]['i_message']);

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
	
}
