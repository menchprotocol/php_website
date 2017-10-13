<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cockpit extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	function engagements(){
	    //Authenticate level 3 or higher, redirect if not:
	    $udata = auth(3,1);
	    
	    //This lists all users based on the permissions of the user
	    $this->load->view('console/shared/d_header', array(
	        'title' => 'All Engagements',
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'All Engagements',
	            ),
	        ),
	    ));
	    $this->load->view('cockpit/engagements' , array(
	       'engagements' => $this->Db_model->e_fetch(), //Fetch Last 100 Engagements
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
}