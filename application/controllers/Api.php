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
			http_404("Invalid edit ID. Refresh the page and try again.");
		}
		if(strlen($new_value)<1){
			http_404("You must enter a new value for  updating. Try again.");
		}
		
		//First fetch the data to see what we got:
		$row = $this->Us_model->fetch_id($id);
		$mapped = 0; //For level 7
		
		if($row['id']!=$id){
			http_404("Row #".$id." could not be found in the database.");
		}
		if($row['status']<0){
			http_404("You cannot edit an inactive field.");
		}
		
		
		
		//How does the input look like?
		if(intval($row['parent'])==2){
			//Hashtag validation:
			if(!ctype_alnum($new_value)){
				//Unallowed characters:
				http_404("Hashtags can only contain A-Z and 0-9");
			}
			if(strlen($new_value)>29){
				http_404("Hashtags must be 29 characters or less.");
			}
		} elseif(intval($row['parent'])==7){
			
			//Now lets see if this text has any hashtags that need to be referenced:
			if(substr_count($new_value,'#')>0){
				//Yes, we do!
				$hashtags = explode('#',$new_value);
				foreach($hashtags as $key=>$ht){
					if($key==0){
						continue;
					}
					
					//Find the end of the hashtag:
					$hashtag_terms = explode(' ',$ht,2); //We're looking for $hashtag_terms[0]
					
					//Now lets see if this is a valid hashtag:
					if(valid_hashtag($hashtag_terms[0])){
						//Yes' we're in business of linking!
						//Lets find the hashtag:
						$hashtag_row = $this->Us_model->search_node($hashtag_terms[0]);
						
						if(count($hashtag_row)>1){
							//Oooops, we found two similar hashtags?!
							//TODO send admin an error!
						}
						
						//Now Insert into DB:
						if(count($hashtag_row)==1 && count($hashtag_row[0])>0){
							
							//Yes, it's here:
							$this->Us_model->insert_row(array(
								'id' => next_id(),
								'status' => 1,
								'node_id' => $row['node_id'],
								'node_depth' => 1,
								'parent' => 8, //This is a #Reference data type
								'time' => date("Y-m-d H:i:s"),
								'value_string' => $hashtag_row[0]['value_string'], //The value as recorded in DB
								'value_int' => $row['value_int'],
								'rank' => -1, //This is hidden data, nor directly shown on page
								'meta_data' => null,
								'creator' => current_user_id(),
							));
							
							//Increase counter:
							$mapped++;
						}
					}
				}
			}
		}
		
		
		
		//Error validation done!
		$next_id = next_id();
		//First insert new row:
		$this->Us_model->insert_row(array(
			'id' => $next_id,
			'status' => 1, //TODO: Change based on user permissions
			'node_id' => $row['node_id'],
			'node_depth' => $row['node_depth'],
			'parent' => $row['parent'],
			'time' => date("Y-m-d H:i:s"),
			'value_string' => $new_value,
			'value_int' => $row['value_int'],
			'rank' => $row['rank'],
			'meta_data' => null,
			'creator' => current_user_id(),
		));
		
		//Lets replace the new data:
		$this->Us_model->update_with_id( $id , array(
			'status' => '-1',
			'parent' => $next_id,
		));
		
		header("HTTP/1.1 200 Successfully Updated & ".$mapped." Mapped");
		header('Content-Type: application/json');
		echo json_encode(array(
			'success' => 1,
			'id' => $id,
			'new_id' => $next_id,
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
