<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Intents extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function ping()
    {
        echo_json(array('status' => 'success'));
    }

    function intents_list($inbound_c_id = 0){

        //Authenticate level 2 or higher, redirect if not:
        $udata = auth(array(1308,1280),1);

        $title = 'Tasks';

        //Load view
        $this->load->view('console/console_header' , array(
            'title' => $title,
            'breadcrumb' => array(
                array(
                    'link' => null,
                    'anchor' => $title.'',
                ),
            ),
        ));

        //Have they activated their Bot yet?
        //Yes, show them their Bootcamps:
        $this->load->view('console/c/list_intents' , array());

        //Footer:
        $this->load->view('console/console_footer');

    }

}