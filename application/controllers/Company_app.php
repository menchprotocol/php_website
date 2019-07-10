<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_app extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);
    }

    function add_company(){

        if (!isset($_POST['user_full_name']) || strlen($_POST['user_full_name']) < 5) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Full name must be at-least 5 characters long',
                'error_field' => 'user_full_name',
            ));
        } elseif (!isset($_POST['user_email']) || !filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Invalid email',
                'error_field' => 'user_email',
            ));
        } elseif (count($this->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $this->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_content' => trim(strtolower($_POST['user_email'])),
                'ln_type_entity_id' => 4255, //Linked Entities Text (Email is text)
                'ln_parent_entity_id' => 3288, //Email Address
            ))) > 0) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Email already in use. You can use forgot password to login.',
                'error_field' => 'user_email',
            ));
        } elseif (!isset($_POST['company_name']) || strlen($_POST['company_name']) < 2) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Company name must be at-least 2 characters long',
                'error_field' => 'company_name',
            ));
        } elseif (!isset($_POST['your_password']) || strlen($_POST['your_password']) < 6) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Password name must be at-least 6 characters long',
                'error_field' => 'your_password',
            ));
        } elseif (!isset($_POST['repeat_password']) || $_POST['repeat_password'] != $_POST['your_password']) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Passwords don\'t match',
                'error_field' => 'repeat_password',
            ));
        }


        return echo_json(array(
            'status' => 1,
            'message' => '<i class="far fa-check-circle"></i> Success, redirecting now...',
            'success_url' => '/11900',
        ));


        //Create entities:
        $user_en = $this->Entities_model->en_verify_create(trim($_POST['user_full_name']), 0, false, 6181);
        $company_en = $this->Entities_model->en_verify_create(trim($_POST['company_name']), $user_en['en_id'], false, 6181);

        //Create user links:
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_miner_entity_id' => $user_en['en_id'],
            'ln_parent_entity_id' => 1278, //People
            'ln_child_entity_id' => $user_en['en_id'],
        ));
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4230, //Raw link
            'ln_miner_entity_id' => $user_en['en_id'],
            'ln_parent_entity_id' => 3504, //English
            'ln_child_entity_id' => $user_en['en_id'],
        ));
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4255, //Text link
            'ln_miner_entity_id' => $user_en['en_id'],
            'ln_parent_entity_id' => 3288, //Email Address
            'ln_content' => trim(strtolower($_POST['user_email'])),
            'ln_child_entity_id' => $user_en['en_id'],
        ));
        $this->Links_model->ln_create(array(
            'ln_type_entity_id' => 4255, //Text link
            'ln_miner_entity_id' => $user_en['en_id'],
            'ln_parent_entity_id' => 3286, //Mench Password
            'ln_content' => strtolower(hash('sha256', $this->config->item('password_salt') . $_POST['your_password'] . $user_en['en_id'])),
            'ln_child_entity_id' => $user_en['en_id'],
        ));




    }

}