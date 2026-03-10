<?php


function post_sort()
{
    return array('chainusertype = \'34513\' DESC' => null, 'chainkey' => 'ASC', 'chaintime' => 'DESC');
}

function user_sort()
{
    return array('chainkey' => 'ASC', 'chaintime' => 'DESC');
}

function string_is_date($str)
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

function discover_chainusertype()
{
    return (isset($_POST['js_request_uri']) && substr($_POST['js_request_uri'], 0, 1) == '/' && substr_count($_POST['js_request_uri'], '/') == 2 ? '/' . strtok(substr($_POST['js_request_uri'], 1), '/') : null);
}


function string_is_icon($string)
{
    return substr_count($string, 'fa-');
}


function post_weight_calculator($i)
{

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->Chains->read(array(
        '(chainpostinput=' . $i['postid'] . ' OR chainpostoutput=' . $i['postid'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

    //Should we update?
    if ($count_x[0]['totals'] != $i['postweight']) {
        $CI->Posts->update($i['postid'], array(
            'postweight' => $count_x[0]['totals'],
        ));
    }

    return $count_x[0]['totals'];

}

function user_weight_calculator($e)
{

    //TODO Improve later (This is a very basic logic)
    $CI =& get_instance();
    $count_x = $CI->Chains->read(array(
        '(chainuseroutput=' . $e['userid'] . ' OR chainuserinput=' . $e['userid'] . ' OR chainusercreator=' . $e['userid'] . ')' => null,
    ), array(), 0, 0, array(), 'COUNT(chainid) as totals');

    //Should we update?
    if ($count_x[0]['totals'] != $e['userweight']) {
        $CI->Users->update($e['userid'], array(
            'userweight' => $count_x[0]['totals'],
        ));
    }

    return $count_x[0]['totals'];

}


function random_string($length_of_string)
{
    $characters = '123456789abcdefghijklmnpqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length_of_string; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}


function phone_href($chainusertype, $number)
{

    $number = preg_replace("/[^0-9]/", "", $number);

    if ($chainusertype == 13815) {
        //WhatsApp
        return 'https://wa.me/' . $number;
    } elseif ($chainusertype == 20337) {
        //Telegram
        return 'https://t.me/' . $number;
    } else {
        //general number:
        return 'tel:' . $number;
    }
}

function usercover_generator($userid)
{
    $CI =& get_instance();
    $fetch = $CI->config->item('users___' . $userid);
    return trim(one_two_explode('class="', '"', $fetch[array_rand($fetch)]['m__cover']));
}

function prefix_common_words($strs)
{

    $prefix_common_words = array();

    if (count($strs) >= 2) {

        $prefix_common_words = explode(' ', $strs[0]);

        foreach ($strs as $str) {

            if (!count($prefix_common_words)) {
                break;  //No common words, terminate
            }

            $words = explode(' ', $str);
            foreach ($words as $word_count => $word) {
                if (!isset($prefix_common_words[$word_count])) {

                    break;

                } elseif ($prefix_common_words[$word_count] != $word) {

                    //We have some common words left, continue to remove these words onwards:
                    $total_words = count($prefix_common_words);

                    for ($i = $word_count; $i <= $total_words; $i++) {
                        if (isset($prefix_common_words[$i])) {
                            unset($prefix_common_words[$i]);
                        }
                    }

                    break;  //No common words, terminate
                }
            }
        }
    }

    return (count($prefix_common_words) ? join(' ', $prefix_common_words) . ' ' : false);

}


function reset_cache($chainusercreator)
{
    $CI =& get_instance();
    $count = 0;
    foreach ($CI->Chains->read(array(
        'chainusertype' => 44176, //User View
        'chainuserinput' => 14599, //Cache App
        'chainuseroutput >' => 0,
    )) as $delete_cahce) {
        //Void:
        $count += $CI->Chains->delete($delete_cahce['chainid'], $chainusercreator);
    }
    return $count;
}

function post_spots_remaining($postid)
{

    $CI =& get_instance();
    $user_session = user_session();

    //Any Limits on Selection?
    $spots_remaining = -1; //No limits
    $max_available = $CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $postid,
        'chainuserinput' => 26189,
    ), array(), 1);
    if (count($max_available) && is_numeric($max_available[0]['chainvalue'])) {

        //We have a limit! See if we've met it already:
        $query_filters = array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___40986')) . ')' => null, //DISCOVERIES
            'chainpostinput' => $postid,
        );
        if ($user_session) {
            //Do not count current user to give them option to edit & resubmit:
            $query_filters['chainusercreator !='] = $user_session['userid'];
        }


        $query = $CI->Chains->read($query_filters, array(), 1, 0, array(), 'COUNT(chainid) as totals');
        $current_discoveries = $query[0]['totals'];

        $spots_remaining = intval($max_available[0]['chainvalue']) - $current_discoveries;
        if ($spots_remaining < 0) {
            $spots_remaining = 0;
        }
    }

    return $spots_remaining;
}

function object_to_array($obj)
{
    //only process if it's an object or array being passed to the function
    if (is_object($obj) || is_array($obj)) {
        $ret = (array)$obj;
        foreach ($ret as &$item) {
            //recursively process EACH element regardless of type
            $item = object_to_array($item);
        }
        return $ret;
    } //otherwise (i.e. for scalar values) return without modification
    else {
        return $obj;
    }
}

function post_redirect_url($i)
{
    $CI =& get_instance();
    if (strlen($i['postmessageraw']) && count($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostinput' => $i['postid'],
            'chainuserinput' => 43871, //Redirect URL
        )))) {
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $i['postmessageraw'], $match);
        foreach ($match[0] as $url) {
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return $url;
            }
        }
    }

    return false;
}

function post_popup_url($i)
{
    if (!user_session()) {
        return false;
    }
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
        'chainuserinput' => 44266, //Popup URL
    )) as $popup_url) {
        if (filter_var($popup_url['chainvalue'], FILTER_VALIDATE_URL)) {
            return $popup_url['chainvalue'];
        }
    }
    return false;
}

function post_required($i)
{
    $CI =& get_instance();
    return count($CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
        'chainuserinput' => 28239, //Required
    )));
}

function get_redirected($url, $message = null, $log_error = false, $standalone = true)
{
    //An error handling function that would redirect member to $url with optional $message
    //Do we have a Message?
    $CI =& get_instance();
    $user_session = user_session();
    $user_id = ($user_session ? $user_session['userid'] : 14068);

    if ($message) {
        $CI->session->set_flashdata('flash_message', $message);
    }

    if ($log_error) {
        //Log thie error:
        log_error($url . ' ' . stripslashes($message), array(
            'chainuseroutput' => $user_id,
            'chainusercreator' => $user_id,
        ));
    }

    if (!$standalone) {

        //Do not redirect:
        return ($message ? $message : 'Error Message');

    } else {
        if (!$message) {
            //Do a permanent redirect if message not available:
            header("Location: " . $url, true, 301);
            return false;
        } else {
            header("Location: " . $url, true);
            return false;
        }
    }

}

function session_delete()
{
    $CI =& get_instance();
    $CI->session->sess_destroy();
    cookie_delete();
}

function cookie_delete()
{
    unset($_COOKIE['auth_cookie']);
    setcookie('auth_cookie', null, -1, '/');
}

function verify_cookie()
{

    //Authenticate Cookie:
    $cookie_parts = explode('ABCEFG', $_COOKIE['auth_cookie']);
    $CI =& get_instance();

    $es = $CI->Users->read(array(
        'userid' => $cookie_parts[0],
    ));

    if (count($es) && $cookie_parts[2] == view_hash($cookie_parts[0] . $cookie_parts[1])) {

        //Assign session & log Chain:
        $CI->Users->activate($es[0], false, true);
        return $es[0];

    } else {

        //Cookie was invalid
        cookie_delete();
        return false;

    }

}


function view_tree($i, $open_by_default = true, $focus_e = false)
{

    $CI =& get_instance();
    $has_children = count($i['treeposts']);
    $users___11035 = $CI->config->item('users___11035'); //Encyclopedia

    echo '<div class="slim_title">';

    echo '<div class="hideIfEmpty">';

    echo '<a href="javascript:void(0);" onclick="$(\'.frame_id_' . $i['postid'] . '\').toggleClass(\'hidden\')">';
    echo '<span class="icon-block-sm ' . ($open_by_default ? 'hidden' : '') . ' frame_id_' . $i['postid'] . '"><i class="far fa-circle-plus"></i></span>';
    echo '<span class="icon-block-sm ' . ($open_by_default ? '' : 'hidden') . ' frame_id_' . $i['postid'] . '"><i class="far fa-circle-minus"></i></span>';
    echo '<span class="' . (!isset($i['user_discovered']) || count($i['user_discovered']) ? ' main__title ' : '') . '">' . view_post_title($i, true) . '</span>';
    echo '</a>';

    echo(isset($i['user_discovered']['chainkey']) && intval($i['user_discovered']['chainkey']) > 1 ? $i['user_discovered']['chainkey'] . 'x ' : '');

    echo (isset($i['user_written_response']['postmessageraw']) && strlen($i['user_written_response']['postmessageraw']) ? ' ' . $i['user_written_response']['postmessageraw'] : '');


    echo '<span class="float_right inner_items ' . ($open_by_default ? '' : 'hidden') . ' frame_id_' . $i['postid'] . '">';
    //Chain Highlights
    foreach ($CI->config->item('users___1592660') as $userid => $m) {

        $opener = '<span ';
        $closer = '</span>';

        if (isset($i['stats']) && $userid == 12273 && $i['stats']['all_posts'] > 0) {

            if ($CI->uri->segment(1) == 'doc') {
                $opener = '<a href="/' . $i['posthashtag'] . '" ';
                $closer = '</a>';
            }
            echo $opener . 'data-toggle="tooltip" data-placement="top" title="' . $m['m__name'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . $i['stats']['all_posts'] . '</span>' . $closer;

        } elseif (isset($i['stats']) && $userid == 1592672 && ($i['treelevel'] > 0 || $i['stats']['max_level'] > 0)) {

            if ($CI->uri->segment(1) == 'doc') {
                $opener = '<a href="/doc/' . $i['posthashtag'] . '" ';
                $closer = '</a>';
            }
            echo $opener . ' data-toggle="tooltip" data-placement="top" title="' . $m['m__name'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . $i['treelevel'] . '/' . $i['stats']['max_level'] . '</span>' . $closer;

        } elseif (isset($i['stats']) && $userid == 1592682 && ($i['stats']['min_choices'] > 0 || $i['stats']['max_choices'] > 0)) {

            echo $opener . ' data-toggle="tooltip" data-placement="top" title="' . $m['m__name'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . ($i['stats']['min_choices'] > 0 && $i['stats']['min_choices'] != $i['stats']['max_choices'] ? $i['stats']['min_choices'] . '-' : '') . $i['stats']['max_choices'] . '</span>' . $closer;

        } elseif (isset($i['stats']) && $userid == 1592686 && ($i['stats']['min_posts'] > 0 || $i['stats']['max_posts'] > 0)) {

            echo $opener . ' data-toggle="tooltip" data-placement="top" title="' . $m['m__name'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . ($i['stats']['min_posts'] != $i['stats']['max_posts'] ? $i['stats']['min_posts'] . '-' : '') . $i['stats']['max_posts'] . '</span>' . $closer;

        } elseif ($userid == 31777) {

            if (post_is_startable($i)) {
                $opener = '<a href="/' . $i['posthashtag'] . '/start" ';
                $closer = '</a>';
            }

            $max_available = $CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $i['postid'],
                'chainuserinput' => 26189,
            ), array(), 1);

            echo $opener . ' data-toggle="tooltip" data-placement="top" title="' . $m['m__name'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : '') . '"><span class="icon-block-sm">' . $m['m__cover'] . '</span><span>' . (count($max_available) && is_numeric($max_available[0]['chainvalue']) ? '<span title="' . $users___11035[26189]['m__name'] . '" style="border-bottom: 1px dotted #000000;">/' . intval($max_available[0]['chainvalue']) . '</span>' : '') . '</span>' . $closer;

        } else {
            //block
            echo $opener . '>&nbsp;' . $closer;
        }
    }
    echo '</span>';
    echo '<div class="doclear">&nbsp;</div>';

    echo '<div class="grey hide-subline maxwidth hideIfEmpty remove_first_line extra_message ' . ($open_by_default || !$has_children ? '' : 'hidden') . ' frame_id_' . $i['postid'] . '">' . view_postmessageraw($i) . '</div>';
    echo '</div>';


    //Post Discovery Expanded List
    if (isset($_GET['expand'])) {
        $already_shown = array();
        foreach ($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainpostinput' => $i['postid'],
        ), array('chainusercreator'), 0, 0, array('chainid' => 'DESC')) as $creator) {
            if (in_array($creator['chainusercreator'], $already_shown)) {
                continue;
            }
            array_push($already_shown, $creator['chainusercreator']);
            echo '<div class="maxwidth cover_x_' . $creator['chainid'] . '" style="padding:3px 0;">' . (strlen($_GET['expand']) > 1 ? '<a href="' . view_app_chain(44328) . '/' . $_GET['expand'] . '@' . $creator['userhandle'] . '" target="_blank" title="' . $users___11035[44328]['m__name'] . '">' : '') . '<span class="icon-block-sm grey">' . $users___11035[44328]['m__cover'] . '</span></a> <a href="' . view_memory(42903, 42902) . $creator['userhandle'] . '"><span class="icon-block">' . view_cover($creator['usercover']) . '</span><span class="grey">@' . $creator['userhandle'] . '</span></a> <span class="grey"><a href="javascript:void(0);" onclick="chain_delete(' . $creator['chainid'] . ', ' . $creator['chainid'] . ',\'' . $i['posthashtag'] . '\')" title="' . $users___11035[10673]['m__name'] . '" class="grey">' . $users___11035[10673]['m__cover'] . '</a> ' . view_time_difference($creator['chaintime'], false) . '</span></div>';
            if (count($already_shown) >= view_memory(6404, 11064)) {
                break;
            }
        }
    } elseif (isset($focus_e['userid']) && !count($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainpostinput' => $i['postid'],
            'chainusercreator' => $focus_e['userid'],
        )))) {
        //Not discovered by this user:
        echo '<span class="grey inline-block"><span class="icon-block-sm"><i class="far fa-eye-slash"></i></span>Not Yet Discovered</span>';
    }


    //Post Filters:
    $filters_ui = '';
    if (isset($i['post_list_config'])) {
        //Post<>User Settings:
        $current_userid = 0;
        foreach ($CI->config->item('users___43006') as $userid => $m) {
            foreach ($i['post_list_config']['full_config_' . $userid] as $filtered_user) {
                if (!$current_userid) {
                    $current_userid = $userid;
                }
                if (strlen($filters_ui) && $current_userid != $userid) {
                    $current_userid = $userid;
                    $filters_ui .= '<div class="and_filter">-AND-</div>';
                }
                $filters_ui .= '<div><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__name'] . ': <a href="/@' . $filtered_user['userhandle'] . '"><span class="icon-block-sm">' . view_cover($filtered_user['usercover']) . '</span>' . $filtered_user['username'] . '</a></div>';
            }
        }
        //Post<>Post Settings:
        foreach ($CI->config->item('users___40792') as $userid => $m) {
            foreach ($i['post_list_config']['full_config_' . $userid] as $filtered_post) {
                if (!$current_userid) {
                    $current_userid = $userid;
                }
                if (strlen($filters_ui) && $current_userid != $userid) {
                    $current_userid = $userid;
                    $filters_ui .= '<div class="and_filter">-AND-</div>';
                }
                $filters_ui .= '<div><span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__name'] . ': <a href="/' . $filtered_post['posthashtag'] . '">' . view_post_title($filtered_post) . '</a></div>';
            }
        }
    }
    if ($filters_ui) {
        $users___11035 = $CI->config->item('users___11035'); //Encyclopedia
        echo '<div class="hideIfEmpty filter_data ' . ($open_by_default || !$has_children ? '' : 'hidden') . ' frame_id_' . $i['postid'] . '">';
        echo '<h3>' . $users___11035[40946]['m__cover'] . ' ' . $users___11035[40946]['m__name'] . ':</h3>';
        echo $filters_ui;
        echo '</div>';
    }

    foreach ($i['treeposts'] as $next_post) {
        echo '<div class="sub_frame ' . ($open_by_default ? '' : 'hidden') . ' frame_id_' . $i['postid'] . '">';
        view_tree($next_post, (isset($_GET['view_all']) ? true : false));
        echo '</div>';
    }

    echo '</div>';
}


function post_list_config($postid, $access_limit = true)
{

    $CI =& get_instance();

    $post_list_config = array(); //To compile the settings of this sheet:

    foreach ($CI->config->item('users___40792') as $chainusertype => $m) {
        $post_list_config[intval($chainusertype)] = array(); //Assume no chains for this type
        $post_list_config['full_config_' . $chainusertype] = array(); //Assume no chains for this type
    }
    foreach ($CI->config->item('users___43006') as $chainusertype => $m) {
        $post_list_config[intval($chainusertype)] = array(); //Assume no chains for this type
        $post_list_config['full_config_' . $chainusertype] = array(); //Assume no chains for this type
    }

    //Now search for these settings across Users:
    foreach ($CI->Chains->read(array(
        'chainpostinput' => $postid,
        'chainusertype IN (' . join(',', $CI->config->item('userids___43006')) . ')' => null,
    ), array('chainuserinput'), 0, 0, array(), '*', null, $access_limit) as $setting_chain) {
        array_push($post_list_config[intval($setting_chain['chainusertype'])], intval($setting_chain['chainuserinput']));
        array_push($post_list_config['full_config_' . $setting_chain['chainusertype']], $setting_chain);
    }

    //Now search for these settings across posts:
    foreach ($CI->Chains->read(array(
        'chainpostinput' => $postid,
        'chainusertype IN (' . join(',', $CI->config->item('userids___40792')) . ')' => null,
    ), array('chainpostoutput'), 0, 0, array(), '*', null, $access_limit) as $setting_chain) {
        array_push($post_list_config[intval($setting_chain['chainusertype'])], intval($setting_chain['chainpostoutput']));
        array_push($post_list_config['full_config_' . $setting_chain['chainusertype']], $setting_chain);
    }

    return $post_list_config;
}


function user_list_config($userid, $access_limit = true)
{

    $CI =& get_instance();

    $user_list_config = array(); //To compile the settings of this sheet:
    $memory_detected = is_array($CI->config->item('userids___6287')) && count($CI->config->item('userids___6287'));
    if (!$memory_detected) {
        return false;
    }

    foreach ($CI->config->item('users___1645191') as $chainusertype => $m) {
        $user_list_config[intval($chainusertype)] = array(); //Assume no chains for this type
        $user_list_config['full_config_' . $chainusertype] = array(); //Assume no chains for this type
    }

    //Now search for these settings across Users:
    foreach ($CI->Chains->read(array(
        'chainuserinput >' => 0,
        'chainuseroutput' => $userid,
        'chainusertype IN (' . join(',', $CI->config->item('userids___1645191')) . ')' => null,
    ), array('chainuserinput'), 0, 0, array(), '*', null, $access_limit) as $setting_chain) {
        array_push($user_list_config[intval($setting_chain['chainusertype'])], intval($setting_chain['chainuserinput']));
        array_push($user_list_config['full_config_' . $setting_chain['chainusertype']], $setting_chain);
    }

    return $user_list_config;
}


function post_settings($posthashtag, $fetch_contact = false)
{

    $CI =& get_instance();
    $mixed_column = array();
    $user_column = array();
    $post_column = array();
    $contact_details = array(
        'full_list' => '',
        'email_list' => '',
        'email_count' => 0,
        'phone_count' => 0,
    );

    foreach ($CI->Posts->read(array(
        'LOWER(posthashtag)' => strtolower($posthashtag),
    )) as $i) {

        $post_list_config = post_list_config($i['postid']);

        //Generate filter:
        $query_string_all = array();
        if (count($post_list_config[40791])) {

            //If post discovered Any
            $query_string_all = $CI->Chains->read(array(
                'chainpostinput IN (' . join(',', $post_list_config[40791]) . ')' => null,
                'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainusercreator'), 0, 0, array('chainid' => 'DESC'));

        } elseif (count($post_list_config[44161])) {

            //If post discovered All
            $query_string_all = $CI->Chains->read(array(
                'chainpostinput IN (' . join(',', $post_list_config[44161]) . ')' => null,
                'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainusercreator'), 0, 0, array('chainid' => 'DESC'));

        } elseif (count($post_list_config[27984])) {

            //IF Follows Any
            $query_string_all = $CI->Chains->read(array(
                'chainuserinput IN (' . join(',', $post_list_config[27984]) . ')' => null,
                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ), array('chainuseroutput'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC'));

        } elseif (count($post_list_config[43513])) {

            //IF Follows All
            $query_string_all = $CI->Chains->read(array(
                'chainuserinput IN (' . join(',', $post_list_config[43513]) . ')' => null,
                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ), array('chainuseroutput'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC'));

        } else {

            //All Discoveries:
            $query_string_all = $CI->Chains->read(array(
                'chainpostinput' => $i['postid'],
                'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            ), array('chainusercreator'), 0, 0, array('chainkey' => 'ASC', 'chainid' => 'DESC'));

        }

        //Filter list:
        $query_string_filtered = array();
        $unique_users_count = array();
        foreach ($query_string_all as $key => $x) {
            if (in_array(intval($x['userid']), $unique_users_count)) {
                //Already added:
                continue;
            } elseif (!post_access(null, $i['postid'], $i, $x['userid'], $post_list_config)) {
                //Does not have access:
                continue;
            } else {
                //Passed all filters:
                array_push($query_string_filtered, $x);
                array_push($unique_users_count, intval($x['userid']));
            }
        }


        //Determine columns if any:
        $pinned_columns = array();
        foreach ($CI->Chains->read(array(
            'chainpostinput' => $i['postid'],
            'chainusertype' => 34513, //Pinned
        ), array('chainuserinput'), 0) as $setting_chain) {
            array_push($pinned_columns, intval($setting_chain['userid']));
        }
        if (count($pinned_columns)) {
            //Add to results:
            $post_list_config[34513] = $pinned_columns;

            $user_column = $CI->Chains->read(array(
                'chainuserinput IN (' . join(',', $pinned_columns) . ')' => null,
                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ), array('chainuseroutput'), 0, 0, user_sort());
            $mixed_column = $user_column;

            foreach ($CI->Chains->read(array(
                'chainuserinput IN (' . join(',', $pinned_columns) . ')' => null,
                'chainusertype IN (' . join(',', $CI->config->item('userids___33602')) . ')' => null, //Post/User Chains Active
                'chainpostinput !=' => $i['postid'],
            ), array('chainpostinput'), 0, 0, array('postmessageraw' => 'ASC')) as $chain_i) {
                array_push($post_column, $chain_i);
                array_push($mixed_column, $chain_i);
            }
        }


        //Append regular references to plot in the sheet:
        foreach ($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___2108854')) . ')' => null, //Sheet Ideas
            'chainpostinput' => $i['postid'],
        ), array('chainpostoutput'), 0, 0, array('chainkey' => 'ASC')) as $chain_i) {
            //array_push($post_column, $chain_i);
            $mixed_column[intval($chain_i['chainkey'])] = $chain_i;
        }
        foreach ($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___2108865')) . ')' => null, //Sheet Players
            'chainpostinput' => $i['postid'],
        ), array('chainuserinput'), 0, 0, array('chainkey' => 'ASC')) as $chain_e) {
            //array_push($user_column, $chain_e);
            $mixed_column[intval($chain_e['chainkey'])] = $chain_e;
        }


        if ($fetch_contact) {
            foreach ($query_string_filtered as $count => $x) {

                //Fetch email & phone:
                $fetch_names = $CI->Chains->read(array(
                    'chainuserinput' => 42584, //First Name
                    'chainuseroutput' => $x['userid'],
                    'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ));
                $fetch_emails = $CI->Chains->read(array(
                    'chainuserinput' => 3288, //Email
                    'chainuseroutput' => $x['userid'],
                    'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ));
                $fetch_phones = $CI->Chains->read(array(
                    'chainuserinput' => 4783, //Phone
                    'chainuseroutput' => $x['userid'],
                    'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ));

                $query_string_filtered[$count]['extension_name'] = (count($fetch_names) && strlen($fetch_names[0]['chainvalue']) ? $fetch_names[0]['chainvalue'] : $x['username']);
                $query_string_filtered[$count]['extension_email'] = (count($fetch_emails) && filter_var($fetch_emails[0]['chainvalue'], FILTER_VALIDATE_EMAIL) ? $fetch_emails[0]['chainvalue'] : false);
                $query_string_filtered[$count]['extension_phone'] = (count($fetch_phones) && strlen($fetch_phones[0]['chainvalue']) >= 10 ? $fetch_phones[0]['chainvalue'] : false);

                $contact_details['full_list'] .= $query_string_filtered[$count]['extension_name'] . "\t" . $query_string_filtered[$count]['extension_email'] . "\t" . $query_string_filtered[$count]['extension_phone'] . "\n";


                if ($query_string_filtered[$count]['extension_email']) {
                    $contact_details['email_count']++;
                    $contact_details['email_list'] .= (strlen($contact_details['email_list']) ? ", " : '') . $query_string_filtered[$count]['extension_email'];
                }
                if ($query_string_filtered[$count]['extension_phone']) {
                    $contact_details['phone_count']++;
                }
            }
        }

        return array(
            'i' => $i,
            'list_config' => $post_list_config,
            'user_column' => $user_column,
            'post_column' => $post_column,
            'mixed_column' => $mixed_column,
            'query_string_filtered' => $query_string_filtered,
            'contact_details' => $contact_details, //Optional addon
        );
    }
}


function count_chain_groups($chainusertype, $chaintime_start = null, $chaintime_end = null)
{

    $CI =& get_instance();

    $query_filters = array(
        'chainusertype IN (' . join(',', (is_array($CI->config->item('userids___' . $chainusertype)) ? $CI->config->item('userids___' . $chainusertype) : array($chainusertype))) . ')' => null,
    );

    if (strtotime($chaintime_start) > 0) {
        $query_filters['chaintime >='] = $chaintime_start;
    }
    if (strtotime($chaintime_end) > 0) {
        $query_filters['chaintime <='] = $chaintime_end;
    }

    //Fetch Results:
    $query = $CI->Chains->read($query_filters, array(), 1, 0, array(), 'COUNT(chainid) as totals');
    return intval($query[0]['totals']);

}


function home_url()
{
    $CI =& get_instance();
    $user_session = user_session();
    return ($user_session ? view_memory(42903, 42902) . $user_session['userhandle'] : view_memory(42903, 14565));
}

function post_is_startable($i)
{
    $CI =& get_instance();
    return count($CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
        'chainuserinput' => 4235,
    )));
}


function remove_none_utf8($string)
{
    return preg_replace('/[\x00-\x1F\x7F-\xFF]/', ' ', $string);
}


function user_session($superpower_userid = null, $force_redirect = 0, $session_user_session = false)
{

    if (isset($session_user_session['userid'])) {
        //We have the user!
        return $session_user_session;
    }
    //Authenticates logged-in members with their session information
    $CI =& get_instance();
    $user_session = $CI->session->userdata('session_user');

    //Let's start checking various ways we can give member access:
    if ($user_session && !$superpower_userid) {

        //No minimum level required, grant access IF member is logged in:
        return $user_session;

    } elseif ($user_session && in_array($superpower_userid, $CI->session->userdata('session_superpowers_unlocked'))) {

        //They are part of one of the levels assigned to them:
        return $user_session;

    }

    //Still here?!
    //We could not find a reason to give member access, so block them:
    if (!$force_redirect) {

        return false;

    } else {

        //Block access:
        if ($user_session) {
            $goto_url = view_memory(42903, 42902) . $user_session['userhandle'];
        } else {
            $goto_url = view_app_chain(4269) . (isset($_SERVER['REQUEST_URI']) ? '?url=' . urlencode($_SERVER['REQUEST_URI']) : '');
        }

        //Now redirect:
        return get_redirected($goto_url, '<div class="alert alert-danger" role="alert"><span class="icon-block"><i class="far fa-exclamation-circle"></i></span>' . blocked_reasoning($superpower_userid) . '</div>');
    }

}


function get_server($var_name)
{
    return (isset($_SERVER[$var_name]) ? $_SERVER[$var_name] : null);
}

function html_input_type($data_type)
{
    $CI =& get_instance();
    $users___42291 = $CI->config->item('users___42291'); //HTML Input Types
    if (isset($users___42291[$data_type]['m__message']) && strlen($users___42291[$data_type]['m__message'])) {
        return $users___42291[$data_type]['m__message'];
    } else {
        //Default option:
        return 'text';
    }
}

function js_php_redirect($url, $timer = 0)
{
    echo '<script> $(document).ready(function () { js_redirect(\'' . $url . '\', ' . $timer . '); }); </script>';
}


function generate_user($focus__node, $str, $suggestion = null, $increment = 1)
{

    //Generates a Suitable User from the title:
    $CI =& get_instance();

    //Previous suggestion did not work, let's tweak and try again:
    $max_allowed_length = view_memory(6404, 41985);
    $max_adj_length = $max_allowed_length - 3; //Reduce target_element to give space for $increment extension up to 99999
    $recommended_length = $max_allowed_length / 2;

    if (strlen($suggestion)) {

        //Previous suggestion that was a duplicate, so it needs to be modified:
        if (strlen($suggestion) > $max_adj_length) {
            $suggestion = substr($suggestion, 0, $max_adj_length);
        }
        $suggestion = ($increment == 1 ? $suggestion : substr($suggestion, 0, -strlen($increment))) . $increment;
        $increment++;

    } else {

        //Create new suggestion from string:
        $str = preg_replace("/[^A-Za-z0-9]/", "", $str);
        if (strlen($str) > $max_allowed_length) {
            //Shorten and remove the last word:
            $word_arr = explode(' ', substr($str, 0, $max_allowed_length));
            unset($word_arr[count($word_arr) - 1]);
            $str = join(' ', $word_arr);
        }
        $suggestion = preg_replace("/[^A-Za-z0-9]/", '', $str);

    }

    if (strlen($suggestion) < 3 || is_numeric($suggestion)) {
        $suggestion = ($focus__node == 12273 ? 'Post' : 'User') . $suggestion;
    }


    //Make sure no duplicates:
    if ($focus__node == 12273 && count($CI->Posts->read(array(
            'LOWER(posthashtag)' => strtolower($suggestion),
        )))) {
        return generate_user(12273, $str, $suggestion, $increment);
    } elseif ($focus__node == 12274 && count($CI->Users->read(array(
            'LOWER(userhandle)' => strtolower($suggestion),
        )))) {
        return generate_user(12274, $str, $suggestion, $increment);
    } else {
        //All good:
        return $suggestion;
    }

}


function process_media($postid, $uploaded_media)
{

    $CI =& get_instance();
    $user_session = user_session();


    if (!$user_session) {
        return false;
    }

    //Fetch submitted media:
    $upload_media_typeids = array();
    if (count($uploaded_media) > 0) {

        //We have media to process:
        $sort_count = 0; //Reset sorting to compare to submitted media
        foreach ($uploaded_media as $upload_media) {

            if (!$upload_media['userid']) {
                //Adding new media
                //Search eTag to see if we already have it:
                $etag_detected = false;
                if (isset($upload_media['media_cache']['etag']) && strlen($upload_media['media_cache']['etag'])) {
                    //We already have this asset, return user:
                    foreach ($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuserinput' => 42662, //etag
                        'chainvalue' => $upload_media['media_cache']['etag'],
                    ), array('chainuseroutput'), 1) as $existing_media) {
                        $upload_media['userid'] = $existing_media['userid'];
                        $etag_detected = true;
                    }
                }

                if (!$upload_media['userid']) {

                    //Create User for this new media:
                    $added_e = $CI->Users->create(array(
                        'uservalue' => $upload_media['uservalue'],
                        'usercover' => ($upload_media['media_typeid'] == 4259 /* Audio has no thumbnail! */ ? 'far fa-volume-up' : $upload_media['usercover']),
                    ), $user_session['userid']);
                    if (!$added_e['status']) {
                        log_error('Failed to create a new User for [' . $upload_media['uservalue'] . '] with cover [' . $upload_media['usercover'] . ']', array(
                            'chainuseroutput' => $upload_media['userid'],
                        ));
                        continue;
                    }

                    //Create new media and assign ID:
                    $upload_media['userid'] = $added_e['user_create']['userid'];

                    //new asset, create new User and insert tags
                    $users___32088 = $CI->config->item('users___32088'); //Platform Variables
                    foreach ($CI->config->item('users___42679') as $chainusertype => $m) {

                        //Ensure variable name exists so we can check the API call:
                        $target_variable = false;
                        if (isset($users___32088[$chainusertype]['m__message'])) {
                            //Determine if variable exists
                            if (in_array($chainusertype, $CI->config->item('userids___42763')) && isset($upload_media['media_cache']['video'][$users___32088[$chainusertype]['m__message']])) {
                                //Video info:
                                $target_variable = $upload_media['media_cache']['video'][$users___32088[$chainusertype]['m__message']];
                            } elseif (in_array($chainusertype, $CI->config->item('userids___42675')) && isset($upload_media['media_cache']['audio'][$users___32088[$chainusertype]['m__message']])) {
                                //Audio info:
                                $target_variable = $upload_media['media_cache']['audio'][$users___32088[$chainusertype]['m__message']];
                            } elseif (isset($upload_media['media_cache'][$users___32088[$chainusertype]['m__message']])) {
                                //Media info:
                                $target_variable = $upload_media['media_cache'][$users___32088[$chainusertype]['m__message']];
                            }
                        }
                        if (!strlen($target_variable) || $target_variable == '0') {
                            //This variable does not have a value, move on
                            continue;
                        }

                        //We have a variable, see what it is
                        if (in_array($chainusertype, $CI->config->item('userids___33331'))) {

                            //Single select that needs auto creation of Users if missing:
                            $child_id = 0;
                            foreach ($CI->Chains->read(array(
                                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                                'chainuserinput' => $chainusertype,
                                'uservalue' => $target_variable,
                            ), array('chainuseroutput'), 1, 0, array('chainid' => 'ASC')) as $child_user) {
                                $child_id = $child_user['userid'];
                            }

                            //If not found create the child:
                            if (!$child_id) {
                                $added_child = $CI->Users->create(array(
                                    'uservalue' => $target_variable,
                                ));
                                if (!$added_child['status']) {
                                    log_error('Failed to create a new User for [' . $target_variable . ']', array(
                                        'chainuseroutput' => $chainusertype,
                                    ));
                                    continue;
                                }

                                //Add chains for this new User:
                                $CI->Chains->create(array(
                                    'chainusercreator' => $user_session['userid'],
                                    'chainuserinput' => $chainusertype,
                                    'chainuseroutput' => $added_child['user_create']['userid'],
                                    'chainusertype' => 4230,
                                ));

                                //Assign child User:
                                $child_id = $added_child['user_create']['userid'];

                            }

                            if ($child_id) {
                                //Child User found, simply chain:
                                $CI->Chains->create(array(
                                    'chainusercreator' => $user_session['userid'],
                                    'chainuserinput' => $child_id,
                                    'chainuseroutput' => $upload_media['userid'],
                                    'chainusertype' => 4230,
                                ));
                            }

                        } else {

                            //Save variable as is:
                            $CI->Chains->create(array(
                                'chainusercreator' => $user_session['userid'],
                                'chainuserinput' => $chainusertype,
                                'chainuseroutput' => $upload_media['userid'],
                                'chainvalue' => $target_variable,
                                'chainusertype' => 4230,
                            ));

                        }
                    }
                }

                //By now have the media User, create necessary chains:
                if ($upload_media['userid'] && $upload_media['media_typeid']) {

                    //Chain to User as Uploader:
                    if (!count($CI->Chains->read(array(
                        'chainuserinput' => $user_session['userid'],
                        'chainuseroutput' => $upload_media['userid'],
                        'chainusertype IN (' . join(',', $CI->config->item('userids___42657')) . ')' => null, //Uploads
                    )))) {
                        $CI->Chains->create(array(
                            'chainusercreator' => $user_session['userid'],
                            'chainuserinput' => $user_session['userid'],
                            'chainuseroutput' => $upload_media['userid'],
                            'chainusertype' => ($etag_detected ? 42849 : 42659), //Reupload vs Upload
                            'chainvalue' => $upload_media['playback_code'],
                        ));
                    }


                    //Chain to Media Type:
                    if (!count($CI->Chains->read(array(
                        'chainuserinput' => $upload_media['media_typeid'],
                        'chainuseroutput' => $upload_media['userid'],
                        'chainusertype' => 4230,
                    )))) {
                        $CI->Chains->create(array(
                            'chainusercreator' => $user_session['userid'],
                            'chainuserinput' => $upload_media['media_typeid'],
                            'chainuseroutput' => $upload_media['userid'],
                            'chainusertype' => 4230,
                            'chainvalue' => $upload_media,
                        ));
                    }

                }
            }

            //Add this to the submitted ones:
            $upload_media_typeids[$sort_count] = $upload_media['userid'];
            $sort_count++;

        }
    }

    return true;

}

function add_media($uploaded_media)
{

    $CI =& get_instance();
    $user_session = user_session();
    if (!$user_session || !count($uploaded_media)) {
        return false;
    }

    //We have media to process:
    foreach ($uploaded_media as $upload_media) {

        //Adding new media
        //Search eTag to see if we already have it:
        $etag_detected = false;
        if (isset($upload_media['media_cache']['etag']) && strlen($upload_media['media_cache']['etag'])) {
            //We already have this asset, return user:
            foreach ($CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                'chainuserinput' => 42662, //etag
                'chainvalue' => $upload_media['media_cache']['etag'],
            ), array('chainuseroutput'), 1) as $existing_media) {
                $upload_media['userid'] = $existing_media['userid'];
                $etag_detected = true;
            }
        }

        //Create User for this new media:
        $added_e = $CI->Users->create(array(
            'username' => $upload_media['username'],
            'usercover' => ($upload_media['media_typeid'] == 4259 /* Audio has no thumbnail! */ ? 'far fa-volume-up' : $upload_media['usercover']),
        ), $user_session['userid']);
        if (!$added_e['status']) {
            log_error('Failed to create a new User for [' . $upload_media['username'] . '] with cover [' . $upload_media['usercover'] . ']', array(
                'chainuseroutput' => $upload_media['userid'],
            ));
            continue;
        }

        //Create new media and assign ID:
        $upload_media['userid'] = $added_e['user_create']['userid'];

        //new asset, create new User and insert tags
        $users___32088 = $CI->config->item('users___32088'); //Platform Variables
        foreach ($CI->config->item('users___42679') as $chainusertype => $m) {

            //Ensure variable name exists so we can check the API call:
            $target_variable = false;
            if (isset($users___32088[$chainusertype]['m__message'])) {
                //Determine if variable exists
                if (in_array($chainusertype, $CI->config->item('userids___42763')) && isset($upload_media['media_cache']['video'][$users___32088[$chainusertype]['m__message']])) {
                    //Video info:
                    $target_variable = $upload_media['media_cache']['video'][$users___32088[$chainusertype]['m__message']];
                } elseif (in_array($chainusertype, $CI->config->item('userids___42675')) && isset($upload_media['media_cache']['audio'][$users___32088[$chainusertype]['m__message']])) {
                    //Audio info:
                    $target_variable = $upload_media['media_cache']['audio'][$users___32088[$chainusertype]['m__message']];
                } elseif (isset($upload_media['media_cache'][$users___32088[$chainusertype]['m__message']])) {
                    //Media info:
                    $target_variable = $upload_media['media_cache'][$users___32088[$chainusertype]['m__message']];
                }
            }
            if (!strlen($target_variable) || $target_variable == '0') {
                //This variable does not have a value, move on
                continue;
            }

            //We have a variable, see what it is
            if (in_array($chainusertype, $CI->config->item('userids___33331'))) {

                //Single select that needs auto creation of Users if missing:
                $child_id = 0;
                foreach ($CI->Chains->read(array(
                    'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                    'chainuserinput' => $chainusertype,
                    'username' => $target_variable,
                ), array('chainuseroutput'), 1, 0, array('chainid' => 'ASC')) as $child_user) {
                    $child_id = $child_user['userid'];
                }

                //If not found create the child:
                if (!$child_id) {
                    $added_child = $CI->Users->create(array(
                        'username' => $target_variable,
                    ));
                    if (!$added_child['status']) {
                        log_error('Failed to create a new User for [' . $target_variable . ']', array(
                            'chainuseroutput' => $chainusertype,
                        ));
                        continue;
                    }

                    //Add chains for this new User:
                    $CI->Chains->create(array(
                        'chainusercreator' => $user_session['userid'],
                        'chainuserinput' => $chainusertype,
                        'chainuseroutput' => $added_child['user_create']['userid'],
                        'chainusertype' => 4230,
                    ));

                    //Assign child User:
                    $child_id = $added_child['user_create']['userid'];

                }

                if ($child_id) {
                    //Child User found, simply chain:
                    $CI->Chains->create(array(
                        'chainusercreator' => $user_session['userid'],
                        'chainuserinput' => $child_id,
                        'chainuseroutput' => $upload_media['userid'],
                        'chainusertype' => 4230,
                    ));
                }

            } else {

                //Save variable as is:
                $CI->Chains->create(array(
                    'chainusercreator' => $user_session['userid'],
                    'chainuserinput' => $chainusertype,
                    'chainuseroutput' => $upload_media['userid'],
                    'chainvalue' => $target_variable,
                    'chainusertype' => 4230,
                ));

            }
        }

        //By now have the media User, create necessary chains:
        if ($upload_media['userid'] && $upload_media['media_typeid']) {

            //Chain to User as Uploader:
            if (!count($CI->Chains->read(array(
                'chainuserinput' => $user_session['userid'],
                'chainuseroutput' => $upload_media['userid'],
                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
            )))) {
                $CI->Chains->create(array(
                    'chainusercreator' => $user_session['userid'],
                    'chainuserinput' => $user_session['userid'],
                    'chainuseroutput' => $upload_media['userid'],
                    'chainusertype' => 4230,
                    'chainvalue' => $upload_media['playback_code'],
                ));
            }

            //Chain to Media Type:
            if (!count($CI->Chains->read(array(
                'chainuserinput' => $upload_media['media_typeid'],
                'chainuseroutput' => $upload_media['userid'],
                'chainusertype' => 4230,
            )))) {
                $CI->Chains->create(array(
                    'chainusercreator' => $user_session['userid'],
                    'chainuserinput' => $upload_media['media_typeid'],
                    'chainuseroutput' => $upload_media['userid'],
                    'chainusertype' => 4230,
                    'chainvalue' => $upload_media,
                ));
            }

        }
    }

    return true;

}


function view_post_media($i)
{

    $CI =& get_instance();
    $message_append = '';

    //TODO REMOVE

    //Query Relevant Users:
    foreach ($CI->Chains->read(array(
        'chainusertype IN (4258,4259,4260)' => null, //Media TODO
        'chainpostoutput' => $i['postid'],
    ), array('chainuserinput'), 0, 0, array('chainkey' => 'ASC')) as $x) {

        if ($x['chainusertype'] == 4258) {

            //Video
            $template = '<video id="video_user_' . $x['chainvalue'] . '" controls class="cld-video-user cld-fluid cld-video-user-skin-light" poster="' . $x['usercover'] . '"></video><script> play_video(\'' . $x['chainvalue'] . '\'); </script>';

        } elseif ($x['chainusertype'] == 4259) {

            //Audio
            $template = '<audio controls src="' . $x['chainvalue'] . '"></audio>';

        } elseif ($x['chainusertype'] == 4260) {

            //Image
            $template = '<img src="' . $x['chainvalue'] . '" />';

        } else {
            continue; //Should not happen!
        }

        //Format data if needed:
        $message_append .= '<div class="media_display media_display_' . $x['chainusertype'] . ($x['chainusertype'] == 4258 ? ' ignore-click ' : '') . '" id="loaded_media_' . $x['chainid'] . '" class="media_item" media_typeid="' . $x['chainusertype'] . '" userid="' . $x['userid'] . '"  usercover="' . $x['usercover'] . '" playback_code="' . $x['chainvalue'] . '" username="' . $x['username'] . '">' . $template . '</div>';

    }

    return $message_append;

}


function append_user($chainuserinput, $chainusercreator, $chainvalue, $postid, $update_if_existing = true)
{

    $CI =& get_instance();

    //First validate data type to ensure it matches:
    foreach ($CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
        'chainuserinput IN (' . join(',', $CI->config->item('userids___4592')) . ')' => null, //Data Types
        'chainuseroutput' => $chainuserinput,
    )) as $data_type) {
        $data_type_validate = data_type_validate($data_type['chainuserinput'], $chainvalue);
        if (!$data_type_validate['status']) {
            //It's not the data type needed:
            return false;
        }
    }

    //Now check existing chains:
    $existing_x = $CI->Chains->read(array(
        'chainvoid >=' => 0, //Any Chain
        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
        'chainuserinput' => $chainuserinput,
        'chainuseroutput' => $chainusercreator,
    ));

    if (count($existing_x)) {

        if ($existing_x[0]['chainvoid'] > 0) {
            return false;
        } elseif (strtolower($existing_x[0]['chainvalue']) == strtolower($chainvalue)) {
            //Everything is the same, nothing to do here:
            return false;
        }

        //Content value has changed, update the Chain:
        if ($update_if_existing) {
            $CI->Chains->update($existing_x[0]['chainid'], array(
                'chainvalue' => $chainvalue,
                'chainusercreator' => $chainusercreator,
            ));
        }

    } else {

        //Create Chain:
        $CI->Chains->create(array(
            'chainusertype' => 4230, //Follow User
            'chainvalue' => $chainvalue,
            'chainusercreator' => $chainusercreator,
            'chainuserinput' => $chainuserinput,
            'chainuseroutput' => $chainusercreator,
        ));

    }

    return true;

}


function data_type_validate($data_type, $data_value, $data_title = null)
{

    $CI =& get_instance();
    $users___4592 = $CI->config->item('users___4592'); //Data types
    $data_value = trim($data_value);

    if(!in_array($data_type, $CI->config->item('userids___4592'))){
        //Unknown data type:
        return array(
            'status' => 0,
            'message' => $data_type.' is an unknown data type not listed under @4592',
        );
    }

    if(!$data_title){
        $data_title = $users___4592[$data_type]['m__name'];
    }

    //Validate data type:
    if ($data_type == 43940 && strlen($data_value)) {

        //Number:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be null/empty but its ['.$data_value.']',
        );

    } elseif ($data_type == 4256 && !filter_var($data_value, FILTER_VALIDATE_URL)) {

        //URL:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $users___4592[$data_type]['m__name'],
        );

    } elseif ($data_type == 4319 && !is_numeric($data_value)) {
        //Number:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $users___4592[$data_type]['m__name'],
        );
    } elseif ($data_type == 42181 && (strlen(preg_replace('/[^0-9]/', '', $data_value)) < 10 || strlen(preg_replace('/[^0-9]/', '', $data_value)) > 14)) {
        //Phone Number:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $users___4592[$data_type]['m__name'] . ' with 10-14 numbers including country code.',
        );
    } elseif (($data_type == 4318 || $data_type == 43939) && !strtotime($data_value)) {
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $users___4592[$data_type]['m__name'],
        );
    } elseif ($data_type == 4255 && !strlen($data_value)) {
        //Text:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $users___4592[$data_type]['m__name'],
        );
    } elseif ($data_type == 32097 && !filter_var($data_value, FILTER_VALIDATE_EMAIL)) {
        //Email:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a valid ' . $users___4592[$data_type]['m__name'],
        );
    } elseif ($data_type == 42947 && (!is_numeric($data_value) || $data_value < 0 || $data_value > 1)) {
        //Percentage:
        return array(
            'status' => 0,
            'message' => $data_title . ' must be set to a number between 0.00 & 1.00.',
        );
    }

    //All good:
    return array(
        'status' => 1,
        'message' => 'Good',
    );

}


function data_type_format($data_type, $data_value)
{

    $CI =& get_instance();

    if (in_array($data_type, $CI->config->item('userids___4318')) && strtotime($data_value) > 0) {
        //Format Time:
        return date(view_memory(6404, 4318), strtotime($data_value));
    }

    //No special formatting needed:
    return $data_value;

}

function change_user($old_user)
{
    $max_length = view_memory(6404, 41985);
    if (strlen($old_user) < $max_length) {
        //We have some room to change:
        return substr($old_user . rand(100000, 999999), 0, $max_length);
    } else {
        //No room to change, remove some words from the end:
        return substr($old_user, 0, ($max_length - 6)) . rand(100000, 999999);
    }
}

function sort_by($userid, $custom_sort = array())
{

    $CI =& get_instance();
    $order_by = array();
    foreach ($CI->config->item('users___' . $userid) as $sort_id => $sort) {
        $order_by['chainuserinput = \'' . $sort_id . '\' DESC'] = null;
    }

    if (is_array($custom_sort)) {
        return array_merge($order_by, $custom_sort);
    } else {
        return $order_by;
    }
}


function validate_update_user($str, $postid = null, $userid = null)
{

    $CI =& get_instance();
    $user_session = user_session();

    //Validate:
    if (($postid && $userid) || (!$postid && !$userid)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Must set either Post or User ID! Pick one',
        );

    } elseif (!strlen($str)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Missing User',
        );

    } elseif (!ctype_alnum($str)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Post Can only contain alphanumneric numbers and letters',
        );

    } elseif (!preg_match('/[a-zA-Z]/', $str)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Post Must contain at-least one letter between A-Z',
        );

    } elseif (strlen($str) > view_memory(6404, 41985)) {

        return array(
            'status' => 0,
            'db_duplicate' => 0,
            'message' => 'Post Must be ' . view_memory(6404, 41985) . ' characters or less',
        );

    } elseif ($postid && array_key_exists(strtolower($str), $CI->config->item('handlusers___6287'))) {

        return array(
            'status' => 0,
            'db_duplicate' => 1,
            'message' => 'Post "' . $str . '" already in use.',
        );

    }

    //Syntax good! Now let's check the DB for duplicates
    if ($postid > 0) {

        foreach ($CI->Posts->read(array(
            'postid !=' => $postid,
            'LOWER(posthashtag)' => strtolower($str),
        ), 0) as $matched) {
            return array(
                'status' => 0,
                'db_duplicate' => 1,
                'message' => 'Post "' . $str . '" already in use.',
            );
        }

        //Since not found we can replace this:
        $CI->Posts->update($postid, array(
            'posthashtag' => change_user($str),
        ), $user_session['userid']);

    } elseif ($userid > 0) {

        foreach ($CI->Users->read(array(
            'userid !=' => $userid,
            'LOWER(userhandle)' => strtolower($str),
        ), 0) as $matched) {
            //Is it active?
            return array(
                'status' => 0,
                'db_duplicate' => 1,
                'message' => 'Post "' . $str . '" already in use.',
            );
        }

        //Since not active we can replace this:
        $CI->Users->update($userid, array(
            'userhandle' => change_user($str),
        ), $user_session['userid']);

    }


    //All good, return success:
    return array(
        'status' => 1,
        'db_duplicate' => 0,
        'message' => 'Success',
    );

}


function validate_username($str)
{

    //Validate:
    $title_clean = trim($str);
    while (substr_count($title_clean, '  ') > 0) {
        $title_clean = str_replace('  ', ' ', $title_clean);
    }

    if (!strlen(trim($str))) {

        return array(
            'status' => 0,
            'message' => 'User title missing',
        );

    } elseif (strlen(trim($str)) < 1) {

        return array(
            'status' => 0,
            'message' => 'Enter User title to continue.',
        );

    } elseif (strlen($str) > view_memory(6404, 6197)) {

        return array(
            'status' => 0,
            'message' => 'User title must be ' . view_memory(6404, 6197) . ' characters or less',
        );

    }

    //All good, return success:
    return array(
        'status' => 1,
        'username_clean' => trim($title_clean),
    );

}

function number_chainkey($str)
{
    //Set chainkey for caching purposes if message value is numerical:
    if ($str != 0 && is_numeric($str)) {
        return intval($str);
    } elseif ($str != 0 && is_double($str)) {
        return doubleval($str);
    } elseif (strtotime($str) > 0) {
        return strtotime($str);
    } else {
        return 0;
    }
}

function delete_all_between($beginning, $end, $string)
{
    $beginningPos = strpos($string, $beginning);
    $endPos = strpos($string, $end);
    if ($beginningPos === false || $endPos === false) {
        return $string;
    }

    $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

    return delete_all_between($beginning, $end, str_replace($textToDelete, '', $string)); // recursion to ensure all occurrences are replaced
}

function user_website($chainusercreator)
{
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(
        'chainuseroutput' => $chainusercreator,
        'chainusertype' => 4230, //New User Created
    ), array(), 1) as $user_created) {
        return $user_created['chainuserdomain'];
    }
    foreach ($CI->Chains->read(array(
        'chainusercreator' => $chainusercreator,
    ), array(), 1) as $user_created) {
        return $user_created['chainuserdomain'];
    }
    return 0;
}


function random_adjective()
{

    $adjectives = array('Amazing', 'Awesome', 'Adventurous', 'Ambitious', 'Adorable', 'Artistic', 'Agile', 'Acrobatic', 'Attractive', 'Alluring', 'Astonishing', 'Authentic', 'Awkward', 'Ancient', 'American', 'Australian', 'Austrian', 'African', 'Asian', 'Brave', 'Beautiful', 'Bright', 'Busy', 'Big', 'Bold', 'Basic', 'Blissful', 'Bouncy', 'Beneficial', 'Bashful', 'Black', 'Brown', 'Burgundy', 'Broad', 'British', 'Belgian', 'Brazilian', 'Creative', 'Confident', 'Cheerful', 'Calm', 'Cute', 'Clever', 'Curious', 'Charming', 'Courageous', 'Clean', 'Cool', 'Considerate', 'Caring', 'Crazy', 'Classic', 'Chic', 'Cloudy', 'Colombian', 'Chinese', 'Delightful', 'Dreamy', 'Daring', 'Dynamic', 'Dark', 'Decent', 'Drastic', 'Defiant', 'Dedicated', 'Deep', 'Desirable', 'Dirty', 'Dramatic', 'Dizzy', 'Demanding', 'Diligent', 'Dutch', 'Danish', 'Delicious', 'Dazzling', 'Easy', 'Elegant', 'Enthusiastic', 'Eager', 'Efficient', 'Empathetic', 'Excellent', 'Exciting', 'Effective', 'Extravagant', 'Entertaining', 'Exotic', 'Expressive', 'Expensive', 'Elaborate', 'European', 'Egyptian', 'Eastern', 'Elderly', 'Educational', 'Fantastic', 'Fabulous', 'Friendly', 'Funny', 'Fearless', 'Fresh', 'Fascinating', 'Fluffy', 'Fierce', 'Fine', 'Free', 'Frugal', 'French', 'Futuristic', 'Fast', 'Flat', 'Famous', 'Flawless', 'Formal', 'Frizzy', 'Gorgeous', 'Great', 'Gentle', 'Generous', 'Gracious', 'Genuine', 'Glorious', 'Graceful', 'Golden', 'Grand', 'Green', 'Growing', 'Groovy', 'Greek', 'Grumpy', 'Gothic', 'Gargantuan', 'Gigantic', 'German', 'Georgian', 'Happy', 'Hot', 'Humble', 'Honest', 'Healthy', 'Heavy', 'Handsome', 'High', 'Helpful', 'Hilarious', 'Heavenly', 'Harmonious', 'Hardworking', 'Historical', 'Heartfelt', 'Homey', 'Hungry', 'Huge', 'Hispanic', 'Hindu', 'Interesting', 'Intelligent', 'Incredible', 'Inspiring', 'Impressive', 'Imaginative', 'Inquisitive', 'Iconic', 'Indigo', 'Industrious', 'Inevitable', 'Inexpensive', 'Incomparable', 'Postlistic', 'Illustrious', 'Indian', 'Italian', 'Irresistible', 'Irrelevant', 'Icy', 'Joyful', 'Jolly', 'Jovial', 'Jaunty', 'Jaded', 'Jazzy', 'Jumpy', 'Juicy', 'Judgmental', 'Jumbled', 'Japanese', 'Javanese', 'Jewish', 'Jittery', 'Junior', 'Justified', 'Jubilant', 'Jade', 'Jumbo', 'Joint', 'Kind', 'Knowledgeable', 'Keen', 'Kooky', 'Knotty', 'Kinetic', 'Known', 'Keen-eyed', 'Knightly', 'Keen-witted', 'Kempt', 'Knockout', 'Knackered', 'Kindhearted', 'Kenyan', 'Kiddy', 'Knotted', 'Kyrgyzstani', 'Kindred', 'Kentuckian', 'Loud', 'Lively', 'Lazy', 'Loyal', 'Long', 'Lonely', 'Lovely', 'Large', 'Light', 'Low', 'Luxurious', 'Lasting', 'Literal', 'Learned', 'Lucky', 'Magnificent', 'Mysterious', 'Modern', 'Moody', 'Musical', 'Mighty', 'Masculine', 'Mesmerizing', 'Mindful', 'Memorable', 'Multicultural', 'Moral', 'Majestic', 'Mischievous', 'Mouthwatering', 'Mellow', 'Modest', 'Magical', 'Melodic', 'Mature', 'Nervous', 'Natural', 'New', 'Nice', 'Noble', 'Naughty', 'Neat', 'Nonchalant', 'Noisy', 'Narrow', 'Nostalgic', 'Needy', 'Negative', 'Nutritious', 'Nonstop', 'Noteworthy', 'Numerous', 'Notable', 'Nurturing', 'Nifty', 'Obvious', 'Original', 'Optimistic', 'Ordinary', 'Official', 'Outstanding', 'Open', 'Organic', 'Odd', 'Observant', 'Obedient', 'Opaque', 'Obsolete', 'Offensive', 'Oily', 'Old-fashioned', 'Ornate', 'Onyx', 'Overwhelming', 'Oceanic', 'Perfect', 'Patient', 'Positive', 'Powerful', 'Popular', 'Polite', 'Peaceful', 'Playful', 'Pleasant', 'Precious', 'Practical', 'Private', 'Proud', 'Profound', 'Pretty', 'Painful', 'Priceless', 'Puzzled', 'Persistent', 'Passionate', 'Quaint', 'Quick', 'Quiet', 'Quirky', 'Quizzical', 'Queenly', 'Quivering', 'Quotable', 'Qualified', 'Quantifiable', 'Questionable', 'Quarrelsome', 'Queasy', 'Quenched', 'Quack', 'Quilted', 'Quizzing', 'Reliable', 'Responsible', 'Romantic', 'Rich', 'Rude', 'Real', 'Radiant', 'Royal', 'Rough', 'Respectful', 'Red', 'Rational', 'Rustic', 'Radiant', 'Robust', 'Rare', 'Resilient', 'Reckless', 'Ready', 'Rambunctious', 'Strong', 'Smart', 'Serious', 'Sad', 'Special', 'Simple', 'Super', 'Sincere', 'Safe', 'Stunning', 'Sweet', 'Shy', 'Successful', 'Satisfied', 'Shiny', 'Silent', 'Sparkling', 'Strong-willed', 'Scary', 'Surprised', 'Tall', 'Talkative', 'Tasty', 'Tender', 'Terrific', 'Terrible', 'Thoughtful', 'Thrifty', 'Timely', 'Tough', 'Traditional', 'Trustworthy', 'Tremendous', 'Tricky', 'Tolerant', 'Tenacious', 'Tiny', 'Tired', 'Top', 'Trembling', 'Ugly', 'Ultimate', 'Unbelievable', 'Uncertain', 'Uncommon', 'Unconditional', 'Unconscious', 'Understanding', 'Unforgettable', 'Unhappy', 'Unique', 'United', 'Universal', 'Unusual', 'Upbeat', 'Uplifting', 'Urbane', 'Urgent', 'Useful', 'Useless', 'Valuable', 'Vague', 'Valid', 'Vast', 'Various', 'Vengeful', 'Vibrant', 'Victorious', 'Vigorous', 'Villainous', 'Vital', 'Vivacious', 'Vocal', 'Volatile', 'Volcanic', 'Voracious', 'Vulnerable', 'Vicious', 'Velvet', 'Verbal', 'Warm', 'Wild', 'Witty', 'Wise', 'Wonderful', 'Worried', 'Wondrous', 'Wealthy', 'Whimsical', 'Wicked', 'Wide', 'Wavy', 'Watery', 'Weighty', 'Wooden', 'Weak', 'Wary', 'Winning', 'Well-groomed', 'Wholesome', 'Xeric', 'Xerophytic', 'Xerotic', 'Xyloid', 'Xylonic', 'Xylophagous', 'Xanthic', 'Xanthous', 'Xerarch', 'Xylotomous', 'Xerographic', 'Xenial', 'Xenogenetic', 'Xenolithic', 'Xylophilous', 'Yellow', 'Young', 'Yielding', 'Yearly', 'Yummy', 'Yawning', 'Yucky', 'Yearning', 'Yeasty', 'Yielding', 'Youthful', 'Yare', 'Yclept', 'Yellowish', 'Yearlong', 'Youth', 'Zealous', 'Zesty', 'Zigzag', 'Zillionth', 'Zinciferous', 'Zingy', 'Zippered', 'Zippy', 'Zoological', 'Zonal', 'Ambitious', 'Amiable', 'Analytical', 'Assertive', 'Authentic', 'Bold', 'Calm', 'Charismatic', 'Charming', 'Cheerful', 'Compassionate', 'Confident', 'Conscientious', 'Considerate', 'Creative', 'Curious', 'Dependable', 'Diligent', 'Disciplined', 'Easygoing', 'Empathetic', 'Enthusiastic', 'Extraverted', 'Flexible', 'Friendly', 'Generous', 'Genuine', 'Gracious', 'Hardworking', 'Honest', 'Humble', 'Independent', 'Innovative', 'Insightful', 'Intelligent', 'Kind', 'Logical', 'Loyal', 'Open-minded', 'Optimistic', 'Outgoing', 'Passionate', 'Patient', 'Persistent', 'Practical', 'Rational', 'Reliable', 'Reuserful', 'Responsible', 'Self-confident', 'Happy', 'Sad', 'Angry', 'Fearful', 'Anxious', 'Excited', 'Frustrated', 'Nostalgic', 'Hopeful', 'Envious', 'Jealous', 'Empathetic', 'Curious', 'Surprised', 'Disappointed', 'Grateful', 'Confused', 'Content', 'Lonely', 'Loved', 'Joyful', 'Melancholic', 'Irritated', 'Apprehensive', 'Restless', 'Ecstatic', 'Distraught', 'Panicked', 'Annoyed', 'Numb', 'Scared', 'Enraged', 'Heartbroken', 'Amused', 'Overwhelmed', 'Grateful', 'Conflicted', 'Peaceful', 'Devastated', 'Empowered');

    return $adjectives[array_rand($adjectives)];
}


function dispatch_sms($to_phone, $single_message, $userid = 0, $x_data = array(), $template_postid = 0, $chainuserdomain = 0, $log_tr = true, $demo_only = false)
{

    $CI =& get_instance();
    $twilio_account_sid = website_setting(30859);
    $twilio_auth_token = website_setting(30860);
    $twilio_from_number = website_setting(27673);
    if (!$twilio_from_number || !$twilio_auth_token || !$twilio_account_sid) {

        //No way to send an SMS:
        if ($log_tr) {
            log_error('dispatch_sms() missing either: ' . $twilio_account_sid . ' / ' . $twilio_auth_token . ' / ' . $twilio_from_number, array(
                'chainuseroutput' => $userid,
                'chainusercreator' => $userid,
                'chainuserdomain' => $chainuserdomain,
            ));
        }

        return false;
    }

    $post = array(
        'From' => $twilio_from_number,
        'Body' => $single_message,
        'To' => $to_phone,
    );

    if ($demo_only) {
        echo print_r($post);
        return false;
    }

    $x = curl_init("https://api.twilio.com/2010-04-01/Accounts/" . $twilio_account_sid . "/Messages.json");
    curl_setopt($x, CURLOPT_POST, true);
    curl_setopt($x, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($x, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($x, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($x, CURLOPT_USERPWD, $twilio_account_sid . ":" . $twilio_auth_token);
    curl_setopt($x, CURLOPT_POSTFIELDS, http_build_query($post));
    $y = curl_exec($x);

    curl_close($x);

    if (substr_count($y, '"code": 21211')) {
        //Invalid input, must be returned:
        return false;
    }
    $sms_success = !substr_count($y, '"status": 400');

    //Log Chain:
    if ($log_tr && $sms_success) {

        $user_session = user_session();
        $userid = ($userid > 0 ? $userid : ($user_session ? $user_session['userid'] : 14068));
        if ($template_postid && count($CI->Posts->read(array(
                'postid' => $template_postid,
            )))) {
            foreach ($CI->Posts->read(array(
                'postid' => $template_postid,
            )) as $post_template) {
                $CI->Chains->post_discovered(27676, $userid, 0, $post_template, array(), array(
                    'chainvalue' => $single_message,
                ));
            }
        } elseif ($userid > 0) {

            $CI->Chains->create(array_merge($x_data, array(
                'chainusertype' => 44176, //User View
                'chainuserinput' => 27676,
                'chainuseroutput' => $userid,
                'chainusercreator' => $userid,
                'chainvalue' => $single_message,
                'chainpostoutput' => $template_postid,
            )));
        }


    }

    return true;

}

function dispatch_email($to_emails, $subject, $email_body, $userid = 0, $x_data = array(), $template_postid = 0, $chainuserdomain = 0, $log_tr = true, $demo_only = false)
{

    $CI =& get_instance();
    $domain_name = get_domain('m__name', $userid, $chainuserdomain);
    $domain_email = website_setting(28614, $userid, $chainuserdomain);

    if (!strlen($domain_email)) {
        $domain_name = 'MENCH';
        $domain_name = 'support@mench.com';
        log_error('Domain email is missing! (' . $domain_name . ') (' . $domain_email . ') (' . join(' & ', $to_emails) . ')', array(
            'chainuseroutput' => $userid,
        ));
    }

    $email_domain = '"' . $domain_name . '" <' . $domain_email . '>';
    $name = 'New User';
    $ReplyToAddresses = array($email_domain);

    if ($userid > 0) {

        $es = $CI->Users->read(array(
            'userid' => $userid,
        ));
        if (count($es)) {

            $name = $es[0]['username'];

            //Also fetch email for this user to populate the reply to:
            $fetch_emails = $CI->Chains->read(array(
                'chainuserinput' => 3288, //Email
                'chainuseroutput' => $userid,
                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
            ));
            if (count($fetch_emails) && filter_var($fetch_emails[0]['chainvalue'], FILTER_VALIDATE_EMAIL)) {
                array_push($ReplyToAddresses, trim($fetch_emails[0]['chainvalue']));
            }
        }
    }

    //Email has no word limit to add header & footer:
    $users___6287 = $CI->config->item('users___6287'); //APP
    $base_domain = 'https://' . get_domain('m__message', $userid, $chainuserdomain);

    $email_message = '<div class="line">' . randomize_text(29749) . ' ' . $name . ' ' . randomize_text(29750) . '</div>';
    $email_message .= $email_body . "\n";
    $email_message .= '<div class="line">' . randomize_text(12691) . '</div>';
    $email_message .= '<div class="line">' . get_domain('m__name', $userid, $chainuserdomain) . '</div>';


    if ($userid > 0 && count($es) && (!$template_postid || !count($CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Writes
                'chainuserinput' => 31779, //Mandatory Emails
                'chainpostinput' => $template_postid,
            ))))) {
        //User specific notifications:
        $email_message .= '<div class="line"><a href="' . $base_domain . view_app_chain(28904) . '?userlogin=' . $es[0]['userhandle'] . '&time=' . time() . '&hash=' . view_hash(time() . $es[0]['userhandle']) . '" style="font-size:13px;">' . $users___6287[28904]['m__name'] . '</a></div>';
    }


    $general_style = 'width:100%; max-width:610px; font-size:16px; margin-bottom:8px; line-height:134%;';

    //Email HTML Transformations:
    $email_message = str_replace('>Show more<', '><', $email_message); //Hide the show more content if any
    $email_message = str_replace('<img ', '<img style="' . $general_style . '" ', $email_message);
    $email_message = str_replace('<div class="line', '<div style="' . $general_style . '" class="line', $email_message);
    $email_message = str_replace("\n", '<div style="padding:3px 0 0; line-height:100%;">&nbsp;</div>', $email_message);
    $email_message = str_replace('href="/', 'style="display:inline-block;" href="' . $base_domain . '/', $email_message);

    $email_data = array(
        // User is required
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
                    'Data' => strip_tags(str_replace("\n", "\n\n", $email_message)),
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

    if ($demo_only) {
        echo print_r($email_data);
        return false;
    }

    //Loadup amazon SES:
    require_once('application/libraries/aws/aws-autoloader.php');

    $client = new Aws\Ses\SesClient([
        'version' => 'latest',
        'region' => 'us-west-2',
        'credentials' => $CI->config->item('cred_aws'),
    ]);

    $response = $client->sendEmail($email_data);

    //Log Chain:
    if ($log_tr) {

        $user_session = user_session();
        $userid = ($userid > 0 ? $userid : ($user_session ? $user_session['userid'] : 14068));
        if ($template_postid && count($CI->Posts->read(array(
                'postid' => $template_postid,
            )))) {
            foreach ($CI->Posts->read(array(
                'postid' => $template_postid,
            )) as $post_template) {
                $CI->Chains->post_discovered(29399, $userid, 0, $post_template, array(), array(
                    'chainvalue' => $subject . "\n" . $email_message,
                ));
            }
        } elseif ($userid > 0) {

            $CI->Chains->create(array_merge($x_data, array(
                'chainusertype' => 44176, //User View
                'chainuserinput' => 29399,
                'chainuseroutput' => $userid,
                'chainusercreator' => $userid,
                'chainvalue' => $subject . "\n" . $email_message,
                'chainpostoutput' => $template_postid,
            )));
        }

        //Can we also mark the discovery as complete?
        if ($userid && isset($x_data['chainpostinput']) && $x_data['chainpostinput'] > 0 && isset($x_data['chainpostoutput'])) {
            foreach ($CI->Posts->read(array(
                'postid' => $x_data['chainpostinput'],
            )) as $email_i) {
                $CI->Chains->post_discovered(4559, $userid, $x_data['chainpostoutput'], $email_i, $x_data);
            }
        }

    }


    return $response;

}


function website_setting($setting_id = 0, $initiator_userid = 0, $chainuserdomain = 0, $force_website = true)
{

    $CI =& get_instance();
    $user_id = 0; //Assume no domain unless found below

    if (!$initiator_userid) {
        $user_session = user_session();
        if ($user_session && isset($user_session['userid']) && $user_session['userid'] > 0) {
            $initiator_userid = $user_session['userid'];
        }
    }

    if ($chainuserdomain && $force_website) {

        $user_id = $chainuserdomain;

    } else {

        $server_name = get_server('SERVER_NAME');
        if (strlen($server_name)) {
            foreach ($CI->config->item('users___14870') as $chainusertype => $m) {
                if (substr_count($m['m__message'], $server_name) == 1) {
                    $user_id = $chainusertype;
                    break;
                }
            }
        }

        $user_id = ($user_id ? $user_id : ($chainuserdomain > 0 ? $chainuserdomain : 2738 /* Mench */));

    }


    if (!$setting_id) {
        return $user_id;
    }


    $users___domain_sett = $CI->config->item('users___' . $setting_id); //DOMAINS

    if (!isset($users___domain_sett[$user_id]) || !strlen($users___domain_sett[$user_id]['m__message'])) {
        $target_return = (in_array($setting_id, $CI->config->item('userids___6404')) ? view_memory(6404, $setting_id) : false);
    } else {
        $target_return = $users___domain_sett[$user_id]['m__message'];
    }

    return $target_return;

}


function get_domain($var_field, $initiator_userid = 0, $chainuserdomain = 0, $force_website = true)
{
    $CI =& get_instance();
    $domain_e = website_setting(0, $initiator_userid, $chainuserdomain, $force_website);
    $users___14870 = $CI->config->item('users___14870'); //DOMAINS
    return $users___14870[$domain_e][$var_field];
}


function user_access($userhandle = null, $userid = 0, $e = false, $replacement_userid = false, $user_list_config = array())
{

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
    $user_session = user_session();
    if (!$replacement_userid && user_session(10939)) {
        return 3;
    } elseif (!$replacement_userid && $user_session && ($userhandle == $user_session['userhandle'] || $userid == $user_session['userid'])) {
        return 3;
    }

    if (strlen($userhandle)) {
        $filters['LOWER(userhandle)'] = strtolower($userhandle);
    } elseif (intval($userid)) {
        $filters['userid'] = $userid;
    } elseif (!$e || (!$user_session && !$replacement_userid)) {
        return 0;
    }

    if (!$e) {
        //Check privacy first:
        foreach ($CI->Users->read($filters) as $match_e) {
            $e = $match_e;
            break;
        }
    }


    //IF Follows Any
    $chainusercreator = ($replacement_userid > 0 ? $replacement_userid : ($user_session ? $user_session['userid'] : 0));
    if (!count($user_list_config)) {
        $user_list_config = user_list_config($e['userid']);
    }
    if (is_array($user_list_config[1645062]) && count($user_list_config[1645062])) {
        $the_counter = 0;
        if ($chainusercreator) {
            foreach ($user_list_config[1645062] as $focususerid) {
                if ((($chainusercreator == $focususerid) || count($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuserinput' => $focususerid,
                        'chainuseroutput' => $chainusercreator,
                    ))))) {
                    $the_counter++;
                    break;
                }
            }
        }
        if (!$chainusercreator || !$the_counter) {
            return 0;
        }
    }


    //IF Follows All
    if (is_array($user_list_config[1645146]) && count($user_list_config[1645146])) {
        $the_counter = 0;
        if ($chainusercreator) {
            foreach ($user_list_config[1645146] as $focususerid) {
                if ((($chainusercreator == $focususerid) || count($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuserinput' => $focususerid,
                        'chainuseroutput' => $chainusercreator,
                    ))))) {
                    $the_counter++;
                }
            }
        }
        if (!$chainusercreator || $the_counter < count($user_list_config[1645146])) {
            return 0;
        }
    }


    //IF Not Follows Any
    if (is_array($user_list_config[1645161]) && count($user_list_config[1645161])) {
        $the_counter = 0;
        if ($chainusercreator) {
            foreach ($user_list_config[1645161] as $focususerid) {
                if (($chainusercreator == $focususerid) || count($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuserinput' => $focususerid,
                        'chainuseroutput' => $chainusercreator,
                    )))) {
                    //Found an exclusion, so skip this:
                    $the_counter++;
                    break;
                }
            }
        }
        if (!$chainusercreator || $the_counter > 0) {
            return 0;
        }
    }

    //IF Not Follows All
    if (is_array($user_list_config[1645176]) && count($user_list_config[1645176])) {
        $the_counter = 0;
        if ($chainusercreator) {
            foreach ($user_list_config[1645176] as $focususerid) {
                if (($chainusercreator == $focususerid) || count($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuserinput' => $focususerid,
                        'chainuseroutput' => $chainusercreator,
                    )))) {
                    //Found an exclusion, so skip this:
                    $the_counter++;
                }
            }
        }
        if (!$chainusercreator || $the_counter == count($user_list_config[1645176])) {
            return 0;
        }
    }


    $is_public = true;
    $is_author = false;
    if ($user_session) {
        $is_author = count($CI->Chains->read(array(
            'chainusertype' => 12274,
            'chainusercreator' => $chainusercreator,
            'chainuserinput' => $e['userid'],
        )));
    }

    return ($is_author ? 3 : ($is_public ? 2 : 1));

}

function user_up($userid, $return_ids = array())
{

    if (!count($return_ids)) {
        $return_ids = array(intval($userid));
    }
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(
        'chainuserinput > 0' => null,
        'chainuseroutput' => $userid,
        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
    ), array(), 0) as $up_user) {
        if (in_array(intval($up_user['chainuserinput']), $return_ids)) {
            continue;
        }
        array_push($return_ids, intval($up_user['chainuserinput']));
        $return_ids_up = user_up($up_user['chainuserinput'], $return_ids);
        foreach ($return_ids_up as $return_id_up) {
            if (!in_array($return_id_up, $return_ids)) {
                array_push($return_ids, $return_id_up);
            }
        }
    }

    return $return_ids;
}

function post_access($posthashtag = null, $postid = 0, $i = false, $replacement_userid = false, $post_list_config = array(), $is_cahce = false)
{

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

    $CI =& get_instance();
    $user_session = user_session();
    $discovery_mode = ($replacement_userid > 0 ? true : ((isset($_POST['js_request_uri']) && substr_count($_POST['js_request_uri'], '/') == 2) || (!isset($_POST['js_request_uri']) && strlen($CI->uri->segment(2)) && !array_key_exists(strtolower($CI->uri->segment(1)), $CI->config->item('handlusers___6287'))) ? true : false));

    if ($is_cahce) {
        return 1;
    }

    if (!$discovery_mode && user_session(12700)) {
        return 3;
    }


    if (!$i) {
        if (strlen($posthashtag)) {
            $filters['LOWER(posthashtag)'] = strtolower($posthashtag);
        } elseif (intval($postid)) {
            $filters['postid'] = $postid;
        } elseif (!$i) {
            return 0;
        }
        //Check privacy first:
        foreach ($CI->Posts->read($filters) as $match_i) {
            $i = $match_i;
            break;
        }
    }

    $chainusercreator = ($replacement_userid > 0 ? $replacement_userid : ($user_session ? $user_session['userid'] : 0));
    $is_author = false;
    if ($chainusercreator) {
        $is_author = count($CI->Chains->read(array(
            'chainusertype' => 12273,
            'chainusercreator' => $chainusercreator,
            'chainpostinput' => $i['postid'],
        )));
    }

    if ($is_author) {

        //Authors can always edit:
        return (!$discovery_mode ? 3 : 2);

    } elseif (!$discovery_mode && count($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___42953')) . ')' => null, //Mentioned Users
            'chainuserinput' => $chainusercreator,
            'chainpostinput' => $i['postid'],
        )))) {

        //Mentioned can always reply:
        return 2;

    } else {

        //Inventory Limits:
        if (!count($post_list_config) && post_spots_remaining($postid) == 0) {
            return 0;
        }

        // POST RELATION CHECK:
        $post_list_config = post_list_config($postid);


        //If post discovered All
        if (count($post_list_config[44161])) {
            $the_counter = 0;
            if ($chainusercreator) {
                foreach ($post_list_config[44161] as $focuspostid) {
                    if (count($CI->Chains->read(array(
                        'chainusercreator' => $chainusercreator,
                        'chainpostinput' => $focuspostid,
                        'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                    )))) {
                        $the_counter++;
                    }
                }
            }
            if (!$chainusercreator || $the_counter < count($post_list_config[44161])) {
                return 0;
            }
        }

        //If post discovered Any
        if (count($post_list_config[40791])) {
            $the_counter = 0;
            if ($chainusercreator) {
                foreach ($post_list_config[40791] as $focuspostid) {
                    if (count($CI->Chains->read(array(
                        'chainusercreator' => $chainusercreator,
                        'chainpostinput' => $focuspostid,
                        'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                    )))) {
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$chainusercreator || !$the_counter) {
                return 0;
            }
        }


        //If Not post discovered All
        if (count($post_list_config[44162])) {
            $the_counter = 0;
            if ($chainusercreator) {
                foreach ($post_list_config[44162] as $focuspostid) {
                    if (count($CI->Chains->read(array(
                        'chainusercreator' => $chainusercreator,
                        'chainpostinput' => $focuspostid,
                        'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                    )))) {
                        $the_counter++;
                    }
                }
                if (!$chainusercreator || $the_counter >= count($post_list_config[44162])) {
                    return 0;
                }
            } else {
                return 0;
            }
        }


        //If Not post discovered Any
        if (count($post_list_config[40793])) {
            $the_counter = 0;
            if ($chainusercreator) {
                foreach ($post_list_config[40793] as $focuspostid) {
                    if (count($CI->Chains->read(array(
                        'chainusercreator' => $chainusercreator,
                        'chainpostinput' => $focuspostid,
                        'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
                    )))) {
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$chainusercreator || $the_counter > 0) {
                return 0;
            }
        }


        // USER RELATION CHECK:


        //IF Follows Any
        if (count($post_list_config[27984])) {
            $the_counter = 0;
            if ($chainusercreator) {
                foreach ($post_list_config[27984] as $focususerid) {
                    if ((($chainusercreator == $focususerid) || count($CI->Chains->read(array(
                            'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                            'chainuserinput' => $focususerid,
                            'chainuseroutput' => $chainusercreator,
                        ))))) {
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$chainusercreator || !$the_counter) {
                return 0;
            }
        }


        //IF Follows All
        if (count($post_list_config[43513])) {
            $the_counter = 0;
            if ($chainusercreator) {
                foreach ($post_list_config[43513] as $focususerid) {
                    if ((($chainusercreator == $focususerid) || count($CI->Chains->read(array(
                            'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                            'chainuserinput' => $focususerid,
                            'chainuseroutput' => $chainusercreator,
                        ))))) {
                        $the_counter++;
                    }
                }
            }
            if (!$chainusercreator || $the_counter < count($post_list_config[43513])) {
                return 0;
            }
        }


        //IF Not Follows Any
        if (count($post_list_config[43514])) {
            $the_counter = 0;
            if ($chainusercreator) {
                foreach ($post_list_config[43514] as $focususerid) {
                    if (($chainusercreator == $focususerid) || count($CI->Chains->read(array(
                            'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                            'chainuserinput' => $focususerid,
                            'chainuseroutput' => $chainusercreator,
                        )))) {
                        //Found an exclusion, so skip this:
                        $the_counter++;
                        break;
                    }
                }
            }
            if (!$chainusercreator || $the_counter > 0) {
                return 0;
            }
        }

        //IF Not Follows All
        if (count($post_list_config[26600])) {
            $the_counter = 0;
            if ($chainusercreator) {
                foreach ($post_list_config[26600] as $focususerid) {
                    if (($chainusercreator == $focususerid) || count($CI->Chains->read(array(
                            'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                            'chainuserinput' => $focususerid,
                            'chainuseroutput' => $chainusercreator,
                        )))) {
                        //Found an exclusion, so skip this:
                        $the_counter++;
                    }
                }
            }
            if (!$chainusercreator || $the_counter == count($post_list_config[26600])) {
                return 0;
            }
        }

        //Public by default:
        return 2;

    }


}


function boost_power()
{
    //Give php page instance more processing power
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 0);
}

function search_enabled()
{
    $CI =& get_instance();
    return ($CI->config->item('universal_search_enabled') && intval(view_memory(6404, 12678)));
}


function update_search($focus__node = null, $s__id = 0)
{

    if (!search_enabled()) {
        return array(
            'status' => 0,
            'message' => 'Search engine disabled',
        );
    }

    $CI =& get_instance();

    /*
     *
     * Syncs data with Algolia Index
     *
     * */

    if ($focus__node && !in_array($focus__node, $CI->config->item('userids___28956'))) {
        return array(
            'status' => 0,
            'message' => 'Object type is invalid',
        );
    } elseif ($s__id && !$focus__node) {
        return array(
            'status' => 0,
            'message' => 'Must define both object type and ID',
        );
    }


    //Define the support objects indexed on algolia:
    $s__id = intval($s__id);
    $limits = array();


    if ($focus__node == 12273) {
        $focus_field_id = 'postid';
    } elseif ($focus__node == 12274) {
        $focus_field_id = 'userid';
    }


    //Loads up algolia search engine functions
    $CI =& get_instance();
    require_once('application/libraries/algoliasearch.php');
    $client = new \AlgoliaSearch\Client($CI->config->item('cred_algolia_app_id'), $CI->config->item('cred_algolia_api_key'));
    $search_index = $client->initIndex('alg_index');


    //Which objects are we fetching?
    if ($focus__node) {

        //We'll only fetch a specific type:
        $fetch_objects = array($focus__node);

    } else {

        //Do both posts and Users:
        $fetch_objects = $CI->config->item('userids___28956');

        //We need to update the entire index, so let's truncate it first:
        $search_index->clearIndex();

    }


    $all_export_rows = array();
    $all_db_rows = array();
    $synced_count = 0;

    foreach ($fetch_objects as $loop_obj) {

        //Reset limits:
        $filters = array();

        //Fetch item(s) for updates including their followings:
        if ($loop_obj == 12273) {

            if ($s__id) {
                $filters['postid'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->Posts->read($filters, 0);

        } elseif ($loop_obj == 12274) {

            //USERS
            if ($s__id) {
                $filters['userid'] = $s__id;
            }

            $db_rows[$loop_obj] = $CI->Users->read($filters, 0);

        }


        //Build the index:
        foreach ($db_rows[$loop_obj] as $s) {

            //Prepare variables:
            unset($export_row);
            $export_row = array();


            //Update Weight if single update:
            if ($s__id) {
                //Update weight before updating this object:
                if ($focus__node == 12273) {
                    post_weight_calculator($s);
                } elseif ($focus__node == 12274) {
                    user_weight_calculator($s);
                }
            }


            //Attempt to fetch Algolia object ID from object Metadata:
            if ($focus__node) {

                $external_name = ($focus__node == 12273 ? 'postexternal' : 'userexternal');

                if (intval($s[$external_name]) > 0) {
                    //We found it! Let's just update existing algolia record
                    $export_row['objectID'] = intval($s[$external_name]);
                }


            } else {

                //Clear possible metadata algolia ID's that have been cached:
                if ($loop_obj == 12273) {
                    $CI->Posts->update($s['postid'], array(
                        'postexternal' => 0,
                    ));
                } elseif ($loop_obj == 12274) {
                    $CI->Users->update($s['userid'], array(
                        'userexternal' => 0,
                    ));
                }

            }

            //To hold followings info
            $export_row['_tags'] = array();
            $export_row['s__keywords'] = '';

            //Now build object-specific index:
            if ($loop_obj == 12273) {

                //POSTS
                //See if this post has a time-range:
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['postid']);
                $export_row['s__user'] = $s['posthashtag'];
                $export_row['s__url'] = view_memory(42903, 33286) . $s['posthashtag']; //Default to post, forward to discovery is lacking superpowers
                $export_row['s__cover'] = '';
                $export_row['s__title'] = $s['postmessageraw'];
                $export_row['s__weight'] = intval($s['postweight']);

                if (post_is_startable($s)) {
                    array_push($export_row['_tags'], 'public_index');
                }

            } elseif ($loop_obj == 12274) {

                //USERS
                $export_row['s__type'] = $loop_obj;
                $export_row['s__id'] = intval($s['userid']);
                $export_row['s__user'] = $s['userhandle'];
                $export_row['s__url'] = view_memory(42903, 42902) . $s['userhandle'];
                $export_row['s__cover'] = $s['usercover'];
                $export_row['s__title'] = $s['username'];
                $export_row['s__weight'] = intval($s['userweight']);

                //Is this an image?
                if (strlen($s['usercover'])) {
                    array_push($export_row['_tags'], 'has_image');
                }

                array_push($export_row['_tags'], 'public_index');

                //Fetch Following:
                foreach ($CI->Chains->read(array(
                    'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                    'chainuseroutput' => $s['userid'], //This follower User
                ), array('chainuserinput'), 0, 0, array('username' => 'DESC')) as $x) {

                    //Add tags:
                    array_push($export_row['_tags'], 'z_' . $x['userid']);

                    //Add Keywords:
                    $export_row['s__keywords'] .= $x['username'] . (strlen($x['chainvalue']) ? ' ' . $x['chainvalue'] : '') . ' ';

                }
            }

            //Prep Keywords:
            $export_row['s__keywords'] = substr(trim(strip_tags($export_row['s__keywords'])), 0, 2000);

            //Add to main array
            array_push($all_export_rows, $export_row);
            array_push($all_db_rows, $s);

        }
    }

    //Did we find anything?
    if (count($all_export_rows) < 1) {

        if (isset($all_export_rows[0]['objectID'])) {

            //Object is deleted locally but still indexed remotely on Algolia, so let's delete it from Algolia:

            //Delete from algolia:
            $algolia_results = $search_index->deleteObject($all_export_rows[0]['objectID']);

            $synced_count += 1;

        }

        return false;
    }


    //Now let's see what to do with the index (Update, Create or delete)
    if ($focus__node) {

        if (isset($all_export_rows[0]['objectID'])) {

            //Update existing index:
            $algolia_results = $search_index->saveObjects($all_export_rows);

        } else {

            //We do not have an index to an Algolia object locally, so create a new index:
            $algolia_results = $search_index->addObjects($all_export_rows);


            //also set its algolia_id to 0 locally:


            //Now update local database with the new objectIDs:
            foreach ($algolia_results['objectIDs'] as $key => $algolia_id) {
                if ($focus__node == 12273) {
                    $CI->Posts->update($all_db_rows[$key][$focus_field_id], array(
                        'postexternal' => $algolia_id,
                    ));
                } elseif ($focus__node == 12274) {
                    $CI->Users->update($all_db_rows[$key][$focus_field_id], array(
                        'userexternal' => $algolia_id,
                    ));
                }
            }

        }

        $synced_count += 1;

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
        if (isset($algolia_results['objectIDs']) && count($algolia_results['objectIDs']) == count($all_db_rows)) {

            foreach ($algolia_results['objectIDs'] as $key => $algolia_id) {

                if (isset($all_db_rows[$key]['postid'])) {
                    $CI->Posts->update($all_db_rows[$key][(isset($all_db_rows[$key]['postid']) ? 'postid' : 'userid')], array(
                        'postexternal' => intval($algolia_id),
                    ));
                } else {
                    $CI->Users->update($all_db_rows[$key][(isset($all_db_rows[$key]['postid']) ? 'postid' : 'userid')], array(
                        'userexternal' => intval($algolia_id),
                    ));
                }

            }
        }

        $synced_count += count($algolia_results['objectIDs']);

    }


    //Return results:
    return array(
        'status' => ($synced_count > 0 ? 1 : 0),
        'message' => $synced_count . ' objects sync with Algolia',
    );

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


function post_creation_time($postid)
{
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(
        'chainid' => $postid,
    )) as $x) {
        return $x['chaintime'];
    }
    //Now:
    return date("Y-m-d H:i:s");
}


function view_cover($cover_code, $noicon_default = null, $icon_prefix = '')
{

    $valid_url = (filter_var($cover_code, FILTER_VALIDATE_URL) || substr($cover_code, 0, 2) == '//');

    //A simple function to display the Member Cover OR the default icon if not available:
    if ($valid_url && $noicon_default) {

        return $icon_prefix . '<div class="img" style="background-image:url(\'' . $cover_code . '\');"></div>';

    } elseif ($valid_url) {

        return $icon_prefix . '<img src="' . $cover_code . '"' . (substr_count($cover_code, 'class=') ? ' class="' . str_replace(',', ' ', one_two_explode('class=', '&', $cover_code)) . '" ' : '') . '/>';

    } elseif (string_is_icon($cover_code)) {

        return $icon_prefix . '<i class="' . $cover_code . '"></i>';

    } elseif (strlen($cover_code)) {

        return $icon_prefix . $cover_code;

    } elseif ($noicon_default && $noicon_default != 1) {

        return $icon_prefix . $noicon_default;

    } else {

        //Standard Cover if none:
        return null;

    }
}

function view_url($string)
{
    return preg_replace('~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i', '<a href="$0" target="_blank">$0</a>', $string);
}

function view_number($number)
{

    if (intval($number) < 1) {
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


function chainprevious($starting_id = -1)
{
    $CI =& get_instance();
    if ($starting_id < 0) {
        foreach ($CI->Chains->read(array(
            '(chainhash IS NOT NULL) AND (chainprevious IS NOT NULL)' => NULL,
            'chainvoid >=' => 0, //Any Chain
        ), array(), 1, 0, array('chainid' => 'DESC')) as $x) {
            return $x['chainhash'];
        }
    } elseif ($starting_id > 0) {
        foreach ($CI->Chains->read(array(
            'chainid >=' => $starting_id,
            'chainvoid >=' => 0, //Any Chain
        ), array(), 1, 0, array('chainid' => 'ASC')) as $x) {
            return $x['chainhash'];
        }
    }
    return '1111111111111111111111111111111111111111';
}


function chainhash($x)
{
    return sha1(
        substr(strtotime($x['chaintime']), 0, 10) .
        $x['chainuserdomain'] .
        $x['chainusercreator'] .
        $x['chainusertype'] .
        (isset($x['chainuserinput']) ? $x['chainuserinput'] : 0) .
        (isset($x['chainuseroutput']) ? $x['chainuseroutput'] : 0) .
        (isset($x['chainpostinput']) ? $x['chainpostinput'] : 0) .
        (isset($x['chainpostoutput']) ? $x['chainpostoutput'] : 0) .
        (isset($x['chainvalue']) ? $x['chainvalue'] : '') .
        (isset($x['chainkey']) ? $x['chainkey'] : 0) .
        $x['chainprevious']
    );
}

function chain_view($x)
{

    $CI =& get_instance();
    $row1 = '<tr width="100%" style="border-top: 1px solid #000000;">';
    $row2 = '<tr width="100%">';
    foreach ($CI->config->item('users___4341') as $userid => $m) {

        $column_value = null;

        if (in_array($userid, array(4593, 14870, 4364))) {

            //MINI USER
            $column_value .= '<td style="width:25px !important;"><div style="width:25px !important; overflow:hidden;">';
            if (isset($x[$m['m__handle']]) && intval($x[$m['m__handle']]) > 0) {
                foreach ($CI->Users->read(array('userid' => $x[$m['m__handle']])) as $focus_e) {
                    $column_value .= '<a href="' . view_memory(42903, 42902) . $focus_e['userhandle'] . '" target="_blank" data-toggle="tooltip" title="' . $focus_e['username'] . '" class="icon-block-sm">' . view_cover($focus_e['usercover']) . '</a>';
                }
            }
            $column_value .= '</div></td>';

        } elseif (in_array($userid, array(4366, 4429))) {

            //Expanded User
            $column_value .= '<td class="fixedchain"><div>';
            if (isset($x[$m['m__handle']]) && intval($x[$m['m__handle']]) > 0) {
                foreach ($CI->Users->read(array('userid' => $x[$m['m__handle']])) as $focus_e) {
                    $column_value .= '<a href="' . view_memory(42903, 42902) . $focus_e['userhandle'] . '" target="_blank" data-toggle="tooltip" title="' . $focus_e['username'] . '">@' . $focus_e['userhandle'] . '</a>';
                }
            }
            $column_value .= '</div></td>';

        } elseif (in_array($userid, array(4368, 4369))) {

            //POST
            $column_value .= '<td class="fixedchain" style=""><div>';
            if (isset($x[$m['m__handle']]) && intval($x[$m['m__handle']]) > 0) {
                foreach ($CI->Posts->read(array('postid' => $x[$m['m__handle']])) as $focus_post) {
                    $column_value .= '<a href="' . view_memory(42903, 33286) . $focus_post['posthashtag'] . '">#' . $focus_post['posthashtag'] . '</a>';
                }
            }
            $column_value .= '</div></td>';

        } elseif ($userid == 4367) {

            //Chain ID
            $column_value .= '<td style="width:62px !important;"><div style="width:62px !important; overflow:hidden;">';
            $column_value .= ($x[$m['m__handle']] > 0 ? '<a href="' . view_app_chain(3445693) . '?chainid=' . $x[$m['m__handle']] . '" target="_blank" '.( intval($x['chainvoid'])>0 ? ' style="text-decoration: line-through;"' : '' ).'>' . $x[$m['m__handle']] . '</a>' : '&nbsp;');
            $column_value .= '</div></td>';

        } elseif ($userid == 44395) {

            //Void:
            $column_value .= '<td style="width:62px !important;"><div style="width:62px !important; overflow:hidden;">';
            $column_value .= ($x[$m['m__handle']] > 0 ? '<a href="' . view_app_chain(3445693) . '?chainid=' . $x[$m['m__handle']] . '" target="_blank">' . $x[$m['m__handle']] . '</a>' : '&nbsp;');
            $column_value .= '</div></td>';

        } elseif ($userid == 4362) {

            //TIME
            $column_value .= '<td style="width:25px !important;">';
            $column_value .= '<div style="width:25px !important; overflow:hidden; text-align: center;">';
            $column_value .= '<span data-toggle="tooltip" data-placement="top" title="' . $x['chaintime'] . ' PST">' . view_time_difference($x['chaintime'], true) . '</span>';
            $column_value .= '</div>';
            $column_value .= '</td>';

        } elseif (in_array($userid, array(1579301, 1579321))) {

            //HASH
            $column_value .= '<td style="width:50px !important;" class="hidden_hash hidden">';
            $column_value .= '<div style="width:50px !important; overflow:hidden;">';
            $column_value .= '<span data-toggle="tooltip" data-placement="top" title="0x' . $x[$m['m__handle']] . '">0x' . substr($x[$m['m__handle']], -4) . '</span>';
            $column_value .= '</div>';
            $column_value .= '</td>';

        } elseif ($userid == 4370) {

            //Number
            $column_value .= '<td>';
            $column_value .= ($x['chainkey'] > 0 ? $x['chainkey'] : '&nbsp;');
            $column_value .= '</td>';

        } elseif ($userid == 4372) {

            //Text
            $column_value .= '<td>';
            $column_value .= (strip_tags($x['chainvalue']) == $x['chainvalue'] || strlen(strip_tags($x['chainvalue'])) < view_memory(6404, 6197) ? nl2br($x['chainvalue']) : '<span class="hidden html_message_' . $x['chainid'] . '">' . $x['chainvalue'] . '</span><a class="html_message_' . $x['chainid'] . '" href="javascript:void(0);" onclick="$(\'.html_message_' . $x['chainid'] . '\').toggleClass(\'hidden\');">View HTML Message</a>');
            $column_value .= '</td>';

        }

        if (in_array($userid, $CI->config->item('userids___1579727'))) {
            //Second row:
            $row2 .= $column_value;
        } else {
            $row1 .= $column_value;
        }

    }
    $row1 .= '</tr>';
    $row2 .= '</tr>';

    return $row1 . $row2;
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
    if ($micro) {
        $time_units = array(
            31536000 => 'y',
            604800 => 'w',
            86400 => 'd',
            3600 => 'h',
            60 => 'm',
            1 => 's'
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


    foreach ($time_units as $unit => $period) {
        if ($time < $unit && $unit > 1) continue;
        $numberOfUnits = number_format(($time / $unit), 0);
        if ($numberOfUnits < 1 && $unit == 1) {
            $numberOfUnits = 1; //Change "0 seconds" to "1 second"
        }

        return $numberOfUnits . ($micro ? '' : ' ') . $period . (($numberOfUnits > 1 && !$micro) ? 's' : '');
    }
}

function view_app_chain($app_id)
{
    return view_memory(42903, 6287) . view_memory(6287, $app_id, 'm__handle');
}

function view_memory($following, $follower, $filed = 'm__message')
{
    $CI =& get_instance();
    $memory_tree = @$CI->config->item('users___' . $following);
    if (is_array($memory_tree) && count($memory_tree) && isset($memory_tree[$follower][$filed])) {
        return $memory_tree[$follower][$filed];
    } else {
        return null;
    }
}


function view_cache($following, $userid, $micro_status = true, $data_placement = 'top', $postid = 0)
{

    /*
     *
     * UI for Platform Cache Users
     *
     * */

    $CI =& get_instance();
    $config_array = $CI->config->item('users___' . $following);
    if (!isset($config_array[$userid])) {
        return false;
    }
    $cache = $config_array[$userid];
    if (!$cache) {
        //Could not find matching item
        return false;
    }


    //We have two skins for displaying Status:
    if (is_null($data_placement)) {
        if ($micro_status) {
            return $cache['m__cover'];
        } else {
            return $cache['m__cover'] . ' ' . $cache['m__name'];
        }
    } else {
        //data-toggle="tooltip" data-placement="' . $data_placement . '"
        return '<span class="' . ($micro_status ? 'cache_micro_' . $following . '_' . $postid : '') . '" ' . ($micro_status && !is_null($data_placement) ? ' title="' . ($micro_status ? $cache['m__name'] : '') . (strlen($cache['m__message']) > 0 ? ($micro_status ? ': ' : '') . $cache['m__message'] : '') . '"' : 'style="cursor:pointer;"') . '>' . $cache['m__cover'] . ' ' . ($micro_status ? '' : $cache['m__name']) . '</span>';
    }
}


function view_card($href, $is_current, $chainusertype, $o__type, $o__title, $chainvalue = null)
{
    $CI =& get_instance();
    $users___4593 = $CI->config->item('users___4593');
    return '<a href="' . ($is_current ? 'javascript:alert(\'You are here already!\');' : $href) . '" class="dropdown-item ' . ($is_current ? ' active ' : '') . '">' .
        '<span class="icon-block-xs">' . $users___4593[$chainusertype]['m__cover'] . '</span>' .
        (strlen($o__type) ? '<span class="icon-block-xs">' . $o__type . '</span>' : '&nbsp;') . //Type or Cover
        $o__title .
        (strlen($chainvalue) && user_session(12701) ? '<div class="message2">' . strip_tags($chainvalue) . '</div>' : '') .
        '</a>';
}

function view_more($href, $is_current, $chainusertype, $o__type, $o__title, $chainvalue = null)
{
    return '<a href="' . ($is_current ? 'javascript:alert(\'You are here already!\');' : $href) . '" class="dropdown-item ' . ($is_current ? ' active ' : '') . '">' .
        ($chainusertype ? '<span class="icon-block-xs">' . $chainusertype . '</span>' : '') .
        (strlen($o__type) ? '<span class="icon-block-xs">' . $o__type . '</span>' : '&nbsp;') . //Type or Cover
        $o__title .
        (strlen($chainvalue) && user_session(12701) ? '<div class="message2">' . strip_tags($chainvalue) . '</div>' : '') .
        '</a>';
}

function view_google_tag($google_analytics_code)
{
    return '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $google_analytics_code . '"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \'' . $google_analytics_code . '\');
</script>';
}


function log_error($error_message, $error_data = array(), $log_error = true)
{

    //Log in PHP File:
    $user_session = user_session();

    if ($log_error) {

        $CI =& get_instance();
        log_message('error', 'MENCH ERROR: ' . $error_message
            . ($user_session ? ' | PLAYER: ' . print_r($user_session, true) : '')
            . ($user_session ? ' | ERROR DATA: ' . print_r($error_data, true) : '')
        );

        //Remove
        foreach ($error_data as $key => $value) {
            if (substr($key, 0, 5) != 'chain') {
                unset($error_data[$key]);
            }
        }

        $CI->Chains->create(array_merge($error_data, array(
            'chainuserinput' => 4246, //Platform Bug Reports
            'chainusertype' => 44176, //User View
            'chainvalue' => $error_message,
            'chainusercreator' => (isset($error_data['chainusercreator']) && $error_data['chainusercreator'] > 0 ? $error_data['chainusercreator'] : ($user_session ? $user_session['userid'] : 0)),
        )));

    }

    return array(
        'status' => 0,
        'message' => $error_message,
        'user_session' => $user_session,
        'error_data' => $error_data,
    );

}


function users_query($chainusertype, $userid, $current_page = 0, $append_card_icon = true, $chainusersub = 0)
{

    /*
     *
     * Loads User
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if ($chainusertype == 12273) {

        //Posts Created
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainpostinput');
        $query_filters = array(
            'chainusercreator' => $userid,
            'chainusertype' => $chainusertype,
        );

    } elseif ($chainusertype == 12274) {

        //User Created
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainuserinput');
        $query_filters = array(
            'chainusercreator' => $userid,
            'chainusertype' => $chainusertype,
        );

    } elseif (!in_array($chainusertype, $CI->config->item('userids___4527')) || !is_array($CI->config->item('userids___' . $chainusertype)) || !count($CI->config->item('userids___' . $chainusertype))) {

        log_error('users_query() @' . $chainusertype . ' Empty Array in Cache @4527');
        return false;

    } elseif ($chainusertype == 32292) {

        //Relationships
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainuseroutput');
        $query_filters = array(
            'chainusercreator' => $userid,
            'chainuseroutput !=' => $userid,
            'chainuserinput !=' => $userid,
            'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null, //USER CHAINS
        );

    } elseif ($chainusertype == 42373) {

        $order_columns = user_sort();
        $joins_objects = array('chainuseroutput');

        if (in_array($chainusersub, $CI->config->item('userids___32292'))) {

            //Down/Followers Sub
            $query_filters = array(
                'chainuserinput' => $userid,
                'chainusertype' => $chainusersub,
            );

        } else {

            //Down/Followers User Chain Groups:
            $query_filters = array(
                'chainuserinput' => $userid,
                'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null, //USER CHAINS
            );

        }

    } elseif ($chainusertype == 42279) {

        $order_columns = user_sort();
        $joins_objects = array('chainuserinput');

        if (in_array($chainusersub, $CI->config->item('userids___32292'))) {

            //Up/Following Sub
            $query_filters = array(
                'chainuseroutput' => $userid,
                'chainusertype' => $chainusersub,
            );

        } else {

            //Up/Following User Chain Groups:
            $query_filters = array(
                'chainuseroutput' => $userid,
                'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null, //USER CHAINS
            );

        }

    } elseif ($chainusertype == 13550) {

        $joins_objects = array('chainpostinput');
        $order_columns = post_sort();

        if (in_array($chainusersub, $CI->config->item('userids___13550'))) {
            //Mentions Sub
            $query_filters = array(
                'chainusertype' => $chainusersub,
                'chainuserinput' => $userid,
            );
        } else {
            //Mentions
            $query_filters = array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null,
                'chainuserinput' => $userid,
            );
        }

    } elseif ($chainusertype == 4486) {

        $order_columns = array();
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainpostoutput');


        if (in_array($chainusersub, $CI->config->item('userids___4486'))) {

            //Posts Sub
            $query_filters = array(
                'chainusercreator' => $userid,
                'chainusertype' => $chainusersub,
            );

        } else {

            //Posts
            $query_filters = array(
                'chainusercreator' => $userid,
                'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null, //DISCOVERY GROUP
            );

        }

    } elseif ($chainusertype == 31777) {

        $order_columns = array();
        $order_columns['chainid'] = 'DESC';
        $joins_objects = array('chainpostoutput');

        if (in_array($chainusersub, $CI->config->item('userids___31777'))) {

            //Discoveries SUB
            $query_filters = array(
                '(chainusercreator=' . $userid . ' OR chainuserinput=' . $userid . ' OR chainuseroutput=' . $userid . ')' => null,
                'chainusertype' => $chainusersub,
            );

        } else {

            //Discoveries
            $query_filters = array(
                '(chainusercreator=' . $userid . ' OR chainuserinput=' . $userid . ' OR chainuseroutput=' . $userid . ')' => null,
                'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null, //DISCOVERY GROUP
            );

        }

    } else {

        return null;

    }

    //print_r($query_filters);

    //Return Results:
    if ($current_page > 0) {

        $limit = view_memory(6404, 11064);
        $query = $CI->Chains->read($query_filters, $joins_objects, $limit, ($current_page - 1) * $limit, $order_columns);
        return $query;

    } else {

        $users___11035 = $CI->config->item('users___11035');
        if (!isset($users___11035[$chainusertype]['m__name'])) {
            log_error('@' . $chainusertype . ' Missing from Nav @11035', array(
                'chainuseroutput' => $chainusertype,
            ));
            $users___11035[$chainusertype] = array(
                'm__name' => '',
                'm__cover' => '',
            );
        }
        $query = $CI->Chains->read($query_filters, $joins_objects, 1, 0, array(), 'COUNT(chainid) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">' . view_number($count_query) . '<span>';
        $title_desc = number_format($count_query, 0) . ' ' . $users___11035[$chainusertype]['m__name'];

        if ($append_card_icon) {

            if (!$count_query) {
                return null;
            }

            $card_icon = '<span class="icon-block-xs">' . $users___11035[$chainusertype]['m__cover'] . '</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding loaduser_cards button_of_' . $userid . '_' . $chainusertype . '" id="carduser_group_' . $chainusertype . '_' . $userid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_chainusertype="' . $chainusertype . '" load_userid="' . $userid . '" load_counter="' . $count_query . '" load_first_segment="' . $first_segment . '"><span title="' . $title_desc . '" data-toggle="tooltip" data-placement="top">' . $card_icon . $visual_counter . '</span></button>';
            $ui .= '<div class="dropdown-menu dropdown_' . $chainusertype . ' coinsuser_' . $userid . '_' . $chainusertype . '" aria-labelledby="carduser_group_' . $chainusertype . '_' . $userid . '">';
            //Menu To be loaded dynamically via AJAX
            $ui .= '</div>';
            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }
    }

}


function posts_query($chainusertype, $postid, $current_page = 0, $append_card_icon = true, $headline_authors = array())
{

    /*
     *
     * Loads Post
     *
     * */

    $CI =& get_instance();
    $first_segment = $CI->uri->segment(1);

    if ($chainusertype == 13550) {

        //USERS
        $joins_objects = array('chainuserinput');
        $query_filters = array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null,
            'chainpostinput' => $postid,
        );
        $order_columns = post_sort();

    } elseif ($chainusertype == 11019) {

        //POST Chain Groups Previous
        $order_columns = array('chainid' => 'DESC');
        $joins_objects = array('chainpostinput');
        $query_filters = array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null, //POST CHAINS
            'chainpostoutput' => $postid,
        );

    } elseif ($chainusertype == 12840) {

        //POST Chain Groups Next
        $order_columns = array('chainkey' => 'ASC');
        $joins_objects = array('chainpostoutput');
        $query_filters = array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null,
            'chainpostinput' => $postid,
        );

    } elseif (in_array($chainusertype, $CI->config->item('userids___12144'))) {

        //DISCOVERIES
        $order_columns = array('chainid' => 'DESC');
        $joins_objects = array('chainusercreator');
        $query_filters = array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___' . $chainusertype)) . ')' => null, //DISCOVERIES
            'chainpostinput' => $postid,
        );

    } else {

        return null;

    }

    //print_r($query_filters);

    //Return Results:
    if ($current_page > 0) {

        $limit = view_memory(6404, 11064);
        return $CI->Chains->read($query_filters, $joins_objects, $limit, ($current_page - 1) * $limit, $order_columns);

    } else {

        $users___11035 = $CI->config->item('users___11035'); //COINS
        $query = $CI->Chains->read($query_filters, $joins_objects, 1, 0, array(), 'COUNT(chainid) as totals');
        $count_query = $query[0]['totals'];
        $visual_counter = '<span class="mini-hidden adjust-left">' . view_number($count_query) . '<span>';
        $title_desc = number_format($count_query, 0) . (isset($users___11035[$chainusertype]['m__name']) ? ' ' . $users___11035[$chainusertype]['m__name'] : '');

        if ($append_card_icon) {

            if (!$count_query) {
                return null;
            }

            $card_icon = '<span class="icon-block-sm">' . $users___11035[$chainusertype]['m__cover'] . '</span>';

            $ui = '<div class="dropdown inline-block">';
            $ui .= '<button type="button" class="btn no-left-padding no-right-padding load_post_cards button_of_' . $postid . '_' . $chainusertype . '" id="card_group_post_' . $chainusertype . '_' . $postid . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" load_chainusertype="' . $chainusertype . '" load_postid="' . $postid . '" load_counter="' . $count_query . '" load_first_segment="' . $first_segment . '"><span title="' . $title_desc . '" data-toggle="tooltip" data-placement="top">' . $card_icon . $visual_counter . '</span></button>';

            //Menu To be loaded dynamically via AJAX:
            $ui .= '<div class="dropdown-menu dropdown_' . $chainusertype . ' coins_post_' . $postid . '_' . $chainusertype . '" aria-labelledby="card_group_post_' . $chainusertype . '_' . $postid . '"></div>';

            $ui .= '</div>';

            return $ui;

        } else {
            return intval($count_query);
        }

    }

}

function view_dynamic_headline($dynamic_userid, $m, $selected_e = null)
{

    $CI =& get_instance();
    $users___11035 = $CI->config->item('users___11035'); //Encyclopedia

    $headline = '<span class="icon-block-sm">' . $m['m__cover'] . '</span>' . $m['m__name'] . ': ';

    if (in_array($dynamic_userid, $CI->config->item('userids___28239'))) {
        $headline .= '<span class="icon-block-sm" title="' . $users___11035[28239]['m__message'] . '" data-toggle="tooltip" data-placement="top" style="font-size:0.34em;">' . $users___11035[28239]['m__cover'] . '</span>';
    }
    if (in_array($dynamic_userid, $CI->config->item('userids___32145'))) {
        $headline .= '<span class="icon-block-sm" title="' . $users___11035[32145]['m__name'] . '" data-toggle="tooltip" data-placement="top">' . $users___11035[32145]['m__cover'] . '</span>';
    }

    if (isset($users___11035[$dynamic_userid]) && strlen($users___11035[$dynamic_userid]['m__message'])) {
        $headline .= '<span class="doregular info_blob ' . (strlen($users___11035[$dynamic_userid]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $users___11035[$dynamic_userid]['m__message'] . '</span></span>';
    }

    return $headline;
}


function view_instant_select($focus__id, $down_userid = 0, $right_postid = 0)
{

    /*
     * Either single or multi select UI elements
     * */

    $CI =& get_instance();
    $users___42179 = $CI->config->item('users___42179'); //Dynamic Input Fields
    $users___11035 = $CI->config->item('users___11035'); //Encyclopedia
    $users___4527 = $CI->config->item('users___4527'); //Memory
    $single_select = in_array($focus__id, $CI->config->item('userids___33331'));
    $multi_select = in_array($focus__id, $CI->config->item('userids___33332'));
    $access_locked = in_array($focus__id, $CI->config->item('userids___32145'));
    $focus_select = $CI->config->item($single_select ? 'users___33331' : 'users___33332');

    if (!$single_select && !$multi_select) {
        //Must be either:
        log_error('view_instant_select() @' . $focus__id . ' not in single select @33331 or multi select 33332', array(
            'chainuseroutput' => $focus__id,
            'chainpostoutput' => $right_postid,
        ));
        return false;
    }

    $already_selected = array();
    $selection_ids = array();
    $selection_options = $CI->Chains->read(array(
        'chainuserinput' => $focus__id,
        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
    ), array('chainuseroutput'), 0, 0, array('chainkey' => 'ASC'));
    foreach ($selection_options as $list_item) {
        array_push($selection_ids, $list_item['userid']);
    }

    //UI for Single select or multi?
    $ui = '<div class="dynamic_selection">';
    $ui .= '<h3 class="mini-font grey">' . view_dynamic_headline($focus__id, $focus_select[$focus__id]) . '</h3>';
    $ui .= '<div class="list-group list-radio-select grey-line radio-' . $focus__id  . '">';

    if ($down_userid > 0) {

        //User Focus:
        if (count($selection_ids)) {
            foreach ($CI->Chains->read(array(
                'chainuserinput IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
                'chainuseroutput' => $down_userid,
                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
            )) as $sel) {
                array_push($already_selected, $sel['chainuserinput']);
            }
        }

        if (!count($already_selected) && $single_select && user_session()) {
            //FIND DEFAULT if set in session of this user:
            $var_id = @$CI->session->userdata('session_custom_ui_' . $focus__id);
            foreach ($selection_ids as $userid2) {
                if ($var_id == $userid2) {
                    $already_selected = array($userid2);
                    break;
                }
            }
        }

    } elseif ($right_postid > 0) {

        //Post focus:
        foreach ($CI->Chains->read(array(
            'chainuserinput IN (' . join(',', $selection_ids) . ')' => null, //All possible answers
            'chainpostinput' => $right_postid,
            'chainusertype IN (' . join(',', $CI->config->item('userids___33602')) . ')' => null, //Post/User Chains Active
        )) as $sel) {
            array_push($already_selected, $sel['chainuserinput']);
        }

    }

    $unselected_count = 0;
    $overflow_unselected_limit = 5;
    $has_selected = count($already_selected);
    $has_multiple = count($selection_options) > 1;
    $overflow_reached = false;
    $exclude_fonts = (in_array($focus__id, $CI->config->item('userids___42417')) ? 'exclude_fonts' : '');
    $users___42179 = $CI->config->item('users___42179'); //Dynamic Input Fields

    foreach ($selection_options as $list_item) {

        //Has superpower?
        if (isset($users___42179[$list_item['userid']]['m__following']) && count($users___42179[$list_item['userid']]['m__following'])) {
            $superpowers_required = array_intersect($CI->config->item('userids___10957'), $users___42179[$list_item['userid']]['m__following']);
            if (count($superpowers_required) && !user_session(end($superpowers_required))) {
                continue;
            }
        }

        $selected = in_array($list_item['userid'], $already_selected);
        if (!$overflow_reached && $unselected_count >= $overflow_unselected_limit && !$selected) {
            $overflow_reached = true;
        }

        $headline = '<span class="inner_headline">' . (strlen($list_item['usercover']) ? '<span class="icon-block-sm change-results">' . view_cover($list_item['usercover']) . '</span>' : '') . $list_item['username'] . '</span>';
        if (in_array($list_item['userid'], $CI->config->item('userids___32145'))) {
            $headline .= '<span class="icon-block-sm" title="' . $users___11035[32145]['m__name'] . '" data-toggle="tooltip" data-placement="top">' . $users___11035[32145]['m__cover'] . '</span>';
        }
        if ($selected) {
            $headline .= '<span class="icon-block-sm checked_icon"><i class="far fa-check"></i></span>';
        }
        if (in_array($list_item['userid'], $CI->config->item('userids___11035')) && strlen($users___11035[$list_item['userid']]['m__message']) > 0) {
            $headline .= '<span class="doregular info_blob ' . (strlen($users___11035[$list_item['userid']]['m__message']) < 55 ? ' short_blob ' : '') . '"><span>' . $users___11035[$list_item['userid']]['m__message'] . '</span></span>';
        }


        if ($selected) {
            if ($access_locked) {
                $ui .= '<span class="list-group-item custom_ui_' . $focus__id . '_' . $list_item['userid'] . ' ' . $exclude_fonts . ' itemsetting_' . $focus__id . ' selection_preview selection_preview_' . $focus__id . ' itemsetting active" title="' . stripslashes($list_item['username']) . '">' . $headline . '</span>';
            } elseif ($has_multiple) {
                $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_' . $focus__id . '\').removeClass(\'hidden\');$(\'.selection_preview_' . $focus__id . '\').addClass(\'hidden\');" class="list-group-item custom_ui_' . $focus__id . '_' . $list_item['userid'] . ' ' . $exclude_fonts . ' itemsetting_' . $focus__id . ' selection_preview selection_preview_' . $focus__id . ' itemsetting active" title="' . stripslashes($list_item['username']) . '">' . $headline . '<span class="icon-block-sm"><i class="far fa-pen-to-square"></i></span></a>';
            }
        }

        if (!$access_locked) {
            $ui .= '<a href="javascript:void(0);" onclick="user_select_apply(' . $focus__id . ',' . $list_item['userid'] . ',' . ($multi_select ? 1 : 0) . ',' . $down_userid . ',' . $right_postid . ')" class="list-group-item itemsetting custom_ui_' . $focus__id . '_' . $list_item['userid'] . ' ' . $exclude_fonts . ' item-' . $list_item['userid'] . ' itemsetting_' . $focus__id . ' selection_item_' . $focus__id . (($has_selected && $has_multiple) || $overflow_reached ? ' hidden' : '') . ($selected ? ' active ' : '') . '" title="' . stripslashes($list_item['username']) . '">' . $headline . '</a>';
        }


        if (!$selected) {
            $unselected_count++;
        }
    }

    if ($overflow_reached && !$has_selected && !$access_locked) {
        //We show this only if non are selected and has too many options:
        $ui .= '<a href="javascript:void(0);" onclick="$(\'.selection_item_' . $focus__id . '\').removeClass(\'hidden\');$(\'.selection_preview_' . $focus__id . '\').addClass(\'hidden\');" class="list-group-item itemsetting selection_preview selection_preview_' . $focus__id . '"><span class="icon-block"><i class="far fa-search-plus"></i></span>Show More</a>';
    }

    $ui .= '</div>';
    $ui .= '</div>';
    return $ui;
}


function randomize_text($userid)
{
    $CI =& get_instance();
    $users___12687 = $CI->config->item('users___12687');
    $line_messages = explode("\n", $users___12687[$userid]['m__message']);
    return $line_messages[rand(0, (count($line_messages) - 1))];
}

function blocked_reasoning($superpower_userid = 0)
{

    if (!user_session()) {

        return 'Sign-in to continue';

    } elseif ($superpower_userid && !user_session($superpower_userid)) {

        $CI =& get_instance();
        $users___10957 = $CI->config->item('users___10957');
        return 'Error: You are missing access to ' . $users___10957[$superpower_userid]['m__name'];

    } else {

        return null;

    }

}


function view_hash($string)
{
    $CI =& get_instance();
    return substr(md5($string . $CI->config->item('secret_hash')), 0, 10);
}


function view_post_title($i, $string_only = false)
{

    if (!isset($i['postmessageraw'])) {
        return null;
    }

    //Break down by lines:
    foreach (explode("\n", $i['postmessageraw']) as $line) {
        if (strlen($line) && !filter_var($line, FILTER_VALIDATE_URL)) {
            return ($string_only ? $line : '<span class="main__title">' . $line . '</span>');
        }
    }

    //If not yet found we need to use other data to generate title:
    return (isset($i['posthashtag']) && strlen($i['posthashtag']) ? $i['posthashtag'] : (isset($i['postid']) && intval($i['postid']) ? 'Post Number ' . $i['postid'] : 'Post' . rand(100000000000, 999999999999)));

}

function view_valid_user_user($string, $check_db = false)
{
    $CI =& get_instance();
    return (substr($string, 0, 1) == '@' && ctype_alnum(substr($string, 1)) && (!$check_db || count($CI->Users->read(array(
            'LOWER(userhandle)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false);
}

function view_valid_user_post($string, $check_db = false)
{
    //TODO MUst remove
    $CI =& get_instance();
    return (substr($string, 0, 1) == '#' && ctype_alnum(substr($string, 1)) && (!$check_db || count($CI->Posts->read(array(
            'LOWER(posthashtag)' => strtolower(substr($string, 1)),
        )))) ? substr($string, 1) : false);
}


function view_postmessageraw($i, $userid = 0, $focus__node = false, $discovery_mode = true, $show_postmessageedit = false)
{

    if (!isset($i['postid'])) {
        return null;
    }

    //Append Custom Reference Chain contents, if any:
    $CI =& get_instance();

    //This is still flawed, we need to fix this to exlude cache apps and more:
    $field = ($show_postmessageedit && isset($i['postmessageedit']) && strlen($i['postmessageedit']) ? 'postmessageedit' : 'postmessageview');

    if ($userid > 0) {
        foreach ($CI->Chains->read(array(
            'chainpostinput' => $i['postid'],
            'chainusertype' => 31835, //References
        ), array('chainuserinput'), 0) as $message_references) {
            if (!substr_count(strtolower($i[$field]), '>@' . strtolower($message_references['userhandle']))) {
                //Maybe because it was duplicated, etc REMOVE IT:
                //$CI->Chains->delete($message_references['chainid']);
                continue;
            }
            foreach ($CI->Chains->read(array(
                'chainuserinput' => $message_references['userid'],
                'chainuseroutput' => $userid,
                'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                'LENGTH(chainvalue) > 0' => null,
            ), array(), 1) as $reference_profile) {
                if (strlen($reference_profile['chainvalue'])) {
                    if (filter_var($reference_profile['chainvalue'], FILTER_VALIDATE_URL)) {
                        $i[$field] = str_ireplace('@' . $message_references['userhandle'] . '</a>', '</a>' . '<a href="' . $reference_profile['chainvalue'] . '" target="_blank">' . $reference_profile['chainvalue'] . '</a>', $i[$field]);

                    } else {
                        $i[$field] = str_ireplace('@' . $message_references['userhandle'], (filter_var($reference_profile['chainvalue'], FILTER_VALIDATE_URL) ? '' : '@' . $message_references['userhandle'] . ' ') . $reference_profile['chainvalue'], $i[$field]);
                    }
                }
            }
        }
    }

    return
        $i[$field] /* . view_list_user($i['postid']) */;
}


function post_to_title($post, $parent_post = null)
{

    //Generates a title from a post:

    //Remove Common prefix with parent postif any:
    $common_start = '';
    $new_post = '';
    if (strlen($parent_post)) {
        //See if post has anything in common with its parent, if any:
        $parent_post_array = str_split($parent_post);
        foreach (str_split($post) as $key => $value) {
            if (isset($parent_post_array[$key]) && $parent_post_array[$key] === $value) {
                $common_start .= $value;
            } else {
                $new_post .= $value;
            }
        }
        if (strlen($common_start) && strlen($new_post)) {
            //Remove this from the string:
            $post = $new_post;
        }
    }

    //Now detect the title based on remaining post:
    $new_title = '';
    $post_array = str_split($post);
    foreach ($post_array as $key => $value) {
        $new_title .= (ctype_upper($value) && ((isset($post_array[($key - 1)]) && !ctype_upper($post_array[($key - 1)])) || (isset($post_array[($key + 1)]) && !ctype_upper($post_array[($key + 1)]))) ? ' ' : '') . $value;
    }

    return (strlen($new_title) >= 2 ? trim($new_title) : 'New Post');

}


function post_view($chainusertype, $i, $previous_i = null, $target_posthashtag = null, $focus__userid = 0, $x_completes = false)
{

    //Search to see if an  posthas a thumbnail:
    $CI =& get_instance();

    $chainid = (isset($i['chainid']) && $i['chainid'] > 0 ? $i['chainid'] : 0);
    $users___11035 = $CI->config->item('users___11035'); //Encyclopedia
    $users___4593 = $CI->config->item('users___4593');
    $is_ajax = strtolower($CI->uri->segment(1)) == 'ajax' || strtolower($CI->uri->segment(1)) == 'controller';
    $is_cache = in_array($chainusertype, $CI->config->item('userids___14599'));
    $goto_start = in_array($chainusertype, $CI->config->item('userids___42988'));
    $user_session = user_session();
    $superpower_10939 = !$is_cache && user_session(10939);
    $post_startable = post_is_startable($i);
    $session_user = ($user_session ? $user_session['userid'] : 0);
    $chainusercreator = ( $focus__userid > 0 ? $focus__userid : $session_user );
    $usercreator = ($session_user > 0 ? $session_user : 14068 /* GUEST */);
    $chain_creator = isset($i['chainusercreator']) && $i['chainusercreator'] == $session_user;
    $focus__node = in_array($chainusertype, $CI->config->item('userids___12149')); //NODE COIN
    $discovery_uri = (isset($_POST['js_request_uri']) && substr_count($_POST['js_request_uri'], '/') == 2 ? one_two_explode('/', '/', $_POST['js_request_uri']) : false);
    $discovery_term = (!$is_ajax && strlen($CI->uri->segment(2)) ? $CI->uri->segment(1) : false);
    $discovery_mode = $session_user && ($discovery_uri || $discovery_term);
    $post_access = post_access($i['posthashtag'], 0, $i, false, array(), $is_cache);
    $focus_post_uri = ($discovery_uri ? one_two_explode('/', '', substr($_POST['js_request_uri'], 1)) : false);
    $focus_post_seg = ($discovery_term ? $CI->uri->segment(2) : false);
    $focus_posthashtag = ($focus_post_uri ? $focus_post_uri : ($focus_post_seg ? $focus_post_seg : false));
    $show_postmessageedit = ($superpower_10939 && !$is_cache && ((!$is_ajax && !strlen($CI->uri->segment(2))) || ($is_ajax && substr_count($_POST['js_request_uri'], '/') == 1)));
    if ($discovery_mode && !$target_posthashtag && ($discovery_uri || $discovery_term)) {
        $target_posthashtag = ($discovery_uri ? $discovery_uri : $discovery_term);
    }
    if ($target_posthashtag && $focus_posthashtag && $focus_posthashtag == $i['posthashtag']) {
        $focus_posthashtag = false;
    }

    if ($session_user && !is_array($x_completes)) {
        //Fetch discovery
        $x_completes = $CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainusercreator' => $session_user,
            'chainpostinput' => $i['postid'],
        ), array('chainpostoutput'));
    }

    $focus_post_or = false;
    if ($discovery_mode && $focus_posthashtag && !$focus__node && isset($previous_i['postid']) && $session_user && !count($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostinput' => $previous_i['postid'],
            'chainuserinput' => 43758,
        )))) {
        foreach ($CI->Posts->read(array(
            'LOWER(posthashtag)' => strtolower($focus_posthashtag),
        )) as $focus_post) {
            if (count($CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $focus_post['postid'],
                'chainuserinput IN (' . join(',', $CI->config->item('userids___7712')) . ')' => null, //Input Choice
            )))) {
                $focus_post_or = $focus_post;
            }
        }
    }

    $was_discovered = 0;
    if (!$is_cache && $session_user) {
        $discoveries = $CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainusercreator' => $session_user,
            'chainpostinput' => $i['postid'],
        ));
        $was_discovered = count($discoveries);
    }
    if ($was_discovered && $discovery_mode) {
        $i = array_merge($i, $discoveries[0]);
    }

    $target_posthashtag_discover = null;
    if ($was_discovered && !$target_posthashtag) {
        foreach ($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___31777')) . ')' => null, //DISCOVERIES
            'chainusercreator' => $session_user,
            'chainpostinput' => $i['postid'],
        ), array('chainpostoutput')) as $CI_dis) {
            $target_posthashtag_discover = $CI_dis['posthashtag'];
            $target_posthashtag = $target_posthashtag_discover;
        }
    }

    $is_locked = ($discovery_mode && !$was_discovered && !$focus__node);
    $is_required = count($CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
        'chainuserinput' => 28239, //Required
    )));

    if (($goto_start || !$superpower_10939) && $post_startable) {
        $href = view_memory(42903, 30795) . $i['posthashtag'] . '/' . view_memory(6404, 4235);
    } elseif ($is_locked) {
        $href = null;
    } elseif ($discovery_mode && $target_posthashtag) {
        $href = view_memory(42903, 30795) . $target_posthashtag . '/' . $i['posthashtag'];
        //} elseif ($target_posthashtag_discover) {
        //$href = view_memory(42903, 30795) . $target_posthashtag_discover . '/' . $i['posthashtag'];
    } elseif ($discovery_mode) {
        $href = view_memory(42903, 33286) . $i['posthashtag'];
    } else {
        $href = view_memory(42903, 33286) . $i['posthashtag'];
    }


    if(!$is_cache){
        //Log List view:
        $CI->Chains->create(array(
            'chainusertype' => ( $focus__node ? 1309378 /* Post User */ : 3112531 /* List Post */ ),
            'chainusercreator' => $usercreator,
            'chainuserinput' => $usercreator,
            'chainpostinput' => $i['postid'],
        ));
    }




    //Top action menu:
    $ui = '<div postid="' . $i['postid'] . '" posthashtag="' . $i['posthashtag'] . '" discovery_mode="' . intval($discovery_mode) . '" chainid="' . $chainid . '" href="' . $href . '" class="card_cover card_post_cover ' . ($focus__node ? ' focus-cover slim_flat coll-md-8 coll-sm-10 col-12
     ' : ' edge-cover ' . ($discovery_mode ? ' col-12 ' : ' coll-md-4 coll-6 col-12 ')) . ' no-padding card-12273 s__12273_' . $i['postid'] . ' ' . (strlen($href) ? ' card_click ' : '') . (!$focus_post_or && $is_locked ? ' is_locked' : '') . ($chainid ? ' cover_x_' . $chainid . ' ' : '') . '">';

    if ($discovery_mode && $session_user && $focus__node) {
        $ui .= '<style> .add_post{ display:none; } </style>';
    }

    if ($is_required) {
        //Add required icon:
        $ui .= '<script> $(document).ready(function () { $(\'.cache_frame_' . $i['postid'] . ' .first_line:first\').append(\'<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' asterisk" title="Required">*</span>\'); }); </script>';
    }

    if ($focus_post_or) {
        $ui .= '<div class="this_selector this_selector_' . $i['postid'] . '" selection_postid="' . $i['postid'] . '"><span class="icon-block-sm">' . (count($CI->Chains->read(array(
                'chainusertype' => 7712, //Input Choice
                'chainusercreator' => $session_user,
                'chainpostinput' => $focus_post_or['postid'],
                'chainpostoutput' => $i['postid'],
            ))) ? '<i class="fas fa-square-check fa-sharp"></i>' : '<i class="far fa-square fa-sharp"></i>') . '</span></div>';
    }

    $ui .= '<div class="cover-content ' . ($focus_post_or ? ' cover_selector ' : '') . '">';
    $ui .= '<div class="inner-content">';
    $ui .= '<div class="cover-text">';

    //Show Chain User:
    $ui .= '<div class="creator_frame creator_frame_' . $i['postid'] . '">';

    //Show Creator if any:
    $headline_authors = array();
    foreach ($CI->Chains->read(array(
        'chainvoid >=' => 0, //Does not matter if it has been updated, we want the original author here
        'chainusertype' => 12273,
        'chainpostinput' => $i['postid'],
    ), array('chainusercreator'), 1, 0, array('chainid' => 'ASC')) as $creator) {

        array_push($headline_authors, $creator['userid']);

        $ui .= '<div class="creator_headline"><a href="' . view_memory(42903, 42902) . $creator['userhandle'] . '"><span class="icon-block">' . view_cover($creator['usercover']) . '</span><b class="hidden">' . $creator['username'] . '</b><span class="grey mini-font mini-frame">@' . $creator['userhandle'] . '</span></a>'.( $chainid ? '<span class="grey mini-font mini-padded mini-frame mini_time" title="' . date("Y-m-d H:i:s", strtotime($creator['chaintime'])) . ' PST">' . view_time_difference($creator['chaintime'], true) . '</span>' : '' ).'</div>';

    }


    $ui .= ($href ? '<a href="' . $href . '"' : '<div') . ' title="' . $i['postid'] . '" class="sub__user space-content grey ' . (!$superpower_10939 && ($discovery_mode || !$focus__node || !$session_user) ? ' hidden ' : '') . '">' . (isset($i['chainusertype']) ? (substr_count($users___4593[$i['chainusertype']]['m__cover'], '#') || substr_count($users___4593[$i['chainusertype']]['m__cover'], 'fa-hashtag') ? $users___4593[$i['chainusertype']]['m__cover'] : ( !substr_count($users___4593[$i['chainusertype']]['m__cover'], '@') ? $users___4593[$i['chainusertype']]['m__cover'].' ' : ''  ). '#') : '#') . '<span class="ui_posthashtag_' . $i['postid'] . '">' . $i['posthashtag'] . '</span>' . ($href ? '</a>' : '</div>');

    //Right menu push here:
    //Bottom Bar
    $bottom_bar_ui = '';

    //Determine Chain Group
    $chainusertype_id = 4593; //Chain Type
    $chainusertype_ui = '';
    if (!$focus__node && $chainid && !$is_cache) {
        foreach ($CI->config->item('users___31770') as $chainusertype1 => $m1) {
            if (in_array($i['chainusertype'], $CI->config->item('userids___' . $chainusertype1))) {
                $chainusertype_id = $chainusertype1;
                break;
            }
        }
    }

    foreach ($CI->config->item('users___31904') as $chainusertype_target_bar => $m_target_bar) {

        //See if missing superpower?
        $superpowers_required = array_intersect($CI->config->item('userids___10957'), $m_target_bar['m__following']);
        if (count($superpowers_required) && (!user_session(end($superpowers_required)) || $is_cache)) {
            continue;
        }

        //Determine hover state:
        if ($chainusertype_target_bar == 31770 && !$discovery_mode && $chainusertype_ui && $superpower_10939) {

            //Chains
            $bottom_bar_ui .= $chainusertype_ui;

        } elseif ($chainusertype_target_bar == 4362 && !$is_cache && !$discovery_mode && $user_session && isset($i['chaintime']) && strtotime($i['chaintime']) > 0 && $chainusertype_ui && ($post_access >= 3 || ($user_session && $session_user == $i['chainusercreator']))) {

            //Chain Time / Creator
            $creator_details = '';
            $time_diff = view_time_difference($i['chaintime'], true);
            $creator_name = '';
            if ($i['chainusercreator'] > 0) {
                foreach ($CI->Users->read(array(
                    'userid' => $i['chainusercreator'],
                )) as $creator) {
                    $creator_name = 'Chained by ' . $creator['username'] . ' @' . $creator['userhandle'] . ' on ';
                    $creator_details = '<a href="' . view_memory(42903, 33286) . $i['posthashtag'] . '"><span class="icon-block-sm">' . view_cover($creator['usercover']) . '</span></a>';
                }
            }

            $bottom_bar_ui .= '<span class="icon-block-sm"><div class="grey created_time" title="' . $creator_name . date("Y-m-d H:i:s", strtotime($i['chaintime'])) . ' which is ' . $time_diff . ' ago | ID ' . $i['chainid'] . '">' . ($creator_details ? $creator_details : $time_diff) . '</div></span>';

        } elseif ($chainusertype_target_bar == 14980 && !$is_cache && $post_access >= 1 && !$discovery_mode) {

            //Drop Down
            $action_buttons = null;
            if (!$chainid) {
                $focus_dropdown = 11047; //Post Dropdown
            } elseif ($chainusertype_id == 4486) { //Post/Post Chains
                $focus_dropdown = 14955; //Post/Post Dropdown
            } elseif ($chainusertype_id == 13550) { //Post/User Chains
                $focus_dropdown = 28787; //Post/User Dropdown
            } else {
                //Discoveries
                $focus_dropdown = 32069; //Post/Discoveries Dropdown
            }

            if (is_array($CI->config->item('users___' . $focus_dropdown))) {
                foreach ($CI->config->item('users___' . $focus_dropdown) as $userid_dropdown => $m_dropdown) {

                    //Skip if missing superpower:
                    $superpowers_required = array_intersect($CI->config->item('userids___10957'), $m_dropdown['m__following']);
                    if (count($superpowers_required) && !user_session(end($superpowers_required))) {
                        continue;
                    }

                    $anchor = '<span class="icon-block-sm">' . $m_dropdown['m__cover'] . '</span>' . $m_dropdown['m__name'];

                    if ($userid_dropdown == 12589 && $post_access >= 3) {

                        //Mass Apply
                        $action_buttons .= '<a href="javascript:void(0);" onclick="chain_preview(12589,' . $i['postid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 33286 && $discovery_mode && $post_access >= 3) {

                        //Posts Mode
                        $action_buttons .= '<a href="' . view_memory(42903, 33286) . $i['posthashtag'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 31911 && $post_access >= 3) {

                        //Post Editor
                        $action_buttons .= '<a href="javascript:void(0);" onclick="post_edit_start(' . $i['postid'] . ',' . $chainid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 13007 && $post_access >= 3) {

                        //Reset Alphabetic order
                        $action_buttons .= '<a href="javascript:void(0);" onclick="chain_sort_reset()" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 31911 && $post_access >= 3 && $discovery_mode) {

                        //Post Editor
                        $action_buttons .= '<a href="javascript:void(0);" onclick="post_edit_start(' . $i['postid'] . ',' . $chainid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 10673 && $chainid && $post_access >= 3) {

                        //Unchain
                        $action_buttons .= '<a href="javascript:void(0);" onclick="chain_delete(' . $chainid . ', ' . $chainusertype . ',\'' . $i['posthashtag'] . '\')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 30873 && $post_access >= 3) {

                        //Clone Post Tree:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="(' . $i['postid'] . ', 1)" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 33292 && $user_session) {

                        //Stats
                        $action_buttons .= '<a href="' . view_app_chain(33292) . view_memory(42903, 33286) . $i['posthashtag'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 29771 && $post_access >= 3) {

                        //Clone Single Post:
                        $action_buttons .= '<a href="javascript:void(0);" onclick="post_copy(' . $i['postid'] . ', 0)" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 3445693 && $post_access >= 3 && $chainid) {

                        //Chain Details
                        $action_buttons .= '<a href="' . view_app_chain(3445693) . '?chainid=' . $chainid . '" class="dropdown-item main__title" target="_blank">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 42648 && $post_access >= 3) {

                        //Delete Permanently
                        $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                        $action_buttons .= '<a href="javascript:void();" onclick="post_delete(' . $i['postid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                    } elseif ($userid_dropdown == 28637 && isset($i['chainusertype']) && user_session(12700)) {

                        //Paypal Details
                        $chainvalue = @unserialize($i['chainvalue']);
                        if (isset($chainvalue['txn_id'])) {
                            $action_buttons .= '<a href="https://www.paypal.com/activity/payment/' . $chainvalue['txn_id'] . '" class="dropdown-item main__title" target="_blank">' . $anchor . '</a>';
                        }

                    } elseif (in_array($userid_dropdown, $CI->config->item('userids___6287')) && $post_access >= 3) {

                        //Standard button
                        $action_buttons .= '<a href="' . view_app_chain($userid_dropdown) . view_memory(42903, 33286) . $i['posthashtag'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                    }
                }
            }

            //Any items found?
            if ($action_buttons && $focus_dropdown > 0) {
                //Right Action Menu
                $users___14980 = $CI->config->item('users___14980'); //Dropdowns

                $bottom_bar_ui .= '<span>';
                $bottom_bar_ui .= '<div class="dropdown inline-block">';
                $bottom_bar_ui .= '<button type="button" class="btn no-left-padding no-right-padding icon-block-sm" id="action_menu_post_' . $i['postid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="' . $users___14980[$focus_dropdown]['m__name'] . '">' . $users___14980[$focus_dropdown]['m__cover'] . '</button>';
                $bottom_bar_ui .= '<div class="dropdown-menu" aria-labelledby="action_menu_post_' . $i['postid'] . '">';
                $bottom_bar_ui .= $action_buttons;
                $bottom_bar_ui .= '</div>';
                $bottom_bar_ui .= '</div>';
                $bottom_bar_ui .= '</span>';

            }
        }
    }

    if ($bottom_bar_ui) {
        $ui .= '<div class="pull-right grey">';
        $ui .= $bottom_bar_ui;
        $ui .= '</div>';
    }


    //Post Location if any:
    foreach ($CI->Chains->read(array(
        'chainusertype' => 41949, //Locate
        'chainpostinput' => $i['postid'],
    ), array('chainuserinput')) as $location) {
        $ui .= view_featured_chains(41949, $location, null, $focus__node);
    }

    //Chain Message if any:
    /*
    if ($chainid && $user_session) {
        $ui .= '<div class="chainvalue_headline grey hideIfEmpty ignore-click ui_chainvalue_' . $chainid . '" style="padding-left:40px;">' . htmlentities($i['chainvalue']) . '</div>';
    }
    */


    $ui .= '</div>';


    //Post Message (Remaining)
    $ui .= '<div class="ui_postmessageview_' . $i['postid'] . (!$focus__node ? ' space-content ' : '') . '">' . view_postmessageraw($i, $chainusercreator, $focus__node, $discovery_mode, $show_postmessageedit) . '</div>';


    $post_popup_url = post_popup_url($i);
    if ($post_popup_url) {
        $ui .= '<div class="ignore-click chain_click chain_click_' . $i['postid'] . ' hideIfEmpty"><a href="' . $post_popup_url . '" class="hideIfEmpty" target="_blank" onclick="chain_clicked(' . $i['postid'] . ')">' . $post_popup_url . '</a></div>';
    }


    //Raw Data:
    $ui .= '<div class="ui_postmessageraw_' . $i['postid'] . '
     hidden">' . $i['postmessageraw'] . '</div>';


    $ui .= '</div>';
    $ui .= '</div>';
    $ui .= '</div>';

    //Three main actions: (Excludes reading which is no action)
    $input_ui = '';

    //Any inputs for this post?
    if (isset($previous_i['postid']) && (count($CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $previous_i['postid'],
                'chainuserinput' => 43758,
            ))) || ($focus__node && count($CI->Chains->read(array(
                    'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                    'chainpostinput' => $i['postid'],
                    'chainuserinput IN (' . join(',', $CI->config->item('userids___41055')) . ')' => null,
                    'chainuserinput !=' => 43758,
                )))))) {

        //PAYMENT TICKET
        if (isset($_GET['cancel_pay']) && !count($x_completes)) {
            $input_ui .= '<div class="alert alert-danger" role="alert">You cancelled your payment.</div>';
        }

        if (isset($_GET['process_pay']) && !count($x_completes)) {

            $input_ui .= '<div class="alert alert-warning" role="alert"><span class="icon-block-sm"><i class="fas fa-yin-yang fa-spin"></i></span>Processing your payment, please wait</div>';

            //Referesh soon so we can check if completed or not
            js_php_redirect(view_memory(42903, 30795) . $target_posthashtag . '/' . $i['posthashtag'] . '?process_pay=1', 987);

        } elseif (isset($previous_i['postid']) && !count($CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $previous_i['postid'],
                'chainuserinput' => 43758,
            ))) && count($x_completes)) {

            foreach ($x_completes as $x_complete) {

                $chainvalue = unserialize($x_complete['chainvalue']);
                $quantity = ($x_complete['chainkey'] >= 2 ? $x_complete['chainkey'] : (isset($chainvalue['quantity']) && $chainvalue['quantity'] >= 2 ? $chainvalue['quantity'] : 1));

                if ($chainvalue['mc_gross'] != 0) {
                    $input_ui .= '<div class="alert alert-success tickets_issued" role="alert"><span class="icon-block"><i class="far fa-check-circle"></i></span>' . ($chainvalue['mc_gross'] > 0 ? 'You paid ' : 'You got a refund of ') . str_replace('.00', '', $chainvalue['mc_gross']) . ' ' . $chainvalue['mc_currency'] . ($quantity > 1 ? ' for ' . $quantity . ' tickets' : '') . ' & should receive a Paypal Email Receipt shortly.</div>';
                }

            }

            $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $chainvalue['mc_gross'] . '">';
            $input_ui .= '<input type="hidden" class="postweight" name="quantity" value="' . $chainvalue['quantity'] . '">'; //Dynamic Variable that JS will update

        } else {

            $valid_instant_pay = false; //Until we can find and verify from DB

            $paypal_email = website_setting(30882);

            $currency_types = $CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => (isset($previous_i['postid']) && count($CI->Chains->read(array(
                    'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                    'chainpostinput' => $previous_i['postid'],
                    'chainuserinput' => 43758,
                ))) ? $previous_i['postid'] : $i['postid']),
                'chainuserinput IN (' . join(',', $CI->config->item('userids___26661')) . ')' => null, //Currency
            ));
            $total_dues = $CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $i['postid'],
                'chainuserinput' => 26562, //Total Due
            ));
            $cart_max = $CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $i['postid'],
                'chainuserinput' => 29651, //Cart Max Quantity
            ));
            $cart_min = $CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $i['postid'],
                'chainuserinput' => 31008, //Cart Min Quantity
            ));


            $unit_price = 0;
            if (count($total_dues) && doubleval($total_dues[0]['chainvalue'])) {
                $unit_price = doubleval($total_dues[0]['chainvalue']);
            } else {
                //Try to find the first user reference and see if this user has a personalized value there to replace a fixed value:
                foreach ($CI->Chains->read(array(
                    'chainusertype' => 31835, //Mention
                    'chainpostinput' => $i['postid'],
                    'chainkey' => 1,
                ), array('chainuserinput'), 1, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $user_session['userid'] */) as $user_output) {
                    foreach ($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                        'chainuserinput' => $user_output['userid'],
                        'chainuseroutput' => $user_session['userid'], //Since we are limiting the query to session user we could disable the $access_limit in the query before it
                    ), array('chainuserinput'), 1, 0, array('chainkey' => 'ASC'), '*', null, false /* Limited to $user_session['userid'] */) as $user_data) {
                        if (doubleval($user_data['chainvalue'])) {
                            $unit_price = doubleval($user_data['chainvalue']);
                        }
                    }
                }
            }

            //Payments Must have Unit Price, otherwise they are NOT a payment until added
            $info_append = '';
            $unit_currency = '';
            $unit_fee = 0;
            $max_allowed = (count($cart_max) && is_numeric($cart_max[0]['chainvalue']) && $cart_max[0]['chainvalue'] > 0 ? intval($cart_max[0]['chainvalue']) : view_memory(6404, 29651));
            $spots_remaining = post_spots_remaining($i['postid']);
            $max_allowed = ($spots_remaining > -1 && $spots_remaining < $max_allowed ? $spots_remaining : $max_allowed);

            $min_allowed = (count($cart_min) && is_numeric($cart_min[0]['chainvalue']) && intval($cart_min[0]['chainvalue']) > $is_required ? intval($cart_min[0]['chainvalue']) : $is_required);
            $users___26661 = $CI->config->item('users___26661'); //Currency
            if (count($currency_types)) {
                $unit_currency = $users___26661[$currency_types[0]['chainuserinput']]['m__message'];
            }

            $prev_invoice = isset($previous_i['postid']) && count($CI->Chains->read(array(
                    'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                    'chainpostinput' => $previous_i['postid'],
                    'chainuserinput' => 43758,
                )));

            if ($session_user && filter_var($paypal_email, FILTER_VALIDATE_EMAIL) && !$prev_invoice && $unit_price && count($currency_types) == 1) {

                $valid_instant_pay = true;

                //Append information to cart about Paypal:
                $info_append .= '<div class="sub_note">After completing the payment on PayPal click "<span style="color: #990000;">Return to Merchant</span>" to continue back here. By paying you agree to our <a href="' . view_app_chain(14373) . '" target="_blank">Terms of Use</a>.</div>';

            }


            $current_value = $min_allowed;
            foreach ($CI->Chains->read(array(
                'chainusertype' => 7712, //Input Choice
                'chainusercreator' => $user_session['userid'],
                'chainpostoutput' => $i['postid'],
            ), array(), 1) as $x_selection) {
                $current_value = $x_selection['chainkey'];
            }


            //Is multi selectable, allow show down for quantity:
            $input_ui .= '<div class="user-info ticket-notice" title="' . $users___11035[44242]['m__name'] . '">'
                . '<span class="icon-block">' . $users___11035[44242]['m__cover'] . '</span>'
                . '<div class="user_info_box">';

            if ($max_allowed > 0 || $min_allowed > 0) {
                $input_ui .= '<div class="sale_controller sale_controller_' . $i['postid'] . '" unitprice="' . $unit_price . '" unitcurrency="' . $unit_currency . '" postid="' . $i['postid'] . '">';
                $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(-1,' . $i['postid'] . ',' . $max_allowed . ',' . $min_allowed . ',' . ($unit_fee + $unit_price) . ',' . $unit_fee . ')" class="sale_increment sale_down"><i class="fas fa-minus ' . ($current_value == $min_allowed ? ' hidden ' : '') . '"></i></a>';
                $input_ui .= '<span class="main__title current_count">' . $current_value . '</span>';
                $input_ui .= '<a href="javascript:void(0);" onclick="sale_increment(1,' . $i['postid'] . ',' . $max_allowed . ',' . $min_allowed . ',' . ($unit_fee + $unit_price) . ',' . $unit_fee . ')" class="sale_increment sale_up">' . ($max_allowed == $min_allowed ? '<i class="fas fa-lock islocked"></i>' : '<i class="fas fa-plus"></i>') . '</a>';
                $input_ui .= '</div>';
            } else {
                $input_ui .= '<span class="current_count" style="display: none;">' . $min_allowed . '</span>';
            }

            $input_ui .= $info_append;

            $input_ui .= '</div>';
            $input_ui .= '</div>';


            if ($valid_instant_pay) {

                $users___14870 = $CI->config->item('users___14870'); //DOMAINS

                //Load Paypal Pay button:
                $input_ui .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">';

                $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $unit_fee . '">';
                $input_ui .= '<input type="hidden" class="postweight" name="quantity" value="' . $min_allowed . '">'; //Dynamic Variable that JS will update
                $input_ui .= '<input type="hidden" name="item_name" value="' . remove_none_utf8(view_post_title($i, true)) . '">';
                $input_ui .= '<input type="hidden" name="item_number" value="' . ($target_posthashtag ? $target_posthashtag . ' #' : '') . $i['posthashtag'] . ' @' . get_domain('m__handle') . ' @' . $user_session['userhandle'] . '">';

                $input_ui .= '<input type="hidden" name="amount" value="' . $unit_price . '">';
                $input_ui .= '<input type="hidden" name="currency_code" value="' . $unit_currency . '">';
                $input_ui .= '<input type="hidden" name="no_shipping" value="1">';
                $input_ui .= '<input type="hidden" name="notify_url" value="https://' . $users___14870[2738]['m__message'] . view_app_chain(44183) . '">';
                $input_ui .= '<input type="hidden" name="cancel_return" value="https://' . get_domain('m__message') . view_memory(42903, 30795) . $target_posthashtag . '/' . $i['posthashtag'] . '?cancel_pay=1">';
                $input_ui .= '<input type="hidden" name="return" value="https://' . get_domain('m__message') . view_memory(42903, 30795) . $target_posthashtag . '/' . $i['posthashtag'] . '?process_pay=1">';
                $input_ui .= '<input type="hidden" name="cmd" value="_xclick">';
                $input_ui .= '<input type="hidden" name="business" value="' . $paypal_email . '">';

                $input_ui .= '<input type="submit" class="adj-btn pay-btn main__title" name="pay_now" id="pay_now" value="Pay Now >" onclick="$(\'.process-btn\').html(\'Loading\');$(\'#pay_now\').val(\'...\');">';

                $input_ui .= '</form>';

                $input_ui .= '<script> $(document).ready(function () { $(\'.discovered_btn\').hide(); }); </script>';

            } else {

                //FREE TICKET
                $input_ui .= '<input type="hidden" class="paypal_handling" name="handling" value="' . $unit_fee . '">';
                $input_ui .= '<input type="hidden" class="postweight" name="quantity" value="' . $min_allowed . '">'; //Dynamic Variable that JS will update

            }
        }

    } elseif (count($CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
        'chainpostinput' => $i['postid'],
        'chainuserinput IN (' . join(',', $CI->config->item('userids___7712')) . ')' => null, //Reply
    )))) {

        //Find the created post if any:
        $user_private_replies = $CI->Chains->read(array(
            'chainusertype' => 1734047,
            'chainpostoutput' => $i['postid'],
            'chainusercreator' => $session_user,
        ), array('chainpostinput'), 0, 0, array('chainid' => 'DESC'));

        $input_attributes = '';
        $previous_response = ($session_user && isset($user_private_replies[0]['postmessageraw']) ? $user_private_replies[0]['postmessageraw'] : '');

        if (count($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostinput' => $i['postid'],
            'chainuserinput IN (' . join(',', $CI->config->item('userids___43002')) . ')' => null,
        )))) {

            //Textarea
            $users___12273 = $CI->config->item('users___12273'); //POST Cache
            $input_ui .= '<textarea class="border dotted-borders x_write algolia_finder algolia__i algolia__e" placeholder="' . (strlen($users___12273[4736]['m__message']) ? $users___12273[4736]['m__message'] : $users___12273[4736]['m__name']) . '">' . $previous_response . '</textarea>';
            $input_ui .= '<script> $(document).ready(function () { set_autosize($(\'.x_write\')); }); </script>';

        } else {

            foreach ($CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $i['postid'],
                'chainuserinput IN (' . join(',', $CI->config->item('userids___43003')) . ')' => null,
            )) as $input_field) {

                if ($input_field['chainuserinput'] == 31794) {

                    //Number
                    if (count($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostinput' => $i['postid'],
                        'chainuserinput' => 42181, //Phone
                    )))) {
                        //It's a phone number:
                        $input_type = 'text';
                        $placeholder = 'Enter Phone Number';
                    } else {
                        //A regular number:
                        $input_type = 'number';
                        $placeholder = 'Enter Number';
                    }

                    //Steps
                    foreach ($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostinput' => $i['postid'],
                        'chainuserinput' => 31813, //Steps
                    )) as $num_posts) {
                        if (strlen($num_posts['chainvalue']) && is_numeric($num_posts['chainvalue'])) {
                            $input_attributes .= ' step="' . $num_posts['chainvalue'] . '" ';
                        }
                    }

                    //Min Value
                    foreach ($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostinput' => $i['postid'],
                        'chainuserinput' => 31800, //Min Value
                    )) as $num_posts) {
                        if (strlen($num_posts['chainvalue']) && is_numeric($num_posts['chainvalue'])) {
                            $input_attributes .= ' min="' . $num_posts['chainvalue'] . '" ';
                        }
                    }

                    //Max Value
                    foreach ($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostinput' => $i['postid'],
                        'chainuserinput' => 31801, //Max Value
                    )) as $num_posts) {
                        if (strlen($num_posts['chainvalue']) && is_numeric($num_posts['chainvalue'])) {
                            $input_attributes .= ' max="' . $num_posts['chainvalue'] . '" ';
                        }
                    }

                } elseif ($input_field['chainuserinput'] == 30350) {

                    $has_time = count($CI->Chains->read(array(
                        'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                        'chainpostinput' => $i['postid'],
                        'chainuserinput' => 32442, //Select Time
                    )));

                    $input_type = ($has_time ? 'datetime-local' : 'date');
                    $placeholder = ($has_time ? 'Select Date & Time' : 'Select Date');

                } elseif ($input_field['chainuserinput'] == 42915) {

                    //URL
                    $input_type = 'url';
                    $placeholder = 'Paste URL';

                }

                $input_ui .= '<input type="' . $input_type . '" ' . $input_attributes . ' class="border dotted-borders x_write" placeholder="' . $placeholder . '" value="' . $previous_response . '" />';

            }
        }

        //Uploader
        if (count($CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
            'chainpostinput' => $i['postid'],
            'chainuserinput IN (' . join(',', $CI->config->item('userids___43004')) . ')' => null,
        )))) {
            foreach ($user_private_replies as $x_response) {
                $input_ui .= '<div class="hidden">' . post_view(31777, $x_response) . '</div>';
            }
        }

    }

    //Display Post media:
    /*
    $ui .= '<div class="post_preview_frame hideIfEmpty">
                    <div id="media_outer_' . $i['postid'] . '" class="media_frame_' . $i['postid'] . ' hideIfEmpty"></div>
                    <div class="doclear">&nbsp;</div>
                </div>';
    $ui .= '<div style="padding:3px 0;"><div class="btn btn-black inner_uploader_' . $i['postid'] . '"><span class="icon-block-sm">' . $users___11035[7637]['m__cover'] . '</span>' . $users___11035[7637]['m__name'] . '</div></div>';

    $ui .= '<script> $(document).ready(function () { load_cloudinary(43004, ' . $i['postid'] . ', [\'#' . $i['postid'] . '\'], \'.inner_uploader_' . $i['postid'] . '\'); setTimeout(function () { display_media(\'media_outer_' . $i['postid'] . '\', 43004, ' . $i['postid'] . '); }, 144); }); </script>';
    */

    if (strlen($input_ui)) {
        $ui .= '<div class="ignore-click input_ui input_ui_' . $i['postid'] . '">' . $input_ui . '</div>';
    }

    //Bottom Bar
    $bottom_menu_ui = '';


    foreach ($CI->config->item('users___44257') as $chainusertype_target_bar => $m_target_bar) {

        //See if missing superpower?
        $superpowers_required = array_intersect($CI->config->item('userids___10957'), $m_target_bar['m__following']);
        if (count($superpowers_required) && (!user_session(end($superpowers_required)) || $is_cache)) {
            continue;
        }

        //Determine hover state:
        if ($chainusertype_target_bar == 4235 && (!$discovery_mode && $post_startable && $post_access >= 1)) {

            //Start
            $bottom_menu_ui .= '<span><a href="' . view_memory(42903, 30795) . $i['posthashtag'] . '/' . view_memory(6404, 4235) . '" class="btn btn-sm btn-black"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . $m_target_bar['m__name'] . '</a></span>';

        } elseif ($chainusertype_target_bar == 42924 && $discovery_mode && $focus__node) {

            //Next
            $users___31777 = $CI->config->item('users___31777');
            $focus_menu = ($was_discovered || !isset($users___31777[4559]) ? $m_target_bar : $users___31777[4559]);
            $bottom_menu_ui .= '<span><a href="javascript:void(0);" onclick="post_discovered(0)" class="btn btn-sm post_button discovered_btn"><span class="icon-block-sm">' . $focus_menu['m__cover'] . '</span>' . $focus_menu['m__name'] . '</a></span>';

        } elseif ($chainusertype_target_bar == 31022 && $discovery_mode && $focus__node && $user_session && !count($x_completes) && !count($CI->Chains->read(array(
                'chainusertype IN (' . join(',', $CI->config->item('userids___42991')) . ')' => null, //Active Writes
                'chainpostinput' => $i['postid'],
                'chainuserinput IN (' . join(',', $CI->config->item('userids___43009')) . ')' => null,
            ))) && !post_required($i)) {

            //Skip
            $bottom_menu_ui .= '<span class="mini_button" style="max-width: 75px;"><a href="javascript:void(0);" onclick="post_discovered(1)" class="btn btn-sm"><span class="icon-block-sm">' . $m_target_bar['m__cover'] . '</span>' . $m_target_bar['m__name'] . '</a></span>';

        }
    }


    //Bottom Bar menu
    if (!$focus__node && !$is_locked && !$is_cache) {
        foreach ($CI->config->item('users___' . ($discovery_mode ? 42877 : 31890)) as $userid_bottom_bar => $m_bottom_bar) {

            $superpowers_required = array_intersect($CI->config->item('userids___10957'), $m_bottom_bar['m__following']);
            if (count($superpowers_required) && !user_session(end($superpowers_required))) {
                continue;
            }

            $coins_ui = posts_query($userid_bottom_bar, $i['postid'], 0, true, $headline_authors);
            if (strlen($coins_ui)) {
                $bottom_menu_ui .= '<span class="hideIfEmpty">';
                $bottom_menu_ui .= $coins_ui;
                $bottom_menu_ui .= '</span>';
            }
        }
    }


    if ($bottom_menu_ui) {
        $ui .= '<div class="' . ($focus__node && $discovery_mode ? ' container fixed-bottom hidden ' : '') . '">';
        $ui .= '<div class="card_cards">';
        $ui .= $bottom_menu_ui;
        $ui .= '</div>';
        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}

function view_random_title()
{
    $usercover_generator = usercover_generator(12279);
    return random_adjective() . str_replace('Badger Honey', 'Honey Badger', str_replace('Black Widow', '', ucwords(str_replace('-', ' ', one_two_explode('fa-', ' ', $usercover_generator)))));
}

function view_list_user($postid, $plain_no_html = false)
{

    if($postid<1){
        return false;
    }
    $CI =& get_instance();
    $message_append = '';

    //Define Order:
    $users___42421 = $CI->config->item('users___42421');
    $order_columns = array();
    foreach ($users___42421 as $sort_id => $sort) {
        $order_columns['chainuserinput = \'' . $sort_id . '\' DESC'] = null;
    }

    //Query Relevant Users:
    foreach ($CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___33602')) . ')' => null, //Writer Chains Active
        'chainpostinput' => $postid,
        'chainuserinput IN (' . join(',', $CI->config->item('userids___42421')) . ')' => null, //Featured Inputs
    ), array('chainuserinput'), 0, 0, $order_columns) as $x) {

        //Format data if needed:
        $x['chainvalue'] = data_type_format($x['chainuserinput'], $x['chainvalue']);

        $message_append .= '<div class="user-info">'
            . '<span class="icon-block">' . $users___42421[$x['chainuserinput']]['m__cover'] . '</span>' . $users___42421[$x['chainuserinput']]['m__name'] . (strlen($x['chainvalue']) ? ':' : '')
            . (strlen($x['chainvalue']) ? '<div class="user_info_box"><div class="sub_note main__title">' . (!$plain_no_html ? nl2br(view_url($x['chainvalue'])) : $x['chainvalue']) . '</div></div>' : '')
            . '</div>';

    }

    return (strlen($message_append) ? ($plain_no_html ? $message_append : '<div class="user-featured">' . $message_append . '</div>') : false);

}


function view_pill($focus__node, $chainusertype, $counter, $m, $ui = null, $is_open = true)
{

    return '<script> $(\'.nav-tabs\').append(\'<li class="nav-item thepill' . $chainusertype . '"><a class="nav-chain" chainusertype="' . $chainusertype . '" href="#' . $m['m__handle'] . '" data-toggle="tooltip" data-placement="top" title="' . number_format($counter, 0) . ' ' . $m['m__name'] . (strlen($m['m__message']) ? ': ' . str_replace('\'', '', str_replace('"', '', $m['m__message'])) : '') . '"><span class="icon-block-xs">' . $m['m__cover'] . '</span><span class="main__title hideIfEmpty xtypecounter' . $chainusertype . '">' . view_number($counter) . '</span></a></li>\') </script>' .
        '<div class="headlinebody pillbody hidden headline_body_' . $chainusertype . '" read-counter="' . $counter . '">' . $ui . '</div>';

}

function user_validate($userid, $chainid = 0, $delete_missing = false)
{
    $userid = intval($userid);
    $chainid = intval($chainid);
    $CI =& get_instance();
    $foundchain = count($CI->Chains->read(array(
        'chainusertype' => 12274,
        'chainuserinput' => $userid,
    ), array(), 1));

    $es = $CI->Users->read(array(
        'userid' => $userid,
    ));
    $foundcache = count($es);

    $active_filter = array(
        '(chainuserdomain=' . $userid . ' OR chainusertype=' . $userid . ' OR chainusercreator=' . $userid . ' OR chainuserinput=' . $userid . ' OR chainuseroutput=' . $userid . ')' => null,
    );
    if (!$delete_missing) {
        $active_filter['chainid !='] = $chainid;
    }
    $activechains = count($CI->Chains->read($active_filter, array(), 0));

    $status = ($foundchain && $foundcache && $activechains ? 1 : 0);
    $chain_deletes = 0;
    if ($delete_missing && !$status) {
        foreach ($CI->Chains->read(array(
            '(chainuserdomain=' . $userid . ' OR chainusertype=' . $userid . ' OR chainusercreator=' . $userid . ' OR chainuserinput=' . $userid . ' OR chainuseroutput=' . $userid . ')' => null,
        ), array(), 0) as $del) {
            $CI->db->query("DELETE FROM ideachains WHERE chainid = " . $del['chainid'] . ";");
            $chain_deletes++;
        }
    }

    return array(
        'status' => $status,
        'userid' => $userid,
        'chainid' => $chainid,
        'foundchain' => $foundchain,
        'cacheuserhandle' => ($foundcache ? '@' . $es[0]['userhandle'] : false),
        'activechains' => $activechains,
        'chain_deletes' => $chain_deletes,
    );
}

function user_view($chainusertype, $e, $extra_class = null, $extra_value = null)
{

    $CI =& get_instance();

    if (!isset($e['userid']) || !isset($e['username'])) {
        log_error('user_view() Missing core variables', array(
            'chainuseroutput' => $chainusertype,
        ));
        return 'Missing core variables';
    }

    $chainid = (isset($e['chainid']) ? $e['chainid'] : 0);
    $is_cache = in_array($chainusertype, $CI->config->item('userids___14599'));
    $user_access = ($is_cache ? 1 : user_access($e['userhandle'], 0, $e));
    $superpower_10939 = (!$is_cache && user_session(10939));
    $user_session = (!$is_cache ? user_session() : false);
    $usercreator = ( isset($user_session['userid']) ? $user_session['userid'] : 14068 /* GUEST */);
    $users___11035 = $CI->config->item('users___11035'); //Encyclopedia
    $focus__node = in_array($chainusertype, $CI->config->item('userids___12149')); //NODE COIN
    $is_app = $chainusertype == 6287;
    $href = ($is_app ? view_app_chain($e['userid']) : view_memory(42903, 42902) . $e['userhandle']);
    $cover_is_image = filter_var($e['usercover'], FILTER_VALIDATE_URL);
    $has_sortable = $chainid > 0 && $user_access >= 3 && in_array($chainusertype, $CI->config->item('userids___13911'));
    $users___4593 = $CI->config->item('users___4593');


    //Log preview view:
    if($e['userid']!=$usercreator && !$is_cache){
        $CI->Chains->create(array(
            'chainusertype' => ( $focus__node ? 44176 /* View User */ : 3459270 /* List User */ ),
            'chainusercreator' => $usercreator,
            'chainuserinput' => $e['userid'],
            'chainuseroutput' => $usercreator,
        ));
    }


    //User UI
    $ui = '<div userid="' . $e['userid'] . '" userlogin="' . $e['userhandle'] . '" ' . (isset($e['chainid']) ? ' chainid="' . $e['chainid'] . '" ' : '') . ' href="' . $href . '" class="card_cover carduser_cover no-padding card-12274 s__12274_' . $e['userid'] . ' ' . $extra_class . ($is_app ? ' card-6287 ' : '') . ($has_sortable ? ' sort_draggable ' : '') . ($focus__node ? ' focus-cover slim_flat col-md-8 col-sm-10 col-12 ' : ' edge-cover col-sm-4 col-6 ' . (strlen($href) ? ' card_click ' : '')) . (isset($e['chainid']) ? ' cover_x_' . $e['chainid'] . ' ' : '') . '">';

    $ui .= '<div class="cover-wrapper">';

    //Coin Cover
    $ui .= (!$focus__node ? '<a href="' . $href . '"' : '<div') . ' class="user_hrefuser_' . $e['userid'] . ' coinType12274 ' . ($user_access >= 3 ? '' : ' ready-only ') . ' black-background-obs cover-chain" ' . ($cover_is_image ? 'style="background-image:url(\'' . $e['usercover'] . '\');"' : '') . '>';
    $ui .= '<div class="cover-btn ui_usercover_' . $e['userid'] . '" raw_cover="' . $e['usercover'] . '">' . (!$cover_is_image && $e['usercover'] ? view_cover($e['usercover'], true) : '') . '</div>';
    $ui .= (!$focus__node ? '</a>' : '</div>');

    $ui .= '</div>';


    //Title Cover
    $ui .= '<div class="cover-content">';
    $ui .= '<div class="inner-content">';


    if ($user_access >= 3) {
        //Editable:
        $ui .= view_user_input(6197, $e['username'], $e['userid'], $user_access, (isset($e['chainkey']) ? ($e['chainkey'] * 100) + 1 : 0), true);
        $ui .= '<div class="hidden usertitle_' . $e['userid'] . '">' . $e['username'] . '</div>';
    } else {
        //Static:
        $ui .= '<input type="hidden" class="usertitle_' . $e['userid'] . '" value="' . $e['username'] . '">';
        $ui .= '<div class="center">';
        $ui .= '<span class="main__title usertitle_' . $e['userid'] . '">' . $e['username'] . '</span>';
        $ui .= '</div>';
    }


    //User Handle
    $ui .= '<div class="center-block">';

    $ui .= '<div class="creator_headline grey"><span class="ignore-click ui_userhandle_' . $e['userid'] . '" title="ID ' . $e['userid'] . '">'.( isset($e['chainusertype']) && $users___4593[$e['chainusertype']]['m__cover']!='@' ? ( substr_count($users___4593[$e['chainusertype']]['m__cover'], '@') ? '<b class="underdot" title="'.$users___4593[$e['chainusertype']]['m__name'].'">'.$users___4593[$e['chainusertype']]['m__cover'].'</b>' : '<span title="'.$users___4593[$e['chainusertype']]['m__name'].'">'.$users___4593[$e['chainusertype']]['m__cover'].'</span> @' ) : '@' ) . $e['userhandle'] . '</span>'.( $chainid ? '<span class="grey mini-font mini-padded mini-frame mini_time" title="' . date("Y-m-d H:i:s", strtotime($e['chaintime'])) . ' PST">' . view_time_difference($e['chaintime'], true) . '</span>' : '' ).( !$focus__node && strlen($e['userbio']) ? '<span class="icon-block-sm" data-toggle="tooltip" data-placement="top" title="' . $e['userbio'] . '"><i class="fas fa-info-square"></i></span>' : '' ).'</div>';

    $ui .= '<div class="'.( !$focus__node ? 'hidden' : '' ).'"><div class="creator_headline grey hideIfEmpty userbio_' . $e['userid'] . '" style="display:block !important;">' . $e['userbio'] . '</div></div>';

    //User Location?
    $users___42777 = $CI->config->item('users___42777');
    $order_columns = array();
    foreach ($users___42777 as $sort_id => $sort) {
        $order_columns['chainusertype = \'' . $sort_id . '\' DESC'] = null;
    }
    foreach ($CI->Chains->read(array(
        'chainusertype IN (' . join(',', $CI->config->item('userids___42777')) . ')' => null, //Featured Profile
        'chainuseroutput' => $e['userid'],
    ), array('chainuserinput'), 0, 0, $order_columns) as $location) {
        $ui .= view_featured_chains($location['chainusertype'], $location, $users___42777[$location['chainusertype']], $focus__node);
    }


    if ($is_app && isset($e['chainvalue']) && strlen($e['chainvalue']) && !$is_cache && $superpower_10939) {
        $ui .= '<span class="icon-block" data-toggle="tooltip" data-placement="top" title="' . $e['chainvalue'] . '"><i class="far fa-info-circle"></i></span>';
    } else if ($chainid && $user_access >= 3 && !$is_cache && $superpower_10939) {
        //Main description:
        $ui .= '<div class="chainvalue_headline grey hideIfEmpty ignore-click ui_chainvalue_' . $chainid . '"><span class="hideIfEmpty">' . htmlentities($e['chainvalue']) . '</span></div>';
    }

    if ($extra_value) {
        $ui .= '<div class="chainvalue_headline grey hideIfEmpty ignore-click"><span class="hideIfEmpty">' . $extra_value . '</span></div>';
    }

    $ui .= '</div>';


    //Start with Chain Note
    $featured_users = '';


    //Featured Users?
    $bio = null;
    $users___14036 = $CI->config->item('users___14036');
    $order_columns = array();
    foreach ($users___14036 as $sort_id => $sort) {
        $order_columns['chainuserinput = \'' . $sort_id . '\' DESC'] = null;
    }
    foreach ($CI->Chains->read(array(
        'chainuserinput IN (' . join(',', $CI->config->item('userids___14036')) . ')' => null, //Featured Users
        'chainuseroutput' => $e['userid'],
        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
    ), array(), 0, 0, $order_columns) as $social_chain) {

        if (in_array($social_chain['chainuserinput'], $CI->config->item('userids___32172'))) {
            /*
             *
             * Before showing this we must enture all information is updated
             *
            if (strlen($social_chain['chainvalue'])) {
                //Must always see, show content here:
                $bio .= '<div class="user_bio grey center">' . $social_chain['chainvalue'] . '</div>';
            }
            */
            continue;
        }

        //Determine chain type:
        $social_url = false;

        if (in_array(32097, $users___14036[$social_chain['chainuserinput']]['m__following'])) {
            $social_url = 'href="mailto:' . $social_chain['chainvalue'] . '"';
        } elseif (in_array(42181, $users___14036[$social_chain['chainuserinput']]['m__following'])) {
            //Phone Number
            $social_url = 'href="' . phone_href($social_chain['chainuserinput'], $social_chain['chainvalue']) . '"';
        }

        $info = htmlentities(str_replace('"','',str_replace('\'','',strlen($social_chain['chainvalue']) && !$social_url ? $users___14036[$social_chain['chainuserinput']]['m__name'] . ': ' . $social_chain['chainvalue'] : ($social_url ? view_url_clean(one_two_explode('href="', '"', $social_url)) : $users___14036[$social_chain['chainuserinput']]['m__name']))));

        //Append to chains:
        $featured_users .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">' . ($social_url && $focus__node ? '<a ' . $social_url . ' data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $users___14036[$social_chain['chainuserinput']]['m__cover'] . '</a>' : ($focus__node ? '<a href="' . (filter_var($social_chain['chainvalue'], FILTER_VALIDATE_URL) ? $social_chain['chainvalue'] : view_memory(42903, 42902) . $users___14036[$social_chain['chainuserinput']]['m__handle']) . '" target="_blank" data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $users___14036[$social_chain['chainuserinput']]['m__cover'] . '</a>' : '<span data-toggle="tooltip" data-placement="top" title="' . $info . '">' . $users___14036[$social_chain['chainuserinput']]['m__cover'] . '</span>')) . '</span>';

    }


    //Start with top bar:
    if (!$is_app && !$is_cache && $user_access >= 1) {

        //User Chain Groups
        $chainusertype_id = 0;
        $chainusertype_ui = '';
        if ($chainid) {
            foreach ($CI->config->item('users___31770') as $chainusertype1 => $m1) {
                if (in_array($e['chainusertype'], $CI->config->item('userids___' . $chainusertype1))) {
                    $chainusertype_id = $chainusertype1;
                    break;
                }
            }
        }

        //Top Bar
        foreach ($CI->config->item('users___31963') as $chainusertype_target_bar => $m_target_bar) {

            //See if missing superpower?
            $superpowers_required = array_intersect($CI->config->item('userids___10957'), $m_target_bar['m__following']);
            if (count($superpowers_required) && !user_session(end($superpowers_required))) {
                continue;
            }

            if ($chainusertype_target_bar == 31770 && $chainid && $superpower_10939) {

                $featured_users .= $chainusertype_ui;

            } elseif ($chainusertype_target_bar == 41037 && $user_access >= 3 && !$focus__node) {

                //Selector
                $featured_users .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' ignore-click">';
                $featured_users .= '<input class="form-check-input" type="checkbox" value="" userid="' . $e['userid'] . '" id="selectoruser_' . $e['userid'] . '" aria-label="">';
                $featured_users .= '</span>';

            } elseif ($chainusertype_target_bar == 13911 && $has_sortable && $user_access >= 3) {

                //Sort User
                $featured_users .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . ' sortuser_frame hidden">';
                $featured_users .= '<span title="' . $m_target_bar['m__name'] . '" class="sortuser_grab">' . $m_target_bar['m__cover'] . '</span>';
                $featured_users .= '</span>';

            } elseif ($chainusertype_target_bar == 14980 && $user_access >= 3) {

                $action_buttons = null;

                if (!$chainid) {
                    $focus_dropdown = 12887; //User Dropdown
                } elseif ($chainusertype_id == 32292) { //User/User Chains
                    $focus_dropdown = 14956; //User/User Dropdown
                } elseif ($chainusertype_id == 31777) { //Discoveries
                    $focus_dropdown = 32070; //User>Discoveries Dropdown
                } elseif ($chainusertype_id == 13550) { //Post/User Chains
                    $focus_dropdown = 28792; //User/Post Dropdown
                } else {
                    $focus_dropdown = 0;
                }

                if ($focus_dropdown > 0 && is_array($CI->config->item('users___' . $focus_dropdown))) {
                    foreach ($CI->config->item('users___' . $focus_dropdown) as $userid_dropdown => $m_dropdown) {

                        //Skip if missing superpower:
                        $superpowers_required = array_intersect($CI->config->item('userids___10957'), $m_dropdown['m__following']);
                        if (count($superpowers_required) && !user_session(end($superpowers_required))) {
                            continue;
                        }

                        $anchor = '<span class="icon-block">' . $m_dropdown['m__cover'] . '</span>' . $m_dropdown['m__name'];


                        if ($userid_dropdown == 4997) {

                            $action_buttons .= '<a href="javascript:void(0);" onclick="chain_preview(4997,' . $e['userid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($userid_dropdown == 31912 && $user_access >= 3) {

                            //Edit User
                            $action_buttons .= '<a href="javascript:void(0);" onclick="user_editor(' . $e['userid'] . ',' . $chainid . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($userid_dropdown == 29771 && $user_access >= 3) {

                            //Clone:
                            $action_buttons .= '<a href="javascript:void(0);" onclick="user_copy(' . $e['userid'] . ')" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($userid_dropdown == 10673 && $chainid > 0 && $user_access >= 3 && $superpower_10939) {

                            //UNCHAIN
                            $action_buttons .= '<a href="javascript:void(0);" onclick="chain_delete(' . $chainid . ', ' . $e['chainusertype'] . ')" class="dropdown-item main__title">' . $anchor . '</span></a>';

                        } elseif ($userid_dropdown == 42649 && $user_access >= 3) {

                            //Delete User
                            $action_buttons .= '<li><hr class="dropdown-divider"></li>';
                            $action_buttons .= '<a href="javascript:void();" onclick="user_delete(' . $e['userid'] . ', ' . $chainid . ', 0)" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif ($userid_dropdown == 13007 && $user_access >= 3) {

                            //Reset Alphabetic order
                            $action_buttons .= '<a href="javascript:void(0);" onclick="chain_sort_reset()" class="dropdown-item main__title">' . $anchor . '</a>';

                        } elseif (in_array($userid_dropdown, $CI->config->item('userids___6287')) && $user_access >= 3) {

                            //Standard button
                            $action_buttons .= '<a href="' . view_app_chain($userid_dropdown) . view_memory(42903, 42902) . $e['userhandle'] . '" class="dropdown-item main__title">' . $anchor . '</a>';

                        }
                    }
                }

                //Any items found?
                if ($action_buttons && $focus_dropdown > 0) {
                    //Right Action Menu
                    $users___14980 = $CI->config->item('users___14980'); //Dropdowns

                    $featured_users .= '<span class="' . ($focus__node ? 'icon-block-sm' : 'icon-block-xs') . '">';
                    $featured_users .= '<div class="dropdown inline-block">';
                    $featured_users .= '<button type="button" class="btn no-left-padding no-right-padding" id="action_menuuser_' . $e['userid'] . '" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="' . $users___14980[$focus_dropdown]['m__name'] . '">' . $users___14980[$focus_dropdown]['m__cover'] . '</button>';
                    $featured_users .= '<div class="dropdown-menu" aria-labelledby="action_menuuser_' . $e['userid'] . '">';
                    $featured_users .= $action_buttons;
                    $featured_users .= '</div>';
                    $featured_users .= '</div>';
                    $featured_users .= '</span>';
                }
            }
        }
    }


    $ui .= $bio;

    if ($focus__node) {
        $ui .= '<div class="center-block">';
        $ui .= $featured_users;
        $ui .= '</div>';
    }


    $ui .= '</div>';
    $ui .= '</div>';


    //Bottom Bar
    if (!$is_app && $user_access >= 1) {

        $ui .= '<div class="card_cards hideIfEmpty">';

        if (!$focus__node) {

            $ui .= $featured_users;

            //Also Append bottom bar / main menu:
            foreach ($CI->config->item('users___31916') as $userid_bottom_bar => $m_bottom_bar) {
                $superpowers_required = array_intersect($CI->config->item('userids___10957'), $m_bottom_bar['m__following']);
                if (count($superpowers_required) && !user_session(end($superpowers_required))) {
                    continue;
                }

                $ui .= '<span class="hideIfEmpty">';
                $ui .= users_query($userid_bottom_bar, $e['userid']);
                $ui .= '</span>';
            }
        }

        $ui .= '</div>';
    }


    $ui .= '</div>';

    return $ui;

}


function view_user_input($cache_userid, $current_value, $s__id, $post_access, $tabindex = 0, $extra_large = false)
{

    $CI =& get_instance();
    $users___12112 = $CI->config->item('users___12112');
    $current_value = htmlentities($current_value);
    $name = 'input' . substr(md5($cache_userid . $current_value . $s__id . $post_access . $tabindex), 0, 8);

    //Define element attributes:
    $attributes = ($post_access >= 3 ? '' : 'disabled') . ' spellcheck="false" tabindex="' . $tabindex . '" old-value="' . $current_value . '" id="input_' . $cache_userid . '_' . $s__id . '" class="form-control 
     inline-block editing-mode x_set_class_text text__' . $cache_userid . '_' . $s__id . ($extra_large ? ' texttype_lg ' : ' texttype_sm ') . ' textuser_' . $cache_userid . '" cache_userid="' . $cache_userid . '" userid="' . $s__id . '" ';

    //Also Append Counter to the end?
    if ($extra_large) {

        $focus_element = '<textarea name="' . $name . '" placeholder="' . $users___12112[$cache_userid]['m__name'] . '" ' . $attributes . '>' . $current_value . '</textarea>';

    } else {

        $focus_element = '<input type="text" name="' . $name . '" data-lpignore="true" placeholder="__" value="' . $current_value . '" ' . $attributes . ' />';

    }

    return '<span class="span__' . $cache_userid . ' ' . (!($post_access >= 3) ? ' edit-locked ' : '') . '">' . $focus_element . '</span>';

}


function view_json($array)
{
    if (!headers_sent()) {
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

function search($count, $has_e = 0)
{
    //A cute little function to either display the plural "s" or not based on $count
    return (intval($count) == 1 ? '' : ($has_e ? 'es' : 's'));
}


function view_featured_chains($chainusertype, $location, $m = null, $focus__node)
{
    $CI =& get_instance();
    $users___11035 = $CI->config->item('users___11035'); //Encyclopedia
    return '<div class="creator_headline" ' . (is_array($m) ? ' data-toggle="tooltip" data-placement="top" title="' . $m['m__name'] . (strlen($m['m__message']) ? ': ' . $m['m__message'] : ' @' . $location['userhandle']) . (strlen($location['chainvalue']) ? ': ' . $location['chainvalue'] : '') . '" ' : '') . '>' . ($focus__node ? '<a href="' . view_memory(42903, 42902) . $location['userhandle'] . '">' : '') . '<span class="grey ' . ($chainusertype == 41949 ? 'icon-block' : 'icon-block-xs') . '">' . $users___11035[$chainusertype]['m__cover'] . '</span><span class="grey mini-frame ' . ($chainusertype == 41949 ? 'mini-font' : '') . '">' . $location['username'] . '</span>' . ($focus__node ? '</a>' : '') . '</div>';
}


function view_post_nav($discovery_mode, $focus_post, $autoload = true)
{

    $CI =& get_instance();
    $coins_count = array();
    $body_content = '';
    $user_session = user_session();
    $posttion_pen = user_session(10939);

    $ui = '';
    $ui .= '<ul class="nav nav-tabs nav12273 nav__' . $focus_post['postid'] . ' hideIfEmpty">';
    foreach ($CI->config->item('users___' . ($discovery_mode ? 42877 : 31890)) as $chainusertype => $m) {

        $superpowers_required = array_intersect($CI->config->item('userids___10957'), $m['m__following']);
        if (count($superpowers_required) && !user_session(end($superpowers_required))) {
            continue;
        }

        $coins_count[$chainusertype] = posts_query($chainusertype, $focus_post['postid'], 0, false);
        if (!$coins_count[$chainusertype] && $discovery_mode) {
            continue;
        }

        if (($user_session && in_array($chainusertype, $CI->config->item('userids___42945'))) || $coins_count[$chainusertype] > 0) {
            $body_content .= '<div class="headlinebody pillbody headline_body_' . $chainusertype . ' hidden" read-counter="' . $coins_count[$chainusertype] . '"><div class="tab_content"></div></div>';


            $ui .= '<li class="nav-item thepill' . $chainusertype . '"><a class="nav-chain user_nav_' . $m['m__handle'] . '" chainusertype="' . $chainusertype . '" href="#' . $m['m__handle'] . '" title="' . $coins_count[$chainusertype].' '.$m['m__name'] . '"><span class="icon-block">' . $m['m__cover'] . '</span><span class="hideIfEmpty xtypecounter' . $chainusertype . '">' . view_number($coins_count[$chainusertype]) . '</span><span class="hidden xtypetitle xtypetitle_' . $chainusertype . '">&nbsp;' . $m['m__name'] . '&nbsp;</span></a></li>';

        }

    }

    //Add any referenced apps:
    foreach ($CI->config->item('handlusers___6287') as $apphandle => $appid) {
        $users___6287 = $CI->config->item('users___6287'); //APP
        //TODO fix this as it would delete "@sheet123" same as "@sheet" and load the app...
        if (substr_count(strtolower($focus_post['postmessageraw']) . ' ', '@' . strtolower($apphandle) . ' ') || substr_count(strtolower($focus_post['postmessageraw']), '@' . strtolower($apphandle) . "\n")) {

            $body_content .= '<div class="headlinebody pillbody headline_body_' . $appid . ' hidden" read-counter="0"><div class="tab_content"></div></div>';


            $ui .= '<li class="nav-item thepill' . $appid . '"><a class="nav-chain user_nav_' . $users___6287[$appid]['m__handle'] . '" chainusertype="' . $appid . '" href="#' . $users___6287[$appid]['m__handle'] . '" title="' . $users___6287[$appid]['m__name'] . '">&nbsp;<span class="icon-block">' . $users___6287[$appid]['m__cover'] . '</span>&nbsp;<span class="hidden xtypetitle xtypetitle_' . $appid . '">' . $users___6287[$appid]['m__name'] . '&nbsp;</span></a></li>';
        }
    }

    $ui .= '</ul>';
    $ui .= $body_content;


    if ($autoload) {
        $users___focus = $CI->config->item('users___26005');
        $focus_tab = 0;
        foreach ($users___focus as $chainusertype => $m) {
            if (isset($coins_count[$chainusertype]) && $coins_count[$chainusertype] > 0) {
                $focus_tab = $chainusertype;
                $ui .= '<script> $(document).ready(function () { if(!document.location.hash) { load_post_menu(\'' . $m['m__handle'] . '\'); } }); </script>';
                break;
            }
        }
        if (!$focus_tab) {
            foreach ($users___focus as $chainusertype => $m) {
                $ui .= '<script> $(document).ready(function () { if(!document.location.hash) { load_post_menu(\'' . $m['m__handle'] . '\'); } }); </script>';
                break;
            }
        }
    }


    return $ui;

}


function nextchainid()
{
    $CI =& get_instance();
    foreach ($CI->Chains->read(array(), array(), 1, 0, array('chainid' => 'DESC'), 'chainid') as $bigchain) {
        return $bigchain['chainid'] + 1;
    }
    return 0;
}

// Function to get PayPal access token
function paypal_token($clientId, $clientSecret)
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
function paypal_invoice($accessToken, $invoiceData)
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


    if ($invoiceData['total_amount'] > 0) {
        $payload['detail']['payment_term'] = [
            'term_type' => 'DUE_ON_DATE_SPECIFIED',
            'due_date' => ($invoiceData['due_date'] ? $invoiceData['due_date'] : date('Y-m-d'))
        ];
        $payload['configuration']['partial_payment'] = [
            'allow_partial_payment' => ($invoiceData['min_payment'] > 0),
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
        return one_two_explode('/invoices/', '', $data['href']);
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

function data_type_example($dataid)
{
    $CI =& get_instance();
    $users___4592 = $CI->config->item('users___4592'); //Data Types
    foreach ($users___4592 as $userid => $m) {
        if (in_array($dataid, $CI->config->item('userids___' . $userid)) && strlen($users___4592[$userid]['m__message'])) {
            return ' ' . $users___4592[$userid]['m__message'];
        }
    }
    return '';
}

function post_index($postmessageraw, $save_postid = 0, $chainusercreator = 0, $current_term = null, $new_term = null)
{

    //Display Images, Audio, Video & PDF Files:
    //Analyze the message to find referencing URLs and Members in the message text:
    $CI =& get_instance();
    $core_references = array('@', '#');
    $post_index = array(
        'chainvalue' => '',
        'postmessageraw' => '',
        'postmessageraw_new' => '',
        'postmessageview' => '',
        'postmessageedit' => '',
        'referenced_posts' => array(),
        'referenced_users' => array(),
        'new_posts' => array(),
        'new_users' => array(),
        'actionstats' => array(
            'current' => 0,
            'added' => 0,
            'removed' => 0,
            'update_attempt' => 0,
            'no_change' => 0,
            'update_success' => 0,
            'posts_links_fixed' => 0,
        ),
    );


    //All the possible reference types that can be found:
    $post_references = array();
    $chainkey = 0;
    $postmessageraw = str_replace('	', ' ', $postmessageraw);

    //See what we can find:
    foreach (explode("\n", $postmessageraw) as $line_count => $line) {

        $first_ref_hidden = false;
        $first_line = !$line_count;
        $words = explode(' ', trim($line));
        $only_word_in_line = count($words) == 1;
        $second_word_onwards = null;

        $linechainvalue = null;
        $linepostmessageraw = null;
        $linepostmessageview = null;
        $linepostmessageedit = null;
        $line_new = '';

        foreach ($words as $word_count => $word_text) {

            $reference_type = 0;
            $first_word = !$word_count;
            if ($first_word && strlen($word_text . ' ') < strlen($line)) {
                $second_word_onwards .= @ltrim($line, $word_text . ' ');
            }
            $chainvalue = null;
            $postmessageraw = null;
            $postmessageview = null;
            $postmessageedit = null;
            $is_url = false;

            if (filter_var($word_text, FILTER_VALIDATE_URL)) {

                $is_url = true;
                //Generic URL, Try to find:
                $newUserTerm = null;
                foreach ($CI->Chains->read(array(
                    'chainvalue' => $word_text,
                    'chainuserinput' => 1326, //URL
                    'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                ), array('chainuseroutput'), 0) as $x) {
                    $newUserTerm = $x['userhandle'];
                    array_push($post_index['referenced_users'], intval($x['userid']));
                }

                if (!$newUserTerm) {
                    //Not found, create it:
                    $added_e = $CI->Users->create(array(
                        'username' => 'URL ' . random_string(8),
                    ));
                    if ($added_e['status']) {

                        //Chain:
                        $CI->Chains->create(array(
                            'chainusertype' => 4230, //Follow
                            'chainuserinput' => 1326, //URL
                            'chainuseroutput' => $added_e['user_create']['userid'],
                            'chainvalue' => $word_text,
                        ));

                        $newUserTerm = $added_e['user_create']['userhandle'];

                        array_push($post_index['new_users'], $newUserTerm);

                    }
                }

                //Replace Word:
                $word_text = '@' . $newUserTerm;

            }


            //Could be another reference, check:
            if (in_array(substr($word_text, 0, 1), $core_references) || in_array(substr($word_text, 1, 1), $core_references)) {

                foreach ($CI->config->item('users___1696899') as $chainusertype => $m) {

                    //Found a reference?
                    $term = substr($word_text, strlen($m['m__cover']));
                    if (!(substr($word_text, 0, strlen($m['m__cover'])) == $m['m__cover'] && ctype_alnum($term))) {
                        //No reference found:
                        continue;
                    }

                    if (in_array($chainusertype, $CI->config->item('userids___4486'))) {

                        //Any replacements?
                        if ($term == $current_term && ctype_alnum($new_term) && ctype_alnum($current_term)) {
                            $term = $new_term;
                            $word_text = $m['m__cover'] . $term;
                        }

                        //Post reference:
                        $found_posts = $CI->Posts->read(array(
                            'LOWER(posthashtag)' => strtolower($term),
                        ));


                        if (!count($found_posts)) {

                            array_push($post_index['new_posts'], $term);

                            if (is_numeric($term) && count($CI->Posts->read(array(
                                    'postid' => intval($term),
                                )))) {

                                foreach ($CI->Posts->read(array(
                                    'postid' => intval($term),
                                )) as $replace_num) {
                                    $term = $replace_num['posthashtag'];
                                    $word_text = $m['m__cover'] . $replace_num['posthashtag'];
                                    $post_index['actionstats']['posts_links_fixed']++;
                                    array_push($found_posts, $replace_num);
                                }

                            } else {

                                //New post not found, try to create:
                                $parent_term = (strlen($new_term) ? $new_term : $current_term);
                                if (!$parent_term && $save_postid > 0) {
                                    //Fetch the term using the ID:
                                    foreach ($CI->Posts->read(array(
                                        'postid' => $save_postid,
                                    )) as $i) {
                                        $parent_term = $i['posthashtag'];
                                    }
                                }

                                //Craete this referenced post since we could not find it:
                                $post_new = $CI->Posts->create(array(
                                    'posthashtag' => $term,
                                    'postmessageraw' => post_to_title($term, $parent_term),
                                ), $chainusercreator);

                                if (isset($post_new['post_create']['postid'])) {
                                    //Re-fetch newly created:
                                    $found_posts = $CI->Posts->read(array(
                                        'postid' => $post_new['post_create']['postid'],
                                    ));
                                }
                            }
                        } else {
                            array_push($post_index['referenced_posts'], intval($found_posts[0]['postid']));
                        }

                        foreach ($found_posts as $post) {

                            //Valid Post
                            $reference_type = $chainusertype;

                            $post_references[$chainkey] = array(
                                'chainusertype' => $chainusertype,
                                'chainuserinput' => 0,
                                'chainuseroutput' => 0,
                                'chainpostinput' => $save_postid,
                                'chainpostoutput' => intval($post['postid']),
                                'chainkey' => $chainkey,
                            );
                            $chainkey++;

                            $chainvalue = $m['m__cover'] . $post['postid'];
                            $postmessageraw = $word_text;
                            if (!(in_array(substr(trim($line), 0, 1), $core_references) || in_array(substr(trim($line), 1, 1), $core_references))) {
                                $postmessageview = '<a href="' . view_memory(42903, 33286) . $post['posthashtag'] . '">' . $word_text . '</a>';
                            } else {
                                $first_ref_hidden = true;
                            }
                            $postmessageedit = '<a href="' . view_memory(42903, 33286) . $post['posthashtag'] . '">' . $word_text . '</a>';

                        }

                    } else {

                        if ($term == $current_term && ctype_alnum($new_term) && ctype_alnum($current_term)) {
                            $term = $new_term;
                            $word_text = $m['m__cover'] . $term;
                        }

                        if (is_numeric(trim($term))) {
                            $filter = array(
                                'userid' => intval(trim($term)),
                            );
                        } else {
                            $filter = array(
                                'LOWER(userhandle)' => strtolower($term),
                            );
                        }

                        $users = $CI->Users->read($filter);

                        //User Reference
                        if(count($users)){

                            if(!$is_url){
                                array_push($post_index['referenced_users'], intval($users[0]['userid']));
                            }
                            foreach ($users as $user) {

                                if (is_numeric($term)) {
                                    //Replace Word:
                                    $term = $user['userhandle'];
                                    $word_text = $m['m__cover'] . $user['userhandle'];
                                }

                                $media_append_end = false;

                                if ($chainusertype == 31835) {

                                    //This is the main @User reference

                                    $media_attachments = array();

                                    foreach ($CI->Chains->read(array(
                                        'chainuserinput IN (' . join(',', $CI->config->item('userids___1735577')) . ')' => null, //USER DISPLAY
                                        'chainuseroutput' => $user['userid'],
                                        'chainusertype IN (' . join(',', $CI->config->item('userids___13548')) . ')' => null, //USER CHAINS
                                    ), array(), 0) as $x) {
                                        if ($x['chainuserinput'] == 1326) {

                                            //URL
                                            array_push($media_attachments, '<a href="' . $x['chainvalue'] . '" target="_blank">' . $x['chainvalue'] . '</a>');

                                        } elseif ($x['chainuserinput'] == 4258) {

                                            //Video
                                            array_push($media_attachments, '<video id="video_user_' . $x['chainvalue'] . '" controls class="cld-video-user cld-fluid cld-video-user-skin-light" poster="' . $user['usercover'] . '"></video><script> play_video(\'' . $x['chainvalue'] . '\'); </script>');

                                        } elseif ($x['chainuserinput'] == 4259) {

                                            //Audio
                                            array_push($media_attachments, '<audio controls src="' . $x['chainvalue'] . '"></audio>');

                                        } elseif ($x['chainuserinput'] == 4260) {

                                            //Image
                                            array_push($media_attachments, '<img src="' . $x['chainvalue'] . '" />');

                                        }
                                    }

                                    if (count($media_attachments)) {
                                        //Replace the Entity:
                                        $media_append_end = '<div class="media_append">' . join(' ', $media_attachments) . '</div>';
                                    }
                                }

                                //Valid User
                                $reference_type = $chainusertype;
                                $post_references[$chainkey] = array(
                                    'chainusertype' => $chainusertype,
                                    'chainuserinput' => intval($user['userid']),
                                    'chainuseroutput' => 0,
                                    'chainpostinput' => $save_postid,
                                    'chainvalue' => ($first_word && strlen($second_word_onwards) ? trim($second_word_onwards) : null),
                                    'chainkey' => $chainkey,
                                );
                                $chainkey++;

                                $chainvalue = $m['m__cover'] . $user['userid'];
                                $postmessageraw = $word_text;
                                if (!(in_array(substr(trim($line), 0, 1), $core_references) || in_array(substr(trim($line), 1, 1), $core_references)) && !(isset($media_attachments) && count($media_attachments) == 1 && $x['chainuserinput'] == 1326)) {
                                    $postmessageview = '<a href="' . view_memory(42903, 42902) . $user['userhandle'] . '">' . $word_text . '</a>' . $media_append_end;
                                } else {
                                    $first_ref_hidden = true;
                                    if ($media_append_end) {
                                        $postmessageview = $media_append_end;
                                    }
                                }
                                $postmessageedit = '<a href="' . view_memory(42903, 42902) . $user['userhandle'] . '">' . $word_text . '</a>' . $media_append_end;

                            }
                        } else {
                            if(!$is_url){
                                array_push($post_index['new_users'], $term);
                            }
                        }

                    }

                    //We found a match:
                    break;

                }

            }

            if (!$reference_type) {
                //This word is not referencing anything!
                $chainvalue = $word_text;
                $postmessageraw = $word_text;
                if (!$first_ref_hidden) {
                    $postmessageview = $word_text;
                }
                $postmessageedit = $word_text;
            }

            //See what we found to add:
            $linechainvalue .= (!$first_word && $chainvalue ? ' ' : '') . $chainvalue;
            $linepostmessageraw .= (!$first_word && $postmessageraw ? ' ' : '') . $postmessageraw;
            $linepostmessageview .= (!$first_word && $postmessageview ? ' ' : '') . $postmessageview;
            $linepostmessageedit .= (!$first_word && $postmessageedit ? ' ' : '') . $postmessageedit;
            $line_new .= $word_text . " ";

        }

        $post_index['chainvalue'] .= (!$first_line && $linechainvalue ? "\n" : '') . $linechainvalue;
        $post_index['postmessageraw'] .= (!$first_line && $linepostmessageraw ? "\n" : '') . $linepostmessageraw;
        $post_index['postmessageview'] .= ($linepostmessageview ? '<div class="line ' . ($first_line ? 'first_line' : '') . '">' . $linepostmessageview . '</div>' : '');
        $post_index['postmessageedit'] .= ($linepostmessageedit ? '<div class="line ' . ($first_line ? 'first_line' : '') . '">' . $linepostmessageedit . '</div>' : '');
        $post_index['postmessageraw_new'] .= trim($line_new) . "\n";

    }

    //Give HTML their frame:
    $view_list_user = view_list_user($save_postid);
    if (strlen($post_index['postmessageview']) || $view_list_user) {
        //Also append featured users:
        $post_index['postmessageview'] = '<div class="i_cache i_postmessageview cache_frame_' . $save_postid . '">' . $post_index['postmessageview'] . $view_list_user . '</div>';
    }
    if (strlen($post_index['postmessageedit']) || $view_list_user) {
        $post_index['postmessageedit'] = '<div class="i_cache i_postmessageedit cache_frame_' . $save_postid . '">' . $post_index['postmessageedit'] . $view_list_user . '</div>';
    }

    if (!intval($chainusercreator)) {
        //Nothing else we need to do:
        return $post_index;
    }

    //Save Found references to remove the ones who exist in DB:

    if (intval($save_postid)) {

        $chainkey = 0;

        $saved_items = $CI->Chains->read(array(
            'chainusertype IN (' . join(',', $CI->config->item('userids___1696899')) . ')' => null, //All possible refereces
            'chainpostinput' => intval($save_postid),
        ), array(), 0, 0, array('chainkey' => 'ASC'));

        //Nothing else we need to do:
        foreach ($saved_items as $x) {

            if (isset($post_references[$chainkey]) && is_array($post_references[$chainkey])) {
                //We have it, see if it matches or needs updating:
                $changed = false;
                foreach ($post_references[$chainkey] as $key => $value) {
                    if ($x[$key].'' != $value.'') {
                        //Updating needed:
                        if($chainusercreator > 0){
                            $post_references[$chainkey]['chainusercreator'] = $chainusercreator;
                        }
                        $post_index['actionstats']['update_attempt']++;
                        $post_index['actionstats']['update_success'] += $CI->Chains->update($x['chainid'], $post_references[$chainkey]);
                        $changed = true;
                        break;
                    }
                }
                if(!$changed){
                    $post_index['actionstats']['no_change']++;
                }
            } else {
                //Must be removed:
                $CI->Chains->delete($x['chainid']);
                $post_index['actionstats']['removed']++;
            }

            $chainkey++;
            $post_index['actionstats']['current']++;

        }

        //Any more links left that were not in DB?
        if($chainkey<count($post_references)){
            for ($i = $chainkey; $i < count($post_references); $i++) {
                $post_references[$i]['chainusercreator'] = $chainusercreator;
                $CI->Chains->create($post_references[$i]);
                $post_index['actionstats']['added']++;
            }
        }
    }


    $post_index['postmessageraw_new'] = trim($post_index['postmessageraw_new']);
    $post_index['post_references_count'] = count($post_references);
    $post_index['saved_items_count'] = count($saved_items);
    $post_index['chainkey'] = $chainkey;
    $post_index['post_references'] = $post_references;
    $post_index['saved_items'] = $saved_items;

    return $post_index;

}
