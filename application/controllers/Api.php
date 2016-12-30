<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct() {
		parent::__construct();
	
		//Load our buddies:
		$this->output->enable_profiler(FALSE);
		
		$this->load->model('Us_model');
	}
	
	
	function autocomplete(){
		$data = $this->Us_model->search_node(@$_GET['keyword'],intval(@$_GET['parentScope']));
		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	function edit_value_string(){
		$id = intval($_REQUEST['pk']);
		$new_value = trim($_REQUEST['value']);
		/*
		 * To edit a single row, here is the process:
		 * 
		 * 1. Insert data as new field with new data
		 * 2. Set status for old row to -1, indicating it's replaced with a newer version (edited)
		 * 3. Set "parent" field of the old row to be the ID of the new row, indicating its replacement
		 * 
		 */
		
		if($id<1){
			header("HTTP/1.1 404 Invalid edit ID. Refresh the page and try again.");
			die();
		}
		if(strlen($new_value)<1){
			header("HTTP/1.1 404 You must enter a new value for  updating. Try again.");
			die();
		}
		
		//First fetch the data to see what we got:
		$row = $this->Us_model->fetch_id($id);
		
		if($row['id']!=$id){
			header("HTTP/1.1 404 Row #".$id." could not be found in the database.");
			die();
		}
		if($row['status']<0){
			header("HTTP/1.1 404 You cannot edit an inactive field.");
			die();
		}
		
		//How does the input look like?
		if(intval($row['parent'])==2){
			//Hashtag validation:
			if(!ctype_alnum($new_value)){
				//Unallowed characters:
				header("HTTP/1.1 404 Hashtags can only contain A-Z and 0-9");
				die();
			}
			if(strlen($new_value)>29){
				header("HTTP/1.1 404 Hashtags must be 29 characters or less.");
				die();
			}
		}
		
		//Error validation done!
		//First insert new row:
		$new_id = $this->Us_model->next_id();
		
		$this->Us_model->insert_row(array(
			'id' => $new_id, //TODO: These is a bug in the auto ID creation, need to look at it later
			'status' => 1, //TODO: Change based on user permissions
			'node_id' => $row['node_id'],
			'node_depth' => $row['node_depth'],
			'parent' => $row['parent'],
			'time' => date("Y-m-d H:i:s"),
			'value_string' => $new_value,
			'value_int' => $row['value_int'],
			'rank' => $row['rank'],
			'meta_data' => null,
			'creator' => 20, //TODO: Later change this based on session data
		));
		
		//Lets replace the new data:
		$this->Us_model->update_with_id( $id , array(
			'status' => '-1',
			'parent' => $new_id,
		));
		
		header("HTTP/1.1 200 Successfully Updated");
		
		header('Content-Type: application/json');
		echo json_encode(array(
			'success' => 1,
			'id' => $id,
			'new_id' => $new_id,
			'parent' => intval($row['parent']),
			'value_string' => $new_value,
		));
	}
	
	function quick_link(){
		print_r($_REQUEST);
	}
	
	function update_sort($node_id){
		
	}
}
