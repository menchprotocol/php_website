<?php




function version_salt(){
	//This variable ensures that the CSS/JS files are being updated upon each launch
	return 'v1.17';
}





function default_start(){
	//Return the default start node for the current user, if any
	return 'US';
}
function current_user_id(){
	//TODO: Later change this based on session data
	return 20;
}
function next_id(){
	//TODO: These is a bug in the auto ID creation, need to look at it later
	//This can be eliminated if the native ID incrementer works
	$CI =& get_instance();
	$new_id = $CI->Us_model->next_id();
	return $new_id;
}

function http_404($message){
	header("HTTP/1.1 404 ".$message);
	die();
}

function valid_hashtag($text){
	//TODO expand upon this, set hashtag policy, check first letter, etc...
	return (ctype_alnum($text));
}

function all_ses_data(){
	$CI =& get_instance();
	return $CI->session->all_userdata();
}

function metadata_types(){
	//TODO: implement
	return array(
	 	1 => array(
	 			'name' => 'External ID',
	 			'icon' => '<span class="glyphicon glyphicon-link" aria-hidden="true"></span>',
	 			'storage' => 'value_int',
	 	),
		14 => array(
				'name' => 'User',
				'icon' => '<span class="glyphicon glyphicon-user" aria-hidden="true"></span>',
				'storage' => 'value_int',
		),
		13 => array(
				'name' => 'Pattern',
				'icon' => '<span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>',
				'storage' => 'value_int',
		),
		11 => array(
				'name' => 'Number',
				'icon' => '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>',
				'storage' => 'value_string',
		),
		8 => array(
				'name' => 'Dollar',
				'icon' => '<span class="glyphicon glyphicon-usd" aria-hidden="true"></span>',
				'storage' => 'value_string',
		),
		3 => array(
 				'name' => 'Date',
				'icon' => '<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>',
				'storage' => 'value_int',
		),
		2 => array(
				'name' => 'Date/Time',
				'icon' => '<span class="glyphicon glyphicon-time" aria-hidden="true"></span>',
				'storage' => 'value_int',
		),
		12 => array(
				'name' => 'Text',
				'icon' => '<span class="glyphicon glyphicon-font" aria-hidden="true"></span>',
				'storage' => 'value_string',
		),
		4 => array(
				'name' => 'Text Area',
				'icon' => '<span class="glyphicon glyphicon-comment" aria-hidden="true"></span>',
				'storage' => 'value_string',
		),
		5 => array(
				'name' => 'Checkbox',
				'icon' => '<span class="glyphicon glyphicon-check" aria-hidden="true"></span>',
				'storage' => 'value_int',
		),
		7 => array(
				'name' => 'Picklist',
				'icon' => '<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>',
				'storage' => 'value_string',
		),
		6 => array(
				'name' => 'Email',
				'icon' => '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>',
				'storage' => 'value_string',
		),
		9 => array(
				'name' => 'Phone',
				'icon' => '<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>',
				'storage' => 'value_string',
		),
		10 => array(
				'name' => 'URL',
				'icon' => '<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>',
				'storage' => 'value_string',
		),
		/*
		15 => array(
				'name' => 'Single File',
				'icon' => '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>',
				'storage' => 'value_int',
		),
		16 => array(
				'name' => 'Multiple Files',
				'icon' => '<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>',
				'storage' => 'value_int',
		),
		*/
	);
}

function prep_metadata_for_edit($data){
	//TODO: implement
	//This function translates the original data into an editable mode:
	$return_array = array();
	foreach($data as $d){
		if($d['hide_from_ui']=='t'){
			//Skip this guy:
			continue;
		}
		
		//What do HTML inputs take for editing?
		if($d['type_id']==3){
			//Date
			$return_array[$d['clean_name']] = date('Y-m-d' , $d['value_int']);
		} elseif($d['type_id']==2){
			//Date/Time
			$return_array[$d['clean_name']] = date('Y-m-d\TH:i:s' , $d['value_int']);
		} elseif(strlen($d['value_string'])>0){
			//Any other string field
			//Need to cleanup the single quote:
			$return_array[$d['clean_name']] = str_replace('\'','&apos;',$d['value_string']);
		} else {
			//This is an integer
			$return_array[$d['clean_name']] = $d['value_int'];
		}
	}
	return $return_array;
}


function data_validate_cleanup($type_id,$value){
	//TODO: implement
	$CI =& get_instance();
	$value = trim($value);
	
	if(strlen($value)<=0){
		//Nothing has been passed!
		if($type_id==5){
			//If a checkbox is false, it would return null, so lets return false:
			return 0;
		} else {
			return null;
		}
	}
	
	if($type_id==1){
		//External ID
		return ( intval($value)>0 ? intval($value) : null );
	} elseif($type_id==5){
		//checkbox, which is never NULL
		return ( strtolower($value)=='on' || intval($value) ? 1 : 0 );
	} elseif($type_id==9){
		//Phone number:
		$phone_number = preg_replace('/\D/', '', $value);
		return ( strlen($phone_number)>=4 ? $phone_number : null );
	} elseif($type_id==2 || $type_id==3){
		//Date/Time && Date
		return ( strtotime($value) ? strtotime($value) : null );
	} elseif($type_id==11 || $type_id==8){
		//Number and dollar, both MAY have decimal values
		return floatval($value);
	} elseif($type_id==6){
		//Email address:
		return ( filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : null );
	} elseif($type_id==10){
		//URL
		return ( filter_var($value, FILTER_VALIDATE_URL) ? $value : null );
	} elseif($type_id==13){
		//Pattern reference ID
		$validate_pattern = $CI->Patterns_model->fetch_pattern_from_id(intval($value));
		return ( $validate_pattern['id'] ? intval($value) : null );
	} elseif($type_id==4 || $type_id==12){
		//Text & Text Area
		return $value;
	} elseif($type_id==7){
		//Pick list
		//TODO: Validate with database possible inputs to ensure it matches!
		return $value;
	} elseif($type_id==14){
		//TODO: Users, to be deleted soon
		return intval($value);
	} else {
		//Unknown?!
		return null;
	}
}
