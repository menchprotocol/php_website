<?php

function echo_en_load_more($page, $limit, $en__child_count)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $ui = '<div class="load-more montserrat list-group-item itemsource no-left-padding" style="padding-bottom:20px;"><a href="javascript:void(0);" onclick="en_load_next_page(' . $page . ', 0)">';

    //Regular section:
    $max_sources = (($page + 1) * $limit);
    $max_sources = ($max_sources > $en__child_count ? $en__child_count : $max_sources);
    $ui .= '<span class="icon-block"><i class="far fa-search-plus"></i></span>LOAD MORE';
    $ui .= '</a></div>';

    return $ui;
}

function echo_clean_db_name($field_name){
    //Takes a database field name and returns a clean version of it:
    if(substr($field_name, 0, 3) == 'ln_'){
        //Link field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('ln_', 'Transaction ', $field_name))));
    } elseif(substr($field_name, 0, 3) == 'in_'){
        //Blog field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('in_', 'Blog ', $field_name))));
    } elseif(substr($field_name, 0, 3) == 'en_'){
        //Player field:
        return ucwords(str_replace('_', ' ', str_replace('_id', '', str_replace('en_', 'Source ', $field_name))));
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

    return ( $min ? $min . ' Min.' : ( $sec ? $sec . ' Sec.' : false ) );
}

function echo_url_type_4537($url, $en_type_link_id)
{

    /*
     *
     * Displays Player Links that are a URL based on their
     * $en_type_link_id as listed under Player URL Links:
     * https://mench.com/source/4537
     *
     * */


    if ($en_type_link_id == 4256 /* Generic URL */) {

        return '<a href="' . $url . '"><span class="url_truncate">' . echo_url_clean($url) . '</span></a>';

    } elseif ($en_type_link_id == 4257 /* Embed Widget URL? */) {

        return echo_url_embed($url);

    } elseif ($en_type_link_id == 4260 /* Image URL */) {

        $current_mench = current_mench();
        if($current_mench['x_name']=='source'){
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
     *       values for ln_type_source_id as this could change the equation for those
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
                    $start_sec = intval(one_two_explode('start=','&',$url));
                }
                if(is_numeric(one_two_explode('end=','&',$url))){
                    $end_sec = intval(one_two_explode('end=','&',$url));
                }
            }

            //Set the Clean URL:
            $clean_url = 'https://www.youtube.com/watch?v=' . $video_id;

            //Inform User that this is a sliced video
            if ($start_sec || $end_sec) {
                $embed_html_code .= '<div class="read-topic"><i class="fad fa-play-circle"></i>&nbsp;' . ( $end_sec ? '<b title="FROM SECOND '.$start_sec.' to '.$end_sec.'">WATCH THIS ' . echo_time_minutes(($end_sec - $start_sec)) . ' CLIP</b>' : '<b>WATCH FROM ' . ($start_sec ? echo_time_minutes($start_sec) : 'START') . '</b> TO <b>' . ($end_sec ? echo_time_minutes($end_sec) : 'END') . '</b>') . ':</div>';
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

function echo_in_title($in, $push_message = false, $common_prefix = null){

    if(strlen($common_prefix) > 0){
        $in['in_title'] = trim(substr($in['in_title'], strlen($common_prefix)));
    }

    if($push_message){

        return $in['in_title'];

    } else {

        return '<span class="text__4736_'.$in['in_id'].'">'.htmlentities(trim($in['in_title'])).'</span>';

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


    //Transaction Status
    $en_all_6186 = $CI->config->item('en_all_6186');


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item itemblog is-msg notes_sortable msg_en_type_' . $ln['ln_type_source_id'] . '" id="ul-nav-' . $ln['ln_id'] . '" tr-id="' . $ln['ln_id'] . '">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="edit-off text_message" id="msgbody_' . $ln['ln_id'] . '">';
    $ui .= $CI->READ_model->dispatch_message($ln['ln_content'], $session_en, false, array(), $ln['ln_child_blog_id']);
    $ui .= '</div>';

    //Editing menu:
    $ui .= '<div class="note-edit edit-off '.superpower_active(10939).'"><span class="show-on-hover">';

        //Sort:
        if(in_array(4603, $en_all_4485[$ln['ln_type_source_id']]['m_parents'])){
            $ui .= '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort '.( in_array(4603, $en_all_4485[$ln['ln_type_source_id']]['m_parents']) ? 'blog_note_sorting' : '' ).'"></i></span>';
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
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-blog white-third" href="javascript:in_note_modify_save(' . $ln['ln_id'] . ',' . $ln['ln_type_source_id'] . ');" title="Save changes" data-toggle="tooltip" data-placement="top"><i class="fas fa-check"></i> Save</a></li>';

    //Cancel Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-blog white-third" href="javascript:in_note_modify_cancel(' . $ln['ln_id'] . ');" title="Cancel editing" data-toggle="tooltip" data-placement="top"><i class="fas fa-times"></i></a></li>';

    //Show drop down for message link status:
    $ui .= '<li class="pull-right edit-on hidden"><span class="white-wrapper" style="margin:-5px 0 0 0; display: block;">';
    $ui .= '<select id="message_status_' . $ln['ln_id'] . '"  class="form-control border" style="margin-bottom:0;" title="Change message status" data-toggle="tooltip" data-placement="top">';
    foreach($CI->config->item('en_all_12012') as $en_id => $m){
        $ui .= '<option value="' . $en_id . '" '.( $en_id==$ln['ln_status_source_id'] ? 'selected="selected"' : '' ).'>' . $m['m_name'] . '</option>';
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
        //Return default icon for sources:
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


function echo_ln_urls($ln_content, $ln_type_source_id){

    $CI =& get_instance();
    if (in_array($ln_type_source_id, $CI->config->item('en_ids_4537'))) {

        //Player URL Links
        return echo_url_type_4537($ln_content, $ln_type_source_id);

    } elseif($ln_type_source_id==10669) {

        return '<i class="'.$ln_content.'"></i>';

    } elseif(strlen($ln_content) > 0) {

        return echo_url(htmlentities($ln_content));

    } else {

        return null;

    }
}


function echo_ln($ln, $is_inner = false)
{

    $CI =& get_instance();
    $en_all_4593 = $CI->config->item('en_all_4593'); //Link Type
    $en_all_4527 = $CI->config->item('en_all_4527');
    $en_all_4341 = $CI->config->item('en_all_4341'); //Link Table
    $en_all_2738 = $CI->config->item('en_all_2738');
    $en_all_6186 = $CI->config->item('en_all_6186'); //Transaction Status



    if(!isset($en_all_4593[$ln['ln_type_source_id']])){
        //We've probably have not yet updated php cache, set error:
        $en_all_4593[$ln['ln_type_source_id']] = array(
            'm_icon' => '<i class="fad fa-exclamation-triangle"></i>',
            'm_name' => 'Link Type Not Synced in PHP Cache',
            'm_desc' => '',
            'm_parents' => array(),
        );
    }





    //Display the item
    $ui = '<div class="ledger-list">';


    //Transaction ID
    $ui .= '<div class="simple-line"><a href="/ledger?ln_id='.$ln['ln_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4367]['m_name'].'" class="montserrat"><span class="icon-block">'.$en_all_4341[4367]['m_icon']. '</span>'.$ln['ln_id'].'</a></div>';


    //Status
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[6186]['m_name'].( strlen($en_all_6186[$ln['ln_status_source_id']]['m_desc']) ? ': '.$en_all_6186[$ln['ln_status_source_id']]['m_desc'] : '' ).'"><span class="icon-block">'.$en_all_6186[$ln['ln_status_source_id']]['m_icon'].'</span>'.$en_all_6186[$ln['ln_status_source_id']]['m_name'].'</span></div>';

    //Time
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $en_all_4341[4362]['m_name'].': '.$ln['ln_timestamp'] . ' PST"><span class="icon-block">'.$en_all_4341[4362]['m_icon']. '</span>' . echo_time_difference(strtotime($ln['ln_timestamp'])) . ' ago</span></div>';


    //Transaction Type
    $ui .= '<div class="simple-line"><a href="/source/'.$ln['ln_type_source_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4593]['m_name'].( strlen($en_all_4593[$ln['ln_type_source_id']]['m_desc']) ? ': '.$en_all_4593[$ln['ln_type_source_id']]['m_desc'] : '' ).'" class="montserrat"><span class="icon-block">'.$en_all_4341[4593]['m_icon']. '</span><span class="'.extract_icon_color($en_all_4593[$ln['ln_type_source_id']]['m_icon']).'">'. $en_all_4593[$ln['ln_type_source_id']]['m_icon']. ' '. $en_all_4593[$ln['ln_type_source_id']]['m_name'] . '</span></a></div>';


    //Hide Sensitive Details?
    if(in_array($ln['ln_type_source_id'] , $CI->config->item('en_ids_4755')) && !superpower_active(10985, true)){

        //Hide Information:
        $ui .= '<div class="simple-line"><span data-toggle="tooltip" class="montserrat" data-placement="top" title="Details are kept private"><span class="icon-block"><i class="fal fa-eye-slash"></i></span>PRIVATE INFORMATION</span></div>';

    } else {


        //Creator
        if($ln['ln_creator_source_id'] > 0){
            $trainer_ens = $CI->SOURCE_model->en_fetch(array(
                'en_id' => $ln['ln_creator_source_id'],
            ));
            $ui .= '<div class="simple-line"><a href="/source/'.$trainer_ens[0]['en_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4364]['m_name'].'" class="montserrat"><span class="icon-block">'.$en_all_4341[4364]['m_icon']. '</span><span class="'.extract_icon_color($trainer_ens[0]['en_icon']).'">'.echo_en_icon($trainer_ens[0]['en_icon']) . ' ' . $trainer_ens[0]['en_name'] . '</span></a></div>';
        }


        //Metadata
        if(strlen($ln['ln_metadata']) > 0){
            $ui .= '<div class="simple-line"><a href="/read/transaction_json/' . $ln['ln_id'] . '" class="montserrat"><span class="icon-block">'.$en_all_4341[6103]['m_icon']. '</span>'.$en_all_4341[6103]['m_name']. '</a></div>';
        }


        //External ID
        if($ln['ln_external_id'] > 0){
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[7694]['m_name'].'"><span class="icon-block">'.$en_all_4341[7694]['m_icon']. '</span>'.$ln['ln_external_id'].'</span></div>';
        }

        //Message
        if(strlen($ln['ln_content']) > 0){
            //$CI->READ_model->dispatch_message($ln['ln_content'])
            $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4372]['m_name'].'"><span class="icon-block">'.$en_all_4341[4372]['m_icon'].'</span><div class="title-block ledger-msg">'.htmlentities($ln['ln_content']).'</div></div>';
        }

        //Order
        if($ln['ln_order'] > 0){
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4370]['m_name']. '"><span class="icon-block">'.$en_all_4341[4370]['m_icon']. '</span>'.echo_ordinal_number($ln['ln_order']).'</span></div>';
        }

    }


    //5x Relations:
    if(!$is_inner){

        $en_all_6232 = $CI->config->item('en_all_6232'); //PLATFORM VARIABLES
        foreach ($CI->config->item('en_all_10692') as $en_id => $m) {

            //Do we have this set?
            if(!intval($ln[$en_all_6232[$en_id]['m_desc']])){
                continue;
            }

            if(in_array(6160 , $m['m_parents'])){

                //SOURCE
                $ens = $CI->SOURCE_model->en_fetch(array('en_id' => $ln[$en_all_6232[$en_id]['m_desc']]));

                $ui .= '<div class="simple-line"><a href="/source/'.$ens[0]['en_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[$en_id]['m_name'].'" class="montserrat"><span class="icon-block">'.$en_all_4341[$en_id]['m_icon']. '</span><span class="'.extract_icon_color($ens[0]['en_icon']).'">'.echo_en_icon($ens[0]['en_icon']). ' '.$ens[0]['en_name'].'</span></a></div>';

            } elseif(in_array(6202 , $m['m_parents'])){

                //BLOG
                $ins = $CI->BLOG_model->in_fetch(array('in_id' => $ln[$en_all_6232[$en_id]['m_desc']]));

                $ui .= '<div class="simple-line"><a href="/blog/'.$ins[0]['in_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[$en_id]['m_name'].'" class="montserrat"><span class="icon-block">'.$en_all_4341[$en_id]['m_icon']. '</span>'.$en_all_2738[4535]['m_icon']. ' '.echo_in_title($ins[0]).'</a></div>';

            } elseif(in_array(4367 , $m['m_parents'])){

                //READ
                $lns = $CI->READ_model->ln_fetch(array('ln_id' => $ln[$en_all_6232[$en_id]['m_desc']]));
                $ui .= '<div class="transaction-ref">'.echo_ln($lns[0], true).'</div>';

            }
        }
    }


    $ui .= '</div>';

    return $ui;
}


function echo_actionplan_step_child($en_id, $in, $is_unlocked_step = false, $common_prefix = null){

    $CI =& get_instance();

    //Open list:
    $ui = '<a href="/'.$in['in_id']. '" class="list-group-item itemread">';

    $ui .= echo_in_title($in, false, $common_prefix);

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

    $ui .= ' <span>' . echo_in_title($in).'</span>';

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
            'ln_type_source_id' => 4246, //Platform Bug Reports
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







function echo_tree_sources($in, $push_message = false, $autoexpand = false)
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
                    //$source_info .= '<a href="/source/' . $en['en_id'] . '">';
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
                //$expert_info .= '<a href="/source/' . $en['en_id'] . '">';
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
        return $pitch_title.$pitch_body;
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
     * UI for Platform Cache sources
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item($config_var_name);
    $cache_en = $config_array[$en_id];
    if (!$cache_en) {
        //Could not find matching item
        return false;
    }


    //We have two skins for displaying Status:
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



function echo_in_stat_read($in = array(), $en = array()){

    $CI =& get_instance();
    $ui = null;

    if(superpower_active(10964, true)){

        if(count($in)){
            $item = $in;
            $coin_filter = array(
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null,
                'ln_parent_blog_id' => $in['in_id'],
            );
        } elseif(count($en)){
            $item = $en;
            $coin_filter = array(
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null,
                'ln_creator_source_id' => $en['en_id'],
            );
        }

        $read_coins = $CI->READ_model->ln_fetch($coin_filter, array(), 1, 0, array(), 'COUNT(ln_id) as total_coins');
        if($read_coins[0]['total_coins'] > 0){

            $ui .= '<span class="montserrat read '.superpower_active(10964).'"><span class="icon-block"><i class="fas fa-circle"></i></span>'.echo_number($read_coins[0]['total_coins']).'</span>';

            //If Progress Type then show progress here....
            if(isset($item['ln_type_source_id']) && in_array($item['ln_type_source_id'], $CI->config->item('en_ids_12227'))){
                $en_all_12227 = $CI->config->item('en_all_12227');
                $ui .= '<div class="space-content">';
                $ui .= '<span class="' . superpower_active(10964).'">' . $en_all_12227[$item['ln_type_source_id']]['m_icon'] . '&nbsp;</span>';
                $ui .= '</div>';
            }
        }

    }

    return $ui;
}

function echo_in_stat_source($in_id = 0, $en_id = 0){

    $CI =& get_instance();

    if(superpower_active(10983, true)){

        if($in_id){
            $mench = 'source';
            $join_objects = array();
            $coin_filter = array(
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_12273')) . ')' => null, //BLOG COIN
                'ln_child_blog_id' => $in_id,
            );
        } elseif($en_id){
            $mench = 'blog';
            $join_objects = array('in_child');
            $coin_filter = array(
                'in_status_source_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Blog Status Public
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_12273')) . ')' => null, //BLOG COIN
                'ln_parent_source_id' => $en_id,
            );
        }

        $source_coins = $CI->READ_model->ln_fetch($coin_filter, $join_objects, 0, 0, array(), 'COUNT(ln_id) as total_coins');
        if($source_coins[0]['total_coins'] > 0){
            return '<span class="montserrat '.$mench.' '.( $in_id ? superpower_active(10983) : '' ).'"><span class="icon-block"><i class="fas fa-circle"></i></span>'.echo_number($source_coins[0]['total_coins']).'</span>';
        }

    }

    return null;
}



function echo_in_read($in, $parent_is_or = false, $footnotes = null, $common_prefix = null, $extra_class = null, $show_editor = false)
{

    //See if user is logged-in:
    $CI =& get_instance();
    $session_en = superpower_assigned();

    $metadata = unserialize($in['in_metadata']);
    $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );

    if($session_en){
        $completion_rate = $CI->READ_model->read__completion_progress($session_en['en_id'], $in);
    } else {
        $completion_rate['completion_percentage'] = 0;
    }


    $can_click = ( ( $parent_is_or && in_array($in['in_status_source_id'], $CI->config->item('en_ids_12138')) ) || $completion_rate['completion_percentage']>0 || $show_editor );
    $in_thumbnail = ( $can_click ?  echo_in_thumbnail($in['in_id']) : false );


    $ui  = '<div id="ap_in_'.$in['in_id'].'" '.( isset($in['ln_id']) ? ' sort-link-id="'.$in['ln_id'].'" ' : '' ).' class="list-group-item no-side-padding '.( $show_editor ? 'actionplan_sort' : '' ).' itemread '.$extra_class.'">';
    $ui .= ( $can_click ? '<a href="/'.$in['in_id'] . '" class="itemread">' : '' );

    if($can_click && $completion_rate['completion_percentage']>0){
        $ui .= '<div class="progress-bg" title="You are '.$completion_rate['completion_percentage'].'% done as you have read '.$completion_rate['steps_completed'].' of '.$completion_rate['steps_total'].' blogs'.( $has_time_estimate ? ' (Total Estimate '.echo_time_range($in, true).')' : '' ).'"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
    }

    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';
    $ui .= '<td>';


    //READ ICON
    $ui .= '<span class="icon-block">'.( $can_click ? '<i class="fas fa-circle read"></i>' : '<i class="far fa-lock read"></i>' ).'</span>';
    $ui .= '<b class="montserrat blog-url title-block '.( $in_thumbnail ? 'title-no-right' : '' ).'">'.echo_in_title($in, false, $common_prefix).'</b>';


    //Description:
    if($can_click){
        $in_description = echo_in_description($in['in_id']);
        if($in_description){
            $ui .= '<div class="blog-desc"><span class="icon-block">&nbsp;</span>'.$in_description.'</div>';
        }
    }

    if($footnotes){
        $ui .= '<div class="blog-footnote"><span class="icon-block">&nbsp;</span>' . $footnotes . '</div>';
    }

    $ui .= '</td>';

    //Search for Blog Image:
    if($in_thumbnail || $show_editor){

        $ui .= '<td class="featured-frame" '.( $show_editor ? ' style="padding-right:25px;" ' : '' ).'>';
        $ui .= $in_thumbnail;

        if($show_editor){
            $ui .= '<div class="note-edit edit-off"><span class="show-on-hover">';

            $ui .= '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort"></i></span>';

            //Remove:
            $ui .= '<span title="Remove from list" data-toggle="tooltip" data-placement="left"><span class="actionplan_remove" in-id="'.$in['in_id'].'"><i class="far fa-trash-alt"></i></span></span>';

            $ui .= '</span></div>';
        }

        $ui .= '</td>';

    }

    $ui .= '</tr></table>';


    $ui .= ( $can_click ? '</a>' : '' );
    $ui .= '</div>';

    return $ui;
}

function echo_in_description($in_id){

    $CI =& get_instance();

    foreach ($CI->READ_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        'ln_type_source_id' => 4231, //Blog Note Messages
        'ln_child_blog_id' => $in_id,
    ), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {

        //See if Text Message:
        if($ln['ln_parent_source_id'] > 0){
            $ln_content = trim(str_replace('@'.$ln['ln_parent_source_id'],'',$ln['ln_content']));
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
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        'ln_type_source_id' => 4231, //Blog Note Messages
        'ln_child_blog_id' => $in_id,
        'ln_parent_source_id >' => 0, //Reference a source
    ), array(), 0, 0, array('ln_order' => 'ASC')) as $ln) {

        //Images?
        foreach($CI->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id' => 4260, //Image
            'ln_child_source_id' => $ln['ln_parent_source_id'],
        )) as $embed_image){
            $embed_code .= '<div class="inline-block featured-frame pull-right"><span class="featured-image"><img src="'.$embed_image['ln_content'].'" /></span></div>';
        }
        if($embed_code){
            break;
        }

        //Embed Videos?
        foreach($CI->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id' => 4257, //Embed
            'ln_child_source_id' => $ln['ln_parent_source_id'],
        )) as $embed_video){
            $youtube_id = extract_youtube_id($embed_video['ln_content']);
            if(strlen($youtube_id) > 0){
                $embed_code .= '<div class="inline-block featured-frame pull-right"><span class="featured-image"><img src="https://i3.ytimg.com/vi/'.$youtube_id.'/maxresdefault.jpg" /></span></div>';
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
    $ui .= '<span class="icon-block">'.$en_all_7585[$in['in_type_source_id']]['m_icon'].'</span>';
    $ui .= '<b class="montserrat">'.echo_in_title($in, false).'</b>';
    $ui .= '</div>';
    return $ui;

}

function echo_in_scores_answer($starting_in, $depth_levels, $original_depth_levels, $parent_in_type_source_id){

    if($depth_levels<=0){
        //End recursion:
        return false;
    }

    //We're going 1 level deep:
    $depth_levels--;

    //Go down recursively:
    $CI =& get_instance();
    $en_all_6186 = $CI->config->item('en_all_6186'); //Transaction Status
    $en_all_4486 = $CI->config->item('en_all_4486');
    $en_all_4737 = $CI->config->item('en_all_4737'); // Blog Status
    $en_all_7585 = $CI->config->item('en_all_7585'); // Blog Subtypes


    $ui = null;
    foreach($CI->READ_model->ln_fetch(array(
        'ln_parent_blog_id' => $starting_in,
        'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
        'in_status_source_id IN (' . join(',', $CI->config->item('en_ids_7356')) . ')' => null, //Blog Status Active
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $in_ln){

        //Prep Metadata:
        $metadata = unserialize($in_ln['ln_metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            'ln_type_source_id' => 4231, //Blog Note Messages
            'ln_child_blog_id' => $in_ln['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Blog Link Type: '.$en_all_4486[$in_ln['ln_type_source_id']]['m_name'].'">'. $en_all_4486[$in_ln['ln_type_source_id']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Blog Transaction Status: '.$en_all_6186[$in_ln['ln_status_source_id']]['m_name'].'">'. $en_all_6186[$in_ln['ln_status_source_id']]['m_icon'] . '</span>';

        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Blog Type: '.$en_all_7585[$in_ln['in_type_source_id']]['m_name'].'">'. $en_all_7585[$in_ln['in_type_source_id']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Blog Status: '.$en_all_4737[$in_ln['in_status_source_id']]['m_name'].'">'. $en_all_4737[$in_ln['in_status_source_id']]['m_icon']. '</span>';
        $ui .= '<a href="/source/admin_panel/assessment_marks_birds_eye?starting_in='.$in_ln['in_id'].'&depth_levels='.$original_depth_levels.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this blog"><u>' .   echo_in_title($in_ln, false) . '</u></a>';

        $ui .= ' [<span data-toggle="tooltip" data-placement="top" title="Completion Marks">'.( ($in_ln['ln_type_source_id'] == 4228 && in_array($parent_in_type_source_id , $CI->config->item('en_ids_6193') /* OR Blogs */ )) || ($in_ln['ln_type_source_id'] == 4229) ? echo_in_marks($in_ln) : '' ).'</span>]';

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
        $ui .=  echo_in_scores_answer($in_ln['in_id'], $depth_levels, $original_depth_levels, $in_ln['in_type_source_id']);

    }

    //Return the wrapped UI if existed:
    return ($ui ? '<div class="inline-box">' . $ui . '</div>' : false);
}

function echo_radio_sources($parent_en_id, $child_en_id, $enable_mulitiselect, $show_max = 25){

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
                'ln_parent_source_id' => $en_id,
                'ln_child_source_id' => $child_en_id,
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            )))>0 ? ' active ' : '' ). '"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'<span class="change-results"></span></a>';
        $count++;
    }


    //Did we have too many items?
    if($count>=$show_max){
        //Show "Show more" button
        $ui .= '<a href="javascript:void(0);" class="list-group-item itemsource itemsetting montserrat extra-items-'.$parent_en_id.'" onclick="$(\'.extra-items-'.$parent_en_id.'\').toggleClass(\'hidden\')"><span class="icon-block"><i class="fas fa-plus-circle"></i></span>Show '.($count-$show_max).' more</a>';
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
                    'ln_parent_source_id' => $group_en_id,
                    'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Source Links
                    'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                    'en_status_source_id IN (' . join(',', $CI->config->item('en_ids_7357')) . ')' => null, //Source Status Public
                ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');

                $subset_total += $child_links[0]['totals'];

            }

            $total_count += $subset_total;

            $inner_ui .= '<tr>';
            $inner_ui .= '<td style="text-align: left;"><span class="icon-block">' . $people_group['m_icon'] . '</span><a href="/source/'.$group_en_id.'">'.$people_group['m_name'].'</a></td>';
            $inner_ui .= '<td style="text-align: right;"><a href="/ledger?ln_status_source_id='.join(',', $CI->config->item('en_ids_7359')) /* Transaction Status Public */.'&ln_type_source_id='.join(',', $CI->config->item('en_ids_4592')).'&ln_parent_source_id=' . join(',', $CI->config->item('en_ids_'.$group_en_id)) . '">' . number_format($subset_total, 0) . '</a></td>';
            $inner_ui .= '</tr>';

        } else {

            //Do a child count:
            $child_links = $CI->READ_model->ln_fetch(array(
                'ln_parent_source_id' => $group_en_id,
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Source Links
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
                'en_status_source_id IN (' . join(',', $CI->config->item('en_ids_7357')) . ')' => null, //Source Status Public
            ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');

            $total_count += $child_links[0]['totals'];

            $inner_ui .= '<tr>';
            $inner_ui .= '<td style="text-align: left;"><span class="icon-block">' . $people_group['m_icon'] . '</span><a href="/source/'.$group_en_id.'">' . $people_group['m_name'] . '</a></td>';
            $inner_ui .= '<td style="text-align: right;"><a href="/ledger?ln_status_source_id='.join(',', $CI->config->item('en_ids_7359')) /* Transaction Status Public */.'&ln_type_source_id='.join(',', $CI->config->item('en_ids_4592')).'&ln_parent_source_id=' . $group_en_id . '">' . number_format($child_links[0]['totals'], 0) . '</a></td>';
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
    if(!isset($in_ln['ln_metadata']) || !isset($in_ln['ln_type_source_id'])){
        return false;
    }

    //prep metadata:
    $ln_metadata = unserialize($in_ln['ln_metadata']);

    //Return mark:
    return ( $in_ln['ln_type_source_id'] == 4228 ? ( !isset($ln_metadata['tr__assessment_points']) || $ln_metadata['tr__assessment_points'] == 0 ? '' : '<span class="score-range">[<span style="'.( $ln_metadata['tr__assessment_points']>0 ? 'font-weight:bold;' : ( $ln_metadata['tr__assessment_points'] < 0 ? 'font-weight:bold;' : '' )).'">' . ( $ln_metadata['tr__assessment_points'] > 0 ? '+' : '' ) . $ln_metadata['tr__assessment_points'].'</span>]</span>' ) : '<span class="score-range">['.$ln_metadata['tr__conditional_score_min'] . ( $ln_metadata['tr__conditional_score_min']==$ln_metadata['tr__conditional_score_max'] ? '' : '-'.$ln_metadata['tr__conditional_score_max'] ).'%]</span>' );

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

    //Check if source is a blog author:
    return count($CI->READ_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
            'ln_type_source_id' => 4983,
            'ln_child_blog_id' => $in_id,
            'ln_parent_source_id' => $session_en['en_id'],
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
            'in_status_source_id IN (' . join(',', $CI->config->item('en_ids_7355')) . ')' => null, //Blog Status Public
        ), 0, 0, array(), 'COUNT(in_id) as total_public_blogs');

        //$ui .= this as the main title:
        $ui .= '<tr>';
        $ui .= '<td style="text-align: left;"><span class="icon-block">'.$in_type['m_icon'].'</span><a href="/source/'.$type_en_id.'">'.$in_type['m_name'].'</a></td>';
        $ui .= '<td style="text-align: right;"><a href="/ledger?ln_type_source_id=4250&in_status_source_id=' . join(',', $CI->config->item('en_ids_7356')) . '&'.$in_field_name.'='.$type_en_id.'" data-toggle="tooltip" data-placement="top" title="'.number_format($in_count[0]['total_public_blogs'], 0).' Blog'.echo__s($in_count[0]['total_public_blogs']).'">'.number_format($in_count[0]['total_public_blogs']/$addup_total_count*100, 1).'%</a></td>';
        $ui .= '</tr>';

    }

    $ui .= '</table>';

    return $ui;
}


function echo_2level_stats($stat_name, $stats_en_id, $mother_en_id, $link_types_counts, $addup_total_count, $link_field, $focus_field){

    $CI =& get_instance();

    echo '<table class="table table-sm table-striped stats-table mini-stats-table">';

    echo '<tr class="panel-title down-border">';
    echo '<td style="text-align: left;">'.$stat_name.'</td>';
    if($focus_field=='total_coins'){
        echo '<td style="text-align: right;">COINS</td>';
    } else {
        echo '<td>&nbsp;</td>';
    }
    echo '</tr>';

    $all_shown = array();
    foreach ($CI->config->item('en_all_'.$stats_en_id) as $en_id => $m) {
        echo_2level_sources($m, $CI->config->item('en_all_'.$en_id), $link_types_counts, $all_shown, $link_field, 'en_all_'.$mother_en_id, $addup_total_count, $focus_field);
        $all_shown = array_merge($all_shown, $CI->config->item('en_ids_'.$en_id));
    }

    //Turn into array:
    $remaining_child = array();
    foreach($CI->config->item('en_all_'.$mother_en_id) as $en_id => $m){
        $remaining_child[$en_id] = $m;
    }

    //Display RemainingIF ANY:
    echo_2level_sources(array(
        'm_icon' => '<i class="fas fa-plus-circle"></i>',
        'm_name' => 'Others',
        'm_desc' => 'What is left',
    ), $remaining_child, $link_types_counts, $all_shown, $link_field, 'en_all_'.$mother_en_id, $addup_total_count, $focus_field);

    echo '</table>';

}


function echo_2level_sources($main_obj, $all_link_types, $link_types_counts, $all_shown, $link_field, $details_en, $addup_total_count, $focus_field){

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

        $ln = filter_array($link_types_counts, 'in_type_source_id', $en_id);
        $show_in_advance_only = ($focus_field=='total_coins' && abs($ln[$focus_field]) < 1 );

        if( !$ln['total_count'] ){
            continue;
        }

        array_push($all_link_type_ids, $en_id);

        //Addup counter:
        if($focus_field=='total_count'){
            $total_sum += $ln['total_count'];
        } elseif($focus_field=='total_coins'){
            $total_sum += abs($ln['total_coins']);
        }

        //Subrow UI:
        $rows =  '<tr class="hidden ' . $identifier . '">';


        if(!isset($en_all_detail[$en_id])){

            $rows .= '<td style="text-align: left; padding-left:30px;" colspan="2">MISSING @'.$en_id.' as Link Type</td>';

        } else {

            $rows .= '<td style="text-align: left;" class="'.( $show_in_advance_only ? superpower_active(10967) : '' ).'">';
            $rows .= '<span class="icon-block" style="margin-left:8px;">'.$m['m_icon'].'</span>';
            $rows .= '<a href="/source/'.$en_id.'">'.$m['m_name'].'</a>';
            $rows .= '</td>';


            $rows .= '<td style="text-align: right;" class="'.( $show_in_advance_only ? superpower_active(10967) : '' ).'">';
            if($focus_field=='total_count'){

                $rows .= '<a href="/ledger?ln_status_source_id='.join(',', $CI->config->item('en_ids_7359')) /* Transaction Status Public */.'&'.$link_field.'=' . $en_id . '" data-toggle="tooltip" data-placement="top" title="'.number_format($ln['total_count'], 0).' Blog'.echo__s($ln['total_count']).'">'.number_format($ln['total_count']/$addup_total_count*100, 1) . '%</a>';

            } elseif($focus_field=='total_coins'){

                $rows .= '<a href="/ledger?ln_status_source_id='.join(',', $CI->config->item('en_ids_7359')) /* Transaction Status Public */.'&'.$link_field.'=' . $en_id . '" data-toggle="tooltip" data-placement="top" title="'.number_format($ln['total_coins'], 0).' Coin'.echo__s($ln['total_coins']).'">'.number_format($ln['total_coins'], 0) . '</a>';

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

    if($focus_field=='total_count'){

        echo '<a href="/ledger?ln_status_source_id='.join(',', $CI->config->item('en_ids_7359')) /* Transaction Status Public */.'&'.$link_field.'=' . join(',' , $all_link_type_ids) . '" data-toggle="tooltip" data-placement="top" title="'.number_format($total_sum, 0).' Blog'.echo__s($total_sum).'">'.number_format($total_sum/$addup_total_count*100, 1).'%</a>';

    } elseif($focus_field=='total_coins'){

        echo '<a href="/ledger?ln_status_source_id='.join(',', $CI->config->item('en_ids_7359')) /* Transaction Status Public */.'&'.$link_field.'=' . join(',' , $all_link_type_ids) . '" data-toggle="tooltip" data-placement="top" title="'.number_format($total_sum, 0).' Coin'.echo__s($total_sum).'">'.number_format($total_sum, 0).'</a>';

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
    $en_all_4737 = $CI->config->item('en_all_4737'); //BLOG STATUS
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
    $is_published = in_array($in['in_status_source_id'], $CI->config->item('en_ids_7355'));
    $is_link_published = in_array($in['ln_status_source_id'], $CI->config->item('en_ids_7359'));
    $is_blog_link = in_array($in['ln_type_source_id'], $CI->config->item('en_ids_4486'));
    $is_author = ( !$is_blog_link ? false : $is_author ); //Disable Edits on Blog List Page

    $ui = '<div in-link-id="' . $ln_id . '" in-tr-type="' . $in['ln_type_source_id'] . '" blog-id="' . $in['in_id'] . '" parent-blog-id="' . $in_linked_id . '" class="list-group-item no-side-padding itemblog blogs_sortable level2_in object_highlight highlight_in_'.$in['in_id'] . ' blog_line_' . $in['in_id'] . ( $is_parent ? ' parent-blog ' : '' ) . ' in__tr_'.$ln_id.'" style="padding-left:0;">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="'.( $session_en ? 'MENCHcolumn1' : 'MENCHcolumnFull' ).'">';


        $ui .= '<div class="inline-block">';
            //BLOG ICON:
            $ui .= '<span class="icon-block"><a href="/blog/'.$in['in_id'].'">' . $en_all_2738[4535]['m_icon'] . '</a></span>';

            //BLOG TITLE
            if($is_blog_link && superpower_active(10984, true)){
                $ui .= echo_in_text(4736, $in['in_title'], $in['in_id'], $is_author, (($in['ln_order']*100)+1));
            } else {
                $ui .= '<a href="/blog/'.$in['in_id'].'" class="title-block montserrat">' . echo_in_title($in) . '</a>';
            }
        $ui .= '</div>';




        //SECOND STATS ROW
        $ui .= '<div class="doclear">&nbsp;</div>';

        $ui .= '<div class="space-content">';


        //LINK STATUS (IF NOT PUBLISHED, SHOULD NOT HAPPEN!)
        $ui .= '<span class="icon-block ln_status_source_id_' . $ln_id . ( $is_link_published ? ' hidden ' : '' ) . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6186[$in['ln_status_source_id']]['m_name'].' @'.$in['ln_status_source_id'].': '.$en_all_6186[$in['ln_status_source_id']]['m_desc'].'">' . $en_all_6186[$in['ln_status_source_id']]['m_icon'] . ' </span></span>';


        //BLOG IF NOT PUBLISHED:
        if(!superpower_active(10984, true) && !$is_published){
            //Show them Blog status in this case:
            $ui .= '<div class="inline-block">' . echo_in_dropdown(4737, $in['in_status_source_id'], null, $is_author, false, $in['in_id']) . ' </div>';
        }


        //Blog Brush
        $ui .= '<div class="inline-block ' . superpower_active(10984) . '">';

            //BLOG STATUS
            $ui .= '<div class="inline-block">' . echo_in_dropdown(4737, $in['in_status_source_id'], null, $is_author, false, $in['in_id']) . ' </div>';

            //BLOG TYPE
            $ui .= echo_in_dropdown(7585, $in['in_type_source_id'], null, $is_author, false, $in['in_id']);

            //BLOG READ TIME
            $ui .= echo_in_text(4356, $in['in_read_time'], $in['in_id'], $is_author, ($in['ln_order']*10)+1);


            //PREVIOUS & NEXT BLOGS
            $previous_ins = $CI->READ_model->ln_fetch(array(
                'ln_child_blog_id' => $in['in_id'],
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_blogs');
            $next_blogs = $CI->READ_model->ln_fetch(array(
                'ln_parent_blog_id' => $in['in_id'],
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Blog-to-Blog Links
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
            ), array(), 0, 0, array(), 'COUNT(ln_id) as total_blogs');


            //Previous blog:
            $ui .= '<span class="montserrat blog blog-previous">' . ( $previous_ins[0]['total_blogs'] >= 2 ? $previous_ins[0]['total_blogs'] . $en_all_12413[11019]['m_icon'] : '&nbsp;') . '</span>';

            //Next Blogs:
            $ui .= '<span class="montserrat blog blog-next">' . ( $next_blogs[0]['total_blogs'] > 0 ? $en_all_12413[11020]['m_icon'] . $next_blogs[0]['total_blogs']: '&nbsp;' ) . '</span>';


        $ui .= '</div>';







        //Blog Wand
        $ui .= '<div class="inline-block ' . superpower_active(10985) . '">';
            //LINK TYPE
            $ui .= echo_in_dropdown(4486, $in['ln_type_source_id'], null, $is_author, false, $in['in_id'], $in['ln_id']);

            //LINK MARKS
            $ui .= '<span class="link_marks settings_4228 '.( $in['ln_type_source_id']==4228 ? : 'hidden' ).'">';
            $ui .= echo_in_text(4358, ( isset($ln_metadata['tr__assessment_points']) ? $ln_metadata['tr__assessment_points'] : '' ), $in['ln_id'], $is_author, ($in['ln_order']*10)+2 );
            $ui .='</span>';


            //LINK CONDIITONAL RANGE
            $ui .= '<span class="link_marks settings_4229 '.( $in['ln_type_source_id']==4229 ? : 'hidden' ).'">';
            //MIN
            $ui .= echo_in_text(4735, ( isset($ln_metadata['tr__conditional_score_min']) ? $ln_metadata['tr__conditional_score_min'] : '' ), $in['ln_id'], $is_author, ($in['ln_order']*10)+3);
            //MAX
            $ui .= echo_in_text(4739, ( isset($ln_metadata['tr__conditional_score_max']) ? $ln_metadata['tr__conditional_score_max'] : '' ), $in['ln_id'], $is_author, ($in['ln_order']*10)+4);
            $ui .= '</span>';
        $ui .= '</div>';







        $ui .= '</div>';

    $ui .= '</td>';




    //READ
    $echo_in_stat_read = echo_in_stat_read($in);
    $ui .= '<td class="MENCHcolumn2 read '.( !$echo_in_stat_read ? 'show-max' : '' ).'">';
    $ui .= $echo_in_stat_read;
    $ui .= '</td>';




    //SOURCE
    $ui .= '<td class="MENCHcolumn3 source">';

    //RIGHT EDITING:
    $ui .= '<div class="pull-right inline-block">';
    $ui .= '<div class="note-edit edit-off '.superpower_active(10939).'">';

    $ui .= '<span class="show-on-hover">';

    if($is_blog_link){
        if($is_author || !$is_parent){

            if($is_author && !$is_parent){
                $ui .= '<span title="Drag up/down to sort" data-toggle="tooltip" data-placement="left"><i class="fas fa-sort black blog-sort-handle"></i></span>';
            }

            //Unlink:
            $ui .= '<span title="Unlink Blog '.$in['ln_type_source_id'].'" data-toggle="tooltip" data-placement="left"><a href="javascript:void(0);" onclick="in_unlink('.$in['in_id'].', '.$in['ln_id'].')"><i class="fas fa-unlink black"></i></a></span>';

        } elseif(!$is_author) {

            //Indicate if NOT an author:
            $ui .= '<span data-toggle="tooltip" title="You are not yet an author of this blog" data-placement="bottom"><i class="fas fa-user-minus read"></i></span>';

        }
    }

    $ui .= '</span>';
    $ui .= '</div>';
    $ui .= '</div>';


    //SOURCE STATS
    $ui .= echo_in_stat_source($in['in_id'], 0);

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
        $max_and_list = ( $push_message ? 5 : 0 );
        $common_prefix = common_prefix($in__children, 'in_title', $in, $max_and_list);
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




        foreach($in__children as $key => $child_in){

            if($push_message){

                $message_content .= ($key+1).'. '.echo_in_title($child_in, $push_message, $common_prefix)."\n";


                //We know that the $next_step_message length cannot surpass the limit defined by facebook
                if (($key >= $max_and_list || strlen($message_content) > (config_var(11074) - 150))) {
                    //We cannot add any more, indicate truncating:
                    $remainder = count($in__children) - $max_and_list;
                    $message_content .= "\n\n".'... plus ' . $remainder . ' more read' . echo__s($remainder) . '.';
                    break;
                }

            } else {

                echo echo_in_read($child_in, false, null, $common_prefix);

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

        }
    }

    if(!$push_message){
        echo '</div>';
    }

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
        echo echo_in_read_previous($in_id, $recipient_en);

        //NEXT:
        $en_all_11035 = $CI->config->item('en_all_11035'); //MENCH  NAVIGATION
        echo '<div class="inline-block margin-top-down previous_reads"><a class="btn btn-read" href="/'.$in_id.'/next">'.$en_all_11035[12211]['m_name'].' '.$en_all_11035[12211]['m_icon'].'</a></div>';

    }

}

function echo_in_read_previous($in_id, $recipient_en){

    if(!$recipient_en || $recipient_en['en_id'] < 1){
        return null;
    }

    //Now fetch the parent of the current
    $ui = null;
    $CI =& get_instance();
    $en_all_4737 = $CI->config->item('en_all_4737'); // Blog Status
    $en_all_2738 = $CI->config->item('en_all_2738');

    //READ LIST
    $player_read_ids = $CI->READ_model->read_ids($recipient_en['en_id']);
    $recursive_parents = $CI->BLOG_model->in_fetch_recursive_parents($in_id, true, true);

    foreach ($recursive_parents as $grand_parent_ids) {
        foreach(array_intersect($grand_parent_ids, $player_read_ids) as $intersect) {

            //Show the breadcrumb since it's connected:
            $ui .= '<div class="previous_reads hidden">';
            $ui .= '<div class="read-topic"><span class="icon-block"><i class="fas fa-step-backward"></i></span>SELECT PREVIOUS:</div>';
            $ui .= '<div class="list-group bottom-read-line">';

            $breadcrumb_items = array();

            foreach ($grand_parent_ids as $parent_in_id) {

                $ins_this = $CI->BLOG_model->in_fetch(array(
                    'in_id' => $parent_in_id,
                ));

                array_push($breadcrumb_items, echo_in_read($ins_this[0]));

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
        //Previous
        $ui .= '<div class="inline-block margin-top-down selected_before"><a class="btn btn-read" href="javascript:void(0);" onclick="$(\'.previous_reads\').toggleClass(\'hidden\');"><span class="previous_reads"><i class="fad fa-step-backward"></i></span><span class="previous_reads hidden"><i class="fas fa-times"></i></span></a>&nbsp;</div>';
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
            'ln_content' => 'echo_en() variable missing source',
            'ln_metadata' => $en,
            'ln_type_source_id' => 4246, //Platform Bug Reports
        ));
        return false;
    }
    $session_en = superpower_assigned();
    $en_all_6177 = $CI->config->item('en_all_6177'); //Source Status
    $en_all_4527 = $CI->config->item('en_all_4527');
    $en_all_2738 = $CI->config->item('en_all_2738');
    $en_all_11028 = $CI->config->item('en_all_11028'); //SOURCEERS LINKS DIRECTION

    $ln_id = (isset($en['ln_id']) ? $en['ln_id'] : 0);
    $is_source_link = ( $ln_id > 0 && in_array($en['ln_type_source_id'], $CI->config->item('en_ids_4592')));
    $is_read_progress = ( $ln_id > 0 && in_array($en['ln_type_source_id'], $CI->config->item('en_ids_12227')));
    $ui = null;

    $en__parents = $CI->READ_model->ln_fetch(array(
        'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Source Links
        'ln_child_source_id' => $en['en_id'], //This child source
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //Transaction Status Active
        'en_status_source_id IN (' . join(',', $CI->config->item('en_ids_7358')) . ')' => null, //Source Status Active
    ), array('en_parent'), 0, 0, array('en_name' => 'ASC'));

    $child_links = $CI->READ_model->ln_fetch(array(
        'ln_parent_source_id' => $en['en_id'],
        'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Source Links
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //Transaction Status Public
        'en_status_source_id IN (' . join(',', $CI->config->item('en_ids_7357')) . ')' => null, //Source Status Public
    ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as totals');

    $is_published = in_array($en['en_status_source_id'], $CI->config->item('en_ids_7357'));
    $is_link_published = ( $ln_id > 0 && in_array($en['ln_status_source_id'], $CI->config->item('en_ids_7359')));
    $is_hidden = filter_array($en__parents, 'en_id', '4755');

    if(!$session_en && ($is_hidden || !$is_published || !$is_link_published)){
        //Not logged in, so should only see published:
        return false;
    } elseif($is_hidden && !superpower_active(10986, true)){
        //They don't have the needed superpower:
        return false;
    }

    //ROW
    $ui .= '<div class="list-group-item no-side-padding itemsource en-item object_highlight '.( $is_hidden ? superpower_active(10986) : '' ).' highlight_en_'.$en['en_id'].' en___' . $en['en_id'] . ( $ln_id > 0 ? ' tr_' . $en['ln_id'].' ' : '' ) . ( $is_parent ? ' parent-source ' : '' ) . '" source-id="' . $en['en_id'] . '" en-status="' . $en['en_status_source_id'] . '" tr-id="'.$ln_id.'" ln-status="'.( $ln_id ? $en['ln_status_source_id'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="MENCHcolumn1">';



    $ui .= '<div class="inline-block">';


    //SOURCE ICON
    $ui .= '<a href="/source/'.$en['en_id'] . '"><span class="icon-block en_ui_icon_' . $en['en_id'] . ' en__icon_'.$en['en_id'].'" en-is-set="'.( strlen($en['en_icon']) > 0 ? 1 : 0 ).'">' . echo_en_icon($en['en_icon']) . '</span></a>';

    //SOURCE NAME
    $ui .= '<a href="/source/'.$en['en_id'] . '" class="title-block title-no-right montserrat '.extract_icon_color($en['en_icon']).'">'.($child_links[0]['totals'] > 0 ? '<span class="'.superpower_active(10983).'">'.echo_number($child_links[0]['totals']).'&nbsp;</span>' : '').'<span class="en_name_' . $en['en_id'] . '">'.$en['en_name'].'</span></a>';

    $ui .= '</div>';




    //Does this source also include a link?
    if ($ln_id > 0) {
        if($is_source_link && (strlen($en['ln_content']) || superpower_assigned(10983))){

            //SOURCE LINKS:
            $ui .= '<div class="doclear">&nbsp;</div>';

            $ui .= '<span class="message_content ln_content ln_content_' . $ln_id . '">' . echo_ln_urls($en['ln_content'] , $en['ln_type_source_id']) . '</span>';

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


    //SOURCE STATUS
    $ui .= '<span class="en_status_source_id_' . $en['en_id'] . ( $is_published ? ' hidden ' : '' ).'"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$en['en_status_source_id']]['m_name'].' @'.$en['en_status_source_id'].': '.$en_all_6177[$en['en_status_source_id']]['m_desc'].'">' . $en_all_6177[$en['en_status_source_id']]['m_icon'] . '</span>&nbsp;</span>';

    //LINK
    if ($ln_id > 0) {

        //Link Type Full List:
        $en_all_6186 = $CI->config->item('en_all_6186'); //Transaction Status

        //LINK STATUS
        $ui .= '<span class="ln_status_source_id_' . $ln_id . ( $is_link_published ? ' hidden ' : '' ) .'"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6186[$en['ln_status_source_id']]['m_name'].' @'.$en['ln_status_source_id'].': '.$en_all_6186[$en['ln_status_source_id']]['m_desc'].'">' . $en_all_6186[$en['ln_status_source_id']]['m_icon'] . '</span>&nbsp;</span>';

        //Show link index
        if($is_source_link && $en['ln_external_id'] > 0){

            //External ID
            if($en['ln_parent_source_id']==6196){
                //Give trainers the ability to ping Messenger profiles:
                $ui .= '<span class="'.superpower_active(10986).'" data-toggle="tooltip" data-placement="right" title="Link External ID = '.$en['ln_external_id'].' [Messenger Profile]"><a href="/read/messenger_fetch_profile/'.$en['ln_external_id'].'"><i class="fas fa-project-diagram"></i></a>&nbsp;</span>';
            } else {
                $ui .= '<span class="'.superpower_active(10986).'" data-toggle="tooltip" data-placement="right" title="Link External ID = '.$en['ln_external_id'].'"><i class="fas fa-project-diagram"></i>&nbsp;</span>';
            }

        }

    }



    $ui .= '<span class="'. superpower_active(10986) .'">';

    if($is_source_link){
        //Link Type
        $en_all_4592 = $CI->config->item('en_all_4592');
        $ui .= '<span class="icon-block-img ln_type_' . $ln_id . superpower_active(10986).'" data-toggle="tooltip" data-placement="bottom" title="LINK ID '.$en['ln_id'].' '.$en_all_4592[$en['ln_type_source_id']]['m_name'].' @'.$en['ln_type_source_id'].'">' . $en_all_4592[$en['ln_type_source_id']]['m_icon'] . '</span> ';
    }

    foreach ($en__parents as $en_parent) {
        $ui .= '<span class="icon-block-img en_child_icon_' . $en_parent['en_id'] . '"><a href="/source/' . $en_parent['en_id'] . '" data-toggle="tooltip" title="' . $en_parent['en_name'] . (strlen($en_parent['ln_content']) > 0 ? ' = ' . $en_parent['ln_content'] : '') . '" data-placement="bottom">' . echo_en_icon($en_parent['en_icon']) . '</a></span> ';
    }
    $ui .= ' </span>';

    $ui .= '</div>';



    $ui .= '</td>';









    //READ
    $read_ui = '<td class="MENCHcolumn2 read">';
    $read_ui .= echo_in_stat_read(array(), $en);
    $read_ui .= '</td>';






    //BLOG
    $blog_ui = '<td class="MENCHcolumn3 source">';

    //RIGHT EDITING:
    $blog_ui .= '<div class="pull-right inline-block">';
    $blog_ui .= '<div class="note-edit edit-off '.superpower_active(10967).'">';
    $blog_ui .= '<span class="show-on-hover">';
    $blog_ui .= '<span title="Modify Source" data-toggle="tooltip" data-placement="left"><a href="javascript:void(0);" onclick="en_modify_load(' . $en['en_id'] . ',' . $ln_id . ')"><i class="fas fa-cog black"></i></a></span>';
    $blog_ui .= '</span>';
    $blog_ui .= '</div>';
    $blog_ui .= '</div>';

    $blog_ui .= echo_in_stat_source(0, $en['en_id']);
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


    $tooltip_span_start = '<span class="'.( !$is_author ? 'edit-locked' : '' ).'" '.( !$is_blog_title_lg || !$is_author ? 'data-toggle="tooltip" data-placement="bottom" title="'.$en_all_12112[$cache_en_id]['m_name'].'"' : '').'>';
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
    $ui = '<div class="dropdown inline-block dropd_'.$cache_en_id.'_'.$in_id.'_'.$ln_id.' '.( !$show_full_name ? ' icon-block ' : '' ).'" selected-val="'.$selected_en_id.'">';
    $ui .= '<button type="button" '.( $is_author ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' '.$btn_class.'" id="dropdownMenuButton'.$cache_en_id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked  '.$btn_class.'"' ).' >';
    $ui .= '<span title="'.$en_all_12079[$cache_en_id]['m_name'].'" data-toggle="tooltip" data-placement="right">';
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
                $href = 'href="/source/'.$session_en['en_id'].'"';
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

