<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entities extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    //Lists entities
    function entity_manage($en_id)
    {

        $udata = auth(null, 1); //Just be logged in to browse

        $entities = $this->Database_model->en_fetch(array(
            'en_id' => $en_id,
        ), array('en__child_count', 'en__children', 'en__actionplans'));

        if (count($entities) < 1) {
            return redirect_message('/entities', '<div class="alert alert-danger" role="alert">Invalid Entity ID</div>');
        }

        //Load views:
        $this->load->view('shared/matrix_header', array(
            'title' => $entities[0]['en_name'],
        ));
        $this->load->view('entities/entity_manage', array(
            'entity' => $entities[0],
        ));
        $this->load->view('shared/matrix_footer');
    }

    function u_load_next_page()
    {

        $en_per_page = $this->config->item('en_per_page');
        $parent_en_id = intval($_POST['parent_en_id']);
        $en_status_filter = intval($_POST['en_status_filter']);
        $page = intval($_POST['page']);
        $udata = auth(null); //Just be logged in to browse
        $filters = array(
            'tr_en_parent_id' => $parent_en_id,
            'en_status' . ($en_status_filter < 0 ? ' >=' : '') => ($en_status_filter < 0 ? 0 : intval($en_status_filter)), //Pending or Active
            'tr_status' => 1, //Active link
        );

        if (!$udata) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle" style="margin:0 8px 0 2px;"></i> Session expired. Refresh the page and try again.</div>';
            return false;
        }

        //Fetch entity itself:
        $entities = $this->Database_model->en_fetch(array('en_id' => $parent_en_id));
        $child_entities_count = count($this->Old_model->ur_children_fetch($filters));
        $child_entities = $this->Old_model->ur_children_fetch($filters, array('en__child_count'), $en_per_page, ($page * $en_per_page));

        foreach ($child_entities as $u) {
            echo echo_u($u, 2, false /* Load more only for children */);
        }

        //Do we need another load more button?
        if ($child_entities_count > (($page * $en_per_page) + count($child_entities))) {
            echo_next_u(($page + 1), $en_per_page, $child_entities_count);
        }

    }


    function link_entities()
    {

        //Responsible to link parent/children entities to each other via a JS function on entity_manage.php

        //Auth user and check required variables:
        $udata = auth(array(1308));

        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Parent Entity',
            ));
        } elseif (!isset($_POST['assign_en_parent_id'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Parent Entity',
            ));
        } elseif (!isset($_POST['is_parent'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Entity Link Direction',
            ));
        } elseif (!isset($_POST['en_new_id']) || !isset($_POST['en_new_name']) || (intval($_POST['en_new_id']) < 1 && strlen($_POST['en_new_name']) < 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Either New Entity ID or Name is required',
            ));
        }

        //Validate parent entity:
        $current_us = $this->Database_model->en_fetch(array(
            'en_id' => $_POST['en_id'],
        ));
        if (count($current_us) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid current entity ID',
            ));
        }


        //Set some variables:
        $_POST['is_parent'] = intval($_POST['is_parent']);
        $_POST['en_new_id'] = intval($_POST['en_new_id']);
        $linking_to_existing_u = false;
        $is_url_input = false;

        //Are we linking to an existing entity?
        if (intval($_POST['en_new_id']) > 0) {

            //We're linking to an existing entity:
            $linking_to_existing_u = true;

            //Validate this existing entity
            $ens = $this->Database_model->en_fetch(array(
                'en_id' => $_POST['en_new_id'],
                'en_status >=' => 1, //Active only
            ));
            if (count($ens) < 1) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid active entity',
                ));
            } else {
                $entity_new = $ens[0];
            }

        } else {

            //We should add a new entity:
            $entity_new = $this->Database_model->en_create(array(
                'en_name' => trim($_POST['en_new_name']),
            ), true, $udata['en_id']);

            if (!isset($entity_new['en_id']) || $entity_new['en_id'] < 1) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Failed to create new entity for [' . $_POST['en_new_name'] . ']',
                ));
            }

            //Do we need to add this new entity to a secondary parent?
            if (intval($_POST['assign_en_parent_id']) > 0) {

                //Link entity to a parent:
                $ur1 = $this->Database_model->tr_create(array(
                    'tr_en_child_id' => $entity_new['en_id'],
                    'tr_en_parent_id' => $_POST['assign_en_parent_id'],
                ));

            }

        }

        //We need to check to ensure this is not a duplicate link if linking to an existing entity:
        $ur2 = array();

        if (!$is_url_input) {

            //Add links only if not already added by the URL function:
            if ($_POST['is_parent']) {
                $tr_en_child_id = $current_us[0]['en_id'];
                $tr_en_parent_id = $entity_new['en_id'];
            } else {
                $tr_en_child_id = $entity_new['en_id'];
                $tr_en_parent_id = $current_us[0]['en_id'];
            }

            //Let's make sure this is not the same as the secondary category:
            if (!($_POST['assign_en_parent_id'] == $tr_en_parent_id)) {
                //Link to new OR existing entity:
                $ur2 = $this->Database_model->tr_create(array(
                    'tr_en_child_id' => $tr_en_child_id,
                    'tr_en_parent_id' => $tr_en_parent_id,
                ));

            } else {
                //This has already been added:
                $ur2 = $ur1;
            }
        }


        //Return newly added/linked entity:
        return echo_json(array(
            'status' => 1,
            'en_new_status' => $entity_new['en_status'],
            'en_new_echo' => echo_u(array_merge($entity_new, $ur2), 2, $_POST['is_parent']),
        ));
    }

    function unlink_entities()
    {

        //Auth user and check required variables:
        $udata = auth(array(1308));

        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid Session. Refresh the page and try again.',
            ));
        } elseif (!isset($_POST['tr_id']) || intval($_POST['tr_id']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity link ID',
            ));
        }

        //Remove transaction:
        $this->Database_model->tr_update($_POST['tr_id'], array(
            'tr_status' => -1,
        ), $udata['en_id']);

        return echo_json(array(
            'status' => 1,
            'message' => 'Successfully unlinked entity',
        ));
    }


    function u_save_settings()
    {

        //Auth user and check required variables:
        $udata = auth(array(1308));
        $tr_content_max = $this->config->item('tr_content_max');

        //Fetch current data:
        $u_current = $this->Database_model->en_fetch(array(
            'en_id' => intval($_POST['en_id']),
        ));

        if (!$udata) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Session Expired',
            ));
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) <= 0 || !(count($u_current) == 1)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid ID',
            ));
        } elseif (!isset($_POST['en_name']) || strlen($_POST['en_name']) <= 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing name',
            ));
        } elseif (!isset($_POST['en_status'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing status',
            ));
        } elseif (!isset($_POST['tr_id']) || !isset($_POST['tr_content'])) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing entity link data',
            ));
        } elseif (strlen($_POST['en_name']) > $this->config->item('en_name_max')) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Name is longer than the allowed ' . $this->config->item('en_name_max') . ' characters. Shorten and try again.',
            ));
        }


        //Prepare data to be updated:
        $u_update = array(
            'en_status' => intval($_POST['en_status']),
            'en_name' => trim($_POST['en_name']),
            'en_icon' => trim($_POST['en_icon']),
        );

        //DO we have a link to update?
        if (intval($_POST['tr_id']) > 0) {

            //Yes, first validate this link:
            $urs = $this->Database_model->tr_parent_fetch(array(
                'tr_id' => $_POST['tr_id'],
            ));

            if (count($urs) == 0) {
                return echo_json(array(
                    'status' => 0,
                    'message' => 'Invalid Entity Link ID',
                ));
            }

            //Has the link value changes?
            if (!($urs[0]['tr_content'] == $_POST['tr_content'])) {

                //Something has changed, log this:
                $this->Database_model->tr_update($_POST['tr_id'], array(
                    'tr_content' => $_POST['tr_content'],
                    'tr_en_type_id' => detect_tr_en_type_id($_POST['tr_content']),
                ), $udata['en_id']);

            }

        }

        //Now update the DB:
        $this->Database_model->en_update(intval($_POST['en_id']), $u_update, true, $udata['en_id']);

        //Reset user session data if this data belongs to the logged-in user:
        if ($_POST['en_id'] == $udata['en_id']) {
            $entities = $this->Database_model->en_fetch(array(
                'en_id' => intval($_POST['en_id']),
            ));
            if (isset($entities[0])) {
                $this->session->set_userdata(array('user' => $entities[0]));
            }
        }

        //Show success:
        return echo_json(array(
            'status' => 1,
            'message' => '<span><i class="fas fa-check"></i> Saved</span>',
            'status_u_ui' => echo_status('en', $_POST['en_status'], true, 'bottom'),
            'tr_content' => echo_link($_POST['tr_content']),
        ));

    }


    function load_messages()
    {

        $udata = auth();
        if (!$udata) {
            //Display error:
            die('<span style="color:#FF0000;">Error: Invalid Session. Login again to continue.</span>');
        } elseif (!isset($_POST['en_id']) || intval($_POST['en_id']) <= 0) {
            die('<span style="color:#FF0000;">Error: Invalid entity id.</span>');
        }

        $messages = $this->Database_model->i_fetch(array(
            'i_status >=' => 0,
            'tr_en_parent_id' => $_POST['en_id'],
        ), 0);
        echo '<div id="list-messages" class="list-group  grey-list">';
        foreach ($messages as $i) {
            echo echo_message_chat($i);
        }
        echo '</div>';
    }


    function login_ui()
    {
        //Check to see if they are already logged in?
        $udata = $this->session->userdata('user');
        if (isset($udata['en__parents'][0]) && filter_array($udata['en__parents'], 'en_id', 1308)) {
            //Lead miner and above, go to console:
            redirect_message('/intents/' . $this->config->item('in_primary_id'));
        }

        $this->load->view('shared/public_header', array(
            'title' => 'Login',
        ));
        $this->load->view('entities/login_ui');
        $this->load->view('shared/public_footer');
    }

    function login_process()
    {

        //Setting for admin logins:

        if (!isset($_POST['input_email']) || !filter_var($_POST['input_email'], FILTER_VALIDATE_EMAIL)) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid email to continue.</div>');
        } elseif (!isset($_POST['input_password'])) {
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Enter valid password to continue.</div>');
        }

        //Validate user email:
        $trs = $this->Database_model->tr_fetch(array(
            'tr_en_parent_id' => 3288, //Primary email
            'LOWER(tr_content)' => strtolower($_POST['input_email']),
        ));

        if (count($trs) == 0) {
            //Not found!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: ' . $_POST['input_email'] . ' not found.</div>');
        }

        //Fetch full entity data with their active Action Plans:
        $entities = $this->Database_model->en_fetch(array(
            'en_id' => $trs[0]['tr_en_child_id'],
        ), array('en__actionplans'));

        if ($entities[0]['en_status'] < 0 || $trs[0]['tr_status'] < 0) {

            //Removed entity
            $this->Database_model->tr_create(array(
                'tr_en_credit_id' => $entities[0]['en_id'],
                'tr_en_child_id' => $entities[0]['en_id'],
                'tr_content' => 'login() denied because account is not active.',
                'tr_metadata' => $_POST,
                'tr_en_type_id' => 4247, //Support Needing Graceful Errors
            ));

            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Your account has been de-activated. Contact us to re-active your account.</div>');

        }

        //Authenticate their password:
        $login_passwords = $this->Database_model->tr_fetch(array(
            'tr_en_parent_id' => 3286, //Mench Login Password
            'tr_en_child_id' => $entities[0]['en_id'],
        ));
        if (count($login_passwords) == 0) {
            //They do not have a password assigned yet!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: An active login password has not been assigned to your account yet. You can assign a new password using the Forgot Password Button.</div>');
        } elseif ($login_passwords[0]['tr_status'] < 2) {
            //They do not have a password assigned yet!
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Password is not activated.</div>');
        } elseif (!($login_passwords[0]['tr_content'] == hash('sha256', $this->config->item('password_salt') . $_POST['input_password']))) {
            //Bad password
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Incorrect password for [' . $_POST['input_email'] . ']</div>');
        }


        $session_data = array();
        $is_chrome = (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') !== false);
        $is_miner = false;
        $is_master = false;


        //Are they miner? Give them login access:
        if (filter_array($entities[0]['en__parents'], 'en_id', 1308)) {
            //They have admin rights:
            $session_data['user'] = $entities[0];
            $is_miner = true;
        }


        //Applicable for miners only:
        if (!$is_chrome) {

            if ($is_master) {

                //Remove miner privileges as they cannot use the matrix with non-chrome Browser:
                $is_miner = false;
                unset($session_data['user']);

            } else {

                return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: Login Denied. The Matrix v' . $this->config->item('app_version') . ' supports <a href="https://www.google.com/chrome/browser/" target="_blank"><u>Google Chrome</u></a> only.</div>');

            }

        } elseif (!$is_miner && !$is_master) {

            //We assume this is a master request:
            return redirect_message('/login', '<div class="alert alert-danger" role="alert">Error: You have not been enrolled to any Bootcamps yet. You can only login as a master after you have been approved by your miner.</div>');

        }


        //Log transaction
        $this->Database_model->tr_create(array(
            'tr_en_credit_id' => $entities[0]['en_id'],
            'tr_metadata' => $entities[0],
            'tr_en_type_id' => 4269, //login
        ));

        //All good to go!
        //Load session and redirect:
        $this->session->set_userdata($session_data);

        //Append user IP and agent information
        if (isset($_POST['input_password'])) {
            unset($_POST['input_password']); //Sensitive information to be removed and NOT logged
        }
        $entities[0]['login_ip'] = $_SERVER['REMOTE_ADDR'];
        $entities[0]['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $entities[0]['input_post_data'] = $_POST;


        if (isset($_POST['url']) && strlen($_POST['url']) > 0) {
            header('Location: ' . $_POST['url']);
        } else {
            //Default:
            if ($is_miner) {
                //miner default:
                header('Location: /intents/' . $this->config->item('in_primary_id'));
            } else {
                //Master default:
                header('Location: /my/actionplan');
            }
        }
    }

    function logout()
    {
        //Log transaction:
        $udata = $this->session->userdata('user');
        $this->Database_model->tr_create(array(
            'tr_en_credit_id' => (isset($udata['en_id']) && $udata['en_id'] > 0 ? $udata['en_id'] : 0),
            'tr_metadata' => $udata,
            'tr_en_type_id' => 4270, //User Logout
        ));

        //Called via AJAX to destroy user session:
        $this->session->sess_destroy();
        header('Location: /');
    }


    function password_initiate_reset()
    {

        //We need an email input:
        if (!isset($_POST['email'])) {
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Missing Email.</div>');
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            die('<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid Email.</div>');
        }

        //Attempt to fetch this user:
        $matching_users = $this->Database_model->en_fetch(array(
            'input_email' => strtolower($_POST['email']),
        ));
        if (count($matching_users) > 0) {
            //Dispatch the password reset Intent:
            $this->Matrix_model->compose_messages(array(
                'tr_en_child_id' => $matching_users[0]['en_id'],
                'tr_in_child_id' => 59,
            ));
        }

        //Show message:
        echo '<div class="alert alert-success">Password reset accepted. You will receive an email only if you have a registered Mench account.</div>';
        echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';

    }


    function password_reset()
    {
        //This function updates the user's new password as requested via a password reset:
        if (!isset($_POST['en_id']) || intval($_POST['en_id']) <= 0 || !isset($_POST['timestamp']) || intval($_POST['timestamp']) <= 0 || !isset($_POST['p_hash']) || strlen($_POST['p_hash']) < 10) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Missing Core Variables.</div>';
        } elseif (!($_POST['p_hash'] == md5($_POST['en_id'] . $this->config->item('password_salt') . $_POST['timestamp']))) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: Invalid hash key.</div>';
        } elseif (!isset($_POST['new_pass']) || strlen($_POST['new_pass']) < 6) {
            echo '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: New password must be longer than 6 characters. Try again.</div>';
        } else {

            //Fetch their passwords to authenticate login:
            $login_passwords = $this->Database_model->tr_fetch(array(
                'tr_status >=' => 2, //Must be published or verified
                'tr_en_parent_id' => 3286, //Mench Login Password
                'tr_en_child_id' => $_POST['en_id'], //For this user
            ));

            $new_password = hash('sha256', $this->config->item('password_salt') . $_POST['new_pass']);

            if (count($login_passwords) > 0) {

                //Update existing password:
                $this->Database_model->tr_update($login_passwords[0]['tr_id'], array(
                    'tr_content' => $new_password,
                    'tr_en_type_id' => detect_tr_en_type_id($new_password),
                ), $login_passwords[0]['tr_en_child_id']);

            } else {
                //Create new password link:

            }


            //Log all sessions out:
            $this->session->sess_destroy();

            //Show message:
            echo '<div class="alert alert-success">Passsword reset successful. You can <a href="/login"><u>login here</u></a>.</div>';
            echo '<script> $(document).ready(function() { $(".pass_success").hide(); }); </script>';
        }
    }


}