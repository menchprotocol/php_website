<?php

function version_salt(){
	//This variable ensures that the CSS/JS files are being updated upon each launch
	//Also appended a timestamp To prevent static file cashing for local development
	//TODO Implemenet in sesseion when user logs in and logout if not matched!
	return 'v0.11';
}

function fetch_file_ext($url){
	//https://cdn.fbsbx.com/v/t59.3654-21/19359558_10158969505640587_4006997452564463616_n.aac/audioclip-1500335487327-1590.aac?oh=5344e3d423b14dee5efe93edd432d245&oe=596FEA95
	$url_parts = explode('?',$url,2);
	$url_parts = explode('/',$url_parts[0]);
	$file_parts = explode('.',end($url_parts));
	return end($file_parts);
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

function format_timestamp($t){	
	$time = time() - strtotime(substr($t,0,19)); // to get the time since that moment
	$time = ($time<1)? 1 : $time;
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
