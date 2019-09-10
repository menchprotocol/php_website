<?php

function echo_en_load_more($page, $limit, $en__child_count)
{
    /*
     * Gives an option to "Load More" entities when we have too many to show in one go
     * */

    echo '<a class="load-more list-group-item" href="javascript:void(0);" onclick="en_load_next_page(' . $page . ')">';

    //Right content:
    echo '<span class="pull-right" style="margin-right: 6px;"><span class="badge badge-secondary"><i class="fas fa-search-plus"></i></span></span>';

    //Regular section:
    $max_entities = (($page + 1) * $limit);
    $max_entities = ($max_entities > $en__child_count ? $en__child_count : $max_entities);
    echo 'Load ' . (($page * $limit) + 1) . ' - ' . $max_entities . ' from ' . $en__child_count . ' total';

    echo '</a>';
}

function echo_clean_db_name($field_name){
    //Takes a database field name and returns a clean version of it:
    if(substr($field_name, 0, 3) == 'ln_'){
        //Link field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('ln_', 'Link ', $field_name))));
    } elseif(substr($field_name, 0, 3) == 'in_'){
        //Intent field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('in_', 'Intent ', $field_name))));
    } elseif(substr($field_name, 0, 3) == 'en_'){
        //Entity field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('en_', 'Entity ', $field_name))));
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
    return ($min ? $min . 'm' : '') . ($sec ? ($min ? ' ' : '') . $sec . 's' : '');
}


function echo_url_type($url, $en_type_link_id)
{

    /*
     *
     * Displays Entity Links that are a URL based on their
     * $en_type_link_id as listed under Entity URL Links:
     * https://mench.com/entities/4537
     *
     * */
    if ($en_type_link_id == 4256 /* Generic URL */) {

        return '<a href="' . $url . '" target="_blank"><span class="url_truncate">' . echo_url_clean($url) . '<i class="fas fa-external-link" style="font-size: 0.7em; padding-left:3px;"></i></span></a>';

    } elseif ($en_type_link_id == 4257 /* Embed Widget URL? */) {

        return  echo_url_embed($url, $url);

    } elseif ($en_type_link_id == 4260 /* Image URL */) {

        return '<img src="' . $url . '" style="max-width:240px;" />';

    } elseif ($en_type_link_id == 4259 /* Audio URL */) {

        return  '<audio controls><source src="' . $url . '" type="audio/mpeg"></audio>' ;

    } elseif ($en_type_link_id == 4258 /* Video URL */) {

        return  '<video width="100%" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="' . $url . '" type="video/mp4"></video>' ;

    } elseif ($en_type_link_id == 4261 /* File URL */) {

        return '<a href="' . $url . '" class="btn btn-primary" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';

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
     *       values for ln_type_entity_id as this could change the equation for those
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

    if (substr_count($url, 'youtube.com/watch?v=') == 1 || substr_count($url, 'youtu.be/') == 1 || $is_embed) {

        $start_sec = 0;
        $end_sec = 0;
        $video_id = extract_youtube_id($url);

        if($is_embed){
            if(is_numeric(one_two_explode('start=','&',$url))){
                $start_sec = one_two_explode('start=','&',$url);
            }
            if(is_numeric(one_two_explode('end=','&',$url))){
                $end_sec = one_two_explode('end=','&',$url);
            }
        }


        if ($video_id) {

            //Set the Clean URL:
            $clean_url = 'https://www.youtube.com/watch?v=' . $video_id;

            //Inform User that this is a sliced video
            if ($start_sec || $end_sec) {
                $embed_html_code .= '<div class="video-prefix"><i class="fab fa-youtube"></i> Watch ' . (($start_sec && $end_sec) ? 'this <b>' . echo_time_minutes(($end_sec - $start_sec)) . '</b> video clip' : 'from <b>' . ($start_sec ? echo_time_minutes($start_sec) : 'start') . '</b> to <b>' . ($end_sec ? echo_time_minutes($end_sec) : 'end') . '</b>') . ':</div>';
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

function echo_in_outcome($in_outcome, $push_message = false, $show_entire_outcome = false, $common_prefix = null){

    /*
     * This function applies the double column
     * by either removing or greying out the
     * outcome part before ::
     *
     * */

    $CI =& get_instance();

    if(substr($in_outcome,0,1)=='='){

        if($push_message){

            return ( $show_entire_outcome ? $in_outcome : substr($in_outcome,1) );

        } else {

            //Miner view:
            if($show_entire_outcome){
                return '<i class="far fa-equals" style="color: #BBB; font-size: 0.9em;"></i> <span class="click_expand">'.htmlentities(substr($in_outcome,1)).'</span>';
            } else {
                return '<span class="click_expand">'.htmlentities(trim(substr($in_outcome,1))).'</span>';
            }

        }

    } else {

        if(strlen($common_prefix) > 0){
            $in_outcome = trim(substr($in_outcome, strlen($common_prefix)));
            if(preg_match("/^[a-z]+$/", substr($in_outcome, 0, 1))){
                $in_outcome = strtoupper(substr($in_outcome, 0, 1)).substr($in_outcome, 1);
            }
        }

        if($push_message){

            return $in_outcome;

        } else {

            return htmlentities(trim($in_outcome));

        }

    }
}



function echo_in_message_manage($ln)
{

    /*
     *
     * A wrapper function that helps manage messages
     * by giving the message additional platform functions
     * such as editing and changing message type.
     *
     * */

    $CI =& get_instance();
    $session_en = $CI->session->userdata('user');

    //Intent Notes types:
    $en_all_4485 = $CI->config->item('en_all_4485');


    //Link Statuses
    $en_all_6186 = $CI->config->item('en_all_6186');


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item is-msg is_level2_sortable all_msg msg_en_type_' . $ln['ln_type_entity_id'] . '" id="ul-nav-' . $ln['ln_id'] . '" tr-id="' . $ln['ln_id'] . '">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="edit-off text_message" id="msgbody_' . $ln['ln_id'] . '" style="margin:2px 0 0 0;">';

    //Now get the message snippet:
    $ui .= $CI->Communication_model->dispatch_message($ln['ln_content'], $session_en, false, array(), $ln['ln_child_intent_id']);

    $ui .= '</div>';


    //Text editing:
    $ui .= '<textarea onkeyup="in_message_validate(' . $ln['ln_id'] . ')" name="ln_content" id="message_body_' . $ln['ln_id'] . '" class="edit-on hidden msg msgin algolia_search" placeholder="Write Message..." style="margin-top: 4px;">' . $ln['ln_content'] . '</textarea>';

    //Editing menu:
    $ui .= '<ul class="msg-nav">';




    //Links:
    $count_msg_trs = $CI->Links_model->ln_fetch(array(
        '( ln_id = ' . $ln['ln_id'] . ' OR ln_parent_link_id = ' . $ln['ln_id'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    $ui .= '<li class="' . advance_mode() . '" style="min-width:48px; display:inline-block;"><a class="btn btn-primary edit-off" style="border:2px solid #ffe027 !important;" href="/links?ln_id=' . $ln['ln_id'] . '" target="_parent"><i class="fas fa-link"></i> '.echo_number($count_msg_trs[0]['totals']).'</a></li>';

    //Modify:
    $ui .= '<li class="edit-off" style="margin-left:0;"><span class="on-hover"><a class="btn btn-primary white-primary" href="javascript:in_message_modify_start(' . $ln['ln_id'] . ',' . $ln['ln_type_entity_id'] . ');" title="Modify Message" data-toggle="tooltip" data-placement="top" style="border:2px solid #ffe027 !important; margin-right:4px !important;"><i class="fas fa-pen-square"></i></a></span></li>';


    //Status:
    $ui .= '<li class="edit-off message_status" style="margin:0 8px 0 0;"><span title="' . $en_all_6186[$ln['ln_status_entity_id']]['m_name'] . ': ' . $en_all_6186[$ln['ln_status_entity_id']]['m_desc'] . '" data-toggle="tooltip" data-placement="top">' . $en_all_6186[$ln['ln_status_entity_id']]['m_icon'] . '</span></li>';

    //Sort:
    if(in_array(4603, $en_all_4485[$ln['ln_type_entity_id']]['m_parents'])){
        $ui .= '<li class="edit-off"><span title="Drag up/down to sort" data-toggle="tooltip" data-placement="top"><i class="fas fa-sort fa-special-sort '.( in_array(4603, $en_all_4485[$ln['ln_type_entity_id']]['m_parents']) ? 'message-sorting' : '' ).'"></i></span></li>';
    }





    //Counter:
    $ui .= '<li class="edit-on hidden"><span id="charNumEditing' . $ln['ln_id'] . '">0</span>/' . $CI->config->item('messages_max_length') . '</li>';

    //Save Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary white-primary" title="Save changes" data-toggle="tooltip" data-placement="top" href="javascript:in_message_modify_save(' . $ln['ln_id'] . ',' . $ln['ln_type_entity_id'] . ');"><i class="fas fa-check"></i> Save</a></li>';

    //Cancel Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary white-primary" title="Cancel editing" data-toggle="tooltip" data-placement="top" href="javascript:in_message_modify_cancel(' . $ln['ln_id'] . ');"><i class="fas fa-times"></i></a></li>';

    //Show drop down for message link status:
    $ui .= '<li class="pull-right edit-on hidden"><span class="white-wrapper" style="margin:-12px 0 0 0;">';
    $ui .= '<select id="message_status_' . $ln['ln_id'] . '"  class="form-control border" style="margin-bottom:0;" title="Change message status" data-toggle="tooltip" data-placement="top">';
    foreach($CI->config->item('en_all_6186') /* Link Statuses */ as $en_id => $m){
        $ui .= '<option value="' . $en_id . '" '.( $en_id==$ln['ln_status_entity_id'] ? 'selected="selected"' : '' ).'>' . $m['m_name'] . '</option>';
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


function echo_en_icon($en)
{
    //TODO Use this function more often, as there are instnaces where we have this logic replicated! Search for "fas fa-at grey-at" to find...
    //A simple function to display the Entity Icon OR the default icon if not available:
    if (strlen($en['en_icon']) > 0) {
        return $en['en_icon'];
    } else {
        return '<i class="fas fa-at grey-at"></i>';
    }
}

function echo_url($text)
{
    //Find and makes links within $text clickable
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank"><u>$1</u></a>', $text);
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
            return '<span>' . $rounded . $append . '</span>';
        }

    } else {

        return $number;

    }
}

function echo_a_an($string){
    return ( in_array(strtolower(substr($string, 0,1)), array('a','e','i','o','u')) ? 'an' : 'a' );
}

function echo_ln_urls($ln_content, $ln_type_entity_id){

    $ln_content = htmlentities($ln_content);

    $CI =& get_instance();
    if (in_array($ln_type_entity_id, $CI->config->item('en_ids_4537'))) {

        //Entity URL Links
        return echo_url_type($ln_content, $ln_type_entity_id);

    } elseif(strlen($ln_content) > 0) {

        return echo_url($ln_content);

    } else {

        return null;

    }
}

function echo_ln_connections($ln){

    $CI =& get_instance();
    $ln_connections_ui = '';
    foreach ($CI->config->item('tr_object_links') as $ln_field => $obj_type) {

        //Don't show miner and type as they are already printed on the first line:
        if(!(!in_array($ln_field, array('ln_creator_entity_id','ln_type_entity_id')) && intval($ln[$ln_field]) > 0)){
            continue;
        }

        $ln_connections_ui .= '<div class="tr-child">';

        if($obj_type=='en'){
            //Fetch
            $ens = $CI->Entities_model->en_fetch(array('en_id' => $ln[$ln_field]));
            if(count($ens) > 0){
                $ln_connections_ui .= echo_en($ens[0], 0);
            }
        } elseif($obj_type=='in'){
            //Fetch
            $ins = $CI->Intents_model->in_fetch(array('in_id' => $ln[$ln_field]));
            if(count($ins) > 0){
                $ln_connections_ui .= echo_in($ins[0], 0);
            }
        } elseif($obj_type=='ln'){
            //Fetch
            $lns = $CI->Links_model->ln_fetch(array('ln_id' => $ln[$ln_field]));
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
    $en_all_4593 = $CI->config->item('en_all_4593');
    $en_all_4341 = $CI->config->item('en_all_4341'); //Link Table
    $session_en = en_auth();
    if(isset($session_en['en_id'])){
        $is_owner = ( $ln['ln_creator_entity_id']>0 && $session_en['en_id']==$ln['ln_creator_entity_id'] );
        $is_miner = filter_array($session_en['en__parents'], 'en_id', 1308);
    } else {
        $is_owner = false;
        $is_miner = false;
    }



    if(!isset($en_all_4593[$ln['ln_type_entity_id']])){
        //We've probably have not yet updated php cache, set error:
        $en_all_4593[$ln['ln_type_entity_id']] = array(
            'm_icon' => '<i class="fal fa-exclamation-triangle redalert"></i>',
            'm_name' => 'Link Type Not Synced in PHP Cache',
            'm_desc' => '',
            'm_parents' => array(),
        );
    }


    $hide_sensitive_details = (!$is_miner && !$is_owner && in_array($ln['ln_type_entity_id'] , $CI->config->item('en_ids_4755')) /* Link Type is locked */);



    //Display the item
    $ui = '<div class="list-group-item tr-box">';


    //What type of main content do we have, if any?
    $en_all_6186 = $CI->config->item('en_all_6186'); //Link Statuses


    //Link ID Row of data:
    $ui .= '<div style="padding: 0px 0 8px 12px; font-size: 0.9em;">';

    $ui .= '<span data-toggle="tooltip" data-placement="top" title="Link ID"><a href="/links?ln_id='.$ln['ln_id'].'"><i class="fas fa-link"></i> '.$ln['ln_id'].'</a></span>';

    $ui .= ' &nbsp;&nbsp;<span data-toggle="tooltip" data-placement="top" title="Link is '.$en_all_6186[$ln['ln_status_entity_id']]['m_desc'].'">'.$en_all_6186[$ln['ln_status_entity_id']]['m_icon'].' '.$en_all_6186[$ln['ln_status_entity_id']]['m_name'].'</span>';

    $ui .= ' &nbsp;&nbsp;<span data-toggle="tooltip" data-placement="top" title="Link Creation Timestamp: ' . $ln['ln_timestamp'] . ' PST"><i class="fas fa-clock"></i> ' . echo_time_difference(strtotime($ln['ln_timestamp'])) . ' ago</span>';

    $ui .= '</div>';


    //Miner and Link Type row:
    $ui .= '<div style="padding:0 10px 12px;">';

    if($hide_sensitive_details){

        //Hide Miner identity:
        $full_name = 'Hidden User';
        $ui .= '<span class="icon-main"><i class="fal fa-eye-slash"></i></span>';
        $ui .= '<b data-toggle="tooltip" data-placement="top" title="Details are kept private">&nbsp;Private Entity</b>';

    } else {

        //Show Miner:
        //Fetch Miner Entity:
        if($ln['ln_creator_entity_id'] > 0){

            $miner_ens = $CI->Entities_model->en_fetch(array(
                'en_id' => $ln['ln_creator_entity_id'],
            ));
            $full_name = $miner_ens[0]['en_name'];

            $ui .= '<span class="icon-main">'.echo_en_icon($miner_ens[0]).'</span>';
            $ui .= '<a href="/entities/'.$miner_ens[0]['en_id'].'" data-toggle="tooltip" data-placement="top" title="Link Creator"> <b>' . $full_name . '</b></a>';

        } else {

            $full_name = $CI->config->item('system_name');
            $ui .= '<span class="icon-main">'.$CI->config->item('system_icon').'</span>';
            $ui .= ' <b>' . $full_name . '</b>';

        }
    }

    //Link Type:
    $ui .= '<a href="/entities/'.$ln['ln_type_entity_id'].'" data-toggle="tooltip" data-placement="top" title="Link Type"><b style="padding-left:5px;">'. ( strlen($en_all_4593[$ln['ln_type_entity_id']]['m_icon']) > 0 ? '&nbsp;'.$en_all_4593[$ln['ln_type_entity_id']]['m_icon'] : '' ) .'&nbsp;'. $en_all_4593[$ln['ln_type_entity_id']]['m_name'] . '</b></a>';

    $ui .= '</div>';




    //Do we have a content to show?
    if(!$hide_sensitive_details && strlen($ln['ln_content']) > 0){
        $ui .= '<div class="e-msg">';
        $ui .= $CI->Communication_model->dispatch_message($ln['ln_content']);
        $ui .= '</div>';
    }


    //Link Connections
    $link_connections_clean_name = ''; //All link connections including child links
    $link_connections_count = 0; //Core link connections excluding child links (2x Intents, 2x Entities & 1x Parent Link)
    $auto_load_max_connections = 3; //If a link has this many of LESS connections, it would auto load them
    if(!$is_inner){
        //First count to see if this link has any connections:
        foreach ($CI->config->item('tr_object_links') as $ln_field => $obj_type) {
            if (!in_array($ln_field, array('ln_creator_entity_id', 'ln_type_entity_id')) && intval($ln[$ln_field]) > 0) {
                if($link_connections_count > 0){
                    $link_connections_clean_name .= ', ';
                }
                $link_connections_clean_name .= trim(ucwords(str_replace('_',' ', str_replace('ln_','', str_replace('_id','', $ln_field)))));
                $link_connections_count++;
            }
        }

        //Count child links:
        $child_links = $CI->Links_model->ln_fetch(array(
            'ln_parent_link_id' => $ln['ln_id'],
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


    //Link words
    $ui .= '<span class="link-connection-a"><span data-toggle="tooltip" data-placement="top" title="Number of words exchanged in this link" style="min-width:30px; display: inline-block;">'.$en_all_4341[10588]['m_icon']. ' '. number_format(abs($ln['ln_words']), (fmod($ln['ln_words'],1)==0 ? 0 : 2)) .' WORD'.strtoupper(echo__s($ln['ln_words'])).' '.( $ln['ln_words'] > 0 ? 'IN' : 'OUT' ).'</span></span> &nbsp;';


    if($ln['ln_order'] > 0){
        $ui .= '<span class="link-connection-a"><span data-toggle="tooltip" data-placement="top" title="Link ordered '.echo_ordinal_number($ln['ln_order']).'" style="min-width:30px; display: inline-block;"><i class="fas fa-sort"></i> '.echo_ordinal_number($ln['ln_order']).' Order</span></span> &nbsp;';
    }

    //Is this a miner? Show them metadata status:
    if(!$hide_sensitive_details && strlen($ln['ln_metadata']) > 0){
        $ui .= '<span class="link-connection-a"><a href="/links/link_json/' . $ln['ln_id'] . '" target="_blank" data-toggle="tooltip" data-placement="top" title="View link metadata (in new window)" style="min-width:26px; display: inline-block;"><i class="far fa-lambda"></i> Metadata</a></span> &nbsp;';
    }

    if(!$hide_sensitive_details && $ln['ln_external_id'] > 0){
        $ui .= '<span class="link-connection-a" data-toggle="tooltip" data-placement="top" title="Link External ID"><i class="fas fa-project-diagram"></i> '.$ln['ln_external_id'].'</span> &nbsp;';
    }

    //Give option to load if it has connections:
    if(!$is_inner && (strlen($link_connections_clean_name) > 0 || $load_main)){

        if(!$load_main || $child_links[0]['total_child_links'] > 0){
            $ui .= '<span class="link_connections_link_'.$ln['ln_id'].' link-connection-a"><a href="#linkconnection-'.$ln['ln_id'].'" onclick="load_link_connections('.$ln['ln_id'].','.$load_main.')"  data-toggle="tooltip" data-placement="top" title="Append Link Connections"><i class="fas fa-project-diagram"></i> '.$link_connections_clean_name.'</a></span>';
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

    return $ui;
}


function echo_actionplan_step_child($en_id, $in, $is_unlocked_step = false, $common_prefix = null){

    $CI =& get_instance();

    $completion_rate = $CI->Actionplan_model->completion_progress($en_id, $in);

    //Open list:
    $ui = '<a href="/actionplan/'.$in['in_id']. '" class="list-group-item">';

    $ui .= echo_in_outcome($in['in_outcome'], false, false, $common_prefix);

    if($is_unlocked_step){
        $en_all_4229 = $CI->config->item('en_all_4229'); //Link Metadata
        $ui .= '<span class="badge badge-primary" style="font-size: 0.8em; margin:-7px 0 -7px 5px;" data-toggle="tooltip" data-placement="right" title="'.$en_all_4229[6140]['m_name'].'">'.$en_all_4229[6140]['m_icon'].'</span>';
    }

    $ui .= '</a>';

    return $ui;
}

function echo_actionplan_step_parent($in)
{

    $CI =& get_instance();

    $ui = '<a href="/actionplan/' . $in['in_id'] . '" class="list-group-item">';

    $ui .= '<span class="pull-left">';
    $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
    $ui .= '</span>';

    $ui .= ' <span>' . echo_in_outcome($in['in_outcome']).'</span>';



    $ui .= '</a>';

    return $ui;
}


function echo_random_message($message_key, $return_all = false){

    /*
     *
     * To make Mench personal assistant feel more natural,
     * this function sends varying messages to communicate
     * specific things about Mench or about the user's
     * progress towards their Action Plan.
     *
     * */

    $rotation_index = array(
        'affirm_progress' => array(
            'Got it 👍',
            'Noted',
            'Ok noted',
            'Ok sweet',
            'Confirmed',
            '👌',
            '👍',
            null,
            null,
            null,
            null,
        ),
        'one_way_only' => array(
            'I am not designed to respond to custom messages. I can understand you only when you choose one of the options that I recommend to you.',
            'I cannot understand if you send me an out-of-context message. I would only understand if you choose one of the options that I recommend to you.',
            'I cannot respond to your custom messages and can only understand if you select one of the options that I recommend to you.',
        ),
        'ying_yang' => array(
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
            "Identity is fluid! People can change - be accepting.",
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
            'You can add a new intention to your Action Plan by sending me a message that starts with "I want to", for example: "I want to assess my back-end skills" or "I want to recruit top talent"',
        ),
        'goto_next' => array(
            'Say next to continue',
        ),

    );

    if(!array_key_exists($message_key, $rotation_index)){

        //Oooopsi, this should never happen:
        $CI =& get_instance();
        $CI->Links_model->ln_create(array(
            'ln_content' => 'echo_random_message() failed to locate message type ['.$message_key.']',
            'ln_type_entity_id' => 4246, //Platform Bug Reports
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
     * This also has an equal Javascript function echo_js_hours() which we
     * want to make sure has more/less the same logic...
     *
     * */

    if ($seconds < 1) {
        //Under 30 seconds would not round up to even 1 minute, so don't show:
        return 0;
    } elseif ($seconds < 60) {
        return $seconds . ($micro ? 's' : ' Seconds');
    } elseif ($seconds < 3600) {
        return round($seconds / 60) . ($micro ? 'm' : ' Minutes');
    } else {
        //Roundup the hours:
        $hours = round($seconds / 3600);
        return $hours . ($micro ? 'h' : ' Hour' . echo__s($hours));
    }
}

function echo_tree_html_body($section_en_id, $pitch_title, $pitch_body, $autoexpand){

    //The body of the tree expansion HTML panel:
    return '<div class="panel-group" id="open' . $section_en_id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $section_en_id . '">
                <h4 class="panel-title">
                    <a role="button" class="tag-manager-overview-link collapsed" section-en-id="'.$section_en_id.'" data-toggle="collapse" data-parent="#open' . $section_en_id . '" href="#collapse' . $section_en_id . '" aria-expanded="' . ($autoexpand ? 'true' : 'false') . '" aria-controls="collapse' . $section_en_id . '">' . $pitch_title . '
                    
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


    //TODO Consider enabling later?
    //return null; //Disable for now

    /*
     *
     * An intent function to display current users for this intent
     * and the percentage of them that have completed it...
     *
     * */


    //Count total users:
    $CI =& get_instance();
    $min_user_show = 101; //Needs this much or more users to display

    //Count users who have completed this intent:
    $enrolled_users_count = $CI->Links_model->ln_fetch(array(
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
        'ln_parent_intent_id' => $in['in_id'],
        'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

    if($enrolled_users_count[0]['totals'] < $min_user_show){
        //No one has added this intention to their Action Plan yet:
        return false;
    }

    //Count users who have completed the common base:
    $in_metadata = unserialize($in['in_metadata']);
    $array_flatten = array_flatten($in_metadata['in__metadata_common_steps']);
    $completed_users_count = $CI->Links_model->ln_fetch(array(
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
        'ln_parent_intent_id' => end($array_flatten),
        'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    if($completed_users_count[0]['totals'] > $enrolled_users_count[0]['totals']){
        $completed_users_count[0]['totals'] = $enrolled_users_count[0]['totals'];
    }
    $completion_percentage_raw = round($completed_users_count[0]['totals'] / $enrolled_users_count[0]['totals'] * 100);
    $completion_percentage_fancy = ( $completion_percentage_raw == 0 ? 'none' : ( $completion_percentage_raw==100 ? 'all' : $completion_percentage_raw.'%' ) );

    //As messenger default format and HTML extra notes:
    $pitch_body  = $completion_percentage_fancy .' of all '.echo_number($enrolled_users_count[0]['totals']).' users completed this intention.';

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
     * An intent function to display experts sources for
     * the entire intent tree stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if ((!isset($metadata['in__metadata_experts']) || count($metadata['in__metadata_experts']) < 1) && (!isset($metadata['in__metadata_sources']) || count($metadata['in__metadata_sources']) < 1)) {
        return false;
    }


    //Let's count to see how many content pieces we have references for this intent tree:
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

                $source_info .= ' <span class="show_type_' . $type_id . '"><a href="javascript:void(0);" class="tag-manager-expert-sources" source-type-en-id="'.$type_id.'" onclick="$(\'.show_type_' . $type_id . '\').toggle()" style="text-decoration:underline; display:inline-block;">' . $cat_contribution . '</a></span><span class="show_type_' . $type_id . '" style="display:none;">';

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
                    //$source_info .= '<a href="/entities/' . $en['en_id'] . '">';
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
                //$expert_info .= '<a href="/entities/' . $en['en_id'] . '">';
                $expert_info .= '<span>';
                $expert_info .= $en['en_name'];
                $expert_info .= '</span>';
                //$expert_info .= '</a>';

                if (($count + 1) == $visible_html && ($expert_count - $visible_html) > 0) {
                    $expert_info .= '<span class="show_more_' . $in['in_id'] . '"> & <a href="javascript:void(0);" class="tag-manager-expert-full-list" onclick="$(\'.show_more_' . $in['in_id'] . '\').toggle()" style="text-decoration:underline;">' . ($expert_count - $visible_html) . ' more</a>.</span><span class="show_more_' . $in['in_id'] . '" style="display:none;">';
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
    $pitch_body = 'Action Plan references ';
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

    //Is this a range or a single step value?
    if($metadata['in__metadata_min_steps'] != $metadata['in__metadata_max_steps']){

        //It's a range:
        return 'Between '.$metadata['in__metadata_min_steps'].' - '.$metadata['in__metadata_max_steps'].' Steps' . ( $educational_mode ? ' (depending on your answers to my questions)' : '' );

    } else {

        //A single step value, nothing to educate about here:
        return $metadata['in__metadata_max_steps']. ' Step'.echo__s($metadata['in__metadata_max_steps']);

    }
}

function echo_tree_steps($in, $push_message = 0, $autoexpand = false)
{

    /*
     *
     * An intent function to display the total tree intents
     * stored in the metadata field.
     *
     * */

    if (!echo_step_range($in)) {
        //No steps, return null:
        return false;
    }

    //Fetch on-start intents:
    $CI =& get_instance();

    $metadata = unserialize($in['in_metadata']);

    //Now do measurements:
    $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );
    $pitch_body = 'I estimate it would take you ' . strtolower(echo_step_range($in, true)).( $has_time_estimate ? ' in ' . strtolower(echo_time_range($in)) : '' ).' to '.echo_in_outcome($in['in_outcome'], false).'.';


    if ($push_message) {

        return '🚩 ' . $pitch_body. "\n\n";

    } else {

        //HTML format
        $pitch_title = '<span class="icon-block"><i class="fas fa-flag"></i></span>&nbsp;'.$metadata['in__metadata_max_steps'].' step'.echo__s($metadata['in__metadata_max_steps']).( $has_time_estimate ? ' in ' . strtolower(echo_time_range($in)) : ' to complete' );

        //If NOT private, Expand body to include Action Plan overview:
        if(!in_array($in['in_subtype_entity_id'], $CI->config->item('en_ids_7366'))){
            $pitch_body .= '<div class="inner_actionplan">';
            $pitch_body .= echo_tree_actionplan($in, false);
            $pitch_body .= '</div>';
        }

        return echo_tree_html_body(7613, $pitch_title, $pitch_body, $autoexpand);

    }
}

function echo_tree_actionplan($in, $autoexpand){


    $CI =& get_instance();

    //Is this private?
    if(in_array($in['in_subtype_entity_id'], $CI->config->item('en_ids_7366'))){
        return null;
    }

    //Fetch actual children:
    $in__children = $CI->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'in_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        'ln_type_entity_id' => 4228, //Intent Link Regular Step
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

    if(count($in__children) < 1){
        return null;
    }


    $common_prefix = common_prefix($in__children);
    $return_html = '';
    $return_html .= '<div class="list-group grey_list actionplan_list maxout public_ap">';

    foreach ($in__children as $in_level2_counter => $in_level2) {

        if(!in_is_clean_outcome($in_level2) || in_array($in_level2['in_subtype_entity_id'], $CI->config->item('en_ids_7309'))){
            continue;
        }

        //Is this private?
        $is_private = (in_array($in_level2['in_subtype_entity_id'], $CI->config->item('en_ids_7366')));
        if($is_private){

            $has_level2_content = false;

        } else {

            //Level 3 intents:
            $in_level2_children = $CI->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'in_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
                'ln_type_entity_id' => 4228, //Intent Link Regular Step
                'ln_parent_intent_id' => $in_level2['in_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

            //Fetch messages:
            $in_level2_messages = $CI->Links_model->ln_fetch(array(
                'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                'ln_type_entity_id' => 4231, //Intent Note Messages
                'ln_child_intent_id' => $in_level2['in_id'],
            ), array(), 0, 0, array('ln_order' => 'ASC'));

            //Determine intent type/settings:
            $has_level2_content = (count($in_level2_children)>0 || count($in_level2_messages)>0);

        }



        //Level 2 title:
        $return_html .= '<div class="panel-group" id="open' . $in_level2_counter . '" role="tablist" aria-multiselectable="true">';
        $return_html .= '<div class="panel panel-primary">';
        $return_html .= '<div class="panel-heading" role="tab" id="heading' . $in_level2_counter . '">';


        $return_html .= '<h4 class="panel-title">';

        if($has_level2_content){
            $return_html .= '<a class="tag-manager-steps-review" intent-id="'.$in_level2['in_id'].'" role="button" data-toggle="collapse" data-parent="#open' . $in_level2_counter . '" href="#collapse' . $in_level2_counter . '" aria-expanded="' . ($autoexpand ? 'true' : 'false') . '" aria-controls="collapse' . $in_level2_counter . '">';
            $return_html .= '<span class="icon-block-lg"><i class="fas fa-plus-circle"></i></span>';
        } else {
            $return_html .= '<span class="empty-block">';
            $return_html .= '<span class="icon-block-lg"><i class="fal fa-check-circle"></i></span>';
        }


        $return_html .= '<span id="title-' . $in_level2['in_id'] . '">' . echo_in_outcome($in_level2['in_outcome'], false, false, $common_prefix) . '</span>';


        if($has_level2_content){
            $return_html .= '</a>';
        } else {
            $return_html .= '</span>';
        }

        $return_html .= '</h4>';
        $return_html .= '</div>';



        //Messages:
        if($has_level2_content){

            //Level 2 body:
            $return_html .= '<div id="collapse' . $in_level2_counter . '" class="panel-collapse collapse ' . ($autoexpand ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '">';
            $return_html .= '<div class="panel-body">';


            foreach ($in_level2_messages as $ln) {
                $return_html .= $CI->Communication_model->dispatch_message($ln['ln_content']);
            }

            if (count($in_level2_children) > 0) {

                //See if they have a common base:
                $common_prefix_granchild = common_prefix($in_level2_children);

                //List level 3:
                $return_html .= '<ul class="action-plan-sub-list">';
                foreach ($in_level2_children as $in_level3_counter => $in_level3) {


                    if(!in_is_clean_outcome($in_level3) || in_array($in_level2['in_subtype_entity_id'], $CI->config->item('en_ids_7309'))){
                        continue;
                    }

                    //Is this private?
                    $is_private = (in_array($in_level3['in_subtype_entity_id'], $CI->config->item('en_ids_7366')));
                    if($is_private){

                        $in_level3_messages = array();

                    } else {

                        //Fetch messages:
                        $in_level3_messages = $CI->Links_model->ln_fetch(array(
                            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
                            'ln_type_entity_id' => 4231, //Intent Note Messages
                            'ln_child_intent_id' => $in_level3['in_id'],
                        ), array(), 0, 0, array('ln_order' => 'ASC'));

                    }


                    $return_html .= '<li>';


                    if(count($in_level3_messages) > 0){
                        $return_html .= '<a role="button" data-toggle="collapse" class="second-level-link tag-manager-steps-review" intent-id="'.$in_level3['in_id'].'" data-parent="#open' . $in_level2_counter . '-'.$in_level3_counter.'" href="#collapse' . $in_level2_counter . '-'.$in_level3_counter.'" aria-expanded="' . ($autoexpand ? 'true' : 'false') . '" aria-controls="collapse' . $in_level2_counter . '">';
                        $return_html .= '<span class="icon-block"><i class="fas fa-plus-circle"></i></span>';
                    } else {
                        $return_html .= '<span class="icon-block"><i class="fal fa-check-circle"></i></span>';
                    }

                    $return_html .= echo_in_outcome($in_level3['in_outcome'], false, false, $common_prefix_granchild);

                    if(count($in_level3_messages) > 0){
                        $return_html .= '</a>';
                    }

                    $return_html .= '</li>';


                    if(count($in_level3_messages) > 0){
                        //Level 2 body:
                        $return_html .= '<div id="collapse' . $in_level2_counter . '-'.$in_level3_counter.'" class="panel-collapse collapse ' . ($autoexpand ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '-'.$in_level3_counter.'">';
                        $return_html .= '<div class="panel-body second-level-body">';
                        foreach ($in_level3_messages as $ln) {
                            $return_html .= $CI->Communication_model->dispatch_message($ln['ln_content']);
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

function echo_en_messages($ln){

    $CI =& get_instance();
    $session_en = $CI->session->userdata('user');
    $en_all_4737 = $CI->config->item('en_all_4737'); // Intent Statuses
    $en_all_4485 = $CI->config->item('en_all_4485');
    $en_all_6186 = $CI->config->item('en_all_6186'); //Link Statuses

    $ui = '<div class="entities-msg">';

    $ui .= '<div>';

    //Editing menu:
    $ui .= '<ul class="msg-nav">';


    //Referenced Intent:
    $en_all_7585 = $CI->config->item('en_all_7585'); // Intent Types
    $ui .= '<li><a class="btn btn-primary button-max" style="border:2px solid #ffe027 !important;" href="/intents/' . $ln['ln_child_intent_id'] . '" target="_parent" title="Message Intent: '.$ln['in_outcome'].'" data-toggle="tooltip" data-placement="top">'.$en_all_4737[$ln['in_status_entity_id']]['m_icon'].'&nbsp; '.$en_all_7585[$ln['in_subtype_entity_id']]['m_icon'].' '.$ln['in_outcome'].'</a></li>';

    //Links:
    $count_msg_trs = $CI->Links_model->ln_fetch(array(
        '( ln_id = ' . $ln['ln_id'] . ' OR ln_parent_link_id = ' . $ln['ln_id'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    $ui .= '<li class="' . advance_mode() . '"><a class="btn btn-primary" style="border:2px solid #ffe027 !important;" href="/links?ln_id=' . $ln['ln_id'] . '" target="_parent"><i class="fas fa-link"></i> '.echo_number($count_msg_trs[0]['totals']).'</a></li>';

    //Intent Note Type:
    $ui .= '<li class="' . advance_mode() . '" style="margin: 0 3px 0 0;"><span title="'.$en_all_4485[$ln['ln_type_entity_id']]['m_name'].': '.$en_all_4485[$ln['ln_type_entity_id']]['m_desc'].'" data-toggle="tooltip" data-placement="top">'.$en_all_4485[$ln['ln_type_entity_id']]['m_icon'].'</span></li>';

    //Link Status:
    $ui .= '<li class="' . advance_mode() . '" style="margin: 0 3px 0 0;"><span title="'.$en_all_6186[$ln['ln_status_entity_id']]['m_name'].': '.$en_all_6186[$ln['ln_status_entity_id']]['m_desc'].'" data-toggle="tooltip" data-placement="top">'.$en_all_6186[$ln['ln_status_entity_id']]['m_icon'].'</span></li>';

    //Order:
    $ui .= '<li class="' . advance_mode() . '" style="margin: 0 3px 0 0;"><span title="Order messages" data-toggle="tooltip" data-placement="top"><i class="fas fa-sort"></i>' . echo_ordinal_number($ln['ln_order']) . '</span></li>';

    $ui .= '<li style="clear: both;">&nbsp;</li>';

    $ui .= '</ul>';

    //Show message only if its not a plain reference and includes additional text/info:
    if($ln['ln_content'] != '@'.$ln['ln_parent_entity_id']){
        $ui .= '<div style="margin-top: 15px;">';
        $ui .= $CI->Communication_model->dispatch_message($ln['ln_content'], $session_en, false);
        $ui .= '</div>';
    }


    $ui .= '</div>';

    $ui .= '</div>';

    return $ui;
}

function echo_time_range($in, $micro = false, $hide_zero = false)
{

    //Make sure we have metadata passed on via $in as sometimes it might miss it (Like when passed on via Algolia results...)
    if (!isset($in['in_metadata'])) {
        //We don't have it, so fetch it:
        $CI =& get_instance();
        $ins = $CI->Intents_model->in_fetch(array(
            'in_id' => $in['in_id'], //We should always have Intent ID
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
        $ui_time .= ' - ';
        $ui_time .= $the_max;
    }
    $ui_time .= ($is_minutes ? ($micro ? 'm' : ' Minute'.echo__s($max_minutes)) : ($micro ? 'h' : ' Hour'.echo__s($max_hours)));

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
     * UI for Platform Cache entities
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
        return '<span class="status-label" ' . ( $micro_status && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="' . $data_placement . '" title="' . ($micro_status ? $cache_en['m_name'] : '') . (strlen($cache_en['m_desc']) > 0 ? ($micro_status ? ': ' : '') . $cache_en['m_desc'] : '') . '" style="border-bottom:1px dotted #444; padding-bottom:1px; line-height:140%;"' : 'style="cursor:pointer;"') . '>' . $cache_en['m_icon'] . ' ' . ($micro_status ? '' : $cache_en['m_name']) . '</span>';
    }
}


function echo_in_recommend($in, $common_prefix = null, $hide_class = null, $referrer_en_id = 0)
{

    //See if user is logged-in:
    $CI =& get_instance();
    $session_en = en_auth();
    $is_starting = ( in_array($in['in_scope_entity_id'], $CI->config->item('en_ids_7582')) /* Intent Scopes Get Started */);
    $en_all_7369 = $CI->config->item('en_all_7369');
    $already_in_actionplan = (isset($session_en['en_id']) && count($CI->Links_model->ln_fetch(array(
            'ln_creator_entity_id' => $session_en['en_id'],
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_7347')) . ')' => null, //Action Plan Intention Set
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7364')) . ')' => null, //incomplete intentions
            'ln_parent_intent_id' => $in['in_id'],
        ))) > 0);

    $ui = '<a href="' . ( $already_in_actionplan ? '/actionplan/'.$in['in_id'] : ( $referrer_en_id > 0 ? '/'.$referrer_en_id.'_'.$in['in_id'] : '/'.$in['in_id'] )) . '" class="list-group-item '.$hide_class .' '.( $is_starting ? 'tag-manager-intent-passthrough' : 'tag-manager-intent-recommend' ).'">';

    $ui .= '<span class="pull-right">';
    $ui .= '<span class="badge badge-primary fr-bgd" style="margin-top: -4px;">'.( $already_in_actionplan ? $en_all_7369[6138]['m_icon'] : '<i class="fas fa-angle-right"></i>' ).'</span>';
    $ui .= '</span>';

    $ui .= '<span style="color:#222; font-weight:500; font-size:1.2em;">'.echo_in_outcome($in['in_outcome'], false, false, $common_prefix).'</span>';

    $ui .= '</a>';
    return $ui;
}

function echo_in_answer_scores($starting_in, $depth_levels, $original_depth_levels, $parent_in_subtype_entity_id){

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
    $en_all_4737 = $CI->config->item('en_all_4737'); // Intent Statuses
    $en_all_7585 = $CI->config->item('en_all_7585'); // Intent Types

    $ui = null;
    foreach($CI->Links_model->ln_fetch(array(
        'ln_parent_intent_id' => $starting_in,
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
        'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        'in_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $in_ln){

        //Prep Metadata:
        $metadata = unserialize($in_ln['ln_metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $in_ln['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Intent Link Type: '.$en_all_4486[$in_ln['ln_type_entity_id']]['m_name'].'">'. $en_all_4486[$in_ln['ln_type_entity_id']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Intent Link Status: '.$en_all_6186[$in_ln['ln_status_entity_id']]['m_name'].'">'. $en_all_6186[$in_ln['ln_status_entity_id']]['m_icon'] . '</span>';

        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Intent Type: '.$en_all_7585[$in_ln['in_subtype_entity_id']]['m_name'].'">'. $en_all_7585[$in_ln['in_subtype_entity_id']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Intent Status: '.$en_all_4737[$in_ln['in_status_entity_id']]['m_name'].'">'. $en_all_4737[$in_ln['in_status_entity_id']]['m_icon']. '</span>';
        $ui .= '<a href="/miner_app/admin_tools/assessment_marks_birds_eye?starting_in='.$in_ln['in_id'].'&depth_levels='.$original_depth_levels.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this intent"><u>' .   echo_in_outcome($in_ln['in_outcome'], false, true) . '</u></a>';

        $ui .= ' [<span data-toggle="tooltip" data-placement="top" title="Completion Marks">'.( ($in_ln['ln_type_entity_id'] == 4228 && in_array($parent_in_subtype_entity_id , $CI->config->item('en_ids_6193') /* OR Intents */ )) || ($in_ln['ln_type_entity_id'] == 4229) ? echo_in_marks($in_ln) : '' ).'</span>]';

        if(count($messages) > 0){
            $ui .= ' <a href="javascript:void(0);" onclick="$(\'.messages-'.$in_ln['in_id'].'\').toggleClass(\'hidden\');"><i class="fas fa-comment"></i><b>' .  count($messages) . '</b></a>';
        }
        $ui .= '</div>';

        //Display Messages:
        $ui .= '<div class="messages-'.$in_ln['in_id'].' hidden">';
        foreach ($messages as $msg) {
            $ui .= '<div class="tip_bubble" style="font-size:1em !important;">';
            $ui .= $CI->Communication_model->dispatch_message($msg['ln_content']);
            $ui .= '</div>';
        }
        $ui .= '</div>';

        //Go Recursively down:
        $ui .=  echo_in_answer_scores($in_ln['in_id'], $depth_levels, $original_depth_levels, $in_ln['in_subtype_entity_id']);

    }

    //Return the wrapped UI if existed:
    return ($ui ? '<div class="inline-box">' . $ui . '</div>' : false);
}

function echo_radio_entities($parent_en_id, $child_en_id, $enable_mulitiselect){
    /*
     * Print UI for
     * */

    $show_max = 10; //This is visible and the rest need to be loaded
    $CI =& get_instance();

    $ui = '<div class="list-group radio-'.$parent_en_id.'">';

    //Fetch all children:
    foreach($CI->Links_model->ln_fetch(array(
        'ln_parent_entity_id' => $parent_en_id,
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
        'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'en_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7357')) . ')' => null, //Entity Statuses Public
    ), array('en_child'), 0, 0, array('ln_order' => 'ASC', 'en_name' => 'ASC')) as $count => $item){

        //Count total children unless its for subscription levels:
        if($parent_en_id!=4454){
            $user_count = $CI->Links_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_parent_entity_id' => $item['en_id'],
                'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        }

        //Echo box:
        $ui .= '<a href="javascript:void(0);" onclick="radio_update('.$parent_en_id.','.$item['en_id'].','.$enable_mulitiselect.')" class="list-group-item item-'.$item['en_id'].' '.( $count>=$show_max ? 'extra-items-'.$parent_en_id.' hidden ' : '' ).( count($CI->Links_model->ln_fetch(array(
                'ln_parent_entity_id' => $item['en_id'],
                'ln_child_entity_id' => $child_en_id,
                'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )))>0 ? ' active ' : '' ). '">'.( strlen($item['en_icon'])>0 ? '<span class="left-icon">'.$item['en_icon'].'</span>' : '' ).$item['en_name'].'<span class="change-results"></span>'.( $parent_en_id!=4454 ? '<span class="pull-right">'.echo_number($user_count[0]['totals']).' <i class="fal fa-users"></i></span>' : '' ).'</a>';
    }

    //Did we have too many items?
    if($count>=$show_max){
        //Show "Show more" button
        $ui .= '<a href="javascript:void(0);" class="list-group-item extra-items-'.$parent_en_id.'" onclick="$(\'.extra-items-'.$parent_en_id.'\').toggleClass(\'hidden\')"><i class="fas fa-plus-circle"></i> Show '.($count-$show_max).' more</a>';
    }

    $ui .= '</div>';

    return $ui;
}


function echo_en_stats_overview($cached_list, $report_name){

    $CI =& get_instance();
    $inner_ui = '';
    $total_count = 0;
    foreach($cached_list as $group_en_id=>$people_group) {

        //Do a child count:
        $child_links = $CI->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $group_en_id,
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'en_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

        $inner_ui .= '<tr>';
        $inner_ui .= '<td style="text-align: left;"><span class="icon-block">' . $people_group['m_icon'] . '</span><a href="/entities/'.$group_en_id.'">' . $people_group['m_name'] . '</a></td>';
        $inner_ui .= '<td style="text-align: right;"><a href="/links?ln_status_entity_id='.join(',', $CI->config->item('en_ids_7360')) /* Link Statuses Active */.'&ln_type_entity_id='.join(',', $CI->config->item('en_ids_4592')).'&ln_parent_entity_id=' . $group_en_id . '">' . number_format($child_links[0]['en__child_count'], 0) . '</a></td>';
        $inner_ui .= '</tr>';

        $total_count += $child_links[0]['en__child_count'];
    }


    $ui = '<table class="table table-condensed table-striped stats-table">';

    $ui .= '<tr class="panel-title down-border">';
    $ui .= '<td style="text-align: left;" colspan="2">'.number_format($total_count,0).' '.$report_name.'</td>';
    $ui .= '</tr>';

    $ui .= $inner_ui;
    $ui .= '</table>';

    return $ui;

}

function echo_in_marks($in_ln){

    //Validate core inputs:
    if(!isset($in_ln['ln_metadata']) || !isset($in_ln['ln_type_entity_id'])){
        return false;
    }

    //prep metadata:
    $ln_metadata = unserialize($in_ln['ln_metadata']);

    //Return mark:
    return ( $in_ln['ln_type_entity_id'] == 4228 ? ( !isset($ln_metadata['tr__assessment_points']) || $ln_metadata['tr__assessment_points'] == 0 ? '' : '<span style="'.( $ln_metadata['tr__assessment_points']>0 ? 'font-weight:bold;' : ( $ln_metadata['tr__assessment_points'] < 0 ? 'font-weight:bold;' : '' )).'">' . ( $ln_metadata['tr__assessment_points'] > 0 ? '+' : '' ) . $ln_metadata['tr__assessment_points'].'</span>' ) : $ln_metadata['tr__conditional_score_min'] . ( $ln_metadata['tr__conditional_score_min']==$ln_metadata['tr__conditional_score_max'] ? '' : '-'.$ln_metadata['tr__conditional_score_max'] ).'%' );

}

function in_authors_class($in_id){
    //Allow to edit IF they are part of the intent Trainers:
    $css_author = '';
    $CI =& get_instance();
    foreach($CI->Links_model->ln_fetch(array(
        'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        'ln_type_entity_id' => 10573, //Intent Note Trainer
        'ln_child_intent_id' => $in_id,
        'ln_parent_entity_id >' => 0, //Where the author entity is stored
    ), array(), 0) as $author){
        //Append CSS class:
        $css_author .= ' author_class_'.$author['ln_parent_entity_id'].' ';
    }
    return $css_author;
}

function echo_in_setting($in_setting_en_id, $in_field_name, $addup_total_count){

    $CI =& get_instance();
    $en_all_7302 = $CI->config->item('en_all_7302'); //Intent Stats

    $ui =  '<table class="table table-condensed table-striped stats-table mini-stats-table ">';

    $ui .= '<tr class="panel-title down-border">';
    $ui .= '<td style="text-align: left;" colspan="2">'.number_format(count($CI->config->item('en_all_'.$in_setting_en_id)),0).' '.$en_all_7302[$in_setting_en_id]['m_name'].echo__s(count($CI->config->item('en_all_'.$in_setting_en_id))).'</td>';
    $ui .= '</tr>';

    foreach ($CI->config->item('en_all_'.$in_setting_en_id) as $type_en_id => $in_type) {

        //Count this sub-type from the database:
        $in_count = $CI->Intents_model->in_fetch(array(
            $in_field_name => $type_en_id,
            'in_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Intent Statuses Public
        ), array(), 0, 0, array(), 'COUNT(in_id) as total_public_intents');

        //$ui .= this as the main title:
        $ui .= '<tr>';
        $ui .= '<td style="text-align: left;"><span class="icon-block">'.$in_type['m_icon'].'</span><a href="/entities/'.$type_en_id.'">'.$in_type['m_name'].'</a></td>';
        $ui .= '<td style="text-align: right;"><a href="/links?ln_type_entity_id=4250&in_status_entity_id=' . join(',', $CI->config->item('en_ids_7356')) . '&'.$in_field_name.'='.$type_en_id.'" data-toggle="tooltip" data-placement="top" title="'.number_format($in_count[0]['total_public_intents'], 0).' Intent'.echo__s($in_count[0]['total_public_intents']).'">'.number_format($in_count[0]['total_public_intents']/$addup_total_count*100, 1).'%</a></td>';
        $ui .= '</tr>';

    }

    $ui .= '</table>';

    return $ui;
}


function echo_2level_stats($stat_name, $stats_en_id, $mother_en_id, $link_types_counts, $addup_total_count, $link_field, $display_field){

    $CI =& get_instance();

    echo '<table class="table table-condensed table-striped stats-table mini-stats-table">';

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;">'.number_format(count($CI->config->item('en_all_'.$stats_en_id)), 0).' '.$stat_name.'</td>';
    if($display_field=='total_words'){
        echo '<td style="text-align: right;">Words</td>';
    } else {
        echo '<td>&nbsp;</td>';
    }
    echo '</tr>';

    $all_shown = array();
    foreach ($CI->config->item('en_all_'.$stats_en_id) as $en_id => $m) {
        echo_2level_entities($m, $CI->config->item('en_all_'.$en_id), $link_types_counts, $all_shown, $link_field, 'en_all_'.$mother_en_id, $addup_total_count, $display_field);
        $all_shown = array_merge($all_shown, $CI->config->item('en_ids_'.$en_id));
    }

    //Turn into array:
    $remaining_child = array();
    foreach($CI->config->item('en_all_'.$mother_en_id) as $en_id => $m){
        $remaining_child[$en_id] = $m;
    }

    //Display RemainingIF ANY:
    echo_2level_entities(array(
        'm_icon' => '<i class="fas fa-plus-circle"></i>',
        'm_name' => 'Others',
        'm_desc' => 'What is left',
    ), $remaining_child, $link_types_counts, $all_shown, $link_field, 'en_all_'.$mother_en_id, $addup_total_count, $display_field);

    echo '</table>';

}


function echo_2level_entities($main_obj, $all_link_types, $link_types_counts, $all_shown, $link_field, $details_en, $addup_total_count, $display_field){

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

        $ln = filter_array($link_types_counts, 'en_id', $en_id);
        $show_in_advance_only = ($display_field=='total_words' && (in_array($en_id, $CI->config->item('en_ids_10596')) /* Nod */ || abs($ln['total_words']) < 100 ));

        if( !$ln['total_count'] ){
            continue;
        }

        array_push($all_link_type_ids, $en_id);

        //Addup counter:
        if($display_field=='total_count'){
            $total_sum += $ln['total_count'];
        } elseif($display_field=='total_words'){
            $total_sum += abs($ln['total_words']);
        }

        //Subrow UI:
        $this_rows =  '<tr class="hidden ' . $identifier . '">';


        if(!isset($en_all_detail[$en_id])){

            $this_rows .= '<td style="text-align: left; padding-left:30px;" colspan="2">MISSING @'.$en_id.' as Link Type</td>';

        } else {

            $this_rows .= '<td style="text-align: left;" class="'.( $show_in_advance_only ? advance_mode() : '' ).'">';
            $this_rows .= '<span class="icon-block" style="margin-left:8px;">'.$m['m_icon'].'</span>';
            $this_rows .= '<a href="/entities/'.$en_id.'">'.$m['m_name'].'</a>';
            $this_rows .= '</td>';


            $this_rows .= '<td style="text-align: right;" class="'.( $show_in_advance_only ? advance_mode() : '' ).'">';
            if($display_field=='total_count'){

                $this_rows .= '<a href="/links?ln_status_entity_id='.join(',', $CI->config->item('en_ids_7360')) /* Link Statuses Active */.'&'.$link_field.'=' . $en_id . '" data-toggle="tooltip" data-placement="top" title="'.number_format($ln['total_count'], 0).' Intent'.echo__s($ln['total_count']).'">'.number_format($ln['total_count']/$addup_total_count*100, 1) . '%</a>';

            } elseif($display_field=='total_words'){

                $this_rows .= '<a href="/links?ln_status_entity_id='.join(',', $CI->config->item('en_ids_7360')) /* Link Statuses Active */.'&'.$link_field.'=' . $en_id . '" data-toggle="tooltip" data-placement="top" title="'.number_format($ln['total_words'], 0).' Word'.echo__s($ln['total_words']).'">'.number_format($ln['total_words'], 0) . '</a>';

            }
            $this_rows .= '</td>';

        }


        //sub-row count:
        $this_rows .= '</tr>';
        
        if($show_in_advance_only){
            $sub_advance_rows .= $this_rows;
        } else {
            $sub_rows .= $this_rows;
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

        echo '<a href="/links?ln_status_entity_id='.join(',', $CI->config->item('en_ids_7360')) /* Link Statuses Active */.'&'.$link_field.'=' . join(',' , $all_link_type_ids) . '" data-toggle="tooltip" data-placement="top" title="'.number_format($total_sum, 0).' Intent'.echo__s($total_sum).'">'.number_format($total_sum/$addup_total_count*100, 1).'%</a>';

    } elseif($display_field=='total_words'){

        echo '<a href="/links?ln_status_entity_id='.join(',', $CI->config->item('en_ids_7360')) /* Link Statuses Active */.'&'.$link_field.'=' . join(',' , $all_link_type_ids) . '" data-toggle="tooltip" data-placement="top" title="'.number_format($total_sum, 0).' Word'.echo__s($total_sum).'">'.number_format($total_sum, 0).'</a>';

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



function echo_in($in, $level, $in_linked_id = 0, $is_parent = false)
{

    /*
     *
     * The Main function to display intents across three levels:
     *
     * - Level 1: Where the user is focused on
     * - Level 2: The Children of the focused intent
     * - Level 3: The Grandchildren of the focused intent
     *
     * */

    $CI =& get_instance();
    $session_en = $CI->session->userdata('user');
    $en_all_4737 = $CI->config->item('en_all_4737'); // Intent Statuses
    $en_all_7585 = $CI->config->item('en_all_7585'); // Intent Types
    $en_all_7596 = $CI->config->item('en_all_7596'); // Intent Scope
    $en_all_6186 = $CI->config->item('en_all_6186'); // Link Status
    $is_child_focused = ($level == 3 && $is_parent && $CI->uri->segment(2)==$in['in_id']);
    $in_filters = in_get_filters(); //If we have any intent filters applied

    //Prepare Intent Metadata:
    $in_metadata = unserialize($in['in_metadata']);


    if ($level == 2 && !isset($in['in__grandchildren'])) {
        //Fetch children if parent, since there are no children fetched:
        $in['in__grandchildren'] = $CI->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_parent_intent_id' => $in['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered
    }


    if ($level <= 1) {

        //No Link for level 1 intent:
        $ln_id = 0;
        $ln_metadata = array();

        $ui = '<div class="list-group-item top_intent object_highlight highlight_in_'.$in['in_id'].'">';

    } else {

        //Prep link metadata to be analyzed later:
        $ln_id = $in['ln_id'];
        $ln_metadata = unserialize($in['ln_metadata']);

        $ui = '<div in-link-id="' . $ln_id . '" in-tr-type="' . $in['ln_type_entity_id'] . '" intent-id="' . $in['in_id'] . '" parent-intent-id="' . $in_linked_id . '" intent-level="' . $level . '" class="list-group-item object_highlight highlight_in_'.$in['in_id'].' ' . ( !in_array($in['ln_status_entity_id'], $CI->config->item('en_ids_7359')) ? advance_mode() : '' ) . ($level == 3 || ($level == 2 && !$is_parent) ? ' enable-sorting ' : '') . ($level == 3 ? 'is_level3_sortable' : 'is_level2_sortable level2_in')  . ' intent_line_' . $in['in_id'] . ( $is_parent && $level!=3 ? ' parent-intent ' : '' ) . ' in__tr_'.$ln_id.'">';

    }

    /*
     *
     * Start Left Side
     *
     * */

    $ui .= '<span style="display:inline-block; margin-top:0px; padding-bottom: 5px;">';






    //Hidden fields to store dynamic value for on-demand JS modifications:
    //Show Link Status if Available:
    if ($level == 1) {

        //Show Blank box:
        $ui .= '<span class="double-icon '.advance_mode().'" style="margin: 0 2px 0 -4px;"><span class="icon-main">&nbsp;</span><span class="icon-top-right">&nbsp;</span></span>';

    } elseif($level > 1) {

        //Fetch Intent Link Connectors:
        $en_all_4486 = $CI->config->item('en_all_4486');

        //Show Link link icons:
        $ui .= '<span class="double-icon '.advance_mode().'" style="margin:0 3px 0 -3px;">';

        //Show larger icon for link type (auto detected based on link content):
        $ui .= '<span class="icon-main ln_type_' . $ln_id . '"><span data-toggle="tooltip" data-placement="right" title="' . $en_all_4486[$in['ln_type_entity_id']]['m_name'] . ': ' . $en_all_4486[$in['ln_type_entity_id']]['m_desc'] . ' @'.$in['ln_type_entity_id'].'">' . $en_all_4486[$in['ln_type_entity_id']]['m_icon'] . '</span></span>';

        //Show smaller link status icon:
        $ui .= '<span class="icon-top-right ln_status_entity_id_' . $ln_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6186[$in['ln_status_entity_id']]['m_name'].' @'.$in['ln_status_entity_id'].': '.$en_all_6186[$in['ln_status_entity_id']]['m_desc'].'">' . $en_all_6186[$in['ln_status_entity_id']]['m_icon'] . '</span></span>';

        //Show Completion Marks based on Intent Link Type:
        $ui .= '<span class="icon-3rd in_assessment_' . $ln_id . '" data-toggle="tooltip" data-placement="right" title="Completion Marks">'. echo_in_marks($in) .'</span>';

        $ui .= '</span>';

    }



    //Always Show Intent Icon
    $ui .= '<span class="double-icon" style="margin-right:9px;">';


    //Show larger intent type icon:
    $ui .= '<span class="icon-main in_parent_type_' . $in['in_id'] . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_7585[$in['in_subtype_entity_id']]['m_name'].': '.$en_all_7585[$in['in_subtype_entity_id']]['m_desc'].'">' . $en_all_7585[$in['in_subtype_entity_id']]['m_icon'] . '</span></span>';

    //Show smaller intent status:
    $ui .= '<span class="icon-top-right in_status_entity_id_' . $in['in_id'] . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$in['in_status_entity_id']]['m_name'].': '.$en_all_4737[$in['in_status_entity_id']]['m_desc'].'">' . $en_all_4737[$in['in_status_entity_id']]['m_icon'] . '</span></span>';


    $ui .= '<span class="icon-top-left in_scope_entity_id_' . $in['in_id'] . '" data-toggle="tooltip" data-placement="right" title="'.$en_all_7596[$in['in_scope_entity_id']]['m_name'].': '.$en_all_7596[$in['in_scope_entity_id']]['m_desc'].'">' . $en_all_7596[$in['in_scope_entity_id']]['m_icon'] . '</span>';



    $ui .= '</span>';




    //Intent UI based on level:
    if ($level <= 1) {

        $ui .= '<span><b id="in_level1_outcome" style="font-size: 1.4em;">';
        $ui .= '<span class="in_outcome_' . $in['in_id'] . '">' . echo_in_outcome($in['in_outcome'], false, true) . '</span>';
        $ui .= '</b></span>';

    } elseif ($level == 2) {

        $ui .= '<span id="title_' . $ln_id . '" style="font-weight: 500;" class="cdr_crnt click_expand tree_title in_outcome_' . $in['in_id'] . '">' . echo_in_outcome($in['in_outcome'], false, true) . '</span>';

    } elseif ($level == 3) {

        $ui .= '<span id="title_' . $ln_id . '" class="tree_title in_outcome_' . $in['in_id'] . '">' .echo_in_outcome($in['in_outcome'], false, true) . '</span> ';

        //Is this the focused item in the parent sibling dropdown?
        if($is_child_focused){
            $ui .= '<span class="badge badge-primary" style="font-size: 0.8em;"><i class="fas fa-map-marker-alt"></i> You\'re Here</span> ';
        }

    }


    $ui .= '</span>';





    /*
     *
     * Start Right Side
     *
     * */

    $ui .= '<span class="pull-right" style="' . ($level < 3 ? 'margin-right: 8px;' : '') . ' padding-top:2px; display:inline-block;">';



    //Do we have intent parents loaded in our data-set?
    if (!isset($in['in__parents'])) {

        //Fetch parents at this point:
        $in['in__parents'] = $CI->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_child_intent_id' => $in['in_id'],
        ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

    }

    //Loop through parents:
    $ui .= '<span class="' . advance_mode() . '">';
    foreach ($in['in__parents'] as $in_parent) {
        if($in_linked_id!=$in_parent['in_id']){
            $ui .= ' &nbsp;<a href="/intents/' . $in_parent['in_id'] . $in_filters['get_filter_url'] . '" data-toggle="tooltip" title="' . $in_parent['in_outcome'] . '" data-placement="bottom" class="in_child_icon_' . $in_parent['in_id'] . '">' . $en_all_7585[$in_parent['in_subtype_entity_id']]['m_icon'] . '</a>';
        }
    }
    $ui .= '</span>';



    $ui .= '<span style="display: inline-block; float: right;">'; //Start of 5x Action Buttons



    //For logged in users:
    $css_author = '';
    if(isset($session_en['en_id'])){

        $author_class = in_authors_class($in['in_id']);

        //Action Plan:
        $actionplan_users = $CI->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
            'ln_parent_intent_id' => $in['in_id'],
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_steps');

        if(count($in_filters['get_filter_query']) > 0){
            $actionplan_users_match = $CI->Links_model->ln_fetch(array_merge($in_filters['get_filter_query'], array(
                'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null, //Action Plan Steps Progressed
                'ln_parent_intent_id' => $in['in_id'],
                'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Link Statuses Public
            )), array(), 0, 0, array(), 'COUNT(ln_id) as total_steps');
        }
        if(1 || $actionplan_users[0]['total_steps'] > 0) {
            $ui .= '<span class="'.advance_mode($author_class).'"><a id="match_list_'.$in['in_id'].'" href="#actionplanusers-'.$in['in_id'].'" onclick="in_action_plan_users('.$in['in_id'].')" class="badge badge-primary white-primary is_not_bg" style="width:40px; margin:-3px -3px 0 4px;" data-toggle="tooltip" data-placement="bottorm" title="Users who completed this step">'.( !count($in_filters['get_filter_query']) || $actionplan_users_match[0]['total_steps']>0 ? '<span class="btn-counter">' . ( count($in_filters['get_filter_query']) > 0 ? '<i class="fas fa-filter mini-filter"></i> '.echo_number($actionplan_users_match[0]['total_steps']) : echo_number($actionplan_users[0]['total_steps']) ) . '</span>' : '' ).'<i class="fas fa-walking"></i></a></span>';
        }



        //Intent Notes:
        $count_in_notes = $CI->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
            'ln_child_intent_id' => $in['in_id'],
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

        //Intent note messages only:
        $count_in_messages = $CI->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $in['in_id'],
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

        $non_message_notes = $count_in_notes[0]['totals'] -  $count_in_messages[0]['totals'];

        $ui .= '<span class="'.advance_mode($author_class).'"><a href="#intentnotes-' . $in['in_id'] . '" onclick="in_messages_iframe('.$in['in_id'].')" class="msg-badge-' . $in['in_id'] . ' badge badge-primary white-primary is_not_bg '.( $level==0 ? '' . advance_mode() . '' : '' ).'" style="width:40px; margin-right:2px; margin-left:5px;" data-toggle="tooltip" title="Intent Notes" data-placement="bottom"><span class="btn-counter"><span class="in-notes-messages-' . $in['in_id'] . '">' . $count_in_messages[0]['totals'] .'</span>' . ( $non_message_notes > 0 ? '<span class="extra-note-counts '.advance_mode().'">+<span class="in-notes-non-messages-">'.$non_message_notes.'</span></span>' : '' ) . '</span><i class="fas fa-comment-plus"></i></a></span>';




        //Intent modify:
        $in__metadata_max_seconds = (isset($in_metadata['in__metadata_max_seconds']) ? $in_metadata['in__metadata_max_seconds'] : 0);

        $ui .= '<span class="'.advance_mode($author_class).'"><a class="badge badge-primary white-primary is_not_bg" onclick="in_modify_load(' . $in['in_id'] . ',' . $ln_id . ')" style="margin:-2px -8px 0 0; width:40px;" href="#loadmodify-' . $in['in_id'] . '-' . $ln_id . '" data-toggle="tooltip" title="Intent completion cost. Click to modify intent'.( $level>1 ? ' and link' : '' ).'" data-placement="bottom"><span class="btn-counter slim-time t_estimate_' . $in['in_id'] . advance_mode() . '" tree-max-seconds="' . $in__metadata_max_seconds . '" intent-seconds="' . $in['in_completion_seconds'] . '">'.( $in__metadata_max_seconds > 0 ? echo_time_hours($in__metadata_max_seconds , true) : 0 ).'</span><i class="fas fa-cog"></i></a> &nbsp;</span>';


        //Intent Unlink for trainers:
        if(filter_array($session_en['en__parents'], 'en_id', 7512)){
            if($ln_id > 0 && $in_linked_id > 0){
                $ui .= '<span class="'.advance_mode(in_authors_class($in_linked_id)).'"><a class="badge badge-primary white-primary is_not_bg " onclick="in_unlink_only(' . $in['in_id'] . ','.$level.',' . $ln_id . ')" style="margin:-2px -8px 0 4px; width:40px;" href="javascript:void(0)" data-toggle="tooltip" title="Unlink Intent" data-placement="bottom"><i class="far fa-trash-alt"></i></a> &nbsp;</span>';
            } else {
                $ui .= '<span style="width:43px; display: inline-block;">&nbsp;</span>';
            }
        }


    }

    //Intent Links:
    $count_in_trs = $CI->Links_model->ln_fetch(array_merge($in_filters['get_filter_query'], array(
        '(ln_parent_intent_id=' . $in['in_id'] . ' OR ln_child_intent_id=' . $in['in_id'] . ($ln_id > 0 ? ' OR ln_parent_link_id=' . $ln_id : '') . ')' => null,
    )), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    //Show link to load these links:
    $ui .= '<span class="'.advance_mode().'"><a href="/links?any_in_id=' . $in['in_id'] . '&ln_parent_link_id=' . $ln_id . $in_filters['get_filter_links_url'] . '" class="badge badge-primary is_not_bg" style="width:40px; margin:-3px 0px 0 4px; border:2px solid #ffe027 !important;"><span class="btn-counter">' . ( strlen($in_filters['get_filter_url']) > 0 ? '<i class="fas fa-filter mini-filter"></i> ' : '' ) . echo_number($count_in_trs[0]['totals']) . '</span><i class="fas fa-link"></i></a></span>';


    //Count children based on level:
    $tree_count = null;
    $tree_count_range = '0';
    if($level==1 || ($level==3 && $is_child_focused)){

        if(isset($in_metadata['in__metadata_max_steps']) && isset($in_metadata['in__metadata_min_steps'])){

            $tree_count = '<span class="btn-counter children-counter-' . $in['in_id'] . ' ' . ($is_parent && $level == 2 ? 'inb-counter' : '') . '">' . ( $in_metadata['in__metadata_min_steps']==$in_metadata['in__metadata_max_steps'] ? $in_metadata['in__metadata_max_steps'] : '~'.round(($in_metadata['in__metadata_min_steps']+$in_metadata['in__metadata_max_steps'])/2) ) . '</span>';

            $tree_count_range = ( $in_metadata['in__metadata_min_steps']==$in_metadata['in__metadata_max_steps'] ? $in_metadata['in__metadata_max_steps'] : $in_metadata['in__metadata_min_steps'].' - '.$in_metadata['in__metadata_max_steps'] );
        }

    } else {

        //Do a live child count:
        $child_links = $CI->Links_model->ln_fetch(array(
            'ln_parent_intent_id' => $in['in_id'],
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'in_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7356')) . ')' => null, //Intent Statuses Active
        ), array('in_child'), 0, 0, array(), 'COUNT(in_id) as in__child_count');

        $tree_count_range = $child_links[0]['in__child_count'];
        if($tree_count_range > 0){
            $tree_count = '<span class="btn-counter children-counter-' . $in['in_id'] . '">' . $tree_count_range . '</span>';
        }

    }



    //Intent Link to Travel Down/UP the Tree:
    if ($level == 0) {

        //Show Landing Page URL:
        $ui .= '&nbsp;<a href="/intents/' . $in['in_id'] . '" class="badge badge-primary is_not_bg is_hard_link" style="display:inline-block; margin-right:-2px; width:40px; border:2px solid #ffe027 !important;">'.$tree_count.'<i class="fas fa-angle-right"></i></a>';

    } elseif ($level == 1 && !$is_child_focused) {

        //Show Landing Page URL IF Public:

        $public_in = $CI->Intents_model->in_is_public($in);

        $ui .= '&nbsp;<em style="display:inline-block; margin-right:-2px; width:39px;">&nbsp;</em>';

    } else {

        $ui .= '&nbsp;<a href="/intents/' . $in['in_id'] . $in_filters['get_filter_url'] . '" class="tree-badge-' . $in['in_id'] . ' badge badge-primary is_not_bg is_hard_link" style="display:inline-block; margin-right:-2px; width:40px; border:2px solid #ffe027 !important;">' . $tree_count . '<i class="'.( $is_parent ? ( $level==3 ? 'fas fa-angle-right' : 'fas fa-angle-up' ) : ( $level==3 ? 'fas fa-angle-double-down' : 'fas fa-angle-down' ) ).'"></i></a>';

    }



    $ui .= '</span>'; //End of 5x Action Buttons

    $ui .= '</span>'; //End of right column


    //To clear right float:
    $ui .= '<div style="clear: both; margin: 0; padding: 0;"></div>';


    /*
     *
     * Child Intents
     *
     * */
    if ($level == 2) {

        $ui .= '<div id="list-cr-' . $ln_id . '" class="list-group step-group link-class--' . $ln_id . ' hidden" intent-id="' . $in['in_id'] . '">';

        //This line enables the in-between list moves to happen for empty lists:
        $ui .= '<div class="is_level3_sortable dropin-box" style="height:1px;">&nbsp;</div>';


        if (count($in['in__grandchildren']) > 0) {
            foreach ($in['in__grandchildren'] as $grandchild_in) {
                $ui .= echo_in($grandchild_in, ($level + 1), $in['in_id'], $is_parent);
            }
        } else {
            $ui .= '<div class="no_child_message no_child_'.$in['in_id'].'">No children added yet.</div>';
        }


        $ui .= '<div class="'.advance_mode(in_authors_class($in['in_id'])).'">';
        $ui .= '<div class="list-group-item list_input new-in3-input link-class--' . $ln_id . ' hidden">
                <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="in_link_or_create(' . $in['in_id'] . ',0,3);" intent-id="' . $in['in_id'] . '"><input type="text" class="form-control intentadder-id-'.$in['in_id'].' algolia_search" maxlength="' . $CI->config->item('in_outcome_max') . '" id="addintent-cr-' . $ln_id . '" intent-id="' . $in['in_id'] . '" placeholder="+ Intent"></form></div>
        </div>';

        $ui .= '<div class="algolia_search_pad in_pad_'.$in['in_id'].' hidden"><span>Search existing intents or create a new one...</span></div>';
        $ui .= '</div>';

        //Load JS search for this input:
        $ui .= '<script> $(document).ready(function () { in_load_search(".intentadder-id-'.$in['in_id'].'", 0, 3, "'. $in['ln_order'] .'"); }); </script>';

        $ui .= '</div>';
    }

    $ui .= '</div>';

    return $ui;

}



function echo_rank($integer){
    if($integer==1){
        return '🏅';
    } elseif($integer==2){
        return '🥈';
    } elseif($integer==3){
        return '🥉';
    } else {
        //return echo_ordinal_number($integer);
        return null;
    }
}


function echo_en($en, $level, $is_parent = false)
{

    $CI =& get_instance();
    $session_en = $CI->session->userdata('user');
    $en_all_6177 = $CI->config->item('en_all_6177'); //Entity Statuses
    $ln_id = (isset($en['ln_id']) ? $en['ln_id'] : 0);
    $ui = null;


    $ui .= '<div entity-id="' . $en['en_id'] . '" en-status="' . $en['en_status_entity_id'] . '" tr-id="'.$ln_id.'" ln-status="'.( $ln_id > 0 ? $en['ln_status_entity_id'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '" class="list-group-item object_highlight highlight_en_'.$en['en_id'].' en-item en___' . $en['en_id'] . ' ' . ($level <= 1 ? 'top_entity' : 'tr_' . $en['ln_id']) . ( $is_parent ? ' parent-entity ' : '' ) . '">';


    $ui .= '<span style="display:inline-block; margin-top:0px; padding-bottom: 5px;">';


    //Hidden fields to store dynamic value for on-demand JS modifications:
    //Show Link Status if Available:
    if ($ln_id > 0) {

        //Link Type Full List:
        $en_all_4593 = $CI->config->item('en_all_4593');
        $en_all_6186 = $CI->config->item('en_all_6186'); //Link Statuses

        //Show Link link icons:
        $ui .= '<span class="double-icon '.advance_mode().'" style="margin-right:7px;">';

        //Show larger icon for link type (auto detected based on link content):
        $ui .= '<span class="icon-main ln_type_' . $ln_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4593[$en['ln_type_entity_id']]['m_name'].' @'.$en['ln_type_entity_id'].'">' . $en_all_4593[$en['ln_type_entity_id']]['m_icon'] . '</span></span> ';

        //Show smaller link status icon:
        $ui .= '<span class="icon-top-right ln_status_entity_id_' . $ln_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6186[$en['ln_status_entity_id']]['m_name'].' @'.$en['ln_status_entity_id'].': '.$en_all_6186[$en['ln_status_entity_id']]['m_desc'].'">' . $en_all_6186[$en['ln_status_entity_id']]['m_icon'] . '</span></span>';

        //Show link index
        if($en['ln_external_id'] > 0){
            if(en_auth(array(1281)) && $en['ln_parent_entity_id']==6196){
                //Give miners the ability to ping Messenger profiles:
                $ui .= '<span class="icon-top-left" data-toggle="tooltip" data-placement="right" title="Link External ID = '.$en['ln_external_id'].' [Messenger Profile]"><a href="/messenger/messenger_fetch_profile/'.$en['ln_external_id'].'" target="_blank"><i class="fas fa-project-diagram"></i></a></span>';
            } else {
                $ui .= '<span class="icon-top-left" data-toggle="tooltip" data-placement="right" title="Link External ID = '.$en['ln_external_id'].'"><i class="fas fa-project-diagram"></i></span>';
            }
        }

        $ui .= '</span>';

    } elseif( $level > 0 ) {

        //Show Blank box:
        $ui .= '<span class="double-icon '.advance_mode().'" style="margin:0 3px;"></span>';

    }





    //Always Show Entity Icons
    $ui .= '<span class="double-icon" style="margin-right:7px;">';

    //Show larger custom entity icon:
    $ui .= '<span class="icon-main en_ui_icon en_ui_icon_' . $en['en_id'] . ' en-icon en__icon_'.$en['en_id'].'" en-is-set="'.( strlen($en['en_icon']) > 0 ? 1 : 0 ).'">' . echo_en_icon($en) . '</span>';

    //Show smaller entity status:
    $ui .= '<span class="icon-top-right en_status_entity_id_' . $en['en_id'] . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$en['en_status_entity_id']]['m_name'].' @'.$en['en_status_entity_id'].': '.$en_all_6177[$en['en_status_entity_id']]['m_desc'].'">' . $en_all_6177[$en['en_status_entity_id']]['m_icon'] . '</span></span>';


    $ui .= '</span>';



    //Entity Name:
    $ui .= '<span class="en_name en_name_' . $en['en_id'] . '">' . $en['en_name'] . '</span>';

    $ui .= '</span>';




    //Does this entity also include a link?
    if ($ln_id > 0) {

        //Show link content:
        $ln_content = echo_ln_urls($en['ln_content'] , $en['ln_type_entity_id']);

        //Is this Entity link an Embeddable URL type or not?
        $ui .= ' <span class="ln_content ln_content_' . $ln_id . '" style="min-width:240px; line-height: 140%; display:inline-block;">';
        $ui .= $ln_content;
        $ui .= '</span>';

        //This is for JS editing:
        $ui .= '<span class="ln_content_val_' . $ln_id . ' hidden">' . $en['ln_content'] . '</span>';

    }


    //Show database connectors:
    if($level == 1){
        $en_count_references = en_count_references($en['en_id']);
        if(count($en_count_references) > 0){

            $en_all_6194 = $CI->config->item('en_all_6194');

            //Show this entities connections:
            $ref_count = 0;
            foreach($en_count_references as $en_id=>$en_count){
                $ui .= '&nbsp;&nbsp;<a href="/links?any_en_id=' . $en_id . '" style="font-size:0.8em; font-weight:bold;" data-toggle="tooltip" data-placement="top" title="This entity is referenced as '.$en_all_6194[$en_id]['m_name'].' '.number_format($en_count, 0).' times">'.$en_all_6194[$en_id]['m_icon'] . ' '. echo_number($en_count).'</a>';
                $ref_count++;
            }
        }
    }







    //Right content:
    $ui .= '<span class="pull-right" style="padding-top:2px;">';

    //Do we have entity parents loaded in our data-set? If not, load it:
    if (!isset($en['en__parents'])) {

        //Fetch parents at this point:
        $en['en__parents'] = $CI->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_child_entity_id' => $en['en_id'], //This child entity
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'en_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ), array('en_parent'), 0, 0, array('en_name' => 'ASC'));
    }


    //Parent entities:
    $ui .= '<span class="' . advance_mode() . '">';
    foreach ($en['en__parents'] as $en_parent) {
        $ui .= '<span class="en_child_icon_' . $en_parent['en_id'] . '">&nbsp;<span class="parent-icon"><a href="/entities/' . $en_parent['en_id'] . '" data-toggle="tooltip" title="' . $en_parent['en_name'] . (strlen($en_parent['ln_content']) > 0 ? ' = ' . $en_parent['ln_content'] : '') . '" data-placement="bottom">' . echo_en_icon($en_parent) . '</a></span> </span>';
    }
    $ui .= '</span>';



    $ui .= '<span style="display: inline-block; float: right;">'; //Start of 5x Action Buttons



    if(isset($session_en['en_id'])) {

        //Action Plan Set Intentions by Users and Companies:
        $user_intentions = $CI->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_7347')) . ')' => null, //Action Plan Set Intentions
            'ln_creator_entity_id' => $en['en_id'],
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_steps');

        if($user_intentions[0]['total_steps'] > 0){
            $ui .= '<a href="/links?ln_status_entity_id='.join(',', $CI->config->item('en_ids_7360')) /* Link Statuses Active */.'&ln_type_entity_id='.join(',', $CI->config->item('en_ids_7347')).'&ln_creator_entity_id=' . $en['en_id'] . '" class="badge badge-secondary white-secondary ' . advance_mode() . '" style="width:40px; margin-left:5px; margin-right: -3px;" data-toggle="tooltip" data-placement="bottom" title="Manage entity intentions"><span class="btn-counter">'.echo_number($user_intentions[0]['total_steps']).'</span><i class="far fa-bullseye-arrow"></i></a>';
        }


        //Count & Display active Intent Notes that this entity has been referenced within:
        $messages = $CI->Links_model->ln_fetch(array(
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
            'ln_parent_entity_id' => $en['en_id'], //Entity Referenced in message content
        ), array(), 0, 0, array(), 'COUNT(ln_id) AS total_messages');
        if($messages[0]['total_messages'] > 0){
            //Only show in non-advance mode if we have messages:
            $ui .= '<a class="badge badge-secondary white-secondary ' . advance_mode() .( $level==0 || $messages[0]['total_messages'] == 0 ? advance_mode() : '' ) . '" href="#entityreferences-' . $en['en_id'] . '" onclick="' . ( $messages[0]['total_messages'] == 0 ? 'alert(\'No Intent Notes found that reference this entity\')' : ( $level==0 ? 'alert(\'Cannot manage here. Go to the entity to manage.\')' : 'en_load_messages('.$en['en_id'].')' ) ) . '" style="width:40px; margin-left:5px; margin-right: -3px;" data-toggle="tooltip" data-placement="bottom" title="Entity References within Intent Notes"><span class="btn-counter">' . echo_number($messages[0]['total_messages']) . '</span><i class="fas fa-comment-plus"></i></a>';
        }



        //Modify Entity:
        $ui .= '<a href="#loadmodify-' . $en['en_id'] . '-' . $ln_id . '" onclick="'.( $level==0 ? 'alert(\'Cannot manage here. Go to the entity to manage.\')' : 'en_modify_load(' . $en['en_id'] . ',' . $ln_id . ')' ).'" class="badge badge-secondary white-secondary '. advance_mode() . ( $level==0 ? '' . advance_mode() . '' : '' ).'" style="margin:-2px -6px 0 5px; width:40px;" data-toggle="tooltip" data-placement="bottom" title="Entity trust score. Click to modify entity'.( $level>1 ? ' and link' : '' ).'"><span class="btn-counter">'.echo_number($en['en_trust_score']).'</span><i class="fas fa-cog" style="width:28px; padding-right:7px; text-align:center;"></i></a> &nbsp;';

    }


    //Count & link to Entity links:
    $count_in_trs = $CI->Links_model->ln_fetch(array(
        '(ln_parent_entity_id=' . $en['en_id'] . ' OR  ln_child_entity_id=' . $en['en_id'] . ' OR  ln_creator_entity_id=' . $en['en_id'] . ($ln_id > 0 ? ' OR ln_parent_link_id=' . $ln_id : '') . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    if ($count_in_trs[0]['totals'] > 0) {
        //Show the link button:
        $ui .= '<a href="/links?any_en_id=' . $en['en_id'] . '&ln_parent_link_id=' . $ln_id . '" class="badge badge-secondary ' . advance_mode() . '" style="width:40px; margin:-3px 2px 0 2px; border:2px solid #0084ff !important;"><span class="btn-counter">' . echo_number($count_in_trs[0]['totals']) . '</span><i class="fas fa-link"></i></a>';
    }






    //Have we counted the Entity Children?
    if (!isset($en['en__child_count'])) {

        //Assume none:
        $en['en__child_count'] = 0;

        //Do a child count:
        $child_links = $CI->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $en['en_id'],
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Link Statuses Active
            'en_status_entity_id IN (' . join(',', $CI->config->item('en_ids_7358')) . ')' => null, //Entity Statuses Active
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

        if (count($child_links) > 0) {
            $en['en__child_count'] = intval($child_links[0]['en__child_count']);
        }

    }

    if($level == 1){

        $ui .= '<em style="display:inline-block; margin-right:6px; width:40px;">&nbsp;</em>';

    } else {

        $ui .= '<a class="badge badge-secondary" href="/entities/' . $en['en_id']. '" style="display:inline-block; margin-right:6px; width:40px; margin-left:1px; border:2px solid #0084ff !important;">' . ($en['en__child_count'] > 0 ? '<span class="btn-counter" title="' . number_format($en['en__child_count'], 0) . ' Entities">' . echo_number($en['en__child_count']) . '</span>' : '') . '<i class="'.( $level==0 ? 'fas fa-angle-right' : ( $is_parent ? 'fas fa-angle-up' : 'fas fa-angle-down' )).'"></i></a>';

    }

    $ui .= '</span>'; //End of 5x Action Buttons

    $ui .= '</span>';


    //To clear right float:
    $ui .= '<div style="clear: both; margin: 0; padding: 0;"></div>';


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
    return ($count == 1 ? '' : ($is_es ? 'es' : 's'));
}

