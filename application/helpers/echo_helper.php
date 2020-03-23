<?php

function echo_en_load_more($page, $limit, $en__child_count)
{
    /*
     * Gives an option to "Load More" players when we have too many to show in one go
     * */

    $ui = '<div class="load-more montserrat list-group-item itemplay no-left-padding" style="padding-bottom:20px;"><a href="javascript:void(0);" onclick="en_load_next_page(' . $page . ', 0)">';

    //Regular section:
    $max_players = (($page + 1) * $limit);
    $max_players = ($max_players > $en__child_count ? $en__child_count : $max_players);
    $ui .= '<span class="icon-block"><i class="far fa-search-plus"></i></span>LOAD MORE';
    $ui .= '</a></div>';

    return $ui;
}

function echo_clean_db_name($field_name){
    //Takes a database field name and returns a clean version of it:
    if(substr($field_name, 0, 3) == 'ln_'){
        //Link field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('ln_', 'Link ', $field_name))));
    } elseif(substr($field_name, 0, 3) == 'in_'){
        //Blog field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('in_', 'Blog ', $field_name))));
    } elseif(substr($field_name, 0, 3) == 'en_'){
        //Player field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('en_', 'Player ', $field_name))));
    } else {
        return false;
    }
}



function echo_time_minutes($sec_int)
{
    //Turns seconds into a nice format with minutes, like "1m 23s"
    $sec_int = intval($sec_int);
    $min = 0;
    $sec = fmod($sec_int, 60);
    if ($sec_int >= 60) {
        $min = floor($sec_int / 60);
    }
    return ($min ? $min . ' Min.' : '') . ($sec ? ($min ? ' ' : '') . $sec . ' Sec.' : '');
}

function echo_rank($integer){
    if($integer==1){
        return ' 🏅';
    } elseif($integer==2){
        return ' 🥈';
    } elseif($integer==3){
        return ' 🥉';
    } else {
        //return echo_ordinal_number($integer);
        return null;
    }
}

function echo_url_type_4537($url, $en_type_link_id)
{

    /*
     *
     * Displays Player Links that are a URL based on their
     * $en_type_link_id as listed under Player URL Links:
     * https://mench.com/play/4537
     *
     * */


    if ($en_type_link_id == 4256 /* Generic URL */) {

        return '<a href="' . $url . '"><span class="url_truncate">' . echo_url_clean($url) . '</span></a>';

    } elseif ($en_type_link_id == 4257 /* Embed Widget URL? */) {

        return echo_url_embed($url);

    } elseif ($en_type_link_id == 4260 /* Image URL */) {

        $current_mench = current_mench();
        if($current_mench['x_name']=='play'){
            return '<a href="' . $url . '"><img src="' . $url . '" class="content-image" /></a>';
        } else {
            return '<img src="' . $url . '" class="content-image" />';
        }

    } elseif ($en_type_link_id == 4259 /* Audio URL */) {

        return  '<audio controls><source src="' . $url . '" type="audio/mpeg"></audio>' ;

    } elseif ($en_type_link_id == 4258 /* Video URL */) {

        return  '<video width="100%" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="' . $url . '" type="video/mp4"></video>' ;

    } elseif ($en_type_link_id == 4261 /* File URL */) {

        return '<a href="' . $url . '" class="btn btn-blog" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';

    } else {

        //Unknown, return null:
        return false;

    }
}




function echo_url_embed($url, $full_message = null, $return_array = false)
{


    /*
     *
     * Detects and displays URLs from supported website with an embed widget
     *
     * NOTE: Changes to this function requires us to re-calculate all current
     *       values for ln_type_play_id as this could change the equation for those
     *       link types. Change with care...
     *
     * */

    $clean_url = null;
    $embed_html_code = null;
    $prefix_message = null;
    $CI =& get_instance();

    if (!$full_message) {
        $full_message = $url;
    }

    //See if $url has a valid embed video in it, and transform it if it does:
    $is_embed = (substr_count($url, 'youtube.com/embed/') == 1);

    if ((substr_count($url, 'youtube.com/watch') == 1) || substr_count($url, 'youtu.be/') == 1 || $is_embed) {

        $start_sec = 0;
        $end_sec = 0;
        $video_id = extract_youtube_id($url);

        if ($video_id) {

            if($is_embed){
                if(is_numeric(one_two_explode('start=','&',$url))){
                    $start_sec = one_two_explode('start=','&',$url);
                }
                if(is_numeric(one_two_explode('end=','&',$url))){
                    $end_sec = one_two_explode('end=','&',$url);
                }
            }

            //Set the Clean URL:
            $clean_url = 'https://www.youtube.com/watch?v=' . $video_id;

            //Inform User that this is a sliced video
            if ($start_sec || $end_sec) {
                $embed_html_code .= '<div class="read-topic"><i class="fad fa-play-circle"></i>&nbsp;' . (($start_sec && $end_sec) ? '<b title="FROM SECOND '.$start_sec.' to '.$end_sec.'">' . echo_time_minutes(($end_sec - $start_sec)) . '</b> VIDEO CLIP' : 'FROM <b>' . ($start_sec ? echo_time_minutes($start_sec) : 'START') . '</b> TO <b>' . ($end_sec ? echo_time_minutes($end_sec) : 'END') . '</b>') . ':</div>';
            }

            $embed_html_code .= '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/' . $video_id . '?theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_sec . ($end_sec ? '&end=' . $end_sec : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';

        }

    } elseif (substr_count($url, 'vimeo.com/') == 1 && is_numeric(one_two_explode('vimeo.com/','?',$url))) {

        //Seems to be Vimeo:
        $video_id = trim(one_two_explode('vimeo.com/', '?', $url));

        //This should be an integer!
        if (intval($video_id) == $video_id) {
            $clean_url = 'https://vimeo.com/' . $video_id;
            $embed_html_code = '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://player.vimeo.com/video/' . $video_id . '?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
        }

    } elseif (substr_count($url, 'wistia.com/medias/') == 1) {

        //Seems to be Wistia:
        $video_id = trim(one_two_explode('wistia.com/medias/', '?', $url));
        $clean_url = trim(one_two_explode('', '?', $url));
        $embed_html_code = '<script src="https://fast.wistia.com/embed/medias/' . $video_id . '.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding video-sorting" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_' . $video_id . ' seo=false videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>';

    }


    if ($return_array) {

        //Return all aspects of this parsed URL:
        return array(
            'status' => ($embed_html_code ? 1 : 0),
            'embed_code' => $embed_html_code,
            'clean_url' => $clean_url,
        );

    } else {
        //Just return the embed code:
        if ($embed_html_code) {
            return trim(str_replace($url, $embed_html_code, $full_message));
        } else {
            //Not matched with an embed rule:
            return false;
        }
    }
}

function echo_in_title($in_title, $push_message = false, $common_prefix = null){

    if(strlen($common_prefix) > 0){
        $in_title = trim(substr($in_title, strlen($common_prefix)));
    }

    if($push_message){

        return $in_title;

    } else {

        return htmlentities(trim($in_title));

    }

}


function echo_in_note($ln)
{

    /*
     *
     * A wrapper function that helps manage messages
     * by giving the message additional platform functions
     * such as editing and changing message type.
     *
     * */

    $CI =& get_instance();
    $session_en = superpower_assigned();
    $en_all_4485 = $CI->config->item('en_all_4485'); //Blog Notes


    //Link Statuses
    $en_all_6186 = $CI->config->item('en_all_6186');


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item itemblog is-msg notes_sortable msg_en_type_' . $ln['ln_type_play_id'] . '" id="ul-nav-' . $ln['ln_id'] . '" tr-id="' . $ln['ln_id'] . '">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="edit-off text_message" id="msgbody_' . $ln['ln_id'] . '">';
    $ui .= $CI->READ_model->dispatch_message($ln['ln_content'], $session_en, false, array(), $ln['ln_child_blog_id']);
    $ui .= '</div>';

    //Editing menu:
    $ui .= '<div class="note-edit edit-off '.superpower_active(10939).'"><span class="show-on-hover">';

        //Sort:
        if(in_array(4603, $en_all_4485[$ln['ln_type_play_id']]['m_parents'])){
            $ui .= '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort '.( in_array(4603, $en_all_4485[$ln['ln_type_play_id']]['m_parents']) ? 'blog_note_sorting' : '' ).'"></i></span>';
        }

        //Modify:
        $ui .= '<span title="Modify Message" data-toggle="tooltip" data-placement="left"><a href="javascript:in_note_modify_start(' . $ln['ln_id'] . ');"><i class="fas fa-pen-square"></i></a></span>';

    $ui .= '</span></div>';


    //Text editing:
    $ui .= '<textarea onkeyup="in_edit_note_count(' . $ln['ln_id'] . ')" name="ln_content" id="message_body_' . $ln['ln_id'] . '" class="edit-on hidden msg note-textarea algolia_search" placeholder="'.stripslashes($ln['ln_content']).'">' . $ln['ln_content'] . '</textarea>';


    //Editing menu:
    $ui .= '<ul class="msg-nav '.superpower_active(10939).'">';

    //Counter:
    $ui .= '<li class="edit-on hidden"><span id="blogNoteCount' . $ln['ln_id'] . '"><span id="charEditingNum' . $ln['ln_id'] . '">0</span>/' . config_var(11073) . '</span></li>';

    //Save Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-blog white-third" href="javascript:in_note_modify_save(' . $ln['ln_id'] . ',' . $ln['ln_type_play_id'] . ');" title="Save changes" data-toggle="tooltip" data-placement="top"><i class="fas fa-check"></i> Save</a></li>';

    //Cancel Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-blog white-third" href="javascript:in_note_modify_cancel(' . $ln['ln_id'] . ');" title="Cancel editing" data-toggle="tooltip" data-placement="top"><i class="fas fa-times"></i></a></li>';

    //Show drop down for message link status:
    $ui .= '<li class="pull-right edit-on hidden"><span class="white-wrapper" style="margin:-5px 0 0 0; display: block;">';
    $ui .= '<select id="message_status_' . $ln['ln_id'] . '"  class="form-control border" style="margin-bottom:0;" title="Change message status" data-toggle="tooltip" data-placement="top">';
    foreach($CI->config->item('en_all_12012') as $en_id => $m){
        $ui .= '<option value="' . $en_id . '" '.( $en_id==$ln['ln_status_play_id'] ? 'selected="selected"' : '' ).'>' . $m['m_name'] . '</option>';
    }
    $ui .= '</select>';
    $ui .= '</span></li>';

    //Update result:
    $ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors



    $ui .= '</ul>';

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function echo_en_icon($en_icon = null)
{
    //A simple function to display the Player Icon OR the default icon if not available:
    if (strlen($en_icon) > 0) {
        return $en_icon;
    } else {
        //Return default icon for players:
        $CI =& get_instance();
        $en_all_2738 = $CI->config->item('en_all_2738'); //MENCH
        return $en_all_2738[4536]['m_icon'];
    }
}

function echo_url($text)
{
    //Find and makes links within $text clickable
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1"><u>$1</u></a>', $text);
}


function echo_number($number, $micro = true, $push_message = false)
{

    //Displays number with a nice format

    //Let's see if we need to apply special formatting:
    $formatting = null;

    if ($number > 0 && $number < 1) {

        $original_format = $number; //Keep as is

        //Format Decimal number:
        if ($number < 0.000001) {
            $formatting = array(
                'multiplier' => 1000000000,
                'decimals' => 0,
                'micro_1' => 'n',
                'micro_0' => ' Nano',
            );
        } elseif ($number < 0.001) {
            $formatting = array(
                'multiplier' => 1000000,
                'decimals' => 0,
                'micro_1' => 'µ',
                'micro_0' => ' Micro',
            );
        } elseif ($number < 0.01) {
            $formatting = array(
                'multiplier' => 100000,
                'decimals' => 0,
                'micro_1' => 'm',
                'micro_0' => ' Milli',
            );
        } else {
            //Must be cents
            $formatting = array(
                'multiplier' => 100,
                'decimals' => 0,
                'micro_1' => 'c',
                'micro_0' => ' Cent',
            );
        }

    } elseif ($number >= 950) {

        $original_format = number_format($number); //Add commas

        if ($number >= 950000000) {
            $formatting = array(
                'multiplier' => (1 / 1000000000),
                'decimals' => 1,
                'micro_1' => 'B',
                'micro_0' => ' Billion',
            );
        } elseif ($number >= 9500000) {
            $formatting = array(
                'multiplier' => (1 / 1000000),
                'decimals' => 0,
                'micro_1' => 'M',
                'micro_0' => ' Million',
            );
        } elseif ($number >= 950000) {
            $formatting = array(
                'multiplier' => (1 / 1000000),
                'decimals' => 1,
                'micro_1' => 'M',
                'micro_0' => ' Million',
            );
        } elseif ($number >= 9500) {
            $formatting = array(
                'multiplier' => (1 / 1000),
                'decimals' => 0,
                'micro_1' => 'K',
                'micro_0' => ' Thousand',
            );
        } elseif ($number >= 950) {
            $formatting = array(
                'multiplier' => (1 / 1000),
                'decimals' => 1,
                'micro_1' => 'K',
                'micro_0' => ' Thousand',
            );
        }

    }


    if ($formatting) {

        //See what to show:
        $rounded = round(($number * $formatting['multiplier']), $formatting['decimals']);
        $append = $formatting['micro_' . (int)$micro] . (!$micro ? echo__s($rounded) : '');

        if ($push_message) {
            //Messaging format, show using plain text:
            return $rounded . $append . ' (' . $original_format . ')';
        } else {
            //HTML, so we can show Tooltip:
            return $rounded . $append;
        }

    } else {

        return intval($number);

    }
}


function echo_ln_urls($ln_content, $ln_type_play_id){

    $CI =& get_instance();
    if (in_array($ln_type_play_id, $CI->config->item('en_ids_4537'))) {

        //Player URL Links
        return echo_url_type_4537($ln_content, $ln_type_play_id);

    } elseif($ln_type_play_id==10669) {

        return '<i class="'.$ln_content.'"></i>';

    } elseif(strlen($ln_content) > 0) {

        return echo_url(htmlentities($ln_content));

    } else {

        return null;

    }
}

function echo_ln_connections($ln){

    $CI =& get_instance();
    $ln_connections_ui = '';
    $en_all_6232 = $CI->config->item('en_all_6232'); //PLATFORM VARIABLES


    foreach ($CI->config->item('en_all_10692') as $en_id => $m) {

        if(!intval($ln[$en_all_6232[$en_id]['m_desc']])){
            continue;
        }

        $ln_connections_ui .= '<div class="read-hisotry-child">';

        if(in_array(6160 , $m['m_parents'])){
            //PLAY
            $ens = $CI->PLAY_model->en_fetch(array('en_id' => $ln[$en_all_6232[$en_id]['m_desc']]));
            if(count($ens) > 0){
                $ln_connections_ui .= echo_en($ens[0], false);
            }
        } elseif(in_array(6202 , $m['m_parents'])){
            //BLOG
            $ins = $CI->BLOG_model->in_fetch(array('in_id' => $ln[$en_all_6232[$en_id]['m_desc']]));
            if(count($ins) > 0){
                $ln_connections_ui .= echo_in_read($ins[0]);
            }
        } elseif(in_array(4367 , $m['m_parents'])){
            //READ
            $lns = $CI->READ_model->ln_fetch(array('ln_id' => $ln[$en_all_6232[$en_id]['m_desc']]));
            if(count($lns) > 0){
                $ln_connections_ui .= echo_ln($lns[0], true);
            }
        }

        $ln_connections_ui .= '</div>';
    }
    return $ln_connections_ui;
}

function echo_ln($ln, $is_inner = false)
{

    $CI =& get_instance();
    $en_all_4593 = $CI->config->item('en_all_4593'); //Link Type
    $en_all_4527 = $CI->config->item('en_all_4527');
    $en_all_4341 = $CI->config->item('en_all_4341'); //Link Table
    $en_all_2738 = $CI->config->item('en_all_2738');



    if(!isset($en_all_4593[$ln['ln_type_play_id']])){
        //We've probably have not yet updated php cache, set error:
        $en_all_4593[$ln['ln_type_play_id']] = array(
            'm_icon' => '<i class="fad fa-exclamation-triangle"></i>',
            'm_name' => 'Link Type Not Synced in PHP Cache',
            'm_desc' => '',
            'm_parents' => array(),
        );
    }



    $hide_sensitive_details = ( in_array($ln['ln_type_play_id'] , $CI->config->item('en_ids_4755')) && !superpower_active(10985, true) );



    //Display the item
    $ui = '<div style="margin-bottom:20px;">';
    $ui .= '<div class="list-group-item no-left-padding">';


    //What type of main content do we have, if any?
    $en_all_6186 = $CI->config->item('en_all_6186'); //Link Statuses


    //READ ID Row of data:
    $ui .= '<div class="read-micro-data">';

    $ui .= '<span data-toggle="tooltip" data-placement="top" title="READ ID" class="montserrat"><i class="fas fa-atlas"></i> '.$ln['ln_id'].'</span>';

    $ui .= ' &nbsp;&nbsp;<span data-toggle="tooltip" data-placement="top" title="Link is '.$en_all_6186[$ln['ln_status_play_id']]['m_desc'].'" class="montserrat">'.$en_all_6186[$ln['ln_status_play_id']]['m_icon'].' '.$en_all_6186[$ln['ln_status_play_id']]['m_name'].'</span>';

    $ui .= ' &nbsp;&nbsp;<span data-toggle="tooltip" class="montserrat" data-placement="top" title="Link Creation Timestamp: ' . $ln['ln_timestamp'] . ' PST">'.$en_all_4341[4362]['m_icon']. ' ' . echo_time_difference(strtotime($ln['ln_timestamp'])) . ' ago</span>';

    $ui .= '</div>';



    //Trainer and Link Type row:
    $ui .= '<div style="padding: 10px 0;">';

    if($hide_sensitive_details){

        //Hide Trainer idplayer:
        $full_name = 'Hidden User';
        $ui .= '<span class="icon-main"><i class="fal fa-eye-slash"></i></span>';
        $ui .= '<b data-toggle="tooltip" data-placement="top" title="Details are kept private" class="montserrat">&nbsp;Private Player</b>';

    } elseif($ln['ln_owner_play_id'] > 0){

        //Show Player:
        $trainer_ens = $CI->PLAY_model->en_fetch(array(
            'en_id' => $ln['ln_owner_play_id'],
        ));
        $full_name = one_two_explode('',' ', $trainer_ens[0]['en_name']);

        $ui .= '<span class="icon-main">'.echo_en_icon($trainer_ens[0]['en_icon']).'</span> ';
        $ui .= '<a href="/play/'.$trainer_ens[0]['en_id'].'" data-toggle="tooltip" data-placement="top" title="Link Creator"><b class="montserrat">' . $full_name . '</b></a>';

    }

    //Link Type:
    $ui .= '&nbsp;'.( strlen($en_all_4593[$ln['ln_type_play_id']]['m_icon']) > 0 ? '&nbsp;'.$en_all_4593[$ln['ln_type_play_id']]['m_icon'].'&nbsp;' : '' ).'<a href="/play/'.$ln['ln_type_play_id'].'" data-toggle="tooltip" data-placement="top" title="Link Type"><b style="padding-left:5px;" class="montserrat">'. $en_all_4593[$ln['ln_type_play_id']]['m_name'] . '</b></a>';

    $ui .= '</div>';




    //Do we have a content to show?
    if(!$hide_sensitive_details && strlen($ln['ln_content']) > 0){
        $ui .= '<div class="read-history-msg">';
        $ui .= $CI->READ_model->dispatch_message($ln['ln_content']);
        $ui .= '</div>';
    }


    //Link Connections
    $link_connections_clean_name = ''; //All link connections including child links
    $link_connections_count = 0; //Core link connections excluding child links (2x Blogs, 2x Players & 1x Parent Link)
    $auto_load_max_connections = 3; //If a link has this many of LESS connections, it would auto load them
    if(!$is_inner){

        $en_all_6232 = $CI->config->item('en_all_6232'); //PLATFORM VARIABLES

        //First count to see if this link has any connections:
        foreach ($CI->config->item('en_all_10692') as $en_id => $m) {

            if (!intval($ln[$en_all_6232[$en_id]['m_desc']])) {
                continue;
            }

            if($link_connections_count > 0){
                $link_connections_clean_name .= ', ';
            }
            $link_connections_clean_name .= $m['m_name'];
            $link_connections_count++;
        }

        //Count child links:
        $child_links = $CI->READ_model->ln_fetch(array(
            'ln_parent_read_id' => $ln['ln_id'],
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_child_links');

        $load_main = ( $link_connections_count <= $auto_load_max_connections ? 1 : 0 ); //Decide if we should auto-load the main connections for this link

        if($link_connections_count>0 && $link_connections_count <= $auto_load_max_connections){
            //Since it would be auto loaded, remove from UI link:
            $link_connections_clean_name = ''; //All link connections including child links
        }

        if($child_links[0]['total_child_links'] > 0){
            if(strlen($link_connections_clean_name) > 0){
                $link_connections_clean_name .= ' & ';
            }
            $link_connections_clean_name .= $child_links[0]['total_child_links'].' Links';
        }
    }


    //Link coins
    if(in_array($ln['ln_type_play_id'], $CI->config->item('en_ids_12141'))){
        $direction = filter_cache_group($ln['ln_type_play_id'], 2738);
        $ui .= '<span class="read-micro-data"><span style="min-width:30px; display: inline-block;" class="montserrat '.extract_icon_color($direction['m_icon']).'">'.$direction['m_icon'].'</i> COIN</span></span> &nbsp;';
    }



    if($ln['ln_order'] > 0){
        $ui .= '<span class="read-micro-data"><span data-toggle="tooltip" data-placement="top" title="Link ordered '.echo_ordinal_number($ln['ln_order']).'" style="min-width:30px; display: inline-block;">'.$en_all_4341[4370]['m_icon']. ' '.echo_ordinal_number($ln['ln_order']).'</span></span> &nbsp;';
    }

    //Is this a trainer? Show them metadata status:
    if(!$hide_sensitive_details && strlen($ln['ln_metadata']) > 0){
        $ui .= '<span class="read-micro-data">'.$en_all_4341[6103]['m_icon']. ' <a href="/read/view_json/' . $ln['ln_id'] . '" data-toggle="tooltip" data-placement="top" title="View link metadata (in new window)" style="min-width:26px; display: inline-block;">Metadata</a></span> &nbsp;';
    }

    if(!$hide_sensitive_details && $ln['ln_external_id'] > 0){
        $ui .= '<span class="read-micro-data" data-toggle="tooltip" data-placement="top" title="Link External ID">'.$en_all_4527[6103]['m_icon']. ' '.$ln['ln_external_id'].'</span> &nbsp;';
    }

    //Give option to load if it has connections:
    if(!$is_inner && (strlen($link_connections_clean_name) > 0 || $load_main)){

        if(!$load_main || $child_links[0]['total_child_links'] > 0){
            $ui .= '<span class="link_connections_link_'.$ln['ln_id'].' read-micro-data"><a href="#linkconnection-'.$ln['ln_id'].'" onclick="load_link_connections('.$ln['ln_id'].','.$load_main.')"  data-toggle="tooltip" data-placement="top" title="'.$en_all_4527[10692]['m_name'].'">'.$en_all_4527[10692]['m_icon'].' '.$link_connections_clean_name.'</a></span>';
        }

        $ui .= '</div>'; //Close main link box

        if($load_main){
            //Load main connections:
            $ui .= echo_ln_connections($ln);
        }

        $ui .= '<div class="link_connections_content_'.$ln['ln_id'].'"></div>';

    } else {

        $ui .= '</div>'; //Close main link box

    }

    $ui .= '</div>'; //Close it all

    return $ui;
}


function echo_actionplan_step_child($en_id, $in, $is_unlocked_step = false, $common_prefix = null){

    $CI =& get_instance();

    //Open list:
    $ui = '<a href="/'.$in['in_id']. '" class="list-group-item itemread">';

    $ui .= echo_in_title($in['in_title'], false, $common_prefix);

    if($is_unlocked_step){
        $en_all_4229 = $CI->config->item('en_all_4229'); //Link Metadata
        $ui .= '<span class="badge badge-primary" style="margin:-7px 0 -7px 5px;" data-toggle="tooltip" data-placement="right" title="'.$en_all_4229[6140]['m_name'].'">'.$en_all_4229[6140]['m_icon'].'</span>';
    }

    $ui .= '</a>';

    return $ui;
}

function echo_actionplan_step_parent($in)
{

    $CI =& get_instance();

    $ui = '<a href="/' . $in['in_id'] . '" class="list-group-item itemread">';

    $ui .= '<span class="pull-left">';
    $ui .= '<span class="badge badge-primary fr-bgd"><i class="fad fa-step-backward"></i></span>';
    $ui .= '</span>';

    $ui .= ' <span>' . echo_in_title($in['in_title']).'</span>';

    $ui .= '</a>';

    return $ui;
}


function echo_random_message($message_key, $return_all = false){

    /*
     *
     * To make Mench personal assistant feel more natural,
     * this function sends varying messages to communicate
     * specific things about Mench or about the user's
     * progress towards their 🔴 READING LIST.
     *
     * */

    $rotation_index = array(
        'next_blog_is' => array(
            'Next: ',
            'Next blog is: ',
            'The next blog is: ',
            'Ok moving on to the next blog: ',
            'Moving to the next blog: ',
        ),
        'one_way_only' => array(
            'I am not designed to respond to custom messages. I can understand you only when you choose one of the options that I recommend to you.',
            'I cannot understand if you send me an out-of-context message. I would only understand if you choose one of the options that I recommend to you.',
            'I cannot respond to your custom messages and can only understand if you select one of the options that I recommend to you.',
        ),
        'loading_notify' => array(
            "Are you having a good day today?",
            "Be gentle with yourself today.",
            "Congratulate yourself for the great job you're doing",
            "Crunching the latest data, just for you. Hang tight...",
            "Have a glass of water nearby? Time for a sip!",
            "Offer hugs. Someone probably needs them.",
            "You are unique!",
            "Get a drink of water. Stay hydrated!",
            "Have you danced today?",
            "Have you listened to your favourite song recently? 🎵",
            "Have you stretched recently?",
            "Have you recently told someone you're proud of them?",
            "Help is out there. Don't be afraid to ask.",
            "Hey! Life is tough, but so are you! 💪",
            "Hey, jump up for a sec and stretch, yeah? 👐",
            "I know it's cheesey but I hope you have a grate day!",
            "Idplayer is fluid! People can change - be accepting.",
            "Is there a window you can look through? The world is beautiful. 🌆",
            "Is your seat comfortable? Can you adjust your chair properly?",
            "It can be hard to get started, can't it? That's okay, you got this.",
            "It's so great to have you here today",
            "Keep growing, keep learning, keep moving forward!",
            "Learning new things is important - open your eyes to the world around you!",
            "Making things awesome...",
            "Novel, new, silly, & unusual activities can help lift your mood.",
            "Play for a few minutes. Doodle, learn solitaire, fold a paper airplane, do something fun.",
            "Don't take yourself for granted. You're important.",
            "Rest your eyes for a moment. Look at something in the distance and count to five! 🌳",
            "Self care is important, look after and love yourself, you're amazing!",
            "Set aside time for a hobby. Gardening, drone building, knitting, do something for the pure pleasure of it.",
            "So often our power lies not in ourselves, but in how we help others find their own strength",
            "Sometimes doing something nice for somebody else is the best way to feel good about yourself! 👭",
            "Stop. Breathe. Be here now.",
            "Stop. Take three slow deep breaths.",
            "Take 5 minutes to straighten the space around you. Set a timer.",
            "Take a break before you need it. It will make it easier to prevent burnout.",
            "Take a moment to send a message to someone you love 😻",
            "Take care of yourself. We need you.",
            "Technology is a tool. Use it wisely.",
            "The impact you leave on the universe can never be erased.",
            "There are no impostors here",
            "There's someone who is so so grateful that you exist together.",
            "Today is a great day to let a friend know how much you appreciate them.",
            "Water is good for you year round. If you're thirsty, you're dehydrated.",
            "We all have superpowers. You included. I hope you are using yours to make your life a joyful one.",
            "When's the last time you treated yourself?",
            "With the dawning of a new day comes a clean slate and lots of opportunity.",
            "You are fantastic",
            "You are loved. <3",
            "You are so very important 💛💛💕",
            "You can do this!",
            "You cannot compare your successes to the apparent achievements of others. 🌄",
            "You deserve to be safe and to have nice things happen to you.",
            "You have the power to change the world.",
            "You're allowed to start small. 🐞",
            "have you hugged anyone lately?",
            "it's time to check your thirst level, human.",
            "💗: don't forget to take a little bit of time to say hi to a friend",
            "🌸: remember to let your eyes rest, maybe by looking at a plant...",
            "🙌: take a second to adjust your posture",
            "😎🌈💕"
        ),
        'saving_notify' => array(
            "Learning everyday 😎",
            "Growing with you 🌸",
            "Getting smarter ^~^",
        ),
        'command_me' => array(
            'You can search for new blogs by sending me a message starting with "Search for", for example: "Search for assess my back-end skills" or "Search for recruit top talent"',
        ),
        'goto_next' => array(
            'Say next to continue',
        ),
        'read_recommendation' => array(
            'What would you like to read next? Start a sentence with "Search for ..." or:  /link:BROWSE READS:https://mench.com/read',
        ),

    );

    if(!array_key_exists($message_key, $rotation_index)){

        //Oooopsi, this should never happen:
        $CI =& get_instance();
        $CI->READ_model->ln_create(array(
            'ln_content' => 'echo_random_message() failed to locate message type ['.$message_key.']',
            'ln_type_play_id' => 4246, //Platform Bug Reports
        ));
        return false;

    } else {

        if($return_all){
            //Return all options:
            return $rotation_index[$message_key];
        } else {
            //Return a random message:
            return $rotation_index[$message_key][rand(0, (count($rotation_index[$message_key]) - 1))];
        }
    }

}

function echo_url_clean($url)
{
    //Returns the watered-down version of the URL for a cleaner UI:
    return rtrim(str_replace('http://', '', str_replace('https://', '', str_replace('www.', '', $url))), '/');
}


function echo_time_hours($seconds, $micro = false)
{

    /*
     * A function that will return a fancy string representing hours & minutes
     *
     * */

    if ($seconds < 1) {
        //Under 30 seconds would not round up to even 1 minute, so don't show:
        return 0;
    } elseif ($seconds < 60) {
        return 1 . ($micro ? ' Min.' : ' Minutes');
    } elseif ($seconds < 3600) {
        return round($seconds / 60) . ($micro ? ' Min.' : ' Minutes');
    } else {
        //Roundup the hours:
        $hours = round($seconds / 3600);
        return $hours . ' Hour' . echo__s($hours);
    }
}

function echo_tree_html_body($section_en_id, $pitch_title, $pitch_body, $autoexpand = false){

    //The body of the tree expansion HTML panel:
    return '<div class="panel-group" id="open' . $section_en_id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $section_en_id . '">
                <h4 class="panel-title">
                    <a role="button" class="collapsed js-ln-create-overview-link" section-en-id="'.$section_en_id.'" data-toggle="collapse" data-parent="#open' . $section_en_id . '" href="#collapse' . $section_en_id . '" aria-expanded="' . ($autoexpand ? 'true' : 'false') . '" aria-controls="collapse' . $section_en_id . '">' . $pitch_title . '
                    
                    <span class="pull-right" style="padding: 1px 11px 0 0;"><i class="fas fa-angle-down"></i></span>
                    
                    </a>
                </h4>
            </div>
            <div id="collapse' . $section_en_id . '" class="panel-collapse collapse ' . ($autoexpand ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $section_en_id . '">
                <div class="panel-body overview-pitch"><div style="padding:10px 5px !important;">' . $pitch_body . '</div></div>
            </div>
        </div></div>';
}



function echo_tree_users($in, $push_message = false, $autoexpand = false){

    return false;

    //TODO Consider enabling later?
    //return null; //Disable for now

    /*
     *
     * a BLOG function to display current users for this blog
     * and the percentage of them that have completed it...
     *
     * */

    //Count total users:
    $CI =& get_instance();
    $min_user_show = 101; //Needs this much or more users to display

    //Count users who have completed this blog:
    $enrolled_users_count = $CI->READ_model->ln_fetch(array(
        'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null, //READ COIN
        'ln_parent_blog_id' => $in['in_id'],
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

    if($enrolled_users_count[0]['totals'] < $min_user_show){
        //No one has added this blog to their 🔴 READING LIST yet:
        return false;
    }

    //Count users who have completed the common base:
    $in_metadata = unserialize($in['in_metadata']);
    $array_flatten = array_flatten($in_metadata['in__metadata_common_steps']);
    $completed_users_count = $CI->READ_model->ln_fetch(array(
        'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null, //READ COIN
        'ln_parent_blog_id' => end($array_flatten),
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    if($completed_users_count[0]['totals'] > $enrolled_users_count[0]['totals']){
        $completed_users_count[0]['totals'] = $enrolled_users_count[0]['totals'];
    }
    $completion_percentage_raw = round($completed_users_count[0]['totals'] / $enrolled_users_count[0]['totals'] * 100);
    $completion_percentage_fancy = ( $completion_percentage_raw == 0 ? 'none' : ( $completion_percentage_raw==100 ? 'all' : $completion_percentage_raw.'%' ) );

    //As messenger default format and HTML extra notes:
    $pitch_body  = $completion_percentage_fancy .' of all '.echo_number($enrolled_users_count[0]['totals']).' users completed this blog.';

    if ($push_message) {
        return '👤 ' . $pitch_body. "\n\n";
    } else {
        //HTML format
        $pitch_title = '<span class="icon-block"><i class="fas fa-users"></i></span>&nbsp;'. echo_number($enrolled_users_count[0]['totals']) .' users engaged';
        return echo_tree_html_body(7615, $pitch_title, $pitch_body, $autoexpand);
    }
}

function echo_tree_experts($in, $push_message = false, $autoexpand = false)
{

    /*
     *
     * a BLOG function to display experts sources for
     * the entire Blog tree stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if ((!isset($metadata['in__metadata_experts']) || count($metadata['in__metadata_experts']) < 1) && (!isset($metadata['in__metadata_sources']) || count($metadata['in__metadata_sources']) < 1)) {
        return false;
    }


    //Let's count to see how many content pieces we have references for this Blog tree:
    $source_info = '';
    $source_count = 0;

    if(isset($metadata['in__metadata_sources'])){
        foreach ($metadata['in__metadata_sources'] as $type_en_id => $referenced_ens) {
            $source_count += count($referenced_ens);
        }
    }
    if ($source_count > 0) {

        //Set some variables and settings to get started:
        $type_all_count = count($metadata['in__metadata_sources']);
        $CI =& get_instance();
        $en_all_3000 = $CI->config->item('en_all_3000');
        $visible_ppl = 3; //How many people to show before clicking on "see more"
        $type_count = 0;
        foreach ($metadata['in__metadata_sources'] as $type_id => $referenced_ens) {

            if ($type_count > 0) {
                if (($type_count + 1) >= $type_all_count) {
                    $source_info .= ' &';
                } else {
                    $source_info .= ',';
                }
            }

            //Show category:
            $cat_contribution = count($referenced_ens) . ' ' . $en_all_3000[$type_id]['m_name'];
            if ($push_message) {

                $source_info .= ' ' . $cat_contribution;

            } else {

                $source_info .= ' <span class="show_type_' . $type_id . '"><a href="javascript:void(0);" class="js-ln-create-expert-sources" source-type-en-id="'.$type_id.'" onclick="$(\'.show_type_' . $type_id . '\').toggle()" style="text-decoration:underline; display:inline-block;">' . $cat_contribution . '</a></span><span class="show_type_' . $type_id . '" style="display:none;">';

                //We only show details on our website's HTML landing pages:
                $count = 0;
                foreach ($referenced_ens as $en) {

                    if ($count > 0) {
                        if (($count + 1) >= count($referenced_ens)) {
                            $source_info .= ' &';
                        } else {
                            $source_info .= ',';
                        }
                    }

                    $source_info .= ' ';

                    //Show link to platform:
                    //$source_info .= '<a href="/play/' . $en['en_id'] . '">';
                    $source_info .= '<span>';
                    $source_info .= $en['en_name'];
                    $source_info .= '</span>';
                    //$source_info .= '</a>';

                    $count++;
                }
                $source_info .= '</span>';

            }
            $type_count++;
        }
    }


    //Define some variables to get stared:
    $expert_count = ( isset($metadata['in__metadata_experts']) ? count($metadata['in__metadata_experts']) : 0 );
    $visible_html = 4; //Landing page, beyond this is hidden and visible with a click
    $visible_bot = 10; //Plain text style, but beyond this is cut out!
    $expert_info = '';

    if(isset($metadata['in__metadata_experts'])){
        foreach ($metadata['in__metadata_experts'] as $count => $en) {

            $is_last_fb_item = ($push_message && $count >= $visible_bot);

            if ($count > 0) {
                if (($count + 1) >= $expert_count || $is_last_fb_item) {
                    $expert_info .= ' &';
                    if ($is_last_fb_item) {
                        $expert_info .= ' ' . ($expert_count - $visible_bot) . ' more!';
                        break;
                    }
                } else {
                    $expert_info .= ',';
                }
            }

            $expert_info .= ' ';

            if ($push_message) {

                //Just the name:
                $expert_info .= $en['en_name'];

            } else {

                //HTML Format:
                //$expert_info .= '<a href="/play/' . $en['en_id'] . '">';
                $expert_info .= '<span>';
                $expert_info .= $en['en_name'];
                $expert_info .= '</span>';
                //$expert_info .= '</a>';

                if (($count + 1) == $visible_html && ($expert_count - $visible_html) > 0) {
                    $expert_info .= '<span class="show_more_' . $in['in_id'] . '"> & <a href="javascript:void(0);" class="js-ln-create-expert-full-list" onclick="$(\'.show_more_' . $in['in_id'] . '\').toggle()" style="text-decoration:underline;">' . ($expert_count - $visible_html) . ' more</a>.</span><span class="show_more_' . $in['in_id'] . '" style="display:none;">';
                }
            }
        }

        if (!$push_message && ($count + 1) >= $visible_html) {
            //Close the span:
            $expert_info .= '.</span>';
        } elseif ($push_message && !$is_last_fb_item) {
            //Close the span:
            $expert_info .= '.';
        }
    }





    $pitch_title = '<span class="icon-block"><i class="fas fa-shield-check"></i></span>&nbsp;';
    $pitch_body = 'References ';
    if($source_count > 0){
        $pitch_title .= $source_count . ' source' . echo__s($source_count);
        $pitch_body .= trim($source_info);
    }
    if($expert_count > 0){
        if($source_count > 0){
            $pitch_title .= ' by ';
            $pitch_body .= ' by ';
        }
        $pitch_title .= $expert_count . ' expert'. echo__s($expert_count);
        $pitch_body .= $expert_count . ' industry expert'. echo__s($expert_count) . ($expert_count == 1 ? ':' : ' including') . $expert_info;
    }

    if ($push_message) {
        return '⭐ ' . $pitch_body. "\n\n";
    } else {
        //HTML format
        return echo_tree_html_body(7614, $pitch_title, $pitch_body, $autoexpand);
    }
}

function echo_step_range($in, $educational_mode = false){

    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__metadata_min_steps']) || !isset($metadata['in__metadata_max_steps']) || $metadata['in__metadata_max_steps'] < 1) {
        return ( $educational_mode ? 'Unknown number of steps' : false );
    }

    //Is this a range or a single read value?
    if($metadata['in__metadata_min_steps'] != $metadata['in__metadata_max_steps']){

        //It's a range:
        return 'Between '.$metadata['in__metadata_min_steps'].' - '.$metadata['in__metadata_max_steps'].' Steps' . ( $educational_mode ? ' (depending on your answers to my questions)' : '' );

    } else {

        //A single read value, nothing to educate about here:
        return $metadata['in__metadata_max_steps']. ' Step'.echo__s($metadata['in__metadata_max_steps']);

    }
}

function echo_tree_actionplan($in, $autoexpand){


    $CI =& get_instance();

    //Fetch actual children:
    $in__children = $CI->READ_model->ln_fetch(array(
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_play_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        'ln_type_play_id' => 4228, //Blog Link Regular Read
        'ln_parent_blog_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

    if(count($in__children) < 1){
        return null;
    }


    $common_prefix = common_prefix($in__children, 'in_title', $in);
    $return_html = '';
    $return_html .= '<div class="list-group maxout public_ap">';

    foreach ($in__children as $in_level2_counter => $in_level2) {


        //Level 3 Blogs:
        $in_level2_children = $CI->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'in_status_play_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
            'ln_type_play_id' => 4228, //Blog Link Regular Read
            'ln_parent_blog_id' => $in_level2['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

        //Fetch messages:
        $in_level2_messages = $CI->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 4231, //Blog Note Messages
            'ln_child_blog_id' => $in_level2['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //Determine Blog type/settings:
        $has_level2_content = (count($in_level2_children)>0 || count($in_level2_messages)>0);



        //Level 2 title:
        $return_html .= '<div class="panel-group" id="open' . $in_level2_counter . '" role="tablist" aria-multiselectable="true">';
        $return_html .= '<div class="panel panel-primary">';
        $return_html .= '<div class="panel-heading" role="tab" id="heading' . $in_level2_counter . '">';


        $return_html .= '<h5 class="panel-title montserrat">';

        if($has_level2_content){
            $return_html .= '<a class="js-ln-create-steps-review" blog-id="'.$in_level2['in_id'].'" role="button" data-toggle="collapse" data-parent="#open' . $in_level2_counter . '" href="#collapse' . $in_level2_counter . '" aria-expanded="' . ($autoexpand ? 'true' : 'false') . '" aria-controls="collapse' . $in_level2_counter . '">';
            $return_html .= '<span class="icon-block"><i class="fas fa-plus-circle"></i></span>';
        } else {
            $return_html .= '<span class="empty-block">';
            $return_html .= '<span class="icon-block"><i class="fal fa-check-circle"></i></span>';
        }


        $return_html .= '<span id="title-' . $in_level2['in_id'] . '">' . echo_in_title($in_level2['in_title'], false, $common_prefix) . '</span>';


        if($has_level2_content){
            $return_html .= '</a>';
        } else {
            $return_html .= '</span>';
        }

        $return_html .= '</h5>';
        $return_html .= '</div>';



        //Messages:
        if($has_level2_content){

            //Level 2 body:
            $return_html .= '<div id="collapse' . $in_level2_counter . '" class="panel-collapse collapse ' . ($autoexpand ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '">';
            $return_html .= '<div class="panel-body">';


            foreach ($in_level2_messages as $ln) {
                $return_html .= $CI->READ_model->dispatch_message($ln['ln_content']);
            }

            if (count($in_level2_children) > 0) {

                //See if they have a common base:
                $common_prefix_granchild = common_prefix($in_level2_children, 'in_title', $in_level2);

                //List level 3:
                $return_html .= '<ul class="action-plan-sub-list">';
                foreach ($in_level2_children as $in_level3_counter => $in_level3) {

                    //Fetch messages:
                    $in_level3_messages = $CI->READ_model->ln_fetch(array(
                        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                        'ln_type_play_id' => 4231, //Blog Note Messages
                        'ln_child_blog_id' => $in_level3['in_id'],
                    ), array(), 0, 0, array('ln_order' => 'ASC'));


                    $return_html .= '<li>';


                    if(count($in_level3_messages) > 0){
                        $return_html .= '<a role="button" data-toggle="collapse" class="second-level-link js-ln-create-steps-review" blog-id="'.$in_level3['in_id'].'" data-parent="#open' . $in_level2_counter . '-'.$in_level3_counter.'" href="#collapse' . $in_level2_counter . '-'.$in_level3_counter.'" aria-expanded="' . ($autoexpand ? 'true' : 'false') . '" aria-controls="collapse' . $in_level2_counter . '">';
                        $return_html .= '<span class="icon-block"><i class="fas fa-plus-circle"></i></span>';
                    } else {
                        $return_html .= '<span class="icon-block"><i class="fal fa-check-circle"></i></span>';
                    }

                    $return_html .= echo_in_title($in_level3['in_title'], false, $common_prefix_granchild);

                    if(count($in_level3_messages) > 0){
                        $return_html .= '</a>';
                    }

                    $return_html .= '</li>';


                    if(count($in_level3_messages) > 0){
                        //Level 2 body:
                        $return_html .= '<div id="collapse' . $in_level2_counter . '-'.$in_level3_counter.'" class="panel-collapse collapse ' . ($autoexpand ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '-'.$in_level3_counter.'">';
                        $return_html .= '<div class="panel-body second-level-body">';
                        foreach ($in_level3_messages as $ln) {
                            $return_html .= $CI->READ_model->dispatch_message($ln['ln_content']);
                        }
                        $return_html .= '</div></div>';
                    }
                }
                $return_html .= '</ul>';

            }

            $return_html .= '</div></div>';

        }

        $return_html .= '</div></div>';

    }
    $return_html .= '</div>';

    return $return_html;
}



function echo_time_range($in, $micro = false, $hide_zero = false)
{

    //Make sure we have metadata passed on via $in as sometimes it might miss it (Like when passed on via Algolia results...)
    if (!isset($in['in_metadata'])) {
        //We don't have it, so fetch it:
        $CI =& get_instance();
        $ins = $CI->BLOG_model->in_fetch(array(
            'in_id' => $in['in_id'], //We should always have Blog ID
        ));
        if (count($ins) > 0) {
            $in = $ins[0];
        } else {
            return false;
        }
    }

    //By now we have the metadata, extract it:
    $metadata = unserialize($in['in_metadata']);

    if (!isset($metadata['in__metadata_max_seconds']) || !isset($metadata['in__metadata_min_seconds'])) {
        return false;
    } elseif($hide_zero && $metadata['in__metadata_max_seconds'] < 1){
        return false;
    }

    //Construct the UI:
    if ($metadata['in__metadata_max_seconds'] == $metadata['in__metadata_min_seconds']) {

        //Exactly the same, show a single value:
        return echo_time_hours($metadata['in__metadata_max_seconds'], $micro);

    } elseif ($metadata['in__metadata_min_seconds'] < 3600) {

        if ($metadata['in__metadata_min_seconds'] < 7200 && $metadata['in__metadata_max_seconds'] < 10800) {
            $is_minutes = true;
            $hours_decimal = 0;
        } elseif ($metadata['in__metadata_min_seconds'] < 36000) {
            $is_minutes = false;
            $hours_decimal = 1;
        } else {
            //Number too large to matter, just treat as one:
            return echo_time_hours($metadata['in__metadata_max_seconds'], $micro);
        }

    } else {
        $is_minutes = false;
        $hours_decimal = 0;
    }

    $min_minutes = round($metadata['in__metadata_min_seconds'] / 60);
    $min_hours = round(($metadata['in__metadata_min_seconds'] / 3600), $hours_decimal);
    $max_minutes = round($metadata['in__metadata_max_seconds'] / 60);
    $max_hours = round(($metadata['in__metadata_max_seconds'] / 3600), $hours_decimal);

    //Generate hours range:
    $the_min = ($is_minutes ? $min_minutes : $min_hours );
    $the_max = ($is_minutes ? $max_minutes : $max_hours );
    $ui_time = $the_min;
    if($the_min != $the_max){
        $ui_time .= ( $micro ? '-' : ' - ' );
        $ui_time .= $the_max;
    }
    $ui_time .= strtoupper($is_minutes ? ($micro ? ' Min.' : ' Minute'.echo__s($max_minutes)) : ' Hour'.echo__s($max_hours));

    //Generate UI to return:
    return $ui_time;
}


function echo_time_difference($t, $second_time = null)
{
    if (!$second_time) {
        $second_time = time(); //Now
    } else {
        $second_time = strtotime(substr($second_time, 0, 19));
    }

    $time = $second_time - (is_int($t) ? $t : strtotime(substr($t, 0, 19))); // to get the time since that moment
    $is_future = ($time < 0);
    $time = abs($time);
    $time_units = array(
        31536000 => 'Year',
        2592000 => 'Month',
        604800 => 'Week',
        86400 => 'Day',
        3600 => 'Hour',
        60 => 'Minute',
        1 => 'Second'
    );

    foreach ($time_units as $unit => $period) {
        if ($time < $unit && $unit > 1) continue;
        if ($unit >= 2592000 && fmod(($time / $unit), 1) >= 0.33 && fmod(($time / $unit), 1) <= .67) {
            $numberOfUnits = number_format(($time / $unit), 1);
        } else {
            $numberOfUnits = number_format(($time / $unit), 0);
        }

        if ($numberOfUnits < 1 && $unit == 1) {
            $numberOfUnits = 1; //Change "0 seconds" to "1 second"
        }

        return $numberOfUnits . ' ' . $period . (($numberOfUnits > 1) ? 's' : '');
    }
}


function echo_time_date($t, $date_only = false)
{
    if (!$t) {
        return 'NOW';
    }
    $timestamp = (is_numeric($t) ? $t : strtotime(substr($t, 0, 19)));
    $year = (date("Y") == date("Y", $timestamp));
    return date(($year ? "D M j " : "j M Y") . ($date_only ? "" : " H:i:s"), $timestamp);
}


function echo_en_cache($config_var_name, $en_id, $micro_status = true, $data_placement = 'top')
{

    /*
     *
     * UI for Platform Cache players
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item($config_var_name);
    $cache_en = $config_array[$en_id];
    if (!$cache_en) {
        //Could not find matching item
        return false;
    }


    //We have two skins for displaying statuses:
    if (is_null($data_placement)) {
        if($micro_status){
            return $cache_en['m_icon'].' ';
        } else {
            return $cache_en['m_icon'].' '.$cache_en['m_name'].' ';
        }
    } else {
        return '<span class="status-label" ' . ( $micro_status && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="' . $data_placement . '" title="' . ($micro_status ? $cache_en['m_name'] : '') . (strlen($cache_en['m_desc']) > 0 ? ($micro_status ? ': ' : '') . $cache_en['m_desc'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache_en['m_icon'] . ' ' . ($micro_status ? '' : $cache_en['m_name']) . '</span>';
    }
}



function echo_in_blog($in, $flip_order = false)
{

    //See if user is logged-in:
    $CI =& get_instance();
    $en_all_4737 = $CI->config->item('en_all_4737'); // Blog Statuses
    $en_all_2738 = $CI->config->item('en_all_2738'); //MENCH

    $ui = '<div class="list-group-item itemblog itembloglist no-side-padding">';

    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="MENCHcolumn1 fixedColumns">';

        //Blog:
        $ui .= '<a href="/blog/'.$in['in_id'] . '">';

            $ui .= '<span class="icon-block">'.$en_all_2738[4535]['m_icon'].'</span>';

            $ui .= '<span class="'.( in_array($in['in_status_play_id'], $CI->config->item('en_ids_7355')) ? ' hidden ' : '' ).'">'.$en_all_4737[$in['in_status_play_id']]['m_icon'].' </span>';

            $ui .= '<b class="montserrat blog-url">'.echo_in_title($in['in_title'], false).'</b>';

        $ui .= '</a>';


        //Footnote
        $metadata = unserialize($in['in_metadata']);
        if( isset($metadata['in__metadata_common_steps']) && count(array_flatten($metadata['in__metadata_common_steps'])) > 0 && isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0){
            $ui .= '<div class="montserrat blog-info doupper '.superpower_active(10989).'"><span class="icon-block">&nbsp;</span>'.echo_time_range($in, true).' READ</div>';
        }

    $ui .= '</td>';


    if($flip_order){

        //PLAY
        $ui .= '<td class="MENCHcolumn3 play fixedColumns">';
        $ui .= echo_in_stat_play($in['in_id'], 0);
        $ui .= '</td>';

        //READ
        $ui .= '<td class="MENCHcolumn2 read fixedColumns">';
        $ui .= echo_in_stat_read($in['in_id'], 0);
        $ui .= '</td>';

    } else {

        //READ
        $ui .= '<td class="MENCHcolumn2 read fixedColumns">';
        $ui .= echo_in_stat_read($in['in_id'], 0);
        $ui .= '</td>';

        //PLAY
        $ui .= '<td class="MENCHcolumn3 play fixedColumns">';
        $ui .= echo_in_stat_play($in['in_id'], 0);
        $ui .= '</td>';

    }



    $ui .= '</tr></table>';
    $ui .= '</div>';

    return $ui;
}

function echo_in_stat_read($in_id = 0, $en_id = 0){

    $CI =& get_instance();

    if(superpower_active(10964, true)){

        if($in_id){
            $coin_filter = array(
                'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null,
                'ln_parent_blog_id' => $in_id,
            );
        } elseif($en_id){
            $coin_filter = array(
                'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null,
                'ln_owner_play_id' => $en_id,
            );
        }

        $read_coins = $CI->READ_model->ln_fetch($coin_filter, array(), 1, 0, array(), 'COUNT(ln_id) as total_coins');
        if($read_coins[0]['total_coins'] > 0){
            return '<span class="montserrat read '.superpower_active(10964).'"><span class="icon-block"><i class="fas fa-circle"></i></span>'.echo_number($read_coins[0]['total_coins']).'</span>';
        }

    }

    return null;
}

function echo_in_stat_play($in_id = 0, $en_id = 0){

    $CI =& get_instance();

    if(superpower_active(10983, true)){

        if($in_id){
            $mench = 'play';
            $join_objects = array();
            $coin_filter = array(
                'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_12273')) . ')' => null, //BLOG COIN
                'ln_child_blog_id' => $in_id,
            );
        } elseif($en_id){
            $mench = 'blog';
            $join_objects = array('in_child');
            $coin_filter = array(
                'in_status_play_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
                'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_12273')) . ')' => null, //BLOG COIN
                'ln_parent_play_id' => $en_id,
            );
        }

        $play_coins = $CI->READ_model->ln_fetch($coin_filter, $join_objects, 0, 0, array(), 'COUNT(ln_id) as total_coins');
        if($play_coins[0]['total_coins'] > 0){
            return '<span class="montserrat '.$mench.' '.superpower_active(10983).'"><span class="icon-block"><i class="fas fa-circle"></i></span>'.echo_number($play_coins[0]['total_coins']).'</span>';
        }

    }

    return null;
}



function echo_in_read($in, $show_description = false, $footnotes = null, $common_prefix = null, $extra_class = null)
{

    //See if user is logged-in:
    $CI =& get_instance();
    $session_en = superpower_assigned();
    $completion_rate = $CI->READ_model->read__completion_progress($session_en['en_id'], $in);


    $ui  = '<div class="list-group-item no-side-padding itemread '.$extra_class.'">';
    $ui .= ( $completion_rate['completion_percentage'] > 0 ? '<a href="/'.$in['in_id'] . '" class="itemread">' : '' );
    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
    $ui .= '<td>';


    //READ ICON
    $ui .= '<span class="icon-block">'.( $completion_rate['completion_percentage'] > 0 ? '<i class="fas fa-circle read"></i>' : '<i class="fas fa-lock read"></i>' ).'</span>';
    $ui .= '<b class="montserrat blog-url">'.echo_in_title($in['in_title'], false, $common_prefix).'</b>';

    //Description:
    if($show_description){
        $in_description = echo_in_description($in['in_id']);
        if($in_description){
            $ui .= '<div class="blog-desc"><span class="icon-block">&nbsp;</span>'.$in_description.'</div>';
        }
    }

    //Show completion if glasses are on:
    if($completion_rate['completion_percentage'] > 0){
        $ui .= '<div class="blog-info montserrat doupper '.superpower_active(10989).'"><span class="icon-block">&nbsp;</span><span title="'.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' blogs read">['.$completion_rate['completion_percentage'].'% done]</span></div>';
    }

    if($footnotes){
        $ui .= '<div class="blog-footnote"><span class="icon-block">&nbsp;</span>' . $footnotes . '</div>';
    }

    $ui .= '</td>';

    //Search for Blog Image:
    if($completion_rate['completion_percentage'] > 0){
        $in_thumbnail = echo_in_thumbnail($in['in_id']);
        if($in_thumbnail){
            $ui .= '<td class="featured-frame">'.$in_thumbnail.'</td>';
        }
    }

    $ui .= '</tr></table>';
    $ui .= ( $completion_rate['completion_percentage'] > 0 ? '</a>' : '' );
    $ui .= '</div>';

    return $ui;
}

function echo_in_description($in_id){

    $CI =& get_instance();

    foreach ($CI->READ_model->ln_fetch(array(
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_play_id' => 4231, //Blog Note Messages
        'ln_child_blog_id' => $in_id,
    ), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {

        //See if Text Message:
        if($ln['ln_parent_play_id'] > 0){
            $ln_content = trim(str_replace('@'.$ln['ln_parent_play_id'],'',$ln['ln_content']));
        } else {
            $ln_content = $ln['ln_content'];
        }

        if(strlen($ln_content) > 0){
            //This is the first text message:
            if(strlen($ln_content) < config_var(12363)){
                //Qualifies as feature message:
                return $CI->READ_model->dispatch_message($ln['ln_content']);
            }

            //Break either way:
            break;
        }
    }

    return null;

}

function echo_in_thumbnail($in_id){

    $CI =& get_instance();
    $embed_code = null;

    foreach ($CI->READ_model->ln_fetch(array(
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_play_id' => 4231, //Blog Note Messages
        'ln_child_blog_id' => $in_id,
        'ln_parent_play_id >' => 0, //Reference a player
    ), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {

        //Images?
        foreach($CI->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 4260, //Image
            'ln_child_play_id' => $ln['ln_parent_play_id'],
        )) as $embed_image){
            $embed_code .= '<div class="inline-block featured-frame pull-right"><span class="featured-image"><img src="'.$embed_image['ln_content'].'" /></span></div>';
        }
        if($embed_code){
            break;
        }

        //Embed Videos?
        foreach($CI->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 4257, //Embed
            'ln_child_play_id' => $ln['ln_parent_play_id'],
        )) as $embed_video){
            $youtube_id = extract_youtube_id($embed_video['ln_content']);
            if(strlen($youtube_id) > 0){
                $embed_code .= '<div class="inline-block featured-frame pull-right"><span class="featured-image"><img src="http://i3.ytimg.com/vi/'.$youtube_id.'/maxresdefault.jpg" /></span></div>';
            }
            if($embed_code){
                break;
            }
        }
        if($embed_code){
            break;
        }
    }

    //Return results:
    return $embed_code;

}


function echo_in_dashboard($in)
{
    $CI =& get_instance();
    $en_all_7585 = $CI->config->item('en_all_7585'); // Blog Subtypes
    $ui = '<div class="list-group-item itemblog">';

    //FOLLOW
    $ui .= '<div class="pull-right inline-block" style="padding-left:3px"><a class="btn btn-blog" href="/blog/' . $in['in_id']. '"><i class="fad fa-step-forward"></i></a></div>';
    $ui .= '<span class="icon-block">'.$en_all_7585[$in['in_type_play_id']]['m_icon'].'</span>';
    $ui .= '<b class="montserrat">'.echo_in_title($in['in_title'], false).'</b>';
    $ui .= '</div>';
    return $ui;

}

function echo_in_scores_answer($starting_in, $depth_levels, $original_depth_levels, $parent_in_type_play_id){

    if($depth_levels<=0){
        //End recursion:
        return false;
    }

    //We're going 1 level deep:
    $depth_levels--;

    //Go down recursively:
    $CI =& get_instance();
    $en_all_6186 = $CI->config->item('en_all_6186'); //Link Statuses
    $en_all_4486 = $CI->config->item('en_all_4486');
    $en_all_4737 = $CI->config->item('en_all_4737'); // Blog Statuses
    $en_all_7585 = $CI->config->item('en_all_7585'); // Blog Subtypes


    $ui = null;
    foreach($CI->READ_model->ln_fetch(array(
        'ln_parent_blog_id' => $starting_in,
        'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_play_id IN (' . join(',', $CI->config->item('en_ids_7356')) . ')' => null, //Blog Statuses Active
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $in_ln){

        //Prep Metadata:
        $metadata = unserialize($in_ln['ln_metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_play_id' => 4231, //Blog Note Messages
            'ln_child_blog_id' => $in_ln['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Blog Link Type: '.$en_all_4486[$in_ln['ln_type_play_id']]['m_name'].'">'. $en_all_4486[$in_ln['ln_type_play_id']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Blog Link Status: '.$en_all_6186[$in_ln['ln_status_play_id']]['m_name'].'">'. $en_all_6186[$in_ln['ln_status_play_id']]['m_icon'] . '</span>';

        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Blog Type: '.$en_all_7585[$in_ln['in_type_play_id']]['m_name'].'">'. $en_all_7585[$in_ln['in_type_play_id']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Blog Status: '.$en_all_4737[$in_ln['in_status_play_id']]['m_name'].'">'. $en_all_4737[$in_ln['in_status_play_id']]['m_icon']. '</span>';
        $ui .= '<a href="/play/admin_panel/assessment_marks_birds_eye?starting_in='.$in_ln['in_id'].'&depth_levels='.$original_depth_levels.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this blog"><u>' .   echo_in_title($in_ln['in_title'], false) . '</u></a>';

        $ui .= ' [<span data-toggle="tooltip" data-placement="top" title="Completion Marks">'.( ($in_ln['ln_type_play_id'] == 4228 && in_array($parent_in_type_play_id , $CI->config->item('en_ids_6193') /* OR Blogs */ )) || ($in_ln['ln_type_play_id'] == 4229) ? echo_in_marks($in_ln) : '' ).'</span>]';

        if(count($messages) > 0){
            $ui .= ' <a href="javascript:void(0);" onclick="$(\'.messages-'.$in_ln['in_id'].'\').toggleClass(\'hidden\');"><i class="fas fa-comment"></i><b>' .  count($messages) . '</b></a>';
        }
        $ui .= '</div>';

        //Display Messages:
        $ui .= '<div class="messages-'.$in_ln['in_id'].' hidden">';
        foreach ($messages as $msg) {
            $ui .= '<div class="tip_bubble">';
            $ui .= $CI->READ_model->dispatch_message($msg['ln_content']);
            $ui .= '</div>';
        }
        $ui .= '</div>';

        //Go Recursively down:
        $ui .=  echo_in_scores_answer($in_ln['in_id'], $depth_levels, $original_depth_levels, $in_ln['in_type_play_id']);

    }

    //Return the wrapped UI if existed:
    return ($ui ? '<div class="inline-box">' . $ui . '</div>' : false);
}

function echo_radio_players($parent_en_id, $child_en_id, $enable_mulitiselect, $show_max = 25){

    /*
     * Print UI for
     * */

    $CI =& get_instance();
    $count = 0;

    $ui = '<div class="list-group radio-'.$parent_en_id.'">';

    if(!count($CI->config->item('en_ids_'.$parent_en_id))){
        return false;
    }

    foreach($CI->config->item('en_all_'.$parent_en_id) as $en_id => $m) {
        $ui .= '<a href="javascript:void(0);" onclick="account_update_radio('.$parent_en_id.','.$en_id.','.$enable_mulitiselect.')" class="item'.extract_icon_color($m['m_icon']).' list-group-item montserrat itemsetting item-'.$en_id.' '.( $count>=$show_max ? 'extra-items-'.$parent_en_id.' hidden ' : '' ).( count($CI->READ_model->ln_fetch(array(
                'ln_parent_play_id' => $en_id,
                'ln_child_play_id' => $child_en_id,
                'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )))>0 ? ' active ' : '' ). '"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'<span class="change-results"></span></a>';
        $count++;
    }


    //Did we have too many items?
    if($count>=$show_max){
        //Show "Show more" button
        $ui .= '<a href="javascript:void(0);" class="list-group-item itemplay itemsetting montserrat extra-items-'.$parent_en_id.'" onclick="$(\'.extra-items-'.$parent_en_id.'\').toggleClass(\'hidden\')"><span class="icon-block"><i class="fas fa-plus-circle"></i></span>Show '.($count-$show_max).' more</a>';
    }

    $ui .= '</div>';

    return $ui;
}


function echo_en_stats_overview($cached_list, $report_name){

    $CI =& get_instance();
    $inner_ui = '';
    $total_count = 0;
    foreach($cached_list as $group_en_id=>$people_group) {

        //See if this item has a cahce, which if does, we need to fetch it's children ($cached_list granchildren):
        if(is_array($CI->config->item('en_ids_'.$group_en_id))){

            $identifier = 'child_ends_'.$group_en_id;
            $subset_total = 0;

            foreach($CI->config->item('en_ids_'.$group_en_id) as $inner_group_en_id=>$inner_people_group) {

                //Do a child count:
                $child_links = $CI->READ_model->ln_fetch(array(
                    'ln_parent_play_id' => $group_en_id,
                    'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                    'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                    'en_status_play_id IN (' . join(',', $CI->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
                ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');

                $subset_total += $child_links[0]['totals'];

            }

            $total_count += $subset_total;

            $inner_ui .= '<tr>';
            $inner_ui .= '<td style="text-align: left;"><span class="icon-block">' . $people_group['m_icon'] . '</span><a href="/play/'.$group_en_id.'">'.$people_group['m_name'].'</a></td>';
            $inner_ui .= '<td style="text-align: right;"><a href="/ledger?ln_status_play_id='.join(',', $CI->config->item('en_ids_7359')) /* Link Statuses Public */.'&ln_type_play_id='.join(',', $CI->config->item('en_ids_4592')).'&ln_parent_play_id=' . join(',', $CI->config->item('en_ids_'.$group_en_id)) . '">' . number_format($subset_total, 0) . '</a></td>';
            $inner_ui .= '</tr>';

        } else {

            //Do a child count:
            $child_links = $CI->READ_model->ln_fetch(array(
                'ln_parent_play_id' => $group_en_id,
                'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
                'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'en_status_play_id IN (' . join(',', $CI->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
            ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');

            $total_count += $child_links[0]['totals'];

            $inner_ui .= '<tr>';
            $inner_ui .= '<td style="text-align: left;"><span class="icon-block">' . $people_group['m_icon'] . '</span><a href="/play/'.$group_en_id.'">' . $people_group['m_name'] . '</a></td>';
            $inner_ui .= '<td style="text-align: right;"><a href="/ledger?ln_status_play_id='.join(',', $CI->config->item('en_ids_7359')) /* Link Statuses Public */.'&ln_type_play_id='.join(',', $CI->config->item('en_ids_4592')).'&ln_parent_play_id=' . $group_en_id . '">' . number_format($child_links[0]['totals'], 0) . '</a></td>';
            $inner_ui .= '</tr>';

        }


    }


    $ui = '<table class="table table-sm table-striped stats-table">';

    $ui .= '<tr class="panel-title down-border">';
    $ui .= '<td style="text-align: left;" colspan="2">'.$report_name.' ['.number_format($total_count,0).']</td>';
    $ui .= '</tr>';

    $ui .= $inner_ui;
    $ui .= '</table>';

    return $ui;

}

function echo_in_marks($in_ln){

    //Validate core inputs:
    if(!isset($in_ln['ln_metadata']) || !isset($in_ln['ln_type_play_id'])){
        return false;
    }

    //prep metadata:
    $ln_metadata = unserialize($in_ln['ln_metadata']);

    //Return mark:
    return ( $in_ln['ln_type_play_id'] == 4228 ? ( !isset($ln_metadata['tr__assessment_points']) || $ln_metadata['tr__assessment_points'] == 0 ? '' : '<span class="score-range">[<span style="'.( $ln_metadata['tr__assessment_points']>0 ? 'font-weight:bold;' : ( $ln_metadata['tr__assessment_points'] < 0 ? 'font-weight:bold;' : '' )).'">' . ( $ln_metadata['tr__assessment_points'] > 0 ? '+' : '' ) . $ln_metadata['tr__assessment_points'].'</span>]</span>' ) : '<span class="score-range">['.$ln_metadata['tr__conditional_score_min'] . ( $ln_metadata['tr__conditional_score_min']==$ln_metadata['tr__conditional_score_max'] ? '' : '-'.$ln_metadata['tr__conditional_score_max'] ).'%]</span>' );

}

function in_is_author($in_id, $session_en = array()){

    $CI =& get_instance();

    if(!isset($session_en['en_id'])){
        //Fetch from session:
        $session_en = superpower_assigned();
    }

    if(!isset($session_en['en_id']) || $in_id < 1){
        return false;
    }

    //Always have power to edit blogs from anyone:
    if(superpower_active(10985, true)){
        return true;
    }

    //Check if player is a blog author:
    return count($CI->READ_model->ln_fetch(array(
            'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            'ln_type_play_id' => 4983,
            'ln_child_blog_id' => $in_id,
            'ln_parent_play_id' => $session_en['en_id'],
        )));
}

function echo_in_setting($in_setting_en_id, $in_field_name, $addup_total_count){

    $CI =& get_instance();
    $en_all_7302 = $CI->config->item('en_all_7302'); //Blog Stats

    $ui =  '<table class="table table-sm table-striped stats-table mini-stats-table ">';

    $ui .= '<tr class="panel-title down-border">';
    $ui .= '<td style="text-align: left;" colspan="2">'.$en_all_7302[$in_setting_en_id]['m_name'].echo__s(count($CI->config->item('en_all_'.$in_setting_en_id))).'</td>';
    $ui .= '</tr>';

    foreach ($CI->config->item('en_all_'.$in_setting_en_id) as $type_en_id => $in_type) {

        //Count this sub-type from the database:
        $in_count = $CI->BLOG_model->in_fetch(array(
            $in_field_name => $type_en_id,
            'in_status_play_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        ), 0, 0, array(), 'COUNT(in_id) as total_public_blogs');

        //$ui .= this as the main title:
        $ui .= '<tr>';
        $ui .= '<td style="text-align: left;"><span class="icon-block">'.$in_type['m_icon'].'</span><a href="/play/'.$type_en_id.'">'.$in_type['m_name'].'</a></td>';
        $ui .= '<td style="text-align: right;"><a href="/ledger?ln_type_play_id=4250&in_status_play_id=' . join(',', $CI->config->item('en_ids_7356')) . '&'.$in_field_name.'='.$type_en_id.'" data-toggle="tooltip" data-placement="top" title="'.number_format($in_count[0]['total_public_blogs'], 0).' Blog'.echo__s($in_count[0]['total_public_blogs']).'">'.number_format($in_count[0]['total_public_blogs']/$addup_total_count*100, 1).'%</a></td>';
        $ui .= '</tr>';

    }

    $ui .= '</table>';

    return $ui;
}


function echo_2level_stats($stat_name, $stats_en_id, $mother_en_id, $link_types_counts, $addup_total_count, $link_field, $display_field){

    $CI =& get_instance();

    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;">'.$stat_name.'</td>';
    if($display_field=='total_coins'){
        echo '<td style="text-align: right;">COINS</td>';
    } else {
        echo '<td>&nbsp;</td>';
    }
    echo '</tr>';

    $all_shown = array();
    foreach ($CI->config->item('en_all_'.$stats_en_id) as $en_id => $m) {
        echo_2level_players($m, $CI->config->item('en_all_'.$en_id), $link_types_counts, $all_shown, $link_field, 'en_all_'.$mother_en_id, $addup_total_count, $display_field);
        $all_shown = array_merge($all_shown, $CI->config->item('en_ids_'.$en_id));
    }

    //Turn into array:
    $remaining_child = array();
    foreach($CI->config->item('en_all_'.$mother_en_id) as $en_id => $m){
        $remaining_child[$en_id] = $m;
    }

    //Display RemainingIF ANY:
    echo_2level_players(array(
        'm_icon' => '<i class="fas fa-plus-circle"></i>',
        'm_name' => 'Others',
        'm_desc' => 'What is left',
    ), $remaining_child, $link_types_counts, $all_shown, $link_field, 'en_all_'.$mother_en_id, $addup_total_count, $display_field);

    echo '</table>';

}


function echo_2level_players($main_obj, $all_link_types, $link_types_counts, $all_shown, $link_field, $details_en, $addup_total_count, $display_field){

    if(!is_array($all_link_types) || count($all_link_types) < 1){
        return false;
    }

    $CI =& get_instance();

    $en_all_detail = $CI->config->item($details_en);
    $identifier = substr(md5($main_obj['m_name']), 0, 10);

    $sub_rows = '';
    $sub_advance_rows = '';

    //First display all children and sum them up:
    $total_sum = 0;
    $all_link_type_ids = array();
    foreach($all_link_types as $en_id => $m){

        if(in_array($en_id , $all_shown)){
            continue;
        }

        $ln = filter_array($link_types_counts, 'in_type_play_id', $en_id);
        $show_in_advance_only = ($display_field=='total_coins' && abs($ln[$display_field]) < 1 );

        if( !$ln['total_count'] ){
            continue;
        }

        array_push($all_link_type_ids, $en_id);

        //Addup counter:
        if($display_field=='total_count'){
            $total_sum += $ln['total_count'];
        } elseif($display_field=='total_coins'){
            $total_sum += abs($ln['total_coins']);
        }

        //Subrow UI:
        $rows =  '<tr class="hidden ' . $identifier . '">';


        if(!isset($en_all_detail[$en_id])){

            $rows .= '<td style="text-align: left; padding-left:30px;" colspan="2">MISSING @'.$en_id.' as Link Type</td>';

        } else {

            $rows .= '<td style="text-align: left;" class="'.( $show_in_advance_only ? superpower_active(10967) : '' ).'">';
            $rows .= '<span class="icon-block" style="margin-left:8px;">'.$m['m_icon'].'</span>';
            $rows .= '<a href="/play/'.$en_id.'">'.$m['m_name'].'</a>';
            $rows .= '</td>';


            $rows .= '<td style="text-align: right;" class="'.( $show_in_advance_only ? superpower_active(10967) : '' ).'">';
            if($display_field=='total_count'){

                $rows .= '<a href="/ledger?ln_status_play_id='.join(',', $CI->config->item('en_ids_7359')) /* Link Statuses Public */.'&'.$link_field.'=' . $en_id . '" data-toggle="tooltip" data-placement="top" title="'.number_format($ln['total_count'], 0).' Blog'.echo__s($ln['total_count']).'">'.number_format($ln['total_count']/$addup_total_count*100, 1) . '%</a>';

            } elseif($display_field=='total_coins'){

                $rows .= '<a href="/ledger?ln_status_play_id='.join(',', $CI->config->item('en_ids_7359')) /* Link Statuses Public */.'&'.$link_field.'=' . $en_id . '" data-toggle="tooltip" data-placement="top" title="'.number_format($ln['total_coins'], 0).' Coin'.echo__s($ln['total_coins']).'">'.number_format($ln['total_coins'], 0) . '</a>';

            }
            $rows .= '</td>';

        }


        //sub-row count:
        $rows .= '</tr>';

        if($show_in_advance_only){
            $sub_advance_rows .= $rows;
        } else {
            $sub_rows .= $rows;
        }
    }


    //Terminate if nothing found:
    if($total_sum==0){
        return false;
    }



    //Fetch Title:
    echo '<tr>';
    echo '<td style="text-align: left;"><span class="icon-block
">'.$main_obj['m_icon'].'</span><a href="javascript:void(0);" onclick="$(\'.'.$identifier.'\').toggleClass(\'hidden\')">'.$main_obj['m_name'].'<i class="fal fa-plus-circle '.$identifier.'" style="padding-left: 5px;"></i><i class="fal fa-minus-circle '.$identifier.' hidden" style="padding-left: 5px;"></i></a></td>';
    echo '<td style="text-align: right;">';

    if($display_field=='total_count'){

        echo '<a href="/ledger?ln_status_play_id='.join(',', $CI->config->item('en_ids_7359')) /* Link Statuses Public */.'&'.$link_field.'=' . join(',' , $all_link_type_ids) . '" data-toggle="tooltip" data-placement="top" title="'.number_format($total_sum, 0).' Blog'.echo__s($total_sum).'">'.number_format($total_sum/$addup_total_count*100, 1).'%</a>';

    } elseif($display_field=='total_coins'){

        echo '<a href="/ledger?ln_status_play_id='.join(',', $CI->config->item('en_ids_7359')) /* Link Statuses Public */.'&'.$link_field.'=' . join(',' , $all_link_type_ids) . '" data-toggle="tooltip" data-placement="top" title="'.number_format($total_sum, 0).' Coin'.echo__s($total_sum).'">'.number_format($total_sum, 0).'</a>';

    }


    echo '</td>';
    echo '</tr>';


    echo $sub_rows;
    echo $sub_advance_rows;

    //Always add at-least one space:
    echo '<tr class="hidden '.$identifier.'"><td colspan="2">&nbsp;</td></tr>';

    //Maybe a second one too:
    if(fmod(count($all_link_type_ids), 2) == 0){
        //Make it even:
        echo '<tr class="hidden '.$identifier.'"><td colspan="2">&nbsp;</td></tr>';
    }

}



function echo_in($in, $in_linked_id, $is_parent, $is_author)
{

    $CI =& get_instance();

    $en_all_6186 = $CI->config->item('en_all_6186');
    $en_all_4737 = $CI->config->item('en_all_4737');
    $en_all_7585 = $CI->config->item('en_all_7585');
    $en_all_4527 = $CI->config->item('en_all_4527');
    $en_all_4486 = $CI->config->item('en_all_4486');
    $en_all_2738 = $CI->config->item('en_all_2738');
    $en_all_12413 = $CI->config->item('en_all_12413');

    //Prep link metadata to be analyzed later:
    $ln_id = $in['ln_id'];
    $ln_metadata = unserialize($in['ln_metadata']);
    $in_metadata = unserialize($in['in_metadata']);

    $session_en = superpower_assigned();
    $is_published = in_array($in['in_status_play_id'], $CI->config->item('en_ids_7355'));
    $is_link_published = in_array($in['ln_status_play_id'], $CI->config->item('en_ids_7359'));

    $ui = '<div in-link-id="' . $ln_id . '" in-tr-type="' . $in['ln_type_play_id'] . '" blog-id="' . $in['in_id'] . '" parent-blog-id="' . $in_linked_id . '" class="list-group-item no-side-padding itemblog blogs_sortable level2_in object_highlight highlight_in_'.$in['in_id'] . ' blog_line_' . $in['in_id'] . ( $is_parent ? ' parent-blog ' : '' ) . ' in__tr_'.$ln_id.'" style="padding-left:0;">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="MENCHcolumn1">';

        $ui .= '<div class="inline-block">';

        //BLOG ICON:
        $ui .= '<span class="icon-block"><a href="/blog/'.$in['in_id'].'">' . $en_all_2738[4535]['m_icon'] . '</a></span>';


        //BLOG TITLE
        if(superpower_active(10984, true)){
            $ui .= echo_in_text(4736, $in['in_title'], $in['in_id'], ($is_author), (($in['ln_order']*100)+1));
        } else {
            $ui .= '<a href="/blog/'.$in['in_id'].'" class="title-block montserrat">' . echo_in_title($in['in_title']) . '</a>';
        }


        $ui .= '</div>';


        //SECOND STATS ROW
        $ui .= '<div class="doclear">&nbsp;</div>';


        $ui .= '<div class="space-content">';

        //BLOG STATUS
        $ui .= '<div class="inline-block ' . ( $is_published ? superpower_active(10984) : '' ) . '">' . echo_in_dropdown(4737, $in['in_status_play_id'], null, $is_author, false, $in['in_id']) . ' </div>';

        //LINK STATUS (IF NOT PUBLISHED, SHOULD NOT HAPPEN!)
        $ui .= '<span class="icon-block ln_status_play_id_' . $ln_id . ( $is_link_published ? ' hidden ' : '' ) . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6186[$in['ln_status_play_id']]['m_name'].' @'.$in['ln_status_play_id'].': '.$en_all_6186[$in['ln_status_play_id']]['m_desc'].'">' . $en_all_6186[$in['ln_status_play_id']]['m_icon'] . ' </span></span>';


        $ui .= '<div class="inline-block ' . superpower_active(10985) . '">';

        //BLOG TYPE
        $ui .= echo_in_dropdown(7585, $in['in_type_play_id'], null, $is_author, false, $in['in_id']);

        //BLOG READ TIME
        $ui .= echo_in_text(4356, $in['in_read_time'], $in['in_id'], $is_author, ($in['ln_order']*10)+1);

        //LINK TYPE
        $ui .= echo_in_dropdown(4486, $in['ln_type_play_id'], null, $is_author, false, $in['in_id'], $in['ln_id']);

        //LINK MARKS
        $ui .= '<span class="link_marks settings_4228 '.( $in['ln_type_play_id']==4228 ? : 'hidden' ).'">';
        $ui .= echo_in_text(4358, ( isset($ln_metadata['tr__assessment_points']) ? $ln_metadata['tr__assessment_points'] : '' ), $in['ln_id'], $is_author, ($in['ln_order']*10)+2 );
        $ui .='</span>';


        //LINK CONDIITONAL RANGE
        $ui .= '<span class="link_marks settings_4229 '.( $in['ln_type_play_id']==4229 ? : 'hidden' ).'">';
        //MIN
        $ui .= echo_in_text(4735, ( isset($ln_metadata['tr__conditional_score_min']) ? $ln_metadata['tr__conditional_score_min'] : '' ), $in['ln_id'], $is_author, ($in['ln_order']*10)+3);
        //MAX
        $ui .= echo_in_text(4739, ( isset($ln_metadata['tr__conditional_score_max']) ? $ln_metadata['tr__conditional_score_max'] : '' ), $in['ln_id'], $is_author, ($in['ln_order']*10)+4);
        $ui .= '</span>';


        //PREVIOUS & NEXT BLOGS
        $previous_ins = $CI->READ_model->ln_fetch(array(
            'ln_child_blog_id' => $in['in_id'],
            'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
            'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_blogs');
        $next_blogs = $CI->READ_model->ln_fetch(array(
            'ln_parent_blog_id' => $in['in_id'],
            'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
            'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_blogs');

        if($previous_ins[0]['total_blogs'] > 1){
            $ui .= '<span class="montserrat blog" data-toggle="tooltip" data-placement="right" title="' . $en_all_12413[11019]['m_name'] . '"><span class="icon-block">' . $en_all_12413[11019]['m_icon'] . '</span>'.$previous_ins[0]['total_blogs'].'</span>';
        }
        if($next_blogs[0]['total_blogs'] > 0){
            $ui .= '<span class="montserrat blog" data-toggle="tooltip" data-placement="right" title="' . $en_all_12413[11020]['m_name'] . '"><span class="icon-block">' . $en_all_12413[11020]['m_icon'] . '</span>'.$next_blogs[0]['total_blogs'].'</span>';
        }



        $ui .= '</div>';
        $ui .= '</div>';

    $ui .= '</td>';





    //PLAY
    $ui .= '<td class="MENCHcolumn3 play">';

        //RIGHT EDITING:
        $ui .= '<div class="pull-right inline-block">';
        $ui .= '<div class="note-edit edit-off '.superpower_active(10939).'">';

        $ui .= '<span class="show-on-hover">';

        if(in_array($in['ln_type_play_id'], $CI->config->item('en_ids_4486'))){
            if($is_author || !$is_parent){

                if($is_author && !$is_parent){
                    $ui .= '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort black blog-sort-handle"></i></span>';
                }

                //Unlink:
                $ui .= '<span title="Unlink Blog '.$in['ln_type_play_id'].'" data-toggle="tooltip" data-placement="left"><a href="javascript:void(0);" onclick="in_unlink('.$in['in_id'].', '.$in['ln_id'].')"><i class="fas fa-unlink black"></i></a></span>';

            } elseif(!$is_author) {

                //Indicate if NOT an author:
                $ui .= '<span data-toggle="tooltip" title="You are not yet an author of this blog" data-placement="bottom"><i class="fas fa-user-minus read"></i></span>';

            }
        }

        $ui .= '</span>';
        $ui .= '</div>';
        $ui .= '</div>';


        //PLAY STATS
        $ui .= echo_in_stat_play($in['in_id'], 0);

    $ui .= '</td>';




    //READ
    $echo_in_stat_read = echo_in_stat_read($in['in_id'], 0);
    $ui .= '<td class="MENCHcolumn2 read '.( !$echo_in_stat_read ? 'show-max' : '' ).'">';
    $ui .= $echo_in_stat_read;
    $ui .= '</td>';





    $ui .= '</tr></table>';
    $ui .= '</div>';



    return $ui;

}




function echo_caret($en_id, $m, $url_append){
    //Display drop down menu:
    $CI =& get_instance();

    $superpower_actives = array_intersect($CI->config->item('en_ids_10957'), $m['m_parents']);

    $ui = '<li class="nav-item dropdown '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'" title="'.$m['m_name'].'" data-toggle="tooltip" data-placement="top">';
    $ui .= '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>';
    $ui .= '<div class="dropdown-menu">';
    foreach ($CI->config->item('en_all_'.$en_id) as $en_id2 => $m2){
        $ui .= '<a class="dropdown-item" href="' . $m2['m_desc'] . $url_append . '"><span class="icon-block">'.$m2['m_icon'].'</span> '.$m2['m_name'].'</a>';
    }
    $ui .= '</div>';
    $ui .= '</li>';

    return $ui;
}


function echo_in_list($in, $in__children, $recipient_en, $push_message, $prefix_statement = null, $in_reads = true){

    //If no list just return the next step:
    if(!count($in__children)){
        return echo_in_next($in['in_id'], $recipient_en, $push_message);
    }

    $CI =& get_instance();

    if(count($in__children)){

        if(!$push_message){
            echo '<div class="previous_reads">';
        }

        //List children so they know what's ahead:
        $found_incomplete = false;
        $trigger_show_all = 0;
        $found_upcoming = 0;
        $max_and_list = ( $push_message ? 5 : 0 );
        $common_prefix = common_prefix($in__children, 'in_title', $in, $max_and_list);
        $completion_rate = array();
        $next_key = -1;
        $has_content = ($prefix_statement || strlen($common_prefix));

        if($push_message){

            $message_content = ( $has_content ? trim($prefix_statement)."\n\n" : '' );
            $msg_quick_reply = array();

        } else {

            //HTML:
            if($has_content){
                echo '<div class="read-topic">'.trim($prefix_statement).'</div>';
            } else {
                echo '<div class="read-topic"><span class="icon-block"><i class="fad fa-step-forward"></i></span>NEXT:</div>';
            }
            echo '<div class="list-group">';

        }


        //First analyze overall list to see how things are:
        foreach($in__children as $key => $child_in) {
            $completion_rate[$key] = $CI->READ_model->read__completion_progress($recipient_en['en_id'], $child_in);
            //Show if All reads are read or none of them are read
            $trigger_show_all += ( $completion_rate[$key]['completion_percentage']==100 || $completion_rate[$key]['completion_percentage']==0 ? 1 : 0 );
            if($completion_rate[$key]['completion_percentage']<100 && !$found_incomplete){
                //We found the next incomplete step:
                $found_incomplete = true;
                $next_key = $key;
            } else {
                $found_upcoming++;
            }
        }

        //If all done, everything would be visible:
        $all_done = ( $trigger_show_all == count($in__children) );

        if($next_key < 0){
            $found_upcoming--;
        }

        foreach($in__children as $key => $child_in){

            if($push_message){

                $message_content .= ($key+1).'. '.echo_in_title($child_in['in_title'], $push_message, $common_prefix).( $completion_rate[$key]['completion_percentage'] > 0 ? '<span class="'.superpower_active(10989).'">['.$completion_rate[$key]['completion_percentage'].'% DONE] </span>' : '' )."\n";

                if($next_key==$key){
                    array_push($msg_quick_reply, array(
                        'content_type' => 'text',
                        'title' => 'NEXT',
                        'payload' => ( $in_reads ? 'GONEXT_'.$child_in['in_id'] : 'ADD_RECOMMENDED_' . $in['in_id']. '_' . $child_in['in_id'] ),
                    ));
                }

                //We know that the $next_step_message length cannot surpass the limit defined by facebook
                if (($key >= $max_and_list || strlen($message_content) > (config_var(11074) - 150))) {
                    //We cannot add any more, indicate truncating:
                    $remainder = count($in__children) - $max_and_list;
                    $message_content .= "\n\n".'... plus ' . $remainder . ' more read' . echo__s($remainder) . '.';
                    break;
                }

            } else {

                echo echo_in_read($child_in, false, null, $common_prefix, ( $next_key>=0 && $next_key!=$key && !$all_done ? 'hidden is_upcoming' : '' ));

            }
        }

        if($push_message){

            $CI->READ_model->dispatch_message(
                $message_content,
                $recipient_en,
                true,
                $msg_quick_reply
            );

        } else {

            echo '</div>';
            if($found_upcoming > 0 && !$all_done){
                echo '<div class="is_upcoming montserrat" style="padding:5px 0 5px 0;"><a href="javascript:void(0);" onclick="$(\'.is_upcoming\').toggleClass(\'hidden\');"><span class="icon-block"><i class="fas fa-plus-circle read"></i></span>'.$found_upcoming.' MORE</a></div>';
            }

        }
    }

    if(!$push_message){
        echo '</div>';
    }

    //Always show next:
    echo_in_next($in['in_id'], $recipient_en, $push_message);

}

function echo_in_next($in_id, $recipient_en, $push_message){

    //A function to display warning/success messages to users:
    $CI =& get_instance();

    if($push_message){

        $CI->READ_model->dispatch_message(
            'Say next to read on.',
            $recipient_en,
            true,
            array(
                array(
                    'content_type' => 'text',
                    'title' => 'Next',
                    'payload' => 'GONEXT_'.$in_id,
                )
            )
        );

    } else {

        /*
        ?>
        <div class="container fixed-bottom" style="padding-bottom: 0 !important;">
            <div class="row">
                <table>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="read"><a class="mench_coin read border-read" href="/'.$in_id.'/next">NEXT <i class="fad fa-step-forward"></i></a></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
        */

        //PREVIOUS:
        echo echo_in_previous($in_id, $recipient_en);

        //NEXT:
        echo '<div class="inline-block margin-top-down previous_reads"><a class="btn btn-read" href="/'.$in_id.'/next">NEXT <i class="fad fa-step-forward"></i></a></div>';

    }

}

function echo_in_previous($in_id, $recipient_en){

    if(!$recipient_en || $recipient_en['en_id'] < 1){
        return null;
    }

    //Now fetch the parent of the current
    $ui = null;
    $CI =& get_instance();
    $recursive_parents = $CI->BLOG_model->in_fetch_recursive_parents($in_id, true, true);
    $en_all_4737 = $CI->config->item('en_all_4737'); // Blog Statuses
    $en_all_2738 = $CI->config->item('en_all_2738');

    //READ LIST
    $player_list = $CI->READ_model->ln_fetch(array(
        'ln_owner_play_id' => $recipient_en['en_id'],
        'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_7347')) . ')' => null, //🔴 READING LIST Blog Set
        'in_status_play_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Blog Statuses Public
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array('in_parent'), 0);

    //Cleanup the list:
    $list_ids = array();
    foreach($player_list as $in_list){
        array_push($list_ids, $in_list['in_id']);
    }


    foreach ($recursive_parents as $grand_parent_ids) {
        foreach(array_intersect($grand_parent_ids, $list_ids) as $intersect) {

            //Show the breadcrumb since it's connected:
            $ui = '<div class="previous_reads hidden">';
            $ui .= '<div class="read-topic"><span class="icon-block"><i class="fas fa-step-backward"></i></span>SELECT PREVIOUS:</div>';
            $ui .= '<div class="list-group bottom-read-line">';

            $breadcrumb_items = array();

            foreach ($grand_parent_ids as $parent_in_id) {

                //Fetch this blog name:
                $ins_this = $CI->BLOG_model->in_fetch(array(
                    'in_id' => $parent_in_id,
                ));
                if (count($ins_this) > 0) {

                    $completion_ui_rate = '';
                    if (superpower_active(10989, true)) {
                        //Calcullate completion time:
                        $completion_rate = $CI->READ_model->read__completion_progress($recipient_en['en_id'], $ins_this[0]);
                        if ($completion_rate['completion_percentage'] > 0) {
                            $completion_ui_rate = '<span title="' . $completion_rate['steps_completed'] . '/' . $completion_rate['steps_total'] . ' read" class="'.superpower_active(10989).'"> [' . $completion_rate['completion_percentage'] . '%]</span>';
                        }
                    }

                    array_push($breadcrumb_items, '<a href="/' . $parent_in_id . '"class="list-group-item itemread no-side-padding montserrat"><span class="icon-block">' . $en_all_2738[6205]['m_icon'] . '</span><span class="icon-block' . (in_array($ins_this[0]['in_status_play_id'], $CI->config->item('en_ids_7355')) ? ' hidden ' : '') . '"><span data-toggle="tooltip" data-placement="right" title="' . $en_all_4737[$ins_this[0]['in_status_play_id']]['m_name'] . ': ' . $en_all_4737[$ins_this[0]['in_status_play_id']]['m_desc'] . '">' . $en_all_4737[$ins_this[0]['in_status_play_id']]['m_icon'] . '</span></span>' . $ins_this[0]['in_title'] . $completion_ui_rate . '</a>');

                }

                if ($parent_in_id == $intersect) {
                    break;
                }
            }

            $ui .= join('', array_reverse($breadcrumb_items));
            $ui .= '</div>';
            $ui .= '</div>';

            break; //TODO Remove later and allow multiple parent links

        }

        if($ui){
            break; //TODO Remove later and allow multiple parent links
        }
    }


    //Did We Find It?
    if($ui){
        //Now show button to show parents:
        $ui .= '<div class="inline-block margin-top-down selected_before"><a class="btn btn-read" href="javascript:void(0);" onclick="$(\'.previous_reads\').toggleClass(\'hidden\');"><span class="previous_reads"><i class="fad fa-step-backward"></i></span><span class="previous_reads hidden"><i class="fas fa-times"></i></span></a>&nbsp;&nbsp;&nbsp;</div>';
    }

    return $ui;
}
function echo_message($message, $is_error, $recipient_en, $push_message){

    //A function to display warning/success messages to users:
    if($push_message){
        $CI =& get_instance();
        $CI->READ_model->dispatch_message(
            'Note: ' . $message,
            $recipient_en,
            true
        );
    } else {
        //HTML:
        echo '<div class="alert '.( $is_error ? 'alert-danger' : 'alert-info' ).'">'.( $is_error ? '<i class="fad fa-exclamation-triangle"></i> ' : '<i class="fas fa-info-circle"></i> ' ).$message.' </div>';
    }

}


function echo_en($en, $is_parent = false)
{

    $CI =& get_instance();

    if(!isset($en['en_id'])){
        $CI->READ_model->ln_create(array(
            'ln_content' => 'echo_en() variable missing PLAY',
            'ln_metadata' => $en,
            'ln_type_play_id' => 4246, //Platform Bug Reports
        ));
        return false;
    }
    $session_en = superpower_assigned();
    $en_all_6177 = $CI->config->item('en_all_6177'); //Player Statuses
    $en_all_4527 = $CI->config->item('en_all_4527');
    $en_all_2738 = $CI->config->item('en_all_2738');
    $en_all_11028 = $CI->config->item('en_all_11028'); //PLAYERS LINKS DIRECTION

    $ln_id = (isset($en['ln_id']) ? $en['ln_id'] : 0);
    $is_play_link = ( $ln_id > 0 && in_array($en['ln_type_play_id'], $CI->config->item('en_ids_4592')));
    $is_read_progress = ( $ln_id > 0 && in_array($en['ln_type_play_id'], $CI->config->item('en_ids_12227')));
    $ui = null;

    $en__parents = $CI->READ_model->ln_fetch(array(
        'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
        'ln_child_play_id' => $en['en_id'], //This child player
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'en_status_play_id IN (' . join(',', $CI->config->item('en_ids_7358')) . ')' => null, //Player Statuses Active
    ), array('en_parent'), 0, 0, array('en_name' => 'ASC'));

    $child_links = $CI->READ_model->ln_fetch(array(
        'ln_parent_play_id' => $en['en_id'],
        'ln_type_play_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Player-to-Player Links
        'ln_status_play_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'en_status_play_id IN (' . join(',', $CI->config->item('en_ids_7357')) . ')' => null, //Player Statuses Public
    ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');

    $is_published = in_array($en['en_status_play_id'], $CI->config->item('en_ids_7357'));
    $is_link_published = ( $ln_id > 0 && in_array($en['ln_status_play_id'], $CI->config->item('en_ids_7359')));
    $is_hidden = filter_array($en__parents, 'en_id', '4755');

    if(!$session_en && ($is_hidden || !$is_published || !$is_link_published)){
        //Not logged in, so should only see published:
        return false;
    } elseif($is_hidden && !superpower_active(10986, true)){
        //They don't have the needed superpower:
        return false;
    }

    //ROW
    $ui .= '<div class="list-group-item no-side-padding itemplay en-item object_highlight '.( $is_hidden ? superpower_active(10986) : '' ).' highlight_en_'.$en['en_id'].' en___' . $en['en_id'] . ( $ln_id > 0 ? ' tr_' . $en['ln_id'].' ' : '' ) . ( $is_parent ? ' parent-player ' : '' ) . '" player-id="' . $en['en_id'] . '" en-status="' . $en['en_status_play_id'] . '" tr-id="'.$ln_id.'" ln-status="'.( $ln_id ? $en['ln_status_play_id'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="MENCHcolumn1">';



    $ui .= '<div class="inline-block">';


    //PLAYER ICON
    $ui .= '<a href="/play/'.$en['en_id'] . '"><span class="icon-block en_ui_icon_' . $en['en_id'] . ' en__icon_'.$en['en_id'].'" en-is-set="'.( strlen($en['en_icon']) > 0 ? 1 : 0 ).'">' . echo_en_icon($en['en_icon']) . '</span></a>';

    //PLAYER NAME
    $ui .= '<a href="/play/'.$en['en_id'] . '" class="title-block montserrat '.extract_icon_color($en['en_icon']).'">'.($child_links[0]['totals'] > 0 ? echo_number($child_links[0]['totals']).' ' : '').'<span class="en_name_' . $en['en_id'] . '">'.$en['en_name'].'</span></a>';

    $ui .= '</div>';




    //Does this player also include a link?
    if ($ln_id > 0) {
        if($is_play_link && (strlen($en['ln_content']) || superpower_assigned(10983))){

            //PLAY LINKS:
            $ui .= '<div class="doclear">&nbsp;</div>';

            $ui .= '<span class="message_content ln_content ln_content_' . $ln_id . '">' . echo_ln_urls($en['ln_content'] , $en['ln_type_play_id']) . '</span>';

            //For JS editing only (HACK):
            $ui .= '<div class="ln_content_val_' . $ln_id . ' hidden overflowhide">' . $en['ln_content'] . '</div>';

        } elseif($is_read_progress && strlen($en['ln_content'])){

            //READ PROGRESS
            $ui .= '<div class="message_content">';
            $ui .= $CI->READ_model->dispatch_message($en['ln_content']);
            $ui .= '</div>';

        }
    }



    //CHILDREN & PARENTS
    $ui .= '<div class="doclear">&nbsp;</div>';
    $ui .= '<div class="space-content">';


    //PLAYER STATUS
    $ui .= '<span class="en_status_play_id_' . $en['en_id'] . ( $is_published ? ' hidden ' : '' ).'"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$en['en_status_play_id']]['m_name'].' @'.$en['en_status_play_id'].': '.$en_all_6177[$en['en_status_play_id']]['m_desc'].'">' . $en_all_6177[$en['en_status_play_id']]['m_icon'] . '</span>&nbsp;</span>';

    //LINK
    if ($ln_id > 0) {

        //Link Type Full List:
        $en_all_6186 = $CI->config->item('en_all_6186'); //Link Statuses

        //LINK STATUS
        $ui .= '<span class="ln_status_play_id_' . $ln_id . ( $is_link_published ? ' hidden ' : '' ) .'"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6186[$en['ln_status_play_id']]['m_name'].' @'.$en['ln_status_play_id'].': '.$en_all_6186[$en['ln_status_play_id']]['m_desc'].'">' . $en_all_6186[$en['ln_status_play_id']]['m_icon'] . '</span>&nbsp;</span>';

        //Show link index
        if($is_play_link && $en['ln_external_id'] > 0){

            //External ID
            if($en['ln_parent_play_id']==6196){
                //Give trainers the ability to ping Messenger profiles:
                $ui .= '<span class="'.superpower_active(10986).'" data-toggle="tooltip" data-placement="right" title="Link External ID = '.$en['ln_external_id'].' [Messenger Profile]"><a href="/read/messenger_fetch_profile/'.$en['ln_external_id'].'"><i class="fas fa-project-diagram"></i></a>&nbsp;</span>';
            } else {
                $ui .= '<span class="'.superpower_active(10986).'" data-toggle="tooltip" data-placement="right" title="Link External ID = '.$en['ln_external_id'].'"><i class="fas fa-project-diagram"></i>&nbsp;</span>';
            }

        }

    }



    $ui .= '<span class="'. superpower_active(10986) .'">';

    if($is_play_link){
        //Link Type
        $en_all_4592 = $CI->config->item('en_all_4592');
        $ui .= '<span class="icon-block-img ln_type_' . $ln_id . superpower_active(10986).'" data-toggle="tooltip" data-placement="bottom" title="LINK ID '.$en['ln_id'].' '.$en_all_4592[$en['ln_type_play_id']]['m_name'].' @'.$en['ln_type_play_id'].'">' . $en_all_4592[$en['ln_type_play_id']]['m_icon'] . '</span> ';
    }

    foreach ($en__parents as $en_parent) {
        $ui .= '<span class="icon-block-img en_child_icon_' . $en_parent['en_id'] . '"><a href="/play/' . $en_parent['en_id'] . '" data-toggle="tooltip" title="' . $en_parent['en_name'] . (strlen($en_parent['ln_content']) > 0 ? ' = ' . $en_parent['ln_content'] : '') . '" data-placement="bottom">' . echo_en_icon($en_parent['en_icon']) . '</a></span> ';
    }
    $ui .= ' </span>';

    $ui .= '</div>';



    $ui .= '</td>';











    //READ
    $read_ui = '<td class="MENCHcolumn2 read">';
    $read_ui .= echo_in_stat_read(0, $en['en_id']);


    //READ TYPE
    if ($ln_id > 0 && $is_read_progress) {
        //LINK TYPE
        $en_all_12227 = $CI->config->item('en_all_12227');
        $read_ui .= '<div class="space-content">';
        $read_ui .= '<span class="ln_type_' . $ln_id . superpower_active(10989).'"><span data-toggle="tooltip" data-placement="right" title="LINK ID '.$en['ln_id'].' '.$en_all_12227[$en['ln_type_play_id']]['m_name'].' @'.$en['ln_type_play_id'].'">' . $en_all_12227[$en['ln_type_play_id']]['m_icon'] . '</span>&nbsp;</span>';
        $read_ui .= '</div>';
    }

    $read_ui .= '</td>';






    //BLOG
    $blog_ui = '<td class="MENCHcolumn3 play">';

    //RIGHT EDITING:
    $blog_ui .= '<div class="pull-right inline-block">';
    $blog_ui .= '<div class="note-edit edit-off '.superpower_active(10967).'">';
    $blog_ui .= '<span class="show-on-hover">';
    $blog_ui .= '<span title="Modify Player" data-toggle="tooltip" data-placement="left"><a href="javascript:void(0);" onclick="en_modify_load(' . $en['en_id'] . ',' . $ln_id . ')"><i class="fas fa-cog black"></i></a></span>';
    $blog_ui .= '</span>';
    $blog_ui .= '</div>';
    $blog_ui .= '</div>';

    $blog_ui .= echo_in_stat_play(0, $en['en_id']);
    $blog_ui .= '</td>';





    //Set order based on view mode:
    if($is_read_progress){

        $ui .= $read_ui.$blog_ui;

    } else {

        $ui .= $blog_ui.$read_ui;

    }


    $ui .= '</tr></table>';
    $ui .= '</div>';

    return $ui;

}


function echo_in_text($cache_en_id, $current_value, $in_ln__id, $is_author, $tabindex = 0, $is_blog_title_lg = false){

    $CI =& get_instance();
    $en_all_12112 = $CI->config->item('en_all_12112');
    $current_value = htmlentities($current_value);

    //Define element attributes:
    $attributes = ( $is_author ? '' : 'disabled' ).' tabindex="'.$tabindex.'" old-value="'.$current_value.'" class="form-control dotransparent montserrat inline-block in_update_text text__'.$cache_en_id.'_'.$in_ln__id.' in_ln__id_'.$in_ln__id.' texttype_'.$cache_en_id.($is_blog_title_lg?'_lg':'_sm').'" cache_en_id="'.$cache_en_id.'" in_ln__id="'.$in_ln__id.'" ';


    $tooltip_span_start = '<span class="'.( !$is_author ? 'edit-locked' : '' ).'" data-toggle="tooltip" data-placement="bottom" title="'.$en_all_12112[$cache_en_id]['m_name'].( !$is_author ? ' (YOU ARE NOT AN AUTHOR)' : '' ).'">';
    $tooltip_span_end = '</span>';


    //Determine ICON
    if($is_blog_title_lg){
        //BLOG COIN:
        $icon = '<span class="icon-block title-icon">'.$en_all_12112[4535]['m_icon'].'</span>';
    } elseif(in_array($cache_en_id, $CI->config->item('en_ids_12420'))){
        $icon = '<span class="icon-block">'.$en_all_12112[$cache_en_id]['m_icon'].'</span>';
    } else {
        $icon = null;
    }

    if($is_blog_title_lg){

        return $tooltip_span_start.$icon.'<textarea onkeyup="in_title_count()" placeholder="'.$en_all_12112[$cache_en_id]['m_name'].'" '.$attributes.'>'.$current_value.'</textarea>'.$tooltip_span_end;

    } else {

        return $tooltip_span_start.$icon.'<input type="text" placeholder="__" value="'.$current_value.'" '.$attributes.' />'.$tooltip_span_end;

    }
}


function echo_menu($menu_id, $btn_class){

    $CI =& get_instance();

    $active_id = 0;
    foreach($CI->config->item('en_all_'.$menu_id) as $en_id => $m){
        if($CI->uri->segment(1) == ltrim($m['m_desc'], '/')){
            $active_id = $en_id;
            break;
        }
    }

    return '<div class="inline-block">'.echo_in_dropdown($menu_id, $active_id, $btn_class, true, false ).'</div>';

}

function echo_in_dropdown($cache_en_id, $selected_en_id, $btn_class, $is_author, $show_full_name, $in_id = 0, $ln_id = 0){

    $CI =& get_instance();
    $en_all_this = $CI->config->item('en_all_'.$cache_en_id);

    if(!$selected_en_id || !isset($en_all_this[$selected_en_id])){
        return false;
    }

    $en_all_12079 = $CI->config->item('en_all_12079');
    $en_all_4527 = $CI->config->item('en_all_4527');

    //data-toggle="tooltip" data-placement="top" title="'.$en_all_4527[$cache_en_id]['m_name'].'"
    $ui = '<div class="dropdown inline-block dropd_'.$cache_en_id.'_'.$in_id.'_'.$ln_id.' '.( !$show_full_name ? ' icon-block ' : '' ).'">';
    $ui .= '<button type="button" '.( $is_author ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' '.$btn_class.'" id="dropdownMenuButton'.$cache_en_id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked  '.$btn_class.'"' ).' >';
    $ui .= '<span title="'.$en_all_12079[$cache_en_id]['m_name'].( !$is_author ? ' (YOU ARE NOT AN AUTHOR)' : '' ).'" data-toggle="tooltip" data-placement="right">';
    $ui .= '<span class="icon-block">' .$en_all_this[$selected_en_id]['m_icon'].'</span><span class="show-max">'.( $show_full_name ?  $en_all_this[$selected_en_id]['m_name'] : '' ).'</span>';
    $ui .= '</span>';
    $ui .= '</button>';
    $ui .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$cache_en_id.'">';

    foreach ($en_all_this as $en_id => $m) {

        $superpower_actives = array_intersect($CI->config->item('en_ids_10957'), $m['m_parents']);
        $is_url_desc = ( substr($m['m_desc'], 0, 1)=='/' );

        $ui .= '<a class="dropdown-item dropi_'.$cache_en_id.'_'.$in_id.'_'.$ln_id.' montserrat optiond_'.$en_id.'_'.$in_id.'_'.$ln_id.' doupper '.( $en_id==$selected_en_id ? ' active ' : ( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ) ).'" '.( $is_url_desc ? ( $en_id==$selected_en_id ? 'href="javascript:void();"' : 'href="'.$m['m_desc'].'"' ) : 'href="javascript:void();" new-en-id="'.$en_id.'" onclick="in_update_dropdown('.$cache_en_id.', '.$en_id.', '.$in_id.', '.$ln_id.', '.intval($show_full_name).')"' ).'><span '.( strlen($m['m_desc']) && !$is_url_desc ? 'title="'.$m['m_desc'].'" data-toggle="tooltip" data-placement="right"' : '' ).'><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</span></a>';

    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}



function echo_navigation_menu($cache_en_id){

    $CI =& get_instance();

    $en_all_this = $CI->config->item('en_all_'.$cache_en_id);
    $en_all_4527 = $CI->config->item('en_all_4527'); //Platform Memory
    $en_all_10876 = $CI->config->item('en_all_10876'); //Mench Website
    $en_all_12502 = $CI->config->item('en_all_12502'); //JS Functions


    $ui = '<div class="dropdown inline-block">';
    $ui .= '<button type="button" class="btn no-side-padding" id="dropdownMenuButton'.$cache_en_id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
    $ui .= '<span class="icon-block">' .$en_all_4527[$cache_en_id]['m_icon'].'</span>';
    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$cache_en_id.'">';

    foreach ($en_all_this as $en_id => $m) {

        //Skip superpowers if not assigned
        if($en_id==10957 && !count($CI->session->userdata('session_superpowers_assigned'))){
            continue;
        } elseif($en_id==7291 && intval($CI->session->userdata('session_6196_sign'))){
            //Messenger sign in does not allow Signout:
            continue;
        }

        $superpower_actives = array_intersect($CI->config->item('en_ids_10957'), $m['m_parents']);

        //Determine URL:
        if(in_array($en_id, $CI->config->item('en_ids_10876'))){

            $href = 'href="'.$en_all_10876[$en_id]['m_desc'].'"';

        } elseif(in_array($en_id, $CI->config->item('en_ids_12502'))){

            $href = 'href="javascript:void();" onclick="'.$en_all_12502[$en_id]['m_desc'].'"';

        } elseif($en_id==12205){

            $session_en = superpower_assigned();

            if($session_en){
                $href = 'href="/play/'.$session_en['en_id'].'"';
            } else {
                continue;
            }

        } else {

            //No Link Structure:
            continue;

        }

        //Navigation
        $ui .= '<a '.$href.' class="dropdown-item montserrat doupper '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</a>';

    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;

}


function echo_json($array)
{
    header('Content-Type: application/json');
    echo json_encode($array);
    return true;
}


function echo_ordinal_number($number)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if (($number % 100) >= 11 && ($number % 100) <= 13) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}

function echo__s($count, $is_es = 0)
{
    //A cute little function to either display the plural "s" or not based on $count
    return ( intval($count) == 1 ? '' : ($is_es ? 'es' : 's'));
}

