<?php


function view_db_field($field_name){

    //Takes a database field name and returns a human-friendly version
    return ucwords(str_replace('i__', '', str_replace('e__', '', str_replace('x__', '', $field_name))));

}





function view_cover($cover_code, $noicon_default = null, $icon_prefix = '')
{

    $valid_url = ( filter_var($cover_code, FILTER_VALIDATE_URL) || substr($cover_code, 0, 2)=='//' );

    //A simple function to display the Member Cover OR the default icon if not available:
    if($valid_url && $noicon_default){

        return $icon_prefix.'<div class="img" style="background-image:url(\''.$cover_code.'\');"></div>';

    } elseif($valid_url){

        return $icon_prefix.'<img src="'.$cover_code.'"'.( substr_count($cover_code, 'class=') ? ' class="'.str_replace(',',' ',one_two_explode('class=','&', $cover_code)).'" ' : '' ).'/>';

    } elseif (string_is_icon($cover_code)) {

        return $icon_prefix.'<i class="'.$cover_code.'"></i>';

    } elseif(strlen($cover_code)) {

        return $icon_prefix.$cover_code;

    } elseif($noicon_default && $noicon_default!=1) {

        return $icon_prefix.$noicon_default;

    } else {

        //Standard Cover if none:
        return null;

    }
}

function view_url($string){
    return preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="$0" target="_blank">$0</a>', $string);
}

function view_number($number)
{

    if(intval($number) < 1){
        return null;
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


function view_card_x($x, $has_x__reference = false)
{

    $CI =& get_instance();
    $e___32088 = $CI->config->item('e___32088'); //Platform Variables
    $ui = '<div class="x-list">';
    foreach($CI->config->item('e___4341') as $e__id => $m) {

        if(in_array(6160 , $m['m__following']) && intval($x[$e___32088[$e__id]['m__message']])>0){

            //SOURCE
            foreach($CI->E_model->fetch(array('e__id' => $x[$e___32088[$e__id]['m__message']])) as $focus_e){
                $ui .= '<div class="simple-line"><a href="/@'.$focus_e['e__handle'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="main__title"><span class="icon-block">'.$m['m__cover']. '</span>'.'<span class="icon-block">'.view_cover($focus_e['e__cover'], true). '</span>'.$focus_e['e__title'].'</a></div>';
            }

        } elseif(in_array(6202 , $m['m__following']) && intval($x[$e___32088[$e__id]['m__message']])>0){

            //IDEA
            foreach($CI->I_model->fetch(array('i__id' => $x[$e___32088[$e__id]['m__message']])) as $focus_i){
                $ui .= '<div class="simple-line"><a href="/~'.$focus_i['i__hashtag'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="main__title"><span class="icon-block">'.$m['m__cover']. '</span><span class="icon-block">'.view_cache(4737 /* Idea Type */, $focus_i['i__type'], true, 'right', $focus_i['i__id']).'</span>'.view_i_title($focus_i).'</a></div>';
            }


        } elseif(in_array(4367 , $m['m__following']) && intval($x[$e___32088[$e__id]['m__message']])>0){

            //TRANSACTION
            if(!$has_x__reference){
                foreach($CI->X_model->fetch(array('x__id' => $x[$e___32088[$e__id]['m__message']])) as $ref_x){
                    $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'">'.$m['m__cover']. '</span><div class="x-ref hidden x_message_'.$x['x__id'].'">'.view_card_x($ref_x, true).'</div><a class="x_message_'.$x['x__id'].'" href="javascript:void(0);" onclick="$(\'.x_message_'.$x['x__id'].'\').toggleClass(\'hidden\');">View Referenced Transaction</a></div>';
                }
            } else {
                //Simple Reference to avoid Loop:
                $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $m['m__title'].': '.$x['x__time'] . ' PST"><span class="icon-block">'.$m['m__cover']. '</span>' . view_time_difference($x['x__time']) . ' Ago</span></div>';
            }

        } elseif($e__id==4367){

            //ID
            $ui .= '<div class="simple-line"><a href="'.view_app_link(4341).'?x__id='.$x['x__id'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="mono-space"><span class="icon-block">'.$m['m__cover']. '</span>'.$x['x__id'].'</a></div>';

        } elseif($e__id==4362){

            //TIME
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $m['m__title'].': '.$x['x__time'] . ' PST"><span class="icon-block">'.$m['m__cover']. '</span>' . view_time_difference($x['x__time']) . ' Ago</span></div>';

        } elseif($e__id==4370 && $x['x__weight'] > 0){

            //Order
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$m['m__title']. '"><span class="icon-block">'.$m['m__cover']. '</span>'.view_ordinal($x['x__weight']).'</span></div>';

        } elseif($e__id==6103 && strlen($x['x__metadata']) > 0){

            //Metadata
            $ui .= '<div class="simple-line"><a href="'.view_app_link(12722).'?x__id=' . $x['x__id'] . '" target="_blank"><span class="icon-block">'.$m['m__cover']. '</span><u>'.$m['m__title']. '</u></a></div>';

        } elseif($e__id==4372 && strlen($x['x__message']) > 0){

            //Message
            $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'"><span class="icon-block">'.$m['m__cover'].'</span><div class="title-block">'.( strip_tags($x['x__message'])==$x['x__message'] || strlen(strip_tags($x['x__message']))<view_memory(6404,6197) ? $x['x__message'] : '<span class="hidden html_message_'.$x['x__id'].'">'.$x['x__message'].'</span><a class="html_message_'.$x['x__id'].'" href="javascript:void(0);" onclick="$(\'.html_message_'.$x['x__id'].'\').toggleClass(\'hidden\');"><u>View HTML Message</u></a>' ).'</div></div>';

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

function view_qr($url, $width = 233, $height = 233) {
    $url    = urlencode($url);
    $image  = '<img src="http://chart.apis.google.com/chart?chs='.$width.'x'.$height.'&cht=qr&chl='.$url.'" alt="QR code" width="'.$width.'" height="'.$height.'"/>';
    return $image;
}

function view_time_difference($t, $micro = false)
{

    $second_time = time(); //Now

    $time = $second_time - (is_int($t) ? $t : strtotime(substr($t, 0, 19))); // to get the time since that moment
    $has_future = ($time < 0);
    $time = abs($time);
    if($micro){
        $time_units = array(
            31536000 => 'y',
            2592000 => 'mo',
            604800 => 'w',
            86400 => 'd',
            3600 => 'h',
            60 => 'min',
            1 => 'sec'
        );
    } else {
        $time_units = array(
            31536000 => 'Year',
            2592000 => 'Month',
            604800 => 'Week',
            86400 => 'Day',
            3600 => 'Hour',
            60 => 'Minute',
            1 => 'Second'
        );
    }


    foreach($time_units as $unit => $period) {
        if ($time < $unit && $unit > 1) continue;
        $numberOfUnits = number_format(($time / $unit), 0);
        if ($numberOfUnits < 1 && $unit==1) {
            $numberOfUnits = 1; //Change "0 seconds" to "1 second"
        }

        return $numberOfUnits . ( $micro ? '' : ' ' ) . $period . (($numberOfUnits > 1 && !$micro) ? 's' : '');
    }
}

function view_app_link($app_id){
    return '/'.view_memory(6287, $app_id, 'm__handle');
}

function view_memory($following, $follower, $filed = 'm__message'){
    $CI =& get_instance();
    $memory_tree = $CI->config->item('e___'.$following);
    return $memory_tree[$follower][$filed];
}


function view_cache($following, $e__id, $micro_status = true, $data_placement = 'top', $i__id = 0)
{

    /*
     *
     * UI for Platform Cache sources
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item('e___'.$following);
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
        return '<span class="'.( $micro_status ? 'cache_micro_'.$following.'_'.$i__id : '' ).'" ' . ( $micro_status && !is_null($data_placement) ? ' title="' . ($micro_status ? $cache['m__title'] : '') . (strlen($cache['m__message']) > 0 ? ($micro_status ? ': ' : '') . $cache['m__message'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache['m__cover'] . ' ' . ($micro_status ? '' : $cache['m__title']) . '</span>';
    }
}




function view_card($href, $is_current, $x__type, $o__privacy, $o__type, $o__title, $x__message = null){
    $CI =& get_instance();
    $e___4593 = $CI->config->item('e___4593');
    $e___6177 = $CI->config->item('e___6177');
    return '<a href="'.( $is_current ? 'javascript:alert(\'You are here already!\');' : $href ).'" class="dropdown-item main__title '.( $is_current ? ' active ' : '' ).'">'.
        ( in_array($x__type, $CI->config->item('n___32172')) ? '<span class="icon-block-xxs">'.$e___4593[$x__type]['m__cover'].'</span>' : '' ).
        ( in_array($o__privacy, $CI->config->item('n___32172')) ? '<span class="icon-block-xxs">'.$e___6177[$o__privacy]['m__cover'].'</span>' : '' ).
        ( strlen($o__type) ? '<span class="icon-block-xxs">'.$o__type.'</span>' : '&nbsp;' ). //Type or Cover
        $o__title.
        ( strlen($x__message) && superpower_unlocked(12701) ? '<div class="message2">'.strip_tags($x__message).'</div>' : '' ).
        '</a>';
}

function view_more($href, $is_current, $x__type, $o__privacy, $o__type, $o__title, $x__message = null){
    return '<a href="'.( $is_current ? 'javascript:alert(\'You are here already!\');' : $href ).'" class="dropdown-item main__title '.( $is_current ? ' active ' : '' ).'">'.
        ( $x__type ? '<span class="icon-block-xxs">'.$x__type.'</span>' : '' ).
        ( $o__privacy ? '<span class="icon-block-xxs">'.$o__privacy.'</span>' : '' ).
        ( strlen($o__type) ? '<span class="icon-block-xxs">'.$o__type.'</span>' : '&nbsp;' ). //Type or Cover
        $o__title.
        ( strlen($x__message) && superpower_unlocked(12701) ? '<div class="message2">'.strip_tags($x__message).'</div>' : '' ).
        '</a>';
}




function view_body_e($x__type, $counter, $e__id){

    $CI =& get_instance();
    $limit = view_memory(6404,11064);
    $member_e = superpower_unlocked();
    $list_results = view_e_covers($x__type, $e__id, 1);
    $focus_e = ($e__id==$member_e['e__id'] ? $member_e : false);
    $es = $CI->E_model->fetch(array(
        'e__id' => $e__id,
    ));
    if(!count($es)){
        return false;
    }
    $ui = '';


    if(in_array($x__type, $CI->config->item('n___42261'))){

        //Ideas:
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $i){
            $ui .= view_card_i($x__type, 0, null, $i, $focus_e);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___11028'))){

        //Sources:
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $e) {
            $ui .= view_card_e($x__type, $e, null);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___12144'))){

        //Discoveries:
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach ($list_results as $i) {
            $ui .= view_card_i($x__type, 0, null, $i, $focus_e);
        }
        $ui .= '</div>';

    }

    return $ui;

}

function view_google_tag($google_analytics_code){
    return '<script async src="https://www.googletagmanager.com/gtag/js?id='.$google_analytics_code.'"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \''.$google_analytics_code.'\');
</script>';
}

function view_body_i($x__type, $counter, $i__id){

    $CI =& get_instance();
    $list_results = view_i_covers($x__type, $i__id, 1);
    $ui = '';
    $is = $CI->I_model->fetch(array(
        'i__id' => $i__id,
    ));
    if(!count($is)){
        return false;
    }

    if($x__type==11019){

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $previous_i) {
            $ui .= view_card_i(11019, 0, null, $previous_i);
        }
        $ui .= '</div>';

    } elseif($x__type==12273 || $x__type==13542){

        //IDEAS
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $next_i) {
            $ui .= view_card_i($x__type, 0, $is[0], $next_i);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___42265'))){

        //IDEA Link Groups
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $next_i) {
            $ui .= view_card_i($x__type, 0, $is[0], $next_i);
        }
        $ui .= '</div>';

    } elseif($x__type==6255 || in_array($x__type, $CI->config->item('n___42284'))) {

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $item){
            $ui .= view_card_e(6255, $item);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___42261'))){

        //Sources
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $e_ref){
            $ui .= view_card_e($x__type, $e_ref, null);
        }
        $ui .= '</div>';

    }

    return $ui;

}

function view_sign($i, $previous_response = null){

    $CI =& get_instance();
    $member_e = superpower_unlocked();
    $e___4737 = $CI->config->item('e___4737'); //Idea Status
    $u_names = array();

    if($member_e){
        $u_names = $CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__down' => $member_e['e__id'],
            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__up' => 30198, //Full Legal Name
        ));
    }

    //Sign Agreement
    $message_ui = '';
    $message_ui .= '<h3 style="margin-top: 34px;">' . $e___4737[$i['i__type']]['m__title'] . '</h3>';
    if(strlen($e___4737[$i['i__type']]['m__message'])){
        $message_ui .= '<p>' . $e___4737[$i['i__type']]['m__message'] . ':</p>';
    }

    $message_ui .= '<input type="text" class="border greybg custom_ui_14506_34281 main__title itemsetting" value="'.( count($u_names) && strlen($u_names[0]['x__message']) ? $u_names[0]['x__message'] : $previous_response ).'" placeholder="" id="x_write" name="x_write" style="width:289px !important; font-size: 2.1em !important;" />';

    //Signature agreement:
    $message_ui .= '<div class="form-check">
  <input class="form-check-input" type="checkbox" value="1" id="DigitalSignAgreement" name="DigitalSignAgreement">
  <label class="form-check-label" for="DigitalSignAgreement">
    I agree to be legally bound by this document & our <a href="'.view_app_link(14373).'" target="_blank"><u>Terms of Service</u></a>.
  </label>
</div><br />';

    return $message_ui;

}

function view_e_covers($x__type, $e__id, $page_num = 0, $append_card_icon = true){

    /*
     *
     * Loads Source
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);
    $privacy_privacy = ( superpower_unlocked(12703) ? 'n___7358' /* ACTIVE */ : 'n___7357' /* PUBLIC/OWNER */  );

    if($x__type==12274 || $x__type==11029){

        //DOWN
        $order_columns = array();
        $order_columns['x__type'] = 'DESC';
        $order_columns['x__weight'] = 'ASC';
        $order_columns['e__title'] = 'ASC';

        $joins_objects = array('x__down');
        $query_filters = array(
            'x__up' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $CI->config->item($privacy_privacy)) . ')' => null,
        );

    } elseif(in_array($x__type, $CI->config->item('n___42276'))){

        //DOWN
        $order_columns = array('x__time' => 'DESC');
        $joins_objects = array('x__up');
        $query_filters = array(
            'x__down' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $CI->config->item($privacy_privacy)) . ')' => null,
        );

    } elseif($x__type==11030){

        //UP
        $order_columns = array('x__time' => 'DESC');
        $joins_objects = array('x__up');
        $query_filters = array(
            'x__down' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'e__privacy IN (' . join(',', $CI->config->item($privacy_privacy)) . ')' => null,
        );

    } elseif(in_array($x__type, $CI->config->item('n___42261'))){

        //IDEAS
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__up' => $e__id,
        );

        $joins_objects = array('x__right');

        $order_columns = array();
        if($x__type==42256){
            $order_columns['x__type = \'34513\' DESC'] = null;
        }
        $order_columns['x__weight'] = 'ASC';
        $order_columns['x__time'] = 'DESC';

    } elseif(in_array($x__type, $CI->config->item('n___12144'))){

        //Discoveries

        //Determine Sort:
        $order_columns = array();
        /*
        foreach($CI->config->item('e___6255') as $x__sort_id => $sort) {
            $order_columns['x__type = \''.$x__sort_id.'\' DESC'] = null;
        }
        */
        $order_columns['x__id'] = 'DESC';

        //DISCOVERIES
        $joins_objects = array('x__left');
        $query_filters = array(
            'x__creator' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //DISCOVERY GROUP
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
        );

    } else {

        return null;

    }


    //Return Results:
    if($page_num > 0){

        $limit = view_memory(6404,11064);
        $query = $CI->X_model->fetch($query_filters, $joins_objects, $limit, ($page_num-1)*$limit, $order_columns);
        return $query;

    } else {

        $e___11035 = $CI->config->item('e___11035');
        $query = $CI->X_model->fetch($query_filters, $joins_objects, 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">'.view_number($count_query).'<span>';
        $title_desc = number_format($count_query, 0).' '.$e___11035[$x__type]['m__title'];

        if($append_card_icon){

            if(!$count_query){
                return null;
            }

            $card_icon = '<span class="icon-block-xxs">'.$e___11035[$x__type]['m__cover'].'</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding main__title load_e_covers button_of_'.$e__id.'_'.$x__type.'" id="card_e_group_'.$x__type.'_'.$e__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_x__type="'.$x__type.'" load_e__id="'.$e__id.'" load_counter="'.$count_query.'" load_first_segment="'.$first_segment.'"><span title="'.$title_desc.'" data-toggle="tooltip" data-placement="top">'.$card_icon.$visual_counter.'</span></button>';
            $ui .= '<div class="dropdown-menu dropdown_'.$x__type.' coins_e_'.$e__id.'_'.$x__type.'" aria-labelledby="card_e_group_'.$x__type.'_'.$e__id.'">';
                //Menu To be loaded dynamically via AJAX
            $ui .= '</div>';
            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }
    }

}


function view_i_covers($x__type, $i__id, $page_num = 0, $append_card_icon = true){

    /*
     *
     * Loads Idea
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if(in_array($x__type, $CI->config->item('n___42261'))){

        //SOURCES
        $joins_objects = array('x__up');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
            'x__right' => $i__id,
        );

        $order_columns = array();
        if($x__type==42256){
            $order_columns['x__type = \'34513\' DESC'] = null;
        }
        $order_columns['x__weight'] = 'ASC';
        $order_columns['x__time'] = 'DESC';

    } elseif($x__type==11019) {

        //IDEAS PREVIOUS
        $order_columns = array('x__id' => 'DESC');
        $joins_objects = array('x__left');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__right' => $i__id,
        );

    } elseif(in_array($x__type, $CI->config->item('n___42265'))){

        //IDEAS NEXT
        $order_columns = array('x__weight' => 'ASC');
        $joins_objects = array('x__right');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
            'x__left' => $i__id,
        );

    } elseif($x__type==12273 || $x__type==13542){

        //IDEAS NEXT
        $order_columns = array('x__weight' => 'ASC');
        $joins_objects = array('x__right');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___4486')) . ')' => null, //IDEA LINKS
            'x__left' => $i__id,
        );

    } elseif(in_array($x__type, $CI->config->item('n___12144'))){

        //DISCOVERIES
        $order_columns = array('x__id' => 'DESC');
        $joins_objects = array('x__creator');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //DISCOVERIES
            'x__left' => $i__id,
        );
        if(isset($_GET['focus__e'])){
            $query_filters['x__creator'] = intval($_GET['focus__e']);
        }

    } else {

        return null;

    }


    //Return Results:
    if($page_num > 0){

        $limit = view_memory(6404,11064);
        return $CI->X_model->fetch($query_filters, $joins_objects, $limit, ($page_num-1)*$limit, $order_columns);

    } else {

        $e___11035 = $CI->config->item('e___11035'); //COINS
        $query = $CI->X_model->fetch($query_filters, $joins_objects, 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">'.view_number($count_query).'<span>';
        $title_desc = number_format($count_query, 0).( isset($e___11035[$x__type]['m__title']) ? ' '.$e___11035[$x__type]['m__title'] : '' );

        if($append_card_icon){

            if(!$count_query){
                return null;
            }

            $card_icon = '<span class="icon-block-xxs">'.$e___11035[$x__type]['m__cover'].'</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding main__title load_i_covers button_of_'.$i__id.'_'.$x__type.'" id="card_group_i_'.$x__type.'_'.$i__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_x__type="'.$x__type.'" load_i__id="'.$i__id.'" load_counter="'.$count_query.'" load_first_segment="'.$first_segment.'"><span title="'.$title_desc.'" data-toggle="tooltip" data-placement="top">'.$card_icon.$visual_counter.'</span></button>';

            //Menu To be loaded dynamically via AJAX:
            $ui .= '<div class="dropdown-menu dropdown_'.$x__type.' coins_i_'.$i__id.'_'.$x__type.'" aria-labelledby="card_group_i_'.$x__type.'_'.$i__id.'"></div>';

            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }

    }

}

function view_radio_e($focus_id, $down_e__id = 0, $right_i__id = 0){

    /*
     * Print UI for
     * */

    $CI =& get_instance();
    $single_select = in_array($focus_id, $CI->config->item('n___33331'));
    $multi_select = in_array($focus_id, $CI->config->item('n___33332'));

    if(!is_array($CI->config->item('n___'.$focus_id)) || !count($CI->config->item('n___'.$focus_id))){
        //Main item must be in memory:
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view_radio_e() @'.$focus_id.' missing in Application Cache',
            'x__up' => $focus_id,
            'x__down' => $down_e__id,
            'x__right' => $right_i__id,
        ));
        return false;
    } elseif(!$single_select && !$multi_select){
        //Must be either:
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view_radio_e() @'.$focus_id.' not in single select @33331 or multi select 33332',
            'x__up' => $focus_id,
            'x__down' => $down_e__id,
            'x__right' => $right_i__id,
        ));
        return false;
    } elseif(($down_e__id && $right_i__id) || (!$down_e__id && !$right_i__id)){
        //Must be either:
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view_radio_e() Required either a Source OR Idea ID',
            'x__up' => $focus_id,
            'x__down' => $down_e__id,
            'x__right' => $right_i__id,
        ));
        return false;
    }

    $count = 0;
    $already_selected = array();
    $ui = '<div class="list-group list-radio-select radio-'.$focus_id.'">';

    if($down_e__id > 0){

        //Source Focus:
        foreach($CI->X_model->fetch(array(
            'x__up IN (' . join(',', $CI->config->item('n___'.$focus_id)) . ')' => null, //All possible answers
            'x__down' => $down_e__id,
            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        )) as $sel){
            array_push($already_selected, $sel['x__up']);
        }

        if(!count($already_selected) && $single_select && superpower_unlocked()){
            //FIND DEFAULT if set in session of this user:
            foreach($CI->config->item('e___'.$focus_id) as $e__id2 => $m2){
                $var_id = @$CI->session->userdata('session_custom_ui_'.$focus_id);
                if($var_id==$e__id2){
                    $already_selected = array($e__id2);
                    break;
                }
            }
        }

    } else {

        //Idea focus:
        foreach($CI->X_model->fetch(array(
            'x__up IN (' . join(',', $CI->config->item('n___'.$focus_id)) . ')' => null, //All possible answers
            'x__right' => $right_i__id,
            'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        )) as $sel){
            array_push($already_selected, $sel['x__up']);
        }

    }


    foreach($CI->config->item('e___'.$focus_id) as $e__id => $m) {
        $ui .= '<span class=""><a href="javascript:void(0);" onclick="e_radio('.$focus_id.','.$e__id.','.( $multi_select ? 1 : 0 ).','.$down_e__id.','.$right_i__id.')" class="list-group-item main__title custom_ui_'.$focus_id.'_'.$e__id.' itemsetting item-'.$e__id.' '.( in_array($e__id, $already_selected) ? ' active ' : '' ). '">'.( strlen($m['m__cover']) ? '<span class="icon-block change-results">'.$m['m__cover'].'</span>' : '' ).$m['m__title'].'</a></span>';
        $count++;
    }

    $ui .= '</div>';

    return $ui;
}


function view_i_list($x__type, $top_i__hashtag, $i, $next_is, $member_e, $body_prepend = null){

    //If no list just return the next step:
    $CI =& get_instance();
    if(!count($next_is)){
        return false;
    } elseif(!in_array($x__type, $CI->config->item('n___13369'))){
        die('@'.$x__type.' NOT in @13369');
        return false;
    }

    $e___13369 = $CI->config->item('e___13369'); //IDEA LISTS

    //Build Body UI:
    $body = '<div class="row justify-content">';
    foreach($next_is as $key => $next_i){
        $body .= view_card_i($x__type, $top_i__hashtag, $i, $next_i, $member_e);
    }
    $body .= '</div>';

    return view_headline($x__type, count($next_is), $e___13369[$x__type], $body_prepend.$body, isset($_GET['open']));

}


function view_shuffle_message($e__id){
    $CI =& get_instance();
    $e___12687 = $CI->config->item('e___12687');
    $line_messages = explode("\n", $e___12687[$e__id]['m__message']);
    return $line_messages[rand(0, (count($line_messages) - 1))];
}

function view_unauthorized_message($superpower_e__id = 0){

    if(!superpower_unlocked()){

        return 'Sign-in to continue';

    } elseif($superpower_e__id && !superpower_unlocked($superpower_e__id)){

        $CI =& get_instance();
        $e___10957 = $CI->config->item('e___10957');
        return 'Error: You are missing access to '.$e___10957[$superpower_e__id]['m__title'];

    } else {

        return null;

    }

}

function view_time_hours($total_seconds, $hide_hour = false){

    $total_seconds = intval($total_seconds);
    //Turns seconds into HH:MM:SS
    $hours = floor($total_seconds/3600);
    $minutes = floor(fmod($total_seconds, 3600)/60);
    $seconds = fmod($total_seconds, 60);

    return ( $hide_hour && !$hours ? '' : str_pad($hours, 2, "0", STR_PAD_LEFT).':' ).str_pad($minutes, 2, "0", STR_PAD_LEFT).':'.str_pad($seconds, 2, "0", STR_PAD_LEFT);
}

function view__focus__e($e){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035');
    return '<div class="alert alert-info no-margin" style="margin-bottom: 10px !important;" title="'.$e___11035[13670]['m__title'].'"><span class="icon-block">'.$e___11035[13670]['m__cover'] . '</span><span class="icon-block-xs">' . view_cover($e['e__handle'], true) . '</span><a href="/@'.$e['e__handle'].'">' . $e['e__title'].'</a>&nbsp;&nbsp;&nbsp;<a href="/'.$CI->uri->segment(1).'" title="'.$e___11035[13671]['m__title'].'">'.$e___11035[13671]['m__cover'].'</a></div>';
}



function view_card_x_select($i, $x__creator, $previously_selected){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    $member_e = superpower_unlocked();
    $spots_remaining = i_spots_remaining($i['i__id']);


    $href = 'href="javascript:void(0);"'.( $spots_remaining==0 && !$previously_selected ? ' onclick="alert(\'This Option is Not Available\')" ' : ' onclick="select_answer(' . $i['i__id'] . ')"' );

    $ui  = '<div class="card_cover coin-6255 col-6 col-md-4 no-padding">';
    $ui .= '<div class="cover-wrapper">';
    $ui .= '<table class="card_covers"></table>'; //For UI height adjustment
    $ui .= '<a '.$href.' selection_i__id="' . $i['i__id'] . '" class="answer-item black-background-obs cover-link x_select_' . $i['i__id'] . ($previously_selected ? ' isSelected ' : '') . ( $spots_remaining==0 ? ' greyout ' : '' ).'">';

    $ui .= '</a>';
    $ui .= '</div>';

    $ui .= '<div class="cover-content"><div class="inner-content">';

    $ui .= '<div class="cover-text">';
    $ui .= '<a '.$href.' class="hideIfEmpty doblock">';
    $ui .= $i['i__cache'];
    $ui .= '</a>';
    $ui .= '</div>';

    $ui .= '</div></div>';
    $ui .= '</div>';

    return $ui;

}

function view_e__hash($string){
    return substr(md5($string.view_memory(6404,30863)), 0, 10);
}



function view_i_title($i, $string_only = false){

    //Break down by lines:
    foreach(explode("\n", $i['i__message']) as $line){
        if(strlen($line) && !filter_var($line, FILTER_VALIDATE_URL)){
            return ( $string_only ? $line : '<span class="main__title">'.$line.'</span>' );
        }
    }

    //If not yet found we need to use other data to generate title:
    return ( isset($i['i__hashtag']) && strlen($i['i__hashtag']) ? $i['i__hashtag'] : ( isset($i['i__id']) && intval($i['i__id']) ? 'Idea Number '.$i['i__id'] : 'Idea'.rand(100000000000,999999999999) ) );

}

function view_valid_handle_e($string, $check_db = false){
    $CI =& get_instance();
    return ( substr($string, 0, 1)=='@' && ctype_alnum(substr($string, 1)) && (!$check_db || count($CI->E_model->fetch(array(
            'LOWER(e__handle)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false );
}

function view_valid_handle_i($string, $check_db = false){
    $CI =& get_instance();
    return ( substr($string, 0, 1)=='#' && ctype_alnum(substr($string, 1)) && ( !$check_db || count($CI->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false );
}

function view_valid_handle_reverse_i($string, $check_db = false){
    $CI =& get_instance();
    return ( substr($string, 0, 2)=='!#' && ctype_alnum(substr($string, 2)) && ( !$check_db || count($CI->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower(substr($string, 2)),
        )))) ? substr($string, 2) : false );
}


function validate_i__message($str){

    //Validate:
    $error = null;
    if(!strlen(trim($str))){

        $error = 'Missing idea title';

    } elseif (strlen($str) > view_memory(6404,4736)) {

        $error = 'Must be '.view_memory(6404,4736).' characters or less';

    }

    if($error){
        return array(
            'status' => 0,
            'message' => $error,
        );
    } else {
        return array(
            'status' => 1,
            'message' => 'All Good',
        );
    }

}

function view_i_links($i, $replace_links = true, $is_focus = false){
    $show_extra_list = ( !$is_focus && substr_count($i['i__cache'], 'show_more_line') ? '' : view_list_e($i, 0, !$replace_links) );
    return ( $replace_links ? str_replace('spanaa','a',$i['i__cache']) : $i['i__cache'] ).$show_extra_list;
}

function idea_author($i__id){
    $CI =& get_instance();
    foreach($CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
        'x__right' => $i__id,
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array(), 0, 0, array('x__type = \'4250\' DESC' => null)) as $x){
        return $x['x__up'];
    }
    $member_e = superpower_unlocked();
    return ( $member_e ? $member_e['e__id'] : 14068 );
}

function idea_creation_time($i__id){
    $CI =& get_instance();
    foreach($CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
        'x__right' => $i__id,
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array(), 0, 0, array('x__type = \'4250\' DESC' => null)) as $x){
        return $x['x__time'];
    }
    //Now:
    return date("Y-m-d H:i:s");
}



function view_sync_links($str, $return_array = false, $save_i__id = 0) {

    /*
     *
     * Examples:
     *
     * Audio URL:      https://s3foundation.s3-us-west-2.amazonaws.com/672b41ff20fece4b3e7ae2cf4b58389f.mp3
     * Video URL:      https://s3foundation.s3-us-west-2.amazonaws.com/8c5a1cc4e8558f422a4003d126502db9.mp4
     * Image URL:      https://s3foundation.s3-us-west-2.amazonaws.com/d673c17d7164817025a000416da3be3f.png
     * Document URL:   https://s3foundation.s3-us-west-2.amazonaws.com/611695da5d0d199e2d95dd2eabe484cf.zip
     *
     * */

    $extension_detect = array(
        4258 => array('mp4','m4v','m4p','avi','mov','flv','f4v','f4p','f4a','f4b','wmv','webm','mkv','vob','ogv','ogg','3gp','mpg','mpeg','m2v'), //Video URL
        4259 => array('pcm','wav','aiff','mp3','aac','ogg','wma','flac','alac','m4a','m4b','m4p'), //Audio URL
        4260 => array('jpeg','jpg','png','gif','tiff','bmp','img','svg','ico','webp','heic','avif'), //Image URL
        42185 => array('pdf','doc','docx','odt','xls','xlsx','ods','ppt','pptx','txt','zip','rar'), //Document URL
    );

    //Display Images, Audio, Video & PDF Files:
    //Analyze the message to find referencing URLs and Members in the message text:
    $CI =& get_instance();

    //All the possible reference types that can be found:
    $i__references = array(
        4258 => array(), //Video URL
        4259 => array(), //Audio URL
        4260 => array(), //Image URL
        42185 => array(), //Document URL

        4256 => array(), //Generic URL

        31834 => array(), //Idea Reference
        42337 => array(), //Idea Contradiction
        31835 => array(), //Source Mention
    );


    $ui_template = array(
        4258 => '<video class="play_video" onclick="this.play()" controls poster="https://s3foundation.s3-us-west-2.amazonaws.com/9988e7bc95f25002b40c2a376cc94806.png"><source src="%s" type="video/mp4"></video><!-- %s -->',
        4259 => '<audio controls src="%s"></audio><!-- %s -->',
        4260 => '<img src="%s" class="content-image" /><!-- %s -->',
        4256 => '<spanaa href="%s" target="_blank" class="ignore-click"><span class="url_truncate">%s</span></spanaa>',
        42185 => '<spanaa href="%s" target="_blank" class="ignore-click">Download</spanaa><!-- %s -->',
        31834 => '<spanaa href="/%s">%s</spanaa>',
        42337 => '<spanaa href="/%s">%s</spanaa>',
        31835 => '<spanaa href="/@%s">%s</spanaa>',
    );

    $replace_from = array();
    $replace_to = array();

    //See what we can find:
    $word_count = 0;
    $word_limit = 89;
    $link_words = 13; //The number of words a link is counted as...
    $media_words = 21; //The number of words a photo/video file is counted as...

    $i__cache = '<div class="i_cache cache_frame_'.$save_i__id.'">';
    foreach(explode("\n", $str) as $line_index => $line) {

        $single_media_line = false;
        $i__cache_line = '';
        $reference_type_last = 0;
        $lines = explode(' ', $line);

        foreach($lines as $word_index => $word) { //'/\s+/'

            $reference_type = 0;
            $i__cache_line .= ( $word_index>0 ? ' ' : '' );

            if (filter_var($word, FILTER_VALIDATE_URL)) {

                //Determine URL type:
                $reference_type = 4256; //Generic URL, unless we can detect one of the specific types below...
                $fileInfo = pathinfo($word);
                foreach($extension_detect as $extension_type => $extension_ids) {
                    if(isset($fileInfo['extension']) && in_array($fileInfo['extension'], $extension_ids)){
                        $reference_type = $extension_type;
                        break;
                    }
                }

                array_push($i__references[$reference_type], $word);
                $i__cache_line .=  @sprintf($ui_template[$reference_type], $word, $word);
                $word_count += ( in_array($reference_type, $CI->config->item('n___42294')) ? $media_words  : $link_words );

            } elseif (view_valid_handle_e($word, true)) {

                $reference_type = 31835;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .= @sprintf($ui_template[$reference_type], substr($word, 1), $word);
                $word_count++;

            } elseif (view_valid_handle_reverse_i($word, true)) {

                $reference_type = 42337;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .= @sprintf($ui_template[$reference_type], substr($word, 2), $word);
                $word_count++;

            } elseif (view_valid_handle_i($word, true)) {

                $reference_type = 31834;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .= @sprintf($ui_template[$reference_type], substr($word, 1), $word);
                $word_count++;

            } else {

                //This word is not referencing anything!
                $i__cache_line .= $word;
                $word_count++;

            }

            $reference_type_last = $reference_type;

        }

        $i__cache .= '<div class="line '.(!$line_index ? 'first_line' : '').( count($lines)<=1 && $reference_type_last>0 ? 'media_line' : '').( $save_i__id && $word_count>=$word_limit ? ' hidden ' : '' ).'">';
        $i__cache .= $i__cache_line;
        $i__cache .= '</div>';

    }
    if($save_i__id && $word_count>=$word_limit){
        //Add show more button:
        $i__cache .= '<div class="line show_more_line"><spanaa href="javascript:void(0);">Show more</spanaa></div>';
    }
    $i__cache .= '</div>';


    $sync_stats = array(
        'old_links_removed' => 0,
        'old_links_kept' => 0,
        'new_links_added' => 0,
    );
    if(intval($save_i__id) > 0){

        //Save Found references to remove the ones who exist in DB:
        $references_add_to_db = $i__references;
        $member_e = superpower_unlocked();
        foreach($CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___4736')) . ')' => null, //Idea Message Links 3x
            'x__right' => $save_i__id,
        )) as $x){

            //Is this still valid?
            if(!in_array($x['x__message'], $i__references[$x['x__type']])){

                //Not valid, must be removed:
                $CI->X_model->update($x['x__id'], array(
                    'x__privacy' => 6173, //Transaction Removed
                ), $member_e['e__id'], 10673 /* Member Transaction Unpublished */);

                $sync_stats['old_links_removed']++;

            } else {

                //Still valid, add to index:
                $sync_stats['old_links_kept']++;

                //Remove from add new to DB list (Since we dont need to add this):
                foreach($references_add_to_db[$x['x__type']] as $key=>$val){
                    if($val==$x['x__message']){
                        unset($references_add_to_db[$x['x__type']][$key]);
                        break;
                    }
                }
            }
        }

        //Add whatever was not found to DB:
        foreach($references_add_to_db as $db_type => $db_vals){
            foreach($db_vals as $db_val){

                //Additional source/idea reference?
                $x__left = 0;
                $x__up = 0;
                $x__message = '';

                if($db_type==31834){
                    $x__type = 31834;
                    foreach($CI->I_model->fetch(array(
                        'LOWER(i__hashtag)' => strtolower(substr($db_val, 1)),
                    )) as $target){
                        $x__left = $target['i__id'];
                    }
                } elseif($db_type==42337){
                    $x__type = 42337;
                    foreach($CI->I_model->fetch(array(
                        'LOWER(i__hashtag)' => strtolower(substr($db_val, 2)),
                    )) as $target){
                        $x__left = $target['i__id'];
                    }
                } elseif($db_type==31835) {
                    $x__type = 31835;
                    foreach($CI->E_model->fetch(array(
                        'LOWER(e__handle)' => strtolower(substr($db_val, 1)),
                    )) as $target){
                        $str = str_replace('@'.$target['e__id'],'@'.$target['e__handle'], $str); //TODO Remove!
                        $x__up = $target['e__id'];
                    }
                } else {
                    $x__type = $db_type; //Message URLs
                    $x__up = idea_author($save_i__id);
                    $x__message = $db_val;
                }

                $CI->X_model->create(array(
                    'x__time' => idea_creation_time($save_i__id),
                    'x__type' => $x__type,
                    'x__creator' => $member_e['e__id'],
                    'x__message' => $x__message,
                    'x__right' => $save_i__id,
                    'x__left' => $x__left,
                    'x__up' => $x__up,
                ));

                $sync_stats['new_links_added']++;

            }
        }

        //Save/update message & its cache:
        $CI->I_model->update($save_i__id, array(
            'i__message' => trim($str),
            'i__cache' => $i__cache,
        ), true, $member_e['e__id']);

    }

    if($return_array){
        return array(
            'i__references' => $i__references,
            'i__cache' => $i__cache,
            'sync_stats' => $sync_stats,
            'replace_from' => $replace_from,
            'replace_to' => $replace_to,
        );
    } else {
        //Return formatted message:
        return $i__cache;
    }

}

function view_media($media_url, $link){
    $view_links = view_sync_links($media_url, true);
    return '<div class="card_cover card_i_cover contrast_bg col-sm-4 col-6 no-padding"><a href="'.$link.'"><div class="square">'.$view_links['i__cache'].'</div></a></div>';
}

function view_card_i($x__type, $top_i__hashtag = 0, $previous_i = null, $i, $focus_e = false){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    if(!in_array($x__type, $CI->config->item('n___13369'))){
        return 'Invalid x__type i '.$x__type;
    }

    $x__id = ( isset($i['x__id']) && $i['x__id']>0 ? $i['x__id'] : 0 );

    if($x__type==42294 && $x__id && filter_var($i['x__message'], FILTER_VALIDATE_URL)){
        return view_media($i['x__message'], '/~'.$i['i__hashtag']);
    }

    $e___13369 = $CI->config->item('e___13369'); //IDEA LIST
    $cache_app = in_array($x__type, $CI->config->item('n___14599'));
    $access_locked = in_array($i['i__privacy'], $CI->config->item('n___32145')); //Locked Dropdown

    $member_e = superpower_unlocked();
    $write_privacy_i = ( $cache_app || $access_locked ? false : write_privacy_i($i['i__hashtag']) );
    $user_input = $focus_e;

    $primary_icon = in_array($x__type, $CI->config->item('n___14378')); //PRIMARY ICON
    $discovery_mode = $top_i__hashtag || in_array($x__type, $CI->config->item('n___14378')); //DISCOVERY MODE
    $focus_card = in_array($x__type, $CI->config->item('n___12149')); //NODE COIN
    $step_by_step = in_array($x__type, $CI->config->item('n___14742'));
    $has_self = $member_e && $focus_e && $member_e['e__id']==$focus_e['e__id'];
    $focus_e__handle = ( view_valid_handle_e($CI->uri->segment(1)) ? substr($CI->uri->segment(1), 1) : false );
    $focus_source = ( $x__id && isset($i['x__creator']) ? $i['x__creator'] : ( $focus_e__handle ? $focus_e__handle : ( $focus_e && $focus_e['e__id'] ? $focus_e['e__id'] : ( $member_e && $member_e['e__id'] ? $member_e['e__id'] : 0 ) ) ) );
    $link_creator = isset($i['x__creator']) && $i['x__creator']==$member_e['e__id'];

    if(!$focus_e){
        $focus_e = $member_e;
    }

    $load_completion = in_array($x__type, $CI->config->item('n___14501')) && $top_i__hashtag && $focus_e && $discovery_mode;

    $followings_is_or = ( $discovery_mode && $previous_i && in_array($previous_i['i__type'], $CI->config->item('n___7712')) );
    $has_sortable = $x__id > 0 && !$focus_card && $write_privacy_i && in_array($x__type, $CI->config->item('n___4603')) && !in_array($i['x__type'], $CI->config->item('n___42348'));

    if($discovery_mode || $cache_app) {
        if($link_creator && $top_i__hashtag){
            $href = '/'.$top_i__hashtag.'/'.$i['i__hashtag'];
        } else {
            $href = '/'.$i['i__hashtag'];
        }
    } else {
        $href = '/~'.$i['i__hashtag'] . ( isset($_GET['focus__e']) ? '?focus__e='.intval($_GET['focus__e']) : '' );
    }

    $has_discovered = false;

    if(!$cache_app && $focus_source){
        $discoveries = $CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__creator' => $focus_source,
            'x__left' => $i['i__id'],
        ));
        $has_discovered = count($discoveries);
    }
    if($has_discovered && $discovery_mode){
        $i = array_merge($i, $discoveries[0]);
    }



    //Top action menu:
    $ui = '<div i__id="'.$i['i__id'].'" i__hashtag="'.$i['i__hashtag'].'" x__id="'.$x__id.'" class="card_cover card_i_cover contrast_bg '.( $focus_card ? ' focus-cover slim_flat coll-md-8 coll-sm-10 col-12
     ' : ' edge-cover ' . ( $discovery_mode ? ' col-12 ' : ' coll-md-4 coll-6 col-12 ' ) ).( $cache_app ? ' is-cache ' : '' ).( $followings_is_or ? ' doborderless ' : '' ).' no-padding '.( $discovery_mode ? ' coin-6255 card_click_x ' : ' coin-12273 card_click_i ' ).' coinface-12273 s__12273_'.$i['i__id'].' '.( $has_sortable ? ' sort_draggable ' : '' ).( $x__id ? ' cover_x_'.$x__id.' ' : '' ).'">';


    //Determine Link Group
    $link_type_id = 4593; //Transaction Type
    $link_type_ui = '';
    if($x__id){
        foreach($CI->config->item('e___31770') as $x__type1 => $m1){
            if(in_array($i['x__type'], $CI->config->item('n___'.$x__type1))){
                foreach($CI->X_model->fetch(array(
                    'x__id' => $x__id,
                ), array('x__creator')) as $linker){
                    $link_type_ui .= '<td><div class="'.( in_array($x__type1, $CI->config->item('n___32172')) || in_array($i['x__type'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                    $link_type_ui .= view_dropdown($x__type1, $i['x__type'], null, $write_privacy_i, false, $i['i__id'], $x__id);
                    $link_type_ui .= '</div></td>';
                }
                $link_type_id = $x__type1;
                break;
            }
        }
        if(!$link_type_ui){
            $link_type_ui .= '<td><div class="show-on-hover">';
            $link_type_ui .= view_dropdown(4593, $i['x__type'], null, false, false, $i['i__id'], $x__id);
            $link_type_ui .= '</div></td>';
        }
    }


    //Top Bar
    $top_bar_ui = '';
    $active_bars = 0;
    if(!$cache_app && !$discovery_mode){
        foreach($CI->config->item('e___31904') as $x__type_top_bar => $m_top_bar) {

            //Determine hover state:
            $always_see = in_array($x__type_top_bar, $CI->config->item('n___32172'));

            if($x__type_top_bar==31770 && $link_type_ui && (!$discovery_mode || $write_privacy_i)){

                //Links
                $active_bars++;
                $top_bar_ui .= $link_type_ui;

            } elseif($x__type_top_bar==4362 && isset($i['x__time']) && strtotime($i['x__time']) > 0){

                //Creation Time:
                $active_bars++;
                $top_bar_ui .= '<td><div class="show-on-hover grey created_time" title="'.date("Y-m-d H:i:s", strtotime($i['x__time'])).'">' . view_time_difference($i['x__time'], true) . '</div></td>';

            } elseif($x__type_top_bar==41037 && $write_privacy_i && !$focus_card){

                //Selector
                $active_bars++;
                $top_bar_ui .= '<td class="ignore-click toggle_i_checkbox" value="" id="selector_i_'.$i['i__id'].'" onclick="toggle_check('.$i['i__id'].')"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<input class="form-check-input " type="checkbox"  />';
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==4737){

                //Idea Type
                $active_bars++;
                $top_bar_ui .= '<td><div class="'.( $always_see || in_array($i['i__type'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= view_dropdown(4737, $i['i__type'], null, $write_privacy_i, false, $i['i__id']);
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==31004 && (!in_array($i['i__privacy'], $CI->config->item('n___31871')) || ($write_privacy_i && !in_array(31004, $CI->config->item('n___32145'))))){

                //Idea Access
                $active_bars++;
                $top_bar_ui .= '<td><div class="'.( $always_see || in_array($i['i__privacy'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= view_dropdown(31004, $i['i__privacy'], null, $write_privacy_i, false, $i['i__id']);
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==31911 && $write_privacy_i){

                //Idea Edit
                $active_bars++;
                $top_bar_ui .= '<td><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<a href="javascript:void(0);" onclick="editor_load_i('.$i['i__id'].','.$x__id.')">'.$m_top_bar['m__cover'].'</a>';
                $top_bar_ui .= '</div></td>';

                $active_bars++;
                $top_bar_ui .= '<td><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<a href="javascript:void(0);" onclick="editor_load_i(0,0,'.$i['i__id'].')">'.$m_top_bar['m__cover'].'</a>';
                $top_bar_ui .= '</div></td>';

                $active_bars++;
                $top_bar_ui .= '<td><div class="'.( $always_see || in_array($i['i__privacy'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= view_dropdown(42260, 0, null, $member_e, false, $i['i__id']);
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==13909 && $write_privacy_i && $has_sortable){

                //Sort Idea
                $active_bars++;
                $top_bar_ui .= '<td class="sort_i_frame hidden"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<span title="'.$m_top_bar['m__title'].'" class="sort_i_grab">'.$m_top_bar['m__cover'].'</span>';
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==14980 && !$cache_app && !$access_locked){

                //Drop Down
                $action_buttons = null;
                if(!$x__id){
                    $focus_dropdown = 11047; //Idea Dropdown
                } elseif($link_type_id==4486){ //Idea/Idea Links
                    $focus_dropdown = 14955; //Idea/Idea Dropdown
                } elseif($link_type_id==13550){ //Idea/Source Links
                    $focus_dropdown = 28787; //Idea/Source Dropdown
                } else {
                    //Discoveries
                    $focus_dropdown = 32069; //Idea/Discoveries Dropdown
                }

                if(is_array($CI->config->item('e___'.$focus_dropdown))){
                    foreach($CI->config->item('e___'.$focus_dropdown) as $e__id_dropdown => $m_dropdown) {

                        //Skip if missing superpower:
                        $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_dropdown['m__following']);
                        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                            continue;
                        }

                        $anchor = '<span class="icon-block">'.$m_dropdown['m__cover'].'</span>'.$m_dropdown['m__title'];

                        if($e__id_dropdown==12589 && $write_privacy_i){

                            //Mass Apply
                            $action_buttons .= '<a href="javascript:void(0);" onclick="mass_apply_preview(12589,'.$i['i__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==30795 && !$discovery_mode){

                            //Discover Idea
                            $action_buttons .= '<a href="/'.$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==33286 && $discovery_mode && $write_privacy_i){

                            //Ideation Mode
                            $action_buttons .= '<a href="/~'.$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==13007){

                            //Reset Alphabetic order
                            $action_buttons .= '<a href="javascript:void(0);" onclick="sort_alphabetical()" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==10673 && $x__id && !in_array($i['x__type'], $CI->config->item('n___31776')) && $write_privacy_i){

                            //Unlink
                            $action_buttons .= '<a href="javascript:void(0);" onclick="x_remove('.$x__id.', '.$x__type.',\''.$i['i__hashtag'].'\')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==30873 && $write_privacy_i){

                            //Clone Idea Tree:
                            $action_buttons .= '<a href="javascript:void(0);" onclick="i_copy('.$i['i__id'].', 1)" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==33292){

                            //Stats
                            $action_buttons .= '<a href="'.view_app_link(33292).'?i__hashtag='.$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==29771 && $write_privacy_i){

                            //Clone Single Idea:
                            $action_buttons .= '<a href="javascript:void(0);" onclick="i_copy('.$i['i__id'].', 0)" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==28636 && $write_privacy_i && $x__id){

                            //Transaction Details
                            $action_buttons .= '<a href="'.view_app_link(4341).'?x__id='.$x__id.'" class="dropdown-item main__title" target="_blank">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==6182 && $write_privacy_i){

                            //Delete Permanently
                            $action_buttons .= '<a href="javascript:void();" current-selected="'.$i['i__privacy'].'" onclick="update_dropdown(31004, 6182, '.$i['i__id'].', '.$x__id.', 0)" class="dropdown-item dropi_31004_'.$i['i__id'].'_'.$x__id.' main__title optiond_6182_'.$i['i__id'].'_'.$x__id.'">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==28637 && isset($i['x__type']) && superpower_unlocked(28727)){

                            //Paypal Details
                            $x__metadata = @unserialize($i['x__metadata']);
                            if(isset($x__metadata['txn_id'])){
                                $action_buttons .= '<a href="https://www.paypal.com/activity/payment/'.$x__metadata['txn_id'].'" class="dropdown-item main__title" target="_blank">'.$anchor.'</a>';
                            }

                        } elseif(in_array($e__id_dropdown, $CI->config->item('n___6287'))){

                            //Standard button
                            $action_buttons .= '<a href="'.view_app_link($e__id_dropdown).'?i__hashtag='.$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                        }
                    }
                }

                //Any items found?
                if($action_buttons && $focus_dropdown>0){
                    //Right Action Menu
                    $e___14980 = $CI->config->item('e___14980'); //Dropdowns
                    $active_bars++;
                    $top_bar_ui .= '<td><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                    $top_bar_ui .= '<div class="dropdown inline-block">';
                    $top_bar_ui .= '<button type="button" class="btn no-left-padding no-right-padding main__title" id="action_menu_i_'.$i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="'.$e___14980[$focus_dropdown]['m__title'].'">'.$e___14980[$focus_dropdown]['m__cover'].'</button>';
                    $top_bar_ui .= '<div class="dropdown-menu" aria-labelledby="action_menu_i_'.$i['i__id'].'">';
                    $top_bar_ui .= $action_buttons;
                    $top_bar_ui .= '</div>';
                    $top_bar_ui .= '</div>';
                    $top_bar_ui .= '</div></td>';

                }

            }
        }
    }

    //Top Bar:
    $ui .= '<table class="card_covers active_bars_'.$active_bars.'"><tr>';
    $ui .= $top_bar_ui;
    $ui .= '</tr></table>';



    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';
    $ui .= '<div class="cover-text">';


    //Show Link User:
    $ui .= '<div class="creator_headline_frame creator_headline_frame_'.$i['i__id'].'">';

    $link_user = 0;
    if(!$discovery_mode && $x__id && isset($i['x__creator'])){
        $link_user = $i['x__creator'];
        foreach($CI->E_model->fetch(array(
            'e__id' => $i['x__creator'],
            'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
        )) as $creator){
            $ui .= '<div class="creator_headline"><a href="/@'.$creator['e__handle'].'"><span class="icon-block icon-block-img">'.view_cover($creator['e__cover']).'</span><b>'.$creator['e__title'].'</b><span class="grey mini-font">@'.$creator['e__handle'].'</span></a><span class="grey mini-font" title="'.date("Y-m-d H:i:s", strtotime($i['x__time'])).' PST">'.view_time_difference($i['x__time'], true).'</span></div>';
        }
    }


    //Show Creator, if any, and if different from linker:
    foreach($CI->X_model->fetch(array(
        'x__type' => 4250,
        'x__up !=' => $link_user,
        'x__right' => $i['i__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    ), array('x__up')) as $creator){
        $ui .= '<div class="creator_headline"><a href="/@'.$creator['e__handle'].'"><span class="icon-block icon-block-img">'.view_cover($creator['e__cover']).'</span><b>'.$creator['e__title'].'</b><span class="grey mini-font">@'.$creator['e__handle'].'</span></a><span class="grey mini-font" title="'.date("Y-m-d H:i:s", strtotime($creator['x__time'])).' PST">'.view_time_difference($creator['x__time'], true).'</span></div>';
    }

    $ui .= '</div>';


    //Idea Message (Remaining)
    $ui .= ( $focus_card ? '<div' : '<a href="'.$href.'"' ).' class="handle_href_i_'.$i['i__id'].' ui_i__cache_' . $i['i__id'] . ( !$focus_card ? ' space-content ' : '' ) . '" show_cache_links="'.intval($focus_card).'">'.view_i_links($i, $focus_card, $focus_card).( $focus_card ? '</div>' : '</a>' );

    //Link Message, if Any:
    if($x__id){
        $ui .= '<div class="space-content"><div '.( ($write_privacy_i || $link_creator) ? ' onclick="editor_load_i('.$i['i__id'].','.$x__id.')" ' : '' ).' class="mini-font greybg hideIfEmpty ui_x__message_' . $x__id . '">'.$i['x__message'].'</div></div>';
    }



    //Raw Data:
    $ui .= '<div class="ui_i__message_' . $i['i__id'] . '
     hidden">'.$i['i__message'].'</div>';
    $ui .= '<div class="sub__handle space-content grey '.( $discovery_mode || !$focus_card ? ' hidden ' : '' ).'">#<span class="ui_i__hashtag_'.$i['i__id'].'">'.$i['i__hashtag'].'</span></div>';



    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';



    //Bottom Bar
    //&& (!$discovery_mode || superpower_unlocked(10939))
    if(!$cache_app && !$focus_card ){
        $active_bars = 0;
        $bottom_bar_ui = '';
        foreach($CI->config->item('e___31890') as $e__id_bottom_bar => $m_bottom_bar) {
            $coins_ui = view_i_covers($e__id_bottom_bar,  $i['i__id']);
            if(strlen($coins_ui)){
                $active_bars++;
                $bottom_bar_ui .= '<span class="hideIfEmpty '.( in_array($e__id_bottom_bar, $CI->config->item('n___32172')) ? '' : 'inline-on-hover' ).'">';
                $bottom_bar_ui .= $coins_ui;
                $bottom_bar_ui .= '</span>';
            }
        }

        if($bottom_bar_ui){
            $ui .= '<div class="card_covers">';
            $ui .= $bottom_bar_ui;
            $ui .= '</div>';
        }
    }

    //Bottom Bar
    /*
    if(!$cache_app && !$focus_card ){

        $bottom_bar_ui = '';
        foreach($CI->config->item('e___31890') as $e__id_bottom_bar => $m_bottom_bar) {


            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_bottom_bar['m__following']);
            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                continue; //Does not have permission
            }


            $coins_count = view_i_covers($e__id_bottom_bar, $i['i__id'], 0, false);


            $bottom_bar_ui .= '<span title="'.$m_bottom_bar['m__title'].'"><span class="icon-block">'.$m_bottom_bar['m__cover']. '</span>';
            //'.( in_array($e__id_bottom_bar, $CI->config->item('n___32172')) ? '' : 'inline-on-hover' ).'
            $bottom_bar_ui .= $coins_count;
            $bottom_bar_ui .= '</span>';
        }

        if($bottom_bar_ui){
            $ui .= '<div class="">'; //card_covers
            $ui .= $bottom_bar_ui;
            $ui .= '</div>';
        }

    }
    */


    $ui .= '</div>';


    return $ui;

}

function view_random_title(){
    $random_cover = random_cover(12279);
    $color = '';
    foreach(array(
                'golden' => 'zq12273',
                'blue' => 'zq12274',
                'red' => 'zq6255',
            ) as $key => $code){
        if(substr_count($random_cover,$code)){
            $color = ucwords($key).' ';
            break;
        }
    }
    return random_adjective().' '.$color.str_replace('Badger Honey','Honey Badger',str_replace('Black Widow','',ucwords(str_replace('-',' ',one_two_explode('fa-',' ',$random_cover)))));
}

function view_list_e($i, $x__creator = 0, $plain_no_html = false){

    $CI =& get_instance();
    $relevant_e = '';
    $e___33602 = $CI->config->item('e___33602');
    $e___41975 = $CI->config->item('e___41975');

    //Define Order:
    $order_columns = array();
    foreach($e___33602 as $x__sort_id => $sort) {
        $order_columns['x__type = \''.$x__sort_id.'\' DESC'] = null;
    }
    $order_columns['e__title'] = 'ASC';

    //Query Relevant Sources:
    foreach($CI->X_model->fetch(array(
        '( x__type IN (' . join(',', $CI->config->item('n___41975')) . ') OR ( x__type IN (' . join(',', $CI->config->item('n___33602')) . ') AND e__privacy IN (' . join(',', $CI->config->item('n___41981')) . ')))' => null, //FEATURED ACCESS -OR- DISCOVERY FEATURED LINKS
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__right' => $i['i__id'],
        'x__up !=' => website_setting(0),
    ), array('x__up'), 0, 0, $order_columns) as $x){
        $relevant_e .= view_list_e_items($i, $x__creator, $x, $plain_no_html, ( in_array($x['x__type'] , $CI->config->item('n___41975')) ? $e___41975[$x['x__type']] : array() ));
    }

    //Idea Setting Source Types:
    foreach($CI->E_model->scissor_e(31826,$i['i__type']) as $e_item) {
        //Show full legal name for agreement:
        $relevant_e .= view_list_e_items($i, $x__creator, $e_item, $plain_no_html);
    }

    return ( strlen($relevant_e) ? ( $plain_no_html ? $relevant_e : '<div class="source-featured">'.$relevant_e.'</div>' ) : false );

}

function view_list_e_items($i, $x__creator, $x, $plain_no_html = false, $append_m = array()){

    //Must have Public/Guest Access
    $CI =& get_instance();

    //See if this member also follows this featured source?
    $member_follows = array();
    if($x__creator>0){
        $member_follows = $CI->X_model->fetch(array(
            'x__up' => $x['e__id'],
            'x__down' => $x__creator,
            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        ));
    }

    $messages = '';
    foreach($member_follows as $member_follow){
        if(strlen($member_follow['x__message'])){
            $messages .= ( $plain_no_html ? $member_follow['x__message']."\n\n" : '<h2 style="padding:0 0 8px;">' . $member_follow['x__message'] . '</h2>' );
        }
    }

    if(strlen($messages)){
        $x['x__message'] = ( strlen($x['x__message']) ? $messages.( $plain_no_html ? $x['x__message'] : nl2br($x['x__message']) ) : $messages );
    }

    $show_google_maps_link = ( $x['x__type']==41949 && in_array($x['e__privacy'], $CI->config->item('n___41981')));

    if(0 && $plain_no_html){
        return
            ( $show_google_maps_link ? "\n".'https://www.google.com/maps/search/'.urlencode($x['e__title']) : $x['e__title'] )
            ."\n". $x['x__message'];
    } else {

        return '<div class="source-info"><span data-toggle="tooltip" data-placement="top" title="'.( $show_google_maps_link && count($append_m) ? $append_m['m__title'].( strlen($append_m['m__message']) ? ': '.$append_m['m__message'] : '' ) : '' ).'">'
            . ( count($append_m) ? '<span class="icon-block-xs">'.$append_m['m__cover'].'</span>' : '<span class="icon-block-xs">'. view_cover($x['e__cover'], true) . '</span>' )
            . '<span>'.( $show_google_maps_link && !$plain_no_html ? '<a href="https://www.google.com/maps/search/'.urlencode($x['e__title']).'" target="_blank">'.$x['e__title'].' <i class="far fa-external-link"></i></a>' : $x['e__title'] ) . ( strlen($x['x__message']) ? ':' : '' ) .'</span>'
            . ( strlen($x['x__message']) ? '<div class="payment_box"><div class="sub_note main__title">'.( !$plain_no_html ? nl2br(view_url($x['x__message'])) : $x['x__message'] ).'</div></div>' : '' )
            . '</span></div>';

    }


    /*
     *
     * <div '.( $x__creator==1 ? 'id="load_map" style="width:100%;height:200px;"' : '' ).'></div><script>

        $(document).ready(function () {
            let map;
let service;
let infowindow;

function initMap() {
  const sydney = new google.maps.LatLng(-33.867, 151.195);

  infowindow = new google.maps.InfoWindow();
  map = new google.maps.Map(document.getElementById("load_map"), {
    center: sydney,
    zoom: 15,
  });

  const request = {
    query: "Museum of Contemporary Art Australia",
    fields: ["name", "geometry"],
  };

  service = new google.maps.places.PlacesService(map);
  service.findPlaceFromQuery(request, (results, status) => {
    if (status === google.maps.places.PlacesServiceStatus.OK && results) {
      for (let i = 0; i < results.length; i++) {
        createMarker(results[i]);
      }

      map.setCenter(results[0].geometry.location);
    }
  });
}

function createMarker(place) {
  if (!place.geometry || !place.geometry.location) return;

  const marker = new google.maps.Marker({
    map,
    position: place.geometry.location,
  });

  google.maps.event.addListener(marker, "click", () => {
    infowindow.setContent(place.name || "");
    infowindow.open(map);
  });
}
            window.initMap = initMap;
        });


</script><script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiwKqWXXTs14NsUhqd2B83nzGSDg1VOoU&libraries=places"></script>
     * */
}

function view_headline($x__type, $counter, $m, $ui, $is_open = true, $left_pad = false){

    if(!strlen($ui)){
        return false;
    }

    $CI =& get_instance();
    $e___26006 = $CI->config->item('e___26006'); //Toggle Headline
    return '<a class="headline headline_'.$x__type.'" href="javascript:void(0);" onclick="toggle_headline('.$x__type.')"><span class="icon-block grey">'.$m['m__cover'].'</span>' .$m['m__title'].':'.( !is_null($counter) ? ' [<span class="xtypecounter'.$x__type.'">'.number_format($counter, 0) . '</span>]' : '' ).'<span class="icon-block pull-right headline_titles headline_title_'.$x__type.'"><span class="icon_26007 '.( !$is_open ? ' hidden ' : '' ).'">'.$e___26006[26008]['m__cover'].'</span><span class="icon_26008 '.( $is_open ? ' hidden ' : '' ).'">'.$e___26006[26007]['m__cover'].'</span></span></a>'.'<div class="headlinebody pillbody '.( $left_pad ? ' leftPad  ' : '' ).' headline_body_'.$x__type.( !$is_open ? ' hidden ' : '' ).'">'.$ui.'</div>';

}

function convertURLs($string)
{
    return 1;
}



function view_pill($focus_card, $x__type, $counter, $m, $ui = null, $is_open = true){

    return '<script> '.( $is_open ? ' $(document).ready(function () { toggle_pills('.$x__type.'); }); ' : '' ).' $(\'.nav-tabs\').append(\'<li class="nav-item thepill'.$x__type.'"><a class="nav-link '.( $is_open ? ' active ' : '' ).'" x__type="'.$x__type.'" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="'.number_format($counter, 0).' '.$m['m__title'].( strlen($m['m__message']) ? ': '.str_replace('\'','',str_replace('"','',$m['m__message'])) : '' ).'" onclick="toggle_pills('.$x__type.')">&nbsp;<span class="icon-block-xxs">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'.view_number($counter) . '</span></a></li>\') </script>' .
        '<div class="headlinebody pillbody headline_body_'.$x__type.( !$is_open ? ' hidden ' : '' ).'" read-counter="'.$counter.'">'.$ui.'</div>';

}

function view_e_line($e)
{

    $ui = '<a href="/@'.$e['e__handle'].'" class="doblock">';
    $ui .= '<span class="icon-block">'.view_cover($e['e__cover'], true).'</span>';
    $ui .= '<span class="main__title">'.$e['e__title'].'<span class="grey" style="padding-left:8px;">' . view_time_difference($e['x__time']) . ' Ago</span></span>';
    $ui .= '</a>';
    return $ui;

}



function view_card_e($x__type, $e, $extra_class = null)
{

    $CI =& get_instance();

    if(!in_array($x__type, $CI->config->item('n___14690'))){
        //Not a valid Source List
        return 'Invalid x__type e @'.$x__type.' is missing from @14690';
    }

    if(!isset($e['e__id']) || !isset($e['e__title'])){
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view_card_e() Missing core variables',
            'x__metadata' => array(
                '$x__type' => $x__type,
                '$e' => $e,
            ),
        ));
        return 'Missing core variables';
    }

    $x__id = ( isset($e['x__id']) ? $e['x__id'] : 0);

    if($x__type==42294 && $x__id && filter_var($e['x__message'], FILTER_VALIDATE_URL)){
        return view_media($e['x__message'], '/@'.$e['e__handle']);
    }

    $access_locked = in_array($e['e__privacy'], $CI->config->item('n___32145')); //Locked Dropdown
    $access_public = in_array($e['e__privacy'], $CI->config->item('n___33240'));

    $write_privacy_e = ( $access_locked ? false :  write_privacy_e($e['e__handle']) );
    $member_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
    $discovery_mode = in_array($x__type, $CI->config->item('n___14378')); //DISCOVERY MODE
    $focus_card = in_array($x__type, $CI->config->item('n___12149')); //NODE COIN
    $cache_app = in_array($x__type, $CI->config->item('n___14599'));
    $is_cache = in_array($x__type, $CI->config->item('n___14599'));
    $is_app_store = in_array($e['e__id'], $CI->config->item('n___6287'));

    $has_note = ( $x__id > 0 && in_array($e['x__type'], $CI->config->item('n___13550')));


    $is_app = $x__type==6287;

    $href = ( $is_app ? '/'.$e['e__handle'] : '/@'.$e['e__handle'] );
    $focus_e__handle = ( view_valid_handle_e($CI->uri->segment(1)) ? substr($CI->uri->segment(1), 1) : false );
    $has_x_progress = ( $x__id > 0 && (in_array($e['x__type'], $CI->config->item('n___6255')) || $write_privacy_e));
    $has_valid_url = filter_var($e['e__cover'], FILTER_VALIDATE_URL);
    $show_custom_image = !$has_valid_url && $e['e__cover'];
    $e_is_e = $focus_e__handle && $e['e__handle']==$focus_e__handle;


    //Is Lock/Private?
    $has_hard_lock = in_array($e['e__privacy'], $CI->config->item('n___30956')) && !superpower_unlocked(12701) && (!$member_e || !$e_is_e);
    $has_soft_lock = !superpower_unlocked(12701) && ($has_hard_lock || (!in_array($e['e__privacy'], $CI->config->item('n___7357')) && !$write_privacy_e));
    $has_any_lock = $is_cache || (!superpower_unlocked(12701) && ($has_soft_lock || $has_hard_lock));
    $has_sortable = $x__id > 0 && !$has_soft_lock && in_array($x__type, $CI->config->item('n___13911')) && superpower_unlocked(13422) && !in_array($e['x__type'], $CI->config->item('n___42348'));
    $show_text_editor = $write_privacy_e && !$has_any_lock && !$is_cache;

    //Source UI
    $ui  = '<div e__id="' . $e['e__id'] . '" e__handle="' . $e['e__handle'] . '" '.( isset($e['x__id']) ? ' x__id="'.$e['x__id'].'" ' : '' ).' class="card_cover card_e_cover contrast_bg no-padding s__12274_'.$e['e__id'].' '.$extra_class.( $is_app ? ' coin-6287 ' : '' ).( $has_sortable ? ' sort_draggable ' : '' ).( $discovery_mode ? ' coinface-6255 coin-6255 coinface-12274 coin-12274 ' : ' coinface-12274 coin-12274  ' ).( $focus_card ? ' focus-cover slim_flat col-md-6 col-8 ' : ' edge-cover card_click_e col-md-4 col-6 ' ).( $show_text_editor ? ' doedit ' : '' ).( isset($e['x__id']) ? ' cover_x_'.$e['x__id'].' ' : '' ).( $has_soft_lock ? ' not-allowed ' : '' ).'">';

    //Source Link Groups
    $link_type_id = 0;
    $link_type_ui = '';
    if($x__id){
        foreach($CI->config->item('e___31770') as $x__type1 => $m1){
            if(in_array($e['x__type'], $CI->config->item('n___'.$x__type1))){
                foreach($CI->X_model->fetch(array(
                    'x__id' => $x__id,
                ), array('x__creator')) as $linker){
                    $link_type_ui .= '<td><div class="'.( in_array($x__type1, $CI->config->item('n___32172')) || in_array($e['x__type'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                    $link_type_ui .= view_dropdown($x__type1, $e['x__type'], null, $write_privacy_e, false, $e['e__id'], $x__id);
                    $link_type_ui .= '</div></td>';
                }
                $link_type_id = $x__type1;
                break;
            }
        }
    }



    if(!$cache_app && !$is_app) {

        //Top Bar
        $top_bar_ui = '';
        $active_bars = 0;
        foreach($CI->config->item('e___31963') as $x__type_top_bar => $m_top_bar) {

            $always_see = in_array($x__type_top_bar, $CI->config->item('n___32172'));

            if($x__type_top_bar==31770 && $x__id && superpower_unlocked(13422)){

                $active_bars++;
                $top_bar_ui .= $link_type_ui;

            } elseif($x__type_top_bar==6177 && ($write_privacy_e || $access_locked || $always_see || in_array($e['e__privacy'], $CI->config->item('n___32172')))){

                //Source Privacy
                $active_bars++;
                $top_bar_ui .= '<td><div class="'.( $always_see || in_array($e['e__privacy'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= view_dropdown(6177, $e['e__privacy'], null, $write_privacy_e, false, $e['e__id']);
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==4362 && isset($e['x__time']) && strtotime($e['x__time']) > 0){

                //Creation Time:
                $active_bars++;
                $top_bar_ui .= '<td><div class="show-on-hover grey created_time" title="'.date("Y-m-d H:i:s", strtotime($e['x__time'])).'">' . view_time_difference($e['x__time'], true) . '</div></td>';

            } elseif($x__type_top_bar==41037 && $write_privacy_e && !$focus_card){

                //Selector
                $active_bars++;
                $top_bar_ui .= '<td class="ignore-click"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<input class="form-check-input" type="checkbox" value="" e__id="'.$e['e__id'].'" id="selector_e_'.$e['e__id'].'" aria-label="...">';
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==31912 && $write_privacy_e){

                //Edit Source
                $active_bars++;
                $top_bar_ui .= '<td><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<a title="'.$m_top_bar['m__title'].'" href="javascript:void(0);" onclick="editor_load_e('.$e['e__id'].','.$x__id.')">'.$m_top_bar['m__cover'].'</a>';
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==13006 && $has_sortable){

                //Sort Source
                $active_bars++;
                $top_bar_ui .= '<td class="sort_e_frame hidden"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<span title="'.$m_top_bar['m__title'].'" class="sort_e_grab">'.$m_top_bar['m__cover'].'</span>';
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==14980 && !$cache_app && !$access_locked){

                $action_buttons = null;

                if(!$x__id){
                    $focus_dropdown = 12887; //Source Dropdown
                } elseif($link_type_id==32292){ //Source/Source Links
                    $focus_dropdown = 14956; //Source/Source Dropdown
                } elseif($link_type_id==6255){ //Discoveries
                    $focus_dropdown = 32070; //Source>Discoveries Dropdown
                } elseif($link_type_id==13550){ //Idea/Source Links
                    $focus_dropdown = 28792; //Source/Idea Dropdown
                } else {
                    $focus_dropdown = 0;
                }

                if($focus_dropdown>0 && is_array($CI->config->item('e___'.$focus_dropdown))){
                    foreach($CI->config->item('e___'.$focus_dropdown) as $e__id_dropdown => $m_dropdown) {

                        //Skip if missing superpower:
                        $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_dropdown['m__following']);
                        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                            continue;
                        }

                        $anchor = '<span class="icon-block">'.$m_dropdown['m__cover'].'</span>'.$m_dropdown['m__title'];


                        if($e__id_dropdown==4997){

                            $action_buttons .= '<a href="javascript:void(0);" onclick="mass_apply_preview(4997,'.$e['e__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==6287 && $is_app_store){

                            //App Store
                            $action_buttons .= '<a href="'.view_app_link($e['e__id']).'" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==29771){

                            //Clone:
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_copy('.$e['e__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==10673 && $x__id > 0 && $write_privacy_e){

                            //UNLINK
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_delete(' . $x__id . ', '.$e['x__type'].')" class="dropdown-item main__title">'.$anchor.'</span></a>';

                        } elseif($e__id_dropdown==6178 && $write_privacy_e){

                            //Delete Permanently
                            $action_buttons .= '<a href="javascript:void();" current-selected="'.$e['e__privacy'].'" onclick="update_dropdown(6177, 6178, '.$e['e__id'].', '.$x__id.', 0)" class="dropdown-item dropi_6177_'.$e['e__id'].'_'.$x__id.' main__title optiond_6178_'.$e['e__id'].'_'.$x__id.'">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==13007){

                            //Reset Alphabetic order
                            $action_buttons .= '<a href="javascript:void(0);" onclick="sort_alphabetical()" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==6415){

                            //Reset my discoveries
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_reset_discoveries('.$e['e__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==13670 && substr($CI->uri->segment(1), 0, 1)=='~') {

                            //Filter applies only when browsing an idea
                            $action_buttons .= '<a href="/~'.$CI->uri->segment(1). '?focus__e=' . $e['e__id'] . '" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif(in_array($e__id_dropdown, $CI->config->item('n___6287'))){

                            //Standard button
                            $action_buttons .= '<a href="'.view_app_link($e__id_dropdown).'?e__handle='.$e['e__handle'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                        }
                    }
                }

                //Any items found?
                if($action_buttons && $focus_dropdown>0){
                    //Right Action Menu
                    $e___14980 = $CI->config->item('e___14980'); //Dropdowns
                    $active_bars++;
                    $top_bar_ui .= '<td><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                    $top_bar_ui .= '<div class="dropdown inline-block">';
                    $top_bar_ui .= '<button type="button" class="btn no-left-padding no-right-padding main__title" id="action_menu_e_'.$e['e__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="'.$e___14980[$focus_dropdown]['m__title'].'">'.$e___14980[$focus_dropdown]['m__cover'].'</button>';
                    $top_bar_ui .= '<div class="dropdown-menu" aria-labelledby="action_menu_e_'.$e['e__id'].'">';
                    $top_bar_ui .= $action_buttons;
                    $top_bar_ui .= '</div>';
                    $top_bar_ui .= '</div>';
                    $top_bar_ui .= '</div></td>';
                }
            }
        }

        $ui .= '<table class="card_covers active_bars_'.$active_bars.'"><tr>';
        $ui .= $top_bar_ui;
        $ui .= '</tr></table>';


    }

    $ui .= '<div class="bottom-wrapper">';

    $grant_privacy = $write_privacy_e || $access_public || ($x__id>0 && $member_e && ($member_e['e__id']==$e['x__up'] || $member_e['e__id']==$e['x__down']));

    if ($grant_privacy && $x__id > 0 && !$is_app) {
        $ui .= '<span '.( $write_privacy_e ? ' onclick="editor_load_e('.$e['e__id'].','.$x__id.')" ' : '' ).' class="x__message mini-font hideIfEmpty light-bg ui_x__message_' . $x__id . '">'.htmlentities($e['x__message']).'</span>';
    }

    $ui .= '</div>';



    $ui .= '<div class="cover-wrapper">';

    //Coin Cover
    $ui .= ( !$focus_card ? '<a href="'.$href.'"' : '<div' ).' class="handle_href_e_'.$e['e__id'].' coinType12274 card_privacy_'.$e['e__privacy'].( !$write_privacy_e ? ' ready-only ' : '' ).' black-background-obs cover-link" '.( $has_valid_url ? 'style="background-image:url(\''.$e['e__cover'].'\');"' : '' ).'>';
    $ui .= '<div class="cover-btn '.( substr_count($e['e__cover'], 'fa-')>0 ? 'fa_found' : 'fa_not_found' ).' ui_e__cover_'.$e['e__id'].'" raw_cover="'.$e['e__cover'].'">'.($show_custom_image ? view_cover($e['e__cover'], true) : '' ).'</div>';
    $ui .= ( !$focus_card ? '</a>' : '</div>' );

    $ui .= '</div>';





    //Title Cover
    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';


    if($show_text_editor && !$is_cache && !$is_app){
        //Editable:
        $ui .= view_input(6197, $e['e__title'], $e['e__id'], $write_privacy_e, ( isset($e['x__weight']) ? ($e['x__weight']*100)+1 : 0  ), true);
        $ui .= '<div class="hidden text__6197_'.$e['e__id'].'">'.$e['e__title'].'</div>';
    } else {
        //Static:
        $ui .= '<input type="hidden" class="text__6197_'.$e['e__id'].'" value="'.$e['e__title'].'">';
        $ui .= '<div class="center">'.( $is_cache ? '<a href="'.$href.'" class="handle_href_e_'.$e['e__id'].' main__title text__6197_'.$e['e__id'].'">'.$e['e__title'].'</a>' : '<span class="main__title text__6197_'.$e['e__id'].'">'.$e['e__title'].'</span>' ).( $is_app && isset($e['x__message']) && strlen($e['x__message']) ? ' <i class="far fa-info-circle" data-toggle="tooltip" data-placement="top" title="'.$e['x__message'].'"></i>' : '' ).'</div>';
    }

    //Source Handle
    $ui .= '<div class="sub__handle grey center-block" style="margin-top: -5px;">@<span class="ui_e__handle_'.$e['e__id'].'" title="ID '.$e['e__id'].'">'.$e['e__handle'].'</span></div>';



    //Source Location:
    $e___32292 = $CI->config->item('e___32292'); //Idea Types
    foreach($CI->X_model->fetch(array(
        'x__type' => 42335,
        'x__down' => $e['e__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    ), array('x__up')) as $located){
        $ui .= '<div class="center-frame"><a href="/@'.$located['e__handle'].'"><span class="icon-block icon-block-img">'.$e___32292[42335]['m__cover'].'</span>'.$located['e__title'].'</a></div>';
    }


    //Source Social Links
    $social_ui = null;
    foreach($this->config->item('e___14036') as $e__id => $m){
        foreach($this->X_model->fetch(array(
            'x__up' => $e__id,
            'x__down' => $e['e__id'],
            'x__type IN (' . join(',', $this->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $this->config->item('n___7360')) . ')' => null, //ACTIVE
        ), array(), 0, 0) as $social_link){

            //Determine link type:
            if(filter_var($social_link['x__message'], FILTER_VALIDATE_URL)){
                //We made sure not the current website:
                $social_url = 'href="'.$social_link['x__message'].'" target="_blank"';
            } elseif(filter_var($social_link['x__message'], FILTER_VALIDATE_EMAIL)){
                $social_url = 'href="mailto:'.$social_link['x__message'].'"';
            } elseif(strlen(preg_replace("/[^0-9]/", "", $social_link['x__message'])) > 5){
                //Phone
                $social_url = 'href="tel:'.preg_replace("/[^0-9]/", "", $social_link['x__message']).'"';
            } else {
                //Unknown!
                continue;
            }

            //Append to links:
            $social_ui .= '<li><a '.$social_url.' data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'">'.$m['m__cover'].'</a></li>';

        }
    }
    if($social_ui){
        $ui .= '<div class="source-social">';
        $ui .= '<ul>';
        $ui .= $social_ui;
        $ui .= '</ul>';
        $ui .= '</div>';
    }



    $ui .= '</div>';
    $ui .= '</div>';

    //Bottom Bar
    if(!$is_cache && !$is_app && !$focus_card){
        $ui .= '<div class="card_covers">';
        foreach($CI->config->item('e___31916') as $menu_id => $m) {
            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
            if(!count($superpowers_required) || superpower_unlocked(end($superpowers_required))){
                $ui .= '<span class="hideIfEmpty '.( in_array($menu_id, $CI->config->item('n___32172')) ? '' : 'inline-on-hover' ).'">';
                $ui .= view_e_covers($menu_id,  $e['e__id']);
                $ui .= '</span>';
            }
        }
        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}


function view_input($cache_e__id, $current_value, $s__id, $write_privacy_i, $tabindex = 0, $extra_large = false){

    $CI =& get_instance();
    $e___12112 = $CI->config->item('e___12112');
    $current_value = htmlentities($current_value);
    $name = 'input'.substr(md5($cache_e__id.$current_value.$s__id.$write_privacy_i.$tabindex), 0, 8);

    //Define element attributes:
    $attributes = ( $write_privacy_i ? '' : 'disabled' ).' spellcheck="false" tabindex="'.$tabindex.'" old-value="'.$current_value.'" id="input_'.$cache_e__id.'_'.$s__id.'" class="form-control 
     inline-block editing-mode x_set_class_text text__'.$cache_e__id.'_'.$s__id.( $extra_large?' texttype__lg ' : ' texttype__sm ').' text_e_'.$cache_e__id.'" cache_e__id="'.$cache_e__id.'" s__id="'.$s__id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $focus_element = '<textarea name="'.$name.'" placeholder="'.$e___12112[$cache_e__id]['m__title'].'" '.$attributes.'>'.$current_value.'</textarea>';

    } else {

        $focus_element = '<input type="text" name="'.$name.'" data-lpignore="true" placeholder="__" value="'.$current_value.'" '.$attributes.' />';

    }

    return '<span class="span__'.$cache_e__id.' '.( !$write_privacy_i ? ' edit-locked ' : '' ).'">'.$focus_element.'</span>';

}




function view_dropdown($cache_e__id, $selected_e__id, $btn_class = null, $write_privacy_i = true, $show_full_name = true, $o__id = 0, $x__id = 0){

    $CI =& get_instance();
    $e___this = $CI->config->item('e___'.$cache_e__id);
    $member_e = superpower_unlocked();
    $e___4527 = $CI->config->item('e___4527');

    if($selected_e__id && !isset($e___this[$selected_e__id])){

        return false;

    } elseif(!$selected_e__id && $write_privacy_i && $member_e){

        //See if this user has any of these options:
        foreach($CI->X_model->fetch(array(
            'x__up IN (' . join(',', $CI->config->item('n___'.$cache_e__id)) . ')' => null, //SOURCE LINKS
            'x__down' => $member_e['e__id'],
            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        )) as $x) {
            //Supports one for now...
            $selected_e__id = $x['x__up'];
            break;
        }

    }

    $show_thumbnail = ( isset($e___this[$selected_e__id]['m__cover']) ? $e___this[$selected_e__id] : $e___4527[$cache_e__id] );

    //Make sure it's not locked:
    $write_privacy_i = ( !in_array($cache_e__id, $CI->config->item('n___32145')) && !in_array($selected_e__id, $CI->config->item('n___32145')) ? $write_privacy_i : false );

    $ui = '<div class="dropdown inline-block dropd_'.$cache_e__id.'_'.$o__id.'_'.$x__id.'" selected-val="'.$selected_e__id.'">';

    $ui .= '<button type="button" '.( $write_privacy_i ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).' btn-'.$btn_class.'" id="dropdownMenuButton'.$cache_e__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn adj-btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked '.$btn_class.'" ' ).'>';

    $ui .= '<span>' .$show_thumbnail['m__cover'].'</span>'.( $show_full_name ?  $show_thumbnail['m__title'] : '' );

    $ui .= '</button>';

    if($write_privacy_i){

        $ui .= '<div class="dropdown-menu btn-'.$btn_class.'" aria-labelledby="dropdownMenuButton'.$cache_e__id.'">';

        foreach($e___this as $e__id => $m) {

            if(in_array($e__id, $CI->config->item('n___32145'))){
                continue; //Locked Dropdown
            }

            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
            if(!count($superpowers_required) || superpower_unlocked(end($superpowers_required))){
                $ui .= '<a class="dropdown-item dropi_'.$cache_e__id.'_'.$o__id.'_'.$x__id.' main__title optiond_'.$e__id.'_'.$o__id.'_'.$x__id.' '.( $e__id==$selected_e__id ? ' active ' : '' ).'" href="javascript:void();" current-selected="'.$e__id.'" onclick="update_dropdown('.$cache_e__id.', '.$e__id.', '.$o__id.', '.$x__id.', '.intval($show_full_name).')" title="'.$m['m__message'].'"><span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].'</a>';
            }

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
    return ( intval($count)==1 ? '' : ($has_e ? 'es' : 's'));
}

