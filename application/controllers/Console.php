<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}
	
	function raw($c_id){
	    print_r(load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
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
		        'c.c_status >=' => 0,
		        'c.c_is_grandpa' => true, //Not sub challenges
		    )),
		));
		$this->load->view('console/shared/d_footer' , array(
		    'load_view' => 'console/modals/new_bootcamp',
		));
	}
	
	
	function dashboard($c_id){
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
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Dashboard',
	            ),
	        ),
	    ));
	    $this->load->view('console/dashboard' , array(
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	function curriculum($c_id,$pid=null){
		
		$udata = auth(2,1);
		$pid = ( (isset($pid) && intval($pid)>0) ? $pid : $c_id );
		$level_names = $this->config->item('level_names');
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		$intent = $this->Db_model->c_plain_fetch(array(
		    'c.c_id' => $pid,
		));
		
		//Valid Curriculum?
		if(!isset($intent['c_id'])){
		    redirect_message('/console/'.$c_id.'/curriculum','<div class="alert alert-danger" role="alert">Invalid framework ID. Select another framework to continue.</div>');
		}
		
		//Construct data:
		$view_data = array(
		    'pid' => $pid,
		    'bootcamp' => $bootcamp,
		    'intent' => $intent,
			'cr' => array(
				'inbound' => $this->Db_model->cr_inbound_fetch(array(
						'cr.cr_outbound_id' => $pid,
						'cr.cr_status >=' => 0,
				)),
				'outbound' => $this->Db_model->cr_outbound_fetch(array(
						'cr.cr_inbound_id' => $pid,
						'cr.cr_status >=' => 0,
				)),
			),
		    
		    /*
			'i_messages' => $this->Db_model->i_fetch(array(
				'i_status >=' => 0,
				'i_c_id >=' => $pid,
			)),
		    */
		);
		
		
		
		/*
		 * 
		 * Now lets determine the level of this Curriculum compared to 
		 * the main bootcamp, and construct the breadcrumb accordingly:
		 * 
		 * */
		if($c_id==$pid){
		    
		    //Level 1 (The bootcamp itself)
		    $view_data['level'] = 1;
		    $view_data['title'] = $level_names[1].' Curriculum | '.$intent['c_objective'];
		    $view_data['breadcrumb'] = array(
		        array(
		            'link' => null,
		            'anchor' => $level_names[1].' Curriculum',
		        ),
		    );
		    
		} else {
		    
		    //See if this is level 2, which means directly below main bootcamp (weekly sprint):
		    foreach($view_data['cr']['inbound'] as $relation){
		        if($relation['cr_outbound_id']==$pid && $relation['cr_inbound_id']==$c_id){
		            //Found this as level 2:
		            $view_data['level'] = 2;
		            $view_data['title'] = $level_names[$view_data['level']].' Curriculum | '.$intent['c_objective'];
		            $view_data['breadcrumb'] = array(
		                array(
		                    'link' => '/console/'.$c_id.'/curriculum',
		                    'anchor' => $level_names[1].' Curriculum',
		                ),
		                array(
		                    'link' => null,
		                    'anchor' => $level_names[2].' #'.$relation['cr_outbound_rank'].': '.$intent['c_objective'],
		                ),
		            );
		            //Found it, Exit loop:
		            break;
		        }
		    }
		    
		    //Not level 2? Likely level 3, meaning a sprint objective:
		    if(!isset($view_data['level'])){
		        foreach($view_data['cr']['inbound'] as $relation){
		            if($relation['cr_outbound_id']==$intent['c_id'] && !($relation['cr_inbound_id']==$c_id)){
		                //This is level 3:
		                $view_data['level'] = 3;
		                $view_data['title'] = $level_names[$view_data['level']].' Curriculum | '.$intent['c_objective'];
		                
		                //Fetch level 2 data:
		                $level_2 = $this->Db_model->c_fetch(array(
		                    'c.c_id >=' => $relation['cr_inbound_id'],
		                ));
		                $level_2_relation = $this->Db_model->cr_outbound_fetch(array(
		                    'cr.cr_outbound_id' => $relation['cr_inbound_id'],
		                    'cr.cr_inbound_id' => $c_id,
		                ));
		                
		                
		                //Create breadcrumb:
		                $view_data['breadcrumb'] = array(
		                    array(
		                        'link' => '/console/'.$c_id.'/curriculum',
		                        'anchor' => $level_names[1].' Curriculum',
		                    ),
		                    array(
		                        'link' => '/console/'.$c_id.'/curriculum/'.$relation['cr_inbound_id'],
		                        'anchor' => $level_names[2].' #'.$level_2_relation[0]['cr_outbound_rank'].': '.$level_2[0]['c_objective'],
		                    ),
		                    array(
		                        'link' => null,
		                        'anchor' => $level_names[3].' #'.$relation['cr_outbound_rank'].': '.$intent['c_objective'],
		                    ),
		                );
		                
		                //Found it, Exit loop:
		                break;
		            }
		        }
		    }
		}
		
		
		//Load views:
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/curriculum' , $view_data);
		$this->load->view('console/shared/d_footer');
		
	}
	
	
	function all_cohorts($c_id){
	    //Authenticate:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    $view_data = array(
	        'title' => 'Cohorts | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
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
	        'bootcamp' => $bootcamp,
	    ));
	}
	
	
	function scheduler($c_id,$r_id){
	    //Authenticate:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    //This could be a new run, or editing an existing run:
	    $cohort = filter($bootcamp['runs'],'r_id',$r_id);
	    if(!$cohort){
	        die('<div class="alert alert-danger" role="alert">Invalid cohort ID.</div>');
	    }
	    
	    //Load in iFrame
	    $this->load->view('console/frames/scheduler' , array( 
	        'title' => 'Edit Schedule | '.time_format($cohort['r_start_date'],1).' Cohort | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
	        'cohort' => $cohort
	    ));
	}
	
	function cohort($c_id,$r_id){
		//Authenticate:
		$udata = auth(2,1);
		
		$bootcamp = load_object('c' , array(
				'c.c_id' => $c_id,
				'c.c_is_grandpa' => true,
		));
		
		//This could be a new run, or editing an existing run:
		$run = filter($bootcamp['runs'],'r_id',$r_id);
		if(!$run){
		    redirect_message('/console/'.$c_id.'/cohorts' , '<div class="alert alert-danger" role="alert">Invalid cohort ID.</div>');
		}
		
		$view_data = array(
		    'title' => time_format($run['r_start_date'],1).' Cohort Settings | '.$bootcamp['c_objective'],
		    'bootcamp' => $bootcamp,
		    'run' => $run,
		    'breadcrumb' => array(
		        array(
		            'link' => '/console/'.$c_id.'/cohorts',
		            'anchor' => 'Cohorts',
		        ),
		        array(
		            'link' => null,
		            'anchor' => time_format($run['r_start_date'],1),
		        ),
		    ),
		);
		
		//Load view
		$this->load->view('console/shared/d_header' , $view_data);
		$this->load->view('console/cohort' , $view_data);
		$this->load->view('console/shared/d_footer');
	}
	
	
	
	function students($c_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Students | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Students',
	            ),
	        ),
	    ));
	    $this->load->view('console/students' , array(
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	
	function stream($c_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Activity Stream | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Activity Stream',
	            ),
	        ),
	    ));
	    $this->load->view('console/stream' , array(
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	
	function settings($c_id){
	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(2,1);
	    
	    $bootcamp = load_object('c' , array(
	        'c.c_id' => $c_id,
	        'c.c_is_grandpa' => true,
	    ));
	    
	    //Load view
	    $this->load->view('console/shared/d_header' , array(
	        'title' => 'Bootcamp Settings | '.$bootcamp['c_objective'],
	        'bootcamp' => $bootcamp,
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Bootcamp Settings',
	            ),
	        ),
	    ));
	    $this->load->view('console/settings' , array(
	        'bootcamp' => $bootcamp,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
}