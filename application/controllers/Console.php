<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	function raw($b_id){
	    print_r($this->Db_model->c_full_fetch(array(
	        'b.b_id' => $b_id
	    )));
	}
	
	
	/* ******************************
	 * User & Help
	 ****************************** */
	
	function account(){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//This lists all users based on the permissions of the user
		$this->load->view('console/shared/d_header', array(
            'title' => 'My Account',
            'breadcrumb' => array(
                array(
                    'link' => null,
                    'anchor' => 'My Account',
                ),
            ),
		));
		$this->load->view('console/account');
		$this->load->view('console/shared/d_footer');
	}
	
	function help(){
	    header( 'Location: /console' );
	}	
	
	function status_bible(){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(1,1);
	    
	    //Load views
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Guides | Status Bible',
	    ));
	    $this->load->view('console/guides/status_bible');
	    $this->load->view('console/shared/d_footer');
	}
	
	
	
	/* ******************************
	 * Bootcamps
	 ****************************** */
	
	function all_bootcamps(){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//Load view
		$this->load->view('console/shared/d_header' , array(
			'title' => 'My Bootcamps',
		    'breadcrumb' => array(
		        array(
		            'link' => null,
		            'anchor' => 'My Bootcamps',
		        ),
		    ),
		));
		$this->load->view('console/all_bootcamps' , array(
		    'bootcamps' => $this->Db_model->u_bootcamps(array(
		        'ba.ba_u_id' => $udata['u_id'],
		        'ba.ba_status >=' => 0,
		        'b.b_status >=' => 0,
		    )),
		));
		$this->load->view('console/shared/d_footer' , array(
		    'load_view' => 'console/modals/wizard_bootcamp',
		));
	}
	
	
	function dashboard($b_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bootcamps[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
	    }	    
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Dashboard | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Bootcamp Dashboard',
	            ),
	        ),
	    ));
	    $this->load->view('console/dashboard' , array(
	        'bootcamp' => $bootcamps[0],
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	function actionplan($b_id,$pid=null){
		
		$udata = auth(2,1);
		$bootcamps = $this->Db_model->c_full_fetch(array(
		    'b.b_id' => $b_id,
		));
		if(!isset($bootcamps[0])){
		    redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
		}
		
		//Fetch intent relative to the bootcamp by doing an array search:
		$view_data = extract_level( $bootcamps[0] , ( intval($pid)>0 ? $pid : $bootcamps[0]['c_id'] ) );
		if(!$view_data){
		    redirect_message('/console/'.$b_id.'/actionplan','<div class="alert alert-danger" role="alert">Invalid task ID. Select another task to continue.</div>');
		}
		
		//Load views:
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/actionplan' , $view_data);
		$this->load->view('console/shared/d_footer');
		
	}
	
	
	function all_cohorts($b_id){
	    //Authenticate:
	    $udata = auth(2,1);
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bootcamps[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
	    }
	    
	    $view_data = array(
	        'title' => 'Cohorts | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Cohorts',
	            ),
	        ),
	    );
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , $view_data);
	    $this->load->view('console/all_cohorts' , $view_data);
	    $this->load->view('console/shared/d_footer' , array(
	        'load_view' => 'console/modals/new_cohort',
	        'bootcamp' => $bootcamps[0],
	    ));
	}
	
	
	function scheduler($b_id,$r_id){
	    //Authenticate:
	    $udata = auth(2,1);
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bootcamps[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
	    }
	    
	    //This could be a new run, or editing an existing run:
	    $cohort = filter($bootcamps[0]['c__cohorts'],'r_id',$r_id);
	    if(!$cohort){
	        die('<div class="alert alert-danger" role="alert">Invalid cohort ID.</div>');
	    }
	    
	    //Load in iFrame
	    $this->load->view('console/frames/scheduler' , array( 
	        'title' => 'Edit Schedule | '.time_format($cohort['r_start_date'],1).' Cohort | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'cohort' => $cohort
	    ));
	}
	
	function cohort($b_id,$r_id){
		//Authenticate:
		$udata = auth(2,1);
		$bootcamps = $this->Db_model->c_full_fetch(array(
		    'b.b_id' => $b_id,
		));
		if(!isset($bootcamps[0])){
		    redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
		}
		
		//This could be a new run, or editing an existing run:
		$cohort = filter($bootcamps[0]['c__cohorts'],'r_id',$r_id);
		if(!$cohort){
		    redirect_message('/console/'.$b_id.'/cohorts' , '<div class="alert alert-danger" role="alert">Invalid cohort ID.</div>');
		}
		
		$view_data = array(
		    'title' => time_format($cohort['r_start_date'],1).' Cohort Settings | '.$bootcamps[0]['c_objective'],
		    'bootcamp' => $bootcamps[0],
		    'cohort' => $cohort,
		    'breadcrumb' => array(
		        array(
		            'link' => '/console/'.$b_id.'/cohorts',
		            'anchor' => 'Cohorts',
		        ),
		        array(
		            'link' => null,
		            'anchor' => time_format($cohort['r_start_date'],1),
		        ),
		    ),
		);
		
		//Load view
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/cohort' , $view_data);
		$this->load->view('console/shared/d_footer');
	}
	
	
	
	function students($b_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bootcamps[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
	    }
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Students | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Students',
	            ),
	        ),
	    ));
	    $this->load->view('console/students' , array(
	        'bootcamp' => $bootcamps[0],
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	
	function stream($b_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bootcamps[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
	    }
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Activity Stream | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Activity Stream',
	            ),
	        ),
	    ));
	    $this->load->view('console/stream' , array(
	        'bootcamp' => $bootcamps[0],
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	function settings($b_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bootcamps[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
	    }
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Bootcamp Settings | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Bootcamp Settings',
	            ),
	        ),
	    ));
	    $this->load->view('console/settings' , array(
	        'bootcamp' => $bootcamps[0],
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
}