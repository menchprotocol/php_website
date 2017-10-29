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





function extract_level($b,$c_id){
    
    $CI =& get_instance();
    //This is what we shall return:
    $view_data = array(
        'pid' => $c_id, //To be deprecated at some point...
        'c_id' => $c_id,
        'bootcamp' => $b,
        'i_messages' => $CI->Db_model->i_fetch(array(
            'i_status' => 1,
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
                'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
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
                        'anchor' => ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'],
                    ),
                );
                $view_data['breadcrumb_p'] = array(
                    array(
                        'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_c_id'],
                        'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
                    ),
                    array(
                        'link' => null,
                        'anchor' => ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'],
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
                                'anchor' => ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'],
                            ),
                            array(
                                'link' => null,
                                'anchor' => 'Task #'.$task['cr_outbound_rank'].' '.$task['c_objective'],
                            ),
                        );
                        $view_data['breadcrumb_p'] = array(
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_c_id'],
                                'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
                            ),
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$sprint['c_id'],
                                'anchor' => ucwords($b['b_sprint_unit']).' #'.$sprint['cr_outbound_rank'].' '.$sprint['c_objective'],
                            ),
                            array(
                                'link' => null,
                                'anchor' => 'Task #'.$task['cr_outbound_rank'].' '.$task['c_objective'],
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
        return '<div class="yt-container"><iframe src="//www.youtube.com/embed/'.one_two_explode('youtube.com/watch?v=','&',$video_url).'" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';
    } else {
        //This is a direct video URL:
        return '<video width="100%" onclick="this.play()" controls><source src="'.$video_url.'" type="video/mp4">Your browser does not support the video tag.</video>';
    }
}



function echo_i($i,$first_name=null){
    
    echo '<div class="i_content">';
    if($i['i_media_type']=='text'){
        
        echo '<div>'.( $first_name ? str_replace('{first_name}', $first_name, $i['i_message']) : $i['i_message'] ).'</div>';
        if(strlen($i['i_url'])>0){
            $CI =& get_instance();
            $website = $CI->config->item('website');
            $tip_url = $website['url'].'tip/'.$i['i_id'];
            echo '<div><a href="'.$tip_url.'" target="_blank">'.$tip_url.'</a></div>';
        }
        
    } else {
        
        echo '<div>'.format_e_message('/attach '.$i['i_media_type'].':'.$i['i_url']).'</div>';
        
    }
    
    echo '</div>';
}

function make_links_clickable($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1"><u>$1</u></a>', $text);
}

function echo_message($i){
	//Fetch current Challenge:
    $CI =& get_instance();
    $i_media_type_names = $CI->config->item('i_media_type_names');
    $i_dispatch_minutes = $CI->config->item('i_dispatch_minutes');
    
	echo '<div class="list-group-item is_sortable" id="ul-nav-'.$i['i_id'].'" iid="'.$i['i_id'].'">';
	echo '<div>';
	
	    //Type & Delivery Method:
	echo '<div>'.$i_media_type_names[$i['i_media_type']].'</div>';
	    
	    echo '<div class="edit-off">';
	    echo echo_i($i);
    	echo '</div>';
    	
    	
    	if($i['i_media_type']=='text'){
    	    echo '<textarea name="i_message" class="edit-on">'.$i['i_message'].'</textarea>';
    	}
    	echo '<input type="url" name="i_url" placeholder="URL" class="form-control edit-on" value="'.$i['i_url'].'">';
    	
        //Editing menu:
        echo '<ul class="msg-nav">';
		    //echo '<li class="edit-off"><a href="javascript:msg_start_edit('.$i['i_id'].');"><i class="fa fa-pencil"></i> Edit</a></li>';
		    //echo '<li class="edit-off"><i class="fa fa-clock-o"></i> 4s Ago</li>';
        echo '<li>'.( isset($i_dispatch_minutes['week'][$i['i_dispatch_minutes']]) ? $i_dispatch_minutes['week'][$i['i_dispatch_minutes']] : $i_dispatch_minutes['day'][$i['i_dispatch_minutes']] ).'</li>';
            echo '<li class="edit-on"><a href="javascript:msg_save_edit('.$i['i_id'].');"><i class="fa fa-check"></i> Save</a></li>';
            echo '<li class="edit-on"><a href="javascript:msg_cancel_edit('.$i['i_id'].');"><i class="fa fa-times"></i></a></li>';
		    echo '<li class="edit-updates"></li>';
		    //echo '<li class="pull-right">'.status_bible('i',$i['i_status'],1,'left').'</a></li>'; //Not editable so no reason to show for now!
		    echo '<li class="pull-right" data-toggle="tooltip" title="Delete Tip" data-placement="left"><a href="javascript:media_delete('.$i['i_id'].');"><i class="fa fa-trash"></i></a></li>';
		    //echo '<li class="pull-right" data-toggle="tooltip" title="Drag Up/Down to Sort" data-placement="left"><i class="fa fa-sort"></i></li>';
		    echo '</ul>';
	    
    echo '</div>';
    echo '</div>';
}

function echo_time($c_time_estimate,$show_icon=1){
    if($c_time_estimate>0){
        $ui = '<span class="title-sub" data-toggle="tooltip" title="Estimated Completion Time">'.( $show_icon ? '<i class="fa fa-clock-o" aria-hidden="true"></i>' : '');
        if($c_time_estimate<1){
            //Minutes:
            $ui .= round($c_time_estimate*60).' Minutes';
        } else {
            //Hours:
            $ui .= round($c_time_estimate,1).' Hour'.(round($c_time_estimate,1)==1?'':'s');
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
            $ui .= '<i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Team Member listed on the Landing Page"></i>';
        } else {
            $ui .= '<i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Team Member NOT listed on the Landing Page"></i>';
        }
        
        $ui .= ' <span class="srt-admins"></span>'; //For the status of sorting
    
    $ui .= '</li>';
    return $ui;
}


//This is used for My/Actionplan display:
function echo_c($b,$c,$level,$us_data=null,$sprint_index=null){
    /* 
     * $b = Bootcamp object
     * $c = Intent object
     * $level Legend:
     *    1 = Top level Action plan
     *    2 = Action Day/Week
     *    3 = Task
     * 
     * * */

    //Calculate deadlines if level 2 action plan items to see which one to show!
    $unlocked_action_plan = false;
    if($level==2){
        //Do some time calculations for the point system:
        $open_date = strtotime(time_format($b['r_start_date'],2,(calculate_duration($b,($sprint_index-1)))))+($sprint_index==1?(intval($b['r_start_time_mins'])*60):0);
        $unlocked_action_plan = ( time() >= $open_date );
    }
    

    $show_a = true; //Most cases
    //Left content
    if($level==0){
        $ui = '<a href="/my/actionplan/'.$b['b_id'].'/'.$c['c_id'].'" class="list-group-item">';
        $ui .= '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> ';
    } elseif($level==3 || $unlocked_action_plan){
        $ui = '<a href="/my/actionplan/'.$b['b_id'].'/'.$c['c_id'].'" class="list-group-item">';
        
        if(isset($us_data[$c['c_id']])){
            $ui .= status_bible('us',$us_data[$c['c_id']]['us_status'],1).' ';
        } else {
            $ui .= '<i class="fa fa-circle-thin initial" aria-hidden="true"></i> ';
        }
        
        
        //if($c['cr_outbound_rank']<=1){
        //$ui .= '<i class="fa fa-check-circle initial" aria-hidden="true"></i> ';
        //}
        
    } else {
        $show_a = false; //Not here, its locked
        $ui = '<li class="list-group-item">';
        $ui .= '<i class="fa fa-lock initial" aria-hidden="true"></i> ';
    }
    
    if($level>0){
        $ui .= ( $level>=2 ? ( $level==2 ? '<span class="inline-level">'.strtoupper(substr($b['b_sprint_unit'],0,1)).$c['cr_outbound_rank'].'</span>' : '' ) : '' );
    }
    
    $ui .= $c['c_objective'].' ';
    
    
    
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
    
    //TODO Need to somehow fetch cohorts in here...
    //$ui .= '<span class="title-sub"><i class="fa fa-calendar" aria-hidden="true"></i>'.time_format($admission['r_start_date'],5,calculate_duration($b,$c['cr_outbound_rank'])).'</span>';
    $ui .= '</span>';
    
    $ui .= ($show_a ? '</a>' : '</li>');
    return $ui;
}


function echo_cr($b_id,$intent,$direction,$level=0,$b_sprint_unit){
    $CI =& get_instance();
	if($direction=='outbound'){
	    
	    $ui = '<a id="cr_'.$intent['cr_id'].'" data-link-id="'.$intent['cr_id'].'" href="/console/'.$b_id.'/actionplan/'.$intent['c_id'].'" class="list-group-item is_sortable">';
	        //Right content
    	    $ui .= '<span class="pull-right">';

    	    $ui .= '<i class="fa fa-sort" data-toggle="tooltip" title="Drag Up/Down to Sort" data-placement="left" aria-hidden="true"></i> &nbsp;';
    	    
    	    $ui .= '<i class="fa fa-trash" onclick="intent_unlink('.$intent['cr_id'].',\''.str_replace('\'','',str_replace('"','',$intent['c_objective'])).'\');" data-toggle="tooltip" title="Remove from Action Plan" data-placement="left"></i> &nbsp;';
    	    
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
    	    $ui .= ( $level>=2 ? '<span class="inline-level">'.( $level==2 ? ucwords($b_sprint_unit) : 'Task' ).' #'.$intent['cr_outbound_rank'].'</span>' : '' );
    	    $ui .= $intent['c_objective'].' ';
  
    	    
    	    //Meta data & stats:
    	    if($level==2 && isset($intent['c__child_intents']) && count($intent['c__child_intents'])>0){
    	        //This sprint has Assignments:
    	        $ui .= '<span class="title-sub" data-toggle="tooltip" title="Number of Tasks"><i class="fa fa-check-square" aria-hidden="true"></i>'.count($intent['c__child_intents']).'</span>';
    	    }
    	    if(isset($intent['c__tip_count']) && $intent['c__tip_count']>0){
    	        $ui .= '<span class="title-sub" data-toggle="tooltip" title="Number of Tips"><i class="fa fa-lightbulb-o" aria-hidden="true"></i>'.$intent['c__tip_count'].'</span>';
    	    }
    	    if(strlen($intent['c_todo_overview'])>0){
    	        $ui .= '<i class="fa fa-binoculars title-sub" aria-hidden="true" data-toggle="tooltip" title="Has Overview"></i>';
    	    }
    	    if($level==2 && isset($intent['c__estimated_hours'])){
    	        $ui .= echo_time($intent['c__estimated_hours'],1);
    	    } elseif($level==3 && isset($intent['c_time_estimate'])){
    	        $ui .= echo_time($intent['c_time_estimate'],1);
    	    }
    	    $ui .= ' <span class="srt-'.$direction.'"></span>'; //For the status of sorting
    	    
	    $ui .= '</a>';
	    return $ui;
	    
	} else {
	    //Not really being used for now...
	    return '<a id="cr_'.$intent['cr_id'].'" data-link-id="'.$intent['cr_id'].'" href="/console/'.$b_id.'/actionplan/'.$intent['c_id'].'" class="list-group-item"><span class="pull-left" style="margin-right:5px;"><span class="label label-default"><i class="fa fa-chevron-left" aria-hidden="true"></i></span></span><span class="pull-right"><i class="fa fa-chain-broken" onclick="intent_unlink('.$intent['cr_id'].',\''.str_replace('\'','',str_replace('"','',$intent['c_objective'])).'\');" data-toggle="tooltip" title="Unlink this reference." data-placement="left"></i></span> '.$intent['c_objective'].' '.echo_time($intent['c_time_estimate']).'</a>';
	}
}

function echo_json($array){
    header('Content-Type: application/json');
    echo json_encode($array);
}

function echo_users($users){
	foreach($users as $i=>$user){
		if($i>0){
			echo ', ';
		}
		echo '<a href="/user/'.$user['u_url_key'].'">@'.$user['u_url_key'].'</a>';
	}
}


function date_is_past($date){
    return ((strtotime($date)-(24*3600))<strtotime(date("F j, Y")));
}

function calculate_bootcamp_status($b){
    
    $CI =& get_instance();
    //A function used on the dashboard to indicate what is left before launching the bootcamp
    $progress_possible = 0; //Total points of progress
    $progress_gained = 0; //Points granted for completion
    $call_to_action = array();
    
    //Now we will check in order that we would display call to actions:
    $task_count = 0;
    foreach($b['c__child_intents'] as $c){
        
        if($c['c_status']<0){
            continue;
        }
        
        //Clear the intent checker:
        unset($c_missing);
        $c_missing = array();
        
        /* *****************************
         *  Action Plan
         *******************************/
        
        
        //c_todo_overview
        $to_gain = 10;
        $progress_possible += $to_gain;
        if(strlen($c['c_todo_overview'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($c_missing,'[Overview]');
        }
        
        //c_time_estimate
        if(!isset($c['c__child_intents']) || count($c['c__child_intents'])<=0){
            $to_gain = 5;
            $progress_possible += $to_gain;
            if($c['c_time_estimate']>0){
                $progress_gained += $to_gain;
            } else {
                array_push($c_missing,'[Time Estimate OR Sub-tasks]');
            }
        }
        
        $sprint_name = ucwords($b['b_sprint_unit']).' #'.$c['cr_outbound_rank'].' ';
        //Did we have anything?
        if(count($c_missing)>0){
            array_push($call_to_action,'Add <b>'.join('</b> & <b>',$c_missing).'</b> to <a href="/console/'.$b['b_id'].'/actionplan/'.$c['c_id'].'#details"><u>'.$sprint_name.$c['c_objective'].'</u></a>');
        }
        
        
        if(isset($c['c__child_intents']) && count($c['c__child_intents'])>0){
            //Also do the count for these:
            foreach($c['c__child_intents'] as $c2){
                
                //Clear the intent checker:
                unset($c_missing);
                $c_missing = array();
                
                
                //c_todo_overview
                $to_gain = 10;
                $progress_possible += $to_gain;
                if(strlen($c2['c_todo_overview'])>0){
                    $progress_gained += $to_gain;
                } else {
                    array_push($c_missing,'[Overview]');
                }
                
                //c_time_estimate
                $to_gain = 5;
                $progress_possible += $to_gain;
                if($c2['c_time_estimate']>0){
                    $progress_gained += $to_gain;
                } else {
                    array_push($c_missing,'[Time Estimate]');
                }
                
                //Did we have anything?
                if(count($c_missing)>0){
                    array_push($call_to_action,'Add <b>'.join('</b> & <b>',$c_missing).'</b> to <a href="/console/'.$b['b_id'].'/actionplan/'.$c2['c_id'].'#details"><u>'.$sprint_name.'Task #'.$c2['cr_outbound_rank'].' '.$c2['c_objective'].'</u></a>');
                }
                
                //Increase count:
                $task_count++;
            }
        }
    }
    
    
    //require some tasks
    $to_gain = 60;
    $progress_possible += $to_gain;
    $required_tasks = 3;
    if($task_count>=$required_tasks){
        $progress_gained += $to_gain;
    } else {
        $progress_gained += ($task_count/$required_tasks)*$to_gain;
        array_push($call_to_action,'Add <b>[At least '.$required_tasks.' Tasks]</b>'.($task_count>0?' ('.($required_tasks-$task_count).' more)':'').' to any '.$b['b_sprint_unit'].' of your <a href="/console/'.$b['b_id'].'/actionplan"><u>Action Plan</u></a>');
    }
    
    
    //require some tips
    $to_gain = 15;
    $required_tips = 3;
    $progress_possible += $to_gain;
    if($b['c__tip_count']>=$required_tips){
        $progress_gained += $to_gain;
    } else {
        $progress_gained += ($b['c__tip_count']/$required_tips)*$to_gain;
        array_push($call_to_action,'Add <b>[At least '.$required_tips.' Tips]</b>'.($b['c__tip_count']>0?' ('.($required_tips-$b['c__tip_count']).' more)':'').' to any task in your <a href="/console/'.$b['b_id'].'/actionplan"><u>Action Plan</u></a>');
    }
    
    
    /* *****************************
     *  Cohorts
     *******************************/
    
    //Let's see if we can find a drafting or published cohort:
    $next_cohort = null;
    if(isset($b['c__cohorts']) && count($b['c__cohorts'])>0){
        foreach($b['c__cohorts'] as $cohort){
            if($cohort['r_status']<=1 && $cohort['r_status']>=0 && !date_is_past($cohort['r_start_date'])){
                $next_cohort = $cohort;
                break;
            }
        }
    }
    
    //r_max_students
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($next_cohort){
        if(strlen($next_cohort['r_max_students'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Max Students]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //r_prerequisites
    $to_gain = 10;
    $progress_possible += $to_gain;
    $default_cohort_prerequisites = $CI->config->item('default_cohort_prerequisites');
    if($next_cohort){
        if(strlen($next_cohort['r_prerequisites'])>0 && !($next_cohort['r_prerequisites']=='<ol><li>'.join('</li><li>',$default_cohort_prerequisites).'</li></ol>')){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Modify <b>[Prerequisites]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //r_application_questions
    $to_gain = 10;
    $progress_possible += $to_gain;
    $default_cohort_questions = $CI->config->item('default_cohort_questions');
    if($next_cohort){
        if(strlen($next_cohort['r_application_questions'])>0 && !($next_cohort['r_application_questions']=='<ol><li>'.join('</li><li>',$default_cohort_questions).'</li></ol>')){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Modify <b>[Application Questions]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //r_response_time_hours
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($next_cohort){
        if(strlen($next_cohort['r_response_time_hours'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Chat Response Time]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'#support"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //r_facebook_group_id
    $to_gain = 15;
    $progress_possible += $to_gain;
    if($next_cohort){
        if(strlen($next_cohort['r_facebook_group_id'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Add a <b>[Facebook Group]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'#support"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //r_weekly_1on1s
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($next_cohort){
        if(strlen($next_cohort['r_weekly_1on1s'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[1-on-1 Mentorship Level]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'#support"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //r_live_office_hours
    if($next_cohort){
        $to_gain = 5;
        $progress_possible += $to_gain;
        if((strlen($next_cohort['r_live_office_hours'])<=0) || (strlen($next_cohort['r_live_office_hours'])>0 && strlen($next_cohort['r_office_hour_instructions'])>0)){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Office Hours: Contact Method]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'#support"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //r_usd_price
    $to_gain = 20;
    $progress_possible += $to_gain;
    if($next_cohort){
        if(strlen($next_cohort['r_usd_price'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Admission Price]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'#pricing"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //r_cancellation_policy
    $to_gain = 10;
    $progress_possible += $to_gain;
    if($next_cohort){
        if($next_cohort['r_usd_price']==0 || strlen($next_cohort['r_usd_price'])==0 || strlen($next_cohort['r_cancellation_policy'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Set <b>[Refund Policy]</b> for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'#pricing"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }    
    
    //r_status
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($next_cohort){
        if($next_cohort['r_status']==1){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Change <b>[Cohort Status]</b> to '.status_bible('r',1).' for <a href="/console/'.$b['b_id'].'/cohorts/'.$next_cohort['r_id'].'#settings"><u>'.time_format($next_cohort['r_start_date'],4).' Cohort</u></a>');
        }
    }
    
    //Did we NOT have a next cohort?
    if(!$next_cohort){
        //Missing cohort all together!
        array_push($call_to_action,'Create <b>[At least 1 Cohort]</b> in <a href="/console/'.$b['b_id'].'/cohorts"><u>Cohorts</u></a>');
    }
    
    
    
    /* *******************************
     *  Leader profile (for them only)
     *********************************/
    $udata = $CI->session->userdata('user');
    if(isset($b['b__admins']) && count($b['b__admins'])>0 && $b['b__admins'][0]['u_id']==$udata['u_id']){
        
        //Set variable short hand:
        $bl = $b['b__admins'][0];
        
        //u_phone
        $to_gain = 5;
        $progress_possible += $to_gain;
        if(strlen($bl['u_phone'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Add <b>[Phone Number]</b> (Private) to <a href="/console/account"><u>My Account</u></a>');
        }
        
        //u_image_url
        $to_gain = 10;
        $progress_possible += $to_gain;
        if(strlen($bl['u_image_url'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Add <b>[Profile Picture URL]</b> to <a href="/console/account"><u>My Account</u></a>');
        }
        
        //u_country_code && u_current_city
        $to_gain = 30;
        $progress_possible += $to_gain;
        if(strlen($bl['u_country_code'])>0 && strlen($bl['u_current_city'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Add <b>[Current Country, City & State]</b> to <a href="/console/account"><u>My Account</u></a>');
        }
        
        //u_language
        $to_gain = 30;
        $progress_possible += $to_gain;
        if(strlen($bl['u_language'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Add <b>[Fluent Languages]</b> to <a href="/console/account"><u>My Account</u></a>');
        }
        
        //u_image_url
        $to_gain = 30;
        $progress_possible += $to_gain;
        if(strlen($bl['u_bio'])>0){
            $progress_gained += $to_gain;
        } else {
            array_push($call_to_action,'Add <b>[Biography]</b> to <a href="/console/account"><u>My Account</u></a>');
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
            array_push($call_to_action,'Link <b>[At least '.$required_social_profiles.' Social Profiles]</b>'.($profile_counter>0?' ('.($required_social_profiles-$profile_counter).' more)':'').' to <a href="/console/account#social"><u>My Account</u></a>');
        }
    }
        
    
    /* *****************************
     *  Bootcamp Settings
     *******************************/
    
    //b_video_url
    $to_gain = 15;
    $progress_possible += $to_gain;
    if(strlen($b['b_video_url'])>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Add <b>[Explainer Video URL]</b> in <a href="/console/'.$b['b_id'].'/settings"><u>Settings</u></a>');
    }
    
    
    //c_todo_overview
    $to_gain = 15;
    $progress_possible += $to_gain;
    if(strlen($b['c_todo_overview'])>0){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Add <b>[Bootcamp Overview]</b> in <a href="/console/'.$b['b_id'].'/settings"><u>Settings</u></a>'.$b['c_todo_bible']);
    }
    
  
    //c_status
    $to_gain = 5;
    $progress_possible += $to_gain;
    if($b['b_status']>=1){
        $progress_gained += $to_gain;
    } else {
        array_push($call_to_action,'Change <b>[Bootcamp Status]</b> to '.status_bible('b',1).' in <a href="/console/'.$b['b_id'].'/settings#settings"><u>Settings</u></a>');
    }
    
    
    $progress_percentage = round($progress_gained/$progress_possible*100);
    if($progress_percentage==100){
        array_push($call_to_action,'Review all settings on your <a href="/bootcamps/'.$b['b_url_key'].'" target="_blank"><u>Bootcamp Landing Page</u> <i class="fa fa-external-link" style="font-size: 0.8em;" aria-hidden="true"></i></a>');
        array_push($call_to_action,'Wait until bootcamp status is updated to '.status_bible('b',3));
        array_push($call_to_action,'Start admissions by notifying your followers via email or social media.');
    }
    return array(
        'stage' => '<i class="fa fa-bullhorn" aria-hidden="true"></i> Launch Progress',
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

function echo_status_dropdown($object,$input_name,$current_status_id,$exclude_ids=array()){
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    ?>
    <input type="hidden" id="<?= $input_name ?>" value="<?= $current_status_id ?>" /> 
    <div class="col-md-3 dropdown">
    	<a href="#" class="btn btn-simple dropdown-toggle border" id="ui_<?= $input_name ?>" data-toggle="dropdown">
        	<?= status_bible($object,$current_status_id,0,'top') ?>
        	<b class="caret"></b>
    	</a>
    	<ul class="dropdown-menu">
    		<?php 
    		$statuses = status_bible($object);
    		$count = 0;
    		foreach($statuses as $intval=>$status){
    		    if($udata['u_status']<$status['u_min_status'] || in_array($intval,$exclude_ids)){
    		        //Do not enable this user to modify to this status:
    		        continue;
    		    }
    		    $count++;
    		    echo '<li><a href="javascript:update_dropdown(\''.$input_name.'\','.$intval.','.$count.');">'.status_bible($object,$intval,0,'top').'</a></li>';
    		    echo '<li style="display:none;" id="'.$input_name.'_'.$count.'">'.status_bible($object,$intval,0,'top').'</li>'; //For UI replacement
    		}
    		?>
    	</ul>
    </div>
    <?php
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

function status_bible($object=null,$status=null,$micro_status=false,$data_placement='bottom'){
	
    //IF you make any changes, make sure to also reflect in the view/console/guides/status_bible.php as well
	$status_index = array(
	    'b' => array(
	        -1 => array(
	            's_name'  => 'Delete',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Bootcamp removed.',
	            'u_min_status'  => 1,
	        ),
	        0 => array(
	            's_name'  => 'Drafting',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Bootcamp not listed in marketplace until published live',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Request To Publish',
	            's_color' => '#8dd08f', //light green
	            's_desc'  => 'Bootcamp submit to be reviewed by Mench team to be published live.',
	            'u_min_status'  => 1,
	        ),
	        2 => array(
    	        's_name'  => 'Published as Unlisted',
    	        's_color' => '#4caf50', //green
    	        's_desc'  => 'Live, ready for admission using the sharable landing page URL.',
    	        'u_min_status'  => 3, //Can only be done by admin
	        ),
	        3 => array(
    	        's_name'  => 'Published to Marketplace',
    	        's_color' => '#4caf50', //green
    	        's_desc'  => 'Bootcamp listed on marketplace and ready for admission.',
    	        'u_min_status'  => 3, //Can only be done by admin
	        ),
	    ),
	    'c' => array(
	        -1 => array(
	            's_name'  => 'Delete',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Task removed.',
	            'u_min_status'  => 999, //Not possible for now.
	        ),
	        0 => array(
	            's_name'  => 'Drafting',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Task being drafted and not accessible by students until published live',
	            'u_min_status'  => 3,
	        ),
	        1 => array(
	            's_name'  => 'Published',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Task is active and accessible by students.',
	            'u_min_status'  => 3,
	        ),
	    ),
	    'r' => array(
    	    -2 => array(
        	    's_name'  => 'Cancel',
        	    's_color' => '#f44336', //red
        	    's_desc'  => 'Cohort was cancelled after it had started.',
        	    'u_min_status'  => 3,
    	    ),
    	    -1 => array(
        	    's_name'  => 'Delete',
        	    's_color' => '#f44336', //red
        	    's_desc'  => 'Cohort removed by bootcamp leader before it was started.',
        	    'u_min_status'  => 2,
    	    ),
	        0 => array(
	            's_name'  => 'Drafting',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Cohort not yet ready for admission as its being modified.',
	            'u_min_status'  => 2,
	        ),
	        1 => array(
    	        's_name'  => 'Open For Admission',
    	        's_color' => '#8dd08f', //light green
    	        's_desc'  => 'Cohort is visible to students and ready for admission.',
    	        'u_min_status'  => 2,
	        ),
	        2 => array(
    	        's_name'  => 'Running',
    	        's_color' => '#4caf50', //green
    	        's_desc'  => 'Cohort has admitted students and is currently running.',
    	        'u_min_status'  => 3,
	        ),
	        3 => array(
    	        's_name'  => 'Finished',
    	        's_color' => '#e91e63', //Rose
    	        's_desc'  => 'Cohort was successfully operated and is now complete.',
    	        'u_min_status'  => 3,
	        ),
	    ),
	    'i' => array(
	        -1 => array(
	            's_name'  => 'Delete',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Tip removed.',
	            'u_min_status'  => 1,
	        ),
	        0 => array(
	            's_name'  => 'Drafting',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'Tip not visible to students until published.',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Published',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Tip ready to be shared with students.',
	            'u_min_status'  => 1,
	        ),
	    ),
	    
	    'cr' => array(
	        -1 => array(
	            's_name'  => 'Delete',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Task link removed.',
	            'u_min_status'  => 1,
	        ),
	        1 => array(
	            's_name'  => 'Published',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Task link is active.',
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
	            's_desc'  => 'View-only access to action plan, cohorts and activity stream. Can answer student inquiries but cannot make any changes.',
	            'u_min_status'  => 1,
	        ),
	        2 => array(
	            's_name'  => 'Instructor',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Can modify action plan and cohort but not settings. Can also answer student inquiries and admit new students.',
	            'u_min_status'  => 1,
	        ),
	        3 => array(
	            's_name'  => 'Lead Instructor',
	            's_color' => '#e91e63', //Rose
	            's_desc'  => 'The bootcamp CEO who is responsible for the bootcamp performance measured based on completion rate.',
	            'u_min_status'  => 1,
	        ),
	    ),
	    
	    'u' => array(
	        -1 => array(
	            's_name'  => 'Delete',
	            's_color' => '#f44336', //red
	            's_desc'  => 'User account deleted and no longer active.',
	            'u_min_status'  => 3, //Only admins can delete user accounts, or the user for their own account
	        ),
	        0 => array(
	            's_name'  => 'Pending',
	            's_color' => '#2f2639', //dark
	            's_desc'  => 'User added by the students but has not yet claimed their account.',
	            'u_min_status'  => 999, //System only
	        ),
	        1 => array(
	            's_name'  => 'Active',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'User active.',
	            'u_min_status'  => 3, //Only admins can downgrade users from a leader status
	        ),
	        2 => array(
	            's_name'  => 'Lead Instructor',
	            's_color' => '#e91e63', //Rose
	            's_desc'  => 'User onboarded as bootcamp leader and can create/manage their own bootcamps.',
	            'u_min_status'  => 3, //Only admins can approve leaders
	        ),
	        3 => array(
	            's_name'  => 'Mench Admin',
	            's_color' => '#e91e63', //Rose
	            's_desc'  => 'User part of Mench team who facilitates bootcamp operations.',
	            'u_min_status'  => 3, //Only admins can create other admins
	        ),
	    ),
	    
	    'us' => array(
    	    -1 => array(
        	    's_name'  => 'Requires Revision',
        	    's_color' => '#f44336', //red
        	    's_desc'  => 'Intructor has reviewed submission and found issues with it that requires student attention.',
        	    'u_min_status'  => 1,
        	    's_mini_icon' => 'fa-exclamation-circle initial',
    	    ),
    	    0 => array(
        	    's_name'  => 'Pending Review',
        	    's_color' => '#2f2639', //dark
        	    's_desc'  => 'Student has submitted thier action plan and is pending instructor review.',
        	    'u_min_status'  => 1,
        	    's_mini_icon' => 'fa-check-circle-o initial',
    	    ),
    	    1 => array(
        	    's_name'  => 'Marked Done',
        	    's_color' => '#2f2639', //dark
        	    's_desc'  => 'This action plan item is complete.',
        	    'u_min_status'  => 1,
        	    's_mini_icon' => 'fa-check-circle initial',
    	    ),
	    ),
	    
	    
	    'ru' => array(
	        
	        //Withrew after course has started:
	        -3 => array(
	            's_name'  => 'Student Dispelled',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Student was dispelled due to misconduct. Refund at the discretion of bootcamp leader.',
	            'u_min_status'  => 1,
	        ),
	        //Withrew prior to course has started:
	        -2 => array(
	            's_name'  => 'Student Withdrew',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Student withdrew from the bootcamp. Refund given based on the cohort refund policy & withdrawal date.',
	            'u_min_status'  => 999, //Only done by Student themselves
	        ),
	        -1 => array(
	            's_name'  => 'Admission Rejected',
	            's_color' => '#f44336', //red
	            's_desc'  => 'Application rejected by bootcamp leader before start date. Students receives a full refund.',
	            'u_min_status'  => 1,
	        ),
	        
	        //Post Application
	        0 => array(
    	        's_name'  => 'Admission Initiated',
    	        's_color' => '#2f2639', //dark
    	        's_desc'  => 'Student has started the application process but has not completed it yet.',
    	        'u_min_status'  => 999, //System insertion only
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
	            's_color' => '#8dd08f', //light green
	            's_desc'  => 'Student has applied, paid in full and is pending application review & approval.',
	            'u_min_status'  => 999, //System insertion only
	        ),
	        
	        
	        /*
	        3 => array(
	            's_name'  => 'Invitation Sent',
	            's_color' => '#8dd08f', //light green
	            's_desc'  => 'Admins have full access to all bootcamp features.',
	            'u_min_status'  => 1,
	        ),
	        */
	        4 => array(
	            's_name'  => 'Bootcamp Student',
	            's_color' => '#4caf50', //green
	            's_desc'  => 'Student admitted making them ready to participate in bootcamp.',
	            'u_min_status'  => 1,
	        ),
	        
	        //Completion
	        5 => array(
	            's_name'  => 'Bootcamp Graduate',
	            's_color' => '#e91e63', //Rose
	            's_desc'  => 'Student completed cohort and executed all their action plan as approved by bootcamp leader.',
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
	    return ( isset($status_index[$object]) ? $status_index[$object] : false );
	} else {
	    $status = intval($status);
	    if(!isset($status_index[$object][$status])){
	        return false;
	    }
		//We have two skins for displaying statuses:
	    if($micro_status){
	        return '<i class="fa '.( isset($status_index[$object][$status]['s_mini_icon']) ? $status_index[$object][$status]['s_mini_icon'] : 'fa-circle' ).'" style="color:'.$status_index[$object][$status]['s_color'].';" data-toggle="tooltip" data-placement="'.$data_placement.'" title="Status is '.$status_index[$object][$status]['s_name'].': '.$status_index[$object][$status]['s_desc'].'" aria-hidden="true"></i>';
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
    return !(!$file_headers || substr_count($file_headers[0],'401')>0 || substr_count($file_headers[0],'402')>0 || substr_count($file_headers[0],'403')>0 || substr_count($file_headers[0],'404')>0);
}

function filter_next_cohort($cohorts){
    if(!$cohorts || count($cohorts)<=0){
        return false;
    }
    
    foreach($cohorts as $cohort){
        if($cohort['r_status']==1 && !date_is_past($cohort['r_start_date'])){
            return $cohort;
            break;
        }
    }
    
    return false;
}

function typeform_url($r_typeform_id,$udata=null){
    $identifier = null;
    if($udata){
        $CI =& get_instance();
        $application_status_salt = $CI->config->item('application_status_salt');
        $identifier = '?u_key='.md5($udata['u_id'].$application_status_salt).'&u_id='.$udata['u_id'].'&u_email='.$udata['u_email'].'&u_fname='.urlencode($udata['u_fname']);
    }
    return 'https://mench.typeform.com/to/'.$r_typeform_id.$identifier;
}

function redirect_message($url,$message){
	$CI =& get_instance();
	$CI->session->set_flashdata('hm', $message);
	header("Location: ".$url);
	return false;
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
            $CI->Db_model->e_create(array(
                'e_message' => 'save_file() Unable to upload file ['.$file_url.'] to internal storage.',
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
    return substr(str_replace(' ','',preg_replace("/[^a-zA-Z0-9]+/", "", trim($text))),0,30);
}

function clean_urlkey($text){
    return str_replace(' ','',preg_replace("/[^a-zA-Z0\-]+/", "", str_replace(' ','-',strtolower($text))));
}

function one_two_explode($one,$two,$content){
	if(substr_count($content, $one)<1){
		return NULL;
	}
	$temp = explode($one,$content,2);
	$temp = explode($two,$temp[1],2);
	return trim($temp[0]);
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
                $e_message .= '<a href="'.$sub_segments[0].'" target="_blank"><img src="'.$sub_segments[0].'" style="max-width:50%" /></a>';
            } elseif($segments[0]=='audio'){
                $e_message .= '<audio controls><source src="'.$sub_segments[0].'" type="audio/mpeg"></audio>';
            } elseif($segments[0]=='video'){
                $e_message .= '<video width="300" controls><source src="'.$sub_segments[0].'" type="video/mp4"></video>';
            } elseif($segments[0]=='file'){
                $e_message .= '<a href="'.$sub_segments[0].'" class="btn btn-primary" target="_blank"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download File</a>';
            }
            
            //Do we have any leftovers after the URL? If so, append:
            if(isset($sub_segments[1])){
                $e_message = ' '.$sub_segments[1];
            }
        }
    }
    $e_message = nl2br($e_message);
    return $e_message;
}


function email_application_url($udata){
    $to_array = array($udata['u_email']);
    $CI =& get_instance();
    $subject = 'mench.co Bootcamp Application';
    $application_status_salt = $CI->config->item('application_status_salt');
    $application_status_url = 'https://mench.co/my/applications?u_key='.md5($udata['u_id'].$application_status_salt).'&u_id='.$udata['u_id'];
    $html_message = null; //Start
    $html_message .= '<div>Hi '.$udata['u_fname'].',</div><br />';
    $html_message .= '<div>For your records, you can access your bootcamp application here:</div><br />';
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
                    return '<a href="'.$website['url'].'console/'.$b_id.'/actionplan/'.$intents[0]['c_id'].'">'.$core_objects[$object]['o_name'].': '.$intents[0]['c_objective'].'</a>';
                } else {
                    return $core_objects[$object]['o_name'].': '.$intents[0]['c_objective'];
                }
            }
        } elseif($object=='b'){
            $bootcamps = $CI->Db_model->c_full_fetch(array(
                'b.b_id' => $id,
            ));
            if(isset($bootcamps[0])){
                return '<a href="'.$website['url'].'console/'.$bootcamps[0]['b_id'].'">'.$core_objects[$object]['o_name'].': '.$bootcamps[0]['c_objective'].'</a>';
            }
        } elseif($object=='u'){
            $matching_users = $CI->Db_model->u_fetch(array(
                'u_id' => $id,
            ));
            if(isset($matching_users[0])){
                return $core_objects[$object]['o_name'].': '.$matching_users[0]['u_fname'].' '.$matching_users[0]['u_lname'].'</a>';
            }
        } elseif($object=='r'){
            $cohorts = $CI->Db_model->r_fetch(array(
                'r.r_id' => $id,
            ));
            if(isset($cohorts[0])){
                if($b_id){
                    //We can return a link:
                    return '<a href="'.$website['url'].'console/'.$b_id.'/cohorts/'.$cohorts[0]['r_id'].'">'.$core_objects[$object]['o_name'].': '.time_format($cohorts[0]['r_start_date'],1).'</a>';
                } else {
                    return $core_objects[$object]['o_name'].': '.time_format($cohorts[0]['r_start_date'],1);
                }
            }
        } elseif($object=='cr'){
            //TODO later...
        } elseif($object=='t'){
            //Transaction
            //TODO later...
        } elseif($object=='i'){
            //TODO later...
        }
    }
    
    //Still here? Return default:
    return $core_objects[$object]['o_name'].' #'.$id;
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





function echo_us($us_data){
    echo status_bible('us',$us_data['us_status']);
    $points = round($us_data['us_time_estimate']*60*$us_data['us_on_time_score']);
    echo '<div style="margin:15px 0 10px;"><b>'.( $points>0 ? 'Congratulations! You earned '.$points.' points' : 'You did not earn any points' ).'</b> for completing this '.echo_time($us_data['us_time_estimate'],1).'task '.( $us_data['us_on_time_score']==0 ? 'really late' : ( $us_data['us_on_time_score']==1 ? 'on-time' : 'a little late' ) ).' on '.time_format($us_data['us_timestamp']).'.</div>';
    echo '<div style="margin-bottom:10px;">Your Comments: '.( strlen($us_data['us_student_notes'])>0 ? nl2br($us_data['us_student_notes']) : 'None' ).'</div>';
    echo '<p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Anything changed? Simply share your task updates over <a href="javascript:close_webview();">MenchBot</a>.</p>';
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


































