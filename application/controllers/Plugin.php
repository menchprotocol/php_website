<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin extends CI_Controller
{

    var $session_id;

    function __construct()
    {

        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));

        boost_power();

        //Validate superpowers:
        $this->session_en = superpower_assigned(12699, true);

    }


    function index($action = null)
    {

        //List Plugins:
        $en_all_11035 = $this->config->item('en_all_11035'); //MENCH NAVIGATION

        $this->load->view('header', array(
            'title' => $en_all_11035[6287]['m_name'],
        ));
        $this->load->view('source/source_plugin_home');
        $this->load->view('footer');

    }

    function plugin_load($plugin_en_id = 12708 /* Page Not Found */){

        //Valud Plugin?
        if(!in_array($plugin_en_id, $this->config->item('en_ids_6287'))){
            die('ERROR: Invalid Plugin ID');
        }

        //Needs extra superpowers?
        $en_all_6287 = $this->config->item('en_all_6287'); //MENCH PLUGIN
        $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $en_all_6287[$plugin_en_id]['m_parents']);
        if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
            die(echo_unauthorized_message(end($superpower_actives)));
        }

        //Load Plugin:
        $this->load->view('header', array(
            'title' => strip_tags($en_all_6287[$plugin_en_id]['m_icon']).$en_all_6287[$plugin_en_id]['m_name'].' | PLUGIN',
        ));
        $this->load->view('source/source_plugin_load' , array(
            'plugin_en_id' => $plugin_en_id,
            'session_en' => $this->session_en,
        ));
        $this->load->view('footer');

    }




    /*
     *
     * If your plugin needs to make AJAX calls
     * you can add these calls here...
     *
     * */


    function ajax_7264(){

        //Authenticate Player:
        $session_en = superpower_assigned(12700);

        if (!$session_en) {
            return echo_json(array(
                'status' => 0,
                'message' => echo_unauthorized_message(12700),
            ));
        } elseif (!isset($_POST['starting_in']) || intval($_POST['starting_in']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Starting Idea',
            ));
        } elseif (!isset($_POST['depth_levels']) || intval($_POST['depth_levels']) < 1) {
            return echo_json(array(
                'status' => 0,
                'message' => 'Missing Depth',
            ));
        }

        //Fetch/Validate idea:
        $ins = $this->IDEA_model->in_fetch(array(
            'in_id' => $_POST['starting_in'],
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //Idea Status Active
        ));
        if(count($ins) != 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not find idea #'.$_POST['starting_in'],
            ));
        }


        //Load AND/OR Ideas:
        $en_all_7585 = $this->config->item('en_all_7585'); // Idea Subtypes
        $en_all_4737 = $this->config->item('en_all_4737'); // Idea Status


        //Return report:
        return echo_json(array(
            'status' => 1,
            'message' => '<h3>'.$en_all_7585[$ins[0]['in_type_source_id']]['m_icon'].' '.$en_all_4737[$ins[0]['in_status_source_id']]['m_icon'].' '.echo_in_title($ins[0], false).'</h3>'.echo_in_scores_answer($_POST['starting_in'], $_POST['depth_levels'], $_POST['depth_levels'], $ins[0]['in_type_source_id']),
        ));


    }

}
