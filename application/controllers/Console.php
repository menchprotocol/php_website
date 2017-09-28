<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	/* ******************************
	 * User & Help
	 ****************************** */
	
	function v_account(){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//Append title:
		$view_data['title'] = 'My Account';
		
		//This lists all users based on the permissions of the user
		$this->load->view('console/shared/d_header', $view_data);
		$this->load->view('console/v_account');
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
	
	function showdown_markup(){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(1,1);
	    
	    //Load views
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Guides | Showdown Markup Syntax',
	    ));
	    $this->load->view('console/guides/showdown_markup');
	    $this->load->view('console/shared/d_footer');
	}
	
	
	
	/* ******************************
	 * Bootcamps
	 ****************************** */
	
	function v_all_bootcamps(){
		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(2,1);
		
		//Load view
		$this->load->view('console/shared/d_header' , array(
				'title' => 'Bootcamps',
		));
		$this->load->view('console/v_all_bootcamps' , array(
				'challenges' => $this->Db_model->c_fetch(array(
				    'c.c_status >=' => 0,
					'c.c_is_grandpa' => true, //Not sub challenges
				)),
		));
		$this->load->view('console/shared/d_footer');
	}
	
	
	function v_dashboard($c_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Dashboard | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/v_dashboard' , array(
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	
	function v_content($c_id,$pid=null){
		
		$udata = auth(2,1);
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		$pid = ( isset($pid) && intval($pid)>0 ? $pid : $bootcamp['c_id'] );
		//Construct data:
		$view_data = array(
				'bootcamp' => $bootcamp,
				'pid' => $pid,
				'cr' => array(
						'c' => $this->Db_model->c_plain_fetch(array(
								'c.c_id' => $pid,
						)),
						'inbound' => $this->Db_model->cr_inbound_fetch(array(
								'cr.cr_outbound_id' => $pid,
								'cr.cr_status >=' => 0,
						)),
						'outbound' => $this->Db_model->cr_outbound_fetch(array(
								'cr.cr_inbound_id' => $pid,
								'cr.cr_status >=' => 0,
						)),
				),
				'i_messages' => $this->Db_model->i_fetch(array(
						'i_status >=' => 0,
						'i_c_id >=' => $pid,
				)),
		);
		
		//Valid challenge key?
		if(!isset($view_data['cr']['c']['c_id'])){
			redirect_message('/console/'.$c_id.'/framework','<div class="alert alert-danger" role="alert">Invalid framework ID. Select another framework to continue.</div>');
		}
		
		//Append Title:
		$view_data['title'] = 'Content Library | '.$view_data['cr']['c']['c_objective'];
		
		//Show View
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/v_content' , $view_data);
		$this->load->view('console/shared/d_footer');
	}
	
	
	function v_all_cohorts($c_id){
	    //Authenticate:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    $view_data = array(
	        'title' => 'Cohorts | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
	    );
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , $view_data);
	    $this->load->view('console/v_all_cohorts' , $view_data);
	    $this->load->view('console/shared/d_footer');
	}
	

	
	function v_cohort($c_id,$r_id){
		//Authenticate:
		$udata = auth(2,1);
		
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//This could be a new run, or editing an existing run:
		$run = filter($bootcamp['runs'],'r_id',$r_id);
		if(!$run){
		    redirect_message('/console/'.$c_id , '<div class="alert alert-danger" role="alert">Invalid run.</div>');
		}
		
		$view_data = array(
		    'title' => 'Cohort #'.$r_id.' | '.$bootcamp['c_objective'],
		    'bootcamp' => $bootcamp,
		    'run' => $run,
		);
		
		//Load view
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/v_cohort' , $view_data);
		$this->load->view('console/shared/d_footer');
	}
	
	
	
	function v_community($c_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Community | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/v_community' , array(
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	
	function v_timeline($c_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Timeline | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/v_timeline' , array(
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	
}