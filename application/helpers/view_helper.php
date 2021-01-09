<?php

function view_e_load_more($x__type, $page, $limit, $list_e_count)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');

    $ui = '<div class="load-more css__title list-group-item itemsource no-left-padding"><a href="javascript:void(0);" onclick="e_load_page('.$x__type.',' . $page . ', 0)">';

    //Regular section:
    $max_e = (($page + 1) * $limit);
    $max_e = ($max_e > $list_e_count ? $list_e_count : $max_e);
    $ui .= '<span class="icon-block">'.$e___11035[14538]['m__icon'].'</span><b class="css__title '.extract_icon_color($e___11035[14538]['m__icon']).'">'.$e___11035[14538]['m__title'].'</b>';
    $ui .= '</a></div>';

    return $ui;
}

function view_i_time($i_stats, $show_icon = false, $micro_sign = false){

    //TIME STATS
    if(!$i_stats['i___6161']){
        return null;
    }

    $has_any_diff = $i_stats['i___6169'] != $i_stats['i___6170'];
    $has_notable_diff = ($i_stats['i___6161'] * view_memory(6404,14579)) < $i_stats['i___6162'] && (($i_stats['i___6162']-$i_stats['i___6161']) >= 60 );
    $is_micro = $i_stats['i___6161']<60 && $i_stats['i___6162']<60;

    //Has Time
    $CI =& get_instance();
    $e___13544 = $CI->config->item('e___13544'); //IDEA TREE COUNT
    $ui = null;
    $full_sec = ' SEC.';
    $full_min = ' MIN';

    $ui .= '<div class="css__title doupper grey inline-block" data-toggle="tooltip" data-placement="top" title="'.
        $i_stats['i___6169'].
        ( $has_any_diff ? '-'.$i_stats['i___6170'] : '' ).
        ' '.$e___13544[12273]['m__title'].
        ' in '.
        ( $is_micro
            ? ( $has_notable_diff ? $i_stats['i___6161'].'-' : '' ).$i_stats['i___6162'].$full_sec
            : ( $has_notable_diff ? round_minutes($i_stats['i___6161']).'-' : '' ).round_minutes($i_stats['i___6162']).$full_min ).
        '">';

    if($is_micro){
        //SECONDS
        $ui .= ( $has_notable_diff && !$micro_sign ? $i_stats['i___6161'].'<span class="mid-range">-</span>'.$i_stats['i___6162'] : $i_stats['i___6162'] ).( $micro_sign ? '"' : $full_sec );
    } else {
        //MINUTES
        $ui .= ( $has_notable_diff && !$micro_sign ? round_minutes($i_stats['i___6161']).'<span class="mid-range">-</span>'.round_minutes($i_stats['i___6162']) : round_minutes($i_stats['i___6162']) ).( $micro_sign ? '\'' : $full_min );
    }

    if($show_icon){
        $ui .= '<span class="icon-block">'.$e___13544[13292]['m__icon'].'</span>';
    }
    $ui .= '</div>';

    return $ui;

}

function view_db_field($field_name){

    //Takes a database field name and returns a human-friendly version
    return ucwords(str_replace('i__', '', str_replace('e__', '', str_replace('x__', '', $field_name))));

}


function view_x__message($x__message, $x__type, $full_message = null, $is_discovery_mode = false)
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

    $CI =& get_instance();

    if ($x__type == 4256 /* Generic URL */) {

        return '<div class="block"><a href="' . $x__message . '" target="_blank" class="ignore-click"><span class="url_truncate">' . view_url_clean($x__message) . '</span></a></div>';

    } elseif ($x__type == 4257 /* Embed Widget URL? */) {

        return view_url_embed($x__message, $full_message);

    } elseif ($x__type == 4260 /* Image URL */) {

        return '<img '.( $is_discovery_mode ? ' src="' . $x__message . '" class="content-image" ' : ' data-src="' . $x__message . '" src="/img/mench.png" class="content-image lazyimage" ' ).' alt="IMAGE" />';

    } elseif ($x__type == 4259 /* Audio URL */) {

        return  '<audio controls src="' . $x__message . '">Your Browser Does Not Support Audio</audio>' ;

    } elseif ($x__type == 4258 /* Video URL */) {

        return  '<video width="100%" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="' . $x__message . '" type="video/mp4"></video>' ;

    } elseif ($x__type == 4261 /* File URL */) {

        $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
        return '<a href="' . $x__message . '" class="btn btn-idea" target="_blank" class="ignore-click">'.$e___11035[13573]['m__icon'].' '.$e___11035[13573]['m__title'].'</a>';

    } elseif(strlen($x__message) > 0) {

        return nl2br(htmlentities($x__message));

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
    $prefix__message = null;
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');

    if(is_https_url($url)){

        //See if $url has a valid embed video in it, and transform it if it does:
        $is_embed = (substr_count($url, 'youtube.com/embed/') == 1);

        if ((substr_count($url, 'youtube.com/watch') == 1) || substr_count($url, 'youtu.be/') == 1 || $is_embed) {

            $start_time = 0;
            $end_time = 0;
            $video_id = extract_youtube_id($url);

            if ($video_id) {

                //See if we have start & end time
                $string_references = extract_e_references($full_message);
                if($string_references['ref_time_found']){
                    $start_time = $string_references['ref_time_start'];
                    $end_time = $string_references['ref_time_end'];
                }

                //Set the Clean URL:
                $clean_url = 'https://www.youtube.com/watch?v=' . $video_id;


                //Header For Time
                if($end_time){
                    $seconds = $end_time-$start_time;
                    $embed_html_code .= '<div class="css__title subtle-line mini-grey"><span class="icon-block-xs">'.$e___11035[13292]['m__icon'].'</span>'.( $seconds<60 ? $seconds.' SEC.' : round_minutes($seconds).' MIN' ).' <span class="inline-block">FROM '.view_time_hours($start_time, true).' TO '.view_time_hours($end_time, true).'</span></div>';
                }

                $embed_html_code .= '<div class="media-content ignore-click"><div class="yt-container video-sorting" style="margin-top:5px;"><iframe id="youtubeplayer'.$video_id.'"  src="//www.youtube.com/embed/' . $video_id . '?wmode=opaque&theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_time . ($end_time ? '&end=' . $end_time : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div><div class="doclear">&nbsp;</div></div>';

            }

        } elseif (substr_count($url, 'vimeo.com/') == 1 && is_numeric(one_two_explode('vimeo.com/','?',$url))) {

            //Seems to be Vimeo:
            $video_id = trim(one_two_explode('vimeo.com/', '?', $url));

            //This should be an integer!
            if (intval($video_id) == $video_id) {
                $clean_url = 'https://vimeo.com/' . $video_id;
                $embed_html_code = '<div class="media-content ignore-click"><div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://user.vimeo.com/video/' . $video_id . '?title=0&byline=0" class="vm-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div><div class="doclear">&nbsp;</div></div>';
            }

        } elseif (substr_count($url, 'wistia.com/medias/') == 1) {

            //Seems to be Wistia:
            $video_id = trim(one_two_explode('wistia.com/medias/', '?', $url));
            $clean_url = trim(one_two_explode('', '?', $url));
            $embed_html_code = '<script src="https://fast.wistia.com/embed/medias/' . $video_id . '.jsonp" async></script><script src="https://fast.wistia.com/assets/external/E-v1.js" async></script><div class="wistia_responsive_padding video-sorting ignore-click" style="padding:56.25% 0 0 0;position:relative;"><div class="wistia_responsive_wrapper" style="height:100%;left:0;position:absolute;top:0;width:100%;"><div class="wistia_embed wistia_async_' . $video_id . ' seo=false videoFoam=true" style="height:100%;width:100%">&nbsp;</div></div></div>';

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

function view_i_title($i, $common_prefix = null, $is_cover = false){

    $CI =& get_instance();
    if(strlen($common_prefix) > 0){
        $i['i__title'] = trim(substr($i['i__title'], strlen($common_prefix)));
    }
    return '<span class="text__4736_'.$i['i__id'].' css__title '.($is_cover && count($CI->X_model->fetch(array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__right' => $i['i__id'],
            '(x__up = 14362 OR x__down = 14362)' => null,
        ))) ? ' hidden ' : '').'">'.htmlentities(trim($i['i__title'])).'</span>';
}


function view_i_note($x__type, $is_discovery_mode, $x, $note_e = false)
{

    /*
     *
     * A wrapper function that helps manage messages
     * by giving the message additional platform functions
     * such as editing and changing message type.
     *
     * */


    $CI =& get_instance();
    $member_e = superpower_unlocked();
    $e___4485 = $CI->config->item('e___4485'); //IDEA NOTES
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $e___11035 = $CI->config->item('e___11035');
    $color_code = trim(extract_icon_color($e___4485[$x__type]['m__icon']));
    $supports_emoji = (in_array($x__type, $CI->config->item('n___14038')));
    $referenced_ideas = (in_array($x__type, $CI->config->item('n___13550')));
    $editable_discovery = (in_array($x__type, $CI->config->item('n___14043')));


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item item'.$color_code.' is-msg note_sortable msg_e_type_' . $x['x__type'] . '" id="ul-nav-' . $x['x__id'] . '" x__id="' . $x['x__id'] . '">'; //title="'.$x['e__title'].' Posted On '.substr($x['x__time'], 0, 19).'" data-toggle="tooltip" data-placement="top"
    $ui .= '<div style="overflow:visible !important;">';

    if($editable_discovery && isset($x['e__id'])){
        //Show member:
        $ui .= view_e(14672, $x);
    }

    //Type & Delivery Method:
    $ui .= '<div class="text_message edit-off" id="msgbody_' . $x['x__id'] . '">';
    $ui .= $CI->X_model->message_view($x['x__message'], $is_discovery_mode, $member_e, $x['x__right']);
    $ui .= '</div>';

    //Editing menu:
    if($note_e){

        $ui .= '<div class="note-editor edit-off"><span class="show-on-hover">';

            //SORT NOTE
            if(in_array($x['x__type'], $CI->config->item('n___4603'))){
                $ui .= '<span title="'.$e___11035[13909]['m__title'].'" class="i_note_sorting">'.$e___11035[13909]['m__icon'].'</span>';
            }

            //MODIFY NOTE
            $ui .= '<span title="'.$e___11035[13574]['m__title'].'"><a href="javascript:void(0);" class="load_i_note_editor '.( $supports_emoji ? 'load_emoji_editor' : '' ).'" x__id="' . $x['x__id'] . '" onclick="load_i_note_editor(' . $x['x__id'] . ');">'.$e___11035[13574]['m__icon'].'</a></span>';

            //REMOVE NOTE
            $ui .= '<span title="'.$e___11035[13579]['m__title'].'"><a href="javascript:void(0);" onclick="i_remove_note(' . $x['x__id'] . ', '.$x['x__type'].')">'.$e___11035[13579]['m__icon'].'</a></span>';

        $ui .= '</span></div>';


        //Text editing:
        $ui .= '<textarea onkeyup="count_13574(' . $x['x__id'] . ')" name="x__message'.$x['x__id'].'" id="message_body_' . $x['x__id'] . '" class="edit-on hidden msg note-textarea edit-note algolia_search" x__id="'.$x['x__id'].'" placeholder="'.stripslashes($x['x__message']).'">' . $x['x__message'] . '</textarea>';


        //Update result & Show potential errors
        $ui .= '<div class="edit-updates hideIfEmpty"></div>';


        //Editing menu:
        $ui .= '<table class="table table-condensed edit-on hidden" style="margin:10px 41px 0;"><tr>';


        //SAVE
        $ui .= '<td class="table-btn"><a class="btn btn-'.$color_code.'" href="javascript:i_note_update_text(' . $x['x__id'] . ',' . $x['x__type'] . ');" title="'.$e___11035[14039]['m__title'].'">'.$e___11035[14039]['m__icon'].'</a></td>';

        //CANCEL
        $ui .= '<td class="table-btn first_btn"><a class="btn btn-compact btn-grey" title="'.$e___11035[13502]['m__title'].'" href="javascript:cancel_13574(' . $x['x__id'] . ');">'.$e___11035[13502]['m__icon'].'</a></td>';

        if($supports_emoji){
            //EMOJI
            $ui .= '<td class="table-btn emoji_edit hidden first_btn"><span class="btn btn-compact btn-grey" id="emoji_pick_id'.$x['x__id'].'" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__icon'].'</span></span></td>';
        }


        //TEXT COUNTER
        $ui .= '<td style="padding:10px 0 0 0;"><span id="NoteCounter' . $x['x__id'] . '" class="hidden some-text"><span id="charEditingNum' . $x['x__id'] . '">0</span>/' . view_memory(6404,4485) . ' CHARACTERS</span></td>';

        $ui .= '</tr></table>';

    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function view_e__icon($e__icon = null)
{
    //A simple function to display the Member Icon OR the default icon if not available:
    if (strlen($e__icon) > 0) {

        return $e__icon;

    } else {
        //Return default icon for sources:
        $CI =& get_instance();
        $e___2738 = $CI->config->item('e___2738'); //MENCH
        return $e___2738[12274]['m__icon'];
    }
}


function view_number($number)
{

    if(intval($number) < 1){
        return false;
    }

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


function view_x($x, $is_x__reference = false)
{

    $CI =& get_instance();
    $e___4593 = $CI->config->item('e___4593'); //Transaction Type
    $e___4341 = $CI->config->item('e___4341'); //Transaction Table
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $member_e = superpower_unlocked();
    $superpower_css_12701 = superpower_active(12701); //SUPERPOWER OF DISCOVERY GLASSES
    $add_e = $CI->E_model->fetch(array(
        'e__id' => $x['x__source'],
    ));




    //Display the item
    $ui = '<div class="x-list">';


    //ID
    $ui .= '<div class="simple-line"><a href="/-4341?x__id='.$x['x__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4367]['m__title'].'" class="mono-space"><span class="icon-block">'.$e___4341[4367]['m__icon']. '</span>'.$x['x__id'].'</a></div>';


    //SOURCE
    $ui .= '<div class="simple-line"><a href="/@'.$add_e[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4364]['m__title'].'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[4364]['m__icon']. '</span><span class="'.extract_icon_color($add_e[0]['e__icon']).'"><span class="icon-block">'.view_e__icon($add_e[0]['e__icon']) . '</span>' . $add_e[0]['e__title'] . '</span></a></div>';


    //HIDE PRIVATE INFO?
    if(in_array($x['x__type'] , $CI->config->item('n___4755')) && (!$member_e || $x['x__source']!=$member_e['e__id']) && !superpower_active(12701, true) && $add_e[0]['e__id']!=14068){

        //Hide Information:
        $ui .= '<div class="simple-line"><span data-toggle="tooltip" class="css__title" data-placement="top" title="Details are kept private"><span class="icon-block"><i class="fal fa-eye-slash"></i></span>PRIVATE INFORMATION</span></div>';
        $ui .= '</div>'; //Premature close & return
        return $ui;

    } elseif(!isset($e___4593[$x['x__type']])){

        //We've probably have not yet updated php cache, set error:
        $e___4593[$x['x__type']] = array(
            'm__icon' => '<i class="fas fa-exclamation-circle"></i>',
            'm__title' => 'Transaction Type Not Synced in PHP Cache',
            'm__message' => '',
            'm__profile' => array(),
        );

    }


    //TIME
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $e___4341[4362]['m__title'].': '.$x['x__time'] . ' PST"><span class="icon-block">'.$e___4341[4362]['m__icon']. '</span>' . view_time_difference(strtotime($x['x__time'])) . ' Ago</span></div>';


    //STATUS
    $ui .= '<div class="simple-line '.$superpower_css_12701.'"><a href="/@'.$x['x__status'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[6186]['m__title'].( strlen($e___6186[$x['x__status']]['m__message']) ? ': '.$e___6186[$x['x__status']]['m__message'] : '' ).'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[6186]['m__icon']. '</span><span class="icon-block">'.$e___6186[$x['x__status']]['m__icon'].'</span><span class="'.extract_icon_color($e___6186[$x['x__status']]['m__icon']).'">'.$e___6186[$x['x__status']]['m__title'].'</span></a></div>';


    //TYPE
    $ui .= '<div class="simple-line"><a href="/@'.$x['x__type'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4593]['m__title'].( strlen($e___4593[$x['x__type']]['m__message']) ? ': '.$e___4593[$x['x__type']]['m__message'] : '' ).'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[4593]['m__icon']. '</span><span class="icon-block">'. $e___4593[$x['x__type']]['m__icon'] . '</span><span class="'.extract_icon_color($e___4593[$x['x__type']]['m__icon']).'">' . $e___4593[$x['x__type']]['m__title'] . '</span></a></div>';


    //Order
    if($x['x__spectrum'] > 0){
        $ui .= '<div class="simple-line '.$superpower_css_12701.'"><span data-toggle="tooltip" data-placement="top" title="'.$e___4341[4370]['m__title']. '"><span class="icon-block">'.$e___4341[4370]['m__icon']. '</span>'.view_ordinal($x['x__spectrum']).'</span></div>';
    }


    //Metadata
    if(strlen($x['x__metadata']) > 0){
        $ui .= '<div class="simple-line '.$superpower_css_12701.'"><a href="/-12722?x__id=' . $x['x__id'] . '" target="_blank"><span class="icon-block">'.$e___4341[6103]['m__icon']. '</span><u>'.$e___4341[6103]['m__title']. '</u> <i class="far fa-external-link"></i></a></div>';
    }

    //Message
    if(strlen($x['x__message']) > 0 && $x['x__message']!='@'.$x['x__up']){
        $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4372]['m__title'].'"><span class="icon-block">'.$e___4341[4372]['m__icon'].'</span><div class="title-block x-msg">'.( strip_tags($x['x__message'])==$x['x__message'] || strlen(strip_tags($x['x__message']))<view_memory(6404,6197) ? $x['x__message'] : '<span class="hidden html_msg_'.$x['x__id'].'">'.$x['x__message'].'</span><a class="html_msg_'.$x['x__id'].'" href="javascript:void(0);" onclick="$(\'.html_msg_'.$x['x__id'].'\').toggleClass(\'hidden\');"><u>View HTML Message</u></a>' ).'</div></div>';
    }


    //5x Relations:
    if(!$is_x__reference){

        $var_index = var_index();
        foreach($CI->config->item('e___10692') as $e__id => $m) {

            //Do we have this set?
            if(!array_key_exists($e__id, $var_index) || !intval($x[$var_index[$e__id]])){
                continue;
            }

            if(in_array(6160 , $m['m__profile'])){

                //SOURCE
                $es = $CI->E_model->fetch(array('e__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/@'.$es[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[$e__id]['m__icon']. '</span>'.'<span class="icon-block">'.view_e__icon($es[0]['e__icon']). '</span><span class="'.extract_icon_color($es[0]['e__icon']).'">'.$es[0]['e__title'].'</span></a></div>';

            } elseif(in_array(6202 , $m['m__profile'])){

                //IDEA
                $is = $CI->I_model->fetch(array('i__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/i/i_go/'.$is[0]['i__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[$e__id]['m__icon']. '</span><span class="icon-block">'.view_cache(4737 /* Idea Status */, $is[0]['i__type'], true, 'right', $is[0]['i__id']).'</span>'.view_i_title($is[0], null).'</a></div>';

            } elseif(in_array(4367 , $m['m__profile'])){

                //PARENT DISCOVER
                $xs = $CI->X_model->fetch(array('x__id' => $x[$var_index[$e__id]]));

                if(count($xs)){
                    $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'">'.$e___4341[$e__id]['m__icon']. '</span><div class="x-ref hidden x_msg_'.$x['x__id'].'">'.view_x($xs[0], true).'</div><a class="x_msg_'.$x['x__id'].'" href="javascript:void(0);" onclick="$(\'.x_msg_'.$x['x__id'].'\').toggleClass(\'hidden\');"><u>View Referenced Transaction</u></a></div>';
                }

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


function view_memory($parent, $child, $filed = 'm__message'){
    $CI =& get_instance();
    $memory_tree = $CI->config->item('e___'.$parent);
    return $memory_tree[$child][$filed];
}

function view_cache($parent, $e__id, $micro_status = true, $data_placement = 'top', $i__id = 0)
{

    /*
     *
     * UI for Platform Cache sources
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item('e___'.$parent);
    $cache = $config_array[$e__id];
    if (!$cache) {
        //Could not find matching item
        return false;
    }


    //We have two skins for displaying Status:
    if (is_null($data_placement)) {
        if($micro_status){
            return $cache['m__icon'];
        } else {
            return $cache['m__icon'].' '.$cache['m__title'];
        }
    } else {
        //data-toggle="tooltip" data-placement="' . $data_placement . '"
        return '<span class="'.( $micro_status ? 'cache_micro_'.$parent.'_'.$i__id : '' ).'" ' . ( $micro_status && !is_null($data_placement) ? ' title="' . ($micro_status ? $cache['m__title'] : '') . (strlen($cache['m__message']) > 0 ? ($micro_status ? ': ' : '') . $cache['m__message'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache['m__icon'] . ' ' . ($micro_status ? '' : $cache['m__title']) . '</span>';
    }
}





function view_mench_coins(){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $ui = '';

    //$ui .= '<div class="headline"><span class="icon-block">'.$e___11035[2738]['m__icon'].'</span>'.$e___11035[2738]['m__title'].'</div>';
    $ui .= '<div class="row margin-top-down">';
    foreach($CI->config->item('e___2738') as $e__id => $m) {
        $count = count_unique_coins($e__id);
        $ui .= '<div class="col-4" style="text-align: center"><span class="css__title '.extract_icon_color($m['m__icon']).'" title="'.number_format($count, 0).' '.$m['m__title'].'" data-toggle="tooltip" data-placement="top">'.$m['m__icon'].'&nbsp;'.view_number($count).' <span class="block-if-mini">'.$m['m__title'].'</span></span></div>';
    }
    $ui .= '</div>';

    return $ui;
}




function view_coins_e($x__type, $e__id, $page_num = 0, $append_coin_icon = true, $i_exclude = array()){

    /*
     *
     * Loads Source MENCH
     *
     * */

    $CI =& get_instance();

    if($x__type==12274){

        //SOURCES
        $limit = view_memory(6404,11064);
        $order_columns = array('x__spectrum' => 'ASC', 'e__title' => 'ASC');
        $join_objects = array('x__down');
        $query_filters = array(
            'x__up' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
        );
        if(count($i_exclude)){
            $query_filters['e__id NOT IN (' . join(',', $i_exclude) . ')'] = null;
        }

    } elseif($x__type==12273){

        //IDEAS
        $limit = view_memory(6404,13958);
        $join_objects = array('x__right');
        $order_columns = array('i__spectrum' => 'DESC'); //BEST IDEAS
        $query_filters = array(
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            '(x__up = '.$e__id.' OR x__down = '.$e__id.')' => null,
        );
        if(count($i_exclude)){
            $query_filters['i__id NOT IN (' . join(',', $i_exclude) . ')'] = null;
        }

    } elseif($x__type==6255){

        //DISCOVERIES
        $join_objects = array('x__left');
        $limit = view_memory(6404,11064);

        if($page_num > 0){
            $order_columns = array('x__spectrum' => 'ASC');
            $query_filters = array(
                'x__source' => $e__id,
                'x__type IN (' . join(',', $CI->config->item('n___12969')) . ')' => null, //MY DISCOVERIES
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $CI->config->item('n___7355')) . ')' => null, //PUBLIC
            );
        } else {
            $order_columns = array('x__id' => 'DESC'); //LATEST DISCOVERIES
            $query_filters = array(
                'x__source' => $e__id,
                'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVER COIN
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'i__type IN (' . join(',', $CI->config->item('n___7355')) . ')' => null, //PUBLIC
            );
        }

        if(count($i_exclude)){
            $query_filters['i__id NOT IN (' . join(',', $i_exclude) . ')'] = null;
        }

    }

    //Return Results:
    if($page_num > 0){

        return $CI->X_model->fetch($query_filters, $join_objects, $limit, ($page_num-1)*$limit, $order_columns);

    } else {
        $count_query = $CI->X_model->fetch($query_filters, $join_objects, 1, 0, array(), 'COUNT(x__id) as totals');
        if($append_coin_icon){
            $e___2738 = $CI->config->item('e___2738'); //MENCH
            return ( $count_query[0]['totals'] > 0 ? '<span class="css__title '.extract_icon_color($e___2738[$x__type]['m__icon']).'" title="'.number_format($count_query[0]['totals'], 0).' '.$e___2738[$x__type]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___2738[$x__type]['m__icon'].'&nbsp;'.view_number($count_query[0]['totals']).'</span>' : null);
        } else {
            return intval($count_query[0]['totals']);
        }
    }

}



function view_coins_i($x__type, $i, $append_coin_icon = true){

    /*
     *
     * Loads Idea MENCH
     *
     * */

    $CI =& get_instance();

    if($x__type==12274){

        //SOURCES
        $query = $CI->X_model->fetch(array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__right' => $i['i__id'],
            '(x__up > 0 OR x__down > 0)' => null, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
        ), array(), 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $query[0]['totals'];

    } elseif($x__type==12273){

        //IDEAS
        $query = $CI->X_model->fetch(array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $CI->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i['i__id'],
        ), array('x__right'), 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $query[0]['totals'];

    } elseif($x__type==6255){

        $query_filters = array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVER COIN
            'x__left' => $i['i__id'],
        );

        if(isset($_GET['load__e'])){
            $query_filters['x__source'] = intval($_GET['load__e']);
        }

        $query = $CI->X_model->fetch($query_filters, array(), 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $query[0]['totals'];

    }

    //Return Results:
    if($append_coin_icon){
        $e___2738 = $CI->config->item('e___2738'); //MENCH
        return ( $count_query > 0 ? '<span title="'.$e___2738[$x__type]['m__title'].'" data-toggle="tooltip" data-placement="top" class="css__title '.extract_icon_color($e___2738[$x__type]['m__icon']).'">'.$e___2738[$x__type]['m__icon'].'&nbsp;'.view_number($count_query).'</span>' : null);
    } else {
        return intval($count_query);
    }

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


    $ui = null;
    foreach($CI->X_model->fetch(array(
        'x__left' => $i__id,
        'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
    ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC')) as $i_x){

        //Prep Metadata:
        $metadata = unserialize($i_x['x__metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->X_model->fetch(array(
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 4231, //IDEA NOTES Messages
            'x__right' => $i_x['i__id'],
        ), array(), 0, 0, array('x__spectrum' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Transaction Type: '.$e___4486[$i_x['x__type']]['m__title'].'">'. $e___4486[$i_x['x__type']]['m__icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Transaction Status: '.$e___6186[$i_x['x__status']]['m__title'].'">'. $e___6186[$i_x['x__status']]['m__icon'] . '</span>';

        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Type: '.$e___4737[$i_x['i__type']]['m__title'].'">'. $e___4737[$i_x['i__type']]['m__icon'] . '</span>';
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="Idea Status: '.$e___4737[$i_x['i__type']]['m__title'].'">'. $e___4737[$i_x['i__type']]['m__icon']. '</span>';
        $ui .= '<a href="?i__id='.$i_x['i__id'].'&depth_levels='.$original_depth_levels.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this idea"><u>' .   view_i_title($i_x, null) . '</u></a>';

        $ui .= ' [<span data-toggle="tooltip" data-placement="top" title="Completion Marks">'.( ($i_x['x__type'] == 4228 && in_array($previous_i__type , $CI->config->item('n___6193') /* OR Ideas */ )) || ($i_x['x__type'] == 4229) ? view_i_marks($i_x) : '' ).'</span>]';

        if(count($messages) > 0){
            $ui .= ' <a href="javascript:void(0);" onclick="$(\'.messages-'.$i_x['i__id'].'\').toggleClass(\'hidden\');"><i class="fas fa-comment"></i><b>' .  count($messages) . '</b></a>';
        }
        $ui .= '</div>';

        //Display Messages:
        $ui .= '<div class="messages-'.$i_x['i__id'].' hidden">';
        foreach($messages as $msg) {
            $ui .= '<div class="tip_bubble">';
            $ui .= $CI->X_model->message_view($msg['x__message'], false);
            $ui .= '</div>';
        }
        $ui .= '</div>';

        //Go Recursively down:
        $ui .=  view_i_scores_answer($i_x['i__id'], $depth_levels, $original_depth_levels, $i_x['i__type']);

    }

    //Return the wrapped UI if existed:
    return ($ui ? $ui : false);
}

function view_radio_e($parent_e__id, $child_e__id, $enable_mulitiselect, $show_max = 25){

    /*
     * Print UI for
     * */

    $CI =& get_instance();
    $count = 0;

    $ui = '<div class="list-group list-radio-select radio-'.$parent_e__id.'">';

    if(!count($CI->config->item('n___'.$parent_e__id))){
        return false;
    }

    $already_selected = array();
    foreach($CI->X_model->fetch(array(
        'x__up IN (' . join(',', $CI->config->item('n___'.$parent_e__id)) . ')' => null,
        'x__down' => $child_e__id,
        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    )) as $sel){
        array_push($already_selected, $sel['x__up']);
    }

    if(!count($already_selected) && in_array($parent_e__id, $CI->config->item('n___6204'))){
        //FIND DEFAULT:
        foreach($CI->config->item('e___'.$parent_e__id) as $e__id2 => $m2){
            if(in_array($e__id2, $CI->config->item('n___13889') /* ACCOUNT DEFAULTS */ )){
                $already_selected = array($e__id2);
                break;
            }
        }
    }

    foreach($CI->config->item('e___'.$parent_e__id) as $e__id => $m) {
        $ui .= '<a href="javascript:void(0);" onclick="e_radio('.$parent_e__id.','.$e__id.','.$enable_mulitiselect.')" class="item'.extract_icon_color($m['m__icon']).' list-group-item css__title itemsetting item-'.$e__id.' '.( $count>=$show_max ? 'extra-items-'.$parent_e__id.' hidden ' : '' ).( in_array($e__id, $already_selected) ? ' active ' : '' ). '"><span class="icon-block change-results">'.$m['m__icon'].'</span>'.$m['m__title'].'</a>';
        $count++;
    }


    //Did we have too many items?
    if($count>=$show_max){
        //Show "Show more" button
        $ui .= '<a href="javascript:void(0);" class="list-group-item itemsource itemsetting css__title extra-items-'.$parent_e__id.'" onclick="$(\'.extra-items-'.$parent_e__id.'\').toggleClass(\'hidden\')"><span class="icon-block"><i class="fas fa-search-plus"></i></span>Show '.($count-$show_max).' more</a>';
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


function view_i_icon($i){

    return '<span class="this_i__icon_'.$i['i__id'].'">'.view_cache(4737, $i['i__type'], true, 'right', $i['i__id']).'</span>';

}




function view_caret($e__id, $m, $s__id){
    //Display drop down menu:
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION

    $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m__profile']);
    $ui = '<li class="nav-item dropdown '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'" title="'.$m['m__title'].'">';
    $ui .= '<a class="nav-x dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"></a>';
    $ui .= '<div class="dropdown-menu">';
    foreach($CI->config->item('e___'.$e__id) as $e__id => $m2){
        $superpower_actives2 = array_intersect($CI->config->item('n___10957'), $m2['m__profile']);

        if($e__id==13007){
            $href = 'href="javascript:void(0);" onclick="e_sort_reset()"';
        } elseif($e__id==6415){
            $href = 'href="javascript:void(0);" onclick="x_reset_all()"';
        } else {
            $href = 'href="' . $m2['m__message'] . $s__id . '"';
        }

        $ui .= '<a '.$href.' class="dropdown-item css__title '.extract_icon_color($m2['m__icon']).' '.( count($superpower_actives2) ? superpower_active(end($superpower_actives2)) : '' ).'"><span class="icon-block">'.view_e__icon($m2['m__icon']).'</span> '.$m2['m__title'].'</a>';
    }
    $ui .= '</div>';
    $ui .= '</li>';

    return $ui;
}


function view_i_list($x__type, $top_i__id, $in_my_x, $i, $is_next, $member_e, $right_content = null){

    //If no list just return the next step:
    if(!count($is_next)){
        return false;
    }

    $CI =& get_instance();

    //List children so they know what's ahead:
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $common_prefix = i_calc_common_prefix($is_next, 'i__title');
    $ui = '';
    $ui .= '<div>';
    $ui .= '<div class="pull-left">';
    $ui .= '<div class="headline"><span class="icon-block">'.$e___11035[$x__type]['m__icon'].'</span>'.$e___11035[$x__type]['m__title'].'</div>';
    $ui .= '</div>';
    if($right_content){
        $ui .= '<div class="pull-right" style="text-align: right; padding:10px 0 0 0;">'.$right_content.'</div>';
    }
    $ui .= '</div>';
    $ui .= '<div class="doclear">&nbsp;</div>';

    $ui .= '<div class="row top-margin">';
    $found_next_discovery = false;
    foreach($is_next as $key => $next_i){
        $completion_rate = $CI->X_model->completion_progress($member_e['e__id'], $next_i);
        if($in_my_x && $x__type==12211 && !$found_next_discovery && $completion_rate['completion_percentage']<100){
            $found_next_discovery = true;
            $x__type_to_pass = 14455; //The Immediate Next
        } else {
            $x__type_to_pass = $x__type;
        }
        $ui .= view_i($x__type_to_pass, $top_i__id, $i, $next_i, $in_my_x, null, $member_e, $completion_rate);
    }
    $ui .= '</div>';
    $ui .= '<div class="doclear">&nbsp;</div>';

    return $ui;

}


function view_i_note_list($x__type, $is_discovery_mode, $i, $i_notes, $e_of_i, $show_empty_error = false){

    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');
    $e___4485 = $CI->config->item('e___4485'); //IDEA NOTES
    $supports_emoji = (in_array($x__type, $CI->config->item('n___14038')));
    $handles_uploads = (in_array($x__type, $CI->config->item('n___12359')));
    $handles_url = (in_array($x__type, $CI->config->item('n___7551')) || in_array($x__type, $CI->config->item('n___4986')));
    $member_e = superpower_unlocked();
    $ui = '';

    if($show_empty_error && !count($i_notes) && $e_of_i){
        $ui .= '<div class="no_notes_' . $x__type .'" style="margin:21px 0;">';
        $ui .= '<div class="msg alert alert-danger" role="alert"><span class="icon-block">&nbsp;</span>No '.ucwords(strtolower($e___4485[$x__type]['m__title'])).' yet</div>';
        $ui .= '</div>';
    }


    if(in_array($x__type, $CI->config->item('n___14311'))){

        //POWER EDITOR
        $tab_nav = '';
        $tab_content = '';
        foreach($CI->config->item('e___14418') as $x__type2 => $m) {

            $default_active = false;
            $tab_ui = '';

            //Is this a caret menu?
            if($x__type2==14468){

                $textarea_content = '';
                foreach($i_notes as $i_note) {
                    $textarea_content .= $i_note['x__message']."\n\n";
                }

                //WRITE
                $default_active = !strlen($textarea_content);


                $tab_ui .= '<div class="power-editor-'.$x__type.'">';
                $tab_ui .= '<textarea class="form-control msg note-textarea indifferent algolia_search new-note power_editor doabsolute emoji-input input_note_'.$x__type.'" note_type_id="' . $x__type . '" placeholder="DESCRIBE IDEA..." style="margin:0 82px 0 41px; width:calc(100% - 82px);">'.$textarea_content.'</textarea>';
                $tab_ui .= '<div id="current_text_'.$x__type.'" class="hidden">'.trim($textarea_content).'</div>';

                //Response result:
                $tab_ui .= '<div class="note_error_'.$x__type.' hideIfEmpty discover msg alert alert-danger indifferent" style="margin:8px 0;"></div>';


                //CONTROLLER
                $tab_ui .= '<div class="no-padding add_notes_' . $x__type .'">';
                $tab_ui .= '<div class="add_notes_form">';
                $tab_ui .= '<form class="box box' . $x__type . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">';
                $tab_ui .= '<table class="table table-condensed" style="margin-top: 10px;"><tr>';

                if($handles_uploads){

                    //UPLOAD
                    $tab_ui .= '<td class="table-btn first_btn indifferent">';
                    $tab_ui .= '<label class="hidden"></label>'; //To catch & store unwanted uploaded file name
                    $tab_ui .= '<label class="btn btn-grey btn-compact file_label_'.$x__type.'" for="fileIdeaType'.$x__type.'" title="'.$e___11035[13572]['m__title'].' '.$e___11035[13572]['m__message'].'"><span class="icon-block">'.$e___11035[13572]['m__icon'].'</span></label>';
                    $tab_ui .= '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType'.$x__type.'" />';
                    $tab_ui .= '</td>';

                    //GIF
                    $tab_ui .= '<td class="table-btn first_btn indifferent"><a class="btn btn-compact btn-grey" href="javascript:void(0);" onclick="gif_modal(' . $x__type . ')" title="'.$e___11035[14073]['m__title'].'"><span class="icon-block">'.$e___11035[14073]['m__icon'].'</span></a></td>';

                }

                if($supports_emoji){
                    //EMOJI
                    $tab_ui .= '<td class="table-btn first_btn indifferent"><span class="btn btn-compact btn-grey" id="emoji_pick_type'.$x__type.'" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__icon'].'</span></span></td>';
                }

                //SAVE Button:
                $tab_ui .= '<td class="table-btn first_btn indifferent save_button save_button_'.$x__type.' hidden"><a href="javascript:i_note_poweredit_save('.$x__type.');" class="btn btn-default save_notes_'.$x__type.'" style="width:104px;" title="Shortcut: Ctrl + Enter" data-toggle="tooltip" data-placement="bottom">'.$e___11035[14422]['m__icon'].' '.$e___11035[14422]['m__title'].'</a></td>';


                $tab_ui .= '<td style="padding:10px 0 0 0;">&nbsp;</td>';
                $tab_ui .= '</tr></table>';
                $tab_ui .= '</form>';
                $tab_ui .= '</div>';
                $tab_ui .= '</div>';
                $tab_ui .= '</div>';


            } elseif($x__type2==14420){

                if(!$default_active){
                    $default_active = true;
                }

                //PREVIEW
                $tab_ui .= '<div class="list-group '.( $e_of_i ? ' editor_preview ' : '' ).' editor_preview_'.$x__type.'">';
                foreach($i_notes as $i_note) {
                    $tab_ui .= $CI->X_model->message_view($i_note['x__message'], $is_discovery_mode, $member_e, $i['i__id']);
                }
                $tab_ui .= '</div>';

            }


            $tab_nav .= '<li class="nav-item"><a href="javascript:void(0);" onclick="loadtab(14418,'.$x__type2.');" class="nav-x tab-nav-14418 tab-head-'.$x__type2.' '.( $default_active ? ' active ' : '' ).extract_icon_color($m['m__icon']).'" title="'.$m['m__title'].( strlen($m['m__message']) ? ' '.$m['m__message'] : '' ).'" data-toggle="tooltip" data-placement="top">&nbsp;'.$m['m__icon'].'&nbsp;</a></li>';


            $tab_content .= '<div class="tab-content tab-group-14418 tab-data-'.$x__type2.' power-editor-'.$x__type.( $default_active ? '' : ' hidden ' ).'">';
            $tab_content .= $tab_ui;
            $tab_content .= '</div>';

        }


        $ui .= '<ul class="nav nav-tabs nav-sm hidden">'; //Toggled automatically via JS
        $ui .= $tab_nav;
        $ui .= '</ul>';

        //Show All Tab Content:
        $ui .= $tab_content;

    } else {

        //Show no-Message notifications for each message type:
        $ui .= '<div id="i_notes_list_'.$x__type.'" class="list-group">';

        //List current notes:
        foreach($i_notes as $i_note) {
            $ui .= view_i_note($x__type, $is_discovery_mode, $i_note, ($i_note['x__source']==$member_e['e__id'] || $e_of_i));
        }

        //ADD NEW:
        if(!in_array($x__type, $CI->config->item('n___12677')) && $e_of_i){

            $color_code = trim(extract_icon_color($e___4485[$x__type]['m__icon']));

            $ui .= '<div class="no-padding add_notes_' . $x__type .'">';
            $ui .= '<div class="add_notes_form">';
            $ui .= '<form class="box box' . $x__type . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">';

            $ui .= '<textarea onkeyup="i_note_count_new('.$x__type.')" class="form-control msg note-textarea regular_editor dotransparent algolia_search new-note '.( $supports_emoji ? 'emoji-input' : '' ).' input_note_'.$x__type.'" note_type_id="' . $x__type . '" style="margin-top: 10px;" placeholder="Write'.( $handles_url ? ' or Paste URL' : '' ).'"></textarea>';

            //Response result:
            $ui .= '<div class="note_error_'.$x__type.' hideIfEmpty discover msg alert alert-danger" style="margin:8px 0;"></div>';


            //CONTROLLER
            $ui .= '<table class="table table-condensed" style="margin-top: 10px;"><tr>';

            if($handles_uploads){

                //UPLOAD
                $ui .= '<td class="table-btn first_btn">';
                $ui .= '<label class="hidden"></label>'; //To catch & store unwanted uploaded file name
                $ui .= '<label class="btn btn-grey btn-compact file_label_'.$x__type.'" for="fileIdeaType'.$x__type.'" title="'.$e___11035[13572]['m__title'].' '.$e___11035[13572]['m__message'].'"><span class="icon-block">'.$e___11035[13572]['m__icon'].'</span></label>';
                $ui .= '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType'.$x__type.'" />';
                $ui .= '</td>';

                //GIF
                $ui .= '<td class="table-btn first_btn"><a class="btn btn-compact btn-grey" href="javascript:void(0);" onclick="gif_modal(' . $x__type . ')" title="'.$e___11035[14073]['m__title'].'"><span class="icon-block">'.$e___11035[14073]['m__icon'].'</span></a></td>';

            }

            if($supports_emoji){
                //EMOJI
                $ui .= '<td class="table-btn first_btn"><span class="btn btn-compact btn-grey" id="emoji_pick_type'.$x__type.'" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__icon'].'</span></span></td>';
            }

            //Add
            $ui .= '<td class="table-btn first_btn"><a href="javascript:i_note_add_text('.$x__type.');" class="btn btn-default save_notes_'.$x__type.'" style="width:104px;" data-toggle="tooltip" data-placement="bottom" title="Shortcut: Ctrl + Enter" data-toggle="tooltip" data-placement="bottom">'.$e___11035[14421]['m__icon'].' '.$e___11035[14421]['m__title'].'</a></td>';


            //File counter:
            $ui .= '<td style="padding:10px 0 0 0;"><span id="ideaNoteNewCount' . $x__type . '" class="hidden some-text"><span id="charNum' . $x__type . '">0</span>/' . view_memory(6404,4485).' CHARACTERS</span></td>';
            $ui .= '</tr></table>';
            $ui .= '</form>';
            $ui .= '</div>';
            $ui .= '</div>';
        }


        $ui .= '</div>';

    }

    return $ui;

}

function view_shuffle_message($e__id){
    $CI =& get_instance();
    $e___12687 = $CI->config->item('e___12687');
    $line_messages = explode("\n", $e___12687[$e__id]['m__message']);
    return $line_messages[rand(0, (count($line_messages) - 1))];
}

function view_e_settings($list_id, $show_accordion){

    $CI =& get_instance();
    $member_e = superpower_unlocked();
    $e___14010 = $CI->config->item('e___14010');
    $ui = null;
    if(!$member_e || !$CI->config->item('e___'.$list_id)){
        return $ui;
    }
    if($show_accordion){
        $ui .= '<div class="accordion" id="MyAccountAccordion'.$list_id.'">';
    }


    //Display account fields ordered with their SOURCE LINKS:
    foreach($CI->config->item('e___'.$list_id) as $acc_e__id => $acc_detail) {

        //Skip if missing superpower:
        $superpower_actives = array_intersect($CI->config->item('n___10957'), $acc_detail['m__profile']);
        //Print account fields that are either Single Selectable or Multi Selectable:
        $is_multi_selectable = in_array(6122, $acc_detail['m__profile']);
        $is_single_selectable = in_array(6204, $acc_detail['m__profile']);
        $tab_ui = null;

        //Append description if any:
        if(strlen($acc_detail['m__message']) > 0){
            $tab_ui .= '<div class="regtext" style="text-align: left; padding:0 0 21px 0;">' . $acc_detail['m__message'] . '</div>';
        }

        if ($acc_e__id == 12289) {

            $e__icon_parts = explode(' ',one_two_explode('class="', '"', $member_e['e__icon']));

            //List avatars:
            $tab_ui .= '<div class="row">';
            foreach($CI->config->item('e___12279') as $x__type3 => $m3) {

                $avatar_icon_parts = explode(' ',one_two_explode('class="', '"', $m3['m__icon']));
                $avatar_match = ($e__icon_parts[1] == $avatar_icon_parts[1]);
                $avatar_type_match = ($e__icon_parts[0] == $avatar_icon_parts[0]);
                $superpower_actives3 = array_intersect($CI->config->item('n___10957'), $m3['m__profile']);

                if(!$avatar_match && count($superpower_actives3) && !superpower_active(end($superpower_actives3), true)){
                    //Missing needed superpower, skip:
                    continue;
                }

                $tab_ui .= '<a href="javascript:void(0);" onclick="e_avatar(\'' . $avatar_icon_parts[0] . '\', \'' . $avatar_icon_parts[1] . '\')" icon-css="' . $avatar_icon_parts[1] . '" class="col-3 col-md-2 list-group-item avatar-item avatar-type-'.$avatar_icon_parts[0].' avatar-name-'.$avatar_icon_parts[1].' ' .( $avatar_type_match ? '' : ' hidden ' ). ( $avatar_type_match && $e__icon_parts[1] == $avatar_icon_parts[1] ? ' active ' : '') . '"><div class="avatar-icon">' . $m3['m__icon'] . '</div></a>';

            }
            $tab_ui .= '</div>';


            //Toggle Avatar Type
            $tab_ui .= '<div class="'.superpower_active(10939).'"><div class="doclear">&nbsp;</div><div class="btn-group avatar-type-group" role="group">';
            foreach($CI->config->item('e___13533') as $m3) {
                $tab_ui .= '<a href="javascript:void(0)" onclick="account_update_avatar_type(\''.$m3['m__message'].'\')" class="btn btn-'.$m3['m__message'].' '.( $e__icon_parts[0]==$m3['m__message'] ? ' active ' : '' ).'" title="'.$m3['m__title'].'">'.$m3['m__icon'].'</a>';
            }
            $tab_ui .= '</div><div class="doclear">&nbsp;</div></div>';


        } elseif ($acc_e__id == 10957 /* Superpowers */) {

            if(count($CI->session->userdata('session_superpowers_unlocked')) >= 2){
                //Mass Toggle Option:
                $tab_ui .= '<div class="btn-group pull-right" role="group" style="margin:0 0 10px 0;">
                  <a href="javascript:void(0)" onclick="account_toggle_all(1)" class="btn btn-far"><i class="fas fa-toggle-on"></i></a>
                  <a href="javascript:void(0)" onclick="account_toggle_all(0)" class="btn btn-fad"><i class="fas fa-toggle-off"></i></a>
                </div><div class="doclear">&nbsp;</div>';
            }

            //SUPERPOWERS
            $tab_ui .= '<div class="list-group">';
            foreach($CI->config->item('e___10957') as $superpower_e__id => $m3){

                $is_unlocked = in_array($superpower_e__id, $CI->session->userdata('session_superpowers_unlocked'));
                $public_link = in_array($superpower_e__id, $CI->config->item('n___6404'));
                $extract_icon_color = extract_icon_color($m3['m__icon']);
                $anchor = '<span class="icon-block main-icon '.$extract_icon_color.'" title="@'.$superpower_e__id.'">'.$m3['m__icon'].'</span><b class="css__title '.$extract_icon_color.'">'.$m3['m__title'].'</b><span class="superpower-message">'.$m3['m__message'].'</span>';

                if($is_unlocked){

                    //SUPERPOWERS UNLOCKED
                    $progress_type_id=14008;
                    $tab_ui .= '<a class="list-group-item itemsetting btn-superpower superpower-frame-'.$superpower_e__id.' '.( superpower_active($superpower_e__id, true) ? ' active ' : '' ).'" en-id="'.$superpower_e__id.'" href="javascript:void();" onclick="e_toggle_superpower('.$superpower_e__id.')"><span class="icon-block pull-right" title="'.$e___14010[$progress_type_id]['m__title'].'">'.$e___14010[$progress_type_id]['m__icon'].'</span>'.$anchor.'</a>';

                } elseif(!$is_unlocked && $public_link){

                    //SUPERPOWERS AVAILABLE
                    $progress_type_id=14011;
                    $tab_ui .= '<a class="list-group-item no-side-padding" href="'.view_memory(6404,$superpower_e__id).'"><span class="icon-block pull-right" title="'.$e___14010[$progress_type_id]['m__title'].'">'.$e___14010[$progress_type_id]['m__icon'].'</span>'.$anchor.'</a>';

                } elseif(!$is_unlocked && !$public_link){

                    //SUPERPOWERS UNAVAILABLE
                    $progress_type_id=14009;
                    $tab_ui .= '<a href="javascript:void();" onclick="alert(\'This superpower is locked & cannot be unlocked at this time. Start by unlocking other available superpowers.\')" class="list-group-item no-side-padding islocked grey"><span class="icon-block pull-right" title="'.$e___14010[$progress_type_id]['m__title'].'">'.$e___14010[$progress_type_id]['m__icon'].'</span>'.$anchor.'</a>';

                }

            }

            $tab_ui .= '</div>';

        } elseif ($acc_e__id == 13025 /* Full Name */) {

            $tab_ui .= '<span><input type="text" id="e_name" class="form-control border dotransparent doupper" value="' . $member_e['e__title'] . '" /></span>
                <a href="javascript:void(0)" onclick="e_name()" class="btn btn-source">Save</a>
                <span class="saving-account save_name"></span>';

        } elseif ($acc_e__id == 3288 /* Email */) {

            $u_emails = $CI->X_model->fetch(array(
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $member_e['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => 3288, //Mench Email
            ));

            $tab_ui .= '<span><input type="email" id="e_email" class="form-control border dotransparent" value="' . (count($u_emails) > 0 ? $u_emails[0]['x__message'] : '') . '" placeholder="you@gmail.com" /></span>
                <a href="javascript:void(0)" onclick="e_email()" class="btn btn-source">Save</a>
                <span class="saving-account save_email"></span>';

        } elseif ($acc_e__id == 3286 /* Password */) {

            $tab_ui .= '<span><input type="password" id="input_password" class="form-control border dotransparent" data-lpignore="true" autocomplete="new-password" placeholder="New Password..." /></span>
                <a href="javascript:void(0)" onclick="e_password()" class="btn btn-source">Save</a>
                <span class="saving-account save_password"></span>';

        } elseif ($is_multi_selectable || $is_single_selectable) {

            $tab_ui .= view_radio_e($acc_e__id, $member_e['e__id'], ($is_multi_selectable ? 1 : 0));

        }

        if($tab_ui){

            if($show_accordion){

                //Accordion header:
                $ui .= '<div class="card '.( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ).'">
<div class="card-header" id="heading' . $acc_e__id . '">
<button class="btn btn-block" type="button" data-toggle="collapse" data-target="#openEn' . $acc_e__id . '" aria-expanded="false" aria-controls="openEn' . $acc_e__id . '">
  <span class="icon-block">' . $acc_detail['m__icon'] . '</span><b class="css__title doupper ' . extract_icon_color($acc_detail['m__icon']) . '">' . $acc_detail['m__title'] . '</b><span class="pull-right icon-block"><i class="fas fa-chevron-down"></i></span>
</button>
</div>

<div class="doclear">&nbsp;</div>

<div id="openEn' . $acc_e__id . '" class="collapse" aria-labelledby="heading' . $acc_e__id . '" data-parent="#MyAccountAccordion'.$list_id.'">
<div class="card-body">';

                //TAB CONTENT
                $ui .= $tab_ui;

            } else {

                //Show Title only:
                $ui .= '<div class="headline top-margin"><span class="icon-block">'.$acc_detail['m__icon'].'</span>'.$acc_detail['m__title'].'</div>';

                //TAB CONTENT
                $ui .= '<div class="padded">'.$tab_ui.'</div>';

            }





            //Print footer:
            $ui .= '<div class="doclear">&nbsp;</div>';
            if($show_accordion){
                $ui .= '</div></div></div>';
            }
        }
    }

    if($show_accordion){
        $ui .= '</div>'; //End of accordion
    }

    return $ui;

}


function view_unauthorized_message($superpower_e__id = 0){

    $member_e = superpower_unlocked($superpower_e__id);

    if(!$member_e){
        if(!$superpower_e__id){

            //Missing Session
            return 'You must login to continue.';

        } else {

            //Missing Superpower:
            $CI =& get_instance();
            $e___10957 = $CI->config->item('e___10957');
            return 'Missing: '.$e___10957[$superpower_e__id]['m__title'].' SUPERPOWER';

        }
    }


    return null;

}

function view_time_hours($total_seconds, $hide_hour = false){

    $total_seconds = intval($total_seconds);
    //Turns seconds into HH:MM:SS
    $hours = floor($total_seconds/3600);
    $minutes = floor(fmod($total_seconds, 3600)/60);
    $seconds = fmod($total_seconds, 60);

    return ( $hide_hour && !$hours ? '' : str_pad($hours, 2, "0", STR_PAD_LEFT).':' ).str_pad($minutes, 2, "0", STR_PAD_LEFT).':'.str_pad($seconds, 2, "0", STR_PAD_LEFT);
}

function view__load__e($e){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');
    return '<div class="msg alert alert-info no-margin" style="margin-bottom: 10px !important;" title="'.$e___11035[13670]['m__title'].'"><span class="icon-block">'.$e___11035[13670]['m__icon'].'</span>' . view_e__icon($e['e__icon']) . '&nbsp;<a href="/@'.$e['e__id'].'" class="'.extract_icon_color($e['e__icon']).'">' . $e['e__title'].'</a>&nbsp;&nbsp;&nbsp;<a href="/'.$CI->uri->segment(1).'" title="'.$e___11035[13671]['m__title'].'">'.$e___11035[13671]['m__icon'].'</a></div>';
}


function view_i_featured($e__id_limit = 0, $i_exclude = array()){

    $CI =& get_instance();
    $visible_ui = '';
    $hidden_ui = '';
    $limit = ( $e__id_limit ? 0 : view_memory(6404,12138) );
    $max_visible = view_memory(6404,14435);
    $member_e = superpower_unlocked();
    $loaded_topics = 0;
    $my_topics = ( $member_e ? array_intersect($CI->session->userdata('session_parent_ids'),  $CI->config->item('n___12138')) : array() );


    //Go through Featured Categories:
    foreach($CI->config->item('e___12138') as $e__id => $m) {

        if($e__id_limit && $e__id_limit!=$e__id){
            continue;
        }
        $query_filters = array(
            'i__type IN (' . join(',', $CI->config->item('n___7355')) . ')' => null, //PUBLIC
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            '(x__up = '.$e__id.' OR x__down = '.$e__id.')' => null,
        );
        if(count($i_exclude)){
            $query_filters['i__id NOT IN (' . join(',', $i_exclude) . ')'] = null;
        }

        $query = $CI->X_model->fetch($query_filters, array('x__right'), $limit, 0, array('i__spectrum' => 'DESC'));

        if(count($query)){

            $should_be_hidden = ( !count($my_topics) && $loaded_topics>=$max_visible ) || ( count($my_topics) && !in_array($e__id, $my_topics) );
            $loaded_topics++;

            //We need to check if we have more than this?
            $see_all_link = '<span class="icon-block">'.$m['m__icon'].'</span>'.$m['m__title'];
            if(!$e__id_limit){
                //We might have more, let's check:
                $count_query = $CI->X_model->fetch($query_filters, array('x__right'), 1, 0, array(), 'COUNT(x__id) as totals');
                if($count_query[0]['totals'] > $limit){
                    //Yes, we have more, show this:
                    $see_all_link = '<a href="/@'.$e__id.'" title="'.number_format($count_query[0]['totals'], 0).' Ideas"><span class="icon-block">'.$m['m__icon'].'</span><u>'.$m['m__title'].'</u>&nbsp;<i class="fas fa-chevron-right" style="font-size: 0.8em !important; margin-left:3px;"></i></a>';
                }
            }

            $ui = '<div class="'.( $should_be_hidden ? 'all-topics hidden' : '' ).'">';
            $ui .= '<div class="headline top-margin">'.$see_all_link.'</div>';
            $ui .= '<div class="row margin-top-down-half">';
            foreach($query as $i){
                $ui .= view_i(12138, 0, null, $i);
                if(!in_array($i['i__id'], $i_exclude)){
                    array_push($i_exclude, $i['i__id']);
                }
            }
            $ui .= '</div>';
            $ui .= '</div>';

            if($should_be_hidden){
                $hidden_ui .= $ui;
            } else {
                $visible_ui .= $ui;
            }
        }
    }


    if($hidden_ui){
        //Append hidden UI to visible UI:
        $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
        $visible_ui .= $hidden_ui;
        $visible_ui .= '<div class="margin-top-down full-width-btn all-topics center"><a  href="javascript:void(0);" onclick="$(\'.all-topics\').toggleClass(\'hidden\');" class="btn btn-large btn-default">'.$e___11035[14435]['m__icon'].' '.$e___11035[14435]['m__title'].'</a></div>';
    }

    return $visible_ui;
}

function view_info_box($e__id){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $ui = '';
    //$ui .= '<div class="info_box_icon">' . $e___11035[$e__id]['m__icon'] . '</div>';
    $ui .= '<h2 class="info_box_header css__title">' . $e___11035[$e__id]['m__title'] . '</h2>';
    //if(strlen($e___11035[$e__id]['m__message'])){ $ui .= '<div class="info_box_message" style="margin-bottom: 89px;">'.$e___11035[$e__id]['m__message'].'</div>'; }
    $ui .= '<div class="row">';
    foreach($CI->config->item('e___'.$e__id) as $m) {
        $ui .= '<div class="col-12 col-sm-4">';
            $ui .= '<div class="info_box">';
                $ui .= '<div class="info_box_cover">'.$m['m__icon'].'</div>';
                $ui .= '<div class="info_box_title css__title">'.$m['m__title'].'</div>';
                $ui .= '<div class="info_box_message">'.$m['m__message'].'</div>';
            $ui .= '</div>';
        $ui .= '</div>';
    }
    $ui .= '</div>';


    //SOCIAL FOOTER
    $ui .= '<ul class="social-footer">';
    foreach($CI->config->item('e___7146') as $e__id => $m) {
        $ui .= '<li><a href="/-7146?e__id='.$e__id.'" title="'.$m['m__title'].'" data-toggle="tooltip" data-placement="top">'.$m['m__icon'].'</a></li>';
    }
    $ui .= '</ul>';


    return $ui;
}

function view_i_select($i, $x__source, $previously_selected){


    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    $i_fetch_cover = i_fetch_cover($i['i__id']);
    $is_valid_url = filter_var($i_fetch_cover, FILTER_VALIDATE_URL);
    $completion_rate = $CI->X_model->completion_progress($x__source, $i);
    $i_title = view_i_title($i, null, true);
    $i_stats = i_stats($i['i__metadata']);
    $href = 'href="javascript:void(0);" onclick="select_answer(' . $i['i__id'] . ')"';

    $ui  = '<div class="i_cover col-md-3 col-sm-4 col-6 no-padding">';
    $ui .= '<div class="cover-wrapper">';
    $ui .= '<a '.$href.' selection_i__id="' . $i['i__id'] . '" class="' . ($previously_selected ? ' active_selected ' : '') . ' x_select_' . $i['i__id'] . ' answer-item black-background cover-link" '.( $is_valid_url ? 'style="background-image:url(\''.$i_fetch_cover.'\');"' : '' ).'>';

    //ICON?
    if(!$is_valid_url){
        $ui .= '<div class="cover-btn">'.$i_fetch_cover.'</div>';
    }

    //LEFT
    $ui .= '<div class="inside-btn left-btn check-icon"><i class="' . ($previously_selected ? 'fas fa-check-circle' : 'far fa-circle') . '"></i></div>';

    //PROGRESS?
    if($completion_rate['completion_percentage'] > 0){
        $ui .= '<div class="cover-progress">'.view_x_progress($completion_rate, $i).'</div>';
    }

    $ui .= '</a>';
    $ui .= '</div>';

    if($i_title){
        $ui .= '<div class="cover-content"><div class="inner-content"><a '.$href.'>'.$i_title.'</a></div></div>';
    }

    if($i_stats['i___6162'] >= view_memory(6404,14735)){
        $ui .= '<div class="cover-text css__title">'.view_i_time($i_stats).'</div>';
    }
    $ui .= '</div>';

    return $ui;

}


function view_i($x__type, $top_i__id = 0, $previous_i = null, $i, $control_enabled = false, $message_input = null, $focus_e = false, $completion_rate = null, $extra_class = null){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    if(!in_array($x__type, $CI->config->item('n___13369'))){
        return 'Invalid x__type '.$x__type;
    }
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $e___13369 = $CI->config->item('e___13369'); //IDEA LIST
    $e_of_i = e_of_i($i['i__id']);
    $user_input = $focus_e;
    $user_session = superpower_unlocked();
    $discovery_mode = in_array($x__type, $CI->config->item('n___14378')); //DISCOVERY MODE
    $idea_editing = in_array($x__type, $CI->config->item('n___14502')) && $e_of_i; //IDEA EDITING
    $load_completion = in_array($x__type, $CI->config->item('n___14501'));
    $is_self = $user_session && $focus_e && $user_session['e__id']==$focus_e['e__id'];

    /*
    if($focus_e && (!$member_session || $member_session['e__id']!=$focus_e['e__id']) && !superpower_active(12701, true)){
        //Do not allow to see this member's info:
        $focus_e = false;
    }
    */

    if(!$focus_e){
        $focus_e = $user_session;
    }

    if($load_completion){ //Load Completion Bar
        if(is_null($completion_rate)){
            $completion_rate['completion_percentage'] = 0; //Assume no progress
            if($focus_e && $discovery_mode){
                $completion_rate = $CI->X_model->completion_progress($focus_e['e__id'], $i);
            }
        }
    } else {
        //Completion rate not supported:
        $completion_rate['completion_percentage'] = 0; //Assume no progress
    }


    $superpower_10939 = superpower_active(10939, true);
    $superpower_12700 = superpower_active(12700, true);
    $previous_is_lock = ($previous_i && in_array($previous_i['i__type'], $CI->config->item('n___14488')));
    $locking_enabled = !$control_enabled || !isset($focus_e['e__id']) || $focus_e['e__id']<1 || ($previous_is_lock && $discovery_mode);
    $is_hard_lock = in_array($x__type, $CI->config->item('n___14453'));
    $is_soft_lock = $locking_enabled && ($is_hard_lock || $previous_is_lock || (in_array($x__type, $CI->config->item('n___14377')) && !$completion_rate['completion_percentage']));
    $is_sortable = !$is_soft_lock && in_array($x__type, $CI->config->item('n___4603'));
    $i_stats = i_stats($i['i__metadata']);
    $i_title = view_i_title($i, null, !$message_input);
    $is_any_lock = $is_soft_lock || $is_hard_lock;
    $lock_notice = (  $previous_is_lock ? 14488 : 14377 );

    if(in_array($x__type, $CI->config->item('n___14454')) && $completion_rate['completion_percentage']<100){
        $href = '/x/x_next/'.$top_i__id.'/'.$i['i__id'];
    } elseif(strlen($e___13369[$x__type]['m__message'])){
        $href = $e___13369[$x__type]['m__message'].$i['i__id'];
    } elseif(in_array($x__type, $CI->config->item('n___14742')) && $previous_i){
        //Complete if not already:
        $href = '/x/complete_next/'.$top_i__id.'/'.$previous_i['i__id'].'/'.$i['i__id'];
    } elseif($discovery_mode){
        $href = '/'.$top_i__id.'/'.$i['i__id'];
    } else {
        $href = '/i/i_go/'.$i['i__id'] . ( isset($_GET['load__e']) ? '?load__e='.intval($_GET['load__e']) : '' );
    }


    $ui  = '<div '.( isset($i['x__id']) ? ' x__id="'.$i['x__id'].'" ' : '' ).' class="i_cover col-md-3 col-sm-4 col-6 no-padding i_line_'.$i['i__id'].' '.( $is_sortable ? ' cover_sort ' : '' ).( isset($i['x__id']) ? ' cover_x_'.$i['x__id'].' ' : '' ).( $is_soft_lock ? ' not-allowed ' : '' ).' '.$extra_class.'" '.( $is_hard_lock ? ' title="'.$e___11035[$x__type]['m__title'].'" data-toggle="tooltip" data-placement="bottom" ' : ( $is_soft_lock ? ' title="'.$e___11035[$lock_notice]['m__title'].'" data-toggle="tooltip" data-placement="top" ' : '' ) ).'>';



    $i_fetch_cover = i_fetch_cover($i['i__id']);
    $is_valid_url = filter_var($i_fetch_cover, FILTER_VALIDATE_URL);
    $ui .= '<div class="cover-wrapper">';
    $ui .= ( $is_any_lock ? '<div' : '<a href="'.$href.'"' ).' class="black-background cover-link" '.( $is_valid_url ? 'style="background-image:url(\''.$i_fetch_cover.'\');"' : '' ).'>';


    //ICON?
    if(!$is_valid_url){
        $ui .= '<div class="cover-btn">'.$i_fetch_cover.'</div>';
    }


    //LEFT
    if($is_sortable && $control_enabled){
        //SORTABLE
        $ui .= '<div class="inside-btn left-btn x_sort" title="'.$e___11035[4603]['m__title'].'">'.$e___11035[4603]['m__icon'].'</div>';
    } elseif(in_array($x__type, $CI->config->item('n___14452'))){
        //Show Self Icon
        $ui .= '<div class="inside-btn left-btn" title="'.$e___11035[$x__type]['m__title'].'">'.$e___11035[$x__type]['m__icon'].'</div>';
    } elseif($is_soft_lock){
        //LOCKED
        $ui .= '<div class="inside-btn left-btn" title="'.$e___11035[$lock_notice]['m__title'].'">'.$e___11035[$lock_notice]['m__icon'].'</div>';
    } elseif($completion_rate['completion_percentage']>=100){
        //100% COMPLETE
        //$ui .= '<div class="inside-btn left-btn" title="'.$e___11035[14459]['m__title'].'">'.$e___11035[14459]['m__icon'].'</div>';
    }

    //RIGHT
    if($control_enabled && isset($i['x__id']) && in_array($x__type, $CI->config->item('n___6155'))){
        //UNLINK
        $ui .= '<div class="inside-btn right-btn x_remove" i__id="'.$i['i__id'].'" x__id="'.$i['x__id'].'" title="'.$e___11035[6155]['m__title'].'">'.$e___11035[6155]['m__icon'].'</div>';
    }

    //PROGRESS?
    if($load_completion){
        $ui .= '<div class="cover-progress">'.view_x_progress($completion_rate, $i).'</div>';
    }

    $ui .= ( $is_any_lock ? '</div>' : '</a>' );
    $ui .= '</div>';


    if($message_input || $i_title){
        $ui .= '<div class="cover-content"><div class="inner-content">';
        //$ui .= view_input_text(4736, $i['i__title'], $i['i__id'], $idea_editing, (($i['x__spectrum']*100)+1));
        if($i_title){
            if(!$is_any_lock){
                $ui .= '<a href="'.$href.'">'.$i_title.'</a>';
            } else {
                $ui .= $i_title;
            }
        }
        if($message_input){
            if(!$is_soft_lock && !substr_count($message_input, '<a ') && !substr_count($message_input, '<iframe')){
                //No HTML Tags, add link:
                $ui .= '<a href="'.$href.'">'.$message_input.'</a>';
            } else {
                //Leave as is so HTML tags work:
                $ui .= $message_input;
            }
        }
        $ui .= '</div></div>';
    }

    if(!$is_any_lock){

        //TOOLBAR
        if($idea_editing && superpower_active(12673, true)){

            //Idea Toolbar
            $ui .= '<div style="text-align: center;">';

            if(isset($i['x__id'])){

                $x__metadata = unserialize($i['x__metadata']);

                //IDEA LINK BAR
                $ui .= '<span class="' . superpower_active(12700) . '">';

                //LINK TYPE
                $ui .= view_input_dropdown(4486, $i['x__type'], null, $idea_editing, false, $i['i__id'], $i['x__id']);

                //LINK MARKS
                $ui .= '<span class="x_marks account_4228 '.( $i['x__type']==4228 ? : 'hidden' ).'">';
                $ui .= view_input_text(4358, ( isset($x__metadata['tr__assessment_points']) ? $x__metadata['tr__assessment_points'] : '' ), $i['x__id'], $idea_editing, ($i['x__spectrum']*10)+2 );
                $ui .='</span>';


                //LINK CONDITIONAL RANGE
                $ui .= '<span class="x_marks account_4229 '.( $i['x__type']==4229 ? : 'hidden' ).'">';
                //MIN
                $ui .= view_input_text(4735, ( isset($x__metadata['tr__conditional_score_min']) ? $x__metadata['tr__conditional_score_min'] : '' ), $i['x__id'], $idea_editing, ($i['x__spectrum']*10)+3);
                //MAX
                $ui .= view_input_text(4739, ( isset($x__metadata['tr__conditional_score_max']) ? $x__metadata['tr__conditional_score_max'] : '' ), $i['x__id'], $idea_editing, ($i['x__spectrum']*10)+4);
                $ui .= '</span>';
                $ui .= '</span>';

            }

            $ui .= '</div>';

        }





        //IDEA TYPE
        $ui .= '<div class="cover-text css__title">';

        //Always Show Time
        if($i_stats['i___6162'] >= view_memory(6404,14735)) {
            $ui .= '<div class="inline-block" ' . ($idea_editing && !$superpower_12700 ? ' style="min-width: 38px; padding-right: 10px; margin-left: -4px;" ' : '') . '>' . view_i_time($i_stats, false, $idea_editing) . '</div>';
        }


        if($idea_editing) {

            $e___4737 = $CI->config->item('e___4737'); // Idea Status
            $first_segment = $CI->uri->segment(1);
            $current_i = ( substr($first_segment, 0, 1)=='~' ? intval(substr($first_segment, 1)) : 0 );

            if($superpower_12700){

                //Previous Ideas:
                $is_previous = $CI->X_model->fetch(array(
                    'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                    'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
                    'x__right' => $i['i__id'],
                ), array('x__left'), 0, 0, array('i__spectrum' => 'DESC'));

                if(count($is_previous)){
                    $ui .= '<div class="dropdown inline-block" title="'.$e___11035[11019]['m__title'].'" data-toggle="tooltip" data-placement="right">';
                    $ui .= '<button type="button" class="btn no-left-padding no-right-padding idea icon-block-xs" id="nextIdeas'.$i['i__id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.count($is_previous).'</button>';
                    $ui .= '<div class="dropdown-menu btn-idea" aria-labelledby="nextIdeas'.$i['i__id'].'">';
                    foreach($is_previous as $previous_i) {
                        $ui .= '<a href="/~'.$previous_i['i__id'].'" class="dropdown-item css__title '.( $previous_i['i__id']==$current_i ? ' active ' : '' ).'"><span class="icon-block i__type_'.$previous_i['i__id'].'" title="'.$e___4737[$previous_i['i__type']]['m__title'].'">'.$e___4737[$previous_i['i__type']]['m__icon'].'</span>'.view_i_title($previous_i).'</a>';
                    }
                    $ui .= '</div>';
                    $ui .= '</div>';
                } else {
                    $ui .= '<div class="icon-block-xs idea css__title" title="'.$e___11035[11019]['m__title'].'" data-toggle="tooltip" data-placement="right">0</div>';
                }

            }



            //Type Dropdown:
            $ui .= view_input_dropdown(4737, $i['i__type'], null, $idea_editing, false, $i['i__id']);



            //Next Ideas:
            $is_next = $CI->X_model->fetch(array(
                'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
                'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
                'x__left' => $i['i__id'],
            ), array('x__right'), 0, 0, array('x__spectrum' => 'ASC'));
            if(count($is_next)){
                $ui .= '<div class="dropdown inline-block" title="'.$e___11035[13542]['m__title'].'" data-toggle="tooltip" data-placement="right">';
                $ui .= '<button type="button" class="btn no-left-padding no-right-padding idea icon-block-xs" id="nextIdeas'.$i['i__id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.count($is_next).'</button>';
                $ui .= '<div class="dropdown-menu btn-idea" aria-labelledby="nextIdeas'.$i['i__id'].'">';
                foreach($is_next as $next_i) {
                    $ui .= '<a href="/~'.$next_i['i__id'].'" class="dropdown-item css__title '.( $next_i['i__id']==$current_i ? ' active ' : '' ).'"><span class="icon-block i__type_'.$next_i['i__id'].'" title="'.$e___4737[$next_i['i__type']]['m__title'].'">'.$e___4737[$next_i['i__type']]['m__icon'].'</span>'.view_i_title($next_i).'</a>';

                }
                $ui .= '</div>';
                $ui .= '</div>';
            } else {
                $ui .= '<div class="icon-block-xs idea css__title" title="'.$e___11035[13542]['m__title'].'" data-toggle="tooltip" data-placement="right">0</div>';
            }

        }

        $ui .= '</div>';

    } else {

        if($i_stats['i___6162'] >= view_memory(6404,14735)) {
            $ui .= '<div class="cover-text css__title">' . view_i_time($i_stats) . '</div>';
        }
    }

    if($is_self && !$discovery_mode && !$e_of_i){
        $ui .= '<div class="cover-text css__title grey" style="margin-top: -15px;">[Not a Source Yet]</div>';
    }


    $ui .= '</div>';

    return $ui;

}

function view_x_progress($completion_rate, $i){

    if(!isset($completion_rate['steps_total'])){
        return '<div class="progress-bg-list progress_'.$i['i__id'].'"><div class="progress-done" style="width:0%"></div></div>';
    }

    return '<div class="progress-bg-list progress_'.$i['i__id'].'" title="'.$completion_rate['completion_percentage'].'% COMPLETED: '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' IDEAS DISCOVERED" data-toggle="tooltip" data-placement="top"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';

}



function view_e($x__type, $e, $extra_class = null, $source_of_e = false, $common_prefix = null, $message_input = null)
{


    $CI =& get_instance();
    if(!in_array($x__type, $CI->config->item('n___14690'))){
        //Not a valid Source List
        return 'Invalid x__type '.$x__type;
    }

    $member_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $superpower_10939 = superpower_active(10939, true);
    $superpower_12706 = superpower_active(12706, true);
    $superpower_13422 = superpower_active(13422, true);
    $superpower_12701 = superpower_active(12701, true);

    $control_enabled = in_array($x__type, $CI->config->item('n___14696'));
    $is_sortable = in_array($x__type, $CI->config->item('n___13911'));
    $show_time = in_array($x__type, $CI->config->item('n___14706'));
    $source_of_e = $control_enabled && $member_e && ($source_of_e || $superpower_13422);
    $x__id = (isset($e['x__id']) ? $e['x__id'] : 0);
    $is_e_link = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___4592')));
    $show_text_editor = $source_of_e && $is_e_link;

    $e_url = '/@'.$e['e__id'];
    $focus_e__id = ( substr($CI->uri->segment(1), 0, 1)=='@' ? intval(substr($CI->uri->segment(1), 1)) : 0 );
    $is_note = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___4485')));
    $is_x_progress = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___12227')));
    $public_sources = $CI->config->item('n___14603');

    $e__profiles = $CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__up !=' => $focus_e__id, //Do Not Fetch Current Source
        'x__down' => $e['e__id'], //This child source
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__type IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__up'), 0, 0, array('e__spectrum' => 'DESC'));


    //Allow source to see all their own transactions:
    $is_private = (!$member_e || $member_e['e__id']!=$focus_e__id) && (filter_array($e__profiles, 'e__id', '4755') || in_array($e['e__id'], $CI->config->item('n___4755')));
    $is_public = in_array($e['e__id'], $public_sources) || in_array($focus_e__id, $public_sources) || ($x__id > 0 && in_array($e['x__type'], $public_sources)) || filter_array($e__profiles, 'e__id', $public_sources);


    if(($is_private && !$superpower_12701) || (!$is_public && !$source_of_e && !$superpower_13422)){
        //PRIVATE SOURCE:
        return ( $superpower_13422 || in_array($x__type, $CI->config->item('n___14691')) ? '<div class="list-group-item itemsource no-side-padding '. $extra_class  . '"><span class="icon-block">'.$e___11035[4755]['m__icon'].'</span>'.$e___11035[4755]['m__title'].'</div>' : null );
    }


    //SOURCE INFO BAR
    $box_items_list = '';

    //SOURCE STATUS
    if(!in_array($e['e__type'], $CI->config->item('n___7357'))){
        $e___6177 = $CI->config->item('e___6177'); //Source Status
        $box_items_list .= '<span class="inline-block e__type_' . $e['e__id'].'"><span data-toggle="tooltip" data-placement="right" title="'.$e___6177[$e['e__type']]['m__title'].' @'.$e['e__type'].'">' . $e___6177[$e['e__type']]['m__icon'] . '</span>&nbsp;</span>';
    }

    //DISCOVER STATUS
    if($x__id > 0 && !in_array($e['x__status'], $CI->config->item('n___7359'))){
        $e___6186 = $CI->config->item('e___6186'); //Transaction Status
        $box_items_list .= '<span class="inline-block x__status_' . $x__id .'"><span data-toggle="tooltip" data-placement="right" title="'.$e___6186[$e['x__status']]['m__title'].' @'.$e['x__status'].'">' . $e___6186[$e['x__status']]['m__icon'] . '</span>&nbsp;</span>';
    }


    //ROW itemsource
    $ui = '<div class="list-group-item no-side-padding itemsource en-item object_saved saved_e_'.$e['e__id'].' e__id_' . $e['e__id'] . ( $x__id > 0 ? ' tr_' . $e['x__id'].' ' : '' ) . ' '. $extra_class  . '" e__id="' . $e['e__id'] . '" x__id="'.$x__id.'">';


    if($source_of_e && ($is_e_link || $is_note)){

        //RIGHT EDITING:
        $ui .= '<div class="note-editor edit-off">';
        $ui .= '<span class="show-on-hover">';


        if(($source_of_e && $is_sortable) || $superpower_13422){

            //UNLINK SOURCE
            $ui .= '<span class="'.superpower_active(10939).'"><a href="javascript:void(0);" onclick="e_remove(' . $x__id . ', '.$e['x__type'].')" title="'.$e___11035[10673]['m__title'].'">'.$e___11035[10673]['m__icon'].'</a></span>';

        }


        if($is_e_link){

            //Sort
            if($is_sortable && $superpower_10939){
                $ui .= '<span title="'.$e___11035[13911]['m__title'].'" class="'.superpower_active(13422).' sort_e hidden">'.$e___11035[13911]['m__icon'].'</span>';
            }

            //Edit Raw Source
            $ui .= '<span class="btn-third '.superpower_active(13422).'"><a href="javascript:void(0);" onclick="e_modify_load(' . $e['e__id'] . ',' . $x__id . ')" title="'.$e___11035[13571]['m__title'].'">'.$e___11035[13571]['m__icon'].'</a></span>';

        }

        //HARD DELETE SOURCE
        $ui .= '<span class="btn-fourth '.superpower_active(14683).'"><a href="javascript:void(0);" onclick="e_nuclear_delete(' . $e['e__id'] . ', '.$e['x__type'].')" title="'.$e___11035[14601]['m__title'].'">'.$e___11035[14601]['m__icon'].'</a></span>';

        $ui .= '</span>';
        $ui .= '</div>';

    }




    $ui .= '<div class="row">';


        $ui .= '<div class="col-9 col-sm-10 col-md-8">';

            //SOURCE ICON
            $ui .= '<a href="'.$e_url.'" '.( $is_e_link ? ' title="TRANSACTION ID '.$e['x__id'].' TYPE @'.$e['x__type'].' SORT '.$e['x__spectrum'].' WEIGHT '.$e['e__spectrum'].'" ' : '' ).'><span class="icon-block e_ui_icon_' . $e['e__id'] . ' e__icon_'.$e['e__id'].'">' . view_e__icon($e['e__icon']) . '</span></a>';


            //SOURCE TITLE TEXT EDITOR
            if($show_text_editor && !$show_time){

                $ui .= view_input_text(6197, $e['e__title'], $e['e__id'], $source_of_e, 0, false, null, extract_icon_color($e['e__icon']));

            } else {

                //SOURCE NAME with PREFIX
                $ui .= '<a href="'.$e_url.'" class="title-block title-no-right css__title '.extract_icon_color($e['e__icon']).'">';
                $ui .= $box_items_list;
                $ui .= '<span class="text__6197_' . $e['e__id'] . '">'.( $common_prefix ? str_replace($common_prefix, '', $e['e__title']) : $e['e__title'] ).'</span>';

                if($show_time && isset($e['x__time'])){
                    $ui .= '<span class="headline-input doregular grey" data-toggle="tooltip" data-placement="top" title="'.substr($e['x__time'], 0, 19).' '.view_memory(6404,11079).'">'.view_time_difference(strtotime($e['x__time'])).' Ago</span>';
                }
                $ui .= '</a>';

            }
        $ui .= '</div>';



        $ui .= '<div class="col-3 col-sm-2 col-md-4">';

            //MENCH
            $ui .= '<div class="row">';
                $ui .= '<div class="col-md-4 show-max">'.view_coins_e(12274, $e['e__id']).'</div>';
                $ui .= '<div class="col-md-4 col">'.view_coins_e(12273, $e['e__id']).'</div>';
                $ui .= '<div class="col-md-4 show-max">'.view_coins_e(6255, $e['e__id']).'</div>';
            $ui .= '</div>';

        $ui .= '</div>';

    $ui .= '</div>';



    if($show_text_editor && $box_items_list){
        $ui .= '<div class="space-content" style="padding-top: 10px;">'.$box_items_list.'</div>';
    }

    if($superpower_12706 && $control_enabled){
        //PROFILE
        $ui .= '<div class="space-content hideIfEmpty '.superpower_active(12706).'">';
        //PROFILE SOURCES:
        $ui .= '<span class="paddingup inline-block hideIfEmpty">';
        foreach($e__profiles as $e_profile) {
            $ui .= '<span class="icon-block-img e_child_icon_' . $e_profile['e__id'] . '"><a href="/@' . $e_profile['e__id'] . '" data-toggle="tooltip" title="' . $e_profile['e__title'] . (strlen($e_profile['x__message']) > 0 ? ' = ' . $e_profile['x__message'] : '') . '" data-placement="bottom">' . view_e__icon($e_profile['e__icon']) . '</a></span> ';
        }
        $ui .= '</span>';
        $ui .= '</div>';
    }



    //DISCOVERY TOOLBAR
    if($is_x_progress && superpower_active(13758, true)){

        $ui .= '<div class="message_content paddingup x__message block '.superpower_active(13758).'">';

        //Show Filter?
        if(superpower_active(14005, true) && (!isset($_GET['load__e']) || $_GET['load__e']!=$e['e__id'])){
            $ui .= '<a href="/'.$CI->uri->segment(1).'?load__e='.$e['e__id'].'" class="icon-block-xs" title="'.$e___11035[13670]['m__title'].'">'.$e___11035[13670]['m__icon'].'</a>';
        }

        //Total Progress
        if(isset($_GET['progress'])){
            $is = $CI->I_model->fetch(array(
                'i__id' => $e['x__left'],
            ));
            $completion_rate = $CI->X_model->completion_progress($e['x__source'], $is[0]);
            $ui .= '<span style="min-width:34px;" class="inline-block" title="'.$e['x__source'].'/'.$e['x__left'].'">' . $completion_rate['completion_percentage'] . '%</span>';
        }

        //Method & Time:
        $ui .= '<span style="min-width:147px;" title="'.$e['x__time'].'" class="inline-block"><span class="icon-block-xs">'.view_cache(12227, $e['x__type']).'</span>' . view_time_difference(strtotime($e['x__time'])) . ' Ago</span>';

        $ui .= '</div>';
    }


    //MESSAGE
    if ($x__id > 0) {
        if($is_e_link){

            $ui .= '<span class="message_content paddingup x__message hideIfEmpty x__message_' . $x__id . '">' . view_x__message($e['x__message'] , $e['x__type']) . '</span>';

        } elseif($is_x_progress && strlen($e['x__message'])){

            //DISCOVER PROGRESS
            $ui .= '<div class="message_content paddingup" style="margin-left: 0;">';
            $ui .= $CI->X_model->message_view($e['x__message'], false);
            $ui .= '</div>';

        }
    }

    if($message_input){
        $ui .= '<div class="message_content paddingup" style="margin-left:41px;">'.$message_input.'</div>';
    }


    $ui .= '</div>';

    return $ui;

}


function view_input_text($cache_e__id, $current_value, $s__id, $e_of_i, $tabindex = 0, $extra_large = false, $e__icon = null, $append_css = null){

    $CI =& get_instance();
    $e___12112 = $CI->config->item('e___12112');
    $current_value = htmlentities($current_value);
    $name = 'input'.substr(md5($cache_e__id.$current_value.$s__id.$e_of_i.$tabindex), 0, 8);

    //Define element attributes:
    $attributes = ( $e_of_i ? '' : 'disabled' ).' spellcheck="false" tabindex="'.$tabindex.'" old-value="'.$current_value.'" id="input_'.$cache_e__id.'_'.$s__id.'" class="form-control css__title inline-block x_set_class_text text__'.$cache_e__id.'_'.$s__id.($extra_large?' texttype__lg ' : ' texttype__sm ').' text_e_'.$cache_e__id.' '.$append_css.'" cache_e__id="'.$cache_e__id.'" s__id="'.$s__id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $focus_element = '<textarea name="'.$name.'" onkeyup="view_input_text_count('.$cache_e__id.','.$s__id.')" placeholder="'.$e___12112[$cache_e__id]['m__title'].'" '.$attributes.'>'.$current_value.'</textarea>';
        $character_counter = '<div class="title_counter title_counter_'.$cache_e__id.'_'.$s__id.' hidden grey css__title doupper" style="text-align: right;"><span id="current_count_'.$cache_e__id.'_'.$s__id.'">0</span>/'.view_memory(6404,$cache_e__id).' CHARACTERS</div>';
        $icon = '<span class="icon-block title-icon">'.$e__icon.'</span>';

    } else {

        $focus_element = '<input type="text" name="'.$name.'" placeholder="__" value="'.$current_value.'" '.$attributes.' />';
        $character_counter = ''; //None
        $icon = $e__icon;

    }

    return '<span class="span__'.$cache_e__id.' '.( !$e_of_i ? 'edit-locked' : '' ).'">'.$icon.$focus_element.'</span>'.$character_counter;
}




function view_input_dropdown($cache_e__id, $selected_e__id, $btn_class, $e_of_i = true, $show_full_name = true, $i__id = 0, $x__id = 0){

    $CI =& get_instance();
    $e___this = $CI->config->item('e___'.$cache_e__id);

    if(!$selected_e__id || !isset($e___this[$selected_e__id])){
        return false;
    }

    $e___12079 = $CI->config->item('e___12079');
    $e___4527 = $CI->config->item('e___4527');

    $ui = '<div class="dropdown inline-block dropd_'.$cache_e__id.'_'.$i__id.'_'.$x__id.'" selected-val="'.$selected_e__id.'" title="'.$e___12079[$cache_e__id]['m__title'].'" data-toggle="tooltip" data-placement="right">'; //dropup

    $ui .= '<button type="button" '.( $e_of_i ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' btn-'.$btn_class.'" id="dropdownMenuButton'.$cache_e__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn adj-btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked  '.$btn_class.'"' ).' >';

    $ui .= '<span class="'.( $show_full_name ? 'icon-block' : '' ).'">' .$e___this[$selected_e__id]['m__icon'].'</span>'.( $show_full_name ?  $e___this[$selected_e__id]['m__title'] : '' );

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu btn-'.$btn_class.'" aria-labelledby="dropdownMenuButton'.$cache_e__id.'">';

    foreach($e___this as $e__id => $m) {

        $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m__profile']);
        $is_url_desc = ( substr($m['m__message'], 0, 1)=='/' );

        //What type of URL?
        if($is_url_desc){

            //Basic transaction:
            $anchor_url = ( $e__id==$selected_e__id ? 'href="javascript:void();"' : 'href="'.$m['m__message'].'"' );

        } else{

            //Idea Dropdown updater:
            $anchor_url = 'href="javascript:void();" new-en-id="'.$e__id.'" onclick="i_set_dropdown('.$cache_e__id.', '.$e__id.', '.$i__id.', '.$x__id.', '.intval($show_full_name).')"';

        }

        $ui .= '<a class="dropdown-item dropi_'.$cache_e__id.'_'.$i__id.'_'.$x__id.' css__title optiond_'.$e__id.'_'.$i__id.'_'.$x__id.' doupper '.( $e__id==$selected_e__id ? ' active ' : ( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ) ).'" '.$anchor_url.' title="'.$m['m__message'].'"><span class="icon-block">'.$m['m__icon'].'</span>'.$m['m__title'].'</a>'; //Used to show desc but caused JS click conflict sp retired for now: ( strlen($m['m__message']) && !$is_url_desc ? 'title="'.$m['m__message'].'" data-toggle="tooltip" data-placement="right"' : '' )

    }

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

function view__s($count, $is_e = 0)
{
    //A cute little function to either display the plural "s" or not based on $count
    return ( intval($count) == 1 ? '' : ($is_e ? 'es' : 's'));
}

