<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cockpit extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	
	function udemy(){
	    //Authenticate level 3 or higher, redirect if not:
	    $udata = auth(3,1);
	    
	    if(isset($_GET['cat'])){
	        
	        //Load instructor list:
	        $this->load->view('console/shared/d_header', array(
	            'title' => urldecode($_GET['cat']).' Udemy Instructors',
	            'breadcrumb' => array(
	                array(
	                    'link' => '/cockpit/udemy',
	                    'anchor' => 'Udemy Instructors',
	                ),
	                array(
	                    'link' => null,
	                    'anchor' => urldecode($_GET['cat']).' <a href="/scraper/udemy_csv?cat='.urlencode($_GET['cat']).'"><i class="fa fa-cloud-download" aria-hidden="true"></i>CSV</a>',
	                ),
	            ),
	        ));
	        $this->load->view('cockpit/udemy_category' , array(
	            'il_category' => $this->Db_model->il_fetch(array(
	                'il_udemy_user_id >' => 0,
	                'il_student_count >' => 0,
	                'il_udemy_category' => urldecode($_GET['cat']),
	            )),
	        ));
	        $this->load->view('console/shared/d_footer');
	        
	    } else {
	        
	        //Load category list:
	        $this->load->view('console/shared/d_header', array(
	            'title' => 'Udemy Instructors',
	            'breadcrumb' => array(
	                array(
	                    'link' => null,
	                    'anchor' => 'Udemy Instructors',
	                ),
	            ),
	        ));
	        $this->load->view('cockpit/udemy_all' , array(
	            'il_overview' => $this->Db_model->il_overview_fetch(),
	        ));
	        $this->load->view('console/shared/d_footer');
	        
	    }
	}
	
	
	function browse($object_name){

        boost_power();

	    //Authenticate level 3 or higher, redirect if not:
	    $udata = auth(3,1);
	    
	    $this->load->view('console/shared/d_header', array(
	        'title' => 'Browse '.ucwords($object_name),
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Browse '.ucwords($object_name),
	            ),
	        ),
	    ));
	    $this->load->view('cockpit/list' , array(
	        'object_name' => $object_name,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
}