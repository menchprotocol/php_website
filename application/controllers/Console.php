<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Console extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);

        $udata = $this->session->userdata('user');
	}

    function ping(){
        echo_json(array('status'=>'success'));
    }


	
	/* ******************************
	 * Bootcamps
	 ****************************** */
	
	function bootcamps(){

		//Authenticate level 2 or higher, redirect if not:
		$udata = auth(array(1308,1280),1);

		$title = 'My Bootcamps';

		//Load view
		$this->load->view('console/console_header' , array(
		    'title' => $title,
		    'breadcrumb' => array(
		        array(
		            'link' => null,
		            'anchor' => $title.' <span id="hb_6024" class="help_button" intent-id="6024"></span>',
		        ),
		    ),
		));

        //Have they activated their Bot yet?
        //Yes, show them their Bootcamps:
        $this->load->view('console/b/bootcamps_my' , array(
            'bs' => $this->Db_model->coach_bs(array(
                'ba.ba_outbound_u_id' => $udata['u_id'],
                'ba.ba_status >=' => 0,
                'b.b_status >=' => 2,
            )),
            'udata' => $udata,
        ));

		//Footer:
		$this->load->view('console/console_footer');

	}
	
	
	function dashboard($b_id){

	    //Authenticate level 2 or higher, redirect if not:
	    $udata = auth(null,1,$b_id);
	    $bs = $this->Db_model->remix_bs(array(
	        'b.b_id' => $b_id,
	    ));
	    if(!isset($bs[0])){
	        redirect_message('/console','<div class="alert alert-danger maxout" role="alert">Invalid Bootcamp ID.</div>');
	    }
	    
	    if(isset($_GET['raw'])){
	        echo_json($bs[0]);
	        exit;
	    }
	    
	    $title = 'Dashboard | '.$bs[0]['c_outcome'];
	    
	    //Log view:
	    $this->Db_model->e_create(array(
	        'e_inbound_u_id' => $udata['u_id'], //The user that updated the account
	        'e_json' => array(
	            'url' => $_SERVER['REQUEST_URI'],
	        ),
	        'e_inbound_c_id' => 48, //View
	        'e_text_value' => $title,
	        'e_b_id' => $bs[0]['b_id'],
	    ));
	    
	    //Load view
	    $this->load->view('console/console_header' , array(
	        'title' => $title,
	        'b' => $bs[0],
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Dashboard',
	            ),
	        ),
	    ));
	    $this->load->view('console/b/dashboard' , array(
	        'b' => $bs[0],
	    ));
	    $this->load->view('console/console_footer');
	}
	
	
	function actionplan($b_id,$pid=null){
		
	    $udata = auth(null,1,$b_id);
		$bs = $this->Db_model->remix_bs(array(
		    'b.b_id' => $b_id,
		));
		if(!isset($bs[0])){
		    redirect_message('/console','<div class="alert alert-danger maxout" role="alert">Invalid Bootcamp ID.</div>');
		}

		//Fetch intent relative to the Bootcamp by doing an array search:
		$view_data = extract_level( $bs[0] , ( intval($pid)>0 ? $pid : $bs[0]['c_id'] ) );


        if(isset($_GET['raw'])){
            //For testing purposes:
            echo_json($view_data['b']);
            exit;
        } elseif(isset($_GET['tree'])){
            //For testing purposes:
            if(isset($_GET['c_id'])){
                //Try to find sub-set of tree:
                echo_json(find_c_tree($view_data['b']['c__tree'], $_GET['c_id']));
            } else {
                //Echo entire tree:
                echo_json($view_data['b']['c__tree']);
            }
            exit;
        } elseif(!$view_data){
		    redirect_message('/console/'.$b_id.'/actionplan','<div class="alert alert-danger" role="alert">Invalid Step ID. Select another Step to continue.</div>');
		} else {
		    //Append universal (Flat design) breadcrumb:
            $view_data['breadcrumb'] = array(
                array(
                    'link' => null,
                    'anchor' => 'Action Plan <span id="hb_2272" class="help_button" intent-id="2272"></span>'.( 0 ? ' <a href="#" data-toggle="modal" data-target="#importActionPlan" class="tipbtn"><span class="badge tip-badge" title="Import some part or all of prerequisites, Tasks and/or Outcomes from another Bootcamp you manage" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-download"></i></span></a>' : ''),
                ),
            );
        }

		
		//Load views:
		$this->load->view('console/console_header' , $view_data);
		$this->load->view('console/b/actionplan' , $view_data);
		$this->load->view('console/console_footer'); //array('load_view' => 'console/b/frame_import_actionplan')
		
	}
	
	
	function classes($b_id){
	    //Authenticate:
	    $udata = auth(null,1,$b_id);
	    $bs = $this->Db_model->remix_bs(array(
	        'b.b_id' => $b_id,
	    ));
        if(!isset($bs[0])){
            redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
        }
	    
	    $view_data = array(
	        'title' => 'Classes | '.$bs[0]['c_outcome'],
            'b' => $bs[0],
            'udata' => $udata,
	        'breadcrumb' => array(
	            array(
	                'link' => null,
	                'anchor' => 'Classes'
                        .($bs[0]['b__coaches'][0]['u_id']==$udata['u_id'] ? '' : '<i class="fas fa-lock" style="font-size:0.8em; margin:0 0 0 5px;" data-toggle="tooltip" data-placement="bottom" title="Support settings locked because you are not the Lead Coach of this Bootcamp"></i>')
                        .' <span id="hb_2274" class="help_button" intent-id="2274"></span>',
	            ),
	        ),
	    );
	    
	    //Load view
	    $this->load->view('console/console_header' , $view_data);
	    $this->load->view('console/b/classes' , $view_data);
	    $this->load->view('console/console_footer');
	}


    function settings($b_id){
        //Authenticate level 2 or higher, redirect if not:
        $udata = auth(null,1,$b_id);
        $bs = $this->Db_model->remix_bs(array(
            'b.b_id' => $b_id,
        ));
        if(!isset($bs[0])){
            redirect_message('/console','<div class="alert alert-danger" role="alert">Invalid Bootcamp ID.</div>');
        }

        //Load view
        $this->load->view('console/console_header' , array(
            'title' => 'Settings | '.$bs[0]['c_outcome'],
            'b' => $bs[0],
            'breadcrumb' => array(
                array(
                    'link' => null,
                    'anchor' => 'Settings',
                ),
            ),
        ));
        $this->load->view('console/b/settings' , array(
            'b' => $bs[0],
            'udata' => $udata,
        ));
        $this->load->view('console/console_footer');
    }
	
}