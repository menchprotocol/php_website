<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function index()
    {
        //Display statuses for intents, entities and ledger transactions:
        $session_en = fn___en_auth(); //Just be logged in to browse

        if($session_en){

            //Miner logged in stats
            $this->load->view('view_shared/matrix_header', array(
                'title' => 'Platform Stats',
            ));
            $this->load->view('view_shared/stats');
            $this->load->view('view_shared/matrix_footer');
        } else {

            //Public facing stats:
            $this->load->view('view_shared/public_header', array(
                'title' => 'Platform Stats',
            ));
            $this->load->view('view_shared/stats');
            $this->load->view('view_shared/public_footer');
        }
    }

    function ses()
    {
        fn___echo_json($this->session->all_userdata());
    }

    function info()
    {
        echo phpinfo();
    }

}