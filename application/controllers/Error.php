<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(FALSE);
	}

	function hi(){
	    echo 'hi's;
	}
}