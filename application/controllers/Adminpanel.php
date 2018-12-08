<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpanel extends CI_Controller
{

    //To carry the user object after validation
    var $udata;

    function __construct()
    {
        parent::__construct();

        //Load our buddies:
        $this->output->enable_profiler(FALSE);

        //Authenticate Trainers, redirect if not:
        $this->udata = auth(array(1308), 1);
    }

    function engagements()
    {
        $this->load->view('shared/matrix_header', array(
            'title' => 'Platform Engagements',
        ));
        $this->load->view('engagements/engagements_browse');
        $this->load->view('shared/matrix_footer');
    }

    function subscriptions()
    {
        $this->load->view('shared/matrix_header', array(
            'title' => 'Subscriptions Browser',
        ));
        $this->load->view('actionplans/actionplans_browse');
        $this->load->view('shared/matrix_footer');
    }

    function li_list_blob($tr_id)
    {
        //Authenticate trainer access:
        $udata = auth(array(1308), 1);
        //Fetch blob of engagement and display it on screen:
        $blobs = $this->Db_model->tr_fetch(array(
            'tr_id' => $tr_id,
        ), 1);
        if (count($blobs) == 1) {
            $blob = $blobs[0]['tr_metadata'];
            unset($blobs[0]['tr_metadata']);
            echo_json(array(
                'blob' => unserialize($blob),
                //Show the rest of the engagement too:
                'e' => $blobs[0]
            ));
        } else {
            echo_json(array('error' => 'Not Found'));
        }
    }


    function statuslegend()
    {
        //Load views
        $this->load->view('shared/matrix_header', array(
            'title' => 'Status Legend',
        ));
        $this->load->view('other/statuslegend');
        $this->load->view('shared/matrix_footer');
    }


}