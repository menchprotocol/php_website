<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_app extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }



    function test($in_id){
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $in_id,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ));

        echo_json(array(
            'next' => $this->User_app_model->actionplan_step_next_find(1, $ins[0]),
            'completion' => $this->User_app_model->actionplan_completion_progress(1, $ins[0]),
            'marks' => $this->User_app_model->actionplan_completion_marks(1, $ins[0]),
            'recursive_parents' => $this->Intents_model->in_fetch_recursive_public_parents($ins[0]['in_id']),
            'common_base' => $this->Intents_model->in_metadata_common_base($ins[0]),

        ));
    }





    function signin($in_id = 0, $referrer_en_id = 0){

        //Check to see if they are already logged in?
        $session_en = $this->session->userdata('user');
        if (isset($session_en['en__parents'][0])) {
            //Lead miner and above, go to console:
            if(filter_array($session_en['en__parents'], 'en_id', 1308)){
                return redirect_message('/dashboard');
            } else {
                return redirect_message('/actionplan' . ( $in_id > 0 ? '/'.$in_id : '' ));
            }
        }

        $en_all_7369 = $this->config->item('en_all_7369');
        $this->load->view('view_user_app/user_app_header', array(
            'hide_header' => 1,
            'title' => $en_all_7369[4269]['m_name'],
        ));
        $this->load->view('view_user_app/signin', array(
            'referrer_in_id' => intval($in_id),
            'referrer_en_id' => intval($referrer_en_id),
            'session_en' => $this->session->userdata('user'),
        ));
        $this->load->view('view_user_app/user_app_footer', array(
            'hide_footer' => 1,
        ));

    }




    function singin_check_password(){

        if (!isset($_POST['login_en_id']) || intval($_POST['login_en_id'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing user ID',
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < $this->config->item('password_min_char')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Password longer than '.$this->config->item('password_min_char').' characters',
            ));
        } elseif (!isset($_POST['referrer_url'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing referrer URL',
            ));
        }



        //Validaye user ID
        $ens = $this->Entities_model->en_fetch(array(
            'en_id' => $_POST['login_en_id'],
        ));
        if (!in_array($ens[0]['en_status_entity_id'], $this->config->item('en_ids_7357') /* Entity Statuses Public */)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Your account entity is not public. Contact us to adjust your account.',
            ));
        }

        //Authenticate password:
        $user_passwords = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4255, //Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $ens[0]['en_id'],
        ));
        if (count($user_passwords) == 0) {
            //They do not have a password assigned yet!
            return echo_json(array(
                'status' => 0,
                'message' => 'An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.',
            ));
        } elseif (!in_array($user_passwords[0]['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)) {
            //They do not have a password assigned yet!
            return echo_json(array(
                'status' => 0,
                'message' => 'Password link is not public. Contact us to adjust your account.',
            ));
        } elseif ($user_passwords[0]['ln_content'] != hash('sha256', $this->config->item('password_salt') . $_POST['input_password'] . $ens[0]['en_id'])) {
            //Bad password
            return echo_json(array(
                'status' => 0,
                'message' => 'Incorrect password',
            ));
        }

        //Now let's do a few more checks:


        $session_data = array();
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false);


        $is_user = filter_array($ens[0]['en__parents'], 'en_id', 4430); //Mench Users
        $is_miner = filter_array($ens[0]['en__parents'], 'en_id', 1308); //Mench Miners


        //Applicable for anyone using the Mench mining app:
        if (!$is_chrome && $is_miner) {

            $this->Links_model->ln_create(array(
                'ln_content' => 'User failed to login using non-Chrome browser',
                'ln_type_entity_id' => 7504, //Admin Review Required
                'ln_miner_entity_id' => $ens[0]['en_id'],
            ));

            return echo_json(array(
                'status' => 0,
                'message' => 'Mench Miner App only support Google Chrome web browser.',
            ));

        }


        //Assign session & log link:
        $this->User_app_model->user_activate_session($ens[0], $is_miner);



        if (isset($_POST['referrer_url']) && strlen($_POST['referrer_url']) > 0) {
            $login_url = urldecode($_POST['referrer_url']);
        } else if ($is_miner) {
            $login_url = '/dashboard';
        } else {
            $login_url = '/actionplan';
        }

        return echo_json(array(
            'status' => 1,
            'login_url' => $login_url,
        ));

    }

    function signin_reset_password_ui($ln_id){

        //Log all sessions out:
        $this->session->sess_destroy();

        //Make sure email input is provided:
        if(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
            //Missing email input:
            return redirect_message('/signin', '<div class="alert alert-danger" role="alert">Missing email address</div>');
        }

        //Validate link ID and matching email:
        $validate_links = $this->Links_model->ln_fetch(array(
            'ln_id' => $ln_id,
            'ln_content' => $_GET['email'],
            'ln_type_entity_id' => 7563, //User Signin Magic Link Email
        ), array('en_miner')); //The user making the request

        if(count($validate_links) < 1){
            //Probably already completed the reset password:
            return redirect_message('/signin', '<div class="alert alert-danger" role="alert">Reset password link not found</div>');
        }

        $this->load->view('view_user_app/user_app_header', array(
            'hide_header' => 1,
            'title' => 'Reset Password',
        ));
        $this->load->view('view_user_app/password_reset', array(
            'validate_link' => $validate_links[0],
        ));
        $this->load->view('view_user_app/user_app_footer', array(
            'hide_footer' => 1,
        ));

    }




    function signin_reset_password_apply()
    {

        //This function updates the user's new password as requested via a password reset:
        if (!isset($_POST['ln_id']) || intval($_POST['ln_id']) < 1 || !isset($_POST['input_email']) || strlen($_POST['input_email']) < 1 || !isset($_POST['input_password'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (strlen($_POST['input_password']) < $this->config->item('password_min_char')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Must be longer than '.$this->config->item('password_min_char').' characters',
            ));
        } else {

            //Validate link ID and matching email:
            $validate_links = $this->Links_model->ln_fetch(array(
                'ln_id' => $_POST['ln_id'],
                'ln_content' => $_POST['input_email'],
                'ln_type_entity_id' => 7563, //User Signin Magic Link Email
            )); //The user making the request
            if(count($validate_links) < 1){
                //Probably already completed the reset password:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Reset password link not found',
                ));
            }

            //Validate user:
            $ens = $this->Entities_model->en_fetch(array(
                'en_id' => $validate_links[0]['ln_miner_entity_id'],
            ));
            if(count($ens) < 1){
                return echo_json(array(
                    'status' => 0,
                    'message' => 'User not found',
                ));
            }


            //Fetch their passwords to authenticate login:
            $user_passwords = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_parent_entity_id' => 3286, //Mench Sign In Password
                'ln_child_entity_id' => $ens[0]['en_id'],
            ));

            $input_password = hash('sha256', $this->config->item('password_salt') . $_POST['input_password']. $ens[0]['en_id']);

            if (count($user_passwords) > 0) {

                $detected_ln_type = ln_detect_type($input_password);
                if (!$detected_ln_type['status']) {
                    return echo_json($detected_ln_type);
                }

                //Update existing password:
                $this->Links_model->ln_update($user_passwords[0]['ln_id'], array(
                    'ln_content' => $input_password,
                    'ln_type_entity_id' => $detected_ln_type['ln_type_entity_id'],
                ), $ens[0]['en_id']);

            } else {

                //Create new password link:
                $this->Links_model->ln_create(array(
                    'ln_type_entity_id' => 4255, //Text link
                    'ln_content' => $input_password,
                    'ln_parent_entity_id' => 3286, //Mench Password
                    'ln_miner_entity_id' => $ens[0]['en_id'],
                    'ln_child_entity_id' => $ens[0]['en_id'],
                ));

            }


            //Log password reset:
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $ens[0]['en_id'],
                'ln_type_entity_id' => 7578, //User Signin Password Updated
                'ln_content' => $input_password, //A copy of their password set at this time
            ));


            //Log them in:
            $is_miner = filter_array($ens[0]['en__parents'], 'en_id', 1308); //Mench Miners
            $this->User_app_model->user_activate_session($ens[0], $is_miner);

            //Their next intent in line:
            return echo_json(array(
                'status' => 1,
                'login_url' => ( $is_miner ? '/dashboard' : '/actionplan/next' ),
            ));


        }
    }


    function signin_create_account(){

        if (!isset($_POST['referrer_in_id']) || !isset($_POST['referrer_en_id']) || !isset($_POST['referrer_url'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        } elseif (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid email address',
            ));
        } elseif (!isset($_POST['input_name']) || strlen($_POST['input_name'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing name',
                'focus_input_field' => 'input_name',
            ));
        }

        //Prep inputs & validate further:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $_POST['input_name'] = trim($_POST['input_name']);
        $name_parts = explode(' ', trim($_POST['input_name']));
        if (strlen($_POST['input_name'])<5) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Full name must longer than 5 characters',
                'focus_input_field' => 'input_name',
            ));
        } elseif (!isset($name_parts[1])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'There must be a space between your your first and last name',
                'focus_input_field' => 'input_name',
            ));
        } elseif (strlen($name_parts[0])<2) {
            return echo_json(array(
                'status' => 0,
                'message' => 'First name must be 2 characters or longer',
                'focus_input_field' => 'input_name',
            ));
        } elseif (strlen($name_parts[1])<2) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Last name must be 2 characters or longer',
                'focus_input_field' => 'input_name',
            ));
        } elseif (strlen($_POST['input_name']) > $this->config->item('en_name_max_length')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Full name must be less than '.$this->config->item('en_name_max_length').' characters',
                'focus_input_field' => 'input_name',
            ));
        } elseif (!isset($_POST['new_password']) || strlen($_POST['new_password'])<1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing password',
                'focus_input_field' => 'new_password',
            ));
        } elseif (strlen($_POST['new_password']) < $this->config->item('password_min_char')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'New password must be '.$this->config->item('password_min_char').' characters or longer',
                'focus_input_field' => 'new_password',
            ));
        }



        //All good, create new entity:
        $user_en = $this->Entities_model->en_verify_create(trim($_POST['input_name']), 0, false, 6181);
        if(!$user_en['status']){
            //We had an error, return it:
            return echo_json($user_en);
        }


        //Create user links:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_parent_entity_id' => 4430, //Mench User
            'ln_miner_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));

        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_parent_entity_id' => 3504, //English Language (Since everything is in English so far)
            'ln_miner_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4255, //Text link
            'ln_content' => trim(strtolower($_POST['input_email'])),
            'ln_parent_entity_id' => 3288, //Mench Email
            'ln_miner_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4255, //Text link
            'ln_content' => strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['new_password'] . $user_en['en']['en_id'])),
            'ln_parent_entity_id' => 3286, //Mench Password
            'ln_miner_entity_id' => $user_en['en']['en_id'],
            'ln_child_entity_id' => $user_en['en']['en_id'],
        ));


        //Fetch referranl intent, if any:
        if(intval($_POST['referrer_in_id']) > 0){

            //Fetch the intent:
            $referrer_ins = $this->Intents_model->in_fetch(array(
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'in_id' => $_POST['referrer_in_id'],
            ));

            //Add this intention to their Action Plan:
            $this->User_app_model->actionplan_intention_add($user_en['en']['en_id'], $_POST['referrer_in_id'], 0, false);

        } else {
            $referrer_ins = array();
        }


        ##Email Subject
        $subject =  ( count($referrer_ins) > 0 ? echo_in_outcome($referrer_ins[0]['in_outcome'], true).' with ' : 'Welcome to ' ) . $this->config->item('system_name');

        ##Email Body
        $html_message = '<div>Hi '.$name_parts[0].' 👋</div><br />';

        $html_message .= '<div>'.( count($referrer_ins) > 0 ? echo_in_outcome($referrer_ins[0]['in_outcome'], true) : 'Get started' ).':</div><br />';
        $actionplan_url = $this->config->item('base_url') . ( count($referrer_ins) > 0 ? 'actionplan/'.$referrer_ins[0]['in_id'] : '' );
        $html_message .= '<div><a href="'.$actionplan_url.'" target="_blank">' . $actionplan_url . '</a></div><br /><br />';

        $html_message .= '<div>Connect on Messenger:</div><br />';
        $messenger_url = $this->config->item('fb_mench_url') . ( count($referrer_ins) > 0 ? '?ref=' . ( $_POST['referrer_en_id'] > 0 ? 'REFERUSER_'.$_POST['referrer_en_id'].'_' : '' ) . $referrer_ins[0]['in_id'] : '' ) ;
        $html_message .= '<div><a href="'.$messenger_url.'" target="_blank">' . $messenger_url . '</a></div>';
        $html_message .= '<br /><br />';
        $html_message .= '<div>Cheers,</div><br />';
        $html_message .= '<div>Mench</div>';
        $html_message .= '<div><a href="https://mench.com?utm_source=mench&utm_medium=email&utm_campaign=signup" target="_blank">mench.com</a></div>';

        //Send Welcome Email:
        $email_log = $this->Communication_model->user_received_emails(array($_POST['input_email']), $subject, $html_message);

        //Log User Signin Joined Mench
        $invite_link = $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 7562, //User Signin Joined Mench
            'ln_miner_entity_id' => $user_en['en']['en_id'],
            'ln_parent_intent_id' => intval($_POST['referrer_in_id']),
            'ln_parent_entity_id' => intval($_POST['referrer_en_id']),
            'ln_metadata' => array(
                'email_log' => $email_log,
            ),
        ));

        //Assign session & log login link:
        $this->User_app_model->user_activate_session($user_en['en'], false);


        if (strlen($_POST['referrer_url']) > 0) {
            $login_url = urldecode($_POST['referrer_url']);
        } elseif(intval($_POST['referrer_in_id']) > 0) {
            $login_url = '/actionplan/'.$_POST['referrer_in_id'];
        } else {
            //Go to home page and let them continue from there:
            $login_url = '/';
        }

        return echo_json(array(
            'status' => 1,
            'login_url' => $login_url,
        ));



    }

    function singin_magic_link_email(){


        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid email address',
            ));
        } elseif (!isset($_POST['referrer_in_id']) || !isset($_POST['referrer_en_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }

        //Cleanup/validate email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));
        $user_emails = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_content' => $_POST['input_email'],
            'ln_type_entity_id' => 4255, //Linked Entities Text (Email is text)
            'ln_parent_entity_id' => 3288, //Mench Email
        ), array('en_child'));
        if(count($user_emails) < 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Email not associated with a registered account',
            ));
        }

        //Log email search attempt:
        $reset_link = $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 7563, //User Signin Magic Link Email
            'ln_content' => $_POST['input_email'],
            'ln_miner_entity_id' => $user_emails[0]['en_id'], //User making request
            'ln_parent_intent_id' => intval($_POST['referrer_in_id']),
            'ln_parent_entity_id' => intval($_POST['referrer_en_id']),
        ));

        //This is a new email, send invitation to join:

        ##Email Subject
        $subject = 'Mench Login Magic Link';

        ##Email Body
        $html_message = '<div>Hi '.one_two_explode('',' ',$user_emails[0]['en_name']).' 👋</div><br /><br />';

        $magic_link_expiry_hours = ($this->config->item('magic_link_expiry')/3600);
        $html_message .= '<div>Login within '.$magic_link_expiry_hours.'-hour'.echo__s($magic_link_expiry_hours).':</div>';
        $magiclogin_url = 'https://mench.com/magiclogin/' . $reset_link['ln_id'] . '?email='.$_POST['input_email'];
        $html_message .= '<div><a href="'.$magiclogin_url.'" target="_blank">' . $magiclogin_url . '</a></div>';

        $password_reset_expiry_hours = ($this->config->item('password_reset_expiry')/3600);
        $html_message .= '<br /><br /><div>Or reset password within '.$password_reset_expiry_hours.'-hour'.echo__s($password_reset_expiry_hours).':</div>';
        $setpassword_url = 'https://mench.com/resetpassword/' . $reset_link['ln_id'] . '?email='.$_POST['input_email'];
        $html_message .= '<div><a href="'.$setpassword_url.'" target="_blank">' . $setpassword_url . '</a></div>';

        $html_message .= '<br /><br />';
        $html_message .= '<div>- <a href="https://mench.com?utm_source=mench&utm_medium=email&utm_campaign=resetpass" target="_blank">Mench</a></div>';

        //Send email:
        $this->Communication_model->user_received_emails(array($_POST['input_email']), $subject, $html_message);

        //Return success
        return echo_json(array(
            'status' => 1,
        ));
    }

    function singin_magic_link_login($ln_id){

        //Validate email:
        if(en_auth(array(1308))){
            return redirect_message('/dashboard');
        } elseif(en_auth()){
            return redirect_message('/actionplan/next');
        } elseif(!isset($_GET['email']) || !filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)){
            //Missing email input:
            return redirect_message('/signin', '<div class="alert alert-danger" role="alert">Missing email address</div>');
        }

        //Validate link ID and matching email:
        $validate_links = $this->Links_model->ln_fetch(array(
            'ln_id' => $ln_id,
            'ln_content' => $_GET['email'],
            'ln_type_entity_id' => 7563, //User Signin Magic Link Email
        )); //The user making the request
        if(count($validate_links) < 1){
            //Probably already completed the reset password:
            return redirect_message('/signin?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert">Invalid data source</div>');
        } elseif(strtotime($validate_links[0]['ln_timestamp']) + $this->config->item('magic_link_expiry') < time()){
            //Probably already completed the reset password:
            return redirect_message('/signin?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert">Magic link has expired. Try again.</div>');
        }

        //Fetch entity:
        $ens = $this->Entities_model->en_fetch(array(
            'en_id' => $validate_links[0]['ln_miner_entity_id'],
        ));
        if(count($ens) < 1){
            return redirect_message('/signin?input_email='.$_GET['email'], '<div class="alert alert-danger" role="alert">User not found</div>');
        }

        //Log them in:
        $is_miner = filter_array($ens[0]['en__parents'], 'en_id', 1308); //Mench Miners
        $this->User_app_model->user_activate_session($ens[0], $is_miner);

        //Take them to next step:
        return redirect_message(( $is_miner ? '/dashboard' : '/actionplan/next' ));
    }

    function singin_check_email(){

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid email address',
            ));
        } elseif (!isset($_POST['referrer_in_id']) || !isset($_POST['referrer_en_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing core data',
            ));
        }


        //Cleanup input email:
        $_POST['input_email'] =  trim(strtolower($_POST['input_email']));


        if(intval($_POST['referrer_in_id']) > 0){
            //Fetch the intent:
            $referrer_ins = $this->Intents_model->in_fetch(array(
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'in_id' => $_POST['referrer_in_id'],
            ));
        } else {
            $referrer_ins = array();
        }


        //Search for email to see if it exists...
        $user_emails = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_content' => $_POST['input_email'],
            'ln_type_entity_id' => 4255, //Linked Entities Text (Email is text)
            'ln_parent_entity_id' => 3288, //Mench Email
        ), array('en_child'));

        if(count($user_emails) > 0){

            return echo_json(array(
                'status' => 1,
                'email_existed_already' => 1,
                'login_en_id' => $user_emails[0]['en_id'],
                'clean_input_email' => $_POST['input_email'],
            ));

        } else {

            return echo_json(array(
                'status' => 1,
                'email_existed_already' => 0,
                'login_en_id' => 0,
                'clean_input_email' => $_POST['input_email'],
            ));

        }
    }


    function page_not_found(){
        $this->load->view('view_user_app/user_app_header', array(
            'session_en' => $this->session->userdata('user'),
            'title' => 'Page not found',
        ));
        $this->load->view('view_user_app/page_not_found');
        $this->load->view('view_user_app/user_app_footer');
    }



    function actionplan($in_id = 0)
    {

        /*
         *
         * Loads user action plans "frame" which would
         * then use JS/Facebook API to determine User
         * PSID which then loads the Action Plan via
         * actionplan_load() function below.
         *
         * */

        $this->load->view('view_user_app/user_app_header', array(
            'title' => '🚩 Action Plan',
        ));
        $this->load->view('view_user_app/actionplan_frame', array(
            'in_id' => $in_id,
        ));
        $this->load->view('view_user_app/user_app_footer', array(
            'hide_social' => 1,
            'show_chat' => 1,
        ));

    }


    function myaccount()
    {
        /*
         *
         * Loads user my account "frame" which would
         * then use JS/Facebook API to determine User
         * PSID which then loads their Account via
         * myaccount_load() function below.
         *
         * */

        $this->load->view('view_user_app/user_app_header', array(
            'title' => '👤 My Account',
        ));
        $this->load->view('view_user_app/myaccount_frame');
        $this->load->view('view_user_app/user_app_footer', array(
            'hide_social' => 1,
            'show_chat' => 1,
        ));
    }


    function myaccount_load($psid)
    {

        /*
         *
         * My Account Web UI used for both Messenger
         * Webview and web-browser login
         *
         * */

        //Authenticate user:
        $session_en = $this->session->userdata('user');
        if (!$psid && !isset($session_en['en_id'])) {
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif (!is_dev_environment() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Failed to authenticate your origin.</div>');
        } elseif (!isset($session_en['en_id'])) {
            //Messenger Webview, authenticate PSID:
            $session_en = $this->Entities_model->en_messenger_auth($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }

        //Log My Account View:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4282, //Opened My Account
            'ln_miner_entity_id' => $session_en['en_id'],
        ));

        //Load UI:
        $this->load->view('view_user_app/myaccount_manage', array(
            'session_en' => $session_en,
        ));

    }


    function signout()
    {
        //Destroys Session
        $this->session->sess_destroy();
        header('Location: /');
    }


    function myaccount_save_full_name()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_name']) || strlen($_POST['en_name']) < 2) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Name must be at-least 2 characters long',
            ));
        }

        //Cleanup:
        $_POST['en_name'] = trim($_POST['en_name']);

        //Check to make sure not duplicate:
        $duplicates = $this->Entities_model->en_fetch(array(
            'en_id !=' => $_POST['en_id'],
            'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
            'LOWER(en_name)' => strtolower($_POST['en_name']),
        ));
        if (count($duplicates) > 0) {
            //This is a duplicate, disallow:
            return echo_json(array(
                'status' => 0,
                'message' => 'Name already in-use. Add a pre-fix or post-fix to make it unique.',
            ));
        }


        //Update name and notify
        $this->Entities_model->en_update($_POST['en_id'], array(
            'en_name' => $_POST['en_name'],
        ), true, $_POST['en_id']);


        //Log Account iteration link type:
        $_POST['account_update_function'] = 'myaccount_save_full_name'; //Add this variable to indicate which My Account function created this link
        $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 6224, //My Account Iterated
            'ln_content' => 'My Account Name Updated:'.$_POST['en_name'],
            'ln_metadata' => $_POST,
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        return echo_json(array(
            'status' => 1,
            'message' => 'Name updated',
        ));
    }


    function myaccount_save_phone(){

        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_phone'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing phone number',
            ));
        } elseif (strlen($_POST['en_phone'])>0 && !is_numeric($_POST['en_phone'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid phone number: numbers only',
            ));
        } elseif (strlen($_POST['en_phone'])>0 && (strlen($_POST['en_phone'])<7 || strlen($_POST['en_phone'])>12)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Phone number must be between 7-12 characters long',
            ));
        }

        if (strlen($_POST['en_phone']) > 0) {

            //Cleanup starting 1:
            if (strlen($_POST['en_phone']) == 11) {
                $_POST['en_phone'] = preg_replace("/^1/", '',$_POST['en_phone']);
            }

            //Check to make sure not duplicate:
            $duplicates = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id' => 4319, //Phone are of type number
                'ln_parent_entity_id' => 4783, //Phone Number
                'ln_child_entity_id !=' => $_POST['en_id'],
                'ln_content' => $_POST['en_phone'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Phone already in-use. Use another number or contact support for assistance.',
                ));
            }
        }


        //Fetch existing phone:
        $user_phones = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4319, //Phone are of type number
            'ln_parent_entity_id' => 4783, //Phone Number
        ));
        if (count($user_phones) > 0) {

            if (strlen($_POST['en_phone']) == 0) {

                //Remove:
                $this->Links_model->ln_update($user_phones[0]['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone Removed',
                );

            } elseif ($user_phones[0]['ln_content'] != $_POST['en_phone']) {

                //Update if not duplicate:
                $this->Links_model->ln_update($user_phones[0]['ln_id'], array(
                    'ln_content' => $_POST['en_phone'],
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Phone Updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Phone Unchanged',
                );

            }

        } elseif (strlen($_POST['en_phone']) > 0) {

            //Create new link:
            $this->Links_model->ln_create(array(
                'ln_status_entity_id' => 6176, //Link Published
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 4319, //Phone are of type number
                'ln_parent_entity_id' => 4783, //Phone Number
                'ln_content' => $_POST['en_phone'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Phone Added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Phone Unchanged',
            );

        }


        //Log Account iteration link type:
        if($return['status']){
            $_POST['account_update_function'] = 'myaccount_save_phone'; //Add this variable to indicate which My Account function created this link
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message']. ( strlen($_POST['en_phone']) > 0 ? ': '.$_POST['en_phone'] : ''),
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }

        return echo_json($return);

    }

    function myaccount_save_email()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_email']) || (strlen($_POST['en_email']) > 0 && !filter_var($_POST['en_email'], FILTER_VALIDATE_EMAIL))) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid email address',
            ));
        }


        if (strlen($_POST['en_email']) > 0) {
            //Cleanup:
            $_POST['en_email'] = trim(strtolower($_POST['en_email']));

            //Check to make sure not duplicate:
            $duplicates = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
                'ln_type_entity_id' => 4255, //Emails are of type Text
                'ln_parent_entity_id' => 3288, //Mench Email
                'ln_child_entity_id !=' => $_POST['en_id'],
                'LOWER(ln_content)' => $_POST['en_email'],
            ));
            if (count($duplicates) > 0) {
                //This is a duplicate, disallow:
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Email already in-use. Use another email or contact support for assistance.',
                ));
            }
        }


        //Fetch existing email:
        $user_emails = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_child_entity_id' => $_POST['en_id'],
            'ln_type_entity_id' => 4255, //Emails are of type Text
            'ln_parent_entity_id' => 3288, //Mench Email
        ));
        if (count($user_emails) > 0) {

            if (strlen($_POST['en_email']) == 0) {

                //Remove email:
                $this->Links_model->ln_update($user_emails[0]['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Email removed',
                );

            } elseif ($user_emails[0]['ln_content'] != $_POST['en_email']) {

                //Update if not duplicate:
                $this->Links_model->ln_update($user_emails[0]['ln_id'], array(
                    'ln_content' => $_POST['en_email'],
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Email updated',
                );

            } else {

                $return = array(
                    'status' => 0,
                    'message' => 'Email unchanged',
                );

            }

        } elseif (strlen($_POST['en_email']) > 0) {

            //Create new link:
            $this->Links_model->ln_create(array(
                'ln_status_entity_id' => 6176, //Link Published
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 4255, //Emails are of type Text
                'ln_parent_entity_id' => 3288, //Mench Email
                'ln_content' => $_POST['en_email'],
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Email added',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Email unchanged',
            );

        }


        if($return['status']){
            //Log Account iteration link type:
            $_POST['account_update_function'] = 'myaccount_save_email'; //Add this variable to indicate which My Account function created this link
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message']. ( strlen($_POST['en_email']) > 0 ? ': '.$_POST['en_email'] : ''),
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }


        //Return results:
        return echo_json($return);


    }


    function myaccount_update_password()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['input_password']) || strlen($_POST['input_password']) < $this->config->item('password_min_char')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'New password must be '.$this->config->item('password_min_char').' characters or more',
            ));
        }


        //Fetch existing password:
        $user_passwords = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4255, //Passwords are of type Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['input_password'] . $_POST['en_id']));


        if (count($user_passwords) > 0) {

            if ($hashed_password == $user_passwords[0]['ln_content']) {

                $return = array(
                    'status' => 0,
                    'message' => 'Password Unchanged',
                );

            } else {

                //Update password:
                $this->Links_model->ln_update($user_passwords[0]['ln_id'], array(
                    'ln_content' => $hashed_password,
                ), $_POST['en_id']);

                $return = array(
                    'status' => 1,
                    'message' => 'Password Updated',
                );

            }

        } else {

            //Create new link:
            $this->Links_model->ln_create(array(
                'ln_status_entity_id' => 6176, //Link Published
                'ln_type_entity_id' => 4255, //Passwords are of type Text
                'ln_parent_entity_id' => 3286, //Password
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_child_entity_id' => $_POST['en_id'],
                'ln_content' => $hashed_password,
            ), true);

            $return = array(
                'status' => 1,
                'message' => 'Password Added',
            );

        }


        //Log Account iteration link type:
        if($return['status']){
            $_POST['account_update_function'] = 'myaccount_update_password'; //Add this variable to indicate which My Account function created this link
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$return['message'],
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));
        }


        //Return results:
        return echo_json($return);

    }


    function myaccount_save_social_profiles()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['social_profiles']) || !is_array($_POST['social_profiles']) || count($_POST['social_profiles']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing social profiles',
            ));
        }

        $en_all_6123 = $this->config->item('en_all_6123');

        //Loop through and validate social profiles:
        $success_messages = '';
        foreach ($_POST['social_profiles'] as $social_profile) {


            //Validate to make sure either nothing OR URL:
            $social_en_id = intval($social_profile[0]);
            $social_url = trim($social_profile[1]);
            $profile_set = ( strlen($social_url) > 0 ? true : false );


            //This profile already added for this user, are we updating or removing?
            if ($profile_set) {

                //Valiodate URL and make sure it matches:
                $is_valid_url = false;
                if (filter_var($social_url, FILTER_VALIDATE_URL)) {
                    //Check to see if it's from the same domain and not in use:
                    $domain_entity = $this->Entities_model->en_sync_domain($social_url);
                    if ($domain_entity['domain_already_existed'] && isset($domain_entity['en_domain']['en_id']) && $domain_entity['en_domain']['en_id'] == $social_en_id) {
                        //Seems to be a valid domain for this social profile:
                        $is_valid_url = true;
                    }
                }

                if (!$is_valid_url) {
                    return echo_json(array(
                        'status' => 0,
                        'message' => 'Invalid ' . $en_all_6123[$social_en_id]['m_name'] . ' URL',
                    ));
                }
            }


            //Does this user have a social URL already?
            $social_url_exists = $this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 4256, //Generic URL
                'ln_parent_entity_id' => $social_en_id,
                'ln_child_entity_id' => $_POST['en_id'],
            ));

            if (count($social_url_exists) > 0) {

                //Make sure not for another entity:
                if ($social_url_exists[0]['ln_child_entity_id'] != $_POST['en_id']) {
                    return echo_json(array(
                        'status' => 0,
                        'message' => $en_all_6123[$social_en_id]['m_name'] . ' URL already taken by another entity.',
                    ));
                }

                //This profile already added for this user, are we updating or removing?
                if ($profile_set && $social_url_exists[0]['ln_content'] != $social_url) {

                    //Check to make sure not duplicate
                    $duplicates = $this->Links_model->ln_fetch(array(
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_type_entity_id' => 4256, //Generic URL
                        'ln_parent_entity_id' => $social_en_id,
                        'ln_child_entity_id !=' => $_POST['en_id'],
                        'ln_content' => $social_url,
                    ));
                    if(count($duplicates) > 0){
                        return echo_json(array(
                            'status' => 0,
                            'message' => 'Duplicates',
                        ));
                    }

                    //Update profile since different:
                    $this->Links_model->ln_update($social_url_exists[0]['ln_id'], array(
                        'ln_content' => $social_url,
                    ), $_POST['en_id']);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Updated. ';

                } elseif(!$profile_set) {

                    //Remove profile:
                    $this->Links_model->ln_update($social_url_exists[0]['ln_id'], array(
                        'ln_status_entity_id' => 6173, //Link Removed
                    ), $_POST['en_id']);

                    $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Removed. ';

                } else {



                }

            } elseif ($profile_set) {

                //Create new link:
                $this->Links_model->ln_create(array(
                    'ln_status_entity_id' => 6176, //Link Published
                    'ln_miner_entity_id' => $_POST['en_id'],
                    'ln_child_entity_id' => $_POST['en_id'],
                    'ln_type_entity_id' => 4256, //Generic URL
                    'ln_parent_entity_id' => $social_en_id,
                    'ln_content' => $social_url,
                ), true);

                $success_messages .= $en_all_6123[$social_en_id]['m_name'] . ' Added. ';

            }
        }

        if(strlen($success_messages) > 0){

            //Log Account iteration link type:
            $_POST['account_update_function'] = 'myaccount_save_social_profiles'; //Add this variable to indicate which My Account function created this link
            $this->Links_model->ln_create(array(
                'ln_miner_entity_id' => $_POST['en_id'],
                'ln_type_entity_id' => 6224, //My Account Iterated
                'ln_content' => 'My Account '.$success_messages,
                'ln_metadata' => $_POST,
                'ln_child_entity_id' => $_POST['en_id'],
            ));

            //All good, return combined success messages:
            return echo_json(array(
                'status' => 1,
                'message' => $success_messages,
            ));

        } else {

            //All good, return combined success messages:
            return echo_json(array(
                'status' => 0,
                'message' => 'Social Profiles Unchanged',
            ));

        }



    }

    function actionplan_intention_add(){

        /*
         *
         * The Ajax function to add an intention to the Action Plan from the landing page.
         *
         * */

        //Validate input:
        $session_en = en_auth();
        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the Page to Continue',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Intent ID',
            ));
        }

        //Attempt to add intent to Action Plan:
        if($this->User_app_model->actionplan_intention_add($session_en['en_id'], $_POST['in_id'], 0, false)){
            //All good:
            $en_all_7369 = $this->config->item('en_all_7369');
            return echo_json(array(
                'status' => 1,
                'message' => '<i class="far fa-check-circle"></i> Added to your '.$en_all_7369[6138]['m_icon'].' '.$en_all_7369[6138]['m_name'],
                'add_redirect' => '/actionplan/'.$_POST['in_id'],
            ));
        } else {
            //There was some error:
            return echo_json(array(
                'status' => 0,
                'message' => 'Unable to add to Action Plan',
            ));
        }

    }




    function actionplan_reset_progress($en_id, $timestamp, $secret_key){

        if($secret_key != md5($en_id . $this->config->item('actionplan_salt') . $timestamp)){
            die('Invalid Secret Key');
        }


        //Define what needs to be cleared:
        $clear_links = array_merge(
            $this->config->item('en_ids_6146'), //User Steps Completed
            $this->config->item('en_ids_4229') //Intent Link Locked Step
        );

        //Fetch their current progress links:
        $progress_links = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id IN (' . join(',', $clear_links) . ')' => null,
            'ln_miner_entity_id' => $en_id,
        ), array(), 0);

        if(count($progress_links) > 0){

            //Yes they did have some:
            $message = count($progress_links).' Action Plan progression link'.echo__s(count($progress_links)).' removed';

            //Log link:
            $clear_all_link = $this->Links_model->ln_create(array(
                'ln_content' => $message,
                'ln_type_entity_id' => 6415, //Action Plan Reset Steps
                'ln_miner_entity_id' => $en_id,
            ));

            //Remove all progressions:
            foreach($progress_links as $progress_link){
                $this->Links_model->ln_update($progress_link['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                    'ln_parent_link_id' => $clear_all_link['ln_id'], //To indicate when it was removed
                ), $en_id);
            }

        } else {

            //Nothing to do:
            $message = 'Nothing to delete...';

        }

        //Show basic UI for now:
        echo $message;
        echo '<div><a href="/actionplan" style="font-weight: bold; font-size: 1.4em; margin-top: 10px;">Go Back</a></div>';

    }


    function actionplan_load($psid, $in_id)
    {

        /*
         *
         * Action Plan Web UI used for both Messenger
         * Webview and web-browser login
         *
         * */

        //Authenticate user:
        $session_en = $this->session->userdata('user');
        if (!$psid && !isset($session_en['en_id'])) {
            die('<div class="alert alert-danger" role="alert">Invalid Credentials</div>');
        } elseif (!is_dev_environment() && isset($_GET['sr']) && !parse_signed_request($_GET['sr'])) {
            die('<div class="alert alert-danger" role="alert">Failed to authenticate your origin.</div>');
        } elseif (!isset($session_en['en_id'])) {
            //Messenger Webview, authenticate PSID:
            $session_en = $this->Entities_model->en_messenger_auth($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }


        //This is a special command to find the next intent:
        if($in_id=='next'){

            //See if we have pending messages:
            $pending_messages = $this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $session_en['en_id'],
                'ln_type_entity_id' => 4570, //User Received Email Message
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            ), array(), 0, 0, array('ln_id' => 'ASC'));

            //Find the next item to navigate them to:
            $next_in_id = $this->User_app_model->actionplan_step_next_go($session_en['en_id'], false);
            $in_id = ( $next_in_id > 0 ? $next_in_id : 0 );

        } else {

            $pending_messages = array();
            $in_id = intval($in_id);

        }


        //Did we find any pending messages?
        if(count($pending_messages) > 0){

            foreach($pending_messages as $pending_message){
                //Update this message status to delivered:
                $this->Links_model->ln_update($pending_message['ln_id'], array(
                    'ln_status_entity_id' => 6176 /* Link Published */,
                ), $session_en['en_id']);
            }

            //Show pending messages:
            $this->load->view('view_user_app/actionplan_pending_messages', array(
                'pending_messages' => $pending_messages,
            ));

        } else {

            //Fetch user's intentions as we'd need to know their top-level goals:
            $user_intents = $this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $session_en['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
                'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
            ), array('in_parent'), 0, 0, array('ln_order' => 'ASC'));

            //Show appropriate UI:
            if ($in_id < 1) {

                //Log Action Plan View:
                $this->Links_model->ln_create(array(
                    'ln_type_entity_id' => 4283, //Opened Action Plan
                    'ln_miner_entity_id' => $session_en['en_id'],
                ));

                //List all user intentions:
                $this->load->view('view_user_app/actionplan_intentions', array(
                    'session_en' => $session_en,
                    'user_intents' => $user_intents,
                ));

            } else {

                //Fetch/validate selected intent:
                $ins = $this->Intents_model->in_fetch(array(
                    'in_id' => $in_id,
                ));

                if (count($ins) < 1) {
                    die('<div class="alert alert-danger" role="alert">Invalid Intent ID.</div>');
                } elseif (!in_array($ins[0]['in_status_entity_id'], $this->config->item('en_ids_7355') /* Intent Statuses Public */)) {
                    die('<div class="alert alert-danger" role="alert">Intent is not made public yet.</div>');
                }

                //Load Action Plan UI with relevant variables:
                $this->load->view('view_user_app/actionplan_step', array(
                    'session_en' => $session_en,
                    'user_intents' => $user_intents,
                    'advance_step' => $this->User_app_model->actionplan_step_next_echo($session_en['en_id'], $in_id, false),
                    'in' => $ins[0], //Currently focused intention:
                ));

            }
        }
    }


    function actionplan_stop_save(){

        /*
         *
         * When users indicate they want to stop
         * an intention this function saves the changes
         * necessary and remove the intention from their
         * Action Plan.
         *
         * */


        if (!isset($_POST['en_miner_id']) || intval($_POST['en_miner_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid miner ID',
            ));
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing intent ID',
            ));
        } elseif (!isset($_POST['stop_method_id']) || intval($_POST['stop_method_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing stop method',
            ));
        } elseif (!isset($_POST['stop_feedback'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing feedback input',
            ));
        }

        //Validate intention to be removed:
        $ins = $this->Intents_model->in_fetch(array(
            'in_id' => $_POST['in_id'],
        ));
        if (count($ins) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid intention',
            ));
        }

        //Go ahead and remove from Action Plan:
        $user_intents = $this->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
            'ln_parent_intent_id' => $_POST['in_id'],
        ));
        if(count($user_intents) < 1){
            //Give error:
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not locate Action Plan',
            ));
        }

        //Adjust Action Plan status:
        foreach($user_intents as $ln){
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_status_entity_id' => ( $_POST['stop_method_id']==6154 ? 6176 /* Link Published */ : 6173 /* Link Removed */ ), //This is a nasty HACK!
            ), $_POST['en_miner_id']);
        }

        //Log related link:
        $this->Links_model->ln_create(array(
            'ln_content' => $_POST['stop_feedback'],
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_type_entity_id' => $_POST['stop_method_id'],
            'ln_parent_intent_id' => $_POST['in_id'],
        ));

        //Communicate with user:
        $this->Communication_model->dispatch_message(
            'I have successfully removed the intention to '.$ins[0]['in_outcome'].' from your Action Plan. You can add it back to your Action Plan at any time and continue from where you left off.',
            array('en_id' => $_POST['en_miner_id']),
            true,
            array(
                array(
                    'content_type' => 'text',
                    'title' => 'Next',
                    'payload' => 'GONEXT',
                )
            )
        );

        return echo_json(array(
            'status' => 1,
        ));

    }


    function actionplan_skip_preview($en_id, $in_id)
    {

        //Just give them an overview of what they are about to skip:
        return echo_json(array(
            'skip_step_preview' => 'WARNING: '.$this->User_app_model->actionplan_step_skip_initiate($en_id, $in_id, false).' Are you sure you want to skip?',
        ));

    }

    function actionplan_skip_apply($en_id, $in_id)
    {

        //Actually go ahead and skip
        $this->User_app_model->actionplan_step_skip_apply($en_id, $in_id);
        //Assume its all good!

        //We actually skipped, draft message:
        $message = '<div class="alert alert-success" role="alert">I successfully skipped all steps.</div>';

        //Find the next item to navigate them to:
        $next_in_id = $this->User_app_model->actionplan_step_next_go($en_id, false);
        if ($next_in_id > 0) {
            return redirect_message('/actionplan/' . $next_in_id, $message);
        } else {
            return redirect_message('/actionplan', $message);
        }

    }

    function myaccount_radio_update()
    {
        /*
         *
         * Saves the radio selection of some account fields
         * that are displayed using echo_radio_entities()
         *
         * */

        if (!isset($_POST['en_miner_id']) || intval($_POST['en_miner_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid miner ID',
            ));
        } elseif (!isset($_POST['parent_en_id']) || intval($_POST['parent_en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing parent entity',
            ));
        } elseif (!isset($_POST['selected_en_id']) || intval($_POST['selected_en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing selected entity',
            ));
        } elseif (!isset($_POST['enable_mulitiselect']) || !isset($_POST['was_already_selected'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing multi-select setting',
            ));
        }

        //Used when the user subscribed back to us:
        $greet_them_back = false;

        if(!$_POST['enable_mulitiselect'] || $_POST['was_already_selected']){
            //Since this is not a multi-select we want to remove all existing options...

            //Fetch all possible answers based on parent entity:
            $filters = array(
                'ln_parent_entity_id' => $_POST['parent_en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
            );

            if($_POST['enable_mulitiselect'] && $_POST['was_already_selected']){
                //Just remove this single item, not the other ones:
                $filters['ln_child_entity_id'] = $_POST['selected_en_id'];
            }

            //List all possible answers:
            $possible_answers = array();
            foreach($this->Links_model->ln_fetch($filters, array('en_child'), 0, 0) as $answer_en){
                array_push($possible_answers, $answer_en['en_id']);
            }

            //Remove selected options for this miner:
            foreach($this->Links_model->ln_fetch(array(
                'ln_parent_entity_id IN (' . join(',', $possible_answers) . ')' => null,
                'ln_child_entity_id' => $_POST['en_miner_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )) as $remove_en){

                //Does this have to do with changing Subscription Type? We need to confirm with them if so:
                if($_POST['parent_en_id']==4454){
                    if($_POST['selected_en_id']==4455){
                        //They just unsubscribed, send them a message before its too late (changing their status):
                        $this->Communication_model->dispatch_message(
                            'This is a confirmation that you are now unsubscribed from Mench and I will not longer send you any messages. You can resume your subscription later by going to MY ACCOUNT > SUBSCRIPTION TYPE > Set Notification',
                            array('en_id' => $_POST['en_miner_id']),
                            true
                        );
                    } elseif($remove_en['ln_parent_entity_id']==4455){
                        //They used to be ub-subscribed, now they join back, confirm with them AFTER we update their settings:
                        $greet_them_back = true;
                    }
                }

                //Should usually remove a single option:
                $this->Links_model->ln_update($remove_en['ln_id'], array(
                    'ln_status_entity_id' => 6173, //Link Removed
                ), $_POST['en_miner_id']);
            }

        }

        //Add new option if not already there:
        if(!$_POST['enable_mulitiselect'] || !$_POST['was_already_selected']){
            $this->Links_model->ln_create(array(
                'ln_parent_entity_id' => $_POST['selected_en_id'],
                'ln_child_entity_id' => $_POST['en_miner_id'],
                'ln_miner_entity_id' => $_POST['en_miner_id'],
                'ln_type_entity_id' => 4230, //Raw
                'ln_status_entity_id' => 6176, //Link Published
            ));
        }

        if($greet_them_back){
            //Now we can communicate with them again:
            $this->Communication_model->dispatch_message(
                'Welcome back 🎉🎉🎉 This is a confirmation that you are now re-subscribed and I will continue to work with you on your Acion Plan intentions',
                array('en_id' => $_POST['en_miner_id']),
                true
            );
        }


        //Log Account iteration link type:
        $_POST['account_update_function'] = 'myaccount_radio_update'; //Add this variable to indicate which My Account function created this link
        $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_type_entity_id' => 6224, //My Account Iterated
            'ln_content' => 'My Account '.( $_POST['enable_mulitiselect'] ? 'Multi' : 'Single' ).'-Select Radio Field '.( $_POST['was_already_selected'] ? 'Removed' : 'Added' ),
            'ln_metadata' => $_POST,
            'ln_parent_entity_id' => $_POST['parent_en_id'],
            'ln_child_entity_id' => $_POST['selected_en_id'],
        ));

        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => 'Updated', //Note: NOT shown in UI
        ));
    }

    function actionplan_sort_save()
    {
        /*
         *
         * Saves the order of Action Plan intents based on
         * user preferences.
         *
         * */

        if (!isset($_POST['en_miner_id']) || intval($_POST['en_miner_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid miner ID',
            ));
        } elseif (!isset($_POST['new_actionplan_order']) || !is_array($_POST['new_actionplan_order']) || count($_POST['new_actionplan_order']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing sorting intents',
            ));
        }


        //Update the order of their Action Plan:
        $results = array();
        foreach($_POST['new_actionplan_order'] as $ln_order => $ln_id){
            if(intval($ln_id) > 0 && intval($ln_order) > 0){
                //Update order of this link:
                $results[$ln_order] = $this->Links_model->ln_update(intval($ln_id), array(
                    'ln_order' => $ln_order,
                ), $_POST['en_miner_id']);
            }
        }


        //Save sorting results:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 6132, //Action Plan Sorted
            'ln_miner_entity_id' => $_POST['en_miner_id'],
            'ln_metadata' => array(
                'new_order' => $_POST['new_actionplan_order'],
                'results' => $results,
            ),
        ));


        //Fetch top intention that being workined on now:
        $top_priority = $this->User_app_model->actionplan_intention_focus($_POST['en_miner_id']);
        if($top_priority){
            //Communicate top-priority with user:
            $this->Communication_model->dispatch_message(
                '🚩 Action Plan prioritised: Now our focus is to '.$top_priority['in']['in_outcome'].' ('.$top_priority['completion_rate']['completion_percentage'].'% done)',
                array('en_id' => $_POST['en_miner_id']),
                true,
                array(
                    array(
                        'content_type' => 'text',
                        'title' => 'Next',
                        'payload' => 'GONEXT',
                    )
                )
            );
        }


        //All good:
        return echo_json(array(
            'status' => 1,
            'message' => count($_POST['new_actionplan_order']).' Intents Sorted',
        ));
    }


    function actionplan_answer_question($answer_type_en_id, $en_id, $parent_in_id, $answer_in_id, $w_key)
    {

        if ($w_key != md5($this->config->item('actionplan_salt') . $answer_in_id . $parent_in_id . $en_id)) {
            return redirect_message('/actionplan/' . $parent_in_id, '<div class="alert alert-danger" role="alert">Invalid Authentication Key</div>');
        } elseif (!in_array($answer_type_en_id, $this->config->item('en_ids_7704'))) {
            return redirect_message('/actionplan/' . $parent_in_id, '<div class="alert alert-danger" role="alert">Invalid answer type</div>');
        }

        //Validate Answer Intent:
        $answer_ins = $this->Intents_model->in_fetch(array(
            'in_id' => $answer_in_id,
            'in_status_entity_id IN (' . join(',', $this->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ));
        if (count($answer_ins) < 1) {
            return redirect_message('/actionplan/' . $parent_in_id, '<div class="alert alert-danger" role="alert">Invalid Answer</div>');
        }

        //Fetch current progression links, if any:
        $current_progression_links = $this->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
            'ln_miner_entity_id' => $en_id,
            'ln_parent_intent_id' => $parent_in_id,
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ));

        //All good, save chosen OR path
        $new_progression_link = $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $en_id,
            'ln_type_entity_id' => $answer_type_en_id,
            'ln_parent_intent_id' => $parent_in_id,
            'ln_child_intent_id' => $answer_in_id,
            'ln_status_entity_id' => 6176, //Link Published
        ));

        //See if we also need to mark the child as complete:
        $this->User_app_model->actionplan_completion_auto_complete($en_id, $answer_ins[0], 7485 /* User Step Answer Unlock */);

        //Archive current progression links:
        foreach($current_progression_links as $ln){
            $this->Links_model->ln_update($ln['ln_id'], array(
                'ln_parent_link_id' => $new_progression_link['ln_id'],
                'ln_status_entity_id' => 6173, //Link Removed
            ), $en_id);
        }

        return redirect_message('/actionplan/next', '<p><i class="far fa-check-circle"></i> I saved your answer.</p>');

    }

}