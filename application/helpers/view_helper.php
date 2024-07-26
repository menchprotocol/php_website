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
                $ui .= '<div class="simple-line"><a href="'.view_memory(42903,42902).$focus_e['e__handle'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="main__title"><span class="icon-block">'.$m['m__cover']. '</span>'.'<span class="icon-block">'.view_cover($focus_e['e__cover'], true). '</span>'.$focus_e['e__title'].'</a></div>';
            }

        } elseif(in_array(6202 , $m['m__following']) && intval($x[$e___32088[$e__id]['m__message']])>0){

            //IDEA
            foreach($CI->I_model->fetch(array('i__id' => $x[$e___32088[$e__id]['m__message']])) as $focus_i){
                $ui .= '<div class="simple-line"><a href="'.view_memory(42903,33286).$focus_i['i__hashtag'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="main__title"><span class="icon-block">'.$m['m__cover']. '</span><span class="icon-block">'.view_cache(4737 /* Idea Type */, $focus_i['i__type'], true, 'right', $focus_i['i__id']).'</span>'.view_i_title($focus_i).'</a></div>';
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
    return view_memory(42903,6287).view_memory(6287, $app_id, 'm__handle');
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
    return '<a href="'.( $is_current ? 'javascript:alert(\'You are here already!\');' : $href ).'" class="dropdown-item '.( $is_current ? ' active ' : '' ).'">'.
        ( in_array($x__type, $CI->config->item('n___32172')) ? '<span class="icon-block-xs">'.$e___4593[$x__type]['m__cover'].'</span>' : '' ).
        ( in_array($o__privacy, $CI->config->item('n___32172')) ? '<span class="icon-block-xs">'.$e___6177[$o__privacy]['m__cover'].'</span>' : '' ).
        ( strlen($o__type) ? '<span class="icon-block-xs">'.$o__type.'</span>' : '&nbsp;' ). //Type or Cover
        $o__title.
        ( strlen($x__message) && superpower_unlocked(12701) ? '<div class="message2">'.strip_tags($x__message).'</div>' : '' ).
        '</a>';
}

function view_more($href, $is_current, $x__type, $o__privacy, $o__type, $o__title, $x__message = null){
    return '<a href="'.( $is_current ? 'javascript:alert(\'You are here already!\');' : $href ).'" class="dropdown-item '.( $is_current ? ' active ' : '' ).'">'.
        ( $x__type ? '<span class="icon-block-xs">'.$x__type.'</span>' : '' ).
        ( $o__privacy ? '<span class="icon-block-xs">'.$o__privacy.'</span>' : '' ).
        ( strlen($o__type) ? '<span class="icon-block-xs">'.$o__type.'</span>' : '&nbsp;' ). //Type or Cover
        $o__title.
        ( strlen($x__message) && superpower_unlocked(12701) ? '<div class="message2">'.strip_tags($x__message).'</div>' : '' ).
        '</a>';
}




function e_view_body($x__type, $counter, $e__id, $js_request_uri){

    return '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">WOW</div>';

    $CI =& get_instance();
    $limit = view_memory(6404,11064);
    $player_e = superpower_unlocked();

    //Check Permission:
    if(in_array($x__type, $CI->config->item('n___42376')) && !access_level_e(null, $e__id)){
        return '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';
    }

    $list_results = view_e_covers($x__type, $e__id, 1);
    $focus_e__id = ( $e__id>0 ? $e__id : ( $player_e ? $player_e['e__id'] : 0 ) );
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
            $ui .= view_card_i($x__type, $i, null, null, $focus_e__id);
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
            $ui .= view_card_i($x__type,  $i, null, null, $focus_e__id);
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


    $list_results = view_i_covers($x__type, $i__id, 1);
    $ui = '';
    $is = $CI->I_model->fetch(array(
        'i__id' => $i__id,
    ));
    if(!count($is)){
        return false;
    }

    if(in_array($x__type, $CI->config->item('n___42376')) && !access_level_i(null, $is[0]['i__id'], $is[0])){
        return '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';
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
            'x__player' => $e__id,
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
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding load_e_covers button_of_'.$e__id.'_'.$x__type.'" id="card_e_group_'.$x__type.'_'.$e__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_x__type="'.$x__type.'" load_e__id="'.$e__id.'" load_counter="'.$count_query.'" load_first_segment="'.$first_segment.'"><span title="'.$title_desc.'" data-toggle="tooltip" data-placement="top">'.$card_icon.$visual_counter.'</span></button>';
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


function view_i_covers($x__type, $i__id, $page_num = 0, $append_card_icon = true, $headline_authors = array()){

    /*
     *
     * Loads Idea
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);
    $i__privacy = ( superpower_unlocked(10939) ? $CI->config->item('n___31871') /* Active */ : $CI->config->item('n___42948') /* Pubicly Listed Ideas */  );

    if(in_array($x__type, $CI->config->item('n___42261'))){

        //SOURCES
        $joins_objects = array('x__following');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
            'x__next' => $i__id,
        );
        if($x__type==42256 && count($headline_authors)){
            //Exclude Headline Authors since they have already been listed:
            $query_filters['x__following NOT IN (' . join(',', $headline_authors) . ')'] = null;
        }

        $order_columns = array('x__type = \'34513\' DESC' => null, 'x__weight' => 'ASC', 'x__time' => 'DESC');

    } elseif(in_array($x__type, $CI->config->item('n___42380'))){

        //IDEA Link Groups Previous
        $order_columns = array('x__id' => 'DESC');
        $joins_objects = array('x__previous');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $i__privacy) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //IDEA LINKS
            'x__next' => $i__id,
        );

    } elseif(in_array($x__type, $CI->config->item('n___42265'))){

        //IDEA Link Groups Next
        $order_columns = array('x__weight' => 'ASC');
        $joins_objects = array('x__next');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'i__privacy IN (' . join(',', $i__privacy) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
            'x__previous' => $i__id,
        );

        //HACK:
        if(0 && $x__type==42997){
            $player_e = superpower_unlocked();
            if($player_e){
                $query_filters['x__player !='] = $player_e['e__id'];
            }
        }

    } elseif(in_array($x__type, $CI->config->item('n___12144'))){

        //DISCOVERIES
        $order_columns = array('x__id' => 'DESC');
        $joins_objects = array('x__player');
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
            'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null, //DISCOVERIES
            'x__previous' => $i__id,
        );

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
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding load_i_covers button_of_'.$i__id.'_'.$x__type.'" id="card_group_i_'.$x__type.'_'.$i__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_x__type="'.$x__type.'" load_i__id="'.$i__id.'" load_counter="'.$count_query.'" load_first_segment="'.$first_segment.'"><span title="'.$title_desc.'" data-toggle="tooltip" data-placement="top">'.$card_icon.$visual_counter.'</span></button>';

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
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia

    $headline = '<span class="icon-block-sm">'.$m['m__cover'].'</span>'.$m['m__title'].': ';

    if(in_array($dynamic_e__id, $CI->config->item('n___28239'))){
        $headline .= '<span class="icon-block-sm" title="'.$e___11035[28239]['m__message'].'" data-toggle="tooltip" data-placement="top" style="font-size:0.34em;">'.$e___11035[28239]['m__cover'].'</span>';
    }
    if(in_array($dynamic_e__id, $CI->config->item('n___32145'))){
        $headline .= '<span class="icon-block-sm" title="'.$e___11035[32145]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[32145]['m__cover'].'</span>';
    }
    if($selected_e && !in_array($selected_e['e__privacy'], $CI->config->item('n___33240'))){
        $headline .= '<span class="icon-block-sm" title="'.$e___6177[$selected_e['e__privacy']]['m__title'].'" data-toggle="tooltip" class="grey" data-placement="top">'.$e___6177[$selected_e['e__privacy']]['m__cover'].'</span>';
    }

    if(isset($e___11035[$dynamic_e__id]) && strlen($e___11035[$dynamic_e__id]['m__message'])){
        $headline .= '<span class="doregular info_blob '.( strlen($e___11035[$dynamic_e__id]['m__message'])<55 ? ' short_blob ' : '' ).'"><span>'.$e___11035[$dynamic_e__id]['m__message'].'</span></span>';
    }

    return $headline;
}


function view_instant_select($focus__id, $down_e__id = 0, $right_i__id = 0){

    /*
     * Either single or multi select UI elements...
     * */

    $CI =& get_instance();
    $e___42179 = $CI->config->item('e___42179'); //Dynamic Input Fields
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia
    $e___4527 = $CI->config->item('e___4527'); //Memory
    $is_compact = in_array($focus__id, $CI->config->item('n___42191'));
    $single_select = in_array($focus__id, $CI->config->item('n___33331'));
    $multi_select = in_array($focus__id, $CI->config->item('n___33332'));
    $access_locked = in_array($focus__id, $CI->config->item('n___32145'));
    $focus_select = $CI->config->item( $single_select ? 'e___33331' : 'e___33332');

    if(!$single_select && !$multi_select){
        //Must be either:
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view_instant_select() @'.$focus__id.' not in single select @33331 or multi select 33332',
            'x__following' => $focus__id,
            'x__follower' => $down_e__id,
            'x__next' => $right_i__id,
        ));
        return false;
    }

    $already_selected = array();
    $selection_ids = array();
    $selection_options = $CI->X_model->fetch(array(
        'x__following' => $focus__id,
        'x__type IN (' . join(',', $CI->config->item('n___33337')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
    ), array('x__follower'), 0, 0, array('x__weight' => 'ASC'));
    foreach($selection_options as $list_item){
        array_push($selection_ids, $list_item['e__id']);
    }

    //UI for Single select or multi?
    $ui = '<div class="dynamic_selection">';
    if(!$is_compact){
        $ui .= '<h3 class="mini-font grey">'.dynamic_headline($focus__id, $focus_select[$focus__id]).'</h3>';
    }
    $ui .= '<div class="list-group list-radio-select grey-line radio-'.$focus__id.( $is_compact ? ' is_compact ' : ''  ).'">';

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
            $var_id = @$CI->session->userdata('session_custom_ui_'.$focus__id);
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
    $has_multiple = count($selection_options)>1;
    $overflow_reached = false;
    $exclude_fonts = ( in_array($focus__id, $CI->config->item('n___42417')) ? 'exclude_fonts' : '' );
    $e___42179 = $CI->config->item('e___42179'); //Dynamic Input Fields

    foreach($selection_options as $list_item){

        //Has superpower?
        if(isset($e___42179[$list_item['e__id']]['m__following']) && count($e___42179[$list_item['e__id']]['m__following'])){
            $superpowers_required = array_intersect($CI->config->item('n___10957'), $e___42179[$list_item['e__id']]['m__following']);
            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                continue;
            }
        }

        $selected = in_array($list_item['e__id'], $already_selected);
        if(!$overflow_reached && $unselected_count>=$overflow_unselected_limit && !$selected && !$is_compact){
            $overflow_reached = true;
        }

        $headline = '<span class="inner_headline">'.( strlen($list_item['e__cover']) ? '<span class="icon-block-sm change-results">'.view_cover($list_item['e__cover']).'</span>' : '' ).$list_item['e__title'].'</span>';
        if(in_array($list_item['e__id'], $CI->config->item('n___32145'))){
            $headline .= '<span class="icon-block-sm" title="'.$e___11035[32145]['m__title'].'" data-toggle="tooltip" data-placement="top">'.$e___11035[32145]['m__cover'].'</span>';
        }
        if($selected){
            $headline .= '<span class="icon-block-sm checked_icon"><i class="far fa-check"></i></span>';
        }
        if(in_array($list_item['e__id'], $CI->config->item('n___11035')) && strlen($e___11035[$list_item['e__id']]['m__message'])>0){
            $headline .= '<span class="doregular info_blob '.( strlen($e___11035[$list_item['e__id']]['m__message'])<55 ? ' short_blob ' : '' ).'"><span>'.$e___11035[$list_item['e__id']]['m__message'].'</span></span>';
        }


        if($selected){
            if($access_locked){
                $ui .= '<span class="list-group-item custom_ui_'.$focus__id.'_'.$list_item['e__id'].' '.$exclude_fonts.' itemsetting_'.$focus__id.' selection_preview selection_preview_'.$focus__id.' itemsetting active" title="'.stripslashes($list_item['e__title']).'">'.$headline.'</span>';
            } elseif($has_multiple) {
                $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_'.$focus__id.'\').removeClass(\'hidden\');$(\'.selection_preview_'.$focus__id.'\').addClass(\'hidden\');" class="list-group-item custom_ui_'.$focus__id.'_'.$list_item['e__id'].' '.$exclude_fonts.' itemsetting_'.$focus__id.' selection_preview selection_preview_'.$focus__id.' itemsetting active" title="'.stripslashes($list_item['e__title']).'">'.$headline.'<span class="icon-block-sm"><i class="far fa-pen-to-square"></i></span></a>';
            }
        }

        if(!$access_locked){
            $ui .= '<a href="javascript:void(0);" onclick="e_select_apply('.$focus__id.','.$list_item['e__id'].','.( $multi_select ? 1 : 0 ).','.$down_e__id.','.$right_i__id.')" class="list-group-item itemsetting custom_ui_'.$focus__id.'_'.$list_item['e__id'].' '.$exclude_fonts.' item-'.$list_item['e__id'].' itemsetting_'.$focus__id.' selection_item_'.$focus__id.( ( $has_selected && $has_multiple ) || $overflow_reached ? ' hidden' : '' ).( $selected ? ' active ' : '' ).'" title="'.stripslashes($list_item['e__title']).'">'.$headline.'</a>';
        }


        if(!$selected){
            $unselected_count++;
        }
    }

    if($overflow_reached && !$has_selected && !$access_locked){
        //We show this only if non are selected and has too many options:
        $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_'.$focus__id.'\').removeClass(\'hidden\');$(\'.selection_preview_'.$focus__id.'\').addClass(\'hidden\');" class="list-group-item itemsetting selection_preview selection_preview_'.$focus__id.'"><span class="icon-block"><i class="far fa-search-plus"></i></span>Show More...</a>';
    }

    $ui .= '</div>';
    $ui .= '</div>';
    return $ui;
}



function view_single_select_form($cache_e__id, $selected_e__id, $show_dropdown_arrow, $show_title){

    $CI =& get_instance();
    $e___this = $CI->config->item('e___'.$cache_e__id);
    $e___4527 = $CI->config->item('e___4527'); //Memory
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia

    if(!$selected_e__id || !isset($e___this[$selected_e__id])){
        return false;
    }

    //Make sure it's not locked:
    $ui = '<div class="dropdown inline-block dropd_form_'.$cache_e__id.'" selected_value="'.$selected_e__id.'">';

    $ui .= '<button type="button" class="btn no-left-padding dropdown-toggle" id="dropdown_form_'.$cache_e__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';

    $ui .= '<span class="current_content"><span class="icon-block-sm">'.$e___this[$selected_e__id]['m__cover'].'</span>'.( $show_title ? $e___this[$selected_e__id]['m__title'] : '' ).'</span>'.( $show_dropdown_arrow ? '<span class="icon-block-sm"><i class="far fa-angle-down"></i></span>' : '' );

    $ui .= '</button>';

    $ui .= '<div class="dropdown-menu dropmenu_form_'.$cache_e__id.'" aria-labelledby="dropdown_form_'.$cache_e__id.'">';

    if(!$show_title){
        $ui .= '<div class="dropdown-item main__title intro_header"><span class="icon-block-sm">'.$e___4527[$cache_e__id]['m__cover'].'</span>'.$e___4527[$cache_e__id]['m__title'].':'.( isset($e___11035[$cache_e__id]) && strlen($e___11035[$cache_e__id]['m__message']) ? '<span class="doregular info_blob '.( strlen($e___11035[$cache_e__id]['m__message'])<55 ? ' short_blob ' : '' ).'"><span>'.$e___11035[$cache_e__id]['m__message'].'</span></span>' : '' ).'</div>';
    }

    foreach($e___this as $e__id => $m) {

        if(in_array($e__id, $CI->config->item('n___32145'))){
            continue; //Locked Dropdown
        }
        $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
            continue;
        }

        $ui .= '<a class="dropdown-item main__title optiond_'.$e__id.' '.( $e__id==$selected_e__id ? ' active ' : '' ).'" href="javascript:void();" this_id="'.$e__id.'" onclick="update_form_select('.$cache_e__id.', '.$e__id.', 0, '.intval($show_title).')"><span class="content_'.$e__id.'"><span class="icon-block-sm">'.$m['m__cover'].'</span>'.$m['m__title'].'</span>'.( isset($e___11035[$e__id]) && strlen($e___11035[$e__id]['m__message']) ? '<span class="doregular info_blob '.( strlen($e___11035[$e__id]['m__message'])<55 ? ' short_blob ' : '' ).'"><span>'.$e___11035[$e__id]['m__message'].'</span></span>' : '' ).'</a>';

    }

    $ui .= '</div>';
    $ui .= '</div>';

    return $ui;
}


function view_single_select_instant($cache_e__id, $selected_e__id, $access_level_i = 0, $show_title = true, $o__id = 0, $x__id = 0){

    $CI =& get_instance();
    $e___this = $CI->config->item('e___'.$cache_e__id);
    $player_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia
    $unselected_radio = in_array($cache_e__id, $CI->config->item('n___33331')) && !$selected_e__id;
    $e___4527 = $CI->config->item('e___4527'); //Memory

    if($selected_e__id && !isset($e___this[$selected_e__id])){

        return false;

        /*
    } elseif(!$selected_e__id && $access_level_i && $player_e){

        //See if this user has any of these options:
        foreach($CI->X_model->fetch(array(
            'x__following IN (' . join(',', $CI->config->item('n___'.$cache_e__id)) . ')' => null, //SOURCE LINKS
            'x__follower' => $player_e['e__id'],
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
    $access_level_i = ( !in_array($cache_e__id, $CI->config->item('n___32145')) && !in_array($selected_e__id, $CI->config->item('n___32145')) ? $access_level_i : 0 );

    $ui = '<div class="dropdown '.( $show_title ? 'dropdown_type_'.$cache_e__id : '' ).' inline-block dropd_instant_'.$cache_e__id.'_'.$o__id.'_'.$x__id.'" selected_value="'.$selected_e__id.'">';

    $ui .= '<button type="button" '.( $access_level_i>=3 ? 'class="btn no-left-padding '.( $show_title ? 'dropdown-toggle' : 'no-right-padding dropdown-lock' ).'" id="dropdown_instant_'.$cache_e__id.'_'.$o__id.'_'.$x__id.'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : 'class="btn adj-btn '.( !$show_title ? 'no-padding' : '' ).' edit-locked" ' ).'>';

    $ui .= '<span class="current_content">'.( isset($e___this[$selected_e__id]['m__cover']) ? '<span class="icon-block-sm">'.$e___this[$selected_e__id]['m__cover'].'</span>'.( $show_title ?  $e___this[$selected_e__id]['m__title'] : '' ) : '<span class="icon-block-sm">'.$e___11035[$cache_e__id]['m__cover'].'</span>'.( $show_title ?  $e___11035[$cache_e__id]['m__title'] : '' ) ).'</span>'; //.( $show_title ? '<span class="icon-block-sm"><i class="far fa-angle-down"></i></span>' : '' )

    $ui .= '</button>';

    if($access_level_i>=3){

        $ui .= '<div class="dropdown-menu dropmenu_instant_'.$cache_e__id.'" o__id="'.$o__id.'" x__id="'.$x__id.'" aria-labelledby="dropdown_instant_'.$cache_e__id.'_'.$o__id.'_'.$x__id.'">';

        if(!$show_title){
            $ui .= '<div class="dropdown-item main__title intro_header"><span class="icon-block-sm">'.$e___4527[$cache_e__id]['m__cover'].'</span>'.$e___4527[$cache_e__id]['m__title'].':'.( isset($e___11035[$cache_e__id]) && strlen($e___11035[$cache_e__id]['m__message']) ? '<span class="doregular info_blob '.( strlen($e___11035[$cache_e__id]['m__message'])<55 ? ' short_blob ' : '' ).'"><span>'.$e___11035[$cache_e__id]['m__message'].'</span></span>' : '' ).'</div>';
        }

        foreach($e___this as $e__id => $m) {

            if(in_array($e__id, $CI->config->item('n___32145'))){
                continue; //Locked Dropdown
            }
            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                continue;
            }

            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
            $removal_option = in_array($e__id, $CI->config->item('n___42850'));

            $ui .= '<a class="dropdown-item drop_item_instant_'.$e__id.'_'.$o__id.'_'.$x__id.' main__title optiond_'.$e__id.'_'.$o__id.'_'.$x__id.' '.( $e__id==$selected_e__id ? ' active ' : '' ).( $removal_option ? ' removal_option '.( $unselected_radio ? ' hidden ' : '') : '' ).'" href="javascript:void();" this_id="'.$e__id.'" onclick="x_update_instant_select('.$cache_e__id.', '.$e__id.', '.$o__id.', '.$x__id.', '.intval($show_title).')"><span class="icon-block-sm">'.$m['m__cover'].'</span>'.$m['m__title'].( isset($e___11035[$e__id]) && strlen($e___11035[$e__id]['m__message']) ? '<span class="doregular info_blob '.( strlen($e___11035[$e__id]['m__message'])<55 ? ' short_blob ' : '' ).'"><span>'.$e___11035[$e__id]['m__message'].'</span></span>' : '' ).'</a>';


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


function view_i__links($i, $replace_links = true, $focus__node = false){
    return
        ( $replace_links ? str_replace('spanaa','a',$i['i__cache']) : $i['i__cache'] ).
        view_i_media($i).
        ( $focus__node || !substr_count($i['i__cache'], 'show_more_line') ? view_list_e($i, !$replace_links) : '' );
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
    $player_e = superpower_unlocked();
    return ( $player_e ? $player_e['e__id'] : 14068 );
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
        4256 => '<spanaa href="%s" target="_blank"><span class="url_truncate">%s</span></spanaa>',
        31834 => '<spanaa href="'.view_memory(42903,33286).'%s" data-toggle="popover" class="ref_idea">%s</spanaa>', //Ideation
        42337 => '<spanaa href="'.view_memory(42903,33286).'%s" data-toggle="popover" class="ref_idea">%s</spanaa>', //Ideation
        31835 => '<spanaa href="'.view_memory(42903,42902).'%s" data-toggle="popover" class="ref_source">%s</spanaa>', //Sourcing
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



        $i__cache .= '<div class="line '.(!$line_index ? 'first_line' : '').(($save_i__id && $word_count>=$word_limit && $line_count>2) ? ' hidden ' : '' ).'">';
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
        $player_e = superpower_unlocked();
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
                ), $player_e['e__id'], 10673 /* Member Transaction Unpublished */);

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
                    'x__player' => $player_e['e__id'],
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
        ), true, $player_e['e__id']);

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



function view_featured_links($x__type, $location, $m = null, $focus__node){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia
    return '<div class="creator_headline" '.( is_array($m) ? ' data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].( strlen($m['m__message']) ? ': '.$m['m__message'] : ' @'.$location['e__handle'] ).( strlen($location['x__message']) ? ': '.$location['x__message'] : '' ).'" ' : '' ).'>'.( $focus__node ? '<a href="'.view_memory(42903,42902).$location['e__handle'].'">' : '' ).'<span class="grey '.( $x__type==41949 ? 'icon-block' : 'icon-block-xs' ).'">'.$e___11035[$x__type]['m__cover'].'</span><span class="grey mini-frame '.( $x__type==41949 ? 'mini-font' : '' ).'">'.$location['e__title'].'</span>'.( $focus__node ? '</a>' : '' ).'</div>';
}


function view_i_nav($discovery_mode, $focus_i){

    $CI =& get_instance();
    $coins_count = array();
    $body_content = '';
    $player_e = superpower_unlocked();
    $ideation_pen = superpower_unlocked(10939);
    $e___loading_order = $CI->config->item('e___'.( $discovery_mode ? 26005 : 26005 ));

    $ui = '';
    $ui .= '<ul class="nav nav-tabs nav12273 nav__'.$focus_i['i__id'].' hideIfEmpty">';
    foreach($CI->config->item('e___'.( $discovery_mode ? 42877 : 31890 )) as $x__type => $m) {

        $superpowers_required = array_intersect($CI->config->item('n___10957'), $m['m__following']);
        if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
            continue;
        }
        if(in_array($x__type, $CI->config->item('n___42376')) && !$player_e){
            //Private content without being a member, so dont even show the counters:
            continue;
        }

        $coins_count[$x__type] = view_i_covers($x__type, $focus_i['i__id'], 0, false);
        if(!$coins_count[$x__type] && ($discovery_mode || in_array($x__type, $CI->config->item('n___12144')))){ continue; }

        $input_content = '';
        if(!$discovery_mode && $ideation_pen){

            if(in_array($x__type, $CI->config->item('n___42261'))){

                $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Search or Link @sources">
                    </div></div></div></div>';
                $body_content .= '<script> $(document).ready(function () { e_load_finder('.$x__type.'); }); </script>';

            } elseif(in_array($x__type, $CI->config->item('n___11020'))){

                //ADD IDEAS
                $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__i algolia__ci dotransparent add-input"
                               maxlength="' . view_memory(6404,6197) . '"
                               placeholder="Search or Link #ideas">
                    </div></div></div></div>';
                $body_content .= '<script> $(document).ready(function () { i_load_finder('.$x__type.'); }); </script>';
            }

        }

        if(in_array($x__type, $CI->config->item('n___42945')) || $coins_count[$x__type]>0){
            $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';

            $ui .= '<li class="nav-item thepill'.$x__type.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" title="'.number_format($coins_count[$x__type], 0).' '.$m['m__title'].'"><span class="icon-block">'.$m['m__cover'].'</span><span class="hideIfEmpty xtypecounter'.$x__type.'">'.view_number($coins_count[$x__type]) . '</span><span class="hidden xtypetitle xtypetitle_'.$x__type.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';
        }

    }
    $ui .= '</ul>';
    $ui .= $body_content;

    if($ideation_pen || 1){
        //Focus on next:
        $focus_tab = 12840;
        $ui .= '<script> $(document).ready(function () { set_hashtag_if_empty(\'Next\'); }); </script>';
    } else {
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

    }

    return $ui;
    
}

function view_card_i($x__type, $i, $previous_i = null, $target_i__hashtag = null, $focus_e__id = 0){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();
    if(!in_array($x__type, $CI->config->item('n___13369'))){
        return 'Invalid x__type i '.$x__type;
    }

    $x__id = ( isset($i['x__id']) && $i['x__id']>0 ? $i['x__id'] : 0 );
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia
    $is_cache = in_array($x__type, $CI->config->item('n___14599'));
    $goto_start = in_array($x__type, $CI->config->item('n___42988'));
    $player_e = superpower_unlocked();
    $superpower_10939 = superpower_unlocked(10939);
    $access_level_i = access_level_i($i['i__hashtag'], 0, $i, $is_cache);
    $i_startable = i_startable($i);
    $x__player = ( $focus_e__id>0 ? $focus_e__id : ( $player_e ? $player_e['e__id'] : 0 ) );
    $link_creator = isset($i['x__player']) && $i['x__player']==$x__player;
    $focus__node = in_array($x__type, $CI->config->item('n___12149')); //NODE COIN
    $discovery_uri = ( isset($_POST['js_request_uri']) && substr_count($_POST['js_request_uri'], '/')==2 ? one_two_explode('/','/',$_POST['js_request_uri']) : false );
    $discovery_seg = ( strtolower($CI->uri->segment(1))!='ajax' && strlen($CI->uri->segment(2)) ? $CI->uri->segment(1) : false );
    $discovery_mode = $x__player && ( $discovery_uri || $discovery_seg );
    $focus_i_uri = ( $discovery_uri ? one_two_explode('/','',substr($_POST['js_request_uri'], 1)) : false );
    $focus_i_seg = ( $discovery_seg ? $CI->uri->segment(2) : false );
    $focus_i__hashtag = ( $focus_i_uri ? $focus_i_uri : ( $focus_i_seg ? $focus_i_seg : false ) );

    if($discovery_mode && !$target_i__hashtag && ($discovery_uri || $discovery_seg)){
        $target_i__hashtag = ( $discovery_uri ? $discovery_uri : $discovery_seg );
    }
    if($target_i__hashtag && $focus_i__hashtag && $focus_i__hashtag==$i['i__hashtag']){
        $focus_i__hashtag = false;
    }

    $focus_i__or = false;
    if($discovery_mode && $focus_i__hashtag && !$focus__node && $x__player){
        foreach($CI->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($focus_i__hashtag),
            'i__type IN (' . join(',', $CI->config->item('n___7712')) . ')' => null, //Input Choice
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
        )) as $focus_i){
            $focus_i__or = $focus_i;
        }
    }

    $has_sortable = $x__id > 0 && !$focus__node && $access_level_i>=3 && in_array($x__type, $CI->config->item('n___4603')) && ($x__type!=42256 || $i['x__type']==34513);
    $has_discovered = 0;
    if(!$is_cache && $x__player){
        $discoveries = $CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__player' => $x__player,
            'x__previous' => $i['i__id'],
        ));
        $has_discovered = count($discoveries);
    }
    if($has_discovered && $discovery_mode){
        //$i = array_merge($i, $discoveries[0]);
    }

    if($has_discovered && !$target_i__hashtag){
        foreach($CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__player' => $x__player,
            'x__previous' => $i['i__id'],
            'x__next > 0' => null,
        ), array('x__next')) as $this_dis){
            $target_i__hashtag = $this_dis['i__hashtag'];
        }
    }

    $is_locked = ($discovery_mode && !$has_discovered && !$focus__node);

    if(($goto_start || !$superpower_10939) && $i_startable){
        $href = view_memory(42903,30795).$i['i__hashtag'].'/'.view_memory(6404,4235);
    } elseif($is_locked) {
        $href = null;
    } elseif($target_i__hashtag){
        $href = view_memory(42903,30795).$target_i__hashtag.'/'.$i['i__hashtag'];
    } elseif($discovery_mode) {
        $href = view_memory(42903,33286).$i['i__hashtag'];
    } else {
        $href = view_memory(42903,33286).$i['i__hashtag'];
    }




    //Top action menu:
    $ui = '<div i__id="'.$i['i__id'].'" i__hashtag="'.$i['i__hashtag'].'" i__privacy="' . $i['i__privacy'] . '" i__type="' . $i['i__type'] . '" x__id="'.$x__id.'" href="'.$href.'" class="card_cover card_i_cover '.( $focus__node ? ' focus-cover slim_flat coll-md-8 coll-sm-10 col-12
     ' : ' edge-cover ' . ( $discovery_mode ? ' col-12 ' : ' coll-md-4 coll-6 col-12 ' ) ).' no-padding card-12273 s__12273_'.$i['i__id'].' '.( strlen($href) ? ' card_click ' : '' ).( !$focus_i__or && $is_locked ? ' is_locked ' : '' ).( $has_sortable ? ' sort_draggable ' : '' ).( $x__id ? ' cover_x_'.$x__id.' ' : '' ).'" '.( !$focus_i__or && $is_locked && 0 ? ' title="Scroll Down & Click on the black [Go Next] button to continue" data-toggle="tooltip" data-placement="top" ' : '' ).'>';

    if($discovery_mode && $x__player && $focus__node){
        $ui .= '<style> .add_idea{ display:none; } </style>';
    }
    if(1){ // $discovery_mode && ($is_locked || $focus_i__or)
        $ui .= '<script> $(document).ready(function () {show_more('.$i['i__id'].'); }); </script>';
    }
    if($is_locked){
        $ui .= '<script> $(document).ready(function () { $(\'.cache_frame_'.$i['i__id'].' .first_line\').prepend(\''.$e___11035[43010]['m__cover'].' \'); }); </script>';
    }

    if(count($CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
            'x__next' => $i['i__id'],
            'x__following' => 28239, //Required
        )))){
        //Add required icon:
        $ui .= '<script> $(document).ready(function () { $(\'.cache_frame_'.$i['i__id'].' .first_line\').append(\'<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).' asterisk" title="Required">*</span>\'); }); </script>';
    }

    if($focus_i__or){
        $ui .= '<div class="this_selector this_selector_'.$i['i__id'].'" selection_i__id="'.$i['i__id'].'"><span class="icon-block-sm">'.( count($CI->X_model->fetch(array(
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7712, //Input Choice
                'x__player' => $x__player,
                'x__previous' => $focus_i__or['i__id'],
                'x__next' => $i['i__id'],
            ))) ? '<i class="fas fa-square-check fa-sharp"></i>' : '<i class="far fa-square fa-sharp"></i>' ).'</span></div>';
    }

    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';
    $ui .= '<div class="cover-text">';

    //Show Link User:
    $ui .= '<div class="creator_frame creator_frame_'.$i['i__id'].'">';

    //Show Creator if any:
    $headline_authors = array();
    foreach($CI->X_model->fetch(array(
        'x__type' => 4250,
        'x__next' => $i['i__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    ), array('x__following')) as $creator){

        array_push($headline_authors, $creator['e__id']);
        $follow_btn = null;
        if($focus__node && $x__player && $x__player!=$creator['e__id']){
            $followings = $CI->X_model->fetch(array(
                'x__following' => $creator['e__id'],
                'x__follower' => $x__player,
                'x__type IN (' . join(',', $CI->config->item('n___42795')) . ')' => null, //Follow
                'x__type !=' => 10673,
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 1, 0, array('x__weight' => 'ASC'));
            $follow_btn = view_single_select_instant(42795, ( count($followings) ? $followings[0]['x__type'] : 0 ), $access_level_i, false, $creator['e__id'], ( count($followings) ? $followings[0]['x__id'] : 0 ));
        }

        $ui .= '<div class="creator_headline"><a href="'.view_memory(42903,42902).$creator['e__handle'].'"><span class="icon-block">'.view_cover($creator['e__cover']).'</span><b>'.$creator['e__title'].'</b><span class="grey mini-font mini-padded mini-frame">@'.$creator['e__handle'].'</span></a>'.( !in_array($creator['e__id'], $CI->config->item('n___42881')) ? '<span class="grey mini-font mini-padded mini-frame mini_time" title="'.date("Y-m-d H:i:s", strtotime($creator['x__time'])).' PST">'.view_time_difference($creator['x__time'], true).'</span>' : '' ).$follow_btn.'</div>';

    }

    //Idea Location if any:
    foreach($CI->X_model->fetch(array(
        'x__type' => 41949, //Locate
        'x__next' => $i['i__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
    ), array('x__following')) as $location){
        $ui .= view_featured_links(41949, $location, null, $focus__node);
    }

    //Link Message if any:
    if($x__id){
        $ui .= '<div class="x__message_headline grey hideIfEmpty ignore-click ui_x__message_' . $x__id . ( in_array($i['x__type'], $CI->config->item('n___42294')) ? ' hidden ' : '' ) . '" style="padding-left:40px;">'.htmlentities($i['x__message']).'</div>';
    }

    $ui .= '</div>';



    //Idea Message (Remaining)
    $ui .= '<div class="ui_i__cache_' . $i['i__id'] . ( !$focus__node ? ' space-content ' : '' ) . '">'.view_i__links($i, ($focus__node || 1), $focus__node).'</div>';


    //Raw Data:
    $ui .= '<div class="ui_i__message_' . $i['i__id'] . '
     hidden">'.$i['i__message'].'</div>';
    $ui .= ( $href ? '<a href="'.$href.'"' : '<div' ).' class="sub__handle space-content grey '.( !$superpower_10939 && ($discovery_mode || !$focus__node || !$x__player) ? ' hidden ' : '' ).'">#<span class="ui_i__hashtag_'.$i['i__id'].'">'.$i['i__hashtag'].'</span>'.( $href ? '</a>' : '</div>' );



    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';




    if($x__player ){

        //Three main actions: (Excludes reading which is no action)
        $input_ui = '';

        //Fetch discovery
        $x_completes = $CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__player' => $x__player,
            'x__previous' => $i['i__id'],
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('x__next'));




        //Any inputs for this idea?
        if (in_array($i['i__type'], $CI->config->item('n___7712'))) {

            //OR Selection change headline menu title, if any:
            $e___7712 = $CI->config->item('e___7712');
            $ui .= '<script> $(document).ready(function () { $(\'.nav__'.$i['i__id'].' .thepill12840 .xtypecounter12840\').remove(); $(\'.nav__'.$i['i__id'].' .thepill12840 .xtypetitle_12840\').text(\''.$e___7712[$i['i__type']]['m__message'].':\'); }); </script>';

        } elseif (in_array($i['i__type'], $CI->config->item('n___41055')) && $focus__node) {

            //PAYMENT TICKET
            if(isset($_GET['cancel_pay']) && !count($x_completes)){
                $input_ui .= '<div class="alert alert-danger" role="alert">You cancelled your payment.</div>';
            }

            if(isset($_GET['process_pay']) && !count($x_completes)){

                $input_ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Processing your payment, please wait</div>';

                //Referesh soon so we can check if completed or not
                js_php_redirect(view_memory(42903, 30795) . $target_i__hashtag .'/'.$i['i__hashtag'].'?process_pay=1', 987);

            } elseif(count($x_completes)){

                foreach($x_completes as $x_complete){

                    $x__metadata = unserialize($x_complete['x__metadata']);
                    $quantity = ( $x_complete['x__weight'] >= 2 ? $x_complete['x__weight'] : ( isset($x__metadata['quantity']) && $x__metadata['quantity']>=2 ? $x__metadata['quantity'] : 1 ) );

                    if($x__metadata['mc_gross']!=0){
                        $input_ui .= '<div class="alert alert-success tickets_issued" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>'.( $x__metadata['mc_gross']>0 ? 'You paid ' : 'You got a refund of ' ).$x__metadata['mc_currency'].' '.str_replace('.00','',$x__metadata['mc_gross']).( $quantity>1 ? ' for '.$quantity.' tickets' : '' ).'. You should receive a <b>Paypal Email Receipt</b> which we will scan upon your arrival as your ticket confirmation.</div>';
                    }

                }

                $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="'.$x__metadata['mc_gross'].'">';
                $input_ui .= '<input type="hidden" class="i__quantity" name="quantity" value="'.$x__metadata['quantity'].'">'; //Dynamic Variable that JS will update

            } else {

                $valid_currency = false; //Until we can find and verify from DB

                $paypal_email =  website_setting(30882);

                $currency_types = $CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                    'x__next' => $i['i__id'],
                    'x__following IN (' . join(',', $CI->config->item('n___26661')) . ')' => null, //Currency
                ));
                $total_dues = $CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                    'x__next' => $i['i__id'],
                    'x__following' => 26562, //Total Due
                ));
                $cart_max = $CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                    'x__next' => $i['i__id'],
                    'x__following' => 29651, //Cart Max Quantity
                ));
                $cart_min = $CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                    'x__next' => $i['i__id'],
                    'x__following' => 31008, //Cart Min Quantity
                ));



                //Payments Must have Unit Price, otherwise they are NOT a payment until added
                $info_append = '';
                $unit_currency = '';
                $unit_price = 0;
                $unit_fee = 0;
                $max_allowed = ( count($cart_max) && is_numeric($cart_max[0]['x__message']) && $cart_max[0]['x__message']>1 ? intval($cart_max[0]['x__message']) : view_memory(6404,29651) );
                $spots_remaining = i_spots_remaining($i['i__id']);
                $max_allowed = ( $spots_remaining>-1 && $spots_remaining<$max_allowed ? $spots_remaining : $max_allowed );
                $max_allowed = ( $max_allowed < 1 ? 1 : $max_allowed );
                $min_allowed = ( count($cart_min) && is_numeric($cart_min[0]['x__message']) && intval($cart_min[0]['x__message'])>0 ? intval($cart_min[0]['x__message'])>0 : 1 );
                $min_allowed = ( $min_allowed < 1 ? 1 : $min_allowed );

                if(filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && count($total_dues) && $total_dues[0]['x__message']>0 && count($currency_types)==1){

                    $valid_currency = true;
                    $e___26661 = $CI->config->item('e___26661'); //Currency

                    $digest_fees = count($CI->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                        'x__next' => $i['i__id'],
                        'x__following' => 30589, //Digest Fees
                    )));

                    //Break down amount & currency
                    $unit_currency = $e___26661[$currency_types[0]['x__following']]['m__message'];
                    $unit_price = doubleval($total_dues[0]['x__message']);
                    $unit_fee = number_format($unit_price * ( $digest_fees ? 0 : (doubleval(website_setting(30590, $x__player)) + doubleval(website_setting(27017, $x__player)))/100 ), 2, ".", "");

                    //Append information to cart:
                    $info_append .= '<div class="sub_note">';
                    if(!count($CI->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                        'x__next' => $i['i__id'],
                        'x__following' => 30615, //Is Refundable
                    )))){
                        $info_append .= 'Final sale. ';
                    }

                    $info_append .= 'No need to create a Paypal account: You can pay by only entering your credit card details to checkout as a guest. Once paid, click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="'.view_app_link(14373).'" target="_blank"><u>Terms of Use</u></a>.';
                    $info_append .= '</div>';

                }



                //Is multi selectable, allow show down for quantity:
                $input_ui .= '<div class="source-info ticket-notice">'
                    . '<span class="icon-block">'. $e___11035[31076]['m__cover'] . '</span>'
                    . '<span>'.$e___11035[31076]['m__title'] . '</span>'
                    . '<div class="source_info_box">'
                    . ( strlen($e___11035[31076]['m__message']) ? '<div class="sub_note main__title">'.nl2br($e___11035[31076]['m__message']).'</div>' : '' );

                if($max_allowed > 1 || $min_allowed > 1){
                    $input_ui .= '<div>';
                    $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(-1,'.$i['i__id'].','.$max_allowed.','.$min_allowed.','.($unit_fee+$unit_price).','.$unit_fee.')" class="sale_increment"><i class="far fa-minus-circle"></i></a>';
                    $input_ui .= '<span class="main__title current_sales" style="display: inline-block; min-width:34px; text-align: center;">'.$min_allowed.'</span>';
                    $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(1,'.$i['i__id'].','.$max_allowed.','.$min_allowed.','.($unit_fee+$unit_price).','.$unit_fee.')" class="sale_increment"><i class="far fa-plus-circle"></i></a>';
                    $input_ui .= '</div>';
                } else {
                    $input_ui .= '<span class="current_sales" style="display: none;">'.$min_allowed.'</span>';
                }


                if($unit_price > 0){
                    $input_ui .= '<div style="padding: 8px 0 21px;" '.( $unit_fee > 0 ? ' title="Base Price of '.$unit_price.' + '.$unit_fee.' in Fees" data-toggle="tooltip" data-placement="top" ' : '' ).'><span class="main__title total_ui">'.(($unit_fee+$unit_price)*$min_allowed).'</span> '.$unit_currency.'</div>';
                } else {
                    $input_ui .= '<span class="total_ui" style="display: none;">0</span>';
                }

                $input_ui .= $info_append;

                $input_ui .= '</div>';
                $input_ui .= '</div>';


                if($valid_currency){

                    $e___14870 = $CI->config->item('e___14870'); //DOMAINS

                    //Load Paypal Pay button:
                    $input_ui .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';

                    $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="'.$unit_fee.'">';
                    $input_ui .= '<input type="hidden" class="i__quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable that JS will update
                    $input_ui .= '<input type="hidden" name="item_name" value="'.view_i_title($i, true).'">';
                    $input_ui .= '<input type="hidden" name="item_number" value="'.( $target_i__hashtag ? '#'.$target_i__hashtag : '' ).'#'.$i['i__hashtag'].'@'.$player_e['e__handle'].'@'.get_domain('m__handle').'">';

                    $input_ui .= '<input type="hidden" name="amount" value="'.$unit_price.'">';
                    $input_ui .= '<input type="hidden" name="currency_code" value="'.$unit_currency.'">';
                    $input_ui .= '<input type="hidden" name="no_shipping" value="1">';
                    $input_ui .= '<input type="hidden" name="notify_url" value="https://'.$e___14870[2738]['m__message'].view_app_link(26595).'">';
                    $input_ui .= '<input type="hidden" name="cancel_return" value="https://'.get_domain('m__message').view_memory(42903,30795).$target_i__hashtag.'/'.$i['i__hashtag'].'?cancel_pay=1">';
                    $input_ui .= '<input type="hidden" name="return" value="https://'.get_domain('m__message').view_memory(42903,30795).$target_i__hashtag.'/'.$i['i__hashtag'].'?process_pay=1">';
                    $input_ui .= '<input type="hidden" name="cmd" value="_xclick">';
                    $input_ui .= '<input type="hidden" name="business" value="'.$paypal_email.'">';

                    $input_ui .= '<input type="submit" class="adj-btn pay-btn main__title" name="pay_now" id="pay_now" value="Pay Now >" onclick="$(\'.process-btn\').html(\'Loading\');$(\'#pay_now\').val(\'...\');">';

                    $input_ui .= '</form>';

                    $input_ui .= '<script> $(document).ready(function () { $(\'.go_next_btn\').hide(); }); </script>';

                } else {

                    //FREE TICKET
                    $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="'.$unit_fee.'">';
                    $input_ui .= '<input type="hidden" class="i__quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable that JS will update

                }
            }

        } elseif (in_array($i['i__type'], $CI->config->item('n___33532'))) {

            //Find the created idea if any:
            $x_responses = $CI->X_model->fetch(array(
                'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
                'x__type' => 33532, //Share Idea
                'x__previous' => $i['i__id'],
                'x__player' => $x__player,
            ), array('x__next'), 0, 1, array('x__id' => 'DESC'));

            $input_attributes = '';
            $previous_response = ( isset($x_responses[0]['i__message']) ? $x_responses[0]['i__message'] : '' );

            if (in_array($i['i__type'], $CI->config->item('n___43002'))) {

                //Textarea
                $e___6201 = $CI->config->item('e___6201'); //IDEA Cache
                $input_ui .= '<textarea class="border dotted-borders x_write algolia_finder algolia__i algolia__e" placeholder="'.( strlen($e___6201[4736]['m__message']) ? $e___6201[4736]['m__message'] : $e___6201[4736]['m__title'].'...' ).'">' . $previous_response . '</textarea>';
                $input_ui .= '<script> $(document).ready(function () { set_autosize($(\'.x_write\')); }); </script>';

            } elseif (in_array($i['i__type'], $CI->config->item('n___43003'))) {

                //Input
                if($i['i__type']==31794){

                    //Number
                    $input_type = 'number';
                    $placeholder = 'Enter Number...';

                    //Steps
                    foreach($CI->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                        'x__next' => $i['i__id'],
                        'x__following' => 31813, //Steps
                    )) as $num_steps){
                        if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                            $input_attributes .= ' step="'.$num_steps['x__message'].'" ';
                        }
                    }

                    //Min Value
                    foreach($CI->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                        'x__next' => $i['i__id'],
                        'x__following' => 31800, //Min Value
                    )) as $num_steps){
                        if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                            $input_attributes .= ' min="'.$num_steps['x__message'].'" ';
                        }
                    }

                    //Max Value
                    foreach($CI->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                        'x__next' => $i['i__id'],
                        'x__following' => 31801, //Max Value
                    )) as $num_steps){
                        if(strlen($num_steps['x__message']) && is_numeric($num_steps['x__message'])){
                            $input_attributes .= ' max="'.$num_steps['x__message'].'" ';
                        }
                    }

                } elseif($i['i__type']==30350){

                    $has_time = count($CI->X_model->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                        'x__next' => $i['i__id'],
                        'x__following' => 32442, //Select Time
                    )));

                    $input_type = ( $has_time ? 'datetime-local'  : 'date' );
                    $placeholder = ( $has_time ? 'Select Date & Time...'  : 'Select Date...' );

                } elseif($i['i__type']==42915){

                    //URL
                    $input_type = 'url';
                    $placeholder = 'Paste URL...';

                } elseif($i['i__type']==43005){

                    //Input Text
                    $input_type = 'text';
                    $placeholder = 'Write...';

                }

                $input_ui .= '<input type="'.$input_type.'" '.$input_attributes.' class="border dotted-borders x_write" placeholder="'.$placeholder.'" value="'.$previous_response.'" />';

            }

            //Uploader
            if (in_array($i['i__type'], $CI->config->item('n___43004'))) {

                if($i['i__hashtag']=='ProfilePicture' && $player_e){

                    //TODO REMOVE HACK: This is a profile picture hack:
                    $input_ui .= '<div style="padding:3px 0;"><a href="javascript:void(0);" onclick="e_editor_load('.$x__player.',0);setTimeout(function () { $(\'.uploader_42359\').click(); }, 987);" class="btn btn-black inner_uploader_'.$i['i__id'].'"><span class="icon-block-sm">'.$e___11035[7637]['m__cover'].'</span>'.$e___11035[7637]['m__title'].'</a></div>';

                } else {
                    $input_ui .= '<div class="media_outer_frame hideIfEmpty">
                        <div id="media_outer_'.$i['i__id'].'" class="media_frame media_frame_'.$i['i__id'].' hideIfEmpty"></div>
                        <div class="doclear">&nbsp;</div>
                    </div>';
                    $input_ui .= '<div style="padding:3px 0;"><div class="btn btn-black inner_uploader_'.$i['i__id'].'"><span class="icon-block-sm">'.$e___11035[7637]['m__cover'].'</span>'.$e___11035[7637]['m__title'].'</div></div>';
                    $input_ui .= '<script> $(document).ready(function () { load_cloudinary(43004, '.$i['i__id'].', [\'#'.$i['i__id'].'\'], \'.inner_uploader_'.$i['i__id'].'\'); setTimeout(function () { display_media(\'media_outer_'.$i['i__id'].'\', 43004, '.$i['i__id'].'); }, 144); }); </script>';

                    foreach($x_responses as $x_response){
                        $input_ui .= '<div class="hidden">'.view_card_i(6255, $x_response).'</div>';
                        $input_ui .= '<script> $(document).ready(function () { setTimeout(function () { display_media(\'media_outer_'.$i['i__id'].'\', 43004, '.$x_response['i__id'].'); }, 144); }); </script>';
                    }
                }

            }

        }

        if(strlen($input_ui)){
            $ui .= '<div class="ignore-click input_ui input_ui_'.$i['i__id'].'">'.$input_ui.'</div>';
        }

        //End of Discovery input
    }




    //Bottom Bar
    $bottom_bar_ui = '';

    //Determine Link Group
    $link_type_id = 4593; //Transaction Type
    $link_type_ui = '';
    if(!$focus__node && $x__id && !$is_cache){
        foreach($CI->config->item('e___31770') as $x__type1 => $m1){
            if(in_array($i['x__type'], $CI->config->item('n___'.$x__type1))){
                foreach($CI->X_model->fetch(array(
                    'x__id' => $x__id,
                ), array('x__player')) as $linker){
                    $link_type_ui .= '<span class="icon-block-sm">';
                    $link_type_ui .= view_single_select_instant($x__type1, $i['x__type'], $access_level_i, false, $i['i__id'], $x__id);
                    $link_type_ui .= '</span>';
                }
                $link_type_id = $x__type1;
                break;
            }
        }
        if(!$link_type_ui){
            $link_type_ui .= '<span class="icon-block-sm">';
            $link_type_ui .= view_single_select_instant(4593, $i['x__type'], false, false, $i['i__id'], $x__id);
            $link_type_ui .= '</span>';
        }
    }

    foreach($CI->config->item('e___31904') as $x__type_target_bar => $m_target_bar) {

        break;

        //See if missing superpower?
        $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_target_bar['m__following']);
        if(count($superpowers_required) && (!superpower_unlocked(end($superpowers_required)) || $is_cache)){
            continue;
        }

        //Determine hover state:
        if($x__type_target_bar==31770 && !$discovery_mode && $link_type_ui && $superpower_10939){

            //Links
            $bottom_bar_ui .= $link_type_ui;

        } elseif($x__type_target_bar==4362 && !$is_cache && !$discovery_mode && $player_e && isset($i['x__time']) && strtotime($i['x__time']) > 0 && $link_type_ui && ($access_level_i>=3 || ($player_e && $x__player==$i['x__player']))){

            //Link Time / Creator
            $creator_details = '';
            $time_diff = view_time_difference($i['x__time'], true);
            $creator_name = '';
            if($i['x__player'] > 0){
                foreach($CI->E_model->fetch(array(
                    'e__id' => $i['x__player'],
                    'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
                )) as $creator){
                    $creator_name = 'Linked by '.$creator['e__title'].' @'.$creator['e__handle'].' on ';
                    $creator_details = '<a href="'.view_memory(42903,33286).$i['i__hashtag'].'"><span class="icon-block-sm">'.view_cover($creator['e__cover']).'</span></a>';
                }
            }

            $bottom_bar_ui .= '<span class="icon-block-sm"><div class="grey created_time" title="'.$creator_name.date("Y-m-d H:i:s", strtotime($i['x__time'])).' which is '.$time_diff.' ago | ID '.$i['x__id'].'">' . ( $creator_details ? $creator_details : $time_diff ) . '</div></span>';

        } elseif($x__type_target_bar==4737 && !$is_cache && !$discovery_mode && $superpower_10939){

            //Idea Type
            $bottom_bar_ui .= '<span>';
            $bottom_bar_ui .= view_single_select_instant(4737, $i['i__type'], $access_level_i, false, $i['i__id'], $x__id);
            $bottom_bar_ui .= '</span>';

        } elseif($x__type_target_bar==31004 && !$is_cache && !$discovery_mode && $access_level_i>=3 && $superpower_10939){

            //Idea Access
            $bottom_bar_ui .= '<span>';
            $bottom_bar_ui .= view_single_select_instant(31004, $i['i__privacy'], $access_level_i, false, $i['i__id'], $x__id);
            $bottom_bar_ui .= '</span>';

        } elseif($x__type_target_bar==33532 && !$is_cache && $player_e && $access_level_i>=2 && !$is_locked){

            //Reply
            $bottom_bar_ui .= '<span class="mini_button main__title" style="max-width:55px;">';
            $bottom_bar_ui .= '<a href="javascript:void(0);" class="btn btn-sm" onclick="i_editor_load(0,0,'.( $access_level_i>=3 ? 4228 : 30901 ).','.$i['i__id'].')"><span class="icon-block-sm">'.$m_target_bar['m__cover'].'</span>'.( $focus__node && 0 ? $m_target_bar['m__title'] : '' ).'</a>';
            $bottom_bar_ui .= '</span>';

        } elseif(0 && $x__type_target_bar==42819 && !$is_cache && superpower_unlocked(13422) && $access_level_i>=3 && !$is_locked){

            //New Source
            $bottom_bar_ui .= '<span class="mini_button main__title">';
            $bottom_bar_ui .= '<a href="javascript:void(0);" onclick="i_editor_load(0,0,'.( $access_level_i>=3 ? 4228 : 30901 ).','.$i['i__id'].')"><span class="icon-block-sm">'.$m_target_bar['m__cover'].'</span>'.( $focus__node ? $m_target_bar['m__title'] : '' ).'</a>';
            $bottom_bar_ui .= '</span>';

        } elseif($x__type_target_bar==42260 && $player_e && !$is_locked && !$is_cache){

            //Reactions... Check to see if they have any?
            $reactions = $CI->X_model->fetch(array(
                'x__following' => $x__player,
                'x__next' => $i['i__id'],
                'x__type IN (' . join(',', $CI->config->item('n___42260')) . ')' => null, //Reactions
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 1);
            $bottom_bar_ui .= '<span class="mini_button" style="max-width:55px;"><div class="main__title">';
            $bottom_bar_ui .= view_single_select_instant(42260, ( count($reactions) ? $reactions[0]['x__type'] : 0 ), $player_e, 0 && $focus__node, $i['i__id'], ( count($reactions) ? $reactions[0]['x__id'] : 0 ));
            $bottom_bar_ui .= '</div></span>';

        } elseif(0 && $x__type_target_bar==41037 && $focus_i__or && !$is_cache){

            //Selector

        } elseif($x__type_target_bar==4235 && (!$discovery_mode && $i_startable && $access_level_i>=1)){

            //Start
            $bottom_bar_ui .= '<span><a href="'.view_memory(42903,30795).$i['i__hashtag'].'/'.view_memory(6404,4235).'" class="btn btn-sm btn-black"><span class="icon-block-sm">'.$m_target_bar['m__cover'].'</span>'.$m_target_bar['m__title'].'</a></span>';

        } elseif($x__type_target_bar==42924 && $discovery_mode && $focus__node){

            //Next
            $e___6255 = $CI->config->item('e___6255');
            $focus_menu = ( $has_discovered ? $m_target_bar : $e___6255[i__discovery_link($i)] );
            $bottom_bar_ui .= '<span><a href="javascript:void(0);" onclick="go_next(0)" class="btn btn-sm post_button go_next_btn"><span class="icon-block-sm">'.$focus_menu['m__cover'].'</span>'.$focus_menu['m__title'].'</a></span>';

        } elseif($x__type_target_bar==31022 && $discovery_mode && $focus__node && $player_e && !count($x_completes) && !i_required($i)){

            //Skip
            $bottom_bar_ui .= '<span class="mini_button" style="max-width: 75px;"><a href="javascript:void(0);" onclick="go_next(1)" class="btn btn-sm"><span class="icon-block-sm">'.$m_target_bar['m__cover'].'</span>'.$m_target_bar['m__title'].'</a></span>';

        } elseif($x__type_target_bar==31911 && $access_level_i>=3 && !$discovery_mode){

            //Idea Editor
            $bottom_bar_ui .= '<span class="icon-block-sm">';
            $bottom_bar_ui .= '<a href="javascript:void(0);" onclick="i_editor_load('.$i['i__id'].','.$x__id.')" class="icon-block-sm" title="'.$m_target_bar['m__title'].'">'.$m_target_bar['m__cover'].'</a>';
            $bottom_bar_ui .= '</span>';

        } elseif($x__type_target_bar==13909 && $access_level_i>=3 && $has_sortable && !$discovery_mode){

            //Sort Idea
            $bottom_bar_ui .= '<span class="sort_i_frame hidden icon-block-sm">';
            $bottom_bar_ui .= '<span title="'.$m_target_bar['m__title'].'" class="sort_i_grab">'.$m_target_bar['m__cover'].'</span>';
            $bottom_bar_ui .= '</span>';

        } elseif($x__type_target_bar==14980 && !$is_cache && $access_level_i>=1 && !$discovery_mode){

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

                    if($e__id_dropdown==12589 && $access_level_i>=3){

                        //Mass Apply
                        $action_buttons .= '<a href="javascript:void(0);" onclick="x_mass_apply_preview(12589,'.$i['i__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==33286 && $discovery_mode && $access_level_i>=3){

                        //Ideation Mode
                        $action_buttons .= '<a href="'.view_memory(42903,33286).$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==13007 && $access_level_i>=3){

                        //Reset Alphabetic order
                        $action_buttons .= '<a href="javascript:void(0);" onclick="x_reset_sorting()" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==31911 && $access_level_i>=3 && $discovery_mode){

                        //Idea Editor
                        $action_buttons .= '<a href="javascript:void(0);" onclick="i_editor_load('.$i['i__id'].','.$x__id.')" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==10673 && $x__id && $access_level_i>=3){ //!in_array($i['x__type'], $CI->config->item('n___31776')) &&

                        //Unlink
                        $action_buttons .= '<a href="javascript:void(0);" onclick="x_remove('.$x__id.', '.$x__type.',\''.$i['i__hashtag'].'\')" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==30873 && $access_level_i>=3){

                        //Clone Idea Tree:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="i_copy('.$i['i__id'].', 1)" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==33292 && $player_e){

                        //Stats
                        $action_buttons .= '<a href="'.view_app_link(33292).view_memory(42903,33286).$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==29771 && $access_level_i>=3){

                        //Clone Single Idea:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="i_copy('.$i['i__id'].', 0)" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==28636 && $access_level_i>=3 && $x__id){

                        //Transaction Details
                        $action_buttons .= '<a href="'.view_app_link(4341).'?x__id='.$x__id.'" class="dropdown-item main__title" target="_blank">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==42648 && $access_level_i>=3){

                        //Delete Permanently
                        $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                        $action_buttons .= '<a href="javascript:void();" this_id="'.$i['i__privacy'].'" onclick="x_update_instant_select(31004, 6182, '.$i['i__id'].', '.$x__id.', 0)" class="dropdown-item drop_item_instant_31004_'.$i['i__id'].'_'.$x__id.' main__title optiond_6182_'.$i['i__id'].'_'.$x__id.'">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==28637 && isset($i['x__type']) && superpower_unlocked(28727)){

                        //Paypal Details
                        $x__metadata = @unserialize($i['x__metadata']);
                        if(isset($x__metadata['txn_id'])){
                            $action_buttons .= '<a href="https://www.paypal.com/activity/payment/'.$x__metadata['txn_id'].'" class="dropdown-item main__title" target="_blank">'.$anchor.'</a>';
                        }

                    } elseif(in_array($e__id_dropdown, $CI->config->item('n___6287')) && $access_level_i>=3){

                        //Standard button
                        $action_buttons .= '<a href="'.view_app_link($e__id_dropdown).view_memory(42903,33286).$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                    }
                }
            }

            //Any items found?
            if($action_buttons && $focus_dropdown>0){
                //Right Action Menu
                $e___14980 = $CI->config->item('e___14980'); //Dropdowns

                $bottom_bar_ui .= '<span>';
                $bottom_bar_ui .= '<div class="dropdown inline-block">';
                $bottom_bar_ui .= '<button type="button" class="btn no-left-padding no-right-padding icon-block-sm" id="action_menu_i_'.$i['i__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="'.$e___14980[$focus_dropdown]['m__title'].'">'.$e___14980[$focus_dropdown]['m__cover'].'</button>';
                $bottom_bar_ui .= '<div class="dropdown-menu" aria-labelledby="action_menu_i_'.$i['i__id'].'">';
                $bottom_bar_ui .= $action_buttons;
                $bottom_bar_ui .= '</div>';
                $bottom_bar_ui .= '</div>';
                $bottom_bar_ui .= '</span>';

            }
        }
    }

    //Bottom Bar menu
    if(!$focus__node && !$is_locked && !$is_cache ){
        foreach($CI->config->item('e___'.( $discovery_mode ? 42877 : 31890 )) as $e__id_bottom_bar => $m_bottom_bar) {

            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_bottom_bar['m__following']);
            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                continue;
            }

            if(in_array($e__id_bottom_bar, $CI->config->item('n___42376')) && !$player_e){
                //Private content without being a member, so dont even show the counters:
                continue;
            }

            $coins_ui = view_i_covers($e__id_bottom_bar,  $i['i__id'], 0, true, $headline_authors);
            if(strlen($coins_ui)){
                $bottom_bar_ui .= '<span class="hideIfEmpty">';
                $bottom_bar_ui .= $coins_ui;
                $bottom_bar_ui .= '</span>';
            }
        }
    }



    if($bottom_bar_ui ){
        $ui .= '<div class="'.( $focus__node && $discovery_mode ? ' container fixed-bottom hidden ' : '' ).'">';
        $ui .= '<div class="card_covers">';
        $ui .= $bottom_bar_ui;
        $ui .= '</div>';
        $ui .= '</div>';
    }




    $ui .= '</div>';


    return $ui;

}

function view_random_title(){
    $random_cover = random_cover(12279);
    return random_adjective().str_replace('Badger Honey','Honey Badger',str_replace('Black Widow','',ucwords(str_replace('-',' ',one_two_explode('fa-',' ',$random_cover)))));
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



function view_pill($focus__node, $x__type, $counter, $m, $ui = null, $is_open = true){

    return '<script> $(\'.nav-tabs\').append(\'<li class="nav-item thepill'.$x__type.'"><a class="nav-link" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" data-toggle="tooltip" data-placement="top" title="'.number_format($counter, 0).' '.$m['m__title'].( strlen($m['m__message']) ? ': '.str_replace('\'','',str_replace('"','',$m['m__message'])) : '' ).'"><span class="icon-block-xs">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'.view_number($counter) . '</span></a></li>\') </script>' .
        '<div class="headlinebody pillbody hidden headline_body_'.$x__type.'" read-counter="'.$counter.'">'.$ui.'</div>';

}

function view_e_line($e)
{

    $ui = '<a href="'.view_memory(42903,42902).$e['e__handle'].'" class="doblock">';
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
    $access_level_e = access_level_e($e['e__handle'], 0, $e);
    $superpower_10939 = superpower_unlocked(10939);
    $player_e = superpower_unlocked();
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia
    $focus__node = in_array($x__type, $CI->config->item('n___12149')); //NODE COIN
    $is_app = $x__type==6287;
    $href = ( $is_app ? view_app_link($e['e__id']) : view_memory(42903,42902).$e['e__handle'] );
    $cover_is_image = filter_var($e['e__cover'], FILTER_VALIDATE_URL);
    $has_sortable = $x__id > 0 && $access_level_e>=3 && in_array($x__type, $CI->config->item('n___13911'));


    //Source UI
    $ui  = '<div e__id="' . $e['e__id'] . '" e__handle="' . $e['e__handle'] . '" e__privacy="' . $e['e__privacy'] . '" '.( isset($e['x__id']) ? ' x__id="'.$e['x__id'].'" x__privacy="'.$e['x__privacy'].'" ' : '' ).' href="'.$href.'" class="card_cover card_e_cover no-padding card-12274 s__12274_'.$e['e__id'].' '.$extra_class.( $is_app ? ' card-6287 ' : '' ).( $has_sortable ? ' sort_draggable ' : '' ).( $focus__node ? ' focus-cover slim_flat col-md-8 col-sm-10 col-12 ' : ' edge-cover col-sm-4 col-6 '.( strlen($href) ? ' card_click ' : '' ) ).( isset($e['x__id']) ? ' cover_x_'.$e['x__id'].' ' : '' ).'">';

    $ui .= '<div class="cover-wrapper">';

    //Coin Cover
    $ui .= ( !$focus__node ? '<a href="'.$href.'"' : '<div' ).' class="handle_href_e_'.$e['e__id'].' coinType12274 '.( $access_level_e>=3 ? '' : ' ready-only ' ).' black-background-obs cover-link" '.( $cover_is_image ? 'style="background-image:url(\''.$e['e__cover'].'\');"' : '' ).'>';
    $ui .= '<div class="cover-btn ui_e__cover_'.$e['e__id'].'" raw_cover="'.$e['e__cover'].'">'.( !$cover_is_image && $e['e__cover'] ? view_cover($e['e__cover'], true) : '' ).'</div>';
    $ui .= ( !$focus__node ? '</a>' : '</div>' );

    $ui .= '</div>';




    //Title Cover
    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';


    if($access_level_e>=3){
        //Editable:
        $ui .= view_e_input(6197, $e['e__title'], $e['e__id'], $access_level_e, ( isset($e['x__weight']) ? ($e['x__weight']*100)+1 : 0  ), true);
        $ui .= '<div class="hidden text__6197_'.$e['e__id'].'">'.$e['e__title'].'</div>';
    } else {
        //Static:
        $ui .= '<input type="hidden" class="text__6197_'.$e['e__id'].'" value="'.$e['e__title'].'">';
        $ui .= '<div class="center">';
        $ui .= '<span class="main__title text__6197_'.$e['e__id'].'">'.$e['e__title'].'</span>';
        $ui .= '</div>';
    }


    //Source Handle
    $ui .= '<div class="center-block">';

    $ui .= '<div class="creator_headline grey">@<span class="ignore-click ui_e__handle_'.$e['e__id'].'" title="ID '.$e['e__id'].'">'.$e['e__handle'].'</span></div>';

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
        $ui .= view_featured_links($location['x__type'], $location, $e___42777[$location['x__type']], $focus__node);
    }


    if($is_app && isset($e['x__message']) && strlen($e['x__message'])){
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$e['x__message'].'"><i class="far fa-info-circle"></i></span>';
    } else if($x__id && $access_level_e>=3){
        //Main description:
        $ui .= '<div class="x__message_headline grey hideIfEmpty ignore-click ui_x__message_' . $x__id . ( in_array($e['x__type'], $CI->config->item('n___42294')) ? ' hidden ' : '' ) . '">'.htmlentities($e['x__message']).'</div>';
    }

    $ui .= '</div>';



    //Start with Link Note
    $featured_sources = '';


    //Start with top bar:
    if(!$is_app && $access_level_e>=1) {

        //Source Link Groups
        $link_type_id = 0;
        $link_type_ui = '';
        if($x__id){
            foreach($CI->config->item('e___31770') as $x__type1 => $m1){
                if(in_array($e['x__type'], $CI->config->item('n___'.$x__type1))){
                    foreach($CI->X_model->fetch(array(
                        'x__id' => $x__id,
                    ), array('x__player')) as $linker){
                        $link_type_ui .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">';
                        $link_type_ui .= view_single_select_instant($x__type1, $e['x__type'], $access_level_e, false, $e['e__id'], $x__id);
                        $link_type_ui .= '</span>';
                    }
                    $link_type_id = $x__type1;
                    break;
                }
            }
        }

        //Top Bar
        foreach($CI->config->item('e___31963') as $x__type_target_bar => $m_target_bar) {

            //See if missing superpower?
            $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_target_bar['m__following']);
            if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                continue;
            }

            if($x__type_target_bar==31770 && $x__id && $superpower_10939){

                $featured_sources .= $link_type_ui;

            } elseif($x__type_target_bar==6177 && $access_level_e>=3 && $superpower_10939){

                //Source Privacy
                $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">';
                $featured_sources .= view_single_select_instant(6177, $e['e__privacy'], $access_level_e, false, $e['e__id'], $x__id);
                $featured_sources .= '</span>';

            } elseif($x__type_target_bar==42795 && $player_e && $player_e['e__id']!=$e['e__id'] && count($CI->X_model->fetch(array(
                    'x__follower' => $e['e__id'],
                    'x__following' => 4430, //Active Member
                    'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){

                //Allow to follow fellow players:
                $followings = $CI->X_model->fetch(array(
                    'x__following' => $e['e__id'],
                    'x__follower' => $player_e['e__id'],
                    'x__type IN (' . join(',', $CI->config->item('n___42795')) . ')' => null, //Follow
                    'x__type !=' => 10673,
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1, 0, array('x__weight' => 'ASC'));

                if(count($followings) || $access_level_e>=3){
                    $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">'.view_single_select_instant(42795, ( count($followings) ? $followings[0]['x__type'] : 0 ), $player_e && $access_level_e>=3, false, $e['e__id'], ( count($followings) ? $followings[0]['x__id'] : 0 )).'</span>';
                }

            } elseif($x__type_target_bar==31912 && $access_level_e>=3){

                //Edit Source
                $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">';
                $featured_sources .= '<a href="javascript:void(0);" onclick="e_editor_load('.$e['e__id'].','.$x__id.')" class="icon-block-sm" title="'.$m_target_bar['m__title'].'">'.$m_target_bar['m__cover'].'</a>';
                $featured_sources .= '</span>';

            } elseif($x__type_target_bar==41037 && $access_level_e>=3 && !$focus__node){

                //Selector
                $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).' ignore-click">';
                $featured_sources .= '<input class="form-check-input" type="checkbox" value="" e__id="'.$e['e__id'].'" id="selector_e_'.$e['e__id'].'" aria-label="...">';
                $featured_sources .= '</span>';

            } elseif($x__type_target_bar==13006 && $has_sortable && $access_level_e>=3){

                //Sort Source
                $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).' sort_e_frame hidden">';
                $featured_sources .= '<span title="'.$m_target_bar['m__title'].'" class="sort_e_grab">'.$m_target_bar['m__cover'].'</span>';
                $featured_sources .= '</span>';

            } elseif($x__type_target_bar==14980 && $access_level_e>=3){

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

                        } elseif($e__id_dropdown==6287){

                            //App Store
                            if(in_array($e['e__id'], $CI->config->item('n___6287'))){
                                $action_buttons .= '<a href="'.view_app_link($e['e__id']).'" class="dropdown-item main__title">'.$anchor.'</a>';
                            }

                        } elseif($e__id_dropdown==31912 && $access_level_e>=3){

                            //Edit Source
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_editor_load('.$e['e__id'].','.$x__id.')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==29771 && $access_level_e>=3){

                            //Clone:
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_copy('.$e['e__id'].')" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==10673 && $x__id > 0 && $access_level_e>=3 && $superpower_10939){

                            //UNLINK
                            $action_buttons .= '<a href="javascript:void(0);" onclick="e_delete(' . $x__id . ', '.$e['x__type'].')" class="dropdown-item main__title">'.$anchor.'</span></a>';

                        } elseif($e__id_dropdown==42649 && $access_level_e>=3){

                            //Delete Source
                            $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                            $action_buttons .= '<a href="javascript:void();" this_id="'.$e['e__privacy'].'" onclick="x_update_instant_select(6177, 6178, '.$e['e__id'].', '.$x__id.', 0)" class="dropdown-item drop_item_instant_6177_'.$e['e__id'].'_'.$x__id.' main__title optiond_6178_'.$e['e__id'].'_'.$x__id.'">'.$anchor.'</a>';

                        } elseif($e__id_dropdown==13007 && $access_level_e>=3){

                            //Reset Alphabetic order
                            $action_buttons .= '<a href="javascript:void(0);" onclick="x_reset_sorting()" class="dropdown-item main__title">'.$anchor.'</a>';

                        } elseif(in_array($e__id_dropdown, $CI->config->item('n___6287')) && $access_level_e>=3){

                            //Standard button
                            $action_buttons .= '<a href="'.view_app_link($e__id_dropdown).view_memory(42903,42902).$e['e__handle'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                        }
                    }
                }

                //Any items found?
                if($action_buttons && $focus_dropdown>0){
                    //Right Action Menu
                    $e___14980 = $CI->config->item('e___14980'); //Dropdowns

                    $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">';
                    $featured_sources .= '<div class="dropdown inline-block">';
                    $featured_sources .= '<button type="button" class="btn no-left-padding no-right-padding" id="action_menu_e_'.$e['e__id'].'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="'.$e___14980[$focus_dropdown]['m__title'].'">'.$e___14980[$focus_dropdown]['m__cover'].'</button>';
                    $featured_sources .= '<div class="dropdown-menu" aria-labelledby="action_menu_e_'.$e['e__id'].'">';
                    $featured_sources .= $action_buttons;
                    $featured_sources .= '</div>';
                    $featured_sources .= '</div>';
                    $featured_sources .= '</span>';
                }
            }
        }
    }


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
        $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">'.( $social_url && $focus__node ? '<a '.$social_url.' data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</a>' : ( $focus__node ? '<a href="'.view_memory(42903,42902).$e___14036[$social_link['x__following']]['m__handle'].'" data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</a>' : '<span data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</span>' ) ).'</span>';

    }

    if($focus__node){
        $ui .= '<div class="center-block">';
        $ui .= $featured_sources;
        $ui .= '</div>';
    }

    $ui .= '</div>';
    $ui .= '</div>';


    //Bottom Bar
    if(!$is_app && $access_level_e>=1){

        $ui .= '<div class="card_covers hideIfEmpty">';

        if(!$focus__node){

            $ui .= $featured_sources;

            //Also Append bottom bar / main menu:
            foreach($CI->config->item('e___31916') as $e__id_bottom_bar => $m_bottom_bar) {
                $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_bottom_bar['m__following']);
                if(count($superpowers_required) && !superpower_unlocked(end($superpowers_required))){
                    continue;
                }
                if(in_array($e__id_bottom_bar, $CI->config->item('n___42376')) && !$player_e){
                    //Private content without being a member, so dont even show the counters:
                    continue;
                }

                $ui .= '<span class="hideIfEmpty">';
                $ui .= view_e_covers($e__id_bottom_bar,  $e['e__id']);
                $ui .= '</span>';
            }
        }

        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}


function view_e_input($cache_e__id, $current_value, $s__id, $access_level_i, $tabindex = 0, $extra_large = false){

    $CI =& get_instance();
    $e___12112 = $CI->config->item('e___12112');
    $current_value = htmlentities($current_value);
    $name = 'input'.substr(md5($cache_e__id.$current_value.$s__id.$access_level_i.$tabindex), 0, 8);

    //Define element attributes:
    $attributes = ( $access_level_i>=3 ? '' : 'disabled' ).' spellcheck="false" tabindex="'.$tabindex.'" old-value="'.$current_value.'" id="input_'.$cache_e__id.'_'.$s__id.'" class="form-control 
     inline-block editing-mode x_set_class_text text__'.$cache_e__id.'_'.$s__id.( $extra_large?' texttype__lg ' : ' texttype__sm ').' text_e_'.$cache_e__id.'" cache_e__id="'.$cache_e__id.'" s__id="'.$s__id.'" ';

    //Also Append Counter to the end?
    if($extra_large){

        $focus_element = '<textarea name="'.$name.'" placeholder="'.$e___12112[$cache_e__id]['m__title'].'" '.$attributes.'>'.$current_value.'</textarea>';

    } else {

        $focus_element = '<input type="text" name="'.$name.'" data-lpignore="true" placeholder="__" value="'.$current_value.'" '.$attributes.' />';

    }

    return '<span class="span__'.$cache_e__id.' '.( !($access_level_i>=3) ? ' edit-locked ' : '' ).'">'.$focus_element.'</span>';

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

