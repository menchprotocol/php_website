<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		if(!auth(1)){
			//Check for session for all functions here!
			echo_html(0,'Invalid session! Login and try again...');
			exit;
		}
	}

	function test(){
		$this->Apiai_model->addEntity();
	}
	
}