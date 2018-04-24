<?php

function is_dev(){
    return ( isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='local.mench.co' );
}

function is_old(){
    return ( isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME']=='mench.co' );
}

function lock_cron_for_processing($e_items){
    $CI =& get_instance();
    foreach($e_items as $e){
        if($e['e_id']>0 && $e['e_cron_job']==0){
            $CI->Db_model->e_update( $e['e_id'] , array(
                'e_cron_job' => -2, //Processing so other Cron jobs do not touch this...
            ));
        }
    }
}


function calculate_total($admission){
    //TODO Implement Coupons here
    return doubleval( $admission['ru_p1_price'] + $admission['ru_p2_price'] + ($admission['ru_p3_price']*50));
}

function fetch_action_plan_copy($b_id,$r_id=0,$current_b=null,$release_cache=array()){

    $CI =& get_instance();
    $cache_action_plans = array();
    $bs = array();

    if($r_id){
        //See if we have a copy:
        $cache_action_plans = $CI->Db_model->e_fetch(array(
            'e_type_id' => 70,
            'e_r_id' => $r_id,
        ), 1, array('ej'));
    }

    if(count($cache_action_plans)>0){

        //Assign this cache to the Bootcamp:
        $b = unserialize($cache_action_plans[0]['ej_e_blob']);

        if($b){
            array_push($bs,$b);

            //Indicate this is a copy:
            $bs[0]['is_copy'] = 1;
            $bs[0]['copy_timestamp'] = $cache_action_plans[0]['e_timestamp'];

            //If we have this, we should replace it to have certain fields updated:
            if($current_b){

                //Any items that we'd like to release its cache?
                foreach($release_cache as $key){
                    //This replaces older values with new ones to ensures we get the most up to date view
                    $bs[0][$key] = $current_b[0][$key];
                }

                //Replace:
                $bs = array_replace_recursive($current_b,$bs);
            }
        }

    }

    if(count($bs)==0){

        //Fetch from live:
        $bs = $CI->Db_model->remix_bs(array(
            'b.b_id' => $b_id,
        ));

        //Indicate this is NOT a copy:
        $bs[0]['is_copy'] = 0;
        $bs[0]['copy_timestamp'] = null;
    }

    if($r_id){
        //Now Fetch Class:
        $classes = $CI->Db_model->r_fetch(array(
            'r_id' => $r_id,
        ), $bs[0] );

        if(count($classes)>0){
            $bs[0]['this_class'] = $classes[0];
        }
    }

    return $bs;
}


function join_keys($input_array,$joiner=','){
    $joined_string = null;
    foreach($input_array as $key=>$value){
        if($joined_string){
            $joined_string .= $joiner;
        }
        $joined_string .= $key;
    }
    return $joined_string;
}



function detect_active_admission($admissions){

    //Determines the active admission of a student, especially useful if they have multiple admissions
    if(count($admissions)<1){

        return false;

    } elseif(count($admissions)>1){

        /*
         * Ohh, let's try to figure this out. There are a few scenarios:
         *
         * 1. Multiple up-coming Bootcaps that do not overlap
         * 2. A mix of past Bootcamps already completed, and some upcoming ones
         * 3. A bunch of past Bootcamps that are all completed and none active
         * 4. A mix and match of above?!
         *
         * ru_status & r_status and are guiding lights here to crack this puzzle
         *
         */

        //TODO Ooptimize the loop below because I cannot fully wrap my head around it for now!
        //Should think further about priorities and various use cases of this function
        //So i'm leaving it as is to be tested further @ later date (Mar 6th 2018)

        $active_admission = null;

        foreach($admissions as $admission){

            //Now see whatssup:
            if($admission['ru_status']>4 || $admission['r_status']>2){

                //This is a completed Class:
                $active_admission = $admission;

            } elseif($admission['ru_status']==4 && $admission['r_status']<2){

                //Class is not started yet:
                $active_admission = $admission;

            } elseif($admission['ru_status']==4 && $admission['r_status']==2){

                //Active class has highest priority, break after:
                $active_admission = $admission;
                break; //This is what we care about the most, so make it have the last say

            } elseif(!$active_admission){

                //Not sure what this could be:
                $active_admission = $admission;

            }
        }

        return $active_admission;

    } elseif(count($admissions)==1){

        //This is typical, treat this as their Active Admission since its the only one they got:
        return $admissions[0];

    }
}

function fetch_file_ext($url){
	//https://cdn.fbsbx.com/v/t59.3654-21/19359558_10158969505640587_4006997452564463616_n.aac/audioclip-1500335487327-1590.aac?oh=5344e3d423b14dee5efe93edd432d245&oe=596FEA95
	$url_parts = explode('?',$url,2);
	$url_parts = explode('/',$url_parts[0]);
	$file_parts = explode('.',end($url_parts));
	return end($file_parts);
}



function parse_signed_request($signed_request) {

    //Fetch app settings:
    $CI =& get_instance();
    $fb_settings = $CI->config->item('fb_settings');

    list($encoded_sig, $payload) = explode('.', $signed_request, 2);

    // Decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);
    
    // Confirm the signature
    $expected_sig = hash_hmac('sha256', $payload, $fb_settings['client_secret'], $raw = true);
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

    //This function uses
    
    $CI =& get_instance();
    //This is what we shall return:
    $view_data = array(
        'pid' => $c_id, //To be deprecated at some point...
        'c_id' => $c_id,
        'b' => $b,
    );

    if($b['c_id']==$c_id){
        
        //Level 1 (The Bootcamp itself)
        $view_data['level'] = 1;
        $view_data['task_index'] = 0;
        $view_data['intent'] = $b;
        $view_data['title'] = 'Action Plan | '.$b['c_objective'];
        $view_data['breadcrumb_p'] = array(
            array(
                'link' => null,
                'anchor' => '<i class="fa fa-dot-circle-o" aria-hidden="true"></i> '.$b['c_objective'],
            ),
        );
        //Not applicable at Bootcamp Level:
        $view_data['next_intent'] = null; //Used in actionplan_ui view for Step Sequence Submission positioning to better understand next move
        $view_data['next_level'] = 0; //Used in actionplan_ui view for Step Sequence Submission positioning to better understand next move
        $view_data['previous_intent'] = null; //Used in actionplan_ui view for Step Sequence Submission positioning to better understand previous move
        $view_data['previous_level'] = 0; //Used in actionplan_ui view for Step Sequence Submission positioning to better understand previous move

        return $view_data;
        
    } else {

        //Keeps track of Tasks:
        $previous_intent = null;
        
        foreach($b['c__child_intents'] as $intent_key=>$intent){

            if($intent['c_status']<1){
                continue;
            }
            
            if($intent['c_id']==$c_id){

                //Found this as level 2:
                $view_data['level'] = 2;
                $view_data['task_index'] = $intent['cr_outbound_rank'];
                $view_data['intent'] = $intent;
                $view_data['title'] = 'Action Plan | '.$CI->lang->line('level_'.$view_data['level'].'_name').' '.$intent['cr_outbound_rank'].': '.$intent['c_objective'];
                $view_data['breadcrumb_p'] = array(
                    array(
                        'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_c_id'],
                        'anchor' => $CI->lang->line('level_'.( isset($b['b_is_parent']) ? $b['b_is_parent'] : 0 ).'_icon').' '.$b['c_objective'],
                    ),
                    array(
                        'link' => null,
                        'anchor' => $CI->lang->line('level_'.$view_data['level'].'_icon').' '.$CI->lang->line('level_'.$view_data['level'].'_name').' '.$intent['cr_outbound_rank'].': '.$intent['c_objective'],
                    ),
                );



                //Find the next intent:
                $next_intent = null;
                $next_level = 0;
                $next_key = $intent_key;

                while(!$next_intent){

                    $next_key++;

                    if(!isset($b['c__child_intents'][$next_key]['c_status'])){

                        //Next Task does not exist, return Bootcamp:
                        $next_intent = $b;
                        $next_level = 1;
                        break;

                    } elseif($b['c__child_intents'][$next_key]['c_status']>=1){

                        $next_intent = $b['c__child_intents'][$next_key];
                        $next_level = 2;
                        break;

                    }
                }

                $view_data['next_intent'] = $next_intent;
                $view_data['next_level'] = $next_level;
                $view_data['previous_intent'] = $previous_intent;
                $view_data['previous_level'] = ( $previous_intent ? 2 : 1 );
                
                return $view_data;
                
            } else {

                //Save this:
                $previous_intent = $intent;

                foreach($intent['c__child_intents'] as $step_key=>$step){

                    if($step['c_status']<1){
                        continue;
                    }

                    if($step['c_id']==$c_id){

                        //This is level 3:
                        $view_data['level'] = 3;
                        $view_data['step_goal'] = $intent; //Only available for Steps
                        $view_data['task_index'] = $intent['cr_outbound_rank'];
                        $view_data['intent'] = $step;
                        $view_data['title'] = 'Action Plan | '.$CI->lang->line('level_'.($view_data['level']-1).'_name').' '.$intent['cr_outbound_rank'].' '.$CI->lang->line('level_'.$view_data['level'].'_name').' '.$step['cr_outbound_rank'].': '.$step['c_objective'];

                        $view_data['breadcrumb_p'] = array(
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$b['b_c_id'],
                                'anchor' => $CI->lang->line('level_'.$b['b_is_parent'].'_icon').' '.$b['c_objective'],
                            ),
                            array(
                                'link' => '/my/actionplan/'.$b['b_id'].'/'.$intent['c_id'],
                                'anchor' => $CI->lang->line('level_'.($view_data['level']-1).'_icon').' '.$CI->lang->line('level_'.($view_data['level']-1).'_name').' '.$intent['cr_outbound_rank'].': '.$intent['c_objective'],
                            ),
                            array(
                                'link' => null,
                                'anchor' => $CI->lang->line('level_'.$view_data['level'].'_icon').' '.$CI->lang->line('level_'.$view_data['level'].'_name').' '.$step['cr_outbound_rank'].': '.$step['c_objective'],
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





function echo_price($b,$support_level=1,$return_double=false,$aggregate_prices=true){

    $price = ( $aggregate_prices ? -1 : 0 ); //Error
    $classroom_offered = ($b['b_p2_max_seats']>0 && $b['b_p2_rate']>0);


    if($support_level==1){

        $price = $b['b_p1_rate'];

    } elseif($support_level==2 && $classroom_offered){

        $price = doubleval(($aggregate_prices ? $b['b_p1_rate'] : 0 ) + $b['b_p2_rate']);

    } elseif($support_level==3 && $classroom_offered && $b['b_p3_rate']>0){

        $price = doubleval(($aggregate_prices ? $b['b_p1_rate'] + $b['b_p2_rate'] : 0 ) + ( $b['b_p3_rate'] * 50 ));

    } elseif($support_level==99){ //Special Support ID to aggregate them all and find the total price

        $price = doubleval($b['b_p1_rate'] + ( $classroom_offered ? $b['b_p2_rate'] + ( $b['b_p3_rate'] * 50 ) : 0 ));

    }


    //Only DIY package:
    if($return_double){

        return $price;

    } else {
        //Need a fancy return for UI:
        if($price==0){
            return 'FREE';
        } elseif($price>0) {
            return '$'.number_format($price,0).'<b style="font-size:0.7em; font-weight:300; padding-left:2px;">USD</b>';
        } else {
            return null;
        }
    }

}


function sec_to_min($sec_int){
    $min = 0;
    $sec = fmod($sec_int,60);
    if($sec_int>=60){
        $min = floor($sec_int/60);
    }
    return ( $min ? $min.'m' : '' ).( $sec ? ( $min ? ' ' : '' ).$sec.'s' : '' );
}

function detect_embed_media($url,$full_message,$require_image=false){

    //$require_image is for Finding the cover photo in YouTube content

    $embed_code = null;
    $prefix_message = null;

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

            if($require_image){
                return '<img src="https://img.youtube.com/vi/'.$video_id.'/0.jpg" class="yt-container" style="padding-bottom:0; margin:-28px 0px;" />';
            }

            //We might also find these in the URL:
            $start_sec = 0;
            $end_sec = 0;
            if(substr_count($url,'start=')>0){
                $start_sec = intval(one_two_explode('start=','&',$url));
            }
            if(substr_count($url,'end=')>0){
                $end_sec = intval(one_two_explode('end=','&',$url));
            }

            //Inform Student that this video has been sliced:
            if($start_sec || $end_sec){
                $embed_code .= '<div class="video-prefix"><i class="fa fa-youtube-play" style="color:#ff0202;" aria-hidden="true"></i> Watch this video from <b>'.($start_sec ? sec_to_min($start_sec) : 'start').'</b> to <b>'.($end_sec ? sec_to_min($end_sec) : 'end').'</b>:</div>';
            }

            $embed_code .= '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/'.$video_id.'?theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start='.$start_sec.( $end_sec ? '&end='.$end_sec : '' ).'" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';

        }

    } elseif(substr_count($url,'vimeo.com/')==1 && !$require_image){

        //Seems to be Vimeo:
        $video_id = trim(one_two_explode('vimeo.com/','?',$url));

        //This should be an integer!
        if(intval($video_id)==$video_id){
            $embed_code = '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://player.vimeo.com/video/'.$video_id.'?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
        }

    } elseif(substr_count($url,'wistia.com/medias/')==1 && !$require_image){

        //Seems to be Wistia:
        $video_id = trim(one_two_explode('wistia.com/medias/','?',$url));

        $embed_code = '<script src="https://fast.wistia.com/embed/medias/'.$video_id.'.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding video-sorting" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_'.$video_id.' seo=false videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>';

    }

    if($embed_code){
        return trim(str_replace($url,$embed_code,$full_message));
    } else {
        //Not matched with an embed rule:
        return false;
    }

}

function clean_url($url){
    return rtrim(str_replace('http://','',str_replace('https://','',str_replace('www.','',$url))),'/');
}


function echo_i($i,$first_name=null,$fb_format=false){
    
    //Must be one of these types:
    if(!isset($i['i_media_type']) || !in_array($i['i_media_type'],array('text','video','audio','image','file'))){
        return false;
    }


    //Do a quick hack to make these two variables inter-changable:
    if(isset($i['i_c_id']) && $i['i_c_id']>0 && !isset($i['e_c_id'])){
        $i['e_c_id'] = $i['i_c_id'];
    } elseif(isset($i['e_c_id']) && $i['e_c_id']>0 && !isset($i['i_c_id'])){
        $i['i_c_id'] = $i['e_c_id'];
    }


    $CI =& get_instance();
    
    if(!$fb_format){
        //HTML format:
        $div_style = ' style="padding:0; margin:0; font-family: Lato, Helvetica, sans-serif; font-size:16px;"'; //We do this for email templates that do not support CSS and also for internal website...
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



        //Does this message include a special command?
        $button_url = null;
        $button_title = null;
        $command = null;

        //Do we have any commands?
        if(substr_count($i['i_message'],'{button}')>0){

            $button_title = 'Open in 🚩Action Plan';
            $command = '{button}';
            $button_url = 'https://mench.com/my/actionplan'; //We assume a basic link to Action Plan

            if(isset($i['i_c_id']) && isset($i['e_b_id']) && isset($i['e_r_id'])){

                //Validate this to make sure it's all Good:
                $bs = fetch_action_plan_copy($i['e_b_id'],$i['e_r_id']);
                $intent_data = extract_level( $bs[0], $i['i_c_id'] );

                //Does this intent belong to this Bootcamp/Class?
                if($intent_data){
                    //Everything looks good:
                    $button_url = 'https://mench.com/my/actionplan/'.$i['e_b_id'].'/'.$i['i_c_id'];
                }
            }

        } elseif(substr_count($i['i_message'],'{admissions}')>0 && isset($i['e_recipient_u_id'])) {

            //Fetch salt:
            $application_status_salt = $CI->config->item('application_status_salt');
            //append their My Account Button/URL:
            $button_title = '🎟️ My Bootcamps';
            $button_url = 'https://mench.com/my/applications?u_key=' . md5($i['e_recipient_u_id'] . $application_status_salt) . '&u_id=' . $i['e_recipient_u_id'];
            $command = '{admissions}';

        } elseif(substr_count($i['i_message'],'{passwordreset}')>0 && isset($i['e_recipient_u_id'])) {

            //append their My Account Button/URL:
            $timestamp = time();
            $button_title = '👉 Set New Password';
            $button_url = 'https://mench.com/my/reset_pass?u_id='.$i['e_recipient_u_id'].'&timestamp='.$timestamp.'&p_hash=' . md5($i['e_recipient_u_id'] . 'p@ssWordR3s3t' . $timestamp);
            $command = '{passwordreset}';

        } elseif(substr_count($i['i_message'],'{messenger}')>0 && isset($i['e_recipient_u_id']) && isset($i['e_b_id'])) {

            //Fetch Facebook Page from Bootcamp:
            $bs = $CI->Db_model->b_fetch(array(
                'b.b_id' => $i['e_b_id'],
            ));

            if(isset($bs[0]['b_fp_id']) && $bs[0]['b_fp_id']>0 && isset($i['e_recipient_u_id']) && $i['e_recipient_u_id']>0){
                $button_url = $CI->Comm_model->fb_activation_url($i['e_recipient_u_id'],$bs[0]['b_fp_id']);
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
                    $i['i_message'] = trim(str_replace($i['i_url'],clean_url($i['i_url']),$i['i_message']));
                }

            } else {

                //Is this a supported embed video URL?
                $embed_html = detect_embed_media($i['i_url'],$i['i_message']);
                if($embed_html){
                    $i['i_message'] = $embed_html;

                    //Facebook Messenger Webview adds an additional button to view full screen:
                    if(isset($i['show_new_window'])){
                        //HTML media format:
                        $i['i_message'] .= '<div><a href="https://mench.com/webview_video/'.$i['i_id'].'" target="_blank">Full Screen in New Window ↗️</a></div>';
                    }

                } else {
                    //HTML format:
                    $i['i_message'] = trim(str_replace($i['i_url'],'<a href="'.$masked_url.'" target="_blank">'.clean_url($i['i_url']).'<i class="fa fa-external-link-square" style="font-size: 0.8em; text-decoration:none; padding-left:4px;" aria-hidden="true"></i></a>',$i['i_message']));
                }

            }
        }





        //Detect the initiator of this message and append their signature to make it clear who is talking
        //RETIRED FOR NOW: As we're moving towards white label...
        /*
        if(isset($i['e_initiator_u_id']) && intval($i['e_initiator_u_id'])>0){
            //We have one, see who it is:
            $matching_users = $CI->Db_model->u_fetch(array(
                'u_id' => $i['e_initiator_u_id'],
            ));
            if(count($matching_users)==1){
                //We found it, append the name:
                //$i['i_message'] .= ' -'.$matching_users[0]['u_fname'].$matching_users[0]['u_lname'];
            } else {
                //$i['i_message'] .= ' -Mench';
            }
        } else {
            //$i['i_message'] .= ' -Mench';
        }
        */


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
            $echo_ui .= '<div '.$div_style.'>'.format_e_message('/attach '.$i['i_media_type'].':'.$i['i_url']).'</div>';

            //Facebook Messenger Webview adds an additional button to view full screen:
            if(isset($i['show_new_window']) && $i['i_media_type']=='video'){
                //HTML media format:
                $echo_ui .= '<div><a href="https://mench.com/webview_video/'.$i['i_id'].'" target="_blank">Full Screen in New Window ↗️</a></div>';
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

function echo_message($i,$level=0,$editing_enabled=true){

    $echo_ui = '';
    $echo_ui .= '<div class="list-group-item is-msg is_sortable all_msg msg_'.$i['i_status'].'" id="ul-nav-'.$i['i_id'].'" iid="'.$i['i_id'].'">';
    $echo_ui .= '<input type="hidden" class="i_media_type" value="'.$i['i_media_type'].'" />';
    $echo_ui .= '<div style="overflow:visible !important;">';
	
	    //Type & Delivery Method:    
	    $echo_ui .= '<div class="'.($i['i_media_type']=='text'?'edit-off text_message':'').'" id="msg_body_'.$i['i_id'].'" style="margin:5px 0 0 0;">';
	    $echo_ui .= echo_i($i);
    	$echo_ui .= '</div>';

    	
    	if($i['i_media_type']=='text'){
    	    //Text editing:
    	    $echo_ui .= '<textarea onkeyup="changeMessageEditing('.$i['i_id'].')" name="i_message" id="message_body_'.$i['i_id'].'" class="edit-on hidden msg msgin" placeholder="Write Message..." style="margin-top: 4px;">'.$i['i_message'].'</textarea>';
    	}
    	
        //Editing menu:
        $echo_ui .= '<ul class="msg-nav">';
        //$echo_ui .= '<li class="edit-off"><i class="fa fa-clock-o"></i> 4s Ago</li>';
        $echo_ui .= '<li class="the_status edit-off" style="margin: 0 6px 0 -3px;">'.status_bible('i',$i['i_status'],1,'right').'</li>';
        if($i['i_media_type']=='text'){
            $CI =& get_instance();
            $message_max = $CI->config->item('message_max');
            $echo_ui .= '<li class="edit-on hidden"><span id="charNumEditing'.$i['i_id'].'">0</span>/'.$message_max.'</li>';
        }
        $echo_ui .= '<li class="edit-off"><span class="on-hover i_uploader">'.echo_uploader($i).'</span></li>';

        if($editing_enabled){
            $echo_ui .= '<li class="edit-off" style="margin: 0 0 0 8px;"><span class="on-hover"><i class="fa fa-bars sort_message" iid="'.$i['i_id'].'" style="color:#3C4858;"></i></span></li>';
            $echo_ui .= '<li class="edit-off" style="margin-right: 10px; margin-left: 6px;"><span class="on-hover"><a href="javascript:i_delete('.$i['i_id'].');"><i class="fa fa-trash" style="margin:0 7px 0 5px;"></i></a></span></li>';
            if($i['i_media_type']=='text' || $level<=2){
                $echo_ui .= '<li class="edit-off" style="margin-left:-4px;"><span class="on-hover"><a href="javascript:msg_start_edit('.$i['i_id'].','.$i['i_status'].');"><i class="fa fa-pencil-square-o"></i></a></span></li>';
            }
            //Right side reverse:
            $echo_ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary" href="javascript:message_save_updates('.$i['i_id'].','.$i['i_status'].');" style="text-decoration:none; font-weight:bold; padding: 1px 8px 4px;"><i class="fa fa-check" aria-hidden="true"></i></a></li>';
            $echo_ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-hidden" href="javascript:msg_cancel_edit('.$i['i_id'].');"><i class="fa fa-times" style="color:#3C4858"></i></a></li>';
            $echo_ui .= '<li class="pull-right edit-on hidden">'.echo_status_dropdown('i','i_status_'.$i['i_id'],$i['i_status'],($level>1?array(-1):array(-1,2)),'dropup',$level,1).'</li>';
            $echo_ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors
        }
        $echo_ui .= '</ul>';
	    
    $echo_ui .= '</div>';
    $echo_ui .= '</div>';
    
    return $echo_ui;
}




function format_hours($decimal_hours,$micro=false){

    if($decimal_hours<=0){

        return '0'.($micro?'h':' Hours ');

    } elseif($decimal_hours<=1.50){

        $decimal_hours = round($decimal_hours*60);
        return $decimal_hours.($micro?'m':' Minutes');

    } elseif($decimal_hours<2 && 0) {

        /*
        $minutes_decimal = fmod($decimal_hours,1);
        $minutes = round(($minutes_decimal-fmod($minutes_decimal,0.083)) * 60);
        $hours = $decimal_hours - $minutes_decimal;
        return $hours.($micro?'h':' Hour'.show_s($hours).' ').($minutes>0 ? $minutes.($micro?'m':' Min'.show_s($minutes)) : '');
        */

    } else {

        //Just round-up:
        return round($decimal_hours).($micro?'h':' Hour'.show_s($decimal_hours));

    }

}

function echo_time($c_time_estimate,$show_icon=1,$micro=false,$c_id=0,$level=0,$c_status=1){

    if($c_time_estimate>0 || $c_id){

        $ui = '<span class="title-sub" style="text-transform:none !important;">';

        if($c_id){

            $ui .= '<span class="slim-time'.( $level<=2?' hours_level_'.$level:'').( $c_status==1 ? '': ' crossout').'" id="t_estimate_'.$c_id.'" current-hours="'.$c_time_estimate.'">'.format_hours( $c_time_estimate,true).'</span>';
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
        $ui .= ' &nbsp; Get in touch using <img data-toggle="tooltip" data-placement="left" title="Facebook Messenger accessible via Console and other devices." src="/img/MessengerIcon.png" class="profile-icon" />';
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



function generate_url_key($string){
    $CI =& get_instance();

    //Strip clean:
    $string = preg_replace("/[^A-Za-z0-9]/", '', $string);

    //Check u_url_key to be unique, and if not, add a number and increment:
    $original_string = $string;
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
            $string = $original_string.$increment;
        }
    }

    return $string;
}

function copy_messages($u_id,$c__messages,$c_id){
    //This function strips and copies all $c__messages to $c_id recorded as $u_id
    $CI =& get_instance();
    $newly_created_messages = array();

    foreach($c__messages as $i){

        if($i['i_status']<=0){
            continue; //Only do active messages, should not happen...
        }

        $new_i = array();
        foreach($i as $key=>$value){
            //Is this a message field?
            if(substr($key,0,2)=='i_' && !in_array($key,array('i_id','i_creator_id','i_c_id','i_timestamp','i_rank'))){
                //Yes, move over:
                $new_i[$key] = $value;
            }
        }

        //Replace creator & c_id
        $new_i['i_creator_id'] = $u_id;
        $new_i['i_c_id'] = $c_id;
        $new_i['i_rank'] = 1 + $CI->Db_model->max_value('v5_messages','i_rank', array(
            'i_status' => $new_i['i_status'],
            'i_c_id' => $c_id,
        ));

        //Create:
        $i_create = $CI->Db_model->i_create($new_i);

        //Append to total stats:
        array_push($newly_created_messages,$i_create);
    }

    return $newly_created_messages;

}

function aggregate_field($input_array,$field){
    $return_array = array();
    foreach($input_array as $item){
        if(isset($item[$field])){
            array_push($return_array,$item[$field]);
        }
    }
    return $return_array;
}

function copy_intent($u_id,$intent,$c_id){

    if($intent['c_status']<0){
        return array();
    }

    $CI =& get_instance();
    $new_c = array();
    foreach($intent as $key=>$value){
        //Is this a message field?
        if(!(substr($key,0,3)=='c__') && substr($key,0,2)=='c_' && !in_array($key,array('c_id','c_timestamp','c_creator_id'))){
            //Yes, move over:
            $new_c[$key] = $value;
        }
    }

    //Append creator:
    $new_c['c_creator_id'] = $u_id;

    //Create intent:
    $new_intent = $CI->Db_model->c_create($new_c);

    //Create Link:
    $intent_relation = $CI->Db_model->cr_create(array(
        'cr_creator_id' => $u_id,
        'cr_inbound_id'  => $c_id,
        'cr_outbound_id' => $new_intent['c_id'],
        'cr_outbound_rank' => 1 + $CI->Db_model->max_value('v5_intent_links','cr_outbound_rank', array(
            'cr_status >=' => 1,
            'c_status >=' => 1,
            'cr_inbound_id' => $c_id,
        )),
    ));

    //Return full package:
    $new_intents = $CI->Db_model->cr_outbound_fetch(array(
        'cr.cr_id' => $intent_relation['cr_id'],
    ));

    return $new_intents[0];

}


function echo_cr($b,$intent,$level=0,$parent_c_id=0,$editing_enabled=true){
    
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $clean_title = preg_replace("/[^A-Za-z0-9 ]/", "", $intent['c_objective']);
    $clean_title = (strlen($clean_title)>0 ? $clean_title : 'This Intent');
    $default_time = ( $b['b_is_parent'] ? 0 : 0.05 );
    $intent['c__estimated_hours'] = ( isset($intent['c__estimated_hours']) ? $intent['c__estimated_hours'] : $intent['c_time_estimate'] );
    $intent['c__estimated_hours'] = ( $level>1 && $intent['c__estimated_hours']==0 ? $default_time : $intent['c__estimated_hours'] );
    $child_enabled = ((isset($intent['c__child_intents']) && count($intent['c__child_intents'])>0) || !isset($b['b_old_format']) || ($udata['u_status']==3 && $b['b_old_format']));

    if(!$editing_enabled && $intent['c_status']<1){
        //Do not show drafting items in read-only mode:
        return false;
    }

    if($level==1){

        //Bootcamp Outcome:
        $ui = '<div id="obj-title" class="list-group-item">';

    } else {

        //ATTENTION: DO NOT CHANGE THE ORDER OF data-link-id & intent-id AS the sorting logic depends on their exact position to sort!

        //CHANGE WITH CAUTION!

        $ui = '<div id="cr_'.$intent['cr_id'].'" data-link-id="'.$intent['cr_id'].'" intent-id="'.$intent['c_id'].'" class="list-group-item '.( $level>2 ? 'is_step_sortable' : 'is_sortable' ).' intent_line_'.$intent['c_id'].'">';

    }


    //Right content
    $ui .= '<span class="pull-right maplevel'.$intent['c_id'].'" level-id="'.$level.'" parent-intent-id="'.$parent_c_id.'" style="'.( $level<3 ? 'margin-right: 8px;' : '' ).'">';



        //Enable total hours/Task reporting...
        if($level<=2){
            $ui .= echo_time($intent['c__estimated_hours'],1,1, $intent['c_id'], $level, $intent['c_status']);
        } else {
            $ui .= echo_time($intent['c_time_estimate'],1,1, $intent['c_id'], $level, $intent['c_status']);
        }


        if($b['b_is_parent'] && $level==2){

            //The Bootcamp of multi-week Bootcamps
            $ui .= '<a class="badge badge-primary" style="margin-right:-1px; width:34px;" href="javascript:delete_b('.$intent['cr_outbound_b_id'].','.$intent['cr_id'].');"><i class="fa fa-trash"></i></a> &nbsp;';

            $ui .= '<a class="badge badge-primary" style="margin-right:1px; width:60px;" href="/console/'.$intent['cr_outbound_b_id'].'"><i class="fa fa-chevron-right"></i></a>';

        } elseif(!$b['b_is_parent'] || $level==1) {

            if($editing_enabled){
                if(!$b['b_old_format'] || $udata['u_status']==3){
                    $ui .= '<a class="badge badge-primary" onclick="load_modify('.$intent['c_id'].','.$level.')" style="margin-right: -1px;" href="#modify-'.$intent['c_id'].'"><i class="fa fa-pencil-square-o"></i></a> &nbsp;';
                }

                $ui .= '<a href="#messages-'.$intent['c_id'].'" onclick="i_load_frame('.$intent['c_id'].','.$level.')" class="badge badge-primary badge-msg"><span id="messages-counter-'.$intent['c_id'].'">'.( isset($intent['c__messages']) ? count($intent['c__messages']) : 0 ).'</span> <i class="fa fa-commenting" aria-hidden="true"></i></a>';
            } else {
                //Show link to current section:
                $ui .= '<a href="javascript:void(0);" onclick="$(\'#messages_'.$intent['c_id'].'\').toggle();" class="badge badge-primary badge-msg"><span id="messages-counter-'.$intent['c_id'].'">'.( isset($intent['c__messages']) ? count($intent['c__messages']) : 0 ).'</span> <i class="fa fa-commenting" aria-hidden="true"></i></a>';
            }

        }


    //Keep an eye out for inner message counter changes:
    $ui .= '</span> ';



    //Sorting & Then Left Content:
    if($level>1 && $editing_enabled && (!$b['b_is_parent'] || $level==2)) {
        $ui .= '<i class="fa fa-bars" aria-hidden="true"></i> &nbsp;';
    }


    if($level==1){

        //Bootcamp Outcome:
        $ui .= '<span><b id="b_objective" style="font-size: 1.3em;"><i class="fa '.( isset($b['b_is_parent']) && $b['b_is_parent'] ? 'fa-folder-open' : 'fa-dot-circle-o' ).'" style="margin-right:3px;" aria-hidden="true"></i><span class="c_objective_'.$intent['c_id'].'">'.$intent['c_objective'].'</span></b></span>';

    } elseif($level==2){

        //Task:
        //( !(level==2) || increments<=1 ? sort_rank : sort_rank+'-'+(sort_rank + increments - 1))
        $ui .= '<span class="inline-level">';

        if($child_enabled){
            $ui .= '<a href="javascript:ms_toggle('.$intent['c_id'].');"><i id="handle-'.$intent['c_id'].'" class="fa fa-plus-square-o" aria-hidden="true"></i></a> &nbsp;';
        }

        $ui .= '<span class="inline-level-'.$level.'">'.( $b['b_is_parent'] ? 'Week' : $CI->lang->line('level_2_name')).' #'.$intent['cr_outbound_rank'].'</span>';
        $ui .= '</span>';

        $ui .= '<b id="title_'.$intent['cr_id'].'" class="cdr_crnt c_objective_'.$intent['c_id'].'" extension-rule="'.@$intent['c_extension_rule'].'" parent-intent-id="" outbound-rank="'.$intent['cr_outbound_rank'].'" current-status="'.$intent['c_status'].'" c_complete_url_required="'.($intent['c_complete_url_required']=='t'?1:0).'"  c_complete_notes_required="'.($intent['c_complete_notes_required']=='t'?1:0).'">'.$intent['c_objective'].'</b> ';

    } elseif ($level>=3){

        //Steps
        $ui .= '<span class="inline-level inline-level-'.$level.'">'.( $intent['c_status']==1 ? $CI->lang->line('level_'.( $b['b_is_parent'] ? '2' : '3' ).'_name').' #'.$intent['cr_outbound_rank'] : '<b><i class="fa fa-pencil-square" aria-hidden="true"></i></b>' ).'</span><span id="title_'.$intent['cr_id'].'" class="c_objective_'.$intent['c_id'].'" current-status="'.$intent['c_status'].'" outbound-rank="'.$intent['cr_outbound_rank'].'" c_complete_url_required="'.($intent['c_complete_url_required']=='t'?1:0).'"  c_complete_notes_required="'.($intent['c_complete_notes_required']=='t'?1:0).'">'.$intent['c_objective'].'</span> ';

    }

    //For Class Action Plan Copy show the messages inline:
    if(!$editing_enabled){
        //Show Message Preview:
        $ui .= '<div id="messages_'.$intent['c_id'].'" class="messages-inline">';
        foreach($intent['c__messages'] as $i){
            $ui .= echo_message($i,$level, false);
        }
        $ui .= '</div>';
    }

    //Any Steps?
    if($level==2){

        $ui .= '<div id="list-outbound-'.$intent['c_id'].'" class="list-group step-group hidden" intent-id="'.$intent['c_id'].'">';
        //This line enables the in-between list moves to happen for empty lists:
        $ui .= '<div class="is_step_sortable dropin-box" style="height:1px;">&nbsp;</div>';
        if(isset($intent['c__child_intents']) && count($intent['c__child_intents'])>0){
            foreach($intent['c__child_intents'] as $sub_intent){
                $ui .= echo_cr($b,$sub_intent,($level+1),$intent['c_id'],$editing_enabled);
            }
        }

        //Step Input field:
        if($editing_enabled && $child_enabled && !$b['b_is_parent']){
            $ui .= '<div class="list-group-item list_input new-step-input">
                <div class="input-group">
                    <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="new_intent('.$intent['c_id'].','.($level+1).');" intent-id="'.$intent['c_id'].'"><input type="text" class="form-control autosearch"  maxlength="70" id="addintent'.$intent['c_id'].'" placeholder=""></form></div>
                    <span class="input-group-addon" style="padding-right:8px;">
                        <span data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" onclick="new_intent('.$intent['c_id'].','.($level+1).');" class="badge badge-primary pull-right" intent-id="'.$intent['c_id'].'" style="cursor:pointer; margin: 13px -6px 1px 13px;">
                            <div><i class="fa fa-plus"></i></div>
                        </span>
                    </span>
                </div>
            </div>';
        }


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
    $b_ui .= '<span class="pull-right"><span class="badge badge-primary"><i class="fa fa-chevron-right" aria-hidden="true"></i></span></span>';
    $b_ui .= '<i class="fa '.( $b['b_is_parent'] ? 'fa-folder-open' : 'fa-dot-circle-o' ).'" aria-hidden="true" style="margin: 0 8px 0 2px; color:#222;"></i> ';
    $b_ui .= $b['c_objective'];

    if($all_students>0){
        $b_ui .= ' &nbsp;<b style="color:#3C4858;" data-toggle="tooltip" data-placement="top" title="This Bootcamp has '.$all_students.' all-time Student'.show_s($all_students).'"><i class="fa fa-user" aria-hidden="true"></i> '.$all_students.'</b>';
    }

    $b_ui .= ( $b['b_old_format'] ? ' &nbsp;<b style="color:#FF0000;"><i class="fa fa-lock" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Bootcamp created with older version of Mench. You can import its Action Plan into a new Bootcamp."></i></b>' : '' );
    $b_ui .= '</a>';
    return $b_ui;
}

function echo_json($array){
    header('Content-Type: application/json');
    echo json_encode($array);
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

function b_aggregate($b,$skip_parent=false){

    //Aggregate this from child-Bootcamps:
    $b_aggregate = array(
        'b_prerequisites'   => ( strlen($b['b_prerequisites'])>0 && !$skip_parent ? json_decode($b['b_prerequisites']) : array() ),
        'b_transformations' => ( strlen($b['b_transformations'])>0 && !$skip_parent ? json_decode($b['b_transformations']) : array() ),
    );

    //Unset some unnecessary fields that do not make sense for a parent Bootcamp:
    unset($b['b_support_email']);
    unset($b['b_calendly_url']);

    //Set price to zero:
    $b['child_bs'] = array();
    $b['b_p1_rate'] = 0;
    $b['b_p2_rate'] = 0;
    $b['b_p3_rate'] = 0;
    $b['b_p2_weeks'] = 0; //Defines how many weeks this is offered
    $b['b_p3_weeks'] = 0; //Defines how many weeks this is offered
    $b['b_p2_max_seats'] = 0; //Would be offered if any of sub-Bootcamps offer
    $b['b_difficulty_level'] = 0; //Not set

    $CI =& get_instance();

    //Fetch all child Bootcamp details:
    foreach($b['c__child_intents'] as $b7d){

        //Fetch Bootcamp URL key:
        $bs = $CI->Db_model->b_fetch(array(
            'b.b_id' => $b7d['cr_outbound_b_id'],
        ));

        //This this as child bootcamp
        $b['child_bs'][$b7d['cr_outbound_b_id']] = $bs[0];

        if(strlen($bs[0]['b_transformations'])>0){
            foreach (json_decode($bs[0]['b_transformations']) as $item){
                if(!in_array($item,$b_aggregate['b_transformations'])){
                    array_push($b_aggregate['b_transformations'],$item);
                }
            }
        }
        if(strlen($bs[0]['b_prerequisites'])>0){
            foreach (json_decode($bs[0]['b_prerequisites']) as $item){
                if(!in_array($item,$b_aggregate['b_prerequisites'])){
                    array_push($b_aggregate['b_prerequisites'],$item);
                }
            }
        }

        //Addup the rates:
        $b['b_p1_rate'] += doubleval($bs[0]['b_p1_rate']);
        if(intval($bs[0]['b_p2_max_seats'])>0){

            $b['b_p2_weeks']++;
            $b['b_p2_rate'] += doubleval($bs[0]['b_p2_rate']);
            $b['b_p3_rate'] += doubleval($bs[0]['b_p3_rate']);

            if($bs[0]['b_p2_max_seats']>$b['b_p2_max_seats']){
                //This is the most difficult child Bootcamp, set this as the overall difficulty:
                $b['b_p2_max_seats'] = $bs[0]['b_p2_max_seats'];
            }

            if($bs[0]['b_p3_rate']>0){
                $b['b_p3_weeks']++;
            }
        }

        //Max Difficulty level:
        if(intval($bs[0]['b_difficulty_level'])>$b['b_difficulty_level']){
            //This is the most difficult child Bootcamp, set this as the overall difficulty:
            $b['b_difficulty_level'] = intval($bs[0]['b_difficulty_level']);
        }
    }

    //Encode like original data:
    $b['b_transformations'] = ( count($b_aggregate['b_transformations'])>0 ? json_encode($b_aggregate['b_transformations']) : null);
    $b['b_prerequisites'] = ( count($b_aggregate['b_prerequisites'])>0 ? json_encode($b_aggregate['b_prerequisites']) : null);

    return $b;
}


function prep_prerequisites($b){
    $week_count = ( $b['b_is_parent'] ? count($b['c__child_intents']) : 1 );
    //Appends system-enforced prerequisites based on Bootcamp settings:
    $pre_req_array = ( strlen($b['b_prerequisites'])>0 ? json_decode($b['b_prerequisites']) : array() );
    if($b['c__estimated_hours']>0){
        array_unshift($pre_req_array, 'Commitment to invest <i class="fa fa-clock-o" aria-hidden="true"></i> <b>'.format_hours($b['c__estimated_hours']).' in '.$week_count.' Week'.show_s($week_count).'</b> anytime that works best for you. (Average '.format_hours($b['c__estimated_hours']/($week_count*7)) .' per day)');
    }
    return $pre_req_array;
}


function b_progress($b){
    
    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    //This must exist:
    $bl = ( isset($b['b__admins'][0]) ? $b['b__admins'][0] : null );

    //A function used on the dashboard to indicate what is left before launching the Bootcamp
    $progress_possible = 0; //Total points of progress
    $progress_gained = 0; //Points granted for completion
    $checklist = array();



    if(!$b['b_is_parent']){
        //Facebook Page
        $estimated_minutes = 15;
        $progress_possible += $estimated_minutes;
        $us_status = ( $b['b_fp_id']>0 && (!($b['b_fp_id']==4) || $bl['u_status']==3) ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/settings#pages',
            'anchor' => '<b>Connect your <i class="fa fa-facebook-official" aria-hidden="true" style="color:#4267b2;"></i> Facebook Page</b> in Settings (also activates Landing Page)',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }



    //Do we have enough Children?
    $estimated_minutes = 60;
    $required_children = ( $b['b_is_parent'] ? 2 : 3 );
    $child_name = ( $b['b_is_parent'] ? 'Bootcamp' : 'Task' );
    $progress_possible += $estimated_minutes;
    $us_status = ( count($b['c__child_intents'])>=$required_children ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : (count($b['c__child_intents'])/$required_children)*$estimated_minutes );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan',
        'anchor' => '<b>Add '.$required_children.' or more '.$child_name.'s</b>'.( count($b['c__child_intents'])>0 && !$us_status ?' ('.($required_children-count($b['c__child_intents'])).' more)':'').' in Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));

    
    
    if(count($b['c__child_intents'])>0 && !$b['b_is_parent']){
        //Now check each Task and its Step List:
        foreach($b['c__child_intents'] as $intent_num=>$c){

            if($c['c_status']<0){
                continue; //Don't check Archived Tasks
            }

            //Prepare key variables:
            $intent_anchor = ' #'.$c['cr_outbound_rank'].' ';


            //Task On Start Messages
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
                'anchor' => '<b>Add '.status_bible('i',1).' Message</b> to '.$intent_anchor.$c['c_objective'],
                'us_status' => $us_status,
                'time_min' => $estimated_minutes,
            ));


            //We only need Steps if:
            /*
            if($c['c_extension_rule']>=1){
                $estimated_minutes = 30;
                $progress_possible += $estimated_minutes;
                $us_status = ( isset($c['c__child_intents']) && count($c['c__child_intents'])>=1 ? 1 : 0 );
                $progress_gained += ( $us_status ? $estimated_minutes : (count($c['c__child_intents']))*$estimated_minutes );
                array_push( $checklist , array(
                    'href' => '/console/'.$b['b_id'].'/actionplan',
                    'anchor' => '<b>Add a Step</b>'.(count($c['c__child_intents'])>0 && !$us_status?' ('.(1-count($c['c__child_intents'])).' more)':'').' to '.$intent_anchor.$c['c_objective'],
                    'us_status' => $us_status,
                    'time_min' => $estimated_minutes,
                ));

                //Check Steps:
                if(isset($c['c__child_intents']) && count($c['c__child_intents'])>0){
                    foreach($c['c__child_intents'] as $c2){

                        //Create Step object:
                        $step_anchor = $intent_anchor.'Step #'.$c2['cr_outbound_rank'].' '.$c2['c_objective'];

                        //Messages for Steps:
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
                            'anchor' => '<b>Add '.status_bible('i',1).' Message</b> to '.$step_anchor,
                            'us_status' => $us_status,
                            'time_min' => $estimated_minutes,
                        ));
                    }
                }
            }
            */
        }
    }


    //Bootcamp Messages:
    $estimated_minutes = 15;
    $progress_possible += $estimated_minutes;
    $qualified_messages = 0;
    if(count($b['c__messages'])>0){
        foreach($b['c__messages'] as $i){
            $qualified_messages += ( $i['i_status']==1 && ( $i['i_media_type']=='image' || ($i['i_media_type']=='text' && strlen($i['i_url'])>0 && detect_embed_media($i['i_url'],$i['i_message'],true))) ? 1 : 0 );
        }
    }
    $us_status = ( $qualified_messages ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/actionplan#messages-'.$b['b_c_id'],
        'anchor' => '<b>Upload an Image or add YouTube Link</b> for your cover photo in Action Plan',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));







    if(!$b['b_is_parent']){
        //Prerequisites
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($b['b_prerequisites'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/actionplan#prerequisites',
            'anchor' => '<b>Set 1 or more Prerequisites</b> for your Bootcamp in Action Plan',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));


        //Skills You Will Gain
        $estimated_minutes = 30;
        $progress_possible += $estimated_minutes;
        $us_status = ( strlen($b['b_transformations'])>0 ? 1 : 0 );
        $progress_gained += ( $us_status ? $estimated_minutes : 0 );
        array_push( $checklist , array(
            'href' => '/console/'.$b['b_id'].'/actionplan#skills',
            'anchor' => '<b>Define Skills You Will Gain</b> in Action Plan',
            'us_status' => $us_status,
            'time_min' => $estimated_minutes,
        ));
    }





    
    
    /* *******************************
     *  Leader profile (for them only)
     *********************************/
    if($bl){
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
     *  Settings
     *******************************/

    if(!$b['b_is_parent']){
        //Offer Classroom Package?
        if($b['b_p2_max_seats']>0){
            $estimated_minutes = 15;
            $progress_possible += $estimated_minutes;
            $us_status = ( strlen($b['b_support_email'])>=1 ? 1 : 0 );
            $progress_gained += ( $us_status ? $estimated_minutes : 0 );
            array_push( $checklist , array(
                'href' => '/console/'.$b['b_id'].'/settings#support',
                'anchor' => '<b>Enter Support Email Address</b> in Settings',
                'us_status' => $us_status,
                'time_min' => $estimated_minutes,
            ));
        }


        //Offer Tutoring?
        if($b['b_p3_rate']>0){
            $estimated_minutes = 15;
            $progress_possible += $estimated_minutes;
            $us_status = ( strlen($b['b_calendly_url'])>=1 ? 1 : 0 );
            $progress_gained += ( $us_status ? $estimated_minutes : 0 );
            array_push( $checklist , array(
                'href' => '/console/'.$b['b_id'].'/settings#support',
                'anchor' => '<b>Enter Calendly URL</b> for Tutoring Bookings in Settings',
                'us_status' => $us_status,
                'time_min' => $estimated_minutes,
            ));
        }
    }



    //Landing Page Category
    $current_inbounds = $CI->Db_model->cr_inbound_fetch(array(
        'cr.cr_outbound_id' => $b['b_c_id'],
        'cr.cr_status' => 1,
    ));
    $estimated_minutes = 15;
    $progress_possible += $estimated_minutes;
    $us_status = ( count($current_inbounds)>0 ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/settings#landingpage',
        'anchor' => '<b>Choose Category</b> in Settings',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));


    // Required Experience Level
    $estimated_minutes = 15;
    $progress_possible += $estimated_minutes;
    $us_status = ( $b['b_difficulty_level']>0 ? 1 : 0 );
    $progress_gained += ( $us_status ? $estimated_minutes : 0 );
    array_push( $checklist , array(
        'href' => '/console/'.$b['b_id'].'/settings#landingpage',
        'anchor' => '<b>Choose Required Experience Level</b> in Settings',
        'us_status' => $us_status,
        'time_min' => $estimated_minutes,
    ));




    
    
    //Return the final message:
    return array(
        'stage' => '<i class="fa fa-steps" aria-hidden="true" title="Gained '.$progress_gained.'/'.$progress_possible.' points"></i> <i class="fa fa-rocket" aria-hidden="true"></i> Launch Checklist',
        'progress' => round($progress_gained/$progress_possible*100),
        'check_list' => $checklist,
    );
}


function echo_r($b,$class,$append_class=null){

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $class_settings = $CI->config->item('class_settings');
    $guided_admissions = count($CI->Db_model->ru_fetch(array(
        'ru_r_id' => $class['r_id'],
        'ru_status >=' => 4,
        'ru_p2_price >' => 0,
    )));

    echo '<li class="list-group-item '.$append_class.'">';

    echo '<span class="pull-right">';
    if($class['r__current_admissions']>0){

        //How many students, if any, are enrolled in support packages?
        echo '<a href="#class-'.$class['r_id'].'" onclick="load_class('.$class['r_id'].')" class="badge badge-primary" style="text-decoration: none;">'.$class['r__current_admissions'].' <i class="fa fa-chevron-right" aria-hidden="true"></i></a>';

    } else {

        echo '<span class="badge badge-primary grey" data-toggle="tooltip" data-placement="right" title="No Students Yet">0</span>';

    }
    echo '</span>';

    //Determine the state of the Checkbox:
    if($guided_admissions>0 || $class['r_status']>=2 || !($b['b__admins'][0]['u_id']==$udata['u_id'])){

        //Locked:
        echo '<span class="badge badge-primary '.( $guided_admissions==0 ? 'grey' : '' ).'">';
        echo status_bible('r',$class['r_status'],true, 'right');
        if($guided_admissions>0){
            echo ' <span data-toggle="tooltip" data-placement="right" title="'.$guided_admissions.'/'.$b['b_p2_max_seats'].' Classroom Seats are Full">'.$guided_admissions.'</span>';
        }
        echo '</span>';

    } else {

        //See what the Lead Instructor's calendar looks like:
        if(strlen($b['b__admins'][0]['u_weeks_off'])>0){

            //They have some days that are booked off:
            $current_status = ( in_array($class['r_start_date'],unserialize($b['b__admins'][0]['u_weeks_off'])) ? 1 : 2 );

        } else {
            //Classroom package #2 is Available as they have no Weeks off:
            $current_status = 2;
        }

        //Can still change:
        echo '<a href="javascript:void(0);" onclick="toggle_support('.$class['r_id'].')" id="support_toggle_'.$class['r_id'].'" class="badge badge-primary '.( $current_status==1 ? 'grey' : '' ).'" style="text-decoration: none;" current-status="'.$current_status.'" data-toggle="tooltip" data-placement="right" title="Toggle support across all your Bootcamps/Classes for the week of '.time_format($class['r_start_date'],4).'. Yellow = Support Available Grey = Do It Yourself Only">'.status_bible('rs',$current_status,true, null).'</a>';

    }

    echo ' <span title="Class ID '.$class['r_id'].'">'.time_format($class['r_start_date'],1).'</span>';

    echo ' <i class="fa fa-eye-slash not_published" data-toggle="tooltip" data-placement="top" title="Class not published yet. Mench accepts admissions only for the upcoming '.$class_settings['students_show_max'].' Classes." aria-hidden="true"></i>';

    echo '</li>';
}




function tree_menu($c,$current_c_ids,$format='list',$level=1){

    $CI =& get_instance();
    $ui = null;

    if(!is_array($c) && intval($c)>0){
        $cs = $CI->Db_model->c_fetch(array(
            'c_id' => $c,
        ));
        $c = $cs[0];
    }

    //Fetch children:
    $c_child = $CI->Db_model->cr_outbound_fetch(array(
        'cr.cr_inbound_id' => $c['c_id'],
        'cr.cr_status >' => 0,
        'c.c_status >' => 0, //Use status to control menu item visibility
    ));


    if($level==1){
        if($format=='list'){
            $ui .= '<div class="list-group">';
        }
    }



    if($format=='list'){

        //Show the item:
        $ui .= '<a href="/'.$c['c_id'].'" class="list-group-item '.( in_array($c['c_id'],$current_c_ids) ? 'active' :'').'" style="'.($level==3 ? 'padding-left:20px;' : '').'; text-decoration:none;">';
        $ui .= '<span class="pull-right">';
        $ui .= '<span class="badge badge-primary">'.count($c_child).' <i class="fa fa-chevron-right" aria-hidden="true"></i></span>';
        $ui .= '</span>';
        $ui .= '<span style="font-weight:'.($level<=2 ? 'bold' :'normal').';">'.$c['c_objective'].'</span>';
        $ui .= '</a>';

    } elseif($format=='select' && $level<=2){

        $ui .= '<select data-c-id="'.$c['c_id'].'" id="c_s_'.$c['c_id'].'" class="border c_select level'.$level.' '.( isset($c['cr_outbound_id']) ? 'outbound_c_'.$c['cr_outbound_id'] : '' ).' '.( $level==2 ? 'hidden' : '' ).'" style="width:100%; margin-bottom:10px; max-width:380px;">';
        //$ui .= '<option value="0">Choose...</option>'; //Not needed for now as we transition to single level categories
        foreach($c_child as $child_intent){
            $ui .= '<option value="'.$child_intent['c_id'].'" '.( in_array($child_intent['c_id'],$current_c_ids) ?'selected="selected"':'').'>'.$child_intent['c_objective'].'</option>';
        }
        $ui .= '</select>';

    }

    //Which level?
    if($level<=2){
        //Loop through children
        foreach($c_child as $child_intent){
            $ui .= tree_menu($child_intent,$current_c_ids,$format,($level+1));
        }
    }


    if($level==1){
        if($format=='list'){
            $ui .= '</div>';
        }
    }

    return $ui;
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
    //Never got around estimating the time of each Step, as it seemed a bit arbitrary to do so...
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
    $inner_tooltip = ($direction=='dropup'?null:'top');

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

function class_status_change($current_status,$has_applicants){
    if($current_status==-3){
        return array(-2,-1,0,1,2,3);
    } elseif($current_status==-2){
        return array(-3,-1,0,1,2,3);
    } elseif($current_status==-1){
        return array(-3,-2,2,3);
    } elseif($current_status==0){
        return array(-3,-2,2,3);
    } elseif($current_status==1 && $has_applicants){
        return array(-2,-1,0,2,3);
    } elseif($current_status==1 && !$has_applicants){
        return array(-3,-2,2,3);
    } elseif($current_status==2){
        return array(-2,-1,0,1,3);
    } elseif($current_status==3){
        return array(-3,-2,-1,0,1,2);
    } else {
        //Should not happen!
        return array();
    }
}

function status_bible($object=null,$status=null,$micro_status=false,$data_placement='bottom'){
	
    //IF you make any changes, make sure to also reflect in the status_bible.php as well
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
            return '<span class="status-label" '.( isset($result['s_desc']) && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="'.$data_placement.'" title="'.$result['s_desc'].'" aria-hidden="true" style="border-bottom:1px dotted #444; padding-bottom:1px;"':'style="cursor:pointer;"').'><i class="fa '.( isset($result['s_mini_icon']) ? $result['s_mini_icon'] : 'fa-circle' ).' initial"></i>'.($micro_status?'':$result['s_name']).'</span>';
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
	    
	    //Fetch Bootcamp admins and see if they have access to this:
	    $b_instructors = $CI->Db_model->ba_fetch(array(
	        'ba.ba_b_id' => $b_id,
	        'ba.ba_status >=' => 1, //Must be an actively assigned instructor
	        'u.u_status >=' => 1, //Must be a user level 1 or higher
	        'u.u_id' => $udata['u_id'],
	    ));
	    
	    if(count($b_instructors)>0){
	        //Append permissions here:
	        $udata['project_permissions'] = $b_instructors[0];
	        //Instructor is part of the Bootcamp:
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
	    redirect_message( ( isset($udata['u_id']) && (intval($udata['u_status'])>=2 || (intval($udata['u_status'])==1 && isset($udata['project_permissions']))) ? '/console' : '/login?url='.urlencode($_SERVER['REQUEST_URI']) ),'<div class="alert alert-danger maxout" role="alert">'.( isset($udata['u_id']) ? 'Access not authorized.' : 'Session Expired. Login to continue.' ).'</div>');
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

function redirect_message($url,$message=null, $response_code=null){

    //Do we have a Message?
    if($message){
        $CI =& get_instance();
        $CI->session->set_flashdata('hm', $message);
    }

    //What's the default response code?
    $response_code = ( !$response_code && !$message ? 301 : ( $response_code ? $response_code : null ) );
    if($response_code) {
        header("Location: ".$url, true, $response_code);
    } else {
        header("Location: ".$url, true);
    }
	die();
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
                'e_json' => $json_data,
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
        return echo_ordinal($rank);
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

function time_format($t,$format=0,$adjust_seconds=0){
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
    }
}

function message_validation($i_status,$i_message,$i_media_type=null /*Only set for editing*/){

    if(in_array($i_media_type,array('video','image','audio','file'))){
        return array(
            'status' => 1,
            'urls' => 'Media images cannot be edited',
        );
    }

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
    $id = intval($id);
    
    if($id>0){
        //Used mainly for engagement tracking
        $website = $CI->config->item('website');
        
        if($object=='c'){
            //Fetch intent/Step:
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
            
            $bs = $CI->Db_model->b_fetch(array(
                'b.b_id' => $id,
            ), array('c'));
            if(isset($bs[0])){
                if($b_id){
                    return '<a href="'.$website['url'].'console/'.$bs[0]['b_id'].'">'.$bs[0]['c_objective'].'</a>';
                } else {
                    return $bs[0]['c_objective'];
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
                    return '<a href="/cockpit/browse/engagements?e_u_id='.$id.'" title="User ID '.$id.'">'.$matching_users[0]['u_fname'].' '.$matching_users[0]['u_lname'].'</a>';
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
        } elseif($object=='fp'){
            $pages = $CI->Db_model->fp_fetch(array(
                'fp_id' => $id,
            ));
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
	$return_string .= '<div class="list-group-item-text hover intent_stats"><div>';
	
	//Collector:
	$return_string .= '<span><a href="/"><img src="https://www.gravatar.com/avatar/'.md5('ssasif').'?d=identicon" class="mini-image" /></a></span>';
	
	//COPY LANDING PAGE:
	$return_string .= ' <span title="Click to Copy URL to share Plugin on Messenger." data-toggle="tooltip" class="hastt clickcopy" data-clipboard-text="httpurlhere"><img src="/img/messenger.png" class="action_icon" /><b>112233</b></span>';
	
	//Date
	$return_string .= '<span title="Added TIME UTC" data-toggle="tooltip" class="hastt"><span class="glyphicon glyphicon-time" aria-hidden="true" style="margin-right:2px;"></span>TIME</span>';
	
	/*
	//Update ID
	$return_string .= '<span title="Unique Update ID assigned per each edit." data-toggle="tooltip" class="hastt">#'.$intent[$key]['id'].'</span>';
	
	if(auth_admin(1)){
		$return_string .= '<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span></button>';
		$return_string .= '<ul class="dropdown-menu">';
		$return_string .= '<li><a href="javascript:edit_link('.$key.','.$intent[$key]['id'].')" class="edit_link"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Edit</a></li>';
		
		//Make sure this is not a grandpa before showing the delete button:
		$grandparents = $CI->config->item('grand_parents');
		if(!($key==0 && array_key_exists($intent[$key]['intent_id'],$grandparents))){
			$return_string .= '<li><a href="javascript:delete_link('.$key.','.$intent[$key]['id'].');"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span> Remove</a></li>'
		}
		
		//Add search shortcuts:
		$return_string .= '<li><a href="https://www.google.com/search?q='.urlencode($intent[$key]['value']).'" target="_blank"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Google</a></li>';
		$return_string .= '<li><a href="https://www.youtube.com/results?search_query='.urlencode($intent[$key]['value']).'" target="_blank"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> YouTube</a></li>';
		
		//Display inversing if NOT direct
		if(!$is_direct){
			//TODO $return_string .= '<li><a href="javascript:inverse_link('.$key.','.$intent[$key]['id'].')"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Flip Direction</a></li>';
		}
		if($intent[$key]['update_id']>0){
			//This gem has previous revisions:
			//TODO $return_string .= '<li><a href="javascript:browse_revisions('.$key.','.$intent[$key]['id'].')"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span> Revisions</a></li>';
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
    echo '<div style="margin:10px 0 10px;"><span class="status-label" style="color:#3C4858;"><i class="fa fa-clock-o initial"></i>Completion Time:</span> '.time_format($us_data['us_timestamp']).' PST</div>';
    //echo '<div style="margin:15px 0 10px;">Congratulations for completing this '.echo_time($us_data['us_time_estimate'],1).'Step on '.time_format($us_data['us_timestamp']).'</div>';
    echo '<div style="margin-bottom:10px;"><span class="status-label" style="color:#3C4858;"><i class="fa fa-file-text initial"></i>Your Comments:</span> '.( strlen($us_data['us_student_notes'])>0 ? make_links_clickable(nl2br(htmlentities($us_data['us_student_notes']))) : 'None' ).'</div>';
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


































