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

function calculate_duration($bootcamp,$action_plan_item=null){
    return ( ( !is_null($action_plan_item) ? $action_plan_item : count($bootcamp['c__child_intents']) ) * ( $bootcamp['b_sprint_unit']=='week' ? 7 : 1 ) );
}

function calculate_refund($duration_days,$refund_type,$cancellation_policy){
    $CI =& get_instance();
    $refund_policies = $CI->config->item('refund_policies');
    return ceil( $duration_days * $refund_policies[$cancellation_policy][$refund_type] );
}




function parse_signed_request($signed_request) {
    list($encoded_sig, $payload) = explode('.', $signed_request, 2);
    
    $secret = "f2857b518c69b3a51f106d6372687094"; // Use your app secret here
    
    // Decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);
    
    // Confirm the signature
    $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
    if ($sig !== $expected_sig) {
        //error_log('Bad Signed JSON signature!');
        return null;
    }
    
    return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}


function itip($c_id){
    echo '<span id="hb_'.$c_id.'" class="help_button belowh2-btn" intent-id="'.$c_id.'"></span>';
    echo '<div class="help_body belowh2-bdy maxout" id="content_'.$c_id.'"></div>';
}



function extract_level($b,$c_id){
    
    $CI =& get_instance();
    $core_objects = $CI->config->item('core_objects');
    //This is what we shall return:
    $view_data = array(
        'pid' => $c_id, //To be deprecated at some point...
        'c_id' => $c_id,
        'bootcamp' => $b,
        'i_messages' => $CI->Db_model->i_fetch(array(
            'i_status >=' => 0, //Private notes never have i_c_id set, but lets filter anyways:
            'i_status <' => 4,
            'i_c_id' => $c_id, 
        )),
    );
    
    
    if($b['c_id']==$c_id){
        
        //Level 1 (The bootcamp itself)
        $view_data['level'] = 1;
        $view_data['sprint_index'] = 0;
        $view_data['intent'] = $b;
        $view_data['title'] = 'Action Plan | '.$b['c_objective'];
        $view_data['breadcrumb'] = array(
            array(
                'link' => null,
                'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'].' <span id="hb_592" class="help_button" intent-id="592"></span>',
            ),
        );
        $view_data['breadcrumb_p'] = $view_data['breadcrumb'];
        return $view_data;
        
    } else {
        
        foreach($b['c__child_intents'] as $sprint){
            
            if($sprint['c_id']==$c_id){
                //Found this as level 2:
                $view_data['level'] = 2;
                $view_data['sprint_index'] = $sprint['cr_outbound_rank'];
                $view_data['intent'] = $sprint;
                $view_data['title'] = 'Action Plan | '.ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'];
                $view_data['breadcrumb'] = array(
                    array(
                        'link' => '/console/'.$b['b_id'].'/actionplan',
                        'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
                    ),
                    array(
                        'link' => null,
                        'anchor' => $core_objects['level_1']['o_icon'].' '.ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'],
                    ),
                );
                $view_data['breadcrumb_p'] = array(
                    array(
                        'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_c_id'],
                        'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
                    ),
                    array(
                        'link' => null,
                        'anchor' => $core_objects['level_1']['o_icon'].' '.ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'],
                    ),
                );
                
                return $view_data;
                
            } else {
                
                //Perhaps a level 3?
                foreach($sprint['c__child_intents'] as $task){
                    if($task['c_id']==$c_id){
                        //This is level 3:
                        $view_data['level'] = 3;
                        $view_data['sprint_index'] = $sprint['cr_outbound_rank'];
                        $view_data['intent'] = $task;
                        $view_data['title'] = 'Action Plan | '.ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' Task #'.$task['cr_outbound_rank'].' '.$task['c_objective'];
                        $view_data['breadcrumb'] = array(
                            array(
                                'link' => '/console/'.$b['b_id'].'/actionplan',
                                'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
                            ),
                            array(
                                'link' => '/console/'.$b['b_id'].'/actionplan/'.$sprint['c_id'],
                                'anchor' => $core_objects['level_1']['o_icon'].' '.ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'],
                            ),
                            array(
                                'link' => null,
                                'anchor' => $core_objects['level_2']['o_icon'].' Task #'.$task['cr_outbound_rank'].' '.$task['c_objective'],
                            ),
                        );
                        $view_data['breadcrumb_p'] = array(
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_c_id'],
                                'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
                            ),
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$sprint['c_id'],
                                'anchor' => $core_objects['level_1']['o_icon'].' '.ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'],
                            ),
                            array(
                                'link' => null,
                                'anchor' => '<i class="fa fa-list-ul" aria-hidden="true"></i> Task #'.$task['cr_outbound_rank'].' '.$task['c_objective'],
                            ),
                        );
                        
                        return $view_data;
                    }
                }
            }
        }
        
        //Still here?!
        return false;
    }
}





function echo_price($r_usd_price){
    return ($r_usd_price>0?'$'.number_format($r_usd_price,0).' <span>USD</span>':'FREE');
}
function echo_hours($int_time){
    return ( $int_time>0 && $int_time<1 ? round($int_time*60).' Minutes' : $int_time.($int_time==1?' Hour':' Hours') );
}

function echo_video($video_url){
    //Support youtube and direct video URLs
    if(substr_count($video_url,'youtube.com/watch?v=')==1){
        //This is youtube:
        //We can also define start and end time by adding this: &start=4&end=9
        return '<div class="yt-container"><iframe src="//www.youtube.com/embed/'.one_two_explode('youtube.com/watch?v=','&',$video_url).'?theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';
    } else {
        //This is a direct video URL:
        return '<video width="100%" onclick="this.play()" controls><source src="'.$video_url.'" type="video/mp4">Your browser does not support the video tag.</video>';
    }
}



function echo_i($i,$first_name=null,$fb_format=false){
    
    //Must be one of these 5 types:
    if(!isset($i['i_media_type']) || !in_array($i['i_media_type'],array('text','video','audio','image','file'))){
        return false;
    }
    
    
    if(!$fb_format){
        //HTML format:
        $echo_ui = '';
        $echo_ui .= '<div class="i_content">';
    }
    
    //Proceed to Send Message:
    if($i['i_media_type']=='text' && strlen($i['i_message'])>0){
        
        //Does this message also have a link?
        if(strlen($i['i_url'])>0){
            
            $CI =& get_instance();
            $website = $CI->config->item('website');
            $url = $website['url'].'ref/'.$i['i_id'];
            if($first_name){
                //Tweak the name:
                $i['i_message'] = str_replace('{first_name}', $first_name, $i['i_message']);
            }
            
            if($fb_format){
                //Messenger format:
                $i['i_message'] = trim(str_replace($i['i_url'],$url,$i['i_message']));
            } else {
                //HTML format:
                $i['i_message'] = trim(str_replace($i['i_url'],'<a href="'.$url.'" target="_blank">'.rtrim(str_replace('http://','',str_replace('https://','',str_replace('www.','',$i['i_url']))),'/').'<i class="fa fa-external-link-square" style="font-size: 0.8em; text-decoration:none; padding-left:4px;" aria-hidden="true"></i></a>',$i['i_message']));
            }
        }
        
        //Now return the template:
        if($fb_format){
            //Messenger array:
            return array(
                $i['i_media_type'] => $i['i_message'],
                'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
            );
        } else {
            //HTML format:
            $echo_ui .= '<div class="msg">'.nl2br($i['i_message']).'</div>';
        }
        
    } elseif(strlen($i['i_url'])>0) {
        
        //Valid media file with URL:
        if($fb_format){
            
            //Messenger array:
            return array(
                'attachment' => array(
                    'type' => $i['i_media_type'],
                    'payload' => array(
                        'url' => $i['i_url'],
                        'is_reusable' => true, //This can likely be reused within the class
                    ),
                ),
                'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
            );
            
        } else {
            //HTML media format:
            $echo_ui .= '<div>'.format_e_message('/attach '.$i['i_media_type'].':'.$i['i_url']).'</div>';
        }
        
    } else {
        //Something was wrong:
        return false;
    }

    //This must be HTML if we're still here, return:
    $echo_ui .= '</div>';
    return $echo_ui;
}



function extract_urls($text){
    $parts = preg_split('/\s+/', $text);
    $urls = array();
    foreach($parts as $part){
        if(filter_var($part, FILTER_VALIDATE_URL)){
            array_push($urls,$part);
        }
    }
    return $urls;
}

function echo_uploader($i){
    return '<img src="'.$i['u_image_url'].'" data-toggle="tooltip" title="Last modified by '.$i['u_fname'].' '.$i['u_lname'].' about '.time_diff($i['i_timestamp']).' ago" data-placement="right" />';
}

function echo_message($i,$level=0){

    $echo_ui = '';
    $echo_ui .= '<div class="list-group-item is_sortable" id="ul-nav-'.$i['i_id'].'" iid="'.$i['i_id'].'">';
    $echo_ui .= '<input type="hidden" class="i_media_type" value="'.$i['i_media_type'].'" />';
    $echo_ui .= '<div style="overflow:visible !important;">';
	
	    //Type & Delivery Method:    
	    $echo_ui .= '<div class="'.($i['i_media_type']=='text'?'edit-off text_message':'').'" style="margin:5px 0 0 0;">';
	    $echo_ui .= echo_i($i);
    	$echo_ui .= '</div>';
    	
    	
    	if($i['i_media_type']=='text'){
    	    //Text editing:
    	    $echo_ui .= '<textarea name="i_message" class="edit-on msg msgin" placeholder="Write Message..." style="margin-top: 4px;">'.$i['i_message'].'</textarea>';
    	}
    	
        //Editing menu:
        $echo_ui .= '<ul class="msg-nav">';
		    //$echo_ui .= '<li class="edit-off"><i class="fa fa-clock-o"></i> 4s Ago</li>';
            $echo_ui .= '<li class="i_uploader">'.echo_uploader($i).'</li>';
            $echo_ui .= '<li data-toggle="tooltip" title="Drag Up/Down to Sort" data-placement="right"><i class="fa fa-sort" style="color:#2f2639;"></i></li>';
            $echo_ui .= '<li data-toggle="tooltip" style="margin-right: 10px; margin-left: 6px;" title="Delete Message" data-placement="right"><a href="javascript:message_delete('.$i['i_id'].');"><i class="fa fa-trash"></i></a></li>';
            $echo_ui .= '<li class="edit-off" data-toggle="tooltip" title="Modify status'.( $i['i_media_type']=='text' ? ' and/or text message' : '').'" data-placement="right"><a href="javascript:msg_start_edit('.$i['i_id'].');"><i class="fa fa-pencil-square-o"></i></a></li>';
            $echo_ui .= '<li class="edit-off the_status" style="margin-right: 0;">'.status_bible('i',$i['i_status'],1,'right').'</li>';
            
            //Right side reverse:
            $echo_ui .= '<li class="pull-right edit-on"><a class="btn btn-primary" href="javascript:message_save_updates('.$i['i_id'].');" style="text-decoration:none; font-weight:bold;">Save</a></li>';
            $echo_ui .= '<li class="pull-right edit-on"><a class="btn btn-default" href="javascript:msg_cancel_edit('.$i['i_id'].');"><i class="fa fa-times" style="color:#000"></i></a></li>';
            $echo_ui .= '<li class="pull-right edit-on">'.echo_status_dropdown('i','i_status_'.$i['i_id'],$i['i_status'],($level==1?array(-1,4):($level==3?array(-1,3,4):array(-1,4))),'dropup',$level).'</li>';
            $echo_ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors
		    $echo_ui .= '</ul>';
	    
    $echo_ui .= '</div>';
    $echo_ui .= '</div>';
    
    return $echo_ui;
}

function echo_time($c_time_estimate,$show_icon=1,$micro=false){
    if($c_time_estimate>0){
        $ui = '<span class="title-sub" style="text-transform:none !important;" data-toggle="tooltip" title="Estimated Task Completion Time">'.( $show_icon ? '<i class="fa fa-clock-o" aria-hidden="true"></i>' : '');
        if($c_time_estimate<1){
            //Minutes:
            $ui .= round($c_time_estimate*60).($micro?'m':' Minutes');
        } else {
            //Hours:
            $ui .= round($c_time_estimate,1).($micro?'h':' Hour'.(round($c_time_estimate,1)==1?'':'s'));
        }
        $ui .= '</span>';
        return $ui;
    }
    //No time:
    return false;
}

function echo_br($admin){
    //Removed for now: href="javascript:ba_open_modify('.$admin['ba_id'].')"
    $ui = '<li id="ba_'.$admin['ba_id'].'" data-link-id="'.$admin['ba_id'].'" class="list-group-item is_sortable">';
        //Right content
        $ui .= '<span class="pull-right">';
            //$ui .= '<span class="label label-primary" data-toggle="tooltip" data-placement="left" title="Click to modify/revoke access.">';
            //$ui .= '<i class="fa fa-cog" aria-hidden="true"></i>';
            //$ui .= '</span>';
            $ui .= status_bible('ba',$admin['ba_status']);
        
        $ui .= '</span> ';
        
        //Left content
        //$ui .= '<i class="fa fa-sort" aria-hidden="true" style="padding-right:3px;"></i> ';
        $ui .= $admin['u_fname'].' '.$admin['u_lname'].' &nbsp;';
        if($admin['ba_team_display']=='t'){
            $ui .= '<i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Instructor listed on the Landing Page"></i>';
        } else {
            $ui .= '<i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Instructor NOT listed on the Landing Page"></i>';
        }
        
        $ui .= ' <span class="srt-admins"></span>'; //For the status of sorting
    
    $ui .= '</li>';
    return $ui;
}


//This is used for My/actionplan display:
function echo_c($b,$c,$level,$us_data=null,$sprint_index=null){
    /* 
     * $b = Bootcamp object
     * $c = Intent object
     * $level Legend:
     *    1 = Action Plan / Top Level
     *    2 = Milestone (Day or Week)
     *    3 = Task
     * 
     * * */

    //Calculate deadlines if level 2 Milestones items to see which one to show!
    $unlocked_action_plan = false;
    $is_now = '';
    if($level==2){
        if($sprint_index>=2){
            //This the second milestone or more, make sure the previous milestone is done before unlocking this
            //We need to check if all child tasks are marked as complete:
            $aggregate_status = 1; //We assume it's all done, unless proven otherwise:
            $last_milestone = ( $sprint_index - 2 );
            //Make sure this last milestone is not a Break Milestone:
            while(isset($b['c__child_intents'][$last_milestone]['c_is_last']) && $b['c__child_intents'][$last_milestone]['c_is_last']=='t'){
                $last_milestone--;
            }
                
            
            if($last_milestone>=0){
                foreach($b['c__child_intents'][$last_milestone]['c__child_intents'] as $task){
                    if(!isset($us_data[$task['c_id']])){
                        //No submission for this, definitely not done!
                        $aggregate_status = -2; //A special meaning here, which is not found
                        break;
                    } elseif($us_data[$task['c_id']]['us_status']<$aggregate_status){
                        $aggregate_status = $us_data[$task['c_id']]['us_status'];
                    }
                }
            }   
        }
        //Do some time calculations for the point system:
        $open_date = strtotime(time_format($b['r_start_date'],2,(calculate_duration($b,($sprint_index-1)))))+(intval($b['r_start_time_mins'])*60);
        $next_open_date = strtotime(time_format($b['r_start_date'],2,(calculate_duration($b,($sprint_index)))))+(intval($b['r_start_time_mins'])*60);
        $is_current = (time() >= $open_date);
        $next_is_current = (time() >= $next_open_date);
        $unlocked_action_plan = ( $is_current && ( $sprint_index<2 || $aggregate_status>0 ) );
        $is_now = ( $is_current && !$next_is_current ? ' <span class="badge badge-current"><i class="fa fa-hand-o-left" aria-hidden="true"></i> HERE NOW</span>' : '' );
    }

    $show_a = true; //Most cases
    //Left content
    if($level==0){
        //Not possible for now as each student can take 1 bootcamp at a time.
        $ui = '<a href="/my/actionplan/'.$b['b_id'].'/'.$c['c_id'].'" class="list-group-item">';
        $ui .= '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> ';
    } elseif($level==3 || $unlocked_action_plan){
        $ui = '<a href="/my/actionplan/'.$b['b_id'].'/'.$c['c_id'].'" class="list-group-item">';
        $ui .= '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
        
        
        
        if($level==2){
            
            //We need to check if all child tasks are marked as complete:
            $aggregate_status = 1; //We assume it's all done, unless proven otherwise:
            foreach($c['c__child_intents'] as $task){
                if(!isset($us_data[$task['c_id']])){
                    //No submission for this, definitely not done!
                    $aggregate_status = -2; //A special meaning here, which is not found
                    break;
                } elseif($us_data[$task['c_id']]['us_status']<$aggregate_status){
                    $aggregate_status = $us_data[$task['c_id']]['us_status'];
                }
            }
            
            if($aggregate_status==-2){
                $ui .= '<i class="fa fa-square-o initial" aria-hidden="true"></i> ';
            } else {
                $ui .= status_bible('us',$aggregate_status,1).' ';
            }
            
        } elseif($level==3){
            //This is a task, it needs to have a direct submission:
            if(isset($us_data[$c['c_id']])){
                $ui .= status_bible('us',$us_data[$c['c_id']]['us_status'],1).' ';
            } else {
                $ui .= '<i class="fa fa-square-o initial" aria-hidden="true"></i> ';
            }
        }
        
        //if($c['cr_outbound_rank']<=1){
        //$ui .= '<i class="fa fa-check-circle initial" aria-hidden="true"></i> ';
        //}
        
    } else {
        $show_a = false; //Not here, its locked
        $ui = '<li class="list-group-item">';
        $ui .= '<i class="fa fa-lock initial" aria-hidden="true"></i> ';
    }
    
    if($level==2){
        //Show milestone abbrevation like "W1" or "D4"
        $ui .= '<span class="inline-level">'.strtoupper(substr($b['b_sprint_unit'],0,1)).$c['cr_outbound_rank'].'</span>';
    }
    
    $ui .= $c['c_objective'].' ';
    
    if($level==2 && $c['c_is_last']=='t'){
        $ui .= '<i class="fa fa-coffee" aria-hidden="true"></i> Break Milestone ';
    }
    
    
    $ui .= '<span class="sub-stats">';
        
    //Other settings:
    if($show_a && $level==2 && isset($c['c__estimated_hours'])){
            $ui .= echo_time($c['c__estimated_hours'],1);
    } elseif($level==3 && isset($c['c_time_estimate'])){
            $ui .= echo_time($c['c_time_estimate'],1);
    }
        
    if($show_a && $level==2 && isset($c['c__child_intents']) && count($c['c__child_intents'])>0){
        //This sprint has Assignments:
        $ui .= '<span class="title-sub"><i class="fa fa-list-ul" aria-hidden="true"></i>'.count($c['c__child_intents']).'</span>';
    }
    
    $ui .= $is_now;
    
    //TODO Need to somehow fetch classes in here...
    //$ui .= '<span class="title-sub"><i class="fa fa-calendar" aria-hidden="true"></i>'.time_format($admission['r_start_date'],5,calculate_duration($b,$c['cr_outbound_rank'])).'</span>';
    $ui .= '</span>';
    
    $ui .= ($show_a ? '</a>' : '</li>');
    return $ui;
}


function echo_cr($b_id,$intent,$direction,$level=0,$b_sprint_unit){
    
    $CI =& get_instance();
    $core_objects = $CI->config->item('core_objects');
    $clean_title = preg_replace("/[^A-Za-z0-9 ]/", "", $intent['c_objective']);
    $clean_title = (strlen($clean_title)>0 ? $clean_title : 'This Item');
    
	if($direction=='outbound'){
	    
	    $ui = '<a id="cr_'.$intent['cr_id'].'" data-link-id="'.$intent['cr_id'].'" href="/console/'.$b_id.'/actionplan/'.$intent['c_id'].'" class="list-group-item is_sortable">';
	        //Right content
    	    $ui .= '<span class="pull-right">';

    	    $ui .= '<i class="fa fa-sort" data-toggle="tooltip" title="Drag Up/Down to Sort" data-placement="left" aria-hidden="true"></i> &nbsp;';
    	    
    	    $ui .= '<i class="fa fa-trash" onclick="intent_unlink('.$intent['cr_id'].',\''.$clean_title.'\');" data-toggle="tooltip" title="Remove '.$core_objects['level_'.($level-1)]['o_name'].'" data-placement="left"></i> &nbsp;';
    	    
    	    $ui .= '<span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>';
    	    
    	    
    	    //$ui .= status_bible('c',$intent['c_status'],1,'left');
    	    //$ui .= '<i class="fa fa-chain-broken" onclick="intent_unlink('.$intent['cr_id'].',\''.str_replace('\'','',str_replace('"','',$intent['c_objective'])).'\');" data-toggle="tooltip" title="Unlink this item. You can re-add it by searching it via the Add section below." data-placement="left"></i> ';
/*
        	    $ui .= '<span class="label label-primary">';
        	       $ui .= '<span class="dir-sign">'.$direction.'</span> ';
        	       $ui .= '<i class="fa fa-chevron-right" aria-hidden="true"></i>';
        	    $ui .= '</span>';
        	    */
    	    $ui .= '</span> ';
    	    
    	    //Left content
    	    $ui .= ( $level>=2 ? '<span class="inline-level">'.( $level==2 ? $core_objects['level_'.($level-1)]['o_icon'].' '.ucwords($b_sprint_unit) : $core_objects['level_'.($level-1)]['o_icon'].' Task' ).' #'.$intent['cr_outbound_rank'].'</span>' : '' );
    	    $ui .= $intent['c_objective'].' ';
  
    	    
    	    //Meta data & stats:
    	    if($level==2 && $intent['c_is_last']=='t'){
    	        //This sprint has Assignments:
    	        $ui .= '<span class="title-sub" data-toggle="tooltip" title="This is a Break Milestone with nothing to Submit, but maybe some Messages to read, listen or watch."><i class="fa fa-coffee" aria-hidden="true"></i>Break Milestone</span>';
    	    }
    	    if($level==2 && isset($intent['c__child_intents']) && count($intent['c__child_intents'])>0){
    	        //This sprint has Assignments:
    	        $ui .= '<span class="title-sub" data-toggle="tooltip" title="This Milestone Has '.count($intent['c__child_intents']).' Task'.(count($intent['c__child_intents'])==1?'':'s').'"><i class="fa fa-check-square" aria-hidden="true"></i>'.count($intent['c__child_intents']).'</span>';
    	    }
    	    if(isset($intent['c__message_tree_count']) && $intent['c__message_tree_count']>0){
    	        $ui .= '<span class="title-sub" data-toggle="tooltip" title="This '.$core_objects['level_'.($level-1)]['o_name'].' Has '.$intent['c__message_tree_count'].' Message'.($intent['c__message_tree_count']==1?'':'s').'"><i class="fa fa-commenting" aria-hidden="true"></i>'.$intent['c__message_tree_count'].'</span>';
    	    }
    	    if($level==2 && isset($intent['c__estimated_hours'])){
    	        $ui .= echo_time($intent['c__estimated_hours'],1,1);
    	    } elseif($level==3 && isset($intent['c_time_estimate'])){
    	        $ui .= echo_time($intent['c_time_estimate'],1,1);
    	    }
    	    $ui .= ' <span class="srt-'.$direction.'"></span>'; //For the status of sorting
    	    
	    $ui .= '</a>';
	    return $ui;
	    
	} else {
	    //Not really being used for now...
	}
}

function echo_json($array){
    header('Content-Type: application/json');
    echo json_encode($array);
}

function echo_mentorship($r_meeting_frequency,$r_meeting_duration){
    if($r_meeting_frequency=="0"){
        return "None";
    } elseif(substr($r_meeting_frequency, 0, 1)=="d"){
        return echo_hours($r_meeting_duration).' Per Day';
    } elseif(substr($r_meeting_frequency, 0, 1)=="w"){
        return (substr($r_meeting_frequency, 1, 1)=="1"?'':substr($r_meeting_frequency, 1, 1).'x').echo_hours($r_meeting_duration).' Per Week';
    } else {
        return ($r_meeting_frequency=="1" ? 'A ' : $r_meeting_frequency."x").echo_hours($r_meeting_duration).' Session'.($r_meeting_frequency=="1"?'':'s');
    }
}


function gross_mentorship($r_meeting_frequency,$r_meeting_duration,$b_sprint_unit,$b_effective_milestones,$is_fancy=true){
    $bootcamp_days = ( $b_sprint_unit=='week' ? 7 : 1 ) * $b_effective_milestones;
    
    if($r_meeting_frequency=="0"){
        $total_hours = 0;
    } elseif($r_meeting_frequency=="d1"){
        //Calculate total length:
        $total_hours = ($bootcamp_days*$r_meeting_duration);
    } elseif(substr($r_meeting_frequency, 0, 1)=="w"){
        $total_hours = ( $bootcamp_days * $r_meeting_duration / 7 * intval(substr($r_meeting_frequency, 1, 1)) );
    } else {
        //1 Time frequencies like "1" or "3"
        $total_hours = ( $r_meeting_frequency * $r_meeting_duration );
    }
    
    //Format nicely:
    $total_hours = number_format($total_hours,1);
    $parts = explode('.',$total_hours,2);
    if(intval($parts[1])==0){
        $total_hours = intval($parts[0]);
    }
    
    if($is_fancy){
        return echo_hours($total_hours);
    } else {
        return $total_hours;
    }
}

function mime_type($mime){
    if(strstr($mime, "video/")){
        return 'video';
    } else if(strstr($mime, "image/")){
        return 'image';
    } else if(strstr($mime, "audio/")){
        return 'audio';
    } else {
        return 'file';
    }
}

function date_is_past($date){
    return ((strtotime($date)-(24*3600))<strtotime(date("F j, Y")));
}

function calculate_bootcamp_status($b){
    
    $CI =& get_instance();
    $sprint_units = $CI->config->item('sprint_units');
    //A function used on the dashboard to indicate what is left before launching the bootcamp
    $progress_possible = 0; //Total points of progress
    $progress_gained = 0; //Points granted for completion
    $call_to_action = array();
    
    
    
    //Do we have enough Milestones?
    $to_gain = 60;
    $required_milestones = ( $b['b_sprint_unit']=='week' ? 2 : 3 ); //Minimum 3 days or 1 week
    $progress_possible += $to_gain;
    if(count($b['c__child_intents'])>=$required_milestones){
        $progress_gained += $to_gain;
    } else {
        $progress_gained += (count($b['c__child_intents'])/$required_milestones)*$to_gain;
        array_push($call_to_action,'Add <b>[At least '.$required_milestones.' '.$sprint_units[$b['b_sprint_unit']]['name'].' Milestone'.($required_milestones==1?'':'s').']</b>'.(count($b['c__child_intents'])>0?' ('.($required_milestones-count($b['c__child_intents'])).' more)':'').' to your <a href="/console/'.$b['b_id'].'/actionplan"><u>Action Plan</u></a>');
    }
    
    //Now check each Milestone and its Task List:
    foreach($b['c__child_intents'] as $milestone_num=>$c){
        
        if($c['c_status']<0){
            continue; //Don't check unpublished Milestones, which is not even possible for now...
        }
        
        
        //Prepare key variables:
        $milestone_anchor = ucwords($b['b_sprint_unit']).' #'.$c['cr_outbound_rank'].' ';
        
        
        //Milestone Messages
        $to_gain = 15;
        $progress_possible += $to_gain;
        $qualified_messages = 0;
        if(count($c['c__messages'])>0){
            foreach($c['c__messages'] as $i){
                $qualified_messages += ( $i['i_status']==3 ? 1 : 0 );
            }
        }
        if($qualified_messages>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Add <b>[At least 1 '.status_bible('i',3).' Message]</b> to <a href="/console/'.$b['b_id'].'/actionplan/'.$c['c_id'].'#messages"><u>'.$milestone_anchor.$c['c_objective'].'</u></a>');
        }
        
        
        
        //For the MVP we require Task details for 1 Weekly Milestone or 2 Daily Milestones, not more!
        if(($b['b_sprint_unit']=='week' && $milestone_num>0) || ($b['b_sprint_unit']=='day' && $milestone_num>1)){
            continue;
        }
        
        
        //Sub Task List
        $to_gain = 30;
        $required_tasks = ( $b['b_sprint_unit']=='week' ? 1 : 1 ); //At least one task for each for now
        $progress_possible += $to_gain;
        if(isset($c['c__child_intents']) && count($c['c__child_intents'])>=$required_tasks){
            $progress_gained += $to_gain;
        } else {
            $progress_gained += (count($c['c__child_intents'])/$required_tasks)*$to_gain;
            array_push($call_to_action,'Add <b>[At least '.$required_tasks.' Task'.($required_tasks==1?'':'s').']</b>'.(count($c['c__child_intents'])>0?' ('.($required_tasks-count($c['c__child_intents'])).' more)':'').' to <a href="/console/'.$b['b_id'].'/actionplan/'.$c['c_id'].'"><u>'.$milestone_anchor.$c['c_objective'].'</u></a>');
        }
        
        
        //Check Tasks:
        if(isset($c['c__child_intents']) && count($c['c__child_intents'])>0){
            foreach($c['c__child_intents'] as $c2){

                //Create task object:
                $task_anchor = $milestone_anchor.'Task #'.$c2['cr_outbound_rank'].' '.$c2['c_objective'];
                
                //c_time_estimate
                $to_gain = 5;
                $progress_possible += $to_gain;
                if($c2['c_time_estimate']>0){
                    $progress_gained += $to_gain;
                } else {
                    array_push($call_to_action,'Add <b>[Time Estimate]</b> to <a href="/console/'.$b['b_id'].'/actionplan/'.$c2['c_id'].'#details"><u>'.$task_anchor.'</u></a>');
                }
                
                //Messages for Tasks:
                $to_gain = 15;
                $progress_possible += $to_gain;
                $qualified_messages = 0;
                if(count($c2['c__messages'])>0){
                    foreach($c2['c__messages'] as $i){
                        $qualified_messages += ( $i['i_status']>=1 && $i['i_status']<=3 ? 1 : 0 );
                    }
                }
                if($qualified_messages>0){
                    $progress_gained += $to_gain;
                } else {
                    array_push($call_to_action,'Add <b>[At least 1 Published Message]</b> to <a href="/console/'.$b['b_id'].'/actionplan/'.$c2['c_id'].'#messages"><u>'.$task_anchor.'</u></a>');
                }
            }
        }
    }
    
    
    //Bootcamp Messages:
    $to_gain = 15;
    $progress_possible += $to_gain;
    $qualified_messages = 0;
    if(count($b['c__messages'])>0){
        foreach($b['c__messages'] as $i){
            $qualified_messages += ( $i['i_status']>=3 && $i['i_status']<4 && $i['i_media_type']=='video' ? 1 : 0 );
        }
    }
    if($qualified_messages>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Upload <b>[At least 1 '.status_bible('i',3).' Video Message]</b> to <a href="/console/'.$b['b_id'].'/actionplan#messages"><u>Action Plan</u></a>');
    }
    
    
    /* *****************************
     *  classes
     *******************************/
    
    //Let's see if we can find a drafting or published class:
    $focus_class = null;
    if(isset($b['c__classes']) && count($b['c__classes'])>0){
        foreach($b['c__classes'] as $class){
            if($class['r_status']<=1 && $class['r_status']>=0 && !date_is_past($class['r_start_date'])){
                $focus_class = $class;
                break;
            }
        }
    }
    
    //r_max_students
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($focus_class){
        if(strlen($focus_class['r_max_students'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Max Students]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    //r_prerequisites
    $to_gain = 10;
    $progress_possible += $to_gain;
    $default_class_prerequisites = $CI->config->item('default_class_prerequisites');
    if($focus_class){
        if(strlen($focus_class['r_prerequisites'])>0 && !($focus_class['r_prerequisites']==json_encode($default_class_prerequisites))){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Modify <b>[Prerequisites]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    
    //r_application_questions
    $to_gain = 10;
    $progress_possible += $to_gain;
    $default_class_questions = $CI->config->item('default_class_questions');
    if($focus_class){
        if(strlen($focus_class['r_application_questions'])>0 && !($focus_class['r_application_questions']==json_encode($default_class_questions))){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Modify <b>[Application Questions]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    //r_response_time_hours
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($focus_class){
        if(strlen($focus_class['r_response_time_hours'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Response Time]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#support"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    //r_meeting_frequency
    $to_gain = 10;
    $progress_possible += $to_gain;
    if($focus_class){
        if(strlen($focus_class['r_meeting_frequency'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[1-on-1 Mentorship Sessions]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#support"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    //r_live_office_hours
    if($focus_class){
        $to_gain = 5;
        $progress_possible += $to_gain;
        if((strlen($focus_class['r_live_office_hours'])<=0) || (strlen($focus_class['r_live_office_hours'])>0 && strlen($focus_class['r_office_hour_instructions'])>0)){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Office Hours: Contact Method]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#support"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    //r_usd_price
    $to_gain = 20;
    $progress_possible += $to_gain;
    if($focus_class){
        if(strlen($focus_class['r_usd_price'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Tuition Rate]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#pricing"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    //r_completion_prizes
    $to_gain = 10;
    $progress_possible += $to_gain;
    $default_class_prizes = $CI->config->item('default_class_prizes');
    if($focus_class){
        if(!($focus_class['r_completion_prizes']==json_encode($default_class_prizes))){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Modify <b>[Completion Prizes]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#pricing"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    //r_cancellation_policy
    $to_gain = 10;
    $progress_possible += $to_gain;
    if($focus_class){
        if($focus_class['r_usd_price']==0 || strlen($focus_class['r_usd_price'])==0 || strlen($focus_class['r_cancellation_policy'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Refund Policy]</b> for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#pricing"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }    
    
    //r_status
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($focus_class){
        if($focus_class['r_status']==1){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Change <b>[Class Status]</b> to '.status_bible('r',1).' for <a href="/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#settings"><u>'.time_format($focus_class['r_start_date'],4).' Class</u></a>');
        }
    }
    
    //Did we NOT have a next class?
    if(!$focus_class){
        //Missing class all together!
        array_push($call_to_action,'Create <b>[At least 1 Class]</b> in <a href="/console/'.$b['b_id'].'/classes"><u>Classes</u></a>');
    }
    
    
    
    /* *******************************
     *  Leader profile (for them only)
     *********************************/
    //This must exist:
    $bl = $b['b__admins'][0];
    $udata = $CI->session->userdata('user');
    $account_action = ( $b['b__admins'][0]['u_id']==$udata['u_id'] ? '<a href="/console/account"><u>My Account</u></a>' : $bl['u_fname'].' '.$bl['u_lname'].'\'s Account.' );
    

    
    //u_phone
    $to_gain = 5;
    $progress_possible += $to_gain;
    if(strlen($bl['u_phone'])>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Add <b>[Phone Number]</b> (Private) to '.$account_action);
    }
    
    //u_image_url
    $to_gain = 10;
    $progress_possible += $to_gain;
    if(strlen($bl['u_image_url'])>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Add <b>[Profile Picture URL]</b> to '.$account_action);
    }
    
    //u_country_code && u_current_city
    $to_gain = 30;
    $progress_possible += $to_gain;
    if(strlen($bl['u_country_code'])>0 && strlen($bl['u_current_city'])>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Add <b>[Current Country, City & State]</b> to '.$account_action);
    }
    
    //u_language
    $to_gain = 30;
    $progress_possible += $to_gain;
    if(strlen($bl['u_language'])>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Add <b>[Fluent Languages]</b> to '.$account_action);
    }
    
    //u_bio
    $to_gain = 30;
    $progress_possible += $to_gain;
    if(strlen($bl['u_bio'])>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Add <b>[Introductory Message]</b> to '.$account_action);
    }
    
    //Profile counter:
    $profile_counter = ( strlen($bl['u_website_url'])>0 ? 1 : 0 );
    $u_social_account = $CI->config->item('u_social_account');
    foreach($u_social_account as $sa_key=>$sa){
        $profile_counter += ( strlen($bl[$sa_key])>0 ? 1 : 0 );
    }
    
    $to_gain = 30;
    $progress_possible += $to_gain;
    $required_social_profiles = 3;
    if($profile_counter>=$required_social_profiles){
        $progress_gained += $to_gain;
    } else {
        $progress_gained += ($profile_counter/$required_social_profiles)*$to_gain;
        array_push($call_to_action,'Link <b>[At least '.$required_social_profiles.' Social Profiles]</b>'.($profile_counter>0?' ('.($required_social_profiles-$profile_counter).' more)':'').' to '.$account_action);
    }
    
    
    //u_terms_agreement_time
    $to_gain = 45;
    $progress_possible += $to_gain;
    if(strlen($bl['u_terms_agreement_time'])>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Agree to <b>[Instructor Agreement]</b> in '.$account_action);
    }
        
    
    /* *****************************
     *  Bootcamp Settings
     *******************************/
    
    
    //b_category_id
    $to_gain = 15;
    $progress_possible += $to_gain;
    if($b['b_category_id']>=1){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Select <b>[Bootcamp Category]</b> in <a href="/console/'.$b['b_id'].'/settings"><u>Settings</u></a>');
    }
    
    
    //b_status
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($b['b_status']>=1){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Finally change <b>[Bootcamp Status]</b> to '.status_bible('b',1).' in <a href="/console/'.$b['b_id'].'/settings"><u>Settings</u></a>');
    }
    
    
    $progress_percentage = round($progress_gained/$progress_possible*100);
    if($progress_percentage==100){
        array_push($call_to_action,'Review your <a href="/'.$b['b_url_key'].'" target="_blank"><u>Bootcamp Landing Page</u> <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></a> to make sure it all looks good.');
        array_push($call_to_action,'Wait until Mench team updates your bootcamp status to '.status_bible('b',2));
        array_push($call_to_action,'Launch admissions by sending a message to your student list.');
    }
    return array(
        'stage' => '<i class="fa fa-tasks" aria-hidden="true"></i> Bootcamp <span class="underl" data-toggle="tooltip" data-placement="bottom" title="MVP = Minimum Viable Product = Build a basic version of your Bootcamp  within 15-20 hours and then iteratively improve it over time.">MVP</span> Checklist',
        'progress' => $progress_percentage,
        'call_to_action' => $call_to_action,
    );
}

function is_valid_intent($c_id){
    $CI =& get_instance();
    $intents = $CI->Db_model->c_fetch(array(
        'c.c_id' => intval($c_id),
        'c.c_status >=' => 0, //Drafting or higher
    ));
    return (count($intents)==1);
}


function echo_ordinal($number){
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($number %100) >= 11 && ($number%100) <= 13){
        return $number. 'th';
    } else {
        return $number. $ends[$number % 10];
    }
}

function echo_status_dropdown($object,$input_name,$current_status_id,$exclude_ids=array(),$direction='dropdown',$level=0){
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $inner_tooltip = ($direction=='dropup'?'top':'top');
    $now = status_bible($object,$current_status_id,0,$inner_tooltip,$level);
    
    $return_ui = '';
    $return_ui .= '<input type="hidden" id="'.$input_name.'" value="'.$current_status_id.'" /> 
    <div style="display:inline-block;" class="'.$direction.'">
    	<a href="#" style="margin: 0;" class="btn btn-simple dropdown-toggle border" id="ui_'.$input_name.'" data-toggle="dropdown">
        	'.( $now ? $now : 'Select...' ).'
        	<b class="caret"></b>
    	</a>
        <ul class="dropdown-menu">';
    $statuses = status_bible($object,null,false,'bottom',$level);
    $count = 0;
    foreach($statuses as $intval=>$status){
        if(isset($status['u_min_status']) && ($udata['u_status']<$status['u_min_status'] || in_array($intval,$exclude_ids))){
            //Do not enable this user to modify to this status:
            continue;
        }
        $count++;
        $return_ui .= '<li><a href="javascript:update_dropdown(\''.$input_name.'\','.$intval.','.$count.');">'.status_bible($object,$intval,0,$inner_tooltip,$level).'</a></li>';
        $return_ui .= '<li style="display:none;" id="'.$input_name.'_'.$count.'">'.status_bible($object,$intval,0,$inner_tooltip,$level).'</li>'; //For UI replacement
    }
    $return_ui .= '</ul></div>';
    return $return_ui;
}

function hourformat($fancy_hour){
    if(substr_count($fancy_hour,'am')>0){
        $fancy_hour = str_replace('am','',$fancy_hour);
        $temp = explode(':',$fancy_hour,2);
        return (intval($temp[0]) + ( isset($temp[1]) ? (intval($temp[1])/60) : 0 ));
    } elseif(substr_count($fancy_hour,'pm')>0){
        $fancy_hour = str_replace('pm','',$fancy_hour);
        $temp = explode(':',$fancy_hour,2);
        return (intval($temp[0]) + ( isset($temp[1]) ? (intval($temp[1])/60) : 0 ) + (intval($temp[0])==12?0:12) );
    }
}

function status_bible($object=null,$status=null,$micro_status=false,$data_placement='bottom',$level=0){
	
    //IF you make any changes, make sure to also reflect in the view/console/guides/status_bible.php as well
	$status_index = array(
	    'b' => array(
	        -1 => array(
	            's_name'  => 'Archive',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Bootcamp archived by lead instructor.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-trash',
	        ),
	        0 => array(
	            's_name'  => 'Drafting',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Bootcamp under development. Admissions starts when published live.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-pencil-square',
	        ),
	        1 => array(
	            's_name'  => 'Request To Publish',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Bootcamp submit to be reviewed by Mench team to be published live.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-eye',
	        ),
	        2 => array(
    	        's_name'  => 'Published',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Ready for student admission by sharing your landing page URL.',
    	        'u_min_status'  => 3, //Can only be done by admin
    	        's_mini_icon' => 'fa-bullhorn',
	        ),
	        3 => array(
    	        's_name'  => 'Published to Marketplace',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Ready for student admission by URL sharing and by being visible in the Mench marketplace.',
    	        'u_min_status'  => 3, //Can only be done by admin
    	        's_mini_icon' => 'fa-bullhorn',
	        ),
	    ),
	    'c' => array(
	        -1 => array(
	            's_name'  => 'Archive',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Task removed.',
	            'u_min_status'  => 999, //Not possible for now.
	            's_mini_icon' => 'fa-trash',
	        ),
	        0 => array(
	            's_name'  => 'Drafting',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Task being drafted and not accessible by students until published live',
	            'u_min_status'  => 3,
	            's_mini_icon' => 'fa-pencil-square',
	        ),
	        1 => array(
	            's_name'  => 'Published',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Task is active and accessible by students.',
	            'u_min_status'  => 3,
	            's_mini_icon' => 'fa-bullhorn',
	        ),
	    ),
	    'r' => array(
    	    -2 => array(
        	    's_name'  => 'Cancel',
    	        's_color' => '#2f2639', //dark
        	    's_desc'  => 'Class was cancelled after it had started.',
        	    'u_min_status'  => 3,
        	    's_mini_icon' => 'fa-times-circle',
    	    ),
    	    -1 => array(
        	    's_name'  => 'Archive',
    	        's_color' => '#2f2639', //dark
        	    's_desc'  => 'Class removed by bootcamp leader before it was started.',
        	    'u_min_status'  => 2,
        	    's_mini_icon' => 'fa-trash',
    	    ),
	        0 => array(
	            's_name'  => 'Drafting',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Class not yet ready for admission as its being modified.',
	            'u_min_status'  => 2,
	            's_mini_icon' => 'fa-pencil-square',
	        ),
	        1 => array(
    	        's_name'  => 'Admission Open',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Class is open for student admission.',
    	        'u_min_status'  => 2,
    	        's_mini_icon' => 'fa-bullhorn',
	        ),
	        2 => array(
    	        's_name'  => 'Running',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Class has admitted students and is currently running.',
    	        'u_min_status'  => 3,
    	        's_mini_icon' => 'fa-play-circle',
	        ),
	        3 => array(
    	        's_name'  => 'Completed',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Class was operated completely until its last day.',
    	        'u_min_status'  => 3,
    	        's_mini_icon' => 'fa-graduation-cap',
	        ),
	    ),
	    'i' => array(
	        -1 => array(
	            's_name'  => 'Archive',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Message removed.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-trash',
	        ),
	        0 => array(
	            's_name'  => 'Drafting',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Message not visible to students while drafting.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-pencil-square',
	        ),
	        1 => array(
    	        's_name'  => 'Student ASAP',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Message sent to students as soon as '.( $level==1 ? 'bootcamp' : 'milestone' ).' starts. Good for mission-critical messages.',
    	        'u_min_status'  => 1,
    	        's_mini_icon' => 'fa-bolt',
	        ),
	        2 => array(
    	        's_name'  => 'Student Drip',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Message sent to students sometime during the '.( $level==1 ? 'bootcamp' : 'milestone' ).'. Good for increasing student engagements.',
    	        's_mini_icon' => 'fa-tint',
    	        'u_min_status'  => 1,
	        ),
	        3 => array(
    	        's_name'  => 'Landing Page',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Message published on the Landing Page and sent to students as soon as '.( $level==1 ? 'bootcamp' : 'milestone' ).' starts.',
    	        's_mini_icon' => 'fa-bullhorn',
    	        'u_min_status'  => 1,
	        ),
	        4 => array(
    	        's_name'  => 'Private Note',
    	        's_color' => '#2f2639', //dark
    	        's_desc'  => 'This Message is taken by the instructor team on a particular student and is visible to the entire team.',
    	        's_mini_icon' => 'fa-eye-slash',
    	        'u_min_status'  => 1,
	        ),
	    ),
	    
	    'cr' => array(
	        -1 => array(
	            's_name'  => 'Archive',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Task link removed.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-trash',
	        ),
	        1 => array(
	            's_name'  => 'Publish',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Task link is active.',
	            'u_min_status'  => 1,
	        ),
	    ),
	    
	    //User related statuses:
	    
	    'ba' => array(
	        -1 => array(
	            's_name'  => 'Revoked',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Bootcamp access revoked.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-minus-circle',
	        ),
	        /*
	        1 => array(
	            's_name'  => 'Assistant',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Not active!',
	            'u_min_status'  => 1,
	        ),
	        */
	        2 => array(
	            's_name'  => 'Co-Instructor',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Supports the lead instructor in bootcamp operations based on specific privileges assigned to them.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-user-plus',
	        ),
	        3 => array(
	            's_name'  => 'Lead Instructor',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'The bootcamp CEO who is responsible for the bootcamp performance measured by its completion rate.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-star',
	        ),
	    ),
	    
	    'u' => array(
	        -2 => array(
	            's_name'  => 'Merged',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'User merged with another user',
	            'u_min_status'  => 3, //Only admins can delete user accounts, or the user for their own account
	            's_mini_icon' => 'fa-user-times',
	        ),
	        -1 => array(
	            's_name'  => 'Delete',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'User no longer active.',
	            'u_min_status'  => 3, //Only admins can delete user accounts, or the user for their own account
	            's_mini_icon' => 'fa-user-times',
	        ),
	        0 => array(
	            's_name'  => 'Pending',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'User added by the students but has not yet claimed their account.',
	            'u_min_status'  => 999, //System only
	            's_mini_icon' => 'fa-user-o',
	        ),
	        1 => array(
	            's_name'  => 'Active',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'User active.',
	            's_mini_icon' => 'fa-user',
	            'u_min_status'  => 3, //Only admins can downgrade users from a leader status
	        ),
	        2 => array(
	            's_name'  => 'Lead Instructor',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'User onboarded as bootcamp leader and can create/manage their own bootcamps.',
	            's_mini_icon' => 'fa-star',
	            'u_min_status'  => 3, //Only admins can approve leaders
	        ),
	        3 => array(
	            's_name'  => 'Mench Admin',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'User part of Mench team who facilitates bootcamp operations.',
	            's_mini_icon' => 'fa-shield',
	            'u_min_status'  => 3, //Only admins can create other admins
	        ),
	    ),
	    
	    'us' => array(
    	    -1 => array(
        	    's_name'  => 'Requires Revision',
    	        's_color' => '#2f2639', //dark
        	    's_desc'  => 'Intructor has reviewed submission and found issues with it that requires student attention.',
        	    'u_min_status'  => 1,
        	    's_mini_icon' => 'fa-exclamation-triangle',
    	    ),
    	    1 => array(
        	    's_name'  => 'Marked Done',
    	        's_color' => '#2f2639', //dark
        	    's_desc'  => 'Milestone tasks are marked as done.',
        	    'u_min_status'  => 1,
        	    's_mini_icon' => 'fa-check-square',
    	    ),
	    ),
	    
	    
	    'ru' => array(
	        
	        //Withrew after course has started:
	        -3 => array(
	            's_name'  => 'Student Dispelled',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Student was dispelled due to misconduct. Refund at the discretion of bootcamp leader.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-times-circle',
	        ),
	        //Withrew prior to course has started:
	        -2 => array(
	            's_name'  => 'Student Withdrew',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Student withdrew from the bootcamp. Refund given based on the class refund policy & withdrawal date.',
	            'u_min_status'  => 999, //Only done by Student themselves
	            's_mini_icon' => 'fa-times-circle',
	        ),
	        -1 => array(
	            's_name'  => 'Admission Rejected',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Application rejected by bootcamp leader before start date. Students receives a full refund.',
	            'u_min_status'  => 1,
	            's_mini_icon' => 'fa-times-circle',
	        ),
	        
	        //Post Application
	        0 => array(
    	        's_name'  => 'Admission Initiated',
	            's_color' => '#2f2639', //dark
    	        's_desc'  => 'Student has started the application process but has not completed it yet.',
    	        'u_min_status'  => 999, //System insertion only
    	        's_mini_icon' => 'fa-pencil-square',
	        ),
	        
	        /*
	        1 => array(
	            's_name'  => 'Applied - Pending Full Payment',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Student has applied but has not paid in full yet, pending bootcamp leader approval before paying in full.',
	            'u_min_status'  => 999, //System insertion only
	        ),
	        */
	        2 => array(
	            's_name'  => 'Pending Admission',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Student has applied, paid in full and is pending application review & approval.',
	            's_mini_icon' => 'fa-pause-circle',
	            'u_min_status'  => 999, //System insertion only
	        ),
	        
	        
	        /*
	        3 => array(
	            's_name'  => 'Invitation Sent',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Admins have full access to all bootcamp features.',
	            'u_min_status'  => 1,
	        ),
	        */
	        4 => array(
	            's_name'  => 'Bootcamp Student',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Student admitted making them ready to participate in bootcamp.',
	            's_mini_icon' => 'fa-user',
	            'u_min_status'  => 1,
	        ),
	        
	        //Completion
	        5 => array(
	            's_name'  => 'Bootcamp Graduate',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Student completed class and completed all Milestones as approved by lead instructor.',
	            's_mini_icon' => 'fa-graduation-cap',
	            'u_min_status'  => 1,
	        ),
	    ),
	    
	    'ct' => array(
    	    //Withrew after course has started:
    	    1 => array(
    	    's_name'  => 'Development',
    	    's_mini_icon' => 'fa-code',
    	    ),
	        7 => array(
	            's_name'  => 'Business',
	            's_mini_icon' => 'fa-handshake-o',
	        ),
	        9=> array(
	            's_name'  => 'IT & Software',
	            's_mini_icon' => 'fa-laptop',
	        ),
	        10=> array(
	            's_name'  => 'Design',
	            's_mini_icon' => 'fa-paint-brush',
	        ),
	        13=> array(
	            's_name'  => 'Marketing',
	            's_mini_icon' => 'fa-bullseye',
	        ),
    	    2=> array(
    	    's_name'  => 'Music',
    	    's_mini_icon' => 'fa-music',
    	    ),
    	    3=> array(
    	    's_name'  => 'Teacher Training',
    	    's_mini_icon' => 'fa-graduation-cap',
    	    ),
    	    4=> array(
    	    's_name'  => 'Language',
    	    's_mini_icon' => 'fa-language',
    	    ),
	        /*
    	    5=> array(
    	    's_name'  => 'Academics',
    	    's_mini_icon' => 'fa-flask',
    	    ),
    	    12=> array(
    	    's_name'  => 'Lifestyle',
    	    's_mini_icon' => 'fa-repeat',
    	    ),
    	    */
    	    
    	    8=> array(
    	    's_name'  => 'Office Productivity',
    	    's_mini_icon' => 'fa-briefcase',
    	    ),
    	    
    	    11=> array(
    	    's_name'  => 'Personal Development',
    	    's_mini_icon' => 'fa-smile-o',
    	    ),
    	    
    	    14=> array(
    	    's_name'  => 'Health & Fitness',
    	    's_mini_icon' => 'fa-heartbeat',
    	    ),
	        
    	    15=> array(
    	    's_name'  => 'Photography',
    	    's_mini_icon' => 'fa-camera',
    	    ),
	    ),
	);	
	
	
	//Return results:
	if(is_null($object)){
		//Everything
	    return $status_index;
	} elseif(is_null($status)){
		//Object Specific
	    return ( isset($status_index[$object]) ? $status_index[$object] : false );
	} else {
	    $status = intval($status);
	    if(!isset($status_index[$object][$status])){
	        return false;
	    }
	    
		//We have two skins for displaying statuses:
	    return '<span class="status-label" style="color:'.( isset($status_index[$object][$status]['s_color']) ? $status_index[$object][$status]['s_color'] : '#2f2639').';" '.(isset($status_index[$object][$status]['s_desc'])?'data-toggle="tooltip" data-placement="'.$data_placement.'" title="'.$status_index[$object][$status]['s_desc'].'" aria-hidden="true"':'').'><i class="fa '.( isset($status_index[$object][$status]['s_mini_icon']) ? $status_index[$object][$status]['s_mini_icon'] : 'fa-circle' ).' initial"></i>'.($micro_status?'':$status_index[$object][$status]['s_name']).'</span>';
	    
	    //Older version: return '<span class="label label-default" style="background-color:'.$status_index[$object][$status]['s_color'].';" data-toggle="tooltip" data-placement="'.$data_placement.'" title="'.$status_index[$object][$status]['s_desc'].'">'.strtoupper($status_index[$object][$status]['s_name']).' <i class="fa fa-info-circle" aria-hidden="true"></i></span>';
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

function filter_class($classes,$r_id=null){
    if(!$classes || count($classes)<=0){
        return false;
    }
    
    foreach($classes as $class){
        if($class['r_status']==1 && !date_is_past($class['r_start_date']) && ($class['r__current_admissions']<$class['r_max_students'] || !$class['r_max_students']) && (!$r_id || ($r_id==$class['r_id']))){
            return $class;
            break;
        }
    }
    
    return false;
}

function typeform_url($typeform_id){
    return 'https://mench.typeform.com/to/'.$typeform_id;
}


function echo_chat(){
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $bot_activation_salt = $CI->config->item('bot_activation_salt');
    //This is for the instructor bot:
    return '<div class="fb-customerchat" page_id="1169880823142908" ref="'.( isset($udata['u_id']) && strlen($udata['u_fb_i_id'])<4 ? 'msgact_'.$udata['u_id'].'_'.substr(md5($udata['u_id'].$bot_activation_salt),0,8) : '').'"></div>';
}

function messenger_activation_url($botkey,$u_id=null){
    $CI =& get_instance();
    $mench_bots = $CI->config->item('mench_bots');
    $bot_activation_salt = $CI->config->item('bot_activation_salt');
    if(isset($mench_bots[$botkey]['bot_ref_url'])){
        return $mench_bots[$botkey]['bot_ref_url'].($u_id?'?ref=msgact_'.$u_id.'_'.substr(md5($u_id.$bot_activation_salt),0,8):''); //TODO: Maybe append some sort of hash for more security
    } else {
        return false;
    }
}

function redirect_message($url,$message){
	$CI =& get_instance();
	$CI->session->set_flashdata('hm', $message);
	header("Location: ".$url);
	exit;
}

function remote_mime($file_url){
    //Fetch Remote:
    $ch = curl_init($file_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $mime;
}

function save_file($file_url,$json_data,$is_local=false){
    $CI =& get_instance();
    
    $file_name = md5($file_url.'fileSavingSa!t').'.'.fetch_file_ext($file_url);
    
    if(!$is_local){
        //Save this remote file to local first:
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
        $fp = @fopen( $file_path.$file_name , 'w');
    }
    
    //Then upload to AWS S3:
    if(($is_local || (isset($fp) && $fp)) && @require_once( 'application/libraries/aws/aws-autoloader.php' )){
        
        if(isset($fp)){
            fwrite($fp, $result);
            fclose($fp);
        }
        
        $s3 = new Aws\S3\S3Client([
            'version' 		=> 'latest',
            'region'  		=> 'us-west-2',
            'credentials' 	=> $CI->config->item('aws_credentials'),
        ]);
        $result = $s3->putObject(array(
            'Bucket'       => 's3foundation', //Same bucket for now
            'Key'          => $file_name,
            'SourceFile'   => ( $is_local ? $file_url : $file_path.$file_name ),
            'ACL'          => 'public-read'
        ));
        
        if(isset($result['ObjectURL']) && strlen($result['ObjectURL'])>10){
            @unlink(( $is_local ? $file_url : $file_path.$file_name ));
            return $result['ObjectURL'];
        } else {
            $CI->Db_model->e_create(array(
                'e_message' => 'save_file() Unable to upload file ['.$file_url.'] to Mench cloud.',
                'e_json' => json_encode($json_data),
                'e_type_id' => 8, //Platform Error
            ));
            return false;
        }
        
    } else {
        //Probably local, ignore this!
        return false;
    }
}

function readable_updates($before,$after,$remove_prefix){
    $message = null;
    foreach($after as $key=>$after_value){
        if(isset($before[$key]) && !($before[$key]==$after_value)){
            //Change detected!
            if($message){
                $message .= "\n";
            }
            $message .= '- Updated '.ucwords(str_replace('_',' ',str_replace($remove_prefix,'',$key))).' from ['.strip_tags($before[$key]).'] to ['.strip_tags($after_value).']';
        }
    }
    
    if(!$message){
        //No changes detected!
        $message = 'Nothing updated!';
    }
    
    return $message;
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

function time_format($t,$format=0,$plus_days=0){
    if(!$t){
        return 'NOW';
    }
    
    $timestamp = ( strlen(intval($t))==strlen($t) ? $t : strtotime(substr($t,0,19)) ) + ($plus_days*24*3600) + ($plus_days>0 ? (12*3600) : 0); //Added this last part to consider the end of days for dates
    $this_year = ( date("Y")==date("Y",$timestamp) );
    if($format==0){
        return date(( $this_year ? "M j, g:i a" : "M j, Y, g:i a" ),$timestamp);
    } elseif($format==1){
        return date(( $this_year ? "j M" : "j M Y" ),$timestamp);
    } elseif($format==2){
        return date(( $this_year ? "D j M" : "D j M Y" ),$timestamp);
    } elseif($format==3){
        return $timestamp;
    } elseif($format==4){
        return date(( $this_year ? "M j" : "M j Y" ),$timestamp);
    } elseif($format==5){
        return date(( $this_year ? "D j M" : "D j M Y" ),$timestamp);
    } elseif($format==6){
        return date("Y/m/d",$timestamp);
    } elseif($format==7){
        return date(( $this_year ? "D M j, g:i a" : "D M j, Y, g:i a" ),$timestamp);
    } 
    
}

function time_diff($t,$second_time=null){
    if(!$second_time){
        $second_time = time(); //Now
    } else {
        $second_time = strtotime(substr($second_time,0,19));
    }
    $time = $second_time - strtotime(substr($t,0,19)); // to get the time since that moment
	$is_future = ( $time<0 );
	$time = abs($time);
	$tokens = array (
			31536000 => 'Year',
			2592000 => 'Month',
			604800 => 'Week',
			86400 => 'Day',
			3600 => 'Hour',
			60 => 'Minute',
			1 => 'Second'
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




function generate_hashtag($text){
    //These hashtags cannot be taken
    $CI =& get_instance();
    $reserved_hashtags = $CI->config->item('reserved_hashtags');
    
    //Cleanup the text:
    $text = trim($text);
    $text = ucwords($text);
    $text = str_replace('&','And',$text);
    $text = preg_replace("/[^a-zA-Z0-9]/", "", $text);
    $text = substr($text,0,30);
    
    //Now check to make sure its all good!
    if(in_array(strtolower($text),$reserved_hashtags)){
        //Oops, they cannot pick this, lets add a random number to this!
        $text .= rand(1,9999);
    }
    
    return $text;    
}

function one_two_explode($one,$two,$content){
	if(substr_count($content, $one)<1){
		return NULL;
	}
	$temp = explode($one,$content,2);
	$temp = explode($two,$temp[1],2);
	return trim($temp[0]);
}


function make_links_clickable($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1"><u>$1</u></a>', $text);
}

function format_e_message($e_message){
    
    //Do replacements:
    if(substr_count($e_message,'/attach ')>0){
        $attachments = explode('/attach ',$e_message);
        foreach($attachments as $key=>$attachment){
            if($key==0){
                //We're gonna start buiolding this message from scrach:
                $e_message = $attachment;
                continue;
            }
            $segments = explode(':',$attachment,2);
            $sub_segments = preg_split('/[\s]+/', $segments[1] );
            
            if($segments[0]=='image'){
                $e_message .= '<a href="'.$sub_segments[0].'" target="_blank"><img src="'.$sub_segments[0].'" style="max-width:100%" /></a>';
            } elseif($segments[0]=='audio'){
                $e_message .= '<audio controls><source src="'.$sub_segments[0].'" type="audio/mpeg"></audio>';
            } elseif($segments[0]=='video'){
                $e_message .= '<video width="100%" onclick="this.play()" controls><source src="'.$sub_segments[0].'" type="video/mp4"></video>';
            } elseif($segments[0]=='file'){
                $e_message .= '<a href="'.$sub_segments[0].'" class="btn btn-primary" target="_blank"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download File</a>';
            }
            
            //Do we have any leftovers after the URL? If so, append:
            if(isset($sub_segments[1])){
                $e_message = ' '.$sub_segments[1];
            }
        }
    } else {
        $e_message = make_links_clickable($e_message);
    }
    $e_message = nl2br($e_message);
    return $e_message;
}


function minutes_to_hours($mins){
    return floor(($mins/60)).':'.fmod($mins,60);
}

function email_application_url($udata){
    $to_array = array($udata['u_email']);
    $CI =& get_instance();
    $subject = 'Mench Bootcamp Application';
    $application_status_salt = $CI->config->item('application_status_salt');
    $application_status_url = 'https://mench.co/my/applications?u_key='.md5($udata['u_id'].$application_status_salt).'&u_id='.$udata['u_id'];
    $html_message = null; //Start
    $html_message .= '<div>Hi '.$udata['u_fname'].',</div><br />';
    $html_message .= '<div>Here is your bootcamp application link so you can easily access it in the future:</div><br />';
    $html_message .= '<div><a href="'.$application_status_url.'" target="_blank">'.$application_status_url.'</a></div><br />';
    $html_message .= '<div>Talk soon.</div>';
    $html_message .= '<div>Team Mench</div>';
    $CI->load->model('Email_model');
    return $CI->Email_model->send_single_email($to_array,$subject,$html_message);
}


function object_link($object,$id,$b_id=0){
    //Loads the name (and possibly URL) for $object with id=$id
    $CI =& get_instance();
    $core_objects = $CI->config->item('core_objects');
    $id = intval($id);
    
    if($id>0){
        //Used mainly for engagement tracking
        $website = $CI->config->item('website');
        
        if($object=='c'){
            //Fetch intent/task:
            $intents = $CI->Db_model->c_fetch(array(
            'c.c_id' => $id,
            ));
            if(isset($intents[0])){
                if($b_id){
                    //We can return a link:
                    return '<a href="'.$website['url'].'console/'.$b_id.'/actionplan/'.$intents[0]['c_id'].'">'.$intents[0]['c_objective'].'</a>';
                } else {
                    return $intents[0]['c_objective'];
                }
            }
        } elseif($object=='b'){
            
            $bootcamps = $CI->Db_model->c_full_fetch(array(
                'b.b_id' => $id,
            ));
            if(isset($bootcamps[0])){
                if($b_id){
                    return '<a href="'.$website['url'].'console/'.$bootcamps[0]['b_id'].'">'.$bootcamps[0]['c_objective'].'</a>';
                } else {
                    return $bootcamps[0]['c_objective'];
                }
            }
            
        } elseif($object=='u'){
            if($id<=0){
                return 'System';
            } else {
                $matching_users = $CI->Db_model->u_fetch(array(
                    'u_id' => $id,
                ));
                if(isset($matching_users[0])){
                    //TODO Link to profile or chat widget link maybe?
                    return '<span title="ID '.$id.'">'.$matching_users[0]['u_fname'].' '.$matching_users[0]['u_lname'].'</span>';
                }
            }
                
        } elseif($object=='r'){
            $classes = $CI->Db_model->r_fetch(array(
                'r.r_id' => $id,
            ));
            if(isset($classes[0])){
                if($b_id){
                    //We can return a link:
                    return '<a href="'.$website['url'].'console/'.$b_id.'/classes/'.$classes[0]['r_id'].'">'.time_format($classes[0]['r_start_date'],1).'</a>';
                } else {
                    return time_format($classes[0]['r_start_date'],1);
                }
            }
        } elseif($object=='cr'){
            //TODO later...
        } elseif($object=='t'){
            //Transaction
            //TODO later...
        } elseif($object=='i'){
            //Fetch message conent:
            $matching_messages = $CI->Db_model->i_fetch(array(
                'i_id' => $id,
            ));
            if(isset($matching_messages[0])){
                //TODO Link to profile or chat widget link maybe?
                return echo_i($matching_messages[0]);
            }
        }
    }
    
    //Still here? Return default:
    if($id>0){
        return '#'.$id;
    } else {
        return NULL;
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


function tree_message($intent_id, $outbound_levels=0 /* 0 is same level messages only, 1 means 1 level down, etc... */, $botkey, $e_recipient_u_id, $notification_type='REGULAR' /*REGULAR/SILENT_PUSH/NO_PUSH*/, $b_id=0, $r_id=0){
    
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $mench_bots = $CI->config->item('mench_bots');
    $e_initiator_u_id = ( isset($udata['u_id']) ? intval($udata['u_id']) : 0 );
    $e_recipient_u_id = intval($e_recipient_u_id); //Just making sure
    
    //Fetch tree and its messages:
    $tree = $CI->Db_model->c_fetch(array(
        'c.c_id' => $intent_id,
    ) , $outbound_levels , array('i') /* Append messages to the return */ );
    
    
    
    //Make sure we have the core components checked:
    if(!isset($tree[0])){
        return array(
            'status' => 0,
            'message' => 'Invalid Intent ID',
        );
    } elseif(!array_key_exists($botkey,$mench_bots)){
        return array(
            'status' => 0,
            'message' => 'Invalid Bot ID',
        );
    } elseif($outbound_levels<0 || $outbound_levels>2){
        return array(
            'status' => 0,
            'message' => 'Invalid Outbound Level',
        );
    } elseif(!in_array(strtoupper($notification_type),array('REGULAR','SILENT_PUSH','NO_PUSH'))){
        return array(
            'status' => 0,
            'message' => 'Invalid Notification type',
        );
    }
    
    //Validate recipient:
    $recipients = $CI->Db_model->u_fetch(array(
        'u_id' => $e_recipient_u_id,
    ));
    
    if(!isset($recipients[0])){
        return array(
            'status' => 0,
            'message' => 'Invalid Recipient ID',
        );
    }
    $recipient_fb_psid = $recipients[0][$mench_bots[$botkey]['u_db']];
    if(strlen($recipient_fb_psid)<4){
        return array(
            'status' => 0,
            'message' => 'Recipient has not activated this bot yet',
        );
    }
    
    //Define key variables:
    $instant_messages = array();
    $drip_count = 0;
    
    if(isset($tree[0]['c__messages']) && count($tree[0]['c__messages'])>0){
        //We have messages for the very first level!
        foreach($tree[0]['c__messages'] as $i){
            if($i['i_status']==2){
                
                //Increase counter:
                $drip_count++;
                
                //This has a drip sequence, subscribe the user for later messages on this:
                $CI->Db_model->e_create(array(
                    'e_initiator_u_id' => $e_initiator_u_id,
                    'e_recipient_u_id' => $e_recipient_u_id,
                    'e_c_id' => $intent_id,
                    'e_message' => 'Scheduled to drip...', //Stage 1 Message
                    'e_json' => json_encode(array(
                        'outbound_levels' => $outbound_levels,
                        'original_tree' => $tree,
                        'top_c_id' => $intent_id,
                    )),
                    'e_cron_job' => 0, //drip this thread later on using its cron job...
                    'e_type_id' => 49, //Messenger drip tree
                    'e_b_id' => $b_id, //If set...
                    'e_r_id' => $r_id, //If set...
                    'e_i_id' => $i['i_id'], //The message that is being dripped
                    'e_fb_page_id' => $botkey,
                ));
                
            } elseif(in_array($i['i_status'],array(1,3))){
                
                //These are to be instantly distributed:
                array_push( $instant_messages , echo_i($i, $i['u_fname'], true /*Facebook Format*/ ));
                
                //Long sent engagement:
                $CI->Db_model->e_create(array(
                    'e_initiator_u_id' => $e_initiator_u_id,
                    'e_recipient_u_id' => $e_recipient_u_id,
                    'e_c_id' => $intent_id,
                    'e_message' => ( $i['i_media_type']=='text' ? $i['i_message'] : '/attach '.$i['i_media_type'].':'.$i['i_url'] ), //For engagement dashboard...
                    'e_json' => json_encode(array(
                        'outbound_levels' => $outbound_levels,
                        'original_tree' => $tree,
                        'top_c_id' => $intent_id,
                    )),
                    'e_type_id' => 7, //Outbound message
                    'e_b_id' => $b_id, //If set...
                    'e_r_id' => $r_id, //If set...
                    'e_i_id' => $i['i_id'], //The message that is being dripped
                    'e_fb_page_id' => $botkey,
                ));
            }
        }
    }
    
    
    if($outbound_levels>=1 && isset($tree[0]['c__child_intents']) && count($tree[0]['c__child_intents'])>0){
        //We have some child intents, see if they have any messages:
        foreach($tree[0]['c__child_intents'] as $level1){
            
            //Does this intent have messages?
            if(isset($level1['c__messages']) && count($level1['c__messages'])>0){
                foreach($level1['c__messages'] as $i){
                    
                    if($i['i_status']==2){
                        
                        //Increase counter:
                        $drip_count++;
                        
                        //This has a drip sequence, subscribe the user for later messages on this:
                        $CI->Db_model->e_create(array(
                            'e_initiator_u_id' => $e_initiator_u_id,
                            'e_recipient_u_id' => $e_recipient_u_id,
                            'e_c_id' => $level1['c_id'],
                            'e_message' => 'Scheduled to drip...', //Stage 1 Message
                            'e_json' => json_encode(array(
                                'outbound_levels' => $outbound_levels,
                                'original_tree' => $tree,
                                'top_c_id' => $intent_id,
                            )),
                            'e_cron_job' => 0, //drip this thread later on using its cron job...
                            'e_type_id' => 49, //Messenger drip message
                            'e_b_id' => $b_id, //If set...
                            'e_r_id' => $r_id, //If set...
                            'e_i_id' => $i['i_id'], //The message that is being dripped
                            'e_fb_page_id' => $botkey,
                        ));
                        
                    } elseif(in_array($i['i_status'],array(1,3))){
                        
                        //These are to be instantly distributed:
                        array_push( $instant_messages , echo_i($i, $i['u_fname'], true /*Facebook Format*/ ));
                        
                        //Long sent engagement:
                        $CI->Db_model->e_create(array(
                            'e_initiator_u_id' => $e_initiator_u_id,
                            'e_recipient_u_id' => $e_recipient_u_id,
                            'e_c_id' => $level1['c_id'],
                            'e_message' => ( $i['i_media_type']=='text' ? $i['i_message'] : '/attach '.$i['i_media_type'].':'.$i['i_url'] ), //For engagement dashboard...
                            'e_json' => json_encode(array(
                                'outbound_levels' => $outbound_levels,
                                'original_tree' => $tree,
                                'top_c_id' => $intent_id,
                            )),
                            'e_type_id' => 7, //Outbound message
                            'e_b_id' => $b_id, //If set...
                            'e_r_id' => $r_id, //If set...
                            'e_i_id' => $i['i_id'], //The message that is being dripped
                            'e_fb_page_id' => $botkey,
                        ));
                    }
                }
            }
            
            //Any child intents and a need to go Deeper?
            if($outbound_levels>=2 && isset($level1['c__child_intents']) && count($level1['c__child_intents'])>0){
                //We have some child intents, see if they have any messages:
                foreach($level1['c__child_intents'] as $level2){
                    if(isset($level2['c__messages']) && count($level2['c__messages'])>0){
                        foreach($level2['c__messages'] as $i){
                            if($i['i_status']==2){
                                
                                //Increase counter:
                                $drip_count++;
                                
                                //This has a drip sequence, subscribe the user for later messages on this:
                                $CI->Db_model->e_create(array(
                                    'e_initiator_u_id' => $e_initiator_u_id,
                                    'e_recipient_u_id' => $e_recipient_u_id,
                                    'e_c_id' => $level2['c_id'],
                                    'e_message' => 'Scheduled to drip...', //Stage 1 Message
                                    'e_json' => json_encode(array(
                                        'outbound_levels' => $outbound_levels,
                                        'original_tree' => $tree,
                                        'top_c_id' => $intent_id,
                                    )),
                                    'e_cron_job' => 0, //drip this thread later on using its cron job...
                                    'e_type_id' => 49, //Messenger drip message
                                    'e_b_id' => $b_id, //If set...
                                    'e_r_id' => $r_id, //If set...
                                    'e_i_id' => $i['i_id'], //The message that is being dripped
                                    'e_fb_page_id' => $botkey,
                                ));
                                
                            } elseif(in_array($i['i_status'],array(1,3))){
                                
                                //These are to be instantly distributed:
                                array_push( $instant_messages , echo_i($i, $i['u_fname'], true /*Facebook Format*/ ));
                                
                                //Long sent engagement:
                                $CI->Db_model->e_create(array(
                                    'e_initiator_u_id' => $e_initiator_u_id,
                                    'e_recipient_u_id' => $e_recipient_u_id,
                                    'e_c_id' => $level2['c_id'],
                                    'e_message' => ( $i['i_media_type']=='text' ? $i['i_message'] : '/attach '.$i['i_media_type'].':'.$i['i_url'] ), //For engagement dashboard...
                                    'e_json' => json_encode(array(
                                        'outbound_levels' => $outbound_levels,
                                        'original_tree' => $tree,
                                        'top_c_id' => $intent_id,
                                    )),
                                    'e_type_id' => 7, //Outbound message
                                    'e_b_id' => $b_id, //If set...
                                    'e_r_id' => $r_id, //If set...
                                    'e_i_id' => $i['i_id'], //The message that is being dripped
                                    'e_fb_page_id' => $botkey,
                                ));
                            }
                        }
                    }
                }
            }
        }
    }
    
    
    //Dispatch all Instant Messages, their engagements have already been logged:
    $CI->Facebook_model->batch_messages($botkey, $recipient_fb_psid, $instant_messages, $notification_type);
    //TODO check to make sure this matches the total number of logged engagements?
    
    
    //Successful:
    return array(
        'status' => 1,
        'message' => 'Sent '.count($instant_messages).' instant messages and scheduled '.$drip_count.' drip messages',
        //Extra field for success only:
        'stats' => array(
            'instant' => count($instant_messages),
            'drip' => $drip_count,
        ),
    );
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





function echo_us($us_data){
    echo status_bible('us',$us_data['us_status']);
    $points = round($us_data['us_time_estimate']*60*$us_data['us_on_time_score']);
    echo '<div style="margin:15px 0 10px;"><b>'.( $points>0 ? 'Congratulations! You earned '.$points.' points' : 'You did not earn any points' ).'</b> for completing this '.echo_time($us_data['us_time_estimate'],1).'task '.( $us_data['us_on_time_score']==0 ? 'really late' : ( $us_data['us_on_time_score']==1 ? 'on-time' : 'a little late' ) ).' on '.time_format($us_data['us_timestamp']).'.</div>';
    echo '<div style="margin-bottom:10px;">Your Comments: '.( strlen($us_data['us_student_notes'])>0 ? nl2br($us_data['us_student_notes']) : 'None' ).'</div>';
    echo '<p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Anything changed? Simply share your task updates over <a href="javascript:close_webview();">MenchBot</a>.</p>';
}


function echo_facebook_pixel($r_fb_pixel_id){
    return "<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '".$r_fb_pixel_id."');
fbq('track', 'PageView');
</script>
<noscript><img height=\"1\" width=\"1\" style=\"display:none\" src=\"https://www.facebook.com/tr?id=".$r_fb_pixel_id."&ev=PageView&noscript=1\" /></noscript>
<!-- End Facebook Pixel Code -->";
}


function bigintval($value) {
    $value = trim($value);
    if (ctype_digit($value)) {
        return $value;
    }
    $value = preg_replace("/[^0-9](.*)$/", '', $value);
    if (ctype_digit($value)) {
        return $value;
    }
    return 0;
}


































