<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_v1 extends CI_Controller {

    /*
     * A hub of external micro apps transmitting data with the Mench server
     * 
     * */

    function __construct() {
		parent::__construct();

		$this->output->enable_profiler(FALSE);
	}

	/* ******************************
	 * Miscs
	 ****************************** */


    function ej_list($e_id){
        $udata = auth(array(1281),1);
        //Fetch blob of engagement and display it on screen:
        $blobs = $this->Db_model->e_fetch(array(
            'ej_e_id' => $e_id,
        ),1,array('ej'));
        if(count($blobs)==1){
            echo_json(array(
                'blob' => unserialize($blobs[0]['ej_e_blob']),
                'e' => $blobs[0]
            ));
        } else {
            echo_json(array('error'=>'Not Found'));
        }
    }

    /* ******************************
	 * Facebook Pages
	 ****************************** */

    function fp_redirect($fp_id,$fp_hash){

	    if(!(md5($fp_id.'pageLinkHash000')==$fp_hash)){
	        die('invalid key');
        }

        $fp_pages = $this->Db_model->fp_fetch(array(
            'fp_id' => $fp_id,
        ), array('fs'));
        if(count($fp_pages)<1){
            die('invalid ID');
        }

        //Go to the inbox app by Facebook
        header( 'Location: https://www.facebook.com/'.$fp_pages[0]['fp_fb_id'].'/inbox/' );
    }

    function fb_connect(){

	    //Responsible to connect and disconnect the Facebook pages when coaches explicitly request this:
        if(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){

            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
            return false;

        } elseif(!isset($_POST['new_b_fp_id']) || intval($_POST['new_b_fp_id'])<0){

            echo_json(array(
                'status' => 0,
                'message' => 'Missing New Facebook Page ID',
            ));
            return false;

        } elseif(!isset($_POST['current_b_fp_id']) || intval($_POST['current_b_fp_id'])<0){

            echo_json(array(
                'status' => 0,
                'message' => 'Missing Current Facebook Page ID',
            ));
            return false;

        }

        $udata = auth(array(1308,1280),0,$_POST['b_id']);
        $bs = $this->Db_model->b_fetch(array(
            'b_id' => $_POST['b_id'],
        ));

        if(!$udata){

            echo_json(array(
                'status' => 0,
                'message' => 'Session expired. Login to try again.',
            ));
            return false;

        } elseif(count($bs)<1){

            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp ID',
            ));
            return false;

        }

        //Validate input Page IDs:
        if($_POST['current_b_fp_id']>0){
            $fp_pages = $this->Db_model->fp_fetch(array(
                'fp_id' => $_POST['current_b_fp_id'],
            ), array('fs'));
            if(count($fp_pages)<1){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Current Page ID',
                ));
                return false;
            }
        }
        if($_POST['new_b_fp_id']>0){
            $fp_pages = $this->Db_model->fp_fetch(array(
                'fp_id' => $_POST['new_b_fp_id'],
            ), array('fs'));
            if(count($fp_pages)<1){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid New Page ID',
                ));
                return false;
            }
        }


        //Is this a disconnect only request?
        $connection_status = false;
        if($_POST['current_b_fp_id']>0 && $_POST['new_b_fp_id']==0){
            //Disconnect
            $connection_status = $this->Comm_model->fb_page_disconnect($udata['u_id'], $_POST['current_b_fp_id'], $_POST['b_id']);
        } elseif($_POST['new_b_fp_id']>0) {
            //Connect, this code handles the disconnect of an existing page if any other page is connected already:
            $connection_status = $this->Comm_model->fb_page_connect($udata['u_id'], $_POST['new_b_fp_id'], $_POST['b_id']);
        }


        //Return result:
        if($connection_status) {
            //All good, let them know:
            echo_json(array(
                'status' => 1,
                'message' => 'Success', //This is not shown...
            ));
        } else {
            //Ooops, some error here...
            echo_json(array(
                'status' => 0,
                'message' => 'Unknown error trying to process your request',
            ));
        }
    }

	function fp_list(){

        $udata = auth(array(1308,1280),0,$_POST['b_id']);
        if(!$udata){

            echo '<div class="alert alert-danger maxout" role="alert"><i class="fas fa-info-circle"></i> Session expired. Login to try again.</div>';
            return false;

        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){

            echo '<div class="alert alert-danger maxout" role="alert"><i class="fas fa-info-circle"></i> Missing Bootcamp ID.</div>';
            return false;

        }

        //Validate Bootcamp and later check current b_fb_id
        $bs = $this->Db_model->b_fetch(array(
            'b_id' => intval($_POST['b_id']),
        ));
        if(count($bs)<1){
            echo '<div class="alert alert-danger maxout" role="alert"><i class="fas fa-info-circle"></i> Invalid Bootcamp ID.</div>';
            return false;
        }


        //Make sure we have their Access Token via the JS call that was made earlier...
        if(!isset($_POST['login_response']['authResponse']['accessToken'])){
            //We have an issue as we cannot access the user's access token, let them know:
            echo '<div class="alert alert-danger maxout" role="alert"><i class="fas fa-info-circle"></i> Cannot fetch your Facebook Access Token.</div>';
            return false;
        }


        //Index and organize their pages:
        $authorized_fp_ids = $this->Comm_model->fb_index_pages($udata['u_id'],$_POST['login_response']['authResponse']['accessToken'],$_POST['b_id']);

        if(!is_array($authorized_fp_ids)){

            //Unknown processing error, let them know about this:
            //This has already been logged via fb_index_pages()
            echo '<div class="alert alert-danger maxout" role="alert"><i class="fas fa-info-circle"></i> Unknown error while trying to list your Facebook Pages.</div>';
            return false;

        }

        //Do we have a page connected, or not?
        if(intval($bs[0]['b_fp_id'])>0 && !in_array($bs[0]['b_fp_id'],$authorized_fp_ids)) {

            //Bootcamp connected to a page they do not control, fetch details and let them know:
            $no_control_pages = $this->Db_model->fp_fetch(array(
                'fp_id' => $bs[0]['b_fp_id']
            ), array('fs'));
            if(count($no_control_pages)>0){
                echo '<div class="alert alert-info maxout" role="alert"><i class="fas fa-plug"></i> Currently connected to a Page you don\'t control: <a href="https://www.facebook.com/'.$no_control_pages[0]['fp_fb_id'].'">'.$no_control_pages[0]['fp_name'].'</a></div>';
            }

        } elseif(intval($bs[0]['b_fp_id'])==0){
            //Indicate to the user that they do not have a match:
            echo '<div class="alert alert-info maxout" role="alert"><i class="fas fa-info-circle"></i> Connect to a Facebook Page to activate Mench</div>';
        }



        //Was any of their previously authorized pages revoked, handle this:
        $admin_lost_pages = $this->Comm_model->fb_detect_revoked($udata['u_id'],$authorized_fp_ids,$_POST['b_id']);
        if(count($admin_lost_pages)>0){
            //Let them know that they lost access to certain pages that is no longer associated with their account:
            echo '<div class="alert alert-info maxout" role="alert"><i class="fas fa-info-circle"></i> You lost access to '.count($admin_lost_pages).' page'.echo__s(count($admin_lost_pages)).' since the last time you logged into Facebook.</div>';
        }




        //List pages:
        $ready_pages = $this->Db_model->fp_fetch(array(
            'fs_inbound_u_id' => $udata['u_id'],
            'fs_status' => 1, //Have access
            'fp_status >=' => 0, //Available or Connected
        ), array('fs'));


        //List UI:
        $pages_list_ui = '<div class="list-group maxout">';
        if(count($ready_pages)>0){
            foreach($ready_pages as $page){

                //Fetch all other Bootcamps this page is connected to:
                $other_bs = $this->Db_model->b_fetch(array(
                    'b.b_fp_id' => $page['fp_id'],
                    'b.b_id !=' => $_POST['b_id'],
                ));

                $pages_list_ui .= '<li class="list-group-item">';

                //Right content
                if($page['fp_status']>=0){
                    $pages_list_ui .= '<span class="pull-right">';

                    if($page['fp_status']==1 && $udata['u_inbound_u_id']==1281){
                        $pages_list_ui .= '<a id="simulate_'.$page['fp_id'].'" class="badge badge-primary btn-mls" href="javascript:fp_refresh('.$page['fp_id'].')" data-toggle="tooltip" title="Refresh the Mench integration on your Facebook Page to resolve any possible connection issues." data-placement="left"><i class="fas fa-sync"></i></a>';
                    }

                    if($bs[0]['b_fp_id']>0 && $page['fp_id']==$bs[0]['b_fp_id']){
                        //This page is already assigned:
                        $pages_list_ui .= '<b><i class="fas fa-plug"></i> Connected</b> &nbsp;';
                        $pages_list_ui .= '<a href="javascript:void(0);" onclick="fb_connect('.$bs[0]['b_fp_id'].',0)" class="badge badge-primary badge-msg" style="text-decoration:none; margin-top:-4px;"><i class="fas fa-times-circle"></i> Disconnect</a>';
                    } else {
                        //Give the option to connect:
                        $pages_list_ui .= '<a href="javascript:void(0);" onclick="fb_connect('.$bs[0]['b_fp_id'].','.$page['fp_id'].')" class="badge badge-primary badge-msg" style="text-decoration:none; margin-top:-4px;"><i class="fas fa-plug"></i> Connect</a>';
                    }
                    $pages_list_ui .= '</span> ';
                }

                //Left content
                $pages_list_ui .= echo_status('fp',$page['fp_status'],true, 'right');
                $pages_list_ui .= ' '.$page['fp_name'];

                //Goes below the list:
                $additional_ui_boxes = null;


                //Do we have a Page greeting?
                if(strlen($page['fp_greeting'])>0){
                    //Show link:
                    $pages_list_ui .= ' &nbsp;<a href="javascript:void(0)" data-toggle="tooltip" title="The Greeting of the Messenger Bot is set by Mench" data-placement="top" onclick="$(\'.fp_greeting_'.$page['fp_id'].'\').toggle()"><i class="fas fa-align-left"></i></a>';

                    //Add Box:
                    $additional_ui_boxes .= '<div class="fp_box fp_greeting_'.$page['fp_id'].'" style="display:none;">';
                    $additional_ui_boxes .= '<h4>Facebook Messenger Bot Greeting:</h4>';
                    $additional_ui_boxes .= nl2br($page['fp_greeting']);
                    $additional_ui_boxes .= '</div>';
                }

                //How about other Connected Bootcamps?
                if(count($other_bs)>0){
                    //Show link:
                    $pages_list_ui .= ' &nbsp;<a href="javascript:void(0)" data-toggle="tooltip" title="This Page is connected to '.count($other_bs).' other Mench Bootcamp'.echo__s(count($other_bs)).'" data-placement="top" onclick="$(\'.fp_current_'.$page['fp_id'].'\').toggle()" style="text-decoration:none;"><i class="fas fa-cube"></i>'.count($other_bs).'</a>';

                    //Show other connected Bootcamps:
                    $additional_ui_boxes .= '<div class="fp_box fp_current_'.$page['fp_id'].'" style="display:none;">';
                    $additional_ui_boxes .= '<h4>Other Mench Bootcamps Connected to this Facebook Page:</h4>';
                    $additional_ui_boxes .= '<ul style="list-style: decimal;">';
                    foreach($other_bs as $count=>$b){
                        $additional_ui_boxes .= '<li><a href="/console/'.$b['b_id'].'">'.$b['c_outcome'].'</a></li>';
                    }
                    $additional_ui_boxes .= '</ul>';
                    $additional_ui_boxes .= '</div>';
                }

                //Link to FB Page
                $pages_list_ui .= ' &nbsp;<a href="https://www.facebook.com/'.$page['fp_fb_id'].'" target="_blank" style="font-size:0.9em;"><i class="fas fa-external-link-square"></i></a>';


                //Addup additional boxes:
                $pages_list_ui .= $additional_ui_boxes;
                //End item:
                $pages_list_ui .= '</li>';
            }
        } else {
            //No page found!
            $pages_list_ui .= '<li class="list-group-item" style="color:#FF0000;"><i class="fas fa-exclamation-triangle"></i> No Facebook Pages found. Create a new one to continue...</li>';
        }

        //Link to create a new Facebook page:
        $pages_list_ui .= '<a href="https://www.facebook.com/pages/create" class="list-group-item"><i class="fas fa-plus-square" style="color:#fedd16;"></i> Create New Facebook Page</a>';
        $pages_list_ui .= '</div>';

        //Show the UI:
        echo $pages_list_ui;

    }

    function fp_refresh(){

        $udata = auth(array(1308,1280),0,$_POST['b_id']);
        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
        } elseif(!isset($_POST['fp_id']) || strlen($_POST['fp_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Page ID',
            ));
        } else {

            //Fetch Page:
            $fp_pages = $this->Db_model->fp_fetch(array(
                'fp_id' => $_POST['fp_id'],
            ), array('fs'));

            if(count($fp_pages)<1){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Could not find page',
                ));
            } elseif(!($fp_pages[0]['fp_status']==1)){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Page is not currently integrated',
                ));
            } else {

                //First remove integration:
                $this->Comm_model->fb_page_integration($udata['u_id'],$fp_pages[0],$_POST['b_id'],0);

                //Then add integration:
                sleep(2);

                $this->Comm_model->fb_page_integration($udata['u_id'],$fp_pages[0],$_POST['b_id'],1);

                //Let user know:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-signal" data-toggle="tooltip" title="Success"></i>',
                ));
            }
        }
    }


    function ru_save_review(){
        if(!isset($_POST['ru_id']) || !isset($_POST['ru_key']) || intval($_POST['ru_id'])<1 || !($_POST['ru_key']==substr(md5($_POST['ru_id'].'r3vi3wS@lt'),0,6))){
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid Subscription Data.</div>');
        } elseif(!isset($_POST['ru_review_score']) || intval($_POST['ru_review_score'])<1 || intval($_POST['ru_review_score'])>10){
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Review Score must be between 1-10.</div>');
        }

        //Validate Subscription:
        $enrollments = $this->Db_model->ru_fetch(array(
            'ru_id' => intval($_POST['ru_id']),
        ));
        if(count($enrollments)<1){
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => 0, //System
                'e_text_value' => 'Validated review submission call failed to fetch enrollment data',
                'e_inbound_c_id' => 8, //System Error
            ));
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Unable to locate your Subscription.</div>');
        }

        //Is this a new review, or updating an existing one?
        $new_review = ( intval($enrollments[0]['ru_review_score'])<0 );
        $has_text = ( strlen($_POST['ru_review_public_note'])>0 || strlen($_POST['ru_review_private_note'])>0 );
        $update_data = array(
            'ru_review_time' => date("Y-m-d H:i:s"),
            'ru_review_score' => $_POST['ru_review_score'],
            'ru_review_public_note' => $_POST['ru_review_public_note'],
            'ru_review_private_note' => $_POST['ru_review_private_note'],
        );

        //Save Engagement that is visible to coach:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $enrollments[0]['u_id'],
            'e_text_value' => ( $new_review ? 'Student rated your Class ' : 'Student updated their rating for your Class to ' ).intval($_POST['ru_review_score']).'/10 with the following review: '.( strlen($_POST['ru_review_public_note'])>0 ? $_POST['ru_review_public_note'] : 'No Review' ),
            'e_json' => $update_data,
            'e_inbound_c_id' => 72, //Student Reviewed Class
        ));

        //Do they have a Private Feedback? Log a need attention Engagement to Mench team reads instantly:
        if(strlen($_POST['ru_review_private_note'])>0){
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $enrollments[0]['u_id'],
                'e_text_value' => 'Received the following private/anonymous feedback: '.$_POST['ru_review_private_note'],
                'e_json' => $update_data,
                'e_inbound_c_id' => 9, //Support Needing Graceful Errors
            ));
        }

        //Update data:
        $this->Db_model->ru_update($enrollments[0]['ru_id'], $update_data);

        //Show success and thank student:
        echo '<div class="alert alert-success">Thanks for '.($new_review?'submitting':'updating').' your review 👌'.( $has_text ? ' We read every single review and use your feedback to continuously improve 🙌​' : '' ).'</div>';

        //TODO Encourage sharing IF reviewed highly...

    }

    function us_save(){

        //Validate integrity of request:
        if(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0
            || !isset($_POST['page_load_time']) || !intval($_POST['page_load_time'])
            || !isset($_POST['s_key']) || !($_POST['s_key']==md5($_POST['c_id'].$_POST['page_load_time'].'pag3l0aDSla7'.$_POST['u_id']))
            || !isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
            die('<span style="color:#FF0000;">Error: Missing Core Data</span>');
        }

        //Fetch student name and details:
        $matching_enrollments = $this->Db_model->ru_fetch(array(
            'ru_outbound_u_id' => intval($_POST['u_id']),
            'ru_r_id' => intval($_POST['r_id']),
            'ru_status >=' => 4, //Only Active students can submit Steps
        ));

        if(!(count($matching_enrollments)==1)){
            die('<span style="color:#FF0000;">Error: You are no longer an active Student of this Bootcamp</span>');
        }

        //Fetch full Bootcamp/Class/Intent data from Action Plan copy:
        $bs = fetch_action_plan_copy(intval($_POST['b_id']),intval($_POST['r_id']));
        $focus_class = $bs[0]['this_class'];
        $intent_data = extract_level( $bs[0] , intval($_POST['c_id']) );


        if(!$focus_class){
            die('<span style="color:#FF0000;">Error: Invalid Class ID!</span>');
        } elseif(!isset($intent_data['intent']) || !is_array($intent_data['intent'])){
            die('<span style="color:#FF0000;">Error: Invalid Task ID</span>');
            //Submission settings:
        } elseif($intent_data['intent']['c_require_url_to_complete'] && count(extract_urls($_POST['us_notes']))<1){
            die('<span style="color:#FF0000;">Error: URL Required to mark as complete. <a href=""><b><u>Refresh this page</u></b></a> and try again.</span>');
        } elseif($intent_data['intent']['c_require_notes_to_complete'] && strlen($_POST['us_notes'])<1){
            die('<span style="color:#FF0000;">Error: Notes Required to mark as complete. <a href=""><b><u>Refresh this page</u></b></a> and try again.</span>');
        }


        //Make sure student has not submitted this Intent before:
        $already_submitted = $this->Db_model->e_fetch(array(
            'e_inbound_c_id' => 33, //Completion Report
            'e_outbound_c_id' => intval($_POST['c_id']), //For this item
            'e_inbound_u_id' => $matching_enrollments[0]['u_id'], //by this Student
            'e_r_id' => intval($_POST['r_id']), //For this Class
            'e_replaced_e_id' => 0, //Data has not been replaced
            'e_status !=' => -3, //Should not be rejected
        ));

        if(count($already_submitted)>0){
            die('<span style="color:#FF0000;">Error: You have already marked this item as complete, You cannot re-submit it.</span>');
        }

        //Do we need to send any notifications to Instuctor for Coaching Students?
        if($matching_enrollments[0]['ru_upfront_pay']>0 && strlen(trim($_POST['us_notes']))>0 && !($matching_enrollments[0]['u_id']==1) /* Shervin does a lot of testing...*/ ){

            //Send email to all coaches of this Bootcamp:
            $b_coaches = $this->Db_model->ba_fetch(array(
                'ba.ba_b_id' => intval($_POST['b_id']),
                'ba.ba_status >=' => 2, //co-coaches & Lead Coach
                'u.u_status' => 1,
            ));

            $student_name = ( isset($matching_enrollments[0]['u_full_name']) && strlen($matching_enrollments[0]['u_full_name'])>0 ? $matching_enrollments[0]['u_full_name'] : 'System' );

            $subject = '⚠️ Review Task Completion '.( strlen(trim($_POST['us_notes']))>0 ? 'Comment' : '(Without Comment)' ).' by '.$student_name;
            $div_style = ' style="padding:5px 0; font-family: Lato, Helvetica, sans-serif; font-size:16px;"';

            //Send notifications to current coach
            foreach($b_coaches as $bi){
                //Make sure this coach has an email on file
                if(strlen($bi['u_email'])>0){
                    //Step Completion Email:
                    //Draft HTML message for this:
                    $html_message  = '<div'.$div_style.'>Hi '.one_two_explode('',' ',$bi['u_full_name']).' 👋​</div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>A new Task Completion report is ready for your review:</div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>Bootcamp: '.$bs[0]['c_outcome'].'</div>';
                    $html_message .= '<div'.$div_style.'>Class: '.echo_time($focus_class['r_start_date'],2).'</div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>Student: '.$student_name.'</div>';
                    $html_message .= '<div'.$div_style.'>Task: '.$intent_data['intent']['c_outcome'].'</div>';
                    $html_message .= '<div'.$div_style.'>Estimated Time: '.echo_estimated_time($intent_data['intent']['c_time_estimate'],0).'</div>';
                    $html_message .= '<div'.$div_style.'>Completion Notes: '.( strlen(trim($_POST['us_notes']))>0 ? nl2br(trim($_POST['us_notes'])) : 'None' ).'</div>';
                    $html_message .= '<br />';
                    $html_message .= '<div'.$div_style.'>Cheers,</div>';
                    $html_message .= '<div'.$div_style.'>Team Mench</div>';
                    $html_message .= '<div><img src="https://s3foundation.s3-us-west-2.amazonaws.com/c65a5ea7c0dd911074518921e3320439.png" /></div>';
                    //Send Email:
                    $this->Comm_model->send_email(array($bi['u_email']), $subject, $html_message, array(
                        'e_inbound_u_id' => ( isset($matching_enrollments[0]['u_id']) ? $matching_enrollments[0]['u_id'] : 0 ), //Student who made submission
                        'e_outbound_u_id' => $bi['u_id'], //The admin
                        'e_text_value' => $subject,
                        'e_json' => array(
                            'html' => $html_message,
                        ),
                        'e_inbound_c_id' => 28, //Email message sent
                        'e_outbound_c_id' => $intent_data['intent']['c_id'],
                        'e_b_id' => intval($_POST['b_id']),
                        'e_r_id' => $focus_class['r_id'],
                    ));
                }
            }
        }


        //See if we need to dispatch any messages:
        $on_complete_text_values = array();
        $drip_messages = array();

        //Dispatch messages for this Step:
        $step_messages = extract_level($bs[0],$_POST['c_id']);

        foreach($step_messages['intent']['c__messages'] as $i){
            if($i['i_status']==2){
                array_push($drip_messages , $i);
            } elseif($i['i_status']==3){
                array_push($on_complete_text_values, array_merge($i , array(
                    'e_inbound_u_id' => 0,
                    'e_outbound_u_id' => $matching_enrollments[0]['u_id'],
                    'i_outbound_c_id' => $i['i_outbound_c_id'],
                    'e_b_id' => intval($_POST['b_id']),
                    'e_r_id' => intval($_POST['r_id']),
                )));
            }
        }

        //Is the Bootcamp Complete?
        if($intent_data['next_level']==1){
            //Seems so!
            foreach($bs[0]['c__messages'] as $i){
                //Bootcamps only could have ON-COMPLETE messages:
                if($i['i_status']==3){
                    array_push($on_complete_text_values, array_merge($i , array(
                        'e_inbound_u_id' => 0,
                        'e_outbound_u_id' => $matching_enrollments[0]['u_id'],
                        'i_outbound_c_id' => $i['i_outbound_c_id'],
                        'e_b_id' => intval($_POST['b_id']),
                        'e_r_id' => intval($_POST['r_id']),
                    )));
                }
            }
        }

        //Anything to be sent instantly?
        if(count($on_complete_text_values)>0){
            //Dispatch all Instant Messages, their engagements have already been logged:
            $this->Comm_model->send_message($on_complete_text_values);
        }

        //Any Drip Messages? Set triggers:
        if(count($drip_messages)>0){

            $start_time = time();
            $drip_intervals = (class_ends($bs[0], $focus_class)-$start_time) / (count($drip_messages)+1);
            $drip_time = $start_time;

            foreach($drip_messages as $i){

                $drip_time += $drip_intervals;
                $this->Db_model->e_create(array(

                    'e_inbound_u_id' => 0, //System
                    'e_outbound_u_id' => $matching_enrollments[0]['u_id'],
                    'e_timestamp' => date("Y-m-d H:i:s" , $drip_time ), //Used by Cron Job to fetch this Drip when due
                    'e_json' => array(
                        'created_time' => date("Y-m-d H:i:s" , $start_time ),
                        'drip_time' => date("Y-m-d H:i:s" , $drip_time ),
                        'i_drip_count' => count($drip_messages),
                        'i' => $i, //The actual message that would be sent
                    ),
                    'e_inbound_c_id' => 52, //Pending Drip e_inbound_c_id=52
                    'e_status' => 0, //Pending for the Drip Cron
                    'e_i_id' => $i['i_id'],
                    'e_outbound_c_id' => $i['i_outbound_c_id'],
                    'e_b_id' => intval($_POST['b_id']),
                    'e_r_id' => intval($_POST['r_id']),

                ));
            }
        }


        //Save student completion report:
        $us_eng = $this->Db_model->e_create(array(
            'e_inbound_u_id' => $matching_enrollments[0]['u_id'],
            'e_status' => -1, //Auto approved
            'e_text_value' => trim($_POST['us_notes']),
            'e_time_estimate' => $intent_data['intent']['c_time_estimate'], //Estimate time spent on this item
            'e_inbound_c_id' => 33, //Completion Report
            'e_outbound_c_id' => intval($_POST['c_id']),
            'e_b_id' => intval($_POST['b_id']),
            'e_r_id' => intval($_POST['r_id']),
            'e_json' => array(
                'input' => $_POST,
                'scheduled_drip' => count($drip_messages),
                'sent_oncomplete' => count($on_complete_text_values),
                'next_level' => $intent_data['next_level'],
                'next_c' => ( isset($intent_data['next_intent']) ? $intent_data['next_intent'] : array() ),
            ),
        ));


        //Show result to student:
        echo_completion_report($us_eng);


        //Take action based on what the next level is...
        if($intent_data['next_level']==1){

            //The next level is the Bootcamp, which means this was the last Step:
            $this->Db_model->ru_update( $matching_enrollments[0]['ru_id'] , array(
                'ru_cache__completion_rate' => 1, //Student is 100% complete
                'ru_cache__current_task' => ($focus_class['r__total_tasks']+1), //Go 1 Task after the total Tasks to indicate completion
            ));

            //Change their entity Group
            if(in_array($matching_enrollments[0]['u_inbound_u_id'],array(1304,1282,1279))){
                $this->Db_model->u_update( $matching_enrollments[0]['u_id'] , array(
                    'u_inbound_u_id' => 1307, //Graduate
                ));
            }

            //Send graduation message:
            $this->Comm_model->foundation_message(array(
                'e_outbound_u_id' => intval($_POST['u_id']),
                'e_outbound_c_id' => 4632, //As soon as Graduated message
                'depth' => 0,
                'e_b_id' => intval($_POST['b_id']),
                'e_r_id' => intval($_POST['r_id']),
            ));

        } elseif($intent_data['next_level']==2){

            //We have a next Task:
            //We also need to change ru_cache__current_task to reflect this advancement:
            $this->Db_model->ru_update( $matching_enrollments[0]['ru_id'] , array(
                'ru_cache__completion_rate' => number_format( ( $matching_enrollments[0]['ru_cache__completion_rate'] + ($intent_data['intent']['c_time_estimate']/$bs[0]['c__estimated_hours']) ),8),
                'ru_cache__current_task' => $intent_data['next_intent']['cr_outbound_rank'],
            ));

            //Show button for next Task:
            echo '<div style="font-size:1.2em;"><a href="/my/actionplan/'.$_POST['b_id'].'/'.$intent_data['next_intent']['c_id'].'" class="btn btn-black">Next <i class="fas fa-arrow-right"></i></a></div>';


        } else {

            //This should not happen!
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $_POST['u_id'],
                'e_text_value' => 'us_save() experienced fatal error where $intent_data/next_level was not 1,2 or 3',
                'e_json' => array(
                    'POST' => $_POST,
                    'intent_data' => $intent_data,
                ),
                'e_inbound_c_id' => 8, //Platform Error
                'e_b_id' => $_POST['b_id'],
                'e_r_id' => $_POST['r_id'],
                'e_outbound_c_id' => $_POST['c_id'],
            ));

        }
    }

}
