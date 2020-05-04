<?php

function echo_en_load_more($page, $limit, $en__portfolios_count)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $ui = '<div class="load-more montserrat list-group-item itemsource no-left-padding"><a href="javascript:void(0);" onclick="en_load_next_page(' . $page . ', 0)">';

    //Regular section:
    $max_sources = (($page + 1) * $limit);
    $max_sources = ($max_sources > $en__portfolios_count ? $en__portfolios_count : $max_sources);
    $ui .= '<span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source">SEE MORE</b>';
    $ui .= '</a></div>';

    return $ui;
}

function echo_db_field($field_name){

    //Takes a database field name and returns a human-friendly version

    $prefix = substr($field_name, 0, 3);

    if($prefix == 'ln_'){
        $name = 'Transaction';
    } elseif($prefix == 'in_'){
        $name = 'Idea';
    } elseif($prefix == 'en_'){
        $name = 'Source';
    } else {
        return false;
    }

    return ucwords(
        str_replace('_', ' ',
            str_replace('_id', '',
                str_replace('_source_id', '',
                    str_replace('_idea_id', '',
                        //Start here:
                        str_replace($prefix, $name.' ', $field_name)
                    )
                )
            )
        )
    );

}


function echo_ln_content($ln_content, $ln_type_source_id, $ln_content_append = null)
{

    /*
     *
     * Displays Source Links
     * https://mench.com/source/4592
     *
     * $ln_content_append Would be the additional message
     * in an idea message that would be passed down
     * to the source profile $ln_content value.
     *
     * */


    if ($ln_type_source_id == 4256 /* Generic URL */) {

        return '<div class="block"><a href="' . $ln_content . '" target="_blank"><span class="icon-block-xs inline-block"><i class="far fa-external-link"></i></span><span class="url_truncate">' . echo_url_clean($ln_content) . '</span></a></div>';

    } elseif ($ln_type_source_id == 4257 /* Embed Widget URL? */) {

        return echo_url_embed($ln_content);

    } elseif ($ln_type_source_id == 4260 /* Image URL */) {

        $current_mench = current_mench();
        if($current_mench['x_name']=='source'){
            return '<a href="' . $ln_content . '"><img data-src="' . $ln_content . '" src="/img/mench.png" alt="IMAGE" class="content-image lazyimage" /></a>';
        } else {
            return '<img data-src="' . $ln_content . '" src="/img/mench.png" alt="IMAGE" class="content-image lazyimage" />';
        }

    } elseif ($ln_type_source_id == 4259 /* Audio URL */) {

        return  '<audio controls><source src="' . $ln_content . '" type="audio/mpeg"></audio>' ;

    } elseif ($ln_type_source_id == 4258 /* Video URL */) {

        return  '<video width="100%" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="' . $ln_content . '" type="video/mp4"></video>' ;

    } elseif ($ln_type_source_id == 4261 /* File URL */) {

        return '<a href="' . $ln_content . '" class="btn btn-idea" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';

    } elseif(strlen($ln_content) > 0) {

        return htmlentities($ln_content);

    } else {

        //UNKNOWN
        return false;

    }
}




function echo_url_embed($url, $full_message = null, $return_array = false)
{


    /*
     *
     * Detects and displays URLs from supported website with an embed widget
     *
     * Alert: Changes to this function requires us to re-calculate all current
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

    if(is_https_url($url)){

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

                $embed_html_code .= '<div class="media-content"><div class="yt-container video-sorting" style="margin-top:5px;">'.($end_sec ? '<span class="media-info mid-right" title="Video Clip from '.echo_time_hours($start_sec).' to '.echo_time_hours($end_sec).'" data-toggle="tooltip" data-placement="top">'.echo_time_hours($end_sec - $start_sec).'</span>' : '').'<iframe src="//www.youtube.com/embed/' . $video_id . '?wmode=opaque&theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_sec . ($end_sec ? '&end=' . $end_sec : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div></div>';

            }

        } elseif (substr_count($url, 'vimeo.com/') == 1 && is_numeric(one_two_explode('vimeo.com/','?',$url))) {

            //Seems to be Vimeo:
            $video_id = trim(one_two_explode('vimeo.com/', '?', $url));

            //This should be an integer!
            if (intval($video_id) == $video_id) {
                $clean_url = 'https://vimeo.com/' . $video_id;
                $embed_html_code = '<div class="media-content"><div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://player.vimeo.com/video/' . $video_id . '?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div></div>';
            }

        } elseif (substr_count($url, 'wistia.com/medias/') == 1) {

            //Seems to be Wistia:
            $video_id = trim(one_two_explode('wistia.com/medias/', '?', $url));
            $clean_url = trim(one_two_explode('', '?', $url));
            $embed_html_code = '<script src="https://fast.wistia.com/embed/medias/' . $video_id . '.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding video-sorting" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_' . $video_id . ' seo=false videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>';

        }
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

function echo_in_title($in, $common_prefix = null){
    if(strlen($common_prefix) > 0){
        $in['in_title'] = trim(substr($in['in_title'], strlen($common_prefix)));
    }
    return '<span class="text__4736_'.$in['in_id'].'">'.htmlentities(trim($in['in_title'])).'</span>';
}


function echo_in_notes($ln)
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
    $en_all_4485 = $CI->config->item('en_all_4485'); //IDEA NOTES


    //Transaction Status
    $en_all_6186 = $CI->config->item('en_all_6186');


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item itemidea is-msg note_sortable msg_en_type_' . $ln['ln_type_source_id'] . '" id="ul-nav-' . $ln['ln_id'] . '" tr-id="' . $ln['ln_id'] . '">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="text_message edit-off" id="msgbody_' . $ln['ln_id'] . '">';
    $ui .= $CI->COMMUNICATION_model->send_message($ln['ln_content'], $session_en, $ln['ln_next_idea_id']);
    $ui .= '</div>';

    //Editing menu:
    $ui .= '<div class="note-editor edit-off '.superpower_active(10939).'"><span class="show-on-hover">';

        //Sort:
        if(in_array(4603, $en_all_4485[$ln['ln_type_source_id']]['m_parents'])){
            $ui .= '<span title="SORT"><i class="fas fa-bars '.( in_array(4603, $en_all_4485[$ln['ln_type_source_id']]['m_parents']) ? 'in_notes_sorting' : '' ).'"></i></span>';
        }

        //Modify:
        $ui .= '<span title="MODIFY"><a href="javascript:in_notes_modify_start(' . $ln['ln_id'] . ');"><i class="fas fa-pen-square"></i></a></span>';

    $ui .= '</span></div>';


    //Text editing:
    $ui .= '<textarea onkeyup="in_edit_notes_count(' . $ln['ln_id'] . ')" name="ln_content" id="message_body_' . $ln['ln_id'] . '" class="edit-on hidden msg note-textarea algolia_search" placeholder="'.stripslashes($ln['ln_content']).'">' . $ln['ln_content'] . '</textarea>';


    //Editing menu:
    $ui .= '<ul class="msg-nav '.superpower_active(10939).'">';

    //Counter:
    $ui .= '<li class="edit-on hidden"><span id="ideaNoteCount' . $ln['ln_id'] . '"><span id="charEditingNum' . $ln['ln_id'] . '">0</span>/' . config_var(4485) . '</span></li>';

    //Save Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-idea white-third" href="javascript:in_notes_modify_save(' . $ln['ln_id'] . ',' . $ln['ln_type_source_id'] . ');" title="Save changes" data-toggle="tooltip" data-placement="top"><i class="fas fa-check"></i> Save</a></li>';

    //Cancel Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-idea white-third" href="javascript:in_notes_modify_cancel(' . $ln['ln_id'] . ');" title="Cancel editing" data-toggle="tooltip" data-placement="top"><i class="fas fa-times"></i></a></li>';

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


function echo_number($number)
{

    //Round & format numbers

    if ($number < 950) {
        return intval($number);
    }

    if ($number >= 950000000) {
        $formatting = array(
            'multiplier' => (1 / 1000000000),
            'decimals' => 1,
            'suffix' => 'B',
        );
    } elseif ($number >= 9500000) {
        $formatting = array(
            'multiplier' => (1 / 1000000),
            'decimals' => 0,
            'suffix' => 'M',
        );
    } elseif ($number >= 950000) {
        $formatting = array(
            'multiplier' => (1 / 1000000),
            'decimals' => 1,
            'suffix' => 'M',
        );
    } elseif ($number >= 9500) {
        $formatting = array(
            'multiplier' => (1 / 1000),
            'decimals' => 0,
            'suffix' => 'K',
        );
    } else {
        $formatting = array(
            'multiplier' => (1 / 1000),
            'decimals' => 1,
            'suffix' => 'K',
        );
    }

    return round(($number * $formatting['multiplier']), $formatting['decimals']) . $formatting['suffix'];

}


function echo_ln($ln, $is_parent_tr = false)
{

    $CI =& get_instance();
    $en_all_4593 = $CI->config->item('en_all_4593'); //Link Type
    $en_all_4341 = $CI->config->item('en_all_4341'); //Link Table
    $en_all_2738 = $CI->config->item('en_all_2738');
    $en_all_6186 = $CI->config->item('en_all_6186'); //Transaction Status
    $session_en = superpower_assigned();



    if(!isset($en_all_4593[$ln['ln_type_source_id']])){
        //We've probably have not yet updated php cache, set error:
        $en_all_4593[$ln['ln_type_source_id']] = array(
            'm_icon' => '<i class="fas fa-exclamation-circle"></i>',
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



    //COINS AWARDED?
    if(in_array($ln['ln_type_source_id'], $CI->config->item('en_ids_6255'))){
        $coin_type = 'read';
    } elseif(in_array($ln['ln_type_source_id'], $CI->config->item('en_ids_12274'))){
        $coin_type = 'source';
    } elseif(in_array($ln['ln_type_source_id'], $CI->config->item('en_ids_12273')) && $ln['ln_profile_source_id']>0){
        $coin_type = 'idea';
    } else {
        $coin_type = null;
    }

    //Transaction Type & Coins
    $ui .= '<div class="simple-line"><a href="/source/'.$ln['ln_type_source_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4593]['m_name'].( strlen($en_all_4593[$ln['ln_type_source_id']]['m_desc']) ? ': '.$en_all_4593[$ln['ln_type_source_id']]['m_desc'] : '' ).'" class="montserrat"><span class="icon-block">'.$en_all_4341[4593]['m_icon']. '</span><span class="'.extract_icon_color($en_all_4593[$ln['ln_type_source_id']]['m_icon']).'">'. $en_all_4593[$ln['ln_type_source_id']]['m_icon'] . '&nbsp;' . $en_all_4593[$ln['ln_type_source_id']]['m_name'] . '</span>'.($coin_type ? '&nbsp;<span title="'.$coin_type.' coin awarded" data-toggle="tooltip" data-placement="top"><i class="fas fa-circle '.$coin_type.'"></i></span>' : '').'</a></div>';


    //Hide Sensitive Details?
    if(in_array($ln['ln_type_source_id'] , $CI->config->item('en_ids_4755')) && (!$session_en || $ln['ln_creator_source_id']!=$session_en['en_id']) && !superpower_active(12701, true)){

        //Hide Information:
        $ui .= '<div class="simple-line"><span data-toggle="tooltip" class="montserrat" data-placement="top" title="Details are kept private"><span class="icon-block"><i class="fal fa-eye-slash"></i></span>PRIVATE INFORMATION</span></div>';

    } else {

        //Metadata
        if(strlen($ln['ln_metadata']) > 0){
            $ui .= '<div class="simple-line"><a href="/plugin/12722?ln_id=' . $ln['ln_id'] . '" class="montserrat"><span class="icon-block">'.$en_all_4341[6103]['m_icon']. '</span>'.$en_all_4341[6103]['m_name']. '</a></div>';
        }

        //External ID
        if($ln['ln_external_id'] > 0){
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[7694]['m_name'].'"><span class="icon-block">'.$en_all_4341[7694]['m_icon']. '</span>'.$ln['ln_external_id'].'</span></div>';
        }

        //Order
        if($ln['ln_order'] > 0){
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4370]['m_name']. '"><span class="icon-block">'.$en_all_4341[4370]['m_icon']. '</span>'.echo_ordinal_number($ln['ln_order']).'</span></div>';
        }


        //Message
        if(strlen($ln['ln_content']) > 0 && $ln['ln_content']!='@'.$ln['ln_profile_source_id']){
            $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4372]['m_name'].'"><span class="icon-block">'.$en_all_4341[4372]['m_icon'].'</span><div class="title-block ledger-msg">'.htmlentities($ln['ln_content']).'</div></div>';
        }


        //Creator (Do not repeat)
        if($ln['ln_creator_source_id'] > 0 && $ln['ln_creator_source_id']!=$ln['ln_profile_source_id'] && $ln['ln_creator_source_id']!=$ln['ln_portfolio_source_id']){

            $player_ens = $CI->SOURCE_model->en_fetch(array(
                'en_id' => $ln['ln_creator_source_id'],
            ));

            $ui .= '<div class="simple-line"><a href="/source/'.$player_ens[0]['en_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[4364]['m_name'].'" class="montserrat"><span class="icon-block">'.$en_all_4341[4364]['m_icon']. '</span><span class="'.extract_icon_color($player_ens[0]['en_icon']).'"><span class="img-block">'.echo_en_icon($player_ens[0]['en_icon']) . '</span> ' . $player_ens[0]['en_name'] . '</span></a></div>';

        }

    }


    //5x Relations:
    if(!$is_parent_tr){

        $en_all_6232 = $CI->config->item('en_all_6232'); //PLATFORM VARIABLES
        foreach($CI->config->item('en_all_10692') as $en_id => $m) {

            //Do we have this set?
            if(!intval($ln[$en_all_6232[$en_id]['m_desc']])){
                continue;
            }

            if(in_array(6160 , $m['m_parents'])){

                //SOURCE
                $ens = $CI->SOURCE_model->en_fetch(array('en_id' => $ln[$en_all_6232[$en_id]['m_desc']]));

                $ui .= '<div class="simple-line"><a href="/source/'.$ens[0]['en_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[$en_id]['m_name'].'" class="montserrat"><span class="icon-block">'.$en_all_4341[$en_id]['m_icon']. '</span>'.( $ln[$en_all_6232[$en_id]['m_desc']]==$ln['ln_creator_source_id'] ? $en_all_4341[4364]['m_icon']. '&nbsp;' : '' ).'<span class="'.extract_icon_color($ens[0]['en_icon']).' img-block">'.echo_en_icon($ens[0]['en_icon']). '&nbsp;'.$ens[0]['en_name'].'</span></a></div>';

            } elseif(in_array(6202 , $m['m_parents'])){

                //IDEA
                $ins = $CI->IDEA_model->in_fetch(array('in_id' => $ln[$en_all_6232[$en_id]['m_desc']]));

                $ui .= '<div class="simple-line"><a href="/idea/go/'.$ins[0]['in_id'].'" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[$en_id]['m_name'].'" class="montserrat"><span class="icon-block">'.$en_all_4341[$en_id]['m_icon']. '</span>'.$en_all_2738[4535]['m_icon']. '&nbsp;'.echo_in_title($ins[0]).'</a></div>';

            } elseif(in_array(4367 , $m['m_parents'])){

                //PARENT TRANSACTION
                $lns = $CI->LEDGER_model->ln_fetch(array('ln_id' => $ln[$en_all_6232[$en_id]['m_desc']]));

                $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$en_all_4341[$en_id]['m_name'].'">'.$en_all_4341[$en_id]['m_icon']. '</span><div class="transaction-ref">'.echo_ln($lns[0], true).'</div></div>';

            }
        }
    }


    $ui .= '</div>';

    return $ui;
}


function echo_url_clean($url)
{
    //Returns the watered-down version of the URL for a cleaner UI:
    return rtrim(str_replace('http://', '', str_replace('https://', '', str_replace('www.', '', $url))), '/');
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

    foreach($time_units as $unit => $period) {
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



function echo_coins_count_read($in_id = 0, $en_id = 0){

    $CI =& get_instance();
    $read_coins = $CI->LEDGER_model->ln_fetch(array(
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_6255')) . ')' => null,
        ( $in_id > 0 ? 'ln_previous_idea_id' : 'ln_creator_source_id' ) => ( $in_id > 0 ? $in_id : $en_id ),
    ), array(), 1, 0, array(), 'COUNT(ln_id) as totals');

    if($read_coins[0]['totals'] > 0){
        return '<span class="montserrat read"><span class="icon-block"><i class="fas fa-circle"></i></span>'.echo_number($read_coins[0]['totals']).'</span>';
    } else {
        return false;
    }

}

function echo_coins_count_source($in_id = 0, $en_id = 0){

    $CI =& get_instance();

    if($in_id){
        $mench = 'source';
        $coin_filter = array(
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
            'ln_profile_source_id >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
            'ln_next_idea_id' => $in_id,
        );
    } elseif($en_id){
        $mench = 'idea';
        $coin_filter = array(
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
            'ln_profile_source_id' => $en_id,
        );
    }

    $en_coins = $CI->LEDGER_model->ln_fetch($coin_filter, array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    if($en_coins[0]['totals'] > 0){
        return '<span class="montserrat '.$mench.'"><span class="icon-block"><i class="fas fa-circle"></i></span>'.echo_number($en_coins[0]['totals']).'</span>';
    }

    return null;
}



function echo_in_read($in, $parent_is_or = false, $common_prefix = null, $extra_class = null, $show_editor = false, $completion_rate = null, $recipient_en = false)
{

    //See if user is logged-in:
    $CI =& get_instance();
    if(!$recipient_en){
        $recipient_en = superpower_assigned();
    }

    $is_highlight = ( isset($in['ln_type_source_id']) && $in['ln_type_source_id']==12896 );
    $metadata = unserialize($in['in_metadata']);
    $has_time_estimate = ( isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0 );

    if(!$completion_rate){
        if($recipient_en){
            $completion_rate = $CI->READ_model->read_completion_progress($recipient_en['en_id'], $in);
        } else {
            $completion_rate['completion_percentage'] = 0;
        }
    }

    $can_click = ( ( $parent_is_or && in_array($in['in_status_source_id'], $CI->config->item('en_ids_12138')) ) || $completion_rate['completion_percentage']>0 || $show_editor || $is_highlight || $recipient_en['en_id'] );


    $ui  = '<div id="ap_in_'.$in['in_id'].'" '.( isset($in['ln_id']) ? ' sort-link-id="'.$in['ln_id'].'" ' : '' ).' class="list-group-item no-side-padding '.( $show_editor ? 'bookshelf_sort' : '' ).' itemread '.$extra_class.'">';
    $ui .= ( $can_click ? '<a href="/'.$in['in_id'] . '" class="itemread">' : '' );
    if($can_click && $completion_rate['completion_percentage']>0 && $completion_rate['completion_percentage']<100){
        $ui .= '<div class="progress-bg-list" title="Read '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
    }

    $ui .= '<span class="icon-block">'.( $can_click && $completion_rate['completion_percentage']==100 ? '<i class="fas fa-circle read"></i>' : '<i class="fas fa-circle idea"></i>' ).'</span>';
    $ui .= '<b class="montserrat idea-url title-block">'.echo_in_title($in, $common_prefix).'</b>';

    //Search for Idea Image:
    if($show_editor){
        if($is_highlight){

            $ui .= '<div class="note-editor edit-off">';
            $ui .= '<span class="show-on-hover">';
            $ui .= '<span><a href="javascript:void(0);" title="Remove Highlight" data-toggle="tooltip" data-placement="left" onclick="read_toggle_highlight('.$in['in_id'].');$(\'#ap_in_'.$in['in_id'].'\').remove();"><i class="fas fa-times"></i></a></span>';
            $ui .= '</span>';
            $ui .= '</div>';

        } else {

            $ui .= '<div class="note-editor edit-off">';

            $ui .= '<span class="show-on-hover">';

            $ui .= '<span class="read-sorter" title="SORT"><i class="fas fa-bars"></i></span>';

            $ui .= '<span title="REMOVE"><span class="read_remove_item" in-id="'.$in['in_id'].'"><i class="fas fa-times"></i></span></span>';

            $ui .= '</span>';
            $ui .= '</div>';

        }
    }

    $ui .= ( $can_click ? '</a>' : '' );
    $ui .= '</div>';

    return $ui;
}


function echo_in_scores_answer($in_id, $depth_levels, $original_depth_levels, $parent_in_type_source_id){

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
    $en_all_4737 = $CI->config->item('en_all_4737'); // Idea Status
    $en_all_7585 = $CI->config->item('en_all_7585'); // Idea Subtypes


    $ui = null;
    foreach($CI->LEDGER_model->ln_fetch(array(
        'ln_previous_idea_id' => $in_id,
        'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        'in_status_source_id IN (' . join(',', $CI->config->item('en_ids_7356')) . ')' => null, //ACTIVE
    ), array('in_next'), 0, 0, array('ln_order' => 'ASC')) as $in_ln){

        //Prep Metadata:
        $metadata = unserialize($in_ln['ln_metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //ACTIVE
            'ln_type_source_id' => 4231, //IDEA NOTES Messages
            'ln_next_idea_id' => $in_ln['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Link Type: '.$en_all_4486[$in_ln['ln_type_source_id']]['m_name'].'">'. $en_all_4486[$in_ln['ln_type_source_id']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Transaction Status: '.$en_all_6186[$in_ln['ln_status_source_id']]['m_name'].'">'. $en_all_6186[$in_ln['ln_status_source_id']]['m_icon'] . '</span>';

        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Type: '.$en_all_7585[$in_ln['in_type_source_id']]['m_name'].'">'. $en_all_7585[$in_ln['in_type_source_id']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Status: '.$en_all_4737[$in_ln['in_status_source_id']]['m_name'].'">'. $en_all_4737[$in_ln['in_status_source_id']]['m_icon']. '</span>';
        $ui .= '<a href="?in_id='.$in_ln['in_id'].'&depth_levels='.$original_depth_levels.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this idea"><u>' .   echo_in_title($in_ln) . '</u></a>';

        $ui .= ' [<span data-toggle="tooltip" data-placement="top" title="Completion Marks">'.( ($in_ln['ln_type_source_id'] == 4228 && in_array($parent_in_type_source_id , $CI->config->item('en_ids_6193') /* OR Ideas */ )) || ($in_ln['ln_type_source_id'] == 4229) ? echo_in_marks($in_ln) : '' ).'</span>]';

        if(count($messages) > 0){
            $ui .= ' <a href="javascript:void(0);" onclick="$(\'.messages-'.$in_ln['in_id'].'\').toggleClass(\'hidden\');"><i class="fas fa-comment"></i><b>' .  count($messages) . '</b></a>';
        }
        $ui .= '</div>';

        //Display Messages:
        $ui .= '<div class="messages-'.$in_ln['in_id'].' hidden">';
        foreach($messages as $msg) {
            $ui .= '<div class="tip_bubble">';
            $ui .= $CI->COMMUNICATION_model->send_message($msg['ln_content']);
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
        $ui .= '<a href="javascript:void(0);" onclick="account_update_radio('.$parent_en_id.','.$en_id.','.$enable_mulitiselect.')" class="item'.extract_icon_color($m['m_icon']).' list-group-item montserrat itemsetting item-'.$en_id.' '.( $count>=$show_max ? 'extra-items-'.$parent_en_id.' hidden ' : '' ).( count($CI->LEDGER_model->ln_fetch(array(
                'ln_profile_source_id' => $en_id,
                'ln_portfolio_source_id' => $child_en_id,
                'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
                'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //PUBLIC
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


function echo_in($in, $in_linked_id, $is_parent, $is_source, $input_message = null, $extra_class = null, $control_enabled = true)
{

    $CI =& get_instance();

    $en_all_6186 = $CI->config->item('en_all_6186');
    $en_all_4737 = $CI->config->item('en_all_4737'); //IDEA STATUS
    $en_all_7585 = $CI->config->item('en_all_7585');
    $en_all_4486 = $CI->config->item('en_all_4486');
    $en_all_2738 = $CI->config->item('en_all_2738');
    $en_all_12413 = $CI->config->item('en_all_12413');

    //Prep link metadata to be analyzed later:
    $ln_id = $in['ln_id'];
    $ln_metadata = unserialize($in['ln_metadata']);
    $in_metadata = unserialize($in['in_metadata']);

    $session_en = superpower_assigned();
    $is_public = in_array($in['in_status_source_id'], $CI->config->item('en_ids_7355'));
    $is_link_published = in_array($in['ln_status_source_id'], $CI->config->item('en_ids_7359'));
    $is_in_link = in_array($in['ln_type_source_id'], $CI->config->item('en_ids_4486'));
    $is_source = ( !$is_in_link ? false : $is_source ); //Disable Edits on Idea List Page
    $show_toolbar = ($control_enabled && $is_in_link && superpower_active(12673, true));




    //IDAE INFO BAR
    $info_items_list = '';
    //TRANSACTION STATUS
    if($ln_id && !$is_link_published){
        $info_items_list .= '<span class="inline-block ln_status_source_id_' . $ln_id .'"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6186[$in['ln_status_source_id']]['m_name'].' @'.$in['ln_status_source_id'].'">' . $en_all_6186[$in['ln_status_source_id']]['m_icon'] . '</span>&nbsp;</span>';
    }





    //NEXT IDEAS COUNT (SYNC WITH SOURCE PORTFOLIO COUNT)
    $child_counter = '';
    if(superpower_active(10939, true)) {
        $next_ins = $CI->LEDGER_model->ln_fetch(array(
            'ln_previous_idea_id' => $in['in_id'],
            'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //IDEA LINKS
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array(), 'COUNT(ln_id) as total_ins');
        if($next_ins[0]['total_ins'] > 0){
            $child_counter .= '<span class="pull-right" '.( $show_toolbar ? ' style="margin-top: -18px;" ' : '' ).'><span class="icon-block doright montserrat idea" title="'.number_format($next_ins[0]['total_ins'], 0).' NEXT IDEAS">'.echo_number($next_ins[0]['total_ins']).'</span></span>';
            $child_counter .= '<div class="doclear">&nbsp;</div>';
        }
    }





    $ui = '<div in-link-id="' . $ln_id . '" in-tr-type="' . $in['ln_type_source_id'] . '" idea-id="' . $in['in_id'] . '" parent-idea-id="' . $in_linked_id . '" class="list-group-item no-side-padding itemidea itemidealist ideas_sortable paddingup level2_in object_highlight highlight_in_'.$in['in_id'] . ' in_line_' . $in['in_id'] . ( $is_parent ? ' parent-idea ' : '' ) . ' in__tr_'.$ln_id.' '.$extra_class.'" style="padding-left:0;">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="MENCHcolumn1">';
        $ui .= '<div class="block">';


            //IDEA ICON:
            $ui .= '<span class="icon-block"><a href="/idea/go/'.$in['in_id'].'" title="Idea Weight: '.number_format($in['in_weight'], 0).'">'.$en_all_2738[4535]['m_icon'].'</a></span>';


            //IDEA TITLE
            if($show_toolbar){

                $ui .= echo_input_text(4736, $in['in_title'], $in['in_id'], $is_source, (($in['ln_order']*100)+1));
                $ui .= $child_counter;

            } else {

                $ui .= '<a href="/idea/go/'.$in['in_id'].'" class="title-block montserrat">';
                $ui .= $info_items_list;
                //IDEA STATUS
                if(!$is_public){
                    //Show the drafting status:
                    $ui .= '<span class="inline-block"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4737[$in['in_status_source_id']]['m_name'].' @'.$in['in_status_source_id'].'">' . $en_all_4737[$in['in_status_source_id']]['m_icon'] . '</span>&nbsp;</span>';
                }
                $ui .= echo_in_title($in); //IDEA TITLE
                $ui .= $child_counter;
                $ui .= '</a>';

            }

        $ui .= '</div>';
    $ui .= '</td>';


    //READ
    $ui .= '<td class="MENCHcolumn2 read">';
    $ui .= echo_coins_count_read($in['in_id']);
    $ui .= '</td>';



    //SOURCE
    $ui .= '<td class="MENCHcolumn3 source">';


    if($is_in_link && $control_enabled && $is_source){

        //RIGHT EDITING:
        $ui .= '<div class="pull-right inline-block '.superpower_active(10939).'">';
        $ui .= '<div class="note-editor edit-off">';
        $ui .= '<span class="show-on-hover">';

        if(!$is_parent){
            $ui .= '<span title="SORT"><i class="fas fa-bars black idea-sort-handle"></i></span>';
        }

        //Unlink:
        $ui .= '<span title="UNLINK"><a href="javascript:void(0);" onclick="in_unlink('.$in['in_id'].', '.$in['ln_id'].', '.( $is_parent ? 1 : 0 ).')"><i class="fas fa-times black"></i></a></span>';

        $ui .= '</span>';
        $ui .= '</div>';
        $ui .= '</div>';

    }


    //SOURCE STATS
    $ui .= echo_coins_count_source($in['in_id'], 0);

    $ui .= '</td>';
    $ui .= '</tr></table>';



    if($input_message){
        $ui .= '<div class="idea-footer space-content">' . $CI->COMMUNICATION_model->send_message($input_message, $session_en) . '</div>';
    }


    if($show_toolbar){

        //Idea Toolbar
        $ui .= '<div class="space-content ' . superpower_active(12673) . '" style="padding-left:25px;">';

        $ui .= $info_items_list;

        //IDEA TYPE
        $ui .= '<div class="inline-block '.superpower_active(10986).'">'.echo_input_dropdown(7585, $in['in_type_source_id'], null, $is_source, false, $in['in_id']).'</div>';

        //IDEA STATUS
        $ui .= '<div class="inline-block">' . echo_input_dropdown(4737, $in['in_status_source_id'], null, $is_source, false, $in['in_id']) . ' </div>';



        //IDEA LINK BAR
        $ui .= '<div class="inline-block ' . superpower_active(12700) . '">';
        //LINK TYPE
        $ui .= echo_input_dropdown(4486, $in['ln_type_source_id'], null, $is_source, false, $in['in_id'], $in['ln_id']);

        //LINK MARKS
        $ui .= '<span class="link_marks settings_4228 '.( $in['ln_type_source_id']==4228 ? : 'hidden' ).'">';
        $ui .= echo_input_text(4358, ( isset($ln_metadata['tr__assessment_points']) ? $ln_metadata['tr__assessment_points'] : '' ), $in['ln_id'], $is_source, ($in['ln_order']*10)+2 );
        $ui .='</span>';


        //LINK CONDIITONAL RANGE
        $ui .= '<span class="link_marks settings_4229 '.( $in['ln_type_source_id']==4229 ? : 'hidden' ).'">';
        //MIN
        $ui .= echo_input_text(4735, ( isset($ln_metadata['tr__conditional_score_min']) ? $ln_metadata['tr__conditional_score_min'] : '' ), $in['ln_id'], $is_source, ($in['ln_order']*10)+3);
        //MAX
        $ui .= echo_input_text(4739, ( isset($ln_metadata['tr__conditional_score_max']) ? $ln_metadata['tr__conditional_score_max'] : '' ), $in['ln_id'], $is_source, ($in['ln_order']*10)+4);
        $ui .= '</span>';
        $ui .= '</div>';



        $ui .= '</div>';

    }

    $ui .= '</div>';



    return $ui;

}




function echo_caret($en_id, $m, $object_id){
    //Display drop down menu:
    $CI =& get_instance();

    $superpower_actives = array_intersect($CI->config->item('en_ids_10957'), $m['m_parents']);

    $ui = '<li class="nav-item dropdown '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'" title="'.$m['m_name'].'" data-toggle="tooltip" data-placement="top">';
    $ui .= '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>';
    $ui .= '<div class="dropdown-menu">';
    foreach($CI->config->item('en_all_'.$en_id) as $en_id2 => $m2){
        $ui .= '<a class="dropdown-item montserrat '.extract_icon_color($m2['m_icon']).'" href="' . $m2['m_desc'] . $object_id . '"><span class="icon-block">'.echo_en_icon($m2['m_icon']).'</span> '.$m2['m_name'].'</a>';
    }
    $ui .= '</div>';
    $ui .= '</li>';

    return $ui;
}


function echo_in_list($in, $in__next, $recipient_en, $prefix_statement = null, $show_next = true){

    //If no list just return the next step:
    if(!count($in__next)){
        return ( $show_next ? echo_in_next_previous($in['in_id'], $recipient_en) : false );
    }

    $CI =& get_instance();

    if(count($in__next)){

        //List children so they know what's ahead:
        $common_prefix = in_calc_common_prefix($in__next, 'in_title', $in);
        $has_content = ($prefix_statement || strlen($common_prefix));

        if($has_content){
            echo '<div class="read-topic">'.trim($prefix_statement).'</div>';
        } else {
            //echo '<div class="read-topic"><span class="icon-block">&nbsp;</span>IDEAS</div>';
        }

        echo '<div class="list-group">';
        foreach($in__next as $key => $child_in){
            echo echo_in_read($child_in, false, $common_prefix);
        }
        echo '</div>';
    }

    if($show_next){
        echo_in_next_previous($in['in_id'], $recipient_en);
        echo '<div class="doclear">&nbsp;</div>';
    }
}

function echo_in_next_previous($in_id, $recipient_en){

    $CI =& get_instance();
    $en_all_11035 = $CI->config->item('en_all_11035'); //MENCH NAVIGATION

    //PREVIOUS:
    echo echo_in_previous_read($in_id, $recipient_en);

    //NEXT:
    echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-read btn-circle" href="/read/next/'.$in_id.'">'.$en_all_11035[12211]['m_icon'].'</a></div>';

}

function echo_in_previous_read($in_id, $recipient_en){

    if(!$recipient_en || $recipient_en['en_id'] < 1){
        return null;
    }

    //Bookshelf
    $CI =& get_instance();
    $ui = null;
    $in_level_up = 0;
    $previous_level_id = 0; //The ID of the Idea one level up
    $player_read_ids = $CI->READ_model->read_ids($recipient_en['en_id']);
    $top_completion_rate = null;

    if(in_array($in_id, $player_read_ids)){

        //A reading list item:
        $ins_this = $CI->IDEA_model->in_fetch(array(
            'in_id' => $in_id,
        ));

    } else {

        //Find it:
        $recursive_parents = $CI->IDEA_model->in_recursive_parents($in_id, true, true);
        foreach($recursive_parents as $grand_parent_ids) {
            foreach(array_intersect($grand_parent_ids, $player_read_ids) as $intersect) {
                foreach($grand_parent_ids as $parent_in_id) {

                    if($in_level_up==0){
                        //Remember the first parent for the back button:
                        $previous_level_id = $parent_in_id;
                    }

                    $ins_this = $CI->IDEA_model->in_fetch(array(
                        'in_id' => $parent_in_id,
                    ));

                    $completion_rate = $CI->READ_model->read_completion_progress($recipient_en['en_id'], $ins_this[0]);

                    $in_level_up++;

                    if ($parent_in_id == $intersect) {
                        $top_completion_rate = $completion_rate;
                        $top_completion_rate['top_in'] = $ins_this[0];
                        break;
                    }
                }
                break; //Just look into the first intersect for now (Expand later)
            }
            if($top_completion_rate){
                break;
            }
        }
    }


    //Did We Find It?
    if($previous_level_id > 0){

        //Previous
        if(isset($_GET['previous_read']) && $_GET['previous_read']>0){
            $ui .= '<div class="inline-block margin-top-down edit_select_answer pull-left"><a class="btn btn-read btn-circle" href="/'.$_GET['previous_read'].'"><i class="fas fa-step-backward"></i></a></div>';
        } else {
            $ui .= '<div class="inline-block margin-top-down edit_select_answer pull-left"><a class="btn btn-read btn-circle" href="/read/previous/'.$previous_level_id.'/'.$in_id.'"><i class="fas fa-step-backward"></i></a></div>';
        }


        //Check Highlight status
        $is_highlighted = count($CI->LEDGER_model->ln_fetch(array(
            'ln_profile_source_id' => $recipient_en['en_id'],
            'ln_next_idea_id' => $in_id,
            'ln_type_source_id' => 12896, //HIGHLIGHTS
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        )));

        $ui .= '<div class="inline-block margin-top-down pull-left edit_select_answer"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="read_toggle_highlight('.$in_id.')"><i class="fas fa-bookmark toggle_highlight '.( $is_highlighted ? '' : 'hidden' ).'"></i><i class="fal fa-bookmark toggle_highlight '.( $is_highlighted ? 'hidden' : '' ).'"></i></a></div>';

        //Main Reads:
        if($top_completion_rate){

            $ui .= '<div class="main_reads_bottom hidden">';
            $ui .= '<div class="list-group">';
            $ui .= echo_in_read($top_completion_rate['top_in'], false, null, null, false, $top_completion_rate);
            $ui .= '</div>';
            $ui .= '</div>';

        }

    }

    return $ui;

}


function echo_in_note_source($in_id, $note_type_en_id, $in_notes, $is_source){

    $CI =& get_instance();
    $en_all_11018 = $CI->config->item('en_all_11018');

    $ui = '<div class="list-group">';
    foreach($in_notes as $en) {
        $ui .= echo_en($en, false, null, true, $is_source);
    }

    if( $is_source ){
        $ui .= '<div class="list-group-item itemsource '.superpower_active(10939).'" style="padding:5px 0;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$en_all_11018[$note_type_en_id]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control IdeaAddPrevious form-control-thick doupper add-input montserrat algolia_search dotransparent"
                           maxlength="' . config_var(6197) . '"
                           idea-id="' . $in_id . '"
                           id="add-source-idea-' . $in_id . '"
                           placeholder="'.$en_all_11018[$note_type_en_id]['m_name'].'">
                </div><div class="algolia_pad_search hidden in_pad_top"></div></div>';
    }
    $ui .= '</div>';

    return $ui;
}

function echo_in_note_mix($note_type_en_id, $in_notes, $is_source){

    $CI =& get_instance();
    $en_all_4485 = $CI->config->item('en_all_4485'); //IDEA NOTES
    $handles_uploads = (in_array($note_type_en_id, $CI->config->item('en_ids_12359')));
    $handles_url = (in_array($note_type_en_id, $CI->config->item('en_ids_7551')) || in_array($note_type_en_id, $CI->config->item('en_ids_4986')));



    //Show no-Message notifications for each message type:
    $ui = '<div id="in_notes_list_'.$note_type_en_id.'" class="list-group">';

    foreach($in_notes as $in_notes) {
        $ui .= echo_in_notes($in_notes);
    }




    //ADD NEW Alert:
    $ui .= '<div class="list-group-item itemidea space-left add_notes_' . $note_type_en_id . ( $is_source ? '' : ' hidden ' ).'">';
    $ui .= '<div class="add_notes_form">';
    $ui .= '<form class="box box' . $note_type_en_id . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">'; //Used for dropping files



    $ui .= '<textarea onkeyup="in_notes_count_new('.$note_type_en_id.')" class="form-control msg note-textarea algolia_search new-note" note-type-id="' . $note_type_en_id . '" id="ln_content' . $note_type_en_id . '" placeholder="WRITE'.( $handles_url ? ', PASTE URL' : '' ).( $handles_uploads ? ', DROP FILE' : '' ).'" style="margin-top:6px;"></textarea>';



    $ui .= '<table class="table table-condensed hidden" id="note_control_'.$note_type_en_id.'"><tr>';


    //Save button:
    $ui .= '<td style="width:85px; padding: 10px 0 0 0;"><a href="javascript:in_notes_add('.$note_type_en_id.');" class="btn btn-idea save_notes_'.$note_type_en_id.'"><i class="fas fa-plus"></i></a></td>';


    //File counter:
    $ui .= '<td style="padding: 10px 0 0 0; font-size: 0.85em;"><span id="ideaNoteNewCount' . $note_type_en_id . '" class="hidden"><span id="charNum' . $note_type_en_id . '">0</span>/' . config_var(4485).'</span></td>';


    //YouTube Clip:
    if($handles_url){
        $ui .= '<td style="width:42px; padding: 10px 0 0 0;"><a href="javascript:in_notes_insert_string('.$note_type_en_id.', \'https://www.youtube.com/embed/VIDEOIDHERE?start=&end=\');" data-toggle="tooltip" title="YOUTUBE CLIPPER: Slice a video using start & end time" data-placement="top"><span class="icon-block"><i class="fab fa-youtube"></i></span></a></td>';
    }


    //Upload File:
    if($handles_uploads){
        $ui .= '<td style="width:36px; padding: 10px 0 0 0;">';
        $ui .= '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType'.$note_type_en_id.'" />';
        $ui .= '<label class="file_label_'.$note_type_en_id.'" for="fileIdeaType'.$note_type_en_id.'" data-toggle="tooltip" title="Upload files up to ' . config_var(11063) . 'MB, or upload elsewhere & paste URL here" data-placement="top"><span class="icon-block"><i class="far fa-paperclip"></i></span></label>';
        $ui .= '</td>';
    }


    $ui .= '</tr></table>';


    //Response result:
    $ui .= '<div class="note_error_'.$note_type_en_id.'"></div>';


    $ui .= '</form>';
    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}

function echo_platform_message($en_id){
    $CI =& get_instance();
    $en_all_12687 = $CI->config->item('en_all_12687');
    if(!substr_count($en_all_12687[$en_id]['m_desc'], " | ")){
        //Single message:
        return $en_all_12687[$en_id]['m_desc'];
    } else {
        //Random message:
        $line_messages = explode(" | ", $en_all_12687[$en_id]['m_desc']);
        return $line_messages[rand(0, (count($line_messages) - 1))];
    }
}

function echo_unauthorized_message($superpower_en_id = 0){

    $session_en = superpower_assigned($superpower_en_id);

    if(!$session_en){

        //Missing Session
        return 'Login to continue.';

    } elseif($superpower_en_id>0) {

        //Missing Superpower:
        $CI =& get_instance();
        $en_all_10957 = $CI->config->item('en_all_10957');
        return 'You are missing the required superpower of '.$en_all_10957[$superpower_en_id]['m_name'];

    }

    return null;

}

function echo_time_hours($total_seconds){
    //Turns seconds into HH:MM:SS
    $hours = floor($total_seconds/3600);
    $minutes = floor(fmod($total_seconds, 3600)/60);
    $minutes = ( $hours && $minutes<10 ? '0'.$minutes  : $minutes );
    $seconds = fmod($total_seconds, 60);
    $seconds = ( $seconds<10 ? '0'.$seconds  : $seconds );
    return ( $hours ? $hours.':' : '' ).$minutes.':'.$seconds;
}

function echo_in_cover($in, $show_editor, $common_prefix = null, $completion_rate = null){


    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();

    //FIND IMAGE
    $cover_photo = null;
    foreach($CI->LEDGER_model->ln_fetch(array( //IDEA SOURCE
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //PUBLIC
        'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_12273')) . ')' => null, //IDEA COIN
        'ln_next_idea_id' => $in['in_id'],
        'ln_profile_source_id >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
    ), array(), 0, 0, array(
        'ln_type_source_id' => 'ASC', //Messages First, Sources Second
        'ln_order' => 'ASC', //Sort by message order
    )) as $en){
        //See if this source has a photo:
        foreach($CI->LEDGER_model->ln_fetch(array(
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'ln_type_source_id' => 4260, //IMAGES ONLY
            'ln_portfolio_source_id' => $en['ln_profile_source_id'],
        )) as $en_image) {
            $cover_photo = '<img src="'.$en_image['ln_content'].'" />';
            break;
        }
        if($cover_photo){
            break;
        }
    }
    if(!$cover_photo){
        //DEFAULT IMAGE
        $cover_photo = '<img src="https://s3foundation.s3-us-west-2.amazonaws.com/4981b7cace14d274a4865e2a416b372b.jpg" />';
    }

    $recipient_en = superpower_assigned();
    $metadata = unserialize($in['in_metadata']);

    $ui  = '<a href="/'.$in['in_id'] . '" id="ap_in_'.$in['in_id'].'" '.( isset($in['ln_id']) ? ' sort-link-id="'.$in['ln_id'].'" ' : '' ).' class="cover-block '.( $show_editor ? ' bookshelf_sort ' : '' ).'">';


    $ui .= '<div class="cover-image">';
    if($recipient_en){
        if(!$completion_rate){
            $completion_rate = $CI->READ_model->read_completion_progress($recipient_en['en_id'], $in);
        }
        if($completion_rate['completion_percentage']>0){
            $ui .= '<div class="progress-bg-image" title="Read '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
        }
    }
    $ui .= $cover_photo;
    if(isset($metadata['in__metadata_max_seconds']) && $metadata['in__metadata_max_seconds']>0){
        $ui .= '<span class="media-info bottom-right">'.echo_time_hours($metadata['in__metadata_max_seconds']).'</span>';
    }
    if($completion_rate['completion_percentage']==100){
        $ui .= '<span class="media-info bottom-left">100% <i class="fas fa-check-circle"></i></span>';
    }
    //Search for Idea Image:
    if($show_editor){
        $ui .= '<span class="media-info top-left read-sorter" title="SORT"><i class="fas fa-bars"></i></span>';
        $ui .= '<span class="media-info top-right read_remove_item" in-id="'.$in['in_id'].'" title="REMOVE"><i class="fas fa-times"></i></span>';
    }
    $ui .= '</div>';

    $ui .= '<b class="montserrat">'.echo_in_title($in, $common_prefix).'</b>';

    $ui .= '</a>';

    return $ui;

}

function echo_en_basic($en)
{
    $ui = '<div class="list-group-item no-side-padding">';
    $ui .= '<span class="icon-block">' . echo_en_icon($en['en_icon']) . '</span>';
    $ui .= '<span class="title-block title-no-right montserrat">'.$en['en_name'].'</span>';
    $ui .= '</div>';
    return $ui;
}


function echo_en($en, $is_parent = false, $extra_class = null, $control_enabled = false, $is_source = false)
{

    $CI =& get_instance();
    $session_en = superpower_assigned();
    $en_all_6177 = $CI->config->item('en_all_6177'); //Source Status
    $en_all_2738 = $CI->config->item('en_all_2738');
    $en_all_4592 = $CI->config->item('en_all_4592');
    $en_all_6186 = $CI->config->item('en_all_6186'); //Transaction Status

    $ln_id = (isset($en['ln_id']) ? $en['ln_id'] : 0);
    $is_link_source = ( $ln_id > 0 && in_array($en['ln_type_source_id'], $CI->config->item('en_ids_4592')));
    $is_read_progress = ( $ln_id > 0 && in_array($en['ln_type_source_id'], $CI->config->item('en_ids_12227')));
    $is_source_only = ( $ln_id > 0 && in_array($en['ln_type_source_id'], $CI->config->item('en_ids_7551')));
    $show_toolbar = ($control_enabled && superpower_active(12706, true));

    $en__profiles = $CI->LEDGER_model->ln_fetch(array(
        'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
        'ln_portfolio_source_id' => $en['en_id'], //This child source
        'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7360')) . ')' => null, //ACTIVE
        'en_status_source_id IN (' . join(',', $CI->config->item('en_ids_7358')) . ')' => null, //ACTIVE
    ), array('en_profile'), 0, 0, array('en_weight' => 'DESC'));

    $is_public = in_array($en['en_status_source_id'], $CI->config->item('en_ids_7357'));
    $is_link_published = ( !$ln_id || in_array($en['ln_status_source_id'], $CI->config->item('en_ids_7359')));
    $is_hidden = filter_array($en__profiles, 'en_id', '4755') || in_array($en['en_id'], $CI->config->item('en_ids_4755'));

    if(!$session_en && (!$is_public || !$is_link_published)){
        //Not logged in, so should only see published:
        return false;
    } elseif($is_hidden && !superpower_assigned(12701)){
        //Cannot see this private transaction:
        return false;
    } elseif($is_hidden && !superpower_active(12701, true)){
        //They don't have the needed superpower:
        return false;
    }


    //SOURCE INFO BAR
    $info_items_list = '';

    //SOURCE STATUS
    if(!$is_public){
        $info_items_list .= '<span class="inline-block en_status_source_id_' . $en['en_id'].'"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6177[$en['en_status_source_id']]['m_name'].' @'.$en['en_status_source_id'].'">' . $en_all_6177[$en['en_status_source_id']]['m_icon'] . '</span>&nbsp;</span>';
    }

    //TRANSACTION STATUS
    if($ln_id){

        if(!$is_link_published){
            $info_items_list .= '<span class="inline-block ln_status_source_id_' . $ln_id .'"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_6186[$en['ln_status_source_id']]['m_name'].' @'.$en['ln_status_source_id'].'">' . $en_all_6186[$en['ln_status_source_id']]['m_icon'] . '</span>&nbsp;</span>';
        }

        //External ID
        if($is_link_source && $en['ln_external_id'] > 0){
            $info_items_list .= '<span class="inline-block '.superpower_active(12701).'" data-toggle="tooltip" data-placement="right" title="Link External ID = '.$en['ln_external_id'].'">&nbsp;<i class="fas fa-project-diagram"></i></span>';
        }
    }



    //PORTFOLIO COUNT (SYNC WITH NEXT IDEA COUNT)
    $child_counter = '';
    if(superpower_active(10967, true)){
        $en__portfolios_count = $CI->LEDGER_model->ln_fetch(array(
            'ln_profile_source_id' => $en['en_id'],
            'ln_type_source_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //SOURCE LINKS
            'ln_status_source_id IN (' . join(',', $CI->config->item('en_ids_7359')) . ')' => null, //PUBLIC
            'en_status_source_id IN (' . join(',', $CI->config->item('en_ids_7357')) . ')' => null, //PUBLIC
        ), array('en_portfolio'), 0, 0, array(), 'COUNT(en_id) as totals');
        if($en__portfolios_count[0]['totals'] > 0){
            $child_counter .= '<span class="pull-right" '.( $show_toolbar ? ' style="margin-top: -19px;" ' : '' ).'><span class="icon-block doright montserrat source" title="'.number_format($en__portfolios_count[0]['totals'], 0).' PORTFOLIO SOURCES">'.echo_number($en__portfolios_count[0]['totals']).'</span></span>';
            $child_counter .= '<div class="doclear">&nbsp;</div>';
        }
    }


    //ROW
    $ui = '<div class="list-group-item no-side-padding itemsource en-item object_highlight highlight_en_'.$en['en_id'].' en___' . $en['en_id'] . ( $ln_id > 0 ? ' tr_' . $en['ln_id'].' ' : '' ) . ( $is_parent ? ' parent-source ' : '' ) . ' '. $extra_class  . '" source-id="' . $en['en_id'] . '" en-status="' . $en['en_status_source_id'] . '" tr-id="'.$ln_id.'" ln-status="'.( $ln_id ? $en['ln_status_source_id'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';


    //SOURCE
    $ui .= '<td class="MENCHcolumn1">';


    //SOURCE ICON
    $ui .= '<a href="/source/'.$en['en_id'] . '" '.( $is_link_source ? ' title="WEIGHT '.$en['en_weight'].' LINK ID '.$en['ln_id'].' '.$en_all_4592[$en['ln_type_source_id']]['m_name'].' @'.$en['ln_type_source_id'].'" ' : '' ).'><span class="icon-block en_ui_icon_' . $en['en_id'] . ' en__icon_'.$en['en_id'].'" en-is-set="'.( strlen($en['en_icon']) > 0 ? 1 : 0 ).'">' . echo_en_icon($en['en_icon']) . '</span></a>';


    //SOURCE TOOLBAR?
    if($show_toolbar){

        $ui .= echo_input_text(6197, $en['en_name'], $en['en_id'], $is_source, 0, false, null, extract_icon_color($en['en_icon']));
        $ui .= $child_counter;
        $ui .= '<div class="space-content">'.$info_items_list.'</div>';

    } else {

        //SOURCE NAME
        $ui .= '<a href="/source/'.$en['en_id'] . '" class="title-block title-no-right montserrat '.extract_icon_color($en['en_icon']).'">';
        $ui .= $info_items_list;
        $ui .= '<span class="text__6197_' . $en['en_id'] . '">'.$en['en_name'].'</span>';
        $ui .= $child_counter;
        $ui .= '</a>';

    }

    $ui .= '</td>';




    //IDEA
    $ui .= '<td class="MENCHcolumn3 source">';
    $ui .= echo_coins_count_source(0, $en['en_id']);
    $ui .= '</td>';



    //READ
    $ui .= '<td class="MENCHcolumn2 read">';

    //RIGHT EDITING:
    $ui .= '<div class="pull-right inline-block">';
    $ui .= '<div class="note-editor edit-off">';
    $ui .= '<span class="show-on-hover">';

    if($control_enabled && $is_source){
        if($is_link_source){

            //Manage source link:
            $ui .= '<span class="'.superpower_active(10967).'"><a href="javascript:void(0);" onclick="en_modify_load(' . $en['en_id'] . ',' . $ln_id . ')"><i class="fas fa-pen-square black"></i></a></span>';

        } elseif($is_source_only){

            //Allow to remove:
            $ui .= '<span><a href="javascript:void(0);" onclick="en_source_only_unlink(' . $ln_id . ', '.$en['ln_type_source_id'].')"><i class="fas fa-times black"></i></a></span>';

        }
    }

    $ui .= '</span>';
    $ui .= '</div>';
    $ui .= '</div>';

    $ui .= echo_coins_count_read(0, $en['en_id']);
    $ui .= '</td>';





    $ui .= '</tr></table>';



    //PROFILE
    $ui .= '<div class="space-content hideIfEmpty">';
    //PROFILE SOURCES:
    $ui .= '<span class="'. superpower_active(12706) .' paddingup inline-block hideIfEmpty">';
    foreach($en__profiles as $en_parent) {
        $ui .= '<span class="icon-block-img en_child_icon_' . $en_parent['en_id'] . '"><a href="/source/' . $en_parent['en_id'] . '" data-toggle="tooltip" title="' . $en_parent['en_name'] . (strlen($en_parent['ln_content']) > 0 ? ' = ' . $en_parent['ln_content'] : '') . '" data-placement="bottom">' . echo_en_icon($en_parent['en_icon']) . '</a></span> ';
    }
    $ui .= '</span>';
    $ui .= '</div>';



    //MESSAGE
    if ($ln_id > 0) {
        if($is_link_source){

            $ui .= '<span class="message_content paddingup ln_content hideIfEmpty ln_content_' . $ln_id . '">' . echo_ln_content($en['ln_content'] , $en['ln_type_source_id']) . '</span>';

            //For JS editing only (HACK):
            $ui .= '<div class="ln_content_val_' . $ln_id . ' hidden overflowhide">' . $en['ln_content'] . '</div>';

        } elseif($is_read_progress && strlen($en['ln_content'])){

            //READ PROGRESS
            $ui .= '<div class="message_content paddingup">';
            $ui .= $CI->COMMUNICATION_model->send_message($en['ln_content']);
            $ui .= '</div>';

        }
    }





    $ui .= '</div>';

    return $ui;

}

function echo_basic_list_link($m, $url){

    $CI =& get_instance();
    $en_all_6287 = $CI->config->item('en_all_6287'); //MENCH PLUGIN
    $en_all_10957 = $CI->config->item('en_all_10957');

    $ui = '<a href="'.$url.'" class="list-group-item no-side-padding">';


    //Icon
    $ui .= '<span class="icon-block">' . echo_en_icon($m['m_icon']) . '</span>';
    $ui .= '<b class="montserrat '.extract_icon_color($m['m_icon']).'">'.$m['m_name'].'</b>';


    //Needs extra superpowers?
    $superpower_actives = array_intersect($CI->config->item('en_ids_10957'), $m['m_parents']);
    foreach($superpower_actives as $needed_superpower_en_id){
        $ui .= '<span title="Requires '.$en_all_10957[$needed_superpower_en_id]['m_name'].'" data-toggle="tooltip" data-placement="top">&nbsp;'.$en_all_10957[$needed_superpower_en_id]['m_icon'].'</span>';
    }


    //Description
    $ui .= ( strlen($m['m_desc']) ? '&nbsp;'.$m['m_desc'] : '' );


    $ui .= '</a>';

    return $ui;

}



function echo_input_text($cache_en_id, $current_value, $object_id, $is_source, $tabindex = 0, $extra_large = false, $en_icon = null, $append_css = null){

    $CI =& get_instance();
    $en_all_12112 = $CI->config->item('en_all_12112');
    $current_value = htmlentities($current_value);

    //Define element attributes:
    $attributes = ( $is_source ? '' : 'disabled' ).' tabindex="'.$tabindex.'" old-value="'.$current_value.'" class="form-control dotransparent montserrat inline-block echo_input_text_update text__'.$cache_en_id.'_'.$object_id.' texttype_'.($extra_large?'_lg':'_sm').' text_en_'.$cache_en_id.' '.$append_css.'" cache_en_id="'.$cache_en_id.'" object_id="'.$object_id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $main_element = '<textarea '.( !strlen($append_css) ? ' style="color:#000000 !important;" ' : '' ).' onkeyup="echo_input_text_count('.$cache_en_id.','.$object_id.')" placeholder="'.$en_all_12112[$cache_en_id]['m_name'].'" '.$attributes.'>'.$current_value.'</textarea>';
        $character_counter = '<div class="title_counter title_counter_'.$cache_en_id.'_'.$object_id.' hidden grey montserrat doupper" style="text-align: right;"><span id="current_count_'.$cache_en_id.'_'.$object_id.'">0</span>/'.config_var($cache_en_id).' CHARACTERS</div>';
        $icon = '<span class="icon-block title-icon">'.( $en_icon ? $en_icon : $en_all_12112[4535]['m_icon'] ).'</span>';

    } else {

        $main_element = '<input type="text" placeholder="__" value="'.$current_value.'" '.$attributes.' />';
        $character_counter = ''; //None
        if(in_array($cache_en_id, $CI->config->item('en_ids_12420'))){ //IDEA TEXT INPUT SHOW ICON
            $icon = '<span class="icon-block">'.( $en_icon ? $en_icon : $en_all_12112[$cache_en_id]['m_icon'] ).'</span>';
        } else {
            $icon = $en_icon;
        }

    }

    return '<span class="span__'.$cache_en_id.' '.( !$is_source ? 'edit-locked' : '' ).'" '.( $extra_large ? '' : 'data-toggle="tooltip" data-placement="top" title="'.$en_all_12112[$cache_en_id]['m_name'].'"').'>'.$icon.$main_element.'</span>'.$character_counter;
}




function echo_input_dropdown($cache_en_id, $selected_en_id, $btn_class, $is_source = true, $show_full_name = true, $in_id = 0, $ln_id = 0){

    $CI =& get_instance();
    $en_all_this = $CI->config->item('en_all_'.$cache_en_id);

    if(!$selected_en_id || !isset($en_all_this[$selected_en_id])){
        return false;
    }

    $en_all_12079 = $CI->config->item('en_all_12079');
    $en_all_4527 = $CI->config->item('en_all_4527');

    //data-toggle="tooltip" data-placement="top" title="'.$en_all_4527[$cache_en_id]['m_name'].'"
    $ui = '<div title="'.$en_all_12079[$cache_en_id]['m_name'].'" data-toggle="tooltip" data-placement="top" class="inline-block">';
    $ui .= '<div class="dropdown inline-block dropd_'.$cache_en_id.'_'.$in_id.'_'.$ln_id.' '.( !$show_full_name ? ' icon-block ' : '' ).'" selected-val="'.$selected_en_id.'">';

    $ui .= '<button type="button" '.( $is_source ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' '.$btn_class.'" id="dropdownMenuButton'.$cache_en_id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked  '.$btn_class.'"' ).' >';

    $ui .= '<span class="icon-block">' .$en_all_this[$selected_en_id]['m_icon'].'</span><span class="show-max">'.( $show_full_name ?  $en_all_this[$selected_en_id]['m_name'] : '' ).'</span>';

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$cache_en_id.'">';

    foreach($en_all_this as $en_id => $m) {

        $superpower_actives = array_intersect($CI->config->item('en_ids_10957'), $m['m_parents']);
        $is_url_desc = ( substr($m['m_desc'], 0, 1)=='/' );

        //What type of URL?
        if($is_url_desc){

            //Basic link:
            $anchor_url = ( $en_id==$selected_en_id ? 'href="javascript:void();"' : 'href="'.$m['m_desc'].'"' );

        } else{

            //Idea Dropdown updater:
            $anchor_url = 'href="javascript:void();" new-en-id="'.$en_id.'" onclick="in_update_dropdown('.$cache_en_id.', '.$en_id.', '.$in_id.', '.$ln_id.', '.intval($show_full_name).')"';

        }

        $ui .= '<a class="dropdown-item dropi_'.$cache_en_id.'_'.$in_id.'_'.$ln_id.' montserrat optiond_'.$en_id.'_'.$in_id.'_'.$ln_id.' doupper '.( $en_id==$selected_en_id ? ' active ' : ( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ) ).'" '.$anchor_url.'><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</a>'; //Used to show desc but caused JS click conflict sp retired for now: ( strlen($m['m_desc']) && !$is_url_desc ? 'title="'.$m['m_desc'].'" data-toggle="tooltip" data-placement="right"' : '' )

    }

    $ui .= '</div>';
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

