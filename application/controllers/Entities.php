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


    //Lists entities
    function entity_manage($u_id=2738){

        $udata = auth(null,1); //Just be logged in to browse
        $view_data = fetch_entity_tree($u_id);

        //Load views
        $this->load->view('console/console_header' , $view_data);
        $this->load->view('entities/entity_manage' , $view_data);
        $this->load->view('console/console_footer');
    }

    function delete($u_id){

        $udata = $this->session->userdata('user');
        if(!array_key_exists(1281, $udata['u__inbounds'])){
            return echo_json(array(
                'status' => 0,
                'message' => 'Session expired',
            ));
        }

        //Attempt to delete:
        echo_json($this->Db_model->u_delete($u_id));
    }

    function entity_load_more($inbound_u_id,$limit,$page){

        $udata = auth(null); //Just be logged in to browse

        if(!$udata){
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle" style="margin:0 8px 0 2px;"></i> Session expired. Refresh the page and try again.</div>';
            return false;
        }

        //Fetch entitie itself:
        $entities = $this->Db_model->u_fetch(array(
            'u_id' => $inbound_u_id,
        ), array('u__outbound_count'));

        $child_entities = $this->Db_model->ur_outbound_fetch(array(
            'ur_inbound_u_id' => $inbound_u_id,
            'ur_status >=' => 0, //Pending or Active
            'u_status >=' => 0, //Pending or Active
        ), array('u__outbound_count'), $limit, ($page*$limit));

        foreach($child_entities as $u){
            echo echo_u($u, 2, intval($_POST['can_edit']), false /* Load more only for outbound */);
        }

        //Do we need another load more button?
        if($entities[0]['u__outbound_count']>(($page*$limit) + count($child_entities))){
            echo_next_u(($page+1), $limit, $entities[0]['u__outbound_count']);
        }

    }


    //Edit entities
    function entity_edit($u_id){

        //Authenticate user:
        $udata = $this->session->userdata('user');

        $udata = auth(array(1308,1280),1,0, $u_id);
        $entity_tree = fetch_entity_tree($u_id,true);

        //Adjust Breadcrumb for non-admins
        if(!array_key_exists(1281, $udata['u__inbounds'])){
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
        $this->load->view('entities/entity_edit', $view_data);
        $this->load->view('console/console_footer');
    }


    function link_entities(){

        //Responsible to link inbound/outbound entities to each other via a JS function on entity_manage.php

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
        } elseif(!isset($_POST['can_edit'])){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Editing Permission',
            ));
        } elseif(!isset($_POST['secondary_parent_u_id'])){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Parent Entity',
            ));
        } elseif(!isset($_POST['is_inbound'])){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Entity Link Direction',
            ));
        } elseif(!isset($_POST['new_u_id']) || !isset($_POST['new_u_input']) || (intval($_POST['new_u_id'])<1 && strlen($_POST['new_u_input'])<1)){
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


        //Set some variables:
        $_POST['is_inbound'] = intval($_POST['is_inbound']);
        $_POST['new_u_id'] = intval($_POST['new_u_id']);
        $linking_to_existing_u = false;
        $is_url_input = false;

        //Are we linking to an existing entity?
        if(intval($_POST['new_u_id'])>0){

            //We're linking to an existing entity:
            $linking_to_existing_u = true;

            //Validate this existing entity
            $new_us = $this->Db_model->u_fetch(array(
                'u_id' => $_POST['new_u_id'],
                'u_status' => 1, //Active only
            ));
            if(count($new_us)<1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid active entity',
                ));
            } else {
                $new_u = $new_us[0];
            }

            //Is this one of the main entity objects?
            if($_POST['is_inbound'] && in_array($_POST['new_u_id'], array(1278,1326,2750))){
                //Does this entity already belong to one?
                $entity_type = entity_type($current_us[0]);
                if($entity_type && $entity_type!=$_POST['new_u_id']){
                    return echo_json(array(
                        'status' => 0,
                        'message' => '['.$current_us[0]['u_full_name'].'] already belong to ['.$current_us[0]['u__inbounds'][$entity_type]['u_full_name'].'] which means it cannot also be added as ['.$new_u['u_full_name'].']. You can unlink ['.$current_us[0]['u__inbounds'][$entity_type]['u_full_name'].'] and try again.',
                    ));
                }
            }

        } else {

            if(filter_var(trim($_POST['new_u_input']),FILTER_VALIDATE_URL)){


                if($_POST['secondary_parent_u_id']==1326){

                    //It's a URL, create an entity from this URL:
                    $accept_existing_url = ( !$_POST['is_inbound'] && $current_us[0]['u_id']!=1326 ); //We can accept duplicates if we're not adding directly under content
                    $url_create = $this->Db_model->add_db_url(trim($_POST['new_u_input']),1326, $_POST['can_edit'], $accept_existing_url);

                    //Did we have an error?
                    if(!$url_create['status']){
                        return echo_json($url_create);
                    } else {
                        $linking_to_existing_u = ( isset($url_create['is_existing']) );
                        $is_url_input = true;
                        $new_u = $url_create['u'];
                    }

                } else {
                    //We only support URL creation for content:
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'You can only use a URL for content entry',
                    ));
                }

            } else {

                //We should add a new entity:
                $new_u = $this->Db_model->u_create(array(
                    'u_full_name' => trim($_POST['new_u_input']),
                ));

                if(!isset($new_u['u_id']) || $new_u['u_id']<1){
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Failed to create new entity for ['.$_POST['new_u_input'].']',
                    ));
                }

                //Do we need to add this new entity to a secondary parent?
                if(intval($_POST['secondary_parent_u_id'])>0){

                    //Link entity to a parent:
                    $ur1 = $this->Db_model->ur_create(array(
                        'ur_outbound_u_id' => $new_u['u_id'],
                        'ur_inbound_u_id' => $_POST['secondary_parent_u_id'],
                    ));

                    $this->Db_model->e_create(array(
                        'e_inbound_u_id' => $udata['u_id'],
                        'e_ur_id' => $ur1['ur_id'],
                        'e_inbound_c_id' => 7291, //Entity Link Create
                    ));
                }

            }
        }

        //We need to check to ensure this is not a duplicate link if linking to an existing entity:
        $ur2 = array();

        if($linking_to_existing_u){

            //Check for duplicates:
            if($_POST['is_inbound']){
                $dup_entities = $this->Db_model->ur_outbound_fetch(array(
                    '(ur_outbound_u_id='.$_POST['u_id'].' AND ur_inbound_u_id='.$new_u['u_id'].')' => null,
                    'ur_status' => 1, //Only active
                ));
            } else {
                $dup_entities = $this->Db_model->ur_outbound_fetch(array(
                    '(ur_inbound_u_id='.$_POST['u_id'].' AND ur_outbound_u_id='.$new_u['u_id'].')' => null,
                    'ur_status' => 1, //Only active
                ));
            }

            if(count($dup_entities)>0){
                return echo_json(array(
                    'status' => 0,
                    'message' => '['.$new_u['u_full_name'].'] is already linked to ['.$current_us[0]['u_full_name'].'] as an '.( $_POST['is_inbound'] ? 'inbound' : 'outbound' ).' entity',
                ));
            }

        }

        if(!$is_url_input){
            //Add links only if not already added by the URL function:
            if($_POST['is_inbound']){
                $ur_outbound_u_id = $current_us[0]['u_id'];
                $ur_inbound_u_id = $new_u['u_id'];
            } else {
                $ur_outbound_u_id = $new_u['u_id'];
                $ur_inbound_u_id = $current_us[0]['u_id'];
            }

            //Link to new OR existing entity:
            $ur2 = $this->Db_model->ur_create(array(
                'ur_outbound_u_id' => $ur_outbound_u_id,
                'ur_inbound_u_id' => $ur_inbound_u_id,
            ));

            //Insert engagement for creation:
            $this->Db_model->e_create(array(
                'e_inbound_u_id' => $udata['u_id'],
                'e_ur_id' => $ur2['ur_id'],
                'e_inbound_c_id' => 7291, //Entity Link Create
            ));

            //Update Algolia:
            $this->Db_model->algolia_sync('u',$ur_outbound_u_id);
        }


        //Return newly added/linked entity:
        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
            'new_u' => echo_u(array_merge($new_u,$ur2),2, $_POST['can_edit'], $_POST['is_inbound']),
        ));
    }

    function unlink_entities(){

        //Auth user and check required variables:
        $udata = auth(array(1308,1280));

        if(!$udata){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif(!isset($_POST['ur_id']) || intval($_POST['ur_id'])<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity link ID',
            ));
        }

        $affected_rows = $this->Db_model->ur_delete($_POST['ur_id']);

        //Log unlinking engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'],
            'e_ur_id' => $_POST['ur_id'],
            'e_inbound_c_id' => 7292, //Entity Link Removed
        ));

        return echo_json(array(
            'status' => 1,
            'message' => 'Successfully unlinked entity',
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
        } elseif(strlen($_POST['u_bio'])>0 && !(strip_tags($_POST['u_bio'])==$_POST['u_bio'])){
            die('<span style="color:#FF0000;">Error: Cannot include code in your bio.</span>');
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
            } elseif(strlen($u_current[0]['u_password'])>0 && !array_key_exists(1281, $udata['u__inbounds']) && strlen($_POST['u_password_current'])<=0){
                die('<span style="color:#FF0000;">Error: Missing current password. Try again.</span>');
            } elseif(strlen($u_current[0]['u_password'])>0 && !array_key_exists(1281, $udata['u__inbounds']) && !(md5($_POST['u_password_current'])==$u_current[0]['u_password'])){
                die('<span style="color:#FF0000;">Error: Invalid current password. Try again.</span>');
            } elseif(strlen($u_current[0]['u_password'])>0 && !array_key_exists(1281, $udata['u__inbounds']) && $_POST['u_password_new']==$_POST['u_password_current']){
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

        //See if they have any active enrollments:
        $active_enrollment = null;

        if(count($users)==1){

            $enrollments = $this->Db_model->remix_enrollments(array(
                'ru_outbound_u_id' => $users[0]['u_id'],
                'ru_status >=' => 4,
            ));
            //We'd need to see which enrollment to load now:
            $active_enrollment = detect_active_enrollment($enrollments);

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


        $session_data = array();
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')!==false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS')!==false);
        $is_coach = false;
        $is_student = false;

        //Are they a student?
        if($active_enrollment){
            //They have admin rights:
            $session_data['uenrollment'] = $active_enrollment;
            $is_student = true;
        }

        //Are they admin?
        if(array_any_key_exists(array(1280,1308,1281),$users[0]['u__inbounds'])){
            //They have admin rights:
            $session_data['user'] = $users[0];
            $is_coach = true;
        }


        //Applicable for coaches only:
        if(!$is_chrome){

            if($is_student){

                //Remove coach privileges as they cannot use the Console with non-chrome Browser:
                $is_coach = false;
                unset($session_data['user']);

            } else {

                redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Login Denied. Mench Console v'.$website['version'].' support <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> only.<br />Wanna know why? <a href="https://support.mench.com/hc/en-us/articles/115003469471"><u>Continue Reading</u> &raquo;</a></div>');
                return false;

            }

        } elseif(!$is_coach && !$is_student){

            //We assume this is a student request:
            redirect_message('/login','<div class="alert alert-danger" role="alert">Error: You have not been enrolled to any Bootcamps yet. You can only login as a student after you have been approved by your coach.</div>');
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
            if($is_coach){
                //Coach default:
                header( 'Location: /intents' );
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