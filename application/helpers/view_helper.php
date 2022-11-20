<?php


function view_show_more($see_more_type, $class, $href_link = null){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
    return '<div class="coin_cover coin_reverse col-xl-2 col-lg-3 col-md-4 col-6 no-padding '.$class.'">
                                <div class="cover-wrapper"><a '.( $href_link ? 'href="'.$href_link.'"' : 'href="javascript:void(0);" onclick="$(\'.'.$class.'\').toggleClass(\'hidden\')"' ).' class="black-background-obs cover-link"><div class="cover-btn">'.$e___11035[$see_more_type]['m__cover'].'</div></a></div>
                            </div>';
}


function view_load_page_i($x__type, $page, $limit, $list_count, $extra_class = null)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');
    $href = 'href="javascript:void(0);" onclick="view_load_page_i('.$x__type.',' . $page . ', 0)"';
    return '<div class="coin_cover coin_reverse col-xl-2 col-lg-3 col-md-4 col-6 no-padding load-more '.$extra_class.'">
                                <div class="cover-wrapper"><a '.$href.' class="black-background-obs cover-link"><div class="cover-btn">'.$e___11035[14538]['m__cover'].'</div></a></div>
                            </div>';
}
function view_load_page_e($x__type, $page, $limit, $list_count, $extra_class = null)
{
    /*
     * Gives an option to "Load More" sources when we have too many to show in one go
     * */

    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');
    $href = 'href="javascript:void(0);" onclick="view_load_page_e('.$x__type.',' . $page . ', 0)"';
    return '<div class="coin_cover coin_reverse col-xl-2 col-lg-3 col-md-4 col-6 no-padding load-more '.$extra_class.'">
                                <div class="cover-wrapper"><a '.$href.' class="black-background-obs cover-link"><div class="cover-btn">'.$e___11035[14538]['m__cover'].'</div></a></div>
                            </div>';
}

function view_db_field($field_name){

    //Takes a database field name and returns a human-friendly version
    return ucwords(str_replace('i__', '', str_replace('e__', '', str_replace('x__', '', $field_name))));

}


function view_x__message($x__message, $x__type, $full_message = null, $has_discovery_mode = false)
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


    } elseif ($x__type == 26155 /* Idea */) {

        $ideas = $CI->I_model->fetch(array(
            'i__id' => substr($x__message, 1),
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
        ));
        if(count($ideas)){
            return '<div><a href="/i/i_go'.$x__message.'" target="_blank" class="ignore-click" style="font-size:0.89em;">'.$ideas[0]['i__title'].'</a></div>';
        } else {
            return $x__message.' ⚠️ INVALID ID';
        }

    } elseif ($x__type == 26090 /* Source */) {

        $sources = $CI->E_model->fetch(array(
            'e__id' => substr($x__message, 1),
            'e__type IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
        ));
        if(count($sources)){
            return '<div><span class="icon-block-xs">'.view_cover(12274,$sources[0]['e__cover'], true). '</span><a href="/'.$x__message.'" target="_blank" class="ignore-click" style="font-size:0.89em;">'.$sources[0]['e__title'].'</a></div>';
        } else {
            return $x__message.' ⚠️ INVALID ID';
        }

    } elseif ($x__type == 26092 /* CAD */) {

        return str_replace('CAD ','$',$x__message);

    } elseif ($x__type == 26091 /* USD */) {

        return str_replace('USD ','$',$x__message);

    } elseif ($x__type == 4260 /* Image URL */) {

        return '<img '.( $has_discovery_mode ? ' src="' . $x__message . '" class="content-image" ' : ' data-src="' . $x__message . '" src="https://s3foundation.s3-us-west-2.amazonaws.com/f9ad9c2e7c18abd949a0119d621bf00f.gif" class="content-image lazyimage" ' ).' alt="IMAGE" />';

    } elseif ($x__type == 4259 /* Audio URL */) {

        return  '<audio controls src="' . $x__message . '">Your Browser Does Not Support Audio</audio>' ;

    } elseif ($x__type == 4258 /* Video URL */) {

        return  '<video width="100%" class="play_video" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="' . $x__message . '" type="video/mp4"></video>' ;

    } elseif ($x__type == 4261 /* File URL */) {

        $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
        return '<a href="' . $x__message . '" class="btn btn-12273" target="_blank" class="ignore-click">'.$e___11035[13573]['m__cover'].' '.$e___11035[13573]['m__title'].'</a>';

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
        $has_embed = (substr_count($url, 'youtube.com/embed/') == 1);

        if (!substr_count($url, '&list=') && ((substr_count($url, 'youtube.com/watch') == 1) || substr_count($url, 'youtu.be/') == 1 || $has_embed)) {

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
                    $embed_html_code .= '<div class="css__title subtle-line mini-grey">'.( $seconds<60 ? $seconds.' SEC.' : round_minutes($seconds).' MIN' ).' <span class="inline-block">FROM '.view_time_hours($start_time, true).' TO '.view_time_hours($end_time, true).'</span></div>';
                }

                $embed_html_code .= '<div class="media-content ignore-click"><div class="ytframe video-sorting" style="margin-top:5px;"><iframe id="youtubeplayer'.$video_id.'"  src="//www.youtube.com/embed/' . $video_id . '?wmode=opaque&theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_time . ($end_time ? '&end=' . $end_time : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div><div class="doclear">&nbsp;</div></div>';

            }

        } elseif (substr_count($url, 'facebook.com/') == 1 && substr_count($url, '/videos/') == 1 && is_numeric(one_two_explode('/videos/','/',$url))) {

            $video_id = trim(one_two_explode('/videos/','/',$url));
            $clean_url = $url;
            $embed_html_code = '<div class="media-content ignore-click"><div class="ytframe video-sorting" style="margin-top:5px;"><iframe src="https://www.facebook.com/plugins/video.php?href='.urlencode($url).'&show_text=false&t=0" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe></div></div>';

        } elseif (substr_count($url, 'vimeo.com/') == 1 && is_numeric(one_two_explode('vimeo.com/','?',$url))) {

            //Seems to be Vimeo:
            $video_id = trim(one_two_explode('vimeo.com/', '?', $url));
            $clean_url = 'https://vimeo.com/' . $video_id;
            $embed_html_code = '<div class="media-content ignore-click"><div class="ytframe video-sorting" style="margin-top:5px;"><iframe src="https://user.vimeo.com/video/' . $video_id . '?title=0&byline=0" class="vm-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div><div class="doclear">&nbsp;</div></div>';

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

function view_i_title($i){

    $CI =& get_instance();
    $hide_title = false;
    return '<span class="text__4736_'.$i['i__id'].' css__title '.( $hide_title ? ' hidden ' : '').'">'.htmlentities(trim($i['i__title'])).'</span>';
}


function view_i_note($x__type, $has_discovery_mode, $x, $note_e = false)
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
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $e___11035 = $CI->config->item('e___11035');
    $supports_emoji = (in_array($x__type, $CI->config->item('n___14990')));
    $referenced_ideas = (in_array($x__type, $CI->config->item('n___13550')));
    $editable_discovery = (in_array($x__type, $CI->config->item('n___14043')));


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item is-msg note_sortable msg_e_type_' . $x['x__type'] . '" id="ul-nav-' . $x['x__id'] . '" x__id="' . $x['x__id'] . '">'; //title="'.$x['e__title'].' Posted On '.substr($x['x__time'], 0, 19).'" data-toggle="tooltip" data-placement="top"
    $ui .= '<div style="overflow:visible !important;">';

    if($editable_discovery && isset($x['e__id'])){
        //Show member:
        $ui .= view_e_line($x);
    }

    //Type & Delivery Method:
    $ui .= '<div class="text_message edit-off" id="msgbody_' . $x['x__id'] . '">';
    $ui .= $CI->X_model->message_view($x['x__message'], $has_discovery_mode, $member_e, $x['x__right']);
    $ui .= '</div>';

    //Editing menu:
    if($note_e){

        $ui .= '<div class="note-editor edit-off"><span class="">'; //show-on-hover

            //SORT NOTE
            if(in_array($x['x__type'], $CI->config->item('n___4603'))){
                $ui .= '<span title="'.$e___11035[13909]['m__title'].'" class="i_note_sorting">'.$e___11035[13909]['m__cover'].'</span>';
            }

            //MODIFY NOTE
            $ui .= '<span title="'.$e___11035[13574]['m__title'].'"><a href="javascript:void(0);" class="load_i_note_editor '.( $supports_emoji ? 'load_emoji_editor' : '' ).'" x__id="' . $x['x__id'] . '" onclick="load_i_note_editor(' . $x['x__id'] . ');">'.$e___11035[13574]['m__cover'].'</a></span>';

            //REMOVE NOTE
            $ui .= '<span title="'.$e___11035[13579]['m__title'].'"><a href="javascript:void(0);" onclick="i_remove_note(' . $x['x__id'] . ', '.$x['x__type'].')">'.$e___11035[13579]['m__cover'].'</a></span>';

        $ui .= '</span></div>';


        //Text editing:
        $ui .= '<textarea onkeyup="count_13574(' . $x['x__id'] . ')" name="x__message'.$x['x__id'].'" id="message_body_' . $x['x__id'] . '" class="edit-on hidden msg note-textarea edit-note algolia_search" x__id="'.$x['x__id'].'" placeholder="'.stripslashes($x['x__message']).'">' . $x['x__message'] . '</textarea>';


        //Update result & Show potential errors
        $ui .= '<div class="edit-updates hideIfEmpty"></div>';


        //Editing menu:
        $ui .= '<table class="table table-condensed edit-on hidden" style="margin:0 41px 0;"><tr>';


        //SAVE
        $ui .= '<td class="table-btn"><a class="btn btn-default" href="javascript:i_note_update_text(' . $x['x__id'] . ',' . $x['x__type'] . ');" title="'.$e___11035[14039]['m__title'].'">'.$e___11035[14039]['m__cover'].'</a></td>';

        //CANCEL
        $ui .= '<td class="table-btn first_btn"><a class="btn btn-compact btn-grey" title="'.$e___11035[13502]['m__title'].'" href="javascript:cancel_13574(' . $x['x__id'] . ');">'.$e___11035[13502]['m__cover'].'</a></td>';

        if($supports_emoji){
            //EMOJI
            $ui .= '<td class="table-btn emoji_edit hidden first_btn"><span class="btn btn-compact btn-grey" id="emoji_pick_id'.$x['x__id'].'" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__cover'].'</span></span></td>';
        }


        //TEXT COUNTER
        $ui .= '<td style="padding:10px 0 0 0;"><span id="NoteCounter' . $x['x__id'] . '" class="hidden some-text"><span id="charEditingNum' . $x['x__id'] . '">0</span>/' . view_memory(6404,4485) . ' CHARACTERS</span></td>';

        $ui .= '</tr></table>';

    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function view_cover($coin__type, $cover_code, $noicon_default = null, $icon_prefix = '')
{

    $valid_url = ( filter_var($cover_code, FILTER_VALIDATE_URL) || substr($cover_code, 0, 2)=='//' );

    //A simple function to display the Member Icon OR the default icon if not available:
    if($valid_url && $noicon_default){

        return $icon_prefix.'<div class="img" style="background-image:url(\''.$cover_code.'\');"></div>';

    } elseif($valid_url){

        return $icon_prefix.'<img src="'.$cover_code.'"'.( substr_count($cover_code, 'class=') ? ' class="'.str_replace(',',' ',one_two_explode('class=','&', $cover_code)).'" ' : '' ).'/>';

    } elseif (string_is_icon($cover_code)) {

        return $icon_prefix.'<i class="'.$cover_code.'"></i>';

    } elseif(strlen($cover_code)) {

        return $icon_prefix.$cover_code;

    } elseif($noicon_default) {

        return $icon_prefix.$noicon_default;

    } else {

        //Standard Icon if none:
        return null;
        //return '<i class="fas fa-circle zq'.$coin__type.'"></i>';
        //return '<img src="/img/'.$coin__type.'.png" />';

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
            'decimals' => 0,
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
            'decimals' => 0,
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


function view_x($x, $has_x__reference = false)
{

    $CI =& get_instance();
    $e___4593 = $CI->config->item('e___4593'); //Transaction Type
    $e___4341 = $CI->config->item('e___4341'); //Transaction Table
    $e___6186 = $CI->config->item('e___6186'); //Transaction Status
    $e___14870 = $CI->config->item('e___14870'); //Hosted Domains
    $member_e = superpower_unlocked();
    $superpower_css_12701 = superpower_active(12701); //SUPERPOWER OF DISCOVERY GLASSES
    $add_e = $CI->E_model->fetch(array(
        'e__id' => $x['x__source'],
    ));




    //Display the item
    $ui = '<div class="x-list">';


    //ID
    $ui .= '<div class="simple-line"><a href="/-4341?x__id='.$x['x__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4367]['m__title'].'" class="mono-space"><span class="icon-block">'.$e___4341[4367]['m__cover']. '</span>'.$x['x__id'].'</a></div>';


    //SOURCE
    $ui .= '<div class="simple-line"><a href="/@'.$add_e[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4364]['m__title'].'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[4364]['m__cover']. '</span><span class="icon-block">'.view_cover(12274,$add_e[0]['e__cover'], true) . '</span>' . $add_e[0]['e__title'] . '</a></div>';


    //HIDE PRIVATE INFO?
    if(in_array($x['e__type'] , $CI->config->item('n___30956')) && (!$member_e || $x['x__source']!=$member_e['e__id']) && !superpower_active(12701, true) && $add_e[0]['e__id']!=14068){

        //Hide Information:
        $ui .= '<div class="simple-line"><span data-toggle="tooltip" class="css__title" data-placement="top" title="Details are kept private"><span class="icon-block"><i class="fal fa-eye-slash"></i></span>PRIVATE INFORMATION</span></div>';
        $ui .= '</div>'; //Premature close & return
        return $ui;

    } elseif(!isset($e___4593[$x['x__type']])){

        //We've probably have not yet updated php cache, set error:
        $e___4593[$x['x__type']] = array(
            'm__cover' => '<i class="fas fa-exclamation-circle"></i>',
            'm__title' => 'Transaction Type Not Synced in PHP Cache',
            'm__message' => '',
            'm__profile' => array(),
        );

    }


    //TIME
    $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $e___4341[4362]['m__title'].': '.$x['x__time'] . ' PST"><span class="icon-block">'.$e___4341[4362]['m__cover']. '</span>' . view_time_difference(strtotime($x['x__time'])) . ' Ago</span></div>';


    //STATUS
    $ui .= '<div class="simple-line '.$superpower_css_12701.'"><a href="/@'.$x['x__status'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[6186]['m__title'].( strlen($e___6186[$x['x__status']]['m__message']) ? ': '.$e___6186[$x['x__status']]['m__message'] : '' ).'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[6186]['m__cover']. '</span><span class="icon-block">'.$e___6186[$x['x__status']]['m__cover'].'</span>'.$e___6186[$x['x__status']]['m__title'].'</a></div>';


    //TYPE
    $ui .= '<div class="simple-line"><a href="/@'.$x['x__type'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4593]['m__title'].( strlen($e___4593[$x['x__type']]['m__message']) ? ': '.$e___4593[$x['x__type']]['m__message'] : '' ).'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[4593]['m__cover']. '</span><span class="icon-block">'. $e___4593[$x['x__type']]['m__cover'] . '</span>' . $e___4593[$x['x__type']]['m__title'] . '</a></div>';


    //Order
    if($x['x__spectrum'] > 0){
        $ui .= '<div class="simple-line '.$superpower_css_12701.'"><span data-toggle="tooltip" data-placement="top" title="'.$e___4341[4370]['m__title']. '"><span class="icon-block">'.$e___4341[4370]['m__cover']. '</span>'.view_ordinal($x['x__spectrum']).'</span></div>';
    }


    //Metadata
    if(strlen($x['x__metadata']) > 0){
        $ui .= '<div class="simple-line '.$superpower_css_12701.'"><a href="/-12722?x__id=' . $x['x__id'] . '" target="_blank"><span class="icon-block">'.$e___4341[6103]['m__cover']. '</span><u>'.$e___4341[6103]['m__title']. '</u></a></div>';
    }

    //Message
    if(strlen($x['x__message']) > 0 && $x['x__message']!='@'.$x['x__up']){
        $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$e___4341[4372]['m__title'].'"><span class="icon-block">'.$e___4341[4372]['m__cover'].'</span><div class="title-block x-msg">'.( strip_tags($x['x__message'])==$x['x__message'] || strlen(strip_tags($x['x__message']))<view_memory(6404,6197) ? $x['x__message'] : '<span class="hidden html_msg_'.$x['x__id'].'">'.$x['x__message'].'</span><a class="html_msg_'.$x['x__id'].'" href="javascript:void(0);" onclick="$(\'.html_msg_'.$x['x__id'].'\').toggleClass(\'hidden\');"><u>View HTML Message</u></a>' ).'</div></div>';
    }



    //5x Relations:
    if(!$has_x__reference){

        $var_index = var_index();
        foreach($CI->config->item('e___10692') as $e__id => $m) {

            //Do we have this set?
            if(!array_key_exists($e__id, $var_index) || !intval($x[$var_index[$e__id]])){
                continue;
            }

            if(in_array(6160 , $m['m__profile'])){

                //SOURCE
                $es = $CI->E_model->fetch(array('e__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/@'.$es[0]['e__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[$e__id]['m__cover']. '</span>'.'<span class="icon-block">'.view_cover(12274,$es[0]['e__cover'], true). '</span>'.$es[0]['e__title'].'</a></div>';

            } elseif(in_array(6202 , $m['m__profile'])){

                //IDEA
                $is = $CI->I_model->fetch(array('i__id' => $x[$var_index[$e__id]]));

                $ui .= '<div class="simple-line"><a href="/i/i_go/'.$is[0]['i__id'].'" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'" class="css__title"><span class="icon-block '.$superpower_css_12701.'">'.$e___4341[$e__id]['m__cover']. '</span><span class="icon-block">'.view_cache(4737 /* Idea Status */, $is[0]['i__type'], true, 'right', $is[0]['i__id']).'</span>'.view_i_title($is[0]).'</a></div>';

            } elseif(in_array(4367 , $m['m__profile'])){

                //PARENT DISCOVERY
                $xs = $CI->X_model->fetch(array('x__id' => $x[$var_index[$e__id]]));

                if(count($xs)){
                    $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$e___4341[$e__id]['m__title'].'">'.$e___4341[$e__id]['m__cover']. '</span><div class="x-ref hidden x_msg_'.$x['x__id'].'">'.view_x($xs[0], true).'</div><a class="x_msg_'.$x['x__id'].'" href="javascript:void(0);" onclick="$(\'.x_msg_'.$x['x__id'].'\').toggleClass(\'hidden\');"><u>View Referenced Transaction</u></a></div>';
                }

            }
        }
    }


    //DOMAIN
    $ui .= '<div class="simple-line">'.( isset($e___14870[$x['x__domain']]) ? '<a href="https://'.$e___14870[$x['x__domain']]['m__message'].'" target="_blank" class="css__title"><span class="icon-block">'.$e___4341[14870]['m__cover'].'</span><span class="icon-block">'.$e___14870[$x['x__domain']]['m__cover']. '</span>' . $e___14870[$x['x__domain']]['m__title'] . '</a>' : 'Unknown Domain' ).'</div>';


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
    $has_future = ($time < 0);
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
    if(!isset($config_array[$e__id])){
        return false;
    }
    $cache = $config_array[$e__id];
    if (!$cache) {
        //Could not find matching item
        return false;
    }


    //We have two skins for displaying Status:
    if (is_null($data_placement)) {
        if($micro_status){
            return $cache['m__cover'];
        } else {
            return $cache['m__cover'].' '.$cache['m__title'];
        }
    } else {
        //data-toggle="tooltip" data-placement="' . $data_placement . '"
        return '<span class="'.( $micro_status ? 'cache_micro_'.$parent.'_'.$i__id : '' ).'" ' . ( $micro_status && !is_null($data_placement) ? ' title="' . ($micro_status ? $cache['m__title'] : '') . (strlen($cache['m__message']) > 0 ? ($micro_status ? ': ' : '') . $cache['m__message'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache['m__cover'] . ' ' . ($micro_status ? '' : $cache['m__title']) . '</span>';
    }
}





function view_coins(){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
    $query = $CI->X_model->fetch(array(), array(), 1, 0, array(), 'COUNT(x__id) as totals');
    $ui = '';

    $ui .= '<div class="row justify-content list-coins">';
    $count = 0;
    foreach($CI->config->item('e___14874') as $e__id => $m) {
        $count++;
        $ui .= '<div class="coin_cover no-padding col-12 col-sm-6 col-md-4">';
        $ui .= '<div class="large_cover">'.$m['m__cover'].'</div>';
        $ui .= '<div class="css__title large_title zq'.$e__id.' "><b class="coin_count_'.$e__id.'">'.number_format(count_unique_coins($e__id), 0).'</b></div>';
        $ui .= '<div class="css__title large_title zq'.$e__id.'">'.$m['m__title'].'</div>';
        $ui .= '</div>';
    }
    $ui .= '</div>';
    //$ui .= '<div class="row justify-content list-coins"><span style="min-width: 89px; min-height: 20px; text-align: right; display: inline-block;"><b class="css__title coin_count_x">'.number_format($query[0]['totals'], 0).'</b></span>&nbsp;Transactions <a href="/18032" data-toggle="tooltip" data-placement="top" title="Learn more about our open-source Ledger that holds all data"><i class="fas fa-info-circle" style="font-size: 1em !important; margin-left: 3px;"></i></a></div>';

    return $ui;
}

function view_coin_line($href, $is_current, $x__type, $o__type, $o__cover, $o__title, $x__message = null){
    return '<a href="'.( $is_current ? 'javascript:alert(\'You are here already!\');' : $href ).'" class="dropdown-item move_away css__title '.( $is_current ? ' active ' : '' ).'">'.( $x__type ? '<span class="icon-block-xxs">'.$x__type.'</span>' : '' ).( $o__type ? '<span class="icon-block-xxs">'.$o__type.'</span>' : '' ).( strlen($o__cover) ? '<span class="icon-block-xxs">'.$o__cover.'</span>' : '' ).$o__title./*'<span class="pull-right inline-block">'..'</span>'.*/( strlen($x__message) && superpower_active(12701, true) ? '<div class="message2">'.strip_tags($x__message).'</div>' : '' ).'</a>';
}




function view_body_e($x__type, $counter, $e__id){

    $CI =& get_instance();
    $limit = view_memory(6404,11064);
    $member_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
    $superpower_10939 = superpower_active(10939, true);
    $source_is_e = $e__id==$member_e['e__id'];
    $source_of_e = source_of_e($e__id);
    $list_results = view_coins_e($x__type, $e__id, 1);
    $focus_e = ($e__id == $member_e['e__id'] ? $member_e : false);
    $ui = '';

    if($x__type==4250){

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $i) {
            $ui .= view_i($x__type, 0, null, $i, $focus_e);
        }
        $ui .= '</div>';

    } elseif($x__type==4251) {

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach ($list_results as $e) {
            $ui .= view_e($x__type, $e, source_of_e($e__id));
        }
        $ui .= '</div>';

    } elseif($x__type==12273){

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $i){
            $ui .= view_i($x__type, 0, null, $i, $focus_e, null);
        }

        if ($counter > count($list_results)) {
            //We have even more:
            $ui .= view_load_page_i($x__type, 1, $limit, $counter);
        }

        $ui .= '</div>';

        $ui .= ( $counter >= 2 ? '<script> $(document).ready(function () {x_sort_load('.$x__type.')}); </script>' : '<style> #list-in-'.$x__type.' .x_sort {display:none !important;} </style>' ); //Need 2 or more to sort


        if($superpower_10939 && !$source_is_e){
            $ui .= '<div class="new-list-'.$x__type.' list-group"><div class="list-group-item list-adder">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-'.$x__type.' .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . view_memory(6404,4736) . '"
                           placeholder="'.$e___11035[14016]['m__title'].'">
                </div><div class="algolia_pad_search row justify-content"></div></div></div>';

            $ui .= '<script> $(document).ready(function () { i_load_search('.$x__type.'); }); </script>';
        }

    } elseif($x__type==12274){

        $ui .= view_body_e(11030, $counter, $e__id);
        $ui .= view_body_e(11029, $counter, $e__id);

    } elseif($x__type==11029 || $x__type==11030){

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';

        foreach($list_results as $e) {
            $ui .= view_e($x__type, $e, null,  ($source_of_e || ($member_e && ($member_e['e__id']==$e['x__source']))));
        }

        if ($counter > count($list_results)) {
            //Load even more if there...
            $ui .= view_load_page_e($x__type, 1, $limit, $counter);
        }

        $ui .= '</div>';

        //Input to add new child:
        if(superpower_active(13422, true)){

            $ui .= '<div current-count="'.$counter.'" class="new-list-'.$x__type.' list-adder">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-'.$x__type.' .add-input\').focus();"><span class="icon-block">'.$e___11035[14055]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="'.$e___11035[14055]['m__title'].'">
                    </div><div class="algolia_pad_search row justify-content"></div></div>';

        } else {

            $ui .= '<div class="hideIfEmpty new-list-'.$x__type.'"></div>';

        }

    } elseif($x__type==10573){

        //Need 2 or more to sort...
        $ui .= ( count($list_results) >= 2 ? '<script> $(document).ready(function () {x_sort_load(10573)}); </script>' : '<style> #list-in-10573 .x_sort {display:none !important;} </style>' );

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-10573">';
        foreach($list_results as $i){
            $ui .= view_i(10573, 0, null, $i, $focus_e);
        }
        $ui .= '</div>';


        //Add Idea:
        if($superpower_10939 && $source_is_e){
            //Give Option to Add New Idea:
            $ui .= '<div class="new-list-10573 list-group"><div class="list-group-item list-adder">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-10573 .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick algolia_search dotransparent add-input"
                           maxlength="' . view_memory(6404,4736) . '"
                           placeholder="'.$e___11035[14016]['m__title'].'">
                </div><div class="algolia_pad_search row justify-content"></div></div></div>';

            $ui .= '<script> $(document).ready(function () { i_load_search(10573); }); </script>';

        }
    } elseif(in_array($x__type, $CI->config->item('n___13550'))){

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $i) {
            $ui .= view_i(13550, 0, null, $i, $focus_e);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___14690'))) {

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-' . $x__type . '">';
        foreach ($list_results as $i) {
            $ui .= view_i($x__type, $i['i__id'], null, $i, $focus_e);
        }
        $ui .= '</div>';

        if ($e__id == $member_e['e__id'] && in_array($x__type, $CI->config->item('n___4603'))) {
            $ui .= '<script> $(document).ready(function () { x_sort_load(' . $x__type . ') }); </script>';
        } else {
            $ui .= '<style> #list-in-' . $x__type . ' .x_sort {display:none !important;} </style>';
        }

    }

    return $ui;

}



function view_body_i($x__type, $counter, $i__id){

    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
    $e_of_i = e_of_i($i__id);
    $list_results = view_coins_i($x__type, $i__id, 1);
    $ui = '';
    $is = $CI->I_model->fetch(array(
        'i__id' => $i__id,
    ));
    if(!count($is)){
        return false;
    }


    if(in_array($x__type, $CI->config->item('n___13550'))){

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $i_note) {
            $ui .= view_e($x__type, $i_note,  null, $e_of_i);
        }
        $ui .= '</div>';

        if($e_of_i && !in_array($x__type, $CI->config->item('n___12677'))) {
            $ui .= '<div class="list-adder e-only-13550 e-i-' . $x__type . '" x__type="' . $x__type . '">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.e-i-' . $x__type . ' .add-input\').focus();"><span class="icon-block">'.$e___11035[14055]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick algolia_search input_note_'.$x__type.' dotransparent add-input"
                           maxlength="' . view_memory(6404,6197) . '"                          
                           placeholder="' . $e___11035[14055]['m__title'] . '">
                </div><div class="algolia_pad_search row justify-content"></div></div>';
        }

    } elseif($x__type==11019){

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $previous_i) {
            $ui .= view_i(11019, 0, null, $previous_i);
        }
        $ui .= '</div>';

        if($e_of_i){
            $ui .= '<div class="new-list-11019 list-adder '.superpower_active(10939).'">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-11019 .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick add-input algolia_search dotransparent"
                               maxlength="' . view_memory(6404,4736) . '"
                               placeholder="'.$e___11035[14016]['m__title'].'">
                    </div><div class="algolia_pad_search row justify-content"></div></div>';
        }

    } elseif($x__type==13542){

        //IDEAS
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $next_i) {
            $ui .= view_i(13542, 0, $is[0], $next_i);
        }
        $ui .= '</div>';

        if($e_of_i){
            $ui .= '<div class="new-list-13542 list-adder '.superpower_active(10939).'">
                <div class="input-group border">
                    <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-13542 .add-input\').focus();"><span class="icon-block">'.$e___11035[14016]['m__cover'].'</span></a>
                    <input type="text"
                           class="form-control form-control-thick add-input algolia_search dotransparent"
                           maxlength="' . view_memory(6404,4736) . '"
                           placeholder="'.$e___11035[14016]['m__title'].'">
                </div><div class="algolia_pad_search row justify-content"></div></div>';
        }

    } elseif($x__type==6255) {

        //DISCOVERIES
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $item){
            $ui .= view_e(6255, $item);
        }
        $ui .= '</div>';

    } elseif($x__type==12274){

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">'; //list-in-4983
        foreach($list_results as $e_ref){
            $ui .= view_e($e_ref['x__type'], $e_ref, null, $e_of_i);
        }
        $ui .= '</div>';
        $ui .= '<div class="new-list-'.$x__type.' list-adder '.superpower_active(10939).'">
                    <div class="input-group border">
                        <a class="input-group-addon addon-lean icon-adder" href="javascript:void(0);" onclick="$(\'.new-list-'.$x__type.' .add-input\').focus();"><span class="icon-block">'.$e___11035[14055]['m__cover'].'</span></a>
                        <input type="text"
                               class="form-control form-control-thick algolia_search dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="'.$e___11035[14055]['m__title'].'">
                    </div><div class="algolia_pad_search row justify-content"></div></div>';

    } elseif(in_array($x__type, $CI->config->item('n___13550'))){

        //IDEA NOTES
        $ui .= view_i_note_list($x__type, false, $is[0], $list_results, $e_of_i);

    }

    return $ui;

}

function view_item($e__id, $i__id, $s__title, $s__cover, $link, $desc = null, $m_cover = false){

    //$link = '/-27970?e__id='.$e__id.'&i__id='.$i__id.'&go_to='.urlencode($link);

    return '<a href="'.$link.'" class="list-group-item list-group-item-action flex-column align-items-start">
    <div class="d-flex justify-content-between">
      <h4 class="css__title"><b>'.( strlen($s__cover) ? '<span class="icon-block-lg title-left">'.( $m_cover ? $s__cover : view_cover(($e__id>0 ? 12274 : 12273),$s__cover) ).'</span><span class="title-right">'.$s__title.'</span>' : $s__title ).'</b></h4>
      <small style="padding: 17px 3px 0 0;"><i class="far fa-chevron-right"></i></small>
    </div>
    '.( strlen($desc) ? '<p>'.$desc.'</p>' : '' ) .'
    
  </a>';

}

function view_coins_e($x__type, $e__id, $page_num = 0, $append_coin_icon = true, $load_items = 0){

    /*
     *
     * Loads Source
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if($x__type==11029){

        //DOWN
        $order_columns = array('x__spectrum' => 'ASC', 'e__title' => 'ASC');
        $join_objects = array('x__down');
        $query_filters = array(
            'x__up' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__type IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
        );

    } elseif($x__type==11030 || $x__type==12274){

        //UP
        $order_columns = array('e__title' => 'ASC');
        $join_objects = array('x__up');
        $query_filters = array(
            'x__down' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__type IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
        );

    } elseif($x__type==10573){

        $order_columns = array('x__spectrum' => 'ASC');
        $join_objects = array('x__right');
        $query_filters = array(
            'x__up' => $e__id,
            'x__type' => 10573, //WATCHERS
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
        );
        
    } elseif($x__type==12273){

        //Determine Sort:
        $order_columns = array();
        foreach($CI->config->item('e___13550') as $x__sort_id => $sort) {
            $order_columns['x__type = \''.$x__sort_id.'\' DESC'] = null;
        }
        foreach($CI->config->item('e___4737') as $x__sort_id => $sort) {
            $order_columns['i__type = \''.$x__sort_id.'\' DESC'] = null;
        }
        //$order_columns['x__spectrum'] = 'ASC';
        $order_columns['x__id'] = 'DESC';

        //IDEAS
        $join_objects = array('x__right');
        $query_filters = array(
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__up' => $e__id,
        );

    } elseif($x__type==12896){

        $join_objects = array('x__right');
        $order_columns = array('x__spectrum' => 'ASC', 'x__id' => 'DESC');
        $query_filters = array(
            'x__up' => $e__id,
            'x__type' => 12896,
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
        );

    } elseif($x__type==6255){

        //Determine Sort:
        $order_columns = array();
        foreach($CI->config->item('e___6255') as $x__sort_id => $sort) {
            $order_columns['x__type = \''.$x__sort_id.'\' DESC'] = null;
        }
        $order_columns['x__id'] = 'DESC';

        //DISCOVERIES
        $join_objects = array('x__left');
        $query_filters = array(
            'x__source' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $CI->config->item('n___7355')) . ')' => null, //PUBLIC
        );

    } elseif(in_array($x__type, $CI->config->item('n___13550'))){

        //IDEA NOTES
        $join_objects = array('x__right');
        $order_columns = array('x__id' => 'DESC'); //LATEST DISCOVERIES
        $query_filters = array(
            'x__up' => $e__id,
            'x__type' => $x__type,
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
        );

    } elseif(in_array($x__type, $CI->config->item('n___12149'))){

        //Ideas/Sources Owned
        $join_objects = array();
        $order_columns = array('x__id' => 'DESC'); //LATEST DISCOVERIES
        $query_filters = array(
            'x__source' => $e__id,
            'x__type' => $x__type,
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        );

    } else {

        return null;

    }


    //Return Results:
    if($page_num > 0){

        if($x__type==12274){

            return array_merge(
                view_coins_e(11030, $e__id, $page_num, $append_coin_icon),
                array(array('is_break' => true)),
                view_coins_e(11029, $e__id, $page_num, $append_coin_icon)
            );

        } else {
            $limit = view_memory(6404,11064);
            return $CI->X_model->fetch($query_filters, $join_objects, ( $load_items > 0 ? $load_items : $limit ), ($page_num-1)*$limit, $order_columns);
        }

    } else {

        $e___11035 = $CI->config->item('e___11035'); //COINS

        if($x__type==12274){

            $coins1 = view_coins_e(11029, $e__id, 0, false);
            $coins2 = view_coins_e(11030, $e__id, 0, false);
            $count_query = $coins1 + $coins2;
            $visual_counter = view_number($coins1+$coins2);
            $title_desc = number_format($coins1, 0).' '.$e___11035[11019]['m__title'].' & '.number_format($coins2, 0).' '.$e___11035[13542]['m__title'];

        } else {
            $query = $CI->X_model->fetch($query_filters, $join_objects, 1, 0, array(), 'COUNT(x__id) as totals');
            $count_query = $query[0]['totals'];
            $visual_counter = view_number($count_query);
            $title_desc = number_format($count_query, 0).' '.$e___11035[$x__type]['m__title'];

        }


        if($append_coin_icon){

            if(!$count_query){
                return null;
            }

            $current_e = ( substr($first_segment, 0, 1)=='@' ? intval(substr($first_segment, 1)) : 0 );
            $coin_icon = '<span class="icon-block-xxs">'.$e___11035[$x__type]['m__cover'].'</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding css__title load_e_coins button_of_'.$e__id.'_'.$x__type.'" id="coin_e_group_'.$x__type.'_'.$e__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_x__type="'.$x__type.'" load_e__id="'.$e__id.'" load_counter="'.$count_query.'" load_first_segment="'.$first_segment.'" load_current_e="'.$current_e.'" ><span title="'.$title_desc.'" data-toggle="tooltip" data-placement="top">'.$coin_icon.$visual_counter.'</span></button>';
            $ui .= '<div class="dropdown-menu coins_e_'.$e__id.'_'.$x__type.'" aria-labelledby="coin_e_group_'.$x__type.'_'.$e__id.'">';
                //Menu To be loaded dynamically via AJAX
            $ui .= '</div>';
            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }
    }

}


function view_coins_i($x__type, $i__id, $page_num = 0, $append_coin_icon = true, $load_items = 0){

    /*
     *
     * Loads Idea
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if($x__type==12274){

        //SOURCES
        $order_columns = array('x__id' => 'ASC');
        $join_objects = array('x__up');
        $query_filters = array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__right' => $i__id,
            'x__up >' => 0, //MESSAGES MUST HAVE A SOURCE REFERENCE TO ISSUE IDEA COINS
        );

    } elseif($x__type==13542){

        //IDEAS
        $order_columns = array('x__spectrum' => 'ASC');
        $join_objects = array('x__right');
        $query_filters = array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i__id,
        );

    } elseif($x__type==6255){

        //DISCOVERIES
        $order_columns = array('x__id' => 'DESC');
        $join_objects = array('x__source');
        $query_filters = array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__left' => $i__id,
        );
        if(isset($_GET['load__e'])){
            $query_filters['x__source'] = intval($_GET['load__e']);
        }

    } elseif($x__type==11019) {

        $order_columns = array('i__title' => 'ASC');
        $join_objects = array('x__left');
        $query_filters = array(
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__type IN (' . join(',', $CI->config->item('n___7356')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__right' => $i__id,
        );

    } elseif(in_array($x__type, $CI->config->item('n___13550'))){

        //1x Source Ref
        $order_columns = array('x__spectrum' => 'ASC');
        $join_objects = array('x__up');
        $query_filters = array(
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => $x__type,
            'x__right' => $i__id,
        );

    } elseif($x__type==12273) {

        //Will merge down below

    } else {

        return null;

    }



    //Return Results:
    if($page_num > 0){

        if($x__type==12273){

            return array_merge(
                view_coins_i(11019, $i__id, $page_num, $append_coin_icon),
                array(array('is_break' => true)),
                view_coins_i(13542, $i__id, $page_num, $append_coin_icon)
            );

        } else {
            $limit = view_memory(6404,11064);
            return $CI->X_model->fetch($query_filters, $join_objects, ( $load_items > 0 ? $load_items : $limit ), ($page_num-1)*$limit, $order_columns);
        }

    } else {

        $e___11035 = $CI->config->item('e___11035'); //COINS

        if($x__type==12273){

            $coins1 = view_coins_i(11019, $i__id, 0, false);
            $coins2 = view_coins_i(13542, $i__id, 0, false);
            $count_query = $coins1 + $coins2;
            $visual_counter = view_number($count_query);
            $title_desc = number_format($coins1, 0).' '.$e___11035[11019]['m__title'].' & '.number_format($coins2, 0).' '.$e___11035[13542]['m__title'];

        } else {

            $query = $CI->X_model->fetch($query_filters, $join_objects, 1, 0, array(), 'COUNT(x__id) as totals');
            $count_query = $query[0]['totals'];
            $visual_counter = view_number($count_query);
            $title_desc = number_format($count_query, 0).( isset($e___11035[$x__type]['m__title']) ? ' '.$e___11035[$x__type]['m__title'] : '' );

        }

        if($append_coin_icon){

            if(!$count_query){
                return null;
            }

            $coin_icon = '<span class="icon-block-xxs">'.$e___11035[$x__type]['m__cover'].'</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding css__title load_i_coins button_of_'.$i__id.'_'.$x__type.'" id="coin_i_group_'.$x__type.'_'.$i__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_x__type="'.$x__type.'" load_i__id="'.$i__id.'" load_counter="'.$count_query.'" load_first_segment="'.$first_segment.'"><span title="'.$title_desc.'" data-toggle="tooltip" data-placement="top">'.$coin_icon.$visual_counter.'</span></button>';
            $ui .= '<div class="dropdown-menu coins_i_'.$i__id.'_'.$x__type.'" aria-labelledby="coin_i_group_'.$x__type.'_'.$i__id.'">';
                //Menu To be loaded dynamically via AJAX
            $ui .= '</div>';
            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }

    }

}

function view_radio_e($focus__id, $child___id, $enable_mulitiselect){

    /*
     * Print UI for
     * */

    $CI =& get_instance();
    $count = 0;

    $ui = '<div class="list-group list-radio-select radio-'.$focus__id.'">';

    if(!is_array($CI->config->item('n___'.$focus__id)) || !count($CI->config->item('n___'.$focus__id))){
        return false;
    }

    $already_selected = array();
    foreach($CI->X_model->fetch(array(
        'x__up IN (' . join(',', $CI->config->item('n___'.$focus__id)) . ')' => null,
        'x__down' => $child___id,
        'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
        'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    )) as $sel){
        array_push($already_selected, $sel['x__up']);
    }

    if(!count($already_selected) && in_array($focus__id, $CI->config->item('n___6204'))){
        //FIND DEFAULT:
        foreach($CI->config->item('e___'.$focus__id) as $e__id2 => $m2){
            if(in_array($e__id2, $CI->config->item('n___'.get_domain_setting(14926)) /* ACCOUNT DEFAULTS */ )){
                $already_selected = array($e__id2);
                break;
            }
        }
    }

    foreach($CI->config->item('e___'.$focus__id) as $e__id => $m) {
        $ui .= '<span class=""><a href="javascript:void(0);" onclick="e_radio('.$focus__id.','.$e__id.','.$enable_mulitiselect.')" class="list-group-item css__title custom_ui_'.$focus__id.'_'.$e__id.' itemsetting item-'.$e__id.' '.( in_array($e__id, $already_selected) ? ' active ' : '' ). '"><span class="icon-block change-results">'.$m['m__cover'].'</span>'.$m['m__title'].'</a></span>';
        $count++;
    }

    $ui .= '</div>';

    return $ui;
}


function view_i_list($x__type, $top_i__id, $i, $next_is, $member_e){

    //If no list just return the next step:
    $CI =& get_instance();
    if(!count($next_is)){
        return false;
    } elseif(!in_array($x__type, $CI->config->item('n___13369'))){
        return false;
    }

    $e___13369 = $CI->config->item('e___13369'); //NAVIGATION

    //Build Body UI:
    $body = '<div class="row">';
    foreach($next_is as $key => $next_i){
        $completion_rate = $CI->X_model->completion_progress($member_e['e__id'], $next_i);
        $body .= view_i($x__type, $top_i__id, $i, $next_i, $member_e, $completion_rate, null);
    }
    $body .= '</div>';

    return view_headline($x__type, count($next_is), $e___13369[$x__type], $body, isset($_GET['open']));

}


function view_i_note_list($x__type, $has_discovery_mode, $i, $i_notes, $e_of_i){

    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');
    $supports_emoji = (in_array($x__type, $CI->config->item('n___14990')));
    $handles_uploads = (in_array($x__type, $CI->config->item('n___12359')));
    $member_e = superpower_unlocked();
    $ui = '';


        //Show no-Message notifications for each message type:
        $ui .= '<div id="i_notes_list_'.$x__type.'" class="list-group">';

        //List current notes:
        foreach($i_notes as $i_note) {
            $ui .= view_i_note($x__type, $has_discovery_mode, $i_note, ($i_note['x__source']==$member_e['e__id'] || $e_of_i));
        }

        //ADD NEW:
        if(!in_array($x__type, $CI->config->item('n___12677')) && $e_of_i){

            $ui .= '<div class="no-padding add_notes_' . $x__type .'">';
            $ui .= '<div class="add_notes_form">';
            $ui .= '<form class="box box' . $x__type . '" method="post" enctype="multipart/form-data" class="'.superpower_active(10939).'">';

            $ui .= '<textarea onkeyup="i_note_count_new('.$x__type.')" class="form-control msg note-textarea regular_editor dotransparent algolia_search new-note '.( $supports_emoji ? 'emoji-input' : '' ).' input_note_'.$x__type.'" x__type="' . $x__type . '" style="margin-top: 10px;" placeholder="Write..."></textarea>';

            //Response result:
            $ui .= '<div class="note_error_'.$x__type.' hideIfEmpty zq6255 msg alert alert-danger" style="margin:8px 0;"></div>';


            //CONTROLLER
            $ui .= '<table class="table table-condensed"><tr>';

            if($handles_uploads){

                //UPLOAD
                $ui .= '<td class="table-btn first_btn">';
                $ui .= '<label class="hidden"></label>'; //To catch & store unwanted uploaded file name
                $ui .= '<label class="btn  btn-compact file_label_'.$x__type.'" for="fileIdeaType'.$x__type.'" title="'.$e___11035[13572]['m__title'].' '.$e___11035[13572]['m__message'].'"><span class="icon-block">'.$e___11035[13572]['m__cover'].'</span></label>';
                $ui .= '<input class="inputfile hidden" type="file" name="file" id="fileIdeaType'.$x__type.'" />';
                $ui .= '</td>';

                //GIF
                $ui .= '<td class="table-btn first_btn"><a class="btn btn-compact " href="javascript:void(0);" onclick="images_modal(' . $x__type . ')" title="'.$e___11035[14073]['m__title'].'"><span class="icon-block">'.$e___11035[14073]['m__cover'].'</span></a></td>';

            }

            if($supports_emoji){
                //EMOJI
                $ui .= '<td class="table-btn first_btn"><span class="btn btn-compact " id="emoji_pick_type'.$x__type.'" title="'.$e___11035[14038]['m__title'].'"><span class="icon-block">'.$e___11035[14038]['m__cover'].'</span></span></td>';
            }

            //Add
            $ui .= '<td class="table-btn first_btn"><a href="javascript:i_note_add_text('.$x__type.');" class="btn btn-default save_notes_'.$x__type.'" style="width:104px;" data-toggle="tooltip" data-placement="bottom" title="Shortcut: Ctrl + Enter">'.$e___11035[14421]['m__cover'].' '.$e___11035[14421]['m__title'].'</a></td>';


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


function view_e_settings($list_id, $is_open){

    $CI =& get_instance();
    $member_e = superpower_unlocked();
    $e___14010 = $CI->config->item('e___14010');
    $ui = null;
    if(!$member_e){
        return false;
    }

    //Display account fields ordered with their SOURCE LINKS:
    foreach($CI->config->item('e___'.$list_id) as $acc_e__id => $acc_detail) {

        //Skip if domain specific:
        $hosted_domains = array_intersect($CI->config->item('n___14870'), $acc_detail['m__profile']);
        if(count($hosted_domains) && !in_array(get_domain_setting(0), $hosted_domains)){
            continue;
        }

        //Skip if missing superpower:
        $superpower_actives = array_intersect($CI->config->item('n___10957'), $acc_detail['m__profile']);
        if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
            continue;
        }

        //Print account fields that are either Single Selectable or Multi Selectable:
        $superpower_actives = array_intersect($CI->config->item('n___10957'), $acc_detail['m__profile']);
        $has_multi_selectable = in_array(6122, $acc_detail['m__profile']);
        $has_single_selectable = in_array(6204, $acc_detail['m__profile']);
        $tab_ui = null;

        //Switch if part of domain settings:
        if(in_array($acc_e__id, $CI->config->item('n___14925'))){
            $domain_specific_id = intval(get_domain_setting($acc_e__id));
            if($domain_specific_id){
                //Replace with domain specific:
                $acc_e__id = $domain_specific_id;
            } else {
                continue;
            }
        }

        //Append description if any:
        if(strlen($acc_detail['m__message']) > 0){
            $tab_ui .= '<div class="regtext" style="text-align: left; padding:0 0 21px 0;">' . nl2br($acc_detail['m__message']) . '</div>';
        }


        if ($acc_e__id == 10957 /* Superpowers */) {

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

                $has_unlocked = in_array($superpower_e__id, $CI->session->userdata('session_superpowers_unlocked'));
                $public_link = in_array($superpower_e__id, $CI->config->item('n___6404'));
                $anchor = '<span class="icon-block main-icon" title="@'.$superpower_e__id.'">'.$m3['m__cover'].'</span><b class="css__title">'.$m3['m__title'].'</b><span class="superpower-message">'.$m3['m__message'].'</span>';

                if($has_unlocked){

                    //SUPERPOWERS UNLOCKED
                    $progress_type_id=14008;
                    $tab_ui .= '<a class="list-group-item itemsetting btn-superpower superpower-frame-'.$superpower_e__id.' '.( superpower_active($superpower_e__id, true) ? ' active ' : '' ).'" en-id="'.$superpower_e__id.'" href="javascript:void();" onclick="e_toggle_superpower('.$superpower_e__id.')"><span class="icon-block pull-right" title="'.$e___14010[$progress_type_id]['m__title'].'">'.$e___14010[$progress_type_id]['m__cover'].'</span>'.$anchor.'</a>';

                } elseif(!$has_unlocked && $public_link){

                    //SUPERPOWERS AVAILABLE
                    $progress_type_id=14011;
                    $tab_ui .= '<a class="list-group-item no-side-padding" href="'.view_memory(6404,$superpower_e__id).'"><span class="icon-block pull-right" title="'.$e___14010[$progress_type_id]['m__title'].'">'.$e___14010[$progress_type_id]['m__cover'].'</span>'.$anchor.'</a>';

                } elseif(!$has_unlocked && !$public_link){

                    //SUPERPOWERS UNAVAILABLE
                    $progress_type_id=14009;
                    $tab_ui .= '<a href="javascript:void();" onclick="alert(\'This superpower is locked & cannot be unlocked at this time. Start by unlocking other available superpowers.\')" class="list-group-item no-side-padding islocked grey '.superpower_active(10939).'"><span class="icon-block pull-right" title="'.$e___14010[$progress_type_id]['m__title'].'">'.$e___14010[$progress_type_id]['m__cover'].'</span>'.$anchor.'</a>';

                }

            }

            $tab_ui .= '</div>';

        } elseif ($acc_e__id == 3288 /* Email */) {

            $u_emails = $CI->X_model->fetch(array(
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $member_e['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => 3288, //Email
            ));

            $tab_ui .= '<span><input type="email" id="e_email" class="form-control border dotransparent" value="' . (count($u_emails) > 0 ? $u_emails[0]['x__message'] : '') . '" placeholder="you@gmail.com" /></span>
                <a href="javascript:void(0)" onclick="e_email()" class="btn btn-default">Save</a>
                <span class="saving-account save_email"></span>';


        } elseif ($acc_e__id == 4783 /* Phone */) {

            $u_phones = $CI->X_model->fetch(array(
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $member_e['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => 4783, //Phone
            ));

            $tab_ui .= '<span><input type="number" id="e_phone" class="form-control border dotransparent" value="' . (count($u_phones) > 0 ? $u_phones[0]['x__message'] : '') . '" placeholder="7781234567" /></span>
                <a href="javascript:void(0)" onclick="e_phone()" class="btn btn-default">Save</a>
                <span class="saving-account save_phone"></span>';

        } elseif ($acc_e__id == 30198 /* Full Name */) {

            $u_names = $CI->X_model->fetch(array(
                'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__down' => $member_e['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //SOURCE LINKS
                'x__up' => 30198, //Full Name
            ));

            $tab_ui .= '<span><input type="text" id="e_fullname" class="form-control border dotransparent" value="' . (count($u_names) > 0 ? $u_names[0]['x__message'] : '') . '" placeholder="Will Smith" /></span>
                <a href="javascript:void(0)" onclick="e_fullname()" class="btn btn-default">Save</a>
                <span class="saving-account save_name"></span>';

        } elseif ($acc_e__id == 3286 /* Password */) {

            $tab_ui .= '<span><input type="password" id="input_password" class="form-control border dotransparent" data-lpignore="true" autocomplete="new-password" placeholder="New Password..." /></span>
                <a href="javascript:void(0)" onclick="e_password()" class="btn btn-default">Save</a>
                <span class="saving-account save_password"></span>';

        } elseif ($has_multi_selectable || $has_single_selectable) {

            $tab_ui .= view_radio_e($acc_e__id, $member_e['e__id'], ($has_multi_selectable ? 1 : 0));

        }

        $ui .= view_headline($acc_e__id, null, $acc_detail, $tab_ui, $is_open, true);

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
            return 'Missing: '.$e___10957[$superpower_e__id]['m__title'];

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
    return '<div class="msg alert alert-info no-margin" style="margin-bottom: 10px !important;" title="'.$e___11035[13670]['m__title'].'"><span class="icon-block">'.$e___11035[13670]['m__cover'] . '</span><span class="icon-block-xs">' . view_cover(12274,$e['e__cover'], true) . '</span><a href="/@'.$e['e__id'].'">' . $e['e__title'].'</a>&nbsp;&nbsp;&nbsp;<a href="/'.$CI->uri->segment(1).'" title="'.$e___11035[13671]['m__title'].'">'.$e___11035[13671]['m__cover'].'</a></div>';
}




function view_i_select($i, $x__source, $previously_selected){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    $i_title = view_i_title($i);
    $member_e = superpower_unlocked();
    $i_stats = i_stats($i['i__metadata']);
    $spots_remaining = i_spots_remaining($i['i__id']);


    $href = 'href="javascript:void(0);"'.( $spots_remaining==0 && !$previously_selected ? ' onclick="alert(\'This Option is Not Available\')" ' : ' onclick="toggle_answer(' . $i['i__id'] . ')"' );

    $ui  = '<div class="coin_cover col-6 col-md-4 col-xl-2 col-lg-3 no-padding">';
    $ui .= '<div class="cover-wrapper">';
    $ui .= '<table class="coin_coins"></table>'; //For UI height adjustment
    $ui .= '<a '.$href.' selection_i__id="' . $i['i__id'] . '" class="answer-item black-background-obs cover-link x_select_' . $i['i__id'] . ($previously_selected ? ' isSelected ' : '') . ( $spots_remaining==0 ? ' greyout ' : '' ).'">';

    $ui .= '</a>';
    $ui .= '</div>';

    $ui .= '<div class="cover-content"><div class="inner-content">';
    $ui .= '<a '.$href.'>'.$i_title.'</a>';

    $ui .= '<div class="cover-text">';


    if($spots_remaining >= 0){
        //$ui .= '<a '.$href.' class="doblock" style="padding-bottom:2px;"><span class="mini-font '.( $spots_remaining==0 ? ' grey ' : ' isgreen ' ).'">[' .( $spots_remaining==0 ? 'Not Available' : $spots_remaining . ' Remaining' ) .']</span></a>';
    }

    //Messages:
    $ui .= '<a '.$href.' class="hideIfEmpty doblock">';
    foreach($CI->X_model->fetch(array(
        'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4231, //IDEA NOTES Messages
        'x__right' => $i['i__id'],
    ), array(), 0, 0, array('x__spectrum' => 'ASC')) as $message_x) {
        $ui .= $CI->X_model->message_view($message_x['x__message'], true, $member_e, 0, true);
    }
    $ui .= '</a>';

    $ui .= '</div>';


    $ui .= '</div></div>';
    $ui .= '</div>';

    return $ui;

}


function view_i($x__type, $top_i__id = 0, $previous_i = null, $i, $focus_e = false, $completion_rate = null, $extra_class = null, $is_first_incomplete = false){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    if(!in_array($x__type, $CI->config->item('n___13369'))){
        return 'Invalid x__type '.$x__type;
    }
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
    $e___13369 = $CI->config->item('e___13369'); //IDEA LIST
    $e_of_i = e_of_i($i['i__id']);
    $user_input = $focus_e;
    $member_e = superpower_unlocked();
    $is_first_incomplete = ( $top_i__id>0 && $member_e ? $is_first_incomplete : false );
    $primary_icon = in_array($x__type, $CI->config->item('n___14378')); //PRIMARY ICON
    $discovery_mode = $top_i__id>0 || in_array($x__type, $CI->config->item('n___14378')); //DISCOVERY MODE
    $linkbar_visible = in_array($x__type, $CI->config->item('n___20410'));
    $cache_app = in_array($x__type, $CI->config->item('n___14599'));
    $editing_enabled = !$cache_app && in_array($x__type, $CI->config->item('n___14502')) && $e_of_i; //IDEA EDITING
    $focus_coin = in_array($x__type, $CI->config->item('n___12149')); //NODE COIN
    $has_self = $member_e && $focus_e && $member_e['e__id']==$focus_e['e__id'];

    if(!$focus_e){
        $focus_e = $member_e;
    }

    $load_completion = in_array($x__type, $CI->config->item('n___14501')) && $top_i__id > 0 && $focus_e && $discovery_mode;

    if(is_null($completion_rate)){
        if($load_completion){ //Load Completion Bar
            $completion_rate = $CI->X_model->completion_progress($focus_e['e__id'], $i);
        } else {
            //set zero:
            $completion_rate['completion_percentage'] = 0;
        }
    }



    $superpower_10939 = superpower_active(10939, true);
    $superpower_12700 = superpower_active(12700, true);
    $superpower_12673 = superpower_active(12673, true);
    $is_completed = ($completion_rate['completion_percentage']>=100);
    $is_started = ($completion_rate['completion_percentage']>0);
    $parent_is_or = ( $discovery_mode && $previous_i && in_array($previous_i['i__type'], $CI->config->item('n___6193')) );
    $force_order = ( $previous_i && count($CI->X_model->fetch(array(
            'x__status IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___13550')) . ')' => null, //SOURCE IDEAS
            'x__right' => $previous_i['i__id'],
            'x__up' => 14488, //Force Order
        ), array(), 1)));
    $locking_enabled = !isset($focus_e['e__id']) || $focus_e['e__id']<1 || ($force_order && $discovery_mode);
    $has_hard_lock = in_array($x__type, $CI->config->item('n___14453'));
    $has_soft_lock = $locking_enabled && !$is_completed && ($has_hard_lock || (!$is_first_incomplete && ($force_order || !$is_started)));
    $has_sortable = !$focus_coin && !$has_soft_lock && $editing_enabled && in_array($x__type, $CI->config->item('n___4603'));
    $i_stats = i_stats($i['i__metadata']);
    $i_title = view_i_title($i);
    $has_any_lock = $has_soft_lock || $has_hard_lock;

    if(in_array($i['i__type'], $CI->config->item('n___14454')) && !$is_completed) {
        if($top_i__id){
            $href = '/x/x_next/'.$top_i__id.'/'.$i['i__id'];
        } elseif($superpower_10939) {
            $href = '/~'.$i['i__id'];
        } else {
            $href = '/'.$i['i__id'];
        }
    } elseif(strlen($e___13369[$x__type]['m__message'])){
        $href = $e___13369[$x__type]['m__message'].$i['i__id'];
    } elseif(in_array($x__type, $CI->config->item('n___14742')) && $previous_i && $member_e && $top_i__id){
        //Complete if not already:
        $href = '/x/complete_next/'.$top_i__id.'/'.$previous_i['i__id'].'/'.$i['i__id'];
    } elseif($discovery_mode){
        if($top_i__id > 0 && $top_i__id!=$i['i__id']){
            $href = '/'.$top_i__id.'/'.$i['i__id'];
        } else {
            $href = '/'.$i['i__id'];
        }
    } else {
        $href = '/i/i_go/'.$i['i__id'] . ( isset($_GET['load__e']) ? '?load__e='.intval($_GET['load__e']) : '' );
    }


    $toolbar = $editing_enabled && $superpower_12673;
    $e___4737 = $CI->config->item('e___4737'); // Idea Status
    $first_segment = $CI->uri->segment(1);
    $current_i = ( substr($first_segment, 0, 1)=='~' ? intval(substr($first_segment, 1)) : 0 );
    $show_coins = !$has_any_lock && !$discovery_mode;
    $can_click = !$has_any_lock && !$focus_coin && ($discovery_mode || !$editing_enabled);


    if(is_new()){

        //Fetch Creator:
        $i_creators = $CI->X_model->fetch(array(
            'x__type' => 4250, //New Idea Created
            'x__right' => $i['i__id'],
        ), array('x__source'), 0);


        $ui = '<div class="col-sm-6 col-lg-4 mb-4">
      <div class="card">

        <div class="card-body">
          <h5 class="card-title">'.$i['i__title'].'</h5>
          <p class="card-text">';
        foreach($CI->X_model->fetch(array(
            'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type' => 4231,
            'x__right' => $i['i__id'],
        ), array('x__source'), 0, 0, array('x__spectrum' => 'ASC')) as $mes){
            $ui .= $CI->X_model->message_view($mes['x__message'], true, $member_e, 0, true);
        }
        echo '</p>
          <p class="card-text">
            <a href="/@'.$i_creators[0]['e__id'].'"><b>'.$i_creators[0]['e__title'].'</b></a>
            <small class="text-muted"> · ' . view_time_difference(strtotime($i_creators[0]['x__time'])) . '</small>
          </p>
        </div>
        
         <svg class="bd-placeholder-img card-img-top" width="100%" height="200" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"/><text x="50%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>
      
      </div>
    </div>';

        $ui = '<div class="idea_cover">';

            $ui .= '<table><tr>';

            $ui .= '<td><a href="/@'.$i_creators[0]['e__id'].'" class="source_cover">'.view_cover(12273, $i_creators[0]['e__cover'], true).'</a></td>';

            $ui .= '<td style="width: 100%;">';


                $ui .= '<div class="message_bar">';
                $ui .= $CI->X_model->message_view($i['i__title'], true, $member_e, 0, true);

                $ui .= '</div>';


                //Actions:
                $ui .= '<div class="action_bar">';
                    $ui .= '<a href="javascript:void(0);"><i class="fal fa-comment"></i> 2,786</a>';
                    $ui .= '<a href="javascript:void(0);"><i class="fal fa-retweet"></i> 564</a>';
                    $ui .= '<a href="javascript:void(0);"><i class="fal fa-heart"></i> 988</a>';
                    $ui .= '<a href="javascript:void(0);"><i class="fal fa-share-square"></i></a>';
                $ui .= '</div>';


            $ui .= '</td>';
            $ui .= '</tr></table>';

        $ui .= '</div>';


    } else {


        //LOCKED
        $o_menu = '';
        $action_buttons = null;

        if($has_any_lock && !$focus_coin){

            //show lock?

        } elseif(!$cache_app) {

            foreach($CI->config->item(( $focus_coin ? 'e___11047' : 'e___14955' )) as $e__id => $m) {

                //Skip if missing superpower:
                $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m__profile']);
                if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
                    //Missing Superpower
                    continue;
                }

                $anchor = '<span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'];

                if($e__id==14937 && $editing_enabled){
                    $action_buttons .= '<a href="javascript:void(0);" onclick="coin__load(12273,'.$i['i__id'].')" class="dropdown-item css__title">'.$anchor.'</a>'; //COIN COVER
                } elseif($e__id==12589){
                    $action_buttons .= '<a href="javascript:void(0);" onclick="apply_all_load(12589,'.$i['i__id'].')" class="dropdown-item css__title">'.$anchor.'</a>';
                } elseif($e__id==30795 && !$discovery_mode){
                    $action_buttons .= '<a href="/'.$i['i__id'].'" class="dropdown-item css__title">'.$anchor.'</a>';
                } elseif($e__id==6155 && isset($i['x__id']) && in_array($x__type, $CI->config->item('n___6155')) && ($x__type!=6255 || $superpower_10939)){
                    $action_buttons .= '<a href="javascript:void(0);" class="dropdown-item css__title x_remove" i__id="'.$i['i__id'].'" x__id="'.$i['x__id'].'">'.$anchor.'</a>'; //UNLINK
                } elseif($e__id==30873){
                    //Template:
                    $action_buttons .= '<a href="javascript:void(0);" onclick="i_copy('.$i['i__id'].', 1)" class="dropdown-item css__title">'.$anchor.'</a>';
                } elseif($e__id==29771){
                    //Clone:
                    $action_buttons .= '<a href="javascript:void(0);" onclick="i_copy('.$i['i__id'].', 0)" class="dropdown-item css__title">'.$anchor.'</a>';
                } elseif($e__id==28636 && $superpower_12700 && isset($i['x__id']) && $i['x__id']>0){
                    //Transaction Details
                    $action_buttons .= '<a href="/-4341?x__id='.$i['x__id'].'" class="dropdown-item css__title" target="_blank">'.$anchor.'</a>';
                } elseif($e__id==28637 && isset($i['x__type'])){
                    //Paypal Details
                    $x__metadata = unserialize($i['x__metadata']);
                    if(isset($x__metadata['txn_id'])){
                        $action_buttons .= '<a href="https://www.paypal.com/activity/payment/'.$x__metadata['txn_id'].'" class="dropdown-item css__title" target="_blank">'.$anchor.'</a>';
                    }
                } elseif(substr($m['m__message'], 0, 1)=='/'){
                    //Standard button
                    $action_buttons .= '<a href="'.$m['m__message'].$i['i__id'].'" class="dropdown-item css__title">'.$anchor.'</a>';
                }
            }

            //Any Buttons?
            if($action_buttons){
                //Right Action Menu
                $o_menu .= '<div class="dropdown inline-block">';
                $o_menu .= '<button type="button" class="btn no-left-padding no-right-padding css__title" id="action_menu_i_'.$i['i__id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$e___11035[14955]['m__cover'].'</button>';
                $o_menu .= '<div class="dropdown-menu" aria-labelledby="action_menu_i_'.$i['i__id'].'">';
                $o_menu .= $action_buttons;
                $o_menu .= '</div>';
                $o_menu .= '</div>';
            }

        }



        $link_dropdown = '';
        $type_visibility = ''; //visible by default
        $link_visibility = ''; //visible by default
        if((isset($i['x__type']) && in_array($i['x__type'], $CI->config->item('n___4486'))) || (in_array($x__type, $CI->config->item('n___4486')))){
            //Idea Links
            $link_dropdown .= view_input_dropdown(4486, $i['x__type'], null, $editing_enabled, false, $i['i__id'], $i['x__id']);
            $link_visibility = 'show-on-hover';//No need to be visible by default
            $type_visibility = 'show-on-hover';//No need to be visible by default
        } elseif(isset($i['x__type']) && in_array($i['x__type'], $CI->config->item('n___6255'))){
            //Discoveries
            $link_visibility = 'show-on-hover';//No need to be visible by default
            $type_visibility = 'show-on-hover';//No need to be visible by default
        } elseif(isset($i['x__type']) && in_array($i['x__type'], $CI->config->item('n___13550'))){
            //Idea Source Reference
            $link_dropdown .= view_input_dropdown(13550, $i['x__type'], null, $editing_enabled, false, $i['i__id'], $i['x__id']);
        }


        //Top action menu:
        $ui = '<div '.( isset($i['x__id']) ? ' x__id="'.$i['x__id'].'" ' : '' ).' class="coin_cover '.( $focus_coin ? ' focus-coin col-xl-4 col-lg-6 col-md-10 col-12 ' : ' edge-coin col-xl-2 col-lg-3 col-md-4 col-sm-6 col-10 ' ).( $parent_is_or ? ' doborderless ' : '' ).( $has_soft_lock ? ' soft_lock ' : '' ).' no-padding '.( $is_completed ? ' coin-6255 ' : ' coin-12273 ' ).' coin___12273_'.$i['i__id'].' '.( $has_sortable ? ' cover_sort ' : '' ).( isset($i['x__id']) ? ' cover_x_'.$i['x__id'].' ' : '' ).( $has_soft_lock ? ' not-allowed ' : '' ).' '.$extra_class.'" '.( $has_hard_lock ? ' title="'.$e___11035[$x__type]['m__title'].'" data-toggle="tooltip" data-placement="top" ' : ( $has_soft_lock ? ' data-toggle="tooltip" data-placement="top" ' : '' ) ).'>';


        $ui .= '<table class="coin_coins '.( !$discovery_mode ? ' style="" ' : '' ).'"><tr>';

        $ui .= '<td width="20%"><div class="'.$type_visibility.'">';
        if(!$discovery_mode && $editing_enabled){
            $ui .= view_input_dropdown(4737, $i['i__type'], null, $editing_enabled, false, $i['i__id']);
        } elseif($discovery_mode && isset($i['x__type']) && $i['x__type']>0){
            $e___4593 = $CI->config->item('e___4593'); //Transaction Types
            $ui .= '<div title="'.$e___4593[$i['x__type']]['m__title'].'">'.$e___4593[$i['x__type']]['m__cover'].'</div>';
        }
        $ui .= '</div></td>';

        $ui .= '<td width="20%"><div class="'.$link_visibility.'">';
        if(!$has_any_lock && $toolbar && $superpower_12700 && isset($i['x__type'])){
            $ui .= $link_dropdown;
        }
        $ui .= '</div></td>';

        $ui .= '<td width="20%"><div class="show-on-hover">'.($has_sortable ? '<span class="x_sort" title="'.$e___11035[4603]['m__title'].'"><span class="icon-block">'.$e___11035[4603]['m__cover'].'</span></span>' : '').'</div></td>';
        $ui .= '<td width="20%"><div class="show-on-hover">'.( !$can_click && $member_e && !$focus_coin ? '<a href="'.$href.'"><i class="fas fa-arrow-right"></i></a>' : '' ).'</div></td>';
        $ui .= '<td width="20%"><div class="show-on-hover">'.$o_menu.'</div></td>';
        $ui .= '</tr></table>';



        $ui .= '<div class="cover-wrapper">';








        //Coin Cover
        $ui .= ( !$can_click ? '<div' : '<a href="'.$href.'"' ).' class="'.( $is_completed ? ' coinType6255 ' : ' coinType12273 ' ).' black-background-obs cover-link">';


        $ui .= ( !$can_click ? '</div>' : '</a>' );
        $ui .= '</div>'; //cover-wrapper



        //Title Cover
        $ui .= '<div class="cover-content">';

        if($load_completion && $is_started && !$is_completed){
            $ui .= '<div class="cover-progress">'.view_x_progress($completion_rate, $i).'</div>';
        }

        $ui .= '<div class="inner-content">';


        //TITLE
        if($e_of_i && $editing_enabled){
            //Editable title:
            $ui .= view_input_text(4736, $i['i__title'], $i['i__id'], $editing_enabled, (isset($i['x__spectrum']) ? (($i['x__spectrum']*100)+1) : 0), true);
        } elseif($can_click){
            $ui .= '<a href="'.$href.'">'.$i_title.'</a>';
        } else {
            $ui .= $i_title;
        }


        //Fetch minting details:
        $minter = ( $focus_coin && !$discovery_mode ? $CI->X_model->fetch(array(
            'x__type' => 4250, //New Idea Created
            'x__right' => $i['i__id'],
        ), array('x__source')) : array());



        //IDEAs & Time & Message
        $message_tooltip = '';
        if(isset($i['x__message']) && strlen($i['x__message'])>0){

            if(superpower_active(12701, true)){
                $message_tooltip = '<a href="javascript:void(0);" onclick="x_message_load(' . $i['x__id'] . ')" class="mini-font">'.$CI->X_model->message_view( $i['x__message'], true).'</a>';
            }

        } else {

            $messages = '';
            foreach($CI->X_model->fetch(array(
                'x__status IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                'x__type' => 4231,
                'x__right' => $i['i__id'],
            ), array('x__source'), 0, 0, array('x__spectrum' => 'ASC')) as $mes){
                $messages .= $CI->X_model->message_view($mes['x__message'], true, $member_e, 0, true);
            }

            if($e_of_i && $editing_enabled) {
                //Can edit:
                $message_tooltip = '<a href="javascript:void(0);" onclick="load_message_27963(' . $i['i__id'] . ')" class="mini-font messages_4231_' . $i['i__id'] . '">' . (strlen($messages) ? $messages : '<i class="no-message">Write Message...</i>') . '</a>';
            } elseif($can_click){
                $message_tooltip = '<a href="'.$href.'">'.$messages.'</a>';
            } else {
                $message_tooltip = $messages;
            }

        }

        $ui .= '<div class="cover-text">';

        if($message_tooltip){
            $ui .= '<div class="">' . $message_tooltip . '</div>'; //grey
        }

        if(count($minter)){
            //$ui .= '<div class="grey mini-font">Minted <span title="'.$minter[0]['x__time'].' PST">'.view_time_difference(strtotime($minter[0]['x__time'])).' ago</span> by <a href="/@'.$minter[0]['e__id'].'"><u>'.$minter[0]['e__title'].'</u></a></div>';
        }
        $ui .= '</div>';

        $ui .= '</div></div>';

        if($superpower_10939 && !$focus_coin && $show_coins){

            $ui .= '<div class="coin_coins">';
            $ui .= '<span class="hideIfEmpty">'.view_coins_i(12274,  $i['i__id']).'</span>';
            $ui .= '<span class="hideIfEmpty">'.view_coins_i(12273,  $i['i__id']).'</span>';
            //$ui .= '<span class="hideIfEmpty">'.view_coins_i(11019,  $i['i__id']).'</span>';
            //$ui .= '<span class="hideIfEmpty">'.view_coins_i(13542,  $i['i__id']).'</span>';
            $ui .= '<span class="hideIfEmpty">'.view_coins_i(6255,  $i['i__id']).'</span>';
            $ui .= '</div>';

        }


        $ui .= '</div>';


    }





    return $ui;

}



function view_headline($x__type, $counter, $m, $ui, $is_open = true, $left_pad = false){

    if(!strlen($ui)){
        return false;
    }

    $CI =& get_instance();
    $e___26006 = $CI->config->item('e___26006'); //Toggle Headline
    return '<a class="headline" href="javascript:void(0);" onclick="toggle_headline('.$x__type.')"><span class="icon-block">'.$m['m__cover'].'</span>' .$m['m__title'].( !is_null($counter) ? ' [<span class="xtypecounter'.$x__type.'">'.number_format($counter, 0) . '</span>]' : '' ).'<span class="icon-block pull-right headline_title_'.$x__type.'"><span class="icon_26007 '.( !$is_open ? ' hidden ' : '' ).'">'.$e___26006[26008]['m__cover'].'</span><span class="icon_26008 '.( $is_open ? ' hidden ' : '' ).'">'.$e___26006[26007]['m__cover'].'</span></span></a>'.'<div class="headlinebody '.( $left_pad ? ' leftPad  ' : '' ).' headline_body_'.$x__type.( !$is_open ? ' hidden ' : '' ).'">'.$ui.'</div>';

}


function view_pill($x__type, $counter, $m, $ui = null, $is_open = true){

    return '<script> '.( $is_open ? ' $(document).ready(function () { load_tab('.$x__type.'); }); ' : '' ).' $(\'.nav-pills\').append(\'<li class="nav-item thepill'.$x__type.'"><a class="nav-link '.( $is_open ? ' active ' : '' ).'" x__type="'.$x__type.'" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.number_format($counter, 0).' '.$m['m__title'].( strlen($m['m__message']) ? ': '.str_replace('\'','',str_replace('"','',$m['m__message'])) : '' ).'" onclick="toggle_pills('.$x__type.')"><span class="icon-block-xxs">'.$m['m__cover'].'</span><span class="css__title hideIfEmpty xtypecounter'.$x__type.'" style="padding-right:4px;">'.view_number($counter) . '</span></a></li>\') </script>' .
        '<div class="headlinebody headline_body_'.$x__type.( !$is_open ? ' hidden ' : '' ).'" item-counter="'.$counter.'">'.$ui.'</div>';

}

function view_x_progress($completion_rate, $i){

    if(!isset($completion_rate['steps_total'])){
        return '<div class="progress-bg-list progress_'.$i['i__id'].'"><div class="progress-done" style="width:0%" prograte="0"></div></div>';
    }

    return '<div class="progress-bg-list progress_'.$i['i__id'].'" title="'.$completion_rate['completion_percentage'].'% COMPLETED"><div class="progress-done" style="width:'.$completion_rate['completion_percentage'].'%" prograte="'.$completion_rate['completion_percentage'].'"></div></div>';
    //: '.$completion_rate['steps_completed'].'/'.$completion_rate['steps_total'].' IDEAS DISCOVERY
    //data-toggle="tooltip" data-placement="top"

}

function view_e_line($e)
{

    $ui = '<a href="/@'.$e['e__id'].'" class="doblock">';
    $ui .= '<span class="icon-block">'.view_cover(12274, $e['e__cover'], true).'</span>';
    $ui .= '<span class="css__title">'.$e['e__title'].'<span class="grey" style="padding-left:8px;">' . view_time_difference(strtotime($e['x__time'])) . ' Ago</span></span>';
    $ui .= '</a>';
    return $ui;

}



function view_e($x__type, $e, $extra_class = null, $source_of_e = false)
{

    $CI =& get_instance();
    if(!in_array($x__type, $CI->config->item('n___14690'))){
        //Not a valid Source List
        return 'Invalid x__type '.$x__type;
    }
    if(!isset($e['e__id']) || !isset($e['e__title'])){
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view_e() Missing core variables',
            'x__metadata' => array(
                '$x__type' => $x__type,
                '$e' => $e,
            ),
        ));
        return 'Missing core variables';
    }

    $member_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
    $superpower_10939 = superpower_active(10939, true);
    $superpower_12706 = superpower_active(12706, true);
    $superpower_13422 = superpower_active(13422, true);
    $superpower_12701 = superpower_active(12701, true);
    $discovery_mode = in_array($x__type, $CI->config->item('n___14378')); //DISCOVERY MODE
    $focus_coin = in_array($x__type, $CI->config->item('n___12149')); //NODE COIN
    $linkbar_visible = in_array($x__type, $CI->config->item('n___20410'));
    $cache_app = in_array($x__type, $CI->config->item('n___14599'));

    $x__id = ( isset($e['x__id']) ? $e['x__id'] : 0);
    $source_of_e = ($superpower_13422) || ($source_of_e && $member_e) || ($x__id && ($e['x__up']==$member_e['e__id'] || $e['x__down']==$member_e['e__id']));
    $has_note = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___13550')));
    $supports_messages = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___20409')));


    $is_app = $x__type==6287;

    $href = ( $is_app ? '/-'.$e['e__id'] : '/@'.$e['e__id'] );
    $focus__id = ( substr($CI->uri->segment(1), 0, 1)=='@' ? intval(substr($CI->uri->segment(1), 1)) : 0 );
    $has_x_progress = ( $x__id > 0 && (in_array($e['x__type'], $CI->config->item('n___6255')) || $source_of_e));
    $is_public =  in_array($e['e__type'], $CI->config->item('n___7357')); //PUBLIC
    $has_valid_url = filter_var($e['e__cover'], FILTER_VALIDATE_URL);
    $show_custom_image = !$has_valid_url && $e['e__cover'];
    $source_is_e = $focus__id>0 && $e['e__id']==$focus__id;


    //Is Lock/Private?
    $has_hard_lock = in_array($e['e__type'], $CI->config->item('n___30956')) && !$superpower_12701 && (!$member_e || !$source_is_e);
    $has_soft_lock = !$superpower_12701 && ($has_hard_lock || (!$is_public && !$source_of_e && !$superpower_13422));
    $has_any_lock = !$superpower_12701 && ($has_soft_lock || $has_hard_lock);
    $has_sortable = !$has_soft_lock && in_array($x__type, $CI->config->item('n___13911')) && $supports_messages && $superpower_13422 && $x__id > 0;
    $show_text_editor = $source_of_e && !$has_any_lock;
    $can_click = !$focus_coin; //Allow clicking for all

    //Source UI
    $ui  = '<div e__id="' . $e['e__id'] . '" '.( isset($e['x__id']) ? ' x__id="'.$e['x__id'].'" ' : '' ).' class="coin_cover no-padding coin___12274_'.$e['e__id'].' '.$extra_class.( $discovery_mode ? ' coinface-6255 coin-6255 coinface-12274 coin-12274 ' : ' coinface-12274 coin-12274  ' ).( $focus_coin ? ' focus-coin col-xl-4 col-lg-6 col-md-10 col-12 ' : ' edge-coin col-xl-2 col-lg-3 col-md-4 col-sm-6 col-10 ' ).( $show_text_editor ? ' doedit ' : '' ).( $has_sortable ? ' cover_sort ' : '' ).( isset($e['x__id']) ? ' cover_x_'.$e['x__id'].' ' : '' ).( $has_soft_lock ? ' not-allowed ' : '' ).'">';

    $ui .= '<div class="cover-wrapper">';



    //LOCKED
    $edit_button = null;
    $dropdown_ui = false;
    if($source_of_e && !$cache_app) {

        $action_buttons = null;

        //Generate Buttons:
        foreach($CI->config->item(( $focus_coin ? 'e___12887' : 'e___14956' )) as $e__id => $m) {

            $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m__profile']);
            if(count($superpower_actives) && !superpower_active(end($superpower_actives), true)){
                //Missing Superpower
                continue;
            }
            $anchor = '<span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'];

            if($e__id==14937 && $source_of_e){

                //COIN COVER
                $edit_button = '<a href="javascript:void(0);" onclick="coin__load(12274,'.$e['e__id'].')" class="icon-block">'.$m['m__cover'].'</a>';
                //continue;
                $action_buttons .= '<a href="javascript:void(0);" onclick="coin__load(12274,'.$e['e__id'].')" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif($e__id==4997 && superpower_active(12703, true)){

                $action_buttons .= '<a href="javascript:void(0);" onclick="apply_all_load(4997,'.$e['e__id'].')" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif($e__id==13571 && $x__id > 0 && $superpower_13422){

                //Edit Message
                $action_buttons .= '<a href="javascript:void(0);" onclick="x_message_load(' . $x__id . ')" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif($e__id==6287 && in_array($e['e__id'], $CI->config->item('n___6287')) && superpower_active(12699, true)){

                //App Store
                $action_buttons .= '<a href="/-'.$e['e__id'].'" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif($e__id==30873){

                //Template:
                $action_buttons .= '<a href="javascript:void(0);" onclick="e_copy('.$e['e__id'].', 1)" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif($e__id==29771){

                //Clone:
                $action_buttons .= '<a href="javascript:void(0);" onclick="e_copy('.$e['e__id'].', 0)" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif($e__id==10673 && $source_of_e && $x__id > 0){

                //UNLINK
                $action_buttons .= '<a href="javascript:void(0);" onclick="e_remove(' . $x__id . ', '.$e['x__type'].')" class="dropdown-item css__title">'.$anchor.'</span></a>';

            } elseif($e__id==13007 && $focus_coin){

                //Reset Alphabetic order
                $action_buttons .= '<a href="javascript:void(0);" onclick="e_sort_reset()" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif($e__id==6415){

                //Reset my discoveries
                $action_buttons .= '<a href="javascript:void(0);" onclick="e_reset_discoveries('.$e['e__id'].')" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif($e__id=13670 && substr($CI->uri->segment(1), 0, 1)=='~') {

                //Filter applies only when browsing an idea
                $action_buttons .= '<a href="/'.$CI->uri->segment(1). '?load__e=' . $e['e__id'] . '" class="dropdown-item css__title">'.$anchor.'</a>';

            } elseif(substr($m['m__message'], 0, 1)=='/') {

                //Custom Anchor
                $action_buttons .= '<a href="' . $m['m__message'] . $e['e__id'] . '" class="dropdown-item css__title">'.$anchor.'</a>';

            }
        }

        //Any Buttons?
        if($action_buttons){
            //Show menu:
            $dropdown_ui .= '<div class="dropdown inline-block">';
            $dropdown_ui .= '<button type="button" class="btn no-left-padding no-right-padding css__title" id="action_menu_e_'.$e['e__id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$e___11035[14956]['m__cover'].'</button>';
            $dropdown_ui .= '<div class="dropdown-menu" aria-labelledby="action_menu_e_'.$e['e__id'].'">';
            $dropdown_ui .= $action_buttons;
            $dropdown_ui .= '</div>';
            $dropdown_ui .= '</div>';
        }
    }



    //Determine coin type: (Hack removed)
    $cointype = 'coinType12274';
    if ($discovery_mode) { // || substr_count($e['e__cover'], 'fas fa-circle zq6255')
        $cointype = 'coinType12274 coinType6255';
    }
    $cointype = $cointype . ' coinStatus'.$e['e__type'].' ';




    //Top action menu:
    $ui .= '<table class="coin_coins"><tr>';
    $ui .= '<td width="20%"><div class="show-on-hover">'.($source_of_e && $superpower_13422 && !$cache_app ? view_input_dropdown(6177, $e['e__type'], null, $source_of_e && $superpower_13422, false, $e['e__id']) : '').'</div></td>';
    $ui .= '<td width="20%"><div class="show-on-hover">'.($source_of_e && $superpower_13422 && !$cache_app && $x__id ? ( in_array($e['x__type'], $CI->config->item('n___13550')) ? view_input_dropdown(13550, $e['x__type'], null, $source_of_e && $superpower_13422, false, $e['e__id'], $x__id) : '<a href="javascript:void(0);" onclick="x_message_load(' . $e['x__id'] . ')" class="icon-block">'.view_cache(4593, $e['x__type']).'</a>' ) : '').'</div></td>'; //<div class="show-on-hover">'.$edit_button.'</div>
    $ui .= '<td width="20%"><div class="show-on-hover">'.($has_sortable ? '<span class="sort_e hidden" title="'.$e___11035[4603]['m__title'].'"><span class="icon-block">'.$e___11035[4603]['m__cover'].'</span></span>' : '').'</div></td>';
    $ui .= '<td width="20%"><div class="show-on-hover">'.( $can_click && $show_text_editor ? '<a href="'.$href.'"><i class="fas fa-arrow-right"></i></a>' : '' ).'</div></td>';
    $ui .= '<td width="20%"><div class="show-on-hover" title="' . ( isset($e['x__time']) ? view_time_difference(strtotime($e['x__time'])) . ' Ago: '.$e['x__time'] : '' ).'">'.$dropdown_ui.'</div></td>';
    $ui .= '</tr></table>';




    //Coin Cover
    $ui .= ( $can_click && !$show_text_editor ? '<a href="'.$href.'"' : '<div' ).' class="'.$cointype.( !$source_of_e ? ' ready-only ' : '' ).' black-background-obs cover-link" '.( $has_valid_url ? 'style="background-image:url(\''.$e['e__cover'].'\');"' : '' ).'>';

    //ICON?
    $ui .= '<div class="cover-btn">'.($show_custom_image ? view_cover(12274,$e['e__cover'], true) : '' ).'</div>';

    $ui .= ( $can_click && !$show_text_editor ? '</a>' : '</div>' );
    $ui .= '</div>';



    //Title Cover
    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';


    //TITLE
    if($show_text_editor){
        //Editable:
        $ui .= view_input_text(6197, $e['e__title'], $e['e__id'], $source_of_e, ( isset($e['x__spectrum']) ? ($e['x__spectrum']*100)+1 : 0  ), true);
    } else {
        //Static:
        $ui .= '<div class="css__title">'.( $can_click && 0 ? '<a href="'.$href.'" class="css__title">'.$e['e__title'].'</a>' : $e['e__title'] ).'</div>';
    }


    //Fetch minting details:
    /*
    $minter = ( $focus_coin ? $CI->X_model->fetch(array(
        'x__type' => 4251, //New Source Minted
        'x__down' => $e['e__id'],
    ), array('x__source')) : array());
    $ui .= ( count($minter) ? '<div class="cover-text"><div class="grey mini-font" style="padding-top:5px;">Minted <span title="'.$minter[0]['x__time'].' PST">'.view_time_difference(strtotime($minter[0]['x__time'])).' ago</span> by <a href="/@'.$minter[0]['e__id'].'"><u>'.$minter[0]['e__title'].'</u></a></div></div>' : '' );
    */

    //Message
    if ($x__id > 0) {
        if(!$has_any_lock && $supports_messages){

            $ui .= '<span class="x__message mini-font hideIfEmpty x__message_' . $x__id . '" onclick="x_message_load(' . $x__id . ')">'.view_x__message($e['x__message'] , $e['x__type']).'</span>';

        } elseif($has_x_progress && strlen($e['x__message'])){

            //DISCOVERY PROGRESS
            $ui .= '<span class="mini-font">'.$CI->X_model->message_view($e['x__message'], false).'</span>';

        }
    }


    $ui .= '</div></div>';





    //Coin Block
    if($superpower_10939 && !$is_app && !$focus_coin){
        $ui .= '<div class="coin_coins">';
        //$ui .= '<span class="hideIfEmpty">'.view_coins_e(11030,  $e['e__id']).'</span>';
        //$ui .= '<span class="hideIfEmpty">'.view_coins_e(11029,  $e['e__id']).'</span>';
        $ui .= '<span class="hideIfEmpty">'.view_coins_e(12274,  $e['e__id']).'</span>';
        $ui .= '<span class="hideIfEmpty">'.view_coins_e(12273,  $e['e__id']).'</span>';
        $ui .= '<span class="hideIfEmpty">'.view_coins_e(6255,  $e['e__id']).'</span>';
        $ui .= '</div>';
    }



    $ui .= '</div>';

    return $ui;

}


function view_input_text($cache_e__id, $current_value, $s__id, $e_of_i, $tabindex = 0, $extra_large = false){

    $CI =& get_instance();
    $e___12112 = $CI->config->item('e___12112');
    $current_value = htmlentities($current_value);
    $name = 'input'.substr(md5($cache_e__id.$current_value.$s__id.$e_of_i.$tabindex), 0, 8);

    //Define element attributes:
    $attributes = ( $e_of_i ? '' : 'disabled' ).' spellcheck="false" tabindex="'.$tabindex.'" old-value="'.$current_value.'" id="input_'.$cache_e__id.'_'.$s__id.'" class="form-control 
     inline-block editing-mode x_set_class_text text__'.$cache_e__id.'_'.$s__id.( $extra_large?' texttype__lg ' : ' texttype__sm ').' text_e_'.$cache_e__id.'" cache_e__id="'.$cache_e__id.'" s__id="'.$s__id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $focus_element = '<textarea name="'.$name.'" placeholder="'.$e___12112[$cache_e__id]['m__title'].'" '.$attributes.'>'.$current_value.'</textarea>';

    } else {

        $focus_element = '<input type="text" name="'.$name.'" data-lpignore="true" placeholder="__" value="'.$current_value.'" '.$attributes.' />';

    }

    return '<span class="span__'.$cache_e__id.' '.( !$e_of_i ? ' edit-locked ' : '' ).'">'.$focus_element.'</span>';

}




function view_input_dropdown($cache_e__id, $selected_e__id, $btn_class = null, $e_of_i = true, $show_full_name = true, $o__id = 0, $x__id = 0){

    $CI =& get_instance();
    $e___this = $CI->config->item('e___'.$cache_e__id);
    $e___12079 = $CI->config->item('e___12079');

    if(!$selected_e__id || !isset($e___this[$selected_e__id]) || !isset($e___12079[$cache_e__id])){
        return false;
    }

    $e___4527 = $CI->config->item('e___4527');

    $ui = '<div class="dropdown inline-block dropd_'.$cache_e__id.'_'.$o__id.'_'.$x__id.'" selected-val="'.$selected_e__id.'" title="'.$e___12079[$cache_e__id]['m__title'].'">';

    $ui .= '<button type="button" '.( $e_of_i ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' btn-'.$btn_class.'" id="dropdownMenuButton'.$cache_e__id.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn adj-btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked '.$btn_class.'"' ).' >';

    $ui .= '<span class="'.( $show_full_name ? 'icon-block' : 'icon-block' /* icon-block-xs */ ).'">' .$e___this[$selected_e__id]['m__cover'].'</span>'.( $show_full_name ?  $e___this[$selected_e__id]['m__title'] : '' );

    $ui .= '</button>';

    if($e_of_i){
        $ui .= '<div class="dropdown-menu btn-'.$btn_class.'" aria-labelledby="dropdownMenuButton'.$cache_e__id.'">';

        foreach($e___this as $e__id => $m) {

            $superpower_actives = array_intersect($CI->config->item('n___10957'), $m['m__profile']);

            //What type of URL?
            if(substr($m['m__message'], 0, 1)=='/'){

                if(substr_count($m['m__message'], '=$_GET')){
                    //Update URL:
                    $parts = str_replace('&','',$m['m__message']);
                    $parts = one_two_explode('?','',$parts);
                    foreach(explode('=$_GET',$parts) as $part){
                        $m['m__message'] = str_replace($part.'=$_GET',$part.'='.(isset($_GET[$part]) && strlen($_GET[$part])>0 ? $_GET[$part] : ''),$m['m__message']);
                    }
                }

                //Basic transaction:
                $anchor_url = ( $e__id==$selected_e__id ? 'href="javascript:void();"' : 'href="'.$m['m__message'].'" ' );

            } else{

                //Idea Dropdown updater:
                $anchor_url = 'href="javascript:void();" new-en-id="'.$e__id.'" onclick="update_dropdown('.$cache_e__id.', '.$e__id.', '.$o__id.', '.$x__id.', '.intval($show_full_name).')"';

            }

            $ui .= '<a class="dropdown-item dropi_'.$cache_e__id.'_'.$o__id.'_'.$x__id.' css__title optiond_'.$e__id.'_'.$o__id.'_'.$x__id.' '.( $e__id==$selected_e__id ? ' active ' : ( count($superpower_actives) ? superpower_active(end($superpower_actives)) : '' ) ).'" '.$anchor_url.' title="'.$m['m__message'].'"><span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].'</a>';

        }

        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;
}

function view_json($array)
{
    if(!headers_sent()) {
        header('Content-Type: application/json');
    }
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

function view__s($count, $has_e = 0)
{
    //A cute little function to either display the plural "s" or not based on $count
    return ( intval($count) == 1 ? '' : ($has_e ? 'es' : 's'));
}

