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
		
		//User Bootcamps:
		$my_bootcamps = $this->Db_model->u_bootcamps(array(
		    'ba.ba_u_id' => $udata['u_id'],
		    'ba.ba_status >=' => 0,
		    'b.b_status >=' => 0,
		));
		
		
		//Did we find any?
		foreach($my_bootcamps as $key=>$mb){
		    //Fetch full bootcamp:
		    $this_full = $this->Db_model->c_full_fetch(array(
		        'b.b_id' => $mb['b_id'],
		    ));
		    $my_bootcamps[$key] = $this_full[0];
		}
		$title = ( strlen($udata['u_fb_id'])>4 ? 'My Bootcamps' : '<img src="/img/white-logo.png" style="width:32px; margin-top: -2px;" /> Activate Your Assistant Bot') ;
		
		//Load view
		$this->load->view('console/shared/d_header' , array(
		    'title' => trim(strip_tags($title)),
		    'breadcrumb' => array(
		        array(
		            'link' => null,
		            'anchor' => $title,
		        ),
		    ),
		));
		
		//Have they activated their Bot yet?
		if(strlen($udata['u_fb_id'])>4){
		    //Yes, show them their bootcamps:
		    $this->load->view('console/all_bootcamps' , array(
		        'bootcamps' => $my_bootcamps,
		        'udata' => $udata,
		    ));
		} else {
		    
		    //Fetch the ID from the Database to be up to date:
		    $users = $this->Db_model->u_fetch(array(
		        'u_id' => $udata['u_id'],
		    ));
		    
		    //Show activation:
		    $this->load->view('console/activate_bot' , array(
		        'bootcamps' => $my_bootcamps,
		        'users' => $users,
		        'udata' => $udata,
		    ));
		}
    	
		//Footer:
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
	    
	    if(isset($_GET['raw'])){
	        echo_json($bootcamps[0]);
	        exit;
	    }
	    
	    $title = 'Dashboard | '.$bootcamps[0]['c_objective'];
	    
	    //Log view:
	    $this->Db_model->e_create(array(
	        'e_initiator_u_id' => $udata['u_id'], //The user that updated the account
	        'e_json' => json_encode(array(
	            'url' => $_SERVER['REQUEST_URI'],
	        )),
	        'e_type_id' => 48, //View
	        'e_message' => $title,
	        'e_b_id' => $bootcamps[0]['b_id'],
	        'e_r_id' => 0,
	        'e_c_id' => 0,
	        'e_recipient_u_id' => 0,
	    ));
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => $title,
	        'bootcamp' => $bootcamps[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Dashboard <span id="hb_597" class="help_button" intent-id="597"></span>',
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
		
		if(isset($_GET['raw'])){
		    echo_json($view_data);
		    exit;
		}
		
		//Load views:
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/actionplan' , $view_data);
		$this->load->view('console/shared/d_footer');
		
	}
	
	
	function all_classes($b_id){
	    //Authenticate:
	    $udata = auth(2,1);
	    $bootcamps = $this->Db_model->c_full_fetch(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bootcamps[0])){
	        redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
	    }
	    
	    $view_data = array(
	        'title' => 'Classes | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Classes <span id="hb_593" class="help_button" intent-id="593"></span>',
	            ),
	        ),
	    );
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , $view_data);
	    $this->load->view('console/all_classes' , $view_data);
	    $this->load->view('console/shared/d_footer' , array(
	        'load_view' => 'console/modals/new_class',
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
	    $class = filter($bootcamps[0]['c__classes'],'r_id',$r_id);
	    if(!$class){
	        die('<div class="alert alert-danger" role="alert">Invalid class ID.</div>');
	    }
	    
	    //Load in iFrame
	    $this->load->view('console/frames/scheduler' , array( 
	        'title' => 'Edit Schedule | '.time_format($class['r_start_date'],1).' Class | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'class' => $class
	    ));
	}
	
	function load_class($b_id,$r_id){
		//Authenticate:
		$udata = auth(2,1);
		$bootcamps = $this->Db_model->c_full_fetch(array(
		    'b.b_id' => $b_id,
		));
		if(!isset($bootcamps[0])){
		    redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid bootcamp ID.</div>');
		}
		
		//This could be a new run, or editing an existing run:
		$class = filter($bootcamps[0]['c__classes'],'r_id',$r_id);
		if(!$class){
		    redirect_message('/console/'.$b_id.'/classes' , '<div class="alert alert-danger" role="alert">Invalid class ID.</div>');
		}
		
		$view_data = array(
		    'title' => time_format($class['r_start_date'],1).' Class Settings | '.$bootcamps[0]['c_objective'],
		    'bootcamp' => $bootcamps[0],
		    'class' => $class,
		    'breadcrumb' => array(
		        array(
		            'link' => '/console/'.$b_id.'/classes',
		            'anchor' => 'Classes',
		        ),
		        array(
		            'link' => null,
		            'anchor' => time_format($class['r_start_date'],1),
		        ),
		    ),
		);
		
		//Load view
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/class' , $view_data);
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
	                'anchor' => 'Students <span id="hb_594" class="help_button" intent-id="594"></span>',
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
	        'title' => 'Settings | '.$bootcamps[0]['c_objective'],
	        'bootcamp' => $bootcamps[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Settings',
	            ),
	        ),
	    ));
	    $this->load->view('console/settings' , array(
	        'bootcamp' => $bootcamps[0],
	        'udata' => $udata,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
}