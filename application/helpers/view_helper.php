<?php

function view_e_load_more($page, $limit, $list_e_count)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $ui = '<div class="load-more montserrat list-group-item itemsource no-left-padding"><a href="javascript:void(0);" onclick="e_load_page(' . $page . ', 0)">';

    //Regular section:
    $max_e = (($page + 1) * $limit);
    $max_e = ($max_e > $list_e_count ? $list_e_count : $max_e);
    $ui .= '<span class="icon-block"><i class="fas fa-plus-circle source"></i></span><b class="montserrat source">SEE MORE</b>';
    $ui .= '</a></div>';

    return $ui;
}

function view_i_tree_stats($i_stats, $noshow_idea){

    //IDEA STATUS BAR
    $CI =& get_instance();
    $e___13544 = $CI->config->item('e___13544'); //IDEA TREE COUNT
    $is_interactive = ( $i_stats['i___6169']!=$i_stats['i___6170'] );
    $has_idea = ( $i_stats['i___6169'] && $i_stats['i___6170'] );
    $cover_show = (!$noshow_idea);

    //Variable time range:
    $ui = null;

    //IDEAS
    if(!$noshow_idea){
        $ui .= '<span class="inline-block '.extract_icon_color($e___13544[12273]['m__icon']).'" '.( !$cover_show ? 'style="min-width:80px;"' : '' ).' title="'.$e___13544[12273]['m__title'].'" data-toggle="tooltip" data-placement="top">'.( $i_stats['i___6169'] > 0 ? ( $has_idea ? ( $cover_show ? $e___13544[12273]['m__icon'].'&nbsp;' : '<span class="icon-block">'.$e___13544[12273]['m__icon'].'</span>' ) : '' ).( $has_idea ? ( $is_interactive ? '<span class="'.superpower_active(12700).'">'.view_number($i_stats['i___6169']).'<span class="mid-range">-</span></span>' : '' ).view_number($i_stats['i___6170']).'&nbsp;' : '' ) : '' ).'</span>';
    }


    //TIME STATS
    if($i_stats['i___6161'] > 0){
        $ui .= '<span class="inline-block grey">';
        $ui .= ( $cover_show ? '&nbsp;&nbsp;'.$e___13544[13292]['m__icon'].' ' : '<span class="icon-block">'.$e___13544[13292]['m__icon'].'</span>' );

        if($i_stats['i___6161']<30 && $i_stats['i___6162']<30){
            //SECONDS
            $ui .= '<span class="inline-block">'.$i_stats['i___6161'].( $i_stats['i___6161']!=$i_stats['i___6162'] ? '<span class="mid-range">-</span>'.$i_stats['i___6162'] : '' ).'&nbsp;SEC</span>';
        } else {
            //MINUTES
            $ui .= '<span class="inline-block">'.round_minutes($i_stats['i___6161']).( round_minutes($i_stats['i___6161']) != round_minutes($i_stats['i___6162']) ? '<span class="mid-range">-</span>'.round_minutes($i_stats['i___6162']) : '' ).( !$cover_show ? '&nbsp;MIN' : '\'' ).'</span>';
        }
        $ui .= '</span>';
    }


    return ( $ui ? '<span class="montserrat doupper">'.$ui.'</span>' : $ui );
}

function view_db_field($field_name){

    //Takes a database field name and returns a human-friendly version
    return ucwords(str_replace('i__', '', str_replace('e__', '', str_replace('x__', '', $field_name))));

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

    $CI =& get_instance();

    if ($x__type == 4256 /* Generic URL */) {

        return '<div class="block"><a href="' . $x__message . '" target="_blank"><span class="url_truncate">' . view_url_clean($x__message) . '</span></a></div>';

    } elseif ($x__type == 4257 /* Embed Widget URL? */) {

        return view_url_embed($x__message, $full_message);

    } elseif ($x__type == 4260 /* Image URL */) {

        return '<img data-src="' . $x__message . '" src="/img/mench.png" alt="IMAGE" class="content-image lazyimage" />';

    } elseif ($x__type == 4259 /* Audio URL */) {

        return  '<audio controls src="' . $x__message . '">Your Browser Does Not Support Audio</audio>' ;

    } elseif ($x__type == 4258 /* Video URL */) {

        return  '<video width="100%" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="' . $x__message . '" type="video/mp4"></video>' ;

    } elseif ($x__type == 4261 /* File URL */) {

        $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
        return '<a href="' . $x__message . '" class="btn btn-idea" target="_blank">'.$e___11035[13573]['m__icon'].' '.$e___11035[13573]['m__title'].'</a>';

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
                    $embed_html_code .= '<div class="grey montserrat" style="padding:5px 0 0 0; font-size:0.84em;"><span class="icon-block-xs">'.$e___11035[13292]['m__icon'].'</span>'.( $seconds<60 ? $seconds.' SEC.' : round_minutes($seconds).' MIN.' ).' CLIP <span class="inline-block">('.view_time_hours($start_time, true).' TO '.view_time_hours($end_time, true).')</span></div>';
                }

                $embed_html_code .= '<div class="media-content"><div class="yt-container video-sorting" style="margin-top:5px;"><iframe id="youtubeplayer'.$video_id.'"  src="//www.youtube.com/embed/' . $video_id . '?wmode=opaque&theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_time . ($end_time ? '&end=' . $end_time : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div></div>';

            }

        } elseif (substr_count($url, 'vimeo.com/') == 1 && is_numeric(one_two_explode('vimeo.com/','?',$url))) {

            //Seems to be Vimeo:
            $video_id = trim(one_two_explode('vimeo.com/', '?', $url));

            //This should be an integer!
            if (intval($video_id) == $video_id) {
                $clean_url = 'https://vimeo.com/' . $video_id;
                $embed_html_code = '<div class="media-content"><div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://user.vimeo.com/video/' . $video_id . '?title=0&byline=0" class="vm-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div></div>';
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


function view_i_note($x__type, $x, $note_e = false)
{

    /*
     *
     * A wrapper function that helps manage messages
     * by giving the message additional platform functions
     * such as editing and changing message type.
     *
     * */


    $CI =& get_instance();
    $user_e = superpower_unlocked();
    $e___4485 = $CI->config->item('e___4485'); //IDEA NOTES
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $e___11035 = $CI->config->item('e___11035');
    $color_code = trim(extract_icon_color($e___4485[$x__type]['m__icon']));
    $supports_emoji = (in_array($x__type, $CI->config->item('n___14038')));


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item item'.$color_code.' is-msg note_sortable msg_e_type_' . $x['x__type'] . '" id="ul-nav-' . $x['x__id'] . '" x__id="' . $x['x__id'] . '">'; //title="'.$x['e__title'].' Posted On '.substr($x['x__time'], 0, 19).'" data-toggle="tooltip" data-placement="top"
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="text_message edit-off" id="msgbody_' . $x['x__id'] . '">';
    $ui .= $CI->X_model->message_send($x['x__message'], in_array($x__type, $CI->config->item('n___13291')), $user_e, $x['x__right']);
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
            $ui .= '<span title="'.$e___11035[13579]['m__title'].'"><a href="javascript:void(0);" onclick="remove_13579(' . $x['x__id'] . ', '.$x['x__type'].')">'.$e___11035[13579]['m__icon'].'</a></span>';

        $ui .= '</span></div>';


        //Text editing:
        $ui .= '<textarea onkeyup="count_13574(' . $x['x__id'] . ')" name="x__message'.$x['x__id'].'" id="message_body_' . $x['x__id'] . '" class="edit-on hidden msg note-textarea edit-note algolia_search" x__id="'.$x['x__id'].'" placeholder="'.stripslashes($x['x__message']).'">' . $x['x__message'] . '</textarea>';


        //Update result & Show potential errors
        $ui .= '<div class="edit-updates hideIfEmpty"></div>';


        //Editing menu:
        $ui .= '<table class="table table-condensed edit-on hidden" style="margin:10px 41px 0;"><tr>';


        //SAVE
        $ui .= '<td class="table-btn"><a class="btn btn-'.$color_code.'" href="javascript:save_13574(' . $x['x__id'] . ',' . $x['x__type'] . ');" title="'.$e___11035[14039]['m__title'].'">'.$e___11035[14039]['m__icon'].'</a></td>';

        //CANCEL
        $ui .= '<td class="table-btn first_btn"><a class="btn btn-grey" title="'.$e___11035[13502]['m__title'].'" href="javascript:cancel_13574(' . $x['x__id'] . ');">'.$e___11035[13502]['m__icon'].'</a></td>';

        if($supports_emoji){
            //EMOJI
            $ui .= '<td class="table-btn emoji_edit hidden first_btn"><span class="btn btn-grey" id="emoji_pick_id'.$x['x__id'].'" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__icon'].'</span></span></td>';
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
    //A simple function to display the User Icon OR the default icon if not available:
    if (strlen($e__icon) > 0) {

        return $e__icon;

    } else {
        //Return default icon for sources:
        $CI =& get_instance();
        $e___12467 = $CI->config->item('e___12467'); //MENCH
        return $e___12467[12274]['m__icon'];
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


function view_x($x, $is_parent_tr = false)
{

    $CI =& get_instance();
    $e___4593 = $CI->config->item('e___4593'); //Transaction Type
    $e___4341 = $CI->config->item('e___4341'); //Transaction Table
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $user_e = superpower_unlocked();
    $superpower_css_12701 = superpower_active(12701); //SUPERPOWER OF DISCOVERY GLASSES





    //Display the item
    $ui = '<div class="x-list">';


    //ID
    $ui .= '<div class="simple-line"><a href="/ledger?x__id='.$x['x__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4367]['m__title'].'" class="mono-space"><span class="icon-block">'.$e___4341[4367]['m__icon']. '</span>'.$x['x__id'].'</a></div>';



    //STATUS
    $ui .= '<div class="simple-line '.$superpower_css_12701.'"><a href="/@'.$x['x__status'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[6186]['m__title'].( strlen($e___6186[$x['x__status']]['m__message']) ? ': '.$e___6186[$x['x__status']]['m__message'] : '' ).'" class="montserrat"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[6186]['m__icon']. '</span><span class="icon-block">'.$e___6186[$x['x__status']]['m__icon'].'</span><span class="'.extract_icon_color($e___6186[$x['x__status']]['m__icon']).'">'.$e___6186[$x['x__status']]['m__title'].'</span></a></div>';


    //SOURCE
    if($x['x__source'] > 0){

        $add_e = $CI->E_model->fetch(array(
            'e__id' => $x['x__source'],
        ));

        $ui .= '<div class="simple-line"><a href="/@'.$add_e[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4364]['m__title'].'" class="montserrat"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[4364]['m__icon']. '</span><span class="'.extract_icon_color($add_e[0]['e__icon']).'"><span class="icon-block">'.view_e__icon($add_e[0]['e__icon']) . '</span>' . $add_e[0]['e__title'] . '</span></a></div>';

    }





    //HIDE PRIVATE INFO?
    if(in_array($x['x__type'] , $CI->config->item('n___4755')) && (!$user_e || $x['x__source']!=$user_e['e__id']) && !superpower_active(12701, true)){

        //Hide Information:
        $ui .= '<div class="simple-line"><span data-toggle="tooltip" class="montserrat" data-placement="top" title="Details are kept private"><span class="icon-block"><i class="fal fa-eye-slash"></i></span>PRIVATE INFORMATION</span></div>';
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


    //TYPE
    $ui .= '<div class="simple-line"><a href="/@'.$x['x__type'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4593]['m__title'].( strlen($e___4593[$x['x__type']]['m__message']) ? ': '.$e___4593[$x['x__type']]['m__message'] : '' ).'" class="montserrat"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[4593]['m__icon']. '</span><span class="icon-block">'. $e___4593[$x['x__type']]['m__icon'] . '</span><span class="'.extract_icon_color($e___4593[$x['x__type']]['m__icon']).'">' . $e___4593[$x['x__type']]['m__title'] . '</span></a></div>';


    //Order
    if($x['x__sort'] > 0){
        $ui .= '<div class="simple-line '.$superpower_css_12701.'"><span data-toggle="tooltip" data-placement="top" title="'.$e___4341[4370]['m__title']. '"><span class="icon-block">'.$e___4341[4370]['m__icon']. '</span>'.view_ordinal($x['x__sort']).'</span></div>';
    }


    //Metadata
    if(strlen($x['x__metadata']) > 0){
        $ui .= '<div class="simple-line '.$superpower_css_12701.'"><a href="/app/12722?x__id=' . $x['x__id'] . '"><span class="icon-block">'.$e___4341[6103]['m__icon']. '</span><u>'.$e___4341[6103]['m__title']. '</u></a></div>';
    }

    //Message
    if(strlen($x['x__message']) > 0 && $x['x__message']!='@'.$x['x__up']){
        $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4372]['m__title'].'"><span class="icon-block">'.$e___4341[4372]['m__icon'].'</span><div class="title-block x-msg">'.htmlentities($x['x__message']).'</div></div>';
    }


    //5x Relations:
    if(!$is_parent_tr){

        $var_index = var_index();
        foreach($CI->config->item('e___10692') as $e__id => $m) {

            //Do we have this set?
            if(!array_key_exists($e__id, $var_index) || !intval($x[$var_index[$e__id]])){
                continue;
            }

            if(in_array(6160 , $m['m__profile'])){

                //SOURCE
                $es = $CI->E_model->fetch(array('e__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/@'.$es[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'" class="montserrat"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[$e__id]['m__icon']. '</span>'.( $x[$var_index[$e__id]]==$x['x__source'] ? '<span class="icon-block">'.$e___4341[4364]['m__icon']. '</span>' : '' ).'<span class="icon-block">'.view_e__icon($es[0]['e__icon']). '</span><span class="'.extract_icon_color($es[0]['e__icon']).'">'.$es[0]['e__title'].'</span></a></div>';

            } elseif(in_array(6202 , $m['m__profile'])){

                //IDEA
                $is = $CI->I_model->fetch(array('i__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/i/i_go/'.$is[0]['i__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'" class="montserrat"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[$e__id]['m__icon']. '</span><span class="icon-block">'.view_cache(4737 /* Idea Status */, $is[0]['i__type'], true, 'right', $is[0]['i__id']).'</span>'.view_i_title($is[0], null).'</a></div>';

            } elseif(in_array(4367 , $m['m__profile'])){

                //PARENT DISCOVER
                $x = $CI->X_model->fetch(array('x__id' => $x[$var_index[$e__id]]));

                if(count($x)){
                    $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'">'.$e___4341[$e__id]['m__icon']. '</span><div class="x-ref">'.view_x($x[0], true).'</div></div>';
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

    $ui .= '<div class="headline"><span class="icon-block">'.$e___11035[12467]['m__icon'].'</span>'.$e___11035[12467]['m__title'].'</div>';
    $ui .= '<div class="list-group" style="padding-bottom:41px;">';
    $ui .= '<div class="list-group-item no-side-padding">';
    $ui .= '<div class="row">';
    $ui .= '<div class="col-sm col-md">&nbsp;</div>';
    $ui .= '<div class="col-sm-6 col-md-4 col2nd">';
    $ui .= '<div class="row">';
    foreach($CI->config->item('e___12467') as $e__id => $m) {
        $count = count_unique_coins($e__id);
        $ui .= '<div class="col-4"><span class="montserrat '.extract_icon_color($m['m__icon']).'" title="'.number_format($count, 0).' '.$m['m__title'].': '.$m['m__message'].'" data-toggle="tooltip" data-placement="top">'.$m['m__icon'].'&nbsp;'.view_number($count).'</span></div>';
    }
    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}




function view_coins_e($x__type, $e__id, $page_num = 0, $append_coin_icon = true, $exclude_ids = array()){

    /*
     *
     * Loads Source Mench Coins
     *
     * */

    $CI =& get_instance();

    if($x__type==12274){

        //SOURCES
        $limit = view_memory(6404,11064);
        $order_columns = array('x__sort' => 'ASC', 'e__title' => 'ASC');
        $join_objects = array('x__down');
        $query_filters = array(
            'x__up' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
        );
        if(count($exclude_ids)){
            $query_filters['e__id NOT IN (' . join(',', $exclude_ids) . ')'] = null;
        }

    } elseif($x__type==12273){

        //IDEAS
        $limit = view_memory(6404,13958);
        $join_objects = array('x__right');
        $order_columns = array('i__weight' => 'DESC'); //BEST IDEAS
        $query_filters = array(
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            '(x__up = '.$e__id.' OR x__down = '.$e__id.')' => null,
        );
        if(count($exclude_ids)){
            $query_filters['i__id NOT IN (' . join(',', $exclude_ids) . ')'] = null;
        }

    } elseif($x__type==6255){

        //DISCOVERIES
        $join_objects = array('x__left');
        $limit = view_memory(6404,11064);

        if($page_num > 0){
            $order_columns = array('x__sort' => 'ASC');
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

        if(count($exclude_ids)){
            $query_filters['i__id NOT IN (' . join(',', $exclude_ids) . ')'] = null;
        }

    }

    //Return Results:
    if($page_num > 0){

        return $CI->X_model->fetch($query_filters, $join_objects, $limit, ($page_num-1)*$limit, $order_columns);

    } else {
        $count_query = $CI->X_model->fetch($query_filters, $join_objects, 1, 0, array(), 'COUNT(x__id) as totals');
        if($append_coin_icon){
            $e___12467 = $CI->config->item('e___12467'); //MENCH COINS
            return ( $count_query[0]['totals'] > 0 ? '<span class="montserrat '.extract_icon_color($e___12467[$x__type]['m__icon']).'" title="'.number_format($count_query[0]['totals'], 0).' '.$e___12467[$x__type]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___12467[$x__type]['m__icon'].'&nbsp;'.view_number($count_query[0]['totals']).'</span>' : null);
        } else {
            return intval($count_query[0]['totals']);
        }
    }

}



function view_coins_i($x__type, $i, $append_coin_icon = true, $append_name = false, $data_placement = 'top'){

    /*
     *
     * Loads Idea Mench Coins
     *
     * */

    $CI =& get_instance();

    if($x__type==12274){

        //SOURCES
        $i_stats = i_stats($i['i__metadata']);
        $count_query = $i_stats['count_13207'];

    } elseif($x__type==12273){

        //IDEAS
        $i_stats = i_stats($i['i__metadata']);
        $count_query = $i_stats['i___6170'];

    } elseif($x__type==6255){

        $query_filters = array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVER COIN
            'x__left' => $i['i__id'],
        );

        if(isset($_GET['load__e'])){
            $query_filters['x__source'] = intval($_GET['load__e']);
        }


        $x_coins = $CI->X_model->fetch($query_filters, array(), 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $x_coins[0]['totals'];

    }

    //Return Results:
    if($append_coin_icon){
        $e___12467 = $CI->config->item('e___12467'); //MENCH COINS
        return ( $count_query > 0 ? '<span title="'.$e___12467[$x__type]['m__title'].'" '.( $data_placement ? 'data-toggle="tooltip" data-placement="'.$data_placement.'"' : ''  ).' class="montserrat '.extract_icon_color($e___12467[$x__type]['m__icon']).'">'.( $append_name ? '' : $e___12467[$x__type]['m__icon'].'&nbsp;'  ).view_number($count_query).( $append_name ? '&nbsp'.$e___12467[$x__type]['m__title'] : '' ).'</span>' : null);
    } else {
        return intval($count_query);
    }

}


function view_i_x($i, $can_click, $common_prefix = null, $show_editor = false, $completion_rate = null)
{
    //See if user is logged-in:
    $CI =& get_instance();
    $user_session = superpower_unlocked();
    $user_e__id = ( (isset($_GET['load__e']) ? $_GET['load__e'] : ( $user_session ? $user_session['e__id'] : 0 ) ));
    $is_saved = ( isset($i['x__type']) && $i['x__type']==12896 );

    if(!$completion_rate){
        if($user_e__id){
            $completion_rate = $CI->X_model->completion_progress($user_e__id, $i);
        } else {
            $completion_rate['completion_percentage'] = 0;
        }
    }

    $i_stats = i_stats($i['i__metadata']);
    $e___12467 = $CI->config->item('e___12467'); //MENCH COINS
    $has_completion = $completion_rate['completion_percentage']>0;

    //Build View:
    $ui  = '<div id="x_save_'.$i['i__id'].'" '.( isset($i['x__id']) ? ' x__id="'.$i['x__id'].'" ' : '' ).' class="list-group-item no-side-padding '.( $show_editor ? ' cover_sort ' : '' ).( $can_click ? ' itemdiscover ' : '' ).'" style="padding-right:17px;">';

    //Give option to remove saved ideas:
    if($show_editor && $is_saved){
        $ui .= '<div class="note-editor edit-off">';
        $ui .= '<span class="show-on-hover">';
        $ui .= '<span><a href="javascript:void(0);" title="Unsave" data-toggle="tooltip" data-placement="left" onclick="x_save('.$i['i__id'].');$(\'#x_save_'.$i['i__id'].'\').remove();"><i class="fas fa-times"></i></a></span>';
        $ui .= '</span>';
        $ui .= '</div>';
    }

    $ui .= '<div class="row">';


        $ui .= '<div class="col-9 col-sm-10 col-md-8">';
            $ui .= ( $can_click ? '<a href="/' . $i['i__id'] .'" class="itemdiscover">' : '' );
            $ui .= '<span class="icon-block">'.( !$completion_rate['completion_percentage'] ? view_i_icon($i) : str_replace('idea','discover',view_i_icon($i)) ).'</span>';
            $ui .= '<b class="'.( $can_click ? ' montserrat ' : '' ).' i-url title-block">'.view_i_title($i, $common_prefix).'</b>';
            $ui .= ( $can_click ? '</a>' : '' );
        $ui .= '</div>';



        //MENCH COINS
        $ui .= '<div class="col-3 col-sm-2 col-md-4">';
            $ui .= '<div class="row">';
                $ui .= '<div class="col-md-4 show-max">'.view_coins_i(12274, $i).'</div>';
                $ui .= '<div class="col-md-4 col hideIfEmptymin">'.view_coins_i(12273, $i).'</div>';
                $ui .= '<div class="col-md-4 show-max">'.view_coins_i(6255, $i).'</div>';
            $ui .= '</div>';
        $ui .= '</div>';

    $ui .= '</div>';

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


    $ui = null;
    foreach($CI->X_model->fetch(array(
        'x__left' => $i__id,
        'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
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
            $ui .= $CI->X_model->message_send($msg['x__message'], false);
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

    if(!count($already_selected) && in_array($parent_e__id, $CI->config->item('n___13890'))){
        //FIND DEFAULT:
        foreach($CI->config->item('e___'.$parent_e__id) as $e__id2 => $m2){
            if(in_array($e__id2, $CI->config->item('n___13889') /* ACCOUNT DEFAULTS */ )){
                $already_selected = array($e__id2);
                break;
            }
        }
    }

    foreach($CI->config->item('e___'.$parent_e__id) as $e__id => $m) {
        $ui .= '<a href="javascript:void(0);" onclick="e_radio('.$parent_e__id.','.$e__id.','.$enable_mulitiselect.')" class="item'.extract_icon_color($m['m__icon']).' list-group-item montserrat itemsetting item-'.$e__id.' '.( $count>=$show_max ? 'extra-items-'.$parent_e__id.' hidden ' : '' ).( in_array($e__id, $already_selected) ? ' active ' : '' ). '"><span class="icon-block">'.$m['m__icon'].'</span>'.$m['m__title'].'<span class="change-results"></span></a>';
        $count++;
    }


    //Did we have too many items?
    if($count>=$show_max){
        //Show "Show more" button
        $ui .= '<a href="javascript:void(0);" class="list-group-item itemsource itemsetting montserrat extra-items-'.$parent_e__id.'" onclick="$(\'.extra-items-'.$parent_e__id.'\').toggleClass(\'hidden\')"><span class="icon-block"><i class="fas fa-search-plus"></i></span>Show '.($count-$show_max).' more</a>';
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


function view_i($i, $i_x_id = 0, $is_parent = false, $e_of_i = false, $message_input = null, $extra_class = null, $control_enabled = true)
{

    $CI =& get_instance();
    $user_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION

    //DISCOVER
    $x__id = ( isset($i['x__id']) ? $i['x__id'] : 0 );
    $is_i_link = ($x__id && in_array($i['x__type'], $CI->config->item('n___4486')));
    $e___6186 = $CI->config->item('e___6186');

    //IDEA
    $i_stats = i_stats($i['i__metadata']);
    $is_public = in_array($i['i__type'], $CI->config->item('n___7355'));
    $e_of_i = ( !$is_i_link ? false : $e_of_i ); //Disable Edits on Idea List Page
    $show_toolbar = ($control_enabled && superpower_active(12673, true));

    //IDEA INFO BAR
    $box_items_list = '';

    //DISCOVER STATUS
    if($x__id && !in_array($i['x__status'], $CI->config->item('n___7359'))){
        $e___6186 = $CI->config->item('e___6186');
        $box_items_list .= '<span class="inline-block"><span data-toggle="tooltip" data-placement="right" title="'.$e___6186[$i['x__status']]['m__title'].' @'.$i['x__status'].'">' . $e___6186[$i['x__status']]['m__icon'] . '</span>&nbsp;</span>';
    }


    $ui = '<div x__id="' . $x__id . '" i-id="' . $i['i__id'] . '" class="list-group-item no-side-padding itemidea itemidealist i_sortable paddingup level2_in object_saved saved_i_'.$i['i__id'] . ' i_line_' . $i['i__id'] . ' i__tr_'.$x__id.' '.$extra_class.'" style="padding-left:0;">';





    //EDITING TOOLBAR
    if($is_i_link && $control_enabled && $e_of_i){

        //RIGHT EDITING:
        $ui .= '<div class="note-editor edit-off '.superpower_active(10939).'">';
        $ui .= '<span class="show-on-hover">';

        if(!$is_parent){
            $ui .= '<span title="'.$e___11035[13908]['m__title'].'" class="sort_i">'.$e___11035[13908]['m__icon'].'</span>';
        }

        //Unlink:
        $ui .= '<span title="'.$e___11035[10686]['m__title'].'"><a href="javascript:void(0);" onclick="i_remove('.$i['i__id'].', '.$i['x__id'].', '.( $is_parent ? 1 : 0 ).')">'.$e___11035[10686]['m__icon'].'</a></span>';

        $ui .= '</span>';
        $ui .= '</div>';

    }






    $ui .= '<div class="row">';
    $ui .= '<div class="col-sm col-md">';

        //IDEA Transaction:
        $href = '/~'.$i['i__id'].( isset($_GET['load__e']) ? '?load__e='.intval($_GET['load__e']) : '' );

        //IDEA STATUS:
        $ui .= '<a href="'.$href.'" title="Weight: '.number_format($i['i__weight'], 0).'" class="icon-block">'.view_i_icon($i).'</a>';

        //IDEA TITLE
        if($is_i_link && $e_of_i){

            $ui .= view_input_text(4736, $i['i__title'], $i['i__id'], $e_of_i, (($i['x__sort']*100)+1));

        } else {

            $ui .= '<a href="'.$href.'" class="title-block montserrat">';
            $ui .= $box_items_list;
            $ui .= view_i_title($i, null); //IDEA TITLE
            $ui .= '</a>';

        }
    $ui .= '</div>';
    $ui .= '<div class="col-sm-6 col-md-4 col2nd">';
        //MENCH COINS
        $ui .= '<div class="row">';
            $ui .= '<div class="col-4">'.view_coins_i(12274, $i).'</div>';
            $ui .= '<div class="col-4">'.view_coins_i(12273, $i).'</div>';
            $ui .= '<div class="col-4">'.view_coins_i(6255,  $i).'</div>';
        $ui .= '</div>';

    $ui .= '</div>';
    $ui .= '</div>';



    if($message_input && trim($message_input)!=$CI->uri->segment(1)){
        $ui .= '<div class="i-footer hideIfEmpty">' . $CI->X_model->message_send($message_input, false, $user_e) . '</div>';
    }


    if($show_toolbar){

        //Idea Toolbar
        $ui .= '<div class="space-content ' . superpower_active(12673) . '" style="padding-left:25px; padding-top:13px;">';

        $ui .= $box_items_list;

        //IDEA STATUS
        $ui .= '<div class="inline-block">' . view_input_dropdown(4737, $i['i__type'], null, $e_of_i, false, $i['i__id']) . ' </div>';




        if($x__id){

            $x__metadata = unserialize($i['x__metadata']);

            //IDEA LINK BAR
            $ui .= '<span class="' . superpower_active(12700) . '">';

            //LINK TYPE
            $ui .= view_input_dropdown(4486, $i['x__type'], null, $e_of_i, false, $i['i__id'], $i['x__id']);

            //LINK MARKS
            $ui .= '<span class="x_marks account_4228 '.( $i['x__type']==4228 ? : 'hidden' ).'">';
            $ui .= view_input_text(4358, ( isset($x__metadata['tr__assessment_points']) ? $x__metadata['tr__assessment_points'] : '' ), $i['x__id'], $e_of_i, ($i['x__sort']*10)+2 );
            $ui .='</span>';


            //LINK CONDITIONAL RANGE
            $ui .= '<span class="x_marks account_4229 '.( $i['x__type']==4229 ? : 'hidden' ).'">';
            //MIN
            $ui .= view_input_text(4735, ( isset($x__metadata['tr__conditional_score_min']) ? $x__metadata['tr__conditional_score_min'] : '' ), $i['x__id'], $e_of_i, ($i['x__sort']*10)+3);
            //MAX
            $ui .= view_input_text(4739, ( isset($x__metadata['tr__conditional_score_max']) ? $x__metadata['tr__conditional_score_max'] : '' ), $i['x__id'], $e_of_i, ($i['x__sort']*10)+4);
            $ui .= '</span>';
            $ui .= '</span>';

        }

        $ui .= '</div>';

    }

    $ui .= '</div>';



    return $ui;

}




function view_caret($e__id, $m, $s__id){
    //Display drop down menu:
    $CI =& get_instance();

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

        $ui .= '<a '.$href.' class="dropdown-item montserrat '.extract_icon_color($m2['m__icon']).' '.( count($superpower_actives2) ? superpower_active(end($superpower_actives2)) : '' ).'"><span class="icon-block">'.view_e__icon($m2['m__icon']).'</span> '.$m2['m__title'].'</a>';
    }
    $ui .= '</div>';
    $ui .= '</li>';

    return $ui;
}


function view_i_list($list_e__id, $in_my_x, $i, $is_next, $user_e){

    //If no list just return the next step:
    if(!count($is_next)){
        return false;
    }

    //List children so they know what's ahead:
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $e___12467 = $CI->config->item('e___12467'); //MENCH COINS
    $common_prefix = i_calc_common_prefix($is_next, 'i__title');
    $ui = '';

    if($list_e__id > 0){
        $ui .= '<div class="headline"><span class="icon-block">'.$e___11035[$list_e__id]['m__icon'].'</span>'.$e___11035[$list_e__id]['m__title'].'</div>';
    }


    $ui .= '<div class="list-group">';
    $is_last_continious_complete = true;
    $counter = 0;
    foreach($is_next as $key => $next_i){
        $completion_rate = $CI->X_model->completion_progress($user_e['e__id'], $next_i);
        $ui .= view_i_x($next_i, ( $completion_rate['completion_percentage'] > 0 || i_is_featured($next_i) ), $common_prefix, false, $completion_rate);

        //Search for the first unlocked idea right after the first stack of continuously completed ideas
        $is_last_continious_complete = ( $is_last_continious_complete && $completion_rate['completion_percentage']>=100 ? true : false );
        $counter = ( $is_last_continious_complete ? 0 : $counter+1 );
    }
    $ui .= '</div>';
    $ui .= '<div class="doclear">&nbsp;</div>';

    return $ui;

}


function view_i_note_list($x__type, $i_notes, $e_of_i, $show_empty_error = false, $show_headline = true){

    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');
    $e___4485 = $CI->config->item('e___4485'); //IDEA NOTES
    $supports_emoji = (in_array($x__type, $CI->config->item('n___14038')));
    $handles_uploads = (in_array($x__type, $CI->config->item('n___12359')));
    $handles_url = (in_array($x__type, $CI->config->item('n___7551')) || in_array($x__type, $CI->config->item('n___4986')));
    $user_e = superpower_unlocked();
    $ui = '';

    //Header:
    if($show_headline){
        $ui .= '<div class="headline"><span class="icon-block">'.$e___4485[$x__type]['m__icon'].'</span>'.$e___4485[$x__type]['m__title'].'</div>';
    }

    if($show_empty_error && !count($i_notes) && $e_of_i){
        $ui .= '<div class="no_notes_' . $x__type .'" style="margin-bottom:13px;">';
        $ui .= '<div class="msg alert alert-warning" role="alert"><span class="icon-block">&nbsp;</span>No '.ucwords(strtolower($e___4485[$x__type]['m__title'])).' yet</div>';
        $ui .= '</div>';
    }

    //Show no-Message notifications for each message type:
    $ui .= '<div id="i_notes_list_'.$x__type.'" class="list-group">';

    //List current notes:
    foreach($i_notes as $i_notes) {
        $ui .= view_i_note($x__type, $i_notes, ($i_notes['x__source']==$user_e['e__id'] || $e_of_i));
    }

    //ADD NEW:
    if(!in_array($x__type, $CI->config->item('n___12677')) && $e_of_i){

        $color_code = trim(extract_icon_color($e___4485[$x__type]['m__icon']));

        $ui .= '<div class="list-group-item no-side-padding add_notes_' . $x__type .'">';
        $ui .= '<div class="add_notes_form">';
        $ui .= '<form class="box box' . $x__type . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">';



        $ui .= '<textarea onkeyup="i_note_count_new('.$x__type.')" class="form-control msg note-textarea algolia_search new-note '.( $supports_emoji ? 'emoji-input' : '' ).' input_note_'.$x__type.'" note_type_id="' . $x__type . '" id="x__message' . $x__type . '" placeholder="WRITE'.( $handles_uploads ? ', DROP FILE' : '' ).( $handles_url ? ', PASTE URL, @SOURCE' : '' ).'" style="margin-top:6px;"></textarea>';


        //Response result:
        $ui .= '<div class="note_error_'.$x__type.' hideIfEmpty discover msg alert alert-danger" style="margin:8px 0;"></div>';


        $ui .= '<table class="table table-condensed" style="margin-top: 10px;"><tr>';


        //ADD
        $ui .= '<td class="table-btn"><a href="javascript:i_note_text('.$x__type.');" class="btn btn-'.$color_code.' save_notes_'.$x__type.'"><i class="fas fa-plus"></i></a></td>';


        //UPLOAD
        if($handles_uploads){
            $ui .= '<td class="table-btn first_btn">';
            $ui .= '<label class="hidden"></label>'; //To catch & store unwanted uploaded file name
            $ui .= '<label class="btn btn-grey file_label_'.$x__type.'" for="fileIdeaType'.$x__type.'" data-toggle="tooltip" title="'.$e___11035[13572]['m__title'].' '.$e___11035[13572]['m__message'].'" data-placement="top"><span class="icon-block">'.$e___11035[13572]['m__icon'].'</span></label>';
            $ui .= '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType'.$x__type.'" />';
            $ui .= '</td>';
        }

        if($supports_emoji){
            //EMOJI
            $ui .= '<td class="table-btn first_btn"><span class="btn btn-grey" id="emoji_pick_type'.$x__type.'" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__icon'].'</span></span></td>';
        }

        //File counter:
        $ui .= '<td style="padding:10px 0 0 0;"><span id="ideaNoteNewCount' . $x__type . '" class="hidden some-text"><span id="charNum' . $x__type . '">0</span>/' . view_memory(6404,4485).' CHARACTERS</span></td>';

        $ui .= '</tr></table>';



        $ui .= '</form>';
        $ui .= '</div>';
        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}

function view_shuffle_message($e__id){
    $CI =& get_instance();
    $e___12687 = $CI->config->item('e___12687');
    $line_messages = explode("\n", $e___12687[$e__id]['m__message']);
    return $line_messages[rand(0, (count($line_messages) - 1))];
}

function view_unauthorized_message($superpower_e__id = 0){

    $user_e = superpower_unlocked($superpower_e__id);

    if(!$user_e){
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

function view_i_cover($x__type, $i, $show_editor, $message_input = null, $focus_e = false){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION
    $user_input = $focus_e;
    $user_session = superpower_unlocked();
    $discovery_mode = $x__type==6255;
    $can_click = !strlen($message_input); //Otherwise top part would show content

    if(!$focus_e){
        $focus_e = $user_session;
    }

    $completion_rate['completion_percentage'] = 0; //Assume no progress
    if($focus_e && $discovery_mode){
        $completion_rate = $CI->X_model->completion_progress($focus_e['e__id'], $i);
    }

    $i_stats = i_stats($i['i__metadata']);
    $href = ( $discovery_mode ? ( $user_input && $focus_e['e__id']!=$user_session['e__id'] ? '/~'.$i['i__id'].'?load__e='.$focus_e['e__id'] : '/'.$i['i__id'] ) : '/i/i_go/'.$i['i__id'] . ( isset($_GET['load__e']) ? '?load__e='.intval($_GET['load__e']) : '' ));



    $ui  = '<div '.( isset($i['x__id']) ? ' x__id="'.$i['x__id'].'" ' : '' ).' class="col-md-4 col-sm-6 i_class_'.$x__type.'_'.$i['i__id'].' no-padding '.( $show_editor ? ' cover_sort ' : '' ).'">';
    $ui .= '<div class="cover-wrapper '.( $discovery_mode ? ( $completion_rate['completion_percentage']<100 ? 'wrap-discover' : '' /* grey */ ) : 'wrap-idea' ).'">';
    $ui .= ( $can_click ? '<a href="'.$href.'"' : '<div' ).' class="cover-link" style="background-image:url(\''.i_fetch_cover($i['i__id']).'\');">';

    if($completion_rate['completion_percentage']>0){
        $ui .= '<span class="cover-progress">'.view_x_progress($completion_rate, $i, true).'</span>';
    }

    //EDITING TOOLBAR
    if($show_editor){

        //SORT
        $sort_id = ( $discovery_mode ? 6132 : 13412 );
        $ui .= '<span class="inside-btn top-left x_sort" title="'.$e___11035[$sort_id]['m__title'].'">'.$e___11035[$sort_id]['m__icon'].'</span>';

        //REMOVE
        $remove_id = ( $discovery_mode ? 6155 : 13415 );
        $ui .= '<span class="inside-btn top-right x_remove" title="'.$e___11035[$remove_id]['m__title'].'" i__id="'.$i['i__id'].'" x__type="'.$x__type.'">'.$e___11035[$remove_id]['m__icon'].'</span>';

    }

    if($message_input){
        $ui .= '<div class="cover-content">'.$message_input.'</div>';
    }

    $ui .= ( $can_click ? '</a>' : '</div>' );
    $ui .= '</div>';
    $ui .= '<div class="cover-text"><a href="'.$href.'" class="montserrat">';
    $ui .= view_i_title($i);
    $ui .= '<div style="padding:5px 0;">'.view_i_tree_stats($i_stats, false).'</div>';
    $ui .= '</a></div>';
    $ui .= '</div>';

    return $ui;

}

function view_x_progress($completion_rate, $i, $show_micro){

    $ui = ( !$show_micro ? '<div class="progress-title">'.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' IDEAS DISCOVERED</div><div class="doclear">&nbsp;</div>' : '' ); // '.$completion_rate['completion_percentage'].'%

    if($completion_rate['steps_total'] < 55 && !$show_micro){

        $ui .= '<ul class="story-bar '.( 1 ? ' compact ' : '' ).'">';
        for($i=0;$i<$completion_rate['steps_total'];$i++){
            $ui .= '<li class="'.( $i<$completion_rate['steps_completed'] ? 'active' : ''  ).'" title="IDEA '.($i+1).'/'.$completion_rate['steps_total'].'"></li>';
        }
        $ui .= '</ul>';

    } else {

        $ui .= '<div class="progress-bg-list" style="'.( $show_micro ? 'width:100%;' : 'margin-left:41px;' ).'"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%"></div></div>';

    }

    return $ui;

}



function view_e($e, $is_parent = false, $extra_class = null, $control_enabled = false, $source_of_e = false, $common_prefix = null, $message_input = null)
{

    $CI =& get_instance();
    $user_e = superpower_unlocked();
    $e___6177 = $CI->config->item('e___6177'); //Source Status
    $e___4592 = $CI->config->item('e___4592');
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $e___11035 = $CI->config->item('e___11035'); //MENCH NAVIGATION

    $focus_e__id = ( substr($CI->uri->segment(1), 0, 1)=='@' ? intval(substr($CI->uri->segment(1), 1)) : 0 );
    $x__id = (isset($e['x__id']) ? $e['x__id'] : 0);
    $is_e_link = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___4592')));
    $is_note = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___4485')));
    $is_x_progress = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___12227')));
    $superpower_10939 = superpower_active(10939, true);
    $superpower_12706 = superpower_active(12706, true);
    $superpower_13422 = superpower_active(13422, true);
    $source_of_e = ( $superpower_13422 ? true : $source_of_e );

    $e__profiles = $CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__up !=' => $focus_e__id, //Do Not Fetch Current Source
        'x__down' => $e['e__id'], //This child source
        'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'e__type IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__up'), 0, 0, array('e__weight' => 'DESC'));

    $is_public = in_array($e['e__type'], $CI->config->item('n___7357'));
    $is_x_published = ( !$x__id || in_array($e['x__status'], $CI->config->item('n___7359')));
    //Allow source to see all their own transactions:
    $is_hidden = (!$user_e || $user_e['e__id']!=$focus_e__id) && (filter_array($e__profiles, 'e__id', '4755') || in_array($e['e__id'], $CI->config->item('n___4755')));
    $e_url = '/@'.$e['e__id'];


    if($is_hidden && !superpower_active(12701, true)){
        //PRIVATE INFORMATION:
        return '<div class="list-group-item no-side-padding itemsource '. $extra_class  . '"><span class="icon-block">'.$e___11035[4755]['m__icon'].'</span>'.$e___11035[4755]['m__title'].'</div>';
    }


    //SOURCE INFO BAR
    $box_items_list = '';

    //SOURCE STATUS
    if(!$is_public){
        $box_items_list .= '<span class="inline-block e__type_' . $e['e__id'].'"><span data-toggle="tooltip" data-placement="right" title="'.$e___6177[$e['e__type']]['m__title'].' @'.$e['e__type'].'">' . $e___6177[$e['e__type']]['m__icon'] . '</span>&nbsp;</span>';
    }

    //DISCOVER STATUS
    if($x__id){
        if(!$is_x_published){
            $box_items_list .= '<span class="inline-block x__status_' . $x__id .'"><span data-toggle="tooltip" data-placement="right" title="'.$e___6186[$e['x__status']]['m__title'].' @'.$e['x__status'].'">' . $e___6186[$e['x__status']]['m__icon'] . '</span>&nbsp;</span>';
        }
    }


    //ROW
    $ui = '<div class="list-group-item no-side-padding itemsource en-item object_saved saved_e_'.$e['e__id'].' e__id_' . $e['e__id'] . ( $x__id > 0 ? ' tr_' . $e['x__id'].' ' : '' ) . ' '. $extra_class  . '" e__id="' . $e['e__id'] . '" x__id="'.$x__id.'">';



    if($control_enabled && $source_of_e && ($is_e_link || $is_note)){

        //RIGHT EDITING:
        $ui .= '<div class="note-editor edit-off">';
        $ui .= '<span class="show-on-hover">';

        $main_controller = $is_e_link && $superpower_13422;
        if($main_controller){

            //Sort
            if(!$is_parent && $superpower_10939){
                $ui .= '<span title="'.$e___11035[13911]['m__title'].'" class="sort_e hidden">'.$e___11035[13911]['m__icon'].'</span>';
            }

            //Edit Raw Source
            $ui .= '<span><a href="javascript:void(0);" onclick="e_modify_load(' . $e['e__id'] . ',' . $x__id . ')" title="'.$e___11035[13571]['m__title'].'">'.$e___11035[13571]['m__icon'].'</a></span>';

        }

        if($superpower_10939 && (($source_of_e && !$is_parent) || $superpower_13422)){

            //UNLINK SOURCE
            $ui .= '<span class="'.( $main_controller ? ' show-max ' : '' ).'"><a href="javascript:void(0);" onclick="remove_10673(' . $x__id . ', '.$e['x__type'].')" title="'.$e___11035[10673]['m__title'].'">'.$e___11035[10673]['m__icon'].'</a></span>';

        }

        $ui .= '</span>';
        $ui .= '</div>';

    }




    $ui .= '<div class="row">';


        $ui .= '<div class="col-9 col-sm-10 col-md-8">';

            //SOURCE ICON
            $ui .= '<a href="'.$e_url.'" '.( $is_e_link ? ' title="TRANSACTION ID '.$e['x__id'].' TYPE @'.$e['x__type'].' SORT '.$e['x__sort'].' WEIGHT '.$e['e__weight'].'" ' : '' ).'><span class="icon-block e_ui_icon_' . $e['e__id'] . ' e__icon_'.$e['e__id'].'">' . view_e__icon($e['e__icon']) . '</span></a>';


            //SOURCE TITLE TEXT EDITOR
            if($user_e && $source_of_e && $is_e_link){

                $ui .= view_input_text(6197, $e['e__title'], $e['e__id'], $source_of_e, 0, false, null, extract_icon_color($e['e__icon']));

                if($superpower_12706){
                    $ui .= '<div class="space-content">'.$box_items_list.'</div>';
                }

            } else {

                //SOURCE NAME with PREFIX
                $ui .= '<a href="'.$e_url.'" class="title-block title-no-right montserrat '.extract_icon_color($e['e__icon']).'">';
                $ui .= $box_items_list;
                $ui .= '<span class="text__6197_' . $e['e__id'] . '">'.( $common_prefix ? str_replace($common_prefix, '', $e['e__title']) : $e['e__title'] ).'</span>';
                $ui .= '</a>';

            }
        $ui .= '</div>';



        $ui .= '<div class="col-3 col-sm-2 col-md-4">';

            //MENCH COINS
            $ui .= '<div class="row">';
                $ui .= '<div class="col-md-4 show-max">'.view_coins_e(12274, $e['e__id']).'</div>';
                $ui .= '<div class="col-md-4 col hideIfEmptymin">'.view_coins_e(12273, $e['e__id']).'</div>';
                $ui .= '<div class="col-md-4 show-max">'.view_coins_e(6255, $e['e__id']).'</div>';
            $ui .= '</div>';

        $ui .= '</div>';





    $ui .= '</div>';






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



    //DISCOVERY TOOLBAR
    if($is_x_progress && superpower_active(13758, true)){
        $ui .= '<div class="message_content paddingup x__message block">';

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
            $ui .= $CI->X_model->message_send($e['x__message'], false);
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
    $attributes = ( $e_of_i ? '' : 'disabled' ).' spellcheck="false" tabindex="'.$tabindex.'" old-value="'.$current_value.'" id="input_'.$cache_e__id.'_'.$s__id.'" class="form-control dotransparent montserrat inline-block x_set_text text__'.$cache_e__id.'_'.$s__id.($extra_large?' texttype__lg ' : ' texttype__sm ').' text_e_'.$cache_e__id.' '.$append_css.'" cache_e__id="'.$cache_e__id.'" s__id="'.$s__id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $focus_element = '<textarea name="'.$name.'" onkeyup="view_input_text_count('.$cache_e__id.','.$s__id.')" placeholder="'.$e___12112[$cache_e__id]['m__title'].'" '.$attributes.'>'.$current_value.'</textarea>';
        $character_counter = '<div class="title_counter title_counter_'.$cache_e__id.'_'.$s__id.' hidden grey montserrat doupper" style="text-align: right;"><span id="current_count_'.$cache_e__id.'_'.$s__id.'">0</span>/'.view_memory(6404,$cache_e__id).' CHARACTERS</div>';
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

    //data-toggle="tooltip" data-placement="top" title="'.$e___4527[$cache_e__id]['m__title'].'"
    $ui = '<div title="'.$e___12079[$cache_e__id]['m__title'].'" data-toggle="tooltip" data-placement="top" class="inline-block">';
    $ui .= '<div class="dropdown inline-block dropd_'.$cache_e__id.'_'.$i__id.'_'.$x__id.' '.( !$show_full_name ? ' icon-block ' : '' ).'" selected-val="'.$selected_e__id.'">'; //dropup

    $ui .= '<button type="button" '.( $e_of_i ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' '.$btn_class.'" id="dropdownMenuButton'.$cache_e__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn adj-btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked  '.$btn_class.'"' ).' >';

    $ui .= '<span class="icon-block">' .$e___this[$selected_e__id]['m__icon'].'</span><span class="show-max">'.( $show_full_name ?  $e___this[$selected_e__id]['m__title'] : '' ).'</span>';

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton'.$cache_e__id.'">';

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

        $ui .= '<a class="dropdown-item dropi_'.$cache_e__id.'_'.$i__id.'_'.$x__id.' montserrat optiond_'.$e__id.'_'.$i__id.'_'.$x__id.' doupper '.( $e__id==$selected_e__id ? ' active ' : ( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ) ).'" '.$anchor_url.' title="'.$m['m__message'].'"><span class="icon-block">'.$m['m__icon'].'</span>'.$m['m__title'].'</a>'; //Used to show desc but caused JS click conflict sp retired for now: ( strlen($m['m__message']) && !$is_url_desc ? 'title="'.$m['m__message'].'" data-toggle="tooltip" data-placement="right"' : '' )

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

function view__s($count, $is_e = 0)
{
    //A cute little function to either display the plural "s" or not based on $count
    return ( intval($count) == 1 ? '' : ($is_e ? 'es' : 's'));
}

