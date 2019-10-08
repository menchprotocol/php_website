<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mench extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }


    function mench(){
        //Loads Mench home page https://mench.com
        $this->load->view('view_mench/shared_header', array(
            'title' => 'PLAY. READ. BLOG.',
        ));
        $this->load->view('view_mench/mench');
        $this->load->view('view_mench/shared_footer');
    }


}