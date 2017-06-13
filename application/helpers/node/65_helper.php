<?php

function add_youtube_video($youtube_identifier,$IN_intent,$us_id=null){
	
	//Input validation:
	if(strlen($youtube_identifier)==11){
		//Its an ID:
		$youtube_id = $youtube_identifier;
	} else {
		//Input is a URL:
		$youtube_url= $youtube_identifier;
		
		//Validate URL and extract ID:
		if(preg_match("~\byoutube\.com\/watch\?v\b~",$youtube_url)){
			$youtube_id = one_two_explode('watch?v=','?',$youtube_url);
		} elseif(preg_match("~\byoutu\.be\/\b~",$youtube_url)){
			$youtube_id = one_two_explode('youtu.be/','?',$youtube_url);
		} else {
			return array(
				'status' => 0,
				'message' => $youtube_url.' not recognized as a valid YouTube URL',
			);
		}
		//Anything wrong with the ID?
		if(strlen($youtube_id)!=11){
			return array(
				'status' => 0,
				'message' => 'YouTube Video ID '.$youtube_id.' is not 11 characters, which is what a standard YouTube ID is.',
			);
		}
	}
	
	//Check to see if we have this ID already, and where:
	$CI =& get_instance();
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
	if($main_node){
		return array(
			'status' => 0,
			'message' => 'YouTube Video ID '.$youtube_id.' already exists on ||'.$main_node,
		);
	} elseif(!$IN_intent){
		return array(
				'status' => 0,
				'message' => '#Intent required for new YouTube videos.',
		);
	}
		
	//Lets start creating the video:
	$html = curl_html('https://www.youtube.com/watch?v='.$youtube_id);
	$minutes = intval(one_two_explode('itemprop="duration" content="PT','M',$html));
	$seconds = intval(one_two_explode('itemprop="duration" content="PT'.$minutes.'M','S',$html));
	
	//TODO Consider looking for embed enabled videos only and reject those with embed restrictions
	// Sample embed disabled video: https://www.youtube.com/watch?v=ORHjkwwpLyU
	
	$next_node = $CI->Us_model->next_node_id();
	
	//Add batch:
	$batch_insert = $CI->Us_model->insert_batch_links(array(
			array(
					'us_id' => $us_id,
					'node_id' => $next_node,
					'parent_id' => 65, //YouTube Video
					'value' => one_two_explode('name="title" content="','"',$html),
					'action_type' => 1, //For adding
					'ui_parent_rank' => 1, //TOP
			),
			array(
					'us_id' => $us_id,
					'node_id' => $next_node,
					'parent_id' => 237, //YouTube Video ID
					'value' => $youtube_id,
					'action_type' => 4, //For linking
			),
			array(
					'us_id' => $us_id,
					'node_id' => $next_node,
					'parent_id' => 28, //Publish date
					'value' => one_two_explode('itemprop="datePublished" content="','"',$html),
					'action_type' => 4, //For linking
			),
			array(
					'us_id' => $us_id,
					'node_id' => $next_node,
					'parent_id' => 192, //Video views
					'value' => one_two_explode('itemprop="interactionCount" content="','"',$html),
					'action_type' => 4, //For linking
			),
			array(
					'us_id' => $us_id,
					'node_id' => $next_node,
					'parent_id' => 161, //Video duration in minutes
					'value' => $minutes + ( $seconds>=30 ? 1 : 0 ), //Round-off
					'action_type' => 4, //For linking
			),
			array(
					'us_id' => $us_id,
					'node_id' => $next_node,
					'parent_id' => $IN_intent, //The core intent of the video
					'value' => '',
					'action_type' => 4, //For linking
			),
	));
	
	//Return final results:
	return array(
		'status' => (count($batch_insert)>0),
		'message' => count($batch_insert).' gems added.',
		'link' => $batch_insert,
	);
}