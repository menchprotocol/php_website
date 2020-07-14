<?php

function view_e_load_more($page, $limit, $e__portfolio_count)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $ui = '<div class="load-more montserrat list-group-item itemsource no-left-padding"><a href="javascript:void(0);" onclick="e_load_page(' . $page . ', 0)">';

    //Regular section:
    $max_es = (($page + 1) * $limit);
    $max_es = ($max_es > $e__portfolio_count ? $e__portfolio_count : $max_es);
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
     * Displays Source Transactions @4592
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

        return '<a href="' . $x__message . '" class="btn btn-i" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';

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
     *       transaction types. Change with care...
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

                //Header For Time
                if($end_time){
                    $embed_html_code .= '<div class="headline" style="padding-bottom: 0;"><span class="img-block icon-block-xs"><i class="fas fa-cut"></i></span>FROM '.view_time_hours($start_time, true).' TO '.view_time_hours($end_time, true).'</div>';
                }

                $embed_html_code .= '<div class="media-content"><div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/' . $video_id . '?wmode=opaque&theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_time . ($end_time ? '&end=' . $end_time : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div></div>';

            }

        } elseif (substr_count($url, 'vimeo.com/') == 1 && is_numeric(one_two_explode('vimeo.com/','?',$url))) {

            //Seems to be Vimeo:
            $video_id = trim(one_two_explode('vimeo.com/', '?', $url));

            //This should be an integer!
            if (intval($video_id) == $video_id) {
                $clean_url = 'https://vimeo.com/' . $video_id;
                $embed_html_code = '<div class="media-content"><div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://miner.vimeo.com/video/' . $video_id . '?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div></div>';
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


function view_i_notes($x, $note_is_e = false)
{

    /*
     *
     * A wrapper function that helps manage messages
     * by giving the message additional platform functions
     * such as editing and changing message type.
     *
     * */


    $CI =& get_instance();
    $session_e = superpower_assigned();
    $e___4485 = $CI->config->item('e___4485'); //IDEA NOTES
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $note_is_e = ( $note_is_e || superpower_active(10984, true) );


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item itemidea is-msg note_sortable msg_e_type_' . $x['x__type'] . '" id="ul-nav-' . $x['x__id'] . '" x__id="' . $x['x__id'] . '" title="'.$x['e__title'].' Posted On '.substr($x['x__time'], 0, 19).'" data-toggle="tooltip" data-placement="top">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="text_message edit-off" id="msgbody_' . $x['x__id'] . '">';
    $ui .= $CI->X_model->message_send($x['x__message'], $session_e, $x['x__right']);
    $ui .= '</div>';

    //Editing menu:
    if($note_is_e){
        $ui .= '<div class="note-editor edit-off"><span class="show-on-hover">';

        //Sorting allowed?
        if(in_array($x['x__type'], $CI->config->item('n___4603'))){
            $ui .= '<span title="SORT"><i class="fas fa-bars i_note_sorting"></i></span>';
        }

        //Modify:
        $ui .= '<span title="MODIFY"><a href="javascript:i_note_edit_start(' . $x['x__id'] . ');"><i class="fas fa-pen-square"></i></a></span>';

        $ui .= '</span></div>';


        //Text editing:
        $ui .= '<textarea onkeyup="i_note_edit_count(' . $x['x__id'] . ')" name="x__message" id="message_body_' . $x['x__id'] . '" class="edit-on hidden msg note-textarea algolia_search" placeholder="'.stripslashes($x['x__message']).'">' . $x['x__message'] . '</textarea>';


        //Editing menu:
        $ui .= '<ul class="msg-nav">';

        //Counter:
        $ui .= '<li class="edit-on hidden"><span id="ideaNoteCount' . $x['x__id'] . '"><span id="charEditingNum' . $x['x__id'] . '">0</span>/' . config_var(4485) . '</span></li>';

        //Save Edit:
        $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-i white-third" href="javascript:i_note_edit(' . $x['x__id'] . ',' . $x['x__type'] . ');"><i class="fas fa-check"></i> Save</a></li>';

        //Cancel Edit:
        $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-i white-third" href="javascript:i_note_edit_cancel(' . $x['x__id'] . ');"><i class="fas fa-times"></i></a></li>';

        //Show drop down for message transaction status:
        $ui .= '<li class="pull-right edit-on hidden"><span class="white-wrapper" style="margin:-5px 5px 0 0; display: block;">';
        $ui .= '<select id="message_status_' . $x['x__id'] . '"  class="form-control border" style="margin-bottom:0;">';
        foreach($CI->config->item('e___12012') as $e__id => $m){
            $ui .= '<option value="' . $e__id . '" '.( $e__id==$x['x__status'] ? 'selected="selected"' : '' ).'>' . $m['m_name'] . '</option>';
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
    //A simple function to display the Miner Icon OR the default icon if not available:
    if (strlen($e__icon) > 0) {

        return $e__icon;

    } else {
        //Return default icon for sources:
        $CI =& get_instance();
        $e___12467 = $CI->config->item('e___12467'); //MENCH
        return $e___12467[12274]['m_icon'];
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


function view_x($x, $is_parent_tr = false)
{

    $CI =& get_instance();
    $e___4593 = $CI->config->item('e___4593'); //Transaction Type
    $e___4341 = $CI->config->item('e___4341'); //Transaction Table
    $e___12467 = $CI->config->item('e___12467');
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $session_e = superpower_assigned();



    if(!isset($e___4593[$x['x__type']])){
        //We've probably have not yet updated php cache, set error:
        $e___4593[$x['x__type']] = array(
            'm_icon' => '<i class="fas fa-exclamation-circle"></i>',
            'm_name' => 'Transaction Type Not Synced in PHP Cache',
            'm_desc' => '',
            'm_parents' => array(),
        );
    }





    //Display the item
    $ui = '<div class="x-list">';


    //Transaction ID
    $ui .= '<div class="simple-line"><a href="/ledger?x__id='.$x['x__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4367]['m_name'].'" class="mono-space"><span class="icon-block">'.$e___4341[4367]['m_icon']. '</span>'.$x['x__id'].'</a></div>';


    //Status
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$e___4341[6186]['m_name'].( strlen($e___6186[$x['x__status']]['m_desc']) ? ': '.$e___6186[$x['x__status']]['m_desc'] : '' ).'"><span class="icon-block">'.$e___6186[$x['x__status']]['m_icon'].'</span>'.$e___6186[$x['x__status']]['m_name'].'</span></div>';

    //Time
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $e___4341[4362]['m_name'].': '.$x['x__time'] . ' PST"><span class="icon-block">'.$e___4341[4362]['m_icon']. '</span>' . view_time_difference(strtotime($x['x__time'])) . ' ago</span></div>';



    //COINS AWARDED?
    if(in_array($x['x__type'], $CI->config->item('n___6255'))){
        $coins_type = 'discover';
    } elseif(in_array($x['x__type'], $CI->config->item('n___12274'))){
        $coins_type = 'source';
    } elseif(in_array($x['x__type'], $CI->config->item('n___12273')) && ($x['x__up']>0 || $x['x__down']>0)){
        $coins_type = 'idea';
    } else {
        $coins_type = null;
    }

    //Transaction Type & Coins
    $ui .= '<div class="simple-line"><a href="/@'.$x['x__type'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4593]['m_name'].( strlen($e___4593[$x['x__type']]['m_desc']) ? ': '.$e___4593[$x['x__type']]['m_desc'] : '' ).'" class="montserrat"><span class="icon-block">'.$e___4341[4593]['m_icon']. '</span><span class="'.extract_icon_color($e___4593[$x['x__type']]['m_icon']).'">'. $e___4593[$x['x__type']]['m_icon'] . '&nbsp;' . $e___4593[$x['x__type']]['m_name'] . '</span>'.($coins_type ? '&nbsp;<span title="'.$coins_type.' coin awarded" data-toggle="tooltip" data-placement="top"><i class="fas fa-circle '.$coins_type.'"></i></span>' : '').'</a></div>';


    //Hide Sensitive Details?
    if(in_array($x['x__type'] , $CI->config->item('n___4755')) && (!$session_e || $x['x__miner']!=$session_e['e__id']) && !superpower_active(12701, true)){

        //Hide Information:
        $ui .= '<div class="simple-line"><span data-toggle="tooltip" class="montserrat" data-placement="top" title="Details are kept private"><span class="icon-block"><i class="fal fa-eye-slash"></i></span>PRIVATE INFORMATION</span></div>';

    } else {

        //Metadata
        if(strlen($x['x__metadata']) > 0){
            $ui .= '<div class="simple-line"><a href="/e/plugin/12722?x__id=' . $x['x__id'] . '" class="montserrat"><span class="icon-block">'.$e___4341[6103]['m_icon']. '</span>'.$e___4341[6103]['m_name']. '</a></div>';
        }

        //Order
        if($x['x__sort'] > 0){
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$e___4341[4370]['m_name']. '"><span class="icon-block">'.$e___4341[4370]['m_icon']. '</span>'.view_ordinal($x['x__sort']).'</span></div>';
        }


        //Message
        if(strlen($x['x__message']) > 0 && $x['x__message']!='@'.$x['x__up']){
            $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4372]['m_name'].'"><span class="icon-block">'.$e___4341[4372]['m_icon'].'</span><div class="title-block x-msg">'.htmlentities($x['x__message']).'</div></div>';
        }


        //Creator (Do not repeat)
        if($x['x__miner'] > 0 && $x['x__miner']!=$x['x__up'] && $x['x__miner']!=$x['x__down']){

            $add_es = $CI->E_model->fetch(array(
                'e__id' => $x['x__miner'],
            ));

            $ui .= '<div class="simple-line"><a href="/@'.$add_es[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4364]['m_name'].'" class="montserrat"><span class="icon-block">'.$e___4341[4364]['m_icon']. '</span><span class="'.extract_icon_color($add_es[0]['e__icon']).'"><span class="img-block">'.view_e__icon($add_es[0]['e__icon']) . '</span> ' . $add_es[0]['e__title'] . '</span></a></div>';

        }

    }


    //5x Relations:
    if(!$is_parent_tr){

        $var_index = var_index();
        foreach($CI->config->item('e___10692') as $e__id => $m) {

            //Do we have this set?
            if(!array_key_exists($e__id, $var_index) || !intval($x[$var_index[$e__id]])){
                continue;
            }

            if(in_array(6160 , $m['m_parents'])){

                //SOURCE
                $es = $CI->E_model->fetch(array('e__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/@'.$es[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m_name'].'" class="montserrat"><span class="icon-block">'.$e___4341[$e__id]['m_icon']. '</span>'.( $x[$var_index[$e__id]]==$x['x__miner'] ? $e___4341[4364]['m_icon']. '&nbsp;' : '' ).'<span class="'.extract_icon_color($es[0]['e__icon']).' img-block">'.view_e__icon($es[0]['e__icon']). '&nbsp;'.$es[0]['e__title'].'</span></a></div>';

            } elseif(in_array(6202 , $m['m_parents'])){

                //IDEA
                $is = $CI->I_model->fetch(array('i__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/i/i_go/'.$is[0]['i__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m_name'].'" class="montserrat"><span class="icon-block">'.$e___4341[$e__id]['m_icon']. '</span>'.$e___12467[12273]['m_icon']. '&nbsp;'.view_i_title($is[0]).'</a></div>';

            } elseif(in_array(4367 , $m['m_parents'])){

                //PARENT DISCOVER
                $x = $CI->X_model->fetch(array('x__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m_name'].'">'.$e___4341[$e__id]['m_icon']. '</span><div class="x-ref">'.view_x($x[0], true).'</div></div>';

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


function view_cache($config_var_id, $e__id, $micro_status = true, $data_placement = 'top', $i__id = 0)
{

    /*
     *
     * UI for Platform Cache sources
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item('e___'.$config_var_id);
    $cache = $config_array[$e__id];
    if (!$cache) {
        //Could not find matching item
        return false;
    }


    //We have two skins for displaying Status:
    if (is_null($data_placement)) {
        if($micro_status){
            return $cache['m_icon'].' ';
        } else {
            return $cache['m_icon'].' '.$cache['m_name'].' ';
        }
    } else {
        return '<span class="'.( $micro_status ? 'cache_micro_'.$config_var_id.'_'.$i__id : '' ).'" ' . ( $micro_status && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="' . $data_placement . '" title="' . ($micro_status ? $cache['m_name'] : '') . (strlen($cache['m_desc']) > 0 ? ($micro_status ? ': ' : '') . $cache['m_desc'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache['m_icon'] . ' ' . ($micro_status ? '' : $cache['m_name']) . '</span>';
    }
}



function view_coins_count_x($i__id = 0, $e__id = 0){

    $CI =& get_instance();
    $query_filters = array(
        'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null,
        ( $i__id > 0 ? 'x__left' : 'x__miner' ) => ( $i__id > 0 ? $i__id : $e__id ),
    );

    if(isset($_GET['focus__e'])){
        $query_filters['x__miner'] = intval($_GET['focus__e']);
    }

    $x_coins = $CI->X_model->fetch($query_filters, array(), 1, 0, array(), 'COUNT(x__id) as totals');

    if($x_coins[0]['totals'] > 0){
        return '<span class="montserrat discover"><span class="icon-block"><i class="fas fa-circle"></i></span>'.view_number($x_coins[0]['totals']).'</span>';
    } else {
        return false;
    }

}


function view_coins_count_e($i__id = 0, $e__id = 0, $number_only = false){

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

function view_i_x_icon($completion_percentage){

    $CI =& get_instance();
    $e___12446 = $CI->config->item('e___12446'); //DISCOVER ICON LEGEND
    $recipient_e = superpower_assigned();
    if(!$recipient_e){
        //DISCOVER GUEST
        $x_legend = 12273;
    } elseif($completion_percentage==0){
        //DISCOVER NOT STARTED
        $x_legend = 12448;
    } elseif($completion_percentage<100){
        //DISCOVER IN PROGRESS
        $x_legend = 12447;
    } else {
        //DISCOVER COMPLETED
        $x_legend = 13338;
    }

    return '<span title="'.$e___12446[$x_legend]['m_name'].'">'.$e___12446[$x_legend]['m_icon'].'</span>';

}

function view_i_x($i, $common_prefix = null, $show_editor = false, $completion_rate = null, $recipient_e = false, $extra_class = null)
{

    //See if miner is logged-in:
    $CI =& get_instance();
    if(!$recipient_e){
        $recipient_e = superpower_assigned();
    }


    if(!$completion_rate){
        if($recipient_e){
            $completion_rate = $CI->X_model->completion_progress($recipient_e['e__id'], $i);
        } else {
            $completion_rate['completion_percentage'] = 0;
        }
    }

    $i_stats = i_stats($i['i__metadata']);
    $is_saved = ( isset($i['x__type']) && $i['x__type']==12896 );
    $can_click = ( $completion_rate['completion_percentage']>0 || $is_saved || $recipient_e );
    $first_segment = $CI->uri->segment(1);
    $e___12467 = $CI->config->item('e___12467'); //MENCH COINS
    $has_completion = $can_click && $completion_rate['completion_percentage']>0 && $completion_rate['completion_percentage']<100;

    //Build View:
    $ui  = '<div id="i_saved_'.$i['i__id'].'" '.( isset($i['x__id']) ? ' sort-x-id="'.$i['x__id'].'" ' : '' ).' class="list-group-item no-side-padding '.( $show_editor ? 'home_sort' : '' ).( $can_click ? ' itemdiscover ' : '' ).' '.$extra_class.'" style="padding-right:17px;">';

    $ui .= ( $can_click ? '<a href="/'. $i['i__id'] .'" class="itemdiscover">' : '' );


    if($has_completion){
        $ui .= '<div class="progress-bg-list" title="discover '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><span class="progress-connector"></span><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
    }

    $ui .= '<span class="icon-block">'.view_i_x_icon($completion_rate['completion_percentage']).'</span>';

    $ui .= '<b class="'.( $can_click ? 'montserrat' : '' ).' i-url title-block">'.view_i_title($i, $common_prefix).'</b>';


    //Extra Stats
    $ui .= '<div class="montserrat handler_13509 hidden hideIfEmpty" style="padding:13px 0 0 34px;">';

    if($i_stats['e_count']){
        $ui .= '<span style="width: 55px;" class="source inline-block">'.$e___12467[12274]['m_icon'].' '.$i_stats['e_count'].'</span>';
    }

    if($i_stats['i___13443']){
        $ui .= '<span style="width: 55px;" class="idea inline-block">'.$e___12467[12273]['m_icon'].' '.$i_stats['i___13443'].'</span>';
    }

    if($i_stats['i___13292']){
        $ui .= '<span class="mono-space inline-block">'.view_time_hours($i_stats['i___13292']).'</span>';
    }

    $ui .= '</div>';

    //Search for Idea Image:
    if($show_editor){
        if($is_saved){

            $ui .= '<div class="note-editor edit-off">';
            $ui .= '<span class="show-on-hover">';
            $ui .= '<span><a href="javascript:void(0);" title="Unsave" data-toggle="tooltip" data-placement="left" onclick="i_save('.$i['i__id'].');$(\'#i_saved_'.$i['i__id'].'\').remove();"><i class="fas fa-times" style="margin-top: 10px;"></i></a></span>';
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
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $e___4486 = $CI->config->item('e___4486');
    $e___4737 = $CI->config->item('e___4737'); // Idea Status
    $e___7585 = $CI->config->item('e___7585'); // Idea Subtypes


    $ui = null;
    foreach($CI->X_model->fetch(array(
        'x__left' => $i__id,
        'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'i__status IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
    ), array('x__right'), 0, 0, array('x__sort' => 'ASC')) as $i_x){

        //Prep Metadata:
        $metadata = unserialize($i_x['x__metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->X_model->fetch(array(
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $i_x['i__id'],
        ), array(), 0, 0, array('x__sort' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Transaction Type: '.$e___4486[$i_x['x__type']]['m_name'].'">'. $e___4486[$i_x['x__type']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Transaction Status: '.$e___6186[$i_x['x__status']]['m_name'].'">'. $e___6186[$i_x['x__status']]['m_icon'] . '</span>';

        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Type: '.$e___7585[$i_x['i__type']]['m_name'].'">'. $e___7585[$i_x['i__type']]['m_icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Status: '.$e___4737[$i_x['i__status']]['m_name'].'">'. $e___4737[$i_x['i__status']]['m_icon']. '</span>';
        $ui .= '<a href="?i__id='.$i_x['i__id'].'&depth_levels='.$original_depth_levels.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this idea"><u>' .   view_i_title($i_x) . '</u></a>';

        $ui .= ' [<span data-toggle="tooltip" data-placement="top" title="Completion Marks">'.( ($i_x['x__type'] == 4228 && in_array($previous_i__type , $CI->config->item('n___6193') /* OR Ideas */ )) || ($i_x['x__type'] == 4229) ? view_i_marks($i_x) : '' ).'</span>]';

        if(count($messages) > 0){
            $ui .= ' <a href="javascript:void(0);" onclick="$(\'.messages-'.$i_x['i__id'].'\').toggleClass(\'hidden\');"><i class="fas fa-comment"></i><b>' .  count($messages) . '</b></a>';
        }
        $ui .= '</div>';

        //Display Messages:
        $ui .= '<div class="messages-'.$i_x['i__id'].' hidden">';
        foreach($messages as $msg) {
            $ui .= '<div class="tip_bubble">';
            $ui .= $CI->X_model->message_send($msg['x__message']);
            $ui .= '</div>';
        }
        $ui .= '</div>';

        //Go Recursively down:
        $ui .=  view_i_scores_answer($i_x['i__id'], $depth_levels, $original_depth_levels, $i_x['i__type']);

    }

    //Return the wrapped UI if existed:
    return ($ui ? '<div class="inline-box">' . $ui . '</div>' : false);
}

function view_radio_es($parent_e__id, $child_e__id, $enable_mulitiselect, $show_max = 25){

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


function view_i_marks($i_x){

    //Validate core inputs:
    if(!isset($i_x['x__metadata']) || !isset($i_x['x__type'])){
        return false;
    }

    //prep metadata:
    $x__metadata = unserialize($i_x['x__metadata']);

    //Return mark:
    return ( $i_x['x__type'] == 4228 ? ( !isset($x__metadata['tr__assessment_points']) || $x__metadata['tr__assessment_points'] == 0 ? '' : '<span class="score-range">[<span style="'.( $x__metadata['tr__assessment_points']>0 ? 'font-weight:bold;' : ( $x__metadata['tr__assessment_points'] < 0 ? 'font-weight:bold;' : '' )).'">' . ( $x__metadata['tr__assessment_points'] > 0 ? '+' : '' ) . $x__metadata['tr__assessment_points'].'</span>]</span>' ) : '<span class="score-range">['.$x__metadata['tr__conditional_score_min'] . ( $x__metadata['tr__conditional_score_min']==$x__metadata['tr__conditional_score_max'] ? '' : '-'.$x__metadata['tr__conditional_score_max'] ).'%]</span>' );

}


function view_i($i, $i_x_id = 0, $is_parent = false, $e_owns_i = false, $message_input = null, $extra_class = null, $control_enabled = true)
{

    $CI =& get_instance();
    $session_e = superpower_assigned();
    $e___6186 = $CI->config->item('e___6186');
    $e___4737 = $CI->config->item('e___4737'); //IDEA STATUS
    $e___7585 = $CI->config->item('e___7585');
    $e___4486 = $CI->config->item('e___4486');
    $e___12467 = $CI->config->item('e___12467');
    $e___12413 = $CI->config->item('e___12413');
    $e___13408 = $CI->config->item('e___13408');

    //DISCOVER
    $x__id = ( isset($i['x__id']) ? $i['x__id'] : 0 );
    $is_i_x = ($x__id && in_array($i['x__type'], $CI->config->item('n___4486')));

    //IDEA
    $i_stats = i_stats($i['i__metadata']);
    $is_public = in_array($i['i__status'], $CI->config->item('n___7355'));
    $e_owns_i = ( !$is_i_x ? false : $e_owns_i ); //Disable Edits on Idea List Page
    $show_toolbar = ($control_enabled && superpower_active(12673, true));




    //IDAE INFO BAR
    $box_items_list = '';

    //DISCOVER STATUS
    if($x__id && !in_array($i['x__status'], $CI->config->item('n___7359'))){
        $box_items_list .= '<span class="inline-block"><span data-toggle="tooltip" data-placement="right" title="'.$e___6186[$i['x__status']]['m_name'].' @'.$i['x__status'].'">' . $e___6186[$i['x__status']]['m_icon'] . '</span>&nbsp;</span>';
    }


    $ui = '<div x__id="' . $x__id . '" i-id="' . $i['i__id'] . '" class="list-group-item no-side-padding itemidea itemidealist i_sortable paddingup level2_in object_saved saved_i_'.$i['i__id'] . ' i_line_' . $i['i__id'] . ( $is_parent ? ' parent-i ' : '' ) . ' i__tr_'.$x__id.' '.$extra_class.'" style="padding-left:0;">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';

    $ui .= '<td class="MENCHcolumn1">';
        $ui .= '<div class="block">';

            //IDAE Transaction:
            $i_x = '/i/i_go/'.$i['i__id'].( isset($_GET['focus__e']) ? '?focus__e='.intval($_GET['focus__e']) : '' );

            //IDEA STATUS:
            $ui .= '<span class="icon-block"><a href="'.$i_x.'" title="Idea Weight: '.number_format($i['i__weight'], 0).'">'.view_cache(4737 /* Idea Status */, $i['i__status'], true, 'right', $i['i__id']).'</a></span>';

            //IDEA TITLE
            if($is_i_x && superpower_active(13354, true)){

                $ui .= view_input_text(4736, $i['i__title'], $i['i__id'], $e_owns_i, (($i['x__sort']*100)+1));

            } else {

                $ui .= '<a href="'.$i_x.'" class="title-block montserrat">';
                $ui .= $box_items_list;
                $ui .= view_i_title($i); //IDEA TITLE
                $ui .= '</a>';

            }

        $ui .= '</div>';
    $ui .= '</td>';


    //DISCOVER
    $ui .= '<td class="MENCHcolumn2 source">';
    $ui .= view_coins_count_x($i['i__id']);
    $ui .= '</td>';


    //SOURCE
    $ui .= '<td class="MENCHcolumn3 source">';
    if($is_i_x && $control_enabled && $e_owns_i){

        //RIGHT EDITING:
        $ui .= '<div class="pull-right inline-block '.superpower_active(10939).'">';
        $ui .= '<div class="note-editor edit-off">';
        $ui .= '<span class="show-on-hover">';

        if(!$is_parent){
            $ui .= '<span title="SORT"><i class="fas fa-bars black i-sort-handle"></i></span>';
        }

        //Remove:
        $ui .= '<span title="REMOVE"><a href="javascript:void(0);" onclick="i_remove('.$i['i__id'].', '.$i['x__id'].', '.( $is_parent ? 1 : 0 ).')"><i class="fas fa-times black"></i></a></span>';

        $ui .= '</span>';
        $ui .= '</div>';
        $ui .= '</div>';

    }

    //SOURCE STATS
    $ui .= view_coins_count_e($i['i__id']);
    $ui .= '</td>';



    $ui .= '</tr></table>';



    if($message_input && trim($message_input)!=$CI->uri->segment(1)){
        $ui .= '<div class="i-footer hideIfEmpty">' . $CI->X_model->message_send($message_input, $session_e) . '</div>';
    }


    if($show_toolbar){

        //Idea Toolbar
        $ui .= '<div class="space-content ' . superpower_active(12673) . '" style="padding-left:25px; padding-top:13px;">';

        $ui .= $box_items_list;

        //IDEA TYPE
        $ui .= '<div class="inline-block">'.view_input_dropdown(7585, $i['i__type'], null, $e_owns_i, false, $i['i__id']).'</div>';

        //IDEA STATUS
        $ui .= '<div class="inline-block">' . view_input_dropdown(4737, $i['i__status'], null, $e_owns_i, false, $i['i__id']) . ' </div>';




        if($x__id){

            $x__metadata = unserialize($i['x__metadata']);

            //IDEA LINK BAR
            $ui .= '<span class="' . superpower_active(12700) . '">';

            //LINK TYPE
            $ui .= view_input_dropdown(4486, $i['x__type'], null, $e_owns_i, false, $i['i__id'], $i['x__id']);

            //LINK MARKS
            $ui .= '<span class="x_marks settings_4228 '.( $i['x__type']==4228 ? : 'hidden' ).'">';
            $ui .= view_input_text(4358, ( isset($x__metadata['tr__assessment_points']) ? $x__metadata['tr__assessment_points'] : '' ), $i['x__id'], $e_owns_i, ($i['x__sort']*10)+2 );
            $ui .='</span>';


            //LINK CONDITIONAL RANGE
            $ui .= '<span class="x_marks settings_4229 '.( $i['x__type']==4229 ? : 'hidden' ).'">';
            //MIN
            $ui .= view_input_text(4735, ( isset($x__metadata['tr__conditional_score_min']) ? $x__metadata['tr__conditional_score_min'] : '' ), $i['x__id'], $e_owns_i, ($i['x__sort']*10)+3);
            //MAX
            $ui .= view_input_text(4739, ( isset($x__metadata['tr__conditional_score_max']) ? $x__metadata['tr__conditional_score_max'] : '' ), $i['x__id'], $e_owns_i, ($i['x__sort']*10)+4);
            $ui .= '</span>';
            $ui .= '</span>';

        }




        //IDEA TREE:
        $previous_is = $CI->X_model->fetch(array(
            'x__right' => $i['i__id'],
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array(), 'COUNT(x__id) as total_is');

        $next_is = $CI->X_model->fetch(array(
            'x__left' => $i['i__id'],
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0, array(), 'COUNT(x__id) as total_is');

        $ui .= '<span class="inline-block montserrat idea" title="'.$e___12413[11019]['m_name'].'" style="width:21px; text-align:right;">'.( $previous_is[0]['total_is']>0 ? $previous_is[0]['total_is'] : '&nbsp;' ).'</span>';
        $ui .= '<span class="icon-block">'.$e___13408[12413]['m_icon'].'</span>';
        $ui .= '<span class="inline-block montserrat idea" title="'.$e___12413[11020]['m_name'].'" style="text-align:left;">'.($next_is[0]['total_is']>0 ? $next_is[0]['total_is'] : '' ).( $i_stats['i___6170']>$next_is[0]['total_is'] ? '<span style="padding: 0 2px;">-</span>'.$i_stats['i___6170'] : '' ).'</span>';



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
    $ui .= '<a class="nav-x dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>';
    $ui .= '<div class="dropdown-menu">';
    foreach($CI->config->item('e___'.$e__id) as $e__id2 => $m2){
        $ui .= '<a class="dropdown-item montserrat '.extract_icon_color($m2['m_icon']).'" href="' . $m2['m_desc'] . $object__id . '"><span class="icon-block">'.view_e__icon($m2['m_icon']).'</span> '.$m2['m_name'].'</a>';
    }
    $ui .= '</div>';
    $ui .= '</li>';

    return $ui;
}


function view_i_list($i, $is_next, $recipient_e, $prefix_statement = null){

    //If no list just return the next step:
    if(!count($is_next)){
        return false;
    }

    //List children so they know what's ahead:
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $common_prefix = i_calc_common_prefix($is_next, 'i__title');


    echo '<div class="pull-left headline">'.( strlen($prefix_statement) ? '<span class="icon-block">&nbsp;</span>'.$prefix_statement : '<span class="icon-block">&nbsp;</span>UP NEXT:'.( $common_prefix ? ' '.$common_prefix : '' ) ).'</div>';

    //Toogle for extra info:
    echo '<div class="pull-right inline-block" style="margin:0 0 -28px 0;"><a href="javascript:void(0);" onclick="$(\'.handler_13509\').toggleClass(\'hidden\');" class="icon-block grey" data-toggle="tooltip" data-placement="top" title="'.$e___11035[13509]['m_name'].'">'.$e___11035[13509]['m_icon'].'</a></div>';

    echo '<div class="doclear">&nbsp;</div>';


    echo '<div class="list-group">';
    foreach($is_next as $key => $next_i){
        echo view_i_x($next_i, $common_prefix);
    }
    echo '</div>';
    echo '<div class="doclear">&nbsp;</div>';

}


function view_i_note_mix($x__type, $i_notes){

    $CI =& get_instance();
    $e___4485 = $CI->config->item('e___4485'); //IDEA NOTES
    $handles_uploads = (in_array($x__type, $CI->config->item('n___12359')));
    $handles_url = (in_array($x__type, $CI->config->item('n___7551')) || in_array($x__type, $CI->config->item('n___4986')));
    $session_e = superpower_assigned();
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
        $ui .= view_i_notes($i_notes, ($i_notes['x__miner']==$session_e['e__id']));
    }

    //ADD NEW:
    if(!in_array($x__type, $CI->config->item('n___12677'))){
        $ui .= '<div class="list-group-item itemidea space-left add_notes_' . $x__type .'">';
        $ui .= '<div class="add_notes_form">';
        $ui .= '<form class="box box' . $x__type . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">';      //Used for dropping files



        $ui .= '<textarea onkeyup="i_note_count_new('.$x__type.')" class="form-control msg note-textarea algolia_search new-note input_note_'.$x__type.'" note-type-id="' . $x__type . '" id="x__message' . $x__type . '" placeholder="WRITE'.( $handles_url ? ', PASTE URL' : '' ).( $handles_uploads ? ', DROP FILE' : '' ).'" style="margin-top:6px;"></textarea>';



        $ui .= '<table class="table table-condensed"><tr>';


        //Save button:
        $ui .= '<td style="width:85px; padding: 10px 0 0 0;"><a href="javascript:i_note_text('.$x__type.');" class="btn btn-i save_notes_'.$x__type.'"><i class="fas fa-plus"></i></a></td>';


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

    $session_e = superpower_assigned($superpower_e__id);

    if(!$session_e){
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

function view_i_cover($i, $show_editor, $x_mode = true){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    $e___13369 = $CI->config->item('e___13369'); //IDEA COVER UI
    $e___12467 = $CI->config->item('e___12467'); //MENCH COINS

    $recipient_e = superpower_assigned();
    $i_stats = i_stats($i['i__metadata']);

    $ui  = '<a href="'.( $x_mode ? '/'.$i['i__id'] : '/~'.$i['i__id'] ) . '" id="i_cover_'.$i['i__id'].'" '.( isset($i['x__id']) ? ' sort-x-id="'.$i['x__id'].'" ' : '' ).' class="cover-block '.( $show_editor ? ' home_sort ' : '' ).'">';

    $ui .= '<div class="cover-image">';
    if($recipient_e){

        $completion_rate = $CI->X_model->completion_progress($recipient_e['e__id'], $i);

        if($completion_rate['completion_percentage']>0){
            $ui .= '<div class="progress-bg-image" title="discover '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' Ideas ('.$completion_rate['completion_percentage'].'%)" data-toggle="tooltip" data-placement="bottom"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';
        }

    }

    $ui .= i_fetch_cover($i['i__id'], true);

    //TOP LEFT
    $ui .= '<span class="media-info top-left" data-toggle="tooltip" data-placement="bottom" title="'.$i_stats['i___13443'].' '.$e___12467[12273]['m_name'].' FROM '.$i_stats['e_count'].' '.$e___12467[12274]['m_name'].'">';

    //SOURCES:
    if(superpower_active(10939, true)){
        $ui .= $e___12467[12274]['m_icon'].'<span style="padding-left: 2px;">'.view_number($i_stats['e_count']).'</span><br />';
    }

    //IDEAS:
    $ui .= $e___12467[12273]['m_icon'].'<span style="padding-left: 2px;">'.view_number($i_stats['i___13443']).'</span><br />';

    //DISCOVERIES:
    if(superpower_active(12701, true)){
        $x_coins = x_coins_i(6255, $i['i__id']);
        if($x_coins > 0){
            $ui .= $e___12467[6255]['m_icon'].'<span style="padding-left: 2px;">'.view_number($x_coins).'</span>';
        }
    }


    $ui .= '</span>';

    //TOP RIGHT
    if($i_stats['i___13292']){
        $ui .= '<span class="media-info top-right" title="'.$e___13369[13292]['m_name'].'">'.view_time_hours($i_stats['i___13292']).'</span>';
    }

    //Search for Idea Image:
    if($show_editor){

        //SORT
        $ui .= '<span class="media-info click-info bottom-left x-sorter" title="'.$e___13369[13413]['m_name'].': '.$e___13369[13413]['m_desc'].'">'.$e___13369[13413]['m_icon'].'</span>';

        //IDEA STATUS?
        if(!$x_mode && !in_array($i['i__status'], $CI->config->item('n___7355'))){
            $ui .= '<span class="media-info bottom-center">'.view_cache(4737 /* Idea Status */, $i['i__status'], true, 'top').'</span>';
        }

        //REMOVE
        $ui .= '<span class="media-info click-info bottom-right x_remove" i__id="'.$i['i__id'].'" title="'.$e___13369[13414]['m_name'].'">'.$e___13369[13414]['m_icon'].'</span>';

    }
    $ui .= '</div>';


    //Title + Drafting?
    $ui .= '<b class="montserrat" style="font-size: 0.9em;">'.view_i_title($i).($i['x__sort'] < 1 ? '<div class="dorubik">⚠️ &nbsp;Newly Added</div>' : '').'</b>';

    $ui .= '</a>';

    return $ui;

}


function view_e_basic($e)
{
    $ui = '<div class="list-group-item no-side-padding">';
    $ui .= '<span class="icon-block">' . view_e__icon($e['e__icon']) . '</span>';

    $e__title = '<span '.( isset($e['x__message']) && strlen($e['x__message']) > 0 ? ' class="underdot" title="'.$e['x__message'].'" data-toggle="tooltip" data-placement="top" ' : '' ).'>'.$e['e__title'].'</span>';

    if(superpower_active(10939, true)){
        //Give Transaction:
        $ui .= '<a class="title-block title-no-right montserrat" href="/@'.$e['e__id'].'">'.$e__title.'</a>';
    } else {
        //Basic
        $ui .= '<span class="title-block title-no-right">'.$e__title.'</span>';
    }

    $ui .= '</div>';
    return $ui;
}


function view_e_tabs($tab_group, $e, $session_e, $miner_is_e){

    $CI =& get_instance();
    $superpower_10939 = superpower_active(10939, true);
    $superpower_13422 = superpower_active(13422, true); //Advance Sourcing
    $superpower_any = ( $session_e ? count($CI->session->userdata('session_superpowers_assigned')) : 0 );
    $e___12467 = $CI->config->item('e___12467'); //MENCH
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
    $previously_activated = false;

    $tab_nav = '';
    $tab_content = '';
    foreach($CI->config->item('e___'.$tab_group) as $x__type => $m) {

        //Don't show empty tabs:
        $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m_parents']);
        if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
            //Missing Superpower:
            continue;
        } elseif(in_array($x__type, $CI->config->item('n___13424')) && $e['e__id']==$session_e['e__id']){
            //SOURCE LAYOUT HIDE IF SOURCE:
            continue;
        } elseif(in_array($x__type, $CI->config->item('n___13425')) && $e['e__id']!=$session_e['e__id']){
            //SOURCE LAYOUT SHOW IF SOURCE:
            continue;
        }


        $counter = null;
        $focus_tab = null;

        //Is this a caret menu?
        if(in_array(11040 , $m['m_parents'])){

            $tab_nav .= view_caret($x__type, $m, $e['e__id']);
            continue;

        } elseif(in_array($x__type, $CI->config->item('n___6194'))){

            //SOURCE REFERENCE:
            $e_count_6194 = e_count_6194($e['e__id'], $x__type);
            $counter = ( isset($e_count_6194[$x__type]) ? $e_count_6194[$x__type] : 0 );
            if(!$counter){
                continue;
            }
            $focus_tab = '<div><span class="icon-block">&nbsp;</span>Source referenced as '.$m['m_icon'].' '.$m['m_name'].' '.number_format($counter, 0).' times.</div>';

        } elseif(in_array($x__type, $CI->config->item('n___12467'))){

            //MENCH COINS
            $counter = x_stats_count($x__type, $e['e__id']);
            if(!$counter){
                continue;
            }
            $focus_tab = x_stats_count($x__type, $e['e__id'], 1);

        } elseif($x__type==6225){

            //ACCOUNT SETTING
            $focus_tab = '<div class="accordion" id="MyAccountAccordion" style="margin-bottom:34px;">';

            //Display account fields ordered with their SOURCE LINKS:
            foreach($CI->config->item('e___6225') as $acc_e__id => $acc_detail) {

                //Do they have any assigned? Skip this section if not:
                if($acc_e__id == 10957 /* Superpowers */ && !$superpower_any){
                    continue;
                }

                //Print header:
                $focus_tab .= '<div class="card">
<div class="card-header" id="heading' . $acc_e__id . '">
<button class="btn btn-block" type="button" data-toggle="collapse" data-target="#openEn' . $acc_e__id . '" aria-expanded="false" aria-controls="openEn' . $acc_e__id . '">
  <span class="icon-block">' . $acc_detail['m_icon'] . '</span><b class="montserrat doupper ' . extract_icon_color($acc_detail['m_icon']) . '">' . $acc_detail['m_name'] . '</b><span class="pull-right icon-block"><i class="fas fa-chevron-down"></i></span>
</button>
</div>

<div class="doclear">&nbsp;</div>

<div id="openEn' . $acc_e__id . '" class="collapse" aria-labelledby="heading' . $acc_e__id . '" data-parent="#MyAccountAccordion">
<div class="card-body">';


                //Show description if any:
                $focus_tab .= (strlen($acc_detail['m_desc']) > 0 ? '<p>' . $acc_detail['m_desc'] . '</p>' : '');


                //Print account fields that are either Single Selectable or Multi Selectable:
                $is_multi_selectable = in_array(6122, $acc_detail['m_parents']);
                $is_single_selectable = in_array(6204, $acc_detail['m_parents']);

                if ($acc_e__id == 12289) {

                    $e__icon_parts = explode(' ',one_two_explode('class="', '"', $session_e['e__icon']));

                    $focus_tab .= '<div class="'.superpower_active(10939).'"><div class="doclear">&nbsp;</div><div class="btn-group avatar-type-group pull-right" role="group" style="margin:0 0 10px 0;">
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'far\')" class="btn btn-far '.( $e__icon_parts[0]=='far' ? ' active ' : '' ).'"><i class="far fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fad\')" class="btn btn-fad '.( $e__icon_parts[0]=='fad' ? ' active ' : '' ).'"><i class="fad fa-paw source"></i></a>
                  <a href="javascript:void(0)" onclick="account_update_avatar_type(\'fas\')" class="btn btn-fas '.( $e__icon_parts[0]=='fas' ? ' active ' : '' ).'"><i class="fas fa-paw source"></i></a>
                </div><div class="doclear">&nbsp;</div></div>';


                    //List avatars:
                    foreach($CI->config->item('e___12279') as $x__type3 => $m3) {

                        $avatar_icon_parts = explode(' ',one_two_explode('class="', '"', $m3['m_icon']));
                        $avatar_type_match = ($e__icon_parts[0] == $avatar_icon_parts[0]);
                        $superpower_actives3 = array_intersect($CI->config->item('n___10957'), $m3['m_parents']);

                        $focus_tab .= '<span class="'.( count($superpower_actives3) ? superpower_active(end($superpower_actives3)) : '' ).'">';
                        $focus_tab .= '<a href="javascript:void(0);" onclick="e_update_avatar(\'' . $avatar_icon_parts[0] . '\', \'' . $avatar_icon_parts[1] . '\')" icon-css="' . $avatar_icon_parts[1] . '" class="list-group-item itemsource avatar-item item-square avatar-type-'.$avatar_icon_parts[0].' avatar-name-'.$avatar_icon_parts[1].' ' .( $avatar_type_match ? '' : ' hidden ' ). ( $avatar_type_match && $e__icon_parts[1] == $avatar_icon_parts[1] ? ' active ' : '') . '"><div class="avatar-icon">' . $m3['m_icon'] . '</div></a>';
                        $focus_tab .= '</span>';

                    }

                } elseif ($acc_e__id == 10957 /* Superpowers */) {

                    if($superpower_any >= 2){
                        //Mass Toggle Option:
                        $focus_tab .= '<div class="btn-group pull-right" role="group" style="margin:0 0 10px 0;">
                  <a href="javascript:void(0)" onclick="account_toggle_all(1)" class="btn btn-far"><i class="fas fa-toggle-on"></i></a>
                  <a href="javascript:void(0)" onclick="account_toggle_all(0)" class="btn btn-fad"><i class="fas fa-toggle-off"></i></a>
                </div><div class="doclear">&nbsp;</div>';
                    }


                    //List avatars:
                    $focus_tab .= '<div class="list-group">';
                    foreach($CI->config->item('e___10957') as $superpower_e__id => $m3){

                        //What is the superpower requirement?
                        if(!superpower_assigned($superpower_e__id)){
                            continue;
                        }

                        $extract_icon_color = extract_icon_color($m3['m_icon']);
                        $focus_tab .= '<a class="list-group-item itemsetting btn-superpower superpower-frame-'.$superpower_e__id.' '.( in_array($superpower_e__id, $CI->session->userdata('session_superpowers_activated')) ? ' active ' : '' ).'" en-id="'.$superpower_e__id.'" href="javascript:void();" onclick="e_toggle_superpower('.$superpower_e__id.')"><span class="icon-block '.$extract_icon_color.'" title="Source @'.$superpower_e__id.'">'.$m3['m_icon'].'</span><b class="montserrat '.$extract_icon_color.'">'.$m3['m_name'].'</b> '.$m3['m_desc'].'</a>';

                    }
                    $focus_tab .= '</div>';

                } elseif ($acc_e__id == 3288 /* Email */) {

                    $miner_emails = $CI->X_model->fetch(array(
                        'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__down' => $session_e['e__id'],
                        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        'x__up' => 3288, //Mench Email
                    ));

                    $focus_tab .= '<span class="white-wrapper"><input type="email" id="e_email" class="form-control border dotransparent" value="' . (count($miner_emails) > 0 ? $miner_emails[0]['x__message'] : '') . '" placeholder="you@gmail.com" /></span>
                <a href="javascript:void(0)" onclick="e_update_email()" class="btn btn-e">Save</a>
                <span class="saving-account save_email"></span>';

                } elseif ($acc_e__id == 3286 /* Password */) {

                    $focus_tab .= '<span class="white-wrapper"><input type="password" id="input_password" class="form-control border dotransparent" data-lpignore="true" autocomplete="new-password" placeholder="New Password..." /></span>
                <a href="javascript:void(0)" onclick="e_update_password()" class="btn btn-e">Save</a>
                <span class="saving-account save_password"></span>';

                } elseif ($is_multi_selectable || $is_single_selectable) {

                    $focus_tab .= view_radio_es($acc_e__id, $session_e['e__id'], ($is_multi_selectable ? 1 : 0));

                }

                //Print footer:
                $focus_tab .= '<div class="doclear">&nbsp;</div>';
                $focus_tab .= '</div></div></div>';

            }

            $focus_tab .= '</div>'; //End of accordion

        } elseif($x__type==11030){ //SOURCE PROFILE

            //FETCH ALL PARENTS
            $e__profiles = $CI->X_model->fetch(array(
                'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__status IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
                'x__down' => $e['e__id'],
            ), array('x__up'), 0, 0, array('e__weight' => 'DESC'));

            $counter = count($e__profiles);
            if(!$counter && !$superpower_13422){
                continue;
            }

            $focus_tab .= '<div id="list-parent" class="list-group ">';
            foreach($e__profiles as $e_profile) {
                $focus_tab .= view_e($e_profile,true, null, true, ($miner_is_e || ($session_e && ($session_e['e__id']==$e_profile['x__miner']))));
            }

            //Input to add new parents:
            $focus_tab .= '<div id="new-parent" class="list-group-item list-adder itemsource no-side-padding '.superpower_active(13422).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$e___12467[12274]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"
                           placeholder="SOURCE TITLE/URL">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';

            $focus_tab .= '</div>';

        } elseif($x__type==11029){

            //SOURCE PORTFOLIO
            $e__portfolio_count = $CI->X_model->fetch(array(
                'x__up' => $e['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                'e__status IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');
            $counter = $e__portfolio_count[0]['totals'];
            $e__portfolios = array(); //Fetch some


            if(!$counter && !$superpower_10939){
                continue;
            }

            if($counter){

                //Determine how to order:
                if($counter > config_var(11064)){
                    $order_columns = array('e__weight' => 'DESC');
                } else {
                    $order_columns = array('x__sort' => 'ASC', 'e__title' => 'ASC');
                }

                //Fetch Portfolios
                $e__portfolios = $CI->X_model->fetch(array(
                    'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                    'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                    'e__status IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
                    'x__up' => $e['e__id'],
                ), array('x__down'), config_var(11064), 0, $order_columns);

            }


            //SOURCE MASS EDITOR
            if($superpower_13422){

                //Mass Editor:
                $dropdown_options = '';
                $input_options = '';
                $editor_counter = 0;

                foreach($CI->config->item('e___4997') as $action_e__id => $e_list_action) {


                    $editor_counter++;
                    $dropdown_options .= '<option value="' . $action_e__id . '">' .$e_list_action['m_name'] . '</option>';
                    $is_upper = ( in_array($action_e__id, $CI->config->item('n___12577') /* SOURCE UPDATER UPPERCASE */) ? ' montserrat doupper ' : false );


                    //Start with the input wrapper:
                    $input_options .= '<span id="mass_id_'.$action_e__id.'" title="'.$e_list_action['m_desc'].'" class="inline-block '. ( $editor_counter > 1 ? ' hidden ' : '' ) .' mass_action_item">';




                    if(in_array($action_e__id, array(5000, 5001, 10625))){

                        //String Find and Replace:

                        //Find:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'" placeholder="Search" class="form-control border '.$is_upper.'">';

                        //Replace:
                        $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'" placeholder="Replace" class="form-control border '.$is_upper.'">';


                    } elseif(in_array($action_e__id, array(5981, 12928, 12930, 5982, 13441))){

                        //Miner search box:

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="Search sources..." class="form-control algolia_search e_text_search border '.$is_upper.'">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" placeholder="Search Source" />';


                    } elseif($action_e__id == 11956){

                        //IF HAS THIS
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="IF THIS SOURCE..." class="form-control algolia_search e_text_search border '.$is_upper.'">';

                        //ADD THIS
                        $input_options .= '<input type="text" name="mass_value2_'.$action_e__id.'"  placeholder="ADD THIS SOURCE..." class="form-control algolia_search e_text_search border '.$is_upper.'">';


                    } elseif($action_e__id == 5003){

                        //Miner Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($CI->config->item('e___6177') /* Source Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($CI->config->item('e___6177') /* Source Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } elseif($action_e__id == 5865){

                        //Transaction Status update:

                        //Find:
                        $input_options .= '<select name="mass_value1_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="*">Update All Statuses</option>';
                        foreach($CI->config->item('e___6186') /* Transaction Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Update All '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';

                        //Replace:
                        $input_options .= '<select name="mass_value2_'.$action_e__id.'" class="form-control border">';
                        $input_options .= '<option value="">Set New Status...</option>';
                        foreach($CI->config->item('e___6186') /* Transaction Status */ as $x__type3 => $m3){
                            $input_options .= '<option value="'.$x__type3.'">Set to '.$m3['m_name'].'</option>';
                        }
                        $input_options .= '</select>';


                    } else {

                        //String command:
                        $input_options .= '<input type="text" name="mass_value1_'.$action_e__id.'"  placeholder="String..." class="form-control border '.$is_upper.'">';

                        //We don't need the second value field here:
                        $input_options .= '<input type="hidden" name="mass_value2_'.$action_e__id.'" value="" />';

                    }

                    $input_options .= '</span>';

                }


                $focus_tab .= '<div class="pull-right grey" style="margin:-35px 34px 0 0;">'.( superpower_active(13422, true) && sources_currently_sorted($e['e__id']) ? '<span class="sort_reset hidden icon-block" title="'.$e___11035[13007]['m_name'].'" data-toggle="tooltip" data-placement="top"><a href="javascript:void(0);" onclick="e_sort_reset()">'.$e___11035[13007]['m_icon'].'</a></span>' : '').'<a href="javascript:void(0);" onclick="$(\'.e_editor\').toggleClass(\'hidden\');" title="'.$e___11035[4997]['m_name'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[4997]['m_icon'].'</a></div>';



                $focus_tab .= '<div class="doclear">&nbsp;</div>';
                $focus_tab .= '<div class="e_editor hidden">';
                $focus_tab .= '<form class="mass_modify" method="POST" action="" style="width: 100% !important; margin-left: 33px;">';
                $focus_tab .= '<div class="inline-box">';

                //Drop Down
                $focus_tab .= '<select class="form-control border" name="mass_action_e__id" id="set_mass_action">';
                $focus_tab .= $dropdown_options;
                $focus_tab .= '</select>';

                $focus_tab .= $input_options;

                $focus_tab .= '<div><input type="submit" value="APPLY" class="btn btn-e inline-block"></div>';

                $focus_tab .= '</div>';
                $focus_tab .= '</form>';

                //Also add invisible child IDs for quick copy/pasting:
                $focus_tab .= '<div style="color:transparent;" class="hideIfEmpty">';
                foreach($e__portfolios as $e_portfolio) {
                    $focus_tab .= $e_portfolio['e__id'].',';
                }
                $focus_tab .= '</div>';

                $focus_tab .= '</div>';







                //Source Status Filters:
                if(superpower_active(12701, true)){

                    $e_count = $CI->E_model->child_count($e['e__id'], $CI->config->item('n___7358') /* ACTIVE */);
                    $child_e_filters = $CI->X_model->fetch(array(
                        'x__up' => $e['e__id'],
                        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                        'e__status IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
                    ), array('x__down'), 0, 0, array('e__status' => 'ASC'), 'COUNT(e__id) as totals, e__status', 'e__status');

                    //Only show filtering UI if we find child sources with different Status (Otherwise no need to filter):
                    if (count($child_e_filters) > 0 && $child_e_filters[0]['totals'] < $e_count) {

                        //Load status definitions:
                        $e___6177 = $CI->config->item('e___6177'); //Source Status

                        //Add 2nd Navigation to UI
                        $focus_tab .= '<div class="nav nav-pills nav-sm">';

                        //Show fixed All button:
                        $focus_tab .= '<li class="nav-item"><a href="#" onclick="e_filter_status(-1)" class="nav-x en-status-filter active en-status--1" data-toggle="tooltip" data-placement="top" title="View all sources"><i class="fas fa-asterisk source"></i><span class="source">&nbsp;' . $e_count . '</span><span class="show-max source">&nbsp;TOTAL</span></a></li>';

                        //Show each specific filter based on DB counts:
                        foreach($child_e_filters as $c_c) {
                            $st = $e___6177[$c_c['e__status']];
                            $extract_icon_color = extract_icon_color($st['m_icon']);
                            $focus_tab .= '<li class="nav-item"><a href="#status-' . $c_c['e__status'] . '" onclick="e_filter_status(' . $c_c['e__status'] . ')" class="nav-x nav-link en-status-filter en-status-' . $c_c['e__status'] . '" data-toggle="tooltip" data-placement="top" title="' . $st['m_desc'] . '">' . $st['m_icon'] . '<span class="' . $extract_icon_color . '">&nbsp;' . $c_c['totals'] . '</span><span class="show-max '.$extract_icon_color.'">&nbsp;' . $st['m_name'] . '</span></a></li>';
                        }

                        $focus_tab .= '</div>';

                    }
                }
            }

            $focus_tab .= '<div id="e__portfolio" class="list-group">';
            $common_prefix = i_calc_common_prefix($e__portfolios, 'e__title');

            foreach($e__portfolios as $e_portfolio) {
                $focus_tab .= view_e($e_portfolio,false, null, true, ($miner_is_e || ($session_e && ($session_e['e__id']==$e_portfolio['x__miner']))), $common_prefix);
            }
            if ($counter > count($e__portfolios)) {
                $focus_tab .= view_e_load_more(1, config_var(11064), $counter);
            }

            //Input to add new child:
            $focus_tab .= '<div id="new_portfolio" current-count="'.$counter.'" class="list-group-item list-adder itemsource no-side-padding '.superpower_active(10939).'">
                <div class="input-group border">
                    <span class="input-group-addon addon-lean icon-adder"><span class="icon-block">'.$e___12467[12274]['m_icon'].'</span></span>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . config_var(6197) . '"
                           placeholder="SOURCE TITLE/URL">
                </div><div class="algolia_pad_search hidden pad_expand"></div></div>';

            $focus_tab .= '</div>';

        } elseif($x__type==13046){

            //Fetch Ideas First:
            $counter = 0; //Unless we find some:
            $i__ids = array();
            foreach($CI->X_model->fetch(array(
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $CI->config->item('n___12273')) . ')' => null, //IDEA COIN
                '(x__up = '.$e['e__id'].' OR x__down = '.$e['e__id'].')' => null,
            ), array(), config_var(11064), 0, array(), 'x__right') as $item) {
                array_push($i__ids, $item['x__right']);
            }

            //Also Show Related Sources:
            if(count($i__ids) > 0){

                $is_included = array($e['e__id']);
                foreach ($CI->X_model->fetch(array(
                    'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___12273')) . ')' => null, //IDEA COIN
                    'x__right IN (' . join(',', $i__ids) . ')' => null,
                    '(x__up > 0 OR x__down > 0)' => null, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
                ), array(), 0) as $fetched_e){

                    foreach(array('x__up','x__down') as $e_ref_field) {
                        if($fetched_e[$e_ref_field] > 0){

                            if(in_array($fetched_e[$e_ref_field], $is_included)){
                                continue;
                            }

                            $counter++;
                            array_push($is_included, $fetched_e[$e_ref_field]);

                            $ref_es = $CI->E_model->fetch(array(
                                'e__id' => $fetched_e[$e_ref_field],
                            ));

                            $focus_tab .= view_e($ref_es[0]);

                        }
                    }
                }

                if($counter > 0){
                    //Wrap list:
                    $focus_tab = '<div class="list-group">' . $focus_tab . '</div>';
                }

            }

        } elseif(in_array($x__type, $CI->config->item('n___4485'))){

            //IDEA NOTES
            $i_notes_filters = array(
                'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__status IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
                'x__type' => $x__type,
                'x__up' => $e['e__id'],
            );

            //COUNT ONLY
            $item_counters = $CI->X_model->fetch($i_notes_filters, array('x__right'), 0, 0, array(), 'COUNT(i__id) as totals');
            $counter = $item_counters[0]['totals'];

            //SHOW LASTEST 100
            if($counter>0){

                $i_notes_query = $CI->X_model->fetch($i_notes_filters, array('x__right'), config_var(11064), 0, array('i__weight' => 'DESC'));
                $focus_tab .= '<div class="list-group">';
                foreach($i_notes_query as $count => $i_notes) {
                    $focus_tab .= view_i($i_notes, 0, false, false, $i_notes['x__message'], null, false);
                }
                $focus_tab .= '</div>';

            } else {

                $focus_tab .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> No '.$m['m_name'].' yet</div>';

            }

        } elseif($x__type == 12969 /* MY DISCOVERIES */){

            $i_x_filters = array(
                'x__miner' => $e['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
                'i__status IN (' . join(',', $CI->config->item('n___7355')) . ')' => null, //PUBLIC
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            );
            $miner_x = $CI->X_model->fetch($i_x_filters, array('x__left'), 1, 0, array(), 'COUNT(x__id) as totals');
            $counter = $miner_x[0]['totals'];

            if($counter > 0){
                $i_x_query = $CI->X_model->fetch($i_x_filters, array('x__left'), config_var(11064), 0, array('x__sort' => 'ASC'));
                $focus_tab .= '<div class="list-group">';
                foreach($i_x_query as $count => $i_notes) {
                    $focus_tab .= view_i($i_notes);
                }
                $focus_tab .= '</div>';
            } else {
                $focus_tab .= '<div class="alert alert-warning" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle"></i></span> No '.$m['m_name'].' yet</div>';
            }

        }


        if(!$counter && (!in_array($x__type, $CI->config->item('n___12574')) || !$session_e)){
            //Hide since Zero without exception @12574:
            continue;
        }


        $default_active = !$previously_activated && $counter > 0 && in_array($x__type, $CI->config->item('n___12571'));
        if(!$previously_activated && $default_active){
            $previously_activated = true;
        }

        $tab_nav .= '<li class="nav-item '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'"><a class="nav-x tab-nav-'.$tab_group.' tab-head-'.$x__type.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m_icon']).'" href="javascript:void(0);" onclick="loadtab('.$tab_group.','.$x__type.')">'.$m['m_icon'].( is_null($counter) ? '' : ' <span class="en-type-counter-'.$x__type.( in_array($x__type, $CI->config->item('n___13004')) ? superpower_active(13422) : '' ).'">'.view_number($counter).'</span>' ).'<span class="show-max-active">&nbsp;'.$m['m_name'].'</span></a></li>';


        $tab_content .= '<div class="tab-content tab-group-'.$tab_group.' tab-data-'.$x__type.( $default_active ? '' : ' hidden ' ).'">';
        $tab_content .= $focus_tab;
        $tab_content .= '</div>';

    }

    if($tab_nav){

        echo '<ul class="nav nav-tabs nav-sm">';
        echo $tab_nav;
        echo '</ul>';

        //Show All Tab Content:
        echo $tab_content;

    }

}



function view_e($e, $is_parent = false, $extra_class = null, $control_enabled = false, $miner_is_e = false, $common_prefix = null)
{

    $CI =& get_instance();
    $session_e = superpower_assigned();
    $e___6177 = $CI->config->item('e___6177'); //Source Status
    $e___4592 = $CI->config->item('e___4592');
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status

    $x__id = (isset($e['x__id']) ? $e['x__id'] : 0);
    $is_x_e = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___4592')));
    $is_x_progress = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___12227')));
    $is_e_only = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___7551')));
    $inline_editing = $control_enabled && superpower_active(13402, true);
    $superpower_10939 = superpower_active(10939, true);
    $superpower_12706 = superpower_active(12706, true);

    $e__profiles = $CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__down' => $e['e__id'], //This child source
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__status IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__up'), 0, 0, array('e__weight' => 'DESC'));

    $e__portfolio_count = $CI->X_model->fetch(array(
        'x__up' => $e['e__id'],
        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__status IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__down'), 0, 0, array(), 'COUNT(e__id) as totals');

    $is_public = in_array($e['e__status'], $CI->config->item('n___7357'));
    $is_x_published = ( !$x__id || in_array($e['x__status'], $CI->config->item('n___7359')));
    $is_hidden = filter_array($e__profiles, 'e__id', '4755') || in_array($e['e__id'], $CI->config->item('n___4755'));

    if(!$session_e && (!$is_public || !$is_x_published)){
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
        $box_items_list .= '<span class="inline-block e__status_' . $e['e__id'].'"><span data-toggle="tooltip" data-placement="right" title="'.$e___6177[$e['e__status']]['m_name'].' @'.$e['e__status'].'">' . $e___6177[$e['e__status']]['m_icon'] . '</span>&nbsp;</span>';
    }

    //DISCOVER STATUS
    if($x__id){
        if(!$is_x_published){
            $box_items_list .= '<span class="inline-block x__status_' . $x__id .'"><span data-toggle="tooltip" data-placement="right" title="'.$e___6186[$e['x__status']]['m_name'].' @'.$e['x__status'].'">' . $e___6186[$e['x__status']]['m_icon'] . '</span>&nbsp;</span>';
        }
    }



    //PORTFOLIO COUNT (SYNC WITH NEXT IDEA COUNT)
    if($superpower_12706){
        $child_counter = '';
        if($superpower_10939){
            if($e__portfolio_count[0]['totals'] > 0){
                $child_counter .= '<span class="pull-right" '.( $inline_editing ? ' style="margin-top: -19px;" ' : '' ).'><span class="icon-block doright montserrat source" title="'.number_format($e__portfolio_count[0]['totals'], 0).' PORTFOLIO SOURCES">'.view_number($e__portfolio_count[0]['totals']).'</span></span>';
                $child_counter .= '<div class="doclear">&nbsp;</div>';
            }
        }
    }


    //ROW
    $ui = '<div class="list-group-item no-side-padding itemsource en-item object_saved saved_e_'.$e['e__id'].' e__id_' . $e['e__id'] . ( $x__id > 0 ? ' tr_' . $e['x__id'].' ' : '' ) . ( $is_parent ? ' parent-e ' : '' ) . ' '. $extra_class  . '" e-id="' . $e['e__id'] . '" en-status="' . $e['e__status'] . '" x__id="'.$x__id.'" x-status="'.( $x__id ? $e['x__status'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '">';


    $ui .= '<table class="table table-sm" style="background-color: transparent !important; margin-bottom: 0;"><tr>';


    //SOURCE
    $ui .= '<td class="MENCHcolumn1">';

    $e_url = ( $is_x_progress ? '/'.$CI->uri->segment(1).'?focus__e='.$e['e__id'] : '/@'.$e['e__id'] );

    //SOURCE ICON
    $ui .= '<a href="'.$e_url.'" '.( $is_x_e ? ' title="TRANSACTION ID '.$e['x__id'].' TYPE @'.$e['x__type'].' SORT '.$e['x__sort'].' WEIGHT '.$e['e__weight'].'" ' : '' ).'><span class="icon-block e_ui_icon_' . $e['e__id'] . ' e__icon_'.$e['e__id'].'" en-is-set="'.( strlen($e['e__icon']) > 0 ? 1 : 0 ).'">' . view_e__icon($e['e__icon']) . '</span></a>';


    //SOURCE TOOLBAR?
    if($inline_editing){

        $ui .= view_input_text(6197, $e['e__title'], $e['e__id'], $miner_is_e, 0, false, null, extract_icon_color($e['e__icon']));

        if($superpower_12706){
            $ui .= $child_counter;
            $ui .= '<div class="space-content">'.$box_items_list.'</div>';
        }

    } else {

        //SOURCE NAME
        $ui .= '<a href="'.$e_url.'" class="title-block title-no-right montserrat '.extract_icon_color($e['e__icon']).'">';
        $ui .= $box_items_list;
        $ui .= '<span class="text__6197_' . $e['e__id'] . '">'.( $common_prefix ? str_replace($common_prefix, '', $e['e__title']) : $e['e__title'] ).'</span>';
        if($e__portfolio_count[0]['totals'] > 0){
            $ui .= ' ['.view_number($e__portfolio_count[0]['totals']).']';
        }
        if($superpower_12706){
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

    if($control_enabled && $miner_is_e){
        if($is_x_e){

            //Sort
            if(!$is_parent && $superpower_10939){
                $ui .= '<span title="SORT"><i class="fas fa-bars hidden black"></i></span>';
            }

            //Manage source transaction:
            $ui .= '<span class="'.superpower_active(13422).'"><a href="javascript:void(0);" onclick="e_modify_load(' . $e['e__id'] . ',' . $x__id . ')"><i class="fas fa-pen-square black"></i></a></span>';


        } elseif($is_e_only){

            //Allow to remove:
            $ui .= '<span><a href="javascript:void(0);" onclick="e_only_remove(' . $x__id . ', '.$e['x__type'].')"><i class="fas fa-times black"></i></a></span>';

        }
    }

    $ui .= '</span>';
    $ui .= '</div>';
    $ui .= '</div>';

    $ui .= view_coins_count_e(0, $e['e__id']);
    $ui .= '</td>';




    //DISCOVER
    $ui .= '<td class="MENCHcolumn3 discover">';
    $ui .= view_coins_count_x(0, $e['e__id']);
    $ui .= '</td>';




    $ui .= '</tr></table>';



    if($superpower_12706){
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
        if($is_x_e){

            $ui .= '<span class="message_content paddingup x__message hideIfEmpty x__message_' . $x__id . '">' . view_x__message($e['x__message'] , $e['x__type']) . '</span>';

            //For JS editing only (HACK):
            $ui .= '<div class="x__message_val_' . $x__id . ' hidden overflowhide">' . $e['x__message'] . '</div>';

        } elseif($is_x_progress && strlen($e['x__message'])){

            //DISCOVER PROGRESS
            $ui .= '<div class="message_content paddingup">';
            $ui .= $CI->X_model->message_send($e['x__message']);
            $ui .= '</div>';

        }
    }





    $ui .= '</div>';

    return $ui;

}


function view_input_text($cache_e__id, $current_value, $object__id, $e_owns_i, $tabindex = 0, $extra_large = false, $e__icon = null, $append_css = null){

    $CI =& get_instance();
    $e___12112 = $CI->config->item('e___12112');
    $current_value = htmlentities($current_value);

    //Define element attributes:
    $attributes = ( $e_owns_i ? '' : 'disabled' ).' tabindex="'.$tabindex.'" old-value="'.$current_value.'" class="form-control dotransparent montserrat inline-block x_set_text text__'.$cache_e__id.'_'.$object__id.' texttype_'.($extra_large?'_lg':'_sm').' text_e_'.$cache_e__id.' '.$append_css.'" cache_e__id="'.$cache_e__id.'" object__id="'.$object__id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $focus_element = '<textarea '.( !strlen($append_css) ? ' style="color:#000000 !important;" ' : '' ).' onkeyup="view_input_text_count('.$cache_e__id.','.$object__id.')" placeholder="'.$e___12112[$cache_e__id]['m_name'].'" '.$attributes.'>'.$current_value.'</textarea>';
        $character_counter = '<div class="title_counter title_counter_'.$cache_e__id.'_'.$object__id.' hidden grey montserrat doupper" style="text-align: right;"><span id="current_count_'.$cache_e__id.'_'.$object__id.'">0</span>/'.config_var($cache_e__id).' CHARACTERS</div>';
        $icon = '<span class="icon-block title-icon">'.( $e__icon ? $e__icon : $e___12112[12273]['m_icon'] ).'</span>';

    } else {

        $focus_element = '<input type="text" placeholder="__" value="'.$current_value.'" '.$attributes.' />';
        $character_counter = ''; //None
        if(in_array($cache_e__id, $CI->config->item('n___12420'))){ //IDEA TEXT INPUT SHOW ICON
            $icon = '<span class="icon-block">'.( $e__icon ? $e__icon : $e___12112[$cache_e__id]['m_icon'] ).'</span>';
        } else {
            $icon = $e__icon;
        }

    }

    return '<span class="span__'.$cache_e__id.' '.( !$e_owns_i ? 'edit-locked' : '' ).'">'.$icon.$focus_element.'</span>'.$character_counter;
}




function view_input_dropdown($cache_e__id, $selected_e__id, $btn_class, $e_owns_i = true, $show_full_name = true, $i__id = 0, $x__id = 0){

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

    $ui .= '<button type="button" '.( $e_owns_i ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' '.$btn_class.'" id="dropdownMenuButton'.$cache_e__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked  '.$btn_class.'"' ).' >';

    $ui .= '<span class="icon-block">' .$e___this[$selected_e__id]['m_icon'].'</span><span class="show-max">'.( $show_full_name ?  $e___this[$selected_e__id]['m_name'] : '' ).'</span>';

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$cache_e__id.'">';

    foreach($e___this as $e__id => $m) {

        $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m_parents']);
        $is_url_desc = ( substr($m['m_desc'], 0, 1)=='/' );

        //What type of URL?
        if($is_url_desc){

            //Basic transaction:
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

