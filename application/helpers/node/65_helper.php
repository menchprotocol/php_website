<?php



function add_youtube_video($input,$start,$end,$hashtag_id,$notes=null){
	
	$CI =& get_instance();
	
	if(strlen($input)==11){
		//Its an ID:
		$youtube_id = $input;
	} else {
		//Input is a URL:
		$youtube_url= $input;
		
		//Validate URL and extract ID:
		if(preg_match("~\byoutube\.com\/watch\?v\b~",$youtube_url)){
			$youtube_id = one_two_explode('watch?v=','?',$youtube_url);
		} elseif(preg_match("~\byoutu\.be\/\b~",$youtube_url)){
			$youtube_id = one_two_explode('youtu.be/','?',$youtube_url);
		} else {
			return array(
					'status' => 0,
					'message' => '"'.$youtube_url.'" not recognized as a valid YouTube URL',
			);
		}
		//Anything wrong with the ID?
		if(strlen($youtube_id)!=11){
			return array(
					'status' => 0,
					'message' => 'YouTube Video ID "'.$youtube_id.'" is not 11 characters, which is what a standard YouTube ID is.',
			);
		}
	}
	
	//Check to see if we have this ID already, and where:
	$is_full_video = (!$start && !$end);
	$current = $CI->Us_model->search_node($youtube_id, 237 , array('append_node_top'=>1));
	//print_r($current);
	$main_node = 0; //This is the node that [node_id][parent_id]=65 which means its part of &YouTubeVideos
	foreach($current as $c){
		if($c['node']['parent_id']==65){
			$main_node = $c['node']['node_id'];
			break; //There should always be one in the main
		}
	}
	
	
	//Lets run some more checks:
	if($is_full_video && $main_node){
		return array(
			'status' => 0,
			'message' => 'YouTube Video ID "'.$youtube_id.'" already exists as full video here: /'.$main_node,
		);
	} elseif($is_full_video && !$hashtag_id){
		return array(
			'status' => 0,
			'message' => 'Parent #Goal required for full YouTube videos to indicate what its topic is.',
		);
	} else
	
	//Lets start creating the video:
	if($is_full_video){
		
		if($main_node){
			return array(
					'status' => 0,
					'message' => 'YouTube Video ID "'.$youtube_id.'" already exists as full video here: /'.$main_node,
			);
		} elseif(!$hashtag_id){
			return array(
					'status' => 0,
					'message' => 'Parent #Goal required for full YouTube videos to indicate what its topic is.',
			);
		}
		
		$html = file_get_contents('https://www.youtube.com/watch?v='.$youtube_id);
		$minutes = intval(one_two_explode('itemprop="duration" content="PT','M',$html));
		$seconds = intval(one_two_explode('itemprop="duration" content="PT'.$minutes.'M','S',$html));
		
		
		//TODO Consider looking for embed enabled videos only and reject those with embed restrictions
		$batch_input = array(
				array(
						'parent_id' => 65, //YouTube Video
						'status' => (auth_admin(1) ? 1 : 0),
						'value' => one_two_explode('name="title" content="','"',$html),
						'action_type' => 1, //For adding
				),
				array(
						'parent_id' => 237, //YouTube Video ID
						'value' => $youtube_id,
						'action_type' => 4, //For linking
				),
				array(
						'parent_id' => 28, //Publish date
						'value' => one_two_explode('itemprop="datePublished" content="','"',$html),
						'action_type' => 4, //For linking
				),
				array(
						'parent_id' => 192, //Video views
						'value' => one_two_explode('itemprop="interactionCount" content="','"',$html),
						'action_type' => 4, //For linking
				),
				array(
						'parent_id' => 161, //Video duration in minutes
						'value' => $minutes + ( $seconds>=30 ? 1 : 0 ), //Round-off
						'action_type' => 4, //For linking
				),
				array(
						'parent_id' => 310, //YouTube Publisher Channel ID
						'value' => one_two_explode('itemprop="channelId" content="','"',$html),
						'action_type' => 4, //For linking
				),
				array(
						'parent_id' => $hashtag_id, //The assigned parent ID
						'value' => 'Video content is related to this goal',
						'action_type' => 4, //For linking
				),
		);
		
	} else {
		
		// video Slice
		//Do some checks:
		if(!$main_node){
			return array(
					'status' => 0,
					'message' => 'YouTube Video ID "'.$youtube_id.'" not found as a full video under /65, so you cannot add this partial video until we have the full video.',
			);
		}
		
		
		$next_node = next_node_id();
		$batch_input = array();
		
		if(intval($hashtag_id)>0){
			//User has submitted a hashtag
			//Fetch the sub-#hashtag this is referencing:
			$fetch_node = $CI->Us_model->fetch_node($hashtag_id);
		}
		
		
		//Main linking:
		array_push($batch_input , array(
				'node_id' => $next_node,
				'status' => 1, //TODO Update
				'parent_id' => $main_node, //Parent Full YouTube Video
				'value' => ( intval($hashtag_id)>0 ? 'Example of '.$fetch_node[0]['sign'].$fetch_node[0]['value'] : 'Video Slice / Seconds '.$start.' - '.$end ),
				'action_type' => 1, //For adding
		));
		array_push($batch_input , array(
				'node_id' => $next_node,
				'status' => 2, //TODO Update
				'parent_id' => 237, //YouTube Video ID
				'value' => $youtube_id,
				'action_type' => 4, //For linking
		));
		array_push($batch_input , array(
				'node_id' => $next_node,
				'status' => 2, //TODO Update
				'parent_id' => 73, //Media start time
				'value' => $start,
				'action_type' => 4, //For linking
		));
		array_push($batch_input , array(
				'node_id' => $next_node,
				'status' => 2, //TODO Update
				'parent_id' => 74, //Media end time
				'value' => $end,
				'action_type' => 4, //For linking
		));
		
		if(intval($hashtag_id)>0){
			//Insert this as the child:
			array_push($batch_input , array(
					'node_id' => $hashtag_id,
					'status' => 2, //TODO Update
					'grandpa_id' => 3, //Always hashtags
					'parent_id' => $next_node, //This slice references hashtag
					'value' => $notes,
					'action_type' => 4, //For linking
			));
		}
	}

	//Add batch:
	$batch_insert = $CI->Us_model->insert_batch_links($batch_input);
	
	//Return final results:
	return array(
			'status' => (count($batch_insert)>0),
			'message' => count($batch_insert).' links modified.',
			//'node' => $batch_insert,
	);
}