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
    return ( ( !is_null($action_plan_item) ? $action_plan_item : $bootcamp['c__milestone_units'] ) * ( $bootcamp['b_sprint_unit']=='week' ? 7 : 1 ) );
}

function calculate_refund($duration_days,$refund_type,$cancellation_policy){
    $CI =& get_instance();
    $refund_policies = $CI->config->item('refund_policies');
    if(isset($refund_policies[$cancellation_policy][$refund_type])){
        return ceil( $duration_days * $refund_policies[$cancellation_policy][$refund_type] );
    } else {
        return 0;
    }
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
    
    //Set universal data:
    $view_data['breadcrumb'] = array(
        array(
            'link' => null,
            'anchor' => 'Action Plan <span id="hb_2272" class="help_button" intent-id="2272"></span>',
        ),
    );


    if($b['c_id']==$c_id){
        
        //Level 1 (The bootcamp itself)
        $view_data['level'] = 1;
        $view_data['sprint_index'] = 0;
        $view_data['sprint_duration_multiplier'] = 0;
        $view_data['next_intent'] = null; //Used in actionplan_ui view for Task Sequence Submission positioning to better understand next move
        $view_data['next_level'] = 0; //Used in actionplan_ui view for Task Sequence Submission positioning to better understand next move
        $view_data['intent'] = $b;
        $view_data['title'] = 'Action Plan | '.$b['c_objective'];
        $view_data['breadcrumb_p'] = array(
            array(
                'link' => null,
                'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
            ),
        );
        return $view_data;
        
    } else {
        
        foreach($b['c__child_intents'] as $sprint_key=>$sprint){
            
            if($sprint['c_id']==$c_id){

                //Found this as level 2:
                $view_data['level'] = 2;
                $view_data['sprint_index'] = $sprint['cr_outbound_rank'];
                $view_data['sprint_duration_multiplier'] = $sprint['c_duration_multiplier'];
                $view_data['next_intent'] = null; //Used in actionplan_ui view for Task Sequence Submission positioning to better understand next move
                $view_data['next_level'] = 0; //Used in actionplan_ui view for Task Sequence Submission positioning to better understand next move
                $view_data['intent'] = $sprint;
                $view_data['title'] = 'Action Plan | '.ucwords($b['b_sprint_unit']).' '.$sprint['cr_outbound_rank'].( $sprint['c_duration_multiplier']>1 ? '-'.($sprint['cr_outbound_rank']+$sprint['c_duration_multiplier']-1) : '' ).': '.$sprint['c_objective'];
                $view_data['breadcrumb_p'] = array(
                    array(
                        'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_c_id'],
                        'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
                    ),
                    array(
                        'link' => null,
                        'anchor' => $core_objects['level_1']['o_icon'].' '.ucwords($b['b_sprint_unit']).' '.$sprint['cr_outbound_rank'].( $sprint['c_duration_multiplier']>1 ? '-'.($sprint['cr_outbound_rank']+$sprint['c_duration_multiplier']-1) : '' ).': '.$sprint['c_objective'],
                    ),
                );
                
                return $view_data;
                
            } else {
                
                //Perhaps a level 3?
                foreach($sprint['c__child_intents'] as $task_key=>$task){
                    if($task['c_id']==$c_id){


                        //Find the next intent:
                        $next_intent = null;
                        $next_level = 0;
                        $next_task_key = $task_key;
                        $next_milestone_key = $sprint_key;

                        while(!$next_intent){

                            $next_task_key += 1;

                            if(!isset($sprint['c__child_intents'][$next_task_key]['c_id'])){

                                //Find the next active milestone since there are no more tasks:
                                while(!$next_intent){

                                    $next_milestone_key += 1;

                                    if(!isset($b['c__child_intents'][$next_milestone_key]['c_id'])){

                                        //Next milestone does not exist, return Bootcamp:
                                        $next_intent = $b;
                                        $next_level = 1;
                                        break;

                                    } elseif($b['c__child_intents'][$next_milestone_key]['c_status']>=1){

                                        $next_intent = $b['c__child_intents'][$next_milestone_key];
                                        $next_level = 2;
                                        break;

                                    }
                                }

                                //Break either way:
                                break;

                            } elseif($sprint['c__child_intents'][$next_task_key]['c_status']>=1){

                                $next_intent = $sprint['c__child_intents'][$next_task_key];
                                $next_level = 3;
                                break;

                            }
                        }


                        //This is level 3:
                        $view_data['level'] = 3;
                        $view_data['sprint_index'] = $sprint['cr_outbound_rank'];
                        $view_data['sprint_duration_multiplier'] = $sprint['c_duration_multiplier'];
                        $view_data['next_intent'] = $next_intent; //Used in actionplan_ui view for Task Sequence Submission positioning to better understand next move
                        $view_data['next_level'] = $next_level; //Used in actionplan_ui view for Task Sequence Submission positioning to better understand next move
                        $view_data['intent'] = $task;
                        $view_data['title'] = 'Action Plan | '.ucwords($b['b_sprint_unit']).' '.$sprint['cr_outbound_rank'].( $sprint['c_duration_multiplier']>1 ? '-'.($sprint['cr_outbound_rank']+$sprint['c_duration_multiplier']-1) : '' ).': '.( $task['c_complete_is_bonus_task']=='t' ? 'Bonus ' : '' ).'Task '.$task['cr_outbound_rank'].': '.$task['c_objective'];
                        $view_data['breadcrumb_p'] = array(
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_c_id'],
                                'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
                            ),
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$sprint['c_id'],
                                'anchor' => $core_objects['level_1']['o_icon'].' '.ucwords($b['b_sprint_unit']).' '.$sprint['cr_outbound_rank'].( $sprint['c_duration_multiplier']>1 ? '-'.($sprint['cr_outbound_rank']+$sprint['c_duration_multiplier']-1) : '' ).': '.$sprint['c_objective'],
                            ),
                            array(
                                'link' => null,
                                'anchor' => ( $task['c_complete_is_bonus_task']=='t' ? '<i class="fa fa-gift" aria-hidden="true"></i> Bonus' : '<i class="fa fa-list-ul" aria-hidden="true"></i>' ).' Task '.$task['cr_outbound_rank'].': '.$task['c_objective'],
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
    return ( $int_time>0 && $int_time<1 ? round($int_time*60).' Minutes' : round($int_time).($int_time==1?' Hour':' Hours') );
}

function detect_embed_video($url,$full_message){

    $embed_code = null;

    //See if $url has a valid embed video in it, and tranform it if it does:
    if(substr_count($url,'youtube.com/watch?v=')==1 || substr_count($url,'youtu.be/')==1){

        //Seems to be youtube:
        if(substr_count($url,'youtube.com/watch?v=')==1){
            $video_id = trim(one_two_explode('youtube.com/watch?v=','&',$url));
        } elseif(substr_count($url,'youtu.be/')==1){
            $video_id = trim(one_two_explode('youtu.be/','?',$url));
        }

        //This should be 11 characters!
        if(strlen($video_id)==11){
            //TODO later we can also define start and end time by adding this: &start=4&end=9
            $embed_code = '<div class="yt-container" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/'.$video_id.'?theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';
        }

    } elseif(substr_count($url,'vimeo.com/')==1){

        //Seems to be Vimeo:
        $video_id = trim(one_two_explode('vimeo.com/','?',$url));

        //This should be an integer!
        if(intval($video_id)==$video_id){
            $embed_code = '<div class="yt-container" style="margin-top:5px;"><iframe src="https://player.vimeo.com/video/'.$video_id.'?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
        }

    } elseif(substr_count($url,'wistia.com/medias/')==1){

        //Seems to be Wistia:
        $video_id = trim(one_two_explode('wistia.com/medias/','?',$url));

        $embed_code = '<script src="https://fast.wistia.com/embed/medias/'.$video_id.'.jsonp" async></script>
<script src="https://fast.wistia.com/assets/external/E-v1.js" async></script>
<div class="wistia_responsive_padding" style="padding:56.25% 0 0 0;position:relative;">
<div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;">
<div class="wistia_embed wistia_async_'.$video_id.' seo=false videoFoam=true" style="height:100%;width:100%">&nbsp;</div>
</div>
</div>';

    }

    if($embed_code){
        return trim(str_replace($url,$embed_code,$full_message));
    } else {
        //Not matched with an embed rule:
        return false;
    }
}

function echo_i($i,$first_name=null,$fb_format=false){
    
    //Must be one of these types:
    if(!isset($i['i_media_type']) || !in_array($i['i_media_type'],array('text','video','audio','image','file'))){
        return false;
    }

    $CI =& get_instance();
    
    if(!$fb_format){
        //HTML format:
        $div_style = ' style="padding:5px 0; font-family: Lato, Helvetica, sans-serif; font-size:16px;"'; //We do this for email templates that do not support CSS and also for internal website...
        $echo_ui = '';
        $echo_ui .= '<div class="i_content">';
    } else {
        //This is what will be returned to be sent via messenger:
        $fb_message = array();
    }
    
    //Proceed to Send Message:
    if($i['i_media_type']=='text' && strlen($i['i_message'])>0){


        //Do we have a {first_name} replacement?
        if($first_name){
            //Tweak the name:
            $i['i_message'] = str_replace('{first_name}', trim($first_name), $i['i_message']);
        }

        //Does this message also have a link?
        if(isset($i['i_url']) && isset($i['i_id']) && intval($i['i_id'])>0 && strlen($i['i_url'])>0){

            $website = $CI->config->item('website');
            $url = $website['url'].'ref/'.$i['i_id'];

            if($fb_format){

                //Messenger format, simply replace the link with a trackable one:
                $i['i_message'] = trim(str_replace($i['i_url'],$url,$i['i_message']));

            } else {

                //Is this a supported embed video URL?
                $embed_html = detect_embed_video($i['i_url'],$i['i_message']);
                if($embed_html){
                    $i['i_message'] = $embed_html;

                    //Facebook Messenger Webview adds an additional button to view full screen:
                    if(isset($i['show_new_window'])){
                        //HTML media format:
                        $i['i_message'] .= '<div><a href="https://mench.co/webview_video/'.$i['i_id'].'" target="_blank">Full Screen in New Window ↗️</a></div>';
                    }

                } else {
                    //HTML format:
                    $i['i_message'] = trim(str_replace($i['i_url'],'<a href="'.$url.'" target="_blank">'.rtrim(str_replace('http://','',str_replace('https://','',str_replace('www.','',$i['i_url']))),'/').'<i class="fa fa-external-link-square" style="font-size: 0.8em; text-decoration:none; padding-left:4px;" aria-hidden="true"></i></a>',$i['i_message']));
                }

            }
        }



        //Does this message include a special command?
        $button_url = null;
        $button_title = null;
        $command = null;

        //Do we have any commands?
        if(substr_count($i['i_message'],'{button}')>0 && isset($i['i_c_id']) && isset($i['e_b_id'])){

            $button_title = 'Open in 🚩Action Plan';
            $button_url = 'https://mench.co/my/actionplan/'.$i['e_b_id'].'/'.$i['i_c_id'];
            $command = '{button}';

        } elseif(substr_count($i['i_message'],'{admissions}')>0 && isset($i['e_recipient_u_id'])) {

            //Fetch salt:
            $application_status_salt = $CI->config->item('application_status_salt');
            //append their My Account Button/URL:
            $button_title = '🎟️ My Bootcamp Application';
            $button_url = 'https://mench.co/my/applications?u_key=' . md5($i['e_recipient_u_id'] . $application_status_salt) . '&u_id=' . $i['e_recipient_u_id'];
            $command = '{admissions}';

        } elseif(substr_count($i['i_message'],'{passwordreset}')>0 && isset($i['e_recipient_u_id'])) {

            //append their My Account Button/URL:
            $timestamp = time();
            $button_title = '👉 Reset Password Here';
            $button_url = 'https://mench.co/my/reset_pass?u_id='.$i['e_recipient_u_id'].'&timestamp='.$timestamp.'&p_hash=' . md5($i['e_recipient_u_id'] . 'p@ssWordR3s3t' . $timestamp);
            $command = '{passwordreset}';

        } elseif(substr_count($i['i_message'],'{menchbot}')>0 && isset($i['e_recipient_u_id'])) {

            $button_url = messenger_activation_url('381488558920384',$i['e_recipient_u_id']);
            if ($button_url) {
                //append their My Account Button/URL:
                $button_title = '🤖 Activate MenchBot';
                $command = '{menchbot}';
            }

        }

        if($command){

            //Append the button to the message:
            if($fb_format){

                //Remove the command from the message:
                $i['i_message'] = trim(str_replace($command, '', $i['i_message']));

                //Return Messenger array:
                $fb_message = array(
                    'attachment' => array(
                        'type' => 'template',
                        'payload' => array(
                            'template_type' => 'button',
                            'text' => $i['i_message'],
                            'buttons' => array(
                                array(
                                    'title' => $button_title,
                                    'type' => 'web_url',
                                    'url' => $button_url,
                                    'webview_height_ratio' => 'tall',
                                    'webview_share_button' => 'hide',
                                    'messenger_extensions' => true,
                                ),
                            ),
                        ),
                    ),
                    'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
                );

            } else {
                //HTML format replaces the button with the command:
                $i['i_message'] = trim(str_replace($command, '<div class="msg"><a href="'.$button_url.'" target="_blank"><b>'.$button_title.'</b></a></div>', $i['i_message']));
                //Return HTML code:
                $echo_ui .= '<div class="msg" '.$div_style.'>'.nl2br($i['i_message']).'</div>';
            }

        } else {

            //Regular without any special commands in it!
            //Now return the template:
            if($fb_format){
                //Messenger array:
                $fb_message = array(
                    'text' => $i['i_message'],
                    'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
                );
            } else {
                //HTML format:
                $echo_ui .= '<div class="msg" '.$div_style.'>'.nl2br($i['i_message']).'</div>';
            }

        }

    } elseif(strlen($i['i_url'])>0) {

        //Valid media file with URL:
        if($fb_format){

            //Do we have this saved in FB Servers?
            if(isset($i['i_fb_att_id']) && $i['i_fb_att_id']>0){
                //Yesss, use that:
                $payload = array(
                    'attachment_id' => $i['i_fb_att_id'],
                );
            } else {
                //No, upload file:
                $payload = array(
                    'url' => $i['i_url'],
                );
            }
            
            //Messenger array:
            $fb_message = array(
                'attachment' => array(
                    'type' => $i['i_media_type'],
                    'payload' => $payload,
                ),
                'metadata' => 'system_logged', //Prevents from duplicate logging via the echo webhook
            );
            
        } else {

            //HTML media format:
            $echo_ui .= '<div '.$div_style.'>'.format_e_message('/attach '.$i['i_media_type'].':'.$i['i_url']).'</div>';

            //Facebook Messenger Webview adds an additional button to view full screen:
            if(isset($i['show_new_window']) && $i['i_media_type']=='video'){
                //HTML media format:
                $echo_ui .= '<div><a href="https://mench.co/webview_video/'.$i['i_id'].'" target="_blank">Full Screen in New Window ↗️</a></div>';
            }

        }
        
    } else {

        //Something was wrong:
        return false;

    }

    //Log engagement if Facebook and return:
    if($fb_format && count($fb_message)>0){

        //Log Outbound Engagement:
        $CI->Db_model->e_create(array(
            'e_initiator_u_id' => ( isset($i['e_initiator_u_id']) ? $i['e_initiator_u_id'] : 0 ),
            'e_recipient_u_id' => ( isset($i['e_recipient_u_id']) ? $i['e_recipient_u_id'] : 0 ),
            'e_message' => ( $i['i_media_type']=='text' ? $i['i_message'] : '/attach '.$i['i_media_type'].':'.$i['i_url'] ), //For engagement dashboard...
            'e_json' => json_encode(array(
                'i' => $i,
                'first_name' => $first_name,
                'fb_message' => $fb_message,
                'tree' => ( isset($i['tree']) ? $i['tree'] : null ),
                'depth' => ( isset($i['depth']) ? $i['depth'] : null ),
            )),
            'e_type_id' => 7, //Outbound message
            'e_r_id' => ( isset($i['e_r_id'])    ? $i['e_r_id']  :0), //If set...
            'e_b_id' => ( isset($i['e_b_id'])    ? $i['e_b_id']  :0), //If set...
            'e_i_id' => ( isset($i['i_id'])      ? $i['i_id']    :0), //The message that is being dripped
            'e_c_id' => ( isset($i['i_c_id'])    ? $i['i_c_id']  :0),
        ));

        //Return Facebook Message to be sent out:
        return $fb_message;

    } elseif(!$fb_format) {

        //This must be HTML if we're still here, return:
        $echo_ui .= '</div>';
        return $echo_ui;

    } else {

        //Should not happen!
        return false;

    }
}



function extract_urls($text,$inverse=false){
    $text = preg_replace('/[[:^print:]]/', ' ', $text); //Replace non-ascii characters with space
    $parts = preg_split('/\s+/', $text);
    $return = array();
    foreach($parts as $part){
        if(!$inverse && filter_var($part, FILTER_VALIDATE_URL)){
            array_push($return,$part);
        } elseif($inverse && !filter_var($part, FILTER_VALIDATE_URL) && strlen($part)>0){
            array_push($return,$part);
        }
    }
    return $return;
}

function echo_uploader($i){
    return '<img src="'.$i['u_image_url'].'" data-toggle="tooltip" title="Last modified by '.$i['u_fname'].' '.$i['u_lname'].' about '.time_diff($i['i_timestamp']).' ago" data-placement="right" />';
}

function echo_message($i,$level=0){

    $echo_ui = '';
    $echo_ui .= '<div class="list-group-item is-msg is_sortable" id="ul-nav-'.$i['i_id'].'" iid="'.$i['i_id'].'">';
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
            $echo_ui .= '<li class="edit-off the_status" style="margin: 0 6px 0 -3px;">'.status_bible('i',$i['i_status'],1,'right').'</li>';
            $echo_ui .= '<li class="i_uploader on-hover">'.echo_uploader($i).'</li>';
            $echo_ui .= '<li class="on-hover" style="margin: 0 0 0 8px;"><i class="fa fa-bars" style="color:#2f2639;"></i></li>';
            $echo_ui .= '<li class="on-hover" style="margin-right: 10px; margin-left: 6px;"><a href="javascript:message_delete('.$i['i_id'].');"><i class="fa fa-trash"></i></a></li>';
            if($i['i_media_type']=='text' || $level<=2){
                $echo_ui .= '<li class="edit-off on-hover" style="margin-left:-4px;"><a href="javascript:msg_start_edit('.$i['i_id'].');"><i class="fa fa-pencil-square-o"></i></a></li>';
            }
            //Right side reverse:
            $echo_ui .= '<li class="pull-right edit-on"><a class="btn btn-primary" href="javascript:message_save_updates('.$i['i_id'].');" style="text-decoration:none; font-weight:bold; padding: 1px 8px 4px;"><i class="fa fa-check" aria-hidden="true"></i></a></li>';
            $echo_ui .= '<li class="pull-right edit-on"><a class="btn btn-hidden" href="javascript:msg_cancel_edit('.$i['i_id'].');"><i class="fa fa-times" style="color:#000"></i></a></li>';
            $echo_ui .= '<li class="pull-right edit-on '.( $level>=3 ? 'hidden' : '' ).'">'.echo_status_dropdown('i','i_status_'.$i['i_id'],$i['i_status'],( $level>=3 ? array(-1,2,4) : array(-1,4) ),'dropup',$level,1).'</li>';
            $echo_ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors
		    $echo_ui .= '</ul>';
	    
    $echo_ui .= '</div>';
    $echo_ui .= '</div>';
    
    return $echo_ui;
}

function echo_time($c_time_estimate,$show_icon=1,$micro=false,$c_id=0,$level=0,$c_status=1){

    if($c_time_estimate>0 || $c_id){
        $ui = '<span class="title-sub" style="text-transform:none !important;">';

        if($c_id){
            $ui .= '<span class="slim-time'.( $level<=2?' hours_level_'.$level:'').( $c_status==1 ? '': ' crossout').'" id="t_estimate_'.$c_id.'" current-hours="'.$c_time_estimate.'">'.( $c_time_estimate==0.05 ? '3m' : '0').'</span>';
            $ui .= ' <i class="fa fa-clock-o" aria-hidden="true"></i>';
        } else {

            if($show_icon){
                $ui .= '<i class="fa fa-clock-o" aria-hidden="true"></i>';
            }
            if($c_time_estimate<1){
                //Minutes:
                $ui .= round($c_time_estimate*60).($micro?'m':' Minutes');
            } else {
                //Hours:
                $ui .= ( round($c_time_estimate,0)==intval($c_time_estimate) ? '' : '~' ).round($c_time_estimate,0).($micro?'h':' Hour'.(round($c_time_estimate,1)==1?'':'s'));
            }
        }

        $ui .= '</span>';
        return $ui;
    }
    //No time:
    return null;
}

function echo_br($admin){
    //Removed for now: href="javascript:ba_open_modify('.$admin['ba_id'].')"
    $ui = '<li id="ba_'.$admin['ba_id'].'" u-id="'.$admin['ba_id'].'" class="list-group-item is_sortable">';
    //Right content
    $ui .= '<span class="pull-right">';
        //$ui .= '<span class="label label-primary" data-toggle="tooltip" data-placement="left" title="Click to modify/revoke access.">';
        //$ui .= '<i class="fa fa-cog" aria-hidden="true"></i>';
        //$ui .= '</span>';
        $ui .= status_bible('ba',$admin['ba_status']);

        //Is this a Mench Adviser?
        if($admin['ba_status']==1){
            //let them know how to get in touch:
            $ui .= ' &nbsp; Get in touch using <img data-toggle="tooltip" data-placement="left" title="MenchBot on Facebook Messenger. Accessible via Console and other devices." src="/img/MessengerIcon.png" class="profile-icon" />';
        }

        //Are they shown on the profile?
        if($admin['ba_team_display']=='t'){
            $ui .= '&nbsp; <i class="fa fa-eye" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Team member who is listed on the Landing Page"></i>';
        } else {
            $ui .= '&nbsp; <i class="fa fa-eye-slash" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Team member who is not listed on the Landing Page"></i>';
        }

    $ui .= '</span> ';

    //Left content
    //$ui .= '<i class="fa fa-sort" aria-hidden="true" style="padding-right:3px;"></i> ';
    $ui .= (strlen($admin['u_image_url'])>4 ? '<img src="'.$admin['u_image_url'].'" class="profile-icon" />' : '<i class="fa fa-user-circle" aria-hidden="true"></i> &nbsp;').$admin['u_fname'].' '.$admin['u_lname'].' &nbsp;';




    //TODO sorting status & updates later on...

    $ui .= '</li>';
    return $ui;
}


//This is used for My/actionplan display for Students:
function echo_c($b,$c,$level,$us_data=null,$sprint_index=null,$previous_item,$next_item){
    /* 
     * $b = Bootcamp object
     * $c = Intent object
     * $level Legend:
     *    2 = Milestone (Day or Week)
     *    3 = Task
     * 
     * * */

    if(!in_array($level,array(2,3))){
        //Show not happen as this function only shows Milestones and Tasks
        return false;
    }


    //Determine some variables for this second Milestone onwards:
    $unlocked_action_plan = false; //Everything is locked by default, unless we see that they have done the previous steps
    $current_is_due = false;
    $next_is_due = false;


    if($level==2){

        //Calculate deadlines if level 2 Milestones items to see which one to show!
        $open_date = strtotime(time_format($b['r_start_date'],2,(($sprint_index-1) * ( $b['b_sprint_unit']=='week' ? 7 : 1 ))))+(intval($b['r_start_time_mins'])*60);
        $next_open_date = strtotime(time_format($b['r_start_date'],2,(($sprint_index+$c['c_duration_multiplier']-1) * ( $b['b_sprint_unit']=='week' ? 7 : 1 ))))+(intval($b['r_start_time_mins'])*60);

        //IF this is the second milestone or more, make sure the previous milestone is done before unlocking this
        $aggregate_status = 1; //We assume it's all done, unless proven otherwise:
        if(!is_null($previous_item) && isset($previous_item['c__child_intents'])){
            foreach($previous_item['c__child_intents'] as $task){
                if($task['c_complete_is_bonus_task']=='t' || $task['c_status']<1){
                    continue;
                }
                if(!isset($us_data[$task['c_id']])){
                    //No submission for this, definitely not done!
                    $aggregate_status = -2; //A special meaning here, which is not found
                    break;
                } elseif($us_data[$task['c_id']]['us_status']<$aggregate_status){
                    $aggregate_status = $us_data[$task['c_id']]['us_status'];
                }
            }
        }

        //Determine key variables:
        $current_is_due = (time() >= $open_date);
        $next_is_due = (time() >= $next_open_date);
        $unlocked_action_plan = ( $current_is_due && $aggregate_status>0 );

    } elseif($level==3){

        //TODO Consider Bonus tasks here with some sort of a loop: $previous_item['c_complete_is_bonus_task']=='t'
        $unlocked_action_plan = ( !isset($previous_item['c_id']) || isset($us_data[$previous_item['c_id']]) );

    }




    //Left content
    if($unlocked_action_plan){

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
        
    } else {

        $ui = '<li class="list-group-item">';
        $ui .= '<i class="fa fa-lock initial" aria-hidden="true"></i> ';

    }

    //Left side starter:
    if($level==2){
        //Show counter:
        $ui .= '<span title="Starts '.date("Y-m-d",$open_date).' and ends '.date("Y-m-d",$next_open_date).'">'.ucwords($b['b_sprint_unit']).' '.$sprint_index.($c['c_duration_multiplier']>1 ? '-'.($sprint_index+$c['c_duration_multiplier']-1) :'').':</span> ';
    } elseif($level==3){
        //Show counter:
        $ui .= '<span>Task '.$sprint_index.':</span> ';
    }

    //Intent title:
    $ui .= $c['c_objective'].' ';


    $ui .= '<span class="sub-stats">';

        //Enable total hours/milestone reporting...
        if($level==2 && isset($c['c__estimated_hours'])){
            $ui .= echo_time($c['c__estimated_hours'],1);
        } elseif($level==3 && isset($c['c_time_estimate'])){
            $ui .= echo_time($c['c_time_estimate'],1);
        }

        if($unlocked_action_plan && $level==2 && isset($c['c__child_intents']) && count($c['c__child_intents'])>0){
            //This sprint has Assignments, count the active ones:
            $active_assinments = 0;
            foreach($c['c__child_intents'] as $task){
                if($task['c_status']>=1){
                    $active_assinments++;
                }
            }
            if($active_assinments>0){
                $ui .= '<span class="title-sub"><i class="fa fa-list-ul" aria-hidden="true"></i>'.$active_assinments.'</span>';
            }
        }

    $ui .= '</span>';


    //The Current focus sign for the focused Task/Milestone:
    if($level==2 && $current_is_due && !$next_is_due){
        $ui .= ' <span class="badge badge-current"><i class="fa fa-hand-o-left" aria-hidden="true"></i> CLASS IS HERE</span>';
    } elseif($level==3 && $c['c_complete_is_bonus_task']=='t'){
        $ui .= ' <span class="badge badge-current"><i class="fa fa-gift" aria-hidden="true"></i> BONUS TASK</span>';
    }

    $ui .= ( $unlocked_action_plan ? '</a>' : '</li>');

    return $ui;
}


function generate_url_key($string){
    $CI =& get_instance();

    //Strip clean:
    $string = preg_replace("/[^A-Za-z0-9]/", '', $string);

    //Check u_url_key to be unique, and if not, add a number and increment:
    $is_duplicate = true;
    $increment = 0;
    while($is_duplicate){
        $matching_users = $CI->Db_model->u_fetch(array(
            'u_url_key' => $string,
        ));
        if(count($matching_users)==0){
            //Yes!
            $is_duplicate = false;
            break;
        } else {
            //This is a duplicate:
            $increment++;
            $string = $string.$increment;
        }
    }

    return $string;
}


function echo_cr($b_id,$intent,$direction,$level=0,$b_sprint_unit,$parent_c_id=0){
    
    $CI =& get_instance();
    $core_objects = $CI->config->item('core_objects');
    $sprint_units = $CI->config->item('sprint_units');
    $udata = $CI->session->userdata('user');
    $clean_title = preg_replace("/[^A-Za-z0-9 ]/", "", $intent['c_objective']);
    $clean_title = (strlen($clean_title)>0 ? $clean_title : 'This Item');
    $intent['c__estimated_hours'] = ( isset($intent['c__estimated_hours']) ? $intent['c__estimated_hours'] : 0 );
    
	if($direction=='outbound'){

	    if($level==1){

            //Bootcamp Outcome:
            $ui = '<div id="obj-title" class="list-group-item">';

        } else {

	        //ATTENTION: DO NOT CHANGE THE ORDER OF data-link-id & node-id AS the sorting logic depends on their exact position to sort!

            //CHANGE WITH CAUTION!

            $ui = '<div id="cr_'.$intent['cr_id'].'" data-link-id="'.$intent['cr_id'].'" node-id="'.$intent['c_id'].'" class="list-group-item '.( $level>2 ? 'is_task_sortable' : 'is_sortable' ).' node_line_'.$intent['c_id'].'">';

        }


        //Right content
        $ui .= '<span class="pull-right maplevel'.$intent['c_id'].'" level-id="'.$level.'" parent-node-id="'.$parent_c_id.'" style="'.( $level<3 ? 'margin-right: 8px;' : '' ).'">';

            if($udata['u_fb_id']>0 && $level==2){
                $ui .= '<a id="simulate_'.$intent['c_id'].'" class="badge badge-primary btn-mls" href="javascript:tree_message('.$intent['c_id'].','.$udata['u_id'].')" data-toggle="tooltip" title="Simulate messages sent to students when '.$core_objects['level_'.($level-1)]['o_name'].' starts" data-placement="top"><i class="fa fa-mobile" aria-hidden="true"></i></a>';
            }

            //Enable total hours/milestone reporting...
            if($level<=2){
                $ui .= echo_time($intent['c__estimated_hours'],1,1, $intent['c_id'], $level, $intent['c_status']);
            } elseif($level==3){
                $ui .= echo_time($intent['c_time_estimate'],1,1, $intent['c_id'], $level, $intent['c_status']);
            }

            $ui .= '<a class="badge badge-primary" onclick="load_modify('.$intent['c_id'].','.$level.')" style="margin-right: -1px;" href="#modify-'.$intent['c_id'].'"><i class="fa fa-pencil-square-o"></i></a> &nbsp;';


        $ui .= '<a href="#messages-'.$intent['c_id'].'" onclick="load_iphone('.$intent['c_id'].','.$level.')" class="badge badge-primary badge-msg"><span id="messages-counter-'.$intent['c_id'].'">'.( isset($intent['c__messages']) ? count($intent['c__messages']) : 0 ).'</span> <i class="fa fa-commenting" aria-hidden="true"></i></a>';
        //Keep an eye out for inner message counter changes:

        $ui .= '</span> ';



        //Sorting & Then Left Content:
        if($level>1) {
            $ui .= '<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;';
        }


        if($level==1){

            //Bootcamp Outcome:
            $ui .= '<span data-toggle="tooltip" title="Bootcamp Outcome which is used to determine student completion rate" data-placement="top"><b id="b_objective" style="font-size: 1.3em;">'.$core_objects['level_'.($level-1)]['o_icon'].'<span class="c_objective_'.$intent['c_id'].'">'.$intent['c_objective'].'</span></b></span>';

            $ui .= '<div class="inline-level" style="margin:9px 0 0 1px; width:100%; clear:both;">';


            $ui .= '<span id="status_holder">'.status_bible('b',$intent['b_status'],0,'right').'</span> &nbsp;&nbsp; ';
            $ui .= '<span style="margin-left:3px; font-weight:500;" data-toggle="tooltip" title="Timelapse between milestones" data-placement="right"><i class="fa fa-flag" aria-hidden="true"></i> <span class="b_sprint_unit2">'.$sprint_units[$b_sprint_unit]['name'].'</span></span> &nbsp; ';
            $ui .= '<div style="font-weight:500; margin-right: 13px;"><i class="fa fa-link" aria-hidden="true" style="font-size: 0.9em; margin-left: 3px; margin-right: 3px;"></i><a href="/'.$intent['b_url_key'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="" class="landing_page_url" data-original-title="Open Landing Page"><span style="color:#555; font-weight:300; padding-left:3px;">https://mench.co/</span><span class="url_anchor">'.$intent['b_url_key'].'</span></a></div>';
            $ui .= '</div>';

        } elseif($level==2){

            //Milestone:
            $ui .= '<span class="inline-level"><a href="javascript:ms_toggle('.$intent['c_id'].');"><i id="handle-'.$intent['c_id'].'" class="fa fa-minus-square-o" aria-hidden="true"></i></a> &nbsp;<span class="inline-level-'.$level.'">'.$core_objects['level_'.($level-1)]['o_icon'].' <span class="b_sprint_unit">'.ucwords($b_sprint_unit).'</span> #0</span></span><b id="title_'.$intent['cr_id'].'" class="cdr_crnt c_objective_'.$intent['c_id'].'" parent-node-id="" outbound-rank="'.$intent['cr_outbound_rank'].'"  current-duration="'.$intent['c_duration_multiplier'].'" current-status="'.$intent['c_status'].'">'.$intent['c_objective'].'</b> ';

        } elseif ($level>=3){

            //Tasks
            $ui .= '<span class="inline-level inline-level-'.$level.'">'.( $intent['c_status']==1 ? $core_objects['level_'.($level-1)]['o_icon'].' #'.$intent['cr_outbound_rank'] : '<b><i class="fa fa-pencil-square" aria-hidden="true"></i> DRAFT</b>' ).'</span><span id="title_'.$intent['cr_id'].'" class="c_objective_'.$intent['c_id'].'" current-status="'.$intent['c_status'].'" outbound-rank="'.$intent['cr_outbound_rank'].'" c_complete_url_required="'.($intent['c_complete_url_required']=='t'?1:0).'"  c_complete_notes_required="'.($intent['c_complete_notes_required']=='t'?1:0).'"  c_complete_is_bonus_task="'.($intent['c_complete_is_bonus_task']=='t'?1:0).'" c_complete_instructions="'.$intent['c_complete_instructions'].'">'.$intent['c_objective'].'</span> <i class="fa fa-gift bonus_task_'.$intent['c_id'].' '.( $intent['c_complete_is_bonus_task']=='t' ? '' : 'hidden').'" title="Bonus Task" data-toggle="tooltip" aria-hidden="true"></i> ';

        }


        //Any tasks?
        if($level==2){

            $ui .= '<div id="list-outbound-'.$intent['c_id'].'" class="list-group task-group" node-id="'.$intent['c_id'].'">';
            //This line enables the in-between list moves to happen for empty lists:
            $ui .= '<div class="is_task_sortable dropin-box" style="height:1px;">&nbsp;</div>';
            if(isset($intent['c__child_intents']) && count($intent['c__child_intents'])>0){
                foreach($intent['c__child_intents'] as $sub_intent){
                    $ui .= echo_cr($b_id,$sub_intent,$direction,($level+1),$b_sprint_unit,$intent['c_id']);
                }
            }

            //Task Input field:
            $ui .= '<div class="list-group-item list_input new-task-input">
        		<div class="input-group">
        			<div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="new_intent('.$intent['c_id'].','.($level+1).');" node-id="'.$intent['c_id'].'"><input type="text" class="form-control autosearch"  maxlength="'.$core_objects['c']['maxlength'].'" id="addnode'.$intent['c_id'].'" placeholder=""></form></div>
        			<span class="input-group-addon" style="padding-right:8px;">
        				<span data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" onclick="new_intent('.$intent['c_id'].','.($level+1).');" class="badge badge-primary pull-right" node-id="'.$intent['c_id'].'" style="cursor:pointer; margin: 13px -6px 1px 13px;">
        					<div><i class="fa fa-plus"></i></div>
        				</span>
        			</span>
        		</div>
        	</div>';

            $ui .= '</div>';
        }


	    $ui .= '</div>';
	    return $ui;
	    
	} else {
	    //Not really being used for now...
	}
}

function echo_json($array){
    /*
    if(isset($array['status']) && $array['status']==0){
        //This is an error, return 400:
        header("HTTP/1.0 400 Bad Request");
    }
    */
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


function gross_mentorship($r_meeting_frequency,$r_meeting_duration,$b_sprint_unit,$c__milestone_units,$is_fancy=true){
    $bootcamp_days = ( $b_sprint_unit=='week' ? 7 : 1 ) * $c__milestone_units;
    
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


function prep_prerequisites($b){
    //Appends system-enforced prerequisites based on Bootcamp settings:
    $pre_req_array = ( strlen($b['b_prerequisites'])>0 ? json_decode($b['b_prerequisites']) : array() );
    if($b['c__estimated_hours']>0){
        array_unshift($pre_req_array, 'Commitment to invest '.echo_hours($b['c__estimated_hours']).' in '.$b['c__milestone_units'].' '.$b['b_sprint_unit'].show_s($b['c__milestone_units']).' (Average '.echo_hours(round($b['c__estimated_hours']/$b['c__milestone_units'])) .' per '. $b['b_sprint_unit'].')');
    }
    return $pre_req_array;
}


function calculate_bootcamp_status($b){
    
    $CI =& get_instance();
    $sprint_units = $CI->config->item('sprint_units');
    //A function used on the dashboard to indicate what is left before launching the bootcamp
    $progress_possible = 0; //Total points of progress
    $progress_gained = 0; //Points granted for completion
    $checklist = array();


    //Check some of the high-priority Action Plan Lists:

    //Skills You Will Gain
    $estimated_minutes = 30;
    $progress_possible += $estimated_minutes;
    $us_status = ( strlen($b['b_transformations'])>0 ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#outcomes',
        'anchor' => '<b>Define Skills You Will Gain</b> in Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));

    //Target Audience
    $estimated_minutes = 30;
    $progress_possible += $estimated_minutes;
    $us_status = ( strlen($b['b_target_audience'])>0 ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#screening',
        'anchor' => '<b>Set Target Audience</b> in Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));

    //Prerequisites
    $default_class_prerequisites = $CI->config->item('default_class_prerequisites');
    $estimated_minutes = 30;
    $progress_possible += $estimated_minutes;
    $us_status = ( strlen($b['b_prerequisites'])>0 && !($b['b_prerequisites']==json_encode($default_class_prerequisites)) ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#screening',
        'anchor' => '<b>'.( strlen($b['b_prerequisites'])>0 ? 'Edit' : 'Set' ).' Prerequisites</b> in Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));










    //Do we have enough Milestones?
    $estimated_minutes = 60;
    $required_milestones = ( $b['b_sprint_unit']=='week' ? 2 : 3 ); //Minimum 3 days or 1 week
    $progress_possible += $estimated_minutes;
    $us_status = ( count($b['c__child_intents'])>=$required_milestones ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : (count($b['c__child_intents'])/$required_milestones)*$estimated_minutes );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan',
        'anchor' => '<b>Add '.$required_milestones.' or more '.$sprint_units[$b['b_sprint_unit']]['name'].' Milestones</b>'.( count($b['c__child_intents'])>0 && !$us_status ?' ('.($required_milestones-count($b['c__child_intents'])).' more)':'').' in Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));
    
    
    //Now check each Milestone and its Task List:
    foreach($b['c__child_intents'] as $milestone_num=>$c){

        if($c['c_status']<0){
            continue; //Don't check unpublished Milestones, which is not even possible for now...
        }
        
        //Prepare key variables:
        $milestone_anchor = ucwords($b['b_sprint_unit']).' #'.$c['cr_outbound_rank'].' ';


        //Milestone On Start Messages
        $estimated_minutes = 15;
        $progress_possible += $estimated_minutes;
        $qualified_messages = 0;
        if(count($c['c__messages'])>0){
            foreach($c['c__messages'] as $i){
                $qualified_messages += ( $i['i_status']==1 ? 1 : 0 );
            }
        }
        $us_status = ( $qualified_messages>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/actionplan#messages-'.$c['c_id'],
            'anchor' => '<b>Add a '.status_bible('i',1).' Message</b> to '.$milestone_anchor.$c['c_objective'],
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
        
        
        //For the MVP we require Task details for 1 Weekly Milestone or 2 Daily Milestones, not more!
        //if(($b['b_sprint_unit']=='week' && $milestone_num>0) || ($b['b_sprint_unit']=='day' && $milestone_num>1)){
            //continue;
        //}
        
        
        //Sub Task List
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $us_status = ( isset($c['c__child_intents']) && count($c['c__child_intents'])>=1 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : (count($c['c__child_intents']))*$estimated_minutes );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/actionplan',
            'anchor' => '<b>Add a Task</b>'.(count($c['c__child_intents'])>0 && !$us_status?' ('.(1-count($c['c__child_intents'])).' more)':'').' to '.$milestone_anchor.$c['c_objective'],
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));

        //Check Tasks:
        if(isset($c['c__child_intents']) && count($c['c__child_intents'])>0){
            foreach($c['c__child_intents'] as $c2){

                //Create task object:
                $task_anchor = $milestone_anchor.'Task #'.$c2['cr_outbound_rank'].' '.$c2['c_objective'];


                //Messages for Tasks:
                $estimated_minutes = 15;
                $progress_possible += $estimated_minutes;
                $qualified_messages = 0;
                if(count($c2['c__messages'])>0){
                    foreach($c2['c__messages'] as $i){
                        $qualified_messages += ( $i['i_status']==1 ? 1 : 0 );
                    }
                }
                $us_status = ( $qualified_messages>0 ? 1 : 0 );
                $progress_gained += ( $us_status ? $estimated_minutes : 0 );
                array_push( $checklist , array(
                    'href' => '/console/'.$b['b_id'].'/actionplan#messages-'.$c2['c_id'],
                    'anchor' => '<b>Add an '.status_bible('i',1).' Message</b> to '.$task_anchor,
                    'us_status' => $us_status,
                    'time_min' => $estimated_minutes,
                ));
            }
        }
    }
    

    //Bootcamp Messages:
    $estimated_minutes = 15;
    $progress_possible += $estimated_minutes;
    $qualified_messages = 0;
    if(count($b['c__messages'])>0){
        foreach($b['c__messages'] as $i){
            $qualified_messages += ( $i['i_status']==1 && ( $i['i_media_type']=='video' || ($i['i_media_type']=='text' && strlen($i['i_url'])>0 && detect_embed_video($i['i_url'],$i['i_message']))) ? 1 : 0 );
        }
    }
    $us_status = ( $qualified_messages ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#messages-'.$b['b_c_id'],
        'anchor' => '<b>Add an '.status_bible('i',1).' Video Message</b> to Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));

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


    //Did we NOT have a next class?
    if(!$focus_class){
        //Missing class all together!
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/classes',
            'anchor' => '<b>Create a Class</b> in Classes',
            'us_status' => 0,
            'time_min' => $estimated_minutes,
        ));
    }


    //r_response_time_hours
    $estimated_minutes = 5;
    $progress_possible += $estimated_minutes;
    if($focus_class){
        $us_status = ( strlen($focus_class['r_response_time_hours'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#support',
            'anchor' => '<b>Set Response Time</b> for '.time_format($focus_class['r_start_date'],4).' Class',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }

    //r_meeting_frequency
    $estimated_minutes = 10;
    $progress_possible += $estimated_minutes;
    if($focus_class){
        $us_status = ( strlen($focus_class['r_meeting_frequency'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#support',
            'anchor' => '<b>Set 1-on-1 Mentorship Sessions</b> for '.time_format($focus_class['r_start_date'],4).' Class',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }

    //r_live_office_hours
    if($focus_class && strlen($focus_class['r_live_office_hours'])>0){
        $estimated_minutes = 5;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($focus_class['r_office_hour_instructions'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#support',
            'anchor' => '<b>Set Weekly group call contact message</b> for '.time_format($focus_class['r_start_date'],4).' Class',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }

    //r_usd_price
    $estimated_minutes = 20;
    $progress_possible += $estimated_minutes;
    if($focus_class){
        $us_status = ( strlen($focus_class['r_usd_price'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#pricing',
            'anchor' => '<b>Set Tuition Rate</b> for '.time_format($focus_class['r_start_date'],4).' Class',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }


    //r_cancellation_policy
    if($focus_class && $focus_class['r_usd_price']>0){
        $estimated_minutes = 10;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($focus_class['r_cancellation_policy'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#pricing',
            'anchor' => '<b>Set Refund Polic</b> for '.time_format($focus_class['r_start_date'],4).' Class',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }

    //r_max_students
    $estimated_minutes = 5;
    $progress_possible += $estimated_minutes;
    if($focus_class){
        $us_status = ( strlen($focus_class['r_max_students'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#admission',
            'anchor' => '<b>Set Max Students</b> for '.time_format($focus_class['r_start_date'],4).' Class',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }

    //r_status
    $estimated_minutes = 5;
    $progress_possible += $estimated_minutes;
    if($focus_class){
        $us_status = ( $focus_class['r_status']==1 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/classes/'.$focus_class['r_id'].'#admission',
            'anchor' => '<b>Set Class Status to '.status_bible('r',1).'</b> for '.time_format($focus_class['r_start_date'],4).' Class',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }


    
    
    /* *******************************
     *  Leader profile (for them only)
     *********************************/
    //This must exist:
    $bl = ( isset($b['b__admins'][0]) ? $b['b__admins'][0] : null );
    if($bl){
        $udata = $CI->session->userdata('user');
        $is_my_account = ( $bl['u_id']==$udata['u_id'] );
        $account_anchor = ( $is_my_account ? 'My Account' : $bl['u_fname'].' '.$bl['u_lname'].'\'s Account' );
        $account_href = ( $is_my_account ? '/console/account' : null );




        //u_phone
        $estimated_minutes = 5;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($bl['u_phone'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#communication' : null ),
            'anchor' => '<b>Set Private Phone Number</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));

        //u_image_url
        $estimated_minutes = 10;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($bl['u_image_url'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => $account_href,
            'anchor' => '<b>Set Picture</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));

        //u_country_code && u_current_city
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($bl['u_country_code'])>0 && strlen($bl['u_current_city'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => $account_href,
            'anchor' => '<b>Set Location</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));


        //u_timezone
        $estimated_minutes = 15;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($bl['u_timezone'])>0 && strlen($bl['u_timezone'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#communication' : null ),
            'anchor' => '<b>Set Timezone</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));

        //u_language
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($bl['u_language'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#communication' : null ),
            'anchor' => '<b>Set Fluent Languages</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));

        //u_bio
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($bl['u_bio'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => $account_href,
            'anchor' => '<b>Set Introductory Message</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));

        //Profile counter:
        $profile_counter = ( strlen($bl['u_website_url'])>0 ? 1 : 0 );
        $profile_counter = 1;
        $u_social_account = $CI->config->item('u_social_account');
        foreach($u_social_account as $sa_key=>$sa){
            $profile_counter += ( strlen($bl[$sa_key])>0 ? 1 : 0 );
        }

        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $required_social_profiles = 3;
        $us_status = ( $profile_counter>=$required_social_profiles ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : ($profile_counter/$required_social_profiles)*$estimated_minutes );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#communication' : null ),
            'anchor' => '<b>Set '.$required_social_profiles.' or more Social Profiles</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));


        //u_paypal_email
        $estimated_minutes = 15;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($bl['u_paypal_email'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#finance' : null ),
            'anchor' => '<b>Set Paypal Email for Payouts</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));


        //u_terms_agreement_time
        $estimated_minutes = 45;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($bl['u_terms_agreement_time'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => ( $account_href ? $account_href.'#finance' : null ),
            'anchor' => '<b>Check Instructor Agreement</b> in '.$account_anchor,
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }



    
    /* *****************************
     *  Bootcamp Settings
     *******************************/


    //Application Questions
    $default_class_questions = $CI->config->item('default_class_questions');
    $estimated_minutes = 30;
    $progress_possible += $estimated_minutes;
    $us_status = ( strlen($b['b_application_questions'])>0 && !($b['b_application_questions']==json_encode($default_class_questions)) ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#screening',
        'anchor' => '<b>'.( strlen($b['b_application_questions'])>0 ? 'Edit' : 'Set' ).' Application Questions</b> in Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));


    //Prizes
    $default_class_prizes = $CI->config->item('default_class_prizes');
    $estimated_minutes = 15;
    $progress_possible += $estimated_minutes;
    $us_status = ( !($b['b_completion_prizes']==json_encode($default_class_prizes)) ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#outcomes',
        'anchor' => '<b>Edit Completion Prizes</b> in Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));
    
    
    //b_status
    $estimated_minutes = 5;
    $progress_possible += $estimated_minutes;
    $us_status = ( $b['b_status']>=1 ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#modify-'.$b['b_c_id'],
        'anchor' => '<b>Set Bootcamp Status to '.status_bible('b',1).'</b> in Settings',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));
    
    
    //Return the final message:
    return array(
        'stage' => '<i class="fa fa-tasks" aria-hidden="true" title="Gained '.$progress_gained.'/'.$progress_possible.' points"></i> Bootcamp Launch Checklist',
        'progress' => round($progress_gained/$progress_possible*100),
        'completion_message' => 'Now that your checklist is complete you can review your <a href="/'.$b['b_url_key'].'" target="_blank"><u>Landing Page</u> <i class="fa fa-external-link-square" style="font-size: 0.8em;" aria-hidden="true"></i></a> to ensure it looks good. Wait until Mench team updates your bootcamp status to '.status_bible('b',2).'. At this time you can launch your bootcamp by inviting your students to join.',
        'check_list' => $checklist,
    );
}



function echo_checklist($href,$anchor,$us_status,$time_min=0){
    
    $ui = '';
    if($href){
        $ui .= '<a href="'.$href.'" class="list-group-item '.($us_status?'checklist-done':'').'">';
        $ui .= '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
    } else {
        $ui .= '<li class="list-group-item '.($us_status?'checklist-done':'').'">';
    }
    
    $ui .= status_bible('us',$us_status,1,'right').' ';
    //Never got around estimating the time of each task, as it seemed a bit arbitrary to do so...
    //$ui .= ( $time_min ? '<span class="est-time" data-toggle="tooltip" data-placement="right" title="Takes about '.$time_min.' minutes to complete"><b>~'.$time_min.'"</b></span>' : '' );
    $ui .= $anchor.' ';
    
    if($href){
        $ui .= '</a>';
    } else {
        $ui .= '</li>';
    }
    return $ui;
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

function show_s($count){
    return ( $count==1?'':'s' );
}


function echo_status_dropdown($object,$input_name,$current_status_id,$exclude_ids=array(),$direction='dropdown',$level=0,$mini=0){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $inner_tooltip = ($direction=='dropup'?'top':'top');

    if(is_array($object)){
        $statuses = $object;
    } else {
        $statuses = status_bible($object,null,false,'bottom');
    }
    $now = status_bible($object,$current_status_id,$mini,$inner_tooltip);
    
    $return_ui = '';
    $return_ui .= '<input type="hidden" id="'.$input_name.'" value="'.$current_status_id.'" />';
    $return_ui .= '<div style="display:inline-block;" class="'.$direction.'">';
    $return_ui .= '<a href="#" style="margin: 0; background-color:#FFF;" class="btn btn-simple dropdown-toggle border" id="ui_'.$input_name.'" data-toggle="dropdown">';
    $return_ui .= ( $now ? $now : 'Select...' );
    $return_ui .= '<b class="caret"></b></a><ul class="dropdown-menu">';

    $count = 0;
    foreach($statuses as $intval=>$status){
        if(isset($status['u_min_status']) && ($udata['u_status']<$status['u_min_status'] || in_array($intval,$exclude_ids))){
            //Do not enable this user to modify to this status:
            continue;
        }
        $count++;
        $return_ui .= '<li><a href="javascript:update_dropdown(\''.$input_name.'\','.$intval.','.$count.');">'.status_bible($object,$intval,0,$inner_tooltip).'</a></li>';
        $return_ui .= '<li style="display:none;" class="'.$input_name.'_'.$intval.'" id="'.$input_name.'_'.$count.'">'.status_bible($object,$intval,$mini,$inner_tooltip).'</li>'; //For UI replacement
    }
    $return_ui .= '</ul></div>';
    return $return_ui;
}

function hourformat($fancy_hour){
    if(substr_count($fancy_hour,'am')>0){
        $fancy_hour = str_replace('am','',$fancy_hour);
        $temp = explode(':',$fancy_hour,2);
        if($temp[0]==12){
            //This is 12M, set to zero:
            $temp[0] = 0;
        }
        return (intval($temp[0]) + ( isset($temp[1]) ? (intval($temp[1])/60) : 0 ));
    } elseif(substr_count($fancy_hour,'pm')>0){
        $fancy_hour = str_replace('pm','',$fancy_hour);
        $temp = explode(':',$fancy_hour,2);
        return (intval($temp[0]) + ( isset($temp[1]) ? (intval($temp[1])/60) : 0 ) + (intval($temp[0])==12?0:12) );
    }
}


function status_bible($object=null,$status=null,$micro_status=false,$data_placement='bottom'){
	
    //IF you make any changes, make sure to also reflect in the view/console/guides/status_bible.php as well
    $CI =& get_instance();
	$status_index = $CI->config->item('object_statuses');
	
	//Return results:
	if(is_null($object)){

		//Everything
	    return $status_index;

	} elseif(is_null($status)){

		//Object Specific
        if(is_array($object) && count($object)>0){
            return $object;
        } else {
            return ( isset($status_index[$object]) ? $status_index[$object] : false );
        }

	} else {

        $status = intval($status);
        if(is_array($object) && count($object)>0){
            $result = $object[$status];
        } else {
            $result = $status_index[$object][$status];
        }

        if(!$result){
            //Could not find matching item
            return false;
        } else {
            //We have two skins for displaying statuses:
            return '<span class="status-label" style="color:#2f2639;" '.(isset($result['s_desc'])?'data-toggle="tooltip" data-placement="'.$data_placement.'" title="'.$result['s_desc'].'" aria-hidden="true"':'').'><i class="fa '.( isset($result['s_mini_icon']) ? $result['s_mini_icon'] : 'fa-circle' ).' initial"></i>'.($micro_status?'':$result['s_name']).'</span>';
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


function auth($min_level,$force_redirect=0,$b_id=0){
	
	$CI =& get_instance();
	$udata = $CI->session->userdata('user');
	
	//Let's start checking various ways we can give user access:
	if(!$min_level && !$b_id){
	    
	    //No minimum level required, grant access:
	    return $udata;
	    
	} elseif(isset($udata['u_id']) && $udata['u_status']>=3){
	    
	    //Always grant access to Super Admins:
	    return $udata;
	    
	} elseif(isset($udata['u_id']) && $b_id){
	    
	    //Fetch bootcamp admins and see if they have access to this:
	    $bootcamp_instructors = $CI->Db_model->ba_fetch(array(
	        'ba.ba_b_id' => $b_id,
	        'ba.ba_status >=' => 1, //Must be an actively assigned instructor
	        'u.u_status >=' => 1, //Must be a user level 1 or higher
	        'u.u_id' => $udata['u_id'],
	    ));
	    
	    if(count($bootcamp_instructors)>0){
	        //Append permissions here:
	        $udata['bootcamp_permissions'] = $bootcamp_instructors[0];
	        //Instructor is part of the bootcamp:
	        return $udata;
	    }
	    
	} elseif(isset($udata['u_id']) && intval($udata['u_status'])>=intval($min_level)){
	    
		//They meet the minimum level requirement:
	    return $udata;
	    
	}
	
	//Still here?!
	//We could not find a reason to give user access, so block them:
	if(!$force_redirect){
	    return false;
	} else {
	    //Block access:
	    redirect_message( ( isset($udata['u_id']) && (intval($udata['u_status'])>=2 || (intval($udata['u_status'])==1 && isset($udata['bootcamp_permissions']))) ? '/console' : '/login?url='.urlencode($_SERVER['REQUEST_URI']) ),'<div class="alert alert-danger maxout" role="alert">'.( isset($udata['u_id']) ? 'Access not authorized.' : 'Session Expired. Login to continue.' ).'</div>');
	}
	
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
        //date_is_past() is key as deadline is last midnight MAX
        if($class['r_status']>=1 && !date_is_past($class['r_start_date']) && (!$r_id || ($r_id==$class['r_id']))){
            return $class;
            break;
        }
    }
    
    return false;
}

function typeform_url($typeform_id){
    return 'https://mench.typeform.com/to/'.$typeform_id;
}


function echo_chat($botkey,$unread_notifications_count=0){
    //$CI =& get_instance();
    //$udata = $CI->session->userdata('user');
    //$bot_activation_salt = $CI->config->item('bot_activation_salt');
    //This is for the instructor bot:
    return '<div class="fb-customerchat" minimized="'.( $unread_notifications_count ? 'false' : 'true' ).'" page_id="'.$botkey.'"></div>';
    //ref="'.( isset($udata['u_id']) && strlen($udata['u_fb_id'])<4 ? 'msgact_'.$udata['u_id'].'_'.substr(md5($udata['u_id'].$bot_activation_salt),0,8) : '').'"
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

function redirect_message($url,$message=null){
    if($message){
        $CI =& get_instance();
        $CI->session->set_flashdata('hm', $message);
    }
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
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_POST, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	if(is_dev()){
	    //SSL does not work on my local PC.
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	}
	return curl_exec($ch);
}

function boost_power(){
	ini_set('memory_limit', '-1');
	ini_set('max_execution_time', 600);
}

function echo_rank($rank){
    if($rank==1){
        return '🥇';
    } elseif($rank==2){
        return '🥈';
    } elseif($rank==3){
        return '🥉';
    } else {
        return $rank;
    }
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
    
    $timestamp = ( is_numeric($t) ? $t : strtotime(substr($t,0,19)) ) + ($plus_days*24*3600) + ($plus_days>0 ? (12*3600) : 0); //Added this last part to consider the end of days for dates
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

function message_validation($i_status,$i_message,$i_media_type=null /*Only set for editing*/){
    $CI =& get_instance();
    $message_max = $CI->config->item('message_max');
    $check_urls = (!$i_media_type || $i_media_type=='text');
    if($check_urls){
        $urls = extract_urls($i_message);
    }

    if(!isset($i_status) || !(intval($i_status)==$i_status)){
        return array(
            'status' => 0,
            'message' => 'Missing Status',
        );
    } elseif(!isset($i_message) || strlen($i_message)<=0){
        return array(
            'status' => 0,
            'message' => 'Missing Message',
        );
    } elseif($check_urls && count($urls)>1){
        return array(
            'status' => 0,
            'message' => 'Max 1 URL per Message',
        );
    } elseif(substr_count($i_message,'{first_name}')>1){
        return array(
            'status' => 0,
            'message' => '{first_name} can be used only once',
        );
    } elseif(strlen($i_message)>$message_max){
        return array(
            'status' => 0,
            'message' => 'Max is '.$message_max.' Characters',
        );
    } elseif($i_message!=strip_tags($i_message)){
        return array(
            'status' => 0,
            'message' => 'HTML Code is not allowed',
        );
    } elseif(!preg_match('//u', $i_message)){
        //Log engagement for this:
        return array(
            'status' => 0,
            'message' => 'Message must be UTF8',
        );
    } else {
        return array(
            'status' => 1,
            'urls' => ( $check_urls ? $urls : null ),
        );
    }
}

function time_diff($t,$second_time=null){
    if(!$second_time){
        $second_time = time(); //Now
    } else {
        $second_time = strtotime(substr($second_time,0,19));
    }

    $time = $second_time - ( is_int($t) ? $t : strtotime(substr($t,0,19)) ); // to get the time since that moment
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
		if ($time<$unit && $unit>1) continue;
		if($unit>=2592000 && fmod(($time / $unit),1)>=0.33 && fmod(($time / $unit),1)<=.67){
		    $numberOfUnits = number_format(($time / $unit),1);
		} else {
		    $numberOfUnits = number_format(($time / $unit),0);
		}

		if($numberOfUnits<1 && $unit==1){
            $numberOfUnits = 1; //Change "0 seconds" to "1 second"
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
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank"><u>$1</u></a>', $text);
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
                $e_message .= '<img src="'.$sub_segments[0].'" style="max-width:100%" />';
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
                    return '<a href="'.$website['url'].'console/'.$b_id.'/actionplan#modify-'.$intents[0]['c_id'].'">'.$intents[0]['c_objective'].'</a>';
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

            /*
            //Fetch message conent:
            $matching_messages = $CI->Db_model->i_fetch(array(
                'i_id' => $id,
            ));
            if(isset($matching_messages[0])){
                //TODO Link to profile or chat widget link maybe?
                return echo_i($matching_messages[0]);
            }
            */

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
    $bootcamps = array();
    $bootcamp_data = null;
    $outbound_levels = 0; //Override this for now and only focus on dispatching tasks at 1 level!


    //Make sure we have the core components checked:
    if(!array_key_exists($botkey,$mench_bots)){
        return array(
            'status' => 0,
            'message' => 'Invalid Bot ID',
        );
    } elseif($outbound_levels<0 || $outbound_levels>1){
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

    //Fetch tree and its messages:
    $tree = $CI->Db_model->c_fetch(array(
        'c.c_id' => $intent_id,
    ) , 1 , array('i') /* Append messages to the return */ );

    if(!isset($tree[0])){
        return array(
            'status' => 0,
            'message' => 'Invalid Intent ID',
        );
    } elseif($b_id){

        $bootcamps = $CI->Db_model->c_full_fetch(array(
            'b.b_id' => $b_id,
        ));

        if(!isset($bootcamps[0])){
            return array(
                'status' => 0,
                'message' => 'Invalid Bootcamp ID',
            );
        } else {
            //Fetch intent relative to the bootcamp by doing an array search:
            $bootcamp_data = extract_level( $bootcamps[0] , $intent_id );
        }

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
    if(strlen($recipients[0]['u_fb_id'])<4){
        return array(
            'status' => 0,
            'message' => 'Recipient has not activated this bot yet',
        );
    }
    
    //Define key variables:
    $instant_messages = array();
    $current_thread_outbound = 0; //Position of current intent

    //This is used for engagement logging of custom messages (not stored in v5_messages) via the echo_i() function
    $custom_message_e_data = array(
        'e_initiator_u_id' => $e_initiator_u_id,
        'e_recipient_u_id' => $e_recipient_u_id,
        'i_c_id' => $intent_id,
        'e_r_id' => $r_id,
        'e_b_id' => $b_id,
        'tree' => $tree[0],
        'depth' => $outbound_levels,
    );

    if($bootcamp_data && $bootcamp_data['level']==2 && $r_id){

        //See if this milestone is due yet...
        $class = filter($bootcamps[0]['c__classes'],'r_id',$r_id);

        if(!$class){
            //This is fishy!
            return array(
                'status' => 0,
                'message' => 'Invalid Class ID',
            );
        }

        $open_date = strtotime(time_format($class['r_start_date'],2,(($bootcamp_data['sprint_index']-1) * ( $bootcamps[0]['b_sprint_unit']=='week' ? 7 : 1 ))))+(intval($class['r_start_time_mins'])*60);

        if(time()<$open_date){

            //Ooopsy, this milestone is not started yet! Let the user know that they are up to date:
            array_push( $instant_messages , echo_i( array_merge( array(
                'i_media_type' => 'text',
                'i_message' => 'Awesome, you completed the current milestone. No more updates for now. I will send you a message when your next milestone starts.',
            ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

            //Send message:
            $CI->Facebook_model->batch_messages($botkey, $recipients[0]['u_fb_id'], $instant_messages, $notification_type);

            //Return message:
            return array(
                'status' => 1,
                'message' => 'Milestone not due yet.',
            );

        }
    }

    //This is the very first message for this milestone!
    if($bootcamp_data && $bootcamp_data['level']==2){

        //Add message to instant stream:
        array_push( $instant_messages , echo_i( array_merge( array(
            'i_media_type' => 'text',
            'i_message' => '🚩 ​{first_name} welcome to your '.$bootcamps[0]['b_sprint_unit'].' '.$bootcamp_data['sprint_index'].' milestone! The target outcome for this milestone is to '.strtolower($bootcamp_data['intent']['c_objective']).'.',
        ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

    } elseif($outbound_levels==0 && $bootcamp_data && $bootcamp_data['level']==3){

        //This is a task, likely due to completing the previous task:
        array_push( $instant_messages , echo_i( array_merge( array(
            'i_media_type' => 'text',
            'i_message' => 'Great job on your task completion. Let\'s move on...', //Supports {first_name} for text messages, but NOT i_url as that needs i_id for tracking (Unless add without tracking?)
        ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

        array_push( $instant_messages , echo_i( array_merge( array(
            'i_media_type' => 'text',
            'i_message' => 'The target outcome for your next task is to '.strtolower($bootcamp_data['intent']['c_objective']).'.', //Supports {first_name} for text messages, but NOT i_url as that needs i_id for tracking (Unless add without tracking?)
        ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

    }

    //See if the milestone/task has messages for it self:
    $starting_message_count = count($instant_messages);

    if(isset($tree[0]['c__messages']) && count($tree[0]['c__messages'])>0){
        //We have messages for the very first level!
        foreach($tree[0]['c__messages'] as $key=>$i){
            if($i['i_status']==1){

                //Mark this tree as sent for the stepping function that will later pick it up via the cron job:
                //$tree[0]['c__messages'][$key]['message_sent_time'] = date("Y-m-d H:i:s");

                //Add message to instant stream:
                array_push( $instant_messages , echo_i(array_merge( $i , array(
                    'e_initiator_u_id' => $e_initiator_u_id,
                    'e_recipient_u_id' => $e_recipient_u_id,
                    'i_c_id' => $intent_id,
                    'e_b_id' => $b_id,
                    'e_r_id' => $r_id,
                    'tree' => $tree[0],
                    'depth' => $outbound_levels,
                )), $recipients[0]['u_fname'], true /*Facebook Format*/ ));

            }
        }
    }

    //We have some messages, huh?
    if($bootcamp_data && $starting_message_count==count($instant_messages) && in_array($bootcamp_data['level'],array(2,3))){

        //Ooops no message for this Milestone:
        array_push( $instant_messages , echo_i( array_merge( array(
            'i_media_type' => 'text',
            'i_message' => 'This '.( $bootcamp_data['level']==2 ? 'milestone' : 'task' ).' has no messages from your instructor.',
        ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

    }


    if($bootcamp_data && $bootcamp_data['level']==2){
        //How many tasks?
        $active_tasks = 0;
        $bonus_tasks = 0; //TODO implement later on...
        foreach($tree[0]['c__child_intents'] as $task){
            if($task['c_status']>=1){
                $active_tasks++;
            }
        }

        if($active_tasks==0){

            //Let students know there are no tasks for this milestone:
            array_push( $instant_messages , echo_i( array_merge( array(
                'i_media_type' => 'text',
                'i_message' => 'This milestone has no tasks.',
            ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

        } else {

            //Let them know how many tasks:
            array_push( $instant_messages , echo_i( array_merge( array(
                'i_media_type' => 'text',
                'i_message' => 'To complete this milestone you need to complete its ' . $active_tasks . ' task' . ($active_tasks == 1 ? '' : 's') . ' which is estimated to take about ' . strtolower(trim(strip_tags(echo_time($bootcamp_data['intent']['c__estimated_hours'], 0)))) . ' in total. {button}', //The {button} command will show a link to the action plan for this milestone...
            ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

        }
    }

    
    //This is only for milestones (Not tasks) as its level=1 (level=0 is for tasks...)
    if($outbound_levels==1 && isset($tree[0]['c__child_intents']) && count($tree[0]['c__child_intents'])>0){

        $active_tasks = 0;
        $bonus_tasks = 0; //TODO implement later on...
        foreach($tree[0]['c__child_intents'] as $task){
            if($task['c_status']>=1){
                $active_tasks++;
            }
        }

        //Count how many tasks and let them know:
        if($active_tasks>0){

            foreach($tree[0]['c__child_intents'] as $level1_key=>$level1){

                if($level1['c_status']<1) {
                    continue;
                }


                //Set initial counter:
                $starting_message_count = count($instant_messages);


                //Does this intent have messages?
                if (isset($level1['c__messages']) && count($level1['c__messages']) > 0) {
                    //We do have a mesasage, lets see if they are active/drip:
                    foreach ($level1['c__messages'] as $key => $i) {
                        if ($i['i_status'] == 1) {

                            if($starting_message_count==count($instant_messages)){

                                //This is the very first message for this Task being added:
                                array_push( $instant_messages , echo_i( array_merge( array(
                                    'i_media_type' => 'text',
                                    'i_message' => ($active_tasks>1 ? 'Your first' : 'Your').' task is to '.strtolower($level1['c_objective']).'. Your instructor has estimated this task to take about '.strtolower(trim(strip_tags(echo_time($level1['c_time_estimate'],0)))).' to complete.',
                                ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

                                if($active_tasks>1){
                                    array_push( $instant_messages , echo_i( array_merge( array(
                                        'i_media_type' => 'text',
                                        'i_message' => 'Once completed I will instantly unlock your next task.',
                                    ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));
                                } else {
                                    //The only task of this milestone:
                                    array_push( $instant_messages , echo_i( array_merge( array(
                                        'i_media_type' => 'text',
                                        'i_message' => 'Once completed you will complete this milestone as this is its only task.',
                                    ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));
                                }

                                array_push( $instant_messages , echo_i( array_merge( array(
                                    'i_media_type' => 'text',
                                    'i_message' => 'Here is how you can go about completing this task:',
                                ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));

                            }

                            //Not needed as we don't have this logic active for now...
                            //Mark this tree as sent for the stepping function that will later pick it up via the cron job:
                            //$tree[0]['c__child_intents'][$level1_key]['c__messages'][$key]['message_sent_time'] = date("Y-m-d H:i:s");

                            //Add message to instant stream:
                            array_push( $instant_messages , echo_i( array_merge( $i , array(
                                'e_initiator_u_id' => $e_initiator_u_id,
                                'e_recipient_u_id' => $e_recipient_u_id,
                                'i_c_id' => $i['i_c_id'],
                                'e_r_id' => $r_id,
                                'tree' => $tree[0],
                                'depth' => $outbound_levels,
                            )), $recipients[0]['u_fname'], true /*Facebook Format*/ ));

                        }
                    }
                }


                if($starting_message_count==count($instant_messages)){
                    //ooops no message found for this task! let the user know:
                    array_push( $instant_messages , echo_i( array_merge( array(
                        'i_media_type' => 'text',
                        'i_message' => 'Oooops! This task has no messages from your instructor.',
                    ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));
                }

                //Create custom message based on Task Completion Settings:
                array_push( $instant_messages , echo_i( array_merge( array(
                    'i_media_type' => 'text',
                    'i_message' => 'Completing this task will earn you '.round($level1['c_time_estimate']*60).' points if completed before the end of this milestone.',
                ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));
                array_push( $instant_messages , echo_i( array_merge( array(
                    'i_media_type' => 'text',
                    //TODO mention the class average response time here:
                    'i_message' => 'If you needed more assistance simply send me a voice or text message and I will forward it to your instructor team for a timely response.',
                ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));
                array_push( $instant_messages , echo_i( array_merge( array(
                    'i_media_type' => 'text',
                    'i_message' => 'Let\'s do it!',
                ), $custom_message_e_data ), $recipients[0]['u_fname'], true ));


                //Level 1 depth only deals with a single intent, so we'll always end here:
                break;
            }
        }
    }

    //Anything to be sent instantly?
    if(count($instant_messages)>0){

        //Dispatch all Instant Messages, their engagements have already been logged:
        $CI->Facebook_model->batch_messages($botkey, $recipients[0]['u_fb_id'], $instant_messages, $notification_type);

    }


    //Successful:
    return array(
        'status' => ( count($instant_messages)>0 ? 1 : 0 ),
        'message' => 'Sent '.count($instant_messages).' instant messages',
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


































