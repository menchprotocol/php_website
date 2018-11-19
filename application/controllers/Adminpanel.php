<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminpanel extends CI_Controller {

    //To carry the user object after validation
    var $udata;

	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);

        //Authenticate level 3 or higher, redirect if not:
        $this->udata = auth(array(1281),1);
	}

    function engagements(){
        $this->load->view('shared/console_header', array(
            'title' => 'Platform Engagements',
        ));
        $this->load->view('engagements/engagements_browse');
        $this->load->view('shared/console_footer');
    }

    function subscriptions(){
        $this->load->view('shared/console_header', array(
            'title' => 'Subscriptions Browser',
        ));
        $this->load->view('actionplans/actionplans_browse');
        $this->load->view('shared/console_footer');
    }

    function ej_list($e_id){
        $udata = auth(array(1281),1);
        //Fetch blob of engagement and display it on screen:
        $blobs = $this->Db_model->e_fetch(array(
            'ej_e_id' => $e_id,
        ),1,array('ej'));
        if(count($blobs)==1){
            echo_json(array(
                'blob' => unserialize($blobs[0]['ej_e_blob']),
                'e' => $blobs[0]
            ));
        } else {
            echo_json(array('error'=>'Not Found'));
        }
    }


    function statuslegend(){
        //Load views
        $this->load->view('shared/console_header' , array(
            'title' => 'Status Legend',
        ));
        $this->load->view('other/statuslegend');
        $this->load->view('shared/console_footer');
    }


}