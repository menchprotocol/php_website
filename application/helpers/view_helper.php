<?php

function view_source_load_more($page, $limit, $source__portfolio_count)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $ui = '<div class="load-more montserrat list-group-item itemsource no-left-padding"><a href="javascript:void(0);" onclick="source_load_page(' . $page . ', 0)">';

    //Regular section:
    $max_sources = (($page + 1) * $limit);
    $max_sources = ($max_sources > $source__portfolio_count ? $source__portfolio_count : $max_sources);
    $ui .= '<span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source">SEE MORE</b>';
    $ui .= '</a></div>';

    return $ui;
}

function view_db_field($field_name){

    //Takes a database field name and returns a human-friendly version
    return ucwords(str_replace('__', ' ', $field_name));

}


function view_read__message($read__message, $read__type, $full_message = null)
{

    /*
     *
     * Displays Source Links @4592
     *
     * $full_message Would be the entire message
     * in an idea message that would be passed down
     * to the source profile $read__message value.
     *
     * */


    if ($read__type == 4256 /* Generic URL */) {

        return '<div class="block"><a href="' . $read__message . '" target="_blank"><span class="icon-block-xs inline-block"><i class="far fa-external-link"></i></span><span class="url_truncate">' . view_url_clean($read__message) . '</span></a></div>';

    } elseif ($read__type == 4257 /* Embed Widget URL? */) {

        return view_url_embed($read__message, $full_message);

    } elseif ($read__type == 4260 /* Image URL */) {

        $current_mench = current_mench();
        if($current_mench['x_name']=='source'){
            return '<a href="' . $read__message . '"><img data-src="' . $read__message . '" src="/img/mench.png" alt="IMAGE" class="content-image lazyimage" /></a>';
        } else {
            return '<img data-src="' . $read__message . '" src="/img/mench.png" alt="IMAGE" class="content-image lazyimage" />';
        }

    } elseif ($read__type == 4259 /* Audio URL */) {

        return  '<audio controls><source src="' . $read__message . '" type="audio/mpeg"></audio>' ;

    } elseif ($read__type == 4258 /* Video URL */) {

        return  '<video width="100%" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="' . $read__message . '" type="video/mp4"></video>' ;

    } elseif ($read__type == 4261 /* File URL */) {

        return '<a href="' . $read__message . '" class="btn btn-idea" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';

    } elseif(strlen($read__message) > 0) {

        return htmlentities($read__message);

    } else {

        //UNKNOWN
        return false;

    }
}




function view_url_embed($url, $full_message = null, $return_array = false)
{


    /*
     *
     * Detects and displays URLs from supported website with an embed widget
     *
     * Alert: Changes to this function requires us to re-calculate all current
     *       values for read__type as this could change the equation for those
     *       link types. Change with care...
     *
     * */



    $clean_url = null;
    $embed_html_code = null;
    $prefix_message = null;
    $CI =& get_instance();

    if(is_https_url($url)){

        //See if $url has a valid embed video in it, and transform it if it does:
        $is_embed = (substr_count($url, 'youtube.com/embed/') == 1);

        if ((substr_count($url, 'youtube.com/watch') == 1) || substr_count($url, 'youtu.be/') == 1 || $is_embed) {

            $start_time = 0;
            $end_time = 0;
            $video_id = extract_youtube_id($url);

            if ($video_id) {

                $string_references = extract_source_references($full_message, true);

                if($string_references['ref_time_found']){

                    $start_time = $string_references['ref_time_start'];
                    $end_time = $string_references['ref_time_end'];

                } elseif($is_embed){

                    if(is_numeric(one_two_explode('start=','&',$url))){
                        $start_time = intval(one_two_explode('start=','&',$url));
                    }
                    if(is_numeric(one_two_explode('end=','&',$url))){
                        $end_time = intval(one_two_explode('end=','&',$url));
                    }
                }

                //Set the Clean URL:
                $clean_url = 'https://www.youtube.com/watch?v=' . $video_id;

                //($end_time ? '<span class="media-info mid-right" title="Clip from '.view_time_hours($start_time).' to '.view_time_hours($end_time).'" data-toggle="tooltip" data-placement="top">'.view_time_hours($end_time - $start_time).'</span>' : '')

                //Header For Time
                if($end_time){
                    $embed_html_code .= '<div class="read-topic" style="padding-bottom: 0;"><span class="img-block icon-block-xs"><i class="fas fa-cut"></i></span>WATCH FROM '.view_time_hours($start_time, true).' TO '.view_time_hours($end_time, true).'</div>';
                }

                $embed_html_code .= '<div class="media-content"><div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/' . $video_id . '?wmode=opaque&theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_time . ($end_time ? '&end=' . $end_time : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div></div>';

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
            'status' => ( $embed_html_code ? 1 : 0 ),
            'embed_code' => $embed_html_code,
            'clean_url' => $clean_url,
        );

    } else {

        //Just return the embed code:
        return $embed_html_code;

    }
}

function view_idea__title($idea, $common_prefix = null){
    if(strlen($common_prefix) > 0){
        $idea['idea__title'] = trim(substr($idea['idea__title'], strlen($common_prefix)));
    }
    return '<span class="text__4736_'.$idea['idea__id'].'">'.htmlentities(trim($idea['idea__title'])).'</span>';
}


function view_idea_notes($read, $is_source = false)
{

    /*
     *
     * A wrapper function that helps manage messages
     * by giving the message additional platform functions
     * such as editing and changing message type.
     *
     * */


    $CI =& get_instance();
    $session_source = superpower_assigned();
    $sources__4485 = $CI->config->item('sources__4485'); //IDEA NOTES
    $sources__6186 = $CI->config->item('sources__6186'); //Read Status
    $is_source = ( $is_source || superpower_active(10984, true) );


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item itemidea is-msg note_sortable msg_source_type_' . $read['read__type'] . '" id="ul-nav-' . $read['read__id'] . '" read__id="' . $read['read__id'] . '" title="'.$read['source__title'].' Posted On '.substr($read['read__time'], 0, 19).'" data-toggle="tooltip" data-placement="top">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="text_message edit-off" id="msgbody_' . $read['read__id'] . '">';
    $ui .= $CI->READ_model->send_message($read['read__message'], $session_source, $read['read__right']);
    $ui .= '</div>';

    //Editing menu:
    if($is_source){
        $ui .= '<div class="note-editor edit-off '.superpower_active(10939).'"><span class="show-on-hover">';

        //Sort:
        if(in_array(4603, $sources__4485[$read['read__type']]['m_parents'])){
            $ui .= '<span title="SORT"><i class="fas fa-bars '.( in_array(4603, $sources__4485[$read['read__type']]['m_parents']) ? 'idea_note_sorting' : '' ).'"></i></span>';
        }

        //Modify:
        $ui .= '<span title="MODIFY"><a href="javascript:idea_note_edit_start(' . $read['read__id'] . ');"><i class="fas fa-pen-square"></i></a></span>';

        $ui .= '</span></div>';


        //Text editing:
        $ui .= '<textarea onkeyup="idea_note_edit_count(' . $read['read__id'] . ')" name="read__message" id="message_body_' . $read['read__id'] . '" class="edit-on hidden msg note-textarea algolia_search" placeholder="'.stripslashes($read['read__message']).'">' . $read['read__message'] . '</textarea>';


        //Editing menu:
        $ui .= '<ul class="msg-nav '.superpower_active(10939).'">';

        //Counter:
        $ui .= '<li class="edit-on hidden"><span id="ideaNoteCount' . $read['read__id'] . '"><span id="charEditingNum' . $read['read__id'] . '">0</span>/' . config_var(4485) . '</span></li>';

        //Save Edit:
        $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-idea white-third" href="javascript:idea_note_modify(' . $read['read__id'] . ',' . $read['read__type'] . ');" title="Save changes" data-toggle="tooltip" data-placement="top"><i class="fas fa-check"></i> Save</a></li>';

        //Cancel Edit:
        $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-idea white-third" href="javascript:idea_note_edit_cancel(' . $read['read__id'] . ');" title="Cancel editing" data-toggle="tooltip" data-placement="top"><i class="fas fa-times"></i></a></li>';

        //Show drop down for message link status:
        $ui .= '<li class="pull-right edit-on hidden"><span class="white-wrapper" style="margin:-5px 0 0 0; display: block;">';
        $ui .= '<select id="message_status_' . $read['read__id'] . '"  class="form-control border" style="margin-bottom:0;" title="Change message status" data-toggle="tooltip" data-placement="top">';
        foreach($CI->config->item('sources__12012') as $source__id => $m){
            $ui .= '<option value="' . $source__id . '" '.( $source__id==$read['read__status'] ? 'selected="selected"' : '' ).'>' . $m['m_name'] . '</option>';
        }
        $ui .= '</select>';
        $ui .= '</span></li>';

        //Update result:
        $ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors

        $ui .= '</ul>';
    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function view_source__icon($source__icon = null)
{
    //A simple function to display the Player Icon OR the default icon if not available:
    if (strlen($source__icon) > 0) {

        return $source__icon;

    } else {
        //Return default icon for sources:
        $CI =& get_instance();
        $sources__2738 = $CI->config->item('sources__2738'); //MENCH
        return $sources__2738[4536]['m_icon'];
    }
}


function view_number($number)
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


function view_interaction($read, $is_parent_tr = false)
{

    $CI =& get_instance();
    $sources__4593 = $CI->config->item('sources__4593'); //Link Type
    $sources__4341 = $CI->config->item('sources__4341'); //Link Table
    $sources__2738 = $CI->config->item('sources__2738');
    $sources__6186 = $CI->config->item('sources__6186'); //Read Status
    $session_source = superpower_assigned();



    if(!isset($sources__4593[$read['read__type']])){
        //We've probably have not yet updated php cache, set error:
        $sources__4593[$read['read__type']] = array(
            'm_icon' => '<i class="fas fa-exclamation-circle"></i>',
            'm_name' => 'Link Type Not Synced in PHP Cache',
            'm_desc' => '',
            'm_parents' => array(),
        );
    }





    //Display the item
    $ui = '<div class="read-list">';


    //Read ID
    $ui .= '<div class="simple-line"><a href="/x?read__id='.$read['read__id'].'" data-toggle="tooltip" data-placement="top" title="'.$sources__4341[4367]['m_name'].'" class="montserrat"><span class="icon-block">'.$sources__4341[4367]['m_icon']. '</span>'.$read['read__id'].'</a></div>';


    //Status
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$sources__4341[6186]['m_name'].( strlen($sources__6186[$read['read__status']]['m_desc']) ? ': '.$sources__6186[$read['read__status']]['m_desc'] : '' ).'"><span class="icon-block">'.$sources__6186[$read['read__status']]['m_icon'].'</span>'.$sources__6186[$read['read__status']]['m_name'].'</span></div>';

    //Time
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $sources__4341[4362]['m_name'].': '.$read['read__time'] . ' PST"><span class="icon-block">'.$sources__4341[4362]['m_icon']. '</span>' . view_time_difference(strtotime($read['read__time'])) . ' ago</span></div>';



    //COINS AWARDED?
    if(in_array($read['read__type'], $CI->config->item('sources_id_6255'))){
        $coins_type = 'read';
    } elseif(in_array($read['read__type'], $CI->config->item('sources_id_12274'))){
        $coins_type = 'source';
    } elseif(in_array($read['read__type'], $CI->config->item('sources_id_12273')) && $read['read__up']>0){
        $coins_type = 'idea';
    } else {
        $coins_type = null;
    }

    //Read Type & Coins
    $ui .= '<div class="simple-line"><a href="/@'.$read['read__type'].'" data-toggle="tooltip" data-placement="top" title="'.$sources__4341[4593]['m_name'].( strlen($sources__4593[$read['read__type']]['m_desc']) ? ': '.$sources__4593[$read['read__type']]['m_desc'] : '' ).'" class="montserrat"><span class="icon-block">'.$sources__4341[4593]['m_icon']. '</span><span class="'.extract_icon_color($sources__4593[$read['read__type']]['m_icon']).'">'. $sources__4593[$read['read__type']]['m_icon'] . '&nbsp;' . $sources__4593[$read['read__type']]['m_name'] . '</span>'.($coins_type ? '&nbsp;<span title="'.$coins_type.' coin awarded" data-toggle="tooltip" data-placement="top"><i class="fas fa-circle '.$coins_type.'"></i></span>' : '').'</a></div>';


    //Hide Sensitive Details?
    if(in_array($read['read__type'] , $CI->config->item('sources_id_4755')) && (!$session_source || $read['read__source']!=$session_source['source__id']) && !superpower_active(12701, true)){

        //Hide Information:
        $ui .= '<div class="simple-line"><span data-toggle="tooltip" class="montserrat" data-placement="top" title="Details are kept private"><span class="icon-block"><i class="fal fa-eye-slash"></i></span>PRIVATE INFORMATION</span></div>';

    } else {

        //Metadata
        if(strlen($read['read__metadata']) > 0){
            $ui .= '<div class="simple-line"><a href="@p12722?read__id=' . $read['read__id'] . '" class="montserrat"><span class="icon-block">'.$sources__4341[6103]['m_icon']. '</span>'.$sources__4341[6103]['m_name']. '</a></div>';
        }

        //Order
        if($read['read__sort'] > 0){
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$sources__4341[4370]['m_name']. '"><span class="icon-block">'.$sources__4341[4370]['m_icon']. '</span>'.view_ordinal($read['read__sort']).'</span></div>';
        }


        //Message
        if(strlen($read['read__message']) > 0 && $read['read__message']!='@'.$read['read__up']){
            $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$sources__4341[4372]['m_name'].'"><span class="icon-block">'.$sources__4341[4372]['m_icon'].'</span><div class="title-block read-msg">'.htmlentities($read['read__message']).'</div></div>';
        }


        //Creator (Do not repeat)
        if($read['read__source'] > 0 && $read['read__source']!=$read['read__up'] && $read['read__source']!=$read['read__down']){

            $add_sources = $CI->SOURCE_model->fetch(array(
                'source__id' => $read['read__source'],
            ));

            $ui .= '<div class="simple-line"><a href="/@'.$add_sources[0]['source__id'].'" data-toggle="tooltip" data-placement="top" title="'.$sources__4341[4364]['m_name'].'" class="montserrat"><span class="icon-block">'.$sources__4341[4364]['m_icon']. '</span><span class="'.extract_icon_color($add_sources[0]['source__icon']).'"><span class="img-block">'.view_source__icon($add_sources[0]['source__icon']) . '</span> ' . $add_sources[0]['source__title'] . '</span></a></div>';

        }

    }


    //5x Relations:
    if(!$is_parent_tr){

        $sources__6232 = $CI->config->item('sources__6232'); //PLATFORM VARIABLES
        foreach($CI->config->item('sources__10692') as $source__id => $m) {

            //Do we have this set?
            if(!intval($read[$sources__6232[$source__id]['m_desc']])){
                continue;
            }

            if(in_array(6160 , $m['m_parents'])){

                //SOURCE
                $sources = $CI->SOURCE_model->fetch(array('source__id' => $read[$sources__6232[$source__id]['m_desc']]));

                $ui .= '<div class="simple-line"><a href="/@'.$sources[0]['source__id'].'" data-toggle="tooltip" data-placement="top" title="'.$sources__4341[$source__id]['m_name'].'" class="montserrat"><span class="icon-block">'.$sources__4341[$source__id]['m_icon']. '</span>'.( $read[$sources__6232[$source__id]['m_desc']]==$read['read__source'] ? $sources__4341[4364]['m_icon']. '&nbsp;' : '' ).'<span class="'.extract_icon_color($sources[0]['source__icon']).' img-block">'.view_source__icon($sources[0]['source__icon']). '&nbsp;'.$sources[0]['source__title'].'</span></a></div>';

            } elseif(in_array(6202 , $m['m_parents'])){

                //IDEA
                $ideas = $CI->IDEA_model->fetch(array('idea__id' => $read[$sources__6232[$source__id]['m_desc']]));

                $ui .= '<div class="simple-line"><a href="/g'.$ideas[0]['idea__id'].'" data-toggle="tooltip" data-placement="top" title="'.$sources__4341[$source__id]['m_name'].'" class="montserrat"><span class="icon-block">'.$sources__4341[$source__id]['m_icon']. '</span>'.$sources__2738[4535]['m_icon']. '&nbsp;'.view_idea__title($ideas[0]).'</a></div>';

            } elseif(in_array(4367 , $m['m_parents'])){

                //PARENT READ
                $reads = $CI->READ_model->fetch(array('read__id' => $read[$sources__6232[$source__id]['m_desc']]));

                $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$sources__4341[$source__id]['m_name'].'">'.$sources__4341[$source__id]['m_icon']. '</span><div class="read-ref">'.view_interaction($reads[0], true).'</div></div>';

            }
        }
    }


    $ui .= '</div>';

    return $ui;
}


function view_url_clean($url)
{
    //Returns the watered-down version of the URL for a cleaner UI:
    return rtrim(str_replace('http://', '', str_replace('https://', '', str_replace('www.', '', $url))), '/');
}


function view_time_difference($t, $second_time = null)
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


function view_cache($config_var_name, $source__id, $micro_status = true, $data_placement = 'top')
{

    /*
     *
     * UI for Platform Cache sources
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item($config_var_name);
    $cache_source = $config_array[$source__id];
    if (!$cache_source) {
        //Could not find matching item
        return false;
    }


    //We have two skins for displaying Status:
    if (is_null($data_placement)) {
        if($micro_status){
            return $cache_source['m_icon'].' ';
        } else {
            return $cache_source['m_icon'].' '.$cache_source['m_name'].' ';
        }
    } else {
        return '<span class="status-label" ' . ( $micro_status && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="' . $data_placement . '" title="' . ($micro_status ? $cache_source['m_name'] : '') . (strlen($cache_source['m_desc']) > 0 ? ($micro_status ? ': ' : '') . $cache_source['m_desc'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache_source['m_icon'] . ' ' . ($micro_status ? '' : $cache_source['m_name']) . '</span>';
    }
}



function view_coins_count_read($idea__id = 0, $source__id = 0){

    $CI =& get_instance();
    $query_filters = array(
        'read__status IN (' . join(',', $CI->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        'read__type IN (' . join(',', $CI->config->item('sources_id_6255')) . ')' => null,
        ( $idea__id > 0 ? 'read__left' : 'read__source' ) => ( $idea__id > 0 ? $idea__id : $source__id ),
    );

    if(isset($_GET['focus__source'])){
        $query_filters['read__source'] = intval($_GET['focus__source']);
    }

    $read_coins = $CI->READ_model->fetch($query_filters, array(), 1, 0, array(), 'COUNT(read__id) as totals');

    if($read_coins[0]['totals'] > 0){
        return '<span class="montserrat read"><span class="icon-block"><i class="fas fa-circle"></i></span>'.view_number($read_coins[0]['totals']).'</span>';
    } else {
        return false;
    }

}


function view_coins_count_source($idea__id = 0, $source__id = 0, $number_only = false){

    $CI =& get_instance();

    if($idea__id){
        $mench = 'source';
        $join_objects = array('read__up');
        $coins_filter = array(
            'read__status IN (' . join(',', $CI->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $CI->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
            'read__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
            'read__right' => $idea__id,
            //ideator_filter() => null,
        );
    } elseif($source__id){
        $mench = 'idea';
        $join_objects = array();
        $coins_filter = array(
            'read__status IN (' . join(',', $CI->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'read__type IN (' . join(',', $CI->config->item('sources_id_12273')) . ')' => null, //IDEA COIN
            'read__up' => $source__id,
        );
    }

    $source_coins = $CI->READ_model->fetch($coins_filter, $join_objects, 0, 0, array(), 'COUNT(read__id) as totals');

    if($number_only){
        return $source_coins[0]['totals'];
    } else {
        return ($source_coins[0]['totals'] > 0 ? '<span class="montserrat '.$mench.'"><span class="icon-block"><i class="fas fa-circle"></i></span>'.view_number($source_coins[0]['totals']).'</span>' : null);
    }
}

function view_idea_icon($can_click, $completion_percentage){
    return ( $can_click ? ( $completion_percentage>=100 ? '<i class="fas fa-circle read"></i>' : '<i class="fas fa-play-circle read"></i>' ) : '<i class="fas fa-circle idea"></i>' );
}

function view_idea_read($idea, $common_prefix = null, $show_editor = false, $completion_rate = null, $recipient_source = false)
{

    //See if user is logged-in:
    $CI =& get_instance();
    if(!$recipient_source){
        $recipient_source = superpower_assigned();
    }


    if(!$completion_rate){
        if($recipient_source){
            $completion_rate = $CI->READ_model->completion_progress($recipient_source['source__id'], $idea);
        } else {
            $completion_rate['completion_percentage'] = 0;
        }
    }

    $idea_stats = idea_stats($idea['idea__metadata']);
    $is_saved = ( isset($idea['read__type']) && $idea['read__type']==12896 );
    $can_click = ( $completion_rate['completion_percentage']>0 || $show_editor || $is_saved ); //|| $recipient_source['source__id']


    //Build View:
    $ui  = '<div id="ap_idea_'.$idea['idea__id'].'" '.( isset($idea['read__id']) ? ' sort-link-id="'.$idea['read__id'].'" ' : '' ).' class="list-group-item no-side-padding '.( $show_editor ? 'home_sort' : '' ).( $can_click ? ' itemread ' : '' ).'">';

    $ui .= ( $can_click ? '<a href="/'.$idea['idea__id'] . '" class="itemread">' : '' );

    //Right Stats:
    if($idea_stats['duration_average'] || $idea_stats['ideas_average']){
        $ui .= '<div class="pull-right montserrat" style="'.( $show_editor ? 'width:155px;' : 'width:138px;' ).'"><span style="width:53px; display: inline-block;">'.( $idea_stats['ideas_average'] ? '<i class="fas fa-circle idea"></i><span style="padding-left:3px;" class="idea">'.$idea_stats['ideas_average'].'</span>' : '' ).'</span>'.( $idea_stats['duration_average'] ? '<span class="mono-space">'.view_time_hours($idea_stats['duration_average']).'</span>': '' ).'</div>';
    }


    if($can_click && $completion_rate['completion_percentage']>0 && $completion_rate['completion_percentage']<100){
        $ui .= '<div class="progress-bg-list" title="Read '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
    }

    $ui .= '<span class="icon-block">'.view_idea_icon($can_click, $completion_rate['completion_percentage']).'</span>';

    $ui .= '<b class="'.( $can_click ? 'montserrat' : '' ).' idea-url title-block">'.view_idea__title($idea, $common_prefix).'</b>';

    //Search for Idea Image:
    if($show_editor){
        if($is_saved){

            $ui .= '<div class="note-editor edit-off">';
            $ui .= '<span class="show-on-hover">';
            $ui .= '<span><a href="javascript:void(0);" title="Unsave" data-toggle="tooltip" data-placement="left" onclick="read_toggle_saved('.$idea['idea__id'].');$(\'#ap_idea_'.$idea['idea__id'].'\').remove();"><i class="fas fa-times" style="margin-top: 10px;"></i></a></span>';
            $ui .= '</span>';
            $ui .= '</div>';

        } else {

            $ui .= '<div class="note-editor edit-off">';

            $ui .= '<span class="show-on-hover">';

            $ui .= '<span class="read-sorter" title="SORT"><i class="fas fa-bars"></i></span>';

            $ui .= '<span title="REMOVE"><span class="read_remove_item" idea__id="'.$idea['idea__id'].'"><i class="fas fa-times"></i></span></span>';

            $ui .= '</span>';
            $ui .= '</div>';

        }
    }

    $ui .= ( $can_click ? '</a>' : '' );
    $ui .= '</div>';

    return $ui;
}


function view_idea_scores_answer($idea__id, $depth_levels, $original_depth_levels, $previous_idea__type){

    if($depth_levels<=0){
        //End recursion:
        return false;
    }

    //We're going 1 level deep:
    $depth_levels--;

    //Go down recursively:
    $CI =& get_instance();
    $sources__6186 = $CI->config->item('sources__6186'); //Read Status
    $sources__4486 = $CI->config->item('sources__4486');
    $sources__4737 = $CI->config->item('sources__4737'); // Idea Status
    $sources__7585 = $CI->config->item('sources__7585'); // Idea Subtypes


    $ui = null;
    foreach($CI->READ_model->fetch(array(
        'read__left' => $idea__id,
        'read__type IN (' . join(',', $CI->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
        'read__status IN (' . join(',', $CI->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        'idea__status IN (' . join(',', $CI->config->item('sources_id_7356')) . ')' => null, //ACTIVE
    ), array('read__right'), 0, 0, array('read__sort' => 'ASC')) as $idea_read){

        //Prep Metadata:
        $metadata = unserialize($idea_read['read__metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->READ_model->fetch(array(
            'read__status IN (' . join(',', $CI->config->item('sources_id_7360')) . ')' => null, //ACTIVE
            'read__type' => 4231, //IDEA NOTES Messages
            'read__right' => $idea_read['idea__id'],
        ), array(), 0, 0, array('read__sort' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Link Type: '.$sources__4486[$idea_read['read__type']]['m_name'].'">'. $sources__4486[$idea_read['read__type']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Read Status: '.$sources__6186[$idea_read['read__status']]['m_name'].'">'. $sources__6186[$idea_read['read__status']]['m_icon'] . '</span>';

        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Type: '.$sources__7585[$idea_read['idea__type']]['m_name'].'">'. $sources__7585[$idea_read['idea__type']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Status: '.$sources__4737[$idea_read['idea__status']]['m_name'].'">'. $sources__4737[$idea_read['idea__status']]['m_icon']. '</span>';
        $ui .= '<a href="?idea__id='.$idea_read['idea__id'].'&depth_levels='.$original_depth_levels.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this idea"><u>' .   view_idea__title($idea_read) . '</u></a>';

        $ui .= ' [<span data-toggle="tooltip" data-placement="top" title="Completion Marks">'.( ($idea_read['read__type'] == 4228 && in_array($previous_idea__type , $CI->config->item('sources_id_6193') /* OR Ideas */ )) || ($idea_read['read__type'] == 4229) ? view_idea_marks($idea_read) : '' ).'</span>]';

        if(count($messages) > 0){
            $ui .= ' <a href="javascript:void(0);" onclick="$(\'.messages-'.$idea_read['idea__id'].'\').toggleClass(\'hidden\');"><i class="fas fa-comment"></i><b>' .  count($messages) . '</b></a>';
        }
        $ui .= '</div>';

        //Display Messages:
        $ui .= '<div class="messages-'.$idea_read['idea__id'].' hidden">';
        foreach($messages as $msg) {
            $ui .= '<div class="tip_bubble">';
            $ui .= $CI->READ_model->send_message($msg['read__message']);
            $ui .= '</div>';
        }
        $ui .= '</div>';

        //Go Recursively down:
        $ui .=  view_idea_scores_answer($idea_read['idea__id'], $depth_levels, $original_depth_levels, $idea_read['idea__type']);

    }

    //Return the wrapped UI if existed:
    return ($ui ? '<div class="inline-box">' . $ui . '</div>' : false);
}

function view_radio_sources($parent_source__id, $child_source__id, $enable_mulitiselect, $show_max = 25){

    /*
     * Print UI for
     * */

    $CI =& get_instance();
    $count = 0;

    $ui = '<div class="list-group list-radio-select radio-'.$parent_source__id.'">';

    if(!count($CI->config->item('sources_id_'.$parent_source__id))){
        return false;
    }

    foreach($CI->config->item('sources__'.$parent_source__id) as $source__id => $m) {
        $ui .= '<a href="javascript:void(0);" onclick="account_update_radio('.$parent_source__id.','.$source__id.','.$enable_mulitiselect.')" class="item'.extract_icon_color($m['m_icon']).' list-group-item montserrat itemsetting item-'.$source__id.' '.( $count>=$show_max ? 'extra-items-'.$parent_source__id.' hidden ' : '' ).( count($CI->READ_model->fetch(array(
                'read__up' => $source__id,
                'read__down' => $child_source__id,
                'read__type IN (' . join(',', $CI->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
                'read__status IN (' . join(',', $CI->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            )))>0 ? ' active ' : '' ). '"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'<span class="change-results"></span></a>';
        $count++;
    }


    //Did we have too many items?
    if($count>=$show_max){
        //Show "Show more" button
        $ui .= '<a href="javascript:void(0);" class="list-group-item itemsource itemsetting montserrat extra-items-'.$parent_source__id.'" onclick="$(\'.extra-items-'.$parent_source__id.'\').toggleClass(\'hidden\')"><span class="icon-block"><i class="fas fa-plus-circle"></i></span>Show '.($count-$show_max).' more</a>';
    }

    $ui .= '</div>';

    return $ui;
}


function view_idea_marks($idea_read){

    //Validate core inputs:
    if(!isset($idea_read['read__metadata']) || !isset($idea_read['read__type'])){
        return false;
    }

    //prep metadata:
    $read__metadata = unserialize($idea_read['read__metadata']);

    //Return mark:
    return ( $idea_read['read__type'] == 4228 ? ( !isset($read__metadata['tr__assessment_points']) || $read__metadata['tr__assessment_points'] == 0 ? '' : '<span class="score-range">[<span style="'.( $read__metadata['tr__assessment_points']>0 ? 'font-weight:bold;' : ( $read__metadata['tr__assessment_points'] < 0 ? 'font-weight:bold;' : '' )).'">' . ( $read__metadata['tr__assessment_points'] > 0 ? '+' : '' ) . $read__metadata['tr__assessment_points'].'</span>]</span>' ) : '<span class="score-range">['.$read__metadata['tr__conditional_score_min'] . ( $read__metadata['tr__conditional_score_min']==$read__metadata['tr__conditional_score_max'] ? '' : '-'.$read__metadata['tr__conditional_score_max'] ).'%]</span>' );

}


function view_idea($idea, $idea_linked_id = 0, $is_parent = false, $is_source = false, $message_input = null, $extra_class = null, $control_enabled = true)
{

    $CI =& get_instance();
    $session_source = superpower_assigned();
    $sources__6186 = $CI->config->item('sources__6186');
    $sources__4737 = $CI->config->item('sources__4737'); //IDEA STATUS
    $sources__7585 = $CI->config->item('sources__7585');
    $sources__4486 = $CI->config->item('sources__4486');
    $sources__2738 = $CI->config->item('sources__2738');
    $sources__12413 = $CI->config->item('sources__12413');

    //READ
    $read__id = ( isset($idea['read__id']) ? $idea['read__id'] : 0 );
    $is_idea_link = ($read__id && in_array($idea['read__type'], $CI->config->item('sources_id_4486')));

    //IDEA
    $idea_stats = idea_stats($idea['idea__metadata']);
    $is_public = in_array($idea['idea__status'], $CI->config->item('sources_id_7355'));
    $is_source = ( !$is_idea_link ? false : $is_source ); //Disable Edits on Idea List Page
    $show_toolbar = ($control_enabled && superpower_active(12673, true));




    //IDAE INFO BAR
    $box_items_list = '';

    //READ STATUS
    if($read__id && !in_array($idea['read__status'], $CI->config->item('sources_id_7359'))){
        $box_items_list .= '<span class="inline-block"><span data-toggle="tooltip" data-placement="right" title="'.$sources__6186[$idea['read__status']]['m_name'].' @'.$idea['read__status'].'">' . $sources__6186[$idea['read__status']]['m_icon'] . '</span>&nbsp;</span>';
    }


    $ui = '<div read__id="' . $read__id . '" idea-id="' . $idea['idea__id'] . '" class="list-group-item no-side-padding itemidea itemidealist ideas_sortable paddingup level2_in object_saved saved_idea_'.$idea['idea__id'] . ' idea_line_' . $idea['idea__id'] . ( $is_parent ? ' parent-idea ' : '' ) . ' idea__tr_'.$read__id.' '.$extra_class.'" style="padding-left:0;">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="MENCHcolumn1">';
        $ui .= '<div class="block">';

            //IDEA ICON:
            $ui .= '<span class="icon-block"><a href="/g'.$idea['idea__id'].( isset($_GET['focus__source']) ? '?focus__source='.intval($_GET['focus__source']) : '' ).'" title="Idea Weight: '.number_format($idea['idea__weight'], 0).'">'.$sources__2738[4535]['m_icon'].'</a></span>';

            //IDEA TITLE
            $ui .= view_input_text(4736, $idea['idea__title'], $idea['idea__id'], $is_source, (($idea['read__sort']*100)+1));

            if(!$show_toolbar){

                $ui .= $box_items_list;

                if($is_idea_link && !$is_public){
                    $ui .= '<span class="inline-block"><span data-toggle="tooltip" data-placement="right" title="'.$sources__4737[$idea['idea__status']]['m_name'].' @'.$idea['idea__status'].'">' . $sources__4737[$idea['idea__status']]['m_icon'] . '</span>&nbsp;</span>';
                }

            }

        $ui .= '</div>';
    $ui .= '</td>';


    //READ
    $ui .= '<td class="MENCHcolumn2 read">';
    $ui .= view_coins_count_read($idea['idea__id']);
    $ui .= '</td>';



    //SOURCE
    $ui .= '<td class="MENCHcolumn3 source">';


    if($is_idea_link && $control_enabled && $is_source){

        //RIGHT EDITING:
        $ui .= '<div class="pull-right inline-block '.superpower_active(10939).'">';
        $ui .= '<div class="note-editor edit-off">';
        $ui .= '<span class="show-on-hover">';

        if(!$is_parent){
            $ui .= '<span title="SORT"><i class="fas fa-bars black idea-sort-handle"></i></span>';
        }

        //Unlink:
        $ui .= '<span title="UNLINK"><a href="javascript:void(0);" onclick="idea_unlink('.$idea['idea__id'].', '.$idea['read__id'].', '.( $is_parent ? 1 : 0 ).')"><i class="fas fa-times black"></i></a></span>';

        $ui .= '</span>';
        $ui .= '</div>';
        $ui .= '</div>';

    }


    //SOURCE STATS
    $ui .= view_coins_count_source($idea['idea__id'], 0);

    $ui .= '</td>';
    $ui .= '</tr></table>';



    if($message_input){
        $ui .= '<div class="idea-footer hideIfEmpty">' . $CI->READ_model->send_message($message_input, $session_source) . '</div>';
    }


    if($show_toolbar && superpower_active(12673, true)){

        //Idea Toolbar
        $ui .= '<div class="space-content ' . superpower_active(12673) . '" style="padding-left:25px; padding-top:13px;">';

        $ui .= $box_items_list;

        //IDEA TYPE
        $ui .= '<div class="inline-block">'.view_input_dropdown(7585, $idea['idea__type'], null, $is_source, false, $idea['idea__id']).'</div>';

        //IDEA STATUS
        $ui .= '<div class="inline-block">' . view_input_dropdown(4737, $idea['idea__status'], null, $is_source, false, $idea['idea__id']) . ' </div>';





        if($read__id){

            $read__metadata = unserialize($idea['read__metadata']);

            //IDEA LINK BAR
            $ui .= '<span class="' . superpower_active(12700) . '">';

            //LINK TYPE
            $ui .= view_input_dropdown(4486, $idea['read__type'], null, $is_source, false, $idea['idea__id'], $idea['read__id']);

            //LINK MARKS
            $ui .= '<span class="link_marks settings_4228 '.( $idea['read__type']==4228 ? : 'hidden' ).'">';
            $ui .= view_input_text(4358, ( isset($read__metadata['tr__assessment_points']) ? $read__metadata['tr__assessment_points'] : '' ), $idea['read__id'], $is_source, ($idea['read__sort']*10)+2 );
            $ui .='</span>';


            //LINK CONDITIONAL RANGE
            $ui .= '<span class="link_marks settings_4229 '.( $idea['read__type']==4229 ? : 'hidden' ).'">';
            //MIN
            $ui .= view_input_text(4735, ( isset($read__metadata['tr__conditional_score_min']) ? $read__metadata['tr__conditional_score_min'] : '' ), $idea['read__id'], $is_source, ($idea['read__sort']*10)+3);
            //MAX
            $ui .= view_input_text(4739, ( isset($read__metadata['tr__conditional_score_max']) ? $read__metadata['tr__conditional_score_max'] : '' ), $idea['read__id'], $is_source, ($idea['read__sort']*10)+4);
            $ui .= '</span>';
            $ui .= '</span>';

        }




        //PREVIOUS IDEAS COUNT
        $ui .= '<span class="inline-block montserrat idea" title="'.$sources__12413[11019]['m_name'].'" style="width:42px; text-align:right;">';
        $previous_ideas = $CI->READ_model->fetch(array(
            'read__right' => $idea['idea__id'],
            'read__type IN (' . join(',', $CI->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__status IN (' . join(',', $CI->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array(), 'COUNT(read__id) as total_ideas');
        if($previous_ideas[0]['total_ideas'] > 0){
            $ui .= $previous_ideas[0]['total_ideas'].( $is_parent ? '<a href="/idea/navigate/'.$idea['idea__id'].'/'.$idea_linked_id.'/previous">'.$sources__12413[11019]['m_icon'].'</a>' : $sources__12413[11019]['m_icon'] );
        }
        $ui .= '</span>';


        //NEXT IDEAS COUNT
        $next_ideas = $CI->READ_model->fetch(array(
            'read__left' => $idea['idea__id'],
            'read__type IN (' . join(',', $CI->config->item('sources_id_4486')) . ')' => null, //IDEA LINKS
            'read__status IN (' . join(',', $CI->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array(), 'COUNT(read__id) as total_ideas');
        if($next_ideas[0]['total_ideas'] > 0){

            $ui .= '<span class="inline-block montserrat idea" title="'.$sources__12413[11020]['m_name'].'" style="width:45px;">'.( $is_parent ? '<a href="/idea/navigate/'.$idea['idea__id'].'/'.$idea_linked_id.'/next">'.$sources__12413[11020]['m_icon'].'</a>' : $sources__12413[11020]['m_icon'] ).$next_ideas[0]['total_ideas'].'</span>';

            //TREE SIZE
            if($idea_stats['ideas_average'] > 0){
                $ui .= '<span class="inline-block montserrat idea" title="'.$sources__12413[13177]['m_name'].': '.$idea_stats['ideas_min'].' - '.$idea_stats['ideas_max'].'" data-toggle="tooltip" data-placement="top">'.$sources__12413[13177]['m_icon'].'&nbsp;'.number_format($idea_stats['ideas_average'], 0).'</span>';
            }

        }






        $ui .= '</div>';


    }

    $ui .= '</div>';



    return $ui;

}




function view_caret($source__id, $m, $object__id){
    //Display drop down menu:
    $CI =& get_instance();

    $superpower_actives = array_intersect($CI->config->item('sources_id_10957'), $m['m_parents']);

    $ui = '<li class="nav-item dropdown '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'" title="'.$m['m_name'].'" data-toggle="tooltip" data-placement="top">';
    $ui .= '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>';
    $ui .= '<div class="dropdown-menu">';
    foreach($CI->config->item('sources__'.$source__id) as $source__id2 => $m2){
        $ui .= '<a class="dropdown-item montserrat '.extract_icon_color($m2['m_icon']).'" href="' . $m2['m_desc'] . $object__id . '"><span class="icon-block">'.view_source__icon($m2['m_icon']).'</span> '.$m2['m_name'].'</a>';
    }
    $ui .= '</div>';
    $ui .= '</li>';

    return $ui;
}


function view_idea_list($idea, $ideas_next, $recipient_source, $prefix_statement = null, $show_next = true){

    //If no list just return the next step:
    if(!count($ideas_next)){
        return ( $show_next ? view_idea_next_previous($idea['idea__id'], $recipient_source) : false );
    }

    $CI =& get_instance();

    if(count($ideas_next)){

        //List children so they know what's ahead:
        $common_prefix = idea_calc_common_prefix($ideas_next, 'idea__title');
        $prefix_statement = ( $prefix_statement ? $prefix_statement : $common_prefix );

        echo '<div class="read-topic">'.(strlen($prefix_statement) ? trim($prefix_statement) : '<span class="icon-block">&nbsp;</span>UP NEXT:').'</div>';
        echo '<div class="list-group">';
        foreach($ideas_next as $key => $next_idea){
            echo view_idea_read($next_idea, $common_prefix);
        }
        echo '</div>';
    }

    if($show_next){
        view_idea_next_previous($idea['idea__id'], $recipient_source);
        echo '<div class="doclear">&nbsp;</div>';
    }
}

function view_idea_next_previous($idea__id, $recipient_source){

    $CI =& get_instance();
    $sources__11035 = $CI->config->item('sources__11035'); //MENCH NAVIGATION

    //PREVIOUS:
    echo view_idea_previous_read($idea__id, $recipient_source);

    //NEXT:
    echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-read btn-circle" href="/read/next/'.$idea__id.'">'.$sources__11035[12211]['m_icon'].'</a></div>';

}

function view_idea_previous_read($idea__id, $recipient_source){

    if(!$recipient_source || $recipient_source['source__id'] < 1){
        return null;
    }

    //Reads
    $CI =& get_instance();
    $ui = null;
    $idea_level_up = 0;
    $previous_level_id = 0; //The ID of the Idea one level up
    $player_read_ids = $CI->READ_model->ids($recipient_source['source__id']);
    $read_list_ui = null;
    $sources__11035 = $CI->config->item('sources__11035'); //MENCH NAVIGATION

    if(in_array($idea__id, $player_read_ids)){

        //A reading list item:
        $ideas_this = $CI->IDEA_model->fetch(array(
            'idea__id' => $idea__id,
        ));

    } else {

        //Find it:
        $recursive_parents = $CI->IDEA_model->recursive_parents($idea__id, true, true);
        foreach($recursive_parents as $grand_parent_ids) {
            foreach(array_intersect($grand_parent_ids, $player_read_ids) as $intersect) {
                foreach($grand_parent_ids as $previous_idea__id) {

                    if($idea_level_up==0){
                        //Remember the first parent for the back button:
                        $previous_level_id = $previous_idea__id;
                    }

                    $ideas_this = $CI->IDEA_model->fetch(array(
                        'idea__id' => $previous_idea__id,
                    ));

                    $completion_rate = $CI->READ_model->completion_progress($recipient_source['source__id'], $ideas_this[0]);

                    $idea_level_up++;

                    if ($previous_idea__id == $intersect) {
                        $read_list_ui .= view_idea_read($ideas_this[0], null, false, $completion_rate);
                        break;
                    }
                }
            }
        }
    }


    //Did We Find It?
    if($previous_level_id > 0){

        //Previous
        if(isset($_GET['previous_read']) && $_GET['previous_read']>0){
            $ui .= '<div class="inline-block margin-top-down edit_select_answer pull-left"><a class="btn btn-read btn-circle" href="/'.$_GET['previous_read'].'" title="'.$sources__11035[12991]['m_name'].'">'.$sources__11035[12991]['m_icon'].'</a></div>';
        } else {
            $ui .= '<div class="inline-block margin-top-down edit_select_answer pull-left"><a class="btn btn-read btn-circle" href="/read/previous/'.$previous_level_id.'/'.$idea__id.'" title="'.$sources__11035[12991]['m_name'].'">'.$sources__11035[12991]['m_icon'].'</a></div>';
        }


        //Is Saved?
        $is_saveded = count($CI->READ_model->fetch(array(
            'read__up' => $recipient_source['source__id'],
            'read__right' => $idea__id,
            'read__type' => 12896, //SAVED
            'read__status IN (' . join(',', $CI->config->item('sources_id_7359')) . ')' => null, //PUBLIC
        )));

        $ui .= '<div class="inline-block margin-top-down pull-left edit_select_answer"><a class="btn btn-read btn-circle" href="javascript:void(0);" onclick="read_toggle_saved('.$idea__id.')"><i class="fas fa-bookmark toggle_saved '.( $is_saveded ? '' : 'hidden' ).'"></i><i class="fal fa-bookmark toggle_saved '.( $is_saveded ? 'hidden' : '' ).'"></i></a></div>';

        //Main Reads:
        if($read_list_ui){

            $ui .= '<div class="focus_reads_bottom hidden">';
            $ui .= '<div class="list-group">';
            $ui .= $read_list_ui;
            $ui .= '</div>';
            $ui .= '</div>';

        }

    }

    return $ui;

}


function view_idea_note_source($idea__id, $note_type_source__id, $idea_notes, $is_source){

    $CI =& get_instance();
    $sources__11018 = $CI->config->item('sources__11018');

    $ui = '<div class="list-group">';
    foreach($idea_notes as $source) {
        $ui .= view_source($source, false, null, true, $is_source);
    }

    if( $is_source ){
        $ui .= '<div class="list-group-item itemsource '.superpower_active(10939).'" style="padding:5px 0;">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$sources__11018[$note_type_source__id]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control IdeaAddPrevious form-control-thick doupper add-input montserrat algolia_search dotransparent"
                           maxlength="' . config_var(6197) . '"
                           idea-id="' . $idea__id . '"
                           id="add-source-idea-' . $idea__id . '"
                           placeholder="'.$sources__11018[$note_type_source__id]['m_name'].'">
                </div><div class="algolia_pad_search hidden idea_pad_top"></div></div>';
    }
    $ui .= '</div>';

    return $ui;
}

function view_idea_note_mix($note_type_source__id, $idea_notes){

    $CI =& get_instance();
    $sources__4485 = $CI->config->item('sources__4485'); //IDEA NOTES
    $handles_uploads = (in_array($note_type_source__id, $CI->config->item('sources_id_12359')));
    $handles_url = (in_array($note_type_source__id, $CI->config->item('sources_id_7551')) || in_array($note_type_source__id, $CI->config->item('sources_id_4986')));
    $session_source = superpower_assigned();
    $ui = '';



    if(!count($idea_notes)){
        $ui .= '<div class="no_notes_' . $note_type_source__id .'" style="margin-bottom:13px;">';
        $ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block">'.$sources__4485[$note_type_source__id]['m_icon'].'</span>No '.ucwords(strtolower($sources__4485[$note_type_source__id]['m_name'])).' yet, Be the first to post one!</div>';
        $ui .= '</div>';
    }


    //Show no-Message notifications for each message type:
    $ui .= '<div id="idea_notes_list_'.$note_type_source__id.'" class="list-group">';

    //List current notes:
    foreach($idea_notes as $idea_notes) {
        $ui .= view_idea_notes($idea_notes, ($idea_notes['read__source']==$session_source['source__id']));
    }

    //ADD NEW:
    $ui .= '<div class="list-group-item itemidea space-left add_notes_' . $note_type_source__id .'">';
    $ui .= '<div class="add_notes_form">';
    $ui .= '<form class="box box' . $note_type_source__id . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">'; //Used for dropping files



    $ui .= '<textarea onkeyup="idea_note_count_new('.$note_type_source__id.')" class="form-control msg note-textarea algolia_search new-note" note-type-id="' . $note_type_source__id . '" id="read__message' . $note_type_source__id . '" placeholder="WRITE'.( $handles_url ? ', PASTE URL' : '' ).( $handles_uploads ? ', DROP FILE' : '' ).'" style="margin-top:6px;"></textarea>';



    $ui .= '<table class="table table-condensed"><tr>';


    //Save button:
    $ui .= '<td style="width:85px; padding: 10px 0 0 0;"><a href="javascript:idea_note_add_text('.$note_type_source__id.');" class="btn btn-idea save_notes_'.$note_type_source__id.'"><i class="fas fa-plus"></i></a></td>';


    //File counter:
    $ui .= '<td style="padding: 10px 0 0 0; font-size: 0.85em;"><span id="ideaNoteNewCount' . $note_type_source__id . '" class="hidden"><span id="charNum' . $note_type_source__id . '">0</span>/' . config_var(4485).'</span></td>';


    //Upload File:
    if($handles_uploads){
        $ui .= '<td style="width:42px; padding: 10px 0 0 0;">';
        $ui .= '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType'.$note_type_source__id.'" />';
        $ui .= '<label class="file_label_'.$note_type_source__id.'" for="fileIdeaType'.$note_type_source__id.'" data-toggle="tooltip" title="Upload files up to ' . config_var(11063) . 'MB, or upload elsewhere & paste URL here" data-placement="top"><span class="icon-block"><i class="far fa-paperclip"></i></span></label>';
        $ui .= '</td>';
    }


    $ui .= '</tr></table>';


    //Response result:
    $ui .= '<div class="note_error_'.$note_type_source__id.'"></div>';


    $ui .= '</form>';
    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;

}

function view_platform_message($source__id){
    $CI =& get_instance();
    $sources__12687 = $CI->config->item('sources__12687');
    if(!substr_count($sources__12687[$source__id]['m_desc'], " | ")){
        //Single message:
        return $sources__12687[$source__id]['m_desc'];
    } else {
        //Random message:
        $line_messages = explode(" | ", $sources__12687[$source__id]['m_desc']);
        return $line_messages[rand(0, (count($line_messages) - 1))];
    }
}

function view_unauthorized_message($superpower_source__id = 0){

    $session_source = superpower_assigned($superpower_source__id);

    if(!$session_source){
        if(!$superpower_source__id){

            //Missing Session
            return 'You must login to continue.';

        } else {

            //Missing Superpower:
            $CI =& get_instance();
            $sources__10957 = $CI->config->item('sources__10957');
            return 'You are missing the required superpower of '.$sources__10957[$superpower_source__id]['m_name'];

        }
    }


    return null;

}

function view_time_hours($total_seconds, $hide_hour = false){

    if(!$total_seconds){
        return 'START';
    }

    //Turns seconds into HH:MM:SS
    $hours = floor($total_seconds/3600);
    $minutes = floor(fmod($total_seconds, 3600)/60);
    $seconds = fmod($total_seconds, 60);

    return ( $hide_hour && !$hours ? '' : str_pad($hours, 2, "0", STR_PAD_LEFT).':' ).str_pad($minutes, 2, "0", STR_PAD_LEFT).':'.str_pad($seconds, 2, "0", STR_PAD_LEFT);
}

function view_idea_cover($idea, $show_editor, $common_prefix = null, $completion_rate = null){


    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    $sources__13291 = $CI->config->item('sources__13291');
    $recipient_source = superpower_assigned();
    $idea_stats = idea_stats($idea['idea__metadata']);

    $ui  = '<a href="/'.$idea['idea__id'] . '" id="ap_idea_'.$idea['idea__id'].'" '.( isset($idea['read__id']) ? ' sort-link-id="'.$idea['read__id'].'" ' : '' ).' class="cover-block '.( $show_editor ? ' home_sort ' : '' ).'">';

    $ui .= '<div class="cover-image">';
    if($recipient_source){
        if(!$completion_rate){
            $completion_rate = $CI->READ_model->completion_progress($recipient_source['source__id'], $idea);
        }
        if($completion_rate['completion_percentage']>0){
            $ui .= '<div class="progress-bg-image" title="Read '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
        }
    }

    $ui .= idea_fetch_cover($idea['idea__id'], true);

    //TOP LEFT
    $ui .= '<span class="media-info top-left">';
    $ui .= $sources__13291[12273]['m_icon'].'<span style="padding-left: 2px;">'.( $idea_stats['ideas_average'] ? $idea_stats['ideas_average'] : '1' ).'</span>';
    $ui .= '</span>';

    //TOP RIGHT
    if($idea_stats['duration_average']){
        $ui .= '<span class="media-info top-right">'.view_time_hours($idea_stats['duration_average']).'</span>';
    }

    //Search for Idea Image:
    if($show_editor){
        $ui .= '<span class="media-info bottom-left read-sorter" title="SORT"><i class="fas fa-bars"></i></span>';
        $ui .= '<span class="media-info bottom-right read_remove_item" idea__id="'.$idea['idea__id'].'" title="REMOVE"><i class="fas fa-times"></i></span>';
    }
    $ui .= '</div>';

    $ui .= '<b class="montserrat" style="font-size: 0.9em;">'.view_idea__title($idea, $common_prefix).'</b>';

    $ui .= '</a>';

    return $ui;

}

function view_source_basic($source)
{
    $ui = '<div class="list-group-item no-side-padding">';
    $ui .= '<span class="icon-block">' . view_source__icon($source['source__icon']) . '</span>';
    $ui .= '<span class="title-block title-no-right"><span '.( strlen($source['read__message']) > 0 ? ' class="underdot" title="'.$source['read__message'].'" data-toggle="tooltip" data-placement="top" ' : '' ).'>'.$source['source__title'].'</span></span>';
    $ui .= '</div>';
    return $ui;
}


function view_source($source, $is_parent = false, $extra_class = null, $control_enabled = false, $is_source = false)
{

    $CI =& get_instance();
    $session_source = superpower_assigned();
    $sources__6177 = $CI->config->item('sources__6177'); //Source Status
    $sources__2738 = $CI->config->item('sources__2738');
    $sources__4592 = $CI->config->item('sources__4592');
    $sources__6186 = $CI->config->item('sources__6186'); //Read Status

    $read__id = (isset($source['read__id']) ? $source['read__id'] : 0);
    $is_link_source = ( $read__id > 0 && in_array($source['read__type'], $CI->config->item('sources_id_4592')));
    $is_read_progress = ( $read__id > 0 && in_array($source['read__type'], $CI->config->item('sources_id_12227')));
    $is_source_only = ( $read__id > 0 && in_array($source['read__type'], $CI->config->item('sources_id_7551')));
    $show_toolbar = ($control_enabled && superpower_active(12706, true));
    $has_source_editor = superpower_active(10967, true);

    $source__profiles = $CI->READ_model->fetch(array(
        'read__type IN (' . join(',', $CI->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
        'read__down' => $source['source__id'], //This child source
        'read__status IN (' . join(',', $CI->config->item('sources_id_7360')) . ')' => null, //ACTIVE
        'source__status IN (' . join(',', $CI->config->item('sources_id_7358')) . ')' => null, //ACTIVE
    ), array('read__up'), 0, 0, array('source__weight' => 'DESC'));

    $is_public = in_array($source['source__status'], $CI->config->item('sources_id_7357'));
    $is_link_published = ( !$read__id || in_array($source['read__status'], $CI->config->item('sources_id_7359')));
    $is_hidden = filter_array($source__profiles, 'source__id', '4755') || in_array($source['source__id'], $CI->config->item('sources_id_4755'));

    if(!$session_source && (!$is_public || !$is_link_published)){
        //Not logged in, so should only see published:
        return false;
    } elseif($is_hidden && !superpower_assigned(12701)){
        //Cannot see this private read:
        return false;
    } elseif($is_hidden && !superpower_active(12701, true)){
        //They don't have the needed superpower:
        return false;
    }


    //SOURCE INFO BAR
    $box_items_list = '';

    //SOURCE STATUS
    if(!$is_public){
        $box_items_list .= '<span class="inline-block source__status_' . $source['source__id'].'"><span data-toggle="tooltip" data-placement="right" title="'.$sources__6177[$source['source__status']]['m_name'].' @'.$source['source__status'].'">' . $sources__6177[$source['source__status']]['m_icon'] . '</span>&nbsp;</span>';
    }

    //READ STATUS
    if($read__id){
        if(!$is_link_published){
            $box_items_list .= '<span class="inline-block read__status_' . $read__id .'"><span data-toggle="tooltip" data-placement="right" title="'.$sources__6186[$source['read__status']]['m_name'].' @'.$source['read__status'].'">' . $sources__6186[$source['read__status']]['m_icon'] . '</span>&nbsp;</span>';
        }
    }



    //PORTFOLIO COUNT (SYNC WITH NEXT IDEA COUNT)
    $child_counter = '';
    if($has_source_editor){
        $source__portfolio_count = $CI->READ_model->fetch(array(
            'read__up' => $source['source__id'],
            'read__type IN (' . join(',', $CI->config->item('sources_id_4592')) . ')' => null, //SOURCE LINKS
            'read__status IN (' . join(',', $CI->config->item('sources_id_7359')) . ')' => null, //PUBLIC
            'source__status IN (' . join(',', $CI->config->item('sources_id_7357')) . ')' => null, //PUBLIC
        ), array('read__down'), 0, 0, array(), 'COUNT(source__id) as totals');
        if($source__portfolio_count[0]['totals'] > 0){
            $child_counter .= '<span class="pull-right" '.( $show_toolbar ? ' style="margin-top: -19px;" ' : '' ).'><span class="icon-block doright montserrat source" title="'.number_format($source__portfolio_count[0]['totals'], 0).' PORTFOLIO SOURCES">'.view_number($source__portfolio_count[0]['totals']).'</span></span>';
            $child_counter .= '<div class="doclear">&nbsp;</div>';
        }
    }


    //ROW
    $ui = '<div class="list-group-item no-side-padding itemsource en-item object_saved saved_source_'.$source['source__id'].' source___' . $source['source__id'] . ( $read__id > 0 ? ' tr_' . $source['read__id'].' ' : '' ) . ( $is_parent ? ' parent-source ' : '' ) . ' '. $extra_class  . '" source-id="' . $source['source__id'] . '" en-status="' . $source['source__status'] . '" read__id="'.$read__id.'" read-status="'.( $read__id ? $source['read__status'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';


    //SOURCE
    $ui .= '<td class="MENCHcolumn1">';

    $source_url = ( $is_read_progress ? '/'.$CI->uri->segment(1).'?focus__source='.$source['source__id'] : '/@'.$source['source__id'] );

    //SOURCE ICON
    $ui .= '<a href="'.$source_url.'" '.( $is_link_source ? ' title="READ ID '.$source['read__id'].' TYPE @'.$source['read__type'].' SORT '.$source['read__sort'].' WEIGHT '.$source['source__weight'].'" ' : '' ).'><span class="icon-block source_ui_icon_' . $source['source__id'] . ' source__icon_'.$source['source__id'].'" en-is-set="'.( strlen($source['source__icon']) > 0 ? 1 : 0 ).'">' . view_source__icon($source['source__icon']) . '</span></a>';


    //SOURCE TOOLBAR?
    if($show_toolbar){

        $ui .= view_input_text(6197, $source['source__title'], $source['source__id'], $is_source, 0, false, null, extract_icon_color($source['source__icon']));
        $ui .= $child_counter;
        $ui .= '<div class="space-content">'.$box_items_list.'</div>';

    } else {

        //SOURCE NAME
        $ui .= '<a href="'.$source_url.'" class="title-block title-no-right montserrat '.extract_icon_color($source['source__icon']).'">';
        $ui .= $box_items_list;
        $ui .= '<span class="text__6197_' . $source['source__id'] . '">'.$source['source__title'].'</span>';
        $ui .= $child_counter;
        $ui .= '</a>';

    }

    $ui .= '</td>';




    //IDEA
    $ui .= '<td class="MENCHcolumn3 source">';
    $ui .= view_coins_count_source(0, $source['source__id']);
    $ui .= '</td>';



    //READ
    $ui .= '<td class="MENCHcolumn2 read">';

    //RIGHT EDITING:
    $ui .= '<div class="pull-right inline-block">';
    $ui .= '<div class="note-editor edit-off">';
    $ui .= '<span class="show-on-hover">';

    if($control_enabled && $is_source){
        if($is_link_source){

            //Sort
            if(!$is_parent && $has_source_editor){
                $ui .= '<span title="SORT"><i class="fas fa-bars hidden black"></i></span>';
            }

            //Manage source link:
            $ui .= '<span class="'.superpower_active(10967).'"><a href="javascript:void(0);" onclick="source_modify_load(' . $source['source__id'] . ',' . $read__id . ')"><i class="fas fa-pen-square black"></i></a></span>';


        } elseif($is_source_only){

            //Allow to remove:
            $ui .= '<span><a href="javascript:void(0);" onclick="source_only_unlink(' . $read__id . ', '.$source['read__type'].')"><i class="fas fa-times black"></i></a></span>';

        }
    }

    $ui .= '</span>';
    $ui .= '</div>';
    $ui .= '</div>';

    $ui .= view_coins_count_read(0, $source['source__id']);
    $ui .= '</td>';





    $ui .= '</tr></table>';



    //PROFILE
    $ui .= '<div class="space-content hideIfEmpty">';
    //PROFILE SOURCES:
    $ui .= '<span class="'. superpower_active(12706) .' paddingup inline-block hideIfEmpty">';
    foreach($source__profiles as $source_profile) {
        $ui .= '<span class="icon-block-img source_child_icon_' . $source_profile['source__id'] . '"><a href="/@' . $source_profile['source__id'] . '" data-toggle="tooltip" title="' . $source_profile['source__title'] . (strlen($source_profile['read__message']) > 0 ? ' = ' . $source_profile['read__message'] : '') . '" data-placement="bottom">' . view_source__icon($source_profile['source__icon']) . '</a></span> ';
    }
    $ui .= '</span>';
    $ui .= '</div>';



    //MESSAGE
    if ($read__id > 0) {
        if($is_link_source){

            $ui .= '<span class="message_content paddingup read__message hideIfEmpty read__message_' . $read__id . '">' . view_read__message($source['read__message'] , $source['read__type']) . '</span>';

            //For JS editing only (HACK):
            $ui .= '<div class="read__message_val_' . $read__id . ' hidden overflowhide">' . $source['read__message'] . '</div>';

        } elseif($is_read_progress && strlen($source['read__message'])){

            //READ PROGRESS
            $ui .= '<div class="message_content paddingup">';
            $ui .= $CI->READ_model->send_message($source['read__message']);
            $ui .= '</div>';

        }
    }





    $ui .= '</div>';

    return $ui;

}


function view_input_text($cache_source__id, $current_value, $object__id, $is_source, $tabindex = 0, $extra_large = false, $source__icon = null, $append_css = null){

    $CI =& get_instance();
    $sources__12112 = $CI->config->item('sources__12112');
    $current_value = htmlentities($current_value);

    //Define element attributes:
    $attributes = ( $is_source ? '' : 'disabled' ).' tabindex="'.$tabindex.'" old-value="'.$current_value.'" class="form-control dotransparent montserrat inline-block view_input_text_update text__'.$cache_source__id.'_'.$object__id.' texttype_'.($extra_large?'_lg':'_sm').' text_source_'.$cache_source__id.' '.$append_css.'" cache_source__id="'.$cache_source__id.'" object__id="'.$object__id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $focus_element = '<textarea '.( !strlen($append_css) ? ' style="color:#000000 !important;" ' : '' ).' onkeyup="view_input_text_count('.$cache_source__id.','.$object__id.')" placeholder="'.$sources__12112[$cache_source__id]['m_name'].'" '.$attributes.'>'.$current_value.'</textarea>';
        $character_counter = '<div class="title_counter title_counter_'.$cache_source__id.'_'.$object__id.' hidden grey montserrat doupper" style="text-align: right;"><span id="current_count_'.$cache_source__id.'_'.$object__id.'">0</span>/'.config_var($cache_source__id).' CHARACTERS</div>';
        $icon = '<span class="icon-block title-icon">'.( $source__icon ? $source__icon : $sources__12112[4535]['m_icon'] ).'</span>';

    } else {

        $focus_element = '<input type="text" placeholder="__" value="'.$current_value.'" '.$attributes.' />';
        $character_counter = ''; //None
        if(in_array($cache_source__id, $CI->config->item('sources_id_12420'))){ //IDEA TEXT INPUT SHOW ICON
            $icon = '<span class="icon-block">'.( $source__icon ? $source__icon : $sources__12112[$cache_source__id]['m_icon'] ).'</span>';
        } else {
            $icon = $source__icon;
        }

    }

    return '<span class="span__'.$cache_source__id.' '.( !$is_source ? 'edit-locked' : '' ).'">'.$icon.$focus_element.'</span>'.$character_counter;
}




function view_input_dropdown($cache_source__id, $selected_source__id, $btn_class, $is_source = true, $show_full_name = true, $idea__id = 0, $read__id = 0){

    $CI =& get_instance();
    $sources__this = $CI->config->item('sources__'.$cache_source__id);

    if(!$selected_source__id || !isset($sources__this[$selected_source__id])){
        return false;
    }

    $sources__12079 = $CI->config->item('sources__12079');
    $sources__4527 = $CI->config->item('sources__4527');

    //data-toggle="tooltip" data-placement="top" title="'.$sources__4527[$cache_source__id]['m_name'].'"
    $ui = '<div title="'.$sources__12079[$cache_source__id]['m_name'].'" data-toggle="tooltip" data-placement="top" class="inline-block">';
    $ui .= '<div class="dropdown inline-block dropd_'.$cache_source__id.'_'.$idea__id.'_'.$read__id.' '.( !$show_full_name ? ' icon-block ' : '' ).'" selected-val="'.$selected_source__id.'">';

    $ui .= '<button type="button" '.( $is_source ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' '.$btn_class.'" id="dropdownMenuButton'.$cache_source__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked  '.$btn_class.'"' ).' >';

    $ui .= '<span class="icon-block">' .$sources__this[$selected_source__id]['m_icon'].'</span><span class="show-max">'.( $show_full_name ?  $sources__this[$selected_source__id]['m_name'] : '' ).'</span>';

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$cache_source__id.'">';

    foreach($sources__this as $source__id => $m) {

        $superpower_actives = array_intersect($CI->config->item('sources_id_10957'), $m['m_parents']);
        $is_url_desc = ( substr($m['m_desc'], 0, 1)=='/' );

        //What type of URL?
        if($is_url_desc){

            //Basic link:
            $anchor_url = ( $source__id==$selected_source__id ? 'href="javascript:void();"' : 'href="'.$m['m_desc'].'"' );

        } else{

            //Idea Dropdown updater:
            $anchor_url = 'href="javascript:void();" new-en-id="'.$source__id.'" onclick="idea_update_dropdown('.$cache_source__id.', '.$source__id.', '.$idea__id.', '.$read__id.', '.intval($show_full_name).')"';

        }

        $ui .= '<a class="dropdown-item dropi_'.$cache_source__id.'_'.$idea__id.'_'.$read__id.' montserrat optiond_'.$source__id.'_'.$idea__id.'_'.$read__id.' doupper '.( $source__id==$selected_source__id ? ' active ' : ( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ) ).'" '.$anchor_url.'><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</a>'; //Used to show desc but caused JS click conflict sp retired for now: ( strlen($m['m_desc']) && !$is_url_desc ? 'title="'.$m['m_desc'].'" data-toggle="tooltip" data-placement="right"' : '' )

    }

    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}

function view_json($array)
{
    header('Content-Type: application/json');
    echo json_encode($array);
    return true;
}


function view_ordinal($number)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if (($number % 100) >= 11 && ($number % 100) <= 13) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}

function view__s($count, $is_es = 0)
{
    //A cute little function to either display the plural "s" or not based on $count
    return ( intval($count) == 1 ? '' : ($is_es ? 'es' : 's'));
}

