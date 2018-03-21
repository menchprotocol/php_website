<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}

    function ping(){
        echo_json(array('status'=>'success'));
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
	
	function status_bible(){
	    $udata = auth(3,1);
	    
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
	
	function bootcamps(){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//User Bootcamps:
		$bs = $this->Db_model->user_projects(array(
		    'ba.ba_u_id' => $udata['u_id'],
		    'ba.ba_status >=' => 0,
		    'b.b_status >=' => 2,
		));

		$title = 'My Bootcamps';
		
		//Load view
		$this->load->view('console/shared/d_header' , array(
		    'title' => $title,
		    'breadcrumb' => array(
		        array(
		            'link' => null,
		            'anchor' => $title,
		        ),
		    ),
		));
		
		//Have they activated their Bot yet?
        //Yes, show them their Bootcamps:
        $this->load->view('console/bootcamps' , array(
            'bs' => $bs,
            'udata' => $udata,
        ));
    	
		//Footer:
		$this->load->view('console/shared/d_footer');

	}
	
	
	function dashboard($b_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(1,1,$b_id);
	    $bs = $this->Db_model->remix_bs(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bs[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
	    }
	    
	    if(isset($_GET['raw'])){
	        echo_json($bs[0]);
	        exit;
	    }
	    
	    $title = 'Dashboard | '.$bs[0]['c_objective'];
	    
	    //Log view:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	        'e_json' => array(
	            'url' => $_SERVER['REQUEST_URI'],
	        ),
	        'e_type_id' => 48, //View
	        'e_message' => $title,
	        'e_b_id' => $bs[0]['b_id'],
	        'e_r_id' => 0,
	        'e_c_id' => 0,
	        'e_recipient_u_id' => 0,
	    ));
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => $title,
	        'b' => $bs[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Dashboard <span id="hb_2273" class="help_button" intent-id="2273"></span>',
	            ),
	        ),
	    ));
	    $this->load->view('console/dashboard' , array(
	        'b' => $bs[0],
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	function actionplan($b_id,$pid=null){
		
	    $udata = auth(1,1,$b_id);
		$bs = $this->Db_model->remix_bs(array(
		    'b.b_id' => $b_id,
		));
		if(!isset($bs[0])){
		    redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
		}

		//Fetch intent relative to the Bootcamp by doing an array search:
		$view_data = extract_level( $bs[0] , ( intval($pid)>0 ? $pid : $bs[0]['c_id'] ) );
		if(!$view_data){
		    redirect_message('/console/'.$b_id.'/actionplan','<div class="alert alert-danger" role="alert">Invalid Step ID. Select another Step to continue.</div>');
		} else {
		    //Append universal (Flat design) breadcrumb:
            $view_data['breadcrumb'] = array(
                array(
                    'link' => null,
                    'anchor' => 'Action Plan <span id="hb_2272" class="help_button" intent-id="2272"></span> <a href="#" data-toggle="modal" data-target="#importActionPlan" class="tipbtn"><span class="badge tip-badge" title="Import parts of all of Screening, Tasks or Outcomes from another Bootcamp you manage" data-toggle="tooltip" data-placement="bottom"><i class="fa fa-download" aria-hidden="true"></i></span></a>',
                ),
            );
        }
		
		if(isset($_GET['raw'])){
		    //For testing purposes:
		    echo_json($view_data['b']);
		    exit;
		}
		
		//Load views:
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/actionplan' , $view_data);
		$this->load->view('console/shared/d_footer' , array(
            'load_view' => 'console/modals/import_actionplan',
        ));
		
	}
	
	
	function classes($b_id){
	    //Authenticate:
	    $udata = auth(1,1,$b_id);
	    $bs = $this->Db_model->remix_bs(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bs[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
	    }
	    
	    $view_data = array(
	        'title' => 'Classes | '.$bs[0]['c_objective'],
	        'b' => $bs[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Classes <span id="hb_2274" class="help_button" intent-id="2274"></span>',
	            ),
	        ),
	    );
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , $view_data);
	    $this->load->view('console/classes' , $view_data);
	    $this->load->view('console/shared/d_footer');
	}
	

	
	function load_class($b_id,$r_id){
		//Authenticate:
	    $udata = auth(1,1,$b_id);
		$bs = $this->Db_model->remix_bs(array(
		    'b.b_id' => $b_id,
		));
		if(!isset($bs[0])){
		    redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
		}
		
		//This could be a new run, or editing an existing run:
		$class = filter($bs[0]['c__classes'],'r_id',$r_id);
		if(!$class){
		    redirect_message('/console/'.$b_id.'/classes' , '<div class="alert alert-danger" role="alert">Invalid class ID.</div>');
		}

		//See how many applied?
        $current_applicants = count($this->Db_model->ru_fetch(array(
            'ru.ru_r_id'	    => $class['r_id'],
            'ru.ru_status >='	=> 4, //Anyone who has completed their application
        )));
		
		$view_data = array(
		    'title' => time_format($class['r_start_date'],1).' Class Settings | '.$bs[0]['c_objective'],
            'b' => $bs[0],
            'current_applicants' => $current_applicants,
		    'class' => $class,
		    'breadcrumb' => array(
		        array(
		            'link' => '/console/'.$b_id.'/classes',
		            'anchor' => 'Classes',
		        ),
		        array(
		            'link' => null,
		            'anchor' => time_format($class['r_start_date'],2),
		        ),
		    ),
		);
		
		//Load view
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/class' , $view_data);
		$this->load->view('console/shared/d_footer');
	}

	
	function settings($b_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(1,1,$b_id);
	    $bs = $this->Db_model->remix_bs(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bs[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
	    }
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Settings | '.$bs[0]['c_objective'],
	        'b' => $bs[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Settings',
	            ),
	        ),
	    ));
	    $this->load->view('console/settings' , array(
	        'b' => $bs[0],
	        'udata' => $udata,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
}