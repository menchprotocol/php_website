<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Webhooks extends CI_Controller
{

    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
    }



    //Issue certificate of completion
    function certify()
    {
        echo 'hi';
    }
}