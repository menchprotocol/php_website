<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cockpit extends CI_Controller {

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
        $this->load->view('console/console_header', array(
            'title' => 'Platform Engagements',
            'breadcrumb' => array(
                array(
                    'link' => null,
                    'anchor' => 'Platform Engagements',
                ),
            ),
        ));
        $this->load->view('console/cockpit/engagements_browse');
        $this->load->view('console/console_footer');
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
	
	function udemy(){

	    if(isset($_GET['cat'])){
	        
	        //Load coach list:
	        $this->load->view('console/console_header', array(
	            'title' => urldecode($_GET['cat']).' Udemy Community',
	            'breadcrumb' => array(
	                array(
	                    'link' => '/cockpit/udemy',
	                    'anchor' => 'Udemy Community',
	                ),
	                array(
	                    'link' => null,
	                    'anchor' => urldecode($_GET['cat']).' <a href="/scraper/udemy_csv?cat='.urlencode($_GET['cat']).'"><i class="fas fa-cloud-download"></i>CSV</a>',
	                ),
	            ),
	        ));
	        $this->load->view('console/cockpit/udemy_category' , array(
	            'il_category' => $this->Db_model->il_fetch(array(
	                'il_udemy_user_id >' => 0,
	                'il_student_count >' => 0,
	                'il_udemy_category' => urldecode($_GET['cat']),
	            )),
	        ));
	        $this->load->view('console/console_footer');
	        
	    } else {
	        
	        //Load category list:
	        $this->load->view('console/console_header', array(
	            'title' => 'Udemy Community',
	            'breadcrumb' => array(
	                array(
	                    'link' => null,
	                    'anchor' => 'Udemy Community',
	                ),
	            ),
	        ));
	        $this->load->view('console/cockpit/udemy_all' , array(
	            'il_overview' => $this->Db_model->il_overview(),
	        ));
	        $this->load->view('console/console_footer');
	        
	    }
	}


    function statusbible(){
        //Load views
        $this->load->view('console/console_header' , array(
            'title' => 'Object Statuses',
            'breadcrumb' => array(
                array(
                    'link' => null,
                    'anchor' => 'Object Statuses',
                ),
            ),
        ));
        $this->load->view('console/cockpit/echo_status');
        $this->load->view('console/console_footer');
    }


}