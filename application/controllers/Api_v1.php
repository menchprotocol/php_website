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

    function ping(){
        echo_json(array('status'=>'success'));
    }

    function index(){
        die('nothing here...');
    }

    function e_js_create(){
	    //Validate hash code:
        if(!isset($_POST['e_hash_time']) || !isset($_POST['e_hash_code']) || strlen($_POST['e_hash_time'])<5 || strlen($_POST['e_hash_code'])<5 || !(md5($_POST['e_hash_time'].'hashcod3')==$_POST['e_hash_code'])){

            echo_json(array(
                'status' => 0,
                'message' => 'invalid hash key',
            ));

        } else {

            //Remove hash data:
            unset($_POST['e_hash_time']);
            unset($_POST['e_hash_code']);

            //Log engagement:
            $new_e = $this->Db_model->e_create($_POST);

            //Show messages:
            if($new_e['e_id']>0){
                echo_json(array(
                    'status' => 1,
                    'message' => 'Logged Engagement #'.$new_e['e_id'],
                ));
            } else {
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid data structure for engagement logging',
                ));
            }

        }
    }

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

    /* ******************************
     * Enrollment
     ****************************** */

    function ru_date_selector(){

        //This function generates a list of dates that the student can register in the class

        $application_status_salt = $this->config->item('application_status_salt');
        $class_settings = $this->config->item('class_settings');

        if(!isset($_POST['ru_support_package']) || !in_array(intval($_POST['ru_support_package']),array(1,2,3)) || !isset($_POST['ru_id']) || intval($_POST['ru_id'])<1 || !isset($_POST['u_key']) || !isset($_POST['u_id']) || intval($_POST['u_id'])<1 || !(md5($_POST['u_id'].$application_status_salt)==$_POST['u_key'])){

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => ( isset($_POST['u_id']) ? intval($_POST['u_id']) : 0 ),
                'e_text_value' => 'ru_date_selector() Missing Core Inputs.',
                'e_json' => $_POST,
                'e_inbound_c_id' => 8, //Platform Error
            ));

            //Display Error:
            die('<span style="color:#FF0000;">Error: Missing Core Inputs. Report Logged for Admin to review.</span>');
        }

        //Fetch enrollment:
        $_POST['ru_support_package'] = intval($_POST['ru_support_package']);
        $enrollments = $this->Db_model->remix_enrollments(array(
            'ru.ru_id'	=> intval($_POST['ru_id']),
        ));

        //Make sure we got all this data:
        if(!(count($enrollments)==1) || !isset($enrollments[0]['b_id'])){

            //Log this error:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $_POST['u_id'],
                'e_text_value' => 'ru_date_selector() failed to fetch enrollment data for ru_id=['.$_POST['ru_id'].'].',
                'e_json' => $_POST,
                'e_inbound_c_id' => 8, //Platform Error
            ));

            //Error:
            die('<span style="color:#FF0000;">Error: Failed to fetch enrollment data. Report Logged for Admin to review.</span>');
        }



        //Now loadup the dates based on Class:
        //echo '<option value="">Choose Class Dates...</option>';
        //Access Classes for this Bootcamp and show available options:
        $classes = $this->Db_model->r_fetch(array(
            'r.r_b_id' => $enrollments[0]['b_id'],
            'r.r_status IN (0,1)' => null,
            'r_start_date >' => date("Y-m-d"),
        ), null, 'ASC', $class_settings['students_show_max']);

        foreach($classes as $class){

            if($_POST['ru_support_package']>1){

                if($class['r_status']==0){
                    continue;
                }

                //Are all child classes available for this Multi-week Bootcamp?
                //Now go through all Bootcamps and see if their Classes are available with this start date
                $not_available_reason = null;
                foreach($enrollments[0]['c__child_intents'] as $key=>$b7d){
                    //Fetch corresponding Class:
                    $validate_classes = $this->Db_model->r_fetch(array(
                        'r.r_b_id' => $b7d['b_id'],
                        'r.r_start_date' => date("Y-m-d",(strtotime($class['r_start_date'])+($key*7*24*3600)+(12*3600)  /* For GMT/timezone adjustments */ )),
                        'r.r_status' => 1,
                    ));
                    if(count($validate_classes)<1){
                        //Class not found!
                        $not_available_reason = 'Not Found';
                        break;
                    }
                }

                if($not_available_reason){
                    continue;
                }
            }

            echo '<option value="'.$class['r_id'].'">';
            echo echo_time($class['r_start_date'],5) .' - '. echo_time($class['r_start_date'],5, (($enrollments[0]['b_weeks_count']*7*24*3600)-(8*3600)));
            echo '</option>';
        }

    }

    function ru_checkout_initiate(){

        $this->load->helper('cookie');
        $application_status_salt = $this->config->item('application_status_salt');

        if(!isset($_POST['b_id']) || intval($_POST['b_id'])<1){
            die(echo_json(array(
                'status' => 0,
                'error_message' => '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> Missing Core Data</div>',
            )));
        } elseif(!isset($_POST['u_full_name'])){

            die(echo_json(array(
                'status' => 0,
                'error_message' => '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> Invalid name, try again.</div>',
            )));

        } elseif(!isset($_POST['u_email']) || strlen($_POST['u_email'])<1 || !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){

            die(echo_json(array(
                'status' => 0,
                'error_message' => '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> Invalid email, try again.</div>',
            )));

        }

        $bs = $this->Db_model->remix_bs(array(
            'b.b_id' => $_POST['b_id'],
        ));

        //Display results:
        if(count($bs)<1) {
            die(echo_json(array(
                'status' => 0,
                'error_message' => '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> Invalid Bootcamp ID</div>',
            )));
        }

        $b = $bs[0];

        //Fetch user data to see if already registered:
        $users = $this->Db_model->u_fetch(array(
            'u_email' => strtolower($_POST['u_email']),
        ));

        if(count($users)==0){

            if(!isset($_POST['u_full_name']) || strlen($_POST['u_full_name'])<2){
                //Invalid First name,
                die(echo_json(array(
                    'status' => 0,
                    'error_message' => '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> Invalid name, try again.</div>',
                )));
            } else {

                //Create new user:
                $udata = $this->Db_model->u_create(array(
                    'u_language' 		=> 'en', //Since they answered initial questions in English
                    'u_email' 			=> trim(strtolower($_POST['u_email'])),
                    'u_full_name' 		=> trim($_POST['u_full_name']),
                    'u_inbound_u_id'    => 1304, //Interested
                ));

                //Log Engagement for registration:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $udata['u_id'], //The user that updated the account
                    'e_json' => array(
                        'input' => $_POST,
                        'udata' => $udata,
                    ),
                    'e_inbound_c_id' => 27, //New Student Lead
                    'e_b_id' => $b['b_id'],
                ));

            }

        } else {

            //This is a registered user!
            $udata = $users[0];

            //Make sure they have not enrolled in this Class before:
            $duplicate_registries = $this->Db_model->ru_fetch(array(
                'ru.ru_outbound_u_id'	            => $udata['u_id'],
                'ru.ru_b_id'	            => $b['b_id'],
                'ru.ru_parent_ru_id'	    => 0, //We only care about the main enrollment
                'ru.ru_status IN (0,4,7)'   => null,
            ), array('ru.ru_status' => 'DESC'));

            if(count($duplicate_registries)>0){

                //Send the email to their enrollment page:
                $this->Comm_model->foundation_message(array(
                    'e_inbound_u_id' => 0,
                    'e_outbound_u_id' => $udata['u_id'],
                    'e_outbound_c_id' => 2697,
                    'depth' => 0,
                    'e_b_id' => $duplicate_registries[0]['ru_b_id'],
                    'e_r_id' => $duplicate_registries[0]['r_id'],
                ), true);

                if($duplicate_registries[0]['ru_status']==0){

                    if($b['b_requires_assessment'] && $duplicate_registries[0]['ru_assessment_result']<1){
                        //Take them to assessment as they have not yet taken it or have failed it:
                        $redirect_url = '/'.$b['b_url_key'].'/assessment?u_email='.$udata['u_email'];
                    } else {
                        //Take them to complete their checkout:
                        $redirect_url = '/'.$b['b_url_key'].'/checkout?u_email='.$udata['u_email'];
                    }

                    //Redirect to application so they can continue:
                    die(echo_json(array(
                        'status' => 1,
                        'hard_redirect' => $redirect_url,
                    )));

                } else {

                    //Show them an error:
                    die(echo_json(array(
                        'status' => 0,
                        'error_message' => '<div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> You have already enrolled in this Bootcamp. Your application status is ['.trim(strip_tags(echo_status('ru',$duplicate_registries[0]['ru_status']))).']. '.($duplicate_registries[0]['ru_status']==-2 ? '<a href="/contact"><u>Contact us</u></a> if you like to restart your application.' : 'We emailed you a link to manage your enrollments. Check your email to continue.').'</div>',
                    )));

                }

            } else {

                //They have never signed up for this Bootcamp, it's all good!
                //Change the entity bucket if needed:
                if(in_array($udata['u_inbound_u_id'], array(1304,1282))){
                    $this->Db_model->u_update( $udata['u_id'] , array(
                        'u_inbound_u_id' => 1304, //Interested
                    ));
                }

            }
        }


        //Admit student:
        $enrollments[0] = $this->Db_model->enroll_student($udata['u_id'],$b);


        //Determie where to redirect depending on whether the Bootcamp has a assessment or not:
        if($b['b_requires_assessment'] && $enrollments[0]['ru_assessment_result']<1){
            //Take them to assessment as they have not yet taken it or have failed it:
            $redirect_url = '/'.$b['b_url_key'].'/assessment?u_email='.$udata['u_email'];
        } else {
            //Take them to complete their checkout:
            $redirect_url = '/'.$b['b_url_key'].'/checkout?u_email='.$udata['u_email'];
        }

        //Redirect to application:
        die(echo_json(array(
            'status' => 1,
            'hard_redirect' => $redirect_url,
        )));

    }

    function load_inbound_c(){

        $udata = auth(array(1308,1280),0,$_POST['b_id']);
        if(!$udata){
            return echo_json(array(
                'status' => 0,
                'message' => 'Session expired. Login to continue.',
            ));
        } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent ID.',
            ));
        }

        //Show Potential parent Bootcamps:
        $parent_bs = $this->Db_model->cr_inbound_fetch(array(
            'cr.cr_outbound_b_id' => $b['b_id'],
            'cr.cr_status >=' => 1,
            'b.b_status >=' => 2, //Published in some way
        ),array('b'));


        //Did we find anything?
        if(count($parent_bs)>0){
            echo '<div class="title" style="margin-top:30px;"><h4><b><i class="fas fa-cubes"></i> Parent Bootcamps</b></a></h4></div>';
            echo '<div class="list-group maxout">';
            foreach ($parent_bs as $parent_b){
                echo '<a href="/console/'.$parent_b['b_id'].'/actionplan" class="list-group-item">';
                echo '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fas fa-chevron-right"></i></span></span>';
                echo '<i class="fas fa-cubes"></i> ';
                echo $parent_b['c_outcome'];
                echo '</a>';
            }
            echo '</div>';
        }
    }


    function ru_save_review(){
        if(!isset($_POST['ru_id']) || !isset($_POST['ru_key']) || intval($_POST['ru_id'])<1 || !($_POST['ru_key']==substr(md5($_POST['ru_id'].'r3vi3wS@lt'),0,6))){
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid Enrollment Data.</div>');
        } elseif(!isset($_POST['ru_review_score']) || intval($_POST['ru_review_score'])<1 || intval($_POST['ru_review_score'])>10){
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Review Score must be between 1-10.</div>');
        }

        //Validate Enrollment:
        $enrollments = $this->Db_model->ru_fetch(array(
            'ru_id' => intval($_POST['ru_id']),
        ));
        if(count($enrollments)<1){
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => 0, //System
                'e_text_value' => 'Validated review submission call failed to fetch enrollment data',
                'e_inbound_c_id' => 8, //System Error
            ));
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Unable to locate your Enrollment.</div>');
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
            'e_b_id' => $enrollments[0]['ru_b_id'],
            'e_r_id' => $enrollments[0]['r_id'],
        ));

        //Do they have a Private Feedback? Log a need attention Engagement to Mench team reads instantly:
        if(strlen($_POST['ru_review_private_note'])>0){
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $enrollments[0]['u_id'],
                'e_text_value' => 'Received the following private/anonymous feedback: '.$_POST['ru_review_private_note'],
                'e_json' => $update_data,
                'e_inbound_c_id' => 9, //Support Needing Graceful Errors
                'e_b_id' => $enrollments[0]['ru_b_id'],
                'e_r_id' => $enrollments[0]['r_id'],
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
            || !isset($_POST['b_id']) || intval($_POST['b_id'])<=0
            || !isset($_POST['r_id']) || intval($_POST['r_id'])<=0
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
        } elseif($intent_data['intent']['c_complete_url_required']=='t' && count(extract_urls($_POST['us_notes']))<1){
            die('<span style="color:#FF0000;">Error: URL Required. <a href=""><b><u>Refresh this page</u></b></a> and try again.</span>');
        } elseif($intent_data['intent']['c_complete_notes_required']=='t' && strlen($_POST['us_notes'])<1){
            die('<span style="color:#FF0000;">Error: Notes Required. <a href=""><b><u>Refresh this page</u></b></a> and try again.</span>');
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
            $drip_intervals = ($focus_class['r__class_end_time']-$start_time) / (count($drip_messages)+1);
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

    /* ******************************
     * r Classes
     ****************************** */

    function r_export($r_id){

        if(intval($r_id)<1){
            die('<span style="color:#FF0000;">Error: Invalid ID.</span>');
        }

        //Validate Class:
        $classes = $this->Db_model->r_fetch(array(
            'r_id' => $r_id,
        ) , null , 'DESC', 1, array('b') );

        if(count($classes)!==1){
            die('<span style="color:#FF0000;">Error: Invalid Class ID.</span>');
        }

        $udata = auth(array(1308,1280),0,$classes[0]['r_b_id']);
        if(!$udata){
            die('<span style="color:#FF0000;">Error: Session Expired.</span>');
        }



        //Fetch all Studnets:
        $enrollments = $this->Db_model->ru_fetch(array(
            'r.r_id'	   => $r_id, //Open for enrollment
            'ru.ru_status >='  => 4, //Initiated or higher as long as Bootcamp is running!
        ));

        if(count($enrollments)==0){
            die('<span style="color:#FF0000;">Error: Class has no Students.</span>');
        }

        //Log Engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'],
            'e_inbound_c_id' => 88, //Exported
            'e_b_id' => $classes[0]['r_b_id'],
            'e_r_id' => $classes[0]['r_id'],
        ));

        //Echo the export file:
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=".$classes[0]['c_outcome']." - Class of ".echo_time($classes[0]['r_start_date'],1)." Student List (".count($enrollments).").xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "#";
        echo "\tName";
        echo "\tEmail";
        echo "\tActive On";
        echo "\tTimezone";
        echo "\tGender";
        echo "\tLanguage";
        echo "\tStatus";
        echo "\r\n";

        $counter = 0;
        foreach($enrollments as $enrollment){

            $counter++;
            echo $counter;
            echo "\t".$enrollment['u_full_name'];
            echo "\t".$enrollment['u_email'];
            echo "\t";
                if($enrollment['u_cache__fp_psid']>0){
                    echo 'Messenger';
                } else {
                    echo 'Email';
                }
                if(strlen($enrollment['u_password'])>0){
                    echo ', Student Hub';
                }
            echo "\t".$enrollment['u_timezone'];
            echo "\t".$enrollment['u_gender'];
            echo "\t".$enrollment['u_language'];
            echo "\t".trim(strip_tags(echo_status('ru',$enrollment['ru_status'])));
            echo "\r\n";
        }
    }

    function r_sync_c(){
        $udata = auth(array(1308,1280),0,$_POST['b_id']);
        if(!$udata){
            echo '<span style="color:#FF0000;">Error: Session Expired.</span>';
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo '<span style="color:#FF0000;">Error: Missing Bootcamp ID.</span>';
        } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){
            echo '<span style="color:#FF0000;">Error: Missing Class ID.</span>';
        } else {
            //All Good, start updating:
            $saved = $this->Db_model->snapshot_action_plan($_POST['b_id'],$_POST['r_id']);
            //Show Message:
            if($saved){
                echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>';
            } else {
                echo '<span style="color:#FF0000;">Unknown Error while trying to save the Action Plan</span>';
            }
        }
    }

	function r_update_status(){

        $udata = auth(array(1308,1280), 0);
        $_POST['r_new_status'] = intval($_POST['r_new_status']);
        $_POST['r_id'] = intval($_POST['r_id']);

        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue.',
            ));
            exit;
        } elseif(!isset($_POST['r_id']) || intval($_POST['r_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Class ID',
            ));
            exit;
	    } elseif(!isset($_POST['r_new_status']) || !in_array($_POST['r_new_status'],array(0,1))){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Class Status',
            ));
            exit;
	    }

	    //Fetch Class:
	    $classes = $this->Db_model->r_fetch(array(
	        'r.r_id' => $_POST['r_id'],
	    ));

	    if(count($classes)<1){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Class ID',
            ));
            exit;
	    } elseif(!(date("N",strtotime($classes[0]['r_start_date']))==1)){
            //Make sure the start Date is a Monday:
            echo_json(array(
                'status' => 0,
                'message' => 'Class does not start on Monday, cannot Toggle Support',
            ));
            exit;
        }


        //Are they attempting to disable Support?
        if(!$_POST['r_new_status']){

            //Let's make sure this Class has NOT sold any seats:
            $coaching_enrollments = count($this->Db_model->ru_fetch(array(
                'ru_r_id' => $_POST['r_id'],
                'ru_status >=' => 4,
                'ru_upfront_pay >' => 0,
            )));
            if($coaching_enrollments>0){

                //Inform Admin:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $udata['u_id'],
                    'e_text_value' => $udata['u_full_name'].' (Lead Coach) is trying to disable coaching for the week of ['.$classes[0]['r_start_date'].'] that they have already sold ['.$coaching_enrollments.'] coaching packages. Reach out to see if they need any help with this.',
                    'e_inbound_c_id' => 9, //Support Needed
                    'e_b_id' => $classes[0]['r_b_id'],
                    'e_r_id' => $classes[0]['r_id'],
                ));

                echo_json(array(
                    'status' => 0,
                    'message' => $coaching_enrollments.' Student'.echo__s($coaching_enrollments).' have already paid for your Classroom for this Week for 1 or more of the Bootcamps you lead, so you cannot disable support until those Students are refunded. Contact Mench support if you need to disable support.',
                ));
                exit;

            }
        }

        //Change status
        $this->Db_model->r_update( $_POST['r_id'] , array(
            'r_status' => $_POST['r_new_status'],
        ));


        //Log engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'], //The user
            'e_inbound_c_id' => ( $_POST['r_new_status'] ? 86 : 87 ), //Class coaching Enabled/Disabled
            'e_b_id' => $classes[0]['r_b_id'],
            'e_r_id' => $classes[0]['r_id'],
        ));

	    //Show result:
        echo_json(array(
            'status' => 1,
            'r_new_status' => $_POST['r_new_status'],
            'message' => echo_status('r',$_POST['r_new_status'],true, null),
        ));

	}

	/* ******************************
	 * b Bootcamps
	 ****************************** */

	function b_create(){

	    $udata = auth(array(1308,1280));
	    if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session expired. Login to try again.',
            ));
            return false;
        } elseif(!isset($_POST['c_outcome']) || strlen($_POST['c_outcome'])<2){
            echo_json(array(
                'status' => 0,
                'message' => 'Outcome must be 2 characters or longer.',
            ));
            return false;
	    }


        //Create new intent:
        $intent = $this->Db_model->c_create(array(
            'c_inbound_u_id' => $udata['u_id'],
            'c_outcome' => trim($_POST['c_outcome']),
        ));
        if(intval($intent['c_id'])<=0){
            //Log this error:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_text_value' => 'b_create() Function failed to create intent ['.$_POST['c_outcome'].'].',
                'e_json' => $_POST,
                'e_inbound_c_id' => 8, //Platform Error
            ));

            echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to create intent.',
            ));
            return false;
        }


        //Generaye URL Key:
        //Cleans text:
        $generated_key = generate_hashtag($_POST['c_outcome']);


        //Check for duplicates:
        $bs = $this->Db_model->b_fetch(array(
            'LOWER(b.b_url_key)' => strtolower($generated_key),
        ));
        if(count($bs)>0){
            //Ooops, we have a duplicate:
            $generated_key = $generated_key.rand(0,99999);
        }

        //Fetch default list values:
        $default_class_prerequisites = $this->config->item('default_class_prerequisites');
        $mench_support_team = $this->config->item('mench_support_team');


        //Create new Bootcamp:
        $b = $this->Db_model->b_create(array(
            'b_fp_id' => ( in_array($udata['u_id'],$mench_support_team) ? 4 : 0 ), //Assign Mench Facebook Page for Mench team
            'b_url_key' => $generated_key,
            'b_outbound_c_id' => $intent['c_id'],
            'b_prerequisites' => json_encode($default_class_prerequisites),
            'b_weeks_count' => 1, //Default is 1 week, can change later...
        ));

        if(intval($b['b_id'])<=0 || intval($b['b_outbound_c_id'])<=0){
            //Log this error:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_text_value' => 'b_create() Function failed to create Bootcamp for intent #'.$intent['c_id'],
                'e_json' => $_POST,
                'e_inbound_c_id' => 8, //Platform Error
            ));
            echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to create Bootcamp.',
            ));
            return false;
        }


        //Create all Classes:
        $new_class_count = $this->Db_model->r_sync($b['b_id']);


        //Add this Bootcamp to the General category:
        /*
        $this->Db_model->cr_create(array(
            'cr_inbound_u_id' => $udata['u_id'],
            'cr_inbound_c_id'  => 6180, //General category for Job Placement
            'cr_outbound_c_id' => $b['b_outbound_c_id'],
            'cr_status' => 1,
        ));
        */

        //Assign permissions for this user:
        $admin_status = $this->Db_model->ba_create(array(
            'ba_outbound_u_id' => $udata['u_id'],
            'ba_status' => 3, //Leader - As this is the first person to create
            'ba_b_id' => $b['b_id'],
        ));

        //Did it go well?
        if(intval($admin_status['ba_id'])<=0){
            //Log this error:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_text_value' => 'b_create() Function failed to grant permission for Bootcamp #'.$b['b_id'],
                'e_json' => $_POST,
                'e_inbound_c_id' => 8, //Platform Error
            ));
            echo_json(array(
                'status' => 0,
                'message' => 'Unknown error while trying to set Bootcamp leader.',
            ));
            return false;
        }

        //Update algolia:
        $this->Db_model->algolia_sync('b',$b['b_id']);


        //Log Engagement for Intent Created:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'],
            'e_json' => array(
                'input' => $_POST,
                'before' => null,
                'after' => $intent,
            ),
            'e_inbound_c_id' => 20, //Intent Created
            'e_b_id' => $b['b_id'],
            'e_outbound_c_id' => $intent['c_id'],
        ));


        //Log Engagement for Bootcamp Created:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'],
            'e_json' => array(
                'input' => $_POST,
                'before' => null,
                'after' => $b,
            ),
            'e_inbound_c_id' => 15, //Bootcamp Created
            'e_b_id' => $b['b_id'],
        ));


        //Log Engagement for Permission Granted:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'],
            'e_text_value' => 'Assigned as Bootcamp Leader',
            'e_json' => array(
                'input' => $_POST,
                'before' => null,
                'after' => $admin_status,
            ),
            'e_inbound_c_id' => 25, //Permission Granted
            'e_b_id' => $b['b_id'],
        ));


        //Show message & redirect:
        //For fancy UI to give impression of hard work:
        echo_json(array(
            'status' => 1,
            'message' => echo_b(array_merge($b,$intent)),
        ));

	}

    function b_save_list(){
        //Auth user and Load object:
        $udata = auth(array(1308,1280));
        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
        } elseif(!isset($_POST['group_id']) || !in_array($_POST['group_id'],array('b_prerequisites','b_transformations','b_coaching_services'))){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Group ID',
            ));
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
        } else {

            //Updatye Bootcamp:
            $this->Db_model->b_update( intval($_POST['b_id']) , array(
                $_POST['group_id'] => ( isset($_POST['new_sort']) && is_array($_POST['new_sort']) && count($_POST['new_sort'])>0 ? json_encode($_POST['new_sort']) : null ),
            ));

            //Update algolia:
            $this->Db_model->algolia_sync('b',$_POST['b_id']);

            //Log Engagement:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_json' => $_POST,
                'e_inbound_c_id' => 53, //Bootcamp List Modified
                'e_b_id' => intval($_POST['b_id']),
            ));

            //Display message:
            echo_json(array(
                'status' => 1,
                'message' => '<i class="fas fa-check"></i> Saved',
            ));
        }
    }

    function b_save_settings(){

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));

        //Validate Bootcamp ID:
        if(isset($_POST['b_id'])){
            $bs = $this->Db_model->b_fetch(array(
                'b.b_id' => intval($_POST['b_id']),
            ));
        }

        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
            return false;
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0 || count($bs)<1){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp ID',
            ));
            return false;
	    } elseif(!isset($_POST['b_url_key'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp URL',
            ));
            return false;
        } elseif(!isset($_POST['b_status'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp Status',
            ));
            return false;
        } elseif($_POST['level1_c_id']>0 && $_POST['level2_c_id']==0){
            echo_json(array(
                'status' => 0,
                'message' => 'Select Category',
            ));
            return false;
        } elseif(strlen($_POST['b_post_enrollment_url_diy'])>0 && !filter_var($_POST['b_post_enrollment_url_diy'], FILTER_VALIDATE_URL)){
            echo_json(array(
                'status' => 0,
                'message' => 'Enter Valid Post-Enrollment URL',
            ));
            return false;
        } elseif(!isset($_POST['b_offers_diy']) || !isset($_POST['b_offers_coaching'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing enrollment data',
            ));
            return false;
        } elseif(intval($_POST['b_offers_coaching']) && (doubleval($_POST['b_weekly_coaching_hours'])<=0 || doubleval($_POST['b_weekly_coaching_rate'])<=0)){
            echo_json(array(
                'status' => 0,
                'message' => 'Coaching hours and rate are required to offer a coaching package',
            ));
            return false;
        } elseif(intval($_POST['b_offers_coaching']) && (doubleval($_POST['b_weekly_coaching_hours'])<0.5 || doubleval($_POST['b_weekly_coaching_rate'])<30)){
            echo_json(array(
                'status' => 0,
                'message' => 'Coaching packages must have at-least half an hour of coaching at a minimum of $30 USD ($1/minute)',
            ));
            return false;
        } elseif(!isset($_POST['b_requires_assessment']) || !in_array($_POST['b_requires_assessment'],array(0,1))){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Call to Action Settings',
            ));
            return false;
        } elseif($_POST['b_requires_assessment']==1 && (strlen($_POST['b_assessment_url'])<1 || !filter_var($_POST['b_assessment_url'], FILTER_VALIDATE_URL))){
            echo_json(array(
                'status' => 0,
                'message' => 'A valid Assessment Embed URL is required to enable the instant assessment.',
            ));
            return false;
        } elseif($_POST['b_requires_assessment']==1 && (intval($_POST['b_assessment_minutes'])<0 || intval($_POST['b_assessment_minutes'])>120)){
            echo_json(array(
                'status' => 0,
                'message' => 'Assessment time must be between 0 (which means Disabled) to 120 Minutes long. ['.$_POST['b_assessment_minutes'].'] does not fall within that range.',
            ));
            return false;
        } elseif($_POST['b_offers_deferred'] && !$_POST['b_offers_job']){
            echo_json(array(
                'status' => 0,
                'message' => 'This Bootcamp must offer a Job in order to be eligible for the deferred payment program',
            ));
            return false;
        } elseif(!isset($_POST['b_weeks_count']) || intval($_POST['b_weeks_count'])<1){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp Duration',
            ));
            return false;
        } elseif(!isset($_POST['b_unlock_intents']) || intval($_POST['b_unlock_intents'])<0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent Unlock Rate',
            ));
            return false;
        }


        //Fetch config variables:
        $reserved_hashtags = $this->config->item('reserved_hashtags');


        //Validate URL Key to be unique:
        $duplicate_bs = $this->Db_model->b_fetch(array(
            'LOWER(b.b_url_key)' => strtolower($_POST['b_url_key']),
            'b.b_id !=' => $_POST['b_id'],
        ));

        //Check URL Key:
        if(in_array(strtolower($_POST['b_url_key']),$reserved_hashtags)){
            echo_json(array(
                'status' => 0,
                'message' => '"'.$_POST['b_url_key'].'" is a reserved URL.',
            ));
            return false;
        } elseif(strlen($_POST['b_url_key'])>30){
            echo_json(array(
                'status' => 0,
                'message' => 'URL Key should be less than 30 characters',
            ));
            return false;
        } elseif(strlen($_POST['b_url_key'])<2){
            echo_json(array(
                'status' => 0,
                'message' => 'URL Key should be at least 2 characters long',
            ));
            return false;
        } elseif(ctype_digit($_POST['b_url_key'])){
            echo_json(array(
                'status' => 0,
                'message' => 'URL Key should include at-least 1 letter.',
            ));
            return false;
        } elseif(!(strtolower(generate_hashtag($_POST['b_url_key']))==strtolower($_POST['b_url_key']))){
            echo_json(array(
                'status' => 0,
                'message' => 'URL Key can only include letters a-z and numbers 0-9',
            ));
            return false;
        } elseif(count($duplicate_bs)>0){
            echo_json(array(
                'status' => 0,
                'message' => 'URL Key <a href="/'.$_POST['b_url_key'].'" target="_blank">'.$_POST['b_url_key'].'</a> already taken.',
            ));
            return false;
        }



        //Update Bootcamp:
        $b_update = array(
            'b_status' => intval($_POST['b_status']),
            'b_url_key' => $_POST['b_url_key'],
            'b_fb_pixel_id' => ( strlen($_POST['b_fb_pixel_id'])>0 ? bigintval($_POST['b_fb_pixel_id']) : NULL ),
            'b_post_enrollment_url_diy' => $_POST['b_post_enrollment_url_diy'],

            'b_offers_diy' => intval($_POST['b_offers_diy']),

            'b_offers_coaching' => intval($_POST['b_offers_coaching']),
            'b_weekly_coaching_hours' => doubleval($_POST['b_weekly_coaching_hours']),
            'b_weekly_coaching_rate' => doubleval($_POST['b_weekly_coaching_rate']),


            'b_offers_job' => intval($_POST['b_offers_job']),
            'b_offers_deferred' => intval($_POST['b_offers_deferred']),

            'b_guarantee_weeks' => intval($_POST['b_guarantee_weeks']),
            'b_weeks_count' => intval($_POST['b_weeks_count']),
            'b_unlock_intents' => intval($_POST['b_unlock_intents']),

            'b_requires_assessment' => intval($_POST['b_requires_assessment']),
            'b_assessment_url' => $_POST['b_assessment_url'],
            'b_assessment_minutes' => intval($_POST['b_assessment_minutes']),
        );

        //Only update
        if(!$bs[0]['b_offers_deferred'] && $_POST['b_offers_deferred'] && !$bs[0]['b_deferred_rate']){
            //Set default deferred rate for this Bootcamp:
            $deferred_pay_defaults = $this->config->item('deferred_pay_defaults');
            $b_update['b_deferred_rate'] = $deferred_pay_defaults['b_deferred_rate'];
            $b_update['b_deferred_deposit'] = $deferred_pay_defaults['b_deferred_deposit'];
            $b_update['b_deferred_payback'] = $deferred_pay_defaults['b_deferred_payback'];
        }


        $this->Db_model->b_update( intval($_POST['b_id']) , $b_update );

        //Do we need to change the public status of intents?
        if(!($b_update['b_offers_diy']==$bs[0]['b_offers_diy'])){
            //TODO the value has changed! Let's update...
        }


        //Check to see what Category this Bootcamp Belongs to:
        $current_c_ids = array();
        //TODO Imorove further by only getching links associated to categorization
        $current_inbounds = $this->Db_model->cr_inbound_fetch(array(
            'cr.cr_outbound_c_id' => $bs[0]['b_outbound_c_id'],
            'cr.cr_outbound_b_id' => 0, //Not linked as part of a Multi-week Bootcamp
            'cr.cr_status' => 1,
        ));
        foreach($current_inbounds as $c){
            array_push($current_c_ids,$c['cr_inbound_c_id']);
        }

        $has_new = (intval($_POST['level2_c_id'])>0 && !in_array($_POST['level2_c_id'],$current_c_ids));
        if((count($current_c_ids)>0 && $_POST['level2_c_id']==0) || $has_new){
            //We need to remove existing Links:
            foreach($current_inbounds as $cr){
                $this->Db_model->cr_update( $cr['cr_id'] , array(
                    'cr_inbound_u_id' => $udata['u_id'],
                    'cr_timestamp' => date("Y-m-d H:i:s"),
                    'cr_status' => -1, //Archived
                ));
            }
        }

        if($has_new){
            //Create link:
            $this->Db_model->cr_create(array(
                'cr_inbound_u_id' => $udata['u_id'],
                'cr_inbound_c_id'  => $_POST['level2_c_id'],
                'cr_outbound_c_id' => $bs[0]['b_outbound_c_id'],
                'cr_status' => 1,
            ));
        }


        //Bootcamp Edit is the default engagement:
        $engagement_type_id = 18;

        //Did the status change? Log Engagement for this:
        if(!(intval($_POST['b_status'])==intval($bs[0]['b_status']))){
            if(intval($_POST['b_status'])<0){
                //Archived
                $engagement_type_id = 17;
            } elseif(intval($_POST['b_status'])==3) {
                //Public in Marketplace
                $engagement_type_id = 68;
            }
        }

        //Update algolia:
        $this->Db_model->algolia_sync('b',$_POST['b_id']);

        //Log engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'],
            'e_text_value' => ( $engagement_type_id==18 ? readable_updates($bs[0],$b_update,'b_') : null ),
            'e_json' => array(
                'input' => $_POST,
                'before' => $bs[0],
                'after' => $b_update,
            ),
            'e_inbound_c_id' => $engagement_type_id,
            'e_b_id' => intval($_POST['b_id']),
        ));

        //Show success:
        echo_json(array(
            'status' => 1,
            'message' => '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>',
        ));
    }

	/* ******************************
	 * c Intents
	 ****************************** */

	function c_new(){

	    $udata = auth(array(1308,1280));
	    if(!$udata){
	        die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the Page to Continue.</span>');
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Intent ID.</span>');
        } elseif(!isset($_POST['c_outcome']) || strlen($_POST['c_outcome'])<=0){
            die('<span style="color:#FF0000;">Error: Missing Intent Outcome.</span>');
        } elseif(!isset($_POST['link_c_id'])){
            die('<span style="color:#FF0000;">Error: Missing Link Intent ID.</span>');
	    }


        $_POST['link_c_id'] = intval($_POST['link_c_id']);

	    //Validate Original intent:
	    $inbound_intents = $this->Db_model->c_fetch(array(
	        'c.c_id' => intval($_POST['pid']),
	    ));
	    if(count($inbound_intents)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid PID.</span>');
	    }

	    //Validate Bootcamp ID:
	    $bs = $this->Db_model->b_fetch(array(
	        'b.b_id' => intval($_POST['b_id']),
	    ));
	    if(count($bs)<=0){
	        die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID.</span>');
	    }

	    if(!$_POST['link_c_id']){
            //Create intent:
            $new_intent = $this->Db_model->c_create(array(
                'c_inbound_u_id' => $udata['u_id'],
                'c_outcome' => trim($_POST['c_outcome']),
                'c_time_estimate' => ( $_POST['next_level']>=2 ? '0.05' : '0' ), //3 min default Step
            ));

            //Log Engagement for New Intent:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_text_value' => 'Intent ['.$new_intent['c_outcome'].'] created',
                'e_json' => array(
                    'input' => $_POST,
                    'before' => null,
                    'after' => $new_intent,
                ),
                'e_inbound_c_id' => 20, //New Intent
                'e_b_id' => intval($_POST['b_id']),
                'e_outbound_c_id' => $new_intent['c_id'],
            ));

        } else {
            $new_intents = $this->Db_model->c_fetch(array(
                'c_id' => $_POST['link_c_id'],
            ));
            if(count($new_intents)<=0){
                die('<span style="color:#FF0000;">Error: Invalid Linked Intent ID.</span>');
            }
            $new_intent = $new_intents[0];
        }

	    //Create Link:
	    $relation = $this->Db_model->cr_create(array(
	        'cr_inbound_u_id' => $udata['u_id'],
	        'cr_inbound_c_id'  => intval($_POST['pid']),
	        'cr_outbound_c_id' => $new_intent['c_id'],
	        'cr_outbound_rank' => 1 + $this->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
                'cr_status >=' => 1,
                'c_status >=' => 1,
	            'cr_inbound_c_id' => intval($_POST['pid']),
	        )),
	    ));

	    //Log Engagement for New Intent Link:
	    $this->Db_model->e_create(array(
	        'e_inbound_u_id' => $udata['u_id'],
	        'e_text_value' => 'Linked intent ['.$new_intent['c_outcome'].'] as outbound of intent ['.$inbound_intents[0]['c_outcome'].']',
	        'e_json' => array(
	            'input' => $_POST,
	            'before' => null,
	            'after' => $relation,
	        ),
	        'e_inbound_c_id' => 23, //New Intent Link
	        'e_b_id' => intval($_POST['b_id']),
	        'e_cr_id' => $relation['cr_id'],
	    ));

	    $relations = $this->Db_model->cr_outbound_fetch(array(
	        'cr.cr_id' => $relation['cr_id'],
	    ));

	    //Return result:
        echo_json(array(
            'status' => 1,
            'c_id' => $new_intent['c_id'],
            'html' => echo_actionplan($bs[0],$relations[0],$_POST['next_level'],intval($_POST['pid'])),
        ));
	}


	function c_move_c(){

        //Auth user and Load object:
        $udata = auth(array(1308,1280));
        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
        } elseif(!isset($_POST['cr_id']) || intval($_POST['cr_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid cr_id',
            ));
        } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid c_id',
            ));
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid b_id',
            ));
        } elseif(!isset($_POST['from_c_id']) || intval($_POST['from_c_id'])<=0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing from_c_id',
            ));
        } elseif(!isset($_POST['to_c_id']) || intval($_POST['to_c_id'])<=0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing to_c_id',
            ));
        } else {

            //Fetch all three intents to ensure they are all valid and use them for engagement logging:
            $subject = $this->Db_model->c_fetch(array(
                'c.c_id' => intval($_POST['c_id']),
            ));
            $from = $this->Db_model->c_fetch(array(
                'c.c_id' => intval($_POST['from_c_id']),
            ));
            $to = $this->Db_model->c_fetch(array(
                'c.c_id' => intval($_POST['to_c_id']),
            ));

            if(!isset($subject[0]) || !isset($from[0]) || !isset($to[0])){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid intent IDs',
                ));
            } else {
                //Make the move:
                $this->Db_model->cr_update( intval($_POST['cr_id']) , array(
                    'cr_inbound_u_id' => $udata['u_id'],
                    'cr_timestamp' => date("Y-m-d H:i:s"),
                    'cr_inbound_c_id' => intval($_POST['to_c_id']),
                    //No need to update sorting here as a separate JS function would call that within half a second after the move...
                ));

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $udata['u_id'],
                    'e_json' => array(
                        'post' => $_POST,
                    ),
                    'e_text_value' => '['.$subject[0]['c_outcome'].'] was migrated from ['.$from[0]['c_outcome'].'] to ['.$to[0]['c_outcome'].']', //Message migrated
                    'e_inbound_c_id' => 50, //Intent migrated
                    'e_outbound_c_id' => intval($_POST['c_id']),
                    'e_cr_id' => intval($_POST['cr_id']),
                    'e_b_id' => intval($_POST['b_id']),
                ));

                //Return success
                echo_json(array(
                    'status' => 1,
                    'message' => 'Move completed',
                ));
            }
        }
    }

    function c_save_settings(){

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));

        //Validate Bootcamp ID:
        $bs = $this->Db_model->b_fetch(array(
            'b.b_id' => intval($_POST['b_id']),
        ));

        //Validate Original intent:
        $original_intents = $this->Db_model->c_fetch(array(
            'c.c_id' => intval($_POST['pid']),
        ) , 0 );


        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
            return false;
        } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
            return false;
        } elseif(!isset($_POST['level']) || intval($_POST['level'])<0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing level',
            ));
            return false;
        } elseif(!isset($_POST['c_outcome']) || strlen($_POST['c_outcome'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Outcome',
            ));
            return false;
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
            return false;
        } elseif($_POST['level']>=2 && !isset($_POST['c_status'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Status',
            ));
            return false;
        } elseif($_POST['level']==2 && !isset($_POST['c_completion_rule'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Extension Rule',
            ));
            return false;
        } elseif($_POST['level']>=2 && !isset($_POST['c_time_estimate'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Time Estimate',
            ));
            return false;
        } elseif($_POST['level']>=2 && (!isset($_POST['c_complete_url_required']) || !isset($_POST['c_complete_notes_required']))){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Completion Settings',
            ));
            return false;
        } elseif(count($bs)<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp ID',
            ));
            return false;
        } elseif(count($original_intents)<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Invalid PID',
            ));
            return false;
        }


        //Process data based on level:
        if($_POST['level']<=1){

            //Did the Bootcamp's Intent Outcome change?
            if(!(trim($_POST['c_outcome'])==$original_intents[0]['c_outcome'])){
                //Generate Update Array
                $c_update = array(
                    'c_outcome' => trim($_POST['c_outcome']),
                );
            }

        } elseif($_POST['level']>=2){

            //For level 2 & 3
            $c_update = array(
                'c_outcome' => trim($_POST['c_outcome']),
                'c_status' => intval($_POST['c_status']),
                'c_time_estimate' => doubleval($_POST['c_time_estimate']),
                'c_complete_url_required' => ( intval($_POST['c_complete_url_required']) ? 't' : 'f' ),
                'c_complete_notes_required' => ( intval($_POST['c_complete_notes_required']) ? 't' : 'f' ),
            );

            if($_POST['level']==2){
                $c_update['c_completion_rule'] = intval($_POST['c_completion_rule']);
            }
        }



        //Did we have any intent updating to do?
        if(isset($c_update) && count($c_update)>0){

            //Now update the DB:
            $this->Db_model->c_update( intval($_POST['pid']) , $c_update );

            if($_POST['pid']==$bs[0]['b_outbound_c_id']){
                //This is a Bootcamp intent, also update algolia:
                $this->Db_model->algolia_sync('b',$_POST['b_id']);
            } else {
                //Update intent algolia object:

            }

            //Log Engagement for New Intent Link:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_text_value' => readable_updates($original_intents[0],$c_update,'c_'),
                'e_json' => array(
                    'input' => $_POST,
                    'before' => $original_intents[0],
                    'after' => $c_update,
                ),
                'e_inbound_c_id' => ( $_POST['level']>=2 && isset($c_update['c_status']) && $c_update['c_status']<0 ? 21 : 19 ), //Intent Deleted OR Updated
                'e_b_id' => intval($_POST['b_id']),
                'e_outbound_c_id' => intval($_POST['pid']),
            ));

        }

        //Show success:
        echo_json(array(
            'status' => 1,
            'message' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));

    }

    function c_sort(){
	    //Auth user and Load object:
	    $udata = auth(array(1308,1280));
	    if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Login again to Continue.',
            ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid PID',
            ));
	    } elseif(!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Nothing passed for sorting',
            ));
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0) {
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
        } else {

            //Validate Parent intent:
            $inbound_intents = $this->Db_model->c_fetch(array(
                'c.c_id' => intval($_POST['pid']),
            ));
            if(count($inbound_intents)<=0){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid PID',
                ));
            } else {

                //Fetch for the record:
                $outbounds_before = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_inbound_c_id' => intval($_POST['pid']),
                    'cr.cr_status >=' => 0,
                ));

                //Update them all:
                foreach($_POST['new_sort'] as $rank=>$cr_id){
                    $this->Db_model->cr_update( intval($cr_id) , array(
                        'cr_inbound_u_id' => $udata['u_id'],
                        'cr_timestamp' => date("Y-m-d H:i:s"),
                        'cr_outbound_rank' => intval($rank), //Might have decimal for DRAFTING Tasks/Steps
                    ));
                }

                //Fetch for the record:
                $outbounds_after = $this->Db_model->cr_outbound_fetch(array(
                    'cr.cr_inbound_c_id' => intval($_POST['pid']),
                    'cr.cr_status >=' => 0,
                ));

                //Log Engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $udata['u_id'],
                    'e_text_value' => 'Sorted outbound intents for ['.$inbound_intents[0]['c_outcome'].']',
                    'e_json' => array(
                        'input' => $_POST,
                        'before' => $outbounds_before,
                        'after' => $outbounds_after,
                    ),
                    'e_inbound_c_id' => 22, //Links Sorted
                    'e_b_id' => intval($_POST['b_id']),
                    'e_outbound_c_id' => intval($_POST['pid']),
                ));

                //Display message:
                echo_json(array(
                    'status' => 1,
                    'message' => '<i class="fas fa-check"></i> Sorted',
                ));
            }
        }
	}

    function c_tree_menu(){

        if(!isset($_POST['c_id']) || !isset($_POST['hash_key']) || !($_POST['hash_key']==md5($_POST['c_id'].'menu89Hash'))){
            die('<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
        }

    }

    function c_import_ui(){

        if(!isset($_POST['import_from_b_id']) || intval($_POST['import_from_b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp source.',
            ));
            exit;
        }

        $udata = auth(array(1308,1280),0,$_POST['import_from_b_id']);
        if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session expired. Login to try again.',
            ));
            exit;
        }

        $bs = $this->Db_model->remix_bs(array(
            'b_id' => $_POST['import_from_b_id'],
        ));
        if(!(count($bs)==1)){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp source.',
            ));
            exit;
        }

        $import_items = array(
            //Action Plan stuff:
            array(
                'name' => '<i class="fas fa-comment-dots"></i> Import Bootcamp-Level Messages',
                'id' => 'b_level_messages',
                'count' => count($bs[0]['c__messages']),
            ),
            array(
                'name' => '<i class="fas fa-shield-check"></i> Override Prerequisites',
                'id' => 'b_prerequisites',
                'count' => ( strlen($bs[0]['b_prerequisites'])>0 ? count(json_decode($bs[0]['b_prerequisites'])) : 0 ),
            ),
        );


        array_push($import_items,array(
            'name' => '<i class="fas fa-check-square"></i> Tasks in Action Plan',
            'id' => 'b_outbound_c_ids',
            'value' => $bs[0]['b_outbound_c_id'],
            'count' => count($bs[0]['c__child_intents']),
        ));


        //Add Outcome section:
        array_push($import_items,array(
            'name' => '<i class="fas fa-trophy"></i> Override Skills',
            'id' => 'b_transformations',
            'count' => ( strlen($bs[0]['b_transformations'])>0 ? count(json_decode($bs[0]['b_transformations'])) : 0 ),
        ));



        //Generate UI:
        $ui = '<p>Choose what to import:</p>'; //Start generating this...
        foreach($import_items as $item){
            $ui .= '<div class="form-group label-floating is-empty"><div class="checkbox"><label><input type="checkbox" class="import_checkbox" name="'.$item['id'].'" '.( isset($item['value']) ? 'value="'.$item['value'].'"' : '' ).' '.( $item['count']==0 ? 'disabled':'').' /> '.$item['count'].'x &nbsp;'.$item['name'].'</label></div></div>';
        }

        echo_json(array(
            'status' => 1,
            'message' => null, //Not used, would load ui instead
            'ui' => $ui,
        ));
    }

    function c_import_process(){

        if(!isset($_POST['import_from_b_id']) || intval($_POST['import_from_b_id'])<=0 || !isset($_POST['import_to_b_id']) || intval($_POST['import_to_b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp source.',
            ));
            exit;
        } elseif(!isset($_POST['task_import_mode']) || intval($_POST['task_import_mode'])<1 || intval($_POST['task_import_mode'])>3){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Task Import Mode',
            ));
            exit;
        }

        $udata = auth(array(1308,1280),0,$_POST['import_from_b_id']);
        $udata2 = auth(array(1308,1280),0,$_POST['import_to_b_id']);
        if(!$udata || !$udata2){
            echo_json(array(
                'status' => 0,
                'message' => 'Session expired. Login to try again.',
            ));
            exit;
        }

        $bs_from = $this->Db_model->remix_bs(array(
            'b_id' => $_POST['import_from_b_id'],
        ));
        $bs_to = $this->Db_model->b_fetch(array(
            'b_id' => $_POST['import_to_b_id'],
        ));
        if(!(count($bs_from)==1)){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp from.',
            ));
            exit;
        } elseif(!(count($bs_to)==1)){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp to.',
            ));
            exit;
        }


        //We're good at this point
        //Start copying potential lists first:
        $lists = array(
            'b_prerequisites',
            'b_transformations',
        );
        $b_lists = array();
        foreach($lists as $list){
            //Do we need this list?
            if(isset($_POST[$list]) && intval($_POST[$list])){
                //Yes, copy this guy:
                $b_lists[$list] = $bs_from[0][$list];
            }
        }
        if(count($b_lists)>0){
            //Copy to new Action Plan:
            $this->Db_model->b_update( $_POST['import_to_b_id'] , $b_lists);
        }



        //Do we need to copy the Bootcamp-level message?
        $b_level_messages_results = array();
        if(isset($_POST['b_level_messages']) && intval($_POST['b_level_messages']) && count($bs_from[0]['c__messages'])>0){
            //Yes, import messages:
            $b_level_messages_results = $this->Db_model->i_replicate($udata['u_id'],$bs_from[0]['c__messages'],$bs_to[0]['b_outbound_c_id']);
        }


        //Do we need to do any Tasks?
        if(isset($_POST['b_outbound_c_ids']) && count($_POST['b_outbound_c_ids'])>0){
            foreach($_POST['b_outbound_c_ids'] as $c_id){

                //Fetch all child Intents for this Intent:
                $intents = $this->Db_model->c_fetch(array(
                    'c.c_id' => $c_id,
                ), 1, array('i')); //Supports up to 2 levels deep for now...

                if(count($intents)>0 && count($intents[0]['c__child_intents'])>0){
                    foreach($intents[0]['c__child_intents'] as $c){
                        if(intval($_POST['task_import_mode'])==3){

                            //Copy intent:
                            $new_task = $this->Db_model->c_replicate($udata['u_id'],$c,$bs_to[0]['b_outbound_c_id']);

                            if(count($c['c__messages'])>0){
                                //Copy messages:
                                $new_task['c__messages'] = $this->Db_model->i_replicate($udata['u_id'], $c['c__messages'], $new_task['c_id']);
                            }
                        }
                    }
                }
            }
        }


        //Log Engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'],
            'e_json' => array(
                'import_settings' => $_POST,
                'b_lists' => $b_lists,
                'b_level_messages_results' => $b_level_messages_results,
            ),
            'e_inbound_c_id' => 75, //Action Plan Imported
            'e_b_id' => $bs_to[0]['b_id'],
        ));


        //Show message & redirect:
        echo_json(array(
            'status' => 1, //Success
            'message' => '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span><div>Reloading Action Plan...</div>',
        ));
    }

    function c_tip(){
        $udata = auth(array(1308,1280));
        //Used to load all the help messages within the Console:
        if(!$udata || !isset($_POST['intent_id']) || intval($_POST['intent_id'])<1){
            echo_json(array(
                'success' => 0,
            ));
        }

        //Fetch Messages and the User's Got It Engagement History:
        $messages = $this->Db_model->i_fetch(array(
            'i_outbound_c_id' => intval($_POST['intent_id']),
            'i_status >' => 0, //Published in any form
        ));

        //Log an engagement for all messages
        foreach($messages as $i){
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_json' => $i,
                'e_inbound_c_id' => 40, //Got It
                'e_outbound_c_id' => intval($_POST['intent_id']),
                'e_i_id' => $i['i_id'],
            ));
        }

        //Build UI friendly Message:
        $help_content = null;
        foreach($messages as $i){
            $help_content .= echo_i(array_merge($i,array('e_outbound_u_id'=>$udata['u_id'])),$udata['u_full_name']);
        }

        //Return results:
        echo_json(array(
            'success' => ( $help_content ?  1 : 0 ), //No Messages perhaps!
            'intent_id' => intval($_POST['intent_id']),
            'help_content' => $help_content,
        ));
    }

	/* ******************************
	 * i Messages
	 ****************************** */

    function i_load_frame(){
        $udata = auth();
        if(!$udata){
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Login again to continue.</span>');
        } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            die('<span style="color:#FF0000;">Error: Invalid Bootcamp ID</span>');
        } elseif(!isset($_POST['c_id']) || intval($_POST['c_id'])<=0){
            die('<span style="color:#FF0000;">Error: Invalid Intent id.</span>');
        } elseif(!isset($_POST['level']) || intval($_POST['level'])<=0){
            die('<span style="color:#FF0000;">Error: invalid level ID.</span>');
        } else {
            //Load the phone:
            $this->load->view('console/b/frame_messages' , $_POST);
        }
    }

    function i_test(){

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));

        if(!$udata){

            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
            return false;

        } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0) {

            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
            return false;

        } elseif(!isset($_POST['depth']) || intval($_POST['depth'])<0) {

            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Depth',
            ));
            return false;

        } elseif(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0) {

            echo_json(array(
                'status' => 0,
                'message' => 'Missing User ID',
            ));
            return false;

        } else {

            //All seems good, attempt dispatch:
            echo_json($this->Comm_model->foundation_message(array(
                'e_outbound_u_id' => intval($_POST['u_id']),
                'e_outbound_c_id' => intval($_POST['pid']),
                'depth' => intval($_POST['depth']),
                'e_b_id' => 0,
                'e_r_id' => 0,
            )));

        }

    }

    function i_attach(){

	    $udata = auth(array(1308,1280));
	    $file_limit_mb = $this->config->item('file_limit_mb');
	    if(!$udata){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Session. Refresh to Continue',
	        ));
            exit;
	    } elseif(!isset($_POST['pid']) || !isset($_POST['b_id']) || !isset($_POST['i_status'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing intent data.',
	        ));
            exit;
	    } elseif(!isset($_POST['upload_type']) || !in_array($_POST['upload_type'],array('file','drop'))){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Unknown upload type.',
	        ));
            exit;
	    } elseif(!isset($_FILES[$_POST['upload_type']]['tmp_name']) || strlen($_FILES[$_POST['upload_type']]['tmp_name'])==0 || intval($_FILES[$_POST['upload_type']]['size'])==0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Unable to save file. Max file size allowed is '.$file_limit_mb.' MB.',
	        ));
            exit;
	    } elseif($_FILES[$_POST['upload_type']]['size']>($file_limit_mb*1024*1024)){

	        echo_json(array(
	            'status' => 0,
	            'message' => 'File is larger than '.$file_limit_mb.' MB.',
	        ));
            exit;

	    }

        //Fetch Bootcamp:
        $bs = $this->Db_model->b_fetch(array(
            'b.b_id' => $_POST['b_id'],
        ));
        if(count($bs)<1){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Bootcamp ID.',
            ));
            return false;
        }

        //Attempt to save file locally:
        $file_parts = explode('.',$_FILES[$_POST['upload_type']]["name"]);
        $temp_local = "application/cache/temp_files/".md5($file_parts[0]).'.'.$file_parts[(count($file_parts)-1)];
        move_uploaded_file( $_FILES[$_POST['upload_type']]['tmp_name'] , $temp_local );


        //Attempt to store in Cloud:
        if(isset($_FILES[$_POST['upload_type']]['type']) && strlen($_FILES[$_POST['upload_type']]['type'])>0){
            $mime = $_FILES[$_POST['upload_type']]['type'];
        } else {
            $mime = mime_content_type($temp_local);
        }

        //Upload to S3:
        $new_file_url = trim(save_file( $temp_local , $_FILES[$_POST['upload_type']] , true ));

        //What happened?
        if(!$new_file_url){
            //Oops something went wrong:
            echo_json(array(
                'status' => 0,
                'message' => 'Could not save to cloud!',
            ));
            exit;
        }

        //Detect file type:
        $i_media_type = mime_type($mime);

        //Create Message:
        $message = '/attach '.$i_media_type.':'.$new_file_url;

        //Create message:
        $i = $this->Db_model->i_create(array(
            'i_inbound_u_id' => $udata['u_id'],
            'i_outbound_c_id' => intval($_POST['pid']),
            'i_media_type' => $i_media_type,
            'i_message' => $message,
            'i_url' => $new_file_url,
            'i_status' => $_POST['i_status'],
            'i_rank' => 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
                'i_status' => $_POST['i_status'],
                'i_outbound_c_id' => $_POST['pid'],
            )),
        ));

        //Fetch full message:
        $new_messages = $this->Db_model->i_fetch(array(
            'i_id' => $i['i_id'],
        ));

        //Log engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'],
            'e_json' => array(
                'post' => $_POST,
                'file' => $_FILES,
                'after' => $new_messages[0],
            ),
            'e_inbound_c_id' => 34, //Message added e_inbound_c_id=34
            'e_i_id' => intval($new_messages[0]['i_id']),
            'e_outbound_c_id' => intval($new_messages[0]['i_outbound_c_id']),
            'e_b_id' => $bs[0]['b_id'],
        ));


        //Does it have an attachment and a connected Facebook Page? If so, save the attachment:
        if($bs[0]['b_fp_id']>0 && in_array($i_media_type,array('image','audio','video','file'))){
            //Log engagement for this to be done via a Cron Job:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_inbound_c_id' => 83, //Message Facebook Sync e_inbound_c_id=83
                'e_i_id' => intval($new_messages[0]['i_id']),
                'e_outbound_c_id' => intval($new_messages[0]['i_outbound_c_id']),
                'e_b_id' => $bs[0]['b_id'],
                'e_fp_id' => $bs[0]['b_fp_id'],
                'e_status' => 0, //Job pending
            ));
        }


        //Echo message:
        echo_json(array(
            'status' => 1,
            'message' => echo_message( array_merge($new_messages[0], array(
                'e_b_id'=>$bs[0]['b_id'],
                'e_outbound_u_id'=>$udata['u_id'],
            )), $_POST['level']),
        ));
	}

	function i_create(){

	    $udata = auth(array(1308,1280));
	    if(!$udata){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Session Expired. Login and Try again.',
	        ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Step',
	        ));
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Bootcamp',
	        ));
	    } else {

            $bs = $this->Db_model->b_fetch(array(
                'b.b_id' => $_POST['b_id'],
            ));
            if(count($bs)<1){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Bootcamp ID.',
                ));
                return false;
            }

	        //Make sure message is all good:
            $validation = message_validation($_POST['i_status'],$_POST['i_message']);

            if(!$validation['status']){

                //There was some sort of an error:
                echo_json($validation);

            } else {

                //Detect file type:
                if(count($validation['urls'])==1 && trim($validation['urls'][0])==trim($_POST['i_message'])){

                    //This message is a URL only, perform raw URL to file conversion
                    //This feature only available for newly created message, NOT in editing mode!
                    $mime = remote_mime($validation['urls'][0]);
                    $i_media_type = mime_type($mime);
                    if($i_media_type=='file'){
                        $i_media_type = 'text';
                    }

                } else {
                    //This channel is all text:
                    $i_media_type = 'text'; //Possible: text,image,video,audio,file
                }

                //Create Message:
                $i = $this->Db_model->i_create(array(
                    'i_inbound_u_id' => $udata['u_id'],
                    'i_outbound_c_id' => intval($_POST['pid']),
                    'i_media_type' => $i_media_type,
                    'i_message' => trim($_POST['i_message']),
                    'i_url' => ( count($validation['urls'])==1 ? $validation['urls'][0] : null ),
                    'i_status' => $_POST['i_status'],
                    'i_rank' => 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
                        'i_status' => $_POST['i_status'],
                        'i_outbound_c_id' => intval($_POST['pid']),
                    )),
                ));

                //Fetch full message:
                $new_messages = $this->Db_model->i_fetch(array(
                    'i_id' => $i['i_id'],
                ), 1, array('x'));

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $udata['u_id'],
                    'e_json' => array(
                        'input' => $_POST,
                        'after' => $new_messages[0],
                    ),
                    'e_inbound_c_id' => 34, //Message added
                    'e_i_id' => intval($new_messages[0]['i_id']),
                    'e_outbound_c_id' => intval($_POST['pid']),
                    'e_b_id' => $bs[0]['b_id'],
                ));

                //Print the challenge:
                echo_json(array(
                    'status' => 1,
                    'message' => echo_message(array_merge($new_messages[0],array(
                        'e_b_id'=>intval($_POST['b_id']),
                        'e_outbound_u_id'=>$udata['u_id'],
                    )), $_POST['level']),
                ));
            }
	    }
	}

	function i_modify(){

	    //Auth user and Load object:
	    $udata = auth(array(1308,1280));
	    if(!$udata){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Session. Refresh.',
	        ));
	    } elseif(!isset($_POST['i_media_type'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Type',
	        ));
	    } elseif(!isset($_POST['i_id']) || intval($_POST['i_id'])<=0){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Missing Message ID',
	        ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
	        echo_json(array(
	            'status' => 0,
	            'message' => 'Invalid Intent ID',
	        ));
	    } else {

            //Fetch Message:
            $messages = $this->Db_model->i_fetch(array(
                'i_id' => intval($_POST['i_id']),
                'i_status >' => 0,
            ));

            //Make sure message is all good:
            $validation = message_validation($_POST['i_status'],( isset($_POST['i_message']) ? $_POST['i_message'] : null ),$_POST['i_media_type']);

            if(!isset($messages[0])){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Message Not Found',
                ));
            } elseif(!$validation['status']){

                //There was some sort of an error:
                echo_json($validation);

            } else {

                //All good, lets move on:
                //Define what needs to be updated:
                $to_update = array(
                    'i_inbound_u_id' => $udata['u_id'],
                    'i_timestamp' => date("Y-m-d H:i:s"),
                );

                //Is this a text message?
                if($_POST['i_media_type']=='text'){
                    $to_update['i_message'] = trim($_POST['i_message']);
                    $to_update['i_url'] = ( isset($validation['urls'][0]) ? $validation['urls'][0] : null );
                }

                if(!($_POST['initial_i_status']==$_POST['i_status'])){
                    //Change the status:
                    $to_update['i_status'] = $_POST['i_status'];
                    //Put it at the end of the new list:
                    $to_update['i_rank'] = 1 + $this->Db_model->max_value('v5_messages','i_rank', array(
                        'i_status' => $_POST['i_status'],
                        'i_outbound_c_id' => intval($_POST['pid']),
                    ));
                }

                //Now update the DB:
                $this->Db_model->i_update( intval($_POST['i_id']) , $to_update );

                //Re-fetch the message for display purposes:
                $new_messages = $this->Db_model->i_fetch(array(
                    'i_id' => intval($_POST['i_id']),
                ), 0, array('x'));

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $udata['u_id'],
                    'e_json' => array(
                        'input' => $_POST,
                        'before' => $messages[0],
                        'after' => $new_messages[0],
                    ),
                    'e_inbound_c_id' => 36, //Message edited
                    'e_i_id' => $messages[0]['i_id'],
                    'e_outbound_c_id' => intval($_POST['pid']),
                ));

                //Print the challenge:
                echo_json(array(
                    'status' => 1,
                    'message' => echo_i(array_merge($new_messages[0],array('e_outbound_u_id'=>$udata['u_id'])),$udata['u_full_name']),
                    'new_status' => echo_status('i',$new_messages[0]['i_status'],1,'right'),
                    'success_icon' => '<span><i class="fas fa-check"></i> Saved</span>',
                    'new_uploader' => echo_cover($new_messages[0],null,true, 'data-toggle="tooltip" title="Last modified by '.$new_messages[0]['u_full_name'].' about '.echo_diff_time($new_messages[0]['i_timestamp']).' ago" data-placement="right"'), //If there is a person change...
                ));
            }
	    }
	}

	function i_delete(){
	    //Auth user and Load object:
	    $udata = auth(array(1308,1280));

	    if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));
	    } elseif(!isset($_POST['i_id']) || intval($_POST['i_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Message ID',
            ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
	    } else {

            //Fetch Message:
            $messages = $this->Db_model->i_fetch(array(
                'i_id' => intval($_POST['i_id']),
                'i_status >' => 0, //Not deleted
            ));
            if(!isset($messages[0])){
                echo_json(array(
                    'status' => 0,
                    'message' => 'Message Not Found',
                ));
            } else {
                //Now update the DB:
                $this->Db_model->i_update( intval($_POST['i_id']) , array(
                    'i_inbound_u_id' => $udata['u_id'],
                    'i_timestamp' => date("Y-m-d H:i:s"),
                    'i_status' => -1, //Deleted by coach
                ));

                //Log engagement:
                $this->Db_model->e_create(array(
                    'e_inbound_u_id' => $udata['u_id'],
                    'e_json' => array(
                        'input' => $_POST,
                        'before' => $messages[0],
                    ),
                    'e_inbound_c_id' => 35, //Message deleted
                    'e_i_id' => intval($messages[0]['i_id']),
                    'e_outbound_c_id' => intval($_POST['pid']),
                ));

                echo_json(array(
                    'status' => 1,
                    'message' => '<span style="color:#3C4858;"><i class="fas fa-trash-alt"></i> Deleted</span>',
                ));
            }
        }
	}

	function i_sort(){
	    //Auth user and Load object:
	    $udata = auth(array(1308,1280));
	    if(!$udata){
            echo_json(array(
                'status' => 0,
                'message' => 'Session Expired. Login and try again',
            ));
	    } elseif(!isset($_POST['new_sort']) || !is_array($_POST['new_sort']) || count($_POST['new_sort'])<=0){
            echo_json(array(
                'status' => 1, //Do not treat this as error as it could happen in moving Messages between types
                'message' => 'There was nothing to sort',
            ));
	    } elseif(!isset($_POST['b_id']) || intval($_POST['b_id'])<=0){
            echo_json(array(
                'status' => 0,
                'message' => 'Missing Bootcamp ID',
            ));
	    } elseif(!isset($_POST['pid']) || intval($_POST['pid'])<=0 || !is_valid_intent($_POST['pid'])){
            echo_json(array(
                'status' => 0,
                'message' => 'Invalid Intent ID',
            ));
	    } else {

            //Update them all:
            $sort_count = 0;
            foreach($_POST['new_sort'] as $i_rank=>$i_id){
                if(intval($i_id)>0){
                    $sort_count++;
                    $this->Db_model->i_update( $i_id , array(
                        'i_rank' => intval($i_rank),
                    ));
                }
            }

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_json' => $_POST,
                'e_inbound_c_id' => 39, //Messages sorted
                'e_outbound_c_id' => intval($_POST['pid']),
                'e_b_id' => intval($_POST['b_id']),
            ));

            echo_json(array(
                'status' => 1,
                'message' => $sort_count.' Sorted', //Does not matter as its currently not displayed in UI
            ));
        }
	}

    function i_dispatch(){
        //Dispatch Messages:
        $results = $this->Comm_model->foundation_message(array(
            'e_outbound_u_id' => $_POST['u_id'],
            'e_outbound_c_id' => $_POST['c_id'],
            'depth' => $_POST['depth'],
            'e_b_id' => $_POST['b_id'],
            'e_r_id' => 0,
        ));

        if($results['status']){
            echo '<i class="fas fa-comment-alt-check" style="color:#3C4858;" title="SUCCESS: '.$results['message'].'"></i>';
        } else {
            echo '<i class="fas fa-comment-alt-times" style="color:#FF0000;" title="ERROR: '.$results['message'].'"></i>';
        }
    }

}
