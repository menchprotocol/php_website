<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }

    function overview(){
        $this->load->view('view_mench/shared_header', array(
            'title' => 'BLOG',
        ));
        $this->load->view('view_blog/blog_overview');
        $this->load->view('view_mench/shared_footer');
    }

}