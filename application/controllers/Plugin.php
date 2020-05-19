<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plugin extends CI_Controller
{

    var $is_player_request;
    var $session_en;

    function __construct()
    {

        parent::__construct();

        $this->output->enable_profiler(FALSE);

        date_default_timezone_set(config_var(11079));

        boost_power();

        //Running from browser? If so, authenticate:
        $this->is_player_request = isset($_SERVER['SERVER_NAME']);
        if($this->is_player_request){
            $this->session_en = superpower_assigned(12699, true);
        }

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

    function plugin_load($plugin_en_id){

        //Valud Plugin?
        if(!in_array($plugin_en_id, $this->config->item('en_ids_6287'))){
            die('Invalid Plugin ID');
        }

        //Needs extra superpowers?
        $en_all_6287 = $this->config->item('en_all_6287'); //MENCH PLUGIN
        $superpower_actives = array_intersect($this->config->item('en_ids_10957'), $en_all_6287[$plugin_en_id]['m_parents']);
        if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
            die(echo_unauthorized_message(end($superpower_actives)));
        }


        //This is also duplicated in source_plugin_load to pass-on to plugin file:
        $view_data = array(
            'plugin_en_id' => $plugin_en_id,
            'session_en' => $this->session_en,
            'is_player_request' => $this->is_player_request,
        );


        if(in_array($plugin_en_id, $this->config->item('en_ids_12741'))){

            //Raw UI:
            $this->load->view('source/plugin/'.$plugin_en_id.'/index', $view_data);

        } else {

            //Regular UI:
            //Load Plugin:
            $this->load->view('header', array(
                'title' => strip_tags($en_all_6287[$plugin_en_id]['m_icon']).$en_all_6287[$plugin_en_id]['m_name'].' | PLUGIN',
            ));
            $this->load->view('source/source_plugin_load', $view_data);
            $this->load->view('footer');

        }

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
        } elseif (!isset($_POST['in_id']) || intval($_POST['in_id']) < 1) {
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
        $ins = $this->IDEA_model->fetch(array(
            'in_id' => $_POST['in_id'],
            'in_status_source_id IN (' . join(',', $this->config->item('en_ids_7356')) . ')' => null, //ACTIVE
        ));
        if(count($ins) != 1){
            return echo_json(array(
                'status' => 0,
                'message' => 'Could not find idea #'.$_POST['in_id'],
            ));
        }


        //Load AND/OR Ideas:
        $en_all_7585 = $this->config->item('en_all_7585'); // Idea Subtypes
        $en_all_4737 = $this->config->item('en_all_4737'); // Idea Status


        //Return report:
        return echo_json(array(
            'status' => 1,
            'message' => '<h3>'.$en_all_7585[$ins[0]['in_type_source_id']]['m_icon'].' '.$en_all_4737[$ins[0]['in_status_source_id']]['m_icon'].' '.echo_in_title($ins[0]).'</h3>'.echo_in_scores_answer($_POST['in_id'], $_POST['depth_levels'], $_POST['depth_levels'], $ins[0]['in_type_source_id']),
        ));


    }

}
