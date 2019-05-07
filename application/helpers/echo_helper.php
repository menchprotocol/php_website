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
    echo 'Load ' . (($page * $limit) + 1) . '-' . $max_entities . ' from ' . $en__child_count . ' total';

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

function echo_advance(){
    $CI =& get_instance();
    return ' advance-ui '.( $CI->session->userdata('advance_view_enabled')==1 ? '' : 'hidden ' );
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


function echo_url_type($url, $en_type_id)
{

    /*
     *
     * Displays Entity Links that are a URL based on their
     * $en_type_id as listed under Entity URL Links:
     * https://mench.com/entities/4537
     *
     * */
    if ($en_type_id == 4256 /* Generic URL */) {

        return '<a href="' . $url . '" target="_blank"><span class="url_truncate">' . echo_url_clean($url) . '<i class="fas fa-external-link" style="font-size: 0.7em; padding-left:3px;"></i></span></a>';

    } elseif ($en_type_id == 4257 /* Embed Widget URL? */) {

        return  echo_url_embed($url, $url);

    } elseif ($en_type_id == 4260 /* Image URL */) {

        return '<img src="' . $url . '" style="max-width:240px;" />';

    } elseif ($en_type_id == 4259 /* Audio URL */) {

        return  '<audio controls><source src="' . $url . '" type="audio/mpeg"></audio>' ;

    } elseif ($en_type_id == 4258 /* Video URL */) {

        return  '<video width="100%" onclick="this.play()" controls><source src="' . $url . '" type="video/mp4"></video>' ;

    } elseif ($en_type_id == 4261 /* File URL */) {

        return '<a href="' . $url . '" class="btn btn-primary" target="_blank"><i class="fas fa-cloud-download"></i> Download File</a>';

    } else {

        //Unknown, return null:
        return false;

    }
}




function echo_url_embed($url, $full_message = null, $return_array = false, $start_sec = 0, $end_sec = 0)
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

    if (!$full_message) {
        $full_message = $url;
    }

    //See if $url has a valid embed video in it, and transform it if it does:
    if (substr_count($url, 'youtube.com/watch?v=') == 1 || substr_count($url, 'youtu.be/') == 1 || substr_count($url, 'youtube.com/embed/') == 1) {

        $video_id = extract_youtube_id($url);

        if ($video_id) {

            //Set the Clean URL:
            $clean_url = 'https://www.youtube.com/watch?v=' . $video_id;

            //Inform Student that this video has been sliced:
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

function echo_in_outcome($in_outcome, $fb_messenger_format = false, $reference_attribution = false){

    /*
     * This function applies the double column
     * by either removing or greying out the
     * outcome part before ::
     *
     * */

    if($reference_attribution && substr_count($in_outcome , '::') > 0){

        $CI =& get_instance();
        $attribution_in_id = intval(one_two_explode(' #',' ',$in_outcome));
        if($attribution_in_id > 0){
            //Fetch attribution intent:
            $ins = $CI->Intents_model->in_fetch(array(
                'in_id' => $attribution_in_id,
            ));
        }

        if(!isset($ins[0])){
            //Report error:
            $CI->Links_model->ln_create(array(
                'ln_content' => 'echo_in_outcome() found intent outcome ['.$in_outcome.'] that has colon but not a valid intent reference',
                'ln_type_entity_id' => 4246, //Platform Error
                'ln_miner_entity_id' => 1, //Shervin/Developer
            ));
        } else {
            //All good, replace title:
            $in_outcome = $ins[0]['in_outcome'];
        }

    }


    //See if outcome has a double column:
    if(substr_count($in_outcome , '::') != 1){
        if($fb_messenger_format){
            return $in_outcome;
        } else {
            return htmlentities($in_outcome);
        }
    }

    //We have it, let's apply it:
    $in_outcome_parts = explode('::',$in_outcome,2);

    if($fb_messenger_format){
        return trim($in_outcome_parts[1]);
    } else {
        //Miner view:
        return '<span class="double-column-omit click_expand" data-toggle="tooltip" data-placement="top" title="Not shown to students">'.$in_outcome_parts[0].'::</span><b class="click_expand">'.htmlentities($in_outcome_parts[1]).'</b>';
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

    //Link statuses:
    $fixed_fields = $CI->config->item('fixed_fields');


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item is-msg is_level2_sortable all_msg msg_en_type_' . $ln['ln_type_entity_id'] . '" id="ul-nav-' . $ln['ln_id'] . '" tr-id="' . $ln['ln_id'] . '">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="edit-off text_message" id="msgbody_' . $ln['ln_id'] . '" style="margin:2px 0 0 0;">';

    //Now get the message snippet:
    $ui .= $CI->Communication_model->dispatch_message($ln['ln_content'], $session_en, false, array(), array(), $ln['ln_child_intent_id']);

    $ui .= '</div>';


    //Text editing:
    $ui .= '<textarea onkeyup="changeMessageEditing(' . $ln['ln_id'] . ')" name="ln_content" id="message_body_' . $ln['ln_id'] . '" class="edit-on hidden msg msgin algolia_search" placeholder="Write Message..." style="margin-top: 4px;">' . $ln['ln_content'] . '</textarea>';

    //Editing menu:
    $ui .= '<ul class="msg-nav">';




    //Links:
    $count_msg_trs = $CI->Links_model->ln_fetch(array(
        '( ln_id = ' . $ln['ln_id'] . ' OR ln_parent_link_id = ' . $ln['ln_id'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    $ui .= '<li class="' . echo_advance() . '" style="min-width:48px; display:inline-block;"><a class="btn btn-primary edit-off" style="border:2px solid #ffe027 !important;" href="/links?ln_id=' . $ln['ln_id'] . '" target="_parent" title="Go to Links" data-toggle="tooltip" data-placement="top"><i class="fas fa-link rotate90"></i> '.echo_number($count_msg_trs[0]['totals']).'</a></li>';

    //Modify:
    $ui .= '<li class="edit-off" style="margin-left:0;"><span class="on-hover"><a class="btn btn-primary white-primary" href="javascript:message_modify_start(' . $ln['ln_id'] . ',' . $ln['ln_type_entity_id'] . ');" title="Modify Message" data-toggle="tooltip" data-placement="top" style="border:2px solid #ffe027 !important; margin-right:4px !important;"><i class="fas fa-pen-square"></i></a></span></li>';


    //Status:
    $ui .= '<li class="edit-off message_status" style="margin:0 8px 0 0;"><span title="' . $fixed_fields['ln_status'][$ln['ln_status']]['s_name'] . ': ' . $fixed_fields['ln_status'][$ln['ln_status']]['s_desc'] . '" data-toggle="tooltip" data-placement="top">' . $fixed_fields['ln_status'][$ln['ln_status']]['s_icon'] . '</span></li>';

    //Sort:
    $ui .= '<li class="edit-off"><span title="Drag up/down to sort" data-toggle="tooltip" data-placement="top"><i class="fas fa-sort '.( in_array(4603, $en_all_4485[$ln['ln_type_entity_id']]['m_parents']) ? 'message-sorting' : '' ).'"></i></span></li>';





    //Counter:
    $ui .= '<li class="edit-on hidden"><span id="charNumEditing' . $ln['ln_id'] . '">0</span>/' . $CI->config->item('ln_content_max_length') . '</li>';

    //Save Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary white-primary" title="Save changes" data-toggle="tooltip" data-placement="top" href="javascript:in_message_modify(' . $ln['ln_id'] . ',' . $ln['ln_type_entity_id'] . ');"><i class="fas fa-check"></i> Save</a></li>';

    //Cancel Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary white-primary" title="Cancel editing" data-toggle="tooltip" data-placement="top" href="javascript:message_modify_cancel(' . $ln['ln_id'] . ');"><i class="fas fa-times"></i></a></li>';

    //Show drop down for message link status:
    $ui .= '<li class="pull-right edit-on hidden"><span class="white-wrapper" style="margin:-12px 0 0 0;">';
    $ui .= '<select id="message_ln_status_' . $ln['ln_id'] . '"  class="form-control border" style="margin-bottom:0;" title="Change message status" data-toggle="tooltip" data-placement="top">';
    foreach($fixed_fields['ln_status'] as $status_id => $status){
        $ui .= '<option value="' . $status_id . '" '.( $status_id==$ln['ln_status'] ? 'selected="selected"' : '' ).'>' . $status['s_name'] . '</option>';
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


function echo_icon($en)
{
    //TODO Use this function more often, as there are instnaces where we have this logic replicated! Search for "fas fa-at grey-at" to find...
    //A simple function to display the Entity Icon OR the default icon if not available:
    if (strlen($en['en_icon']) > 0) {
        return $en['en_icon'];
    } else {
        return '<i class="fas fa-at grey-at"></i>';
    }
}

function echo_link($text)
{
    //Find and makes links within $text clickable
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank"><u>$1</u></a>', $text);
}


function echo_number($number, $micro = true, $fb_messenger_format = false)
{

    //Displays number with a nice format

    //Let's see if we need to apply special formatting:
    $formatting = null;

    if ($number > 0 && $number < 1) {

        $original_format = $number; //Keep as is

        //Decimal number, format based on decimal points:
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
                'micro_1' => 'k',
                'micro_0' => ' Thousand',
            );
        } elseif ($number >= 950) {
            $formatting = array(
                'multiplier' => (1 / 1000),
                'decimals' => 1,
                'micro_1' => 'k',
                'micro_0' => ' Thousand',
            );
        }

    }


    if ($formatting) {

        //See what to show:
        $rounded = round(($number * $formatting['multiplier']), $formatting['decimals']);
        $append = $formatting['micro_' . (int)$micro] . (!$micro ? echo__s($rounded) : '');

        if ($fb_messenger_format) {
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

function echo_tr_urls($ln_content, $ln_type_entity_id){

    $ln_content = htmlentities($ln_content);

    $CI =& get_instance();
    if (in_array($ln_type_entity_id, $CI->config->item('en_ids_4537'))) {

        //Entity URL Links
        return echo_url_type($ln_content, $ln_type_entity_id);

    } elseif(strlen($ln_content) > 0) {

        return echo_link($ln_content);

    } else {

        return null;

    }
}

function echo_tr_row($ln, $is_inner = false)
{

    $CI =& get_instance();
    $en_all_4593 = $CI->config->item('en_all_4593');

    if(!isset($en_all_4593[$ln['ln_type_entity_id']])){
        //We've probably have not yet updated php cache, set error:
        $en_all_4593[$ln['ln_type_entity_id']] = array(
            'm_icon' => '<i class="fal fa-exclamation-triangle redalert"></i>',
            'm_name' => 'Link Type Not Synced in PHP Cache',
            'm_desc' => '',
            'm_parents' => array(),
        );
    }

    $hide_sensitive_details = (in_array($ln['ln_type_entity_id'] , $CI->config->item('en_ids_4755')) /* Link Type is locked */ && !en_auth(array(1281)) /* Viewer NOT a moderator */);

    //Fetch Miner Entity:
    $miner_ens = $CI->Entities_model->en_fetch(array(
        'en_id' => $ln['ln_miner_entity_id'],
    ));

    //Display the item
    $ui = '<div class="list-group-item tr-box">';


    //What type of main content do we have, if any?
    $fixed_fields = $CI->config->item('fixed_fields');



    //Link ID Row of data:
    $ui .= '<div style="padding: 0px 0 8px 12px; font-size: 0.9em;">';
    $ui .= '<span data-toggle="tooltip" data-placement="top" title="Status is '.$fixed_fields['ln_status'][$ln['ln_status']]['s_name'].': '.$fixed_fields['ln_status'][$ln['ln_status']]['s_desc'].'" style="min-width:80px; display: inline-block;">'.$fixed_fields['ln_status'][$ln['ln_status']]['s_icon'].' '.$ln['ln_id'].'</span>';
    $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Link Creation Timestamp: ' . $ln['ln_timestamp'] . ' PST" style="min-width:120px; display: inline-block;"><i class="fal fa-clock"></i> ' . echo_time_difference(strtotime($ln['ln_timestamp'])) . ' ago</span>';
    $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Mined Points" style="min-width:47px; display: inline-block;"><i class="fas fa-award"></i> <b>'. $ln['ln_points'] .'</b></span>';



    if($ln['ln_order'] != 0){
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Link ordered '.echo_ordinal_number($ln['ln_order']).' relative to its siblings" style="min-width:30px; display: inline-block;" class="' . echo_advance() . '">'.echo_ordinal_number($ln['ln_order']).'</span>';
    } else {
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Link is not ordered" style="min-width:30px; display: inline-block;" class="' . echo_advance() . '"><i class="fas fa-sort" style="color: #AAA;"></i></span>';
    }


    if(!$hide_sensitive_details && strlen($ln['ln_content']) < 1){
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Link has no content" class="' . echo_advance() . '"><i class="fal fa-comment-slash" style="color: #AAA;"></i></span>';
    }

    //Is this a miner? Show them metadata status:
    if(!$hide_sensitive_details && en_auth(array(1308))){
        if(strlen($ln['ln_metadata']) > 0){
            $ui .= ' &nbsp;<a href="/links/link_json/' . $ln['ln_id'] . '" target="_blank" data-toggle="tooltip" data-placement="top" title="Open link metadata json object (in new window)" style="min-width:26px; display: inline-block;" class="' . echo_advance() . '"><i class="fas fa-search-plus"></i></a>';
        } else {
            $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="No Metadata" style="min-width:26px; display: inline-block;" class="' . echo_advance() . '"><i class="fal fa-search-minus" style="color: #AAA;"></i></span>';
        }
    }

    $ui .= '</div>';


    //Miner and Link Type row:
    $ui .= '<div style="padding:0 10px 12px;">';

        if($hide_sensitive_details){

            //Hide Miner:
            $ui .= '<span class="icon-main"><i class="fal fa-eye-slash"></i></span>';
            $ui .= '<b data-toggle="tooltip" data-placement="top" title="Sign in as a Mench moderator to unlock private information about this link">&nbsp;Private Entity</b>';

        } else {

            //Show Miner:
            $ui .= '<span class="icon-main">'.( strlen($miner_ens[0]['en_icon']) > 0 ? $miner_ens[0]['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).' </span>';
            $ui .= '<a href="/entities/'.$miner_ens[0]['en_id'].'" data-toggle="tooltip" data-placement="top" title="View miner profile"><b>' . one_two_explode('',' ',$miner_ens[0]['en_name']) . '</b></a>';
        }

        //Link Type:
        $ui .= '<a href="/entities/'.$ln['ln_type_entity_id'].'" data-toggle="tooltip" data-placement="top" title="View link type entity"><b style="padding-left:5px;">'. ( strlen($en_all_4593[$ln['ln_type_entity_id']]['m_icon']) > 0 ? '&nbsp;'.$en_all_4593[$ln['ln_type_entity_id']]['m_icon'] : '' ) .'&nbsp;'. $en_all_4593[$ln['ln_type_entity_id']]['m_name'] . '</b></a>';

    $ui .= '</div>';




    //Do we have a content to show?
    if(!$hide_sensitive_details){
        $main_content = echo_tr_urls($ln['ln_content'], $ln['ln_type_entity_id']);
        $ui .= '<div class="e-msg ' . ($main_content ? '' : 'hidden') . '">';
        $ui .= $main_content;
        $ui .= '</div>';
    }

    $ui .= '</div>';

    //Lets go through all references to see what is there:
    if(!$is_inner){

        //Show Links:
        foreach ($CI->config->item('tr_object_links') as $ln_field => $obj_type) {

            if(!(!in_array($ln_field, array('ln_miner_entity_id','ln_type_entity_id')) && intval($ln[$ln_field]) > 0)){
                //Don't show miner and type as they are already printed on the first line:
                continue;
            }

            $ui .= '<div class="tr-child">';
            if($obj_type=='en'){
                //Fetch
                $ens = $CI->Entities_model->en_fetch(array('en_id' => $ln[$ln_field]));
                if(count($ens) > 0){
                    $ui .= echo_en($ens[0], 0);
                }
            } elseif($obj_type=='in'){
                //Fetch
                $ins = $CI->Intents_model->in_fetch(array('in_id' => $ln[$ln_field]));
                if(count($ins) > 0){
                    $ui .= echo_in($ins[0], 0);
                }
            } elseif($obj_type=='ln'){
                //Fetch
                $lns = $CI->Links_model->ln_fetch(array('ln_id' => $ln[$ln_field]));
                if(count($lns) > 0){
                    $ui .= echo_tr_row($lns[0], true);
                }
            }
            $ui .= '</div>';
        }

        //Now show all links for this link:
        foreach ($CI->Links_model->ln_fetch(array(
            'ln_status >=' => 0, //New+
            'ln_parent_link_id' => $ln['ln_id'],
        ), array(), 0, 0, array('ln_id' => 'DESC')) as $ln_child) {
            $ui .= '<div class="tr-child">' . echo_tr_row($ln_child, true) . '</div>';
        }

    }


    return $ui;
}




function echo_in_actionplan_step($in, $is_parent, $step_ln_status)
{

    $CI =& get_instance();


    $ui = '<a href="/messenger/actionplan/' . $in['in_id'] . '" class="list-group-item">';

    //Different pointer position based on direction:
    if ($is_parent) {

        $ui .= '<span class="pull-left">';
        $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
        $ui .= '</span>';

    } else {

        $ui .= '<span class="pull-right">';
        $time_estimate = echo_time_range($in, true);
        if ($time_estimate) {
            $ui .= $time_estimate . ' <i class="fal fa-alarm-clock"></i> ';
        }
        $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-right"></i></span>';
        $ui .= '</span>';

    }

    //Completed Step Status:
    $ui .= echo_fixed_fields('ln_student_status', $step_ln_status, 1, 'right');

    $ui .= ' <span>' . echo_in_outcome($in['in_outcome'], true).'</span>';



    $ui .= '</a>';

    return $ui;
}


function echo_random_message($message_key){

    /*
     *
     * To make Mench personal assistant feel more natural,
     * this function sends varying messages to communicate
     * specific things about Mench or about the student's
     * progress towards their Action Plan.
     *
     * */

    $rotation_index = array(
        'affirm_progress' => array(
            'Got it 👍',
            'Noted',
            'Ok sweet',
            'Nice answer',
            'Nice 👍',
            'Gotcha 🙌',
            'Fabulous',
            'Confirmed',
            '👌',
            '👍',
        ),
        'one_way_only' => array(
            'I am not designed to respond to custom messages. I can understand you only when you choose one of the options that I recommend to you.',
            'I cannot understand if you send me an out-of-context message. I would only understand if you choose one of the options that I recommend to you.',
            'I cannot respond to your custom messages and can only understand if you select one of the options that I recommend to you.',
        ),
        'command_me' => array(
            'You can add a new intention to your Action Plan by sending me a message that starts with "I want to", for example "I want to assess my self as a programmer" or "I want to get hired as a full-stack developer"',
        ),
        'goto_next' => array(
            'When ready, say "Next"',
            'I\'m ready to continue when you are...',
            'Are you ready to move-on?',
            'Ready to move to the next step?',
            'Say next to continue',
        ),

    );

    if(!array_key_exists($message_key, $rotation_index)){

        //Oooopsi, this should never happen:
        $CI =& get_instance();
        $CI->Links_model->ln_create(array(
            'ln_content' => 'echo_random_message() failed to locate message type ['.$message_key.']',
            'ln_type_entity_id' => 4246, //Platform Error
            'ln_miner_entity_id' => 1, //Shervin/Developer
        ));
        return false;

    } else {

        //Return a random message:
        return $rotation_index[$message_key][rand(0, (count($rotation_index[$message_key]) - 1))];

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

    if ($seconds < 30) {
        //Under 30 seconds would not round up to even 1 minute, so don't show:
        return 0;
    } elseif ($seconds <= 5400) {
        return round($seconds / 60) . ($micro ? 'm' : ' Minutes');
    } else {
        //Roundup the hours:
        $hours = round($seconds / 3600);
        return $hours . ($micro ? 'h' : ' Hour' . echo__s($hours));
    }
}


function echo_tree_references($in, $fb_messenger_format = false, $expand_mode = false)
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
            if ($fb_messenger_format) {

                $source_info .= ' ' . $cat_contribution;

            } else {

                $source_info .= ' <span class="show_type_' . $type_id . '"><a href="javascript:void(0);" onclick="$(\'.show_type_' . $type_id . '\').toggle()" style="text-decoration:underline; display:inline-block;">' . $cat_contribution . '</a></span><span class="show_type_' . $type_id . '" style="display:none;">';

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
                    $source_info .= '<a href="/entities/' . $en['en_id'] . '">';

                    $source_info .= $en['en_name'];

                    $source_info .= '</a>';

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

            $is_last_fb_item = ($fb_messenger_format && $count >= $visible_bot);

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

            if ($fb_messenger_format) {

                //Just the name:
                $expert_info .= $en['en_name'];

            } else {

                //HTML Format:
                $expert_info .= '<a href="/entities/' . $en['en_id'] . '">';


                $expert_info .= $en['en_name'];

                $expert_info .= '</a>';

                if (($count + 1) == $visible_html && ($expert_count - $visible_html) > 0) {
                    $expert_info .= '<span class="show_more_' . $in['in_id'] . '"> & <a href="javascript:void(0);" onclick="$(\'.show_more_' . $in['in_id'] . '\').toggle()" style="text-decoration:underline;">' . ($expert_count - $visible_html) . ' more</a>.</span><span class="show_more_' . $in['in_id'] . '" style="display:none;">';
                }
            }
        }

        if (!$fb_messenger_format && ($count + 1) >= $visible_html) {
            //Close the span:
            $expert_info .= '.</span>';
        } elseif ($fb_messenger_format && !$is_last_fb_item) {
            //Close the span:
            $expert_info .= '.';
        }
    }





    $pitch_title = '';
    $pitch = 'Action Plan references ';
    if($source_count > 0){
        $pitch_title .= $source_count . ' Source'. echo__s($source_count);
        $pitch .= trim($source_info);
    }
    if($expert_count > 0){
        if($source_count > 0){
            $pitch_title .= ' by ';
            $pitch .= ' by ';
        }
        $pitch_title .= $expert_count . ' Expert'. echo__s($expert_count);
        $pitch .= $expert_count . ' industry expert'. echo__s($expert_count) . ($expert_count == 1 ? ':' : ' including') . $expert_info;
    }


    if ($fb_messenger_format) {
        return '⭐ ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'IndustryExperts';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;"><i class="fas fa-star" style="transform:none !important;"></i></i> ' . $pitch_title . '<i class="fal fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body overview-pitch">
                    ' . $pitch . '
                </div>
            </div>
        </div></div>';
    }
}

function echo_step_range($in, $educational_mode = false){

    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__metadata_min_steps']) || !isset($metadata['in__metadata_max_steps'])) {
        return ( $educational_mode ? 'Unknown number of steps' : false );
    }

    //Is this a range or a single step value?
    if($metadata['in__metadata_min_steps'] != $metadata['in__metadata_max_steps']){

        //It's a range:
        return ( $educational_mode ? 'between ' : '' ) . $metadata['in__metadata_min_steps'].' - '.$metadata['in__metadata_max_steps'].' Steps' . ( $educational_mode ? ' (depending on your answers to my questions)' : '' );

    } else {

        //A single step value, nothing to educate about here:
        return $metadata['in__metadata_max_steps']. ' Step'.echo__s($metadata['in__metadata_max_steps']);

    }
}

function echo_tree_steps($in, $fb_messenger_format = 0, $expand_mode = false)
{

    /*
     *
     * An intent function to display the total tree intents
     * stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    if (!echo_step_range($in)) {
        return false;
    }

    $pitch = 'Action Plan contains ' . echo_step_range($in, true);

    if ($fb_messenger_format) {

        return '🚶 ' . $pitch . "\n";

    } else {

        //HTML format
        $id = 'IntentOverview';
        $return_html = '';

        //Section header:
        $return_html .= '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                    <i class="fas" style="transform:none !important;"><i class="fas fa-walking" style="transform:none !important;"></i></i> ' . echo_step_range($in) . '<i class="fal fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                </a>
            </h4>
        </div>
        <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
            <div class="panel-body overview-pitch">';

        //TODO Add note for step range to inform students it depends on their chosen answers:
        $return_html .= $pitch.':';

        //Action Plan:
        $return_html .= '<div class="inner_actionplan">';
        $return_html .= echo_public_actionplan($in, false);
        $return_html .= '</div>';

        //Close the section:
        $return_html .= '</div></div></div></div>';

        return $return_html;

    }

}

function echo_public_actionplan($in, $expand_mode){


    $CI =& get_instance();
    $children_ins = $CI->Links_model->ln_fetch(array(
        'ln_status' => 2, //Published
        'in_status' => 2, //Published
        'ln_type_entity_id' => 4228, //Fixed intent links only
        'ln_parent_intent_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

    if(count($children_ins) < 1){
        return null;
    }


    $return_html = '';
    $return_html .= '<div class="list-group grey_list actionplan_list maxout public_ap">';

    foreach ($children_ins as $in_level2_counter => $in_level2) {

        //Level 3 intents:
        $grandchildren_ins = $CI->Links_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'in_status' => 2, //Published
            'ln_type_entity_id' => 4228, //Fixed intent links only
            'ln_parent_intent_id' => $in_level2['in_id'],
        ), array('in_child'), 0, 0, array('ln_order' => 'ASC'));

        //Fetch messages:
        $in_level2_messages = $CI->Links_model->ln_fetch(array(
            'ln_status' => 2, //Published
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $in_level2['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //Skip if intent has no message and no level 3 children:
        if(count($grandchildren_ins) == 0 && count($in_level2_messages) == 0){
            continue;
        }

        //Level 2 title:
        $return_html .= '<div class="panel-group" id="open' . $in_level2_counter . '" role="tablist" aria-multiselectable="true">';
        $return_html .= '<div class="panel panel-primary">';
        $return_html .= '<div class="panel-heading" role="tab" id="heading' . $in_level2_counter . '">';


        $return_html .= '<h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#open' . $in_level2_counter . '" href="#collapse' . $in_level2_counter . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $in_level2_counter . '">' . '<i class="fal fa-plus-circle" style="font-size: 1em !important; margin-left: 0; width: 21px;"></i>'. ( $in['in_type'] ? 'Option #'. ($in_level2_counter + 1).': ' : '') . '<span style="text-decoration:underline;" id="title-' . $in_level2['in_id'] . '">' . echo_in_outcome($in_level2['in_outcome'], true) . '</span>';

        $in_level2_time = echo_time_range($in_level2, true);
        if ($in_level2_time) {
            $return_html .= ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock" style="width:16px; text-transform: none !important;"></i>' . $in_level2_time . '</span>';
        }

        $return_html .= '</a></h4>';
        $return_html .= '</div>';


        //Level 2 body:
        $return_html .= '<div id="collapse' . $in_level2_counter . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '">';
        $return_html .= '<div class="panel-body" style="padding:5px 0 0 25px; font-size:0.85em !important;">';

        //Messages:
        foreach ($in_level2_messages as $ln) {
            $return_html .= $CI->Communication_model->dispatch_message($ln['ln_content']);
        }


        if (count($grandchildren_ins) > 0) {

            //List level 3:
            $return_html .= '<ul style="list-style-type: circle; margin:10px 0 10px -15px; font-size:1em !important;">';
            foreach ($grandchildren_ins as $in_level3_counter => $in_level3) {

                $return_html .= '<li>' . ($in_level2['in_type'] ? 'Option #' . ($in_level3_counter + 1) . ': ' : '') . echo_in_outcome($in_level3['in_outcome'], true);
                $in_level3_time = echo_time_range($in_level3, true);
                if ($in_level3_time) {
                    $return_html .= ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock"></i>' . $in_level3_time . '</span>';
                }
                $return_html .= '</li>';

            }
            $return_html .= '</ul>';

        }

        $return_html .= '</div></div></div></div>';

    }
    $return_html .= '</div>';

    return $return_html;
}

function echo_tree_time_estimate($in, $fb_messenger_format = 0, $expand_mode = false)
{

    /*
     *
     * An intent function to display estimated completion time range
     * for the entire intent tree stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__metadata_max_seconds']) || $metadata['in__metadata_max_seconds'] == 0) {
        return false;
    }

    //As messenger default format and HTML extra notes:
    $pitch  = 'I estimate it would take you '. strtolower(echo_time_range($in)) .' to complete this Action Plan';

    if ($fb_messenger_format) {
        return '⏰ ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'EstimatedTime';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;"><i class="fas fa-alarm-clock" style="transform:none !important;"></i></i> ' . echo_time_range($in) . ' <i class="fal fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body overview-pitch">' . $pitch . '.</div>
            </div>
        </div></div>';
    }
}

function echo_en_messages($ln){

    $CI =& get_instance();
    $session_en = $CI->session->userdata('user');
    $fixed_fields = $CI->config->item('fixed_fields');
    $en_all_4485 = $CI->config->item('en_all_4485');

    $ui = '<div class="entities-msg">';

    $ui .= '<div>';

    //Editing menu:
    $ui .= '<ul class="msg-nav">';


    //Referenced Intent:
    $ui .= '<li><a class="btn btn-primary button-max" style="border:2px solid #ffe027 !important;" href="/intents/' . $ln['ln_child_intent_id'] . '" target="_parent" title="Message Intent: '.$ln['in_outcome'].'" data-toggle="tooltip" data-placement="top">'.$fixed_fields['in_status'][$ln['in_status']]['s_icon'].'&nbsp; '.$fixed_fields['in_type'][$ln['in_type']]['s_icon'].' '.$ln['in_outcome'].'</a></li>';

    //Links:
    $count_msg_trs = $CI->Links_model->ln_fetch(array(
        '( ln_id = ' . $ln['ln_id'] . ' OR ln_parent_link_id = ' . $ln['ln_id'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    $ui .= '<li class="' . echo_advance() . '"><a class="btn btn-primary" style="border:2px solid #ffe027 !important;" href="/links?ln_id=' . $ln['ln_id'] . '" target="_parent" title="Go to Links" data-toggle="tooltip" data-placement="top"><i class="fas fa-link rotate90"></i> '.echo_number($count_msg_trs[0]['totals']).'</a></li>';

    //Intent Note Type:
    $ui .= '<li class="' . echo_advance() . '" style="margin: 0 3px 0 0;"><span title="'.$en_all_4485[$ln['ln_type_entity_id']]['m_name'].': '.$en_all_4485[$ln['ln_type_entity_id']]['m_desc'].'" data-toggle="tooltip" data-placement="top">'.$en_all_4485[$ln['ln_type_entity_id']]['m_icon'].'</span></li>';

    //Link Status:
    $ui .= '<li class="' . echo_advance() . '" style="margin: 0 3px 0 0;"><span title="'.$fixed_fields['ln_status'][$ln['ln_status']]['s_name'].': '.$fixed_fields['ln_status'][$ln['ln_status']]['s_desc'].'" data-toggle="tooltip" data-placement="top">'.$fixed_fields['ln_status'][$ln['ln_status']]['s_icon'].'</span></li>';

    //Order:
    $ui .= '<li class="' . echo_advance() . '" style="margin: 0 3px 0 0;"><span title="Message order relative to siblings" data-toggle="tooltip" data-placement="top"><i class="fas fa-sort"></i>' . echo_ordinal_number($ln['ln_order']) . '</span></li>';

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


function echo_fixed_fields($obj_type = null, $status = null, $micro_status = false, $data_placement = 'bottom')
{

    /*
     *
     * Displays Object Status for Intents, Entities and Links
     * based on the variables defines in fixed_fields
     *
     * */

    $CI =& get_instance();
    $fixed_fields = $CI->config->item('fixed_fields');

    //Return results:
    if (is_null($obj_type)) {

        //Everything
        return $fixed_fields;

    } elseif (is_null($status)) {

        //Object Specific
        if (is_array($obj_type) && count($obj_type) > 0) {
            return $obj_type;
        } else {
            return (isset($fixed_fields[$obj_type]) ? $fixed_fields[$obj_type] : false);
        }

    } else {

        $status = intval($status);
        if (is_array($obj_type) && count($obj_type) > 0) {
            $result = $obj_type[$status];
        } else {
            $result = $fixed_fields[$obj_type][$status];
        }

        if (!$result) {
            //Could not find matching item
            return false;
        } else {
            //We have two skins for displaying statuses:
            if (is_null($data_placement) && $micro_status) {
                return (isset($result['s_icon']) ? $result['s_icon'] . ' ' : '<i class="fas fa-sliders-h"></i> ');
            } else {
                return '<span class="status-label" ' . ((isset($result['s_desc']) || $micro_status) && !is_null($data_placement) ? 'data-toggle="tooltip" data-placement="' . $data_placement . '" title="' . ($micro_status ? $result['s_name'] : '') . (isset($result['s_desc']) ? ($micro_status ? ': ' : '') . $result['s_desc'] : '') . '" style="border-bottom:1px dotted #444; padding-bottom:1px; line-height:140%;"' : 'style="cursor:pointer;"') . '>' . (isset($result['s_icon']) ? $result['s_icon'] : '<i class="fas fa-sliders-h"></i>') . ' ' . ($micro_status ? '' : $result['s_name']) . '</span>';
            }

        }
    }
}


function echo_in_featured($in)
{

    //See if student is logged-in:
    $CI =& get_instance();
    $session_en = en_auth();
    $en_all_2738 = $CI->config->item('en_all_2738');
    $already_in_actionplan = (isset($session_en['en_id']) && count($CI->Links_model->ln_fetch(array(
            'ln_miner_entity_id' => $session_en['en_id'],
            'ln_type_entity_id' => 4235, //Action Plan Set Intention
            'ln_status IN (' . join(',', $CI->config->item('ln_status_incomplete')) . ')' => null, //incomplete intentions
            'ln_parent_intent_id' => $in['in_id'],
        ))) > 0);


    $ui = '<a href="' . ( $already_in_actionplan ? '/messenger/actionplan' : '' ) . '/' . $in['in_id'] . '" class="list-group-item">';

    $ui .= '<span class="pull-right">';
    $ui .= '<span class="badge badge-primary fr-bgd">'.( $already_in_actionplan ? $en_all_2738[6138]['m_icon'] : '<i class="fas fa-angle-right"></i>' ).'</span>';
    $ui .= '</span>';

    $ui .= '<span style="color:#222; font-weight:500; font-size:1.2em;">'.echo_in_outcome($in['in_outcome'], true).'</span>';
    $ui .= '<span style="font-size:0.8em; font-weight:300; margin-left:5px; display:inline-block;">';
    $ui .= '<span><i class="fal fa-clock"></i>' . echo_time_range($in) . '</span>';
    $ui .= '</span>';
    $ui .= '</a>';
    return $ui;
}

function echo_in_answer_scores($starting_in, $depth_levels, $status_min, $original_depth_levels, $parent_in_type){

    if($depth_levels<=0){
        //End recursion:
        return false;
    }

    //We're going 1 level deep:
    $depth_levels--;

    //Go down recursively:
    $CI =& get_instance();
    $fixed_fields = $CI->config->item('fixed_fields');
    $en_all_4486 = $CI->config->item('en_all_4486');

    $ui = null;
    foreach($CI->Links_model->ln_fetch(array(
        'ln_parent_intent_id' => $starting_in,
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
        'ln_status >=' => $status_min,
        'in_status >=' => $status_min,
    ), array('in_child'), 0, 0, array('ln_order' => 'ASC')) as $in_ln){

        //Prep Metadata:
        $metadata = unserialize($in_ln['ln_metadata']);
        $tr__assessment_points = ( isset($metadata['tr__assessment_points']) ? $metadata['tr__assessment_points'] : 0 );
        $messages = $CI->Links_model->ln_fetch(array(
            'ln_status >=' => $status_min,
            'ln_type_entity_id' => 4231, //Intent Note Messages
            'ln_child_intent_id' => $in_ln['in_id'],
        ), array(), 0, 0, array('ln_order' => 'ASC'));

        //Display block:
        $ui .= '<div class="'.( $tr__assessment_points==0 ? 'no-assessment ' : 'has-assessment' ).'">';
        $ui .= '<span style="width: 22px; display:inline-block; text-align: center;" data-toggle="tooltip" data-placement="top" title="Intent Link Type: '.$en_all_4486[$in_ln['ln_type_entity_id']]['m_name'].'">'. $en_all_4486[$in_ln['ln_type_entity_id']]['m_icon'] . '</span>';
        $ui .= '<span style="width: 22px; display:inline-block; text-align: center;" data-toggle="tooltip" data-placement="top" title="Intent Link Status: '.$fixed_fields['ln_status'][$in_ln['ln_status']]['s_name'].'">'. $fixed_fields['ln_status'][$in_ln['ln_status']]['s_icon'] . '</span>';
        $ui .= '<span style="width: 50px; display:inline-block; text-align: left;" data-toggle="tooltip" data-placement="top" title="Milestone Mark">'.( ($in_ln['ln_type_entity_id'] == 4228 && $parent_in_type==1) || ($in_ln['ln_type_entity_id'] == 4229) ? echo_assessment_mark($in_ln) : '' ).'</span>';
        $ui .= '<span style="width:26px; display:inline-block; text-align: center;" data-toggle="tooltip" data-placement="top" title="Intent Type: '.$fixed_fields['in_type'][$in_ln['in_type']]['s_name'].'">'. $fixed_fields['in_type'][$in_ln['in_type']]['s_icon'] . '</span>';
        $ui .= '<span style="width: 30px; display:inline-block; text-align: center;" data-toggle="tooltip" data-placement="top" title="Intent Status: '.$fixed_fields['in_status'][$in_ln['in_status']]['s_name'].'">'. $fixed_fields['in_status'][$in_ln['in_status']]['s_icon']. '</span>';
        $ui .= '<a href="/admin/tools/assessment_marks_birds_eye?starting_in='.$in_ln['in_id'].'&depth_levels='.$original_depth_levels.'&status_min='.$status_min.'" data-toggle="tooltip" data-placement="top" title="Navigate report to this intent"><u>' .  $in_ln['in_outcome'] . '</u></a>';
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
        $ui .=  echo_in_answer_scores($in_ln['in_id'], $depth_levels, $status_min, $original_depth_levels, $in_ln['in_type']);

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
        'ln_status' => 2, //Published
        'en_status' => 2, //Published
    ), array('en_child'), 0, 0, array('ln_order' => 'ASC', 'en_trust_score' => 'DESC')) as $count => $item){

        //Count total children unless its for subscription levels:
        if($parent_en_id!=4454){
            $student_count = $CI->Links_model->ln_fetch(array(
                'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_parent_entity_id' => $item['en_id'],
                'ln_status' => 2, //Published
            ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
        }

        //Echo box:
        $ui .= '<a href="javascript:void(0);" onclick="radio_update('.$parent_en_id.','.$item['en_id'].','.$enable_mulitiselect.')" class="list-group-item item-'.$item['en_id'].' '.( $count>=$show_max ? 'extra-items-'.$parent_en_id.' hidden ' : '' ).( count($CI->Links_model->ln_fetch(array(
                'ln_parent_entity_id' => $item['en_id'],
                'ln_child_entity_id' => $child_en_id,
                'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
                'ln_status' => 2, //Published
            )))>0 ? ' active ' : '' ). '">'.( strlen($item['en_icon'])>0 ? '<span class="left-icon">'.$item['en_icon'].'</span>' : '' ).$item['en_name'].'<span class="change-results"></span>'.( $parent_en_id!=4454 ? '<span class="pull-right">'.echo_number($student_count[0]['totals']).' <i class="fal fa-users"></i></span>' : '' ).'</a>';
    }

    //Did we have too many items?
    if($count>=$show_max){
        //Show "Show more" button
        $ui .= '<a href="javascript:void(0);" class="list-group-item extra-items-'.$parent_en_id.'" onclick="$(\'.extra-items-'.$parent_en_id.'\').toggleClass(\'hidden\')"><i class="fal fa-plus-circle"></i> Show '.($count-$show_max).' more</a>';
    }

    $ui .= '</div>';

    return $ui;
}

function echo_assessment_mark($in_ln){

    //Validate core inputs:
    if(!isset($in_ln['ln_metadata']) || !isset($in_ln['ln_type_entity_id'])){
        return false;
    }

    //prep metadata:
    $ln_metadata = unserialize($in_ln['ln_metadata']);

    //Return mark:
    return ( $in_ln['ln_type_entity_id'] == 4228 ? ( !isset($ln_metadata['tr__assessment_points']) || $ln_metadata['tr__assessment_points'] == 0 ? '' : '<span style="'.( $ln_metadata['tr__assessment_points']>0 ? 'color:#00CC00; font-weight:bold;' : ( $ln_metadata['tr__assessment_points'] < 0 ? 'color:#FF0000; font-weight:bold;' : '' )).'">' . ( $ln_metadata['tr__assessment_points'] > 0 ? '+' : '' ) . $ln_metadata['tr__assessment_points'].'</span>' ) : $ln_metadata['tr__conditional_score_min'] . ( $ln_metadata['tr__conditional_score_min']==$ln_metadata['tr__conditional_score_max'] ? '' : '-'.$ln_metadata['tr__conditional_score_max'] ).'%' );

}

function echo_in($in, $level, $in_parent_id = 0, $is_parent = false)
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
    $fixed_fields = $CI->config->item('fixed_fields');
    $en_all_4331 = $CI->config->item('en_all_4331');
    $is_child_focused = ($level == 3 && $is_parent && $CI->uri->segment(2)==$in['in_id']);

    //Prepare Intent Metadata:
    $in_metadata = unserialize($in['in_metadata']);


    if ($level <= 1) {

        //No Link for level 1 intent:
        $ln_id = 0;
        $ln_metadata = array();

        $ui = '<div class="list-group-item top_intent">';

    } else {

        //Prep link metadata to be analyzed later:
        $ln_id = $in['ln_id'];
        $ln_metadata = unserialize($in['ln_metadata']);

        $ui = '<div in-tr-id="' . $ln_id . '" in-tr-type="' . $in['ln_type_entity_id'] . '" intent-id="' . $in['in_id'] . '" parent-intent-id="' . $in_parent_id . '" intent-level="' . $level . '" class="list-group-item ' . ($level == 3 || ($level == 2 && !$is_parent) ? ' enable-sorting ' : '') . ($level == 3 ? 'is_level3_sortable' : 'is_level2_sortable level2_in') . ' intent_line_' . $in['in_id'] . ( $is_parent && $level!=3 ? ' parent-intent ' : '' ) . ' in__tr_'.$ln_id.'">';

    }





    /*
     *
     * Start Left Side
     *
     * */

    $ui .= '<span style="display:inline-block; margin-top:0px;">';









    //Hidden fields to store dynamic value for on-demand JS modifications:
    //Show Link Status if Available:
    if ($level == 1) {

        //Show Blank box:
        $ui .= '<span class="double-icon" style="margin: 0 2px 0 -4px;"><span class="icon-main"><i class="fas fa-map-pin" data-toggle="tooltip" data-placement="right" title="You are Here"></i></span><span class="icon-top-right">&nbsp;</span></span>';

    } elseif($level > 1) {

        //Fetch Intent Link Connectors:
        $en_all_4486 = $CI->config->item('en_all_4486');

        //Show Link link icons:
        $ui .= '<span class="double-icon" style="margin:0 3px 0 -3px;">';

        //Show larger icon for link type (auto detected based on link content):
        $ui .= '<span class="icon-main ln_type_' . $ln_id . '"><span data-toggle="tooltip" data-placement="right" title="' . $en_all_4486[$in['ln_type_entity_id']]['m_name'] . ': ' . $en_all_4486[$in['ln_type_entity_id']]['m_desc'] . ' @'.$in['ln_type_entity_id'].'">' . $en_all_4486[$in['ln_type_entity_id']]['m_icon'] . '</span></span>';

        //Show smaller link status icon:
        $ui .= '<span class="icon-top-right ln_status_' . $ln_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['ln_status'][$in['ln_status']]['s_name'].' ['.$in['ln_status'].']: '.$fixed_fields['ln_status'][$in['ln_status']]['s_desc'].'">' . $fixed_fields['ln_status'][$in['ln_status']]['s_icon'] . '</span></span>';

        //Count and show total up-votes for this intent correlation (not necessarily this exact link, but the parent/child intent relation)
        $ln_upvotes = $CI->Links_model->ln_fetch(array(
            'ln_parent_intent_id' => $in['ln_parent_intent_id'],
            'ln_child_intent_id' => $in['ln_child_intent_id'],
            'ln_type_entity_id' => 4983, //Up-votes
            'ln_status >=' => 0, //New+
        ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');

        $ui .= '<span class="icon-top-left ' . echo_advance() . ' in_upvotes_' . $in['ln_child_intent_id'] . '_'.$in['ln_parent_intent_id'].'" data-toggle="tooltip" data-placement="right" title="Up-Votes">' . ( $ln_upvotes[0]['totals'] > 0 ? $ln_upvotes[0]['totals'] : '' ) . '</span>';

        //Show Milestone Mark based on Intent Link Type:
        $ui .= '<span class="icon-3rd in_assessment_' . $ln_id . '" data-toggle="tooltip" data-placement="right" title="Milestone Mark">'. echo_assessment_mark($in) .'</span>';

        $ui .= '</span>';

    }




    //Always Show Intent Icon (AND or OR)
    $ui .= '<span class="double-icon" style="margin-right:5px;">';

    //Show larger intent icon (AND or OR):
    $ui .= '<span class="icon-main in_type_' . $in['in_id'] . '"><span class="in_type_val" data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['in_type'][$in['in_type']]['s_name'].' ['.$in['in_type'].']: '.$fixed_fields['in_type'][$in['in_type']]['s_desc'].'">' . $fixed_fields['in_type'][$in['in_type']]['s_icon'] . '</span></span>';

    //Show smaller intent status:
    $ui .= '<span class="icon-top-right in_status_' . $in['in_id'] . '"><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['in_status'][$in['in_status']]['s_name'].' ['.$in['in_status'].']: '.$fixed_fields['in_status'][$in['in_status']]['s_desc'].'">' . $fixed_fields['in_status'][$in['in_status']]['s_icon'] . '</span></span>';

    //Status locked intent?
    if(in_array($in['in_id'],$CI->config->item('in_status_locked'))){
        $ui .= '<span class="icon-top-left" data-toggle="tooltip" data-placement="right" title="Status locked by system"><i class="fas fa-lock"></i></span>';
    }

    $ui .= '<span class="icon-3rd ' . echo_advance() . ' in_completion_' . $in['in_id'] . '" data-toggle="tooltip" data-placement="right" title="Completion Requirement">'.( $in['in_requirement_entity_id'] == 6087 ? '' : $en_all_4331[$in['in_requirement_entity_id']]['m_name'] ).'</span>';

    $ui .= '</span>';




    //Intent UI based on level:
    if ($level <= 1) {

        $ui .= '<span><b id="in_level1_outcome" style="font-size: 1.4em; padding-left: 5px;">';
        $ui .= '<span class="in_outcome_' . $in['in_id'] . '">' . echo_in_outcome($in['in_outcome']) . '</span>';
        $ui .= '</b></span>';

    } elseif ($level == 2) {

        $ui .= '<span>&nbsp;<i id="handle-' . $ln_id . '" class="fal click_expand fa-plus-circle"></i> <span id="title_' . $ln_id . '" style="font-weight: 500;" class="cdr_crnt click_expand tree_title in_outcome_' . $in['in_id'] . '">' . echo_in_outcome($in['in_outcome'], false) . '</span></span>';

    } elseif ($level == 3) {

        $ui .= '<span id="title_' . $ln_id . '" class="tree_title in_outcome_' . $in['in_id'] . '" style="padding-left:23px;">' .echo_in_outcome($in['in_outcome']) . '</span> ';

        //Is this the focused item in the parent sibling dropdown?
        if($is_child_focused){
            $ui .= '<span class="badge badge-primary" style="font-size: 0.8em;"><i class="fas fa-map-pin"></i> You\'re Here</span> ';
        }

    }



    $ui .= '</span>';






    /*
     *
     * Start Right Side
     *
     * */

    $ui .= '<span class="pull-right" style="' . ($level < 3 ? 'margin-right: 8px;' : '') . ' padding-top:2px; display:inline-block;">';



    //Do we have entity parents loaded in our data-set?
    if (!isset($in['in__parents'])) {
        //Fetch parents at this point:
        $in['in__parents'] = $CI->Links_model->ln_fetch(array(
            'ln_status >=' => 0, //New+
            'in_status >=' => 0, //New+
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
            'ln_child_intent_id' => $in['in_id'],
        ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

    }

    //Loop through parents:
    $ui .= '<span class="' . echo_advance() . '">';
    foreach ($in['in__parents'] as $in_parent) {
        $ui .= ' &nbsp;<a href="/intents/' . $in_parent['in_id'] . '" data-toggle="tooltip" title="' . $in_parent['in_outcome'] . '" data-placement="top" class="in_icon_child_' . $in_parent['in_id'] . '">' . $fixed_fields['in_type'][$in_parent['in_type']]['s_icon'] . '</a>';
    }
    $ui .= '</span>';



    $ui .= '<span style="display: inline-block; float: right;">'; //Start of 5x Action Buttons



    //Action Plan:
    $actionplan_steps = $CI->Links_model->ln_fetch(array(
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
        'ln_parent_intent_id' => $in['in_id'],
        'ln_status' => 2, //Published
    ), array(), 0, 0, array(), 'COUNT(ln_id) as total_steps');
    if($actionplan_steps[0]['total_steps'] > 0) {
        $ui .= '<a href="/links?ln_status=2&ln_type_entity_id=' . join(',', $CI->config->item('en_ids_6146')) . '&ln_parent_intent_id=' . $in['in_id'] . '" class="badge badge-primary ' . echo_advance() . '" style="width:40px; margin:-3px -2px 0 4px; border:2px solid #ffe027 !important;" data-toggle="tooltip" data-placement="top" title="Go to Published Action Plan Progression Links @6146"><span class="btn-counter">' . echo_number($actionplan_steps[0]['total_steps']) . '</span>🚩</a>';
    }


    //Intent Notes:
    $count_in_metadata = $CI->Links_model->ln_fetch(array(
        'ln_status >=' => 0, //New+
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
        'ln_child_intent_id' => $in['in_id'],
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    $ui .= '<a href="#intentnotes-' . $in['in_id'] . '" onclick="in_messages_iframe('.$in['in_id'].')" class="msg-badge-' . $in['in_id'] . ' badge badge-primary white-primary is_not_bg '.( $level==0 ? '' . echo_advance() . '' : '' ).'" style="width:40px; margin-right:2px; margin-left:5px;" data-toggle="tooltip" title="Intent Notes" data-placement="top"><span class="btn-counter messages-counter-' . $in['in_id'] . '">' . $count_in_metadata[0]['totals'] . '</span><i class="fas fa-comment-plus"></i></a>';



    //Intent modify:
    $in__metadata_max_seconds = (isset($in_metadata['in__metadata_max_seconds']) ? $in_metadata['in__metadata_max_seconds'] : 0);
    $ui .= '<a class="badge badge-primary white-primary is_not_bg '.( $level==0 ? '' . echo_advance() . '' : '' ).'" onclick="in_modify_load(' . $in['in_id'] . ',' . $ln_id . ')" style="margin:-2px -8px 0 0; width:40px;" href="#loadmodify-' . $in['in_id'] . '-' . $ln_id . '" data-toggle="tooltip" title="Intent completion cost. Click to modify intent'.( $level>1 ? ' and link' : '' ).'" data-placement="top"><span class="btn-counter slim-time t_estimate_' . $in['in_id'] . '" tree-max-seconds="' . $in__metadata_max_seconds . '" intent-seconds="' . $in['in_seconds_cost'] . '">'.( $in__metadata_max_seconds > 0 ? echo_time_hours($in__metadata_max_seconds , true) : 0 ).'</span><i class="fas fa-cog"></i></a> &nbsp;';






    //Intent Links:
    $count_in_trs = $CI->Links_model->ln_fetch(array(
        '(ln_parent_intent_id=' . $in['in_id'] . ' OR ln_child_intent_id=' . $in['in_id'] . ($ln_id > 0 ? ' OR ln_parent_link_id=' . $ln_id : '') . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    //Show link to load these links:
    $ui .= '<a href="/links?any_in_id=' . $in['in_id'] . '&ln_parent_link_id=' . $ln_id . '" class="badge badge-primary ' . echo_advance() . ' is_not_bg" style="width:40px; margin:-3px 0px 0 4px; border:2px solid #ffe027 !important;" data-toggle="tooltip" data-placement="top" title="Go to Links"><span class="btn-counter">' . echo_number($count_in_trs[0]['totals']) . '</span><i class="fas fa-link rotate90"></i></a>';

    $tree_count = null;
    $tree_count_range = '0';
    if(isset($in_metadata['in__metadata_max_steps']) && isset($in_metadata['in__metadata_min_steps'])){
        $tree_count = '<span class="btn-counter children-counter-' . $in['in_id'] . ' ' . ($is_parent && $level == 2 ? 'inb-counter' : '') . '">' . ( $in_metadata['in__metadata_min_steps']==$in_metadata['in__metadata_max_steps'] ? $in_metadata['in__metadata_max_steps'] : '~'.round(($in_metadata['in__metadata_min_steps']+$in_metadata['in__metadata_max_steps'])/2) ) . '</span>';
        $tree_count_range = ( $in_metadata['in__metadata_min_steps']==$in_metadata['in__metadata_max_steps'] ? $in_metadata['in__metadata_max_steps'] : $in_metadata['in__metadata_min_steps'].'-'.$in_metadata['in__metadata_max_steps'] );
    }

    //Intent Link to Travel Down/UP the Tree:
    if ($level == 0) {

        //Show Landing Page URL:
        $ui .= '&nbsp;<a href="/intents/' . $in['in_id'] . '" data-toggle="tooltip" title="'.$tree_count_range.' published intents in tree. Go to this intent." data-placement="top" class="badge badge-primary is_not_bg is_hard_link" style="display:inline-block; margin-right:-2px; width:40px; border:2px solid #ffe027 !important;">'.$tree_count.'<i class="fas fa-angle-right"></i></a>';

    } elseif ($level == 1 || $is_child_focused) {

        //Show Landing Page URL:
        $ui .= '&nbsp;<a href="/' . $in['in_id'] . '" target="_blank" class="badge badge-primary is_not_bg is_hard_link" style="display:inline-block; margin-right:-2px; width:40px; border:2px solid #ffe027 !important;" data-toggle="tooltip" title="'.$tree_count_range.' published intents in tree. Open landing page in a new window." data-placement="top">'.$tree_count.'<i class="fas fa-shopping-cart" style="margin-left: -3px;"></i></a>';

    } else {

        $ui .= '&nbsp;<a href="/intents/' . $in['in_id'] . '" class="tree-badge-' . $in['in_id'] . ' badge badge-primary is_not_bg is_hard_link" style="display:inline-block; margin-right:-2px; width:40px; border:2px solid #ffe027 !important;" data-toggle="tooltip" title="'.$tree_count_range.' published intents in tree. Go to this intent." data-placement="top">' . $tree_count . '<i class="'.( $is_parent ? ( $level==3 ? 'fas fa-angle-right' : 'fas fa-angle-up' ) : ( $level==3 ? 'fas fa-angle-double-down' : 'fas fa-angle-down' ) ).'"></i></a>';

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

        //Fetch children if parent, since there are no children fetched:
        if(!isset($in['in__grandchildren'])){
            $in['in__grandchildren'] = $CI->Links_model->ln_fetch(array(
                'ln_status >=' => 0, //New+
                'in_status >=' => 0, //New+
                'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Connectors
                'ln_parent_intent_id' => $in['in_id'],
            ), array('in_child'), 0, 0, array('ln_order' => 'ASC')); //Child intents must be ordered
        }


        $ui .= '<div id="list-cr-' . $ln_id . '" class="list-group step-group cr-class-' . $ln_id . ' hidden" intent-id="' . $in['in_id'] . '">';
        //This line enables the in-between list moves to happen for empty lists:
        $ui .= '<div class="is_level3_sortable dropin-box" style="height:1px;">&nbsp;</div>';


        if (isset($in['in__grandchildren']) && count($in['in__grandchildren']) > 0) {
            foreach ($in['in__grandchildren'] as $grandchild_in) {
                $ui .= echo_in($grandchild_in, ($level + 1), $in['in_id'], $is_parent);
            }
        }


        $ui .= '<div class="list-group-item list_input new-in3-input cr-class-' . $ln_id . ' hidden">
                <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="in_link_or_create(' . $in['in_id'] . ',3);" intent-id="' . $in['in_id'] . '"><input type="text" class="form-control autosearch intentadder-id-'.$in['in_id'].' algolia_search" maxlength="' . $CI->config->item('in_outcome_max') . '" id="addintent-cr-' . $ln_id . '" intent-id="' . $in['in_id'] . '" placeholder="Add #Intent"></form></div>
        </div>';

        $ui .= '<div class="algolia_search_pad in_pad_'.$in['in_id'].' hidden"><span>Search existing intents or create a new one...</span></div>';

        //Load JS search for this input:
        $ui .= '<script> $(document).ready(function () { in_load_search(".intentadder-id-'.$in['in_id'].'", 0, 3); }); </script>';

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
        return echo_ordinal_number($integer);
    }
}


function echo_en($en, $level, $is_parent = false)
{

    $CI =& get_instance();
    $session_en = $CI->session->userdata('user');
    $fixed_fields = $CI->config->item('fixed_fields');
    $ln_id = (isset($en['ln_id']) ? $en['ln_id'] : 0);
    $ui = null;


    $ui .= '<div entity-id="' . $en['en_id'] . '" en-status="' . $en['en_status'] . '" tr-id="'.$ln_id.'" ln-status="'.( $ln_id > 0 ? $en['ln_status'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '" class="list-group-item en-item en___' . $en['en_id'] . ' ' . ($level <= 1 ? 'top_entity' : 'tr_' . $en['ln_id']) . ( $is_parent ? ' parent-entity ' : '' ) . '">';





    $ui .= '<span style="display:inline-block; margin-top:0px;">';






    //Hidden fields to store dynamic value for on-demand JS modifications:
    //Show Link Status if Available:
    if ($ln_id > 0) {

        //Link Type Full List:
        $en_all_4593 = $CI->config->item('en_all_4593');

        //Show Link link icons:
        $ui .= '<span class="double-icon" style="margin-right:7px;">';

        //Show larger icon for link type (auto detected based on link content):
        $ui .= '<span class="icon-main ln_type_' . $ln_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4593[$en['ln_type_entity_id']]['m_name'].' @'.$en['ln_type_entity_id'].'">' . $en_all_4593[$en['ln_type_entity_id']]['m_icon'] . '</span></span> ';

        //Show smaller link status icon:
        $ui .= '<span class="icon-top-right ln_status_' . $ln_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['ln_status'][$en['ln_status']]['s_name'].' ['.$en['ln_status'].']: '.$fixed_fields['ln_status'][$en['ln_status']]['s_desc'].'">' . $fixed_fields['ln_status'][$en['ln_status']]['s_icon'] . '</span></span>';

        $ui .= '</span>';

    } elseif( $level > 0 ) {

        //Show Blank box:
        $ui .= '<span class="double-icon" style="margin:0 3px;"><span class="icon-main"><i class="fas fa-map-pin" data-toggle="tooltip" data-placement="right" title="You are Here"></i></span><span class="icon-top-right">&nbsp;</span></span>';

    }





    //Always Show Entity Icons
    $ui .= '<span class="double-icon" style="margin-right:7px;">';

    //Show larger custom entity icon:
    $ui .= '<span class="icon-main en_ui_icon en_ui_icon_' . $en['en_id'] . ' en-icon en__icon_'.$en['en_id'].'" en-is-set="'.( strlen($en['en_icon']) > 0 ? 1 : 0 ).'" data-toggle="tooltip" data-placement="right" title="Entity Icon">' . echo_icon($en) . '</span>';

    //Show smaller entity status:
    $ui .= '<span class="icon-top-right en_status_' . $en['en_id'] . '"><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['en_status'][$en['en_status']]['s_name'].' ['.$en['en_status'].']: '.$fixed_fields['en_status'][$en['en_status']]['s_desc'].'">' . $fixed_fields['en_status'][$en['en_status']]['s_icon'] . '</span></span>';

    //Status locked intent?
    if($en['en_psid'] > 0){
        $ui .= '<span class="icon-top-left" data-toggle="tooltip" data-placement="right" title="Student connected to Mench on Messenger">';
        if(en_auth(array(1281))){
            //Give Facebook profile ping option to Moderators:
            $ui .= '<a href="/messenger/api_fetch_profile/'.$en['en_id'].'" target="_blank"><i class="fas fa-badge-check blue" style="font-size: 1.1em;"></i></a>';
        } else {
            $ui .= '<i class="fas fa-badge-check blue" style="font-size: 1.1em;"></i>';
        }
        $ui .= '</span>';
    }

    $ui .= '</span>';



    //Entity Name:
    $ui .= '<span class="en_name en_name_' . $en['en_id'] . '">' . $en['en_name'] . '</span>';

    $ui .= '</span>';



    //Does this entity also include a link?
    if ($ln_id > 0) {

        //Show link content:
        $ln_content = echo_tr_urls($en['ln_content'] , $en['ln_type_entity_id']);

        //Is this Entity link an Embeddable URL type or not?
        $ui .= ' <span class="ln_content ln_content_' . $ln_id . '" style="min-width:240px; line-height: 140%; display:inline-block;">';
        $ui .= $ln_content;
        $ui .= '</span>';

        //This is for JS editing:
        $ui .= '<span class="ln_content_val_' . $ln_id . ' hidden">' . $en['ln_content'] . '</span>';

    }






    //Right content:
    $ui .= '<span class="pull-right" style="padding-top:2px;">';

    //Do we have entity parents loaded in our data-set? If not, load it:
    if (!isset($en['en__parents'])) {
        //Fetch parents at this point:
        $en['en__parents'] = $CI->Links_model->ln_fetch(array(
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_child_entity_id' => $en['en_id'], //This child entity
            'ln_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_parent'), 0, 0, array('en_trust_score' => 'DESC'));
    }

    //Loop through parents and only show those that have en_icon set:
    $ui .= '<span class="' . echo_advance() . '">';
    foreach ($en['en__parents'] as $en_parent) {
        $ui .= ' &nbsp;<a href="/entities/' . $en_parent['en_id'] . '" data-toggle="tooltip" title="' . $en_parent['en_name'] . (strlen($en_parent['ln_content']) > 0 ? ' = ' . $en_parent['ln_content'] : '') . '" data-placement="top" class="parent-icon en_child_icon_' . $en_parent['en_id'] . '">' . (strlen($en_parent['en_icon']) > 0 ? $en_parent['en_icon'] : '<i class="fas fa-at grey-at"></i>' ) . '</a>';
    }
    $ui .= '</span>';




    $ui .= '<span style="display: inline-block; float: right;">'; //Start of 5x Action Buttons





    //Action Plan:
    $actionplan_steps = $CI->Links_model->ln_fetch(array(
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_6146')) . ')' => null, //Action Plan Progression Link Types
        'ln_miner_entity_id' => $en['en_id'],
        'ln_status' => 2, //Published
    ), array(), 0, 0, array(), 'COUNT(ln_id) as total_steps');
    if($actionplan_steps[0]['total_steps'] > 0){
        $ui .= '<a href="/links?ln_status=2&ln_type_entity_id=' . join(',', $CI->config->item('en_ids_6146')) . '&ln_miner_entity_id=' . $en['en_id'] . '" class="badge badge-secondary ' . echo_advance() . '" style="width:40px; margin:-3px -2px 0 6px; border:2px solid #0084ff !important;" data-toggle="tooltip" data-placement="top" title="Go to Published Action Plan Progression Links @6146"><span class="btn-counter">'.echo_number($actionplan_steps[0]['total_steps']).'</span>🚩</a>';
    }




    //Count & Display active Intent Notes that this entity has been referenced within:
    $messages = $CI->Links_model->ln_fetch(array(
        'ln_status >=' => 0, //New+
        'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
        'ln_parent_entity_id' => $en['en_id'], //Entity Referenced in message content
    ), array(), 0, 0, array(), 'COUNT(ln_id) AS total_messages');
    if($messages[0]['total_messages'] > 0){
        //Only show in non-advance mode if we have messages:
        $ui .= '<a class="badge badge-secondary white-secondary '.( $level==0 || $messages[0]['total_messages'] == 0 ? echo_advance() : '' ) . '" href="#entityreferences-' . $en['en_id'] . '" onclick="' . ( $messages[0]['total_messages'] == 0 ? 'alert(\'No Intent Notes found that reference this entity\')' : ( $level==0 ? 'alert(\'Cannot manage here. Go to the entity to manage.\')' : 'en_load_messages('.$en['en_id'].')' ) ) . '" style="width:40px; margin-left:5px; margin-right: -3px;" data-toggle="tooltip" data-placement="top" title="Entity References within Intent Notes"><span class="btn-counter">' . echo_number($messages[0]['total_messages']) . '</span><i class="fas fa-comment-plus"></i></a>';
    }



    //Modify Entity:
    $ui .= '<a href="#loadmodify-' . $en['en_id'] . '-' . $ln_id . '" onclick="'.( $level==0 ? 'alert(\'Cannot manage here. Go to the entity to manage.\')' : 'en_modify_load(' . $en['en_id'] . ',' . $ln_id . ')' ).'" class="badge badge-secondary white-secondary '.( $level==0 ? '' . echo_advance() . '' : '' ).'" style="margin:-2px -6px 0 5px; width:40px;" data-toggle="tooltip" data-placement="top" title="Entity trust score. Click to modify entity'.( $level>1 ? ' and link' : '' ).'"><span class="btn-counter ' . echo_advance() . '">'.echo_number($en['en_trust_score']).'</span><i class="fas fa-cog" style="width:28px; padding-right:7px; text-align:center;"></i></a> &nbsp;';





    //Count & link to Entity links:
    $count_in_trs = $CI->Links_model->ln_fetch(array(
        '(ln_parent_entity_id=' . $en['en_id'] . ' OR  ln_child_entity_id=' . $en['en_id'] . ' OR  ln_miner_entity_id=' . $en['en_id'] . ($ln_id > 0 ? ' OR ln_parent_link_id=' . $ln_id : '') . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(ln_id) as totals');
    if ($count_in_trs[0]['totals'] > 0) {
        //Show the link button:
        $ui .= '<a href="/links?any_en_id=' . $en['en_id'] . '&ln_parent_link_id=' . $ln_id . '" class="badge badge-secondary ' . echo_advance() . '" style="width:40px; margin:-3px 2px 0 2px; border:2px solid #0084ff !important;" data-toggle="tooltip" data-placement="top" title="Go to Links"><span class="btn-counter">' . echo_number($count_in_trs[0]['totals']) . '</span><i class="fas fa-link rotate90"></i></a>';
    }






    //Have we counted the Entity Children?
    if (!isset($en['en__child_count'])) {
        //Assume none:
        $en['en__child_count'] = 0;

        //Do a child count:
        $child_trs = $CI->Links_model->ln_fetch(array(
            'ln_parent_entity_id' => $en['en_id'],
            'ln_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'ln_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

        if (count($child_trs) > 0) {
            $en['en__child_count'] = intval($child_trs[0]['en__child_count']);
        }
    }

    if($level == 1){

        $ui .= '<a class="badge badge-secondary" href="https://www.google.com/search?q=' . urlencode($en['en_name']) . '" target="_blank" style="display:inline-block; margin-right:6px; width:40px; margin-left:1px; border:2px solid #0084ff !important;" data-toggle="tooltip" data-placement="top" title="Google Search (New Window)"><span class="btn-counter ' . echo_advance() . '"><i class="fas fa-external-link"></i></span><i class="fas fa-search"></i></a>';

    } else {

        $ui .= '<a class="badge badge-secondary" href="/entities/' . $en['en_id']. '" style="display:inline-block; margin-right:6px; width:40px; margin-left:1px; border:2px solid #0084ff !important;" data-toggle="tooltip" data-placement="top" title="Go to this Entity">' . ($en['en__child_count'] > 0 ? '<span class="btn-counter ' . echo_advance() . '" title="' . number_format($en['en__child_count'], 0) . ' Entities">' . echo_number($en['en__child_count']) . '</span>' : '') . '<i class="'.( $level==0 ? 'fas fa-angle-right' : ( $is_parent ? 'fas fa-angle-up' : 'fas fa-angle-down' )).'"></i></a>';

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

