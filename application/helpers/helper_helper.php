<?php

function includes_any($str, $items)
{
    //Determines if any of the items in array $items includes $str
    foreach($items as $item) {
        if (substr_count($str, $item) > 0) {
            return $item;
        }
    }
    return false;
}


function load_algolia($index_name)
{
    //Loads up algolia search engine functions
    $CI =& get_instance();
    require_once('application/libraries/algoliasearch.php');
    $client = new \AlgoliaSearch\Client($CI->config->item('cred_algolia_app_id'), $CI->config->item('cred_algolia_api_key'));
    return $client->initIndex($index_name);
}

function detect_missing_columns($add_fields, $required_columns, $x__player)
{
    //A function used to review and require certain fields when inserting new rows in DB
    foreach($required_columns as $req_field) {
        if (!isset($add_fields[$req_field]) || strlen($add_fields[$req_field])==0) {
            return true; //Ooops, we're missing this required field
        }
    }

    //No errors found, all good:
    return false; //Not missing anything
}


function second_calc($str){
    $seconds = -1; //Error
    $parts = explode(':',$str);
    if(count($parts)==3 && $parts[0] < 60 && $parts[1] < 60 && $parts[2] < 60){
        //HH:MM:SS
        $seconds = (intval($parts[0]) * 3600) + (intval($parts[1]) * 60) + intval($parts[2]);
    } elseif(count($parts)==2 && $parts[0] < 60 && $parts[1] < 60){
        //MM:SS
        $seconds = (intval($parts[0]) * 60) + intval($parts[1]);
    } elseif(count($parts)==1 && $parts[0] < 60) {
        //SS
        $seconds = intval($parts[0]);
    }
    return $seconds;
}


function is_valid_date($str)
{
    //Determines if the input $str is a valid date
    if (!$str) {
        return false;
    }

    try {
        new \DateTime($str);
        return true;
    } catch (\Exception $e) {
        return false;
    }
}

function target_disccovery(){
    return ( isset($_POST['js_request_uri']) && substr($_POST['js_request_uri'], 0, 1)=='/' && substr_count($_POST['js_request_uri'], '/')==2 ? '/'.strtok(substr($_POST['js_request_uri'], 1), '/') : null );
}
function e_pinned($e__id, $return_itself = false, $first_pin_only = true){

    $CI =& get_instance();
    $pinned_down = $CI->config->item('pinned_down');
    if(isset($pinned_down[$e__id])){
        return ( $first_pin_only ? reset($pinned_down[$e__id]) : $pinned_down[$e__id] );
    }

    $pinned_up = $CI->config->item('pinned_up');
    if(isset($pinned_up[$e__id])){
        return ( $first_pin_only ? reset($pinned_up[$e__id]) : $pinned_up[$e__id] );
    }

    return ( $first_pin_only ? ( $return_itself ? $e__id : 0 ) : array() );

}

function i__discovery_link($i, $trying_to_skip = false){

    if($trying_to_skip){
        return 31022;
    }

    $CI =& get_instance();
    if($i['i__type']==26560){
        $currency_types = $CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
            'x__next' => $i['i__id'],
            'x__following IN (' . join(',', $CI->config->item('n___26661')) . ')' => null, //Currency
        ));
        $total_dues = $CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
            'x__next' => $i['i__id'],
            'x__following' => 26562, //Total Due
        ));
        return ( count($total_dues) && doubleval($total_dues[0]['x__message']) && count($currency_types) ? 26595 : 42332 );
    } else {
        return e_pinned($i['i__type']);
    }

}



function string_is_icon($string){
    return substr_count($string,'fa-');
}
function string_is_emoji($string){
    return preg_match('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', $string);
}


function i__weight_calculator($i){

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        '(x__previous='.$i['i__id'].' OR x__next='.$i['i__id'].')' => null,
    ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

    //Should we update?
    if($count_x[0]['totals'] != $i['i__weight']){
        return $CI->Idea_cache->update($i['i__id'], array(
            'i__weight' => $count_x[0]['totals'],
        ));
    } else {
        return 0;
    }

}

function e__weight_calculator($e){

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        '(x__follower='.$e['e__id'].' OR x__following='.$e['e__id'].' OR x__player='.$e['e__id'].')' => null,
    ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

    //Should we update?
    if($count_x[0]['totals'] != $e['e__weight']){
        return $CI->Source_cache->update($e['e__id'], array(
            'e__weight' => $count_x[0]['totals'],
        ));
    } else {
        return 0;
    }

}


function random_string($length_of_string){
    $characters = '123456789abcdefghijklmnpqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length_of_string; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function update_description($before_string, $after_string){
    return 'Updated from ['.$before_string.'] to ['.$after_string.']';
}

function phone_href($x__type, $number){

    $number = preg_replace("/[^0-9]/", "", $number);

    if($x__type==13815){
        //WhatsApp
        return 'https://wa.me/'.$number;
    } elseif($x__type==20337){
        //Telegram
        return 'https://t.me/'.$number;
    } else {
        //general number:
        return 'tel:'.$number;
    }
}

function random_cover($e__id){
    $CI =& get_instance();
    $fetch = $CI->config->item('e___'.$e__id);
    return trim(one_two_explode('class="','"',$fetch[array_rand($fetch)]['m__cover']));
}

function format_percentage($percent){
    return number_format($percent, ( $percent < 10 ? 1 : 0 ));
}


function new_player_redirect($e__id, $sign_i__hashtag){
    //Is there a redirect app?
    if(strlen($sign_i__hashtag)) {
        return view__memory(42903,33286) . $sign_i__hashtag;
    } elseif(isset($_GET['url'])) {
        return $_GET['url'];
    } else {
        return home_url();
    }
}

function prefix_common_words($strs) {

    $prefix_common_words = array();

    if(count($strs)>=2){

        $prefix_common_words = explode(' ',$strs[0]);

        foreach($strs as $str){

            if(!count($prefix_common_words)){
                break;  //No common words, terminate
            }

            $words = explode(' ',$str);
            foreach($words as $word_count => $word){
                if(!isset($prefix_common_words[$word_count])) {

                    break;

                } elseif($prefix_common_words[$word_count]!=$word){

                    //We have some common words left, continue to remove these words onwards:
                    $total_words = count($prefix_common_words);

                    for($i=$word_count;$i<=$total_words;$i++){
                        if(isset($prefix_common_words[$i])){
                            unset($prefix_common_words[$i]);
                        }
                    }

                    break;  //No common words, terminate
                }
            }
        }
    }

    return ( count($prefix_common_words) ? join(' ',$prefix_common_words).' '  : false );

}


function reset_cache($x__player){
    $CI =& get_instance();
    $count = 0;
    foreach($CI->Mench_ledger->fetch(array(
        'x__type' => 14599, //Cache App
        'x__following IN (' . join(',', $CI->config->item('n___14599')) . ')' => null, //Cache Apps
        'x__time >' => date("Y-m-d H:i:s", (time() - view__memory(6404,14599))),
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    )) as $delete_cahce){
        //Delete email:
        $count += $CI->Mench_ledger->update($delete_cahce['x__id'], array(
            'x__privacy' => 6173, //Transaction Removed
        ), $x__player, 14600 /* Delete Cache */);
    }
    return $count;
}

function filter_array($array, $match_key, $match_value, $return_all = false)
{

    //Searches through $array and attempts to find $array[$match_key] = $match_value
    if (!is_array($array) || count($array) < 1) {
        return false;
    }

    $all_matches = array();
    foreach($array as $key => $value) {
        if (isset($value[$match_key]) && ( is_array($match_value) ? in_array($value[$match_key], $match_value) : $value[$match_key]==$match_value )) {
            if($return_all){
                array_push($all_matches, $value[$match_key]);
            } else {
                return $array[$key];
            }
        }
    }


    if($return_all){

        return $all_matches;

    } else {
        //Could not find it!
        return false;
    }
}

function i_unlockable($i){
    $CI =& get_instance();
    return in_array($i['i__privacy'], $CI->config->item('n___31871') /* ACTIVE */);
}

function i_spots_remaining($i__id){

    $CI =& get_instance();
    $player_e = superpower_unlocked();

    //Any Limits on Selection?
    $spots_remaining = -1; //No limits
    $max_available = $CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
        'x__next' => $i__id,
        'x__following' => 26189,
    ), array(), 1);
    if(count($max_available) && is_numeric($max_available[0]['x__message'])){

        //We have a limit! See if we've met it already:
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'x__previous' => $i__id,
        );
        if($player_e){
            //Do not count current user to give them option to edit & resubmit:
            $query_filters['x__player !='] = $player_e['e__id'];
        }

        //Navigation?
        $must_follow = array();
        foreach($CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 32235, //Navigation
            'x__next' => $i__id,
        )) as $follow){
            array_push($must_follow, $follow['x__following']);
        }

        $current_discoveries = 0;
        if(count($must_follow)){
            //We must qualify each discovery individually:
            foreach($CI->Mench_ledger->fetch($query_filters) as $e){
                if(count($must_follow)==count($CI->Mench_ledger->fetch(array(
                        'x__follower' => $e['x__player'],
                        'x__following IN (' . join(',', $must_follow) . ')' => null,
                        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                    $current_discoveries++;
                }
            }
        } else {
            $query = $CI->Mench_ledger->fetch($query_filters, array(), 1, 0, array(), 'COUNT(x__id) as totals');
            $current_discoveries = $query[0]['totals'];
        }


        $spots_remaining = intval($max_available[0]['x__message'])-$current_discoveries;
        if($spots_remaining < 0){
            $spots_remaining = 0;
        }
    }
    
    return $spots_remaining;
}

function object_to_array($obj) {
    //only process if it's an object or array being passed to the function
    if(is_object($obj) || is_array($obj)) {
        $ret = (array) $obj;
        foreach($ret as &$item) {
            //recursively process EACH element regardless of type
            $item = object_to_array($item);
        }
        return $ret;
    }
    //otherwise (i.e. for scalar values) return without modification
    else {
        return $obj;
    }
}

function i_redirect_url($i){
    $CI =& get_instance();
    if(strlen($i['i__message']) && count($CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
            'x__next' => $i['i__id'],
            'x__following' => 43871, //Redirect URL
        )))){
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $i['i__message'], $match);
        foreach($match[0] as $url){
            if(filter_var($url, FILTER_VALIDATE_URL)){
                return $url;
            }
        }
    }

    return false;
}

function i_popup_url($i){
    $CI =& get_instance();
    foreach($CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
        'x__next' => $i['i__id'],
        'x__following' => 44266, //Popup URL
    )) as $popup_url){
        if(filter_var($popup_url['x__message'], FILTER_VALIDATE_URL)){
            return $popup_url['x__message'];
        }
    }
    return false;
}

function i_required($i){
    $CI =& get_instance();
    return in_array($i['i__type'], $CI->config->item('n___43009')) || count($CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
            'x__next' => $i['i__id'],
            'x__following' => 28239, //Required
        )));
}

function redirect_message($url, $message = null, $log_error = false)
{
    //An error handling function that would redirect member to $url with optional $message
    //Do we have a Message?
    $CI =& get_instance();
    $player_e = superpower_unlocked();

    if ($message) {
        $CI->session->set_flashdata('flash_message', $message);
    }

    if($log_error){
        //Log thie error:
        $CI->Mench_ledger->create(array(
            'x__message' => $url.' '.stripslashes($message),
            'x__type' => 4246, //Platform Bug Reports
            'x__player' => ( $player_e ? $player_e['e__id'] : 0 ),
        ));
    }

    if (!$message) {
        //Do a permanent redirect if message not available:
        header("Location: " . $url, true, 301);
        return false;
    } else {
        header("Location: " . $url, true);
        return false;
    }
}

function session_delete(){
    $CI =& get_instance();
    $CI->session->sess_destroy();
    cookie_delete();
}

function cookie_delete(){
    unset($_COOKIE['auth_cookie']);
    setcookie('auth_cookie', null, -1, '/');
}

function verify_cookie(){

    //Authenticate Cookie:
    $cookie_parts = explode('ABCEFG',$_COOKIE['auth_cookie']);
    $CI =& get_instance();

    $es = $CI->Source_cache->fetch(array(
        'e__id' => $cookie_parts[0],
    ));

    if(count($es) && $cookie_parts[2]==view__hash($cookie_parts[0].$cookie_parts[1])){

        //Assign session & log transaction:
        $CI->Source_cache->activate_session($es[0], false, true);
        return $es[0];

    } else {

        //Cookie was invalid
        cookie_delete();
        return false;

    }

}

function auto_login_player($is_ajax) {

    date_default_timezone_set('America/Los_Angeles');
    @session_start();
    $CI =& get_instance();


    $e_user = false;
    $first_segment = ( $is_ajax && isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : $CI->uri->segment(1));
    $_SERVER['REQUEST_URI'] = ( isset($_POST['js_request_uri']) ? $_POST['js_request_uri'] : @$_SERVER['REQUEST_URI'] );
    $_SERVER['REQUEST_URI'] = ( strlen($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : view__app_link(4269) );
    $player_e = superpower_unlocked();
    $is_login_verified = isset($_GET['e__handle']) && $_GET['e__handle']!='SuccessfulWhale' && isset($_GET['e__hash']) && isset($_GET['e__time']) && ($_GET['e__time']+604800)>time() && strlen($_GET['e__handle']) && view__hash($_GET['e__time'].$_GET['e__handle'])==$_GET['e__hash'];

    if(
        !$player_e //User must not be logged in
        && !array_key_exists(strtolower($first_segment), $CI->config->item('handle___14582'))
        && (isset($_COOKIE['auth_cookie']) || $is_login_verified) //We can auto login with either method:
    ) {


        if($is_login_verified){

            foreach($CI->Source_cache->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $player_e){

                //Login:
                $CI->Source_cache->activate_session($player_e, true);

                //Log them in:
                if(!$is_ajax){
                    header("Location: " . $_SERVER['REQUEST_URI'], true, 307);
                    exit;
                }

            }

        } elseif(isset($_COOKIE['auth_cookie'])) {

            $player_e = verify_cookie();
            if($player_e){
                //Log them in:
                if(!$is_ajax){
                    header("Location: " . $_SERVER['REQUEST_URI'], true, 307);
                    exit;
                }
            }
        }


        //Log them in:
        if(!$is_ajax){
            header("Location: " . view__app_link(4269).( strlen($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '' ), true, 307);
            exit;
        }

    }

    return $player_e;

}

function round_minutes($seconds){
    $minutes = round($seconds/60);
    return ($minutes <= 1 ? 1 : $minutes );
}



function view_tree($i_array){



}


function list_settings($i__hashtag, $fetch_contact = false){

    $CI =& get_instance();
    $e___6287 = $CI->config->item('e___6287'); //APP
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia
    $e___40946 = $CI->config->item('e___40946'); //Source List Controllers
    $list_config = array(); //To compile the settings of this sheet:
    $column_e = array();
    $column_i = array();
    $contact_details = array(
        'full_list' => '',
        'email_list' => '',
        'email_count' => 0,
        'phone_count' => 0,
    );

   foreach($CI->Idea_cache->fetch(array(
       'LOWER(i__hashtag)' => strtolower($i__hashtag),
   )) as $i){

       foreach($e___40946 as $x__type => $m) {
           $list_config[intval($x__type)] = array(); //Assume no links for this type
       }
       //Now search for these settings across sources:
       foreach($CI->Mench_ledger->fetch(array(
           'x__next' => $i['i__id'],
           'x__type IN (' . join(',', $CI->config->item('n___40946')) . ')' => null, //Source List Controllers
           'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
       ), array('x__following'), 0) as $setting_link){
           array_push($list_config[intval($setting_link['x__type'])], intval($setting_link['e__id']));
       }
       //Now search for these settings across ideas:
       foreach($CI->Mench_ledger->fetch(array(
           'x__previous' => $i['i__id'],
           'x__type IN (' . join(',', $CI->config->item('n___40946')) . ')' => null, //Source List Controllers
           'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
       ), array('x__next'), 0) as $setting_link){
           array_push($list_config[intval($setting_link['x__type'])], intval($setting_link['i__id']));
       }


       //Generate filter:
       $query_string_all = array();
       if(count($list_config[40791])){

           //If Discovered Any
           $query_string_all = $CI->Mench_ledger->fetch(array(
               'x__previous IN (' . join(',', $list_config[40791]) . ')' => null,
               'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           ), array('x__player'), 0, 0, array('x__id' => 'DESC'));

       } elseif(count($list_config[27984])){

           //Include If Has ANY
           $query_string_all = $CI->Mench_ledger->fetch(array(
               'x__following IN (' . join(',', $list_config[27984]) . ')' => null,
               'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           ), array('x__follower'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC'));

       } elseif(count($list_config[43513])){

           //Include If Has ALL
           $query_string_all = $CI->Mench_ledger->fetch(array(
               'x__following IN (' . join(',', $list_config[43513]) . ')' => null,
               'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           ), array('x__follower'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC'));

       } else {

           //All Discoveries:
           $query_string_all = $CI->Mench_ledger->fetch(array(
               'x__previous' => $i['i__id'],
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
           ), array('x__player'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC'));

       }

       //Filter list:
       $query_string_filtered = array();
       $unique_users_count = array();
       foreach($query_string_all as $key => $x) {

           if(

               (in_array(intval($x['e__id']), $unique_users_count)) ||

               //Include If Has ANY
               (count($list_config[27984]) && !count($CI->Mench_ledger->fetch(array(
                       'x__follower' => $x['e__id'],
                       'x__following IN (' . join(',', $list_config[27984]) . ')' => null,
                       'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                       'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                   )))) ||

               //Exclude If Has ALL
               (count($list_config[26600]) && count($CI->Mench_ledger->fetch(array(
                       'x__follower' => $x['e__id'],
                       'x__following IN (' . join(',', $list_config[26600]) . ')' => null,
                       'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                       'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                   )))==count($list_config[26600])) ||

               //Exclude If Has ANY
               (count($list_config[43514]) && count($CI->Mench_ledger->fetch(array(
                       'x__follower' => $x['e__id'],
                       'x__following IN (' . join(',', $list_config[43514]) . ')' => null,
                       'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                       'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                   )))>0) ||

               //If Not Discovered Any
               (count($list_config[40793]) && !count($CI->Mench_ledger->fetch(array(
                       'x__player' => $x['e__id'],
                       'x__previous IN (' . join(',', $list_config[40793]) . ')' => null,
                       'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
                       'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                   ))))

           ){

               continue;

           } elseif(count($list_config[43513])){
               //Include If Has ALL
               $total_found_43513 = 0;
               foreach($list_config[43513] as $CI_filter){
                   $total_found_43513 += ( count($CI->Mench_ledger->fetch(array(
                       'x__follower' => $x['e__id'],
                       'x__following' => $CI_filter,
                       'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                       'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                   ))) ? 1 : 0 );
               }
               if($total_found_43513<count($list_config[43513])){
                   continue;
               }
           }

           //Passed all filters:
           array_push($query_string_filtered, $x);
           array_push($unique_users_count, intval($x['e__id']));

       }




       //Determine columns if any:
       if(count($list_config[34513])){

           $column_e = $CI->Mench_ledger->fetch(array(
               'x__following IN (' . join(',', $list_config[34513]) . ')' => null,
               'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
           ), array('x__follower'), 0, 0, sort__e());

           foreach($CI->Mench_ledger->fetch(array(
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
               'x__following IN (' . join(',', $list_config[34513]) . ')' => null,
               'x__next !=' => $i['i__id'],
               'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
           ), array('x__next'), 0, 0, array('x__weight' => 'ASC', 'i__message' => 'ASC')) as $link_i){
               array_push($column_i, $link_i);
           }

       }


       if($fetch_contact){
           foreach($query_string_filtered as $count => $x){

               //Fetch email & phone:
               $fetch_names = $CI->Mench_ledger->fetch(array(
                   'x__following' => 42584, //First Name
                   'x__follower' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               ));
               $fetch_emails = $CI->Mench_ledger->fetch(array(
                   'x__following' => 3288, //Email
                   'x__follower' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               ));
               $fetch_phones = $CI->Mench_ledger->fetch(array(
                   'x__following' => 4783, //Phone
                   'x__follower' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               ));

               $query_string_filtered[$count]['extension_name'] = ( count($fetch_names) && strlen($fetch_names[0]['x__message']) ? $fetch_names[0]['x__message'] : $x['e__title'] );
               $query_string_filtered[$count]['extension_email'] = ( count($fetch_emails) && filter_var($fetch_emails[0]['x__message'], FILTER_VALIDATE_EMAIL) ? $fetch_emails[0]['x__message'] : false );
               $query_string_filtered[$count]['extension_phone'] = ( count($fetch_phones) && strlen($fetch_phones[0]['x__message'])>=10 ? $fetch_phones[0]['x__message'] : false );

               $contact_details['full_list'] .= $query_string_filtered[$count]['extension_name']."\t".$query_string_filtered[$count]['extension_email']."\t".$query_string_filtered[$count]['extension_phone']."\n";


               if($query_string_filtered[$count]['extension_email']){
                   $contact_details['email_count']++;
                   $contact_details['email_list'] .= ( strlen($contact_details['email_list']) ? ", " : '' ).$query_string_filtered[$count]['extension_email'];
               }
               if($query_string_filtered[$count]['extension_phone']){
                   $contact_details['phone_count']++;
               }
           }
       }


       //Append Navigation:
       foreach($column_i as $key => $i_var){
           $must_follow = array();
           foreach($CI->Mench_ledger->fetch(array(
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'x__type' => 32235, //Navigation
               'x__next' => $i_var['i__id'],
           )) as $follow){
               array_push($must_follow, $follow['x__following']);
           }
           $column_i[$key]['must_follow'] = $must_follow;
       }

       return array(
           'i' => $i,
           'list_config' => $list_config,
           'column_e' => $column_e,
           'column_i' => $column_i,
           'query_string_filtered' => $query_string_filtered,
           'contact_details' => $contact_details, //Optional addon
       );
    }
}


function count_link_groups($x__type, $x__time_start = null, $x__time_end = null){

    $CI =& get_instance();
    if(!is_array($CI->config->item('n___'.$x__type))){
        return 0;
    }
    $query_filters = array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___'.$x__type)) . ')' => null,
    );

    if(strtotime($x__time_start) > 0){
        $query_filters['x__time >='] = $x__time_start;
    }
    if(strtotime($x__time_end) > 0){
        $query_filters['x__time <='] = $x__time_end;
    }

    //Fetch Results:
    $query = $CI->Mench_ledger->fetch($query_filters, array(), 1, 0, array(), 'COUNT(x__id) as totals');
    return intval($query[0]['totals']);

}




function home_url(){
    $CI =& get_instance();
    $player_e = superpower_unlocked();
    return ( $player_e ? view__memory(42903,42902).$player_e['e__handle'] : view__memory(42903,14565) );
}

function i_startable($i, $x__player = 0){
    $CI =& get_instance();
    return ( $x__player>0 ? count($CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__player' => $x__player,
        'x__type' => 4235, //Get started
        'x__next' => $i['i__id'],
    ))) : count($CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
        'x__next' => $i['i__id'],
        'x__following' => 4235,
    ))) );
}


function remove_none_utf8($string){
    return preg_replace('/[\x00-\x1F\x7F-\xFF]/', ' ', $string);
}


function superpower_unlocked($superpower_e__id = null, $force_redirect = 0, $session_player_e = false)
{

    if(isset($session_player_e['e__id'])){
        //We have the player!
        return $session_player_e;
    }
    //Authenticates logged-in members with their session information
    $CI =& get_instance();
    $player_e = $CI->session->userdata('session_up');
    $has_session = ( is_array($player_e) && count($player_e) > 0 && $player_e );

    //Let's start checking various ways we can give member access:
    if ($has_session && !$superpower_e__id) {

        //No minimum level required, grant access IF member is logged in:
        return $player_e;

    } elseif ($has_session && in_array($superpower_e__id, $CI->session->userdata('session_superpowers_unlocked'))) {

        //They are part of one of the levels assigned to them:
        return $player_e;

    }

    //Still here?!
    //We could not find a reason to give member access, so block them:
    if (!$force_redirect) {

        return false;

    } else {

        //Block access:
        if($has_session){
            $goto_url = view__memory(42903,42902).$player_e['e__handle'];
        } else {
            $goto_url = view__app_link(4269).( isset($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '' );
        }

        //Now redirect:
        return redirect_message($goto_url, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>'.view__unauthorized_message($superpower_e__id).'</div>');
    }

}

function sort__e(){
    return array(
        'x__weight' => 'ASC', //Applies if sources have been manually sorted
        'x__time' => 'DESC' //Always applies
    );
}

function get_server($var_name){
    return ( isset($_SERVER[$var_name]) ? $_SERVER[$var_name] : null );
}

function html_input_type($data_type){
    $CI =& get_instance();
    $e___42291 = $CI->config->item('e___42291'); //HTML Input Types
    if(isset($e___42291[$data_type]['m__message']) && strlen($e___42291[$data_type]['m__message'])){
        return $e___42291[$data_type]['m__message'];
    } else {
        //Default option:
        return 'text';
    }
}

function js_php_redirect($url, $timer = 0){
    echo '<script> $(document).ready(function () { js_redirect(\''.$url.'\', '.$timer.'); }); </script>';
}

function js_reload($timer = 1){
    echo '<script> $(document).ready(function () { setTimeout(function () { location.reload(true); }, '.$timer.'); }); </script>';
}


function generate_handle($focus__node, $str, $suggestion = null, $increment = 1){

    //Generates a Suitable Handle from the title:
    $CI =& get_instance();

    //Previous suggestion did not work, let's tweak and try again:
    $max_allowed_length = view__memory(6404,41985);
    $max_adj_length = $max_allowed_length - 3; //Reduce target_element to give space for $increment extension up to 99999
    $recommended_length = $max_allowed_length/2;

    if(strlen($suggestion)){

        //Previous suggestion that was a duplicate, so it needs to be modified:
        if(strlen($suggestion)>$max_adj_length){
            $suggestion = substr($suggestion, 0, $max_adj_length);
        }
        $suggestion = ($increment==1 ? $suggestion : substr($suggestion, 0, -strlen($increment)) ).$increment;
        $increment++;

    } else {

        //Create new suggestion from string:
        $str = preg_replace("/[^A-Za-z0-9]/", "", $str);
        if(strlen($str)>$max_allowed_length){
            //Shorten and remove the last word:
            $word_arr = explode(' ', substr($str, 0, $max_allowed_length));
            unset($word_arr[count($word_arr)-1]);
            $str = join(' ',$word_arr);
        }
        $suggestion = preg_replace("/[^A-Za-z0-9]/", '', $str);

    }

    if(strlen($suggestion)<4 || is_numeric($suggestion)){
        $suggestion = ( $focus__node==12273 ? 'Idea' : 'Source' ).$suggestion;
    }


    //Make sure no duplicates:
    if($focus__node==12273 && count($CI->Idea_cache->fetch(array(
            'LOWER(i__hashtag)' => strtolower($suggestion),
        )))){
        return generate_handle(12273, $str, $suggestion, $increment);
    } elseif($focus__node==12274 && count($CI->Source_cache->fetch(array(
            'LOWER(e__handle)' => strtolower($suggestion),
        )))){
        return generate_handle(12274, $str, $suggestion, $increment);
    } else {
        //All good:
        return $suggestion;
    }

}


function process_media($i__id, $uploaded_media){

    $CI =& get_instance();
    $player_e = superpower_unlocked();

    //Update Media...
    $media_stats = array(
        'media_e__cover' => null,
        'total_current' => 0,
        'total_submitted' => 0,
        'adjust_created' => 0,
        'adjust_duplicated' => 0,
        'adjust_updated' => 0,
        'adjust_removed' => 0,
        'total_media' => 0,
    );


    if(!$player_e){
        return $media_stats;
    }

    $full_media = array();
    $current_media_e__ids = array();
    $sort_count = 0;

    //Fetch current media:
    foreach($CI->Mench_ledger->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___42294')) . ')' => null, //Media
        'x__next' => $i__id,
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
    ), array('x__following'), 0, 0, array('x__weight' => 'ASC')) as $media){
        $media_stats['total_current']++;
        $current_media_e__ids[$sort_count] = intval($media['x__following']);
        $full_media[$media['x__following']] = $media;
        $sort_count++;
    }

    //Fetch submitted media:
    $upload_media_e__ids = array();
    if(count($uploaded_media)>0){

        //We have media to process:
        $sort_count = 0; //Reset sorting to compare to submitted media...
        foreach($uploaded_media as $upload_media){

            if($upload_media['e__id']>0){

                $adjust_updated = false;

                //Update media order?
                if($current_media_e__ids[$sort_count]!=$upload_media['e__id']){
                    //Order has changed, update it:
                    $adjust_updated = true;
                    $CI->Mench_ledger->update($full_media[$upload_media['e__id']]['x__id'], array(
                        'x__weight' => $sort_count,
                    ), $player_e['e__id'], 13006 /* SOURCE SORT MANUAL */);
                }

                //Update the source title?
                $validate_e__title = validate_e__title($upload_media['e__title']);
                if($validate_e__title['status'] && $full_media[$upload_media['e__id']]['e__title']!=$upload_media['e__title']){
                    $adjust_updated = true;
                    $CI->Source_cache->update($upload_media['e__id'], array(
                        'e__title' => trim($upload_media['e__title']),
                    ), true, $player_e['e__id']);
                }

                $media_stats['media_e__cover'] = $upload_media['e__cover'];

                if($adjust_updated){
                    $media_stats['adjust_updated']++;
                }

            } else {

                //Adding new media...
                //Search eTag to see if we already have it:
                $etag_detected = false;
                if(isset($upload_media['media_cache']['etag']) && strlen($upload_media['media_cache']['etag'])){
                    //We we already have this asset, link to that source without giving this new source the authority over it...
                    //First person to upload a source will get authority over its created source...
                    foreach($CI->Mench_ledger->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__following' => 42662, //etag
                        'x__message' => $upload_media['media_cache']['etag'],
                    ), array('x__follower'), 1) as $existing_media){
                        $media_stats['adjust_duplicated']++;
                        $upload_media['e__id'] = $existing_media['e__id'];
                        $etag_detected = true;
                    }
                }

                if(!$upload_media['e__id']){

                    $media_stats['media_e__cover'] = $upload_media['e__cover'];

                    //Create Source for this new media:
                    $added_e = $CI->Source_cache->verify_create($upload_media['e__title'], $player_e['e__id'], ( $upload_media['media_e__id']==4259 /* Audio has no thumbnail! */ ? 'far fa-volume-up' : $upload_media['e__cover'] ), true);
                    if(!$added_e['status']){
                        $CI->Mench_ledger->create(array(
                            'x__type' => 4246, //Platform Bug Reports
                            'x__message' => 'Failed to create a new source for ['.$upload_media['e__title'].'] with cover ['.$upload_media['e__cover'].']',
                            'x__metadata' => array(
                                'submitted_media' => $upload_media,
                                'post' => $_POST,
                            ),
                        ));
                        continue;
                    }

                    //Create new media and assign ID:
                    $media_stats['adjust_created']++;
                    $upload_media['e__id'] = $added_e['new_e']['e__id'];

                    //new asset, create new source and insert tags...
                    $e___32088 = $CI->config->item('e___32088'); //Platform Variables
                    foreach($CI->config->item('e___42679') as $x__type => $m) {

                        //Ensure variable name exists so we can check the API call:
                        $target_variable = false;
                        if(isset($e___32088[$x__type]['m__message'])){
                            //Determine if variable exists...
                            if(in_array($x__type, $CI->config->item('n___42763')) && isset($upload_media['media_cache']['video'][$e___32088[$x__type]['m__message']])){
                                //Video info:
                                $target_variable = $upload_media['media_cache']['video'][$e___32088[$x__type]['m__message']];
                            } elseif(in_array($x__type, $CI->config->item('n___42675')) && isset($upload_media['media_cache']['audio'][$e___32088[$x__type]['m__message']])){
                                //Audio info:
                                $target_variable = $upload_media['media_cache']['audio'][$e___32088[$x__type]['m__message']];
                            } elseif(isset($upload_media['media_cache'][$e___32088[$x__type]['m__message']])) {
                                //Media info:
                                $target_variable = $upload_media['media_cache'][$e___32088[$x__type]['m__message']];
                            }
                        }
                        if(!strlen($target_variable) || $target_variable=='0'){
                            //This variable does not have a value, move on...
                            continue;
                        }

                        //We have a variable, see what it is...
                        if(in_array($x__type, $CI->config->item('n___33331'))){

                            //Single select that needs auto creation of sources if missing:
                            $child_id = 0;
                            foreach($CI->Mench_ledger->fetch(array(
                                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'x__following' => $x__type,
                                'e__title' => $target_variable,
                            ), array('x__follower'), 1, 0, array('x__id' => 'ASC')) as $child_source){
                                $child_id = $child_source['e__id'];
                            }

                            //If not found create the child:
                            if(!$child_id){
                                $added_child = $CI->Source_cache->verify_create($target_variable, 14068);
                                if(!$added_child['status']){
                                    $CI->Mench_ledger->create(array(
                                        'x__type' => 4246, //Platform Bug Reports
                                        'x__message' => 'Failed to create a new source for ['.$target_variable.']',
                                        'x__metadata' => array(
                                            'submitted_media' => $upload_media,
                                            'post' => $_POST,
                                        ),
                                    ));
                                    continue;
                                }

                                //Add links for this new source:
                                $CI->Mench_ledger->create(array(
                                    'x__player' => $player_e['e__id'],
                                    'x__following' => $x__type,
                                    'x__follower' => $added_child['new_e']['e__id'],
                                    'x__type' => 4251,
                                ));

                                //Assign child source:
                                $child_id = $added_child['new_e']['e__id'];

                            }

                            if($child_id){
                                //Child source found, simply link:
                                $CI->Mench_ledger->create(array(
                                    'x__player' => $player_e['e__id'],
                                    'x__following' => $child_id,
                                    'x__follower' => $upload_media['e__id'],
                                    'x__type' => 4251,
                                ));
                            }

                        } else {

                            //Save variable as is:
                            $CI->Mench_ledger->create(array(
                                'x__player' => $player_e['e__id'],
                                'x__following' => $x__type,
                                'x__follower' => $upload_media['e__id'],
                                'x__message' => $target_variable,
                                'x__type' => 4251,
                            ));

                        }
                    }
                }


                //By now have the media source, create necessary links:
                if($upload_media['e__id'] && $upload_media['media_e__id']){

                    //Link to Idea:
                    if(!count($CI->Mench_ledger->fetch(array(
                        'x__next' => $i__id,
                        'x__following' => $upload_media['e__id'],
                        'x__type' => $upload_media['media_e__id'],
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $CI->Mench_ledger->create(array(
                            'x__player' => $player_e['e__id'],
                            'x__next' => $i__id,
                            'x__following' => $upload_media['e__id'],
                            'x__type' => $upload_media['media_e__id'],
                            'x__message' => $upload_media['playback_code'],
                            'x__weight' => $sort_count,
                        ));
                    }


                    //Link to Source as Uploader:
                    if(!count($CI->Mench_ledger->fetch(array(
                        'x__following' => $player_e['e__id'],
                        'x__follower' => $upload_media['e__id'],
                        'x__type IN (' . join(',', $CI->config->item('n___42657')) . ')' => null, //Uploads
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $CI->Mench_ledger->create(array(
                            'x__player' => $player_e['e__id'],
                            'x__following' => $player_e['e__id'],
                            'x__follower' => $upload_media['e__id'],
                            'x__type' => ( $etag_detected ? 42849 : 42659 ), //Reupload vs Upload
                            'x__message' => $upload_media['playback_code'],
                        ));
                    }


                    //Link to Media Type:
                    if(!count($CI->Mench_ledger->fetch(array(
                        'x__following' => $upload_media['media_e__id'],
                        'x__follower' => $upload_media['e__id'],
                        'x__type' => 4251,
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $CI->Mench_ledger->create(array(
                            'x__player' => $player_e['e__id'],
                            'x__following' => $upload_media['media_e__id'],
                            'x__follower' => $upload_media['e__id'],
                            'x__type' => 4251,
                            'x__metadata' => $upload_media,
                        ));
                    }

                }
            }

            //Add this to the submitted ones:
            $upload_media_e__ids[$sort_count] = $upload_media['e__id'];
            $media_stats['total_submitted']++;
            $sort_count++;

        }
    }

    //Remove current media missing from submitted (Removed during editing):
    foreach(array_diff($current_media_e__ids, $upload_media_e__ids) as $deleted_media_e__id){
        $media_stats['adjust_removed']++;
        $CI->Mench_ledger->update($full_media[$deleted_media_e__id]['x__id'], array(
            'x__privacy' => 6173, //Transaction Removed
        ), $player_e['e__id'], 42694); //Media Removed
    }

    //Calculate total media:
    $media_stats['total_media'] = $media_stats['total_current'] + $media_stats['adjust_duplicated'] + $media_stats['adjust_created'] - $media_stats['adjust_removed'];

    return $media_stats;

}


function append_source($x__following, $x__player, $x__message, $i__id){

    $CI =& get_instance();

    //First validate data type to ensure it matches:
    foreach($CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__following IN (' . join(',', $CI->config->item('n___4592')) . ')' => null, //Data Types
        'x__follower' => $x__following,
    )) as $data_type) {
        $data_type_validate = data_type_validate($data_type['x__following'], $x__message);
        if (!$data_type_validate['status']) {
            //It's not the data type needed:
            return false;
        }
    }

    //Now check existing links:
    $existing_x = $CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type' => 4251, //SOURCE LINKS
        'x__following' => $x__following,
        'x__follower' => $x__player,
    ));

    if(count($existing_x)){

        //Transaction previously exists, see if content value is the same:
        if(strtolower($existing_x[0]['x__message'])==strtolower($x__message)){
            //Everything is the same, nothing to do here:
            return false;
        }

        //Content value has changed, update the transaction:
        $CI->Mench_ledger->update($existing_x[0]['x__id'], array(
            'x__message' => $x__message,
        ), $x__player, 10657 /* SOURCE LINK CONTENT UPDATE  */);

    } else {

        //Create transaction:
        $CI->Mench_ledger->create(array(
            'x__type' => 4251, //Follow Source
            'x__message' => $x__message,
            'x__player' => $x__player,
            'x__following' => $x__following,
            'x__follower' => $x__player,
        ));

    }

    $CI->Mench_ledger->create(array(
        'x__type' => 12197, //Following Added
        'x__player' => $x__player,
        'x__following' => $x__following,
        'x__follower' => $x__player,
        'x__previous' => $i__id,
        'x__message' => $x__message,
    ));

    return true;

}

function data_type_validate($data_type, $data_value, $data_title = null){

    $CI =& get_instance();
    $e___4592 = $CI->config->item('e___4592'); //Data types

    if($data_type==4319 && !is_numeric($data_value)){
        //Number:
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a valid '.$e___4592[$data_type]['m__title'],
        );
    } elseif($data_type==42181 && ( strlen(preg_replace('/[^0-9]/', '', $data_value))<10 || strlen(preg_replace('/[^0-9]/', '', $data_value))>14 )){
        //Phone Number:
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a valid '.$e___4592[$data_type]['m__title'].' with 10-14 numbers including country code.',
        );
    } elseif($data_type==4318 && !strtotime($data_value)){
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a valid '.$e___4592[$data_type]['m__title'],
        );
    } elseif($data_type==4255 && !strlen($data_value)){
        //Text:
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a valid '.$e___4592[$data_type]['m__title'],
        );
    } elseif($data_type==32097 && !filter_var($data_value, FILTER_VALIDATE_EMAIL)){
        //Email:
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a valid '.$e___4592[$data_type]['m__title'],
        );
    } elseif($data_type==42947 && (!is_numeric($data_value) || $data_value<0 || $data_value>1)){
        //Percentage:
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a number between 0.00 & 1.00.',
        );
    } elseif(in_array($data_type, $CI->config->item('n___42189')) && !filter_var($data_value, FILTER_VALIDATE_URL)){
        //URL:
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a valid '.$e___4592[$data_type]['m__title'],
        );
    } elseif(in_array($data_type, $CI->config->item('n___42188'))){
        //Single Choice of Multi Choice source types should not be validated here
        $CI->Mench_ledger->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'data_type_validate() was asked to validate choice options for @'.$data_type.' ['.$data_value.'] ['.$data_title.']',
        ));
    }

    //All good:
    return array(
        'status' => 1,
        'message' => 'Good',
    );

}


function data_type_format($data_type, $data_value){

    $CI =& get_instance();

    if(in_array($data_type, $CI->config->item('n___4318')) && strtotime($data_value)>0){
        //Format Time:
        return date(view__memory(6404,4318), strtotime($data_value));
    }

    //No special formatting needed:
    return $data_value;

}

function change_handle($old_handle){
    $max_length = view__memory(6404,41985);
    if(strlen($old_handle) < $max_length){
        //We have some room to change:
        return substr($old_handle.rand(100000,999999), 0, $max_length);
    } else {
        //No room to change, remove some words from the end:
        return substr($old_handle, 0, ($max_length-6)).rand(100000,999999);
    }
}

function sort_by($e__id, $custom_sort = array()){

    $CI =& get_instance();
    $order_by = array();
    foreach($CI->config->item('e___'.$e__id) as $x__sort_id => $sort) {
        $order_by['x__following = \''.$x__sort_id.'\' DESC'] = null;
    }

    if(is_array($custom_sort)){
        return array_merge($order_by, $custom_sort);
    } else {
        return $order_by;
    }
}

function sync_handle_references($e, $new_handle_string){

    if($e['e__handle']==$new_handle_string){
        return false; //Nothing changed...
    }

    //Update Handles everywhere they are referenced:
    $CI =& get_instance();
    foreach ($CI->Mench_ledger->fetch(array(
        'x__following' => $e['e__id'],
        'x__type' => 31835, //Source Mention
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array('x__next')) as $ref) {
        view__sync_links(str_replace('@'.$e['e__handle'], '@'.$new_handle_string, $ref['i__message']), true, $ref['i__id']);
    }
    return $new_handle_string;
}

function validate_update_handle($str, $i__id = null, $e__id = null){

    $CI =& get_instance();
    $player_e = superpower_unlocked();

    //Validate:
    if(($i__id && $e__id) || (!$i__id && !$e__id)){

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Must set either Idea or Source ID! Pick one',
        );

    } elseif(!strlen($str)){

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Missing Handle',
        );

    } elseif (!ctype_alnum($str)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Hashtag Can only contain alphanumneric numbers and letters',
        );

    } elseif (!preg_match('/[a-zA-Z]/', $str)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Hashtag Must contain at-least one letter between A-Z',
        );

    } elseif (strlen($str) > view__memory(6404,41985)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Hashtag Must be '.view__memory(6404,41985).' characters or less',
        );

    } elseif ($i__id && array_key_exists(strtolower($str), $CI->config->item('handle___6287'))) {

        return array(
            'status' => 0,
            'db_duplicate' => 1,
            'message' => 'Hashtag "'.$str.'" already in use.',
        );

    }

    //Syntax good! Now let's check the DB for duplicates
    if($i__id > 0){
        foreach($CI->Idea_cache->fetch(array(
            'i__id !=' => $i__id,
            'LOWER(i__hashtag)' => strtolower($str),
        ), 0) as $matched){
            //Is it active?
            if(!in_array($matched['i__privacy'], $CI->config->item('n___31871')) && $player_e){

                //Since not active we can replace this:
                $CI->Idea_cache->update($matched['i__id'], array(
                    'i__hashtag' => change_handle($matched['i__hashtag']),
                ), true, $player_e['e__id']);

            } else {
                return array(
                    'status' => 0,
                    'db_duplicate' => 1,
                    'message' => 'Hashtag "'.$str.'" already in use.',
                );
            }
        }
    } elseif($e__id>0){

        foreach($CI->Source_cache->fetch(array(
            'e__id !=' => $e__id,
            'LOWER(e__handle)' => strtolower($str),
        ), 0) as $matched){
            //Is it active?
            if(!in_array($matched['e__privacy'], $CI->config->item('n___7358')) && $player_e){

                //Since not active we can replace this:
                $CI->Source_cache->update($matched['e__id'], array(
                    'e__handle' => change_handle($matched['e__handle']),
                ), true, $player_e['e__id']);

            } else {
                return array(
                    'status' => 0,
                    'db_duplicate' => 1,
                    'message' => 'Hashtag "'.$str.'" already in use.',
                );
            }
        }

    }


    //All good, return success:
    return array(
        'status' => 1,
        'db_duplicate' => 0,
        'message' => 'Success',
    );

}


function validate_e__title($str){

    //Validate:
    $title_clean = trim($str);
    while(substr_count($title_clean , '  ') > 0){
        $title_clean = str_replace('  ',' ',$title_clean);
    }

    if(!strlen(trim($str))){

        return array(
            'status' => 0,
            'message' => 'Source title missing',
        );

    } elseif(strlen(trim($str)) < 1){

        return array(
            'status' => 0,
            'message' => 'Enter Source title to continue.',
        );

    } elseif (strlen($str) > view__memory(6404,6197)) {

        return array(
            'status' => 0,
            'message' => 'Source title must be '.view__memory(6404,6197).' characters or less',
        );

    }

    //All good, return success:
    return array(
        'status' => 1,
        'e__title_clean' => trim($title_clean),
    );

}

function number_x__weight($str){
    //Set x__weight for caching purposes if message value is numerical:
    if($str!=0 && is_numeric($str)){
        return intval($str);
    } elseif($str!=0 && is_double($str)){
        return doubleval($str);
    } elseif(strtotime($str) > 0){
        return strtotime($str);
    } else {
        return 0;
    }
}

function delete_all_between($beginning, $end, $string) {
    $beginningPos = strpos($string, $beginning);
    $endPos = strpos($string, $end);
    if ($beginningPos === false || $endPos === false) {
        return $string;
    }

    $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

    return delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
}

function user_website($x__player){
    $CI =& get_instance();
    foreach($CI->Mench_ledger->fetch(array(
        'x__follower' => $x__player,
        'x__type' => 4251, //New Source Created
    ), array(), 1) as $e_created){
        return $e_created['x__website'];
    }
    foreach($CI->Mench_ledger->fetch(array(
        'x__player' => $x__player,
    ), array(), 1) as $e_created){
        return $e_created['x__website'];
    }
    return 0;
}


function clean_phone($phone){
    $phone_numbers = preg_replace('/\D/', '', $phone);
    if(strlen($phone_numbers)==10){
        $phone_numbers = '+1'.$phone_numbers;
    }
    return $phone_numbers;
}

function random_adjective(){

    $adjectives = array('Amazing', 'Awesome', 'Adventurous', 'Ambitious', 'Adorable', 'Artistic', 'Agile', 'Acrobatic', 'Attractive', 'Alluring', 'Astonishing', 'Authentic', 'Awkward', 'Ancient', 'American', 'Australian', 'Austrian', 'African', 'Asian', 'Brave', 'Beautiful', 'Bright', 'Busy', 'Big', 'Bold', 'Basic', 'Blissful', 'Bouncy', 'Beneficial', 'Bashful', 'Black', 'Brown', 'Burgundy', 'Broad', 'British', 'Belgian', 'Brazilian', 'Creative', 'Confident', 'Cheerful', 'Calm', 'Cute', 'Clever', 'Curious', 'Charming', 'Courageous', 'Clean', 'Cool', 'Considerate', 'Caring', 'Crazy', 'Classic', 'Chic', 'Cloudy', 'Colombian', 'Chinese', 'Delightful', 'Dreamy', 'Daring', 'Dynamic', 'Dark', 'Decent', 'Drastic', 'Defiant', 'Dedicated', 'Deep', 'Desirable', 'Dirty', 'Dramatic', 'Dizzy', 'Demanding', 'Diligent', 'Dutch', 'Danish', 'Delicious', 'Dazzling', 'Easy', 'Elegant', 'Enthusiastic', 'Eager', 'Efficient', 'Empathetic', 'Excellent', 'Exciting', 'Effective', 'Extravagant', 'Entertaining', 'Exotic', 'Expressive', 'Expensive', 'Elaborate', 'European', 'Egyptian', 'Eastern', 'Elderly', 'Educational', 'Fantastic', 'Fabulous', 'Friendly', 'Funny', 'Fearless', 'Fresh', 'Fascinating', 'Fluffy', 'Fierce', 'Fine', 'Free', 'Frugal', 'French', 'Futuristic', 'Fast', 'Flat', 'Famous', 'Flawless', 'Formal', 'Frizzy', 'Gorgeous', 'Great', 'Gentle', 'Generous', 'Gracious', 'Genuine', 'Glorious', 'Graceful', 'Golden', 'Grand', 'Green', 'Growing', 'Groovy', 'Greek', 'Grumpy', 'Gothic', 'Gargantuan', 'Gigantic', 'German', 'Georgian', 'Happy', 'Hot', 'Humble', 'Honest', 'Healthy', 'Heavy', 'Handsome', 'High', 'Helpful', 'Hilarious', 'Heavenly', 'Harmonious', 'Hardworking', 'Historical', 'Heartfelt', 'Homey', 'Hungry', 'Huge', 'Hispanic', 'Hindu', 'Interesting', 'Intelligent', 'Incredible', 'Inspiring', 'Impressive', 'Imaginative', 'Inquisitive', 'Iconic', 'Indigo', 'Industrious', 'Inevitable', 'Inexpensive', 'Incomparable', 'Idealistic', 'Illustrious', 'Indian', 'Italian', 'Irresistible', 'Irrelevant', 'Icy', 'Joyful', 'Jolly', 'Jovial', 'Jaunty', 'Jaded', 'Jazzy', 'Jumpy', 'Juicy', 'Judgmental', 'Jumbled', 'Japanese', 'Javanese', 'Jewish', 'Jittery', 'Junior', 'Justified', 'Jubilant', 'Jade', 'Jumbo', 'Joint', 'Kind', 'Knowledgeable', 'Keen', 'Kooky', 'Knotty', 'Kinetic', 'Known', 'Keen-eyed', 'Knightly', 'Keen-witted', 'Kempt', 'Knockout', 'Knackered', 'Kindhearted', 'Kenyan', 'Kiddy', 'Knotted', 'Kyrgyzstani', 'Kindred', 'Kentuckian', 'Loud', 'Lively', 'Lazy', 'Loyal', 'Long', 'Lonely', 'Lovely', 'Large', 'Light', 'Low', 'Luxurious', 'Lasting', 'Literal', 'Learned', 'Lucky', 'Magnificent', 'Mysterious', 'Modern', 'Moody', 'Musical', 'Mighty', 'Masculine', 'Mesmerizing', 'Mindful', 'Memorable', 'Multicultural', 'Moral', 'Majestic', 'Mischievous', 'Mouthwatering', 'Mellow', 'Modest', 'Magical', 'Melodic', 'Mature', 'Nervous', 'Natural', 'New', 'Nice', 'Noble', 'Naughty', 'Neat', 'Nonchalant', 'Noisy', 'Narrow', 'Nostalgic', 'Needy', 'Negative', 'Nutritious', 'Nonstop', 'Noteworthy', 'Numerous', 'Notable', 'Nurturing', 'Nifty', 'Obvious', 'Original', 'Optimistic', 'Ordinary', 'Official', 'Outstanding', 'Open', 'Organic', 'Odd', 'Observant', 'Obedient', 'Opaque', 'Obsolete', 'Offensive', 'Oily', 'Old-fashioned', 'Ornate', 'Onyx', 'Overwhelming', 'Oceanic', 'Perfect', 'Patient', 'Positive', 'Powerful', 'Popular', 'Polite', 'Peaceful', 'Playful', 'Pleasant', 'Precious', 'Practical', 'Private', 'Proud', 'Profound', 'Pretty', 'Painful', 'Priceless', 'Puzzled', 'Persistent', 'Passionate', 'Quaint', 'Quick', 'Quiet', 'Quirky', 'Quizzical', 'Queenly', 'Quivering', 'Quotable', 'Qualified', 'Quantifiable', 'Questionable', 'Quarrelsome', 'Queasy', 'Quenched', 'Quack', 'Quilted', 'Quizzing', 'Reliable', 'Responsible', 'Romantic', 'Rich', 'Rude', 'Real', 'Radiant', 'Royal', 'Rough', 'Respectful', 'Red', 'Rational', 'Rustic', 'Radiant', 'Robust', 'Rare', 'Resilient', 'Reckless', 'Ready', 'Rambunctious', 'Strong', 'Smart', 'Serious', 'Sad', 'Special', 'Simple', 'Super', 'Sincere', 'Safe', 'Stunning', 'Sweet', 'Shy', 'Successful', 'Satisfied', 'Shiny', 'Silent', 'Sparkling', 'Strong-willed', 'Scary', 'Surprised', 'Tall', 'Talkative', 'Tasty', 'Tender', 'Terrific', 'Terrible', 'Thoughtful', 'Thrifty', 'Timely', 'Tough', 'Traditional', 'Trustworthy', 'Tremendous', 'Tricky', 'Tolerant', 'Tenacious', 'Tiny', 'Tired', 'Top', 'Trembling', 'Ugly', 'Ultimate', 'Unbelievable', 'Uncertain', 'Uncommon', 'Unconditional', 'Unconscious', 'Understanding', 'Unforgettable', 'Unhappy', 'Unique', 'United', 'Universal', 'Unusual', 'Upbeat', 'Uplifting', 'Urbane', 'Urgent', 'Useful', 'Useless', 'Valuable', 'Vague', 'Valid', 'Vast', 'Various', 'Vengeful', 'Vibrant', 'Victorious', 'Vigorous', 'Villainous', 'Vital', 'Vivacious', 'Vocal', 'Volatile', 'Volcanic', 'Voracious', 'Vulnerable', 'Vicious', 'Velvet', 'Verbal', 'Warm', 'Wild', 'Witty', 'Wise', 'Wonderful', 'Worried', 'Wondrous', 'Wealthy', 'Whimsical', 'Wicked', 'Wide', 'Wavy', 'Watery', 'Weighty', 'Wooden', 'Weak', 'Wary', 'Winning', 'Well-groomed', 'Wholesome', 'Xeric', 'Xerophytic', 'Xerotic', 'Xyloid', 'Xylonic', 'Xylophagous', 'Xanthic', 'Xanthous', 'Xerarch', 'Xylotomous', 'Xerographic', 'Xenial', 'Xenogenetic', 'Xenolithic', 'Xylophilous', 'Yellow', 'Young', 'Yielding', 'Yearly', 'Yummy', 'Yawning', 'Yucky', 'Yearning', 'Yeasty', 'Yielding', 'Youthful', 'Yare', 'Yclept', 'Yellowish', 'Yearlong', 'Youth', 'Zealous', 'Zesty', 'Zigzag', 'Zillionth', 'Zinciferous', 'Zingy', 'Zippered', 'Zippy', 'Zoological', 'Zonal', 'Ambitious', 'Amiable', 'Analytical', 'Assertive', 'Authentic', 'Bold', 'Calm', 'Charismatic', 'Charming', 'Cheerful', 'Compassionate', 'Confident', 'Conscientious', 'Considerate', 'Creative', 'Curious', 'Dependable', 'Diligent', 'Disciplined', 'Easygoing', 'Empathetic', 'Enthusiastic', 'Extraverted', 'Flexible', 'Friendly', 'Generous', 'Genuine', 'Gracious', 'Hardworking', 'Honest', 'Humble', 'Independent', 'Innovative', 'Insightful', 'Intelligent', 'Kind', 'Logical', 'Loyal', 'Open-minded', 'Optimistic', 'Outgoing', 'Passionate', 'Patient', 'Persistent', 'Practical', 'Rational', 'Reliable', 'Resourceful', 'Responsible', 'Self-confident', 'Happy', 'Sad', 'Angry', 'Fearful', 'Anxious', 'Excited', 'Frustrated', 'Nostalgic', 'Hopeful', 'Envious', 'Jealous', 'Empathetic', 'Curious', 'Surprised', 'Disappointed', 'Grateful', 'Confused', 'Content', 'Lonely', 'Loved', 'Joyful', 'Melancholic', 'Irritated', 'Apprehensive', 'Restless', 'Ecstatic', 'Distraught', 'Panicked', 'Annoyed', 'Numb', 'Scared', 'Enraged', 'Heartbroken', 'Amused', 'Overwhelmed', 'Grateful', 'Conflicted', 'Peaceful', 'Devastated', 'Empowered');

    return $adjectives[array_rand($adjectives)];
}



function dispatch_sms($to_phone, $single_message, $e__id = 0, $x_data = array(), $template_i__id = 0, $x__website = 0, $log_tr = true, $demo_only = false){

    $CI =& get_instance();
    $twilio_account_sid = website_setting(30859);
    $twilio_auth_token = website_setting(30860);
    $twilio_from_number = website_setting(27673);
    if(!$twilio_from_number || !$twilio_auth_token || !$twilio_account_sid){

        //No way to send an SMS:
        if($log_tr){
            $CI->Mench_ledger->create(array(
                'x__message' => 'dispatch_sms() missing either: '.$twilio_account_sid.' / '.$twilio_auth_token.' / '.$twilio_from_number,
                'x__type' => 4246, //Platform Bug Reports
                'x__player' => $e__id,
                'x__website' => $x__website,
                'x__metadata' => array(
                    '$to_phone' => $to_phone,
                    '$single_message' => $single_message,
                    '$template_i__id' => $template_i__id,
                    '$x_data' => $x_data,
                ),
            ));
        }

        return false;
    }

    $post = array(
        'From' => $twilio_from_number,
        'Body' => $single_message,
        'To' => $to_phone,
    );

    if($demo_only){
        echo print_r($post);
        return false;
    }

    $x = curl_init("https://api.twilio.com/2010-04-01/Accounts/".$twilio_account_sid."/Messages.json");
    curl_setopt($x, CURLOPT_POST, true);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($x, CURLOPT_USERPWD, $twilio_account_sid.":".$twilio_auth_token);
    curl_setopt($x, CURLOPT_POSTFIELDS, http_build_query($post));
    $y = curl_exec($x);
    curl_close($x);

    if(substr_count($y, '"code": 21211')){
        //Invalid input, must be returned:
        return false;
    }
    $sms_success = !substr_count($y, '"status": 400');

    //Log transaction:
    if($log_tr){
        $CI->Mench_ledger->create(array_merge($x_data, array(
            'x__type' => ( $sms_success ? 27676 : 27678 ), //System SMS Success/Fail
            'x__player' => $e__id,
            'x__message' => $single_message,
            'x__next' => $template_i__id,
            'x__metadata' => array(
                'post' => $post,
                'response' => $y,
            ),
        )));
    }

    return true;

}

function dispatch_email($to_emails, $subject, $email_body, $e__id = 0, $x_data = array(), $template_i__id = 0, $x__website = 0, $log_tr = true, $demo_only = false){

    $CI =& get_instance();
    $domain_name = get_domain('m__title', $e__id, $x__website);
    $domain_email = website_setting(28614, $e__id, $x__website);

    if(!strlen($domain_email)){
        $domain_name = 'MENCH';
        $domain_name = 'support@mench.com';
        $CI->Mench_ledger->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'Domain email is missing! ('.$domain_name.') ('.$domain_email.') ('.join(' & ',$to_emails).')',
        ));
    }

    $email_domain = '"'.$domain_name.'" <'.$domain_email.'>';
    $name = 'New User';
    $ReplyToAddresses = array($email_domain);

    if($e__id > 0){

        $es = $CI->Source_cache->fetch(array(
            'e__id' => $e__id,
        ));
        if(count($es)){

            $name = $es[0]['e__title'];

            //Also fetch email for this user to populate the reply to:
            $fetch_emails = $CI->Mench_ledger->fetch(array(
                'x__following' => 3288, //Email
                'x__follower' => $e__id,
                'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            ));
            if(count($fetch_emails) && filter_var($fetch_emails[0]['x__message'], FILTER_VALIDATE_EMAIL)){
                array_push($ReplyToAddresses, trim($fetch_emails[0]['x__message']));
            }
        }
    }

    //Email has no word limit to add header & footer:
    $e___6287 = $CI->config->item('e___6287'); //APP
    $base_domain = 'https://'.get_domain('m__message', $e__id, $x__website);

    $email_message = '<div class="line">'.view__shuffle_message(29749).' '.$name.' '.view__shuffle_message(29750).'</div>';
    $email_message .= $email_body."\n";
    $email_message .= '<div class="line">'.view__shuffle_message(12691).'</div>';
    $email_message .= '<div class="line">'.get_domain('m__title', $e__id, $x__website).'</div>';


    if($e__id > 0 && count($es) && (!$template_i__id || !count($CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42256')) . ')' => null, //Writes
            'x__following' => 31779, //Mandatory Emails
            'x__next' => $template_i__id,
        ))))){
        //User specific notifications:
        $email_message .= '<div class="line"><a href="'.$base_domain.view__app_link(28904).'?e__handle='.$es[0]['e__handle'].'&e__time='.time().'&e__hash='.view__hash(time().$es[0]['e__handle']).'" style="font-size:13px;">'.$e___6287[28904]['m__title'].'</a></div>';
    }


    $general_style = 'width:100%; max-width:610px; font-size:16px; margin-bottom:8px; line-height:134%;';

    //Email HTML Transformations:
    $email_message = str_replace('>Show more<','><', $email_message); //Hide the show more content if any
    $email_message = str_replace('<img ','<img style="'.$general_style.'" ', $email_message);
    $email_message = str_replace('<div class="line','<div style="'.$general_style.'" class="line', $email_message);
    $email_message = str_replace("\n",'<div style="padding:3px 0 0; line-height:100%;">&nbsp;</div>', $email_message);
    $email_message = str_replace('href="/','style="display:inline-block;" href="'.$base_domain.'/', $email_message);

    $email_data = array(
        // Source is required
        'Source' => $email_domain,
        // Destination is required
        'Destination' => array(
            'ToAddresses' => $to_emails,
            'CcAddresses' => array(),
            'BccAddresses' => array(),
        ),
        // Message is required
        'Message' => array(
            // Subject is required
            'Subject' => array(
                // Data is required
                'Data' => $subject,
                'Charset' => 'UTF-8',
            ),
            // Body is required
            'Body' => array(
                'Text' => array(
                    // Data is required
                    'Data' => strip_tags(str_replace("\n","\n\n",$email_message)),
                    'Charset' => 'UTF-8',
                ),
                'Html' => array(
                    // Data is required
                    'Data' => nl2br($email_message),
                    'Charset' => 'UTF-8',
                ),
            ),
        ),
        'ReplyToAddresses' => $ReplyToAddresses,
        'ReturnPath' => $email_domain,
    );

    if($demo_only){
        echo print_r($email_data);
        return false;
    }

    //Loadup amazon SES:
    require_once('application/libraries/aws/aws-autoloader.php');

    $client = new Aws\Ses\SesClient([
        //'profile' => 'default',
        'version' => 'latest',
        'region' => 'us-west-2',
        'credentials' => $CI->config->item('cred_aws'),
    ]);

    $response = $client->sendEmail($email_data);

    //Log transaction:
    if($log_tr){

        //Let's log a system email as the last resort way to record this transaction:
        $CI->Mench_ledger->create(array_merge($x_data, array(
            'x__type' => 29399,
            'x__next' => $template_i__id,
            'x__player' => $e__id,
            'x__message' => $subject."\n\n".$email_message,
            'x__metadata' => array(
                'to' => $to_emails,
                'subject' => $subject,
                'message' => $email_message,
                'response' => $response,
            ),
        )));

        //Can we also mark the discovery as complete?
        if($e__id && isset($x_data['x__previous']) && $x_data['x__previous']>0 && isset($x_data['x__next'])) {
            foreach ($CI->Idea_cache->fetch(array(
                'i__id' => $x_data['x__previous'],
            )) as $email_i) {
                $CI->Mench_ledger->mark_complete(i__discovery_link($email_i), $e__id, $x_data['x__next'], $email_i, $x_data);
            }
        }

    }


    return $response;

}



function fetch_next($player_e, $i__id, $i, $target_i__hashtag){

    //Find Next:
    $CI =& get_instance();
    $i_redirect_url = false;
    foreach($CI->Idea_cache->fetch(array(
        'i__id' => $i__id,
    )) as $primary_i){
        return i_redirect_url($primary_i);
    }

    //Still here? Find the next URL
    $find_next = $CI->Mench_ledger->find_next($player_e['e__id'], $target_i__hashtag, $i);
    return $find_next;

}

function website_setting($setting_id = 0, $initiator_e__id = 0, $x__website = 0, $force_website = true){

    $CI =& get_instance();
    $e_id = 0; //Assume no domain unless found below

    if(!$initiator_e__id){
        $player_e = superpower_unlocked();
        if($player_e && $player_e['e__id']>0){
            $initiator_e__id = $player_e['e__id'];
        }
    }

    if($x__website && $force_website){

        $e_id = $x__website;

    } else {

        $server_name = get_server('SERVER_NAME');
        if(strlen($server_name)){
            foreach($CI->config->item('e___14870') as $x__type => $m) {
                if (substr_count($m['m__message'], $server_name)==1){
                    $e_id = $x__type;
                    break;
                }
            }
        }

        $e_id = ( $e_id ? $e_id : ( $x__website > 0 ? $x__website : 2738 /* Mench */ ) );

    }


    if(!$setting_id){
        return $e_id;
    }


    $e___domain_sett = $CI->config->item('e___'.$setting_id); //DOMAINS

    if(!isset($e___domain_sett[$e_id]) || !strlen($e___domain_sett[$e_id]['m__message'])){
        $target_return = ( in_array($setting_id, $CI->config->item('n___6404')) ? view__memory(6404,$setting_id) : false );
    } else {
        $target_return = $e___domain_sett[$e_id]['m__message'];
    }

    return $target_return;

}



function get_domain($var_field, $initiator_e__id = 0, $x__website = 0, $force_website = true){
    $CI =& get_instance();
    $domain_e = website_setting(0, $initiator_e__id, $x__website, $force_website);
    $e___14870 = $CI->config->item('e___14870'); //DOMAINS
    return $e___14870[$domain_e][$var_field];
}



function access_level_e($e__handle = null, $e__id = 0, $e = false){

    /*
     *
     * Returns an Integer Depending on Access Level:
     *
     * 0 ACCESS BLOCKED
     * 1 READ-ONLY
     * 2 VIEW ALL
     * 3 EDIT
     *
     * */

    $CI =& get_instance();
    $player_e = superpower_unlocked();
    if(superpower_unlocked(10939)){
        return 3;
    } elseif($player_e && ($e__handle==$player_e['e__handle'] || $e__id==$player_e['e__id'])){
        return 3;
    }

    if(strlen($e__handle)){
        $filters['LOWER(e__handle)'] = strtolower($e__handle);
    } elseif(intval($e__id)){
        $filters['e__id'] = $e__id;
    } elseif(!$e || !$player_e){
        return 0;
    }

    if(!$e){
        //Check privacy first:
        foreach($CI->Source_cache->fetch($filters) as $match_e){
            $e = $match_e;
            break;
        }
    }

    //Source or its status is system locked?
    if(in_array($e['e__id'], $CI->config->item('n___32145'))){
        //Read Only
        return 0;
    }

    $is_public = in_array($e['e__privacy'], $CI->config->item('n___33240'));
    $is_author = false;
    if($player_e){
        $is_author = count($CI->Mench_ledger->fetch(array(
            'x__type IN (' . join(',', $CI->config->item('n___13548')) . ')' => null, //AUTHORED SOURCES
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__following' => $player_e['e__id'],
            'x__follower' => $e['e__id'],
        )));
    }

    return ( $is_author ? 3 : ( $is_public ? 2 : ( $e['e__privacy']==43008 ? 1 : 0 ) ) );

}

function access_level_i($i__hashtag = null, $i__id = 0, $i = false, $is_cahce = false){

    /*
     *
     * Returns an Integer Depending on Access Level:
     *
     * 0 ACCESS BLOCKED
     * 1 READ-ONLY
     * 2 CAN-REPLY
     * 3 EDIT
     *
     * */

    if($is_cahce){
        return 1;
    }

    if(superpower_unlocked(12700)){
        return 3;
    }

    $CI =& get_instance();
    $player_e = superpower_unlocked();

    if(strlen($i__hashtag)){
        $filters['LOWER(i__hashtag)'] = strtolower($i__hashtag);
    } elseif(intval($i__id)){
        $filters['i__id'] = $i__id;
    } elseif(!$i){
        return 0;
    }

    if(!$i){
        //Check privacy first:
        foreach($CI->Idea_cache->fetch($filters) as $match_i){
            $i = $match_i;
            break;
        }
    }

    $is_author = false;
    if($player_e){
        $is_author = count($CI->Mench_ledger->fetch(array( //IDEA SOURCE
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
            'x__following' => $player_e['e__id'],
            'x__next' => $i['i__id'],
        )));
    }

    if($is_author) {
        //Authors can always edit:
        return 3;
    } elseif(count($CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42953')) . ')' => null, //Mentioned Sources
        'x__following' => $player_e['e__id'],
        'x__next' => $i['i__id'],
    )))){
        //Mentioned can always reply:
        return 2;
    } else {

        //Inventory Limits:
        if(i_spots_remaining($i['i__id'])==0){
            return 0;
        }



        // IDEA RELATION CHECK:

        //If Discovered All
        $fetch_44161 = $CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 44161, //If Discovered All
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0);
        if(count($fetch_44161)){
            $the_counter = 0;
            if($player_e){
                foreach($fetch_44161 as $e_pre){
                    if(count($CI->Mench_ledger->fetch(array(
                        'x__player' => $player_e['e__id'],
                        'x__previous' => $e_pre['x__previous'],
                        'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $the_counter++;
                    }
                }
            }
            if($the_counter < count($fetch_44161)){
                return 0;
            }
        }

        //If Discovered Any
        $fetch_40791 = $CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 40791, //If Discovered Any
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0);
        if(count($fetch_40791)){
            $the_counter = 0;
            if($player_e){
                foreach($fetch_40791 as $e_pre){
                    if(count($CI->Mench_ledger->fetch(array(
                        'x__player' => $player_e['e__id'],
                        'x__previous' => $e_pre['x__previous'],
                        'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $the_counter++;
                        break;
                    }
                }
            }
            if(!$the_counter){
                return 0;
            }
        }


        //If Not Discovered All
        $fetch_44162 = $CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 44162, //If Not Discovered All
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0);
        if(count($fetch_44162)){
            $the_counter = 0;
            if($player_e){
                foreach($fetch_44162 as $e_pre){
                    if(count($CI->Mench_ledger->fetch(array(
                        'x__player' => $player_e['e__id'],
                        'x__previous' => $e_pre['x__previous'],
                        'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $the_counter++;
                    }
                }
                if($the_counter >= count($fetch_44162)){
                    return 0;
                }
            } else {
                return 0;
            }
        }


        //If Not Discovered Any
        $fetch_40793 = $CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 40793, //If Not Discovered Any
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0);
        if(count($fetch_40793)){
            $the_counter = 0;
            if($player_e){
                foreach($fetch_40793 as $e_pre){
                    if(count($CI->Mench_ledger->fetch(array(
                        'x__player' => $player_e['e__id'],
                        'x__previous' => $e_pre['x__previous'],
                        'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $the_counter++;
                        break;
                    }
                }
            } else {
                return 0;
            }
            if($the_counter > 0){
                return 0;
            }
        }


        // SOURCE RELATION CHECK:


        //Include If Has ANY
        $fetch_27984 = $CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 27984, //Include If Has Any
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0);
        if(count($fetch_27984)){
            $the_counter = 0;
            if($player_e){
                foreach($fetch_27984 as $e_pre){
                    if((( $player_e && $player_e['e__id']==$e_pre['x__following'] ) || count($CI->Mench_ledger->fetch(array(
                            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__following' => $e_pre['x__following'],
                            'x__follower' => $player_e['e__id'],
                        ))))){
                        $the_counter++;
                        break;
                    }
                }
            }
            if(!$the_counter){
                return 0;
            }
        }


        //Include If Has ALL
        $fetch_43513 = $CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 43513, //Must Include All
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0);
        if(count($fetch_43513)){
            $the_counter = 0;
            if($player_e){
                foreach($fetch_43513 as $e_pre){
                    if((( $player_e && $player_e['e__id']==$e_pre['x__following'] ) || count($CI->Mench_ledger->fetch(array(
                            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__following' => $e_pre['x__following'],
                            'x__follower' => $player_e['e__id'],
                        ))))){
                        $the_counter++;
                    }
                }
            }
            if($the_counter < count($fetch_43513)){
                return 0;
            }
        }


        //Exclude If Has ANY
        $fetch_43514 = $CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 43514, //Must Exclude All
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0);
        if(count($fetch_43514)){
            $the_counter = 0;
            if($player_e){
                foreach($fetch_43514 as $e_pre){
                    if(( $player_e['e__id']==$e_pre['x__following'] ) || count($CI->Mench_ledger->fetch(array(
                            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__following' => $e_pre['x__following'],
                            'x__follower' => $player_e['e__id'],
                        )))){
                        //Found an exclusion, so skip this:
                        $the_counter++;
                        break;
                    }
                }
            }
            if($the_counter>0){
                return 0;
            }
        }

        //Exclude If Has ALL
        $fetch_26600 = $CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 26600, //Must Exclude All
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array(), 0);
        if(count($fetch_26600)){
            $the_counter = 0;
            if($player_e){
                foreach($fetch_26600 as $e_pre){
                    if(( $player_e['e__id']==$e_pre['x__following'] ) || count($CI->Mench_ledger->fetch(array(
                            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__following' => $e_pre['x__following'],
                            'x__follower' => $player_e['e__id'],
                        )))){
                        //Found an exclusion, so skip this:
                        $the_counter++;
                    }
                }
            }
            if($the_counter==count($fetch_26600)){
                return 0;
            }
        }


        //Privacy level:
        $is_public = in_array($i['i__privacy'], $CI->config->item('n___42952'));
        //$is_read_only = $i['i__privacy']==42929; // returned 1, this is retired
        return ( $is_public ? 2 : 0 );
    }


}


function boost_power()
{
    //Give php page instance more processing power
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
}

function public_app($e){
    $CI =& get_instance();
    return in_array($e['e__privacy'], $CI->config->item('n___7357')) && !in_array($e['e__id'], $CI->config->item('n___32141'));
}
function flag_for_search_indexing($focus__node = null, $s__id = 0) {

    $CI =& get_instance();

    if($focus__node && !in_array($focus__node , $CI->config->item('n___12761'))){
        return array(
            'status' => 0,
            'message' => 'Object type is invalid',
        );
    } elseif(($focus__node && !$s__id) || ($s__id && !$focus__node)){
        return array(
            'status' => 0,
            'message' => 'Must define both object type and ID',
        );
    }

    //Update live for now:
    return update_algolia($focus__node, $s__id);

}

function search_enabled(){
    $CI =& get_instance();
    return ( $CI->config->item('universal_search_enabled') && intval(view__memory(6404,12678)) );
}


function update_algolia($focus__node = null, $s__id = 0) {

    if(!search_enabled()){
        console.log("Search engine is disabled!");
        return false;
    }

    $CI =& get_instance();

    /*
     *
     * Syncs data with Algolia Index
     *
     * */

    if($focus__node && !in_array($focus__node , $CI->config->item('n___12761'))){
        return array(
            'status' => 0,
            'message' => 'Object type is invalid',
        );
    } elseif(($focus__node && !$s__id) || ($s__id && !$focus__node)){
        return array(
            'status' => 0,
            'message' => 'Must define both object type and ID',
        );
    }


    $e___4737 = $CI->config->item('e___4737'); //Idea Status

    //Define the support objects indexed on algolia:
    $s__id = intval($s__id);
    $limits = array();


    if($focus__node==12273){
        $focus_field_id = 'i__id';
        $focus_field_privacy = 'i__privacy';
    } elseif($focus__node==12274){
        $focus_field_id = 'e__id';
        $focus_field_privacy = 'e__privacy';
    }


    //Load Algolia Index
    $search_index = load_algolia('alg_index');



    //Which objects are we fetching?
    if ($focus__node) {

        //We'll only fetch a specific type:
        $fetch_objects = array($focus__node);

    } else {

        //Do both ideas and sources:
        $fetch_objects = $CI->config->item('n___12761');

        //We need to update the entire index, so let's truncate it first:
        $search_index->clearIndex();

        //Boost processing power:
        boost_power();

    }


    $all_export_rows = array();
    $all_db_rows = array();
    $synced_count = 0;

    foreach($fetch_objects as $loop_obj){

        //Reset limits:
        unset($filters);

        //Fetch item(s) for updates including their followings:
        if ($loop_obj==12273) {

            $filters['i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')'] = null;
            if($s__id){
                $filters['i__id'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->Idea_cache->fetch($filters, 0);

        } elseif ($loop_obj==12274) {

            //SOURCES
            $filters['e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')'] = null; //ACTIVE

            if($s__id){
                $filters['e__id'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->Source_cache->fetch($filters, 0);

        }




        //Build the index:
        foreach($db_rows[$loop_obj] as $s) {

            //Prepare variables:
            unset($export_row);
            $export_row = array();


            //Update Weight if single update:
            if($s__id){
                //Update weight before updating this object:
                if($focus__node==12273){
                    i__weight_calculator($s);
                } elseif($focus__node==12274){
                    e__weight_calculator($s);
                }
            }


            //Attempt to fetch Algolia object ID from object Metadata:
            if($focus__node){

                $external_name = ( $focus__node==12273 ? 'i__external' : 'e__external' );

                if (intval($s[$external_name]) > 0) {
                    //We found it! Let's just update existing algolia record
                    $export_row['objectID'] = intval($s[$external_name]);
                }

            } else {

                //Clear possible metadata algolia ID's that have been cached:
                if ($loop_obj==12273) {
                    $CI->Idea_cache->update($s['i__id'], array(
                        'i__external' => null,
                    ));
                } elseif ($loop_obj==12274) {
                    $CI->Source_cache->update($s['e__id'], array(
                        'e__external' => null,
                    ));
                }

            }

            //To hold followings info
            $export_row['_tags'] = array();
            $export_row['s__keywords'] = '';

            //Now build object-specific index:
            if ($loop_obj==12273) {

                //IDEAS
                //See if this idea has a time-range:
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['i__id']);
                $export_row['s__handle'] = $s['i__hashtag'];
                $export_row['s__url'] = view__memory(42903,33286) . $s['i__hashtag']; //Default to idea, forward to discovery is lacking superpowers
                $export_row['s__privacy'] = intval($s['i__privacy']);
                $export_row['s__cover'] = '';
                $export_row['s__title'] = $s['i__message'];
                $export_row['s__cache'] = $s['i__cache'];
                $export_row['s__weight'] = intval($s['i__weight']);

                //Top/Bottom Idea Keywords
                foreach ($CI->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $CI->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                    'x__previous' => $s['i__id'],
                ), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $i) {
                    $export_row['s__keywords'] .= $i['i__message'] . ' ';
                }
                foreach ($CI->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $CI->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                    'x__next' => $s['i__id'],
                ), array('x__previous'), 0, 0, array('x__weight' => 'ASC')) as $i) {
                    $export_row['s__keywords'] .= $i['i__message'] . ' ';
                }

                //Idea Sources Keywords
                foreach($CI->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__next' => $s['i__id'],
                ), array('x__following'), 0) as $x){

                    //Featured?
                    if(in_array($x['e__id'], $CI->config->item('n___41804'))){
                        array_push($export_row['_tags'], 'public_index');
                    }

                    //Authored?
                    $is_author = in_array($x['x__type'], $CI->config->item('n___31919'));
                    if($is_author){
                        array_push($export_row['_tags'], 'z_' . $x['e__id']);
                    }

                    //Keywords?
                    if($is_author || strlen($x['x__message'])){
                        $export_row['s__keywords'] .= $x['e__title'].' '.( strlen($x['x__message']) ? $x['x__message'] . ' '  : '' );
                    }

                }

            } elseif ($loop_obj==12274) {

                //SOURCES
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['e__id']);
                $export_row['s__handle'] = $s['e__handle'];
                $export_row['s__url'] = view__memory(42903,42902). $s['e__handle'];
                $export_row['s__privacy'] = intval($s['e__privacy']);
                $export_row['s__cover'] = $s['e__cover'];
                $export_row['s__title'] = $s['e__title'];
                $export_row['s__cache'] = '';
                $export_row['s__weight'] = intval($s['e__weight']);

                //Is this an image?
                if(strlen($s['e__cover'])){
                    array_push($export_row['_tags'], 'has_image');
                }
                if(in_array($s['e__privacy'], $CI->config->item('n___7357'))){
                    array_push($export_row['_tags'], 'public_index');
                }

                //Fetch Following:
                foreach($CI->Mench_ledger->fetch(array(
                    'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__follower' => $s['e__id'], //This follower source
                    'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                    'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
                ), array('x__following'), 0, 0, array('e__title' => 'DESC')) as $x) {

                    //Add tags:
                    array_push($export_row['_tags'], 'z_' . $x['e__id']);

                    //Add Keywords:
                    $export_row['s__keywords'] .= $x['e__title']. ( strlen($x['x__message']) ? ' '.$x['x__message'] : '' ) . ' ';

                }

                //Append Discovery Written Responses to Keywords
                foreach($CI->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___29133')) . ')' => null, //Written Responses
                    'x__player' => $s['e__id'], //This follower source
                ), array('x__player'), 0, 0, array('x__time' => 'DESC')) as $x){
                    $export_row['s__keywords'] .= $x['x__message'] . ' ';
                }

            }

            //Prep Keywords:
            $export_row['s__keywords'] = trim(strip_tags($export_row['s__keywords']));

            //Add to main array
            array_push($all_export_rows, $export_row);
            array_push($all_db_rows, $s);

        }
    }

    //Did we find anything?
    if(count($all_export_rows) < 1){
        return false;
    }

    //Now let's see what to do with the index (Update, Create or delete)
    if ($focus__node) {

        //We should have fetched a single item only, meaning $all_export_rows[0] is what we are focused on

        //What's the status? Is it active or should it be deleted?
        if (in_array($all_db_rows[0][$focus_field_privacy], array(6178 /* Source Deleted */, 6182 /* Idea Deleted */))) {

            if (isset($all_export_rows[0]['objectID'])) {

                //Object is deleted locally but still indexed remotely on Algolia, so let's delete it from Algolia:

                //Delete from algolia:
                $algolia_results = $search_index->deleteObject($all_export_rows[0]['objectID']);

                $synced_count += 1;

            } else {
                //Nothing to do here since we don't have the Algolia object locally!
            }

        } else {

            if (isset($all_export_rows[0]['objectID'])) {

                //Update existing index:
                $algolia_results = $search_index->saveObjects($all_export_rows);

            } else {

                //We do not have an index to an Algolia object locally, so create a new index:
                $algolia_results = $search_index->addObjects($all_export_rows);


                //also set its algolia_id to 0 locally:


                //Now update local database with the new objectIDs:
                if (isset($algolia_results['objectIDs']) && count($algolia_results['objectIDs'])==1 ) {
                    foreach($algolia_results['objectIDs'] as $key => $algolia_id) {
                        if ($focus__node==12273) {
                            $CI->Idea_cache->update($all_db_rows[$key][$focus_field_id], array(
                                'i__external' => $algolia_id,
                            ));
                        } elseif ($focus__node==12274) {
                            $CI->Source_cache->update($all_db_rows[$key][$focus_field_id], array(
                                'e__external' => $algolia_id,
                            ));
                        }
                    }
                }

            }

            $synced_count += 1;
        }

    } else {



        /*
         *
         * This is a mass update request.
         *
         * All remote objects have previously been deleted from the Algolia
         * index & metadata algolia_ids have all been set to zero!
         *
         * Create new items and update local
         *
         * */

        $algolia_results = $search_index->addObjects($all_export_rows);

        //Now update database with the objectIDs:
        if (isset($algolia_results['objectIDs']) && count($algolia_results['objectIDs'])==count($all_db_rows) ) {

            foreach($algolia_results['objectIDs'] as $key => $algolia_id) {

                if (isset($all_db_rows[$key]['i__id'])) {
                    $CI->Idea_cache->update($all_db_rows[$key][( isset($all_db_rows[$key]['i__id']) ? 'i__id' : 'e__id')], array(
                        'i__external' => intval($algolia_id),
                    ));
                } else {
                    $CI->Source_cache->update($all_db_rows[$key][( isset($all_db_rows[$key]['i__id']) ? 'i__id' : 'e__id')], array(
                        'e__external' => intval($algolia_id),
                    ));
                }

            }
        }

        $synced_count += count($algolia_results['objectIDs']);

    }



    //Return results:
    return array(
        'status' => ( $synced_count > 0 ? 1 : 0),
        'message' => $synced_count . ' objects sync with Algolia',
    );

}

function x__metadata_update($x__id, $new_fields, $x__player = 0)
{

    $CI =& get_instance();

    /*
     *
     * Enables the easy manipulation of the text metadata field which holds cache data for developers
     *
     *
     * $obj:                    The Member, Idea or Transaction itself.
     *                          We're looking for the $obj ID and METADATA
     *
     * $new_fields:             The new array of metadata fields to be Set,
     *                          Updated or Deleted (If set to null)
     *
     * */

    if ($x__id < 1 || count($new_fields) < 1) {
        return false;
    }

    //Fetch metadata for this object:
    $db_objects = $CI->Mench_ledger->fetch(array(
        'x__id' => $x__id,
    ));

    if (count($db_objects) < 1) {
        return false;
    }


    //Prepare newly fetched metadata:
    $metadata = (strlen($db_objects[0]['x__metadata']) > 0 ? unserialize($db_objects[0]['x__metadata']) : array() );

    //Go through all the new fields and see if they differ from current metadata fields:
    foreach($new_fields as $metadata_key => $metadata_value) {

        //We are doing an absolute adjustment if needed:
        if (is_null($metadata_value)) {

            //Member asked to delete this value:
            unset($metadata[$metadata_key]);

        } else {

            //Set Value
            $metadata[$metadata_key] = $metadata_value;

        }
    }

    //Should be all good:
    return $CI->Mench_ledger->update($x__id, array(
        'x__metadata' => $metadata,
    ));

}


function one_two_explode($one, $two, $str)
{
    //A quick function to extract a subset of $str between $one and $two
    if (strlen($one) > 0) {
        if (substr_count($str, $one) < 1) {
            return NULL;
        }
        $temp = explode($one, $str, 2);
        if (strlen($two) > 0) {
            $temp = explode($two, $temp[1], 2);
            return trim($temp[0]);
        } else {
            return trim($temp[1]);
        }
    } else {
        $temp = explode($two, $str, 2);
        return trim($temp[0]);
    }
}


function idea_author($i__id){
    $CI =& get_instance();
    foreach($CI->Mench_ledger->fetch(array(
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
    foreach($CI->Mench_ledger->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
        'x__next' => $i__id,
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array(), 0, 0, array('x__type = \'4250\' DESC' => null)) as $x){
        return $x['x__time'];
    }
    //Now:
    return date("Y-m-d H:i:s");
}




/*
 *
 * VIEW HELPERS
 *
 * */


function view__db_field($field_name){

    //Takes a database field name and returns a human-friendly version
    return ucwords(str_replace('i__', '', str_replace('e__', '', str_replace('x__', '', $field_name))));

}





function view__cover($cover_code, $noicon_default = null, $icon_prefix = '')
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

function view__url($string){
    return preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="$0" target="_blank">$0</a>', $string);
}

function view__number($number)
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


function view__card_x($x, $has_x__reference = false)
{

    $CI =& get_instance();
    $e___32088 = $CI->config->item('e___32088'); //Platform Variables
    $ui = '<div class="x-list">';
    foreach($CI->config->item('e___4341') as $e__id => $m) {

        if(in_array(6160 , $m['m__following']) && isset($x[$e___32088[$e__id]['m__message']]) && intval($x[$e___32088[$e__id]['m__message']])>0){

            //SOURCE
            foreach($CI->Source_cache->fetch(array('e__id' => $x[$e___32088[$e__id]['m__message']])) as $focus_e){
                $ui .= '<div class="simple-line"><a href="'.view__memory(42903,42902).$focus_e['e__handle'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="main__title"><span class="icon-block">'.$m['m__cover']. '</span>'.'<span class="icon-block">'.view__cover($focus_e['e__cover'], true). '</span>'.$focus_e['e__title'].'</a></div>';
            }

        } elseif(in_array(6202 , $m['m__following']) && isset($x[$e___32088[$e__id]['m__message']]) && intval($x[$e___32088[$e__id]['m__message']])>0){

            //IDEA
            foreach($CI->Idea_cache->fetch(array('i__id' => $x[$e___32088[$e__id]['m__message']])) as $focus_i){
                $ui .= '<div class="simple-line"><a href="'.view__memory(42903,33286).$focus_i['i__hashtag'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="main__title"><span class="icon-block">'.$m['m__cover']. '</span><span class="icon-block">'.view__cache(4737 /* Source Reference */, $focus_i['i__type'], true, 'right', $focus_i['i__id']).'</span>'.view__i_title($focus_i).'</a></div>';
            }


        } elseif(in_array(4367 , $m['m__following']) && isset($x[$e___32088[$e__id]['m__message']]) && intval($x[$e___32088[$e__id]['m__message']])>0){

            //TRANSACTION
            if(!$has_x__reference){
                foreach($CI->Mench_ledger->fetch(array('x__id' => $x[$e___32088[$e__id]['m__message']])) as $ref_x){
                    $ui .= '<div class="simple-line"><span class="icon-block" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'">'.$m['m__cover']. '</span><div class="x-ref hidden x_message_'.$x['x__id'].'">'.view__card_x($ref_x, true).'</div><a class="x_message_'.$x['x__id'].'" href="javascript:void(0);" onclick="$(\'.x_message_'.$x['x__id'].'\').toggleClass(\'hidden\');">View Referenced Transaction</a></div>';
                }
            } else {
                //Simple Reference to avoid Loop:
                $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $m['m__title'].': '.$x['x__time'] . ' PST"><span class="icon-block">'.$m['m__cover']. '</span>' . view__time_difference($x['x__time']) . ' Ago</span></div>';
            }

        } elseif($e__id==4367){

            //ID
            $ui .= '<div class="simple-line"><a href="'.view__app_link(4341).'?x__id='.$x['x__id'].'" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'" class="mono-space"><span class="icon-block">'.$m['m__cover']. '</span>'.$x['x__id'].'</a></div>';

        } elseif($e__id==4362){

            //TIME
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="' . $m['m__title'].': '.$x['x__time'] . ' PST | ID '.$x['x__id'].'"><span class="icon-block">'.$m['m__cover']. '</span>' . view__time_difference($x['x__time']) . ' Ago</span></div>';

        } elseif($e__id==4370 && $x['x__weight'] > 0){

            //Order
            $ui .= '<div class="simple-line"><span data-toggle="tooltip" data-placement="top" title="'.$m['m__title']. '"><span class="icon-block">'.$m['m__cover']. '</span>'.view__ordinal($x['x__weight']).'</span></div>';

        } elseif($e__id==6103 && strlen($x['x__metadata']) > 0){

            //Metadata
            $ui .= '<div class="simple-line"><a href="'.view__app_link(12722).'?x__id=' . $x['x__id'] . '" target="_blank"><span class="icon-block">'.$m['m__cover']. '</span><u>'.$m['m__title']. '</u></a></div>';

        } elseif($e__id==4372 && strlen($x['x__message']) > 0){

            //Message
            $ui .= '<div class="simple-line" data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].'"><span class="icon-block">'.$m['m__cover'].'</span><div class="title-block">'.( strip_tags($x['x__message'])==$x['x__message'] || strlen(strip_tags($x['x__message']))<view__memory(6404,6197) ? $x['x__message'] : '<span class="hidden html_message_'.$x['x__id'].'">'.$x['x__message'].'</span><a class="html_message_'.$x['x__id'].'" href="javascript:void(0);" onclick="$(\'.html_message_'.$x['x__id'].'\').toggleClass(\'hidden\');"><u>View HTML Message</u></a>' ).'</div></div>';

        }
    }

    $ui .= '</div>';

    return $ui;
}


function view__url_clean($url)
{
    //Returns the watered-down version of the URL for a cleaner UI:
    return rtrim(str_replace('http://', '', str_replace('https://', '', str_replace('www.', '', $url))), '/');
}


function view__time_difference($t, $micro = false)
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

function view__app_link($app_id){
    return view__memory(42903,6287).view__memory(6287, $app_id, 'm__handle');
}

function view__memory($following, $follower, $filed = 'm__message'){
    $CI =& get_instance();
    $memory_tree = @$CI->config->item('e___'.$following);
    if(is_array($memory_tree) && count($memory_tree) && isset($memory_tree[$follower][$filed])){
        return $memory_tree[$follower][$filed];
    } else {
        return null;
        $CI->Mench_ledger->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view__memory() Failed to load ['.$filed.'] @'.$following.' for @'.$follower,
        ));
    }
}


function view__cache($following, $e__id, $micro_status = true, $data_placement = 'top', $i__id = 0)
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




function view__card($href, $is_current, $x__type, $o__privacy, $o__type, $o__title, $x__message = null){
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

function view__more($href, $is_current, $x__type, $o__privacy, $o__type, $o__title, $x__message = null){
    return '<a href="'.( $is_current ? 'javascript:alert(\'You are here already!\');' : $href ).'" class="dropdown-item '.( $is_current ? ' active ' : '' ).'">'.
        ( $x__type ? '<span class="icon-block-xs">'.$x__type.'</span>' : '' ).
        ( $o__privacy ? '<span class="icon-block-xs">'.$o__privacy.'</span>' : '' ).
        ( strlen($o__type) ? '<span class="icon-block-xs">'.$o__type.'</span>' : '&nbsp;' ). //Type or Cover
        $o__title.
        ( strlen($x__message) && superpower_unlocked(12701) ? '<div class="message2">'.strip_tags($x__message).'</div>' : '' ).
        '</a>';
}




function view__e_body($x__type, $counter, $e__id, $js_request_uri){


    $CI =& get_instance();
    $limit = view__memory(6404,11064);
    $player_e = superpower_unlocked();

    //Check Permission:
    if(in_array($x__type, $CI->config->item('n___42376')) && !access_level_e(null, $e__id)){
        return '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-lock"></i></span>Private</div>';
    }

    $list_results = view__e_covers($x__type, $e__id, 1);
    $focus_e__id = ( $e__id>0 ? $e__id : ( $player_e ? $player_e['e__id'] : 0 ) );
    $es = $CI->Source_cache->fetch(array(
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
            $ui .= view__card_i($x__type, $i, null, null, $focus_e__id);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___11028'))){

        //Sources:
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $e) {
            $ui .= view__card_e($x__type, $e, null);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___12144'))){

        //Discoveries:
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach ($list_results as $i) {
            $ui .= view__card_i($x__type,  $i, null, null, $focus_e__id);
        }
        $ui .= '</div>';

    }

    return $ui;

}

function view__google_tag($google_analytics_code){
    return '<script async src="https://www.googletagmanager.com/gtag/js?id='.$google_analytics_code.'"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \''.$google_analytics_code.'\');
</script>';
}

function view__i_body($x__type, $counter, $i__id){

    $CI =& get_instance();


    $list_results = view__i_covers($x__type, $i__id, 1);
    $ui = '';
    $is = $CI->Idea_cache->fetch(array(
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
            $ui .= view__card_i(11019, $previous_i);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___42265'))){

        //IDEA Link Groups Next
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $next_i) {
            $ui .= view__card_i($x__type, $next_i, $is[0]);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___42284'))) {

        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $item){
            $ui .= view__card_e(6255, $item);
        }
        $ui .= '</div>';

    } elseif(in_array($x__type, $CI->config->item('n___42261'))){

        //Sources
        $ui .= '<div class="row justify-content hideIfEmpty" id="list-in-'.$x__type.'">';
        foreach($list_results as $e_ref){
            $ui .= view__card_e($x__type, $e_ref, null);
        }
        $ui .= '</div>';

    }

    return $ui;

}

function view__e_covers($x__type, $e__id, $page_num = 0, $append_card_icon = true){

    /*
     *
     * Loads Source
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);
    $privacy_privacy = ( superpower_unlocked(12700) ? 'n___7358' /* ACTIVE */ : 'n___7357' /* PUBLIC/OWNER */  );

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

        $limit = view__memory(6404,11064);
        $query = $CI->Mench_ledger->fetch($query_filters, $joins_objects, $limit, ($page_num-1)*$limit, $order_columns);
        return $query;

    } else {

        $e___11035 = $CI->config->item('e___11035');
        if(!isset($e___11035[$x__type]['m__title'])){
            $CI->Mench_ledger->create(array(
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
        $query = $CI->Mench_ledger->fetch($query_filters, $joins_objects, 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">'.view__number($count_query).'<span>';
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


function view__i_covers($x__type, $i__id, $page_num = 0, $append_card_icon = true, $headline_authors = array()){

    /*
     *
     * Loads Idea
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);
    $i__privacy = ( superpower_unlocked(10939) ? $CI->config->item('n___31871') /* Active */ : ( superpower_unlocked() ? $CI->config->item('n___42952') /* Pubicly Accessible Ideas */ : $CI->config->item('n___42948') /* Pubicly Listed Ideas */ ) );

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

        $limit = view__memory(6404,11064);
        return $CI->Mench_ledger->fetch($query_filters, $joins_objects, $limit, ($page_num-1)*$limit, $order_columns);

    } else {

        $e___11035 = $CI->config->item('e___11035'); //COINS
        $query = $CI->Mench_ledger->fetch($query_filters, $joins_objects, 1, 0, array(), 'COUNT(x__id) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">'.view__number($count_query).'<span>';
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

function view__dynamic_headline($dynamic_e__id, $m, $selected_e = null){

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


function view__instant_select($focus__id, $down_e__id = 0, $right_i__id = 0){

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
        $CI->Mench_ledger->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view__instant_select() @'.$focus__id.' not in single select @33331 or multi select 33332',
            'x__following' => $focus__id,
            'x__follower' => $down_e__id,
            'x__next' => $right_i__id,
        ));
        return false;
    }

    $already_selected = array();
    $selection_ids = array();
    $selection_options = $CI->Mench_ledger->fetch(array(
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
        $ui .= '<h3 class="mini-font grey">'.view__dynamic_headline($focus__id, $focus_select[$focus__id]).'</h3>';
    }
    $ui .= '<div class="list-group list-radio-select grey-line radio-'.$focus__id.( $is_compact ? ' is_compact ' : ''  ).'">';

    if($down_e__id > 0){

        //Source Focus:
        if(count($selection_ids)){
            foreach($CI->Mench_ledger->fetch(array(
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
        foreach($CI->Mench_ledger->fetch(array(
            'x__following IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
            'x__next' => $right_i__id,
            'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        )) as $sel){
            array_push($already_selected, $sel['x__following']);
        }

    }

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

        $headline = '<span class="inner_headline">'.( strlen($list_item['e__cover']) ? '<span class="icon-block-sm change-results">'.view__cover($list_item['e__cover']).'</span>' : '' ).$list_item['e__title'].'</span>';
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
                $ui .= '<span class="list-group-item custom_ui_'.$focus__id.'_'.$list_item['e__id'].' '.$exclude_fonts.' itemsetting_'.$focus__id.' selection_preview selection_preview__'.$focus__id.' itemsetting active" title="'.stripslashes($list_item['e__title']).'">'.$headline.'</span>';
            } elseif($has_multiple) {
                $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_'.$focus__id.'\').removeClass(\'hidden\');$(\'.selection_preview__'.$focus__id.'\').addClass(\'hidden\');" class="list-group-item custom_ui_'.$focus__id.'_'.$list_item['e__id'].' '.$exclude_fonts.' itemsetting_'.$focus__id.' selection_preview selection_preview__'.$focus__id.' itemsetting active" title="'.stripslashes($list_item['e__title']).'">'.$headline.'<span class="icon-block-sm"><i class="far fa-pen-to-square"></i></span></a>';
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
        $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_'.$focus__id.'\').removeClass(\'hidden\');$(\'.selection_preview__'.$focus__id.'\').addClass(\'hidden\');" class="list-group-item itemsetting selection_preview selection_preview__'.$focus__id.'"><span class="icon-block"><i class="far fa-search-plus"></i></span>Show More...</a>';
    }

    $ui .= '</div>';
    $ui .= '</div>';
    return $ui;
}



function view__single_select_form($cache_e__id, $selected_e__id, $show_dropdown_arrow = false, $show_title = false){

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


function view__single_select_instant($cache_e__id, $selected_e__id, $access_level_i = 0, $show_title = true, $o__id = 0, $x__id = 0){

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
        foreach($CI->Mench_ledger->fetch(array(
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



function view__shuffle_message($e__id){
    $CI =& get_instance();
    $e___12687 = $CI->config->item('e___12687');
    $line_messages = explode("\n", $e___12687[$e__id]['m__message']);
    return $line_messages[rand(0, (count($line_messages) - 1))];
}

function view__unauthorized_message($superpower_e__id = 0){

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

function view__time_hours($total_seconds, $hide_hour = false){

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


function view__i_title($i, $string_only = false){

    if(!isset($i['i__message'])){
        return null;
    }

    //Break down by lines:
    foreach(explode("\n", $i['i__message']) as $line){
        if(strlen($line) && !filter_var($line, FILTER_VALIDATE_URL)){
            return ( $string_only ? $line : '<span class="main__title">'.$line.'</span>' );
        }
    }

    //If not yet found we need to use other data to generate title:
    return ( isset($i['i__hashtag']) && strlen($i['i__hashtag']) ? $i['i__hashtag'] : ( isset($i['i__id']) && intval($i['i__id']) ? 'Idea Number '.$i['i__id'] : 'Idea'.rand(100000000000,999999999999) ) );

}

function view__valid_handle_e($string, $check_db = false){
    $CI =& get_instance();
    return ( substr($string, 0, 1)=='@' && ctype_alnum(substr($string, 1)) && (!$check_db || count($CI->Source_cache->fetch(array(
            'LOWER(e__handle)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false );
}

function view__valid_handle_i($string, $check_db = false){
    $CI =& get_instance();
    return ( substr($string, 0, 1)=='#' && ctype_alnum(substr($string, 1)) && ( !$check_db || count($CI->Idea_cache->fetch(array(
            'LOWER(i__hashtag)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false );
}

function view__valid_handle_reverse_i($string, $check_db = false){
    $CI =& get_instance();
    return ( substr($string, 0, 2)=='!#' && ctype_alnum(substr($string, 2)) && ( !$check_db || count($CI->Idea_cache->fetch(array(
            'LOWER(i__hashtag)' => strtolower(substr($string, 2)),
        )))) ? substr($string, 2) : false );
}


function view__i__links($i, $e__id = 0, $replace_links = true, $focus__node = false){

    if(!isset($i['i__id'])){
        return null;
    }

    //Append Custom Reference Link contents, if any:
    $CI =& get_instance();

    if($replace_links){
        $i['i__cache'] = str_replace('spanaa','a',$i['i__cache']);
    }

    if($e__id>0){
        foreach($CI->Mench_ledger->fetch(array(
            'x__next' => $i['i__id'],
            'x__following > 0' => null,
            'x__type' => 31835, //References
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        ), array('x__following'), 0) as $message_references){
            if(!substr_count(strtolower($i['i__cache']), '>@'.strtolower($message_references['e__handle']))){
                //Strange!
                $CI->Mench_ledger->create(array(
                    'x__type' => 4246, //Platform Bug Reports
                    'x__following' => $e__id,
                    'x__next' => $i['i__id'],
                    'x__message' => 'view__i__links() Missing referenced source from message content @'.$message_references['e__handle'],
                ));
                continue;
            }
            foreach($CI->Mench_ledger->fetch(array(
                'x__follower' => $e__id,
                'x__following' => $message_references['e__id'],
                'x__type IN (' . join(',', $CI->config->item('n___33337')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'LENGTH(x__message) > 0' => null,
            ), array(), 1) as $reference_profile){
                if(strlen($reference_profile['x__message'])){

                    if(filter_var($reference_profile['x__message'], FILTER_VALIDATE_URL)){
                        $i['i__cache'] = str_ireplace('@'.$message_references['e__handle'].'</a>','</a>'.'<a href="'.$reference_profile['x__message'].'" target="_blank">'.$reference_profile['x__message'].'</a>', $i['i__cache']);

                    } else {
                        $i['i__cache'] = str_ireplace('@'.$message_references['e__handle'], ( filter_var($reference_profile['x__message'], FILTER_VALIDATE_URL) ? '' : '@'.$message_references['e__handle'].' ' ).$reference_profile['x__message'], $i['i__cache']);
                    }
                }
            }
        }
    }

    return
        $i['i__cache']. view__i_media($i). ( $focus__node || !substr_count($i['i__cache'], 'show_more_line') ? view__list_e($i, !$replace_links) : '' );
}





function view__sync_links($str, $return_array = false, $save_i__id = 0) {

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
        31834 => '<spanaa href="'.view__memory(42903,33286).'%s" data-toggle="popover" class="ref_idea">%s</spanaa>', //Ideation
        42337 => '<spanaa href="'.view__memory(42903,33286).'%s" data-toggle="popover" class="ref_idea">%s</spanaa>', //Ideation
        31835 => '<spanaa href="'.view__memory(42903,42902).'%s" data-toggle="popover" class="ref_source">%s</spanaa>', //Sourcing
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

            } elseif (view__valid_handle_e($word, true)) {

                //Idea Synonym
                $reference_type = 31835;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .= @sprintf($ui_template[$reference_type], substr($word, 1), $word);
                $word_count++;

            } elseif (view__valid_handle_reverse_i($word, true)) {

                //Idea Antonym
                $reference_type = 42337;
                array_push($i__references[$reference_type], $word);
                $i__cache_line .= @sprintf($ui_template[$reference_type], substr($word, 2), $word);
                $word_count++;

            } elseif (view__valid_handle_i($word, true)) {

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
        foreach($CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___4736')) . ')' => null, //Idea Message Links 3x
            'x__next' => $save_i__id,
        )) as $x){

            //Is this still valid?
            if(!in_array($x['x__message'], $i__references[$x['x__type']])){

                //Not valid, must be removed:
                $CI->Mench_ledger->update($x['x__id'], array(
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
                    foreach($CI->Idea_cache->fetch(array(
                        'LOWER(i__hashtag)' => strtolower(substr($db_val, 1)),
                    )) as $target){
                        $x__previous = $target['i__id'];
                    }
                } elseif($db_type==42337){
                    $x__type = 42337;
                    foreach($CI->Idea_cache->fetch(array(
                        'LOWER(i__hashtag)' => strtolower(substr($db_val, 2)),
                    )) as $target){
                        $x__previous = $target['i__id'];
                    }
                } elseif($db_type==31835) {
                    $x__type = 31835;
                    foreach($CI->Source_cache->fetch(array(
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

                $CI->Mench_ledger->create(array(
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
        $CI->Idea_cache->update($save_i__id, array(
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



function view__featured_links($x__type, $location, $m = null, $focus__node){
    $CI =& get_instance();
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia
    return '<div class="creator_headline" '.( is_array($m) ? ' data-toggle="tooltip" data-placement="top" title="'.$m['m__title'].( strlen($m['m__message']) ? ': '.$m['m__message'] : ' @'.$location['e__handle'] ).( strlen($location['x__message']) ? ': '.$location['x__message'] : '' ).'" ' : '' ).'>'.( $focus__node ? '<a href="'.view__memory(42903,42902).$location['e__handle'].'">' : '' ).'<span class="grey '.( $x__type==41949 ? 'icon-block' : 'icon-block-xs' ).'">'.$e___11035[$x__type]['m__cover'].'</span><span class="grey mini-frame '.( $x__type==41949 ? 'mini-font' : '' ).'">'.$location['e__title'].'</span>'.( $focus__node ? '</a>' : '' ).'</div>';
}


function view__i_nav($discovery_mode, $focus_i, $x_completes = false){

    $CI =& get_instance();
    $coins_count = array();
    $body_content = '';
    $player_e = superpower_unlocked();
    $ideation_pen = superpower_unlocked(10939);
    $e___loading_order = $CI->config->item('e___'.( $discovery_mode ? 26005 : 26005 ));

    if($player_e && !is_array($x_completes)){
        $x_completes = $CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__player' => $player_e['e__id'],
            'x__previous' => $focus_i['i__id'],
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('x__next'));
    }

    $discovery_next_hide = $player_e && $discovery_mode && !count($x_completes) && count($CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
        'x__next' => $focus_i['i__id'],
        'x__following' => 44250, //Hide Next Ideas
    )));

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


        $coins_count[$x__type] = view__i_covers($x__type, $focus_i['i__id'], 0, false);
        if(!$coins_count[$x__type] && ($discovery_mode || in_array($x__type, $CI->config->item('n___12144')))){ continue; }

        $input_content = '';
        if(!$discovery_mode && $ideation_pen){

            if(in_array($x__type, $CI->config->item('n___42261'))){

                $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__e algolia__ce dotransparent add-input"
                               maxlength="' . view__memory(6404,6197) . '"
                               placeholder="Search or Link @sources">
                    </div></div></div></div>';
                $body_content .= '<script> $(document).ready(function () { e_load_finder('.$x__type.'); }); </script>';

            } elseif(in_array($x__type, $CI->config->item('n___11020'))){

                //ADD IDEAS
                $input_content .= '<div class="new_list new-list-'.$x__type.'"><div class="col-12 container-center"><div class="dropdown_'.$x__type.' list-adder">
                    <div class="input-group border">
                        <input type="text"
                               class="form-control form-control-thick algolia_finder algolia__i algolia__ci dotransparent add-input"
                               maxlength="' . view__memory(6404,6197) . '"
                               placeholder="Search or Link #ideas">
                    </div></div></div></div>';
                $body_content .= '<script> $(document).ready(function () { i_load_finder('.$x__type.'); }); </script>';
            }

        }

        if(in_array($x__type, $CI->config->item('n___42945')) || $coins_count[$x__type]>0){
            $body_content .= '<div class="headlinebody pillbody headline_body_'.$x__type.' hidden" read-counter="'.$coins_count[$x__type].'">'.$input_content.'<div class="tab_content"></div></div>';


            if($x__type!=12840 || !$discovery_next_hide){
                $ui .= '<li class="nav-item thepill'.$x__type.'"><a class="nav-link handle_nav_'.$m['m__handle'].'" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" title="'.$m['m__title'].'"><span class="icon-block">'.$m['m__cover'].'</span><span class="hideIfEmpty xtypecounter'.$x__type.'">'.view__number($coins_count[$x__type]) . '</span><span class="hidden xtypetitle xtypetitle_'.$x__type.'">&nbsp;'. $m['m__title'] . '&nbsp;</span></a></li>';
            }

        }

    }
    $ui .= '</ul>';
    $ui .= $body_content;

    if(!$discovery_next_hide){
        $ui .= '<script> $(document).ready(function () { load_hashtag_menu(\'Next\'); }); </script>';
    }


    if(in_array($focus_i['i__type'], $CI->config->item('n___34826')) && $player_e && $discovery_mode && !count($x_completes)){
        foreach($CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
            'x__next' => $focus_i['i__id'],
            'x__following' => 44262, //Skip Next If Undiscovered
        )) as $skip){
            //Not yet discovered, lets go next automatically:
            $ui .= '<script> $(document).ready(function () { setTimeout(function () { go_next(0); }, '.( is_numeric($skip['x__message']) && intval($skip['x__message'])>0 ? intval($skip['x__message']) : '2584' ).'); }); </script>';
            break;
        }
    }


    return $ui;

}




// Function to get PayPal access token
function getAccessToken($clientId, $clientSecret)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-m.paypal.com/v1/oauth2/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_USERPWD => "$clientId:$clientSecret",
        CURLOPT_POSTFIELDS => "grant_type=client_credentials",
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Accept-Language: en_US"
        ],
        CURLOPT_SSL_VERIFYPEER => true,  // Verify SSL in production
        CURLOPT_SSL_VERIFYHOST => 2      // Verify host in production
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($httpCode == 200 && !$error) {
        $data = json_decode($response, true);
        return $data['access_token'];
    }

    throw new Exception("Failed to get access token. HTTP Code: $httpCode, Error: $error");
}

// Function to create PayPal invoice
function createPaypalInvoice($accessToken, $invoiceData)
{
    $curl = curl_init();

    // Invoice payload
    $payload = [
        'detail' => [
            'currency_code' => $invoiceData['currency_code'],
            'note' => $invoiceData['note'],
            'invoice_date' => date('Y-m-d'),
        ],
        'invoicer' => [
            'name' => [
                'given_name' => $invoiceData['invoicer_given_name']
            ],
            'email_address' => $invoiceData['invoicer_email'],
            'website' => $invoiceData['invoicer_website'],
            'logo_url' => $invoiceData['invoicer_logo_url'],
            'address' => [
                'address_line_1' => $invoiceData['invoicer_address_line_1'] ?? '',
                'address_line_2' => $invoiceData['invoicer_address_line_2'] ?? '',
            ],
        ],
        'primary_recipients' => [
            [
                'billing_info' => [
                    'email_address' => $invoiceData['recipient_email'],
                    'name' => [
                        'given_name' => $invoiceData['recipient_name'] ?? '',
                        'surname' => $invoiceData['recipient_surname'] ?? ''
                    ],
                    'address' => [
                        'address_line_1' => $invoiceData['recipient_address_line_1'] ?? '',
                        'address_line_2' => $invoiceData['recipient_address_line_2'] ?? '',
                    ],

                ]
            ]
        ],
        'items' => $invoiceData['items'],

        'configuration' => [
            'allow_tip' => false,
        ],

        'amount' => [
            'currency_code' => $invoiceData['currency_code'],
            'value' => $invoiceData['total_amount'],
            'breakdown' => [
                'item_total' => [
                    'currency_code' => $invoiceData['currency_code'],
                    'value' => $invoiceData['total_amount']
                ]
            ]
        ],

        // This triggers immediate sending instead of draft creation
        'send_to_recipient' => true,
        'send_to_invoicer' => false  // Set to true if you want a copy
    ];


    if($invoiceData['total_amount']>0){
        $payload['detail']['payment_term'] = [
            'term_type' => 'DUE_ON_DATE_SPECIFIED',
            'due_date' => ( $invoiceData['due_date'] ? $invoiceData['due_date'] : date('Y-m-d') )
        ];
        $payload['configuration']['partial_payment'] = [
            'allow_partial_payment' => ( $invoiceData['min_payment'] > 0 ),
            'minimum_amount_due' => [
                'currency_code' => $invoiceData['currency_code'],
                'value' => $invoiceData['min_payment']
            ]
        ];

    }

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-m.paypal.com/v2/invoicing/invoices",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($httpCode == 201 && !$error) {
        $data = json_decode($response, true);
        return one_two_explode('/invoices/','',$data['href']);
    }

    throw new Exception("Failed to create invoice. HTTP Code: $httpCode, Error: $error, Response: $response");
}

// Function to send PayPal invoice
function sendPaypalInvoice($accessToken, $invoiceId)
{
    $curl = curl_init();

    $payload = [
        'send_to_recipient' => true,
        'send_to_invoicer' => false,
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-m.paypal.com/v2/invoicing/invoices/$invoiceId/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer $accessToken"
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    if ($httpCode == 202 && !$error) {
        return true;
    }

    throw new Exception("Failed to send invoice. HTTP Code: $httpCode, Error: $error, Response: $response");
}



function view__card_i($x__type, $i, $previous_i = null, $target_i__hashtag = null, $focus_e__id = 0, $x_completes = false){

    //Search to see if an idea has a thumbnail:
    $CI =& get_instance();

    $x__id = ( isset($i['x__id']) && $i['x__id']>0 ? $i['x__id'] : 0 );
    $e___11035 = $CI->config->item('e___11035'); //Encyclopedia
    $is_cache = in_array($x__type, $CI->config->item('n___14599'));
    $goto_start = in_array($x__type, $CI->config->item('n___42988'));
    $player_e = superpower_unlocked();
    $superpower_10939 = !$is_cache && superpower_unlocked(10939);
    $access_level_i = access_level_i($i['i__hashtag'], 0, $i, $is_cache);
    $i_startable = i_startable($i);
    $x__player = ( $focus_e__id>0 ? $focus_e__id : ( $player_e ? $player_e['e__id'] : 0 ) );
    $link_creator = isset($i['x__player']) && $i['x__player']==$x__player;
    $focus__node = in_array($x__type, $CI->config->item('n___12149')); //NODE COIN
    $discovery_uri = ( isset($_POST['js_request_uri']) && substr_count($_POST['js_request_uri'], '/')==2 ? one_two_explode('/','/',$_POST['js_request_uri']) : false );
    $discovery_seg = ( strtolower($CI->uri->segment(1))!='ajax' && strtolower($CI->uri->segment(1))!='app' && strlen($CI->uri->segment(2)) ? $CI->uri->segment(1) : false );
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

    if($x__player && !is_array($x_completes)){
        //Fetch discovery
        $x_completes = $CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__player' => $x__player,
            'x__previous' => $i['i__id'],
            'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
        ), array('x__next'));
    }

    $focus_i__or = false;
    if($discovery_mode && $focus_i__hashtag && !$focus__node && $x__player && $previous_i['i__type']!=43758){
        foreach($CI->Idea_cache->fetch(array(
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
        $discoveries = $CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__player' => $x__player,
            'x__previous' => $i['i__id'],
        ));
        $has_discovered = count($discoveries);
    }
    if($has_discovered && $discovery_mode){
        $i = array_merge($i, $discoveries[0]);
    }

    if($has_discovered && !$target_i__hashtag){
        foreach($CI->Mench_ledger->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
            'x__player' => $x__player,
            'x__previous' => $i['i__id'],
            'x__next > 0' => null,
        ), array('x__next')) as $CI_dis){
            $target_i__hashtag = $CI_dis['i__hashtag'];
        }
    }

    $is_locked = ($discovery_mode && !$has_discovered && !$focus__node);

    if(($goto_start || !$superpower_10939) && $i_startable){
        $href = view__memory(42903,30795).$i['i__hashtag'].'/'.view__memory(6404,4235);
    } elseif($is_locked) {
        $href = null;
    } elseif($discovery_mode && $target_i__hashtag){
        $href = view__memory(42903,30795).$target_i__hashtag.'/'.$i['i__hashtag'];
    } elseif($discovery_mode) {
        $href = view__memory(42903,33286).$i['i__hashtag'];
    } else {
        $href = view__memory(42903,33286).$i['i__hashtag'];
    }




    //Top action menu:
    $ui = '<div i__id="'.$i['i__id'].'" i__hashtag="'.$i['i__hashtag'].'" i__privacy="' . $i['i__privacy'] . '" i__type="' . $i['i__type'] . '" x__id="'.$x__id.'" href="'.$href.'" class="card_cover card_i_cover '.( $focus__node ? ' focus-cover slim_flat coll-md-8 coll-sm-10 col-12
     ' : ' edge-cover ' . ( $discovery_mode ? ' col-12 ' : ' coll-md-4 coll-6 col-12 ' ) ).' no-padding card-12273 s__12273_'.$i['i__id'].' '.( strlen($href) ? ' card_click ' : '' ).( !$focus_i__or && $is_locked ? ' is_locked' : '' ).( $has_sortable ? ' sort_draggable ' : '' ).( $x__id ? ' cover_x_'.$x__id.' ' : '' ).'">';

    if($discovery_mode && $x__player && $focus__node){
        $ui .= '<style> .add_idea{ display:none; } </style>';
    }
    if(1 || ($discovery_mode && ($is_locked || $focus_i__or))){
        $ui .= '<script> $(document).ready(function () {show_more('.$i['i__id'].'); }); </script>';
    }

    $is_required = count($CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
        'x__next' => $i['i__id'],
        'x__following' => 28239, //Required
    )));

    if($is_locked){
        //$ui .= '<script> $(document).ready(function () { $(\'.cache_frame_'.$i['i__id'].' .first_line\').prepend(\''.$e___11035[43010]['m__cover'].' \'); }); </script>';
    }

    if($is_required){
        //Add required icon:
        $ui .= '<script> $(document).ready(function () { $(\'.cache_frame_'.$i['i__id'].' .first_line\').append(\'<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).' asterisk" title="Required">*</span>\'); }); </script>';
    }

    if($focus_i__or){
        $ui .= '<div class="this_selector this_selector_'.$i['i__id'].'" selection_i__id="'.$i['i__id'].'"><span class="icon-block-sm">'.( count($CI->Mench_ledger->fetch(array(
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type' => 7712, //Input Choice
                'x__player' => $x__player,
                'x__previous' => $focus_i__or['i__id'],
                'x__next' => $i['i__id'],
            ))) ? '<i class="fas fa-square-check fa-sharp"></i>' : '<i class="far fa-square fa-sharp"></i>' ).'</span></div>';
    }

    $ui .= '<div class="cover-content '.( $focus_i__or ? ' cover_selector ' : '' ).'">';
    $ui .= '<div class="inner-content">';
    $ui .= '<div class="cover-text">';

    //Show Link User:
    $ui .= '<div class="creator_frame creator_frame_'.$i['i__id'].'">';

    //Show Creator if any:
    $headline_authors = array();
    foreach($CI->Mench_ledger->fetch(array(
        'x__type' => 4250,
        'x__next' => $i['i__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    ), array('x__following')) as $creator){

        array_push($headline_authors, $creator['e__id']);
        $follow_btn = null;
        if($focus__node && $x__player && $x__player!=$creator['e__id']){
            $followings = $CI->Mench_ledger->fetch(array(
                'x__following' => $creator['e__id'],
                'x__follower' => $x__player,
                'x__type IN (' . join(',', $CI->config->item('n___42795')) . ')' => null, //Follow
                'x__type !=' => 10673,
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 1, 0, array('x__weight' => 'ASC'));
            $follow_btn = view__single_select_instant(42795, ( count($followings) ? $followings[0]['x__type'] : 0 ), $access_level_i, false, $creator['e__id'], ( count($followings) ? $followings[0]['x__id'] : 0 ));
        }

        $ui .= '<div class="creator_headline"><a href="'.view__memory(42903,42902).$creator['e__handle'].'"><span class="icon-block">'.view__cover($creator['e__cover']).'</span><b class="hidden">'.$creator['e__title'].'</b><span class="grey mini-font mini-frame">@'.$creator['e__handle'].'</span></a>'.( !in_array($creator['e__id'], $CI->config->item('n___42881')) ? '<span class="grey mini-font mini-padded mini-frame mini_time" title="'.date("Y-m-d H:i:s", strtotime($creator['x__time'])).' PST">'.view__time_difference($creator['x__time'], true).'</span>' : '' ).$follow_btn.'</div>';

    }


    $ui .= ( $href ? '<a href="'.$href.'"' : '<div' ).' class="sub__handle space-content grey '.( !$superpower_10939 && ($discovery_mode || !$focus__node || !$x__player) ? ' hidden ' : '' ).'">#<span class="ui_i__hashtag_'.$i['i__id'].'">'.$i['i__hashtag'].'</span>'.( $href ? '</a>' : '</div>' );

    //Right menu push here:
    //Bottom Bar
    $bottom_bar_ui = '';

    //Determine Link Group
    $link_type_id = 4593; //Transaction Type
    $link_type_ui = '';
    if(!$focus__node && $x__id && !$is_cache){
        foreach($CI->config->item('e___31770') as $x__type1 => $m1){
            if(in_array($i['x__type'], $CI->config->item('n___'.$x__type1))){
                foreach($CI->Mench_ledger->fetch(array(
                    'x__id' => $x__id,
                ), array('x__player')) as $linker){
                    $link_type_ui .= '<span class="icon-block-sm">';
                    $link_type_ui .= view__single_select_instant($x__type1, $i['x__type'], $access_level_i, false, $i['i__id'], $x__id);
                    $link_type_ui .= '</span>';
                }
                $link_type_id = $x__type1;
                break;
            }
        }
        if(!$link_type_ui){
            $link_type_ui .= '<span class="icon-block-sm">';
            $link_type_ui .= view__single_select_instant(4593, $i['x__type'], false, false, $i['i__id'], $x__id);
            $link_type_ui .= '</span>';
        }
    }

    foreach($CI->config->item('e___31904') as $x__type_target_bar => $m_target_bar) {

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
            $time_diff = view__time_difference($i['x__time'], true);
            $creator_name = '';
            if($i['x__player'] > 0){
                foreach($CI->Source_cache->fetch(array(
                    'e__id' => $i['x__player'],
                    'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
                )) as $creator){
                    $creator_name = 'Linked by '.$creator['e__title'].' @'.$creator['e__handle'].' on ';
                    $creator_details = '<a href="'.view__memory(42903,33286).$i['i__hashtag'].'"><span class="icon-block-sm">'.view__cover($creator['e__cover']).'</span></a>';
                }
            }

            $bottom_bar_ui .= '<span class="icon-block-sm"><div class="grey created_time" title="'.$creator_name.date("Y-m-d H:i:s", strtotime($i['x__time'])).' which is '.$time_diff.' ago | ID '.$i['x__id'].'">' . ( $creator_details ? $creator_details : $time_diff ) . '</div></span>';

        } elseif($x__type_target_bar==4737 && !$discovery_mode && $superpower_10939){

            //Source Reference
            $bottom_bar_ui .= '<span>';
            $bottom_bar_ui .= view__single_select_instant(4737, $i['i__type'], $access_level_i, false, $i['i__id'], $x__id);
            $bottom_bar_ui .= '</span>';

        } elseif($x__type_target_bar==31004 && !$discovery_mode && $access_level_i>=3 && $superpower_10939){

            //Idea Access
            $bottom_bar_ui .= '<span>';
            $bottom_bar_ui .= view__single_select_instant(31004, $i['i__privacy'], $access_level_i, false, $i['i__id'], $x__id);
            $bottom_bar_ui .= '</span>';


        } elseif(0 && $x__type_target_bar==41037 && $focus_i__or && !$is_cache){

            //Selector

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
                        $action_buttons .= '<a href="'.view__memory(42903,33286).$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==31911 && $access_level_i>=3){

                        //Idea Editor
                        $action_buttons .= '<a href="javascript:void(0);" onclick="i_editor_load('.$i['i__id'].','.$x__id.')" class="dropdown-item main__title">'.$anchor.'</a>';

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
                        $action_buttons .= '<a href="'.view__app_link(33292).view__memory(42903,33286).$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==29771 && $access_level_i>=3){

                        //Clone Single Idea:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="i_copy('.$i['i__id'].', 0)" class="dropdown-item main__title">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==28636 && $access_level_i>=3 && $x__id){

                        //Transaction Details
                        $action_buttons .= '<a href="'.view__app_link(4341).'?x__id='.$x__id.'" class="dropdown-item main__title" target="_blank">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==42648 && $access_level_i>=3){

                        //Delete Permanently
                        $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                        $action_buttons .= '<a href="javascript:void();" this_id="'.$i['i__privacy'].'" onclick="x_update_instant_select(31004, 6182, '.$i['i__id'].', '.$x__id.', 0)" class="dropdown-item drop_item_instant_31004_'.$i['i__id'].'_'.$x__id.' main__title optiond_6182_'.$i['i__id'].'_'.$x__id.'">'.$anchor.'</a>';

                    } elseif($e__id_dropdown==28637 && isset($i['x__type']) && superpower_unlocked(12700)){

                        //Paypal Details
                        $x__metadata = @unserialize($i['x__metadata']);
                        if(isset($x__metadata['txn_id'])){
                            $action_buttons .= '<a href="https://www.paypal.com/activity/payment/'.$x__metadata['txn_id'].'" class="dropdown-item main__title" target="_blank">'.$anchor.'</a>';
                        }

                    } elseif(in_array($e__id_dropdown, $CI->config->item('n___6287')) && $access_level_i>=3){

                        //Standard button
                        $action_buttons .= '<a href="'.view__app_link($e__id_dropdown).view__memory(42903,33286).$i['i__hashtag'].'" class="dropdown-item main__title">'.$anchor.'</a>';

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

    if($bottom_bar_ui){
        $ui .= '<div class="pull-right grey">';
        $ui .= $bottom_bar_ui;
        $ui .= '</div>';
    }


    //Idea Location if any:
    foreach($CI->Mench_ledger->fetch(array(
        'x__type' => 41949, //Locate
        'x__next' => $i['i__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
    ), array('x__following')) as $location){
        $ui .= view__featured_links(41949, $location, null, $focus__node);
    }

    //Link Message if any:
    if($x__id){
        $ui .= '<div class="x__message_headline grey hideIfEmpty ignore-click ui_x__message_' . $x__id . ( in_array($i['x__type'], $CI->config->item('n___42294')) ? ' hidden ' : '' ) . '" style="padding-left:40px;">'.htmlentities($i['x__message']).'</div>';
    }


    $ui .= '</div>';


    //Idea Message (Remaining)
    $ui .= '<div class="ui_i__cache_' . $i['i__id'] . ( !$focus__node ? ' space-content ' : '' ) . '">'.view__i__links($i, $x__player, ($focus__node || 1), $focus__node).'</div>';

    $i_popup_url = i_popup_url($i);
    if($i_popup_url){
        $ui .= '<div class="ignore-click link_click link_click_'.$i['i__id'].' hideIfEmpty"><a href="'.$i_popup_url.'" class="hideIfEmpty" target="_blank" onclick="link_clicked('.$i['i__id'].')">'.$i_popup_url.'</a></div>';
    }


    //Raw Data:
    $ui .= '<div class="ui_i__message_' . $i['i__id'] . '
     hidden">'.$i['i__message'].'</div>';



    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';




    if($x__player){

        //Three main actions: (Excludes reading which is no action)
        $input_ui = '';


        //Any inputs for this idea?
        if ($previous_i['i__type']==43758 || (in_array($i['i__type'], $CI->config->item('n___41055')) && $focus__node && $i['i__type']!=43758)) {

            //PAYMENT TICKET
            if(isset($_GET['cancel_pay']) && !count($x_completes)){
                $input_ui .= '<div class="alert alert-danger" role="alert">You cancelled your payment.</div>';
            }

            if(isset($_GET['process_pay']) && !count($x_completes)){

                $input_ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Processing your payment, please wait</div>';

                //Referesh soon so we can check if completed or not
                js_php_redirect(view__memory(42903, 30795) . $target_i__hashtag .'/'.$i['i__hashtag'].'?process_pay=1', 987);

            } elseif($previous_i['i__type']!=43758 && count($x_completes)){

                foreach($x_completes as $x_complete){

                    $x__metadata = unserialize($x_complete['x__metadata']);
                    $quantity = ( $x_complete['x__weight'] >= 2 ? $x_complete['x__weight'] : ( isset($x__metadata['quantity']) && $x__metadata['quantity']>=2 ? $x__metadata['quantity'] : 1 ) );

                    if($x__metadata['mc_gross']!=0){
                        $input_ui .= '<div class="alert alert-success tickets_issued" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>'.( $x__metadata['mc_gross']>0 ? 'You paid ' : 'You got a refund of ' ).str_replace('.00','',$x__metadata['mc_gross']).' '.$x__metadata['mc_currency'].( $quantity>1 ? ' for '.$quantity.' tickets' : '' ).' & should receive a <u>Paypal Email Receipt</u> shortly.</div>';
                    }

                }

                $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="'.$x__metadata['mc_gross'].'">';
                $input_ui .= '<input type="hidden" class="i__quantity" name="quantity" value="'.$x__metadata['quantity'].'">'; //Dynamic Variable that JS will update

            } else {

                $valid_instant_pay = false; //Until we can find and verify from DB

                $paypal_email =  website_setting(30882);

                $currency_types = $CI->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                    'x__next' => ( $previous_i['i__type']==43758 ? $previous_i['i__id'] : $i['i__id']  ),
                    'x__following IN (' . join(',', $CI->config->item('n___26661')) . ')' => null, //Currency
                ));
                $total_dues = $CI->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                    'x__next' => $i['i__id'],
                    'x__following' => 26562, //Total Due
                ));
                $cart_max = $CI->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                    'x__next' => $i['i__id'],
                    'x__following' => 29651, //Cart Max Quantity
                ));
                $cart_min = $CI->Mench_ledger->fetch(array(
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
                $max_allowed = ( count($cart_max) && is_numeric($cart_max[0]['x__message']) && $cart_max[0]['x__message']>0 ? intval($cart_max[0]['x__message']) : view__memory(6404,29651) );
                $spots_remaining = i_spots_remaining($i['i__id']);
                $starting_point = ( $is_required ? 1 : 0  );
                $max_allowed = ( $spots_remaining>-1 && $spots_remaining<$max_allowed ? $spots_remaining : $max_allowed );

                $min_allowed = ( count($cart_min) && is_numeric($cart_min[0]['x__message']) && intval($cart_min[0]['x__message'])>$starting_point ? intval($cart_min[0]['x__message']) : $starting_point );
                $e___26661 = $CI->config->item('e___26661'); //Currency
                if(count($currency_types)){
                    $unit_currency = $e___26661[$currency_types[0]['x__following']]['m__message'];
                }


                if(filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && count($total_dues) && $previous_i['i__type']!=43758 && $total_dues[0]['x__message']>0 && count($currency_types)==1){

                    $valid_instant_pay = true;

                    $digest_fees = count($CI->Mench_ledger->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                        'x__next' => $i['i__id'],
                        'x__following' => 30589, //Digest Fees
                    )));

                    //Break down amount & currency
                    $unit_price = doubleval($total_dues[0]['x__message']);
                    $unit_fee = number_format($unit_price * ( $digest_fees ? 0 : (doubleval(website_setting(30590, $x__player)) + doubleval(website_setting(27017, $x__player)))/100 ), 2, ".", "");

                    //Append information to cart about Paypal:
                    $info_append .= '<div class="sub_note">After completing the payment on PayPal click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="'.view__app_link(14373).'" target="_blank"><u>Terms of Use</u></a>.</div>';

                } elseif(filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && $previous_i['i__type']==43758 && count($total_dues) && $total_dues[0]['x__message']>0){

                    $digest_fees = count($CI->Mench_ledger->fetch(array(
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
                        'x__next' => $previous_i['i__id'],
                        'x__following' => 30589, //Digest Fees
                    )));

                    //Break down amount & currency
                    $unit_price = doubleval($total_dues[0]['x__message']);
                    $unit_fee = number_format($unit_price * ( $digest_fees ? 0 : (doubleval(website_setting(30590, $x__player)) + doubleval(website_setting(27017, $x__player)))/100 ), 2, ".", "");

                }


                $current_value = $min_allowed;
                foreach($CI->Mench_ledger->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type' => 7712, //Input Choice
                    'x__player' => $player_e['e__id'],
                    'x__next' => $i['i__id'],
                ), array(), 1) as $x_selection){
                    $current_value = $x_selection['x__weight'];
                }


                //Is multi selectable, allow show down for quantity:
                $input_ui .= '<div class="source-info ticket-notice" title="'.$e___11035[44242]['m__title'] . '">'
                    . '<span class="icon-block">'. $e___11035[44242]['m__cover'] . '</span>'
                    . '<div class="source_info_box">';

                if($max_allowed > 0 || $min_allowed > 0){
                    $input_ui .= '<div class="sale_controller sale_controller_'.$i['i__id'].'" unitprice="'.$unit_price.'" unitcurrency="'.$unit_currency.'" i__id="'.$i['i__id'].'">';
                    $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(-1,'.$i['i__id'].','.$max_allowed.','.$min_allowed.','.($unit_fee+$unit_price).','.$unit_fee.')" class="sale_increment sale_down"><i class="fas fa-minus '.( $current_value==$min_allowed ? ' hidden ' : '' ).'"></i></a>';
                    $input_ui .= '<span class="main__title current_count">'.$current_value.'</span>';
                    $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(1,'.$i['i__id'].','.$max_allowed.','.$min_allowed.','.($unit_fee+$unit_price).','.$unit_fee.')" class="sale_increment sale_up">'.( $max_allowed==$min_allowed ? '<i class="fas fa-lock islocked"></i>' : '<i class="fas fa-plus"></i>' ).'</a>';
                    $input_ui .= '</div>';
                } else {
                    $input_ui .= '<span class="current_count" style="display: none;">'.$min_allowed.'</span>';
                }

                $input_ui .= $info_append;

                $input_ui .= '</div>';
                $input_ui .= '</div>';


                if($valid_instant_pay){

                    $e___14870 = $CI->config->item('e___14870'); //DOMAINS

                    //Load Paypal Pay button:
                    $input_ui .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';

                    $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="'.$unit_fee.'">';
                    $input_ui .= '<input type="hidden" class="i__quantity" name="quantity" value="'.$min_allowed.'">'; //Dynamic Variable that JS will update
                    $input_ui .= '<input type="hidden" name="item_name" value="'.remove_none_utf8(view__i_title($i, true)).'">';
                    $input_ui .= '<input type="hidden" name="item_number" value="'.( $target_i__hashtag ? $target_i__hashtag.' #' : '' ).$i['i__hashtag'].' @'.get_domain('m__handle').' @'.$player_e['e__handle'].'">';

                    $input_ui .= '<input type="hidden" name="amount" value="'.$unit_price.'">';
                    $input_ui .= '<input type="hidden" name="currency_code" value="'.$unit_currency.'">';
                    $input_ui .= '<input type="hidden" name="no_shipping" value="1">';
                    $input_ui .= '<input type="hidden" name="notify_url" value="https://'.$e___14870[2738]['m__message'].view__app_link(26595).'">';
                    $input_ui .= '<input type="hidden" name="cancel_return" value="https://'.get_domain('m__message').view__memory(42903,30795).$target_i__hashtag.'/'.$i['i__hashtag'].'?cancel_pay=1">';
                    $input_ui .= '<input type="hidden" name="return" value="https://'.get_domain('m__message').view__memory(42903,30795).$target_i__hashtag.'/'.$i['i__hashtag'].'?process_pay=1">';
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
            $x_responses = $CI->Mench_ledger->fetch(array(
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
                    foreach($CI->Mench_ledger->fetch(array(
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
                    foreach($CI->Mench_ledger->fetch(array(
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
                    foreach($CI->Mench_ledger->fetch(array(
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

                    $has_time = count($CI->Mench_ledger->fetch(array(
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
                        $input_ui .= '<div class="hidden">'.view__card_i(6255, $x_response).'</div>';
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
    $bottom_menu_ui = '';


    foreach($CI->config->item('e___44257') as $x__type_target_bar => $m_target_bar) {

        //See if missing superpower?
        $superpowers_required = array_intersect($CI->config->item('n___10957'), $m_target_bar['m__following']);
        if(count($superpowers_required) && (!superpower_unlocked(end($superpowers_required)) || $is_cache)){
            continue;
        }

        //Determine hover state:
        if($x__type_target_bar==33532 && !$is_cache && $player_e && $access_level_i>=2 && !$is_locked){

            //Reply
            $bottom_menu_ui .= '<span class="mini_button main__title" style="max-width:55px;">';
            $bottom_menu_ui .= '<a href="javascript:void(0);" class="btn btn-sm" onclick="i_editor_load(0,0,'.( $access_level_i>=3 ? 4228 : 30901 ).','.$i['i__id'].')"><span class="icon-block-sm">'.$m_target_bar['m__cover'].'</span>'.( $focus__node && 0 ? $m_target_bar['m__title'] : '' ).'</a>';
            $bottom_menu_ui .= '</span>';

        } elseif(0 && $x__type_target_bar==42819 && !$is_cache && superpower_unlocked(10939) && $access_level_i>=3 && !$is_locked){

            //New Source
            $bottom_menu_ui .= '<span class="mini_button main__title">';
            $bottom_menu_ui .= '<a href="javascript:void(0);" onclick="i_editor_load(0,0,'.( $access_level_i>=3 ? 4228 : 30901 ).','.$i['i__id'].')"><span class="icon-block-sm">'.$m_target_bar['m__cover'].'</span>'.( $focus__node ? $m_target_bar['m__title'] : '' ).'</a>';
            $bottom_menu_ui .= '</span>';

        } elseif($x__type_target_bar==42260 && $player_e && !$is_locked && !$is_cache && 0){

            //Reactions... Check to see if they have any?
            $reactions = $CI->Mench_ledger->fetch(array(
                'x__following' => $x__player,
                'x__next' => $i['i__id'],
                'x__type IN (' . join(',', $CI->config->item('n___42260')) . ')' => null, //Reactions
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            ), array(), 1);
            $bottom_menu_ui .= '<span class="mini_button" style="max-width:55px;"><div class="main__title">';
            $bottom_menu_ui .= view__single_select_instant(42260, ( count($reactions) ? $reactions[0]['x__type'] : 0 ), $player_e, 0 && $focus__node, $i['i__id'], ( count($reactions) ? $reactions[0]['x__id'] : 0 ));
            $bottom_menu_ui .= '</div></span>';

        } elseif($x__type_target_bar==4235 && (!$discovery_mode && $i_startable && $access_level_i>=1)){

            //Start
            $bottom_menu_ui .= '<span><a href="'.view__memory(42903,30795).$i['i__hashtag'].'/'.view__memory(6404,4235).'" class="btn btn-sm btn-black"><span class="icon-block-sm">'.$m_target_bar['m__cover'].'</span>'.$m_target_bar['m__title'].'</a></span>';

        } elseif($x__type_target_bar==42924 && $discovery_mode && $focus__node){

            //Next
            $e___6255 = $CI->config->item('e___6255');
            $focus_menu = ( $has_discovered ? $m_target_bar : $e___6255[i__discovery_link($i)] );
            $bottom_menu_ui .= '<span><a href="javascript:void(0);" onclick="go_next(0)" class="btn btn-sm post_button go_next_btn"><span class="icon-block-sm">'.$focus_menu['m__cover'].'</span>'.$focus_menu['m__title'].'</a></span>';

        } elseif($x__type_target_bar==31022 && $discovery_mode && $focus__node && $player_e && !count($x_completes) && !i_required($i)){

            //Skip
            $bottom_menu_ui .= '<span class="mini_button" style="max-width: 75px;"><a href="javascript:void(0);" onclick="go_next(1)" class="btn btn-sm"><span class="icon-block-sm">'.$m_target_bar['m__cover'].'</span>'.$m_target_bar['m__title'].'</a></span>';

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

            $coins_ui = view__i_covers($e__id_bottom_bar,  $i['i__id'], 0, true, $headline_authors);
            if(strlen($coins_ui)){
                $bottom_menu_ui .= '<span class="hideIfEmpty">';
                $bottom_menu_ui .= $coins_ui;
                $bottom_menu_ui .= '</span>';
            }
        }
    }



    if($bottom_menu_ui){
        $ui .= '<div class="'.( $focus__node && $discovery_mode ? ' container fixed-bottom hidden ' : '' ).'">';
        $ui .= '<div class="card_covers">';
        $ui .= $bottom_menu_ui;
        $ui .= '</div>';
        $ui .= '</div>';
    }




    $ui .= '</div>';


    return $ui;

}

function view__random_title(){
    $random_cover = random_cover(12279);
    return random_adjective().str_replace('Badger Honey','Honey Badger',str_replace('Black Widow','',ucwords(str_replace('-',' ',one_two_explode('fa-',' ',$random_cover)))));
}

function view__list_e($i, $plain_no_html = false){

    $CI =& get_instance();
    $message_append = '';

    //Define Order:
    $e___42421 = $CI->config->item('e___42421');
    $order_columns = array();
    foreach($e___42421 as $x__sort_id => $sort) {
        $order_columns['x__following = \''.$x__sort_id.'\' DESC'] = null;
    }

    //Query Relevant Sources:
    foreach($CI->Mench_ledger->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Writer Links Active
        'x__next' => $i['i__id'],
        'x__following IN (' . join(',', $CI->config->item('n___42421')) . ')' => null, //Featured Inputs
    ), array('x__following'), 0, 0, $order_columns) as $x){

        //Format data if needed:
        $x['x__message'] = data_type_format($x['x__following'], $x['x__message']);

        $message_append .= '<div class="source-info">'
            . '<span class="icon-block">'. $e___42421[$x['x__following']]['m__cover'] . '</span>' . $e___42421[$x['x__following']]['m__title'] . ( strlen($x['x__message']) ? ':' : '' )
            . ( strlen($x['x__message']) ? '<div class="source_info_box"><div class="sub_note main__title">'.( !$plain_no_html ? nl2br(view__url($x['x__message'])) : $x['x__message'] ).'</div></div>' : '' )
            . '</div>';

    }

    return ( strlen($message_append) ? ( $plain_no_html ? $message_append : '<div class="source-featured">'.$message_append.'</div>' ) : false );

}


function view__i_media($i){

    $CI =& get_instance();
    $message_append = '';

    //Query Relevant Sources:
    foreach($CI->Mench_ledger->fetch(array(
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



function view__pill($focus__node, $x__type, $counter, $m, $ui = null, $is_open = true){

    return '<script> $(\'.nav-tabs\').append(\'<li class="nav-item thepill'.$x__type.'"><a class="nav-link" x__type="'.$x__type.'" href="#'.$m['m__handle'].'" data-toggle="tooltip" data-placement="top" title="'.number_format($counter, 0).' '.$m['m__title'].( strlen($m['m__message']) ? ': '.str_replace('\'','',str_replace('"','',$m['m__message'])) : '' ).'"><span class="icon-block-xs">'.$m['m__cover'].'</span><span class="main__title hideIfEmpty xtypecounter'.$x__type.'">'.view__number($counter) . '</span></a></li>\') </script>' .
        '<div class="headlinebody pillbody hidden headline_body_'.$x__type.'" read-counter="'.$counter.'">'.$ui.'</div>';

}

function view__e_line($e)
{

    $ui = '<a href="'.view__memory(42903,42902).$e['e__handle'].'" class="doblock">';
    $ui .= '<span class="icon-block">'.view__cover($e['e__cover'], true).'</span>';
    $ui .= '<span class="main__title">'.$e['e__title'].'<span class="grey" style="padding-left:8px;">' . view__time_difference($e['x__time']) . ' Ago</span></span>';
    $ui .= '</a>';
    return $ui;

}



function view__card_e($x__type, $e, $extra_class = null)
{

    $CI =& get_instance();

    if(!isset($e['e__id']) || !isset($e['e__title'])){
        $CI->Mench_ledger->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'view__card_e() Missing core variables',
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
    $href = ( $is_app ? view__app_link($e['e__id']) : view__memory(42903,42902).$e['e__handle'] );
    $cover_is_image = filter_var($e['e__cover'], FILTER_VALIDATE_URL);
    $has_sortable = $x__id > 0 && $access_level_e>=3 && in_array($x__type, $CI->config->item('n___13911'));


    //Source UI
    $ui  = '<div e__id="' . $e['e__id'] . '" e__handle="' . $e['e__handle'] . '" e__privacy="' . $e['e__privacy'] . '" '.( isset($e['x__id']) ? ' x__id="'.$e['x__id'].'" x__privacy="'.$e['x__privacy'].'" ' : '' ).' href="'.$href.'" class="card_cover card_e_cover no-padding card-12274 s__12274_'.$e['e__id'].' '.$extra_class.( $is_app ? ' card-6287 ' : '' ).( $has_sortable ? ' sort_draggable ' : '' ).( $focus__node ? ' focus-cover slim_flat col-md-8 col-sm-10 col-12 ' : ' edge-cover col-sm-4 col-6 '.( strlen($href) ? ' card_click ' : '' ) ).( isset($e['x__id']) ? ' cover_x_'.$e['x__id'].' ' : '' ).'">';

    $ui .= '<div class="cover-wrapper">';

    //Coin Cover
    $ui .= ( !$focus__node ? '<a href="'.$href.'"' : '<div' ).' class="handle_href_e_'.$e['e__id'].' coinType12274 '.( $access_level_e>=3 ? '' : ' ready-only ' ).' black-background-obs cover-link" '.( $cover_is_image ? 'style="background-image:url(\''.$e['e__cover'].'\');"' : '' ).'>';
    $ui .= '<div class="cover-btn ui_e__cover_'.$e['e__id'].'" raw_cover="'.$e['e__cover'].'">'.( !$cover_is_image && $e['e__cover'] ? view__cover($e['e__cover'], true) : '' ).'</div>';
    $ui .= ( !$focus__node ? '</a>' : '</div>' );

    $ui .= '</div>';




    //Title Cover
    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';


    if($access_level_e>=3){
        //Editable:
        $ui .= view__e_input(6197, $e['e__title'], $e['e__id'], $access_level_e, ( isset($e['x__weight']) ? ($e['x__weight']*100)+1 : 0  ), true);
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
    foreach($CI->Mench_ledger->fetch(array(
        'x__type IN (' . join(',', $CI->config->item('n___42777')) . ')' => null, //Featured Profile
        'x__follower' => $e['e__id'],
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
    ), array('x__following'), 0, 0, $order_columns) as $location){
        $ui .= view__featured_links($location['x__type'], $location, $e___42777[$location['x__type']], $focus__node);
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
                    foreach($CI->Mench_ledger->fetch(array(
                        'x__id' => $x__id,
                    ), array('x__player')) as $linker){
                        $link_type_ui .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">';
                        $link_type_ui .= view__single_select_instant($x__type1, $e['x__type'], $access_level_e, false, $e['e__id'], $x__id);
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
                $featured_sources .= view__single_select_instant(6177, $e['e__privacy'], $access_level_e, false, $e['e__id'], $x__id);
                $featured_sources .= '</span>';

            } elseif($x__type_target_bar==42795 && $player_e && $player_e['e__id']!=$e['e__id'] && count($CI->Mench_ledger->fetch(array(
                    'x__follower' => $e['e__id'],
                    'x__following' => 4430, //Active Member
                    'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                )))){

                //Allow to follow fellow players:
                $followings = $CI->Mench_ledger->fetch(array(
                    'x__following' => $e['e__id'],
                    'x__follower' => $player_e['e__id'],
                    'x__type IN (' . join(',', $CI->config->item('n___42795')) . ')' => null, //Follow
                    'x__type !=' => 10673,
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                ), array(), 1, 0, array('x__weight' => 'ASC'));

                if(count($followings) || $access_level_e>=3){
                    $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">'.view__single_select_instant(42795, ( count($followings) ? $followings[0]['x__type'] : 0 ), $player_e && $access_level_e>=3, false, $e['e__id'], ( count($followings) ? $followings[0]['x__id'] : 0 )).'</span>';
                }

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
                                $action_buttons .= '<a href="'.view__app_link($e['e__id']).'" class="dropdown-item main__title">'.$anchor.'</a>';
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
                            $action_buttons .= '<a href="'.view__app_link($e__id_dropdown).view__memory(42903,42902).$e['e__handle'].'" class="dropdown-item main__title">'.$anchor.'</a>';

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
    foreach($CI->Mench_ledger->fetch(array(
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

        $info = ( strlen($social_link['x__message']) && !$social_url ? $e___14036[$social_link['x__following']]['m__title'].': '.$social_link['x__message'] : ( $social_url ? view__url_clean(one_two_explode('href="','"',$social_url)) : $e___14036[$social_link['x__following']]['m__title'] ) );

        //Append to links:
        $featured_sources .= '<span class="'.( $focus__node ? 'icon-block-sm' : 'icon-block-xs' ).'">'.( $social_url && $focus__node ? '<a '.$social_url.' data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</a>' : ( $focus__node ? '<a href="'.view__memory(42903,42902).$e___14036[$social_link['x__following']]['m__handle'].'" data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</a>' : '<span data-toggle="tooltip" data-placement="top" title="'.$info.'">'.$e___14036[$social_link['x__following']]['m__cover'].'</span>' ) ).'</span>';

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
                $ui .= view__e_covers($e__id_bottom_bar,  $e['e__id']);
                $ui .= '</span>';
            }
        }

        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}


function view__e_input($cache_e__id, $current_value, $s__id, $access_level_i, $tabindex = 0, $extra_large = false){

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



function view__json($array)
{
    if(!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode($array);
    return true;
}


function view__ordinal($number)
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
