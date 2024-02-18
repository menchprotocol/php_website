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
                $ui .= '<div class="simple-line"><a href="/'.$focus_i['i__hashtag'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="main__title"><span class="icon-block">'.$m['m__cover']. '</span><span class="icon-block">'.view_cache(4737 /* Idea Type */, $focus_i['i__type'], true, 'right', $focus_i['i__id']).'</span>'.view_i_title($focus_i).'</a></div>';
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
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $m['m__title'].': '.$x['x__time'] . ' PST | ID '.$x['x__id'].'"><span class="icon-block">'.$m['m__cover']. '</span>' . view_time_difference($x['x__time']) . ' Ago</span></div>';

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
    $memory_tree = @$CI->config->item('e___'.$following);
    if(is_array($memory_tree) && count($memory_tree) && isset($memory_tree[$follower][$filed])){
        return $memory_tree[$follower][$filed];
    } else {
        return null;
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view_memory() Failed to load ['.$filed.'] @'.$following.' for @'.$follower,
        ));
    }
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
    return '<a title="@'.$x__type.'" href="'.( $is_current ? 'javascript:alert(\'You are here already!\');' : $href ).'" class="dropdown-item main__title '.( $is_current ? ' active ' : '' ).'">'.
        ( in_array($x__type, $CI->config->item('n___32172')) ? '<span class="icon-block-xs">'.$e___4593[$x__type]['m__cover'].'</span>' : '' ).
        ( in_array($o__privacy, $CI->config->item('n___32172')) ? '<span class="icon-block-xs">'.$e___6177[$o__privacy]['m__cover'].'</span>' : '' ).
        ( strlen($o__type) ? '<span class="icon-block-xs">'.$o__type.'</span>' : '&nbsp;' ). //Type or Cover
        $o__title.
        ( strlen($x__message) && superpower_unlocked(12701) ? '<div class="message2">'.strip_tags($x__message).'</div>' : '' ).
        '</a>';
}

function view_more($href, $is_current, $x__type, $o__privacy, $o__type, $o__title, $x__message = null){
    return '<a href="'.( $is_current ? 'javascript:alert(\'You are here already!\');' : $href ).'" class="dropdown-item main__title '.( $is_current ? ' active ' : '' ).'">'.
        ( $x__type ? '<span class="icon-block-xs">'.$x__type.'</span>' : '' ).
        ( $o__privacy ? '<span class="icon-block-xs">'.$o__privacy.'</span>' : '' ).
        ( strlen($o__type) ? '<span class="icon-block-xs">'.$o__type.'</span>' : '&nbsp;' ). //Type or Cover
        $o__title.
        ( strlen($x__message) && superpower_unlocked(12701) ? '<div class="message2">'.strip_tags($x__message).'</div>' : '' ).
        '</a>';
}




function e_view_body($x__type, $counter, $e__id){



    $CI =& get_instance();
    $limit = view_memory(6404,11064);
    $member_e = superpower_unlocked();

    //Check Permission:
    if(in_array($x__type, $CI->config->item('n___42376')) && !write_privacy_e(null, $e__id)){
        return '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-lock"></i></span>Private</div>';
    }

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
            $ui .= view_card_i($x__type, $i, null, null, $focus_e);
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
            $ui .= view_card_i($x__type,  $i, null, null, $focus_e);
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

function i_view_body($x__type, $counter, $i__id){

    $CI =& get_instance();


    if(in_array($x__type, $CI->config->item('n___42376')) && !write_privacy_i(null, $i__id)){
        return '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-lock"></i></span>Private</div>';
    }

    $list_results = view_i_covers($x__type, $i__id, 1);
    $ui = '';
    $is = $CI->I_model->fetch(array(
        'i__id' => $i__id,
    ));
    if(!count($is)){
        return false;
    }

    if(in_array($x__type, $CI->config->item('n___42380'))){

        //IDEA Link Groups Previous
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $previous_i) {
            $ui .= view_card_i(11019, $previous_i);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___42265'))){

        //IDEA Link Groups Next
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $next_i) {
            $ui .= view_card_i($x__type, $next_i, $is[0]);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___42284'))) {

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

    //Sign Agreement
    $message_ui = '';
    $message_ui .= '<h3 style="margin-top: 34px;">' . $e___4737[$i['i__type']]['m__title'] . '</h3>';
    if(strlen($e___4737[$i['i__type']]['m__message'])){
        $message_ui .= '<p>' . $e___4737[$i['i__type']]['m__message'] . ':</p>';
    }

    $message_ui .= '<input type="text" class="border greybg custom_ui_14506_34281 main__title itemsetting" value="'.$previous_response.'" placeholder="" id="x_write" name="x_write" style="width:289px !important; font-size: 2.1em !important;" />';

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

    if(in_array($x__type, $CI->config->item('n___42377'))){

        //Down Source Link Groups:
        $order_columns = array('x__type = \'41011\' DESC' => null, 'x__weight' => 'ASC', 'x__time' => 'DESC');
        $joins_objects = array('x__follower');
        $query_filters = array(
            'x__following' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $CI->config->item($privacy_privacy)) . ')' => null,
        );

    } elseif(in_array($x__type, $CI->config->item('n___42276'))){

        //Up Source Link Groups:
        $order_columns = array('x__type = \'41011\' DESC' => null, 'x__weight' => 'ASC', 'x__time' => 'DESC');
        $joins_objects = array('x__following');
        $query_filters = array(
            'x__follower' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $CI->config->item($privacy_privacy)) . ')' => null,
        );

    } elseif(in_array($x__type, $CI->config->item('n___11028'))){

        //Source Tree
        $order_columns = array('x__weight' => 'ASC', 'x__time' => 'DESC');
        $joins_objects = array('x__follower');
        $query_filters = array(
            'x__following' => $e__id,
            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $CI->config->item($privacy_privacy)) . ')' => null,
        );

    } elseif(in_array($x__type, $CI->config->item('n___42261'))){

        //IDEAS
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__following' => $e__id,
        );

        $joins_objects = array('x__next');
        $order_columns = array('x__type = \'34513\' DESC' => null, 'x__weight' => 'ASC', 'x__time' => 'DESC');

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
        $joins_objects = array('x__previous');
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
        if(!isset($e___11035[$x__type]['m__title'])){
            $CI->X_model->create(array(
                'x__type' => 4246, //Platform Bug Reports
                'x__following' => 11035,
                'x__follower' => $x__type,
                'x__message' => '@'.$x__type.' Missing from Nav @11035',
            ));
            $e___11035[$x__type] = array(
                'm__title' => '',
                'm__cover' => '',
            );
        }
        $query = $CI->X_model->fetch($query_filters, $joins_objects, 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">'.view_number($count_query).'<span>';
        $title_desc = number_format($count_query, 0).' '.$e___11035[$x__type]['m__title'];

        if($append_card_icon){

            if(!$count_query){
                return null;
            }

            $card_icon = '<span class="icon-block-xs">'.$e___11035[$x__type]['m__cover'].'</span>';

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
        $joins_objects = array('x__following');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
            'x__next' => $i__id,
        );

        $order_columns = array('x__type = \'34513\' DESC' => null, 'x__weight' => 'ASC', 'x__time' => 'DESC');

    } elseif(in_array($x__type, $CI->config->item('n___42380'))){

        //IDEA Link Groups Previous
        $order_columns = array('x__id' => 'DESC');
        $joins_objects = array('x__previous');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //IDEA LINKS
            'x__next' => $i__id,
        );

    } elseif(in_array($x__type, $CI->config->item('n___42265'))){

        //IDEA Link Groups Next
        $order_columns = array('x__weight' => 'ASC');
        $joins_objects = array('x__next');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
            'x__previous' => $i__id,
        );

    } elseif(in_array($x__type, $CI->config->item('n___12144'))){

        //DISCOVERIES
        $order_columns = array('x__id' => 'DESC');
        $joins_objects = array('x__creator');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //DISCOVERIES
            'x__previous' => $i__id,
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

            $card_icon = '<span class="icon-block-sm">'.$e___11035[$x__type]['m__cover'].'</span>';

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

function dynamic_headline($dynamic_e__id, $m, $selected_e = null){

    $CI =& get_instance();
    $e___6177 = $CI->config->item('e___6177'); //Source Privacy
    $e___11035 = $CI->config->item('e___11035'); //Summary

    $headline = '<span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].': ';

    if(isset($e___11035[$dynamic_e__id]) && strlen($e___11035[$dynamic_e__id]['m__message'])){
        $headline .= '<span class="icon-block-sm" title="'.$e___11035[$dynamic_e__id]['m__message'].'" data-toggle="tooltip" data-placement="top">'.@$e___11035[11035]['m__cover'].'</span>';
    }
    if(in_array($dynamic_e__id, $CI->config->item('n___42174'))){
        $headline .= '<span class="icon-block-sm" title="'.$e___11035[42174]['m__message'].'" data-toggle="tooltip" data-placement="top" style="font-size:0.34em;">'.$e___11035[42174]['m__cover'].'</span>';
    }
    if(in_array($dynamic_e__id, $CI->config->item('n___32145'))){
        $headline .= '<span class="icon-block-sm" title="'.$e___11035[32145]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[32145]['m__cover'].'</span>';
    }
    if($selected_e && !in_array($selected_e['e__privacy'], $CI->config->item('n___33240'))){
        $headline .= '<span class="icon-block-sm" title="'.$e___6177[$selected_e['e__privacy']]['m__title'].'" data-toggle="tooltip" class="grey" data-placement="top">'.$e___6177[$selected_e['e__privacy']]['m__cover'].'</span>';
    }

    return $headline;
}


function view_instant_select($focus_id, $down_e__id = 0, $right_i__id = 0){

    /*
     * Either single or multi select UI elements...
     * */

    $CI =& get_instance();
    $e___42179 = $CI->config->item('e___42179'); //Dynamic Input Fields
    $e___11035 = $CI->config->item('e___11035'); //Summary
    $e___4527 = $CI->config->item('e___4527'); //Memory
    $is_compact = in_array($focus_id, $CI->config->item('n___42191'));
    $single_select = in_array($focus_id, $CI->config->item('n___33331'));
    $multi_select = in_array($focus_id, $CI->config->item('n___33332'));
    $access_locked = in_array($focus_id, $CI->config->item('n___32145'));
    $focus_select = $CI->config->item( $single_select ? 'e___33331' : 'e___33332');

    if(!$single_select && !$multi_select){
        //Must be either:
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view_instant_select() @'.$focus_id.' not in single select @33331 or multi select 33332',
            'x__following' => $focus_id,
            'x__follower' => $down_e__id,
            'x__next' => $right_i__id,
        ));
        return false;
    }

    $already_selected = array();
    $selection_ids = array();
    $selection_options = $CI->X_model->fetch(array(
        'x__following' => $focus_id,
        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
    ), array('x__follower'), 0, 0, array('x__weight' => 'ASC'));
    foreach($selection_options as $list_item){
        array_push($selection_ids, $list_item['e__id']);
    }

    //UI for Single select or multi?
    $ui = '<div class="dynamic_selection">';
    if(!$is_compact){
        $ui .= '<h3 class="mini-font grey-line grey-header">'.dynamic_headline($focus_id, $focus_select[$focus_id]).'</h3>';
    }
    $ui .= '<div class="list-group list-radio-select grey-line radio-'.$focus_id.( $is_compact ? ' is_compact ' : ''  ).'">';

    if($down_e__id > 0){

        //Source Focus:
        if(count($selection_ids)){
            foreach($CI->X_model->fetch(array(
                'x__following IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
                'x__follower' => $down_e__id,
                'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            )) as $sel){
                array_push($already_selected, $sel['x__following']);
            }
        }

        if(!count($already_selected) && $single_select && superpower_unlocked()){
            //FIND DEFAULT if set in session of this user:
            $var_id = @$CI->session->userdata('session_custom_ui_'.$focus_id);
            foreach($selection_ids as $e__id2){
                if($var_id==$e__id2){
                    $already_selected = array($e__id2);
                    break;
                }
            }
        }

    } elseif($right_i__id>0) {

        //Idea focus:
        foreach($CI->X_model->fetch(array(
            'x__following IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
            'x__next' => $right_i__id,
            'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        )) as $sel){
            array_push($already_selected, $sel['x__following']);
        }

    }

    //view_single_select_instant(4737, 0, true, true)
    $unselected_count = 0;
    $overflow_unselected_limit = 5;
    $has_selected = count($already_selected);
    $overflow_reached = false;
    $exclude_fonts = ( in_array($focus_id, $CI->config->item('n___42417')) ? 'exclude_fonts' : '' );

    foreach($selection_options as $list_item){

        $selected = in_array($list_item['e__id'], $already_selected);
        if(!$overflow_reached && $unselected_count>=$overflow_unselected_limit && !$selected && !$is_compact){
            $overflow_reached = true;
        }

        $headline = ( strlen($list_item['e__cover']) ? '<span class="icon-block change-results">'.view_cover($list_item['e__cover']).'</span>' : '' ).$list_item['e__title'];

        if(in_array($list_item['e__id'], $CI->config->item('n___32145'))){
            $headline .= '<span class="icon-block-sm" title="'.$e___11035[32145]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[32145]['m__cover'].'</span>';
        }
        if(in_array($list_item['e__id'], $CI->config->item('n___11035')) && strlen($e___11035[$list_item['e__id']]['m__message'])>0){
            $headline .= '<span class="icon-block-sm" title="'.$e___11035[$list_item['e__id']]['m__message'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[11035]['m__cover'].'</span>';
        }

        if($selected){
            if($access_locked){
                $ui .= '<span class="list-group-item custom_ui_'.$focus_id.'_'.$list_item['e__id'].' '.$exclude_fonts.' itemsetting_'.$focus_id.' selection_preview selection_preview_'.$focus_id.' itemsetting active" title="'.stripslashes($list_item['e__title']).'">'.$headline.'</span>';
            } else {
                $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_'.$focus_id.'\').removeClass(\'hidden\');$(\'.selection_preview_'.$focus_id.'\').addClass(\'hidden\');" class="list-group-item custom_ui_'.$focus_id.'_'.$list_item['e__id'].' '.$exclude_fonts.' itemsetting_'.$focus_id.' selection_preview selection_preview_'.$focus_id.' itemsetting active" title="'.stripslashes($list_item['e__title']).'">'.$headline.'<span class="icon-block-sm"><i class="fal fa-pen-to-square"></i></span></a>';
            }
        }

        if(!$access_locked){
            $ui .= '<a href="javascript:void(0);" onclick="e_select_apply('.$focus_id.','.$list_item['e__id'].','.( $multi_select ? 1 : 0 ).','.$down_e__id.','.$right_i__id.')" class="list-group-item itemsetting custom_ui_'.$focus_id.'_'.$list_item['e__id'].' '.$exclude_fonts.' item-'.$list_item['e__id'].' itemsetting_'.$focus_id.' selection_item_'.$focus_id.( $has_selected || $overflow_reached ? ' hidden' : '' ).( $selected ? ' active ' : '' ).'" title="'.stripslashes($list_item['e__title']).'">'.$headline.( $selected ? '<span class="icon-block-sm checked_icon" title="Selected" data-toggle="tooltip" data-placement="top"><i class="fas fa-check"></i></span>' : '' ).'</a>';
        }

        if(!$selected){
            $unselected_count++;
        }
    }

    if($overflow_reached && !$has_selected && !$access_locked){
        //We show this only if non are selected and has too many options:
        $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_'.$focus_id.'\').removeClass(\'hidden\');$(\'.selection_preview_'.$focus_id.'\').addClass(\'hidden\');" class="list-group-item itemsetting selection_preview selection_preview_'.$focus_id.'"><span class="icon-block"><i class="fas fa-search-plus"></i></span>Show More...</a>';
    }

    $ui .= '</div>';
    $ui .= '</div>';
    return $ui;
}



function view_single_select_form($cache_e__id, $selected_e__id, $show_dropdown_arrow, $show_title){

    $CI =& get_instance();
    $e___this = $CI->config->item('e___'.$cache_e__id);
    $e___4527 = $CI->config->item('e___4527'); //Memory
    $e___11035 = $CI->config->item('e___11035'); //Summary

    if(!$selected_e__id || !isset($e___this[$selected_e__id])){
        return false;
    }

    //Make sure it's not locked:
    $ui = '<div class="dropdown inline-block dropd_form_'.$cache_e__id.'" selected_value="'.$selected_e__id.'">';

    $ui .= '<button type="button" class="btn no-left-padding dropdown-toggle" id="dropdown_form_'.$cache_e__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';

    $ui .= '<span class="current_content"><span class="icon-block-sm">'.$e___this[$selected_e__id]['m__cover'].'</span>'.( $show_title ? $e___this[$selected_e__id]['m__title'] : '' ).'</span>'.( $show_dropdown_arrow ? '<span class="icon-block-sm"><i class="fal fa-angle-down"></i></span>' : '' );

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu dropmenu_form_'.$cache_e__id.'" aria-labelledby="dropdown_form_'.$cache_e__id.'">';

    $ui .= '<div class="dropdown-item main__title intro_header"><span class="icon-block">'.$e___4527[$cache_e__id]['m__cover'].'</span>'.$e___4527[$cache_e__id]['m__title'].( isset($e___11035[$cache_e__id]) && strlen($e___11035[$cache_e__id]['m__message']) ? '<span class="icon-block-sm" title="'.$e___11035[$cache_e__id]['m__message'].'" data-toggle="tooltip" data-placement="top">'.@$e___11035[11035]['m__cover'].'</span>' : '' ).'</div>';


    foreach($e___this as $e__id => $m) {

        if(in_array($e__id, $CI->config->item('n___32145'))){
            continue; //Locked Dropdown
        }

        $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
        if(!count($superpowers_required) || superpower_unlocked(end($superpowers_required))){
            $ui .= '<a class="dropdown-item main__title optiond_'.$e__id.' '.( $e__id==$selected_e__id ? ' active ' : '' ).'" href="javascript:void();" this_id="'.$e__id.'" onclick="update_form_select('.$cache_e__id.', '.$e__id.', 0, '.intval($show_title).')"><span class="content_'.$e__id.'"><span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].'</span>'.( isset($e___11035[$e__id]) && strlen($e___11035[$e__id]['m__message']) ? '<span class="icon-block-sm" title="'.$e___11035[$e__id]['m__message'].'" data-toggle="tooltip" data-placement="top">'.@$e___11035[11035]['m__cover'].'</span>' : '' ).'</a>';
        }

    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function view_single_select_instant($cache_e__id, $selected_e__id, $write_privacy_i = true, $show_full_name = true, $o__id = 0, $x__id = 0){

    $CI =& get_instance();
    $e___this = $CI->config->item('e___'.$cache_e__id);
    $member_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //Summary
    $unselected_radio = in_array($cache_e__id, $CI->config->item('n___33331')) && !$selected_e__id;

    if($selected_e__id && !isset($e___this[$selected_e__id])){

        return false;

        /*
    } elseif(!$selected_e__id && $write_privacy_i && $member_e){

        //See if this user has any of these options:
        foreach($CI->X_model->fetch(array(
            'x__following IN (' . join(',', $CI->config->item('n___'.$cache_e__id)) . ')' => null, //SOURCE LINKS
            'x__follower' => $member_e['e__id'],
            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        )) as $x) {
            //Supports one for now
            $selected_e__id = $x['x__following'];
            break;
        }
    */
    }

    //Make sure it's not locked:
    $write_privacy_i = ( !in_array($cache_e__id, $CI->config->item('n___32145')) && !in_array($selected_e__id, $CI->config->item('n___32145')) ? $write_privacy_i : false );

    $ui = '<div class="dropdown '.( $show_full_name ? 'dropdown_type_'.$cache_e__id : '' ).' inline-block dropd_instant_'.$cache_e__id.'_'.$o__id.'_'.$x__id.'" selected_value="'.$selected_e__id.'">';

    $ui .= '<button type="button" '.( $write_privacy_i ? 'class="btn no-left-padding '.( $show_full_name ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).'" id="dropdown_instant_'.$cache_e__id.'_'.$o__id.'_'.$x__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn adj-btn '.( !$show_full_name ? 'no-padding' : '' ).' edit-locked" ' ).'>';

    $ui .= '<span class="current_content">'.( isset($e___this[$selected_e__id]['m__cover']) ? '<span class="icon-block-sm">'.$e___this[$selected_e__id]['m__cover'].'</span>'.( $show_full_name ?  $e___this[$selected_e__id]['m__title'] : '' ) : '<span class="icon-block-sm">'.$e___11035[$cache_e__id]['m__cover'].'</span>'.( $show_full_name ?  $e___11035[$cache_e__id]['m__title'] : '' ) ).'</span>'; //.( $show_full_name ? '<span class="icon-block-sm"><i class="fal fa-angle-down"></i></span>' : '' )

    $ui .= '</button>';

    if($write_privacy_i){

        $ui .= '<div class="dropdown-menu dropmenu_instant_'.$cache_e__id.'" o__id="'.$o__id.'" x__id="'.$x__id.'" aria-labelledby="dropdown_instant_'.$cache_e__id.'_'.$o__id.'_'.$x__id.'">';

        foreach($e___this as $e__id => $m) {

            if(in_array($e__id, $CI->config->item('n___32145'))){
                continue; //Locked Dropdown
            }

            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
            $removal_option = in_array($e__id, $CI->config->item('n___42850'));

            if(!count($superpowers_required) || superpower_unlocked(end($superpowers_required))){
                $ui .= '<a class="dropdown-item drop_item_instant_'.$cache_e__id.'_'.$o__id.'_'.$x__id.' main__title optiond_'.$e__id.'_'.$o__id.'_'.$x__id.' '.( $e__id==$selected_e__id ? ' active ' : '' ).( $removal_option ? ' removal_option '.( $unselected_radio ? ' hidden ' : '') : '' ).'" href="javascript:void();" this_id="'.$e__id.'" onclick="x_update_instant_select('.$cache_e__id.', '.$e__id.', '.$o__id.', '.$x__id.', '.intval($show_full_name).')"><span class="icon-block">'.$m['m__cover'].'</span>'.$m['m__title'].( isset($e___11035[$e__id]) && strlen($e___11035[$e__id]['m__message']) ? '<span class="icon-block-sm" title="'.$e___11035[$e__id]['m__message'].'" data-toggle="tooltip" data-placement="top">'.@$e___11035[11035]['m__cover'].'</span>' : '' ).'</a>';
            }

        }

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
    return '<div class="alert alert-info no-margin" style="margin-bottom: 10px !important;" title="'.$e___11035[13670]['m__title'].'"><span class="icon-block">'.$e___11035[13670]['m__cover'] . '</span><span class="icon-block-sm">' . view_cover($e['e__handle'], true) . '</span><a href="/@'.$e['e__handle'].'">' . $e['e__title'].'</a>&nbsp;&nbsp;&nbsp;<a href="/'.$CI->uri->segment(1).'" title="'.$e___11035[13671]['m__title'].'">'.$e___11035[13671]['m__cover'].'</a></div>';
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

function view__hash($string){
    $CI =& get_instance();
    return substr(md5($string.$CI->config->item('secret_hash')), 0, 10);
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


function view_i__links($i, $replace_links = true, $focus_card = false){
    return
        ( $replace_links ? str_replace('spanaa','a',$i['i__cache']) : $i['i__cache'] ).
        view_i_media($i).
        ( $focus_card || !substr_count($i['i__cache'], 'show_more_line') ? view_list_e($i, !$replace_links) : '' );
}

function idea_author($i__id){
    $CI =& get_instance();
    foreach($CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
        'x__next' => $i__id,
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array(), 0, 0, array('x__type = \'4250\' DESC' => null)) as $x){
        return $x['x__following'];
    }
    $member_e = superpower_unlocked();
    return ( $member_e ? $member_e['e__id'] : 14068 );
}

function idea_creation_time($i__id){
    $CI =& get_instance();
    foreach($CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
        'x__next' => $i__id,
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

    //Display Images, Audio, Video & PDF Files:
    //Analyze the message to find referencing URLs and Members in the message text:
    $CI =& get_instance();


    //All the possible reference types that can be found:
    $i__references = array(
        4256 => array(), //Generic URL
        31834 => array(), //Idea Synonym
        42337 => array(), //Idea Antonym
        31835 => array(), //Source Mention
    );

    $ui_template = array(
        4256 => '<spanaa href="%s" target="_blank" class="ignore-click"><span class="url_truncate">%s</span></spanaa>',
        31834 => '<spanaa href="/%s" data-toggle="popover" class="ref_idea">%s</spanaa>',
        42337 => '<spanaa href="/%s" data-toggle="popover" class="ref_idea">%s</spanaa>',
        31835 => '<spanaa href="/@%s" data-toggle="popover" class="ref_source">%s</spanaa>',
    );

    $replace_from = array();
    $replace_to = array();


    //See what we can find:
    $word_count = 0;
    $word_limit = 89;
    $line_inwards = 3;
    $link_words = 13; //The number of words a link is counted as

    $i__cache = '<div class="i_cache cache_frame_'.$save_i__id.'">';
    $line_count = 0;
    $hidden_started = false;
    $hidden_closed = false;

    foreach(explode("\n", $str) as $line_index => $line) {

        if(strlen($line)){
            $line_count++;
        }
        $i__cache_line = '';

        foreach(explode(' ', $line) as $word_index => $word) {

            $reference_type = 0;
            if($word_count>=$word_limit && !$hidden_started && (!$line_inwards || $word_index>=$line_inwards)){
                $i__cache_line .= '<span class="hidden inner_line">';
                $hidden_started = true;
            }
            $i__cache_line .= ( $word_index>0 ? ' ' : '' );

            if (filter_var($word, FILTER_VALIDATE_URL)) {

                //Generic URL:
                $reference_type = 4256;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .=  @sprintf($ui_template[$reference_type], $word, $word);
                $word_count += $link_words;

            } elseif (view_valid_handle_e($word, true)) {

                //Idea Synonym
                $reference_type = 31835;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .= @sprintf($ui_template[$reference_type], substr($word, 1), $word);
                $word_count++;

            } elseif (view_valid_handle_reverse_i($word, true)) {

                //Idea Antonym
                $reference_type = 42337;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .= @sprintf($ui_template[$reference_type], substr($word, 2), $word);
                $word_count++;

            } elseif (view_valid_handle_i($word, true)) {

                //Source Mention
                $reference_type = 31834;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .= @sprintf($ui_template[$reference_type], substr($word, 1), $word);
                $word_count++;

            } else {

                //This word is not referencing anything!
                $i__cache_line .= htmlentities($word);
                $word_count++;

            }
        }



        $i__cache .= '<div class="line hideIfEmpty '.(!$line_index ? 'first_line' : '').(($save_i__id && $word_count>=$word_limit && $line_count>2) ? ' hidden ' : '' ).'">';
        $i__cache .= $i__cache_line;
        if($hidden_started && !$hidden_closed){
            $i__cache .= '</span>';
            $hidden_closed = true;
        }
        $i__cache .= '</div>';

    }


    if($save_i__id && ($hidden_started || ($word_count>=$word_limit && $line_count>2))){
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
            'x__next' => $save_i__id,
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
                $x__previous = 0;
                $x__following = 0;
                $x__message = '';

                if($db_type==31834){
                    $x__type = 31834;
                    foreach($CI->I_model->fetch(array(
                        'LOWER(i__hashtag)' => strtolower(substr($db_val, 1)),
                    )) as $target){
                        $x__previous = $target['i__id'];
                    }
                } elseif($db_type==42337){
                    $x__type = 42337;
                    foreach($CI->I_model->fetch(array(
                        'LOWER(i__hashtag)' => strtolower(substr($db_val, 2)),
                    )) as $target){
                        $x__previous = $target['i__id'];
                    }
                } elseif($db_type==31835) {
                    $x__type = 31835;
                    foreach($CI->E_model->fetch(array(
                        'LOWER(e__handle)' => strtolower(substr($db_val, 1)),
                    )) as $target){
                        $str = str_replace('@'.$target['e__id'],'@'.$target['e__handle'], $str); //TODO Remove!
                        $x__following = $target['e__id'];
                    }
                } else {
                    $x__type = $db_type; //Message URLs
                    $x__following = idea_author($save_i__id);
                    $x__message = $db_val;
                }

                $CI->X_model->create(array(
                    'x__time' => idea_creation_time($save_i__id),
                    'x__type' => $x__type,
                    'x__creator' => $member_e['e__id'],
                    'x__message' => $x__message,
                    'x__next' => $save_i__id,
                    'x__previous' => $x__previous,
                    'x__following' => $x__following,
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



function view_featured_links($x__type, $location, $m = null, $focus_card){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //Summary
    return '<div class="creator_headline" '.( is_array($m) ? ' data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].( strlen($m['m__message']) ? ': '.$m['m__message'] : ' @'.$location['e__handle'] ).( strlen($location['x__message']) ? ': '.$location['x__message'] : '' ).'" ' : '' ).'>'.( $focus_card ? '<a href="/@'.$location['e__handle'].'">' : '' ).'<span class="grey '.( $x__type==41949 ? 'icon-block' : 'icon-block-xs' ).'">'.$e___11035[$x__type]['m__cover'].'</span><span class="grey mini-frame '.( $x__type==41949 ? 'mini-font' : '' ).'">'.$location['e__title'].'</span>'.( $focus_card ? '</a>' : '' ).'</div>';
}


function view_i_nav($discovery_mode, $focus_i, $write_privacy_i){

    $CI =& get_instance();
    $coins_count = array();
    $body_content = '';
    $e___loading_order = $CI->config->item('e___'.( $discovery_mode ? 26005 : 26005 ));
    $ui = '';
    $ui .= '<ul class="nav nav-tabs nav12273">';
    foreach($CI->config->item('e___'.( $discovery_mode ? 42877 : 31890 )) as $x__type => $m) {

        $coins_count[$x__type] = view_i_covers($x__type, $focus_i['i__id'], 0, false);
        if(!$coins_count[$x__type] && $x__type!=6255 & in_array($x__type, $CI->config->item('n___12144'))){ continue; }
        $can_add = $write_privacy_i && in_array($x__type, $CI->config->item('n___42262'));

        $input_content = '';
        if($can_add){

            if(in_array($x__type, $CI->config->item('n___42261'))){

                $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="+ Add @source">
                    </div></div></div><div class="algolia_pad_finder row justify-content dropdown_'.$x__type.'"></div></div>';

            }

            $body_content .= '<script> $(document).ready(function () { load_finder(12273, '.$x__type.'); }); </script>';

        }

        if($can_add || $coins_count[$x__type]>0){
            $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';

            $ui .= '<li class="nav-item thepill'.$x__type.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'">&nbsp;<span class="icon-block">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'.view_number($coins_count[$x__type]) . '</span><span class="main__title hidden xtypetitle xtypetitle_'.$x__type.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';
        }

    }
    $ui .= '</ul>';
    $ui .= $body_content;

    
    $focus_tab = 0;
    foreach($e___loading_order as $x__type => $m) { //Load Focus Tab:
        if(isset($coins_count[$x__type]) && $coins_count[$x__type] > 0){
            $focus_tab = $x__type;
            $ui .= '<script> $(document).ready(function () { set_hashtag_if_empty(\''.$m['m__handle'].'\'); }); </script>';
            break;
        }
    }
    if(!$focus_tab){
        foreach($e___loading_order as $x__type => $m) { //Load Focus Tab:
            $ui .= '<script> $(document).ready(function () { set_hashtag_if_empty(\''.$m['m__handle'].'\'); }); </script>';
            break;
        }
    }
    
    return $ui;
    
}

function view_card_i($x__type, $i, $previous_i = null, $top_i__hashtag = null, $focus_e = false){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    if(!in_array($x__type, $CI->config->item('n___13369'))){
        return 'Invalid x__type i '.$x__type;
    }

    $x__id = ( isset($i['x__id']) && $i['x__id']>0 ? $i['x__id'] : 0 );

    $e___11035 = $CI->config->item('e___11035'); //Summary
    $cache_app = in_array($x__type, $CI->config->item('n___14599'));
    $access_locked = in_array($i['i__privacy'], $CI->config->item('n___32145')); //Locked Dropdown

    $member_e = superpower_unlocked();
    $write_privacy_i = ( $cache_app || $access_locked ? false : write_privacy_i($i['i__hashtag']) );
    $user_input = $focus_e;

    $primary_icon = in_array($x__type, $CI->config->item('n___14378')); //PRIMARY ICON
    $discovery_mode = in_array($x__type, $CI->config->item('n___14378')); //DISCOVERY MODE
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
    $has_sortable = $x__id > 0 && !$focus_card && $write_privacy_i && in_array($x__type, $CI->config->item('n___4603')) && ($x__type!=42256 || $i['x__type']==34513);

    if($discovery_mode || $cache_app) {
        if($link_creator && $top_i__hashtag){
            $href = '/'.$top_i__hashtag.'/'.$i['i__hashtag'];
        } else {
            $href = '/'.$i['i__hashtag'];
        }
    } else {
        $href = '/'.$i['i__hashtag'] . ( isset($_GET['focus__e']) ? '?focus__e='.intval($_GET['focus__e']) : '' );
    }

    $has_discovered = false;

    if(!$cache_app && $focus_source){
        $discoveries = $CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__creator' => $focus_source,
            'x__previous' => $i['i__id'],
        ));
        $has_discovered = count($discoveries);
    }
    if($has_discovered && $discovery_mode){
        $i = array_merge($i, $discoveries[0]);
    }



    //Top action menu:
    $ui = '<div i__id="'.$i['i__id'].'" i__hashtag="'.$i['i__hashtag'].'" i__privacy="' . $i['i__privacy'] . '" i__type="' . $i['i__type'] . '" x__id="'.$x__id.'" class="card_cover card_i_cover '.( $focus_card ? ' focus-cover slim_flat coll-md-8 coll-sm-10 col-12
     ' : ' edge-cover ' . ( $discovery_mode ? ' col-12 ' : ' coll-md-4 coll-6 col-12 ' ) ).( $cache_app ? ' is-cache ' : '' ).( $followings_is_or ? ' doborderless ' : '' ).' no-padding '.( $discovery_mode ? ' coin-6255 card_click_x ' : ' coin-12273 card_click_i ' ).' coinface-12273 s__12273_'.$i['i__id'].' '.( $has_sortable ? ' sort_draggable ' : '' ).( $x__id ? ' cover_x_'.$x__id.' ' : '' ).'">';


    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';
    $ui .= '<div class="cover-text">';

    //Show Link User:
    $ui .= '<div class="creator_frame creator_frame_'.$i['i__id'].'">';

    //Show Creator if any:
    foreach($CI->X_model->fetch(array(
        'x__type' => 4250,
        'x__next' => $i['i__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    ), array('x__following')) as $creator){

        $follow_btn = null;
        if($focus_card && $member_e && $member_e['e__id']!=$creator['e__id']){
            $followings = $CI->X_model->fetch(array(
                'x__following' => $creator['e__id'],
                'x__follower' => $member_e['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___42795')) . ')' => null, //Follow
                'x__type !=' => 10673,
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 1, 0, array('x__weight' => 'ASC'));
            $follow_btn = view_single_select_instant(42795, ( count($followings) ? $followings[0]['x__type'] : 0 ), $member_e, false, $creator['e__id'], ( count($followings) ? $followings[0]['x__id'] : 0 ));
        }

        $ui .= '<div class="creator_headline"><a href="/@'.$creator['e__handle'].'"><span class="icon-block">'.view_cover($creator['e__cover']).'</span><b>'.$creator['e__title'].'</b><span class="grey mini-font mini-padded mini-frame">@'.$creator['e__handle'].'</span></a>'.( !in_array($creator['e__id'], $CI->config->item('n___42881')) ? '<span class="grey mini-font mini-padded mini-frame mini_time" title="'.date("Y-m-d H:i:s", strtotime($creator['x__time'])).' PST">'.view_time_difference($creator['x__time'], true).'</span>' : '' ).$follow_btn.'</div>';

    }

    //Idea Location if any:
    foreach($CI->X_model->fetch(array(
        'x__type' => 41949, //Locate
        'x__next' => $i['i__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
    ), array('x__following')) as $location){
        $ui .= view_featured_links(41949, $location, null, $focus_card);
    }

    //Link Message if any:
    if($x__id){
        $ui .= '<div class="x__message_headline grey hideIfEmpty ignore-click ui_x__message_' . $x__id . ( in_array($i['x__type'], $CI->config->item('n___42294')) ? ' hidden ' : '' ) . '" style="padding-left:34px;">'.htmlentities($i['x__message']).'</div>';
    }

    $ui .= '</div>';


    //Idea Message (Remaining)
    $ui .= '<div class="handle_href_i_'.$i['i__id'].' ui_i__cache_' . $i['i__id'] . ( !$focus_card ? ' space-content ' : '' ) . '">'.view_i__links($i, $focus_card, $focus_card).'</div>';


    //Raw Data:
    $ui .= '<div class="ui_i__message_' . $i['i__id'] . '
     hidden">'.$i['i__message'].'</div>';
    $ui .= '<div class="sub__handle space-content grey '.( $discovery_mode || !$focus_card ? ' hidden ' : '' ).'">#<span class="ui_i__hashtag_'.$i['i__id'].'">'.$i['i__hashtag'].'</span></div>';



    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';



    //Bottom Bar
    if(!$cache_app ){

        $bottom_bar_ui = '';

        //Determine Link Group
        $link_type_id = 4593; //Transaction Type
        $link_type_ui = '';
        if(!$focus_card && $x__id){
            foreach($CI->config->item('e___31770') as $x__type1 => $m1){
                if(in_array($i['x__type'], $CI->config->item('n___'.$x__type1))){
                    foreach($CI->X_model->fetch(array(
                        'x__id' => $x__id,
                    ), array('x__creator')) as $linker){
                        $link_type_ui .= '<span class="icon-block-sm"><div class="'.( in_array($x__type1, $CI->config->item('n___32172')) || in_array($i['x__type'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                        $link_type_ui .= view_single_select_instant($x__type1, $i['x__type'], $write_privacy_i, false, $i['i__id'], $x__id);
                        $link_type_ui .= '</div></span>';
                    }
                    $link_type_id = $x__type1;
                    break;
                }
            }
            if(!$link_type_ui){
                $link_type_ui .= '<span class="icon-block-sm"><div class="show-on-hover">';
                $link_type_ui .= view_single_select_instant(4593, $i['x__type'], false, false, $i['i__id'], $x__id);
                $link_type_ui .= '</div></span>';
            }
        }

        foreach($CI->config->item('e___31904') as $x__type_top_bar => $m_top_bar) {

            //See if missing superpower?
            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_top_bar['m__following']);
            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                continue;
            }

            //Determine hover state:
            $always_see = $focus_card || in_array($x__type_top_bar, $CI->config->item('n___32172'));

            if($x__type_top_bar==31770 && !$discovery_mode && $link_type_ui){

                //Links
                $bottom_bar_ui .= $link_type_ui;

            } elseif($x__type_top_bar==4362 && !$discovery_mode && isset($i['x__time']) && strtotime($i['x__time']) > 0 && $link_type_ui && ($write_privacy_i || ($member_e && $member_e['e__id']==$i['x__creator']))){

                //Link Time / Creator
                $creator_details = '';
                $time_diff = view_time_difference($i['x__time'], true);
                $creator_name = '';
                if($i['x__creator'] > 0){
                    foreach($CI->E_model->fetch(array(
                        'e__id' => $i['x__creator'],
                        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
                    )) as $creator){
                        $creator_name = 'Linked by '.$creator['e__title'].' @'.$creator['e__handle'].' on ';
                        $creator_details = '<a href="/'.$i['i__hashtag'].'"><span class="icon-block-sm">'.view_cover($creator['e__cover']).'</span></a>';
                    }
                }

                $bottom_bar_ui .= '<span class="icon-block-sm"><div class="show-on-hover grey created_time" title="'.$creator_name.date("Y-m-d H:i:s", strtotime($i['x__time'])).' which is '.$time_diff.' ago | ID '.$i['x__id'].'">' . ( $creator_details ? $creator_details : $time_diff ) . '</div></span>';

            } elseif($x__type_top_bar==41037 && !$discovery_mode && $write_privacy_i && !$focus_card){

                //Selector

            } elseif($x__type_top_bar==4737 && !$discovery_mode){

                //Idea Type
                $bottom_bar_ui .= '<span class="icon-block-sm"><div class="'.( $always_see || in_array($i['i__type'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                $bottom_bar_ui .= view_single_select_instant(4737, $i['i__type'], $write_privacy_i, false, $i['i__id'], $x__id);
                $bottom_bar_ui .= '</div></span>';

            } elseif($x__type_top_bar==31004 && !$discovery_mode && (!in_array($i['i__privacy'], $CI->config->item('n___31871')) || ($write_privacy_i && !in_array(31004, $CI->config->item('n___32145'))))){

                //Idea Access
                $bottom_bar_ui .= '<span class="icon-block-sm"><div class="'.( $always_see || in_array($i['i__privacy'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                $bottom_bar_ui .= view_single_select_instant(31004, $i['i__privacy'], $write_privacy_i, false, $i['i__id'], $x__id);
                $bottom_bar_ui .= '</div></span>';

            } elseif($x__type_top_bar==30901){

                //Reply
                $bottom_bar_ui .= '<span class="icon-block-sm"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $bottom_bar_ui .= '<a href="javascript:void(0);" onclick="i_editor_load(0,0,'.( $write_privacy_i ? 4228 : 30901 ).','.$i['i__id'].')">'.$m_top_bar['m__cover'].'</a>';
                $bottom_bar_ui .= '</div></span>';

            } elseif($x__type_top_bar==42379){

                //Reply Inverse / Quote
                $bottom_bar_ui .= '<span class="icon-block-sm"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $bottom_bar_ui .= '<a href="javascript:void(0);" onclick="i_editor_load(0,0,'.( $write_privacy_i ? 4228 : 30901 ).',0,'.$i['i__id'].')">'.$m_top_bar['m__cover'].'</a>';
                $bottom_bar_ui .= '</div></span>';

            } elseif($x__type_top_bar==42260 && $member_e && (!$x__id || !in_array($i['x__type'], $CI->config->item('n___42260')) || $i['x__creator']!=$member_e['e__id'])){

                //Reactions... Check to see if they have any?
                $reactions = $CI->X_model->fetch(array(
                    'x__following' => $member_e['e__id'],
                    'x__next' => $i['i__id'],
                    'x__type IN (' . join(',', $CI->config->item('n___42260')) . ')' => null, //Reactions
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1, 0, array('x__weight' => 'ASC'));
                $bottom_bar_ui .= '<span class="icon-block-sm"><div class="'.( $always_see || in_array($i['i__privacy'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                $bottom_bar_ui .= view_single_select_instant(42260, ( count($reactions) ? $reactions[0]['x__type'] : 0 ), $member_e, false, $i['i__id'], ( count($reactions) ? $reactions[0]['x__id'] : 0 ));
                $bottom_bar_ui .= '</div></span>';

            } elseif($x__type_top_bar==4235 && !$discovery_mode && count($CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___42350')) . ')' => null, //Active Writes
                    'x__next' => $i['i__id'],
                    'x__following' => 4235,
                )))){

                //Reply Inverse / Quote
                $bottom_bar_ui .= '<span class="icon-block-sm"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $bottom_bar_ui .= '<a href="/'.$i['i__hashtag'].'/'.view_memory(6404,4235).'">'.$m_top_bar['m__cover'].'</a>';
                $bottom_bar_ui .= '</div></span>';

            } elseif($x__type_top_bar==31911 && $write_privacy_i && !$discovery_mode){

                //Idea Editor
                $bottom_bar_ui .= '<span class="icon-block-sm"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $bottom_bar_ui .= '<a href="javascript:void(0);" onclick="i_editor_load('.$i['i__id'].','.$x__id.')" class="icon-block-sm" title="'.$m_top_bar['m__title'].'">'.$m_top_bar['m__cover'].'</a>';
                $bottom_bar_ui .= '</div></span>';

            } elseif($x__type_top_bar==13909 && $write_privacy_i && $has_sortable && !$discovery_mode){

                //Sort Idea
                $bottom_bar_ui .= '<span class="sort_i_frame hidden icon-block-sm"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $bottom_bar_ui .= '<span title="'.$m_top_bar['m__title'].'" class="sort_i_grab">'.$m_top_bar['m__cover'].'</span>';
                $bottom_bar_ui .= '</div></span>';

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

                        $anchor = '<span class="icon-block-sm">'.$m_dropdown['m__cover'].'</span>'.$m_dropdown['m__title'];

                        if($e__id_dropdown==12589 && $write_privacy_i){

                            //Mass Apply
                            $action_buttons .= '<a href="javascript:void(0);" onclick="x_mass_apply_preview(12589,'.$i['i__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==33286 && $discovery_mode && $write_privacy_i){

                            //Ideation Mode
                            $action_buttons .= '<a href="/'.$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==13007){

                            //Reset Alphabetic order
                            $action_buttons .= '<a href="javascript:void(0);" onclick="x_reset_sorting()" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==31911 && $write_privacy_i && $discovery_mode){

                            //Idea Editor
                            $action_buttons .= '<a href="javascript:void(0);" onclick="i_editor_load('.$i['i__id'].','.$x__id.')" class="dropdown-item main__title">'.$anchor.'</a>';

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

                        } elseif($e__id_dropdown==42648 && $write_privacy_i){

                            //Delete Permanently
                            $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                            $action_buttons .= '<a href="javascript:void();" this_id="'.$i['i__privacy'].'" onclick="x_update_instant_select(31004, 6182, '.$i['i__id'].', '.$x__id.', 0)" class="dropdown-item drop_item_instant_31004_'.$i['i__id'].'_'.$x__id.' main__title optiond_6182_'.$i['i__id'].'_'.$x__id.'">'.$anchor.'</a>';

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

                    $bottom_bar_ui .= '<span><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                    $bottom_bar_ui .= '<div class="dropdown inline-block">';
                    $bottom_bar_ui .= '<button type="button" class="btn no-left-padding no-right-padding main__title icon-block-sm" id="action_menu_i_'.$i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="'.$e___14980[$focus_dropdown]['m__title'].'">'.$e___14980[$focus_dropdown]['m__cover'].'</button>';
                    $bottom_bar_ui .= '<div class="dropdown-menu" aria-labelledby="action_menu_i_'.$i['i__id'].'">';
                    $bottom_bar_ui .= $action_buttons;
                    $bottom_bar_ui .= '</div>';
                    $bottom_bar_ui .= '</div>';
                    $bottom_bar_ui .= '</div></span>';

                }

            }
        }

        //Bottom Bar menu
        if(!$focus_card){
            foreach($CI->config->item('e___'.( $discovery_mode ? 42877 : 31890 )) as $e__id_bottom_bar => $m_bottom_bar) {
                $coins_ui = view_i_covers($e__id_bottom_bar,  $i['i__id']);
                if(strlen($coins_ui)){
                    $bottom_bar_ui .= '<span class="hideIfEmpty '.( in_array($e__id_bottom_bar, $CI->config->item('n___32172')) ? '' : 'inline-on-hover' ).'">';
                    $bottom_bar_ui .= $coins_ui;
                    $bottom_bar_ui .= '</span>';
                }
            }
        }


        if($bottom_bar_ui){
            $ui .= '<div class="card_covers">';
            $ui .= $bottom_bar_ui;
            $ui .= '</div>';
        }
    }




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

function view_list_e($i, $plain_no_html = false){

    $CI =& get_instance();
    $message_append = '';

    //Define Order:
    $e___42421 = $CI->config->item('e___42421');
    $order_columns = array();
    foreach($e___42421 as $x__sort_id => $sort) {
        $order_columns['x__following = \''.$x__sort_id.'\' DESC'] = null;
    }

    //Query Relevant Sources:
    foreach($CI->X_model->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Writer Links Active
        'x__next' => $i['i__id'],
        'x__following IN (' . join(',', $CI->config->item('n___42421')) . ')' => null, //Featured Inputs
    ), array('x__following'), 0, 0, $order_columns) as $x){

        //Format data if needed:
        $x['x__message'] = data_type_format($x['x__following'], $x['x__message']);

        $message_append .= '<div class="source-info">'
            . '<span class="icon-block">'. $e___42421[$x['x__following']]['m__cover'] . '</span>' . $e___42421[$x['x__following']]['m__title'] . ( strlen($x['x__message']) ? ':' : '' )
            . ( strlen($x['x__message']) ? '<div class="source_info_box"><div class="sub_note main__title">'.( !$plain_no_html ? nl2br(view_url($x['x__message'])) : $x['x__message'] ).'</div></div>' : '' )
            . '</div>';

    }

    return ( strlen($message_append) ? ( $plain_no_html ? $message_append : '<div class="source-featured">'.$message_append.'</div>' ) : false );

}


function view_i_media($i){

    $CI =& get_instance();
    $message_append = '';

    //Query Relevant Sources:
    foreach($CI->X_model->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42294')) . ')' => null, //Media
        'x__next' => $i['i__id'],
    ), array('x__following'), 0, 0, array('x__weight' => 'ASC')) as $x){

        if($x['x__type']==4258){

            //Video
            $template = '<video id="video_player_'.$x['x__message'].'" controls class="cld-video-player cld-fluid cld-video-player-skin-light" poster="'.$x['e__cover'].'"></video><script> play_video(\''.$x['x__message'].'\'); </script>';

        } elseif($x['x__type']==4259){

            //Audio
            $template = '<audio controls src="'.$x['x__message'].'"></audio>';

        } elseif($x['x__type']==4260){

            //Image
            $template = '<img src="'.$x['x__message'].'"></video>';

        } else {
            continue; //Should not happen!
        }

        //Format data if needed:
        $message_append .= '<div class="media_display media_display_'.$x['x__type'].( $x['x__type']==4258 ? ' ignore-click ' : '' ).'" id="loaded_media_'.$x['x__id'].'" class="media_item" media_e__id="'.$x['x__type'].'" e__id="'.$x['e__id'].'"  e__cover="'.$x['e__cover'].'" playback_code="'.$x['x__message'].'" e__title="'.$x['e__title'].'">'.$template.'</div>';

    }

    return $message_append;

}


function convertURLs($string)
{
    return 1;
}



function view_pill($focus_card, $x__type, $counter, $m, $ui = null, $is_open = true){

    return '<script> $(\'.nav-tabs\').append(\'<li class="nav-item thepill'.$x__type.'"><a class="nav-link" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" data-toggle="tooltip" data-placement="top" title="'.number_format($counter, 0).' '.$m['m__title'].( strlen($m['m__message']) ? ': '.str_replace('\'','',str_replace('"','',$m['m__message'])) : '' ).'"><span class="icon-block-xs">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'.view_number($counter) . '</span></a></li>\') </script>' .
        '<div class="headlinebody pillbody hidden headline_body_'.$x__type.'" read-counter="'.$counter.'">'.$ui.'</div>';

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

    $access_locked = in_array($e['e__privacy'], $CI->config->item('n___32145')); //Locked Dropdown
    $access_public = in_array($e['e__privacy'], $CI->config->item('n___33240'));

    $write_privacy_e = ( $access_locked ? false :  write_privacy_e($e['e__handle']) );
    $member_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //Summary
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
    $has_hard_lock = in_array($e['e__privacy'], $CI->config->item('n___30956')) && !$e_is_e;
    $has_soft_lock = !superpower_unlocked(12701) && ($has_hard_lock || (!in_array($e['e__privacy'], $CI->config->item('n___7357')) && !$write_privacy_e));
    $has_any_lock = $is_cache || (!superpower_unlocked(12701) && ($has_soft_lock || $has_hard_lock));
    $has_sortable = $x__id > 0 && !$has_soft_lock && in_array($x__type, $CI->config->item('n___13911')) && superpower_unlocked(13422);
    $show_text_editor = $write_privacy_e && !$has_any_lock && !$is_cache;

    //Source UI
    $ui  = '<div e__id="' . $e['e__id'] . '" e__handle="' . $e['e__handle'] . '" e__privacy="' . $e['e__privacy'] . '" '.( isset($e['x__id']) ? ' x__id="'.$e['x__id'].'" x__privacy="'.$e['x__privacy'].'" ' : '' ).' class="card_cover card_e_cover no-padding s__12274_'.$e['e__id'].' '.$extra_class.( $is_app ? ' coin-6287 ' : '' ).( $has_sortable ? ' sort_draggable ' : '' ).( $discovery_mode ? ' coinface-6255 coin-6255 coinface-12274 coin-12274 ' : ' coinface-12274 coin-12274  ' ).( $focus_card ? ' focus-cover slim_flat col-md-8 col-sm-10 col-12 ' : ' edge-cover card_click_e col-sm-4 col-6 ' ).( $show_text_editor ? ' doedit ' : '' ).( isset($e['x__id']) ? ' cover_x_'.$e['x__id'].' ' : '' ).( $has_soft_lock ? ' not-allowed ' : '' ).'">';


    if(!$cache_app && !$is_app) {

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
                        $link_type_ui .= view_single_select_instant($x__type1, $e['x__type'], $write_privacy_e, false, $e['e__id'], $x__id);
                        $link_type_ui .= '</div></td>';
                    }
                    $link_type_id = $x__type1;
                    break;
                }
            }
        }

        //Top Bar
        $top_bar_ui = '';
        foreach($CI->config->item('e___31963') as $x__type_top_bar => $m_top_bar) {

            //See if missing superpower?
            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_top_bar['m__following']);
            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                continue;
            }

            $always_see = in_array($x__type_top_bar, $CI->config->item('n___32172'));

            if($x__type_top_bar==31770 && $x__id && $member_e){

                $top_bar_ui .= $link_type_ui;

            } elseif($x__type_top_bar==6177 && $member_e && ($write_privacy_e || $access_locked || $always_see || in_array($e['e__privacy'], $CI->config->item('n___32172')))){

                //Source Privacy
                $top_bar_ui .= '<td><div class="'.( $always_see || in_array($e['e__privacy'], $CI->config->item('n___32172')) ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= view_single_select_instant(6177, $e['e__privacy'], $write_privacy_e, false, $e['e__id'], $x__id);
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==4362 && $member_e && isset($e['x__time']) && strtotime($e['x__time']) > 0){

                //Creation Time:

                $top_bar_ui .= '<td><div class="show-on-hover grey created_time" title="'.date("Y-m-d H:i:s", strtotime($e['x__time'])).' | ID '.$e['x__id'].'">' . view_time_difference($e['x__time'], true) . '</div></td>';

            } elseif($x__type_top_bar==42795 && $member_e && $member_e['e__id']!=$e['e__id'] && (!$x__id || !(superpower_unlocked(13422) && in_array($e['x__type'], $CI->config->item('n___42795')) && $e['x__follower']==$member_e['e__id'] && $e['x__following']==$e['e__id']))){

                $followings = $CI->X_model->fetch(array(
                    'x__following' => $e['e__id'],
                    'x__follower' => $member_e['e__id'],
                    'x__type IN (' . join(',', $CI->config->item('n___42795')) . ')' => null, //Follow
                    'x__type !=' => 10673,
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1, 0, array('x__weight' => 'ASC'));

                if(count($followings) || !$has_any_lock){
                    $top_bar_ui .= '<td><div class="show-on-hover">'.view_single_select_instant(42795, ( count($followings) ? $followings[0]['x__type'] : 0 ), $member_e && !$has_any_lock, false, $e['e__id'], ( count($followings) ? $followings[0]['x__id'] : 0 )).'</div></td>';
                }

            } elseif($x__type_top_bar==31912 && $write_privacy_e){

                //Edit Source
                $top_bar_ui .= '<td class="ignore-click"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<a href="javascript:void(0);" onclick="e_editor_load('.$e['e__id'].','.$x__id.')" class="icon-block-sm" title="'.$m_top_bar['m__title'].'">'.$m_top_bar['m__cover'].'</a>';
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==41037 && $write_privacy_e && !$focus_card){

                //Selector
                $top_bar_ui .= '<td class="ignore-click"><div class="'.( $always_see ? '' : 'show-on-hover' ).'">';
                $top_bar_ui .= '<input class="form-check-input" type="checkbox" value="" e__id="'.$e['e__id'].'" id="selector_e_'.$e['e__id'].'" aria-label="...">';
                $top_bar_ui .= '</div></td>';

            } elseif($x__type_top_bar==13006 && $has_sortable){

                //Sort Source
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

                            $action_buttons .= '<a href="javascript:void(0);" onclick="x_mass_apply_preview(4997,'.$e['e__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==6287 && $is_app_store){

                            //App Store
                            $action_buttons .= '<a href="'.view_app_link($e['e__id']).'" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==31912 && $write_privacy_e){

                            //Edit Source
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_editor_load('.$e['e__id'].','.$x__id.')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==29771){

                            //Clone:
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_copy('.$e['e__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==10673 && $x__id > 0 && $write_privacy_e && superpower_unlocked(10939)){

                            //UNLINK
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_delete(' . $x__id . ', '.$e['x__type'].')" class="dropdown-item main__title">'.$anchor.'</span></a>';

                        } elseif($e__id_dropdown==42649 && $write_privacy_e){

                            //Delete Source
                            $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                            $action_buttons .= '<a href="javascript:void();" this_id="'.$e['e__privacy'].'" onclick="x_update_instant_select(6177, 6178, '.$e['e__id'].', '.$x__id.', 0)" class="dropdown-item drop_item_instant_6177_'.$e['e__id'].'_'.$x__id.' main__title optiond_6178_'.$e['e__id'].'_'.$x__id.'">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==13007){

                            //Reset Alphabetic order
                            $action_buttons .= '<a href="javascript:void(0);" onclick="x_reset_sorting()" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==6415){

                            //Reset my discoveries
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_reset_discoveries('.$e['e__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==13670 && substr($CI->uri->segment(1), 0, 1)=='~') {

                            //Filter applies only when browsing an idea
                            $action_buttons .= '<a href="/'.$CI->uri->segment(1). '?focus__e=' . $e['e__id'] . '" class="dropdown-item main__title">'.$anchor.'</a>';

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

        if($top_bar_ui){
            $ui .= '<table class="card_covers"><tr>';
            $ui .= $top_bar_ui;
            $ui .= '</tr></table>';
        }

    } else {
        //Add some space:
        $ui .= '<div style="height: 8px;">&nbsp;</div>';
    }

    $ui .= '<div class="bottom-wrapper">';

    $grant_privacy = $write_privacy_e || $access_public || ($x__id>0 && $member_e && ($member_e['e__id']==$e['x__following'] || $member_e['e__id']==$e['x__follower']));

    $ui .= '</div>';



    $ui .= '<div class="cover-wrapper">';

    //Coin Cover
    $ui .= ( !$focus_card ? '<a href="'.$href.'"' : '<div' ).' class="handle_href_e_'.$e['e__id'].' coinType12274 '.( !$write_privacy_e ? ' ready-only ' : '' ).' black-background-obs cover-link" '.( $has_valid_url ? 'style="background-image:url(\''.$e['e__cover'].'\');"' : '' ).'>';
    $ui .= '<div class="cover-btn ui_e__cover_'.$e['e__id'].'" raw_cover="'.$e['e__cover'].'">'.($show_custom_image ? view_cover($e['e__cover'], true) : '' ).'</div>';
    $ui .= ( !$focus_card ? '</a>' : '</div>' );

    $ui .= '</div>';





    //Title Cover
    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';


    if($show_text_editor && !$is_cache && !$is_app && superpower_unlocked(10939)){
        //Editable:
        $ui .= view_input(6197, $e['e__title'], $e['e__id'], $write_privacy_e, ( isset($e['x__weight']) ? ($e['x__weight']*100)+1 : 0  ), true);
        $ui .= '<div class="hidden text__6197_'.$e['e__id'].'">'.$e['e__title'].'</div>';
    } else {
        //Static:
        $ui .= '<input type="hidden" class="text__6197_'.$e['e__id'].'" value="'.$e['e__title'].'">';
        $ui .= '<div class="center">';
        if($is_cache){
            $ui .= '<a href="'.$href.'" class="handle_href_e_'.$e['e__id'].' main__title text__6197_'.$e['e__id'].'">'.$e['e__title'].'</a>';
        } else {
            $ui .= '<span class="main__title text__6197_'.$e['e__id'].'">'.$e['e__title'].'</span>';
        }
        $ui .= '</div>';
    }


    //Source Handle
    $ui .= '<div class="center-block">';

    $ui .= '<div class="creator_headline grey mini-frame">@<span class="ui_e__handle_'.$e['e__id'].'" title="ID '.$e['e__id'].'">'.$e['e__handle'].'</span></div>';

    //Source Location:
    $e___42777 = $CI->config->item('e___42777');
    $order_columns = array();
    foreach($e___42777 as $x__sort_id => $sort) {
        $order_columns['x__type = \''.$x__sort_id.'\' DESC'] = null;
    }
    foreach($CI->X_model->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___42777')) . ')' => null, //Featured Profile
        'x__follower' => $e['e__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
    ), array('x__following'), 0, 0, $order_columns) as $location){
        $ui .= view_featured_links($location['x__type'], $location, $e___42777[$location['x__type']], $focus_card);
    }


    if($is_app && isset($e['x__message']) && strlen($e['x__message'])){
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$e['x__message'].'"><i class="far fa-info-circle"></i></span>';
    } else if($grant_privacy && $x__id){
        //Main description:
        $ui .= '<div class="x__message_headline grey hideIfEmpty ignore-click ui_x__message_' . $x__id . ( in_array($e['x__type'], $CI->config->item('n___42294')) ? ' hidden ' : '' ) . '">'.htmlentities($e['x__message']).'</div>';
    }

    $ui .= '</div>';
    //Icons were here before...



    //Start with Link Note
    $featured_sources = '';

    //Featured Sources
    $e___14036 = $CI->config->item('e___14036');
    $order_columns = array();
    foreach($e___14036 as $x__sort_id => $sort) {
        $order_columns['x__following = \''.$x__sort_id.'\' DESC'] = null;
    }
    foreach($CI->X_model->fetch(array(
        'x__following IN (' . join(',', $CI->config->item('n___14036')) . ')' => null, //Featured Sources
        'x__follower' => $e['e__id'],
        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array(), 0, 0, $order_columns) as $social_link){

        if(in_array($social_link['x__following'], $CI->config->item('n___32172'))){
            if(strlen($social_link['x__message'])){
                //Must always see, show content here:
                $ui .= '<div class="source_bio grey center">'.$social_link['x__message'].'</div>';
            }
            continue;
        }

        //Determine link type:
        $social_url = false;

        if(in_array(4256 , $e___14036[$social_link['x__following']]['m__following'])){
            //We made sure not the current website:
            $social_url = 'href="'.$social_link['x__message'].'" target="_blank"';
        } elseif(in_array(32097 , $e___14036[$social_link['x__following']]['m__following'])){
            $social_url = 'href="mailto:'.$social_link['x__message'].'"';
        } elseif(in_array(42181 , $e___14036[$social_link['x__following']]['m__following'])){
            //Phone Number
            $social_url = 'href="'.phone_href($social_link['x__following'], $social_link['x__message']).'"';
        }

        $info = ( strlen($social_link['x__message']) && !$social_url ? $e___14036[$social_link['x__following']]['m__title'].': '.$social_link['x__message'] : ( $social_url ? view_url_clean(one_two_explode('href="','"',$social_url)) : $e___14036[$social_link['x__following']]['m__title'] ) );

        //Append to links:
        $featured_sources .= '<span class="'.( $focus_card ? 'icon-block-sm' : 'icon-block-xs' ).'">'.( $social_url && $focus_card ? '<a '.$social_url.' data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</a>' : ( $focus_card ? '<a href="/@'.$e___14036[$social_link['x__following']]['m__handle'].'" data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</a>' : '<span data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</span>' ) ).'</span>';

    }

    if($focus_card){
        $ui .= '<div class="center-block" style="padding-top: 13px;">';
        $ui .= $featured_sources;
        $ui .= '</div>';
    }

    $ui .= '</div>';
    $ui .= '</div>';


    //Bottom Bar
    if(!$is_cache && !$is_app){

        $ui .= '<div class="card_covers hideIfEmpty">';

        if(!$focus_card){

            $ui .= $featured_sources;

            //Also Append bottom bar / main menu:
            foreach($CI->config->item('e___31916') as $menu_id => $m) {
                $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
                if(!count($superpowers_required) || superpower_unlocked(end($superpowers_required))){
                    $ui .= '<span class="hideIfEmpty '.( in_array($menu_id, $CI->config->item('n___32172')) ? '' : 'inline-on-hover' ).'">';
                    $ui .= view_e_covers($menu_id,  $e['e__id']);
                    $ui .= '</span>';
                }
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

