<?php





function echo_next_u($page,$limit,$u__outbound_count){
    //We have more child entities than what was listed here.
    //Give user a way to access them:
    echo '<a class="load-more list-group-item" href="javascript:void(0);" onclick="entity_load_more('.$page.')">';

    //Right content:
    echo '<span class="pull-right"><span class="badge badge-primary"><i class="fas fa-search-plus"></i></span></span>';

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
    $can_edit = ($udata['u_id']==$u['u_id'] || $udata['u_inbound_u_id']==1281);

    $ui = null;
    $ui .= '<div id="x_'.$x['x_id'].'" class="list-group-item url-item">';

    //Right content:
    $ui .= '<span class="pull-right">';

    if($can_edit && strlen($x['x_clean_url'])>0 && !($x['x_url']==$x['x_clean_url'])){
        //We have detected a different URL behind the scene:
        $ui .= '<a class="badge badge-primary" href="'.$x['x_clean_url'].'" target="_blank" data-toggle="tooltip" data-placement="left" title="Redirects to another URL"><i class="fas fa-route"></i></a> ';
    }

    //This is an image and can be set as Cover photo, or may have already been set so...
    if($x['x_id']==$u['u_cover_x_id']){
        //Already set as the cover photo:
        $ui .= '<span class="badge badge-primary grey current-cover" data-toggle="tooltip" data-placement="left" title="Currently set as Cover Photo"><i class="fas fa-file-check"></i></span> ';
    } elseif($x['x_id']==$u['u_booking_x_id']){
        //Already set as the cover photo:
        $ui .= '<span class="badge badge-primary grey current-cover" data-toggle="tooltip" data-placement="left" title="Currently set as Booking URL"><i class="fas fa-clock"></i></span> ';
    } elseif($x['x_type']==4 && $x['x_status']>0 && $can_edit){
        //Could be set as the cover photo:
        $ui .= '<a class="badge badge-primary add-cover" href="javascript:void(0);" onclick="x_cover_set('.$x['x_id'].')" data-toggle="tooltip" data-placement="left" title="Set this image as Cover Photo"><i class="fas fa-file-image"></i></a> ';
    }

    //User can always remove a URL:
    if($can_edit){
        $ui .= '<a class="badge badge-primary" href="javascript:void(0);" onclick="x_delete('.$x['x_id'].')" data-toggle="tooltip" data-placement="left" title="Delete this URL"><i class="fas fa-trash-alt" title="ID '.$x['x_id'].'"></i></a>';
    }

    $ui .= '</span>';


    //Regular section:
    $ui .= '<a href="'.$x['x_url'].'" target="_blank" '.( strlen($x['x_url'])>0 && !($x['x_url']==$x['x_url']) ? '' : '' ).'>';
    $ui .= '<span class="url_truncate">'.echo_clean_url($x['x_url']).'</span>';

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


function echo_u($u){

    $ui = null;
    $ui = null;
    $ui .= '<div id="u_'.$u['u_id'].'" entity-id="'.$u['u_id'].'" class="list-group-item u-item">';

    //Right content:
    $ui .= '<span class="pull-right">';
    $ui .= echo_score($u['u_e_score']);

    $ui .= '<a class="badge badge-primary" href="/entities/'.$u['u_id'].'/modify" style="margin:-2px 3px 0 0;"><i class="fas fa-cog"></i></a>';

    $ui .= '<a class="badge badge-primary" href="/entities/'.$u['u_id'].'" data-toggle="tooltip" data-placement="left" title="" data-original-title="Entity tree contains '.$u['u__outbound_count'].' child entities. Click to browse."><span class="btn-counter">'.(isset($u['u__outbound_count']) ? echo_big_num($u['u__outbound_count']).' ' : '').'</span><i class="fas fa-sitemap"></i></a>';

    $ui .= '</span>';

    //Regular section:
    $ui .= echo_cover($u,'micro-image', ( in_array($u['u_inbound_u_id'], array(1280,1279,1307,1281,1308,1304,1282)) ? 1 : 0 )).' ';
    if(strlen($u['u_bio'])>0){
        $ui .= '<span data-toggle="tooltip" data-placement="right" title="'.$u['u_bio'].'" style="border-bottom:1px dotted #3C4858; cursor:help;">'.$u['u_full_name'].'</span>';
    } else {
        $ui .= $u['u_full_name'];
    }

    $ui .= '</div>';

    return $ui;
}


function echo_ru($ru){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $can_edit = ($udata['u_inbound_u_id']==1281);

    echo '<div class="list-group-item">';

    //Right content:
    echo '<span class="pull-right">';
    echo echo_status('ru', $ru['ru_status'], true, 'left').' &nbsp;';
    if($can_edit){
        echo '<a class="badge badge-primary" href="/console/'.$ru['ru_b_id'].( $ru['ru_r_id']>0 ? '/classes#class-'.$ru['ru_r_id'] : '' ).'"><i class="fas fa-chevron-right"></i></a>';
    }
    echo '</span>';

    //Regular section:
    echo '<i class="fas fa-cube"></i> '.$ru['c_outcome'];

    echo '</div>';
}

function echo_ba($ba){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $can_edit = ($udata['u_id']==$ba['ba_outbound_u_id'] || $udata['u_inbound_u_id']==1281);

    echo '<div class="list-group-item">';

    //Right content:
    echo '<span class="pull-right">';
    echo echo_status('ba',$ba['ba_status'], true, 'left').' &nbsp;';
    if($can_edit){
        echo '<a class="badge badge-primary" href="/console/'.$ba['ba_b_id'].'"><i class="fas fa-chevron-right"></i></a>';
    }
    echo '</span>';

    //Regular section:
    echo '<i class="fas fa-cube"></i> '.$ba['c_outcome'];

    echo '</div>';
}

function echo_t($t){
    echo '<div class="list-group-item">';

    //Right content:
    echo '<span class="pull-right">';
    echo '<a class="badge badge-primary stnd-btn" href="https://www.paypal.com/activity/payment/'.$t['t_paypal_id'].'" target="_blank"><i class="fab fa-paypal"></i> <i class="fas fa-external-link-square"></i></a>';
    echo '</span>';

    //Regular section:
    echo $t['t_total'].' '.$t['t_currency'];

    echo '</div>';
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

function echo_embed($url,$full_message=null,$require_image=false,$return_array=false){

    //$require_image is for Finding the cover photo in YouTube content

    $clean_url = null;
    $embed_code = null;
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

            if($require_image){

                $embed_code = '<img src="https://img.youtube.com/vi/'.$video_id.'/0.jpg" class="yt-container" style="padding-bottom:0; margin:-28px 0px;" />';

            } else {
                //We might also find these in the URL:
                $start_sec = 0;
                $end_sec = 0;
                if(substr_count($url,'start=')>0){
                    $start_sec = intval(one_two_explode('start=','&',$url));
                    $clean_url = $clean_url.'&start='.$start_sec;
                }
                if(substr_count($url,'end=')>0){
                    $end_sec = intval(one_two_explode('end=','&',$url));
                    $clean_url = $clean_url.'&end='.$end_sec;
                }

                //Inform Student that this video has been sliced:
                if($start_sec || $end_sec){
                    $embed_code .= '<div class="video-prefix"><i class="fab fa-youtube" style="color:#ff0202;"></i> Watch from <b>'.($start_sec ? echo_min_from_sec($start_sec) : 'start').'</b> to <b>'.($end_sec ? echo_min_from_sec($end_sec) : 'end').'</b>:</div>';
                }

                $embed_code .= '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/'.$video_id.'?theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start='.$start_sec.( $end_sec ? '&end='.$end_sec : '' ).'" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';
            }

        }

    } elseif(substr_count($url,'vimeo.com/')==1 && !$require_image){

        //Seems to be Vimeo:
        $video_id = trim(one_two_explode('vimeo.com/','?',$url));

        //This should be an integer!
        if(intval($video_id)==$video_id){
            $clean_url = 'https://vimeo.com/'.$video_id;
            $embed_code = '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://player.vimeo.com/video/'.$video_id.'?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
        }

    } elseif(substr_count($url,'wistia.com/medias/')==1 && !$require_image){

        //Seems to be Wistia:
        $video_id = trim(one_two_explode('wistia.com/medias/','?',$url));
        $clean_url = trim(one_two_explode('','?',$url));
        $embed_code = '<script src="https://fast.wistia.com/embed/medias/'.$video_id.'.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding video-sorting" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_'.$video_id.' seo=false videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>';

    }

    if($return_array){

        //Return all aspects of this parsed URL:
        return array(
            'status' => ( $embed_code ? 1 : 0 ),
            'embed_code' => $embed_code,
            'clean_url' => $clean_url,
        );

    } else {
        //Just return the embed code:
        if($embed_code){
            return trim(str_replace($url,$embed_code,$full_message));
        } else {
            //Not matched with an embed rule:
            return false;
        }
    }



}




function echo_i($i,$u_full_name=null,$fb_format=false){

    //Must be one of these types:
    if(!isset($i['i_media_type']) || !in_array($i['i_media_type'],array('text','video','audio','image','file'))){
        return false;
    }


    //Do a quick hack to make these two variables inter-changable:
    if(isset($i['i_outbound_c_id']) && $i['i_outbound_c_id']>0 && !isset($i['e_outbound_c_id'])){
        $i['e_outbound_c_id'] = $i['i_outbound_c_id'];
    } elseif(isset($i['e_outbound_c_id']) && $i['e_outbound_c_id']>0 && !isset($i['i_outbound_c_id'])){
        $i['i_outbound_c_id'] = $i['e_outbound_c_id'];
    }


    $CI =& get_instance();

    if(!$fb_format){
        //HTML format:
        $div_style = ' style="padding:0; margin:0; font-family: Lato, Helvetica, sans-serif; font-size:16px;"'; //We do this for email templates that do not support CSS and also for internal website...
        $ui = '';
        $ui .= '<div class="i_content">';
    } else {
        //This is what will be returned to be sent via messenger:
        $fb_message = array();
    }

    //Proceed to Send Message:
    if($i['i_media_type']=='text' && strlen($i['i_message'])>0){


        //Do we have a {first_name} replacement?
        if($u_full_name){
            //Tweak the name:
            $i['i_message'] = str_replace('{first_name}', one_two_explode('',' ',$u_full_name), $i['i_message']);
        }



        //Does this message include a special command?
        $button_url = null;
        $button_title = null;
        $command = null;

        //Do we have any commands?
        if(substr_count($i['i_message'],'{button}')>0){

            $button_title = 'Open in 🚩Action Plan';
            $command = '{button}';
            $button_url = 'https://mench.com/my/actionplan'; //We assume a basic link to Action Plan

            if(isset($i['i_outbound_c_id']) && isset($i['e_b_id']) && isset($i['e_r_id'])){

                //Validate this to make sure it's all Good:
                $bs = fetch_action_plan_copy($i['e_b_id'],$i['e_r_id']);
                $c_data = extract_level( $bs[0], $i['i_outbound_c_id'] );

                //Does this intent belong to this Bootcamp/Class?
                if($c_data){
                    //Everything looks good:
                    $button_url = 'https://mench.com/my/actionplan/'.$i['e_b_id'].'/'.$i['i_outbound_c_id'];
                }
            }

        } elseif(substr_count($i['i_message'],'{enrollments}')>0 && isset($i['e_outbound_u_id'])) {

            //Fetch salt:
            $application_status_salt = $CI->config->item('application_status_salt');
            //append their My Account Button/URL:
            $button_title = '🎟️ My Bootcamps';
            $button_url = 'https://mench.co/my/applications?u_key=' . md5($i['e_outbound_u_id'] . $application_status_salt) . '&u_id=' . $i['e_outbound_u_id'];
            $command = '{enrollments}';

        } elseif(substr_count($i['i_message'],'{passwordreset}')>0 && isset($i['e_outbound_u_id'])) {

            //append their My Account Button/URL:
            $timestamp = time();
            $button_title = '👉 Set New Password';
            $button_url = 'https://mench.com/my/reset_pass?u_id='.$i['e_outbound_u_id'].'&timestamp='.$timestamp.'&p_hash=' . md5($i['e_outbound_u_id'] . 'p@ssWordR3s3t' . $timestamp);
            $command = '{passwordreset}';

        } elseif(substr_count($i['i_message'],'{messenger}')>0 && isset($i['e_outbound_u_id']) && isset($i['e_b_id'])) {

            //Fetch Facebook Page from Bootcamp:
            $bs = $CI->Db_model->b_fetch(array(
                'b.b_id' => $i['e_b_id'],
            ));

            if(isset($bs[0]['b_fp_id']) && $bs[0]['b_fp_id']>0 && isset($i['e_outbound_u_id']) && $i['e_outbound_u_id']>0){
                $button_url = $CI->Comm_model->fb_activation_url($i['e_outbound_u_id'],$bs[0]['b_fp_id']);
                if($button_url) {
                    //append their My Account Button/URL:
                    $button_title = '🤖 Activate Chatline';
                    $command = '{messenger}';
                }
            }

        } elseif(isset($i['i_button']) && strlen($i['i_button'])>0 && isset($i['i_url']) && strlen($i['i_url'])>0){

            $button_title = trim($i['i_button']);
            $button_url = $i['i_url'];
            $command = '{inject_button}'; //Not used anywhere
            $i['i_message'] .= $command; //To be replaced later on...

        }





        //Does this message also have a URL?
        if(isset($i['i_url']) && isset($i['i_id']) && intval($i['i_id'])>0 && strlen($i['i_url'])>0){

            $website = $CI->config->item('website');
            $masked_url = $website['url'].'ref/'.$i['i_id'];

            if($fb_format){

                //Messenger format, simply replace the link with a trackable one UNLESS the link is to our own domain:
                if(substr_count(strtolower($i['i_url']),'mench.com')==0){
                    $i['i_message'] = trim(str_replace($i['i_url'],$masked_url,$i['i_message']));
                } else {
                    //Clean the URL:
                    $i['i_message'] = trim(str_replace($i['i_url'],echo_clean_url($i['i_url']),$i['i_message']));
                }

            } else {

                //Is this a supported embed video URL?
                $embed_html = echo_embed($i['i_url'],$i['i_message']);
                if($embed_html){
                    $i['i_message'] = $embed_html;

                    //Facebook Messenger Webview adds an additional button to view full screen:
                    if(isset($i['show_new_window'])){
                        //HTML media format:
                        $i['i_message'] .= '<div><a href="https://mench.com/webview_video/'.$i['i_id'].'" target="_blank">Full Screen in New Window ↗️</a></div>';
                    }

                } else {
                    //HTML format:
                    $i['i_message'] = trim(str_replace($i['i_url'],'<a href="'.$masked_url.'" target="_blank">'.echo_clean_url($i['i_url']).'<i class="fas fa-external-link-square" style="font-size: 0.8em; text-decoration:none; padding-left:4px;"></i></a>',$i['i_message']));
                }

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
                $i['i_message'] = trim(str_replace($command, '<div class="msg" style="padding-top:15px;"><a href="'.$button_url.'" target="_blank"><b>'.$button_title.'</b></a></div>', $i['i_message']));
                //Return HTML code:
                $ui .= '<div class="msg" '.$div_style.'>'.nl2br($i['i_message']).'</div>';
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
                $ui .= '<div class="msg" '.$div_style.'>'.nl2br($i['i_message']).'</div>';
            }

        }

    } elseif(in_array($i['i_media_type'],array('video','audio','image','file')) && strlen($i['i_url'])>0) {

        //Valid media file with URL:
        if($fb_format){

            $payload = array();

            //Do we have this saved in FB Servers?
            if(isset($i['e_fp_id']) && $i['e_fp_id']>0 && isset($i['i_id']) && $i['i_id']>0){

                //See if we have a cached version of this file for this page:
                $synced_media_files = $CI->Db_model->sy_fetch(array(
                    'sy_i_id' => $i['i_id'],
                    'sy_fp_id' => $i['e_fp_id'],
                ));

                if(isset($synced_media_files[0]['sy_fb_att_id']) && $synced_media_files[0]['sy_fb_att_id']>0){
                    //Yesss, use that:
                    $payload = array(
                        'attachment_id' => $synced_media_files[0]['sy_fb_att_id'],
                    );
                }
            }

            if(count($payload)<1){
                //Use standard file:
                $payload = array(
                    'url' => $i['i_url'],
                    'is_reusable' => false,
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
            $ui .= '<div '.$div_style.'>'.format_e_text_value('/attach '.$i['i_media_type'].':'.$i['i_url']).'</div>';

            //Facebook Messenger Webview adds an additional button to view full screen:
            if(isset($i['show_new_window']) && $i['i_media_type']=='video'){
                //HTML media format:
                $ui .= '<div><a href="https://mench.com/webview_video/'.$i['i_id'].'" target="_blank">Full Screen in New Window ↗️</a></div>';
            }

        }

    } else {

        //Something was wrong:
        return false;

    }

    //Log engagement if Facebook and return:
    if($fb_format && count($fb_message)>0){

        //Return Facebook Message to be sent out:
        return $fb_message;

    } elseif(!$fb_format) {

        //This must be HTML if we're still here, return:
        $ui .= '</div>';
        return $ui;

    } else {

        //Should not happen!
        return false;

    }
}



function echo_message($i){

    $ui = '';
    $ui .= '<div class="list-group-item is-msg is_level2_sortable all_msg msg_'.$i['i_status'].'" id="ul-nav-'.$i['i_id'].'" iid="'.$i['i_id'].'">';
    $ui .= '<input type="hidden" class="i_media_type" value="'.$i['i_media_type'].'" />';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="'.($i['i_media_type']=='text'?'edit-off text_message':'').'" id="msg_body_'.$i['i_id'].'" style="margin:5px 0 0 0;">';
    $ui .= echo_i($i);
    $ui .= '</div>';


    if($i['i_media_type']=='text'){
        //Text editing:
        $ui .= '<textarea onkeyup="changeMessageEditing('.$i['i_id'].')" name="i_message" id="message_body_'.$i['i_id'].'" class="edit-on hidden msg msgin" placeholder="Write Message..." style="margin-top: 4px;">'.$i['i_message'].'</textarea>';
    }

    //Editing menu:
    $ui .= '<ul class="msg-nav">';
    //$ui .= '<li class="edit-off"><i class="fas fa-clock"></i> 4s Ago</li>';
    $ui .= '<li class="the_status edit-off" style="margin: 0 6px 0 -3px;">'.echo_status('i',$i['i_status'],1,'right').'</li>';
    if($i['i_media_type']=='text'){
        $CI =& get_instance();
        $message_max = $CI->config->item('message_max');
        $ui .= '<li class="edit-on hidden"><span id="charNumEditing'.$i['i_id'].'">0</span>/'.$message_max.'</li>';
    }
    $ui .= '<li class="edit-off"><span class="on-hover i_uploader">'.echo_cover($i,null,true, 'data-toggle="tooltip" title="Last modified by '.$i['u_full_name'].' about '.echo_diff_time($i['i_timestamp']).' ago" data-placement="right"').'</span></li>';

    $ui .= '<li class="edit-off" style="margin: 0 0 0 8px;"><span class="on-hover"><i class="fas fa-bars sort_message" iid="'.$i['i_id'].'" style="color:#3C4858;"></i></span></li>';
    $ui .= '<li class="edit-off" style="margin-right: 10px; margin-left: 6px;"><span class="on-hover"><a href="javascript:i_delete('.$i['i_id'].');"><i class="fas fa-trash-alt" style="margin:0 7px 0 5px;"></i></a></span></li>';
    if($i['i_media_type']=='text'){
        $ui .= '<li class="edit-off" style="margin-left:-4px;"><span class="on-hover"><a href="javascript:msg_start_edit('.$i['i_id'].','.$i['i_status'].');"><i class="fas fa-pen-square"></i></a></span></li>';
    }
    //Right side reverse:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary" href="javascript:message_save_updates('.$i['i_id'].','.$i['i_status'].');" style="text-decoration:none; font-weight:bold; padding: 1px 8px 4px;"><i class="fas fa-check"></i></a></li>';
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-hidden" href="javascript:msg_cancel_edit('.$i['i_id'].');"><i class="fas fa-times" style="color:#3C4858"></i></a></li>';
    $ui .= '<li class="pull-right edit-on hidden">'.echo_dropdown_status('i','i_status_'.$i['i_id'],$i['i_status'],array(-1),'dropup',1).'</li>';
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
        return '<i class="fas fa-user-circle" '.$tooltip_content.' ></i>';
    } else {
        return null;
    }
}

function echo_b_header($b){
    $ui = '<p style="border-bottom:4px solid #3C4858; font-weight:bold; padding-bottom:10px; margin-bottom:50px; display:block;">';
    $ui .= '<i class="fas fa-cube"></i> '.$b['c_outcome'];
    $ui .= '<span style="font-weight: 500; display: block; padding-top:5px; font-size:0.9em;"><i class="fas fa-clock"></i> '.$b['b_weeks_count'].' Week'.echo__s($b['b_weeks_count']).' @ '.echo_hours(($b['c__estimated_hours']/$b['b_weeks_count']),false).'/Week'.'</span>';
    $ui .= '</p>';

    return $ui;
}

function echo_link($text){
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank"><u>$1</u></a>', $text);
}


function echo_completion_report($us_eng){
    echo echo_status('e_status',$us_eng['e_status']);
    echo '<div style="margin:10px 0 10px;"><span class="status-label" style="color:#3C4858;"><i class="fas fa-clock initial"></i>Completion Time:</span> '.echo_time($us_eng['e_timestamp']).' PST</div>';
    echo '<div style="margin-bottom:10px;"><span class="status-label" style="color:#3C4858;"><i class="fas fa-comment-dots initial"></i>Your Comments:</span> '.( strlen($us_eng['e_text_value'])>0 ? echo_link(nl2br(htmlentities($us_eng['e_text_value']))) : 'None' ).'</div>';
}


function echo_clean_url($url){
    return rtrim(str_replace('http://','',str_replace('https://','',str_replace('www.','',$url))),'/');
}



function echo_coach($u,$b,$is_landing_page){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    echo '<div class="col-sm-6"><div class="price-box coaching-box" id="coach_'.$u['u_id'].'">';

        echo echo_cover($u,'coach-face');
        echo '<h3>'.$u['u_full_name'].'</h3>';

    echo '<p style="margin:0 0 0 0 !important;"><img src="/img/flags/'.strtolower($u['u_country_code']).'.png" class="flag" style="margin-top:-4px;" /> '.$u['u_current_city'].( strlen($u['u_timezone'])>0 ? ' '.one_two_explode('','.', $u['u_timezone']).( substr_count($u['u_timezone'],'.5')==1 ? ':30' : ':00' ).' GMT' : '' ).( isset($udata['u_inbound_u_id']) ? ' <a href="/entities/'.$u['u_id'].( $udata['u_inbound_u_id']==1281 ? '/modify' : '' ).'"><i class="fas fa-cog"></i></a>' : '' ).'</p>';


    //Any languages other than English?
    /*
    if(strlen($u['u_language'])>0){
        $all_languages = $CI->config->item('languages');
        //They know more than enligh!
        $langs = explode(',',$u['u_language']);
        echo '<p style="margin:0 0 0 0 !important;">';
        echo 'Fluent in ';
        foreach($langs as $count=>$lang){
            if($count>0 && ($count+1)==count($langs)){
                echo ' & ';
            } elseif($count>0){
                echo ', ';
            }
            echo $all_languages[$lang];
        }
        echo '</p>';
    }
    */

    echo '<p style="margin:15px 0 !important;">'.$u['u_bio'].'</p>';

    //Public profiles:
    echo '<p style="margin:15px 0 5px !important;" class="public-profiles">';
    echo echo_social_profiles($CI->Db_model->x_social_fetch($u['u_id']));
    echo '</p>';

    if(!$is_landing_page){
        echo '<a href="javascript:void(0);" onclick="choose_coach('.$u['u_id'].');">Book Free Call with '.one_two_explode('',' ',$u['u_full_name']).' <i class="fas fa-chevron-right"></i></a>';
    }


    echo '</div></div>';
}

function echo_package($b,$is_diy,$is_landing_page,$enrollment=array()){

    if($is_diy && $b['b_offers_diy']){

        ?>
        <?= ( !$is_landing_page ? '<div class="col-sm-6">' : '' ) ?>
        <div class="price-box">
            <i class="fas fa-comment-smile"></i>
            <h3>Mench Personal Assistant</h3>
            <span id="p_name_1" data-price="0" class="hidden">Mench Personal Assistant (Free)<!-- For Final Confirmation Text, injected by JS --></span>
            <?= ( !$is_landing_page && $b['b_offers_coaching'] ? '<div style="margin:0 0 10px 0 !important;">&nbsp;</div>' : '' ) ?>
            <div class="support_p">
            </div>
            <a <?= ( $is_landing_page ? 'href="/'.$b['b_url_key'].'/enroll?package_id=1" class="btn btn-primary"' : 'href="javascript:void(0);" onclick="set_package(1);"' ) ?>>Get Started <i class="fas fa-angle-right"></i></a>
        </div>
        <?= ( !$is_landing_page ? '</div>' : '' ) ?>
        <?php

    } elseif(!$is_diy && $b['b_offers_coaching']){

        $coaching_price = intval($b['b_weekly_coaching_rate'] * $b['b_weeks_count']);

        if($b['b_offers_deferred']) {
            $deferred_deposit = intval($b['b_weekly_coaching_rate'] * $b['b_weeks_count'] * $b['b_deferred_rate'] * $b['b_deferred_deposit']);
            $deferred_remainder = intval($b['b_weekly_coaching_rate'] * $b['b_weeks_count'] * $b['b_deferred_rate'] * (1 - $b['b_deferred_deposit']));
            $deferred_total = $deferred_deposit+$deferred_remainder;
        }
        ?>
        <?= ( !$is_landing_page && !count($enrollment) ? '<div class="col-sm-6">' : '' ) ?>
        <div class="price-box">
            <i class="fas fa-user-friends"></i>
            <h3>1-on-1 Coaching</h3>
            <span id="p_name_2" data-price="<?= $coaching_price ?>" class="hidden">Mench Personal Assistant + 1 Free Coaching Call​<!-- For Final Confirmation Text, injected by JS --></span>
            <?= ( $b['b_offers_diy'] && !count($enrollment) ? '<p class="bold-box">Mench Personal Assistant plus:</p>' : '' ) ?>


            <div class="support_p">


                <div class="dash-label"><span class="icon-left"><i class="fas fa-hands-helping"></i></span> Team-Up with an Industry Expert</div>
                <div class="dash-label"><span class="icon-left"><i class="fas fa-whistle"></i></span> <?= echo_hours($b['b_weekly_coaching_hours']*$b['b_weeks_count'], false, true) ?> Coaching in <?= $b['b_weeks_count'].' Week'.echo__s($b['b_weeks_count']) ?></div>



                <?php if(strlen($b['b_coaching_services'])>0){

                    $service_count = count(json_decode($b['b_coaching_services']));
                    //echo them all:
                    foreach(json_decode($b['b_coaching_services']) as $service){
                        echo '<div class="dash-label"><span class="icon-left"><i class="fas fa-badge-check"></i></span> '.$service.'</div>';
                    }

                } else { ?>
                    <div class="dash-label"><span class="icon-left"><i class="fas fa-route"></i></span> Personalized Tasks & Feedback</div>
                <?php } ?>


                <!--<div class="dash-label"><span class="icon-left"><i class="fas fa-lightbulb-on"></i></span> Weekly Brainstorming Calls</div>
                <div class="dash-label"><span class="icon-left"><i class="fab fa-facebook-messenger"></i></span> 1-on-1 Chat & Email Support</div>-->

                <div class="dash-label"><span class="icon-left"><i class="fas fa-smile"></i></span> <span title="We guarantee you will <?= strtolower($b['c_outcome']) . ( $b['b_guarantee_weeks']>0 ? ' within '.$b['b_guarantee_weeks'].' week'.echo__s($b['b_guarantee_weeks']).' after the end of this '.$b['b_weeks_count'].' week Bootcamp' : '') ?> as long as you complete all weekly tasks assigned to you by your coach. You will receive a full refund if you do the work but do not <?= strtolower($b['c_outcome']) ?>. Click to learn more." data-toggle="tooltip" data-placement="top"><a href="https://support.mench.com/hc/en-us/articles/115002080031" target="_blank" style="text-decoration: underline !important; font-size:1em !important;"><?= ($b['b_weeks_count']+$b['b_guarantee_weeks']) ?> Week Outcome Guarantee</a> <i class="fas fa-info-circle"></i></span></div>


                <?php if(!count($enrollment)){ ?>
                <div class="dash-label"><span class="icon-left"><i class="fas fa-dollar-sign"></i></span> <?= ( $b['b_offers_deferred'] ? 'Starting from $'.number_format($deferred_deposit/$b['b_weeks_count'],0).' per Week' : 'A 1-time Payment of $'.number_format($coaching_price,0).' USD') //After your free strategy session if you decided that coaching is the right support framework for you, then you get to choose to pay either $'.$coaching_price.' up-front as full payment OR pay a non-refundable deposit of $'.$deferred_deposit.' USD up-front and $'.$deferred_remainder.' USD later from '.($b['b_deferred_payback']*100).'% of each paycheck when (and only if) you '.strtolower($b['c_outcome']).' ?></div>
                <?php } ?>

                <div class="dash-label"><span class="icon-left"><i class="fas fa-gift"></i></span> First Call Free (No Credit Card)​</div>

            </div>


            <?php if(count($enrollment)){ ?>
                <a href="/<?= $b['b_url_key'] ?>/pay?u_email=shervin@mench.com&payment=<?= $coaching_price ?>" class="btn btn-primary"><?= 'Pay $'.$coaching_price.' USD'.( $b['b_offers_deferred'] ? ' Up-front' : ''  ).' <i class="fas fa-chevron-right"></i>' ?></a>
            <?php } else { ?>
            <a <?= ( $is_landing_page ? 'href="/'.$b['b_url_key'].'/enroll?package_id=2" class="btn btn-primary"' : 'href="javascript:void(0);" onclick="set_package(2);"' ) ?>><?= ( $b['b_requires_assessment'] && $is_landing_page ? 'Continue' : 'Book Free Coaching Call' ) ?> <i class="fas fa-chevron-right"></i></a>
            <?php } ?>


            <?php if($b['b_offers_deferred'] && count($enrollment)){ ?>

                <!-- Placeholder for Defered pricing box: -->
                <p style="margin: 30px 0 10px !important; font-size: 0.9em; font-weight: 300;">Or pay a non-refundable deposit of $<?= $deferred_deposit ?> USD now and $<?= $deferred_remainder ?> USD later from <?= ($b['b_deferred_payback']*100) ?>% of each paycheck when (and only if) you <?= strtolower($b['c_outcome']) ?>:</p>
                <a href="/<?= $b['b_url_key'] ?>/pay?u_email=shervin@mench.com&payment=<?= $deferred_deposit ?>" class="btn btn-primary">Pay $<?= $deferred_deposit ?> USD Now + $<?= $deferred_remainder ?> Later <i class="fas fa-chevron-right"></i></a>

            <?php } ?>


            <?php if(!$is_landing_page){ ?>

                <div style="font-size:0.9em; margin-top:15px; font-weight: 300;"><i class="fas fa-lightbulb-on" style="margin: 0 2px;"></i> <?= echo_embed('https://www.youtube.com/watch?v=kngyyeMel5c', 'Tip by Eric Schmidt [Ex-Google-CEO]: https://www.youtube.com/watch?v=kngyyeMel5c'); ?></div>

            <?php } ?>

        </div>


        <?= ( !$is_landing_page && !count($enrollment) ? '</div>' : '' ) ?>
        <?php
    }
}

function echo_hours($decimal_hours,$micro=false,$plus=false){

    if($decimal_hours<=0){

        return '0'.($micro?'m':' Minutes ');

    } elseif($decimal_hours<=1.50){

        $original_hours = $decimal_hours*60;
        $decimal_hours = round($original_hours);
        return ($decimal_hours==$original_hours || $decimal_hours==7?'':'~').$decimal_hours.($plus?'+':'').($micro?'m':' Minutes');

    } else {

        //Just round-up:
        $original_hours = $decimal_hours;
        $decimal_hours = round($original_hours);
        return ($decimal_hours==$original_hours?'':'~').$decimal_hours.($plus?'+':'').($micro?'h':' Hour'.echo__s($original_hours));

    }

}


function echo_facebook_pixel($b_fb_pixel_id,$purchase_value=0){
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
fbq('init', '".$b_fb_pixel_id."');
". ( $purchase_value>0 ? "fbq('track', 'Purchase', {'value':'".$purchase_value."','currency':'USD'});" : "fbq('track', 'PageView');" ) ."
</script>
<noscript><img height=\"1\" width=\"1\" style=\"display:none\" src=\"https://www.facebook.com/tr?id=".$b_fb_pixel_id."&ev=PageView&noscript=1\" /></noscript>
<!-- End Facebook Pixel Code -->";
}


function echo_object($object,$id,$b_id=0){
    //Loads the name (and possibly URL) for $object with id=$id
    $CI =& get_instance();
    $id = intval($id);

    if($id>0){
        //Used mainly for engagement tracking
        $website = $CI->config->item('website');

        if($object=='c'){
            //Fetch intent/Step:
            $cs = $CI->Db_model->c_fetch(array(
                'c.c_id' => $id,
            ));
            if(isset($cs[0])){
                if($b_id){
                    //We can return a link:
                    //TODO Update to intent library link
                    return '<a href="'.$website['url'].'console/'.$b_id.'/actionplan#modify-'.$cs[0]['c_id'].'">'.$cs[0]['c_outcome'].'</a>';
                } else {
                    return $cs[0]['c_outcome'];
                }
            }
        } elseif($object=='b'){

            $bs = $CI->Db_model->b_fetch(array(
                'b.b_id' => $id,
            ));
            if(isset($bs[0])){
                if($b_id){
                    return '<a href="'.$website['url'].'console/'.$bs[0]['b_id'].'">'.$bs[0]['c_outcome'].'</a>';
                } else {
                    return $bs[0]['c_outcome'];
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
                    return '<a href="'.$website['url'].'entities/'.$id.'" title="User ID '.$id.'">'.$matching_users[0]['u_full_name'].'</a>';
                }
            }

        } elseif($object=='x' && $id>0){

            $matching_urls = $CI->Db_model->x_fetch(array(
                'x_id' => $id,
            ));
            if(isset($matching_urls[0])){
                return '<a href="'.$matching_urls[0]['x_url'].'" title="Reference ID '.$id.'" target="_blank">'.echo_clean_url($matching_urls[0]['x_url']).'</a>';
            }

        } elseif($object=='r'){
            $classes = $CI->Db_model->r_fetch(array(
                'r.r_id' => $id,
            ));
            if(isset($classes[0])){
                if($b_id){
                    //We can return a link:
                    return '<a href="'.$website['url'].'console/'.$b_id.'/classes/'.$classes[0]['r_id'].'">'.echo_time($classes[0]['r_start_date'],1).'</a>';
                } else {
                    return echo_time($classes[0]['r_start_date'],1);
                }
            }
        } elseif($object=='fp'){
            $pages = $CI->Db_model->fp_fetch(array(
                'fp_id' => $id,
            ), array('fs'));
            if(isset($pages[0])){
                return '<a href="https://www.facebook.com/'.$pages[0]['fp_fb_id'].'" target="_blank">'.$pages[0]['fp_name'].'</a>';
            } else {
                print_r($pages);
            }
        }
        //We would not do the other engagement types...
    }

    //Still here? Return default:
    if($id>0){
        return '#'.$id;
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
                return '<span class="status-label" '.( isset($result['s_desc']) && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="'.$data_placement.'" title="'.$result['s_desc'].'" style="border-bottom:1px dotted #444; padding-bottom:1px;"':'style="cursor:pointer;"').'>'.( isset($result['s_icon']) ? '<i class="'.$result['s_icon'].' initial"></i> ' : '<i class="fas fa-sliders-h initial"></i> ' ).($micro_status?'':$result['s_name']).'</span>';
            }

        }
    }
}


function echo_estimated_time($c_time_estimate,$show_icon=1,$micro=false,$c_id=0,$c_time_intent=0){

    if($c_time_estimate>0 || $c_id){

        $ui = '<span class="title-sub" style="text-transform:none !important;">';

        if($c_id){

            $ui .= '<span class="slim-time" id="t_estimate_'.$c_id.'" tree-hours="'.$c_time_estimate.'" intent-hours="'.$c_time_intent.'">'.echo_hours( $c_time_estimate,true).'</span>';
            $ui .= ' <i class="fas fa-clock"></i>';

        } else {

            if($show_icon){
                $ui .= '<i class="fas fa-clock"></i>';
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

function echo_br($ba){
    //Removed for now: href="javascript:ba_open_modify('.$ba['ba_id'].')"
    $ui = '<li id="ba_'.$ba['ba_id'].'" u-id="'.$ba['ba_id'].'" class="list-group-item is_level2_sortable">';
    //Right content
    $ui .= '<span class="pull-right">';
    //$ui .= '<span class="label label-primary" data-toggle="tooltip" data-placement="left" title="Click to modify/revoke access.">';
    //$ui .= '<i class="fas fa-cog"></i>';
    //$ui .= '</span>';
    $ui .= echo_status('ba',$ba['ba_status'], false, 'top');

    $ui .= '</span> ';

    //Left content
    //$ui .= '<i class="fa fas-bars" style="padding-right:3px;"></i> ';
    $ui .= echo_cover($ba,'profile-icon',true).' <a href="/entities/'.$ba['u_id'].'">'.$ba['u_full_name'].' &raquo;</a> &nbsp;';

    if(!$ba['u_booking_x_id']){
        $ui .= ' <a href="/entities/'.$ba['u_id'].'#references"  data-toggle="tooltip" title="Coach will not be listed until they have a Booking URL Uploaded from calendly.com or hubspot.com so students can book their first free call" data-placement="top" style="color:#FF0000; text-decoration: none;"><i class="fas fa-calendar-exclamation"></i> Add Booking URL &raquo;</a>';
    }

    $ui .= '</li>';
    return $ui;
}



function echo_score($score){
    if(!$score){
        return false;
    }
    return '<span class="title-sub" style="text-transform:none;" data-toggle="tooltip" data-placement="left" title="Engagement Score"><span class="slim-time">'.echo_big_num($score).'</span> <i class="fas fa-badge"></i></span>';
}




function echo_actionplan($c, $level, $parent_c_id=0){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    if($level==1){

        //Bootcamp Outcome:
        $ui = '<div class="list-group-item">';

    } else {

        //ATTENTION: DO NOT CHANGE THE ORDER OF data-link-id & intent-id AS the sorting logic depends on their exact position to sort!
        //CHANGE WITH CAUTION!
        $ui = '<div id="cr_'.$c['cr_id'].'" data-link-id="'.$c['cr_id'].'" intent-id="'.$c['c_id'].'" intent-level="'.$level.'" class="list-group-item '.( $level==3 ? 'is_level3_sortable' : 'is_level2_sortable' ).' intent_line_'.$c['c_id'].'">';

    }

    //Right content
    $ui .= '<span class="pull-right maplevel'.$c['c_id'].'" parent-intent-id="'.$parent_c_id.'" style="'.( $level<3 ? 'margin-right: 8px;' : '' ).'">';


    //Show total tree hours:
    $ui .= echo_estimated_time($c['c__tree_hours'],1,1, $c['c_id'], $c['c_time_estimate']);


    //TODO Maybe later show indicator for requiring notes or URL: <span class="btn-counter"><i class="fas fa-pencil"></i><i class="fas fa-link"></i></span>
    $ui .= '<a class="badge badge-primary" onclick="load_modify('.$c['c_id'].','.( isset($c['cr_id']) ? $c['cr_id'] : 0 ).')" style="margin:-2px -4px 0 1px; width:40px;" href="#modify-'.$c['c_id'].'-'.( isset($c['cr_id']) ? $c['cr_id'] : 0 ).'"><i class="c_is_output_icon '.( $c['c_is_output'] ? 'fas fa-check-square' : 'fas fa-lightbulb-on' ).'"></i></a> &nbsp;';

    $ui .= '<a href="#messages-'.$c['c_id'].'" onclick="i_load_frame('.$c['c_id'].')" class="badge badge-primary" style="width:40px;"><span class="btn-counter" id="messages-counter-'.$c['c_id'].'">'.$c['c__this_messages'].'</span><i class="fas fa-comment-dots"></i></a>';

    $ui .= '&nbsp;<'.($level>1 ? 'a href="/intents/'.$c['c_id'].'" class="badge badge-primary"' :'span class="badge badge-primary grey"').' data-toggle="tooltip" data-placement="left" title="Intent tree contains '.($c['c__tree_inputs']+$c['c__tree_outputs']).' child intents. '.($level>1 ? 'Click to browse' :'See list below').'" style="display:inline-block; margin-right:-1px; width:40px;"><span class="btn-counter" id="tree-counter-'.$c['c_id'].'">'.($c['c__tree_inputs']+$c['c__tree_outputs']).'</span><i class="c_is_any_icon '.( $c['c_is_any'] ? 'fas fa-code-merge' : 'fas fa-sitemap' ).'"></i></'.($level>1 ? 'a' :'span').'> ';

    //Keep an eye out for inner message counter changes:
    $ui .= '</span> ';



    $c_settings = ' c_require_url_to_complete="'.$c['c_require_url_to_complete'].'" c_require_notes_to_complete="'.$c['c_require_notes_to_complete'].'" c_is_any="'.$c['c_is_any'].'" c_is_output="'.$c['c_is_output'].'" ';


    //Sorting & Then Left Content:
    if($level>1) {
        $ui .= '<i class="fas fa-bars"></i> &nbsp;';
    }


    if($level==1){

        //Bootcamp Outcome:
        $ui .= '<span><b id="b_objective" style="font-size: 1.3em;"><i class="fas fa-hashtag"></i><span class="c_outcome_'.$c['c_id'].'" '.$c_settings.'>'.$c['c_outcome'].'</span></b></span>';

    } elseif($level==2){

        //Task:
        $ui .= '<span class="inline-level">';

        $ui .= '<a href="javascript:ms_toggle('.$c['cr_id'].');"><i id="handle-'.$c['cr_id'].'" class="fal fa-plus-square"></i></a> &nbsp;';

        $ui .= '<span class="inline-level-'.$level.'">#'.$c['cr_outbound_rank'].'</span>';
        $ui .= '</span>';

        $ui .= '<b id="title_'.$c['cr_id'].'" class="cdr_crnt c_outcome_'.$c['c_id'].'" outbound-rank="'.$c['cr_outbound_rank'].'" '.$c_settings.'>'.$c['c_outcome'].'</b> ';

    } elseif ($level==3){

        //Steps
        $ui .= '<span class="inline-level inline-level-'.$level.'">#'.$c['cr_outbound_rank'].'</span><span id="title_'.$c['cr_id'].'" class="c_outcome_'.$c['c_id'].'" outbound-rank="'.$c['cr_outbound_rank'].'" '.$c_settings.'>'.$c['c_outcome'].'</span> ';

    }

    //Any Tree?
    if($level==2){

        $ui .= '<div id="list-cr-'.$c['cr_id'].'" class="cr-class-'.$c['cr_id'].' list-group step-group hidden list-level-3" intent-id="'.$c['c_id'].'">';
        //This line enables the in-between list moves to happen for empty lists:
        $ui .= '<div class="is_level3_sortable dropin-box" style="height:1px;">&nbsp;</div>';


        if(isset($c['c__child_intents']) && count($c['c__child_intents'])>0){
            foreach($c['c__child_intents'] as $key=>$sub_intent){
                $ui .= echo_actionplan($sub_intent, ($level+1), $c['c_id']);
            }
        }


        //Step Input field:
        $ui .= '<div class="list-group-item list_input new-step-input">
            <div class="input-group">
                <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="new_intent('.$c['c_id'].',3);" intent-id="'.$c['c_id'].'"><input type="text" class="form-control autosearch intentadder-level-3" maxlength="70" id="addintent-cr-'.$c['cr_id'].'" intent-id="'.$c['c_id'].'" placeholder="Add #Intent"></form></div>
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

function echo_b($b){

    //Fetch total students:
    $CI =& get_instance();
    $all_students = count($CI->Db_model->ru_fetch(array(
        'ru.ru_b_id'	   => $b['b_id'],
        'ru.ru_status >='  => 4,
    )));

    $b_ui = null;
    $b_ui .= '<a href="/console/'.$b['b_id'].'" class="list-group-item">';
    $b_ui .= '<span class="pull-right"><span class="badge badge-primary"><i class="fas fa-chevron-right"></i></span></span>';
    $b_ui .= '<i class="fas fa-cube" style="margin: 0 8px 0 2px;"></i>';
    $b_ui .= $b['c_outcome'];
    if($b['b_weeks_count']>1){
        $b_ui .= '<b data-toggle="tooltip" data-placement="top" title="Bootcamp Duration is '.$b['b_weeks_count'].' Week'.echo__s($b['b_weeks_count']).'"><i class="fas fa-calendar-alt" style="margin: 0 2px 0 8px;"></i>'.$b['b_weeks_count'].'</b>';
    }



    if($all_students>0){
        $b_ui .= ' &nbsp;<b data-toggle="tooltip" data-placement="top" title="Bootcamp has '.$all_students.' Enrolled Student'.echo__s($all_students).'"><i class="fas fa-users" style="margin: 0 2px 0 8px;"></i>'.$all_students.'</b>';
    }

    $b_ui .= '</a>';
    return $b_ui;
}

function echo_json($array){
    header('Content-Type: application/json');
    echo json_encode($array);
}

function echo_r($b,$class,$append_class=null){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $class_settings = $CI->config->item('class_settings');

    $guided_enrollments = count($CI->Db_model->ru_fetch(array(
        'ru_r_id' => $class['r_id'],
        'ru_status >=' => 4,
        'ru_upfront_pay >' => 0,
    )));

    echo '<li class="list-group-item '.$append_class.'">';

    echo '<span class="pull-right">';
    if($class['r__current_enrollments']>0){

        //How many students, if any, are enrolled in Support Frameworks?
        echo '<a href="#class-'.$class['r_id'].'" onclick="load_class('.$class['r_id'].')" class="badge badge-primary" style="text-decoration: none;">'.$class['r__current_enrollments'].' <i class="fas fa-chevron-right"></i></a>';

    } else {

        echo '<span class="badge badge-primary grey" data-toggle="tooltip" data-placement="right" title="No Students Yet">0</span>';

    }
    echo '</span>';

    //Determine the state of the Checkbox:
    if($guided_enrollments>0 || $class['r_status']>=2){

        //Locked:
        echo '<span class="badge badge-primary '.( $guided_enrollments==0 ? 'grey' : '' ).'">';
        echo echo_status('r',$class['r_status'],true, 'right');
        if($guided_enrollments>0){
            echo ' <span data-toggle="tooltip" data-placement="right" title="'.$guided_enrollments.' Students Enrolled">'.$guided_enrollments.'</span>';
        }
        echo '</span>';

    } else {

        //Can still change:
        echo '<a href="javascript:void(0);" onclick="toggle_support('.$class['r_id'].')" id="support_toggle_'.$class['r_id'].'" class="badge badge-primary '.( $class['r_status']==0 ? 'grey' : '' ).'" style="text-decoration: none;" current-r-status="'.$class['r_status'].'" data-toggle="tooltip" data-placement="right" title="Click to toggle availability for the week of ['.echo_time($class['r_start_date'],4).'] Yellow = Offer Coaching Grey = Disable Coaching">'.echo_status('r',$class['r_status'],true, null).'</a>';


    }

    echo ' <span title="Class ID '.$class['r_id'].'">'.echo_time($class['r_start_date'],1).'</span>';

    echo '</li>';
}



function echo_checklist($href,$anchor,$e_status,$time_min=0){

    $ui = '';
    if($href){
        $ui .= '<a href="'.$href.'" class="list-group-item '.(($e_status>=-2)?'checklist-done':'').'">';
        $ui .= '<span class="pull-right"><span class="badge badge-primary" style="margin-top:-5px;"><i class="fas fa-chevron-right"></i></span></span>';
    } else {
        $ui .= '<li class="list-group-item '.(($e_status>=-2)?'checklist-done':'').'">';
    }

    $ui .= echo_status('e_status',$e_status,1,'right').' ';
    $ui .= $anchor.' ';

    if($href){
        $ui .= '</a>';
    } else {
        $ui .= '</li>';
    }
    return $ui;
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
        if((isset($status['limit_u_inbounds']) && !in_array($udata['u_inbound_u_id'], $status['limit_u_inbounds'])) || in_array($intval,$exclude_ids)){
            //Do not enable this user to modify to this status:
            continue;
        }
        $count++;
        $return_ui .= '<li><a href="javascript:update_dropdown(\''.$input_name.'\','.$intval.','.$count.');">'.echo_status($object,$intval,0,$inner_tooltip).'</a></li>';
        $return_ui .= '<li style="display:none;" class="'.$input_name.'_'.$intval.'" id="'.$input_name.'_'.$count.'">'.echo_status($object,$intval,$mini,$inner_tooltip).'</li>'; //For UI replacement
    }
    $return_ui .= '</ul></div>';
    return $return_ui;
}

