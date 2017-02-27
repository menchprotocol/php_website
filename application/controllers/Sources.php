<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sources extends CI_Controller {
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
	}
	
	function index(){
		$top_sources = $this->Us_model->fetch_top_sources();
		$this->load->view('shared/header' , array( 'title' => 'Sources' ));
		$this->load->view('sources/all_sources' , array( 'top_sources' => $top_sources ));
		$this->load->view('shared/footer');
	}
	
	function load_source($source_hashtag){
		
		$this->load->view('shared/header' , array( 'title' => 'Sources' ));
		echo $source_hashtag;
		//$this->load->view('sources/all_sources' , array( 'top_sources' => $top_sources ));
		$this->load->view('shared/footer');
		
	}
}
