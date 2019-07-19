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
        ));
        $this->load->view('view_user_app/user_app_footer', array(
            'hide_footer' => 1,
        ));

    }

    function singin_check_password(){

        return echo_json(array(
            'status' => 0,
            'message' => 'Invalid password',
        ));

    }

    function singin_search_email(){

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid email address',
            ));
        } elseif (!isset($_POST['referrer_url']) || !isset($_POST['referrer_in_id']) || !isset($_POST['referrer_en_id']) || !isset($_POST['password_reset'])) {
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
            'ln_parent_entity_id' => 3288, //Email Address
        ), array('en_child'));

        if(count($user_emails) > 0){

            if($_POST['password_reset']){

                //Log email search attempt:
                $reset_link = $this->Links_model->ln_create(array(
                    'ln_type_entity_id' => 7563, //User Signin on Website Forgot Password
                    'ln_content' => $_POST['input_email'],
                    'ln_miner_entity_id' => $user_emails[0]['en_id'], //User making request
                    'ln_parent_intent_id' => intval($_POST['referrer_in_id']),
                    'ln_parent_entity_id' => intval($_POST['referrer_en_id']),
                ));

                //This is a new email, send invitation to join:
                $setpassword_url = 'https://mench.com/resetpassword/' . $reset_link['ln_id'] . '?email='.$_POST['input_email'];

                ##Email Subject
                $subject = 'Reset your Mench Password';

                ##Email Body
                $html_message = '<div>Hi '.$user_emails[0]['en_name'].' :) </div><br />';
                $html_message .= '<div>You can reset your Mench password using this link:</div><br />';
                $html_message .= '<div><a href="'.$setpassword_url.'" target="_blank">' . $setpassword_url . '</a></div>';
                $html_message .= '<br />';
                $html_message .= '<div>If you did not make this request you can ignore this email.</div><br />';
                $html_message .= '<br /><br />';
                $html_message .= '<div>Cheers,</div><br />';
                $html_message .= '<div>Team Mench</div>';
                $html_message .= '<div><a href="https://mench.com?utm_source=mench&utm_medium=email&utm_campaign=resetpass" target="_blank">mench.com</a></div>';

                //Send email:
                $this->Communication_model->dispatch_email(array($_POST['input_email']), array(), $subject, $html_message);
            }

            return echo_json(array(
                'status' => 1,
                'email_existed_already' => 1,
                'login_en_id' => 0,
                'clean_input_email' => $_POST['input_email'],
            ));

        } else {

            //Log email search attempt:
            $invite_link = $this->Links_model->ln_create(array(
                'ln_type_entity_id' => 7562, //User Signin on Website New Email
                'ln_content' => $_POST['input_email'],
                'ln_miner_entity_id' => 1, //Shervin/Developer
                'ln_parent_intent_id' => intval($_POST['referrer_in_id']),
                'ln_parent_entity_id' => intval($_POST['referrer_en_id']),
            ));

            //This is a new email, send invitation to join:
            $setpassword_url = 'https://mench.com/setpassword/' . $invite_link['ln_id'] . '?email='.$_POST['input_email'];
            $preset_intention = ( count($referrer_ins) > 0 ? ' to '.echo_in_outcome($referrer_ins[0]['in_outcome'], true) : '' );

            ##Email Subject
            $subject = 'Join Mench' . $preset_intention;

            ##Email Body
            $html_message = '<div>Welcome :) </div><br />';
            $html_message .= '<div>I\'m Mench, an open-source personal assistant focused on connecting top talent to their dream jobs.</div><br />';
            $html_message .= '<div>Complete your registration'.$preset_intention.' using this link:</div><br />';
            $html_message .= '<div><a href="'.$setpassword_url.'" target="_blank">' . $setpassword_url . '</a></div>';
            $html_message .= '<br />';
            $html_message .= '<div>If you did not make this request you can ignore this email.</div><br />';
            $html_message .= '<br /><br />';
            $html_message .= '<div>Cheers,</div><br />';
            $html_message .= '<div>Team Mench</div>';
            $html_message .= '<div><a href="https://mench.com?utm_source=mench&utm_medium=email&utm_campaign=signup" target="_blank">mench.com</a></div>';

            //Send email:
            $this->Communication_model->dispatch_email(array($_POST['input_email']), array(), $subject, $html_message);


            return echo_json(array(
                'status' => 1,
                'email_existed_already' => 0,
                'login_en_id' => 0,
                'clean_input_email' => $_POST['input_email'],
            ));


        }
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


    function password_reset()
    {
        $data = array(
            'title' => 'Password Reset',
        );
        $this->load->view('view_user_app/user_app_header', $data);
        $this->load->view('view_user_app/password_reset');
        $this->load->view('view_user_app/user_app_footer', array(
            'hide_social' => 1,
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
            $session_en = $this->Entities_model->en_psid_check($psid);
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

    function user_login()
    {
        //Check to see if they are already logged in?
        $session_en = $this->session->userdata('user');
        if (isset($session_en['en__parents'][0]) && filter_array($session_en['en__parents'], 'en_id', 1308)) {
            //Lead miner and above, go to console:
            return redirect_message('/dashboard');
        }

        $this->load->view('view_user_app/user_app_header', array(
            'title' => 'Sign In',
        ));
        $this->load->view('view_user_app/user_login');
        $this->load->view('view_user_app/user_app_footer', array(
            'hide_social' => 1,
        ));
    }

    function login_process()
    {

        //Setting for admin Sign Ins:

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
        } elseif (!isset($_POST['en_password'])) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
        }

        //Validate user email:
        $lns = $this->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => 3288, //Primary email
            'LOWER(ln_content)' => strtolower($_POST['input_email']),
        ));

        if (count($lns) == 0) {
            //Not found!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: ' . $_POST['input_email'] . ' not found.</div>');
        } elseif (!in_array($lns[0]['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Your email link is not public. Contact us to adjust your account.</div>');
        }


        //Fetch full entity data with their active Action Plans:
        $ens = $this->Entities_model->en_fetch(array(
            'en_id' => $lns[0]['ln_child_entity_id'],
        ));
        if (!in_array($ens[0]['en_status_entity_id'], $this->config->item('en_ids_7357') /* Entity Statuses Public */)) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Your account entity is not public. Contact us to adjust your account.</div>');
        }

        //Authenticate their password:
        $user_passwords = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4255, //Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $ens[0]['en_id'],
        ));
        if (count($user_passwords) == 0) {
            //They do not have a password assigned yet!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.</div>');
        } elseif (!in_array($user_passwords[0]['ln_status_entity_id'], $this->config->item('en_ids_7359') /* Link Statuses Public */)) {
            //They do not have a password assigned yet!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Password link is not public. Contact us to adjust your account.</div>');
        } elseif ($user_passwords[0]['ln_content'] != strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['en_password'] . $ens[0]['en_id']))) {
            //Bad password
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Incorrect password for [' . $_POST['input_email'] . ']</div>');
        }

        //Now let's do a few more checks:


        $session_data = array();
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false);


        $is_user = filter_array($ens[0]['en__parents'], 'en_id', 4430); //Mench Users
        $is_miner = filter_array($ens[0]['en__parents'], 'en_id', 1308); //Mench Miners
        $is_partner_employee = filter_array($ens[0]['en__parents'], 'en_id', 7512); //Mench Partner Employees


        //Applicable for anyone using the Mench mining app:
        if (!$is_chrome && ($is_miner || $is_partner_employee)) {

            $this->Links_model->ln_create(array(
                'ln_content' => 'User failed to login using non-Chrome browser',
                'ln_type_entity_id' => 7504, //Admin Review Required
                'ln_child_entity_id' => $ens[0]['en_id'],
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));

            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Mining console requires <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> to properly function. Your mining permissions have been removed but you still have access to your Action Plan and Account.</div>');

        }


        //Assign user details:
        $session_data['user'] = $ens[0];


        //Are they miner? Give them Sign In access:
        if ($is_miner) {

            //Check their advance mode status:
            $last_advance_settings = $this->Links_model->ln_fetch(array(
                'ln_miner_entity_id' => $ens[0]['en_id'],
                'ln_type_entity_id' => 5007, //Toggled Advance Mode
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            ), array(), 1, 0, array('ln_id' => 'DESC'));

            //They have admin rights:
            $session_data['user_default_intent'] = $this->config->item('in_focus_id');
            $session_data['user_session_count'] = 0;
            $session_data['advance_view_enabled'] = ( count($last_advance_settings) > 0 && substr_count($last_advance_settings[0]['ln_content'] , ' ON')==1 ? 1 : 0 );

        } elseif($is_partner_employee){

            //Determine their company:
            $session_data['user_default_intent'] = 0; //TBD

            foreach($this->Links_model->ln_fetch(array(
                'ln_parent_entity_id' => $ens[0]['en_id'],
                'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'en_status_entity_id IN (' . join(',', $this->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
            ), array('en_child'), 0) as $employee_child){

                //Is this child a Partner Mench Company?
                if(count($this->Links_model->ln_fetch(array(
                        'ln_child_entity_id' => $employee_child['en_id'],
                        'ln_parent_entity_id' => 6695, //Mench Partner Companies
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    ))) > 0){

                    //This is it, find the top intention and terminate loop:
                    $company_intentions = $this->Links_model->ln_fetch(array(
                        'ln_miner_entity_id' => $employee_child['en_id'],
                        'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
                        'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7364')) . ')' => null, //Link Statuses Incomplete
                    ), array(), 0, 0, array('ln_order' => 'ASC'));

                    if(count($company_intentions) > 0){
                        //The first intent is the default one:
                        $session_data['user_default_intent'] = intval($company_intentions[0]['ln_parent_intent_id']);
                        break;
                    }
                }
            }

            //Make sure we found companies top intent:
            if($session_data['user_default_intent'] == 0){

                $this->Links_model->ln_create(array(
                    'ln_content' => 'Unable to locate your companies intention. Make sure user logged in with Chrome or get back to them',
                    'ln_type_entity_id' => 7504, //Admin Review Required
                    'ln_child_entity_id' => $ens[0]['en_id'],
                    'ln_miner_entity_id' => 1, //Shervin/Developer
                ));

                return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Unable to locate your companies intention</div>');
            }


            //They have admin rights:
            $session_data['user_session_count'] = 0;
            $session_data['advance_view_enabled'] = 0;

        }


        //Append user IP and agent information
        if (isset($_POST['en_password'])) {
            unset($_POST['en_password']); //Sensitive information to be removed and NOT logged
        }

        //Log additional information:
        $ens[0]['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $ens[0]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $ens[0]['input_post_data'] = $_POST;


        //Log Sign In Link:
        $this->Links_model->ln_create(array(
            'ln_miner_entity_id' => $ens[0]['en_id'],
            'ln_metadata' => $ens[0],
            'ln_type_entity_id' => 4269, //User Login
        ));

        //All good to go!
        //Load session and redirect:
        $this->session->set_userdata($session_data);


        if (isset($_POST['url']) && strlen($_POST['url']) > 0) {
            header('Location: ' . $_POST['url']);
        } else {
            //Default:
            if ($is_miner) {
                //Go to Mench dashboard:
                header('Location: /dashboard');
            } elseif ($is_partner_employee) {
                //Go to their default intent:
                header('Location: /intents/' . $session_data['user_default_intent']);
            } elseif ($is_user) {
                //Go to user Action Plan:
                header('Location: /actionplan');
            }
        }
    }


    function logout()
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
                'ln_parent_entity_id' => 3288, //Email Address
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
            'ln_parent_entity_id' => 3288, //Email Address
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
                'ln_parent_entity_id' => 3288, //Email Address
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


    function myaccount_save_password()
    {


        if (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity ID',
            ));
        } elseif (!isset($_POST['en_password']) || strlen($_POST['en_password']) < 4) {
            return echo_json(array(
                'status' => 0,
                'message' => 'New password must be 4 characters or more',
            ));
        }


        //Fetch existing password:
        $user_passwords = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_entity_id' => 4255, //Passwords are of type Text
            'ln_parent_entity_id' => 3286, //Password
            'ln_child_entity_id' => $_POST['en_id'],
        ));

        $hashed_password = strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['en_password'] . $_POST['en_id']));


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
            $_POST['account_update_function'] = 'myaccount_save_password'; //Add this variable to indicate which My Account function created this link
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
        if($this->User_app_model->actionplan_intention_add($session_en['en_id'], $_POST['in_id'])){
            //All good:
            $en_all_7369 = $this->config->item('en_all_7369');
            return echo_json(array(
                'status' => 1,
                'message' => '<i class="far fa-check-circle"></i> Successfully added to your <b><a href="/actionplan">'.$en_all_7369[6138]['m_icon'].' '.$en_all_7369[6138]['m_name'].'</a></b>',
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

        //Fetch their current progress links:
        $progress_links = $this->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id IN (' . join(',', $this->config->item('en_ids_6146')) . ')' => null, //User Steps Completed
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
            $session_en = $this->Entities_model->en_psid_check($psid);
            //Make sure we found them:
            if (!$session_en) {
                //We could not authenticate the user!
                die('<div class="alert alert-danger" role="alert">Credentials could not be validated</div>');
            }
        }


        //This is a special command to find the next intent:
        if($in_id=='next'){
            //Find the next item to navigate them to:
            $next_in_id = $this->User_app_model->actionplan_step_next_go($session_en['en_id'], false);
            $in_id = ( $next_in_id > 0 ? $next_in_id : 0 );
        }


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


    function actionplan_answer_question($en_id, $parent_in_id, $answer_in_id, $w_key)
    {

        if ($w_key != md5($this->config->item('actionplan_salt') . $answer_in_id . $parent_in_id . $en_id)) {
            return redirect_message('/actionplan/' . $parent_in_id, '<div class="alert alert-danger" role="alert">Invalid Authentication Key</div>');
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
            'ln_type_entity_id' => 6157, //Action Plan Question Answered
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

        return redirect_message('/actionplan/' . $answer_in_id, '<div class="alert alert-success" role="alert">Your answer was saved.</div>');

    }



}