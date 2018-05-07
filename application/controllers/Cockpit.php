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

    function browse($object_name='none'){

        boost_power();

        $this->load->view('console/console_header', array(
            'title' => 'Browse '.ucwords($object_name),
            'breadcrumb' => array(
                array(
                    'link' => null,
                    'anchor' => 'Browse <span id="hb_6086" class="help_button" intent-id="6086"></span>',
                ),
            ),
        ));
        $this->load->view('console/cockpit/browse/browse_index' , array(
            'object_name' => $object_name,
        ));
        $this->load->view('console/console_footer');
    }
	
	function udemy(){

	    if(isset($_GET['cat'])){
	        
	        //Load instructor list:
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
	                    'anchor' => 'Udemy Community <span id="hb_6085" class="help_button" intent-id="6085"></span>',
	                ),
	            ),
	        ));
	        $this->load->view('console/cockpit/udemy_all' , array(
	            'il_overview' => $this->Db_model->il_overview_fetch(),
	        ));
	        $this->load->view('console/console_footer');
	        
	    }
	}


    function statusbible(){
        //Load views
        $this->load->view('console/console_header' , array(
            'title' => 'Status Bible',
            'breadcrumb' => array(
                array(
                    'link' => null,
                    'anchor' => 'Status Bible <span id="hb_6084" class="help_button" intent-id="6084"></span>',
                ),
            ),
        ));
        $this->load->view('console/cockpit/status_bible');
        $this->load->view('console/console_footer');
    }


}