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

function echo_price($r_usd_price){
    return ($r_usd_price>0?'$'.number_format($r_usd_price,0).' <span>USD</span>':'FREE');
}
function echo_hours($int_time){
    return '~'.( $int_time>0 && $int_time<1 ? round($int_time*60).' Minutes' : $int_time.' Hour'.($int_time>1?'s':'') );
}

function echo_video($video_url){
    //Support youtube and direct video URLs
    if(substr_count($video_url,'youtube.com/watch?v=')==1){
        //This is youtube:
        return '<div class="yt-container"><iframe src="//www.youtube.com/embed/'.one_two_explode('youtube.com/watch?v=','&',$video_url).'" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';
    } else {
        //This is a direct video URL:
        return '<video width="100%" controls><source src="'.$video_url.'" type="video/mp4">Your browser does not support the video tag.</video>';
    }
}

function echo_title($title_string){
    $peaces = explode(' ',$title_string);
    
    $CI =& get_instance();
    $title_key_icons = $CI->config->item('title_replacements');
    
    $return_title = '';
    $return_title .= '<span class="title-main">';
    foreach($peaces as $count=>$p){
        //Look for trigger keywords that would end the main title and start a new sub-section:
        if($count==0 && array_key_exists(strtolower($p),$title_key_icons['prepend'])){
            
            $return_title .= $title_key_icons['prepend'][strtolower($p)].' ';
            
        } elseif($count>0 && array_key_exists(strtolower($p),$title_key_icons['append'])){
            
            //End previous section:
            $return_title .= '</span>';
            //Start new section:
            $return_title .= '<span class="title-sub">'.$title_key_icons['append'][strtolower($p)].' ';
            
        } else {
            //Not trigger word, Append:
            $return_title .= $p.' ';
        }
    }
    //Close last span:
    $return_title .= '</span>';
    //Return:
    return $return_title;
}

function echo_message($i){
	//Fetch current Challenge:
	echo '<div class="list-group-item is_sortable" id="ul-nav-'.$i['i_id'].'" iid="'.$i['i_id'].'">';
		echo '<div>';
			echo '<span class="showdown">'.$i['i_message'].'</span>';
			echo '<textarea class="edit-on">'.$i['i_message'].'</textarea>';
			echo '<div class="original">'.$i['i_message'].'</div>';
			echo '<ul class="msg-nav">';
			    echo '<li><i class="fa fa-sort"></i></li>';
			    echo '<li class="edit-off"><a href="javascript:msg_start_edit('.$i['i_id'].');"><i class="fa fa-pencil"></i> Edit</a></li>';
			    //echo '<li class="edit-off"><i class="fa fa-clock-o"></i> 4s Ago</li>';
			    echo '<li class="edit-on"><a href="javascript:msg_save_edit('.$i['i_id'].');"><i class="fa fa-check"></i> Save</a></li>';
			    echo '<li class="edit-on"><a href="javascript:msg_cancel_edit('.$i['i_id'].');"><i class="fa fa-times"></i></a></li>';
			    echo '<li class="edit-updates"></li>';
			    echo '<li class="pull-right"><a href="javascript:media_delete('.$i['i_id'].');"><i class="fa fa-trash"></i></a></li>';
			    echo '<li class="edit-on pull-right"><a href="/console/help/showdown_markup" target="_blank"><i class="fa fa-info-circle"></i> Syntax</a></li>';
			    echo '</ul>';
		echo '</div>';
	echo '</div>';
}

function echo_time($c_time_estimate){
    if($c_time_estimate>0){
        $ui = '<span class="title-sub" data-toggle="tooltip" title="Estimated Time Investment"><i class="fa fa-clock-o" aria-hidden="true"></i>';
        if($c_time_estimate<1){
            //Minutes:
            $ui .= round($c_time_estimate*60).'m';
        } else {
            //Hours:
            $ui .= round($c_time_estimate,1).'h';
        }
        $ui .= '</span>';
        return $ui;
    }
    //No time:
    return false;
}

function echo_cr($b_id,$intent,$direction,$level=0){
    $CI =& get_instance();
    $level_names = $CI->config->item('level_names');
    
    
	if($direction=='outbound'){
	    
	    $ui = '<a id="cr_'.$intent['cr_id'].'" data-link-id="'.$intent['cr_id'].'" href="/console/'.$b_id.'/curriculum/'.$intent['c_id'].'" class="list-group-item is_sortable">';
	        //Right content
    	    $ui .= '<span class="pull-right">';
    	        $ui .= '<i class="fa fa-chain-broken" onclick="intent_unlink('.$intent['cr_id'].',\''.str_replace('\'','',str_replace('"','',$intent['c_objective'])).'\');" data-toggle="tooltip" title="Unlink this item. You can re-add it by searching it via the Add section below." data-placement="left"></i> ';
    	        /*
        	    $ui .= '<span class="label label-primary">';
        	       $ui .= '<span class="dir-sign">'.$direction.'</span> ';
        	       $ui .= '<i class="fa fa-chevron-right" aria-hidden="true"></i>';
        	    $ui .= '</span>';
        	    */
    	    $ui .= '</span> ';
    	    
    	    //Left content
    	    $ui .= '<i class="fa fa-sort" aria-hidden="true" style="padding-right:3px;"></i>';
    	    $ui .= ( $level>=2 ? '<span class="inline-level">'.$level_names[$level].' #'.$intent['cr_outbound_rank'].'</span>' : '' );
    	    $ui .= echo_title($intent['c_objective']).'&nbsp;';
    	    
    	    //Other settings:
    	    if(strlen($intent['c_todo_overview'])>0){
    	        $ui .= '<i class="fa fa-search title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Overview"></i>';
    	    }
    	    if(strlen($intent['c_prerequisites'])>0){
    	        $ui .= '<i class="fa fa-exclamation-circle title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Requirements"></i>';
    	    }
    	    if(strlen($intent['c_todo_bible'])>0){
    	        $ui .= '<i class="fa fa-wrench title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Action Plan"></i>';
    	    }
    	    
    	    if($level==2 && isset($intent['c__estimated_hours'])){
    	        $ui .= echo_time($intent['c__estimated_hours']);
    	    } elseif($level==3 && isset($intent['c_time_estimate'])){
    	        $ui .= echo_time($intent['c_time_estimate']);
    	    }
    	    
    	    if($level==2 && isset($intent['c__child_intents']) && count($intent['c__child_intents'])>0){
    	        //This sprint has tasks:
    	        $ui .= '<span class="title-sub" data-toggle="tooltip" title="The number of tasks for this sprint"><i class="fa fa-check-square-o" aria-hidden="true"></i>'.count($intent['c__child_intents']).'</span>';
    	    }
    	    $ui .= ' <span class="srt-'.$direction.'"></span>'; //For the status of sorting
    	    
	    $ui .= '</a>';
	    return $ui;
	    
	} else {
	    //Not really being used for now...
	    return '<a id="cr_'.$intent['cr_id'].'" data-link-id="'.$intent['cr_id'].'" href="/console/'.$b_id.'/curriculum/'.$intent['c_id'].'" class="list-group-item"><span class="pull-left" style="margin-right:5px;"><span class="label label-default"><i class="fa fa-chevron-left" aria-hidden="true"></i></span></span><span class="pull-right"><i class="fa fa-chain-broken" onclick="intent_unlink('.$intent['cr_id'].',\''.str_replace('\'','',str_replace('"','',$intent['c_objective'])).'\');" data-toggle="tooltip" title="Unlink this reference." data-placement="left"></i></span> '.echo_title($intent['c_objective']).echo_time($intent['c_time_estimate']).'</a>';
	}
}

function echo_users($users){
	foreach($users as $i=>$user){
		if($i>0){
			echo ', ';
		}
		echo '<a href="/user/'.$user['u_url_key'].'">@'.$user['u_url_key'].'</a>';
	}
}

function is_valid_intent($c_id){
    $CI =& get_instance();
    $intents = $CI->Db_model->c_fetch(array(
        'c.c_id' => intval($c_id),
        'c.c_status >=' => 0, //Drafting or higher
    ));
    return (count($intents)==1);
}


function echo_status_dropdown($object,$input_name,$current_status_id){
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    ?>
    <input type="hidden" id="<?= $input_name ?>" value="<?= $current_status_id ?>" /> 
    <div class="col-md-3 dropdown">
    	<a href="#" class="btn btn-simple dropdown-toggle" id="ui_<?= $input_name ?>" data-toggle="dropdown">
        	<?= status_bible($object,$current_status_id) ?>
        	<b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
    		<?php 
    		$statuses = status_bible($object);
    		$count = 0;
    		foreach($statuses as $intval=>$status){
    		    if($udata['u_status']<$status['u_min_status']){
    		        //Do not enable this user to modify to this status:
    		        continue;
    		    }
    		    $count++;
    		    echo '<li><a href="javascript:update_dropdown(\''.$input_name.'\','.$intval.','.$count.');">'.status_bible($object,$intval).'</a></li>';
    		    echo '<li style="display:none;" id="'.$input_name.'_'.$count.'">'.status_bible($object,$intval).'</li>'; //For UI replacement
    		}
    		?>
    	</ul>
    </div>
    <?php 
}


function status_bible($object=null,$status=null,$micro_status=false,$data_placement='bottom'){
	
    //IF you make any changes, make sure to also reflect in the view/console/guides/status_bible.php as well
	$status_index = array(
	    'b' => array(
	        -1 => array(
	            's_name'  => 'Deleted',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Bootcamp removed.',
	            'u_min_status'  => 1,
	        ),
	        0 => array(
	            's_name'  => 'On Hold',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Bootcamp not listed in marketplace until published live',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Submit For Live',
	            's_color' => '#8dd08f', //light green
	            's_desc'  => 'Bootcamp submit to be reviewed by Mench team to be listed on marketplace.',
	            'u_min_status'  => 1,
	        ),
	        2 => array(
	            's_name'  => 'Live',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Bootcamp is listed on marketplace.',
	            'u_min_status'  => 3, //Can only be done by admin
	        ),
	    ),
	    'c' => array(
	        -1 => array(
	            's_name'  => 'Deleted',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Intent removed.',
	            'u_min_status'  => 1,
	        ),
	        0 => array(
	            's_name'  => 'On Hold',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Intent not accessible by community until published live',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Live',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Intent is active and accessible by community.',
	            'u_min_status'  => 1,
	        ),
	    ),
	    'r' => array(
	        -1 => array(
	            's_name'  => 'Deleted',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Cohort removed by bootcamp leader.',
	            'u_min_status'  => 1,
	        ),
	        0 => array(
	            's_name'  => 'On Hold',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Cohort not displayed on landing page until published live',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Live',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Cohort is open for enrollment.',
	            'u_min_status'  => 1,
	        ),
	    ),
	    'i' => array(
	        -1 => array(
	            's_name'  => 'Deleted',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Reference removed by bootcamp leader.',
	            'u_min_status'  => 1,
	        ),
	        0 => array(
	            's_name'  => 'On Hold',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Reference not visible to community until published live',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Live',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Reference ready for distribution during weekly sprint.',
	            'u_min_status'  => 1,
	        ),
	    ),
	    
	    'cr' => array(
	        -1 => array(
	            's_name'  => 'Deleted',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Intent link removed.',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Live',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Intent link is active.',
	            'u_min_status'  => 1,
	        ),
	    ),
	    
	    //User related statuses:
	    
	    'ba' => array(
	        -1 => array(
	            's_name'  => 'Revoked',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Bootcamp access revoked.',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Assistant',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Can modify curriculum, view cohorts & answer student inquiries. Cannot modify bootcamp or cohorts.',
	            'u_min_status'  => 1,
	        ),
	        2 => array(
	            's_name'  => 'Mentor',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Can modify bootcamp, cohorts & curriculum. NOT responsible for bootcamp outcome & performance.',
	            'u_min_status'  => 1,
	        ),
	        3 => array(
	            's_name'  => 'Leader',
	            's_color' => '#e91e63', //Rose
	            's_desc'  => 'The bootcamp CEO who is responsible for outcome & performance based on student completion rates.',
	            'u_min_status'  => 1,
	        ),
	    ),
	    
	    'u' => array(
	        -1 => array(
	            's_name'  => 'Deleted',
	            's_color' => '#f44336', //red
	            's_desc'  => 'User account deleted and no longer active.',
	            'u_min_status'  => 3, //Only admins can delete user accounts, or the user for their own account
	        ),
	        0 => array(
	            's_name'  => 'Pending',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'User added by the community but has not yet claimed their account.',
	            'u_min_status'  => 999, //System only
	        ),
	        1 => array(
	            's_name'  => 'Active',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'User active.',
	            'u_min_status'  => 3, //Only admins can downgrade users from a leader status
	        ),
	        2 => array(
	            's_name'  => 'Leader',
	            's_color' => '#e91e63', //Rose
	            's_desc'  => 'User onboarded as bootcamp leader and can create/manage their own bootcamps.',
	            'u_min_status'  => 3, //Only admins can approve leaders
	        ),
	        3 => array(
	            's_name'  => 'Super Admin',
	            's_color' => '#e91e63', //Rose
	            's_desc'  => 'User part of Mench team who facilitates bootcamp operations.',
	            'u_min_status'  => 3, //Only admins can create other admins
	        ),
	    ),
	    
	    'ru' => array(
	        
	        //Withrew after course has started:
	        -4 => array(
	            's_name'  => 'Dispelled by Admin',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Student was dispelled due to misconduct. Refund at the discretion of bootcamp leader.',
	            'u_min_status'  => 1,
	        ),
	        -3 => array(
	            's_name'  => 'Post Grace Period Withdrawal',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Student withdrew after free withdrawal deadline. Refund at the discretion of bootcamp leader.',
	            'u_min_status'  => 999, //Only done by Student themselves
	        ),
	        
	        //Withrew prior to course has started:
	        -2 => array(
	            's_name'  => 'Graceful Withdrawal',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Student withdrew prior to free withdrawal deadline. Full refund will be given.',
	            'u_min_status'  => 999, //Only done by Student themselves
	        ),
	        -1 => array(
	            's_name'  => 'Application Rejected',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Student application rejected by bootcamp leader before start date.',
	            'u_min_status'  => 1,
	        ),
	        
	        //Applied
	        /*
	        0 => array(
	            's_name'  => 'Applied - Pending Full Payment',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Student has applied but has not paid in full yet, pending bootcamp leader approval before paying in full.',
	            'u_min_status'  => 999, //System insertion only
	        ),
	        */
	        1 => array(
	            's_name'  => 'Application Pending',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Student has applied, paid in full and is pending application review & approval.',
	            'u_min_status'  => 999, //System insertion only
	        ),
	        
	        //Enrolled
	        /*
	        2 => array(
	            's_name'  => 'Application Approved - Pending Payment',
	            's_color' => '#8dd08f', //light green
	            's_desc'  => 'Admins have full access to all bootcamp features.',
	            'u_min_status'  => 1,
	        ),
	        */
	        3 => array(
	            's_name'  => 'Enrolled',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Student application approved and full payment collected, making them ready to participate in bootcamp.',
	            'u_min_status'  => 1,
	        ),
	        
	        //Completion
	        4 => array(
	            's_name'  => 'Completed',
	            's_color' => '#e91e63', //Rose
	            's_desc'  => 'Student completed cohort and had all their assignments approved by bootcamp leader.',
	            'u_min_status'  => 1,
	        ),
	    ),
	);	
	
	
	//Return results:
	if(is_null($object)){
		//Everything
	    return $status_index;
	} elseif(is_null($status)){
		//Object Specific
	    return $status_index[$object];
	} else {
	    $status = intval($status);
		//We have two skins for displaying statuses:
	    if($micro_status){
	        return '<i class="fa fa-circle" style="color:'.$status_index[$object][$status]['s_color'].';" data-toggle="tooltip" data-placement="'.$data_placement.'" title="Status is '.$status_index[$object][$status]['s_name'].': '.$status_index[$object][$status]['s_desc'].'" aria-hidden="true"></i>';
		} else {
		    return '<span class="label label-default" style="background-color:'.$status_index[$object][$status]['s_color'].';" data-toggle="tooltip" data-placement="'.$data_placement.'" title="'.$status_index[$object][$status]['s_desc'].'">'.strtoupper($status_index[$object][$status]['s_name']).' <i class="fa fa-info-circle" aria-hidden="true"></i></span>';
		}
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
			$CI->session->set_flashdata('hm', '<div class="alert alert-danger" role="alert">Missing access or session expired. Login to continue.</div>');
			header( 'Location: /login?url='.urlencode($_SERVER['REQUEST_URI']) );
		}
	}
	
	return $udata;
}
function can_modify($object,$object_id){
	
	$CI =& get_instance();
	$udata = $CI->session->userdata('user');
	
	//TODO Validate:
	return true;
	
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

function url_exists($url){
    $file_headers = @get_headers($url);
    return !(!$file_headers || substr_count($file_headers[0],'404')>0);
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
    if(@include( 'application/libraries/aws/aws-autoloader.php' )){
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
            log_error('Unable to upload Facebook Message Attachment ['.$file_url.'] to Internal Storage.' , $json_data, 2);
            return false;
        }
    } else {
        //Probably local, ignore this!
        return false;
    }
}


function log_error($error_message, $json_data=array(), $e_medium_id=1){
	$CI =& get_instance();
	
	//First log error in DB:
	$res = $CI->Db_model->log_engagement(array(
			'e_message' => $error_message,
			'e_medium_id' => $e_medium_id, //Error logging
			'e_medium_action_id' => 0, //Reserved for errors
	));
	
	//Return error ID:
	return $res['e_id'];
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
    if(!$t){
        return 'NOW';
    }
	$this_year = ( date("Y")==date("Y",strtotime(substr($t,0,19))) );
	if($date_only){
		return date(( $this_year ? "M j" : "M j, Y" ),strtotime(substr($t,0,19)));
	} else {
		return date(( $this_year ? "M j, g:i a" : "M j, Y, g:i a" ),strtotime(substr($t,0,19)));
	}
	
}

function time_diff($t,$second_tiome=null){
    if(!$second_tiome){
        $second_tiome = time(); //Now
    } else {
        $second_tiome = strtotime(substr($second_tiome,0,19));
    }
    $time = $second_tiome - strtotime(substr($t,0,19)); // to get the time since that moment
	$is_future = ( $time<0 );
	$time = abs($time);
	$tokens = array (
			31536000 => 'Year',
			2592000 => 'Month',
			604800 => 'Week',
			86400 => 'Day',
			3600 => 'Hr',
			60 => 'Min',
			1 => 'Sec'
	);
	
	foreach ($tokens as $unit => $text) {
		if ($time < $unit) continue;
		if($unit>=2592000 && fmod(($time / $unit),1)>=0.33 && fmod(($time / $unit),1)<=.67){
		    $numberOfUnits = number_format(($time / $unit),1);
		} else {
		    $numberOfUnits = number_format(($time / $unit),0);
		}
		
		
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	}
}




function url_name($text){
    //Cleans text and
    return substr(str_replace(' ','',preg_replace("/[^a-zA-Z0-9]+/", "", $text)),0,30);
}

function url_key($text){
    //Cleans text:
    $generated_key = str_replace(' ','',preg_replace("/[^a-zA-Z0\-]+/", "", str_replace(' ','-',strtolower($text))));

    //Check for duplicates:
    $CI =& get_instance();
    $bootcamps = $CI->Db_model->c_full_fetch(array(
        'b.b_url_key' => $generated_key,
    ));
    
    if(count($bootcamps)>0){
        //Ooops, we have a duplicate:
        $generated_key = $generated_key.'-'.rand(0,99999);
    }
    
    return $generated_key;
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