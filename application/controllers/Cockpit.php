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
	
	
	function all($object_name){
	    //Authenticate level 3 or higher, redirect if not:
	    $udata = auth(3,1);
	    
	    $this->load->view('console/shared/d_header', array(
	        'title' => 'All '.ucwords($object_name),
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'All '.ucwords($object_name),
	            ),
	        ),
	    ));
	    $this->load->view('cockpit/list' , array(
	        'object_name' => $object_name,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
	function engagements(){
	    //Authenticate level 3 or higher, redirect if not:
	    $udata = auth(3,1);
	    
	    //Define engagement filters:
	    $engagement_filters = array(
	        'e_type_id' => 'Choose Engagement Type',
	        'e_initiator_u_id' => 'Initiator User ID',
	        'e_recipient_u_id' => 'Recipient User ID',
	        'e_b_id' => 'Bootcamp ID',
	        'e_r_id' => 'Class ID',
	        'e_c_id' => 'Intent ID',
	    );
	    $title_suffix = '';
	    $match_columns = array();
	    foreach($engagement_filters as $key=>$value){
	        if(isset($_GET[$key]) && intval($_GET[$key])>0){
	            $match_columns[$key] = intval($_GET[$key]);
	            $title_suffix .= ' | '.$value.' '.intval($_GET[$key]);
	        }
	    }
	    
	    //Fetch engagements with possible filters:
	    $engagements = $this->Db_model->e_fetch($match_columns,50);
	    
	    //This lists all users based on the permissions of the user
	    $this->load->view('console/shared/d_header', array(
	        'title' => 'Platform-Wide Engagements'.$title_suffix,
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Platform-Wide Engagements',
	            ),
	        ),
	    ));
	    $this->load->view('cockpit/engagements' , array(
	        'engagements' => $engagements, //Fetch recent engagements
	        'e_type_id' => $this->Db_model->a_fetch(), //This would turn this $engagement_filters into a drop down based on key=>value structure
	        'engagement_filters' => $engagement_filters,
	    ));
	    $this->load->view('console/shared/d_footer');
	}
	
}