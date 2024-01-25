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

function detect_missing_columns($add_fields, $required_columns, $x__creator)
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

function current_s__type(){

    /*
     *
     * Detects which of the coins
     * coins is focused on based on
     * the URL which reflects the
     * logic in routes.php
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);
    $first_letter = substr($first_segment, 0, 1);

    if($first_letter=='~'){

        //IDEATION
        return 12273;

    } elseif($first_letter=='@'){

        //IDEATION
        return 12274;

    } elseif(strlen($first_segment) && !array_key_exists($first_segment, $CI->config->item('handle___6287'))){

        //DISCOVERY
        return 6255;

    } else {

        //Source/App:
        return 12274;

    }

}

function map_primary_links($link_id){
    return ( $link_id==12273 ? 4737 /* Idea Type */ : ( $link_id==12274 ? 7358 /* Source Active Access */ : $link_id /* Link It-self */ ) );
}

function int_hash($str){
    $int_length = 4;
    $numhash = unpack('N2', md5($str, true));
    $int_val = $numhash[1] & 0x000FFFFF;
    if(strlen($int_val) < $int_length){
        return str_pad($int_val, $int_length, "0", STR_PAD_RIGHT);
    } else {
        return substr($int_val, 0, $int_length);
    }
}


function validateDate($date, $format)
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format)==$date;
}

function current_link(){
    return 'https://' .get_server('SERVER_NAME') . get_server('REQUEST_URI');
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
        '(x__left='.$i['i__id'].' OR x__right='.$i['i__id'].')' => null,
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
        '(x__down='.$e['e__id'].' OR x__up='.$e['e__id'].' OR x__creator='.$e['e__id'].')' => null,
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

function filter_cache_group($search_e__id, $cache_e__id){

    //Determines which category an source belongs to

    $CI =& get_instance();
    foreach($CI->config->item('e___'.$cache_e__id) as $e__id => $m) {
        if(in_array($search_e__id, $CI->config->item('n___'.$e__id))){
            return $m;
        }
    }
    return false;
}

function random_string($length_of_string){
    $characters = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ';
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
    $colors = array(' ',' ',' ',' ',' ',' ',' zq12273',' zq12274',' zq12274',' zq6255',' zq6255',' zq6255');
    return trim(one_two_explode('class="','"',$fetch[array_rand($fetch)]['m__cover']).$colors[array_rand($colors)]);
}

function format_percentage($percent){
    return number_format($percent, ( $percent < 10 ? 1 : 0 ));
}


function new_member_redirect($e__id, $sign_i__hashtag){
    //Is there a redirect app?
    if(strlen($sign_i__hashtag)) {
        return '/' . $sign_i__hashtag;
    } elseif(isset($_GET['url'])) {
        return $_GET['url'];
    } else {
        return '/';
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


function reset_cache($x__creator){
    $CI =& get_instance();
    $count = 0;
    foreach($CI->X_model->fetch(array(
        'x__type' => 14599, //Cache App
        'x__up IN (' . join(',', $CI->config->item('n___14599')) . ')' => null, //Cache Apps
        'x__time >' => date("Y-m-d H:i:s", (time() - view_memory(6404,14599))),
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    )) as $delete_cahce){
        //Delete email:
        $count += $CI->X_model->update($delete_cahce['x__id'], array(
            'x__privacy' => 6173, //Transaction Removed
        ), $x__creator, 14600 /* Delete Cache */);
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
    $member_e = superpower_unlocked();

    //Any Limits on Selection?
    $spots_remaining = -1; //No limits
    $max_available = $CI->X_model->fetch(array(
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___42350')) . ')' => null, //Active Writes
        'x__right' => $i__id,
        'x__up' => 26189,
    ), array(), 1);
    if(count($max_available) && is_numeric($max_available[0]['x__message'])){

        //We have a limit! See if we've met it already:
        $query_filters = array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___40986')) . ')' => null, //SUCCESSFUL DISCOVERIES
            'x__left' => $i__id,
        );
        if($member_e){
            //Do not count current user to give them option to edit & resubmit:
            $query_filters['x__creator !='] = $member_e['e__id'];
        }

        //Navigation?
        $must_follow = array();
        foreach($CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type' => 32235, //Navigation
            'x__right' => $i__id,
        )) as $follow){
            array_push($must_follow, $follow['x__up']);
        }

        $current_discoveries = 0;
        if(count($must_follow)){
            //We must qualify each discovery individually:
            foreach($CI->X_model->fetch($query_filters) as $e){
                if(count($must_follow)==count($CI->X_model->fetch(array(
                        'x__down' => $e['x__creator'],
                        'x__up IN (' . join(',', $must_follow) . ')' => null,
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

function access_blocked($log_tnx, $log_message, $x__creator, $i__id, $x__up, $x__down){

    $return_i__id = $i__id;
    $CI =& get_instance();

    //Log Access Block:
    if($log_tnx){

        $access_blocked = $CI->X_model->create(array(
            'x__type' => ( $x__creator>0 ? 29737 : 30341 ), //Access Blocked
            'x__creator' => $x__creator,
            'x__left' => $i__id,
            'x__up' => $x__up,
            'x__down' => $x__down,
            'x__message' => $log_message,
        ));

        //Delete Current Selection:
        foreach($CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___32234')) . ')' => null, //Discovery Expansions
            'x__right' => $i__id, //This was select as an answer to x__left
            'x__left > 0' => null,
        ), array('x__left'), 0) as $x_progress) {

            //Find all answers
            foreach($CI->X_model->fetch(array(
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
                'x__creator' => $x__creator,
                'x__left' => $x_progress['x__left'],
            ), array(), 0) as $x){

                //Delete all Selections:
                foreach($CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___32234')) . ')' => null, //Discovery Expansions
                    'x__left' => $x_progress['x__left'],
                ), array('x__right'), 0) as $x2){
                    $CI->X_model->update($x2['x__id'], array(
                        'x__privacy' => 6173, //Transaction Removed
                        'x__reference' => $access_blocked['x__id'],
                    ), $x__creator, 29782 );
                }

                //Delete question discovery so the user can re-select:
                $CI->X_model->update($x['x__id'], array(
                    'x__privacy' => 6173, //Transaction Removed
                    'x__reference' => $access_blocked['x__id'],
                ), $x__creator, 29782 );

            }

            //Delete this answer:
            $CI->X_model->update($x_progress['x__id'], array(
                'x__privacy' => 6173, //Transaction Removed
                'x__reference' => $access_blocked['x__id'],
            ), $x__creator, 29782 );

            //Guide them back to the top:
            $return_i__id = $x_progress['x__left'];

            //We can only handle 1 question for now
            //TODO If multiple questions found, see which one is within top_i__id
            break;

        }

    }

    //Return false:
    foreach($CI->I_model->fetch(array(
        'i__id' => $return_i__id,
    )) as $i){
        return array(
            'status' => false,
            'return_i__hashtag' => $i['i__hashtag'],
            'message' => $log_message,
        );
    }

}


function i_is_discoverable($i__id, $log_tnx, $check_inventory = true){

    $CI =& get_instance();
    $member_e = superpower_unlocked();
    $x__creator = ( $member_e ? $member_e['e__id'] : 0 );
    $double_check = 'if you believe you have this source then make sure to login with the same email address that we sent you the email.';

    //Any Inclusion Any Requirements?
    $fetch_13865 = $CI->X_model->fetch(array(
        'x__right' => $i__id,
        'x__type' => 13865, //Must Include Any
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__up'), 0);
    if(count($fetch_13865)){
        //Let's see if they meet any of these PREREQUISITES:
        $meets_inc1_prereq = false;
        $x__up = 0;
        $excludes_message = 'Unknown Error @13865';

        if($x__creator > 0){
            foreach($fetch_13865 as $e_pre){
                if(( $member_e && $member_e['e__id']==$e_pre['x__up'] ) || count($CI->X_model->fetch(array(
                        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__up' => $e_pre['x__up'],
                        'x__down' => $x__creator,
                    )))){
                    $meets_inc1_prereq = true;
                    $x__up = ( isset($e_pre['x__up']) ? $e_pre['x__up'] : 0 );
                    $excludes_message = "You cannot discover this idea because you are missing a requirement, ".$double_check;
                    break;
                }
            }
        }
        if(!$meets_inc1_prereq && $x__creator > 0){
            return access_blocked($log_tnx, $excludes_message,$x__creator, $i__id, 13865, $x__up);
        }
    }

    //Any Inclusion All Requirements?
    $fetch_27984 = $CI->X_model->fetch(array(
        'x__right' => $i__id,
        'x__type' => 27984, //Must Include All
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__up'), 0);
    if(count($fetch_27984)){
        //There are some requirements, Let's see if they meet all of them:
        $missing_es = '';
        $meets_inc2_prereq = 0;

        $x__up = 0;
        $excludes_message = 'Unknown Error @27984';

        if($x__creator > 0){
            foreach($fetch_27984 as $e_pre){
                if($x__creator && (( $member_e && $member_e['e__id']==$e_pre['x__up'] ) || count($CI->X_model->fetch(array(
                        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__up' => $e_pre['x__up'],
                        'x__down' => $x__creator,
                    ))))){
                    $meets_inc2_prereq++;
                } else {
                    //Missing:
                    $missing_es .= ( strlen($missing_es) ? ' & ' : '' ).$e_pre['e__title'];
                }

                $x__up = ( isset($e_pre['x__up']) ? $e_pre['x__up'] : 0 );
                $excludes_message = "You cannot discover this idea because you are ".( $x__creator ? "missing [".$missing_es."]" : "not logged in" ).", ".$double_check;
            }
        }
        if($meets_inc2_prereq < count($fetch_27984)){
            //Did not meet all requirements:
            return access_blocked($log_tnx, $excludes_message,$x__creator, $i__id, 27984, $x__up);
        }
    }

    //Any Exclusion All Requirements?
    $fetch_26600 = $CI->X_model->fetch(array(
        'x__right' => $i__id,
        'x__type' => 26600, //Must Exclude All
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
    ), array('x__up'), 0);
    if(count($fetch_26600)){
        //Let's see if they meet any of these PREREQUISITES:
        $x__up = 0;
        $excludes_all = false;
        $excludes_message = 'Unknown Error @26600';
        if($x__creator > 0){
            foreach($fetch_26600 as $e_pre){
                if(( $member_e && $member_e['e__id']==$e_pre['x__up'] ) || count($CI->X_model->fetch(array(
                        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                        'x__up' => $e_pre['x__up'],
                        'x__down' => $x__creator,
                    )))){
                    //Found an exclusion, so skip this:
                    $excludes_all = false;
                    $excludes_message = "You cannot discover this idea because you belong to [".$e_pre['e__title']."]";
                    $x__up =  ( isset($e_pre['x__up']) ? $e_pre['x__up'] : 0 );
                    break;
                } else {
                    $excludes_all = true;
                }
            }
        }

        if(!$excludes_all){
            return access_blocked($log_tnx, $excludes_message, $x__creator, $i__id, 26600, $x__up);
        }
    }


    //Any Limits on Selection?
    if($check_inventory && !i_spots_remaining($i__id)){
        //Limit is reached, cannot complete this at this time:
        return access_blocked($log_tnx, "You cannot discover this idea because there are no spots remaining.", $x__creator, $i__id, 26189, 0);
    }
    

    //All good:
    return array(
        'status' => true,
    );

}


function redirect_message($url, $message = null, $log_error = false)
{
    //An error handling function that would redirect member to $url with optional $message
    //Do we have a Message?
    $CI =& get_instance();
    $member_e = superpower_unlocked();

    if ($message) {
        $CI->session->set_flashdata('flash_message', $message);
    }

    if($log_error){
        //Log thie error:
        $CI->X_model->create(array(
            'x__message' => $url.' '.stripslashes($message),
            'x__type' => 4246, //Platform Bug Reports
            'x__creator' => ( $member_e ? $member_e['e__id'] : 0 ),
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

function auto_login() {

    @session_start();
    date_default_timezone_set(view_memory(6404,11079));
    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);
    $member_e = superpower_unlocked();
    $is_login_verified = isset($_GET['e__handle']) && isset($_GET['e__hash']) && isset($_GET['e__time']) && ($_GET['e__time']+604800)>time() && strlen($_GET['e__handle']) && view_e__hash($_GET['e__time'].$_GET['e__handle'])==$_GET['e__hash'];

    if(
        !$member_e //User must not be logged in
        && !array_key_exists($first_segment, $CI->config->item('handle___14582'))
        && (isset($_COOKIE['auth_cookie']) || $is_login_verified) //We can auto login with either method:
    ) {


        if($is_login_verified){
            foreach($CI->E_model->fetch(array(
                'LOWER(e__handle)' => strtolower($_GET['e__handle']),
            )) as $e_user){

                //Login:
                $CI->E_model->activate_session($e_user, true);

                //Log them in:
                header("Location: " . ( $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : view_app_link(4269) ), true, 307);
                exit;
            }
        }

        header("Location: " . view_app_link(4269).( isset($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '' ), true, 307);
        exit;

    }
}

function round_minutes($seconds){
    $minutes = round($seconds/60);
    return ($minutes <= 1 ? 1 : $minutes );
}



function list_settings($i__hashtag, $fetch_contact = false){

    $CI =& get_instance();
    $e___6287 = $CI->config->item('e___6287'); //APP
    $e___11035 = $CI->config->item('e___11035'); //NAVIGATION
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
           'x__right' => $i['i__id'],
           'x__type IN (' . join(',', $CI->config->item('n___40946')) . ')' => null, //Source List Controllers
           'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           'e__privacy IN (' . join(',', $CI->config->item('n___7357')) . ')' => null, //PUBLIC/OWNER
       ), array('x__up'), 0) as $setting_link){
           array_push($list_config[intval($setting_link['x__type'])], intval($setting_link['e__id']));
       }
       //Now search for these settings across ideas:
       foreach($CI->X_model->fetch(array(
           'x__left' => $i['i__id'],
           'x__type IN (' . join(',', $CI->config->item('n___40946')) . ')' => null, //Source List Controllers
           'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
       ), array('x__right'), 0) as $setting_link){
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

       if(count($list_config[32426])){
           foreach($list_config[32426] as $first_frame){
               $list_config[32426] = $first_frame;
               break;
           }
       } else {
           $list_config[32426] = 0;
       }



       //Generate filter:
       $query_string = array();
       if(count($list_config[40791])){
           $query_string = $CI->X_model->fetch(array(
               'x__left IN (' . join(',', $list_config[40791]) . ')' => null,
               'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           ), array('x__creator'), 1000, 0, array('x__id' => 'DESC'));
       } elseif(count($list_config[27984])>0){
           $query_string = $CI->X_model->fetch(array(
               'x__up IN (' . join(',', $list_config[27984]) . ')' => null,
               'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
           ), array('x__down'), 1000, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC'));
       } else {
           $query_string = $CI->X_model->fetch(array(
               'x__left' => $i['i__id'],
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
           ), array('x__creator'), 1000, 0, array('x__weight' => 'ASC', 'x__id' => 'DESC'));
       }

       //Clean list:
       $unique_users_count = array();
       foreach($query_string as $key => $x) {

           if (in_array($x['e__id'], $unique_users_count)) {

               unset($query_string[$key]);

           } elseif (count($list_config[26600]) && count($CI->X_model->fetch(array(
                   'x__up IN (' . join(',', $list_config[26600]) . ')' => null, //All of these
                   'x__down' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               )))) {

               //Must follow NONE of these sources:
               unset($query_string[$key]);

           } elseif (count($list_config[40793]) && count($CI->X_model->fetch(array(
                   'x__left IN (' . join(',', $list_config[40793]) . ')' => null, //All of these
                   'x__creator' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___6255')) . ')' => null, //DISCOVERIES
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               )))) {

               //They have discovered at-least one, so skip this:
               unset($query_string[$key]);

           } elseif (count($list_config[40791]) && count($list_config[27984])) {

               foreach($list_config[27984] as $limit_27984){
                   if(!count($CI->X_model->fetch(array(
                       'x__up' => $limit_27984,
                       'x__down' => $x['e__id'],
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
               'x__up' => $list_config[34513],
               'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
           ), array('x__down'), 0, 0, sort__e());

           foreach($CI->X_model->fetch(array(
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
               'x__up' => $list_config[34513],
               'x__right !=' => $i['i__id'],
               'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
           ), array('x__right'), 0, 0, array('x__weight' => 'ASC', 'i__message' => 'ASC')) as $link_i){
               array_push($column_i, $link_i);
           }

       }


       if($fetch_contact){
           foreach($query_string as $count => $x){

               //Fetch email & phone:
               $fetch_names = $CI->X_model->fetch(array(
                   'x__up' => 30198, //Full Legal Name
                   'x__down' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               ));
               $fetch_emails = $CI->X_model->fetch(array(
                   'x__up' => 3288, //Email
                   'x__down' => $x['e__id'],
                   'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                   'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               ));
               $fetch_phones = $CI->X_model->fetch(array(
                   'x__up' => 4783, //Phone
                   'x__down' => $x['e__id'],
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
       foreach($column_i as $key => $i2){
           $must_follow = array();
           foreach($CI->X_model->fetch(array(
               'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
               'x__type' => 32235, //Navigation
               'x__right' => $i2['i__id'],
           )) as $follow){
               array_push($must_follow, $follow['x__up']);
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
    $member_e = superpower_unlocked();
    return ( $member_e ? '/@'.$member_e['e__handle'] : '/' );
}

function superpower_unlocked($superpower_e__id = null, $force_redirect = 0)
{

    //Authenticates logged-in members with their session information
    $CI =& get_instance();
    $member_e = $CI->session->userdata('session_up');
    $has_session = ( is_array($member_e) && count($member_e) > 0 && $member_e );

    //Let's start checking various ways we can give member access:
    if ($has_session && !$superpower_e__id) {

        //No minimum level required, grant access IF member is logged in:
        return $member_e;

    } elseif ($has_session && in_array($superpower_e__id, $CI->session->userdata('session_superpowers_unlocked'))) {

        //They are part of one of the levels assigned to them:
        return $member_e;

    }

    //Still here?!
    //We could not find a reason to give member access, so block them:
    if (!$force_redirect) {

        return false;

    } else {

        //Block access:
        if($has_session){
            $goto_url = '/@'.$member_e['e__handle'];
        } else {
            $goto_url = view_app_link(4269).( isset($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '' );
        }

        //Now redirect:
        return redirect_message($goto_url, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="fas fa-exclamation-circle zq6255"></i></span>'.view_unauthorized_message($superpower_e__id).'</div>');
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


function generate_handle($s__type, $str, $suggestion = null, $increment = 1){
    //Generates a Suitable Handle from the title:

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
        $suggestion = ( $s__type==12273 ? 'Idea' : 'Source' ).$suggestion;
    }


    //Make sure no duplicates:
    $CI =& get_instance();
    if($s__type==12273 && count($CI->I_model->fetch(array(
            'LOWER(i__hashtag)' => strtolower($suggestion),
        )))){
        return generate_handle(12273, $str, $suggestion, $increment);
    } elseif($s__type==12274 && count($CI->E_model->fetch(array(
            'LOWER(e__handle)' => strtolower($suggestion),
        )))){
        return generate_handle(12274, $str, $suggestion, $increment);
    } else {
        //All good:
        return $suggestion;
    }

}

function data_type_validate($data_type, $data_value, $data_title){

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
    } elseif($data_type==7657 && (!is_numeric($data_value) || $data_value<0 || $data_value>100)){
        //Percentage:
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a valid '.$e___4592[$data_type]['m__title'].' which is a number between 0 & 100.',
        );
    } elseif(in_array($data_type, $CI->config->item('n___42188'))){

        //Single Choice of Multi Choice source types should not be validated here
        $CI->X_model->create(array(
            'x__type' => 4246, //Platform Bug Reports
            'x__message' => 'data_type_validate() was asked to validate choice options for ['.$data_value.'] ['.$data_title.']',
        ));

    } elseif(in_array($data_type, $CI->config->item('n___42189')) && !filter_var($data_value, FILTER_VALIDATE_URL)){
        //URL:
        return array(
            'status' => 0,
            'message' => $data_title.' must be set to a valid '.$e___4592[$data_type]['m__title'],
        );
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

function validate_handle($str, $i__id = null, $e__id = null){

    $CI =& get_instance();
    $member_e = superpower_unlocked();

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
            'message' => 'Can only contain alphanumneric numbers and letters',
        );

    } elseif (!preg_match('/[a-zA-Z]/', $str)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Must contain at-least one letter between A-Z',
        );

    } elseif (strlen($str) > view_memory(6404,41985)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Must be '.view_memory(6404,41985).' characters or less',
        );

    } elseif ($i__id && array_key_exists($str, $CI->config->item('handle___6287'))) {

        return array(
            'status' => 0,
            'db_duplicate' => 1,
            'message' => 'Handle '.$str.' already being used by an App with the same name!',
        );

    }

    //Syntax good! Now let's check the DB for duplicates
    if($i__id > 0){
        foreach($CI->I_model->fetch(array(
            'i__id !=' => $i__id,
            'LOWER(i__hashtag)' => strtolower($str),
        ), 0) as $matched){
            //Is it active?
            if(!in_array($matched['i__privacy'], $CI->config->item('n___31871')) && $member_e){

                //Since not active we can replace this:
                $CI->I_model->update($matched['i__id'], array(
                    'i__hashtag' => change_handle($matched['i__hashtag']),
                ), true, $member_e['e__id']);

            } else {
                return array(
                    'status' => 0,
                    'db_duplicate' => 1,
                    'message' => 'Hashtag #'.$str.' is already assigned to another idea.',
                );
            }
        }
    } elseif($e__id>0){
        foreach($CI->E_model->fetch(array(
            'e__id !=' => $e__id,
            'LOWER(e__handle)' => strtolower($str),
        ), 0) as $matched){
            //Is it active?
            if(!in_array($matched['e__privacy'], $CI->config->item('n___7358')) && $member_e){

                //Since not active we can replace this:
                $CI->E_model->update($matched['e__id'], array(
                    'e__handle' => change_handle($matched['e__handle']),
                ), true, $member_e['e__id']);

            } else {
                return array(
                    'status' => 0,
                    'db_duplicate' => 1,
                    'message' => 'Handle @'.$str.' is already assigned to another source.',
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

function user_website($x__creator){
    $CI =& get_instance();
    foreach($CI->X_model->fetch(array(
        'x__down' => $x__creator,
        'x__type' => 4251, //New Source Created
    ), array(), 1) as $e_created){
        return $e_created['x__website'];
    }
    foreach($CI->X_model->fetch(array(
        'x__creator' => $x__creator,
    ), array(), 1) as $e_created){
        return $e_created['x__website'];
    }
    return 0;
}

function email_ticket($x__id, $i__hashtag, $x__creator){

    $CI =& get_instance();
    $user_website = user_website($x__creator);

    $additional_info = '';
    foreach($CI->X_model->fetch(array(
        'x__id' => $x__id,
    ), array('x__right')) as $top_i){
        $additional_info = ' for '.view_i_title($top_i, true);
        break;
    }

    foreach($CI->E_model->fetch(array(
        'e__id' => $x__creator,
    )) as $e){
        $CI->X_model->send_dm($x__creator, get_domain('m__title', $x__creator, $user_website).' QR Ticket'.$additional_info,
            'Upon arrival have your QR code ready to be scanned:'.
            "\n\n".'https://'.get_domain('m__message', $x__creator, $user_website).'/'.$i__hashtag.'?e__handle='.$e['e__handle'].'&e__time='.time().'&e__hash='.view_e__hash(time().$e['e__handle'])."\n", array(), 0, $user_website);
    }

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



function e_link_message($x__up, $e__id, $message_text){

    $CI =& get_instance();

    $e_fields = $CI->X_model->fetch(array(
        'x__up' => $x__up,
        'x__down' => $e__id,
        'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
    ));

    if (count($e_fields) > 0) {

        if (strlen($message_text)==0) {

            //Delete:
            $CI->X_model->update($e_fields[0]['x__id'], array(
                'x__privacy' => 6173, //Transaction Removed
            ), $e__id, 6224 /* Member Account Updated */);

            $return = array(
                'status' => 1,
                'message' => 'Field deleted',
            );

        } elseif ($e_fields[0]['x__message'] != $message_text) {

            //Update if not the same:
            $CI->X_model->update($e_fields[0]['x__id'], array(
                'x__message' => $message_text,
            ), $e__id, 6224 /* Member Account Updated */);

            $return = array(
                'status' => 1,
                'message' => 'Field updated',
            );

        } else {

            $return = array(
                'status' => 0,
                'message' => 'Field unchanged',
            );

        }

    } elseif (strlen($message_text) > 0) {

        //Create new transaction:
        $CI->X_model->create(array(
            'x__creator' => $e__id,
            'x__down' => $e__id,
            'x__type' => 4230,
            'x__up' => $x__up,
            'x__message' => $message_text,
        ), true);

        $return = array(
            'status' => 1,
            'message' => 'Field added',
        );

    } else {

        $return = array(
            'status' => 0,
            'message' => 'Field unchanged',
        );

    }

    if($return['status']){
        //Log Account Update transaction type:
        $CI->X_model->create(array(
            'x__creator' => $e__id,
            'x__type' => 6224, //My Account updated
            'x__up' => $x__up,
            'x__down' => $e__id,
            'x__message' => $message_text,
            'x__metadata' => $_POST,
        ));
    }

    return $return;
    
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
                'x__creator' => $e__id,
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
            'x__creator' => $e__id,
            'x__message' => $single_message,
            'x__right' => $template_i__id,
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
                'x__up' => 3288, //Email
                'x__down' => $e__id,
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
    $email_message .= "\n".$email_body."\n";
    $email_message .= '<div class="line">'.view_shuffle_message(12691).'</div>';
    $email_message .= '<div class="line">'.get_domain('m__title', $e__id, $x__website).'</div>';


    if($e__id > 0 && count($es) && (!$template_i__id || !count($CI->X_model->fetch(array(
            'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
            'x__type IN (' . join(',', $CI->config->item('n___42256')) . ')' => null, //Writes
            'x__up' => 31779, //Mandatory Emails
            'x__right' => $template_i__id,
        ))))){
        //User specific notifications:
        $email_message .= '<div class="line"><a href="'.$base_domain.view_app_link(28904).'?e__handle='.$es[0]['e__handle'].'&e__time='.time().'&e__hash='.view_e__hash(time().$es[0]['e__handle']).'" style="font-size:13px;">'.$e___6287[28904]['m__title'].'</a></div>';
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
            'x__right' => $template_i__id,
            'x__creator' => $e__id,
            'x__message' => $subject."\n\n".$email_message,
            'x__metadata' => array(
                'to' => $to_emails,
                'subject' => $subject,
                'message' => $email_message,
                'response' => $response,
            ),
        )));

        //Can we also mark the discovery as complete?
        if($e__id && isset($x_data['x__left']) && $x_data['x__left']>0 && isset($x_data['x__right'])) {
            foreach ($CI->I_model->fetch(array(
                'i__id' => $x_data['x__left'],
            )) as $email_i) {
                $CI->X_model->read_only_complete($e__id, $x_data['x__right'], $email_i, $x_data);
            }
        }

    }


    return $response;

}

function website_setting($setting_id = 0, $initiator_e__id = 0, $x__website = 0, $force_website = true){

    $CI =& get_instance();
    $e_id = 0; //Assume no domain unless found below

    if(!$initiator_e__id){
        $member_e = superpower_unlocked();
        if($member_e && $member_e['e__id']>0){
            $initiator_e__id = $member_e['e__id'];
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



function write_privacy_e($e__handle, $e__id = 0){


    $member_e = superpower_unlocked();
    if(!$member_e || (!strlen($e__handle) && !intval($e__id))){
        //No input
        return false;
    }

    //Ways a Member can modify a source:
    $CI =& get_instance();
    $filters = array(
        'x__type IN (' . join(',', $CI->config->item('n___41944')) . ')' => null, //Source Authors
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__up' => $member_e['e__id'],
    );
    if(strlen($e__handle)){
        $filters['LOWER(e__handle)'] = strtolower($e__handle);
    } elseif(intval($e__id)){
        $filters['e__id'] = $e__id;
    }

    return (

        //Member has Advance source editing superpower
        superpower_unlocked(13422)

        //Member is the source
        || ($member_e && ($e__handle==$member_e['e__handle'] || $e__id==$member_e['e__id']))

        //If Source Follows this Member
        || count($CI->X_model->fetch($filters, array('x__down')))

    );

}

function write_privacy_i($i__hashtag, $i__id = 0){

    $member_e = superpower_unlocked();
    if(!$member_e || (!strlen($i__hashtag) && !intval($i__id))){
        return false;
    }

    //Ways a member can modify an idea:
    $CI =& get_instance();
    $filters = array( //IDEA SOURCE
        'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
        'x__type IN (' . join(',', $CI->config->item('n___31919')) . ')' => null, //IDEA AUTHOR
        'x__up' => $member_e['e__id'],
    );

    if(strlen($i__hashtag)){
        $filters['LOWER(i__hashtag)'] = strtolower($i__hashtag);
    } elseif(intval($i__id)){
        $filters['i__id'] = $i__id;
    }

    return (
        superpower_unlocked(12700) || //WALKIE TALKIE
        (
            count($CI->X_model->fetch($filters, array('x__right')))
        )
    );

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
function flag_for_search_indexing($s__type = null, $s__id = 0) {

    $CI =& get_instance();

    if($s__type && !in_array($s__type , $CI->config->item('n___12761'))){
        return array(
            'status' => 0,
            'message' => 'Object type is invalid',
        );
    } elseif(($s__type && !$s__id) || ($s__id && !$s__type)){
        return array(
            'status' => 0,
            'message' => 'Must define both object type and ID',
        );
    }

    if($s__type==12273){
        //Update idea flag
        $this->I_model->update($s__id, array(
            'i__flag' => true,
        ));
    } elseif($s__type==12274){
        //Update idea flag
        $this->E_model->update($s__id, array(
            'e__flag' => true,
        ));
    }

}


function update_algolia($s__type = null, $s__id = 0) {

    if(!intval(view_memory(6404,12678))){
        return false;
    }

    $CI =& get_instance();

    /*
     *
     * Syncs data with Algolia Index
     *
     * */

    if($s__type && !in_array($s__type , $CI->config->item('n___12761'))){
        return array(
            'status' => 0,
            'message' => 'Object type is invalid',
        );
    } elseif(($s__type && !$s__id) || ($s__id && !$s__type)){
        return array(
            'status' => 0,
            'message' => 'Must define both object type and ID',
        );
    }


    $e___4737 = $CI->config->item('e___4737'); //Idea Status

    //Define the support objects indexed on algolia:
    $s__id = intval($s__id);
    $limits = array();


    if($s__type==12273){
        $focus_field_id = 'i__id';
        $focus_field_privacy = 'i__privacy';
    } elseif($s__type==12274 || $s__type==6287){
        $focus_field_id = 'e__id';
        $focus_field_privacy = 'e__privacy';
    }


    //Load Algolia Index
    $search_index = load_algolia('alg_index');



    //Which objects are we fetching?
    if ($s__type) {

        //We'll only fetch a specific type:
        $fetch_objects = array($s__type);

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

        } elseif (!$s__id && $loop_obj==6287) {

            $db_rows[$loop_obj] = $CI->X_model->fetch(array(
                'x__up' => 6287, //Featured Apps
                'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
            ), array('x__down'), 0);

        }




        //Build the index:
        foreach($db_rows[$loop_obj] as $s) {

            //Prepare variables:
            unset($export_row);
            $export_row = array();


            //Update Weight if single update:
            if($s__id){
                //Update weight before updating this object:
                if($s__type==12273){
                    i__weight_calculator($s);
                } elseif($s__type==12274){
                    e__weight_calculator($s);
                }
            }


            //Attempt to fetch Algolia object ID from object Metadata:
            if($s__type){

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
                $export_row['s__url'] = '/~' . $s['i__hashtag']; //Default to idea, forward to discovery is lacking superpowers
                $export_row['s__privacy'] = intval($s['i__privacy']);
                $export_row['s__cover'] = '';
                $export_row['s__title'] = view_i_title($s, true);
                $export_row['s__cache'] = $s['i__cache'];
                $export_row['s__weight'] = intval($s['i__weight']);

                //Top/Bottom Idea Keywords
                foreach ($CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $CI->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                    'x__left' => $s['i__id'],
                ), array('x__right'), 0, 0, array('x__weight' => 'ASC')) as $i) {
                    $export_row['s__keywords'] .= $i['i__message'] . ' ';
                }
                foreach ($CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'i__privacy IN (' . join(',', $CI->config->item('n___31871')) . ')' => null, //ACTIVE
                    'x__type IN (' . join(',', $CI->config->item('n___42345')) . ')' => null, //Active Sequence 2-Ways
                    'x__right' => $s['i__id'],
                ), array('x__left'), 0, 0, array('x__weight' => 'ASC')) as $i) {
                    $export_row['s__keywords'] .= $i['i__message'] . ' ';
                }

                //Idea Sources Keywords
                foreach($CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___33602')) . ')' => null, //Idea/Source Links Active
                    'x__right' => $s['i__id'],
                ), array('x__up'), 0) as $x){

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
                $export_row['s__url'] = '/@' . $s['e__handle'];
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
                    'x__down' => $s['e__id'], //This follower source
                    'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                    'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
                ), array('x__up'), 0, 0, array('e__title' => 'DESC')) as $x) {

                    //Add tags:
                    array_push($export_row['_tags'], 'z_' . $x['e__id']);

                    //Add Keywords:
                    $export_row['s__keywords'] .= $x['e__title']. ( strlen($x['x__message']) ? ' '.$x['x__message'] : '' ) . ' ';

                }

                //Append Discovery Written Responses to Keywords
                foreach($CI->X_model->fetch(array(
                    'x__privacy IN (' . join(',', $CI->config->item('n___7359')) . ')' => null, //PUBLIC
                    'x__type IN (' . join(',', $CI->config->item('n___29133')) . ')' => null, //Written Responses
                    'x__creator' => $s['e__id'], //This follower source
                ), array('x__creator'), 0, 0, array('x__time' => 'DESC')) as $x){
                    $export_row['s__keywords'] .= $x['x__message'] . ' ';
                }

            } elseif ($loop_obj==6287) {

                //Non-Hidden APPS
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['e__id']);
                $export_row['s__handle'] = $s['e__handle'];
                $export_row['s__url'] = view_app_link($s['e__id']);
                $export_row['s__privacy'] = intval($s['e__privacy']);
                $export_row['s__cover'] = $s['e__cover'];
                $export_row['s__title'] = $s['e__title'];
                $export_row['s__cache'] = '';
                $export_row['s__weight'] = intval($s['e__weight']);

                array_push($export_row['_tags'], 'is_app');

                if(public_app($s)){
                    array_push($export_row['_tags'], 'public_index');
                }

                //Fetch Following:
                foreach($CI->X_model->fetch(array(
                    'x__type IN (' . join(',', $CI->config->item('n___32292')) . ')' => null, //SOURCE LINKS
                    'x__down' => $s['e__id'], //This follower source
                    'x__privacy IN (' . join(',', $CI->config->item('n___7360')) . ')' => null, //ACTIVE
                    'e__privacy IN (' . join(',', $CI->config->item('n___7358')) . ')' => null, //ACTIVE
                ), array('x__up'), 0, 0, array('e__title' => 'DESC')) as $x) {

                    //Add tags:
                    array_push($export_row['_tags'], 'z_' . $x['e__id']);

                    //Add Keywords:
                    $export_row['s__keywords'] .= $x['e__title']. ( strlen($x['x__message']) ? ' '.$x['x__message'] : '' ) . ' ';
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
    if ($s__type) {

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
                        if ($s__type==12273) {
                            $CI->I_model->update($all_db_rows[$key][$focus_field_id], array(
                                'algolia__id' => $algolia_id,
                            ));
                        } elseif ($s__type==12274) {
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

function x__metadata_update($x__id, $new_fields, $x__creator = 0)
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
