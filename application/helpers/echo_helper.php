<?php





function echo_next_u($page,$limit,$u__outbound_count){
    //We have more child entities than what was listed here.
    //Give user a way to access them:
    echo '<a class="load-more list-group-item" href="javascript:void(0);" onclick="entity_load_more('.$page.')">';

    //Right content:
    echo '<span class="pull-right" style="margin-right: 6px;"><span class="badge badge-secondary"><i class="fas fa-search-plus"></i></span></span>';

    //Regular section:
    $max_entities = (($page+1)*$limit);
    $max_entities = ( $max_entities>$u__outbound_count ? $u__outbound_count : $max_entities );
    echo 'Load '.(($page*$limit)+1).'-'.$max_entities.' from '.$u__outbound_count.' total';

    echo '</a>';
}

function echo_social_profiles($social_profiles){
    $ui = null;
    foreach($social_profiles as $sp){
        $ui .= '<a href="'.$sp['url'].'" target="_blank" class="social-link"><i class="'.$sp['fa_icon'].'"></i></a>';
    }
    return $ui;
}



function echo_x($u, $x){

    $CI =& get_instance();
    $social_urls = $CI->config->item('social_urls');
    $udata = $CI->session->userdata('user');
    $can_edit = ($udata['u_id']==$u['u_id'] || array_key_exists(1281, $udata['u__inbounds']));

    $ui = null;
    $ui .= '<div id="x_'.$x['x_id'].'" class="list-group-item url-item">';

    //Right content:
    $ui .= '<span class="pull-right" style="margin-right: 6px;">';

    if($can_edit && strlen($x['x_clean_url'])>0 && !($x['x_url']==$x['x_clean_url'])){
        //We have detected a different URL behind the scene:
        $ui .= '<a class="badge badge-secondary" href="'.$x['x_clean_url'].'" target="_blank" data-toggle="tooltip" data-placement="left" title="Redirects to another URL"><i class="fas fa-route"></i></a> ';
    }

    //This is an image and can be set as Cover photo, or may have already been set so...
    if($x['x_id']==$u['u_cover_x_id']){
        //Already set as the cover photo:
        $ui .= '<span class="badge badge-secondary grey current-cover" data-toggle="tooltip" data-placement="left" title="Currently set as Cover Photo"><i class="fas fa-file-check"></i></span> ';
    } elseif($x['x_type']==4 && $x['x_status']>0 && $can_edit){
        //Could be set as the cover photo:
        $ui .= '<a class="badge badge-secondary add-cover" href="javascript:void(0);" onclick="x_cover_set('.$x['x_id'].')" data-toggle="tooltip" data-placement="left" title="Set this image as Cover Photo"><i class="fas fa-file-image"></i></a> ';
    }

    //User can always remove a URL:
    if($can_edit){
        $ui .= '<a class="badge badge-secondary" href="javascript:void(0);" onclick="x_delete('.$x['x_id'].')" data-toggle="tooltip" data-placement="left" title="Delete this URL"><i class="fas fa-trash-alt" title="ID '.$x['x_id'].'"></i></a>';
    }

    $ui .= '</span>';


    //Regular section:
    $ui .= '<a href="'.$x['x_url'].'" target="_blank" '.( strlen($x['x_url'])>0 && !($x['x_url']==$x['x_url']) ? '' : '' ).'>';
    $ui .= '<span class="url_truncate"><i class="fas fa-link" style="margin-right:3px;"></i>'.echo_clean_url($x['x_url']).'</span>';

    //Is this a social URL?
    foreach($social_urls as $url=>$fa_icon){
        if(substr_count($x['x_url'],$url)>0){
            $ui .= '<i class="'.$fa_icon.'" data-toggle="tooltip" data-placement="top" title="Verified Domain"></i> ';
            break;
        }
    }


    $ui .= '<i class="fas fa-external-link-square"></i></a>';

    //Can we display this URL?
    if($x['x_type']==1){
        $ui .= '<div style="margin-top:7px;">'.echo_embed($x['x_clean_url'],$x['x_clean_url']).'</div>';
    } elseif($x['x_type']>1){
        $ui .= '<div style="margin-top:7px;">'.echo_content_url($x['x_clean_url'],$x['x_type']).'</div>';
    }

    $ui .= '</div>';

    return $ui;
}



function echo_rank($rank){
    if($rank==1){
        return '🥇';
    } elseif($rank==2){
        return '🥈';
    } elseif($rank==3){
        return '🥉';
    } else {
        return echo_ordinal($rank);
    }
}

function echo_tip($c_id){
    echo '<span id="hb_'.$c_id.'" class="help_button belowh2-btn" intent-id="'.$c_id.'"></span>';
    echo '<div class="help_body belowh2-bdy maxout" id="content_'.$c_id.'"></div>';
}








function echo_min_from_sec($sec_int){
    $sec_int = intval($sec_int);
    $min = 0;
    $sec = fmod($sec_int,60);
    if($sec_int>=60){
        $min = floor($sec_int/60);
    }
    return ( $min ? $min.'m' : '' ).( $sec ? ( $min ? ' ' : '' ).$sec.'s' : '' );
}

function echo_content_url($x_clean_url,$x_type){
    if($x_type==4){
        return '<img src="'.$x_clean_url.'" style="max-width:100%" />';
    } elseif($x_type==3){
        return '<audio controls><source src="'.$x_clean_url.'" type="audio/mpeg"></audio>';
    } elseif($x_type==2){
        return '<video width="100%" onclick="this.play()" controls><source src="'.$x_clean_url.'" type="video/mp4"></video>';
    } elseif($x_type==5){
        return '<a href="'.$x_clean_url.'" class="btn btn-primary" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';
    } else {
        return false;
    }
}

function echo_embed($url, $full_message=null, $return_array=false, $start_sec=0, $end_sec=0){

    $clean_url = null;
    $embed_html_code = null;
    $prefix_message = null;

    if(!$full_message){
        $full_message = $url;
    }

    //See if $url has a valid embed video in it, and transform it if it does:
    if(substr_count($url,'youtube.com/watch?v=')==1 || substr_count($url,'youtu.be/')==1 || substr_count($url,'youtube.com/embed/')==1){

        //Seems to be youtube:
        if(substr_count($url,'youtube.com/embed/')==1){

            //We might have start and end here too!
            $video_id = trim(one_two_explode('youtube.com/embed/','?',$url));

        } elseif(substr_count($url,'youtube.com/watch?v=')==1){

            $video_id = trim(one_two_explode('youtube.com/watch?v=','&',$url));

        } elseif(substr_count($url,'youtu.be/')==1){

            $video_id = trim(one_two_explode('youtu.be/','?',$url));

        }

        //This should be 11 characters!
        if(strlen($video_id)==11){

            //Set the Clean URL:
            $clean_url = 'https://www.youtube.com/watch?v='.$video_id;

            //Inform Student that this video has been sliced:
            if($start_sec || $end_sec){
                $embed_html_code .= '<div class="video-prefix"><i class="fab fa-youtube"></i> Watch '.( ($start_sec && $end_sec) ? 'this <b>'.echo_min_from_sec(($end_sec-$start_sec)).'</b> video clip' : 'from <b>'.($start_sec ? echo_min_from_sec($start_sec) : 'start').'</b> to <b>'.($end_sec ? echo_min_from_sec($end_sec) : 'end').'</b>' ).':</div>';
            }

            $embed_html_code .= '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/'.$video_id.'?theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start='.$start_sec.( $end_sec ? '&end='.$end_sec : '' ).'" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';

        }

    } elseif(substr_count($url,'vimeo.com/')==1){

        //Seems to be Vimeo:
        $video_id = trim(one_two_explode('vimeo.com/','?',$url));

        //This should be an integer!
        if(intval($video_id)==$video_id){
            $clean_url = 'https://vimeo.com/'.$video_id;
            $embed_html_code = '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://player.vimeo.com/video/'.$video_id.'?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
        }

    } elseif(substr_count($url,'wistia.com/medias/')==1){

        //Seems to be Wistia:
        $video_id = trim(one_two_explode('wistia.com/medias/','?',$url));
        $clean_url = trim(one_two_explode('','?',$url));
        $embed_html_code = '<script src="https://fast.wistia.com/embed/medias/'.$video_id.'.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding video-sorting" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_'.$video_id.' seo=false videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>';

    }

    if($return_array){

        //Return all aspects of this parsed URL:
        return array(
            'status' => ( $embed_html_code ? 1 : 0 ),
            'embed_code' => $embed_html_code,
            'clean_url' => $clean_url,
        );

    } else {
        //Just return the embed code:
        if($embed_html_code){
            return trim(str_replace($url,$embed_html_code,$full_message));
        } else {
            //Not matched with an embed rule:
            return false;
        }
    }
}




function echo_i($i,$u_full_name=null,$fb_format=false){

    //HACK: Make these two variables inter-changeable:
    if(isset($i['i_outbound_c_id']) && $i['i_outbound_c_id']>0 && !isset($i['e_outbound_c_id'])){
        $i['e_outbound_c_id'] = $i['i_outbound_c_id'];
    } elseif(isset($i['e_outbound_c_id']) && $i['e_outbound_c_id']>0 && !isset($i['i_outbound_c_id'])){
        $i['i_outbound_c_id'] = $i['e_outbound_c_id'];
    }

    $CI =& get_instance();
    $button_url = ( isset($i['button_url']) ? $i['button_url'] : null );
    $button_title = ( isset($i['button_title']) ? $i['button_title'] : null );
    $command = null;
    $is_intent = ( $CI->uri->segment(1)=='intents' );
    $is_entity = ( $CI->uri->segment(1)=='entities' );
    $is_public = ( !$is_intent && !$is_entity ); //The public landing pages that users use to get started
    $is_focus_entity = ( $is_entity && $CI->uri->segment(2)==$i['i_outbound_u_id'] );
    $ui = null;
    $original_cs = array();


    if($fb_format){
        //This is what will be returned to be sent via messenger:
        $fb_message = array();
    } else {
        //HTML format:
        $i['i_message'] = nl2br($i['i_message']);
        $ui .= '<div class="i_content">';
    }

    //Is it being displayed under entities? Show the original intent as well:
    if($is_entity && !$fb_format){

        $original_cs = $CI->Db_model->c_fetch(array(
            'c_id' => $i['i_outbound_c_id'],
        ));
        if(count($original_cs)>0){

            $ui .= '<div class="entities-msg">';
            $ui .= '<span class="pull-right" style="margin:6px 10px 0 0;">';
                $ui .= '<span data-toggle="tooltip" title="This is the '.echo_ordinal($i['i_rank']).' message for this intent" data-placement="left" class="underdot" style="padding-bottom:4px;">'.echo_ordinal($i['i_rank']).'</span> ';
                $ui .= '<span>'.echo_status('i_status',$i['i_status'],1,'left').'</span> ';
                $ui .= '<a href="/entities/'.$i['i_inbound_u_id'].'" class="on-hover i_uploader badge badge-secondary" data-toggle="tooltip" title="Last modified by '.$i['u_full_name'].' about '.echo_diff_time($i['i_timestamp']).' ago. Click to open profile." data-placement="left">'.echo_cover($i,null,true).'</a>';
                $ui .= '<a href="/intents/'.$i['i_outbound_c_id'].'#messages-'.$i['i_outbound_c_id'].'"><span class="badge badge-primary" style="display:inline-block; margin-left:3px; width:40px;"><i class="fas fa-sign-out-alt rotate90"></i></span></a>';
            $ui .= '</span>';
            $ui .= '<h4><i class="fas fa-hashtag" style="font-size:1em;"></i> '.$original_cs[0]['c_outcome'].'</h4>';
            $ui .= '<div>';

        }
    }



    //Does this have a entity reference?
    if(isset($i['i_outbound_u_id']) && $i['i_outbound_u_id']>0){

        //This message has a referenced entity
        //See if that entity has a URL:
        $us = $CI->Db_model->u_fetch(array(
            'u_id' => $i['i_outbound_u_id'],
        ), array('skip_u__inbounds','u__urls'));

        if(count($us)>0){

            //Is there a slice command?
            if($fb_format){

                //Show an option to open action plan:
                $i['i_message'] = str_replace('@'.$i['i_outbound_u_id'], $us[0]['u_full_name'].' /open_actionplan', $i['i_message']);

                if(substr_count($i['i_message'],'/slice')>0){
                    $time_range = explode(':', one_two_explode('/slice:',' ',$i['i_message']) ,2);
                    $i['i_message'] = str_replace('/slice:'.$time_range[0].':'.$time_range[1], '', $i['i_message']);
                }

            } else {

                //HTML Format:
                $time_range = array();
                $button_title = 'Open Entity';
                $button_url = '/entities/'.$us[0]['u_id'].'?skip_header=1'; //To loadup the entity
                $embed_html_code = null;

                if(substr_count($i['i_message'],'/slice')>0){

                    $time_range = explode(':', one_two_explode('/slice:',' ',$i['i_message']) ,2);

                    //Try finding a compatible URL for slicing:
                    foreach($us[0]['u__urls'] as $x){
                        if($x['x_type']==1 && substr_count($x['x_url'],'youtube.com')>0 ){
                            $embed_html_code = '<div style="margin-top:7px;">'.echo_embed($x['x_clean_url'], $x['x_clean_url'],false, $time_range[0], $time_range[1]).'</div>';
                            break;
                        }
                    }

                    //Remove slice command:
                    $i['i_message'] = str_replace('/slice:'.$time_range[0].':'.$time_range[1], '', $i['i_message']);


                } elseif(!$is_focus_entity) {

                    //So we did not have a slice command and this is an HTML request for a non-entity page
                    //Note: The reason we don't need these for entities is that they already list all URLs with embed codes, so no need to repeat
                    //Let's see if we have any other embeddable content that we can append to message:

                    foreach($us[0]['u__urls'] as $x){
                        //Find all the ways we could use this URL:
                        if($x['x_type']==0){
                            if($is_public){
                                //Replace the name:

                            } else {
                                //Regular website:
                                $embed_html_code .= '<div style="margin-top:7px;"><a href="'.$x['x_url'].'" target="_blank"><span class="url_truncate"><i class="fas fa-link" style="margin-right:3px;"></i>'.echo_clean_url($x['x_url']).'</span></a></div>';
                            }
                        } elseif($x['x_type']==1){
                            $embed_html_code .= '<div style="margin-top:7px;">'.echo_embed($x['x_clean_url'],$x['x_clean_url']).'</div>';
                        } elseif($x['x_type']>1){
                            $embed_html_code .= '<div style="margin-top:7px;">'.echo_content_url($x['x_clean_url'],$x['x_type']).'</div>';
                        }
                    }
                }



                if($is_intent || ($is_entity && !$is_focus_entity)) {

                    //HTML format:
                    $i['i_message'] = str_replace('@'.$i['i_outbound_u_id'], echo_social_profiles($CI->Db_model->x_social_fetch($i['i_outbound_u_id'])).' <a href="javascript:void(0);" onclick="url_modal(\''.$button_url.'\')">'.$us[0]['u_full_name'].'</a>', $i['i_message']);

                } else {

                    //HTML format:
                    $entity_title = ( strlen($us[0]['u_bio'])>0 ? '<span data-toggle="tooltip" title="'.$us[0]['u_bio'].'" data-placement="top" class="underdot">'.$us[0]['u_full_name'].'</span>' : $us[0]['u_full_name'] );
                    $i['i_message'] = str_replace('@'.$i['i_outbound_u_id'], $entity_title.' ', $i['i_message']);

                }

                //Did we have an embed code to be attached?
                if($embed_html_code){
                    $i['i_message'] .= $embed_html_code;
                }

            }

        }
    }



    //Does this have an intent reference?
    if(isset($i['i_inbound_c_id']) && $i['i_inbound_c_id']>0){
        //This message has a referenced entity
        //See if that entity has a URL:
        $cs = $CI->Db_model->c_fetch(array(
            'c_id' => $i['i_inbound_c_id'],
        ));

        if($fb_format || $is_public){
            $i['i_message'] = str_replace('#'.$i['i_inbound_c_id'], $cs[0]['c_outcome'], $i['i_message']);
        } else {
            //HTML format for the console:
            $i['i_message'] = str_replace('#'.$i['i_inbound_c_id'], '<a href="javascript:void(0);" onclick="url_modal(\'/intents/'.$cs[0]['c_id'].'?skip_header=1\')">'.$cs[0]['c_outcome'].'</a>', $i['i_message']);
        }
    }



    //Do we have any commands?
    if($u_full_name && substr_count($i['i_message'],'/firstname')>0){
        //Tweak the name:
        $command = '/firstname';
        $i['i_message'] = str_replace('/firstname', one_two_explode('',' ',$u_full_name), $i['i_message']);
    }



    if(substr_count($i['i_message'],'/open_actionplan')>0 && isset($i['e_w_id']) && $i['e_w_id']>0 && isset($i['i_outbound_c_id']) && $i['i_outbound_c_id']>0){
        $button_title = 'Open in 🚩Action Plan';
        $command = '/open_actionplan';
        $button_url = 'https://mench.com/my/actionplan/'.$i['e_w_id'].'/'.$i['i_outbound_c_id'].'?is_from_messenger=1';
    }


    if(substr_count($i['i_message'],'/typing')>0){
        $command = '/typing';
        if($fb_format) {
            //TODO include sender actions https://developers.facebook.com/docs/messenger-platform/send-messages/sender-actions/
        } else {
            //HTML format:
            $i['i_message'] = str_replace($command, '<img src="/img/typing.gif" height="35px" />', $i['i_message']);
        }
    }



    if(substr_count($i['i_message'],'/resetpassurl')>0 && isset($i['e_outbound_u_id'])) {
        //append their My Account Button/URL:
        $timestamp = time();
        $button_title = '👉 Set New Password';
        $button_url = 'https://mench.com/my/reset_pass?u_id='.$i['e_outbound_u_id'].'&timestamp='.$timestamp.'&p_hash=' . md5($i['e_outbound_u_id'] . 'p@ssWordR3s3t' . $timestamp);
        $command = '/resetpassurl';
    }




    if($command || $button_url){

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


            if($button_url && $button_title){
                //HTML format replaces the button with the command:
                $i['i_message'] = trim(str_replace($command, '<div class="msg" style="padding-top:15px;"><a href="'.$button_url.'" target="_blank"><b>'.$button_title.'</b></a></div>', $i['i_message']));
            }

            //Return HTML code:
            $ui .= '<div class="msg">'.$i['i_message'].'</div>';

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

            //Should we append a Quick reply to this message?
            if(isset($i['quick_replies']) && count($i['quick_replies'])>0){
                $fb_message['quick_replies'] = $i['quick_replies'];
            }

        } else {
            //HTML format:
            $ui .= '<div class="msg">'.$i['i_message'].'</div>';
        }

    }


    //Log engagement if Facebook and return:
    if($fb_format){

        if(count($fb_message)>0){
            //Return Facebook Message to be sent out:
            return $fb_message;
        } else {
            //Should not happen!
            return false;
        }

    } else {

        //This must be HTML if we're still here, return:
        if(count($original_cs)>0){
            $ui .= '</div></div>';
        }

        $ui .= '</div>';
        return $ui;

    }
}



function echo_message($i){

    $CI =& get_instance();
    $message_max = $CI->config->item('message_max');

    $ui = '';
    $ui .= '<div class="list-group-item is-msg is_level2_sortable all_msg msg_'.$i['i_status'].'" id="ul-nav-'.$i['i_id'].'" iid="'.$i['i_id'].'">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="edit-off text_message" id="msg_body_'.$i['i_id'].'" style="margin:2px 0 0 0;">';
    $ui .= echo_i($i);
    $ui .= '</div>';


    //Text editing:
    $ui .= '<textarea onkeyup="changeMessageEditing('.$i['i_id'].')" name="i_message" id="message_body_'.$i['i_id'].'" class="edit-on hidden msg msgin algolia_search" placeholder="Write Message..." style="margin-top: 4px;">'.$i['i_message'].'</textarea>';

    //Editing menu:
    $ui .= '<ul class="msg-nav">';
    //$ui .= '<li class="edit-off"><i class="fas fa-clock"></i> 4s Ago</li>';
    //$ui .= '<li class="the_status edit-off" style="margin: 0 6px 0 -3px;">'.echo_status('i_status',$i['i_status'],1,'right').'</li>'; //This is duplicate of top menu status
    $ui .= '<li class="edit-on hidden"><span id="charNumEditing'.$i['i_id'].'">0</span>/'.$message_max.'</li>';
    $ui .= '<li class="edit-off"><span class="on-hover i_uploader">'.echo_cover($i,null,true, 'data-toggle="tooltip" title="Last modified by '.$i['u_full_name'].' about '.echo_diff_time($i['i_timestamp']).' ago" data-placement="right"').'</span></li>';

    $ui .= '<li class="edit-off" style="margin: 0 0 0 8px;"><span class="on-hover"><i class="fas fa-bars sort_message" iid="'.$i['i_id'].'" style="color:#2f2739;"></i></span></li>';
    $ui .= '<li class="edit-off" style="margin-right: 10px; margin-left: 6px;"><span class="on-hover"><a href="javascript:i_delete('.$i['i_id'].');"><i class="fas fa-trash-alt" style="margin:0 7px 0 5px;"></i></a></span></li>';
    $ui .= '<li class="edit-off" style="margin-left:-4px;"><span class="on-hover"><a href="javascript:msg_start_edit('.$i['i_id'].','.$i['i_status'].');"><i class="fas fa-pen-square"></i></a></span></li>';
    //Right side reverse:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary" href="javascript:message_save_updates('.$i['i_id'].','.$i['i_status'].');" style="text-decoration:none; font-weight:bold; padding: 1px 8px 4px;"><i class="fas fa-check"></i></a></li>';
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-hidden" href="javascript:msg_cancel_edit('.$i['i_id'].');"><i class="fas fa-times" style="color:#2f2739"></i></a></li>';
    $ui .= '<li class="pull-right edit-on hidden">'.echo_dropdown_status('i_status','i_status_'.$i['i_id'],$i['i_status'],array(-1,0),'dropup',1).'</li>';
    $ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors

    $ui .= '</ul>';

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function echo_cover($u,$img_class=null,$return_anyways=false,$tooltip_content=null){
    if($u['u_cover_x_id']>0 && isset($u['x_url'])){
        return '<img src="'.$u['x_url'].'" class="'.$img_class.'" '.$tooltip_content.' />';
    } elseif($return_anyways) {
        return '<i class="fas fa-at" '.$tooltip_content.' ></i>';
    } else {
        return null;
    }
}

function echo_link($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank"><u>$1</u></a>', $text);
}

function echo_big_num($number){
    if($number>=10000000){
        return '<span title="'.$number.'">'.round(($number/1000000),0).'m</span>';
    } elseif($number>=1000000){
        return '<span title="'.$number.'">'.round(($number/1000000), 1).'m</span>';
    } elseif($number>=10000){
        return '<span title="'.$number.'">'.round(($number/1000), 0).'k</span>';
    } elseif($number>=1000){
        return '<span title="'.$number.'">'.round(($number/1000),1).'k</span>';
    } else {
        return $number;
    }
}



function echo_e($e){

    $CI =& get_instance();

    //Display the item
    $ui = '<div class="list-group-item">';

        //Right content:
        $ui .= '<span class="pull-right">';

            //Show user notification level:
            $ui .= ' <span>'.echo_status('e_status',$e['e_status'], true, 'left').'</span> ';

            //Lets go through all references to see what is there:
            $engagement_references = $CI->config->item('engagement_references');
            foreach($engagement_references as $engagement_field=>$er){
                if(intval($e[$engagement_field])>0){
                    //Yes we have a value here:
                    $ui .= echo_object($er['object_code'], $e[$engagement_field], $engagement_field, $er['name']);
                }
            }

            if($e['e_has_blob']=='t'){
                $ui .= '<a href="/cockpit/ej_list/'.$e['e_id'].'" class="badge badge-primary grey" target="_blank" data-toggle="tooltip" title="Analyze Engagement JSON Blob in a new window" data-placement="left"><i class="fas fa-search-plus"></i></a>';
            }

        $ui .= '</span>';

        //What type of main content do we have, if any?
        $main_content = null;
        $main_content_title = null;
        if(strlen($e['e_text_value'])>0){
            $main_content = format_e_text_value($e['e_text_value']);
        } elseif($e['e_i_id']>0){
            //Fetch message conent:
            $matching_messages = $CI->Db_model->i_fetch(array(
                'i_id' => $e['e_i_id'],
            ));
            if(count($matching_messages)>0){
                $main_content_title = ' Message #'.$e['e_i_id'];
                $main_content = echo_i($matching_messages[0]);
            }
        }


        $ui .= '<b>'.$e['c_outcome'].'</b>';
        $ui .= ' <span data-toggle="tooltip" data-placement="right" title="'.$e['e_timestamp'].' Engagement #'.$e['e_id'].'" style="font-size:0.8em;">'.echo_diff_time(strtotime($e['e_timestamp'])).' ago</span> ';
        $ui .= $main_content_title;

        //Do we have a message?
        $ui .= '<div class="e-msg '.( $main_content && strlen($main_content)>0 ? '' : 'hidden' ).'">';
        $ui .= $main_content;
        $ui .= '</div>';


    $ui .= '</div>';

    return $ui;
}

function echo_w_console($w){

    //Assumes w_stats has been added to w_fetch so we can display proper stats here...

    $CI =& get_instance();
    //This function will be called from 3 areas:
    $is_intent = ( $CI->uri->segment(1)=='intents' );
    $is_entity = ( $CI->uri->segment(1)=='entities' );
    $is_cockpit = ( $CI->uri->segment(1)=='cockpit' );
    $subscription_title = ''; //Build as we go depending on which view is loaded...


    //Display the item
    $ui = '<div class="list-group-item" id="w_div_'.$w['w_id'].'">';

    //Right content:
    $ui .= '<span class="pull-right">';

    //Show subscription time:
    $ui .= ' <span data-toggle="tooltip" data-placement="top" title="Student initiated subscription on '.$w['w_timestamp'].'" style="font-size:0.8em;">'.echo_diff_time($w['w_timestamp']).'</span> ';


    //Show user notification level:
    $ui .= ' <span>'.echo_status('u_fb_notification',$w['u_fb_notification'], true, 'left').'</span> ';


    //Then customize based on request location:
    if($is_intent || $is_cockpit){

        //Show user who has subscribed:
        $user_ws = $CI->Db_model->w_fetch(array(
            'w_outbound_u_id' => $w['w_outbound_u_id'],
        ));
        $subscription_title .= echo_cover($w,'micro-image', 1).' ';
        $subscription_title .= '<span class="u_full_name u_full_name_'.$w['u_id'].'">'.$w['u_full_name'].'</span>';

        //Engagements made by subscriber:
        $ui .= '<a href="#wengagements-'.$w['w_outbound_u_id'].'-'.$w['w_id'].'" onclick="load_u_engagements('.$w['w_outbound_u_id'].','.$w['w_id'].')" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="'.$w['w_stats']['e_all_count'].' engagements"><span class="btn-counter">'.$w['w_stats']['e_all_count'].( $w['w_stats']['e_all_count']==$CI->config->item('max_counter') ? '+' : '').'</span><i class="fas fa-exchange"></i></a>';

        //Link to subscriber, but count total subscriptions first:
        $ui .= '<a href="/entities/'.$w['w_outbound_u_id'].'" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="top" title="Student has '.count($user_ws).' total subsciptions"><span class="btn-counter">'.count($user_ws).'</span><i class="fas fa-sign-out-alt rotate90"></i></a>';

    }


    //Number of intents in Student Action Plan:
    $ui .= '<a href="#wactionplan-'.$w['w_id'].'-'.$w['w_outbound_u_id'].'" onclick="load_w_actionplan('.$w['w_id'].','.$w['w_outbound_u_id'].')" class="badge badge-primary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="'.$w['w_stats']['k_count_done'].'/'.($w['w_stats']['k_count_done']+$w['w_stats']['k_count_undone']).' intents are marked as complete. Click to open Action Plan."><span class="btn-counter">'.( ($w['w_stats']['k_count_undone']+$w['w_stats']['k_count_done'])>0 ? number_format(($w['w_stats']['k_count_done']/($w['w_stats']['k_count_undone']+$w['w_stats']['k_count_done'])*100),0).'%' : '0%' ).'</span><i class="fas fa-flag" style="font-size:0.85em;"></i></a>';


    if($is_entity || $is_cockpit){

        //Link to subscription's main intent:
        $intent_ws = $CI->Db_model->w_fetch(array(
            'w_inbound_c_id' => $w['c_id'],
        ));
        $ui .= '<a href="/intents/'.$w['c_id'].'" class="badge badge-primary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="Open subscribed intention to '.$w['c_outcome'].' with '.count($intent_ws).' subscriptions"><span class="btn-counter">'.count($intent_ws).'</span><i class="fas fa-sign-in-alt"></i></a>';

        $subscription_title .= ( $is_cockpit ? '<div style="margin: 3px 0 0 3px;"><i class="fas fa-hashtag"></i> ' : '' );
        $subscription_title .= '<span class="w_intent_'.$w['w_id'].'">'.$w['c_outcome'].'</span>';
        $subscription_title .= ( $is_cockpit ? '</div>' : '' );
    }


    $ui .= '</span>';


    //Start with subscription status:
    $ui .= echo_status('w_status',$w['w_status'], true, 'right').' ';
    $ui .= $subscription_title;
    $ui .= '</div>';


    return $ui;
}


function echo_w_students($w){
    $ui = '<a href="/my/actionplan/'.$w['w_id'].'/'.$w['w_inbound_c_id'].'" class="list-group-item">';
    $ui .= '<span class="pull-right">';
    $ui .= '<span class="badge badge-primary"><i class="fas fa-angle-right"></i></span>';
    $ui .= '</span>';
    $ui .= echo_status('w_status',$w['w_status'],1,'right');
    $ui .= ' '.$w['c_outcome'];
    $ui .= '  '.$w['c__tree_all_count'];
    $ui .= ' &nbsp;<i class="fas fa-clock"></i> '.echo_hour_range($w,1);
    $ui .= '</a>';
    return $ui;
}


function echo_k_console($k){

    //NOTE: Assumes the subscription, its intent and entity subscriber are loaded in $k

    $CI =& get_instance();

    //Fetch some additional subscription stats:
    $user_ws = $CI->Db_model->w_fetch(array(
        'w_outbound_u_id' => $k['u_id'],
    ));
    $intent_ws = $CI->Db_model->w_fetch(array(
        'w_inbound_c_id' => $k['c_id'],
    ));



    //Display the item
    $ui = '<div class="list-group-item">';

        //Right content:
        $ui .= '<span class="pull-right">';

            //Show last update time, if any:
            if($k['k_last_updated']){
                $ui .= ' <span data-toggle="tooltip" data-placement="top" title="Submitted on  '.$k['k_last_updated'].'" style="font-size:0.8em;">'.echo_diff_time($k['k_last_updated']).'</span> ';
            }

            //Link to subscriber, but count total subscriptions first:
            $ui .= '<a href="/entities/'.$k['u_id'].'" target="_parent" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="Open Subscriber '.$k['u_full_name'].' with '.count($user_ws).' subscriptions"><span class="btn-counter">'.count($user_ws).'</span><i class="fas fa-sign-out-alt rotate90"></i></a>';

            //Link to subscription's main intent:
            $ui .= '<a href="/intents/'.$k['c_id'].'" target="_parent" class="badge badge-primary" style="width:40px;" data-toggle="tooltip" data-placement="left" title="Open subscribed intention to '.$k['c_outcome'].' with '.count($intent_ws).' subscriptions"><span class="btn-counter">'.count($intent_ws).'</span><i class="fas fa-sign-in-alt"></i></a>';

        $ui .= '</span>';

        //Show user who has subscribed:
        $ui .= echo_cover($k,'micro-image', 1).' ';
        $ui .= $k['u_full_name'];
        $ui .= echo_status('w_status',$k['w_status'], true, 'top').' '.$k['c_outcome'];

        if(strlen($k['k_notes'])>0){
            $ui .= '<div class="e-msg ">'.$k['k_notes'].'</div>';
        }

    $ui .= '</div>';



    return $ui;
}


function echo_k($k, $is_inbound, $c_is_any_cr_inbound_c_id=0){

    $ui = '<a href="'.( $c_is_any_cr_inbound_c_id ? '/my/choose_any_path/'.$k['w_id'].'/'.$c_is_any_cr_inbound_c_id.'/'.$k['c_id'].'/'.md5($k['w_id'].'kjaghksjha*(^'.$k['c_id'].$c_is_any_cr_inbound_c_id) : '/my/actionplan/'.$k['k_w_id'].'/'.$k['c_id'] ).'" class="list-group-item">';

    //Different pointer position based on direction:
    if($is_inbound){
        $ui .= '<span class="pull-left">';
        $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
        $ui .= '</span>';
    } else {
        $ui .= '<span class="pull-right">';
        $ui .= '<span class="badge badge-primary fr-bgd">'.( $c_is_any_cr_inbound_c_id ? 'Select <i class="fas fa-check-circle"></i>' : '<i class="fas fa-angle-right"></i>').'</span>';
        $ui .= '</span>';

        //For outbound show icon:
        if($c_is_any_cr_inbound_c_id){
            //Radio button to indicate a single selection:
            $ui .= '<span class="status-label" style="padding-bottom:1px;"><i class="fal fa-circle"></i> </span>';
        } else {
            //Proper status:
            $ui .= echo_status('k_status',$k['k_status'],1,'right');
        }
    }

    $ui .= ' '.$k['c_outcome'];
    if(strlen($k['k_notes'])>0){
        $ui .= ' <i class="fas fa-edit"></i> '.htmlentities($k['k_notes']);
    }

    $ui .= '</a>';

    return $ui;
}


function echo_clean_url($url){
    return rtrim(str_replace('http://','',str_replace('https://','',str_replace('www.','',$url))),'/');
}



function echo_hours($decimal_hours,$micro=false){
    if($decimal_hours<=0){
        return '0'.($micro?'m':' Minutes ');
    } elseif($decimal_hours<=1.50){
        $original_hours = $decimal_hours*60;
        $decimal_hours = round($original_hours);
        return $decimal_hours.($micro?'m':' Minutes');
    } else {
        //Just round-up:
        $original_hours = $decimal_hours;
        $decimal_hours = round($original_hours);
        return $decimal_hours.($micro?'h':' Hour'.echo__s($original_hours));
    }
}

function echo_contents($c, $fb_format=0){

    //Do we have anything to return?
    if(strlen($c['c__tree_contents'])<=0){
        return false;
    }


    //Make initial variables:
    $c['c__tree_contents'] = unserialize($c['c__tree_contents']);

    if(count($c['c__tree_contents'])<1){
        return false;
    }

    $all_count = 0;
    foreach($c['c__tree_contents'] as $type_u_id=>$current_us){
        $all_count += count($current_us);
    }

    if($all_count>0){

        $visible_ppl = 3;
        $type_count = 0;
        $type_all_count = count($c['c__tree_contents']);
        $CI =& get_instance();
        $content_types = $CI->config->item('content_types');
        $edit_enabled = auth(array(1281),0);
        //More than 3:
        $text_overview = '';
        foreach($c['c__tree_contents'] as $type_id=>$current_us){

            if($type_count>0){
                if(($type_count+1)>=$type_all_count){
                    $text_overview .= ' &';
                } else {
                    $text_overview .= ',';
                }
            }

            //Show category:
            $cat_contribution = count($current_us).' '.$content_types[$type_id].echo__s(count($current_us));
            if($fb_format) {

                $text_overview .= ' '.$cat_contribution;

            } else {

                $text_overview .= ' <span class="show_type_'.$type_id.'"><a href="javascript:void(0);" onclick="$(\'.show_type_'.$type_id.'\').toggle()" style="text-decoration:underline; display:inline-block;">'.$cat_contribution.'</a></span><span class="show_type_'.$type_id.'" style="display:none;">';

                //We only show details on our website's HTML landing pages:
                $count = 0;
                foreach ($current_us as $u) {

                    if ($count > 0) {
                        if (($count + 1) >= count($current_us)) {
                            $text_overview .= ' &';
                        } else {
                            $text_overview .= ',';
                        }
                    }

                    $text_overview .= ' ';

                    if ($edit_enabled) {
                        $text_overview .= '<a href="/entities/' . $u['u_id'] . '">';
                    }

                    if (isset($u['u_bio']) && strlen($u['u_bio']) > 0) {
                        //Has description, show it here:
                        $text_overview .= ' <span data-toggle="tooltip" title="' . stripslashes($u['u_bio']) . '" data-placement="top" class="underdot">' . $u['u_full_name'] . '</span>';
                    } else {
                        //Just the name:
                        $text_overview .= $u['u_full_name'];
                    }

                    if ($edit_enabled) {
                        $text_overview .= '</a>';
                    }
                    $count++;
                }
                $text_overview .= '</span>';
            }
            $type_count++;
        }
    }

    //Return results:
    if($all_count==0){
        return false;
    }




    $pitch = 'Action Plan references'.$text_overview.' from industry experts.';
    if($fb_format) {
        return '📚 '.$pitch."\n";
    } else {
        //HTML format
        $id = 'ContentReferences';
        return '<div class="panel-group" id="open'.$id.'" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading'.$id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open'.$id.'" href="#collapse'.$id.'" aria-expanded="false" aria-controls="collapse'.$id.'">
                        <i class="fas" style="transform:none !important;">📚</i> '.$all_count.' Reference'.echo__s($all_count).'<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse'.$id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading'.$id.'">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">'.$pitch.'</div>
            </div>
        </div></div>';
    }
}

function echo_pa_lets(){
    $options = array(
        'You can give me a command by starting a sentence with "Lets", for example: [Lets land a dream job], [Lets book new interviews] or [Lets create a great resume].',
        'You can command me using "Lets", for example: [Lets get hired], [Lets create a cover letter] or [Lets do better at interviews].',
    );
    return $options[rand(0,(count($options)-1))];
}

function echo_skip_statements(){
    //TODO Not in use for now, maybe remove?
    $options = array(
        'You may also skip and do none of the above, even though I would not recommend that.',
        'You can skip and move on, which I don\'t think is a good idea.',
        'You may also skip this entire section and move on, which I would vote against.',
        'There is a skip option as well...',
    );
    return $options[rand(0,(count($options)-1))];
}



function echo_c_requirements($c){
    if($c['c_require_url_to_complete'] && $c['c_require_notes_to_complete']) {
        return 'Marking as complete requires both a URL & a written response';
    } elseif ($c['c_require_url_to_complete']) {
        return 'Marking as complete requires a URL';
    } elseif ($c['c_require_notes_to_complete']) {
        return 'Marking as complete requires a written response';
    } else {
        return null;
    }
}

function echo_pa_saved(){
    //Informs the user that their answer is saved!
    $options = array(
        'Got it 👍',
        'Noted',
        'Ok sweet',
        'Nice answer',
        'Nice 👍',
        'Gotcha 🙌',
        'Fabulous',
        'Confirmed',
        '👌',
    );
    return $options[rand(0,(count($options)-1))];
}

function echo_pa_oneway(){
    //Informs the user that the PA cannot speak, unless you give it a specific command like Lets
    $options = array(
        'I am not designed to respond to custom text messages. I can understand you only when you choose one of the multiple-choice options I provide.',
        'What was that? I would only understand if you choose one of the multiple-choice options I provide.',
    );
    return $options[rand(0,(count($options)-1))];
}

function echo_costs($c, $fb_format=0){

    if($c['c__tree_max_cost']<=0){
        return false;
    } elseif(round($c['c__tree_max_cost'])==round($c['c__tree_min_cost']) || $c['c__tree_min_cost']==0){
        //Single price:
        $price_range = '$'.round($c['c__tree_max_cost']).' USD';
    } else {
        //Price range:
        $price_range = 'between $'.round($c['c__tree_min_cost']).' to $'.round($c['c__tree_max_cost']).' USD';
    }


    $pitch = 'Action Plan recommends '.$price_range.' in third-party product purchases.';
    if($fb_format) {
        return '💸 '.$pitch."\n";
    } else {
        //HTML format
        $id = 'CostForcast';
        return '<div class="panel-group" id="open'.$id.'" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading'.$id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open'.$id.'" href="#collapse'.$id.'" aria-expanded="false" aria-controls="collapse'.$id.'">
                        <i class="fas" style="transform:none !important;">💸</i> '.ucwords($price_range).'<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse'.$id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading'.$id.'">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">'.$pitch.'</div>
            </div>
        </div></div>';
    }
}

function echo_intent_overview($c, $fb_format=0){

    $pitch = 'Action Plan contains '.$c['c__tree_all_count'].' insights that will help you '.$c['c_outcome'].'.';

    if($fb_format) {
        return '🚩 '.$pitch."\n";
    } else {
        //HTML format
        $id = 'IntentOverview';
        return '<div class="panel-group" id="open'.$id.'" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading'.$id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open'.$id.'" href="#collapse'.$id.'" aria-expanded="false" aria-controls="collapse'.$id.'">
                    <i class="fas" style="transform:none !important;">💡</i> '.$c['c__tree_all_count'].' Insights<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                </a>
            </h4>
        </div>
        <div id="collapse'.$id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading'.$id.'">
            <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">'.$pitch.'</div>
        </div>
    </div></div>';
    }
}

function echo_completion_estimate($c, $fb_format=0){
    $pitch = 'Action Plan estimates that it will take '.strtolower(echo_hour_range($c)).' to '.$c['c_outcome'].'.';
    if($fb_format) {
        return '⏰ '.$pitch."\n";
    } else {
        //HTML format
        $id = 'EstimatedTime';
        return '<div class="panel-group" id="open'.$id.'" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading'.$id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open'.$id.'" href="#collapse'.$id.'" aria-expanded="false" aria-controls="collapse'.$id.'">
                        <i class="fas" style="transform:none !important;">⏰</i> '.ucwords(echo_hour_range($c)).'<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse'.$id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading'.$id.'">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">'.$pitch.'</div>
            </div>
        </div></div>';
    }
}

function echo_experts($c, $fb_format=0){

    //Do we have any intents?
    if(strlen($c['c__tree_experts'])<=0){
        return false;
    }

    //Make initial variables:
    $c['c__tree_experts'] = unserialize($c['c__tree_experts']);
    $all_count = count($c['c__tree_experts']);
    if($all_count==0){
        //Should never happen since strlen($c['c__tree_experts'])>0
        return false;
    }


    $visible_html = 4; //Landing page, beyond this is hidden and visible with a click
    $visible_bot = 10; //Plain text style, but beyond this is cut out!
    $edit_enabled = auth(array(1281),0);
    $text_overview = '';

    foreach($c['c__tree_experts'] as $count=>$u){

        $is_last_fb_item = ($fb_format && $count>=$visible_bot);

        if($count>0){
            if(($count+1)>=$all_count || $is_last_fb_item){
                $text_overview .= ' &';
                if($is_last_fb_item){
                    $text_overview .= ' '.($all_count-$visible_bot).' more!';
                    break;
                }
            } else {
                $text_overview .= ',';
            }
        }

        $text_overview .= ' ';

        if($fb_format){

            //Just the name:
            $text_overview .= $u['u_full_name'];

        } else {

            //HTML Format:
            if($edit_enabled){
                $text_overview .= '<a href="/entities/'.$u['u_id'].'">';
            }

            if(isset($u['u_bio']) && strlen($u['u_bio'])>0){
                //Has description, show it here:
                $text_overview .= ' <span data-toggle="tooltip" title="'.stripslashes($u['u_bio']).'" data-placement="top" class="underdot" style="display:inline-block;">'.$u['u_full_name'].'</span>';
            } else {
                //Just the name:
                $text_overview .= $u['u_full_name'];
            }

            if($edit_enabled){
                $text_overview .= '</a>';
            }

            if(($count+1)==$visible_html && ($all_count-$visible_html)>0){
                $text_overview .= '<span class="show_more_'.$c['c_id'].'"> & <a href="javascript:void(0);" onclick="$(\'.show_more_'.$c['c_id'].'\').toggle()" style="text-decoration:underline;">'.($all_count-$visible_html).' more</a>.</span><span class="show_more_'.$c['c_id'].'" style="display:none;">';
            }
        }
    }

    if(!$fb_format && ($count+1)>=$visible_html){
        //Close the span:
        $text_overview .= '.</span>';
    } elseif($fb_format && !$is_last_fb_item){
        //Close the span:
        $text_overview .= '.';
    }



    $pitch = 'Action Plan quotes '.$all_count.' industry expert'.echo__s($all_count).($all_count==1 ? ':' : ' including' ).$text_overview;
    if($fb_format) {
        return '🎓 '.$pitch."\n";
    } else {
        //HTML format
        $id = 'IndustryExperts';
        return '<div class="panel-group" id="open'.$id.'" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading'.$id.'">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open'.$id.'" href="#collapse'.$id.'" aria-expanded="false" aria-controls="collapse'.$id.'">
                        <i class="fas" style="transform:none !important;">🎓</i> '.$all_count.' Industry Expert'.echo__s($all_count).'<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse'.$id.'" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="heading'.$id.'">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">
                    '.$pitch.' <span style="font-size: 1em !important;">They are not affiliated with Mench, yet their work has been referenced by our training team.</span>
                </div>
            </div>
        </div></div>';
    }
}



function echo_hour_range($c, $micro=false){

    if(round($c['c__tree_max_hours'],1)==round($c['c__tree_min_hours'],1)){
        //Exactly the same, show a single value:
        return echo_hours($c['c__tree_max_hours'],$micro);
    } elseif(round($c['c__tree_max_hours'])==round($c['c__tree_min_hours']) || $c['c__tree_min_hours']<1){
        if($c['c__tree_min_hours']<2 && $c['c__tree_max_hours']<3 && ($c['c__tree_max_hours']- $c['c__tree_min_hours'])*60>30){
            $is_minutes = true;
        } elseif($c['c__tree_min_hours']<10){
            $is_minutes = false;
            $hours_decimal = 1;
        } else {
            //Number too large to matter, just treat as one:
            return echo_hours($c['c__tree_max_hours'],$micro);
        }
    } else {
        $is_minutes = false;
        $hours_decimal = 0;
    }

    //Generate hours range:
    $ui_time = ($is_minutes ? round($c['c__tree_min_hours']*60) : round($c['c__tree_min_hours'], $hours_decimal) );
    $ui_time .= '-';
    $ui_time .= ($is_minutes ? round($c['c__tree_max_hours']*60) : round($c['c__tree_max_hours'], $hours_decimal) );
    $ui_time .= ($is_minutes ? ($micro?'m':' Minutes') : ($micro?'h':' Hours') );

    //Generate UI to return:
    return $ui_time;
}




function echo_object($object,$id,$engagement_field,$button_type){


    //Loads the name (and possibly URL) for $object with id=$id
    $CI =& get_instance();
    $id = intval($id);

    if($id>0){
        if($object=='c'){

            $is_inbound = ( $engagement_field=='e_inbound_c_id' ? true : false );
            //Fetch intent/Step:
            $cs = $CI->Db_model->c_fetch(array(
                'c.c_id' => $id,
            ));
            if(isset($cs[0])){
                if(!$button_type){
                    //Plain view:
                    return '<a href="https://mench.com/intents/'.$cs[0]['c_id'].'">'.$cs[0]['c_outcome'].'</a>';
                } else {
                    return '<a href="/intents/'.$cs[0]['c_id'].'" target="_parent" class="badge badge-primary" style="width:40px;" data-toggle="tooltip" data-placement="left" title="'.$button_type.': '.stripslashes($cs[0]['c_outcome']).'"><i class="'.( $is_inbound ? 'fas fa-sign-in-alt' : 'fas fa-sign-out-alt rotate90' ).'"></i></a> ';

                }
            }
        } elseif($object=='i'){

            if(!$button_type){
                //Plain view:
                return '#'.$id;
            } else {
                return NULL;
            }

        } elseif($object=='u'){

            $is_inbound = ( $engagement_field=='e_inbound_u_id' ? true : false );

            if($id<=0){
                return 'System';
            } else {
                $matching_users = $CI->Db_model->u_fetch(array(
                    'u_id' => $id,
                ));
                if(isset($matching_users[0])){
                    if(!$button_type){
                        //Plain view:
                        return '<a href="https://mench.com/entities/'.$id.'" title="Entity ID '.$id.'">'.$matching_users[0]['u_full_name'].'</a>';
                    } else {
                        return '<a href="/entities/'.$id.'" target="_parent" class="badge badge-secondary" style="width:40px;" data-toggle="tooltip" data-placement="left" title="'.$button_type.': '.stripslashes($matching_users[0]['u_full_name']).'">'.echo_cover($matching_users[0], 'profile-icon2').'</a> ';
                    }
                }
            }

        } elseif($object=='x' && $id>0){

            $matching_urls = $CI->Db_model->x_fetch(array(
                'x_id' => $id,
            ));
            if(isset($matching_urls[0])){
                if(!$button_type){
                    //Plain view:
                    return '<a href="'.$matching_urls[0]['x_url'].'" target="_blank" title="Reference ID '.$id.'">'.echo_clean_url($matching_urls[0]['x_url']).'</a>';
                } else {
                    return '<a href="'.$matching_urls[0]['x_url'].'" target="_blank" class="badge badge-secondary" style="width:40px;">'.echo_status('x_status',$matching_urls[0]['x_status'], true, 'top').'</a> ';
                }
            }

        }
        //We would not do the other engagement types...
    }

    //Still here? Return default:
    if($id>0){
        if(!$button_type){
            //Plain view:
            return '#'.$id;
        } else {
            return '<span class="badge badge-primary grey" data-toggle="tooltip" data-placement="left" title="'.$button_type.' #'.$id.'"><i class="fas fa-question-circle"></i></span> ';
        }
    } else {
        return NULL;
    }
}



function echo_diff_time($t,$second_time=null){
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

function echo_time($t,$format=0,$adjust_seconds=0){
    if(!$t){
        return 'NOW';
    }

    $timestamp = ( is_numeric($t) ? $t : strtotime(substr($t,0,19)) ) + $adjust_seconds; //Added this last part to consider the end of days for dates
    $year = ( date("Y")==date("Y",$timestamp) );
    if($format==0){
        return date(( $year ? "M j, g:i a" : "M j, Y, g:i a" ),$timestamp);
    } elseif($format==1){
        return date(( $year ? "j M" : "j M Y" ),$timestamp);
    } elseif($format==2){
        return date(( $year ? "D M j " : "j M Y" ),$timestamp);
    } elseif($format==3){
        return $timestamp;
    } elseif($format==4){
        return date(( $year ? "M j" : "M j Y" ),$timestamp);
    } elseif($format==5){
        return date(( $year ? "D M j" : "D M j Y" ),$timestamp);
    } elseif($format==6){
        return date("Y/m/d",$timestamp);
    } elseif($format==7){
        return date(( $year ? "D M j, g:i a" : "D M j, Y, g:i a" ),$timestamp);
    } elseif($format==8){
        return date(( $year ? "M j" : "M j Y" ),$timestamp);
    }
}



function echo_status($object=null,$status=null,$micro_status=false,$data_placement='bottom'){

    //IF you make any changes, make sure to also reflect in the echo_status.php as well
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
            if(is_null($data_placement) && $micro_status){
                return ( isset($result['s_icon']) ? '<i class="'.$result['s_icon'].' initial"></i> ' : '<i class="fas fa-sliders-h initial"></i> ' );
            } else {
                return '<span class="status-label" '.( (isset($result['s_desc']) || $micro_status) && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="'.$data_placement.'" title="'.($micro_status ? $CI->lang->line('obj_'.$object.'_name').' is '.$result['s_name'] : '').( isset($result['s_desc']) ? ($micro_status ? ': ' : '').$result['s_desc'] : '' ).'" style="border-bottom:1px dotted #444; padding-bottom:1px;"':'style="cursor:pointer;"').'>'.( isset($result['s_icon']) ? '<i class="'.$result['s_icon'].' initial"></i>' : '<i class="fas fa-sliders-h initial"></i>' ).' '.($micro_status?'':$result['s_name']).'</span>';
            }

        }
    }
}

function echo_featured_c($c){
    $ui = '<a href="/'.$c['c_id'].'" class="list-group-item">';

    $ui .= '<span class="pull-right">';
    $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-right"></i></span>';
    $ui .= '</span>';

    $ui .= $c['c_outcome'];
    $ui .= '<span style="font-size:0.8em; font-weight:300; margin-left:5px; display:inline-block;">';
    //$ui .= ( $c['c__tree_all_count']>0 ? '<span style="padding-right:5px;"><i class="fas fa-lightbulb-on"></i>'.$c['c__tree_all_count'].'</span>' : '' );
    $ui .= '<span><i class="fas fa-clock"></i>'.echo_hour_range($c, false).'</span>';
    $ui .= '</span>';
    $ui .= '</a>';
    return $ui;
}

function echo_estimated_time($c_time_estimate,$show_icon=1,$micro=false,$c_id=0,$c_time_intent=0){

    if($c_time_estimate>0 || $c_id){

        $ui = '<span class="title-sub" style="text-transform:none !important;">';

        if($c_id){

            $ui .= '<span class="slim-time t_estimate_'.$c_id.'" tree-hours="'.$c_time_estimate.'" intent-hours="'.$c_time_intent.'">'.echo_hours( $c_time_estimate,true).'</span>';

            if($show_icon){
                $ui .= ' <i class="fas fa-clock"></i>';
            }

        } else {

            if($show_icon){

                $ui .= '<i class="fas fa-clock"></i>';
            }
            if($c_time_estimate<1){
                //Minutes:
                $ui .= round($c_time_estimate*60).($micro?'m':' Minutes');
            } else {
                //Hours:
                $ui .= round($c_time_estimate,0).($micro?'h':' Hour'.(round($c_time_estimate,1)==1?'':'s'));
            }
        }

        $ui .= '</span>';
        return $ui;
    }

    //No time:
    return null;

}

function echo_mili($microtime){
    $time = $microtime/1000;
    echo date("Y-m-d H:i:s", floor($time)).'.'.one_two_explode('.','',$time);
}


function echo_c($c, $level, $c_inbound_id=0, $is_inbound=false){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    //Count engagements for this intent:
    $e_count = count($CI->Db_model->e_fetch(array(
        '(e_inbound_c_id='.$c['c_id'].' OR e_outbound_c_id='.$c['c_id'].')' => null,
    ), $CI->config->item('max_counter')));

    //Count subscription caches for this intent link:
    $k_stats = array(
        'k_all' => 0,
        'k_completed' => 0,
    );

    //Fetch K stats:
    $k_stat_fetch = $CI->Db_model->k_fetch(array(
        'cr_outbound_c_id' => $c['c_id'],
    ), array('cr'), array(), 0, 'k_status, COUNT(k_id) as cr_count', 'k_status');
    foreach($k_stat_fetch as $ks){
        $k_stats['k_all'] += $ks['cr_count'];
        //Calculate real completion:
        if($ks['k_status']>=2){
            $k_stats['k_completed'] += $ks['cr_count'];
        }
    }


    if($level==1){

        //Bootcamp Outcome:
        $ui = '<div class="list-group-item">';

    } else {

        //ATTENTION: DO NOT CHANGE THE ORDER OF data-link-id & intent-id AS the sorting logic depends on their exact position to sort!
        //CHANGE WITH CAUTION!
        $ui = '<div id="cr_'.$c['cr_id'].'" data-link-id="'.$c['cr_id'].'" intent-id="'.$c['c_id'].'" parent-intent-id="'.$c_inbound_id.'" intent-level="'.$level.'" class="list-group-item '.( $level==3 ? 'is_level3_sortable' : 'is_level2_sortable' ).' intent_line_'.$c['c_id'].'">';

    }

    //Right content
    $ui .= '<span class="pull-right" style="'.( $level<3 ? 'margin-right: 8px;' : '' ).'">';

    //TODO show intent status here

    //Show submission stats
    if($k_stats['k_all']>0){
        //Show link to load these intents in user subscriptions:
        $ui .= '<a href="#kcache-'.$c['c_id'].'" onclick="kcache_load('.$c['c_id'].')" class="badge badge-primary" style="width:40px; margin-right:2px;" data-toggle="tooltip" title="'.$k_stats['k_completed'].'/'.$k_stats['k_all'].' marked as complete across all Action Plans" data-placement="top"><span class="btn-counter">'.round($k_stats['k_completed']/$k_stats['k_all']*100).'%</span><i class="fas fa-flag" style="font-size:0.85em;"></i></a>';
    }

    if($e_count>0){
        //Show link to load these engagements:
        $ui .= '<a href="#estats-'.$c['c_id'].'" onclick="estats_load('.$c['c_id'].')" class="badge badge-primary" style="width:40px; margin-right:2px;"><span class="btn-counter">'.$e_count.($e_count==$CI->config->item('max_counter')?'+':'').'</span><i class="fas fa-exchange"></i></a>';
    }

    $ui .= '<a href="#messages-'.$c['c_id'].'" onclick="i_load_modify('.$c['c_id'].')" class="msg-badge-'.$c['c_id'].' badge badge-primary '.( $c['c__this_messages']==0 ? 'grey' : '' ).'" style="width:40px;"><span class="btn-counter messages-counter-'.$c['c_id'].'">'.$c['c__this_messages'].'</span><i class="fas fa-comment-dots"></i></a>';

    $ui .= '<a class="badge badge-primary" onclick="load_c_modify('.$c['c_id'].','.( isset($c['cr_id']) ? $c['cr_id'] : 0 ).')" style="margin:-2px -8px 0 2px; width:40px;" href="#modify-'.$c['c_id'].'-'.( isset($c['cr_id']) ? $c['cr_id'] : 0 ).'"><span class="btn-counter">'.echo_estimated_time($c['c__tree_max_hours'],0,1, $c['c_id'], $c['c_time_estimate']).'</span><i class="c_is_any_icon'.$c['c_id'].' '.( $c['c_is_any'] ? 'fas fa-code-merge' : 'fas fa-sitemap' ).'" style="font-size:0.9em; width:28px; padding-right:3px; text-align:center;"></i></a> &nbsp;';

    //Show link to travel down the tree:
    $ui .= '&nbsp;<a href="/intents/'.$c['c_id'].'" class="tree-badge-'.$c['c_id'].' badge badge-primary '.( $c['c__tree_all_count']<=1 ? 'grey' : '' ).'" style="display:inline-block; margin-right:-1px; width:40px;"><span class="btn-counter outbound-counter-'.$c['c_id'].' '.( $is_inbound && $level==2 ? 'inb-counter' : '' ).'">'.$c['c__tree_all_count'].'</span><i class="'.( $is_inbound && $level<=2 ? 'fas fa-sign-in-alt' : 'fas fa-sign-out-alt rotate90' ).'"></i></a> ';

    //Keep an eye out for inner message counter changes:
    $ui .= '</span> ';



    $c_settings = ' c_require_url_to_complete="'.$c['c_require_url_to_complete'].'" c_require_notes_to_complete="'.$c['c_require_notes_to_complete'].'" c_cost_estimate="'.$c['c_cost_estimate'].'" c_is_any="'.$c['c_is_any'].'" ';


    //Sorting & Then Left Content:
    if($level>1 && (!$is_inbound || $level==3)) {
        $ui .= '<i class="fas fa-bars"></i> &nbsp;';
    }


    if($level==1){

        //Bootcamp Outcome:
        $ui .= '<span><b id="b_objective" style="font-size: 1.3em;">';
        $ui .= '<span class="c_outcome_'.$c['c_id'].'" '.$c_settings.'>'.$c['c_outcome'].'</span>';
        $ui .= '</b></span>';
        $ui .= ' <span class="obj-id underdot" data-toggle="tooltip" data-placement="top" title="Intent ID">#' . $c['c_id'] . '</span>';

        //Give option to update the cache:
        $ui .= ' <a href="/cron/intent_sync/'.$c['c_id'].'/1?redirect=/'.$c['c_id'].'" onclick="turn_off()" data-toggle="tooltip" title="Updates Intent tree cache which controls landing page counters for intent, hours, content types and industry expert" data-placement="top"><i class="fas fa-sync-alt"></i></a>';

        //Show Landing Page URL:
        $ui .= ' <a href="/'.$c['c_id'].'" data-toggle="tooltip" title="Open Landing Page with Intent tree overview & Messenger subscription button" data-placement="top"><i class="fas fa-shopping-cart"></i></a>';


        //Additional details about this intent?
        $ui .= '<div style="padding-top: 5px;">';
        if(in_array($c['c_id'],$CI->config->item('featured_cs'))) {
            //$ui .= '<b data-toggle="tooltip" title="Intention is featured on landing pages under [Related Intentions]" class="underdot" data-placement="top" style="color:#0000FF !important;"><i class="fas fa-bullhorn"></i> FEATURED</b>';
        }
        if(in_array($c['c_id'],$CI->config->item('onhold_intents'))) {
            $ui .= ' &nbsp;&nbsp;<b data-toggle="tooltip" title="Intention is on-hold and not publicly accessible" class="underdot" data-placement="top" style="color:#FF0000 !important;"><i class="fas fa-exclamation-triangle"></i> ON HOLD</b>';
        }
        $ui .= '</div>';

    } elseif($level==2){

        //Task:
        $ui .= '<span class="inline-level">';

        $ui .= '<a href="javascript:ms_toggle('.$c['cr_id'].');"><i id="handle-'.$c['cr_id'].'" class="fal fa-plus-square"></i></a> &nbsp;';

        if(!$is_inbound){
            $ui .= '<span class="inline-level-'.$level.'">#'.$c['cr_outbound_rank'].'</span>';
        }
        $ui .= '</span>';

        $ui .= '<span id="title_'.$c['cr_id'].'" class="cdr_crnt c_outcome_'.$c['c_id'].'" outbound-rank="'.$c['cr_outbound_rank'].'" '.$c_settings.'>'.$c['c_outcome'].'</span> ';

    } elseif ($level==3){

        //Steps
        $ui .= '<span class="inline-level inline-level-'.$level.'">#'.$c['cr_outbound_rank'].'</span>';
        $ui .= '<span id="title_'.$c['cr_id'].'" class="c_outcome_'.$c['c_id'].'" outbound-rank="'.$c['cr_outbound_rank'].'" '.$c_settings.'>'.$c['c_outcome'].'</span> ';

    }


    //Any Tree?
    if($level==2){

        $ui .= '<div id="list-cr-'.$c['cr_id'].'" class="cr-class-'.$c['cr_id'].' list-group step-group hidden list-level-3" intent-id="'.$c['c_id'].'">';
        //This line enables the in-between list moves to happen for empty lists:
        $ui .= '<div class="is_level3_sortable dropin-box" style="height:1px;">&nbsp;</div>';


        if(isset($c['c__child_intents']) && count($c['c__child_intents'])>0){
            foreach($c['c__child_intents'] as $key=>$sub_intent){
                $ui .= echo_c($sub_intent, ($level+1), $c['c_id'], $is_inbound);
            }
        }


        //Step Input field:
        $ui .= '<div class="list-group-item list_input new-step-input">
            <div class="input-group">
                <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="new_intent('.$c['c_id'].',3);" intent-id="'.$c['c_id'].'"><input type="text" class="form-control autosearch intentadder-level-3 algolia_search" maxlength="'.$CI->config->item('c_outcome_max').'" id="addintent-cr-'.$c['cr_id'].'" intent-id="'.$c['c_id'].'" placeholder="Add #Intent"></form></div>
                <span class="input-group-addon" style="padding-right:8px;">
                    <span data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" onclick="new_intent('.$c['c_id'].',3);" class="badge badge-primary pull-right" intent-id="'.$c['c_id'].'" style="cursor:pointer; margin: 13px -6px 1px 13px;">
                        <div><i class="fas fa-plus"></i></div>
                    </span>
                </span>
            </div>
        </div>';


        $ui .= '</div>';
    }


    $ui .= '</div>';
    return $ui;

}



function echo_u($u, $level, $can_edit, $is_inbound=false){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $status_index = $CI->config->item('object_statuses');
    $ur_id = ( isset($u['ur_id']) ? $u['ur_id'] : 0 );
    $ui = null;


    $ui .= '<div id="u_'.$u['u_id'].'" entity-id="'.$u['u_id'].'" entity-email="'.$u['u_email'].'" entity-bio="'.str_replace('"','\\"',$u['u_bio']).'" entity-status="'.$u['u_status'].'" has-password="'.( strlen($u['u_password'])>0 ? 1 : 0 ).'" is-inbound="'.( $is_inbound ? 1 : 0 ).'" class="list-group-item u-item u__'.$u['u_id'].' '.( $level==1 ? 'top_entity' : 'ur_'.$u['ur_id'] ).'">';

    //Right content:
    $ui .= '<span class="pull-right">';

    //Count messages:
    $messages = $CI->Db_model->i_fetch(array(
        'i_status >=' => 0,
        'i_outbound_u_id' => $u['u_id'], //Referenced content in messages
    ));

    //What's the entity status?
    $ui .= '<span class="u-status-bar-'.$u['u_id'].'">';
    if(array_key_exists(1281, $udata['u__inbounds']) && $u['u_status']==0){

        $ui .= '<i class="'.$status_index['u'][1]['s_icon'].'"></i> ';
        $ui .= '<a href="javascript:update_u_status('.$u['u_id'].',1)" data-toggle="tooltip" data-placement="left" title="Current status is '.$status_index['u'][$u['u_status']]['s_name'].'. Click to update entity status to '.$status_index['u'][1]['s_name'].': '.$status_index['u'][1]['s_desc'].'" style="text-decoration:underline;">Set '.$status_index['u'][1]['s_name'].'</a>';

    } elseif(array_key_exists(1281, $udata['u__inbounds']) && $u['u_status']==1){

        $ui .= '<i class="'.$status_index['u'][2]['s_icon'].'" style="font-size:0.9em;"></i> ';
        $ui .= '<a href="javascript:update_u_status('.$u['u_id'].',2)" data-toggle="tooltip" data-placement="left" title="Current status is '.$status_index['u'][$u['u_status']]['s_name'].'. Click to mark entity as '.$status_index['u'][2]['s_name'].': '.$status_index['u'][2]['s_desc'].'" style="text-decoration:underline;">Set '.$status_index['u'][2]['s_name'].'</a>';

    } elseif($u['u_status']==2){

        //Show verified status:
        $ui .= echo_status('u',2, true, 'left');

    }
    $ui .= '</span> ';


    //Check total key engagement for this user:
    $e_count = count($CI->Db_model->e_fetch(array(
        '(e_inbound_u_id='.$u['u_id'].' OR e_outbound_u_id='.$u['u_id'].')' => null,
        '(e_inbound_c_id NOT IN ('.join(',', $CI->config->item('exclude_es')).'))' => null,
    ), $CI->config->item('max_counter')));
    if($e_count>0){
        //Show the engagement button:
        $ui .= '<a href="#wengagements-'.$u['u_id'].'" onclick="load_u_engagements('.$u['u_id'].')" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="'.$e_count.' entity engagements"><span class="btn-counter">'.$e_count.( $e_count==$CI->config->item('max_counter') ? '+' : '').'</span><i class="fas fa-exchange"></i></a>';
    }


    $ui .= '<'.( count($messages)>0 ? 'a href="#messages-'.$u['u_id'].'" onclick="load_u_messages('.$u['u_id'].')" class="badge badge-secondary"' : 'span class="badge badge-secondary grey"' ).' style="width:40px;">'.( count($messages)>0 ? '<span class="btn-counter">'.count($messages).'</span>' : '' ).'<i class="fas fa-comment-dots"></i></'.( count($messages)>0 ? 'a' : 'span' ).'>';


    $ui .= '<'.( $can_edit ? 'a href="#modify-'.$u['u_id'].'-'.$ur_id.'" onclick="load_u_modify('.$u['u_id'].','.$ur_id.')" class="badge badge-secondary"' : 'span class="badge badge-secondary grey"' ).' style="margin:-2px -6px 0 2px; width:40px;">'.( $u['u__e_score']>0 ? '<span class="btn-counter" data-toggle="tooltip" data-placement="left" title="Engagement Score">'.echo_big_num($u['u__e_score']).'</span>' : '' ).'<i class="fas fa-sitemap" style="font-size:0.9em; width:28px; padding-right:3px; text-align:center;"></i></'.( $can_edit ? 'a' : 'span' ).'> &nbsp;';

    $ui .= '<a class="badge badge-secondary" href="/entities/'.$u['u_id'].'" style="display:inline-block; margin-right:6px; width:40px; margin-left:1px;">'.(isset($u['u__outbound_count']) && $u['u__outbound_count']>0 ? '<span class="btn-counter '.( $level==1 ? 'li-outbound-count' : '' ).'">'.$u['u__outbound_count'].'</span>' : '').'<i class="'.( $is_inbound ? 'fas fa-sign-in-alt' : 'fas fa-sign-out-alt rotate90' ).'"></i></a>';

    $ui .= '</span>';


    if($level==1){

        //Regular section:
        $ui .= echo_cover($u, 'profile-icon2');
        $ui .= '<b id="u_title" class="u_full_name u_full_name_'.$u['u_id'].'">' . $u['u_full_name'] . '</b>';
        $ui .= ' <span class="obj-id underdot" data-toggle="tooltip" data-placement="top" title="Entity ID">@' . $u['u_id'] . '</span>';
        $ui .= ' <a href="https://www.google.com/search?q='.urlencode($u['u_full_name']).'" target="_blank" data-toggle="tooltip" title="Search on Google" data-placement="top"><i class="fab fa-google"></i></a>';

        //Visibly show bio for level 1:
        $ui .= '<div class="u_bio_'.$u['u_id'].'" style="margin-top:2px;">' . nl2br($u['u_bio']) . '</div>';

    } else {

        //Regular section:
        $ui .= echo_cover($u,'micro-image', 1).' ';
        $ui .= '<span class="u_full_name u_full_name_'.$u['u_id'].( strlen($u['u_bio'])>0 ? ' has-desc ' : '' ).'" data-toggle="tooltip" data-placement="right" title="'.$u['u_bio'].'">'.$u['u_full_name'].'</span>';

    }


    $ui .= '</div>';

    return $ui;

}



function echo_json($array){
    header('Content-Type: application/json');
    echo json_encode($array);
}





function echo_ordinal($number){
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($number %100) >= 11 && ($number%100) <= 13){
        return $number. 'th';
    } else {
        return $number. $ends[$number % 10];
    }
}

function echo__s($count,$is_es=0){
    return ( $count==1?'':( $is_es ? 'es' : 's'));
}


function echo_dropdown_status($object,$input_name,$current_status_id,$exclude_ids=array(),$direction='dropdown',$mini=0){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $inner_tooltip = ($direction=='dropup'?null:'top');

    if(is_array($object)){
        $statuses = $object;
    } else {
        $statuses = echo_status($object,null,false,'bottom');
    }
    $now = echo_status($object,$current_status_id,$mini,$inner_tooltip);

    $return_ui = '';
    $return_ui .= '<input type="hidden" id="'.$input_name.'" value="'.$current_status_id.'" />';
    $return_ui .= '<div style="display:inline-block;" class="'.$direction.'">';
    $return_ui .= '<a href="#" style="margin: 0; background-color:#FFF;" class="btn btn-simple dropdown-toggle border" id="ui_'.$input_name.'" data-toggle="dropdown">';
    $return_ui .= ( $now ? $now : 'Select...' );
    $return_ui .= '<b class="caret"></b></a><ul class="dropdown-menu">';

    $count = 0;
    foreach($statuses as $intval=>$status){
        if(in_array($intval,$exclude_ids)){
            continue;
        }
        $count++;
        $return_ui .= '<li><a href="javascript:update_dropdown(\''.$input_name.'\','.$intval.','.$count.');">'.echo_status($object,$intval,0,$inner_tooltip).'</a></li>';
        $return_ui .= '<li style="display:none;" class="'.$input_name.'_'.$intval.'" id="'.$input_name.'_'.$count.'">'.echo_status($object,$intval,$mini,$inner_tooltip).'</li>'; //For UI replacement
    }
    $return_ui .= '</ul></div>';
    return $return_ui;
}

