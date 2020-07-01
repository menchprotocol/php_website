<?php

function view_e_load_more($page, $limit, $e__portfolio_count)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $ui = '<div class="load-more montserrat list-group-item itemsource no-left-padding"><a href="javascript:void(0);" onclick="e_load_page(' . $page . ', 0)">';

    //Regular section:
    $max_sources = (($page + 1) * $limit);
    $max_sources = ($max_sources > $e__portfolio_count ? $e__portfolio_count : $max_sources);
    $ui .= '<span class="icon-block"><i class="far fa-plus-circle source"></i></span><b class="montserrat source">SEE MORE</b>';
    $ui .= '</a></div>';

    return $ui;
}

function view_db_field($field_name){

    //Takes a database field name and returns a human-friendly version
    return ucwords(str_replace('__', ' ', $field_name));

}


function view_x__message($x__message, $x__type, $full_message = null)
{

    /*
     *
     * Displays Source Links @4592
     *
     * $full_message Would be the entire message
     * in an idea message that would be passed down
     * to the source profile $x__message value.
     *
     * */


    if ($x__type == 4256 /* Generic URL */) {

        return '<div class="block"><a href="' . $x__message . '" target="_blank"><span class="icon-block-xs inline-block"><i class="far fa-external-link"></i></span><span class="url_truncate">' . view_url_clean($x__message) . '</span></a></div>';

    } elseif ($x__type == 4257 /* Embed Widget URL? */) {

        return view_url_embed($x__message, $full_message);

    } elseif ($x__type == 4260 /* Image URL */) {

        $current_mench = current_mench();
        if($current_mench['x_name']=='source'){
            return '<a href="' . $x__message . '"><img data-src="' . $x__message . '" src="/img/mench.png" alt="IMAGE" class="content-image lazyimage" /></a>';
        } else {
            return '<img data-src="' . $x__message . '" src="/img/mench.png" alt="IMAGE" class="content-image lazyimage" />';
        }

    } elseif ($x__type == 4259 /* Audio URL */) {

        return  '<audio controls><source src="' . $x__message . '" type="audio/mpeg"></audio>' ;

    } elseif ($x__type == 4258 /* Video URL */) {

        return  '<video width="100%" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="' . $x__message . '" type="video/mp4"></video>' ;

    } elseif ($x__type == 4261 /* File URL */) {

        return '<a href="' . $x__message . '" class="btn btn-idea" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';

    } elseif(strlen($x__message) > 0) {

        return htmlentities($x__message);

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
     *       values for x__type as this could change the equation for those
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

                $string_references = extract_e_references($full_message);

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
                    $embed_html_code .= '<div class="discover-topic" style="padding-bottom: 0;"><span class="img-block icon-block-xs"><i class="fas fa-cut"></i></span>VIDEO CLIP FROM '.view_time_hours($start_time, true).' TO '.view_time_hours($end_time, true).'</div>';
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

function view_i_title($i, $common_prefix = null){
    if(strlen($common_prefix) > 0){
        $i['i__title'] = trim(substr($i['i__title'], strlen($common_prefix)));
    }
    return '<span class="text__4736_'.$i['i__id'].'">'.htmlentities(trim($i['i__title'])).'</span>';
}


function view_i_notes($discovery, $note_is_e_source = false)
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
    $e___4485 = $CI->config->item('e___4485'); //IDEA NOTES
    $e___6186 = $CI->config->item('e___6186'); //Interaction Status
    $note_is_e_source = ( $note_is_e_source || superpower_active(10984, true) );


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item itemidea is-msg note_sortable msg_e_type_' . $discovery['x__type'] . '" id="ul-nav-' . $discovery['x__id'] . '" x__id="' . $discovery['x__id'] . '" title="'.$discovery['e__title'].' Posted On '.substr($discovery['x__time'], 0, 19).'" data-toggle="tooltip" data-placement="top">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="text_message edit-off" id="msgbody_' . $discovery['x__id'] . '">';
    $ui .= $CI->X_model->message_send($discovery['x__message'], $session_source, $discovery['x__right']);
    $ui .= '</div>';

    //Editing menu:
    if($note_is_e_source){
        $ui .= '<div class="note-editor edit-off"><span class="show-on-hover">';

        //Sorting allowed?
        if(in_array($discovery['x__type'], $CI->config->item('n___4603'))){
            $ui .= '<span title="SORT"><i class="fas fa-bars i_note_sorting"></i></span>';
        }

        //Modify:
        $ui .= '<span title="MODIFY"><a href="javascript:i_note_edit_start(' . $discovery['x__id'] . ');"><i class="fas fa-pen-square"></i></a></span>';

        $ui .= '</span></div>';


        //Text editing:
        $ui .= '<textarea onkeyup="i_note_edit_count(' . $discovery['x__id'] . ')" name="x__message" id="message_body_' . $discovery['x__id'] . '" class="edit-on hidden msg note-textarea algolia_search" placeholder="'.stripslashes($discovery['x__message']).'">' . $discovery['x__message'] . '</textarea>';


        //Editing menu:
        $ui .= '<ul class="msg-nav">';

        //Counter:
        $ui .= '<li class="edit-on hidden"><span id="ideaNoteCount' . $discovery['x__id'] . '"><span id="charEditingNum' . $discovery['x__id'] . '">0</span>/' . config_var(4485) . '</span></li>';

        //Save Edit:
        $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-idea white-third" href="javascript:i_note_edit(' . $discovery['x__id'] . ',' . $discovery['x__type'] . ');"><i class="fas fa-check"></i> Save</a></li>';

        //Cancel Edit:
        $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-idea white-third" href="javascript:i_note_edit_cancel(' . $discovery['x__id'] . ');"><i class="fas fa-times"></i></a></li>';

        //Show drop down for message link status:
        $ui .= '<li class="pull-right edit-on hidden"><span class="white-wrapper" style="margin:-5px 5px 0 0; display: block;">';
        $ui .= '<select id="message_status_' . $discovery['x__id'] . '"  class="form-control border" style="margin-bottom:0;">';
        foreach($CI->config->item('e___12012') as $e__id => $m){
            $ui .= '<option value="' . $e__id . '" '.( $e__id==$discovery['x__status'] ? 'selected="selected"' : '' ).'>' . $m['m_name'] . '</option>';
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


function view_e__icon($e__icon = null)
{
    //A simple function to display the Player Icon OR the default icon if not available:
    if (strlen($e__icon) > 0) {

        return $e__icon;

    } else {
        //Return default icon for sources:
        $CI =& get_instance();
        $e___2738 = $CI->config->item('e___2738'); //MENCH
        return $e___2738[4536]['m_icon'];
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


function view_interaction($discovery, $is_parent_tr = false)
{

    $CI =& get_instance();
    $e___4593 = $CI->config->item('e___4593'); //Link Type
    $e___4341 = $CI->config->item('e___4341'); //Link Table
    $e___2738 = $CI->config->item('e___2738');
    $e___6186 = $CI->config->item('e___6186'); //Interaction Status
    $session_source = superpower_assigned();



    if(!isset($e___4593[$discovery['x__type']])){
        //We've probably have not yet updated php cache, set error:
        $e___4593[$discovery['x__type']] = array(
            'm_icon' => '<i class="fas fa-exclamation-circle"></i>',
            'm_name' => 'Link Type Not Synced in PHP Cache',
            'm_desc' => '',
            'm_parents' => array(),
        );
    }





    //Display the item
    $ui = '<div class="discover-list">';


    //Interaction ID
    $ui .= '<div class="simple-line"><a href="/x?x__id='.$discovery['x__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4367]['m_name'].'" class="montserrat"><span class="icon-block">'.$e___4341[4367]['m_icon']. '</span>'.$discovery['x__id'].'</a></div>';


    //Status
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$e___4341[6186]['m_name'].( strlen($e___6186[$discovery['x__status']]['m_desc']) ? ': '.$e___6186[$discovery['x__status']]['m_desc'] : '' ).'"><span class="icon-block">'.$e___6186[$discovery['x__status']]['m_icon'].'</span>'.$e___6186[$discovery['x__status']]['m_name'].'</span></div>';

    //Time
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $e___4341[4362]['m_name'].': '.$discovery['x__time'] . ' PST"><span class="icon-block">'.$e___4341[4362]['m_icon']. '</span>' . view_time_difference(strtotime($discovery['x__time'])) . ' ago</span></div>';



    //COINS AWARDED?
    if(in_array($discovery['x__type'], $CI->config->item('n___6255'))){
        $coins_type = 'discover';
    } elseif(in_array($discovery['x__type'], $CI->config->item('n___12274'))){
        $coins_type = 'source';
    } elseif(in_array($discovery['x__type'], $CI->config->item('n___12273')) && ($discovery['x__up']>0 || $discovery['x__down']>0)){
        $coins_type = 'idea';
    } else {
        $coins_type = null;
    }

    //Interaction Type & Coins
    $ui .= '<div class="simple-line"><a href="/@'.$discovery['x__type'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4593]['m_name'].( strlen($e___4593[$discovery['x__type']]['m_desc']) ? ': '.$e___4593[$discovery['x__type']]['m_desc'] : '' ).'" class="montserrat"><span class="icon-block">'.$e___4341[4593]['m_icon']. '</span><span class="'.extract_icon_color($e___4593[$discovery['x__type']]['m_icon']).'">'. $e___4593[$discovery['x__type']]['m_icon'] . '&nbsp;' . $e___4593[$discovery['x__type']]['m_name'] . '</span>'.($coins_type ? '&nbsp;<span title="'.$coins_type.' coin awarded" data-toggle="tooltip" data-placement="top"><i class="fas fa-circle '.$coins_type.'"></i></span>' : '').'</a></div>';


    //Hide Sensitive Details?
    if(in_array($discovery['x__type'] , $CI->config->item('n___4755')) && (!$session_source || $discovery['x__member']!=$session_source['e__id']) && !superpower_active(12701, true)){

        //Hide Information:
        $ui .= '<div class="simple-line"><span data-toggle="tooltip" class="montserrat" data-placement="top" title="Details are kept private"><span class="icon-block"><i class="fal fa-eye-slash"></i></span>PRIVATE INFORMATION</span></div>';

    } else {

        //Metadata
        if(strlen($discovery['x__metadata']) > 0){
            $ui .= '<div class="simple-line"><a href="/e/plugin/12722?x__id=' . $discovery['x__id'] . '" class="montserrat"><span class="icon-block">'.$e___4341[6103]['m_icon']. '</span>'.$e___4341[6103]['m_name']. '</a></div>';
        }

        //Order
        if($discovery['x__sort'] > 0){
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$e___4341[4370]['m_name']. '"><span class="icon-block">'.$e___4341[4370]['m_icon']. '</span>'.view_ordinal($discovery['x__sort']).'</span></div>';
        }


        //Message
        if(strlen($discovery['x__message']) > 0 && $discovery['x__message']!='@'.$discovery['x__up']){
            $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4372]['m_name'].'"><span class="icon-block">'.$e___4341[4372]['m_icon'].'</span><div class="title-block discover-msg">'.htmlentities($discovery['x__message']).'</div></div>';
        }


        //Creator (Do not repeat)
        if($discovery['x__member'] > 0 && $discovery['x__member']!=$discovery['x__up'] && $discovery['x__member']!=$discovery['x__down']){

            $add_sources = $CI->E_model->fetch(array(
                'e__id' => $discovery['x__member'],
            ));

            $ui .= '<div class="simple-line"><a href="/@'.$add_sources[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4364]['m_name'].'" class="montserrat"><span class="icon-block">'.$e___4341[4364]['m_icon']. '</span><span class="'.extract_icon_color($add_sources[0]['e__icon']).'"><span class="img-block">'.view_e__icon($add_sources[0]['e__icon']) . '</span> ' . $add_sources[0]['e__title'] . '</span></a></div>';

        }

    }


    //5x Relations:
    if(!$is_parent_tr){

        $var_index = var_index();
        foreach($CI->config->item('e___10692') as $e__id => $m) {

            //Do we have this set?
            if(!array_key_exists($e__id, $var_index) || !intval($discovery[$var_index[$e__id]])){
                continue;
            }

            if(in_array(6160 , $m['m_parents'])){

                //SOURCE
                $sources = $CI->E_model->fetch(array('e__id' => $discovery[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/@'.$sources[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m_name'].'" class="montserrat"><span class="icon-block">'.$e___4341[$e__id]['m_icon']. '</span>'.( $discovery[$var_index[$e__id]]==$discovery['x__member'] ? $e___4341[4364]['m_icon']. '&nbsp;' : '' ).'<span class="'.extract_icon_color($sources[0]['e__icon']).' img-block">'.view_e__icon($sources[0]['e__icon']). '&nbsp;'.$sources[0]['e__title'].'</span></a></div>';

            } elseif(in_array(6202 , $m['m_parents'])){

                //IDEA
                $is = $CI->I_model->fetch(array('i__id' => $discovery[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/i/i_go/'.$is[0]['i__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m_name'].'" class="montserrat"><span class="icon-block">'.$e___4341[$e__id]['m_icon']. '</span>'.$e___2738[4535]['m_icon']. '&nbsp;'.view_i_title($is[0]).'</a></div>';

            } elseif(in_array(4367 , $m['m_parents'])){

                //PARENT DISCOVER
                $discoveries = $CI->X_model->fetch(array('x__id' => $discovery[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m_name'].'">'.$e___4341[$e__id]['m_icon']. '</span><div class="discover-ref">'.view_interaction($discoveries[0], true).'</div></div>';

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


function view_cache($config_var_name, $e__id, $micro_status = true, $data_placement = 'top')
{

    /*
     *
     * UI for Platform Cache sources
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item($config_var_name);
    $cache_source = $config_array[$e__id];
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
        return '<span ' . ( $micro_status && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="' . $data_placement . '" title="' . ($micro_status ? $cache_source['m_name'] : '') . (strlen($cache_source['m_desc']) > 0 ? ($micro_status ? ': ' : '') . $cache_source['m_desc'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache_source['m_icon'] . ' ' . ($micro_status ? '' : $cache_source['m_name']) . '</span>';
    }
}



function view_coins_count_discover($i__id = 0, $e__id = 0){

    $CI =& get_instance();
    $query_filters = array(
        'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null,
        ( $i__id > 0 ? 'x__left' : 'x__member' ) => ( $i__id > 0 ? $i__id : $e__id ),
    );

    if(isset($_GET['focus__source'])){
        $query_filters['x__member'] = intval($_GET['focus__source']);
    }

    $x_coins = $CI->X_model->fetch($query_filters, array(), 1, 0, array(), 'COUNT(x__id) as totals');

    if($x_coins[0]['totals'] > 0){
        return '<span class="montserrat discover"><span class="icon-block"><i class="fas fa-circle"></i></span>'.view_number($x_coins[0]['totals']).'</span>';
    } else {
        return false;
    }

}


function view_coins_count_source($i__id = 0, $e__id = 0, $number_only = false){

    $CI =& get_instance();

    if($i__id){
        $mench = 'source';
        $coins_filter = array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___12273')) . ')' => null, //IDEA COIN
            'x__right' => $i__id,
            '(x__up > 0 OR x__down > 0)' => null, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
        );
    } elseif($e__id){
        $mench = 'idea';
        $coins_filter = array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___12273')) . ')' => null, //IDEA COIN
            '(x__up = '.$e__id.' OR x__down = '.$e__id.')' => null,
        );
    }

    $e_coins = $CI->X_model->fetch($coins_filter, array(), 0, 0, array(), 'COUNT(x__id) as totals');

    if($number_only){
        return $e_coins[0]['totals'];
    } else {
        return ($e_coins[0]['totals'] > 0 ? '<span class="montserrat '.$mench.'"><span class="icon-block"><i class="fas fa-circle"></i></span>'.view_number($e_coins[0]['totals']).'</span>' : null);
    }
}

function view_x_icon_legend($can_click, $completion_percentage){

    $CI =& get_instance();
    $e___12446 = $CI->config->item('e___12446'); //DISCOVER ICON LEGEND

    if(!$can_click || $completion_percentage==0){
        //DISCOVER NOT STARTED
        $discovery_legend = 12448;
    } elseif($completion_percentage<100){
        //DISCOVER IN PROGRESS
        $discovery_legend = 12447;
    } else {
        //DISCOVER COMPLETED
        $discovery_legend = 13338;
    }

    return '<span title="'.$e___12446[$discovery_legend]['m_name'].'">'.$e___12446[$discovery_legend]['m_icon'].'</span>';

}

function view_i_discover($i, $common_prefix = null, $show_editor = false, $completion_rate = null, $recipient_source = false, $extra_class = null)
{

    //See if user is logged-in:
    $CI =& get_instance();
    if(!$recipient_source){
        $recipient_source = superpower_assigned();
    }


    if(!$completion_rate){
        if($recipient_source){
            $completion_rate = $CI->X_model->completion_progress($recipient_source['e__id'], $i);
        } else {
            $completion_rate['completion_percentage'] = 0;
        }
    }

    $i_stats = i_stats($i['i__metadata']);
    $is_saved = ( isset($i['x__type']) && $i['x__type']==12896 );
    $can_click = ( $completion_rate['completion_percentage']>0 || $is_saved || superpower_active(13404, true) );
    $first_segment = $CI->uri->segment(1);
    $has_completion = $can_click && $completion_rate['completion_percentage']>0 && $completion_rate['completion_percentage']<100;

    //Build View:
    $ui  = '<div id="ap_i_'.$i['i__id'].'" '.( isset($i['x__id']) ? ' sort-x-id="'.$i['x__id'].'" ' : '' ).' class="list-group-item no-side-padding '.( $show_editor ? 'home_sort' : '' ).( $can_click ? ' itemdiscover ' : '' ).' '.$extra_class.'">';

    $ui .= ( $can_click ? '<a href="/'. $i['i__id'] .'" class="itemdiscover">' : '' );


    //Right Stats:
    if($i_stats['duration_average'] || $i_stats['i_average']){
        $ui .= '<div class="pull-right montserrat" style="'.( $show_editor ? 'width:155px;' : 'width:138px;' ).' '.( $has_completion ? ' padding-top:4px;' : '' ).'"><span style="width:53px; display: inline-block;">'.( $i_stats['i_average'] ? '<i class="fas fa-circle idea"></i><span style="padding-left:3px;" class="idea">'.$i_stats['i_average'].'</span>' : '' ).'</span>'.( $i_stats['duration_average'] ? '<span class="mono-space">'.view_time_hours($i_stats['duration_average']).'</span>': '' ).'</div>';
    }


    if($has_completion){
        $ui .= '<div class="progress-bg-list" title="discover '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
    }

    $ui .= '<span class="icon-block">'.view_x_icon_legend($can_click, $completion_rate['completion_percentage']).'</span>';

    $ui .= '<b class="'.( $can_click ? 'montserrat' : '' ).' idea-url title-block">'.view_i_title($i, $common_prefix).'</b>';


    //Search for Idea Image:
    if($show_editor){
        if($is_saved){

            $ui .= '<div class="note-editor edit-off">';
            $ui .= '<span class="show-on-hover">';
            $ui .= '<span><a href="javascript:void(0);" title="Unsave" data-toggle="tooltip" data-placement="left" onclick="i_save('.$i['i__id'].');$(\'#ap_i_'.$i['i__id'].'\').remove();"><i class="fas fa-times" style="margin-top: 10px;"></i></a></span>';
            $ui .= '</span>';
            $ui .= '</div>';

        }
    }

    $ui .= ( $can_click ? '</a>' : '' );
    $ui .= '</div>';

    return $ui;
}


function view_i_scores_answer($i__id, $depth_levels, $original_depth_levels, $previous_i__type){

    if($depth_levels<=0){
        //End recursion:
        return false;
    }

    //We're going 1 level deep:
    $depth_levels--;

    //Go down recursively:
    $CI =& get_instance();
    $e___6186 = $CI->config->item('e___6186'); //Interaction Status
    $e___4486 = $CI->config->item('e___4486');
    $e___4737 = $CI->config->item('e___4737'); // Idea Status
    $e___7585 = $CI->config->item('e___7585'); // Idea Subtypes


    $ui = null;
    foreach($CI->X_model->fetch(array(
        'x__left' => $i__id,
        'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'i__status IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $i_discover){

        //Prep Metadata:
        $metadata = unserialize($i_discover['x__metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->X_model->fetch(array(
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $i_discover['i__id'],
        ), array(), 0, 0, array('x__sort' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Link Type: '.$e___4486[$i_discover['x__type']]['m_name'].'">'. $e___4486[$i_discover['x__type']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Interaction Status: '.$e___6186[$i_discover['x__status']]['m_name'].'">'. $e___6186[$i_discover['x__status']]['m_icon'] . '</span>';

        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Type: '.$e___7585[$i_discover['i__type']]['m_name'].'">'. $e___7585[$i_discover['i__type']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Status: '.$e___4737[$i_discover['i__status']]['m_name'].'">'. $e___4737[$i_discover['i__status']]['m_icon']. '</span>';
        $ui .= '<a href="?i__id='.$i_discover['i__id'].'&depth_levels='.$original_depth_levels.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this idea"><u>' .   view_i_title($i_discover) . '</u></a>';

        $ui .= ' [<span data-toggle="tooltip" data-placement="top" title="Completion Marks">'.( ($i_discover['x__type'] == 4228 && in_array($previous_i__type , $CI->config->item('n___6193') /* OR Ideas */ )) || ($i_discover['x__type'] == 4229) ? view_i_marks($i_discover) : '' ).'</span>]';

        if(count($messages) > 0){
            $ui .= ' <a href="javascript:void(0);" onclick="$(\'.messages-'.$i_discover['i__id'].'\').toggleClass(\'hidden\');"><i class="fas fa-comment"></i><b>' .  count($messages) . '</b></a>';
        }
        $ui .= '</div>';

        //Display Messages:
        $ui .= '<div class="messages-'.$i_discover['i__id'].' hidden">';
        foreach($messages as $msg) {
            $ui .= '<div class="tip_bubble">';
            $ui .= $CI->X_model->message_send($msg['x__message']);
            $ui .= '</div>';
        }
        $ui .= '</div>';

        //Go Recursively down:
        $ui .=  view_i_scores_answer($i_discover['i__id'], $depth_levels, $original_depth_levels, $i_discover['i__type']);

    }

    //Return the wrapped UI if existed:
    return ($ui ? '<div class="inline-box">' . $ui . '</div>' : false);
}

function view_radio_sources($parent_e__id, $child_e__id, $enable_mulitiselect, $show_max = 25){

    /*
     * Print UI for
     * */

    $CI =& get_instance();
    $count = 0;

    $ui = '<div class="list-group list-radio-select radio-'.$parent_e__id.'">';

    if(!count($CI->config->item('n___'.$parent_e__id))){
        return false;
    }

    foreach($CI->config->item('e___'.$parent_e__id) as $e__id => $m) {
        $ui .= '<a href="javascript:void(0);" onclick="e_update_radio('.$parent_e__id.','.$e__id.','.$enable_mulitiselect.')" class="item'.extract_icon_color($m['m_icon']).' list-group-item montserrat itemsetting item-'.$e__id.' '.( $count>=$show_max ? 'extra-items-'.$parent_e__id.' hidden ' : '' ).( count($CI->X_model->fetch(array(
                'x__up' => $e__id,
                'x__down' => $child_e__id,
                'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            )))>0 ? ' active ' : '' ). '"><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'<span class="change-results"></span></a>';
        $count++;
    }


    //Did we have too many items?
    if($count>=$show_max){
        //Show "Show more" button
        $ui .= '<a href="javascript:void(0);" class="list-group-item itemsource itemsetting montserrat extra-items-'.$parent_e__id.'" onclick="$(\'.extra-items-'.$parent_e__id.'\').toggleClass(\'hidden\')"><span class="icon-block"><i class="fas fa-plus-circle"></i></span>Show '.($count-$show_max).' more</a>';
    }

    $ui .= '</div>';

    return $ui;
}


function view_i_marks($i_discover){

    //Validate core inputs:
    if(!isset($i_discover['x__metadata']) || !isset($i_discover['x__type'])){
        return false;
    }

    //prep metadata:
    $x__metadata = unserialize($i_discover['x__metadata']);

    //Return mark:
    return ( $i_discover['x__type'] == 4228 ? ( !isset($x__metadata['tr__assessment_points']) || $x__metadata['tr__assessment_points'] == 0 ? '' : '<span class="score-range">[<span style="'.( $x__metadata['tr__assessment_points']>0 ? 'font-weight:bold;' : ( $x__metadata['tr__assessment_points'] < 0 ? 'font-weight:bold;' : '' )).'">' . ( $x__metadata['tr__assessment_points'] > 0 ? '+' : '' ) . $x__metadata['tr__assessment_points'].'</span>]</span>' ) : '<span class="score-range">['.$x__metadata['tr__conditional_score_min'] . ( $x__metadata['tr__conditional_score_min']==$x__metadata['tr__conditional_score_max'] ? '' : '-'.$x__metadata['tr__conditional_score_max'] ).'%]</span>' );

}


function view_i($i, $i_linked_id = 0, $is_parent = false, $player_is_i_source = false, $message_input = null, $extra_class = null, $control_enabled = true)
{

    $CI =& get_instance();
    $session_source = superpower_assigned();
    $e___6186 = $CI->config->item('e___6186');
    $e___4737 = $CI->config->item('e___4737'); //IDEA STATUS
    $e___7585 = $CI->config->item('e___7585');
    $e___4486 = $CI->config->item('e___4486');
    $e___2738 = $CI->config->item('e___2738');
    $e___12413 = $CI->config->item('e___12413');
    $e___13408 = $CI->config->item('e___13408');

    //DISCOVER
    $x__id = ( isset($i['x__id']) ? $i['x__id'] : 0 );
    $is_i_link = ($x__id && in_array($i['x__type'], $CI->config->item('n___4486')));

    //IDEA
    $i_stats = i_stats($i['i__metadata']);
    $is_public = in_array($i['i__status'], $CI->config->item('n___7355'));
    $player_is_i_source = ( !$is_i_link ? false : $player_is_i_source ); //Disable Edits on Idea List Page
    $show_toolbar = ($control_enabled && superpower_active(12673, true));




    //IDAE INFO BAR
    $box_items_list = '';

    //DISCOVER STATUS
    if($x__id && !in_array($i['x__status'], $CI->config->item('n___7359'))){
        $box_items_list .= '<span class="inline-block"><span data-toggle="tooltip" data-placement="right" title="'.$e___6186[$i['x__status']]['m_name'].' @'.$i['x__status'].'">' . $e___6186[$i['x__status']]['m_icon'] . '</span>&nbsp;</span>';
    }


    $ui = '<div x__id="' . $x__id . '" idea-id="' . $i['i__id'] . '" class="list-group-item no-side-padding itemidea itemidealist i_sortable paddingup level2_in object_saved saved_i_'.$i['i__id'] . ' i_line_' . $i['i__id'] . ( $is_parent ? ' parent-idea ' : '' ) . ' i__tr_'.$x__id.' '.$extra_class.'" style="padding-left:0;">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="MENCHcolumn1">';
        $ui .= '<div class="block">';

            //IDAE Link:
            $i_link = '/i/i_go/'.$i['i__id'].( isset($_GET['focus__source']) ? '?focus__source='.intval($_GET['focus__source']) : '' );

            //IDEA ICON:
            $ui .= '<span class="icon-block"><a href="'.$i_link.'" title="Idea Weight: '.number_format($i['i__weight'], 0).'">'.$e___2738[4535]['m_icon'].'</a></span>';

            //IDEA TITLE
            if($is_i_link && superpower_active(13354, true)){

                $ui .= view_input_text(4736, $i['i__title'], $i['i__id'], $player_is_i_source, (($i['x__sort']*100)+1));

            } else {

                $ui .= '<a href="'.$i_link.'" class="title-block montserrat">';
                $ui .= $box_items_list;

                //IDEA STATUS
                if(!$is_public && $is_i_link){
                    //Show the drafting status:
                    $ui .= '<span class="inline-block">'.view_cache('e___4737' /* Idea Status */, $i['i__status'], true, 'right').'&nbsp;</span>';
                }

                $ui .= view_i_title($i); //IDEA TITLE
                $ui .= '</a>';

            }

        $ui .= '</div>';
    $ui .= '</td>';


    //DISCOVER
    $ui .= '<td class="MENCHcolumn2 source">';
    $ui .= view_coins_count_discover($i['i__id']);
    $ui .= '</td>';


    //SOURCE
    $ui .= '<td class="MENCHcolumn3 source">';
    if($is_i_link && $control_enabled && $player_is_i_source){

        //RIGHT EDITING:
        $ui .= '<div class="pull-right inline-block '.superpower_active(10939).'">';
        $ui .= '<div class="note-editor edit-off">';
        $ui .= '<span class="show-on-hover">';

        if(!$is_parent){
            $ui .= '<span title="SORT"><i class="fas fa-bars black idea-sort-handle"></i></span>';
        }

        //Unlink:
        $ui .= '<span title="UNLINK"><a href="javascript:void(0);" onclick="i_unlink('.$i['i__id'].', '.$i['x__id'].', '.( $is_parent ? 1 : 0 ).')"><i class="fas fa-times black"></i></a></span>';

        $ui .= '</span>';
        $ui .= '</div>';
        $ui .= '</div>';

    }

    //SOURCE STATS
    $ui .= view_coins_count_source($i['i__id']);
    $ui .= '</td>';



    $ui .= '</tr></table>';



    if($message_input && trim($message_input)!=$CI->uri->segment(1)){
        $ui .= '<div class="idea-footer hideIfEmpty">' . $CI->X_model->message_send($message_input, $session_source) . '</div>';
    }


    if($show_toolbar){

        //Idea Toolbar
        $ui .= '<div class="space-content ' . superpower_active(12673) . '" style="padding-left:25px; padding-top:13px;">';

        $ui .= $box_items_list;

        //IDEA TYPE
        $ui .= '<div class="inline-block">'.view_input_dropdown(7585, $i['i__type'], null, $player_is_i_source, false, $i['i__id']).'</div>';

        //IDEA STATUS
        $ui .= '<div class="inline-block">' . view_input_dropdown(4737, $i['i__status'], null, $player_is_i_source, false, $i['i__id']) . ' </div>';




        if($x__id){

            $x__metadata = unserialize($i['x__metadata']);

            //IDEA LINK BAR
            $ui .= '<span class="' . superpower_active(12700) . '">';

            //LINK TYPE
            $ui .= view_input_dropdown(4486, $i['x__type'], null, $player_is_i_source, false, $i['i__id'], $i['x__id']);

            //LINK MARKS
            $ui .= '<span class="link_marks settings_4228 '.( $i['x__type']==4228 ? : 'hidden' ).'">';
            $ui .= view_input_text(4358, ( isset($x__metadata['tr__assessment_points']) ? $x__metadata['tr__assessment_points'] : '' ), $i['x__id'], $player_is_i_source, ($i['x__sort']*10)+2 );
            $ui .='</span>';


            //LINK CONDITIONAL RANGE
            $ui .= '<span class="link_marks settings_4229 '.( $i['x__type']==4229 ? : 'hidden' ).'">';
            //MIN
            $ui .= view_input_text(4735, ( isset($x__metadata['tr__conditional_score_min']) ? $x__metadata['tr__conditional_score_min'] : '' ), $i['x__id'], $player_is_i_source, ($i['x__sort']*10)+3);
            //MAX
            $ui .= view_input_text(4739, ( isset($x__metadata['tr__conditional_score_max']) ? $x__metadata['tr__conditional_score_max'] : '' ), $i['x__id'], $player_is_i_source, ($i['x__sort']*10)+4);
            $ui .= '</span>';
            $ui .= '</span>';

        }




        //IDEA TREE:
        $previous_ideas = $CI->X_model->fetch(array(
            'x__right' => $i['i__id'],
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array(), 'COUNT(x__id) as total_ideas');

        $next_ideas = $CI->X_model->fetch(array(
            'x__left' => $i['i__id'],
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array(), 'COUNT(x__id) as total_ideas');

        $ui .= '<span class="inline-block montserrat idea" title="'.$e___12413[11019]['m_name'].'" style="width:21px; text-align:right;">'.( $previous_ideas[0]['total_ideas']>0 ? $previous_ideas[0]['total_ideas'] : '&nbsp;' ).'</span>';
        $ui .= '<span class="icon-block">'.$e___13408[12413]['m_icon'].'</span>';
        $ui .= '<span class="inline-block montserrat idea" title="'.$e___12413[11020]['m_name'].'" style="text-align:left;">'.($next_ideas[0]['total_ideas']>0 ? $next_ideas[0]['total_ideas'] : '' ).( $i_stats['i_max']>$next_ideas[0]['total_ideas'] ? '<span style="padding: 0 2px;">-</span>'.$i_stats['i_max'] : '' ).'</span>';



        $ui .= '</div>';


    }

    $ui .= '</div>';



    return $ui;

}




function view_caret($e__id, $m, $object__id){
    //Display drop down menu:
    $CI =& get_instance();

    $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m_parents']);

    $ui = '<li class="nav-item dropdown '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'" title="'.$m['m_name'].'" data-toggle="tooltip" data-placement="top">';
    $ui .= '<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>';
    $ui .= '<div class="dropdown-menu">';
    foreach($CI->config->item('e___'.$e__id) as $e__id2 => $m2){
        $ui .= '<a class="dropdown-item montserrat '.extract_icon_color($m2['m_icon']).'" href="' . $m2['m_desc'] . $object__id . '"><span class="icon-block">'.view_e__icon($m2['m_icon']).'</span> '.$m2['m_name'].'</a>';
    }
    $ui .= '</div>';
    $ui .= '</li>';

    return $ui;
}


function view_i_list($i, $is_next, $recipient_source, $prefix_statement = null, $show_next = true){

    //If no list just return the next step:
    if(!count($is_next)){
        return ( $show_next ? view_next_i_previous($i['i__id'], $recipient_source) : false );
    }

    $CI =& get_instance();

    if(count($is_next)){

        //List children so they know what's ahead:
        $common_prefix = i_calc_common_prefix($is_next, 'i__title');

        echo '<div class="discover-topic">'.( strlen($prefix_statement) ? '<span class="icon-block">&nbsp;</span>'.$prefix_statement : '<span class="icon-block">&nbsp;</span>UP NEXT:'.( $common_prefix ? ' '.$common_prefix : '' ) ).'</div>';

        echo '<div class="list-group">';
        foreach($is_next as $key => $next_idea){
            echo view_i_discover($next_idea, $common_prefix);
        }
        echo '</div>';

    }

    if($show_next){
        view_next_i_previous($i['i__id'], $recipient_source);
        echo '<div class="doclear">&nbsp;</div>';
    }
}

function view_next_i_previous($i__id, $recipient_source){

    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION

    //PREVIOUS:
    echo view_i_previous_discover($i__id, $recipient_source);

    //NEXT:
    echo '<div class="inline-block margin-top-down pull-right"><a class="btn btn-discover btn-circle" href="/x/x_next/'.$i__id.'">'.$e___11035[12211]['m_icon'].'</a></div>';

}

function view_i_previous_discover($i__id, $recipient_source){

    if(!$recipient_source || $recipient_source['e__id'] < 1){
        return null;
    }

    //Discoveries
    $CI =& get_instance();
    $ui = null;
    $i_level_up = 0;
    $previous_level_id = 0; //The ID of the Idea one level up
    $player_discovery_ids = $CI->X_model->ids($recipient_source['e__id']);
    $discovery_list_ui = null;
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $e___12994 = $CI->config->item('e___12994'); //DISCOVER LAYOUT

    if(in_array($i__id, $player_discovery_ids)){

        //A discovering list item:
        $is_this = $CI->I_model->fetch(array(
            'i__id' => $i__id,
        ));

    } else {

        //Find it:
        $recursive_parents = $CI->I_model->recursive_parents($i__id, true, true);
        $sitemap_items = array();

        foreach($recursive_parents as $grand_parent_ids) {
            foreach(array_intersect($grand_parent_ids, $player_discovery_ids) as $intersect) {
                foreach($grand_parent_ids as $previous_i__id) {

                    if($i_level_up==0){
                        //Remember the first parent for the back button:
                        $previous_level_id = $previous_i__id;
                    }

                    $is_this = $CI->I_model->fetch(array(
                        'i__id' => $previous_i__id,
                    ));

                    $i_level_up++;

                    if ($previous_i__id == $intersect) {
                        //array_push($sitemap_items, '<div class="list-group-item no-side-padding itemdiscover full_sitemap"><a href="javascript:void(0);" onclick="$(\'.full_sitemap\').toggleClass(\'hidden\');"><span class="icon-block">'.$e___12994[13400]['m_icon'].'</span><span class="montserrat">'.$e___12994[13400]['m_name'].'</span></a></div><div class="list-group-item hidden">&nbsp;</div>');
                    }

                    //array_push($sitemap_items, view_i_discover($is_this[0], null, false, null, false, ( $previous_i__id!=$intersect ? ' full_sitemap hidden ' : '' )));

                    if ($previous_i__id == $intersect) {
                        array_push($sitemap_items, view_i_discover($is_this[0], null, false, null, false, ( $previous_i__id!=$intersect ? ' full_sitemap hidden ' : '' )));
                        break;
                    }
                }
            }
        }

        $discovery_list_ui .= '<div class="list-group">' . join('', array_reverse($sitemap_items)) . '</div>';

    }


    //Did We Find It?
    if($previous_level_id > 0){

        //Previous
        if(isset($_GET['previous_discover']) && $_GET['previous_discover']>0){
            $ui .= '<div class="inline-block margin-top-down edit_select_answer pull-left"><a class="btn btn-discover btn-circle" href="/'.$_GET['previous_discover'].'" title="'.$e___11035[12991]['m_name'].'">'.$e___11035[12991]['m_icon'].'</a></div>';
        } else {
            $ui .= '<div class="inline-block margin-top-down edit_select_answer pull-left"><a class="btn btn-discover btn-circle" href="/x/x_previous/'.$previous_level_id.'/'.$i__id.'" title="'.$e___11035[12991]['m_name'].'">'.$e___11035[12991]['m_icon'].'</a></div>';
        }


        //Is Saved?
        $is_saveded = count($CI->X_model->fetch(array(
            'x__up' => $recipient_source['e__id'],
            'x__right' => $i__id,
            'x__type' => 12896, //SAVED
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        )));

        $ui .= '<div class="inline-block margin-top-down pull-left edit_select_answer"><a class="btn btn-discover btn-circle" href="javascript:void(0);" onclick="i_save('.$i__id.')"><i class="fas fa-bookmark toggle_saved '.( $is_saveded ? '' : 'hidden' ).'"></i><i class="fal fa-bookmark toggle_saved '.( $is_saveded ? 'hidden' : '' ).'"></i></a></div>';

        //Main Discoveries:
        if($discovery_list_ui){

            $ui .= '<div class="focus_discoveries_bottom hidden">';
            $ui .= '<div class="list-group">';
            $ui .= $discovery_list_ui;
            $ui .= '</div>';
            $ui .= '</div>';

        }

    }

    return $ui;

}


function view_i_note_mix($x__type, $i_notes){

    $CI =& get_instance();
    $e___4485 = $CI->config->item('e___4485'); //IDEA NOTES
    $handles_uploads = (in_array($x__type, $CI->config->item('n___12359')));
    $handles_url = (in_array($x__type, $CI->config->item('n___7551')) || in_array($x__type, $CI->config->item('n___4986')));
    $session_source = superpower_assigned();
    $ui = '';


    if(!count($i_notes)){
        $ui .= '<div class="no_notes_' . $x__type .'" style="margin-bottom:13px;">';
        $ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block">&nbsp;</span>No '.ucwords(strtolower($e___4485[$x__type]['m_name'])).'. Be the first to post one</div>';
        $ui .= '</div>';
    }


    //Show no-Message notifications for each message type:
    $ui .= '<div id="i_notes_list_'.$x__type.'" class="list-group">';

    //List current notes:
    foreach($i_notes as $i_notes) {
        $ui .= view_i_notes($i_notes, ($i_notes['x__member']==$session_source['e__id']));
    }

    //ADD NEW:
    if(!in_array($x__type, $CI->config->item('n___12677'))){
        $ui .= '<div class="list-group-item itemidea space-left add_notes_' . $x__type .'">';
        $ui .= '<div class="add_notes_form">';
        $ui .= '<form class="box box' . $x__type . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">';      //Used for dropping files



        $ui .= '<textarea onkeyup="i_note_count_new('.$x__type.')" class="form-control msg note-textarea algolia_search new-note input_note_'.$x__type.'" note-type-id="' . $x__type . '" id="x__message' . $x__type . '" placeholder="WRITE'.( $handles_url ? ', PASTE URL' : '' ).( $handles_uploads ? ', DROP FILE' : '' ).'" style="margin-top:6px;"></textarea>';



        $ui .= '<table class="table table-condensed"><tr>';


        //Save button:
        $ui .= '<td style="width:85px; padding: 10px 0 0 0;"><a href="javascript:i_note_text('.$x__type.');" class="btn btn-idea save_notes_'.$x__type.'"><i class="fas fa-plus"></i></a></td>';


        //File counter:
        $ui .= '<td style="padding: 10px 0 0 0; font-size: 0.85em;"><span id="ideaNoteNewCount' . $x__type . '" class="hidden"><span id="charNum' . $x__type . '">0</span>/' . config_var(4485).'</span></td>';


        //Upload File:
        if($handles_uploads){
            $ui .= '<td style="width:42px; padding: 10px 0 0 0;">';
            $ui .= '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType'.$x__type.'" />';
            $ui .= '<label class="file_label_'.$x__type.'" for="fileIdeaType'.$x__type.'" data-toggle="tooltip" title="Upload files up to ' . config_var(11063) . 'MB, or upload elsewhere & paste URL here" data-placement="top"><span class="icon-block"><i class="far fa-paperclip"></i></span></label>';
            $ui .= '</td>';
        }


        $ui .= '</tr></table>';


        //Response result:
        $ui .= '<div class="note_error_'.$x__type.'"></div>';


        $ui .= '</form>';
        $ui .= '</div>';
        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}

function view_platform_message($e__id){
    $CI =& get_instance();
    $e___12687 = $CI->config->item('e___12687');
    if(!substr_count($e___12687[$e__id]['m_desc'], " | ")){
        //Single message:
        return $e___12687[$e__id]['m_desc'];
    } else {
        //Random message:
        $line_messages = explode(" | ", $e___12687[$e__id]['m_desc']);
        return $line_messages[rand(0, (count($line_messages) - 1))];
    }
}

function view_unauthorized_message($superpower_e__id = 0){

    $session_source = superpower_assigned($superpower_e__id);

    if(!$session_source){
        if(!$superpower_e__id){

            //Missing Session
            return 'You must login to continue.';

        } else {

            //Missing Superpower:
            $CI =& get_instance();
            $e___10957 = $CI->config->item('e___10957');
            return 'You are missing the required superpower of '.$e___10957[$superpower_e__id]['m_name'];

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

function view_i_cover($i, $show_editor, $discover_mode = true){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    $e___13369 = $CI->config->item('e___13369'); //IDEA COVER UI

    $recipient_source = superpower_assigned();
    $i_stats = i_stats($i['i__metadata']);

    $ui  = '<a href="'.( $discover_mode ? '/'.$i['i__id'] : '/~'.$i['i__id'] ) . '" id="ap_i_'.$i['i__id'].'" '.( isset($i['x__id']) ? ' sort-x-id="'.$i['x__id'].'" ' : '' ).' class="cover-block '.( $show_editor ? ' home_sort ' : '' ).'">';

    $ui .= '<div class="cover-image">';
    if($recipient_source){

        $completion_rate = $CI->X_model->completion_progress($recipient_source['e__id'], $i);

        if($completion_rate['completion_percentage']>0){
            $ui .= '<div class="progress-bg-image" title="discover '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
        }

    }

    $ui .= i_fetch_cover($i['i__id'], true);

    //TOP LEFT
    $ui .= '<span class="media-info top-left" data-toggle="tooltip" data-placement="bottom" title="'.$i_stats['i_average'].' '.$e___13369[12273]['m_name'].' FROM '.$i_stats['count_sources'].' '.$e___13369[12274]['m_name'].'">';

    //SOURCES:
    if(superpower_active(10967, true)){
        $ui .= $e___13369[12274]['m_icon'].'<span style="padding-left: 2px;">'.view_number($i_stats['count_sources']).'</span><br />';
    }

    //IDEAS:
    $ui .= $e___13369[12273]['m_icon'].'<span style="padding-left: 2px;">'.view_number($i_stats['i_average']).'</span><br />';

    //DISCOVERIES:
    if(superpower_active(12701, true)){
        $discover_coins = x_coins_idea(6255, $i['i__id']);
        if($discover_coins > 0){
            $ui .= $e___13369[6255]['m_icon'].'<span style="padding-left: 2px;">'.view_number($discover_coins).'</span>';
        }
    }


    $ui .= '</span>';

    //TOP RIGHT
    if($i_stats['duration_average']){
        $ui .= '<span class="media-info top-right" title="'.$e___13369[13292]['m_name'].'">'.view_time_hours($i_stats['duration_average']).'</span>';
    }

    //Search for Idea Image:
    if($show_editor){

        //SORT
        $ui .= '<span class="media-info bottom-left discover-sorter" title="'.$e___13369[13413]['m_name'].': '.$e___13369[13413]['m_desc'].'">'.$e___13369[13413]['m_icon'].'</span>';

        //IDEA STATUS?
        if(!$discover_mode && !in_array($i['i__status'], $CI->config->item('n___7355'))){
            $ui .= '<span class="media-info bottom-center">'.view_cache('e___4737' /* Idea Status */, $i['i__status'], true, 'top').'</span>';
        }

        //REMOVE
        $ui .= '<span class="media-info bottom-right x_remove" i__id="'.$i['i__id'].'" title="'.$e___13369[13414]['m_name'].'">'.$e___13369[13414]['m_icon'].'</span>';

    }
    $ui .= '</div>';


    //Title + Drafting?
    $ui .= '<b class="montserrat" style="font-size: 0.9em;">'.view_i_title($i).($i['x__sort'] < 1 ? '<div class="dorubik">⚠️ &nbsp;Newly Added</div>' : '').'</b>';

    $ui .= '</a>';

    return $ui;

}


function view_e_basic($source)
{
    $ui = '<div class="list-group-item no-side-padding">';
    $ui .= '<span class="icon-block">' . view_e__icon($source['e__icon']) . '</span>';

    $source__title = '<span '.( isset($source['x__message']) && strlen($source['x__message']) > 0 ? ' class="underdot" title="'.$source['x__message'].'" data-toggle="tooltip" data-placement="top" ' : '' ).'>'.$source['e__title'].'</span>';

    if(superpower_active(10939, true)){
        //Give Link:
        $ui .= '<a class="title-block title-no-right montserrat" href="/@'.$source['e__id'].'">'.$source__title.'</a>';
    } else {
        //No Link:
        $ui .= '<span class="title-block title-no-right">'.$source__title.'</span>';
    }

    $ui .= '</div>';
    return $ui;
}



function view_e($source, $is_parent = false, $extra_class = null, $control_enabled = false, $player_is_e_source = false, $common_prefix = null)
{

    $CI =& get_instance();
    $session_source = superpower_assigned();
    $e___6177 = $CI->config->item('e___6177'); //Source Status
    $e___2738 = $CI->config->item('e___2738');
    $e___4592 = $CI->config->item('e___4592');
    $e___6186 = $CI->config->item('e___6186'); //Interaction Status

    $x__id = (isset($source['x__id']) ? $source['x__id'] : 0);
    $is_link_source = ( $x__id > 0 && in_array($source['x__type'], $CI->config->item('n___4592')));
    $is_x_progress = ( $x__id > 0 && in_array($source['x__type'], $CI->config->item('n___12227')));
    $is_e_only = ( $x__id > 0 && in_array($source['x__type'], $CI->config->item('n___7551')));
    $inline_editing = $control_enabled && superpower_active(13402, true);
    $has_e_editor = superpower_active(10967, true);
    $show_toolbar = superpower_active(12706, true);

    $e__profiles = $CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__down' => $source['e__id'], //This child source
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__status IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__up'), 0, 0, array('e__weight' => 'DESC'));

    $is_public = in_array($source['e__status'], $CI->config->item('n___7357'));
    $is_link_published = ( !$x__id || in_array($source['x__status'], $CI->config->item('n___7359')));
    $is_hidden = filter_array($e__profiles, 'e__id', '4755') || in_array($source['e__id'], $CI->config->item('n___4755'));

    if(!$session_source && (!$is_public || !$is_link_published)){
        //Not logged in, so should only see published:
        return false;
    } elseif($is_hidden && !superpower_assigned(12701)){
        //Cannot see this private discover:
        return false;
    } elseif($is_hidden && !superpower_active(12701, true)){
        //They don't have the needed superpower:
        return false;
    }


    //SOURCE INFO BAR
    $box_items_list = '';

    //SOURCE STATUS
    if(!$is_public){
        $box_items_list .= '<span class="inline-block e__status_' . $source['e__id'].'"><span data-toggle="tooltip" data-placement="right" title="'.$e___6177[$source['e__status']]['m_name'].' @'.$source['e__status'].'">' . $e___6177[$source['e__status']]['m_icon'] . '</span>&nbsp;</span>';
    }

    //DISCOVER STATUS
    if($x__id){
        if(!$is_link_published){
            $box_items_list .= '<span class="inline-block x__status_' . $x__id .'"><span data-toggle="tooltip" data-placement="right" title="'.$e___6186[$source['x__status']]['m_name'].' @'.$source['x__status'].'">' . $e___6186[$source['x__status']]['m_icon'] . '</span>&nbsp;</span>';
        }
    }



    //PORTFOLIO COUNT (SYNC WITH NEXT IDEA COUNT)
    if($show_toolbar){
        $child_counter = '';
        if($has_e_editor){
            $e__portfolio_count = $CI->X_model->fetch(array(
                'x__up' => $source['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'e__status IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC
            ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');
            if($e__portfolio_count[0]['totals'] > 0){
                $child_counter .= '<span class="pull-right" '.( $inline_editing ? ' style="margin-top: -19px;" ' : '' ).'><span class="icon-block doright montserrat source" title="'.number_format($e__portfolio_count[0]['totals'], 0).' PORTFOLIO SOURCES">'.view_number($e__portfolio_count[0]['totals']).'</span></span>';
                $child_counter .= '<div class="doclear">&nbsp;</div>';
            }
        }
    }


    //ROW
    $ui = '<div class="list-group-item no-side-padding itemsource en-item object_saved saved_e_'.$source['e__id'].' e__id_' . $source['e__id'] . ( $x__id > 0 ? ' tr_' . $source['x__id'].' ' : '' ) . ( $is_parent ? ' parent-source ' : '' ) . ' '. $extra_class  . '" source-id="' . $source['e__id'] . '" en-status="' . $source['e__status'] . '" x__id="'.$x__id.'" discover-status="'.( $x__id ? $source['x__status'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';


    //SOURCE
    $ui .= '<td class="MENCHcolumn1">';

    $e_url = ( $is_x_progress ? '/'.$CI->uri->segment(1).'?focus__source='.$source['e__id'] : '/@'.$source['e__id'] );

    //SOURCE ICON
    $ui .= '<a href="'.$e_url.'" '.( $is_link_source ? ' title="INTERACTION ID '.$source['x__id'].' TYPE @'.$source['x__type'].' SORT '.$source['x__sort'].' WEIGHT '.$source['e__weight'].'" ' : '' ).'><span class="icon-block e_ui_icon_' . $source['e__id'] . ' e__icon_'.$source['e__id'].'" en-is-set="'.( strlen($source['e__icon']) > 0 ? 1 : 0 ).'">' . view_e__icon($source['e__icon']) . '</span></a>';


    //SOURCE TOOLBAR?
    if($inline_editing){

        $ui .= view_input_text(6197, $source['e__title'], $source['e__id'], $player_is_e_source, 0, false, null, extract_icon_color($source['e__icon']));

        if($show_toolbar){
            $ui .= $child_counter;
            $ui .= '<div class="space-content">'.$box_items_list.'</div>';
        }

    } else {

        //SOURCE NAME
        $ui .= '<a href="'.$e_url.'" class="title-block title-no-right montserrat '.extract_icon_color($source['e__icon']).'">';
        $ui .= $box_items_list;
        $ui .= '<span class="text__6197_' . $source['e__id'] . '">'.( $common_prefix ? str_replace($common_prefix, '', $source['e__title']) :  $source['e__title'] ).'</span>';
        if($show_toolbar){
            $ui .= $child_counter;
        }
        $ui .= '</a>';

    }

    $ui .= '</td>';




    //IDEA
    $ui .= '<td class="MENCHcolumn2 source">';

    //RIGHT EDITING:
    $ui .= '<div class="pull-right inline-block">';
    $ui .= '<div class="note-editor edit-off">';
    $ui .= '<span class="show-on-hover">';

    if($control_enabled && $player_is_e_source){
        if($is_link_source){

            //Sort
            if(!$is_parent && $has_e_editor){
                $ui .= '<span title="SORT"><i class="fas fa-bars hidden black"></i></span>';
            }

            //Manage source link:
            $ui .= '<span class="'.superpower_active(10967).'"><a href="javascript:void(0);" onclick="e_modify_load(' . $source['e__id'] . ',' . $x__id . ')"><i class="fas fa-pen-square black"></i></a></span>';


        } elseif($is_e_only){

            //Allow to remove:
            $ui .= '<span><a href="javascript:void(0);" onclick="e_only_unlink(' . $x__id . ', '.$source['x__type'].')"><i class="fas fa-times black"></i></a></span>';

        }
    }

    $ui .= '</span>';
    $ui .= '</div>';
    $ui .= '</div>';

    $ui .= view_coins_count_source(0, $source['e__id']);
    $ui .= '</td>';




    //DISCOVER
    $ui .= '<td class="MENCHcolumn3 discover">';
    $ui .= view_coins_count_discover(0, $source['e__id']);
    $ui .= '</td>';




    $ui .= '</tr></table>';



    if($show_toolbar){
        //PROFILE
        $ui .= '<div class="space-content hideIfEmpty">';
        //PROFILE SOURCES:
        $ui .= '<span class="paddingup inline-block hideIfEmpty">';
        foreach($e__profiles as $e_profile) {
            $ui .= '<span class="icon-block-img e_child_icon_' . $e_profile['e__id'] . '"><a href="/@' . $e_profile['e__id'] . '" data-toggle="tooltip" title="' . $e_profile['e__title'] . (strlen($e_profile['x__message']) > 0 ? ' = ' . $e_profile['x__message'] : '') . '" data-placement="bottom">' . view_e__icon($e_profile['e__icon']) . '</a></span> ';
        }
        $ui .= '</span>';
        $ui .= '</div>';
    }



    //MESSAGE
    if ($x__id > 0) {
        if($is_link_source){

            $ui .= '<span class="message_content paddingup x__message hideIfEmpty x__message_' . $x__id . '">' . view_x__message($source['x__message'] , $source['x__type']) . '</span>';

            //For JS editing only (HACK):
            $ui .= '<div class="x__message_val_' . $x__id . ' hidden overflowhide">' . $source['x__message'] . '</div>';

        } elseif($is_x_progress && strlen($source['x__message'])){

            //DISCOVER PROGRESS
            $ui .= '<div class="message_content paddingup">';
            $ui .= $CI->X_model->message_send($source['x__message']);
            $ui .= '</div>';

        }
    }





    $ui .= '</div>';

    return $ui;

}


function view_input_text($cache_e__id, $current_value, $object__id, $player_is_i_source, $tabindex = 0, $extra_large = false, $e__icon = null, $append_css = null){

    $CI =& get_instance();
    $e___12112 = $CI->config->item('e___12112');
    $current_value = htmlentities($current_value);

    //Define element attributes:
    $attributes = ( $player_is_i_source ? '' : 'disabled' ).' tabindex="'.$tabindex.'" old-value="'.$current_value.'" class="form-control dotransparent montserrat inline-block x_set_text text__'.$cache_e__id.'_'.$object__id.' texttype_'.($extra_large?'_lg':'_sm').' text_e_'.$cache_e__id.' '.$append_css.'" cache_e__id="'.$cache_e__id.'" object__id="'.$object__id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $focus_element = '<textarea '.( !strlen($append_css) ? ' style="color:#000000 !important;" ' : '' ).' onkeyup="view_input_text_count('.$cache_e__id.','.$object__id.')" placeholder="'.$e___12112[$cache_e__id]['m_name'].'" '.$attributes.'>'.$current_value.'</textarea>';
        $character_counter = '<div class="title_counter title_counter_'.$cache_e__id.'_'.$object__id.' hidden grey montserrat doupper" style="text-align: right;"><span id="current_count_'.$cache_e__id.'_'.$object__id.'">0</span>/'.config_var($cache_e__id).' CHARACTERS</div>';
        $icon = '<span class="icon-block title-icon">'.( $e__icon ? $e__icon : $e___12112[4535]['m_icon'] ).'</span>';

    } else {

        $focus_element = '<input type="text" placeholder="__" value="'.$current_value.'" '.$attributes.' />';
        $character_counter = ''; //None
        if(in_array($cache_e__id, $CI->config->item('n___12420'))){ //IDEA TEXT INPUT SHOW ICON
            $icon = '<span class="icon-block">'.( $e__icon ? $e__icon : $e___12112[$cache_e__id]['m_icon'] ).'</span>';
        } else {
            $icon = $e__icon;
        }

    }

    return '<span class="span__'.$cache_e__id.' '.( !$player_is_i_source ? 'edit-locked' : '' ).'">'.$icon.$focus_element.'</span>'.$character_counter;
}




function view_input_dropdown($cache_e__id, $selected_e__id, $btn_class, $player_is_i_source = true, $show_full_name = true, $i__id = 0, $x__id = 0){

    $CI =& get_instance();
    $e___this = $CI->config->item('e___'.$cache_e__id);

    if(!$selected_e__id || !isset($e___this[$selected_e__id])){
        return false;
    }

    $e___12079 = $CI->config->item('e___12079');
    $e___4527 = $CI->config->item('e___4527');

    //data-toggle="tooltip" data-placement="top" title="'.$e___4527[$cache_e__id]['m_name'].'"
    $ui = '<div title="'.$e___12079[$cache_e__id]['m_name'].'" data-toggle="tooltip" data-placement="top" class="inline-block">';
    $ui .= '<div class="dropdown inline-block dropd_'.$cache_e__id.'_'.$i__id.'_'.$x__id.' '.( !$show_full_name ? ' icon-block ' : '' ).'" selected-val="'.$selected_e__id.'">';

    $ui .= '<button type="button" '.( $player_is_i_source ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' '.$btn_class.'" id="dropdownMenuButton'.$cache_e__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked  '.$btn_class.'"' ).' >';

    $ui .= '<span class="icon-block">' .$e___this[$selected_e__id]['m_icon'].'</span><span class="show-max">'.( $show_full_name ?  $e___this[$selected_e__id]['m_name'] : '' ).'</span>';

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$cache_e__id.'">';

    foreach($e___this as $e__id => $m) {

        $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m_parents']);
        $is_url_desc = ( substr($m['m_desc'], 0, 1)=='/' );

        //What type of URL?
        if($is_url_desc){

            //Basic link:
            $anchor_url = ( $e__id==$selected_e__id ? 'href="javascript:void();"' : 'href="'.$m['m_desc'].'"' );

        } else{

            //Idea Dropdown updater:
            $anchor_url = 'href="javascript:void();" new-en-id="'.$e__id.'" onclick="i_set_dropdown('.$cache_e__id.', '.$e__id.', '.$i__id.', '.$x__id.', '.intval($show_full_name).')"';

        }

        $ui .= '<a class="dropdown-item dropi_'.$cache_e__id.'_'.$i__id.'_'.$x__id.' montserrat optiond_'.$e__id.'_'.$i__id.'_'.$x__id.' doupper '.( $e__id==$selected_e__id ? ' active ' : ( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ) ).'" '.$anchor_url.'><span class="icon-block">'.$m['m_icon'].'</span>'.$m['m_name'].'</a>'; //Used to show desc but caused JS click conflict sp retired for now: ( strlen($m['m_desc']) && !$is_url_desc ? 'title="'.$m['m_desc'].'" data-toggle="tooltip" data-placement="right"' : '' )

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

