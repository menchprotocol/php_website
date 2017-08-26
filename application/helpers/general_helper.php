<?php

function is_dev(){
	return in_array($_SERVER['SERVER_NAME'],array('local.mench.co'));
}

function fetch_file_ext($url){
	//https://cdn.fbsbx.com/v/t59.3654-21/19359558_10158969505640587_4006997452564463616_n.aac/audioclip-1500335487327-1590.aac?oh=5344e3d423b14dee5efe93edd432d245&oe=596FEA95
	$url_parts = explode('?',$url,2);
	$url_parts = explode('/',$url_parts[0]);
	$file_parts = explode('.',end($url_parts));
	return end($file_parts);
}

function echo_cr($c_id,$relation,$direction){
	//Fetch current Challenge:	
	return '<a id="cr_'.$relation['cr_id'].'" data-link-id="'.$relation['cr_id'].'" href="/marketplace/'.$c_id.'/'.$relation['c_id'].'" class="list-group-item is_sortable"><i class="fa fa-sort" aria-hidden="true" style="padding-right:10px;"></i><span class="pull-right"><i class="fa fa-times" onclick="cr_delete('.$relation['cr_id'].',\''.str_replace('\'','',str_replace('"','',$relation['c_objective'])).'\');"></i> <span class="label label-'.($direction=='outbound'?'primary':'default').'">'.$direction.' <i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>'.$relation['c_objective'].' <span class="srt-'.$direction.'"></span></a>';
}

function echo_users($users){
	foreach($users as $i=>$user){
		if($i>0){
			echo ', ';
		}
		echo '<a href="/user/'.$user['u_url_key'].'">@'.$user['u_url_key'].'</a>';
	}
}

function load_object($object,$obj_limits){
	
	$CI =& get_instance();
	
	if($object=='c'){
		//Fetch Challenge:
		$fetch_challenges = $CI->Db_model->c_fetch($obj_limits);
		
		//Valid challenge key?
		if(!count($fetch_challenges)==1){
			redirect_message('/marketplace','<div class="alert alert-danger" role="alert">Invalid challenge key.</div>');
		}
		
		//Append more data:
		$fetch_challenges[0]['runs'] = $CI->Db_model->r_fetch(array(
				'r.r_c_id' => $fetch_challenges[0]['c_id'],
				'r.r_status >=' => 0,
		));
		
		return $fetch_challenges[0];
	} elseif($object=='u'){
		
		//Fetch users:
		$users = $CI->Db_model->users_fetch($obj_limits);
		
		if(!count($users)==1){
			//Ooops, something wrong:
			//TODO Redirect to a user index instead of the marketpace
			redirect_message('/marketplace','<div class="alert alert-danger" role="alert">Invalid username.</div>');
		}
		
		//Append permissions:
		$users[0]['access'] = $CI->Db_model->fetch_user_access($users[0]['u_id']);
		
		return $users[0];
	}
		
}

function run_icon($run_version){
	if($run_version==1){
		return '<i class="material-icons">looks_one</i>';
	} elseif($run_version==2){
		return '<i class="material-icons">looks_two</i>';
	} elseif($run_version>2 && $run_version<=6){
		return '<i class="material-icons">looks_'.$run_version.'</i>';
	} else {
		//Ooops, no option here!
		return $run_version;
	}
}



function status_bible($object=null,$status=null){
	
	$CI =& get_instance();
	
	/* ******************************
	 * OBJECTS
	 ****************************** */
	$o_name = array( //Name
			-2 	=> 'DISQUALIFIED',
			-1 	=> 'DELETED',
			0 	=> 'PREPPING', //Normally Default
			1	=> 'LIVE',
			2	=> 'DONE', //Not for all objects
	);
	$o_desc = array( //Insight
			-2 	=> 'removed because it did meet our community guidelines.',
			-1 	=> 'deleted by user.',
			0 	=> 'created and being prepared to be published live. Users cannot see challenges until they are published live.', //Normally Default
			1	=> 'is live and visible to the Mench community.',
			2	=> 'finished and completed.',
	);
	
	
	/* ******************************
	 * USERS
	 ****************************** */
	$u_name = array( //Name
			-2 	=> 'DISQUALIFIED',
			-1 	=> 'DELETED',
			0 	=> 'INVITED',
			1 	=> 'MEMBER',
			2	=> 'CONTRIBUTOR',
			3	=> 'LEADER',
			4	=> 'ADMIN',
	);
	$u_desc = array( //Name
			-2 	=> 'removed because it did meet our community guidelines.',
			-1 	=> 'deleted their account by their own will.',
			0 	=> 'has been invited through a friend.',
			1 	=> 'is an active member.',
			2	=> 'is an Mench researcher and content contributor.',
			3	=> 'is an leader in one or more runs.',
			4	=> 'is an Mench administrator.',
	);
	
	
	//Ok draft bible:
	$status_bible = array(
			
			/* ******************************
			 * OBJECTS
			 ****************************** */
			'c' => array( //Challenges
					-2 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('c_name').' '.$o_desc[-2].'">'.$o_name[-2].' Challenge <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					-1 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('c_name').' '.$o_desc[-1].'">'.$o_name[-1].' Challenge <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					0 	=> '<span class="label label-default" 	data-toggle="tooltip" title="'.$CI->lang->line('c_name').' '.$o_desc[0].'">'.$o_name[0].' Challenge <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					1	=> '<span class="label label-success" 	data-toggle="tooltip" title="'.$CI->lang->line('c_name').' '.$o_desc[1].'">'.$o_name[1].' Challenge <i class="fa fa-info-circle" aria-hidden="true"></i></span>', //Default
			),
			'r' => array( //Runs
					-2 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('r_name').' '.$o_desc[-2].'">'.$o_name[-2].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					-1 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('r_name').' '.$o_desc[-1].'">'.$o_name[-1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					0 	=> '<span class="label label-default" 	data-toggle="tooltip" title="'.$CI->lang->line('r_name').' '.$o_desc[0].'">'.$o_name[0].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>', //Default
					1	=> '<span class="label label-success" 	data-toggle="tooltip" title="'.$CI->lang->line('r_name').' '.$o_desc[1].'">'.$o_name[1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					2	=> '<span class="label label-info" 		data-toggle="tooltip" title="'.$CI->lang->line('r_name').' '.$o_desc[2].'">'.$o_name[2].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
			),
			'i' => array( //Insights
					-2 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('i_name').' '.$o_desc[-2].'">'.$o_name[-2].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					-1 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('i_name').' '.$o_desc[-1].'">'.$o_name[-1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					0 	=> '<span class="label label-default" 	data-toggle="tooltip" title="'.$CI->lang->line('i_name').' '.$o_desc[0].'">'.$o_name[0].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>', //Default
					1	=> '<span class="label label-success" 	data-toggle="tooltip" title="'.$CI->lang->line('i_name').' '.$o_desc[1].'">'.$o_name[1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
			),
			'cr' => array( //Challenge Relations (to Insights)
					-2 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('cr_name').' '.$o_desc[-2].'">'.$o_name[-2].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					-1 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('cr_name').' was replaced by a new reference or '.$o_desc[-1].'">'.$o_name[-1].'</span>',
					1	=> '<span class="label label-success" 	data-toggle="tooltip" title="'.$CI->lang->line('cr_name').' '.$o_desc[1].'">'.$o_name[1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
			),
			
			
			/* ******************************
			 * USERS
			 ****************************** */
			'u' => array( //Users
					-2 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('u_name').' '.$u_desc[-2].'">'.$u_name[-2].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					-1 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('u_name').' '.$u_desc[-1].'">'.$u_name[-1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					0 	=> '<span class="label label-default" 	data-toggle="tooltip" title="'.$CI->lang->line('u_name').' '.$u_desc[0].'">'.$u_name[0].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					1 	=> '<span class="label label-success" 	data-toggle="tooltip" title="'.$CI->lang->line('u_name').' '.$u_desc[1].'">'.$u_name[1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>', //Default
					2	=> '<span class="label label-rose" 		data-toggle="tooltip" title="'.$CI->lang->line('u_name').' '.$u_desc[2].'">'.$u_name[2].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					3	=> '<span class="label label-rose" 		data-toggle="tooltip" title="'.$CI->lang->line('u_name').' '.$u_desc[3].'">'.$u_name[3].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					4	=> '<span class="label label-rose" 		data-toggle="tooltip" title="'.$CI->lang->line('u_name').' '.$u_desc[4].'">'.$u_name[4].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
			),
			'ru' => array( //Users who joined a particular run, either as Admin or Participants
					-2 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('ru_name').' status is '.$u_desc[-2].'">'.$u_name[-2].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>', //Default
					-1 	=> '<span class="label label-danger" 	data-toggle="tooltip" title="'.$CI->lang->line('ru_name').' status is '.$u_desc[-1].'">'.$u_name[-1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>', //Default
					0 	=> '<span class="label label-default"	data-toggle="tooltip" title="'.$CI->lang->line('ru_name').' status is '.$u_desc[0].'">'.$u_name[0].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>', //Default
					1 	=> '<span class="label label-success" 	data-toggle="tooltip" title="'.$CI->lang->line('ru_name').' status is '.$u_desc[1].'">'.$u_name[1].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>', //Default
					2	=> '<span class="label label-rose" 		data-toggle="tooltip" title="'.$CI->lang->line('ru_name').' status is '.$u_desc[2].'">'.$u_name[2].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
					3	=> '<span class="label label-rose" 		data-toggle="tooltip" title="'.$CI->lang->line('ru_name').' status is '.$u_desc[3].'">'.$u_name[3].' <i class="fa fa-info-circle" aria-hidden="true"></i></span>',
			),
	);
	
	
	
	//Return what's asked for:
	if($object==null){
		//Everything
		return $status_bible;
	} elseif($status==null){
		//Object Specific
		return $status_bible[$object];
	} else {
		//Object & Status Specific
		return $status_bible[$object][intval($status)];
	}
}

function filter($array,$ikey,$ivalue){
	if(!is_array($array) || count($array)<=0){
		return null;
	}
	foreach($array as $key=>$value){
		if(isset($value[$ikey]) && $value[$ikey]==$ivalue){
			return $array[$key];
		}
	}
	return null;
}

//2x Authentication Functions:

function auth($min_level,$force_redirect=0){
	
	$CI =& get_instance();
	$udata = $CI->session->userdata('user');
	
	if(!isset($udata['u_status']) || intval($udata['u_status'])<intval($min_level)){
		//Ooops, there is an error:
		if(!$force_redirect){
			return false;
		} else {
			//Block access:
			header( 'Location: /missing_access?url='.$_SERVER['REQUEST_URI'] );
		}
	}
	
	return $udata;
}
function can_modify($object,$object_id){
	
	$CI =& get_instance();
	$udata = $CI->session->userdata('user');
	
	//Validate:
	if(isset($udata['u_status']) && $udata['u_status']>=2){
		if(in_array($object,array('c','r'))){
			
			return in_array($object_id,$udata['access'][$object]);
			
		} elseif($object=='u'){
			
			return ($udata['u_id']==$object_id || $udata['u_status']>=4);
			
		}
	}
	
	//No access:
	return false;
}

function redirect_message($url,$message){
	$CI =& get_instance();
	$CI->session->set_flashdata('hm', $message);
	header("Location: ".$url);
}

function save_file($file_url,$json_data){
	$CI =& get_instance();
	
	$file_name = md5($file_url.time().'someSa!t').'.'.fetch_file_ext($file_url);
	$file_path = 'application/cache/temp_files/';
	
	//Fetch Remote:
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $file_url);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$result = curl_exec($ch);
	curl_close($ch);
	
	//Write in directory:
	$fp = fopen( $file_path.$file_name , 'w');
	fwrite($fp, $result);
	fclose($fp);
	
	//Then upload to AWS S3:
	require( '/var/www/us/application/libraries/aws/aws-autoloader.php' );
	$s3 = new Aws\S3\S3Client([
			'version' 		=> 'latest',
			'region'  		=> 'us-west-2',
			'credentials' 	=> $CI->config->item('aws_credentials'),
	]);
	$result = $s3->putObject(array(
			'Bucket'       => 's3foundation', //Same bucket for now
			'Key'          => $file_name,
			'SourceFile'   => $file_path.$file_name,
			'ACL'          => 'public-read'
	));
	
	if(isset($result['ObjectURL']) && strlen($result['ObjectURL'])>10){
		@unlink($file_path.$file_name);
		return $result['ObjectURL'];
	} else {
		log_error('Unable to upload Facebook Message Attachment ['.$file_url.'] to Internal Storage.' , $json_data);
		return false;
	}
}

function ping_admin($message , $from_log_error=false){
	$CI =& get_instance();
	$CI->Facebook_model->send_message(array(
			'recipient' => array(
					'id' => '1344093838979504', //Shervin
			),
			'message' => array(
					'text' => $message,
					'metadata' => 'SKIP_ECHO_LOGGING', //Prevent further impression logging on this.
			),
			'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
	) , $from_log_error );
}

function log_error($error_message, $json_data=array()){
	$CI =& get_instance();
	
	//First log error in DB:
	//TODO improve to log details like platform_pid and us_id based on error origin
	$res = $CI->Us_model->log_engagement(array(
			'message' => $error_message,
			'action_pid' => 1033, //Error logging
			'json_blob' => json_encode($json_data),
			'us_id' => 766,
			'platform_pid' => 766,
	));
	
	//Notifty admin via Messenger:
	ping_admin('Error #'.$res['id'].': '.$error_message, true);
	//Return error ID:
	return $res['id'];
}

function fb_time($unix_time){
	//It has milliseconds like "1458668856253", which we need to tranform for DB insertion:
	return date("Y-m-d H:i:s",round($unix_time/1000));
}

function curl_html($url){
	$ch = curl_init($url);
	curl_setopt_array($ch, array(
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_POST => FALSE,
			CURLOPT_RETURNTRANSFER => TRUE,
	));
	return curl_exec($ch);
}

function boost_power(){
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
}


function objectToArray( $object ) {
	if( !is_object( $object ) && !is_array( $object ) ) {
		return $object;
	}
	if( is_object( $object ) ) {
		$object = (array) $object;
	}
	return array_map( 'objectToArray', $object );
}


function arrayToObject($array){
	$obj = new stdClass;
	foreach($array as $k => $v) {
		if(strlen($k)) {
			if(is_array($v)) {
				$obj->{$k} = arrayToObject($v); //RECURSION
			} else {
				$obj->{$k} = $v;
			}
		}
	}
	return $obj;
}



function time_ispast($t){
	return ((time() - strtotime(substr($t,0,19))) > 0);
}

function time_format($t,$date_only=false){
	$this_year = ( date("Y")==date("Y",strtotime(substr($t,0,19))) );
	if($date_only){
		return date(( $this_year ? "M j" : "M j, Y" ),strtotime(substr($t,0,19)));
	} else {
		return date(( $this_year ? "M j, g:i a" : "M j, Y, g:i a" ),strtotime(substr($t,0,19)));
	}
	
}

function time_diff($t){
	$time = time() - strtotime(substr($t,0,19)); // to get the time since that moment
	$is_future = ( $time<0 );
	$time = abs($time);
	$tokens = array (
			31536000 => 'year',
			2592000 => 'month',
			604800 => 'week',
			86400 => 'day',
			3600 => 'hr',
			60 => 'min',
			1 => 'sec'
	);
	
	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	}
}




function url_name($text){
	//Cleans text and
	return substr(str_replace(' ','',preg_replace("/[^a-zA-Z0-9]+/", "", $text)),0,30);
}

function one_two_explode($one,$two,$content){
	if(substr_count($content, $one)<1){
		return NULL;
	}
	$temp = explode($one,$content,2);
	$temp = explode($two,$temp[1],2);
	return trim($temp[0]);
}


function quick_message($fb_user_id,$message){
	$CI =& get_instance();
	
	//Detect what type of message is this?
	if(substr($message,0,8)=='/attach '){
		//Some sort of attachment:
		$attachment_type = trim(one_two_explode('/attach ',':',$message));
		
		if(in_array($attachment_type,array('image','audio','video','file'))){
			$temp = explode($attachment_type.':',$message,2);
			$attachment_url = $temp[1];
			$CI->Facebook_model->send_message(array(
					'recipient' => array(
							'id' => $fb_user_id
					),
					'message' => array(
							'attachment' => array(
									'type' => $attachment_type,
									'payload' => array(
											'url' => $attachment_url,
									),
							),
					),
					'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
			));
			return 1;
		}
		
		//Still here? oops:
		return 0;
		
	} else {
		
		//Assumption is that this is a regular text message:
		$CI->Facebook_model->send_message(array(
				'recipient' => array(
						'id' => $fb_user_id
				),
				'message' => array(
						'text' => $message,
				),
				'notification_type' => 'REGULAR' //Can be REGULAR, SILENT_PUSH or NO_PUSH
		));
		return 1;
	}
}


function fetchMax($input_array,$searchKey){
	//Find the biggest $searchKey in $input_array:
	$max_ui_rank = 0;
	foreach($input_array as $child){
		if($child[$searchKey]>$max_ui_rank){
			$max_ui_rank = $child[$searchKey];
		}
	}
	return $max_ui_rank;
}






function html_new_run(){
	//Start generating the add new Run button:
	$return_string = '';
	$return_string .= '<div class="list-group-item">';
	$return_string .= '<h4 class="list-group-item-heading">';
	
	$return_string .= '<a href="/" class="expA"><span class="boldbadge badge">New</span></a>';
	
	
	$return_string .= '</h4>';
	$return_string .= '</div>';
	return $return_string;
}


function html_run($run){
	
	$CI =& get_instance();
	$user_data = $CI->session->userdata('user');
	

	//Start the display:
	$return_string = '';
	$return_string .= '<div class="list-group-item">';
	
	$return_string .= '<h4 class="list-group-item-heading">';
	$return_string .= '<a href="/"><span class="boldbadge badge">'.'Hiii'.'</span></a>';
	$return_string .= '<a href="alert(\'Hiii\');">'.
							'ICON'.'<span class="anchor">'. 'TITLE 1' . '<span>'.'ANCHOR'.'</span>'.'</span>'.
	
	( 1 ? ' ICON2' : '').
	
	'<span class="updateStatus"></span>'.
	
	'</a>'.
	'</h4>';
	
	
	$return_string .= '<div class="link-details">';
	$return_string .= '<p class="list-group-item-text">'.'VALUE'.'</p>';
	$return_string .= '<div class="list-group-item-text hover node_stats"><div>';
	
	//Collector:
	$return_string .= '<span><a href="/"><img src="https://www.gravatar.com/avatar/'.md5('ssasif').'?d=identicon" class="mini-image" /></a></span>';
	
	//COPY LANDING PAGE:
	$return_string .= ' <span title="Click to Copy URL to share Plugin on Messenger." data-toggle="tooltip" class="hastt clickcopy" data-clipboard-text="httpurlhere"><img src="/img/icons/messenger.png" class="action_icon" /><b>112233</b></span>';
	
	//Date
	$return_string .= '<span title="Added TIME UTC" data-toggle="tooltip" class="hastt"><span class="glyphicon glyphicon-time" aria-hidden="true" style="margin-right:2px;"></span>TIME</span>';
	
	/*
	//Update ID
	$return_string .= '<span title="Unique Update ID assigned per each edit." data-toggle="tooltip" class="hastt">#'.$node[$key]['id'].'</span>';
	
	if(auth_admin(1)){
		$return_string .= '<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></button>';
		$return_string .= '<ul class="dropdown-menu">';
		$return_string .= '<li><a href="javascript:edit_link('.$key.','.$node[$key]['id'].')" class="edit_link"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Edit</a></li>';
		
		//Make sure this is not a grandpa before showing the delete button:
		$grandparents = $CI->config->item('grand_parents');
		if(!($key==0 && array_key_exists($node[$key]['node_id'],$grandparents))){
			$return_string .= '<li><a href="javascript:delete_link('.$key.','.$node[$key]['id'].');"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> Remove</a></li>';
		}
		
		//Add search shortcuts:
		$return_string .= '<li><a href="https://www.google.com/search?q='.urlencode($node[$key]['value']).'" target="_blank"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Google</a></li>';
		$return_string .= '<li><a href="https://www.youtube.com/results?search_query='.urlencode($node[$key]['value']).'" target="_blank"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> YouTube</a></li>';
		
		//Display inversing if NOT direct
		if(!$is_direct){
			//TODO $return_string .= '<li><a href="javascript:inverse_link('.$key.','.$node[$key]['id'].')"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Flip Direction</a></li>';
		}
		if($node[$key]['update_id']>0){
			//This gem has previous revisions:
			//TODO $return_string .= '<li><a href="javascript:browse_revisions('.$key.','.$node[$key]['id'].')"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Revisions</a></li>';
		}
		
		$return_string .= '</ul></div>';
		
	} else {
		$return_string .= ''; //<span title="Request admin access to start collecting Gems." data-toggle="tooltip" class="hastt"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span> Limited Access</span>
	}
	*/
	$return_string .= '</div></div>';
	$return_string .= '</div>';
	$return_string .= '</div>';
	
	//Return:
	return $return_string;
}