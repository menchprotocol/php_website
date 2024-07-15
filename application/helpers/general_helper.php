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
    if(in_array($i['i__type'], $CI->config->item('n___41055'))){
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
    $count_x = $CI->X_model->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        '(x__previous='.$i['i__id'].' OR x__next='.$i['i__id'].')' => null,
    ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

    //Should we update?
    if($count_x[0]['totals'] != $i['i__weight']){
        return $CI->I_model->update($i['i__id'], array(
            'i__weight' => $count_x[0]['totals'],
        ));
    } else {
        return 0;
    }

}

function e__weight_calculator($e){

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->X_model->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
        '(x__follower='.$e['e__id'].' OR x__following='.$e['e__id'].' OR x__player='.$e['e__id'].')' => null,
    ), array(), 0, 0, array(), 'COUNT(x__id) as totals');

    //Should we update?
    if($count_x[0]['totals'] != $e['e__weight']){
        return $CI->E_model->update($e['e__id'], array(
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
        return view_memory(42903,33286) . $sign_i__hashtag;
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
    foreach($CI->X_model->fetch(array(
        'x__type' => 14599, //Cache App
        'x__following IN (' . join(',', $CI->config->item('n___14599')) . ')' => null, //Cache Apps
        'x__time >' => date("Y-m-d H:i:s", (time() - view_memory(6404,14599))),
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    )) as $delete_cahce){
        //Delete email:
        $count += $CI->X_model->update($delete_cahce['x__id'], array(
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
    $max_available = $CI->X_model->fetch(array(
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
        foreach($CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 32235, //Navigation
            'x__next' => $i__id,
        )) as $follow){
            array_push($must_follow, $follow['x__following']);
        }

        $current_discoveries = 0;
        if(count($must_follow)){
            //We must qualify each discovery individually:
            foreach($CI->X_model->fetch($query_filters) as $e){
                if(count($must_follow)==count($CI->X_model->fetch(array(
                        'x__follower' => $e['x__player'],
                        'x__following IN (' . join(',', $must_follow) . ')' => null,
                        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                    $current_discoveries++;
                }
            }
        } else {
            $query = $CI->X_model->fetch($query_filters, array(), 1, 0, array(), 'COUNT(x__id) as totals');
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

function i_required($i){
    $CI =& get_instance();
    return in_array($i['i__type'], $CI->config->item('n___43009')) || count($CI->X_model->fetch(array(
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
        $CI->X_model->create(array(
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

    $es = $CI->E_model->fetch(array(
        'e__id' => $cookie_parts[0],
    ));

    if(count($es) && $cookie_parts[2]==view__hash($cookie_parts[0].$cookie_parts[1])){

        //Assign session & log transaction:
        $CI->E_model->activate_session($es[0], false, true);
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
    $_SERVER['REQUEST_URI'] = ( strlen($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : view_app_link(4269) );
    $player_e = superpower_unlocked();
    $is_login_verified = isset($_GET['e__handle']) && $_GET['e__handle']!='SuccessfulWhale' && isset($_GET['e__hash']) && isset($_GET['e__time']) && ($_GET['e__time']+604800)>time() && strlen($_GET['e__handle']) && view__hash($_GET['e__time'].$_GET['e__handle'])==$_GET['e__hash'];

    if(
        !$player_e //User must not be logged in
        && !array_key_exists(strtolower($first_segment), $CI->config->item('handle___14582'))
        && (isset($_COOKIE['auth_cookie']) || $is_login_verified) //We can auto login with either method:
    ) {


        if($is_login_verified){

            foreach($CI->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $player_e){

                //Login:
                $CI->E_model->activate_session($player_e, true);

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
            header("Location: " . view_app_link(4269).( strlen($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '' ), true, 307);
            exit;
        }

    }

    return $player_e;

}

function round_minutes($seconds){
    $minutes = round($seconds/60);
    return ($minutes <= 1 ? 1 : $minutes );
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

   foreach($CI->I_model->fetch(array(
       'LOWER(i__hashtag)' => strtolower($i__hashtag),
   )) as $i){

       foreach($e___40946 as $x__type => $m) {
           $list_config[intval($x__type)] = array(); //Assume no links for this type
       }
       //Now search for these settings across sources:
       foreach($CI->X_model->fetch(array(
           'x__next' => $i['i__id'],
           'x__type IN (' . join(',', $CI->config->item('n___40946')) . ')' => null, //Source List Controllers
           'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
       ), array('x__following'), 0) as $setting_link){
           array_push($list_config[intval($setting_link['x__type'])], intval($setting_link['e__id']));
       }
       //Now search for these settings across ideas:
       foreach($CI->X_model->fetch(array(
           'x__previous' => $i['i__id'],
           'x__type IN (' . join(',', $CI->config->item('n___40946')) . ')' => null, //Source List Controllers
           'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
       ), array('x__next'), 0) as $setting_link){
           array_push($list_config[intval($setting_link['x__type'])], intval($setting_link['i__id']));
       }

       //Can only have one focus view, pick first one if any:
       if(count($list_config[34513])){
           foreach($list_config[34513] as $first_frame){
               $list_config[34513] = $first_frame;
               break;
           }
       } else {
           $list_config[34513] = 0;
       }



       //Generate filter:
       $query_string = array();
       if(count($list_config[40791])){
           $query_string = $CI->X_model->fetch(array(
               'x__previous IN (' . join(',', $list_config[40791]) . ')' => null,
               'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           ), array('x__player'), 0, 0, array('x__id' => 'DESC'));
       } elseif(count($list_config[27984])>0){
           $query_string = $CI->X_model->fetch(array(
               'x__following IN (' . join(',', $list_config[27984]) . ')' => null,
               'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           ), array('x__follower'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC'));
       } else {
           $query_string = $CI->X_model->fetch(array(
               'x__previous' => $i['i__id'],
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
           ), array('x__player'), 0, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC'));
       }

       //Clean list:
       $unique_users_count = array();
       foreach($query_string as $key => $x) {

           if (in_array($x['e__id'], $unique_users_count)) {

               unset($query_string[$key]);

           } elseif (count($list_config[26600]) && count($CI->X_model->fetch(array(
                   'x__following IN (' . join(',', $list_config[26600]) . ')' => null, //All of these
                   'x__follower' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               )))) {

               //Must follow NONE of these sources:
               unset($query_string[$key]);

           } elseif (count($list_config[40793]) && count($CI->X_model->fetch(array(
                   'x__previous IN (' . join(',', $list_config[40793]) . ')' => null, //All of these
                   'x__player' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               )))) {

               //They have discovered at-least one, so skip this:
               unset($query_string[$key]);

           } elseif (count($list_config[40791]) && count($list_config[27984])) {

               foreach($list_config[27984] as $limit_27984){
                   if(!count($CI->X_model->fetch(array(
                       'x__following' => $limit_27984,
                       'x__follower' => $x['e__id'],
                       'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                       'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                   )))){
                       //Must be included in ALL Sources, since not lets continue:
                       unset($query_string[$key]);
                       break;
                   }
               }

           }

           array_push($unique_users_count, $x['e__id']);

       }




       //Determine columns if any:
       if($list_config[34513]){

           $column_e = $CI->X_model->fetch(array(
               'x__following' => $list_config[34513],
               'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
           ), array('x__follower'), 0, 0, sort__e());

           foreach($CI->X_model->fetch(array(
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
               'x__following' => $list_config[34513],
               'x__next !=' => $i['i__id'],
               'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
           ), array('x__next'), 0, 0, array('x__weight' => 'ASC', 'i__message' => 'ASC')) as $link_i){
               array_push($column_i, $link_i);
           }

       }


       if($fetch_contact){
           foreach($query_string as $count => $x){

               //Fetch email & phone:
               $fetch_names = $CI->X_model->fetch(array(
                   'x__following' => 42584, //First Name
                   'x__follower' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               ));
               $fetch_emails = $CI->X_model->fetch(array(
                   'x__following' => 3288, //Email
                   'x__follower' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               ));
               $fetch_phones = $CI->X_model->fetch(array(
                   'x__following' => 4783, //Phone
                   'x__follower' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               ));

               $query_string[$count]['extension_name'] = ( count($fetch_names) && strlen($fetch_names[0]['x__message']) ? $fetch_names[0]['x__message'] : $x['e__title'] );
               $query_string[$count]['extension_email'] = ( count($fetch_emails) && filter_var($fetch_emails[0]['x__message'], FILTER_VALIDATE_EMAIL) ? $fetch_emails[0]['x__message'] : false );
               $query_string[$count]['extension_phone'] = ( count($fetch_phones) && strlen($fetch_phones[0]['x__message'])>=10 ? $fetch_phones[0]['x__message'] : false );

               $contact_details['full_list'] .= $query_string[$count]['extension_name']."\t".$query_string[$count]['extension_email']."\t".$query_string[$count]['extension_phone']."\n";



               if($query_string[$count]['extension_email']){
                   $contact_details['email_count']++;
                   $contact_details['email_list'] .= ( strlen($contact_details['email_list']) ? ", " : '' ).$query_string[$count]['extension_email'];
               }
               if($query_string[$count]['extension_phone']){
                   $contact_details['phone_count']++;
               }
           }
       }


       //Append Navigation:
       foreach($column_i as $key => $i_var){
           $must_follow = array();
           foreach($CI->X_model->fetch(array(
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
           'query_string' => $query_string,
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
    $query = $CI->X_model->fetch($query_filters, array(), 1, 0, array(), 'COUNT(x__id) as totals');
    return intval($query[0]['totals']);

}




function home_url(){
    $CI =& get_instance();
    $player_e = superpower_unlocked();
    return ( $player_e ? view_memory(42903,42902).$player_e['e__handle'] : view_memory(42903,14565) );
}

function i_startable($i){
    $CI =& get_instance();
    return count($CI->X_model->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42991')) . ')' => null, //Active Writes
        'x__next' => $i['i__id'],
        'x__following' => 4235,
    )));
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
            $goto_url = view_memory(42903,42902).$player_e['e__handle'];
        } else {
            $goto_url = view_app_link(4269).( isset($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '' );
        }

        //Now redirect:
        return redirect_message($goto_url, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>'.view_unauthorized_message($superpower_e__id).'</div>');
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
    $max_allowed_length = view_memory(6404,41985);
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
    if($focus__node==12273 && count($CI->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($suggestion),
        )))){
        return generate_handle(12273, $str, $suggestion, $increment);
    } elseif($focus__node==12274 && count($CI->E_model->fetch(array(
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
    foreach($CI->X_model->fetch(array(
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
                    $CI->X_model->update($full_media[$upload_media['e__id']]['x__id'], array(
                        'x__weight' => $sort_count,
                    ), $player_e['e__id'], 13006 /* SOURCE SORT MANUAL */);
                }

                //Update the source title?
                $validate_e__title = validate_e__title($upload_media['e__title']);
                if($validate_e__title['status'] && $full_media[$upload_media['e__id']]['e__title']!=$upload_media['e__title']){
                    $adjust_updated = true;
                    $CI->E_model->update($upload_media['e__id'], array(
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
                    foreach($CI->X_model->fetch(array(
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
                    $added_e = $CI->E_model->verify_create($upload_media['e__title'], $player_e['e__id'], ( $upload_media['media_e__id']==4259 /* Audio has no thumbnail! */ ? 'far fa-volume-up' : $upload_media['e__cover'] ), true);
                    if(!$added_e['status']){
                        $CI->X_model->create(array(
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
                            foreach($CI->X_model->fetch(array(
                                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'x__following' => $x__type,
                                'e__title' => $target_variable,
                            ), array('x__follower'), 1, 0, array('x__id' => 'ASC')) as $child_source){
                                $child_id = $child_source['e__id'];
                            }

                            //If not found create the child:
                            if(!$child_id){
                                $added_child = $CI->E_model->verify_create($target_variable, 14068);
                                if(!$added_child['status']){
                                    $CI->X_model->create(array(
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
                                $CI->X_model->create(array(
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
                                $CI->X_model->create(array(
                                    'x__player' => $player_e['e__id'],
                                    'x__following' => $child_id,
                                    'x__follower' => $upload_media['e__id'],
                                    'x__type' => 4251,
                                ));
                            }

                        } else {

                            //Save variable as is:
                            $CI->X_model->create(array(
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
                    if(!count($CI->X_model->fetch(array(
                        'x__next' => $i__id,
                        'x__following' => $upload_media['e__id'],
                        'x__type' => $upload_media['media_e__id'],
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $CI->X_model->create(array(
                            'x__player' => $player_e['e__id'],
                            'x__next' => $i__id,
                            'x__following' => $upload_media['e__id'],
                            'x__type' => $upload_media['media_e__id'],
                            'x__message' => $upload_media['playback_code'],
                            'x__weight' => $sort_count,
                        ));
                    }


                    //Link to Source as Uploader:
                    if(!count($CI->X_model->fetch(array(
                        'x__following' => $player_e['e__id'],
                        'x__follower' => $upload_media['e__id'],
                        'x__type IN (' . join(',', $CI->config->item('n___42657')) . ')' => null, //Uploads
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $CI->X_model->create(array(
                            'x__player' => $player_e['e__id'],
                            'x__following' => $player_e['e__id'],
                            'x__follower' => $upload_media['e__id'],
                            'x__type' => ( $etag_detected ? 42849 : 42659 ), //Reupload vs Upload
                            'x__message' => $upload_media['playback_code'],
                        ));
                    }


                    //Link to Media Type:
                    if(!count($CI->X_model->fetch(array(
                        'x__following' => $upload_media['media_e__id'],
                        'x__follower' => $upload_media['e__id'],
                        'x__type' => 4251,
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    )))){
                        $CI->X_model->create(array(
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
        $CI->X_model->update($full_media[$deleted_media_e__id]['x__id'], array(
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
    foreach($CI->X_model->fetch(array(
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
    $existing_x = $CI->X_model->fetch(array(
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
        $CI->X_model->update($existing_x[0]['x__id'], array(
            'x__message' => $x__message,
        ), $x__player, 10657 /* SOURCE LINK CONTENT UPDATE  */);

    } else {

        //Create transaction:
        $CI->X_model->create(array(
            'x__type' => 4251, //Follow Source
            'x__message' => $x__message,
            'x__player' => $x__player,
            'x__following' => $x__following,
            'x__follower' => $x__player,
        ));

    }

    $CI->X_model->create(array(
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
        $CI->X_model->create(array(
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
        return date(view_memory(6404,4318), strtotime($data_value));
    }

    //No special formatting needed:
    return $data_value;

}

function change_handle($old_handle){
    $max_length = view_memory(6404,41985);
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
    foreach ($CI->X_model->fetch(array(
        'x__following' => $e['e__id'],
        'x__type' => 31835, //Source Mention
        'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
    ), array('x__next')) as $ref) {
        view_sync_links(str_replace('@'.$e['e__handle'], '@'.$new_handle_string, $ref['i__message']), true, $ref['i__id']);
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
            'message' => 'Hashtag Missing',
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

    } elseif (strlen($str) > view_memory(6404,41985)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Hashtag Must be '.view_memory(6404,41985).' characters or less',
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
        foreach($CI->I_model->fetch(array(
            'i__id !=' => $i__id,
            'LOWER(i__hashtag)' => strtolower($str),
        ), 0) as $matched){
            //Is it active?
            if(!in_array($matched['i__privacy'], $CI->config->item('n___31871')) && $player_e){

                //Since not active we can replace this:
                $CI->I_model->update($matched['i__id'], array(
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

        foreach($CI->E_model->fetch(array(
            'e__id !=' => $e__id,
            'LOWER(e__handle)' => strtolower($str),
        ), 0) as $matched){
            //Is it active?
            if(!in_array($matched['e__privacy'], $CI->config->item('n___7358')) && $player_e){

                //Since not active we can replace this:
                $CI->E_model->update($matched['e__id'], array(
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

    } elseif (strlen($str) > view_memory(6404,6197)) {

        return array(
            'status' => 0,
            'message' => 'Source title must be '.view_memory(6404,6197).' characters or less',
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
    foreach($CI->X_model->fetch(array(
        'x__follower' => $x__player,
        'x__type' => 4251, //New Source Created
    ), array(), 1) as $e_created){
        return $e_created['x__website'];
    }
    foreach($CI->X_model->fetch(array(
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



function send_sms($to_phone, $single_message, $e__id = 0, $x_data = array(), $template_i__id = 0, $x__website = 0, $log_tr = true){

    $CI =& get_instance();
    $twilio_account_sid = website_setting(30859);
    $twilio_auth_token = website_setting(30860);
    $twilio_from_number = website_setting(27673);
    if(!$twilio_from_number || !$twilio_auth_token || !$twilio_account_sid){

        //No way to send an SMS:
        if($log_tr){
            $CI->X_model->create(array(
                'x__message' => 'send_sms() missing either: '.$twilio_account_sid.' / '.$twilio_auth_token.' / '.$twilio_from_number,
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
        $CI->X_model->create(array_merge($x_data, array(
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

function send_email($to_emails, $subject, $email_body, $e__id = 0, $x_data = array(), $template_i__id = 0, $x__website = 0, $log_tr = true){

    $CI =& get_instance();
    $domain_name = get_domain('m__title', $e__id, $x__website);
    $domain_email = website_setting(28614, $e__id, $x__website);

    if(!strlen($domain_email)){
        $domain_name = 'MENCH';
        $domain_name = 'support@mench.com';
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'Domain email is missing! ('.$domain_name.') ('.$domain_email.') ('.join(' & ',$to_emails).')',
        ));
    }

    $email_domain = '"'.$domain_name.'" <'.$domain_email.'>';
    $name = 'New User';
    $ReplyToAddresses = array($email_domain);

    if($e__id > 0){

        $es = $CI->E_model->fetch(array(
            'e__id' => $e__id,
        ));
        if(count($es)){

            $name = $es[0]['e__title'];

            //Also fetch email for this user to populate the reply to:
            $fetch_emails = $CI->X_model->fetch(array(
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

    $email_message = '<div class="line">'.view_shuffle_message(29749).' '.$name.' '.view_shuffle_message(29750).'</div>';
    $email_message .= $email_body."\n";
    $email_message .= '<div class="line">'.view_shuffle_message(12691).'</div>';
    $email_message .= '<div class="line">'.get_domain('m__title', $e__id, $x__website).'</div>';


    if($e__id > 0 && count($es) && (!$template_i__id || !count($CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42256')) . ')' => null, //Writes
            'x__following' => 31779, //Mandatory Emails
            'x__next' => $template_i__id,
        ))))){
        //User specific notifications:
        $email_message .= '<div class="line"><a href="'.$base_domain.view_app_link(28904).'?e__handle='.$es[0]['e__handle'].'&e__time='.time().'&e__hash='.view__hash(time().$es[0]['e__handle']).'" style="font-size:13px;">'.$e___6287[28904]['m__title'].'</a></div>';
    }


    $general_style = 'width:100%; max-width:610px; font-size:16px; margin-bottom:8px; line-height:134%;';

    //Email HTML Transformations:
    $email_message = str_replace('>Show more<','><', $email_message); //Hide the show more content if any
    $email_message = str_replace('<img ','<img style="'.$general_style.'" ', $email_message);
    $email_message = str_replace('<div class="line','<div style="'.$general_style.'" class="line', $email_message);
    $email_message = str_replace("\n",'<div style="padding:3px 0 0; line-height:100%;">&nbsp;</div>', $email_message);
    $email_message = str_replace('href="/','href="'.$base_domain.'/', $email_message);

    //Loadup amazon SES:
    require_once('application/libraries/aws/aws-autoloader.php');

    $client = new Aws\Ses\SesClient([
        //'profile' => 'default',
        'version' => 'latest',
        'region' => 'us-west-2',
        'credentials' => $CI->config->item('cred_aws'),
    ]);

    $response = $client->sendEmail(array(
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
    ));

    //Log transaction:
    if($log_tr){

        //Let's log a system email as the last resort way to record this transaction:
        $CI->X_model->create(array_merge($x_data, array(
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
            foreach ($CI->I_model->fetch(array(
                'i__id' => $x_data['x__previous'],
            )) as $email_i) {
                $CI->X_model->mark_complete(i__discovery_link($email_i), $e__id, $x_data['x__next'], $email_i, $x_data);
            }
        }

    }


    return $response;

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
        $target_return = ( in_array($setting_id, $CI->config->item('n___6404')) ? view_memory(6404,$setting_id) : false );
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
    if(superpower_unlocked(13422)){
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
        foreach($CI->E_model->fetch($filters) as $match_e){
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
        $is_author = count($CI->X_model->fetch(array(
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
    } elseif(!$i || !$player_e){
        return 0;
    }

    if(!$i){
        //Check privacy first:
        foreach($CI->I_model->fetch($filters) as $match_i){
            $i = $match_i;
            break;
        }
    }

    $is_author = false;
    if($player_e){
        $is_author = count($CI->X_model->fetch(array( //IDEA SOURCE
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
            'x__following' => $player_e['e__id'],
            'x__next' => $i['i__id'],
        )));
    }

    if($is_author) {
        //Authors can always edit:
        return 3;
    } elseif(count($CI->X_model->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42953')) . ')' => null, //Mentioned Sources
        'x__following' => $player_e['e__id'],
        'x__next' => $i['i__id'],
    )))){
        //Mentioned can always reply:
        return 2;
    } else {

        //Any Limits on Selection?
        if(!i_spots_remaining($i['i__id'])){
            return 0;
        }

        //Any Inclusion All Requirements?
        $fetch_27984 = $CI->X_model->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 27984, //Must Include All
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__following'), 0);
        if(count($fetch_27984)){
            $meets_inc2_prereq = 0;
            if($player_e){
                foreach($fetch_27984 as $e_pre){
                    if((( $player_e && $player_e['e__id']==$e_pre['x__following'] ) || count($CI->X_model->fetch(array(
                                'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                                'x__following' => $e_pre['x__following'],
                                'x__follower' => $player_e['e__id'],
                            ))))){
                        $meets_inc2_prereq++;
                    }
                }
            }
            if($meets_inc2_prereq < count($fetch_27984)){
                return 0;
            }
        }

        //Any Exclusion All Requirements?
        $fetch_26600 = $CI->X_model->fetch(array(
            'x__next' => $i['i__id'],
            'x__type' => 26600, //Must Exclude All
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
        ), array('x__following'), 0);
        if(count($fetch_26600)){
            $excludes_all = false; //Let's see if they meet any of these PREREQUISITES
            if($player_e){
                foreach($fetch_26600 as $e_pre){
                    if(( $player_e['e__id']==$e_pre['x__following'] ) || count($CI->X_model->fetch(array(
                            'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                            'x__following' => $e_pre['x__following'],
                            'x__follower' => $player_e['e__id'],
                        )))){
                        //Found an exclusion, so skip this:
                        $excludes_all = false;
                        break;
                    } else {
                        $excludes_all = true;
                    }
                }
            }
            if(!$excludes_all){
                return 0;
            }
        }


        $is_public = in_array($i['i__privacy'], $CI->config->item('n___42952'));
        $is_read_only = $i['i__privacy']==42929;
        return ( $is_public ? ( $is_read_only ? 1 : 2 ) : 0 );
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

    /*
    if($focus__node==12273){
        //Update idea flag
        $CI->I_model->update($s__id, array(
            'i__flag' => true,
        ));
    } elseif($focus__node==12274){
        //Update idea flag
        $CI->E_model->update($s__id, array(
            'e__flag' => true,
        ));
    }
    */

}

function search_enabled(){
    $CI =& get_instance();
    return ( $CI->config->item('universal_search_enabled') && intval(view_memory(6404,12678)) );
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

            $db_rows[$loop_obj] = $CI->I_model->fetch($filters, 0);

        } elseif ($loop_obj==12274) {

            //SOURCES
            $filters['e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')'] = null; //ACTIVE

            if($s__id){
                $filters['e__id'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->E_model->fetch($filters, 0);

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

                if (intval($s['algolia__id']) > 0) {
                    //We found it! Let's just update existing algolia record
                    $export_row['objectID'] = intval($s['algolia__id']);
                }

            } else {

                //Clear possible metadata algolia ID's that have been cached:
                if ($loop_obj==12273) {
                    $CI->I_model->update($s['i__id'], array(
                        'algolia__id' => null,
                    ));
                } elseif ($loop_obj==12274) {
                    $CI->E_model->update($s['e__id'], array(
                        'algolia__id' => null,
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
                $export_row['s__url'] = view_memory(42903,33286) . $s['i__hashtag']; //Default to idea, forward to discovery is lacking superpowers
                $export_row['s__privacy'] = intval($s['i__privacy']);
                $export_row['s__cover'] = '';
                $export_row['s__title'] = $s['i__message'];
                $export_row['s__cache'] = $s['i__cache'];
                $export_row['s__weight'] = intval($s['i__weight']);

                //Top/Bottom Idea Keywords
                foreach ($CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $CI->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                    'x__previous' => $s['i__id'],
                ), array('x__next'), 0, 0, array('x__weight' => 'ASC')) as $i) {
                    $export_row['s__keywords'] .= $i['i__message'] . ' ';
                }
                foreach ($CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $CI->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                    'x__next' => $s['i__id'],
                ), array('x__previous'), 0, 0, array('x__weight' => 'ASC')) as $i) {
                    $export_row['s__keywords'] .= $i['i__message'] . ' ';
                }

                //Idea Sources Keywords
                foreach($CI->X_model->fetch(array(
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
                $export_row['s__url'] = view_memory(42903,42902). $s['e__handle'];
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
                foreach($CI->X_model->fetch(array(
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
                foreach($CI->X_model->fetch(array(
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
                            $CI->I_model->update($all_db_rows[$key][$focus_field_id], array(
                                'algolia__id' => $algolia_id,
                            ));
                        } elseif ($focus__node==12274) {
                            $CI->E_model->update($all_db_rows[$key][$focus_field_id], array(
                                'algolia__id' => $algolia_id,
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
                    $CI->I_model->update($all_db_rows[$key][( isset($all_db_rows[$key]['i__id']) ? 'i__id' : 'e__id')], array(
                        'algolia__id' => intval($algolia_id),
                    ));
                } else {
                    $CI->E_model->update($all_db_rows[$key][( isset($all_db_rows[$key]['i__id']) ? 'i__id' : 'e__id')], array(
                        'algolia__id' => intval($algolia_id),
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
    $db_objects = $CI->X_model->fetch(array(
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
    return $CI->X_model->update($x__id, array(
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
