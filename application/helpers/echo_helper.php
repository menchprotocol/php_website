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


function fn___echo_advance(){
    $CI =& get_instance();
    return ' advance-ui '.( $CI->session->userdata('advance_view_enabled')==1 ? '' : 'hidden ' );
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

        return '<a href="' . $url . '" target="_blank"><span class="url_truncate">' . fn___echo_url_clean($url) . '<i class="fas fa-external-link" style="font-size: 0.7em; padding-left:3px;"></i></span></a>';

    } elseif ($en_type_id == 4257 /* Embed Widget URL? */) {

        return  fn___echo_url_embed($url, $url);

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
     *       values for tr_type_entity_id as this could change the equation for those
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

    } elseif (substr_count($url, 'vimeo.com/') == 1 && is_numeric(fn___one_two_explode('vimeo.com/','?',$url))) {

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
    $session_en = $CI->session->userdata('user');

    //Fetch all possible Intent Notes types to enable the Miner to change message type:
    $en_all_4485 = $CI->config->item('en_all_4485');


    //Build the HTML UI:
    $ui = '';
    $ui .= '<div class="list-group-item is-msg is_level2_sortable all_msg msg_en_type_' . $tr['tr_type_entity_id'] . '" id="ul-nav-' . $tr['tr_id'] . '" tr-id="' . $tr['tr_id'] . '">';
    $ui .= '<div style="overflow:visible !important;">';

    //Type & Delivery Method:
    $ui .= '<div class="edit-off text_message" id="msgbody_' . $tr['tr_id'] . '" style="margin:2px 0 0 0;">';

    //Now get the message snippet:
    $ui .= $CI->Chat_model->fn___dispatch_message($tr['tr_content'], $session_en, false, array(), array(), $tr['tr_child_intent_id']);

    $ui .= '</div>';


    //Text editing:
    $ui .= '<textarea onkeyup="fn___changeMessageEditing(' . $tr['tr_id'] . ')" name="tr_content" id="message_body_' . $tr['tr_id'] . '" class="edit-on hidden msg msgin algolia_search" placeholder="Write Message..." style="margin-top: 4px;">' . $tr['tr_content'] . '</textarea>';

    //Editing menu:
    $ui .= '<ul class="msg-nav">';



    //Counter:
    $ui .= '<li class="edit-on hidden"><span id="charNumEditing' . $tr['tr_id'] . '">0</span>/' . $CI->config->item('tr_content_max_length') . '</li>';





    //Transactions:
    $count_msg_trs = $CI->Database_model->fn___tr_fetch(array(
        '( tr_id = ' . $tr['tr_id'] . ' OR tr_parent_transaction_id = ' . $tr['tr_id'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
    $ui .= '<li class="pull-right edit-off ' . fn___echo_advance() . '"><a class="btn btn-primary" style="border:2px solid #fedd16 !important;" href="/ledger?tr_id=' . $tr['tr_id'] . '" target="_parent" title="Go to Transactions" data-toggle="tooltip" data-placement="top"><i class="fas fa-atlas"></i> '.fn___echo_number($count_msg_trs[0]['totals']).'</a></li>';

    //Delete:
    $ui .= '<li class="pull-right edit-off" style="margin-right:5px; margin-left: 6px;"><span class="on-hover"><a class="btn btn-primary white-primary" href="javascript:fn___message_remove(' . $tr['tr_id'] . ');" title="Remove Message" data-toggle="tooltip" data-placement="top" style="border:2px solid #fedd16 !important;"><i class="fas fa-trash-alt"></i></a></span></li>';

    //Modify:
    $ui .= '<li class="pull-right edit-off" style="margin-left:0;"><span class="on-hover"><a class="btn btn-primary white-primary" href="javascript:fn___message_modify_start(' . $tr['tr_id'] . ',' . $tr['tr_type_entity_id'] . ');" title="Modify Message" data-toggle="tooltip" data-placement="top" style="border:2px solid #fedd16 !important; margin-left: 8px !important; margin-right: -6px !important;"><i class="fas fa-pen-square"></i></a></span></li>';

    //Is this a video message?

    //Type:
    $ui .= '<li class="pull-right edit-off message_status" style="margin: 0 1px 0 -1px;"><span title="' . rtrim($en_all_4485[$tr['tr_type_entity_id']]['m_name'], 's') . '" data-toggle="tooltip" data-placement="top">' . $en_all_4485[$tr['tr_type_entity_id']]['m_icon'] . '</span></li>';

    //Sort:
    $ui .= '<li class="pull-right edit-off"><span title="Drag up/down to sort" data-toggle="tooltip" data-placement="top"><i class="fas fa-sort '.( in_array(4603, $en_all_4485[$tr['tr_type_entity_id']]['m_parents']) ? 'message-sorting' : '' ).'"></i></span></li>';



    //Save Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary white-primary" title="Save changes" data-toggle="tooltip" data-placement="top" href="javascript:fn___in_message_modify(' . $tr['tr_id'] . ',' . $tr['tr_type_entity_id'] . ');"><i class="fas fa-check"></i> Save</a></li>';

    //Cancel Edit:
    $ui .= '<li class="pull-right edit-on hidden"><a class="btn btn-primary white-primary" title="Cancel editing" data-toggle="tooltip" data-placement="top" href="javascript:fn___message_modify_cancel(' . $tr['tr_id'] . ');"><i class="fas fa-times"></i></a></li>';



    //Show drop down for message type adjustment:
    if(in_array( 4742 /* Intent Note Switchable  */, $en_all_4485[$tr['tr_type_entity_id']]['m_parents'])){

        $ui .= '<li class="pull-right edit-on hidden">';
        $ui .= '<select id="en_all_4485_' . $tr['tr_id'] . '" title="Change message type" data-toggle="tooltip" data-placement="top">';
        foreach ($en_all_4485 as $tr_type_entity_id => $m) {
            if(in_array( 4742 /* Intent Note Switchable  */, $m['m_parents'])){
                $ui .= '<option value="' . $tr_type_entity_id . '" '.( $tr_type_entity_id==$tr['tr_type_entity_id'] ? 'selected="selected"' : '' ).'>' . $m['m_name'] . '</option>';
            }
        }
        $ui .= '</select>';
        $ui .= '</li>';

    } else {

        //Do not allow to change:
        $ui .= '<input type="hidden" id="en_all_4485_' . $tr['tr_id'] . '" value="'.$tr['tr_type_entity_id'].'" />';

    }

    $ui .= '<li class="pull-right edit-updates"></li>'; //Show potential errors

    $ui .= '</ul>';

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function fn___echo_icon($en)
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

function fn___echo_tr_urls($tr_content, $tr_type_entity_id){

    $tr_content = htmlentities($tr_content);

    $CI =& get_instance();
    if (in_array($tr_type_entity_id, $CI->config->item('en_ids_4537'))) {

        //Entity URL Links
        return fn___echo_url_type($tr_content, $tr_type_entity_id);

    } elseif(strlen($tr_content) > 0) {

        return fn___echo_link($tr_content);

    } else {

        return null;

    }
}

function fn___echo_tr_row($tr, $is_inner = false)
{

    $CI =& get_instance();
    $en_all_4594 = $CI->config->item('en_all_4594');

    if(!isset($en_all_4594[$tr['tr_type_entity_id']])){
        //We've probably have not yet updated php cache, set error:
        $en_all_4594[$tr['tr_type_entity_id']] = array(
            'm_icon' => '<i class="fal fa-exclamation-triangle redalert"></i>',
            'm_name' => 'Transaction Type Not Synced in PHP Cache',
            'm_desc' => '',
            'm_parents' => array(),
        );
    }

    $hide_sensitive_details = (in_array($tr['tr_type_entity_id'] , $CI->config->item('en_ids_4755')) /* Transaction Type is locked */ && !fn___en_auth(array(1281)) /* Viewer NOT a moderator */);

    //Fetch Miner Entity:
    $miner_ens = $CI->Database_model->fn___en_fetch(array(
        'en_id' => $tr['tr_miner_entity_id'],
    ));

    //Display the item
    $ui = '<div class="list-group-item tr-box">';


    //What type of main content do we have, if any?
    $fixed_fields = $CI->config->item('fixed_fields');


    $ui .= '<div style="padding: 0 10px;">';

        if($hide_sensitive_details){

            //Hide Miner:
            $ui .= '<span class="icon-main"><i class="fal fa-eye-slash"></i></span>';
            $ui .= '<b data-toggle="tooltip" data-placement="top" title="Sign in as a Mench moderator to unlock private information about this transaction">&nbsp;Private Entity</b>';

        } else {

            //Show Miner:
            $ui .= '<span class="icon-main">'.( strlen($miner_ens[0]['en_icon']) > 0 ? $miner_ens[0]['en_icon'] : '<i class="fas fa-at grey-at"></i>' ).' </span>';
            $ui .= '<a href="/entities/'.$miner_ens[0]['en_id'].'" data-toggle="tooltip" data-placement="top" title="View miner profile"><b>' . $miner_ens[0]['en_name'] . '</b></a>';
        }

        //Transaction Type:
        $ui .= '<a href="/entities/'.$tr['tr_type_entity_id'].'" data-toggle="tooltip" data-placement="top" title="View transaction type entity"><b style="padding-left:5px;">'. ( strlen($en_all_4594[$tr['tr_type_entity_id']]['m_icon']) > 0 ? $en_all_4594[$tr['tr_type_entity_id']]['m_icon'] . ' ' : '' ) . $en_all_4594[$tr['tr_type_entity_id']]['m_name'] . '</b></a>';

    $ui .= '</div>';


    //2nd Row of data:
    $ui .= '<div style="padding:7px 0 9px 13px; font-size:0.8em;">';
    $ui .= '<span data-toggle="tooltip" data-placement="top" title="Ledger Transaction ID" style="min-width:80px; display: inline-block;"><i class="fas fa-atlas"></i> '.$tr['tr_id'].'</span>';
    $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Mined Coins" style="min-width:47px; display: inline-block;"><i class="fal fa-coins"></i> <b>'. $tr['tr_coins'] .'</b></span>';
    $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="'.$fixed_fields['tr_status'][$tr['tr_status']]['s_desc'].'" style="min-width:82px; display: inline-block;">'.$fixed_fields['tr_status'][$tr['tr_status']]['s_icon'].' '.$fixed_fields['tr_status'][$tr['tr_status']]['s_name'].'</span>';
    $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Ledger Transaction Log Time: ' . $tr['tr_timestamp'] . ' PST" style="min-width:120px; display: inline-block;"><i class="fal fa-clock"></i> ' . fn___echo_time_difference(strtotime($tr['tr_timestamp'])) . ' ago</span>';


    if($tr['tr_order'] != 0){
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Transaction ordered '.fn___echo_ordinal_number($tr['tr_order']).' relative to its siblings" style="min-width:30px; display: inline-block;" class="' . fn___echo_advance() . '"><i class="fas fa-sort"></i>'.$tr['tr_order'].'</span>';
    } else {
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Transaction is not ordered" style="min-width:30px; display: inline-block;" class="' . fn___echo_advance() . '"><i class="fas fa-sort" style="color: #AAA;"></i></span>';
    }


    if(!$hide_sensitive_details && strlen($tr['tr_content']) < 1){
        $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="Transaction has no content" class="' . fn___echo_advance() . '"><i class="fal fa-comment-slash" style="color: #AAA;"></i></span>';
    }

    //Is this a miner? Show them metadata status:
    if(!$hide_sensitive_details && fn___en_auth(array(1308))){
        if(strlen($tr['tr_metadata']) > 0){
            $ui .= ' &nbsp;<a href="/ledger/fn___tr_json/' . $tr['tr_id'] . '" target="_blank" data-toggle="tooltip" data-placement="top" title="Open transaction metadata json object (in new window)" style="min-width:26px; display: inline-block;" class="' . fn___echo_advance() . '"><i class="fas fa-search-plus"></i></a>';
        } else {
            $ui .= ' &nbsp;<span data-toggle="tooltip" data-placement="top" title="No Metadata" style="min-width:26px; display: inline-block;" class="' . fn___echo_advance() . '"><i class="fal fa-search-minus" style="color: #AAA;"></i></span>';
        }
    }

    $ui .= '</div>';

    //Do we have a content to show?
    if(!$hide_sensitive_details){
        $main_content = fn___echo_tr_urls($tr['tr_content'], $tr['tr_type_entity_id']);
        $ui .= '<div class="e-msg ' . ($main_content ? '' : 'hidden') . '">';
        $ui .= $main_content;
        $ui .= '</div>';
    }

    $ui .= '</div>';

    //Lets go through all references to see what is there:
    if(!$is_inner){

        //Show Transaction Links:
        foreach ($CI->config->item('tr_object_links') as $tr_field => $obj_type) {

            if(!(!in_array($tr_field, array('tr_miner_entity_id','tr_type_entity_id')) && intval($tr[$tr_field]) > 0)){
                //Don't show miner and type as they are already printed on the first line:
                continue;
            }

            $ui .= '<div class="tr-child">';
            if($obj_type=='en'){
                //Fetch
                $ens = $CI->Database_model->fn___en_fetch(array('en_id' => $tr[$tr_field]));
                if(count($ens) > 0){
                    $ui .= fn___echo_en($ens[0], 0);
                }
            } elseif($obj_type=='in'){
                //Fetch
                $ins = $CI->Database_model->fn___in_fetch(array('in_id' => $tr[$tr_field]));
                if(count($ins) > 0){
                    $ui .= fn___echo_in($ins[0], 0);
                }
            } elseif($obj_type=='tr'){
                //Fetch
                $trs = $CI->Database_model->fn___tr_fetch(array('tr_id' => $tr[$tr_field]));
                if(count($trs) > 0){
                    $ui .= fn___echo_tr_row($trs[0], true);
                }
            }
            $ui .= '</div>';
        }

        //Now show all transactions for this transaction:
        foreach ($CI->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'tr_parent_transaction_id' => $tr['tr_id'],
        ), array(), 0, 0, array('tr_id' => 'DESC')) as $tr_child) {
            $ui .= '<div class="tr-child">' . fn___echo_tr_row($tr_child, true) . '</div>';
        }

    }


    return $ui;
}

function echo_k($k, $is_parent, $in_type_tr_parent_intent_id = 0)
{

    $ui = '<a href="' . ($in_type_tr_parent_intent_id ? '/my/choose_any_path/' . $k['tr_id'] . '/' . $in_type_tr_parent_intent_id . '/' . $k['in_id'] . '/' . md5($k['tr_id'] . 'kjaghksjha*(^' . $k['in_id'] . $in_type_tr_parent_intent_id) : '/my/actionplan/' . $k['tr_parent_transaction_id'] . '/' . $k['in_id']) . '" class="list-group-item">';

    //Different pointer position based on direction:
    if ($is_parent) {
        $ui .= '<span class="pull-left">';
        $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-left"></i></span>';
        $ui .= '</span>';
    } else {
        $ui .= '<span class="pull-right">';
        $ui .= '<span class="badge badge-primary fr-bgd">' . ($in_type_tr_parent_intent_id ? 'Select <i class="fas fa-check-circle"></i>' : '<i class="fas fa-angle-right"></i>') . '</span>';
        $ui .= '</span>';

        //For children show icon:
        if ($in_type_tr_parent_intent_id) {
            //Radio button to indicate a single selection:
            $ui .= '<span class="status-label" style="padding-bottom:1px;"><i class="fal fa-circle"></i> </span>';
        } else {
            //Proper status:
            $ui .= fn___echo_fixed_fields('tr_status', $k['tr_status'], 1, 'right');
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


function fn___echo_tree_sources($in, $fb_messenger_format = false, $expand_mode = false)
{

    /*
     *
     * An intent function to display experts sources for
     * the entire intent tree stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if ((!isset($metadata['in__tree_experts']) || count($metadata['in__tree_experts']) < 1) && (!isset($metadata['in__tree_contents']) || count($metadata['in__tree_contents']) < 1)) {
        return false;
    }


    //Let's count to see how many content pieces we have references for this intent tree:
    $source_info = '';
    $source_count = 0;

    if(isset($metadata['in__tree_contents'])){
        foreach ($metadata['in__tree_contents'] as $type_en_id => $referenced_ens) {
            $source_count += count($referenced_ens);
        }
    }
    if ($source_count > 0) {

        //Set some variables and settings to get started:
        $type_all_count = count($metadata['in__tree_contents']);
        $CI =& get_instance();
        $en_all_3000 = $CI->config->item('en_all_3000');
        $visible_ppl = 3; //How many people to show before clicking on "see more"
        $type_count = 0;
        foreach ($metadata['in__tree_contents'] as $type_id => $referenced_ens) {

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

                    //Show link to matrix:
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
    $expert_count = ( isset($metadata['in__tree_experts']) ? count($metadata['in__tree_experts']) : 0 );
    $visible_html = 4; //Landing page, beyond this is hidden and visible with a click
    $visible_bot = 10; //Plain text style, but beyond this is cut out!
    $expert_info = '';

    if(isset($metadata['in__tree_experts'])){
        foreach ($metadata['in__tree_experts'] as $count => $en) {

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
        $pitch_title .= $source_count . ' Source'. fn___echo__s($source_count);
        $pitch .= $source_info;
    }
    if($expert_count > 0){
        if($source_count > 0){
            $pitch_title .= ' by ';
            $pitch .= ' by ';
        }
        $pitch_title .= $expert_count . ' Expert'. fn___echo__s($expert_count);
        $pitch .= $expert_count . ' industry expert'. fn___echo__s($expert_count) . ($expert_count == 1 ? ':' : ' including') . $expert_info;
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
                        <i class="fas" style="transform:none !important;">⭐</i> ' . $pitch_title . '<i class="fal fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body overview-pitch">
                    ' . $pitch . '.
                </div>
            </div>
        </div></div>';
    }
}


function fn___echo_tree_steps($in, $fb_messenger_format = 0, $expand_mode = false)
{

    /*
     *
     * An intent function to display the total tree intents
     * stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    if (!isset($metadata['in__flat_unique_published_count']) || $metadata['in__flat_unique_published_count'] < 2) {
        return false;
    }

    $metadata['in__flat_unique_published_count']--;

    $pitch = 'Action Plan contains ' . $metadata['in__flat_unique_published_count'] . ' steps to ' . $in['in_outcome'];

    if ($fb_messenger_format) {

        return '🚩 ' . $pitch . "\n";

    } else {

        //HTML format
        $id = 'IntentOverview';
        $return_html = '';

        //Section header:
        $return_html .= '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                    <i class="fas" style="transform:none !important;">✅</i> ' . $metadata['in__flat_unique_published_count'] . ' Steps<i class="fal fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                </a>
            </h4>
        </div>
        <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
            <div class="panel-body overview-pitch">';
        $return_html .= $pitch.'.';

        //Action Plan:
        $return_html .= fn___echo_action_plan($in, false);

        //Close the section:
        $return_html .= '</div></div></div></div>';


        return $return_html;

    }

}

function fn___echo_action_plan($in, $expand_mode){


    $CI =& get_instance();
    $children_ins = $CI->Database_model->fn___tr_fetch(array(
        'tr_status' => 2, //Published
        'in_status' => 2, //Published
        'tr_type_entity_id' => 4228, //Fixed intent links only
        'tr_parent_intent_id' => $in['in_id'],
    ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

    if(count($children_ins) < 1){
        return null;
    }


    $return_html = '';
    $return_html .= '<div class="list-group grey_list actionplan_list maxout" style="margin:5px 0 0 5px;">';

    foreach ($children_ins as $in_level2_counter => $in_level2) {

        //Level 3 intents:
        $grandchildren_ins = $CI->Database_model->fn___tr_fetch(array(
            'tr_status' => 2, //Published
            'in_status' => 2, //Published
            'tr_type_entity_id' => 4228, //Fixed intent links only
            'tr_parent_intent_id' => $in_level2['in_id'],
        ), array('in_child'), 0, 0, array('tr_order' => 'ASC'));

        //Fetch messages:
        $in_level2_messages = $CI->Database_model->fn___tr_fetch(array(
            'tr_status' => 2, //Published
            'tr_type_entity_id' => 4231, //Intent Note Messages
            'tr_child_intent_id' => $in_level2['in_id'],
        ), array(), 0, 0, array('tr_order' => 'ASC'));

        //Skip if intent has no message and no level 3 children:
        if(count($grandchildren_ins) == 0 && count($in_level2_messages) == 0){
            continue;
        }

        //Level 2 title:
        $return_html .= '<div class="panel-group" id="open' . $in_level2_counter . '" role="tablist" aria-multiselectable="true">';
        $return_html .= '<div class="panel panel-primary">';
        $return_html .= '<div class="panel-heading" role="tab" id="heading' . $in_level2_counter . '">';


        $return_html .= '<h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#open' . $in_level2_counter . '" href="#collapse' . $in_level2_counter . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $in_level2_counter . '">' . '<i class="fal fa-plus-circle" style="font-size: 1em !important; margin-left: 0; width: 21px;"></i>'. ( $in['in_type'] ? 'Option #'. ($in_level2_counter + 1).': ' : '') . '<span id="title-' . $in_level2['in_id'] . '">' . $in_level2['in_outcome'] . '</span>';

        $in_level2_metadata = unserialize($in_level2['in_metadata']);
        if (isset($in_level2_metadata['in__tree_max_seconds']) && $in_level2_metadata['in__tree_max_seconds'] > 0) {
            $return_html .= ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock" style="width:16px; text-transform: none !important;"></i>' . fn___echo_time_range($in_level2, true) . '</span>';
        }

        $return_html .= '</a></h4>';
        $return_html .= '</div>';


        //Level 2 body:
        $return_html .= '<div id="collapse' . $in_level2_counter . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $in_level2_counter . '">';
        $return_html .= '<div class="panel-body" style="padding:5px 0 0 25px;">';

        //Messages:
        foreach ($in_level2_messages as $tr) {
            $return_html .= $CI->Chat_model->fn___dispatch_message($tr['tr_content']);
        }


        if (count($grandchildren_ins) > 0) {

            //List level 3:
            $return_html .= '<ul style="list-style-type: circle; margin:10px 0 10px -15px; font-size:1em;">';
            foreach ($grandchildren_ins as $in_level3_counter => $in_level3) {

                $return_html .= '<li>' . ($in_level2['in_type'] ? 'Option #' . ($in_level3_counter + 1) . ': ' : '') . $in_level3['in_outcome'];
                $in_level3_metadata = unserialize($in_level3['in_metadata']);
                if (isset($in_level3_metadata['in__tree_max_seconds']) && $in_level3_metadata['in__tree_max_seconds'] > 0) {
                    $return_html .= ' <span style="font-size: 0.9em; font-weight: 300;"><i class="fal fa-clock"></i> ' . fn___echo_time_range($in_level3, true) . '</span>';
                }
                $return_html .= '</li>';

            }
            $return_html .= '</ul>';

            //Show call to action to go here only:
            if (!$expand_mode) {
                //Since it has children, lets also give the option to navigate downwards:
                //TODO Maybe consider enabling later on, but for now I want to reduce clutter
                //$return_html .= '<p>You can also view <a href="/' . $in_level2['in_id'] . '" ' . ( $in['in_id'] == $CI->config->item('in_home_page') ? 'onclick="confirm_child_go(' . $in_level2['in_id'] . ')"' : '') . ' class="this-step alink-' . $in_level2['in_id'] . '">this intention</a> only.</p>';
            }

        }

        $return_html .= '</div></div></div></div>';

    }
    $return_html .= '</div>';

    return $return_html;
}

function fn___echo_tree_cost($in, $fb_messenger_format = 0, $expand_mode = false)
{

    /*
     *
     * An intent function to display estimated completion range
     * for the entire intent tree stored in the metadata field.
     *
     * */

    //Do we have anything to return?
    $metadata = unserialize($in['in_metadata']);
    $has_time_estimate = ( isset($metadata['in__tree_max_seconds']) && !($metadata['in__tree_max_seconds'] == 0) );
    $has_cost_estimate = ( isset($metadata['in__tree_max_cost']) && !($metadata['in__tree_max_cost'] == 0) );
    if (!$has_cost_estimate && !$has_time_estimate) {
        return false;
    }


    if($has_cost_estimate){
        //Construct UI:
        if (round($metadata['in__tree_max_cost']) == round($metadata['in__tree_min_cost']) || $metadata['in__tree_min_cost'] == 0) {
            //Single price:
            $price_range = '$' . round($metadata['in__tree_max_cost']) . ' USD';
        } else {
            //Price range:
            $price_range = '$' . round($metadata['in__tree_min_cost']) . ' - $' . round($metadata['in__tree_max_cost']) . ' USD';
        }
    }


    //For HTML version only:
    $title = '';
    $title .= ( $has_time_estimate ? fn___echo_time_range($in).' ' : '' );
    $title .= ( $has_cost_estimate ? ( $has_time_estimate ? 'and ' : '' ) . $price_range : '' );
    $title .= ' to Complete';

    //As messenger default format and HTML extra notes:
    $pitch  = 'Action Plan estimates ';
    $pitch .= ( $has_time_estimate ? strtolower(fn___echo_time_range($in)).' ' : '' );
    $pitch .= ( $has_cost_estimate ? ( $has_time_estimate ? ' and ' : '' ) . $price_range : '' );
    $pitch .= ' to ' . $in['in_outcome'];

    if ($fb_messenger_format) {
        return '⏰ ' . $pitch . "\n";
    } else {
        //HTML format
        $id = 'EstimatedTime';
        return '<div class="panel-group" id="open' . $id . '" role="tablist" aria-multiselectable="true"><div class="panel panel-primary">
            <div class="panel-heading" role="tab" id="heading' . $id . '">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#open' . $id . '" href="#collapse' . $id . '" aria-expanded="' . ($expand_mode ? 'true' : 'false') . '" aria-controls="collapse' . $id . '">
                        <i class="fas" style="transform:none !important;">⏰</i> ' . $title . '<i class="fal fa-info-circle" style="transform:none !important; font-size:0.85em !important;"></i>
                    </a>
                </h4>
            </div>
            <div id="collapse' . $id . '" class="panel-collapse collapse ' . ($expand_mode ? 'in' : 'out') . '" role="tabpanel" aria-labelledby="heading' . $id . '">
                <div class="panel-body overview-pitch">' . $pitch . '.</div>
            </div>
        </div></div>';
    }
}

function fn___echo_en_messages($tr){


    $CI =& get_instance();
    $session_en = $CI->session->userdata('user');
    $fixed_fields = $CI->config->item('fixed_fields');

    $ui = '<div class="entities-msg">';

    $ui .= '<div>';

    //Editing menu:
    $ui .= '<ul class="msg-nav" style="margin-bottom: 15px;">';


    //Referenced Intent:
    $ui .= '<li class="edit-off"><a class="btn btn-primary button-max" style="border:2px solid #fedd16 !important;" href="/intents/' . $tr['tr_child_intent_id'] . '" target="_parent" title="Message Intent: '.$tr['in_outcome'].'" data-toggle="tooltip" data-placement="top">'.$fixed_fields['in_type'][$tr['in_type']]['s_icon'].' '.$tr['in_outcome'].'</a></li>';

    //Transactions:
    $count_msg_trs = $CI->Database_model->fn___tr_fetch(array(
        '( tr_id = ' . $tr['tr_id'] . ' OR tr_parent_transaction_id = ' . $tr['tr_id'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
    $ui .= '<li class="edit-off ' . fn___echo_advance() . '"><a class="btn btn-primary" style="border:2px solid #fedd16 !important;" href="/ledger?tr_id=' . $tr['tr_id'] . '" target="_parent" title="Go to Transactions" data-toggle="tooltip" data-placement="top"><i class="fas fa-atlas"></i> '.fn___echo_number($count_msg_trs[0]['totals']).'</a></li>';

    //Order:
    $ui .= '<li class="edit-off message_status ' . fn___echo_advance() . '" style="margin: 0 3px 0 0;"><span title="Message order relative to siblings" data-toggle="tooltip" data-placement="top"><i class="fas fa-sort"></i>' . fn___echo_ordinal_number($tr['tr_order']) . '</span></li>';

    $ui .= '<li style="clear: both;">&nbsp;</li>';

    $ui .= '</ul>';

    $ui .= $CI->Chat_model->fn___dispatch_message($tr['tr_content'], $session_en, false);


    $ui .= '</div>';

    $ui .= '</div>';

    return $ui;
}

function fn___echo_time_range($in, $micro = false, $hide_zero = false)
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

    if (!isset($metadata['in__tree_max_seconds']) || !isset($metadata['in__tree_min_seconds_cost'])) {
        return false;
    } elseif($hide_zero && $metadata['in__tree_max_seconds'] < 1){
        return false;
    }

    //Construct the UI:
    if ($metadata['in__tree_max_seconds'] == $metadata['in__tree_min_seconds_cost']) {

        //Exactly the same, show a single value:
        return fn___echo_time_hours($metadata['in__tree_max_seconds'], $micro);

    } elseif ($metadata['in__tree_min_seconds_cost'] < 3600) {

        if ($metadata['in__tree_min_seconds_cost'] < 7200 && $metadata['in__tree_max_seconds'] < 10800 && ($metadata['in__tree_max_seconds'] - $metadata['in__tree_min_seconds_cost']) > 1800) {
            $is_minutes = true;
            $hours_decimal = 0;
        } elseif ($metadata['in__tree_min_seconds_cost'] < 36000) {
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

    $min_minutes = round($metadata['in__tree_min_seconds_cost'] / 60);
    $min_hours = round(($metadata['in__tree_min_seconds_cost'] / 3600), $hours_decimal);
    $max_minutes = round($metadata['in__tree_max_seconds'] / 60);
    $max_hours = round(($metadata['in__tree_max_seconds'] / 3600), $hours_decimal);

    //Generate hours range:
    $the_min = ($is_minutes ? $min_minutes : $min_hours );
    $the_max = ($is_minutes ? $max_minutes : $max_hours );
    $ui_time = $the_min;
    if($the_min != $the_max){
        $ui_time .= '-';
        $ui_time .= $the_max;
    }
    $ui_time .= ($is_minutes ? ($micro ? 'm' : ' Minute'.fn___echo__s($max_minutes)) : ($micro ? 'h' : ' Hour'.fn___echo__s($max_hours)));

    //Generate UI to return:
    return $ui_time;
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


function fn___echo_fixed_fields($obj_type = null, $status = null, $micro_status = false, $data_placement = 'bottom')
{

    /*
     *
     * Displays Object Status for Intents, Entities and Transactions
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


function fn___echo_in_featured($in)
{
    $ui = '<a href="/' . $in['in_id'] . '" class="list-group-item">';

    $ui .= '<span class="pull-right">';
    $ui .= '<span class="badge badge-primary fr-bgd"><i class="fas fa-angle-right"></i></span>';
    $ui .= '</span>';

    $ui .= '<span style="color:#222; font-weight:500; font-size:1.2em;">'.$in['in_outcome'].'</span>';
    $ui .= '<span style="font-size:0.8em; font-weight:300; margin-left:5px; display:inline-block;">';
    $ui .= '<span><i class="fal fa-clock"></i>' . fn___echo_time_range($in) . '</span>';
    $ui .= '</span>';
    $ui .= '</a>';
    return $ui;
}

function fn___echo_time_milliseconds($microtime)
{
    $time = $microtime / 1000;
    return date("Y-m-d H:i:s", floor($time)) . '.' . fn___one_two_explode('.', '', $time);
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
    $session_en = $CI->session->userdata('user');
    $fixed_fields = $CI->config->item('fixed_fields');
    $en_all_4331 = $CI->config->item('en_all_4331');
    $is_child_focused = ($level == 3 && $is_parent && $CI->uri->segment(2)==$in['in_id']);

    //Prepare Intent Metadata:
    $in_metadata = unserialize($in['in_metadata']);


    if ($level <= 1) {

        //No Transaction for level 1 intent:
        $tr_id = 0;
        $tr_metadata = array();

        $ui = '<div class="list-group-item top_intent">';

    } else {

        //Prep transaction metadata to be analyzed later:
        $tr_id = $in['tr_id'];
        $tr_metadata = unserialize($in['tr_metadata']);

        $ui = '<div in-tr-id="' . $tr_id . '" in-tr-type="' . $in['tr_type_entity_id'] . '" intent-id="' . $in['in_id'] . '" parent-intent-id="' . $in_parent_id . '" intent-level="' . $level . '" class="list-group-item ' . ($level == 3 || ($level == 2 && !$is_parent) ? ' enable-sorting ' : '') . ($level == 3 ? 'is_level3_sortable' : 'is_level2_sortable level2_in') . ' intent_line_' . $in['in_id'] . ( $is_parent && $level!=3 ? ' parent-intent ' : '' ) . ' in__tr_'.$tr_id.'">';

    }





    /*
     *
     * Start Left Side
     *
     * */

    $ui .= '<span style="display:inline-block; margin-top:0px;">';









    //Hidden fields to store dynamic value for on-demand JS modifications:
    //Show Transaction Status if Available:
    if ($level == 1) {

        //Show Blank box:
        $ui .= '<span class="double-icon" style="margin: 0 2px 0 -4px;"><span class="icon-main"><i class="fas fa-map-pin" data-toggle="tooltip" data-placement="right" title="You are Here"></i></span><span class="icon-top-right">&nbsp;</span></span>';

    } elseif($level > 1) {

        //Fetch intent link types:
        $en_all_4486 = $CI->config->item('en_all_4486');

        //Show Transaction link icons:
        $ui .= '<span class="double-icon" style="margin:0 3px 0 -3px;">';

        //Show larger icon for transaction type (auto detected based on transaction content):
        $ui .= '<span class="icon-main tr_type_' . $tr_id . '"><span data-toggle="tooltip" data-placement="right" title="' . $en_all_4486[$in['tr_type_entity_id']]['m_name'] . ': ' . $en_all_4486[$in['tr_type_entity_id']]['m_desc'] . '">' . $en_all_4486[$in['tr_type_entity_id']]['m_icon'] . '</span></span>';

        //Show smaller transaction status icon:
        $ui .= '<span class="icon-top-right tr_status_' . $tr_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['tr_status'][$in['tr_status']]['s_name'].': '.$fixed_fields['tr_status'][$in['tr_status']]['s_desc'].'">' . $fixed_fields['tr_status'][$in['tr_status']]['s_icon'] . '</span></span>';

        //Count and show total up-votes for this intent correlation (not necessarily this exact transaction, but the parent/child intent relation)
        $tr_upvotes = $CI->Database_model->fn___tr_fetch(array(
            'tr_parent_intent_id' => $in['tr_parent_intent_id'],
            'tr_child_intent_id' => $in['tr_child_intent_id'],
            'tr_type_entity_id' => 4983, //Up-votes
            'tr_status >=' => 0, //New+
        ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');

        $ui .= '<span class="icon-top-left ' . fn___echo_advance() . ' in_upvotes_' . $in['tr_child_intent_id'] . '_'.$in['tr_parent_intent_id'].'" data-toggle="tooltip" data-placement="right" title="Up-Votes">' . ( $tr_upvotes[0]['totals'] > 0 ? $tr_upvotes[0]['totals'] : '' ) . '</span>';

        //Show assessment score based on Intent Link Type:
        $ui .= '<span class="icon-3rd in_assessment_' . $tr_id . '" data-toggle="tooltip" data-placement="right" title="Assessment Score">'. ( $in['tr_type_entity_id'] == 4228 ? ( !isset($tr_metadata['tr__assessment_points']) || $tr_metadata['tr__assessment_points'] == 0 ? '' : ( $tr_metadata['tr__assessment_points'] > 0 ? '+' : '' ) . $tr_metadata['tr__assessment_points'] ) : $tr_metadata['tr__conditional_score_min'] . ( $tr_metadata['tr__conditional_score_min']==$tr_metadata['tr__conditional_score_max'] ? '' : '-'.$tr_metadata['tr__conditional_score_max'] ).'%' ) .'</span>';

        $ui .= '</span>';

    }




    //Always Show Intent Icon (AND or OR)
    $ui .= '<span class="double-icon" style="margin-right:5px;">';

    //Show larger intent icon (AND or OR):
    $ui .= '<span class="icon-main in_type_' . $in['in_id'] . '"><span class="in_type_val" data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['in_type'][$in['in_type']]['s_name'].': '.$fixed_fields['in_type'][$in['in_type']]['s_desc'].'">' . $fixed_fields['in_type'][$in['in_type']]['s_icon'] . '</span></span>';

    //Show smaller intent status:
    $ui .= '<span class="icon-top-right in_status_' . $in['in_id'] . '"><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['in_status'][$in['in_status']]['s_name'].': '.$fixed_fields['in_status'][$in['in_status']]['s_desc'].'">' . $fixed_fields['in_status'][$in['in_status']]['s_icon'] . '</span></span>';

    //Status locked intent?
    if(in_array($in['in_id'],$CI->config->item('in_status_locked'))){
        $ui .= '<span class="icon-top-left" data-toggle="tooltip" data-placement="right" title="Status locked by system"><i class="fas fa-lock"></i></span>';
    }

    $ui .= '<span class="icon-3rd ' . fn___echo_advance() . ' in_completion_' . $in['in_id'] . '" data-toggle="tooltip" data-placement="right" title="Completion Requirement">'.( $in['in_requirement_entity_id'] > 0 ? $en_all_4331[$in['in_requirement_entity_id']]['m_name']  : '' ).'</span>';

    $ui .= '</span>';





    //Intent UI based on level:
    if ($level <= 1) {

        $ui .= '<span><b id="in_level1_outcome" style="font-size: 1.4em; padding-left: 5px;">';
        $ui .= '<span class="in_outcome_' . $in['in_id'] . '">' . $in['in_outcome'] . '</span>';
        $ui .= '</b></span>';

    } elseif ($level == 2) {

        $ui .= '<span>&nbsp;<i id="handle-' . $tr_id . '" class="fal click_expand fa-plus-circle"></i> <span id="title_' . $tr_id . '" style="font-weight: 500;" class="cdr_crnt click_expand tree_title in_outcome_' . $in['in_id'] . '">' . $in['in_outcome'] . '</span></span>';

    } elseif ($level == 3) {

        $ui .= '<span id="title_' . $tr_id . '" class="tree_title in_outcome_' . $in['in_id'] . '" style="padding-left:23px;">' . $in['in_outcome'] . '</span> ';

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
        $in['in__parents'] = $CI->Database_model->fn___tr_fetch(array(
            'tr_status >=' => 0, //New+
            'in_status >=' => 0, //New+
            'tr_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
            'tr_child_intent_id' => $in['in_id'],
        ), array('in_parent')); //Note that parents do not need any sorting, since we only sort child intents

    }

    //Loop through parents:
    $ui .= '<span class="' . fn___echo_advance() . '">';
    foreach ($in['in__parents'] as $in_parent) {
        $ui .= ' &nbsp;<a href="/intents/' . $in_parent['in_id'] . '" data-toggle="tooltip" title="' . $in_parent['in_outcome'] . '" data-placement="top" class="in_icon_child_' . $in_parent['in_id'] . '">' . $fixed_fields['in_type'][$in_parent['in_type']]['s_icon'] . '</a>';
    }
    $ui .= '</span>';




    $ui .= '<span style="display: inline-block; float: right;">'; //Start of 5x Action Buttons



    //Action Plan Stats:
    if(0){
        //TODO activate later...
        $count_in_actionplans = $CI->Database_model->fn___tr_fetch(array(
            'tr_type_entity_id' => 4559, //Action Plan Step
            'tr_child_intent_id' => $in['in_id'], //For this Intent
        ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');

        if ($count_in_actionplans[0]['totals'] > 0) {

            //Yes, this intent has been added to some Action Plans, let's see what % is completed so far:
            $count_in_actionplans_complete = $CI->Database_model->fn___tr_fetch(array(
                'tr_type_entity_id' => 4559, //Action Plan Step
                'tr_child_intent_id' => $in['in_id'], //For this Intent
                'tr_status NOT IN (' . join(',', $CI->config->item('tr_status_incomplete')) . ')' => null, //completed
            ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');

            //Show link to load these intents in Student Action Plans:
            $ui .= '<a href="/ledger?cc=' . $in['in_id'] . '-' . $tr_id . '-4559" class="badge badge-primary is_not_bg" style="width:40px; margin-right:2px;" data-toggle="tooltip" title="' . $count_in_actionplans_complete[0]['totals'] . '/' . $count_in_actionplans[0]['totals'] . ' completed (or skipped) across all Action Plans" data-placement="top"><span class="btn-counter">' . round($count_in_actionplans_complete[0]['totals'] / $count_in_actionplans[0]['totals'] * 100) . '%</span><i class="far fa-flag" style="font-size:0.85em;"></i></a>';

        }
    }


    //Action Plan:
    //TODO Count transactions and Implement later...
    $ui .= '<a href="#loadinactionplans-' . $in['in_id'] . '" onclick="'.( $level==0 ? 'alert(\'Cannot manage here. Go to the intent to manage.\')' : 'fn___in_action_plans(' . $in['in_id'] . ')' ).'" class="badge badge-primary ' . fn___echo_advance() . ' white-primary action_plans_in_'.$in['in_id'].'" ap-count="'.(0).'" style="margin:-2px -3px 0 5px; width:40px;" data-toggle="tooltip" data-placement="top" title="Intent Action Plans"><span class="btn-counter">'.fn___echo_number(0).'</span><i class="far fa-flag" style="width:28px; padding-right:7px; text-align:center;"></i></a>';



    //Intent Notes:
    $count_in_metadata = $CI->Database_model->fn___tr_fetch(array(
        'tr_status >=' => 0, //New+
        'tr_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
        'tr_child_intent_id' => $in['in_id'],
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
    $ui .= '<a href="#intentmessages-' . $in['in_id'] . '" onclick="'.( $level==0 ? 'alert(\'Cannot manage here. Go to the intent to manage.\')' : 'fn___in_messages_load('.$in['in_id'].')' ).'" class="msg-badge-' . $in['in_id'] . ' badge badge-primary white-primary is_not_bg '.( $level==0 ? '' . fn___echo_advance() . '' : '' ).'" style="width:40px; margin-right:2px; margin-left:5px;" data-toggle="tooltip" title="Intent Notes" data-placement="top"><span class="btn-counter messages-counter-' . $in['in_id'] . '">' . $count_in_metadata[0]['totals'] . '</span><i class="fas fa-comment-plus"></i></a>';



    //Intent modify:
    $in__tree_max_seconds = (isset($in_metadata['in__tree_max_seconds']) ? $in_metadata['in__tree_max_seconds'] : 0);
    $ui .= '<a class="badge badge-primary white-primary is_not_bg '.( $level==0 ? '' . fn___echo_advance() . '' : '' ).'" onclick="'.( $level==0 ? 'alert(\'Cannot manage here. Go to the intent to manage.\')' : 'fn___in_modify_load(' . $in['in_id'] . ',' . $tr_id . ')' ).'" style="margin:-2px -8px 0 0; width:40px;" href="#loadmodify-' . $in['in_id'] . '-' . $tr_id . '" data-toggle="tooltip" title="Intent completion cost. Click to modify intent'.( $level>1 ? ' and transaction' : '' ).'" data-placement="top"><span class="btn-counter slim-time t_estimate_' . $in['in_id'] . '" intent-usd="'.$in['in_dollar_cost'].'" tree-max-seconds="' . $in__tree_max_seconds . '" intent-seconds="' . $in['in_seconds_cost'] . '">'.( $in__tree_max_seconds > 0 ? fn___echo_time_hours($in__tree_max_seconds , true) : 0 ).'</span><i class="fas fa-cog"></i></a> &nbsp;';


    //Intent Transactions:
    $count_in_trs = $CI->Database_model->fn___tr_fetch(array(
        '(tr_parent_intent_id=' . $in['in_id'] . ' OR tr_child_intent_id=' . $in['in_id'] . ($tr_id > 0 ? ' OR tr_parent_transaction_id=' . $tr_id : '') . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
    //Show link to load these transactions:
    $ui .= '<a href="/ledger?any_in_id=' . $in['in_id'] . '&tr_parent_transaction_id=' . $tr_id . '" class="badge badge-primary ' . fn___echo_advance() . ' is_not_bg" style="width:40px; margin:-3px 0px 0 4px; border:2px solid #fedd16 !important;" data-toggle="tooltip" data-placement="top" title="Go to Transactions"><span class="btn-counter">' . fn___echo_number($count_in_trs[0]['totals']) . '</span><i class="fas fa-atlas"></i></a>';

    $tree_count = null;
    if(isset($in_metadata['in__tree_in_active_count'])){
        $tree_count = '<span class="btn-counter ' . fn___echo_advance() . ' children-counter-' . $in['in_id'] . ' ' . ($is_parent && $level == 2 ? 'inb-counter' : '') . '">' . $in_metadata['in__tree_in_active_count'] . '</span>';
    }

    //Intent Link to Travel Down/UP the Tree:
    if ($level == 0) {

        //Show Landing Page URL:
        $ui .= '&nbsp;<a href="/intents/' . $in['in_id'] . '" class="badge badge-primary is_not_bg is_hard_link" style="display:inline-block; margin-right:-2px; width:40px; border:2px solid #fedd16 !important;">'.$tree_count.'<i class="fas fa-angle-right"></i></a>';

    } elseif ($level == 1 || $is_child_focused) {

        //Show Landing Page URL:
        $ui .= '&nbsp;<a href="/' . $in['in_id'] . '" target="_blank" class="badge badge-primary is_not_bg is_hard_link" style="display:inline-block; margin-right:-2px; width:40px; border:2px solid #fedd16 !important;" data-toggle="tooltip" title="Landing Page (New Window)" data-placement="top">'.$tree_count.'<i class="fas fa-shopping-cart" style="margin-left: -3px;"></i></a>';

    } else {

        $ui .= '&nbsp;<a href="/intents/' . $in['in_id'] . '" class="tree-badge-' . $in['in_id'] . ' badge badge-primary is_not_bg is_hard_link" style="display:inline-block; margin-right:-2px; width:40px; border:2px solid #fedd16 !important;" data-toggle="tooltip" title="Go to this intent" data-placement="top">' . $tree_count . '<i class="'.( $is_parent ? ( $level==3 ? 'fas fa-angle-right' : 'fas fa-angle-up' ) : ( $level==3 ? 'fas fa-angle-double-down' : 'fas fa-angle-down' ) ).'"></i></a>';

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
            $in['in__grandchildren'] = $CI->Database_model->fn___tr_fetch(array(
                'tr_status >=' => 0, //New+
                'in_status >=' => 0, //New+
                'tr_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4486')) . ')' => null, //Intent Link Types
                'tr_parent_intent_id' => $in['in_id'],
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
                <div class="form-group is-empty"  style="margin: 0; padding: 0;"><form action="#" onsubmit="fn___in_link_or_create(' . $in['in_id'] . ',3);" intent-id="' . $in['in_id'] . '"><input type="text" class="form-control autosearch intentadder-level-3 intentadder-id-'.$in['in_id'].' algolia_search bottom-add" maxlength="' . $CI->config->item('in_outcome_max') . '" id="addintent-cr-' . $tr_id . '" intent-id="' . $in['in_id'] . '" placeholder="Add #Intent"></form></div>
        </div>';


        $ui .= '</div>';
    }


    $ui .= '</div>';
    return $ui;

}


function fn___detect_fav_icon($url_clean_domain, $return_icon = false){
    //Does this domain have a Favicon?
    $fav_icon = $url_clean_domain . '/favicon.ico';
    $is_valid_icon = @file_get_contents($fav_icon);
    if ($is_valid_icon) {
        return '<img src="'.$fav_icon.'">';
    } else {
        return ( $return_icon ? '<i class="fas fa-at grey-at"></i>' : null );
    }
}

function fn___echo_rank($integer){
    if($integer==1){
        return '🏅';
    } elseif($integer==2){
        return '🥈';
    } elseif($integer==3){
        return '🥉';
    } else {
        return fn___echo_ordinal_number($integer);
    }
}


function fn___echo_en($en, $level, $is_parent = false)
{

    $CI =& get_instance();
    $session_en = $CI->session->userdata('user');
    $fixed_fields = $CI->config->item('fixed_fields');
    $tr_id = (isset($en['tr_id']) ? $en['tr_id'] : 0);
    $ui = null;


    $ui .= '<div entity-id="' . $en['en_id'] . '" entity-status="' . $en['en_status'] . '" tr-id="'.$tr_id.'" tr-status="'.( $tr_id > 0 ? $en['tr_status'] : 0 ).'" is-parent="' . ($is_parent ? 1 : 0) . '" class="list-group-item en-item en___' . $en['en_id'] . ' ' . ($level <= 1 ? 'top_entity' : 'tr_' . $en['tr_id']) . ( $is_parent ? ' parent-entity ' : '' ) . '">';





    $ui .= '<span style="display:inline-block; margin-top:0px;">';






    //Hidden fields to store dynamic value for on-demand JS modifications:
    //Show Transaction Status if Available:
    if ($tr_id > 0) {

        //Transaction Type Full List:
        $en_all_4594 = $CI->config->item('en_all_4594');

        //Show Transaction link icons:
        $ui .= '<span class="double-icon" style="margin-right:7px;">';

        //Show larger icon for transaction type (auto detected based on transaction content):
        $ui .= '<span class="icon-main tr_type_' . $tr_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$en_all_4594[$en['tr_type_entity_id']]['m_name'].'">' . $en_all_4594[$en['tr_type_entity_id']]['m_icon'] . '</span></span> ';

        //Show smaller transaction status icon:
        $ui .= '<span class="icon-top-right tr_status_' . $tr_id . '"><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['tr_status'][$en['tr_status']]['s_name'].': '.$fixed_fields['tr_status'][$en['tr_status']]['s_desc'].'">' . $fixed_fields['tr_status'][$en['tr_status']]['s_icon'] . '</span></span>';

        $ui .= '</span>';

    } elseif( $level > 0 ) {

        //Show Blank box:
        $ui .= '<span class="double-icon" style="margin:0 3px;"><span class="icon-main"><i class="fas fa-map-pin" data-toggle="tooltip" data-placement="right" title="You are Here"></i></span><span class="icon-top-right">&nbsp;</span></span>';

    }





    //Always Show Entity Icons
    $ui .= '<span class="double-icon" style="margin-right:7px;">';

    //Show larger custom entity icon:
    $ui .= '<span class="icon-main en_ui_icon en_ui_icon_' . $en['en_id'] . ' en-icon en__icon_'.$en['en_id'].'" en-is-set="'.( strlen($en['en_icon']) > 0 ? 1 : 0 ).'" data-toggle="tooltip" data-placement="right" title="Entity Icon">' . fn___echo_icon($en) . '</span>';

    //Show smaller entity status:
    $ui .= '<span class="icon-top-right en_status_' . $en['en_id'] . '"><span data-toggle="tooltip" data-placement="right" title="'.$fixed_fields['en_status'][$en['en_status']]['s_name'].': '.$fixed_fields['en_status'][$en['en_status']]['s_desc'].'">' . $fixed_fields['en_status'][$en['en_status']]['s_icon'] . '</span></span>';

    $ui .= '</span>';



    //Entity Name:
    $ui .= '<span class="en_name en_name_' . $en['en_id'] . '">' . $en['en_name'] . '</span>';

    $ui .= '</span>';



    //Does this entity also include a transaction?
    if ($tr_id > 0) {

        //Show transaction content:
        $tr_content = fn___echo_tr_urls($en['tr_content'] , $en['tr_type_entity_id']);

        //Is this Entity transaction an Embeddable URL type or not?
        $ui .= ' <span class="tr_content tr_content_' . $tr_id . '" style="min-width:240px; line-height: 140%; display:inline-block;">';
        $ui .= $tr_content;
        $ui .= '</span>';

        //This is for JS editing:
        $ui .= '<span class="tr_content_val_' . $tr_id . ' hidden">' . $en['tr_content'] . '</span>';

    }






    //Right content:
    $ui .= '<span class="pull-right" style="padding-top:2px;">';

    //Do we have entity parents loaded in our data-set? If not, load it:
    if (!isset($en['en__parents'])) {
        //Fetch parents at this point:
        $en['en__parents'] = $CI->Database_model->fn___tr_fetch(array(
            'tr_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'tr_child_entity_id' => $en['en_id'], //This child entity
            'tr_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_parent'), 0, 0, array('en_trust_score' => 'DESC'));
    }

    //Loop through parents and only show those that have en_icon set:
    $ui .= '<span class="' . fn___echo_advance() . '">';
    foreach ($en['en__parents'] as $en_parent) {
        $ui .= ' &nbsp;<a href="/entities/' . $en_parent['en_id'] . '" data-toggle="tooltip" title="' . $en_parent['en_name'] . (strlen($en_parent['tr_content']) > 0 ? ' = ' . $en_parent['tr_content'] : '') . '" data-placement="top" class="parent-icon en_child_icon_' . $en_parent['en_id'] . '">' . (strlen($en_parent['en_icon']) > 0 ? $en_parent['en_icon'] : '<i class="fas fa-at grey-at"></i>' ) . '</a>';
    }
    $ui .= '</span>';




    $ui .= '<span style="display: inline-block; float: right;">'; //Start of 5x Action Buttons



    //Action Plan:
    //TODO Count transactions and Implement later...
    $ui .= '<a href="#loadenactionplans-' . $en['en_id'] . '" onclick="'.( $level==0 ? 'alert(\'Cannot manage here. Go to the entity to manage.\')' : 'fn___en_action_plans(' . $en['en_id'] . ')' ).'" class="badge badge-secondary ' . fn___echo_advance() . ' white-secondary action_plans_en_'.$en['en_id'].'" ap-count="'.(0).'" style="margin:-2px -3px 0 5px;; width:40px;" data-toggle="tooltip" data-placement="top" title="Entity Action Plans"><span class="btn-counter">'.fn___echo_number(0).'</span><i class="far fa-flag" style="width:28px; padding-right:7px; text-align:center;"></i></a>';



    //Count & Display active Intent Notes that this entity has been referenced within:
    $messages = $CI->Database_model->fn___tr_fetch(array(
        'tr_status >=' => 0, //New+
        'tr_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4485')) . ')' => null, //All Intent Notes
        'tr_parent_entity_id' => $en['en_id'], //Entity Referenced in message content
    ), array(), 0, 0, array(), 'COUNT(tr_id) AS total_messages');

    //Only show in non-advance mode if we have messages:
    $ui .= '<a class="badge badge-secondary white-secondary '.( $level==0 || $messages[0]['total_messages'] == 0 ? fn___echo_advance() : '' ) . '" href="#entitymessages-' . $en['en_id'] . '" onclick="' . ( $messages[0]['total_messages'] == 0 ? 'alert(\'No Intent Notes found that reference this entity\')' : ( $level==0 ? 'alert(\'Cannot manage here. Go to the entity to manage.\')' : 'fn___load_en_messages('.$en['en_id'].')' ) ) . '" style="width:40px; margin-left:5px;" data-toggle="tooltip" data-placement="top" title="Entity References within Intent Notes"><span class="btn-counter">' . $messages[0]['total_messages'] . '</span><i class="fas fa-comment-plus"></i></a>';




    //Modify Entity:
    $ui .= '<a href="#loadmodify-' . $en['en_id'] . '-' . $tr_id . '" onclick="'.( $level==0 ? 'alert(\'Cannot manage here. Go to the entity to manage.\')' : 'fn___en_modify_load(' . $en['en_id'] . ',' . $tr_id . ')' ).'" class="badge badge-secondary white-secondary '.( $level==0 ? '' . fn___echo_advance() . '' : '' ).'" style="margin:-2px -6px 0 2px; width:40px;" data-toggle="tooltip" data-placement="top" title="Entity trust score. Click to modify entity'.( $level>1 ? ' and transaction' : '' ).'"><span class="btn-counter ' . fn___echo_advance() . '">'.fn___echo_number($en['en_trust_score']).'</span><i class="fas fa-cog" style="width:28px; padding-right:7px; text-align:center;"></i></a> &nbsp;';



    //Count & link to Entity transactions:
    $count_in_trs = $CI->Database_model->fn___tr_fetch(array(
        '(tr_parent_entity_id=' . $en['en_id'] . ' OR  tr_child_entity_id=' . $en['en_id'] . ' OR  tr_miner_entity_id=' . $en['en_id'] . ($tr_id > 0 ? ' OR tr_parent_transaction_id=' . $tr_id : '') . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(tr_id) as totals');
    if ($count_in_trs[0]['totals'] > 0) {
        //Show the transaction button:
        $ui .= '<a href="/ledger?any_en_id=' . $en['en_id'] . '&tr_parent_transaction_id=' . $tr_id . '" class="badge badge-secondary ' . fn___echo_advance() . '" style="width:40px; margin:-3px 2px 0 2px; border:2px solid #0084ff !important;" data-toggle="tooltip" data-placement="top" title="Go to Transactions"><span class="btn-counter">' . fn___echo_number($count_in_trs[0]['totals']) . '</span><i class="fas fa-atlas"></i></a>';
    }






    //Have we counted the Entity Children?
    if (!isset($en['en__child_count'])) {
        //Assume none:
        $en['en__child_count'] = 0;

        //Do a child count:
        $child_trs = $CI->Database_model->fn___tr_fetch(array(
            'tr_parent_entity_id' => $en['en_id'],
            'tr_type_entity_id IN (' . join(',', $CI->config->item('en_ids_4592')) . ')' => null, //Entity Link Connectors
            'tr_status >=' => 0, //New+
            'en_status >=' => 0, //New+
        ), array('en_child'), 0, 0, array(), 'COUNT(en_id) as en__child_count');

        if (count($child_trs) > 0) {
            $en['en__child_count'] = intval($child_trs[0]['en__child_count']);
        }
    }

    if($level == 1){

        $ui .= '<a class="badge badge-secondary" href="https://www.google.com/search?q=' . urlencode($en['en_name']) . '" target="_blank" style="display:inline-block; margin-right:6px; width:40px; margin-left:1px; border:2px solid #0084ff !important;" data-toggle="tooltip" data-placement="top" title="Google Search (New Window)"><span class="btn-counter ' . fn___echo_advance() . '"><i class="fas fa-external-link"></i></span><i class="fas fa-search"></i></a>';

    } else {

        $ui .= '<a class="badge badge-secondary" href="/entities/' . $en['en_id']. '" style="display:inline-block; margin-right:6px; width:40px; margin-left:1px; border:2px solid #0084ff !important;" data-toggle="tooltip" data-placement="top" title="Go to this Entity">' . ($en['en__child_count'] > 0 ? '<span class="btn-counter ' . fn___echo_advance() . '" title="' . number_format($en['en__child_count'], 0) . ' Entities">' . fn___echo_number($en['en__child_count']) . '</span>' : '') . '<i class="'.( $level==0 ? 'fas fa-angle-right' : ( $is_parent ? 'fas fa-angle-up' : 'fas fa-angle-down' )).'"></i></a>';

    }

    $ui .= '</span>'; //End of 5x Action Buttons

    $ui .= '</span>';


    //To clear right float:
    $ui .= '<div style="clear: both; margin: 0; padding: 0;"></div>';


    $ui .= '</div>';

    return $ui;

}


function fn___echo_json($array)
{
    header('Content-Type: application/json');
    echo json_encode($array);
}


function fn___echo_ordinal_number($number)
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

