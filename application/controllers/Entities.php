<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entities extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function ping()
    {
        echo_json(array('status' => 'success'));
    }


    function new_student_api(){

        //And API call that get's POST variables for email and name and creates a new user

        //Check variables to make sure it's set
        if(!isset($_POST['secret']) || !($_POST['secret']=='9d8f710315f4136bf6060b339cedddd1')){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid secret',
            ));
        } elseif(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid email',
            ));
        } elseif(!isset($_POST['name']) || strlen($_POST['name'])<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid name',
            ));
        }

        $_POST['email'] = trim(strtolower($_POST['email']));

        //Validate parent entity:
        $current_us = $this->Db_model->u_fetch(array(
            'u_email' => $_POST['email'],
        ));
        if(count($current_us)>0){
            return echo_json(array(
                'status' => 0,
                'message' => 'Email already exists',
            ));
        }


        //We should add a new entity:
        $new_u = $this->Db_model->u_create(array(
            'u_email' 		    => $_POST['email'],
            'u_status' 		    => 1,
            'u_full_name' 		=> trim($_POST['name']),
            'u_inbound_u_id'    => 1304, //Interested student
        ));


        //Log engagement
        $this->Db_model->e_create(array(
            'e_text_value' => 'New student entity created using new_student_api() api',
            'e_inbound_c_id' => 6971, //New Entity
            'e_inbound_u_id'  => 0, //System/API
            'e_outbound_u_id' => $new_u['u_id'],
        ));

        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
            'new_user' => $new_u,
        ));
    }


    //Lists entities
    function entity_browse($inbound_u_id=0){

        //Authenticate contributor, redirect if not:
        //$udata = auth(array(1308,1280),1);

        $udata = $this->session->userdata('user');
        if(!($udata['u_id']==$inbound_u_id || $udata['u_inbound_u_id']==1281)){
            //This is not an admin, so they cannot edit this:
            redirect_message(( isset($udata['u_id']) && $udata['u_id']>0 ? '/console' : '/entities/'.$inbound_u_id ),'<div class="alert alert-danger" role="alert">You can only view <a href="/entities/'.$udata['u_id'].'">your own entity</a>.</div>');
        }

        $entities_per_page = 100;
        $entity_tree = fetch_entity_tree($inbound_u_id);

        //Adjust Breadcrumb for non-admins
        if(!($udata['u_inbound_u_id']==1281)){
            $entity_tree['breadcrumb'] = array(
                array(
                    'link' => null,
                    'anchor' => 'My Account',
                ),
            );
        }

        //Fetch core data:
        $view_data = array_merge( $entity_tree , array(
            'udata' => $udata,
            'entities_per_page' => $entities_per_page,
            'child_entities' => $this->Db_model->u_fetch(array(
                'u_inbound_u_id' => $inbound_u_id,
                'u_status' => 1, //Only active
            ), array('count_child'), $entities_per_page),
        ));

        //Load views
        $this->load->view('console/console_header' , $view_data);
        $this->load->view('console/u/entity_browse' , $view_data);
        $this->load->view('console/console_footer');
    }

    function entity_load_more($inbound_u_id,$limit,$page){

        //Fetch entitie itself:
        $parent_entities = $this->Db_model->u_fetch(array(
            'u_id' => $inbound_u_id,
        ), array('count_child'));

        $child_entities = $this->Db_model->u_fetch(array(
            'u_inbound_u_id' => $inbound_u_id,
            'u_status' => 1, //Only active
        ), array('count_child'), $limit, ($page*$limit));

        foreach($child_entities as $u){
            echo echo_u($u);
        }

        //Do we need another load more button?
        if($parent_entities[0]['u__outbound_count']>(($page*$limit) + count($child_entities))){
            echo_next_u(($page+1), $limit, $parent_entities[0]['u__outbound_count']);
        }

    }


    //Edit entities
    function entity_edit($u_id){

        //Authenticate user:
        $udata = $this->session->userdata('user');
        if(!($udata['u_id']==$u_id || $udata['u_inbound_u_id']==1281)){
            //This is not an admin, so they cannot edit this:
            redirect_message('/entities/'.$u_id,'<div class="alert alert-danger" role="alert">You can only edit <a href="/entities/'.$udata['u_id'].'">your own entity</a>.</div>');
        }

        $entity_tree = fetch_entity_tree($u_id,true);

        //Adjust Breadcrumb for non-admins
        if(!($udata['u_inbound_u_id']==1281)){
            $entity_tree['breadcrumb'] = array(
                array(
                    'link' => '/entities/'.$u_id,
                    'anchor' => 'My Account',
                ),
                array(
                    'link' => null,
                    'anchor' => '<i class="fas fa-cog"></i> Modify',
                ),
            );
        }

        //Fetch core data:
        $view_data = array_merge( $entity_tree , array(
            'udata' => $udata,
        ));

        //This lists all users based on the permissions of the user
        $this->load->view('console/console_header', $view_data);
        $this->load->view('console/u/entity_edit', $view_data);
        $this->load->view('console/console_footer');
    }


    function link_entities(){
        //Responsible to link inbound/outbound entities to each other via a JS function on entity_browse.php


        //Auth user and check required variables:
        $udata = auth(array(1308,1280));

        if(!$udata){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif(!isset($_POST['u_id']) || intval($_POST['u_id'])<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Entity',
            ));
        } elseif(!isset($_POST['new_u_id']) || !isset($_POST['new_u_full_name']) || (intval($_POST['new_u_id'])<1 && strlen($_POST['new_u_full_name'])<1)){
            return echo_json(array(
                'status' => 0,
                'message' => 'Either New Entity ID or Name is required',
            ));
        }

        //Validate parent entity:
        $current_us = $this->Db_model->u_fetch(array(
            'u_id' => $_POST['u_id'],
        ));
        if(count($current_us)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid current entity ID',
            ));
        }


        //Do we need to add a new entity?
        if(intval($_POST['new_u_id'])>1){

            //We already have an entity that we just want to link:
            $new_us = $this->Db_model->u_fetch(array(
                'u_id' => $_POST['new_u_id'],
            ));

            if(count($new_us)<1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid new linked entity ID',
                ));
            } else {
                $new_u = $new_us[0];
            }

        } else {

            //We should add a new entity:
            $new_u = $this->Db_model->u_create(array(
                'u_full_name' 		=> trim($_POST['new_u_full_name']),
                'u_inbound_u_id'    => 1282, //Authors
            ));

        }

        //Link to new OR existing entity:
        $new_link = $this->Db_model->e_create(array(
            'e_inbound_c_id' => 6966, //Link
            'e_inbound_u_id'  => $current_us[0]['u_id'],
            'e_outbound_u_id' => $new_u['u_id'],
        ));


        //Create a link to track this new creation:
        $this->Db_model->e_create(array(
            'e_inbound_c_id' => 6978, //Link Added
            'e_inbound_u_id'  => $udata['u_id'], //The person who added this link
            'e_e_id' => $new_link['e_id'],
        ));


        //Return newly added/linked entity:
        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
            'new_u' => echo_u($new_u),
        ));
    }


    function entity_save_edit(){

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));
        $countries_all = $this->config->item('countries_all');
        $timezones = $this->config->item('timezones');
        $message_max = $this->config->item('message_max');

        //Fetch current data:
        $u_current = $this->Db_model->u_fetch(array(
            'u_id' => intval($_POST['u_id']),
        ));

        if(!$udata){
            die('<span style="color:#FF0000;">Error: Invalid Session. Refresh the page and try again.</span>');
        } elseif(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0 || !(count($u_current)==1)){
            die('<span style="color:#FF0000;">Error: Invalid ID. Try again.</span>');
        } elseif(!isset($_POST['u_full_name']) || strlen($_POST['u_full_name'])<=0){
            die('<span style="color:#FF0000;">Error: Missing First Name. Try again.</span>');
        } elseif(strlen($_POST['u_email'])>0 && !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){
            die('<span style="color:#FF0000;">Error: Email ['.$_POST['u_email'].'] is invalid. Try again.</span>');
        } elseif(filter_var($u_current[0]['u_email'], FILTER_VALIDATE_EMAIL) && strlen($_POST['u_email'])==0){
            die('<span style="color:#FF0000;">Error: Initial email was ['.$u_current[0]['u_email'].']. Email required once set. Try again.</span>');
        } elseif(strlen($_POST['u_paypal_email'])>0 && !filter_var($_POST['u_paypal_email'], FILTER_VALIDATE_EMAIL)){
            die('<span style="color:#FF0000;">Error: Invalid Paypal Email. Try again.</span>');
        } elseif(strlen($_POST['u_bio'])>$message_max){
            die('<span style="color:#FF0000;">Error: Introductory Message should be less than '.$message_max.' characters. Try again.</span>');
        }

        //Adjust email:
        $_POST['u_email'] = strtolower($_POST['u_email']);
        $_POST['u_paypal_email'] = strtolower($_POST['u_paypal_email']);


        //Make sure email is unique:
        if(strlen($_POST['u_email'])>0){
            $dup_email = $this->Db_model->u_fetch(array(
                'u_id !=' => $_POST['u_id'],
                'u_email' => strtolower($_POST['u_email']),
            ));
            if(count($dup_email)>0){
                die('<span style="color:#FF0000;">Error: Email ['.$_POST['u_email'].'] is already assigned to another user.</span>');
            }
        }


        if(!isset($_POST['u_language'])){
            $_POST['u_language'] = array();
        }

        $u_update = array(
            //Email updates:
            'u_email' => ( isset($_POST['u_email']) && strlen($_POST['u_email'])>0 ? trim(strtolower($_POST['u_email'])) : null ),
            'u_paypal_email' => ( isset($_POST['u_paypal_email']) && strlen($_POST['u_paypal_email'])>0 ? trim(strtolower($_POST['u_paypal_email'])) : null ),
            'u_full_name' => trim($_POST['u_full_name']),
            'u_phone' => $_POST['u_phone'],
            'u_gender' => $_POST['u_gender'],
            'u_country_code' => $_POST['u_country_code'],
            'u_current_city' => $_POST['u_current_city'],
            'u_timezone' => $_POST['u_timezone'],
            'u_language' => join(',',$_POST['u_language']),
            'u_bio' => trim($_POST['u_bio']),
        );

        //Some more checks:
        if(strlen($_POST['u_password_new'])>0 || strlen($_POST['u_password_current'])>0){

            //Password update attempt, lets check:
            if(strlen($_POST['u_password_new'])<=0){
                die('<span style="color:#FF0000;">Error: Missing new password. Try again.</span>');
            } elseif(strlen($u_current[0]['u_password'])>0 && !($udata['u_inbound_u_id']==1281) && strlen($_POST['u_password_current'])<=0){
                die('<span style="color:#FF0000;">Error: Missing current password. Try again.</span>');
            } elseif(strlen($u_current[0]['u_password'])>0 && !($udata['u_inbound_u_id']==1281) && !(md5($_POST['u_password_current'])==$u_current[0]['u_password'])){
                die('<span style="color:#FF0000;">Error: Invalid current password. Try again.</span>');
            } elseif(strlen($u_current[0]['u_password'])>0 && !($udata['u_inbound_u_id']==1281) && $_POST['u_password_new']==$_POST['u_password_current']){
                die('<span style="color:#FF0000;">Error: New and current password cannot be the same. Try again.</span>');
            } elseif(strlen($_POST['u_password_new'])<6){
                die('<span style="color:#FF0000;">Error: New password must be longer than 6 characters. Try again.</span>');
            } else {

                //Set password for updating:
                $u_update['u_password'] = md5($_POST['u_password_new']);

                //Reset both fields:
                echo "<script> $('#u_password_current').val(''); $('#u_password_new').val(''); </script>";

            }
        }


        //Did they just agree to the agreement?
        if(isset($_POST['u_newly_checked']) && intval($_POST['u_newly_checked']) && strlen($u_current[0]['u_terms_agreement_time'])<1){
            //Yes they did, save the timestamp:
            $u_update['u_terms_agreement_time'] = date("Y-m-d H:i:s");
        }

        //Now update the DB:
        $this->Db_model->u_update(intval($_POST['u_id']) , $u_update);

        //Refetch some DB (to keep consistency with login session format) & update the Session:
        if($_POST['u_id']==$udata['u_id']){
            $users = $this->Db_model->u_fetch(array(
                'u_id' => intval($_POST['u_id']),
            ));
            if(isset($users[0])){
                $this->session->set_userdata(array('user' => $users[0]));
            }
        }

        //Remove sensitive data before logging:
        unset($_POST['u_password_new']);
        unset($_POST['u_password_current']);

        //Log engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'], //The user that updated the account
            'e_text_value' => readable_updates($u_current[0],$u_update,'u_'),
            'e_json' => array(
                'input' => $_POST,
                'before' => $u_current[0],
                'after' => $u_update,
            ),
            'e_inbound_c_id' => 12, //Account Update
            'e_outbound_u_id' => intval($_POST['u_id']), //The user that their account was updated
        ));

        //Show success:
        echo '<span><img src="/img/round_done.gif?time='.time().'" class="loader"  /></span>';

    }









    function login(){

        //Setting for admin logins:
        $master_password = 'mench7788826962';
        $website = $this->config->item('website');

        if(!isset($_POST['u_email']) || !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
            return false;
        } elseif(!isset($_POST['u_password'])){
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
            return false;
        }

        //Fetch user data:
        $users = $this->Db_model->u_fetch(array(
            'u_email' => strtolower($_POST['u_email']),
        ));

        //See if they have any active admissions:
        $active_admission = null;

        if(count($users)==1){

            $admissions = $this->Db_model->remix_admissions(array(
                'ru_outbound_u_id' => $users[0]['u_id'],
                'ru_status >=' => 4,
            ));
            //We'd need to see which admission to load now:
            $active_admission = detect_active_admission($admissions);

        }

        if(count($users)==0){

            //Not found!
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: '.$_POST['u_email'].' not found.</div>');
            return false;

        } elseif($users[0]['u_status']<0){

            //Deleted entity
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $users[0]['u_id'],
                'e_text_value' => 'login() denied because account is not active.',
                'e_json' => $_POST,
                'e_inbound_c_id' => 9, //Support Needing Graceful Errors
            ));
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Your account has been de-activated. Contact us to re-active your account.</div>');
            return false;

        } elseif(!($_POST['u_password']==$master_password) && !($users[0]['u_password']==md5($_POST['u_password']))){

            //Bad password
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Incorrect password for '.$_POST['u_email'].'.</div>');
            return false;

        }

        $co_instructors = array();
        if(!in_array($users[0]['u_inbound_u_id'], array(1280,1308,1281))){
            //Regular user, see if they are assigned to any Bootcamp as co-instructor
            $co_instructors = $this->Db_model->instructor_bs(array(
                'ba.ba_outbound_u_id' => $users[0]['u_id'],
                'ba.ba_status >=' => 1,
                'b.b_status >=' => 2,
            ));
        }

        $session_data = array();
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS')!==false);
        $is_instructor = false;
        $is_student = false;

        //Are they a student?
        if($active_admission){
            //They have admin rights:
            $session_data['uadmission'] = $active_admission;
            $is_student = true;
        }

        //Are they admin?
        if(in_array($users[0]['u_inbound_u_id'], array(1280,1308,1281))){
            //They have admin rights:
            $session_data['user'] = $users[0];
            $is_instructor = true;
        }


        //Applicable for instructors only:
        if(!$is_chrome){

            if($is_student){

                //Remove instructor privileges as they cannot use the Console with non-chrome Browser:
                $is_instructor = false;
                unset($session_data['user']);

            } else {

                redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Login Denied. Mench Console v'.$website['version'].' support <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> only.<br />Wanna know why? <a href="https://support.mench.com/hc/en-us/articles/115003469471"><u>Continue Reading</u> &raquo;</a></div>');
                return false;

            }

        } elseif(!$is_instructor && !$is_student){

            //We assume this is a student request:
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: You have not been admitted to any Bootcamps yet. You can only login as a student after you have been approved by your instructor.</div>');
            return false;

        }


        //Log engagement
        if(!($_POST['u_password']==$master_password)){
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $users[0]['u_id'],
                'e_json' => $users[0],
                'e_inbound_c_id' => 10, //login
            ));
        }

        //All good to go!
        //Load session and redirect:
        $this->session->set_userdata($session_data);

        //Append user IP and agent information
        if(isset($_POST['u_password'])){
            unset($_POST['u_password']); //Sensitive information to be removed and NOT logged
        }
        $users[0]['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $users[0]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $users[0]['input_post_data'] = $_POST;


        if(isset($_POST['url']) && strlen($_POST['url'])>0){
            header( 'Location: '.$_POST['url'] );
        } else {
            //Default:
            if($is_instructor){
                //Instructor default:
                header( 'Location: /console' );
            } else {
                //Student default:
                header( 'Location: /my/actionplan' );
            }
        }
    }

    function logout(){
        //Log engagement:
        $udata = $this->session->userdata('user');
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => ( isset($udata['u_id']) && $udata['u_id']>0 ? $udata['u_id'] : 0 ),
            'e_json' => $udata,
            'e_inbound_c_id' => 11, //Admin Logout
        ));

        //Called via AJAX to destroy user session:
        $this->session->sess_destroy();
        header( 'Location: /' );
    }





    function u_password_reset_initiate(){
        //We need an email input:
        if(!isset($_POST['email'])){
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Missing Email.</div>');
        } elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid Email.</div>');
        }

        //Attempt to fetch this user:
        $matching_users = $this->Db_model->u_fetch(array(
            'u_email' => strtolower($_POST['email']),
        ));
        if(count($matching_users)>0){
            //Dispatch the password reset Intent:
            $this->Comm_model->foundation_message(array(
                'e_inbound_u_id' => 0,
                'e_outbound_u_id' => $matching_users[0]['u_id'],
                'e_outbound_c_id' => 3030,
                'depth' => 0,
                'e_b_id' => 0,
                'e_r_id' => 0,
            ), true);
        }

        //Show message:
        echo '<div class="alert alert-success">Password reset accepted. You will receive an email only if you have a registered Mench account.</div>';
        echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';

    }

    function u_password_reset_apply(){
        //This function updates the user's new password as requested via a password reset:
        if(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0 || !isset($_POST['timestamp']) || intval($_POST['timestamp'])<=0 || !isset($_POST['p_hash']) || strlen($_POST['p_hash'])<10){
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Missing Core Variables.</div>';
        } elseif(!($_POST['p_hash']==md5($_POST['u_id'] . 'p@ssWordR3s3t' . $_POST['timestamp']))){
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid hash key.</div>';
        } elseif(!isset($_POST['new_pass']) || strlen($_POST['new_pass'])<6){
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: New password must be longer than 6 characters. Try again.</div>';
        } else {
            //All seems good, lets update their account:
            $this->Db_model->u_update( intval($_POST['u_id']) , array(
                'u_password' => md5($_POST['new_pass']),
            ));

            //Log engagement:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => intval($_POST['u_id']),
                'e_inbound_c_id' => 59, //Password reset
            ));

            //Log all sessions out:
            $this->session->sess_destroy();

            //Show message:
            echo '<div class="alert alert-success">Passsword reset successful. You can <a href="/login"><u>login here</u></a>.</div>';
            echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';
        }
    }


}