<?php


function fn___echo_en_load_more($page, $limit, $en__child_count)
{

    /*
     * Gives an option to "Load More" entities when we have too many to show in one go
     * */

    echo '<a class="load-more list-group-item" href="javascript:void(0);" onclick="fn___en_load_next_page(' . $page . ')">';

    //Right content:
    echo '<span class="pull-right" style="margin-right: 6px;"><span class="badge badge-secondary"><i class="fas fa-search-plus"></i></span></span>';

    //Regular section:
    $max_entities = (($page + 1) * $limit);
    $max_entities = ($max_entities > $en__child_count ? $en__child_count : $max_entities);
    echo 'Load ' . (($page * $limit) + 1) . '-' . $max_entities . ' from ' . $en__child_count . ' total';

    echo '</a>';
}


function fn___echo_time_minutes($sec_int)
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


function fn___echo_url_type($url, $en_type_id)
{

    /*
     *
     * Displays Entity Links that are a URL based on their
     * $en_type_id as listed under Entity URL Links:
     * https://mench.com/entities/4537
     *
     * */
    if ($en_type_id == 4256 /* Generic URL */) {

        return '<a href="' . $url . '" target="_blank"><span class="url_truncate"><i class="fas fa-link" style="margin-right:3px;"></i>' . fn___echo_url_clean($url) . '</span></a>';

    } elseif ($en_type_id == 4257 /* Embed Widget URL? */) {

        return  fn___echo_url_embed($url, $url);

    } elseif ($en_type_id == 4260 /* Image URL */) {

        return '<img src="' . $url . '" style="max-width:240px" />';

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


function fn___echo_youtube_id($url)
{

    //Attemp to extract YouTube ID from URL:
    $video_id = null;

    if (substr_count($url, 'youtube.com/embed/') == 1) {

        //We might have start and end here too!
        $video_id = trim(fn___one_two_explode('youtube.com/embed/', '?', $url));

    } elseif (substr_count($url, 'youtube.com/watch?v=') == 1) {

        $video_id = trim(fn___one_two_explode('youtube.com/watch?v=', '&', $url));

    } elseif (substr_count($url, 'youtu.be/') == 1) {

        $video_id = trim(fn___one_two_explode('youtu.be/', '?', $url));

    }

    //This should be 11 characters!
    if (strlen($video_id) == 11) {
        return $video_id;
    } else {
        return false;
    }
}


function fn___echo_url_embed($url, $full_message = null, $return_array = false, $start_sec = 0, $end_sec = 0)
{


    /*
     *
     * Detects and displays URLs from supported website with an embed widget
     *
     * NOTE: Changes to this function requires us to re-calculate all current
     *       values for tr_type_en_id as this could change the equation for those
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

        $video_id = fn___echo_youtube_id($url);

        if ($video_id) {

            //Set the Clean URL:
            $clean_url = 'https://www.youtube.com/watch?v=' . $video_id;

            //Inform Student that this video has been sliced:
            if ($start_sec || $end_sec) {
                $embed_html_code .= '<div class="video-prefix"><i class="fab fa-youtube"></i> Watch ' . (($start_sec && $end_sec) ? 'this <b>' . fn___echo_time_minutes(($end_sec - $start_sec)) . '</b> video clip' : 'from <b>' . ($start_sec ? fn___echo_time_minutes($start_sec) : 'start') . '</b> to <b>' . ($end_sec ? fn___echo_time_minutes($end_sec) : 'end') . '</b>') . ':</div>';
            }

            $embed_html_code .= '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="//www.youtube.com/embed/' . $video_id . '?theme=light&color=white&keyboard=1&autohide=2&modestbranding=1&showinfo=0&rel=0&iv_load_policy=3&start=' . $start_sec . ($end_sec ? '&end=' . $end_sec : '') . '" frameborder="0" allowfullscreen class="yt-video"></iframe></div>';

        }

    } elseif (substr_count($url, 'vimeo.com/') == 1) {

        //Seems to be Vimeo:
        $video_id = trim(fn___one_two_explode('vimeo.com/', '?', $url));

        //This should be an integer!
        if (intval($video_id) == $video_id) {
            $clean_url = 'https://vimeo.com/' . $video_id;
            $embed_html_code = '<div class="yt-container video-sorting" style="margin-top:5px;"><iframe src="https://player.vimeo.com/video/' . $video_id . '?title=0&byline=0" class="yt-video" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></div>';
        }

    } elseif (substr_count($url, 'wistia.com/medias/') == 1) {

        //Seems to be Wistia:
        $video_id = trim(fn___one_two_explode('wistia.com/medias/', '?', $url));
        $clean_url = trim(fn___one_two_explode('', '?', $url));
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


function fn___echo_in_message_manage($tr)
{

    /*
     *
     * A wrapper function that helps manage messages
     * by giving the message additional matrix functions
     * such as editing and changing message type.
     *
     * */

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');

    //Fetch all possible Intent Messages to enable the Miner to change message type:
    $en_all_4485 = $CI->config->item('en_all_4485');


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item is-msg is_level2_sortable all_msg msg_en_type_' . $tr['tr_type_en_id'] . '" id="ul-nav-' . $tr['tr_id'] . '" tr-id="' . $tr['tr_id'] . '">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="edit-off text_message" id="msgbody_' . $tr['tr_id'] . '" style="margin:2px 0 0 0;">';

    //Now get the message snippet:
    $ui .= $CI->Chat_model->fn___dispatch_message($tr['tr_content'], $udata);

    $ui .= '</div>';


    //Text editing:
    $ui .= '<textarea onkeyup="fn___changeMessageEditing(' . $tr['tr_id'] . ')" name="tr_content" id="message_body_' . $tr['tr_id'] . '" class="edit-on hidden msg msgin algolia_search" placeholder="Write Message..." style="margin-top: 4px;">' . $tr['tr_content'] . '</textarea>';

    //Editing menu:
    $ui .= '<ul class="msg-nav">';

    $ui .= '<li class="edit-off message_status" style="margin: 0 1px 0 -1px;"><span title="' . $en_all_4485[$tr['tr_type_en_id']]['m_name'] . ': ' . stripslashes($en_all_4485[$tr['tr_type_en_id']]['m_desc']) . '" data-toggle="tooltip" data-placement="top">' . $en_all_4485[$tr['tr_type_en_id']]['m_icon'] . '</span></li>';
    $ui .= '<li class="edit-on hidden"><span id="charNumEditing' . $tr['tr_id'] . '">0</span>/' . $CI->config->item('tr_content_max') . '</li>';

    $ui .= '<li class="edit-off" style="margin: 0 0 0 8px;"><span class="on-hover"><i class="fas double-sort fa-sort sort_message" tr-id="' . $tr['tr_id'] . '" style="color:#2f2739;"></i></span></li>';
    $ui .= '<li class="edit-off" style="margin-right: 10px; margin-left: 6px;"><span class="on-hover"><a href="javascript:fn___message_remove(' . $tr['tr_id'] . ');"><i class="fas fa-trash-alt" style="margin:0 7px 0 5px;"></i></a></span></li>';
    $ui .= '<li class="edit-off" style="margin-left:-4px;"><span class="on-hover"><a href="javascript:fn___message_modify_start(' . $tr['tr_id'] . ',' . $tr['tr_type_en_id'] . ');"><i class="fas fa-pen-square"></i></a></span></li>';
    //Right side reverse:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary" href="javascript:fn___in_message_modify(' . $tr['tr_id'] . ',' . $tr['tr_type_en_id'] . ');" style="text-decoration:none; font-weight:bold; padding: 1px 8px 4px;"><i class="fas fa-check"></i></a></li>';
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-hidden" href="javascript:fn___message_modify_cancel(' . $tr['tr_id'] . ');"><i class="fas fa-times" style="color:#2f2739"></i></a></li>';

    //Show drop down for message type adjustment:
    $ui .= '<li class="pull-right edit-on hidden">';
    $ui .= '<select id="en_all_4485_' . $tr['tr_id'] . '">';
    foreach ($en_all_4485 as $tr_type_en_id => $m) {
        $ui .= '<option value="' . $tr_type_en_id . '">' . $m['m_name'] . '</option>';
    }
    $ui .= '</select>';
    $ui .= '</li>';

    $ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors

    $ui .= '</ul>';

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function fn___echo_en_icon($en)
{
    //A simple function to display the Entity Icon OR the default icon if not available:
    if (strlen($en['en_icon']) > 0) {
        return $en['en_icon'];
    } else {
        return '<i class="fas fa-at grey-at"></i>';
    }
}

function fn___echo_link($text)
{
    //Find and makes links within $text clickable
    return preg_replace('!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', '<a href="$1" target="_blank"><u>$1</u></a>', $text);
}


function fn___echo_number($number, $micro = true, $fb_messenger_format = false)
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

    } elseif ($number >= 1000) {

        $original_format = number_format($number); //Add commas

        if ($number >= 1000000000) {
            $formatting = array(
                'multiplier' => (1 / 1000000000),
                'decimals' => 1,
                'micro_1' => 'B',
                'micro_0' => ' Billion',
            );
        } elseif ($number >= 10000000) {
            $formatting = array(
                'multiplier' => (1 / 1000000),
                'decimals' => 0,
                'micro_1' => 'M',
                'micro_0' => ' Million',
            );
        } elseif ($number >= 1000000) {
            $formatting = array(
                'multiplier' => (1 / 1000000),
                'decimals' => 1,
                'micro_1' => 'M',
                'micro_0' => ' Million',
            );
        } elseif ($number >= 10000) {
            $formatting = array(
                'multiplier' => (1 / 1000),
                'decimals' => 0,
                'micro_1' => 'k',
                'micro_0' => ' Thousand',
            );
        } elseif ($number >= 1000) {
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
        $append = $formatting['micro_' . (int)$micro] . (!$micro ? fn___echo__s($rounded) : '');

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

function fn___echo_tr_content($tr_content, $tr_type_en_id){

    $CI =& get_instance();
    if (in_array($tr_type_en_id, $CI->config->item('en_ids_4537'))) {

        return fn___echo_url_type($tr_content, $tr_type_en_id);

    } elseif(strlen($tr_content) > 0) {

        return fn___echo_link($tr_content);

    } else {

        return null;

    }
}

function fn___echo_tr_row($tr)
{

    $CI =& get_instance();

    //Display the item
    $ui = '<div class="list-group-item" style="padding:12px 6px 6px 0px; min-height:84px;">';

    //Right content:
    $ui .= '<span class="pull-right">';


    //Lets go through all references to see what is there:
    foreach ($CI->config->item('ledger_filters') as $tr_field => $obj_type) {
        $ui .= fn___echo_tr_column($obj_type, $tr[$tr_field], $tr_field, false);
    }

    if ($tr['tr_type_en_id'] == 4235) {

        //Count Total Transactions made by Action Plan Student:
        $count_en_trs = $CI->Database_model->fn___tr_fetch(array(
            'tr_miner_en_id' => $tr['tr_en_parent_id'],
        ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
        $ui .= '<a href="#browseledger-' . $tr['tr_en_parent_id'] . '-' . $tr['tr_id'] . '" onclick="fn___load_en_ledger(' . $tr['tr_en_parent_id'] . ',' . $tr['tr_id'] . ')" class="badge badge-secondary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="' . $count_en_trs[0]['totals'] . ' Total Transactions credited to this Student"><span class="btn-counter">' . fn___echo_number($count_en_trs[0]['totals']) . '</span><i class="fas fa-atlas"></i></a>';


        //Number of intents in Student Action Plan & Its completion Percentage:
        $count_in_actionplans = $CI->Database_model->fn___tr_fetch(array(
            'tr_type_en_id' => 4559, //Action Plan Intents
            'tr_tr_parent_id' => $tr['tr_id'],
        ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
        if ($count_in_actionplans[0]['totals'] > 0) {

            //Yes, this intent has been added to some Action Plans, let's see what % is completed so far:
            $count_in_actionplans_complete = $CI->Database_model->fn___tr_fetch(array(
                'tr_type_en_id' => 4559, //Action Plan Intents
                'tr_tr_parent_id' => $tr['tr_id'],
                'tr_status NOT IN (' . join(',', $CI->config->item('tr_status_incomplete')) . ')' => null, //completed
            ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');

            //Show link to load these intents in Student Action Plans:
            $ui .= '<a href="#wactionplan-' . $tr['tr_id'] . '-' . $tr['tr_en_parent_id'] . '" onclick="load_w_actionplan(' . $tr['tr_id'] . ',' . $tr['tr_en_parent_id'] . ')" class="badge badge-primary" style="width:40px; margin-right:2px;" data-toggle="tooltip" data-placement="left" title="' . $count_in_actionplans_complete[0]['totals'] . '/' . $count_in_actionplans[0]['totals'] . ' completed (or skipped)"><span class="btn-counter">' . round($count_in_actionplans_complete[0]['totals'] / $count_in_actionplans[0]['totals'] * 100) . '%</span><i class="fas fa-flag" style="font-size:0.85em;"></i></a>';

        }

    }





    $ui .= '</span>';

    //What type of main content do we have, if any?
    $main_content = null;
    $object_statuses = $CI->config->item('object_statuses');


    //Fetch content for this link:
    $main_content = fn___echo_tr_content($tr['tr_content'], $tr['tr_type_en_id']);

    $ui .= '<span class="badge badge-primary transparent" style="width:40px; margin:0;" data-toggle="tooltip" data-placement="top" title="Mench Coins"><span class="btn-counter">' . $tr['tr_coins'] . '</span><i class="fal fa-coins"></i></span>';


    $ui .= '<b>' . $tr['en_name'] . '</b>';
    $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Transaction ID" style="font-size:0.8em;"><i class="fas fa-atlas"></i> '.$tr['tr_id'].'</span>';
    $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Transaction Last Modified: ' . $tr['tr_timestamp'] . '" style="font-size:0.8em;"><i class="fal fa-clock"></i> ' . fn___echo_time_difference(strtotime($tr['tr_timestamp'])) . ' ago</span>';
    $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Transaction Status: '.$object_statuses['tr_status'][$tr['tr_status']]['s_desc'].'" style="font-size:0.8em;">'.$object_statuses['tr_status'][$tr['tr_status']]['s_icon'].' '.$object_statuses['tr_status'][$tr['tr_status']]['s_name'].'</span>';


    if($tr['tr_order'] != 0){
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Transaction Relative Order" style="font-size:0.8em;"><i class="fas fa-sort-numeric-down"></i> '.$tr['tr_order'].'</span>';
    } else {
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Not Ordered" style="font-size:0.8em;"><i class="fas fa-sort-numeric-down" style="color: #AAA;"></i></span>';
    }

    if(strlen($tr['tr_metadata']) > 0){
        $ui .= ' &nbsp;<a href="/ledger/fn___tr_json/' . $tr['tr_id'] . '" target="_blank" data-toggle="tooltip" data-placement="top" title="Open Transaction Metadata in New Window" style="font-size:0.8em;"><i class="fas fa-search-plus"></i> <b>Metadata</b></a>';
    } else {
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="No Metadata" style="font-size:0.8em;"><i class="fal fa-search-minus" style="color: #AAA;"></i></span>';
    }

    //Do we have a message?
    $ui .= '<div class="e-msg ' . ($main_content ? '' : 'hidden') . '">';
    $ui .= $main_content;
    $ui .= '</div>';


    $ui .= '</div>';

    return $ui;
}

function echo_k($k, $is_parent, $in_is_any_tr_in_parent_id = 0)
{

    $ui = '<a href="' . ($in_is_any_tr_in_parent_id ? '/master/choose_any_path/' . $k['tr_id'] . '/' . $in_is_any_tr_in_parent_id . '/' . $k['in_id'] . '/' . md5($k['tr_id'] . 'kjaghksjha*(^' . $k['in_id'] . $in_is_any_tr_in_parent_id) : '/master/actionplan/' . $k['tr_tr_parent_id'] . '/' . $k['in_id']) . '" class="list-group-item">';

    //Different pointer position based on direction:
    if ($is_parent) {
        $ui .= '<span class="pull-left">';
        $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
        $ui .= '</span>';
    } else {
        $ui .= '<span class="pull-right">';
        $ui .= '<span class="badge badge-primary fr-bgd">' . ($in_is_any_tr_in_parent_id ? 'Select <i class="fas fa-check-circle"></i>' : '<i class="fas fa-angle-right"></i>') . '</span>';
        $ui .= '</span>';

        //For children show icon:
        if ($in_is_any_tr_in_parent_id) {
            //Radio button to indicate a single selection:
            $ui .= '<span class="status-label" style="padding-bottom:1px;"><i class="fal fa-circle"></i> </span>';
        } else {
            //Proper status:
            $ui .= fn___echo_status('tr_status', $k['tr_status'], 1, 'right');
        }
    }

    $ui .= ' ' . $k['in_outcome'];
    if (strlen($k['tr_content']) > 0) {
        $ui .= ' <i class="fas fa-edit"></i> ' . htmlentities($k['tr_content']);
    }

    $ui .= '</a>';

    return $ui;
}


function fn___echo_url_clean($url)
{
    //Returns the watered-down version of the URL for a cleaner UI:
    return rtrim(str_replace('http://', '', str_replace('https://', '', str_replace('www.', '', $url))), '/');
}


function fn___echo_time_hours($seconds, $micro = false)
{

    /*
     * A function that will return a fancy string representing hours & minutes
     *
     * This also has an equal Javascript function fn___echo_js_hours() which we
     * want to make sure has more/less the same logic...
     *
     * */

    if ($seconds < 1) {
        return '0' . ($micro ? 'm' : ' Minutes ');
    } elseif ($seconds <= 5400) {
        return round($seconds / 60) . ($micro ? 'm' : ' Minutes');
    } else {
        //Roundup the hours:
        $hours = round($seconds / 3600);
        return $hours . ($micro ? 'h' : ' Hour' . fn___echo__s($hours));
    }
}


function fn___echo_in_referenced_content($in, $fb_messenger_format = false, $expand_mode = false)
{

    /*
     *
     * An intent function to display the cached referenced content
     * that is stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__tree_contents']) || count($metadata['in__tree_contents']) < 1) {
        return false;
    }

    //Let's count to see how many content pieces we have references for this intent tree:
    $all_count = 0;
    foreach ($metadata['in__tree_contents'] as $type_en_id => $referenced_ens) {
        $all_count += count($referenced_ens);
    }

    if ($all_count > 0) {

        //Set some variables and settings to get started:
        $CI =& get_instance();
        $type_all_count = count($metadata['in__tree_contents']);
        $en_all_3000 = $CI->config->item('en_all_3000');
        $is_miner = fn___en_auth(array(1308));
        $visible_ppl = 3; //How many people to show before clicking on "see more"
        $type_count = 0;
        $text_overview = '';
        foreach ($metadata['in__tree_contents'] as $type_id => $referenced_ens) {

            if ($type_count > 0) {
                if (($type_count + 1) >= $type_all_count) {
                    $text_overview .= ' &';
                } else {
                    $text_overview .= ',';
                }
            }

            //Show category:
            $cat_contribution = count($referenced_ens) . ' ' . $en_all_3000[$type_id]['m_name'];
            if ($fb_messenger_format) {

                $text_overview .= ' ' . $cat_contribution;

            } else {

                $text_overview .= ' <span class="show_type_' . $type_id . '"><a href="javascript:void(0);" onclick="$(\'.show_type_' . $type_id . '\').toggle()" style="text-decoration:underline; display:inline-block;">' . $cat_contribution . '</a></span><span class="show_type_' . $type_id . '" style="display:none;">';

                //We only show details on our website's HTML landing pages:
                $count = 0;
                foreach ($referenced_ens as $en) {

                    if ($count > 0) {
                        if (($count + 1) >= count($referenced_ens)) {
                            $text_overview .= ' &';
                        } else {
                            $text_overview .= ',';
                        }
                    }

                    $text_overview .= ' ';

                    if ($is_miner) {
                        //Show link to matrix:
                        $text_overview .= '<a href="/entities/' . $en['en_id'] . '" class="underdot">';
                    }

                    $text_overview .= $en['en_name'];

                    if ($is_miner) {
                        $text_overview .= '</a>';
                    }
                    $count++;
                }
                $text_overview .= '</span>';

            }
            $type_count++;
        }
    }

    //Return results:
    if ($all_count == 0) {
        return false;
    }


    $pitch = 'Action Plan references' . $text_overview . ' from industry experts.';
    if ($fb_messenger_format) {
        return '📚 ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'ContentReferences';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">📚</i> ' . $all_count . ' Reference' . fn___echo__s($all_count) . '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">' . $pitch . '</div>
            </div>
        </div></div>';
    }
}


function fn___echo_in_cost_range($in, $fb_messenger_format = 0, $expand_mode = false)
{

    /*
     *
     * An intent function to display the cached cost of the
     * intent tree that is stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__tree_max_cost']) || $metadata['in__tree_max_cost'] <= 0) {
        return false;
    }

    //Construct UI:
    if (round($metadata['in__tree_max_cost']) == round($metadata['in__tree_min_cost']) || $metadata['in__tree_min_cost'] == 0) {
        //Single price:
        $price_range = '$' . round($metadata['in__tree_max_cost']) . ' USD';
    } else {
        //Price range:
        $price_range = 'between $' . round($metadata['in__tree_min_cost']) . ' to $' . round($metadata['in__tree_max_cost']) . ' USD';
    }


    $pitch = 'Action Plan recommends ' . $price_range . ' in third-party product purchases.';
    if ($fb_messenger_format) {
        return '💸 ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'CostForcast';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">💸</i> ' . ucwords($price_range) . '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">' . $pitch . '</div>
            </div>
        </div></div>';
    }
}

function fn___echo_in_overview($in, $fb_messenger_format = 0, $expand_mode = false)
{

    /*
     *
     * An intent function to display the total tree intents
     * stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__tree_in_published_count']) || $metadata['in__tree_in_published_count'] < 1) {
        return false;
    }

    $pitch = 'Action Plan contains ' . $metadata['in__flat_unique_published_count'] . ' key ideas that will help you ' . $in['in_outcome'] . '.';

    if ($fb_messenger_format) {
        return '🚩 ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'IntentOverview';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                    <i class="fas" style="transform:none !important;">💡</i> ' . $metadata['in__flat_unique_published_count'] . ' Key Ideas<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                </a>
            </h4>
        </div>
        <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
            <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">' . $pitch . '</div>
        </div>
    </div></div>';
    }

}

function fn___echo_in_time_estimate($in, $fb_messenger_format = 0, $expand_mode = false)
{

    /*
     *
     * An intent function to display estimated completion range
     * for the entire intent tree stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__tree_max_seconds']) || $metadata['in__tree_max_seconds'] == 0) {
        return false;
    }

    $pitch = 'Action Plan estimates that it will take ' . strtolower(fn___echo_time_range($in)) . ' to ' . $in['in_outcome'] . '.';
    if ($fb_messenger_format) {
        return '⏰ ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'EstimatedTime';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">⏰</i> ' . ucwords(fn___echo_time_range($in)) . '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">' . $pitch . '</div>
            </div>
        </div></div>';
    }
}

function fn___echo_in_experts($in, $fb_messenger_format = 0, $expand_mode = false)
{

    /*
     *
     * An intent function to display referenced experts for
     * the entire intent tree stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__tree_experts']) || count($metadata['in__tree_experts']) < 1) {
        return false;
    }

    //Define some variables to get stared:
    $all_count = count($metadata['in__tree_experts']);
    $visible_html = 4; //Landing page, beyond this is hidden and visible with a click
    $visible_bot = 10; //Plain text style, but beyond this is cut out!
    $is_miner = fn___en_auth(array(1308)); //If true, will link referenced entities to the Matrix for easier management
    $text_overview = '';

    foreach ($metadata['in__tree_experts'] as $count => $en) {

        $is_last_fb_item = ($fb_messenger_format && $count >= $visible_bot);

        if ($count > 0) {
            if (($count + 1) >= $all_count || $is_last_fb_item) {
                $text_overview .= ' &';
                if ($is_last_fb_item) {
                    $text_overview .= ' ' . ($all_count - $visible_bot) . ' more!';
                    break;
                }
            } else {
                $text_overview .= ',';
            }
        }

        $text_overview .= ' ';

        if ($fb_messenger_format) {

            //Just the name:
            $text_overview .= $en['en_name'];

        } else {

            //HTML Format:
            if ($is_miner) {
                $text_overview .= '<a href="/entities/' . $en['en_id'] . '">';
            }

            $text_overview .= $en['en_name'];

            if ($is_miner) {
                $text_overview .= '</a>';
            }

            if (($count + 1) == $visible_html && ($all_count - $visible_html) > 0) {
                $text_overview .= '<span class="show_more_' . $in['in_id'] . '"> & <a href="javascript:void(0);" onclick="$(\'.show_more_' . $in['in_id'] . '\').toggle()" style="text-decoration:underline;">' . ($all_count - $visible_html) . ' more</a>.</span><span class="show_more_' . $in['in_id'] . '" style="display:none;">';
            }
        }
    }

    if (!$fb_messenger_format && ($count + 1) >= $visible_html) {
        //Close the span:
        $text_overview .= '.</span>';
    } elseif ($fb_messenger_format && !$is_last_fb_item) {
        //Close the span:
        $text_overview .= '.';
    }


    $pitch = 'Action Plan quotes ' . $all_count . ' industry expert' . fn___echo__s($all_count) . ($all_count == 1 ? ':' : ' including') . $text_overview;
    if ($fb_messenger_format) {
        return '🎓 ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'IndustryExperts';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">🎓</i> ' . $all_count . ' Industry Expert' . fn___echo__s($all_count) . '<i class="fas fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body" style="padding:5px 0 0 5px; font-size:1.1em;">
                    ' . $pitch . '
                </div>
            </div>
        </div></div>';
    }
}


function fn___echo_time_range($in, $micro = false)
{

    //Make sure we have metadata passed on via $in as sometimes it might miss it (Like when passed on via Algolia results...)
    if (!isset($in['in_metadata'])) {
        //We don't have it, so fetch it:
        $CI =& get_instance();
        $ins = $CI->Database_model->fn___in_fetch(array(
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

    if (!isset($metadata['in__tree_max_seconds']) || !isset($metadata['in__tree_min_seconds'])) {
        return false;
    }

    //Construct the UI:
    if ($metadata['in__tree_max_seconds'] == $metadata['in__tree_min_seconds']) {

        //Exactly the same, show a single value:
        return fn___echo_time_hours($metadata['in__tree_max_seconds'], $micro);

    } elseif ($metadata['in__tree_min_seconds'] < 3600) {

        if ($metadata['in__tree_min_seconds'] < 7200 && $metadata['in__tree_max_seconds'] < 10800 && ($metadata['in__tree_max_seconds'] - $metadata['in__tree_min_seconds']) > 1800) {
            $is_minutes = true;
        } elseif ($metadata['in__tree_min_seconds'] < 36000) {
            $is_minutes = false;
            $hours_decimal = 1;
        } else {
            //Number too large to matter, just treat as one:
            return fn___echo_time_hours($metadata['in__tree_max_seconds'], $micro);
        }

    } else {
        $is_minutes = false;
        $hours_decimal = 0;
    }

    //Generate hours range:
    $the_min = ($is_minutes ? round($metadata['in__tree_min_seconds'] / 60) : round(($metadata['in__tree_min_seconds'] / 3600), $hours_decimal));
    $the_max = ($is_minutes ? round($metadata['in__tree_max_seconds'] / 60) : round(($metadata['in__tree_max_seconds'] / 3600), $hours_decimal));
    $ui_time = $the_min;
    if($the_min != $the_max){
        $ui_time .= '-';
        $ui_time .= $the_max;
    }
    $ui_time .= ($is_minutes ? ($micro ? 'm' : ' Minutes') : ($micro ? 'h' : ' Hours'));

    //Generate UI to return:
    return $ui_time;
}



function fn___echo_tr_column($obj_type, $id, $tr_field, $fb_messenger_format = false)
{

    /*
     *
     * Displays intents, entities and transactions from the Ledger
     * Loads the name (and possibly URL) for $obj_type with id=$id
     *
     * */

    $CI =& get_instance();
    $id = intval($id);
    $object_statuses = $CI->config->item('object_statuses');
    if (!array_key_exists($obj_type, $CI->config->item('core_objects'))) {
        return false;
    }


    if ($obj_type == 'in') {

        $is_parent = ( $tr_field == 'tr_in_parent_id' );

        if((is_null($id) || $id==0)){
            if($fb_messenger_format){
                return false;
            } else {
                return '<span class="badge badge-primary grey '.( $is_parent ? '' : 'tuc-right-under' ).'" style="width:40px;" data-toggle="tooltip" data-placement="top" title="No '.( $is_parent ? 'Parent' : 'Child' ).' Intent"><i class="fas fa-hashtag"></i></span> ';
            }
        }

        //Fetch Intent:
        $ins = $CI->Database_model->fn___in_fetch(array(
            'in_id' => $id,
        ));
        if (count($ins) < 1) {
            //Should not happen:
            return false;
        }

        if ($fb_messenger_format) {
            //Plain view:
            return $ins[0]['in_outcome'] . ' [https://mench.com/intents/' . $ins[0]['in_id'] . ']';
        } else {
            //HTML view:
            return '<a href="/intents/' . $ins[0]['in_id'] . '" target="_parent" class="badge badge-primary '.( $is_parent ? '' : 'tuc-right-under' ).'" style="width:40px;" data-toggle="tooltip" data-placement="top" title="' . stripslashes($ins[0]['in_outcome']) . '">'.$object_statuses['in_is_any'][$ins[0]['in_is_any']]['s_icon'].'</a> ';
        }

    } elseif ($obj_type == 'en') {

        $tuc_right_under = null;

        //4 different entity types could pass from here:
        if($tr_field == 'tr_miner_en_id'){

            $name = 'Miner Entity';
            $tuc_right_under = 'tuc-right-under';

        } elseif($tr_field == 'tr_en_parent_id'){

            $name = 'Parent Entity';


        } elseif($tr_field == 'tr_en_child_id'){

            $name = 'Child Entity';
            $tuc_right_under = 'tuc-right-under';


        } elseif($tr_field == 'tr_type_en_id'){

            $name = 'Transaction Type';


        } else {
            //Should not happen:
            return false;
        }



        if((is_null($id) || $id==0)){
            if($fb_messenger_format){
                return false;
            } else {
                return '<span class="badge badge-primary grey '.$tuc_right_under.'" style="width:40px;" data-toggle="tooltip" data-placement="top" title="No '.$name.'"><i class="fas fa-at"></i></span> ';
            }
        }

        $ens = $CI->Database_model->fn___en_fetch(array(
            'en_id' => $id,
        ));
        if (count($ens) < 1) {
            //Should not happen:
            return false;
        }

        if ($fb_messenger_format) {
            //Plain view:
            return $ens[0]['en_name'] . ' [https://mench.com/entities/' . $id . ']';
        } else {
            //HTML Format:
            return '<a href="/entities/' . $id . '" target="_parent" class="badge badge-secondary '.$tuc_right_under.'" style="width:40px;" data-toggle="tooltip" data-placement="top" title="' . $name . ': ' . stripslashes($ens[0]['en_name']) . '">' . fn___echo_en_icon($ens[0]) . '</a> ';
        }

    } elseif ($obj_type == 'tr') {

        if((is_null($id) || $id==0)){
            if($fb_messenger_format){
                return false;
            } else {
                return '<span class="badge badge-primary grey tuc-midway" style="width:40px;" data-toggle="tooltip" data-placement="top" title="No Parent Transaction"><i class="fas fa-atlas"></i></span> ';
            }
        }

        if ($fb_messenger_format) {
            //Plain view:
            return 'Transaction '.$id.' [https://mench.com/ledger?tr_id=' . $id . ']';
        } else {
            //HTML View:
            return '<a href="/ledger?tr_id=' . $id . '" target="_parent" class="badge badge-primary white tuc-midway" style="width:40px;" data-toggle="tooltip" data-placement="top" title="Parent Transaction '.$id.'"><i class="fas fa-atlas"></i></a> ';
        }

    }
}


function fn___echo_time_difference($t, $second_time = null)
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


function fn___echo_time_date($t, $date_only = false)
{
    if (!$t) {
        return 'NOW';
    }
    $timestamp = (is_numeric($t) ? $t : strtotime(substr($t, 0, 19)));
    $year = (date("Y") == date("Y", $timestamp));
    return date(($year ? "D M j " : "j M Y") . ($date_only ? "" : " H:i:s"), $timestamp);
}


function fn___echo_status($obj_type = null, $status = null, $micro_status = false, $data_placement = 'bottom')
{

    /*
     *
     * Displays Object Statuses for Intents, Entities and Transactions
     * based on the variables defines in object_statuses
     *
     * */

    $CI =& get_instance();
    $object_statuses = $CI->config->item('object_statuses');

    //Return results:
    if (is_null($obj_type)) {

        //Everything
        return $object_statuses;

    } elseif (is_null($status)) {

        //Object Specific
        if (is_array($obj_type) && count($obj_type) > 0) {
            return $obj_type;
        } else {
            return (isset($object_statuses[$obj_type]) ? $object_statuses[$obj_type] : false);
        }

    } else {

        $status = intval($status);
        if (is_array($obj_type) && count($obj_type) > 0) {
            $result = $obj_type[$status];
        } else {
            $result = $object_statuses[$obj_type][$status];
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

function fn___echo_last_updated($obj_type, $tr){

    //Validate object type:
    $CI =& get_instance();
    $core_objects = $CI->config->item('core_objects');
    if(!array_key_exists($obj_type, $core_objects)){
        return 'Invalid Object Type';
    }

    //Return updated line:
    return $core_objects[$obj_type].' updated <b data-toggle="tooltip" title="Transaction #'.$tr['tr_id'].' updated on '.fn___echo_time_date($tr['tr_timestamp']).' PST" data-placement="top">'.fn___echo_time_difference(strtotime($tr['tr_timestamp'])).' ago</b> <span style="display: inline-block">by '.( $tr['tr_miner_en_id']>0 ? ( isset($tr['en_name']) ? '<a href="/entities/'.$tr['en_id'].'" style="font-weight: bold;"><span class="en_icon_child_'.$tr['en_id'].'">'.$tr['en_icon'].'</span> '.$tr['en_name'].'</a>' : 'Un-fetched' ) : 'System' ).'</span>.';

}


function fn___echo_in_featured($in)
{
    $ui = '<a href="/' . $in['in_id'] . '" class="list-group-item">';

    $ui .= '<span class="pull-right">';
    $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-right"></i></span>';
    $ui .= '</span>';

    $ui .= $in['in_outcome'];
    $ui .= '<span style="font-size:0.8em; font-weight:300; margin-left:5px; display:inline-block;">';
    $ui .= '<span><i class="fas fa-clock"></i>' . fn___echo_time_range($in) . '</span>';
    $ui .= '</span>';
    $ui .= '</a>';
    return $ui;
}

function fn___echo_time_milliseconds($microtime)
{
    $time = $microtime / 1000;
    echo date("Y-m-d H:i:s", floor($time)) . '.' . fn___one_two_explode('.', '', $time);
}


function fn___echo_in($in, $level, $in_parent_id = 0, $is_parent = false)
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
    $udata = $CI->session->userdata('user');
    $object_statuses = $CI->config->item('object_statuses');
    $tr_id = (isset($in['tr_id']) ? $in['tr_id'] : 0);
    $is_child_focused = ($level == 3 && $is_parent && $CI->uri->segment(2)==$in['in_id']);

    //Prepare Intent Metadata:
    $in_metadata = unserialize($in['in_metadata']);


    if ($level == 1) {

        $ui = '<div class="list-group-item">';

    } else {

        $ui = '<div in-tr-id="' . $tr_id . '" in-tr-type="' . $in['tr_type_en_id'] . '" intent-id="' . $in['in_id'] . '" parent-intent-id="' . $in_parent_id . '" intent-level="' . $level . '" class="list-group-item ' . ($level == 3 ? 'is_level3_sortable' : 'is_level2_sortable') . ' intent_line_' . $in['in_id'] . ' in__tr_'.$tr_id.'">';

    }


    /*
     *
     * Start Right Side
     *
     * */

    $ui .= '<span class="pull-right" style="' . ($level < 3 ? 'margin-right: 8px;' : '') . '">';



    //Fetch Input Requirements:
    $completion_requirements = $CI->Database_model->fn___tr_fetch(array(
        'tr_type_en_id' => 4331, //Intent Completion Requirements
        'tr_in_child_id' => $in['in_id'], //For this intent
        'tr_status >=' => 0, //New+
        'tr_en_parent_id IN (' . join(',', $CI->config->item('en_ids_4331')) . ')' => null, //Technically not needed, but here for extra clarity
    ));
    $ui .= '<span class="input_requirements_' . $in['in_id'] . '">';
    if(count($completion_requirements)>0){
        $en_all_4331 = $CI->config->item('en_all_4331');
        foreach($completion_requirements as $tr){
            $ui .= ' <span data-toggle="tooltip" data-placement="top" title="Accepts '.$en_all_4331[$tr['tr_en_parent_id']]["m_name"].' to be marked as complete">'.$en_all_4331[$tr['tr_en_parent_id']]["m_icon"].'</span>';
        }
    }
    $ui .= '</span>';



    //Intent USD Cost Icon Indicator:
    $ui .= ' <span class="ui_in_usd_' . $in['in_id'] . '">';
    if ($in['in_usd'] > 0) {
        $ui .= '<i class="fal fa-usd-circle" data-toggle="tooltip" title="$'.number_format($in['in_usd'],2).' USD in product purchase recommendations" data-placement="top"></i>';
    }
    $ui .= '</span>';


    //Webhook:
    $ui .= ' <span class="in_setwebhook_' . $in['in_id'] . '">';
    if (strlen($in['in_webhook']) > 0) {
        $ui .= '<i class="fal fa-cloud-upload" data-toggle="tooltip" title="'.$in['in_webhook'].'" data-placement="top"></i>';
    }
    $ui .= '</span>';


    if ($level == 1) {
        //Give option to update the cache:
        $ui .= ' <a href="/cron/fn___in_metadata_update/' . $in['in_id'] . '/1?redirect=/' . $in['in_id'] . '" onclick="fn___turn_off()" data-toggle="tooltip" title="Updates Intent tree cache which controls landing page counters for intent, hours, content types and industry expert" data-placement="top"><i class="fas fa-sync-alt"></i></a>';

        //Show Landing Page URL:
        $ui .= ' <a href="/' . $in['in_id'] . '" data-toggle="tooltip" title="Open Landing Page with Intent tree overview & Messenger Action Plan button" data-placement="top"><i class="fas fa-shopping-cart"></i></a>';

        $ui .= ' <span class="obj-id underdot" data-toggle="tooltip" data-placement="top" title="Intent #' . $in['in_id'] . '">#' . $in['in_id'] . '</span>';
    }






    //Intenet Points Icon indicator:
    $ui .= '<span class="badge badge-primary transparent ui_in_points_frame_' . $in['in_id'] . '" style="width:40px; margin-right:2px; margin-left:3px;" data-toggle="tooltip" data-placement="top" title="Completion points"><span class="btn-counter ui_in_points_' . $in['in_id'] . '">' . $in['in_points'] . '</span><i class="fal fa-weight"></i></span>';



    //Action Plan Stats:
    $count_in_actionplans = $CI->Database_model->fn___tr_fetch(array(
        'tr_type_en_id' => 4559, //Action Plan Intents
        'tr_in_child_id' => $in['in_id'], //For this Intent
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');

    if ($count_in_actionplans[0]['totals'] > 0) {

        //Yes, this intent has been added to some Action Plans, let's see what % is completed so far:
        $count_in_actionplans_complete = $CI->Database_model->fn___tr_fetch(array(
            'tr_type_en_id' => 4559, //Action Plan Intents
            'tr_in_child_id' => $in['in_id'], //For this Intent
            'tr_status NOT IN (' . join(',', $CI->config->item('tr_status_incomplete')) . ')' => null, //completed
        ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');

        //Show link to load these intents in Student Action Plans:
        $ui .= '<a href="#browseledger-' . $in['in_id'] . '-' . $tr_id . '-4559" onclick="fn___in_tr_load(' . $in['in_id'] . ', '. $tr_id .', 4559)" class="badge badge-primary" style="width:40px; margin-right:2px;" data-toggle="tooltip" title="' . $count_in_actionplans_complete[0]['totals'] . '/' . $count_in_actionplans[0]['totals'] . ' completed (or skipped) across all Action Plans" data-placement="top"><span class="btn-counter">' . round($count_in_actionplans_complete[0]['totals'] / $count_in_actionplans[0]['totals'] * 100) . '%</span><i class="fas fa-flag" style="font-size:0.85em;"></i></a>';

    }



    //Intent Messages:
    $count_in_messages = $CI->Database_model->fn___tr_fetch(array(
        'tr_status >=' => 0, //New+
        'tr_type_en_id IN (' . join(',', $CI->config->item('en_ids_4485')) . ')' => null, //All Intent messages
        'tr_in_child_id' => $in['in_id'],
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
    $ui .= '<a href="#loadmessages-' . $in['in_id'] . '" onclick="fn___in_messages_load(' . $in['in_id'] . ')" class="msg-badge-' . $in['in_id'] . ' badge badge-primary white-primary" style="width:40px; margin-right:2px;"><span class="btn-counter messages-counter-' . $in['in_id'] . '">' . $count_in_messages[0]['totals'] . '</span><i class="fas fa-comment-dots"></i></a>';




    //Intent Transactions:
    $count_in_trs = $CI->Database_model->fn___tr_fetch(array(
        '(tr_in_parent_id=' . $in['in_id'] . ' OR tr_in_child_id=' . $in['in_id'] . ($tr_id > 0 ? ' OR tr_tr_parent_id=' . $tr_id : '') . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
    if ($count_in_trs[0]['totals'] > 0) {
        //Show link to load these transactions:
        $ui .= '<a href="#browseledger-' . $in['in_id'] . '-' . $tr_id . '-0" onclick="fn___in_tr_load(' . $in['in_id'] . ', ' . $tr_id . ', 0)" class="badge badge-primary white-primary" style="width:40px; margin-right:0px;" data-toggle="tooltip" data-placement="top" title="' . number_format($count_in_trs[0]['totals'], 0) . ' Transactions"><span class="btn-counter">' . fn___echo_number($count_in_trs[0]['totals']) . '</span><i class="fas fa-atlas"></i></a>';
    }


    //Intent Modification + Completion Time Estimate:
    $ui .= '<a class="badge badge-primary white-primary" onclick="fn___in_modify_load(' . $in['in_id'] . ',' . $tr_id . ')" style="margin:-2px -8px 0 2px; width:40px;" href="#loadmodify-' . $in['in_id'] . '-' . $tr_id . '">' . '<span class="btn-counter slim-time t_estimate_' . $in['in_id'] . '" tree-max-seconds="' . (isset($in_metadata['in__tree_max_seconds']) ? $in_metadata['in__tree_max_seconds'] : 0) . '" intent-seconds="' . $in['in_seconds'] . '">' . (isset($in_metadata['in__tree_max_seconds']) ? fn___echo_time_hours($in_metadata['in__tree_max_seconds'], true) : 0) . '</span>' . '<i class="fas fa-cog"></i></a> &nbsp;';


    //Intent Link to Travel Down/UP the Tree:
    $ui .= '&nbsp;<a href="' . ($level == 1 || $is_child_focused ? 'javascript:alert(\'You are already here!\')' : '/intents/' . $in['in_id']) . '" class="tree-badge-' . $in['in_id'] . ' badge badge-primary ' . ( $level == 1 || $is_child_focused ? 'transparent' : '') . '" style="display:inline-block; margin-right:-1px; width:40px; border:2px solid ' . ( $level == 1 || $is_child_focused ? 'transparent' : '#fedd16') . ' !important;">' . (isset($in_metadata['in__tree_in_active_count']) ? '<span class="btn-counter children-counter-' . $in['in_id'] . ' ' . ($is_parent && $level == 2 ? 'inb-counter' : '') . '">' . $in_metadata['in__tree_in_active_count'] . '</span>' : '') . '<i class="fas fa-angle-right"></i></a> ';

    $ui .= '</span> '; //End of right column




    /*
     *
     * Start Left Side
     *
     * */

    $ui .= '<span style="display:inline-block; margin-top:0px;">';












    //Hidden fields to store dynamic value for on-demand JS modifications:
    //Show Transaction Status if Available:
    if ($level > 1) {
        //Fetch intent link types:
        $en_all_4486 = $CI->config->item('en_all_4486');

        //Show Transaction link icons:
        $ui .= '<span class="double-icon" style="width:42px;">';

        //Show larger icon for transaction type (auto detected based on transaction content):
        $ui .= '<span class="icon-main tr_type_' . $tr_id . '"><span class="tr_type_val" data-toggle="tooltip" data-placement="right" title="' . $en_all_4486[$in['tr_type_en_id']]['m_name'] . ': ' . $en_all_4486[$in['tr_type_en_id']]['m_desc'] . '">' . $en_all_4486[$in['tr_type_en_id']]['m_icon'] . '</span></span> ';

        //Show smaller transaction status icon:
        $ui .= '<span class="icon-sub tr_status_' . $tr_id . '"><span class="in_status_val" data-toggle="tooltip" data-placement="right" title="'.$object_statuses['tr_status'][$in['tr_status']]['s_name'].': '.$object_statuses['tr_status'][$in['tr_status']]['s_desc'].'">' . $object_statuses['tr_status'][$in['tr_status']]['s_icon'] . '</span></span> ';

        $ui .= '</span>';

    } else {

        //Show Blank box:
        $ui .= '<span class="double-icon" style="margin-right:7px;"><span class="icon-main"><i class="fas fa-map-pin" data-toggle="tooltip" data-placement="right" title="You are Here"></i></span><span class="icon-sub">&nbsp;</span></span>';

    }




    //Always Show Intent Icon (AND or OR)
    $ui .= '<span class="double-icon" style="margin-right:5px;">';

    //Show larger intent icon (AND or OR):
    $ui .= '<span class="icon-main in_is_any_' . $in['in_id'] . '"><span class="in_is_any_val" data-toggle="tooltip" data-placement="right" title="'.$object_statuses['in_is_any'][$in['in_is_any']]['s_name'].': '.$object_statuses['in_is_any'][$in['in_is_any']]['s_desc'].'">' . $object_statuses['in_is_any'][$in['in_is_any']]['s_icon'] . '</span></span> ';

    //Show smaller intent status:
    $ui .= '<span class="icon-sub in_status_' . $in['in_id'] . '"><span class="in_status_val" data-toggle="tooltip" data-placement="right" title="'.$object_statuses['in_status'][$in['in_status']]['s_name'].': '.$object_statuses['in_status'][$in['in_status']]['s_desc'].'">' . $object_statuses['in_status'][$in['in_status']]['s_icon'] . '</span></span> ';

    $ui .= '</span>';




    //We have numbering:
    if($level == 3 || ($level == 2 && !$is_parent)){
        //Always Show Entity Icons
        $ui .= '<span class="double-icon double-sort" style="width: 42px;">';

        //Show larger intent icon (AND or OR):
        $ui .= '<span class="icon-main"><span class="inline-level inline-level-' . $level . '" style="'.( $level == 2 ? 'font-weight:900;' : '' ).'">#' . $in['tr_order'] . '</span></span> ';

        //Show smaller intent status:
        $ui .= '<span class="icon-sub"><i class="fas fa-sort"></i></span> ';

        $ui .= '</span>';
    }



    //Intent UI based on level:
    if ($level == 1) {

        $ui .= '<span><b id="in_level1_outcome" style="font-size: 1.3em;">';
        $ui .= '<span class="in_outcome_' . $in['in_id'] . '">' . $in['in_outcome'] . '</span>';
        $ui .= '</b></span>';

    } elseif ($level == 2) {

        $ui .= '<a href="javascript:fn___ms_toggle(' . $tr_id . ', -1);"><span id="title_' . $tr_id . '" style="font-weight: 500;" class="cdr_crnt tree_title in_outcome_' . $in['in_id'] . (strlen($in['in_alternatives']) > 0 ? ' has-desc ' : '') . '" data-toggle="tooltip" data-placement="right" title="' . $in['in_alternatives'] . '">' . $in['in_outcome'] . '</span> <i id="handle-' . $tr_id . '" class="fal fa-plus-square" ' . ($is_parent ? 'data-toggle="tooltip" data-placement="right" title="View Siblings for this Intent"' : '') . '></i></a>';

    } elseif ($level == 3) {

        //Is this the focused item in the parent sibling dropdown?
        if($is_child_focused){
            $ui .= '<span class="badge badge-primary" style="font-size: 0.8em;"><i class="fas fa-map-pin"></i> You\'re Here</span> ';
        }

        $ui .= '<span id="title_' . $tr_id . '" class="tree_title in_outcome_' . $in['in_id'] . (strlen($in['in_alternatives']) > 0 ? ' has-desc ' : '') . '" data-toggle="tooltip" data-placement="right" title="' . $in['in_alternatives'] . '">' . $in['in_outcome'] . '</span> ';

    }

    //Is this a conditionl intent link?
    $ui .= ' <span class="range_is_'. $in['in_id'] .' underdot">';
    if ($level > 1 && $in['tr_type_en_id'] == 4229) {
        $tr_metadata = unserialize($in['tr_metadata']);
        if(isset($tr_metadata['tr__conditional_score_min'])){
            //Yes, fetch the min/max score requirements:
            $ui .= $tr_metadata['tr__conditional_score_min'].'-'.$tr_metadata['tr__conditional_score_max'].'%';
        }
    }
    $ui .= '</span>';



    $ui .= '</span>';


    if ($level == 1) {

        //Expand alternative outcomes for Level 1 intent:
        $ui .= '<div class="in_alternatives_' . $in['in_id'] . '" style="margin-top:2px;">' . nl2br($in['in_alternatives']) . '</div>';

    }

    /*
     *
     * Child Intents
     *
     * */
    if ($level == 2) {

        //Fetch children if parent, since there are no children fetched:
        if(!isset($in['in__grandchildren'])){
            $in['in__grandchildren'] = $CI->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 0, //New+
                'in_status >=' => 0, //New+
                'tr_type_en_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                'tr_in_parent_id' => $in['in_id'],
            ), array('in_child'), 0, 0, array('tr_order' => 'ASC')); //Child intents must be ordered
        }

        $ui .= '<div id="list-cr-' . $tr_id . '" class="cr-class-' . $tr_id . ' list-group step-group hidden list-level-3" intent-id="' . $in['in_id'] . '">';
        //This line enables the in-between list moves to happen for empty lists:
        $ui .= '<div class="is_level3_sortable dropin-box" style="height:1px;">&nbsp;</div>';


        if (isset($in['in__grandchildren']) && count($in['in__grandchildren']) > 0) {
            foreach ($in['in__grandchildren'] as $grandchild_in) {
                $ui .= fn___echo_in($grandchild_in, ($level + 1), $in['in_id'], $is_parent);
            }
        }


        //Intent Level 3 Input field:
        $ui .= '<div class="list-group-item list_input new-in3-input">
            <div class="input-group">
                <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="fn___in_link_or_create(' . $in['in_id'] . ',3);" intent-id="' . $in['in_id'] . '"><input type="text" class="form-control autosearch intentadder-level-3 intentadder-id-'.$in['in_id'].' algolia_search bottom-add" maxlength="' . $CI->config->item('in_outcome_max') . '" id="addintent-cr-' . $tr_id . '" intent-id="' . $in['in_id'] . '" placeholder="Add #Intent"></form></div>
                <span class="input-group-addon" style="padding-right:8px;">
                    <span data-toggle="tooltip" title="or press ENTER ;)" data-placement="top" onclick="fn___in_link_or_create(' . $in['in_id'] . ',3);" class="badge badge-primary pull-right" intent-id="' . $in['in_id'] . '" style="cursor:pointer; margin: 13px -6px 1px 13px;">
                        <div><i class="fas fa-plus"></i></div>
                    </span>
                </span>
            </div>
        </div>';


        $ui .= '</div>';
    }


    $ui .= '</div>';
    return $ui;

}

function fn___echo_leaderboard($days_ago = null, $top = 25){

    $filters = array(
        'tr_miner_en_id >' => 0,
        'tr_coins !=' => 0,
    );

    if(!is_null($days_ago)){
        $start_date = date("Y-m-d" , (time() - ($days_ago * 24 * 3600)));
        //From beginning of the day:
        $filters['tr_timestamp >='] = $start_date.' 00:00:00';
        $table_name = $days_ago.'-Day';
    } else {
        $table_name = 'All Time';
    }

    $CI =& get_instance();


    //Count coins per Miner
    //Object Stats grouped by Status:
    $all_engs = $CI->Database_model->fn___tr_fetch($filters, array('en_miner'), $top, 0, array('coins_sum' => 'DESC'), 'COUNT(tr_miner_en_id) as trs_count, SUM(tr_coins) as coins_sum, en_name, en_icon, tr_miner_en_id', 'tr_miner_en_id, en_name, en_icon');

    $all_transaction_count = 0;
    $all_coin_payouts = 0;
    $table_body = '';
    $top_miner = '';

    foreach ($all_engs as $count=>$tr) {

        if($count == 0){
            $top_miner = fn___one_two_explode('', ' ', $tr['en_name']);
        }

        //Echo stats:
        $table_body .= '<tr>';
        $table_body .= '<td style="text-align: left;"><span style="width:29px; display: inline-block; text-align: center; '.( $count > 2 ? 'font-size:0.8em;' : '' ).'">'.fn___echo_rank($count+1).'</span><span style="width: 29px; display: inline-block; text-align: center;">'.( strlen($tr['en_icon']) > 0 ? $tr['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).'</span><a href="/entities/'.$tr['tr_miner_en_id'].'">'.$tr['en_name'].'</a></td>';
        $table_body .= '<td style="text-align: right;"><a href="/ledger?tr_miner_en_id='.$tr['tr_miner_en_id'].( is_null($days_ago) ? '' : '&start_range='.$start_date ).'"  data-toggle="tooltip" title="Awarded for '.number_format($tr['trs_count'],0).' transactions averaging '.round(($tr['coins_sum']/$tr['trs_count']),0).' coins/transaction" data-placement="top">'.number_format($tr['coins_sum'], 0).'</a></td>';
        $table_body .= '</tr>';

        $all_transaction_count += $tr['trs_count'];
        $all_coin_payouts += $tr['coins_sum'];

    }


    $ui = '';

    $ui .= '<a href="javascript:void(0);" onclick="$(\'.leaderboard'.$days_ago.'\').toggleClass(\'hidden\');" class="large-stat"><span>🏅'. $top_miner . '</span>1,233 all-time miners <i class="fal fa-plus-circle"></i></a>';

    $ui .= '<table class="table table-condensed table-striped stats-table leaderboard'.$days_ago.' hidden" style="max-width:100%;">';


//Object Header:
    $ui .= '<tr style="font-weight: bold;">';
    $ui .= '<td style="text-align: left;">'.$table_name.' Miners</td>';
    $ui .= '<td style="text-align: right;"><i class="fal fa-coins"></i> Coins</td>';
    $ui .= '</tr>';

    $ui .= $table_body;

    $ui .= '<tr style="font-weight: bold;">';
    $ui .= '<td style="text-align: right;">Top '.($count+1).' Totals:&nbsp;</td>';
    $ui .= '<td style="text-align: right;"><span data-toggle="tooltip" title="Awarded for '.number_format($tr['trs_count'],0).' Transactions averaging '.round(($all_coin_payouts/$all_transaction_count),1).' coins per transaction" data-placement="top">'.number_format($all_coin_payouts,0).'</span></td>';
    $ui .= '</tr>';


    //End Section:
    $ui .= '</table>';

    return $ui;

}


function fn___echo_rank($integer){
    if($integer==1){
        return '🏅';
    } elseif($integer==2){
        return '🥈';
    } elseif($integer==3){
        return '🥉';
    } else {
        return fn___echo_number_ordinal($integer);
    }
}


function fn___echo_en($en, $level, $is_parent = false)
{

    $CI =& get_instance();
    $udata = $CI->session->userdata('user');
    $object_statuses = $CI->config->item('object_statuses');
    $tr_id = (isset($en['tr_id']) ? $en['tr_id'] : 0);
    $ui = null;


    $ui .= '<div entity-id="' . $en['en_id'] . '" entity-status="' . $en['en_status'] . '" tr-id="'.$tr_id.'" tr-status="'.( $tr_id > 0 ? $en['tr_status'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '" class="list-group-item en-item en___' . $en['en_id'] . ' ' . ($level == 1 ? 'top_entity' : 'tr_' . $en['tr_id']) . ( $is_parent && $level == 2 ? ' redirect_go_'.$en['tr_en_child_id'] : '') . '">';


    //Right content:
    $ui .= '<span class="pull-right">';


    //Do we have entity parents loaded in our data-set?
    if (!isset($en['en__parents'])) {
        //Fetch parents at this point:
        $en['en__parents'] = $CI->Database_model->fn___tr_fetch(array(
            'tr_type_en_id IN (' . join(',', array_merge($CI->config->item('en_ids_4537'), $CI->config->item('en_ids_4538'))) . ')' => null, //Entity Link Connectors
            'tr_en_child_id' => $en['en_id'], //This child entity
            'tr_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_parent'), 0, 0, array('en_trust_score' => 'DESC'));
    }

    //Loop through parents and only show those that have en_icon set:
    foreach ($en['en__parents'] as $en_parent) {
        if (strlen($en_parent['en_icon']) > 0) {
            $ui .= ' &nbsp;<a href="/entities/' . $en_parent['en_id'] . '" data-toggle="tooltip" title="' . $en_parent['en_name'] . (strlen($en_parent['tr_content']) > 0 ? ' = ' . $en_parent['tr_content'] : '') . '" data-placement="top" class="en_icon_child_' . $en_parent['en_id'] . '">' . $en_parent['en_icon'] . '</a>';
        }
    }


    //Does entity have a Messenger PSID?
    if ($en['en_psid'] > 0) {
        $ui .= ' &nbsp;<img src="/img/bp_128.png" style="width:20px; margin:-4px 0 0 -2px;" data-toggle="tooltip" data-placement="top" title="Connected to Mench on Messenger">';
    }

    if ($level == 1) {

        //Google search:
        $ui .= ' &nbsp;<a href="https://www.google.com/search?q=' . urlencode($en['en_name']) . '" target="_blank" data-toggle="tooltip" title="Search on Google" data-placement="top"><i class="fab fa-google"></i></a>';

        //Also show Entity ID:
        $ui .= ' &nbsp;<span class="obj-id underdot" data-toggle="tooltip" data-placement="top" title="Entity ID">@' . $en['en_id'] . '</span>';

    }





    //Count & Display active Intent messages that this entity has been referenced within:
    $messages = $CI->Database_model->fn___tr_fetch(array(
        'tr_status >=' => 0, //New+
        'tr_type_en_id IN (' . join(',', $CI->config->item('en_ids_4485')) . ')' => null, //All Intent messages
        'tr_en_parent_id' => $en['en_id'], //Entity Referenced in message content
    ), array(), 0, 0, array(), 'COUNT(tr_id) AS total_messages');

    $ui .= '<' . ($messages[0]['total_messages'] > 0 ? 'a href="#loadmessages-' . $en['en_id'] . '" onclick="fn___load_en_messages(' . $en['en_id'] . ')" class="badge badge-secondary white-secondary"' : 'a href="#" onclick="alert(\'No intent messages found that reference this entity\')" class="badge badge-secondary transparent"') . ' style="width:40px; margin-left:5px;"><span class="btn-counter">' . $messages[0]['total_messages'] . '</span><i class="fas fa-comment-dots"></i></a>';






    //Count & Display all Entity transaction:
    $count_in_trs = $CI->Database_model->fn___tr_fetch(array(
        '(tr_en_parent_id=' . $en['en_id'] . ' OR  tr_en_child_id=' . $en['en_id'] . ' OR  tr_miner_en_id=' . $en['en_id'] . ($tr_id > 0 ? ' OR tr_tr_parent_id=' . $tr_id : '') . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
    if ($count_in_trs[0]['totals'] > 0) {
        //Show the transaction button:
        $ui .= '<a href="#browseledger-' . $en['en_id'] . '" onclick="fn___load_en_ledger(' . $en['en_id'] . ')" class="badge badge-secondary white-secondary" style="width:40px; margin-left:2px;" data-toggle="tooltip" data-placement="top" title="' . number_format($count_in_trs[0]['totals'], 0) . ' Transactions"><span class="btn-counter">' . fn___echo_number($count_in_trs[0]['totals']) . '</span><i class="fas fa-atlas"></i></a>';
    }




    //Show modification button along with Trust Score
    $ui .= '<a href="#loadmodify-' . $en['en_id'] . '-' . $tr_id . '" onclick="fn___en_modify_load(' . $en['en_id'] . ',' . $tr_id . ')" class="badge badge-secondary white-secondary" style="margin:-2px -6px 0 2px; width:40px;"><span class="btn-counter" data-toggle="tooltip" data-placement="left" title="Entity Trust Score is ' . number_format($en['en_trust_score'], 0) . '">' . fn___echo_number($en['en_trust_score']) . '</span><i class="fas fa-cog" style="font-size:0.9em; width:28px; padding-right:3px; text-align:center;"></i></a> &nbsp;';


    //Have we counted the Entity Children?
    if (!isset($en['en__child_count'])) {
        //Assume none:
        $en['en__child_count'] = 0;

        //Do a child count:
        $child_trs = $CI->Database_model->fn___tr_fetch(array(
            'tr_en_parent_id' => $en['en_id'],
            'tr_type_en_id IN (' . join(',', array_merge($CI->config->item('en_ids_4537'), $CI->config->item('en_ids_4538'))) . ')' => null, //Entity Link Connectors
            'tr_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

        if (count($child_trs) > 0) {
            $en['en__child_count'] = intval($child_trs[0]['en__child_count']);
        }
    }
    $ui .= '<a class="badge badge-secondary '. ( $level == 1 ? 'transparent' : '') .'" href="'.($level == 1 ? 'javascript:alert(\'You are already here!\')' : '/entities/' . $en['en_id']). '" style="display:inline-block; margin-right:6px; width:40px; margin-left:1px; border:2px solid '. ( $level == 1 ? 'transparent' : '#0084ff') .' !important;">' . ($en['en__child_count'] > 0 ? '<span class="btn-counter ' . ($level == 1 ? 'li-children-count' : '') . '" title="' . number_format($en['en__child_count'], 0) . ' Entities">' . fn___echo_number($en['en__child_count']) . '</span>' : '') . '<i class="fas fa-angle-right"></i></a>';

    $ui .= '</span>';






    $ui .= '<span style="display:inline-block; margin-top:0px;">';






    //Hidden fields to store dynamic value for on-demand JS modifications:
    //Show Transaction Status if Available:
    if ($tr_id > 0) {
        //Show Link Type:
        $entity_links = $CI->config->item('en_all_4537') + $CI->config->item('en_all_4538'); //Will Contain every possible Entity Link Connector!

        //Show Transaction link icons:
        $ui .= '<span class="double-icon" style="margin-right:7px;">';

        //Show larger icon for transaction type (auto detected based on transaction content):
        $ui .= '<span class="icon-main tr_type_' . $tr_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$entity_links[$en['tr_type_en_id']]['m_name'].': '.$entity_links[$en['tr_type_en_id']]['m_desc'].'">' . $entity_links[$en['tr_type_en_id']]['m_icon'] . '</span></span> ';

        //Show smaller transaction status icon:
        $ui .= '<span class="icon-sub tr_status_' . $tr_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$object_statuses['tr_status'][$en['tr_status']]['s_name'].': '.$object_statuses['tr_status'][$en['tr_status']]['s_desc'].']">' . $object_statuses['tr_status'][$en['tr_status']]['s_icon'] . '</span></span>';

        $ui .= '</span>';
    } else {

        //Show Blank box:
        $ui .= '<span class="double-icon" style="margin-right:7px;"><span class="icon-main"><i class="fas fa-map-pin" data-toggle="tooltip" data-placement="right" title="You are Here"></i></span><span class="icon-sub">&nbsp;</span></span>';

    }





    //Always Show Entity Icons
    $ui .= '<span class="double-icon" style="margin-right:7px;">';

    //Show larger custom entity icon:
    $ui .= '<span class="icon-main en_icon_ui en_icon_ui_' . $en['en_id'] . ' en-icon en_icon_'.$en['en_id'].'" en-is-set="'.( strlen($en['en_icon']) > 0 ? 1 : 0 ).'" data-toggle="tooltip" data-placement="right" title="Entity Icon">' . fn___echo_en_icon($en) . '</span>';

    //Show smaller entity status:
    $ui .= '<span class="icon-sub en_status_' . $en['en_id'] . '"><span data-toggle="tooltip" data-placement="right" title="'.$object_statuses['en_status'][$en['en_status']]['s_name'].': '.$object_statuses['en_status'][$en['en_status']]['s_desc'].'">' . $object_statuses['en_status'][$en['en_status']]['s_icon'] . '</span></span>';

    $ui .= '</span>';






    if ($tr_id > 0) {
        $ui .= '<span class="tr_content_val_' . $tr_id . ' hidden">' . $en['tr_content'] . '</span>';
    }


    //Entity Icon/Name:
    $ui .= '<span class="en_name en_name_' . $en['en_id'] . '">' . $en['en_name'] . '</span>';

    $ui .= '</span>';


    //Does this entity also include a transaction?
    if ($tr_id > 0) {

        $tr_content = fn___echo_tr_content($en['tr_content'] , $en['tr_type_en_id']);

        //Is this Entity transaction an Embeddable URL type or not?
        $ui .= ' <span class="tr_content tr_content_' . $tr_id . '" style="'.( strlen($tr_content) > 0 ? 'min-width:240px;' : '').' line-height: 140%; display:inline-block;">';
        $ui .= $tr_content;
        $ui .= '</span>';

    }



    $ui .= '</div>';

    return $ui;

}


function fn___echo_json($array)
{
    header('Content-Type: application/json');
    echo json_encode($array);
}


function fn___echo_number_ordinal($number)
{
    $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
    if (($number % 100) >= 11 && ($number % 100) <= 13) {
        return $number . 'th';
    } else {
        return $number . $ends[$number % 10];
    }
}

function fn___echo__s($count, $is_es = 0)
{
    //A cute little function to either display the plural "s" or not based on $count
    return ($count == 1 ? '' : ($is_es ? 'es' : 's'));
}

