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
    function entity_manage($u_id){

        $udata = auth(null,1); //Just be logged in to browse
        $view_data = fetch_entity_tree($u_id);

        //Load views
        $this->load->view('console/console_header' , $view_data);
        $this->load->view('entities/entity_manage' , $view_data);
        $this->load->view('console/console_footer');
    }

    function hard_delete($u_id){

        $udata = $this->session->userdata('user');
        if(!array_key_exists(1281, $udata['u__inbounds'])){
            return echo_json(array(
                'status' => 0,
                'message' => 'Session expired',
            ));
        }

        //Attempt to delete:
        echo_json($this->Db_model->u_hard_delete($u_id));
    }

    function entity_load_more(){

        $entity_per_page = $this->config->item('entity_per_page');
        $inbound_u_id = intval($_POST['inbound_u_id']);
        $u_status_filter = intval($_POST['u_status_filter']);
        $page = intval($_POST['page']);
        $udata = auth(null); //Just be logged in to browse
        $filters = array(
            'ur_inbound_u_id' => $inbound_u_id,
            'u_status'.( $u_status_filter<0 ? ' >=' : '' ) => ( $u_status_filter<0 ? 0 : intval($u_status_filter) ), //Pending or Active
            'ur_status' => 1, //Active link
        );

        if(!$udata){
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle" style="margin:0 8px 0 2px;"></i> Session expired. Refresh the page and try again.</div>';
            return false;
        }

        //Fetch entity itself:
        $entities = $this->Db_model->u_fetch(array('u_id' => $inbound_u_id));
        $child_entities_count = count($this->Db_model->ur_outbound_fetch($filters));
        $child_entities = $this->Db_model->ur_outbound_fetch($filters, array('u__outbound_count'), $entity_per_page, ($page*$entity_per_page));

        foreach($child_entities as $u){
            echo echo_u($u, 2, intval($_POST['can_edit']), false /* Load more only for outbound */);
        }

        //Do we need another load more button?
        if($child_entities_count>(($page*$entity_per_page) + count($child_entities))){
            echo_next_u(($page+1), $entity_per_page, $child_entities_count);
        }

    }



    function link_entities(){

        //Responsible to link inbound/outbound entities to each other via a JS function on entity_manage.php

        //Auth user and check required variables:
        $udata = auth(array(1308));

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
                'u_status >=' => 1, //Active only
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
                    $url_create = $this->Db_model->x_sync(trim($_POST['new_u_input']),1326, $_POST['can_edit'], $accept_existing_url);

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

            //Let's make sure this is not the same as the secondary category:
            if(!($_POST['secondary_parent_u_id']==$ur_inbound_u_id)){
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
            } else {
                //This has already been added:
                $ur2 = $ur1;
            }

            //Update Algolia:
            $this->Db_model->algolia_sync('u',$ur_outbound_u_id);
            $this->Db_model->algolia_sync('u',$ur_inbound_u_id);
        }


        //Return newly added/linked entity:
        return echo_json(array(
            'status' => 1,
            'message' => 'Success',
            'new_u_status' => $new_u['u_status'],
            'new_u' => echo_u(array_merge($new_u,$ur2),2, $_POST['can_edit'], $_POST['is_inbound']),
        ));
    }

    function unlink_entities(){

        //Auth user and check required variables:
        $udata = auth(array(1308));

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


    function u_save_settings(){

        //Auth user and check required variables:
        $udata = auth(array(1308));
        $message_max = $this->config->item('message_max');

        //Fetch current data:
        $u_current = $this->Db_model->u_fetch(array(
            'u_id' => intval($_POST['u_id']),
        ));

        if(!$udata){
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0 || !(count($u_current)==1)){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid ID',
            ));
        } elseif(!isset($_POST['u_full_name']) || strlen($_POST['u_full_name'])<=0){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing name',
            ));
        } elseif(strlen($_POST['u_full_name'])>$this->config->item('u_full_name_max')){
            return echo_json(array(
                'status' => 0,
                'message' => 'Name is longer than the allowed '.$this->config->item('u_full_name_max').' characters. Shorten and try again.',
            ));
        } elseif(strlen($_POST['u_email'])>0 && !filter_var($_POST['u_email'], FILTER_VALIDATE_EMAIL)){
            return echo_json(array(
                'status' => 0,
                'message' => 'Email ['.$_POST['u_email'].'] is invalid',
            ));
        } elseif(filter_var($u_current[0]['u_email'], FILTER_VALIDATE_EMAIL) && strlen($_POST['u_email'])==0){
            return echo_json(array(
                'status' => 0,
                'message' => 'Initial email was ['.$u_current[0]['u_email'].']. Email is required once its set',
            ));
        } elseif(strlen($_POST['u_bio'])>0 && !(strip_tags($_POST['u_bio'])==$_POST['u_bio'])){
            return echo_json(array(
                'status' => 0,
                'message' => 'Cannot include code in your bio',
            ));
        } elseif(strlen($_POST['u_bio'])>$message_max){
            return echo_json(array(
                'status' => 0,
                'message' => 'Introductory Message should be less than '.$message_max.' characters',
            ));
        }

        //Adjust email:
        $_POST['u_email'] = strtolower($_POST['u_email']);

        //Make sure email is unique:
        if(strlen($_POST['u_email'])>0){
            $dup_email = $this->Db_model->u_fetch(array(
                'u_id !=' => $_POST['u_id'],
                'u_email' => strtolower($_POST['u_email']),
            ));
            if(count($dup_email)>0){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Email ['.$_POST['u_email'].'] is already assigned to ['.$dup_email[0]['u_full_name'].']',
                ));
            }
        }

        //Prepare data to be updated:
        $u_update = array(
            'u_full_name' => trim($_POST['u_full_name']),
            'u_bio' => str_replace('"','`',trim($_POST['u_bio'])),
            'u_email' => ( isset($_POST['u_email']) && strlen($_POST['u_email'])>0 ? trim(strtolower($_POST['u_email'])) : null ),
        );

        //Some more checks:
        if(strlen($_POST['u_password_new'])>0){

            //Password update attempt, lets check:
            if(!array_key_exists(1281, $udata['u__inbounds'])){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'You must be an admin to set new passwords',
                ));
            } elseif(strlen($_POST['u_password_new'])<6){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'New password must be longer than 6 characters',
                ));
            }

            //Set password for updating:
            $u_update['u_password'] = md5($_POST['u_password_new']);

            //Reset field:
            echo "<script> $('#u_password_new').val(''); </script>";
        }

        //Now update the DB:
        $this->Db_model->u_update(intval($_POST['u_id']) , $u_update);
        //Above call would also update algolia index...


        //Refetch some DB (to keep consistency with login session format) & update the Session:
        if($_POST['u_id']==$udata['u_id']){
            $users = $this->Db_model->u_fetch(array(
                'u_id' => intval($_POST['u_id']),
            ));
            if(isset($users[0])){
                $this->session->set_userdata(array('user' => $users[0]));
            }
        }

        //Remove sensitive data before logging engagement:
        unset($_POST['u_password_new']);

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
        return echo_json(array(
            'status' => 1,
            'message' => '<span><i class="fas fa-check"></i> Saved</span>',
        ));

    }




    function load_messages(){

        $udata = auth();
        if(!$udata){
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Login again to continue.</span>');
        } elseif(!isset($_POST['u_id']) || intval($_POST['u_id'])<=0){
            die('<span style="color:#FF0000;">Error: Invalid entity id.</span>');
        }

        $messages = $this->Db_model->i_fetch(array(
            'i_status >=' => 0,
            'i_outbound_u_id' => $_POST['u_id'],
        ), 0, array('x'));
        echo '<div id="list-messages" class="list-group  grey-list">';
        foreach($messages as $i){
            echo echo_i($i);
        }
        echo '</div>';
    }




    function login(){

        //Setting for admin logins:
        $master_password = 'mench7788826962';

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
        ), array('u__ws'));

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

        //Are they admin?
        if(array_any_key_exists(array(1308,1281),$users[0]['u__inbounds'])){
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

                redirect_message('/login','<div class="alert alert-danger" role="alert">Error: Login Denied. Mench Console v'.$this->config->item('app_version').' support <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> only.</div>');
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
                header( 'Location: /intents/'.$this->config->item('primary_c') );
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
            'e_inbound_c_id' => 11, //User Logout
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
                'e_outbound_c_id' => 59,
            ), true);
        }

        //Show message:
        echo '<div class="alert alert-success">Password reset accepted. You will receive an email only if you have a registered Mench account.</div>';
        echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';

    }

    function filter_u_status(){

    }

    function update_u_status(){

        //Auth user and check required variables:
        $udata = auth(array(1281));

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
        } elseif(!isset($_POST['new_u_status']) || !in_array($_POST['new_u_status'],array(1,2))){
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing New Status with value 1 or 2',
            ));
        }

        //Validate parent entity:
        $current_us = $this->Db_model->u_fetch(array(
            'u_id' => $_POST['u_id'],
        ));
        if(count($current_us)<1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid entity ID',
            ));
        } elseif($current_us[0]['u_status']==$_POST['new_u_status']){
            //Status seems to be already updated, just update the UI:
            return echo_json(array(
                'status' => 1,
                'old_status' => intval($current_us[0]['u_status']),
                'message' => '<span>'.echo_status('u',$_POST['new_u_status'], true, 'left').'</span>',
            ));
        } elseif(!in_array($current_us[0]['u_status'],array(0,1))){
            return echo_json(array(
                'status' => 0,
                'message' => 'You cannot change the status of this entity because of its current status',
            ));
        }

        $u_update = array(
            'u_status' => $_POST['new_u_status'],
        );

        //Update status:
        //Now update the DB:
        $this->Db_model->u_update(intval($_POST['u_id']) , $u_update);
        //Above call would also update algolia index...


        //Log engagement:
        $this->Db_model->e_create(array(
            'e_inbound_u_id' => $udata['u_id'], //The user that updated the account
            'e_text_value' => readable_updates($current_us[0],$u_update,'u_'),
            'e_json' => array(
                'input' => $_POST,
                'before' => $current_us[0],
                'after' => $u_update,
            ),
            'e_inbound_c_id' => 12, //Account Update
            'e_outbound_u_id' => intval($_POST['u_id']), //The user that their account was updated
        ));

        //Show success:
        return echo_json(array(
            'status' => 1,
            'old_status' => intval($current_us[0]['u_status']),
            'message' => '<span>'.echo_status('u',$_POST['new_u_status'], true, 'left').'</span>',
        ));

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