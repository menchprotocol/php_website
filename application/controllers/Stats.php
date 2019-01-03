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
        $this->load->view('view_shared/matrix_header', array(
            'title' => 'Stats',
        ));
        $this->load->view('view_shared/stats');
        $this->load->view('view_shared/matrix_footer');
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